<x-app-layout>
    <x-slot name="header">
        @if (session()->has('message'))
            <div class="bg-green-100 text-green-800 p-2 rounded mb-4">
                {{ session('message') }}
            </div>
        @endif

        <div class="w-full mx-auto bg-white p-6 rounded-lg shadow-lg space-y-6">
            <h2 class="text-2xl font-bold text-center">🗺️ Petualangan</h2>

            @if ( $gameCompleted && $character && $character->hp <= 0)
                <div class="bg-red-100 text-red-700 p-4 rounded text-center">
                    💀 <strong>GAME OVER</strong><br>
                    Karaktermu telah dikalahkan. Mulai kembali atau buat karakter baru.
                </div>
                <div class="flex justify-center">
                    <button wire:click="continueGame"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-2">
                        Lanjutkan
                    </button>
                   <a href="{{ route('character.create') }}" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mt-2 ml-2">
                        Buat Karakter Baru
                    </a>
                </div>

            @elseif ($gameCompleted && $character && $character->hp > 0)
                <div class="bg-green-100 text-gray-700 p-4 rounded text-center">
                    <img src="https://static.vecteezy.com/system/resources/previews/023/266/839/non_2x/game-emblem-logo-free-png.png" alt="Game Emblem" class="w-72 mx-auto mb-2">
                    🏆 Kamu telah menamatkan game ini!<br>
                    🔄 Semua monster telah dihidupkan kembali. Petualanganmu berlanjut!
                </div>
                <div class="flex justify-center">
                    <button wire:click="continueGame"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-2">
                        Lanjutkan Petualangan
                    </button>
                </div>

            @elseif ($encounter === 'battle')
                @if ($monster && $monster->hp > 0 && $character && $character->hp > 0)
                    <div class="flex flex-col md:flex-row gap-6">
                        {{-- Log Pertempuran Kiri --}}
                        <div class="md:w-1/2 w-full bg-gray-100 p-4 rounded overflow-y-auto max-h-[400px]">
                            <h3 class="font-semibold text-gray-800 mb-2">📜 Log Pertempuran:</h3>
                            @forelse($combatLog as $log)
                                <p class="text-sm text-gray-800">👉 {{ $log }}</p>
                            @empty
                                <p class="text-sm text-gray-500">Belum ada log pertempuran.</p>
                            @endforelse
                        </div>

                        {{-- Battle Interface Kanan --}}
                        <div class=" w-full text-center space-y-4">
                            <div class="relative w-full">
                                <img src="{{ asset($monster->image) }}" alt="{{ $monster->name }}"
                                    class="w-56 object-contain mx-auto rounded-lg shadow">
                                <div class="absolute top-0 left-0 w-full bg-black bg-opacity-50 text-white text-md p-2 rounded-t-lg">
                                    <strong>{{ $monster->name }}</strong>
                                </div>
                            </div>

                            {{-- Monster Health Bar --}}
                            @php
                                $hpPercent = ($monster->hp / $monster->max_hp) * 100;
                                $barColor = $hpPercent > 50 ? 'bg-green-500' : ($hpPercent > 20 ? 'bg-yellow-500' : 'bg-red-500');
                            @endphp
                            <div class="w-full bg-gray-300 h-4 rounded-full overflow-hidden">
                                <div class="h-full {{ $barColor }}" style="width: {{ $hpPercent }}%"></div>
                            </div>
                            <p class="text-sm font-semibold text-gray-700">{{ $monster->hp }} / {{ $monster->max_hp }} HP</p>

                            {{-- Player Health Bar --}}
                            @php
                                $charHpPercent = ($character->hp / $character->max_hp) * 100;
                                $charBarColor = $charHpPercent > 50 ? 'bg-green-500' : ($charHpPercent > 20 ? 'bg-yellow-500' : 'bg-red-500');
                            @endphp
                            <div class="">
                                <div class="flex flex-col mx-auto gap-4 justify-start items-start">
                                     <img src="{{ asset($character->avatar) }}" alt="Player" class="w-24 h-24 rounded-full object-cover border-2 border-blue-500">
                               <div class="flex gap-2 justify-start items-center">
                                 <p class="text-lg font-semibold">{{ $character->name }}</p>
                                 <p class="text-sm font-semibold text-gray-600"> ( {{ $character->class }} - Lv. {{ $character->level }} )</p>
                               
                               </div>

                                </div>
                               
                                <div class="w-full bg-gray-300 h-4 rounded-full overflow-hidden">
                                    <div class="h-full {{ $charBarColor }}" style="width: {{ $charHpPercent }}%"></div>
                                </div>
                                <div class="flex justify-between    items-center">
                                     <p class="text-sm font-semibold">EXP : <span class="text-orange-500">{{ $character->exp }}</span></p>
                                <p class="text-sm font-semibold text-gray-700 mt-1">{{ $character->hp }} / {{ $character->max_hp }} HP</p>
                                </div>
                            </div>

                            {{-- Attack Button --}}
                            <div class="flex gap-4 mx-auto justify-center">
                                <button wire:click="attack"
                                class="bg-red-600 text-white px-6 py-2 rounded-lg hover:bg-red-700 transition">
                                ⚔️ Serang!
                            </button>
                            <button wire:click="heal"
                                class="bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                                🩹 Heal (+{{ $healAmount }})
                            </button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center text-red-500">
                        ⚠️ Tidak ada monster atau karakter untuk bertarung.
                    </div>
                    <div class="flex justify-center mt-4">
                        <button wire:click="findMonster"
                            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                            Cari Monster Lain
                        </button>
                    </div>
                @endif
            @endif
        </div>
    </x-slot>
</x-app-layout>
