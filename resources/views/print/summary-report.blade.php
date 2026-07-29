<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>ڕاپۆرتی پزیشکی نەخۆش - {{ $patient->full_name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Vazirmatn:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 30px 0;
            display: flex;
            justify-content: center;
        }
        .report-container {
            width: 210mm;
            min-height: 297mm;
            background: white;
            padding: 35px 45px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px double #e11d48;
            padding-bottom: 15px;
        }
        .header-title {
            text-align: center;
        }
        .header-title h1 {
            margin: 0;
            font-size: 20px;
            font-weight: 900;
            color: #be123c;
        }
        .header-title h2 {
            margin: 4px 0 0 0;
            font-size: 13px;
            font-weight: 700;
            color: #475569;
        }
        .section-title {
            font-size: 14px;
            font-weight: 900;
            color: #be123c;
            border-right: 4px solid #e11d48;
            padding-right: 10px;
            margin: 25px 0 12px 0;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            font-size: 12px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            color: #64748b;
            font-weight: 700;
            font-size: 11px;
            margin-bottom: 2px;
        }
        .info-value {
            color: #0f172a;
            font-weight: 800;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
            margin-top: 8px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 8px 10px;
            text-align: right;
        }
        th {
            background-color: #f1f5f9;
            color: #334155;
            font-weight: 800;
        }
        .footer {
            margin-top: 40px;
            border-top: 2px solid #e2e8f0;
            padding-top: 20px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            font-size: 12px;
        }
        .stamp-box {
            text-align: center;
            width: 200px;
            border: 1px dashed #cbd5e1;
            padding: 15px;
            border-radius: 10px;
            color: #94a3b8;
            font-weight: 700;
        }
        @media print {
            body {
                background: white;
                padding: 0;
            }
            .report-container {
                box-shadow: none;
                padding: 20px 30px;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="report-container">
        <div>
            <!-- Print Action Floating Bar -->
            <div class="no-print" style="margin-bottom: 20px; text-align: left;">
                <button onclick="window.print()" style="background-color: #e11d48; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; font-family: inherit;">
                    🖨️ پرینتکردنی ڕاپۆرت
                </button>
            </div>

            <!-- Header -->
            <div class="header">
                <div>
                    <strong style="font-size: 13px; color: #334155;">کۆمەڵەی نەخۆشانی هیمۆفیلیا</strong><br>
                    <span style="font-size: 11px; color: #64748b;">بەشی پزیشکی و ڕاپۆرتەکان</span>
                </div>
                <div class="header-title">
                    <h1>ڕاپۆرتی پزیشکی و کۆمەکی نەخۆش</h1>
                    <h2>Patient Medical & Assistance Summary</h2>
                </div>
                <div style="text-align: left; font-size: 11px; color: #64748b;">
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
                <div class="info-item"><span class="info-label">ڕەگەز</span><span class="info-value">{{ $patient->gender->label() }}</span></div>
                <div class="info-item"><span class="info-label">بەرواری لەدایکبوون</span><span class="info-value">{{ $patient->dob ? $patient->dob->format('Y-m-d') : '—' }} ({{ $patient->age }} ساڵ)</span></div>
                <div class="info-item"><span class="info-label">ژمارەی مۆبایل</span><span class="info-value" dir="ltr" style="text-align: right;">{{ $patient->phone }}</span></div>
                <div class="info-item"><span class="info-label">پارێزگا / قەزا</span><span class="info-value">{{ $patient->governorate ?? '—' }} / {{ $patient->district ?? '—' }}</span></div>
                <div class="info-item"><span class="info-label">دۆخی هاوسەرگیری</span><span class="info-value">{{ $patient->marital_status ?? '—' }}</span></div>
                <div class="info-item"><span class="info-label">لیستی دۆخی نەخۆش</span><span class="info-value" style="color: #e11d48;">{{ $patient->list_status->label() }}</span></div>
            </div>

            <!-- Medical Info -->
            <div class="section-title">پرۆفایلی پزیشکی و تاقیکردنەوەکان</div>
            <div class="info-grid">
                <div class="info-item"><span class="info-label">جۆری هیمۆفیلیا</span><span class="info-value">{{ $patient->hemophilia_type->label() }}</span></div>
                <div class="info-item"><span class="info-label">ئاستی سەختی (Severity)</span><span class="info-value">{{ $patient->severity->label() }}</span></div>
                <div class="info-item"><span class="info-label">گرووپی خوێن</span><span class="info-value" dir="ltr" style="text-align: right;">{{ $patient->blood_type ?? '—' }}</span></div>
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
                        @foreach($patient->medicalLogs->take(5) as $log)
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
                        @foreach($patient->assistances->take(5) as $aid)
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

        <!-- Footer -->
        <div class="footer">
            <div>
                <p style="margin: 0;"><strong>ناونیشان:</strong> هەرێمی کوردستان - عێراق</p>
                <p style="margin: 3px 0 0 0; color: #64748b;">تەلەفۆنی پەیوەندی: {{ $patient->phone }}</p>
            </div>
            <div class="stamp-box">
                مۆر و ئیمزای فەرمی
            </div>
        </div>
    </div>

    <script>
        window.onload = function() {
            // Auto print prompt when opened
            window.print();
        };
    </script>
</body>
</html>
