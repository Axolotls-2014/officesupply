<?php
  $sale = $this->utility_model->get_records_by_field('sale_items','product_id',$product->id,true,false);
  $quotation = $this->utility_model->get_records_by_field('quotation_items','product_id',$product->id,true,false);
  $proforma_invoice = $this->utility_model->get_records_by_field('proforma_invoice_items','product_id',$product->id,true,false);
  
 
?>

<form role="form" method="post" name="editProductForm" id="editProductForm" enctype="multipart/form-data">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_edit');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
      <span aria-hidden="true">&times;</span>
    </button>
  </div>
  <div class="modal-body">

    <!-- Manage Inventory -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_manage_inventory")?>
      </label>
      <div class="col-sm-8">
        <?php
          $product_id_exists = !empty($sale) || !empty($quotation) || !empty($proforma_invoice);
          $disabled_attribute = ($product_id_exists) ? 'disabled' : '';
        ?>
        <input type="radio" name="manage_inventory" value="<?=MANAGE_INVENTORY_YES?>" <?= ($product->manage_inventory == MANAGE_INVENTORY_YES) ? 'checked' : '' ?> <?= $disabled_attribute ?>> <?=strtoupper(MANAGE_INVENTORY_YES)?>
        <input type="radio" name="manage_inventory" value="<?=MANAGE_INVENTORY_NO?>" <?= ($product->manage_inventory == MANAGE_INVENTORY_NO) ? 'checked' : '' ?> <?= $disabled_attribute ?>> <?=strtoupper(MANAGE_INVENTORY_NO)?>
      </div>
    </div>
    
    <!-- Product Name -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_name")?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="name" id="name" value="<?=set_value("name", $product->name) ?>" class="form-control form-control-sm field_validation" placeholder="<?=$this->lang->line("product_name")?>" required>
        <span id="err_name" class="error invalid-feedback"></span>
      </div>
    </div>
               
    <!-- Product Code -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        Product Code<span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="product_code" id="product_code" value="<?=set_value("product_code", $product->product_code) ?>" class="form-control form-control-sm field_validation" placeholder="Product Code" required>
        <span id="err_product_code" class="error invalid-feedback"></span>
      </div>
    </div>
            
    <!-- Description -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_description")?>
      </label>
      <div class="col-sm-8">
        <input type="text" name="description" id="description" value="<?=set_value("description", $product->description) ?>" class="form-control form-control-sm" placeholder="<?=$this->lang->line("product_description")?>">
        <span id="err_description" class="error invalid-feedback"></span>
      </div>
    </div>

    <!-- Product Category -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_product_category_name")?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <?php if($this->permission_model->has_permission('add_product_category')): ?>
        <div class="input-group input-group-sm">
          <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" width="100%" data-dropdown-parent="#edit_product_modal" required>
            <?php foreach ($product_categories as $value): ?>
              <option value="<?=$value->id;?>" <?=($value->id == $product->product_category_id) ? 'selected' : '' ?>>
                <?= $value->name . ' GST - ' . $value->igst . '% ';?>
              </option>
            <?php endforeach; ?>
          </select>
          <span class="input-group-append">
            <button type="button" class="btn btn-info btn-flat add_product_category_modal" data-toggle="modal" data-target="#add_product_category_modal" data-tt="tooltip" title="<?=$this->lang->line('product_category_add')?>"><i class="fas fa-plus"></i></button>
          </span>
        </div>
        <?php else: ?>
        <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" width="100%" data-dropdown-parent="#edit_product_modal" required>
          <?php foreach ($product_categories as $value): ?>
            <option value="<?=$value->id;?>" <?=($value->id == $product->product_category_id) ? 'selected' : '' ?>>
              <?= $value->name . ' GST - ' . $value->igst . '% ';?>
            </option>
          <?php endforeach; ?>
        </select>
        <?php endif; ?>
        <span id="err_product_category_id" class="error invalid-feedback"></span>
      </div>
    </div> 

    <!-- HSN (Optional) -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_hsn")?>
      </label>
      <div class="col-sm-8">
        <input type="text" name="hsn" id="hsn" value="<?=set_value("hsn", $product->hsn) ?>" class="form-control form-control-sm" placeholder="<?=$this->lang->line("product_hsn")?>">
        <span id="err_hsn" class="error invalid-feedback"></span>
      </div>
    </div>

    <!-- Cost (Purchase Price) - REQUIRED -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_cost")?>
      </label>
      <div class="col-sm-8">
        <input type="number" name="cost" value="<?=set_value("cost", $product->cost) ?>" class="form-control form-control-sm field_validation" step="0.01" id="cost" placeholder="<?=$this->lang->line("product_cost")?>" required>
        <span id="err_cost" class="error invalid-feedback"></span>
      </div>
    </div>

    <!-- Selling Price -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_selling_price")?>
      </label>
      <div class="col-sm-8">
        <input type="number" name="selling_price" value="<?=set_value("selling_price", $product->selling_price) ?>" step="0.01" class="form-control form-control-sm" id="selling_price" placeholder="<?=$this->lang->line("product_selling_price")?>">
        <span id="err_selling_price" class="error invalid-feedback"></span>
      </div>
    </div>

    <!-- Price (MRP) -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_price")?>
      </label>
      <div class="col-sm-8">
        <input type="number" name="price" value="<?=set_value("price", $product->price) ?>" class="form-control form-control-sm" step="0.01" id="price" placeholder="<?=$this->lang->line("product_price")?>">
        <span id="err_price" class="error invalid-feedback"></span>
      </div>
    </div>

    <!-- Markup (Hidden) -->
    <div class="form-group row d-none">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_markup")?>
      </label>
      <div class="col-sm-8">
        <input type="text" name="markup" id="markup" value="<?=set_value("markup", $product->markup) ?>" class="form-control form-control-sm" placeholder="<?=$this->lang->line("product_markup")?>">
        <span id="err_markup" class="error invalid-feedback"></span>
      </div>
    </div>

    <!-- OPENING QUANTITY FIELD - FETCHED FROM product_view TABLE -->
<!--  <div class="form-group row">
    <label class="col-sm-4 col-form-label">
      Opening Quantity
    </label>
    <div class="col-sm-8">
      <input type="number" name="opening_quantity" id="opening_quantity" 
             value="<?=set_value("opening_quantity", isset($opening_quantity) ? $opening_quantity : 0) ?>" 
             class="form-control form-control-sm" step="0.01" min="0" 
             placeholder="Opening Quantity">
      <span id="err_opening_quantity" class="error invalid-feedback"></span>
      <small class="text-muted">Current stock quantity</small>
    </div>
  </div>-->

    <!-- Alert Quantity -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_alert_quantity")?>
      </label>
      <div class="col-sm-8">
        <input type="text" name="alert_quantity" id="alert_quantity" value="<?=set_value("alert_quantity", $product->alert_quantity) ?>" class="form-control form-control-sm" placeholder="<?=$this->lang->line("product_alert_quantity")?>">
        <span id="err_alert_quantity" class="error invalid-feedback"></span>
      </div>
    </div>

    <!-- UOM -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_uom")?><span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <select class="form-control form-control-sm select2bs4 field_validation" name="uom_id" id="uom_id" width="100%" data-dropdown-parent="#edit_product_modal" required>
          <option value=""><?=$this->lang->line('select')?></option>
          <?php foreach ($uoms as $value): ?>
            <option value="<?=$value->id;?>" <?=($value->id == $product->uom_id) ? 'selected' : '' ?>>
              <?= $value->name . ' (' . $value->uom . ')';?>
            </option>
          <?php endforeach; ?>
        </select>
        <span id="err_uom_id" class="error invalid-feedback"></span>
      </div>
    </div>

    <!-- Status -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_status")?>
      </label>
      <div class="col-sm-8">
        <select class="form-control form-control-sm select2bs4 field_validation" name="status" id="status" width="100%" data-dropdown-parent="#edit_product_modal">
          <option value="<?=PRODUCT_STATUS_ACTIVE?>" <?=($product->status == PRODUCT_STATUS_ACTIVE) ? 'selected' : '' ?>>
            <?=ucfirst(PRODUCT_STATUS_ACTIVE)?>
          </option>
          <option value="<?=PRODUCT_STATUS_INACTIVE?>" <?=($product->status == PRODUCT_STATUS_INACTIVE) ? 'selected' : '' ?>>
            <?=ucfirst(PRODUCT_STATUS_INACTIVE)?>
          </option>
        </select>
        <span id="err_status" class="error invalid-feedback"></span>
      </div>
    </div>

    <!-- Product Image -->
    <div class="form-group row">
      <label class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_image")?>
      </label>
      <div class="col-sm-8">
        <div class="custom-file">
          <input type="file" class="custom-file-input" id="upload_image" name="upload_image">
          <label class="custom-file-label" for="upload_image">Choose file</label>
        </div>
        
        <?php if($product->product_image != ''): ?>
          <img src="<?php echo base_url();?>assets/product_images/<?php echo $product->product_image;?>" height="100px" width="100px" style="padding-top: 5px; margin-top: 10px;" id="imagePreview">
          <button type="button" class="btn btn-sm btn-danger" id="deleteImage" style="float: right; margin-top: 45px;"><i class="fas fa-times-circle"></i></button>
          <input type="hidden" value="<?php echo $product->product_image;?>" name="product_image" id="product_image">
        <?php else: ?>
          <img src="" id="imagePreview" height="100px" width="100px" style="padding-top: 5px; display: none; margin-top: 10px;">
          <button type="button" class="btn btn-sm btn-danger" id="deleteImage" style="float: right; margin-top: 45px; display: none;"><i class="fas fa-times-circle"></i></button>
          <input type="hidden" value="" name="product_image" id="product_image">
        <?php endif; ?>
      </div>
    </div>

  </div>
  
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?=$product->id?>">
    <button type="submit" name="submit" id="editProductSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>

<script>
$(document).ready(function() {
    // Initialize select2 for dropdowns
    $('.select2bs4').select2({
        theme: 'bootstrap4',
        dropdownParent: $('#edit_product_modal')
    });
    
    // Opening quantity validation
    $('#opening_quantity').on('keyup change', function() {
        var qty = parseFloat($(this).val());
        if(isNaN(qty) || qty < 0) {
            $(this).addClass('is-invalid');
            $('#err_opening_quantity').html('Quantity must be a positive number');
            $('#err_opening_quantity').show();
        } else {
            $(this).removeClass('is-invalid');
            $('#err_opening_quantity').hide();
        }
    });
});
</script>