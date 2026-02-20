<!-- <h1>Halo Admin, {{ Auth::user()->nama }}</h1>
<p>Ini adalah halaman dashboard admin.</p>

<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Logout</button>
</form> -->

<!doctype html>

<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="template/sneat/assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    <title>@yield('title')</title>

    <meta name="description" content="" />
    <style>
        .text-blue {
            color: #1E56A0;
        }
    </style>

    <link href='https://cdn.boxicons.com/fonts/basic/boxicons.min.css' rel='stylesheet'>
    <!-- Favicon -->
    <!-- <link rel="icon" type="image/x-icon" href="template/sneat/assets/img/favicon/favicon.ico" /> -->

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('template/sneat/assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css  -->
    <link rel="stylesheet" href="{{ asset('template/sneat/assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/sneat/assets/css/demo.css') }}" />
    <link rel="stylesheet"
        href="{{ asset('template/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('template/sneat/assets/vendor/libs/apex-charts/apex-charts.css') }}" />

    <!-- Page CSS -->

    <!-- CSS DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <!-- Helpers -->
    <script src="{{ asset('template/sneat/assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="{{ asset('template/sneat/assets/js/config.js') }}"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->

            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="index.html" class="app-brand-link align-items-center">
                        <span class="app-brand-logo demo">
                            
                            <img style="width: 40px;" src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">

                        </span>
                        <span class="app-brand-text demo menu-text fw-bold ms-2">Wieska</span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto">
                        <i class="bx bx-chevron-left d-block d-xl-none align-middle"></i>
                    </a>
                </div>

                <div class="menu-divider mt-0"></div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboards -->
                    <li class="menu-item @yield('dashboardActive')">
                        <a href="/admin/dashboard" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-collection"></i>
                            <div class="text-truncate" data-i18n="Basic">Dashboard</div>
                        </a>
                    </li>
                    <li class="menu-item @yield('masterDataActive')">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class='bxr  bx-database-alt menu-icon'  ></i> 
                            <div class="text-truncate" data-i18n="Dashboards">Master Data</div>
                            <!-- <span class="badge rounded-pill bg-danger ms-auto">5</span> -->
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item @yield('karyawanActive')">
                                <a href="/admin/karyawan" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Karyawan</div>
                                </a>
                                <!-- </li>
                            <li class="menu-item ">
                                <a href="barang" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Barang</div>
                                </a>
                            </li> -->
                            <li class="menu-item @yield('menuActive')">
                                <a href="/admin/menu" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Menu</div>
                                </a>
                            </li>
                            <li class="menu-item @yield('satuanActive')">
                                <a href="/admin/satuan" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Satuan</div>
                                </a>
                            </li>
                            <li class="menu-item @yield('kategoriActive')">
                                <a href="/admin/kategori" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Kategori</div>
                                </a>
                            </li>
                            <li class="menu-item @yield('jenisTiketActive')">
                                <a href="/admin/jenis_tiket" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Jenis Tiket</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item @yield('transaksiActive')">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class='bxr  bx-credit-card-front menu-icon'  ></i> 
                            <div class="text-truncate" data-i18n="Dashboards">Transaksi</div>
                            <!-- <span class="badge rounded-pill bg-danger ms-auto">5</span> -->
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item @yield('barangMasukActive')">
                                <a href="/admin/barang_masuk" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Barang Masuk</div>
                                </a>
                            </li>
                            <li class="menu-item @yield('kasirCafeActive')">
                                <a href="/kasir_cafe" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Kasir Cafe</div>
                                </a>
                            </li>
                            <li class="menu-item @yield('kasirTiketActive')">
                                <a href="/kasir_tiket" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Kasir Tiket</div>
                                </a>
                            </li>
                            <li class="menu-item @yield('bookingActive')">
                                <a href="/admin/booking" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Booking</div>
                                </a>
                            </li>
                            <li class="menu-item @yield('hutangActive')">
                                <a href="/admin/hutang" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Hutang</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="menu-item @yield('laporanActive')">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class='bxr  bx-file-report menu-icon'  ></i> 
                            <div class="text-truncate" data-i18n="Dashboards">Laporan</div>
                            <!-- <span class="badge rounded-pill bg-danger ms-auto">5</span> -->
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item @yield('riwayatCafeActive')">
                                <a href="/admin/riwayat_cafe" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Riwayat Cafe</div>
                                </a>
                            </li>
                            <li class="menu-item @yield('riwayatTiketActive')">
                                <a href="/admin/riwayat_tiket" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Riwayat Tiket</div>
                                </a>
                            </li>
                            <li class="menu-item @yield('stokBarangActive')">
                                <a href="/admin/stok_barang" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Stok Barang</div>
                                </a>
                            </li>
                            <li class="menu-item @yield('labaActive')">
                                <a href="/admin/laba" class="menu-link">
                                    <div class="text-truncate" data-i18n="Analytics">Laba</div>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="icon-base bx bx-menu icon-md"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
                        <!-- Breadcrumbs -->
                        <div class="navbar-nav align-items-center me-auto">
                            @php
                                $breadcrumbs = [
                                    'dashboard' => ['Dashboard'],
                                    'karyawan' => ['Master Data', 'Karyawan'],
                                    'karyawan_detail' => ['Master Data', 'Karyawan', 'Detail Karyawan'],
                                    'menu' => ['Master Data', 'Menu'],
                                    'satuan' => ['Master Data', 'Satuan'],
                                    'kategori' => ['Master Data', 'Kategori Barang'],
                                    'jenis_tiket' => ['Master Data', 'Jenis Tiket'],

                                    'barang_masuk' => ['Transaksi', 'Barang Masuk'],
                                    'kasir_cafe' => ['Transaksi', 'Kasir Cafe'],
                                    'kasir_tiket' => ['Transaksi', 'Kasir Tiket'],

                                    'riwayat_cafe' => ['Laporan', 'Pesanan Cafe'],
                                    'riwayat_tiket' => ['Laporan', 'Pesanan Tiket'],
                                    'stok_barang' => ['Laporan', 'Stok Menu'],
                                ];

                                $current = Request::segment(1); // ambil segment pertama dari URL
                            @endphp

                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb mb-0">
                                    @if(isset($breadcrumbs[$current]))
                                        @foreach($breadcrumbs[$current] as $i => $crumb)
                                            @if($i + 1 < count($breadcrumbs[$current]))
                                                <li class="breadcrumb-item">{{ $crumb }}</li>
                                            @else
                                                <li class="breadcrumb-item active" aria-current="page">{{ $crumb }}</li>
                                            @endif
                                        @endforeach
                                    @else
                                        <li class="breadcrumb-item">Dashboard</li>
                                    @endif
                                </ol>
                            </nav>

                        </div>

                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-md-auto">


                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        @if(auth()->user()->foto)
                                            <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                                                alt="Foto {{ auth()->user()->nama }}"
                                                class="w-px-40 h-auto rounded-circle" />
                                        @else
                                            <img src="https://img.freepik.com/premium-vector/person-with-blue-shirt-that-says-name-person_1029948-7040.jpg"
                                                alt="Default Foto" class="w-px-40 h-auto rounded-circle" />
                                        @endif
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        @if(auth()->user()->foto)
                                                            <img src="{{ asset('storage/' . auth()->user()->foto) }}"
                                                                alt="Foto {{ auth()->user()->nama }}"
                                                                class="w-px-40 h-auto rounded-circle" />
                                                        @else
                                                            <img src="https://img.freepik.com/premium-vector/person-with-blue-shirt-that-says-name-person_1029948-7040.jpg"
                                                                alt="Default Foto" class="w-px-40 h-auto rounded-circle" />
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ auth()->user()->nama }}</h6>
                                                    <small
                                                        class="text-body-secondary">{{ auth()->user()->jabatan }}</small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>
                                        <a class="dropdown-item"
                                            href="{{ url('admin/karyawan_detail/' . Auth::user()->id . '/edit') }}">
                                            <i class="icon-base bx bx-user icon-md me-3"></i><span>Profil Saya</span>
                                        </a>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider my-1"></div>
                                    </li>
                                    <li>

                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item" href="javascript:void(0);">
                                                <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Log
                                                    Out</span>
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    @yield('isi')

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl">
                            <div
                                class="footer-container d-flex align-items-center justify-content-between py-4 flex-md-row flex-column">
                                <div class="mb-2 mb-md-0">
                                    &#169;
                                    <script>
                                        document.write(new Date().getFullYear());
                                    </script>
                                    , made with ❤️ by AlphaLogic
                                </div>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>

    <!-- JS DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

    <!-- Tempat untuk script tambahan dari child -->
    @stack('scripts')
    <!-- / Layout wrapper -->

    <!-- <div class="buy-now">
      <a
        href="https://themeselection.com/item/sneat-dashboard-pro-bootstrap/"
        target="_blank"
        class="btn btn-danger btn-buy-now"
        >Upgrade to Pro</a
      >
    </div> -->

    <!-- Core JS -->


    <script src="{{ asset('template/sneat/assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('template/sneat/assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('template/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
    <script src="{{ asset('template/sneat/assets/vendor/js/menu.js') }}"></script>
    <script src="{{ asset('template/sneat/assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    {{-- Main JS --}}
    <script src="{{ asset('template/sneat/assets/js/main.js') }}"></script>

    {{-- Page JS --}}
    <script src="{{ asset('template/sneat/assets/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async defer src="https://buttons.github.io/buttons.js"></script>


</body>

</html>