@props(['disabled' => false])

@if ($disabled)
    <input @disabled($disabled)
        {{ $attributes->merge(['class' => 'border bg-gray-100/50 border-gray-300 rounded-xl transition py-2.5 px-4 block w-full text-base text-slate-400 placeholder:text-sm placeholder:font-medium placeholder:text-slate-400 focus:ring focus:ring-primary-500 focus:outline focus:outline-primary-500']) }}>
@else
    <input @disabled($disabled)
        {{ $attributes->merge(['class' => 'border bg-slate-50/25 border-slate-200 rounded-xl transition py-2.5 px-4 block w-full text-base text-slate-700 placeholder:text-sm placeholder:text-slate-400 focus:ring focus:ring-primary-500 focus:outline focus:outline-primary-500']) }}>
@endif
