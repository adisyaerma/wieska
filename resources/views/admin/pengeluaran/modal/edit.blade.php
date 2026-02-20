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
                        <input type="number" name="nominal_operasional" id="edit_nominal_operasional"
                            class="form-control">
                    </div>
                </div>

                <!-- GAJI -->
                <div id="edit-form-gaji" class="d-none">
                    <div class="mb-3">
                        <label>Karyawan</label>
                        <select name="refrensi_id" id="edit_karyawan" class="form-control">
                            @foreach($karyawan as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Gaji Pokok</label>
                        <input type="number" name="gaji_pokok" id="edit_gaji_pokok" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col">
                            <label>Potongan</label>
                            <input type="number" name="potongan" id="edit_potongan" class="form-control">
                        </div>
                        <div class="col">
                            <label>Bonus</label>
                            <input type="number" name="bonus" id="edit_bonus" class="form-control">
                        </div>
                    </div>

                    <div class="mt-2">
                        <label>Total Gaji</label>
                        <input type="number" id="edit_total_gaji" class="form-control" readonly>
                    </div>
                </div>

                <!-- HUTANG -->
                <div id="edit-form-hutang" class="d-none">
                    <div class="mb-3">
                        <label>Hutang</label>
                        <select name="refrensi_id" id="edit_hutang" class="form-control">
                            @foreach($hutang as $h)
                                <option value="{{ $h->id }}">{{ $h->pihak }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nominal Bayar</label>
                        <input type="number" name="nominal_hutang" id="edit_nominal_hutang" class="form-control">
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
                <button class="btn btn-primary">Update</button>
            </div>

        </form>
    </div>
</div>

<script>
    const editJenis = document.getElementById('edit_jenis');

    editJenis.addEventListener('change', function () {
        ['operasional', 'gaji', 'hutang'].forEach(v => {
            document.getElementById(`edit-form-${v}`).classList.add('d-none');
        });

        if (this.value === 'Operasional' || this.value === 'Lainnya') {
            document.getElementById('edit-form-operasional').classList.remove('d-none');
        }
        if (this.value === 'Gaji') {
            document.getElementById('edit-form-gaji').classList.remove('d-none');
        }
        if (this.value === 'Hutang') {
            document.getElementById('edit-form-hutang').classList.remove('d-none');
        }
    });

    function hitungGajiEdit() {
        let total =
            (parseInt(edit_gaji_pokok.value) || 0)
            - (parseInt(edit_potongan.value) || 0)
            + (parseInt(edit_bonus.value) || 0);

        edit_total_gaji.value = total;
    }

    ['edit_gaji_pokok', 'edit_potongan', 'edit_bonus'].forEach(id => {
        document.getElementById(id).addEventListener('input', hitungGajiEdit);
    });

    function openEditModal(data) {
        document.getElementById('formEditPengeluaran').action = `/admin/pengeluaran/edit/${data.id}`;

        edit_id.value = data.id;
        edit_tanggal.value = data.tanggal.replace(' ', 'T');
        edit_jenis.value = data.jenis_pengeluaran;
        edit_status.value = data.status;

        // Trigger form tampil
        editJenis.dispatchEvent(new Event('change'));

        setTimeout(() => {

            if (data.jenis_pengeluaran === 'Gaji') {
                edit_karyawan.value = String(data.refrensi_id); // ⚠️ STRING
                edit_gaji_pokok.value = data.gaji_pokok ?? 0;
                edit_potongan.value = data.potongan ?? 0;
                edit_bonus.value = data.bonus ?? 0;
                hitungGajiEdit();
            }

            if (data.jenis_pengeluaran === 'Hutang') {
                edit_hutang.value = String(data.refrensi_id);
                edit_nominal_hutang.value = data.nominal_pengeluaran;
            }

            if (['Operasional', 'Lainnya'].includes(data.jenis_pengeluaran)) {
                edit_tujuan.value = data.tujuan_pengeluaran;
                edit_nominal_operasional.value = data.nominal_pengeluaran;
            }

        }, 50); // ⏱️ DOM settle

        new bootstrap.Modal('#editPengeluaran').show();
    }
</script>