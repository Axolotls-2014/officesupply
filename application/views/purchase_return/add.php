<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

  $this->load->view('layout/header');
  $currency = $this->company_settings_model->get_company_records()->currency_symbol;

  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);

  $mfg_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);

  $promotion = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'promotion', 'active',$row = true,$check_delete_status = false);
   
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

/* Sticky Header for Better UX */
.product_table thead th {
    position: sticky;
    top: 0;
    z-index: 1;
}

/* Responsive Column Sizes */
.product_table th,
.product_table td {
    min-width: 50px;
    /* Ensure a minimum width for readability */
    max-width: 300px;
    /* Prevent excessively wide columns */
    word-wrap: break-word;
    /* Prevent text overflow */
}

/* Scrollbars for Smaller Screens */
.table-responsive {
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    /* Smooth scrolling for mobile */
}

/* Media Query for Smaller Screens */
@media (max-width: 1024px) {

    .product_table th,
    .product_table td {
        font-size: 12px;
        /* Adjust font size for laptops */
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
                       <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><a href="https://ossdemo.tastytap.in/product_core"><?=$this->lang->line('header_inventory')?></a></li>
         
                        <li class="breadcrumb-item "><a
                                href="<?=base_url('purchase_return')?>"><?=$this->lang->line('purchase_return_header')?></a>
                        </li>
                        <li class="breadcrumb-item active"><?=$this->lang->line('purchase_return_add')?></li>
                    </ol>
                </div>
            </div>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <form class="form-horizontal" name="addPurchasereturnForm" id="addPurchasereturnForm" method="POST"
                        action="<?=base_url('purchase_return/add')?>">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><?=$this->lang->line('purchase_return_add')?></h3>
                                <div class="card-tools">

                                    <ul class="nav nav-pills ml-auto">
                                        <li class="nav-item  ml-2">
                                            <a class="nav-link reset btn-sm btn-warning" href="#" data-tt="tooltip"
                                                title="Click here to Reset Purchase return Items">
                                                <i class="fas fa-redo-alt"></i> Reset
                                            </a>
                                        </li>



                                        <li class="nav-item ml-2">
                                            <a class="nav-link active" href="<?=base_url('purchase_return')?>"
                                                data-tt="tooltip" title="Click here to show purchase return list"><i
                                                    class="fas fa-arrow-left mr-2"></i>Back</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-tools d-none">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-tool btn-warning rcm_btn" data-tt="tooltip"
                                            title="<?=$this->lang->line('purchase_return_rcm_change_status')?>">
                                            <?=$this->lang->line('purchase_return_enable_rcm')?>
                                        </button>
                                        <button type="button" class="btn btn-tool btn-warning" data-tt="tooltip"
                                            title="<?=$this->lang->line('purchase_return_rcm_help')?>">
                                            <i class="far fa-question-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <!--<div class="col-sm-2">-->
                                        <!--  <div class="form-group">-->
                                        <!--    <label><?=$this->lang->line('purchase_return_reference_no')?></label>-->
                                        <input type="hidden" class="form-control" id="reference_no" name="reference_no"
                                            disabled="">
                                        <!--  </div>-->
                                        <!--</div>-->
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label><?=$this->lang->line('purchase_return_date')?></label>
                                                <input type="text" class="form-control datepicker"
                                                    id="purchase_return_date" name="purchase_return_date"
                                                    value="<?=date('d-m-Y');?>">
                                                <span id="err_purchase_return_date"
                                                    class="error invalid-feedback"><?=form_error('purchase_return_date');?></span>
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <label><?=$this->lang->line('purchase_return_invoice_no')?></label>
                                                <!--<select class="form-control field_validation select2bs4" id="invoice_no" name="invoice_no" placeholder="<?=$this->lang->line('purchase_return_invoice_no')?>">
                            <option>Select Purchase Order</option>
                            <?php
                              foreach ($purchases as $p)
                              {
                                $supp = $this->supplier_model->get_single_record($p->supplier_id);
                                $delivered_quantity = $this->purchase_delivery_model->get_total_no_of_quantity_delivered($p->id);
                                if($delivered_quantity > 0)
                                {
                            ?>
                               <option value="<?=$p->id?>"
                                  data-supplier_id = "<?=$p->supplier_id?>"
                                  data-warehouse_id = "<?=$p->warehouse_id?>"
                                >
                                  <?=$p->reference_no.' - '.$supp->company_name?>
                                </option>
                            <?php
                                }
                              }
                            ?>
                          </select>-->

                                                
<select class="form-control field_validation select2bs4" id="invoice_no" name="invoice_no" placeholder="<?=$this->lang->line('purchase_return_invoice_no')?>">
    <option value="">Select Purchase Invoice</option>
    <?php
      foreach ($purchases as $p)
      {
        // Only consider purchases that have an invoice number
        if(!empty($p->invoice_no))
        {
            $supp = $this->supplier_model->get_single_record($p->supplier_id);
            
            // Get ordered and delivered quantities
            $ordered_quantity = $this->purchase_delivery_model->get_total_no_of_quantity_ordered($p->id);
            $delivered_quantity = $this->purchase_delivery_model->get_total_no_of_quantity_delivered($p->id);
            
            // ONLY SHOW PURCHASES THAT ARE FULLY DELIVERED
            if($delivered_quantity == $ordered_quantity && $ordered_quantity > 0)
            {
                $warehouse = $this->warehouse_model->get_single_record($p->warehouse_id);
                $warehouse_name = $warehouse ? $warehouse->name : '';
    ?>
       <option value="<?=$p->invoice_no?>"
          data-supplier_id="<?=$p->supplier_id?>"
          data-warehouse_id="<?=$p->warehouse_id?>"
          data-warehouse_name="<?=$warehouse_name?>"
          data-purchase_id="<?=$p->id?>"
          data-invoice_no="<?=$p->invoice_no?>">
          <?=htmlspecialchars($p->invoice_no . ' - ' . $supp->company_name)?>
        </option>
    <?php
            }
        }
      }
    ?>
</select>      
    <div id="purchase_details_link" style="display: none; margin-top: 5px;">
      <a href="#" id="show_purchase_details" data-tt="tooltip" title="Click here to show details of Purchase">
        <i class="fas fa-info-circle"></i> Click here to show details of Purchase
      </a>
    </div>

<span id="err_invoice_no"
                                                    class="error invalid-feedback"><?=form_error('invoice_no');?></span>
                                                <span id="reference_invoice_no" class="text-danger"></span>
                                            </div>
                                        </div>

                                      
<div class="col-sm-3">
    <div class="form-group">
        <label for="warehouse">
            <?=$this->lang->line('purchase_return_warehouse')?>
            <span class="text-danger">*</span>
        </label>

        <div class="input-group input-group-sm">
            <?php
            $user_id = $this->session->userdata('user_id');
            $user = $this->db->get_where('users', ['id' => $user_id])->row();

            if ($user && isset($user->branch_id) && $user->branch_id) {
                $user_branch_id = $user->branch_id;
                $warehouse_name = '';

                foreach ($warehouses as $value) {
                    if ($value->id == $user_branch_id) {
                        $warehouse_name = $value->name;
                        break;
                    }
                }
                ?>
                <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$user_branch_id;?>">
                <input type="text" class="form-control form-control-sm" id="warehouse_name_display" value="<?=$warehouse_name;?>" readonly>
            <?php } else { ?>
                <input type="hidden" name="warehouse_id" id="warehouse_id" value="">
                <input type="text" class="form-control form-control-sm" id="warehouse_name_display" placeholder="" readonly>
               
                <?php if ($this->permission_model->has_permission('add_warehouse')): ?>
                    <span class="input-group-append">
                        <button type="button" class="btn btn-info btn-flat add_warehouse_modal" data-toggle="modal" data-target="#add_warehouse_modal" data-tt="tooltip" accesskey="c" style="display:none;">
                            <i class="fas fa-plus"></i>
                        </button>
                    </span>
                <?php endif; ?>
            <?php } ?>
        </div>
       
        <span id="err_warehouse_id" class="error invalid-feedback"><?=form_error('warehouse_id');?></span>
    </div>
</div>
<div class="col-sm-3">
    <div class="form-group">
        <label for="supplier">
            <?=$this->lang->line('purchase_return_supplier')?>
            <span class="text-danger">*</span>
        </label>
        <div class="input-group input-group-sm">
            <input type="hidden" name="supplier_id" id="supplier_id" value="">
            <input type="text" class="form-control form-control-sm" id="supplier_name_display" placeholder="" readonly>
            <span class="input-group-append">
                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_supplier_modal" data-tt="tooltip" accesskey="c" style="display:none;"><i class="fas fa-plus"></i></button>
            </span>
        </div>
        <input type="hidden" name="supplier_gstin" id="supplier_gstin" value="">
        <input type="hidden" name="rcm" id="rcm" value="N">
        <input type="hidden" name="supplier_state_id" id="supplier_state_id" value="">
        <input type="hidden" name="supplier_country_id" id="supplier_country_id" value="">
        <span id="err_supplier_id" class="error invalid-feedback"><?=form_error('supplier_id');?></span>
    </div>
</div>
                                    </div>

                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group">
                                                <label><?=$this->lang->line('cdn_document')?></label>
                                                <div class="input-group input-group-sm">
                                                    <div class="custom-file">
                                                        <input type="file" class="custom-file-input"
                                                            id="upload_document" name="upload_document[]" accept=".pdf"
                                                            multiple>
                                                        <label class="custom-file-label" for="exampleInputFile">Choose
                                                            file</label>
                                                    </div>

                                                    <input type="hidden" value="" name="document[]" id="document">
                                                </div>
                                                <span id="fileTypeError" class="error-message"></span>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-sm-12 ">
                                            <div class="well">
                                                <div class="row" style="display: none;">
                                                    <div class="col-sm-12">
                                                        <a href="" data-toggle="modal" data-tt="tooltip"
                                                            data-target="#add_product_modal"
                                                            class="float-right"><?=$this->lang->line('purchase_return_add_new_product')?></a>
                                                    </div>
                                                    <div class="col-sm-12 search">
                                                        <span class="fa fa-search"></span>
                                                        <input id="search_product" class="form-control search_product"
                                                            type="text" name="search_product"
                                                            placeholder="<?=$this->lang->line('purchase_return_item_search')?>">
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
                                                <label><?=$this->lang->line('purchase_return_items')?></label>
                                                <div class="table-responsive"
                                                    style="max-width: 100%; overflow-x: auto;">
                                                    <table
                                                        class="table items table-striped table-bordered table-condensed table-hover product_table">
                                                        <thead>
                                                            <tr>
                                                                <th width="5%">
                                                                    <img
                                                                        src="<?php  echo base_url(); ?>assets/images/bin1.png" />
                                                                </th>
                                                                <th class="span2" width="100px">
                                                                    <?=$this->lang->line('product_description')?></th>
                                                                <!--<th class="span2" width="100px"><?=$this->lang->line('product_batch_no')?></th>-->
                                                                <th class="span2" width="100px">
                                                                    <?=$this->lang->line('purchase_return_qty')?></th>
                                                                <!--<th class="span2 <?= (empty($promotion) || $promotion->field_status !== 'active') ? 'd-none' : '' ?>" width="100px">-->
                                                                <!--<?=$this->lang->line('proforma_invoice_free_qty')?>-->
                                                                <!--</th>-->
                                                                <th class="span2" width="100px">
                                                                    <?=$this->lang->line('purchase_return_cost')?></th>

                                                                <!--<th class="span2 <?= (empty($mfg_date) || $mfg_date->field_status !== 'active') ? 'd-none' : '' ?>" width="100px">-->
                                                                <!--  <?= $this->lang->line('product_mfg_date') ?>-->
                                                                <!--</th>-->
                                                                <!--<th class="span2 <?= (empty($expiry_date) || $expiry_date->field_status !== 'active') ? 'd-none' : '' ?>" width="100px">-->
                                                                <!--  <?= $this->lang->line('product_expiry_date') ?>-->
                                                                <!--</th>-->

                                                                <!--<th class="span2" width="100px"><?=$this->lang->line('purchase_return_discount')?></th>-->
                                                                <th class="span2" width="100px">
                                                                    <?=$this->lang->line('purchase_return_uom')?></th>
                                                                <th class="span2" width="100px">
                                                                    <?=$this->lang->line('purchase_return_taxable_value')?>
                                                                </th>
                                                                <th class="span2" width="100px">
                                                                    <?=$this->lang->line('purchase_return_tax')?></th>
                                                                <!--<th class="span2" width="100px"><?=$this->lang->line('purchase_return_inclusive')?></th>-->
                                                                <th class="span2" width="100px">
                                                                    <?=$this->lang->line('purchase_return_total')?></th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="product_table_body">
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <table
                                                    class="table table-striped table-bordered table-condensed table-hover total_data">
                                                    <tr>
                                                        <td align="right" colspan="8">
                                                            <?=$this->lang->line('purchase_return_total_discount')?>(<?=$currency?>)
                                                        </td>
                                                        <td align='right' class="text-danger">
                                                            -<span id="total_discount">0.00</span>
                                                            <input type="hidden" name="total_discount" id="t_discount"
                                                                value="0">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right" colspan="8">
                                                            <?=$this->lang->line('purchase_return_total_taxable_value')?>(<?=$currency?>)
                                                        </td>
                                                        <td align='right' class="text-success">
                                                            +<span id="total_taxable_value">0.00</span>
                                                            <input type="hidden" name="total_taxable_value"
                                                                id="t_taxable_value" value="0">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right" colspan="8">
                                                            <?=$this->lang->line('purchase_return_total_tax')?>(<?=$currency?>)
                                                        </td>
                                                        <td align='right' class="text-success">
                                                            +<span id="total_tax">0.00</span>
                                                            <input type="hidden" name="total_tax" id="t_tax" value="0">
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="right" colspan="8">
                                                            <?=$this->lang->line('purchase_return_total')?>(<?=$currency?>)
                                                        </td>
                                                        <td align='right'>
                                                            <span id="total">0.00</span>
                                                            <input type="hidden" name="total" id="t" value="0">
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
                                                                    data-toggle="pill"><?php echo $this->lang->line('purchase_return_external_note'); ?></a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="#internal_note"
                                                                    data-toggle="pill"><?php echo $this->lang->line('purchase_return_internal_note'); ?></a>
                                                            </li>
                                                            <li class="nav-item">
                                                                <a class="nav-link" href="#terms_and_condition"
                                                                    data-toggle="pill"><?php echo $this->lang->line('purchase_return_terms_and_condition'); ?></a>
                                                            </li>
                                                        </ul>
                                                        <br>
                                                        <div class="tab-content">
                                                            <div class="tab-pane active" id="external_note">
                                                                <textarea class="col-sm-12 form-control"
                                                                    name="external_note" rows="4"
                                                                    placeholder="<?php echo $this->lang->line('purchase_return_external_note'); ?>"></textarea>
                                                            </div>
                                                            <div class="tab-pane" id="internal_note">
                                                                <textarea class="col-sm-12 form-control"
                                                                    name="internal_note" rows="4"
                                                                    placeholder="<?php echo $this->lang->line('purchase_return_internal_note'); ?>"></textarea>
                                                            </div>
                                                            <div class="tab-pane" id="terms_and_condition">
                                                                <textarea class="col-sm-12 form-control"
                                                                    name="terms_and_condition" rows="4"
                                                                    placeholder="<?php echo $this->lang->line('purchase_return_terms_and_condition');?>"><?=str_replace("<br />","",$company_setting->terms_and_condition)?></textarea>
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
                                <input type="hidden" name="purchase_return_items" id="purchase_return_items" value="">
                                <input type="hidden" name="company_state_id" id="company_state_id"
                                    value="<?=$company_setting->state_id?>">
                                <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                                    value="<?php echo $this->security->get_csrf_hash(); ?>">
                                <input type="hidden" name="company_country_id" id="company_country_id"
                                    value="<?=$company_setting->country_id?>">
                                <button type="submit" name="submit" id="purchasereturnSubmit"
                                    class="btn btn-info"><?=$this->lang->line('purchase_return_add')?></button>
                                <!-- <button type="submit" name="submit" id="purchasereturnSubmitPayNow" value="pay" name="pay" class="btn btn-info">Add purchase_return & Pay Now</button>                      -->
                                <span class="btn btn-default float-right" id="cancel"
                                    onclick="window.history.back()"><?=$this->lang->line('purchase_return_cancel')?></span>
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
  // $this->load->view('warehouse/add_warehouse_modal');
 
  // $this->load->view('service/add_service_modal');
?>

<div class="modal fade" id="emptypurchase_returnItemWarningModal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header warning-header">
                <h5 class="modal-title" id="exampleModalLabel"><?=$this->lang->line('purchase_return_message_label')?>
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <?=$this->lang->line('empty_purchase_return_warning_label')?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="view_purchase_detail_modal">
    <div class="modal-dialog modal-lg">
        <form name="purchaseItemsReturnForm" id="purchaseItemsReturnForm" method="POST">
            <div class="modal-content">
                <div class="modal-header info-header">
                    <h5 class="modal-title">purchase Products</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="view_purchase_items">

                </div>
                <div class="modal-footer">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>"
                        value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" class="btn btn-primary" name="purchaseItemsReturnSubmit"
                        id="purchaseItemsReturnSubmit" value="submit">Add to Purchase Return</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
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
                        <?php echo "Are you sure want to reset this purchase return ?";?>
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

    $(document).on('click', "#resetSubmit", function(e) {
        e.preventDefault();

        $('span.delete_item').trigger('click');

        $('#supplier_id').val('');

        $('#supplier_id').trigger('change');
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
                        if ($('form#addPurchasereturnForm #warehouse_id').length) {
                            $('form#addPurchasereturnForm #warehouse_id').html('');
                            $('form#addPurchasereturnForm #warehouse_id').append(
                                '<option value="">Select</option>');

                            for (i = 0; i < response['warehouses'].length; i++) {
                                $('form#addPurchasereturnForm #warehouse_id').append(
                                    '<option value="' + response['warehouses'][i].id +
                                    '">' + response['warehouses'][i].name + '</option>');
                            }

                            $('form#addPurchasereturnForm #warehouse_id').val(response[
                                'id']).attr("selected", "selected");


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

    $("#supplier_id").closest('.row').siblings().css('display', 'none');

    $('#warehouse_id').change(function(e) {

        var warehouse_id = $(this).val();
        if (warehouse_id != '') {
            if ($('#supplier_id').val() != '') {
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
                    $("#product_table_body tr").remove();
                    calculateGrandTotal();

                }
            });
        }
    });

    $('#supplier_id').change(function(e) {

        var supplier_id = $(this).val();
        if (supplier_id != '') {
            if ($('#warehouse_id').val() != '') {
                $("#supplier_id").closest('.row').siblings().fadeIn(10);
            }

            $.ajax({
                url: "<?php echo base_url('supplier/get_record_detail') ?>",
                type: "POST",
                dataType: "json",
                data: {
                    'supplier_id': supplier_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(data) {
                    var supplier = data.supplier;
                    $('#supplier_state_id').val(supplier.state_id);
                    $('#supplier_country_id').val(supplier.country_id);
                    $('#supplier_gstin').val(supplier.gstin);

                    $("#product_table_body tr").remove();
                    calculateGrandTotal();

                }
            });
        }
    });

    // Service search code begin

    var c_mapping = {};

    $(function() {
        $('#search_product').autoComplete({
            minChars: 1,
            cache: 0,
            source: function(term, suggest) {
                term = term.toLowerCase();

                $.ajax({
                    url: "<?php echo base_url('product/search') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        'term': term,
                        'module': '<?=PURCHASE_RETURN_MODULE?>',
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    success: function(data) {
                        var products = data;
                        var suggestions = [];
                        for (var i = 0; i < products.length; ++i) {
                            suggestions.push(products[i].id + ' - ' + products[
                                    i].name + ' - ' + products[i]
                                .product_category_name);
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
                    data: {
                        'product_id': product_id,
                        'module': '<?=PURCHASE_RETURN_MODULE?>',
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    success: function(data) {
                        var product = data.product;

                        if (!is_product_exist_in_row(product.id)) {
                            add_row(data, batch_no);
                        } else {
                            highlight_row(product.id);
                        }

                        calculateGrandTotal();
                        $('#search_product').val('').focus();
                    }
                });
            }
        });
    });

    /*function add_row(data, return_quantity = null, free_quantity = null, purchased_quantity = null,
        delivered_quantity = null, product_name = null, description = null, cost = null, price = null,
        batch_no = null, selling_price = null) {

        var supplier_country_id = $('#supplier_country_id').val();
        var supplier_state_id = $('#supplier_state_id').val();

        var company_country_id = $('#company_country_id').val();
        var company_state_id = $('#company_state_id').val();
        var company_gstin = '<?=$company_setting->gstin?>';

        var product = data.product;
        var discounts = data.discount;

        var select_discount = "";
        select_discount += '<select class="form-control select2bs4" name="item_discount" style="width: 100%;">';
        select_discount += '<option value="">Select</option>';
        for (a = 0; a < discounts.length; a++) {
            var type_symbol;
            if (discounts[a].type == 0) {
                type_symbol = "<?=$this->session->userdata('currency_symbol')?>";
            } else {
                type_symbol = "%";
            }
            select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name + '(' + discounts[
                a].value + type_symbol + ')' + '</option>';
        }
        select_discount += '</select>'
        select_discount +=
            '<span name="span_discount_type" class="discount_type">Discount Value :  <?=$this->session->userdata('currency_symbol');?> </span><input type="hidden" name="discount_type" value="0">';
        select_discount +=
            '<span name="discount_amount" class="discount_amount">0.0</span><input type="hidden" name="discount_value" value="' +
            0 + '">';

        var input_uom = '<input type="hidden" name="uom_id" value="' + product.uom_id + '" data-uom_name="' +
            product.uom_name + '" data-uom_uom="' + product.uom_uom + '">' + product.uom_uom;

        var input_quantity = '<input type="number" class="form-control" name="quantity" value="' +
            return_quantity + '" step="1" max="' + (purchased_quantity - delivered_quantity) +
            '"><span name="quantity_update_message" class="quantity_update_message"></span>';

        var taxable_value = '<span name="taxable_value">' + cost + '</span>';

        var tax = '<input type="hidden" name="tax_id" value="' + product.tax_id + '">';

        // alert(company_state_id);
        // alert(supplier_state_id);

        if (company_country_id == supplier_country_id) {
            if (company_state_id == supplier_state_id) {
                tax += 'CGST : <span name="c_tax">' + 0.0 + '</span>(<span name="cgst">' + product.cgst +
                    '</span>)<br/>';
                tax += 'SGST : <span name="s_tax">' + 0.0 + '</span>(<span name="sgst">' + product.sgst +
                    '</span>)';
                tax +=
                    '<span name="i_tax"style="display:none">0</span><span name="igst" style="display:none">0</span>';
            } else {
                tax += 'IGST : <span name="i_tax">' + 0.0 + '</span>(<span name="igst">' + product.igst +
                    '</span>)<br/>';
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


      

        var tax_type = '<input type="hidden" name="tax_type" value="' + product.tax_type + '">';
        tax_type += (product.tax_type == 0) ? "No" : "Yes";


        var free_quantity = '<input type="number" class="form-control" name="free_quantity" value="' +
            free_quantity + '" max="' + free_quantity + '"  min="0" step="any">';


        var newRow = $('<tr class="product_row">');
        var cols = "";

        cols += '<td>' +
            '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>' +
            '<input type="hidden" name="product_id" value="' + product.id + '">' +
            '<input type="hidden" name="igst_rate" value="' + product.igst + '">' +
            '<input type="hidden" name="cgst_rate" value="' + product.cgst + '">' +
            '<input type="hidden" name="sgst_rate" value="' + product.sgst + '">' +
            '</td>';
        cols += '<td><span name="product_name">' + product.name + '</span></td>';
        // cols += '<td><input type="text" name="batch_no" value="'+batch_no+'" class="form-control " required></td>';
        cols += '<td>' + input_quantity + '</td>';
        // cols += '<td class="<?php echo empty($promotion) ? 'd-none' : ''; ?>">'+free_quantity+'</td>';
        cols += '<td>' +
            '<span id="cost_span">' +
            '<input type="number" class="form-control text-right" name="cost" step="0.01" value="' + cost +
            '" min="0.01">' +
            '<input type="hidden" name="selling_price" value="' + selling_price + '">' +
            '<input type="hidden" name="price" value="' + price + '">' +
            '</span>' +
            '</td>';

        // cols += '<td class="<?php echo empty($mfg_date) ? 'd-none' : ''; ?>">'
        //           +'<input type="text" name="mfg_date" value="" class="form-control datepicker" autocomplete="off">'
        //         +'</td>';
        // cols += '<td class="<?php echo empty($expiry_date) ? 'd-none' : ''; ?>">'
        //           +'<input type="text" name="expiry_date" value="" class="form-control datepicker" autocomplete="off">'
        //         +'</td>';
        // cols += '<td>'+select_discount+'</td>';
        cols += '<td>' + input_uom + '</td>';
        cols += '<td>' + taxable_value + '</td>';
        cols += '<td>' + tax + '</td>';

        // cols += '<td>'+tax_type+'</td>';
        cols += '<td><span name="subtotal"></span></td>';
        cols += '</tr>';

        newRow.append(cols);
        $("table.product_table").append(newRow);
        $('.select2bs4').select2({
            theme: 'bootstrap4'
        });

        calculateRow(newRow);
    }
*/


function add_row(data, return_quantity = null, free_quantity = null, purchased_quantity = null,
    delivered_quantity = null, product_name = null, description = null, cost = null, price = null,
    batch_no = null, selling_price = null, discount_id = '', d_type = '0', d_value = '0', d_amount = '0.00') {
    console.log("===== Discount Debug =====");
    console.log("discount_id :", discount_id);
    console.log("d_type      :", d_type);
    console.log("d_value     :", d_value);
    console.log("d_amount    :", d_amount);
    console.log("==========================");
    var supplier_country_id = $('#supplier_country_id').val();
    var supplier_state_id = $('#supplier_state_id').val();

    var company_country_id = $('#company_country_id').val();
    var company_state_id = $('#company_state_id').val();
    var company_gstin = '<?=$company_setting->gstin?>';

    var product = data.product;
    var discounts = data.discount;

    // Set default quantity if not provided
    var quantity_value = (return_quantity && return_quantity > 0) ? return_quantity : 0;
    
    // Calculate max quantity (purchased - delivered)
    var max_qty = (purchased_quantity && delivered_quantity) ? (purchased_quantity - delivered_quantity) : 999999;
    
    // var select_discount = "";
    // select_discount += '<select class="form-control select2bs4" name="item_discount" style="width: 100%;">';
    // select_discount += '<option value="">Select</option>';
    // for (a = 0; a < discounts.length; a++) {
    //     var type_symbol;
    //     if (discounts[a].type == 0) {
    //         type_symbol = "<?=$this->session->userdata('currency_symbol')?>";
    //     } else {
    //         type_symbol = "%";
    //     }
    //     select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name + '(' + discounts[a].value + type_symbol + ')' + '</option>';
    // }
    // select_discount += '</select>'
    // select_discount += '<span name="span_discount_type" class="discount_type">Discount Value :  <?=$this->session->userdata('currency_symbol');?> </span><input type="hidden" name="discount_type" value="0">';
    // select_discount += '<span name="discount_amount" class="discount_amount">0.0</span><input type="hidden" name="discount_value" value="0">';
    
    // var select_discount = '<select class="form-control select2bs4" name="item_discount" style="width: 100%;">';
    // select_discount += '<option value="">Select</option>';
    // for (a = 0; a < discounts.length; a++) {
    //     var selected = (discounts[a].id == discount_id) ? "selected" : ""; // Select original discount
    //     var type_symbol = (discounts[a].type == 0) ? "<?=$currency?>" : "%";
    //     select_discount += '<option value="' + discounts[a].id + '" ' + selected + '>' + discounts[a].name + '(' + discounts[a].value + type_symbol + ')</option>';
    // }
    // select_discount += '</select>';
    
    // // Set the hidden values so calculateRow works
    // select_discount += '<input type="hidden" name="discount_type" value="' + d_type + '">';
    // select_discount += '<input type="hidden" name="discount_value" value="' + d_value + '">';
    // select_discount += '<span name="discount_amount" class="discount_amount d-none">' + d_amount + '</span>';
    
    var select_discount = '<div class="d-none">'; 
    select_discount += '<select name="item_discount">';
    select_discount += '<option value="">Select</option>';
    for (a = 0; a < discounts.length; a++) {
        var selected = (discounts[a].id == discount_id) ? "selected" : "";
        select_discount += '<option value="' + discounts[a].id + '" ' + selected + '>' + discounts[a].name + '</option>';
    }
    select_discount += '</select>';
    select_discount += '<input type="hidden" name="discount_type" value="' + d_type + '">';
    select_discount += '<input type="hidden" name="discount_value" value="' + d_value + '">';
    select_discount += '<span name="discount_amount" class="discount_amount">' + d_amount + '</span>';
    select_discount += '</div>';
    
    var input_uom = '<input type="hidden" name="uom_id" value="' + product.uom_id + '" data-uom_name="' + product.uom_name + '" data-uom_uom="' + product.uom_uom + '">' + product.uom_uom;

    // FIXED: Set the quantity input with proper value and max attribute
    var input_quantity = '<input type="number" class="form-control" name="quantity" value="' + quantity_value + '" step="1" max="' + max_qty + '" min="0"><span name="quantity_update_message" class="quantity_update_message"></span>';

    var taxable_value = '<span name="taxable_value">0.00</span>';

    /************    tax begin   *****************/
    var tax = '<input type="hidden" name="tax_id" value="' + product.tax_id + '">';

    if (company_country_id == supplier_country_id) {
        if (company_state_id == supplier_state_id) {
            tax += 'CGST : <span name="c_tax">0.00</span>(<span name="cgst">' + product.cgst + '</span>)<br/>';
            tax += 'SGST : <span name="s_tax">0.00</span>(<span name="sgst">' + product.sgst + '</span>)';
            tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
        } else {
            tax += 'IGST : <span name="i_tax">0.00</span>(<span name="igst">' + product.igst + '</span>)<br/>';
            tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
            tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
        }
    } else {
        tax += 'N/A';
        tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
        tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
        tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
    }

    /************    tax type begin   *****************/
    var tax_type = '<input type="hidden" name="tax_type" value="' + product.tax_type + '">';
    tax_type += (product.tax_type == 0) ? "No" : "Yes";

    var free_quantity_input = '<input type="number" class="form-control" name="free_quantity" value="' + (free_quantity || 0) + '" max="' + (free_quantity || 0) + '" min="0" step="any">';

    var newRow = $('<tr class="product_row">');
    var cols = "";

    cols += '<td>' +
        '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>' +
        '<input type="hidden" name="product_id" value="' + product.id + '">' +
        '<input type="hidden" name="igst_rate" value="' + product.igst + '">' +
        '<input type="hidden" name="cgst_rate" value="' + product.cgst + '">' +
        '<input type="hidden" name="sgst_rate" value="' + product.sgst + '">' +
        '</td>';
    cols += '<td><span name="product_name">' + (product_name || product.name) + '</span></td>';
    cols += '<td>' + input_quantity + '</td>';
    cols += '<td>' +
        '<span id="cost_span">' +
        '<input type="number" class="form-control text-right" name="cost" step="0.01" value="' + (cost || product.cost || 0) + '" min="0.01">' +
        '<input type="hidden" name="selling_price" value="' + (selling_price || 0) + '">' +
        '<input type="hidden" name="price" value="' + (price || 0) + '">' +
        '</span>' + select_discount  + 
        '</td>';
    cols += '<td>' + input_uom + '</td>';
    cols += '<td>' + taxable_value + '</td>';
    cols += '<td>' + tax + '</td>';
    cols += '<td><span name="subtotal">0.00</span></td>';
    cols += '</tr>';

    newRow.append(cols);
    $("table.product_table tbody").append(newRow);
    $('.select2bs4').select2({
        theme: 'bootstrap4'
    });

    // Trigger calculation to update all values
    calculateRow(newRow);
}

    function is_product_exist_in_row(product_id, batch_no) {
        var isProductExist = false;
        $("#product_table_body").find('tr').each(function() {

            var tr = $(this).closest("tr");

            if (tr.find('input[name^="product_id"]').val() == product_id && tr.find(
                    'input[name^="batch_no"]').val() == batch_no) {
                isProductExist = true;
            }
        });

        return isProductExist;
    }

    function highlight_row(product_id, batch_no) {
        $("#product_table_body").find('tr').each(function() {
            var tr = $(this).closest("tr");

            if (tr.find('input[name^="product_id"]').val() == product_id && tr.find(
                    'input[name^="batch_no"]').val() == batch_no) {
                tr.addClass('highlight_row');
                tr.find('input[name^="quantity"]').val(existing_quantity + 1).trigger('change');
                tr.find('span[name^="quantity_update_message"]').text('+1');

                // setTimeout(function(){
                //   tr.removeClass('highlight_row');
                //   tr.find('span[name^="quantity_update_message"]').text('');
                // },3000);
            }
        });
    }

    $("table.product_table").on('change',
        'input[name^="cost"], input[name^="quantity"], select[name^="item_discount"]',
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

    $(document).on('change', 'input[name="tds"]', function(event) {
        // alert();
        var tds = parseFloat($(this).val());
        var total = parseFloat($('#t').val());
        var total_tax = parseFloat($('#t_tax').val());
        var total_discount = parseFloat($('#t_discount').val());

        var total_taxable_value = total - total_tax - tds;
        $('#total_taxable_value').text((total_taxable_value).toFixed(2));
        $('#t_taxable_value').val((total_taxable_value).toFixed(2));
    });
    $(document).on('change', '.return_quantity', function() {
    var qty = parseInt($(this).val()) || 0;
    var maxQty = parseInt($(this).attr('max')) || 0;
    var tr = $(this).closest('tr');
    
    // Validate quantity
    if(qty < 0) {
        $(this).val(0);
        qty = 0;
    }
    if(qty > maxQty) {
        $(this).val(maxQty);
        qty = maxQty;
        Swal.fire({
            title: "Warning",
            text: "Return quantity cannot exceed " + maxQty,
            icon: "warning",
            timer: 2000,
            showConfirmButton: false
        });
    }
    
    // Check/uncheck checkbox based on quantity
    if(qty > 0) {
        tr.find('.checkbox').prop('checked', true);
    } else {
        tr.find('.checkbox').prop('checked', false);
    }
});

   /* $(document).on('change', 'input[name="return_quantity"]', function(event) {

        if ($(this).val() > 0) {
            var tr = $(this).closest("tr");
            tr.find('.checkbox').prop('checked', true);
        }
    });*/
    
    $(document).on('change', '.return_quantity', function() {
    var qty = $(this).val();
    if(qty && qty > 0) {
        $(this).closest('tr').find('.checkbox').prop('checked', true);
    } else {
        $(this).closest('tr').find('.checkbox').prop('checked', false);
    }
});

  /*  function calculateRow(row) {
        var quantity = parseFloat(row.find('input[name="quantity"]').val()) || 0;
        var cost = parseFloat(row.find('input[name="cost"]').val()) || 0;

        var taxable_value = quantity * cost;

        // Get company & supplier location
        var supplier_country_id = $('#supplier_country_id').val();
        var supplier_state_id = $('#supplier_state_id').val();

        var company_country_id = $('#company_country_id').val();
        var company_state_id = $('#company_state_id').val();

        // GST rates
        var cgst = parseFloat(row.find('input[name="cgst_rate"]').val()) || 0;
        var sgst = parseFloat(row.find('input[name="sgst_rate"]').val()) || 0;
        var igst = parseFloat(row.find('input[name="igst_rate"]').val()) || 0;

        var cgst_tax = 0;
        var sgst_tax = 0;
        var igst_tax = 0;

        if (company_country_id == supplier_country_id) {
            if (company_state_id == supplier_state_id) {
                // SAME STATE → CGST + SGST
                cgst_tax = taxable_value * cgst / 100;
                sgst_tax = taxable_value * sgst / 100;
            } else {
                // DIFFERENT STATE → IGST ONLY
                igst_tax = taxable_value * igst / 100;
            }
        }

        var subtotal = taxable_value + cgst_tax + sgst_tax + igst_tax;

        row.find('span[name="taxable_value"]').text(taxable_value.toFixed(2));
        row.find('span[name="c_tax"]').text(cgst_tax.toFixed(2));
        row.find('span[name="s_tax"]').text(sgst_tax.toFixed(2));
        row.find('span[name="i_tax"]').text(igst_tax.toFixed(2));
        row.find('span[name="subtotal"]').text(subtotal.toFixed(2));
    }*/
    
    function calculateRow(row) {
    var quantity = parseFloat(row.find('input[name="quantity"]').val()) || 0;
    var cost = parseFloat(row.find('input[name="cost"]').val()) || 0;
    
    // Get discount values if they exist
    var discount_type = row.find('input[name="discount_type"]').val() || 0;
    var discount_value = parseFloat(row.find('input[name="discount_value"]').val()) || 0;
    
    // Calculate taxable value before discount
    var taxable_value_before_discount = quantity * cost;
    
    // Apply discount
    var final_discount = 0;
    if(discount_type == 0) {
        final_discount = discount_value; // Fixed amount discount
    } else {
        final_discount = (taxable_value_before_discount * discount_value) / 100; // Percentage discount
    }
     console.log("--- ROW CALCULATION ---");
    console.log("Qty: " + quantity + " | Cost: " + cost);
    console.log("Disc Value: " + discount_value + " | Calc Discount: " + final_discount);
    // Taxable value after discount
    var taxable_value = taxable_value_before_discount - final_discount;
    
    // Get company & supplier location
    var supplier_country_id = $('#supplier_country_id').val();
    var supplier_state_id = $('#supplier_state_id').val();
    
    var company_country_id = $('#company_country_id').val();
    var company_state_id = $('#company_state_id').val();
    
    // GST rates
    var cgst_rate = parseFloat(row.find('input[name="cgst_rate"]').val()) || 0;
    var sgst_rate = parseFloat(row.find('input[name="sgst_rate"]').val()) || 0;
    var igst_rate = parseFloat(row.find('input[name="igst_rate"]').val()) || 0;
    
    var tax_type = row.find('input[name="tax_type"]').val() || 0;
    
    var cgst_tax = 0;
    var sgst_tax = 0;
    var igst_tax = 0;
    
    // Tax calculation based on location and tax type
    if(tax_type == 1) { // Inclusive tax
        var total_tax_rate = 0;
        
        if(company_country_id == supplier_country_id) {
            if(company_state_id == supplier_state_id) {
                total_tax_rate = cgst_rate + sgst_rate;
            } else {
                total_tax_rate = igst_rate;
            }
        }
        
        // For inclusive tax, calculate tax amount from the total
        var tax_amount = (taxable_value * total_tax_rate) / (100 + total_tax_rate);
        
        if(company_country_id == supplier_country_id) {
            if(company_state_id == supplier_state_id) {
                cgst_tax = tax_amount / 2;
                sgst_tax = tax_amount / 2;
                taxable_value = taxable_value - tax_amount;
            } else if(company_country_id == supplier_country_id) {
                igst_tax = tax_amount;
                taxable_value = taxable_value - tax_amount;
            }
        }
        
    } else { // Exclusive tax
        if(company_country_id == supplier_country_id) {
            if(company_state_id == supplier_state_id) {
                // SAME STATE → CGST + SGST
                cgst_tax = (taxable_value * cgst_rate) / 100;
                sgst_tax = (taxable_value * sgst_rate) / 100;
            } else if(company_country_id == supplier_country_id) {
                // DIFFERENT STATE WITHIN SAME COUNTRY → IGST ONLY
                igst_tax = (taxable_value * igst_rate) / 100;
            }
        }
        // Cross-country transactions - no tax
    }
    
    // Calculate subtotal including all taxes
    var subtotal = parseFloat(taxable_value + cgst_tax + sgst_tax + igst_tax);
    
    // Update the row display
    row.find('span[name="taxable_value"]').text(taxable_value.toFixed(2));
    row.find('span[name="c_tax"]').text(cgst_tax.toFixed(2));
    row.find('span[name="s_tax"]').text(sgst_tax.toFixed(2));
    row.find('span[name="i_tax"]').text(igst_tax.toFixed(2));
    row.find('span[name="discount_amount"]').text(final_discount.toFixed(2));
    row.find('span[name="subtotal"]').text(subtotal.toFixed(2));
}
    
    
    
    //   function calculateRow(row)
    //   {

    //     var tax_type    = row.find('input[name^="tax_type"]').val();

    //     var supplier_country_id = $('#supplier_country_id').val();
    //     var supplier_state_id   = $('#supplier_state_id').val();

    //     var company_country_id  = $('#company_country_id').val();
    //     var company_state_id    = $('#company_state_id').val();

    //     if(tax_type == 0)
    //     {
    //       var product_id  = row.find('input[name^="product_id"]').val();
    //       var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
    //       var cost       = parseFloat(row.find('input[name^="cost"]').val());
    //       var final_discount_value = 0;

    //       var taxable_value   = parseFloat(quantity * cost);

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

    //       if(company_country_id == supplier_country_id)
    //       {
    //         if(company_state_id == supplier_state_id)
    //         {
    //           cgst        = parseFloat(row.find('input[name^="cgst_rate"]').val());
    //           sgst        = parseFloat(row.find('input[name^="sgst_rate"]').val());
    //         }
    //         else
    //         {
    //           igst        = parseFloat(row.find('input[name^="igst_rate"]').val());
    //         }  
    //       }

    //       if(company_country_id == supplier_country_id)
    //       {

    //           if(company_state_id == supplier_state_id)
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

    //       var subtotal       = parseFloat(taxable_value + igst_tax + cgst_tax + sgst_tax);

    //       row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
    //       row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
    //       row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
    //       row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

    //       row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
    //       row.find('span[name^="subtotal"]').text(subtotal.toFixed(2));  
    //     }
    //     else
    //     {
    //       var final_discount_value = 0;
    //       var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
    //       var cost       = parseFloat(row.find('input[name^="cost"]').val());

    //       var discount_type   = row.find('input[name^="discount_type"]').val();
    //       var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val());

    //       var subtotal       = (quantity * cost);

    //       if(discount_type == 0)
    //       {
    //         final_discount_value = discount_value;
    //       }
    //       else
    //       {
    //         final_discount_value = (subtotal * discount_value)/100;
    //       }

    //       subtotal = subtotal - final_discount_value;

    //       // alert(final_discount_value);

    //       var igst        = 0;
    //       var cgst        = 0;
    //       var sgst        = 0;

    //       if(company_country_id == supplier_country_id)
    //       {
    //         if(company_state_id == supplier_state_id)
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

    //       var tax_amount    = parseFloat((subtotal * tax_rate) / (100 + tax_rate));

    //       var igst_tax = 0;
    //       var cgst_tax = 0;
    //       var sgst_tax = 0;

    //       if(company_country_id == supplier_country_id)
    //       {
    //         if(company_state_id == supplier_state_id)
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

    //       var taxable_value = subtotal - (igst_tax + cgst_tax + sgst_tax);    

    //       row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
    //       row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
    //       row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
    //       row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

    //       row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
    //       row.find('span[name^="subtotal"]').text(subtotal.toFixed(2));  

    //     }

    //   }


   /* function calculateGrandTotal() {
        var total_taxable_value = 0.0;
        var total_cgst = 0.0;
        var total_sgst = 0.0;
        var total_igst = 0.0;
        var total = 0.0;
        var total_discount = 0.0;

        $("table.product_table").find('tr.product_row').each(function() {
            var tr = $(this);
            total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text()) || 0;
            total_discount += parseFloat(tr.find('span[name^="discount_amount"]').text()) || 0;
            total_cgst += parseFloat(tr.find('span[name^="c_tax"]').text()) || 0;
            total_sgst += parseFloat(tr.find('span[name^="s_tax"]').text()) || 0;
            total_igst += parseFloat(tr.find('span[name^="i_tax"]').text()) || 0;
            total += parseFloat(tr.find('span[name^="subtotal"]').text()) || 0;
        });


        $('#total_taxable_value').text(total_taxable_value.toFixed(2));
        $('#t_taxable_value').val(total_taxable_value.toFixed(2));

        $('#total_discount').text(total_discount.toFixed(2));
        $('#t_discount').val(total_discount.toFixed(2));

        $('#total_tax').text((total_cgst + total_sgst + total_igst).toFixed(2));
        $('#t_tax').val((total_cgst + total_sgst + total_igst).toFixed(2));

        $('#total').text(total.toFixed(2));
        $('#t').val(total.toFixed(2));
    }*/
    
    function calculateGrandTotal() {
    var total_taxable_value = 0.0;
    var total_cgst = 0.0;
    var total_sgst = 0.0;
    var total_igst = 0.0;
    var total = 0.0;
    var total_discount = 0.0;
    
    $("table.product_table").find('tr.product_row').each(function() {
        var tr = $(this);
        var taxable_val = parseFloat(tr.find('span[name="taxable_value"]').text()) || 0;
        var discount_amt = parseFloat(tr.find('span[name="discount_amount"]').text()) || 0;
        var cgst = parseFloat(tr.find('span[name="c_tax"]').text()) || 0;
        var sgst = parseFloat(tr.find('span[name="s_tax"]').text()) || 0;
        var igst = parseFloat(tr.find('span[name="i_tax"]').text()) || 0;
        var subtotal = parseFloat(tr.find('span[name="subtotal"]').text()) || 0;
        
        total_taxable_value += taxable_val;
        total_discount += discount_amt;
        total_cgst += cgst;
        total_sgst += sgst;
        total_igst += igst;
        total += subtotal;
    });
    
    $('#total_taxable_value').text(total_taxable_value.toFixed(2));
    $('#t_taxable_value').val(total_taxable_value.toFixed(2));
    
    $('#total_discount').text(total_discount.toFixed(2));
    $('#t_discount').val(total_discount.toFixed(2));
    
    var total_tax = total_cgst + total_sgst + total_igst;
    $('#total_tax').text(total_tax.toFixed(2));
    $('#t_tax').val(total_tax.toFixed(2));
    
    $('#total').text(total.toFixed(2));
    $('#t').val(total.toFixed(2));
}

    $('#addPurchasereturnForm').submit(function(e) {
        e.preventDefault();
        var isError = false;
        var form = this;

        // Disable submit button during processing
        $('#purchasereturnSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled',
            'disabled');

        // Field validation
        $('form#addPurchasereturnForm .field_validation').each(function() {
            var id = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if (value == null || value == "") {
                $("form#addPurchasereturnForm #err_" + id).text(field + " field is required.")
                    .fadeIn('slow');
                $('form#addPurchasereturnForm #' + id).addClass('is-invalid');
                isError = true;
            } else {
                $("form#addPurchasereturnForm #err_" + id).text("").fadeOut('slow');
                $('form#addPurchasereturnForm #' + id).removeClass('is-invalid');
                $('form#addPurchasereturnForm #' + id).addClass('is-valid');
            }
        });

        // Collect product data
        var productDataArray = [];
        $("table.product_table tbody tr.product_row").each(function() {
            var tr = $(this);
            var productData = {};

            productData['product_id'] = tr.find('input[name^="product_id"]').val();
            productData['batch_no'] = tr.find('input[name^="batch_no"]').val();
            productData['product_name'] = tr.find('span[name^="product_name"]').text();
            productData['description'] = tr.find('span[name^="description"]').text();
            productData['quantity'] = tr.find('input[name^="quantity"]').val();
            productData['cost'] = tr.find('input[name^="cost"]').val();
            productData['selling_price'] = tr.find('input[name="selling_price"]').val();
            productData['price'] = tr.find('input[name="price"]').val();
            productData['mfg_date'] = tr.find('input[name="mfg_date"]').val();
            productData['expiry_date'] = tr.find('input[name="expiry_date"]').val();
            productData['taxable_value'] = tr.find('span[name^="taxable_value"]').text();
            productData['discount_id'] = tr.find('select[name^="item_discount"]').val();
            productData['discount_type'] = tr.find('input[name^="discount_type"]').val();
            productData['discount_value'] = tr.find('input[name^="discount_value"]').val();
            productData['discount_amount'] = tr.find('span[name^="discount_amount"]').text();
            productData['uom_id'] = tr.find('input[name^="uom_id"]').val();
            productData['uom_name'] = tr.find('input[name^="uom_id"]').data('uom_name');
            productData['uom_uom'] = tr.find('input[name^="uom_id"]').data('uom_uom');
            productData['tax_id'] = tr.find('input[name^="tax_id"]').val();
            productData['tax_type'] = tr.find('input[name^="tax_type"]').val();
            productData['igst'] = tr.find('span[name^="igst"]').text();
            productData['igst_tax'] = tr.find('span[name^="i_tax"]').text();
            productData['cgst'] = tr.find('span[name^="cgst"]').text();
            productData['cgst_tax'] = tr.find('span[name^="c_tax"]').text();
            productData['sgst'] = tr.find('span[name^="sgst"]').text();
            productData['sgst_tax'] = tr.find('span[name^="s_tax"]').text();
            productData['subtotal'] = tr.find('span[name^="subtotal"]').text();
            productData['free_quantity'] = tr.find('input[name="free_quantity"]').val();

            productDataArray.push(productData);
        });

        if (productDataArray.length === 0) {
            isError = true;
            $('#emptypurchase_returnItemWarningModal').modal('show');
        }

        if (isError) {
            $('#purchasereturnSubmit').text('<?=$this->lang->line("purchase_return_add")?>').removeAttr(
                'disabled');
            return false;
        } else {
            // Create FormData object for proper file upload handling
            var formData = new FormData(form);

            // Add product items in the format the backend expects
            var itemsString = '';
            productDataArray.forEach(function(item, index) {
                if (index > 0) itemsString += '|';
                itemsString += JSON.stringify(item);
            });
            formData.set('purchase_return_items', itemsString);

            // Submit via AJAX for better error handling
            $.ajax({
                url: $(form).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    window.location.href = '<?=base_url("purchase_return")?>';
                },
                error: function(xhr) {
                    $('#purchasereturnSubmit').text(
                        '<?=$this->lang->line("purchase_return_add")?>').removeAttr(
                        'disabled');
                    alert('Error submitting form: ' + xhr.responseText);
                }
            });
        }
    });

    $("form#addPurchasereturnForm .field_validation").on("blur keyup change", function(event) {
        var id = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if (value == null || value == "") {
            $("form#addPurchasereturnForm #err_" + id).text(field + " field is required.").fadeIn(
                'slow');
            $('form#addPurchasereturnForm #' + id).addClass('is-invalid');
            $('form#addPurchasereturnForm #' + id).removeClass('is-valid');
            return false;
        } else {
            $("form#addPurchasereturnForm #err_" + id).text("").fadeOut('slow');
            $('form#addPurchasereturnForm #' + id).removeClass('is-invalid');
            $('form#addPurchasereturnForm #' + id).addClass('is-valid');
        }
    });

    $('.rcm_btn').click(function(e) {
        e.preventDefault();

        if ($('#product_table_body tr').length > 0) {
            $('.rcm-confirmation-body').html(
                '<?=$this->lang->line('purchase_return_rcm_confirmation_message')?>');

            $('#rcm-confirmation').modal({
                backdrop: 'static',
                keyboard: false
            }).on('click', '#rcm-confirmation-confirm', function(e) {

                var rcm = $('#rcm').val();

                if (rcm == 'N') {
                    $('#rcm').val('Y');
                    $('.rcm_btn').text('<?=$this->lang->line('purchase_return_disable_rcm')?>');
                } else {
                    $('#rcm').val('N');
                    $('.rcm_btn').text('<?=$this->lang->line('purchase_return_enable_rcm')?>');
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
                $('.rcm_btn').text('<?=$this->lang->line('purchase_return_disable_rcm')?>');
            } else {
                $('#rcm').val('N');
                $('.rcm_btn').text('<?=$this->lang->line('purchase_return_enable_rcm')?>');
            }
        }
    });

    // $('#invoice_no').on('change',function(){

    //   //var invoice_no = $('#invoice_no').val();
    //   var purchase_id = $(this).find(":selected").val();

    //   if(invoice_no != '')
    //   {
    //     $.ajax({
    //       url: "<?php echo base_url(); ?>purchase/get_record_detail_by_invoice_no",
    //       method: "POST",
    //       dataType:'json',
    //       data: {
    //         'invoice_no':invoice_no,
    //         '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
    //       },
    //       success: function(data){

    //         if(data.code == 1)
    //         {
    //           $('#reference_invoice_no').text('Invoice no is not available.');
    //         }
    //         else
    //         {
    //           var purchase    = data.purchase;
    //           $('#reference_invoice_no').html('<a href=""  data-toggle="modal" data-tt="tooltip" data-target="#view_purchase_detail_modal" data-purchase_id="'+purchase.id+'" id="open_view_purchase_detail_modal">Invoice no are already exists. Click here to show details of purchases</a>');
    //           $('#supplier_id').val(purchase.supplier_id).trigger('change');
    //           $('#warehouse_id').val(purchase.warehouse_id).trigger('change');
    //           $('#open_view_purchase_detail_modal').trigger('click');
    //         }
    //       }
    //     });
    //   }
    // });

    /*$('#invoice_no').on('change',function(){

      var purchase_id = $(this).find(":selected").val();
      var warehouse_id = $(this).find(":selected").data('warehouse_id');
      var supplier_id = $(this).find(":selected").data('supplier_id');

      $('#reference_invoice_no').html('<a href=""  data-toggle="modal" data-tt="tooltip" data-target="#view_purchase_detail_modal" data-purchase_id="'+purchase_id+'" id="open_view_purchase_detail_modal">Click here to show details of Purchase</a>');
      $('#supplier_id').val(supplier_id).trigger('change');
      $('#warehouse_id').val(warehouse_id).trigger('change');
      $('#open_view_purchase_detail_modal').trigger('click');
    });*/

 /*$('#invoice_no').on('change', function() {
    var purchase_id = $(this).find(":selected").val();
    var warehouse_id = $(this).find(":selected").data('warehouse_id');
    var warehouse_name = $(this).find(":selected").data('warehouse_name');
    var supplier_id = $(this).find(":selected").data('supplier_id');
    
    // Get supplier name from the selected option text
    var selectedText = $(this).find(":selected").text();
    var supplier_name = selectedText.split(' - ').slice(1).join(' - ');

    if (purchase_id) {
        // Set supplier (hidden field and display) - readonly
        $('#supplier_id').val(supplier_id);
        $('#supplier_name_display').val(supplier_name);
        
        // Set supplier state and country via AJAX
        $.ajax({
            url: "<?php echo base_url('supplier/get_record_detail') ?>",
            type: "POST",
            dataType: "json",
            async: false,
            data: {
                'supplier_id': supplier_id,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(data) {
                var supplier = data.supplier;
                $('#supplier_state_id').val(supplier.state_id);
                $('#supplier_country_id').val(supplier.country_id);
                $('#supplier_gstin').val(supplier.gstin);
            }
        });
        
        // Handle warehouse - readonly/disabled
        var $warehouseField = $('#warehouse_id');
        
        if ($warehouseField.is('select')) {
            $warehouseField.val(warehouse_id).trigger('change');
            // Disable the warehouse select
            $warehouseField.prop('disabled', true);
        } else if ($warehouseField.is('input') && $warehouseField.attr('type') === 'hidden') {
            $warehouseField.val(warehouse_id);
            if ($('#warehouse_name_display').length) {
                $('#warehouse_name_display').val(warehouse_name);
            }
        }
        
        // Clear any existing products
        $("#product_table_body tr").remove();
        
        // Update the link for purchase details
        $('#reference_invoice_no').html('<a href="" data-toggle="modal" data-tt="tooltip" data-target="#view_purchase_detail_modal" data-purchase_id="' + purchase_id + '" id="open_view_purchase_detail_modal">Click here to show details of Purchase</a>');
        
        // Show the rows that were hidden
        $("#supplier_id").closest('.row').siblings().fadeIn(10);
        
        // Open the modal to show purchase items
        $('#open_view_purchase_detail_modal').trigger('click');
    }
});*/


$('#invoice_no').on('change', function() {
  var $selected = $(this).find(":selected");
  var purchase_id = $selected.data('purchase_id');
  var warehouse_id = $selected.data('warehouse_id');
  var warehouse_name = $selected.data('warehouse_name');
  var supplier_id = $selected.data('supplier_id');
  var invoice_no = $selected.data('invoice_no');
  var selectedText = $selected.text();
  var supplier_name = selectedText.split(' - ').slice(1).join(' - ');
  
  if(!purchase_id || purchase_id == '') {
      // Reset fields
      $('#supplier_id').val('');
      $('#supplier_name_display').val('');
      $('#warehouse_id').val('');
      $('#warehouse_name_display').val('');
      $("#product_table_body tr").remove();
      calculateGrandTotal();
      $('#purchase_details_link').hide(); // Hide the link
      return;
  }
  
  // Show the purchase details link
  $('#purchase_details_link').show();
  
  // Set supplier
  $('#supplier_id').val(supplier_id);
  $('#supplier_name_display').val(supplier_name);
  
  // Set warehouse
  $('#warehouse_id').val(warehouse_id);
  $('#warehouse_name_display').val(warehouse_name);
  
  // Set supplier state and country via AJAX
  $.ajax({
      url: "<?php echo base_url('supplier/get_record_detail') ?>",
      type: "POST",
      dataType: "json",
      async: false,
      data: {
          'supplier_id': supplier_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
      },
      success: function(data) {
          if(data.supplier) {
              $('#supplier_state_id').val(data.supplier.state_id || '');
              $('#supplier_country_id').val(data.supplier.country_id || '');
              $('#supplier_gstin').val(data.supplier.gstin || '');
          }
      }
  });
  
  // Clear existing products
  $("#product_table_body tr").remove();
  calculateGrandTotal();
  
  // Store purchase_id for the modal
  $('#view_purchase_detail_modal').data('purchase_id', purchase_id);
  
  // Load purchase items data immediately (but don't show modal yet)
  $.ajax({
      url: "<?php echo base_url('purchase_return/get_purchase_items_by_purchase_id')?>",
      type: "POST",
      dataType: "JSON",
      async: false,
      data: {
          'purchase_id': purchase_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
      },
      success: function(data) {
          $("#view_purchase_items").html(data.view_purchase_items);
          
          // Initialize select all checkbox
          $('#selectAll').off('click').on('click', function() {
              if(this.checked) {
                  $('.checkbox').each(function() {
                      this.checked = true;
                  });
              } else {
                  $('.checkbox').each(function() {
                      this.checked = false;
                  });
              }
          });
      }
  });
});

// Handle click on the purchase details link
$(document).on('click', '#show_purchase_details', function(e) {
  e.preventDefault();
  
  var purchase_id = $('#invoice_no').find(":selected").data('purchase_id');
  
  if(purchase_id) {
      // Refresh the items in modal (in case of any updates)
      $.ajax({
          url: "<?php echo base_url('purchase_return/get_purchase_items_by_purchase_id')?>",
          type: "POST",
          dataType: "JSON",
          data: {
              'purchase_id': purchase_id,
              '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          success: function(data) {
              $("#view_purchase_items").html(data.view_purchase_items);
              
              // Re-initialize select all checkbox
              $('#selectAll').off('click').on('click', function() {
                  if(this.checked) {
                      $('.checkbox').each(function() {
                          this.checked = true;
                      });
                  } else {
                      $('.checkbox').each(function() {
                          this.checked = false;
                      });
                  }
              });
          }
      });
      
      // Show the modal
      $('#view_purchase_detail_modal').modal('show');
  }
});

// Handle modal show event separately (for any other triggers)
$(document).on('shown.bs.modal', '#view_purchase_detail_modal', function(e) {
    // If purchase_id is already loaded via the change event, don't reload
    var purchase_id = $(this).data('purchase_id');
    if (purchase_id && !$(this).data('loaded')) {
        $(this).data('loaded', true);
    }
});

// Add this delegated event handler for the dynamic button
$(document).on('click', '.open_purchase_detail_btn', function(e) {
    e.preventDefault();
    
    var purchase_id = $(this).data('purchase_id');
    
    if(purchase_id) {
        // Store the purchase_id for the modal
        $('#view_purchase_detail_modal').data('purchase_id', purchase_id);
        
        // Show the modal
        $('#view_purchase_detail_modal').modal('show');
        
        // Load the purchase items
        $.ajax({
            url: "<?php echo base_url('purchase_return/get_purchase_items_by_purchase_id')?>",
            type: "POST",
            dataType: "JSON",
            data: {
                'purchase_id': purchase_id,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(data) {
                $("#view_purchase_items").html(data.view_purchase_items);
                
                // Initialize select all checkbox functionality
                $('#selectAll').off('click').on('click', function() {
                    if (this.checked) {
                        $('.checkbox').each(function() {
                            this.checked = true;
                        });
                    } else {
                        $('.checkbox').each(function() {
                            this.checked = false;
                        });
                    }
                });
            },
            error: function(xhr, status, error) {
                console.log('Error loading purchase items:', error);
                $("#view_purchase_items").html('<div class="alert alert-danger">Failed to load purchase items. Please try again.</div>');
            }
        });
    } else {
        console.log('No purchase_id found');
    }
});

// Reset modal data when closed
$('#view_purchase_detail_modal').on('hidden.bs.modal', function() {
    $(this).data('purchase_id', null);
    $(this).data('loaded', false);
});
  /*  $('#view_purchase_detail_modal').on('hide.bs.modal', function(e) {
        var existing_product_array = new Array();

        $("#existing_purchase_items").find('tr').each(function() {
            var tr = $(this).closest("tr");

            if (tr.find('.checkbox').is(':checked')) {
                //var product_id = tr.find('input[name="product_id"]').val();
                var product_id = tr.find('input[name="product_id"]').val();
                var return_quantity = tr.find('input[name="return_quantity"]').val();
                var purchased_quantity = tr.find('input[name="purchased_quantity"]').val();
                var delivered_quantity = tr.find('input[name="delivered_quantity"]').val();
                var product_name = tr.find('input[name="product_name"]').val();
                var description = tr.find('input[name="description"]').val();
                var cost = tr.find('input[name="cost"]').val();
                var selling_price = tr.find('input[name="selling_price"]').val();
                var price = tr.find('input[name="price"]').val();
                var batch_no = tr.find('input[name="batch_no"]').val();

                var free_quantity = tr.find('input[name="free_quantity"]').val();
                // alert(free_quantity);

                $.ajax({
                    url: "<?php echo base_url('product/get_record_detail') ?>",
                    type: "POST",
                    dataType: "json",
                    data: {
                        'product_id': product_id,
                        'module': '<?=PURCHASE_RETURN_MODULE?>',
                        '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    success: function(data) {
                        var product = data.product;

                        if (!is_product_exist_in_row(product.id, batch_no)) {
                            add_row(data, return_quantity, free_quantity,
                                purchased_quantity, delivered_quantity,
                                product_name, description, cost, price,
                                batch_no, selling_price);
                        } else {
                            highlight_row(product.id, batch_no);
                        }

                        $('.datepicker').datepicker({
                            weekStart: 1,
                            daysOfWeekHighlighted: "6,0",
                            autoclose: true,
                            todayHighlight: true,
                            format: 'dd-mm-yyyy'
                        });

                        $("#supplier_id").closest('.row').siblings().fadeIn(10);
                        // $('#search_product').val('').focus();
                        calculateGrandTotal();
                    }
                });
            }
        });
    });*/
// Remove the old shown.bs.modal event handler and replace with this
$('#view_purchase_detail_modal').on('hide.bs.modal', function (e) {
        console.log("--- MODAL CLOSING: TRANSFERRING DATA ---");

    var existing_product_array = new Array();

    $("#existing_purchase_items").find('tr').each(function () {
        var tr = $(this).closest("tr");

        if(tr.find('.checkbox').is(':checked'))
        {
            var product_id              = tr.find('input[name="product_id"]').val();
            var return_quantity         = tr.find('input[name="return_quantity"]').val();
            var purchased_quantity      = tr.find('input[name="purchased_quantity"]').val();
            var delivered_quantity      = tr.find('input[name="delivered_quantity"]').val();
            var product_name            = tr.find('input[name="product_name"]').val();
            var description             = tr.find('input[name="description"]').val();
            var cost                    = tr.find('input[name="cost"]').val();
            var selling_price           = tr.find('input[name="selling_price"]').val();
            var price                   = tr.find('input[name="price"]').val();
            var batch_no                = tr.find('input[name="batch_no"]').val();
            var free_quantity           = tr.find('input[name="free_quantity"]').val();
            
            var discount_id         = tr.find('input[name="discount_id"]').val();
            var discount_type       = tr.find('input[name="discount_type"]').val();
            var discount_value      = tr.find('input[name="discount_value"]').val();
            var discount_amount     = tr.find('input[name="discount_amount"]').val();
            console.log("Product: " + product_name);
            console.log("Detected Discount - ID: " + discount_id + " | Value: " + discount_value + " | Type: " + discount_type + " | amout: " + discount_amount);
            // Validate return quantity
            if(!return_quantity || return_quantity == 0) {
                Swal.fire({
                    title: "Warning",
                    text: "Please enter return quantity for product: " + product_name,
                    icon: "warning",
                    confirmButtonText: "Ok"
                });
                return;
            }

            $.ajax({
                url: "<?php echo base_url('product/get_record_detail') ?>",
                type: "POST",
                dataType: "json",
                async: false,
                data: {
                    'product_id': product_id,
                    'module': '<?=PURCHASE_RETURN_MODULE?>',
                    '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
                },
                success: function(data){
                    var product = data.product;

                    if(!is_product_exist_in_row(product.id, batch_no))
                    {
                        // add_row(data, return_quantity, free_quantity, purchased_quantity, 
                        //         delivered_quantity, product_name, description, cost, 
                        //         price, batch_no, selling_price);
                        add_row(data, return_quantity, null, purchased_quantity, 
                                delivered_quantity, product_name, null, cost, 
                                null, batch_no, null, 
                                discount_id, discount_type, discount_value, discount_amount);
                    }
                    else
                    {
                        highlight_row(product.id, batch_no);
                    }

                    $('.datepicker').datepicker({
                        weekStart: 1,
                        daysOfWeekHighlighted: "6,0",
                        autoclose: true,
                        todayHighlight: true,
                        format: 'dd-mm-yyyy'
                    });
                   
                    $("#supplier_id").closest('.row').siblings().fadeIn(10);
                    calculateGrandTotal();
                }
            });  
        }
    });
});

    $('.gstin_row').css('display', 'none');

    // regular expression for gstin format
    var gstReg = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/;

    const SupplierToast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 10000
    });

    $('form#addSupplierForm #supplierSubmit').click(function(e) {
        e.preventDefault();

        var isError = false;

        $('form#addSupplierForm .field_validation').each(function() {

            var id = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if (value == null || value == "") {
                $("form#addSupplierForm #err_" + id).text(field + " field is required.");
                if ($('form#addSupplierForm #' + id).hasClass('is-valid')) {
                    $('form#addSupplierForm #' + id).removeClass('is-valid');
                }
                $('form#addSupplierForm #' + id).addClass('is-invalid');
                isError = true;
            } else {
                $("form#addSupplierForm #err_" + id).text("");
                $('form#addSupplierForm #' + id).removeClass('is-invalid');
                $('form#addSupplierForm #' + id).addClass('is-valid');
            }

            if (!gstReg.test($('form#addSupplierForm #gstin').val()) && $(
                    'form#addSupplierForm #gst_registration_type').val() == 1) {
                if ($('form#addSupplierForm #gstin').val() == '') {
                    // $('form#addSupplierForm #gstin').val('');
                    $("form#addSupplierForm #err_gstin").text('GSTIN field is required');
                    $('form#addSupplierForm #gstin').addClass('is-invalid');
                    isError = true;
                } else {
                    // $('form#addSupplierForm #gstin').val('');
                    $("form#addSupplierForm #err_gstin").text('Please enter valid GSTIN');
                    $('form#addSupplierForm #gstin').addClass('is-invalid');
                    isError = true;
                }
            } else {

                $("form#addSupplierForm #err_gstin").text("");
                $('form#addSupplierForm #gstin').removeClass('is-invalid');
                $('form#addSupplierForm #gstin').addClass('is-valid');
            }
        });


        if (isError == true) {
            return false;
        } else {
            var formData = $('#addSupplierForm').serialize();
            $('form#addSupplierForm #supplierSubmit').text('<?=$this->lang->line("please_wait")?>')
                .attr('disabled', 'disabled');

            $.ajax({
                url: '<?php echo base_url("supplier/add") ?>',
                type: 'POST',
                dataType: 'json',
                data: formData,
                success: function(response) {

                    var supplier = response.supplier;

                    if (response.code == 1) {
                        $('#add_supplier_modal').modal('hide');
                        $('#supplierSubmit').text('<?=$this->lang->line("submit")?>')
                            .removeAttr('disabled');

                        $('#supplier_id').html('');
                        $('#supplier_id').append('<option value="">Select</option>');

                        for (i = 0; i < response['suppliers'].length; i++) {
                            $('#supplier_id').append('<option value="' + response[
                                    'suppliers'][i].id + '">' + response['suppliers'][i]
                                .company_name + '</option>');
                        }

                        $('#supplier_id').val(response['id']).attr("selected", "selected");

                        if ($("#warehouse_id").length) {
                            if ($("#warehouse_id").val() != '') {
                                $("#supplier_id").closest('.row').siblings().fadeIn(10);
                            }

                            if (supplier.gst_registration_type == 0) {
                                $('#rcm').val('Y');
                            }

                            $('form#addSupplierForm #supplier_state_id').val(supplier
                                .state_id);
                            $('form#addSupplierForm #supplier_country_id').val(supplier
                                .country_id);
                            $('form#addSupplierForm #supplier_gstin').val(supplier.gstin);

                            $("#product_table_body tr").remove();
                            calculateGrandTotal();
                        }

                        // show_message('success-header',response.message);
                        SupplierToast.fire({
                            type: 'success',
                            title: response.message
                        });
                    } else {
                        // show_message('failure-header',response.message);
                        SupplierToast.fire({
                            type: 'error',
                            title: response.message
                        });
                    }
                },
                error: function() {
                    show_message('failure-header',
                        'Please contact the administrator if you are keep facing this issue.'
                    );
                }
            });
        }
    });

    $("form#addSupplierForm .field_validation").on("blur change keyup", function(event) {
        var id = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if (value == null || value == "") {
            $("form#addSupplierForm #err_" + id).text(field + " field is required.");
            if ($('form#addSupplierForm #' + id).hasClass('is-valid')) {
                $('form#addSupplierForm #' + id).removeClass('is-valid');
            }
            $('form#addSupplierForm #' + id).addClass('is-invalid');
            return false;
        } else {
            $("form#addSupplierForm #err_" + id).text("");
            $('form#addSupplierForm #' + id).removeClass('is-invalid');
            $('form#addSupplierForm #' + id).addClass('is-valid');
        }
    });

    $(document).on('hidden.bs.modal', '#add_supplier_modal', function() {

        $('.gstin_row').css('display', 'none');

        $('form#addSupplierForm .form-control').each(function() {
            var id = $(this).attr('id');
            $("form#addSupplierForm #" + id).val("");
            $("form#addSupplierForm #err_" + id).text("");
            $('form#addSupplierForm #' + id).removeClass('is-invalid');
            $('form#addSupplierForm #' + id).removeClass('is-valid');
        });
    });
    $('#add_supplier_modal').on('shown.bs.modal', function() {

        $("form#addSupplierForm #company_name").focus();

        var company_country_id =
            '<?=$this->company_settings_model->get_company_records()->country_id?>';

        $('#country_id').html('<option value="">Select</option>');

        $.ajax({
            url: "<?php echo base_url('utility/get_countries') ?>/",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $('#country_id').append('<option value="' + data[i].id + '">' + data[i]
                        .name + '</option>');
                }
                $('#country_id').val(company_country_id).trigger('change');
            }
        });
    });

    $("form#addSupplierForm #gstin").on("blur keyup change", function(event) {

        if (!gstReg.test($('form#addSupplierForm #gstin').val()) && $(
                'form#addSupplierForm #gst_registration_type').val() == 1) {
            if ($('form#addSupplierForm #gstin').val() == "") {
                $("form#addSupplierForm #err_gstin").text("GSTIN field is required.");
                $('form#addSupplierForm #gstin').addClass('is-invalid');
                return false;
            } else {
                $("form#addSupplierForm #err_gstin").text("Please enter valid GSTIN.");
                $('form#addSupplierForm #gstin').addClass('is-invalid');
                return false;
            }
        } else {
            $("form#addSupplierForm #err_gstin").text("");
            $('form#addSupplierForm #gstin').removeClass('is-invalid');
            $('form#addSupplierForm #gstin').addClass('is-valid');
        }
    });

    $("form#addSupplierForm #gst_registration_type").on("blur keyup change", function(event) {

        if ($('form#addSupplierForm #gst_registration_type').val() == 1) {
            $('.gstin_row').fadeIn(10);

            if ($('form#addSupplierForm #gstin').val() == "") {
                $("form#addSupplierForm #err_gstin").text("GSTIN field is required.");
                $('form#addSupplierForm #gstin').addClass('is-invalid');
                return false;
            } else {
                $("form#addSupplierForm #err_gstin").text("Please enter valid GSTIN.");
                $('form#addSupplierForm #gstin').addClass('is-invalid');
                return false;
            }
        } else {
            $('.gstin_row').fadeOut(10);

            $("form#addSupplierForm #err_gstin").text("");
            $('form#addSupplierForm #gstin').removeClass('is-invalid');
            $('form#addSupplierForm #gstin').addClass('is-valid');
        }
    });

    $('#country_id').change(function() {

        var id = $(this).val();

        var company_state_id = '<?=$this->company_settings_model->get_company_records()->state_id?>';
        // alert(id);
        $('form#addSupplierForm #state_id').html('<option value="">Select</option>');
        $('form#addSupplierForm #city_id').html('<option value="">Select</option>');
        $.ajax({
            url: "<?php echo base_url('utility/get_states') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $('#state_id').append('<option value="' + data[i].id + '">' + data[i]
                        .name + '</option>');
                }
                $('#state_id').val(company_state_id).trigger('change');
            }
        });
    });

    $('#state_id').change(function() {

        var id = $(this).val();

        // alert(id);
        $('#city_id').html('<option value="">Select</option>');
        $.ajax({
            url: "<?php echo base_url('utility/get_cities') ?>/" + id,
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                for (i = 0; i < data.length; i++) {
                    $('#city_id').append('<option value="' + data[i].id + '">' + data[i]
                        .name + '</option>');
                }
            }
        });
    });

    // Submit purchase return items

    $('#purchaseItemsReturnForm').submit(function(e) {
        e.preventDefault();
        $('#view_purchase_detail_modal').modal('hide');
    });

    $(document).on('change', '#upload_document', function(e) {
        var allowedExtensions = ["pdf"];
        var files = this.files;
        var filenames = [];

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
                fileInputLabel.text("Choose file");
                return;
            }

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

        var csrfTokenName =
            "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
        var csrfTokenValue =
            "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

        // Add the CSRF token to the form data
        formData.append(csrfTokenName, csrfTokenValue);

        // Perform the AJAX request to the server
        $.ajax({
            url: "<?php echo base_url('purchase_return/upload_documents')?>", // Replace with your server-side script URL
            type: "POST",
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.length > 0) {
                    var documentValue = response.join(', '); // Join filenames with commas
                    $('#document').val(documentValue); // Set the document value
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Handle the error (if needed)
                alert("Error uploading document: " + errorThrown);
            }
        });
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