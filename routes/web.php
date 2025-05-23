<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotificationController;
use App\Http\Middleware\TokenVerificationMiddleware;

// Public routes
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Route::post('/loginForm', [UserController::class, 'userLoginForm']);

Route::get('/loginForm',function(){
    return Inertia::render('/LoginForm');
}
);

Route::get('/loginPage',function(){
    return Inertia::render('/LoginPage');
}
);

Route::post('/login', [UserController::class, 'userLogin']);

// Route::get('/login', [UserController::class, 'userLogin']);

Route::get('/register', [UserController::class, 'create'])->name('register');
Route::post('/register', [UserController::class, 'store']);



// Protected routes
Route::get('/users/create', [UserController::class, 'create'])->name('users.create');

Route::middleware(TokenVerificationMiddleware::class)->group(function () {

    // User Management Routes

    Route::post('/logout', [UserController::class, 'userLogout']);
    Route::prefix('user')->group(function () {
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

        Route::get('/{id}', [UserController::class, 'userProfile']);
        Route::post('/{id}/update', [UserController::class, 'updateUserProfile']);
        Route::delete('/{id}/delete', [UserController::class, 'deleteUserProfile']);
        Route::post('/{id}/update-password', [UserController::class, 'updateUserPassword']);
        Route::post('/{id}/update-profile-picture', [UserController::class, 'updateUserProfilePicture']);
        Route::post('/{id}/update-cover-picture', [UserController::class, 'updateUserCoverPicture']);
    });

    // Post Management Routes
    Route::prefix('posts')->group(function () {
        // Route::post('/posts/create', [PostController::class, 'createPost'])->name(posts.create);
        Route::get('/', [PostController::class, 'getAllPosts']);
        Route::get('/{id}', [PostController::class, 'getPostById']);
        Route::post('/{id}/update', [PostController::class, 'updatePost']);
        Route::delete('/{id}/delete', [PostController::class, 'deletePost']);

        // Post Comments
        Route::prefix('{id}/comments')->group(function () {
            Route::get('/', [CommentController::class, 'getPostComments']);
            Route::post('/create', [CommentController::class, 'createComment']);
            Route::post('/{commentId}/reply', [CommentController::class, 'replyToComment']);
            Route::get('/{commentId}/replies', [CommentController::class, 'getCommentReplies']);
            Route::post('/{commentId}/update', [CommentController::class, 'updateComment']);
            Route::delete('/{commentId}/delete', [CommentController::class, 'deleteComment']);

            // Comment Replies
            Route::prefix('{commentId}/reply/{replyId}')->group(function () {
                Route::post('/update', [CommentController::class, 'updateReply']);
                Route::delete('/delete', [CommentController::class, 'deleteReply']);
                Route::post('/reply', [CommentController::class, 'replyToReply']);
                Route::get('/replies', [CommentController::class, 'getReplyReplies']);
            });
        });

        // Post Interactions
        Route::prefix('{id}')->group(function () {
            // Likes
            Route::post('/like', [LikeController::class, 'likePost']);
            Route::post('/unlike', [LikeController::class, 'unlikePost']);
            Route::get('/likes', [LikeController::class, 'getPostLikes']);

            // Bookmarks
            Route::post('/bookmark', [BookmarkController::class, 'bookmarkPost']);
            Route::post('/unbookmark', [BookmarkController::class, 'unbookmarkPost']);
            Route::get('/bookmarks', [BookmarkController::class, 'getPostBookmarks']);

            // Comment Interactions
            Route::prefix('comments/{commentId}')->group(function () {
                Route::post('/like', [LikeController::class, 'likeComment']);
                Route::post('/unlike', [LikeController::class, 'unlikeComment']);
                Route::get('/likes', [LikeController::class, 'getCommentLikes']);
                Route::post('/bookmark', [BookmarkController::class, 'bookmarkComment']);
                Route::post('/unbookmark', [BookmarkController::class, 'unbookmarkComment']);
                Route::get('/bookmarks', [BookmarkController::class, 'getCommentBookmarks']);

                // Reply Interactions
                Route::prefix('reply/{replyId}')->group(function () {
                    Route::post('/like', [LikeController::class, 'likeReply']);
                    Route::post('/unlike', [LikeController::class, 'unlikeReply']);
                    Route::get('/likes', [LikeController::class, 'getReplyLikes']);
                    Route::post('/bookmark', [BookmarkController::class, 'bookmarkReply']);
                    Route::post('/unbookmark', [BookmarkController::class, 'unbookmarkReply']);
                    Route::get('/bookmarks', [BookmarkController::class, 'getReplyBookmarks']);
                });
            });
        });
    });

    // Category Management
    Route::prefix('categories')->group(function () {
        Route::post('/category/create', [CategoryController::class, 'createCategory']);
        Route::get('/', [CategoryController::class, 'listCategory']);
    });

    // Notification routes
    Route::prefix('notifications')->group(function () {
        Route::get('/', [NotificationController::class, 'index']);
        Route::get('/unread', [NotificationController::class, 'unread']);
        Route::post('/{id}/read', [NotificationController::class, 'markAsRead']);
        Route::post('/mark-all-read', [NotificationController::class, 'markAllAsRead']);
    });

});
