@props([
    'to' => 'لایەنی پەیوەندیدار',
    'subject' => 'داواکاری',
])

{{-- Recipient and subject are always printed on two separate lines --}}
<div class="addressee">
    <div><span class="label">بۆ بەڕێز :</span> {{ $to }}</div>
    <div><span class="label">بابەت :</span> {{ $subject }}</div>
</div>
