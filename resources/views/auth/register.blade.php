<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Create a new account">
  <meta name="author" content="">
  <title>Create Your Account</title>
  
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
      padding: 12px;
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
    
    .register-header {
      margin-bottom: 2rem;
    }
    
    .register-header h1 {
      font-weight: 700;
      color: var(--primary-color);
      margin-bottom: 0.5rem;
    }
    
    .register-header p {
      color: var(--text-color);
      font-size: 0.9rem;
    }
    
    .password-strength {
      height: 5px;
      background-color: #eee;
      border-radius: 5px;
      margin-top: 5px;
      overflow: hidden;
    }
    
    .password-strength-bar {
      height: 100%;
      width: 0;
      transition: width 0.3s ease, background-color 0.3s ease;
    }
    
    .password-requirements {
      font-size: 0.8rem;
      color: var(--text-color);
      margin-top: 5px;
    }
    
    .requirement {
      display: flex;
      align-items: center;
      margin-bottom: 3px;
    }
    
    .requirement i {
      margin-right: 5px;
      font-size: 0.7rem;
    }
    
    .requirement.valid {
      color: var(--success-color);
    }
    
    .requirement.invalid {
      color: var(--error-color);
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
    
    /* Error message styling */
    .invalid-feedback {
      color: var(--error-color);
      font-size: 0.8rem;
      margin-top: -10px;
      margin-bottom: 10px;
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
              <div class="col-lg-5 d-none d-lg-block bg-custom-image p-5 d-flex align-items-center justify-content-center">
                <div class="text-white text-center position-relative" style="z-index: 2;">
                  <h2 class="mb-3 font-weight-bold">Join Us</h2>
                  <p class="mb-0">Create your account to get started</p>
                </div>
              </div>
              <div class="col-lg-7">
                <div class="p-5">
                  <div class="register-header text-center">
                    <h1 class="h3 font-weight-bold">Create Account</h1>
                    <p>Fill in your details to register</p>
                  </div>
                  
                  <form action="{{ route('register.save') }}" method="POST" class="user" id="registerForm">
                    @csrf
                    
                    @if(session('success'))
                      <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                    @endif
                    
                    <div class="form-group">
                      <label for="exampleInputName" class="sr-only">Full Name</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input name="name" type="text" class="form-control form-control-user @error('name')is-invalid @enderror" 
                               id="exampleInputName" placeholder="Full Name" 
                               value="{{ old('name') }}" required autocomplete="name" autofocus>
                      </div>
                      @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleInputEmail" class="sr-only">Email Address</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input name="email" type="email" class="form-control form-control-user @error('email')is-invalid @enderror" 
                               id="exampleInputEmail" placeholder="Email Address" 
                               value="{{ old('email') }}" required autocomplete="email">
                      </div>
                      @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleInputPassword" class="sr-only">Password</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input name="password" type="password" class="form-control form-control-user @error('password')is-invalid @enderror" 
                               id="exampleInputPassword" placeholder="Password" 
                               required autocomplete="new-password">
                        <div class="input-group-append">
                          <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="fas fa-eye"></i>
                          </button>
                        </div>
                      </div>
                      <div class="password-strength">
                        <div class="password-strength-bar" id="passwordStrengthBar"></div>
                      </div>
                      <div class="password-requirements" id="passwordRequirements">
                        <div class="requirement" id="lengthReq">
                          <i class="fas fa-circle"></i> At least 8 characters
                        </div>
                        <div class="requirement" id="numberReq">
                          <i class="fas fa-circle"></i> Contains a number
                        </div>
                        <div class="requirement" id="specialReq">
                          <i class="fas fa-circle"></i> Contains a special character
                        </div>
                      </div>
                      @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    
                    <div class="form-group">
                      <label for="exampleRepeatPassword" class="sr-only">Repeat Password</label>
                      <div class="input-group">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-lock"></i></span>
                        </div>
                        <input name="password_confirmation" type="password" class="form-control form-control-user @error('password_confirmation')is-invalid @enderror" 
                               id="exampleRepeatPassword" placeholder="Repeat Password" 
                               required autocomplete="new-password">
                        <div class="input-group-append">
                          <button class="btn btn-outline-secondary" type="button" id="toggleRepeatPassword">
                            <i class="fas fa-eye"></i>
                          </button>
                        </div>
                      </div>
                      @error('password_confirmation')
                        <span class="invalid-feedback">{{ $message }}</span>
                      @enderror
                    </div>
                    
                    <div class="form-group">
                      <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="termsCheck" required>
                        <label class="custom-control-label" for="termsCheck">
                          I agree to the <a href="#" data-toggle="modal" data-target="#termsModal">Terms and Conditions</a>
                        </label>
                      </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-user btn-block" id="registerButton">
                      <span id="buttonText">Register Account</span>
                    </button>
                    
                    <div class="divider">
                      <span class="divider-text">OR</span>
                    </div>
                    
                    <div class="text-center">
                      <a class="small" href="{{ route('login') }}">Already have an account? Login!</a>
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

  <!-- Terms Modal -->
  <div class="modal fade" id="termsModal" tabindex="-1" role="dialog" aria-labelledby="termsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="termsModalLabel">Terms and Conditions</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Please read these terms and conditions carefully before using our service.</p>
          <!-- Add your actual terms and conditions here -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
    
    // Repeat Password visibility toggle
    document.getElementById('toggleRepeatPassword').addEventListener('click', function() {
      const passwordInput = document.getElementById('exampleRepeatPassword');
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
    
    // Password strength checker
    document.getElementById('exampleInputPassword').addEventListener('input', function() {
      const password = this.value;
      const strengthBar = document.getElementById('passwordStrengthBar');
      let strength = 0;
      
      // Check length
      const hasLength = password.length >= 8;
      document.getElementById('lengthReq').className = hasLength ? 'requirement valid' : 'requirement invalid';
      if (hasLength) strength += 25;
      
      // Check for numbers
      const hasNumber = /\d/.test(password);
      document.getElementById('numberReq').className = hasNumber ? 'requirement valid' : 'requirement invalid';
      if (hasNumber) strength += 25;
      
      // Check for special characters
      const hasSpecial = /[!@#$%^&*(),.?":{}|<>]/.test(password);
      document.getElementById('specialReq').className = hasSpecial ? 'requirement valid' : 'requirement invalid';
      if (hasSpecial) strength += 25;
      
      // Check for uppercase and lowercase
      const hasUpperLower = /[a-z]/.test(password) && /[A-Z]/.test(password);
      if (hasUpperLower) strength += 25;
      
      // Update strength bar
      strengthBar.style.width = strength + '%';
      
      // Update color based on strength
      if (strength < 50) {
        strengthBar.style.backgroundColor = var('--error-color');
      } else if (strength < 75) {
        strengthBar.style.backgroundColor = '#ffc107';
      } else {
        strengthBar.style.backgroundColor = var('--success-color');
      }
    });
    
    // Form submission loading state
    document.getElementById('registerForm').addEventListener('submit', function() {
      const button = document.getElementById('registerButton');
      const buttonText = document.getElementById('buttonText');
      
      button.classList.add('btn-loading');
      button.disabled = true;
      buttonText.textContent = 'Creating Account...';
    });
    
    // Accessibility improvements
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Enter' && e.target.tagName !== 'BUTTON' && e.target.tagName !== 'A') {
        e.preventDefault();
        document.getElementById('registerButton').click();
      }
    });
    
    // Input validation
    document.getElementById('registerForm').addEventListener('input', function(e) {
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