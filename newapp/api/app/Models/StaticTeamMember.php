<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticTeamMember extends Model
{
    use HasFactory;

    protected $fillable = ['team_id', 'character_id'];
}
