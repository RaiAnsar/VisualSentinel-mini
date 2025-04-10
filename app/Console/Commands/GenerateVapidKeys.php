<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'webpush:generate 
                            {--show : Display the keys instead of modifying files}
                            {--force : Force the operation to run and override existing keys}
                            {--sample : Generate sample keys for testing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate VAPID keys for Web Push notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Check if VAPID keys already exist in .env
        if (!$this->option('force') && 
            env('VAPID_PUBLIC_KEY') && 
            env('VAPID_PRIVATE_KEY')) {
            
            if (!$this->confirm('VAPID keys already exist. Do you want to override them?')) {
                $this->info('Operation cancelled.');
                return 1;
            }
        }

        // Use sample keys if requested or if key generation fails
        if ($this->option('sample')) {
            $vapidKeys = $this->getSampleKeys();
            $this->info('Using sample VAPID keys for testing purposes. DO NOT USE THESE IN PRODUCTION.');
        } else {
            try {
                // Generate the VAPID keys
                $vapidKeys = VAPID::createVapidKeys();
            } catch (\Throwable $e) {
                $this->error('Failed to generate VAPID keys: ' . $e->getMessage());
                if ($this->confirm('Would you like to use sample keys for testing instead?')) {
                    $vapidKeys = $this->getSampleKeys();
                    $this->info('Using sample VAPID keys for testing purposes. DO NOT USE THESE IN PRODUCTION.');
                } else {
                    $this->info('Please try generating keys using a web service like https://web-push-codelab.glitch.me/');
                    return 1;
                }
            }
        }

        if ($this->option('show')) {
            $this->line('VAPID_PUBLIC_KEY=' . $vapidKeys['publicKey']);
            $this->line('VAPID_PRIVATE_KEY=' . $vapidKeys['privateKey']);
            $this->info('Add these keys to your .env file.');
            return 0;
        }

        // Update the .env file with the new keys
        $this->updateEnvironmentFile('VAPID_PUBLIC_KEY', $vapidKeys['publicKey']);
        $this->updateEnvironmentFile('VAPID_PRIVATE_KEY', $vapidKeys['privateKey']);

        $this->info('VAPID keys saved successfully!');
        return 0;
    }

    /**
     * Update the environment file with the given key and value.
     *
     * @param string $key
     * @param string $value
     * @return void
     */
    protected function updateEnvironmentFile($key, $value)
    {
        $path = app()->environmentFilePath();
        $content = file_get_contents($path);

        // Check if the key already exists in the file
        if (preg_match("/^{$key}=/m", $content)) {
            // Replace the existing key-value pair
            $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
        } else {
            // Add the key-value pair at the end of the file
            $content .= "\n{$key}={$value}";
        }

        file_put_contents($path, $content);
    }

    /**
     * Get sample VAPID keys for testing.
     * DO NOT USE THESE IN PRODUCTION!
     *
     * @return array
     */
    protected function getSampleKeys()
    {
        return [
            'publicKey' => 'BLGMvj9MxTjHm8WNpL69RjsXpCckeTZpVt6kMJIE4I6UUn_axMJoqJirH7cDx1EHiIzPKXLTbk79Njie_Au6JoE',
            'privateKey' => '17dJHepJQAcS-hcJUARWkl_0bLQ25uSGnMaM2_2YKi0',
        ];
    }
}
