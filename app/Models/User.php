<?php

namespace App\Models;

use App\Models\PostModel; // ✅ Import Post model
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'profile_photo',
        'password',
        'role',
        'is_approved',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ✅ Each user can have many posts
    public function posts()
    {
        return $this->hasMany(PostModel::class);
    }

    public function profilePhotoUrl()
{
    return $this->photo
        ? asset('storage/' . $this->photo)
        : asset('images/default-avatar.png');
}

}
