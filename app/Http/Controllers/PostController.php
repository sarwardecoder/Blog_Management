<?php

namespace App\Http\Controllers;

use App\Helper\JWTToken;
use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\URL;
use Inertia\Inertia;


class PostController extends Controller
{
    public function index()
    {
        try {
            $posts = Post::with(['user', 'comments', 'likes'])->latest()->get();
            return response()->json([
                'status' => 'success',
                'posts' => $posts
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function createPost()
    {
        return Inertia::render('Pages/posts');
    }

    public function store(Request $request)
    {
        try {
            // Verify JWT token
            $token = $request->cookie('token');
            $decoded = JWTToken::verifyToken($token);

            if ($decoded === "unauthorized") {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Unauthorized'
                ], 401);
            }

            // Validate request
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'featured_image' => 'nullable|string',
                'status' => 'required|in:draft,published'
            ]);

            // Create post
            $post = Post::create([
                'user_id' => $decoded->userId,
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'category_id' => $request->category_id,
                'featured_image' => $request->featured_image,
                'status' => $request->status,
                'published_at' => $request->status === 'published' ? now() : null
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully',
                'post' => $post
            ], 201);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $post = Post::with(['user', 'comments', 'likes'])->findOrFail($id);
            return response()->json([
                'status' => 'success',
                'post' => $post
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Post not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Verify JWT token
            $token = $request->cookie('token');
            $decoded = JWTToken::verifyToken($token);

            if ($decoded === "unauthorized") {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Unauthorized'
                ], 401);
            }

            $post = Post::findOrFail($id);

            // Check if user owns the post
            if ($post->user_id !== $decoded->userId) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Unauthorized to update this post'
                ], 403);
            }

            // Validate request
            $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category_id' => 'required|exists:categories,id',
                'featured_image' => 'nullable|string',
                'status' => 'required|in:draft,published'
            ]);

            // Update post
            $post->update([
                'title' => $request->title,
                'slug' => Str::slug($request->title),
                'content' => $request->content,
                'category_id' => $request->category_id,
                'featured_image' => $request->featured_image,
                'status' => $request->status,
                'published_at' => $request->status === 'published' ? now() : null
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Post updated successfully',
                'post' => $post
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            // Verify JWT token
            $token = $request->cookie('token');
            $decoded = JWTToken::verifyToken($token);

            if ($decoded === "unauthorized") {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Unauthorized'
                ], 401);
            }

            $post = Post::findOrFail($id);

            // Check if user owns the post
            if ($post->user_id !== $decoded->userId) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'Unauthorized to delete this post'
                ], 403);
            }

            $post->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Post deleted successfully'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 'failed',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}