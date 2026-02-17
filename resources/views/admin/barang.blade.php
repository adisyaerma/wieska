@extends('admin.master')

@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <h5 class="mb-0 fs-4">Data Barang</h5>

                <!-- Kanan: export dan tambah -->
                <div class="d-flex align-items-center gap-2">
                    <!-- Tempat tombol export DataTables -->
                    <div id="exportButtons"></div>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#tambahBarang">
                        Tambah
                    </button>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="tambahBarang" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('barang.store') }}" method="POST" enctype="multipart/form-data"
                        class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Data Barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="kodeBarang" class="form-label">Kode Barang</label>
                                <input type="text" id="kodeBarang" name="kode_barang" class="form-control"
                                    placeholder="Masukkan Kode Barang" required />
                            </div>
                            <div class="mb-3">
                                <label for="namaBarang" class="form-label">Nama Barang</label>
                                <input type="text" id="namaBarang" name="nama_barang" class="form-control"
                                    placeholder="Masukkan Nama Barang" required />
                            </div>
                            <div class="mb-3">
                                <label for="kategoriSelect" class="form-label">Kategori</label>
                                <select class="form-select" id="kategoriSelect" name="kategori_id" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}">{{ $kategori->kategori }}</option>
                                    @endforeach
                                </select>
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
                <table class="table" id="barangTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Barang</th>
                            <th>Nama Barang</th>
                            <th>Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach ($barangs as $i => $barang)
                            <tr>
                                <td><span>{{ $i + 1 }}</span></td>
                                <td>{{ $barang->kode_barang }}</td>
                                <td>{{ $barang->nama_barang }}</td>
                                <td><span class="badge bg-label-primary me-1">{{ $barang->kategori->kategori ?? '-' }}</span></td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editBarang-{{ $barang->id }}">
                                                <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                            </button>
                                            <form action="{{ route('barang.destroy', $barang->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="dropdown-item"
                                                    onclick="return confirm('Yakin hapus barang ini?')">
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
        @foreach ($barangs as $i=>$barang)
        @csrf
            <div class="modal fade" id="editBarang-{{ $barang->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data"
                        class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Barang</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Kode Barang</label>
                                <input type="text" name="kode_barang" value="{{ $barang->kode_barang }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="kategori_id" class="form-select" required>
                                    <option value="">-- Pilih Kategori --</option>
                                    @foreach ($kategoris as $kategori)
                                        <option value="{{ $kategori->id }}" {{ $barang->kategori_id == $kategori->id ? 'selected' : '' }}>
                                            {{ $kategori->kategori }}
                                        </option>
                                    @endforeach
                                </select>
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
                let table = $('#barangTable').DataTable({
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
    @endpush

@endsection