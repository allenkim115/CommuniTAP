@props([
    'eyebrow' => null,
    'title',
    'description' => null,
    'actions' => null,
])

@php
    $actionsContent = $actions ?? null;
@endphp

<div {{ $attributes->class('flex flex-col gap-4 md:flex-row md:items-center md:justify-between') }}>
    <div>
        @if($eyebrow)
            <p class="section-heading">{{ $eyebrow }}</p>
        @endif
        <h2 class="text-3xl font-semibold text-gray-900 leading-tight">
            {{ $title }}
        </h2>
        @if($description)
            <p class="mt-1 text-sm text-gray-500">
                {{ $description }}
            </p>
        @endif
    </div>

    @if($actionsContent || $slot->isNotEmpty())
        <div class="flex flex-wrap gap-3">
            {{ $actionsContent ?? $slot }}
        </div>
    @endif
</div>

