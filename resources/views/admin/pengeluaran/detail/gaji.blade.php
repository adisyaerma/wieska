@extends('admin.master')
@section('title', 'Detail Pengeluaran')
@section('pengeluaranActive', 'active')
@section('masterDataActive', 'active open')

@section('isi')
    <div class="container-xxl container-p-y">

        <!-- HEADER -->
        <div class="card mb-4 border-0 shadow-sm">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                <div>
                    <h4 class="mb-1 text-dark">Detail Pengeluaran</h4>
                    <small class="text-muted">
                        {{ \Carbon\Carbon::parse($data->tanggal)->translatedFormat('d F Y, H:i') }}
                    </small>
                </div>
                <span
                    class="badge {{ $data->status == 'Valid' ? 'bg-success bg-opacity-25 text-success' : 'bg-secondary bg-opacity-25 text-secondary' }} fs-6">
                    {{ $data->status }}
                </span>
            </div>
        </div>

        <!-- INFORMASI UTAMA -->
        <div class="row g-4">

            <!-- KIRI: Informasi Pengeluaran -->
            <div class="col-lg-6 col-md-12">
                <div class="card shadow-sm h-100 border-0">
                    <div class="card-header bg-white border-bottom">
                        <h6 class="mb-0 text-dark fw-semibold">Informasi Pengeluaran</h6>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless table-sm mb-0">
                            <tr>
                                <th width="40%">Jenis</th>
                                <td class="text-dark text-end">{{ $data->jenis_pengeluaran }}</td>
                            </tr>
                            <tr>
                                <th>Tujuan</th>
                                <td class="text-dark text-end">{{ $data->tujuan_pengeluaran ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Nominal</th>
                                <td class="text-dark fw-semibold text-end">
                                    Rp {{ number_format($data->nominal_pengeluaran, 0, ',', '.') }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- KANAN: Detail Gaji -->
            <div class="col-lg-6 col-md-12">
                @if($data->jenis_pengeluaran === 'Gaji')
                    <div class="card shadow-sm h-100 border-0">
                        <div class="card-header bg-white border-bottom">
                            <h6 class="mb-0 text-dark fw-semibold">Detail Gaji Karyawan</h6>
                        </div>
                        <div class="card-body p-3">
                            <table class="table table-sm table-borderless mb-0">
                                <tr>
                                    <th width="35%">Karyawan</th>
                                    <td class="text-dark text-end">{{ $data->nama_karyawan }}</td>
                                </tr>
                                <tr>
                                    <th>Gaji Pokok</th>
                                    <td class="text-dark text-end">
                                        Rp {{ number_format($data->gaji_pokok, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Potongan</th>
                                    <td class="text-dark text-end">
                                        Rp {{ number_format($data->potongan, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Bonus</th>
                                    <td class="text-dark text-end">
                                        Rp {{ number_format($data->bonus, 0, ',', '.') }}
                                    </td>
                                </tr>
                                <tr class="border-top pt-2 fw-semibold">
                                    <th>Total Gaji</th>
                                    <td class="text-dark text-end">
                                        Rp {{ number_format($data->nominal_pengeluaran, 0, ',', '.') }}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- ACTION -->
        <div class="mt-4">
            <a href="{{ route('pengeluaran.index') }}" class="btn btn-outline-dark">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {

                // SweetAlert Hapus
                const forms = document.querySelectorAll('.form-hapus');
                forms.forEach(form => {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Yakin hapus data ini?',
                            text: 'Data yang dihapus tidak bisa dikembalikan!',
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#0d6efd',
                            cancelButtonColor: '#6c757d',
                            confirmButtonText: 'Ya, hapus!',
                            cancelButtonText: 'Batal'
                        }).then((result) => {
                            if (result.isConfirmed) form.submit();
                        });
                    });
                });

                @if(session('success'))
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('success') }}', showConfirmButton: false, timer: 2000 });
                @endif
                @if(session('updated'))
                    Swal.fire({ icon: 'success', title: 'Berhasil!', text: '{{ session('updated') }}', showConfirmButton: false, timer: 2000 });
                @endif
                @if(session('deleted'))
                    Swal.fire({ icon: 'success', title: 'Dihapus!', text: '{{ session('deleted') }}', showConfirmButton: false, timer: 2000 });
                @endif

        });
        </script>
    @endpush
@endsection