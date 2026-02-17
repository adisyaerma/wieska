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
                        <input type="text" id="searchMenu" class="form-control form-control-sm" placeholder="Cari menu..."
                            style="width: 200px;">
                    </div>
                </div>

                <!-- Grid card max 3 baris -->
                <div class="overflow-x-auto">
                    <div class="d-grid" style="grid-template-rows: repeat(3, auto);
                    grid-auto-flow: column;
                    grid-auto-columns: 180px;
                    gap: 12px;
                    width: max-content;" id="menuContainer">

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
                                            data-harga="{{ $menu->harga_jual }}">
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

            <div class="card mb-6">
                <div class="card-body p-2 px-3 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Dibayarkan</h5>
                    <input type="number" id="dibayarkan" name="dibayarkan" class="form-control form-control-sm text-end"
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
            let subtotal = 0; // ini akan diisi dari total pesanan (renderTable)

            function formatRupiah(angka) {
                return "Rp " + angka.toLocaleString("id-ID");
            }

            // update total pesanan dari renderTable()
            function updateTotalPesanan(total) {
                subtotal = total;
                document.getElementById("totalPesanan").textContent = formatRupiah(total);
                hitungKembalian(); // panggil langsung supaya muncul -subtotal di awal
            }

            // hitung kembalian otomatis
            function hitungKembalian() {
                const inputBayar = document.getElementById("dibayarkan");
                const outputKembali = document.getElementById("kembalian");

                if (!outputKembali) return;

                const dibayarkan = parseInt(inputBayar?.value) || 0;
                let kembali = dibayarkan - subtotal;

                // jika dibayarkan belum diisi (0), tampilkan kembalian negatif subtotal
                if (dibayarkan === 0) {
                    kembali = -subtotal;
                }

                outputKembali.textContent = formatRupiah(kembali);
            }

            document.addEventListener("DOMContentLoaded", function () {
                const inputBayar = document.getElementById("dibayarkan");

                if (inputBayar) {
                    inputBayar.addEventListener("input", hitungKembalian);
                }
            });

            // event listener untuk input dibayarkan
            document.getElementById("dibayarkan").addEventListener("input", hitungKembalian);
        </script>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const searchInput = document.getElementById("searchMenu");
                const filterKategori = document.getElementById("filterKategori");
                const cards = document.querySelectorAll(".menu-card");
                const detailTable = document.querySelector("tbody.table-border-bottom-0");
                const namaPelangganInput = document.querySelector("input[placeholder='Masukkan Nama']");
                const itemsInput = document.getElementById("itemsInput");
                const namaPelangganHidden = document.getElementById("namaPelangganInput");

                let pesanan = [];

                // fungsi render tabel
                // fungsi render tabel
                function renderTable() {
                    detailTable.innerHTML = "";
                    let total = 0; // total pesanan

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

                    // update total pesanan
                    updateTotalPesanan(total);

                    // simpan JSON ke hidden input
                    itemsInput.value = JSON.stringify(pesanan.map(p => ({
                        menu_id: p.menu_id,
                        jumlah: p.jumlah,
                        subtotal: p.harga * p.jumlah
                    })));
                }


                // tambah menu
                function tambahPesanan(menu_id, nama, harga, satuan) {
                    let existing = pesanan.find(p => p.menu_id === menu_id);
                    if (existing) {
                        existing.jumlah++;
                    } else {
                        pesanan.push({ menu_id, nama, satuan, harga, jumlah: 1 });
                    }
                    renderTable();
                }


                // hapus menu
                window.hapusPesanan = function (menu_id) {
                    pesanan = pesanan.filter(p => p.menu_id !== menu_id);
                    renderTable();
                }

                cards.forEach((card) => {
                    const btnPlus = card.querySelector(".btn-plus");
                    const btnMinus = card.querySelector(".btn-minus");
                    const qtyDisplay = card.querySelector(".qty-display");

                    // Event tambah (+)
                    btnPlus.addEventListener("click", () => {
                        const menu_id = parseInt(btnPlus.dataset.id);
                        const nama = btnPlus.dataset.nama;
                        const satuan = btnPlus.dataset.satuan;
                        const harga = Number(btnPlus.dataset.harga) || 0;

                        tambahPesanan(menu_id, nama, harga, satuan);

                        // update qty display
                        const pesananItem = pesanan.find((p) => p.menu_id === menu_id);
                        qtyDisplay.textContent = pesananItem ? pesananItem.jumlah : 0;
                    });

                    // Event kurang (-)
                    btnMinus.addEventListener("click", () => {
                        const menu_id = parseInt(btnMinus.dataset.id);

                        kurangiPesanan(menu_id);

                        // update qty display
                        const pesananItem = pesanan.find((p) => p.menu_id === menu_id);
                        qtyDisplay.textContent = pesananItem ? pesananItem.jumlah : 0;
                    });
                });

                function kurangiPesanan(menu_id) {
                    const index = pesanan.findIndex((p) => p.menu_id === menu_id);

                    if (index !== -1) {
                        if (pesanan[index].jumlah > 1) {
                            pesanan[index].jumlah -= 1;
                        } else {
                            pesanan.splice(index, 1); // hapus dari array kalau jumlah jadi 0
                        }
                        renderTable();
                    }
                }


                // sebelum submit, ambil nama pelanggan
                document.getElementById("formKasir").addEventListener("submit", function (e) {
                    namaPelangganHidden.value = namaPelangganInput.value;
                });

                // filter menu
                function filterCards() {
                    const searchText = searchInput.value.toLowerCase();
                    const kategori = filterKategori.value.toLowerCase();

                    cards.forEach(card => {
                        const title = card.querySelector(".card-title").textContent.toLowerCase();
                        const cardKategori = (card.getAttribute("data-kategori") || "").toLowerCase();

                        const matchText = title.includes(searchText);
                        const matchKategori = kategori === "" || kategori === cardKategori;

                        card.style.display = (matchText && matchKategori) ? "block" : "none";
                    });
                }

                searchInput.addEventListener("keyup", filterCards);
                filterKategori.addEventListener("change", filterCards);
            });
        </script>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                // simpan jumlah tiap menu di object
                let cart = {};

                // tombol plus
                document.querySelectorAll(".btn-plus").forEach(btn => {
                    btn.addEventListener("click", function () {
                        let id = this.dataset.id;
                        let card = this.closest(".card");
                        let qtyDisplay = card.querySelector(".qty-display");

                        if (!cart[id]) cart[id] = 0;
                        cart[id]++;

                        qtyDisplay.textContent = cart[id];
                    });
                });

                // tombol minus
                document.querySelectorAll(".btn-minus").forEach(btn => {
                    btn.addEventListener("click", function () {
                        let id = this.dataset.id;
                        let card = this.closest(".card");
                        let qtyDisplay = card.querySelector(".qty-display");

                        if (cart[id] && cart[id] > 0) {
                            cart[id]--;
                            qtyDisplay.textContent = cart[id];
                        }
                    });
                });
            });
        </script>

        <script>
            document.getElementById("formKasir").addEventListener("submit", function (e) {
                // isi nama pelanggan hidden
                document.getElementById("namaPelangganInput").value = document.getElementById("namaPelanggan").value;

                // buat input kembalian jika belum ada
                let kembalianInput = document.getElementById("kembalianInput");
                if (!kembalianInput) {
                    kembalianInput = document.createElement("input");
                    kembalianInput.type = "hidden";
                    kembalianInput.name = "kembalian";
                    kembalianInput.id = "kembalianInput";
                    this.appendChild(kembalianInput);
                }

                const subtotal = parseInt(document.getElementById("totalPesanan").textContent.replace(/\D/g, ''));
                const dibayarkan = parseInt(document.getElementById("dibayarkan").value) || 0;
                kembalianInput.value = dibayarkan - subtotal;
            });
        </script>
    @endpush
@endsection