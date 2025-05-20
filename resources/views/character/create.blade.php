<x-app-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white rounded shadow">
        <h2 class="text-xl font-bold mb-4 text-center">Buat Karakter</h2>

        <form action="{{ route('character.store') }}" method="POST">
            @csrf

            {{-- Input Nama --}}
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium">Nama Karakter</label>
                <input type="text" name="name" id="name" class="w-full border rounded p-2" required>
            </div>

            {{-- Pilih Class --}}
            <div class="mb-4">
                <label for="class" class="block text-sm font-medium mb-1">Pilih Kelas</label>
                <select name="class" id="class" class="w-full border rounded p-2" required onchange="updateAvatarPreview()">
                    <option value="">-- Pilih Class --</option>
                    <option value="Warrior">🗡️ Warrior</option>
                    <option value="Archer">🏹 Archer</option>
                    <option value="Mage">🧙 Mage</option>
                </select>
            </div>

            {{-- Preview Gambar Avatar --}}
            <div class="mb-4 text-center">
                <img id="avatarPreview" src="" alt="Avatar Preview"
                    class="w-32 h-32 mx-auto rounded-full shadow hidden object-cover">
                <p id="classLabel" class="mt-2 text-sm text-gray-600 font-medium"></p>
            </div>

            {{-- Tombol Submit --}}
            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Buat Karakter
                </button>
            </div>
        </form>
    </div>

    {{-- JavaScript untuk menampilkan gambar berdasarkan pilihan class --}}
    <script>
        function updateAvatarPreview() {
            const classSelect = document.getElementById('class');
            const avatarPreview = document.getElementById('avatarPreview');
            const classLabel = document.getElementById('classLabel');
            const selectedClass = classSelect.value;

            const avatarMap = {
                'Warrior': '{{ asset("images/avatars/warrior.png") }}',
                'Archer': '{{ asset("images/avatars/archer.png") }}',
                'Mage': '{{ asset("images/avatars/mage.png") }}'
            };

            if (avatarMap[selectedClass]) {
                avatarPreview.src = avatarMap[selectedClass];
                avatarPreview.classList.remove('hidden');
                classLabel.innerText = selectedClass;
            } else {
                avatarPreview.classList.add('hidden');
                classLabel.innerText = '';
            }
        }
    </script>
</x-app-layout>
