@extends('admin.master')
@section('title', 'Dashboard')
@section('dashboardActive', 'active')
@section('isi')
  <div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
      <div class="col-xxl-12 col-lg-12 col-md-12 order-1">
        <div class="row">
          <div class="col-lg-4 col-md-12 col-6 mb-6">
            <div class="card h-100">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                  <div class="avatar flex-shrink-0">
                    <img src="{{ asset('template/sneat/assets/img/icons/unicons/chart-success.png') }}"
                      alt="chart success" class="rounded" />
                  </div>
                  <!-- <div class="dropdown">
                                                              <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="icon-base bx bx-dots-vertical-rounded text-body-secondary"></i>
                                                              </button>
                                                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                                                <a class="dropdown-item" href="javascript:void(0);">Hari Ini</a>
                                                                <a class="dropdown-item" href="javascript:void(0);">Bulan Ini</a>
                                                                <a class="dropdown-item" href="javascript:void(0);">Tahun Ini</a>
                                                              </div>
                                                            </div> -->
                </div>
                <p class="mb-1">Jumlah Menu Terjual Hari Ini</p>
                <h4 class="card-title mb-3">{{ $jumlahCafeTerjual }}</h4>
                @if($persenCafe > 0)
                  <small class="text-success fw-medium">
                    <i class="icon-base bx bx-up-arrow-alt"></i> +{{ number_format($persenCafe, 2) }}%
                  </small>
                @elseif($persenCafe < 0)
                  <small class="text-danger fw-medium">
                    <i class="icon-base bx bx-down-arrow-alt"></i> {{ number_format($persenCafe, 2) }}%
                  </small>
                @else
                  <small class="text-muted">0%</small>
                @endif
              </div>
            </div>
          </div>
          <div class="col-lg-4 col-md-12 col-6 mb-6">
            <div class="card h-100">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                  <div class="avatar flex-shrink-0">
                    <img src="{{ asset('template/sneat/assets/img/icons/unicons/chart.png') }}" alt="chart success"
                      class="rounded" />

                  </div>
                  <!-- <div class="dropdown">
                                                              <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="icon-base bx bx-dots-vertical-rounded text-body-secondary"></i>
                                                              </button>
                                                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                                                <a class="dropdown-item" href="javascript:void(0);">Hari Ini</a>
                                                                <a class="dropdown-item" href="javascript:void(0);">Bulan Ini</a>
                                                                <a class="dropdown-item" href="javascript:void(0);">Tahun Ini</a>
                                                              </div>
                                                            </div> -->
                </div>
                <p class="mb-1">Jumlah Tiket Terjual Hari Ini</p>
                <h4 class="card-title mb-3">{{ $jumlahTiketTerjual }}</h4>
                @if($persenTiket > 0)
                  <small class="text-success fw-medium">
                    <i class="icon-base bx bx-up-arrow-alt"></i> +{{ number_format($persenTiket, 2) }}%
                  </small>
                @elseif($persenTiket < 0)
                  <small class="text-danger fw-medium">
                    <i class="icon-base bx bx-down-arrow-alt"></i> {{ number_format($persenTiket, 2) }}%
                  </small>
                @else
                  <small class="text-muted">0%</small>
                @endif
              </div>
            </div>
          </div>
          <div class="col-lg-4 mb-6 payments">
            <div class="card h-100">
              <div class="card-body">
                <div class="card-title d-flex align-items-start justify-content-between mb-4">
                  <div class="avatar flex-shrink-0">
                    <img src="{{ asset('template/sneat/assets/img/icons/unicons/wallet-info.png') }}" alt="chart success"
                      class="rounded" />

                  </div>
                  <!-- <div class="dropdown">
                                                              <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown" aria-haspopup="true"
                                                                aria-expanded="false">
                                                                <i class="icon-base bx bx-dots-vertical-rounded text-body-secondary"></i>
                                                              </button>
                                                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                                                <a class="dropdown-item" href="javascript:void(0);">Hari Ini</a>
                                                                <a class="dropdown-item" href="javascript:void(0);">Bulan Ini</a>
                                                                <a class="dropdown-item" href="javascript:void(0);">Tahun Ini</a>
                                                              </div>
                                                            </div> -->
                </div>
                <p class="mb-1">Total Pendapatan Hari Ini</p>
                <h4 class="card-title mb-3">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h4>
                @if($persenPendapatan > 0)
                  <small class="text-success fw-medium">
                    <i class="icon-base bx bx-up-arrow-alt"></i> +{{ number_format($persenPendapatan, 2) }}%
                  </small>
                @elseif($persenPendapatan < 0)
                  <small class="text-danger fw-medium">
                    <i class="icon-base bx bx-down-arrow-alt"></i> {{ number_format($persenPendapatan, 2) }}%
                  </small>
                @else
                  <small class="text-muted">0%</small>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
      <!-- Total Revenue -->
      <div class="col-12 col-xxl-8 order-2 order-md-3 order-xxl-2 mb-6 total-revenue">
        <div class="card">
          <div class="row row-bordered g-0">
            <div class="col-lg-7">
              <div class="card-header d-flex align-items-center justify-content-between">
                <div class="card-title mb-0">
                  <h5 class="m-0 me-2">Grafik Penjualan Tiket Minggu Ini</h5>
                  <canvas id="tiketChart" height="400" width="450"></canvas>
                </div>
              </div>
              <!-- <canvas id="tiketChart" style="height:400px; width: 200px;"></canvas> -->
            </div>
            <div class="col-lg-5">
              <div class="card-body p-2 d-flex align-items-center flex-column">
                <div class="card-header d-flex align-items-center justify-content-between">
                  <div class="card-title mb-0">
                    <h5 class="m-0 me-2">Grafik Penghasilan Minggu Ini</h5>
                  </div>
                </div>
                <div class="card-body">
                  <div class="tab-content p-0">
                    <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                      <!-- <div class="d-flex mb-6">
                            <div class="avatar flex-shrink-0 me-3">
                              <img src="template/sneat/assets/img/icons/unicons/wallet.png" alt="User" />
                            </div>
                          </div> -->
                      <canvas id="pendapatanChart" style="width:100%; max-width:700px; height:350px;"></canvas>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Basic Bootstrap Table -->
    <div class="card mt-1"> <!-- kasih margin top -->
      <div class="card-header d-flex justify-content-between align-items-center py-5">
        <h5 class="mb-0 fs-4">Menu Terlaris</h5>

        <!-- Kanan: export dan tambah -->
        <div class="d-flex align-items-center gap-2">
          <!-- Tempat tombol export DataTables -->
          <div id="exportButtons"></div>
        </div>
      </div>
      <div class="table-responsive text-nowrap">
        <table class="table" id="menuTerlaris">
          <thead>
            <tr>
              <th>No</th>
              <th>Nama Menu</th>
              <th>Kategori</th>
              <th>Jumlah Terjual</th>
              <th>Harga Satuan</th>
            </tr>
          </thead>
          <tbody class="table-border-bottom-0">
            @foreach($topMenus as $index => $menu)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $menu->nama_barang }}</td>
                <td>{{ $menu->kategori }}</td>
                <td>{{ $menu->total_terjual }}</td>
                <td>Rp {{ number_format($menu->harga_jual, 0, ',', '.') }}</td>
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
        let table = $('#menuTerlaris').DataTable({
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

        // Pindahkan tombol ke dalam div di card-header
        table.buttons().container().appendTo('#exportButtons');
      });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      const ctx = document.getElementById('tiketChart').getContext('2d');

      // Warna-warni transparan untuk setiap hari
      const warnaBackground = [
        'rgba(255, 99, 132, 0.3)',   // merah muda
        'rgba(54, 162, 235, 0.3)',   // biru
        'rgba(255, 206, 86, 0.3)',   // kuning
        'rgba(75, 192, 192, 0.3)',   // hijau toska
        'rgba(153, 102, 255, 0.3)',  // ungu
        'rgba(255, 159, 64, 0.3)',   // oranye
        'rgba(100, 181, 246, 0.3)',  // biru muda
      ];

      const warnaBorder = [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        'rgba(100, 181, 246, 1)',
      ];

      const data = {
        labels: @json($tiketMingguanHari),
        datasets: [{
          label: 'Jumlah Tiket Terjual',
          data: @json($tiketMingguanTotal),
          backgroundColor: warnaBackground,
          borderColor: warnaBorder,
          borderWidth: 2,
          borderRadius: 8,
          hoverBackgroundColor: warnaBorder.map(c => c.replace('1)', '0.6)')),
        }]
      };

      const options = {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: 'rgba(0,0,0,0.7)',
            titleColor: '#fff',
            bodyColor: '#fff',
            cornerRadius: 10,
            padding: 10
          }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { color: '#333', font: { weight: '500' } }
          },
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.05)' },
            ticks: { color: '#333' }
          }
        },
        animation: {
          duration: 1200,
          easing: 'easeOutBounce'
        }
      };

      new Chart(ctx, { type: 'bar', data, options });
    </script>

    <script>
      const ctx2 = document.getElementById('pendapatanChart').getContext('2d');

      // Gradasi lembut biru ke ungu
      const gradient = ctx2.createLinearGradient(0, 0, 0, 300);
      gradient.addColorStop(0, 'rgba(54, 162, 235, 0.5)');
      gradient.addColorStop(1, 'rgba(153, 102, 255, 0.2)');

      const data2 = {
        labels: @json($tanggalPendapatan),
        datasets: [{
          label: 'Total Penghasilan (Rp)',
          data: @json($totalPendapatanMingguan),
          fill: true,
          backgroundColor: gradient,
          borderColor: 'rgba(54, 162, 235, 1)',
          borderWidth: 2,
          tension: 0.4, // garis melengkung lembut
          pointBackgroundColor: '#fff',
          pointBorderColor: 'rgba(54, 162, 235, 1)',
          pointHoverBackgroundColor: 'rgba(54, 162, 235, 1)',
          pointRadius: 5,
        }]
      };

      const options2 = {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            backgroundColor: 'rgba(0,0,0,0.8)',
            titleColor: '#fff',
            bodyColor: '#fff',
            cornerRadius: 10,
            padding: 10,
            callbacks: {
              label: function (ctx) {
                return ' Rp ' + ctx.formattedValue.replace(/\B(?=(\d{3})+(?!\d))/g, ".");
              }
            }
          }
        },
        scales: {
          x: {
            grid: { display: false },
            ticks: { color: '#333', font: { weight: '500' } }
          },
          y: {
            beginAtZero: true,
            grid: { color: 'rgba(0,0,0,0.05)' },
            ticks: {
              color: '#333',
              callback: function (value) {
                return 'Rp ' + value.toLocaleString();
              }
            }
          }
        },
        animation: {
          duration: 1200,
          easing: 'easeOutQuart'
        }
      };

      if (window.pendapatanChartInstance) {
        window.pendapatanChartInstance.destroy();
      }
      window.pendapatanChartInstance = new Chart(ctx2, { type: 'line', data: data2, options: options2 });


      new Chart(ctx2, { type: 'line', data: data2, options: options2 });
    </script>

  @endpush
@endsection