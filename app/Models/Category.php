<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Optional: Define relationship to posts or other models
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
