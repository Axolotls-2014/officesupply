<?php 
  $this->load->view('layout/header');
  $currency = $this->company_settings_model->get_company_records()->currency_symbol;

  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);

  $mfg_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);

  $promotion = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'promotion', 'active',$row = true,$check_delete_status = false);

  $batch_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'batch_no_purchase', 'active',$row = true,$check_delete_status = false);
 
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

.search-results {
  position: absolute;
  top: 100%;
  left: 0;
  right: 0;
  background: white;
  border: 1px solid #ddd;
  border-top: none;
  max-height: 200px;
  overflow-y: auto;
  z-index: 1000;
  box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.search-result-item {
  padding: 8px 15px;
  cursor: pointer;
  border-bottom: 1px solid #eee;
}

.search-result-item:hover {
  background-color: #f5f5f5;
}

.search-result-item:last-child {
  border-bottom: none;
}

.search { 
  position: relative; 
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
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_inventory')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('purchase')?>"><?=$this->lang->line('purchase_header')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('purchase_add')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="addPurchaseForm" id="addPurchaseForm" method="POST" action="<?=base_url('purchase/add')?>">
              <div class="card">
                <div class="card-header"><?=$this->lang->line('purchase_add')?></div>
                <div class="card-body">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('purchase_reference_no')?></label>
                          <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?=$purchase_order->reference_no?>" readonly>
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('purchase_invoice_no')?><span class="text-danger">*</span></label>
                          <input type="text" class="form-control field_validation" id="invoice_no" name="invoice_no" value="<?=$purchase_order->invoice_no?>" placeholder="<?=$this->lang->line('purchase_invoice_no')?>">
                          <span id="err_invoice_no" class="error invalid-feedback"><?=form_error('invoice_no');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('purchase_date')?><span class="text-danger">*</span></label>
                          <input type="text" class="form-control datepicker field_validation" id="purchase_date" name="purchase_date" value="<?=date('d-m-Y', strtotime($purchase_order->purchase_order_date))?>" placeholder="Invoice Date">
                          <span id="err_purchase_date" class="error invalid-feedback"><?=form_error('purchase_date');?></span>
                        </div>
                      </div>
                      
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>Select Payment Terms</label>
                          <select class="form-control select2bs4" id="due_days" name="due_days">
                            <option value="">Select Due Days</option>
                            <?php foreach($due_days_options as $option): ?>
                              <option value="<?= $option->due_day ?>"
                                <?= (!empty($selected_due_days) && $selected_due_days == $option->due_day) ? 'selected' : '' ?>
                                data-terms="<?= htmlspecialchars($option->terms_and_condition ?? '') ?>">
                                <?= $option->due_day ?> Days
                              </option>
                            <?php endforeach; ?>
                          </select>
                        </div>
                      </div>
                      
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label>Payment terms condition</label>
                          <div id="due_days_terms" class="form-control" style="padding:6px 10px; border:1px solid #ddd; border-radius:4px; min-height:35px;">
                            <?= htmlspecialchars($payment_terms ?? '') ?>
                          </div>
                        </div>
                      </div>
                      
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>Due Date</label>
                          <div id="due_date_display" class="form-control" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 32px;">
                            <?= !empty($due_date) ? date('d-m-Y', strtotime($due_date)) : '' ?>
                          </div>
                        </div>
                      </div>
                      
                      <input type="hidden" name="payment_terms" id="payment_terms" value="<?= htmlspecialchars($payment_terms ?? '') ?>">
                      
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="warehouse">
                            <?=$this->lang->line('purchase_order_warehouse')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="input-group input-group-sm">
                            <?php
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
                          <label for="warehouse">
                            <?=$this->lang->line('purchase_supplier')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="input-group input-group-sm">
                            <input type="hidden" name="supplier_id" id="supplier_id" value="<?=$purchase_order->supplier_id?>">
                            <input type="text" class="form-control form-control-sm" value="<?=$supplier_detail->company_name?>" readonly>
                          </div>
                          <input type="hidden" name="supplier_gstin" id="supplier_gstin" value="<?=$supplier_detail->gstin?>">
                          <input type="hidden" name="supplier_state_id" id="supplier_state_id" value="<?=$supplier_detail->state_id?>">
                          <input type="hidden" name="supplier_country_id" id="supplier_country_id" value="<?=$supplier_detail->country_id?>">
                          <input type="hidden" name="rcm" id="rcm" value="N">
                          <span id="err_supplier_id" class="error invalid-feedback"><?=form_error('supplier_id');?></span>
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
                              <input id="search_product" class="form-control search_product" type="text" name="search_product" placeholder="<?=$this->lang->line('purchase_item_search')?>">
                              <div id="search_results" class="search-results" style="display: none;"></div>
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
                          
                          <div class="table-responsive">
                            <table class="table items table-striped table-bordered table-condensed table-hover product_table" id="product_data">
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
                                </tr>
                              </thead>
                              <tbody id="product_table_body">
                                <?php foreach ($purchase_order_items as $row): ?>
                                <tr class="product_row">
                                  <td class="text-center">
                                    <span class="delete_item"><i class="fas fa-minus-circle text-danger" style="cursor:pointer;"></i></span>
                                    <input type="hidden" name="product_id" value="<?=$row->product_id?>">
                                    <input type="hidden" name="igst_rate" value="<?=$row->igst?>">
                                    <input type="hidden" name="cgst_rate" value="<?=$row->cgst?>">
                                    <input type="hidden" name="sgst_rate" value="<?=$row->sgst?>">
                                  </td>
                                  <td class="text-center sr-no"><?=$loop->iteration?></td>
                                  <td>
                                    <span name="product_name"><?=$row->product_name?></span>
                                    <?php if(!empty($row->description)): ?>
                                      <br/><small class="text-muted"><?=$row->description?></small>
                                    <?php endif; ?>
                                  </td>
                                  <td><input type="text" name="remark" class="form-control form-control-sm" value="<?=$row->remark?>" placeholder="Add remark"></td>
                                  <td class="text-center"><span name="hsn"><?=$row->hsn?></span></td>
                                  <td class="text-right">
                                    <input type="number" class="form-control form-control-sm text-right quantity-input" name="quantity" value="<?=$row->quantity?>" step="1" min="1">
                                  </td>
                                  <td class="text-center">
                                    <input type="hidden" name="uom_id" value="<?=$row->uom_id?>" data-uom_name="<?=$row->uom_name?>" data-uom_uom="<?=$row->uom_uom?>">
                                    <span name="uom"><?=$row->uom_uom?></span>
                                  </td>
                                  <td class="text-right"><span name="mrp"><?=number_format($row->selling_price,2)?></span></td>
                                  <td class="text-right">
                                    <input type="number" class="form-control form-control-sm text-right cost-input" name="cost" step="0.01" value="<?=$row->cost?>" min="0">
                                  </td>
                                  <td>
                                    <div class="input-group input-group-sm">
                                      <input type="number" class="form-control form-control-sm text-right discount-value" name="discount_value" value="<?=$row->discount_value?>" step="0.01" min="0" style="width: 65%;">
                                      <select class="form-control form-control-sm discount-type" name="discount_type" style="width: 35%;">
                                        <option value="percentage" <?=($row->discount_type == 'percentage') ? 'selected' : ''?>>%</option>
                                        <option value="fixed" <?=($row->discount_type == 'fixed') ? 'selected' : ''?>><?=$currency?></option>
                                      </select>
                                    </div>
                                    <input type="hidden" name="discount_amount" value="<?=$row->discount_amount?>">
                                    <span name="discount_amount" class="discount_amount text-info" style="display:block; font-size:12px;">Discount: <?=number_format($row->discount_amount,2)?></span>
                                  </td>
                                  <td class="text-right"><span name="taxable_value"><?=number_format($row->taxable_value,2)?></span></td>
                                  <td class="tax_td">
                                    <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">
                                    <?php 
                                        if($company_setting->country_id == $supplier_detail->country_id):
                                            if($company_setting->state_id == $supplier_detail->state_id):
                                    ?>
                                        CGST: <span name="c_tax"><?=number_format($row->cgst_tax,2)?></span> (<span name="cgst"><?=$row->cgst?></span>%)<br/>
                                        SGST: <span name="s_tax"><?=number_format($row->sgst_tax,2)?></span> (<span name="sgst"><?=$row->sgst?></span>%)
                                        <span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>
                                    <?php elseif($company_setting->state_id != $supplier_detail->state_id): ?>
                                        IGST: <span name="i_tax"><?=number_format($row->igst_tax,2)?></span> (<span name="igst"><?=$row->igst?></span>%)
                                        <span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>
                                        <span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>
                                    <?php endif; ?>
                                    <?php else: ?>
                                        N/A
                                        <span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>
                                        <span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>
                                        <span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>
                                    <?php endif; ?>
                                    <input type="hidden" name="tax_type" value="0">
                                  </td>
                                  <td class="text-right"><strong><span name="subtotal"><?=number_format($row->subtotal,2)?></span></strong></td>
                                </tr>
                                <?php endforeach; ?>
                              </tbody>
                            </table>
                          </div>

                          <!-- TOTAL CALCULATION TABLE WITH TRANSPORT SECTION -->
                          <table class="table table-striped table-bordered table-condensed table-hover total_data" style="font-size: 18px; margin-top: 20px;">
                            <!-- TRANSPORT / FREIGHT ROW -->
                            <tr>
                              <td align="right" width="66%">
                                <select class="form-control" id="additional_cost_type" name="additional_cost_type">
                                  <option value="">Select Transport</option>
                                  <option value="internal" <?=($purchase_order->additional_cost_type == 'internal') ? 'selected' : ''?>>Internal</option>
                                </select>
                              </td>
                              <td align='right' width="34%">
                                <input type="number" class="form-control" id="additional_cost_amount" name="additional_cost_amount" value="<?=$purchase_order->additional_cost_amount?>" step="0.01">
                              </td>
                            </tr>
                            
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('purchase_total_discount')?>(<?=$currency?>)</td>
                              <td align='right' class="text-danger" width="34%">
                                -<span id="total_discount"><?=number_format($purchase_order->total_discount,2)?></span>
                                <input type="hidden" name="total_discount" id="t_discount" value="<?=$purchase_order->total_discount?>">
                              </td>
                            </tr>
                            
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('purchase_total_taxable_value')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                +<span id="total_taxable_value"><?=number_format($purchase_order->total_taxable_value,2)?></span>
                                <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="<?=$purchase_order->total_taxable_value?>">
                              </td>
                            </tr>
                            
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('purchase_total_tax')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                +<span id="total_tax"><?=number_format($purchase_order->total_tax,2)?></span>
                                <input type="hidden" name="total_tax" id="t_tax" value="<?=$purchase_order->total_tax?>">
                              </td>
                            </tr>
                            
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('purchase_total')?>(<?=$currency?>)</td>
                              <td align='right' width="34%">
                                <span id="total"><?=number_format($purchase_order->total,2)?></span>
                                <input type="hidden" name="total" id="t" value="<?=$purchase_order->total?>">
                              </td>
                            </tr>
                          </table>
                        </div>
                      </div>
                    </div>

                    <!-- TABS SECTION -->
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
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('purchase_external_note'); ?>"><?=str_replace("<br />","",$purchase_order->external_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('purchase_internal_note'); ?>"><?=str_replace("<br />","",$purchase_order->internal_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="bank_detail">
                                  <textarea class="col-sm-12 form-control" name="bank_detail" rows="4" placeholder="<?php echo $this->lang->line('purchase_bank_detail'); ?>"><?=str_replace("<br />","",$purchase_order->bank_detail)?></textarea>
                                </div>
                                <div class="tab-pane" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('purchase_terms_and_condition'); ?>"><?=str_replace("<br />","",$purchase_order->terms_and_condition)?></textarea>
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
                  <input type="hidden" name="purchase_order_id" value="<?=$purchase_order->id?>">
                  <input type="hidden" name="purchase_items" id="purchase_items" value="">
                  <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  <input type="hidden" name="due_date" id="due_date" value="<?= isset($due_date) ? $due_date : '' ?>">
                  <input type="hidden" name="freight_data" id="freight_data" value='{"freight_amount":<?=$purchase_order->additional_cost_amount?>,"freight_taxable_value":0,"freight_sub_total":<?=$purchase_order->additional_cost_amount?>}'>
                  
                  <button type="submit" name="submit" id="purchaseSubmit" class="btn btn-info"><?=$this->lang->line('purchase_add')?></button>
                  <span class="btn btn-default float-right" id="cancel" onclick="cancel('purchase')"><?=$this->lang->line('purchase_cancel')?></span>
                </div>
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
$this->load->view('supplier/add_supplier_modal');
?>

<div class="modal fade" id="emptypurchaseItemWarningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header warning-header">
        <h5 class="modal-title" id="exampleModalLabel"><?=$this->lang->line('message')?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?=$this->lang->line('empty_purchase_warning_label')?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/disableautofill/src/jquery.disableAutoFill.min.js"></script>
<script type="text/javascript">
$(document).ready(function(e){
    
    let searchTimeout;
    var currencySymbol = '<?= $currency ?>';
    
    // Initialize product search
    $('#search_product').on('input', function() {
        const searchTerm = $(this).val().trim();
        clearTimeout(searchTimeout);
        if (searchTerm.length === 0) {
            $('#search_results').hide().empty();
            return;
        }
        searchTimeout = setTimeout(() => {
            searchProducts(searchTerm);
        }, 500);
    });
    
    $('#search_product').on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            const searchTerm = $(this).val().trim();
            if (searchTerm.length > 0) {
                searchProducts(searchTerm);
            }
        }
    });
    
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.search').length) {
            $('#search_results').hide().empty();
        }
    });
    
    function searchProducts(term) {
        $.ajax({
            url: "<?php echo base_url('product/search') ?>",
            type: "POST",
            dataType: "json",
            data: {
                'term': term,
                'module': '<?= PURCHASE_MODULE ?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(products) {
                displaySearchResults(products, term);
            },
            error: function(xhr, status, error) {
                console.error('Search error:', error);
                $('#search_results').hide().empty();
            }
        });
    }
    
    function displaySearchResults(products, searchTerm) {
        const $results = $('#search_results');
        $results.empty();
        
        if (!products || products.length === 0) {
            $results.append('<div class="search-result-item">No products found for "' + searchTerm + '"</div>');
        } else {
            products.forEach(function(product) {
                const $item = $('<div class="search-result-item">' + 
                    '<strong>' + escapeHtml(product.name) + '</strong>' +
                    (product.product_category_name ? '<br><small>Category: ' + escapeHtml(product.product_category_name) + '</small>' : '') +
                    (product.hsn ? '<br><small>HSN: ' + escapeHtml(product.hsn) + '</small>' : '') +
                    (product.mrp ? '<br><small>MRP: ' + parseFloat(product.mrp).toFixed(2) + '</small>' : '') +
                    (product.cost ? '<br><small>Cost: ' + parseFloat(product.cost).toFixed(2) + '</small>' : '') +
                    '</div>');
                
                $item.on('click', function() {
                    selectProduct(product.id);
                    $results.hide().empty();
                    $('#search_product').val('');
                });
                $results.append($item);
            });
        }
        $results.show();
    }
    
    function selectProduct(productId) {
        $.ajax({
            url: "<?php echo base_url('product/get_record_detail') ?>",
            type: "POST",
            dataType: "json",
            data: {
                'product_id': productId,
                'module': '<?= PURCHASE_MODULE ?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(data) {
                if (!is_product_exist_in_row(data.product.id)) {
                    add_row(data);
                } else {
                    highlight_row(data.product.id);
                }
                calculateGrandTotal();
            },
            error: function(xhr, status, error) {
                console.error('Product details error:', error);
                alert('Error loading product details');
            }
        });
    }
    
    function is_product_exist_in_row(product_id) {
        var isproductExist = false;
        $("#product_table_body").find('tr').each(function () {
            var tr = $(this).closest("tr");
            if(tr.find('input[name^="product_id"]').val() == product_id) {
                isproductExist = true;
            }
        });
        return isproductExist;
    }
    
    function highlight_row(product_id) {
        $("#product_table_body").find('tr').each(function () {
            var tr = $(this).closest("tr");
            if(tr.find('input[name^="product_id"]').val() == product_id) {
                tr.addClass('highlight_row');
                var existing_quantity = +tr.find('input[name^="quantity"]').val();
                tr.find('input[name^="quantity"]').val(existing_quantity + 1).trigger('change');
                tr.find('span[name^="quantity_update_message"]').text('+1');
                setTimeout(function(){
                    tr.removeClass('highlight_row');
                    tr.find('span[name^="quantity_update_message"]').text('');
                }, 3000);
            }
        });
    }
    
    function escapeHtml(str) {
        if (!str) return '';
        return str.replace(/[&<>]/g, function(m) {
            if (m === '&') return '&amp;';
            if (m === '<') return '&lt;';
            if (m === '>') return '&gt;';
            return m;
        });
    }
    
    function add_row(data) {
        var supplier_country_id = $('#supplier_country_id').val();
        var supplier_state_id = $('#supplier_state_id').val();
        var company_country_id = $('#company_country_id').val();
        var company_state_id = $('#company_state_id').val();

        var product = data.product;
        var rowCount = $("#product_table_body tr").length;
        var srNo = rowCount + 1;
        var mrp = product.mrp || product.cost || 0;
        var hsn = product.hsn || 'N/A';

        var newRow = $('<tr class="product_row">');
        var cols = "";
        
        cols += '<td class="text-center">' +
                '<span class="delete_item" style="cursor:pointer;"><i class="fas fa-minus-circle text-danger"></i></span>' +
                '<input type="hidden" name="product_id" value="' + product.id + '">' +
                '<input type="hidden" name="product_hsn" value="' + hsn + '">' +
                '<input type="hidden" name="product_mrp" value="' + mrp + '">' +
                '<input type="hidden" name="igst_rate" value="' + (product.igst || 0) + '">' +
                '<input type="hidden" name="cgst_rate" value="' + (product.cgst || 0) + '">' +
                '<input type="hidden" name="sgst_rate" value="' + (product.sgst || 0) + '">' +
                '<\/td>';
        
        cols += '<td class="text-center"><span name="sr_no">' + srNo + '<\/span><\/td>';
        cols += '<td><span name="product_name"><strong>' + escapeHtml(product.name) + '<\/strong><\/span>' +
                (product.description ? '<br/><small class="text-muted">' + escapeHtml(product.description) + '<\/small>' : '') + '<\/td>';
        cols += '<td><input type="text" name="remark" class="form-control form-control-sm" placeholder="Add remark"><\/td>';
        cols += '<td class="text-center"><span name="hsn">' + escapeHtml(hsn) + '<\/span><\/td>';
        cols += '<td><input type="number" class="form-control form-control-sm text-right quantity-input" name="quantity" value="1" step="1" min="1">' +
                '<span name="quantity_update_message" class="quantity_update_message text-danger small"><\/span><\/td>';
        cols += '<td><input type="hidden" name="uom_id" value="' + product.uom_id + '" data-uom_name="' + escapeHtml(product.uom_name) + '" data-uom_uom="' + escapeHtml(product.uom_uom) + '">' +
                '<span name="uom">' + escapeHtml(product.uom_uom) + '<\/span><\/td>';
        cols += '<td class="text-right"><span name="mrp">' + parseFloat(mrp).toFixed(2) + '<\/span><\/td>';
        cols += '<td><input type="number" class="form-control form-control-sm text-right cost-input" name="cost" step="0.01" value="' + parseFloat(product.cost).toFixed(2) + '" min="0"><\/td>';
        cols += '<td><div class="input-group input-group-sm">' +
                '<input type="number" class="form-control form-control-sm text-right discount-value" name="discount_value" value="0" step="0.01" min="0" style="width: 65%;">' +
                '<select class="form-control form-control-sm discount-type" name="discount_type" style="width: 35%;">' +
                '<option value="percentage">%<\/option>' +
                '<option value="fixed">' + currencySymbol + '<\/option>' +
                '<\/select><\/div>' +
                '<input type="hidden" name="discount_amount" value="0"><\/td>';
        
        var cost = parseFloat(product.cost);
        var taxable_value = cost;
        cols += '<td class="text-right"><span name="taxable_value">' + taxable_value.toFixed(2) + '<\/span><\/td>';
        
        let cgst = parseFloat(product.cgst) || 0;
        let sgst = parseFloat(product.sgst) || 0;
        let igst = parseFloat(product.igst) || 0;
        let taxHtml = '<input type="hidden" name="tax_id" value="' + (product.tax_id || '1') + '">';
        taxHtml += '<input type="hidden" name="tax_type" value="0">';
        
        if(supplier_country_id == company_country_id) {
            if(supplier_state_id == company_state_id) {
                taxHtml += '<small>CGST: <span name="c_tax">0.00<\/span> (<span name="cgst">' + cgst + '<\/span>%)<br>';
                taxHtml += 'SGST: <span name="s_tax">0.00<\/span> (<span name="sgst">' + sgst + '<\/span>%)<\/small>';
                taxHtml += '<span name="i_tax" style="display:none">0.00<\/span><span name="igst" style="display:none">0<\/span>';
            } else {
                taxHtml += '<small>IGST: <span name="i_tax">0.00<\/span> (<span name="igst">' + igst + '<\/span>%)<\/small>';
                taxHtml += '<span name="c_tax" style="display:none">0.00<\/span><span name="cgst" style="display:none">0<\/span>';
                taxHtml += '<span name="s_tax" style="display:none">0.00<\/span><span name="sgst" style="display:none">0<\/span>';
            }
        } else {
            taxHtml += '<small>N/A<\/small>';
            taxHtml += '<span name="i_tax" style="display:none">0.00<\/span>';
            taxHtml += '<span name="c_tax" style="display:none">0.00<\/span>';
            taxHtml += '<span name="s_tax" style="display:none">0.00<\/span>';
        }
        
        cols += '<td class="tax_td">' + taxHtml + '<\/td>';
        cols += '<td class="text-right"><strong><span name="subtotal">' + taxable_value.toFixed(2) + '<\/span><\/strong><\/td>';
        
        newRow.append(cols);
        $("#product_table_body").append(newRow);
        calculateRow(newRow);
        updateSerialNumbers();
    }
    
    function updateSerialNumbers() {
        $("#product_table_body tr").each(function(index) {
            $(this).find('span[name="sr_no"]').text(index + 1);
        });
    }
    
    function calculateRow(row) {
        if(row.hasClass('freight-row')) {
            var amount = parseFloat(row.find('.freight-amount').val()) || 0;
            row.find('span[name="freight_taxable_value"]').text('0.00');
            row.find('span[name="freight_sub_total"]').text(amount.toFixed(2));
            return;
        }
        
        var quantity = parseFloat(row.find('input[name^="quantity"]').val()) || 0;
        var cost = parseFloat(row.find('input[name^="cost"]').val()) || 0;
        var discountValue = parseFloat(row.find('input[name^="discount_value"]').val()) || 0;
        var discountType = row.find('select[name^="discount_type"]').val();
        
        var priceAfterDiscount = cost;
        var discountAmount = 0;
        if (discountValue > 0) {
            if (discountType === 'percentage') {
                discountAmount = (cost * discountValue) / 100;
                priceAfterDiscount = cost - discountAmount;
            } else {
                discountAmount = discountValue;
                priceAfterDiscount = cost - discountValue;
            }
        }
        priceAfterDiscount = Math.max(0, priceAfterDiscount);
        discountAmount = Math.max(0, discountAmount);
        row.find('input[name^="discount_amount"]').val(discountAmount.toFixed(2));
        
        var taxable_value = quantity * priceAfterDiscount;
        var igst_rate = parseFloat(row.find('input[name^="igst_rate"]').val()) || 0;
        var cgst_rate = parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0;
        var sgst_rate = parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0;
        
        var igst_tax = (taxable_value * igst_rate) / 100;
        var cgst_tax = (taxable_value * cgst_rate) / 100;
        var sgst_tax = (taxable_value * sgst_rate) / 100;
        var subtotal = taxable_value + igst_tax + cgst_tax + sgst_tax;
        
        row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
        row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
        row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
        row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
        row.find('span[name^="subtotal"]').text(subtotal.toFixed(2));
    }
    
    function calculateGrandTotal() {
        var total_taxable_value = 0;
        var total_tax = 0;
        var total = 0;
        var total_discount = 0;

        $("#product_table_body tr").each(function () {
            var tr = $(this);
            if (tr.hasClass('freight-row')) {
                var freight_amount = parseFloat(tr.find('.freight-amount').val()) || 0;
                total += freight_amount;
            } else {
                var taxable = parseFloat(tr.find('span[name^="taxable_value"]').text()) || 0;
                var i_tax = parseFloat(tr.find('span[name^="i_tax"]').text()) || 0;
                var c_tax = parseFloat(tr.find('span[name^="c_tax"]').text()) || 0;
                var s_tax = parseFloat(tr.find('span[name^="s_tax"]').text()) || 0;
                var discount_amount = parseFloat(tr.find('input[name^="discount_amount"]').val()) || 0;
                var subtotal = parseFloat(tr.find('span[name^="subtotal"]').text()) || 0;

                total_taxable_value += taxable;
                total_tax += i_tax + c_tax + s_tax;
                total += subtotal;
                total_discount += discount_amount;
            }
        });

        $('#total_discount').text(total_discount.toFixed(2));
        $('#t_discount').val(total_discount.toFixed(2));
        $('#total_taxable_value').text(total_taxable_value.toFixed(2));
        $('#t_taxable_value').val(total_taxable_value.toFixed(2));
        $('#total_tax').text(total_tax.toFixed(2));
        $('#t_tax').val(total_tax.toFixed(2));
        $('#total').text(total.toFixed(2));
        $('#t').val(total.toFixed(2));
    }
    
    $('table.product_table').on('click', "span.delete_item", function(e){
        var tr = $(this).closest('tr');
        if (tr.hasClass('freight-row')) {
            $('#additional_cost_type').val('');
            $('#additional_cost_amount').val(0);
        }
        tr.remove();
        updateSerialNumbers();
        calculateGrandTotal();
    });
    
    $("table.product_table").on('change keyup', 'input[name^="cost"], input[name^="quantity"], input[name^="discount_value"], select[name^="discount_type"]', function (event) {
        calculateRow($(this).closest("tr"));
        calculateGrandTotal();
    });
    
    // ==================== TRANSPORT / FREIGHT FUNCTIONS ====================
    
    function addFreightRow(type, amount = 0) {
        if ($('.freight-row').length > 0) {
            $('.freight-amount').val(amount.toFixed(2)).trigger('input');
            $('.freight-row input[name="freight_type"]').val(type);
            $('#additional_cost_amount').val(amount);
            calculateGrandTotal();
            return;
        }

        var srNo = $("#product_table_body tr:not(.freight-row)").length + 1;
        var newRow = $('<tr class="product_row freight-row">');
        var cols = "";
        
        cols += '<td class="text-center">' +
                '<span class="delete_item" style="cursor:pointer;"><i class="fas fa-minus-circle text-danger"></i></span>' +
                '<input type="hidden" name="freight_id" value="freight">' +
                '<input type="hidden" name="freight_type" value="' + type + '">' +
                '<input type="hidden" name="freight_tax_rate" value="0">' +
                '<\/td>';
        cols += '<td class="text-center">' + srNo + '<\/td>';
        cols += '<td><span name="freight_name"><strong>Freight Charges</strong><br/><small class="text-muted">Transport (' + type + ')</small></span><\/td>';
        cols += '<td><textarea name="freight_remark" class="form-control form-control-sm" rows="2" placeholder="Transport remark...">Transport charges<\/textarea><\/td>';
        cols += '<td class="text-center">NA<\/td>';
        cols += '<td class="text-right"><input type="number" class="form-control form-control-sm freight-quantity" name="freight_quantity" value="1" min="1" style="width:100%;"><\/td>';
        cols += '<td class="text-center">NA<\/td>';
        cols += '<td class="text-right">0.00<\/td>';
        cols += '<td class="text-right"><input type="number" class="form-control form-control-sm freight-amount" name="freight_cost" value="' + amount.toFixed(2) + '" step="0.01" min="0"><\/td>';
        cols += '<td class="text-center">—<\/td>';
        cols += '<td class="text-right"><span name="freight_taxable_value">0.00</span><\/td>';
        cols += '<td class="tax_td text-center">N/A<\/td>';
        cols += '<td class="text-right"><strong><span name="freight_sub_total">' + amount.toFixed(2) + '<\/span><\/strong><\/td>';
        
        newRow.append(cols);
        $("#product_table_body").append(newRow);
        updateSerialNumbers();
        calculateGrandTotal();
    }
    
    $(document).on('input', '.freight-amount', function() {
        var amount = parseFloat($(this).val()) || 0;
        var row = $(this).closest('tr');
        row.find('span[name="freight_taxable_value"]').text('0.00');
        row.find('span[name="freight_sub_total"]').text(amount.toFixed(2));
        $('#additional_cost_amount').val(amount);
        calculateGrandTotal();
    });
    
    $('#additional_cost_amount').on('input', function() {
        var amount = parseFloat($(this).val()) || 0;
        $('.freight-amount').val(amount.toFixed(2));
        $('.freight-row span[name="freight_taxable_value"]').text('0.00');
        $('.freight-row span[name="freight_sub_total"]').text(amount.toFixed(2));
        calculateGrandTotal();
    });
    
    $('#additional_cost_type').change(function() {
        var type = $(this).val();
        if (!type) {
            $('.freight-row').remove();
            $('#additional_cost_amount').val(0);
            updateSerialNumbers();
            calculateGrandTotal();
            return;
        }
        if ($('.freight-row').length === 0) {
            var currentAmount = parseFloat($('#additional_cost_amount').val()) || 0;
            addFreightRow(type, currentAmount);
        } else {
            $('.freight-row input[name="freight_type"]').val(type);
            $('.freight-row span[name="freight_name"]').html('<strong>Freight Charges</strong><br/><small class="text-muted">Transport (' + type + ')</small>');
        }
        calculateGrandTotal();
    });
    
    // Form submission handler
   /* $('#addPurchaseForm').on('submit', function(e) {
        $('#payment_terms').val($('#due_days_terms').text());
        calculateDueDate();
        
        var freightData = {'freight_amount':0, 'freight_taxable_value':0, 'freight_sub_total':0, 'freight_remark':''};
        $(".freight-row").each(function() {
            freightData = {
                'freight_amount': parseFloat($(this).find('.freight-amount').val()) || 0,
                'freight_taxable_value': 0,
                'freight_sub_total': parseFloat($(this).find('span[name="freight_sub_total"]').text()) || 0,
                'freight_remark': $(this).find('textarea[name="freight_remark"]').val() || ''
            };
        });
        $('#freight_data').val(JSON.stringify(freightData));
        
        var purchase_items = [];
        $("#product_table_body tr").each(function () {
            var row = $(this);
            if(row.hasClass('freight-row')) return;
            var item = {
                product_id: row.find('input[name^="product_id"]').val(),
                product_name: row.find('span[name^="product_name"]').text().trim(),
                hsn: row.find('span[name^="hsn"]').text(),
                product_remark: row.find('input[name^="remark"]').val() || '',
                quantity: parseFloat(row.find('input[name^="quantity"]').val()) || 0,
                uom_id: row.find('input[name^="uom_id"]').val(),
                uom_uom: row.find('input[name^="uom_id"]').data('uom_uom'),
                uom_name: row.find('input[name^="uom_id"]').data('uom_name'),
                mrp: parseFloat(row.find('span[name^="mrp"]').text()) || 0,
                cost: parseFloat(row.find('input[name^="cost"]').val()) || 0,
                discount_value: parseFloat(row.find('input[name^="discount_value"]').val()) || 0,
                discount_type: row.find('select[name^="discount_type"]').val(),
                discount_amount: parseFloat(row.find('input[name^="discount_amount"]').val()) || 0,
                taxable_value: parseFloat(row.find('span[name^="taxable_value"]').text()) || 0,
                tax_id: row.find('input[name^="tax_id"]').val(),
                igst_rate: parseFloat(row.find('input[name^="igst_rate"]').val()) || 0,
                cgst_rate: parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0,
                sgst_rate: parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0,
                igst_tax: parseFloat(row.find('span[name^="i_tax"]').text()) || 0,
                cgst_tax: parseFloat(row.find('span[name^="c_tax"]').text()) || 0,
                sgst_tax: parseFloat(row.find('span[name^="s_tax"]').text()) || 0,
                subtotal: parseFloat(row.find('span[name^="subtotal"]').text()) || 0
            };
            purchase_items.push(item);
        });
        
        var purchase_items_string = purchase_items.map(function(item) {
            return JSON.stringify(item);
        }).join('|');
        $('#purchase_items').val(purchase_items_string);
        
        if (purchase_items.length === 0) {
            e.preventDefault();
            $('#emptypurchaseItemWarningModal').modal('show');
            return false;
        }
    });*/
    // Form submission handler - UPDATED to match your database columns
$('#addPurchaseForm').on('submit', function(e) {
    $('#payment_terms').val($('#due_days_terms').text());
    calculateDueDate();
    
    var freightData = {'freight_amount':0, 'freight_taxable_value':0, 'freight_sub_total':0, 'freight_remark':''};
    $(".freight-row").each(function() {
        freightData = {
            'freight_amount': parseFloat($(this).find('.freight-amount').val()) || 0,
            'freight_taxable_value': 0,
            'freight_sub_total': parseFloat($(this).find('span[name="freight_sub_total"]').text()) || 0,
            'freight_remark': $(this).find('textarea[name="freight_remark"]').val() || ''
        };
    });
    $('#freight_data').val(JSON.stringify(freightData));
    
    var purchase_items = [];
    $("#product_table_body tr").each(function () {
        var row = $(this);
        if(row.hasClass('freight-row')) return;
        
        var item = {
            product_id: row.find('input[name^="product_id"]').val(),
            product_name: row.find('span[name^="product_name"]').text().trim(),
            hsn: row.find('span[name^="hsn"]').text(),
            product_remark: row.find('input[name^="remark"]').val() || '',
            quantity: parseFloat(row.find('input[name^="quantity"]').val()) || 0,
            uom_id: row.find('input[name^="uom_id"]').val(),
            uom_uom: row.find('input[name^="uom_id"]').data('uom_uom'),
            uom_name: row.find('input[name^="uom_id"]').data('uom_name'),
            description: row.find('span[name^="description"]').text() || '',
            cost: parseFloat(row.find('input[name^="cost"]').val()) || 0,
            price: parseFloat(row.find('input[name^="cost"]').val()) || 0,  // Using cost as price
            selling_price: parseFloat(row.find('span[name^="mrp"]').text()) || 0,  // MRP as selling_price
            discount_id: 0,
            discount_type: row.find('select[name^="discount_type"]').val() === 'percentage' ? 1 : 0,
            discount_value: parseFloat(row.find('input[name^="discount_value"]').val()) || 0,
            discount_amount: parseFloat(row.find('input[name^="discount_amount"]').val()) || 0,
            taxable_value: parseFloat(row.find('span[name^="taxable_value"]').text()) || 0,
            tax_id: row.find('input[name^="tax_id"]').val(),
            // Tax rates
            igst: parseFloat(row.find('input[name^="igst_rate"]').val()) || 0,
            igst_rate: parseFloat(row.find('input[name^="igst_rate"]').val()) || 0,
            igst_tax: parseFloat(row.find('span[name^="i_tax"]').text()) || 0,
            cgst: parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0,
            cgst_rate: parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0,
            cgst_tax: parseFloat(row.find('span[name^="c_tax"]').text()) || 0,
            sgst: parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0,
            sgst_rate: parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0,
            sgst_tax: parseFloat(row.find('span[name^="s_tax"]').text()) || 0,
            tax_type: 0,
            subtotal: parseFloat(row.find('span[name^="subtotal"]').text()) || 0,
            batch_no: '',
            free_quantity: 0
        };
        purchase_items.push(item);
    });
    
    var purchase_items_string = purchase_items.map(function(item) {
        return JSON.stringify(item);
    }).join('|');
    $('#purchase_items').val(purchase_items_string);
    
    if (purchase_items.length === 0) {
        e.preventDefault();
        $('#emptypurchaseItemWarningModal').modal('show');
        return false;
    }
});
    
    // Due date calculation
    function calculateDueDate() {
        var purchaseDate = $('#purchase_date').val();
        var dueDays = $('#due_days').val();
        if (purchaseDate && dueDays) {
            var dateParts = purchaseDate.split('-');
            var purchaseDateObj = new Date(dateParts[2], dateParts[1] - 1, dateParts[0]);
            var dueDateObj = new Date(purchaseDateObj);
            dueDateObj.setDate(purchaseDateObj.getDate() + parseInt(dueDays));
            var dueDateFormatted = dueDateObj.toISOString().split('T')[0];
            var dueDateDisplay = ('0' + dueDateObj.getDate()).slice(-2) + '-' + 
                                ('0' + (dueDateObj.getMonth() + 1)).slice(-2) + '-' + 
                                dueDateObj.getFullYear();
            $('#due_date').val(dueDateFormatted);
            $('#due_date_display').text(dueDateDisplay);
        } else {
            $('#due_date').val('');
            $('#due_date_display').text('');
        }
    }
    
    $('#purchase_date').on('change', function() { calculateDueDate(); });
    $('#due_days').on('change', function() {
        calculateDueDate();
        var selectedOption = $(this).find('option:selected');
        var terms = selectedOption.data('terms') || '';
        $('#due_days_terms').text(terms);
        $('#payment_terms').val(terms);
    });
    
    // Initialize existing purchase order items
    function initializeExistingPurchaseOrderItems(purchaseOrderItems) {
        if (!purchaseOrderItems || purchaseOrderItems.length === 0) {
            console.log('No existing items to load');
            return;
        }
        console.log('Loading existing purchase order items:', purchaseOrderItems);
        $("#product_table_body").empty();
        
        purchaseOrderItems.forEach(function(item, index) {
            var supplier_country_id = $('#supplier_country_id').val();
            var supplier_state_id = $('#supplier_state_id').val();
            var company_country_id = $('#company_country_id').val();
            var company_state_id = $('#company_state_id').val();
            var srNo = index + 1;
            var product_name = item.product_name || '';
            var description = item.description || '';
            var hsn = item.hsn || 'N/A';
            var mrp = item.mrp || item.cost || 0;
            var uom_uom = item.uom_uom || '';
            var uom_id = item.uom_id || '';
            var uom_name = item.uom_name || '';
            var quantity = item.quantity || 1;
            var cost = item.cost || 0;
            var remark = item.remark || '';
            var taxable_value = item.taxable_value || (quantity * cost);
            var subtotal = item.subtotal || taxable_value;
            var cgst = parseFloat(item.cgst) || 0;
            var sgst = parseFloat(item.sgst) || 0;
            var igst = parseFloat(item.igst) || 0;
            var cgst_tax = item.cgst_tax || 0;
            var sgst_tax = item.sgst_tax || 0;
            var igst_tax = item.igst_tax || 0;
            
            var newRow = $('<tr class="product_row">');
            var cols = "";
            cols += '<td class="text-center">' +
                    '<span class="delete_item" style="cursor:pointer;"><i class="fas fa-minus-circle text-danger"></i></span>' +
                    '<input type="hidden" name="product_id" value="' + item.product_id + '">' +
                    '<input type="hidden" name="product_hsn" value="' + hsn + '">' +
                    '<input type="hidden" name="product_mrp" value="' + mrp + '">' +
                    '<\/td>';
            cols += '<td class="text-center"><span name="sr_no">' + srNo + '<\/span><\/td>';
            cols += '<td><span name="product_name"><strong>' + escapeHtml(product_name) + '<\/strong><\/span>' +
                    (description ? '<br/><small class="text-muted">' + escapeHtml(description) + '<\/small>' : '') + '<\/td>';
            cols += '<td><input type="text" name="remark" class="form-control form-control-sm" placeholder="Add remark" value="' + escapeHtml(remark) + '"><\/td>';
            cols += '<td class="text-center"><span name="hsn">' + escapeHtml(hsn) + '<\/span><\/td>';
            cols += '<td><input type="number" class="form-control form-control-sm text-right quantity-input" name="quantity" value="' + quantity + '" step="1" min="1">' +
                    '<span name="quantity_update_message" class="quantity_update_message text-danger small"><\/span><\/td>';
            cols += '<td><input type="hidden" name="uom_id" value="' + uom_id + '" data-uom_name="' + escapeHtml(uom_name) + '" data-uom_uom="' + escapeHtml(uom_uom) + '">' +
                    '<span name="uom">' + escapeHtml(uom_uom) + '<\/span><\/td>';
            cols += '<td class="text-right"><span name="mrp">' + parseFloat(mrp).toFixed(2) + '<\/span><\/td>';
            cols += '<td><input type="number" class="form-control form-control-sm text-right cost-input" name="cost" step="0.01" value="' + parseFloat(cost).toFixed(2) + '" min="0"><\/td>';
            cols += '<td><div class="input-group input-group-sm">' +
                    '<input type="number" class="form-control form-control-sm text-right discount-value" name="discount_value" value="' + (item.discount_value || 0) + '" step="0.01" min="0" style="width: 65%;">' +
                    '<select class="form-control form-control-sm discount-type" name="discount_type" style="width: 35%;">' +
                    '<option value="percentage"' + ((item.discount_type || 'percentage') === 'percentage' ? ' selected' : '') + '%<\/option>' +
                    '<option value="fixed"' + ((item.discount_type || 'percentage') === 'fixed' ? ' selected' : '') + '>' + currencySymbol + '<\/option>' +
                    '<\/select><\/div>' +
                    '<input type="hidden" name="discount_amount" value="' + (item.discount_amount || 0) + '"><\/td>';
            cols += '<td class="text-right"><span name="taxable_value">' + parseFloat(taxable_value).toFixed(2) + '<\/span><\/td>';
            
            let taxHtml = '<input type="hidden" name="tax_id" value="' + (item.tax_id || '1') + '">';
            if (supplier_country_id == company_country_id) {
                if (supplier_state_id == company_state_id) {
                    taxHtml += '<input type="hidden" name="igst_rate" value="0"><input type="hidden" name="cgst_rate" value="' + cgst + '"><input type="hidden" name="sgst_rate" value="' + sgst + '">';
                    taxHtml += '<small>CGST: <span name="c_tax">' + parseFloat(cgst_tax).toFixed(2) + '<\/span> (<span name="cgst">' + cgst + '<\/span>%)<br>';
                    taxHtml += 'SGST: <span name="s_tax">' + parseFloat(sgst_tax).toFixed(2) + '<\/span> (<span name="sgst">' + sgst + '<\/span>%)<\/small>';
                    taxHtml += '<span name="i_tax" style="display:none">0.00<\/span><span name="igst" style="display:none">0<\/span>';
                } else {
                    taxHtml += '<input type="hidden" name="igst_rate" value="' + igst + '"><input type="hidden" name="cgst_rate" value="0"><input type="hidden" name="sgst_rate" value="0">';
                    taxHtml += '<small>IGST: <span name="i_tax">' + parseFloat(igst_tax).toFixed(2) + '<\/span> (<span name="igst">' + igst + '<\/span>%)<\/small>';
                    taxHtml += '<span name="c_tax" style="display:none">0.00<\/span><span name="cgst" style="display:none">0<\/span>';
                    taxHtml += '<span name="s_tax" style="display:none">0.00<\/span><span name="sgst" style="display:none">0<\/span>';
                }
            } else {
                taxHtml += '<input type="hidden" name="igst_rate" value="0"><input type="hidden" name="cgst_rate" value="0"><input type="hidden" name="sgst_rate" value="0">';
                taxHtml += '<small>N/A<\/small>';
                taxHtml += '<span name="i_tax" style="display:none">0.00<\/span>';
                taxHtml += '<span name="c_tax" style="display:none">0.00<\/span>';
                taxHtml += '<span name="s_tax" style="display:none">0.00<\/span>';
            }
            cols += '<td class="text-left">' + taxHtml + '<\/td>';
            cols += '<td class="text-right"><strong><span name="subtotal">' + parseFloat(subtotal).toFixed(2) + '<\/span><\/strong><\/td>';
            
            newRow.append(cols);
            $("#product_table_body").append(newRow);
        });
        
        $("#product_table_body tr").each(function() { calculateRow($(this)); });
        calculateGrandTotal();
        updateSerialNumbers();
        console.log('Successfully loaded ' + purchaseOrderItems.length + ' items');
    }
    
    // Load existing freight data from purchase order
    function initializeExistingFreightData() {
        <?php if(isset($purchase_order) && $purchase_order->additional_cost_type && $purchase_order->additional_cost_amount > 0): ?>
            var freightType = '<?= $purchase_order->additional_cost_type ?>';
            var freightAmount = parseFloat('<?= $purchase_order->additional_cost_amount ?>') || 0;
            if(freightType && freightAmount > 0) {
                $('#additional_cost_type').val(freightType);
                $('#additional_cost_amount').val(freightAmount.toFixed(2));
                setTimeout(function() {
                    addFreightRow(freightType, freightAmount);
                }, 200);
            }
        <?php endif; ?>
    }
    
    <?php if(isset($purchase_order_items) && !empty($purchase_order_items)): ?>
        var purchaseOrderItems = <?php echo json_encode($purchase_order_items); ?>;
        setTimeout(function() { initializeExistingPurchaseOrderItems(purchaseOrderItems); }, 100);
    <?php endif; ?>
    
    function initializeExistingRows() {
        $("#product_table_body tr").each(function() { calculateRow($(this)); });
        calculateGrandTotal();
        updateSerialNumbers();
    }
    
    initializeExistingRows();
    initializeExistingFreightData();
});
</script>