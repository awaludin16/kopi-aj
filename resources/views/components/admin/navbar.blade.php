@props(['breadcrumbs' => []])

<!-- start: Navbar -->
<div
    class="sticky top-0 left-0 z-30 flex items-center px-6 py-2 bg-white shadow dark:bg-gray-900 dark:text-white shadow-black/5">
    <button type="button" class="text-lg text-gray-600 sidebar-toggle">
        <i class="ri-menu-line"></i>
    </button>

    {{-- Breadcrumb --}}
    <ul class="flex items-center ml-4 text-sm">
        @foreach ($breadcrumbs as $index => $breadcrumb)
            @if (isset($breadcrumb['url']))
                <li class="mr-2">
                    <a href="{{ $breadcrumb['url'] }}" class="font-medium text-gray-400 hover:text-gray-600">
                        {{ $breadcrumb['label'] }}
                    </a>
                </li>
            @else
                <li aria-current="page" class="mr-2 font-medium text-gray-600">
                    {{ $breadcrumb['label'] }}</li>
            @endif
        @endforeach
    </ul>

    <ul class="flex items-center p-2 ml-auto">
        <button id="toggleDarkMode"
            class="flex items-center justify-center w-8 h-8 text-gray-400 rounded dark:text-gray-50 dark:hover:bg-gray-700 hover:bg-gray-50 hover:text-gray-600">
            <i id="themeIcon" class="ri-moon-line"></i>
        </button>
        <li class="ml-3 dropdown">
            <button type="button" class="flex items-center dropdown-toggle">
                <img src="https://placehold.co/32x32" alt=""
                    class="block object-cover w-8 h-8 align-middle rounded">
            </button>
            <ul
                class="dropdown-menu shadow-md shadow-black/5 z-30 hidden py-1.5 rounded-md bg-white border border-gray-100 w-full max-w-35">
                <li class="flex">
                    <x-dropdown-link :href="">
                        {{ __('Profile') }}
                    </x-dropdown-link>
                </li>
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <x-dropdown-link :href="route('logout')"
                            onclick="event.preventDefault();
                                                this.closest('form').submit();">
                            {{ __('Logout') }}
                        </x-dropdown-link>
                    </form>
                </li>
            </ul>
        </li>
    </ul>
</div>
<!-- end: Navbar -->
