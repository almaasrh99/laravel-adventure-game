<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class GameController extends Controller
{
    public function index()
{
    $character = Auth::user()->character->Latest()->first();

    if (!$character) {
        return redirect()->route('character.create');
    }

    return view('game.play', compact('character'));
}
}
