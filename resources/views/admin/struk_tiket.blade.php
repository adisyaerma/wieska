<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <title>Cetak Tiket Wieska</title>
    <style>
        /* ================= PRINT SETTINGS ================= */
        @page {
            size: 58mm auto;
            margin: 0;
        }

        @media print {

            html,
            body {
                width: 58mm;
                margin: 0;
                padding: 0;
            }

            .pagebreak {
                page-break-after: always;
            }

            .btn-kembali {
                display: none;
            }
        }

        /* ================= GENERAL ================= */
        * {
            box-sizing: border-box;
        }

        body {
            font-family: "Calibri", "Helvetica", Arial, sans-serif;
            font-size: 9pt;
            color: #000;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
        }

        .paper {
            width: 52mm;
            margin: 0 auto;
            padding: 4px 2mm 6px 2mm;
        }

        /* ================= HEADER ================= */
        .header {
            text-align: center;
            margin-bottom: 4px;
        }

        .logo {
            display: inline-block;
            width: 40px;
            height: 40px;
            object-fit: contain;
            margin-bottom: 2px;
        }

        .title {
            font-weight: bold;
            font-size: 11pt;
            margin: 0;
        }

        .address,
        .phone {
            font-size: 8pt;
            margin: 0;
            line-height: 1.1;
        }

        /* ================= SEPARATOR ================= */
        hr {
            border: none;
            border-top: 1px solid #000;
            margin: 4px 0;
        }

        .dotted {
            border-top: 1px dotted #000;
            margin: 5px 0;
        }

        /* ================= DETAIL AREA ================= */
        .details {
            font-size: 8.5pt;
            line-height: 1.2;
            margin-top: 2px;
        }

        .details p {
            margin: 2px 0;
        }

        .details strong {
            display: inline-block;
            width: 20mm;
        }

        /* ================= MESSAGE ================= */
        .message {
            margin: 6px 0 4px 0;
            font-size: 8pt;
            font-style: italic;
            line-height: 1.2;
        }

        /* ================= BARCODE BOX ================= */
        .barcode-strip {
            width: 100%;
            height: 20px;
            background: repeating-linear-gradient(90deg,
                    #000 0px,
                    #000 2px,
                    transparent 2px,
                    transparent 4px);
            margin: 6px 0 2px 0;
        }

        /* ================= FOOTER ================= */
        .footer {
            text-align: center;
            font-size: 7.5pt;
            margin-top: 6px;
        }

        .footer p {
            margin: 2px 0;
        }

        .btn-kasir {
            display: inline-flex;
            align-items: center;
            gap: 8px;

            padding: 10px 20px;
            background-color: #989b9d;
            /* abu Bootstrap */
            color: #ffffff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;

            border-radius: 6px;
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
            transition: all 0.25s ease;
        }

        /* Hover effect */
        .btn-kasir:hover {
            background-color: #5a6268;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
            color: #ffffff;
        }

        /* Active / klik */
        .btn-kasir:active {
            transform: translateY(0);
            box-shadow: 0 3px 8px rgba(0, 0, 0, 0.15);
        }

        /* Fokus (aksesibilitas) */
        .btn-kasir:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(108, 117, 125, 0.4);
        }
    </style>
</head>

<body onload="window.print()">

    @foreach ($tiketMasukList as $t)
        <div class="paper">
            <div class="header">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Wieska" class="logo">
                <div class="title">KOLAM RENANG WIESKA</div>
                <p class="address">Kebonsinyo - Tegalasri RT/RW 3/6</p>
                <p class="phone">Telp. +62 851-5657-3718</p>
            </div>

            <hr>

            <div class="details">
                <p><strong>No. Tiket</strong> {{ $t['no_tiket'] }}</p>
                <p><strong>Tanggal</strong> {{ \Carbon\Carbon::parse($t['tanggal'])->format('d M Y') }}</p>
                <p><strong>Harga</strong> Rp{{ number_format($t['harga'], 0, ',', '.') }}</p>
                <p><strong>Kasir</strong> {{ $t['kasir'] }}</p>
            </div>

            <div class="dotted"></div>

            <div class="message">
                Nikmati hari Anda dengan suasana segar dan kenyamanan di Kolam Renang Wieska!
            </div>

            <div class="dotted"></div>

            <div class="footer">

                <!-- BARCODE AREA -->
                <div class="barcode-strip"></div>

                <p>IG: @wieskapool</p>
                <p>Terima kasih atas kunjungan Anda 💧</p>
            </div>
        </div>

        @if (!$loop->last)
            <div class="pagebreak"></div>
        @endif
    @endforeach

    <div class="text-center btn-kembali" style="text-align: center; margin-top: 25px;" >
        <a href="{{ route('kasir_tiket') }}" class="btn btn-secondary btn-sm btn-kasir">
            Kembali ke Kasir
        </a>
    </div>

</body>

</html>
