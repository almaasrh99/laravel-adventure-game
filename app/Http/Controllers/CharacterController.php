<?php

namespace App\Http\Controllers;


use App\Models\Character;
use App\Models\Monster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CharacterController extends Controller
{
    public function create()
    {
        return view('character.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:20',
            'class' => 'required|in:Warrior,Archer,Mage',
        ]);

        $baseStats = match ($request->class) {
            'Warrior' => ['hp' => 110, 'attack' => 15, 'defense' => 12],
            'Archer'  => ['hp' => 105, 'attack' => 12, 'defense' => 10],
            'Mage'    => ['hp' => 120,  'attack' => 10, 'defense' => 8],
        };

        $avatarPaths = [
            'Warrior' => 'images/avatars/warrior.png',
            'Archer'  => 'images/avatars/archer.png',
            'Mage'    => 'images/avatars/mage.png',
        ];

        Character::create([
            'user_id' => Auth::id(),
            'name'    => $request->name,
            'class'   => $request->class,
            'hp'      => $baseStats['hp'],
            'attack'  => $baseStats['attack'],
            'defense' => $baseStats['defense'],
            'level'   => 1,
            'exp'     => 0,
            'max_hp'  => $baseStats['hp'],
            'avatar'  => $avatarPaths[$request->class],
        ]);

        // 🔁 Reset semua monster HP ke max_hp
   $monsters = Monster::all();
    foreach ($monsters as $monster) {
    $monster->hp = $monster->max_hp;
    $monster->save();
}


        return redirect()->route('game');
    }
}
