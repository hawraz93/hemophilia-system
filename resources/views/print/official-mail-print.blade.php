<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>نوسراوی فەرمی - {{ $mail->mail_number }}</title>
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
        .letter-container {
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
            border: 3px double #b91c1c;
        }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 260px;
            height: 260px;
            opacity: 0.05;
            pointer-events: none;
            z-index: 0;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #b91c1c;
            padding-bottom: 5px;
            position: relative;
            z-index: 1;
        }
        .header-logo {
            width: 50px;
            height: 50px;
            object-fit: contain;
        }
        .header-title {
            text-align: center;
        }
        .header-title h1 {
            margin: 0;
            font-size: 16.5px;
            color: #b91c1c;
            font-weight: 900;
        }
        .header-title h2 {
            margin: 1px 0 0 0;
            font-size: 10px;
            color: #1e293b;
            font-weight: 800;
        }

        .meta-info-box {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 10px;
            margin-bottom: 10px;
            padding: 6px 10px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 5px;
            position: relative;
            z-index: 1;
        }
        .meta-recipient {
            font-size: 12px;
            font-weight: 900;
            color: #0f172a;
        }
        .meta-recipient span {
            color: #b91c1c;
        }
        .meta-subject {
            font-size: 12px;
            font-weight: 900;
            color: #0f172a;
        }
        .meta-subject span {
            color: #0369a1;
        }

        .content {
            margin-top: 6px;
            font-size: 12px;
            line-height: 1.75;
            color: #0f172a;
            text-align: justify;
            position: relative;
            z-index: 1;
            white-space: pre-line;
        }
        .footer-wrap {
            position: relative;
            z-index: 1;
            margin-top: auto;
        }
        .signature-section {
            margin-top: 8px;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            min-height: 65px;
        }
        .seal-img {
            width: 55px;
            height: 55px;
            object-fit: contain;
            opacity: 0.9;
        }
        .signature-box {
            text-align: center;
            font-size: 11px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.2;
        }
        .green-signature {
            height: 38px;
            object-fit: contain;
            margin-bottom: -4px;
        }
        .manual-space {
            min-height: 40px;
        }
        .footer {
            border-top: 1px solid #cbd5e1;
            padding-top: 4px;
            margin-top: 8px;
            text-align: center;
            font-size: 8.5px;
            font-weight: 700;
            color: #475569;
            line-height: 1.3;
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
            .letter-container {
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

    <button onclick="window.print()" class="print-btn">🖨️ چاپکردنی نوسراو (Print)</button>

    <div class="screen-wrapper">
        <div class="letter-container">
            <!-- Watermark -->
            <img src="{{ asset('images/logo.jpg') }}" class="watermark" alt="Watermark" />

            <div class="content-body">
                <!-- Official Header -->
                <div class="header">
                    <div style="width: 120px; text-align: right; font-size: 9.5px; color: #1e293b; font-weight: 900; line-height: 1.3;">
                        کۆمەڵەی هیمۆفیلیای کوردستان<br>لقی سلێمانی
                    </div>

                    <div class="header-title flex items-center gap-3">
                        <img src="{{ asset('images/logo.jpg') }}" class="header-logo" alt="Logo" />
                        <div>
                            <h1>کۆمەڵەی هیمۆفیلیای کوردستان</h1>
                            <h2>لقی سلێمانی</h2>
                        </div>
                    </div>

                    <div style="width: 120px; text-align: left; font-size: 9px; color: #475569; font-weight: 900; line-height: 1.4;">
                        ژمارە: <strong style="color: #b91c1c;">{{ $mail->mail_number }}</strong><br>
                        ڕێکەوت: <strong>{{ $mail->mail_date->format('Y / m / d') }}</strong>
                    </div>
                </div>

                <!-- Subject & Recipient Block -->
                <div class="meta-info-box">
                    <div class="meta-recipient">
                        بۆ / <span>{{ $mail->sender_recipient }}</span>
                    </div>
                    <div class="meta-subject">
                        بابەت / <span>{{ $mail->reason_subject }}</span>
                    </div>
                </div>

                <!-- Content Body -->
                <div class="content">
                    {{ $mail->letter_body ?: "سڵاو و ڕێز ...\nئاماژە بە بە بابەتەکە، داواکارین لە بەڕێزتان هاوکاری و کارئاسانی پێویستمان بۆ بکەن." }}
                </div>
            </div>

            <!-- Footer & Signature Wrap -->
            <div class="footer-wrap">
                <div class="signature-section">
                    @if($mail->stamp_type === 'online')
                        <div>
                            <img src="{{ asset('images/logo.jpg') }}" class="seal-img" alt="Seal" />
                        </div>
                        <div class="signature-box">
                            <div>
                                <img src="{{ asset('images/signature_green.png') }}" class="green-signature" alt="Signature" />
                            </div>
                            <strong style="font-size: 11.5px; color: #0f172a;">زانیار عادل حسین</strong><br>
                            <span style="font-size: 8.5px; color: #475569;">سەرۆکی کۆمەڵەی هیمۆفیلیای کوردستان / لقی سلێمانی</span>
                        </div>
                    @else
                        <!-- Manual Stamp Space for Physical Stamping After Print -->
                        <div class="manual-space">
                            <!-- Left blank for physical rubber stamp -->
                        </div>
                        <div class="signature-box">
                            <div class="manual-space"></div>
                            <strong style="font-size: 11.5px; color: #0f172a;">زانیار عادل حسین</strong><br>
                            <span style="font-size: 8.5px; color: #475569;">سەرۆکی کۆمەڵەی هیمۆفیلیای کوردستان / لقی سلێمانی</span>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="footer">
                    ناونیشان : سلێمانی – گەڕەکی ژیانەوە – نزیک خوێندنگەی ڕەزبەری بنەڕەتی<br>
                    پەیوەندی : <strong>07732929393 | 07501910667</strong>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
