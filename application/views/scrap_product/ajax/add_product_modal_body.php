  
<form role="form" method="post" name="addProductForm" id="addProductForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_add');?></h4>
    <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button> -->

    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
    <button type="submit" name="submit" id="addProductSubmit" class="btn btn-primary" style="float: right"><?=$this->lang->line('submit')?></button>
  </div>
  <div class="modal-body">
    <div class="col-md-12">
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_name")?><span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <input type="text" name="name" value="<?=set_value("name") ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line("product_name")?>">
              <div class="text-danger" id="err_name"><?=form_error('name')?></div>
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
                <span id="err_product_category_id" class="error invalid-feedback"></span>
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
              <?=$this->lang->line("product_hsn")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="hsn" value="<?=set_value("hsn") ?>" class="form-control form-control-sm" id="hsn" placeholder="<?=$this->lang->line("product_hsn")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_cost")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="p_cost" value="0" class="form-control form-control-sm" id="p_cost" placeholder="<?=$this->lang->line("product_cost")?>" step="any">
            </div>
          </div>
          
          
          

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_price")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="p_price" value="0" class="form-control form-control-sm" id="p_price" placeholder="<?=$this->lang->line("product_price")?>" step="any">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_ptd")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="ptd" value="<?=set_value("ptd") ?>" class="form-control form-control-sm" id="ptd" placeholder="<?=$this->lang->line("product_ptd")?>" step="any">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_ptr")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="ptr" value="<?=set_value("ptr") ?>" class="form-control form-control-sm" id="ptr" placeholder="<?=$this->lang->line("product_ptr")?>" step="any">
            </div>
          </div>

          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_markup")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="markup" value="0" class="form-control form-control-sm" id="markup" placeholder="<?=$this->lang->line("product_markup")?>">
            </div>
          </div> 

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
              <select class="form-control form-control-sm select2bs4 field_validation" name="uom_id" id="uom_id" placeholder="<?=$this->lang->line('product_uom')?>" data-dropdown-parent="#add_product_modal" width="100%">
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
        </div>

        <div class="col-sm-4">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_salt")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="salt" value="<?=set_value("salt") ?>" class="form-control form-control-sm" id="salt" placeholder="<?=$this->lang->line("product_salt")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_short_name")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="short_name" value="<?=set_value("short_name") ?>" class="form-control form-control-sm" id="short_name" placeholder="<?=$this->lang->line("product_short_name")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_barcode")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="barcode" value="<?=set_value("barcode") ?>" class="form-control form-control-sm" id="barcode" placeholder="<?=$this->lang->line("product_barcode")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_mfg_name")?>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4" name="supplier_id" id="supplier_id" placeholder="<?=$this->lang->line('supplier_company_name')?>" width="100%">
                <option value="">Select Supplier</option>
                <?php 
                  foreach ($suppliers as $value) {
                ?>
                    <option value="<?=$value->id?>"><?=$value->company_name?></option>
                <?php
                  }
                ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_life_in_month")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="product_life_in_month" value="<?=set_value("product_life_in_month",0) ?>" class="form-control form-control-sm" id="product_life_in_month" placeholder="<?=$this->lang->line("product_life_in_month")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_division")?>
            </label>
            <div class="col-sm-8">
              <input list="division_datalist" name="division" value="<?=set_value("division") ?>" class="form-control form-control-sm" id="division" placeholder="<?=$this->lang->line("product_division")?>">
              <datalist id="division_datalist">
                <?php 
                  $division_array = column_unique_value('product','division');
                  for ($i=0; $i < sizeof($division_array); $i++) { 
                ?>    
                    <option value="<?=$division_array[$i]?>">
                <?php 
                  }
                ?>
              </datalist>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_packing")?>
            </label>
            <div class="col-sm-8">
              <input list="packing_datalist" name="packing" value="<?=set_value("packing") ?>" class="form-control form-control-sm" id="packing" placeholder="<?=$this->lang->line("product_packing")?>">
              <datalist id="packing_datalist">
                <?php 
                  $packing_array = column_unique_value('product','packing');
                  for ($i=0; $i < sizeof($packing_array); $i++) { 
                ?>    
                    <option value="<?=$packing_array[$i]?>">
                <?php 
                  }
                ?>
              </datalist>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_packing_type")?>
            </label>
            <div class="col-sm-8">
              <input list="packing_type_datalist" name="packing_type" value="<?=set_value("packing_type") ?>" class="form-control form-control-sm" id="packing_type" placeholder="<?=$this->lang->line("product_packing_type")?>">
              <datalist id="packing_type_datalist">
                <?php 
                  $packing_type_array = column_unique_value('product','packing_type');
                  for ($i=0; $i < sizeof($packing_type_array); $i++) { 
                ?>    
                    <option value="<?=$packing_type_array[$i]?>">
                <?php 
                  }
                ?>
              </datalist>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_item_type")?>
            </label>
            <div class="col-sm-8">
              <input list="item_type_datalist" name="item_type" value="<?=set_value("item_type") ?>" class="form-control form-control-sm" id="item_type" placeholder="<?=$this->lang->line("product_item_type")?>">
              <datalist id="item_type_datalist">
                <?php 
                  $item_type_array = column_unique_value('product','item_type');
                  for ($i=0; $i < sizeof($item_type_array); $i++) { 
                ?>    
                    <option value="<?=$item_type_array[$i]?>">
                <?php 
                  }
                ?>
              </datalist>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_sale_type")?>
            </label>
            <div class="col-sm-8">
              <select class="fom-control form-control-sm select2bs4" name="sale_type" id="sale_type" placeholder="<?=$this->lang->line('product_sale_type')?>" width="100%">
                <?php 
                  $sale_type_array = enum_select('product','sale_type');
                  for ($i=0; $i < sizeof($sale_type_array); $i++) { 
                ?>
                    <option value="<?=$sale_type_array[$i]?>"><?=clean_e_val($sale_type_array[$i])?></option>
                <?php
                  }
                ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_stock_expiry_warning_in_month")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="stock_expiry_warning_in_month" value="<?=set_value("stock_expiry_warning_in_month") ?>" class="form-control form-control-sm" id="stock_expiry_warning_in_month" placeholder="<?=$this->lang->line("product_stock_expiry_warning_in_month")?>">
            </div>
          </div>
        </div>

        <div class="col-sm-4">
          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("variant1")?>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4" name="variant1" id="variant1" placeholder="<?=$this->lang->line('variant1')?>" width="100%" data-dropdown-parent="#add_product_modal">
                  <option value=""><?=$this->lang->line('select')?></option>
                  <?php
                    foreach ($variants as $value) {
                  ?>
                    <option value="<?=$value->id;?>">
                      <?= $value->variant_name;?>
                    </option>
                  <?php 
                    }
                  ?>
                </select>
            </div>
          </div>

          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("variant1_option")?><br/>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4" name="variant1_option[]" id="variant1_option" placeholder="Variant options" width="100%" data-dropdown-parent="#add_product_modal" multiple="multiple">
              </select>
            </div>
          </div>

          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("variant2")?>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4" name="variant2" id="variant2" placeholder="<?=$this->lang->line('variant2')?>" width="100%" data-dropdown-parent="#add_product_modal">
                  <option value=""><?=$this->lang->line('select')?></option>
                  <?php
                    foreach ($variants as $value) {
                  ?>
                    <option value="<?=$value->id;?>">
                      <?= $value->variant_name;?>
                    </option>
                  <?php 
                    }
                  ?>
                </select>
            </div>
          </div>

          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("variant2_option")?><br/>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4" name="variant2_option[]" id="variant2_option" placeholder="Variant Option" width="100%" data-dropdown-parent="#add_product_modal" multiple="multiple">
              </select>
            </div>
          </div>

          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("variant3")?>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4" name="variant3" id="variant3" placeholder="<?=$this->lang->line('variant3')?>" width="100%" data-dropdown-parent="#add_product_modal">
                  <option value=""><?=$this->lang->line('select')?></option>
                  <?php
                    foreach ($variants as $value) {
                  ?>
                    <option value="<?=$value->id;?>">
                      <?= $value->variant_name;?>
                    </option>
                  <?php 
                    }
                  ?>
                </select>
            </div>
          </div>

          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("variant3_option")?><br/>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4" name="variant3_option[]" id="variant3_option" placeholder="Variant Option" width="100%" data-dropdown-parent="#add_product_modal" multiple="multiple">
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="no_of_tablets_per_strip" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_no_of_tablets_per_strip")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="no_of_tablets_per_strip" value="<?=set_value("no_of_tablets_per_strip",1) ?>" class="form-control form-control-sm" id="no_of_tablets_per_strip" placeholder="<?=$this->lang->line("product_no_of_tablets_per_strip")?>">
            </div>
          </div>

          <div class="form-group row ">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_no_of_strips_in_box")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="no_of_strips_in_box" value="<?=set_value("no_of_strips_in_box",1) ?>" class="form-control form-control-sm" id="no_of_strips_in_box" placeholder="<?=$this->lang->line("product_no_of_strips_in_box")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_no_of_box_in_case")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="no_of_box_in_case" value="<?=set_value("no_of_box_in_case",1) ?>" class="form-control form-control-sm" id="no_of_box_in_case" placeholder="<?=$this->lang->line("product_no_of_box_in_case")?>">
            </div>
          </div>

          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_no_of_qty_in_box")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="no_of_qty_in_box" value="<?=set_value("no_of_qty_in_box",1) ?>" class="form-control form-control-sm" id="no_of_qty_in_box" placeholder="<?=$this->lang->line("product_no_of_qty_in_box")?>" readonly="readonly">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_shipper_quantity")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="shipper_quantity" value="<?=set_value("shipper_quantity",1) ?>" class="form-control form-control-sm" id="shipper_quantity" placeholder="<?=$this->lang->line("product_shipper_quantity")?>" readonly>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_strip_cost")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="strip_cost" value="0" class="form-control form-control-sm" id="strip_cost" placeholder="<?=$this->lang->line("product_strip_cost")?>" step="any" readonly="readonly">
            </div>
          </div>


          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_box_weight")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="box_weight" value="<?=set_value("box_weight") ?>" class="form-control form-control-sm" id="box_weight" placeholder="<?=$this->lang->line("product_box_weight")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_box_height")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="box_height" value="<?=set_value("box_height") ?>" class="form-control form-control-sm" id="box_height" placeholder="<?=$this->lang->line("product_box_height")?>">
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <label for="inputEmail3" class="col-sm-1 col-form-label">
          <?=$this->lang->line("product_description")?>
        </label>
        <div class="col-sm-11">
          <textarea type="text" name="description" class="form-control form-control-sm" id="description" placeholder="<?=$this->lang->line("product_description")?>"><?=set_value("description")?></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
  </div>
</form>

  
