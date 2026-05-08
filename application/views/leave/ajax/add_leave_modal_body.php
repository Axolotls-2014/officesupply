<form  name="addLeaveForm" id="addLeaveForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($leave == '')
          echo $this->lang->line('leave_add');
        else
          echo $this->lang->line('leave_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
     

      <div class="form-group row">
        <label for="employee_id" class="col-md-4 required"><?=$this->lang->line('leave_employee_id')?></label>
        <div class="col-md-8">
          <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('leave_employee_id')?>" id="employee_id" name="employee_id">
            <option value="">Select</option>
            <?php
                $leave_employee_id = $leave ? $leave->employee_id : '';
                foreach ($employees as $value) {
                    $selected = ($value->employee_id == $leave_employee_id) ? ' selected' : '';
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
        <label for="date" class="col-md-4 required"><?=$this->lang->line('leave_date')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm datepicker field_validation" name="leave_date" id="leave_date" value="<?=($leave != null && $leave->leave_date != '' && $leave->leave_date != '0000-00-00') ? date('d-m-Y', strtotime($leave->leave_date)) : '' ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('leave_date')?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('leave_date')?>" autocomplete="off">  
          <span id="err_leave_date" class="error invalid-feedback"></span> 
        </div>
      </div>

     

      <div class="form-group row">
        <label for="leave_type" class="col-md-4 required"><?=$this->lang->line('leave_type')?></label>
        <div class="col-md-8">
            <?php 
                $leave_type_array = enum_select('hr_leaves', 'leave_type');
                $leave_type_labels = [
                    'full' => $this->lang->line('leave_type_full'),
                    'half' => $this->lang->line('leave_type_half'),
                    'quarter' => $this->lang->line('leave_type_quarter'), 
                ];

                for ($i = 0; $i < sizeof($leave_type_array); $i++) {
                    $checked = ($leave != null && $leave->leave_type == $leave_type_array[$i]) || ($leave == null && $leave_type_array[$i] == 'full') ? 'checked' : '';
                    $label = isset($leave_type_labels[$leave_type_array[$i]]) ? $leave_type_labels[$leave_type_array[$i]] : clean_e_val($leave_type_array[$i]);
            ?>
                    <div class="icheck-primary d-inline">
                        <input type="radio" id="leave_type_<?=$i?>" name="leave_type" value="<?=$leave_type_array[$i]?>" <?=$checked?>>
                        <label for="leave_type_<?=$i?>" class="normal_font"><?=$label?></label>
                    </div>
            <?php 
                } 
            ?>
            <span id="err_leave_type" class="error invalid-feedback"></span> 
        </div>
    </div>

    <div class="form-group row">
        <label for="status" class="col-md-4 required"><?=$this->lang->line('leave_status')?></label>
        <div class="col-md-8">
            <?php 
                $status_array = enum_select('hr_leaves', 'status');
                $status_labels = [
                    'pending' => $this->lang->line('leave_status_pending'), 
                    'approved' => $this->lang->line('leave_status_approved'),
                    'rejected' => $this->lang->line('leave_status_rejected'),
                   
                ];

                for ($i = 0; $i < sizeof($status_array); $i++) {
                    $checked = ($leave != null && $leave->status == $status_array[$i]) || ($leave == null && $status_array[$i] == 'pending') ? 'checked' : '';
                    $label = isset($status_labels[$status_array[$i]]) ? $status_labels[$status_array[$i]] : clean_e_val($status_array[$i]);
            ?>
                    <div class="icheck-primary d-inline">
                        <input type="radio" id="status_<?=$i?>" name="status" value="<?=$status_array[$i]?>" <?=$checked?>>
                        <label for="status_<?=$i?>" class="normal_font"><?=$label?></label>
                    </div>
            <?php 
                } 
            ?>
            <span id="err_status" class="error invalid-feedback"></span> 
        </div>
    </div>


   
     
  
  </div>
  <div class="modal-footer">
    <input type="hidden" name="leave_id" id="leave_id" value="<?=($leave != null) ? $leave->leave_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addLeaveSubmit" name="addLeaveSubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>