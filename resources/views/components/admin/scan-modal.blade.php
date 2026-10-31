{{-- Modal Scan QR Code Pesanan --}}
<div id="qrScanModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity" onclick="closeScanModal()"></div>

    {{-- Modal Box --}}
    <div class="flex min-h-screen items-center justify-center p-4">
        <div class="relative w-full max-w-lg rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-2xl transition-all border border-gray-100 dark:border-gray-700">
            
            {{-- Header --}}
            <div class="flex items-center justify-between pb-4 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-2">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-blue-50 dark:bg-blue-900/50 text-blue-600 dark:text-blue-400">
                        <i class="ri-qr-scan-2-line text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Scan QR Pesanan</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">Scan QR Code customer untuk proses pembayaran</p>
                    </div>
                </div>
                <button type="button" onclick="closeScanModal()" 
                    class="rounded-lg p-2 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-700 dark:hover:text-gray-200">
                    <i class="ri-close-line text-xl"></i>
                </button>
            </div>

            {{-- Tabs --}}
            <div class="mt-4 flex bg-gray-100 dark:bg-gray-700/60 rounded-xl p-1">
                <button id="modal-tab-camera" type="button"
                    class="flex-1 py-2 px-3 rounded-lg text-xs font-semibold transition-all bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm flex items-center justify-center gap-1.5"
                    onclick="switchModalTab('camera')">
                    <i class="ri-camera-lens-line text-base"></i> Kamera
                </button>
                <button id="modal-tab-manual" type="button"
                    class="flex-1 py-2 px-3 rounded-lg text-xs font-semibold transition-all text-gray-500 dark:text-gray-400 flex items-center justify-center gap-1.5"
                    onclick="switchModalTab('manual')">
                    <i class="ri-keyboard-line text-base"></i> Input Manual
                </button>
            </div>

            {{-- Camera Section --}}
            <div id="modal-section-camera" class="mt-4">
                <div class="relative overflow-hidden rounded-xl bg-black border-2 border-dashed border-gray-300 dark:border-gray-600">
                    <div id="modal-qr-reader" style="width: 100%; min-height: 280px;"></div>
                </div>

                <div id="modal-scan-status" class="mt-3 text-center text-xs text-gray-500 dark:text-gray-400 flex items-center justify-center gap-2">
                    <i class="ri-loader-4-line animate-spin text-base text-blue-500"></i>
                    <span>Arahkan kamera tepat ke QR Code pesanan customer</span>
                </div>

                <form id="modal-camera-form" method="POST" action="{{ route('admin.orders.process') }}" class="hidden">
                    @csrf
                    <input type="hidden" name="qr_token" id="modal-camera-qr-token">
                </form>
            </div>

            {{-- Manual Section --}}
            <div id="modal-section-manual" class="mt-4 hidden">
                <form method="POST" action="{{ route('admin.orders.process') }}">
                    @csrf
                    <div class="mb-4">
                        <label for="modal-input-qr-token" class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                            Kode / Token QR
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                                <i class="ri-barcode-line text-lg"></i>
                            </span>
                            <input type="text" name="qr_token" id="modal-input-qr-token"
                                placeholder="Tempel token QR atau scan barcode scanner..."
                                class="w-full pl-10 pr-3 py-2.5 text-sm border border-gray-300 dark:border-gray-600 rounded-xl bg-gray-50 dark:bg-gray-700/50 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center justify-center gap-2 shadow-lg shadow-blue-500/20">
                        <i class="ri-search-line"></i>
                        Cari & Buka Pesanan
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>

{{-- Load Html5Qrcode Library jika belum terload --}}
<script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>

<script>
    let modalQrScanner = null;
    let isModalScanning = false;

    function openScanModal() {
        const modal = document.getElementById('qrScanModal');
        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        switchModalTab('camera');
    }

    function closeScanModal() {
        const modal = document.getElementById('qrScanModal');
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        stopModalScanner();
    }

    function switchModalTab(tab) {
        const tabCamera = document.getElementById('modal-tab-camera');
        const tabManual = document.getElementById('modal-tab-manual');
        const secCamera = document.getElementById('modal-section-camera');
        const secManual = document.getElementById('modal-section-manual');

        const activeClass = 'bg-white dark:bg-gray-800 text-gray-900 dark:text-white shadow-sm';
        const inactiveClass = 'text-gray-500 dark:text-gray-400';

        if (tab === 'camera') {
            tabCamera.className = `flex-1 py-2 px-3 rounded-lg text-xs font-semibold transition-all ${activeClass} flex items-center justify-center gap-1.5`;
            tabManual.className = `flex-1 py-2 px-3 rounded-lg text-xs font-semibold transition-all ${inactiveClass} flex items-center justify-center gap-1.5`;
            secCamera.classList.remove('hidden');
            secManual.classList.add('hidden');
            startModalScanner();
        } else {
            tabManual.className = `flex-1 py-2 px-3 rounded-lg text-xs font-semibold transition-all ${activeClass} flex items-center justify-center gap-1.5`;
            tabCamera.className = `flex-1 py-2 px-3 rounded-lg text-xs font-semibold transition-all ${inactiveClass} flex items-center justify-center gap-1.5`;
            secManual.classList.remove('hidden');
            secCamera.classList.add('hidden');
            stopModalScanner();
            setTimeout(() => document.getElementById('modal-input-qr-token')?.focus(), 150);
        }
    }

    function startModalScanner() {
        if (isModalScanning) return;

        const statusEl = document.getElementById('modal-scan-status');

        if (!modalQrScanner) {
            modalQrScanner = new Html5Qrcode("modal-qr-reader");
        }

        const config = {
            fps: 10,
            qrbox: { width: 220, height: 220 },
            aspectRatio: 1.0,
        };

        modalQrScanner.start(
            { facingMode: "environment" },
            config,
            (decodedText) => {
                statusEl.innerHTML = `
                    <div class="text-green-600 dark:text-green-400 font-semibold flex items-center justify-center gap-1.5">
                        <i class="ri-checkbox-circle-line text-lg"></i>
                        <span>QR Ditemukan! Mengalihkan...</span>
                    </div>
                `;
                stopModalScanner();
                document.getElementById('modal-camera-qr-token').value = decodedText;
                document.getElementById('modal-camera-form').submit();
            },
            (errorMessage) => {
                // scanning...
            }
        ).then(() => {
            isModalScanning = true;
            statusEl.innerHTML = `
                <i class="ri-qr-scan-2-line text-base text-blue-500"></i>
                <span>Arahkan kamera tepat ke QR Code pesanan</span>
            `;
        }).catch((err) => {
            isModalScanning = false;
            statusEl.innerHTML = `
                <div class="text-red-500 font-medium">
                    <i class="ri-error-warning-line"></i>
                    <span>Kamera tidak dapat diakses atau izin ditolak.</span>
                </div>
            `;
            console.warn('Modal scanner error:', err);
        });
    }

    function stopModalScanner() {
        if (modalQrScanner && isModalScanning) {
            modalQrScanner.stop().then(() => {
                isModalScanning = false;
            }).catch((err) => {
                console.warn('Stop scanner err:', err);
                isModalScanning = false;
            });
        }
    }

    // Keyboard ESC to close
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            const modal = document.getElementById('qrScanModal');
            if (modal && !modal.classList.contains('hidden')) {
                closeScanModal();
            }
        }
    });
</script>

