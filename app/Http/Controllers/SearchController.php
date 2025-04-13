<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class SearchController extends Controller
{
    public function searchPosts(Request $request)
    {
        $query = Post::with(['user', 'tags', 'category'])
            ->select('posts.*')
            ->join('users', 'posts.user_id', '=', 'users.id')
            ->leftJoin('post_tag', 'posts.id', '=', 'post_tag.post_id')
            ->leftJoin('tags', 'post_tag.tag_id', '=', 'tags.id')
            ->where('posts.status', 'published')
            ->groupBy('posts.id');

        // Search by title or content
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('posts.title', 'like', "%{$searchTerm}%")
                  ->orWhere('posts.content', 'like', "%{$searchTerm}%");
            });
        }

        // Filter by username
        if ($request->has('username')) {
            $query->where('users.name', 'like', "%{$request->username}%");
        }

        // Filter by tags
        if ($request->has('tags')) {
            $tags = explode(',', $request->tags);
            $query->whereHas('tags', function($q) use ($tags) {
                $q->whereIn('tags.name', $tags);
            });
        }

        $posts = $query->latest()->paginate(10);

        return response()->json([
            'status' => 'success',
            'posts' => $posts
        ]);
    }
}