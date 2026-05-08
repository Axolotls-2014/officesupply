  
<form role="form" method="post" name="addProduct_categoryForm" id="addProduct_categoryForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_category_add');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

      
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_category_name")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="name" value="<?=set_value("name") ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line("product_category_name")?>">
          <span id="err_name" class="error invalid-feedback"></span>
        </div>
      </div> 

                        
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_category_description")?>
        </label>
        <div class="col-sm-8">
          <input type="text" name="description" value="<?=set_value("description") ?>" class="form-control form-control-sm" id="description" placeholder="<?=$this->lang->line("product_category_description")?>">
        </div>
      </div>

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_category_tax_name")?>
          <span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <?php
            if($this->permission_model->has_permission('add_tax'))
            { 
          ?>
          <div class="input-group input-group-sm">
            <select class="form-control form-control-sm select2bs4 field_validation" name="tax_id" id="tax_id" placeholder="<?=$this->lang->line('product_category_tax_name')?>" width="100%" data-dropdown-parent="#add_product_category_modal">
            <?php
              foreach ($tax as $value) {
            ?>
              <option value="<?=$value->id;?>" <?php echo set_select('tax_id', $value->id); ?>>
                <?=$value->tax_name.' ( I : '.$value->igst.' | C : '.$value->cgst.' | S : '.$value->sgst.' )'?>
              </option>
            <?php 
              }
            ?>
          </select>
            <span class="input-group-append">
              <button type="button" class="btn btn-info btn-flat add_tax_modal" data-toggle="modal" data-target="#add_tax_modal" data-tt="tooltip" title="<?=$this->lang->line('tax_add')?>"><i class="fas fa-plus"></i></button>
            </span>
          </div>
          <?php
            }
            else
            {
          ?>
          <select class="form-control form-control-sm select2bs4 field_validation" name="tax_id" id="tax_id" placeholder="<?=$this->lang->line('product_category_tax_name')?>" width="100%">
            <?php
              foreach ($tax as $value) {
            ?>
              <option value="<?=$value->id;?>" <?php echo set_select('tax_id', $value->id); ?>>
                <?=$value->tax_name.' ( I : '.$value->igst.' | C : '.$value->cgst.' | S : '.$value->sgst.' )'?>
              </option>
            <?php 
              }
            ?>
          </select>
          <?php
            } 
          ?>
          <span id="err_tax_id" class="error invalid-feedback"></span>
        </div>
      </div> 

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_category_tax_type")?>
        </label>
        <div class="col-sm-8">
          <div class="custom-control custom-checkbox">
            <input class="custom-control-input" name="tax_type" type="checkbox" id="tax_type" value="1">
            <label for="tax_type" class="custom-control-label"></label>
          </div>
        </div>
      </div>

  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addProduct_categorySubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
