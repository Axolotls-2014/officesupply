<?php 
  $this->load->view('layout/header');
  $currency = $this->company_settings_model->get_company_records()->currency_symbol;

  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);

  $mfg_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);

  $search_product = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'allow_product_to_add_in_sale', 'active',$row = true,$check_delete_status = false);

  $promotion = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'promotion', 'active',$row = true,$check_delete_status = false);

  $lr_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'lr_no', 'active',$row = true,$check_delete_status = false);

  $lr_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'lr_date', 'active',$row = true,$check_delete_status = false);

  $carrier = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'carrier', 'active',$row = true,$check_delete_status = false);

  $ewaybill_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'ewaybill_no', 'active',$row = true,$check_delete_status = false);

  $dispatch_from = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'dispatch_from', 'active',$row = true,$check_delete_status = false);

  $mode_of_transportation = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mode_of_transportation', 'active',$row = true,$check_delete_status = false);

  $sub_type = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'sub_type', 'active',$row = true,$check_delete_status = false);

  $doc_type = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'doc_type', 'active',$row = true,$check_delete_status = false);

  $transporter_name = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'transporter_name', 'active',$row = true,$check_delete_status = false);

  $transporter_gstin = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'transporter_gstin', 'active',$row = true,$check_delete_status = false);

  $distance_of_ransportation = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'distance_of_ransportation', 'active',$row = true,$check_delete_status = false);

  $transporter_doc_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'transporter_doc_no', 'active',$row = true,$check_delete_status = false);

  $transpoter_doc_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'transpoter_doc_date', 'active',$row = true,$check_delete_status = false);

  $eway_vehicle_no = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'eway_vehicle_no', 'active',$row = true,$check_delete_status = false);

  $vehicle_tye = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'vehicle_tye', 'active',$row = true,$check_delete_status = false);

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
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_sales')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('sale')?>"><?=$this->lang->line('sale_header')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('sale_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editSaleForm" id="editSaleForm" method="POST" action="<?=base_url('sale/regenerate')?>">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('sale_edit')?></h3>
                  <div class="card-tools">
                    <div class="btn-group">
                      <button type="button" class="btn btn-tool btn-warning rcm_btn" data-tt="tooltip" title="<?=$this->lang->line('sale_rcm_change_status')?>">
                        <?=($sale->rcm == 'Y') ? $this->lang->line('sale_disable_rcm') : $this->lang->line('sale_enable_rcm')?>
                      </button>
                      <button type="button" class="btn btn-tool btn-warning" data-tt="tooltip" title="<?=$this->lang->line('sale_rcm_help')?>">
                        <i class="far fa-question-circle"></i>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="col-sm-12">
                  <div class="row">
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_reference_no')?></label>
                          <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?=$sale->reference_no?>" readonly>
                        </div>
                      </div>
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_invoice_date')?></label>
                          <input type="text" class="form-control datepicker" id="invoice_date" name="invoice_date" value="<?=date('d-m-Y', strtotime($sale->invoice_date))?>" placeholder="Invoice Date">
                          <span id="err_invoice_date" class="error invalid-feedback"><?=form_error('invoice_date');?></span>
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_due_date')?></label>
                          <input type="text" class="form-control datepicker" id="due_date" name="due_date" value="<?=set_value('due_date',(($sale->due_date != '' && $sale->due_date != '0000-00-00') ? date('d-m-Y',strtotime($sale->due_date)) : '' ))?>" readonly>
                          <span id="err_due_date" class="error invalid-feedback"><?=form_error('due_date');?></span>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="warehouse">
                            <?=$this->lang->line('purchase_warehouse')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="input-group input-group-sm">

                            <select class="form-control form-control-sm select2bs4 field_validation" name="warehouse_id" id="warehouse_id" width="100%"  placeholder="<?=$this->lang->line('sale_warehouse')?>">
                                <option value=""><?=$this->lang->line('select')?></option>
                                <?php
                                  foreach ($warehouses as $value) {
                                ?>
                                  <option value="<?=$value->id;?>" 
                                  <?php 
                                      if($value->id == $sale->warehouse_id)
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
                          <!-- <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$sale->warehouse_id?>"> -->
                          <span id="err_warehouse_id" class="error invalid-feedback"><?=form_error('warehouse_id');?></span>
                        </div>
                      </div>

                   

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label for="customer">
                            <?=$this->lang->line('sale_customer')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="input-group input-group-sm">
                            <select class="form-control form-control-sm select2bs4 field_validation" name="customer_id" id="customer_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('sale_customer')?>">
                              <option value=""><?=$this->lang->line('select')?></option>
                              <?php
                                foreach ($customers as $value) {
                              ?>
                                <option value="<?=$value->id;?>"  data-ledger_id="<?=$value->ledger_id?>" 
                                <?php 
                                    if($value->id == $sale->customer_id)
                                      echo ' selected';
                                  ?>
                                >
                                  <?= $value->customer_name;?>
                                </option>
                              <?php 
                                }
                              ?>
                            </select>
                            <span class="input-group-append">
                              <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_customer_modal" data-tt="tooltip" accesskey="c"><i class="fas fa-plus"></i></button>
                            </span>
                          </div>
                          <input type="hidden" name="customer_state_id" id="customer_state_id" value="<?=$customer_detail->state_id?>">
                          <input type="hidden" name="customer_country_id" id="customer_country_id" value="<?=$customer_detail->country_id?>">
                          <span id="err_customer_id" class="error invalid-feedback"><?=form_error('customer_id');?></span>
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
                                  if($value->id == $sale->customer_shipping_country_id)
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
                                        if($value->id == $sale->customer_shipping_state_id)
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
                                        if($value->id == $sale->customer_shipping_city_id)
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
                          <input type="text" name="customer_shipping_address" value="<?=$sale->customer_shipping_address?>" class="form-control field_validation" id="customer_shipping_address" placeholder="<?=$this->lang->line('customer_shipping_address')?>" tabindex="<?=$tabindex++?>"><?=form_error('shipping_address', '<div class="text-danger">', '</div>');?>
                          <span id="err_customer_shipping_address" class="error invalid-feedback"><?=form_error('customer_shipping_address', '<div class="text-danger">', '</div>');?></span>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label>
                            <?=$this->lang->line('customer_shipping_pincode')?>
                            <span class="text-danger">*</span>
                          </label>
                          <input type="text" name="customer_shipping_pincode" value="<?=$sale->customer_shipping_pincode?>" class="form-control field_validation" id="customer_shipping_pincode" placeholder="<?=$this->lang->line('customer_shipping_pincode')?>" tabindex="<?=$tabindex++?>">
                          <span id="err_customer_shipping_pincode" class="error invalid-feedback"><?=form_error('customer_shipping_pincode', '<div class="text-danger">', '</div>');?></span>
                        </div>
                      </div>

                    </div>

                    <div class="row">
                      
                      <div class="col-sm-2 <?= (empty($lr_no) || $lr_no->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_lori_no')?></label>
                          <input type="text" class="form-control" id="lori_no" name="lori_no" value="<?=set_value('lori_no',$sale->lori_no)?>">
                          <span id="err_lori_no" class="error invalid-feedback"><?=form_error('lori_no');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2 <?= (empty($lr_date) || $lr_date->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_lori_date')?></label>
                          <input type="text" class="form-control datepicker" id="lori_date" name="lori_date" value="<?=set_value('lori_date',(($sale->lori_date != '' && $sale->lori_date != '0000-00-00') ? date('d-m-Y',strtotime($sale->lori_date)) : '' ))?>">
                          <span id="err_lori_date" class="error invalid-feedback"><?=form_error('lori_date');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2 <?= (empty($carrier) || $carrier->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_carrier')?></label>
                          <input type="text" class="form-control" id="carrier" name="carrier" value="<?=set_value('carrier',$sale->carrier)?>">
                          <span id="err_carrier" class="error invalid-feedback"><?=form_error('carrier');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2 d-none">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_vehicle_no')?></label>
                          <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" value="<?=set_value('vehicle_no',$sale->vehicle_no)?>">
                          <span id="err_vehicle_no" class="error invalid-feedback"><?=form_error('vehicle_no');?></span>
                        </div>
                      </div>
                      <div class="col-sm-3 <?= (empty($ewaybill_no) || $ewaybill_no->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_no')?></label>
                          <input type="text" class="form-control" id="ewaybill_no" name="ewaybill_no" value="<?=set_value('ewaybill_no',$sale->ewaybill_no)?>">
                          <span id="err_ewaybill_no" class="error invalid-feedback"><?=form_error('ewaybill_no');?></span>
                        </div>
                      </div>

                      <div class="col-sm-3 <?= (empty($dispatch_from) || $dispatch_from->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_dispatch_from')?></label>
                          <select class="form-control ewaybill_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('sale_ewaybill_dispatch_from')?>" id="ewaybill_dispatch_from" name="ewaybill_dispatch_from">
                            <option value="">Select</option>
                            <?php 
                              foreach ($states as $st) 
                              { 
                            ?>
                                <option value="<?=$st->id?>"
                                  <?php 
                                    if($st->id == $sale->ewaybill_dispatch_from)
                                      echo ' selected';
                                  ?>  
                                >
                                  <?=$st->name.' - '.sprintf("%02d", $st->state_code)?>
                                </option>
                            <?php 
                              } 
                            ?>
                          </select>
                          <span id="err_ewaybill_dispatch_from" class="error invalid-feedback"><?=form_error('ewaybill_dispatch_from');?></span>
                        </div>
                      </div>

                     
                    </div>

                    <div class="row">
                      <div class="col-sm-2 d-none">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_additional_case')?></label>
                          <input type="text" class="form-control" id="additional_case" name="additional_case" value="<?=set_value('additional_case',$sale->additional_case)?>">
                          <span id="err_additional_case" class="error invalid-feedback"><?=form_error('additional_case');?></span>
                        </div>
                      </div>
                      
                      <div class="col-sm-3 <?= (empty($mode_of_transportation) || $mode_of_transportation->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_mode_of_transportation')?></label>
                          <select class="form-control ewaybill_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('sale_ewaybill_mode_of_transportation')?>" id="ewaybill_mode_of_transportation" name="ewaybill_mode_of_transportation">
                            <option value="">Select</option>
                            <?php 
                              $ewaybill_mode_of_transportation_array = enum_select('sale','ewaybill_mode_of_transportation');
                              for($i = 0; $i < sizeof($ewaybill_mode_of_transportation_array); $i++)
                              {
                            ?>
                                <option value="<?=$ewaybill_mode_of_transportation_array[$i]?>"
                                  <?php 
                                    if($ewaybill_mode_of_transportation_array[$i] == $sale->ewaybill_mode_of_transportation)
                                      echo ' selected';
                                  ?>
                                >
                                  <?=clean_e_val($ewaybill_mode_of_transportation_array[$i])?>
                                </option>
                            <?php 
                              } 
                            ?>
                          </select>
                          <span id="err_ewaybill_mode_of_transportation" class="error invalid-feedback"><?=form_error('ewaybill_mode_of_transportation');?></span>
                        </div>
                      </div>
                      <div class="col-sm-3 <?= (empty($sub_type) || $sub_type->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_subtype')?></label>
                          <select class="form-control ewaybill_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('sale_ewaybill_subtype')?>" id="ewaybill_subtype" name="ewaybill_subtype">
                            <option value="">Select</option>
                            <?php 
                              $ewaybill_subtype_array = enum_select('sale','ewaybill_subtype');
                              for($i = 0; $i < sizeof($ewaybill_subtype_array); $i++)
                              {
                            ?>
                                <option value="<?=$ewaybill_subtype_array[$i]?>"
                                  <?php 
                                    if($ewaybill_subtype_array[$i] == $sale->ewaybill_subtype)
                                      echo ' selected';
                                  ?>
                                >
                                  <?=clean_e_val($ewaybill_subtype_array[$i])?>
                                </option>
                            <?php 
                              } 
                            ?>
                          </select>
                          <span id="err_ewaybill_subtype" class="error invalid-feedback"><?=form_error('ewaybill_subtype');?></span>
                        </div>
                      </div>
                      <div class="col-sm-3 <?= (empty($doc_type) || $doc_type->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_doctype')?></label>
                          <select class="form-control ewaybill_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('sale_ewaybill_doctype')?>" id="ewaybill_doctype" name="ewaybill_doctype">
                            <option value="">Select</option>
                            <?php 
                              $ewaybill_doctype_array = enum_select('sale','ewaybill_doctype');
                              for($i = 0; $i < sizeof($ewaybill_doctype_array); $i++)
                              {
                            ?>
                                <option value="<?=$ewaybill_doctype_array[$i]?>"
                                  <?php 
                                    if($ewaybill_doctype_array[$i] == $sale->ewaybill_doctype)
                                      echo ' selected';
                                  ?>
                                >
                                  <?=clean_e_val($ewaybill_doctype_array[$i])?>
                                </option>
                            <?php 
                              } 
                            ?>
                          </select>
                          <span id="err_ewaybill_doctype" class="error invalid-feedback"><?=form_error('ewaybill_doctype');?></span>
                        </div>
                      </div>
                      <div class="col-sm-3 <?= (empty($transporter_gstin) || $transporter_gstin->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_transporter_gstin')?></label>
                          <input type="text" class="form-control " id="ewaybill_transporter_gstin" name="ewaybill_transporter_gstin" value="<?=set_value('ewaybill_transporter_gstin',$sale->ewaybill_transporter_gstin)?>">
                          <span id="err_ewaybill_transporter_gstin" class="error invalid-feedback"><?=form_error('ewaybill_transporter_gstin');?></span>
                        </div>
                      </div>
                    </div>

                    <div class="row">

                      <div class="col-sm-2 <?= (empty($transporter_name) || $transporter_name->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_transporter_name')?></label>
                          <input type="text" class="form-control " id="ewaybill_transporter_name" name="ewaybill_transporter_name" value="<?=set_value('ewaybill_transporter_name',$sale->ewaybill_transporter_name)?>">
                          <span id="err_ewaybill_transporter_name" class="error invalid-feedback"><?=form_error('ewaybill_transporter_name');?></span>
                        </div>
                      </div>
                      
                      <div class="col-sm-2 <?= (empty($distance_of_transportation) || $distance_of_transportation->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_distance_of_transportation')?></label>
                          <input type="number" class="form-control" id="ewaybill_distance_of_transportation" name="ewaybill_distance_of_transportation" value="<?=set_value('ewaybill_distance_of_transportation',$sale->ewaybill_distance_of_transportation)?>" step="0.01">
                          <span id="err_ewaybill_distance_of_transportation" class="error invalid-feedback"><?=form_error('ewaybill_distance_of_transportation');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2 <?= (empty($transporter_doc_no) || $transporter_doc_no->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_transporter_doc_no')?></label>
                          <input type="text" class="form-control" id="ewaybill_transporter_doc_no" name="ewaybill_transporter_doc_no" value="<?=set_value('ewaybill_transporter_doc_no',$sale->ewaybill_transporter_doc_no)?>">
                          <span id="err_ewaybill_transporter_doc_no" class="error invalid-feedback"><?=form_error('ewaybill_transporter_doc_no');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2 <?= (empty($transporter_doc_date) || $transporter_doc_date->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_transporter_doc_date')?></label>
                          <input type="text" class="form-control datepicker" id="ewaybill_transporter_doc_date" name="ewaybill_transporter_doc_date" value="<?=set_value('ewaybill_transporter_doc_date',(($sale->ewaybill_transporter_doc_date != '') ? date('d-m-Y',strtotime($sale->ewaybill_transporter_doc_date)) : ''))?>">
                          <span id="err_ewaybill_transporter_doc_date" class="error invalid-feedback"><?=form_error('ewaybill_transporter_doc_date');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2 <?= (empty($eway_vehicle_no) || $eway_vehicle_no->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_vehicle_no')?></label>
                          <input type="text" class="form-control" id="ewaybill_vehicle_no" name="ewaybill_vehicle_no" value="<?=set_value('ewaybill_vehicle_no',$sale->ewaybill_vehicle_no)?>">
                          <span id="err_ewaybill_vehicle_no" class="error invalid-feedback"><?=form_error('ewaybill_vehicle_no');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2 <?= (empty($vehicle_type) || $vehicle_type->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_vehicle_type')?></label>
                          <select class="form-control ewaybill_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('sale_ewaybill_vehicle_type')?>" id="ewaybill_vehicle_type" name="ewaybill_vehicle_type">
                            <option value="">Select</option>
                            <?php 
                              $ewaybill_vehicle_type_array = enum_select('sale','ewaybill_vehicle_type');
                              for($i = 0; $i < sizeof($ewaybill_vehicle_type_array); $i++)
                              {
                            ?>
                                <option value="<?=$ewaybill_vehicle_type_array[$i]?>"
                                  <?php 
                                    if($ewaybill_vehicle_type_array[$i] == $sale->ewaybill_vehicle_type)
                                      echo ' selected';
                                  ?>
                                >
                                  <?=clean_e_val($ewaybill_vehicle_type_array[$i])?>
                                </option>
                            <?php 
                              } 
                            ?>
                          </select>
                          <span id="err_ewaybill_vehicle_type" class="error invalid-feedback"><?=form_error('ewaybill_vehicle_type');?></span>
                        </div>
                      </div>
                    </div>

                    <div class="row">
                      <div class="col-sm-8">
                        <div class="form-group">
                          <select class="form-control form-control-sm select2bs4" name="proforma_invoice_id[]" id="proforma_invoice_id" width="100%" class="add-row" multiple="multiple" placeholder="Proforma invoice Items">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php

                              $proforma_invoice_array = explode(",", $sale->proforma_invoice_id); 
                              foreach ($proforma_invoices as $value) {
                            ?>
                              <option value="<?=$value->id?>" 
                                <?php
                                  if(in_array($value->id, $proforma_invoice_array))
                                    echo ' selected'; 
                                ?>
                              ><?=$value->reference_no?></option>
                            <?php
                              }
                            ?>
                          </select>
                          <span id="err_proforma_invoice_id" class="error invalid-feedback"><?=form_error('proforma_invoice_id');?></span>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="row">
                          <button type="button" class="btn btn-primary" name="get_proforma_invoice_items" id="get_proforma_invoice_items" >Get Proforma Invoice Items</button>

                          <button type="button" class="btn btn-primary ml-1" name="refresh_proforma_invoice_items" id="refresh_proforma_invoice_items" >Refresh Proforma Invoice Items</button>
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
                                <input type="hidden" value="<?= $sale->document ?>" name="document[]" id="document">
                            </div>
                            <span id="fileTypeError" class="error-message"></span>
                            <?php
                              $document_filenames = explode(',', $sale->document);
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

                    
                    <div class="row <?= (empty($search_product) || $search_product->field_status !== 'active') ? 'd-none' : '' ?>">
                      <div class="col-sm-12 ">
                        <div class="well">
                          <div class="row">
                            <!-- <div class="col-sm-12">
                              <a href="" data-toggle="modal" data-tt="tooltip" data-target="#add_service_modal" class="float-right"><?=$this->lang->line('sale_add_new_service')?></a>
                            </div> -->
                            <div class="col-sm-12 search">
                              <span class="fa fa-search"></span>
                              <input id="search_product" class="form-control search_product" type="text" name="search_product"  placeholder="<?=$this->lang->line('sale_item_search')?>">
                              <span id="err_search_product" class="validation"></span>
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
                          <label><?=$this->lang->line('sale_items')?></label>
                          <table class="table items table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
                            <thead>
                              <tr>
                                <th width="2%">
                                  <img src="<?php  echo base_url(); ?>assets/images/bin1.png" />
                                </th>
                                <th class="span2" width="15%"><?=$this->lang->line('service_description')?></th>
                                <th class="span2" width="8%"><?=$this->lang->line('sale_qty')?></th>
                                <th class="span2 <?= (empty($promotion) || $promotion->field_status !== 'active') ? 'd-none' : '' ?>" width="10%"><?=$this->lang->line('proforma_invoice_free_qty')?></th>
                                <th class="span2" width="10%"><?=$this->lang->line('sale_selling_price')?></th>
                                <th class="span2" width="10%"><?=$this->lang->line('sale_price')?></th>
                                <th class="span2 <?= (empty($batch_no) || $batch_no->field_status !== 'active') ? 'd-none' : '' ?>" width="10%"><?=$this->lang->line('proforma_invoice_batch')?></th>
                                <th class="span2 <?= (empty($mfg_date) || $mfg_date->field_status !== 'active') ? 'd-none' : '' ?>" width="10%">
                                  <?= $this->lang->line('product_mfg_date') ?>
                                </th>
                                <th class="span2 <?= (empty($expiry_date) || $expiry_date->field_status !== 'active') ? 'd-none' : '' ?>" width="10%">
                                  <?= $this->lang->line('product_expiry_date') ?>
                                </th>
                                <th class="span2" width="14%"><?=$this->lang->line('sale_discount')?></th>
                                <th class="span2" width="11%"><?=$this->lang->line('sale_uom')?></th>
                                <th class="span2" width="8%"><?=$this->lang->line('sale_taxable_value')?></th>
                                <th class="span2" width="12%"><?=$this->lang->line('sale_tax')?></th>
                                <th class="span2" width="5%"><?=$this->lang->line('sale_inclusive')?></th>
                                <th class="span2" width="7%"><?=$this->lang->line('sale_total')?></th>
                              </tr>
                            </thead>
                            <tbody id="product_table_body">
                             
                            </tbody>
                          </table>
                          <table class="table table-striped table-bordered table-condensed table-hover total_data">
                            <tr>
                              <td align="right" width="66%"> <?=$this->lang->line('sale_total_discount')?>(<?=$currency?>)</td>
                              <td align='right' class="text-danger" width="34%">
                                -<span id="total_discount">0.00</span>
                                <input type="hidden" name="total_discount" id="t_discount" value="0">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('sale_total_taxable_value')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                +<span id="total_taxable_value">0.00</span>
                                <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="0">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('sale_total_tax')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                +<span id="total_tax">0.00</span>
                                <input type="hidden" name="total_tax" id="t_tax" value="0">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('tds')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                <input type="number" class="form-control" name="tds" id="tds" value="0.00" min="0.00" style="text-align: right" step="0.01">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('sale_total')?>(<?=$currency?>)</td>
                              <td align='right' width="34%">
                                <span id="total">0.00</span>
                                <input type="hidden" name="total" id="t" value="0">
                              </td>
                            </tr>

                            <tr>
                              <td align="right" width="66%">Available Credit(<?=$currency?>)</td>
                              <td align='right' width="34%">
                                <span id="available_credit">0.00</span>
                                <input type="hidden" name="available_credit" value="0">
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
                                  <a class="nav-link active" href="#external_note" data-toggle="pill"><?php echo $this->lang->line('sale_external_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#internal_note" data-toggle="pill"><?php echo $this->lang->line('sale_internal_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#bank_detail" data-toggle="pill"><?php echo $this->lang->line('sale_bank_detail'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#terms_and_condition" data-toggle="pill"><?php echo $this->lang->line('sale_terms_and_condition'); ?></a>
                                </li>
                              </ul>                           
                              <br>
                              <div class="tab-content">
                                <div class="tab-pane active" id="external_note">
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('sale_external_note'); ?>"><?=str_replace("<br />","",$sale->external_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('sale_internal_note'); ?>"><?=str_replace("<br />","",$sale->internal_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="bank_detail">
                                  <textarea class="col-sm-12 form-control" name="bank_detail" rows="4" placeholder="<?php echo $this->lang->line('sale_bank_detail'); ?>"><?=str_replace("<br />","",$sale->bank_detail)?></textarea>
                                </div>
                                <div class="tab-pane" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('sale_terms_and_condition'); ?>"><?=str_replace("<br />","",$sale->terms_and_condition)?></textarea>
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
                  <input type="hidden" name="rcm" id="rcm" value="<?=$sale->rcm?>">
                  <input type="hidden" name="id" value="<?=$sale->id?>">
                  <input type="hidden" name="sale_items" id="sale_items" value="">
                  <input type="hidden" name="user_id" id="user_id" value="<?=$this->session->userdata("user_id")?>">
                  <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  <button type="submit" name="submit" id="saleSubmit" class="btn btn-info"><?=$this->lang->line('sale_add')?></button>
                  <!-- <button type="submit" name="submit" id="saleSubmitPayNow" value="pay" name="pay" class="btn btn-info">Add Sale & Pay Now</button>                      -->
                  <span class="btn btn-default float-right" id="cancel" onclick="cancel('sale')"><?=$this->lang->line('sale_cancel')?></span>
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
  $this->load->view('service/add_service_modal');
?>

<div class="modal fade" id="emptySaleItemWarningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header warning-header">
        <h5 class="modal-title" id="exampleModalLabel"> <?=$this->lang->line('sale_message_label')?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <?=$this->lang->line('empty_sale_warning_label')?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=$this->lang->line('close')?></button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/disableautofill/src/jquery.disableAutoFill.min.js"></script>

<script type="text/javascript">

  $(document).ready(function(e){

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

    $('#customer_id').change(function(e){

      $(".customer_shipping_detail").fadeIn(10);

      var customer_id = $(this).val();
      var user_id     = $('#user_id').val();

      if(customer_id != '')
      {
        if($('#warehouse_id').val() != '')
        {
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

              var proforma_invoices = data.proforma_invoices;
              var futureDate = data.futureDate;

              $('#due_date').val(futureDate);
              
              $('#customer_state_id').val(customer.state_id);
              $('#customer_country_id').val(customer.country_id);

              //$('#customer_shipping_country_id').val(customer.shipping_country_id).trigger('change');
              $('#customer_shipping_state_id').val(customer.shipping_state_id).trigger('change'); // Trigger 'change' event to refresh select2
              $('#customer_shipping_city_id').val(customer.shipping_city_id).trigger('change');
              $('#customer_shipping_address').val(customer.shipping_address).trigger('change');
              $('#customer_shipping_pincode').val(customer.shipping_pincode).trigger('change');

              if(customer.shipping_state_id != '' && customer.shipping_state_id != null)
              {
                $('#customer_state_id').val(customer.shipping_state_id);
              }

              if(customer.closing_balance < 0)
              {                  
                $('#available_credit').text(Math.abs(customer.closing_balance));
                $('input[name="available_credit"]').val(customer.closing_balance);
              }
              else
              {
                $('#available_credit').text(0);
                $('input[name="available_credit"]').val(0); 
              }
              $("#product_table_body tr").remove();
              $('#proforma_invoice_id').html('<option value="">Select</option>');
              // for(i=0;i<pack_slips.length;i++){
              //   if(pack_slips[i].sale_id == null)
              //     $('#pack_slip_id').append('<option value="' + pack_slips[i].id + '" selected>' + pack_slips[i].reference_no + '</option>');  
              // }

              for (var i = 0; i < proforma_invoices.length; i++) {
                  var proforma_invoice = proforma_invoices[i];

                  if (proforma_invoice.sale_id == null) {
                      var lockDateTime = proforma_invoice.lock_datetime;
                     
                      $('#proforma_invoice_id').append('<option value="' + proforma_invoice.id + '" selected>' + proforma_invoice.reference_no + '</option');
                    
                  }
              }

            

              refresh_tax_td();
              // $("#product_table_body tr").remove();
              // calculateGrandTotal();
            }
        });
      }
      else
      {
        $("#customer_id").closest('.row').siblings().css('display','none');
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
              
              for(i=0;i<data.length;i++){
                $('#customer_shipping_city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
              }
              // $('#customer_shipping_city_id').trigger('change');
            }
          });
        }


      });

    function isLockDurationGreaterThanFiveMinutes(lockDateTime) 
      {
        var currentDateTime         = new Date();
        var lockTime                = new Date(lockDateTime);
        var timeDifferenceInMinutes = (currentDateTime - lockTime) / (1000 * 60);

        return timeDifferenceInMinutes > 5;
      }

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
              cgst        = parseFloat(row.find('input[name^="cgst_rate"]').val());
              sgst        = parseFloat(row.find('input[name^="sgst_rate"]').val());
            }
            else
            {
              igst        = parseFloat(row.find('input[name^="igst_rate"]').val());
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

      $(function(){
        $('#search_product').autoComplete({
          minChars: 1,
          cache: 0,
          source: function(term, suggest){
            term = term.toLowerCase();
            var warehouse_id = $('#warehouse_id').val();
            
            $.ajax({
              url: "<?php echo base_url('product/search') ?>",
              type: "POST",
              dataType: "json",
              data:{
                'term': term,
                'warehouse_id': warehouse_id,
                'module': '<?=SALE_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var products = data;
                var suggestions = [];
                for(var i = 0; i < products.length; ++i) {
                    suggestions.push(products[i].warehouse_products_id+' - '+products[i].name+' - '+products[i].product_category_name+' - '+products[i].price);
                    c_mapping[products[i].warehouse_products_id] = products[i].name;
                }

                if(suggestions.length == 0)
                {
                  $('#err_search_product').text('No Product(s) are available with this term OR products matches with this term are having 0 quantity. Please try different term');
                }
                else
                {
                  $('#err_search_product').text(''); 
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
              data:{
                'product_id': warehouse_products_id, // here product is warehouse_products_id 
                'module': '<?=SALE_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var product = data.product;

                if(!is_product_exist_in_row(product.warehouse_products_id))
                {
                  add_row(data);
                }
                else
                {
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
              }
            });
          } 
        });
      });

      $(document).on('click','#get_proforma_invoice_items',function(e){

        $('#get_proforma_invoice_items').addClass('disabled').text('Please wait...');
        var proforma_invoice_ids = $('#proforma_invoice_id').val();
        var customer_id   = $('#customer_id').val();

        Swal.fire({
          title: "Warning",
          text: "Please wait we are fetching proforma invoice items",
          icon: "warning",
          showConfirmButton: false
        });

        if($('#proforma_invoice_id option:selected').length)
        {
          $("#product_table_body tr").remove();

          
        
          $.ajax({
            url: "<?php echo base_url('proforma_invoice/get_proforma_invoice_items') ?>",
            type: "POST",
            dataType: "json",
            data: {
                'proforma_invoice_ids' : proforma_invoice_ids,
                'customer_id'   : customer_id,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(data){
              // var warehouse = data.warehouse;
              var proforma_invoice_items = data.proforma_invoice_items;
              var discounts = data.discounts;

              // alert(proforma_invoice_items);
              // alert(discounts);

              add_proforma_invoice_row(proforma_invoice_items,discounts);

              $('.datepicker').datepicker({
                    weekStart: 1,
                    daysOfWeekHighlighted: "6,0",
                    autoclose: true,
                    todayHighlight: true,
                    format: 'dd-mm-yyyy'
                });

              // for (var i = proforma_invoice_items.length - 1; i >= 0; i--) {
              //   add_pack_item_row(proforma_invoice_items[i],discounts);
              // }

              // $("#product_table_body tr").remove();
              // calculateRow();
              calculateGrandTotal();

              $('#get_proforma_invoice_items').removeClass('disabled').text('Get Proforma Invoice Items');
              Swal.close(); 

            }
          });  
        }
        else
        {
          $('#get_proforma_invoice_items').removeClass('disabled').text('Get Proforma Invoice Items');
          Swal.close();
          
          Swal.fire({
            text: 'Proforma Invoice should be created first for the selected customer',
            icon: "warning",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            timer: 2000,
            customClass: {
                confirmButton: "btn btn-primary"
            }
          });

        }  

      });

      $(document).on('click','#refresh_proforma_invoice_items',function(e){
        $('#customer_id').trigger('change');
      });

      $('.datepicker').datepicker({
          weekStart: 1,
          daysOfWeekHighlighted: "6,0",
          autoclose: true,
          todayHighlight: true,
          format: 'dd-mm-yyyy'
      });

      function add_proforma_invoice_row(proforma_invoice_items,discount)
      {
        var customer_country_id = $('#customer_country_id').val();
        var customer_state_id   = $('#customer_state_id').val();

        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var company_gstin       = '<?=$company_setting->gstin?>';

        // var pack_slip_item   = pack_slip_item;
        var discounts = discount;

        // console.log("pack_slip_items:", pack_slip_items);
        // console.log("discounts:", discounts);

        for (var i = proforma_invoice_items.length - 1; i >= 0; i--) {
            var proforma_invoice_item = proforma_invoice_items[i];
            
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
                select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name+'('+discounts[a].value  + type_symbol +')'+ '</option>';
              }
            select_discount += '</select>'
            select_discount += '<span name="span_discount_type" class="discount_type">Discount Value :  <?=$this->session->userdata('currency_symbol');?> </span><input type="hidden" name="discount_type" value="0">';
            select_discount += '<span name="discount_amount" class="discount_amount">0.0</span><input type="hidden" name="discount_value" value="'+0+'">';

        var input_uom = '<input type="hidden" name="uom_id" value="'+proforma_invoice_item.uom_id+'" data-uom_name="'+proforma_invoice_item.uom_name+'" data-uom_uom="'+proforma_invoice_item.uom_uom+'">'+proforma_invoice_item.uom_uom;
        var input_quantity = "";
        /* input_quantity  +=      'MIN: '+product.minimum_order_quantity+' <span data-tt="tooltip" title="Minimum qty must be ordered by customer"><i class="fas fa-question" style="float:right;font-size:11px;padding-top:5px"></i></span>';*/

        input_quantity  +=  '<input type="number" class="form-control" name="quantity" value="'+proforma_invoice_item.quantity+'" min="0.01" step="any" readonly="readonly">';
        // input_quantity  +=  'QTY: '+pack_slip_item.quantity+' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/>';

        input_quantity  +=  '<span name="quantity_update_message" class="quantity_update_message"></span>';

       
        input_optional  = "";

        input_optional += '<textarea class="form-control d-none" id="textarea" name="optional" rows="4" placeholder="" maxlength="50">'+proforma_invoice_item.description+'</textarea><br/>';


        /*var input_quantity =  '<input type="number" class="form-control" name="quantity" value="1" step="1" min="1" max="'+product.quantity+'"><span name="quantity_update_message" class="quantity_update_message"></span>';*/
        var taxable_value  =  '<span name="taxable_value">'+proforma_invoice_item.selling_price+'</span>';

        /************    tax begin   *****************/
        var tax = '<input type="hidden" name="tax_id" value="'+proforma_invoice_item.tax_id+'">';

        // alert(company_state_id);
        // alert(customer_state_id);

        if(company_country_id == customer_country_id)
        {          
          if(company_state_id == customer_state_id)
          {
            tax += 'C : <span name="c_tax">'+0.0+'</span>(<span name="cgst">'+proforma_invoice_item.cgst+'</span>)<br/>';
            tax += 'S : <span name="s_tax">'+0.0+'</span>(<span name="sgst">'+proforma_invoice_item.sgst+'</span>)';
            tax += '<span name="i_tax"style="display:none">0</span><span name="igst" style="display:none">0</span>';
          }
          else
          {
            tax += 'I : <span name="i_tax">'+0.0+'</span>(<span name="igst">'+proforma_invoice_item.igst+'</span>)<br/>';
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


        /************    tax end   *****************/

        /************    tax type begin   *****************/

        var tax_type  = '<input type="hidden" name="tax_type" value="'+proforma_invoice_item.tax_type+'">';
        tax_type      += (proforma_invoice_item.tax_type == 0) ? "No" : "Yes";

        /************    tax type end   *****************/

        var free_quantity = '<input type="number" class="form-control" name="free_quantity" value="'+proforma_invoice_item.free_quantity+'" min="0" step="any" readonly="readonly">';

       
        var newRow = $('<tr class="service_row">');
            var cols = "";

            cols += '<td>'
                      + '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
                      + '<input type="hidden" name="product_id" value="'+proforma_invoice_item.product_id+'">'
                      + '<input type="hidden" name="proforma_invoice_idd" value="'+proforma_invoice_item.proforma_invoice_id+'">'
                      + '<input type="hidden" name="warehouse_product_id" value="'+proforma_invoice_item.warehouse_product_id+'">'
                      + '<input type="hidden" name="igst_rate" value="'+proforma_invoice_item.igst+'">'
                      + '<input type="hidden" name="cgst_rate" value="'+proforma_invoice_item.cgst+'">'
                      + '<input type="hidden" name="sgst_rate" value="'+proforma_invoice_item.sgst+'">'
                     
                    +'</td>';
           
            cols += '<td><span name="product_name">'+proforma_invoice_item.name+'</span><br/><span name="description">'+proforma_invoice_item.description+'</span></td>';

            cols += '<td>'+input_quantity+'</td>';

            cols += '<td class="<?php echo empty($promotion) ? 'd-none' : ''; ?>">'+free_quantity+'</td>';

            // cols += '<td>' 
            //             +'<input type="number" class="form-control text-right" name="selling_price" step="any" value="'+proforma_invoice_item.selling_price+'">'
            //         +'</td>';
            
            cols += '<td>' 
                    +'<span id="selling_price_span">'
                      +'<input type="number" class="form-control text-right" name="selling_price" step="0.01" value="'+proforma_invoice_item.selling_price+'" readonly>'
                      +'<input type="hidden" name="cost" value="'+proforma_invoice_item.cost+'">'
                    +'</span>'
                  +'</td>';

            cols += '<td>' 
                    +'<span id="price_span">'
                      +'<input type="number" class="form-control text-right" name="price" step="0.01" value="'+proforma_invoice_item.price+'" readonly>'
                    +'</span>'
                  +'</td>';

            cols += '<td class="<?php echo empty($batch_no) ? 'd-none' : ''; ?>"><span name="">'+proforma_invoice_item.batch_no+'</span></td>';

            cols += '<td class="<?php echo empty($mfg_date) ? 'd-none' : ''; ?>">'
                      +'<input type="text" name="mfg_date" value="" class="form-control datepicker" autocomplete="off">'
                    +'</td>';
            cols += '<td class="<?php echo empty($expiry_date) ? 'd-none' : ''; ?>">'
                      +'<input type="text" name="expiry_date" value="" class="form-control datepicker" autocomplete="off">'
                    +'</td>';
           
           
            cols += '<td>'+select_discount+'</td>';
            cols += '<td>'+input_uom+'</td>';
            cols += '<td>'+taxable_value+'</td>';
            cols += '<td class="tax_td">'+tax+'</td>';
           
            cols += '<td>'+tax_type+'</td>';
            cols += '<td><span name="sub_total">'+proforma_invoice_item.sub_total+'</span></td>';

           
            cols += '</tr>';

            // alert(cols);

            newRow.append(cols);
            $("table.product_table").append(newRow);
            calculateRow(newRow);
        }

        $('.select2bs4').select2({theme: 'bootstrap4'});
        $('[data-tt="tooltip"]').tooltip({trigger : 'hover'});
      }

      function add_row(data)
      {
        var customer_country_id = $('#customer_country_id').val();
        var customer_state_id   = $('#customer_state_id').val();

        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var company_gstin       = '<?=$company_setting->gstin?>';

        var product   = data.product;
        var discounts = data.discount;

        var quantity = product.quantity

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
                select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name+'('+discounts[a].value  + type_symbol +')'+ '</option>';
              }
            select_discount += '</select>'
            select_discount += '<span name="span_discount_type" class="discount_type">Discount Value :  <?=$this->session->userdata('currency_symbol');?> </span><input type="hidden" name="discount_type" value="0">';
            select_discount += '<span name="discount_amount" class="discount_amount">0.0</span><input type="hidden" name="discount_value" value="'+0+'">';

        var input_uom = '<input type="hidden" name="uom_id" value="'+product.uom_id+'" data-uom_name="'+product.uom_name+'" data-uom_uom="'+product.uom_uom+'">'+product.uom_uom;
        var input_quantity = "";
        /* input_quantity  +=      'MIN: '+product.minimum_order_quantity+' <span data-tt="tooltip" title="Minimum qty must be ordered by customer"><i class="fas fa-question" style="float:right;font-size:11px;padding-top:5px"></i></span>';*/

        // input_quantity  +=  '<input type="number" class="form-control" name="quantity" value="1" min="1"  step="1" max="'+product.quantity+'">';
        // input_quantity  +=  'MAX: '+product.quantity+' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/>';

        // input_quantity  +=  '<span name="quantity_update_message" class="quantity_update_message"></span>';

        if (quantity === null || quantity === undefined || quantity == '' || quantity == '0.00') 
        {
          //alert('in');
          if(product.manage_inventory == '<?=MANAGE_INVENTORY_NO?>')
          {
            input_quantity  +=  '<input type="number" class="form-control" name="quantity" value="1" min="1"  step="0.01">';
           
          }
          else
          {
            input_quantity  +=  '<input type="number" class="form-control" name="quantity" value="'+product.quantity+'" min="1"  step="1" max="'+product.quantity+'">';
            input_quantity  +=  'MAX: '+product.quantity+' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/>';

            input_quantity  +=  '<span name="quantity_update_message" class="quantity_update_message"></span>';
          }
         
        }
        else
        {
           //alert('out');
          input_quantity  +=  '<input type="number" class="form-control" name="quantity" value="'+product.quantity+'" min="1"  step="1" max="'+product.quantity+'">';
          input_quantity  +=  'MAX: '+product.quantity+' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/>';

          input_quantity  +=  '<span name="quantity_update_message" class="quantity_update_message"></span>';
          
        }


        /*var input_quantity =  '<input type="number" class="form-control" name="quantity" value="1" step="1" min="1" max="'+product.quantity+'"><span name="quantity_update_message" class="quantity_update_message"></span>';*/
        var taxable_value  =  '<span name="taxable_value">'+product.selling_price+'</span>';

        /************    tax begin   *****************/
        var tax = '<input type="hidden" name="tax_id" value="'+product.tax_id+'">';

        // alert(company_state_id);
        // alert(customer_state_id);

        if(company_country_id == customer_country_id)
        {
          
            if(company_state_id == customer_state_id)
            {
              tax += 'CGST : <span name="c_tax">'+0.0+'</span>(<span name="cgst">'+product.cgst+'</span>)<br/>';
              tax += 'SGST : <span name="s_tax">'+0.0+'</span>(<span name="sgst">'+product.sgst+'</span>)';
              tax += '<span name="i_tax"style="display:none">0</span><span name="igst" style="display:none">0</span>';
            }
            else
            {
              tax += 'IGST : <span name="i_tax">'+0.0+'</span>(<span name="igst">'+product.igst+'</span>)<br/>';
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


        /************    tax end   *****************/

        /************    tax type begin   *****************/

        var tax_type  = '<input type="hidden" name="tax_type" value="'+product.tax_type+'">';
        tax_type      += (product.tax_type == 0) ? "No" : "Yes";

        /************    tax type end   *****************/

        var free_quantity = '<input type="number" class="form-control" name="free_quantity" value="0" min="0" step="any">';

        var newRow = $('<tr class="service_row">');
            var cols = "";

            cols += '<td>'
                      + '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
                      + '<input type="hidden" name="product_id" value="'+product.id+'">'
                      + '<input type="hidden" name="proforma_invoice_idd" value="">'
                      + '<input type="hidden" name="warehouse_product_id" value="'+product.warehouse_products_id+'">'  
                      + '<input type="hidden" name="igst_rate" value="'+product.igst+'">'
                      + '<input type="hidden" name="cgst_rate" value="'+product.cgst+'">'
                      + '<input type="hidden" name="sgst_rate" value="'+product.sgst+'">'
                    +'</td>';
            cols += '<td><span name="product_name">'+product.name+'</span><br/><span name="description">'+product.description+'</span></td>';
            cols += '<td>'+input_quantity+'</td>';
            cols += '<td class="<?php echo empty($promotion) ? 'd-none' : ''; ?>">'+free_quantity+'</td>';
            cols += '<td>' 
                      +'<span id="selling_price_span">'
                        +'<input type="number" class="form-control text-right" name="selling_price" step="0.01" value="'+product.selling_price+'">'
                        +'<input type="hidden" name="cost" value="'+product.cost+'">'
                      +'</span>'
                    +'</td>';
            cols += '<td>' 
                    +'<span id="price_span">'
                      +'<input type="number" class="form-control text-right" name="price" step="0.01" value="'+product.price+'" readonly>'
                    +'</span>'
                  +'</td>';
            cols += '<td class="<?php echo empty($batch_no) ? 'd-none' : ''; ?>"><span name="">'+product.batch_no+'</span></td>';
            cols += '<td class="<?php echo empty($mfg_date) ? 'd-none' : ''; ?>">'
                      +'<input type="text" name="mfg_date" value="" class="form-control datepicker" autocomplete="off">'
                    +'</td>';
            cols += '<td class="<?php echo empty($expiry_date) ? 'd-none' : ''; ?>">'
                      +'<input type="text" name="expiry_date" value="" class="form-control datepicker" autocomplete="off">'
                    +'</td>';
            cols += '<td>'+select_discount+'</td>';
            cols += '<td>'+input_uom+'</td>';
            cols += '<td>'+taxable_value+'</td>';
            cols += '<td class="tax_td">'+tax+'</td>';
           
            cols += '<td>'+tax_type+'</td>';
            cols += '<td><span name="sub_total"></span></td>';
            cols += '</tr>';

            newRow.append(cols);
            $("table.product_table").append(newRow);
            $('.select2bs4').select2({theme: 'bootstrap4'});
            $('[data-tt="tooltip"]').tooltip({trigger : 'hover'});
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

      $("table.product_table").on('change', 'input[name^="selling_price"], input[name^="quantity"], select[name^="item_discount"]', function (event) {

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

      $(document).on('change','input[name="tds"]',function(event){
        // alert();
        var tds             = parseFloat($(this).val());
        var total           = parseFloat($('#t').val());
        var total_tax       = parseFloat($('#t_tax').val());
        var total_discount  = parseFloat($('#t_discount').val());

        var total_taxable_value = total-total_tax-tds;
        $('#total_taxable_value').text((total_taxable_value).toFixed(2));
        $('#t_taxable_value').val((total_taxable_value).toFixed(2));
      });

      // function calculateRow(row)
      // {
      //   var tax_type    = row.find('input[name^="tax_type"]').val();

      //   var customer_country_id = $('#customer_country_id').val();
      //   var customer_state_id   = $('#customer_state_id').val();

      //   var company_country_id  = $('#company_country_id').val();
      //   var company_state_id    = $('#company_state_id').val();


      //   if(tax_type == 0)
      //   {
      //     var product_id  = row.find('input[name^="product_id"]').val();
      //     var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
      //     var selling_price       = parseFloat(row.find('input[name^="selling_price"]').val());
      //     var final_discount_value = 0;

      //     var taxable_value   = parseFloat(quantity * selling_price);

      //     var discount_type   = row.find('input[name^="discount_type"]').val();
      //     var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val());  

      //     var final_discount_value = 0;

      //     if(discount_type == 0)
      //     {
      //       final_discount_value = discount_value;
      //     }
      //     else
      //     {
      //       final_discount_value = (taxable_value * discount_value)/100;
      //     }

      //     taxable_value   = taxable_value - final_discount_value;
          
      //     var igst        = 0;
      //     var cgst        = 0;
      //     var sgst        = 0;

      //     if(company_country_id == customer_country_id)
      //     {
      //       if(company_state_id == customer_state_id)
      //       {
      //         cgst        = parseFloat(row.find('input[name^="cgst_rate"]').val());
      //         sgst        = parseFloat(row.find('input[name^="sgst_rate"]').val());
      //       }
      //       else
      //       {
      //         igst        = parseFloat(row.find('input[name^="igst_rate"]').val());
      //       }  
      //     }


      //     if(company_country_id == customer_country_id)
      //     {
          
      //         if(company_state_id == customer_state_id)
      //         {
      //           var igst_tax    = 0 ;
      //           var cgst_tax    = parseFloat((taxable_value * cgst)/100) ;
      //           var sgst_tax    = parseFloat((taxable_value * sgst)/100) ;
      //         }
      //         else
      //         {
      //           var igst_tax    = parseFloat((taxable_value * igst)/100) ;
      //           var cgst_tax    = 0 ;
      //           var sgst_tax    = 0 ;
      //         }  
      
      //     }
      //     else
      //     {
      //       var igst_tax    = 0 ;
      //       var cgst_tax    = 0 ;
      //       var sgst_tax    = 0 ;
      //     }

      //     // var igst_tax    = parseFloat((taxable_value * igst)/100) ;
      //     // var cgst_tax    = parseFloat((taxable_value * cgst)/100) ;
      //     // var sgst_tax    = parseFloat((taxable_value * sgst)/100) ;

      //     var sub_total       = parseFloat(taxable_value + igst_tax + cgst_tax + sgst_tax);

      //     row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
      //     row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
      //     row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));

      //     row.find('span[name^="igst"]').text(igst.toFixed(2));
      //     row.find('span[name^="cgst"]').text(cgst.toFixed(2));
      //     row.find('span[name^="sgst"]').text(sgst.toFixed(2));
          
      //     row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

      //     row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
      //     row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));  
      //   }
      //   else
      //   {
      //     var final_discount_value = 0;
      //     var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
      //     var selling_price       = parseFloat(row.find('input[name^="selling_price"]').val());

      //     var discount_type   = row.find('input[name^="discount_type"]').val();
      //     var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val()); 

      //     var sub_total       = (quantity * selling_price);

      //     if(discount_type == 0)
      //     {
      //       final_discount_value = discount_value;
      //     }
      //     else
      //     {
      //       final_discount_value = (subtotal * discount_value)/100;
      //     }

      //     sub_total = sub_total - final_discount_value;

      //     // alert(final_discount_value);
      //     var igst        = 0;
      //     var cgst        = 0;
      //     var sgst        = 0;

      //     if(company_country_id == customer_country_id)
      //     {
      //       if(company_state_id == customer_state_id)
      //       {
      //         cgst        = parseFloat(row.find('input[name^="cgst_rate"]').val());
      //         sgst        = parseFloat(row.find('input[name^="sgst_rate"]').val());
      //       }
      //       else
      //       {
      //         igst        = parseFloat(row.find('input[name^="igst_rate"]').val());
      //       }  
      //     }


      //     var tax_rate    = igst;

      //     var tax_amount    = parseFloat((sub_total * tax_rate) / (100 + tax_rate));

      //     var igst_tax = 0;
      //     var cgst_tax = 0;
      //     var sgst_tax = 0;

      //     if(company_country_id == customer_country_id)
      //     {
      //       if(company_state_id == customer_state_id)
      //       {
      //         cgst_tax = tax_amount/2;
      //         sgst_tax = tax_amount/2;
      //       }
      //       else
      //       {
      //         igst_tax = tax_amount;
      //       }  
      //     }
      //     else
      //     {
      //       var igst_tax    = 0 ;
      //       var cgst_tax    = 0 ;
      //       var sgst_tax    = 0 ;
      //     }

      //     var taxable_value = sub_total - (igst_tax + cgst_tax + sgst_tax);

      //     row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
      //     row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
      //     row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));

      //     row.find('span[name^="igst"]').text(igst.toFixed(2));
      //     row.find('span[name^="cgst"]').text(cgst.toFixed(2));
      //     row.find('span[name^="sgst"]').text(sgst.toFixed(2));

      //     row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

      //     row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
      //     row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));   

      //   }
        
      // }

      function calculateRow(row)
      {
        var tax_type    = row.find('input[name^="tax_type"]').val();

        var customer_country_id = $('#customer_country_id').val();
        var customer_state_id   = $('#customer_state_id').val();

        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();


        if(tax_type == 0)
        {
          var product_id  = row.find('input[name^="product_id"]').val();
          var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
          var selling_price       = parseFloat(row.find('input[name^="selling_price"]').val());
          var final_discount_value = 0;

          var taxable_value   = parseFloat(quantity * selling_price);

          var discount_type   = row.find('input[name^="discount_type"]').val();
          var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val());  

          var final_discount_value = 0;

          if(discount_type == 0)
          {
            final_discount_value = discount_value;
          }
          else
          {
            final_discount_value = (taxable_value * discount_value)/100;
          }

          taxable_value   = taxable_value - final_discount_value;
          
          var igst        = 0;
          var cgst        = 0;
          var sgst        = 0;

          if(company_country_id == customer_country_id)
          {
            if(company_state_id == customer_state_id)
            {
              cgst        = parseFloat(row.find('input[name^="cgst_rate"]').val());
              sgst        = parseFloat(row.find('input[name^="sgst_rate"]').val());
            }
            else
            {
              igst        = parseFloat(row.find('input[name^="igst_rate"]').val());
            }  
          }


          if(company_country_id == customer_country_id)
          {
          
              if(company_state_id == customer_state_id)
              {
                var igst_tax    = 0 ;
                var cgst_tax    = parseFloat((taxable_value * cgst)/100) ;
                var sgst_tax    = parseFloat((taxable_value * sgst)/100) ;
              }
              else
              {
                var igst_tax    = parseFloat((taxable_value * igst)/100) ;
                var cgst_tax    = 0 ;
                var sgst_tax    = 0 ;
              }  
      
          }
          else
          {
            var igst_tax    = 0 ;
            var cgst_tax    = 0 ;
            var sgst_tax    = 0 ;
          }

          // var igst_tax    = parseFloat((taxable_value * igst)/100) ;
          // var cgst_tax    = parseFloat((taxable_value * cgst)/100) ;
          // var sgst_tax    = parseFloat((taxable_value * sgst)/100) ;

          var sub_total       = parseFloat(taxable_value + igst_tax + cgst_tax + sgst_tax);

          row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
          row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
          row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));

          row.find('span[name^="igst"]').text(igst.toFixed(2));
          row.find('span[name^="cgst"]').text(cgst.toFixed(2));
          row.find('span[name^="sgst"]').text(sgst.toFixed(2));
          
          row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

          row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
          row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));  
        }
        else
        {
          var final_discount_value = 0;
          var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
          var selling_price       = parseFloat(row.find('input[name^="selling_price"]').val());

          var discount_type   = row.find('input[name^="discount_type"]').val();
          var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val()); 

          var sub_total       = (quantity * selling_price);

          if(discount_type == 0)
          {
            final_discount_value = discount_value;
          }
          else
          {
            final_discount_value = (subtotal * discount_value)/100;
          }

          sub_total = sub_total - final_discount_value;

          // alert(final_discount_value);
          var igst        = 0;
          var cgst        = 0;
          var sgst        = 0;

          if(company_country_id == customer_country_id)
          {
            if(company_state_id == customer_state_id)
            {
              cgst        = parseFloat(row.find('input[name^="cgst_rate"]').val());
              sgst        = parseFloat(row.find('input[name^="sgst_rate"]').val());
            }
            else
            {
              igst        = parseFloat(row.find('input[name^="igst_rate"]').val());
            }  
          }


          var tax_rate    = igst+cgst+sgst;

          var tax_amount    = parseFloat((sub_total * tax_rate) / (100 + tax_rate));

          var igst_tax = 0;
          var cgst_tax = 0;
          var sgst_tax = 0;

          if(company_country_id == customer_country_id)
          {
            if(company_state_id == customer_state_id)
            {
              cgst_tax = tax_amount/2;
              sgst_tax = tax_amount/2;
            }
            else
            {
              igst_tax = tax_amount;
            }  
          }
          else
          {
            var igst_tax    = 0 ;
            var cgst_tax    = 0 ;
            var sgst_tax    = 0 ;
          }

          var taxable_value = sub_total - (igst_tax + cgst_tax + sgst_tax);

          row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
          row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
          row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));

          row.find('span[name^="igst"]').text(igst.toFixed(2));
          row.find('span[name^="cgst"]').text(cgst.toFixed(2));
          row.find('span[name^="sgst"]').text(sgst.toFixed(2));

          row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

          row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
          row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));   

        }
        
      }

      function calculateGrandTotal()
      {
        var total_taxable_value = 0.0;
        var total_cgst          = 0.0;
        var total_sgst          = 0.0;
        var total_igst          = 0.0;
        var total               = 0.0;
        var total_discount      = 0.0;

        // TDS part
        var tds                 = parseFloat($('#tds').val()); 


        $("#product_table_body").find('tr').each(function () {
          var tr              = $(this).closest("tr");
          total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text());
          total_discount      += parseFloat(tr.find('span[name^="discount_amount"]').text());
          total_cgst          += parseFloat(tr.find('span[name^="c_tax"]').text());
          total_sgst          += parseFloat(tr.find('span[name^="s_tax"]').text());
          total_igst          += parseFloat(tr.find('span[name^="i_tax"]').text());
          total               += parseFloat(tr.find('span[name^="sub_total"]').text()); 
        });

        $('#total_taxable_value').text(parseFloat(total_taxable_value.toFixed(2))-tds);
        $('#t_taxable_value').val(parseFloat(total_taxable_value.toFixed(2))-tds);

        $('#total_discount').text(total_discount.toFixed(2));
        $('#t_discount').val(total_discount.toFixed(2));

        $('#total_tax').text((total_cgst+total_sgst+total_igst).toFixed(2));
        $('#t_tax').val((total_cgst+total_sgst+total_igst).toFixed(2));

        $('#total').text(total.toFixed(2));
        $('#t').val(total.toFixed(2));
      }

      $('#editSaleForm').submit(function(e){
        // e.preventDefault();

        var isError = false;
        $('#saleSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $('form#editSaleForm .field_validation').each(function() {
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editSaleForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#editSaleForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editSaleForm #err_"+id).text("").fadeOut('slow');
            $('form#editSaleForm #'+id).removeClass('is-invalid');
            $('form#editSaleForm #'+id).addClass('is-valid');
          }
        });

        var productDataArray = [];

        $("#product_table_body").find('tr').each(function () {

          var tr              = $(this).closest("tr");
          var productData     = {};

          productData['product_id']       = tr.find('input[name="product_id"]').val();
          productData['proforma_invoice_idd']  = tr.find('input[name="proforma_invoice_idd"]').val();
          productData['warehouse_product_id'] = tr.find('input[name="warehouse_product_id"]').val();
          productData['product_name']     = tr.find('span[name="product_name"]').text();
          productData['description']      = tr.find('span[name="description"]').text();
          productData['quantity']         = tr.find('input[name="quantity"]').val();
          productData['cost']             = tr.find('input[name="cost"]').val();
          productData['selling_price']            = tr.find('input[name="selling_price"]').val();
          productData['price']            = tr.find('input[name="price"]').val();

          productData['mfg_date']      = tr.find('input[name="mfg_date"]').val();
          productData['expiry_date']      = tr.find('input[name="expiry_date"]').val();

          productData['taxable_value']    = tr.find('span[name="taxable_value"]').text();
          productData['discount_id']      = tr.find('select[name="item_discount"]').val();
          productData['discount_type']    = tr.find('input[name="discount_type"]').val();
          productData['discount_value']   = tr.find('input[name="discount_value"]').val();
          productData['discount_amount']  = tr.find('span[name="discount_amount"]').text();
          productData['uom_id']           = tr.find('input[name="uom_id"]').val();
          productData['uom_name']         = tr.find('input[name="uom_id"]').data('uom_name');
          productData['uom_uom']          = tr.find('input[name="uom_id"]').data('uom_uom');
          productData['tax_id']           = tr.find('input[name="tax_id"]').val();
          productData['tax_type']         = tr.find('input[name="tax_type"]').val();
          productData['igst']             = tr.find('span[name="igst"]').text();
          productData['igst_tax']         = tr.find('span[name="i_tax"]').text();
          productData['cgst']             = tr.find('span[name="cgst"]').text();
          productData['cgst_tax']         = tr.find('span[name="c_tax"]').text();
          productData['sgst']             = tr.find('span[name="sgst"]').text();
          productData['sgst_tax']         = tr.find('span[name="s_tax"]').text();
          productData['sub_total']        = tr.find('span[name="sub_total"]').text();

          productData['free_quantity']          = tr.find('input[name="free_quantity"]').val();

          productDataArray.push(JSON.stringify(productData));
        });

        if(productDataArray.length > 0)
        {
          $('#sale_items').val(productDataArray.join('|'));
        }
        else
        {
          isError = true;
          $('#emptySaleItemWarningModal').modal('show');
        }

        if(isError == true)
        {
          $('#saleSubmit').text('<?=$this->lang->line("sale_add")?>').removeAttr('disabled');
          return false;
        }
        else 
        {
          return true;
        }
      });

      $("form#editSaleForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editSaleForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#editSaleForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editSaleForm #err_"+id).text("").fadeOut('slow');
          $('form#editSaleForm #'+id).removeClass('is-invalid');
          $('form#editSaleForm #'+id).addClass('is-valid');
        }
      });

      $('.rcm_btn').click(function(e){
        e.preventDefault();

        if($('#product_table_body tr').length > 0)
        {
          $('.rcm-confirmation-body').html('<?=$this->lang->line('sale_rcm_confirmation_message')?>');

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
                $('.rcm_btn').text('<?=$this->lang->line('sale_disable_rcm')?>');
              }
              else
              {
                $('#rcm').val('0');
                $('.rcm_btn').text('<?=$this->lang->line('sale_enable_rcm')?>');
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
            $('.rcm_btn').text('<?=$this->lang->line('sale_disable_rcm')?>');
          }
          else
          {
            $('#rcm').val('0');
            $('.rcm_btn').text('<?=$this->lang->line('sale_enable_rcm')?>');
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
        /* $('#shipping_country_id').val($('#country_id').val());*/
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
          /*$('#shipping_country_id').val("");*/
          $('form#addCustomerForm #shipping_country_id').val("");;
          $('form#addCustomerForm #shipping_state_id').val("");;
          $('form#addCustomerForm #shipping_city_id').val("");;
          $('form#addCustomerForm #shipping_address').val("");
          $('form#addCustomerForm #shipping_pincode').val("");
          
        };
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
              url: "<?php echo base_url('sale/upload_documents')?>", // Replace with your server-side script URL
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