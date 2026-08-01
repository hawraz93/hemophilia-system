<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <title>چاپکردنی بەڵگەنامە - {{ $document->title }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
        }
        body {
            font-family: 'Segoe UI', Tahoma, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: flex-start;
            min-height: 100vh;
        }
        .document-wrapper {
            width: 210mm;
            max-width: 100%;
            background: white;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            box-sizing: border-box;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .doc-header {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 12px;
            margin-bottom: 20px;
        }
        .doc-title {
            font-size: 18px;
            font-weight: 900;
            color: #991b1b;
        }
        .doc-meta {
            font-size: 11px;
            color: #475569;
            text-align: left;
            font-weight: bold;
        }
        .doc-preview-container {
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 10px;
        }
        .doc-preview-container img {
            max-width: 100%;
            max-height: 220mm;
            object-fit: contain;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .doc-preview-container iframe {
            width: 100%;
            height: 220mm;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
        }
        .print-btn {
            position: fixed;
            top: 20px;
            left: 20px;
            background-color: #dc2626;
            color: white;
            padding: 12px 24px;
            border-radius: 10px;
            border: none;
            font-weight: bold;
            font-size: 14px;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(220,38,38,0.3);
            z-index: 100;
        }
        @media print {
            .print-btn { display: none !important; }
            body { padding: 0; background: white; }
            .document-wrapper { box-shadow: none; padding: 0; border-radius: 0; }
        }
    </style>
</head>
<body>

    <button onclick="window.print()" class="print-btn">🖨️ چاپکردنی بەڵگەنامە (Print)</button>

    <div class="document-wrapper">
        <div class="doc-header">
            <div>
                <div class="doc-title">{{ $document->title }}</div>
                <div style="font-size: 12px; color: #475569; font-weight: bold; mt-1">
                    نەخۆش: {{ $document->patient?->full_name }} (کۆد: {{ $document->patient?->patient_code }})
                </div>
            </div>
            <div class="doc-meta">
                کۆمەڵەی هیمۆفیلیای کوردستان - لقی سلێمانی<br>
                جۆری بەڵگەنامە: {{ $document->document_type?->label() }}<br>
                بەرواری چاودان: {{ date('Y/m/d') }}
            </div>
        </div>

        <div class="doc-preview-container">
            @php
                $ext = strtolower(pathinfo($document->file_path, PATHINFO_EXTENSION));
                $isImage = in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg']);
            @endphp

            @if($isImage)
                <img src="{{ Storage::url($document->file_path) }}" alt="{{ $document->title }}" />
            @else
                <iframe src="{{ Storage::url($document->file_path) }}"></iframe>
            @endif
        </div>
    </div>

    <script>
        window.addEventListener('load', () => {
            // Auto trigger print prompt if opened for printing
            if (window.location.search.includes('autoprint=1')) {
                window.print();
            }
        });
    </script>
</body>
</html>
