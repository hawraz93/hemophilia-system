@php
    // "card" = dedicated ID card printer (one CR80 card per page), otherwise both sides on an A4 sheet
    $cardPrinter = request('printer') === 'card';
@endphp
<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ناسنامەی ئەندام - {{ $patient->full_name }}</title>
    <link rel="stylesheet" href="{{ asset('fonts/vazirmatn.css') }}">
    <style>
        @if($cardPrinter)
            @page { size: 85.6mm 54mm; margin: 0; }
        @else
            @page { size: A4 portrait; margin: 15mm; }
        @endif

        *, *::before, *::after { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 30px 15px;
            background: #e2e8f0;
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .toolbar { display: flex; justify-content: center; gap: 10px; margin-bottom: 20px; flex-wrap: wrap; }
        .toolbar button, .toolbar a {
            font-family: inherit;
            font-weight: 800;
            font-size: 13px;
            padding: 9px 18px;
            border-radius: 10px;
            border: 1px solid #cbd5e1;
            background: #fff;
            color: #0f172a;
            cursor: pointer;
            text-decoration: none;
        }
        .toolbar .primary { background: #b91c1c; color: #fff; border-color: #b91c1c; }
        .toolbar .active { outline: 2px solid #b91c1c; }

        .cards { display: flex; flex-direction: column; align-items: center; gap: 10mm; }

        /* Standard CR80 card size */
        .card {
            position: relative;
            width: 85.6mm;
            height: 54mm;
            overflow: hidden;
            background-size: 100% 100%;
            background-repeat: no-repeat;
            border-radius: 3mm;
            box-shadow: 0 8px 24px rgba(0,0,0,0.18);
            color: #111827;
        }
        .card-front { background-image: url('{{ asset('images/id-card/front.jpg') }}'); }
        .card-back { background-image: url('{{ asset('images/id-card/back.jpg') }}'); }

        /* Values sit to the left of the labels printed in the template */
        .val {
            position: absolute;
            transform: translateY(-50%);
            font-weight: 800;
            line-height: 1.2;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: right;
        }
        .front .val { font-size: 2.75mm; left: 27%; }
        .back .val { font-size: 2.6mm; left: 23%; }
        .ltr { direction: ltr; unicode-bidi: isolate; display: inline-block; }

        .photo {
            position: absolute;
            left: 9.1%;
            top: 26%;
            width: 15.2%;
            height: 32%;
            border-radius: 50%;
            border: 0.5mm solid #b91c1c;
            overflow: hidden;
            background: #f1f5f9;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 6mm;
            font-weight: 900;
            color: #94a3b8;
        }
        .photo img { width: 100%; height: 100%; object-fit: cover; }

        @media print {
            body { background: #fff; padding: 0; }
            .toolbar { display: none; }
            .card { box-shadow: none; border-radius: 0; }
            @if($cardPrinter)
                .cards { display: block; }
                .card { break-after: page; page-break-after: always; }
                .card:last-child { break-after: auto; page-break-after: auto; }
            @else
                .cards { gap: 8mm; }
                /* thin cut guide around each card on A4 */
                .card { outline: 0.2mm dashed #94a3b8; outline-offset: 0.5mm; }
            @endif
        }
    </style>
</head>
<body>
    <div class="toolbar">
        <button class="primary" onclick="window.print()">🖨️ چاپکردنی ناسنامە</button>
        <a href="{{ route('patients.id-card', $patient) }}" class="{{ $cardPrinter ? '' : 'active' }}">کاغەزی A4</a>
        <a href="{{ route('patients.id-card', ['patient' => $patient, 'printer' => 'card']) }}" class="{{ $cardPrinter ? 'active' : '' }}">پرینتەری کارت (CR80)</a>
    </div>

    <div class="cards">
        {{-- FRONT --}}
        <div class="card card-front front">
            <div class="photo">
                @if($patient->photoDocument)
                    <img src="{{ route('patient-documents.view', $patient->photoDocument) }}" alt="">
                @else
                    {{ mb_substr($patient->first_name, 0, 1) }}
                @endif
            </div>

            <div class="val" style="top: 35.6%; right: 8.2%;">{{ $patient->full_name }}</div>
            <div class="val" style="top: 48%; right: 10.7%;">{{ $patient->gender?->label() ?? '—' }}</div>
            <div class="val" style="top: 60.3%; right: 17.9%;"><span class="ltr">{{ $patient->dob?->format('Y/m/d') ?? '—' }}</span></div>
            <div class="val" style="top: 72.1%; right: 15.9%;"><span class="ltr">{{ $patient->national_id ?: ($patient->membership_number ?: $patient->patient_code) }}</span></div>
            <div class="val" style="top: 84.9%; right: 25.4%;"><span class="ltr">{{ now()->format('Y/m/d') }}</span></div>
        </div>

        {{-- BACK --}}
        <div class="card card-back back">
            <div class="val" style="top: 10%; right: 20%;">{{ collect([$patient->governorate, $patient->district, $patient->neighborhood])->filter()->join(' - ') ?: '—' }}</div>
            <div class="val" style="top: 23.1%; right: 20%; color: #b91c1c;"><span class="ltr">{{ $patient->blood_group?->value ?? '—' }}</span></div>
            <div class="val" style="top: 33.5%; right: 23.2%;">{{ $patient->hemophilia_type?->label() }}@if($patient->severity) - {{ $patient->severity->label() }}@endif</div>
        </div>
    </div>
</body>
</html>
