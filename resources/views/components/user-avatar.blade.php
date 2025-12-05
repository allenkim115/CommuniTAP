@props([
    'user' => null,
    'size' => 'h-10 w-10',
    'textSize' => 'text-sm',
])

@php
    $profilePictureUrl = $user?->profile_picture_url;
    $initials = $user?->initials ?? ($user?->name ? strtoupper(substr($user->name, 0, 2)) : 'NA');
@endphp

<div {{ $attributes->class([
        'rounded-full flex items-center justify-center overflow-hidden font-semibold ring-1 ring-gray-200',
        $size,
        $textSize,
        'bg-brand-teal/15 text-brand-teal-dark' => !$profilePictureUrl,
        'bg-gray-50 text-white' => $profilePictureUrl,
    ]) }}>
    @if($profilePictureUrl)
        <img src="{{ $profilePictureUrl }}" alt="{{ $user?->name ?? 'User' }} avatar" class="w-full h-full object-cover">
    @else
        <span>{{ $initials }}</span>
    @endif
</div>

