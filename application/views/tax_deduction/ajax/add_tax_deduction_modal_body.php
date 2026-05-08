<form  name="addtaxDeductionForm" id="addtaxDeductionForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
    <?php 
        if($tax_deduction == '')
          echo $this->lang->line('tax_deduction_add');
        else
          echo $this->lang->line('tax_deduction_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
     

      <div class="form-group row">
        <label for="tax_deduction_date" class="col-md-4 required"><?=$this->lang->line('tax_deduction_date')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm datepicker field_validation" name="tax_deduction_date" id="tax_deduction_date" value="<?=($tax_deduction != null && $tax_deduction->tax_deduction_date != '' && $tax_deduction->tax_deduction_date != '0000-00-00') ? date('d-m-Y', strtotime($tax_deduction->tax_deduction_date)) :  date('d-m-Y') ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('tax_deduction_date')?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('tax_deduction_date')?>" autocomplete="off">  
          <span id="err_tax_deduction_date" class="error invalid-feedback"></span> 
        </div>
      </div>

      <div class="form-group row">
        <label for="employee_id" class="col-md-4 required"><?=$this->lang->line('tax_deduction_employee_id')?></label>
        <div class="col-md-8">
          <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('tax_deduction_employee_id')?>" id="employee_id" name="employee_id">
            <option value="">Select</option>
            <?php
                $tax_deduction_employee_id = $tax_deduction ? $tax_deduction->employee_id : '';
                foreach ($employees as $value) {
                    $selected = ($value->employee_id == $tax_deduction_employee_id) ? ' selected' : '';
            ?>
                <option value="<?=$value->employee_id;?>"<?=$selected;?>>
                    <?= $value->first_name;?>
                </option>
            <?php 
                }
            ?>
          </select>
          <span id="err_employee_id" class="error invalid-feedback"></span> 
        </div>
      </div>

      
      <div class="form-group row">
        <label for="tax_type" class="col-md-4 required"><?=$this->lang->line('tax_deduction_tax_type')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm field_validation" name="tax_type" id="tax_type" value="<?=($tax_deduction != null) ? $tax_deduction->tax_type : '' ?>" placeholder="<?=$this->lang->line('tax_deduction_tax_type')?>"> 
          <span id="err_tax_type" class="error invalid-feedback"></span> 
        </div>
        
      </div>

     

      <div class="form-group row">
        <label for="amount" class="col-md-4 required"><?=$this->lang->line('tax_deduction_amount')?></label>
        <div class="col-md-8">
          <input type="number" class="form-control form-control-sm field_validation" name="amount" id="amount" value="<?=($tax_deduction != null) ? $tax_deduction->amount : 0 ?>" placeholder="<?=$this->lang->line('tax_deduction_amount')?>"> 
          <span id="err_amount" class="error invalid-feedback"></span> 
        </div>
      </div>

  </div>
  <div class="modal-footer">
    <input type="hidden" name="tax_deduction_id" id="tax_deduction_id" value="<?=($tax_deduction != null) ? $tax_deduction->tax_deduction_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addtaxDeductionSubmit" name="addtaxDeductionSubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>