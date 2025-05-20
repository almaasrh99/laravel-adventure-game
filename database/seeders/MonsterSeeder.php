<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Monster;

class MonsterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       Monster::create([
    'name' => 'Slime',
    'hp' => 20,
    'max_hp' => 20,
    'exp_reward' => 5,
    'attack' => 5,
    'defense' => 2,
    'image' => 'https://img.freepik.com/premium-psd/slime-monster-isolated-transparent-background_454461-17198.jpg',
]);

Monster::create([
    'name' => 'Goblin',
    'hp' => 35,
    'max_hp' => 35,
    'exp_reward' => 10,
    'attack' => 10,
    'defense' => 5,
    'image' => 'https://pngimg.com/d/goblin_PNG1.png',
]);

Monster::create([
    'name' => 'Skeleton',
    'hp' => 40,
    'max_hp' => 40,
    'exp_reward' => 12,
    'attack' => 12,
    'defense' => 6,
    'image' => 'https://www.dndbeyond.com/avatars/thumbnails/30835/849/1000/1000/638063922565505819.png',
]);

Monster::create([
    'name' => 'Orc',
    'hp' => 50,
    'max_hp' => 50,
    'exp_reward' => 15,
    'attack' => 15,
    'defense' => 8,
    'image' => 'https://pngimg.com/uploads/orc/orc_PNG25.png',
]);


    }
}
