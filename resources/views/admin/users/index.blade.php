@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">👤Users</h1>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Add New User
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
    @endif

    <!-- Search and Filter Section -->
    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex gap-4">
            <input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">

            <select name="role" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <option value="">All Roles</option>
                <option value="user" {{ request('role') === 'user' ? 'selected' : '' }}>User</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            </select>

            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Filter
            </button>

            @if(request('search') || request('role'))
            <a href="{{ route('admin.users.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded">
                Clear
            </a>
            @endif
        </form>
    </div>

    <!-- Users Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-left table-auto border-collapse">
            <thead>
                <tr class="bg-gray-100 border-b">
                    <th class="border px-6 py-3 font-semibold text-gray-900">Name</th>
                    <th class="border px-6 py-3 font-semibold text-gray-900">Email</th>
                    <th class="border px-6 py-3 font-semibold text-gray-900">Role</th>
                    <th class="border px-6 py-3 font-semibold text-gray-900">Created At</th>
                    <th class="border px-6 py-3 font-semibold text-gray-900">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="border px-6 py-4 text-gray-900">{{ $user->name }}</td>
                    <td class="border px-6 py-4 text-gray-700">{{ $user->email }}</td>
                    <td class="border px-6 py-4">
                        <span class="px-3 py-1 rounded-full text-sm font-semibold
                                {{ $user->role === 'admin' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="border px-6 py-4 text-gray-700">{{ $user->created_at->format('M d, Y') }}</td>
                    <td class="border px-6 py-4">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.users.show', $user->id) }}" class="text-blue-600 hover:text-blue-900 font-semibold">
                                View
                            </a>
                            <a href="{{ route('admin.users.edit', $user->id) }}" class="text-yellow-600 hover:text-yellow-900 font-semibold">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 font-semibold">
                                    Delete
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="border px-6 py-4 text-center text-gray-500">
                        No users found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $users->links() }}
    </div>
</div>
@endsection
