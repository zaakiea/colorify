@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 p-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-6">🎨 Presets</h1>
        </div>
        @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        <!-- Presets -->
        @forelse($presets as $category => $categoryPresets)

        <div class="bg-white rounded-xl shadow-sm p-6 mb-8">

            <!-- Category -->
            <h2 class="text-lg font-medium capitalize mb-6">
                {{ $category }}
            </h2>

            @foreach($categoryPresets as $preset)

            <!-- Preset Name -->
            <div class="mb-6">
                <h3 class="text-md font-semibold text-gray-800 mb-3">
                    {{ $preset->name }}
                </h3>
                <!-- Templates Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($preset->templates->sortBy('order') as $template)
                    <div class="palette-card rounded-lg overflow-hidden hover:shadow-lg transition relative">
                        <div class="flex h-20">
                            @foreach($template->colors as $color)
                            <div class="color-box group flex-1 relative" data-color="{{ $color }}" style="background-color: {{ $color }}">
                                <div class="opacity-0 group-hover:opacity-100 absolute inset-0 flex items-center justify-center bg-black/50 text-white text-xs pointer-events-none">
                                    {{ $color }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
        @empty
        <div class="bg-white p-6 text-center rounded">
            <p class="text-gray-500">No presets available</p>
        </div>
        @endforelse
        <script>
            document.addEventListener('DOMContentLoaded', function() {

                // Copy single color (klik 1x)
                document.querySelectorAll('.color-box').forEach(el => {
                    el.addEventListener('click', function(e) {
                        const color = this.dataset.color;

                        navigator.clipboard.writeText(color)
                            .then(() => showNotification('Color copied: ' + color))
                            .catch(() => showNotification('Failed to copy'));

                        e.stopPropagation();
                    });
                });

                // Copy full palette (double click)
                document.querySelectorAll('.palette-card').forEach(card => {
                    card.addEventListener('dblclick', function() {
                        const colors = Array.from(this.querySelectorAll('.color-box'))
                            .map(el => el.dataset.color)
                            .join(', ');

                        navigator.clipboard.writeText(colors)
                            .then(() => showNotification('Palette copied'))
                            .catch(() => showNotification('Failed to copy'));
                    });
                });

            });

            function showNotification(message) {
                const existingNotification = document.querySelector('.notification-toast');
                if (existingNotification) {
                    existingNotification.remove();
                }

                const notification = document.createElement('div');
                notification.className = 'notification-toast fixed bottom-4 right-4 bg-gray-900 text-white px-4 py-2 rounded-lg shadow-lg z-50';
                notification.textContent = message;

                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.remove();
                }, 2000);
            }

        </script>
        @endsection
