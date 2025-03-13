<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Character extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'characters';
    protected $fillable = [
        'character_name',
        'dkp_points',
        'primary_weapon_id',
        'secondary_weapon_id',
        'character_type_id',
        'user_id',
        'guild_rank_id',
        'guild_id',
        'gear_score',
    ];

    public function gearPictures()
    {
        return $this->hasMany(GearPicture::class, 'character_id');
    }

    public function latestGearPicture()
    {
        return $this->hasOne(GearPicture::class, 'character_id')->latestOfMany();
    }

    public function characterType()
    {
        return $this->belongsTo(CharType::class, 'character_type_id');
    }

    public function primaryWeapon()
    {
        return $this->belongsTo(Weapon::class, 'primary_weapon_id');
    }

    public function secondaryWeapon()
    {
        return $this->belongsTo(Weapon::class, 'secondary_weapon_id');
    }

    public function guildRank()
    {
        return $this->belongsTo(GuildRank::class, 'guild_rank_id');
    }

    public function belongsToUser()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function guild()
    {
        return $this->belongsTo(Guild::class, 'guild_id');
    }

}
