@props([
    'number' => null,
    'date' => null,
    'title' => null,
    'subtitle' => null,
])

{{-- Official letterhead: logo + organisation name, with an optional report title and reference number/date --}}
<div class="letterhead">
    <div class="letterhead-org">
        <img src="{{ asset(config('org.logo')) }}" class="letterhead-logo" alt="Logo">
        <div>
            <h1>{{ config('org.name') }}</h1>
            <h2>{{ config('org.branch') }}</h2>
            <h3>{{ config('org.name_en') }}</h3>
        </div>
    </div>

    @if($title)
        <div class="letterhead-title">
            <h1>{{ $title }}</h1>
            @if($subtitle)
                <h2>{{ $subtitle }}</h2>
            @endif
        </div>
    @endif

    <div class="letterhead-meta">
        @if($number)
            ژمارە: <strong class="ref">{{ $number }}</strong><br>
        @endif
        ڕێکەوت: <strong>{{ $date ?? now()->format('Y / m / d') }}</strong>
        {{ $slot }}
    </div>
</div>
