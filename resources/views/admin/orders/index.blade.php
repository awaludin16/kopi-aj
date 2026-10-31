<x-admin-layout>
    <x-slot name="title">
        {{ __('Pesanan Masuk') }}
    </x-slot>

    <x-slot name="breadcrumb">
        <x-admin.breadcrumb :breadcrumbs="[['label' => 'Pesanan Masuk']]" />
    </x-slot>

    {{-- Alert Messages --}}
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg flex items-center gap-2">
            <i class="ri-checkbox-circle-line text-xl"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    @error('qr_token')
        <div class="mb-4 p-4 bg-red-100 border border-red-300 text-red-800 rounded-lg flex items-center gap-2">
            <i class="ri-error-warning-line text-xl"></i>
            <span>{{ $message }}</span>
        </div>
    @enderror

    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h4 class="text-2xl font-bold text-gray-800 dark:text-white">Pesanan Masuk</h4>
            <p class="text-sm text-gray-500 dark:text-gray-400">Daftar pesanan baru yang menunggu konfirmasi pembayaran</p>
        </div>

        {{-- Trigger Scan Button --}}
        <div>
            <button type="button" onclick="openScanModal()"
                class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/20 transition-all cursor-pointer">
                <i class="ri-qr-scan-2-line text-lg"></i>
                <span>Scan QR Pesanan</span>
            </button>
        </div>
    </div>

    <x-ui.table>
        <x-slot name="header">
            <form method="GET" action="">
                <div class="flex items-center justify-between gap-4 flex-wrap">
                    <div class="relative">
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
                            placeholder="Cari pesanan atau meja...">
                    </div>

                    <div class="text-xs text-gray-500 dark:text-gray-400">
                        Total Pesanan Menunggu: <span class="font-bold text-gray-700 dark:text-gray-200">{{ $orders->count() }}</span>
                    </div>
                </div>
            </form>
        </x-slot>

        <x-slot name="thead">
            <tr>
                <th class="px-6 py-3">Pesanan</th>
                <th class="px-6 py-3">Meja</th>
                <th class="px-6 py-3">Total</th>
                <th class="px-6 py-3">Metode</th>
                <th class="px-6 py-3">Pembayaran</th>
                <th class="px-6 py-3">Waktu</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </x-slot>

        @forelse ($orders as $order)
            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                <td class="px-6 py-3 font-semibold text-gray-900 dark:text-white">#{{ $order->order_number }}</td>
                <td class="px-6 py-3">Meja {{ $order->table->table_number ?? '-' }}</td>
                <td class="px-6 py-3 font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                <td class="px-6 py-3">
                    <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{ $order->payment?->payment_method === 'cash' ? 'bg-amber-100 text-amber-800' : 'bg-indigo-100 text-indigo-800' }}">
                        {{ $order->payment?->payment_method === 'cash' ? '💵 Cash' : '💳 Online' }}
                    </span>
                </td>
                <td class="px-6 py-3">
                    <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded-full">
                        <span class="w-1.5 h-1.5 mr-1.5 bg-yellow-500 rounded-full animate-pulse"></span>
                        Menunggu Bayar
                    </span>
                </td>
                <td class="px-6 py-3">
                    <div>{{ $order->created_at->format('H:i') }}</div>
                    <small class="text-gray-400">{{ $order->created_at->format('d M Y') }}</small>
                </td>
                <td class="px-6 py-3">
                    <a href="{{ route('admin.orders.show', $order) }}"
                        class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors shadow-sm">
                        <i class="ri-eye-line mr-1"></i>
                        Detail
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <i class="ri-inbox-line text-4xl text-gray-300 mb-2"></i>
                        <p class="font-medium">Tidak ada pesanan yang menunggu pembayaran</p>
                    </div>
                </td>
            </tr>
        @endforelse
    </x-ui.table>

    {{-- Scan QR Modal Component --}}
    <x-admin.scan-modal />
</x-admin-layout>
