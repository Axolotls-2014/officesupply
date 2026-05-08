<form  name="addadvanceSalaryForm" id="addadvanceSalaryForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($advance_salary == '')
          echo $this->lang->line('advance_salary_add');
        else
          echo $this->lang->line('advance_salary_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
     

      <div class="form-group row">
        <label for="employee_id" class="col-md-4 required"><?=$this->lang->line('advance_salary_employee_id')?></label>
        <div class="col-md-8">
          <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('advance_salary_employee_id')?>" id="employee_id" name="employee_id">
            <option value="">Select</option>
            <?php
                $advance_salary_employee_id = $advance_salary ? $advance_salary->employee_id : '';
                foreach ($employees as $value) {
                    $selected = ($value->employee_id == $advance_salary_employee_id) ? ' selected' : '';
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
        <label for="request_date" class="col-md-4 required"><?=$this->lang->line('advance_salary_request_date')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm datepicker field_validation" name="request_date" id="request_date" value="<?=($advance_salary != null && $advance_salary->request_date != '' && $advance_salary->request_date != '0000-00-00') ? date('d-m-Y', strtotime($advance_salary->request_date)) : date('d-m-Y') ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('request_date')?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('advance_salary_request_date')?>" autocomplete="off">
          <span id="err_request_date" class="error invalid-feedback"></span>   
        </div>
      </div>

      <div class="form-group row">
        <label for="amount" class="col-md-4 required"><?=$this->lang->line('advance_salary_amount')?></label>
        <div class="col-md-8">
          <input type="number" class="form-control form-control-sm field_validation" name="amount" id="amount" value="<?=($advance_salary != null) ? $advance_salary->amount : '' ?>" placeholder="<?=$this->lang->line('advance_salary_amount')?>">  
          <span class="error invalid-feedback" id="err_amount"></span>
        </div>
      </div>

      <div class="form-group row">
        <label for="status" class="col-md-4"><?=$this->lang->line('advance_salary_status')?></label>
        <div class="col-md-8">
            <?php 
                $status_array = enum_select('hr_advance_salaries', 'status');
                $status_labels = [
                    $this->lang->line('advance_salary_approved'),
                    $this->lang->line('advance_salary_rejected'),
                    $this->lang->line('advance_salary_pending'), 
                ];

                for ($i = 0; $i < sizeof($status_array); $i++) {
                    $checked = ($advance_salary != null && $advance_salary->status == $status_array[$i]) || ($advance_salary == null && $status_array[$i] == 'pending') ? 'checked' : '';
                    $label = isset($status_labels[$status_array[$i]]) ? $status_labels[$status_array[$i]] : clean_e_val($status_array[$i]);
            ?>
                    <div class="icheck-primary d-inline">
                        <input type="radio" id="status_<?=$i?>" name="status" value="<?=$status_array[$i]?>" <?=$checked?>>
                        <label for="status_<?=$i?>" class="normal_font"><?=$label?></label>
                    </div>
            <?php 
                } 
            ?>
        </div>
    </div>




   
     
  
  </div>
  <div class="modal-footer">
    <input type="hidden" name="advance_id" id="advance_id" value="<?=($advance_salary != null) ? $advance_salary->advance_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addadvanceSalarySubmit" name="addadvanceSalarySubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>