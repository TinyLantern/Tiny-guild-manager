<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GuildApplicationStatus extends Model
{
    use HasFactory;

    protected $table = 'guild_application_statuses';
    protected $fillable = [
        'status'
    ];

    public function characters()
    {
        return $this->hasMany(Guild::class, 'guild_id');
    }
}
