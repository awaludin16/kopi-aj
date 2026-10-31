<x-app-layout>
    <x-slot name="title">Payment</x-slot>

    <x-slot name="toolBar">
        <div class="flex items-center px-4 py-2 transition-all duration-300">
            <a href="{{ route('order.index') }}"
                class="inline-flex justify-center items-center text-2xl w-10 h-10 rounded-full hover:bg-slate-100 active:bg-slate-200">
                <i class="ri-arrow-left-long-line"></i>
            </a>
            <header class="w-[calc(100%-5rem)] text-center">
                <h1 class="text-base font-semibold text-slate-800 sm:text-3xl">Pembayaran</h1>
            </header>
        </div>
    </x-slot>

    <div class="container bg-slate-100/75 pt-14 pb-28 min-h-screen">
        <form action="{{ route('payment.store') }}" method="POST">
            @csrf
            <div class="px-4 py-4 pb-6 bg-white">
                <div class="pb-2">
                    <h1 class="text-[1.1rem] font-semibold">Informasi Pemesan</h1>
                </div>
                <div class="flex flex-col items-center justify-center gap-6">
                    <div class="w-full">
                        <x-input-label for="nomor_meja" class="after:content-['*'] after:text-rose-400 after:-ml-1"
                            :value="__('Nomor Meja')" />
                        <x-text-input id="nomor_meja" class="mt-1 block @error('nomor_meja') is-invalid @enderror"
                            readonly name="nomor_meja" type="text" value="{{ $tableNumber }}" :disabled="true" />
                        @error('nomor_meja')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <input type="hidden" name="nomor_meja" value="{{ $tableNumber }}">
                    </div>
                    <div class="w-full">
                        <x-input-label for="nama_lengkap" class="after:content-['*'] after:text-rose-400 after:-ml-1"
                            :value="__('Nama Lengkap')" />
                        <x-text-input id="nama_lengkap" name="nama_lengkap" type="text" placeholder="Nama Lengkap"
                            value="{{ old('nama_lengkap') }}"
                            class="mt-1 block @error('nama_lengkap') is-invalid @enderror" />
                        @error('nama_lengkap')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full">
                        <x-input-label for="no_hp" :value="__('No. HP/Whatsapp (info promo)')" />
                        <x-text-input id="no_hp" name="no_hp" type="text" placeholder="No. HP/Whatsapp"
                            value="{{ old('no_hp') }}" class="mt-1 block @error('no_hp') is-invalid @enderror" />
                        @error('no_hp')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="w-full">
                        <x-input-label for="email" :value="__('Kirim struk ke email')" />
                        <x-text-input id="email" name="email" type="email" placeholder="Email"
                            value="{{ old('email') }}" class="mt-1 block @error('email') is-invalid @enderror" />
                        @error('email')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="border-none my-2">

            <div class="flex flex-col px-4 py-4 bg-white">
                <div class="pb-2">
                    <h1 class="text-[1.1rem] font-semibold">Metode Pembayaran</h1>
                </div>
                @error('payment_method')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <div class="flex flex-col gap-3">
                    <label for="qris" class="flex items-center justify-between">
                        <div class="flex flex-row-reverse items-center">
                            <div class="py-4 ml-6 text-slate-700">
                                <h3 class="font-medium">Pembayaran Online</h3>
                                <p class="mb-1 text-sm text-slate-500">QRIS/E-Wallet/MBanking/dll</p>
                            </div>
                            <img alt="Online payment Flaticons Flat icon" loading="lazy" decoding="async" data-nimg="1"
                                class="_e0usu51 size-14" style="color:transparent"
                                src="https://cdn-icons-png.freepik.com/512/10551/10551890.png?uid=R151018612&amp;ga=GA1.1.178936179.1717067031">
                        </div>
                        <input id="qris" type="radio" name="payment_method" value="pembayaran_online"
                            class="size-4" checked required>
                    </label>
                    <label for="cash" class="flex items-center justify-between">
                        <div class="flex flex-row-reverse items-center">
                            <div class="py-4 ml-6 text-slate-700">
                                <h3 class="font-medium">Cash</h3>
                                <p class="mb-1 text-sm text-slate-500">Bayar di Kasir</p>
                            </div>
                            <img alt="Money Special Flat icon" loading="lazy" decoding="async" data-nimg="1"
                                class="_e0usu51 size-14"
                                src="https://cdn-icons-png.freepik.com/512/998/998662.png?uid=R151018612&amp;ga=GA1.1.178936179.1717067031"
                                style="color: transparent;">
                        </div>
                        <input id="cash" type="radio" name="payment_method" value="cash" class="size-4"
                            required>
                    </label>
                </div>
            </div>

            <div id="fixed-button-box"
                class="fixed-button-box fixed bottom-0 right-0 left-0 w-full rounded-t-2xl p-6 bg-white shadow-[0px_-1px_20px_3px_rgba(0,0,0,0.1)]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-700">Total Pembayaran</p>
                        <h1 class="total text-xl font-bold">Rp{{ number_format($total, 0, ',', '.') }}</h1>
                    </div>
                    <x-primary-button id="payButton" type="submit"
                        class="w-1/2 text-lg font-medium py-3 px-4 cursor-pointer">
                    </x-primary-button>
                </div>
            </div>
        </form>
    </div>
</x-app-layout>
