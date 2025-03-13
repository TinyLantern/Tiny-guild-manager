<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GuildLogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'guild_logos';
    protected $fillable = [
        'guild_id',
        'file_path',
    ];

    public function guild()
    {
        return $this->belongsTo(Guild::class, 'guild_id');
    }
}
