<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuildRank extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'guild_ranks';
    protected $fillable = [
        'guild_rank'
    ];
}
