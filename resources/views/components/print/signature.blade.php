@props([
    'stamped' => true,
])

{{-- President's signature; for electronically signed documents the stamp is placed on top of the signature --}}
<div class="signature-row">
    <div class="signature-block">
        @if($stamped)
            <img src="{{ asset(config('org.signature')) }}" class="signature-img" alt="Signature">
            <img src="{{ asset(config('org.stamp')) }}" class="signature-stamp" alt="Stamp">
        @else
            <div class="signature-manual-space"></div>
        @endif
        <div class="signature-name">{{ config('org.president_name') }}</div>
        <div class="signature-title">{{ config('org.president_title') }}</div>
    </div>
</div>
