<form  name="addPayrollForm" id="addPayrollForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($payroll == '')
          echo $this->lang->line('payroll_add');
        else
          echo $this->lang->line('payroll_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">

      <div class="form-group row">
        <label for="employee_id" class="col-md-4 required"><?=$this->lang->line('payroll_employee_id')?></label>
        <div class="col-md-8">
          <select class="form-control form-control-sm field_validation select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('payroll_employee_id')?>" id="employee_id" name="employee_id">
            <option value="">Select</option>
            <?php
                $payroll_employee_id = $payroll ? $payroll->employee_id : '';
                foreach ($employees as $value) {
                    $selected = ($value->employee_id == $payroll_employee_id) ? ' selected' : '';
            ?>
                <option value="<?=$value->employee_id;?>" data-salary="<?=$value->salary;?>" <?=$selected;?>>
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
        <label for="payroll_date" class="col-md-4 required"><?=$this->lang->line('payroll_date')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control field_validation form-control-sm datepicker" name="payroll_date" id="payroll_date" value="<?=($payroll != null && $payroll->payroll_date != '' && $payroll->payroll_date != '0000-00-00') ? date('d-m-Y', strtotime($payroll->payroll_date)) : '' ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('payroll_date')?>" autocomplete="off"> 
          <span id="err_payroll_date" class="error invalid-feedback"></span>   
        </div>
      </div>


      <div class="form-group row">
        <label for="gross_amount" class="col-md-4"><?=$this->lang->line('payroll_gross_amount')?></label>
        <div class="col-md-8">
          <input type="gross_amount" class="form-control form-control-sm" name="gross_amount" id="gross_amount" value="<?=($payroll != null) ? $payroll->gross_amount : '' ?>" placeholder="<?=$this->lang->line('payroll_gross_amount')?>" readonly>  
        </div>
      </div>

      <div class="form-group row">
        <label for="net_amount" class="col-md-4"><?=$this->lang->line('payroll_net_amount')?></label>
        <div class="col-md-8">
          <input type="net_amount" class="form-control form-control-sm" name="net_amount" id="net_amount" value="<?=($payroll != null) ? $payroll->net_amount : '' ?>" placeholder="<?=$this->lang->line('payroll_net_amount')?>" readonly>  
        </div>
      </div>

      
      
  </div>
  <div class="modal-footer">
    <input type="hidden" name="payroll_id" id="payroll_id" value="<?=($payroll != null) ? $payroll->payroll_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addPayrollSubmit" name="addPayrollSubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>