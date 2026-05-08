  
<form role="form" method="post" name="editProduct_categoryForm" id="editProduct_categoryForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_category_edit');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
    <h4>
      <?php echo $this->lang->line('lbl_cust_delete_modal');?>
    </h4>
  </div>
  <div class="modal-body">
    
     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_category_name")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="name" id="name" value="<?=set_value("name",$product_category->name) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("product_category_name")?>">
        <span id="err_name" class="error invalid-feedback"></span>
      </div>
    </div>
                     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_category_description")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="description" id="description" value="<?=set_value("description",$product_category->description) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("product_category_description")?>">
        <span id="err_description" class="error invalid-feedback"></span>
      </div>
    </div>
                    

  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?=$product_category->id?>">
    <button type="submit" name="submit" id="editProduct_categorySubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
