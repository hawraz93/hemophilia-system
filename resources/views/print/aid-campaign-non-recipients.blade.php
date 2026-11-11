@extends('layouts.print', [
    'title' => 'لیستی ئەوانەی بەشیان نەدرا - ' . $campaign->title,
    'printLabel' => 'چاپکردنی لیست (Print)',
])

@section('content')
    <x-print.letterhead
        title="لیستی ئەو نەخۆشانەی بەشیان نەدرا"
        subtitle="Patients Not Covered by This Aid"
        :number="'CAM-' . $campaign->campaign_date->format('Y') . '-' . str_pad($campaign->id, 4, '0', STR_PAD_LEFT)"
    />

    <div class="meta-grid">
        <div>ناوی هاوکاری: <strong>{{ $campaign->title }}</strong></div>
        <div>سەرچاوە / بەخشەر: <strong>{{ $campaign->source_funder ?: '—' }}</strong></div>
        <div>جۆری هاوکاری: <strong>{{ $campaign->category?->label() }}</strong></div>
        <div>بەرواری گەیشتن بۆ کۆمەڵە: <strong>{{ $campaign->campaign_date->format('Y-m-d') }}</strong></div>
        <div>بڕی هاتوو: <strong>{{ $campaign->max_recipients }}</strong></div>
        <div>دابەشکراو: <strong>{{ $distributed }}</strong></div>
        <div>بەشیان نەدرا: <strong>{{ $patients->count() }}</strong></div>
    </div>

    <p style="font-size: 11px; color: #475569; margin: 6px 0;">
        ڕیزبەندی بەپێی پێشینە: سەرەتا ئەوانەی هەرگیز ئەم جۆرە هاوکارییەیان وەرنەگرتووە، پاشان ئەوانەی ماوەیەکی زیاتر بەسەر دوایین وەرگرتنیاندا تێپەڕیوە.
    </p>

    <table class="print-table">
        <thead>
            <tr>
                <th style="width: 28px; text-align: center;">#</th>
                <th>کۆدی نەخۆش</th>
                <th>ژمارەی ئەندامێتی</th>
                <th>ناوی تەواوی نەخۆش</th>
                <th>مۆبایل</th>
                <th>دوایین وەرگرتنی ئەم جۆرە هاوکارییە</th>
                <th style="width: 90px; text-align: center;">تێبینی</th>
            </tr>
        </thead>
        <tbody>
            @forelse($patients as $p)
                <tr>
                    <td style="text-align: center; font-weight: bold;">{{ $loop->iteration }}</td>
                    <td style="font-family: monospace; font-weight: bold; direction: ltr; text-align: right;">{{ $p->patient_code }}</td>
                    <td style="font-family: monospace;">{{ $p->membership_number ?: '—' }}</td>
                    <td><strong>{{ $p->full_name }}</strong></td>
                    <td style="font-family: monospace; direction: ltr; text-align: right;">{{ $p->phone ?: '—' }}</td>
                    <td style="font-family: monospace;">
                        {{ $p->last_same_category_aid ? \Carbon\Carbon::parse($p->last_same_category_aid)->format('Y-m-d') : 'هەرگیز وەرینەگرتووە' }}
                    </td>
                    <td></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 10px; color: #94a3b8;">هەموو نەخۆشەکان بەشیان وەرگرتووە.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <x-print.signature />
@endsection
