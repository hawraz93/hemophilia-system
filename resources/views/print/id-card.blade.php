<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>بەجی ئەندامێتی - {{ $patient->full_name }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
            background-color: #f1f5f9;
            margin: 0;
            padding: 30px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            box-sizing: border-box;
        }
        .print-btn {
            background-color: #dc2626;
            color: white;
            padding: 12px 28px;
            border-radius: 10px;
            border: none;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(220,38,38,0.3);
            margin-bottom: 30px;
            transition: all 0.2s;
        }
        .print-btn:hover {
            background-color: #b91c1c;
        }
        .cards-wrapper {
            display: flex;
            flex-direction: flex-row;
            flex-wrap: wrap;
            gap: 30px;
            justify-content: center;
            align-items: center;
        }
        .badge-card {
            width: 100mm;
            height: 62mm;
            background-color: #ffffff;
            border-radius: 14px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.12);
            border: 1px solid #cbd5e1;
            position: relative;
            box-sizing: border-box;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Front Side Styles */
        .front-header {
            background: linear-gradient(135deg, #c91c1c 0%, #b91c1c 100%);
            height: 38px;
            color: white;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 12px 0 16px;
            border-bottom-left-radius: 35px 12px;
            border-bottom-right-radius: 0px;
        }
        .front-header-title {
            font-size: 13.5px;
            font-weight: 900;
            color: #ffffff;
            letter-spacing: -0.2px;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
            margin-right: 38px;
        }
        .society-logo-wrapper {
            position: absolute;
            top: 3px;
            right: 8px;
            width: 38px;
            height: 38px;
            background: white;
            border-radius: 50%;
            border: 2px solid #b91c1c;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 6px rgba(0,0,0,0.2);
            z-index: 10;
        }
        .society-logo-svg {
            width: 32px;
            height: 32px;
        }

        .front-body {
            display: flex;
            justify-content: space-between;
            padding: 6px 14px 4px 14px;
            flex: 1;
        }
        .front-info {
            display: flex;
            flex-direction: column;
            gap: 2.5px;
            font-size: 11px;
            color: #0f172a;
            font-weight: 800;
            line-height: 1.35;
        }
        .front-info-row {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .front-info-label {
            color: #1e293b;
            font-weight: 900;
            font-size: 11.5px;
            min-width: 75px;
        }
        .front-info-val {
            color: #000000;
            font-weight: 900;
        }

        .front-left-side {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            width: 75px;
        }
        .patient-photo-placeholder {
            width: 62px;
            height: 68px;
            background-color: #f1f5f9;
            border: 2px solid #cbd5e1;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            color: #64748b;
            font-size: 20px;
            overflow: hidden;
        }
        .patient-photo-placeholder img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .signature-seal-box {
            text-align: center;
            position: relative;
            margin-top: 2px;
        }
        .seal-logo-svg {
            width: 38px;
            height: 38px;
            opacity: 0.85;
        }
        .signature-text {
            font-size: 7.5px;
            font-weight: 900;
            color: #0f172a;
            line-height: 1.1;
            margin-top: -6px;
        }

        /* Back Side Styles */
        .back-top {
            padding: 8px 12px 2px 12px;
            position: relative;
        }
        .back-top-logo {
            position: absolute;
            top: 6px;
            right: 8px;
            width: 36px;
            height: 36px;
        }
        .back-info-list {
            margin-right: 42px;
            font-size: 11px;
            color: #000000;
            font-weight: 900;
            line-height: 1.45;
        }
        .back-info-item {
            display: flex;
            align-items: center;
            gap: 4px;
        }
        .back-info-label {
            color: #0f172a;
            font-weight: 900;
            min-width: 85px;
        }

        .hiwa-logo-wrapper {
            position: absolute;
            top: 20px;
            left: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .hiwa-icon {
            width: 32px;
            height: 36px;
        }
        .hiwa-text {
            font-size: 10px;
            font-weight: 900;
            color: #15803d;
            font-family: Arial, sans-serif;
            letter-spacing: 0.5px;
        }
        .hiwa-subtext {
            font-size: 6px;
            color: #64748b;
            font-family: Arial, sans-serif;
        }

        .notice-text-box {
            margin: 4px 12px;
            font-size: 9px;
            font-weight: 800;
            color: #0f172a;
            line-height: 1.35;
            text-align: justify;
        }

        .back-footer-banner {
            background: linear-gradient(135deg, #c91c1c 0%, #b91c1c 100%);
            color: white;
            text-align: center;
            padding: 5px 8px;
            border-top-left-radius: 20px 8px;
            border-top-right-radius: 20px 8px;
        }
        .back-footer-ckb {
            font-size: 9.5px;
            font-weight: 900;
            margin-bottom: 1px;
        }
        .back-footer-eng {
            font-size: 7.5px;
            font-weight: 700;
            font-family: Arial, sans-serif;
            opacity: 0.95;
        }

        @media print {
            .print-btn { display: none !important; }
            body { background: white; padding: 0; }
            .badge-card { box-shadow: none; border: 1px solid #94a3b8; page-break-inside: avoid; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">🖨️ چاپکردنی بەج (ID Card Badge)</button>

    <div class="cards-wrapper">
        <!-- FRONT SIDE OF BADGE -->
        <div class="badge-card">
            <div class="front-header">
                <div class="society-logo-wrapper">
                    <!-- Kurdistan Hemophilia Society Circle Logo SVG -->
                    <svg class="society-logo-svg" viewBox="0 0 100 100">
                        <circle cx="50" cy="50" r="48" fill="#ffffff" stroke="#b91c1c" stroke-width="3"/>
                        <circle cx="50" cy="50" r="42" fill="none" stroke="#64748b" stroke-width="1" stroke-dasharray="2,2"/>
                        <!-- Globe Grid -->
                        <ellipse cx="50" cy="50" rx="36" ry="18" fill="none" stroke="#94a3b8" stroke-width="1.5"/>
                        <ellipse cx="50" cy="50" rx="18" ry="36" fill="none" stroke="#94a3b8" stroke-width="1.5"/>
                        <line x1="14" y1="50" x2="86" y2="50" stroke="#94a3b8" stroke-width="1.5"/>
                        <!-- Two Human Figures Joining Hands -->
                        <path d="M42,30 A5,5 0 1,1 42,20 A5,5 0 1,1 42,30 Z" fill="#dc2626"/>
                        <path d="M58,30 A5,5 0 1,1 58,20 A5,5 0 1,1 58,30 Z" fill="#1e3a8a"/>
                        <path d="M38,36 C42,34 46,34 50,38 C54,34 58,34 62,36 L58,58 L54,58 L52,46 L48,46 L46,58 L42,58 Z" fill="#dc2626"/>
                        <path d="M58,36 C60,35 64,35 66,38 L62,58 L58,58 Z" fill="#1e3a8a"/>
                        <!-- Curved Text Ribbon -->
                        <path id="textPathFront" d="M 15 50 A 35 35 0 0 1 85 50" fill="none"/>
                        <text font-size="7" font-weight="bold" fill="#b91c1c" text-anchor="middle">
                            <textPath href="#textPathFront" startOffset="50%">کۆمەڵەی هیمۆفیلیای کوردستان</textPath>
                        </text>
                    </svg>
                </div>

                <div class="front-header-title">
                    کۆمەڵەی هیمۆفیلیای کوردستان - لقی سلێمانی
                </div>
            </div>

            <div class="front-body">
                <!-- Right Side Information -->
                <div class="front-info">
                    <div class="front-info-row">
                        <span class="front-info-label">ناو:</span>
                        <span class="front-info-val">{{ $patient->full_name }}</span>
                    </div>

                    <div class="front-info-row">
                        <span class="front-info-label">ڕەگەز:</span>
                        <span class="front-info-val">{{ $patient->gender?->label() }}</span>
                    </div>

                    <div class="front-info-row">
                        <span class="front-info-label">لە دایکبوون:</span>
                        <span class="front-info-val">{{ $patient->dob?->format('Y/m/d') ?? $patient->age }}</span>
                    </div>

                    <div class="front-info-row">
                        <span class="front-info-label">ژ. ناسنامە:</span>
                        <span class="front-info-val">{{ $patient->national_id ?? $patient->membership_number ?? $patient->patient_code }}</span>
                    </div>

                    <div class="front-info-row">
                        <span class="front-info-label">ڕێکەوتی دەرچوون:</span>
                        <span class="front-info-val">{{ date('Y/m/d') }}</span>
                    </div>
                </div>

                <!-- Left Side: Photo + Signature & Stamp -->
                <div class="front-left-side">
                    <div class="patient-photo-placeholder">
                        @if($patient->photoDocument)
                            <img src="{{ route('patient-documents.view', $patient->photoDocument) }}" alt="Photo" />
                        @else
                            {{ mb_substr($patient->first_name, 0, 1) }}
                        @endif
                    </div>

                    <div class="signature-seal-box">
                        <!-- Blue Official Stamp SVG -->
                        <svg class="seal-logo-svg" viewBox="0 0 100 100">
                            <circle cx="50" cy="50" r="45" fill="none" stroke="#1d4ed8" stroke-width="2.5"/>
                            <circle cx="50" cy="50" r="40" fill="none" stroke="#1d4ed8" stroke-width="1" stroke-dasharray="3,1.5"/>
                            <!-- Inner Figure -->
                            <path d="M44,26 A4,4 0 1,1 44,18 A4,4 0 1,1 44,26 Z" fill="#1d4ed8"/>
                            <path d="M56,26 A4,4 0 1,1 56,18 A4,4 0 1,1 56,26 Z" fill="#1d4ed8"/>
                            <path d="M40,32 L60,32 L56,52 L44,52 Z" fill="#1d4ed8"/>
                            <!-- Signature Curve -->
                            <path d="M15,60 C35,40 55,65 85,45 C65,65 35,55 15,60 Z" fill="#1e40af"/>
                        </svg>
                        <div class="signature-text">
                            زانیار عادل حسین<br>
                            سەرۆکی کۆمەڵەی هیمۆفیلیای لقی سلێمانی
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- BACK SIDE OF BADGE -->
        <div class="badge-card">
            <div class="back-top">
                <!-- Logo Top Right -->
                <div class="back-top-logo">
                    <svg viewBox="0 0 100 100" style="width: 36px; height: 36px;">
                        <circle cx="50" cy="50" r="46" fill="#ffffff" stroke="#b91c1c" stroke-width="2.5"/>
                        <ellipse cx="50" cy="50" rx="34" ry="17" fill="none" stroke="#94a3b8" stroke-width="1.2"/>
                        <ellipse cx="50" cy="50" rx="17" ry="34" fill="none" stroke="#94a3b8" stroke-width="1.2"/>
                        <path d="M42,30 A4,4 0 1,1 42,22 A4,4 0 1,1 42,30 Z" fill="#dc2626"/>
                        <path d="M58,30 A4,4 0 1,1 58,22 A4,4 0 1,1 58,30 Z" fill="#1e3a8a"/>
                        <path d="M38,34 C42,32 46,32 50,36 C54,32 58,32 62,34 L58,54 L42,54 Z" fill="#dc2626"/>
                    </svg>
                </div>

                <!-- Patient Back Info (Right Side) -->
                <div class="back-info-list">
                    <div class="back-info-item">
                        <span class="back-info-label">نیشتەجێبوون:</span>
                        <span>{{ $patient->governorate }} — {{ $patient->district }}</span>
                    </div>

                    <div class="back-info-item">
                        <span class="back-info-label">گرووپی خوێن:</span>
                        <span style="color: #dc2626; font-size: 12px;">{{ $patient->blood_group?->value ?? '—' }}</span>
                    </div>

                    <div class="back-info-item">
                        <span class="back-info-label">جۆری چاره‌سه‌ر :</span>
                        <span>هیمۆفیلیای {{ $patient->hemophilia_type?->label() }}</span>
                    </div>

                    <div class="back-info-item" style="color: #000000;">
                        <span class="back-info-label">شوێنی چاره‌سه‌ر :</span>
                        <span>نەخۆشخانەی هیوا — سلێمانی</span>
                    </div>
                </div>

                <!-- Hiwa Hospital Logo (Left Middle) -->
                <div class="hiwa-logo-wrapper">
                    <!-- Green Leaf & HIWA Logo SVG -->
                    <svg class="hiwa-icon" viewBox="0 0 100 120">
                        <!-- Green Leaf -->
                        <path d="M30,70 C10,40 20,10 60,5 C40,30 50,55 30,70 Z" fill="#16a34a"/>
                        <path d="M25,75 C5,55 10,25 45,15 C30,35 40,60 25,75 Z" fill="#22c55e"/>
                    </svg>
                    <div class="hiwa-text">HIWA</div>
                    <div class="hiwa-subtext">H o s p i t a l</div>
                </div>
            </div>

            <!-- Medical Notice Text -->
            <div class="notice-text-box">
                <strong>هیمۆفیلیا :</strong> نەخۆشییەکی بۆماوەیی خوێنبەربوونه لەکاتی هەر جۆره برینداریەک خوێنی ناوەستێت ڕۆژانه پێویستی بە دەرز ی تایبەت هەیە، خاوەنپێداویستی تایبەتن
            </div>

            <!-- Curved Red Footer Banner -->
            <div class="back-footer-banner">
                <div class="back-footer-ckb">تکایه لەکاتی هەر ڕووداوێکی نەخوازراو ئەم کەسه بگەیەنە شوێنی چاره‌سه‌ر</div>
                <div class="back-footer-eng">Please in any unwanted accident transfer to selected hospital</div>
            </div>
        </div>
    </div>

</body>
</html>
