<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(string $postId)
    {
        $comments = Comment::with(['user', 'parent', 'reactions'])
            ->where('post_id', $postId)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        return response()->json($comments);
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required|string',
            'post_id' => 'required|exists:posts,id',
            'parent_id' => 'nullable|exists:comments,id'
        ]);

        $comment = Comment::create([
            'content' => $request->content,
            'post_id' => $request->post_id,
            'user_id' => auth()->id(),
            'parent_id' => $request->parent_id
        ]);

        // Get post author to notify them about the new comment
        $post = Post::find($request->post_id);
        if ($post->user_id !== auth()->id()) {
            $this->notificationService->sendNotification(
                $post->user_id,
                'New comment on your post',
                'Someone commented on your post: ' . substr($request->content, 0, 50) . '...'
            );
        }

        // If this is a reply, notify the parent comment author
        if ($request->parent_id) {
            $parentComment = Comment::find($request->parent_id);
            if ($parentComment->user_id !== auth()->id()) {
                $this->notificationService->sendNotification(
                    $parentComment->user_id,
                    'New reply to your comment',
                    'Someone replied to your comment: ' . substr($request->content, 0, 50) . '...'
                );
            }
        }

        return response()->json(['message' => 'Comment created successfully', 'comment' => $comment], 201);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $comment->update([
            'content' => $request->content
        ]);

        return response()->json(['message' => 'Comment updated successfully', 'comment' => $comment]);
    }

    public function destroy(string $id)
    {
        $comment = Comment::findOrFail($id);

        if ($comment->user_id !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Delete all child comments
        Comment::where('parent_id', $id)->delete();
        $comment->delete();

        return response()->json(['message' => 'Comment deleted successfully']);
    }

    public function getReplies(string $commentId)
    {
        $replies = Comment::with(['user', 'reactions'])
            ->where('parent_id', $commentId)
            ->latest()
            ->paginate(10);

        return response()->json($replies);
    }
}
