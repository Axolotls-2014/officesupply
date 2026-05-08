<style>
  .error-message {
    color: red;
}
</style>
<form role="form" method="post" name="addPDCpaymentForm" id="addPDCpaymentForm">
  <div class="modal-header text-left">
    <h4 class="modal-title">
      <?php 
        if($pdc_payment == '')
          echo $this->lang->line('pdc_payment_add');
        else
          echo $this->lang->line('pdc_payment_edit');
      ?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line("pdc_payment_ledger_id")?>
      </label>
      <div class="col-sm-9">
        <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('pdc_payment_ledger_id')?>" id="ledger_id" name="ledger_id">
          <option value="">Select</option>
            <?php 
                foreach ($ledger_accounts as $value) {
                    // Check if the current option is the selected one
                    $selected = ($value->id == $pdc_payment->ledger_id) ? 'selected' : '';
            ?>
                    <option value="<?=$value->id?>" <?=$selected?>><?=$value->title?></option>
            <?php 
                } 
            ?>
        </select>
        <span id="err_ledger_id" class="error invalid-feedback"></span>
      </div>
    </div>  
    
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line("pdc_payment_purchase_id")?>
      </label>
      <div class="col-sm-9">
        <select class="form-control form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('pdc_payment_purchase_id')?>" id="purchase_id" name="purchase_id[]" multiple="multiple">
          <option value="">Select</option>
          <?php 
                if(!is_null($pdc_payment)) 
                {
                  $purchases_arr  = (($pdc_payment->purchase_id != '') ? explode(",",$pdc_payment->purchase_id) : array());
                  foreach ($purchases as $sl) {
              ?>
                    <option value="<?=$sl->id?>"
                      <?php
                        if(in_array($sl->id,$purchases_arr))
                          echo ' selected';
                      ?>
                    >
                      <?=$sl->reference_no?>
                    </option>
              <?php
                  }
                }
              ?>
        </select>
        <!-- <span id="err_purchase_id" class="error invalid-feedback"></span> -->
      </div>
    </div>  

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line("pdc_payment_pdc_cheque_no")?>
      </label>
      <div class="col-sm-9">
        <input type="text" class="form-control form-control-sm field_validation" name="pdc_cheque_no" id="pdc_cheque_no" value="<?=($pdc_payment != null) ? $pdc_payment->pdc_cheque_no : '' ?>" placeholder="<?=$this->lang->line("pdc_payment_pdc_cheque_no")?>" autocomplete="off">  
        <span id="err_pdc_cheque_no" class="error invalid-feedback"></span>
      </div>
    </div>  

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line("pdc_payment_pdc_cheque_date")?>
      </label>
      <div class="col-sm-9">

        <div class="input-group">
          <input type="text" class="form-control datepicker" name="pdc_cheque_date" id="pdc_cheque_date" value="<?=($pdc_payment != null) ? date('d-m-Y', strtotime($pdc_payment->pdc_cheque_date)) : '' ?>"  readonly="readonly">
          <span class="input-group-append">
            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
          </span>
        </div>
        <span id="err_pdc_cheque_date" class="error invalid-feedback"></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line("pdc_payment_pdc_deposit_date")?>
      </label>
      <div class="col-sm-9">

        <div class="input-group">
          <input type="text" class="form-control datepicker" name="pdc_deposit_date" id="pdc_deposit_date" value="<?=($pdc_payment != null) ? date('d-m-Y', strtotime($pdc_payment->pdc_deposit_date)) : '' ?>" style="z-index:999 !important" readonly="readonly">
          <span class="input-group-append">
            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
          </span>
        </div>
        <span id="err_pdc_deposit_date" class="error invalid-feedback"></span>
      </div>
    </div>
    
  

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line("pdc_payment_pdc_amount")?>
      </label>
      <div class="col-sm-9">
        <input type="number" class="form-control form-control-sm field_validation" name="pdc_amount" id="pdc_amount" value="<?=($pdc_payment != null) ? $pdc_payment->pdc_amount : '' ?>" placeholder="<?=$this->lang->line("pdc_payment_pdc_amount")?>">
        <span id="err_pdc_amount" class="error invalid-feedback"></span>  
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label required">
        <?=$this->lang->line("pdc_payment_pdc_deposit_to")?>
      </label>
      <div class="col-sm-9">
        <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('pdc_payment_pdc_deposit_to')?>" id="pdc_deposit_to" name="pdc_deposit_to">
          <option value="">Select</option>
            <?php 
                foreach ($bank_ledger_accounts as $value) {
                    // Check if the current option is the selected one
                    $selected = ($value->id == $pdc_payment->pdc_deposit_to) ? 'selected' : '';
            ?>
                    <option value="<?=$value->id?>" <?=$selected?>><?=$value->title?></option>
            <?php 
                } 
            ?>
        </select>
        <span id="err_pdc_deposit_to" class="error invalid-feedback"></span>
      </div>
    </div>  
    
  </div>
  <div class="modal-footer">
    <input type="hidden" name="id" id="id" value="<?=($pdc_payment != null) ? $pdc_payment->id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addPDCpaymentSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>

