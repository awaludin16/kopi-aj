<div
    class="fixed top-0 left-0 z-50 w-64 h-full p-4 text-gray-800 transition-transform bg-white dark:bg-gray-800 dark:text-white rounded-r-2xl sidebar-menu">
    <a href="#" class="flex items-center pl-4 pb-4 border-b border-b-gray-200 dark:border-b-gray-900">
        <img src="{{ asset('assets/img/illustrations/logo.png') }}" alt="Logo"
            class="object-cover bg-gray-50 w-8 h-8 rounded">
        <span class="ml-3 text-lg font-semibold text-gray-800 dark:text-white">Kedai Uniko</span>
    </a>

    <ul class="mt-3">
        <li class="mt-2 mb-1 group">
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center py-2 px-4 rounded-md text-sm text-gray-700 font-medium dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-black dark:hover:text-white {{ request()->routeIs('dashboard') ? 'bg-gray-200 dark:bg-gray-800 font-semibold' : '' }}">
                <i class="mr-3 text-lg ri-home-9-line"></i>
                <span>Dashboard</span>
            </a>
        </li>

        {{-- @if (auth()->user() && auth()->user()->role === 'kasir') --}}
        <div
            class="py-2 mt-3 text-sm font-medium text-gray-400 border-t border-t-gray-200 dark:border-t-gray-800 dark:text-slate-300">
            <div class="mt-2 font-light">Interface</div>

            <li class="mt-2 mb-1 group">
                <a href="#"
                    class="flex items-center px-4 py-2 text-gray-700 rounded-md dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 sidebar-dropdown-toggle">
                    <i class="mr-3 text-lg ri-restaurant-2-line"></i>
                    <span class="text-sm">Kelola Menu</span>
                    <i class="ml-auto ri-arrow-right-s-line"></i>
                </a>
                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="{{ route('admin.categories.index') }}"
                            class="flex items-center text-sm text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white before:w-1 before:h-1 before:rounded-full before:bg-gray-400 dark:before:bg-gray-300 before:mr-3">
                            Kategori
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.menus.index') }}"
                            class="flex items-center text-sm text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white before:w-1 before:h-1 before:rounded-full before:bg-gray-400 dark:before:bg-gray-300 before:mr-3">
                            Menu
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-2 mb-1 group">
                <a href="#"
                    class="flex items-center px-4 py-2 text-gray-700 rounded-md dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 sidebar-dropdown-toggle">
                    <i class="mr-3 text-lg ri-flashlight-line"></i>
                    <span class="text-sm">Kelola Pesanan</span>
                    <i class="ml-auto ri-arrow-right-s-line"></i>
                </a>
                <ul class="pl-7 mt-2 hidden group-[.selected]:block">
                    <li class="mb-4">
                        <a href="{{ route('admin.orders.index') }}"
                            class="flex items-center text-sm text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white before:w-1 before:h-1 before:rounded-full before:bg-gray-400 dark:before:bg-gray-300 before:mr-3">
                            <span>Pesanan Masuk</span>
                            <span id="sidebar-pending-badge" class="ml-auto hidden px-2 py-0.5 text-[10px] font-bold text-white bg-red-500 rounded-full animate-pulse">0</span>
                        </a>
                    </li>
                    <li class="mb-4">
                        <a href="{{ route('admin.orders.list') }}"
                            class="flex items-center text-sm text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white before:w-1 before:h-1 before:rounded-full before:bg-gray-400 dark:before:bg-gray-300 before:mr-3">
                            Daftar Pesanan
                        </a>
                    </li>
                </ul>
            </li>

            <li class="mt-2 mb-1 group">
                <a href="#"
                    class="flex items-center py-2 px-4 rounded-md text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-black dark:hover:text-white {{ request()->routeIs('laporan.index') ? 'bg-gray-200 dark:bg-gray-800 font-semibold' : '' }}">
                    <i class="mr-3 text-lg ri-time-line"></i>
                    <span>Riwayat Pesanan</span>
                </a>
            </li>
        </div>
        {{-- @endif --}}

        {{-- @if (auth()->user() && auth()->user()->role === 'owner') --}}
        <div
            class="py-2 mt-3 text-sm font-medium text-gray-400 border-t border-t-gray-200 dark:border-t-gray-800 dark:text-slate-300">
            <div class="mt-2 font-light">Others</div>

            <li class="mt-2 mb-1 group">
                <a href="#"
                    class="flex items-center py-2 px-4 rounded-md text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-black dark:hover:text-white {{ request()->routeIs('user.index') ? 'bg-gray-200 dark:bg-gray-800 font-semibold' : '' }}">
                    <i class="mr-3 text-lg ri-user-3-line"></i>
                    <span>Kelola User</span>
                </a>
            </li>

            <li class="mt-2 mb-1 group">
                <a href="#"
                    class="flex items-center py-2 px-4 rounded-md text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-black dark:hover:text-white {{ request()->routeIs('laporan.index') ? 'bg-gray-200 dark:bg-gray-800 font-semibold' : '' }}">
                    <i class="mr-3 text-lg ri-time-line"></i>
                    <span>Riwayat Penjualan</span>
                </a>
            </li>

            <li class="mt-2 mb-1 group">
                <a href="#"
                    class="flex items-center py-2 px-4 rounded-md text-sm text-gray-700 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-black dark:hover:text-white {{ request()->routeIs('laporan.index') ? 'bg-gray-200 dark:bg-gray-800 font-semibold' : '' }}">
                    <i class="mr-3 text-lg ri-settings-line"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
        </div>
        {{-- @endif --}}
    </ul>
</div>

<div class="fixed top-0 left-0 z-40 w-full h-full bg-black/50 dark:bg-gray-900 md:hidden sidebar-overlay"></div>
