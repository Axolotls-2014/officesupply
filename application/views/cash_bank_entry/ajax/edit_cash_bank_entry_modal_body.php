  
<form role="form" method="post" name="editcashBankEntryForm" id="editcashBankEntryForm">
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
        <input type="text" name="reference_no" value="<?=set_value("reference_no",$cash_bank_entry->reference_no) ?>" class="form-control form-control-sm" id="reference_no"  placeholder="<?=$this->lang->line('cash_bank_entry_reference_no')?>" readonly>
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
              <option value="<?=$voucher_type_array[$i]?>"
                <?php 
                  if($cash_bank_entry->voucher_type == $voucher_type_array[$i])
                    echo ' selected';
                ?>
              ><?=clean_e_val($voucher_type_array[$i])?></option>
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
        <input type="text" name="voucher_date"  value="<?=set_value('voucher_date',(($cash_bank_entry->voucher_date != '' && $cash_bank_entry->voucher_date != '0000-00-00') ? date('d-m-Y',strtotime($cash_bank_entry->voucher_date)) : '' ))?>" class="form-control form-control-sm datepicker" style="cursor:pointer;" id="voucher_date" placeholder="<?=$this->lang->line('cash_bank_entry_voucher_date')?>">
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
            <?php 

              $from_account_record_type = '';

              if($cash_bank_entry->voucher_type == VOUCHER_TYPE_BANK_PAYMENT)
              {
                $from_account_record_type = BANK_ACCOUNT_GROUP;
              }
              else if($cash_bank_entry->voucher_type == VOUCHER_TYPE_CASH_PAYMENT)
              {
                $from_account_record_type = CASH_GROUP;
              }
              else if($cash_bank_entry->voucher_type == VOUCHER_TYPE_BANK_RECEIPT && $cash_bank_entry->voucher_type == VOUCHER_TYPE_CASH_RECEIPT)
              {
                $from_account_record_type = SUNDRY_DEBTORS_GROUP;
              }
              
                
              foreach($from_account_records as $from_account_record)
              {
            ?>
              <option value="<?=$from_account_record->id?>"

                    <?php 
                      if($from_account_record->id == $cash_bank_entry->from_account_id)
                        echo ' selected';
                    ?>
              >
                <?php echo $from_account_record->title; ?>
              </option>
            <?php
              }
            ?>
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
            <?php 

              $to_account_record_type = '';

              if($cash_bank_entry->voucher_type == VOUCHER_TYPE_BANK_PAYMENT)
              {
                $to_account_record_type = BANK_ACCOUNT_GROUP;
              }
              else if($cash_bank_entry->voucher_type == VOUCHER_TYPE_CASH_PAYMENT)
              {
                $to_account_record_type = CASH_GROUP;
              }
              else if($cash_bank_entry->voucher_type == VOUCHER_TYPE_BANK_RECEIPT && $cash_bank_entry->voucher_type == VOUCHER_TYPE_CASH_RECEIPT)
              {
                $to_account_record_type = SUNDRY_DEBTORS_GROUP;
              }
              
                
              foreach($to_account_records as $to_account_record)
              {
            ?>
              <option value="<?=$to_account_record->id?>"

                  <?php 
                    if($to_account_record->id == $cash_bank_entry->to_account_id)
                      echo ' selected';
                  ?>
              >
                <?php echo $to_account_record->title; ?>
              </option>
            <?php
              }
            ?>
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
        <input type="text" name="amount"  value="<?=set_value("amount",$cash_bank_entry->amount) ?>" class="form-control form-control-sm field_validation" id="amount" placeholder="<?=$this->lang->line('cash_bank_entry_amount')?>"><?=form_error('amount', '<div class="text-danger">', '</div>');?>
        <span id="err_amount" class="error invalid-feedback"><!-- <?=form_error('amount');?> --></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-3 col-form-label">
        <?=$this->lang->line('cash_bank_entry_narration')?>
      </label>
      <div class="col-sm-9">
        <input type="text" name="narration"  value="<?=set_value("narration",$cash_bank_entry->narration) ?>" class="form-control form-control-sm" id="narration" placeholder="<?=$this->lang->line('cash_bank_entry_narration')?>">
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="id" id="id" value="<?=$cash_bank_entry->id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="editcashBankEntrySubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>