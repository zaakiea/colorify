<nav class="fixed right-0 left-64 top-0 h-16 bg-white border-b border-gray-200 z-10">
    <div class="h-full px-4 flex items-center justify-end space-x-4">
        <!-- Help Icon -->
        <div class="relative" x-data="{ openHelp: false }">
            <button @click="openHelp = !openHelp" @click.away="openHelp = false" class="p-2 text-gray-500 hover:text-gray-700 focus:outline-none">
                <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                    <path d="M12 22C6.477 22 2 17.523 2 12S6.477 2 12 2s10 4.477 10 10-4.477 10-10 10zm-1-7v2h2v-2h-2zm2-1.645A3.502 3.502 0 0012 6.5a3.501 3.501 0 00-3.433 2.813l1.962.393A1.5 1.5 0 1112 11.5a1 1 0 00-1 1V14h2v-.645z" fill="currentColor" />
                </svg>
            </button>

            <!-- Help Dropdown -->
            <div x-show="openHelp" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 bg-white rounded-xl shadow-lg z-50 overflow-hidden border border-gray-100 p-5 text-left" style="display: none; width: 320px; white-space: normal; word-wrap: break-word;">
                <h3 class="font-bold text-gray-900 text-lg mb-2">Tentang Wernoin</h3>
                <p class="text-sm text-gray-600 mb-4 leading-relaxed">
                    Wernoin adalah sebuah aplikasi generator dan pengelola palet warna. Dirancang untuk membantu desainer, developer, dan kreator mencari, menyimpan, serta mengelola inspirasi kombinasi warna dengan mudah.
                </p>
                <div class="border-t border-gray-100 pt-4 mt-4">
                    <p class="text-xs font-semibold text-indigo-600 uppercase tracking-wider mb-3">Tugas Akhir PSBD</p>
                    <div class="space-y-2">
                        <div class="text-sm">
                            <p class="text-gray-600 font-medium">Kelompok 04 (09 & 56)</p>
                        </div>
                        <div class="space-y-1 text-xs text-gray-700">
                            <p><span class="font-semibold">Raihan Sahaja</span> <span class="text-gray-500">(21120123130093)</span></p>
                            <p><span class="font-semibold">Yasmin Haniyya</span> <span class="text-gray-500">(21120124120010)</span></p>
                            <p><span class="font-semibold">Shofwan Thufail Akhmad</span> <span class="text-gray-500">(21120124130066)</span></p>
                            <p><span class="font-semibold">Nabil Bintang Ardiansyah Purwanto</span> <span class="text-gray-500">(21120123140121)</span></p>
                            <p><span class="font-semibold">Dzaki Eka Atmaja</span> <span class="text-gray-500">(21120123130068)</span></p>
                            <p><span class="font-semibold">Razzaq Permana</span> <span class="text-gray-500">(21120123120016)</span></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Icon -->
        <a href="{{ route('settings.index') }}" class="p-2 text-gray-500 hover:text-gray-700">
            <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none">
                <path d="M12 15a3 3 0 100-6 3 3 0 000 6z" stroke="currentColor" stroke-width="2" />
                <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" stroke="currentColor" stroke-width="2" />
            </svg>
        </a>

        <!-- Auth Buttons -->
        @auth
        <!-- User Menu -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" class="px-4 py-2 bg-[#FDEBB6] text-gray-700 font-bold rounded-lg hover:bg-[#f8e0a0] transition-colors flex items-center space-x-2">
                <span>{{ Auth::user()->name }}</span>
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg z-50">
                <div class="py-1">
                    <div class="px-4 py-2 text-sm text-gray-500">
                        Signed in as <span class="font-medium text-gray-900">{{ Auth::user()->email }}</span>
                    </div>
                    <div class="border-t border-gray-100"></div>
                    <a href="{{ route('settings.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                        Settings
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="block w-full text-left">
                        @csrf
                        <button type="submit" class="block w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900">
                            Sign out
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @else
        <!-- Login/Register Links -->
        <div class="flex items-center space-x-4">
            <a href="{{ route('login') }}" class="px-4 py-2 bg-[#FDEBB6] text-gray-700 font-bold rounded-lg hover:bg-[#f8e0a0] transition-colors">
                Sign in
            </a>
            <a href="{{ route('register') }}" class="px-4 py-2 text-gray-700 hover:text-gray-900 font-medium">
                Register
            </a>
        </div>
        @endauth

        <!-- Export Button -->
        <button class="p-2 text-gray-500 hover:text-gray-700">
            <img src="{{ asset('images/sampinglogin.png') }}" alt="Export" class="w-10 h-10">
        </button>
    </div>
</nav>

<!-- AlpineJS - Add this before closing body tag or in your app layout -->
<script src="//unpkg.com/alpinejs" defer></script>

<style>
    /* Dropdown menu animations */
    .dropdown-enter-active,
    .dropdown-leave-active {
        transition: all 0.15s ease-out;
    }

    .dropdown-enter-from,
    .dropdown-leave-to {
        opacity: 0;
        transform: scale(0.95);
    }

    /* Hover effects */
    .hover\:bg-gray-100:hover {
        background-color: rgba(243, 244, 246, 1);
    }

    .hover\:text-gray-900:hover {
        color: rgba(17, 24, 39, 1);
    }

    /* User menu button hover */
    .hover\:bg-\[\#f8e0a0\]:hover {
        background-color: #f8e0a0;
    }

    /* Transitions */
    .transition-colors {
        transition-property: background-color, border-color, color, fill, stroke;
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
        transition-duration: 150ms;
    }

    /* Focus styles */
    .focus\:outline-none:focus {
        outline: 2px solid transparent;
        outline-offset: 2px;
    }

    .focus\:ring-2:focus {
        --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color);
        --tw-ring-shadow: var(--tw-ring-inset) 0 0 0 calc(2px + var(--tw-ring-offset-width)) var(--tw-ring-color);
        box-shadow: var(--tw-ring-offset-shadow), var(--tw-ring-shadow), var(--tw-shadow, 0 0 #0000);
    }

    /* Z-index utilities */
    .z-10 {
        z-index: 10;
    }

    .z-50 {
        z-index: 50;
    }

</style>
