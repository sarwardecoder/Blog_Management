<?php

namespace App\Http\Controllers;

use App\Models\Bookmark;
use App\Models\Post;
use Illuminate\Http\Request;

class BookmarkController extends Controller
{
    public function index()
    {
        $bookmarks = Bookmark::with(['post.user', 'post.category'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return response()->json($bookmarks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id'
        ]);

        // Check if bookmark already exists
        $existingBookmark = Bookmark::where('user_id', auth()->id())
            ->where('post_id', $request->post_id)
            ->first();

        if ($existingBookmark) {
            return response()->json(['message' => 'Post already bookmarked'], 400);
        }

        $bookmark = Bookmark::create([
            'user_id' => auth()->id(),
            'post_id' => $request->post_id
        ]);

        return response()->json(['message' => 'Post bookmarked successfully', 'bookmark' => $bookmark], 201);
    }

    public function checkBookmarkStatus(string $postId)
    {
        $bookmark = Bookmark::where('user_id', auth()->id())
            ->where('post_id', $postId)
            ->first();

        return response()->json([
            'is_bookmarked' => (bool) $bookmark,
            'bookmark_id' => $bookmark ? $bookmark->id : null
        ]);    
    }

    public function destroy(string $id)
    {
        $bookmark = Bookmark::where('user_id', auth()->id())
            ->where('id', $id)
            ->firstOrFail();

        $bookmark->delete();
        return response()->json(['message' => 'Bookmark removed successfully']);
    }
}
