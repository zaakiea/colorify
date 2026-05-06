@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <div class="border-b border-gray-200 bg-white">
        <div class="px-8 py-4">
            <!-- Navigation Tabs -->
            <div class="flex items-center justify-between">
                <div class="flex space-x-1">
                    <a href="#" class="text-blue-600 bg-blue-50 rounded-lg px-4 py-2">{{ $collection->name }}</a>
                    @foreach($collections->where('id', '!=', $collection->id) as $col)
                        <a href="{{ route('collections.show', $col->slug) }}" class="text-gray-600 hover:bg-gray-100 rounded-lg px-4 py-2">{{ $col->name }}</a>
                    @endforeach
                    <button onclick="showAddCollectionModal()" class="text-gray-600 hover:bg-gray-100 rounded-lg px-4 py-2">+ Add more</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Color Palettes Grid -->
    <div class="p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
            <!-- Color Palette Cards -->
            @foreach($palettes as $palette)
            <div id="palette_{{ $palette->id }}" class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300">
                <div class="h-48 rounded-t-lg flex overflow-hidden">
                    @foreach($palette->colors as $color)
                    <div class="flex-1" style="background-color: {{ $color }}"></div>
                    @endforeach
                </div>
                <div class="p-4">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="font-medium text-gray-900">{{ $palette->name }}</h3>
                            <p class="text-sm text-gray-500">Saved on {{ $palette->saved_on->format('M d, Y') }}</p>
                        </div>
                        <div class="relative">
                            <button onclick="event.stopPropagation(); togglePaletteMenu({{ $palette->id }})" 
                                    class="p-1.5 text-gray-500 hover:text-gray-700 rounded-full hover:bg-gray-100">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                        d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"/>
                                </svg>
                            </button>
                            <div id="paletteMenu_{{ $palette->id }}" 
                                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg hidden z-20">
                                <button onclick="showEditPaletteModal({{ $palette->id }})" 
                                        class="w-full text-left px-4 py-2 hover:bg-gray-100 rounded-t-lg">
                                    Edit Palette
                                </button>
                                <button onclick="deletePalette({{ $palette->id }})" 
                                        class="w-full text-left px-4 py-2 text-red-600 hover:bg-gray-100 rounded-b-lg">
                                    Delete Palette
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach

            <!-- Add New Palette Button -->
            <button onclick="showAddPaletteModal()" 
                    class="bg-white rounded-lg shadow-sm hover:shadow-md transition-all duration-300 border-2 border-dashed border-gray-200 flex items-center justify-center h-72">
                <div class="text-center">
                    <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-gray-100 flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                    </div>
                    <p class="text-gray-600">Add New Palette</p>
                </div>
            </button>
        </div>
    </div>

    <!-- Add Palette Modal -->
    <div id="addPaletteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-[480px] max-w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Add New Palette</h2>
                <button onclick="closeAddPaletteModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="paletteForm" onsubmit="savePalette(event)">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Palette Name</label>
                        <input type="text" id="paletteName" required
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" 
                            placeholder="Enter palette name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Colors</label>
                        <div id="colorInputs" class="space-y-2">
                            <!-- Color inputs will be added here -->
                        </div>
                        <button type="button" onclick="addColorInput()" 
                                class="mt-2 text-blue-600 hover:text-blue-700 text-sm">
                            + Add Color
                        </button>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeAddPaletteModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Create Palette
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Palette Modal -->
    <div id="editPaletteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-[480px] max-w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-semibold">Edit Palette</h2>
                <button onclick="closeEditPaletteModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <form id="editPaletteForm" onsubmit="handleEditPalette(event)">
                <input type="hidden" id="editPaletteId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Palette Name</label>
                        <input type="text" id="editPaletteName" required
                            class="w-full px-3 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" 
                            placeholder="Enter palette name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Colors</label>
                        <div id="editColorInputs" class="space-y-2">
                            <!-- Edit color inputs will be added here -->
                        </div>
                        <button type="button" onclick="addEditColorInput()" 
                                class="mt-2 text-blue-600 hover:text-blue-700 text-sm">
                            + Add Color
                        </button>
                    </div>
                </div>
                <div class="flex justify-end space-x-2 mt-6">
                    <button type="button" onclick="closeEditPaletteModal()" 
                            class="px-4 py-2 text-gray-600 hover:text-gray-800">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        Update Palette
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
<script>
// Color Utilities
function componentToHex(c) {
    const hex = c.toString(16);
    return hex.length === 1 ? '0' + hex : hex;
}

function rgbToHex(rgb) {
    // If already hex
    if (rgb.startsWith('#')) {
        return rgb;
    }
    
    // Extract RGB values
    const matches = rgb.match(/^rgb\((\d+),\s*(\d+),\s*(\d+)\)$/);
    if (!matches) {
        return '#000000';
    }
    
    const r = parseInt(matches[1]);
    const g = parseInt(matches[2]);
    const b = parseInt(matches[3]);
    
    return '#' + componentToHex(r) + componentToHex(g) + componentToHex(b);
}

// Menu Management
let activeMenu = null;

function togglePaletteMenu(paletteId) {
    const menu = document.getElementById(`paletteMenu_${paletteId}`);
    if (!menu) return;
    
    // Tutup menu yang sedang terbuka
    const openMenus = document.querySelectorAll('[id^="paletteMenu_"]');
    openMenus.forEach(openMenu => {
        if (openMenu !== menu && !openMenu.classList.contains('hidden')) {
            openMenu.classList.add('hidden');
        }
    });
    
    // Toggle menu yang diklik
    menu.classList.toggle('hidden');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    if (activeMenu && !event.target.closest('.relative')) {
        activeMenu.classList.add('hidden');
        activeMenu = null;
    }
});

document.addEventListener('click', function(event) {
    if (!event.target.closest('.relative')) {
        const openMenus = document.querySelectorAll('[id^="paletteMenu_"]');
        openMenus.forEach(menu => menu.classList.add('hidden'));
    }
});

// Add Palette Modal Functions
function showAddPaletteModal() {
    const modal = document.getElementById('addPaletteModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.getElementById('paletteName').value = '';
    document.getElementById('colorInputs').innerHTML = '';
    addColorInput();
}

function closeAddPaletteModal() {
    const modal = document.getElementById('addPaletteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function addColorInput() {
    const colorInputs = document.getElementById('colorInputs');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2 mb-2';
    div.innerHTML = `
        <input type="color" class="color-input h-10 w-14">
        <input type="text" class="color-hex flex-1 px-3 py-2 border rounded-lg" 
               placeholder="#000000" onchange="updateColorPicker(this)">
        <button type="button" onclick="removeColor(this)" class="text-red-500 hover:text-red-700">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
    
    const colorInput = div.querySelector('input[type="color"]');
    const hexInput = div.querySelector('.color-hex');
    
    colorInput.addEventListener('input', function() {
        hexInput.value = this.value.toUpperCase();
    });
    
    colorInputs.appendChild(div);
}

// Edit Palette Functions
function showEditPaletteModal(paletteId) {
    const modal = document.getElementById('editPaletteModal');
    const palette = document.getElementById(`palette_${paletteId}`);
    
    if (!palette) return;
    
    const name = palette.querySelector('h3').textContent;
    const colors = Array.from(palette.querySelector('.h-48').children)
        .map(div => rgbToHex(div.style.backgroundColor));
    
    document.getElementById('editPaletteId').value = paletteId;
    document.getElementById('editPaletteName').value = name;
    
    // Clear existing color inputs
    document.getElementById('editColorInputs').innerHTML = '';
    
    // Add color inputs for each color
    colors.forEach(color => addEditColorInput(color));
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function addEditColorInput(color = '#000000') {
    const editColorInputs = document.getElementById('editColorInputs');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2 mb-2';
    
    const hexColor = rgbToHex(color);
    div.innerHTML = `
        <input type="color" class="color-input h-10 w-14" value="${hexColor}">
        <input type="text" class="color-hex flex-1 px-3 py-2 border rounded-lg" 
               value="${hexColor}" placeholder="#000000" onchange="updateColorPicker(this)">
        <button type="button" onclick="removeColor(this)" class="text-red-500 hover:text-red-700">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
    
    const colorInput = div.querySelector('input[type="color"]');
    const hexInput = div.querySelector('.color-hex');
    
    colorInput.addEventListener('input', function() {
        hexInput.value = this.value.toUpperCase();
    });
    
    editColorInputs.appendChild(div);
}

function closeEditPaletteModal() {
    const modal = document.getElementById('editPaletteModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

function addEditColorInput(color = '#000000') {
    const editColorInputs = document.getElementById('editColorInputs');
    const div = document.createElement('div');
    div.className = 'flex items-center space-x-2 mb-2';
    const hexColor = rgbToHex(color);
    div.innerHTML = `
        <input type="color" class="color-input h-10 w-14" value="${hexColor}">
        <input type="text" class="color-hex flex-1 px-3 py-2 border rounded-lg" 
               value="${hexColor}" placeholder="#000000" onchange="updateColorPicker(this)">
        <button type="button" onclick="removeColor(this)" class="text-red-500 hover:text-red-700">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    `;
    
    const colorInput = div.querySelector('input[type="color"]');
    const hexInput = div.querySelector('.color-hex');
    
    colorInput.addEventListener('input', function() {
        hexInput.value = this.value.toUpperCase();
    });
    
    editColorInputs.appendChild(div);
}

// Shared Functions
function updateColorPicker(hexInput) {
    const colorPicker = hexInput.previousElementSibling;
    if (/^#[0-9A-F]{6}$/i.test(hexInput.value)) {
        colorPicker.value = hexInput.value.toUpperCase();
    }
}

function removeColor(button) {
    const colorInputs = button.closest('.space-y-2');
    if (colorInputs.children.length > 1) {
        button.closest('div').remove();
    }
}

// CRUD Operations
async function savePalette(event) {
    event.preventDefault();
    
    const name = document.getElementById('paletteName').value;
    const colors = Array.from(document.querySelectorAll('#colorInputs .color-input'))
        .map(input => input.value.toUpperCase());
    
    try {
        const response = await fetch(`/collections/{{ $collection->id }}/palettes`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ name, colors })
        });

        if (!response.ok) {
            throw new Error('Failed to create palette');
        }

        closeAddPaletteModal();
        showNotification('Palette created successfully');
        window.location.reload();
    } catch (error) {
        console.error('Error:', error);
        showNotification(error.message, 'error');
    }
}

async function handleEditPalette(event) {
    event.preventDefault();
    
    const paletteId = document.getElementById('editPaletteId').value;
    const collectionId = {{ $collection->id }}; // Get the collection ID
    const name = document.getElementById('editPaletteName').value;
    const colors = Array.from(document.querySelectorAll('#editColorInputs .color-input'))
        .map(input => input.value.toUpperCase());

    try {
        const response = await fetch(`/collections/${collectionId}/palettes/${paletteId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ name, colors })
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Gagal mengupdate palette');
        }

        // Update UI
        const palette = document.getElementById(`palette_${paletteId}`);
        if (palette) {
            palette.querySelector('h3').textContent = name;
            const colorContainer = palette.querySelector('.h-48');
            colorContainer.innerHTML = colors.map(color => 
                `<div class="flex-1" style="background-color: ${color}"></div>`
            ).join('');
        }

        closeEditPaletteModal();
        showNotification('Palette berhasil diupdate');

    } catch (error) {
        console.error('Update error:', error);
        showNotification(error.message || 'Gagal mengupdate palette', 'error');
    }
}
async function deletePalette(paletteId) {
    if (!confirm('Apakah anda yakin ingin menghapus palette ini?')) {
        return;
    }

    try {
        const collectionId = {{ $collection->id }};
        const response = await fetch(`/collections/${collectionId}/palettes/${paletteId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        });

        const data = await response.json();

        if (!response.ok) {
            throw new Error(data.error || 'Failed to delete palette');
        }

        // Remove element from DOM
        const paletteElement = document.getElementById(`palette_${paletteId}`);
        if (paletteElement) {
            paletteElement.remove();
            showNotification('Palette berhasil dihapus');
        }

    } catch (error) {
        console.error('Delete error:', error);
        showNotification(error.message, 'error');
    }
}

function showNotification(message, type = 'success') {
    const notification = document.createElement('div');
    notification.className = `fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
    notification.textContent = message;
    
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.classList.add('fade-out');
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('colorInputs').children.length === 0) {
        addColorInput();
    }

    // Handle outside clicks for modals
    window.addEventListener('click', function(event) {
        const addModal = document.getElementById('addPaletteModal');
        const editModal = document.getElementById('editPaletteModal');
        
        if (event.target === addModal) {
            closeAddPaletteModal();
        }
        
        if (event.target === editModal) {
            closeEditPaletteModal();
        }
    });

    // Event listener untuk menu toggle
    document.querySelectorAll('[onclick^="togglePaletteMenu"]').forEach(button => {
        button.addEventListener('click', function(e) {
            e.stopPropagation();
            const paletteId = this.getAttribute('onclick').match(/\d+/)[0];
            togglePaletteMenu(paletteId);
        });
    });
});
</script>

<style>
.modal-enter {
    opacity: 0;
    transform: scale(0.9);
}

.modal-enter-active {
    opacity: 1;
    transform: scale(1);
    transition: opacity 300ms, transform 300ms;
}

.modal-exit {
    opacity: 1;
}

.modal-exit-active {
    opacity: 0;
    transform: scale(0.9);
    transition: opacity 300ms, transform 300ms;
}

input[type="color"] {
    -webkit-appearance: none;
    padding: 0;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

input[type="color"]::-webkit-color-swatch-wrapper {
    padding: 0;
}

input[type="color"]::-webkit-color-swatch {
    border: none;
    border-radius: 4px;
}

.notification-toast {
    animation: slideIn 0.3s ease-out;
}

.notification-toast.fade-out {
    animation: fadeOut 0.3s ease-out forwards;
}

@keyframes slideIn {
    from {
        transform: translateY(100%);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

@keyframes fadeOut {
    from {
        opacity: 1;
    }
    to {
        opacity: 0;
    }
}
</style>
@endpush
@endsection