<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TagController extends Controller
{
    /**
     * Display a listing of the user's tags.
     */
    public function index()
    {
        $tags = Tag::where('user_id', Auth::id())->orderBy('name')->get();

        return response()->json([
            'status' => 'success',
            'data' => $tags
        ]);
    }

    /**
     * Store a newly created tag.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $tag = Tag::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'color' => $request->color ?? '#6366F1', // Default to indigo
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Tag created successfully',
            'data' => $tag
        ], 201);
    }

    /**
     * Display the specified tag.
     */
    public function show(Tag $tag)
    {
        // Make sure the tag belongs to the authenticated user
        if ($tag->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        // Get count of websites with this tag
        $websiteCount = $tag->websites()->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'tag' => $tag,
                'website_count' => $websiteCount
            ]
        ]);
    }

    /**
     * Update the specified tag.
     */
    public function update(Request $request, Tag $tag)
    {
        // Make sure the tag belongs to the authenticated user
        if ($tag->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'color' => 'nullable|string|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        if ($request->has('name')) {
            $tag->name = $request->name;
            $tag->slug = Str::slug($request->name);
        }
        
        if ($request->has('color')) {
            $tag->color = $request->color;
        }
        
        $tag->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Tag updated successfully',
            'data' => $tag
        ]);
    }

    /**
     * Remove the specified tag.
     */
    public function destroy(Tag $tag)
    {
        // Make sure the tag belongs to the authenticated user
        if ($tag->user_id !== Auth::id()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized'
            ], 403);
        }

        // Detach the tag from all websites
        $tag->websites()->detach();
        
        // Delete the tag
        $tag->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Tag deleted successfully'
        ]);
    }
}
