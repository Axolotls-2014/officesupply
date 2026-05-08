<form  name="addWeekendForm" id="addWeekendForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($weekend == '')
          echo $this->lang->line('weekend_add');
        else
          echo $this->lang->line('weekend_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
     
      <div class="form-group row">
        <label for="weekend_date" class="col-md-4 required"><?=$this->lang->line('weekend_date')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm field_validation datepicker" name="weekend_date" id="weekend_date" value="<?=($weekend != null) ? date('d-m-Y',strtotime($weekend->weekend_date)) : '' ?>" style="cursor:pointer;" placeholder="<?=$this->lang->line('weekend_date')?>" autocomplete="off">  
          <span class="error invalid-feedback" id="err_weekend_date"><?= form_error('weekend_date') ?></span>
        </div>
      </div>

     

      <div class="form-group row">
        <label for="description" class="col-md-4"><?=$this->lang->line('weekend_description')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm" name="description" id="description" value="<?=($weekend != null) ? $weekend->description : '' ?>" placeholder="<?=$this->lang->line('weekend_description')?>">  
        </div>
      </div>

      
      
  </div>
  <div class="modal-footer">
    <input type="hidden" name="weekend_id" id="weekend_id" value="<?=($weekend != null) ? $weekend->weekend_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addWeekendSubmit" name="addWeekendSubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>