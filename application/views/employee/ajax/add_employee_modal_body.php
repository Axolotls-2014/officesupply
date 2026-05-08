<form  name="addEmployeeForm" id="addEmployeeForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($employee == '')
          echo $this->lang->line('employee_add');
        else
          echo $this->lang->line('employee_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
      <div class="form-group row">
        <label for="first_name" class="col-md-4 required"><?=$this->lang->line('employee_first_name')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm field_validation" name="first_name" id="first_name" value="<?=($employee != null) ? $employee->first_name : '' ?>" placeholder="<?=$this->lang->line('employee_first_name')?>">  
          <span class="error invalid-feedback" id="err_first_name"></span>
        </div>
      </div>

      <div class="form-group row">
        <label for="last_name" class="col-md-4 required"><?=$this->lang->line('employee_last_name')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm field_validation" name="last_name" id="last_name" value="<?=($employee != null) ? $employee->last_name : '' ?>" placeholder="<?=$this->lang->line('employee_last_name')?>">  
          <span class="error invalid-feedback" id="err_last_name"></span>
        </div>
      </div>

      <div class="form-group row">
        <label for="email" class="col-md-4"><?=$this->lang->line('employee_email')?></label>
        <div class="col-md-8">
          <input type="email" class="form-control form-control-sm" name="email" id="email" value="<?=($employee != null) ? $employee->email : '' ?>" placeholder="<?=$this->lang->line('employee_email')?>">  
        </div>
      </div>

      <div class="form-group row">
        <label for="date_of_birth" class="col-md-4"><?=$this->lang->line('employee_date_of_birth')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm datepicker" name="date_of_birth" id="date_of_birth" value="<?=($employee != null && $employee->date_of_birth != '' && $employee->date_of_birth != '0000-00-00') ? date('d-m-Y', strtotime($employee->date_of_birth)) : '' ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('employee_date_of_birth')?>" autocomplete="off">  
        </div>
      </div>

      <div class="form-group row">
        <label for="department_id" class="col-md-4 required"><?=$this->lang->line('employee_department_id')?></label>
        <div class="col-md-8">
          <div class="input-group">
            <select class="form-control form-control-sm select2bs4 field_validation" width="100%" placeholder="<?=$this->lang->line('employee_department_id')?>" id="department_id" name="department_id">
              <option value="">Select</option>
              <?php
                  $employee_department_id = $employee ? $employee->department_id : '';
                  foreach ($departments as $value) {
                      $selected = ($value->department_id == $employee_department_id) ? ' selected' : '';
              ?>
                  <option value="<?=$value->department_id;?>"<?=$selected;?>>
                      <?= $value->department_name;?>
                  </option>
              <?php 
                  }
              ?>
            </select> 
            <span class="input-group-append">
              <button type="button" class="btn btn-info btn-flat add_department_modal" data-tt="tooltip" title="Add Department" data-toggle="modal" data-target="#add_department_modal" data-tt="tooltip"><i class="fas fa-plus"></i>
              </button>
            </span>
          </div>
          <span class="error invalid-feedback" id="err_department_id"></span> 
        </div>
      </div>

      <div class="form-group row">
        <label for="position_id" class="col-md-4 required"><?=$this->lang->line('employee_position_id')?></label>
        <div class="col-md-8">
          <div class="input-group">
          <select class="form-control form-control-sm select2bs4 field_validation" width="100%" placeholder="<?=$this->lang->line('employee_position_id')?>" id="position_id" name="position_id">
            <option value="">Select</option>
            <?php
                $employee_position_id = $employee ? $employee->position_id : '';
                foreach ($positions as $value) {
                    $selected = ($value->position_id == $employee_position_id) ? ' selected' : '';
            ?>
                <option value="<?=$value->position_id;?>"<?=$selected;?>>
                    <?= $value->position_title;?>
                </option>
            <?php 
                }
            ?>
          </select> 
          <span class="input-group-append">
            <button type="button" class="btn btn-info btn-flat add_position_modal" data-tt="tooltip" title="Add Position" data-toggle="modal" data-target="#add_position_modal" data-tt="tooltip"><i class="fas fa-plus"></i>
            </button>
          </span>
          </div>
          <span class="error invalid-feedback" id="err_position_id"></span> 
        </div>
      </div>

      <div class="form-group row">
        <label for="bank_name" class="col-md-4"><?=$this->lang->line('employee_bank_name')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm" name="bank_name" id="bank_name" value="<?=($employee != null) ? $employee->bank_name : '' ?>" placeholder="<?=$this->lang->line('employee_bank_name')?>">  
           
        </div>
      </div>

      <div class="form-group row">
        <label for="account_no" class="col-md-4"><?=$this->lang->line('employee_account_no')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm" name="account_number" id="account_number" value="<?=($employee != null) ? $employee->account_number : '' ?>" placeholder="<?=$this->lang->line('employee_account_no')?>">  
           
        </div>
      </div>

      <div class="form-group row">
        <label for="branch" class="col-md-4"><?=$this->lang->line('employee_branch')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm" name="branch" id="branch" value="<?=($employee != null) ? $employee->branch : '' ?>" placeholder="<?=$this->lang->line('employee_branch')?>">  
           
        </div>
      </div>

      <div class="form-group row">
        <label for="ifsc_code" class="col-md-4"><?=$this->lang->line('employee_ifsc_code')?></label>
        <div class="col-md-8">
            <input type="text" class="form-control form-control-sm field_validation" name="ifsc_code" id="ifsc_code" value="<?=($employee != null) ? $employee->ifsc_code : '' ?>" placeholder="<?=$this->lang->line('employee_ifsc_code')?>">  
            <span id="err_ifsc_code" class="error invalid-feedback"></span>
        </div>
    </div>

      <div class="form-group row">
        <label for="salary" class="col-md-4 required"><?=$this->lang->line('employee_salary')?></label>
        <div class="col-md-8">
          <input type="number" class="form-control form-control-sm field_validation" name="salary" id="salary" value="<?=($employee != null) ? $employee->salary : '' ?>" placeholder="<?=$this->lang->line('employee_salary')?>">  
          <span class="error invalid-feedback" id="err_salary"></span> 
        </div>
      </div>

       <div class="form-group row">
        <label for="hire_date" class="col-md-4"><?=$this->lang->line('employee_hire_date')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm datepicker" name="hire_date" id="hire_date" value="<?=($employee != null && $employee->hire_date != '' && $employee->hire_date != '0000-00-00') ? date('d-m-Y', strtotime($employee->hire_date)) : '' ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('employee_hire_date')?>" autocomplete="off">  
        </div>
      </div>

      <div class="form-group row">
        <label for="termination_date" class="col-md-4"><?=$this->lang->line('employee_termination_date')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm datepicker" name="termination_date" id="termination_date" value="<?=($employee != null && $employee->termination_date != '' && $employee->termination_date != '0000-00-00') ? date('d-m-Y', strtotime($employee->termination_date)) : '' ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('employee_termination_date')?>" autocomplete="off">  
        </div>
      </div>

  </div>
  <div class="modal-footer">
    <input type="hidden" name="employee_id" id="employee_id" value="<?=($employee != null) ? $employee->employee_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addEmployeeSubmit" name="addEmployeeSubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>