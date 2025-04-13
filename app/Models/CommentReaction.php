<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentReaction extends Model
{
    protected $fillable = [
        'user_id',
        'comment_id',
        'type'
    ];

    protected $casts = [
        'type' => 'string'
    ];

    // Relationship with User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relationship with Comment
    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}