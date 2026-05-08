<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Reset Password</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=base_url('assets')?>/login-page/css/login.css">
  <style type="text/css">
    .error_message{
      color: #dc3545; 
    }
  </style>
</head>
<body>
  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6 login-section-wrapper">
          <div class="brand-wrapper">
            <!-- <img src="assets/images/logo.svg" alt="logo" class="logo"> -->
            <!-- <h3>Zivaan BASIC</h3> -->
          </div>
          <div class="login-wrapper my-auto">
            <h1 class="login-title">Reset Your Password</h1>
            <form action="<?=base_url('auth/reset_password/'.$code)?>" method="post">
              <div class="form-group">
                <label >New Password <span class="text-danger">*</span></label>
                <input type="password" class="form-control field_validation"  name="new" id="new" value="<?=set_value('new')?>" placeholder="New Password">
                <span id="err_new" class="error text-danger"><?=form_error('new');?></span>
              </div>
              <div class="form-group">
                <label >Password <span class="text-danger">*</span></label>
                <input type="password" maxlength="100" class="form-control field_validation" name="new_confirm" id="new_confirm" placeholder="Confirm New Password">
                <span id="err_new_confirm" class="error text-danger"><?=form_error('new_confirm');?></span>
              </div>
              
           
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

              <?php echo form_input($user_id);?>
              
              <input type="submit" name="submit" id="" class="btn btn-block btn-sm login-btn" value="Reset Password">
            </form>
            <a href="<?=base_url('auth/forgot_password')?>" class="forgot-password-link">Forgot password?</a>
            <?php 
              if($this->session->userdata('message'))
              {
            ?>
              <div class="error_message">
                <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
                <?=$this->session->userdata('message');?>
              </div>
            <?php 
              }
            ?>
            
          </div>
        </div>
        <div class="col-sm-6 px-0 d-none d-sm-block">
          <img src="<?=base_url('assets')?>/login-page/images/ZivaanProLoginPage.png" alt="login image" class="login-img">
        </div>
      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
