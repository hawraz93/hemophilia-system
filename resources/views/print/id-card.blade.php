<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>کارتی ئەندامێتی - {{ $patient->full_name }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f1f5f9;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-h: 100vh;
            margin: 0;
            padding: 20px;
        }
        .print-btn {
            background-color: #dc2626;
            color: white;
            padding: 10px 24px;
            border-radius: 8px;
            border: none;
            font-weight: bold;
            cursor: pointer;
            margin-bottom: 30px;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .id-card {
            width: 85.6mm;
            height: 53.98mm;
            background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
            border-radius: 12px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border: 1px solid #cbd5e1;
            position: relative;
            box-sizing: border-box;
            padding: 12px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 6px;
        }
        .card-title {
            font-size: 11px;
            font-weight: 900;
            color: #991b1b;
        }
        .card-subtitle {
            font-size: 8px;
            color: #64748b;
        }
        .card-body {
            display: flex;
            gap: 10px;
            align-items: center;
            margin-top: 4px;
        }
        .photo-box {
            width: 60px;
            height: 65px;
            background-color: #e2e8f0;
            border-radius: 8px;
            border: 2px solid #dc2626;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #64748b;
            overflow: hidden;
        }
        .photo-box img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .info-list {
            font-size: 9.5px;
            line-height: 1.4;
            color: #1e293b;
        }
        .info-list strong {
            color: #0f172a;
        }
        .card-footer {
            background-color: #dc2626;
            color: white;
            margin: -12px;
            margin-top: 4px;
            padding: 4px 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 8px;
            font-weight: bold;
        }
        @media print {
            .print-btn { display: none; }
            body { background: white; }
            .id-card { box-shadow: none; border: 1px solid #94a3b8; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">🖨️ چاپکردنی کارتی ناسنامە</button>

    <div class="card-container">
        <!-- Front Side -->
        <div class="id-card">
            <div class="card-header">
                <div>
                    <div class="card-title">کۆمەڵەی هیمۆفیلیای کوردستان</div>
                    <div class="card-subtitle">لقی سلێمانی — کارتی ئەندامێتی</div>
                </div>
                <div style="font-size: 10px; font-weight: 900; color: #dc2626;">KHA</div>
            </div>

            <div class="card-body">
                <div class="photo-box">
                    @if($patient->photo)
                        <img src="{{ Storage::url($patient->photo) }}" alt="Photo" />
                    @else
                        {{ mb_substr($patient->first_name, 0, 1) }}
                    @endif
                </div>

                <div class="info-list">
                    <div>ناو: <strong>{{ $patient->full_name }}</strong></div>
                    <div>کۆدی نەخۆش: <strong>{{ $patient->patient_code }}</strong></div>
                    <div>ژمارەی ئەندامێتی: <strong>{{ $patient->membership_number ?? '—' }}</strong></div>
                    <div>گروپی خوێن: <strong style="color: #dc2626;">{{ $patient->blood_group?->value ?? '—' }}</strong></div>
                </div>
            </div>

            <div class="card-footer">
                <span>هیمۆفیلیا {{ $patient->hemophilia_type?->label() }} ({{ $patient->severity?->label() }})</span>
                <span>تەلەفۆن: {{ $patient->phone }}</span>
            </div>
        </div>

        <!-- Back Side -->
        <div class="id-card" style="background: #0f172a; color: white;">
            <div style="text-align: center; border-bottom: 1px solid #334155; padding-bottom: 4px;">
                <div style="font-size: 10px; font-weight: bold; color: #f87171;">زانیاری لە کاتی فریاگوزاری بەپەلەدا</div>
            </div>

            <div style="font-size: 9px; line-height: 1.5; color: #cbd5e1; padding: 4px 0;">
                <div>• نەخۆش هەڵگری هیمۆفیلیاست (کێشەی خوێن مەین)</div>
                <div>• پێویستی بە دەرزی Factor {{ $patient->hemophilia_type?->value === 'B' ? 'IX' : 'VIII' }} هەیە لە کاتی خوێنڕشتندا.</div>
                <div>• ژمارەی ئەندامێتی: <strong>{{ $patient->membership_number }}</strong></div>
                <div>• ژمارەی خێزان: <strong>{{ $patient->secondary_phone ?? $patient->phone }}</strong></div>
            </div>

            <div style="text-align: center; background: #dc2626; margin: -12px; padding: 6px; font-size: 8px; font-weight: bold;">
                کۆمەڵەی هیمۆفیلیای کوردستان - لقی سلێمانی
            </div>
        </div>
    </div>

</body>
</html>
