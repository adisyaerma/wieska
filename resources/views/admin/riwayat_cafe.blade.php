@extends('admin.master')
@section('title', 'Riwayat Cafe')
@section('riwayatCafeActive', 'active')
@section('laporanActive', 'active open')
@section('isi')
  <div class="container-xxl flex-grow-1 container-p-y">
    <!-- Basic Bootstrap Table -->
    <div class="card mt-1">
      <div class="card-header d-flex justify-content-between align-items-center py-5">
        <h5 class="mb-0 fs-4">Riwayat Pesanan Cafe</h5>

        <!-- Kanan: export dan tambah -->
        <div class="d-flex align-items-center gap-2">
          <div id="exportButtons"></div>
        </div>
      </div>

      <div class="table-responsive text-nowrap p-3">
        <!-- Baris atas: Filter tanggal (kiri) + Search (kanan bawaan DataTables) -->
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex align-items-center gap-2 ms-3" id="filterContainer">
            <label for="minDate" class="form-label mb-0 fw-medium">Dari:</label>
            <input type="date" id="minDate" class="form-control form-control-sm" style="width: 160px;">
            <label for="maxDate" class="form-label mb-0 fw-medium">Sampai:</label>
            <input type="date" id="maxDate" class="form-control form-control-sm" style="width: 160px;">
          </div>

          <!-- Tempat Search DataTables -->
          <div id="tableSearchContainer"></div>
        </div>

        <table class="table" id="pesananTable">
          <thead>
            <tr>
              <th>No</th>
              <th>Tanggal</th>
              <th>Nama Pelanggan</th>
              <th>Kasir</th>
              <th>Subtotal</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach ($cafes as $i => $c)
              <tr>
                <td>{{ $i + 1 }}</td>
                <td>
                  <span class="badge bg-label-primary me-1">
                    {{ \Carbon\Carbon::parse($c->tanggal)->format('d/m/Y') }}
                  </span>
                </td>
                <td>{{ $c->nama_pelanggan }}</td>
                <td>{{ optional($c->karyawan)->nama ?? '—' }}</td>
                <td>Rp {{ number_format($c->subtotal, 0, ',', '.') }}</td>
                <td>
                  <div class="dropdown">
                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                      <i class="icon-base bx bx-dots-vertical-rounded"></i>
                    </button>
                    <div class="dropdown-menu">
                      <a href="{{ route('riwayat_cafe.detail', $c->id) }}" class="dropdown-item">
                        <i class="icon-base bx bx-clipboard me-1"></i> Detail
                      </a>
                      <form class="form-hapus" action="{{ route('riwayat_cafe.destroy', $c->id) }}" method="POST">
                        @csrf @method('DELETE')
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
          <tfoot>
            <tr>
              <th colspan="4" class="text-end">Total:</th>
              <th id="totalSubtotal" class="text-start">Rp 0</th>
              <th></th>
            </tr>
          </tfoot>

        </table>
      </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="editBarang" tabindex="-1" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel1">
              Edit Data Barang
            </h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="row">
              <div class="col mb-6">
                <label for="nameBasic" class="form-label">Nama Barang</label>
                <input type="text" id="nameBasic" class="form-control" placeholder="Masukkan Nama Barang" />
              </div>
            </div>
            <div class="row g-6">
              <div class="col mb-0">
                <label for="emailBasic" class="form-label">Kode Barang</label>
                <input type="text" id="emailBasic" class="form-control" placeholder="Masukkan Kode Barang" />
              </div>
              <div class="col mb-0">
                <label for="exampleFormControlSelect1" class="form-label">Kategori</label>
                <select class="form-select" id="exampleFormControlSelect1" aria-label="Default select example">
                  <option selected>Pilih Kategori</option>
                  <option value="1">One</option>
                  <option value="2">Two</option>
                  <option value="3">Three</option>
                </select>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
              Batal
            </button>
            <button type="button" class="btn btn-primary">
              Simpan
            </button>
          </div>
        </div>
      </div>
    </div>
    <!--/ Basic Bootstrap Table -->
  </div>
  @push('scripts')
    <style>
      /* Biar search box lebih rapi */
      div.dataTables_filter {
        padding: 0.75rem 1rem;
      }

      /* Biar tulisan "Showing x to y" lebih rapi */
      div.dataTables_info {
        padding: 0.75rem 1rem;
      }

      /* Biar pagination (Previous/Next) ada spasi */
      div.dataTables_paginate {
        padding: 0.75rem 1rem;
      }

      /* Biar dropdown "Show entries" juga ada padding */
      div.dataTables_length {
        padding: 0.75rem 1rem;
      }
    </style>
    <script>
      $(document).ready(function () {
        // 🔹 Deteksi otomatis kolom tanggal
        let dateColIndex = 1;
        $('#pesananTable thead th').each(function (i) {
          const txt = $(this).text().toLowerCase();
          if (txt.includes('tgl') || txt.includes('tanggal')) {
            dateColIndex = i;
            return false;
          }
        });

        // 🔹 Fungsi bantu
        function stripTags(html) {
          return $('<div/>').html(html).text().trim();
        }

        function parseCellDate(s) {
          if (!s) return null;
          s = s.trim();
          const match = s.match(/^(\d{1,2})[\/\-](\d{1,2})[\/\-](\d{4})$/);
          if (match) return new Date(match[3], match[2] - 1, match[1]);
          const dt = new Date(s);
          return isNaN(dt) ? null : new Date(dt.getFullYear(), dt.getMonth(), dt.getDate());
        }

        function parseInputDate(val) {
          if (!val) return null;
          const parts = val.split('-');
          return new Date(parts[0], parts[1] - 1, parts[2]);
        }

        // 🔹 Filter berdasarkan range tanggal
        $.fn.dataTable.ext.search.push(function (settings, data) {
          const min = parseInputDate($('#minDate').val());
          const max = parseInputDate($('#maxDate').val());
          const dateText = stripTags(data[dateColIndex]);
          const date = parseCellDate(dateText);
          if (!date) return true;
          if (min && date < min) return false;
          if (max && date > max) return false;
          return true;
        });

        // 🔹 Inisialisasi DataTable
        let table = $('#pesananTable').DataTable({
          dom: 'Bfrtip',
          buttons: [
            {
              extend: 'excel',
              text: '<i class="bx bx-file me-1"></i> Excel',
              className: 'btn btn-sm btn-success',
              exportOptions: { modifier: { search: 'applied' } }
            },
            {
              extend: 'pdf',
              text: '<i class="bx bx-file me-1"></i> PDF',
              className: 'btn btn-sm btn-danger',
              exportOptions: { modifier: { search: 'applied' } }
            },
            {
              extend: 'print',
              text: '<i class="bx bx-printer me-1"></i> Print',
              className: 'btn btn-sm btn-secondary',
              exportOptions: { modifier: { search: 'applied' } }
            },
            {
              extend: 'colvis',
              text: '<i class="bx bx-show me-1"></i> Kolom',
              className: 'btn btn-sm btn-info'
            }
          ],
          order: [[0, 'asc']],

          // 🔹 Hitung total subtotal
          footerCallback: function (row, data, start, end, display) {
            let api = this.api();

            // Kolom Subtotal = index ke-4 (bukan 5)
            let total = api
              .column(4, { search: 'applied' })
              .data()
              .reduce((sum, val) => {
                // Bersihkan HTML
                let text = $('<div>').html(val).text().trim();

                // Ambil angka dari teks (contoh: "Rp 25.000" → "25000")
                let cleaned = text.replace(/[^\d]/g, '');
                let num = cleaned ? parseFloat(cleaned) : 0;

                return sum + num;
              }, 0);

            // Format angka menjadi Rp dengan pemisah ribuan
            let formatted = new Intl.NumberFormat('id-ID').format(total);
            $(api.column(4).footer()).html('Rp ' + formatted);
          },

          initComplete: function () {
            $("#pesananTable_filter").appendTo("#tableSearchContainer");
            $("#pesananTable_filter label").addClass("mb-0");
          }
        });

        // 🔹 Tempatkan tombol export di kanan atas
        table.buttons().container().appendTo('#exportButtons');

        // 🔹 Jalankan filter saat tanggal berubah
        $('#minDate, #maxDate').on('change', function () {
          table.draw();
        });
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

        // ✅ Notifikasi tambah berhasil
        @if(session('updated'))
          Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('updated') }}',
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

    <!-- <script>
                                                              $(document).ready(function () {
                                                                // Jika DataTable sudah ada, hapus dulu
                                                                if ($.fn.DataTable.isDataTable('#barangTable')) {
                                                                  $('#barangTable').DataTable().clear().destroy();
                                                                }

                                                                // Inisialisasi ulang DataTable
                                                                const table = $('#barangTable').DataTable({
                                                                  dom: '<"d-flex justify-content-between align-items-center mb-3"lf>tip',
                                                                  language: {
                                                                    search: "Cari:",
                                                                    lengthMenu: "Tampilkan _MENU_ data",
                                                                    info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                                                                    paginate: { previous: "Sebelumnya", next: "Selanjutnya" },
                                                                    zeroRecords: "Tidak ada data ditemukan"
                                                                  }
                                                                });

                                                                // Pindahkan filter tanggal ke kiri search
                                                                $('#filterContainer').prependTo('.dataTables_filter').css({
                                                                  display: 'flex',
                                                                  alignItems: 'center',
                                                                  gap: '6px',
                                                                  marginRight: '15px'
                                                                });

                                                                // Fungsi filter tanggal
                                                                $('#filterBtn').on('click', function () {
                                                                  const start = $('#startDate').val();
                                                                  const end = $('#endDate').val();
                                                                  let total = 0;

                                                                  table.rows().every(function () {
                                                                    const tanggalText = $(this.node()).find('td:nth-child(2) span').text();
                                                                    const [day, month, year] = tanggalText.split('/');
                                                                    const tanggal = new Date(`${year}-${month}-${day}`);
                                                                    let show = true;

                                                                    if (start && tanggal < new Date(start)) show = false;
                                                                    if (end && tanggal > new Date(end)) show = false;

                                                                    $(this.node()).toggle(show);

                                                                    if (show) {
                                                                      const subtotalText = $(this.node()).find('td:nth-child(5)').text().replace(/[^\d]/g, '');
                                                                      total += parseInt(subtotalText || 0);
                                                                    }
                                                                  });

                                                                  $('#totalSubtotal').text('Rp ' + total.toLocaleString('id-ID'));
                                                                });
                                                              });

                                                            </script> -->

  @endpush


@endsection