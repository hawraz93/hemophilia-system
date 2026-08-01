<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>نوسراوی پشتگیری - {{ $patient->full_name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
        }
        .letter-container {
            width: 210mm;
            min-height: 285mm;
            background: white;
            padding: 40px 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            border: 3px double #b91c1c;
        }
        /* Background Watermark */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 320px;
            height: 320px;
            opacity: 0.04;
            pointer-events: none;
            z-index: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #b91c1c;
            padding-bottom: 15px;
            position: relative;
            z-index: 1;
        }
        .header-logo {
            width: 75px;
            height: 75px;
        }
        .header-title {
            text-align: center;
        }
        .header-title h1 {
            margin: 0;
            font-size: 22px;
            color: #b91c1c;
            font-weight: 900;
            letter-spacing: -0.3px;
        }
        .header-title h2 {
            margin: 3px 0 0 0;
            font-size: 13px;
            color: #1e293b;
            font-weight: 800;
        }
        .header-title h3 {
            margin: 2px 0 0 0;
            font-size: 11px;
            color: #64748b;
            font-family: Arial, sans-serif;
            font-weight: bold;
        }

        .meta-info-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 30px;
            margin-bottom: 25px;
            padding: 12px 18px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            position: relative;
            z-index: 1;
        }
        .meta-recipient {
            font-size: 15px;
            font-weight: 900;
            color: #0f172a;
        }
        .meta-recipient span {
            color: #b91c1c;
        }
        .meta-subject {
            font-size: 15px;
            font-weight: 900;
            color: #0f172a;
        }
        .meta-subject span {
            color: #0369a1;
        }

        .content {
            margin-top: 15px;
            font-size: 15px;
            line-height: 2.2;
            color: #0f172a;
            text-align: justify;
            position: relative;
            z-index: 1;
        }
        .patient-box {
            background-color: #ffffff;
            border: 1px solid #cbd5e1;
            border-right: 6px solid #b91c1c;
            padding: 18px 24px;
            margin: 22px 0;
            border-radius: 8px;
            font-size: 14.5px;
            line-height: 2.1;
            box-shadow: 0 2px 8px rgba(0,0,0,0.03);
        }
        .signature-section {
            margin-top: 45px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            position: relative;
            z-index: 1;
        }
        .seal-box {
            width: 110px;
            height: 110px;
            border: 2px stroke #1d4ed8;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0.85;
        }
        .signature-box {
            text-align: center;
            font-size: 14px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.4;
        }
        .footer {
            border-top: 1.5px solid #cbd5e1;
            padding-top: 10px;
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            position: relative;
            z-index: 1;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #b91c1c;
            color: white;
            padding: 12px 26px;
            border-radius: 10px;
            border: none;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(185,28,28,0.35);
            transition: all 0.2s;
            z-index: 100;
        }
        .print-btn:hover {
            background-color: #991b1b;
        }
        @media print {
            .print-btn { display: none !important; }
            body { padding: 0; background: white; }
            .letter-container { box-shadow: none; border-radius: 0; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">🖨️ چاپکردنی نوسراوی پشتگیری (Print)</button>

    <div class="letter-container">
        <!-- Background Watermark SVG -->
        <svg class="watermark" viewBox="0 0 100 100">
            <circle cx="50" cy="50" r="46" fill="#b91c1c"/>
            <ellipse cx="50" cy="50" rx="34" ry="17" fill="none" stroke="#ffffff" stroke-width="2"/>
            <ellipse cx="50" cy="50" rx="17" ry="34" fill="none" stroke="#ffffff" stroke-width="2"/>
        </svg>

        <div>
            <!-- Official Header -->
            <div class="header">
                <div style="width: 140px; text-align: right; font-size: 11.5px; color: #1e293b; font-weight: 900; line-height: 1.5;">
                    کۆمەڵەی هیمۆفیلیای کوردستان<br>لقی سلێمانی
                </div>

                <div class="header-title">
                    <h1>کۆمەڵەی هیمۆفیلیای کوردستان</h1>
                    <h2>لقی سلێمانی</h2>
                    <h3>Kurdistan Hemophilia Society - Sulaimani Branch</h3>
                </div>

                <div style="width: 140px; text-align: left; font-size: 11px; color: #475569; font-weight: 900; line-height: 1.6;">
                    ژمارە: SUP-{{ date('Y') }}-{{ str_pad($patient->id, 4, '0', STR_PAD_LEFT) }}<br>
                    بەروار: {{ date('Y/m/d') }}
                </div>
            </div>

            <!-- Subject & Recipient Block -->
            <div class="meta-info-box">
                <div class="meta-recipient">
                    بۆ / <span>{{ $recipient ?? request('recipient', 'سەرجەم لایەنە پەیوەندیدارەکان') }}</span>
                </div>
                <div class="meta-subject">
                    بابەت / <span>{{ $subject ?? request('subject', 'نوسراوی پشتگیری') }}</span>
                </div>
            </div>

            <!-- Content Body -->
            <div class="content">
                سڵاو و ڕێز ...<br>
                ئاماژە بە تۆمارە فەرمییەکانی کۆمەڵەی هیمۆفیلیای کوردستان (لقی سلێمانی)، پشتگیری دەکەین کە هاووڵاتی ئاماژەپێکراوی خوارەوە ئەندامی بەردەوام و تۆمارکراوی کۆمەڵەکەمانە بە دۆخی <strong>سەوز (تەواو)</strong> و تووشبووی نەخۆشی هیمۆفیلیاست:

                <div class="patient-box">
                    • <strong>ناوی تەواو:</strong> {{ $patient->full_name }}<br>
                    • <strong>کۆدی نەخۆش:</strong> {{ $patient->patient_code }} | <strong>ژمارەی ئەندامێتی:</strong> {{ $patient->membership_number ?? '—' }} ({{ $patient->membership_type?->label() ?? 'ئەندامی ئاسایی' }})<br>
                    • <strong>جۆری نەخۆشی:</strong> {{ $patient->hemophilia_type?->label() }} (پلەی {{ $patient->severity?->label() }})<br>
                    • <strong>گروپی خوێن:</strong> {{ $patient->blood_group?->value ?? '—' }} | <strong>کۆدی نەخۆشخانەی هیوا:</strong> {{ $patient->hiwa_code ?? '—' }}<br>
                    • <strong>ژمارەی ناسنامە / دەنگدان:</strong> {{ $patient->national_id ?? '—' }} {{ $patient->voting_card_number ? '| کارتی دەنگدان: '.$patient->voting_card_number : '' }}<br>
                    • <strong>ژمارەی مۆبایل:</strong> {{ $patient->phone }}
                </div>

                تکایە هاوکاری و ئاسانکاری پێویستی بۆ بکەن بۆ ڕاییکردنی مامەڵەکانی بەپێی یاسا و ڕێنماییە کارپێکراوەکان.

                <br><br>
                لەگەڵ فێنکی ڕێزماندا ...
            </div>

            <!-- Signatures & Stamp -->
            <div class="signature-section">
                <!-- Blue Seal SVG -->
                <div class="seal-box">
                    <svg viewBox="0 0 100 100" style="width: 90px; height: 90px;">
                        <circle cx="50" cy="50" r="45" fill="none" stroke="#1d4ed8" stroke-width="2.5"/>
                        <circle cx="50" cy="50" r="40" fill="none" stroke="#1d4ed8" stroke-width="1" stroke-dasharray="3,1.5"/>
                        <path d="M15,60 C35,40 55,65 85,45 C65,65 35,55 15,60 Z" fill="#1e40af"/>
                        <text x="50" y="32" font-size="6" font-weight="bold" fill="#1d4ed8" text-anchor="middle">کۆمەڵەی هیمۆفیلیای کوردستان</text>
                        <text x="50" y="78" font-size="6" font-weight="bold" fill="#1d4ed8" text-anchor="middle">لقی سلێمانی - مۆری فەرمی</text>
                    </svg>
                </div>

                <div class="signature-box">
                    رانیار عادل حسین<br>
                    <span style="font-size: 12px; color: #475569;">سەرۆکی کۆمەڵەی هیمۆفیلیای کوردستان / لقی سلێمانی</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            سلێمانی - ڕێکخراوەکانی کۆمەڵگەی مەدەنی | مۆبایل: 0770 000 0000 | ئیمەیڵ: info@hemophilia-kurdistan.org
        </div>
    </div>

</body>
</html>
