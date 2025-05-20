<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    
 protected $fillable = [
   'name',
   'user_id',
   'class',
   'hp',
   'attack',
   'defense',
   'level',
   'exp',
   'max_hp',
   'avatar'
 ];
}
