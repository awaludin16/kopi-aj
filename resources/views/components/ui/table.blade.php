<div class="overflow-hidden border border-gray-200 rounded-lg shadow-md">

    {{-- HEADER (optional) --}}
    @isset($header)
        <div class="px-6 py-3 bg-white">
            {{ $header }}
        </div>
    @endisset

    {{-- TABLE --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left text-gray-500 bg-white border-collapse">
            @isset($thead)
                <thead class="bg-gray-50">
                    {{ $thead }}
                </thead>
            @endisset

            <tbody class="divide-y divide-gray-100">
                {{ $slot }}
            </tbody>
        </table>
    </div>
</div>

{{-- PAGINATION (optional) --}}
@isset($pagination)
    <div class="mt-4">
        {{ $pagination }}
    </div>
@endisset
