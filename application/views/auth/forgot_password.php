<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Forgot Password</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=base_url('assets')?>/login-page/css/login.css">
  <style type="text/css">
    .error_message { color: #dc3545; }
    .otp-section, .new-password-section { display: none; }
    .spinner-container { display: inline-block; width: 20px; height: 20px; margin-right: 5px; }
  </style>
  <!-- Add CSRF meta tag -->
  <meta name="csrf_token_name" content="<?=$this->security->get_csrf_token_name()?>">
</head>
<body>
  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 login-section-wrapper">
          <div class="brand-wrapper"></div>
          <div class="login-wrapper my-auto">
            <h1 class="login-title">Recover Password</h1>
            
            <!-- Flash Messages -->
            <?php if($this->session->flashdata('message')): ?>
              <div class="alert alert-success alert-dismissible fade show">
                <?=$this->session->flashdata('message')?>
                <button type="button" class="close" data-dismiss="alert">
                  <span>&times;</span>
                </button>
              </div>
            <?php endif; ?>
            
            <?php if($this->session->flashdata('error')): ?>
              <div class="alert alert-danger alert-dismissible fade show">
                <?=$this->session->flashdata('error')?>
                <button type="button" class="close" data-dismiss="alert">
                  <span>&times;</span>
                </button>
              </div>
            <?php endif; ?>
            
            <form id="forgotPasswordForm">
              <!-- Step 1: Email Input -->
              <div class="email-section">
                <div class="form-group">
                  <label for="email">Email <span class="text-danger">*</span></label>
                  <input type="email" maxlength="100" class="form-control" name="email" id="email" 
                         value="<?=set_value('email')?>" placeholder="Enter your email">
                  <span id="err_email" class="error text-danger"><?=form_error('email')?></span>
                </div>
                <button type="button" id="sendOtpBtn" class="btn btn-block login-btn">
                  <span class="spinner-container d-none"><span class="spinner-border spinner-border-sm"></span></span>
                  Send OTP
                </button>
              </div>
              
              <!-- Step 2: OTP Verification -->
              <div class="otp-section">
                <div class="form-group">
                  <label for="otp">OTP <span class="text-danger">*</span></label>
                  <input type="text" maxlength="6" class="form-control" name="otp" id="otp" 
                         placeholder="Enter OTP" autocomplete="off">
                  <span id="err_otp" class="error text-danger"><?=form_error('otp')?></span>
                  <small class="text-muted">Check your email for the OTP</small>
                </div>
                <button type="button" id="verifyOtpBtn" class="btn btn-block login-btn">
                  <span class="spinner-container d-none"><span class="spinner-border spinner-border-sm"></span></span>
                  Verify OTP
                </button>
                <button type="button" id="resendOtpBtn" class="btn btn-block btn-outline-secondary mt-2">
                  <span class="spinner-container d-none"><span class="spinner-border spinner-border-sm"></span></span>
                  Resend OTP
                </button>
              </div>
              
              <!-- Step 3: New Password -->
              <div class="new-password-section">
                <div class="form-group">
                  <label for="new_password">New Password <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" name="new_password" id="new_password" 
                         placeholder="Enter new password">
                  <span id="err_new_password" class="error text-danger"><?=form_error('new_password')?></span>
                </div>
                <div class="form-group">
                  <label for="confirm_password">Confirm Password <span class="text-danger">*</span></label>
                  <input type="password" class="form-control" name="confirm_password" id="confirm_password" 
                         placeholder="Confirm new password">
                  <span id="err_confirm_password" class="error text-danger"><?=form_error('confirm_password')?></span>
                </div>
                <button type="submit" id="resetPasswordSubmit" class="btn btn-block login-btn">
                  <span class="spinner-container d-none"><span class="spinner-border spinner-border-sm"></span></span>
                  Reset Password
                </button>
              </div>
              
              <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" 
                     value="<?=$this->security->get_csrf_hash()?>" id="csrf_token">
            </form>
            
            <p class="login-wrapper-footer-text mt-3">
              Remember your password? <a href="<?=base_url('auth/login')?>" class="text-reset">Login here</a>
            </p>
          </div>
        </div>
        <div class="col-sm-6 px-0 d-none d-sm-block">
          <img src="<?=base_url('')?>/uploads/login.jpg" alt="login image" class="login-img">
        </div>
        https://erp.apluscrm.in/
 
      </div>
    </div>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>

  <script>
  $(document).ready(function(){
    // Initialize sections
    $('.email-section').show();
    $('.otp-section, .new-password-section').hide();
    
    // Helper functions
    function showLoading(btn) {
      btn.find('.spinner-container').removeClass('d-none');
      btn.prop('disabled', true);
    }
    
    function hideLoading(btn) {
      btn.find('.spinner-container').addClass('d-none');
      btn.prop('disabled', false);
    }
    
    function updateCsrfToken(token) {
      $('#csrf_token').val(token);
    }
    
    function isValidEmail(email) {
      return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }
    
    function clearErrors() {
      $('.error').text('');
      $('.is-invalid').removeClass('is-invalid');
    }
    
    // Handle Send OTP
    $('#sendOtpBtn').click(function(){
      clearErrors();
      const email = $('#email').val().trim();
      let isValid = true;
      
      if(!email) {
        $('#err_email').text('Email is required');
        $('#email').addClass('is-invalid');
        isValid = false;
      } else if(!isValidEmail(email)) {
        $('#err_email').text('Please enter a valid email');
        $('#email').addClass('is-invalid');
        isValid = false;
      }
      
      if(isValid) {
        const btn = $(this);
        showLoading(btn);
        
        $.ajax({
          url: '<?=base_url("auth/send_otp")?>',
          type: 'POST',
          data: {
            email: email,
            [getCsrfName()]: getCsrfToken()
          },
          dataType: 'json',
          success: function(res) {
            if(res.success) {
              $('.email-section').hide();
              $('.otp-section').show();
              if(res.csrf_token) updateCsrfToken(res.csrf_token);
            } else {
              $('#err_email').text(res.message || 'Error sending OTP');
              if(res.csrf_token) updateCsrfToken(res.csrf_token);
            }
          },
          error: function(xhr) {
            alert('Server error occurred. Please try again.');
            console.error(xhr.responseText);
          },
          complete: function() {
            hideLoading(btn);
          }
        });
      }
    });
    
    // Handle Verify OTP
    $('#verifyOtpBtn').click(function(){
      clearErrors();
      const otp = $('#otp').val().trim();
      let isValid = true;
      
      if(!otp) {
        $('#err_otp').text('OTP is required');
        $('#otp').addClass('is-invalid');
        isValid = false;
      } else if(otp.length !== 6) {
        $('#err_otp').text('OTP must be 6 digits');
        $('#otp').addClass('is-invalid');
        isValid = false;
      }
      
      if(isValid) {
        const btn = $(this);
        showLoading(btn);
        
        $.ajax({
          url: '<?=base_url("auth/verify_otp")?>',
          type: 'POST',
          data: {
            email: $('#email').val(),
            otp: otp,
            [getCsrfName()]: getCsrfToken()
          },
          dataType: 'json',
          success: function(res) {
            if(res.success) {
              $('.otp-section').hide();
              $('.new-password-section').show();
              if(res.csrf_token) updateCsrfToken(res.csrf_token);
            } else {
              $('#err_otp').text(res.message || 'Invalid OTP');
              if(res.csrf_token) updateCsrfToken(res.csrf_token);
            }
          },
          error: function(xhr) {
            alert('Server error occurred. Please try again.');
            console.error(xhr.responseText);
          },
          complete: function() {
            hideLoading(btn);
          }
        });
      }
    });
    
    // Handle Resend OTP
    $('#resendOtpBtn').click(function(){
      clearErrors();
      const btn = $(this);
      showLoading(btn);
      
      $.ajax({
        url: '<?=base_url("auth/resend_otp")?>',
        type: 'POST',
        data: {
          email: $('#email').val(),
          [getCsrfName()]: getCsrfToken()
        },
        dataType: 'json',
        success: function(res) {
          if(res.success) {
            alert('New OTP has been sent to your email');
            if(res.csrf_token) updateCsrfToken(res.csrf_token);
          } else {
            alert(res.message || 'Error resending OTP');
            if(res.csrf_token) updateCsrfToken(res.csrf_token);
          }
        },
        error: function(xhr) {
          alert('Server error occurred. Please try again.');
          console.error(xhr.responseText);
        },
        complete: function() {
          hideLoading(btn);
        }
      });
    });
    
    // Handle Password Reset
    $('#forgotPasswordForm').submit(function(e){
      e.preventDefault();
      clearErrors();
      
      const newPass = $('#new_password').val();
      const confirmPass = $('#confirm_password').val();
      let isValid = true;
      
      if(!newPass) {
        $('#err_new_password').text('New password is required');
        $('#new_password').addClass('is-invalid');
        isValid = false;
      } else if(newPass.length < 8) {
        $('#err_new_password').text('Password must be at least 8 characters');
        $('#new_password').addClass('is-invalid');
        isValid = false;
      }
      
      if(!confirmPass) {
        $('#err_confirm_password').text('Please confirm your password');
        $('#confirm_password').addClass('is-invalid');
        isValid = false;
      } else if(confirmPass !== newPass) {
        $('#err_confirm_password').text('Passwords do not match');
        $('#confirm_password').addClass('is-invalid');
        isValid = false;
      }
      
      if(isValid) {
        const btn = $('#resetPasswordSubmit');
        showLoading(btn);
        
        $.ajax({
          url: '<?=base_url("auth/reset_password")?>',
          type: 'POST',
          data: {
            email: $('#email').val(),
            new_password: newPass,
            confirm_password: confirmPass,
            [getCsrfName()]: getCsrfToken()
          },
          dataType: 'json',
          success: function(res) {
            if(res.success) {
              window.location.href = '<?=base_url("auth/login")?>?message=' + 
                encodeURIComponent(res.message || 'Password reset successfully');
            } else {
              alert(res.message || 'Password reset failed');
              if(res.csrf_token) updateCsrfToken(res.csrf_token);
            }
          },
          error: function(xhr) {
            alert('Server error occurred. Please try again.');
            console.error(xhr.responseText);
          },
          complete: function() {
            hideLoading(btn);
          }
        });
      }
    });
    
    // CSRF helper functions
    function getCsrfName() {
      return $('meta[name="csrf_token_name"]').attr('content');
    }
    
    function getCsrfToken() {
      return $('#csrf_token').val();
    }
  });
  </script>
</body>
</html>