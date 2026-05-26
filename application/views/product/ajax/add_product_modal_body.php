
<form role="form" method="post" name="addProductForm" id="addProductForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_add');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_manage_inventory")?>
        </label>
        <div class="col-sm-8">
          <input type="radio" name="manage_inventory" value="<?=MANAGE_INVENTORY_YES?>" checked> <?=strtoupper(MANAGE_INVENTORY_YES)?>
          <input type="radio" name="manage_inventory" value="<?=MANAGE_INVENTORY_NO?>"> <?=strtoupper(MANAGE_INVENTORY_NO)?>
        </div>
      </div>

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_name")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="name" value="<?=set_value("name") ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line("product_name")?>">
          <span id="err_name" class="error invalid-feedback"></span>
        </div>
      </div> 

     <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
        Product Code<span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="product_code" value="<?=set_value("product_code") ?>" class="form-control form-control-sm field_validation" id="product_code" placeholder="Product Code" required>
        </div>
      </div> 
                        
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_description")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="description" value="<?=set_value("description") ?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line("product_description")?>">
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
            <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_product_category_name')?>" width="100%" data-dropdown-parent="#add_product_modal">
              <option value=""><?=$this->lang->line('select')?></option>
              <?php
                foreach ($product_categories as $value) {
              ?>
                <option value="<?=$value->id;?>">
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
          <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_product_category_name')?>" width="100%" data-dropdown-parent="#add_product_modal">
            <?php
              foreach ($product_categories as $value) {
            ?>
              <option value="<?=$value->id;?>">
                <?= $value->name.' GST - '.$value->igst.'% ';?>
              </option>
            <?php 
              }
            ?>
          </select>
          <?php
            } 
          ?>
          <span id="err_product_category_id" class="error invalid-feedback"></span>
        </div>
      </div> 

     <div class="form-group row">
    <label for="inputEmail3" class="col-sm-4 col-form-label">
        <?=$this->lang->line("product_hsn")?><span class="text-danger">*</span>
    </label>
    <div class="col-sm-8">
        <input type="text" name="hsn" value="<?=set_value("hsn") ?>" 
               class="form-control form-control-sm field_validation" 
               id="hsn" placeholder="Enter HSN code">
        <span id="err_hsn" class="error invalid-feedback"><?=form_error('hsn')?></span>
    </div>
</div>
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_cost")?>
        </label>
        <div class="col-sm-8">
          <input type="number" name="cost" value="<?=set_value("cost") ?>" class="form-control form-control-sm" step="0.01" id="cost" placeholder="<?=$this->lang->line("product_cost")?>">
          <span id="err_cost" class="error invalid-feedback"></span>
        </div>
      </div>

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_selling_price")?>
        </label>
        <div class="col-sm-8">
          <input type="number" name="selling_price" value="<?=set_value("selling_price") ?>" step="0.01" class="form-control form-control-sm" id="selling_price" placeholder="<?=$this->lang->line("product_selling_price")?>">
          <span id="err_selling_price" class="error invalid-feedback"></span>
        </div>
      </div>

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_price")?>
        </label>
        <div class="col-sm-8">
          <input type="number" name="price" value="<?=set_value("price") ?>" class="form-control form-control-sm" step="0.01" id="price" placeholder="<?=$this->lang->line("product_price")?>">
          <span id="err_price" class="error invalid-feedback"></span>
        </div>
      </div>

      

      <div class="form-group row d-none">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_markup")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="markup" value="<?=set_value("markup",0) ?>" class="form-control form-control-sm field_validation" id="markup" placeholder="<?=$this->lang->line("product_markup")?>">
          <span id="err_markup" class="error invalid-feedback"></span>
        </div>
      </div> 
     <!-- <div class="form-group row">
    <label for="inputEmail3" class="col-sm-4 col-form-label">
        Opening Quantity
    </label>
    <div class="col-sm-8">
        <input type="number" name="opening_quantity" value="<?=set_value("opening_quantity", 0) ?>" 
               class="form-control form-control-sm" 
               id="opening_quantity" 
               placeholder="Opening Quantity" 
               step="0.01" 
               min="0">
        <span id="err_opening_quantity" class="error invalid-feedback"></span>
        <small class="text-muted">Initial stock quantity (will create stock entry if > 0)</small>
    </div>
</div>-->


      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_alert_quantity")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <input type="text" name="alert_quantity" value="<?=set_value("alert_quantity",1) ?>" class="form-control form-control-sm field_validation" id="alert_quantity" placeholder="<?=$this->lang->line("product_alert_quantity")?>">
          <span id="err_alert_quantity" class="error invalid-feedback"></span>
        </div>
      </div> 
      

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_uom")?><span class="text-danger">*</span>
        </label>
        <div class="col-sm-8">
          <select class="form-control form-control-sm select2bs4 field_validation" name="uom_id" id="uom_id" placeholder="<?=$this->lang->line('product_uom')?>" width="100%">
            <option value=""><?=$this->lang->line('select')?></option>
            <?php
              foreach ($uoms as $value) {
            ?>
              <option value="<?=$value->id;?>" <?php echo set_select('uom_id', $value->id); ?>>
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
          <select class="form-control form-control-sm select2bs4 field_validation" name="status" id="status" placeholder="<?=$this->lang->line('product_status')?>" width="100%">
            <option value="<?=PRODUCT_STATUS_ACTIVE?>">
              <?=ucfirst(PRODUCT_STATUS_ACTIVE)?>
            </option>
            <option value="<?=PRODUCT_STATUS_INACTIVE?>">
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
          <div id="message"></div>
          <div>
            <img src="" height="100px" width="100px" id="displayImage" style="padding-top: 5px;display: none;">
          </div>
          <button class="btn btn-sm btn-danger" id ="deleteImageButton" style="display: none;float: right;margin-top: -64px;"><i class="fas fa-times-circle"></i></button>
          <input type="hidden" value="" name="product_image" id="product_image">

        </div>
      </div>

      

    </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="addProductSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
