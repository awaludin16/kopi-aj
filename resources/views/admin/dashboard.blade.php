<x-admin-layout>
    <x-slot name="title">
        Dashboard
    </x-slot>

    <x-slot name="breadcrumb">
        <x-admin.breadcrumb :breadcrumbs="[['label' => 'Dashboard']]" />
    </x-slot>

    <div class="space-y-6">

        {{-- Header & Quick Actions --}}
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
            <div>
                <h3 class="text-2xl font-bold text-gray-800 dark:text-white">Selamat Datang, {{ auth()->user()->name }}! 👋</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    Rangkuman kedai Kopi AJ &bull; Zona Waktu: <strong class="text-gray-700 dark:text-gray-200">WIB (Asia/Jakarta)</strong> &bull;
                    <span class="inline-flex items-center text-blue-600 dark:text-blue-400 font-medium">
                        <i class="ri-calendar-event-line mr-1"></i> {{ now('Asia/Jakarta')->translatedFormat('l, d F Y') }}
                    </span>
                </p>
            </div>
            
            <div class="flex items-center gap-2 flex-wrap">
                <button type="button" onclick="openScanModal()"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-xl shadow-md shadow-blue-500/20 transition-all cursor-pointer">
                    <i class="ri-qr-scan-2-line text-lg"></i>
                    <span>Scan QR Pesanan</span>
                </button>
            </div>
        </div>

        {{-- Filter Periode Bar --}}
        <div class="bg-white dark:bg-gray-800 p-4 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                {{-- Periode Pills --}}
                <div class="flex items-center gap-1.5 flex-wrap">
                    <span class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mr-2 flex items-center">
                        <i class="ri-filter-3-line mr-1"></i> Periode:
                    </span>
                    <a href="{{ route('admin.dashboard', ['period' => 'today']) }}"
                        class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $period === 'today' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                        Hari Ini
                    </a>
                    <a href="{{ route('admin.dashboard', ['period' => 'yesterday']) }}"
                        class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $period === 'yesterday' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                        Kemarin
                    </a>
                    <a href="{{ route('admin.dashboard', ['period' => 'this_week']) }}"
                        class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $period === 'this_week' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                        Minggu Ini
                    </a>
                    <a href="{{ route('admin.dashboard', ['period' => 'this_month']) }}"
                        class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $period === 'this_month' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                        Bulan Ini
                    </a>
                    <a href="{{ route('admin.dashboard', ['period' => 'this_year']) }}"
                        class="px-3.5 py-1.5 text-xs font-semibold rounded-lg transition-all {{ $period === 'this_year' ? 'bg-blue-600 text-white shadow-sm' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300 hover:bg-gray-200' }}">
                        Tahun Ini
                    </a>
                </div>

                {{-- Custom Date Form --}}
                <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2 flex-wrap">
                    <input type="hidden" name="period" value="custom">
                    <div class="flex items-center gap-1.5 bg-gray-50 dark:bg-gray-700/60 p-1 rounded-xl border border-gray-200 dark:border-gray-600">
                        <input type="date" name="start_date" value="{{ $customStart ?? '' }}"
                            class="text-xs bg-transparent border-0 py-1 px-2 text-gray-700 dark:text-gray-200 focus:ring-0">
                        <span class="text-xs text-gray-400">s/d</span>
                        <input type="date" name="end_date" value="{{ $customEnd ?? '' }}"
                            class="text-xs bg-transparent border-0 py-1 px-2 text-gray-700 dark:text-gray-200 focus:ring-0">
                    </div>
                    <button type="submit"
                        class="px-3 py-1.5 bg-gray-800 dark:bg-gray-700 hover:bg-black text-white text-xs font-semibold rounded-xl transition-colors">
                        Terapkan
                    </button>
                </form>
            </div>

            <div class="mt-3 pt-3 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400 flex items-center justify-between">
                <div>
                    Menampilkan data untuk: <strong class="text-gray-800 dark:text-gray-200">{{ $periodLabel }}</strong>
                </div>
            </div>
        </div>

        {{-- Metric Cards Grid --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
            {{-- Card 1: Pendapatan Periode --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Pendapatan</span>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">Rp {{ number_format($periodIncome, 0, ',', '.') }}</h4>
                    <span class="inline-flex items-center text-xs font-medium text-emerald-600 dark:text-emerald-400 mt-1">
                        <i class="ri-check-line mr-0.5"></i> Status Lunas
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-50 dark:bg-emerald-950/50 flex items-center justify-center text-emerald-600 dark:text-emerald-400 text-2xl">
                    <i class="ri-wallet-3-line"></i>
                </div>
            </div>

            {{-- Card 2: Total Pesanan Periode --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total Pesanan</span>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $periodOrdersCount }}</h4>
                    <span class="inline-flex items-center text-xs font-medium text-blue-600 dark:text-blue-400 mt-1">
                        <i class="ri-shopping-cart-2-line mr-0.5"></i> Pesanan Masuk
                    </span>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-blue-50 dark:bg-blue-950/50 flex items-center justify-center text-blue-600 dark:text-blue-400 text-2xl">
                    <i class="ri-shopping-bag-3-line"></i>
                </div>
            </div>

            {{-- Card 3: Pesanan Menunggu Konfirmasi (Realtime) --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Perlu Konfirmasi</span>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $pendingOrdersCount }}</h4>
                    @if ($pendingOrdersCount > 0)
                        <a href="{{ route('admin.orders.index') }}" class="inline-flex items-center text-xs font-medium text-amber-600 dark:text-amber-400 hover:underline mt-1">
                            <i class="ri-alert-line mr-0.5 animate-pulse"></i> Cek pesanan sekarang
                        </a>
                    @else
                        <span class="inline-flex items-center text-xs font-medium text-gray-400 mt-1">
                            <i class="ri-checkbox-circle-line mr-0.5"></i> Semua terproses
                        </span>
                    @endif
                </div>
                <div class="w-12 h-12 rounded-2xl bg-amber-50 dark:bg-amber-950/50 flex items-center justify-center text-amber-600 dark:text-amber-400 text-2xl">
                    <i class="ri-time-line"></i>
                </div>
            </div>

            {{-- Card 4: Total Menu Aktif --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex items-center justify-between">
                <div>
                    <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Menu Tersedia</span>
                    <h4 class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalMenusCount }}</h4>
                    <a href="{{ route('admin.menus.index') }}" class="inline-flex items-center text-xs font-medium text-purple-600 dark:text-purple-400 hover:underline mt-1">
                        <i class="ri-restaurant-2-line mr-0.5"></i> Kelola menu
                    </a>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-purple-50 dark:bg-purple-950/50 flex items-center justify-center text-purple-600 dark:text-purple-400 text-2xl">
                    <i class="ri-cup-line"></i>
                </div>
            </div>
        </div>

        {{-- Charts Section --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Sales Trend (2 Cols) --}}
            <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-base font-bold text-gray-900 dark:text-white">Tren Pendapatan</h4>
                        <p class="text-xs text-gray-400">Grafik omzet penjualan lunas pada periode terpilih</p>
                    </div>
                    <span class="text-xs font-semibold text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 px-3 py-1 rounded-full">
                        {{ $periodLabel }}
                    </span>
                </div>
                <div class="h-72 w-full">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>

            {{-- Payment Methods (1 Col) --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm flex flex-col justify-between">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h4 class="text-base font-bold text-gray-900 dark:text-white">Metode Pembayaran</h4>
                            <p class="text-xs text-gray-400">Perbandingan Cash vs Online</p>
                        </div>
                    </div>
                    <div class="h-56 relative flex items-center justify-center">
                        <canvas id="paymentMethodChart"></canvas>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-2 gap-3 pt-4 border-t border-gray-100 dark:border-gray-700">
                    <div class="p-3 bg-amber-50 dark:bg-amber-950/30 rounded-xl text-center">
                        <span class="text-xs text-amber-700 dark:text-amber-400 font-semibold block">💵 Cash</span>
                        <span class="text-lg font-bold text-amber-900 dark:text-amber-200">{{ $cashPaymentsCount }}</span>
                    </div>
                    <div class="p-3 bg-indigo-50 dark:bg-indigo-950/30 rounded-xl text-center">
                        <span class="text-xs text-indigo-700 dark:text-indigo-400 font-semibold block">💳 Online</span>
                        <span class="text-lg font-bold text-indigo-900 dark:text-indigo-200">{{ $onlinePaymentsCount }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Bottom Tables: Top Menus & Recent Orders --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Top Menus --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-base font-bold text-gray-900 dark:text-white">Menu Terlaris</h4>
                        <p class="text-xs text-gray-400">Paling banyak terjual pada periode ini</p>
                    </div>
                    <i class="ri-fire-line text-xl text-orange-500"></i>
                </div>

                <div class="divide-y divide-gray-100 dark:divide-gray-700">
                    @forelse ($topMenus as $index => $item)
                        <div class="py-3 flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg text-xs font-bold {{ $index === 0 ? 'bg-amber-500 text-white' : ($index === 1 ? 'bg-gray-400 text-white' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300') }}">
                                    #{{ $index + 1 }}
                                </span>
                                <div>
                                    <h5 class="text-sm font-semibold text-gray-900 dark:text-white">{{ $item->menu->name ?? 'Menu tidak ditemukan' }}</h5>
                                    <p class="text-xs text-gray-400">Rp {{ number_format($item->menu->price ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $item->total_sold }} porsi</span>
                                <p class="text-xs text-emerald-600 dark:text-emerald-400">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-gray-400 text-xs">
                            Belum ada transaksi menu lunas pada periode ini
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- Recent Orders --}}
            <div class="bg-white dark:bg-gray-800 p-5 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h4 class="text-base font-bold text-gray-900 dark:text-white">Pesanan Terkini</h4>
                        <p class="text-xs text-gray-400">Transaksi pada periode ini</p>
                    </div>
                    <a href="{{ route('admin.orders.list') }}" class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                        Lihat Semua →
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead>
                            <tr class="text-gray-400 uppercase border-b border-gray-100 dark:border-gray-700">
                                <th class="pb-2">Order</th>
                                <th class="pb-2">Meja</th>
                                <th class="pb-2">Total</th>
                                <th class="pb-2">Status</th>
                                <th class="pb-2 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse ($recentOrders as $order)
                                <tr>
                                    <td class="py-3 font-semibold text-gray-900 dark:text-white">#{{ $order->order_number }}</td>
                                    <td class="py-3">Meja {{ $order->table->table_number ?? '-' }}</td>
                                    <td class="py-3 font-medium">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td class="py-3">
                                        @php
                                            $isPaid = $order->payment?->status?->value === 'paid';
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full font-semibold {{ $isPaid ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300' }}">
                                            {{ $isPaid ? 'Lunas' : 'Pending' }}
                                        </span>
                                    </td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 dark:text-blue-400 font-semibold hover:underline">
                                            Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-gray-400">
                                        Belum ada pesanan pada periode ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- Scan QR Modal Component --}}
    <x-admin.scan-modal />

    {{-- Chart Scripts --}}
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. Sales Trend Chart
            const salesCtx = document.getElementById('salesTrendChart').getContext('2d');
            new Chart(salesCtx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels),
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: @json($chartIncomeData),
                        borderColor: '#2563eb',
                        backgroundColor: 'rgba(37, 99, 235, 0.1)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointBackgroundColor: '#2563eb',
                        pointRadius: 4,
                        pointHoverRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    if (value >= 1000000) return (value / 1000000).toFixed(1) + 'M';
                                    if (value >= 1000) return (value / 1000).toFixed(0) + 'k';
                                    return value;
                                }
                            },
                            grid: {
                                color: 'rgba(156, 163, 175, 0.1)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });

            // 2. Payment Method Donut Chart
            const paymentCtx = document.getElementById('paymentMethodChart').getContext('2d');
            const totalCash = {{ $cashPaymentsCount }};
            const totalOnline = {{ $onlinePaymentsCount }};
            const hasData = (totalCash + totalOnline) > 0;

            new Chart(paymentCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Cash', 'Online'],
                    datasets: [{
                        data: hasData ? [totalCash, totalOnline] : [1, 0],
                        backgroundColor: hasData ? ['#f59e0b', '#6366f1'] : ['#e5e7eb', '#e5e7eb'],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                boxWidth: 12,
                                padding: 15,
                                font: {
                                    size: 11
                                }
                            }
                        },
                        tooltip: {
                            enabled: hasData
                        }
                    },
                    cutout: '70%'
                }
            });
        });
    </script>
</x-admin-layout>
