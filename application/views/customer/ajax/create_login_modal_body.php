<form method="POST" name="customerDetailForm" id="customerDetailForm">

  <div class="modal-header">
    <h4 class="modal-title">Enable Login</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">

  
    <div class="col-md-12">
      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line('customer_first_name')?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="first_name" value="<?=$customer->customer_name?>"  class="form-control form-control-sm" id="first_name" readonly>
            </div>
          </div> 
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line('customer_last_name')?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="last_name" value=""  class="form-control form-control-sm" id="last_name">
            </div>
          </div> 
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
            <?=$this->lang->line('customer_email')?><span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
                <input type="text" name="email" value="<?= !empty($customer->email) ? $customer->email : '' ?>" class="form-control form-control-sm field_validation" id="email" placeholder="Email" <?= !empty($customer->email) ? 'readonly' : '' ?>>
                <span id="err_email" class="error invalid-feedback"></span>
            </div>


          </div> 
        </div>
      </div>

     

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line('customer_phone')?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="phone" value="<?=$customer->phone?>"  class="form-control form-control-sm" id="phone">
            </div>
          </div> 
        </div>
      </div>

      <div class="row">
        <div class="col-sm-12">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line('customer_password')?><span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <input type="password" name="password" value=""  class="form-control form-control-sm field_validation" id="password">
            </div>
          </div> 
        </div>
      </div>


  
    </div>
  </div>

  <div class="modal-footer">

    <input type="hidden" name="customer_id" value="<?=$customer->id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="customerDetailSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
   </div>
</form>

<?php
// Function to generate a random string
function generateRandomString($length = 8) {
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $randomString = '';

    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }

    return $randomString;
}
?>