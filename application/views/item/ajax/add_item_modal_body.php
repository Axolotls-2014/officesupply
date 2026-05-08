  
<form role="form" method="post" name="addItemForm" id="addItemForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('item_add');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("item_name")?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="item_name" value="<?=set_value("item_name") ?>" class="form-control form-control-sm field_validation" id="item_name" placeholder="<?=$this->lang->line("item_name")?>">
        <span id="err_item_name" class="error invalid-feedback"></span>
      </div>
    </div> 

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("item_description")?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="item_description" value="<?=set_value("item_description") ?>" class="form-control form-control-sm field_validation" id="item_description" placeholder="<?=$this->lang->line("item_description")?>">
        <span id="err_item_description" class="error invalid-feedback"></span>
      </div>
    </div>
    
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('item_tax')?><span class="text-danger">*</span></label>
      <div class="col-sm-8">
        <select class="form-control form-control-sm select2bs4 field_validation" name="tax_id" id="tax_id" placeholder="<?=$this->lang->line('item_tax')?>" style="width: 100% !important">
          <option value=""><?=$this->lang->line('select')?></option>
            <?php
              foreach ($tax as $value) {
            ?>
              <option value="<?=$value->id;?>" <?php echo set_select('item_tax', $value->id); ?>>
                <?= ucfirst($value->tax_name);?>
              </option>
            <?php 
              }
            ?>
          </select>
          <span id="err_account_group_id" class="error invalid-feedback"><?=form_error('account_group_id');?></span>
      </div>
    </div>

  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addItemSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
