@props(['breadcrumbs' => []])

<ul class="flex items-center ml-4 text-sm dark:text-gray-100">
    @foreach ($breadcrumbs as $index => $breadcrumb)
        @if (isset($breadcrumb['url']))
            <li class="mr-2">
                <a href="{{ $breadcrumb['url'] }}"
                    class="font-medium text-gray-400 hover:text-gray-600 after:content-['/'] after:ml-1 after:text-gray-300 dark:hover:text-gray-200">
                    {{ $breadcrumb['label'] }}
                </a>
            </li>
        @else
            <li aria-current="page" class="mr-2 font-medium text-gray-600 dark:text-gray-400">
                {{ $breadcrumb['label'] }}</li>
        @endif
    @endforeach
</ul>
