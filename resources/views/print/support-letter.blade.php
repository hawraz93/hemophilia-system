@extends('layouts.print', [
    'title' => 'نوسراوی پشتگیری - ' . $patient->full_name,
    'printLabel' => 'چاپکردنی نوسراوی پشتگیری (Print)',
])

@push('styles')
    .patient-box {
        border: 1px solid #cbd5e1;
        border-right: 4px solid #b91c1c;
        border-radius: 5px;
        padding: 6px 14px;
        margin: 8px 0 10px;
        font-size: 13px;
        line-height: 2;
    }
    .patient-box .row { display: block; }
@endpush

@section('content')
    <x-print.letterhead :number="$ref_no" />

    <x-print.addressee :to="$recipient" :subject="$subject" />

    <div class="letter-text no-pre">
        سڵاو و ڕێز ...<br>
        ئاماژە بە تۆمارە فەرمییەکانی ({{ config('org.name') }} / {{ config('org.branch') }})، پشتگیری دەکەین کە هاووڵاتی ئاماژەپێکراوی خوارەوە ئەندامی بەردەوام و تۆمارکراوی کۆمەڵەکەمانە و نەخۆشی خوێنبەربوونی بۆماوەیی هەیە و ئەم زانیاریانەی خوارەوە لە تۆمارەکانی کۆمەڵەدا هەیە:

        <div class="patient-box">
            <span class="row">• <strong>ناوی تەواو:</strong> {{ $patient->full_name }}</span>
            <span class="row">• <strong>کۆدی نەخۆش:</strong> {{ $patient->patient_code }}</span>
            <span class="row">• <strong>ژمارەی ئەندامێتی:</strong> {{ $patient->membership_number ?: '—' }}</span>
            <span class="row">• <strong>پلەی ئەندام:</strong> {{ $patient->membership_type?->label() ?? 'ئەندامی ئاسایی' }}</span>
            <span class="row">• <strong>جۆری نەخۆشی:</strong> {{ $patient->hemophilia_type?->label() }}@if($patient->severity) (پلەی {{ $patient->severity->label() }})@endif</span>
            <span class="row">• <strong>گروپی خوێن:</strong> {{ $patient->blood_group?->value ?? '—' }}</span>
            <span class="row">• <strong>کۆدی نەخۆشخانەی هیوا:</strong> {{ $patient->hiwa_code ?: '—' }}</span>
            <span class="row">• <strong>ژمارەی ناسنامە:</strong> {{ $patient->national_id ?: '—' }}</span>
            <span class="row">• <strong>ژمارەی مۆبایل:</strong> {{ $patient->phone ?: '—' }}</span>
            <span class="row">• <strong>تێبینی پزیشک:</strong> {{ $patient->medical_notes ?: '—' }}</span>
            <span class="row">• <strong>کێشەی جەستە یان خاوەنپێداویستی تایبەت:</strong> {{ $patient->disability_special_needs ?: '—' }}</span>
            <span class="row">• <strong>نەخۆشییە هاوشێوەکان:</strong> {{ $patient->comorbidities ?: '—' }}</span>
        </div>

        بەپێی یاسا و ڕێنماییە کارپێکراوەکانی حکومەتی هەرێمی کوردستان ئەم کەسە خاوەنپێداویستی تایبەتە، تکایە هاوکاری و ئاسانکاری پێویستی بۆ بکەن بۆ ڕاییکردنی مامەڵەکانی، ئەم پشتگیرییەی لەسەر داوای خۆی بۆکراوە.
        <br><br>
        لەگەڵ ڕێزدا ...
    </div>

    <x-print.signature />
@endsection
