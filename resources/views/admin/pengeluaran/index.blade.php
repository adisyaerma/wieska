@extends('admin.master')
@section('title', 'Pengeluaran')
@section('pengeluaranActive', 'active')
@section('transaksiActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <h5 class="mb-0 fs-4">Pengeluaran</h5>

                <div class="d-flex align-items-center gap-3">
                    <!-- date range -->
                    <div class="d-flex align-items-center gap-2">
                        <input type="date" id="tanggal_awal" class="form-control form-control-sm"
                            value="{{ date('d-m-Y') }}">

                        <span>s/d</span>

                        <input type="date" id="tanggal_akhir" class="form-control form-control-sm"
                            value="{{ date('d-m-Y') }}">
                    </div>

                    <div id="exportButtons"></div>

                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#tambahPengeluaran">
                        Tambah
                    </button>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table" id="pengeluaranTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Jenis Pengeluaran</th>
                            <th>Tujuan Pengeluaran</th>
                            <th>Jumlah Nominal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($data as $i => $item)
                            <tr>
                                <td><span>{{ $i + 1 }}</span></td>
                                <td>{{ \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y') }}</td>
                                <td>{{ $item->jenis_pengeluaran }}</td>
                                <td>
                                    {{ $item->tujuan_pengeluaran }}
                                    @if ($item->jenis_pengeluaran == 'Gaji')
                                        - {{ $item->nama ?? '-' }}
                                    @elseif ($item->jenis_pengeluaran == 'Hutang')
                                        - {{ $item->pihak ?? '-' }}
                                    @else

                                    @endif
                                </td>
                                @php
                                    $totalGaji = $item->gaji_pokok - $item->potongan + $item->bonus;
                                @endphp
                                <td>
                                    @if ($item->jenis_pengeluaran == "Gaji")
                                        Rp {{ number_format($totalGaji, 0, ',', '.') }}
                                    @else
                                        Rp {{ number_format($item->nominal_pengeluaran, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button type="button" class="dropdown-item" href="javascript:void(0);" onclick='openEditModal({
                                                                                                id: "{{ $item->id }}",
                                                                                                tanggal: "{{ $item->tanggal }}",
                                                                                                jenis_pengeluaran: "{{ $item->jenis_pengeluaran }}",
                                                                                                refrensi_id: "{{ $item->refrensi_id }}",
                                                                                                tujuan_pengeluaran: "{{ $item->tujuan_pengeluaran }}",
                                                                                                nominal_pengeluaran: "{{ $item->nominal_pengeluaran }}",
                                                                                                gaji_pokok: "{{ $item->gaji_pokok }}",
                                                                                                potongan: "{{ $item->potongan }}",
                                                                                                bonus: "{{ $item->bonus }}",
                                                                                                status: "{{ $item->status }}"
                                                                                              })'>
                                                <i class="icon-base bx bx-edit-alt me-1"></i>
                                                Edit
                                            </button>
                                            @if ($item->jenis_pengeluaran == 'Gaji')
                                                <a href="{{ route('pengeluaran.gaji', $item->id) }}" class="dropdown-item">
                                                    <i class="icon-base bx bx-show me-1"></i>
                                                    Detail
                                                </a>
                                            @endif
                                            @if ($item->jenis_pengeluaran == 'Hutang')
                                                <a href="{{ route('pengeluaran.hutang', $item->id) }}" class="dropdown-item">
                                                    <i class="icon-base bx bx-show me-1"></i>
                                                    Detail
                                                </a>
                                            @endif
                                            <a href="{{ route('pengeluaran.print', $item->id) }}" target="_blank" class="dropdown-item"
                                                rel="noopener noreferrer">
                                                <i class="icon-base bx bx-printer me-1"></i>
                                                Cetak
                                            </a>
                                            <form action="{{ route('pengeluaran.destroy', $item->id) }}" method="POST"
                                                class="form-hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="icon-base bx bx-trash me-1"></i>
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.pengeluaran.modal.edit')
    @include('admin.pengeluaran.modal.tambah')

    @push('scripts')
        <script>
            $.fn.dataTable.ext.search.push(function (settings, data) {
                let min = $('#tanggal_awal').val();
                let max = $('#tanggal_akhir').val();
                let tanggal = data[1]; // kolom Tanggal (index ke-1)

                if (!min && !max) return true;

                let date = new Date(tanggal);
                let minDate = min ? new Date(min) : null;
                let maxDate = max ? new Date(max) : null;

                if (
                    (!minDate || date >= minDate) &&
                    (!maxDate || date <= maxDate)
                ) {
                    return true;
                }
                return false;
            });

            $(document).ready(function () {

                // 🔒 Pastikan tidak double init
                if ($.fn.DataTable.isDataTable('#pengeluaranTable')) {
                    $('#pengeluaranTable').DataTable().destroy();
                }

                let table = $('#pengeluaranTable').DataTable({
                    dom: 'Bfrtip',
                    buttons: [
                        {
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
                    order: [[0, 'asc']],
                    pageLength: 10,
                    lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "Semua"]],
                });

                // Pindahkan tombol export ke header
                table.buttons().container().appendTo('#exportButtons');

                // 🔥 LIVE date range filter (tanpa reload)
                $('#tanggal_awal, #tanggal_akhir').on('change', function () {
                    table.draw();
                });

            });
        </script>
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


        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.addEventListener('DOMContentLoaded', () => {

                // Jika ada pengeluaran baru
                @if(session('new_pengeluaran_id'))
                    let pengeluaranId = {{ session('new_pengeluaran_id') }};
                    Swal.fire({
                        title: 'Pengeluaran berhasil ditambahkan!',
                        text: 'Apakah Anda ingin mencetak tiket?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Cetak',
                        cancelButtonText: 'Tidak',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Redirect ke halaman cetak, misal route 'pengeluaran.print'
                            window.location.href = '/admin/pengeluaran/print/' + pengeluaranId;
                        }
                        // Jika klik Tidak, tetap di halaman index
                    });
                @endif

                        });
        </script>
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