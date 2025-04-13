<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::withCount('posts')->get();
        return response()->json($tags);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:tags,name'
        ]);

        $tag = Tag::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json(['message' => 'Tag created successfully', 'tag' => $tag], 201);
    }

    public function show(string $id)
    {
        $tag = Tag::with(['posts' => function ($query) {
            $query->with(['user', 'category'])->latest();
        }])->findOrFail($id);

        return response()->json($tag);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:50|unique:tags,name,' . $id
        ]);

        $tag = Tag::findOrFail($id);
        $tag->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return response()->json(['message' => 'Tag updated successfully', 'tag' => $tag]);
    }

    public function destroy(string $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->posts()->detach(); // Remove all post associations
        $tag->delete();

        return response()->json(['message' => 'Tag deleted successfully']);
    }

    public function attachToPost(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'tag_ids' => 'required|array',
            'tag_ids.*' => 'exists:tags,id'
        ]);

        $post = Post::findOrFail($request->post_id);
        
        // Check if user is authorized to add tags to this post
        if ($post->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $post->tags()->sync($request->tag_ids);

        return response()->json(['message' => 'Tags attached successfully']);
    }

    public function searchTags(Request $request)
    {
        $request->validate([
            'query' => 'required|string|min:2'
        ]);

        $tags = Tag::where('name', 'like', '%' . $request->query . '%')
            ->withCount('posts')
            ->limit(10)
            ->get();

        return response()->json($tags);
    }
}
