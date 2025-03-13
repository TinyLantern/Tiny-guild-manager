<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CharType extends Model
{
    use HasFactory;

    protected $table = 'character_types';
    protected $fillable = [
        'type'
    ];

    public function characters()
    {
        return $this->hasMany(Character::class, 'character_type_id');
    }
}
