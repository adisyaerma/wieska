<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Pembelian</title>
    <style>
        body {
            width: 58mm;
            font-family: monospace;
            font-size: 12px;
            margin: 0;
            padding: 0 4mm;
            /* ➜ Tambah margin kanan kiri */
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            vertical-align: top;
        }

        img.logo {
            max-width: 40px;
            display: block;
            margin: 0 auto;
        }

        .info-table td:first-child {
            width: 30%;
        }

        .info-table td {
            padding: 2px 0;
        }

        .right {
            text-align: right;
        }
    </style>

</head>

<body onload="window.print()">
    <div class="center">
        {{-- Logo --}}
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
        <h3>CAFE WIESKA</h3>
        <p>Kebonsinyo - Tegalasri<br>RT/RW 3/6</p>
    </div>

    <div class="line"></div>

    {{-- Informasi Umum --}}
    <table class="info-table">
        <tr>
            <td>Tanggal</td>
            <td>: {{ \Carbon\Carbon::parse($cafe->tanggal)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>: {{ $cafe->karyawan->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Pelanggan</td>
            <td>: {{ $cafe->nama_pelanggan ?? '-' }}</td>
        </tr>
    </table>

    <div class="line"></div>

    {{-- Daftar Pesanan --}}
    <table>
        @foreach($cafe->details as $d)
            <tr>
                <td colspan="2">{{ $d->menu->stokBarang->nama_barang }}</td>
            </tr>
            <tr>
                <td>{{ $d->jumlah }} x Rp {{ number_format($d->menu->harga_jual, 0, ',', '.') }}</td>
                <td class="right">Rp {{ number_format($d->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    {{-- Total --}}
    <table>
        <tr>
            <td><b>Total</b></td>
            <td class="right"><b>Rp {{ number_format($cafe->subtotal, 0, ',', '.') }}</b></td>
        </tr>
        <tr>
            <td>Dibayarkan</td>
            <td class="right">Rp {{ number_format($cafe->dibayarkan, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kembalian</td>
            <td class="right">Rp {{ number_format($cafe->kembalian, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <div class="center">
        <p>~ Terima Kasih ~</p>
    </div>
</body>

</html>