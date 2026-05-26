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

  $selling_price = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'selling_price', 'active',$row = true,$check_delete_status = false);
    
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

.profit_data {
    margin-top: 20px;
    background-color: #f8f9fa;
    border: 2px solid #dee2e6;
}
.quantity-input.is-invalid {
    border-color: #dc3545;
    box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
}

.quantity-input.is-valid {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.highlight_row {
    background-color: #fff3cd !important;
    transition: background-color 0.3s ease;
}
.profit_data tr:last-child {
    font-weight: bold;
    background-color: #e9ecef;
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
    .service_row{
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
      /*padding-left: 20px;*/
    }
    .discount_amount{
      /*padding-right: 10px !important;*/
    }
    .delete_item{
      cursor:pointer;
    }

    .total_data{
      font-size: 18px;
      font-weight: bolder;
    }
   /* General Table Styling */
.product_table {
  border-collapse: collapse;
  width: 100%;
  font-size: 14px; /* Adjust size based on design */
  overflow-x: auto; /* Allow horizontal scroll */
}

.product_table th, .product_table td {
  text-align: left;
  padding: 8px;
  vertical-align: middle;
}

.product_table th {
  background-color: #f4f4f4;
  font-weight: bold;
  text-align: center;
}

/* Sticky Header for Better UX */
.product_table thead th {
  position: sticky;
  top: 0;
  z-index: 1;
}

/* Responsive Column Sizes */
.product_table th, .product_table td {
  min-width: 50px; /* Ensure a minimum width for readability */
  max-width: 300px; /* Prevent excessively wide columns */
  word-wrap: break-word; /* Prevent text overflow */
}

/* Scrollbars for Smaller Screens */
.table-responsive {
    overflow-x: hidden !important; /* Removes horizontal scroll */
  -webkit-overflow-scrolling: touch; /* Smooth scrolling for mobile */
}

/* Media Query for Smaller Screens */
@media (max-width: 1024px) {
  .product_table th, .product_table td {
    font-size: 12px; /* Adjust font size for laptops */
    padding: 6px;
  }
  .product_table {
    font-size: 12px;
  }
}
#product_table_body input {
    width: 7em !important;
  }
  /* Force all table cells to display content in single line */
.product_table th,
.product_table td {
  white-space: nowrap !important;
}

/* Make the table horizontally scrollable */
.table-responsive {
  overflow-x: auto !important;
  -webkit-overflow-scrolling: touch;
}

/* Ensure table has minimum width to show all columns in single line */
.product_table {
  min-width: 1400px;
  width: 100%;
  table-layout: auto;
}

/* Keep input fields in single line */
#product_table_body input,
#product_table_body select,
#product_table_body textarea {
  white-space: nowrap !important;
  width: auto !important;
  min-width: 80px;
}

/* Specific width for quantity input */
#product_table_body input[name="quantity"] {
  width: 85px !important;
  min-width: 85px;
}

/* Specific width for selling price and cost */
#product_table_body input[name="selling_price"],
#product_table_body input[name="freight_selling_price"], /* Add this line */
#product_table_body input[name="vendor_cost"] {
  width: 100px !important;
  min-width: 100px;
}

/* Product name column - allow some width but keep in single line */
.product_table td span[name="product_name"] {
  white-space: nowrap !important;
  display: inline-block;
}

/* UOM, HSN spans in single line */
.product_table td span[name="hsn"],
.product_table td span[name="taxable_value"],
.product_table td span[name="sub_total"] {
  white-space: nowrap !important;
}

/* Tax cell in single line */
.tax_td {
  white-space: nowrap !important;
}

/* Vendor select dropdown */
.supplier-select {
  min-width: 130px !important;
  width: auto !important;
}

/* Remove any break lines */
.tax_td br {
  display: none;
}

/* Ensure the MAX: text appears inline with quantity */
.product_table td .quantity-input + br {
  display: none;
}

.product_table td .quantity-input + span {
  display: inline-block;
  margin-left: 5px;
  font-size: 11px;
}
.service_row td {
    padding-top: 15px !important;   /* same top padding */
    padding-bottom: 10px;           /* optional */
    vertical-align: top;            /* important for alignment */
}

#due_date_display {
    background-color: #e9ecef; /* Standard gray for readonly fields */
    cursor: not-allowed;        /* Shows the 'disabled' cursor */
    color: #495057;
    opacity: 1;
    padding: 6px 12px;         /* Standard input padding */
}
  </style>
  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                 <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
               
                <li class="breadcrumb-item "><a href="<?=base_url('sale')?>"><?=$this->lang->line('sale_header')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('sale_add')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="addSaleForm" id="addSaleForm" method="POST" action="<?=base_url('sale/add')?>">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('sale_add')?></h3>
                  <div class="card-tools">

                    <ul class="nav nav-pills ml-auto">
                      <li class="nav-item  ml-2">
                        <a class="nav-link reset btn-sm btn-warning" href="#" data-tt="tooltip" title="Click here to Reset Sale Items">
                          <i class="fas fa-redo-alt"></i> Reset
                        </a>
                      </li>


                      <li class="nav-item ml-2">
                        <a class="nav-link active" href="<?=base_url('sale')?>" data-tt="tooltip" title="Click here to show sale list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                      </li>

                      <li class="nav-item ml-2 d-none">
                        <div class="btn-group">
                          <button type="button" class="btn btn-tool btn-warning rcm_btn" data-tt="tooltip" title="<?=$this->lang->line('sale_rcm_change_status')?>">
                            <?=$this->lang->line('sale_enable_rcm')?>
                          </button>
                          <button type="button" class="btn btn-tool btn-warning" data-tt="tooltip" title="<?=$this->lang->line('sale_rcm_help')?>">
                            <i class="far fa-question-circle"></i>
                          </button>
                      </div>
                      </li>

                    </ul>
                    
                  </div>
                </div>
                <div class="card-body">
                  <div class="col-sm-12">
                    <div class="row">

                      <!--<div class="col-sm-2">-->
                      <!--  <div class="form-group">-->
                      <!--    <label><?=$this->lang->line('sale_due_date')?></label>-->
                      <!--    <input type="hidden" class="form-control" id="due_date" name="due_date" value="" readonly="readonly">-->
                      <!--  </div>-->
                      <!--</div>-->


                       <!--  <div class="col-sm-3">
                            <div class="form-group">
                                <label for="customer">
                                    <?=$this->lang->line('sale_customer')?>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-sm">
                                 <select class="form-control form-control-sm select2bs4 field_validation" 
                                    name="customer_id" 
                                    id="customer_id" 
                                    width="100%" 
                                    class="add-row" 
                                    placeholder="<?= $this->lang->line('sale_customer') ?>">
                            
                                <option value=""><?= $this->lang->line('select') ?></option>
                            
                                <?php if(!empty($customers)): ?>
                                    <?php foreach ($customers as $value): ?>
                                        <option value="<?= $value->id; ?>" 
                                                data-ledger_id="<?= $value->ledger_id ?>" 
                                                <?= set_select('customer_id', $value->id); ?>>
                                            <?= $value->customer_name; ?>
                                            <?php if (!empty($value->customer_company_name)): ?>
                                                (<?= $value->customer_company_name; ?>)
                                            <?php endif; ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>

                                    <span class="input-group-append">
                                        <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_customer_modal" data-tt="tooltip" accesskey="c">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </span>
                                </div>
                                <input type="hidden" name="customer_state_id" id="customer_state_id" value="">
                                <input type="hidden" name="customer_country_id" id="customer_country_id" value="">
                                <span id="err_customer_id" class="error invalid-feedback"><?= form_error('customer_id'); ?></span>
                            </div>
                        </div>-->
                        <div class="col-sm-3">
    <div class="form-group">
        <label for="customer">
            Company Name
            <span class="text-danger">*</span>
        </label>
        <div class="input-group input-group-sm">
            <select class="form-control form-control-sm select2bs4 field_validation" 
                name="customer_id" 
                id="customer_id" 
                width="100%" 
                class="add-row" 
                placeholder="<?= $this->lang->line('sale_customer') ?>">
            
                <option value=""><?= $this->lang->line('select') ?></option>
            
                <?php if(!empty($customers)): ?>
                    <?php foreach ($customers as $value): ?>
                        <option value="<?= $value->id; ?>" 
                                data-ledger_id="<?= $value->ledger_id ?>" 
                                <?= set_select('customer_id', $value->id); ?>>
                            <?= $value->customer_company_name; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <span class="input-group-append">
                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_customer_modal" data-tt="tooltip" accesskey="c">
                    <i class="fas fa-plus"></i>
                </button>
            </span>
        </div>
        <input type="hidden" name="customer_state_id" id="customer_state_id" value="">
        <input type="hidden" name="customer_country_id" id="customer_country_id" value="">
        <span id="err_customer_id" class="error invalid-feedback"><?= form_error('customer_id'); ?></span>
    </div>
</div>

                         <div class="col-sm-3">
                            <div class="form-group">
                                <label for="warehouse">
                                    <?=$this->lang->line('purchase_warehouse')?>
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group input-group-sm">
                                    <?php 
                                        $user_id = $this->session->userdata('user_id');
                                        $user = $this->db->get_where('users', ['id' => $user_id])->row();
                        
                                        // Check if the user has a branch ID (warehouse) assigned
                                        if ($user && isset($user->branch_id) && $user->branch_id): 
                                            $user_branch_id = $user->branch_id;
                                            $warehouse_name = '';
                                            // Find the warehouse name by the branch ID
                                            foreach ($warehouses as $value) {
                                                if ($value->id == $user_branch_id) {
                                                    $warehouse_name = $value->name;
                                                    break;
                                                }
                                            }
                                    ?>
                                        <!-- Hidden input for the warehouse_id -->
                                        <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$user_branch_id;?>">
                                        <!-- Display warehouse name in a readonly input -->
                                        <input type="text" class="form-control form-control-sm" value="<?=$warehouse_name;?>" readonly>
                                    <?php else: ?>
                                        <!-- Dropdown for selecting a warehouse if no default warehouse is assigned -->
                                        <select class="form-control form-control-sm select2bs4 field_validation" name="warehouse_id" id="warehouse_id" width="100%" placeholder="<?=$this->lang->line('purchase_warehouse')?>">
                                            <option value=""><?=$this->lang->line('select')?></option>
                                            <?php foreach ($warehouses as $value): ?>
                                                <option value="<?=$value->id;?>" <?= ($value->id == set_value('warehouse_id')) ? 'selected' : ''; ?> selected>
                                                    <?= $value->name;?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                        <span class="input-group-append">
                                            <!-- Button to open the modal for adding a new warehouse -->
                                            <button type="button" class="btn btn-info btn-flat add_warehouse_modal" data-toggle="modal" data-target="#add_warehouse_modal" data-tt="tooltip" accesskey="c"><i class="fas fa-plus"></i></button>
                                        </span>
                                    <?php endif; ?>
                                    <span id="err_warehouse_id" class="error invalid-feedback"><?=form_error('warehouse_id');?></span>
                                </div>
                            </div>
                        </div>
                        
                          <div class="col-sm-2">
                            <div class="form-group">
                                <label><?=$this->lang->line('sale_reference_no')?></label>
                                <input type="text" class="form-control" id="reference_no" name="reference_no" 
                                       value="<?=isset($reference_no) ? $reference_no : ''?>" readonly>
                            </div>
    
                          </div>                        
                                              
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_invoice_date')?></label>
                          <input type="text" class="form-control datepicker" id="invoice_date" name="invoice_date" value="<?=date('d-m-Y');?>">
                          <span id="err_invoice_date" class="error invalid-feedback"><?=form_error('invoice_date');?></span>
                        </div>
                      </div>
                      
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Due Date</label>
                                <div id="due_date_display" class="form-control" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 32px;">
                                    <!-- Due date will appear here -->
                                </div>
                            </div>
                        </div>
                      
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label>Select Shipping Address</label>
                                <select class="form-control form-control-sm" id="shipping_address_select" name="shipping_address_select">
                                    <option value="">Select Shipping Address</option>
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
                            <option value="<?=$value->id;?>" <?php echo set_select('customer_shipping_country_id', $value->id); ?>>
                              <?= $value->name;?>
                            </option>
                          <?php 
                            }
                          ?>
                        </select>
                        <span id="err_customer_shipping_country_id" class="error invalid-feedback"><?=form_error('phone');?></span>
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>
                            <?=$this->lang->line('customer_shipping_state_id')?>
                            <span class="text-danger">*</span>
                          </label>
                          <select class="form-control form-control-sm select2bs4 field_validation"  name="customer_shipping_state_id" id="customer_shipping_state_id" width="100%" placeholder="<?=$this->lang->line('customer_shipping_state_id')?>" tabindex="<?=$tabindex++?>">
                                <option value=""></option>
                              
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
                                <option value="<?=$value->id;?>" <?php echo set_select('customer_shipping_city_id', $value->id); ?>>
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
                          <input type="text" name="customer_shipping_address" value="" class="form-control field_validation" id="customer_shipping_address" placeholder="<?=$this->lang->line('customer_shipping_address')?>" tabindex="<?=$tabindex++?>"><?=form_error('shipping_address', '<div class="text-danger">', '</div>');?>
                          <span id="err_customer_shipping_address" class="error invalid-feedback"><?=form_error('customer_shipping_address', '<div class="text-danger">', '</div>');?></span>
                        </div>
                      </div>

                      <div class="col-sm-3">
                        <div class="form-group">
                          <label>
                            <?=$this->lang->line('customer_shipping_pincode')?>
                            <span class="text-danger">*</span>
                          </label>
                          <input type="text" name="customer_shipping_pincode" value="<?=set_value('customer_shipping_pincode') ?>" class="form-control field_validation" id="customer_shipping_pincode" placeholder="<?=$this->lang->line('customer_shipping_pincode')?>" tabindex="<?=$tabindex++?>">
                          <span id="err_customer_shipping_pincode" class="error invalid-feedback"><?=form_error('customer_shipping_pincode', '<div class="text-danger">', '</div>');?></span>
                        </div>
                      </div>

                    </div>

                    <div class="row">

                      
                      
                      <div class="col-sm-2 <?= (empty($lr_no) || $lr_no->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_lori_no')?></label>
                          <input type="text" class="form-control" id="lori_no" name="lori_no" value="<?=set_value('lori_no')?>">
                          <span id="err_lori_no" class="error invalid-feedback"><?=form_error('lori_no');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2 <?= (empty($lr_date) || $lr_date->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_lori_date')?></label>
                          <input type="text" class="form-control datepicker" id="lori_date" name="lori_date" value="<?=set_value('lori_date')?>">
                          <span id="err_lori_date" class="error invalid-feedback"><?=form_error('lori_date');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2 <?= (empty($carrier) || $carrier->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_carrier')?></label>
                          <input type="text" class="form-control" id="carrier" name="carrier" value="<?=set_value('carrier')?>">
                          <span id="err_carrier" class="error invalid-feedback"><?=form_error('carrier');?></span>
                        </div>
                      </div>

                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_vehicle_no')?></label>
                          <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" value="<?=set_value('vehicle_no')?>">
                          <span id="err_vehicle_no" class="error invalid-feedback"><?=form_error('vehicle_no');?></span>
                        </div>
                      </div>
                      
                      <div class="col-sm-2">
                        <div class="form-group">
                            <label>Number of Boxes</label>
                            <input type="number" class="form-control" id="no_of_boxes" name="no_of_boxes" value="<?=set_value('no_of_boxes', 1)?>" min="1">
                            <span id="err_no_of_boxes" class="error invalid-feedback"><?=form_error('no_of_boxes');?></span>
                        </div>
                    </div>
                      
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Select Payment Terms</label>
                                <select class="form-control select2bs4" style="width: 100%;" id="due_days" name="due_days">
                                    <option value="">Select Due Days</option>
                                    <?php foreach($due_days_options as $option): ?>
                                        <option value="<?= $option->due_day ?>" data-terms="<?= htmlspecialchars($option->terms_and_condition) ?>">
                                            <?= $option->due_day ?> Days
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <span id="err_due_days" class="error invalid-feedback"><?= form_error('due_days'); ?></span>
                            </div>
                        </div>
                        
                        <!-- Add this div to display terms (you can place it where you want the terms to appear) -->
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label>Payment terms condition</label>
                                <div id="due_days_terms" class="form-control" style="padding: 10px; border: 1px solid #ddd; border-radius: 4px; min-height: 32px;">
                                     <!-- Terms will appear here when a due day is selected -->
                                </div>
                            </div>
                        </div>
                        <input type="hidden" name="payment_terms" id="payment_terms" value="">
                        
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Purchase Order No</label>
                                <input type="text" class="form-control" id="purchase_order_no" name="purchase_order_no" value="<?=set_value('purchase_order_no')?>">
                                <span id="err_purchase_order_no" class="error invalid-feedback"><?=form_error('purchase_order_no');?></span>
                            </div>
                        </div>
                        
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Purchase Order Date</label>
                                <input type="date" class="form-control" id="purchase_order_date" name="purchase_order_date" value="<?=set_value('purchase_order_date')?>">
                                <span id="err_purchase_order_date" class="error invalid-feedback"><?=form_error('purchase_order_date');?></span>
                            </div>
                        </div>
                      
                      <div class="col-sm-3 <?= (empty($ewaybill_no) || $ewaybill_no->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_no')?></label>
                          <input type="text" class="form-control" id="ewaybill_no" name="ewaybill_no" value="<?=set_value('ewaybill_no')?>">
                          <span id="err_ewaybill_no" class="error invalid-feedback"><?=form_error('ewaybill_no');?></span>
                        </div>
                      </div>

                      <div class="col-sm-3 <?= (empty($dispatch_from) || $dispatch_from->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_dispatch_from')?></label>
                          <select class="form-control ewaybill_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('sale_ewaybill_dispatch_from')?>" id="ewaybill_dispatch_from" name="ewaybill_dispatch_from">
                            <?php 
                              foreach ($states as $st) 
                              { 
                            ?>
                                <option value="<?=$st->id?>"
                                  <?php 
                                    if($st->id == $company_setting->state_id)
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
                          <input type="text" class="form-control" id="additional_case" name="additional_case" value="<?=set_value('additional_case')?>">
                          <span id="err_additional_case" class="error invalid-feedback"><?=form_error('additional_case');?></span>
                        </div>
                      </div>

                     
                      <div class="col-sm-3 <?= (empty($mode_of_transportation) || $mode_of_transportation->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_mode_of_transportation')?></label>
                          <select class="form-control ewaybill_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('sale_ewaybill_mode_of_transportation')?>" id="ewaybill_mode_of_transportation" name="ewaybill_mode_of_transportation">
                            <?php 
                              $ewaybill_mode_of_transportation_array = enum_select('sale','ewaybill_mode_of_transportation');
                              for($i = 0; $i < sizeof($ewaybill_mode_of_transportation_array); $i++)
                              {
                            ?>
                                <option value="<?=$ewaybill_mode_of_transportation_array[$i]?>"><?=clean_e_val($ewaybill_mode_of_transportation_array[$i])?></option>
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
                            <?php 
                              $ewaybill_subtype_array = enum_select('sale','ewaybill_subtype');
                              for($i = 0; $i < sizeof($ewaybill_subtype_array); $i++)
                              {
                            ?>
                                <option value="<?=$ewaybill_subtype_array[$i]?>"><?=clean_e_val($ewaybill_subtype_array[$i])?></option>
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
                            <?php 
                              $ewaybill_doctype_array = enum_select('sale','ewaybill_doctype');
                              for($i = 0; $i < sizeof($ewaybill_doctype_array); $i++)
                              {
                            ?>
                                <option value="<?=$ewaybill_doctype_array[$i]?>"><?=clean_e_val($ewaybill_doctype_array[$i])?></option>
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
                          <input type="text" class="form-control " id="ewaybill_transporter_gstin" name="ewaybill_transporter_gstin" value="<?=set_value('ewaybill_transporter_gstin')?>">
                          <span id="err_ewaybill_transporter_gstin" class="error invalid-feedback"><?=form_error('ewaybill_transporter_gstin');?></span>
                        </div>
                      </div>
                      
                    </div>
                    <div class="row">
                      <div class="col-sm-3 <?= (empty($transporter_name) || $transporter_name->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <!--<label><?=$this->lang->line('sale_ewaybill_transporter_name')?></label>-->
                          <label>Transporter/Courier Name</label>
                          <input type="text" class="form-control " id="ewaybill_transporter_name" name="ewaybill_transporter_name" value="<?=set_value('ewaybill_transporter_name')?>">
                          <span id="err_ewaybill_transporter_name" class="error invalid-feedback"><?=form_error('ewaybill_transporter_name');?></span>
                        </div>
                      </div>
                      <div class="col-sm-3 <?= (empty($distance_of_transportation) || $distance_of_transportation->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_distance_of_transportation')?></label>
                          <input type="number" class="form-control" id="ewaybill_distance_of_transportation" name="ewaybill_distance_of_transportation" value="<?=set_value('ewaybill_distance_of_transportation')?>" step="0.01" max="4000">
                          <span id="err_ewaybill_distance_of_transportation" class="error invalid-feedback"><?=form_error('ewaybill_distance_of_transportation');?></span>
                        </div>
                      </div>
                      <div class="col-sm-3 <?= (empty($transporter_doc_no) || $transporter_doc_no->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <!--<label><?=$this->lang->line('sale_ewaybill_transporter_doc_no')?></label>-->
                            <label>Transporter/Courier Doc No</label>
                          <input type="text" class="form-control" id="ewaybill_transporter_doc_no" name="ewaybill_transporter_doc_no" value="<?=set_value('ewaybill_transporter_doc_no')?>">
                          <span id="err_ewaybill_transporter_doc_no" class="error invalid-feedback"><?=form_error('ewaybill_transporter_doc_no');?></span>
                        </div>
                      </div>
                      <div class="col-sm-3 <?= (empty($transporter_doc_date) || $transporter_doc_date->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_transporter_doc_date')?></label>
                          <input type="text" class="form-control datepicker" id="ewaybill_transporter_doc_date" name="ewaybill_transporter_doc_date" value="<?=set_value('ewaybill_transporter_doc_date')?>">
                          <span id="err_ewaybill_transporter_doc_date" class="error invalid-feedback"><?=form_error('ewaybill_transporter_doc_date');?></span>
                        </div>
                      </div>
                      
                        <div class="col-sm-2">
                            <div class="form-group">
                                <label>Dispatch Date</label>
                                <input type="date" class="form-control" id="dispatch_date" name="dispatch_date" value="<?=set_value('dispatch_date')?>">
                                <span id="err_dispatch_date" class="error invalid-feedback"><?=form_error('dispatch_date');?></span>
                            </div>
                        </div>
                      
                      <!--LUT no-->
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label>LUT No</label>
                          <input type="text" class="form-control" id="lut_no" name="lut_no" value="<?=set_value('lut_no')?>">
                          <span id="err_lut_no" class="error invalid-feedback"><?=form_error('lut_no');?></span>
                        </div>
                      </div>
                      
                      <div class="col-sm-2 <?= (empty($eway_vehicle_no) || $eway_vehicle_no->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_vehicle_no')?></label>
                          <input type="text" class="form-control" id="ewaybill_vehicle_no" name="ewaybill_vehicle_no" value="<?=set_value('ewaybill_vehicle_no')?>">
                          <span id="err_ewaybill_vehicle_no" class="error invalid-feedback"><?=form_error('ewaybill_vehicle_no');?></span>
                        </div>
                      </div>
                      <div class="col-sm-2 <?= (empty($vehicle_type) || $vehicle_type->field_status !== 'active') ? 'd-none' : '' ?>">
                        <div class="form-group">
                          <label><?=$this->lang->line('sale_ewaybill_vehicle_type')?></label>
                          <select class="form-control ewaybill_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('sale_ewaybill_vehicle_type')?>" id="ewaybill_vehicle_type" name="ewaybill_vehicle_type">
                            <?php 
                              $ewaybill_vehicle_type_array = enum_select('sale','ewaybill_vehicle_type');
                              for($i = 0; $i < sizeof($ewaybill_vehicle_type_array); $i++)
                              {
                            ?>
                                <option value="<?=$ewaybill_vehicle_type_array[$i]?>"><?=clean_e_val($ewaybill_vehicle_type_array[$i])?></option>
                            <?php 
                              } 
                            ?>
                          </select>
                          <span id="err_ewaybill_vehicle_type" class="error invalid-feedback"><?=form_error('ewaybill_vehicle_type');?></span>
                        </div>
                      </div>
                    </div>

                    <!--<div class="row">-->
                    <!--  <div class="col-sm-8">-->
                    <!--    <div class="form-group">-->
                    <!--      <select class="form-control form-control-sm select2bs4" name="proforma_invoice_id[]" id="proforma_invoice_id" width="100%" class="add-row" multiple="multiple" placeholder="Proforma invoice Items">-->
                    <!--        <option value=""><?=$this->lang->line('select')?></option>-->
                    <!--      </select>-->
                    <!--      <span id="err_proforma_invoice_id" class="error invalid-feedback"><?=form_error('proforma_invoice_id');?></span>-->
                    <!--    </div>-->
                    <!--  </div>-->
                    <!--  <div class="col-sm-4">-->
                    <!--    <div class="row">-->
                    <!--      <button type="button" class="btn btn-primary" name="get_proforma_invoice_items" id="get_proforma_invoice_items" >Get Proforma Invoice Items</button>-->

                    <!--      <button type="button" class="btn btn-primary ml-1" name="refresh_proforma_invoice_items" id="refresh_proforma_invoice_items" >Refresh Proforma Invoice Items</button>-->
                    <!--    </div>-->
                    <!--  </div>-->

                    <!--</div>-->

                    <div class="row">
                      <div class="col-sm-12">
                        <div class="form-group">
                          <label><?=$this->lang->line('cdn_document')?></label>
                            <div class="input-group input-group-sm">
                              <div class="custom-file">
                               <input type="file" 
                                   class="custom-file-input" 
                                   id="upload_document" 
                                   name="upload_document[]" 
                                   accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx" 
                                   multiple>
                                <label class="custom-file-label" for="exampleInputFile">Choose file</label>
                              </div>
                              <input type="hidden" value="" name="document[]" id="document">
                            </div>
                            <span id="fileTypeError" class="error-message"></span> 
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
                              <input id="search_product" class="form-control search_product"  type="text" name="search_product"  placeholder="<?=$this->lang->line('sale_item_search')?>">
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
                             <div class="table-responsive" style="max-width: 100%; overflow-x: auto;">
                              <table class="table items table-striped table-bordered table-condensed table-hover product_table" 
                                     style="min-width: 1200px; table-layout: auto;" id="product_data">
                                <thead>
                                  <tr class="service_row">
                                    <th style="width: 5%;">
                                      <img src="<?php echo base_url(); ?>assets/images/bin1.png" alt="Bin">
                                    </th>
                                    <th class="span2" width="22%">Product Name</th>
                                    <th class="span2" width="10%">Add Remark</th>
                                    <th class="span2" width="12%">Vendor</th> <!-- New column -->
                                    <!--<th class="span2" width="8%">Purchase Cost</th>  New column -->
                                    <th class="span2" width="8%">Qty</th>
                                    <!--<th class="<?= (empty($promotion) || $promotion->field_status !== 'active') ? 'd-none' : '' ?>" -->
                                    <!--    style="min-width: 70px;">-->
                                    <!--  <?=$this->lang->line('proforma_invoice_free_qty')?>-->
                                    <!--</th>-->
                                    <th class="span2" width="10%"><?=$this->lang->line('sale_selling_price')?></th>
                                    <th class="span2" width="15%"><?=$this->lang->line('sale_hsn')?></th>
                                    <!--<th style="min-width: 100px;"><?=$this->lang->line('sale_price')?></th>-->
                                    <!--<th class="<?= (empty($batch_no) || $batch_no->field_status !== 'active') ? 'd-none' : '' ?>" -->
                                    <!--    style="min-width: 100px;">-->
                                    <!--  <?=$this->lang->line('proforma_invoice_batch')?>-->
                                    <!--</th>-->
                                    
          <!--<th class="span2 <?= (empty($mfg_date) || $mfg_date->field_status !== 'active') ? 'd-none' : '' ?>" width="100px">-->
          <!--                        <?= $this->lang->line('product_mfg_date') ?>-->
          <!--                      </th>-->
          <!--                      <th class="span2 <?= (empty($expiry_date) || $expiry_date->field_status !== 'active') ? 'd-none' : '' ?>" width="100px">-->
          <!--                        <?= $this->lang->line('product_expiry_date') ?>-->
          <!--                      </th>-->
                                <th class="span2 d-none" width="100px"><?=$this->lang->line('sale_discount')?></th>
                                <th class="span2" width="11%"><?=$this->lang->line('sale_uom')?></th>
                                <th class="span2" width="8%"><?=$this->lang->line('sale_taxable_value')?></th>
                                <th class="span2" width="12%"><?=$this->lang->line('sale_tax')?>(₹)</th>
                                <th class="span2 d-none" width="100px"><?=$this->lang->line('sale_inclusive')?></th>
                                <th class="span2" width="7%"><?=$this->lang->line('sale_total')?></th>
                              </tr>
                            </thead>
                            <tbody id="product_table_body">
                              <!-- Dynamic rows go here -->
                            </tbody>
                          </table>
                        </div>

<!--<table class="table table-striped table-bordered table-condensed table-hover total_data">-->
  <!-- Transport dropdown -->
<!--  <tr>-->
<!--    <td align="right" width="66%">-->
<!--      <select class="form-control" id="additional_cost_type" name="additional_cost_type">-->
<!--        <option value="">Select Transport</option>-->
<!--        <option value="internal">Internal</option>-->
<!--        <option value="external">External</option>-->
<!--      </select>-->
<!--    </td>-->
<!--     <td width="34%">-->
<!--         <input type="number" class="form-control form-control-sm" id="additional_cost_amount" placeholder="Enter Amount" step="any">-->
<!--     </td>-->
<!--  </tr>-->

  <!-- Total Taxable Value (excluding Freight) -->
<!--  <tr>-->
<!--    <td align="right" width="66%">Total Taxable Value (₹)</td>-->
<!--    <td align='right' class="text-success" width="34%">-->
<!--      +<span id="total_taxable_value">0.00</span>-->
<!--      <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="0">-->
<!--    </td>-->
<!--  </tr>-->

  <!-- Product Tax Only -->
<!--  <tr>-->
<!--    <td align="right" width="66%">Total Product Tax (₹)</td>-->
<!--    <td align='right' class="text-success" width="34%">-->
<!--      +<span id="total_tax">0.00</span>-->
<!--      <input type="hidden" name="total_tax" id="t_tax" value="0">-->
<!--    </td>-->
<!--  </tr>-->

  <!-- Freight GST (always shown separately) -->
  <!--<tr>-->
  <!--  <td align="right" width="66%">Freight Tax (18%) (₹)</td>-->
  <!--  <td align='right' width="34%">-->
  <!--    +<span id="freight_gst">0.00</span>-->
  <!--    <input type="hidden" name="freight_gst" id="freight_gst_input" value="0">-->
  <!--  </td>-->
  <!--</tr>-->

  <!-- Final Total -->
<!--  <tr>-->
<!--    <td align="right" width="66%">Total (₹)</td>-->
<!--    <td align='right' width="34%">-->
<!--      <span id="total">0.00</span>-->
<!--      <input type="hidden" name="total" id="t" value="0">-->
<!--    </td>-->
<!--  </tr>-->

  <!-- Credit -->
<!--  <tr>-->
<!--    <td align="right" width="66%">Available Credit (₹)</td>-->
<!--    <td align='right' width="34%">-->
<!--      <span id="available_credit">0.00</span>-->
<!--      <input type="hidden" name="available_credit" value="0">-->
<!--    </td>-->
<!--  </tr>-->
<!--</table>-->
<table class="table table-striped table-bordered table-condensed table-hover total_data">
  <!-- Transport Selection -->
  <tr>
    <td align="right" width="66%">
      <select class="form-control" id="additional_cost_type" name="additional_cost_type">
        <option value="">Select Transport</option>
        <option value="internal">Internal</option>
        <option value="external">External</option>
      </select>
    </td>
    <td width="34%">
      <input type="number" class="form-control form-control-sm" id="additional_cost_amount" placeholder="Enter Amount" step="any">
    </td>
  </tr>

  <!-- Product Sub Total -->
  <tr>
    <td align="right">Sub Total (Excl. Tax) (₹)</td>
    <td align='right'>
      <span id="product_sub_total">0.00</span>
      <input type="hidden" name="total_taxable_value" id="t_taxable_value">
    </td>
  </tr>

  <!-- Product Tax -->
  <tr>
    <td align="right">Tax (₹)</td>
    <td align='right'>
      <span id="product_tax_total">0.00</span>
      <input type="hidden" name="total_tax" id="t_tax">
    </td>
  </tr>

  <!-- Freight Taxable (Only shown if transport selected) -->
  <tr id="freight_taxable_row" style="display:none;">
    <td align="right">Freight Charges (₹)</td>
    <td align='right'><span id="freight_taxable_display">0.00</span></td>
  </tr>

  <!-- Freight Tax (Only shown if External) -->
  <tr id="freight_tax_row" style="display:none;">
    <td align="right">Freight Tax (18%) (₹)</td>
    <td align='right'><span id="freight_tax_amt_display">0.00</span></td>
  </tr>

  <!-- Final Grand Total -->
  <tr>
    <td align="right" class="total_data">Grand Total (Incl. Tax) (₹)</td>
    <td align='right' class="total_data">
      <span id="total">0.00</span>
      <input type="hidden" name="total" id="t" value="0">
    </td>
  </tr>
</table>

                          <!-- Add this after your existing total table -->
                      <!--  <table class="table table-striped table-bordered table-condensed table-hover profit_data">
                            <tr>
                                <td align="right" width="66%">Total Purchase Cost(<?=$currency?>)</td>
                                <td align='right' width="34%">
                                    <span id="total_purchase_cost">0.00</span>
                                    <input type="text" name="total_purchase_cost" id="t_purchase_cost" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td align="right" width="66%">Total Selling Price(<?=$currency?>)</td>
                                <td align='right' width="34%">
                                    <span id="total_selling_price">0.00</span>
                                    <input type="text" name="total_selling_price" id="t_selling_price" value="0">
                                </td>
                            </tr>
                            <tr>
                                <td align="right" width="66%">Gross Profit/Loss(<?=$currency?>)</td>
                                <td align='right' width="34%">
                                    <span id="gross_profit_loss">0.00</span>
                                    <input type="text" name="gross_profit_loss" id="t_profit_loss" value="0">
                                    
                                </td>
                            </tr>
                            <tr>
                                <td align="right" width="66%">Profit Margin(%)</td>
                                <td align='right' width="34%">
                                    <span id="profit_margin">0.00</span>
                                    <input type="text" name="profit_margin" id="profit_margin" value="">
                                </td>
                            </tr>
                        </table>-->
                        
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
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('sale_external_note'); ?>"></textarea>
                                </div>
                                <div class="tab-pane" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('sale_internal_note'); ?>"></textarea>
                                </div>
                                <div class="tab-pane" id="bank_detail">
                                  <textarea class="col-sm-12 form-control" name="bank_detail" rows="4" placeholder="<?php echo $this->lang->line('sale_bank_detail'); ?>"><?=str_replace("<br />","",$company_setting->bank_detail)?></textarea>
                                </div>
                                <div class="tab-pane" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('sale_terms_and_condition');?>"><?=str_replace("<br />","",$company_setting->terms_and_condition)?></textarea>
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
                  <input type="hidden" name="rcm" id="rcm" value="<?=$company_setting->default_rcm?>">
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
            <?php echo "Are you sure want to reset this sale ?";?>
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

<?php 
  $this->load->view('layout/footer');
  $this->load->view('customer/add_customer_modal');
  // $this->load->view('warehouse/add_warehouse_modal');
  
  // $this->load->view('service/add_service_modal');
?>

<div class="modal fade" id="emptySaleItemWarningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header warning-header">
        <h5 class="modal-title" id="exampleModalLabel"><?=$this->lang->line('sale_message_label')?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?=$this->lang->line('empty_sale_warning_label')?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/disableautofill/src/jquery.disableAutoFill.min.js"></script>

<script type="text/javascript">
 $('.datepicker').datepicker({
                    weekStart: 1,
                    daysOfWeekHighlighted: "6,0",
                    autoclose: true,
                    todayHighlight: true,
                    format: 'dd-mm-yyyy'
                });

 

  $(document).ready(function(e){

   
    // Initially hide shipping details
        $("#customer_id").closest('.row').siblings().css('display', 'none');
        $('#shipping_address_select').closest('.form-group').hide();
        $(".customer_shipping_detail").hide();

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

                refresh_tax_td();
                // $("#product_table_body tr").remove();
                // calculateGrandTotal();

              }
          });
        }
      });

    
        $('#due_days').change(function() {
            var selectedOption = $(this).find('option:selected');
            var dueDays = selectedOption.val();
            var terms = selectedOption.data('terms');
            
            // Update terms display
            $('#due_days_terms').html(terms || 'No terms available');
            $('#payment_terms').val(terms);
            
            // Calculate due_date (invoice_date + due_days)
            var invoiceDate = $('#invoice_date').val();
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
                
                // (Optional) Display due date in UI for user confirmation
                $('#due_date_display').text(formattedDueDate);
            }
        });
        
        // Trigger change on page load if due_days has a value
        if($('#due_days').val()) {
            $('#due_days').trigger('change');
        }


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

    $('#customer_id').change(function(e) {
        
        $(".customer_shipping_detail").fadeIn(10);
        var customer_id = $(this).val();
        var user_id = $('#user_id').val();
        if (customer_id != '') {
             $('#shipping_address_select').closest('.form-group').show();
            if ($('#warehouse_id').val() != '') {
                $("#customer_id").closest('.row').siblings().fadeIn(10);
            }

            $.ajax({
                url: "<?php echo base_url('customer/get_record_details') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    'customer_id': customer_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(data) {
                    console.log(data);
                    var customer = data.customer;
                    if (data.default_due_days) {

    // Set Due Days dropdown
    $('#due_days').val(data.default_due_days);

    // Get selected option
    var selectedOption = $('#due_days').find('option:selected');

    // Get terms from data attribute
    var terms = selectedOption.data('terms') || data.default_payment_terms || '';

    // Show terms in UI
    $('#due_days_terms').html(terms || 'No terms available');

    // Store for submission
    $('#payment_terms').val(terms);

    // Trigger due date calculation
    $('#due_days').trigger('change');
}
                    var shipping_addresses = data.shipping_addresses || [];
                    var proforma_invoices = data.proforma_invoices;
                    var futureDate = data.futureDate;

                    $('#due_date').val(futureDate);
                    $('#customer_state_id').val(customer.state_id);
                    $('#customer_country_id').val(customer.country_id);

                    // Populate shipping address dropdown
                    var $shippingDropdown = $('#shipping_address_select');
                    $shippingDropdown.empty();
                    $shippingDropdown.append('<option value="">Select Shipping Address</option>');
                    
                    // Add each shipping address as an option
                    $.each(shipping_addresses, function(index, address) {
                        var optionText = address.shipping_name + ' - ' + address.shipping_address;
                        $shippingDropdown.append(
                            $('<option></option>')
                                .val(address.id)
                                .text(optionText)
                                .data('address', address)
                        );
                    });

                    // Set default values from customer record
                    $('#customer_shipping_country_id').val(customer.shipping_country_id || '').trigger('change');
                    $('#customer_shipping_state_id').val(customer.shipping_state_id || '').trigger('change');
                    $('#customer_shipping_city_id').val(customer.shipping_city_id || '');
                    $('#customer_shipping_address').val(customer.shipping_address || '');
                    $('#customer_shipping_pincode').val(customer.shipping_pincode || '');

                    if (customer.shipping_state_id != '' && customer.shipping_state_id != null) {
                        $('#customer_state_id').val(customer.shipping_state_id);
                    }

                    // Set credit information
                    if (customer.closing_balance < 0) {
                        $('#available_credit').text(Math.abs(customer.closing_balance));
                        $('input[name="available_credit"]').val(customer.closing_balance);
                    } else {
                        $('#available_credit').text(0);
                        $('input[name="available_credit"]').val(0);
                    }

                    // Clear and repopulate proforma invoices
                    $("#product_table_body tr").remove();
                    $('#proforma_invoice_id').html('<option value="">Select</option>');
                    
                    for (var i = 0; i < proforma_invoices.length; i++) {
                        var proforma_invoice = proforma_invoices[i];
                        if (proforma_invoice.sale_id == null) {
                            var lockDateTime = proforma_invoice.lock_datetime;
                            if (lockDateTime == null ||
                                (lockDateTime != null && proforma_invoice.lock_by == user_id) ||
                                (lockDateTime != null && proforma_invoice.lock_by != user_id && isLockDurationGreaterThanFiveMinutes(lockDateTime))) {
                                $('#proforma_invoice_id').append('<option value="' + proforma_invoice.id + '" selected>' + proforma_invoice.reference_no + '</option');
                            }
                        }
                    }

                    refresh_tax_td();
                }
            });
        }
        else{
            $('#shipping_address_select').closest('.form-group').hide();
        }
    });


// Shipping address selection handler
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

$('#default_due_days').change(function(){

  var terms = $(this)
                .find('option:selected')
                .data('terms') || '';

  $('#default_payment_terms').val(terms);

  $('#terms_preview').text(terms);

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
      var item_id_map = { }; // Add this line here

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
                    // for(var i = 0; i < products.length; ++i) {
                    //     suggestions.push(products[i].warehouse_products_id+' - '+products[i].name+' - '+products[i].product_category_name+' - '+products[i].price);
                    //     c_mapping[products[i].warehouse_products_id] = products[i].name;
                    // }
                    // Find this inside success: function(data)
                    for(var i = 0; i < products.length; ++i) {
                        // 1. Build the text WITHOUT the ID at the start
                        var display_text = products[i].name + ' - ' + products[i].product_category_name + ' - ' + products[i].price;
                        
                        // 2. Add only the clean text to the dropdown UI
                        suggestions.push(display_text);
                        
                        // 3. Link that specific text back to its hidden ID in our map
                        item_id_map[display_text] = products[i].warehouse_products_id;
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
            
            // var warehouse_products_id = str[0];
            var warehouse_products_id = item_id_map[ui]; 

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
                    console.log("FULL DATA OBJECT:", data); // <--- ADD THIS LINE
                    var product = data.product;
                    var discounts = data.discount;
                                console.log("PRODUCT IMAGE FILENAME:", product.product_image); // <--- ADD THIS LINE

                    // Get vendors for this product
                    $.ajax({
                        url: "<?php echo base_url('product/get_product_vendors'); ?>",
                        type: "GET",
                        dataType: "json",
                        data: {
                            product_id: product.id
                        },
                        success: function(vendors) {
                            if(!is_product_exist_in_row(product.warehouse_products_id))
                            {
                                add_row(data, vendors);
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
                        },
                        error: function(xhr, status, error) {
                            console.error('Error fetching vendors:', error);
                            // Continue with adding row even if vendors fetch fails
                            if(!is_product_exist_in_row(product.warehouse_products_id))
                            {
                                add_row(data, []);
                            }
                            else
                            {
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

        var discounts = discount;

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

      

        if(company_country_id == customer_country_id)
        {          
          if(company_state_id == customer_state_id)
          {
            tax += 'C : <span name="c_tax">'+0.0+'</span><br/>';
            tax += 'S : <span name="s_tax">'+0.0+'</span>';
            tax += '<span name="i_tax"style="display:none">0</span><span name="igst" style="display:none">0</span>';
          }
          else
          {
            tax += 'I : <span name="i_tax">'+0.0+'</span><br/>';
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
           
            cols += '<td><span name="product_name">'+proforma_invoice_item.name+'</span></td>';

            cols += '<td>'+input_quantity+'</td>';

            cols += '<td class="<?php echo empty($promotion) ? 'd-none' : ''; ?>">'+free_quantity+'</td>';

            // cols += '<td>' 
            //             +'<input type="number" class="form-control text-right" name="selling_price" step="any" value="'+proforma_invoice_item.selling_price+'">'
            //         +'</td>';
            
            cols += '<td>' 
                    +'<span id="selling_price_span">'
                      +'<input type="number" class="form-control text-right" name="selling_price" step="0.01" value="'+proforma_invoice_item.selling_price+'" <?php echo empty($selling_price) ? 'readonly' : ''?> >'
                      +'<input type="hidden" name="cost" value="'+proforma_invoice_item.cost+'">'
                    +'</span>'
                  +'</td>';

            cols += '<td>' 
                    +'<span id="price_span">'
                      +'<input type="number" class="form-control text-right" name="price" step="0.01" value="'+proforma_invoice_item.price+'" readonly>'
                    +'</span>'
                  +'</td>';

            cols += '<td class="<?php echo empty($batch_no) ? 'd-none' : ''; ?>"><span name="">'+proforma_invoice_item.batch_no+'</span></td>';


            // cols += '<td class="<?php echo empty($mfg_date) ? 'd-none' : ''; ?>">'
            //           +'<input type="text" name="mfg_date" value="" class="form-control datepicker" autocomplete="off">'
            //         +'</td>';
            // cols += '<td class="<?php echo empty($expiry_date) ? 'd-none' : ''; ?>">'
            //           +'<input type="text" name="expiry_date" value="" class="form-control datepicker" autocomplete="off">'
            //         +'</td>';
           
           
            cols += '<td style="display:none">'+select_discount+'</td>';
            cols += '<td>'+input_uom+'</td>';
            cols += '<td>'+taxable_value+'</td>';
            cols += '<td class="tax_td">'+tax+'</td>';
           
            cols += '<td style="display:none">'+tax_type+'</td>';
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

   
/*function add_row(data, vendors) {
    var customer_country_id = $('#customer_country_id').val();
    var customer_state_id = $('#customer_state_id').val();
    var company_country_id = $('#company_country_id').val();
    var company_state_id = $('#company_state_id').val();
    var company_gstin = '<?=$company_setting->gstin?>';

    var product = data.product;
    var discounts = data.discount;
    var quantity = product.quantity;

    var select_discount = "<select class='form-control select2bs4' name='item_discount' style='width: 100%;'>";
    select_discount += '<option value="">Select</option>';
    for (a = 0; a < discounts.length; a++) {
        var type_symbol = (discounts[a].type == 0) ? "<?=$this->session->userdata('currency_symbol')?>" : "%";
        select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name + '(' + discounts[a].value + type_symbol + ')</option>';
    }
    select_discount += '</select>';
    select_discount += '<span name="span_discount_type" class="discount_type">Discount Value : <?=$this->session->userdata('currency_symbol');?> </span><input type="hidden" name="discount_type" value="0">';
    select_discount += '<span name="discount_amount" class="discount_amount">0.0</span><input type="hidden" name="discount_value" value="0">';

    var input_uom = '<input type="hidden" name="uom_id" value="' + product.uom_id + '">' +
                    '<input type="hidden" name="uom_name" value="' + product.uom_name + '">' +
                    '<input type="hidden" name="uom_uom" value="' + product.uom_uom + '">' +
                    product.uom_uom;

    var input_quantity = "";
    
    if (quantity === null || quantity === undefined || quantity == '' || quantity == '0.00') {
        if (product.manage_inventory == '<?=MANAGE_INVENTORY_NO?>') {
            input_quantity += '<input type="number" class="form-control no-spinner" name="quantity" required>';
        } else {
            input_quantity += '<input type="number" class="form-control quantity-input no-spinner" name="quantity" required max="' + product.quantity + '" data-available-qty="' + product.quantity + '">';
            input_quantity += 'MAX: ' + product.quantity + ' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/>';
            input_quantity += '<span name="quantity_update_message" class="quantity_update_message text-danger" style="font-size: 12px;"></span>';
        }
    } else {
        input_quantity += '<input type="number" class="form-control quantity-input no-spinner" name="quantity" required max="' + product.quantity + '" data-available-qty="' + product.quantity + '">';
        input_quantity += 'MAX: ' + product.quantity + ' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/>';
        input_quantity += '<span name="quantity_update_message" class="quantity_update_message text-danger" style="font-size: 12px;"></span>';
    }

    var taxable_value = '<span name="taxable_value">' + product.selling_price + '</span>';

    var tax = '<input type="hidden" name="tax_id" value="' + product.tax_id + '">';
    if (company_country_id == customer_country_id) {
        if (company_state_id == customer_state_id) {
            // CGST+SGST applicable
            tax += 'CGST : <span name="c_tax">0.0</span>(<span name="cgst">' + product.cgst + '%</span>)<br/>';
            tax += 'SGST : <span name="s_tax">0.0</span>(<span name="sgst">' + product.sgst + '%</span>)';
            tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
        } else {
            // IGST applicable
            tax += 'IGST : <span name="i_tax">0.0</span>(<span name="igst">' + product.igst + '%</span>)<br/>';
            tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
            tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
        }
    } else {
        // No GST
        tax += 'N/A';
        tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
        tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
        tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
    }

    var tax_type = '<input type="hidden" name="tax_type" value="' + product.tax_type + '">' + (product.tax_type == 0 ? "No" : "Yes");
    var free_quantity = '<input type="number" class="form-control" name="free_quantity" value="0" min="0" step="any">';

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

    var cost_field = `
        <input type="number" class="form-control cost-field" name="vendor_cost" step="0.01" value="0.00" readonly>
        <div>
            <small>
                GST: <span name="purchase_gst_amount">0.00</span> (
                <span name="purchase_gst_percent">0%</span>)
            </small>
        </div>
    `;

    var newRow = $('<tr class="service_row">');
    var cols = '';
    cols += '<td><span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>' +
            '<input type="hidden" name="product_id" value="' + product.id + '">' +
            '<input type="hidden" name="proforma_invoice_idd" value="">' +
            '<input type="hidden" name="warehouse_product_id" value="' + product.warehouse_products_id + '">' +
            '<input type="hidden" name="igst_rate" value="' + product.igst + '">' +
            '<input type="hidden" name="cgst_rate" value="' + product.cgst + '">' +
            '<input type="hidden" name="sgst_rate" value="' + product.sgst + '">' +
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
    cols += '<td><span id="selling_price_span">' +
            '<input type="number" class="form-control text-right" name="selling_price" step="0.01" ' +
            'value="' + product.selling_price + '" required min="0.01" ' +
            'oninvalid="this.setCustomValidity(\'Selling price must be greater than 0\')" ' +
            'oninput="this.setCustomValidity(\'\')">' +
            '<input type="hidden" name="cost" value="' + product.cost + '">' +
            '</span></td>';

    cols += '<td><span name="hsn">' + product.hsn + '</span></td>';

    cols += '<td style="display:none">' + select_discount + '</td>';
    cols += '<td>' + input_uom + '</td>';
    cols += '<td>' + taxable_value + '</td>';
    cols += '<td class="tax_td">' + tax + '</td>';
    
    cols += '<td style="display:none">';
    cols += '<input type="text" name="igst_tax[]" value="0" class="igst_tax">';
    cols += '<input type="text" name="cgst_tax[]" value="0" class="cgst_tax">';
    cols += '<input type="text" name="sgst_tax[]" value="0" class="sgst_tax">';
    cols += '<input type="text" name="igst[]" value="' + product.igst + '" class="igst">';
    cols += '<input type="text" name="cgst[]" value="' + product.cgst + '" class="cgst">';
    cols += '<input type="text" name="sgst[]" value="' + product.sgst + '" class="sgst">';
    cols += '</td>';
    
    cols += '<td style="display:none">' + tax_type + '</td>';
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

    $('[data-tt="tooltip"]').tooltip({ trigger: 'hover' });
    calculateRow(newRow);
}*/
function add_row(data, vendors) {
    var customer_country_id = $('#customer_country_id').val();
    var customer_state_id = $('#customer_state_id').val();
    var company_country_id = $('#company_country_id').val();
    var company_state_id = $('#company_state_id').val();
    var company_gstin = '<?=$company_setting->gstin?>';

    var product = data.product;
    var discounts = data.discount;
    var quantity = product.quantity;
    
    // Get default purchase cost from product (this will show immediately)
    var default_purchase_cost = parseFloat(product.cost) || 0;

    var select_discount = "<select class='form-control select2bs4' name='item_discount' style='width: 100%;'>";
    select_discount += '<option value="">Select</option>';
    for (a = 0; a < discounts.length; a++) {
        var type_symbol = (discounts[a].type == 0) ? "<?=$this->session->userdata('currency_symbol')?>" : "%";
        select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name + '(' + discounts[a].value + type_symbol + ')</option>';
    }
    select_discount += '</select>';
    select_discount += '<span name="span_discount_type" class="discount_type">Discount Value : <?=$this->session->userdata('currency_symbol');?> </span><input type="hidden" name="discount_type" value="0">';
    select_discount += '<span name="discount_amount" class="discount_amount">0.0</span><input type="hidden" name="discount_value" value="0">';

    var input_uom = '<input type="hidden" name="uom_id" value="' + product.uom_id + '">' +
                    '<input type="hidden" name="uom_name" value="' + product.uom_name + '">' +
                    '<input type="hidden" name="uom_uom" value="' + product.uom_uom + '">' +
                    product.uom_uom;

    var input_quantity = "";
    
    if (quantity === null || quantity === undefined || quantity == '' || quantity == '0.00') {
        if (product.manage_inventory == '<?=MANAGE_INVENTORY_NO?>') {
            input_quantity += '<input type="number" class="form-control no-spinner" name="quantity" required>';
        } else {
            input_quantity += '<input type="number" class="form-control quantity-input no-spinner" name="quantity" required max="' + product.quantity + '" data-available-qty="' + product.quantity + '">';
            input_quantity += 'STOCK: ' + product.quantity + ' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/>';
            input_quantity += '<span name="quantity_update_message" class="quantity_update_message text-danger" style="font-size: 12px;"></span>';
        }
    } else {
        input_quantity += '<input type="number" class="form-control quantity-input no-spinner" name="quantity" required max="' + product.quantity + '" data-available-qty="' + product.quantity + '">';
        input_quantity += 'STOCK: ' + product.quantity + ' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/>';
        input_quantity += '<span name="quantity_update_message" class="quantity_update_message text-danger" style="font-size: 12px;"></span>';
    }

    var taxable_value = '<span name="taxable_value">' + product.selling_price + '</span>';

    var tax = '<input type="hidden" name="tax_id" value="' + product.tax_id + '">';
    if (company_country_id == customer_country_id) {
        if (company_state_id == customer_state_id) {
            tax += 'CGST : <span name="c_tax">0.0</span>(<span name="cgst">' + product.cgst + '%</span>)<br/>';
            tax += 'SGST : <span name="s_tax">0.0</span>(<span name="sgst">' + product.sgst + '%</span>)';
            tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
        } else {
            tax += 'IGST : <span name="i_tax">0.0</span>(<span name="igst">' + product.igst + '%</span>)<br/>';
            tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
            tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
        }
    } else {
        tax += 'N/A';
        tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
        tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
        tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
    }

    var tax_type = '<input type="hidden" name="tax_type" value="' + product.tax_type + '">' + (product.tax_type == 0 ? "No" : "Yes");
    var free_quantity = '<input type="number" class="form-control" name="free_quantity" value="0" min="0" step="any">';

    // Supplier select dropdown
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

    // Purchase Cost Field - Show default cost immediately
    var total_gst_percent = (parseFloat(product.igst) + parseFloat(product.cgst) + parseFloat(product.sgst));
    var cost_field = `
        <input type="number" class="form-control cost-field" name="vendor_cost" step="0.01" value="` + default_purchase_cost.toFixed(2) + `" readonly>
        <div>
            <small>
                GST: <span name="purchase_gst_amount">` + ((default_purchase_cost * total_gst_percent / 100)).toFixed(2) + `</span> (
                <span name="purchase_gst_percent">` + total_gst_percent.toFixed(2) + `%</span>)
            </small>
        </div>
    `;

    var newRow = $('<tr class="service_row">');
    var cols = '';
    cols += '<td><span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>' +
            '<input type="hidden" name="product_id" value="' + product.id + '">' +
            '<input type="hidden" name="proforma_invoice_idd" value="">' +
            '<input type="hidden" name="warehouse_product_id" value="' + product.warehouse_products_id + '">' +
            '<input type="hidden" name="igst_rate" value="' + product.igst + '">' +
            '<input type="hidden" name="cgst_rate" value="' + product.cgst + '">' +
            '<input type="hidden" name="sgst_rate" value="' + product.sgst + '">' +
            '<input type="hidden" name="vendor_total_with_gst[]" value="' + (default_purchase_cost + (default_purchase_cost * total_gst_percent / 100)).toFixed(2) + '">' +
            '<input type="hidden" name="default_cost" value="' + default_purchase_cost + '">' +
            '</td>';
    
   /* cols += '<td>'
        + '<img src="<?= base_url('assets/product_images').'/'; ?>'+product.product_image+'" alt="'+product.name+'" style="width:40px; height:40px; object-fit:cover; margin-right:5px; border-radius:4px;">'
        + '<span name="product_name">'+product.name+'</span>'
        + '<span class="text-muted">(Code - '+product.product_code+')</span>'
        + '</td>';*/
        
        cols += '<td>'
    +  '<img src="<?= base_url('assets/product_images').'/'; ?>'+product.product_image+'"  style="width:40px; height:40px; object-fit:cover; margin-right:5px; border-radius:4px;">'
    + '<span name="product_name">'+product.name+'</span><br>'
    + '<span class="text-muted">(Product Code - '+product.product_code+')</span>'
    + '</td>';
    
    // var img_folder = "<?= base_url('assets/images/'); ?>"; // Changed from product_images to images
    // var product_img_src = (product.product_image) ? img_folder + product.product_image : "<?= base_url('assets/images/no_image.png'); ?>";
        
    // cols += '<td>'
    // + '<div style="display:flex; align-items:flex-start;">'
    // + '<img src="' + product_img_src + '" '
    // + 'onerror="this.src=\'<?= base_url("assets/images/no_image.png"); ?>\'" ' 
    // + 'style="width:30px; height:30px; object-fit:cover; margin-right:5px; border-radius:2px;">'
    // + '<div>'
    // + '<span name="product_name" style="font-weight:bold; display:block; line-height:1.2;">'+product.name+'</span>'
    // + '<small class="text-muted" style="font-size:11px;">Product Code - '+(product.product_code || "N/A")+'</small>'
    // + '</div>'
    // + '</div></td>';
    
    cols += '<td><textarea name="product_remark" rows="1" cols="20"></textarea></td>';
    cols += '<td>' + supplier_select + '</td>';
    // cols += '<td>' + cost_field + '</td>';
    var available_stock = parseFloat(product.quantity) || 0;
    var input_quantity = '<input type="number" class="form-control quantity-input" name="quantity" value="1" required min="1" max="' + available_stock + '" data-available-qty="' + available_stock + '">'
                       + '<div style="font-size: 11px; margin-top:2px;">STOCK: <b>' + available_stock + '</b></div>'
                       + '<span name="quantity_err" class="text-danger" style="font-size: 10px; display:block;"></span>';
    cols += '<td>' + input_quantity + '</td>';
    cols += '<td><span id="selling_price_span">' +
            '<input type="number" class="form-control text-right" name="selling_price" step="0.01" ' +
            'value="' + product.selling_price + '" required min="0.01" ' +
            'oninvalid="this.setCustomValidity(\'Selling price must be greater than 0\")" ' +
            'oninput="this.setCustomValidity(\'\')">' +
            '<input type="hidden" name="cost" value="' + product.cost + '">' +
            '</span></td>';

    cols += '<td><span name="hsn">' + product.hsn + '</span></td>';

    cols += '<td style="display:none">' + select_discount + '</td>';
    cols += '<td>' + input_uom + '</td>';
    cols += '<td>' + taxable_value + '</td>';
    cols += '<td class="tax_td">' + tax + '</td>';
    
    cols += '<td style="display:none">';
    cols += '<input type="text" name="igst_tax[]" value="0" class="igst_tax">';
    cols += '<input type="text" name="cgst_tax[]" value="0" class="cgst_tax">';
    cols += '<input type="text" name="sgst_tax[]" value="0" class="sgst_tax">';
    cols += '<input type="text" name="igst[]" value="' + product.igst + '" class="igst">';
    cols += '<input type="text" name="cgst[]" value="' + product.cgst + '" class="cgst">';
    cols += '<input type="text" name="sgst[]" value="' + product.sgst + '" class="sgst">';
    cols += '</td>';
    
    cols += '<td style="display:none">' + tax_type + '</td>';
    cols += '<td><span name="sub_total"></span></td>';

    newRow.append(cols);
    $("table.product_table").append(newRow);
    $('.select2bs4').select2({ theme: 'bootstrap4' });

    // Calculate row with default cost
    calculateRow(newRow);
    updateProfitTotals();

    // Handle supplier change - update cost if vendor has purchase history
    newRow.find('.supplier-select').change(function () {
        var product_id = newRow.find('input[name="product_id"]').val();
        var supplier_id = $(this).val();
        var costField = newRow.find('.cost-field');
        var defaultCost = parseFloat(newRow.find('input[name="default_cost"]').val()) || 0;

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
                    if (response.success && response.cost > 0) {
                        // Found purchase history - update with vendor cost
                        costField.val(response.cost);
                        // Add a small indicator that this came from vendor
                        costField.css('background-color', '#e8f5e9');
                        setTimeout(function() {
                            costField.css('background-color', '');
                        }, 1000);
                    } else {
                        // No purchase history - keep default cost
                        costField.val(defaultCost);
                        // Show tooltip that using default cost
                        costField.attr('title', 'Using default product cost (no purchase history)');
                    }
                    calculateRow(newRow);
                    updateProfitTotals();
                },
                error: function () {
                    // On error, keep default cost
                    costField.val(defaultCost);
                    calculateRow(newRow);
                    updateProfitTotals();
                }
            });
        } else {
            // No supplier selected - revert to default cost
            costField.val(defaultCost);
            calculateRow(newRow);
            updateProfitTotals();
        }
    });

    $('[data-tt="tooltip"]').tooltip({ trigger: 'hover' });
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

      $("table.product_table").on('change', 'input[name^="selling_price"], input[name^="quantity"], select[name^="item_discount"], .supplier-select', function (event) {

        if($(this).attr('name') == 'item_discount')
        {
          var discount_id = $(this).val();
          var tr          = $(this).closest('tr');

          if(discount_id != '')
          {
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

        $(document).on('change', 'input[name="quantity"], input[name="selling_price"], .supplier-select', function() {
            var row = $(this).closest('tr');
            calculateRow(row);
            updateProfitTotals();
        });


// function calculateRow(row) {
//   var tax_type = row.find('input[name^="tax_type"]').val();
//   var customer_country_id = $('#customer_country_id').val();
//   var customer_state_id = $('#customer_state_id').val();
//   var company_country_id = $('#company_country_id').val();
//   var company_state_id = $('#company_state_id').val();

//   var quantity = parseFloat(row.find('input[name^="quantity"]').val()) || 0;
//   var selling_price = parseFloat(row.find('input[name^="selling_price"]').val()) || 0;
//   var discount_type = row.find('input[name^="discount_type"]').val();
//   var discount_value = parseFloat(row.find('input[name^="discount_value"]').val()) || 0;

//   var taxable_value = quantity * selling_price;
//   var final_discount_value = 0;

//   if (discount_type == 0) {
//     final_discount_value = discount_value;
//   } else {
//     final_discount_value = (taxable_value * discount_value) / 100;
//   }

//   taxable_value = taxable_value - final_discount_value;

//   // Initialize tax rates from the product data
//   var igst = parseFloat(row.find('input[name^="igst"]').val()) || 0;
//   var cgst = parseFloat(row.find('input[name^="cgst"]').val()) || 0;
//   var sgst = parseFloat(row.find('input[name^="sgst"]').val()) || 0;
//   console.log('igst',igst);
//   console.log('cgst',cgst);
//   console.log('sgst',sgst);
//   // Determine which taxes to apply based on location
//   var apply_igst = false;
//   var apply_cgst_sgst = false;
  
//   if (company_country_id == customer_country_id) {
//     if (company_state_id == customer_state_id) {
//       // Same state - apply CGST+SGST
//       apply_cgst_sgst = true;
//       igst = 0; // Reset IGST if it was set
//     } else {
//       // Different state - apply IGST
//       apply_igst = true;
//       cgst = 0; // Reset CGST if it was set
//       sgst = 0; // Reset SGST if it was set
//     }
//   } else {
//     // Different country - no GST
//     igst = 0;
//     cgst = 0;
//     sgst = 0;
//   }
//   row.data('apply_igst', apply_igst);
//   row.data('apply_cgst_sgst', apply_cgst_sgst);
  
//   var igst_tax = 0, cgst_tax = 0, sgst_tax = 0;

//   if (tax_type == 0) {
//     // Exclusive tax
//     if (apply_igst) {
//       igst_tax = (taxable_value * igst) / 100;
//     } else if (apply_cgst_sgst) {
//       cgst_tax = (taxable_value * cgst) / 100;
//       sgst_tax = (taxable_value * sgst) / 100;
//     }
//   } else {
//     // Inclusive tax
//     var total_tax_rate = igst + cgst + sgst;
//     var total_tax_amount = (taxable_value * total_tax_rate) / (100 + total_tax_rate);

//     if (apply_igst) {
//       igst_tax = total_tax_amount;
//     } else if (apply_cgst_sgst) {
//       cgst_tax = total_tax_amount / 2;
//       sgst_tax = total_tax_amount / 2;
//     }

//     taxable_value = taxable_value - (igst_tax + cgst_tax + sgst_tax);
//   }

//   var sub_total = taxable_value + igst_tax + cgst_tax + sgst_tax;

//   // Update row values
//   row.find('input[name^="igst_tax"]').val(igst_tax.toFixed(2));
//   row.find('input[name^="cgst_tax"]').val(cgst_tax.toFixed(2));
//   row.find('input[name^="sgst_tax"]').val(sgst_tax.toFixed(2));

//   row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
//   row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
//   row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));

//   // Only update the displayed tax rates if they're actually being applied
//   row.find('span[name^="igst"]').text(apply_igst ? igst.toFixed(2) : "0");
//   row.find('span[name^="cgst"]').text(apply_cgst_sgst ? cgst.toFixed(2) : "0");
//   row.find('span[name^="sgst"]').text(apply_cgst_sgst ? sgst.toFixed(2) : "0");

//   row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));
//   row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
//   row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));

//   // Rest of your existing code for purchase cost calculations...
//   var cost = parseFloat(row.find('input[name="vendor_cost"]').val()) || 0;
//   var purchase_igst = igst;
//   var purchase_cgst = cgst;
//   var purchase_sgst = sgst;

//   var purchase_gst_percent = purchase_igst + purchase_cgst + purchase_sgst;
//   var purchase_cost_with_gst = (cost + ((cost * purchase_gst_percent) / 100)) * quantity;
//   row.find('input[name="vendor_total_with_gst[]"]').val(purchase_cost_with_gst.toFixed(2));
//   var purchase_tax_amount = (purchase_cost_with_gst - (cost * quantity));

//   row.find('span[name="purchase_gst_amount"]').text(purchase_tax_amount.toFixed(2));
//   row.find('span[name="purchase_gst_percent"]').text(purchase_gst_percent.toFixed(2) + '%');

//   var rowProfit = (selling_price * quantity) - purchase_cost_with_gst;
//   row.find('span[name="row_profit"]').text(rowProfit.toFixed(2));

//   // Final update for total
//   updateProfitTotals();
// }

function calculateRow(row) {
  if (row.find('input[name="freight_id"]').val() === 'freight') return; 

  var tax_type = row.find('input[name^="tax_type"]').val();
  var customer_country_id = $('#customer_country_id').val();
  var customer_state_id = $('#customer_state_id').val();
  var company_country_id = $('#company_country_id').val();
  var company_state_id = $('#company_state_id').val();

  var quantity = parseFloat(row.find('input[name^="quantity"]').val()) || 0;
  var selling_price = parseFloat(row.find('input[name^="selling_price"]').val()) || 0;
  var discount_type = row.find('input[name^="discount_type"]').val();
  var discount_value = parseFloat(row.find('input[name^="discount_value"]').val()) || 0;

  var taxable_value = quantity * selling_price;
  var final_discount_value = 0;

  if (discount_type == 0) {
    final_discount_value = discount_value;
  } else {
    final_discount_value = (taxable_value * discount_value) / 100;
  }

  taxable_value = taxable_value - final_discount_value;

  var igst = parseFloat(row.find('input[name^="igst"]').val()) || 0;
  var cgst = parseFloat(row.find('input[name^="cgst"]').val()) || 0;
  var sgst = parseFloat(row.find('input[name^="sgst"]').val()) || 0;

  var apply_igst = false;
  var apply_cgst_sgst = false;
  
  if (company_country_id == customer_country_id) {
    if (company_state_id == customer_state_id) {
      apply_cgst_sgst = true;
      igst = 0;
    } else {
      apply_igst = true;
      cgst = 0;
      sgst = 0;
    }
  } else {
    igst = 0; cgst = 0; sgst = 0;
  }

  row.data('apply_igst', apply_igst);
  row.data('apply_cgst_sgst', apply_cgst_sgst);
  
  var igst_tax = 0, cgst_tax = 0, sgst_tax = 0;

  if (tax_type == 0) {
    if (apply_igst) {
      igst_tax = (taxable_value * igst) / 100;
    } else if (apply_cgst_sgst) {
      cgst_tax = (taxable_value * cgst) / 100;
      sgst_tax = (taxable_value * sgst) / 100;
    }
  } else {
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

  // Update DOM
  row.find('input[name^="igst_tax"]').val(igst_tax.toFixed(2));
  row.find('input[name^="cgst_tax"]').val(cgst_tax.toFixed(2));
  row.find('input[name^="sgst_tax"]').val(sgst_tax.toFixed(2));
  row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
  row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
  row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
  row.find('span[name^="igst"]').text(apply_igst ? igst.toFixed(2) : "0");
  row.find('span[name^="cgst"]').text(apply_cgst_sgst ? cgst.toFixed(2) : "0");
  row.find('span[name^="sgst"]').text(apply_cgst_sgst ? sgst.toFixed(2) : "0");
  row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));
  row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
  row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));

  // Purchase cost logic
  var cost = parseFloat(row.find('input[name="vendor_cost"]').val()) || 0;
  var purchase_gst_percent = igst + cgst + sgst;
  var purchase_cost_with_gst = (cost + ((cost * purchase_gst_percent) / 100)) * quantity;
  row.find('input[name="vendor_total_with_gst[]"]').val(purchase_cost_with_gst.toFixed(2));
  
  // ✅ IMPORTANT: Trigger total refreshes
  updateProfitTotals();
  calculateGrandTotal(); 
}

function updateProfitTotals() {
    var totalPurchaseCost = 0;
    var totalSellingPrice = 0;
    var totalTaxableValue = 0;
    var totalTax = 0;
    var transportType = $('#additional_cost_type').val();
    var freightSubTotal = 0;

    // $("#product_table_body").find('tr').each(function () {
    //     var row = $(this);
    //     var isFreight = row.find('input[name="freight_id"]').val() === 'freight';
        
    //     if (isFreight) {
    //         var freightAmount = parseFloat(row.find('input[name="freight_selling_price"]').val()) || 0;
    //         var freightTax = parseFloat(row.find('span[name="freight_i_tax"]').text()) || 0;
    //         freightSubTotal = parseFloat(row.find('span[name="freight_sub_total"]').text()) || 0;

    //         if (transportType === 'internal') {
    //             // Internal transport - add to purchase cost only
    //             totalPurchaseCost += freightAmount;
    //         } else {
    //             // External transport - add to taxable and tax
    //             totalTaxableValue += freightAmount;
    //             totalTax += freightTax;
    //             totalSellingPrice += freightSubTotal;
    //         }
    //     } else {
    //         // Handle product rows normally
    //         var vendorWithGst = parseFloat(row.find('input[name="vendor_total_with_gst[]"]').val()) || 0;
    //         totalPurchaseCost += vendorWithGst;

    //         var subTotal = parseFloat(row.find('span[name="sub_total"]').text()) || 0;
    //         totalSellingPrice += subTotal;
            
    //         totalTaxableValue += parseFloat(row.find('span[name="taxable_value"]').text()) || 0;
    //         totalTax += (parseFloat(row.find('span[name="i_tax"]').text()) || 0)
    //                   + (parseFloat(row.find('span[name="c_tax"]').text()) || 0)
    //                   + (parseFloat(row.find('span[name="s_tax"]').text()) || 0);
    //     }
    // });
    
     $("#product_table_body").find('tr').each(function () {
        var row = $(this);
        var isFreight = row.find('input[name="freight_id"]').val() === 'freight';
        
        if (isFreight) {
            var freightAmount = parseFloat(row.find('input[name="freight_selling_price"]').val()) || 0;
            var freightTax = parseFloat(row.find('span[name="i_tax"]').text()) || 0; // Fixed selector
            freightSubTotal = parseFloat(row.find('span[name="sub_total"]').text()) || 0; // Fixed selector
            
            // FIX: Always add to invoice totals regardless of type
            totalTaxableValue += freightAmount;
            totalTax += freightTax;
            totalSellingPrice += freightSubTotal;

            if (transportType === 'internal') {
                // Also treat it as a purchase expense for profit margin
                totalPurchaseCost += freightAmount;
            }
        } else {
            // Handle regular product rows
            var vendorWithGst = parseFloat(row.find('input[name="vendor_total_with_gst[]"]').val()) || 0;
            totalPurchaseCost += vendorWithGst;

            totalSellingPrice += parseFloat(row.find('span[name="sub_total"]').text()) || 0;
            totalTaxableValue += parseFloat(row.find('span[name="taxable_value"]').text()) || 0;
            totalTax += (parseFloat(row.find('span[name="i_tax"]').text()) || 0)
                      + (parseFloat(row.find('span[name="c_tax"]').text()) || 0)
                      + (parseFloat(row.find('span[name="s_tax"]').text()) || 0);
        }
    });

    // Calculate profit metrics
    var grossProfitLoss = totalSellingPrice - totalPurchaseCost;
    var profitMargin = (totalPurchaseCost > 0) ? ((grossProfitLoss / totalPurchaseCost) * 100) : 0;

    // Update UI - maintain same structure as working version
    $('#total_purchase_cost').text(totalPurchaseCost.toFixed(2));
    $('#t_purchase_cost').val(totalPurchaseCost.toFixed(2));
    $('#total_selling_price').text(totalSellingPrice.toFixed(2));
    $('#t_selling_price').val(totalSellingPrice.toFixed(2));
    $('#total_taxable_value').text(totalTaxableValue.toFixed(2));
    $('#t_taxable_value').val(totalTaxableValue.toFixed(2));
    $('#total_tax').text(totalTax.toFixed(2));
    $('#t_tax').val(totalTax.toFixed(2));
    $('#freight_gst').text(transportType === 'external' ? freightSubTotal.toFixed(2) : '0.00');
    $('#freight_gst_input').val(transportType === 'external' ? freightSubTotal.toFixed(2) : '0');
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

// function calculateGrandTotal() {
//     var total_taxable_value = 0.0;
//     var total_cgst = 0.0;
//     var total_sgst = 0.0;
//     var total_igst = 0.0;
//     var total = 0.0;
//     var total_discount = 0.0;
//     var tds = parseFloat($('#tds').val());

//     $("#product_table_body").find('tr').each(function () {
//         var tr = $(this).closest("tr");
//         total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text());
//         total_discount += parseFloat(tr.find('span[name^="discount_amount"]').text());
//         total_cgst += parseFloat(tr.find('span[name^="c_tax"]').text());
//         total_sgst += parseFloat(tr.find('span[name^="s_tax"]').text());
//         total_igst += parseFloat(tr.find('span[name^="i_tax"]').text());
//         total += parseFloat(tr.find('span[name^="sub_total"]').text());
//     });

//     $('#total_taxable_value').text(parseFloat(total_taxable_value.toFixed(2)) - tds);
//     $('#t_taxable_value').val(parseFloat(total_taxable_value.toFixed(2)) - tds);
//     $('#total_discount').text(total_discount.toFixed(2));
//     $('#t_discount').val(total_discount.toFixed(2));
//     $('#total_tax').text((total_cgst + total_sgst + total_igst).toFixed(2));
//     $('#t_tax').val((total_cgst + total_sgst + total_igst).toFixed(2));
//     $('#total').text(total.toFixed(2));
//     $('#t').val(total.toFixed(2));
// }

// function calculateGrandTotal() {
//     var total_taxable_value = 0.0;
//     var total_cgst = 0.0;
//     var total_sgst = 0.0;
//     var total_igst = 0.0;
//     var total = 0.0;
//     var total_discount = 0.0;
    
//     // Safety check for TDS: if field doesn't exist, use 0
//     var tds = parseFloat($('#tds').val()) || 0;

//     $("#product_table_body tr").each(function () {
//         var tr = $(this);
        
//         // Use || 0 to ensure we never add "undefined" or "NaN"
//         total_taxable_value += parseFloat(tr.find('span[name="taxable_value"]').text()) || 0;
//         total_discount      += parseFloat(tr.find('span[name="discount_amount"]').text()) || 0;
//         total_cgst          += parseFloat(tr.find('span[name="c_tax"]').text()) || 0;
//         total_sgst          += parseFloat(tr.find('span[name="s_tax"]').text()) || 0;
//         total_igst          += parseFloat(tr.find('span[name="i_tax"]').text()) || 0;
//         total               += parseFloat(tr.find('span[name="sub_total"]').text()) || 0;
//     });

//     var grand_tax = total_cgst + total_sgst + total_igst;
//     var final_taxable = total_taxable_value - tds;

//     // Update UI
//     $('#total_taxable_value').text(final_taxable.toFixed(2));
//     $('#t_taxable_value').val(final_taxable.toFixed(2));
    
//     $('#total_tax').text(grand_tax.toFixed(2));
//     $('#t_tax').val(grand_tax.toFixed(2));
    
//     $('#total').text(total.toFixed(2));
//     $('#t').val(total.toFixed(2));
    
//     $('#total_discount').text(total_discount.toFixed(2));
//     $('#t_discount').val(total_discount.toFixed(2));
// }

function calculateGrandTotal() {
    var product_taxable = 0.0;
    var product_tax = 0.0;
    var freight_taxable = 0.0;
    var freight_tax = 0.0;
    var grand_total = 0.0;
    
    var transport_type = $('#additional_cost_type').val();

    $("#product_table_body tr").each(function () {
        var tr = $(this);
        var isFreight = tr.find('input[name="freight_id"]').val() === 'freight';
        
        var taxable = parseFloat(tr.find('span[name="taxable_value"]').text()) || 0;
        var tax = (parseFloat(tr.find('span[name="c_tax"]').text()) || 0) + 
                  (parseFloat(tr.find('span[name="s_tax"]').text()) || 0) + 
                  (parseFloat(tr.find('span[name="i_tax"]').text()) || 0);

        if (isFreight) {
            freight_taxable = taxable;
            freight_tax = tax;
        } else {
            product_taxable += taxable;
            product_tax += tax;
        }
    });

    // Update UI Spans
    $('#product_sub_total').text(product_taxable.toFixed(2));
    $('#product_tax_total').text(product_tax.toFixed(2));
    
    // Handle Freight Display Rows
    if(transport_type != '') {
        $('#freight_taxable_row').show();
        $('#freight_taxable_display').text(freight_taxable.toFixed(2));
        
        if(transport_type == 'external') {
            $('#freight_tax_row').show();
            $('#freight_tax_amt_display').text(freight_tax.toFixed(2));
        } else {
            $('#freight_tax_row').hide();
        }
    } else {
        $('#freight_taxable_row, #freight_tax_row').hide();
    }

    // Final Calculations
    grand_total = product_taxable + product_tax + freight_taxable + freight_tax;

    // Update Hidden inputs for form submission
    $('#t_taxable_value').val((product_taxable + freight_taxable).toFixed(2)); // Combined for DB
    $('#t_tax').val((product_tax + freight_tax).toFixed(2));
    $('#total').text(grand_total.toFixed(2));
    $('#t').val(grand_total.toFixed(2));
}



$('#addSaleForm').submit(function(e) {

    const form = this;
    const invalidField = form.querySelector(':invalid'); 

    if (invalidField) {
        e.preventDefault();
        invalidField.reportValidity();
        invalidField.focus(); 
        return false;
    }

    var isError = false;
    $('#saleSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled', 'disabled');

    $('form#addSaleForm .field_validation').each(function() {
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if (value == null || value == "") {
            $("form#addSaleForm #err_" + id).text(field + " field is required.").fadeIn('slow');
            $('form#addSaleForm #' + id).addClass('is-invalid');
            isError = true;
        } else {
            $("form#addSaleForm #err_" + id).text("").fadeOut('slow');
            $('form#addSaleForm #' + id).removeClass('is-invalid').addClass('is-valid');
        }
    });



    var profitResults = updateProfitTotals();
    var additionalCostType = $('#additional_cost_type').val();
    var additionalCostAmount = parseFloat($('#additional_cost_amount').val()) || 0;

// Prepare product data
    var productDataArray = [];
    $("#product_table_body").find('tr').each(function() {
        var tr = $(this);
        if (tr.find('input[name="freight_id"]').val() === 'freight') return; // Skip freight row
        
          var apply_igst = tr.data('apply_igst') || false;
            var apply_cgst_sgst = tr.data('apply_cgst_sgst') || false;
        
        var productData = {
            'product_id': tr.find('input[name="product_id"]').val(),
            'product_remark': tr.find('textarea[name="product_remark"]').val(),
            'warehouse_product_id': tr.find('input[name="warehouse_product_id"]').val(),
            'uom_id': tr.find('input[name="uom_id"]').val(),
            'uom_name': tr.find('input[name="uom_name"]').val(),
            'uom_uom': tr.find('input[name="uom_uom"]').val(),
            'product_name': tr.find('span[name="product_name"]').text(),
            'hsn': tr.find('span[name="hsn"]').text(),
            'quantity': parseFloat(tr.find('input[name="quantity"]').val()) || 0,
            'selling_price': parseFloat(tr.find('input[name="selling_price"]').val()) || 0,
            'sub_total': parseFloat(tr.find('span[name="sub_total"]').text()) || 0,
            'purchase_cost': parseFloat(tr.find('input[name="vendor_cost"]').val()) || 0,
           // 'vendor_total_with_gst': parseFloat(tr.find('input[name="vendor_total_with_gst[]"]').val()) || 0,
            'supplier_id': tr.find('select[name="supplier_id"]').val() || '',
            'taxable_value': parseFloat(tr.find('span[name="taxable_value"]').text()) || 0,
            'igst_tax': parseFloat(tr.find('span[name="i_tax"]').text()) || 0,
            'cgst_tax': parseFloat(tr.find('span[name="c_tax"]').text()) || 0,
            'sgst_tax': parseFloat(tr.find('span[name="s_tax"]').text()) || 0,
            'igst': parseFloat(tr.find('input[name="igst_rate"]').val()) || 0,
            'cgst': parseFloat(tr.find('input[name="cgst_rate"]').val()) || 0,
            'sgst': parseFloat(tr.find('input[name="sgst_rate"]').val()) || 0,
            'tax_type': tr.find('input[name="tax_type"]').val() || 0,
            'igst_rate': parseFloat(tr.find('input[name="igst_rate"]').val()) || 0,
            'cgst_rate': parseFloat(tr.find('input[name="cgst_rate"]').val()) || 0,
            'sgst_rate': parseFloat(tr.find('input[name="sgst_rate"]').val()) || 0
        };
        
          if (apply_igst) {
                productData['igst_tax'] = parseFloat(tr.find('span[name="i_tax"]').text()) || 0;
                productData['igst'] = parseFloat(tr.find('input[name="igst_rate"]').val()) || 0;
                productData['igst_rate'] = parseFloat(tr.find('input[name="igst_rate"]').val()) || 0;
                // Set CGST/SGST to 0 since they're not applicable
                productData['cgst_tax'] = 0;
                productData['sgst_tax'] = 0;
                productData['cgst'] = 0;
                productData['sgst'] = 0;
                productData['cgst_rate'] = 0;
                productData['sgst_rate'] = 0;
            } else if (apply_cgst_sgst) {
                productData['cgst_tax'] = parseFloat(tr.find('span[name="c_tax"]').text()) || 0;
                productData['sgst_tax'] = parseFloat(tr.find('span[name="s_tax"]').text()) || 0;
                productData['cgst'] = parseFloat(tr.find('input[name="cgst_rate"]').val()) || 0;
                productData['sgst'] = parseFloat(tr.find('input[name="sgst_rate"]').val()) || 0;
                productData['cgst_rate'] = parseFloat(tr.find('input[name="cgst_rate"]').val()) || 0;
                productData['sgst_rate'] = parseFloat(tr.find('input[name="sgst_rate"]').val()) || 0;
                // Set IGST to 0 since it's not applicable
                productData['igst_tax'] = 0;
                productData['igst'] = 0;
                productData['igst_rate'] = 0;
            } else {
                // No taxes applicable
                productData['igst_tax'] = 0;
                productData['cgst_tax'] = 0;
                productData['sgst_tax'] = 0;
                productData['igst'] = 0;
                productData['cgst'] = 0;
                productData['sgst'] = 0;
                productData['igst_rate'] = 0;
                productData['cgst_rate'] = 0;
                productData['sgst_rate'] = 0;
            }
    
        productDataArray.push(JSON.stringify(productData));
    });


    // Prepare freight data
    var freightData = {
        'freight_selling_price': 0,
        'freight_taxable_value': 0,
        'freight_sub_total': 0,
        'freight_tax_rate': 0,
        'freight_tax_amount': 0
    };

    // Check if freight row exists and get values
    // $(".freight-row").each(function() {
    //     freightData = {
    //         'freight_selling_price': parseFloat($(this).find('input[name="freight_selling_price"]').val()) || 0,
    //         'freight_taxable_value': parseFloat($(this).find('span[name="freight_taxable_value"]').text()) || 0,
    //         'freight_sub_total': parseFloat($(this).find('span[name="freight_sub_total"]').text()) || 0,
    //         'freight_tax_rate': parseFloat($(this).find('input[name="freight_tax_rate"]').val()) || 0,
    //         'freight_tax_amount': parseFloat($(this).find('span[name="freight_i_tax"]').text()) || 0
    //     };
    // });
    
    $(".freight-row").each(function() {
    freightData = {
        'freight_selling_price': parseFloat($(this).find('input[name="freight_selling_price"]').val()) || 0,
        // We use the new span names here so they aren't 0
            'freight_taxable_value': parseFloat($(this).find('span[name="taxable_value"]').text()) || 0,
            'freight_sub_total': parseFloat($(this).find('span[name="sub_total"]').text()) || 0,
            'freight_tax_rate': parseFloat($(this).find('input[name="freight_tax_rate"]').val()) || 0,
            'freight_tax_amount': parseFloat($(this).find('span[name="i_tax"]').text()) || 0
        };
    });

    // Add hidden field for freight data if it doesn't exist
    if ($('#freight_data').length === 0) {
        $('<input>').attr({
            type: 'hidden',
            id: 'freight_data',
            name: 'freight_data'
        }).appendTo('#addSaleForm');
    }
    $('#freight_data').val(JSON.stringify(freightData));

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


    // Add hidden field for profit data if it doesn't exist
    if ($('#profit_data').length === 0) {
        $('<input>').attr({
            type: 'hidden',
            id: 'profit_data',
            name: 'profit_data'
        }).appendTo('#addSaleForm');
    }
    $('#profit_data').val(JSON.stringify(profitData));

    // Check if there are any items
    if(productDataArray.length > 0) {
        $('#sale_items').val(productDataArray.join('|'));
    } else {
        isError = true;
        $('#emptySaleItemWarningModal').modal('show');
    }

    if(isError) {
        $('#saleSubmit').text('<?=$this->lang->line("sale_add")?>').removeAttr('disabled');
        return false;
    } else {
        return true;
    }
});

      $("form#addSaleForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addSaleForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addSaleForm #'+id).addClass('is-invalid');
          $('form#addSaleForm #'+id).removeClass('is-valid');
          return false;
        }
        else{
          $("form#addSaleForm #err_"+id).text("").fadeOut('slow');
          $('form#addSaleForm #'+id).removeClass('is-invalid');
          $('form#addSaleForm #'+id).addClass('is-valid');
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

              var rcm = $('#rcm').val();

              if(rcm == 'N')
              {
                $('#rcm').val('Y');
                $('.rcm_btn').text('<?=$this->lang->line('sale_disable_rcm')?>');
              }
              else
              {
                $('#rcm').val('N');
                $('.rcm_btn').text('<?=$this->lang->line('sale_enable_rcm')?>');
              }

              // Clear the product and calculate the grand total again              
              $("#product_table_body tr").remove();
              calculateGrandTotal();

              $('#rcm-confirmation').modal('hide');
          });
        }
        else
        {
          var rcm = $('#rcm').val();
          if(rcm == 'N')
          {
            $('#rcm').val('Y');
            $('.rcm_btn').text('<?=$this->lang->line('sale_disable_rcm')?>');
          }
          else
          {
            $('#rcm').val('N');
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
                    //   $('#customer_id').append('<option value="' + response['customers'][i].id + '">' + response['customers'][i].customer_name+'-'+response['customers'][i].phone+'</option>');
                  $('#customer_id').append('<option value="' + response['customers'][i].id + '">' + response['customers'][i].customer_name+'</option>');
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

                show_message('success-header','Customer Added Successfully.');   
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
    var files = this.files;
    var filenames = [];

    for (var i = 0; i < files.length; i++) {
        var file = files[i];
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
            $(this).val(""); // Clear file input
            return;
        }

        // Clear any previous error and show file name
        errorSpan.text("");
        fileInputLabel.text(file.name);
        filenames.push(file.name);
    }

        // Join filenames with commas
        var joinedFilenames = filenames.join(', ');

        // Set the value of the hidden input field to the joined filenames
        $('#document').val(joinedFilenames);

        // Set the value of the hidden input field document[] to filenames array
        $('#document\\[\\]').val(filenames);

        // Create a new FormData object to send the files to the server
        var formData = new FormData();
        for (var j = 0; j < files.length; j++) {
            formData.append("upload_document[]", files[j]);
        }

        var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
        var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

        // Add the CSRF token to the form data
        formData.append(csrfTokenName, csrfTokenValue);

       
        $.ajax({
            url: "<?php echo base_url('sale/upload_documents')?>", 
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(response) {
              if (response.length > 0) {
                var documentValue = response.join(', '); 
                $('#document').val(documentValue); 
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                
                alert("Error uploading document: " + errorThrown);
            }
        });
      });
      

// function addFreightRow(type, amount = 0) {
//     if ($('.freight-row').length > 0) return;

//     let taxRate = (type === 'external') ?  0 : 0;
//     let taxAmount = (amount * taxRate) / 100;
//     let subTotal = amount + taxAmount;

//     let newRow = $('<tr class="service_row freight-row">');
//     let cols = "";

//     cols += '<td><span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
//           + '<input type="hidden" name="freight_id" value="freight"></td>';
//     cols += '<td><b>Freight Charges (' + type + ')</b></td>';
//     cols += '<td>Transport</td>';
//     cols += '<td></td>'; 
//     cols += '<td><input type="hidden" name="quantity" value="1">1</td>';

//     // TABLE INPUT: Users can type here
//     cols += '<td>'
//           + '<input type="number" class="form-control text-right freight-row-amount" name="freight_selling_price" value="' + amount + '" step="any">'
//           + '</td>';

//     cols += '<td>NA</td>';
//     cols += '<td>NA</td>'; 
//     cols += '<td><span name="taxable_value">' + amount.toFixed(2) + '</span></td>';

//     // Tax logic
//     if (type === 'external') {
//         cols += '<td class="tax_td">IGST(18%): <span name="i_tax">' + taxAmount.toFixed(2) + '</span>'
//               + '<span name="c_tax" style="display:none">0</span>'
//               + '<span name="s_tax" style="display:none">0</span></td>';
//     } else {
//         cols += '<td class="tax_td">N/A'
//               + '<span name="i_tax" style="display:none">0</span>'
//               + '<span name="c_tax" style="display:none">0</span>'
//               + '<span name="s_tax" style="display:none">0</span></td>';
//     }

//     cols += '<td><span name="sub_total">' + subTotal.toFixed(2) + '</span></td>';

//     newRow.append(cols);
//     $("#product_table_body").append(newRow);
    
//     calculateGrandTotal();
//     updateProfitTotals();
// }

function addFreightRow(type, amount = 0) {
    if ($('.freight-row').length > 0) return;

    // View logic: External usually has 18% GST
    let taxRate = (type === 'external') ? 0 : 0;
    let taxAmount = (amount * taxRate) / 100;
    let subTotal = amount + taxAmount;

    let newRow = $('<tr class="service_row freight-row">');
    let cols = "";

    cols += '<td><span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
          + '<input type="hidden" name="freight_id" value="freight"></td>';
    cols += '<td><b>Freight Charges (' + type + ')</b></td>';
    cols += '<td>Transport</td>';
    cols += '<td></td>'; 
    cols += '<td><input type="hidden" name="quantity" value="1">1</td>';

    cols += '<td>'
          + '<input type="number" class="form-control text-right freight-row-amount" name="freight_selling_price" value="' + amount + '" step="any">'
          + '</td>';

    cols += '<td>NA</td>'; // HSN
    cols += '<td>NA</td>'; // UOM
    cols += '<td><span name="taxable_value">' + amount.toFixed(2) + '</span></td>';

    if (type === 'external') {
        cols += '<td class="tax_td">IGST (18%): <span name="i_tax">' + taxAmount.toFixed(2) + '</span>'
              + '<span name="c_tax" style="display:none">0</span>'
              + '<span name="s_tax" style="display:none">0</span>'
              + '<input type="hidden" name="igst_rate" value="18">'
              + '<input type="hidden" name="cgst_rate" value="0">'
              + '<input type="hidden" name="sgst_rate" value="0"></td>';
    } else {
        cols += '<td class="tax_td">N/A'
              + '<span name="i_tax" style="display:none">0</span>'
              + '<span name="c_tax" style="display:none">0</span>'
              + '<span name="s_tax" style="display:none">0</span>'
              + '<input type="hidden" name="igst_rate" value="0">'
              + '<input type="hidden" name="cgst_rate" value="0">'
              + '<input type="hidden" name="sgst_rate" value="0"></td>';
    }

    cols += '<td><span name="sub_total">' + subTotal.toFixed(2) + '</span></td>';

    newRow.append(cols);
    $("#product_table_body").append(newRow);
    
    calculateGrandTotal();
}

function refreshFreightCalculations(amount) {
    let type = $('#additional_cost_type').val();
    let row = $('.freight-row');
    if (row.length === 0) return;

    let taxRate = (type === 'external') ?  0 : 0;
    let taxAmount = (amount * taxRate) / 100;
    let subTotal = amount + taxAmount;

    // Update the display spans only (DO NOT TOUCH THE INPUTS HERE)
    row.find('span[name="taxable_value"]').text(amount.toFixed(2));
    if (type === 'external') {
        row.find('span[name="i_tax"]').text(taxAmount.toFixed(2));
    }
    row.find('span[name="sub_total"]').text(subTotal.toFixed(2));

    calculateGrandTotal();
    updateProfitTotals();
}

function updateFreightValues(type, amount) {
    let taxRate = (type === 'external') ? 0 : 0;
    let taxableValue = amount;
    let taxAmount = 0;
    let subTotal = amount;

    if (type === 'external' && amount > 0) {
        taxAmount = (amount * taxRate) / 100;
        subTotal = amount + taxAmount;
    }

    let row = $('.freight-row');
    // Updated selectors to find name="taxable_value" instead of name="freight_taxable_value"
    row.find('span[name="taxable_value"]').text(taxableValue.toFixed(2));
    row.find('input[name="freight_selling_price"]').val(amount.toFixed(2));

    if (type === 'external') {
        row.find('.tax_td').html('IGST : <span name="i_tax">' + taxAmount.toFixed(2) + '</span> (18%)'
              + '<span name="c_tax" style="display:none">0</span>'
              + '<span name="s_tax" style="display:none">0</span>'
              + '<span name="discount_amount" style="display:none">0</span>');
    } else {
        row.find('.tax_td').html('N/A'
              + '<span name="i_tax" style="display:none">0</span>'
              + '<span name="c_tax" style="display:none">0</span>'
              + '<span name="s_tax" style="display:none">0</span>'
              + '<span name="discount_amount" style="display:none">0</span>');
    }

    row.find('span[name="sub_total"]').text(subTotal.toFixed(2));

    // IMPORTANT: Call both functions to refresh UI
    calculateGrandTotal();
    updateProfitTotals();
}

// On change of cost type dropdown
$('#additional_cost_type').change(function () {
    let type = $(this).val();
    let amount = parseFloat($('#additional_cost_amount').val()) || 0;
    
    if (type) {
        if ($('.freight-row').length === 0) {
            addFreightRow(type, amount);
        } else {
            // Update existing row label and recalculate
            $('.freight-row b').text('Freight Charges (' + type + ')');
            refreshFreightCalculations(amount);
        }
    } else {
        $('.freight-row').remove();
        calculateGrandTotal();
        updateProfitTotals();
    }
});

$('#additional_cost_amount').on('input', function() {
    let amountStr = $(this).val();
    let amountNum = parseFloat(amountStr) || 0;
    
    // Update the table row input value (without formatting to prevent cursor jump)
    $('.freight-row-amount').val(amountStr);
    
    refreshFreightCalculations(amountNum);
});

$(document).on('input', '.freight-amount', function () {
    let newAmount = parseFloat($(this).val()) || 0;
    let type = $('#additional_cost_type').val();
    updateFreightValues(type, newAmount);
});

$(document).on('input', '.freight-row-amount', function() {
    let amountStr = $(this).val();
    let amountNum = parseFloat(amountStr) || 0;
    
    // Update the footer input value (without formatting to prevent cursor jump)
    $('#additional_cost_amount').val(amountStr);
    
    refreshFreightCalculations(amountNum);
});

$('table.product_table').on('click', "span.delete_item", function(e) {
    var tr = $(this).closest('tr');
    if (tr.hasClass('freight-row')) {
        $('#additional_cost_type').val('');
        $('#additional_cost_amount').val('');
    }
    tr.remove();
    calculateGrandTotal();
    updateProfitTotals();
});


      
  });
</script>

<script>
$(document).ready(function() {
    
    // ⭐ SHOW TERMS TEXT BASED ON SELECTED DUE DAYS

$('#default_due_days').on('change', function () {

    var selectedOption = $(this).find('option:selected');

    // Get terms from data attribute
    var terms = selectedOption.data('terms') || '';

    // Show preview text
    $('#terms_preview').text(terms || 'No terms available');

    // Store terms in hidden field for DB save
    $('#default_payment_terms').val(terms);
});
    // When warehouse selection changes
    $('#warehouse_id').change(function() {
        var warehouse_id = $(this).val();
        
        if(warehouse_id) {
            // Make AJAX call to get reference number
            $.ajax({
                url: '<?=base_url("sale/get_reference_no")?>',
                type: 'POST',
                data: {warehouse_id: warehouse_id},
                dataType: 'json',
                success: function(response) {
                    if(response.status) {
                        $('#reference_no').val(response.reference_no);
                    } else {
                        $('#reference_no').val('');
                        console.error('Error fetching reference number');
                    }
                },
                error: function() {
                    console.error('Error in AJAX request');
                }
            });
        } else {
            $('#reference_no').val('');
        }
    });

    // Trigger change event on page load if warehouse is already selected
    if($('#warehouse_id').val()) {
        $('#warehouse_id').trigger('change');
    }
    function validateQuantity(input) {
    var row = $(input).closest('tr');
    var quantityInput = $(input);
    var availableQty = parseFloat(quantityInput.data('available-qty')) || 0;
    var enteredQty = parseFloat(quantityInput.val()) || 0;
    var messageSpan = row.find('span[name="quantity_update_message"]');
    
    // Reset message
    messageSpan.text('').removeClass('text-danger text-success');
    quantityInput.removeClass('is-invalid is-valid');
    
    if (enteredQty <= 0) {
        messageSpan.text('Quantity must be greater than 0').addClass('text-danger');
        quantityInput.addClass('is-invalid');
        return false;
    }
    
    if (enteredQty > availableQty) {
        messageSpan.text('Quantity exceeds available stock! Available: ' + availableQty).addClass('text-danger');
        quantityInput.addClass('is-invalid');
        return false;
    }
    
    if (enteredQty <= availableQty) {
        messageSpan.text('Quantity available').addClass('text-success');
        quantityInput.addClass('is-valid');
        return true;
    }
    
    return true;
}

});

$(document).on('keyup change', 'input[name="quantity"]', function() {
    var row = $(this).closest('tr');
    var entered = parseFloat($(this).val()) || 0;
    var stock = parseFloat($(this).data('available-qty')) || 0;
    var err_span = row.find('span[name="quantity_err"]');

    if (entered > stock) {
        $(this).addClass('is-invalid');
        err_span.text('Exceeds Stock (' + stock + ')');
        $('#saleSubmit').attr('disabled', 'disabled');
    } else {
        $(this).removeClass('is-invalid');
        err_span.text('');
        if ($('.is-invalid').length === 0) {
            $('#saleSubmit').removeAttr('disabled');
        }
    }
    calculateRow(row);
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
<small class="text-muted" id="cost_note" style="display:none;">
  18% additional cost auto-applied
</small>


