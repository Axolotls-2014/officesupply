<style>
  .error-message {
    color: red;
}
</style>
<form role="form" method="post" name="adddepositForm" id="adddepositForm">
  <div class="modal-header text-left">
    <h4 class="modal-title">
      <?php 
        echo $this->lang->line('pdc_receive_deposit');
      ?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line("pdc_receive_actual_deposit_date")?>
      </label>
      <div class="col-sm-9">

        <div class="input-group">
          <input type="text" class="form-control datepicker field_validation" name="actual_deposit_date" id="actual_deposit_date" 
              value="<?= isset($pdc_payment) && !empty($pdc_payment->actual_deposit_date) && $pdc_payment->actual_deposit_date != '0000-00-00' ? date('d-m-Y', strtotime($pdc_payment->actual_deposit_date)) : '' ?>" 
              readonly="readonly" autocomplete="off" placeholder="Actual Deposit Date" style="cursor:pointer;">
          <span class="input-group-append">
              <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
          </span>
          <span id="err_actual_deposit_date" class="error invalid-feedback"></span> 
        </div>

      </div>
    </div>
   

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line("pdc_receive_pay_slip_no")?>
      </label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" name="pay_slip_no" id="pay_slip_no" value="<?=($pdc_payment != null) ? $pdc_payment->pay_slip_no : '' ?>" placeholder="<?=$this->lang->line("pdc_receive_pay_slip_no")?>">  
      </div>
    </div>  


    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line("pdc_receive_deposited_by")?>
      </label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm" name="deposited_by" id="deposited_by" value="<?=($pdc_payment != null) ? $pdc_payment->deposited_by : '' ?>" placeholder="<?=$this->lang->line("pdc_receive_deposited_by")?>">
      </div>
    </div>

   
  </div>
  <div class="modal-footer">
    <input type="hidden" name="id" id="id" value="<?=($pdc_payment != null) ? $pdc_payment->id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="adddepositSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>

