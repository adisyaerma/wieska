<!-- Modal -->
<div class="modal fade" id="tambahPengeluaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form action="{{ route('pengeluaran.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel1">Tambah Satuan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Jenis Pengeluaran -->
                <div class="mb-3">
                    <label>Jenis Pengeluaran</label>
                    <select name="jenis_pengeluaran" id="jenis_pengeluaran" class="form-control">
                        <option value="">-- Pilih --</option>
                        <option value="Operasional">Operasional</option>
                        <option value="Gaji">Gaji</option>
                        <option value="Hutang">Hutang</option>
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- Operasional / Lainnya -->
                <div id="form_operasional" class="d-none">
                    <div class="mb-3">
                        <label>Tujuan Pengeluaran</label>
                        <input type="text" name="tujuan_pengeluaran" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Nominal</label>
                        <input type="number" name="nominal_pengeluaran" class="form-control">
                    </div>
                </div>

                <!-- Gaji -->
                <div id="form_gaji" class="d-none">
                    <div class="mb-3">
                        <label>Karyawan</label>
                        <select name="refrensi_id" class="form-control">
                            @foreach ($karyawan as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="col">
                            <label>Gaji Pokok</label>
                            <input type="number" id="gaji_pokok" name="gaji_pokok" class="form-control hitung-gaji">
                        </div>
                        <div class="col">
                            <label>Potongan</label>
                            <input type="number" id="potongan" name="potongan" class="form-control hitung-gaji">
                        </div>
                        <div class="col">
                            <label>Bonus</label>
                            <input type="number" id="bonus" name="bonus" class="form-control hitung-gaji">
                        </div>
                    </div>

                    <div class="mt-3">
                        <label>Total Gaji</label>
                        <input type="number" id="total_gaji" name="nominal_pengeluaran" class="form-control" readonly>
                    </div>
                </div>

                <!-- Hutang -->
                <div id="form_hutang" class="d-none">
                    <div class="mb-3">
                        <label>Daftar Hutang</label>
                        <select name="refrensi_id" class="form-control">
                            @foreach ($hutang as $h)
                                <option value="{{ $h->id }}">{{ $h->pihak }} - {{ number_format($h->sisa_hutang) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nominal Bayar</label>
                        <input type="number" name="nominal_pengeluaran" class="form-control">
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
<script>
    document.getElementById('jenis_pengeluaran').addEventListener('change', function () {
        document.getElementById('form_operasional').classList.add('d-none');
        document.getElementById('form_gaji').classList.add('d-none');
        document.getElementById('form_hutang').classList.add('d-none');

        if (this.value === 'Operasional' || this.value === 'Lainnya') {
            document.getElementById('form_operasional').classList.remove('d-none');
        }
        if (this.value === 'Gaji') {
            document.getElementById('form_gaji').classList.remove('d-none');
        }
        if (this.value === 'Hutang') {
            document.getElementById('form_hutang').classList.remove('d-none');
        }
    });

    document.querySelectorAll('.hitung-gaji').forEach(input => {
        input.addEventListener('input', function () {
            let pokok = parseInt(document.getElementById('gaji_pokok').value) || 0;
            let potongan = parseInt(document.getElementById('potongan').value) || 0;
            let bonus = parseInt(document.getElementById('bonus').value) || 0;

            document.getElementById('total_gaji').value = (pokok + bonus) - potongan;
        });
    });
</script>