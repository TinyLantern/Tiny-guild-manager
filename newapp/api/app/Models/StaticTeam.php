<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticTeam extends Model
{
    use HasFactory;

    protected $fillable = ['team_name', 'guild_id'];

    public function guild()
    {
        return $this->belongsTo(Guild::class);
    }

    public function members()
    {
        return $this->belongsToMany(Character::class, 'static_team_members', 'team_id', 'character_id')
                    ->withPivot('id');
    }
    
}