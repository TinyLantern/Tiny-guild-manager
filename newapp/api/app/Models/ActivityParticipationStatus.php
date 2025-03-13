<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActivityParticipationStatus extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'activity_participation_status'
    ];
}
