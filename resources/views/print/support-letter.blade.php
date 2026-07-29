<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>نوسراوی پشتگیری - {{ $patient->full_name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 40px;
            display: flex;
            justify-content: center;
        }
        .letter-container {
            width: 210mm;
            min-height: 297mm;
            background: white;
            padding: 40px 50px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px double #dc2626;
            padding-bottom: 15px;
        }
        .header-title {
            text-align: center;
        }
        .header-title h1 {
            margin: 0;
            font-size: 20px;
            color: #991b1b;
        }
        .header-title h2 {
            margin: 4px 0 0 0;
            font-size: 14px;
            color: #475569;
        }
        .meta-info {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
            font-size: 13px;
            font-weight: bold;
            color: #334155;
        }
        .content {
            margin-top: 40px;
            font-size: 15px;
            line-height: 2;
            color: #1e293b;
            text-align: justify;
        }
        .patient-box {
            background-color: #f1f5f9;
            border-right: 4px solid #dc2626;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 6px;
            font-size: 14px;
            line-height: 1.8;
        }
        .signature-section {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }
        .signature-box {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            color: #0f172a;
        }
        .footer {
            border-top: 1px solid #cbd5e1;
            padding-top: 10px;
            text-align: center;
            font-size: 11px;
            color: #64748b;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #dc2626;
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }
        @media print {
            .print-btn { display: none; }
            body { padding: 0; background: white; }
            .letter-container { box-shadow: none; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">🖨️ چاپکردنی نوسراوی پشتگیری</button>

    <div class="letter-container">
        <div>
            <!-- Header -->
            <div class="header">
                <div style="width: 100px; text-align: right; font-size: 11px; color: #475569;">
                    کۆمەڵەی هیمۆفیلیا<br>لقی سلێمانی
                </div>
                <div class="header-title">
                    <h1>کۆمەڵەی هیمۆفیلیای کوردستان</h1>
                    <h2>Kurdistan Hemophilia Association</h2>
                </div>
                <div style="width: 100px; text-align: left; font-size: 11px; color: #475569;">
                    ژمارە: SUP-{{ date('Y') }}-{{ $patient->id }}<br>
                    بەروار: {{ date('Y/m/d') }}
                </div>
            </div>

            <!-- Subject -->
            <div class="meta-info">
                <div>بۆ / سەرجەم لایەنە پەیوەندیدارەکان</div>
                <div>بابەت / نوسراوی پشتگیری</div>
            </div>

            <!-- Content Body -->
            <div class="content">
                سڵاو و ڕێز ...<br>
                ئاماژە بە تۆمارەکانی کۆمەڵەی هیمۆفیلیای کوردستان (لقی سلێمانی)، پشتگیری دەکەین کە هاووڵاتی ئاماژەپێکراوی خوارەوە ئەندامی بەردەوامی کۆمەڵەکەمانە لە لیستی سەوزدا و نەخۆشی هیمۆفیلیای هەیە:

                <div class="patient-box">
                    • <strong>ناوی تەواو:</strong> {{ $patient->full_name }}<br>
                    • <strong>کۆدی نەخۆش:</strong> {{ $patient->patient_code }} | <strong>ژمارەی ئەندامێتی:</strong> {{ $patient->membership_number ?? '—' }}<br>
                    • <strong>جۆری هیمۆفیلیا:</strong> هیمۆفیلیا {{ $patient->hemophilia_type?->label() }} (پلەی {{ $patient->severity?->label() }})<br>
                    • <strong>گروپی خوێن:</strong> {{ $patient->blood_group?->value ?? '—' }} | <strong>کۆدی هیوا:</strong> {{ $patient->hiwa_code ?? '—' }}<br>
                    • <strong>ژمارەی مۆبایل:</strong> {{ $patient->phone }}
                </div>

                تکایە هاوکاری و ئاسانکاری پێویستی بۆ بکەن بۆ ڕاییکردنی مامەڵەکانی بەپێی یاسا و ڕێنماییە کارپێکراوەکان.

                <br><br>
                لەگەڵ ڕێزماندا ...
            </div>

            <!-- Signatures -->
            <div class="signature-section">
                <div class="signature-box">
                    مۆری فەرمی کۆمەڵە
                </div>
                <div class="signature-box">
                    بەڕێوەبەری لقی سلێمانی<br>
                    کۆمەڵەی هیمۆفیلیای کوردستان
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
