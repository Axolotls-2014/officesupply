  
<form role="form" method="post" name="editUomForm" id="editUomForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('uom_edit');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
    <h4>
      <?php echo $this->lang->line('lbl_cust_delete_modal');?>
    </h4>
  </div>
  <div class="modal-body">
    
     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("uom_uom")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="uom" id="uom" value="<?=set_value("uom",$uom->uom) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("uom_uom")?>">
        <span id="err_uom" class="error invalid-feedback"></span>
      </div>
    </div>
                     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("uom_name")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="name" id="name" value="<?=set_value("name",$uom->name) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("uom_name")?>">
        <span id="err_name" class="error invalid-feedback"></span>
      </div>
    </div>
                    

  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?=$uom->id?>">
    <button type="submit" name="submit" id="editUomSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
