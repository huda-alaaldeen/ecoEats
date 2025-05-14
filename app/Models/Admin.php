<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasApiTokens, HasFactory;

    protected $fillable = ['name', 'email', 'password', 'role_id'];

    protected $hidden = ['password'];

    protected $casts = ['password' => 'hashed'];
}
