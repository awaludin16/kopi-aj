<x-admin-layout>
    <x-slot name="title">
        Create Menu
    </x-slot>

    <x-slot name="breadcrumb">
        <x-admin.breadcrumb :breadcrumbs="[['label' => 'menu', 'url' => route('admin.menus.index')], ['label' => 'create']]" />
    </x-slot>

    <div class="rounded-lg border max-w-3/4 mx-auto border-gray-200 bg-white dark:border-gray-800 dark:bg-white/3">
        <div class="px-5 py-4 sm:px-6 sm:py-5">
            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                Create Menu Form
            </h3>
        </div>
        <div class="p-5 space-y-6 border-t border-gray-100 dark:border-gray-800 sm:p-6">
            <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data"
                class="space-y-6">
                @csrf
                @method('PUT')
                <div class="-mx-2.5 flex flex-wrap gap-y-5">
                    <div class="w-full px-2.5">
                        <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
                            Kategori
                        </label>
                        <div class="relative z-20 bg-transparent">
                            <select name="category_id"
                                class="w-full px-4 py-3 text-sm text-gray-800 bg-transparent border border-gray-300 rounded-lg appearance-none dark:bg-dark-900 h-11 bg-none shadow-theme-xs placeholder:text-gray-400 focus:border-primary-500 focus:outline-hidden focus:ring-2 focus:ring-primary-300 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-primary-800"
                                required>
                                <option value="{{ $menu->category->id }}">{{ $menu->category->name }}</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="w-full px-2.5">
                        <x-input-label for="name" :value="__('Nama')" />
                        <x-text-input id="name" name="name" type="text" value="{{ $menu->name }}"
                            class="mt-1 block w-full @error('name') is-invalid @enderror" required />
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="price" :value="__('Harga (Rp)')" />
                        <x-text-input id="price" name="price" type="number"
                            value="{{ number_format($menu->price, 0, ',', '.') }}"
                            class="mt-1 block w-full @error('price') is-invalid @enderror" required />
                        @error('price')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full px-2.5 xl:w-1/2">
                        <x-input-label for="stock" :value="__('Stok')" />
                        <x-text-input id="stock" name="stock" type="number" value="{{ $menu->stock }}"
                            class="mt-1 block w-full @error('stock') is-invalid @enderror" required />
                        @error('stock')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full px-2.5">
                        <x-input-label for="description" :value="__('Deskripsi')" />
                        <textarea id="description" name="description" rows="3"
                            class="w-full mt-2 p-2 text-sm text-slate-700 bg-slate-50/25 border border-slate-300 rounded-lg resize-none order-note focus:outline-none focus:border-primary-500 @error('description') is-invalid @enderror">
                            {{ $menu->description }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full px-2.5">
                        <x-input-label :value="__('Preview Gambar')" />

                        @if (!$menu->image)
                            <span>Tidak Ada Gambar</span>
                        @endif

                        <div id="image-preview-wrapper"
                            class="relative w-48 h-48 mt-2 rounded-lg border border-gray-300 overflow-hidden
                            {{ $menu->image ? '' : 'hidden' }}">
                            <img id="image-preview"
                                src="{{ $menu->image ? asset('storage/menus/' . $menu->image) : '' }}"
                                class="w-full h-full object-cover">
                            <!-- tombol hapus -->
                            <button type="button" id="remove-image"
                                class="absolute top-2 right-2 bg-red-500 text-white rounded-full w-7 h-7 flex items-center justify-center hover:bg-red-600">
                                ✕
                            </button>
                        </div>

                        <!-- penanda hapus gambar lama -->
                        <input type="hidden" name="remove_image" id="remove_image" value="0">
                    </div>

                    <div class="w-full px-2.5">
                        <x-input-label for="image" :value="__('Gambar')" />
                        <x-text-input id="image" name="image" type="file"
                            class="mt-1 block w-full @error('image') is-invalid @enderror" accept="image/*" />
                        @error('image')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="w-full px-2.5 mt-2">
                        <div class="flex items-center justify-end gap-4 mt-1">
                            <x-primary-button as="a" outline="true" href="{{ route('admin.menus.index') }}"
                                class="px-5 py-2.5 border border-gray-300 hover:bg-gray-50">
                                Batal
                            </x-primary-button>
                            <x-primary-button class="px-5 py-2.5 hover:bg-primary-600" type="submit">
                                Simpan
                            </x-primary-button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
