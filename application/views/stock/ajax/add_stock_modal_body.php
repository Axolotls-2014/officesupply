  
<form role="form" method="post" name="addStockForm" id="addStockForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('stock_add');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  
<div class="modal-body">

  <div class="form-group row">
    <label for="inputEmail3" class="col-sm-4 col-form-label">
    <?=$this->lang->line("stock_warehouse_id")?>
      <span class="text-danger"></span>
    </label>
    <div class="col-sm-8">
      <select class="form-control form-control-sm select2bs4 " name="warehouse_id" id="warehouse_id" placeholder="<?=$this->lang->line("stock_warehouse_id")?>" width="100%">
        <option value="">Select Branch</option>
        <?php
          foreach ($warehouses as $value) {
        ?>
          <option value="<?=$value->id;?>"
                  data-warehouse_name="<?=$value->name?>"
            <?php 
              if(!empty($warehouse_product) &&  $value->id == $warehouse_product->warehouse_id)
                echo ' selected';
            ?>  
          >
            <?= $value->name;?>
          </option>
        <?php 
          }
        ?>
      </select>
      <span id="err_warehouse_id" class="error invalid-feedback"></span>
    </div>
    <!-- <div class="col-sm-8">
    <div class="form-group">
    
        
        <div class="input-group input-group-sm">
            <?php
            // Always set warehouse_id to 1
            $default_warehouse_id = 1;
            $warehouse_name = '';
            
            foreach ($warehouses as $value) {
                if ($value->id == $default_warehouse_id) {
                    $warehouse_name = $value->name;
                    break;
                }
            }
            ?>
            <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$default_warehouse_id;?>">
            <input type="text" class="form-control form-control-sm" value="<?=$warehouse_name;?>" readonly>
        </div>
        
        <span id="err_warehouse_id" class="error invalid-feedback"><?=form_error('warehouse_id');?></span>
    </div>
</div>-->
                    
  </div>
  
<div class="form-group row">
    <label for="inputEmail3" class="col-sm-4 col-form-label">
    Branch Name<span class="text-danger"></span>
    </label>
    <div class="col-sm-8">
      <input type="text" name="warehouse_name" value="<?=set_value("warehouse_name") ?>" class="form-control form-control-sm " id="warehouse_name" placeholder="<?=$this->lang->line("stock_warehouse_name")?>" readonly="readonly">
      <span id="err_warehouse_name" class="error invalid-feedback"></span>
    </div>
  </div>

  <div class="form-group row">
    <label for="inputEmail3" class="col-sm-4 col-form-label">
      <?=$this->lang->line("stock_product_id")?>
      <span class="text-danger"></span>
    </label>
    <div class="col-sm-8">
      <select class="form-control form-control-sm select2bs4 " name="product_id" id="product_id" placeholder="<?=$this->lang->line("stock_product_id")?>" width="100%">
        <option value="">Select Product</option>

        <?php
        foreach ($products as $value) {

          $product = $this->product_model->get_single_record($value->id);
          $product_category = $this->product_category_model->get_single_record($product->product_category_id);
          $uom = $this->uom_model->get_single_record($product->uom_id);
          $hsn = ($product->hsn != '')? ' - '.$product->hsn : '';

          $warehouse_products = $this->warehouse_products_model->get_records_by_product_id($product->id);

          $batch_no_array = array();

          foreach($warehouse_products as $warehouse_product)
          {
            $batch_no_array[] = $warehouse_product->batch_no;
          }

          $batch_no = '';

          if(!empty($batch_no_array))
          {
            $batch_no = implode(',', $batch_no_array);
          } 

          // if($value->delete_status == NOT_DELETED)
          // {

      ?>
        <option value="<?=$value->id;?>"
                data-product_name="<?=$product->name?>"
                data-batch_no="<?=$batch_no?>"
                data-product_uom="<?=($uom != null) ? $uom->uom : ''?>"
                data-product_uom_name="<?= ($uom != null) ? $uom->name : ''?>"
                data-product_uom_name="<?=$uom->name?>"
                data-product_cost="<?=$product->cost?>"
                data-product_price="<?=$product->price?>"
                data-selling_price="<?=$product->selling_price?>"
          <?php
            if(!empty($warehouse_product) && $value->id == $warehouse_product->product_id)
              echo ' selected';
          ?>
        >
        <?= $product->name.' - '.$product_category->name;?>
        </option>
      <?php
          // } 
        }
      ?>

     
      </select>
      <span id="err_product_id" class="error invalid-feedback"></span>
    </div>
  </div>

            
  <div class="form-group row">
    <label for="inputEmail3" class="col-sm-4 col-form-label">
      <?=$this->lang->line("stock_product_name")?><span class="text-danger"></span>
    </label>
    <div class="col-sm-8">
      <input type="text" name="product_name" value="<?=set_value("product_name") ?>" class="form-control form-control-sm " id="product_name" placeholder="<?=$this->lang->line("stock_product_name")?>" readonly="readonly">
      <span id="err_product_name" class="error invalid-feedback"></span>
    </div>
  </div> 

   


  <div class="form-group row">
    <label for="inputEmail3" class="col-sm-4 col-form-label">
      <?=$this->lang->line("stock_product_uom")?><span class="text-danger"></span>
    </label>
    <div class="col-sm-8">
      <input type="text" name="product_uom" value="<?=set_value("product_uom") ?>" class="form-control form-control-sm " id="product_uom" placeholder="<?=$this->lang->line("stock_product_uom")?>" readonly="readonly">
      <span id="err_product_uom" class="error invalid-feedback"></span>
    </div>
  </div> 
                    
  <div class="form-group row">
    <label for="inputEmail3" class="col-sm-4 col-form-label">
      <?=$this->lang->line("stock_product_quantity")?><span class="text-danger"></span>
    </label>
    <div class="col-sm-8">
      <input type="number" name="product_quantity" value="<?=set_value("product_quantity") ?>" class="form-control form-control-sm " id="product_quantity" placeholder="<?=$this->lang->line("stock_product_quantity")?>" step="0.01">
      <span id="err_product_quantity" class="error invalid-feedback"></span>
    </div>
  </div> 

  <div class="form-group row">
    <label for="inputEmail3" class="col-sm-4 col-form-label">
      <?=$this->lang->line("product_batch_no")?><span class="text-danger"></span>
    </label>
    <div class="col-sm-8">
      <input list="batchNumbersDatalist" type="text" name="batch_no" value="<?=set_value("batch_no") ?>" class="form-control form-control-sm " id="batch_no" placeholder="<?=$this->lang->line("product_batch_no")?>" autocomplete="off">
      <span id="err_batch_no" class="error invalid-feedback"></span>
      <datalist id="batchNumbersDatalist"></datalist>
    </div>
  </div> 
 

  <div class="form-group row">
    <label for="inputEmail3" class="col-sm-4 col-form-label">
      <?=$this->lang->line("stock_product_cost").' ('.$this->session->userdata('currency_symbol').')'?><span class="text-danger"></span>
    </label>
    <div class="col-sm-8">
      <input type="text" name="product_cost" value="<?=set_value("product_cost") ?>" class="form-control form-control-sm " id="product_cost" placeholder="<?=$this->lang->line("stock_product_cost")?>">
      <span id="err_product_cost" class="error invalid-feedback"></span>
    </div>
  </div> 

  <div class="form-group row">
  <label for="inputEmail3" class="col-sm-4 col-form-label">
    <?=$this->lang->line("stock_product_price").' ('.$this->session->userdata('currency_symbol').')'?><span class="text-danger"></span>
  </label>
  <div class="col-sm-8">
    <input type="text" name="selling_price" value="<?=set_value("selling_price") ?>" class="form-control form-control-sm " id="selling_price" placeholder="<?=$this->lang->line("stock_product_price")?>">
    <span id="err_selling_price" class="error invalid-feedback"></span>
  </div>
  </div>
                  
  <div class="form-group row">
    <label for="inputEmail3" class="col-sm-4 col-form-label">
      <?=$this->lang->line("product_price").' ('.$this->session->userdata('currency_symbol').')'?><span class="text-danger"></span>
    </label>
    <div class="col-sm-8">
      <input type="text" name="product_price" value="<?=set_value("product_price") ?>" class="form-control form-control-sm " id="product_price" placeholder="<?=$this->lang->line("product_price")?>">
      <span id="err_product_price" class="error invalid-feedback"></span>
    </div>
  </div>
      
  </div>
  <div class="modal-footer">
    <input type="hidden" name="entry_type" id="entry_type" value=""> 
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addStockSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
