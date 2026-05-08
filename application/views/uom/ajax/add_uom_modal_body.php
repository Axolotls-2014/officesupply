  
<form role="form" method="post" name="addUomForm" id="addUomForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('uom_add');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

      
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("uom_uom")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="uom" value="<?=set_value("uom") ?>" class="form-control form-control-sm field_validation" id="uom" placeholder="<?=$this->lang->line("uom_uom")?>">
          <span id="err_uom" class="error invalid-feedback"></span>
        </div>
      </div> 

                        
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("uom_name")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="name" value="<?=set_value("name") ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line("uom_name")?>">
          <span id="err_name" class="error invalid-feedback"></span>
        </div>
      </div> 

                        
    
  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addUomSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
