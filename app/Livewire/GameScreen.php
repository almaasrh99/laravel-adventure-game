<?php

namespace App\Livewire;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class GameScreen extends Component
{
    public $character;

    public function mount()
    {
        $this->character = Auth::user()->character->latest()->first();
    }

    public function render()
    {
        return view('livewire.game-screen');
    }

    public function startAdventure()
{
    // Di tahap selanjutnya: random encounter / battle / event
    session()->flash('message', 'Petualangan dimulai!');
    return redirect()->route('adventure');
}

}
