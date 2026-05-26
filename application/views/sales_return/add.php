<?php 
  $this->load->view('layout/header');
  $currency = $this->company_settings_model->get_company_records()->currency_symbol;

  $expiry_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'expiry_date', 'active',$row = true,$check_delete_status = false);

  $mfg_date = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'mfg_date', 'active',$row = true,$check_delete_status = false);

  $promotion = $this->utility_model->get_records_by_field_value('custom_field_setting','field_name', 'field_status', 'promotion', 'active',$row = true,$check_delete_status = false);
    
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

  /*.discount_type{*/
  /*  padding-left: 20px;*/
  /*}*/
  /*.discount_amount{*/
  /*  padding-right: 10px !important;*/
  /*}*/
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
                <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
                
                <li class="breadcrumb-item "><a href="<?=base_url('sales_return')?>"><?=$this->lang->line('header_sales_return')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('sales_return_add')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="addSalesreturnForm" id="addSalesreturnForm" method="POST" action="<?=base_url('sales_return/add')?>">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('sales_return_add')?></h3>
                  
                  <div class="card-tools">

                    <ul class="nav nav-pills ml-auto">
                      <li class="nav-item  ml-2">
                        <a class="nav-link reset btn-sm btn-warning" href="#" data-tt="tooltip" title="Click here to Reset Sales return Items">
                          <i class="fas fa-redo-alt"></i> Reset
                        </a>
                      </li>



                      <li class="nav-item ml-2">
                        <a class="nav-link active" href="<?=base_url('sales_return')?>" data-tt="tooltip" title="Click here to show sales return list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="card-body">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('sales_return_reference_no')?></label>
                          <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?php echo $reference_no; ?>" readonly>
                        </div>
                      </div>
                      
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('sales_return_date')?></label>
                          <input type="text" class="form-control datepicker" id="sales_return_date" name="sales_return_date" value="<?=date('d-m-Y');?>">
                          <span id="err_sales_return_date" class="error invalid-feedback"><?=form_error('sales_return_date');?></span>
                        </div>
                      </div>
                      
                      <div class="col-sm-2">
                        <div class="form-group">
                          <label><?=$this->lang->line('sales_return_invoice_no')?></label>
                          <select class="form-control field_validation select2bs4" id="invoice_no" name="invoice_no" placeholder="<?=$this->lang->line('sales_return_invoice_no')?>">
                            <option>Select Invoice</option>
                            <?php 
                              foreach ($sales as $s) 
                              {
                                $cust = $this->customer_model->get_single_record($s->customer_id);
                            ?>
                                <option value="<?=$s->reference_no?>"
                                      data-sale_id="<?=$s->id?>"
                                >
                                  <!--<?=$s->reference_no.' - '.$cust->customer_name?>-->
                                      <?=$s->reference_no.' - '.$cust->customer_company_name?>
                                </option>
                            <?php
                              }
                            ?>
                          </select>
                          <input type="hidden" name="sale_id" id="sale_id" value="">
                          <span id="err_invoice_no" class="error invalid-feedback"><?=form_error('invoice_no');?></span>
                          <span id="reference_invoice_no" class="text-danger"></span>
                        </div>
                      </div>
                      
    <div class="col-sm-3">
    <div class="form-group">
        <label for="warehouse">
            <?=$this->lang->line('sales_return_warehouse')?>
            <span class="text-danger">*</span>
        </label>
        
        <?php
            
            if ($this->permission_model->has_permission('add_warehouse')): 
        ?>
            <div class="input-group input-group-sm">
                <select class="form-control form-control-sm select2bs4 field_validation" 
                        name="warehouse_id" 
                        id="warehouse_id" 
                        width="100%" 
                        class="add-row" 
                        placeholder="<?=$this->lang->line('sales_return_warehouse')?>">
                    <option value=""><?=$this->lang->line('select')?></option>
                    <?php foreach ($warehouses as $value): ?>
                        <option value="<?=$value->id;?>" <?= set_select('warehouse_id', $value->id); ?>>
                            <?= $value->name;?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        <?php else: ?>
          
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
                    <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$user_branch_id;?>">
                    <input type="text" class="form-control form-control-sm" value="<?=$warehouse_name;?>" readonly>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <span id="err_warehouse_id" class="error invalid-feedback"><?=form_error('warehouse_id');?></span>
    </div>
</div>
                     <div class="col-sm-3">
    <div class="form-group">
        <label for="customer">
            <?=$this->lang->line('sales_return_customer')?>
            <span class="text-danger">*</span>
        </label>
        <div class="input-group input-group-sm">
          <select class="form-control form-control-sm select2bs4 field_validation" 
        name="customer_id" 
        id="customer_id" 
        width="100%" 
        class="add-row" 
        placeholder="<?= $this->lang->line('sales_return_customer') ?>">

    <option value=""><?= $this->lang->line('select') ?></option>

    <!--<?php if(!empty($customers)): ?>-->
    <!--    <?php foreach ($customers as $value): ?>-->
    <!--        <option value="<?= $value->id; ?>" data-ledger_id="<?= $value->ledger_id ?>" <?= set_select('customer_id', $value->id); ?>>-->
    <!--            <?= $value->customer_name; ?>-->
    <!--        </option>-->
    <!--    <?php endforeach; ?>-->
    <!--<?php endif; ?>-->
    
    <?php if(!empty($customer)): ?>
        <?php foreach ($customer as $value): ?>
            <!--<option value="<?= $value->id; ?>"><?= $value->customer_name; ?></option>-->
            <option value="<?= $value->id; ?>"><?= $value->customer_company_name; ?></option>
        <?php endforeach; ?>
    <?php endif; ?>
</select>

            <span class="input-group-append">
                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_customer_modal" data-tt="tooltip" accesskey="c">
                    <i class="fas fa-plus"></i>
                </button>
            </span>
        </div>
        
        <!-- Hidden inputs for GSTIN, State ID, and Country ID -->
        <input type="hidden" name="customer_gstin" id="customer_gstin" value="">
        <input type="hidden" name="rcm" id="rcm" value="N">
        <input type="hidden" name="customer_state_id" id="customer_state_id" value="">
        <input type="hidden" name="customer_country_id" id="customer_country_id" value="">
        
        <span id="err_customer_id" class="error invalid-feedback"><?= form_error('customer_id'); ?></span>
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
                              
                              <input type="hidden" value="" name="document[]" id="document">
                            </div>
                            <span id="fileTypeError" class="error-message"></span> 
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 ">
                        <div class="well">
                          <div class="row" style="display: none">
                            <div class="col-sm-12">
                              <a href="" data-toggle="modal" data-tt="tooltip" data-target="#add_product_modal" class="float-right"><?=$this->lang->line('sales_return_add_new_product')?></a>
                            </div>
                            <div class="col-sm-12 search">
                              <span class="fa fa-search"></span>
                              <input id="search_product" class="form-control search_product"  type="text" name="search_product"  placeholder="<?=$this->lang->line('sales_return_item_search')?>">
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
                          <label><?=$this->lang->line('sales_return_items')?></label>
                          <!--<table class="table items table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">-->
                            <table class="table items table-striped table-bordered table-condensed table-hover product_table" 
                               name="product_data" id="product_data" 
                               style="table-layout: fixed; width: 100%;">
                            <thead>
                              <tr>
                                <th width="4%">
                                  <img src="<?php  echo base_url(); ?>assets/images/bin1.png" />
                                </th>
                                <th class="span2" width="15%"><?=$this->lang->line('product_description')?></th>
                                <th class="span2" width="8%"><?=$this->lang->line('sales_return_qty')?></th>
                                <th class="span2 <?= (empty($promotion) || $promotion->field_status !== 'active') ? 'd-none' : '' ?>" width="10%"><?=$this->lang->line('proforma_invoice_free_qty')?></th>
                                <th class="span2" width="12%"><?=$this->lang->line('sales_return_price')?></th>
                                <th class="span2" width="10%"><?=$this->lang->line('proforma_invoice_batch')?></th>
                                <th class="span2 <?= (empty($mfg_date) || $mfg_date->field_status !== 'active') ? 'd-none' : '' ?>" width="10%">
                                  <?= $this->lang->line('product_mfg_date') ?>
                                </th>
                                <th class="span2 <?= (empty($expiry_date) || $expiry_date->field_status !== 'active') ? 'd-none' : '' ?>" width="10%">
                                  <?= $this->lang->line('product_expiry_date') ?>
                                </th>
                                <th class="span2" width="14%"><?=$this->lang->line('sales_return_discount')?></th>
                                <th class="span2" width="8%"><?=$this->lang->line('sales_return_uom')?></th>
                                <th class="span2" width="8%"><?=$this->lang->line('sales_return_taxable_value')?></th>
                                <th class="span2" width="13%"><?=$this->lang->line('sales_return_tax')?></th>
                                <th class="span2" width="10%"><?=$this->lang->line('sales_return_total')?></th>
                              </tr>
                            </thead>
                            <tbody id="product_table_body">
                            </tbody>
                          </table>
                          <table class="table table-striped table-bordered table-condensed table-hover total_data">
                            <tr>
                              <td align="right" colspan="8"> <?=$this->lang->line('sales_return_total_discount')?>(<?=$currency?>)</td>
                              <td align='right' class="text-danger">
                                -<span id="total_discount">0.00</span>
                                <input type="hidden" name="total_discount" id="t_discount" value="0">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="8"><?=$this->lang->line('sales_return_total_taxable_value')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success">
                                +<span id="total_taxable_value">0.00</span>
                                <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="0">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="8"><?=$this->lang->line('sales_return_total_tax')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success">
                                +<span id="total_tax">0.00</span>
                                <input type="hidden" name="total_tax" id="t_tax" value="0">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="8"><?=$this->lang->line('sales_return_total')?>(<?=$currency?>)</td>
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
                              <ul class="nav nav-tabs" id="custom-content-below-tab" role="tablist">
                                <li class="nav-item">
                                  <a class="nav-link active" href="#external_note" data-toggle="pill"><?php echo $this->lang->line('sales_return_external_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#internal_note" data-toggle="pill"><?php echo $this->lang->line('sales_return_internal_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#terms_and_condition" data-toggle="pill"><?php echo $this->lang->line('sales_return_terms_and_condition'); ?></a>
                                </li>
                              </ul>                           
                              <br>
                              <div class="tab-content">
                                <div class="tab-pane active" id="external_note">
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('sales_return_external_note'); ?>"></textarea>
                                </div>
                                <div class="tab-pane" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('sales_return_internal_note'); ?>"></textarea>
                                </div>
                                <div class="tab-pane" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('sales_return_terms_and_condition');?>"><?=str_replace("<br />","",$company_setting->terms_and_condition)?></textarea>
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
                 
                  <input type="hidden" name="sales_return_items" id="sales_return_items" value="">
                  <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  <button type="submit" name="submit" id="sales_returnSubmit" class="btn btn-info"><?=$this->lang->line('sales_return_add')?></button>
                  <!-- <span class="text-sm">(<?=$this->lang->line('enter_shift')?>)</span> -->
                  <!-- <button type="submit" name="submit" id="sales_returnSubmitPayNow" value="pay" name="pay" class="btn btn-info">Add sales_return & Pay Now</button>                      -->
                  <span class="btn btn-default float-right" id="cancel" onclick="cancel('sales_return')"><?=$this->lang->line('sales_return_cancel')?></span>
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
 /* $this->load->view('warehouse/add_warehouse_modal');*/
  $this->load->view('customer/add_customer_modal');
  /*$this->load->view('sale/view_sale_detail_modal');*/
?>

<div class="modal fade" id="emptySalesreturnItemWarningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header warning-header">
        <h5 class="modal-title" id="exampleModalLabel"><?=$this->lang->line('message')?><essage/h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?=$this->lang->line('empty_sales_return_warning_label')?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="view_sale_detail_modal">
  <div class="modal-dialog modal-lg">
    <form name="saleItemsReturnForm" id="saleItemsReturnForm" method="POST">
      <div class="modal-content">
        <div class="modal-header info-header">
          <h5 class="modal-title" >Sale Products</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body" id="view_sale_items">
         
        </div>
        <div class="modal-footer">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <button type="submit" class="btn btn-primary" name="saleItemsReturnSubmit" id="saleItemsReturnSubmit" value="submit">Add to Sale Return</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
      </div>
    </form>
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
            <?php echo "Are you sure want to reset this sales return ?";?>
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

<!--  -->


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


      $("#warehouse_id").closest('.row').siblings().css('display','none');

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
                $("#product_table_body tr").remove();
                calculateGrandTotal();

              }
          });
        }
      });
      $('#customer_id').change(function(e){

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
                $('#customer_gstin').val(customer.gstin);

                $("#product_table_body tr").remove();
                calculateGrandTotal();

              }
          });
        }
      });

      // product search code begin

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
                'module': '<?=SALE_RETURN_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var products = data;
                var suggestions = [];
                for(var i = 0; i < products.length; ++i) {
                  suggestions.push(products[i].warehouse_products_id+' - '+products[i].name+' - '+products[i].product_category_name+' - '+products[i].selling_price);
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
              data:{
                'product_id': warehouse_products_id, 
                'module': '<?=SALE_RETURN_MODULE?>',
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

                $('.datepicker1').datepicker({
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

      function add_row(data,sold_quantity = null,free_quantity = null,product_name = null, description = null, return_quantity = null,cost = null,price = null,selling_price = null)
      {
        var customer_country_id = $('#customer_country_id').val();
        var customer_state_id   = $('#customer_state_id').val();
        var customer_gstin      = $('#customer_gstin').val();

        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var company_gstin       = '<?=$company_setting->gstin?>';

        var product   = data.product;
        var discounts = data.discount;
        var uqc       = data.uqc;

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

        var max_input_quantity = (sold_quantity == null) ? product.quantity : sold_quantity;
        var input_quantity =  '<input type="number" class="form-control" name="quantity" value="'+return_quantity+'" step="1" min="1" max="'+max_input_quantity+'"><span name="quantity_update_message" class="quantity_update_message"></span>';
        var taxable_value  =  '<span name="taxable_value">'+product.selling_price+'</span>';

        /************    tax begin   *****************/
        var tax = '<input type="hidden" name="tax_id" value="'+product.tax_id+'">';

        if(company_country_id == customer_country_id)
        {
          if(company_state_id == customer_state_id)
          {
            tax += 'CGST : <span name="c_tax">'+0.0+'</span><br/>';
            tax += 'SGST : <span name="s_tax">'+0.0+'</span>';
            tax += '<span name="i_tax"style="display:none">0</span><span name="igst" style="display:none">0</span>';
          }
          else
          {
            
            tax += 'IGST : <span name="i_tax">'+0.0+'</span><br/>';
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

        // var tax_type  = '<input type="hidden" name="tax_type" value="'+product.tax_type+'">';
        // tax_type      += (product.tax_type == 0) ? "No" : "Yes";
        var tax_type  = '<input type="hidden" name="tax_type" value="'+product.tax_type+'">';

        /************    tax type end   *****************/

        var free_quantity = '<input type="number" class="form-control" name="free_quantity" value="' + free_quantity + '" max="' + free_quantity + '"  min="0" step="any">';

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
        cols += '<td>'
      + '<img src="<?= base_url('assets/product_images').'/'; ?>'+product.product_image+'" alt="'+product.name+'" style="width:40px; height:40px; object-fit:cover; margin-right:5px; border-radius:4px;">'
      + '<span name="product_name">'+product.name+'</span>'
      + '</td>';

            cols += '<td>'+input_quantity+'</td>';
            cols += '<td class="<?php echo empty($promotion) ? 'd-none' : ''; ?>">'+free_quantity+'</td>';
            cols += '<td>' 
                      +'<span id="cost_span">'
                        +'<input type="number" class="form-control text-right" name="cost" step="0.01" value="'+ selling_price +'" min="0.01">'
                        +'<input type="hidden" name="selling_price" value="'+ selling_price +'">'
                        +'<input type="hidden" name="price" value="'+ selling_price +'">'
                      +'</span>'
                    +'</td>';
            cols += '<td><span name="">'+product.batch_no+'</span></td>';
            cols += '<td class="<?php echo empty($mfg_date) ? 'd-none' : ''; ?>">'
                      +'<input type="text" name="mfg_date" value="" class="form-control datepicker1" autocomplete="off">'
                    +'</td>';
            cols += '<td class="<?php echo empty($expiry_date) ? 'd-none' : ''; ?>">'
                      +'<input type="text" name="expiry_date" value="" class="form-control datepicker1" autocomplete="off">'
                    +'</td>';
            cols += '<td>'+select_discount+'</td>';
            cols += '<td>'+input_uom+'</td>';
            cols += '<td>'+taxable_value+'</td>';
            // cols += '<td>'+tax+'</td>';
           cols += '<td>' + tax + tax_type + '</td>';
            // cols += '<td>'+tax_type+'</td>';
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
      }

      function is_product_exist_in_row(product_id)
      {
        var isproductExist = false;
        $("#product_table_body").find('tr').each(function () {

          var tr = $(this).closest("tr");

          if(tr.find('input[name^="warehouse_products_id"]').val() == product_id)
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

          if(tr.find('input[name^="warehouse_products_id"]').val() == product_id)
          {
            tr.addClass('highlight_row');

            var existing_quantity = +tr.find('input[name^="quantity"]').val();
            alert(existing_quantity + 1);
            tr.find('input[name^="quantity"]').val(existing_quantity + 1).trigger('change');
            tr.find('span[name^="quantity_update_message"]').text('+1');

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

                tr.find('input[name="discount_type"]').val(discount.type);
                tr.find('input[name="discount_value"]').val(discount.value);
                
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
          var price       = parseFloat(row.find('input[name^="price"]').val());
          var final_discount_value = 0;

          var taxable_value   = parseFloat(quantity * price);

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

          var sub_total       = parseFloat(taxable_value + igst_tax + cgst_tax + sgst_tax);

          row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
          row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
          row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
          row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

          row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
          row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));  
        }
        else
        {
          var final_discount_value = 0;
          var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
          var price       = parseFloat(row.find('input[name^="price"]').val());

          var discount_type   = row.find('input[name^="discount_type"]').val();
          var discount_value  = parseFloat(row.find('input[name^="discount_value"]').val()); 

          var sub_total       = (quantity * price);

          if(discount_type == 0)
          {
            final_discount_value = discount_value;
          }
          else
          {
            final_discount_value = (sub_total * discount_value)/100;
          }

          sub_total = sub_total - final_discount_value;

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

        $("#product_table_body").find('tr').each(function () {
            var tr              = $(this).closest("tr");
            total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text());
            total_discount      += parseFloat(tr.find('span[name^="discount_amount"]').text());
            total_cgst          += parseFloat(tr.find('span[name^="c_tax"]').text());
            total_sgst          += parseFloat(tr.find('span[name^="s_tax"]').text());
            total_igst          += parseFloat(tr.find('span[name^="i_tax"]').text());
            total               += parseFloat(tr.find('span[name^="sub_total"]').text()); 
        });

        $('#total_taxable_value').text(total_taxable_value.toFixed(2));
        $('#t_taxable_value').val(total_taxable_value.toFixed(2));

        $('#total_discount').text(total_discount.toFixed(2));
        $('#t_discount').val(total_discount.toFixed(2));

        $('#total_tax').text((total_cgst+total_sgst+total_igst).toFixed(2));
        $('#t_tax').val((total_cgst+total_sgst+total_igst).toFixed(2));

        $('#total').text(total.toFixed(2));
        $('#t').val(total.toFixed(2));
      }

      $('#addSalesreturnForm').submit(function(e){
        // e.preventDefault();


        // if(!(e.keyCode == 13 && e.shiftKey)) {

        //   alert(e.keyCode);
        //   alert(e.shiftKey);
        //   return false;
        // }
      
        var isError = false;
        $('#sales_returnSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $('form#addSalesreturnForm .field_validation').each(function() {
            var id    = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if(value==null || value==""){
              $("form#addSalesreturnForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
              $('form#addSalesreturnForm #'+id).addClass('is-invalid');
              isError = true;
            }
            else
            {
              $("form#addSalesreturnForm #err_"+id).text("").fadeOut('slow');
              $('form#addSalesreturnForm #'+id).removeClass('is-invalid');
              $('form#addSalesreturnForm #'+id).addClass('is-valid');
            }
        });

        var productDataArray = [];

        $("#product_table_body").find('tr').each(function () {
            var tr              = $(this).closest("tr");
            var productData     = {};

            productData['warehouse_product_id']   = tr.find('input[name="warehouse_product_id"]').val();
            productData['product_id']       = tr.find('input[name^="product_id"]').val();
            productData['product_name']     = tr.find('span[name^="product_name"]').text();
            productData['description']      = tr.find('span[name^="description"]').text();
            productData['quantity']         = tr.find('input[name^="quantity"]').val();
            productData['uom_id']           = tr.find('input[name^="uom_id"]').val();
            productData['uom_name']         = tr.find('input[name^="uom_id"]').data('uom_name');
            productData['uom_uom']          = tr.find('input[name^="uom_id"]').data('uom_uom');
            productData['cost']             = tr.find('input[name^="cost"]').val();
            productData['selling_price']    = tr.find('input[name="selling_price"]').val();
            productData['price']            = tr.find('input[name="price"]').val();

            productData['mfg_date']      = tr.find('input[name="mfg_date"]').val();
            productData['expiry_date']      = tr.find('input[name="expiry_date"]').val();
            productData['taxable_value']    = tr.find('span[name^="taxable_value"]').text();
            productData['discount_id']      = tr.find('select[name^="item_discount"]').val();
            productData['discount_type']    = tr.find('input[name^="discount_type"]').val();
            productData['discount_value']   = tr.find('input[name^="discount_value"]').val();
            productData['discount_amount']  = tr.find('span[name^="discount_amount"]').text();
            productData['tax_id']           = tr.find('input[name^="tax_id"]').val();
            productData['tax_type']         = tr.find('input[name^="tax_type"]').val();
            productData['igst']             = tr.find('span[name^="igst"]').text();
            productData['igst_tax']         = tr.find('span[name^="i_tax"]').text();
            productData['cgst']             = tr.find('span[name^="cgst"]').text();
            productData['cgst_tax']         = tr.find('span[name^="c_tax"]').text();
            productData['sgst']             = tr.find('span[name^="sgst"]').text();
            productData['sgst_tax']         = tr.find('span[name^="s_tax"]').text();
            productData['sub_total']         = tr.find('span[name^="sub_total"]').text();

            productData['free_quantity']          = tr.find('input[name="free_quantity"]').val();

            productDataArray.push(JSON.stringify(productData));
        });

        if(productDataArray.length > 0)
        {
          $('#sales_return_items').val(productDataArray.join('|'));
        }
        else
        {
          isError = true;
          $('#emptySalesreturnItemWarningModal').modal('show');
        }

        // return false;

        if(isError == true)
        {
           $('#sales_returnSubmit').text('<?=$this->lang->line("sales_return_add")?>').removeAttr('disabled');
           return false;
        }
          
        else 
        {
          return true;
        }
      });

      $("form#addSalesreturnForm .field_validation").on("blur change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addSalesreturnForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addSalesreturnForm #'+id).removeClass('is-valid');
          $('form#addSalesreturnForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addSalesreturnForm #err_"+id).text("").fadeOut('slow');
          $('form#addSalesreturnForm #'+id).removeClass('is-invalid');
          $('form#addSalesreturnForm #'+id).addClass('is-valid');
        }
      });

     /* $('.rcm_btn').click(function(e){
        e.preventDefault();

        if($('#product_table_body tr').length > 0)
        {
          $('.rcm-confirmation-body').html('<?=$this->lang->line('sales_return_rcm_confirmation_message')?>');

          $('#rcm-confirmation').modal({
              backdrop: 'static',
              keyboard: false
          }).on('click', '#rcm-confirmation-confirm', function(e) {

              var rcm = $('#rcm').val();

              if(rcm == 'N')
              {
                $('#rcm').val('Y');
                $('.rcm_btn').text('<?=$this->lang->line('sales_return_disable_rcm')?>');
              }
              else
              {
                $('#rcm').val('N');
                $('.rcm_btn').text('<?=$this->lang->line('sales_return_enable_rcm')?>');
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
            $('.rcm_btn').text('<?=$this->lang->line('sales_return_disable_rcm')?>');
          }
          else
          {
            $('#rcm').val('N');
            $('.rcm_btn').text('<?=$this->lang->line('sales_return_enable_rcm')?>');
          }  
        }
      });*/

      /*$("#invoice_no").on('change','input[name^="invoice_no]',function(){

        alert();
      });*/
     /* $('#invoice_no').change(function(){
        alert();

      });*/
      $('#invoice_no').on('change',function(){

        var reference_no = $('#invoice_no').val();

        var sale_id      = $('#invoice_no').find('option:selected').data('sale_id');

         console.log("DEBUG: Invoice selected: " + reference_no); // LOG 1

        $('#sale_id').val(sale_id);

        if(reference_no != '')
        {
          $.ajax({
            url: "<?php echo base_url(); ?>sale/get_record_detail_by_reference_no",
            method: "POST",
            dataType:'json',
            data: {
              'reference_no':reference_no,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(data){
             console.log("DEBUG: Data received from server:", data); // LOG 2
            //   if(data.code == 1)
            //   {
            //     $('#reference_invoice_no').text('invoice no is not available');
            //   }
            //   else
            //   {
            //     var sale    = data.sale;
            //     $('#reference_invoice_no').html('<a href=""  data-toggle="modal" data-tt="tooltip" data-target="#view_sale_detail_modal" data-sale_id="'+sale.id+'" id="open_view_sale_detail_modal">Click here to show details of Sale</a>');
            //     $('#customer_id').val(sale.customer_id).trigger('change');
            //     $('#warehouse_id').val(sale.warehouse_id).trigger('change');
            //     $('#open_view_sale_detail_modal').trigger('click');
            //   }
            
             if (data.sale) {
                    var sale = data.sale;
                    // $('#reference_no').val(sale.reference_no); 

                    // 1. Auto-select Branch & Trigger Update
                    $('#warehouse_id').val(sale.warehouse_id).trigger('change');
                    
                    // 2. Auto-select Customer & Trigger Update
                    $('#customer_id').val(sale.customer_id).trigger('change');
                    
                    // 3. Update the text link for details
                    $('#reference_invoice_no').html('<a href="javascript:void(0)" data-toggle="modal" data-target="#view_sale_detail_modal" data-sale_id="' + sale.id + '" id="open_view_sale_detail_modal">Click here to select items from Sale</a>');
                    
                    // 4. Automatically open the product selection modal
                    setTimeout(function() {
                        $('#open_view_sale_detail_modal').trigger('click');
                    }, 500);
                }
            }
          });
        }
      });

      $('#view_sale_detail_modal').on('shown.bs.modal', function (e) {
        var sale_id = $(e.relatedTarget).data('sale_id');

        // alert(sale_id);

        

        $.ajax({
            url: "<?php echo base_url('sales_return/get_sale_items_by_sale_id')?>",
            type: "POST",
            dataType: "JSON",
            data: {
              'sale_id': sale_id,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(data){
              $("#view_sale_items").html(data.view_sale_items);

              

              // select all sales return items

              $('#selectAll').on('click',function(){
                if(this.checked)
                {
                    $('.checkbox').each(function(){
                        this.checked = true;
                    });
                }
                else
                {
                     $('.checkbox').each(function(){
                        this.checked = false;
                    });
                }
              });
            }
          });
      });

      $(document).on('change','input[name="return_quantity"]',function(event){
        
        if($(this).val() > 0)
        {
          var tr = $(this).closest("tr");
          tr.find('.checkbox').prop('checked',true);  
        }
      });


    //   $('#view_sale_detail_modal').on('hide.bs.modal', function (e) {
    //     var existing_product_array = new Array();

    //     $("#existing_sale_items").find('tr').each(function () {
    //       var tr  = $(this).closest("tr");

    //       if(tr.find('.checkbox').is(':checked'))
    //       {
    //         var warehouse_product_id  = tr.find('input[name="warehouse_product_id"]').val();
    //         var return_quantity       = tr.find('input[name="return_quantity"]').val();
    //         var sold_quantity         = tr.find('input[name="sold_quantity"]').val();
    //         var product_name          = tr.find('input[name="product_name"]').val();
    //         var description           = tr.find('input[name="description"]').val();
    //         var cost                    = tr.find('input[name="cost"]').val();
    //         var selling_price           = tr.find('input[name="selling_price"]').val();
    //         var price                   = tr.find('input[name="price"]').val();

    //         var free_quantity           = tr.find('input[name="free_quantity"]').val();

    //         $.ajax({
    //           url: "<?php echo base_url('product/get_record_detail') ?>",
    //           type: "POST",
    //           dataType: "json",
    //           data:{
    //             'product_id': warehouse_product_id, 
    //             'module': '<?=SALE_RETURN_MODULE?>',
    //             '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
    //           },
    //           success: function(data){
    //             var product = data.product;

    //             if(!is_product_exist_in_row(product.warehouse_products_id))
    //             {
    //               add_row(data,sold_quantity,free_quantity,product_name,description,return_quantity,cost,price,selling_price);
    //             }
    //             else
    //             {
    //               highlight_row(product.warehouse_products_id);
    //             }

    //             $('.datepicker').datepicker({
    //                 weekStart: 1,
    //                 daysOfWeekHighlighted: "6,0",
    //                 autoclose: true,
    //                 todayHighlight: true,
    //                 format: 'dd-mm-yyyy'
    //             });

                
    //             $("#customer_id").closest('.row').siblings().fadeIn(10);
    //             // $('#search_product').val('').focus();
    //             calculateGrandTotal();
    //           }
    //         });  
    //       }
    //     });
    //   });

    //   // Submit Sales return items

    //   $('#saleItemsReturnForm').submit(function(e){
    //     e.preventDefault();
    //     $('#view_sale_detail_modal').modal('hide');
    //   });
// Change your Submit handler to this:
    $('#saleItemsReturnForm').submit(function(e){
        e.preventDefault();
        
        // Process the selected items ONLY when the user clicks SUBMIT
        $("#existing_sale_items").find('tr').each(function () {
            var tr = $(this).closest("tr");
            var return_quantity = tr.find('input[name="return_quantity"]').val();
    
            // Check if checkbox is checked AND quantity is valid
            if(tr.find('.checkbox').is(':checked') && return_quantity > 0)
            {
                var warehouse_product_id  = tr.find('input[name="warehouse_product_id"]').val();
                var sold_quantity         = tr.find('input[name="sold_quantity"]').val();
                var product_name          = tr.find('input[name="product_name"]').val();
                var description           = tr.find('input[name="description"]').val();
                var cost                  = tr.find('input[name="cost"]').val();
                var selling_price         = tr.find('input[name="selling_price"]').val();
                var price                 = tr.find('input[name="price"]').val();
                var free_quantity         = tr.find('input[name="free_quantity"]').val();
    
                $.ajax({
                    url: "<?php echo base_url('product/get_record_detail') ?>",
                    type: "POST",
                    dataType: "json",
                    data:{
                        'product_id': warehouse_product_id, 
                        'module': '<?=SALE_RETURN_MODULE?>',
                        '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                    },
                    success: function(data){
                        var product = data.product;
                        if(!is_product_exist_in_row(product.warehouse_products_id)) {
                            add_row(data,sold_quantity,free_quantity,product_name,description,return_quantity,cost,price,selling_price);
                        } else {
                            highlight_row(product.warehouse_products_id);
                        }
                        calculateGrandTotal();
                        $("#customer_id").closest('.row').siblings().fadeIn(10);
                    }
                });  
            }
        });
    
        // Now hide the modal
        $('#view_sale_detail_modal').modal('hide');
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

        var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
        var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

        // Add the CSRF token to the form data
        formData.append(csrfTokenName, csrfTokenValue);

        // Perform the AJAX request to the server
        $.ajax({
            url: "<?php echo base_url('sales_return/upload_documents')?>", // Replace with your server-side script URL
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



