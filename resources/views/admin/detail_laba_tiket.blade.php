@extends('admin.master')
@section('title', 'Laba Tiket')
@section('labaActive', 'active')
@section('laporanActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <h5 class="mb-0 fs-4">Laba Tiket</h5>
                <div class="d-flex align-items-center gap-2">
                    <!-- Tempat tombol export DataTables -->
                    <div id="exportButtons"></div>

                </div>

            </div>

            <div class="table-responsive text-nowrap">
                <table class="table" id="detailTiketTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Tiket</th>
                            <th>Kasir</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                            <th>Laba</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @php
                            $totalSubtotal = 0;
                            $totalLaba = 0;
                        @endphp

                        @foreach ($detail as $i => $row)
                            @php
                                $totalSubtotal += $row->subtotal;
                                $totalLaba += $row->subtotal;
                            @endphp

                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $row->jenis_tiket }}</td>
                                <td>{{ $row->kasir }}</td>
                                <td>{{ $row->jumlah }}</td>
                                <td>Rp {{ number_format($row->harga_satuan, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge bg-info">
                                        Rp {{ number_format($row->subtotal, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-success">
                                        Rp {{ number_format($row->subtotal, 0, ',', '.') }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    @php
                        $labaBersih = $totalLaba - $kembalianTiket;
                    @endphp

                    <tfoot class="">
                        <tr class="fw-bold">
                            <th colspan="5" class="text-end">TOTAL</th>
                            <th>Rp {{ number_format($totalSubtotal, 0, ',', '.') }}</th>
                            <th>Rp {{ number_format($totalLaba, 0, ',', '.') }}</th>
                        </tr>

                        {{-- KEMBALIAN --}}
                        <tr>
                            <th colspan="6" class="text-end text-warning">KEMBALIAN TIKET</th>
                            <th class="text-warning">
                                - Rp {{ number_format($kembalianTiket, 0, ',', '.') }}
                            </th>
                        </tr>

                        {{-- LABA BERSIH --}}
                        <tr class="table-success fw-bold">
                            <th colspan="6" class="text-end">LABA BERSIH</th>
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
                let table = $('#detailTiketTable').DataTable({
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

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                console.log('✅ Script SweetAlert aktif (pakai class .form-hapus)');

                const forms = document.querySelectorAll('.form-hapus');
                console.log('Jumlah form hapus ditemukan:', forms.length);

                forms.forEach(form => {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        console.log('Form hapus diklik!');

                        Swal.fire({
                            title: 'Yakin hapus data ini?',
                            text: 'Data yang dihapus tidak bisa dikembalikan!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor: '#d33',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });

                // ✅ Notifikasi tambah berhasil
                @if (session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 2000
                    });
                @endif

                // ✅ Notifikasi tambah berhasil
                @if (session('updated'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('updated') }}',
                        showConfirmButton: false,
                        timer: 2000
                    });
                @endif

                // ✅ Notifikasi hapus berhasil
                @if (session('deleted'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Dihapus!',
                        text: '{{ session('deleted') }}',
                        showConfirmButton: false,
                        timer: 2000
                    });
                @endif
            });
        </script>

        <script>
            document.querySelectorAll('.btn-hadir').forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;

                    Swal.fire({
                        title: 'Konfirmasi Kehadiran',
                        text: 'Apakah pelanggan benar-benar hadir?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Hadir',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            fetch(`{{ url('admin/booking') }}/${id}/hadir`, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    }
                                })
                                .then(res => res.json())
                                .then(() => {
                                    Swal.fire('Berhasil', 'Status diubah menjadi Hadir', 'success')
                                        .then(() => location.reload());
                                });
                        }
                    });
                });
            });
        </script>
    @endpush

@endsection
