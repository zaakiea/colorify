@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.presets.index') }}" class="text-blue-600 hover:text-blue-900">← Back to Presets</a>
        <div class="flex justify-between items-start mt-2">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $preset->name }}</h1>
                <p class="text-gray-600 mt-1">{{ $preset->description }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.presets.edit', $preset->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    Edit
                </a>
                <form action="{{ route('admin.presets.destroy', $preset->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-lg shadow mb-6">
        <div class="grid grid-cols-2 gap-4">
            <div>
                <h3 class="text-gray-700 font-semibold">Category</h3>
                <p class="text-gray-900 mt-1">
                    <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                        {{ ucfirst($preset->category) }}
                    </span>
                </p>
            </div>
            <div>
                <h3 class="text-gray-700 font-semibold">Templates</h3>
                <p class="text-gray-900 mt-1">{{ $preset->templates->count() }} / 4</p>
            </div>
            <div>
                <h3 class="text-gray-700 font-semibold">Created</h3>
                <p class="text-gray-900 mt-1">{{ $preset->created_at->format('M d, Y H:i') }}</p>
            </div>
            <div>
                <h3 class="text-gray-700 font-semibold">Last Updated</h3>
                <p class="text-gray-900 mt-1">{{ $preset->updated_at->format('M d, Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Templates -->
    <h2 class="text-2xl font-bold text-gray-900 mb-4">Templates ({{ $preset->templates->count() }})</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($preset->templates as $template)
        <div class="bg-white p-4 rounded-lg shadow">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">{{ $template->name }}</h3>

            <div class="flex rounded-lg overflow-hidden h-24 mb-4">
                @foreach($template->colors as $color)
                <div class="flex-1 group relative" style="background-color: {{ $color }}">
                    <div class="opacity-0 group-hover:opacity-100 absolute inset-0 flex items-center justify-center bg-black/50 text-white text-xs transition-opacity">
                        {{ $color }}
                    </div>
                </div>
                @endforeach
            </div>

            <div class="grid grid-cols-5 gap-2">
                @foreach($template->colors as $color)
                <div class="text-center">
                    <div class="w-full h-12 rounded border" style="background-color: {{ $color }}; cursor: pointer;" onclick="copyColor('{{ $color }}')"></div>
                    <p class="text-xs text-gray-600 mt-1 font-mono">{{ $color }}</p>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    function copyColor(color) {
        navigator.clipboard.writeText(color).then(() => {
            alert('Color ' + color + ' copied to clipboard!');
        });
    }

</script>
@endsection
