<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostCommentsController extends Controller
{
    public function index(Request $request, $postId)
    {
        // Fetch comments for the post
        $comments = \App\Models\PostComments::with('user:id,name')->where('post_id', $postId)->get();

        return response()->json($comments);
    }
}
