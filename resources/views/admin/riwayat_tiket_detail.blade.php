@extends('admin.master')
@section('title', 'Detail Riwayat Tiket')
@section('riwayatTiketActive', 'active')
@section('laporanActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <div>
                    <h5 class="mb-0">Kasir: {{ optional($tiket->karyawan)->nama ?? '—' }}</h5>
                    <h5 class="mb-0">Pelanggan: {{ $tiket->nama_pelanggan }}</h5>
                </div>

                <!-- Kanan: export dan tambah -->
                <div class="d-flex align-items-center">
                    <!-- Tempat tombol export DataTables -->
                    <div id="exportButtons"></div>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table" id="pesananTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jenis Tiket</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($tiket->details as $index => $detail)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $detail->jenisTiket->jenis_tiket ?? '-' }}</td>
                                <td>{{ $detail->jumlah }}</td>
                               <td>
                                    {{ $detail->jenisTiket->harga ? number_format($detail->jenisTiket->harga, 0, ',', '.') : '-' }}
                                </td>

                                <td>{{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            {{-- Edit pakai modal --}}
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editTiketDetail{{ $detail->id }}">
                                                <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                            </button>
                                            {{-- Delete --}}
                                            <form class="form-hapus" action="{{ route('riwayat_tiket_detail.destroy', $detail->id) }}" method="POST"
                                                >
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

                            {{-- Modal Edit --}}
                            <div class="modal fade" id="editTiketDetail{{ $detail->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <form action="{{ route('tiketdetail.update', $detail->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Detail Tiket</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                
                                                {{-- Dropdown Jenis Tiket --}}
                                                <div class="mb-3">
                                                    <label for="jenis_tiket">Jenis Tiket</label>
                                                    <select name="jenis_tiket" class="form-control" required>
                                                        <option value="">-- Pilih Jenis Tiket --</option>
                                                        @foreach($jenisTiket as $jt)
                                                            <option value="{{ $jt->id }}" 
                                                                {{ $detail->id_jenis_tiket == $jt->id ? 'selected' : '' }}>
                                                                {{ $jt->jenis_tiket }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- Jumlah Tiket --}}
                                                <div class="mb-3">
                                                    <label for="jumlah_tiket">Jumlah Tiket</label>
                                                    <input type="number" name="jumlah" class="form-control"
                                                        value="{{ $detail->jumlah }}" min="1" required>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        @endforeach
                    </tbody>

                    
                </table>

            </div>
        </div>
        <!--/ Basic Bootstrap Table -->
        <!-- Modal -->
        <div class="modal fade" id="editTiketDetail" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel1">Edit Detail Tiket
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-6">
                                <label for="nameBasic" class="form-label">Jenis Tiket</label>
                                <input type="text" id="nameBasic" class="form-control" placeholder="Masukkan Kategori" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label for="nameBasic" class="form-label">Jumlah</label>
                                <input type="text" id="nameBasic" class="form-control" placeholder="Masukkan Kategori" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label for="nameBasic" class="form-label">Harga Satuan</label>
                                <input type="text" id="nameBasic" class="form-control" placeholder="Masukkan Kategori" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col mb-6">
                                <label for="nameBasic" class="form-label">Subtotal</label>
                                <input type="text" id="nameBasic" class="form-control" placeholder="Masukkan Kategori" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Batal
                        </button>
                        <button type="button" class="btn btn-primary">Simpan</button>
                    </div>
                </div>
            </div>
        </div>
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