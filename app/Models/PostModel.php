<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PostModel extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'content',
        'media',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function likes() {
    return $this->hasMany(Like::class, 'post_id');
}

  public function comments() {
    return $this->hasMany(Comment::class, 'post_id');
}

  public function isLikedBy($user) {
    if (!$user) return false;
    return $this->likes()->where('user_id', $user->id)->exists();
}


}
