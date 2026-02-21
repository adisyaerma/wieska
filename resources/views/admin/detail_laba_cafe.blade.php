@extends('admin.master')
@section('title', 'Laba Cafe')
@section('labaActive', 'active')
@section('laporanActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <h5 class="mb-0 fs-4">Laba Cafe</h5>
                <div class="d-flex align-items-center gap-2">
                    <!-- Tempat tombol export DataTables -->
                    <div id="exportButtons"></div>

                </div>

            </div>

            @php
                $totalOmzet = 0;
                $totalModal = 0;
                $totalLaba = 0;
            @endphp

            <div class="table-responsive text-nowrap">
                <table class="table " id="detailCafeTable">
                    <thead class="">
                        <tr>
                            <th>No</th>
                            <th>Nama Menu</th>
                            <th>Kasir</th>
                            <th>Jumlah</th>
                            <th>Harga Jual</th>
                            <th>Harga Beli</th>
                            <th>Omzet</th>
                            <th>Modal</th>
                            <th>Laba</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($detail as $i => $row)
                            @php
                                $omzet = $row->subtotal;
                                $modal = $row->jumlah * $row->harga_beli;
                                $laba = $omzet - $modal;

                                $totalOmzet += $omzet;
                                $totalModal += $modal;
                                $totalLaba += $laba;
                            @endphp
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row->nama_barang }}</td>
                                <td>{{ $row->kasir }}</td>
                                <td>{{ $row->jumlah }}</td>
                                <td>Rp {{ number_format($row->harga_jual, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($row->harga_beli, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        Rp {{ number_format($omzet, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">
                                        Rp {{ number_format($modal, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $laba >= 0 ? 'bg-success' : 'bg-danger' }}">
                                        Rp {{ number_format($laba, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @php
                        $labaBersih = $totalLaba - $kembalianCafe;
                    @endphp

                    {{-- TOTAL --}}
                    <tfoot>
                        <tr>
                            <th colspan="6" class="text-end">TOTAL</th>
                            <th>Rp {{ number_format($totalOmzet, 0, ',', '.') }}</th>
                            <th>Rp {{ number_format($totalModal, 0, ',', '.') }}</th>
                            <th>Rp {{ number_format($totalLaba, 0, ',', '.') }}</th>
                        </tr>

                        {{-- KEMBALIAN --}}
                        <tr>
                            <th colspan="8" class="text-end text-warning">KEMBALIAN CAFE</th>
                            <th class="text-warning">
                                - Rp {{ number_format($kembalianCafe, 0, ',', '.') }}
                            </th>
                        </tr>

                        {{-- LABA BERSIH --}}
                        <tr class="table-success fw-bold">
                            <th colspan="8" class="text-end">LABA BERSIH</th>
                            <th>
                                Rp {{ number_format($labaBersih, 0, ',', '.') }}
                            </th>
                        </tr>
                    </tfoot>

                </table>
            </div>


        </div>

        <!--/ Basic Bootstrap Table -->
    </div>

    @push('scripts')
        <style>
            /* Biar search box lebih rapi */
            div.dataTables_filter {
                padding: 0.75rem 1rem;
            }

            /* Biar tulisan "Showing x to y" lebih rapi */
            div.dataTables_info {
                padding: 0.75rem 1rem;
            }

            /* Biar pagination (Previous/Next) ada spasi */
            div.dataTables_paginate {
                padding: 0.75rem 1rem;
            }

            /* Biar dropdown "Show entries" juga ada padding */
            div.dataTables_length {
                padding: 0.75rem 1rem;
            }
        </style>

        <script>
            function formatRupiah(el, suffix = '') {
                const angka = el.value.replace(/\D/g, '');
                el.value = angka ? 'Rp ' + new Intl.NumberFormat('id-ID').format(angka) : '';
                document.getElementById('harga_value' + suffix).value = angka;
            }
        </script>

        <script>
            $(document).ready(function() {
                let table = $('#detailCafeTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [{
                            extend: 'excel',
                            text: '<i class="bx bx-file me-1"></i> Excel',
                            className: 'btn btn-sm btn-success'
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="bx bx-file me-1"></i> PDF',
                            className: 'btn btn-sm btn-danger'
                        },
                        {
                            extend: 'print',
                            text: '<i class="bx bx-printer me-1"></i> Print',
                            className: 'btn btn-sm btn-secondary'
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="bx bx-show me-1"></i> Kolom',
                            className: 'btn btn-sm btn-info'
                        }
                    ],
                    order: [
                        [0, 'asc']
                    ],
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "Semua"]
                    ],
                });

                // Pindahkan tombol ke dalam div di card-header
                table.buttons().container().appendTo('#exportButtons');
            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

@endsection
