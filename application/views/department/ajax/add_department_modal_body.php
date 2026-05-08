<form  name="addDepartmentForm" id="addDepartmentForm">
  <div class="modal-header">
    <h5 class="modal-title" id="exampleModalLabel">
      <?php 
        if($department == '')
          echo $this->lang->line('department_add');
        else
          echo $this->lang->line('department_edit');
      ?>
    </h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">
      <div class="form-group row">
        <label for="department_name" class="col-md-4 required"><?=$this->lang->line('department_name')?></label>
        <div class="col-md-8">
          <input type="text" class="form-control form-control-sm field_validation" name="department_name" id="department_name" value="<?=($department != null) ? $department->department_name : '' ?>" placeholder="<?=$this->lang->line('department_name')?>">  
          <span class="error text-danger" id="err_department_name"></span>
        </div>
      </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="department_id" id="department_id" value="<?=($department != null) ? $department->department_id : '' ?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" class="btn btn-sm btn-primary" id="addDepartmentSubmit" name="addDepartmentSubmit">Submit</button>
    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Close</button>
  </div>
</form>