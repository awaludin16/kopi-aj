<x-admin-layout>
    <x-slot name="title">
        Detail Pesanan
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Pesanan') }}
        </h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <x-admin.breadcrumb :breadcrumbs="[['label' => 'orders', 'url' => route('admin.orders.index')], ['label' => 'detail']]" />
    </x-slot>

    <div class="max-w-2xl mx-auto py-10">

        {{-- Flash messages --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-800 rounded-lg flex items-center gap-2">
                <i class="ri-checkbox-circle-line text-xl"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('info'))
            <div class="mb-4 p-4 bg-blue-100 border border-blue-300 text-blue-800 rounded-lg flex items-center gap-2">
                <i class="ri-information-line text-xl"></i>
                <span>{{ session('info') }}</span>
            </div>
        @endif

        <h2 class="text-xl font-semibold mb-4">
            Detail Pesanan
        </h2>

        {{-- Info Pesanan --}}
        <div class="mb-4 bg-white rounded-lg border border-gray-200 p-4 space-y-2">
            <p><strong>No Order:</strong> {{ $order->order_number }}</p>
            <p><strong>Meja:</strong> {{ $order->table->table_number }}</p>
            <p><strong>Total:</strong> Rp {{ number_format($order->total, 0, ',', '.') }}</p>
            <p>
                <strong>Metode Pembayaran:</strong>
                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full
                    {{ $order->payment->payment_method === 'cash' ? 'bg-amber-100 text-amber-800' : 'bg-indigo-100 text-indigo-800' }}">
                    {{ $order->payment->payment_method === 'cash' ? '💵 Cash' : '💳 Online' }}
                </span>
            </p>
            <p>
                <strong>Status Pembayaran:</strong>
                @php
                    $statusColors = [
                        'pending' => 'bg-yellow-100 text-yellow-800',
                        'paid' => 'bg-green-100 text-green-800',
                        'failed' => 'bg-red-100 text-red-800',
                        'expired' => 'bg-gray-100 text-gray-800',
                    ];
                    $statusColor = $statusColors[$order->payment->status->value] ?? 'bg-gray-100 text-gray-800';
                @endphp
                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full {{ $statusColor }}">
                    {{ ucfirst($order->payment->status->value) }}
                </span>
            </p>
            @if ($order->note)
                <p><strong>Catatan:</strong> {{ $order->note }}</p>
            @endif
        </div>

        {{-- Tabel Item Pesanan --}}
        <table class="w-full mb-6 border border-gray-200 rounded-lg overflow-hidden">
            <thead>
                <tr class="bg-gray-100">
                    <th class="p-3 text-left text-sm font-semibold">Menu</th>
                    <th class="p-3 text-right text-sm font-semibold">Qty</th>
                    <th class="p-3 text-right text-sm font-semibold">Harga</th>
                    <th class="p-3 text-right text-sm font-semibold">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr class="border-t border-gray-100">
                        <td class="p-3">{{ $item->menu->name }}</td>
                        <td class="p-3 text-right">{{ $item->qty }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="bg-gray-50">
                @if ($order->pb1 > 0)
                    <tr class="border-t border-gray-200">
                        <td colspan="3" class="p-3 text-right text-sm text-gray-600">PB1</td>
                        <td class="p-3 text-right text-sm">Rp {{ number_format($order->pb1, 0, ',', '.') }}</td>
                    </tr>
                @endif
                @if ($order->service_charge > 0)
                    <tr class="border-t border-gray-100">
                        <td colspan="3" class="p-3 text-right text-sm text-gray-600">Service Charge</td>
                        <td class="p-3 text-right text-sm">Rp {{ number_format($order->service_charge, 0, ',', '.') }}</td>
                    </tr>
                @endif
                <tr class="border-t-2 border-gray-300">
                    <td colspan="3" class="p-3 text-right font-bold">Total</td>
                    <td class="p-3 text-right font-bold text-lg">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                </tr>
            </tfoot>
        </table>

        {{-- Action Buttons --}}
        <div class="mt-6 space-y-3">

            @if ($order->payment->status === \App\Enums\PaymentStatus::PENDING)
                {{-- Tombol Konfirmasi Pembayaran — untuk semua metode --}}
                <form method="POST" action="{{ route('admin.orders.confirm-payment', $order) }}"
                    onsubmit="return confirm('Konfirmasi pembayaran {{ $order->payment->payment_method === 'cash' ? 'cash' : 'online' }} untuk pesanan #{{ $order->order_number }}?')">
                    @csrf

                    <button type="submit"
                        class="w-full bg-green-600 text-white py-3 rounded-lg font-semibold hover:bg-green-700 transition-colors flex items-center justify-center gap-2">
                        <i class="ri-checkbox-circle-line text-xl"></i>
                        Konfirmasi Pembayaran ({{ $order->payment->payment_method === 'cash' ? 'Cash' : 'Online' }})
                    </button>
                </form>
            @else
                {{-- Pembayaran sudah dikonfirmasi --}}
                <div class="w-full bg-green-100 text-green-800 py-3 rounded-lg font-semibold text-center flex items-center justify-center gap-2">
                    <i class="ri-checkbox-circle-fill text-xl"></i>
                    Pembayaran Sudah Dikonfirmasi
                </div>

                {{-- Tombol Cetak Struk --}}
                <a href="{{ route('admin.orders.receipt', $order) }}" target="_blank"
                    class="w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors flex items-center justify-center gap-2">
                    <i class="ri-printer-line text-xl"></i>
                    Cetak Struk
                </a>
            @endif

            {{-- Tombol Kembali --}}
            <a href="{{ route('admin.orders.index') }}"
                class="w-full bg-gray-200 text-gray-700 py-3 rounded-lg font-semibold hover:bg-gray-300 transition-colors flex items-center justify-center gap-2">
                <i class="ri-arrow-left-line text-xl"></i>
                Kembali ke Daftar Pesanan
            </a>
        </div>

    </div>
</x-admin-layout>
