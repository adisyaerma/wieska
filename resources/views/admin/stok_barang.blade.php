@extends('admin.master')
@section('title', 'Stok Barang')
@section('stokBarangActive', 'active')
@section('laporanActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <h5 class="mb-0 fs-4">Stok Barang</h5>

                <!-- Kanan: export dan tambah -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Tempat tombol export DataTables -->
                    <div id="exportButtons"></div>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#tambahStok">
                        Tambah
                    </button>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="tambahStok" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('stok_barang.store') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Stok</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control"
                                        placeholder="Masukkan nama barang" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kode Barang</label>
                                    <input type="text" name="kode_barang" class="form-control"
                                        placeholder="Masukkan kode barang" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="kategori_id" class="form-select" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}">{{ $kategori->kategori }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <select name="satuan_id" class="form-select" required>
                                        <option value="">Pilih Satuan</option>
                                        @foreach($satuans as $satuan)
                                            <option value="{{ $satuan->id }}">{{ $satuan->satuan }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Total Stok</label>
                                    <input type="number" name="total_stok" class="form-control"
                                        placeholder="Masukkan stok" required>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table" id="pesananTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Satuan</th>
                            <th>Total Stok</th>
                            <th>Status Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($stokBarangs as $i => $stok)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $stok->kode_barang }}</td>
                                <td>{{ $stok->nama_barang }}</td>
                                <td>{{ $stok->kategori->kategori }}</td>
                                <td>{{ $stok->satuan->satuan }}</td>
                                <td>{{ $stok->total_stok }}</td>
                                <td>
                                    @if($stok->status_stok == 'Tersedia')
                                        <span class="badge bg-label-success me-1">Tersedia</span>
                                    @elseif($stok->status_stok == 'Hampir Habis')
                                        <span class="badge bg-label-warning me-1">Hampir Habis</span>
                                    @else
                                        <span class="badge bg-label-danger me-1">Habis</span>
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
                                                data-bs-target="#editStok{{ $stok->id }}">
                                                <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                            </button>
                                            <form class="form-hapus" action="{{ route('stok_barang.destroy', $stok->id) }}" method="POST">
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
        <!--/ Basic Bootstrap Table -->
        <!-- Modal -->
        @foreach($stokBarangs as $i => $stok)
            <div class="modal fade" id="editStok{{ $stok->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('stok_barang.update', $stok->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Stok</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label class="form-label">Nama Barang</label>
                                    <input type="text" name="nama_barang" class="form-control"
                                        value="{{ $stok->nama_barang }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kode Barang</label>
                                    <input type="text" name="kode_barang" class="form-control"
                                        value="{{ $stok->kode_barang }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Kategori</label>
                                    <select name="kategori_id" class="form-select" required>
                                        <option value="">Pilih Kategori</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" {{ $stok->kategori_id == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Satuan</label>
                                    <select name="satuan_id" class="form-select" required>
                                        <option value="">Pilih Satuan</option>
                                        @foreach($satuans as $satuan)
                                            <option value="{{ $satuan->id }}" {{ $stok->satuan_id == $satuan->id ? 'selected' : '' }}>
                                                {{ $satuan->satuan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Total Stok</label>
                                    <input type="number" name="total_stok" class="form-control"
                                        value="{{ $stok->total_stok }}" required>
                                </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach

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
                let table = $('#pesananTable').DataTable({
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