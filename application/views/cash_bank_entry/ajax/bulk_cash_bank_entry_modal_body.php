  
<form role="form" method="post" name="bulkcashBankEntryForm" id="bulkcashBankEntryForm">
  <div class="modal-header text-left">
    <h4 class="modal-title">Bulk Entry</h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

    <div class="row">
      <div class="col-sm-12">
        <table class="table table-bordered" style="width: 100%;">
          <thead>
            <tr>
              <td width="15%">
                <?=$this->lang->line('cash_bank_entry_voucher_type')?>
                <select class="form-control field_validation form-control-sm select2bs4" placeholder="<?=$this->lang->line('cdn_note_type')?>" id="voucher_type" name="voucher_type">
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
              </td>

              <td width="15%">
                <?=$this->lang->line('cash_bank_entry_voucher_date')?>
                <input type="text" name="voucher_date"  value="<?=set_value('voucher_date',date('d-m-Y')) ?>" class="form-control datepicker field_validation" style="cursor:pointer;" autocomplete="off" id="voucher_date" placeholder="<?=$this->lang->line('cash_bank_entry_voucher_date')?>">
                <span id="err_voucher_date" class="error invalid-feedback"></span>
              </td>

              <td width="15%">
                <?=$this->lang->line('cash_bank_entry_from_account_id')?>
                <select class="form-control field_validation form-control-sm select2bs4"  placeholder="<?=$this->lang->line('cash_bank_entry_from_account_id')?>" id="from_account_id" name="from_account_id">
                  <option value="">Select</option>
                </select>
                <span id="err_from_account_id" class="error invalid-feedback"></span>
              </td>

              <td width="15%">
                <?=$this->lang->line('cash_bank_entry_to_account_id')?>
                <select class="form-control field_validation form-control-sm select2bs4"  placeholder="<?=$this->lang->line('cash_bank_entry_to_account_id')?>" id="to_account_id" name="to_account_id">
                  <option value="">Select</option>
                </select>
                <span id="err_to_account_id" class="error invalid-feedback"></span>
              </td>

              <td width="15%">
                <?=$this->lang->line('cash_bank_entry_amount')?>
                <input type="text" name="amount"  value="<?=set_value('amount') ?>" class="form-control field_validation amount" id="amount" placeholder="<?=$this->lang->line('cash_bank_entry_amount')?>">
                <span id="err_amount" class="error invalid-feedback"><!-- <?=form_error('amount');?> --></span>
              </td>

              <td width="20%">
                <?=$this->lang->line('cash_bank_entry_narration')?>
                <input type="text" name="narration"  value="<?=set_value('narration') ?>" class="form-control" id="narration" placeholder="<?=$this->lang->line('cash_bank_entry_narration')?>">
              </td>
              
              <th width="5%">
                <button class="btn btn-lg btn-primary btn-block add_cash_bank_entry">Add</button>
              </th>
            </tr>  
          </thead>
          <tbody id="cash_bank_entry_table_body">
           
          
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="modal-footer">
  <input type="hidden" name="cash_bank_entries" id="cash_bank_entries" value="">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="bulkcashBankEntrySubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>