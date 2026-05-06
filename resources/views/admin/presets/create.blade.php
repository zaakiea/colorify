@extends('layouts.app')

@section('content')
<div class="p-6 max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.presets.index') }}" class="text-blue-600 hover:text-blue-900">← Back to Presets</a>
        <h1 class="text-3xl font-bold text-gray-900 mt-2">Create New Preset</h1>
    </div>

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul>
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.presets.store') }}" method="POST" class="bg-white p-6 rounded-lg shadow">
        @csrf

        <div class="mb-6">
            <label for="name" class="block text-gray-700 font-semibold mb-2">Preset Name</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('name') border-red-500 @enderror" required>
            @error('name')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="category" class="block text-gray-700 font-semibold mb-2">Category</label>
            <select id="category" name="category" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('category') border-red-500 @enderror" required>
                <option value="">Select a category</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ old('category') === $cat ? 'selected' : '' }}>{{ ucfirst($cat) }}</option>
                @endforeach
            </select>
            @error('category')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="description" class="block text-gray-700 font-semibold mb-2">Description (Optional)</label>
            <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
        </div>

        <!-- Templates Section -->
        <div class="mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Templates (Max 4, Min 1)</h2>
            <div id="templatesContainer">
                <!-- Templates will be added here -->
            </div>
            <button type="button" onclick="addTemplate()" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mt-4">
                + Add Template
            </button>
            @error('templates')
            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex gap-4">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                Create Preset
            </button>
            <a href="{{ route('admin.presets.index') }}" class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-6 rounded">
                Cancel
            </a>
        </div>
    </form>
</div>

<script>
    let templateCount = 0;
    const maxTemplates = 4;
    const colors = ['#FF1493', '#8B008B', '#4B0082', '#9400D3', '#483D8B'];

    function addTemplate() {
        if (templateCount >= maxTemplates) {
            alert(`Maximum ${maxTemplates} templates allowed`);
            return;
        }

        const container = document.getElementById('templatesContainer');
        const templateHTML = `
        <div class="template-item bg-gray-50 p-4 rounded mb-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-semibold text-gray-900">Template ${templateCount + 1}</h3>
                <button type="button" onclick="removeTemplate(this)" class="text-red-600 hover:text-red-900 font-bold">Remove</button>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">Template Name</label>
                <input type="text" name="templates[${templateCount}][name]"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="e.g., Purple Shades" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-semibold mb-2">5 Colors (Hex Format)</label>
                <div class="grid grid-cols-5 gap-2">
                    ${Array.from({length: 5}, (_, i) => ` <
            div >
            <input type="color" name="templates[${templateCount}][colors][${i}]" 
                                class="w-full h-12 border rounded cursor-pointer" 
                                value="${colors[i]}"
                                onchange="updateHexInput(this)"
                                required>
                            <input type="text" class="hex-input w-full mt-1 px-2 py-1 border rounded text-sm text-center"
                                placeholder="#000000" value="${colors[i]}" readonly>
                        </div>
                    `).join('')}
                </div>
            </div>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', templateHTML);
    templateCount++;
    updateAddButtonState();
}

function removeTemplate(btn) {
    btn.closest('.template-item').remove();
    templateCount--;
    updateAddButtonState();
}

function updateAddButtonState() {
    const btn = document.querySelector('button[onclick="addTemplate()"]');
    if (templateCount >= maxTemplates) {
        btn.disabled = true;
        btn.classList.add('opacity-50', 'cursor-not-allowed');
    } else {
        btn.disabled = false;
        btn.classList.remove('opacity-50', 'cursor-not-allowed');
    }
}

function updateHexInput(colorPicker) {
    const hexInput = colorPicker.nextElementSibling;
    hexInput.value = colorPicker.value;
}

// Initialize with one template
document.addEventListener('DOMContentLoaded', function() {
    addTemplate();
});

</script>

<style>
    button:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

</style>
@endsection
