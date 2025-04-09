<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TagController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tags = Tag::where('user_id', Auth::id())
            ->withCount('websites')
            ->orderBy('name')
            ->get();
            
        return view('tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('tags.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tags')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                }),
            ],
            'color' => 'nullable|string|max:7',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $slug = Tag::createSlug($request->input('name'));
        
        $tag = Tag::create([
            'name' => $request->input('name'),
            'slug' => $slug,
            'color' => $request->input('color', '#7B42F6'),
            'user_id' => Auth::id(),
        ]);
        
        return redirect()->route('tags.index')
            ->with('success', 'Tag created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tag $tag)
    {
        // Make sure the tag belongs to the authenticated user
        if ($tag->user_id !== Auth::id()) {
            abort(403);
        }
        
        $websites = $tag->websites()
            ->where('user_id', Auth::id())
            ->orderBy('name')
            ->paginate(10);
            
        return view('tags.show', compact('tag', 'websites'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tag $tag)
    {
        // Make sure the tag belongs to the authenticated user
        if ($tag->user_id !== Auth::id()) {
            abort(403);
        }
        
        return view('tags.edit', compact('tag'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tag $tag)
    {
        // Make sure the tag belongs to the authenticated user
        if ($tag->user_id !== Auth::id()) {
            abort(403);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:50',
                Rule::unique('tags')->where(function ($query) {
                    return $query->where('user_id', Auth::id());
                })->ignore($tag->id),
            ],
            'color' => 'nullable|string|max:7',
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        // Update the tag
        $tag->update([
            'name' => $request->input('name'),
            'color' => $request->input('color', '#7B42F6'),
        ]);
        
        return redirect()->route('tags.index')
            ->with('success', 'Tag updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tag $tag)
    {
        // Make sure the tag belongs to the authenticated user
        if ($tag->user_id !== Auth::id()) {
            abort(403);
        }
        
        // Delete the tag (the relationship will be handled by the database)
        $tag->delete();
        
        return redirect()->route('tags.index')
            ->with('success', 'Tag deleted successfully!');
    }
}
