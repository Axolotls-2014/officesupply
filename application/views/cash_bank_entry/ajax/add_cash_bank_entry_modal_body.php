  
<form role="form" method="post" name="addcashBankEntryForm" id="addcashBankEntryForm">
  <div class="modal-header text-left">
    <h4 class="modal-title">
      <?php 
        if($cash_bank_entry_id == '')
          echo $this->lang->line('cash_bank_entry_add');
        else
          echo $this->lang->line('cash_bank_entry_edit');
      ?>
    </h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line('cash_bank_entry_reference_no')?>
      </label>
      <div class="col-sm-9">
        <input type="text" name="reference_no" value="<?=set_value('reference_no') ?>" class="form-control form-control-sm" id="reference_no" disabled="disabled" placeholder="<?=$this->lang->line('cash_bank_entry_reference_no')?>">
      </div>
    </div>
    
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line('cash_bank_entry_voucher_type')?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-9">
      <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cdn_note_type')?>" id="voucher_type" name="voucher_type">
        <option value="">Select</option>
          <?php 
            $voucher_type_array = enum_select('cash_bank_entry','voucher_type');
            for($i = 0; $i < sizeof($voucher_type_array); $i++)
            {
          ?>
              <option value="<?=$voucher_type_array[$i]?>"><?=clean_e_val($voucher_type_array[$i])?></option>
          <?php 
            } 
          ?>
        </select>
        <span id="err_voucher_type" class="error invalid-feedback"><?=form_error('voucher_type');?></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line('cash_bank_entry_voucher_date')?>
      </label>
      <div class="col-sm-9">
        <input type="text" name="voucher_date"  value="<?=set_value('voucher_date',date('d-m-Y')) ?>" class="form-control form-control-sm datepicker" style="cursor:pointer;" id="voucher_date" placeholder="<?=$this->lang->line('cash_bank_entry_voucher_date')?>">
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line('cash_bank_entry_from_account_id')?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-9">
        <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cash_bank_entry_from_account_id')?>" id="from_account_id" name="from_account_id">
          <option value="">Select</option>
        </select>
        <span id="err_from_account_id" class="error invalid-feedback"></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line('cash_bank_entry_to_account_id')?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-9">
      <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('cash_bank_entry_to_account_id')?>" id="to_account_id" name="to_account_id">
          <option value="">Select</option>
        </select>
        <span id="err_to_account_id" class="error invalid-feedback"></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line('cash_bank_entry_amount')?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-9">
        <input type="text" name="amount"  value="<?=set_value('amount') ?>" class="form-control form-control-sm field_validation" id="amount" placeholder="<?=$this->lang->line('cash_bank_entry_amount')?>"><?=form_error('amount', '<div class="text-danger">', '</div>');?>
        <span id="err_amount" class="error invalid-feedback"><!-- <?=form_error('amount');?> --></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line('cash_bank_entry_narration')?>
      </label>
      <div class="col-sm-9">
        <input type="text" name="narration"  value="<?=set_value('narration') ?>" class="form-control form-control-sm" id="narration" placeholder="<?=$this->lang->line('cash_bank_entry_narration')?>">
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addcashBankEntrySubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>