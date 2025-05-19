<x-app-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4">Buat Karakter</h2>
        <form action="{{ route('character.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium">Nama Karakter</label>
                <input type="text" name="name" id="name" class="w-full border rounded p-2" required>
            </div>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Mulai Petualangan
            </button>
        </form>
    </div>
</x-app-layout>
