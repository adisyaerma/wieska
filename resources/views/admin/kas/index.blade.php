@extends('admin.master')
@section('title', 'Laba')
@section('labaActive', 'active')
@section('laporanActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <h5 class="mb-0 fs-4">Laba</h5>
                <div class="d-flex align-items-center gap-2">
                    <!-- Tempat tombol export DataTables -->
                    <div id="exportButtons"></div>

                </div>

            </div>

            <div class="table-responsive text-nowrap p-3">

                <!-- Baris atas: Filter tanggal (kiri) + Search (kanan bawaan DataTables) -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2 ms-3" id="filterContainer">
                        <label for="minDate" class="form-label mb-0 fw-medium">Dari:</label>
                        <input type="date" id="minDate" class="form-control form-control-sm" style="width: 160px;">
                        <label for="maxDate" class="form-label mb-0 fw-medium">Sampai:</label>
                        <input type="date" id="maxDate" class="form-control form-control-sm" style="width: 160px;">
                    </div>

                    <!-- Tempat Search DataTables -->
                    <div id="tableSearchContainer"></div>
                </div>
                <table class="table" id="labaTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Kas Masuk</th>
                            <th>Kas Keluar</th>
                            <th>Saldo Akhir</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @foreach ($dataKas as $i => $kas)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ \Carbon\Carbon::parse($kas['tanggal'])->format('d-m-Y') }}</td>
                                <td class="text-success">Rp {{ number_format($kas['kas_masuk'], 0, ',', '.') }}</td>
                                <td class="text-danger">Rp {{ number_format($kas['kas_keluar'], 0, ',', '.') }}</td>
                                <td class="fw-bold">Rp {{ number_format($kas['saldo_akhir'], 0, ',', '.') }}</td>

                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                        </button>

                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="">
                                                <i class="icon-base bx bx-receipt me-1"></i>
                                                Detail Tiket
                                            </a>

                                            <a class="dropdown-item" href="">
                                                <i class="icon-base bx bx-coffee me-1"></i>
                                                Detail Cafe
                                            </a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
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

        <script>~
                $(document).ready(function () {
                    // 🔹 Deteksi otomatis kolom tanggal
                    let dateColIndex = 1;
                    $('#labaTable thead th').each(function (i) {
                        const txt = $(this).text().toLowerCase();
                        if (txt.includes('tgl') || txt.includes('tanggal')) {
                            dateColIndex = i;
                            return false;
                        }
                    });

                    // 🔹 Fungsi bantu
                    function stripTags(html) {
                        return $('<div/>').html(html).text().trim();
                    }

                    function parseCellDate(s) {
                        if (!s) return null;
                        s = s.trim();
                        const match = s.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
                        if (match) return new Date(match[3], match[2] - 1, match[1]);
                        const dt = new Date(s);
                        return isNaN(dt) ? null : new Date(dt.getFullYear(), dt.getMonth(), dt.getDate());
                    }

                    function parseInputDate(val) {
                        if (!val) return null;
                        const parts = val.split('-');
                        return new Date(parts[0], parts[1] - 1, parts[2]);
                    }

                    // 🔹 Filter berdasarkan range tanggal
                    $.fn.dataTable.ext.search.push(function (settings, data) {
                        const min = parseInputDate($('#minDate').val());
                        const max = parseInputDate($('#maxDate').val());
                        const dateText = stripTags(data[dateColIndex]);
                        const date = parseCellDate(dateText);
                        if (!date) return true;
                        if (min && date < min) return false;
                        if (max && date > max) return false;
                        return true;
                    });

                    // 🔹 Inisialisasi DataTable
                    let table = $('#labaTable').DataTable({
                        dom: 'Bfrtip',
                        buttons: [{
                            extend: 'excel',
                            text: '<i class="bx bx-file me-1"></i> Excel',
                            className: 'btn btn-sm btn-success',
                            exportOptions: {
                                modifier: {
                                    search: 'applied'
                                }
                            }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="bx bx-file me-1"></i> PDF',
                            className: 'btn btn-sm btn-danger',
                            exportOptions: {
                                modifier: {
                                    search: 'applied'
                                }
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="bx bx-printer me-1"></i> Print',
                            className: 'btn btn-sm btn-secondary',
                            exportOptions: {
                                modifier: {
                                    search: 'applied'
                                }
                            }
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

                        // 🔹 Hitung total subtotal
                        footerCallback: function (row, data, start, end, display) {
                            let api = this.api();

                            // Kolom Subtotal = index ke-4 (bukan 5)
                            let total = api
                                .column(4, {
                                    search: 'applied'
                                })
                                .data()
                                .reduce((sum, val) => {
                                    // Bersihkan HTML
                                    let text = $('<div>').html(val).text().trim();

                                    // Ambil angka dari teks (contoh: "Rp 25.000" → "25000")
                                    let cleaned = text.replace(/[^\d]/g, '');
                                    let num = cleaned ? parseFloat(cleaned) : 0;

                                    return sum + num;
                                }, 0);

                            // Format angka menjadi Rp dengan pemisah ribuan
                            let formatted = new Intl.NumberFormat('id-ID').format(total);
                            $(api.column(4).footer()).html('Rp ' + formatted);
                        },

                        initComplete: function () {
                            $("#labaTable_filter").appendTo("#tableSearchContainer");
                            $("#labaTable_filter label").addClass("mb-0");
                        }
                    });

                    // 🔹 Tempatkan tombol export di kanan atas
                    table.buttons().container().appendTo('#exportButtons');

                    // 🔹 Jalankan filter saat tanggal berubah
                    $('#minDate, #maxDate').on('change', function () {
                        table.draw();
                    });
                });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                console.log('✅ Script SweetAlert aktif (pakai class .form-hapus)');

                const forms = document.querySelectorAll('.form-hapus');
                console.log('Jumlah form hapus ditemukan:', forms.length);

                forms.forEach(form => {
                    form.addEventListener('submit', function (e) {
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
                btn.addEventListener('click', function () {
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