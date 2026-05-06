@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">📋 Manage Presets</h1>
        <a href="{{ route('admin.presets.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Add New Preset
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-left table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="border px-6 py-3 font-semibold text-gray-900">Name</th>
                    <th class="border px-6 py-3 font-semibold text-gray-900">Category</th>
                    <th class="border px-6 py-3 font-semibold text-gray-900">Templates</th>
                    <th class="border px-6 py-3 font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($presets as $preset)
                <tr class="border-b hover:bg-gray-50">
                    <td class="border px-6 py-4 text-gray-900">{{ $preset->name }}</td>
                    <td class="border px-6 py-4 text-gray-700">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold bg-blue-100 text-blue-800">
                            {{ ucfirst($preset->category) }}
                        </span>
                    </td>
                    <td class="border px-6 py-4 text-gray-700">
                        {{ $preset->templates_count ?? $preset->templates->count() }} / 4
                    </td>
                    <td class="border px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.presets.show', $preset->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                View
                            </a>
                            <a href="{{ route('admin.presets.edit', $preset->id) }}" class="text-yellow-600 hover:text-yellow-900 font-semibold">
                                Edit
                            </a>
                            <form action="{{ route('admin.presets.destroy', $preset->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="border px-6 py-4 text-center text-gray-500">
                        No presets found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $presets->links() }}
    </div>
</div>
@endsection
