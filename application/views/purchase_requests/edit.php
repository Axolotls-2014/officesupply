<?php 
  $this->load->view('layout/header');
  $currency = $this->company_settings_model->get_company_records()->currency_symbol;
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
                <li class="breadcrumb-item "><a href="<?=base_url('quotation')?>">Edit Purchase Order</a></li>
                <li class="breadcrumb-item active">Edit Purchase Order</li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
           <form class="form-horizontal" name="editQuotationForm" id="editQuotationForm" method="POST" action="<?= base_url('Purchase_request/update_purchase_order/' . base64_encode($purchase->id)) ?>">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">Edit Purchase Order</h3>
                  <div class="card-tools">
                    <div class="btn-group d-none">
                      <button type="button" class="btn btn-tool btn-warning rcm_btn" data-tt="tooltip" title="<?=$this->lang->line('quotation_rcm_change_status')?>">
                        <?=($purchase->rcm == 'Y') ? $this->lang->line('quotation_disable_rcm') : $this->lang->line('quotation_enable_rcm')?>
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
                          <label>Invoice Number</label>
                          <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?=$purchase->reference_no?>" readonly>
                        </div>
                      </div>
                      <div class="col-sm-3">
                        <div class="form-group">
                          <label>PO Date</label>
                          <input type="text" class="form-control datepicker" id="quotation_date" name="quotation_date" value="<?=date('d-m-Y', strtotime($purchase->invoice_date))?>" placeholder="Invoice Date">
                          <span id="err_quotation_date" class="error invalid-feedback"><?=form_error('quotation_date');?></span>
                        </div>
                      </div>
                    </div>
                
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-store"></i> Branch Details
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                      
                        
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted"><i class="fas fa-building"></i> Branch Name</span>
                                    <span class="info-box-number"><?= $branch_details->branch_name ?? 'N/A' ?></span>
                                </div>
                            </div>
                        </div>
                         <div class="col-md-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted"><i class="fas fa-user"></i> User Name</span>
                                    <span class="info-box-number"><?= $order_info->user_name ?? 'N/A' ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted"><i class="fas fa-mobile-alt"></i> Contact No.</span>
                                    <span class="info-box-number"><?= $branch_details->contact_no ?? 'N/A' ?></span>
                                </div>
                            </div>
                        </div>
                   
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted"><i class="fas fa-envelope"></i> Email</span>
                                    <span class="info-box-number" style="font-size: 12px;"><?= $branch_details->email ?? 'N/A' ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted"><i class="fas fa-map-marker-alt"></i> Address</span>
                                    <span class="info-box-number"><?= $branch_details->address ?? 'N/A' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Personal Details Card - Customer Information Only -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="card card-info card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-user"></i> Personal Details
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted"><i class="fas fa-user-tag"></i> Customer Name</span>
                                    <span class="info-box-number"><?= $customer_details->customer_name ?></span>
                                </div>
                            </div>
                        </div>
            
                        <div class="col-md-3">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted"><i class="fas fa-envelope"></i> Email</span>
                                    <span class="info-box-number" style="font-size: 12px;"><?= $customer_details->email ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box bg-light">
                                <div class="info-box-content">
                                    <span class="info-box-text text-muted"><i class="fas fa-mobile-alt"></i> Contact No.</span>
                                    <span class="info-box-number"><?= $customer_details->phone ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                  
                </div>
            </div>
        </div>
    </div>
</section>
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
                          <label><?=$this->lang->line('purchase_items')?></label>
                          <table class="table items table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
                            <thead>
                              <tr>
                                <th width="2%">
                                  <img src="<?php  echo base_url(); ?>assets/images/bin1.png" />
                                </th>
                                <th class="span2" width="15%"><?=$this->lang->line('product_description')?></th>
                                <th class="span2" width="8%"><?=$this->lang->line('quotation_qty')?></th>
                                <th class="span2" style="display:none" width="10%"><?=$this->lang->line('quotation_price')?></th>
                                <!--<th class="span2" width="6%"><?=$this->lang->line('purchase_uom')?></th>-->
                                <th class="span2" width="10%"><?=$this->lang->line('quotation_taxable_value')?></th>
                                <th class="span2" width="15%"><?=$this->lang->line('quotation_tax')?></th>
                                <th class="span2" width="5%"><?=$this->lang->line('quotation_inclusive')?></th>
                                <th class="span2" width="10%"><?=$this->lang->line('quotation_total')?></th>
                              </tr>
                            </thead>
                            <tbody id="product_table_body">
                              <?php 
                                foreach ($purchase_items as $row) {
                              ?>
                                <tr class="product_row">
                                  <td>
                                    <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i>
                                    </span>
                                    <input type="hidden" name="product_id" value="<?=$row->product_id?>">
                                    <input type="hidden" name="igst_rate" value="<?=$row->igst_tax?>">
                                    <input type="hidden" name="sgst_rate" value="<?=$row->sgst_tax?>">
                                    <input type="hidden" name="cgst_rate" value="<?=$row->cgst_tax?>">
                                    <?php 
                                      $product_id   = $row->product_id;
                                      $cost         = $row->cost;
                                      $warehouse_id = $purchase->warehouse_id;
                                      $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);
                                    ?>
                                  </td>
                                  <td>
                                    <span name="product_name"><?=$row->product_name?></span><br/>
                                    <span name="description"><?=$row->description?></span>
                                  </td>
                                  <td>
                                    <input type ="number" class="form-control" name="quantity" value="<?=$row->quantity?>" step="1" min="1">
                                    <span name  ="quantity_update_message" class="quantity_update_message"></span>
                                  </td>
                                  <td style="display:none">
                                    <span id="price_span">
                                      <input type="number" class="form-control text-right" name="price" step="0.01" value="<?=$row->cost?>">
                                      <input type="hidden" name="cost" value="<?=$row->cost?>">
                                    </span>
                                  </td>
                                  <td style="display:none;">
                                    <select class="form-control select2bs4" name="item_discount" style="width: 100%;">
                                      <option value="">Select</option>
                                      <?php 
                                        foreach ($discounts as $value) {
                                      ?>
                                        <option value="<?=$value->id?>"
                                          <?php 
                                            if($value->id == $row->discount_id)
                                              echo ' selected';
                                          ?>
                                        >
                                          <?=$value->name.' ('.$value->value.$this->session->userdata('currency_symbol').')' ?>
                                        </option>
                                      <?php
                                        }
                                      ?>
                                      </option>
                                    </select>
                                    <span name="span_discount_type" class="discount_type">Discount Value :  <?=$this->session->userdata('currency_symbol')?> </span>
                                    <input type="hidden" name="discount_type" value="<?=$row->discount_type?>">
                                    <span name="discount_amount" class="discount_amount"><?=$row->discount_amount?></span>
                                    <input type="hidden" name="discount_value" value="<?=$row->discount_value?>">
                                  </td>
                                  <td><span name="taxable_value"><?=$row->taxable_value?></span></td>
                                  <td class="tax_td">
                                  <?php
                                    if (!empty($row->igst) && $row->igst > 0) {
                                  ?>
                                      <input type="hidden" name="tax_id" value="1">
                                      IGST : <span name="i_tax"><?= $row->igst_tax ?></span>
                                      (<span name="igst"><?= $row->igst ?></span>)
                                      <span name="c_tax" style="display:none"><?= $row->cgst_tax ?></span>
                                      <span name="cgst" style="display:none"><?= $row->cgst ?></span>
                                      <span name="s_tax" style="display:none"><?= $row->sgst_tax ?></span>
                                      <span name="sgst" style="display:none"><?= $row->sgst ?></span>
                                  <?php
                                    } elseif ((!empty($row->cgst) && $row->cgst > 0) || (!empty($row->sgst) && $row->sgst > 0)) {
                                  ?>
                                      <input type="hidden" name="tax_id" value="1">
                                      CGST : <span name=""><?= $row->cgst_tax ?>%</span>
                                      (<span name="cgst"><?= $row->cgst ?></span>)<br>
                                      SGST : <span name=""><?= $row->sgst_tax ?>%</span>
                                      (<span name="sgst"><?= $row->sgst ?></span>)
                                      <span name="i_tax" style="display:none"><?= $row->igst_tax ?></span>
                                      <span name="igst" style="display:none"><?= $row->igst ?></span>
                                  <?php
                                    } else {
                                  ?>
                                      N/A
                                      <span name="c_tax" style="display:none"><?= $row->cgst_tax ?></span>
                                      <span name="cgst" style="display:none"><?= $row->cgst ?></span>
                                      <span name="s_tax" style="display:none"><?= $row->sgst_tax ?></span>
                                      <span name="sgst" style="display:none"><?= $row->sgst ?></span>
                                      <span name="i_tax" style="display:none"><?= $row->igst_tax ?></span>
                                      <span name="igst" style="display:none"><?= $row->igst ?></span>
                                  <?php
                                    }
                                  ?>
                                </td>
                                  <td>
                                    <input type="hidden" name="tax_type" value="<?=$row->tax_type?>">
                                    <?=($row->tax_type == 0) ? 'No' : 'Yes';?>
                                  </td>
                                  <td><span name="sub_total"><?=$row->subtotal?></span></td>
                                </tr>
                              <?php 
                                }
                              ?>
                            </tbody>
                          </table>
                          <table class="table table-striped table-bordered table-condensed table-hover total_data" style="font-size: 18px;">
                            <tr>
                              <td align="right" colspan="7"><?=$this->lang->line('quotation_total_taxable_value')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success">
                                +<span id="total_taxable_value"><?=$purchase->total_taxable_value?></span>
                                <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="<?=$purchase->total_taxable_value?>">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="7"><?=$this->lang->line('quotation_total_tax')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success">
                                +<span id="total_tax"><?=$purchase->total_tax?></span>
                                <input type="hidden" name="total_tax" id="t_tax" value="<?=$purchase->total_tax?>">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="7"><?=$this->lang->line('quotation_total')?>(<?=$currency?>)</td>
                              <td align='right'>
                                <span id="total"><?=$purchase->total?></span>
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
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('quotation_external_note'); ?>"><?=str_replace("<br />","",$purchase->external_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('quotation_internal_note'); ?>"><?=str_replace("<br />","",$purchase->internal_note)?></textarea>
                                </div>
                                <div class="tab-pane" id="bank_detail">
                                  <textarea class="col-sm-12 form-control" name="bank_detail" rows="4" placeholder="<?php echo $this->lang->line('quotation_bank_detail'); ?>"><?=str_replace("<br />","",$purchase->bank_detail)?></textarea>
                                </div>
                                <div class="tab-pane" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('quotation_terms_and_condition'); ?>"><?=str_replace("<br />","",$purchase->terms_and_condition)?></textarea>
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
                  <input type="hidden" name="rcm" id="rcm" value="<?=$purchase->rcm?>">
                  <input type="hidden" name="id" value="<?=$purchase->id?>">
                  <input type="hidden" name="purchase_items" id="purchase_items" value="">
                  <!--<input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">-->
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <!--<input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">-->
                  <button type="submit" name="submit" id="quotationSubmit" class="btn btn-info">Edit</button>
                    <a href="<?= base_url('Purchase_request?highlight=' . base64_encode($purchase->id)) ?>" class="btn btn-secondary ml-2">
    <i class="fas fa-arrow-left"></i> Back to List
  </a>
                  <!-- <button type="submit" name="submit" id="quotationSubmitPayNow" value="pay" name="pay" class="btn btn-info">Add Quotation & Pay Now</button>-->
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

<script src="https://cdn.jsdelivr.net/npm/disableautofill/src/jquery.disableAutoFill.min.js"></script>

<script type="text/javascript">

  $(document).ready(function(e){

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

    $('#customer_id').change(function(e){

      $(".customer_shipping_detail").fadeIn(10);

      var customer_id = $(this).val();
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

              $('#customer_state_id').val(customer.state_id);
              $('#customer_country_id').val(customer.country_id);
              // $('#customer_id').val(customer.id);


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

          tax_td.html(tax);
          calculateRow(tr);
          calculateGrandTotal();
        });
      }

      var c_mapping = { };

      $(function(){
  
        $('#search_product').autoComplete({
          minChars: 1,
          cache: 0,
          source: function(term, suggest){
            term = term.toLowerCase();

            $.ajax({
              url: "<?php echo base_url('purchase_request/search') ?>",
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
              url: "<?php echo base_url('purchase_request/get_record_detail') ?>",
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
                $('#search_product').val('').focus();
              }
            });
          } 
        });
      });
      
      /*function add_row(data)
      {
           var tax_type = '<?= $tax_type ?>';
        console.log("Tax Type:", tax_type); 
        var customer_country_id = $('#customer_country_id').val();
        var customer_state_id   = $('#customer_state_id').val();
        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var company_gstin       = '<?=$company_setting->gstin?>';

        var product   = data.product;
        var discounts = data.discount;

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

        var input_quantity =  '<input type="number" class="form-control" name="quantity" value="1" step="1" min="1"><span name="quantity_update_message" class="quantity_update_message"></span>';
        var taxable_value  =  '<span name="taxable_value">'+product.price+'</span>';

        var tax = '<input type="hidden" name="tax_id" value="'+product.tax_id+'">';

        // alert(company_state_id);
        // alert(customer_state_id);

        const cgst = parseFloat(product.cgst) || 0;
        const sgst = parseFloat(product.sgst) || 0;
        const igst = parseFloat(product.igst) || 0;

        let tax_html = '';
        let total_tax_percentage = 0;
    
        console.log("CGST:", product.cgst, "SGST:", sgst, "IGST:", igst);
    
        if (tax_type === 'intra') {
            tax_html += '<strong>Tax Type:</strong> Intra-State<br>';
            tax_html += `CGST: ${cgst}%<br>`;
            tax_html += `SGST: ${sgst}%<br>`;
            total_tax_percentage = cgst + sgst;
        } else if (tax_type === 'inter') {
            tax_html += '<strong>Tax Type:</strong> Inter-State<br>';
            tax_html += `IGST: ${igst}%<br>`;
            total_tax_percentage = igst;
        } else {
            console.warn("Unknown tax_type, setting to N/A");
            tax_html += '<strong>Tax:</strong> N/A<br>';
        }
        tax_html += `<strong>Total Tax:</strong> ${total_tax_percentage}%`;
        console.log("Total Tax Percentage:", total_tax_percentage + "%");
        const tax_type_input = `<input type="hidden" name="tax_type" value="${tax_type}">`;



        var tax_type  = '<input type="hidden" name="tax_type" value="'+product.tax_type+'">';
        tax_type      += (product.tax_type == 0) ? "No" : "Yes";


        var newRow = $('<tr class="product_row">');
            var cols = "";
            cols += '<td>'
                      + '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
                      + '<input type="hidden" name="product_id" value="'+product.id+'">'
                      + '<input type="hidden" name="warehouse_product_id" value="'+product.warehouse_products_id+'">'  
                      + '<input type="hidden" name="igst_rate" value="'+product.igst+'">'
                      + '<input type="hidden" name="cgst_rate" value="'+product.cgst+'">'
                      + '<input type="hidden" name="sgst_rate" value="'+product.sgst+'">'
                    +'</td>';
            cols += '<td><span name="product_name">'+product.name+'</span><br/><span name="description">'+product.description+'</span></td>';
            cols += '<td>'+input_quantity+'</td>';
            cols += '<td  style="display:none;">' 
                      +'<span id="price_span">'
                        +'<input type="number" class="form-control text-right" name="price" step="0.01" value="'+product.price+'">'
                        +'<input type="hidden" name="cost" value="'+product.cost+'">'
                      +'</span>'
                    +'</td>';
            cols += '<td style="display:none;">'
                   + '<input type="text" name="mfg_date" value="" class="form-control datepicker" autocomplete="off">'
                   + '</td>';

            cols += '<td style="display:none;">'
                   + '<input type="text" name="expiry_date" value="" class="form-control datepicker" autocomplete="off">'
                   + '</td>';
            
            cols += '<td style="display:none;">' + select_discount + '</td>';

            cols += '<td>'+taxable_value+'</td>';
            cols += '<td class="tax_td">'+tax+tax_html+'</td>';
           
            cols += '<td>'+tax_type+'</td>';
            cols += '<td><span name="sub_total"></span></td>';
            cols += '</tr>';

            newRow.append(cols);
            $("table.product_table").append(newRow);
            $('.select2bs4').select2({theme: 'bootstrap4'});

            $('.datepicker').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
            });

            calculateRow(newRow);
      }*/

            function add_row(data)
{
    var tax_type = '<?= $tax_type ?>';
    var product = data.product;
    var discounts = data.discount;

    var price = parseFloat(product.price) || 0;
    var cgst = parseFloat(product.cgst) || 0;
    var sgst = parseFloat(product.sgst) || 0;
    var igst = parseFloat(product.igst) || 0;

    // Build discount dropdown
    var select_discount = '<select class="form-control select2bs4" name="item_discount" style="width: 100%;">';
    select_discount += '<option value="">Select</option>';
    for(a=0; a<discounts.length; a++) {
        var type_symbol = discounts[a].type == 0 ? "<?=$this->session->userdata('currency_symbol')?>" : "%";
        select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name + '(' + discounts[a].value + type_symbol + ')' + '</option>';
    }
    select_discount += '</select>';
    select_discount += '<span name="span_discount_type" class="discount_type">Discount Value : <?=$this->session->userdata('currency_symbol');?></span>';
    select_discount += '<input type="hidden" name="discount_type" value="0">';
    select_discount += '<span name="discount_amount" class="discount_amount">0.00</span>';
    select_discount += '<input type="hidden" name="discount_value" value="0">';

    var input_quantity = '<input type="number" class="form-control" name="quantity" value="1" step="1" min="1"><span name="quantity_update_message" class="quantity_update_message"></span>';
    
    // Build tax HTML
    var tax_html = '';
    if(tax_type === 'intra') {
        tax_html = '<input type="hidden" name="tax_id" value="' + product.tax_id + '">';
        tax_html += '<strong>Tax Type:</strong> Intra-State<br>';
        tax_html += 'CGST: ' + cgst + '%<br>';
        tax_html += 'SGST: ' + sgst + '%<br>';
        tax_html += '<strong>Total Tax:</strong> ' + (cgst + sgst) + '%';
    } else {
        tax_html = '<input type="hidden" name="tax_id" value="' + product.tax_id + '">';
        tax_html += '<strong>Tax Type:</strong> Inter-State<br>';
        tax_html += 'IGST: ' + igst + '%<br>';
        tax_html += '<strong>Total Tax:</strong> ' + igst + '%';
    }

    var newRow = $('<tr class="product_row">');
    var cols = "";
    cols += '<td style="width:2%">'
          + '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
          + '<input type="hidden" name="product_id" value="' + product.id + '">'
          + '<input type="hidden" name="igst_rate" value="' + igst + '">'
          + '<input type="hidden" name="cgst_rate" value="' + cgst + '">'
          + '<input type="hidden" name="sgst_rate" value="' + sgst + '">'
          + '<input type="hidden" name="tax_type" value="' + tax_type + '">'
          + '</td>';
    cols += '<td style="width:15%"><span name="product_name">' + product.name + '</span><br/><span name="description">' + (product.description || '') + '</span></td>';
    cols += '<td style="width:8%">' + input_quantity + '</td>';
    cols += '<td style="display:none"><input type="number" class="form-control text-right" name="price" step="0.01" value="' + price + '"><input type="hidden" name="cost" value="' + price + '"></td>';
    cols += '<td style="display:none">' + select_discount + '</td>';
    cols += '<td style="width:10%"><span name="taxable_value">' + price.toFixed(2) + '</span></td>';
    cols += '<td class="tax_td" style="width:15%">' + tax_html + '</td>';
    cols += '<td style="width:5%"><input type="hidden" name="tax_type" value="' + tax_type + '">';
    cols += (tax_type === 'intra') ? 'No' : 'Yes';
    cols += '</td>';
    cols += '<td style="width:10%"><span name="sub_total">0.00</span></td>';
    cols += '</tr>';

    newRow.append(cols);
    $("#product_table_body").append(newRow);
    $('.select2bs4').select2({theme: 'bootstrap4'});

    // Calculate the new row
    calculateRow(newRow);
    calculateGrandTotal();
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
function calculateRow(row) {
    var tax_type = '<?= $tax_type ?>';
    var quantity = parseFloat(row.find('input[name^="quantity"]').val()) || 0;
    var price = parseFloat(row.find('input[name^="price"]').val()) || 0;
    
    // Get the tax rates
    var cgst_rate = parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0;
    var sgst_rate = parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0;
    var igst_rate = parseFloat(row.find('input[name^="igst_rate"]').val()) || 0;

    // Calculate taxable value
    var taxable_value = quantity * price;
    
    // Calculate discount if any
    var discount_type = row.find('input[name^="discount_type"]').val();
    var discount_value = parseFloat(row.find('input[name^="discount_value"]').val()) || 0;
    var discount_amount = 0;
    
    if(discount_type == 0) {
        discount_amount = discount_value;
    } else if(discount_type == 1) {
        discount_amount = (taxable_value * discount_value) / 100;
    }
    
    var final_taxable_value = taxable_value - discount_amount;
    
    // Calculate tax amounts based on tax type
    var cgst_amount = 0;
    var sgst_amount = 0;
    var igst_amount = 0;
    var total = final_taxable_value;
    
    if(tax_type === 'intra') {
        // Intra-state: CGST + SGST
        cgst_amount = (final_taxable_value * cgst_rate) / 100;
        sgst_amount = (final_taxable_value * sgst_rate) / 100;
        total = final_taxable_value + cgst_amount + sgst_amount;
    } else {
        // Inter-state: IGST only
        igst_amount = (final_taxable_value * igst_rate) / 100;
        total = final_taxable_value + igst_amount;
    }
    
    // Update the row with calculated values
    row.find('span[name^="discount_amount"]').text(discount_amount.toFixed(2));
    row.find('span[name^="taxable_value"]').text(final_taxable_value.toFixed(2));
    row.find('span[name^="sub_total"]').text(total.toFixed(2));
    
    // Update tax display based on tax type
    if(tax_type === 'intra') {
        row.find('span[name^="c_tax"]').text(cgst_amount.toFixed(2));
        row.find('span[name^="s_tax"]').text(sgst_amount.toFixed(2));
        row.find('span[name^="i_tax"]').text('0.00');
        row.find('span[name^="cgst"]').text(cgst_rate.toFixed(2));
        row.find('span[name^="sgst"]').text(sgst_rate.toFixed(2));
        row.find('span[name^="igst"]').text('0.00');
    } else {
        row.find('span[name^="i_tax"]').text(igst_amount.toFixed(2));
        row.find('span[name^="c_tax"]').text('0.00');
        row.find('span[name^="s_tax"]').text('0.00');
        row.find('span[name^="igst"]').text(igst_rate.toFixed(2));
        row.find('span[name^="cgst"]').text('0.00');
        row.find('span[name^="sgst"]').text('0.00');
    }
}
   /*function calculateRow(row) {
    var tax_type = row.find('input[name^="tax_type"]').val();
    var quantity = parseFloat(row.find('input[name^="quantity"]').val());
    var price = parseFloat(row.find('input[name^="price"]').val());
    
    // Get the original tax rates (these should never change)
    var cgst_rate = parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0;
    var sgst_rate = parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0;
    var igst_rate = parseFloat(row.find('input[name^="igst_rate"]').val()) || 0;

    // Calculate taxable value (quantity × price)
    var taxable_value = quantity * price;

    if(tax_type == 0) { // Exclusive tax
        // Calculate tax amounts based on fixed rates
        var cgst_tax = (taxable_value * cgst_rate) / 100;
        var sgst_tax = (taxable_value * sgst_rate) / 100;
        var igst_tax = (taxable_value * igst_rate) / 100;
        
        var total = taxable_value + cgst_tax + sgst_tax + igst_tax;
                console.log("taxable_value",taxable_value);
                console.log("cgst_tax",cgst_tax);
                                console.log("sgst_tax",sgst_tax);
                console.log("igst_tax",igst_tax);

                console.log("cgst_rate",cgst_rate);

        // Update the row - keep tax rates constant, only update amounts
        row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
        row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
        row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
        // Keep the percentage displays constant
        row.find('span[name^="igst"]').text(igst_rate.toFixed(2));
        row.find('span[name^="cgst"]').text(cgst_rate.toFixed(2));
        row.find('span[name^="sgst"]').text(sgst_rate.toFixed(2));
        
        row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
        row.find('span[name^="sub_total"]').text(total.toFixed(2));
    } 
    else { 
        var total_tax_rate = cgst_rate + sgst_rate + igst_rate;
        var total_inclusive = quantity * price;
        console.log(total_inclusive);
        var taxable_value = total_inclusive / (1 + (total_tax_rate / 100));
        
        var cgst_tax = (taxable_value * cgst_rate) / 100;
        var sgst_tax = (taxable_value * sgst_rate) / 100;
        var igst_tax = (taxable_value * igst_rate) / 100;
        
        // Update the row - keep tax rates constant
        row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
        row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
        row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
        // Keep the percentage displays constant
        row.find('span[name^="igst"]').text(igst_rate.toFixed(2));
        row.find('span[name^="cgst"]').text(cgst_rate.toFixed(2));
        row.find('span[name^="sgst"]').text(sgst_rate.toFixed(2));
        
        row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
        row.find('span[name^="sub_total"]').text(total_inclusive.toFixed(2));
    }
    // Handle discount calculations
    var discount_type = row.find('input[name^="discount_type"]').val();
    var discount_value = parseFloat(row.find('input[name^="discount_value"]').val());
    var final_discount_value = 0;
    
    if(discount_type == 0) {
        final_discount_value = discount_value;
    } else {
        final_discount_value = (taxable_value * discount_value)/100;
    }
    
    row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));
}*/
      function calculateGrandTotal()
      {
        var total_taxable_value = 0.0;
        var total_cgst          = 0.0;
        var total_sgst          = 0.0;
        var total_igst          = 0.0;
        var total               = 0.0;
        var total_discount      = 0.0;

        $("#product_table_body").find('tr').each(function () {
            var tr              = $(this).closest("tr");
            total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text());
            total_discount      += parseFloat(tr.find('span[name^="discount_amount"]').text());
            total_cgst          += parseFloat(tr.find('span[name^="c_tax"]').text());
            total_sgst          += parseFloat(tr.find('span[name^="s_tax"]').text());
            total_igst          += parseFloat(tr.find('span[name^="i_tax"]').text());
            total               += parseFloat(tr.find('span[name^="sub_total"]').text()); 
        });
        var total_tax = total - total_taxable_value;
            console.log(total_tax);
            console.log(total);
            console.log(total_taxable_value);

        $('#total_taxable_value').text(total_taxable_value.toFixed(2));
        $('#t_taxable_value').val(total_taxable_value.toFixed(2));
        $('#total_discount').text(total_discount.toFixed(2));
        $('#t_discount').val(total_discount.toFixed(2));
        $('#total_tax').text(total_tax.toFixed(2));
        $('#t_tax').val(total_tax.toFixed(2));
        $('#total').text(total.toFixed(2));
        $('#t').val(total.toFixed(2));
      }
      $('#editQuotationForm').submit(function(e){
        // e.preventDefault();

        var isError = false;
        $('#quotationSubmit').text('<?=$this->lang->line("please_wait")?>');

        $('form#editQuotationForm .field_validation').each(function() {
            var id    = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if(value==null || value==""){
              $("form#editQuotationForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
              $('form#editQuotationForm #'+id).addClass('is-invalid');
              isError = true;
            }
            else
            {
              $("form#editQuotationForm #err_"+id).text("").fadeOut('slow');
              $('form#editQuotationForm #'+id).removeClass('is-invalid');
              $('form#editQuotationForm #'+id).addClass('is-valid');
            }
        });
        var productDataArray = [];

        $("#product_table_body").find('tr').each(function () {
            var tr              = $(this).closest("tr");
            var productData     = {};

            productData['product_id']       = tr.find('input[name="product_id"]').val();
            productData['warehouse_product_id'] = tr.find('input[name="warehouse_product_id"]').val();
            productData['product_name']     = tr.find('span[name="product_name"]').text();
            productData['description']      = tr.find('span[name="description"]').text();
            productData['quantity']         = tr.find('input[name="quantity"]').val();
            productData['cost']             = tr.find('input[name="cost"]').val();
            productData['price']            = tr.find('input[name="price"]').val();

            productData['mfg_date']      = tr.find('input[name="mfg_date"]').val();
            productData['expiry_date']      = tr.find('input[name="expiry_date"]').val();
            
            productData['taxable_value']    = parseFloat(productData['quantity'])*parseFloat(productData['price']);
            productData['discount_id']      = tr.find('select[name="item_discount"]').val();
            productData['discount_type']    = tr.find('input[name="discount_type"]').val();
            productData['discount_value']   = tr.find('input[name="discount_value"]').val();
            productData['discount_amount']  = tr.find('span[name="discount_amount"]').text();
            productData['tax_id']           = tr.find('input[name="tax_id"]').val();
            productData['tax_type']         = tr.find('input[name="tax_type"]').val();
            productData['igst']             = tr.find('span[name="igst"]').text();
            productData['igst_tax']         = tr.find('span[name="i_tax"]').text();
            productData['cgst']             = tr.find('span[name="cgst"]').text();
            productData['cgst_tax']         = tr.find('span[name="c_tax"]').text();
            productData['sgst']             = tr.find('span[name="sgst"]').text();
            productData['sgst_tax']         = tr.find('span[name="s_tax"]').text();
            productData['sub_total']        = tr.find('span[name="sub_total"]').text();

            productDataArray.push(JSON.stringify(productData));
        });

        if(productDataArray.length > 0)
        {
          $('#purchase_items').val(productDataArray.join('|'));
        }
        else
        {
          isError = true;
          $('#emptyQuotationItemWarningModal').modal('show');
        }

        if(isError == true)
        {
          $('#quotationSubmit').text('<?=$this->lang->line("quotation_save")?>');
          return false;
        }
        else 
        {
          return true;
        }
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