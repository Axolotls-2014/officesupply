<!doctype html>
<html lang="en" data-bs-theme="blue-light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Authentication</title>
    <link rel="icon" href="<?= base_url('assets/admin/') ?>assets/images/favicon-32x32.png" type="image/png">
    <link href="<?= base_url('assets/admin/') ?>assets/css/pace.min.css" rel="stylesheet">
    <script src="<?= base_url('assets/admin/') ?>assets/js/pace.min.js"></script>
    <link href="<?= base_url('assets/admin/') ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link rel="<?=  base_url('assets/admin/') ?>stylesheet" type="text/css" href="assets/plugins/metismenu/metisMenu.min.css">
    <link rel="<?=  base_url('assets/admin/') ?>stylesheet" type="text/css" href="assets/plugins/metismenu/mm-vertical.css">
    <link href="<?= base_url('assets/admin/') ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/main.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/dark-theme.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/blue-theme.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/responsive.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
</head>

<body>
    <!--authentication-->
    <div class="auth-basic-wrapper d-flex align-items-center justify-content-center">
        <div class="container-fluid my-5 my-lg-0">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5 col-xxl-4 mx-auto">
                    <div class="card rounded-4 mb-0 border-top border-4 border-primary border-gradient-1">
                        <div class="card-body p-5">
                            <div class="d-flex justify-content-center">
                                <img src="<?= base_url('uploads/logo.webp"') ?>" width="200" alt="">
                            </div>
                            <div class="form-body my-3">
                                <form class="row g-3">
                                    <div class="col-12">
                                        <label for="inputEmailAddress" class="form-label">Email</label>
                                        <input type="email" class="form-control" id="inputEmailAddress" placeholder="Enter email" name="username" required>
                                    </div>
                                    <!--<div class="col-12">-->
                                    <!--    <label for="inputChoosePassword" class="form-label">Password</label>-->
                                    <!--    <div class="input-group" id="show_hide_password">-->
                                    <!--        <input type="password" class="form-control border-end-0" id="inputChoosePassword" required placeholder="Enter Password" name="password">-->
                                    <!--        <a href="javascript:;" class="input-group-text bg-transparent"><i class="bi bi-eye-slash-fill"></i></a>-->
                                    <!--    </div>-->
                                    <!--</div>-->
                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked">
                                            <label class="form-check-label" for="flexSwitchCheckChecked">Remember Me</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-end"> <a href="#">Forgot Password ?</a>
                                    </div>
                                    <div class="col-12">
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-grd-info text-white">Request Otp</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!--plugins-->
    <script src="<?= base_url('assets/admin/') ?>assets/js/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#show_hide_password a").on('click', function (event) {
              event.preventDefault();
              if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password i').addClass("bi-eye-slash-fill");
                $('#show_hide_password i').removeClass("bi-eye-fill");
              } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password i').removeClass("bi-eye-slash-fill");
                $('#show_hide_password i').addClass("bi-eye-fill");
              }
            });
          });
    </script>
    <script>
        document.querySelector('form').addEventListener('submit', function(e) {
            e.preventDefault(); 
        
            var formData = new FormData(this);
        
            fetch('<?= base_url('auth/send_otp') ?>', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: data.message,
                        icon: 'success'
                    }).then(() => {
                        window.location.href = '<?= site_url('auth/otpLogin') ?>';
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: data.message,
                        icon: 'error'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error!',
                    text: 'An unexpected error occurred.',
                    icon: 'error'
                });
            });
        });
    </script>
</body>
</html>