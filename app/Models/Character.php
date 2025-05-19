<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Character extends Model
{
    
 protected $fillable = [
   'name',
   'user_id',
   'hp',
   'attack',
   'defense',
   'level',
   'exp'
 ];
}
