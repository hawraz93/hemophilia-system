<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>ڕاپۆرتی دابەشکردنی هاوکاری - {{ $campaign->title }}</title>
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
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
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
            border-bottom: 2px solid #b91c1c;
            padding-bottom: 5px;
        }
        .header-logo {
            width: 45px;
            height: 45px;
            object-fit: contain;
        }
        .header-title {
            text-align: center;
        }
        .header-title h1 {
            margin: 0;
            font-size: 15px;
            color: #b91c1c;
            font-weight: 900;
        }
        .header-title h2 {
            margin: 1px 0 0 0;
            font-size: 9.5px;
            color: #1e293b;
            font-weight: 800;
        }
        .meta-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 3px 6px;
            margin: 5px 0;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 4px 6px;
            font-size: 9px;
        }
        .meta-item strong {
            color: #0f172a;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5px;
            margin-top: 3px;
        }
        th, td {
            border: 1px solid #cbd5e1;
            padding: 2.5px 5px;
            text-align: right;
        }
        th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-weight: 900;
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
        }
        .signature-box {
            text-align: center;
            font-size: 10.5px;
            font-weight: 900;
            color: #0f172a;
        }
        .green-signature {
            height: 36px;
            object-fit: contain;
            margin-bottom: -3px;
        }
        .footer {
            border-top: 1px solid #cbd5e1;
            padding-top: 3px;
            margin-top: 5px;
            text-align: center;
            font-size: 8.5px;
            font-weight: 700;
            color: #475569;
        }
        .print-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            background-color: #b91c1c;
            color: white;
            padding: 8px 18px;
            border-radius: 8px;
            border: none;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(185,28,28,0.35);
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

    <button onclick="window.print()" class="print-btn">🖨️ چاپکردنی ڕاپۆرت (Print)</button>

    <div class="screen-wrapper">
        <div class="report-container">
            <div class="content-body">
                <!-- Official Header -->
                <div class="header">
                    <div style="display: flex; items-center; gap: 6px;">
                        <img src="{{ asset('images/logo.jpg') }}" class="header-logo" alt="Logo" />
                        <div>
                            <strong style="font-size: 10px; color: #1e293b;">کۆمەڵەی هیمۆفیلیای کوردستان - لقی سلێمانی</strong><br>
                            <span style="font-size: 8px; color: #64748b;">لیستی فەرمی بڵاوکردنەوەی هاوکاری</span>
                        </div>
                    </div>

                    <div class="header-title">
                        <h1>ڕاپۆرتی بەخشینی هاوکاری و کۆمەک</h1>
                        <h2>Official Aid Distribution List</h2>
                    </div>

                    <div style="text-align: left; font-size: 8.5px; color: #475569;">
                        کۆدی کەمپین: <strong>CAM-{{ date('Y') }}-{{ str_pad($campaign->id, 4, '0', STR_PAD_LEFT) }}</strong><br>
                        بەرواری چاپ: <strong>{{ date('Y/m/d') }}</strong>
                    </div>
                </div>

                <!-- Campaign Info Metadata -->
                <div class="meta-grid">
                    <div class="meta-item">ناوی کەمپین: <strong>{{ $campaign->title }}</strong></div>
                    <div class="meta-item">سەرچاوە / دابینکەر: <strong>{{ $campaign->source_funder ?? '—' }}</strong></div>
                    <div class="meta-item">جۆری هاوکاری: <strong>{{ $campaign->category?->label() }}</strong></div>
                    <div class="meta-item">ژمارەی نەخۆشە وەرگرەکان: <strong>{{ $campaign->patients->count() }} لە {{ $campaign->max_recipients }}</strong></div>
                    <div class="meta-item">بڕ بۆ هەر کەسێک: <strong>{{ number_format($campaign->amount_per_patient) }} IQD</strong></div>
                    <div class="meta-item">بەرواری هاوکاری: <strong>{{ $campaign->campaign_date->format('Y-m-d') }}</strong></div>
                </div>

                <!-- Recipients Table -->
                <table>
                    <thead>
                        <tr>
                            <th style="width: 25px; text-align: center;">#</th>
                            <th>کۆدی نەخۆش</th>
                            <th>ژمارەی ئەندامێتی</th>
                            <th>ناوی تەواوی نەخۆش</th>
                            <th>جۆری نەخۆشی / گروپی خوێن</th>
                            <th>بەرواری وەرگرتن</th>
                            <th style="width: 85px; text-align: center;">واژۆ / وەستانی وەرگرتن</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($campaign->patients->take(8) as $idx => $p)
                            <tr>
                                <td style="text-align: center; font-weight: bold;">{{ $idx + 1 }}</td>
                                <td style="font-family: monospace; font-weight: bold;">{{ $p->patient_code }}</td>
                                <td style="font-family: monospace;">{{ $p->membership_number ?? '—' }}</td>
                                <td><strong>{{ $p->full_name }}</strong></td>
                                <td>{{ $p->hemophilia_type?->label() }} ({{ $p->blood_group?->value ?? '—' }})</td>
                                <td style="font-size: 8px; color: #475569;">{{ $p->pivot->received_at ? \Carbon\Carbon::parse($p->pivot->received_at)->format('Y-m-d H:i') : '—' }}</td>
                                <td style="text-align: center;"></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" style="text-align: center; padding: 8px; color: #94a3b8;">هیچ نەخۆشێک تۆمار نەکراوە.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
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
