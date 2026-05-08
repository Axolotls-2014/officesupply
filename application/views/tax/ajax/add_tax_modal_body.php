  
<form role="form" method="post" name="addTaxForm" id="addTaxForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('tax_add');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

      
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("tax_name")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="tax_name" value="<?=set_value("tax_name") ?>" class="form-control form-control-sm field_validation" id="tax_name" placeholder="<?=$this->lang->line("tax_name")?>">
          <span id="err_tax_name" class="error invalid-feedback"></span>
        </div>
      </div> 

                        
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("tax_sgst")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="sgst" value="<?=set_value("sgst") ?>" class="form-control form-control-sm field_validation" id="sgst" placeholder="<?=$this->lang->line("tax_sgst")?>">
          <span id="err_sgst" class="error invalid-feedback"></span>
        </div>
      </div> 

                        
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("tax_cgst")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="cgst" value="<?=set_value("cgst") ?>" class="form-control form-control-sm field_validation" id="cgst" placeholder="<?=$this->lang->line("tax_cgst")?>">
          <span id="err_cgst" class="error invalid-feedback"></span>
        </div>
      </div> 

                        
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("tax_igst")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="igst" value="<?=set_value("igst") ?>" class="form-control form-control-sm field_validation" id="igst" placeholder="<?=$this->lang->line("tax_igst")?>">
          <span id="err_igst" class="error invalid-feedback"></span>
        </div>
      </div> 

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("tax_status")?>
        </label>
        <div class="col-sm-8">
          <select class="form-control form-control-sm select2bs4 field_validation" name="status" id="status" width="100%">
            <option value="1"><?=$this->lang->line('tax_status_active')?></option>
            <option value="0"><?=$this->lang->line('tax_status_inactive')?></option>
          </select>
        </div>
      </div> 

  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addTaxSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
