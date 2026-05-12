<?php 
  $this->load->view('layout/header');
  $currency = $this->company_settings_model->get_company_records()->currency_symbol;

  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);

  $mfg_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);

  $batch_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'batch_no', 'active',$row = true,$check_delete_status = false);
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

  .search_product::-webkit-input-placeholder {
    padding-left: 5px;
    font-size: 18px;
  }
  .search_product::-moz-placeholder {
    font-size: 18px;
  }
  .search_product:-ms-input-placeholder {
    font-size: 18px;
  }
  .search_product:-moz-placeholder {
    font-size: 18px;
  }
  
  .product_row{
    background: #fffee9 !important;
  }
  .highlight_row{
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

  .delete_item{
    cursor:pointer;
  }

  .total_data{
    font-size: 18px;
    font-weight: bolder;
  }

  /* Product Table Styling - No Scrollbars, All Columns Visible */
  .product_table {
    border-collapse: collapse;
    width: 100%;
    font-size: 13px;
    table-layout: fixed;
  }

  .product_table th, 
  .product_table td {
    padding: 8px 6px;
    vertical-align: middle;
    word-wrap: break-word;
    overflow: hidden;
  }

  .product_table th {
    background-color: #f4f4f4;
    font-weight: bold;
    text-align: center;
  }

  /* Fixed Column Widths - All Columns Fit on Page */
  .product_table th:nth-child(1) { width: 4%; }  /* Delete */
  .product_table th:nth-child(2) { width: 5%; }  /* Sr.No */
  .product_table th:nth-child(3) { width: 14%; } /* Product Name */
  .product_table th:nth-child(4) { width: 10%; } /* Add Remark */
  .product_table th:nth-child(5) { width: 7%; }  /* HSN */
  .product_table th:nth-child(6) { width: 7%; }  /* Purchase Qty */
  .product_table th:nth-child(7) { width: 5%; }  /* UOM */
  .product_table th:nth-child(8) { width: 9%; }  /* Product MRP */
  .product_table th:nth-child(9) { width: 9%; }  /* Price/Unit */
  .product_table th:nth-child(10) { width: 10%; } /* Discount */
  .product_table th:nth-child(11) { width: 9%; }  /* Taxable Value */
  .product_table th:nth-child(12) { width: 9%; }  /* Tax */
  .product_table th:nth-child(13) { width: 10%; }  /* Total Price */

  /* Input Styling */
  .product_table input,
  .product_table select,
  .product_table textarea {
    width: 100%;
    box-sizing: border-box;
  }

  .product_table input[type="number"],
  .product_table input[type="text"] {
    min-width: 70px;
  }

  .product_table textarea {
    resize: vertical;
    min-height: 40px;
    font-size: 12px;
  }

  /* Remove table-responsive scrollbar */
  .table-responsive {
    overflow-x: visible !important;
  }

  /* Product image styling */
  .product_table img {
    width: 35px;
    height: 35px;
    object-fit: cover;
    border-radius: 4px;
  }

  /* Tax column text size */
  .product_table .tax_td {
    font-size: 11px;
    line-height: 1.3;
  }

  /* Select2 full width */
  .product_table .select2-container {
    width: 100% !important;
  }

  /* Media query for smaller screens - adjust font sizes only */
  @media (max-width: 1200px) {
    .product_table {
      font-size: 12px;
    }
    .product_table input[type="number"],
    .product_table input[type="text"] {
      min-width: 60px;
    }
    .product_table th,
    .product_table td {
      padding: 6px 4px;
    }
  }
</style>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                  <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><a href="https://ossdemo.tastytap.in/product_core"><?=$this->lang->line('header_inventory')?></a></li>
         
                <li class="breadcrumb-item "><a href="<?=base_url('purchase')?>"><?=$this->lang->line('purchase_header')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('purchase_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editPurchaseForm" id="editPurchaseForm" method="POST" action="<?=base_url('purchase/edit')?>">
              <div class="card">
                <div class="card-header"><?=$this->lang->line('purchase_edit')?></h6>
                  <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">


                      
                      <li class="nav-item  ml-2">
                        <a class="nav-link reset btn-sm btn-warning" href="#" data-tt="tooltip" title="Click here to Reset Purchase Items">
                          <i class="fas fa-redo-alt"></i> Reset
                        </a>
                      </li>
                      


                      <li class="nav-item ml-2">
                        <a class="nav-link active" href="<?=base_url('purchase')?>" data-tt="tooltip" title="Click here to show purchase list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                      </li>

                    </ul>
                  </div>
                </div>
                <div class="card-body">
                  <div class="col-sm-12">
                    <div class="row">
                     <!-- <div class="col-sm-3">
                        <div class="form-group">
                          <label>Serial No</label>
                          <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?=$purchase->reference_no?>" readonly>
                        </div>
                      </div>-->
                    <!-- ADD THIS BLOCK FOR INVOICE NUMBER -->
<div class="col-sm-3">
    <div class="form-group">
        <label><?=$this->lang->line('purchase_invoice_no')?> <span class="text-danger">*</span></label>
        <input type="text" class="form-control field_validation" id="invoice_no" name="invoice_no" value="<?=$purchase->invoice_no?>" placeholder="<?=$this->lang->line('purchase_invoice_no')?>">
        <span id="err_invoice_no" class="error invalid-feedback"><?=form_error('invoice_no');?></span>
    </div>
</div>
<!-- END OF ADDED BLOCK -->
                    <div class="col-sm-3">
    <div class="form-group">
        <label><?=$this->lang->line('purchase_date')?> <span class="text-danger">*</span></label>
        <input type="text" class="form-control datepicker field_validation" id="purchase_date" name="purchase_date" value="<?=date('d-m-Y', strtotime($purchase->purchase_date))?>" placeholder="Invoice Date">
        <span id="err_purchase_date" class="error invalid-feedback"><?=form_error('purchase_date');?></span>
    </div>
</div>
                     <div class="col-sm-2">
                        <div class="form-group">
                            <label>Select Payment Terms</label>
                            <select class="form-control select2bs4" style="width: 100%;" id="due_days" name="due_days">
                                <option value="">Select Due Days</option>
                                <?php foreach($due_days_options as $option): ?>
                                    <option value="<?= $option->due_day ?>" 
                                        data-terms="<?= htmlspecialchars($option->terms_and_condition) ?>"
                                        <?= ($purchase->due_days == $option->due_day) ? 'selected' : '' ?>>
                                        <?= $option->due_day ?> Days
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <span id="err_due_days" class="error invalid-feedback"><?=form_error('due_days');?></span>
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <div class="form-group">
                            <label>Payment terms condition</label>
                            <div id="due_days_terms" class="form-control">
                                <?= $purchase->payment_terms ?>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="payment_terms" id="payment_terms" value="<?= $purchase->payment_terms ?>">
                    
                    <div class="col-sm-2">
                        <div class="form-group">
                            <label>Due Date</label>
                            <div id="due_date_display" class="form-control">
                                <?= date('d-m-Y', strtotime($purchase->due_date)) ?>
                            </div>
                            <input type="hidden" name="due_date" id="due_date" value="<?= date('d-m-Y', strtotime($purchase->due_date)) ?>">
                        </div>
                    </div>  
                      
                      
                      <!--<div class="col-sm-3">
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
                                  if($value->id == $purchase->warehouse_id)
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
                          <span id="err_warehouse_id" class="error invalid-feedback"><?=form_error('warehouse_id');?></span>
                        </div>
                      </div>-->
                          <div class="col-sm-3">
    <div class="form-group">
        <label for="warehouse">
            <?=$this->lang->line('purchase_order_warehouse')?>
            <span class="text-danger">*</span>
        </label>
        
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
</div>
                      
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="supplier"><?=$this->lang->line('purchase_supplier')?><span class="validation-color">*</span>
                          </label>
                          <div class="input-group input-group-sm">
                            <select class="form-control form-control-sm select2bs4 field_validation" name="supplier_id" id="supplier_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('purchase_supplier')?>">
                              <option value=""><?=$this->lang->line('select')?></option>
                              <?php
                                foreach ($supplier as $value) {
                              ?>
                                <option value="<?=$value->id;?>"
                                  <?php 
                                    if($value->id == $purchase->supplier_id)
                                      echo ' selected';
                                  ?>
                                >
                                  <?= $value->company_name;?>
                                </option>
                                
                              <?php 
                                }
                              ?>
                             
                          
                            </select>
                            <!-- <span class="input-group-append">
                              <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_supplier_modal" data-tt="tooltip" accesskey="c"><i class="fas fa-plus"></i></button>
                            </span> -->
                         
                          </div>
                         
                          <input type="hidden" name="supplier_state_id" id="supplier_state_id" value="<?=$supplier_detail->state_id?>">
                          <input type="hidden" name="supplier_country_id" id="supplier_country_id" value="<?=$supplier_detail->country_id?>">
                          <span id="err_supplie_id" class="error invalid-feedback"><?=form_error('supplie_id');?></span>
                        </div>
                      </div>
                      
                    </div>

                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                            <label><?=$this->lang->line('cdn_document')?></label>
                            <div class="input-group input-group-sm">
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="upload_document" name="upload_document[]" accept=".pdf" multiple>
                                    <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                                </div>
                                <input type="hidden" value="<?= $purchase->document ?>" name="document[]" id="document">
                            </div>
                            <span id="fileTypeError" class="error-message"></span>
                            <?php
                              $document_filenames = explode(',', $purchase->document);
                              foreach ($document_filenames as $filename){
                                if($filename != ''){
                            ?>
                            <div class="btn-group attached-file" style="margin-top:5px; margin-left:5px;margin-right:5px;">
                                <a href="#" class="btn btn-default"><?=$filename?></a>
                                <button type="button" class="btn btn-danger deleteAttachedFile" data-filename="<?=$filename?>">X</button>
                            </div>
                            <?php
                                }
                              }
                            ?>

                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 ">
                        <div class="well">
                         <div class="row">
                           <div class="col-sm-12">
                              <a href="" data-toggle="modal" data-tt="tooltip" data-target="#add_product_modal" class="float-right add_product_modal"><?=$this->lang->line('purchase_add_new_product')?></a>
                            </div>
                            <div class="col-sm-12 search">
                             <span class="fa fa-search"></span>
                             <input id="search_product" class="form-control search_product"  type="text" name="search_product"  placeholder="<?=$this->lang->line('purchase_item_search')?>">
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
                          <label><?=$this->lang->line('purchase_items')?></label>
                       <!--<div class="table-responsive" style="max-width: 100%; overflow-x: auto;">
  <table class="table items table-striped table-bordered table-condensed table-hover product_table" 
         style="min-width: 1200px; table-layout: auto;" id="product_data">
    <thead>
      <tr>
        <th width="5%">
          <img src="<?php echo base_url(); ?>assets/images/bin1.png" />
        </th>
        <th class="span2" width="15%"><?=$this->lang->line('product_description')?></th>
        <th class="span2" width="10%">Add Remark</th>
        <th class="span2" width="8%"><?=$this->lang->line('purchase_qty')?></th>
        <th class="span2" width="10%"><?=$this->lang->line('purchase_cost')?></th>
                <th class="span2" width="10%"><?=$this->lang->line('purchase_order_product_hsn')?></th>
        <th class="span2 d-none" width="100px"><?=$this->lang->line('product_selling_price')?></th>
        <th class="span2 d-none" width="100px"><?=$this->lang->line('purchase_price')?></th>
        <th class="span2 d-none" width="100px"><?=$this->lang->line('purchase_discount')?></th>
        <th class="span2" width="8%"><?=$this->lang->line('purchase_uom')?></th>
        <th class="span2" width="10%"><?=$this->lang->line('purchase_taxable_value')?></th>
        <th class="span2" width="12%"><?=$this->lang->line('purchase_tax')?></th>
        <th class="span2 d-none" width="100px"><?=$this->lang->line('purchase_inclusive')?></th>
        <th class="span2" width="8%"><?=$this->lang->line('purchase_total')?></th>
      </tr>
    </thead>
    <tbody id="product_table_body">
      <?php foreach ($purchase_items as $row): ?>
        <tr class="product_row">
          <td>
            <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>
            <input type="hidden" name="product_id" value="<?=$row->product_id?>">
            <input type="hidden" name="igst_rate" value="<?=$row->igst?>">
            <input type="hidden" name="cgst_rate" value="<?=$row->cgst?>">
            <input type="hidden" name="sgst_rate" value="<?=$row->sgst?>">
          </td>
          <td><span name="product_name"><?=$row->product_name?></span></td>
          <td><textarea name="product_remark" rows="1" cols="20"><?=$row->product_remark?></textarea></td>
          <td>
            <input type="number" class="form-control" name="quantity" value="<?=$row->quantity?>" step="0.01" min="0.01">
          </td>
          <td>
            <span id="cost_span">
              <input type="number" class="form-control text-right" name="cost" step="0.01" value="<?=$row->cost?>" min="1">
              <input type="hidden" class="form-control text-right" name="hidden_cost" step="0.01" value="<?=$row->cost?>" min="1">
            </span>
          </td>
            <td><span name="hsn"><?=$row->hsn ?></span></td>

          <td class="d-none">
            <span id="selling_price_span">
              <input type="number" class="form-control text-right" name="selling_price" step="0.01" value="<?=$row->selling_price?>">
              <input type="hidden" class="form-control text-right" name="hidden_selling_price" step="0.01" value="<?=$row->selling_price?>">
            </span>
          </td>
          <td class="d-none">
            <span id="price_span">
              <input type="number" class="form-control text-right" name="price" step="0.01" value="<?=$row->price?>">
              <input type="hidden" class="form-control text-right" name="hidden_price" step="0.01" value="<?=$row->price?>">
            </span>
          </td>
          <td class="d-none">
            <select class="form-control select2bs4" name="item_discount" style="width: 100%;">
              <option value="">Select</option>
              <?php foreach ($discounts as $value): ?>
                <option value="<?=$value->id?>" <?=($value->id == $row->discount_id) ? 'selected' : ''?>>
                  <?=$value->name.' ('.$value->value.($value->type == 0 ? $this->session->userdata('currency_symbol') : '%').')'?>
                </option>
              <?php endforeach; ?>
            </select>
            <span name="span_discount_type" class="discount_type">Discount Value : <?=$this->session->userdata('currency_symbol')?> </span>
            <input type="hidden" name="discount_type" value="<?=$row->discount_type?>">
            <span name="discount_amount" class="discount_amount"><?=$row->discount_amount?></span>
            <input type="hidden" name="discount_value" value="<?=$row->discount_value?>">
          </td>
          <td>
            <input type="hidden" name="uom_id" value="<?=$row->uom_id?>" data-uom_name="<?=$row->uom_name?>" data-uom_uom="<?=$row->uom_uom?>">
            <?=$row->uom_uom?>
          </td>
          <td><span name="taxable_value"><?=$row->taxable_value?></span></td>
          <td class="tax_td">
            <?php if($company_setting->country_id == $supplier_detail->country_id): ?>
              <?php if($company_setting->state_id == $supplier_detail->state_id): ?>
                <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">
                CGST : <span name="c_tax"><?=$row->cgst_tax?> </span> (<?= $row->cgst ?>%)<br/>
                SGST : <span name="s_tax"><?=$row->sgst_tax?>  </span>(<?= $row->sgst ?>%)
                <span name="i_tax" style="display:none"><?=$row->igst_tax?></span>
                <span name="igst" style="display:none"><?=$row->igst?></span>
              <?php else: ?>
                <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">
                IGST : <span name="i_tax"><?=$row->igst_tax?></span> (<?= $row->igst ?>%)<br/>
                <span name="c_tax" style="display:none"><?=$row->cgst_tax?></span>
                <span name="cgst" style="display:none"><?=$row->cgst?></span>
                <span name="s_tax" style="display:none"><?=$row->sgst_tax?></span>
                <span name="sgst" style="display:none"><?=$row->sgst?></span>
              <?php endif; ?>
            <?php else: ?>
              N/A
              <input type="hidden" name="tax_id" value="1">
              <span name="i_tax" style="display:none">0</span>
              <span name="igst" style="display:none">0</span>
              <span name="c_tax" style="display:none">0</span>
              <span name="cgst" style="display:none">0</span>
              <span name="s_tax" style="display:none">0</span>
              <span name="sgst" style="display:none">0</span>
            <?php endif; ?>
          </td>
          <td class="d-none">
            <input type="hidden" name="tax_type" value="<?=$row->tax_type?>">
            <?=($row->tax_type == 0) ? 'No' : 'Yes';?>
          </td>
          <td><span name="subtotal"><?=$row->subtotal?></span></td>
        </tr>
      <?php endforeach; ?>
      
      <?php if($purchase->additional_cost_type): ?>
        <tr class="product_row freight-row">
          <td>
            <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>
            <input type="hidden" name="freight_id" value="freight">
            <input type="hidden" name="freight_type" value="<?=$purchase->additional_cost_type?>">
            <input type="hidden" name="freight_tax_rate" value="0">
          </td>
          <td><span name="freight_name">Freight Charges (<?=$purchase->additional_cost_type?>)</span></td>
          <td><textarea name="freight_remark" rows="1" cols="20">Transport charges</textarea></td>
          <td><input type="number" class="form-control" name="freight_quantity" value="1" min="1" readonly></td>
          <td>
            <input type="number" class="form-control freight-amount" name="freight_cost" 
                   value="<?=$purchase->freight_amount?>" step="0.01" min="0">
          </td>
           <td>NA</td>
          <td class="d-none">0.00</td>
          <td class="d-none">0.00</td>
          <td class="d-none"></td>
          <td>NA</td>
          <td><span name="freight_taxable_value"><?=$purchase->freight_taxable_value?></span></td>
          <td class="tax_td">N/A</td>
          <td class="d-none">No</td>
          <td><span name="freight_sub_total"><?=$purchase->freight_sub_total?></span></td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</div>-->

<div class="table-responsive">
  <table class="table items table-striped table-bordered table-condensed table-hover product_table" 
          id="product_data">
    <thead>
      <tr>
        <th width="3%"><i class="fas fa-trash-alt"></i></th>
        <th width="3%">Sr.No</th>
        <th width="15%">Product Name</th>
        <th width="10%">Add Remark</th>
        <th width="8%">HSN</th>
        <th width="8%"><?=$this->lang->line('purchase_qty')?></th>
        <th width="8%"><?=$this->lang->line('purchase_uom')?></th>
        <th width="10%">Product MRP</th>
        <th width="10%">Price/Unit</th>
        <th width="10%"><?=$this->lang->line('purchase_discount')?></th>
        <th width="10%"><?=$this->lang->line('purchase_taxable_value')?></th>
        <th width="12%"><?=$this->lang->line('purchase_tax')?></th>
        <th width="10%">Total Price</th>
      <tr>
    </thead>
   <!-- <tbody id="product_table_body">
      <?php 
      $sr_no = 1;
      foreach ($purchase_items as $row): 
      ?>
        <tr class="product_row">
          <td class="text-center">
            <span class="delete_item"><i class="fas fa-minus-circle text-danger" style="cursor:pointer; font-size:18px;"></i></span>
            <input type="hidden" name="product_id" value="<?=$row->product_id?>">
            <input type="hidden" name="igst_rate" value="<?=$row->igst?>">
            <input type="hidden" name="cgst_rate" value="<?=$row->cgst?>">
            <input type="hidden" name="sgst_rate" value="<?=$row->sgst?>">
          </td>
          <td class="text-center sr-no"><?=$sr_no++?></td>
          <td>
            <img src="<?= base_url('assets/product_images').'/'; ?><?=$row->product_image?>" style="width:40px; height:40px; object-fit:cover; margin-right:5px; border-radius:4px;">
            <span name="product_name"><?=$row->product_name?></span>
          </td>
          <td><textarea name="product_remark" class="form-control product-remark" rows="2" placeholder="Add remark..." style="width:100%;"><?=$row->product_remark?></textarea></td>
          <td><span name="hsn"><?=$row->hsn ?: 'NA'?></span></td>
          <td class="text-right">
            <input type="number" class="form-control text-right item-quantity" name="quantity" value="<?=$row->quantity?>" step="0.01" min="0.01">
          </td>
          <td class="text-center">
            <input type="hidden" name="uom_id" value="<?=$row->uom_id?>" data-uom_name="<?=$row->uom_name?>" data-uom_uom="<?=$row->uom_uom?>">
            <?=$row->uom_uom?>
          </td>
          <td class="text-right">
            <input type="number" class="form-control text-right item-mrp" name="selling_price" step="0.01" value="<?=$row->selling_price?>" min="0">
            <input type="hidden" name="hidden_selling_price" value="<?=$row->selling_price?>">
          </td>
          <td class="text-right">
            <input type="number" class="form-control text-right item-cost" name="cost" step="0.01" value="<?=$row->cost?>" min="0">
            <input type="hidden" name="hidden_cost" value="<?=$row->cost?>">
          </td>
          <td>
            <select class="form-control select2bs4 item-discount-select" name="item_discount" style="width: 100%;">
              <option value="">Select Discount</option>
              <?php foreach ($discounts as $value): ?>
                <option value="<?=$value->id?>" <?=($value->id == $row->discount_id) ? 'selected' : ''?>>
                  <?=$value->name.' ('.$value->value.($value->type == 0 ? $this->session->userdata('currency_symbol') : '%').')'?>
                </option>
              <?php endforeach; ?>
            </select>
            <input type="hidden" name="discount_type" value="<?=$row->discount_type?>">
            <input type="hidden" name="discount_value" value="<?=$row->discount_value?>">
            <span name="discount_amount" class="discount_amount text-info" style="display:block; font-size:12px;">Discount: <?=$row->discount_amount?></span>
          </td>
          <td class="text-right"><span name="taxable_value" class="taxable-value"><?=$row->taxable_value?></span></td>
          <td class="tax_td">
            <?php if($company_setting->country_id == $supplier_detail->country_id): ?>
              <?php if($company_setting->state_id == $supplier_detail->state_id): ?>
                <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">
                CGST: <span name="c_tax"><?=$row->cgst_tax?></span>%<br/>
                SGST: <span name="s_tax"><?=$row->sgst_tax?></span>%
                <span name="i_tax" style="display:none"><?=$row->igst_tax?></span>
                <span name="igst" style="display:none"><?=$row->igst?></span>
                <span name="cgst" style="display:none"><?=$row->cgst?></span>
                <span name="sgst" style="display:none"><?=$row->sgst?></span>
              <?php else: ?>
                <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">
                IGST: <span name="i_tax"><?=$row->igst_tax?></span>%<br/>
                <span name="c_tax" style="display:none"><?=$row->cgst_tax?></span>
                <span name="cgst" style="display:none"><?=$row->cgst?></span>
                <span name="s_tax" style="display:none"><?=$row->sgst_tax?></span>
                <span name="sgst" style="display:none"><?=$row->sgst?></span>
                <span name="igst" style="display:none"><?=$row->igst?></span>
              <?php endif; ?>
            <?php else: ?>
              N/A
              <input type="hidden" name="tax_id" value="1">
              <span name="i_tax" style="display:none">0</span>
              <span name="igst" style="display:none">0</span>
              <span name="c_tax" style="display:none">0</span>
              <span name="cgst" style="display:none">0</span>
              <span name="s_tax" style="display:none">0</span>
              <span name="sgst" style="display:none">0</span>
            <?php endif; ?>
            <input type="hidden" name="tax_type" value="<?=$row->tax_type?>">
          </td>
          <td class="text-right"><span name="subtotal" class="item-total" style="font-weight:bold;"><?=$row->subtotal?></span></td>
        </tr>
      <?php endforeach; ?>
      
      <?php if($purchase->additional_cost_type): ?>
        <tr class="product_row freight-row">
          <td class="text-center">
            <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>
            <input type="hidden" name="freight_id" value="freight">
            <input type="hidden" name="freight_type" value="<?=$purchase->additional_cost_type?>">
            <input type="hidden" name="freight_tax_rate" value="0">
          </td>
          <td class="text-center sr-no"><?=$sr_no++?></td>
          <td><span name="freight_name">Freight Charges (<?=$purchase->additional_cost_type?>)</span></td>
          <td><textarea name="freight_remark" rows="1" cols="20">Transport charges</textarea></td>
          <td>NA</td>
          <td class="text-right"><input type="number" class="form-control" name="freight_quantity" value="1" min="1" readonly></td>
          <td class="text-center">NA</td>
          <td class="text-right">0.00</td>
          <td class="text-right"><input type="number" class="form-control freight-amount" name="freight_cost" value="<?=$purchase->freight_amount?>" step="0.01" min="0"></td>
          <td></td>
          <td class="text-right"><span name="freight_taxable_value"><?=$purchase->freight_taxable_value?></span></td>
          <td class="tax_td">N/A</td>
          <td class="text-right"><span name="freight_sub_total"><?=$purchase->freight_sub_total?></span></td>
        </tr>
      <?php endif; ?>
    </tbody>-->
    <tbody id="product_table_body">
  <?php 
  $sr_no = 1;
  foreach ($purchase_items as $row): 
  ?>
    <tr class="product_row">
      <td class="text-center">
        <span class="delete_item"><i class="fas fa-minus-circle text-danger" style="cursor:pointer; font-size:18px;"></i></span>
        <input type="hidden" name="product_id" value="<?=$row->product_id?>">
        <input type="hidden" name="igst_rate" value="<?=$row->igst?>">
        <input type="hidden" name="cgst_rate" value="<?=$row->cgst?>">
        <input type="hidden" name="sgst_rate" value="<?=$row->sgst?>">
      </td>
      <td class="text-center sr-no"><?=$sr_no++?></td>
      <td>
        <?php if(isset($row->product_image) && $row->product_image): ?>
          <img src="<?= base_url('assets/product_images').'/'; ?><?=$row->product_image?>" alt="<?=$row->product_name?>" style="width:40px; height:40px; object-fit:cover; margin-right:5px; border-radius:4px;">
        <?php endif; ?>
        <span name="product_name"><?=$row->product_name?></span>
      </td>
      <td><textarea name="product_remark" class="form-control product-remark" rows="2" placeholder="Add remark..." style="width:100%;"><?=$row->product_remark?></textarea></td>
      <td><span name="hsn"><?=$row->hsn ?: 'NA'?></span></td>
      <td class="text-right">
        <input type="number" class="form-control text-right item-quantity" name="quantity" value="<?=$row->quantity?>" step="0.01" min="0.01">
      </td>
      <td class="text-center">
        <input type="hidden" name="uom_id" value="<?=$row->uom_id?>" data-uom_name="<?=$row->uom_name?>" data-uom_uom="<?=$row->uom_uom?>">
        <?=$row->uom_uom?>
      </td>
      <td class="text-right">
        <input type="number" class="form-control text-right item-mrp" name="selling_price" step="0.01" value="<?=$row->selling_price?>" min="0">
        <input type="hidden" name="hidden_selling_price" value="<?=$row->selling_price?>">
      </td>
      <td class="text-right">
        <input type="number" class="form-control text-right item-cost" name="cost" step="0.01" value="<?=$row->cost?>" min="0">
        <input type="hidden" name="hidden_cost" value="<?=$row->cost?>">
      </td>
      <td>
        <select class="form-control select2bs4 item-discount-select" name="item_discount" style="width: 100%;">
          <option value="">Select Discount</option>
          <?php foreach ($discounts as $value): ?>
            <option value="<?=$value->id?>" <?=($value->id == $row->discount_id) ? 'selected' : ''?>>
              <?=$value->name.' ('.$value->value.($value->type == 0 ? $this->session->userdata('currency_symbol') : '%').')'?>
            </option>
          <?php endforeach; ?>
        </select>
        <input type="hidden" name="discount_type" value="<?=$row->discount_type?>">
        <input type="hidden" name="discount_value" value="<?=$row->discount_value?>">
        <span name="discount_amount" class="discount_amount text-info" style="display:block; font-size:12px;">Discount: <?=$row->discount_amount?></span>
      </td>
      <td class="text-right"><span name="taxable_value" class="taxable-value"><?=$row->taxable_value?></span></td>
      <td class="tax_td">
        <?php if($company_setting->country_id == $supplier_detail->country_id): ?>
          <?php if($company_setting->state_id == $supplier_detail->state_id): ?>
            <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">
            CGST: <span name="c_tax"><?=$row->cgst_tax?></span>%<br/>
            SGST: <span name="s_tax"><?=$row->sgst_tax?></span>%
            <span name="i_tax" style="display:none"><?=$row->igst_tax?></span>
            <span name="igst" style="display:none"><?=$row->igst?></span>
            <span name="cgst" style="display:none"><?=$row->cgst?></span>
            <span name="sgst" style="display:none"><?=$row->sgst?></span>
          <?php else: ?>
            <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">
            IGST: <span name="i_tax"><?=$row->igst_tax?></span>%<br/>
            <span name="c_tax" style="display:none"><?=$row->cgst_tax?></span>
            <span name="cgst" style="display:none"><?=$row->cgst?></span>
            <span name="s_tax" style="display:none"><?=$row->sgst_tax?></span>
            <span name="sgst" style="display:none"><?=$row->sgst?></span>
            <span name="igst" style="display:none"><?=$row->igst?></span>
          <?php endif; ?>
        <?php else: ?>
          N/A
          <input type="hidden" name="tax_id" value="1">
          <span name="i_tax" style="display:none">0</span>
          <span name="igst" style="display:none">0</span>
          <span name="c_tax" style="display:none">0</span>
          <span name="cgst" style="display:none">0</span>
          <span name="s_tax" style="display:none">0</span>
          <span name="sgst" style="display:none">0</span>
        <?php endif; ?>
        <input type="hidden" name="tax_type" value="<?=$row->tax_type?>">
      </td>
      <td class="text-right"><span name="subtotal" class="item-total" style="font-weight:bold;"><?=$row->subtotal?></span></td>
    </tr>
  <?php endforeach; ?>
  
  <?php if($purchase->additional_cost_type): ?>
    <tr class="product_row freight-row">
      <td class="text-center">
        <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>
        <input type="hidden" name="freight_id" value="freight">
        <input type="hidden" name="freight_type" value="<?=$purchase->additional_cost_type?>">
        <input type="hidden" name="freight_tax_rate" value="0">
      </td>
      <td class="text-center sr-no"><?=$sr_no++?></td>
      <td><span name="freight_name">Freight Charges (<?=$purchase->additional_cost_type?>)</span></td>
      <td><textarea name="freight_remark" rows="1" cols="20">Transport charges</textarea></td>
      <td>NA</td>
      <td class="text-right"><input type="number" class="form-control" name="freight_quantity" value="1" min="1" readonly></td>
      <td class="text-center">NA</td>
      <td class="text-right">0.00</td>
      <td class="text-right"><input type="number" class="form-control freight-amount" name="freight_cost" value="<?=$purchase->freight_amount?>" step="0.01" min="0"></td>
      <td></td>
      <td class="text-right"><span name="freight_taxable_value"><?=$purchase->freight_taxable_value?></span></td>
      <td class="tax_td">N/A</td>
      <td class="text-right"><span name="freight_sub_total"><?=$purchase->freight_sub_total?></span></td>
    </tr>
  <?php endif; ?>
</tbody>
  </table>
</div>

<table class="table table-striped table-bordered table-condensed table-hover total_data">
  <tr>
    <td align="right" width="66%">
      <select class="form-control" id="additional_cost_type" name="additional_cost_type">
        <option value="">Select Transport</option>
        <option value="internal" <?=($purchase->additional_cost_type == 'internal') ? 'selected' : ''?>>Internal</option>
      </select>
    </td>
    <td align='right' width="34%">
      <input type="number" class="form-control" id="additional_cost_amount" name="additional_cost_amount" 
             value="<?=$purchase->additional_cost_amount?>" step="0.01">
    </td>
  </tr>

  <tr>
    <td align="right" width="66%">Total Taxable Value (₹)</td>
    <td align='right' class="text-success" width="34%">
      +<span id="total_taxable_value"><?=number_format($purchase->total_taxable_value, 2)?></span>
      <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="<?=$purchase->total_taxable_value?>">
    </td>
  </tr>

  <tr>
    <td align="right" width="66%">Total Product Tax (₹)</td>
    <td align='right' class="text-success" width="34%">
      +<span id="total_tax"><?=number_format($purchase->total_tax, 2)?></span>
      <input type="hidden" name="total_tax" id="t_tax" value="<?=$purchase->total_tax?>">
    </td>
  </tr>

  <tr>
    <td align="right" width="66%">Total (₹)</td>
    <td align='right' width="34%">
      <span id="total"><?=number_format($purchase->total, 2)?></span>
      <input type="hidden" name="total" id="t" value="<?=$purchase->total?>">
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
                                  <a class="nav-link active" href="#external_note" data-toggle="pill"><?php echo $this->lang->line('purchase_external_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#internal_note" data-toggle="pill"><?php echo $this->lang->line('purchase_internal_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#bank_detail" data-toggle="pill"><?php echo $this->lang->line('purchase_bank_detail'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#terms_and_condition" data-toggle="pill"><?php echo $this->lang->line('purchase_terms_and_condition'); ?></a>
                                </li>
                              </ul>                           
                              <br>
                              <div class="tab-content">
                                <div class="tab-pane active" id="external_note">
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('purchase_external_note'); ?>"><?=str_replace("<br />","",$purchase->external_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('purchase_internal_note'); ?>"><?=str_replace("<br />","",$purchase->internal_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="bank_detail">
                                  <textarea class="col-sm-12 form-control" name="bank_detail" rows="4" placeholder="<?php echo $this->lang->line('purchase_bank_detail'); ?>"><?=str_replace("<br />","",$purchase->bank_detail)?></textarea>
                                </div>
                                <div class="tab-pane" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('purchase_terms_and_condition'); ?>"><?=str_replace("<br />","",$purchase->terms_and_condition)?></textarea>
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
                  <input type="hidden" name="id" value="<?=$purchase->id?>">
                  <input type="hidden" name="purchase_items" id="purchase_items" value="">
                  <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  <button type="submit" name="submit" id="PurchaseSubmit" class="btn btn-info"><?=$this->lang->line('purchase_add')?></button>
                  <!-- <button type="submit" name="submit" id="PurchaseSubmitPayNow" value="pay" name="pay" class="btn btn-info">Add purchase & Pay Now</button>                      -->
                  <span class="btn btn-default float-right" id="cancel" onclick="cancel('purchase')"><?=$this->lang->line('purchase_cancel')?></span>
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
  // $this->load->view('supplier/add_supplier_modal');
  /*$this->load->view('supplier/add_supplier_modal');
  $this->load->view('warehouse/add_warehouse_modal');*/
?>

<div class="modal fade" id="emptyPurchaseItemWarningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header warning-header">
        <h5 class="modal-title" id="exampleModalLabel">Message</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        There are no item(s) in Invoice. Please atleast 1 item in invoice.
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="add_product_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="add_product_category_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="add_tax_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
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
            <?php echo "Are you sure want to reset this purchase ?";?>
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

     const product_categoryToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    const productToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    const taxToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    $(document).on('click', "#resetSubmit" ,function(e){
      e.preventDefault();

      $('span.delete_item').trigger('click');
       
      $('#supplier_id').val('');

      $('#supplier_id').trigger('change');
      $('#reset_model').modal('hide');
   
    });


    $(document).on('click', ".reset" ,function(){
      $('#reset_model').modal('show');
    });

    $(document).on('click', ".add_product_modal" ,function(){
   
      $.ajax({
      url: "<?php echo base_url('product_core/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_product_modal').find('.modal-content').html(data.add_product_modal_body);
          $('#add_product_modal').modal('show');
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });


    $(document).on('submit','#addProductForm',function(e){
      
      e.preventDefault();

      $('#addProductSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addProductForm').serialize();

      var isError = false;

      $('form#addProductForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addProductForm  #err_"+id).text(field+ " field is required.");
            $('form#addProductForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addProductForm #err_"+id).text("");
            $('form#addProductForm #'+id).removeClass('is-invalid');
            $('form#addProductForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('product/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
            $('#add_product_modal').modal('hide');
              $('form#addProductForm #addProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              //////initialize_datatable();

              productToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              productToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addProductForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addProductForm #err_"+id).text(field+ " field is required.");
          $('form#addProductForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addProductForm #err_"+id).text("");
          $('form#addProductForm #'+id).removeClass('is-invalid');
          $('form#addProductForm #'+id).addClass('is-valid');
        }
    });

     $(document).on('click', ".add_product_category_modal" ,function(){

     $.ajax({
        url: "<?php echo base_url('product_category/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_product_category_modal').find('.modal-content').html(data.add_product_category_modal_body);
          $('#add_product_category_modal').modal('show');
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','#addProduct_categoryForm',function(e){
      
      e.preventDefault();

      $('#addProduct_categorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addProduct_categoryForm').serialize();

      var isError = false;

      $('form#addProduct_categoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addProduct_categoryForm  #err_"+id).text(field+ " field is required.");
            $('form#addProduct_categoryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addProduct_categoryForm #err_"+id).text("");
            $('form#addProduct_categoryForm #'+id).removeClass('is-invalid');
            $('form#addProduct_categoryForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('product_category/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
            $('#add_product_category_modal').modal('hide');
              $('form#addProduct_categoryForm #addProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
             /* initialize_datatable();*/
              if($('form#addProductForm #product_category_id').length)
              {
                $('form#addProductForm #product_category_id').html('');
                $('form#addProductForm #product_category_id').append('<option value="">Select</option>');
                
                for(i=0;i<response['product_categories'].length;i++)
                { 
                  $('form#addProductForm #product_category_id').append('<option value="' + response['product_categories'][i].id + '">' + response['product_categories'][i].name +'</option>');
                }

                $('form#addProductForm #product_category_id').val(response['id']).attr("selected","selected");


                product_categoryToast.fire({
                  type: 'success',
                  title: response.message
                });
              }
              else
              {
                // show_message('success-header',response.message);
                product_categoryToast.fire({
                  type: 'success',
                  title: response.message
                });  

                location.reload(true);
              }
            }
            else
            {
              product_categoryToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on('click', ".add_tax_modal" ,function(){

     $.ajax({
        url: "<?php echo base_url('tax/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_tax_modal').find('.modal-content').html(data.add_tax_modal_body);
          $('#add_tax_modal').modal('show');
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });


    $(document).on('submit','#addTaxForm',function(e){
      
      e.preventDefault();

      $('#taxSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addTaxForm').serialize();

      var isError = false;

      $('form#addTaxForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addTaxForm  #err_"+id).text(field+ " field is required.");
            $('form#addTaxForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addTaxForm #err_"+id).text("");
            $('form#addTaxForm #'+id).removeClass('is-invalid');
            $('form#addTaxForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        var formData = $('#addTaxForm').serialize();
        $('#taxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('tax/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
              {
                $('#add_tax_modal').modal('hide');
                /*$('#add_product_category_modal').modal('hide');*/
                $('#taxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
                
                if($('form#addProduct_categoryForm #tax_id').length)
                {
                  $('form#addProduct_categoryForm #tax_id').html('');
                  $('form#addProduct_categoryForm #tax_id').append('<option value="">Select</option>');
                  
                  for(i=0;i<response['taxes'].length;i++)
                  { 
                    $('form#addProduct_categoryForm #tax_id').append('<option value="' + response['taxes'][i].id + '">' + response['taxes'][i].tax_name + ' ( I : ' + response['taxes'][i].igst + ' | C : ' +response['taxes'][i].cgst + ' | S : ' + response['taxes'][i].sgst + ' ) ' +'</option>');

                    /*<?=$value->tax_name.' ( I : '.$value->igst.' | C : '.$value->cgst.' | S : '.$value->sgst.' )'?>*/
                  }

                  $('form#addProduct_categoryForm #tax_id').val(response['id']).attr("selected","selected");  

                  // show_message('success-header',response.message);
                  TaxToast.fire({
                    type: 'success',
                    title: response.message
                  });  

                }
                else
                {
                  // show_message('success-header',response.message);
                  TaxToast.fire({
                    type: 'success',
                    title: response.message
                  });  

                  location.reload(true);
                }
              }
            else
            {
              TaxToast.fire({
                type: 'error',
                title: response.message
              });
              $('#taxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addTaxForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addTaxForm #err_"+id).text(field+ " field is required.");
          $('form#addTaxForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addTaxForm #err_"+id).text("");
          $('form#addTaxForm #'+id).removeClass('is-invalid');
          $('form#addTaxForm #'+id).addClass('is-valid');
        }
    });


      // $("#supplier_id").closest('.row').siblings().css('display','none');

      // $("#supplier_id").select2({disabled:readonly});

     
      $('#warehouse_id').change(function(e){

        var warehouse_id = $(this).val();
        if(warehouse_id != '')
        {
          if($('#supplier_id').val() != '')
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

      $('#supplier_id').change(function(e){

        var supplier_id = $(this).val();
        if(supplier_id != '')
        {
          if($('#warehouse_id').val() != '')
          {
            $("#supplier_id").closest('.row').siblings().fadeIn(10);  
          }

          $.ajax({
              url: "<?php echo base_url('supplier/get_record_detail') ?>",
              type: "POST",
              dataType: "json",
              data: {
                  'supplier_id' : supplier_id,
                  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var supplier = data.supplier;
                $('#supplier_state_id').val(supplier.state_id);
                $('#supplier_country_id').val(supplier.country_id);
                $('#supplier_gstin').val(supplier.gstin);
                // $('#supplier_id').val(supplier.id);

              

                refresh_tax_td();
              
                // $("#product_table_body tr").remove();
                // calculateGrandTotal();
              }
          });
        }
        else
        {
          $("#supplier_id").closest('.row').siblings().css('display','none');
        }
      }); 


      function refresh_tax_td()
      {
        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var supplier_state_id   = $('#supplier_state_id').val();
        var supplier_country_id = $('#supplier_country_id').val();
        
        $("#product_table_body").find('tr').each(function () {
          var tr              = $(this).closest("tr");
          var tax_td          = tr.find('.tax_td');
          // var igst            = parseFloat(tax_td.find('span[name="igst"]').text());
          // var cgst            = parseFloat(tax_td.find('span[name="cgst"]').text());
          // var sgst            = parseFloat(tax_td.find('span[name="sgst"]').text());

          var igst            = 0;
          var cgst            = 0;
          var sgst            = 0;

          if(company_country_id == supplier_country_id)
          {
            if(company_state_id == supplier_state_id)
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

          if(company_country_id == supplier_country_id)
          { 
            if(company_state_id == supplier_state_id)
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
      // product search code begin

      var c_mapping = { };

      $(function(){
        $('#search_product').autoComplete({
          minChars: 1,
          source: function(term, suggest){
            term = term.toLowerCase();

            $.ajax({
              url: "<?php echo base_url('product/search') ?>",
              type: "POST",
              dataType: "json",
              data:{
                'term': term,
                'module': '<?=PURCHASE_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var products = data;
                var suggestions = [];
                for(var i = 0; i < products.length; ++i) {
                    suggestions.push(products[i].id+' - '+products[i].name+' - '+products[i].product_category_name);
                    c_mapping[products[i].id] = products[i].name;
                }
                suggest(suggestions);
              }
            });
          },
          onSelect: function(event, ui) {
            var str = ui.split(' - ');
            
            var product_id = str[0];

            $.ajax({
              url: "<?php echo base_url('product/get_record_detail') ?>",
              type: "POST",
              dataType: "json",
              data:{
                'product_id': product_id, 
                'module': '<?=PURCHASE_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var product = data.product;

                if(!is_product_exist_in_row(product.id))
                {
                  add_row(data);
                }
                else
                {
                  highlight_row(product.id);
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
              }
            });
          } 
        });
      });
      
      // Function to re-index Sr.No after row deletion
function reindexSerialNumbers() {
    $("#product_table_body tr").each(function(index) {
        $(this).find('.sr-no').text(index + 1);
    });
}

   /*     function add_row(data)
{
  var supplier_country_id = $('#supplier_country_id').val();
  var supplier_state_id   = $('#supplier_state_id').val();
  var supplier_gstin      = $('#supplier_gstin').val();

  var company_country_id  = $('#company_country_id').val();
  var company_state_id    = $('#company_state_id').val();
  var company_gstin       = '<?=$company_setting->gstin?>';

  var product   = data.product;
  var discounts = data.discount;
 console.log('Full product data:', product);
  // Build discount dropdown with proper formatting
  var select_discount = "";
  select_discount += '<select class="form-control select2bs4" name="item_discount" style="width: 100%;">';
  select_discount += '<option value="">Select</option>';
  for(a=0;a<discounts.length;a++)
  {
    var type_symbol;
    if(discounts[a].type == 0)
    {
      type_symbol = "<?=$this->session->userdata('currency_symbol')?>";
    }
    else
    {
      type_symbol = "%"; 
    }
    select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name+' ('+discounts[a].value  + type_symbol +')'+ '</option>';
  }
  select_discount += '</select>'
  select_discount += '<span name="span_discount_type" class="discount_type">Discount Value : <?=$this->session->userdata('currency_symbol');?> </span>';
  select_discount += '<input type="hidden" name="discount_type" value="0">';
  select_discount += '<span name="discount_amount" class="discount_amount">0.00</span>';
  select_discount += '<input type="hidden" name="discount_value" value="0">';

  var input_uom = '<input type="hidden" name="uom_id" value="'+product.uom_id+'" data-uom_name="'+product.uom_name+'" data-uom_uom="'+product.uom_uom+'">'+product.uom_uom;

  var input_quantity = '<input type="number" class="form-control" name="quantity" value="1" step="0.01" min="0.01"><span name="quantity_update_message" class="quantity_update_message"></span>';
  var taxable_value = '<span name="taxable_value">'+product.cost+'</span>';

  var tax = '<input type="hidden" name="tax_id" value="'+product.tax_id+'">';

  if(company_country_id == supplier_country_id)
  {
    if(company_state_id == supplier_state_id)
    {
      tax += 'CGST : <span name="c_tax">0.00</span> (<span name="cgst">'+product.cgst+'</span>%)<br/>';
      tax += 'SGST : <span name="s_tax">0.00</span> (<span name="sgst">'+product.sgst+'</span>%)';
      tax += '<span name="i_tax" style="display:none">0</span>';
      tax += '<span name="igst" style="display:none">0</span>';
    }
    else
    {
      tax += 'IGST : <span name="i_tax">0.00</span> (<span name="igst">'+product.igst+'</span>%)<br/>';
      tax += '<span name="c_tax" style="display:none">0</span>';
      tax += '<span name="cgst" style="display:none">0</span>';
      tax += '<span name="s_tax" style="display:none">0</span>';
      tax += '<span name="sgst" style="display:none">0</span>';
    }
  }
  else
  {
    tax += 'N/A';
    tax += '<span name="i_tax" style="display:none">0</span>';
    tax += '<span name="igst" style="display:none">0</span>';
    tax += '<span name="c_tax" style="display:none">0</span>';
    tax += '<span name="cgst" style="display:none">0</span>';
    tax += '<span name="s_tax" style="display:none">0</span>';
    tax += '<span name="sgst" style="display:none">0</span>';
  }

  var tax_type = '<input type="hidden" name="tax_type" value="'+product.tax_type+'">';
  tax_type += (product.tax_type == 0) ? "No" : "Yes";

  var newRow = $('<tr class="product_row">');
  var cols = "";

  cols += '<td>'
            + '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
            + '<input type="hidden" name="product_id" value="'+product.id+'">'
            + '<input type="hidden" name="igst_rate" value="'+product.igst+'">'
            + '<input type="hidden" name="cgst_rate" value="'+product.cgst+'">'
            + '<input type="hidden" name="sgst_rate" value="'+product.sgst+'">'
          +'</td>';
          
  cols += '<td>'
          + '<span name="product_name">'+product.name+'</span>'
          + '<br/><small><span name="description">'+(product.description ? product.description : '')+'</span></small>'
        +'</td>';
        
  cols += '<td><textarea name="product_remark" rows="1" cols="20"></textarea></td>';
  cols += '<td>'+input_quantity+'</td>';
  cols += '<td>'
            +'<span id="cost_span">'
              +'<input type="number" class="form-control text-right" name="cost" step="0.01" value="'+product.cost+'" min="1">'
              +'<input type="hidden" class="form-control text-right" name="hidden_cost" step="0.01" value="'+product.cost+'" min="1">'
            +'</span>'
          +'</td>';
  cols += '<td><span name="hsn">'+(product.hsn ? product.hsn : 'NA')+'</span></td>';
  
  // Hidden columns (selling_price, price, discount)
  cols += '<td class="d-none">'
            +'<span id="selling_price_span">'
              +'<input type="number" class="form-control text-right" name="selling_price" step="0.01" value="'+product.selling_price+'">'
              +'<input type="hidden" class="form-control text-right" name="hidden_selling_price" step="0.01" value="'+product.selling_price+'">'
            +'</span>'
          +'</td>';
  cols += '<td class="d-none">'
            +'<span id="price_span">'
              +'<input type="number" class="form-control text-right" name="price" step="0.01" value="'+product.price+'">'
              +'<input type="hidden" class="form-control text-right" name="hidden_price" step="0.01" value="'+product.price+'">'
            +'</span>'
          +'</td>';
  cols += '<td class="d-none">'+select_discount+'</td>';
  
  cols += '<td>'+input_uom+'</td>';
  cols += '<td>'+taxable_value+'</td>';
  cols += '<td class="tax_td">'+tax+'</td>';
  cols += '<td class="d-none">'+tax_type+'</td>';
  cols += '<td><span name="subtotal"></span></td>';

  newRow.append(cols);
  $("table.product_table tbody#product_table_body").append(newRow);
  $('.select2bs4').select2({theme: 'bootstrap4'});

  // Calculate the row after adding
  calculateRow(newRow);
}*/


function add_row(data)
{
  var supplier_country_id = $('#supplier_country_id').val();
  var supplier_state_id   = $('#supplier_state_id').val();
  var supplier_gstin      = $('#supplier_gstin').val();

  var company_country_id  = $('#company_country_id').val();
  var company_state_id    = $('#company_state_id').val();
  var company_gstin       = '<?=$company_setting->gstin?>';

  var product   = data.product;
  var discounts = data.discount;
  
  // Get current row count for Sr.No
  var rowCount = $("#product_table_body tr").length + 1;

  // Build discount dropdown with proper formatting
  var select_discount = "";
  select_discount += '<select class="form-control select2bs4 item-discount-select" name="item_discount" style="width: 100%;">';
  select_discount += '<option value="">Select Discount</option>';
  for(a=0;a<discounts.length;a++)
  {
    var type_symbol;
    if(discounts[a].type == 0)
    {
      type_symbol = "<?=$this->session->userdata('currency_symbol')?>";
    }
    else
    {
      type_symbol = "%"; 
    }
    select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name+' ('+discounts[a].value  + type_symbol +')'+ '</option>';
  }
  select_discount += '</select>';
  select_discount += '<input type="hidden" name="discount_type" value="0">';
  select_discount += '<input type="hidden" name="discount_value" value="0">';
  select_discount += '<span name="discount_amount" class="discount_amount text-info" style="display:block; font-size:12px;">Discount: 0.00</span>';

  var input_uom = '<input type="hidden" name="uom_id" value="'+product.uom_id+'" data-uom_name="'+product.uom_name+'" data-uom_uom="'+product.uom_uom+'">'+product.uom_uom;

  var input_quantity = '<input type="number" class="form-control text-right item-quantity" name="quantity" value="1" step="0.01" min="0.01">';

  /************ tax begin *****************/
  var tax = '<input type="hidden" name="tax_id" value="'+product.tax_id+'">';

  if(company_country_id == supplier_country_id)
  {
    if(company_state_id == supplier_state_id)
    {
      tax += 'CGST: <span name="c_tax">0.00</span>%<br/>';
      tax += 'SGST: <span name="s_tax">0.00</span>%';
      tax += '<span name="i_tax" style="display:none">0</span>';
      tax += '<span name="igst" style="display:none">0</span>';
      tax += '<span name="cgst" style="display:none">0</span>';
      tax += '<span name="sgst" style="display:none">0</span>';
    }
    else
    {
      tax += 'IGST: <span name="i_tax">0.00</span>%<br/>';
      tax += '<span name="c_tax" style="display:none">0</span>';
      tax += '<span name="cgst" style="display:none">0</span>';
      tax += '<span name="s_tax" style="display:none">0</span>';
      tax += '<span name="sgst" style="display:none">0</span>';
      tax += '<span name="igst" style="display:none">0</span>';
    }
  }
  else
  {
    tax += 'N/A';
    tax += '<span name="i_tax" style="display:none">0</span>';
    tax += '<span name="igst" style="display:none">0</span>';
    tax += '<span name="c_tax" style="display:none">0</span>';
    tax += '<span name="cgst" style="display:none">0</span>';
    tax += '<span name="s_tax" style="display:none">0</span>';
    tax += '<span name="sgst" style="display:none">0</span>';
  }
  /************ tax end *****************/

  /************ tax type begin *****************/
  var tax_type = '<input type="hidden" name="tax_type" value="'+product.tax_type+'">';
  /************ tax type end *****************/

  var newRow = $('<tr class="product_row">');
  var cols = "";

  // Delete icon column
  cols += '<td class="text-center">'
            + '<span class="delete_item"><i class="fas fa-minus-circle text-danger" style="cursor:pointer; font-size:18px;"></i></span>'
            + '<input type="hidden" name="product_id" value="'+product.id+'">'
            + '<input type="hidden" name="igst_rate" value="'+product.igst+'">'
            + '<input type="hidden" name="cgst_rate" value="'+product.cgst+'">'
            + '<input type="hidden" name="sgst_rate" value="'+product.sgst+'">'
          +'</td>';
          
  // Sr.No column
  cols += '<td class="text-center sr-no">' + rowCount + '</td>';
  
  // Product Description column
  cols += '<td>'
          + '<img src="<?= base_url('assets/product_images').'/'; ?>'+(product.product_image ? product.product_image : 'default.png')+'" alt="'+product.name+'" style="width:40px; height:40px; object-fit:cover; margin-right:5px; border-radius:4px;">'
          + '<span name="product_name">'+product.name+'</span>'
        +'</td>';
        
  // Add Remark column
  cols += '<td><textarea name="product_remark" class="form-control product-remark" rows="2" placeholder="Add remark..." style="width:100%;"></textarea></td>';
  
  // HSN column
  cols += '<td><span name="hsn">'+(product.hsn ? product.hsn : 'NA')+'</span></td>';
  
  // Purchase Qty column
  cols += '<td class="text-right">'+input_quantity+'</td>';
  
  // UOM column
  cols += '<td class="text-center">'+input_uom+'</td>';
  
  // Product MRP column
  cols += '<td class="text-right">'
            +'<input type="number" class="form-control text-right item-mrp" name="selling_price" step="0.01" value="'+(product.selling_price || product.price || 0)+'" min="0">'
            +'<input type="hidden" name="hidden_selling_price" value="'+(product.selling_price || product.price || 0)+'">'
          +'</td>';
  
  // Price/Unit column
  cols += '<td class="text-right">'
            +'<input type="number" class="form-control text-right item-cost" name="cost" step="0.01" value="'+product.cost+'" min="0">'
            +'<input type="hidden" name="hidden_cost" value="'+product.cost+'">'
          +'</td>';
  
  // Discount column
  cols += '<td>' + select_discount + '</td>';
  
  // Taxable Value column
  cols += '<td class="text-right"><span name="taxable_value" class="taxable-value">0.00</span></td>';
  
  // Tax column
  cols += '<td class="tax_td">' + tax + tax_type + '</td>';
  
  // Total Price column
  cols += '<td class="text-right"><span name="subtotal" class="item-total" style="font-weight:bold;">0.00</span></td>';

  newRow.append(cols);
  $("table.product_table tbody#product_table_body").append(newRow);
  $('.select2bs4').select2({theme: 'bootstrap4'});

  // Calculate the row after adding
  calculateRow(newRow);
}
   
      function is_product_exist_in_row(product_id)
      {
        var isproductExist = false;
        $("#product_table_body").find('tr').each(function () {

          var tr = $(this).closest("tr");

          if(tr.find('input[name^="product_id"]').val() == product_id)
          {
            isproductExist = true;
          }
        });

        return isproductExist;
      }

      function highlight_row(product_id)
      {
        $("#product_table_body").find('tr').each(function () {
          var tr  = $(this).closest("tr");

          if(tr.find('input[name^="product_id"]').val() == product_id)
          {
            tr.addClass('highlight_row');

            var existing_quantity = +tr.find('input[name^="quantity"]').val();
            // alert(existing_quantity + 1);
            tr.find('input[name^="quantity"]').val(existing_quantity + 1).trigger('change');
            tr.find('span[name^="quantity_update_message"]').text('+1');

            setTimeout(function(){
              tr.removeClass('highlight_row');
              tr.find('span[name^="quantity_update_message"]').text('');
            },3000);
          }
        });        
      }

      $("table.product_table").on('change', 'input[name^="cost"], input[name^="quantity"], select[name^="item_discount"]', function (event) {

        if($(this).attr('name') == 'item_discount')
        {
          var discount_id = $(this).val();
          var tr          = $(this).closest('tr');

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
              var discount_type = '';

              tr.find('input[name^="discount_type"]').val(discount.type);
              tr.find('input[name^="discount_value"]').val(discount.value);
              
              calculateRow(tr);
              calculateGrandTotal();
            }
          });  
        }
        else
        {
          calculateRow($(this).closest("tr"));
          calculateGrandTotal();
        }
       });
       
       
        $(document).on('change keyup', '.item-mrp', function() {
        calculateRow($(this).closest("tr"));
    });

    // Handle Cost changes
    $(document).on('change keyup', '.item-cost', function() {
        calculateRow($(this).closest("tr"));
    });

    // Handle Quantity changes
    $(document).on('change keyup', '.item-quantity', function() {
        calculateRow($(this).closest("tr"));
    });

    // Handle Discount selection change
    $(document).on('change', '.item-discount-select', function() {
        var discount_id = $(this).val();
        var tr = $(this).closest('tr');

        if(discount_id != '') {
            $.ajax({
                url: "<?php echo base_url('discount/get_record_detail') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    'discount_id': discount_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(data){
                    var discount = data.discount;
                    tr.find('input[name="discount_type"]').val(discount.type);
                    tr.find('input[name="discount_value"]').val(discount.value);
                    calculateRow(tr);
                    calculateGrandTotal();
                }
            });
        } else {
            tr.find('input[name^="discount_type"]').val(0);
            tr.find('input[name^="discount_value"]').val(0);
            calculateRow(tr);
            calculateGrandTotal();
        }
    });
      
    //   $('table.product_table').on('click',"span.delete_item", function(e){
    //     var tr = $(this).closest('tr');
    //     tr.remove();
    //     calculateGrandTotal();
    //   });

 // Calculate row totals
/*  function calculateRow(row) {
    if(row.hasClass('freight-row')) {
      // Handle freight row separately
      var amount = parseFloat(row.find('.freight-amount').val()) || 0;
      row.find('span[name="freight_taxable_value"]').text(amount.toFixed(2));
      row.find('span[name="freight_sub_total"]').text(amount.toFixed(2));
      return;
    }

    var tax_type = row.find('input[name^="tax_type"]').val();
    var supplier_country_id = $('#supplier_country_id').val();
    var supplier_state_id = $('#supplier_state_id').val();
    var company_country_id = $('#company_country_id').val();
    var company_state_id = $('#company_state_id').val();

    if(tax_type == 0) {
      // Exclusive tax calculation
      var quantity = parseFloat(row.find('input[name^="quantity"]').val());
      var cost = parseFloat(row.find('input[name^="cost"]').val());
      var final_discount_value = 0;

      var taxable_value = parseFloat(quantity * cost);

      var discount_type = row.find('input[name^="discount_type"]').val();
      var discount_value = parseFloat(row.find('input[name^="discount_value"]').val());  

      if(discount_type == 0) {
        final_discount_value = discount_value;
      } else {
        final_discount_value = (taxable_value * discount_value)/100;
      }

      taxable_value = taxable_value - final_discount_value;
      
      var igst = 0;
      var cgst = 0;
      var sgst = 0;

      if(company_country_id == supplier_country_id) {
        if(company_state_id == supplier_state_id) {
          cgst = parseFloat(row.find('input[name^="cgst_rate"]').val());
          sgst = parseFloat(row.find('input[name^="sgst_rate"]').val());
        } else {
          igst = parseFloat(row.find('input[name^="igst_rate"]').val());
        }  
      }

      var igst_tax = 0;
      var cgst_tax = 0;
      var sgst_tax = 0;

      if(company_country_id == supplier_country_id) {
        if(company_state_id == supplier_state_id) {
          cgst_tax = parseFloat((taxable_value * cgst)/100);
          sgst_tax = parseFloat((taxable_value * sgst)/100);
        } else {
          igst_tax = parseFloat((taxable_value * igst)/100);
        }  
      }

      var subtotal = parseFloat(taxable_value + igst_tax + cgst_tax + sgst_tax);

      row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
      row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
      row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));

      row.find('span[name^="igst"]').text(igst.toFixed(2));
      row.find('span[name^="cgst"]').text(cgst.toFixed(2));
      row.find('span[name^="sgst"]').text(sgst.toFixed(2));

      row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

      row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
      row.find('span[name^="subtotal"]').text(subtotal.toFixed(2));  
    } else {
      // Inclusive tax calculation
      var final_discount_value = 0;
      var quantity = parseFloat(row.find('input[name^="quantity"]').val());
      var cost = parseFloat(row.find('input[name^="cost"]').val());

      var discount_type = row.find('input[name^="discount_type"]').val();
      var discount_value = parseFloat(row.find('input[name^="discount_value"]').val()); 

      var subtotal = (quantity * cost);

      if(discount_type == 0) {
        final_discount_value = discount_value;
      } else {
        final_discount_value = (subtotal * discount_value)/100;
      }

      subtotal = subtotal - final_discount_value;

      var igst = 0;
      var cgst = 0;
      var sgst = 0;

      if(company_country_id == supplier_country_id) {
        if(company_state_id == supplier_state_id) {
          cgst = parseFloat(row.find('input[name^="cgst_rate"]').val());
          sgst = parseFloat(row.find('input[name^="sgst_rate"]').val());
        } else {
          igst = parseFloat(row.find('input[name^="igst_rate"]').val());
        }  
      }

      var tax_rate = igst+cgst+sgst;
      var tax_amount = parseFloat((subtotal * tax_rate) / (100 + tax_rate));

      var igst_tax = 0;
      var cgst_tax = 0;
      var sgst_tax = 0;

      if(company_country_id == supplier_country_id) {
        if(company_state_id == supplier_state_id) {
          cgst_tax = tax_amount/2;
          sgst_tax = tax_amount/2;
        } else {
          igst_tax = tax_amount;
        }  
      }

      var taxable_value = subtotal - (igst_tax + cgst_tax + sgst_tax);    

      row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
      row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
      row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));

      row.find('span[name^="igst"]').text(igst.toFixed(2));
      row.find('span[name^="cgst"]').text(cgst.toFixed(2));
      row.find('span[name^="sgst"]').text(sgst.toFixed(2));

      row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

      row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
      row.find('span[name^="subtotal"]').text(subtotal.toFixed(2));   
    }
  }*/
  function calculateRow(row) {
    if(row.hasClass('freight-row')) {
        // Handle freight row separately
        var amount = parseFloat(row.find('.freight-amount').val()) || 0;
        row.find('span[name="freight_taxable_value"]').text(amount.toFixed(2));
        row.find('span[name="freight_sub_total"]').text(amount.toFixed(2));
        return;
    }

    var tax_type = row.find('input[name^="tax_type"]').val();
    var supplier_country_id = $('#supplier_country_id').val();
    var supplier_state_id = $('#supplier_state_id').val();
    var company_country_id = $('#company_country_id').val();
    var company_state_id = $('#company_state_id').val();

    // Get values
    var quantity = parseFloat(row.find('input[name^="quantity"]').val()) || 0;
    var cost = parseFloat(row.find('input[name^="cost"]').val()) || 0;
    var mrp = parseFloat(row.find('input[name^="selling_price"]').val()) || 0;
    
    // Calculate subtotal before discount
    var subtotal = quantity * cost;
    
    // Calculate discount
    var discount_type = row.find('input[name^="discount_type"]').val();
    var discount_value = parseFloat(row.find('input[name^="discount_value"]').val()) || 0;
    var final_discount_amount = 0;
    
    if(discount_value > 0) {
        if(discount_type == 0) {
            // Fixed amount discount
            final_discount_amount = discount_value;
        } else {
            // Percentage discount
            final_discount_amount = (subtotal * discount_value) / 100;
        }
    }
    
    // Update discount display
    row.find('span[name^="discount_amount"]').text('Discount: ' + final_discount_amount.toFixed(2));
    
    // Taxable value after discount
    var taxable_value = subtotal - final_discount_amount;
    
    // Tax rates
    var igst = 0, cgst = 0, sgst = 0;
    
    if(tax_type == 0) {
        // Exclusive tax calculation
        if(company_country_id == supplier_country_id) {
            if(company_state_id == supplier_state_id) {
                cgst = parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0;
                sgst = parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0;
                row.find('span[name^="cgst"]').text(cgst);
                row.find('span[name^="sgst"]').text(sgst);
            } else {
                igst = parseFloat(row.find('input[name^="igst_rate"]').val()) || 0;
                row.find('span[name^="igst"]').text(igst);
            }
        }
        
        var igst_tax = (taxable_value * igst) / 100;
        var cgst_tax = (taxable_value * cgst) / 100;
        var sgst_tax = (taxable_value * sgst) / 100;
        
        var total_tax = igst_tax + cgst_tax + sgst_tax;
        var final_total = taxable_value + total_tax;
        
        // Update tax displays
        row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
        row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
        row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
        
        row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
        row.find('span[name^="subtotal"]').text(final_total.toFixed(2));
        
    } else {
        // Inclusive tax calculation
        if(company_country_id == supplier_country_id) {
            if(company_state_id == supplier_state_id) {
                cgst = parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0;
                sgst = parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0;
            } else {
                igst = parseFloat(row.find('input[name^="igst_rate"]').val()) || 0;
            }
        }
        
        var tax_rate = igst + cgst + sgst;
        var tax_amount = (taxable_value * tax_rate) / (100 + tax_rate);
        
        var igst_tax = 0, cgst_tax = 0, sgst_tax = 0;
        
        if(company_country_id == supplier_country_id) {
            if(company_state_id == supplier_state_id) {
                cgst_tax = tax_amount / 2;
                sgst_tax = tax_amount / 2;
            } else {
                igst_tax = tax_amount;
            }
        }
        
        var final_taxable_value = taxable_value - tax_amount;
        var final_total = taxable_value;
        
        row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
        row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
        row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
        
        row.find('span[name^="taxable_value"]').text(final_taxable_value.toFixed(2));
        row.find('span[name^="subtotal"]').text(final_total.toFixed(2));
    }
    
    calculateGrandTotal();
}


function calculateGrandTotal() {
    var total_taxable_value = 0.0;
    var total_cgst = 0.0;
    var total_sgst = 0.0;
    var total_igst = 0.0;
    var total = 0.0;
    var total_discount = 0.0;
    var freight_amount = 0.0;

    $("#product_table_body").find('tr').each(function () {
      var tr = $(this).closest("tr");
      
      if (tr.hasClass('freight-row')) {
        // Get freight amount from the cost field
        freight_amount = parseFloat(tr.find('.freight-amount').val()) || 0;
        total += freight_amount;
        total_taxable_value += freight_amount;
      } else {
        // Regular product row
        total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text()) || 0;
        total_discount += parseFloat(tr.find('span[name^="discount_amount"]').text()) || 0;
        total_cgst += parseFloat(tr.find('span[name^="c_tax"]').text()) || 0;
        total_sgst += parseFloat(tr.find('span[name^="s_tax"]').text()) || 0;
        total_igst += parseFloat(tr.find('span[name^="i_tax"]').text()) || 0;
        total += parseFloat(tr.find('span[name^="subtotal"]').text()) || 0;
      }
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
  }


/* $('table.product_table').on('click', "span.delete_item", function(e){
    var tr = $(this).closest('tr');
    if (tr.hasClass('freight-row')) {
      // If deleting freight row, reset the additional cost fields
      $('#additional_cost_type').val('');
      $('#additional_cost_amount').val(0);
    }
    tr.remove();
    calculateGrandTotal();
  });*/
  
  $('table.product_table').on('click', "span.delete_item", function(e){
    var tr = $(this).closest('tr');
    if (tr.hasClass('freight-row')) {
        // If deleting freight row, reset the additional cost fields
        $('#additional_cost_type').val('');
        $('#additional_cost_amount').val(0);
    }
    tr.remove();
    reindexSerialNumbers(); // Re-index remaining rows
    calculateGrandTotal();
});


        $('#editPurchaseForm').submit(function(e){
    var isError = false;
    $('#PurchaseSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

    $('form#editPurchaseForm .field_validation').each(function() {
      var id = $(this).attr('id');
      var value = $(this).val();
      var field = $(this).attr('placeholder');

      if(value==null || value=="") {
        $("form#editPurchaseForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
        $('form#editPurchaseForm #'+id).addClass('is-invalid');
        isError = true;
      } else {
        $("form#editPurchaseForm #err_"+id).text("").fadeOut('slow');
        $('form#editPurchaseForm #'+id).removeClass('is-invalid');
        $('form#editPurchaseForm #'+id).addClass('is-valid');
      }
    });

    // Prepare freight data
    var freightData = {
      'freight_amount': 0,
      'freight_taxable_value': 0,
      'freight_sub_total': 0,
      'freight_remark': ''
    };

    // Check if freight row exists and get values
    $(".freight-row").each(function() {
      freightData = {
        'freight_amount': parseFloat($(this).find('.freight-amount').val()) || 0,
        'freight_taxable_value': parseFloat($(this).find('span[name="freight_taxable_value"]').text()) || 0,
        'freight_sub_total': parseFloat($(this).find('span[name="freight_sub_total"]').text()) || 0,
        'freight_remark': $(this).find('textarea[name="freight_remark"]').val()
      };
    });

    // Add hidden field for freight data if it doesn't exist
    if ($('#freight_data').length === 0) {
      $('<input>').attr({
        type: 'hidden',
        id: 'freight_data',
        name: 'freight_data'
      }).appendTo('#editPurchaseForm');
    }
    $('#freight_data').val(JSON.stringify(freightData));

 var productDataArray = [];

    $("#product_table_body").find('tr').each(function () {
        var tr = $(this).closest("tr");
        if(tr.hasClass('freight-row')) return; // Skip freight row
        
        var productData = {};
        productData['product_id'] = tr.find('input[name^="product_id"]').val();
        productData['product_name'] = tr.find('span[name^="product_name"]').text();
                productData['hsn'] = tr.find('span[name^="hsn"]').text();

        productData['description'] = tr.find('span[name^="description"]').text();
        productData['quantity'] = tr.find('input[name^="quantity"]').val();
        productData['uom_id'] = tr.find('input[name^="uom_id"]').val();
        productData['uom_name'] = tr.find('input[name^="uom_id"]').data('uom_name');
        productData['uom_uom'] = tr.find('input[name^="uom_id"]').data('uom_uom');
        productData['cost'] = tr.find('input[name^="cost"]').val();
        productData['price'] = tr.find('input[name="price"]').val();
        productData['selling_price'] = tr.find('input[name="selling_price"]').val();
        productData['mfg_date'] = tr.find('input[name="mfg_date"]').val();
        productData['expiry_date'] = tr.find('input[name="expiry_date"]').val();
        productData['product_remark'] = tr.find('textarea[name="product_remark"]').val();
        productData['taxable_value'] = tr.find('span[name^="taxable_value"]').text();
        productData['discount_id'] = tr.find('select[name^="item_discount"]').val();
        productData['discount_type'] = tr.find('input[name^="discount_type"]').val();
        productData['discount_value'] = tr.find('input[name^="discount_value"]').val();
        productData['discount_amount'] = tr.find('span[name^="discount_amount"]').text();
        productData['tax_id'] = tr.find('input[name^="tax_id"]').val();
        productData['tax_type'] = tr.find('input[name^="tax_type"]').val();
        
        // Add these lines to include the tax rates
        productData['igst_rate'] = tr.find('input[name^="igst_rate"]').val();
        productData['cgst_rate'] = tr.find('input[name^="cgst_rate"]').val();
        productData['sgst_rate'] = tr.find('input[name^="sgst_rate"]').val();
        
        // These are the tax amounts (not rates)
        productData['igst'] = tr.find('input[name^="igst_rate"]').val();
        productData['igst_tax'] = tr.find('span[name^="i_tax"]').text();
        productData['cgst'] = tr.find('input[name^="cgst_rate"]').val();
        productData['cgst_tax'] = tr.find('span[name^="c_tax"]').text();
        productData['sgst'] = tr.find('input[name^="sgst_rate"]').val();    
        productData['sgst_tax'] = tr.find('span[name^="s_tax"]').text();
        
        productData['subtotal'] = tr.find('span[name^="subtotal"]').text();

        productDataArray.push(JSON.stringify(productData));
    });

    if(productDataArray.length > 0) {
      $('#purchase_items').val(productDataArray.join('|'));
    } else {
      isError = true;
      $('#emptyPurchaseItemWarningModal').modal('show');
    }

    if(isError == true) {
      $('#PurchaseSubmit').text('<?=$this->lang->line("purchase_add")?>').removeAttr('disabled');
      return false;
    } else {
      return true;
    }
  });

      $("form#editPurchaseForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editPurchaseForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#editPurchaseForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editPurchaseForm #err_"+id).text("").fadeOut('slow');
          $('form#editPurchaseForm #'+id).removeClass('is-invalid');
          $('form#editPurchaseForm #'+id).addClass('is-valid');
        }
      });

      $('.gstin_row').css('display','none');

      // regular expression for gstin format
      var gstReg = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

      const SupplierToast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 10000
      });

      $('form#addSupplierForm #supplierSubmit').click(function(e){
        e.preventDefault();

        var isError = false;

        $('form#addSupplierForm .field_validation').each(function() {
            
            var id    = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if(value==null || value==""){
              $("form#addSupplierForm #err_"+id).text(field+ " field is required.");
              if($('form#addSupplierForm #'+id).hasClass('is-valid')){
                $('form#addSupplierForm #'+id).removeClass('is-valid');
              }
              $('form#addSupplierForm #'+id).addClass('is-invalid');
              isError = true;
            }
            else
            {
              $("form#addSupplierForm #err_"+id).text("");
              $('form#addSupplierForm #'+id).removeClass('is-invalid');
              $('form#addSupplierForm #'+id).addClass('is-valid');
            }

            if (!gstReg.test($('form#addSupplierForm #gstin').val()) && $('form#addSupplierForm #gst_registration_type').val() == 1) 
            {
              if($('form#addSupplierForm #gstin').val() == '')
              {
                // $('form#addSupplierForm #gstin').val('');
                $("form#addSupplierForm #err_gstin").text('GSTIN field is required');
                $('form#addSupplierForm #gstin').addClass('is-invalid');
                isError = true;
              }
              else
              {
                // $('form#addSupplierForm #gstin').val('');
                $("form#addSupplierForm #err_gstin").text('Please enter valid GSTIN');
                $('form#addSupplierForm #gstin').addClass('is-invalid');
                isError = true;  
              }
            }
            else
            {
              
              $("form#addSupplierForm #err_gstin").text("");
              $('form#addSupplierForm #gstin').removeClass('is-invalid');
              $('form#addSupplierForm #gstin').addClass('is-valid');
            }
        });


        if(isError == true)
        {
          return false;
        }  
        else 
        {
          var formData = $('#addSupplierForm').serialize();
          $('form#addSupplierForm #supplierSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

          $.ajax({
              url: '<?php echo base_url("supplier/add") ?>',
              type: 'POST',
              dataType : 'json',
              data: formData,                       
              success: function (response) {

                var supplier = response.supplier;

                if(response.code==1)
                {
                  $('#add_supplier_modal').modal('hide');
                  $('#supplierSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
                  
                  $('#supplier_id').html('');
                  $('#supplier_id').append('<option value="">Select</option>');
                  
                  for(i=0;i<response['suppliers'].length;i++)
                  { 
                    $('#supplier_id').append('<option value="' + response['suppliers'][i].id + '">' + response['suppliers'][i].company_name+'</option>');
                  }

                  $('#supplier_id').val(response['id']).attr("selected","selected");

                  if($("#warehouse_id").length)
                  { 
                    if($("#warehouse_id").val() != '')
                    {
                      $("#supplier_id").closest('.row').siblings().fadeIn(10);  
                    }

                    if(supplier.gst_registration_type == 0)
                    {
                      $('#rcm').val('Y');
                    }

                    $('form#addSupplierForm #supplier_state_id').val(supplier.state_id);
                    $('form#addSupplierForm #supplier_country_id').val(supplier.country_id);
                    $('form#addSupplierForm #supplier_gstin').val(supplier.gstin);

                    $("#product_table_body tr").remove();
                    calculateGrandTotal();
                  }

                  // show_message('success-header',response.message);
                  SupplierToast.fire({
                    type: 'success',
                    title: response.message
                  });
                }
                else
                {
                  // show_message('failure-header',response.message);
                  SupplierToast.fire({
                    type: 'error',
                    title: response.message
                  });
                }
              },
              error: function () 
              { 
                show_message('failure-header','Please contact the administrator if you are keep facing this issue.');   
              }
          });
        }    
      });

      $("form#addSupplierForm .field_validation").on("blur change keyup",  function (event){
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');
          
          if(value==null || value==""){
            $("form#addSupplierForm #err_"+id).text(field+ " field is required.");
            if($('form#addSupplierForm #'+id).hasClass('is-valid')){
              $('form#addSupplierForm #'+id).removeClass('is-valid');
            }
            $('form#addSupplierForm #'+id).addClass('is-invalid');
            return false;
          }
          else{
            $("form#addSupplierForm #err_"+id).text("");
            $('form#addSupplierForm #'+id).removeClass('is-invalid');
            $('form#addSupplierForm #'+id).addClass('is-valid');
          }
      });

      $(document).on('hidden.bs.modal','#add_supplier_modal', function () {

        $('.gstin_row').css('display','none');

        $('form#addSupplierForm .form-control').each(function() {
          var id = $(this).attr('id');
          $("form#addSupplierForm #"+id).val("");
          $("form#addSupplierForm #err_"+id).text("");
          $('form#addSupplierForm #'+id).removeClass('is-invalid');
          $('form#addSupplierForm #'+id).removeClass('is-valid');
        });
      });
      $('#add_supplier_modal').on('shown.bs.modal', function () {

        $("form#addSupplierForm #company_name").focus();
        $('.select2bs4').select2({theme: 'bootstrap4'});

        var company_country_id  = '<?=$this->company_settings_model->get_company_records()->country_id?>';
      
        $('#country_id').html('<option value="">Select</option>');

        $.ajax({
          url: "<?php echo base_url('utility/get_countries') ?>/",
          type: "GET",
          dataType: "JSON",
          success: function(data)
          {
            for(i=0;i<data.length;i++)
            {
              $('#country_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }
            $('#country_id').val(company_country_id).trigger('change');
          }
        });
      });

      $("form#addSupplierForm #gstin").on("blur keyup change",  function (event){

        if (!gstReg.test($('form#addSupplierForm #gstin').val()) && $('form#addSupplierForm #gst_registration_type').val() == 1) 
        {        
          if($('form#addSupplierForm #gstin').val() == "")
          {
            $("form#addSupplierForm #err_gstin").text("GSTIN field is required.");
            $('form#addSupplierForm #gstin').addClass('is-invalid');
            return false;
          }
          else
          {
            $("form#addSupplierForm #err_gstin").text("Please enter valid GSTIN.");
            $('form#addSupplierForm #gstin').addClass('is-invalid');
            return false;
          }
        }
        else
        {
          $("form#addSupplierForm #err_gstin").text("");
          $('form#addSupplierForm #gstin').removeClass('is-invalid');
          $('form#addSupplierForm #gstin').addClass('is-valid');
        }
      });

      $("form#addSupplierForm #gst_registration_type").on("blur keyup change",  function (event){

        if ($('form#addSupplierForm #gst_registration_type').val() == 1) 
        {   
          $('.gstin_row').fadeIn(10);

          if($('form#addSupplierForm #gstin').val() == "")
          {
            $("form#addSupplierForm #err_gstin").text("GSTIN field is required.");
            $('form#addSupplierForm #gstin').addClass('is-invalid');
            return false;
          }
          else
          {
            $("form#addSupplierForm #err_gstin").text("Please enter valid GSTIN.");
            $('form#addSupplierForm #gstin').addClass('is-invalid');
            return false;
          }
        }
        else
        {
          $('.gstin_row').fadeOut(10);

          $("form#addSupplierForm #err_gstin").text("");
          $('form#addSupplierForm #gstin').removeClass('is-invalid');
          $('form#addSupplierForm #gstin').addClass('is-valid');
        }
      });

      $('#country_id').change(function(){

        var id = $(this).val();

        var company_state_id    = '<?=$this->company_settings_model->get_company_records()->state_id?>';
        // alert(id);
        $('form#addSupplierForm #state_id').html('<option value="">Select</option>');
        $('form#addSupplierForm #city_id').html('<option value="">Select</option>');
        $.ajax({
          url: "<?php echo base_url('utility/get_states') ?>/"+id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            for(i=0;i<data.length;i++){
              $('#state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }
            $('#state_id').val(company_state_id).trigger('change');
          }
        });
      });

      $('#state_id').change(function(){

        var id = $(this).val();

        // alert(id);
        $('#city_id').html('<option value="">Select</option>');
        $.ajax({
          url: "<?php echo base_url('utility/get_cities') ?>/"+id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            for(i=0;i<data.length;i++){
              $('#city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
            }
          }
        });
      });

      $(document).on('change', '#upload_document', function(e) {
        var allowedExtensions = ["pdf"];
        var files = this.files;
        var formData = new FormData();
        formData.append('csrf_test_name', '<?= $this->security->get_csrf_hash(); ?>');

        // Get existing filenames
        var existingFilenames = $('#document').val().split(', ');

        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var fileExtension = file.name.split(".").pop().toLowerCase();
            var errorSpan = $('#fileTypeError');
            var fileInputLabel = $(this).next('.custom-file-label');

            var fileName = file.name;

            // Regular expression to match only alphanumeric characters and underscores
            var regex = /^[a-zA-Z0-9_]+(\.[a-zA-Z0-9]+)?$/;

            if (!regex.test(fileName)) {
                Swal.fire({
                    title: "Message",
                    text: "File name should only contain alphanumeric (a-z0-9) and underscore ( _ ).",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    timer: 5000,
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });

                $(this).val(""); // Clear the file input

                return;
            }

            if ($.inArray(fileExtension, allowedExtensions) === -1) {
                errorSpan.text("Only PDF files are allowed.");
                $(this).val(""); // Clear the file input
                return;
            }

            // Append the file to form data
            formData.append("upload_document[]", file);
            // Append the filename to existing filenames
            existingFilenames.push(file.name);
        }

        // Join existing filenames with newly uploaded filenames
        var combinedFilenames = existingFilenames.join(', ');

        // Set the value of the hidden input field to the combined filenames
        // $('#document').val(combinedFilenames);

        // Create a new FormData object to send the files to the server
        var formData = new FormData();
        for (var j = 0; j < files.length; j++) {
            formData.append("upload_document[]", files[j]);
        }
        var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
        var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

        // Add the CSRF token to the form data
        formData.append(csrfTokenName, csrfTokenValue);

          // Perform the AJAX request to the server
          $.ajax({
              url: "<?php echo base_url('purchase/upload_documents')?>", // Replace with your server-side script URL
              type: "POST",
              data: formData,
              dataType: "JSON",
              contentType: false,
              processData: false,
              success: function(response) {
                if (response.length > 0) {
                  var currentDocumentValue = $('#document').val();
                  var newFilenames = response.join(', ');

                  // Append new filenames to the existing document value
                  var updatedDocumentValue = currentDocumentValue ? currentDocumentValue + ', ' + newFilenames : newFilenames;

                  // Update the document field with the combined filenames
                  $('#document').val(updatedDocumentValue);
              }
              },
              error: function(jqXHR, textStatus, errorThrown) {
                  // Handle the error (if needed)
                  alert("Error uploading document: " + errorThrown);
              }
          });
      });


      $(document).on('click', '.deleteAttachedFile', function() {
        var filename = $(this).data('filename');
        
        $(this).closest('.attached-file').remove();

        var filenames = [];

        $('.attached-file').each(function() {
            filenames.push($(this).find('a').text());
        });

        $('#document').val(filenames.join(','));

      });

  $(document).on('input', '.freight-amount', function() {
    let amount = parseFloat($(this).val()) || 0;
    let row = $(this).closest('tr');
    
    // Update the taxable value and subtotal
    row.find('span[name="freight_taxable_value"]').text(amount.toFixed(2));
    row.find('span[name="freight_sub_total"]').text(amount.toFixed(2));
    
    // Update the additional cost amount field to match
    $('#additional_cost_amount').val(amount);
    
    // Recalculate totals
    calculateGrandTotal();
  });

  // Update the additional cost amount field handler
  $('#additional_cost_amount').on('input', function() {
    let amount = parseFloat($(this).val()) || 0;
    $('.freight-amount').val(amount.toFixed(2));
    $('.freight-row span[name="freight_taxable_value"]').text(amount.toFixed(2));
    $('.freight-row span[name="freight_sub_total"]').text(amount.toFixed(2));
    calculateGrandTotal();
  });

  // Additional cost type change handler
  $('#additional_cost_type').change(function () {
    let type = $(this).val();
    if (!type) {
      // Remove freight row if exists
      $('.freight-row').remove();
      calculateGrandTotal();
      return;
    }

    // Check if freight row already exists
    if($('.freight-row').length > 0) return;

    // Get current amount
    let currentAmount = parseFloat($('#additional_cost_amount').val()) || 0;
    
    // Add new freight row
    let newRow = $('<tr class="product_row freight-row">');
    let cols = "";

    cols += '<td>'
              + '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
              + '<input type="hidden" name="freight_id" value="freight">'
              + '<input type="hidden" name="freight_type" value="' + type + '">'
              + '<input type="hidden" name="freight_tax_rate" value="0">'
            +'</td>';
    cols += '<td><span name="freight_name">Freight Charges (' + type + ')</span></td>';
    cols += '<td><textarea name="freight_remark" rows="1" cols="20">Transport charges</textarea></td>';
    cols += '<td><input type="number" class="form-control" name="freight_quantity" value="1" min="1" readonly></td>';
    cols += '<td><input type="number" class="form-control freight-amount" name="freight_cost" value="' + currentAmount.toFixed(2) + '" step="0.01" min="0"></td>';
    cols += '<td>NA</td>';
  
    cols += '<td class="d-none">0.00</td>';
    cols += '<td class="d-none">0.00</td>';
    cols += '<td class="d-none"></td>';
    cols += '<td>NA</td>';
    cols += '<td><span name="freight_taxable_value">' + currentAmount.toFixed(2) + '</span></td>';
    cols += '<td class="tax_td">N/A</td>';
    cols += '<td class="d-none">No</td>';
    cols += '<td><span name="freight_sub_total">' + currentAmount.toFixed(2) + '</span></td>';

    newRow.append(cols);
    $("table.product_table tbody#product_table_body").append(newRow);
    calculateGrandTotal();
  });

 var initialDueDays = $('#due_days').val();
    var initialInvoiceDate = '<?= date("d-m-Y", strtotime($purchase->purchase_date)) ?>';
    
    if (initialDueDays) {
        // Calculate initial due date
        var dateParts = initialInvoiceDate.split('-');
        var invoiceDateObj = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
        invoiceDateObj.setDate(invoiceDateObj.getDate() + parseInt(initialDueDays));
        
        var formattedDueDate = 
            ('0' + invoiceDateObj.getDate()).slice(-2) + '-' +
            ('0' + (invoiceDateObj.getMonth() + 1)).slice(-2) + '-' +
            invoiceDateObj.getFullYear();
        
        $('#due_date').val(formattedDueDate);
        $('#due_date_display').text(formattedDueDate);
    }

    // Handle changes to payment terms dropdown
    $('#due_days').change(function() {
        var selectedOption = $(this).find('option:selected');
        var dueDays = selectedOption.val();
        var terms = selectedOption.data('terms');
        
        // Update terms display
        $('#due_days_terms').html(terms || 'No terms available');
        $('#payment_terms').val(terms);
        
        // Calculate due_date (invoice_date + due_days)
        var invoiceDate = '<?= date("d-m-Y", strtotime($purchase->purchase_date)) ?>';
        if (invoiceDate && dueDays) {
            // Parse invoice date (assuming format: dd-mm-yyyy)
            var dateParts = invoiceDate.split('-');
            var invoiceDateObj = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
            
            // Add due days
            invoiceDateObj.setDate(invoiceDateObj.getDate() + parseInt(dueDays));
            
            // Format back to dd-mm-yyyy
            var formattedDueDate = 
                ('0' + invoiceDateObj.getDate()).slice(-2) + '-' +
                ('0' + (invoiceDateObj.getMonth() + 1)).slice(-2) + '-' +
                invoiceDateObj.getFullYear();
            
            // Set the hidden field value
            $('#due_date').val(formattedDueDate);
            
            // Display due date
            $('#due_date_display').text(formattedDueDate);
        }
    });

      
  });
</script>