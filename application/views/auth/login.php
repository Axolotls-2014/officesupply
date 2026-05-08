<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link rel="icon" type="image/x-icon" href="<?=base_url().'assets/img/favicon.ico'?>">
  <title><?=$app_name?> | Login</title>
  <link href="https://fonts.googleapis.com/css?family=Karla:400,700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/4.8.95/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?=base_url('assets')?>/login-page/css/login.css">
  <style type="text/css">
    .error_message{
      color: #dc3545; 
    }
    /* Adjust the image size for smaller screens using media queries */
    @media (max-width: 767px) { /* Adjust for screens smaller than or equal to 767px */
      .login-img {
        width: 80%; /* Adjust the width as needed */
        /* You can adjust other styles like margin or padding here if needed */
      }
    }
  </style>
</head>
<body>
  <main>
    <div class="container-fluid">
      <div class="row">
        <div class="col-md-6 col-sm-12 login-section-wrapper">
         <div class="brand-wrapper">
            <span class="brand-content">
                <img src="/assets/images/0/13972039167ee846674c9f.png" alt="logo" class="logo" style="width:200px;height:50px;"> <!-- Adjust width as needed -->
                <!--<h3 style="display: inline;" class="ml-2">Invade Agro Limited</h3>-->
            </span>
        </div>
          <div class="login-wrapper my-auto">
            <h1 class="login-title">Log in</h1>
            <form action="<?=base_url('auth/login')?>" method="post">
              <div class="form-group">
                <label for="email">Username</label>
                <input type="text" class="form-control" name="identity" id="identity" placeholder="Eg. jondoe" required="required" <?php echo (strpos($_SERVER['HTTP_HOST'], 'vaksys.com') !== false) ? 'value="zivaansolutions@gmail.com"' : ''; ?>>
              </div>
              <div class="form-group mb-4">
                <label for="password">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="********" required="required" <?php echo (strpos($_SERVER['HTTP_HOST'], 'vaksys.com') !== false) ? 'value="Zivaan!@#123"' : ''; ?>>
              </div>
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <input name="login" id="login" class="btn btn-block login-btn" type="submit" value="Login">
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
            <div class="error_message">
              <!-- <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button> -->
              <?=validation_errors()?>
            </div>
            
          </div>
        </div>
       <div class="col-md-6 col-sm-12 px-0 d-none d-sm-block">
    <img 
        src="<?=base_url('uploads')?>/login.jpg" 
        alt="login image" 
        class="login-img" 
        style="max-width: 80%; height: auto; object-fit: contain;">
     </div>

      </div>
    </div>
  </main>
  <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
</body>
</html>
