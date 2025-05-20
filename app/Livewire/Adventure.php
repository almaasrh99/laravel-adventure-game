<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Character;
use App\Models\CombatLog;


use App\Models\Monster;
use Illuminate\Support\Facades\Auth;

use function Termwind\render;

class Adventure extends Component
{
    public $character;
    public $encounter = null;
    public $monsterId;
    public $combatLog = [];
    public $gameCompleted = false;

    public $healAmount = 0;


    public function mount()
    {
        $this->character = Auth::user()->character->latest()->first();
        $this->loadCombatLog();
        $this->explore();
        if (session()->has('current_monster_id')) {
            $this->monsterId = session('current_monster_id');
            $this->encounter = 'battle';
        }
        $this->checkGameCompletion();
        $this->setHealAmount();
    }

    public function getMonsterProperty()
    {
        return $this->monsterId ? Monster::find($this->monsterId) : null;
    }

    public function explore()
    {
        // Cegah explore jika pertempuran masih berlangsung
        $monster = $this->monster;
        if ($this->encounter === 'battle' && $monster && $monster->hp > 0) {
            return;
        }

        // Pilih monster berdasarkan max_hp terkecil yang masih hidup
        $newMonster = Monster::where('hp', '>', 0)
            ->orderBy('max_hp', 'asc')
            ->first();

        if ($newMonster) {
            $this->monsterId = $newMonster->id;
            $this->encounter = 'battle';
            $this->loadCombatLog();
        }
    }



    public function attack()
    {
        $character = $this->character;

        // 1) Karakter mati?
        if (!$character || $character->hp <= 0) {
            $this->monsterId = null;
            $this->encounter = null;
            return redirect()->route('adventure');
        }

        // 2) Ambil monster yang sedang dihadapi
        $monster = $this->monster;

        // 3) Kalau belum ada monster atau dia sudah mati → cari monster baru
        if (!$monster || $monster->hp <= 0) {
            $this->checkGameCompletion();
            if ($this->gameCompleted) {
                $this->monsterId = null;
                $this->encounter = null;
                $this->loadCombatLog();
                return redirect()->route('adventure');
            }

            $target = Monster::where('hp', '>', 0)->inRandomOrder()->first();

            if (!$target) {
                // Semua monster sudah mati
                $this->checkGameCompletion();
                $this->monsterId = null;
                $this->encounter = null;
                $this->loadCombatLog();
                return redirect()->route('adventure');
            }

            $monster = $target;
            $this->monsterId = $monster->id;
            $this->encounter = 'battle';
        }

        // 4) Serang monster aktif
        $damageToMonster = max(0, $character->attack + rand(0, 10) - $monster->defense);
        $monster->hp = max(0, $monster->hp - $damageToMonster);
        $monster->save();

        CombatLog::create([
            'character_id' => $character->id,
            'message' => "⚔️ Kamu menyerang dan memberi {$damageToMonster} damage ke {$monster->name}.",
        ]);

        // 5) Jika monster mati, level up & prepare next
        if ($monster->hp === 0) {
            CombatLog::create([
                'character_id' => $character->id,
                'message' => "💀 Kamu mengalahkan {$monster->name}!",
            ]);
            $character->exp += $monster->exp_reward;
            $character->save();

            CombatLog::create([
                "character_id" => $character->id,
                "message" => "🎉 Kamu mendapatkan {$monster->exp_reward} EXP. Kamu sekarang memiliki {$character->exp} EXP.",
            ]);

            // next monster dipilih saat klik attack berikutnya
            $this->monsterId = null;
            $this->encounter = null;

            $this->loadCombatLog();
            return redirect()->route('adventure');
        }

        // 6) Monster serang balik jika masih hidup
        $damageToPlayer = max(0, ($monster->attack + rand(0, 100)) - $character->defense);
        $character->hp = max(0, $character->hp - $damageToPlayer);
        $character->save();

        CombatLog::create([
            'character_id' => $character->id,
            'message' => "💥 {$monster->name} menyerang dan memberi {$damageToPlayer} damage padamu!",
        ]);

        if ($character->hp === 0) {
            CombatLog::create([
                'character_id' => $character->id,
                'message' => "💀 Kamu dikalahkan oleh {$monster->name}...",
            ]);
            $this->monsterId = null;
            $this->encounter = null;
        }

        $this->loadCombatLog();
        return redirect()->route('adventure');
    }



    public function loadCombatLog()
    {
        $this->combatLog = CombatLog::where('character_id', $this->character->id)
            ->latest()
            ->take(10)
            ->pluck('message')
            ->toArray();
    }

    public function findMonster()
    {
        $monster = Monster::where('hp', '>', 0)->inRandomOrder()->first();

        if (!$monster) {
            // Tidak ada monster hidup, mungkin beri pesan atau lakukan sesuatu
            session()->flash('message', 'Tidak ada monster yang tersedia untuk dilawan.');
            $this->monsterId = null;
            $this->encounter = null;
            $this->loadCombatLog();

            return redirect()->route('adventure');
        }

        $this->monsterId = $monster->id;
        $this->encounter = 'battle';
        $this->loadCombatLog();
        return redirect()->route('adventure');
    }


    public function checkGameCompletion()
    {
        $aliveMonsters = Monster::where('hp', '>', 0)->count();
        $this->gameCompleted = ($aliveMonsters === 0 || $this->character->hp <= 0);
    }

    public function resetMonsters()
    {
        $monsters = Monster::all();
        foreach ($monsters as $monster) {
            $monster->hp = $monster->max_hp;
            $monster->save();
        }

        CombatLog::create([
            'character_id' => $this->character->id,
            'message' => "🎉 Game selesai! Semua monster telah dikalahkan.",
        ]);

        $this->gameCompleted = false;
    }



public function setHealAmount()
{
    switch (strtolower($this->character->class)) {
        case 'mage':
            $this->healAmount = 20;
            break;
        case 'warrior':
            $this->healAmount = 15;
            break;
        case 'archer':
        default:
            $this->healAmount = 10;
            break;
    }
}

  public function heal()
{
    $maxHp = $this->character->max_hp;

    if (is_null($this->character->hp)) {
        $this->character->hp = 0;
    }

    // Tentukan heal amount berdasarkan class
   

    // Tambahkan HP dengan batas maksimal max HP
    $this->character->hp = min($maxHp, $this->character->hp + $this->healAmount);

    
    // Pesan jika HP sudah penuh
    if ($this->character->hp === $maxHp) {
        CombatLog::create([
            "character_id" => $this->character->id,
            "message" => "✨ HP kamu sudah penuh! Tidak bisa menambah HP lagi.",
        ]);
    } else {
         // Simpan pesan heal ke CombatLog
        CombatLog::create([
        "character_id" => $this->character->id,
        "message" => "💊 Kamu mendapatkan +{$this->healAmount} HP. HP kamu sekarang {$this->character->hp}.",
    ]);
    }

   


    $this->character->save();
    $this->loadCombatLog();

    return redirect()->route('adventure');
}


    public function continueGame()
    {
        // Reset karakter HP ke nilai default
        if ($this->character) {
            $this->character->hp = $this->character->max_hp; // reset sesuai default HP karakter 
            $this->character->save();
        }


        // Reset state encounter dan monsterId
        $this->monsterId = null;
        $this->encounter = null;

        $this->gameCompleted = false;
        // Mulai ulang explore (cari monster baru)
        $this->explore();
        $this->resetMonsters();
        $this->resetCombatLog();

        // Muat ulang combat log
        $this->loadCombatLog();
        return redirect()->route('adventure');
    }

    public function resetCombatLog()
    {
        CombatLog::where('character_id', $this->character->id)->delete();
        $this->loadCombatLog();
    }



    public function render()
    {
        return view('livewire.adventure', [
            'monster' => $this->monster,
        ]);
    }
}
