@extends('layouts.print', [
    'title' => 'ڕاپۆرتی دابەشکردنی هاوکاری - ' . $campaign->title,
    'printLabel' => 'چاپکردنی ڕاپۆرت (Print)',
])

@section('content')
    <x-print.letterhead
        title="ڕاپۆرتی دابەشکردنی هاوکاری"
        subtitle="Official Aid Distribution List"
        :number="'CAM-' . $campaign->campaign_date->format('Y') . '-' . str_pad($campaign->id, 4, '0', STR_PAD_LEFT)"
    />

    @php
        $distributed = $campaign->patients->count();
    @endphp

    <div class="meta-grid">
        <div>ناوی هاوکاری: <strong>{{ $campaign->title }}</strong></div>
        <div>سەرچاوە / بەخشەر: <strong>{{ $campaign->source_funder ?: '—' }}</strong></div>
        <div>جۆری هاوکاری: <strong>{{ $campaign->category?->label() }}</strong></div>
        <div>بەرواری گەیشتن بۆ کۆمەڵە: <strong>{{ $campaign->campaign_date->format('Y-m-d') }}</strong></div>
        <div>بەرواری دەستپێکی دابەشکردن: <strong>{{ $campaign->distribution_date?->format('Y-m-d') ?? '—' }}</strong></div>
        <div>بڕ بۆ هەر کەسێک: <strong>{{ number_format($campaign->amount_per_patient) }} IQD</strong></div>
        <div>بڕی هاتوو: <strong>{{ $campaign->max_recipients }}</strong></div>
        <div>دابەشکراو: <strong>{{ $distributed }}</strong></div>
        <div>ماوە لە کۆگا: <strong>{{ max(0, $campaign->max_recipients - $distributed) }}</strong></div>
    </div>

    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 28px; text-align: center;">#</th>
                <th>کۆدی نەخۆش</th>
                <th>ژمارەی ئەندامێتی</th>
                <th>ناوی تەواوی نەخۆش</th>
                <th>جۆری نەخۆشی / گروپی خوێن</th>
                <th>بەرواری وەرگرتن</th>
                <th style="width: 90px; text-align: center;">واژۆی وەرگر</th>
            </tr>
        </thead>
        <tbody>
            @forelse($campaign->patients as $p)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $loop->iteration }}</td>
                    <td style="font-family: monospace; font-weight: bold; direction: ltr; text-align: right;">{{ $p->patient_code }}</td>
                    <td style="font-family: monospace;">{{ $p->membership_number ?: '—' }}</td>
                    <td><strong>{{ $p->full_name }}</strong></td>
                    <td><bdi>{{ $p->hemophilia_type?->label() }}</bdi> / <bdi dir="ltr">{{ $p->blood_group?->value ?? '—' }}</bdi></td>
                    <td style="font-family: monospace;">{{ $p->pivot->received_at ? \Carbon\Carbon::parse($p->pivot->received_at)->format('Y-m-d') : '—' }}</td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 10px; color: #94a3b8;">هیچ نەخۆشێک تۆمار نەکراوە.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <x-print.signature />
@endsection
