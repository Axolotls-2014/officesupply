<?php $this->load->view('layout/header');?>


<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
        </div>
      </div>
    </section>

    <section class="content">

      <div class="error-page" style="padding-top: 15%">
        <h2 class="headline text-warning"> <i class="fas fa-exclamation-triangle text-warning"></i></h2>
        <div class="error-content">
          <h3>Oops! You are not authorized to access this page.</h3>
          <p>
            Please contact the administrator incase you want to have access this page, 
            you may <a href="<?=base_url('auth/dashboard')?>">return to index</a>.
          </p>
        </div>
      </div>
    </section>
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>



<?php $this->load->view('layout/footer');?>
