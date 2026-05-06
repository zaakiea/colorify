<div class="sidebar fixed h-screen w-64 bg-white border-r border-gray-200">

    <!-- Header -->
    <div class="h-16 px-4 flex items-center border-b border-gray-200">
        <button class="p-2 hover:bg-gray-100 rounded-lg mr-2">
            <x-heroicon-o-bars-3 class="w-6 h-6 text-gray-700" />
        </button>
        <span class="text-xl font-semibold">WERNOIN</span>
    </div>

    <div class="p-4">
        <nav class="flex flex-col space-y-3">

            @auth
            @if(auth()->user()->isAdmin())

            <!-- Users -->
            <a href="{{ route('admin.users.index') }}" class="menu-item flex items-center space-x-3 p-2.5 rounded-lg hover:bg-gray-100">
                <x-heroicon-o-users class="w-6 h-6 text-gray-600" />
                <span class="text-lg text-gray-600">Users</span>
            </a>

            <!-- Presets Admin -->
            <a href="{{ route('admin.presets.index') }}" class="menu-item flex items-center space-x-3 p-2.5 rounded-lg hover:bg-gray-100">
                <x-heroicon-o-swatch class="w-6 h-6 text-gray-600" />
                <span class="text-lg text-gray-600">Presets</span>
            </a>

            @else

            <!-- Create -->
            <div class="menu-item flex items-center space-x-3 p-2.5 rounded-lg hover:bg-gray-100 cursor-pointer" data-menu="create">
                <x-heroicon-o-plus class="w-6 h-6 text-gray-600" />
                <span class="text-lg text-gray-600">Create</span>
            </div>

            <!-- Collection -->
            <div>
                <div class="menu-item flex items-center space-x-3 p-2.5 rounded-lg hover:bg-gray-100 cursor-pointer" onclick="toggleCollection()">
                    <x-heroicon-o-folder class="w-6 h-6 text-gray-600" />
                    <span class="text-lg text-gray-600">Collection</span>
                </div>

                <div id="collectionDropdown" class="ml-10 transition-all duration-300" style="max-height:0; overflow:hidden;">
                    <div id="collectionList"></div>
                    <div onclick="showAddCollectionModal()" class="text-gray-500 cursor-pointer mt-2">
                        + Add more
                    </div>
                </div>
            </div>

            <!-- Presets -->
            <div class="menu-item flex items-center space-x-3 p-2.5 rounded-lg hover:bg-gray-100 cursor-pointer" data-menu="presets">
                <x-heroicon-o-swatch class="w-6 h-6 text-gray-600" />
                <span class="text-lg text-gray-600">Presets</span>
            </div>

            <!-- Trash -->
            <a href="{{ route('trash.index') }}" class="menu-item flex items-center space-x-3 p-2.5 rounded-lg hover:bg-gray-100">
                <x-heroicon-o-trash class="w-6 h-6 text-gray-600" />
                <span class="text-lg text-gray-600">Trash</span>
            </a>
            @endif
            @endauth

        </nav>
    </div>
</div>

<!-- Add Collection Modal -->
<div id="addCollectionModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 w-96">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Add New Collection</h2>
            <button onclick="closeAddCollectionModal()" class="text-gray-400 hover:text-gray-600">
                <x-heroicon-o-plus class="w-6 h-6 text-gray-600" />
            </button>
        </div>
        <form id="collectionForm" onsubmit="handleCollectionSubmit(event)">
            <input type="text" id="collectionName" class="w-full px-3 py-2 border rounded-lg mb-4" placeholder="Collection name" required>
            <div class="flex justify-end space-x-2">
                <button type="button" onclick="closeAddCollectionModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800">
                    Cancel
                </button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @auth
        loadCollections();
        @endauth
        setActiveMenuItem();

        // Event listener for search input
        @auth
        document.getElementById('searchInput').addEventListener('input', debounce(function(e) {
            const searchTerm = e.target.value.toLowerCase();
            filterCollections(searchTerm);
        }, 300));
        @endauth
    });

    function setActiveMenuItem() {
        const currentPath = window.location.pathname;
        document.querySelectorAll('.menu-item').forEach(item => {
            if (item.dataset.menu !== 'collection') {
                item.addEventListener('click', function(e) {
                    const menu = this.dataset.menu;

                    switch (menu) {
                        case 'create':
                            window.location.href = '/';
                            break;
                        case 'presets':
                            window.location.href = '/presets';
                            break;
                    }
                });
            }
        });
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    async function loadCollections() {
        try {
            const response = await fetch('/collections', {
                method: 'GET'
                , headers: {
                    'Accept': 'application/json'
                    , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            if (!response.ok) {
                return;
            }

            const collections = await response.json();

            const collectionList = document.getElementById('collectionList');
            if (!collectionList) {
                return;
            }

            const collectionHTML = collections.map(collection => `
            <div class="collection-item text-gray-500 hover:text-gray-700 cursor-pointer text-lg py-2" 
                 data-name="${collection.name.toLowerCase()}"
                 onclick="window.location.href='/collection/${collection.slug}'">
                ${collection.name}
            </div>
        `).join('');

            collectionList.innerHTML = collectionHTML;

            const dropdown = document.getElementById('collectionDropdown');
            if (dropdown) {
                dropdown.style.maxHeight = 'none';
            }

        } catch (error) {
            return;
        }
    }

    function filterCollections(searchTerm) {
        const items = document.querySelectorAll('.collection-item');
        items.forEach(item => {
            const name = item.dataset.name;
            if (name.includes(searchTerm)) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    function toggleCollection() {
        const dropdown = document.getElementById('collectionDropdown');
        const currentHeight = dropdown.style.maxHeight;

        if (currentHeight === '0px' || currentHeight === '') {
            dropdown.style.maxHeight = dropdown.scrollHeight + 'px';
        } else {
            dropdown.style.maxHeight = '0px';
        }
    }

    function showAddCollectionModal() {
        const modal = document.getElementById('addCollectionModal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.getElementById('collectionName').value = '';
        document.getElementById('collectionName').focus();
    }

    function closeAddCollectionModal() {
        const modal = document.getElementById('addCollectionModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function handleCollectionSubmit(event) {
        event.preventDefault();
        const name = document.getElementById('collectionName').value.trim();

        if (!name) {
            showNotification('Please enter a collection name', 'error');
            return;
        }

        try {
            const response = await fetch('/collections', {
                method: 'POST'
                , headers: {
                    'Content-Type': 'application/json'
                    , 'Accept': 'application/json'
                    , 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
                , body: JSON.stringify({
                    name
                })
            });

            const data = await response.json();

            if (!response.ok) {
                throw new Error(data.message || 'Failed to create collection');
            }

            closeAddCollectionModal();
            await loadCollections();
            showNotification('Collection created successfully');

            const dropdown = document.getElementById('collectionDropdown');
            if (dropdown.style.maxHeight === '0px' || dropdown.style.maxHeight === '') {
                toggleCollection();
            }

        } catch (error) {
            console.error('Error:', error);
            showNotification(error.message || 'Failed to create collection', 'error');
        }
    }

    function showNotification(message, type = 'success') {
        const existingNotification = document.querySelector('.notification-toast');
        if (existingNotification) {
            existingNotification.remove();
        }

        const notification = document.createElement('div');
        notification.className = `notification-toast fixed bottom-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 ${
        type === 'success' ? 'bg-green-500' : 'bg-red-500'
    } text-white`;
        notification.textContent = message;

        document.body.appendChild(notification);

        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('addCollectionModal');
        if (event.target === modal) {
            closeAddCollectionModal();
        }
    });

</script>

<style>
    .menu-item {
        transition: all 0.2s ease-in-out;
    }

    .menu-item.active {
        background-color: rgb(239 246 255);
    }

    .menu-item.active span {
        color: rgb(17 24 39);
        font-weight: 500;
    }

    #collectionDropdown {
        transition: max-height 0.3s ease-in-out;
    }

    .collection-item {
        padding: 0.5rem;
        border-radius: 0.375rem;
        transition: all 0.2s ease-in-out;
    }

    .collection-item:hover {
        color: rgb(17 24 39);
        background-color: rgb(243 244 246);
        transform: translateX(8px);
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
