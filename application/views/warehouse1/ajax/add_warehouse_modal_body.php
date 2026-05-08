  
<form role="form" method="post" name="addWarehouseForm" id="addWarehouseForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('warehouse_add');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

      
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("warehouse_code")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="code" value="<?=set_value("code") ?>" class="form-control form-control-sm field_validation" id="code" placeholder="<?=$this->lang->line("warehouse_code")?>">
          <span id="err_code" class="error invalid-feedback"></span>
        </div>
      </div> 

                        
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("warehouse_name")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="name" value="<?=set_value("name") ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line("warehouse_name")?>">
          <span id="err_name" class="error invalid-feedback"></span>
        </div>
      </div> 

                        
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("warehouse_description")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="description" value="<?=set_value("description") ?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line("warehouse_description")?>">
          <span id="err_description" class="error invalid-feedback"></span>
        </div>
      </div> 

                        
    
  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addWarehouseSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
