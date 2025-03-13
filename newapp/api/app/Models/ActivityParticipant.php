<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityParticipant extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'activity_id', 
        'character_id', 
        'participated_at',
        'activity_participation_status_id',
    ];

    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function character()
    {
        return $this->belongsTo(Character::class);
    }

    public function activityParticipationStatus()
    {
        return $this->belongsTo(ActivityParticipationStatus::class);
    }
}
