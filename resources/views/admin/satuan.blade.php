@extends('admin.master')
@section('title', 'Satuan')
@section('satuanActive', 'active')
@section('masterDataActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <h5 class="mb-0 fs-4">Satuan</h5>

                <!-- Kanan: export dan tambah -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Tempat tombol export DataTables -->
                    <div id="exportButtons"></div>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#tambahSatuan">
                        Tambah
                    </button>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="tambahSatuan" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('satuan.store') }}" method="POST" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel1">Tambah Satuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col mb-6">
                                    <label for="nameBasic" class="form-label">Satuan</label>
                                    <input type="text" id="satuan" name="satuan" class="form-control"
                                        placeholder="Masukkan Satuan" />
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                Batal
                            </button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive text-nowrap">
                <table class="table" id="satuanTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Satuan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($satuans as $i => $satuan)
                            <tr>
                                <td><span>{{ $i + 1 }}</span></td>
                                <td>{{ $satuan->satuan }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button type="button" class="dropdown-item" href="javascript:void(0);"
                                                data-bs-toggle="modal" data-bs-target="#editSatuan{{ $satuan->id }}"><i
                                                    class="icon-base bx bx-edit-alt me-1"></i>
                                                Edit</button>
                                            <form action="{{ route('satuan.destroy', $satuan->id) }}" method="POST" class="form-hapus">
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
        <!-- Modal -->
        @foreach($satuans as $satuan)
            <div class="modal fade" id="editSatuan{{ $satuan->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form action="{{ route('satuan.update', $satuan->id) }}" method="POST" class="modal-content">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Satuan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" name="satuan" class="form-control" value="{{ $satuan->satuan }}" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
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
            $(document).ready(function () {
                let table = $('#satuanTable').DataTable({
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