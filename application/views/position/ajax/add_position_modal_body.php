<form  name="addPositionForm" id="addPositionForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($position == '')
          echo $this->lang->line('position_add');
        else
          echo $this->lang->line('position_edit');
      ?>
    
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
      <div class="form-group row">
        <label for="position_title" class="col-md-4 required"><?=$this->lang->line('position_title')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm field_validation" name="position_title" id="position_title" value="<?=($position != null) ? $position->position_title : '' ?>" placeholder="<?=$this->lang->line('position_title')?>">  
          <span class="error text-danger" id="err_position_title"></span>
        </div>
      </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="position_id" id="position_id" value="<?=($position != null) ? $position->position_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addPositionSubmit" name="addPositionSubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>