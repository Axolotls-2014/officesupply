<form role="form" method="post" name="editStockForm" id="editStockForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('stock_edit');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  
  <div class="modal-body">
    <input type="hidden" name="id" value="<?=$stock->id?>">
    
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("stock_warehouse_id")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <select class="form-control form-control-sm select2bs4" name="warehouse_id" id="warehouse_id" width="100%" disabled>
          <option value="">Select Branch</option>
          <?php
            foreach ($warehouses as $value) {
          ?>
            <option value="<?=$value->id;?>"
              data-warehouse_name="<?=$value->name?>"
              <?php if($stock->warehouse_id == $value->id) echo ' selected'; ?>>
              <?= $value->name;?>
            </option>
          <?php 
            }
          ?>
        </select>
        <input type="hidden" name="warehouse_id" value="<?=$stock->warehouse_id?>">
        <span id="err_warehouse_id" class="error invalid-feedback"></span>
      </div>
    </div>
    
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        Branch Name<span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="warehouse_name" value="<?=$stock->warehouse_name?>" class="form-control form-control-sm" id="warehouse_name" readonly>
        <span id="err_warehouse_name" class="error invalid-feedback"></span>
      </div>
    </div> 

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("stock_product_id")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <select class="form-control form-control-sm select2bs4" name="product_id" id="product_id" width="100%" disabled>
          <option value="">Select Product</option>
          <?php
            foreach ($products as $value) {
              $product = $this->product_model->get_single_record($value->id);
              $product_category = $this->product_category_model->get_single_record($product->product_category_id);
          ?>
            <option value="<?=$value->id;?>"
              data-product_name="<?=$product->name?>"
              data-product_uom="<?=$product->uom?>"
              data-product_cost="<?=$product->cost?>"
              data-product_price="<?=$product->price?>"
              data-selling_price="<?=$product->selling_price?>"
              <?php if($stock->product_id == $value->id) echo ' selected'; ?>>
              <?= $product->name.' - '.$product_category->name;?>
            </option>
          <?php 
            }
          ?>
        </select>
        <input type="hidden" name="product_id" value="<?=$stock->product_id?>">
        <span id="err_product_id" class="error invalid-feedback"></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("stock_product_name")?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="product_name" value="<?=$stock->product_name?>" class="form-control form-control-sm" id="product_name" readonly>
        <span id="err_product_name" class="error invalid-feedback"></span>
      </div>
    </div> 

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("stock_product_uom")?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="product_uom" value="<?=$stock->product_uom?>" class="form-control form-control-sm field_validation" id="product_uom" placeholder="<?=$this->lang->line("stock_product_uom")?>">
        <span id="err_product_uom" class="error invalid-feedback"></span>
      </div>
    </div> 
                    
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("stock_product_quantity")?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="number" name="product_quantity" value="<?=$stock->quantity?>" class="form-control form-control-sm field_validation" id="product_quantity" placeholder="<?=$this->lang->line("stock_product_quantity")?>" step="0.01">
        <span id="err_product_quantity" class="error invalid-feedback"></span>
      </div>
    </div> 

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_batch_no")?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="batch_no" value="<?=$stock->batch_no?>" class="form-control form-control-sm field_validation" id="batch_no" placeholder="<?=$this->lang->line("product_batch_no")?>">
        <span id="err_batch_no" class="error invalid-feedback"></span>
      </div>
    </div> 

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("stock_product_cost").' ('.$this->session->userdata('currency_symbol').')'?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="product_cost" value="<?=$stock->product_cost?>" class="form-control form-control-sm field_validation" id="product_cost" placeholder="<?=$this->lang->line("stock_product_cost")?>">
        <span id="err_product_cost" class="error invalid-feedback"></span>
      </div>
    </div> 

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("stock_product_price").' ('.$this->session->userdata('currency_symbol').')'?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="selling_price" value="<?=$stock->selling_price?>" class="form-control form-control-sm field_validation" id="selling_price" placeholder="<?=$this->lang->line("stock_product_price")?>">
        <span id="err_selling_price" class="error invalid-feedback"></span>
      </div>
    </div>
                  
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_price").' ('.$this->session->userdata('currency_symbol').')'?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="product_price" value="<?=$stock->product_price?>" class="form-control form-control-sm field_validation" id="product_price" placeholder="<?=$this->lang->line("product_price")?>">
        <span id="err_product_price" class="error invalid-feedback"></span>
      </div>
    </div>

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("stock_product_entry_type")?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <select name="entry_type" id="entry_type" class="form-control form-control-sm field_validation">
          <option value="<?=WAREHOUSE_STOCK_IN?>" <?=($stock->entry_type == WAREHOUSE_STOCK_IN) ? 'selected' : ''?>>Stock In</option>
          <option value="<?=WAREHOUSE_STOCK_OUT?>" <?=($stock->entry_type == WAREHOUSE_STOCK_OUT) ? 'selected' : ''?>>Stock Out</option>
        </select>
        <span id="err_entry_type" class="error invalid-feedback"></span>
      </div>
    </div>
  </div>
  
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="editStockSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>