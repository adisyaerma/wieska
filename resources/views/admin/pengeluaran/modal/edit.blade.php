<!-- MODAL EDIT PENGELUARAN -->
<div class="modal fade" id="editPengeluaran" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <form id="formEditPengeluaran" method="POST" class="modal-content">
            @csrf
            @method('post')

            <input type="hidden" id="edit_id">

            <div class="modal-header">
                <h5 class="modal-title">Edit Pengeluaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <!-- TANGGAL -->
                <div class="mb-3">
                    <label>Tanggal</label>
                    <input type="datetime-local" name="tanggal" id="edit_tanggal" class="form-control" required>
                </div>

                <!-- JENIS -->
                <div class="mb-3">
                    <label>Jenis Pengeluaran</label>
                    <select name="jenis_pengeluaran" id="edit_jenis" class="form-control" required>
                        <option value="Operasional">Operasional</option>
                        <option value="Gaji">Gaji</option>
                        <option value="Hutang">Hutang</option>
                        <option value="Kembalian Cafe">Kembalian Cafe</option>
                        <option value="Kembalian Tiket">Kembalian Tiket</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- OPERASIONAL / LAINNYA -->
                <div id="edit-form-operasional" class="d-none">
                    <div class="mb-3">
                        <label>Tujuan</label>
                        <input type="text" name="tujuan_pengeluaran" id="edit_tujuan" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Nominal</label>
                        <input type="text" name="nominal_operasional" id="edit_nominal_operasional"
                            class="form-control rupiah">
                    </div>
                </div>

                <!-- GAJI -->
                <div id="edit-form-gaji" class="d-none">
                    <div class="mb-3">
                        <label>Karyawan</label>
                        <select name="refrensi_id" id="edit_karyawan" class="form-control">
                            @foreach ($karyawan as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Gaji Pokok</label>
                        <input type="text" name="gaji_pokok" id="edit_gaji_pokok" class="form-control rupiah">
                    </div>

                    <div class="row">
                        <div class="col">
                            <label>Potongan</label>
                            <input type="text" name="potongan" id="edit_potongan" class="form-control rupiah">
                        </div>
                        <div class="col">
                            <label>Bonus</label>
                            <input type="text" name="bonus" id="edit_bonus" class="form-control rupiah">
                        </div>
                    </div>

                    <div class="mt-2">
                        <label>Total Gaji</label>
                        <input type="text" id="edit_total_gaji" class="form-control rupiah" readonly>
                    </div>
                </div>

                <!-- HUTANG -->
                <div id="edit-form-hutang" class="d-none">
                    <div class="mb-3">
                        <label>Hutang</label>
                        <select name="refrensi_id" id="edit_hutang" class="form-control">
                            @foreach ($hutang_edit as $h)
                                <option value="{{ $h->id }}" data-hutang="{{ $h->sisa_hutang }}">
                                    {{ $h->pihak }} -
                                    Rp{{ number_format($h->total_hutang, 0, ',', '.') }} - Rp
                                    {{ number_format($h->sisa_hutang, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nominal Bayar</label>
                        <input type="text" name="nominal_hutang" id="edit_nominal_hutang"
                            class="form-control rupiah">
                        <small id="warningHutangEdit" class="text-danger d-none">
                            Nominal bayar melebihi sisa hutang
                        </small>
                    </div>
                </div>

                <div id="edit-form-kembalian" class="d-none">
                    <div class="mb-3">
                        <label>Nominal Kembalian</label>
                        <input type="text" name="nominal_kembalian" placeholder="Masukkan Nominal Kembalian"
                            class="form-control rupiah" id="edit_nominal_kembalian" value="">
                    </div>
                </div>

                <!-- STATUS -->
                <div class="mb-3">
                    <label>Status</label>
                    <select name="status" id="edit_status" class="form-control" required>
                        <option value="Valid">Valid</option>
                        <option value="Dibatalkan">Dibatalkan</option>
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary">Simpan Perubahan</button>
            </div>

        </form>
    </div>
</div>
<script>
    /* ===============================
   HELPER RUPIAH
================================ */
    function formatRupiah(angka) {
        return new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: 0
        }).format(angka);
    }

    function onlyNumber(value) {
        return value.replace(/[^\d]/g, '');
    }
</script>

<script>
    /* ===============================
   TOGGLE FORM BERDASARKAN JENIS
================================ */
    const editJenis = document.getElementById('edit_jenis');

    editJenis.addEventListener('change', function() {

        // reset semua form
        ['operasional', 'gaji', 'hutang', 'kembalian'].forEach(v => {
            const el = document.getElementById(`edit-form-${v}`);
            if (el) el.classList.add('d-none');
        });

        if (this.value === 'Operasional' || this.value === 'Lainnya') {
            document.getElementById('edit-form-operasional')?.classList.remove('d-none');
        }

        if (this.value === 'Gaji') {
            document.getElementById('edit-form-gaji')?.classList.remove('d-none');
        }

        if (this.value === 'Hutang') {
            document.getElementById('edit-form-hutang')?.classList.remove('d-none');
        }

        if (this.value === 'Kembalian Cafe' || this.value === 'Kembalian Tiket') {
            document.getElementById('edit-form-kembalian')?.classList.remove('d-none');
        }
    });
</script>

<script>
    function openEditModal(data) {

        document.getElementById('formEditPengeluaran').action =
            `/admin/pengeluaran/edit/${data.id}`;

        edit_id.value = data.id;
        edit_tanggal.value = data.tanggal.replace(' ', 'T');
        edit_jenis.value = data.jenis_pengeluaran;
        edit_status.value = data.status;

        // trigger form tampil
        editJenis.dispatchEvent(new Event('change'));

        setTimeout(() => {

            if (data.jenis_pengeluaran === 'Gaji') {
                edit_karyawan.value = String(data.refrensi_id);
                edit_gaji_pokok.value = formatRupiah(data.gaji_pokok ?? 0);
                edit_potongan.value = formatRupiah(data.potongan ?? 0);
                edit_bonus.value = formatRupiah(data.bonus ?? 0);
                hitungGajiEdit();
            }

            if (data.jenis_pengeluaran === 'Hutang') {
                edit_hutang.value = String(data.refrensi_id);
                edit_nominal_hutang.value = formatRupiah(data.nominal_pengeluaran);
            }

            if (['Operasional', 'Lainnya'].includes(data.jenis_pengeluaran)) {
                edit_tujuan.value = data.tujuan_pengeluaran;
                edit_nominal_operasional.value = formatRupiah(data.nominal_pengeluaran);
            }

            if (
                data.jenis_pengeluaran === 'Kembalian Cafe' ||
                data.jenis_pengeluaran === 'Kembalian Tiket'
            ) {
                edit_nominal_kembalian.value =
                    formatRupiah(data.nominal_pengeluaran);
            }

        }, 50);

        new bootstrap.Modal('#editPengeluaran').show();
    }
</script>

<script>
    /* ===============================
   FORMAT RUPIAH INPUT & SUBMIT INT
================================ */
    document.addEventListener('DOMContentLoaded', function() {

        const rupiahInputs = [
            'edit_nominal_operasional',
            'edit_nominal_hutang',
            'edit_nominal_kembalian',
            'edit_gaji_pokok',
            'edit_bonus',
            'edit_potongan'
        ];

        rupiahInputs.forEach(id => {
            const input = document.getElementById(id);
            if (!input) return;

            // format saat mengetik
            input.addEventListener('input', function() {
                const angka = onlyNumber(this.value);
                if (!angka) {
                    this.value = '';
                    return;
                }
                this.value = formatRupiah(parseInt(angka));
            });
        });

        // sebelum submit → ubah jadi INT
        document.getElementById('formEditPengeluaran')
            ?.addEventListener('submit', function() {

                rupiahInputs.forEach(id => {
                    const input = document.getElementById(id);
                    if (!input) return;
                    input.value = onlyNumber(input.value);
                });
            });
    });
</script>

<script>
    /* ===============================
   VALIDASI HUTANG (RUPIAH AMAN)
================================ */
    document.addEventListener('DOMContentLoaded', function() {

        const selectHutang = document.getElementById('edit_hutang');
        const inputBayar = document.getElementById('edit_nominal_hutang');
        const warning = document.getElementById('warningHutangEdit');

        if (!selectHutang || !inputBayar || !warning) return;

        let sisaHutang = 0;

        function cekNominal() {
            const bayar = parseInt(onlyNumber(inputBayar.value) || 0);

            if (bayar > sisaHutang && sisaHutang > 0) {
                warning.classList.remove('d-none');
            } else {
                warning.classList.add('d-none');
            }
        }

        selectHutang.addEventListener('change', function() {
            const selected = this.options[this.selectedIndex];
            sisaHutang = parseInt(selected.dataset.hutang || 0);
            cekNominal();
        });

        inputBayar.addEventListener('input', cekNominal);

        window.addEventListener('shown.bs.modal', function(e) {
            if (e.target.id === 'editPengeluaran') {
                const selected = selectHutang.options[selectHutang.selectedIndex];
                sisaHutang = parseInt(selected?.dataset.hutang || 0);
                cekNominal();
            }
        });

    });
</script>
<script>
    function hitungGajiEdit() {
        const gaji = parseInt(onlyNumber(edit_gaji_pokok.value) || 0);
        const potongan = parseInt(onlyNumber(edit_potongan.value) || 0);
        const bonus = parseInt(onlyNumber(edit_bonus.value) || 0);

        const total = gaji - potongan + bonus;
        edit_total_gaji.value = formatRupiah(total);
    }

    // realtime hitung
    ['edit_gaji_pokok', 'edit_potongan', 'edit_bonus'].forEach(id => {
        const el = document.getElementById(id);
        if (!el) return;
        el.addEventListener('input', hitungGajiEdit);
    });
</script>
