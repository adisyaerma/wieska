@extends('admin.master')
@section('title', 'Kasir Tiket')
@section('kasirTiketActive', 'active')
@section('transaksiActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <div class="card-header d-flex justify-content-between align-items-center py-4">
                <h5 class="mb-0 fs-4">Kasir Tiket</h5>
                <div class="float-end fw-semibold fs-5 text-end">
                    <div class="">{{ auth()->user()->nama }}</div>
                    <div>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
                </div>
            </div>
            <div class="card-body">

                {{-- Bagian Tiket --}}
                <div class="overflow-x-auto mb-4">
                    <div class="d-flex gap-3 flex-nowrap align-items-center justify-content-center" id="menuContainer">
                        @foreach ($jenis_tikets as $jenis_tiket)
                            <div class="card text-bg-primary h-100 menu-card" style="width: 180px; flex: 0 0 auto;">
                                <div class="card-body p-2 d-flex flex-column justify-content-between">
                                    <h5 class="card-title text-white fw-bold mb-1">{{ $jenis_tiket->jenis_tiket }}</h5>
                                    <p class="card-text text-white fw-semibold mb-1">
                                        Rp {{ number_format($jenis_tiket->harga, 0, ',', '.') }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-center gap-2 mt-1">
                                        <button type="button"
                                            class="btn rounded-pill btn-icon btn-primary bg-white text-primary p-0 btn-minus"
                                            style="width:28px;height:28px;" data-id="{{ $jenis_tiket->id }}"
                                            data-harga="{{ $jenis_tiket->harga }}">
                                            <i class="bx bx-minus" style="font-size:14px;"></i>
                                        </button>
                                        <p class="mb-0 fw-bold text-white qty-display" data-id="{{ $jenis_tiket->id }}">0</p>
                                        <button type="button"
                                            class="btn rounded-pill btn-icon btn-primary bg-white text-primary p-0 btn-plus"
                                            style="width:28px;height:28px;" data-id="{{ $jenis_tiket->id }}"
                                            data-nama="{{ $jenis_tiket->jenis_tiket }}" data-harga="{{ $jenis_tiket->harga }}">
                                            <i class="bx bx-plus" style="font-size:14px;"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Bagian Total Pesanan --}}
                <div class="card mb-3">
                    <div class="card-body p-2 px-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Total Harga</h5>
                        <h5 id="totalPesanan" class="mb-0 fw-medium">Rp 0</h5>
                    </div>
                </div>

                {{-- Bagian Dibayarkan --}}
                <div class="card mb-3">
                    <div class="card-body p-2 px-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Dibayarkan</h5>
                        <input id="dibayarkan" type="number" class="form-control form-control-sm text-end"
                            style="max-width: 150px;" placeholder="Rp 0" />
                    </div>
                </div>

                {{-- Bagian Kembalian --}}
                <div class="card mb-4">
                    <div class="card-body p-2 px-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Kembalian</h5>
                        <h5 id="kembalian" class="mb-0 fw-medium">Rp 0</h5>
                    </div>
                </div>

                {{-- Bagian Nama Pelanggan --}}
                <div class="card mb-3">
                    <div class="card-body p-2 px-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Nama Pelanggan</h5>
                        <input id="nama_pelanggan" type="text" class="form-control form-control-sm text-end"
                            style="max-width: 150px;" placeholder="" />
                    </div>
                </div>

                <form action="{{ route('kasir_tiket.store') }}" method="POST" id="formTiket">
                    @csrf
                    <input type="hidden" name="items" id="itemsInput">
                    <input type="hidden" name="subtotal" id="subtotalInput">

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary" style="width:100%;">Cetak Tiket</button>
                    </div>
                </form>


            </div>

        </div>
        @push('scripts')

            <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

            <script>
                let items = {};
                let total = 0;

                function updateTotal() {
                    total = 0;
                    Object.values(items).forEach(i => total += i.subtotal);
                    document.getElementById("totalPesanan").innerText = "Rp " + total.toLocaleString('id-ID');
                    updateKembalian();
                }

                function updateKembalian() {
                    let bayar = parseInt(document.getElementById("dibayarkan").value || 0);
                    let kembali = bayar - total;
                    document.getElementById("kembalian").innerText = "Rp " + kembali.toLocaleString('id-ID');
                }

                // Tombol Tambah
                document.querySelectorAll('.btn-plus').forEach(btn => {
                    btn.addEventListener('click', function () {
                        let id = this.dataset.id;
                        let harga = parseInt(this.dataset.harga);

                        if (!items[id]) items[id] = { id: id, qty: 0, harga: harga, subtotal: 0 };

                        items[id].qty++;
                        items[id].subtotal = items[id].qty * harga;

                        document.querySelector(`.qty-display[data-id="${id}"]`).innerText = items[id].qty;
                        updateTotal();
                    });
                });

                // Tombol Kurang
                document.querySelectorAll('.btn-minus').forEach(btn => {
                    btn.addEventListener('click', function () {
                        let id = this.dataset.id;

                        if (items[id] && items[id].qty > 0) {
                            items[id].qty--;
                            items[id].subtotal = items[id].qty * items[id].harga;

                            document.querySelector(`.qty-display[data-id="${id}"]`).innerText = items[id].qty;

                            if (items[id].qty === 0) delete items[id];

                            updateTotal();
                        }
                    });
                });

                document.getElementById("dibayarkan").addEventListener("input", updateKembalian);

                // ===============================
                //   SUBMIT FORM DENGAN SWEET ALERT
                // ===============================
                document.getElementById('formTiket').addEventListener('submit', function (e) {
                    e.preventDefault(); // hentikan submit default

                    // Jika tidak ada tiket
                    if (Object.keys(items).length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Tidak ada tiket!',
                            text: 'Silakan pilih tiket terlebih dahulu.',
                            confirmButtonColor: '#3085d6'
                        });
                        return;
                    }

                    // Set value ke hidden input
                    document.getElementById("itemsInput").value = JSON.stringify(Object.values(items));
                    document.getElementById("subtotalInput").value = total;

                    const form = this;
                    const formData = new FormData(form);

                    // ambil nama pelanggan
                    const namaPelanggan = document.getElementById('nama_pelanggan').value;
                    formData.append('nama_pelanggan', namaPelanggan);

                    // Kirim request
                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                        }
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.tiket_id) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Tiket berhasil dibuat.',
                                    timer: 1500,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = `/${data.tiket_id}/struk_tiket`;
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: 'Gagal membuat tiket.'
                                });
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'Terjadi Kesalahan!',
                                text: 'Silakan coba lagi.'
                            });
                        });
                });

            </script>

        @endpush

@endsection