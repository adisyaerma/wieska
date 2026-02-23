<!-- Modal -->
<div class="modal fade" id="tambahPengeluaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form action="{{ route('pengeluaran.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Tambah Pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tanggal -->
                <div class="mb-3">
                    <label>Tanggal</label>
                    <input type="datetime-local" name="tanggal" class="form-control" required>
                </div>

                <!-- Jenis -->
                <div class="mb-3">
                    <label>Jenis Pengeluaran</label>
                    <select name="jenis_pengeluaran" id="jenis_pengeluaran" class="form-control" required>
                        <option value="">-- Pilih --</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Gaji">Gaji</option>
                        <option value="Hutang">Hutang</option>
                        <option value="Kembalian Cafe">Kembalian Cafe</option>
                        <option value="Kembalian Tiket">Kembalian Tiket</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- OPERASIONAL / LAINNYA -->
                <div id="form-operasional" class="d-none">
                    <div class="mb-3">
                        <label>Tujuan Pengeluaran</label>
                        <input type="text" name="tujuan_pengeluaran" placeholder="Masukkan Tujuan Pengeluaran"
                            class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Nominal</label>
                        <input type="text" oninput="formatHarga(this)" name="nominal_pengeluaran"
                            placeholder="Masukkan Nominal" class="form-control">
                        <input type="hidden" name="nominal_pengeluaran" id="harga_value">
                    </div>
                </div>

                <!-- GAJI -->
                <div id="form-gaji" class="d-none">
                    <div class="mb-3">
                        <label>Karyawan</label>
                        <select name="refrensi_id_gaji" class="form-control">
                            @foreach ($karyawan as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Gaji Pokok</label>
                        <input type="text" id="gaji_pokok" oninput="formatHarga4(this,'gaji_value')"
                            class="form-control">
                        <input type="hidden" id="gaji_value" name="gaji_pokok">
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label>Potongan</label>
                                <input type="text" id="potongan" oninput="formatHarga4(this,'potongan_value')"
                                    class="form-control">
                                <input type="hidden" id="potongan_value" name="potongan">
                            </div>
                        </div>
                        <div class="col">
                            <div class="mb-3">
                                <label>Bonus</label>
                                <input type="text" id="bonus" oninput="formatHarga4(this,'bonus_value')"
                                    class="form-control">
                                <input type="hidden" id="bonus_value" name="bonus">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Total Gaji</label>
                        <input type="text" oninput="formatHarga4(this)" id="total_gaji" class="form-control"
                            placeholder="Total Gaji" readonly>
                        <input type="hidden" id="total_gaji_value">
                    </div>

                    <input type="hidden" name="nominal_pengeluaranGaji" id="nominal_pengeluaran">
                </div>

                <!-- HUTANG -->
                <div id="form-hutang" class="d-none">
                    <div class="mb-3">
                        <label>Bayar Hutang</label>
                        <select name="refrensi_id_hutang" class="form-control" id="hutang_add">
                            <option value="">-- Pilih --</option>
                            @foreach ($hutang as $h)
                                <option value="{{ $h->id }}" data-sisa="{{ $h->sisa_hutang }}">
                                    {{ $h->pihak }} - Rp{{ number_format($h->total_hutang, 0, ',', '.') }} - Rp
                                    {{ number_format($h->sisa_hutang, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nominal Bayar</label>
                        {{-- <input type="number" name="nominal_pengeluaran_hutang"
                            placeholder="Masukkan Nominal Bayar Hutang" class="form-control"> --}}
                        <input type="text" oninput="formatHarga2(this)" name="nominal_pengeluaran_hutang"
                            id="nominal_bayar" class="form-control" placeholder="Masukkan nominal bayar">
                        <input type="hidden" name="nominal_pengeluaran_hutang" id="harga_value2">

                        <small id="warningHutang" class="text-danger d-none">
                            Nominal bayar melebihi sisa hutang
                        </small>
                    </div>
                </div>

                <!-- KEMBALIAN -->
                <div id="form-kembalian" class="d-none">
                    <div class="mb-3">
                        <label>Nominal Kembalian</label>
                        <input type="text" oninput="formatHarga3(this)" name="nominal_kembalian"
                            placeholder="Masukkan Nominal Kembalian" class="form-control">
                        <input type="hidden" name="nominal_kembalian" id="harga_value3">

                    </div>
                </div>

                <!-- Status -->
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Valid">Valid</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
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

<script>
    function formatHarga(el, suffix = '') {
        const angka = el.value.replace(/\D/g, '');
        el.value = angka ? 'Rp ' + new Intl.NumberFormat('id-ID').format(angka) : '';
        document.getElementById('harga_value' + suffix).value = angka;
    }
</script>
<script>
    function formatHarga2(el, suffix = '') {
        const angka = el.value.replace(/\D/g, '');
        el.value = angka ? 'Rp ' + new Intl.NumberFormat('id-ID').format(angka) : '';
        document.getElementById('harga_value2' + suffix).value = angka;
    }
</script>
<script>
    function formatHarga3(el, suffix = '') {
        const angka = el.value.replace(/\D/g, '');
        el.value = angka ? 'Rp ' + new Intl.NumberFormat('id-ID').format(angka) : '';
        document.getElementById('harga_value3' + suffix).value = angka;
    }
</script>
<script>
    function formatHarga4(el, targetId) {
        const angka = el.value.replace(/\D/g, '');
        el.value = angka ? 'Rp ' + new Intl.NumberFormat('id-ID').format(angka) : '';
        document.getElementById(targetId).value = angka;
        hitungGaji(); // 🔥 WAJIB
    }
</script>
<script>
    const jenis = document.getElementById('jenis_pengeluaran');

    jenis.addEventListener('change', function() {

        // reset semua form
        ['operasional', 'gaji', 'hutang', 'kembalian'].forEach(v => {
            document.getElementById(`form-${v}`)?.classList.add('d-none');
        });

        if (this.value === 'Operasional' || this.value === 'Lainnya') {
            document.getElementById('form-operasional').classList.remove('d-none');
        }

        if (this.value === 'Gaji') {
            document.getElementById('form-gaji').classList.remove('d-none');
        }

        if (this.value === 'Hutang') {
            document.getElementById('form-hutang').classList.remove('d-none');
        }

        // ✅ KEMBALIAN CAFE & TIKET
        if (this.value === 'Kembalian Cafe' || this.value === 'Kembalian Tiket') {
            document.getElementById('form-kembalian').classList.remove('d-none');
        }
    });

    function hitungGaji() {
        let pokok = parseInt(document.getElementById('gaji_value').value) || 0;
        let potongan = parseInt(document.getElementById('potongan_value').value) || 0;
        let bonus = parseInt(document.getElementById('bonus_value').value) || 0;

        let total = pokok - potongan + bonus;

        document.getElementById('total_gaji').value =
            'Rp ' + new Intl.NumberFormat('id-ID').format(total);

        document.getElementById('nominal_pengeluaran').value = total;
    }

    ['gaji_pokok', 'potongan', 'bonus'].forEach(id => {
        document.getElementById(id).addEventListener('input', hitungGaji);
    });
</script>

<script>
    const selectHutang = document.getElementById('hutang_add');
    const inputBayar = document.getElementById('nominal_bayar');
    const warning = document.getElementById('warningHutang');

    let sisaHutang = 0;

    selectHutang.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        sisaHutang = parseInt(selected.dataset.sisa || 0);

        // reset warning & input
        warning.classList.add('d-none');
        inputBayar.value = '';
    });

    inputBayar.addEventListener('input', function() {
        const bayar = parseInt(this.value || 0);

        if (bayar > sisaHutang && sisaHutang > 0) {
            warning.classList.remove('d-none');
        } else {
            warning.classList.add('d-none');
        }
    });
</script>
