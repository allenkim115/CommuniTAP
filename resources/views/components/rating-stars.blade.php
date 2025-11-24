@props([
    'value' => 0,
    'size' => 'md',
])

@php
    $ratingValue = max(0, min(5, (float) $value));
    $sizeMap = [
        'xs' => 'w-3 h-3',
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-8 h-8',
    ];
    $dimension = $sizeMap[$size] ?? $sizeMap['md'];
    $starMask = "url(\"data:image/svg+xml,%3Csvg%20viewBox%3D%270%200%2020%2020%27%20xmlns%3D%27http%3A//www.w3.org/2000/svg%27%3E%3Cpath%20fill%3D%27white%27%20d%3D%27M9.049%202.927c.3-.921%201.603-.921%201.902%200l1.07%203.292a1%201%200%2000.95.69h3.462c.969%200%201.371%201.24.588%201.81l-2.8%202.034a1%201%200%2000-.364%201.118l1.07%203.292c.3.921-.755%201.688-1.54%201.118l-2.8-2.034a1%201%200%2000-1.175%200l-2.8%202.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1%201%200%2000-.364-1.118L2.98%208.72c-.783-.57-.38-1.81.588-1.81h3.461a1%201%200%2000.951-.69l1.07-3.292z%27/%3E%3C/svg%3E\")";
@endphp

<div {{ $attributes->class(['flex items-center gap-1']) }} role="img" aria-label="{{ number_format($ratingValue, 1) }} out of 5 stars">
    @for ($i = 1; $i <= 5; $i++)
        @php
            $fill = max(0, min(1, $ratingValue - ($i - 1))) * 100;
        @endphp
        <span
            class="inline-block {{ $dimension }}"
            style="
                background: linear-gradient(90deg,#F3A261 0%,#F3A261 {{ $fill }}%,#E5E7EB {{ $fill }}%,#E5E7EB 100%);
                mask: {{ $starMask }} no-repeat center / contain;
                -webkit-mask: {{ $starMask }} no-repeat center / contain;
            "
            aria-hidden="true"
        ></span>
    @endfor

    @if(trim($slot))
        {{ $slot }}
    @endif
</div>

