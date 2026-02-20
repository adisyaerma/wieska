@extends('admin.master')
@section('title', 'Hutang')
@section('hutangActive', 'active')
@section('transaksiActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <h5 class="mb-0 fs-4">Hutang</h5>

                <!-- Kanan: export dan tambah -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Tempat tombol export DataTables -->
                    <div id="exportButtons"></div>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahHutang">
                        Tambah
                    </button>
                </div>
            </div>
            <!-- Modal Tambah Hutang -->
            <div class="modal fade" id="tambahHutang" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('hutang.store') }}" method="POST" class="modal-content">
                        @csrf

                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Hutang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">

                                {{-- Tanggal --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}"
                                        name="tanggal" class="form-control" required>
                                </div>

                                {{-- Pihak --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pihak</label>
                                    <input type="text" name="pihak" class="form-control" required>
                                </div>

                                {{-- Total Hutang --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Hutang</label>
                                    <input type="text" oninput="formatRupiah(this)" name="total_hutang"
                                        class="form-control" required>
                                    <input type="hidden" name="total_hutang" id="harga_value">
                                </div>

                                {{-- Jatuh Tempo --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jatuh Tempo</label>
                                    <input type="date" name="jatuh_tempo" class="form-control" required>
                                </div>

                                {{-- Keterangan --}}
                                <div class="col-md-12 mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="3"></textarea>
                                </div>

                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>

                    </form>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table" id="hutangTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Pihak</th>
                            <th>Keterangan</th>
                            <th>Total Hutang</th>
                            <th>Jatuh Tempo</th>
                            <th>Tanggal Bayar</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="table-border-bottom-0">
                        @foreach ($hutangs as $i => $hutang)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $hutang->tanggal->format('d-m-Y') }}</td>
                                <td>{{ $hutang->pihak }}</td>
                                <td>{{ $hutang->keterangan ?? '-' }}</td>
                                <td>Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}</td>
                                <td>{{ $hutang->jatuh_tempo->format('d-m-Y') }}</td>
                                <td>{{ $hutang->tanggal_bayar ? $hutang->tanggal_bayar->format('d-m-Y') : '-' }}</td>
                                <td>
                                    @if ($hutang->status == 'Belum Lunas')
                                        <span class="badge bg-warning mb-1 d-block">Belum Lunas</span>
                                        <button class="btn btn-sm btn-success"
                                            onclick="konfirmasiLunas({{ $hutang->id }})">
                                            Konfirmasi Lunas
                                        </button>
                                    @elseif ($hutang->status == 'Lunas')
                                        <span class="badge bg-success">Lunas</span>
                                    @else
                                        <span class="badge bg-danger">Jatuh Tempo</span>
                                    @endif
                                </td>

                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                        </button>

                                        <div class="dropdown-menu">
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editHutang{{ $hutang->id }}">
                                                <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                            </button>

                                            <form action="{{ route('hutang.destroy', $hutang->id) }}" method="POST"
                                                class="form-hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="icon-base bx bx-trash me-1"></i> Hapus
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

        @foreach ($hutangs as $hutang)
            <div class="modal fade" id="editHutang{{ $hutang->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <form action="{{ route('hutang.update', $hutang->id) }}" method="POST" class="modal-content">
                        @csrf
                        @method('PUT')

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Hutang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">
                            <div class="row">

                                {{-- Tanggal --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Tanggal</label>
                                    <input type="date" name="tanggal" value="{{ $hutang->tanggal->format('Y-m-d') }}"
                                        class="form-control" required>
                                </div>

                                {{-- Pihak --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Pihak</label>
                                    <input type="text" name="pihak" value="{{ $hutang->pihak }}"
                                        class="form-control" required>
                                </div>

                                {{-- Total Hutang --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Total Hutang</label>
                                    <input type="text" oninput="formatRupiah(this, '_{{ $hutang->id }}')"
                                        name="total_hutang" class="form-control"
                                        value="Rp {{ number_format($hutang->total_hutang, 0, ',', '.') }}" required>
                                    <input type="hidden" name="total_hutang" value="{{ $hutang->total_hutang }}"
                                        id="harga_value_{{ $hutang->id }}">
                                </div>

                                {{-- Jatuh Tempo --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Jatuh Tempo</label>
                                    <input type="date" name="jatuh_tempo"
                                        value="{{ $hutang->jatuh_tempo->format('Y-m-d') }}" class="form-control"
                                        required>
                                </div>

                                {{-- Keterangan --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="3">{{ $hutang->keterangan }}</textarea>
                                </div>

                                {{-- Status (readonly visual) --}}
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <select name="status" class="form-select" required>
                                        <option value="Belum Lunas"
                                            {{ $hutang->status == 'Belum Lunas' ? 'selected' : '' }}>
                                            Belum Lunas
                                        </option>
                                        <option value="Lunas" {{ $hutang->status == 'Lunas' ? 'selected' : '' }}>
                                            Lunas
                                        </option>
                                        <option value="Jatuh Tempo"
                                            {{ $hutang->status == 'Jatuh Tempo' ? 'selected' : '' }}>
                                            Jatuh Tempo
                                        </option>
                                    </select>
                                </div>


                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>

                    </form>
                </div>
            </div>
        @endforeach


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
                let table = $('#hutangTable').DataTable({
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
                                e.target.submit();
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
            function konfirmasiLunas(id) {
                Swal.fire({
                    title: 'Konfirmasi Pelunasan',
                    text: 'Yakin hutang ini sudah lunas?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, Lunas',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/admin/hutang/${id}/lunas`, {
                                method: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                }
                            })
                            .then(() => {
                                Swal.fire('Berhasil', 'Hutang ditandai lunas', 'success')
                                    .then(() => location.reload());
                            });
                    }
                });
            }
        </script>
    @endpush

@endsection
