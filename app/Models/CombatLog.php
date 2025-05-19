<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CombatLog extends Model
{
    protected $fillable = ['character_id', 'message'];

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
