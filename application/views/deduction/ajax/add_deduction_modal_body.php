<form  name="addDeductionForm" id="addDeductionForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($deduction == '')
          echo $this->lang->line('deduction_add');
        else
          echo $this->lang->line('deduction_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
     
      <div class="form-group row">
        <label for="employee_id" class="col-md-4 required"><?=$this->lang->line('tax_deduction_employee_id')?></label>
        <div class="col-md-8">
          <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('tax_deduction_employee_id')?>" id="employee_id" name="employee_id">
            <option value="">Select</option>
            <?php
                $deduction_employee_id = $deduction ? $deduction->employee_id : '';
                foreach ($employees as $value) {
                    $selected = ($value->employee_id == $deduction_employee_id) ? ' selected' : '';
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
        <label for="deduction_type" class="col-md-4 required"><?=$this->lang->line('deduction_type')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm field_validation" name="deduction_type" id="deduction_type" value="<?=($deduction != null) ? $deduction->deduction_type : '' ?>" placeholder="<?=$this->lang->line('deduction_type')?>">  
          <span id="err_deduction_type" class="error invalid-feedback"></span> 
        </div>
      </div>

      <div class="form-group row">
        <label for="deduction_date" class="col-md-4 required"><?=$this->lang->line('deduction_date')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm datepicker field_validation" name="deduction_date" id="deduction_date" value="<?=($deduction != null && $deduction->deduction_date != '' && $deduction->deduction_date != '0000-00-00') ? date('d-m-Y', strtotime($deduction->deduction_date)) : date('d-m-Y') ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('deduction_date')?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('deduction_date')?>" autocomplete="off">
          <span id="err_deduction_date" class="error invalid-feedback"></span>   
        </div>
      </div>

      <div class="form-group row">
        <label for="amount" class="col-md-4 required"><?=$this->lang->line('deduction_amount')?></label>
        <div class="col-md-8">
          <input type="number" class="form-control form-control-sm field_validation" name="amount" id="amount" value="<?=($deduction != null) ? $deduction->amount : 0 ?>" placeholder="<?=$this->lang->line('deduction_amount')?>">  
          <span id="err_amount" class="error invalid-feedback"></span> 
        </div>
      </div>

   
  </div>
  <div class="modal-footer">
    <input type="hidden" name="deduction_id" id="deduction_id" value="<?=($deduction != null) ? $deduction->deduction_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addDeductionSubmit" name="addDeductionSubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>