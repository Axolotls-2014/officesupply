<form  name="addAttendanceForm" id="addAttendanceForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($attendance == '')
          echo $this->lang->line('attendance_add');
        else
          echo $this->lang->line('attendance_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
     

      <div class="form-group row">
        <label for="employee_id" class="col-md-4 required"><?=$this->lang->line('attendance_employee_id')?></label>
        <div class="col-md-8">
          <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('attendance_employee_id')?>" id="employee_id" name="employee_id">
            <option value="">Select</option>
            <?php
                $attendance_employee_id = $attendance ? $attendance->employee_id : '';
                foreach ($employees as $value) {
                    $selected = ($value->employee_id == $attendance_employee_id) ? ' selected' : '';
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
        <label for="attendance_date" class="col-md-4 required"><?=$this->lang->line('attendance_date')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm datepicker field_validation" name="attendance_date" id="attendance_date" value="<?=($attendance != null && $attendance->attendance_date != '' && $attendance->attendance_date != '0000-00-00') ? date('d-m-Y', strtotime($attendance->attendance_date)) : '' ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('attendance_date')?>" autocomplete="off">  
          <span id="err_attendance_date" class="error invalid-feedback"></span> 
        </div>
      </div>

     

      <div class="form-group row">
        <label for="attendance_status" class="col-md-4"><?=$this->lang->line('attendance_status')?></label>
        <div class="col-md-8">
            <?php 
                $status_array = enum_select('hr_attendance', 'attendance_status');
                $status_labels = [
                    '1' => $this->lang->line('attendance_present'),
                    '0' => $this->lang->line('attendance_absent'),
                    '0.5' => $this->lang->line('attendance_half_leave'),
                    '0.75' => $this->lang->line('attendance_third_fourth_leave'),
                    // '0.75' => $this->lang->line('attendance_quarter_leave') // Adjust label as needed
                ];

                for ($i = 0; $i < sizeof($status_array); $i++) {
                    $checked = ($attendance != null && $attendance->attendance_status == $status_array[$i]) || ($attendance == null && $status_array[$i] == '1') ? 'checked' : '';
                    $label = isset($status_labels[$status_array[$i]]) ? $status_labels[$status_array[$i]] : clean_e_val($status_array[$i]);
            ?>
                    <div class="icheck-primary">
                        <input type="radio" id="status_<?=$i?>" name="attendance_status" value="<?=$status_array[$i]?>" <?=$checked?>>
                        <label for="status_<?=$i?>" class="normal_font"><?=$label?></label>
                    </div>
            <?php 
                } 
            ?>
        </div>
    </div>





      
      
  </div>
  <div class="modal-footer">
    <input type="hidden" name="attendance_id" id="attendance_id" value="<?=($attendance != null) ? $attendance->attendance_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addAttendanceSubmit" name="addAttendanceSubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>