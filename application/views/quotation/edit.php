<?php 
  $this->load->view('layout/header');
  $currency = $this->company_settings_model->get_company_records()->currency_symbol;

  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);

  $mfg_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);

?>

<style type="text/css">
  .search { position: relative; }
  .search input { text-indent: 20px;}
  .search .fa-search { 
    position: absolute;
    top: 9px;
    left: 20px;
    font-size: 18px;
    padding-left: 8px;
  }
  .search_product{
    background-color: #F8F8F8;
    height: 35px;
    padding-left: 25px;
    font-size: 18px;
  }

  .search_product::-webkit-input-placeholder { /* Chrome/Opera/Safari */
    padding-left: 5px;
    font-size: 18px;
  }
  .search_product::-moz-placeholder { /* Firefox 19+ */
    font-size: 18px;
  }
  .search_product:-ms-input-placeholder { /* IE 10+ */
    font-size: 18px;
  }
  .search_product:-moz-placeholder { /* Firefox 18- */
    font-size: 18px;
  }
  .product_row{
    background: #fffee9 !important;
  }
  .highlight_row{
    /*animation: fadeOut 5s forwards;*/
    background: #fed9c6 !important;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
  }
  .quantity_update_message{
    width: 100% !important;
    padding-left: 40% !important;
  }

  .discount_type{
    padding-left: 20px;
  }
  .discount_amount{
    padding-right: 10px !important;
  }
  .delete_item{
    cursor:pointer;
  }
  .total_data{
    font-size: 18px;
    font-weight: bolder;
  }



</style>
  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="<?=base_url('auth')?>"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('quotation')?>"><?=$this->lang->line('quotation_header')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('quotation_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editQuotationForm" id="editQuotationForm" method="POST" action="<?=base_url('quotation/edit')?>">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('quotation_edit')?></h3>
                  <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                  
                      <li class="nav-item  ml-2">
                        <a class="nav-link reset btn-sm btn-warning" href="#" data-tt="tooltip" title="Click here to Reset Quotation Items">
                          <i class="fas fa-redo-alt"></i> Reset
                        </a>
                      </li>



                      <li class="nav-item ml-2">
                        <a class="nav-link active" href="<?=base_url('quotation')?>" data-tt="tooltip" title="Click here to show quotation list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                      </li>

                     
                    </ul>

                    <div class="btn-group d-none">
                      <button type="button" class="btn btn-tool btn-warning rcm_btn" data-tt="tooltip" title="<?=$this->lang->line('quotation_rcm_change_status')?>">
                        <?=($quotation->rcm == 'Y') ? $this->lang->line('quotation_disable_rcm') : $this->lang->line('quotation_enable_rcm')?>
                      </button>
                      <button type="button" class="btn btn-tool btn-warning" data-tt="tooltip" title="<?=$this->lang->line('quotation_rcm_help')?>">
                        <i class="far fa-question-circle"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label><?=$this->lang->line('quotation_reference_no')?></label>
                          <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?=$quotation->reference_no?>" readonly>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label><?=$this->lang->line('quotation_date')?></label>
                          <input type="text" class="form-control datepicker" id="quotation_date" name="quotation_date" value="<?=date('d-m-Y', strtotime($quotation->quotation_date))?>" placeholder="Invoice Date">
                          <span id="err_quotation_date" class="error invalid-feedback"><?=form_error('quotation_date');?></span>
                        </div>
                      </div>
                      
                      <!-- ADD THIS VALID DAYS FIELD -->
<div class="col-sm-3">
    <div class="form-group">
        <label>Validity Period (Days)</label>
        <input type="number" 
               class="form-control field_validation" 
               id="valid_days" 
               name="valid_days" 
               value="<?= isset($quotation->valid_days) ? $quotation->valid_days : '30' ?>"
               min="1" 
               max="365" 
               required
               placeholder="Validity Period">
        <span id="err_valid_days" class="error invalid-feedback"><?=form_error('valid_days');?></span>
    </div>
</div>
<!-- END VALID DAYS FIELD -->
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="warehouse">
                            <?=$this->lang->line('purchase_warehouse')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="input-group input-group-sm">
                            <select class="form-control form-control-sm select2bs4 field_validation" name="warehouse_id" id="warehouse_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('purchase_warehouse')?>">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($warehouses as $value) {
                            ?>
                              <option value="<?=$value->id;?>"
                                <?php 
                                  if($value->id == $quotation->warehouse_id)
                                    echo ' selected';
                                ?>
                              >
                                <?= $value->name;?>
                              </option>
                              
                            <?php 
                              }
                            ?>
                             
                          
                            </select>
                          </div>
                          <!-- <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$quotation->warehouse_id?>"> -->
                          <span id="err_warehouse_id" class="error invalid-feedback"><?=form_error('warehouse_id');?></span>
                        </div>
                      </div>
                      
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="customer"><?=$this->lang->line('quotation_customer')?><span class="validation-color">*</span>
                          </label>
                          <div class="input-group input-group-sm">
                            <select class="form-control form-control-sm select2bs4 field_validation" name="customer_id" id="customer_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('quotation_customer')?>">
                              <option value=""><?= $this->lang->line('select') ?></option> <?php foreach ($customers as $value): ?> <option value="<?= $value->id ?>" <?= ($value->id == $quotation->customer_id) ? 'selected' : '' ?>> <?= $value->customer_name ?> <?php if (!empty($value->customer_company_name)): ?> (<?= $value->customer_company_name ?>) <?php endif; ?> </option> <?php endforeach; ?> </select>
                            <span class="input-group-append">
                              <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_customer_modal" data-tt="tooltip" accesskey="c"><i class="fas fa-plus"></i></button>
                            </span>
                         
                          </div>
                          <!-- <input type="hidden" name="customer_id" value="<?=$quotation->customer_id?>" id="customer_id"> -->
                          <input type="hidden" name="customer_state_id" id="customer_state_id" value="<?=$customer_detail->state_id?>">
                          <input type="hidden" name="customer_country_id" id="customer_country_id" value="<?=$customer_detail->country_id?>">
                          <span id="err_customer_id" class="error invalid-feedback"><?=form_error('customer_id');?></span>
                        </div>
                      </div>
                    </div>
                    
<div class="row">
    <div class="col-sm-12">
        <div class="form-group">
            <label>Select Shipping Address</label>
            <select class="form-control form-control-sm" id="shipping_address_select" name="shipping_address_select" required>
                <option value="">Select Shipping Address</option>
                <?php foreach ($customer_shipping_addresses as $address): ?>
                    <option value="<?= $address->id ?>" 
                        <?= ($address->id == $quotation->selected_shipping_address_id) ? 'selected' : '' ?>
                        data-address='<?= json_encode($address) ?>'>
                        <?= $address->shipping_name ?> - <?= $address->shipping_address ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
</div>

                    <div class="row customer_shipping_detail">

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>
                            <?=$this->lang->line('customer_shipping_country_id')?>
                            <span class="text-danger">*</span>
                          </label>
                          <select class="form-control form-control-sm select2bs4 field_validation" name="customer_shipping_country_id" id="customer_shipping_country_id" width="100%" placeholder="<?=$this->lang->line('customer_shipping_country_id')?>" tabindex="<?=$tabindex++?>">
                            <?php
                              foreach ($countries as $value) {
                            ?>
                              <option value="<?=$value->id;?>"
                                <?php 
                                  if($value->id == $quotation->customer_shipping_country_id)
                                    echo ' selected';
                                ?>
                              >
                                <?= $value->name;?>
                              </option>
                            <?php 
                              }
                            ?>
                        </select>
                        <span id="err_customer_shipping_country_id" class="error invalid-feedback"></span>
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>
                            <?=$this->lang->line('customer_shipping_state_id')?>
                            <span class="text-danger">*</span>
                          </label>
                          <select class="form-control form-control-sm select2bs4 field_validation"  name="customer_shipping_state_id" id="customer_shipping_state_id" width="100%" placeholder="<?=$this->lang->line('customer_shipping_state_id')?>" tabindex="<?=$tabindex++?>">
                        
                            <?php
                                if(isset($states))
                                {
                                  foreach ($states as $value) 
                                  {
                              ?>
                                  <option value="<?=$value->id;?>"
                                    <?php
                                      if(isset($customer_shipping_state_id))
                                      {
                                        if($customer_shipping_state_id == $value->id)
                                          echo ' selected';
                                      } 
                                      else
                                      {
                                        if($value->id == $quotation->customer_shipping_state_id)
                                          echo ' selected';
                                      }

                                    ?>
                                  >
                                    <?= $value->name;?>
                                  </option>
                              <?php 
                                  }
                                }
                              ?>
                              
                          </select>
                          <span id="err_customer_shipping_state_id" class="error invalid-feedback"><?=form_error('customer_shipping_state_id');?></span>
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>
                            <?=$this->lang->line('customer_shipping_city_id')?>
                            <span class="text-danger">*</span>
                          </label>
                          <select class="form-control form-control-sm select2bs4 field_validation" name="customer_shipping_city_id" id="customer_shipping_city_id" width="100%" tabindex="<?=$tabindex++?>">
                            <?php
                                if(isset($cities))
                                {
                                  foreach ($cities as $value) 
                                  {
                              ?>
                                  <option value="<?=$value->id;?>"
                                    <?php 
                                      if(isset($customer_shipping_city_id))
                                      {
                                        if($customer_shipping_city_id == $value->id)
                                          echo ' selected';
                                      }
                                      else
                                      {
                                        if($value->id == $quotation->customer_shipping_city_id)
                                          echo ' selected';
                                      }
                                    ?>
                                  >
                                    <?= $value->name;?>
                                  </option>
                              <?php 
                                  }
                                }
                              ?>
                          </select>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label>
                            <?=$this->lang->line('customer_shipping_address')?>
                            <span class="text-danger">*</span>
                          </label>
                          <input type="text" name="customer_shipping_address" value="<?=$quotation->customer_shipping_address?>" class="form-control field_validation" id="customer_shipping_address" placeholder="<?=$this->lang->line('customer_shipping_address')?>" tabindex="<?=$tabindex++?>"><?=form_error('shipping_address', '<div class="text-danger">', '</div>');?>
                          <span id="err_customer_shipping_address" class="error invalid-feedback"><?=form_error('customer_shipping_address', '<div class="text-danger">', '</div>');?></span>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label>
                            <?=$this->lang->line('customer_shipping_pincode')?>
                            <span class="text-danger">*</span>
                          </label>
                          <input type="text" name="customer_shipping_pincode" value="<?=$quotation->customer_shipping_pincode?>" class="form-control field_validation" id="customer_shipping_pincode" placeholder="<?=$this->lang->line('customer_shipping_pincode')?>" tabindex="<?=$tabindex++?>">
                          <span id="err_customer_shipping_pincode" class="error invalid-feedback"><?=form_error('customer_shipping_pincode', '<div class="text-danger">', '</div>');?></span>
                        </div>
                      </div>

                    </div>                    
                    
                    <div class="row">
                      <div class="col-sm-12 ">
                        <div class="well">
                          <div class="row">
                             <div class="col-sm-12">
                              <a href="" data-toggle="modal" data-tt="tooltip" data-target="#add_product_modal" class="float-right"><?=$this->lang->line('quotation_add_new_product')?></a>
                            </div>
                            <div class="col-sm-12 search">
                              <span class="fa fa-search"></span>
                              <input id="search_product" class="form-control search_product"  type="text" name="search_product"  placeholder="<?=$this->lang->line('quotation_item_search')?>">
                            </div>
                            <div class="col-sm-8">
                              <span class="validation-color" id="err_product"></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12" style="height: 20px;"></div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label><?=$this->lang->line('quotation_items')?></label>
<table class="table items table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
    <thead>
        <tr>
            <th width="2%">
                <img src="<?php echo base_url(); ?>assets/images/bin1.png" />
            </th>
            <th class="span2" width="15%">Product Name</th>
            <th class="span2" width="13%">Add Remark</th>
            <th class="span2" width="12%">Vendor</th>
            <th class="span2" width="8%">Purchase Cost</th>
            <th class="span2" width="8%"><?=$this->lang->line('quotation_qty')?></th>
            <th class="span2" width="10%"><?=$this->lang->line('quotation_price')?></th>
            <th class="span2 d-none" width="14%"><?=$this->lang->line('quotation_discount')?></th>
            <th class="span2" width="11%"><?=$this->lang->line('quotation_uom')?></th>
            <th class="span2" width="8%"><?=$this->lang->line('quotation_taxable_value')?></th>
            <th class="span2" width="12%"><?=$this->lang->line('quotation_tax')?></th>
            <th class="span2 d-none" width="5%"><?=$this->lang->line('quotation_inclusive')?></th>
            <th class="span2" width="7%"><?=$this->lang->line('quotation_total')?></th>
        </tr>
    </thead>
    <tbody id="product_table_body">
        <?php foreach ($quotation_items as $row) { ?>
            <tr class="product_row">
                <td>
                    <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>
                    <input type="hidden" name="product_id" value="<?=$row->product_id?>">
                    <input type="hidden" name="tax_type" value="<?= $row->tax_type ?>">
                    <input type="hidden" name="igst_rate" value="<?=$row->igst?>">
                    <input type="hidden" name="sgst_rate" value="<?=$row->sgst?>">
                    <input type="hidden" name="cgst_rate" value="<?=$row->cgst?>">
                    <input type="hidden" name="vendor_total_with_gst[]" value="<?= $row->purchase_cost * (1 + ($row->igst + $row->cgst + $row->sgst)/100) * $row->quantity ?>">

                    <?php 
                        $product_id = $row->product_id;
                        $cost = $row->cost;
                        $warehouse_id = $quotation->warehouse_id;
                        $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);
                    ?>
                    <input type="hidden" name="warehouse_product_id" value="<?=$warehouse_product->id?>">
                </td>

                <td>
                    <span name="product_name"><?=$row->product_name?></span><br/>
                    <span name="description"><?=$row->description?></span>
                </td>
                <td>
                    <textarea name="product_remark" class="form-control" rows="1"><?=!empty($row->product_remark) ? $row->product_remark : ''?></textarea>
                </td>
                
                <!-- Vendor Selection Column -->
               <td>
                    <select class="form-control select2bs4 supplier-select" name="supplier_id" style="width: 100%;" required>
                        <option value="">Select Supplier</option>
                        <?php 
                        // Use product-specific vendors instead of all suppliers
                        $product_vendors = isset($row->product_vendors) ? $row->product_vendors : [];
                        
                        if (!empty($product_vendors)) {
                            foreach($product_vendors as $vendor): 
                        ?>
                                <option value="<?=$vendor->id?>" 
                                    <?=($row->supplier_id == $vendor->id) ? 'selected' : ''?>>
                                    <?=$vendor->company_name?>
                                </option>
                        <?php 
                            endforeach; 
                        } else {
                            // Fallback to all suppliers if no specific vendors found for this product
                            foreach($suppliers as $supplier): 
                        ?>
                                <option value="<?=$supplier->id?>" 
                                    <?=($row->supplier_id == $supplier->id) ? 'selected' : ''?>>
                                    <?=$supplier->company_name?>
                                </option>
                        <?php 
                            endforeach;
                        }
                        ?>
                    </select>
                </td>
                
                <!-- Purchase Cost Column -->
                <td>
                    <input type="number" class="form-control cost-field" name="vendor_cost" step="0.01" value="<?=$row->purchase_cost?>" readonly>
                    <div>
                        <small>
                            GST: <span name="purchase_gst_amount"><?= number_format(($row->purchase_cost * ($row->igst + $row->cgst + $row->sgst)/100) * $row->quantity, 2) ?></span> (
                            <span name="purchase_gst_percent"><?= $row->igst + $row->cgst + $row->sgst ?>%</span>)
                        </small>
                    </div>
                </td>
                
                <td>
                    <input type="number" class="form-control" name="quantity" value="<?=$row->quantity?>" step="1" min="1">
                </td>
                
                <td>
                    <span id="price_span">
                        <input type="number" class="form-control text-right" name="price" step="0.01" min="0.01" value="<?=$row->price?>">
                        <input type="hidden" name="cost" value="<?=$row->cost?>">
                    </span>
                </td>

                <td class="d-none">
                    <select class="form-control select2bs4" name="item_discount" style="width: 100%;">
                        <option value="">Select</option>
                        <?php foreach ($discounts as $value) { ?>
                            <option value="<?=$value->id?>" <?=($value->id == $row->discount_id) ? ' selected' : ''?>>
                                <?=$value->name.' ('.$value->value.$this->session->userdata('currency_symbol').')' ?>
                            </option>
                        <?php } ?>
                    </select>
                    <span name="span_discount_type" class="discount_type">Discount Value: <?=$this->session->userdata('currency_symbol')?> </span>
                    <input type="hidden" name="discount_type" value="<?=$row->discount_type?>">
                    <span name="discount_amount" class="discount_amount"><?=$row->discount_amount?></span>
                    <input type="hidden" name="discount_value" value="<?=$row->discount_value?>">
                </td>
                
                <td>
                    <input type="hidden" name="uom_id" value="<?=$row->uom_id?>" data-uom_name="<?=$row->uom_name?>" data-uom_uom="<?=$row->uom_uom?>">  
                    <?=$row->uom_uom?>
                </td>
                
                <td><span name="taxable_value"><?=$row->taxable_value-$row->discount_amount?></span></td>
                
                <td class="tax_td">
                    <input type="hidden" name="cgst_rate" value="<?= $row->cgst ?>">
                    <input type="hidden" name="sgst_rate" value="<?= $row->sgst ?>">
                    <input type="hidden" name="igst_rate" value="<?= $row->igst ?>">

                    <?php 
                        if($company_setting->country_id == $customer_detail->country_id) {
                            if($company_setting->state_id == $customer_detail->state_id) {
                    ?>
                                <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">
                                CGST: <span name="c_tax"><?=$row->cgst_tax?></span>
                                (<span name="cgst"><?=$row->cgst?></span>)<br>
                                SGST: <span name="s_tax"><?=$row->sgst_tax?></span>
                                (<span name="sgst"><?=$row->sgst?></span>)
                                <span name="i_tax" style="display:none"><?=$row->igst_tax?></span>
                                <span name="igst" style="display:none"><?=$row->igst?></span>
                    <?php
                            } else {
                    ?>
                                <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">
                                IGST: <span name="i_tax"><?=$row->igst_tax?></span>
                                (<span name="igst"><?=$row->igst?></span>)
                                <span name="c_tax" style="display:none"><?=$row->cgst_tax?></span>
                                <span name="cgst" style="display:none"><?=$row->cgst?></span>
                                <span name="s_tax" style="display:none"><?=$row->sgst_tax?></span>
                                <span name="sgst" style="display:none"><?=$row->sgst?></span>
                    <?php
                            }
                        } else {
                    ?>
                            <input type="hidden" name="tax_id" value="1">
                            N/A
                            <span name="c_tax" style="display:none"><?=$row->cgst_tax?></span>
                            <span name="cgst" style="display:none"><?=$row->cgst?></span>
                            <span name="s_tax" style="display:none"><?=$row->sgst_tax?></span>
                            <span name="sgst" style="display:none"><?=$row->sgst?></span>
                            <span name="i_tax" style="display:none"><?=$row->igst_tax?></span>
                            <span name="igst" style="display:none"><?=$row->igst?></span>
                    <?php } ?>
                </td>
                
                <td class="d-none">
                    <input type="hidden" name="tax_type" value="<?=$row->tax_type?>">
                    <?=($row->tax_type == 0) ? 'No' : 'Yes';?>
                </td>
                
                <td><span name="sub_total"><?=$row->sub_total?></span></td>
            </tr>
        <?php } ?>
        
        <?php if ($quotation->additional_cost_type): ?>
            <tr class="service_row freight-row">
                <td>
                    <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>
                    <input type="hidden" name="freight_id" value="freight">
                    <input type="hidden" name="freight_type" value="<?= $quotation->additional_cost_type ?>">
                    <input type="hidden" name="freight_tax_rate" value="<?= $quotation->freight_tax_rate ?>">
                </td>
                <td><span name="freight_name">Freight Charges (<?= $quotation->additional_cost_type ?>)</span></td>
                <td><span name="freight_remark">Transport charges</span></td>
                <td></td> <!-- Vendor -->
                <td>0.00</td> <!-- Purchase cost -->
                <td><input type="number" class="form-control" name="freight_quantity" value="1" min="1" readonly></td>
                <td>
                    <input type="number" class="form-control text-right freight-amount" name="freight_price" id="freight_price" min="1"
                           value="<?= $quotation->freight_taxable_value ?>" data-freight-type="<?= $quotation->additional_cost_type ?>">
                </td>
                <td class="d-none"></td> <!-- Discount -->
                <td>NA</td> <!-- UOM -->
                <td><span name="freight_taxable_value"><?= $quotation->freight_taxable_value ?></span></td>
                <td class="tax_td">
                    <?php if ($quotation->additional_cost_type === 'external'): ?>
                        IGST : <span name="freight_i_tax"><?= $quotation->freight_tax_amount ?></span> (<span name="freight_igst"><?= $quotation->freight_tax_rate ?>%</span>)
                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
                <td class="d-none"></td> <!-- Inclusive -->
                <td><span name="freight_sub_total"><?= $quotation->freight_sub_total ?></span></td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<table class="table table-striped table-bordered table-condensed table-hover total_data" style="font-size: 18px;">
    <!-- Transport dropdown -->
    <tr>
        <td align="right" width="66%">
            <select class="form-control" id="additional_cost_type" name="additional_cost_type">
                <option value="">Select Transport</option>
                <option value="internal" <?= ($quotation->additional_cost_type == 'internal') ? 'selected' : '' ?>>Internal</option>
                <option value="external" <?= ($quotation->additional_cost_type == 'external') ? 'selected' : '' ?>>External</option>
            </select>
        </td>
        <td align='right' width="34%">
            <input type="number" class="form-control" id="additional_cost_amount" name="additional_cost_amount" 
                   value="<?= $quotation->additional_cost_amount ?>" min="0.00" step="0.01" placeholder="Enter Amount">
        </td>
    </tr>

    <!-- Total Taxable Value (excluding Freight) -->
    <tr>
        <td align="right" width="66%">Total Taxable Value (<?=$currency?>)</td>
        <td align='right' class="text-success" width="34%">
            +<span id="total_taxable_value"><?= $quotation->total_taxable_value ?></span>
            <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="<?= $quotation->total_taxable_value ?>">
        </td>
    </tr>

    <!-- Product Tax Only -->
    <tr>
        <td align="right" width="66%">Total Product Tax (<?=$currency?>)</td>
        <td align='right' class="text-success" width="34%">
            +<span id="total_tax"><?= $quotation->total_tax ?></span>
            <input type="hidden" name="total_tax" id="t_tax" value="<?= $quotation->total_tax ?>">
        </td>
    </tr>

    <tr>
        <td align="right" width="66%">Total (<?=$currency?>)</td>
        <td align='right' width="34%">
            <span id="total"><?= $quotation->total ?></span>
            <input type="hidden" name="total" id="t" value="<?= $quotation->total ?>">
        </td>
    </tr>
</table>

<table class="table table-striped table-bordered table-condensed table-hover profit_data" style="font-size: 18px;">
    <tr>
        <td align="right" width="66%">Total Purchase Cost(<?=$currency?>)</td>
        <td align='right' width="34%">
            <span id="total_purchase_cost"><?= $quotation->total_purchase_cost ?></span>
            <input type="hidden" name="total_purchase_cost" id="t_purchase_cost" value="<?= $quotation->total_purchase_cost ?>">
        </td>
    </tr>
    <tr>
        <td align="right" width="66%">Total Selling Price(<?=$currency?>)</td>
        <td align='right' width="34%">
            <span id="total_selling_price"><?= $quotation->total_selling_price ?></span>
            <input type="hidden" name="total_selling_price" id="t_selling_price" value="<?= $quotation->total_selling_price ?>">
        </td>
    </tr>
    <tr>
        <td align="right" width="66%">Gross Profit/Loss(<?=$currency?>)</td>
        <td align='right' width="34%">
            <span id="gross_profit_loss"><?= $quotation->gross_profit_loss ?></span>
            <input type="hidden" name="gross_profit_loss" id="t_profit_loss" value="<?= $quotation->gross_profit_loss ?>">
        </td>
    </tr>
    <tr>
        <td align="right" width="66%">Profit Margin(%)</td>
        <td align='right' width="34%">
            <span id="profit_margin"><?= $quotation->profit_margin ?>%</span>
            <input type="hidden" name="profit_margin" id="profit_margin" value="<?= $quotation->profit_margin ?>">
        </td>
    </tr>
</table>


                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-sm-12">
                        <div class="control-group">                     
                          <div class="controls">
                            <div class="tabbable">
                              <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                <li class="nav-item">
                                  <a class="nav-link active" href="#external_note" data-toggle="pill"><?php echo $this->lang->line('quotation_external_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#internal_note" data-toggle="pill"><?php echo $this->lang->line('quotation_internal_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#bank_detail" data-toggle="pill"><?php echo $this->lang->line('quotation_bank_detail'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#terms_and_condition" data-toggle="pill"><?php echo $this->lang->line('quotation_terms_and_condition'); ?></a>
                                </li>
                              </ul>                           
                              <br>
                              <div class="tab-content">
                                <div class="tab-pane active" id="external_note">
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('quotation_external_note'); ?>"><?=str_replace("<br />","",$quotation->external_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('quotation_internal_note'); ?>"><?=str_replace("<br />","",$quotation->internal_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="bank_detail">
                                  <textarea class="col-sm-12 form-control" name="bank_detail" rows="4" placeholder="<?php echo $this->lang->line('quotation_bank_detail'); ?>"><?=str_replace("<br />","",$quotation->bank_detail)?></textarea>
                                </div>
                                <div class="tab-pane" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('quotation_terms_and_condition'); ?>"><?=str_replace("<br />","",$quotation->terms_and_condition)?></textarea>
                                </div>
                              </div>
                            </div>   
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="rcm" id="rcm" value="<?=$quotation->rcm?>">
                  <input type="hidden" name="id" value="<?=$quotation->id?>">
                  <input type="hidden" name="quotation_items" id="quotation_items" value="">
                  <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  <button type="submit" name="submit" id="quotationSubmit" class="btn btn-info"><?=$this->lang->line('quotation_add')?></button>
                  <!-- <button type="submit" name="submit" id="quotationSubmitPayNow" value="pay" name="pay" class="btn btn-info">Add Quotation & Pay Now</button>                      -->
                  <span class="btn btn-default float-right" id="cancel" onclick="cancel('quotation')"><?=$this->lang->line('quotation_cancel')?></span>
                </div>
            </form>
          </div>
        </div>
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php 
  $this->load->view('layout/footer');
  $this->load->view('customer/add_customer_modal');
  // $this->load->view('product/add_product_modal');
?>

<div class="modal fade" id="emptyQuotationItemWarningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header warning-header">
        <h5 class="modal-title" id="exampleModalLabel"> <?=$this->lang->line('quotation_message_label')?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <?=$this->lang->line('empty_quotation_warning_label')?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=$this->lang->line('close')?></button>
      </div>
    </div>
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="reset_model" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header secondary-header">
          <h4 class="modal-title">
            Reset Purchase
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>
            <?php echo "Are you sure want to reset this quotation ?";?>
          </p>
        </div>
        <div class="modal-footer">
            
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        
          <button type="submit" name="resetSubmit" id="resetSubmit" class="btn btn-secondary" value="">Reset</button>
         
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/disableautofill/src/jquery.disableAutoFill.min.js"></script>

<script type="text/javascript">

  $(document).ready(function(e){
      
      
       // Initialize datepicker
    $('.datepicker').datepicker({
        weekStart: 1,
        daysOfWeekHighlighted: "6,0",
        autoclose: true,
        todayHighlight: true,
        format: 'dd-mm-yyyy'
    });

    // Initialize select2
    $('.select2bs4').select2({ theme: 'bootstrap4' });

    // Initialize calculations on page load
    $("#product_table_body tr.product_row").each(function() {
        calculateRow($(this));
    });
    updateProfitTotals();

    // Event handlers for row calculations
    $(document).on('change', 'input[name="quantity"], .supplier-select, select[name="item_discount"]', function() {
        var row = $(this).closest('tr');
        calculateRow(row);
        updateProfitTotals();
    });

    // Delegated event for price changes
    $(document).on('input', 'input[name="price"]', function() {
        var row = $(this).closest('tr');
        calculateRow(row);
        updateProfitTotals();
    });

    // Transport type change handler
    $('#additional_cost_type').change(function() {
        const type = $(this).val();
        const amount = 0;
        
        // Remove existing freight row if any
        $('.freight-row').remove();
        
        if (type) {
            addFreightRow(type, amount);
            updateProfitTotals();
        }
    });

    // Transport amount input handler
    $('#additional_cost_amount').on('input', function() {
        const type = $('#additional_cost_type').val();
        const amount = parseFloat($(this).val()) || 0;
        
        if (type && $('.freight-row').length) {
            updateFreightRow(type, amount);
            updateProfitTotals();
        }
    });

    // Delegated event for freight price changes
    $(document).on('input', '.freight-amount', function() {
        const type = $('#additional_cost_type').val();
        const amount = parseFloat($(this).val()) || 0;
        
        if (type) {
            updateFreightRow(type, amount);
            updateProfitTotals();
        }
    });

    $('.supplier-select').on('change', function() {
        var row = $(this).closest('tr');
        var product_id = row.find('input[name="product_id"]').val();
        var supplier_id = $(this).val();
        var costField = row.find('.cost-field');

        if (supplier_id) {
            $.ajax({
                url: "<?php echo base_url('quotation/get_latest_purchase_cost'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    product_id: product_id,
                    supplier_id: supplier_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(response) {
                    if (response.success) {
                        costField.val(response.cost);
                        // Update the hidden cost field if it exists
                        row.find('input[name="cost"]').val(response.cost);
                    } else {
                        costField.val('0.00');
                        row.find('input[name="cost"]').val('0.00');
                        alert('No purchase history found for this product from selected supplier');
                    }
                    calculateRow(row);
                    updateProfitTotals();
                },
                error: function() {
                    costField.val('0.00');
                    row.find('input[name="cost"]').val('0.00');
                    alert('Error fetching purchase cost');
                    calculateRow(row);
                    updateProfitTotals();
                }
            });
        } else {
            costField.val('0.00');
            row.find('input[name="cost"]').val('0.00');
            calculateRow(row);
            updateProfitTotals();
        }
    });

    // Delete item handler
    $('table.product_table').on('click', "span.delete_item", function(e) {
        var tr = $(this).closest('tr');
        tr.remove();
        updateProfitTotals();
    }); 
      
      
      

    $(document).on('click', "#resetSubmit" ,function(e){
      e.preventDefault();

      $('span.delete_item').trigger('click');
       
      $('#customer_id').val('');

      $('#customer_id').trigger('change');
      $('#reset_model').modal('hide');
   
    });


    $(document).on('click', ".reset" ,function(){
      $('#reset_model').modal('show');
    });


    $('#warehouse_id').change(function(e){

      var warehouse_id = $(this).val();
      if(warehouse_id != '')
      {
        if($('#customer_id').val() != '')
        {
          $("#warehouse_id").closest('.row').siblings().fadeIn(10);
        }

        $.ajax({
            url: "<?php echo base_url('warehouse/get_record_details') ?>",
            type: "POST",
            dataType: "json",
            data: {
                'warehouse_id' : warehouse_id,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(data){
              var warehouse = data.warehouse;
              // $("#product_table_body tr").remove();
              // calculateGrandTotal();
              refresh_tax_td();

            }
        });
      }
      else
      {
        $("#warehouse_id").closest('.row').siblings().css('display','none');
      }
    });
    
    
        $('#customer_shipping_country_id').change(function(){

        var id = $(this).val();

        //var company_state_id    = '<?=$this->company_settings_model->get_company_records()->state_id?>';
        // alert(id);
        $('#customer_shipping_state_id').html('<option value="">Select</option>');
        $('#customer_shipping_city_id').html('<option value="">Select</option>');

        $.ajax({
          url: "<?php echo base_url('utility/get_states') ?>/"+id,
          async: false,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            for(i=0;i<data.length;i++){
              $('#customer_shipping_state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }

            $('#customer_shipping_state_id').trigger('change');
          }
        });
      });

      $('#customer_shipping_state_id').change(function(){
        var id = $(this).val();

        if(id != '')
        {
          $.ajax({
            url: "<?php echo base_url('utility/get_cities') ?>/"+id,
            async: false,
            type: "GET",
            dataType: "JSON",
            success: function(data){
              $('#customer_shipping_city_id').html('<option value="">Select</option>');
              for(i=0;i<data.length;i++){
                $('#customer_shipping_city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
              }
              // $('#customer_shipping_city_id').trigger('change');
            }
          });
        }


      });
    
    

  $('#customer_id').change(function(e){
        $(".customer_shipping_detail").fadeIn(10);
        
        var customer_id = $(this).val();
        if(customer_id != '') {
            if($('#warehouse_id').val() != '') {
                $("#customer_id").closest('.row').siblings().fadeIn(10);  
            }

            $.ajax({
                url: "<?php echo base_url('customer/get_record_details') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    'customer_id' : customer_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(data){
                    var customer = data.customer;
                    var futureDate = data.futureDate;

                    $('#due_date').val(futureDate);
                    $('#customer_state_id').val(customer.state_id);
                    $('#customer_country_id').val(customer.country_id);

                    // Set the selected value in customer_shipping_state_id dropdown
                    $('#customer_shipping_country_id').val(customer.shipping_country_id).trigger('change');
                    $('#customer_shipping_state_id').val(customer.shipping_state_id).trigger('change');
                    $('#customer_shipping_city_id').val(customer.shipping_city_id).trigger('change');
                    $('#customer_shipping_address').val(customer.shipping_address).trigger('change');
                    $('#customer_shipping_pincode').val(customer.shipping_pincode).trigger('change');

                    if(customer.shipping_state_id != '' && customer.shipping_state_id != null) {
                        $('#customer_state_id').val(customer.shipping_state_id);
                    }

                    refresh_tax_td();
                }
            });
        } else {
            $("#customer_id").closest('.row').siblings().css('display','none');
        }
    }); 




    $('#shipping_address_select').change(function() {
        var selectedOption = $(this).find('option:selected');
        var addressData = selectedOption.data('address');
    
        if (addressData) {
            // Step 1: Set country and trigger change
            $('#customer_shipping_country_id').val(addressData.shipping_country_id).trigger('change');
    
            // Step 2: Wait until state dropdown is updated
            setTimeout(function () {
                $('#customer_shipping_state_id').val(addressData.shipping_state_id).trigger('change');
                $('#customer_shipping_city_id').val(addressData.shipping_city_id);
                
                // Step 3: Wait for the city AJAX call to finish (by checking when options are more than 1)
                let waitForCities = setInterval(function () {
                    if ($('#customer_shipping_city_id option').length > 1) {
                        clearInterval(waitForCities);
    
                        // Now that cities are populated, set the city value
                        $('#customer_shipping_city_id').val(addressData.shipping_city_id);
    
                        // Set remaining fields
                        $('#customer_shipping_address').val(addressData.shipping_address);
                        $('#customer_shipping_pincode').val(addressData.shipping_pincode);
                    }
                }, 100); // check every 100ms
            }, 800); // wait for state dropdown to update
        }
    });
    
    // Initialize the form with the selected shipping address if one exists
    $(document).ready(function() {
        var selectedAddressId = '<?= $quotation->selected_shipping_address_id ?>';
        if (selectedAddressId) {
            $('#shipping_address_select').trigger('change');
        }
    });

    function refresh_tax_td()
      {
        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var customer_state_id   = $('#customer_state_id').val();
        var customer_country_id = $('#customer_country_id').val();
        
        $("#product_table_body").find('tr').each(function () {
          var tr              = $(this).closest("tr");
          var tax_td          = tr.find('.tax_td');
          // var igst            = parseFloat(tax_td.find('span[name="igst"]').text());
          // var cgst            = parseFloat(tax_td.find('span[name="cgst"]').text());
          // var sgst            = parseFloat(tax_td.find('span[name="sgst"]').text());

          var igst            = 0;
          var cgst            = 0;
          var sgst            = 0;

          if(company_country_id == customer_country_id)
          {
            if(company_state_id == customer_state_id)
            {
              cgst        = parseFloat(tax_td.find('input[name^="cgst_rate"]').val());
              sgst        = parseFloat(tax_td.find('input[name^="sgst_rate"]').val());
            }
            else
            {
              igst        = parseFloat(tax_td.find('input[name^="igst_rate"]').val());
            }  
          }

          var tax_rate        = igst+cgst+sgst;

         

          var igst            = tax_rate;
          var cgst            = (parseFloat(tax_rate/2)).toFixed(2);
          var sgst            = (parseFloat(tax_rate/2)).toFixed(2);

        

          var tax_id          = tax_td.find('input[name="tax_id"]').val();
          var tax             = '<input type="hidden" name="tax_id" value="'+tax_id+'">';

          if(company_country_id == customer_country_id)
          { 
            if(company_state_id == customer_state_id)
            {
              tax += 'CGST : <span name="c_tax">'+0.0+'</span>(<span name="cgst">'+cgst+'</span>)<br/>';
              tax += 'SGST : <span name="s_tax">'+0.0+'</span>(<span name="sgst">'+sgst+'</span>)';
              tax += '<span name="i_tax"style="display:none">0</span><span name="igst" style="display:none">0</span>';
            }
            else
            {
              tax += 'IGST : <span name="i_tax">'+0.0+'</span>(<span name="igst">'+igst+'</span>)<br/>';
              tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
              tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
            }  
          }
          else
          {
            tax += 'N/A'; 
            tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
            tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
            tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>'; 
          }


          tax_td.html(tax);
          calculateRow(tr);
          calculateGrandTotal();
        });
      }

      // Service search code begin

      var c_mapping = { };

      $(function() {
    $('#search_product').autoComplete({
        minChars: 1,
        cache: 0,
        source: function(term, suggest) {
            term = term.toLowerCase();
            var warehouse_id = $('#warehouse_id').val();
            
            $.ajax({
                url: "<?php echo base_url('product/search') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    'term': term,
                    'warehouse_id': warehouse_id, 
                    'module': '<?= SALE_MODULE ?>',
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(data) {
                    var products = data;
                    var suggestions = [];
                    for (var i = 0; i < products.length; ++i) {
                        suggestions.push(products[i].warehouse_products_id + ' - ' + products[i].name + ' - ' + products[i].product_category_name + ' - ' + products[i].selling_price);
                        c_mapping[products[i].warehouse_products_id] = products[i].name;
                    }
                    suggest(suggestions);
                }
            });
        },
        onSelect: function(event, ui) {
            var str = ui.split(' - ');
            var warehouse_products_id = str[0];

            $.ajax({
                url: "<?php echo base_url('product/get_record_detail') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    'product_id': warehouse_products_id, 
                    'module': '<?= SALE_MODULE ?>',
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(data) {
                    var product = data.product;
                    var discounts = data.discount;
                    
                    // Get vendors for this product
                    $.ajax({
                        url: "<?php echo base_url('product/get_product_vendors'); ?>",
                        type: "GET",
                        dataType: "json",
                        data: {
                            product_id: product.id
                        },
                        success: function(vendors) {
                            if (!is_product_exist_in_row(product.warehouse_products_id)) {
                                add_row(product, discounts, vendors);
                            } else {
                                highlight_row(product.warehouse_products_id);
                            }
                    
                            $('.datepicker').datepicker({
                                weekStart: 1,
                                daysOfWeekHighlighted: "6,0",
                                autoclose: true,
                                todayHighlight: true,
                                format: 'dd-mm-yyyy'
                            });
                    
                            calculateGrandTotal();
                            $('#search_product').val('');
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching vendors:', error);
                            // Continue with adding row even if vendors fetch fails
                            if (!is_product_exist_in_row(product.warehouse_products_id)) {
                                add_row(product, discounts, []);
                            } else {
                                highlight_row(product.warehouse_products_id);
                            }
                            calculateGrandTotal();
                            $('#search_product').val('');
                        }
                    });
                }
            });
        }
    });
});

    
    function add_row(product, discounts, vendors) {
    var customer_country_id = $('#customer_country_id').val();
    var customer_state_id = $('#customer_state_id').val();
    var company_country_id = $('#company_country_id').val();
    var company_state_id = $('#company_state_id').val();

    var quantity = product.quantity;
    var input_quantity = '<input type="number" class="form-control" name="quantity" value="'+(quantity || 1)+'" step="1" min="1">';
    var taxable_value = '<span name="taxable_value">'+product.selling_price+'</span>';

    // Tax calculation
    var tax = '<input type="hidden" name="tax_id" value="'+product.tax_id+'">';
    if(company_country_id == customer_country_id) {
        if(company_state_id == customer_state_id) {
            tax += 'CGST : <span name="c_tax">0.0</span>(<span name="cgst">'+product.cgst+'%</span>)<br/>';
            tax += 'SGST : <span name="s_tax">0.0</span>(<span name="sgst">'+product.sgst+'%</span>)';
            tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
        } else {
            tax += 'IGST : <span name="i_tax">0.0</span>(<span name="igst">'+product.igst+'%</span>)<br/>';
            tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
            tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
        }
    } else {
        tax += 'N/A';
        tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
        tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
        tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
    }

    // UOM input
    var input_uom = '<input type="hidden" name="uom_id" value="' + product.uom_id + '">' +
                    '<input type="hidden" name="uom_name" value="' + product.uom_name + '">' +
                    '<input type="hidden" name="uom_uom" value="' + product.uom_uom + '">' +
                    product.uom_uom;

    // Supplier select dropdown - Only show vendors who supplied this product
    var supplier_select = '<select class="form-control select2bs4 supplier-select" name="supplier_id" style="width: 100%;">';
    supplier_select += '<option value="">Select Supplier</option>';
    
    if (vendors && vendors.length > 0) {
        vendors.forEach(function(vendor) {
            supplier_select += '<option value="'+vendor.id+'">'+vendor.company_name+'</option>';
        });
    } else {
        supplier_select += '<option value="">No suppliers found for this product</option>';
    }
    
    supplier_select += '</select>';

    // Cost field with GST info
    var cost_field = '<input type="number" class="form-control cost-field" name="vendor_cost" step="0.01" value="0.00" readonly>' +
                     '<div><small>GST: <span name="purchase_gst_amount">0.00</span> (<span name="purchase_gst_percent">0%</span>)</small></div>';

    var newRow = $('<tr class="service_row">');
    var cols = '';

    cols += '<td><span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>' +
            '<input type="hidden" name="product_id" value="'+product.id+'">' +
            '<input type="hidden" name="warehouse_product_id" value="'+product.warehouse_products_id+'">' + 
            '<input type="hidden" name="igst_rate" value="'+product.igst+'">' +
            '<input type="hidden" name="cgst_rate" value="'+product.cgst+'">' +
            '<input type="hidden" name="sgst_rate" value="'+product.sgst+'">' +
            '<input type="hidden" name="vendor_total_with_gst[]" value="0"></td>';
    
    cols += '<td>'
      + '<img src="<?= base_url('assets/product_images').'/'; ?>'+product.product_image+'" alt="'+product.name+'" style="width:40px; height:40px; object-fit:cover; margin-right:5px; border-radius:4px;">'
      + '<span name="product_name">'+product.name+'</span>'
      + '<span class="text-muted">(Code - '+product.product_code+')</span>'
    + '</td>';

    cols += '<td><textarea name="product_remark" rows="1" cols="20"></textarea></td>';
    cols += '<td>' + supplier_select + '</td>';
    cols += '<td>' + cost_field + '</td>';
    cols += '<td>' + input_quantity + '</td>';
    cols += '<td><input type="number" class="form-control text-right" name="price" step="0.01" min="0.01" value="'+product.selling_price+'">' +
            '<input type="hidden" name="cost" value="'+product.cost+'"></td>';
    cols += '<td style="display:none">' + // Discount (hidden)
            '<select class="form-control select2bs4" name="item_discount" style="display:none">' +
            '<option value="">Select</option></select>' +
            '<input type="hidden" name="discount_type" value="0">' +
            '<input type="hidden" name="discount_value" value="0"></td>';
    cols += '<td>' + input_uom + '</td>';
    cols += '<td>' + taxable_value + '</td>';
    cols += '<td class="tax_td">' + tax + '</td>';
    cols += '<td style="display:none"><input type="hidden" name="tax_type" value="'+product.tax_type+'">' +
            (product.tax_type == 0 ? "No" : "Yes") + '</td>';
    cols += '<td><span name="sub_total"></span></td>';

    newRow.append(cols);
    $("table.product_table").append(newRow);
    $('.select2bs4').select2({ theme: 'bootstrap4' });

    // Handle supplier change to update purchase cost
    newRow.find('.supplier-select').change(function () {
        var product_id = newRow.find('input[name="product_id"]').val();
        var supplier_id = $(this).val();
        var costField = newRow.find('.cost-field');

        if (supplier_id) {
            $.ajax({
                url: "<?php echo base_url('sale/get_latest_purchase_cost'); ?>",
                type: "POST",
                dataType: "json",
                data: {
                    product_id: product_id,
                    supplier_id: supplier_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function (response) {
                    if (response.success) {
                        costField.val(response.cost);
                    } else {
                        costField.val('0.00');
                        alert('No purchase history found for this product from selected supplier');
                    }
                    calculateRow(newRow);
                    updateProfitTotals();
                },
                error: function () {
                    costField.val('0.00');
                    alert('Error fetching purchase cost');
                    calculateRow(newRow);
                    updateProfitTotals();
                }
            });
        } else {
            costField.val('0.00');
            calculateRow(newRow);
            updateProfitTotals();
        }
    });

    calculateRow(newRow);
}

      function is_product_exist_in_row(product_id)
      { 
        var isProductExist = false;
        $("#product_table_body").find('tr').each(function () {

          var tr = $(this).closest("tr");

          if(tr.find('input[name^="warehouse_product_id"]').val() == product_id)
          {
            isProductExist = true;
          }
        });

        return isProductExist;
      }

      function highlight_row(product_id)
      {
        $("#product_table_body").find('tr').each(function () {
          var tr  = $(this).closest("tr");

          if(tr.find('input[name^="warehouse_product_id"]').val() == product_id)
          {
            tr.addClass('highlight_row');

            var existing_quantity = +tr.find('input[name^="quantity"]').val();
            // alert(existing_quantity + 1);
            // tr.find('input[name^="quantity"]').val(existing_quantity + 1).trigger('change');
            // tr.find('span[name^="quantity_update_message"]').text('+1');

            setTimeout(function(){
              tr.removeClass('highlight_row');
              tr.find('span[name^="quantity_update_message"]').text('');
            },3000);
          }
        });        
      }

      $("table.product_table").on('change', 'input[name^="price"], input[name^="quantity"], select[name^="item_discount"]', function (event) {

        if($(this).attr('name') == 'item_discount')
        {

          var discount_id = $(this).val();
          var tr          = $(this).closest('tr');

          if(discount_id != '')
          {
            // alert();
            $.ajax({
              url: "<?php echo base_url('discount/get_record_detail') ?>",
              type: "POST",
              dataType: "json",
              data:{
                'discount_id' : discount_id,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){

                var discount = data.discount;

                tr.find('input[name^="discount_type"]').val(discount.type);
                tr.find('input[name^="discount_value"]').val(discount.value);
                
                calculateRow(tr);
                calculateGrandTotal();
              }
            });    
          }
          else
          {
              tr.find('input[name^="discount_type"]').val(0);
              tr.find('input[name^="discount_value"]').val(0);

              calculateRow(tr);
              calculateGrandTotal();
          }
        }
        else
        {
          calculateRow($(this).closest("tr"));
          calculateGrandTotal();
        }
      });
      
      $('table.product_table').on('click',"span.delete_item", function(e){
        var tr = $(this).closest('tr');
        tr.remove();
        calculateGrandTotal();
      });

    //   function calculateRow(row)
    //   {
    //     var tax_type    = row.find('input[name^="tax_type"]').val();

    //     var customer_country_id = $('#customer_country_id').val();
    //     var customer_state_id   = $('#customer_state_id').val();

    //     var company_country_id  = $('#company_country_id').val();
    //     var company_state_id    = $('#company_state_id').val();

    //     if(tax_type == 0)
    //     {
    //       var product_id  = row.find('input[name^="product_id"]').val();
    //       var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
    //       var price       = parseFloat(row.find('input[name^="price"]').val());
    //       var final_discount_value = 0;

    //       var taxable_value   = parseFloat(quantity * price);

    //       var discount_type   = row.find('input[name^="discount_type"]').val();
    //       var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val());  

    //       var final_discount_value = 0;

    //       if(discount_type == 0)
    //       {
    //         final_discount_value = discount_value;
    //       }
    //       else
    //       {
    //         final_discount_value = (taxable_value * discount_value)/100;
    //       }

    //       taxable_value   = taxable_value - final_discount_value;
          
    //       var igst        = 0;
    //       var cgst        = 0;
    //       var sgst        = 0;

    //       if(company_country_id == customer_country_id)
    //       {
    //         if(company_state_id == customer_state_id)
    //         {
    //           cgst        = parseFloat(row.find('input[name^="cgst_rate"]').val());
    //           sgst        = parseFloat(row.find('input[name^="sgst_rate"]').val());
    //         }
    //         else
    //         {
    //           igst        = parseFloat(row.find('input[name^="igst_rate"]').val());
    //         }  
    //       }


    //       if(company_country_id == customer_country_id)
    //       {
          
    //           if(company_state_id == customer_state_id)
    //           {
    //             var igst_tax    = 0 ;
    //             var cgst_tax    = parseFloat((taxable_value * cgst)/100) ;
    //             var sgst_tax    = parseFloat((taxable_value * sgst)/100) ;
    //           }
    //           else
    //           {
    //             var igst_tax    = parseFloat((taxable_value * igst)/100) ;
    //             var cgst_tax    = 0 ;
    //             var sgst_tax    = 0 ;
    //           }  
      
    //       }
    //       else
    //       {
    //         var igst_tax    = 0 ;
    //         var cgst_tax    = 0 ;
    //         var sgst_tax    = 0 ;
    //       }

    //       var sub_total       = parseFloat(taxable_value + igst_tax + cgst_tax + sgst_tax);

    //       row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
    //       row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
    //       row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));

    //       row.find('span[name^="igst"]').text(igst.toFixed(2));
    //       row.find('span[name^="cgst"]').text(cgst.toFixed(2));
    //       row.find('span[name^="sgst"]').text(sgst.toFixed(2));

    //       row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

    //       row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
    //       row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));  
    //     }
    //     else
    //     {
    //       var final_discount_value = 0;
    //       var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
    //       var price       = parseFloat(row.find('input[name^="price"]').val());

    //       var discount_type   = row.find('input[name^="discount_type"]').val();
    //       var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val()); 

    //       var sub_total       = (quantity * price);

    //       if(discount_type == 0)
    //       {
    //         final_discount_value = discount_value;
    //       }
    //       else
    //       {
    //         final_discount_value = (subtotal * discount_value)/100;
    //       }

    //       sub_total = sub_total - final_discount_value;

    //       // alert(final_discount_value);

        
    //       var igst        = 0;
    //       var cgst        = 0;
    //       var sgst        = 0;

    //       if(company_country_id == customer_country_id)
    //       {
    //         if(company_state_id == customer_state_id)
    //         {
    //           cgst        = parseFloat(row.find('input[name^="cgst_rate"]').val());
    //           sgst        = parseFloat(row.find('input[name^="sgst_rate"]').val());
    //         }
    //         else
    //         {
    //           igst        = parseFloat(row.find('input[name^="igst_rate"]').val());
    //         }  
    //       }

        

    //       var tax_rate    = igst+cgst+sgst;

    //       var tax_amount    = parseFloat((sub_total * tax_rate) / (100 + tax_rate));

    //       var igst_tax = 0;
    //       var cgst_tax = 0;
    //       var sgst_tax = 0;

    //       if(company_country_id == customer_country_id)
    //       {
    //         if(company_state_id == customer_state_id)
    //         {
    //           cgst_tax = tax_amount/2;
    //           sgst_tax = tax_amount/2;
    //         }
    //         else
    //         {
    //           igst_tax = tax_amount;
    //         }  
    //       }
    //       else
    //       {
    //         var igst_tax    = 0 ;
    //         var cgst_tax    = 0 ;
    //         var sgst_tax    = 0 ;
    //       }

    //       var taxable_value = sub_total - (igst_tax + cgst_tax + sgst_tax);

    //       row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
    //       row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
    //       row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));

    //       row.find('span[name^="igst"]').text(igst.toFixed(2));
    //       row.find('span[name^="cgst"]').text(cgst.toFixed(2));
    //       row.find('span[name^="sgst"]').text(sgst.toFixed(2));
    //       row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

    //       row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
    //       row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));   

    //     }
        
    //   }
    //  function calculateRow(row) {
    //     var tax_type = row.find('input[name="tax_type"]').val();
    //     var quantity = parseFloat(row.find('input[name="quantity"]').val()) || 0;
    //     var price = parseFloat(row.find('input[name="price"]').val()) || 0;
    //     var discount_type = row.find('input[name="discount_type"]').val();
    //     var discount_value = parseFloat(row.find('input[name="discount_value"]').val()) || 0;

    //     var customer_country_id = $('#customer_country_id').val();
    //     var customer_state_id = $('#customer_state_id').val();
    //     var company_country_id = $('#company_country_id').val();
    //     var company_state_id = $('#company_state_id').val();

    //     var taxable_value = quantity * price;
    //     var final_discount_value = 0;

    //     if (discount_type == 0) {
    //         final_discount_value = discount_value;
    //     } else {
    //         final_discount_value = (taxable_value * discount_value) / 100;
    //     }

    //     taxable_value = taxable_value - final_discount_value;

    //     var igst = 0, cgst = 0, sgst = 0;
    //     var igst_tax = 0, cgst_tax = 0, sgst_tax = 0;

    //     // Set tax rates based on location
    //     if (company_country_id == customer_country_id) {
    //         if (company_state_id == customer_state_id) {
    //             cgst = parseFloat(row.find('input[name="cgst_rate"]').val()) || 0;
    //             sgst = parseFloat(row.find('input[name="sgst_rate"]').val()) || 0;
    //         } else {
    //             igst = parseFloat(row.find('input[name="igst_rate"]').val()) || 0;
    //         }
    //     }

    //     if (tax_type == 0) {
    //         // Exclusive tax
    //         if (company_country_id == customer_country_id) {
    //             if (company_state_id == customer_state_id) {
    //                 cgst_tax = (taxable_value * cgst) / 100;
    //                 sgst_tax = (taxable_value * sgst) / 100;
    //             } else {
    //                 igst_tax = (taxable_value * igst) / 100;
    //             }
    //         }
    //     } else {
    //         // Inclusive tax
    //         var total_tax_rate = igst + cgst + sgst;
    //         var total_tax_amount = (taxable_value * total_tax_rate) / (100 + total_tax_rate);

    //         if (company_country_id == customer_country_id) {
    //             if (company_state_id == customer_state_id) {
    //                 cgst_tax = total_tax_amount / 2;
    //                 sgst_tax = total_tax_amount / 2;
    //             } else {
    //                 igst_tax = total_tax_amount;
    //             }
    //         }

    //         taxable_value = taxable_value - (igst_tax + cgst_tax + sgst_tax);
    //     }

    //     var sub_total = taxable_value + igst_tax + cgst_tax + sgst_tax;

    //     // Update row values
    //     row.find('span[name="i_tax"]').text(igst_tax.toFixed(2));
    //     row.find('span[name="c_tax"]').text(cgst_tax.toFixed(2));
    //     row.find('span[name="s_tax"]').text(sgst_tax.toFixed(2));

    //     row.find('span[name="igst"]').text(igst.toFixed(2));
    //     row.find('span[name="cgst"]').text(cgst.toFixed(2));
    //     row.find('span[name="sgst"]').text(sgst.toFixed(2));

    //     row.find('span[name="discount_amount"]').text(final_discount_value.toFixed(2));
    //     row.find('span[name="taxable_value"]').text(taxable_value.toFixed(2));
    //     row.find('span[name="sub_total"]').text(sub_total.toFixed(2));

    //     // Calculate purchase cost with GST
    //     var cost = parseFloat(row.find('input[name="vendor_cost"]').val()) || 0;
    //     var purchase_igst = igst;
    //     var purchase_cgst = cgst;
    //     var purchase_sgst = sgst;

    //     var purchase_gst_percent = purchase_igst + purchase_cgst + purchase_sgst;
    //     var purchase_cost_with_gst = (cost + ((cost * purchase_gst_percent) / 100)) * quantity;
    //     row.find('input[name="vendor_total_with_gst[]"]').val(purchase_cost_with_gst.toFixed(2));
    //     var purchase_tax_amount = (purchase_cost_with_gst - (cost * quantity));

    //     row.find('span[name="purchase_gst_amount"]').text(purchase_tax_amount.toFixed(2));
    //     row.find('span[name="purchase_gst_percent"]').text(purchase_gst_percent.toFixed(2) + '%');
    // }

    // function updateProfitTotals() {
    //     let totalPurchaseCost = 0;
    //     let totalSellingPrice = 0;
    //     let totalTaxableValue = 0;
    //     let totalTax = 0;
    //     const transportType = $('#additional_cost_type').val();
    //     let freightSubTotal = 0;
    //     let freightTaxAmount = 0;
    //     let freightTaxableValue = 0;

    //     $("#product_table_body").find('tr').each(function() {
    //         const row = $(this);
    //         const isFreight = row.hasClass('freight-row');
            
    //         if (isFreight) {
    //             freightSubTotal = parseFloat(row.find('span[name="freight_sub_total"]').text()) || 0;
    //             freightTaxAmount = parseFloat(row.find('span[name="freight_i_tax"]').text()) || 0;
    //             freightTaxableValue = parseFloat(row.find('span[name="freight_taxable_value"]').text()) || 0;
                
    //             if (transportType === 'external') {
    //                 // Only add to taxable value and tax if external transport
    //                 totalTaxableValue += freightTaxableValue;
    //                 totalTax += freightTaxAmount;
    //                 totalSellingPrice += freightSubTotal;
    //             }
                
    //             // Always add to selling price (since it's part of the total amount charged)
                
    //             // Add to purchase cost only if internal transport
    //             if (transportType === 'internal') {
    //                 totalPurchaseCost += freightSubTotal;
    //             }
    //         } else {
    //             // Product row calculations (unchanged)
    //             const quantity = parseFloat(row.find('input[name="quantity"]').val()) || 0;
    //             const cost = parseFloat(row.find('input[name="vendor_cost"]').val()) || 0;
                
    //             // Calculate purchase cost with GST
    //             const igst = parseFloat(row.find('input[name="igst_rate"]').val()) || 0;
    //             const cgst = parseFloat(row.find('input[name="cgst_rate"]').val()) || 0;
    //             const sgst = parseFloat(row.find('input[name="sgst_rate"]').val()) || 0;
    //             const gstPercent = igst + cgst + sgst;
    //             const purchaseCostWithGst = (cost + ((cost * gstPercent) / 100)) * quantity;
    //             totalPurchaseCost += purchaseCostWithGst;

    //             // Add to selling values
    //             const subTotal = parseFloat(row.find('span[name="sub_total"]').text()) || 0;
    //             totalSellingPrice += subTotal;
                
    //             totalTaxableValue += parseFloat(row.find('span[name="taxable_value"]').text()) || 0;
    //             totalTax += (parseFloat(row.find('span[name="i_tax"]').text()) || 0)
    //                       + (parseFloat(row.find('span[name="c_tax"]').text()) || 0)
    //                       + (parseFloat(row.find('span[name="s_tax"]').text()) || 0);
    //         }
    //     });

    //     const grossProfitLoss = totalSellingPrice - totalPurchaseCost;
    //     const profitMargin = (totalPurchaseCost > 0) ? ((grossProfitLoss / totalPurchaseCost) * 100) : 0;

    //     $('#total_purchase_cost').text(totalPurchaseCost.toFixed(2));
    //     $('#t_purchase_cost').val(totalPurchaseCost.toFixed(2));
    //     $('#total_selling_price').text(totalSellingPrice.toFixed(2));
    //     $('#t_selling_price').val(totalSellingPrice.toFixed(2));
    //     $('#total_taxable_value').text(totalTaxableValue.toFixed(2));
    //     $('#t_taxable_value').val(totalTaxableValue.toFixed(2));
    //     $('#total_tax').text(totalTax.toFixed(2));
    //     $('#t_tax').val(totalTax.toFixed(2));
        
    //     if (transportType === 'external') {
    //         $('#freight_gst').text(freightTaxAmount.toFixed(2));
    //         $('#freight_gst_input').val(freightTaxAmount.toFixed(2));
    //     } else {
    //         $('#freight_gst').text('0.00');
    //         $('#freight_gst_input').val('0');
    //     }
        
    //     $('#total').text(totalSellingPrice.toFixed(2));
    //     $('#t').val(totalSellingPrice.toFixed(2));
    //     $('#gross_profit_loss').text(grossProfitLoss.toFixed(2));
    //     $('#t_profit_loss').val(grossProfitLoss.toFixed(2));
    //     $('#profit_margin').text(profitMargin.toFixed(2) + '%');
        
    //     return {
    //         totalPurchaseCost: totalPurchaseCost,
    //         totalSellingPrice: totalSellingPrice,
    //         grossProfitLoss: grossProfitLoss,
    //         profitMargin: profitMargin,
    //         freightSubTotal: freightSubTotal,
    //         transportType: transportType
    //     };
    // }
    function calculateRow(row) {
    var tax_type = parseFloat(row.find('input[name^="tax_type"]').val()) || 0;
    var customer_country_id = $('#customer_country_id').val();
    var customer_state_id = $('#customer_state_id').val();
    var company_country_id = $('#company_country_id').val();
    var company_state_id = $('#company_state_id').val();

    var quantity = parseFloat(row.find('input[name^="quantity"]').val()) || 0;
    var price = parseFloat(row.find('input[name^="price"]').val()) || 0;
    var cost = parseFloat(row.find('input[name="vendor_cost"]').val()) || 0;
    var discount_type = parseFloat(row.find('input[name="discount_type"]').val()) || 0;
    var discount_value = parseFloat(row.find('input[name="discount_value"]').val()) || 0;

    var taxable_value = quantity * price;
    var final_discount_value = 0;

    // Apply discount
    if (discount_type == 0) {
        final_discount_value = discount_value;
    } else {
        final_discount_value = (taxable_value * discount_value) / 100;
    }
    taxable_value = taxable_value - final_discount_value;

    // Initialize tax rates from the product data
    var igst = parseFloat(row.find('input[name^="igst_rate"]').val()) || 0;
    var cgst = parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0;
    var sgst = parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0;

    // Determine which taxes to apply based on location
    var apply_igst = false;
    var apply_cgst_sgst = false;
    
    if (company_country_id == customer_country_id) {
        if (company_state_id == customer_state_id) {
            // Same state - apply CGST+SGST
            apply_cgst_sgst = true;
            igst = 0; // Reset IGST if it was set
        } else {
            // Different state - apply IGST
            apply_igst = true;
            cgst = 0; // Reset CGST if it was set
            sgst = 0; // Reset SGST if it was set
        }
    } else {
        // Different country - no GST
        igst = 0;
        cgst = 0;
        sgst = 0;
    }
    row.data('apply_igst', apply_igst);
    row.data('apply_cgst_sgst', apply_cgst_sgst);
    
    var igst_tax = 0, cgst_tax = 0, sgst_tax = 0;

    if (tax_type == 0) {
        // Exclusive tax
        if (apply_igst) {
            igst_tax = (taxable_value * igst) / 100;
        } else if (apply_cgst_sgst) {
            cgst_tax = (taxable_value * cgst) / 100;
            sgst_tax = (taxable_value * sgst) / 100;
        }
    } else {
        // Inclusive tax
        var total_tax_rate = igst + cgst + sgst;
        var total_tax_amount = (taxable_value * total_tax_rate) / (100 + total_tax_rate);

        if (apply_igst) {
            igst_tax = total_tax_amount;
        } else if (apply_cgst_sgst) {
            cgst_tax = total_tax_amount / 2;
            sgst_tax = total_tax_amount / 2;
        }

        taxable_value = taxable_value - (igst_tax + cgst_tax + sgst_tax);
    }

    var sub_total = taxable_value + igst_tax + cgst_tax + sgst_tax;

    // Update row values
    row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
    row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
    row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));

    // Only update the displayed tax rates if they're actually being applied
    row.find('span[name^="igst"]').text(apply_igst ? igst.toFixed(2) : "0");
    row.find('span[name^="cgst"]').text(apply_cgst_sgst ? cgst.toFixed(2) : "0");
    row.find('span[name^="sgst"]').text(apply_cgst_sgst ? sgst.toFixed(2) : "0");

    row.find('span[name="discount_amount"]').text(final_discount_value.toFixed(2));
    row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
    row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));

    // Calculate purchase cost with GST
    var purchase_gst_percent = igst + cgst + sgst;
    var purchase_cost_with_gst = (cost + ((cost * purchase_gst_percent) / 100)) * quantity;
    row.find('input[name="vendor_total_with_gst[]"]').val(purchase_cost_with_gst.toFixed(2));
    var purchase_tax_amount = (purchase_cost_with_gst - (cost * quantity));

    // Update purchase GST info
    row.find('span[name="purchase_gst_amount"]').text(purchase_tax_amount.toFixed(2));
    row.find('span[name="purchase_gst_percent"]').text(purchase_gst_percent.toFixed(2) + '%');

    // Calculate row profit
    var rowProfit = (price * quantity) - purchase_cost_with_gst;
    row.find('span[name="row_profit"]').text(rowProfit.toFixed(2));
}

    function updateProfitTotals() {
    var totalPurchaseCost = 0;
    var totalSellingPrice = 0;
    var totalTaxableValue = 0;
    var totalTax = 0;
    var transportType = $('#additional_cost_type').val();
    var freightSubTotal = 0;

    $("#product_table_body").find('tr').each(function () {
        var row = $(this);
        var isFreight = row.find('input[name="freight_id"]').val() === 'freight';
        
        if (isFreight) {
            var freightAmount = parseFloat(row.find('input[name="freight_price"]').val()) || 0;
            var freightTax = parseFloat(row.find('span[name="freight_i_tax"]').text()) || 0;
            freightSubTotal = parseFloat(row.find('span[name="freight_sub_total"]').text()) || 0;

            if (transportType === 'internal') {
                // Internal transport - add to purchase cost only
                totalPurchaseCost += freightAmount;
            } else {
                // External transport - add to taxable and tax
                totalTaxableValue += freightAmount;
                totalTax += freightTax;
                totalSellingPrice += freightSubTotal;
            }
        } else {
            // Handle product rows normally
            var vendorWithGst = parseFloat(row.find('input[name="vendor_total_with_gst[]"]').val()) || 0;
            totalPurchaseCost += vendorWithGst;

            var subTotal = parseFloat(row.find('span[name="sub_total"]').text()) || 0;
            totalSellingPrice += subTotal;
            
            totalTaxableValue += parseFloat(row.find('span[name="taxable_value"]').text()) || 0;
            totalTax += (parseFloat(row.find('span[name="i_tax"]').text()) || 0)
                      + (parseFloat(row.find('span[name="c_tax"]').text()) || 0)
                      + (parseFloat(row.find('span[name="s_tax"]').text()) || 0);
        }
    });

    // Calculate profit metrics
    var grossProfitLoss = totalSellingPrice - totalPurchaseCost;
    var profitMargin = (totalPurchaseCost > 0) ? ((grossProfitLoss / totalPurchaseCost) * 100) : 0;

    // Update UI
    $('#total_purchase_cost').text(totalPurchaseCost.toFixed(2));
    $('#t_purchase_cost').val(totalPurchaseCost.toFixed(2));
    $('#total_selling_price').text(totalSellingPrice.toFixed(2));
    $('#t_selling_price').val(totalSellingPrice.toFixed(2));
    $('#total_taxable_value').text(totalTaxableValue.toFixed(2));
    $('#t_taxable_value').val(totalTaxableValue.toFixed(2));
    $('#total_tax').text(totalTax.toFixed(2));
    $('#t_tax').val(totalTax.toFixed(2));
    $('#total').text(totalSellingPrice.toFixed(2));
    $('#t').val(totalSellingPrice.toFixed(2));
    $('#gross_profit_loss').text(grossProfitLoss.toFixed(2));
    $('#t_profit_loss').val(grossProfitLoss.toFixed(2));
    $('#profit_margin').text(profitMargin.toFixed(2) + '%');

    return {
        totalPurchaseCost: totalPurchaseCost,
        totalSellingPrice: totalSellingPrice,
        grossProfitLoss: grossProfitLoss,
        profitMargin: profitMargin,
        freightSubTotal: freightSubTotal,
        transportType: transportType
    };
}


    function addFreightRow(type, amount = 0) {
        const taxRate = type === 'external' ? 18 : 0;
        let taxableValue = type === 'external' ? amount / (1 + (taxRate / 100)) : amount;
        let taxAmount = 0;
        let subTotal = amount;

        if (type === 'external' && amount > 0) {
            taxAmount = taxableValue * (taxRate / 100);
            subTotal = taxableValue + taxAmount;
        }

        const newRow = $(`
            <tr class="service_row freight-row">
                <td>
                    <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>
                    <input type="hidden" name="freight_id" value="freight">
                    <input type="hidden" name="freight_type" value="${type}">
                    <input type="hidden" name="freight_tax_rate" value="${taxRate}">
                </td>
                <td><span name="freight_name">Freight Charges (${type})</span></td>
                <td><span name="freight_remark">Transport charges</span></td>
                <td></td>
                <td>0.00</td>
                <td><input type="number" class="form-control" name="freight_quantity" value="1" min="1" readonly></td>
                <td>
                    <input type="number" class="form-control text-right freight-amount" 
                           name="freight_price" value="${taxableValue.toFixed(2)}" 
                           data-freight-type="${type}">
                </td>
                <td class="d-none"></td>
                <td>NA</td>
                <td><span name="freight_taxable_value">${taxableValue.toFixed(2)}</span></td>
                <td class="tax_td">
                    ${type === 'external' 
                        ? `IGST : <span name="freight_i_tax">${taxAmount.toFixed(2)}</span> (<span name="freight_igst">${taxRate}%</span>)` 
                        : 'N/A'}
                </td>
                <td class="d-none"></td>
                <td><span name="freight_sub_total">${subTotal.toFixed(2)}</span></td>
            </tr>
        `);

        $("table.product_table tbody#product_table_body").append(newRow);
    }

    function updateFreightRow(type, amount) {
        let taxRate = (type === 'external') ? 18 : 0;
        let taxableValue = amount;
        let taxAmount = 0;
        let subTotal = amount;

        if (type === 'external' && amount > 0) {
            taxAmount = (amount * taxRate) / 100;
            subTotal = amount + taxAmount;
        }

        let row = $('.freight-row');
        row.find('span[name="freight_taxable_value"]').text(taxableValue.toFixed(2));
        row.find('input[name="freight_price"]').val(amount.toFixed(2));

        row.find('input[name="freight_tax_rate"]').val(taxRate);

        if (type === 'external') {
            row.find('.tax_td').html('IGST : <span name="freight_i_tax">' + taxAmount.toFixed(2) + '</span> (18%)');
        } else {
            row.find('.tax_td').html('N/A');
        }

        row.find('span[name="freight_sub_total"]').text(subTotal.toFixed(2));

        updateProfitTotals();
    }


    function calculateGrandTotal() {
    var total_taxable_value = 0.0;
    var total_cgst = 0.0;
    var total_sgst = 0.0;
    var total_igst = 0.0;
    var total = 0.0;
    var total_discount = 0.0;

    $("#product_table_body").find('tr').each(function () {
        var tr = $(this); // No need for closest("tr") since we're already on the tr
        
        // Safely parse values with default 0 if NaN
        total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text()) || 0;
        total_discount += parseFloat(tr.find('span[name^="discount_amount"]').text()) || 0;
        total_cgst += parseFloat(tr.find('span[name^="c_tax"]').text()) || 0;
        total_sgst += parseFloat(tr.find('span[name^="s_tax"]').text()) || 0;
        total_igst += parseFloat(tr.find('span[name^="i_tax"]').text()) || 0;
        total += parseFloat(tr.find('span[name^="sub_total"]').text()) || 0;
    });

    // Update the totals
    $('#total_taxable_value').text(total_taxable_value.toFixed(2));
    $('#t_taxable_value').val(total_taxable_value.toFixed(2));

    $('#total_discount').text(total_discount.toFixed(2));
    $('#t_discount').val(total_discount.toFixed(2));

    $('#total_tax').text((total_cgst + total_sgst + total_igst).toFixed(2));
    $('#t_tax').val((total_cgst + total_sgst + total_igst).toFixed(2));

    $('#total').text(total.toFixed(2));
    $('#t').val(total.toFixed(2));
    
    // Also update profit totals if needed
    updateProfitTotals();
}

    //   $('#editQuotationForm').submit(function(e){
    //     // e.preventDefault();

    //     var isError = false;
    //     $('#quotationSubmit').text('<?=$this->lang->line("please_wait")?>');

    //     $('form#editQuotationForm .field_validation').each(function() {
    //         var id    = $(this).attr('id');
    //         var value = $(this).val();
    //         var field = $(this).attr('placeholder');

    //         if(value==null || value==""){
    //           $("form#editQuotationForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
    //           $('form#editQuotationForm #'+id).addClass('is-invalid');
    //           isError = true;
    //         }
    //         else
    //         {
    //           $("form#editQuotationForm #err_"+id).text("").fadeOut('slow');
    //           $('form#editQuotationForm #'+id).removeClass('is-invalid');
    //           $('form#editQuotationForm #'+id).addClass('is-valid');
    //         }
    //     });

    //     var productDataArray = [];

    //     $("#product_table_body").find('tr').each(function () {

    //         var tr              = $(this).closest("tr");
    //         var productData     = {};

    //         productData['product_id']       = tr.find('input[name="product_id"]').val();
    //         productData['warehouse_product_id'] = tr.find('input[name="warehouse_product_id"]').val();
    //         productData['product_name']     = tr.find('span[name="product_name"]').text();
    //         productData['description']      = tr.find('span[name="description"]').text();
    //         productData['quantity']         = tr.find('input[name="quantity"]').val();
    //         productData['cost']             = tr.find('input[name="cost"]').val();
    //         productData['price']            = tr.find('input[name="price"]').val();

    //         productData['mfg_date']      = tr.find('input[name="mfg_date"]').val();
    //         productData['expiry_date']      = tr.find('input[name="expiry_date"]').val();
            
    //         productData['taxable_value']    = parseFloat(productData['quantity'])*parseFloat(productData['price']);
    //         productData['discount_id']      = tr.find('select[name="item_discount"]').val();
    //         productData['discount_type']    = tr.find('input[name="discount_type"]').val();
    //         productData['discount_value']   = tr.find('input[name="discount_value"]').val();
    //         productData['discount_amount']  = tr.find('span[name="discount_amount"]').text();
    //         productData['tax_id']           = tr.find('input[name="tax_id"]').val();
    //         productData['tax_type']         = tr.find('input[name="tax_type"]').val();
    //         productData['igst']             = tr.find('span[name="igst"]').text();
    //         productData['igst_tax']         = tr.find('span[name="i_tax"]').text();
    //         productData['cgst']             = tr.find('span[name="cgst"]').text();
    //         productData['cgst_tax']         = tr.find('span[name="c_tax"]').text();
    //         productData['sgst']             = tr.find('span[name="sgst"]').text();
    //         productData['sgst_tax']         = tr.find('span[name="s_tax"]').text();
    //         productData['sub_total']        = tr.find('span[name="sub_total"]').text();

    //         productDataArray.push(JSON.stringify(productData));
    //     });

    //     if(productDataArray.length > 0)
    //     {
    //       $('#quotation_items').val(productDataArray.join('|'));
    //     }
    //     else
    //     {
    //       isError = true;
    //       $('#emptyQuotationItemWarningModal').modal('show');
    //     }

    //     if(isError == true)
    //     {
    //       $('#quotationSubmit').text('<?=$this->lang->line("quotation_save")?>');
    //       return false;
    //     }
    //     else 
    //     {
    //       return true;
    //     }
    //   });
      $('#editQuotationForm').submit(function(e) {
        var isError = false;
        $('#quotationSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled', 'disabled');

        // Validate required fields
        $('form#editQuotationForm .field_validation').each(function() {
            var id = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if (value == null || value == "") {
                $("form#editQuotationForm #err_" + id).text(field + " field is required.").fadeIn('slow');
                $('form#editQuotationForm #' + id).addClass('is-invalid');
                isError = true;
            } else {
                $("form#editQuotationForm #err_" + id).text("").fadeOut('slow');
                $('form#editQuotationForm #' + id).removeClass('is-invalid');
                $('form#editQuotationForm #' + id).addClass('is-valid');
            }
        });

        // Calculate profit data
        var profitResults = updateProfitTotals();
        var additionalCostType = $('#additional_cost_type').val();
        var additionalCostAmount = parseFloat($('#additional_cost_amount').val()) || 0;

        // Prepare product data - match add form structure
        var productDataArray = [];
        $("#product_table_body").find('tr').each(function() {
            var tr = $(this);
            if (tr.find('input[name="freight_id"]').val() === 'freight') return; // Skip freight row
            
            var productData = {
                'product_id': tr.find('input[name="product_id"]').val(),
                'product_remark': tr.find('textarea[name="product_remark"]').val(),
                'warehouse_product_id': tr.find('input[name="warehouse_product_id"]').val(),
                'uom_id': tr.find('input[name="uom_id"]').val(),
                'uom_name': tr.find('input[name="uom_name"]').val() || '',
                'uom_uom': tr.find('input[name="uom_uom"]').val() || '',
                'product_name': tr.find('span[name="product_name"]').text(),
                'description': tr.find('span[name="description"]').text(),
                'quantity': parseFloat(tr.find('input[name="quantity"]').val()) || 0,
                'price': parseFloat(tr.find('input[name="price"]').val()) || 0,
                'sub_total': parseFloat(tr.find('span[name="sub_total"]').text()) || 0,
                'purchase_cost': parseFloat(tr.find('input[name="vendor_cost"]').val()) || 0,
                'supplier_id': tr.find('select[name="supplier_id"]').val() || '',
                'taxable_value': parseFloat(tr.find('span[name="taxable_value"]').text()) || 0,
                'igst_tax': parseFloat(tr.find('span[name="i_tax"]').text()) || 0,
                'cgst_tax': parseFloat(tr.find('span[name="c_tax"]').text()) || 0,
                'sgst_tax': parseFloat(tr.find('span[name="s_tax"]').text()) || 0,
                'igst': parseFloat(tr.find('input[name="igst_rate"]').val()) || 0,
                'cgst': parseFloat(tr.find('input[name="cgst_rate"]').val()) || 0,
                'sgst': parseFloat(tr.find('input[name="sgst_rate"]').val()) || 0,
                'tax_type': tr.find('input[name="tax_type"]').val() || 0,
                'tax_id': tr.find('input[name="tax_id"]').val() || 0,
                'discount_id': tr.find('select[name="item_discount"]').val() || '',
                'discount_type': tr.find('input[name="discount_type"]').val() || 0,
                'discount_value': tr.find('input[name="discount_value"]').val() || 0,
                'discount_amount': parseFloat(tr.find('span[name="discount_amount"]').text()) || 0,
                'cost': parseFloat(tr.find('input[name="cost"]').val()) || 0
            };
            
            productDataArray.push(JSON.stringify(productData));
        });

        // Prepare freight data
        var freightData = {
            'freight_price': 0,
            'freight_taxable_value': 0,
            'freight_sub_total': 0,
            'freight_tax_rate': 0,
            'freight_tax_amount': 0
        };

        $(".freight-row").each(function() {
            freightData = {
                'freight_price': parseFloat($(this).find('input[name="freight_price"]').val()) || 0,
                'freight_taxable_value': parseFloat($(this).find('span[name="freight_taxable_value"]').text()) || 0,
                'freight_sub_total': parseFloat($(this).find('span[name="freight_sub_total"]').text()) || 0,
                'freight_tax_rate': parseFloat($(this).find('input[name="freight_tax_rate"]').val()) || 0,
                'freight_tax_amount': parseFloat($(this).find('span[name="freight_i_tax"]').text()) || 0
            };
        });

        // Prepare profit data for submission
        var profitData = {
            'total_purchase_cost': profitResults.totalPurchaseCost.toFixed(2),
            'additional_cost_type': additionalCostType,
            'additional_cost_amount': additionalCostAmount.toFixed(2),
            'total_selling_price': profitResults.totalSellingPrice.toFixed(2),
            'gross_profit_loss': profitResults.grossProfitLoss.toFixed(2),
            'profit_margin': profitResults.profitMargin.toFixed(2),
            'freight_amount': profitResults.freightSubTotal.toFixed(2),
            'transport_type': profitResults.transportType
        };

        // Create/update hidden fields
        if ($('#quotation_items').length === 0) {
            $('<input>').attr({
                type: 'hidden',
                id: 'quotation_items',
                name: 'quotation_items'
            }).appendTo('#editQuotationForm');
        }
        
        if ($('#profit_data').length === 0) {
            $('<input>').attr({
                type: 'hidden',
                id: 'profit_data',
                name: 'profit_data'
            }).appendTo('#editQuotationForm');
        }
        
        if ($('#freight_data').length === 0) {
            $('<input>').attr({
                type: 'hidden',
                id: 'freight_data',
                name: 'freight_data'
            }).appendTo('#editQuotationForm');
        }

        // Set values - match add form structure
        $('#quotation_items').val(productDataArray.join('|'));
        $('#profit_data').val(JSON.stringify(profitData));
        $('#freight_data').val(JSON.stringify(freightData));

        // Check if there are any items
        if (productDataArray.length === 0) {
            isError = true;
            $('#emptyQuotationItemWarningModal').modal('show');
        }

        if (isError) {
            $('#quotationSubmit').text('<?=$this->lang->line("quotation_edit")?>').removeAttr('disabled');
            return false;
        }
        
        return true;
    });

      $("form#editQuotationForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editQuotationForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#editQuotationForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editQuotationForm #err_"+id).text("").fadeOut('slow');
          $('form#editQuotationForm #'+id).removeClass('is-invalid');
          $('form#editQuotationForm #'+id).addClass('is-valid');
        }
      });

      $('.rcm_btn').click(function(e){
        e.preventDefault();

        if($('#product_table_body tr').length > 0)
        {
          $('.rcm-confirmation-body').html('<?=$this->lang->line('quotation_rcm_confirmation_message')?>');

          $('#rcm-confirmation').modal({
              backdrop: 'static',
              keyboard: false
          }).on('click', '#rcm-confirmation-confirm', function(e) {

              $("#product_table_body tr").remove();
              calculateGrandTotal();

              var rcm = $('#rcm').val();

              if(rcm == '0')
              {
                $('#rcm').val('1');
                $('.rcm_btn').text('<?=$this->lang->line('quotation_disable_rcm')?>');
              }
              else
              {
                $('#rcm').val('0');
                $('.rcm_btn').text('<?=$this->lang->line('quotation_enable_rcm')?>');
              }

              $('#rcm-confirmation').modal('hide');
          });
        }
        else
        {
          var rcm = $('#rcm').val();
          if(rcm == '0')
          {
            $('#rcm').val('1');
            $('.rcm_btn').text('<?=$this->lang->line('quotation_disable_rcm')?>');
          }
          else
          {
            $('#rcm').val('0');
            $('.rcm_btn').text('<?=$this->lang->line('quotation_enable_rcm')?>');
          }  
        }
      });

      const CustomerToast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 10000
      });

      $('form#addCustomerForm').submit(function(e){
        e.preventDefault();

        var isError = false;
        $('form#addCustomerForm #customerSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $('form#addCustomerForm .field_validation').each(function() {
            
            var id    = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if(value==null || value==""){
              $("form#addCustomerForm #err_"+id).text(field+ " field is required.");
              if($('form#addCustomerForm #'+id).hasClass('is-valid')){
                $('form#addCustomerForm #'+id).removeClass('is-valid');
              }
              $('form#addCustomerForm #'+id).addClass('is-invalid');
              isError = true;
            }
            else
            {
              $("form#addCustomerForm #err_"+id).text("");
              $('form#addCustomerForm #'+id).removeClass('is-invalid');
              $('form#addCustomerForm #'+id).addClass('is-valid');
            }
        });


        if(isError == true)
        {
          $('form#addCustomerForm #customerSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
          return false;
        }  
        else 
        {
          var formData = $('#addCustomerForm').serialize();

          $.ajax({
              url: '<?php echo base_url("customer/add") ?>',
              type: 'POST',
              dataType : 'json',
              data: formData,                       
              success: function (response) {

                var customer = response.customer;

                if(response.code==1)
                {
                  $('#add_customer_modal').modal('hide');
                  $('form#addCustomerForm #customerSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');

                  if($('#customer_id').length)
                  {
                    $('#customer_id').html('');
                    $('#customer_id').append('<option value="">Select</option>');
                    
                    for(i=0;i<response['customers'].length;i++)
                    { 
                      $('#customer_id').append('<option value="' + response['customers'][i].id + '">' + response['customers'][i].customer_name+'-'+response['customers'][i].phone+'</option>');
                    }

                    $('#customer_id').val(response['id']).attr("selected","selected");  

                    if($("#warehouse_id").length)
                    { 
                      // display shipping fields
                      $('.shipping_detail').css('display','block');
                      
                      if($("#warehouse_id").val() != '')
                      {
                        $("#customer_id").closest('.row').siblings().fadeIn(10);
                      }
                      $('form#addCustomerForm #customer_state_id').val(customer.state_id);
                      $('form#addCustomerForm #customer_country_id').val(customer.country_id);

                      $('input[name="shipping_country_id"]').val(customer.shipping_country_id).trigger('change');

                      // $("#product_table_body tr").remove();
                      // calculateGrandTotal();
                      refresh_tax_td();
                    }

                    // show_message('success-header',response.message);
                    CustomerToast.fire({
                      type: 'success',
                      title: response.message
                    });  
                  }
                  else
                  {
                    // show_message('success-header',response.message);
                    CustomerToast.fire({
                      type: 'success',
                      title: response.message
                    });  

                    location.reload(true);
                  }
                }
                else
                {
                  $('form#addCustomerForm #customerSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');

                  // show_message('failure-header',response.message);
                  CustomerToast.fire({
                    type: 'error',
                    title: response.message
                  });
                }
              },
              error: function () 
              { 
                $('form#addCustomerForm #customerSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');

                show_message('failure-header','Please contact the administrator if you are keep facing this issue.');   
              }
          });
        }    
      });
      $("form#addCustomerForm .field_validation").on("blur change keyup",  function (event){
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');
          
          if(value==null || value==""){
            $("form#addCustomerForm #err_"+id).text(field+ " field is required.");
            if($('form#addCustomerForm #'+id).hasClass('is-valid')){
              $('form#addCustomerForm #'+id).removeClass('is-valid');
            }
            $('form#addCustomerForm #'+id).addClass('is-invalid');
            return false;
          }
          else{
            $("form#addCustomerForm #err_"+id).text("");
            $('form#addCustomerForm #'+id).removeClass('is-invalid');
            $('form#addCustomerForm #'+id).addClass('is-valid');
          }
      });
      $(document).on('hidden.bs.modal','#add_customer_modal', function () {
        $('form#addCustomerForm .field_validation').each(function() {
          var id    = $(this).attr('id');
          $("form#addCustomerForm #"+id).val("");
          $("form#addCustomerForm #err_"+id).text("");
          $('form#addCustomerForm #'+id).removeClass('is-invalid');
          $('form#addCustomerForm #'+id).removeClass('is-valid');
        });
      });
      $('#add_customer_modal').on('shown.bs.modal', function () {

        $("form#addCustomerForm #customer_name ").focus();
        
        var company_country_id  = '<?=$this->company_settings_model->get_company_records()->country_id?>';

        $('form#addCustomerForm #country_id').html('<option value="">Select</option>');

        $.ajax({
          url: "<?php echo base_url('utility/get_countries') ?>/",
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            for(i=0;i<data.length;i++)
            {
              $('form#addCustomerForm #country_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }

            $('form#addCustomerForm #country_id').val(company_country_id).trigger('change');
          }
        });

        $('form#addCustomerForm #shipping_country_id').html('<option value="">Select</option>');

          $.ajax({
            url: "<?php echo base_url('utility/get_countries') ?>/",
            type: "GET",
            dataType: "JSON",
            success: function(data)
            {
              for(i=0;i<data.length;i++)
              {
                $('form#addCustomerForm #shipping_country_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
              }

              $('form#addCustomerForm #shipping_country_id').val(company_country_id).trigger('change');
            }
          });
        });

      $('form#addCustomerForm #country_id').change(function(){

        var id = $(this).val();

        var company_state_id    = '<?=$this->company_settings_model->get_company_records()->state_id?>';
        // alert(id);
        $('form#addCustomerForm #state_id').html('<option value="">Select</option>');
        $('form#addCustomerForm #city_id').html('<option value="">Select</option>');
        $.ajax({
          url: "<?php echo base_url('utility/get_states') ?>/"+id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            for(i=0;i<data.length;i++){
              $('form#addCustomerForm #state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }

            $('form#addCustomerForm #state_id').val(company_state_id).trigger('change');
          }
        });
      });

      $('form#addCustomerForm #state_id').change(function(){
        var id = $(this).val();
        
        $('form#addCustomerForm #city_id').html('<option value="">Select</option>');
        $.ajax({
          url: "<?php echo base_url('utility/get_cities') ?>/"+id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            for(i=0;i<data.length;i++){
              $('form#addCustomerForm #city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }
          }
        });
      });

      $('form#addCustomerForm #shipping_country_id').change(function(){

        var id = $(this).val();

        var company_state_id    = '<?=$this->company_settings_model->get_company_records()->state_id?>';
        // alert(id);
        $('form#addCustomerForm #shipping_state_id').html('<option value="">Select</option>');
        $('form#addCustomerForm #shipping_city_id').html('<option value="">Select</option>');

        $.ajax({
          url: "<?php echo base_url('utility/get_states') ?>/"+id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            for(i=0;i<data.length;i++){
              $('form#addCustomerForm #shipping_state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }

            $('form#addCustomerForm #shipping_state_id').val(company_state_id).trigger('change');
          }
        });
      });

      $('form#addCustomerForm #shipping_state_id').change(function(){
        var id = $(this).val();
        
        $('form#addCustomerForm #shipping_city_id').html('<option value="">Select</option>');

        $.ajax({
          url: "<?php echo base_url('utility/get_cities') ?>/"+id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            for(i=0;i<data.length;i++){
              $('form#addCustomerForm #shipping_city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }
          }
        });
      });

      $('form#addCustomerForm #copy_address').click(function(){

        if($('input[name="copy_address"]').is(':checked')){

          var country = $('form#addCustomerForm #country_id option:selected').val();
          if(country !="")
          {
            $('form#addCustomerForm #shipping_country_id').val(country).trigger('change');
          }

          setTimeout(function() {
            var state = $('form#addCustomerForm #state_id').val();
            if(state !="")
            {
              $('form#addCustomerForm #shipping_state_id').val(state).trigger('change');
            }
          }, 500);

          setTimeout(function() {
            var city = $('form#addCustomerForm #city_id').val();
            // alert(city);
            if(city !="")
            {
              $('form#addCustomerForm #shipping_city_id').val(city).trigger('change');
            }
          }, 800);
          
          
          $('form#addCustomerForm #shipping_country_id').val($('#country_id').val());
          $('form#addCustomerForm #shipping_city_id').val($('#city_id').val());
          $('form#addCustomerForm #shipping_address').val($('#address').val());
          $('form#addCustomerForm #shipping_pincode').val($('#pincode').val());
          /* alert(($('#state_id option:selected').val()));*/

        } else { 
          $('form#addCustomerForm #shipping_country_id').val("");;
          $('form#addCustomerForm #shipping_state_id').val("");;
          $('form#addCustomerForm #shipping_city_id').val("");;
          $('form#addCustomerForm #shipping_address').val("");
          $('form#addCustomerForm #shipping_pincode').val("");
          
        };
      });
  });
</script>

<div class="rcm-confirmation-modal">
  <div class="modal fade" id="rcm-confirmation">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header warning-header text-left">
          <h4 class="modal-title message_title"><?php echo $this->lang->line('message');?></h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body rcm-confirmation-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" id="rcm-confirmation-confirm">
            <?=$this->lang->line('confirm')?>
          </button>
          <button type="button" class="btn btn-default" id="rcm-confirmation-cancel" data-dismiss="modal">
            <?=$this->lang->line('close')?>
          </button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
  </div>
</div>