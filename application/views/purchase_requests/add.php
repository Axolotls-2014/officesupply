<?php 
//   ini_set('display_errors', 1);
// error_reporting(E_ALL);

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

  .delete_item{
    cursor:pointer;
  }

  .total_data{
    font-size: 18px;
    font-weight: bolder;
  }
  
   /* bottom search styles to match top search */
  #bottom_search_wrapper {
    margin-top: 20px;
  }
  #bottom_search_product {
    background-color: #F8F8F8;
    height: 35px;
    padding-left: 25px;
    font-size: 18px;
  }


</style>
  <div class="wrapper">
    <div class="content-wrapper">
        
         <!-- Cart Button -->
    <div class="p-2">
      <a href="<?= base_url('Purchase_request/purchase_with_cart') ?>" class="btn btn-warning">
        <i class="fas fa-shopping-cart text-secondary"></i> Add With Cart
      </a>
    </div>
        
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
              
                <li class="breadcrumb-item "><a href="<?=base_url('purchase')?>"><?=$this->lang->line('header_purchase')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('purchase_add')?></li>
              </ol>
            </div>
          </div>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="addPurchaseForm" id="addPurchaseForm" method="POST" action="<?=base_url('Purchase_request/add')?>">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('purchase_add')?></h3>
                  <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                      <li class="nav-item ml-2">
                        <a class="nav-link active" href="<?=base_url('purchase_request')?>" data-tt="tooltip" title="Click here to show purchase list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                      </li>
                    </ul>
                  </div>
                </div>
                <div class="card-body">
                  <div class="col-sm-12">
             <div class="row">
                    <?php
                    $this->load->helper('common');
                    $next_invoice_no = get_next_sale_reference(true); 
                    ?>
                
                    <!-- Invoice No -->
                    <!-- Invoice No -->
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label><?= $this->lang->line('purchase_invoice_no') ?></label>
                            <input type="text" class="form-control" id="invoice_no" name="invoice_no"
                                   value="Auto Generated"
                                   placeholder="<?= $this->lang->line('purchase_invoice_no') ?>" readonly>
                        </div>
                    </div>

                
                    <!-- Purchase Order Date -->
                    <div class="col-sm-3">
                        <div class="form-group">
                            <label><?=$this->lang->line('purchase_order_date')?></label>
                            <input type="text" class="form-control datepicker" id="invoice_date" name="invoice_date" 
                                   value="<?=date('d-m-Y');?>">
                            <span id="err_purchase_date" class="error invalid-feedback"><?=form_error('invoice_date');?></span>
                        </div>
                    </div>
                
                    <!-- Due Date (hidden) -->
                    <div class="col-sm-2" style="display:none;">
                        <div class="form-group">
                            <label><?=$this->lang->line('sale_due_date')?></label>
                            <input type="text" class="form-control datepicker" readonly id="due_date" name="due_date" value="">
                        </div>
                    </div>
                
                  <?php if(in_array($this->session->userdata('level_id'), [0, 1, 2])): ?>
                        <!-- Order For -->
                        <div class="col-md-2">
                            <label class="d-block">Order For</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="order_for" id="order_self" value="self" checked>
                                <label class="form-check-label" for="order_self">Self</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="order_for" id="order_behalf" value="behalf">
                                <label class="form-check-label" for="order_behalf">Behalf</label>
                            </div>
                        </div>
                
                        <!-- Branch -->
                        <div class="col-md-2" id="branch_div" style="display:none;">
                            <label>Select Branch</label>
                            <select class="form-control" name="behalf_branch_id" id="branch_id">
                                <option value="">-- Select Branch --</option>
                            </select>
                        </div>
                
                        <!-- User -->
                        <div class="col-md-2" id="behalf_user_div" style="display:none;">
                            <label>Select User</label>
                            <select class="form-control" name="behalf_user_id" id="behalf_user_id">
                                <option value="">-- Select User --</option>
                            </select>
                        </div>
                    <?php endif; ?>
                </div>

                    
                     <div class="row">
                      <div class="col-sm-12 ">
                        <div class="well">
                          <div class="row">
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
                          <table class="table items table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
                            <thead>
                              <tr>
                                <th width="2%">
                                  <img src="<?php  echo base_url(); ?>assets/images/bin1.png" />
                                </th>
                                <th class="span2" width="13%">Product</th>
                                <th width="13%">Add Remark</th>
                                <th class="span2" width="7%"><?=$this->lang->line('purchase_qty')?></th>
                                <th style="display:none;" class="span2 <?= (empty($promotion) || $promotion->field_status !== 'active') ? 'd-none' : '' ?>" width="10%"><?=$this->lang->line('proforma_invoice_free_qty')?></th>
                                <th class="span2" width="10%" style="display: none;"><?=$this->lang->line('purchase_cost')?></th>
                                <th class="span2" style="display:none;" width="10%"><?=$this->lang->line('product_selling_price')?></th>
                                <th class="span2" style="display:none;" width="10%"><?=$this->lang->line('purchase_price')?></th>
                                <th class="span2" width="14%" style="display: none;"><?=$this->lang->line('purchase_discount')?></th>
                                <th class="span2" width="6%"><?=$this->lang->line('purchase_uom')?></th>
                                <th class="span2" width="8%"><?=$this->lang->line('purchase_taxable_value')?></th>
                                <th class="span2" width="10%"><?=$this->lang->line('purchase_tax')?></th>
                                <th class="span2" width="7%"><?=$this->lang->line('purchase_total')?></th>
                              </tr>
                            </thead>
                            <tbody id="product_table_body">
                            </tbody>
                          </table>
                           <!-- ====== INSERT BOTTOM SEARCH WRAPPER HERE (hidden by default) ====== -->
                          <div id="bottom_search_wrapper" style="margin-top:20px; display:none;">
                              <div class="search">
                                  <span class="fa fa-search"></span>
                                  <input id="bottom_search_product" class="form-control"  type="text" name="bottom_search_product"  placeholder="Search product (bottom)">
                              </div>
                          </div>
                          <!-- =================================================================== -->
                          <table class="table table-striped table-bordered table-condensed table-hover total_data">
                            <tr>
                              <td align="right" colspan="8"><?=$this->lang->line('purchase_total_taxable_value')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success">
                                +<span id="total_taxable_value">0.00</span>
                                <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="0">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="8"><?=$this->lang->line('purchase_total_tax')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success">
                                +<span id="total_tax">0.00</span>
                                <input type="hidden" name="total_tax" id="t_tax" value="0">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="8"><?=$this->lang->line('purchase_total')?>(<?=$currency?>)</td>
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
                                  <a class="nav-link active" href="#external_note" data-toggle="pill"><?php echo $this->lang->line('purchase_external_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#internal_note" data-toggle="pill"><?php echo $this->lang->line('purchase_internal_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#terms_and_condition" data-toggle="pill"><?php echo $this->lang->line('purchase_terms_and_condition'); ?></a>
                                </li>
                              </ul>                           
                              <br>
                              <div class="tab-content">
                                <div class="tab-pane active" id="external_note">
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('purchase_external_note'); ?>"></textarea>
                                </div>
                                <div class="tab-pane" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('purchase_internal_note'); ?>"></textarea>
                                </div>
                                <div class="tab-pane" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('purchase_terms_and_condition');?>"><?=str_replace("<br />","",$company_settings->terms_and_condition)?></textarea>
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
                  <input type="hidden" name="purchase_items" id="purchase_items" value="">
                  <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  <button type="submit" name="submit" id="purchaseSubmit" class="btn btn-info"><?=$this->lang->line('purchase_add')?></button>
                  <!-- <span class="text-sm">(<?=$this->lang->line('enter_shift')?>)</span> -->
                  <!-- <button type="submit" name="submit" id="purchaseSubmitPayNow" value="pay" name="pay" class="btn btn-info">Add purchase & Pay Now</button>     -->
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
  $this->load->view('supplier/add_supplier_modal');
?>
<script src="https://cdn.jsdelivr.net/npm/disableautofill/src/jquery.disableAutoFill.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/disableautofill/src/jquery.disableAutoFill.min.js"></script>

<script type="text/javascript">
$(document).ready(function() {
    // Load allowed branches first
    $.ajax({
        url: "<?= base_url('purchase_request/get_branches_for_user') ?>",
        type: "GET",
        dataType: "json",
        success: function(data) {
            $.each(data, function(key, branch) {
                $('#branch_id').append('<option value="'+branch.id+'">'+branch.branch_name+'</option>');
            });
        }
    });

    // Then when branch changes → load users
    $('#branch_id').on('change', function() {
        var branch_id = $(this).val();
        if(branch_id) {
            $.ajax({
                url: "<?= base_url('purchase_request/get_users_by_branch') ?>",
                type: "POST",
                data: {branch_id: branch_id},
                dataType: "json",
                success: function(data) {
                    $('#behalf_user_id').empty().append('<option value="">-- Select User --</option>');
                    $.each(data, function(key, user) {
                        $('#behalf_user_id').append('<option value="'+user.id+'">'+user.first_name+'</option>');
                    });
                }
            });
        } else {
            $('#behalf_user_id').empty().append('<option value="">-- Select User --</option>');
        }
    });
});


$(document).ready(function(){
    $('input[name="order_for"]').on('change', function(){
        if($(this).val() === 'behalf'){
            $('#behalf_user_div').show();
            $('#branch_div').show(); // 👈 show select branch also
        } else {
            $('#behalf_user_div').hide();
            $('#branch_div').hide(); // 👈 hide when not behalf
            $('#behalf_user_id').val('');
            $('#branch_id').val(''); // reset branch selection if needed
        }
    });
});

  $(document).ready(function(e){
    const taxToast = Swal.mixin({
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

    $(document).on('click', ".add_warehouse_modal" ,function(){

     $.ajax({
        url: "<?php echo base_url('warehouse/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_warehouse_modal').find('.modal-content').html(data.add_warehouse_modal_body);
          $('#add_warehouse_modal').modal('show');
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

    $(document).on("blur change keyup", "form#addWarehouseForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addWarehouseForm #err_"+id).text(field+ " field is required.");
          $('form#addWarehouseForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addWarehouseForm #err_"+id).text("");
          $('form#addWarehouseForm #'+id).removeClass('is-invalid');
          $('form#addWarehouseForm #'+id).addClass('is-valid');
        }
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

    $("#warehouse_id").closest('.row').siblings().css('display','none');

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

                var futureDate = data.futureDate;

                $('#due_date').val(futureDate); 
                $('#supplier_state_id').val(supplier.state_id);
                $('#supplier_country_id').val(supplier.country_id);
                $('#supplier_gstin').val(supplier.gstin);

                // $("#product_table_body tr").remove();
                // calculateGrandTotal();
                refresh_tax_td();

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
                checkBottomSearchBar();
                
                
              }
            });
          } 
        });
      });
      
      // ====== BOTTOM SEARCH AUTO-COMPLETE (same behavior as top) ======
      $(function(){
        $('#bottom_search_product').autoComplete({
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

                calculateGrandTotal();
                $('#bottom_search_product').val('').focus();
                checkBottomSearchBar();
              }
            });
          } 
        });
      });
      // ====== END BOTTOM SEARCH AUTO-COMPLETE ======
      
    function getTaxAndAddRow(productId) {
        $.ajax({
            url: '<?= base_url("Purchase_request/get_product_tax_details") ?>',
            method: 'POST',
            data: { product_id: productId },
            success: function(response) {
                let res = JSON.parse(response);
                if (res.status === 'success') {
                    addRowWithTax(res.product);
                } else {
                    alert(res.message);
                }
            }
        });
    }

    // function add_row(data) {
    //      var tax_type = '<?= $tax_type ?>';
    //     //  var tax_type = data.product.tax_type || '';
    // // var cgst = parseFloat(data.product.cgst) || 0;
    // // var sgst = parseFloat(data.product.sgst) || 0;
    // // var igst = parseFloat(data.product.igst) || 0;
    //     console.log("Tax Type:", tax_type); 
    //     const company_state_id = $('#company_state_id').val();
    //     const warehouse_id = $('#warehouse_id').val();
    //     const product = data.product;
    //     const discounts = data.discount;
      
    //     let select_discount = '<select class="form-control select2bs4" name="item_discount" style="width: 100%;">';
    //     select_discount += '<option value="">Select</option>';
    //     for (let a = 0; a < discounts.length; a++) {
    //         const symbol = discounts[a].type == 0 
    //             ? "<?=$this->session->userdata('currency_symbol')?>" 
    //             : "%";
    //         select_discount += `<option value="${discounts[a].id}">${discounts[a].name} (${discounts[a].value}${symbol})</option>`;
    //     }
    //     select_discount += '</select>';
    //     select_discount += `
    //         <span name="span_discount_type" class="discount_type">Discount Value : <?=$this->session->userdata('currency_symbol');?></span>
    //         <input type="hidden" name="discount_type" value="0">
    //         <span name="discount_amount" class="discount_amount">0.0</span>
    //         <input type="hidden" name="discount_value" value="0">
    //     `;
    //   const input_uom = `<input type="hidden" name="uom_id" value="${product.uom_id}" data-uom_name="${product.uom_name}" data-uom_uom="${product.uom_name}">${product.uom_name}`;
    //     const input_quantity = `<input type="number" class="form-control" name="quantity" value="1" step="1" min="1"><span name="quantity_update_message" class="quantity_update_message"></span>`;
    //     const taxable_value = `<span name="taxable_value">${product.price}</span>`;
    
    //     const cgst = parseFloat(product.cgst) || 0;
    //     const sgst = parseFloat(product.sgst) || 0;
    //     const igst = parseFloat(product.igst) || 0;

    //     let tax_html = '';
    //     let total_tax_percentage = 0;
    
    //     console.log("CGST:", cgst, "SGST:", sgst, "IGST:", igst);
    
    //     if (tax_type === 'intra') {
    //         tax_html += '<strong>Tax Type:</strong> Intra-State<br>';
    //         tax_html += `CGST: ${cgst}%<br>`;
    //         tax_html += `SGST: ${sgst}%<br>`;
    //         total_tax_percentage = cgst + sgst;
    //     } else if (tax_type === 'inter') {
    //         tax_html += '<strong>Tax Type:</strong> Inter-State<br>';
    //         tax_html += `IGST: ${igst}%<br>`;
    //         total_tax_percentage = igst;
    //     } else {
    //         console.warn("Unknown tax_type, setting to N/A");
    //         tax_html += '<strong>Tax:</strong> N/A<br>';
    //     }
    //     tax_html += `<strong>Total Tax:</strong> ${total_tax_percentage}%`;
    //     console.log("Total Tax Percentage:", total_tax_percentage + "%");
    //     const tax_type_input = `<input type="hidden" name="tax_type" value="${tax_type}">`;
    //     const free_quantity = `<input type="number" class="form-control" name="free_quantity" min="0.00" value="0" step="0.01">`;
    //     const generated_batch_no = get_batch_no(product.cost, product.selling_price, product.price, product.id, warehouse_id);
    //     const newRow = $('<tr class="product_row">');
    //     let cols = '';
    //     cols += `
    //         <td>
    //             <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>
    //             <input type="hidden" name="product_id" value="${product.id}">
    //             <input type="hidden" name="igst_rate" value="${igst}">
    //             <input type="hidden" name="cgst_rate" value="${cgst}">
    //             <input type="hidden" name="sgst_rate" value="${sgst}">
    //         </td>
    //     `;
    //   cols += `
    //       <td>
    //         <img src="<?= base_url('assets/product_images/'); ?>/${product.product_image}" 
    //              alt="${product.name}" 
    //              style="width:40px; height:40px; object-fit:cover; margin-right:5px; border-radius:4px;">
    //         <span name="product_name">${product.name}</span>
    //         <span class="text-muted">(Code - ${product.product_code})</span>
    //       </td>`;

    //     cols += `<td><textarea name="product_remark" rows="1" cols="30"></textarea></td>`;
    //     cols += `<td>${input_quantity}</td>`;
    //     cols += `
    //         <td style="display: none;">
    //             <span id="cost_span">
    //                 <input type="number" class="form-control text-right" name="cost" step="0.01" value="${product.price}" min="1">
    //                 <input type="hidden" class="form-control text-right" name="hidden_cost" step="0.01" value="${product.price}" min="1">
    //             </span>
    //         </td>
    //     `;
    //     cols += `<td style="display: none;">${select_discount}</td>`;
    //     cols += `<td>${input_uom}</td>`;
    //     cols += `<td>${taxable_value}</td>`;
    //     cols += `<td class="tax_td">${tax_html}</td>`;
    //     cols += `<td><span name="subtotal"></span></td>`;
    
    //     newRow.append(cols);
    //     $("table.product_table").append(newRow);
    //     $('.select2bs4').select2({ theme: 'bootstrap4' });
    
    //     calculateRow(newRow);
    // }
    
    /*function add_row(data) {

    var tax_type = '<?= $tax_type ?>';
    const company_state_id = $('#company_state_id').val();
    const warehouse_id = $('#warehouse_id').val();

    const product   = data.product;   // ✅ define FIRST
    const discounts = data.discount;

    const price = parseFloat(product.price || 0);

    console.log('ADD ROW PRODUCT ID:', product.id);
    console.log('ADD ROW PRICE:', price);

    let select_discount = '<select class="form-control select2bs4" name="item_discount" style="width:100%">';
    select_discount += '<option value="">Select</option>';

    for (let i = 0; i < discounts.length; i++) {
        const symbol = discounts[i].type == 0
            ? "<?= $this->session->userdata('currency_symbol') ?>"
            : "%";

        select_discount += `
            <option value="${discounts[i].id}">
                ${discounts[i].name} (${discounts[i].value}${symbol})
            </option>`;
    }

    select_discount += '</select>';
    select_discount += `
        <span class="discount_type">Discount Value : <?= $this->session->userdata('currency_symbol') ?></span>
        <input type="hidden" name="discount_type" value="0">
        <span class="discount_amount">0.00</span>
        <input type="hidden" name="discount_value" value="0">
    `;

    // ---------------- INPUTS ----------------
    const input_quantity = `
        <input type="number" class="form-control" name="quantity" value="1" min="1" step="1">
        <span class="quantity_update_message"></span>
    `;

    const input_uom = `
        <input type="hidden" name="uom_id" value="${product.uom_id}"
               data-uom_name="${product.uom_name}"
               data-uom_uom="${product.uom_name}">
        ${product.uom_name}
    `;

    const taxable_value = `<span name="taxable_value">${price.toFixed(2)}</span>`;

    // ---------------- TAX ----------------
    const cgst = parseFloat(product.cgst) || 0;
    const sgst = parseFloat(product.sgst) || 0;
    const igst = parseFloat(product.igst) || 0;

    let tax_html = '';
    let total_tax_percentage = 0;

    if (tax_type === 'intra') {
        tax_html += `CGST: ${cgst}%<br>SGST: ${sgst}%`;
        total_tax_percentage = cgst + sgst;
    } else {
        tax_html += `IGST: ${igst}%`;
        total_tax_percentage = igst;
    }

    tax_html += `<br><strong>Total Tax:</strong> ${total_tax_percentage}%`;

    // ---------------- ROW BUILD ----------------
    const newRow = $('<tr class="product_row">');

    let cols = `
        <td>
            <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>
            <input type="hidden" name="product_id" value="${product.id}">
            <input type="hidden" name="igst_rate" value="${igst}">
            <input type="hidden" name="cgst_rate" value="${cgst}">
            <input type="hidden" name="sgst_rate" value="${sgst}">
        </td>

        <td>
            <img src="<?= base_url('assets/product_images/') ?>/${product.product_image}"
                 style="width:40px;height:40px;border-radius:4px;">
            <span name="product_name">${product.name}</span>
            <span class="text-muted">(Code - ${product.product_code})</span>
        </td>

        <td>
            <textarea name="product_remark" rows="1"></textarea>
        </td>

        <td>${input_quantity}</td>

        <!-- COST (HIDDEN BUT USED) -->
        <td style="display:none">
            <input type="number" name="cost" value="${price}" step="0.01">
            <input type="hidden" name="hidden_cost" value="${price}">
            <input type="hidden" name="price" value="${price}">
            <input type="hidden" name="tax_type" value="${tax_type}">
        </td>

        <td style="display:none">${select_discount}</td>

        <td>${input_uom}</td>
        <td>${taxable_value}</td>
        <td class="tax_td">${tax_html}</td>
        <td><span name="subtotal">0.00</span></td>
    `;

    newRow.append(cols);
    $("#product_table_body").append(newRow);

    $('.select2bs4').select2({ theme: 'bootstrap4' });

    calculateRow(newRow);
}*/
function add_row(data) {
    // ---------------- BASIC SETUP ----------------
    var tax_type = '<?= $tax_type ?>';
    const product = data.product;
    const discounts = data.discount;

    // ---------------- PRICE SAFETY ----------------
    const price = parseFloat(product.price || 0);

    console.log('ADD ROW - Product:', product.name);
    console.log('ADD ROW - Tax Type:', tax_type);
    console.log('ADD ROW - Tax Details:', {
        cgst: product.cgst,
        sgst: product.sgst,
        igst: product.igst
    });

    // ---------------- DISCOUNT DROPDOWN ----------------
    let select_discount = '<select class="form-control select2bs4" name="item_discount" style="width:100%">';
    select_discount += '<option value="">Select</option>';

    for (let i = 0; i < discounts.length; i++) {
        const symbol = discounts[i].type == 0 ? "<?= $this->session->userdata('currency_symbol') ?>" : "%";
        select_discount += `<option value="${discounts[i].id}">${discounts[i].name} (${discounts[i].value}${symbol})</option>`;
    }
    select_discount += '</select>';
    select_discount += `
        <span class="discount_type">Discount Value : <?= $this->session->userdata('currency_symbol') ?></span>
        <input type="hidden" name="discount_type" value="0">
        <span class="discount_amount">0.00</span>
        <input type="hidden" name="discount_value" value="0">
    `;

    // ---------------- INPUTS ----------------
    const input_quantity = `
        <input type="number" class="form-control" name="quantity" value="1" min="1" step="1">
        <span class="quantity_update_message"></span>
    `;

    const input_uom = `
        <input type="hidden" name="uom_id" value="${product.uom_id}" data-uom_name="${product.uom_name}" data-uom_uom="${product.uom_name}">
        ${product.uom_name}
    `;

    const taxable_value = `<span name="taxable_value">${price.toFixed(2)}</span>`;

    // ---------------- TAX RATES ----------------
    const cgst = parseFloat(product.cgst) || 0;
    const sgst = parseFloat(product.sgst) || 0;
    const igst = parseFloat(product.igst) || 0;

    // ---------------- ROW BUILD ----------------
    const newRow = $('<tr class="product_row">');
    
    let cols = `
         <td style="width: 2%;">
            <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>
            <input type="hidden" name="product_id" value="${product.id}">
            <input type="hidden" name="igst_rate" value="${igst}">
            <input type="hidden" name="cgst_rate" value="${cgst}">
            <input type="hidden" name="sgst_rate" value="${sgst}">
            <input type="hidden" name="tax_type" value="${tax_type}">
            <input type="hidden" name="tax_id" value="${product.tax_id || ''}">
           </td>
         <td style="width: 13%;">
            <img src="<?= base_url('assets/product_images/') ?>${product.product_image || 'default_product.jpg'}" style="width:40px;height:40px;border-radius:4px;">
            <span name="product_name">${product.name}</span>
            <span class="text-muted">(Code - ${product.product_code || ''})</span>
           </td>
         <td style="width: 13%;">
            <textarea name="product_remark" rows="1" cols="30" class="form-control"></textarea>
           </td>
         <td style="width: 7%;">
            ${input_quantity}
           </td>
        <td style="display:none">
            <input type="number" name="cost" value="${price}" step="0.01" class="form-control">
            <input type="hidden" name="hidden_cost" value="${price}">
            <input type="hidden" name="price" value="${price}">
           </td>
        <td style="display:none">
            ${select_discount}
           </td>
         <td style="width: 6%;">
            ${input_uom}
           </td>
         <td style="width: 8%;">
            ${taxable_value}
           </td>
        <td class="tax_td" style="width: 10%;">
            ₹0.00 (0%)
           </td>
         <td style="width: 7%;">
            <span name="subtotal">0.00</span>
           </td>
    `;

    newRow.append(cols);
    $("#product_table_body").append(newRow);
    
    $('.select2bs4').select2({ theme: 'bootstrap4' });
    calculateRow(newRow);
    calculateGrandTotal();
}

      function get_batch_no(cost, selling_price, price, product_id, warehouse_id) 
      {
        // alert();
        var batch_no = '';
            batch_no = generate_batch_no(cost, selling_price, price, product_id, warehouse_id);
            return batch_no;
      }
      function generate_batch_no(cost, selling_price, price,product_id,warehouse_id) 
      { 
        // Remove the decimal points by converting each price to a string and replacing the dot 
        var strCost = cost.toString().replace('.', '');
        var strSellingPrice = selling_price.toString().replace('.', '');
        var strPrice = price.toString().replace('.', '');
        // Concatenate the strings
        var concatenatedString = strCost + strSellingPrice + strPrice + product_id + warehouse_id;
        //alert(concatenatedString);
        return concatenatedString;
      }
      $(document).on('change', '.batch_no_list', function (e) {
        var batch_no = $(this).val();
        var datalist = $(this).get(0).list;

        var isBatchFromList = false; // Flag to track if the batch number is from the list

        $(datalist).find('option').each(function () {
          // Compare the option value with the value to compare
          if ($(this).val() == batch_no) {
            var row = $(this).closest("tr");
            row.find('input[name="price"]').val($(this).data('price')).prop('readonly', true);
            row.find('input[name="selling_price"]').val($(this).data('selling_price')).prop('readonly', true);
            row.find('input[name="cost"]').val($(this).data('cost')).prop('readonly', true);
            isBatchFromList = true;
            return false;
          }
        });

        if (!isBatchFromList) {
          var row = $(this).closest("tr");
          var productCost = parseFloat(row.find('input[name="hidden_cost"]').val());
          var productPrice = parseFloat(row.find('input[name="hidden_price"]').val());
          var productSellingPrice = parseFloat(row.find('input[name="hidden_selling_price"]').val());

          row.find('input[name="cost"]').val(productCost).prop('readonly', false);
          row.find('input[name="price"]').val(productPrice).prop('readonly', false);
          row.find('input[name="selling_price"]').val(productSellingPrice).prop('readonly', false);
        }
      });

      // $(document).on('keyup' ,'#batch_no' , function(e){
      //   var row = $(this).closest("tr");

      // });

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

/* function calculateRow(row) {
    var tax_type = row.find('input[name^="tax_type"]').val();
    var quantity = parseFloat(row.find('input[name^="quantity"]').val()) || 0;
    var cost = parseFloat(row.find('input[name^="cost"]').val()) || 0;
    var discount_type = row.find('input[name^="discount_type"]').val();
    var discount_value = parseFloat(row.find('input[name^="discount_value"]').val()) || 0;
    var cgst = parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0;
    var sgst = parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0;
    var igst = parseFloat(row.find('input[name^="igst_rate"]').val()) || 0;

    var subtotal = quantity * cost;
    var final_discount_value = 0;

    if (discount_type == "0") {
        final_discount_value = discount_value;
    } else {
        final_discount_value = (subtotal * discount_value) / 100;
    }
    var taxable_value = subtotal - final_discount_value;
    var cgst_tax = (taxable_value * cgst) / 100;
    var sgst_tax = (taxable_value * sgst) / 100;
    var igst_tax = (taxable_value * igst) / 100;
    var total    = taxable_value + igst_tax;
    // Update DOM values
    row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));
    row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
    row.find('span[name^="c_tax"]').text(cgst_tax.toFixed(2));
    row.find('span[name^="s_tax"]').text(sgst_tax.toFixed(2));
    row.find('span[name^="i_tax"]').text(igst_tax.toFixed(2));
    row.find('span[name^="cgst"]').text(cgst.toFixed(2));
    row.find('span[name^="sgst"]').text(sgst.toFixed(2));
    row.find('span[name^="igst"]').text(igst.toFixed(2));
    row.find('span[name^="subtotal"]').text(total.toFixed(2));
 }
 */
function calculateRow(row) {
    var tax_type = row.find('input[name^="tax_type"]').val();
    var quantity = parseFloat(row.find('input[name^="quantity"]').val()) || 0;
    var cost = parseFloat(row.find('input[name^="cost"]').val()) || 0;
    var discount_type = row.find('input[name^="discount_type"]').val();
    var discount_value = parseFloat(row.find('input[name^="discount_value"]').val()) || 0;
    var cgst = parseFloat(row.find('input[name^="cgst_rate"]').val()) || 0;
    var sgst = parseFloat(row.find('input[name^="sgst_rate"]').val()) || 0;
    var igst = parseFloat(row.find('input[name^="igst_rate"]').val()) || 0;

    // Calculate subtotal
    var subtotal = quantity * cost;
    
    // Calculate discount
    var final_discount_value = 0;
    if (discount_type == "0") {
        final_discount_value = discount_value;
    } else {
        final_discount_value = (subtotal * discount_value) / 100;
    }
    
    // Taxable value after discount
    var taxable_value = subtotal - final_discount_value;
    
    // Calculate tax based on type
    var tax_amount = 0;
    var total_tax_percentage = 0;
    
    if (tax_type === 'intra') {
        // Intra-state: CGST + SGST
        total_tax_percentage = cgst + sgst;
        tax_amount = (taxable_value * total_tax_percentage) / 100;
    } else {
        // Inter-state: IGST only
        total_tax_percentage = igst;
        tax_amount = (taxable_value * total_tax_percentage) / 100;
    }
    
    // Total = Taxable value + Tax amount
    var total = taxable_value + tax_amount;
    
    // Format tax display as: ₹9.00 (18.00%)
    var tax_display = `₹${tax_amount.toFixed(2)} (${total_tax_percentage.toFixed(2)}%)`;
    
    // Update DOM elements
    row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));
    row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
    row.find('span[name^="subtotal"]').text(total.toFixed(2));
    
    // Update tax column with amount and percentage
    row.find('.tax_td').html(tax_display);
}
 /*function calculateGrandTotal()
 {
    var total_taxable_value = 0.0;
    var total_cgst = 0.0;
    var total_sgst = 0.0;
    var total_igst = 0.0;
    var total = 0.0;
    var total_discount = 0.0;

    $("#product_table_body").find('tr').each(function () {
        var tr = $(this).closest("tr");

        var taxable_value = parseFloat(tr.find('span[name^="taxable_value"]').text()) || 0;
        var discount = parseFloat(tr.find('span[name^="discount_amount"]').text()) || 0;
        var cgst = parseFloat(tr.find('span[name^="c_tax"]').text()) || 0;
        var sgst = parseFloat(tr.find('span[name^="s_tax"]').text()) || 0;
        var igst = parseFloat(tr.find('span[name^="i_tax"]').text()) || 0;
        var subtotal = parseFloat(tr.find('span[name^="subtotal"]').text()) || 0;

        total_taxable_value += taxable_value;
        total_discount += discount;
        total_cgst += cgst;
        total_sgst += sgst;
        total_igst += igst;

        total += subtotal + cgst + sgst + igst; 
    });

    var total_tax = total - total_taxable_value;
    $('#total_taxable_value').text(total_taxable_value.toFixed(2));
    $('#t_taxable_value').val(total_taxable_value.toFixed(2));

    $('#total_discount').text(total_discount.toFixed(2));
    $('#t_discount').val(total_discount.toFixed(2));

    $('#total_tax').text(total_tax.toFixed(2));
    $('#t_tax').val(total_tax.toFixed(2));

    $('#total').text(total.toFixed(2));
    $('#t').val(total.toFixed(2));

    console.log("Total Taxable Value:", total_taxable_value.toFixed(2));
    console.log("Total Discount:", total_discount.toFixed(2));
    console.log("Total CGST:", total_cgst.toFixed(2));
    console.log("Total SGST:", total_sgst.toFixed(2));
    console.log("Total IGST:", total_igst.toFixed(2));
    console.log("Total Tax:", total_tax.toFixed(2));
    console.log("Total (Including Tax):", total.toFixed(2));

    if (total_taxable_value > 0) {
        var igst_percent = (total_igst / total_taxable_value) * 100;
        console.log("IGST % of Taxable Value:", igst_percent.toFixed(2) + "%");
    } else {
        console.log("IGST % of Taxable Value: 0.00% (Taxable value is 0)");
    }
}
    
    */
   function calculateGrandTotal() {
    var total_taxable_value = 0.0;
    var total_tax = 0.0;
    var total = 0.0;
    var total_discount = 0.0;

    $("#product_table_body").find('tr').each(function () {
        var tr = $(this).closest("tr");
        
        var taxable_value = parseFloat(tr.find('span[name^="taxable_value"]').text()) || 0;
        var discount = parseFloat(tr.find('span[name^="discount_amount"]').text()) || 0;
        var subtotal = parseFloat(tr.find('span[name^="subtotal"]').text()) || 0;
        
        total_taxable_value += taxable_value;
        total_discount += discount;
        total += subtotal;
    });
    
    // Total tax is the difference between total and taxable value
    total_tax = total - total_taxable_value;
    
    // Calculate total tax percentage based on total taxable value
    var total_tax_percentage = 0;
    if (total_taxable_value > 0) {
        total_tax_percentage = (total_tax / total_taxable_value) * 100;
    }
    
    // Update DOM
    $('#total_taxable_value').text(total_taxable_value.toFixed(2));
    $('#t_taxable_value').val(total_taxable_value.toFixed(2));
    
    $('#total_discount').text(total_discount.toFixed(2));
    $('#t_discount').val(total_discount.toFixed(2));
    
    // Format total tax display with amount and percentage
    $('#total_tax').html(`₹${total_tax.toFixed(2)} (${total_tax_percentage.toFixed(2)}%)`);
    $('#t_tax').val(total_tax.toFixed(2));
    
    $('#total').text(total.toFixed(2));
    $('#t').val(total.toFixed(2));
}
    
    
    // ===== bottom search visibility helper =====
function checkBottomSearchBar() {
    // Count only product rows
    let rowCount = $("#product_table_body tr").length;
    if (rowCount >= 4) {
        $("#bottom_search_wrapper").show();
    } else {
        $("#bottom_search_wrapper").hide();
    }
}

// ensure initial state on page load (in case you prefill items)
$(document).ready(function() {
    checkBottomSearchBar();
});
      $('#addPurchaseForm').submit(function(e){
        
        var isError = false;
        $('#purchaseSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $('form#addPurchaseForm .field_validation').each(function() {
            var id    = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if(value==null || value==""){
              $("form#addPurchaseForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
              $('form#addPurchaseForm #'+id).addClass('is-invalid');
              isError = true;
            }
            else
            {
              $("form#addPurchaseForm #err_"+id).text("").fadeOut('slow');
              $('form#addPurchaseForm #'+id).removeClass('is-invalid');
              $('form#addPurchaseForm #'+id).addClass('is-valid');
            }
        });
        
        
        
        

        var productDataArray = [];

        $("#product_table_body").find('tr').each(function () {

            var tr              = $(this).closest("tr");
            var productData     = {};

            productData['product_id']       = tr.find('input[name^="product_id"]').val();
            productData['product_remark']   = tr.find('textarea[name^="product_remark"]').val();
            productData['product_name']     = tr.find('span[name^="product_name"]').text();
            productData['description']      = tr.find('span[name^="description"]').text();
            productData['quantity']         = tr.find('input[name^="quantity"]').val();
            productData['uom_id']           = tr.find('input[name^="uom_id"]').val();
            console.log('UOM ID captured:', productData['uom_id']); // Add this line

            productData['uom_name']         = tr.find('input[name^="uom_id"]').data('uom_name');
            productData['uom_uom']          = tr.find('input[name^="uom_id"]').data('uom_uom');
            productData['cost']             = tr.find('input[name^="cost"]').val();
            productData['price']            = tr.find('input[name="price"]').val();
            productData['selling_price']    = tr.find('input[name="selling_price"]').val();
            productData['taxable_value']    = tr.find('span[name^="taxable_value"]').text();
            productData['discount_id']      = tr.find('select[name^="item_discount"]').val();
            productData['discount_type']    = tr.find('input[name^="discount_type"]').val();
            productData['discount_value']   = tr.find('input[name^="discount_value"]').val();
            productData['discount_amount']  = tr.find('span[name^="discount_amount"]').text();
            productData['tax_id']           = tr.find('input[name^="tax_id"]').val();
            productData['igst']             = tr.find('span[name^="igst"]').text();
            productData['igst_tax']         = tr.find('span[name^="i_tax"]').text();
            productData['cgst']             = tr.find('span[name^="cgst"]').text();
            productData['cgst_tax']         = tr.find('span[name^="c_tax"]').text();
            productData['sgst']             = tr.find('span[name^="sgst"]').text();
            productData['sgst_tax']         = tr.find('span[name^="s_tax"]').text();
            productData['subtotal']         = tr.find('span[name^="subtotal"]').text();
            productData['free_quantity']          = tr.find('input[name="free_quantity"]').val();

            productDataArray.push(JSON.stringify(productData));
        });

        if(productDataArray.length > 0)
        {
          $('#purchase_items').val(productDataArray.join('|'));
        }
        else
        {
          isError = true;
          $('#emptyPurchaseItemWarningModal').modal('show');
        }

        // return false;

        if(isError == true)
        {
           $('#purchaseSubmit').text('<?=$this->lang->line("purchase_add")?>').removeAttr('disabled');
           return false;
        }
          
        else 
        {
          return true;
        }
      });

      $("form#addPurchaseForm .field_validation").on("blur change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addPurchaseForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addPurchaseForm #'+id).removeClass('is-valid');
          $('form#addPurchaseForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addPurchaseForm #err_"+id).text("").fadeOut('slow');
          $('form#addPurchaseForm #'+id).removeClass('is-invalid');
          $('form#addPurchaseForm #'+id).addClass('is-valid');
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
            url: "<?php echo base_url('purchase/upload_documents')?>", // Replace with your server-side script URL
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
  
  // Replace your existing $("table.product_table").on('change', ...) with this:

// Immediate calculation when typing/changing quantity
$(document).on('input', 'input[name^="quantity"]', function(e) {
    var tr = $(this).closest('tr');
    calculateRow(tr);
    calculateGrandTotal();
});

// Handle other changes (cost, discount) on change event
$("table.product_table").on('change', 'input[name^="cost"], select[name^="item_discount"]', function (event) {
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
</script>




