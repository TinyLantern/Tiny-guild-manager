<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuildApplication extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'guild_applications';
    protected $fillable = [
        'character_id',
        'guild_id',
        'guild_application_status_id'
    ];

    public function character()
    {
        return $this->belongsTo(Character::class, 'character_id');
    }

    public function guild()
    {
        return $this->belongsTo(Guild::class, 'guild_id');
    }

    public function guild_application_status()
    {
        return $this->belongsTo(GuildApplicationStatus::class, 'guild_application_status_id');
    }
}
