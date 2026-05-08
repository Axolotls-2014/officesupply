<form  name="addHolidayForm" id="addHolidayForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($holiday == '')
          echo $this->lang->line('holiday_add');
        else
          echo $this->lang->line('holiday_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
     
      <div class="form-group row">
        <label for="holiday_date" class="col-md-4 required"><?=$this->lang->line('holiday_date')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm field_validation datepicker" name="holiday_date" id="holiday_date" value="<?=($holiday != null && $holiday->holiday_date != '' && $holiday->holiday_date != '0000-00-00') ? date('d-m-Y', strtotime($holiday->holiday_date)) : '' ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('holiday_date')?>" autocomplete = "off">
          <span class="error invalid-feedback" id="err_holiday_date"><?= form_error('holiday_date') ?></span>
        </div>
      </div>

     

      <div class="form-group row">
        <label for="holiday_name" class="col-md-4 required"><?=$this->lang->line('holiday_name')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm field_validation" name="holiday_name" id="holiday_name" value="<?=($holiday != null) ? $holiday->holiday_name : '' ?>" placeholder="<?=$this->lang->line('holiday_name')?>">
          <span class="error invalid-feedback" id="err_holiday_name"></span>  
        </div>
      </div>

      
      
  </div>
  <div class="modal-footer">
    <input type="hidden" name="holiday_id" id="holiday_id" value="<?=($holiday != null) ? $holiday->holiday_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addHolidaySubmit" name="addHolidaySubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>