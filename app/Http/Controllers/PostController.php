<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Http\Resources\PostResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PostController extends Controller
{
    /**
     * Display a listing of the authenticated user's posts.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $posts = $user->posts;
        return response()->json($posts);
    }



    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'body' => 'required|string',
            'cover_image' => 'required|string',
            'pinned' => 'required|boolean',
            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ]);
        $user = auth()->user();
        $post = $user->posts()->create($validatedData);
        $post->tags()->sync($validatedData['tags']);

        return response()->json($post,201);
    }

    /**
     * Display the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        $user = auth()->user();

        if ($post->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json($post);
    }
    /**
     * Update the specified post in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $request->validate([
            'title' => 'required|max:255',
            'body' => 'required',
            'cover_image' => 'image',
            'pinned' => 'boolean',
            'tags' => 'array',
            'tags.*' => 'exists:tags,id',
        ]);

        $post->update($request->only('title', 'body', 'pinned'));

        if ($request->hasFile('cover_image')) {
            $post->cover_image = $request->file('cover_image')->store('images');
            $post->save();
        }

        if ($request->has('tags')) {
            $post->tags()->sync($request->input('tags'));
        }

        return new PostResource($post);
    }

    /**
     * Soft delete the specified post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $user = auth()->user();

        if ($post->user_id !== $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }



    public function deleted()
    {
        $user = auth()->user();
        $deletedPosts = $user->posts()->onlyTrashed()->get();

        return response()->json($deletedPosts);
    }

    /**
     * Restore the specified deleted post.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function restore(Post $post)
    {
        $this->authorize('restore', $post);

        $post->restore();

        return new PostResource($post);

    }
}
