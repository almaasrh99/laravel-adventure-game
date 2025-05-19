
<x-app-layout>
    <x-slot name="header">
        @if (session()->has('message'))
    <div class="mt-4 p-2 bg-green-100 text-green-800 rounded">
        {{ session('message') }}
    </div>
@endif
    <div class="container mx-auto p-4">
        <livewire:game-screen :character="$character" />
    </div>
    </x-slot>
</x-app-layout>
