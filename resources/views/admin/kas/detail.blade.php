@extends('admin.master')
@section('title', 'Detail Kas')@section('kasActive', 'active')@section('laporanActive', 'active open')@section('isi')
    <div class="container-xxl container-p-y">

        <!-- ================= HEADER ================= -->
        <div class="card mb-4">
            <div class="card-body d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    Detail Kas
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}
                    </small>
                </h4>
                <a href="{{ route('kas.index') }}" class="btn btn-secondary btn-sm">
                    <i class="bx bx-arrow-back"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card-header p-0">
            <ul class="nav nav-tabs nav-tabs-solid" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#tabKasMasuk">
                        <i class="bx bx-log-in m-1"></i>
                        Kas Masuk
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#tabKasKeluar">
                        <i class="bx bx-log-out m-1"></i>
                        Kas Keluar
                    </button>
                </li>
            </ul>
        </div>


        <div class="tab-content">

            <!-- ================= TAB KAS MASUK ================= -->
            <div class="tab-pane fade show active" id="tabKasMasuk">
                <div class="card">
                    <div class="card-body">

                        <table class="table table-bordered" id="kasMasukTable">
                            <thead class="">
                                <tr>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Sumber</th>
                                    <th class="text-center">Keterangan</th>
                                    <th class="text-center">Petugas</th>
                                    <th class="text-center">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($kasMasuk as $row)
                                    <tr>
                                        <td class="text-center">{{ \Carbon\Carbon::parse($row['tanggal'])->format('d-m-Y') }}
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success">{{ $row['sumber'] }}</span>
                                        </td>
                                        <td class="text-center">{{ $row['keterangan'] }}</td>
                                        <td class="text-center">{{ $row['petugas'] }}</td>
                                        <td class="text-end">
                                            Rp {{ number_format($row['total'], 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="fw-bold">
                                <tr>
                                    <td colspan="4" class="text-end">Total</td>
                                    <td class="text-end"></td>
                                </tr>
                            </tfoot>
                        </table>

                    </div>
                </div>
            </div>

            <!-- ================= TAB KAS KELUAR ================= -->
            <div class="tab-pane fade" id="tabKasKeluar">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered" id="kasKeluarTable">
                            <thead>
                                <tr>
                                    <th class="text-center">Jenis</th>
                                    <th class="text-center">Tujuan</th>
                                    <th class="text-center">Petugas</th>
                                    <th class="text-center">Nominal</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($kasKeluar as $row)
                                    <tr>
                                        <td class="text-center">{{ $row->jenis_pengeluaran }}</td>
                                        <td class="text-center">{{ $row->jenis_pengeluaran }}</td>
                                        <td class="text-center">{{ $row->nama }}</td>
                                        <td class="text-end">
                                            Rp {{ number_format($row->nominal_pengeluaran, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                            <!-- TOTAL -->
                            <tfoot class="fw-bold">
                                <tr>
                                    <td colspan="3" class="text-end">Total</td>
                                    <td class="text-end"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
        <style>
            /* TAB SOLID STYLE */
            .nav-tabs-solid {
                border-bottom: 1px solid #dee2e6;
                background: #f8f9fa;
            }

            .nav-tabs-solid .nav-link {
                border: none;
                border-radius: 0;
                color: #6c757d;
                padding: 12px 18px;
                font-weight: 500;
            }

            .nav-tabs-solid .nav-link.active {
                background: #ffffff;
                color: #0d6efd;
                border-bottom: 3px solid #0d6efd;
            }

            .tab-content {
                background: #ffffff;
            }
        </style>
        <style>
            div.dataTables_filter,
            div.dataTables_info,
            div.dataTables_paginate,
            div.dataTables_length {
                padding: .75rem 1rem;
            }
        </style>

        <script>
            $(document).ready(function () {

                let masuk = $('#kasMasukTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excel',
                            className: 'btn btn-sm btn-success',
                            text: '<i class="bx bx-file"></i> Excel'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-sm btn-danger',
                            text: '<i class="bx bx-file"></i> PDF'
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-sm btn-secondary',
                            text: '<i class="bx bx-printer"></i> Print'
                        },
                        {
                            extend: 'colvis',
                            className: 'btn btn-sm btn-info',
                            text: '<i class="bx bx-show"></i> Kolom'
                        }
                    ],
                    order: [[0, 'asc']],
                    footerCallback: function () {
                        let api = this.api();

                        let total = api
                            .column(4, { search: 'applied' })
                            .data()
                            .reduce(function (sum, val) {
                                let clean = $('<div>').html(val).text().replace(/[^\d]/g, '');
                                return sum + (parseInt(clean) || 0);
                            }, 0);

                        $(api.column(4).footer())
                            .html('Rp ' + new Intl.NumberFormat('id-ID').format(total));
                    }
                });

                masuk.buttons().container()
                    .appendTo('#kasMasukTable_wrapper .col-md-6:eq(0)');
            });
        </script>
        <script>
            $(document).ready(function () {

                let keluar = $('#kasKeluarTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excel',
                            className: 'btn btn-sm btn-success',
                            text: '<i class="bx bx-file"></i> Excel'
                        },
                        {
                            extend: 'pdf',
                            className: 'btn btn-sm btn-danger',
                            text: '<i class="bx bx-file"></i> PDF'
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-sm btn-secondary',
                            text: '<i class="bx bx-printer"></i> Print'
                        },
                        {
                            extend: 'colvis',
                            className: 'btn btn-sm btn-info',
                            text: '<i class="bx bx-show"></i> Kolom'
                        }
                    ],
                    order: [[0, 'asc']],
                    footerCallback: function () {
                        let api = this.api();

                        let total = api
                            .column(3, { search: 'applied' }) // kolom Nominal
                            .data()
                            .reduce(function (sum, val) {
                                let clean = $('<div>').html(val).text().replace(/[^\d]/g, '');
                                return sum + (parseInt(clean) || 0);
                            }, 0);

                        $(api.column(3).footer())
                            .html('Rp ' + new Intl.NumberFormat('id-ID').format(total));
                    }
                });

                keluar.buttons().container()
                    .appendTo('#kasKeluarTable_wrapper .col-md-6:eq(0)');
            });
        </script>
    @endpush
@endsection