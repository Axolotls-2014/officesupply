<div class="form-group row">
  <label for="inputEmail3" class="col-sm-2 col-form-label">
    <?=$this->lang->line('voucher_date')?>
  </label>
  <div class="col-sm-4">
    <input type="text" name="voucher_date" value="<?=date('d-m-Y')?>" class="form-control form-control-sm datepicker" id="voucher_date" placeholder="<?=$this->lang->line('voucher_date')?>" style="cursor:pointer;">
  </div>
</div>

<div class="form-group row">
  <label for="inputEmail3" class="col-sm-2 col-form-label">
    <?=$this->lang->line('to_account')?>
    <span class="text-danger">*</span>
  </label>
  <div class="col-sm-4">
    <select class="form-control form-control-sm select2bs4 field_validation" name="to_account" id="to_account" placeholder="<?=$this->lang->line('to_account')?>" width="100%">
      <option value=""><?=$this->lang->line('select')?></option>
      <?php
        foreach ($to_account as $value) {
      ?>
        <option value="<?=$value->id;?>" <?php echo set_select('to_account', $value->id); ?>>
          <?= ucfirst($value->title).' (CB : '.$value->closing_balance.')';?>
        </option>
      <?php 
        }
      ?>
    </select>

    <span id="err_to_account" class="error invalid-feedback"><?=form_error('to_account');?></span>
  </div>
</div>
<div class="form-group row">
  <label for="inputEmail3" class="col-sm-2 col-form-label">
    <?=$this->lang->line('transaction_amount')?>
    <span class="text-danger">*</span>
  </label>
  <div class="col-sm-4">
    <input type="number" name="transaction_amount" value="<?=$sale->total-$paid_amount?>" class="form-control form-control-sm field_validation" id="transaction_amount" placeholder="<?=$this->lang->line('transaction_amount')?>" max="<?=round($sale->total-$paid_amount)?>">
    <span id="err_transaction_amount" class="error invalid-feedback"><?=form_error('transaction_amount');?></span>
  </div>
</div>
<div class="form-group row">
  <label for="inputEmail3" class="col-sm-2 col-form-label">
    <?=$this->lang->line('payment_mode')?>
    <span class="text-danger">*</span>
  </label>
  <div class="col-sm-4">
    <select class="form-control form-control-sm select2bs4 field_validation" name="payment_mode" id="payment_mode" placeholder="<?=$this->lang->line('payment_mode')?>" width="100%">
      <option value=""><?=$this->lang->line('select')?></option>
      <option value="0"><?=$this->lang->line('transaction_mode_cash')?></option>
      <option value="1"><?=$this->lang->line('transaction_mode_credit_card')?></option>
      <option value="2"><?=$this->lang->line('transaction_mode_cheque')?></option>
      <option value="3"><?=$this->lang->line('transaction_mode_neft')?></option>
    </select>

    <span id="err_payment_mode" class="error invalid-feedback"><?=form_error('payment_mode');?></span>
  </div>
</div>
<div class="form-group row credit_card_row transaction_mode_row" style="display: none;">
  <label for="inputEmail3" class="col-sm-2 col-form-label">
    <?=$this->lang->line('transaction_mode_credit_card_no')?>
  </label>
  <div class="col-sm-4">
    <input type="number" name="credit_card_no" value="<?=set_value('credit_card_no') ?>" class="form-control form-control-sm" id="credit_card_no" placeholder="<?=$this->lang->line('transaction_mode_credit_card_no')?>">
    <span id="err_credit_card_no" class="error invalid-feedback"><?=form_error('credit_card_no');?></span>
  </div>
</div>
<div class="form-group row cheque_row transaction_mode_row" style="display: none;">
  <label for="inputEmail3" class="col-sm-2 col-form-label">
    <?=$this->lang->line('transaction_mode_cheque_no')?>
  </label>
  <div class="col-sm-4">
    <input type="number" name="cheque_no" value="<?=set_value('cheque_no') ?>" class="form-control form-control-sm" id="cheque_no" placeholder="<?=$this->lang->line('transaction_mode_cheque_no')?>">
    <span id="err_cheque_no" class="error invalid-feedback"><?=form_error('cheque_no');?></span>
  </div>
</div>
<div class="form-group row">
  <label for="inputEmail3" class="col-sm-2 col-form-label">
    <?=$this->lang->line('transaction_reference_no')?>
  </label>
  <div class="col-sm-4">
    <input type="text" name="reference_no" value="<?=set_value('reference_no') ?>" class="form-control form-control-sm" id="reference_no" placeholder="<?=$this->lang->line('transaction_reference_no')?>">
    <span id="err_reference_no" class="error invalid-feedback"><?=form_error('reference_no');?></span>
  </div>
</div>