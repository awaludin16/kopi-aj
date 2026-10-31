<x-admin-layout>
    <x-slot name="title">
        Category
    </x-slot>

    <x-slot name="breadcrumb">
        <x-admin.breadcrumb :breadcrumbs="[['label' => 'kategori']]" />
    </x-slot>

    <div class="flex items-center justify-between mb-4 border-b-0 border-b-solid rounded-t-2xl border-b-transparent">
        <h4 class="text-2xl font-light">Kelola Kategori</h4>
        <x-primary-button as="a" href="#"
            class="px-8 py-1.5 font-medium shadow hover:text-primary-600/90 hover:shadow-md transition-all duration-300"
            :outline="true">
            <i class="ri-add-circle-line text-base"></i>
            Kategori
        </x-primary-button>
    </div>

    <x-ui.table>
        <x-slot name="thead">
            <tr>
                <th class="px-6 py-3">ID</th>
                <th class="px-6 py-3">Nama Kategori</th>
                <th class="px-6 py-3">Dibuat</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </x-slot>

        @forelse ($categories as $category)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-3">{{ $category->id }}</td>
                <td class="px-6 py-3">{{ $category->name }}</td>
                <td class="px-6 py-3">{{ $category->created_at->format('d M Y') }}</td>
                <td class="px-6 py-3">
                    <div class="flex gap-2">
                        <a href="#"
                            class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-blue-600 rounded hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300">
                            <i class="mr-1 ri-edit-box-line"></i>Edit
                        </a>
                        <form action="#" method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus menu ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="button" disabled
                                class="inline-flex items-center px-3 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-300">
                                <i class="mr-1 ri-delete-bin-line"></i>Hapus
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="px-6 py-4 text-center text-gray-400">Belum ada kategori.</td>
            </tr>
        @endforelse
    </x-ui.table>
</x-admin-layout>
