<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $table = 'users';
    protected $fillable = [
        'discord_global_name',
        'discord_id',
        'discord_avatar',
        'discord_username',
        'last_active_character_id',
    ];

}
