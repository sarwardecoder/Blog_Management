<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{

protected $fillable = [
   
    'name',
    'created_by',//FK User
];


    public function posts() {
        return $this->belongsToMany(Post::class);
    }
    
}
