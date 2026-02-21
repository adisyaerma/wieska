<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Pengeluaran #{{ $data->id }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 13px;
            line-height: 1.4;
            color: #000;
            margin: 0;
            padding: 0;
        }

        .struk {
            width: 80mm;
            padding: 10px 8px;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .bold {
            font-weight: bold;
        }

        hr {
            border: 0;
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 5px 0;
        }

        th,
        td {
            padding: 2px 0;
            font-size: 13px;
        }

        th {
            text-align: left;
        }

        td {
            text-align: right;
        }

        .logo {
            max-width: 60px;
            margin-bottom: 5px;
        }

        .title {
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 2px;
        }

        .address,
        .phone {
            font-size: 12px;
            margin: 0;
        }
    </style>
</head>

<body>
    <div class="struk">
        <div class="center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Wieska" class="logo">
            <div class="title">KOLAM RENANG WIESKA</div>
            <p class="address">Kebonsinyo - Tegalasri RT/RW 3/6</p>
            <p class="phone">Telp. +62 851-5657-3718</p>
            <hr>
            <p class="bold">STRUK PENGELUARAN</p>
        </div>

        <p>ID: {{ $data->id }}</p>
        <p>Tanggal: {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d/m/Y H:i') }}</p>
        <p>Jenis: {{ $data->jenis_pengeluaran }}</p>
        <hr>

        {{-- GAJI --}}
        @if($data->jenis_pengeluaran === 'Gaji')
            <table>
                <tr>
                    <th>Karyawan</th>
                    <td class="right">{{ $data->nama_karyawan ?? $data->nama }}</td>
                </tr>
                <tr>
                    <th>Gaji Pokok</th>
                    <td class="right">Rp {{ number_format($data->gaji_pokok, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Potongan</th>
                    <td class="right">Rp {{ number_format($data->potongan, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Bonus</th>
                    <td class="right">Rp {{ number_format($data->bonus, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Total Gaji</th>
                    <td class="right bold">Rp
                        {{ number_format($data->gaji_pokok - $data->potongan + $data->bonus, 0, ',', '.') }}</td>
                </tr>
            </table>
        @endif

        {{-- HUTANG --}}
        @if($data->jenis_pengeluaran === 'Hutang')
            <table>
                <tr>
                    <th>Pihak</th>
                    <td class="right">{{ $data->pihak_hutang ?? $data->pihak }}</td>
                </tr>
                <tr>
                    <th>Total Hutang</th>
                    <td class="right">Rp {{ number_format($data->total_hutang, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Dibayarkan</th>
                    <td class="right bold">Rp {{ number_format($data->nominal_pengeluaran, 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th>Status Hutang</th>
                    <td class="right">{{ $data->status_hutang ?? $data->status }}</td>
                </tr>
            </table>
        @endif

        {{-- OPERASIONAL / LAINNYA --}}
        @if($data->jenis_pengeluaran !== 'Gaji' && $data->jenis_pengeluaran !== 'Hutang')
            <table>
                <tr>
                    <th>Nama Pengeluaran</th>
                    <td class="right">{{ $data->jenis_pengeluaran }}</td>
                </tr>
                <tr>
                    <th>Tujuan</th>
                    <td class="right">{{ $data->tujuan_pengeluaran ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Nominal</th>
                    <td class="right bold">Rp {{ number_format($data->nominal_pengeluaran, 0, ',', '.') }}</td>
                </tr>
            </table>
        @endif

        <hr>
        <div class="center">
            <p>Terima kasih atas perhatiannya</p>
        </div>
    </div>

    <script>
        // Auto print
        window.onload = function () {
            window.print();
        }
    </script>
</body>

</html>