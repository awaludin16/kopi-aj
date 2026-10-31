<x-app-layout>
    <x-slot name="title">Profile</x-slot>

    <x-slot name="toolBar">
        <div class="flex items-center px-4 py-2 transition-all duration-300">
            <a href="{{ route('customer.menu') }}"
                class="inline-flex justify-center items-center text-2xl w-10 h-10 rounded-full hover:bg-slate-100 active:bg-slate-200">
                <i class="ri-arrow-left-long-line"></i>
            </a>
            <header class="w-[calc(100%-5rem)] text-center">
                <h1 class="text-base font-semibold text-slate-800 sm:text-3xl">Profile</h1>
            </header>
        </div>
    </x-slot>

    <div class="container flex flex-col items-center gap-2 mt-14">
        {{-- Profile --}}
        <div class="flex p-4 gap-4 w-full">
            {{-- Avatar --}}
            <div class="text-7xl h-max text-slate-300">
                <i class="ri-account-circle-fill"></i>
            </div>
            {{-- Login Button --}}
            <div class="flex w-full flex-col gap-2">
                <h2 class="text-base text-slate-800 font-medium">Masuk sebagai tamu</h2>
                <button class="py-1.5 bg-primary-500 text-white font-medium text-sm rounded-lg">Masuk</button>
            </div>
        </div>
        <div class="flex flex-col w-full gap-4 px-4">
            <div
                class="flex items-center gap-2.5 px-4 py-2.5 text-slate-500 text-2xl bg-white border border-slate-300/65 shadow-[0px_2px_8px_rgba(0,0,0,0.07)] rounded-lg">
                <i class="ri-file-list-3-line"></i>
                <span class="text-slate-700 text-sm font-medium">Riwayat Pesanan</span>
            </div>
            <div
                class="flex items-center justify-between px-4 py-2.5 text-slate-500 text-2xl bg-white border border-slate-300/65 shadow-[0px_2px_8px_rgba(0,0,0,0.07)] rounded-lg">
                <div class="flex items-center gap-2.5">
                    <i class="ri-global-line"></i>
                    <span class="text-slate-700 text-sm font-medium">Bahasa</span>
                </div>
                <i class="ri-arrow-right-s-line self-end"></i>
            </div>
            {{-- <div
                class="flex items-center gap-2 px-4 py-2.5 text-slate-500 text-2xl bg-white border border-slate-300/65 shadow-[0px_2px_8px_rgba(0,0,0,0.07)] rounded-lg">
                <i class="ri-file-list-3-line"></i>
                <span class="text-slate-700 text-sm font-medium">Riwayat Pesanan</span>
            </div> --}}
        </div>
    </div>
</x-app-layout>
