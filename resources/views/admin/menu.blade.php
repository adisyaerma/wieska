@extends('admin.master')
@section('title', 'Menu')
@section('menuActive', 'active')
@section('masterDataActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Basic Bootstrap Table -->
        <div class="card mt-1">
            <div class="card-header d-flex justify-content-between align-items-center py-5">
                <h5 class="mb-0 fs-4">Menu Cafe</h5>

                <div class="d-flex align-items-center gap-2">

                    <!-- Tempat tombol export DataTables -->
                    <div id="exportButtons"></div>

                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#tambahMenu">
                        Tambah
                    </button>
                </div>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="tambahMenu" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('menu.store') }}" method="POST" enctype="multipart/form-data"
                        class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Tambah Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Pilih dari Stok Barang <a href="barang_masuk">menu belum ada? tambahkan di sini. </a> </label>
                                <select name="stok_barang_id" class="form-select" required>
                                    <option value="">-- Pilih Menu --</option>
                                    @foreach($stok as $s)
                                        <option value="{{ $s->id }}">
                                            {{ $s->nama_barang }} (Stok: {{ $s->total_stok ?? 0 }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="harga_jual" class="form-label">Harga Jual</label>
                                <input type="text" class="form-control harga_jual_display" placeholder="Rp 0" required>
                                <input type="hidden" name="harga_jual" class="harga_jual_hidden">
                            </div>

                            <div class="mb-3">
                                <label for="gambar" class="form-label">Gambar</label>
                                <input type="file" name="gambar" id="gambar" class="form-control" accept="image/*">
                                <!-- ✅ Preview muncul di sini -->
                                <div class="mt-3 text-center">
                                    <img id="preview-gambar" src="#" alt="Preview Gambar" class="img-fluid rounded d-none"
                                        style="max-height: 200px; object-fit: cover;">
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

            <script>
                // ✅ Script untuk menampilkan preview gambar
                document.getElementById('gambar').addEventListener('change', function (event) {
                    const preview = document.getElementById('preview-gambar');
                    const file = event.target.files[0];

                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function (e) {
                            preview.src = e.target.result;
                            preview.classList.remove('d-none');
                        }
                        reader.readAsDataURL(file);
                    } else {
                        preview.src = '#';
                        preview.classList.add('d-none');
                    }
                });
            </script>


            <!-- Tampilan Card (hidden by default) -->
            <!-- <div id="cardView" class="row row-cols-4 g-3 justify-content-center d-none px-5 py-5">
                                                        @for($i = 1; $i <= 8; $i++)
                                                            <div class="col">
                                                                <div class="d-flex align-items-center p-2 border bg-white"
                                                                    style="border-radius:15px; width:210px; height:90px;">
                                                                    <img src="https://img.klikindogrosir.com/images/products/1504020.png" alt="Pop Mie"
                                                                        class="img-fluid" style="max-width:70px; height:auto;">
                                                                    <div class="ms-2 text-start">
                                                                        <strong class="d-block" style="font-size:14px;">Pop Mie Kuah<br>Tori Miso</strong>
                                                                        <span class="d-block" style="font-size:13px;">Rp 7.000</span>
                                                                        <small class="text-muted" style="font-size:12px;">Stok : 35</small>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endfor
                                                    </div> -->

            <div id="tableView" class="table-responsive text-nowrap">
                <table class="table" id="MenuTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Gambar</th>
                            <th>Nama Menu</th>
                            <th>Kategori</th>
                            <th>Stok</th>
                            <th>Satuan</th>
                            <th>Harga Jual</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                        @foreach($menus as $i => $m)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>
                                    @if($m->gambar)
                                        <img width="50" src="{{ asset('storage/' . $m->gambar) }}" alt="">
                                    @else
                                        <span>-</span>
                                    @endif
                                </td>
                                <td>
                                    {{ $m->nama_menu }}
                                    <small class="text-muted">{{ $m->stokBarang->nama_barang }}</small>
                                </td>
                                <td>
                                    {{ optional($m->kategori)->kategori }}

                                    <small class="text-muted">
                                        {{ optional($m->stokBarang->kategori)->kategori }}
                                    </small>
                                </td>
                                <td>{{ $m->stokBarang ? $m->stokBarang->total_stok : 0 }}</td>
                                <td>{{ $m->stokBarang->satuan->satuan }}</td>
                                <td>Rp {{ number_format($m->harga_jual, 0, ',', '.') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <button type="button" class="btn p-0 dropdown-toggle hide-arrow"
                                            data-bs-toggle="dropdown">
                                            <i class="icon-base bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu">
                                            <!-- Edit: buka modal edit atau route edit -->
                                            <button type="button" class="dropdown-item" data-bs-toggle="modal"
                                                data-bs-target="#editMenu{{ $m->id }}">
                                                <i class="icon-base bx bx-edit-alt me-1"></i> Edit
                                            </button>

                                            <form action="{{ route('menu.destroy', $m->id) }}" method="POST" class="form-hapus"
                                                style="display:inline">
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
                </table>
            </div>
        </div>
        <!-- Modal -->
        @foreach($menus as $m)
            <div class="modal fade" id="editMenu{{ $m->id }}" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('menu.update', $m->id) }}" method="POST" enctype="multipart/form-data"
                        class="modal-content">
                        @csrf
                        @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Data Menu</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <!-- Pilih Stok Barang -->
                            <div class="mb-3">
                                <label class="form-label">Pilih Stok Barang</label>
                                <select name="stok_barang_id" class="form-select" required>
                                    <option value="">-- Pilih Barang --</option>
                                    @foreach($stok as $s)
                                        <option value="{{ $s->id }}" {{ $m->stok_barang_id == $s->id ? 'selected' : '' }}>
                                            {{ $s->nama_barang }}
                                            (Stok: {{ $s->total_stok ?? 0 }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Harga Jual -->
                            <div class="mb-3">
                                <label class="form-label">Harga Jual</label>
                                <input type="text" class="form-control harga_jual_display"
                                    value="Rp {{ number_format($m->harga_jual ?? 0, 0, ',', '.') }}" required>
                                <input type="hidden" name="harga_jual" class="harga_jual_hidden"
                                    value="{{ $m->harga_jual ?? '' }}">
                            </div>

                            <!-- Gambar -->
                            <div class="mb-3">
                                <label for="gambar{{ $m->id }}" class="form-label">Gambar</label>
                                <input type="file" name="gambar" id="gambar{{ $m->id }}" class="form-control" accept="image/*">
                                @if($m->gambar)
                                    <small class="text-muted">Gambar sekarang:
                                        <img src="{{ asset('storage/' . $m->gambar) }}" width="50">
                                    </small>
                                @endif
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
                    let table = $('#MenuTable').DataTable({
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
                document.addEventListener("DOMContentLoaded", function () {
                    const btnTable = document.getElementById("showTable");
                    const btnCard = document.getElementById("showCard");
                    const tableView = document.getElementById("tableView");
                    const cardView = document.getElementById("cardView");

                    btnTable.addEventListener("click", function () {
                        tableView.classList.remove("d-none");
                        cardView.classList.add("d-none");
                        btnTable.classList.replace("btn-outline-primary", "btn-primary");
                        btnCard.classList.replace("btn-primary", "btn-outline-primary");
                    });

                    btnCard.addEventListener("click", function () {
                        cardView.classList.remove("d-none");
                        tableView.classList.add("d-none");
                        btnCard.classList.replace("btn-outline-primary", "btn-primary");
                        btnTable.classList.replace("btn-primary", "btn-outline-primary");
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

            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    document.querySelectorAll('.harga_jual_display').forEach(function (displayInput) {
                        const hiddenInput = displayInput.parentElement.querySelector('.harga_jual_hidden');

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
        @endpush
    </div>
@endsection