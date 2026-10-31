@php use Illuminate\Support\Str; @endphp

<x-app-layout>
    <style>
        /* default */
        #navbar {
            background: transparent;
        }

        .search-box {
            width: 100%;
            background: rgba(248, 250, 252, 0.25);
            border: 1px solid rgba(248, 250, 252, 0.5);
            color: white;
        }

        /* saat scroll */
        #navbar.is-scrolled {
            background: white;
        }

        #navbar.is-scrolled>div {
            padding-top: .5rem;
            padding-bottom: .5rem;
        }

        #navbar.is-scrolled .search-box {
            width: 2.5rem;
            color: black;
            padding: 0;
            justify-content: center;
            background: transparent;
            border: none;
        }

        #navbar.is-scrolled .nav-end {
            gap: .5rem;
        }

        #navbar.is-scrolled .search-text {
            display: none;
        }

        /* add to cart animation */
        /* Fly image */
        .fly-image {
            position: fixed;
            z-index: 9999;
            pointer-events: none;
            transition: all 0.7s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Cart shake */
        @keyframes cart-shake {
            0% {
                transform: translateX(0);
            }

            25% {
                transform: translateX(-4px);
            }

            50% {
                transform: translateX(4px);
            }

            75% {
                transform: translateX(-4px);
            }

            100% {
                transform: translateX(0);
            }
        }

        .cart-shake {
            animation: cart-shake 0.35s ease;
        }
    </style>

    <x-slot name="title">
        Menu
    </x-slot>

    <div class="container bg-slate-100/60">
        {{-- Sticky Toolbar --}}
        <div id="navbar" class="fixed top-0 left-0 right-0 z-20 transition-all duration-300">
            <div class="flex items-center justify-between gap-4 p-4">

                {{-- Table Info --}}
                <div id="navbarTableInfo" class="hidden text-nowrap text pl-2 font-semibold text-slate-800">
                    Meja {{ $tableNumber }}
                </div>

                <div class="flex nav-end items-center justify-end w-full gap-4">
                    {{-- Search --}}
                    <div id="searchBox"
                        class="search-box flex items-center h-10 px-4 gap-2 rounded-full transition-all duration-300">
                        <i class="ri-search-2-line text-xl"></i>
                        <span class="search-text text-sm">Cari menu...</span>
                    </div>

                    {{-- Menu Button --}}
                    <div id="menuButton"
                        class="flex justify-center items-center text-xl w-12 h-10 bg-white rounded-full">
                        <a href="{{ route('customer.profile') }}">
                            <i class="ri-menu-line"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Banner Image --}}
        <div class="w-full h-52 mb-4 overflow-hidden rounded-b-xl">
            <img src="{{ asset('storage/banner-default.jpg') }}"
                class="w-full h-full object-cover brightness-50 grayscale-50" alt="banner-image">
        </div>

        <div class="flex flex-col gap-2 mb-4 mx-4">
            {{-- Outlet Info --}}
            {{-- <div class="flex items-center justify-between bg-white px-4 py-3 border border-slate-200 rounded-2xl">
                <div class="flex flex-col gap-2">
                    <div class="font-semibold">
                        <h1>Kopi AJ - Kab. Bogor Ciampea</h1>
                    </div>
                    <div class="text-sm text-slate-600">
                        <span>Buka hari ini, 00:00 - 23:59</span>
                    </div>
                </div>
                <div>
                    <i class="ri-arrow-right-s-line"></i>
                </div>
            </div> --}}

            {{-- Nomor Meja --}}
            <div id="pageTableInfo"
                class="bg-primary-100 text-center text-slate-700 px-4 py-3 border border-slate-200 rounded-2xl">
                <p>Nomor Meja: {{ $tableNumber }}</p>
            </div>
        </div>

        {{-- Category --}}
        <div id="cat-box" class="sticky top-14 px-4 bg-linear-to-b from-white to-transparent">
            <div class="flex gap-3 overflow-x-auto">
                @foreach ($categories as $category)
                    @if ($category->menus->count())
                        <a href="#category-{{ Str::slug($category->name) }}"
                            data-category="{{ Str::slug($category->name) }}"
                            class="category-btn whitespace-nowrap px-4 py-2 font-medium hover:bg-slate-100 transition">
                            {{ $category->name }}
                        </a>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- List Menu --}}
        <div class="p-4 mb-6">
            @foreach ($categories as $category)
                @if ($category->menus->count())
                    <section id="category-{{ Str::slug($category->name) }}"
                        data-category="{{ Str::slug($category->name) }}" class="scroll-mt-24 mb-8">
                        <div class="flex items-center gap-2 text-slate-800 -mr-2">
                            <h2 class="font-light text-nowrap text-lg py-4">{{ $category->name }}</h2>
                            <span class="h-px bg-slate-300/50 w-full"></span>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                            @foreach ($category->menus as $menu)
                                <div data-menu-id = "{{ $menu->id }}"
                                    class="flex flex-col bg-white rounded-xl border-b-4 border-white shadow-[0px_1px_8px_1px_rgba(0,0,0,0.07)] p-3">

                                    <img src="{{ asset('storage/menus/' . $menu->image) }}"
                                        class="menu-image w-full h-32 object-cover rounded-lg mb-2">

                                    <h3 class="font-semibold leading-tight text-sm mb-auto line-clamp-2">
                                        {{ $menu->name }}
                                    </h3>

                                    <p class="text-sm text-end font-medium text-slate-700 mb-2.5 mt-4">
                                        Rp{{ number_format($menu->price, 0, ',', '.') }}
                                    </p>

                                    {{-- Action Area --}}
                                    <div class="menu-action">
                                        <x-primary-button onclick="addToCart({{ $menu->id }})" class="w-full py-1">
                                            Tambah
                                        </x-primary-button>
                                    </div>

                                    <template id="qtyInput">
                                        <div
                                            class="flex items-center justify-between border border-primary-500 text-primary-500 py-1 rounded-lg text-sm">
                                            <button class="px-3" data-action="decrease">&minus;</button>
                                            <span class="qty text-sm">1</span>
                                            <button class="px-3" data-action="increase">&plus;</button>
                                        </div>
                                    </template>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif
            @endforeach
        </div>

        {{-- Cart Snackbar --}}
        <a href="{{ route('order.index') }}">
            <div id="cartSnackbar"
                class="hidden fixed bottom-6 right-4 left-4 z-50 bg-white shadow-[0px_1px_20px_6px_rgba(0,0,0,0.140)] justify-center items-center rounded-xl overflow-hidden">
                <div class="relative text-4xl w-1/5 h-full flex items-center justify-center">
                    <span
                        class="cart-qty absolute -top-1 right-2 w-5 h-5 ring-3 ring-white text-sm bg-primary-400 rounded-full text-center font-medium text-white">0</span>
                    <i class="cart-icon ri-shopping-basket-2-line text-primary-600"></i>
                </div>
                <div class="bg-primary-600 text-white flex justify-between w-full">
                    <div class="flex flex-col items-start justify-between px-4 py-2 gap-1">
                        <p class="text-sm font-medium">Total</p>
                        <p class="cart-total text-base font-semibold">Rp0</p>
                    </div>
                    <div class="flex items-center justify-end px-4 py-2.5">
                        <P class="font-bold">CHECK OUT <i class="ri-arrow-right-long-line"></i></P>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <footer class="mt-auto py-2">
        <x-footer>
            &copy; {{ date('Y') }} Kopi AJ. All rights reserved.
        </x-footer>
    </footer>

    <script>
        // UX Category
        const sections = document.querySelectorAll("section[data-category]");
        const buttons = document.querySelectorAll(".category-btn");

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const activeCategory = entry.target.dataset.category;

                    buttons.forEach(btn => {
                        btn.classList.remove("border-b-3", "border-primary-500");
                        if (btn.dataset.category === activeCategory) {
                            btn.classList.add("border-b-3", "border-primary-500");
                        }
                    });
                }
            });
        }, {
            rootMargin: "-40% 0px -50% 0px",
            threshold: 0
        });

        sections.forEach(section => observer.observe(section));

        // UX Navbar
        const navbar = document.getElementById('navbar');
        const menuButton = document.getElementById('menuButton');
        const navbarTableInfo = document.getElementById('navbarTableInfo');
        const pageTableInfo = document.getElementById('pageTableInfo');

        window.addEventListener('scroll', () => {
            navbar.classList.toggle('is-scrolled', window.scrollY > 120);
            menuButton.classList.toggle('is-scrolled', window.scrollY > 120);

            const isTableInfoVisible = window.scrollY > 220;
            navbarTableInfo.classList.toggle('hidden', !isTableInfoVisible);
            pageTableInfo.classList.toggle('hidden', isTableInfoVisible);

            // UX Sticky Category
            const categoryBox = document.getElementById('cat-box');
            if (window.scrollY > 270) {
                categoryBox.classList.remove('bg-linear-to-b', 'from-white', 'to-transparent');
                categoryBox.classList.add('bg-white');
            } else {
                categoryBox.classList.remove('bg-white');
                categoryBox.classList.add('bg-linear-to-b', 'from-white', 'to-transparent');
            }
        });
    </script>
</x-app-layout>
