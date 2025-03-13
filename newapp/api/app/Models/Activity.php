<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 
        'description', 
        'start_time', 
        'end_time', 
        'dkp', 
        'guild_id',
        'processed',
    ];

    public function guild()
    {
        return $this->belongsTo(Guild::class);
    }

    public function participants()
    {
        return $this->hasMany(ActivityParticipant::class);
    }
}
