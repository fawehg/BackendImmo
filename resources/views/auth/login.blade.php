<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Secure login portal">
  <meta name="author" content="">
  <title>Secure Login Portal</title>
  
  <!-- Favicon -->
  <link rel="icon" href="{{ asset('admin_assets/img/favicon.ico') }}" type="image/x-icon">
  
  <!-- Font Awesome with fallback -->
  <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <noscript><link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet"></noscript>
  
  <!-- Google Fonts with fallback -->
  <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500&display=swap');
    body {
      font-family: 'Nunito', 'Roboto', sans-serif;
    }
  </style>
  
  <!-- Main CSS with fallback -->
  <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
  <noscript>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  </noscript>
  
  <style>
    :root {
      --primary-color: #4e73df;
      --secondary-color: #f8f9fc;
      --accent-color: #2e59d9;
      --text-color: #5a5c69;
      --error-color: #e74a3b;
      --success-color: #1cc88a;
    }
    
    body {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh;
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      margin: 0;
      padding: 20px;
      box-sizing: border-box;
    }
    
    .bg-custom-image {
      background-image: url('{{ asset("admin_assets/img/maisona.jpg") }}');
      background-size: cover;
      background-position: center;
      position: relative;
    }
    
    .bg-custom-image::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      background: rgba(78, 115, 223, 0.3);
    }
    
    .card {
      border-radius: 15px;
      overflow: hidden;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
    }
    
    .form-control-user {
      border-radius: 50px;
      padding: 15px 20px;
      margin-bottom: 15px;
      border: 1px solid #ddd;
      transition: all 0.3s;
    }
    
    .form-control-user:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
    }
    
    .btn-user {
      border-radius: 50px;
      padding: 10px;
      font-weight: 600;
      letter-spacing: 0.5px;
      text-transform: uppercase;
      transition: all 0.3s;
      background-color: var(--primary-color);
      border: none;
    }
    
    .btn-user:hover {
      background-color: var(--accent-color);
      transform: translateY(-2px);
    }
    
    .login-header {
      margin-bottom: 2rem;
    }
    
    .login-header h1 {
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 0.5rem;
    }
    
    .login-header p {
      color: var(--text-color);
      font-size: 0.9rem;
    }
    
    .custom-checkbox .custom-control-label::before {
      border-radius: 3px;
    }
    
    .custom-checkbox .custom-control-input:checked~.custom-control-label::before {
      background-color: var(--primary-color);
    }
    
    .alert {
      border-radius: 50px;
      padding: 10px 20px;
    }
    
    .divider {
      position: relative;
      text-align: center;
      margin: 20px 0;
    }
    
    .divider::before {
      content: "";
      position: absolute;
      top: 50%;
      left: 0;
      right: 0;
      height: 1px;
      background-color: #ddd;
      z-index: 1;
    }
    
    .divider-text {
      position: relative;
      display: inline-block;
      padding: 0 10px;
      background-color: white;
      z-index: 2;
      color: var(--text-color);
      font-size: 0.8rem;
    }
    
    @media (max-width: 992px) {
      .bg-custom-image {
        min-height: 200px;
      }
    }
    
    /* Loading animation */
    .btn-loading {
      position: relative;
      pointer-events: none;
    }
    
    .btn-loading::after {
      content: "";
      position: absolute;
      top: 50%;
      left: 50%;
      width: 20px;
      height: 20px;
      margin: -10px 0 0 -10px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-top-color: white;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
    }
    
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    
    /* Accessibility improvements */
    a:focus, button:focus, input:focus {
      outline: 2px solid var(--accent-color);
      outline-offset: 2px;
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-custom-image p-5 d-flex align-items-center justify-content-center">
                <div class="text-white text-center position-relative" style="z-index: 2;">
                  <h2 class="mb-3 font-weight-bold">Welcome Back</h2>
                  <p class="mb-0">Secure access to your account</p>
                </div>
              </div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="login-header text-center">
                    <h1 class="h3 font-weight-bold">Sign In</h1>
                    <p>Enter your credentials to access your account</p>
                  </div>
                  
                  <form action="{{ route('login.action') }}" method="POST" class="user" id="loginForm">
                    @csrf
                    
                    @if (isset($errors) && $errors->any() && request()->routeIs('login'))
                      <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> 
                        @foreach ($errors->all() as $error)
                          {{ $error }}
                        @endforeach
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                    @endif
                    
                    @if(session('success'))
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                    @endif
                    
                    <div class="form-group">
                      <label for="exampleInputEmail" class="sr-only">Email Address</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input name="email" type="email" class="form-control form-control-user" 
                               id="exampleInputEmail" aria-describedby="emailHelp" 
                               placeholder="Enter Email Address..." autocomplete="username"
                               required>
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleInputPassword" class="sr-only">Password</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input name="password" type="password" class="form-control form-control-user" 
                               id="exampleInputPassword" placeholder="Password" 
                               autocomplete="current-password" required>
                        <div class="input-group-append">
                          <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                          </button>
                        </div>
                      </div>
                    </div>
                    
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input name="remember" type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-user btn-block" id="loginButton">
                      <span id="buttonText">Login</span>
                    </button>
                    
                    <div class="divider">
                      <span class="divider-text">OR</span>
                    </div>
                    
               
                    <div class="text-center">
                      <a class="small" href="{{ route('register') }}">Create an Account!</a>
                    </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- JavaScript Libraries with Fallbacks -->
  <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
  <script>
    window.jQuery || document.write('<script src="https://code.jquery.com/jquery-3.6.0.min.js"><\/script>');
  </script>
  
  <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script>
    if(typeof $().modal !== 'function') {
      document.write('<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"><\/script>');
    }
  </script>
  
  <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
  <script>
    if(typeof jQuery.easing !== 'object') {
      document.write('<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js"><\/script>');
    }
  </script>
  
  <!-- Custom Scripts -->
  <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>
  
  <script>
    // Password visibility toggle
    document.getElementById('togglePassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('exampleInputPassword');
      const icon = this.querySelector('i');
      
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.classList.remove('fa-eye');
        icon.classList.add('fa-eye-slash');
      } else {
        passwordInput.type = 'password';
        icon.classList.remove('fa-eye-slash');
        icon.classList.add('fa-eye');
      }
    });
    
    // Form submission loading state
    document.getElementById('loginForm').addEventListener('submit', function() {
      const button = document.getElementById('loginButton');
      const buttonText = document.getElementById('buttonText');
      
      button.classList.add('btn-loading');
      button.disabled = true;
      buttonText.textContent = 'Authenticating...';
    });
    
    // Accessibility improvements
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A') {
        e.preventDefault();
        document.getElementById('loginButton').click();
      }
    });
    
    // Input validation
    document.getElementById('loginForm').addEventListener('input', function(e) {
      if (e.target.tagName === 'INPUT') {
        if (e.target.checkValidity()) {
          e.target.classList.remove('is-invalid');
        } else {
          e.target.classList.add('is-invalid');
        }
      }
    });
  </script>
</body>
</html>