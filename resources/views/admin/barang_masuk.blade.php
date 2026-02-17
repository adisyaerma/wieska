@extends('admin.master')
@section('title', 'Barang Masuk')
@section('barangMasukActive', 'active')
@section('transaksiActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <h5 class="mb-0 fs-4">Barang Masuk</h5>

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
            <!-- Modal Tambah -->
            <div class="modal fade" id="tambahBarang" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('barang_masuk.store') }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tambah Data Barang Masuk</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input class="form-control" type="date" name="tanggal" id="tanggal" required
                                        value="{{ date('Y-m-d') }}">
                                </div>

                                <div class="mb-3">
                                    <label for="stok_barang_id" class="form-label">Nama Barang</label>
                                    <select name="stok_barang_id" id="stok_barang_id" class="form-select" required>
                                        <option value="">-- Pilih Barang --</option>
                                        @foreach($stokBarangs as $barang)
                                            <option value="{{ $barang->id }}">{{ $barang->nama_barang }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="jumlah" class="form-label">Jumlah</label>
                                        <input type="number" name="jumlah" id="jumlah" class="form-control" required>
                                    </div>
                                    <div class="col">
                                        <label for="satuan_id" class="form-label">Satuan</label>
                                        <select name="satuan_id" id="satuan_id" class="form-select" required>
                                            <option value="">-- Pilih Satuan --</option>
                                            @foreach($satuans as $satuan)
                                                <option value="{{ $satuan->id }}">{{ $satuan->satuan }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="harga_satuan" class="form-label">Harga Satuan</label>
                                    <input type="text" class="form-control harga_satuan_display" placeholder="Rp 0"
                                        required>
                                    <input type="hidden" name="harga_satuan" class="harga_satuan_hidden">
                                </div>

                                <div class="mb-3">
                                    <label for="catatan" class="form-label">Catatan</label>
                                    <textarea name="catatan" id="catatan" class="form-control"></textarea>
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
                <table class="table" id="barangTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama Barang</th>
                            <th>Jumlah</th>
                            <th>Satuan</th>
                            <th>Harga Satuan</th>
                            <th>Total Harga</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($barangMasuks as $i => $barangMasuk)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    <span class="badge bg-label-primary me-1">
                                        {{ \Carbon\Carbon::parse($barangMasuk->tanggal)->format('d/m/Y') }}
                                    </span>
                                </td>
                                <td>{{ $barangMasuk->stokBarang->nama_barang }}</td>
                                <td>{{ $barangMasuk->jumlah }}</td>
                                <td>{{ $barangMasuk->satuan->satuan }}</td>
                                <td>Rp {{ number_format($barangMasuk->harga_satuan, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($barangMasuk->total_harga, 0, ',', '.') }}</td>
                                <td>
                                    <button type="button" class="btn btn-sm text-primary fs-5" data-bs-toggle="modal"
                                        data-bs-target="#catatan{{ $barangMasuk->id }}">
                                        <i class='bxr  bx-note'></i>
                                    </button>
                                </td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editBarang{{ $barangMasuk->id }}">
                                                <i class="bx bx-edit-alt me-1"></i> Edit
                                            </button>
                                            <form action="{{ route('barang_masuk.destroy', $barangMasuk->id) }}" method="POST"  class="form-hapus">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item">
                                                    <i class="bx bx-trash me-1"></i> Delete
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
        <!-- Modal Edit -->
        @foreach($barangMasuks as $i => $barangMasuk)
            <div class="modal fade" id="editBarang{{ $barangMasuk->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('barang_masuk.update', $barangMasuk->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Data Barang Masuk</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">

                                <div class="mb-3">
                                    <label for="tanggal" class="form-label">Tanggal</label>
                                    <input class="form-control" type="date" name="tanggal" value="{{ $barangMasuk->tanggal }}"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="stok_barang_id" class="form-label">Nama Barang</label>
                                    <select name="stok_barang_id" class="form-select" required>
                                        @foreach($stokBarangs as $barang)
                                            <option value="{{ $barang->id }}" {{ $barangMasuk->stok_barang_id == $barang->id ? 'selected' : '' }}>
                                                {{ $barang->nama_barang }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row mb-3">
                                    <div class="col">
                                        <label for="jumlah" class="form-label">Jumlah</label>
                                        <input type="number" name="jumlah" class="form-control"
                                            value="{{ $barangMasuk->jumlah }}" required>
                                    </div>
                                    <div class="col">
                                        <label for="satuan_id" class="form-label">Satuan</label>
                                        <select name="satuan_id" class="form-select" required>
                                            @foreach($satuans as $satuan)
                                                <option value="{{ $satuan->id }}" {{ $barangMasuk->satuan_id == $satuan->id ? 'selected' : '' }}>
                                                    {{ $satuan->satuan }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Harga Satuan</label>
                                    <input type="text" class="form-control harga_satuan_display"
                                        value="Rp {{ number_format($barangMasuk->harga_satuan ?? 0, 0, ',', '.') }}" required>
                                    <input type="hidden" name="harga_satuan" class="harga_satuan_hidden"
                                        value="{{ $barangMasuk->harga_satuan ?? '' }}">
                                </div>


                                <div class="mb-3">
                                    <label for="catatan" class="form-label">Catatan</label>
                                    <textarea name="catatan" class="form-control">{{ $barangMasuk->catatan }}</textarea>
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

        @foreach ($barangMasuks as $barangMasuk)
            <div class="modal fade" id="catatan{{ $barangMasuk->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Catatan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            {{ $barangMasuk->catatan }}
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                                Tutup
                            </button>
                        </div>
                    </div>
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

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('.harga_satuan_display').forEach(function (displayInput) {
                    const hiddenInput = displayInput.parentElement.querySelector('.harga_satuan_hidden');

                    // Saat user mengetik
                    displayInput.addEventListener('input', function () {
                        let value = this.value.replace(/\D/g, '');
                        hiddenInput.value = value;

                        if (value) {
                            this.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        } else {
                            this.value = '';
                        }
                    });

                    // Saat fokus: hilangkan "Rp"
                    displayInput.addEventListener('focus', function () {
                        this.value = hiddenInput.value ? hiddenInput.value : '';
                    });

                    // Saat blur: tampilkan kembali format Rp
                    displayInput.addEventListener('blur', function () {
                        let value = hiddenInput.value;
                        if (value) {
                            this.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    });
                });
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