<x-admin-layout>
    <x-slot name="title">
        Menu
    </x-slot>

    <x-slot name="breadcrumb">
        <x-admin.breadcrumb :breadcrumbs="[['label' => 'menu']]" />
    </x-slot>

    <div class="flex items-center justify-between mb-4 border-b-0 rounded-t-2xl">
        <div>
            <h4 class="text-2xl font-light">Daftar Menu</h4>
            <p class="text-sm text-gray-500">Daftar menu yang tersedia</p>
        </div>
        <x-primary-button as="a" href="{{ route('admin.menus.create') }}"
            class="px-8 py-1.5 font-medium shadow hover:text-primary-600/90 hover:shadow-md transition-all duration-300"
            :outline="true">
            <i class="ri-add-circle-line text-base"></i>
            Menu
        </x-primary-button>
        {{-- <a href="{{ route('admin.menus.create') }}"
            class="px-6 py-1.5 text-sm font-semibold text-white transition-all rounded-lg shadow bg-linear-to-tl from-primary-500 to-primary-600 hover:shadow-md">
            <i class="ri-add-circle-line font-light text-base mr-1"></i>
            Menu
        </a> --}}
    </div>

    <x-ui.table>
        <x-slot name="header">
            <form method="GET" action="{{ route('admin.menus.index') }}">
                <div class="flex items-center gap-4">
                    <div>
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" id="table-search" value="{{ request('search') }}"
                                class="block w-80 p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:placeholder-gray-400"
                                placeholder="Cari menu...">
                        </div>
                    </div>

                    <select name="category"
                        class="flex items-center justify-center text-sm border border-gray-300 rounded-lg px-3 py-2.5 -mb-1">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </x-slot>

        <x-slot name="thead">
            <tr>
                <th class="px-6 py-3">Gambar</th>
                <th class="px-6 py-3">Nama</th>
                <th class="px-6 py-3">Stok</th>
                <th class="px-6 py-3">Harga</th>
                <th class="px-6 py-3">Kategori</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </x-slot>

        @foreach ($menus as $menu)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3">
                    <img src="{{ $menu->image ? asset('storage/menus/' . $menu->image) : 'https://placehold.co/32x32' }}"
                        class="w-10 h-10 rounded object-cover">
                </td>
                <td class="px-6 py-3">{{ $menu->name }}</td>
                <td class="px-6 py-3">{{ $menu->stock }}</td>
                <td class="px-6 py-3">Rp{{ number_format($menu->price, 0, ',', '.') }}</td>
                <td class="px-6 py-3">{{ $menu->category->name }}</td>
                <td class="px-6 py-3">
                    <div class="flex gap-2">
                        <a href="{{ route('admin.menus.edit', $menu->id) }}"
                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">
                            <i class="mr-1 ri-edit-box-line"></i>Edit
                        </a>
                        <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-300">
                                <i class="mr-1 ri-delete-bin-line"></i>Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @endforeach

        <x-slot name="pagination">
            {{ $menus->links() }}
        </x-slot>
    </x-ui.table>

    {{-- <div class="overflow-hidden border border-gray-200 rounded-lg shadow-md">
        <div class="px-6 py-3 bg-white dark:bg-gray-800">
            <form method="GET" action="{{ route('admin.menus.index') }}">
                <div class="flex items-center gap-4">
                    <div>
                        <label for="table-search" class="sr-only">Search</label>
                        <div class="relative mt-1">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" fill="currentColor"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" id="table-search" value="{{ request('search') }}"
                                class="block w-80 p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:placeholder-gray-400"
                                placeholder="Cari menu...">
                        </div>
                    </div>

                    <select name="category"
                        class="flex items-center justify-center text-sm border border-gray-300 rounded-lg px-3 py-2.5 -mb-1">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ request('category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <table class="w-full text-sm text-left text-gray-500 bg-white border-collapse">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Gambar</th>
                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Nama Menu</th>
                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Stok</th>
                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Harga</th>
                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Kategori</th>
                    <th class="px-6 py-3 font-medium text-gray-900 dark:text-white">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white border-t border-gray-100 divide-y divide-gray-100 dark:border-gray-700">
                @foreach ($menus as $menu)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="px-6 py-3">
                            @if ($menu->image)
                                <img src="{{ asset('storage/menus/' . $menu->image) }}" alt="Gambar"
                                    class="object-cover w-10 h-10 rounded">
                            @else
                                <img src="https://placehold.co/32x32" alt="Placeholder"
                                    class="object-cover w-10 h-10 rounded">
                            @endif
                        </td>
                        <td class="px-6 py-3">{{ $menu->name }}</td>
                        <td class="px-6 py-3">{{ $menu->stock }}</td>
                        <td class="px-6 py-3">Rp {{ number_format($menu->price, 0, ',', '.') }}</td>
                        <td class="px-6 py-3">{{ $menu->category->name }}</td>
                        <td class="px-6 py-3">
                            <div class="flex gap-2">
                                <a href="{{ route('admin.menus.edit', $menu->id) }}"
                                    class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">
                                    <i class="mr-1 ri-edit-box-line"></i>Edit
                                </a>
                                <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-300">
                                        <i class="mr-1 ri-delete-bin-line"></i>Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{ $menus->links() }}
    </div> --}}

    <script>
        document.getElementById('table-search').addEventListener('input', function() {
            this.form.submit();
        });

        document.querySelector('select[name="category"]').addEventListener('change', function() {
            this.form.submit();
        });
    </script>
</x-admin-layout>
