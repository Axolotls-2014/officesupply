<?php
  $sale             = $this->utility_model->get_records_by_field('sale_items','product_id',$product->id,true,false);
  $quotation        = $this->utility_model->get_records_by_field('quotation_items','product_id',$product->id,true,false);
  $proforma_invoice = $this->utility_model->get_records_by_field('proforma_invoice_items','product_id',$product->id,true,false);
?>

<form role="form" method="post" name="copyProductForm" id="copyProductForm" enctye="multipart/formdata">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_copy');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
    <h4>
      <?php echo $this->lang->line('lbl_cust_delete_modal');?>
    </h4>
  </div>
  <div class="modal-body">

    <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
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
    
     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_name")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="name" id="name" value="<?=set_value("name",$product->name) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("product_name")?>">
        <span id="err_name" class="error invalid-feedback"></span>
      </div>
    </div>
                     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_description")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="description" id="description" value="<?=set_value("description",$product->description) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("product_description")?>">
        <span id="err_description" class="error invalid-feedback"></span>
      </div>
    </div>

    <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_product_category_name")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <?php
            if($this->permission_model->has_permission('add_product_category'))
            { 
          ?>
          <div class="input-group input-group-sm">
            <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_product_category_name')?>" width="100%" data-dropdown-parent="#copy_product_modal">
              <?php
                foreach ($product_categories as $value) {
              ?>
                <option value="<?=$value->id;?>"
                  <?php 
                    if($value->id == $product->product_category_id)
                      echo ' selected';
                  ?>
                >
                  <?= $value->name.' GST - '.$value->igst.'% ';?>
                </option>
              <?php 
                }
              ?>
            </select>
            <span class="input-group-append">
              <button type="button" class="btn btn-info btn-flat add_product_category_modal" data-toggle="modal" data-target="#add_product_category_modal" data-tt="tooltip" title="<?=$this->lang->line('product_category_add')?>"><i class="fas fa-plus"></i></button>
            </span>
          </div>
          <?php
            }
            else
            {
          ?>
          <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_product_category_name')?>" width="100%" data-dropdown-parent="#copy_product_modal">
            <?php
              foreach ($product_categories as $value) {
            ?>
              <option value="<?=$value->id;?>"
                <?php 
                  if($value->id == $product->product_category_id)
                    echo ' selected';
                ?>
              >
                <?= $value->name.' GST - '.$value->igst.'% ';?>
              </option>
            <?php 
              }
            ?>
          </select>
          <?php
            } 
          ?>
          
          <span id="err_product_category_id" class="error invalid-feedback"><?=form_error('product_category_id');?></span>
        </div>
      </div> 

    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_hsn")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="hsn" id="hsn" value="<?=set_value("hsn",$product->hsn) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("product_hsn")?>">
        <span id="err_hsn" class="error invalid-feedback"></span>
      </div>
    </div>

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
        <?=$this->lang->line("product_price")?>
      </label>
      <div class="col-sm-8">
        <input type="number" name="price" value="<?=set_value("price",$product->price) ?>" class="form-control form-control-sm" step="0.01" id="price" placeholder="<?=$this->lang->line("product_price")?>">
        <span id="err_price" class="error invalid-feedback"></span>
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

                     
    <div class="form-group row d-none">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_markup")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="markup" id="markup" value="<?=set_value("markup",$product->markup) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("product_markup")?>">
        <span id="err_markup" class="error invalid-feedback"></span>
      </div>
    </div>
                     
    
                     
    <div class="form-group row">
      <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_alert_quantity")?>
        <span class="text-danger">*</span>
      </label>
      <div class="col-sm-8">
        <input type="text" name="alert_quantity" id="alert_quantity" value="<?=set_value("alert_quantity",$product->alert_quantity) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("product_alert_quantity")?>">
        <span id="err_alert_quantity" class="error invalid-feedback"></span>
      </div>
    </div>
                     
    <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_uom")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <select class="form-control form-control-sm select2bs4 field_validation" name="uom_id" id="uom_id" placeholder="<?=$this->lang->line('product_uom')?>" width="100%" data-dropdown-parent="#copy_product_modal">
            <option value=""><?=$this->lang->line('select')?></option>
              <?php
                foreach ($uoms as $value) {
              ?>
                <option value="<?=$value->id;?>" 
                  <?php 
                    if(isset($uom_id))
                    {
                      if($uom_id == $value->id)
                      {
                        echo ' selected';
                      }
                    }
                    else
                    {
                      if($value->id == $product->uom_id)
                      {
                        echo ' selected';
                      }
                    }
                  ?>
                >
                  <?= $value->name.' ('.$value->uom.')';?>
                </option>
              <?php 
                }
              ?>
          </select>
          <span id="err_uom_id" class="error invalid-feedback"><?=form_error('uom_id')?></span>
        </div>
      </div>

                        
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_status")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <select class="form-control form-control-sm select2bs4 field_validation" name="status" id="status" placeholder="<?=$this->lang->line('product_status')?>" width="100%" data-dropdown-parent="#copy_product_modal">
            <option value="<?=PRODUCT_STATUS_ACTIVE?>"
              <?php 
                if($product->status == PRODUCT_STATUS_ACTIVE)
                  echo ' selected';
              ?>
            >
              <?=ucfirst(PRODUCT_STATUS_ACTIVE)?>
            </option>
            <option value="<?=PRODUCT_STATUS_INACTIVE?>"
              <?php 
                if($product->status == PRODUCT_STATUS_INACTIVE)
                  echo ' selected';
              ?>
            >
              <?=ucfirst(PRODUCT_STATUS_INACTIVE)?>
            </option>
            
          </select>
          <span id="err_status" class="error invalid-feedback"><?=form_error('status')?></span>
        </div>
      </div>

      <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_image")?>
            </label>
            <div class="col-sm-8">
              <div class="custom-file">
                <input type="file" class="custom-file-input" id="upload_image" name="upload_image">
                <label class="custom-file-label" for="product_image">Choose file</label>
              </div>
          

              <?php 
                if($product->product_image != '')
                {
              ?>
                  <img src="<?php echo base_url();?>assets/product_images/<?php echo $product->product_image;?>" height="100px" width="100px" style="padding-top: 5px;" id="imagePreview">
                  <button class="btn btn-sm btn-danger" id="deleteImage" style="float: right;margin-top: 35px;"><i class="fas fa-times-circle"></i></button>
                  <input type="hidden" value="<?php echo $product->product_image;?>" name="product_image" id="product_image">
              <?php
                }
                else
                {
              ?>
                  <img src="" id="imagePreview" height="100px" width="100px" style="padding-top: 5px;display: none;">
                  <button class="btn btn-sm btn-danger" id="deleteImage" style="float: right;margin-top: 35px;display: none;"><i class="fas fa-times-circle"></i></button>
                  <input type="hidden" value="" name="product_image" id="product_image1">

              <?php
                }
              ?>
            </div>
          </div>

        </div>
                    

  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <!-- <input type="hidden" name="id" value="<?=$product->id?>"> -->
    <button type="submit" name="submit" id="copyProductSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
