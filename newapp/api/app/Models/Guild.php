<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guild extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'guilds';
    protected $fillable = [
        'name',
        'server_id',
        'character_id'
    ];

    public function guildLogos()
    {
        return $this->hasMany(GuildLogo::class, 'guild_id');
    }

    public function latestGuildLogo()
    {
        return $this->hasOne(GuildLogo::class, 'guild_id')->latestOfMany();
    }

    public function server()
    {
        return $this->belongsTo(Server::class, 'server_id');
    }

    public function characters()
    {
        return $this->hasMany(Character::class, 'guild_id');
    }

    public function owner()
    {
        return $this->belongsTo(Character::class, 'character_id');
    }
}
