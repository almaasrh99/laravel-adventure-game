<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monster extends Model
{
    protected $fillable = [
        'name', 'hp', 'max_hp', 'attack', 'defense', 'image'
        ];
}
