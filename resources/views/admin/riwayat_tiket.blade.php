@extends('admin.master')
@section('title', content: 'Riwayat Tiket')
@section('riwayatTiketActive', 'active')
@section('laporanActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <h5 class="mb-0 fs-4">Riwayat Pesanan Tiket</h5>

                <!-- Kanan: export dan tambah -->
                <div class="d-flex align-items-center gap-2">
                    <div id="exportButtons"></div>
                </div>
            </div>

            <div class="table-responsive text-nowrap p-3">
                <!-- Wrapper untuk filter + search -->
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <!-- Filter tanggal di kiri -->
                    <div class="d-flex align-items-center gap-2 ms-3" id="filterContainer">
                        <label for="minDate" class="form-label mb-0 fw-medium">Dari:</label>
                        <input type="date" id="minDate" class="form-control form-control-sm" style="width: 160px;">
                        <label for="maxDate" class="form-label mb-0 fw-medium">Sampai:</label>
                        <input type="date" id="maxDate" class="form-control form-control-sm" style="width: 160px;">
                    </div>

                    <!-- Tempat untuk search bawaan DataTables -->
                    <div id="tableSearchContainer"></div>
                </div>

                <!-- Tabel -->
                <div class="table-responsive text-nowrap">
                    <table class="table" id="pesananTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Nama Pelanggan</th>
                                <th>Kasir</th>
                                <th>Total Tiket</th>
                                <th>Subtotal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="table-border-bottom-0">
                            @foreach($tiket as $index => $row)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <span class="badge bg-label-primary me-1">
                                            {{ \Carbon\Carbon::parse($row->tanggal)->format('d/m/Y') }}
                                        </span>
                                    </td>
                                    <td>{{ $row->nama_pelanggan }}</td>
                                    <td>{{ $row->karyawan->nama ?? '-' }}</td>
                                    <td>{{ $row->details->sum('jumlah') }}</td>
                                    <td>{{ number_format($row->subtotal, 0, ',', '.') }}</td>
                                    <td>
                                        <div class="dropdown">
                                            <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                                data-bs-toggle="dropdown">
                                                <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <div class="dropdown-menu">
                                                <a href="{{ route('riwayat_tiket.detail', $row->id) }}" class="dropdown-item">
                                                    <i class='icon-base bx bx-clipboard me-1'></i> Detail
                                                </a>
                                                <form class="form-hapus" action="{{ route('riwayat_tiket.destroy', $row->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="icon-base bx bx-trash me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

                        <!-- Footer tabel untuk total -->
                        <tfoot>
                            <tr>
                                <th colspan="5" class="text-end">Total:</th>
                                <th id="totalSubtotal" class="text-start">Rp 0</th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>

        <!--/ Basic Bootstrap Table -->
    </div>

    @push('scripts')
        <style>
            div.dataTables_filter {
                padding: 0.75rem 1rem;
            }

            div.dataTables_info {
                padding: 0.75rem 1rem;
            }

            div.dataTables_paginate {
                padding: 0.75rem 1rem;
            }

            div.dataTables_length {
                padding: 0.75rem 1rem;
            }
        </style>

        <script>
            $(document).ready(function () {
                // cari index kolom tanggal secara otomatis (header mengandung 'tgl' atau 'tanggal')
                let dateColIndex = 1; // fallback
                $('#pesananTable thead th').each(function (i) {
                    const txt = $(this).text().toLowerCase();
                    if (txt.includes('tgl') || txt.includes('tanggal') || txt.includes('date')) {
                        dateColIndex = i;
                        return false;
                    }
                });

                // bersihkan HTML dari cell (ambil text-nya)
                function stripTags(html) {
                    return $('<div/>').html(html).text().trim();
                }

                // parse tanggal sel (coba beberapa format, utama dd/mm/yyyy)
                function parseCellDate(s) {
                    if (!s) return null;
                    s = s.trim();
                    s = s.split(' ')[0]; // ambil hanya tanggal

                    let dmy = s.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
                    if (dmy) return new Date(dmy[3], dmy[2] - 1, dmy[1]);

                    let iso = s.match(/^(\d{4})[\/\-](\d{1,2})[\/\-](\d{1,2})$/);
                    if (iso) return new Date(iso[1], iso[2] - 1, iso[3]);

                    let human = s.match(/^(\d{1,2})\s+([A-Za-z]+)\s+(\d{4})$/);
                    if (human) {
                        const months = ['jan', 'feb', 'mar', 'apr', 'may', 'jun', 'jul', 'aug', 'sep', 'oct', 'nov', 'dec'];
                        const m = months.findIndex(mn => human[2].toLowerCase().startsWith(mn));
                        if (m >= 0) return new Date(human[3], m, human[1]);
                    }

                    let dt = new Date(s);
                    return isNaN(dt.getTime()) ? null : new Date(dt.getFullYear(), dt.getMonth(), dt.getDate());
                }

                // parse input date (YYYY-MM-DD)
                function parseInputDate(val) {
                    if (!val) return null;
                    const parts = val.split('-');
                    if (parts.length === 3) return new Date(parts[0], parts[1] - 1, parts[2]);
                    let dt = new Date(val);
                    return isNaN(dt.getTime()) ? null : new Date(dt.getFullYear(), dt.getMonth(), dt.getDate());
                }

                // custom filter tanggal
                $.fn.dataTable.ext.search.push(function (settings, data) {
                    const minVal = $('#minDate').val();
                    const maxVal = $('#maxDate').val();

                    let raw = data[dateColIndex];
                    if (raw === undefined) return true;

                    const text = stripTags(raw);
                    const tableDate = parseCellDate(text);
                    if (!tableDate) return true;

                    const minDate = parseInputDate(minVal);
                    const maxDate = parseInputDate(maxVal);

                    if ((minDate && tableDate < minDate) || (maxDate && tableDate > maxDate)) return false;
                    return true;
                });

                // Inisialisasi DataTable
                let table = $('#pesananTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'excel',
                            text: '<i class="bx bx-file me-1"></i> Excel',
                            className: 'btn btn-sm btn-success',
                            exportOptions: { modifier: { search: 'applied' } }
                        },
                        {
                            extend: 'pdf',
                            text: '<i class="bx bx-file me-1"></i> PDF',
                            className: 'btn btn-sm btn-danger',
                            exportOptions: { modifier: { search: 'applied' } }
                        },
                        {
                            extend: 'print',
                            text: '<i class="bx bx-printer me-1"></i> Print',
                            className: 'btn btn-sm btn-secondary',
                            exportOptions: { modifier: { search: 'applied' } }
                        },
                        {
                            extend: 'colvis',
                            text: '<i class="bx bx-show me-1"></i> Kolom',
                            className: 'btn btn-sm btn-info'
                        }
                    ],
                    order: [[0, 'asc']],
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                    footerCallback: function (row, data, start, end, display) {
                        let api = this.api();
                        // Ambil kolom Subtotal (index 5)
                        let total = api
                            .column(5, { search: 'applied' })
                            .data()
                            .reduce((sum, val) => {
                                let num = typeof val === 'string' ? parseInt(val.replace(/[^\d]/g, '')) || 0 : val;
                                return sum + num;
                            }, 0);
                        let formatted = new Intl.NumberFormat('id-ID').format(total);
                        $(api.column(5).footer()).html('Rp ' + formatted);
                    },
                    initComplete: function () {
                        if ($('#tableSearchContainer').length) {
                            $("#pesananTable_filter").appendTo("#tableSearchContainer");
                            $("#pesananTable_filter label").addClass("mb-0");
                        }
                    }
                });

                // tempatkan tombol export di kanan atas
                table.buttons().container().appendTo('#exportButtons');

                // redraw saat filter tanggal berubah
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
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('success') }}',
                        showConfirmButton: false,
                        timer: 2000
                    });
                @endif

                // ✅ Notifikasi tambah berhasil
                @if(session('updated'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: '{{ session('updated') }}',
                        showConfirmButton: false,
                        timer: 2000
                    });
                @endif

                // ✅ Notifikasi hapus berhasil
                @if(session('deleted'))
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
    @endpush
@endsection