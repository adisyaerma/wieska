@extends('admin.master')
@section('title', 'Karyawan')
@section('karyawanActive', 'active')
@section('masterDataActive', 'active open')
@section('isi')
  <div class="container-xxl flex-grow-1 container-p-y">
    <!-- Basic Bootstrap Table -->
    <div class="card mt-1">
      <div class="card-header d-flex justify-content-between align-items-center py-5">
        <h5 class="mb-0 fs-4">Data Karyawan</h5>

        <!-- Kanan: export dan tambah -->
        <div class="d-flex align-items-center gap-2">
          <!-- Tempat tombol export DataTables -->
          <div id="exportButtons"></div>

          <!-- Button trigger modal -->
          <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahKaryawan">
            Tambah
          </button>
        </div>
      </div>

      <!-- Modal -->
      <div class="modal fade" id="tambahKaryawan" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Tambah Data Karyawan</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('karyawan.store') }}" method="POST" class="modal-content"
              enctype="multipart/form-data">
              @csrf
              <div class="modal-body">
                <div class="row">
                  <div class="col mb-6">
                    <label for="fotoKaryawan" class="form-label">Foto</label>
                    <input type="file" id="fotoKaryawan" name="foto" class="form-control" accept="image/*" />
                  </div>
                </div>
                <div class="row">
                  <div class="col mb-6">
                    <label for="nameBasic" class="form-label">Nama Karyawan</label>
                    <input type="text" id="nameBasic" name="nama" class="form-control"
                      placeholder="Masukkan Nama Karyawan" />
                  </div>
                </div>
                <div class="row">
                  <div class="col mb-6">
                    <label for="jabatanBasic" class="form-label">Jabatan/Posisi</label>
                    <select class="form-select" id="jabatanBasic" name="jabatan" aria-label="Default select example"
                      required>
                      <option value="" selected disabled>Pilih Jabatan/Posisi</option>
                      <option value="admin">Admin</option>
                      <option value="kasir">Kasir</option>
                    </select>
                  </div>
                </div>
                <div class="row">
                  <div class="col mb-6">
                    <label for="nomor" class="form-label">No. Telpon</label>
                    <input type="text" id="nomor" name="no_telp" class="form-control" placeholder="Masukkan No Telepon" />
                  </div>
                </div>
                <div class="row">
                  <div class="col mb-6">
                    <label for="nomor" class="form-label">Email</label>
                    <input type="email" id="nomor" name="email" class="form-control" placeholder="Masukkan Email" />
                  </div>
                </div>
                <div class="row">
                  <div class="col mb-6">
                    <label for="nomor" class="form-label">Alamat</label>
                    <textarea class="form-control" name="alamat" id="exampleFormControlTextarea1" rows="3"></textarea>
                  </div>
                </div>
                <div class="row">
                  <div class="col mb-6">
                    <label for="tanggal" class="form-label">Tanggal Bergabung</label>
                    <input type="date" id="tanggal" name="tgl_bergabung" class="form-control"
                      value="{{ date('Y-m-d') }}" />
                  </div>
                </div>
                <div class="row">
                  <div class="col mb-6">
                    <label for="nomor" class="form-label">Password</label>
                    <input type="password" id="nomor" name="password" class="form-control"
                      placeholder="Masukkan Password" />
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
      </div>

      <div class="table-responsive text-nowrap">
        <table class="table" id="karyawanTable">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>Jabatan/Posisi</th>
              <th>No.Telepon</th>
              <th>Tanggal Bergabung</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($karyawans as $i => $karyawan)
              <tr>
                <td><span>{{ $i + 1 }}</span></td>
                <td>{{ $karyawan->nama }}</td>
                <td>{{ $karyawan->jabatan }}</td>
                <td>{{ $karyawan->no_telp }}</td>
                <td>
                  <span class="badge bg-label-primary me-1">
                    {{ \Carbon\Carbon::parse($karyawan->tgl_bergabung)->format('d/m/Y') }}
                  </span>
                </td>

                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="icon-base bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a href="{{route('karyawan_detail.edit', $karyawan->id)}}" class="dropdown-item" type="button">
                        <i class="icon-base bxr bx-clipboard-detail me-1"></i>
                        Detail
                      </a>
                      <form action="{{ route('karyawan.destroy', $karyawan->id) }}" method="POST" class="form-hapus">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="dropdown-item">
                          <i class="icon-base bx bx-trash me-1"></i> Hapus
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

    <!--/ Basic Bootstrap Table -->
  </div>

  @push('scripts')
    <style>
      div.dataTables_filter {
        padding: 0.75rem 1rem;
      }

      div.dataTables_info {
        padding: 0.75rem 1rem;
      }

      div.dataTables_paginate {
        padding: 0.75rem 1rem;
      }

      div.dataTables_length {
        padding: 0.75rem 1rem;
      }
    </style>

    <script>
      $(document).ready(function () {
        let table = $('#karyawanTable').DataTable({
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

        table.buttons().container().appendTo('#exportButtons');
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


  @endpush
@endsection