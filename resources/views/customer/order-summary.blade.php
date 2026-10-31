<x-app-layout>
    <x-slot name="title">Order Summary</x-slot>

    <x-slot name="toolBar">
        <div class="flex items-center px-4 py-2 transition-all duration-300">
            <a href="{{ url()->previous() }}"
                class="inline-flex justify-center items-center text-2xl w-10 h-10 rounded-full hover:bg-slate-100 active:bg-slate-200">
                <i class="ri-arrow-left-long-line"></i>
            </a>
            <header class="w-[calc(100%-5rem)] text-center">
                <h1 class="text-base font-semibold text-slate-800 sm:text-3xl">Ringkasan Pesanan</h1>
            </header>
        </div>
    </x-slot>

    <div class="container pt-14">
        <div class="py-2.5 px-4 flex items-center text-sm justify-between bg-gray-50">
            <div class="flex flex-col">
                <p>Tanggal,</p>
                <p>{{ $order->updated_at->format('d F Y, H:i') }}</p>
            </div>
            <div class="flex flex-col items-end">
                <p class="mb-1">Nomor Pesanan</p>
                <span class="inline-flex items-center gap-1 text-center">
                    <i class="ri-file-copy-line text-primary-500"></i>
                    <p class="font-semibold">{{ $order->order_number }}</p>
                </span>
            </div>
        </div>

        <div class="p-4">
            <div class="flex gap-2 text-sm pb-4">
                <i class="ri-user-5-line text-primary-500 text-2xl"></i>
                <div class="flex flex-col">
                    <p class="text-slate-500">Nama Pemesan</p>
                    <p class="font-semibold">{{ $order->user->name }}</p>
                </div>
            </div>
            <div class="flex gap-2 text-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="text-primary-500 fill-current" height="26px"
                    viewBox="0 -960 960 960" width="26px" fill="#1f1f1f">
                    <path
                        d="m240-160 60-150q9-23 29-36.5t45-13.5h66v-161q-153-5-256.5-45T80-660q0-58 117-99t283-41q167 0 283.5 41T880-660q0 54-103.5 94T520-521v161h66q24 0 44.5 13.5T660-310l60 150h-80l-48-120H368l-48 120h-80Zm240-440q97 0 183-17t126-43q-40-26-126-43t-183-17q-97 0-183 17t-126 43q40 26 126 43t183 17Zm0-60Z" />
                </svg>
                <div class="flex flex-col">
                    <p class="text-slate-500">Nomor Meja</p>
                    <p class="font-semibold">{{ $order->table->table_number }}</p>
                </div>
            </div>
        </div>

        <hr class="border-slate-200">

        <div class="p-4">
            <div class="flex justify-between items-center pb-4">
                <h2 class="font-semibold">Item yang dipesan</h2>
                <x-primary-button as="a" outline="true" href="{{ route('customer.menu') }}"
                    class="font-medium py-px px-2 text-[.8rem]">
                    pesan lagi
                </x-primary-button>
            </div>
            @foreach ($order->items as $item)
                @php
                    $isLast = $loop->last;
                    $qty = $item->qty;
                    $subTotal = $item->menu->price * $qty;
                @endphp
                <div class="flex flex-col justify-between {{ $isLast ? 'pb-0' : 'pb-6' }}">
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center gap-2 max-w-60">
                            <h5 class="font-semibold">{{ $item->qty }}x</h5>
                            <p class="font-normal">
                                {{ strtoupper($item->menu->name) }}</p>
                        </div>
                        <div>Rp {{ number_format($subTotal, 0, ',', '.') }}</div>
                    </div>
                </div>
            @endforeach
        </div>

        <hr class="border-slate-200" />

        <div class="p-4">
            <h2 class="font-semibold text-center pb-4">Rincian Pembayaran</h2>
            <div class="flex flex-col w-full gap-2 text-sm">
                <div class="flex items-center justify-between">
                    <p>Subtotal <span class="total-item text-slate-400">({{ $order->items->count() }} menu)</span>
                    </p>
                    <p class="subtotal">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</p>
                </div>
                <div x-data="{ open: false }">
                    <button x-on:click="open = !open" type="button" class="w-full">
                        <div class="flex items-center justify-between">
                            <span class="flex items-center gap-1">
                                Biaya lainnya
                                <i class="ri-arrow-down-s-fill transition-transform duration-300"
                                    :class="{ 'rotate-180': open }"></i>
                            </span>
                            <span
                                class="other-costs">Rp{{ number_format($order->pb1 + $order->service_charge, 0, ',', '.') }}</span>
                        </div>
                    </button>
                    <div x-show="open" x-cloak x-transition.duration.300>
                        <div class="flex flex-col gap-1 pl-4">
                            <div class="flex items-center justify-between text-slate-400 text-[.9rem] pt-2 pb-1">
                                <p>PB1</p>
                                <p class="pb1">Rp{{ number_format($order->pb1, 0, ',', '.') }}</p>
                            </div>
                            @if ($order->service_charge > 0)
                                <div class="flex items-center justify-between text-slate-400 text-[.9rem]">
                                    <p>Service charge</p>
                                    <p class="service-charge">
                                        Rp{{ number_format($order->service_charge, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center justify-between">
                    <p>Pembulatan</p>
                    <p class="rounding">&minus;Rp{{ number_format($order->rounding, 0, ',', '.') }}</p>
                </div>
                <div class="flex items-center justify-between pt-1">
                    <p>Metode Pembayaran</p>
                    <p class="font-semibold">{{ $order->payment->payment_method }}</p>
                </div>
                <div class="flex items-center justify-between font-semibold pt-2">
                    <p>Total</p>
                    <p class="total text-primary-500 font-bold">
                        Rp{{ number_format($order->total, 0, ',', '.') }}
                    </p>
                </div>
            </div>
        </div>
        <hr class="border-slate-200" />
    </div>
</x-app-layout>
