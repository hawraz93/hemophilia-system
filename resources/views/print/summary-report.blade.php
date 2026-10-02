@extends('layouts.print', [
    'title' => 'ڕاپۆرتی گشتی نەخۆش - ' . $patient->full_name,
    'printLabel' => 'پرینتکردنی ڕاپۆرت (Print)',
])

@push('styles')
    .info-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 6px; }
    .info-item { border: 1px solid #e2e8f0; border-radius: 4px; padding: 4px 8px; background: #f8fafc; }
    .info-label { display: block; font-size: 9.5px; font-weight: 700; color: #64748b; }
    .info-value { display: block; font-size: 12px; font-weight: 800; }
@endpush

@section('content')
    <x-print.letterhead title="ڕاپۆرتی پزیشکی و کۆمەکی نەخۆش" subtitle="Patient Medical & Assistance Summary" :number="$patient->patient_code" />

    <div class="section-title">زانیاری کەسی و ناسنامەی نەخۆش</div>
    <div class="info-grid">
        <div class="info-item"><span class="info-label">ناوی تەواو</span><span class="info-value">{{ $patient->full_name }}</span></div>
        <div class="info-item"><span class="info-label">کۆدی هیوا</span><span class="info-value">{{ $patient->hiwa_code ?? '—' }}</span></div>
        <div class="info-item"><span class="info-label">ژمارەی ئەندامێتی</span><span class="info-value">{{ $patient->membership_number ?? '—' }}</span></div>
        <div class="info-item"><span class="info-label">ڕەگەز</span><span class="info-value">{{ $patient->gender?->label() ?? '—' }}</span></div>
        <div class="info-item"><span class="info-label">بەرواری لەدایکبوون</span><span class="info-value">{{ $patient->dob ? $patient->dob->format('Y-m-d') : '—' }} ({{ $patient->age }} ساڵ)</span></div>
        <div class="info-item"><span class="info-label">ژمارەی مۆبایل</span><span class="info-value" dir="ltr" style="text-align: right;">{{ $patient->phone }}</span></div>
        <div class="info-item"><span class="info-label">پارێزگا / قەزا</span><span class="info-value">{{ $patient->governorate ?? '—' }} / {{ $patient->district ?? '—' }}</span></div>
        <div class="info-item"><span class="info-label">دۆخی هاوسەرگیری</span><span class="info-value">{{ $patient->marital_status?->label() ?? '—' }}</span></div>
        <div class="info-item"><span class="info-label">لیستی دۆخی نەخۆش</span><span class="info-value" style="color: #be123c;">{{ $patient->list_status->label() }}</span></div>
    </div>

    <div class="section-title">پرۆفایلی پزیشکی و تاقیکردنەوەکان</div>
    <div class="info-grid">
        <div class="info-item"><span class="info-label">جۆری هیمۆفیلیا</span><span class="info-value">{{ $patient->hemophilia_type?->label() }}</span></div>
        <div class="info-item"><span class="info-label">ئاستی سەختی (Severity)</span><span class="info-value">{{ $patient->severity?->label() }}</span></div>
        <div class="info-item"><span class="info-label">گرووپی خوێن</span><span class="info-value" dir="ltr" style="text-align: right;">{{ $patient->blood_group?->value ?? '—' }}</span></div>
        <div class="info-item"><span class="info-label">Hepatitis B</span><span class="info-value">{{ $patient->hepatitis_b?->label() ?? '—' }}</span></div>
        <div class="info-item"><span class="info-label">Hepatitis C</span><span class="info-value">{{ $patient->hepatitis_c?->label() ?? '—' }}</span></div>
        <div class="info-item"><span class="info-label">HIV</span><span class="info-value">{{ $patient->hiv?->label() ?? '—' }}</span></div>
    </div>

    @if($patient->medicalLogs->isNotEmpty())
        <div class="section-title">تۆماری سەردانی پزیشکی و فاکتەرەکان</div>
        <table class="print-table">
            <thead>
                <tr>
                    <th>بەروار</th>
                    <th>جۆری سەردان / ڕووداو</th>
                    <th>نەخۆشخانە</th>
                    <th>Factor & Dose</th>
                    <th>وردەکاری</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patient->medicalLogs->sortByDesc('log_date') as $log)
                    <tr>
                        <td>{{ $log->log_date->format('Y-m-d') }}</td>
                        <td><strong>{{ $log->log_type->label() }}</strong></td>
                        <td>{{ $log->hospital_name ?? '—' }}</td>
                        <td>{{ $log->factor_name_dose ?? '—' }}</td>
                        <td>{{ $log->details ?? '—' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($patient->assistances->isNotEmpty())
        <div class="section-title">مێژووی هاوکاری و یارمەتییەکان</div>
        <table class="print-table">
            <thead>
                <tr>
                    <th>کۆد</th>
                    <th>بەروار</th>
                    <th>جۆری هاوکاری</th>
                    <th>سەرچاوە / دابینکەر</th>
                    <th>سەرچاوەی پارە</th>
                    <th>بڕی پارە (IQD)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patient->assistances->sortByDesc('assistance_date') as $aid)
                    <tr>
                        <td style="font-family: monospace; font-weight: bold;">{{ $aid->assistance_number }}</td>
                        <td>{{ $aid->assistance_date->format('Y-m-d') }}</td>
                        <td>{{ $aid->category->label() }}</td>
                        <td>{{ $aid->source_funder ?: '—' }}</td>
                        <td>{{ $aid->funding_source?->shortLabel() ?? '—' }}</td>
                        <td style="font-weight: bold; color: #059669;">{{ number_format($aid->amount) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <x-print.signature />
@endsection
