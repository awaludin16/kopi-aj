<x-app-layout>
    <x-slot name="title">Order</x-slot>

    <x-slot name="toolBar">
        <div class="flex items-center px-4 py-2 transition-all duration-300">
            <a href="{{ route('customer.menu') }}"
                class="inline-flex justify-center items-center text-2xl w-10 h-10 rounded-full hover:bg-slate-100 active:bg-slate-200">
                <i class="ri-arrow-left-long-line"></i>
            </a>
            <header class="w-[calc(100%-5rem)] text-center">
                <h1 class="text-base font-semibold text-slate-800 sm:text-3xl">Pesanan</h1>
            </header>
        </div>
    </x-slot>

    <div class="container mt-14 pb-30">
        <div id="data-order" class="items-center">

            <div class="menu-heading flex justify-between px-4 pt-6">
                <h2 class="text-[1.1rem] font-semibold">Item yang dipesan <span
                        class="total-item">({{ $order->items->count() }})</span></h2>
                <x-primary-button as="a" outline="true" href="{{ route('customer.menu') }}"
                    class="text-[.8rem] font-medium py-1 px-4">
                    + Tambah item
                </x-primary-button>
            </div>
            <hr class="m-4 mx-2 text-slate-300">
            @foreach ($order->items as $item)
                @php
                    $isLast = $loop->last;
                    $qty = $item->qty;
                    $subTotal = $item->menu->price * $qty;
                @endphp
                <div class="px-4">
                    <div data-order-item="{{ $item->menu_id }}" class="">
                        <div class="flex gap-3 py-2">
                            <div
                                class="flex items-center justify-center overflow-hidden bg-gray-100 rounded-lg max-w-18 aspect-square">
                                <img src="{{ asset('storage/menus/' . $item->menu->image) }}" alt=""
                                    class="object-cover object-center w-full h-full">
                            </div>
                            <div class="flex flex-col justify-center gap-3 w-full">
                                <div class="flex items-center justify-between">
                                    <h1 class="text-sm font-semibold max-w-60 text-wrap">
                                        {{ strtoupper($item->menu->name) }}
                                    </h1>
                                    {{-- REMOVE --}}
                                    <button data-action="remove" data-id="{{ $item->menu_id }}" type="button"
                                        class="flex justify-end ml-auto text-gray-600 transition cart-btn hover:text-red-600"
                                        data-action="remove" data-id="">
                                        <span class="sr-only">Remove item</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-[1.2rem]">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                                <div class="flex items-center justify-between">
                                    {{-- QTY INPUT --}}
                                    <div class="flex items-center text-center">
                                        <button data-action="decrease" data-id="{{ $item->menu_id }}"
                                            class="leading-0 font-medium w-6 h-6 rounded-full border-2 border-slate-800">&minus;</button>
                                        <span class="qty w-10 font-medium">{{ $item->qty }}</span>
                                        <button data-action="increase" data-id="{{ $item->menu_id }}"
                                            class="leading-0 font-medium w-6 h-6 rounded-full border-2 border-slate-800">&plus;</button>
                                    </div>
                                    <span
                                        class="subtotal-item text-[.9rem] text-slate-600 font-bold">Rp{{ number_format($subTotal, 0, ',', '.') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        <hr class="my-4 text-slate-300">
                    </div>
                </div>
            @endforeach

            <div class="flex flex-col mb-4 px-4">
                <label class="font-medium" for="note">Catatan (opsional)</label>
                <textarea id="note" name="note"
                    class="w-full mt-2 p-2 text-sm text-slate-700 bg-gray-50/75 border border-slate-300 rounded-lg resize-none order-note focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 inset-shadow-sm"
                    rows="3" placeholder="Tambahkan catatan disini..." data-order-id="{{ $order->id }}">{{ $order->note }}</textarea>
            </div>

            <div id="payment-details" class="payment-details mx-4 p-3 py-4 border border-slate-400 rounded-2xl">
                <h2 class="font-semibold text-center pb-3">Rincian Pembayaran</h2>
                <div class="flex flex-col w-full font-medium">
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-200">
                        <p>Subtotal <span class="total-item text-slate-400">({{ $order->items->count() }} menu)</span>
                        </p>
                        <p class="subtotal">Rp{{ number_format($order->subtotal, 0, ',', '.') }}</p>
                    </div>
                    <div class="py-2.5 border-b border-slate-200">
                        <div x-data="{ open: false }">
                            <button x-on:click="open = !open" type="button" class="w-full">
                                <div class="flex items-center justify-between">
                                    <span class="flex items-center gap-1">
                                        Biaya lainnya
                                        <i class="ri-arrow-down-s-fill transition-transform duration-300"
                                            :class="{ 'rotate-180': open }"></i>
                                    </span>
                                    <span class="other-costs">Rp{{ number_format($otherCosts, 0, ',', '.') }}</span>
                                </div>
                            </button>
                            <div x-show="open" x-cloak x-transition.duration.300>
                                <div class="flex flex-col gap-1 pl-4 pt-2">
                                    <div class="flex items-center justify-between text-slate-400 text-[.9rem]">
                                        <p>PB1</p>
                                        <p class="pb1">Rp{{ number_format($pb1, 0, ',', '.') }}</p>
                                    </div>
                                    @isset($serviceCharge)
                                        <div class="flex items-center justify-between text-slate-400 text-[.9rem]">
                                            <p>Service charge</p>
                                            <p class="service-charge">Rp{{ number_format($serviceCharge, 0, ',', '.') }}
                                            </p>
                                        </div>
                                    @endisset
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-slate-200">
                        <p>Pembulatan</p>
                        <p class="rounding">&minus;Rp{{ number_format($rounding, 0, ',', '.') }}</p>
                    </div>
                    <div class="flex items-center justify-between font-semibold mt-2 pt-2">
                        <p>Total</p>
                        <p class="total text-primary-500 font-bold">
                            Rp{{ number_format($total, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </div>

            <div id="fixed-button-box"
                class="fixed-button-box fixed bottom-0 right-0 left-0 w-full rounded-t-2xl p-4 bg-white shadow-[0px_-1px_20px_3px_rgba(0,0,0,0.1)]">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-slate-700">Total Pembayaran</p>
                        <h1 class="total text-xl font-bold">Rp{{ number_format($total, 0, ',', '.') }}</h1>
                    </div>
                    <x-primary-button as="a" href="{{ route('payment.index') }}"
                        class="text-base font-medium py-3 px-4">
                        Lanjut Pembayaran
                    </x-primary-button>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
