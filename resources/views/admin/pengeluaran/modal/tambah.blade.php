<!-- Modal -->
<div class="modal fade" id="tambahPengeluaran" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog" role="document">
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
                        <option value="Lainnya">Lainnya</option>
                    </select>
                </div>

                <!-- OPERASIONAL / LAINNYA -->
                <div id="form-operasional" class="d-none">
                    <div class="mb-3">
                        <label>Tujuan Pengeluaran</label>
                        <input type="text" name="tujuan_pengeluaran" placeholder="Masukkan Tujuan Pengeluaran" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Nominal</label>
                        <input type="number" name="nominal_pengeluaran" placeholder="Masukkan Nominal" class="form-control">
                    </div>
                </div>

                <!-- GAJI -->
                <div id="form-gaji" class="d-none">
                    <div class="mb-3">
                        <label>Karyawan</label>
                        <select name="refrensi_id_gaji" class="form-control">
                            @foreach($karyawan as $k)
                                <option value="{{ $k->id }}">{{ $k->nama }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Gaji Pokok</label>
                        <input type="number" id="gaji_pokok" name="gaji_pokok" placeholder="Masukkan Gaji Pokok" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="mb-3">
                                <label>Potongan</label>
                                <input type="number" id="potongan" name="potongan" placeholder="Masukkan Potongan Gaji" class="form-control">
                            </div>
                        </div>
                            <div class="col">
                                <div class="mb-3">
                                    <label>Bonus</label>
                                    <input type="number" id="bonus" name="bonus" placeholder="Masukkan Bonus Gaji" class="form-control">
                                </div>
                            </div>
                    </div>

                    <div class="mb-3">
                        <label>Total Gaji</label>
                        <input type="number" id="total_gaji" class="form-control" placeholder="Total Gaji" readonly>
                    </div>

                    <input type="hidden" name="nominal_pengeluaranGaji" id="nominal_pengeluaran">
                </div>

                <!-- HUTANG -->
                <div id="form-hutang" class="d-none">
                    <div class="mb-3">
                        <label>Bayar Hutang</label>
                        <select name="refrensi_id_hutang" class="form-control">
                            <option value="">-- Pilih --</option>
                            @foreach($hutang as $h)
                                <option value="{{ $h->id }}">
                                    {{ $h->pihak }} - Rp{{ number_format($h->total_hutang, 0, ',', '.') }} - Rp {{ number_format($h->sisa_hutang, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nominal Bayar</label>
                        <input type="number" name="nominal_pengeluaran_hutang" placeholder="Masukkan Nominal Bayar Hutang" class="form-control">
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
                    Batala
                </button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
    const jenis = document.getElementById('jenis_pengeluaran');

    jenis.addEventListener('change', function () {
        document.getElementById('form-operasional').classList.add('d-none');
        document.getElementById('form-gaji').classList.add('d-none');
        document.getElementById('form-hutang').classList.add('d-none');

        if (this.value === 'Operasional' || this.value === 'Lainnya') {
            document.getElementById('form-operasional').classList.remove('d-none');
        }

        if (this.value === 'Gaji') {
            document.getElementById('form-gaji').classList.remove('d-none');
        }

        if (this.value === 'Hutang') {
            document.getElementById('form-hutang').classList.remove('d-none');
        }
    });

    function hitungGaji() {
        let pokok = parseInt(document.getElementById('gaji_pokok').value) || 0;
        let potongan = parseInt(document.getElementById('potongan').value) || 0;
        let bonus = parseInt(document.getElementById('bonus').value) || 0;

        let total = pokok - potongan + bonus;
        document.getElementById('total_gaji').value = total;
        document.getElementById('nominal_pengeluaran').value = total;
    }

    ['gaji_pokok', 'potongan', 'bonus'].forEach(id => {
        document.getElementById(id).addEventListener('input', hitungGaji);
    });
</script>