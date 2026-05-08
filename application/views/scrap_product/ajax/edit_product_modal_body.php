  
<form role="form" method="post" name="editProductForm" id="editProductForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_edit');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
    <h4>
      <?php echo $this->lang->line('lbl_cust_delete_modal');?>
    </h4>
  </div>
  <div class="modal-body">

    <div class="col-md-12">
      <div class="row">
        <div class="col-sm-4">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_name")?>
              <span class="text-danger">*</span>
            </label>
            <div class="col-sm-8">
              <input type="text" name="name" id="name" value="<?=set_value("name",$product->name) ?>" class="form-control form-control-sm field_validation" id="client_contact_person_name" placeholder="<?=$this->lang->line("product_name")?>">
              <span id="err_name" class="error invalid-feedback"><?=form_error('name')?></span>
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
                  <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_product_category_name')?>" width="100%">
                    <?php
                      foreach ($product_categories as $value) {
                    ?>
                      <option value="<?=$value->id;?>"
                        <?php 
                          if($value->id == $product->product_category_id)
                            echo ' selected';
                        ?>
                      >
                        <?= $value->name;?>
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
                <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_product_category_name')?>" width="100%">
                  <?php
                    foreach ($product_categories as $value) {
                  ?>
                    <option value="<?=$value->id;?>"
                      <?php 
                        if($value->id == $product->product_category_id)
                          echo ' selected';
                      ?>
                    >
                      <?= $value->name;?>
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
            </label>
            <div class="col-sm-8">
              <input type="text" name="hsn" id="hsn" value="<?=set_value("hsn",$product->hsn) ?>" class="form-control form-control-sm" id="client_contact_person_name" placeholder="<?=$this->lang->line("product_hsn")?>">
            </div>
          </div>


          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_cost")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="p_cost" value="<?=($product != null) ? $product->p_cost : '0' ?>" class="form-control form-control-sm" id="p_cost" placeholder="<?=$this->lang->line("product_cost")?>" step="any">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_price")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="p_price" value="<?=($product != null) ? $product->p_price : '0' ?>" class="form-control form-control-sm" id="p_price" placeholder="<?=$this->lang->line("product_price")?>" step="any">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_ptd")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="ptd" value="<?=set_value("ptd",$product->ptd) ?>" class="form-control form-control-sm" id="ptd" placeholder="<?=$this->lang->line("product_ptd")?>" step="any">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_ptr")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="ptr" value="<?=set_value("ptr",$product->ptr) ?>" class="form-control form-control-sm" id="ptr" placeholder="<?=$this->lang->line("product_ptr")?>" step="any">
            </div>
          </div>
                     
          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_markup")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="markup" id="markup" value="<?=set_value("markup",$product->markup) ?>" class="form-control form-control-sm" id="client_contact_person_name" placeholder="<?=$this->lang->line("product_markup")?>">
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
              <select class="form-control form-control-sm select2bs4 field_validation" name="uom_id" id="uom_id" placeholder="<?=$this->lang->line('product_uom')?>" width="100%">
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
                <select class="form-control form-control-sm select2bs4 field_validation" name="status" id="status" placeholder="<?=$this->lang->line('product_status')?>" width="100%">
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
        </div>
        <div class="col-sm-4">
          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_short_name")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="short_name" value="<?=set_value("short_name",$product->short_name) ?>" class="form-control form-control-sm" id="short_name" placeholder="<?=$this->lang->line("product_short_name")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_barcode")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="barcode" value="<?=set_value("barcode",$product->barcode) ?>" class="form-control form-control-sm" id="barcode" placeholder="<?=$this->lang->line("product_barcode")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_mfg_name")?>
            </label>
            <div class="col-sm-8">
              <input list="supplier_datalist" name="supplier_id" value="<?=set_value("supplier_id",$product->supplier_id) ?>" class="form-control form-control-sm" id="supplier_id" placeholder="<?=$this->lang->line("product_supplier_id")?>">
              <datalist id="supplier_datalist">
                <?php 
                  $supplier_id_array = column_unique_value('product','supplier_id');
                  for ($i=0; $i < sizeof($supplier_id_array); $i++) { 
                ?>    
                    <option value="<?=$supplier_id_array[$i]?>">
                <?php 
                  }
                ?>
              </datalist>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_life_in_month")?>
            </label>
            <div class="col-sm-8">
              <input type="text" name="product_life_in_month" value="<?=set_value("product_life_in_month",$product->product_life_in_month) ?>" class="form-control form-control-sm" id="product_life_in_month" placeholder="<?=$this->lang->line("product_life_in_month")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_division")?>
            </label>
            <div class="col-sm-8">
              <input list="division_datalist" name="division" value="<?=set_value("division",$product->division) ?>" class="form-control form-control-sm" id="division" placeholder="<?=$this->lang->line("product_division")?>">
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
              <input list="packing_datalist" name="packing" value="<?=set_value("packing",$product->packing) ?>" class="form-control form-control-sm" id="packing" placeholder="<?=$this->lang->line("product_packing")?>">
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
              <input list="packing_type_datalist" name="packing_type" value="<?=set_value("packing_type",$product->packing_type) ?>" class="form-control form-control-sm" id="packing_type" placeholder="<?=$this->lang->line("product_packing_type")?>">
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
              <input list="item_type_datalist" name="item_type" value="<?=set_value("item_type",$product->item_type) ?>" class="form-control form-control-sm" id="item_type" placeholder="<?=$this->lang->line("product_item_type")?>">
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
              <input type="text" name="sale_type" value="<?=set_value("sale_type") ?>" class="form-control form-control-sm" id="sale_type" placeholder="<?=$this->lang->line("product_sale_type")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_stock_expiry_warning_in_month")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="stock_expiry_warning_in_month" value="<?=set_value("stock_expiry_warning_in_month",$product->stock_expiry_warning_in_month) ?>" class="form-control form-control-sm" id="stock_expiry_warning_in_month" placeholder="<?=$this->lang->line("product_stock_expiry_warning_in_month")?>">
            </div>
          </div>


        </div>
        <div class="col-sm-4">
          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("variant1")?>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4" name="variant1" id="variant1" placeholder="<?=$this->lang->line('variant1')?>" width="100%" data-dropdown-parent="#edit_product_modal">
                  <option value=""><?=$this->lang->line('select')?></option>
                  <?php
                    foreach ($variants as $value) {
                  ?>
                    <option value="<?=$value->id;?>"
                      <?php 
                        if($value->id == $product->variant1)
                          echo ' selected';
                      ?>
                    >
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
              <select class="form-control form-control-sm select2bs4" name="variant1_option[]" id="variant1_option" placeholder="Variant Option" width="100%" data-dropdown-parent="#edit_product_modal" multiple="multiple">
                <?php 

                  $selected_variant1_option = $product->variant1_option;
                  $selected_variant1_option_array = ($selected_variant1_option != '') ? explode(",", $selected_variant1_option) : array();

                  if($product->variant1 != '')
                  {
                    $variant1_option_ = $this->product_variant_model->get_single_record($product->variant1)->variant_option;
                    $variant1_option_array_ = ($variant1_option_ != '') ? explode(",", $variant1_option_) : array();

                    for ($i=0; $i < sizeof($variant1_option_array_); $i++) 
                    { 
                      if(!in_array($variant1_option_array_[$i], $selected_variant1_option_array))
                      {
                ?>
                      <option value="<?=$variant1_option_array_[$i]?>">
                        <?=clean_e_val($variant1_option_array_[$i])?>
                      </option>
                <?php
                      }
                    }
                  }
                ?>
              </select>
              <br/>
              <?php 
                for ($i=0; $i < sizeof($selected_variant1_option_array); $i++) { 
                  if($selected_variant1_option_array[$i] != '')
                  {
              ?>
                  <span class="bg-light border rounded p-2"><?=clean_e_val($selected_variant1_option_array[$i])?> <a href="#" class="text-danger ml-2 delete_warehouse_product" data-product_id="<?=$product->id?>" data-variant_value="<?=$selected_variant1_option_array[$i]?>" data-variant_option_id="variant1_option" data-varirant="variant1">X</a></span>
              <?php
                  }
                }
              ?>
              
            </div>
          </div>

          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("variant2")?>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4" name="variant2" id="variant2" placeholder="<?=$this->lang->line('variant2')?>" width="100%" data-dropdown-parent="#edit_product_modal">
                  <option value=""><?=$this->lang->line('select')?></option>
                  <?php
                    foreach ($variants as $value) {
                  ?>
                    <option value="<?=$value->id;?>"
                      <?php 
                        if($value->id == $product->variant2)
                          echo ' selected';
                      ?>
                    >
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
              <select class="form-control form-control-sm select2bs4" name="variant2_option[]" id="variant2_option" placeholder="Variant Option" width="100%" data-dropdown-parent="#edit_product_modal" multiple="multiple">
                <?php 

                  $selected_variant2_option = $product->variant2_option;
                  $selected_variant2_option_array = ($selected_variant2_option != '') ? explode(",", $selected_variant2_option) : array();

                  if($product->variant2 != '')
                  {
                    $variant2_option_ = $this->product_variant_model->get_single_record($product->variant2)->variant_option;
                    $variant2_option_array_ = ($variant2_option_ != '') ? explode(",", $variant2_option_) : array();

                    for ($i=0; $i < sizeof($variant2_option_array_); $i++) 
                    { 
                      if(!in_array($variant2_option_array_[$i], $selected_variant2_option_array))
                      {
                ?>
                      <option value="<?=$variant2_option_array_[$i]?>">
                        <?=clean_e_val($variant2_option_array_[$i])?>
                      </option>
                <?php
                      }
                    }
                  }
                ?>
              </select>
              <br/>
              <?php 
                for ($i=0; $i < sizeof($selected_variant2_option_array); $i++) { 
                  if($selected_variant2_option_array[$i] != '')
                  {
              ?>
                  <span class="bg-light border rounded p-2"><?=clean_e_val($selected_variant2_option_array[$i])?> <a href="#" class="text-danger delete_warehouse_product ml-2" data-product_id="<?=$product->id?>" data-variant_value="<?=$selected_variant2_option_array[$i]?>" data-variant_option_id="variant2_option" data-varirant="variant2">X</a></span>
              <?php
                  }
                }
              ?>
              
              </select>
            </div>
          </div>

          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("variant3")?>
            </label>
            <div class="col-sm-8">
              <select class="form-control form-control-sm select2bs4" name="variant3" id="variant3" placeholder="<?=$this->lang->line('variant3')?>" width="100%" data-dropdown-parent="#edit_product_modal">
                  <option value=""><?=$this->lang->line('select')?></option>
                  <?php
                    foreach ($variants as $value) {
                  ?>
                    <option value="<?=$value->id;?>"
                      <?php 
                        if($value->id == $product->variant3)
                          echo ' selected';
                      ?>
                    >
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
              <select class="form-control form-control-sm select2bs4" name="variant3_option[]" id="variant3_option" placeholder="Variant Option" width="100%" data-dropdown-parent="#edit_product_modal" multiple="multiple">
                <?php 

                  $selected_variant3_option = $product->variant3_option;
                  $selected_variant3_option_array = ($selected_variant3_option != '') ? explode(",", $selected_variant3_option) : array();

                  if($product->variant3 != '')
                  {
                    $variant3_option_ = $this->product_variant_model->get_single_record($product->variant3)->variant_option;
                    $variant3_option_array_ = ($variant3_option_ != '') ? explode(",", $variant3_option_) : array();

                    for ($i=0; $i < sizeof($variant3_option_array_); $i++) 
                    { 
                      if(!in_array($variant3_option_array_[$i], $selected_variant3_option_array))
                      {
                ?>
                      <option value="<?=$variant3_option_array_[$i]?>">
                        <?=clean_e_val($variant3_option_array_[$i])?>
                      </option>
                <?php
                      }
                    }
                  }
                ?>
              </select>
              <br/>
              <?php 
                for ($i=0; $i < sizeof($selected_variant3_option_array); $i++) { 
                  if($selected_variant3_option_array[$i] != '')
                  {
              ?>
                  <span class="bg-light border rounded p-2"><?=clean_e_val($selected_variant3_option_array[$i])?> <a href="#" class="text-danger delete_warehouse_product ml-2" data-product_id="<?=$product->id?>" data-variant_value="<?=$selected_variant3_option_array[$i]?>" data-variant_option_id="variant3_option" data-varirant="variant3">X</a></span>
              <?php
                  }
                }
              ?>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_no_of_strips_in_box")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="no_of_strips_in_box" value="<?=set_value("no_of_strips_in_box",$product->no_of_strips_in_box) ?>" class="form-control form-control-sm" id="no_of_strips_in_box" placeholder="<?=$this->lang->line("product_no_of_strips_in_box")?>">
            </div>
          </div>

          <div class="form-group row d-none">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_no_of_qty_in_box")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="no_of_qty_in_box" value="<?=set_value("no_of_qty_in_box",$product->no_of_qty_in_box) ?>" class="form-control form-control-sm" id="no_of_qty_in_box" placeholder="<?=$this->lang->line("product_no_of_qty_in_box")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_no_of_box_in_case")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="no_of_box_in_case" value="<?=set_value("no_of_box_in_case",$product->no_of_box_in_case) ?>" class="form-control form-control-sm" id="no_of_box_in_case" placeholder="<?=$this->lang->line("product_no_of_box_in_case")?>">
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_shipper_quantity")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="shipper_quantity" value="<?=set_value("shipper_quantity",$product->shipper_quantity) ?>" class="form-control form-control-sm" id="shipper_quantity" placeholder="<?=$this->lang->line("product_shipper_quantity")?>" readonly>
            </div>
          </div>

          <div class="form-group row">
            <label for="inputEmail3" class="col-sm-4 col-form-label">
              <?=$this->lang->line("product_strip_cost")?>
            </label>
            <div class="col-sm-8">
              <input type="number" name="strip_cost" value="<?=set_value("strip_cost",$product->strip_cost) ?>" class="form-control form-control-sm" id="strip_cost" placeholder="<?=$this->lang->line("product_strip_cost")?>" step="any" readonly="readonly">
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
      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-1 col-form-label">
          <?=$this->lang->line("product_description")?>
        </label>
        <div class="col-sm-11">
          <textarea type="text" name="description" id="description" class="form-control form-control-sm" id="client_contact_person_name" placeholder="<?=$this->lang->line("product_description")?>"><?=set_value("description",$product->description) ?></textarea>
        </div>
      </div>
    </div>
    
     
    
                    

  </div>
  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <input type="hidden" name="id" value="<?=$product->id?>">
    <input type="checkbox" class="single_product" name="update_warehouse_product_price" value="yes">
              Update all variant price.
    <button type="submit" name="submit" id="editProductSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
