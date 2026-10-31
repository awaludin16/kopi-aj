<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>{{ $title ? 'AJ Admin - ' . $title : 'Kopi AJ' }}</title>

    {{-- Font --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Montserrat:ital,wght@0,100..900;1,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="text-gray-800 dark:bg-gray-900 dark:text-white font-inter">

    <!-- start: Sidebar -->
    @include('layouts.sidebar')
    <!-- end: Sidebar -->

    <!-- start: Main -->
    <main
        class="dark:bg-gray-900 dark:text-white w-full md:w-[calc(100%-256px)] md:ml-64 bg-gray-50 min-h-screen transition-all main">

        <!-- start: Navbar -->
        <div
            class="sticky top-0 left-0 z-30 flex items-center px-6 py-2 bg-white shadow dark:bg-gray-900 dark:shadow-md dark:shadow-black/10 dark:text-white shadow-black/5">
            <button type="button" class="text-lg text-gray-600 dark:text-white sidebar-toggle">
                <i class="ri-menu-line"></i>
            </button>

            {{-- Breadcrumb --}}
            @isset($breadcrumb)
                {{ $breadcrumb }}
            @endisset

            <ul class="flex items-center gap-2 p-2 ml-auto">
                {{-- Notifikasi Bell Realtime --}}
                <li class="relative dropdown">
                    <button type="button" id="notifDropdownToggle"
                        class="relative flex items-center justify-center w-8 h-8 text-gray-700 rounded-full dark:text-gray-50 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors dropdown-toggle">
                        <i class="ri-notification-3-line text-lg"></i>
                        <span id="notif-badge-count"
                            class="absolute -top-1 -right-1 hidden items-center justify-center px-1.5 py-0.5 text-[10px] font-bold text-white bg-red-600 rounded-full shadow-sm animate-pulse">
                            0
                        </span>
                    </button>

                    <div id="notifDropdownMenu"
                        class="dropdown-menu hidden absolute right-0 top-10 w-80 sm:w-96 rounded-2xl bg-white dark:bg-gray-800 shadow-xl border border-gray-100 dark:border-gray-700 z-50 overflow-hidden">
                        <div class="p-4 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i class="ri-notification-3-fill text-blue-600 dark:text-blue-400"></i>
                                <h4 class="text-sm font-bold text-gray-800 dark:text-white">Pesanan Masuk</h4>
                            </div>
                            <span id="notif-count-pill" class="text-[11px] font-semibold px-2 py-0.5 rounded-full bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300">
                                0 Menunggu
                            </span>
                        </div>

                        <div id="notif-items-list" class="max-h-72 overflow-y-auto divide-y divide-gray-100 dark:divide-gray-700">
                            <div class="p-6 text-center text-xs text-gray-400">
                                <i class="ri-inbox-line text-3xl mb-1 block"></i>
                                Memuat notifikasi...
                            </div>
                        </div>

                        <div class="p-2.5 bg-gray-50 dark:bg-gray-800/80 border-t border-gray-100 dark:border-gray-700 text-center">
                            <a href="{{ route('admin.orders.index') }}"
                                class="text-xs font-semibold text-blue-600 dark:text-blue-400 hover:underline">
                                Buka Pesanan Masuk →
                            </a>
                        </div>
                    </div>
                </li>

                {{-- Dark Mode Toggle --}}
                <button id="toggleDarkMode"
                    class="flex items-center justify-center w-8 h-8 text-gray-700 rounded-full dark:text-gray-50 hover:border-gray-300 dark:hover:bg-gray-800 hover:bg-gray-100 hover:text-gray-800 dark:hover:text-gray-300">
                    <i id="themeIcon" class="ri-moon-line"></i>
                </button>

                {{-- User Dropdown --}}
                <li class="ml-1 dropdown">
                    <button type="button"
                        class="flex items-center justify-center gap-2 bg-gray-100 border border-gray-200 pr-3 h-8 text-gray-700 rounded-full dark:text-gray-50 dark:hover:bg-gray-800 hover:bg-gray-100 hover:text-gray-900 hover:border-gray-300 dark:hover:text-gray-300 cursor-pointer dropdown-toggle">
                        <span
                            class="flex items-center justify-center rounded-full border-r border-gray-200 w-8 h-8 bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-300">
                            <i class="ri-user-line text-sm"></i>
                        </span>
                        <p class="text-xs font-semibold">{{ auth()->user()->name }}</p>
                    </button>
                    <ul
                        class="dropdown-menu hidden absolute top-18 right-8 shadow-md shadow-black/5 z-30 py-1.5 rounded-md bg-white border border-gray-100 w-full max-w-35">
                        <li class="flex">
                            <x-dropdown-link class="flex items-center gap-2" href="{{ route('admin.dashboard') }}">
                                <i class="ri-dashboard-line text-lg text-slate-400"></i>
                                {{ __('Dashboard') }}
                            </x-dropdown-link>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf

                                <x-dropdown-link :href="route('logout')" class="flex items-center gap-2"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                    <svg xmlns="http://www.w3.org/2000/svg" height="18px" viewBox="0 -960 960 960"
                                        width="18px" fill="#99a1af">
                                        <path
                                            d="M200-120q-33 0-56.5-23.5T120-200v-560q0-33 23.5-56.5T200-840h280v80H200v560h280v80H200Zm440-160-55-58 102-102H360v-80h327L585-622l55-58 200 200-200 200Z" />
                                    </svg>
                                    {{ __('Logout') }}
                                </x-dropdown-link>
                            </form>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- end: Navbar -->


        <div class="p-6">
            {{ $slot }}
        </div>

    </main>
    <!-- end: Main -->

    {{-- Floating Toast Container untuk Notifikasi Realtime --}}
    <div id="toast-notification-container" class="fixed top-16 right-6 z-50 flex flex-col gap-3 max-w-sm w-full pointer-events-none"></div>

    <script src="https://unpkg.com/@popperjs/core@2"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    {{-- Realtime Notification Script --}}
    <script>
        (function() {
            let lastKnownOrderId = 0;
            let isInitialCheck = true;
            const notifCheckUrl = "{{ route('admin.notifications.check') }}";

            // Web Audio API sintetis untuk suara notifikasi lonceng kasir
            function playOrderChime() {
                try {
                    const AudioCtx = window.AudioContext || window.webkitAudioContext;
                    if (!AudioCtx) return;
                    const ctx = new AudioCtx();

                    // Nada 1 (Ding)
                    const osc1 = ctx.createOscillator();
                    const gain1 = ctx.createGain();
                    osc1.type = 'sine';
                    osc1.frequency.setValueAtTime(880, ctx.currentTime); // Note A5
                    gain1.gain.setValueAtTime(0.3, ctx.currentTime);
                    gain1.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.5);
                    osc1.connect(gain1);
                    gain1.connect(ctx.destination);
                    osc1.start(ctx.currentTime);
                    osc1.stop(ctx.currentTime + 0.5);

                    // Nada 2 (Dong - harmoni lebih tinggi)
                    const osc2 = ctx.createOscillator();
                    const gain2 = ctx.createGain();
                    osc2.type = 'triangle';
                    osc2.frequency.setValueAtTime(1174.66, ctx.currentTime + 0.15); // Note D6
                    gain2.gain.setValueAtTime(0.35, ctx.currentTime + 0.15);
                    gain2.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.8);
                    osc2.connect(gain2);
                    gain2.connect(ctx.destination);
                    osc2.start(ctx.currentTime + 0.15);
                    osc2.stop(ctx.currentTime + 0.8);
                } catch (e) {
                    console.warn('Audio chime warning:', e);
                }
            }

            // Tampilkan Toast Alert Mengambang
            function showOrderToast(order) {
                const container = document.getElementById('toast-notification-container');
                if (!container) return;

                const toast = document.createElement('div');
                toast.className = 'pointer-events-auto transform transition-all duration-300 ease-out translate-x-full opacity-0 bg-white dark:bg-gray-800 border-2 border-blue-500 rounded-2xl shadow-2xl p-4 flex items-start gap-3';
                
                const methodBadge = order.payment_method === 'cash' 
                    ? '<span class="px-1.5 py-0.5 text-[10px] bg-amber-100 text-amber-800 rounded font-bold">💵 Cash</span>' 
                    : '<span class="px-1.5 py-0.5 text-[10px] bg-indigo-100 text-indigo-800 rounded font-bold">💳 Online</span>';

                toast.innerHTML = `
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-blue-50 dark:bg-blue-900/50 flex items-center justify-center text-blue-600 dark:text-blue-400 text-xl animate-bounce">
                        <i class="ri-shopping-cart-2-fill"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase tracking-wide">Pesanan Baru Masuk!</span>
                            <span class="text-[10px] text-gray-400">${order.created_at_time}</span>
                        </div>
                        <h5 class="text-sm font-bold text-gray-900 dark:text-white mt-0.5">#${order.order_number} &bull; Meja ${order.table_number}</h5>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs font-semibold text-emerald-600 dark:text-emerald-400">${order.formatted_total}</span>
                            ${methodBadge}
                        </div>
                        <div class="mt-2.5 flex items-center gap-2">
                            <a href="${order.detail_url}" class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                Lihat Pesanan
                            </a>
                            <button type="button" class="toast-dismiss text-xs text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                                Tutup
                            </button>
                        </div>
                    </div>
                `;

                container.appendChild(toast);

                // Animate In
                setTimeout(() => {
                    toast.classList.remove('translate-x-full', 'opacity-0');
                }, 50);

                // Dismiss Button
                toast.querySelector('.toast-dismiss').addEventListener('click', () => {
                    dismissToast(toast);
                });

                // Auto dismiss after 8 seconds
                setTimeout(() => {
                    dismissToast(toast);
                }, 8000);
            }

            function dismissToast(toast) {
                toast.classList.add('translate-x-full', 'opacity-0');
                setTimeout(() => toast.remove(), 300);
            }

            // Update UI Notifikasi & Badge
            function updateNotificationUI(data) {
                const badge = document.getElementById('notif-badge-count');
                const sidebarBadge = document.getElementById('sidebar-pending-badge');
                const pill = document.getElementById('notif-count-pill');
                const list = document.getElementById('notif-items-list');

                const count = data.pending_count || 0;

                // Update Navbar Badge
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.classList.remove('hidden');
                        badge.classList.add('flex');
                    } else {
                        badge.classList.add('hidden');
                        badge.classList.remove('flex');
                    }
                }

                // Update Sidebar Badge
                if (sidebarBadge) {
                    if (count > 0) {
                        sidebarBadge.textContent = count > 99 ? '99+' : count;
                        sidebarBadge.classList.remove('hidden');
                    } else {
                        sidebarBadge.classList.add('hidden');
                    }
                }

                if (pill) {
                    pill.textContent = `${count} Menunggu`;
                }

                // Render List Dropdown
                if (list) {
                    if (data.pending_orders && data.pending_orders.length > 0) {
                        list.innerHTML = data.pending_orders.map(order => `
                            <a href="${order.detail_url}" class="p-3 flex items-center justify-between hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors block">
                                <div class="flex items-center gap-2.5">
                                    <div class="w-8 h-8 rounded-lg bg-amber-50 dark:bg-amber-900/40 text-amber-600 dark:text-amber-400 flex items-center justify-center text-sm">
                                        <i class="ri-time-line"></i>
                                    </div>
                                    <div>
                                        <h5 class="text-xs font-bold text-gray-900 dark:text-white">#${order.order_number}</h5>
                                        <p class="text-[11px] text-gray-500 dark:text-gray-400">Meja ${order.table_number} &bull; ${order.formatted_total}</p>
                                    </div>
                                </div>
                                <span class="text-[10px] text-gray-400">${order.time_ago}</span>
                            </a>
                        `).join('');
                    } else {
                        list.innerHTML = `
                            <div class="p-6 text-center text-xs text-gray-400">
                                <i class="ri-checkbox-circle-line text-2xl text-emerald-500 mb-1 block"></i>
                                Tidak ada pesanan menunggu
                            </div>
                        `;
                    }
                }
            }

            // Polling Function
            async function pollNotifications() {
                try {
                    const url = `${notifCheckUrl}?last_order_id=${lastKnownOrderId}`;
                    const res = await fetch(url, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    if (!res.ok) return;

                    const data = await res.json();

                    // Simpan ID terbesar
                    if (data.current_max_id) {
                        lastKnownOrderId = Math.max(lastKnownOrderId, data.current_max_id);
                    }

                    // Jika bukan initial check dan ada pesanan baru masuk
                    if (!isInitialCheck && data.has_new && data.latest_order) {
                        playOrderChime();
                        showOrderToast(data.latest_order);
                    }

                    updateNotificationUI(data);
                    isInitialCheck = false;

                } catch (err) {
                    console.warn('Realtime poll warning:', err);
                }
            }

            // Jalankan saat halaman siap
            document.addEventListener('DOMContentLoaded', function() {
                pollNotifications();
                // Polling setiap 4 detik
                setInterval(pollNotifications, 4000);
            });
        })();
    </script>
</body>

</html>
