<?php

namespace App\Console\Commands;

use App\Models\Screenshot;
use App\Models\Website;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CompareScreenshotsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'screenshots:compare
                            {website_id? : ID of the website to compare screenshots for}
                            {--screenshot_id= : Compare specific screenshot with baseline}
                            {--last : Compare the last two screenshots}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Compare website screenshots and highlight differences';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $websiteId = $this->argument('website_id');
        $screenshotId = $this->option('screenshot_id');
        $compareLast = $this->option('last');
        
        if ($websiteId) {
            // Process specific website
            $this->processWebsite($websiteId, $screenshotId, $compareLast);
        } else {
            // Process all websites with screenshots
            $websites = Website::has('screenshots', '>=', 2)->get();
            
            if ($websites->isEmpty()) {
                $this->warn('No websites found with multiple screenshots to compare.');
                return 0;
            }
            
            $this->info("Found {$websites->count()} websites with screenshots to compare.");
            $progressBar = $this->output->createProgressBar($websites->count());
            $progressBar->start();
            
            foreach ($websites as $website) {
                $this->processWebsite($website->id, null, true);
                $progressBar->advance();
            }
            
            $progressBar->finish();
            $this->newLine();
            $this->info('Screenshot comparison completed for all websites.');
        }
        
        return 0;
    }
    
    /**
     * Process comparison for a specific website
     */
    private function processWebsite($websiteId, $screenshotId = null, $compareLast = false)
    {
        $website = Website::find($websiteId);
        
        if (!$website) {
            $this->error("Website with ID {$websiteId} not found.");
            return;
        }
        
        $this->info("Processing screenshots for: {$website->name}");
        
        if ($screenshotId) {
            // Compare specific screenshot with baseline
            $screenshot = Screenshot::find($screenshotId);
            
            if (!$screenshot || $screenshot->website_id != $website->id) {
                $this->error("Screenshot with ID {$screenshotId} not found for this website.");
                return;
            }
            
            $baseline = Screenshot::where('website_id', $website->id)
                ->where('is_baseline', true)
                ->first();
            
            if (!$baseline) {
                $this->warn("No baseline screenshot found for {$website->name}.");
                return;
            }
            
            $this->compareScreenshots($baseline, $screenshot);
            
        } elseif ($compareLast) {
            // Compare last two screenshots
            $screenshots = Screenshot::where('website_id', $website->id)
                ->orderBy('created_at', 'desc')
                ->limit(2)
                ->get();
            
            if ($screenshots->count() < 2) {
                $this->warn("Not enough screenshots found for {$website->name}.");
                return;
            }
            
            $this->compareScreenshots($screenshots[1], $screenshots[0]);
        } else {
            // Compare all screenshots with baseline
            $baseline = Screenshot::where('website_id', $website->id)
                ->where('is_baseline', true)
                ->first();
            
            if (!$baseline) {
                $this->warn("No baseline screenshot found for {$website->name}.");
                return;
            }
            
            $screenshots = Screenshot::where('website_id', $website->id)
                ->where('id', '!=', $baseline->id)
                ->orderBy('created_at', 'desc')
                ->get();
            
            if ($screenshots->isEmpty()) {
                $this->warn("No non-baseline screenshots found for {$website->name}.");
                return;
            }
            
            $progressBar = $this->output->createProgressBar($screenshots->count());
            $progressBar->start();
            
            foreach ($screenshots as $screenshot) {
                $this->compareScreenshots($baseline, $screenshot);
                $progressBar->advance();
            }
            
            $progressBar->finish();
            $this->newLine();
        }
    }
    
    /**
     * Compare two screenshots and generate a diff image
     */
    private function compareScreenshots(Screenshot $baseline, Screenshot $screenshot)
    {
        try {
            $manager = new ImageManager(new Driver());
            
            // Get the file paths
            $baselinePath = Storage::path('public/' . $baseline->path);
            $screenshotPath = Storage::path('public/' . $screenshot->path);
            
            if (!file_exists($baselinePath) || !file_exists($screenshotPath)) {
                Log::error("Screenshot files missing for comparison: {$baseline->id} or {$screenshot->id}");
                return;
            }
            
            // Load the images
            $baselineImg = $manager->read($baselinePath);
            $screenshotImg = $manager->read($screenshotPath);
            
            // Ensure both images are the same size
            $width = min($baselineImg->width(), $screenshotImg->width());
            $height = min($baselineImg->height(), $screenshotImg->height());
            
            $baselineImg = $baselineImg->crop($width, $height);
            $screenshotImg = $screenshotImg->crop($width, $height);
            
            // Create a diff image
            $diffPath = "screenshots/{$screenshot->website_id}/diff_{$baseline->id}_{$screenshot->id}.png";
            $diffFullPath = Storage::path('public/' . $diffPath);
            
            // Ensure the directory exists
            if (!file_exists(dirname($diffFullPath))) {
                mkdir(dirname($diffFullPath), 0755, true);
            }
            
            // Create an overlay image
            $overlayPath = "screenshots/{$screenshot->website_id}/overlay_{$baseline->id}_{$screenshot->id}.png";
            $overlayFullPath = Storage::path('public/' . $overlayPath);
            
            // Create a blank canvas for the diff image
            $diffImg = $manager->create($width, $height);
            
            // Generate diff by comparing pixels
            $diffCount = 0;
            
            // Convert images to native PHP format for pixel access
            $baselineResource = $baselineImg->encode('png')->toString();
            $screenshotResource = $screenshotImg->encode('png')->toString();
            
            $baselineGd = imagecreatefromstring($baselineResource);
            $screenshotGd = imagecreatefromstring($screenshotResource);
            $diffGd = imagecreatetruecolor($width, $height);
            
            // Set background to transparent
            imagesavealpha($diffGd, true);
            $trans_colour = imagecolorallocatealpha($diffGd, 0, 0, 0, 127);
            imagefill($diffGd, 0, 0, $trans_colour);
            
            // Set diff color (red with some transparency)
            $diffColor = imagecolorallocatealpha($diffGd, 255, 0, 0, 64);
            
            // Compare pixels and create diff
            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $baselineColor = imagecolorat($baselineGd, $x, $y);
                    $screenshotColor = imagecolorat($screenshotGd, $x, $y);
                    
                    // Compare colors (ignore slight differences)
                    if (abs(($baselineColor & 0xFF) - ($screenshotColor & 0xFF)) > 5 ||
                        abs((($baselineColor >> 8) & 0xFF) - (($screenshotColor >> 8) & 0xFF)) > 5 ||
                        abs((($baselineColor >> 16) & 0xFF) - (($screenshotColor >> 16) & 0xFF)) > 5) {
                        // Mark the difference
                        imagesetpixel($diffGd, $x, $y, $diffColor);
                        $diffCount++;
                    }
                }
            }
            
            // Save the diff image
            imagepng($diffGd, $diffFullPath);
            
            // Create an overlay image (screenshotImg with diff highlighted)
            $overlayGd = imagecreatefromstring($screenshotResource);
            
            // Copy the diff onto the overlay
            imagecopy($overlayGd, $diffGd, 0, 0, 0, 0, $width, $height);
            
            // Save the overlay
            imagepng($overlayGd, $overlayFullPath);
            
            // Clean up
            imagedestroy($baselineGd);
            imagedestroy($screenshotGd);
            imagedestroy($diffGd);
            imagedestroy($overlayGd);
            
            // Calculate diff percentage
            $diffPercentage = ($diffCount / ($width * $height)) * 100;
            
            // Update screenshot metadata
            $metadata = $screenshot->metadata ?? [];
            $metadata['comparison'] = [
                'baseline_id' => $baseline->id,
                'diff_path' => $diffPath,
                'overlay_path' => $overlayPath,
                'diff_percentage' => round($diffPercentage, 2),
                'diff_count' => $diffCount,
                'compared_at' => now()->toIso8601String()
            ];
            
            $screenshot->metadata = $metadata;
            $screenshot->save();
            
            $this->info("  Comparison complete: {$diffCount} different pixels ({$diffPercentage}%)");
            
            return [
                'diff_path' => $diffPath,
                'overlay_path' => $overlayPath,
                'diff_percentage' => $diffPercentage
            ];
        } catch (\Exception $e) {
            Log::error("Error comparing screenshots: " . $e->getMessage());
            $this->error("  Error comparing screenshots: " . $e->getMessage());
            return null;
        }
    }
}
