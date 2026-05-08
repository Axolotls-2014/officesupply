

<form role="form" method="post" name="editProductForm" id="editProductForm" enctye="multipart/formdata">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_edit');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
    <h4>
      <?php echo $this->lang->line('lbl_cust_delete_modal');?>
    </h4>
  </div>
  <div class="modal-body">

     

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_cost")?>
      </label>
      <div class="col-sm-8">
        <input type="number" name="cost" value="<?=set_value("cost",$product->cost) ?>" class="form-control form-control-sm" step="0.01" id="cost" placeholder="<?=$this->lang->line("product_cost")?>">
        <span id="err_cost" class="error invalid-feedback"></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_selling_price")?>
      </label>
      <div class="col-sm-8">
        <input type="number" name="selling_price" value="<?=set_value("selling_price",$product->selling_price) ?>" step="0.01" class="form-control form-control-sm" id="selling_price" placeholder="<?=$this->lang->line("product_selling_price")?>">
        <span id="err_selling_price" class="error invalid-feedback"></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_price")?>
      </label>
      <div class="col-sm-8">
        <input type="number" name="price" value="<?=set_value("price",$product->price) ?>" class="form-control form-control-sm" step="0.01" id="price" placeholder="<?=$this->lang->line("product_price")?>">
        <span id="err_price" class="error invalid-feedback"></span>
      </div>
    </div>
    

  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="warehouse_product_id" value="<?=$product->id?>">
    <button type="submit" name="submit" id="editProductSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
