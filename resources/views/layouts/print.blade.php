<!DOCTYPE html>
<html lang="ckb" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? config('org.name') }}</title>
    <link rel="stylesheet" href="{{ asset('fonts/vazirmatn.css') }}">
    <style>
        /* Content flows onto as many A4 pages as it needs — nothing is clipped. */
        @page { size: A4 portrait; margin: 10mm 12mm; }
        *, *::before, *::after { box-sizing: border-box; }
        html, body {
            margin: 0;
            padding: 0;
            background: #e2e8f0;
            color: #0f172a;
            font-family: 'Vazirmatn', 'Segoe UI', Tahoma, sans-serif;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            margin: 15px auto;
            padding: 10mm 12mm;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,0.12);
            position: relative;
            display: flex;
            flex-direction: column;
        }
        .page-body { flex: 1; position: relative; z-index: 1; }
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 95mm;
            opacity: 0.05;
            pointer-events: none;
            z-index: 0;
        }

        /* Letterhead */
        .letterhead {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            border-bottom: 2px solid #b91c1c;
            padding-bottom: 6px;
        }
        .letterhead-org { display: flex; align-items: center; gap: 10px; }
        .letterhead-logo { width: 21mm; height: 21mm; object-fit: contain; }
        .letterhead-org h1 { margin: 0; font-size: 17px; font-weight: 900; color: #b91c1c; line-height: 1.3; }
        .letterhead-org h2 { margin: 0; font-size: 12px; font-weight: 800; color: #1e293b; }
        .letterhead-org h3 { margin: 1px 0 0; font-size: 9px; font-weight: 700; color: #64748b; font-family: Arial, sans-serif; direction: ltr; text-align: right; }
        .letterhead-meta { text-align: left; font-size: 11px; font-weight: 700; color: #475569; line-height: 1.7; white-space: nowrap; }
        .letterhead-meta strong { color: #0f172a; }
        .letterhead-meta .ref { color: #b91c1c; }
        .letterhead-title { text-align: center; }
        .letterhead-title h1 { margin: 0; font-size: 16px; font-weight: 900; color: #b91c1c; }
        .letterhead-title h2 { margin: 2px 0 0; font-size: 10px; font-weight: 800; color: #1e293b; font-family: Arial, sans-serif; }

        /* Addressee: "To" and "Subject" always on two separate lines */
        .addressee { margin: 14px 0 12px; font-size: 14px; font-weight: 900; line-height: 2; }
        .addressee div { display: block; }
        .addressee .label { display: inline-block; min-width: 18mm; }

        .letter-text { font-size: 13.5px; line-height: 2; text-align: justify; white-space: pre-line; }
        .letter-text.no-pre { white-space: normal; }

        /* Signature block — stamp sits on top of the signature */
        .signature-row { display: flex; justify-content: flex-end; margin-top: 18px; break-inside: avoid; page-break-inside: avoid; }
        .signature-block { position: relative; width: 70mm; text-align: center; padding-top: 4mm; }
        .signature-img { height: 22mm; max-width: 55mm; object-fit: contain; display: block; margin: 0 auto -3mm; position: relative; z-index: 1; }
        .signature-stamp {
            position: absolute;
            width: 30mm;
            height: 30mm;
            object-fit: contain;
            top: -2mm;
            left: 50%;
            transform: translateX(-20%) rotate(-8deg);
            opacity: 0.85;
            mix-blend-mode: multiply;
            z-index: 2;
            pointer-events: none;
        }
        .signature-name { position: relative; z-index: 3; font-size: 13px; font-weight: 900; }
        .signature-title { position: relative; z-index: 3; font-size: 10px; font-weight: 700; color: #475569; }
        .signature-manual-space { height: 24mm; }

        .print-footer {
            margin-top: 10mm;
            border-top: 1px solid #cbd5e1;
            padding-top: 4px;
            text-align: center;
            font-size: 9.5px;
            font-weight: 700;
            color: #475569;
            line-height: 1.6;
        }

        /* Tables split across pages with the header repeated on each page */
        table.print-table { width: 100%; border-collapse: collapse; font-size: 10.5px; }
        table.print-table th, table.print-table td { border: 1px solid #cbd5e1; padding: 4px 6px; text-align: right; }
        table.print-table th { background: #f1f5f9; font-weight: 900; }
        table.print-table thead { display: table-header-group; }
        table.print-table tr { break-inside: avoid; page-break-inside: avoid; }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 4px 10px;
            margin: 10px 0;
            background: #f8fafc;
            border: 1px solid #cbd5e1;
            border-radius: 4px;
            padding: 6px 10px;
            font-size: 11px;
        }
        .section-title { font-size: 13px; font-weight: 900; color: #b91c1c; margin: 12px 0 6px; border-bottom: 1px solid #fecaca; padding-bottom: 3px; }

        .print-btn {
            position: fixed;
            top: 15px;
            left: 15px;
            background: #b91c1c;
            color: #fff;
            padding: 8px 18px;
            border-radius: 8px;
            border: none;
            font-family: inherit;
            font-weight: 800;
            font-size: 13px;
            cursor: pointer;
            box-shadow: 0 4px 14px rgba(185,28,28,0.35);
            z-index: 100;
        }

        @media print {
            html, body { background: #fff; }
            /* min-height keeps the address footer at the bottom of a single-page letter */
            .page { width: auto; min-height: 270mm; margin: 0; padding: 0; box-shadow: none; }
            .watermark { position: fixed; }
            .print-btn, .no-print { display: none !important; }
        }
        @stack('styles')
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-btn">🖨️ {{ $printLabel ?? 'چاپکردن (Print)' }}</button>

    <div class="page">
        @if($watermark ?? true)
            <img src="{{ asset(config('org.logo')) }}" class="watermark" alt="">
        @endif

        <div class="page-body">
            @yield('content')
        </div>

        <x-print.footer />
    </div>
</body>
</html>
