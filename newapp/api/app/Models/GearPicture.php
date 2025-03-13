<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GearPicture extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'gear_pictures';
    protected $fillable = [
        'character_id',
        'file_path',
    ];

    public function character()
    {
        return $this->belongsTo(Character::class, 'character_id');
    }
}

