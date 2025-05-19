<div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow-lg">
    <h1 class="text-2xl font-bold mb-4">Selamat datang, {{ $character->name }}!</h1>

    <div class="space-y-2">
        <p><strong>HP:</strong> {{ $character->hp }}</p>
        <p><strong>Attack:</strong> {{ $character->attack }}</p>
        <p><strong>Defense:</strong> {{ $character->defense }}</p>
        <p><strong>Level:</strong> {{ $character->level }}</p>
        <p><strong>EXP:</strong> {{ $character->exp }}</p>
    </div>

    <div class="mt-6">
        <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700" wire:click="startAdventure">
            Mulai Petualangan!
        </button>
    </div>
</div>
