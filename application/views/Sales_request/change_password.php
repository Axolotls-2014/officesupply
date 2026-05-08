<?php 
  $this->load->view('layout/header');
?>
<div class="content-wrapper">
  <!-- Page header -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <h1>Change Password</h1>
        </div>
      </div>
    </div>
  </section>

  <!-- Main content -->
         <section class="content">
            <div class="container-fluid">
              <div class="card card-info">
                 <div class="card-header">
                  <h3 class="card-title">Update Your Password</h3>
                </div>
               <form method="post" action="<?= base_url('Purchase_request/update_password') ?>">
            <div class="card-body">
            <div class="form-group">
              <label for="new_password">New Password</label>
              <input type="password" name="new_password" class="form-control" id="new_password" required>
            </div>
            <div class="form-group">
              <label for="confirm_password">Confirm New Password</label>
              <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
            </div>
          </div>
          <div class="card-footer">
            <button type="submit" class="btn btn-info">Change Password</button>
          </div>
        </form>
      </div>
    </div>
  </section>
</div>

<?php 
  $this->load->view('layout/footer');
?>
