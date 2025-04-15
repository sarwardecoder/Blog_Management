<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use App\Models\Bookmark;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;


class DashboardController extends Controller
{


    public function index()
    {
        // Fetch statistics
        $totalPosts = Post::count();
        $totalComments = Comment::count();
        $totalBookmarks = Bookmark::count();
        $totalLikes = Like::count();

        // Fetch recent posts with category and comments count
        $recentPosts = Post::with('category')
            ->withCount('comments')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Fetch recent activities (notifications)
        $recentActivities = Notification::orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return Inertia::render('Pages/Dashboard', [
            'totalPosts' => $totalPosts,
            'totalComments' => $totalComments,
            'totalBookmarks' => $totalBookmarks,
            'totalLikes' => $totalLikes,
            'recentPosts' => $recentPosts,
            'recentActivities' => $recentActivities
        ]);
    }
}
