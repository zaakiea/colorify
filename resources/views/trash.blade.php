@extends('layouts.app')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold text-red-600 mb-6">🗑️ Trash</h1>

    @if(session('success'))
    <div class="text-green-600 mb-4">{{ session('success') }}</div>
    @endif

    @if(session('info'))
    <div class="text-blue-600 mb-4">{{ session('info') }}</div>
    @endif

    <!-- Active Collections Section -->
    <h2 class="text-xl font-semibold mb-2">Active Collections</h2>
    <table class="w-full text-left table-auto border-collapse mb-8">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Collection Name</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($collections as $collection)
            @if(!in_array($collection->id, $trashedIds))
            <tr>
                <td class="border px-4 py-2">{{ $collection->name }}</td>
                <td class="border px-4 py-2">
                    <form action="{{ route('trash.move', $collection->id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:underline">Move to Trash</button>
                    </form>
                </td>
            </tr>
            @endif
            @endforeach
        </tbody>
    </table>

    <!-- Trashed Collections Section -->
    <h2 class="text-xl font-semibold mb-2">Trashed Collections</h2>
    <table class="w-full text-left table-auto border-collapse">
        <thead>
            <tr class="bg-gray-100">
                <th class="border px-4 py-2">Collection Name</th>
                <th class="border px-4 py-2">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($trashedCollections as $trashed)
            <tr>
                <td class="border px-4 py-2">{{ $trashed->collection_name }}</td>
                <td class="border px-4 py-2">
                    <form action="{{ route('trash.restore', $trashed->collection_id) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="text-green-600 hover:underline">Restore</button>
                    </form>
                    <form action="{{ route('trash.delete', $trashed->collection_id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This cannot be undone.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:underline ml-4">Delete Permanently</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
