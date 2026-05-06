@extends('layouts.app')

@section('content')
<div class="p-6 max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="text-blue-600 hover:text-blue-900">← Back to Users</a>
        <h1 class="text-3xl font-bold text-gray-900 mt-2">User Details</h1>
    </div>

    <div class="bg-white p-6 rounded-lg shadow">
        <div class="mb-6">
            <p class="text-gray-600 text-sm font-semibold uppercase">Name</p>
            <p class="text-2xl font-bold text-gray-900">{{ $user->name }}</p>
        </div>

        <div class="mb-6">
            <p class="text-gray-600 text-sm font-semibold uppercase">Email</p>
            <p class="text-lg text-gray-900">{{ $user->email }}</p>
        </div>

        <div class="mb-6">
            <p class="text-gray-600 text-sm font-semibold uppercase">Role</p>
            <div class="mt-2">
                <span class="px-3 py-1 rounded-full text-sm font-semibold
                    {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>

        <div class="mb-6">
            <p class="text-gray-600 text-sm font-semibold uppercase">Created At</p>
            <p class="text-lg text-gray-900">{{ $user->created_at->format('M d, Y \a\t h:i A') }}</p>
        </div>

        <div class="mb-6">
            <p class="text-gray-600 text-sm font-semibold uppercase">Last Updated</p>
            <p class="text-lg text-gray-900">{{ $user->updated_at->format('M d, Y \a\t h:i A') }}</p>
        </div>

        <div class="flex gap-4">
            <a href="{{ route('admin.users.edit', $user->id) }}" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-2 px-6 rounded">
                Edit User
            </a>
            @if($user->id !== auth()->id())
            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded">
                    Delete User
                </button>
            </form>
            @endif
            <a href="{{ route('admin.users.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded">
                Back
            </a>
        </div>
    </div>
</div>
@endsection
