<!-- <!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>

    @if ($errors->any())
        <div style="color:red;">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ url('/login') }}">
        @csrf
        <label>Email</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password</label><br>
        <input type="password" name="password" required><br><br>

        <button type="submit">Login</button>
    </form>
</body>
</html> -->


<!doctype html>

<html lang="en" class="layout-wide customizer-hide" data-assets-path="template/sneat/assets/"
  data-template="vertical-menu-template-free">

<head>
  <meta charset="utf-8" />
  <meta name="viewport"
    content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

  <title>Login</title>

  <meta name="description" content="" />

  <!-- Favicon -->
  <link rel="icon" type="image/x-icon" href="template/sneat/assets/img/favicon/favicon.ico" />

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link
    href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
    rel="stylesheet" />

  <link rel="stylesheet" href="template/sneat/assets/vendor/fonts/iconify-icons.css" />

  <!-- Core CSS -->
  <!-- build:css assets/vendor/css/theme.css  -->

  <link rel="stylesheet" href="template/sneat/assets/vendor/css/core.css" />
  <link rel="stylesheet" href="template/sneat/assets/css/demo.css" />

  <!-- Vendors CSS -->

  <link rel="stylesheet" href="template/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />

  <!-- endbuild -->

  <!-- Page CSS -->
  <!-- Page -->
  <link rel="stylesheet" href="template/sneat/assets/vendor/css/pages/page-auth.css" />

  <!-- Helpers -->
  <script src="template/sneat/assets/vendor/js/helpers.js"></script>
  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->

  <script src="template/sneat/assets/js/config.js"></script>
</head>

<body>
  <!-- Content -->

  @if ($errors->any())
    <div style="color:red;">
      {{ $errors->first() }}
    </div>
  @endif

  <div class="container-xxl">
    <div class="authentication-wrapper authentication-basic container-p-y">
      <div class="authentication-inner">
        <!-- Register -->
        <div class="card px-sm-6 px-0">
          <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center">
              <a href="index.html" class="app-brand-link gap-2">
                <span class="app-brand-logo demo">
                  <img style="width: 40px;" src="{{ asset('images/logo.png') }}" alt="Logo" class="logo">
                </span>
                <span class="app-brand-text demo text-heading fw-bold">Wieska</span>
              </a>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1">Selamat Datang! 👋</h4>
            <p class="mb-6">Silahkan masuk ke akun anda dan mulai pengalaman</p>

            <form method="POST" action="{{ url('/login') }}" id="formAuthentication" class="mb-6" action="index.html">
              @csrf
              <div class="mb-6">
                <label for="email" class="form-label">Email</label>
                <input type="text" class="form-control" id="email" name="email" placeholder="Masukkan Email Anda" required
                  autofocus />
              </div>
              <div class="mb-6 form-password-toggle">
                <label class="form-label" for="password">Kata Sandi</label>
                <div class="input-group input-group-merge">
                  <input type="password" id="password" class="form-control" name="password" required
                    placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    aria-describedby="password" />
                  <span class="input-group-text cursor-pointer"><i class="icon-base bx bx-hide"></i></span>
                </div>
              </div>
              <div class="mb-8">
                <div class="d-flex justify-content-between">
                  <!-- <div class="form-check mb-0">
                      <input class="form-check-input" type="checkbox" id="remember-me" />
                      <label class="form-check-label" for="remember-me"> Remember Me </label>
                    </div>
                    <a href="auth-forgot-password-basic.html">
                      <span>Forgot Password?</span>
                    </a> -->
                </div>
              </div>
              <div class="mb-6">
                <button class="btn btn-primary d-grid w-100" type="submit">Login</button>
              </div>
            </form>

            <!-- <p class="text-center">
                <span>New on our platform?</span>
                <a href="auth-register-basic.html">
                  <span>Create an account</span>
                </a>
              </p> -->
          </div>
        </div>
        <!-- /Register -->
      </div>
    </div>
  </div>

  <!-- / Content -->

  <!-- <div class="buy-now">
      <a
        href="https://themeselection.com/item/sneat-dashboard-pro-bootstrap/"
        target="_blank"
        class="btn btn-danger btn-buy-now"
        >Upgrade to Pro</a
      >
    </div> -->

  <!-- Core JS -->

  <script src="template/sneat/assets/vendor/libs/jquery/jquery.js"></script>

  <script src="template/sneat/assets/vendor/libs/popper/popper.js"></script>
  <script src="template/sneat/assets/vendor/js/bootstrap.js"></script>

  <script src="template/sneat/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>

  <script src="template/sneat/assets/vendor/js/menu.js"></script>

  <!-- endbuild -->

  <!-- Vendors JS -->

  <!-- Main JS -->

  <script src="template/sneat/assets/js/main.js"></script>

  <!-- Page JS -->

  <!-- Place this tag before closing body tag for github widget button. -->
  <script async defer src="https://buttons.github.io/buttons.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  @if (session('loginSuccess'))
    <script>
      const jabatan = "{{ session('jabatan') }}";
      const redirectUrl = jabatan === 'admin'
        ? "{{ route('dashboard.index') }}"
        : "{{ route('kasir_cafe') }}";

      Swal.fire({
        icon: 'success',
        title: 'Login Berhasil',
        text: 'Selamat datang ' + jabatan.charAt(0).toUpperCase() + jabatan.slice(1) + '!',
        timer: 1500,
        showConfirmButton: false,
        willClose: () => {
          window.location.href = redirectUrl;
        }
      });
    </script>
  @endif

  @if (session('loginError'))
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Login Gagal',
        text: '{{ session('loginError') }}'
      });
    </script>
  @endif


</body>

</html>