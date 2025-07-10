<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with('user:id,name', 'tags:name')->paginate(10);
        return response()->json($posts);
    }

    public function create()
    {
        return response()->json([
            'message' => 'Create post',
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image file
            'is_published' => 'boolean',
            'tags.*' => 'integer|exists:tags,id', // Validate each tag ID
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public'); // Store the image in the public disk
            $data['image'] = $imagePath; // Add the image path to the data array
        } else {
            $data['image'] = null; // Set image to null if no file is uploaded
        }

        $post = Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => Auth::id(), // Assuming the user is authenticated
            'slug' => Str::slug($data['title'], '-', 'ru'), // Generate a slug from the title
            'image' => $data['image'] ?? null,
            'is_published' => $data['is_published'] ?? false,
            'published_at' => $data['is_published'] ? now() : null,
            'views' => 0,
            'likes' => 0,
        ]);


        $post->tags()->attach($data['tags'] ?? []); // Attach tags if provided

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post->load('tags'), // Load tags relationship
        ], 201);
    }

    public function show(Post $post)
    {
        return response()->json([
            'post' => $post->load('user:id,name', 'tags:name'), // Load user and tags relationships
        ]);
    }

    public function edit(int $id)
    {
        $post = Post::findOrFail($id);
        return view('posts', compact('post'));
    }

    public function update(Request $request, $id)
    {
        $post = Post::findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validate image file
            'is_published' => 'boolean',
            'tags.*' => 'integer|exists:tags,id', // Validate each tag ID
        ]);

        $post->update($data);

        $post->tags()->sync($data['tags'] ?? []); // Sync tags

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post->load('tags:name'), // Load tags relationship
        ]);
    }

    public function destroy(int $id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
        ]);

    }

    public function user_posts()
    {
        $posts = Post::where('user_id', Auth::id())->with('user:id,name', 'tags:name')->paginate(10);
        return response()->json($posts);
    }
}
