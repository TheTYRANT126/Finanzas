@props(['title', 'description' => null, 'icon' => '◌'])
<div {{ $attributes->class('empty-state') }}>
    <span class="empty-icon" aria-hidden="true">{{ $icon }}</span>
    <strong>{{ $title }}</strong>
    @if($description)<p>{{ $description }}</p>@endif
    @if($slot->isNotEmpty()){{ $slot }}@endif
</div>
