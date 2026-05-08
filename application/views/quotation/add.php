<?php 
  $this->load->view('layout/header');
  $currency    = $company_setting->currency_symbol;
  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);
  $mfg_date    = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);
?>

<style type="text/css">
.search {
    position: relative;
}

.search input {
    text-indent: 20px;
}

.search .fa-search {
    position: absolute;
    top: 9px;
    left: 20px;
    font-size: 18px;
    padding-left: 8px;
}

.search_product {
    background-color: #F8F8F8;
    height: 35px;
    padding-left: 25px;
    font-size: 18px;
}

.search_product::-webkit-input-placeholder {
    /* Chrome/Opera/Safari */
    padding-left: 5px;
    font-size: 18px;
}

.search_product::-moz-placeholder {
    /* Firefox 19+ */
    font-size: 18px;
}

.search_product:-ms-input-placeholder {
    /* IE 10+ */
    font-size: 18px;
}

.search_product:-moz-placeholder {
    /* Firefox 18- */
    font-size: 18px;
}

.service_row {
    background: #fffee9 !important;
}

.highlight_row {
    /*animation: fadeOut 5s forwards;*/
    background: #fed9c6 !important;
    -webkit-transition: all 0.5s ease;
    -moz-transition: all 0.5s ease;
    -o-transition: all 0.5s ease;
    transition: all 0.5s ease;
}

.quantity_update_message {
    width: 100% !important;
    padding-left: 40% !important;
}

.discount_type {
    padding-left: 20px;
}

.discount_amount {
    padding-right: 10px !important;
}

.delete_item {
    cursor: pointer;
}

.total_data {
    font-size: 18px;
    font-weight: bolder;
}

/* General Table Styling */
.product_table {
    border-collapse: collapse;
    width: 100%;
    font-size: 14px;
    /* Adjust size based on design */
    overflow-x: auto;
    /* Allow horizontal scroll */
}

.product_table th,
.product_table td {
    text-align: left;
    padding: 8px;
    vertical-align: middle;
}

.product_table th {
    background-color: #f4f4f4;
    font-weight: bold;
    text-align: center;
}

.product_table thead th {
    position: sticky;
    top: 0;
    z-index: 1;
}

.product_table th,
.product_table td {
    min-width: 50px;
    max-width: 300px;
    word-wrap: break-word;
}

.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
}


@media (max-width: 1024px) {

    .product_table th,
    .product_table td {
        font-size: 12px;
        padding: 6px;
    }

    .product_table {
        font-size: 12px;
    }
}

#product_table_body input {
    width: 7em !important;
}
</style>
<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <ol class="breadcrumb breadcrumb-custom float-sm-left">
                        <li class="breadcrumb-item"><a href="<?=base_url('auth')?>"><?=$this->lang->line('home')?></a>
                        </li>

                        <li class="breadcrumb-item "><a
                                href="<?=base_url('quotation')?>"><?=$this->lang->line('quotation_header')?></a></li>
                        <li class="breadcrumb-item active"><?=$this->lang->line('quotation_add')?></li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" name="addQuotationForm" id="addQuotationForm" method="POST"
                        action="<?=base_url('quotation/add')?>">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?=$this->lang->line('quotation_add')?></h3>
                                <div class="card-tools">

                                    <ul class="nav nav-pills ml-auto">

                                        <li class="nav-item  ml-2">
                                            <a class="nav-link reset btn-sm btn-warning" href="#" data-tt="tooltip"
                                                title="Click here to Reset Quotation Items">
                                                <i class="fas fa-redo-alt"></i> Reset
                                            </a>
                                        </li>

                                        <li class="nav-item ml-2">
                                            <a class="nav-link active" href="<?=base_url('quotation')?>"
                                                data-tt="tooltip" title="Click here to show quotation list"><i
                                                    class="fas fa-arrow-left mr-2"></i>Back</a>
                                        </li>

                                        <li class="nav-item ml-2">
                                            <a class="nav-link import_product_modal btn-sm btn-secondary text-white"
                                                href="#" data-tt="tooltip" title="Click here to Import Product">
                                                <i class="fas fa-file-import"></i> Import Product
                                            </a>
                                        </li>

                                    </ul>

                                    <div class="btn-group d-none">
                                        <button type="button" class="btn btn-tool btn-warning rcm_btn" data-tt="tooltip"
                                            title="<?=$this->lang->line('quotation_rcm_change_status')?>">
                                            <?=$this->lang->line('quotation_enable_rcm')?>
                                        </button>
                                        <button type="button" class="btn btn-tool btn-warning" data-tt="tooltip"
                                            title="<?=$this->lang->line('quotation_rcm_help')?>">
                                            <i class="far fa-question-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <!--<div class="col-sm-3">-->
                                        <!--  <div class="form-group">-->
                                        <!--<label><?=$this->lang->line('quotation_reference_no')?></label>-->
                                        <input type="hidden" class="form-control" id="reference_no" name="reference_no"
                                            disabled="">
                                        <!--  </div>-->
                                        <!--</div>-->
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label><?=$this->lang->line('quotation_date')?></label>
                                                <input type="text" class="form-control datepicker" id="quotation_date"
                                                    name="quotation_date" value="<?=date('d-m-Y');?>">
                                                <span id="err_quotation_date"
                                                    class="error invalid-feedback"><?=form_error('quotation_date');?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>Validity Period (Days)</label>
                                                <input type="number" class="form-control field_validation"
                                                    id="valid_days" name="valid_days" value="30" min="1" max="365"
                                                    required placeholder="Validity Period">
                                                <span id="err_valid_days"
                                                    class="error invalid-feedback"><?=form_error('valid_days');?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="warehouse">
                                                    <?=$this->lang->line('quotation_warehouse')?>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group input-group-sm">
                                                    <?php 
                                $user_id = $this->session->userdata('user_id');
                                $user = $this->db->get_where('users', ['id' => $user_id])->row();
                
                                if ($user && isset($user->branch_id) && $user->branch_id): 
                                    $user_branch_id = $user->branch_id;
                                    $warehouse_name = '';
                                    foreach ($warehouses as $value) {
                                        if ($value->id == $user_branch_id) {
                                            $warehouse_name = $value->name;
                                            break;
                                        }
                                    }
                            ?>
                                                    <input type="hidden" name="warehouse_id" id="warehouse_id"
                                                        value="<?=$user_branch_id;?>">
                                                    <input type="text" class="form-control form-control-sm"
                                                        value="<?=$warehouse_name;?>" readonly>
                                                    <?php else: ?>
                                                    <select
                                                        class="form-control form-control-sm select2bs4 field_validation"
                                                        name="warehouse_id" id="warehouse_id" width="100%"
                                                        placeholder="<?=$this->lang->line('purchase_warehouse')?>">
                                                        <option value=""><?=$this->lang->line('select')?></option>
                                                        <?php foreach ($warehouses as $value): ?>
                                                        <option value="<?=$value->id;?>"
                                                            <?= ($value->id == set_value('warehouse_id')) ? 'selected' : ''; ?>>
                                                            <?= $value->name;?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>
                                                    <span class="input-group-append">
                                                        <button type="button"
                                                            class="btn btn-info btn-flat add_warehouse_modal"
                                                            data-toggle="modal" data-target="#add_warehouse_modal"
                                                            data-tt="tooltip" accesskey="c"><i
                                                                class="fas fa-plus"></i></button>
                                                    </span>
                                                    <?php endif; ?>
                                                    <span id="err_warehouse_id"
                                                        class="error invalid-feedback"><?=form_error('warehouse_id');?></span>
                                                </div>
                                            </div>
                                        </div>

                                        <!--<div class="col-sm-3">
                                            <div class="form-group">
                                                <label for="customer">
                                                    <?= $this->lang->line('quotation_customer') ?>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <div class="input-group input-group-sm">
                                                    <select
                                                        class="form-control form-control-sm select2bs4 field_validation"
                                                        name="customer_id" id="customer_id" width="100%"
                                                        placeholder="<?= $this->lang->line('quotation_customer') ?>">

                                                        <option value=""><?= $this->lang->line('select') ?></option>

                                                        <?php foreach ($customers as $value): ?>
                                                        <?php
                    $displayText = $value->customer_name;
                    if (!empty($value->customer_company_name)) {
                        $displayText .= ' (' . $value->customer_company_name . ')';
                    }
                    ?>
                                                        <option value="<?= $value->id; ?>"
                                                            <?= set_select('customer_id', $value->id); ?>>
                                                            <?= htmlspecialchars($displayText); ?>
                                                        </option>
                                                        <?php endforeach; ?>
                                                    </select>

                                                    <span class="input-group-append">
                                                        <button type="button" class="btn btn-info btn-flat"
                                                            data-toggle="modal" data-target="#add_customer_modal"
                                                            data-tt="tooltip" accesskey="c">
                                                            <i class="fas fa-plus"></i>
                                                        </button>
                                                    </span>
                                                </div>
                                                <input type="hidden" name="customer_state_id" id="customer_state_id"
                                                    value="">
                                                <input type="hidden" name="customer_country_id" id="customer_country_id"
                                                    value="">
                                                <span id="err_customer_id"
                                                    class="error invalid-feedback"><?= form_error('customer_id'); ?></span>
                                            </div>
                                        </div>-->
                                       <!-- <div class="col-sm-3">
                <div class="form-group">
                    <label for="customer">
                        <?= $this->lang->line('quotation_customer') ?>
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group input-group-sm">
                     <select class="form-control form-control-sm select2bs4 field_validation" 
                        name="customer_id" 
                        id="customer_id" 
                        width="100%" 
                        placeholder="<?= $this->lang->line('quotation_customer') ?>">
                
                    <option value=""><?= $this->lang->line('select') ?></option>
                
                    <?php foreach ($customers as $value): ?>
                        <option value="<?= $value->id; ?>" <?= set_select('customer_id', $value->id); ?>>
                            <?= $value->customer_name; ?>
                            <?php if (!empty($value->customer_company_name)): ?>
                                (<?= $value->customer_company_name; ?>)
                            <?php endif; ?>
                        </option>
                    <?php endforeach; ?>
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
            <?= $this->lang->line('quotation_customer') ?>
            <span class="text-danger">*</span>
        </label>
        <div class="input-group input-group-sm">
            <select class="form-control form-control-sm select2bs4 field_validation" 
                name="customer_id" 
                id="customer_id" 
                width="100%" 
                placeholder="<?= $this->lang->line('quotation_customer') ?>">
            
                <option value=""><?= $this->lang->line('select') ?></option>
            
                <?php foreach ($customers as $value): ?>
                    <option value="<?= $value->id; ?>" <?= set_select('customer_id', $value->id); ?>>
                        <?php 
                        $displayText = $value->customer_name;
                        if (!empty($value->customer_company_name)) {
                            $displayText .= ' (' . $value->customer_company_name . ')';
                        }
                        echo htmlspecialchars($displayText);
                        ?>
                    </option>
                <?php endforeach; ?>
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

                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label>Select Shipping Address</label>
                                                <select class="form-control form-control-sm"
                                                    id="shipping_address_select" name="shipping_address_select">
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
                                                <select class="form-control form-control-sm select2bs4 field_validation"
                                                    name="customer_shipping_country_id"
                                                    id="customer_shipping_country_id" width="100%"
                                                    placeholder="<?=$this->lang->line('customer_shipping_country_id')?>"
                                                    tabindex="<?=$tabindex++?>">
                                                    <?php
                foreach ($countries as $value) {
            ?>
                                                    <option value="<?=$value->id;?>"
                                                        <?php echo set_select('customer_shipping_country_id', $value->id); ?>>
                                                        <?= $value->name;?>
                                                    </option>
                                                    <?php 
                }
            ?>
                                                </select>
                                                <span id="err_customer_shipping_country_id"
                                                    class="error invalid-feedback"><?=form_error('phone');?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>
                                                    <?=$this->lang->line('customer_shipping_state_id')?>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control form-control-sm select2bs4 field_validation"
                                                    name="customer_shipping_state_id" id="customer_shipping_state_id"
                                                    width="100%"
                                                    placeholder="<?=$this->lang->line('customer_shipping_state_id')?>"
                                                    tabindex="<?=$tabindex++?>">
                                                    <option value=""></option>
                                                </select>
                                                <span id="err_customer_shipping_state_id"
                                                    class="error invalid-feedback"><?=form_error('customer_shipping_state_id');?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label>
                                                    <?=$this->lang->line('customer_shipping_city_id')?>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <select class="form-control form-control-sm select2bs4 field_validation"
                                                    name="customer_shipping_city_id" id="customer_shipping_city_id"
                                                    width="100%" tabindex="<?=$tabindex++?>">
                                                    <?php
                    if(isset($cities)) {
                        foreach ($cities as $value) {
                ?>
                                                    <option value="<?=$value->id;?>"
                                                        <?php echo set_select('customer_shipping_city_id', $value->id); ?>>
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
                                                <input type="text" name="customer_shipping_address" value=""
                                                    class="form-control field_validation" id="customer_shipping_address"
                                                    placeholder="<?=$this->lang->line('customer_shipping_address')?>"
                                                    tabindex="<?=$tabindex++?>"><?=form_error('shipping_address', '<div class="text-danger">', '</div>');?>
                                                <span id="err_customer_shipping_address"
                                                    class="error invalid-feedback"><?=form_error('customer_shipping_address', '<div class="text-danger">', '</div>');?></span>
                                            </div>
                                        </div>

                                        <div class="col-sm-3">
                                            <div class="form-group">
                                                <label>
                                                    <?=$this->lang->line('customer_shipping_pincode')?>
                                                    <span class="text-danger">*</span>
                                                </label>
                                                <input type="text" name="customer_shipping_pincode"
                                                    value="<?=set_value('customer_shipping_pincode') ?>"
                                                    class="form-control field_validation" id="customer_shipping_pincode"
                                                    placeholder="<?=$this->lang->line('customer_shipping_pincode')?>"
                                                    tabindex="<?=$tabindex++?>">
                                                <span id="err_customer_shipping_pincode"
                                                    class="error invalid-feedback"><?=form_error('customer_shipping_pincode', '<div class="text-danger">', '</div>');?></span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12 ">
                                            <div class="well">
                                                <div class="row">
                                                    <div class="col-sm-12">
                                                        <a href="" data-toggle="modal" data-tt="tooltip"
                                                            data-target="#add_service_modal"
                                                            class="float-right"><?=$this->lang->line('quotation_add_new_service')?></a>
                                                    </div>
                                                    <div class="col-sm-12 search">
                                                        <span class="fa fa-search"></span>
                                                        <input id="search_product" class="form-control search_product"
                                                            type="text" name="search_product"
                                                            placeholder="<?=$this->lang->line('quotation_item_search')?>">
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
                                                <div class="table-responsive"
                                                    style="max-width: 100%; overflow-x: auto;">
                                                    <table
                                                        class="table items table-striped table-bordered table-condensed table-hover product_table"
                                                        style="min-width: 1200px; table-layout: auto;"
                                                        id="product_data">
                                                        <thead>
                                                            <tr>
                                                                <th width="2%">
                                                                    <img
                                                                        src="<?php echo base_url(); ?>assets/images/bin1.png" />
                                                                </th>
                                                                <th class="span2" width="15%">Product name</th>
                                                                <th class="span2" width="10%">Add Remark</th>
                                                                <th class="span2" width="12%">Vendor</th>
                                                                <th class="span2" width="8%">Purchase Cost</th>
                                                                <th class="span2" width="8%">
                                                                    <?=$this->lang->line('quotation_qty')?></th>
                                                                <th class="span2" width="10%">
                                                                    <?=$this->lang->line('quotation_price')?></th>
                                                                <th class="span2 d-none" width="100px">
                                                                    <?=$this->lang->line('quotation_discount')?></th>
                                                                <th class="span2" width="11%">
                                                                    <?=$this->lang->line('quotation_uom')?></th>
                                                                <th class="span2" width="8%">
                                                                    <?=$this->lang->line('quotation_taxable_value')?>
                                                                </th>
                                                                <th class="span2" width="12%">
                                                                    <?=$this->lang->line('quotation_tax')?>(₹)</th>
                                                                <th class="span2 d-none" width="100px">
                                                                    <?=$this->lang->line('quotation_inclusive')?></th>
                                                                <th class="span2" width="7%">
                                                                    <?=$this->lang->line('quotation_total')?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="product_table_body">
                                                        </tbody>
                                                    </table>

                                                    <!-- Transport dropdown -->
                                                    <table
                                                        class="table table-striped table-bordered table-condensed table-hover total_data">
                                                        <tr>
                                                            <td align="right" width="66%">
                                                                <select class="form-control" id="additional_cost_type"
                                                                    name="additional_cost_type">
                                                                    <option value="">Select Transport</option>
                                                                    <option value="internal">Internal</option>
                                                                    <option value="external">External</option>
                                                                </select>
                                                            </td>
                                                            <td align='right' width="34%">
                                                                <input type="number" class="form-control"
                                                                    id="additional_cost_amount"
                                                                    name="additional_cost_amount" value="0" step="0.01">
                                                            </td>
                                                        </tr>

                                                        <!-- Total Taxable Value (excluding Freight) -->
                                                        <tr>
                                                            <td align="right" width="66%">Total Taxable Value (₹)</td>
                                                            <td align='right' class="text-success" width="34%">
                                                                +<span id="total_taxable_value">0.00</span>
                                                                <input type="hidden" name="total_taxable_value"
                                                                    id="t_taxable_value" value="0">
                                                            </td>
                                                        </tr>

                                                        <!-- Product Tax Only -->
                                                        <tr>
                                                            <td align="right" width="66%">Total Product Tax (₹)</td>
                                                            <td align='right' class="text-success" width="34%">
                                                                +<span id="total_tax">0.00</span>
                                                                <input type="hidden" name="total_tax" id="t_tax"
                                                                    value="0">
                                                            </td>
                                                        </tr>

                                                        <tr>
                                                            <td align="right" width="66%">Total (₹)</td>
                                                            <td align='right' width="34%">
                                                                <span id="total">0.00</span>
                                                                <input type="hidden" name="total" id="t" value="0">
                                                            </td>
                                                        </tr>
                                                    </table>

                                                    <!-- Profit/Loss Section -->
                                                    <table
                                                        class="table table-striped table-bordered table-condensed table-hover profit_data">
                                                        <tr>
                                                            <td align="right" width="66%">Total Purchase
                                                                Cost(<?=$currency?>)</td>
                                                            <td align='right' width="34%">
                                                                <span id="total_purchase_cost">0.00</span>
                                                                <input type="hidden" name="total_purchase_cost"
                                                                    id="t_purchase_cost" value="0">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" width="66%">Total Selling
                                                                Price(<?=$currency?>)</td>
                                                            <td align='right' width="34%">
                                                                <span id="total_selling_price">0.00</span>
                                                                <input type="hidden" name="total_selling_price"
                                                                    id="t_selling_price" value="0">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" width="66%">Gross
                                                                Profit/Loss(<?=$currency?>)</td>
                                                            <td align='right' width="34%">
                                                                <span id="gross_profit_loss">0.00</span>
                                                                <input type="hidden" name="gross_profit_loss"
                                                                    id="t_profit_loss" value="0">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" width="66%">Profit Margin(%)</td>
                                                            <td align='right' width="34%">
                                                                <span id="profit_margin">0.00</span>
                                                                <input type="hidden" name="profit_margin"
                                                                    id="profit_margin" value="">
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
                                                            <ul class="nav nav-tabs" id="custom-content-below-tab"
                                                                role="tablist">
                                                                <li class="nav-item">
                                                                    <a class="nav-link active" href="#external_note"
                                                                        data-toggle="pill"><?php echo $this->lang->line('quotation_external_note'); ?></a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" href="#internal_note"
                                                                        data-toggle="pill"><?php echo $this->lang->line('quotation_internal_note'); ?></a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" href="#bank_detail"
                                                                        data-toggle="pill"><?php echo $this->lang->line('quotation_bank_detail'); ?></a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" href="#terms_and_condition"
                                                                        data-toggle="pill"><?php echo $this->lang->line('quotation_terms_and_condition'); ?></a>
                                                                </li>
                                                            </ul>
                                                            <br>
                                                            <div class="tab-content">
                                                                <div class="tab-pane active" id="external_note">
                                                                    <textarea class="col-sm-12 form-control"
                                                                        name="external_note" rows="4"
                                                                        placeholder="<?php echo $this->lang->line('quotation_external_note'); ?>"></textarea>
                                                                </div>
                                                                <div class="tab-pane" id="internal_note">
                                                                    <textarea class="col-sm-12 form-control"
                                                                        name="internal_note" rows="4"
                                                                        placeholder="<?php echo $this->lang->line('quotation_internal_note'); ?>"></textarea>
                                                                </div>
                                                                <div class="tab-pane" id="bank_detail">
                                                                    <textarea class="col-sm-12 form-control"
                                                                        name="bank_detail" rows="4"
                                                                        placeholder="<?php echo $this->lang->line('quotation_bank_detail'); ?>"><?=str_replace("<br />","",$company_setting->bank_detail)?></textarea>
                                                                </div>
                                                                <div class="tab-pane" id="terms_and_condition">
                                                                    <textarea class="col-sm-12 form-control"
                                                                        name="terms_and_condition" rows="4"
                                                                        placeholder="<?php echo $this->lang->line('quotation_terms_and_condition');?>"><?=str_replace("<br />","",$company_setting->terms_and_condition)?></textarea>
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
                                    <input type="hidden" name="quotation_items" id="quotation_items" value="">
                                    <input type="hidden" name="company_state_id" id="company_state_id"
                                        value="<?=$company_setting->state_id?>">
                                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                        value="<?php echo $this->security->get_csrf_hash(); ?>">
                                    <input type="hidden" name="company_country_id" id="company_country_id"
                                        value="<?=$company_setting->country_id?>">
                                    <button type="submit" name="submit" id="quotationSubmit"
                                        class="btn btn-info"><?=$this->lang->line('quotation_add')?></button>
                                    <!--<button type="submit" name="submit" id="quotationSubmitPayNow" value="pay" name="pay" class="btn btn-info">Add Sale & Pay Now</button>                  -->
                                    <span class="btn btn-default float-right" id="cancel"
                                        onclick="cancel('quotation')"><?=$this->lang->line('quotation_cancel')?></span>
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
  // $this->load->view('service/add_service_modal');
?>

<div class="modal fade" id="emptyQuotationItemWarningModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header warning-header">
                <h5 class="modal-title" id="exampleModalLabel"><?=$this->lang->line('message')?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?=$this->lang->line('empty_quotation_warning_label')?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="example-modal">
    <div class="modal fade" id="import_product_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

<div class="example-modal">
    <div class="modal fade" id="add_warehouse_modal" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
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

                    <button type="submit" name="resetSubmit" id="resetSubmit" class="btn btn-secondary"
                        value="">Reset</button>

                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/disableautofill/src/jquery.disableAutoFill.min.js"></script>

<script type="text/javascript">
$(document).ready(function(e) {

    const warehouseToast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 10000
    });

    $("#customer_id").closest('.row').siblings().css('display', 'none');
    $('#shipping_address_select').closest('.form-group').hide();
    $(".customer_shipping_detail").hide();

    $(document).on('click', "#resetSubmit", function(e) {
        e.preventDefault();

        $('span.delete_item').trigger('click');

        $('#customer_id').val('');
        $('#customer_id').trigger('change');
        $('#reset_model').modal('hide');

    });


    $(document).on('click', ".reset", function() {
        $('#reset_model').modal('show');
    });

    $(document).on('click', ".add_warehouse_modal", function() {

        $.ajax({
            url: "<?php echo base_url('warehouse/add')?>",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#add_warehouse_modal').find('.modal-content').html(data
                    .add_warehouse_modal_body);
                $('#add_warehouse_modal').modal('show');
                $('.select2bs4').select2({
                    theme: 'bootstrap4'
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status);
                alert(thrownError);
                alert(ajaxOptions);
            }
        });
    });

    $(document).on('submit', '#addWarehouseForm', function(e) {

        e.preventDefault();

        $('#addWarehouseSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled',
            'disabled');
        var formData = $('#addWarehouseForm').serialize();

        var isError = false;

        $('form#addWarehouseForm .field_validation').each(function() {

            var id = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if (value == null || value == "") {
                $("form#addWarehouseForm  #err_" + id).text(field + " field is required.");
                $('form#addWarehouseForm  #' + id).addClass('is-invalid');
                isError = true;
            } else {
                $("form#addWarehouseForm #err_" + id).text("");
                $('form#addWarehouseForm #' + id).removeClass('is-invalid');
                $('form#addWarehouseForm #' + id).addClass('is-valid');
            }

        });

        if (isError == true) {
            $('#addWarehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            return false;
        } else {
            $.ajax({
                url: "<?php echo base_url('warehouse/add')?>",
                type: "POST",
                data: formData,
                dataType: "JSON",
                success: function(response) {
                    if (response.code == 1) {
                        $('#add_warehouse_modal').modal('hide');
                        $('form#addWarehouseForm #addWarehouseSubmit').text(
                            '<?=$this->lang->line("submit")?>').removeAttr('disabled');
                        if ($('form#addQuotationForm #warehouse_id').length) {
                            $('form#addQuotationForm #warehouse_id').html('');
                            $('form#addQuotationForm #warehouse_id').append(
                                '<option value="">Select</option>');

                            for (i = 0; i < response['warehouses'].length; i++) {
                                $('form#addQuotationForm #warehouse_id').append(
                                    '<option value="' + response['warehouses'][i].id +
                                    '">' + response['warehouses'][i].name + '</option>');
                            }

                            $('form#addQuotationForm #warehouse_id').val(response['id'])
                                .attr("selected", "selected");


                            warehouseToast.fire({
                                type: 'success',
                                title: response.message
                            });
                        } else {
                            // show_message('success-header',response.message);
                            warehouseToast.fire({
                                type: 'success',
                                title: response.message
                            });

                            location.reload(true);
                        }
                    } else {
                        warehouseToast.fire({
                            type: 'error',
                            title: response.message
                        });
                        $('#addWarehouseSubmit').text('<?=$this->lang->line("submit")?>')
                            .removeAttr('disabled');
                    }
                }
            });
        }

    });

    $(document).on("blur change keyup", "form#addWarehouseForm  .field_validation", function(event) {
        var id = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if (value == null || value == "") {
            $("form#addWarehouseForm #err_" + id).text(field + " field is required.");
            $('form#addWarehouseForm #' + id).addClass('is-invalid');
            return false;
        } else {
            $("form#addWarehouseForm #err_" + id).text("");
            $('form#addWarehouseForm #' + id).removeClass('is-invalid');
            $('form#addWarehouseForm #' + id).addClass('is-valid');
        }
    });


    $("#customer_id").closest('.row').siblings().css('display', 'none');

    $('#warehouse_id').change(function(e) {

        var warehouse_id = $(this).val();
        if (warehouse_id != '') {
            if ($('#customer_id').val() != '') {
                $("#warehouse_id").closest('.row').siblings().fadeIn(10);
            }

            $.ajax({
                url: "<?php echo base_url('warehouse/get_record_details') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    'warehouse_id': warehouse_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(data) {
                    var warehouse = data.warehouse;
                    // $("#product_table_body tr").remove();
                    // calculateGrandTotal();
                    refresh_tax_td();

                }
            });
        }
    });

    $('#customer_shipping_country_id').change(function() {
        var id = $(this).val();
        $('#customer_shipping_state_id').html('<option value="">Select</option>');
        $('#customer_shipping_city_id').html('<option value="">Select</option>');

        $.ajax({
            url: "<?php echo base_url('utility/get_states') ?>/" + id,
            async: false,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $('#customer_shipping_state_id').append('<option value="' + data[i].id +
                        '">' + data[i].name + '</option>');
                }
                $('#customer_shipping_state_id').trigger('change');
            }
        });
    });

    $('#customer_shipping_state_id').change(function() {
        var id = $(this).val();
        if (id != '') {
            $.ajax({
                url: "<?php echo base_url('utility/get_cities') ?>/" + id,
                async: false,
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#customer_shipping_city_id').html(
                        '<option value="">Select</option>');
                    for (i = 0; i < data.length; i++) {
                        $('#customer_shipping_city_id').append('<option value="' + data[i]
                            .id + '">' + data[i].name + '</option>');
                    }
                }
            });
        }
    });

    $('#customer_id').change(function(e) {

        $(".customer_shipping_detail").fadeIn(10);

        var customer_id = $(this).val();
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
                    var customer = data.customer;
                    var shipping_addresses = data.shipping_addresses || [];

                    $('#customer_state_id').val(customer.state_id);
                    $('#customer_country_id').val(customer.country_id);

                    // Populate shipping address dropdown
                    var $shippingDropdown = $('#shipping_address_select');
                    $shippingDropdown.empty();
                    $shippingDropdown.append(
                        '<option value="">Select Shipping Address</option>');

                    // Add each shipping address as an option
                    $.each(shipping_addresses, function(index, address) {
                        var optionText = address.shipping_name + ' - ' + address
                            .shipping_address;
                        $shippingDropdown.append(
                            $('<option></option>')
                            .val(address.id)
                            .text(optionText)
                            .data('address', address)
                        );
                    });

                    // Set default values from customer record
                    $('#customer_shipping_country_id').val(customer.shipping_country_id ||
                        '').trigger('change');
                    $('#customer_shipping_state_id').val(customer.shipping_state_id || '')
                        .trigger('change');
                    $('#customer_shipping_city_id').val(customer.shipping_city_id || '');
                    $('#customer_shipping_address').val(customer.shipping_address || '');
                    $('#customer_shipping_pincode').val(customer.shipping_pincode || '');

                    if (customer.shipping_state_id != '' && customer.shipping_state_id !=
                        null) {
                        $('#customer_state_id').val(customer.shipping_state_id);
                    }

                    refresh_tax_td();
                }
            });
        } else {
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
            setTimeout(function() {
                $('#customer_shipping_state_id').val(addressData.shipping_state_id).trigger(
                    'change');
                $('#customer_shipping_city_id').val(addressData.shipping_city_id);

                // Step 3: Wait for the city AJAX call to finish (by checking when options are more than 1)
                let waitForCities = setInterval(function() {
                    if ($('#customer_shipping_city_id option').length > 1) {
                        clearInterval(waitForCities);

                        // Now that cities are populated, set the city value
                        $('#customer_shipping_city_id').val(addressData
                            .shipping_city_id);

                        // Set remaining fields
                        $('#customer_shipping_address').val(addressData
                            .shipping_address);
                        $('#customer_shipping_pincode').val(addressData
                            .shipping_pincode);
                    }
                }, 100); // check every 100ms
            }, 800); // wait for state dropdown to update
        }
    });

    function refresh_tax_td() {
        var company_country_id = $('#company_country_id').val();
        var company_state_id = $('#company_state_id').val();
        var customer_state_id = $('#customer_state_id').val();
        var customer_country_id = $('#customer_country_id').val();

        $("#product_table_body").find('tr').each(function() {
            var tr = $(this).closest("tr");
            var tax_td = tr.find('.tax_td');
            // var igst            = parseFloat(tax_td.find('span[name="igst"]').text());
            // var cgst            = parseFloat(tax_td.find('span[name="cgst"]').text());
            // var sgst            = parseFloat(tax_td.find('span[name="sgst"]').text());

            var igst = 0;
            var cgst = 0;
            var sgst = 0;

            if (company_country_id == customer_country_id) {
                if (company_state_id == customer_state_id) {
                    cgst = parseFloat(tax_td.find('input[name^="cgst_rate"]').val());
                    sgst = parseFloat(tax_td.find('input[name^="sgst_rate"]').val());
                } else {
                    igst = parseFloat(tax_td.find('input[name^="igst_rate"]').val());
                }
            }

            var tax_rate = igst + cgst + sgst;



            var igst = tax_rate;
            var cgst = (parseFloat(tax_rate / 2)).toFixed(2);
            var sgst = (parseFloat(tax_rate / 2)).toFixed(2);



            var tax_id = tax_td.find('input[name="tax_id"]').val();
            var tax = '<input type="hidden" name="tax_id" value="' + tax_id + '">';

            if (company_country_id == customer_country_id) {
                if (company_state_id == customer_state_id) {
                    tax += 'CGST : <span name="c_tax">' + 0.0 + '</span><br/>';
                    tax += 'SGST : <span name="s_tax">' + 0.0 + '</span>';
                    tax +=
                        '<span name="i_tax"style="display:none">0</span><span name="igst" style="display:none">0</span>';
                } else {
                    tax += 'IGST : <span name="i_tax">' + 0.0 + '</span><br/>';
                    tax +=
                        '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
                    tax +=
                        '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
                }
            } else {
                tax += 'N/A';
                tax +=
                    '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
                tax +=
                    '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
                tax +=
                    '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
            }


            tax_td.html(tax);
            calculateRow(tr);
            calculateGrandTotal();
        });
    }



    var c_mapping = {};

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
                            suggestions.push(products[i].warehouse_products_id +
                                ' - ' + products[i].name + ' - ' + products[
                                    i].product_category_name + ' - ' +
                                products[i].selling_price);
                            c_mapping[products[i].warehouse_products_id] =
                                products[i].name;
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
                                if (!is_product_exist_in_row(product
                                        .warehouse_products_id)) {
                                    add_row(product, discounts,
                                        vendors);
                                } else {
                                    highlight_row(product
                                        .warehouse_products_id);
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
                                console.error(
                                    'Error fetching vendors:',
                                    error);
                                // Continue with adding row even if vendors fetch fails
                                if (!is_product_exist_in_row(product
                                        .warehouse_products_id)) {
                                    add_row(product, discounts, []);
                                } else {
                                    highlight_row(product
                                        .warehouse_products_id);
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



    $(document).on('click', ".import_product_modal", function(event) {
        event.preventDefault();

        var customer_id = $('#customer_id').val();
        var warehouse_id = $('#warehouse_id').val();

        if (customer_id != '' && warehouse_id != '') {
            $.ajax({
                url: "<?php echo base_url('quotation/import_product')?>",
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#import_product_modal').find('.modal-content').html(data
                        .import_product_modal_body);
                    $('#import_product_modal').modal('show');

                    $('.select2bs4').select2({
                        theme: 'bootstrap4'
                    });
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    // alert(xhr.status);
                    show_message('failure-header', thrownError);
                    // alert(thrownError);
                    // alert(ajaxOptions);
                }
            });
        } else {
            Swal.fire({
                title: "Message",
                text: "Please select customer and warehouse to import product",
                // type: "warning",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                timer: 5000,
                customClass: {
                    confirmButton: "btn btn-primary"
                }
            });
        }


    });

    $(document).on('submit', 'form#importproductForm', function(event) {
        event.preventDefault();

        $('form#importproductForm #importproductSubmit').text('<?=$this->lang->line("please_wait")?>')
            .attr('disabled', 'disabled');

        var csvfileInput = $('form#importproductForm #csvfile')[0];


        if (csvfileInput.files.length === 0) {
            alert('Please select a CSV file before submitting.');
            return false;
        }


        var formData = new FormData();
        formData.append('csvfile', csvfileInput.files[0]);

        var customerId = $('form#addQuotationForm #customer_id').val();
        var warehouseId = $('form#addQuotationForm #warehouse_id').val();

        formData.append('customer_id', customerId);
        formData.append('warehouse_id', warehouseId);

        var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>";
        var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>";

        formData.append(csrfTokenName, csrfTokenValue);

        $.ajax({
            url: "<?php echo base_url('quotation/import_product')?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            success: function(response) {
                //console.log("Response from server:", response);

                // var warehouseProductData  = response.warehouseProductData;
                var ProductData = response.ProductData;
                var discount = response.discount;


                if (response.code === 1) {
                    //console.log("Warehouse Product Data", holdQuantities);
                    // Now, call the add_row function for each matched product
                    for (var i = 0; i < ProductData.length; i++) {
                        var product = ProductData[i];
                        var product_id = parseInt(product.id);
                        var discounts = discount;

                        if (!is_product_exist_in_row(product.id)) {
                            $('#import_product_modal').modal('hide');
                            add_row(product, discounts);

                        } else {
                            $('#import_product_modal').modal('hide');
                            highlight_row(product.id);
                        }

                        calculateGrandTotal();
                    }

                    if (response.message != '') {
                        Swal.fire({
                            title: "Message",
                            html: response.message,
                            // type: "warning",
                            buttonsStyling: !1,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    }
                } else if (response.code === 0) {
                    Swal.fire({
                        title: "Message",
                        text: response.message,
                        // type: "warning",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        timer: 5000,
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    });

                    $('#csvfile').val('');
                    $('#fileLabel').text('Choose file');
                }


                $('form#importproductForm #importproductSubmit').text(
                    '<?=$this->lang->line("submit")?>').removeAttr('disabled');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred while uploading the CSV file.");

                $('form#importproductForm #importproductSubmit').text(
                    '<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
        });
    });




    // Add product row function
    function add_row(product, discounts, vendors) {
        var customer_country_id = $('#customer_country_id').val();
        var customer_state_id = $('#customer_state_id').val();
        var company_country_id = $('#company_country_id').val();
        var company_state_id = $('#company_state_id').val();

        var quantity = product.quantity;
        var input_quantity =
            '<input type="number" class="form-control field_validation" name="quantity" step="1" min="1" required placeholder="Quantity">';

        var taxable_value = '<span name="taxable_value">' + product.selling_price + '</span>';

        // Tax calculation
        var tax = '<input type="hidden" name="tax_id" value="' + product.tax_id + '">';
        if (company_country_id == customer_country_id) {
            if (company_state_id == customer_state_id) {
                tax += 'CGST : <span name="c_tax">0.0</span>(<span name="cgst">' + product.cgst +
                    '%</span>)<br/>';
                tax += 'SGST : <span name="s_tax">0.0</span>(<span name="sgst">' + product.sgst + '%</span>)';
                tax +=
                    '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
            } else {
                tax += 'IGST : <span name="i_tax">0.0</span>(<span name="igst">' + product.igst +
                    '%</span>)<br/>';
                tax +=
                    '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
                tax +=
                    '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
            }
        } else {
            tax += 'N/A';
            tax +=
                '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
            tax +=
                '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
            tax +=
                '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
        }

        // UOM input
        var input_uom = '<input type="hidden" name="uom_id" value="' + product.uom_id + '">' +
            '<input type="hidden" name="uom_name" value="' + product.uom_name + '">' +
            '<input type="hidden" name="uom_uom" value="' + product.uom_uom + '">' +
            product.uom_uom;

        // Supplier select dropdown - Only show vendors who supplied this product
        var supplier_select =
            '<select class="form-control select2bs4 supplier-select" name="supplier_id" style="width: 100%;">';
        supplier_select += '<option value="">Select Supplier</option>';

        if (vendors && vendors.length > 0) {
            vendors.forEach(function(vendor) {
                supplier_select += '<option value="' + vendor.id + '">' + vendor.company_name +
                    '</option>';
            });
        } else {
            supplier_select += '<option value="">No suppliers found for this product</option>';
        }

        supplier_select += '</select>';

        // Cost field with GST info
        var cost_field =
            '<input type="number" class="form-control cost-field" name="vendor_cost" step="0.01" value="0.00" readonly>' +
            '<div><small>GST: <span name="purchase_gst_amount">0.00</span> (<span name="purchase_gst_percent">0%</span>)</small></div>';

        var newRow = $('<tr class="service_row">');
        var cols = '';

        cols += '<td><span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>' +
            '<input type="hidden" name="product_id" value="' + product.id + '">' +
            '<input type="hidden" name="warehouse_product_id" value="' + product.warehouse_products_id + '">' +
            '<input type="hidden" name="igst_rate" value="' + product.igst + '">' +
            '<input type="hidden" name="cgst_rate" value="' + product.cgst + '">' +
            '<input type="hidden" name="sgst_rate" value="' + product.sgst + '">' +
            '<input type="hidden" name="vendor_total_with_gst[]" value="0"></td>';

        cols += '<td>' +
            '<img src="<?= base_url('assets/product_images').'/'; ?>' + product.product_image + '" alt="' +
            product.name +
            '" style="width:40px; height:40px; object-fit:cover; margin-right:5px; border-radius:4px;">' +
            '<span name="product_name">' + product.name + '</span>' +
            '<span class="text-muted">(Code - ' + product.product_code + ')</span>' +
            '</td>';

        cols += '<td><textarea name="product_remark" rows="1" cols="20"></textarea></td>';
        cols += '<td>' + supplier_select + '</td>';
        cols += '<td>' + cost_field + '</td>';
        cols += '<td>' + input_quantity + '</td>';
        cols +=
            '<td><input type="number" class="form-control text-right" name="price" step="0.01" min="0.01" value="' +
            product.selling_price + '">' +
            '<input type="hidden" name="cost" value="' + product.cost + '"></td>';
        cols += '<td style="display:none">' + // Discount (hidden)
            '<select class="form-control select2bs4" name="item_discount" style="display:none">' +
            '<option value="">Select</option></select>' +
            '<input type="hidden" name="discount_type" value="0">' +
            '<input type="hidden" name="discount_value" value="0"></td>';
        cols += '<td>' + input_uom + '</td>';
        cols += '<td>' + taxable_value + '</td>';
        cols += '<td class="tax_td">' + tax + '</td>';
        cols += '<td style="display:none"><input type="hidden" name="tax_type" value="' + product.tax_type +
            '">' +
            (product.tax_type == 0 ? "No" : "Yes") + '</td>';
        cols += '<td><span name="sub_total"></span></td>';

        newRow.append(cols);
        $("table.product_table").append(newRow);
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        // Handle supplier change to update purchase cost
        newRow.find('.supplier-select').change(function() {
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
                    success: function(response) {
                        if (response.success) {
                            costField.val(response.cost);
                        } else {
                            costField.val('0.00');
                            alert(
                                'No purchase history found for this product from selected supplier'
                                );
                        }
                        calculateRow(newRow);
                        updateProfitTotals();
                    },
                    error: function() {
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


    function is_product_exist_in_row(product_id) {
        var isProductExist = false;
        $("#product_table_body").find('tr').each(function() {

            var tr = $(this).closest("tr");

            if (tr.find('input[name^="warehouse_product_id"]').val() == product_id) {
                isProductExist = true;
            }
        });

        return isProductExist;
    }

    function highlight_row(product_id) {
        $("#product_table_body").find('tr').each(function() {
            var tr = $(this).closest("tr");

            if (tr.find('input[name^="warehouse_product_id"]').val() == product_id) {
                tr.addClass('highlight_row');

                var existing_quantity = +tr.find('input[name^="quantity"]').val();
                // alert(existing_quantity + 1);
                // tr.find('input[name^="quantity"]').val(existing_quantity + 1).trigger('change');
                // tr.find('span[name^="quantity_update_message"]').text('+1');

                setTimeout(function() {
                    tr.removeClass('highlight_row');
                    tr.find('span[name^="quantity_update_message"]').text('');
                }, 3000);
            }
        });
    }

    $("table.product_table").on('change',
        'input[name^="price"], input[name^="quantity"], select[name^="item_discount"]',
        function(event) {

            if ($(this).attr('name') == 'item_discount') {
                var discount_id = $(this).val();
                var tr = $(this).closest('tr');

                if (discount_id != '') {
                    $.ajax({
                        url: "<?php echo base_url('discount/get_record_detail') ?>",
                        type: "POST",
                        dataType: "json",
                        data: {
                            'discount_id': discount_id,
                            '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                        },
                        success: function(data) {

                            var discount = data.discount;

                            tr.find('input[name^="discount_type"]').val(discount.type);
                            tr.find('input[name^="discount_value"]').val(discount.value);

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
            } else {
                calculateRow($(this).closest("tr"));
                calculateGrandTotal();
            }
        });

    $('table.product_table').on('click', "span.delete_item", function(e) {
        var tr = $(this).closest('tr');
        tr.remove();
        calculateGrandTotal();
    });

    //   function calculateRow(row)
    //   {
    //     var tax_type = row.find('input[name^="tax_type"]').val();
    //     var customer_country_id = $('#customer_country_id').val();
    //     var customer_state_id = $('#customer_state_id').val();
    //     var company_country_id = $('#company_country_id').val();
    //     var company_state_id = $('#company_state_id').val();

    //     // Initialize all tax values to 0
    //     var igst = 0, cgst = 0, sgst = 0;
    //     var igst_tax = 0, cgst_tax = 0, sgst_tax = 0;

    //     // Only try to get tax rates if the elements exist
    //     if (row.find('input[name^="igst_rate"]').length) {
    //         igst = parseFloat(row.find('input[name^="igst_rate"]').val()) || 0;
    //     }
    //     if (row.find('input[name^="cgst_rate"]').length) {
    //         cgst = parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0;
    //     }
    //     if (row.find('input[name^="sgst_rate"]').length) {
    //         sgst = parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0;
    //     }
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

    function calculateRow(row) {
        var tax_type = row.find('input[name^="tax_type"]').val();
        var customer_country_id = $('#customer_country_id').val();
        var customer_state_id = $('#customer_state_id').val();
        var company_country_id = $('#company_country_id').val();
        var company_state_id = $('#company_state_id').val();

        var quantity = parseFloat(row.find('input[name^="quantity"]').val()) || 0;
        var price = parseFloat(row.find('input[name^="price"]').val()) || 0;
        var cost = parseFloat(row.find('input[name="vendor_cost"]').val()) || 0;

        var taxable_value = quantity * price;
        var final_discount_value = 0;

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

        var igst_tax = 0,
            cgst_tax = 0,
            sgst_tax = 0;

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

        // Update totals
        updateProfitTotals();
    }

    function updateProfitTotals() {
        var totalPurchaseCost = 0;
        var totalSellingPrice = 0;
        var totalTaxableValue = 0;
        var totalTax = 0;
        var transportType = $('#additional_cost_type').val();
        var freightSubTotal = 0;

        $("#product_table_body").find('tr').each(function() {
            var row = $(this);
            var isFreight = row.find('input[name="freight_id"]').val() === 'freight';

            if (isFreight) {
                var freightAmount = parseFloat(row.find('input[name="freight_selling_price"]').val()) ||
                    0;
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
                var vendorWithGst = parseFloat(row.find('input[name="vendor_total_with_gst[]"]')
                    .val()) || 0;
                totalPurchaseCost += vendorWithGst;

                var subTotal = parseFloat(row.find('span[name="sub_total"]').text()) || 0;
                totalSellingPrice += subTotal;

                totalTaxableValue += parseFloat(row.find('span[name="taxable_value"]').text()) || 0;
                totalTax += (parseFloat(row.find('span[name="i_tax"]').text()) || 0) +
                    (parseFloat(row.find('span[name="c_tax"]').text()) || 0) +
                    (parseFloat(row.find('span[name="s_tax"]').text()) || 0);
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


    // Add freight row
    function addFreightRow(type, amount = 0) {
        // If freight row already exists, don't add it again
        if ($('.freight-row').length > 0) return;

        let taxRate = (type === 'external') ? 18 : 0;
        let taxableValue = amount;
        let taxAmount = 0;
        let subTotal = amount;

        if (type === 'external' && amount > 0) {
            taxAmount = (amount * taxRate) / 100;
            subTotal = amount + taxAmount; // Include GST in subtotal
        }

        let newRow = $('<tr class="service_row freight-row">');
        let cols = "";

        cols += '<td>' +
            '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>' +
            '<input type="hidden" name="freight_id" value="freight">' +
            '<input type="hidden" name="freight_type" value="' + type + '">' +
            '<input type="hidden" name="freight_tax_rate" value="' + taxRate + '">' +
            '</td>';

        cols += '<td><span name="freight_name">Freight Charges (' + type + ')</span></td>';
        cols += '<td><span name="freight_remark">Transport charges</span></td>';
        cols += '<td></td>'; // Vendor
        cols += '<td>0.00</td>'; // Purchase cost
        cols +=
            '<td><input type="number" class="form-control" name="freight_quantity" value="1" min="1" readonly></td>';

        cols += '<td>' +
            '<input type="number" class="form-control text-right freight-amount" name="freight_selling_price" min="1" value="' +
            amount.toFixed(2) + '" data-freight-type="' + type + '">' +
            '</td>';

        cols += '<td class="d-none"></td>'; // Discount
        cols += '<td>NA</td>'; // UOM
        cols += '<td><span name="freight_taxable_value">' + taxableValue.toFixed(2) + '</span></td>';

        if (type === 'external') {
            cols += '<td class="tax_td">IGST : <span name="freight_i_tax">' + taxAmount.toFixed(2) +
                '</span> (<span name="freight_igst">' + taxRate + '%</span>)</td>';
        } else {
            cols += '<td class="tax_td">N/A</td>';
        }

        cols += '<td><span name="freight_sub_total">' + subTotal.toFixed(2) + '</span></td>';

        newRow.append(cols);
        $("table.product_table tbody#product_table_body").append(newRow);
    }

    // Update freight values
    function updateFreightValues(type, amount) {
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
        row.find('input[name="freight_taxable_value"]').val(taxableValue.toFixed(2));

        row.find('input[name="freight_tax_rate"]').val(taxRate);
        row.find('input[name="freight_selling_price"]').val(amount.toFixed(2));

        if (type === 'external') {
            row.find('.tax_td').html('IGST : <span name="freight_i_tax">' + taxAmount.toFixed(2) +
                '</span> (18%)');
        } else {
            row.find('.tax_td').html('N/A');
        }

        row.find('span[name="freight_sub_total"]').text(subTotal.toFixed(2));

        updateProfitTotals();
    }


    $('#additional_cost_type').change(function() {
        let type = $(this).val();
        const amount = 0;
        if (!type) return;


        // Remove existing freight row if any
        $('.freight-row').remove();

        if (type) {
            addFreightRow(type, amount);
            updateProfitTotals();
        }


        // // Always add row if not exists
        // addFreightRow(type);

        // // If there's amount, update the values
        // let currentAmount = parseFloat($('#additional_cost_amount').val()) || 0;
        // updateFreightValues(type, currentAmount);
    });

    // Additional cost amount input handler
    $('#additional_cost_amount').on('input', function() {
        let amount = parseFloat($(this).val()) || 0;
        let type = $('#additional_cost_type').val();
        if (type) {
            updateFreightValues(type, amount);
        }
    });

    // Freight amount input handler
    $(document).on('input', '.freight-amount', function() {
        let newAmount = parseFloat($(this).val()) || 0;
        let type = $('#additional_cost_type').val();
        updateFreightValues(type, newAmount);
    });

    // Delete item handler
    $('table.product_table').on('click', "span.delete_item", function(e) {
        var tr = $(this).closest('tr');
        tr.remove();
        updateProfitTotals();
    });

    //   function calculateGrandTotal()
    //   {
    //     var total_taxable_value = 0.0;
    //     var total_cgst          = 0.0;
    //     var total_sgst          = 0.0;
    //     var total_igst          = 0.0;
    //     var total               = 0.0;
    //     var total_discount      = 0.0;

    //     $("#product_table_body").find('tr').each(function () {
    //         var tr              = $(this).closest("tr");
    //         total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text());
    //         total_discount      += parseFloat(tr.find('span[name^="discount_amount"]').text());
    //         total_cgst          += parseFloat(tr.find('span[name^="c_tax"]').text());
    //         total_sgst          += parseFloat(tr.find('span[name^="s_tax"]').text());
    //         total_igst          += parseFloat(tr.find('span[name^="i_tax"]').text());
    //         total               += parseFloat(tr.find('span[name^="sub_total"]').text()); 
    //     });

    //     $('#total_taxable_value').text(total_taxable_value.toFixed(2));
    //     $('#t_taxable_value').val(total_taxable_value.toFixed(2));

    //     $('#total_discount').text(total_discount.toFixed(2));
    //     $('#t_discount').val(total_discount.toFixed(2));

    //     $('#total_tax').text((total_cgst+total_sgst+total_igst).toFixed(2));
    //     $('#t_tax').val((total_cgst+total_sgst+total_igst).toFixed(2));

    //     $('#total').text(total.toFixed(2));
    //     $('#t').val(total.toFixed(2));
    //   }
    function calculateGrandTotal() {
        var total_taxable_value = 0.0;
        var total_cgst = 0.0;
        var total_sgst = 0.0;
        var total_igst = 0.0;
        var total = 0.0;
        var total_discount = 0.0;

        $("#product_table_body").find('tr').each(function() {
            var tr = $(this).closest("tr");

            // Use parseFloat with || 0 to handle NaN cases
            total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text()) || 0;
            total_discount += parseFloat(tr.find('span[name^="discount_amount"]').text()) || 0;
            total_cgst += parseFloat(tr.find('span[name^="c_tax"]').text()) || 0;
            total_sgst += parseFloat(tr.find('span[name^="s_tax"]').text()) || 0;
            total_igst += parseFloat(tr.find('span[name^="i_tax"]').text()) || 0;
            total += parseFloat(tr.find('span[name^="sub_total"]').text()) || 0;
        });

        // Update the totals - use toFixed(2) to ensure 2 decimal places
        $('#total_taxable_value').text(total_taxable_value.toFixed(2));
        $('#t_taxable_value').val(total_taxable_value.toFixed(2));

        $('#total_discount').text(total_discount.toFixed(2));
        $('#t_discount').val(total_discount.toFixed(2));

        $('#total_tax').text((total_cgst + total_sgst + total_igst).toFixed(2));
        $('#t_tax').val((total_cgst + total_sgst + total_igst).toFixed(2));

        $('#total').text(total.toFixed(2));
        $('#t').val(total.toFixed(2));
    }

    // Form submission handler
    $('#addQuotationForm').submit(function(e) {
        if ($(this).attr('name') === 'quantity' && parseFloat(value) <= 0) {
            $("form#addQuotationForm #err_" + id).text("Quantity must be greater than 0.").fadeIn(
                'slow');
            $(this).addClass('is-invalid');
            isError = true;
        }

        var isError = false;
        $('#quotationSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled',
            'disabled');

        // Validate required fields
        $('form#addQuotationForm .field_validation').each(function() {
            var id = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if (value == null || value == "") {
                $("form#addQuotationForm #err_" + id).text(field + " field is required.")
                    .fadeIn('slow');
                $('form#addQuotationForm #' + id).addClass('is-invalid');
                isError = true;
            } else {
                $("form#addQuotationForm #err_" + id).text("").fadeOut('slow');
                $('form#addQuotationForm #' + id).removeClass('is-invalid');
                $('form#addQuotationForm #' + id).addClass('is-valid');
            }
        });

        var profitResults = updateProfitTotals();
        var additionalCostType = $('#additional_cost_type').val();
        var additionalCostAmount = parseFloat($('#additional_cost_amount').val()) || 0;

        // Prepare product data
        var productDataArray = [];
        $("#product_table_body").find('tr').each(function() {
            var tr = $(this);
            if (tr.find('input[name="freight_id"]').val() === 'freight')
                return; // Skip freight row

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
                'quantity': parseFloat(tr.find('input[name="quantity"]').val()) || 0,
                'price': parseFloat(tr.find('input[name="price"]').val()) || 0,
                'sub_total': parseFloat(tr.find('span[name="sub_total"]').text()) || 0,
                'purchase_cost': parseFloat(tr.find('input[name="vendor_cost"]').val()) ||
                    0,
                'vendor_total_with_gst': parseFloat(tr.find(
                    'input[name="vendor_total_with_gst[]"]').val()) || 0,
                'supplier_id': tr.find('select[name="supplier_id"]').val() || '',
                'taxable_value': parseFloat(tr.find('span[name="taxable_value"]').text()) ||
                    0,
                'tax_id': tr.find('input[name="tax_id"]').val(),
                'tax_type': tr.find('input[name="tax_type"]').val() || 0,
                'igst_rate': parseFloat(tr.find('input[name="igst_rate"]').val()) || 0,
                'cgst_rate': parseFloat(tr.find('input[name="cgst_rate"]').val()) || 0,
                'sgst_rate': parseFloat(tr.find('input[name="sgst_rate"]').val()) || 0
            };

            if (apply_igst) {
                productData['igst_tax'] = parseFloat(tr.find('span[name="i_tax"]').text()) || 0;
                productData['igst'] = parseFloat(tr.find('input[name="igst_rate"]').val()) || 0;
                // Set CGST/SGST to 0 since they're not applicable
                productData['cgst_tax'] = 0;
                productData['sgst_tax'] = 0;
                productData['cgst'] = 0;
                productData['sgst'] = 0;
            } else if (apply_cgst_sgst) {
                productData['cgst_tax'] = parseFloat(tr.find('span[name="c_tax"]').text()) || 0;
                productData['sgst_tax'] = parseFloat(tr.find('span[name="s_tax"]').text()) || 0;
                productData['cgst'] = parseFloat(tr.find('input[name="cgst_rate"]').val()) || 0;
                productData['sgst'] = parseFloat(tr.find('input[name="sgst_rate"]').val()) || 0;
                // Set IGST to 0 since it's not applicable
                productData['igst_tax'] = 0;
                productData['igst'] = 0;
            } else {
                // No taxes applicable
                productData['igst_tax'] = 0;
                productData['cgst_tax'] = 0;
                productData['sgst_tax'] = 0;
                productData['igst'] = 0;
                productData['cgst'] = 0;
                productData['sgst'] = 0;
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
        $(".freight-row").each(function() {
            freightData = {
                'freight_selling_price': parseFloat($(this).find(
                    'input[name="freight_selling_price"]').val()) || 0,
                'freight_taxable_value': parseFloat($(this).find(
                    'span[name="freight_taxable_value"]').text()) || 0,
                'freight_sub_total': parseFloat($(this).find(
                    'span[name="freight_sub_total"]').text()) || 0,
                'freight_tax_rate': parseFloat($(this).find(
                    'input[name="freight_tax_rate"]').val()) || 0,
                'freight_tax_amount': parseFloat($(this).find('span[name="freight_i_tax"]')
                    .text()) || 0
            };
        });

        // Add hidden field for freight data if it doesn't exist
        if ($('#freight_data').length === 0) {
            $('<input>').attr({
                type: 'hidden',
                id: 'freight_data',
                name: 'freight_data'
            }).appendTo('#addQuotationForm');
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
            }).appendTo('#addQuotationForm');
        }
        $('#profit_data').val(JSON.stringify(profitData));

        // Check if there are any items
        if (productDataArray.length > 0) {
            $('#quotation_items').val(productDataArray.join('|'));
        } else {
            isError = true;
            $('#emptyQuotationItemWarningModal').modal('show');
        }

        if (isError) {
            $('#quotationSubmit').text('<?=$this->lang->line("quotation_add")?>').removeAttr(
                'disabled');
            return false;
        } else {
            return true;
        }
    });

    $("form#addQuotationForm .field_validation").on("blur keyup change", function(event) {
        var id = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if (value == null || value == "") {
            $("form#addQuotationForm #err_" + id).text(field + " field is required.").fadeIn('slow');
            $('form#addQuotationForm #' + id).addClass('is-invalid');
            return false;
        } else {
            $("form#addQuotationForm #err_" + id).text("").fadeOut('slow');
            $('form#addQuotationForm #' + id).removeClass('is-invalid');
            $('form#addQuotationForm #' + id).addClass('is-valid');
        }
    });

    $('.rcm_btn').click(function(e) {
        e.preventDefault();

        if ($('#product_table_body tr').length > 0) {
            $('.rcm-confirmation-body').html(
                '<?=$this->lang->line('quotation_rcm_confirmation_message')?>');

            $('#rcm-confirmation').modal({
                backdrop: 'static',
                keyboard: false
            }).on('click', '#rcm-confirmation-confirm', function(e) {

                var rcm = $('#rcm').val();

                if (rcm == 'N') {
                    $('#rcm').val('Y');
                    $('.rcm_btn').text('<?=$this->lang->line('quotation_disable_rcm')?>');
                } else {
                    $('#rcm').val('N');
                    $('.rcm_btn').text('<?=$this->lang->line('quotation_enable_rcm')?>');
                }

                // Clear the product and calculate the grand total again              
                $("#product_table_body tr").remove();
                calculateGrandTotal();

                $('#rcm-confirmation').modal('hide');
            });
        } else {
            var rcm = $('#rcm').val();
            if (rcm == 'N') {
                $('#rcm').val('Y');
                $('.rcm_btn').text('<?=$this->lang->line('quotation_disable_rcm')?>');
            } else {
                $('#rcm').val('N');
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

    $('form#addCustomerForm').submit(function(e) {
        e.preventDefault();

        var isError = false;
        $('form#addCustomerForm #customerSubmit').text('<?=$this->lang->line("please_wait")?>').attr(
            'disabled', 'disabled');

        $('form#addCustomerForm .field_validation').each(function() {

            var id = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if (value == null || value == "") {
                $("form#addCustomerForm #err_" + id).text(field + " field is required.");
                if ($('form#addCustomerForm #' + id).hasClass('is-valid')) {
                    $('form#addCustomerForm #' + id).removeClass('is-valid');
                }
                $('form#addCustomerForm #' + id).addClass('is-invalid');
                isError = true;
            } else {
                $("form#addCustomerForm #err_" + id).text("");
                $('form#addCustomerForm #' + id).removeClass('is-invalid');
                $('form#addCustomerForm #' + id).addClass('is-valid');
            }
        });


        if (isError == true) {
            $('form#addCustomerForm #customerSubmit').text('<?=$this->lang->line("submit")?>')
                .removeAttr('disabled');
            return false;
        } else {
            var formData = $('#addCustomerForm').serialize();

            $.ajax({
                url: '<?php echo base_url("customer/add") ?>',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(response) {

                    var customer = response.customer;

                    if (response.code == 1) {
                        $('#add_customer_modal').modal('hide');
                        $('form#addCustomerForm #customerSubmit').text(
                            '<?=$this->lang->line("submit")?>').removeAttr('disabled');

                        if ($('#customer_id').length) {
                            $('#customer_id').html('');
                            $('#customer_id').append('<option value="">Select</option>');

                            for (i = 0; i < response['customers'].length; i++) {
                                $('#customer_id').append('<option value="' + response[
                                    'customers'][i].id + '">' + response[
                                    'customers'][i].customer_name + '-' + response[
                                    'customers'][i].customer_company_name + '</option>');
                            }

                            $('#customer_id').val(response['id']).attr("selected",
                                "selected");

                            if ($("#warehouse_id").length) {
                                // display shipping fields
                                $('.shipping_detail').css('display', 'block');

                                if ($("#warehouse_id").val() != '') {
                                    $("#customer_id").closest('.row').siblings().fadeIn(10);
                                }
                                $('form#addCustomerForm #customer_state_id').val(customer
                                    .state_id);
                                $('form#addCustomerForm #customer_country_id').val(customer
                                    .country_id);

                                $('input[name="shipping_country_id"]').val(customer
                                    .shipping_country_id).trigger('change');

                                // $("#product_table_body tr").remove();
                                // calculateGrandTotal();
                                refresh_tax_td();
                            }

                            // show_message('success-header',response.message);
                            CustomerToast.fire({
                                type: 'success',
                                title: response.message
                            });
                        } else {
                            // show_message('success-header',response.message);
                            CustomerToast.fire({
                                type: 'success',
                                title: response.message
                            });

                            location.reload(true);
                        }
                    } else {
                        $('form#addCustomerForm #customerSubmit').text(
                            '<?=$this->lang->line("submit")?>').removeAttr('disabled');

                        // show_message('failure-header',response.message);
                        CustomerToast.fire({
                            type: 'error',
                            title: response.message
                        });
                    }
                },
                error: function() {
                    $('form#addCustomerForm #customerSubmit').text(
                        '<?=$this->lang->line("submit")?>').removeAttr('disabled');

                    show_message('failure-header', 'Added Successfully.');
                }
            });
        }
    });
    /*$("form#addCustomerForm .field_validation").on("blur change keyup", function(event) {
        var id = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if (value == null || value == "") {
            $("form#addCustomerForm #err_" + id).text(field + " field is required.");
            if ($('form#addCustomerForm #' + id).hasClass('is-valid')) {
                $('form#addCustomerForm #' + id).removeClass('is-valid');
            }
            $('form#addCustomerForm #' + id).addClass('is-invalid');
            return false;
        } else {
            $("form#addCustomerForm #err_" + id).text("");
            $('form#addCustomerForm #' + id).removeClass('is-invalid');
            $('form#addCustomerForm #' + id).addClass('is-valid');
        }
    });*/
    $(document).on('hidden.bs.modal', '#add_customer_modal', function() {
        $('form#addCustomerForm .field_validation').each(function() {
            var id = $(this).attr('id');
            $("form#addCustomerForm #" + id).val("");
            $("form#addCustomerForm #err_" + id).text("");
            $('form#addCustomerForm #' + id).removeClass('is-invalid');
            $('form#addCustomerForm #' + id).removeClass('is-valid');
        });
    });
    $('#add_customer_modal').on('shown.bs.modal', function() {

        $("form#addCustomerForm #customer_name ").focus();

        var company_country_id =
            '<?=$this->company_settings_model->get_company_records()->country_id?>';

        $('form#addCustomerForm #country_id').html('<option value="">Select</option>');

        $.ajax({
            url: "<?php echo base_url('utility/get_countries') ?>/",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $('form#addCustomerForm #country_id').append('<option value="' + data[i]
                        .id + '">' + data[i].name + '</option>');
                }

                $('form#addCustomerForm #country_id').val(company_country_id).trigger(
                    'change');
            }
        });

        $('form#addCustomerForm #shipping_country_id').html('<option value="">Select</option>');

        $.ajax({
            url: "<?php echo base_url('utility/get_countries') ?>/",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $('form#addCustomerForm #shipping_country_id').append(
                        '<option value="' + data[i].id + '">' + data[i].name +
                        '</option>');
                }

                $('form#addCustomerForm #shipping_country_id').val(company_country_id)
                    .trigger('change');
            }
        });
    });

    $('form#addCustomerForm #country_id').change(function() {

        var id = $(this).val();

        var company_state_id = '<?=$this->company_settings_model->get_company_records()->state_id?>';
        // alert(id);
        $('form#addCustomerForm #state_id').html('<option value="">Select</option>');
        $('form#addCustomerForm #city_id').html('<option value="">Select</option>');
        $.ajax({
            url: "<?php echo base_url('utility/get_states') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $('form#addCustomerForm #state_id').append('<option value="' + data[i]
                        .id + '">' + data[i].name + '</option>');
                }

                $('form#addCustomerForm #state_id').val(company_state_id).trigger('change');
            }
        });
    });

    $('form#addCustomerForm #state_id').change(function() {
        var id = $(this).val();

        $('form#addCustomerForm #city_id').html('<option value="">Select</option>');
        $.ajax({
            url: "<?php echo base_url('utility/get_cities') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $('form#addCustomerForm #city_id').append('<option value="' + data[i]
                        .id + '">' + data[i].name + '</option>');
                }
            }
        });
    });

    $('form#addCustomerForm #shipping_country_id').change(function() {

        var id = $(this).val();

        var company_state_id = '<?=$this->company_settings_model->get_company_records()->state_id?>';
        // alert(id);
        $('form#addCustomerForm #shipping_state_id').html('<option value="">Select</option>');
        $('form#addCustomerForm #shipping_city_id').html('<option value="">Select</option>');

        $.ajax({
            url: "<?php echo base_url('utility/get_states') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $('form#addCustomerForm #shipping_state_id').append('<option value="' +
                        data[i].id + '">' + data[i].name + '</option>');
                }

                $('form#addCustomerForm #shipping_state_id').val(company_state_id).trigger(
                    'change');
            }
        });
    });

    $('form#addCustomerForm #shipping_state_id').change(function() {
        var id = $(this).val();

        $('form#addCustomerForm #shipping_city_id').html('<option value="">Select</option>');

        $.ajax({
            url: "<?php echo base_url('utility/get_cities') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $('form#addCustomerForm #shipping_city_id').append('<option value="' +
                        data[i].id + '">' + data[i].name + '</option>');
                }
            }
        });
    });

    $('form#addCustomerForm #copy_address').click(function() {

        if ($('input[name="copy_address"]').is(':checked')) {

            var country = $('form#addCustomerForm #country_id option:selected').val();
            if (country != "") {
                $('form#addCustomerForm #shipping_country_id').val(country).trigger('change');
            }

            setTimeout(function() {
                var state = $('form#addCustomerForm #state_id').val();
                if (state != "") {
                    $('form#addCustomerForm #shipping_state_id').val(state).trigger('change');
                }
            }, 500);

            setTimeout(function() {
                var city = $('form#addCustomerForm #city_id').val();
                // alert(city);
                if (city != "") {
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