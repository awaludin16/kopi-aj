<x-admin-layout>
    <x-slot name="title">
        Scan QR Pesanan
    </x-slot>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scan QR Pesanan') }}
        </h2>
    </x-slot>

    <x-slot name="breadcrumb">
        <x-admin.breadcrumb :breadcrumbs="[['label' => 'scan']]" />
    </x-slot>

    <div class="max-w-lg mx-auto py-6">

        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4 text-center">
            Scan QR Code Pesanan
        </h3>

        {{-- Tab Toggle: Kamera vs Manual --}}
        <div class="flex mb-4 bg-gray-100 dark:bg-gray-700 rounded-lg p-1">
            <button id="tab-camera" type="button"
                class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm"
                onclick="switchTab('camera')">
                <i class="ri-camera-line mr-1"></i> Kamera
            </button>
            <button id="tab-manual" type="button"
                class="flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all text-gray-500 dark:text-gray-400"
                onclick="switchTab('manual')">
                <i class="ri-keyboard-line mr-1"></i> Input Manual
            </button>
        </div>

        {{-- Camera Scanner Section --}}
        <div id="section-camera" class="mb-4">
            <div id="qr-reader" class="rounded-lg overflow-hidden border-2 border-gray-200 dark:border-gray-600" style="width: 100%;"></div>

            <div id="scan-status" class="mt-3 text-center text-sm text-gray-500 dark:text-gray-400">
                <i class="ri-qr-scan-2-line text-lg"></i>
                <span>Arahkan kamera ke QR Code...</span>
            </div>

            {{-- Hidden form for camera scan auto-submit --}}
            <form id="camera-form" method="POST" action="{{ route('admin.orders.process') }}" class="hidden">
                @csrf
                <input type="hidden" name="qr_token" id="camera-qr-token">
            </form>
        </div>

        {{-- Manual Input Section --}}
        <div id="section-manual" class="mb-4 hidden">
            <form method="POST" action="{{ route('admin.orders.process') }}">
                @csrf

                <div class="mb-3">
                    <label for="qr_token" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                        Kode QR Token
                    </label>
                    <input type="text" name="qr_token" id="qr_token"
                        placeholder="Masukkan atau tempel QR token..."
                        class="w-full border border-gray-300 dark:border-gray-600 rounded-lg p-3 text-sm bg-white dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        autofocus>
                </div>

                <button type="submit"
                    class="w-full bg-gray-900 dark:bg-gray-700 text-white py-3 rounded-lg font-semibold hover:bg-gray-800 dark:hover:bg-gray-600 transition-colors flex items-center justify-center gap-2">
                    <i class="ri-search-line"></i>
                    Lihat Pesanan
                </button>
            </form>
        </div>

        {{-- Error Messages --}}
        @error('qr_token')
            <div class="mt-3 p-3 bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 text-red-700 dark:text-red-400 rounded-lg text-sm flex items-center gap-2">
                <i class="ri-error-warning-line text-lg"></i>
                <span>{{ $message }}</span>
            </div>
        @enderror

    </div>

    {{-- html5-qrcode Library --}}
    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

    <script>
        let html5QrCode = null;
        let isScanning = false;

        // Tab switching
        function switchTab(tab) {
            const tabCamera = document.getElementById('tab-camera');
            const tabManual = document.getElementById('tab-manual');
            const sectionCamera = document.getElementById('section-camera');
            const sectionManual = document.getElementById('section-manual');

            const activeClass = 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm';
            const inactiveClass = 'text-gray-500 dark:text-gray-400';

            if (tab === 'camera') {
                tabCamera.className = `flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all ${activeClass}`;
                tabManual.className = `flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all ${inactiveClass}`;
                sectionCamera.classList.remove('hidden');
                sectionManual.classList.add('hidden');
                startScanner();
            } else {
                tabManual.className = `flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all ${activeClass}`;
                tabCamera.className = `flex-1 py-2 px-4 rounded-md text-sm font-medium transition-all ${inactiveClass}`;
                sectionManual.classList.remove('hidden');
                sectionCamera.classList.add('hidden');
                stopScanner();
                document.getElementById('qr_token')?.focus();
            }
        }

        // Start QR scanner
        function startScanner() {
            if (isScanning) return;

            const qrReader = document.getElementById('qr-reader');
            const statusEl = document.getElementById('scan-status');

            html5QrCode = new Html5Qrcode("qr-reader");

            const config = {
                fps: 10,
                qrbox: { width: 250, height: 250 },
                aspectRatio: 1.0,
            };

            html5QrCode.start(
                { facingMode: "environment" },
                config,
                (decodedText) => {
                    // QR berhasil terbaca
                    statusEl.innerHTML = `
                        <div class="text-green-600 dark:text-green-400">
                            <i class="ri-checkbox-circle-line text-lg"></i>
                            <span>QR terdeteksi! Memproses...</span>
                        </div>
                    `;

                    // Stop scanner & submit form
                    stopScanner();
                    document.getElementById('camera-qr-token').value = decodedText;
                    document.getElementById('camera-form').submit();
                },
                (errorMessage) => {
                    // Scan in progress, no QR found yet — silent
                }
            ).then(() => {
                isScanning = true;
                statusEl.innerHTML = `
                    <i class="ri-qr-scan-2-line text-lg"></i>
                    <span>Arahkan kamera ke QR Code...</span>
                `;
            }).catch((err) => {
                isScanning = false;
                statusEl.innerHTML = `
                    <div class="text-red-500">
                        <i class="ri-error-warning-line text-lg"></i>
                        <span>Kamera tidak tersedia. Gunakan input manual.</span>
                    </div>
                `;
                console.error('QR Scanner error:', err);
                // Fallback to manual tab
                setTimeout(() => switchTab('manual'), 1500);
            });
        }

        // Stop QR scanner
        function stopScanner() {
            if (html5QrCode && isScanning) {
                html5QrCode.stop().then(() => {
                    isScanning = false;
                }).catch((err) => {
                    console.error('Stop scanner error:', err);
                });
            }
        }

        // Auto-start camera on page load
        document.addEventListener('DOMContentLoaded', () => {
            startScanner();
        });

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            stopScanner();
        });
    </script>
</x-admin-layout>
