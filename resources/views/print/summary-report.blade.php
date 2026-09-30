<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>ڕاپۆرتی پزیشکی نەخۆش - {{ $patient->full_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        @page {
            size: A4 portrait;
            size: 210mm 297mm;
            margin: 0 !important;
        }
        *, *::before, *::after {
            box-sizing: border-box !important;
        }
        html, body {
            margin: 0 !important;
            padding: 0 !important;
            width: 100%;
            height: 100%;
            background-color: #f1f5f9;
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, sans-serif;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
        .screen-wrapper {
            display: flex;
            justify-content: center;
            padding: 15px 0;
        }
        .report-container {
            width: 210mm;
            height: 250mm;
            max-height: 250mm;
            background: white;
            padding: 5mm 8mm;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            overflow: hidden;
            position: relative;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #be123c;
            padding-bottom: 4px;
        }
        .header-logo {
            width: 44px;
            height: 44px;
            object-fit: contain;
        }
        .header-title {
            text-align: center;
        }
        .header-title h1 {
            margin: 0;
            font-size: 14.5px;
            font-weight: 900;
            color: #be123c;
        }
        .header-title h2 {
            margin: 1px 0 0 0;
            font-size: 9.5px;
            font-weight: 700;
            color: #475569;
        }
        .section-title {
            font-size: 10px;
            font-weight: 900;
            color: #be123c;
            border-right: 3px solid #be123c;
            padding-right: 5px;
            margin: 5px 0 2px 0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2px 6px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 4px 6px;
            font-size: 9px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            color: #64748b;
            font-weight: 700;
            font-size: 8px;
            margin-bottom: 0px;
        }
        .info-value {
            color: #0f172a;
            font-weight: 800;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
            margin-top: 2px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 2.5px 5px;
            text-align: right;
        }
        th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 800;
        }
        .footer-wrap {
            margin-top: auto;
            position: relative;
        }
        .signature-section {
            margin-top: 6px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .seal-img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            opacity: 0.9;
        }
        .signature-box {
            text-align: center;
            font-size: 10.5px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.2;
        }
        .green-signature {
            height: 36px;
            object-fit: contain;
            margin-bottom: -3px;
        }
        .footer {
            margin-top: 4px;
            border-top: 1px solid #e2e8f0;
            padding-top: 3px;
            text-align: center;
            font-size: 8.5px;
            font-weight: 700;
            color: #475569;
        }
        .print-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            background-color: #be123c;
            color: white;
            padding: 8px 18px;
            border-radius: 8px;
            border: none;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(190,18,60,0.35);
            z-index: 100;
        }
        @media print {
            .screen-wrapper {
                padding: 0 !important;
                margin: 0 !important;
                display: block !important;
            }
            html, body {
                width: 100% !important;
                height: 100% !important;
                max-height: 250mm !important;
                overflow: hidden !important;
                background: white !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .report-container {
                width: 100% !important;
                height: 250mm !important;
                max-height: 250mm !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                border: none !important;
                padding: 3mm 6mm !important;
                box-sizing: border-box !important;
                overflow: hidden !important;
                page-break-after: avoid !important;
                page-break-before: avoid !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            .print-btn, .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn">🖨️ پرینتکردنی ڕاپۆرت (Print)</button>

    <div class="screen-wrapper">
        <div class="report-container">
            <div class="content-body">
                <!-- Header -->
                <div class="header">
                    <div style="display: flex; items-center; gap: 6px;">
                        <img src="{{ asset('images/logo.jpg') }}" class="header-logo" alt="Logo" />
                        <div>
                            <strong style="font-size: 10.5px; color: #334155;">کۆمەڵەی هیمۆفیلیای کوردستان - لقی سلێمانی</strong><br>
                            <span style="font-size: 8.5px; color: #64748b;">بەشی پزیشکی و ڕاپۆرتەکان</span>
                        </div>
                    </div>

                    <div class="header-title">
                        <h1>ڕاپۆرتی پزیشکی و کۆمەکی نەخۆش</h1>
                        <h2>Patient Medical & Assistance Summary</h2>
                    </div>

                    <div style="text-align: left; font-size: 8.5px; color: #64748b;">
                        <strong>کۆدی نەخۆش:</strong> {{ $patient->patient_code }}<br>
                        <strong>بەرواری چاپ:</strong> {{ date('Y-m-d') }}
                    </div>
                </div>

                <!-- Patient Personal Info -->
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

                <!-- Medical Info -->
                <div class="section-title">پرۆفایلی پزیشکی و تاقیکردنەوەکان</div>
                <div class="info-grid">
                    <div class="info-item"><span class="info-label">جۆری هیمۆفیلیا</span><span class="info-value">{{ $patient->hemophilia_type?->label() }}</span></div>
                    <div class="info-item"><span class="info-label">ئاستی سەختی (Severity)</span><span class="info-value">{{ $patient->severity?->label() }}</span></div>
                    <div class="info-item"><span class="info-label">گرووپی خوێن</span><span class="info-value" dir="ltr" style="text-align: right;">{{ $patient->blood_group?->value ?? '—' }}</span></div>
                    <div class="info-item"><span class="info-label">Hepatitis B</span><span class="info-value">{{ $patient->hepatitis_b?->label() ?? '—' }}</span></div>
                    <div class="info-item"><span class="info-label">Hepatitis C</span><span class="info-value">{{ $patient->hepatitis_c?->label() ?? '—' }}</span></div>
                    <div class="info-item"><span class="info-label">HIV</span><span class="info-value">{{ $patient->hiv?->label() ?? '—' }}</span></div>
                </div>

                <!-- Medical Logs -->
                @if($patient->medicalLogs->count() > 0)
                    <div class="section-title">تۆماری سەردانی پزیشکی و فاکتەرەکان</div>
                    <table>
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
                            @foreach($patient->medicalLogs->take(2) as $log)
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

                <!-- Assistances -->
                @if($patient->assistances->count() > 0)
                    <div class="section-title">مێژووی هاوکاری و یارمەتییەکان</div>
                    <table>
                        <thead>
                            <tr>
                                <th>کۆد</th>
                                <th>بەروار</th>
                                <th>جۆری هاوکاری</th>
                                <th>سەرچاوە / دابینکەر</th>
                                <th>بڕی پارە (IQD)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patient->assistances->take(2) as $aid)
                                <tr>
                                    <td style="font-family: monospace; font-weight: bold;">{{ $aid->assistance_number }}</td>
                                    <td>{{ $aid->assistance_date->format('Y-m-d') }}</td>
                                    <td>{{ $aid->category->label() }}</td>
                                    <td>{{ $aid->source_funder ?? '—' }}</td>
                                    <td style="font-weight: bold; color: #059669;">{{ number_format($aid->amount) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

            <!-- Footer & Signature Wrap -->
            <div class="footer-wrap">
                <div class="signature-section">
                    <div>
                        <img src="{{ asset('images/logo.jpg') }}" class="seal-img" alt="Seal" />
                    </div>
                    <div class="signature-box">
                        <div>
                            <img src="{{ asset('images/signature_green.png') }}" class="green-signature" alt="Signature" />
                        </div>
                        <strong>زانیار عادل حسین</strong><br>
                        <span style="font-size: 8.5px; color: #475569;">سەرۆکی کۆمەڵەی هیمۆفیلیای کوردستان / لقی سلێمانی</span>
                    </div>
                </div>

                <!-- Footer -->
                <div class="footer">
                    ناونیشان : سلێمانی – گەڕەکی ژیانەوە – نزیک خوێندنگەی ڕەزبەری بنەڕەتی | پەیوەندی : <strong>07732929393 | 07501910667</strong>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
