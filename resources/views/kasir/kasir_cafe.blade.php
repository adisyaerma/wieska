@extends(Auth::user()->jabatan === 'admin' ? 'admin.master' : 'kasir.master')
@section('title', 'Kasir Cafe')
@section('kasirCafeActive', 'active')
@section('transaksiActive', 'active open')
@section('isi')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-6">
            <div class="card-header d-flex justify-content-between align-items-center py-4">
                <h5 class="mb-0 fs-4">Kasir Cafe</h5>
                <div class="float-end fw-semibold fs-5 text-end">
                    <div class="">{{ auth()->user()->nama }}</div>
                    <div>{{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}</div>
                </div>

            </div>

            <div class="card-body">
                <!-- Filter kategori & search -->
                <div class="d-flex justify-content-between align-items-center mb-3 gap-2 flex-wrap">
                    <div class="d-flex gap-2">
                        <select id="filterKategori" class="form-select form-select-sm" style="width: 150px;">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoris as $kategori)
                                <option value="{{ strtolower($kategori->kategori) }}">{{ $kategori->kategori }}</option>
                            @endforeach

                        </select>
                    </div>
                    <div>
                        <input type="text" id="searchMenu" class="form-control form-control-sm"
                            placeholder="Cari menu..." style="width: 200px;">
                    </div>
                </div>

                <!-- Grid card max 3 baris -->
                <div class="overflow-x-auto">
                    <div class="d-grid"
                        style="
                    display: grid;
                    grid-auto-flow: column;
                    grid-template-rows: repeat(3, auto);
                    grid-auto-columns: 180px;
                    gap: 12px;
                    width: max-content;
                 "
                        id="menuContainer">

                        @foreach ($menus as $menu)
                            <div class="card text-bg-primary h-100 menu-card" style="width: 180px;"
                                data-kategori="{{ optional($menu->stokBarang->kategori)->kategori ?? '' }}">
                                <div class="card-body p-2 d-flex flex-column justify-content-between">
                                    <h5 class="card-title text-white fw-bold mb-1" style="font-size: 16px;">
                                        {{ $menu->stokBarang->nama_barang ?? '-' }}
                                    </h5>
                                    <p class="card-text text-white fw-semibold mb-1" style="font-size: 16px;">
                                        Rp {{ number_format($menu->harga_jual, 0, ',', '.') }}
                                    </p>
                                    <div class="d-flex align-items-center justify-content-center gap-2 mt-1">
                                        <button type="button"
                                            class="btn rounded-pill btn-icon btn-primary bg-white text-primary p-0 btn-minus"
                                            style="width:28px; height:28px;" data-id="{{ $menu->id }}">
                                            <i class='bx bx-minus' style="font-size:14px;"></i>
                                        </button>
                                        <p class="mb-0 fw-bold text-white qty-display" style="font-size: 15px;">0</p>
                                        <button type="button"
                                            class="btn rounded-pill btn-icon btn-primary bg-white text-primary p-0 btn-plus"
                                            style="width:28px; height:28px;" data-id="{{ $menu->id }}"
                                            data-nama="{{ $menu->stokBarang->nama_barang }}"
                                            data-satuan="{{ $menu->stokBarang->satuan->satuan }}"
                                            data-harga="{{ $menu->harga_jual }}"
                                            data-stok="{{ $menu->stokBarang->total_stok }}">
                                            <i class='bx bx-plus' style="font-size:14px;"></i>
                                        </button>

                                    </div>
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-6">
            <h5 class="card-header">Detail Pesanan</h5>
            <div class="table-responsive text-nowrap">
                <table class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Satuan</th>
                            <th>Jumlah</th>
                            <th>Harga Satuan</th>
                            <th>Sub Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="table-border-bottom-0">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card mb-6">
            <div class="card-body p-2 px-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Total Pesanan</h5>
                <h5 id="totalPesanan" class="mb-0 fw-medium">Rp 0</h5>
            </div>
        </div>

        <form id="formKasir" action="{{ route('kasir_cafe.store') }}" method="POST">
            @csrf
            <input type="hidden" name="id_karyawan" value="{{ Auth::user()->id }}">
            <input type="hidden" name="nama_pelanggan" id="namaPelangganInput">
            <input type="hidden" name="items" id="itemsInput">
            <input type="hidden" name="dibayarkan" id="dibayarkanInput">
            <input type="hidden" name="kembalian" id="kembalianInput">
            <input type="hidden" name="total_pesanan" id="totalPesananInput" value="0">

            <div class="card mb-6">
                <div class="card-body p-2 px-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Dibayarkan</h5>
                    <input type="number" id="dibayarkan" class="form-control form-control-sm text-end"
                        style="max-width: 150px;" placeholder="0" />
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-body p-2 px-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Kembalian</h5>
                    <h5 id="kembalian" class="mb-0 fw-medium">Rp 0</h5>
                </div>
            </div>

            <div class="card mb-6">
                <div class="card-body p-2 px-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Nama Pelanggan</h5>
                    <input type="text" id="namaPelanggan" class="form-control form-control-sm text-end"
                        style="max-width: 150px;" placeholder="Masukkan Nama" />
                </div>
            </div>

            <button style="width: 100%;" type="submit" class="btn btn-primary">Cetak Struk</button>
        </form>


    </div>

    @push('scripts')
        <script>
            let subtotal = 0;

            function formatRupiah(angka) {
                return "Rp " + angka.toLocaleString("id-ID");
            }

            function updateTotalPesanan(total) {
                subtotal = total;
                document.getElementById("totalPesanan").textContent = formatRupiah(total);
                document.getElementById("totalPesananInput").value = total;
                hitungKembalian();
            }

            function hitungKembalian() {
                const dibayarkan = parseInt(document.getElementById("dibayarkan").value) || 0;
                const kembali = dibayarkan - subtotal;
                document.getElementById("kembalian").textContent = formatRupiah(kembali);
                document.getElementById("kembalianInput").value = kembali;
            }

            document.addEventListener("DOMContentLoaded", function() {
                const inputBayar = document.getElementById("dibayarkan");
                const form = document.getElementById("formKasir");
                const detailTable = document.querySelector("tbody.table-border-bottom-0");
                const searchInput = document.getElementById("searchMenu");
                const filterKategori = document.getElementById("filterKategori");
                const cards = document.querySelectorAll(".menu-card");

                const itemsInput = document.getElementById("itemsInput");
                const namaPelangganInput = document.getElementById("namaPelanggan");
                const namaPelangganHidden = document.getElementById("namaPelangganInput");

                let pesanan = [];

                // Render tabel pesanan
                function renderTable() {
                    detailTable.innerHTML = "";
                    let total = 0;

                    pesanan.forEach((item, index) => {
                        const sub = item.harga * item.jumlah;
                        total += sub;

                        detailTable.innerHTML += `
                                                        <tr>
                                                            <td>${index + 1}</td>
                                                            <td>${item.nama}</td>
                                                            <td>${item.satuan}</td>
                                                            <td>${item.jumlah}</td>
                                                            <td>Rp ${item.harga.toLocaleString()}</td>
                                                            <td>Rp ${sub.toLocaleString()}</td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-outline-danger" onclick="hapusPesanan(${item.menu_id})">
                                                                    <i class='bx bx-trash'></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    `;
                    });

                    updateTotalPesanan(total);

                    // Simpan ke hidden input items
                    itemsInput.value = JSON.stringify(pesanan.map(p => ({
                        menu_id: p.menu_id,
                        jumlah: p.jumlah,
                        subtotal: p.harga * p.jumlah
                    })));
                }

                // Tambah menu
                function tambahPesanan(menu_id, nama, harga, satuan) {
                    const existing = pesanan.find(p => p.menu_id === menu_id);
                    if (existing) existing.jumlah++;
                    else pesanan.push({
                        menu_id,
                        nama,
                        satuan,
                        harga,
                        jumlah: 1
                    });
                    renderTable();
                }

                // Kurangi menu
                function kurangiPesanan(menu_id) {
                    const index = pesanan.findIndex(p => p.menu_id === menu_id);
                    if (index !== -1) {
                        if (pesanan[index].jumlah > 1) pesanan[index].jumlah--;
                        else pesanan.splice(index, 1);
                    }
                    renderTable();
                }

                // Hapus menu global
                window.hapusPesanan = function(menu_id) {
                    pesanan = pesanan.filter(p => p.menu_id !== menu_id);
                    renderTable();
                }

                // Event plus & minus pada card
                cards.forEach(card => {
                    const btnPlus = card.querySelector(".btn-plus");
                    const btnMinus = card.querySelector(".btn-minus");
                    const qtyDisplay = card.querySelector(".qty-display");

                    btnPlus.addEventListener("click", () => {
                        const menu_id = parseInt(btnPlus.dataset.id);
                        const nama = btnPlus.dataset.nama;
                        const satuan = btnPlus.dataset.satuan;
                        const harga = Number(btnPlus.dataset.harga) || 0;
                        const stok = parseInt(btnPlus.dataset.stok);

                        const existing = pesanan.find(p => p.menu_id === menu_id);
                        const jumlahSekarang = existing ? existing.jumlah : 0;

                        // 🚫 jika melebihi stok
                        if (jumlahSekarang >= stok) {
                            Swal.fire({
                                icon: "warning",
                                title: "Stok tidak cukup",
                                text: `Stok ${nama} tersisa ${stok}`,
                                confirmButtonText: "OK"
                            });
                            return;
                        }

                        // ✅ jika masih ada stok
                        tambahPesanan(menu_id, nama, harga, satuan);

                        const pesananItem = pesanan.find(p => p.menu_id === menu_id);
                        qtyDisplay.textContent = pesananItem ? pesananItem.jumlah : 0;
                    });


                    btnMinus.addEventListener("click", () => {
                        const menu_id = parseInt(btnMinus.dataset.id);
                        kurangiPesanan(menu_id);

                        const pesananItem = pesanan.find(p => p.menu_id === menu_id);
                        qtyDisplay.textContent = pesananItem ? pesananItem.jumlah : 0;
                    });
                });

                // Event input dibayarkan
                inputBayar.addEventListener("input", hitungKembalian);

                // Filter menu
                function filterCards() {
                    const searchText = searchInput.value.toLowerCase();
                    const kategori = filterKategori.value.toLowerCase();

                    cards.forEach(card => {
                        const title = card.querySelector(".card-title").textContent.toLowerCase();
                        const cardKategori = (card.getAttribute("data-kategori") || "").toLowerCase();

                        card.style.display = (title.includes(searchText) && (kategori === "" || kategori ===
                            cardKategori)) ? "block" : "none";
                    });
                }

                searchInput.addEventListener("keyup", filterCards);
                filterKategori.addEventListener("change", filterCards);

                // Event submit form: isi semua hidden input
                form.addEventListener("submit", function(e) {
                    // Cegah submit jika pesanan kosong
                    if (pesanan.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'error',
                            title: 'Tidak Ada Menu',
                            text: 'Silakan pilih menu terlebih dahulu.',
                        });
                        return;
                    }

                    // Jika ada pesanan → lanjut isi hidden input seperti biasa
                    document.getElementById("namaPelangganInput").value = namaPelangganInput.value;
                    const dibayarkan = parseInt(document.getElementById("dibayarkan").value) || 0;
                    document.getElementById("dibayarkanInput").value = dibayarkan;
                    document.getElementById("totalPesananInput").value = subtotal;
                    document.getElementById("kembalianInput").value = dibayarkan - subtotal;

                    document.getElementById("itemsInput").value = JSON.stringify(
                        pesanan.map(p => ({
                            menu_id: p.menu_id,
                            jumlah: p.jumlah,
                            subtotal: p.harga * p.jumlah
                        }))
                    );
                });

            });
        </script>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        @if (session('error'))
            <script>
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Cukup',
                    text: "{{ session('error') }}",
                });
            </script>
        @endif

        @if (session('success'))
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: "{{ session('success') }}",
                });
            </script>
        @endif

    @endpush

@endsection
