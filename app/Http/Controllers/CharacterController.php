<?php

namespace App\Http\Controllers;
use App\Models\Character;
use Illuminate\Http\Request;
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
        ]);

        Character::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'hp' => 100,
            'attack' => 10,
            'defense' => 5,
            'level' => 1,
            'exp' => 0,
        ]);

        return redirect()->route('game');
    }
}
