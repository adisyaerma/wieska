@extends('admin.master')
@section('title', 'Detail Karyawan')
@section('karyawanActive', 'active')
@section('masterDataActive', 'active open')
@section('isi')
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-md-12">
        <div class="card mb-6">
          <form action="{{ route('karyawan_detail.update', $karyawan->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!-- Account -->
            <div class="card-body">
              <div class="d-flex align-items-start align-items-sm-center gap-6 pb-4 border-bottom">
                <!-- Foto -->
                <img
                  src="{{ $karyawan->foto ? asset('storage/' . $karyawan->foto) : 'https://img.freepik.com/premium-vector/person-with-blue-shirt-that-says-name-person_1029948-7040.jpg' }}"
                  alt="user-avatar" class="d-block w-px-100 h-px-100 rounded" id="uploadedAvatar" />


                <div class="button-wrapper">
                  <!-- Nama -->
                  <div class="fs-4 mb-2">{{ $karyawan->nama }}</div>

                  <!-- Input file -->
                  <label for="foto" class="btn btn-primary btn-sm me-3 mb-4" tabindex="0">
                    <span class="d-none d-sm-block">Ubah Foto</span>
                    <i class="icon-base bx bx-upload d-block d-sm-none"></i>
                    <input type="file" id="foto" name="foto" class="account-file-input" hidden
                      accept="image/png, image/jpeg, image/jpg" onchange="previewImage(event)" />
                  </label>
                </div>
              </div>
            </div>

            <div class="card-body pt-4">
              <div class="row g-4">
                <!-- Nama & Jabatan -->
                <div class="col-md-6">
                  <label for="Nama" class="form-label">Nama</label>
                  <input class="form-control" type="text" name="nama" id="nama" value="{{ old('nama', $karyawan->nama) }}"
                    data-original="{{ $karyawan->nama }}" />
                </div>
                <div class="col-md-6">
                  <label for="Jabatan" class="form-label">Jabatan</label>
                  <select class="form-select" id="jabatan" name="jabatan">
                    <option value="" disabled {{ $karyawan->jabatan ? '' : 'selected' }}>-- Pilih Jabatan --</option>
                    <option value="admin" {{ old('jabatan', $karyawan->jabatan) == 'admin' ? 'selected' : '' }}>Admin
                    </option>
                    <option value="kasir" {{ old('jabatan', $karyawan->jabatan) == 'kasir' ? 'selected' : '' }}>Kasir
                    </option>
                  </select>
                </div>


                <!-- No.Telepon & Tanggal -->
                <div class="col-md-6">
                  <label class="form-label" for="phoneNumber">No. Telepon</label>
                  <div class="input-group">
                    <span class="input-group-text">(+62)</span>
                    <input type="text" id="phoneNumber" name="no_telp" class="form-control"
                      value="{{ old('no_telp', $karyawan->no_telp) }}" data-original="{{ $karyawan->no_telp }}" />
                  </div>
                </div>
                <div class="col-md-6">
                  <label for="Tanggal" class="form-label">Tanggal Bergabung</label>
                  <input class="form-control" type="date" id="tanggal" name="tgl_bergabung"
                    value="{{ old('tgl_bergabung', $karyawan->tgl_bergabung) }}"
                    data-original="{{ $karyawan->tgl_bergabung }}" />
                </div>

                <!-- Email & Password -->
                <div class="col-md-6">
                  <label for="email" class="form-label">E-mail</label>
                  <input class="form-control" type="email" id="email" name="email"
                    value="{{ old('email', $karyawan->email) }}" data-original="{{ $karyawan->email }}" />
                </div>
                <div class="col-md-6">
                  <label for="Password" class="form-label">Password</label>
                  <input type="password" class="form-control" id="password" name="password"
                    placeholder="Isi jika ingin ganti password" />
                </div>
                <!-- Alamat full width -->
                <div class="col-12">
                  <label for="Alamat" class="form-label">Alamat</label>
                  <textarea class="form-control" id="alamat" name="alamat" rows="3"
                    data-original="{{ $karyawan->alamat }}">{{ old('alamat', $karyawan->alamat) }}</textarea>
                </div>
              </div>

              <!-- Tombol -->
              <div class="mt-4">
                <button type="submit" class="btn btn-primary btn-sm me-3">Simpan Perubahan</button>
                <button type="reset" class="btn btn-outline-secondary btn-sm">Reset</button>
              </div>
            </div>

          </form>
          <!-- /Account -->
        </div>
      </div>
    </div>
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
        let table = $('#pesananTable').DataTable({
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
    <script>
      function previewImage(event) {
        const input = event.target;
        const reader = new FileReader();

        reader.onload = function () {
          const avatar = document.getElementById('uploadedAvatar');
          avatar.src = reader.result; // tampilkan gambar baru
        };

        if (input.files && input.files[0]) {
          reader.readAsDataURL(input.files[0]); // baca file sebagai base64
        }
      }
    </script>

    <script>
      document.querySelector('button[type="reset"]').addEventListener('click', function (e) {
        e.preventDefault(); // cegah reset bawaan
        // ambil semua input
        document.querySelectorAll('.form-control').forEach(input => {
          if (input.dataset.original !== undefined) {
            input.value = input.dataset.original; // reset ke nilai awal database
          }
        });

        // reset preview foto juga
        const avatar = document.getElementById('uploadedAvatar');
        const originalFoto = "{{ $karyawan->foto ? asset('storage/' . $karyawan->foto) : 'https://img.freepik.com/premium-vector/person-with-blue-shirt-that-says-name-person_1029948-7040.jpg' }}";
        avatar.src = originalFoto;
        document.getElementById('foto').value = ''; // kosongkan input file
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
      document.addEventListener('DOMContentLoaded', () => {

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
                  });
    </script>

  @endpush
@endsection