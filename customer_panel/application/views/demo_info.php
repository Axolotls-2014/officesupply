<?php
function format_url($url) {
    if (!preg_match("/^https?:\/\//", $url)) {
        $url = "http://" . $url;
    }
    return $url;
}
?>

<!doctype html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Demo | <?= $data->project ?></title>
    <link rel="icon" href="<?= base_url('assets/admin/') ?>assets/images/favicon-32x32.png" type="image/png">
    <link href="<?= base_url('assets/admin/') ?>assets/css/pace.min.css" rel="stylesheet">
    <script src="<?= base_url('assets/admin/') ?>assets/js/pace.min.js"></script>
    <link href="<?= base_url('assets/admin/') ?>assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/') ?>assets/plugins/metismenu/metisMenu.min.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/') ?>assets/plugins/metismenu/mm-vertical.css">
    <link rel="stylesheet" type="text/css" href="<?= base_url('assets/admin/') ?>assets/plugins/simplebar/css/simplebar.css">
    <link href="<?= base_url('assets/admin/') ?>assets/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans:wght@300;400;500;600&amp;display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>assets/css/bootstrap-extended.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/main.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/dark-theme.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/blue-theme.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/semi-dark.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/bordered-theme.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>sass/responsive.css" rel="stylesheet">
    <link href="<?= base_url('assets/admin/') ?>assets/plugins/datatable/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/admin/') ?>assets/css/extra-icons.css">
</head>

<body>
  <main class="p-3">
    <div class="main-content">
    <div class="mb-4">
        <h2 class="text-danger">Project : <?= $data->project ?></h2>
        <?php if (!empty($data->remark)): ?>
        <p>Description : <?= $data->remark ?></p>
        <?php endif; ?>
    </div>
      <div class="row">
        <?php if (!empty($data->attachment)): ?>
        <div class="col-12 col-xl-4">
          <div class="card border-top border-4 border-info">
            <div class="card-body p-4 rounded" style="background-image: url('<?= base_url('uploads/demo/'.$data->attachment) ?>'); background-size: 80% 80%; background-position: center; background-repeat: no-repeat;">
              <div class="px-2 py-1 fw-medium bg-info bg-opacity-10 text-info text-uppercase w-25 text-center rounded">Image</div>
              <div style="height: 250px;">
                  
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($data->web_link)): ?>
        <div class="col-12 col-xl-4">
          <div class="card border-top border-4 border-info">
            <div class="card-body p-4">
              <div class="px-2 py-1 fw-medium bg-info bg-opacity-10 text-info text-uppercase w-25 text-center rounded">Website</div>
              <div class="my-4">
                <h3 class="mb-2">Website Details</h3>
                <p class="mb-0 text-danger">Demo purpose only</p>
              </div>
              <div class="pricing-content d-flex flex-column gap-3">
                <div class="d-flex align-items-center justify-content-between">
                  <p class="mb-0 fs-6">Category</p>
                  <p class="mb-0 fw-medium fs-6"><?= $data->type ?></p>
                </div>
              </div>
              <div class="d-grid mt-4">
                <a href="<?= format_url($data->web_link) ?>" class="btn btn-lg btn-info text-white" target="_blank" rel="noopener noreferrer" style="margin-top:2em">Goto Website</a>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
        
        <?php if (!empty($data->apk_file)): ?>
        <div class="col-12 col-xl-4">
          <div class="card border-top border-4 border-primary">
            <div class="card-body p-4">
              <div class="px-2 py-1 fw-medium bg-primary bg-opacity-10 text-primary text-uppercase w-25 text-center rounded">App</div>
              <div class="my-4">
                <h3 class="mb-2">Apk Details</h3>
                <p class="mb-0 text-danger">Demo purpose only</p>
              </div>
              <div class="pricing-content d-flex flex-column gap-3">
                <div class="d-flex align-items-center justify-content-between">
                  <p class="mb-0 fs-6">Category</p>
                  <p class="mb-0 fw-medium fs-6"><?= $data->type ?></p>
                </div>
              </div>
              <div class="d-grid mt-4">
                <a href="<?= format_url($data->apk_file) ?>" class="btn btn-lg btn-primary text-white" target="_blank" rel="noopener noreferrer" style="margin-top:2em">Goto Download</a>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($data->username) && !empty($data->user_password)): ?>
        <div class="col-12 col-xl-4">
          <div class="card border-top border-4 border-success">
            <div class="card-body p-4">
              <div class="px-2 py-1 fw-medium bg-success bg-opacity-10 text-success text-uppercase w-25 text-center rounded">User</div>
              <div class="my-4">
                <h3 class="mb-2">User Login</h3>
                <p class="mb-0 text-danger">Demo purpose only</p>
              </div>
              <div class="pricing-content d-flex flex-column gap-3">
                <div class="d-flex align-items-center justify-content-between">
                  <p class="mb-0 fs-6">Username</p>
                  <p class="mb-0 fw-medium fs-6"><?= $data->username ?></p>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                  <p class="mb-0 fs-6">Password</p>
                  <p class="mb-0 fw-medium fs-6"><?= $data->user_password ?></p>
                </div>
              </div>
              <div class="d-grid mt-4">
                <a href="<?= format_url($data->user_link) ?>" class="btn btn-lg btn-success text-white" target="_blank" rel="noopener noreferrer">Goto Login</a>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>

        <?php if (!empty($data->admin_username) && !empty($data->admin_password)): ?>
        <div class="col-12 col-xl-4">
          <div class="card border-top border-4 border-danger">
            <div class="card-body p-4">
              <div class="px-2 py-1 fw-medium bg-danger bg-opacity-10 text-danger text-uppercase w-25 text-center rounded">Admin</div>
              <div class="my-4">
                <h3 class="mb-2">Admin Login</h3>
                <p class="mb-0 text-danger">Demo purpose only</p>
              </div>
              <div class="pricing-content d-flex flex-column gap-3">
                <div class="d-flex align-items-center justify-content-between">
                  <p class="mb-0 fs-6">Username</p>
                  <p class="mb-0 fw-medium fs-6"><?= $data->admin_username ?></p>
                </div>
                <div class="d-flex align-items-center justify-content-between">
                  <p class="mb-0 fs-6">Password</p>
                  <p class="mb-0 fw-medium fs-6"><?= $data->admin_password ?></p>
                </div>
              </div>
              <div class="d-grid mt-4">
                <a href="<?= format_url($data->admin_link) ?>" class="btn btn-lg btn-danger text-white" target="_blank" rel="noopener noreferrer">Goto Login</a>
              </div>
            </div>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </main>
</body>

</html>
