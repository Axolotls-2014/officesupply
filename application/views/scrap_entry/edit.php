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
  .is-green {
        border: 1px solid red; /* Example: Green border */
    }


</style>
  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><?=$this->lang->line('header_account')?></li>
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_scrap_entry')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('scrap_entry_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editScrapentryForm" id="editScrapentryForm" method="POST" action="<?=base_url('scrap_entry/edit')?>">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('scrap_entry_edit')?></h3>
                  <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">

                      <li class="nav-item ml-2">
                        <a class="nav-link active btn btn-sm" href="<?=base_url('scrap_entry')?>" data-tt="tooltip" title="Click here to show scrap entry list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                      </li>
                    
                    <?php 
                      if($this->permission_model->has_permission('import_scrap_entry_items'))
                      {
                    ?>
                      <li class="nav-item  ml-2">
                        <a class="nav-link import_scrap_entry_modal btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Import Scrap issue">
                          <i class="fas fa-file-import"></i> Import
                        </a>
                      </li>
                    <?php
                      }
                    ?>

                    <li class="nav-item  ml-2">
                      <button type="submit" name="submit" id="ScrapentrySubmit" class="btn btn-info"><?=$this->lang->line('scrap_entry_save')?></button>
                    </li>

                    </ul>
                  </div>
                </div>
                <div class="card-body">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label><?=$this->lang->line('scrap_entry_reference_no')?></label>
                          <input type="text" class="form-control" id="reference_no" name="reference_no" value="<?=$scrap_entry->reference_no?>" readonly>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label><?=$this->lang->line('scrap_entry_date')?></label>
                          <input type="text" class="form-control datepicker" id="scrap_entry_date" name="scrap_entry_date" value="<?=date('d-m-Y', strtotime($scrap_entry->scrap_entry_date))?>" placeholder="Pack Slip Date">
                          <span id="err_scrap_entry_date" class="error invalid-feedback"><?=form_error('scrap_entry_date');?></span>
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="warehouse">
                            <?=$this->lang->line('purchase_warehouse')?>
                            <span class="text-danger">*</span>
                          </label>
                          <select class="form-control form-control-sm select2bs4 field_validation" name="warehouse_id" id="warehouse_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('purchase_warehouse')?>">
                            <option value=""><?=$this->lang->line('select')?></option>
                            <?php
                              foreach ($warehouses as $value) {
                            ?>
                              <option value="<?=$value->id;?>"
                                <?php 
                                 if($value->is_default == WAREHOUSE_IS_DEFAULT_YES || $value->id == $scrap_entry->warehouse_id)
                                    echo ' selected';
                                ?>
                              >
                                <?= $value->name;?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span id="err_warehouse_id" class="error invalid-feedback"><?=form_error('warehouse_id');?></span>
                        </div>
                      </div>
                     
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 ">
                        <div class="well">
                          <div class="row">
                            <!-- <div class="col-sm-12">
                              <a href="" data-toggle="modal" data-tt="tooltip" data-target="#add_service_modal" class="float-right"><?=$this->lang->line('supplier_add_new_service')?></a>
                            </div> -->
                            <div class="col-sm-12 search">
                              <span class="fa fa-search"></span>
                              <input id="search_product" class="form-control search_product" type="text" name="search_product"  placeholder="<?=$this->lang->line('supplier_item_search')?>">
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
                          <label><?=$this->lang->line('scrap_entry_items')?></label>
                          <table class="table items table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
                            <thead>
                              <tr>
                                <th width="2%">
                                  <img src="<?php  echo base_url(); ?>assets/images/bin1.png" />
                                </th>
                                <th class="span2" width="15%"><?=$this->lang->line('service_description')?></th>
                                <th class="span2" width="8%"><?=$this->lang->line('proforma_invoice_qty')?></th>
                                <th class="span2" width="10%"><?=$this->lang->line('proforma_invoice_batch')?></th>
                                 <th class="span2" width="7%"><?=$this->lang->line('product_cost')?></th>
                                <th class="span2" width="10%"><?=$this->lang->line('proforma_invoice_selling_price')?></th>
                                <th class="span2" width="10%"><?=$this->lang->line('product_price')?></th>
                                <th class="span2 d-none" width="14%"><?=$this->lang->line('proforma_invoice_discount')?></th>
                                <th class="span2 d-none" width="11%"><?=$this->lang->line('proforma_invoice_uom')?></th>
                                <th class="span2" width="8%"><?=$this->lang->line('proforma_invoice_taxable_value')?></th>
                              
                               
                              </tr>
                            </thead>
                            <tbody id="product_table_body">
                              <?php 
                                foreach ($scrap_entry_items as $row) {
                              ?>
                                <tr>
                                  <td>
                                    <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i>
                                    </span>
                                    <input type="hidden" name="product_id" value="<?=$row->product_id?>">
                                 
                                    <?php 
                                      $product_id   = $row->product_id;
                                      $cost         = $row->cost;
                                      // $warehouse_id = $scrap_entry->warehouse_id;

                                      $warehouse_product = $this->warehouse_products_model->get_single_record($row->warehouse_product_id);
                                    ?>
                                    <input type="hidden" name="warehouse_product_id" value="<?=$row->warehouse_product_id?>">
                                    <input type="hidden" name="pid" id="pid" value="<?=$row->pid?>">
                                  </td>

                                  <td>
                                    <span name="product_name"><?=$row->product_name?></span><br/>
                                    <span name="description"><?=$row->description?></span>
                                  </td>
                                  
                                  <td>

                                    <input type="number" class="form-control" name="quantity" value="<?=$row->quantity?>"  step="0.01" min="1">
                                   
                                  </td>

                                  <td>
                                   <span name="batch_no"><?=$row->batch_no?></span>
                                  </td>

                                  <td>
                                    <span id="cost_span">
                                      <input type="number" class="form-control text-right" name="cost" step="0.01" value="<?=$row->cost?>" required>
                                     </span>
                                  </td>
                                  
                                  <td>
                                    <span id="selling_price_span">
                                      <input type="number" class="form-control text-right" name="selling_price" step="0.01" value="<?=$row->selling_price?>" required>
                                      <!-- <input type="hidden" name="cost" value="<?=$row->cost?>"> -->
                                    </span>
                                  </td>

                                  <td>
                                    <span id="price_span">
                                      <input type="number" class="form-control text-right" name="price" step="0.01" value="<?=$row->price?>" required>
                                     </span>
                                  </td>
                                  
                                  <td class="d-none">
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
                                  <td class="d-none">
                                    <input type="hidden" name="uom_id" value="<?=$row->uom_id?>" data-uom_name="<?=$row->uom_name?>" data-uom_uom="<?=$row->uom_uom?>">  
                                    <?=$row->uom_uom?>
                                  </td>
                                  <td><span name="taxable_value"><?=$row->taxable_value-$row->discount_amount?></span></td>
<!--                                   
                                
                                  
                                  <td><span name="sub_total"><?=$row->sub_total?></span></td> -->
                                </tr>
                              <?php 
                                }
                              ?>
                            </tbody>
                          </table>
                          <table class="table table-striped table-bordered table-condensed table-hover total_data" style="font-size: 18px;">
                            <!-- <tr>
                              <td align="right"  width="66%"> <?=$this->lang->line('proforma_invoice_total_discount')?>(<?=$currency?>)</td>
                              <td align='right' class="text-danger"  width="34%">
                                -<span id="total_discount"><?=$proforma_invoice->total_discount?></span>
                                <input type="hidden" name="total_discount" id="t_discount" value="<?=$proforma_invoice->total_discount?>">
                              </td>
                            </tr> -->
                            <tr>
                              <td align="right"  width="66%" style="font-size: 15px;"><?=$this->lang->line('scrap_entry_total_taxable_value')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                +<span id="total_taxable_value"><?=$scrap_entry->total_taxable_value?></span>
                                <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="<?=$scrap_entry->total_taxable_value-$scrap_entry->tds?>">
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
                                <li class="nav-item d-none">
                                  <a class="nav-link active" href="#external_note" data-toggle="pill"><?php echo $this->lang->line('scrap_entry_external_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#internal_note" data-toggle="pill"><?php echo $this->lang->line('scrap_entry_internal_note'); ?></a>
                                </li>
                                <li class="nav-item d-none">
                                  <a class="nav-link" href="#bank_detail" data-toggle="pill"><?php echo $this->lang->line('scrap_entry_bank_detail'); ?></a>
                                </li>
                                <li class="nav-item d-none">
                                  <a class="nav-link" href="#terms_and_condition" data-toggle="pill"><?php echo $this->lang->line('scrap_entry_terms_and_condition'); ?></a>
                                </li>
                              </ul>                           
                              <br>
                              <div class="tab-content">
                                <div class="tab-pane  d-none" id="external_note">
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('scrap_entry_external_note'); ?>"><?=str_replace("<br />","",$scrap_entry->external_note)?></textarea>
                                </div>
                                <div class="tab-pane active" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('scrap_entry_internal_note'); ?>"><?=str_replace("<br />","",$scrap_entry->internal_note)?></textarea>
                                </div>
                                <div class="tab-pane d-none" id="bank_detail">
                                  <textarea class="col-sm-12 form-control" name="bank_detail" rows="4" placeholder="<?php echo $this->lang->line('scrap_entry_bank_detail'); ?>"><?=str_replace("<br />","",$scrap_entry->bank_detail)?></textarea>
                                </div>
                                <div class="tab-pane d-none" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('scrap_entry_terms_and_condition'); ?>"><?=str_replace("<br />","",$scrap_entry->terms_and_condition)?></textarea>
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
                
                  <input type="hidden" name="id" value="<?=$scrap_entry->id?>">
                  <input type="hidden" name="scrap_entry_items" id="scrap_entry_items" value="">
                 
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  
                  <span class="btn btn-default float-right" id="cancel" onclick="cancel('sale')"><?=$this->lang->line('scrap_entry_cancel')?></span>
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
        <h5 class="modal-title" id="exampleModalLabel"> <?=$this->lang->line('scrap_entry_message_label')?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
         <?=$this->lang->line('empty_scrap_entry_warning_label')?>
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
      $("#warehouse_id").closest('.row').siblings().fadeIn(10);

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

          }
      });
    }
    else
      {
        $("#warehouse_id").closest('.row').siblings().css('display','none');
      }
    });


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
                'module': '<?=SCRAP_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var products = data;
                var suggestions = [];
                for(var i = 0; i < products.length; ++i) {
                 

                  suggestions.push(products[i].id+' - '+products[i].name+' - '+products[i].product_category_name+" - "+products[i].tax_rate+"% - "+products[i].batch_no);

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
                'module': '<?=SCRAP_MODULE?>',
                'supplier_id': $('#supplier_id').val(),
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var product = data.product;
               
                var discounts = data.discount;



                var supplier_id = $('#supplier_id').val();
                if(supplier_id != '')
                {
                  if(!is_product_exist_in_row(product.warehouse_products_id))
                  {
                    // autosaveForm();
                   
                    add_row(product,discounts,ordered_quantity=null);
                  }
                  else
                  {
                    highlight_row(product.warehouse_products_id);
                  }

                  calculateGrandTotal();
                  $('#search_product').val('');
                }
                else{
                  $('#search_product').val('');
                  Swal.fire({
                    title: "Message",
                    text: "Please select supplier",
                    // type: "warning",
                    buttonsStyling: !1,
                    confirmButtonText: "Ok, got it!",
                    timer: 5000,
                    customClass: {
                      confirmButton: "btn btn-primary"
                    }
                  });
                }
              }
            });
          } 
        });
      });

      $(document).on('click', ".import_scrap_entry_modal" ,function(event){
        event.preventDefault();

      
        $.ajax({
          url: "<?php echo base_url('scrap_entry/import_scrap_entry_items')?>",
          type: "GET",
          dataType: "JSON",
          success: function(data){
            $('#import_scrap_entry_modal').find('.modal-content').html(data.import_scrap_entry_items_modal_body);
            $('#import_scrap_entry_modal').modal('show');

            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
          },
          error: function (xhr, ajaxOptions, thrownError) {
            // alert(xhr.status);
            show_message('failure-header',thrownError);
            // alert(thrownError);
            // alert(ajaxOptions);
          }
        });
       
        
      });

      
       
      $(document).on('submit', 'form#importscrapentryItemsForm', function (event) {
        event.preventDefault();

        $('form#importscrapentryItemsForm #importscrapentryItemsSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled', 'disabled');

        // Get the CSV file input
        var csvfileInput = $('form#importscrapentryItemsForm #csvfile')[0];

        // Check if a file was selected
        if (csvfileInput.files.length === 0) {
            alert('Please select a CSV file before submitting.');
            return false;
        }

      
        var formData = new FormData();
        formData.append('csvfile', csvfileInput.files[0]);
        
       

        var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
        var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

        formData.append(csrfTokenName, csrfTokenValue);

        $.ajax({
            url: "<?php echo base_url('scrap_entry/import_scrap_entry_items')?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            processData: false,  
            contentType: false,  
            success: function (response) {
                console.log("Response from server:", response);

                // var warehouseProductData  = response.warehouseProductData;
                var warehouseProductData  = response.warehouseProductData;
              
                // var lastPtd               = response.lastPtd;
                var discount              = response.discount;
                var orderedquantity       = response.quantity;

                if (response.code === 1) {
                    console.log("Warehouse Product Data", holdQuantities);
                    // Now, call the add_row function for each matched product
                    for (var i = 0; i < warehouseProductData.length; i++) 
                    { 
                        var product           = warehouseProductData[i];
                        var product_id        = parseInt(product.warehouse_products_id);

                        // var last_ptd          = lastPtd[product_id];
                        
                        var discounts         = discount;
                        var ordered_quantity  = orderedquantity[product_id];
                        
                        if(!is_product_exist_in_row(product.warehouse_products_id))
                        {
                          $('#import_scrap_entry_modal').modal('hide');
                          add_row(product,discountsordered_quantity=null);
                          
                        }
                        else
                        {
                          $('#import_scrap_entry_modal').modal('hide');
                          highlight_row(product.warehouse_products_id);
                        }

                        calculateGrandTotal();                        
                    }

                    if(response.message != '')
                    {
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
                } 
                else if(response.code === 0)
                {
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

                  $('#csvfile').val(''); // Clear the file input value
                  $('#fileLabel').text('Choose file'); // Reset the label text
                }

                // Reset the submit button
                $('form#importscrapentryItemsForm #importscrapentryItemsSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            },
            error: function (xhr, status, error) {
                console.error(xhr.responseText);
                alert("An error occurred while uploading the CSV file.");
                // Reset the submit button
                $('form#importscrapentryItemsForm #importscrapentryItemsSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
        });
      });

      function add_row(product,discounts,ordered_quantity=null)
      {
      
       

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
        /* input_quantity  +=      'MIN: '+product.minimum_order_quantity+' <span data-tt="tooltip" title="Minimum qty must be ordered by supplier"><i class="fas fa-question" style="float:right;font-size:11px;padding-top:5px"></i></span>';*/

        
        input_quantity  +=  '<input type="number" class="form-control" name="quantity" value="1" step="0.01"  min="0.01">';
       

        // if (ordered_quantity === null || ordered_quantity === undefined) 
        // {
        //   var quantity  =  '<input type="number" class="form-control" name="quantity" value="1" step="0.01" min="0.01">';
        // }
        // else
        // {
        //   var quantity  =  '<input type="number" class="form-control" name="quantity" value="'+ordered_quantity+'" step="0.01" min="0.01">';
        // }

       

        input_optional  = "";

        input_optional += '<textarea class="form-control d-none" id="textarea" name="optional" rows="4" placeholder="" maxlength="50"></textarea><br/>';


        /*var input_quantity =  '<input type="number" class="form-control" name="quantity" value="1" step="1" min="1" max="'+product.quantity+'"><span name="quantity_update_message" class="quantity_update_message"></span>';*/
        var taxable_value  =  '<span name="taxable_value">'+product.price+'</span>';


        /* Variant start */

      

        /* Variant end */

        var newRow = $('<tr class="service_row">');
            var cols = "";

            cols += '<td>'
                      + '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
                      + '<input type="hidden" name="product_id" value="'+product.id+'">'
                      + '<input type="hidden" name="pid" id="pid" value="'+product.pid+'">'
                      + '<input type="hidden" name="po_request_id" value="">'
                      + '<input type="hidden" name="warehouse_product_id" value="'+product.warehouse_products_id+'">'  
                    +'</td>';
            cols += '<td>'
                      + '<span name="product_name">'+product.name+'</span><br/>'
                    
                      + '<span name="description">'+product.description+'</span><br/>'+input_optional
                     
                    +'</td>';

            cols += '<td>'+input_quantity+'</td>';
            cols += '<td><span name="batch_no">'+product.batch_no+'</span></td>';
            cols += '<td>' 
                      +'<input type="number" class="form-control text-right" name="cost" step="0.01" value="'+product.cost+'">'
                    +'</td>';
            cols += '<td>' 
                      +'<input type="number" class="form-control text-right" name="selling_price" step="0.01" value="'+product.selling_price+'">'
                    +'</td>';
            cols += '<td>' 
                      +'<input type="number" class="form-control text-right" name="price" value="'+product.price+'" step="0.01">'
                      // + '<input type="hidden" name="cost" value="'+product.cost+'">'
          
                    +'</td>';
          
            // cols += '<td>'+quantity+'</td>';
           
            
            // cols += '<td><span name="total_quantity"></span></td>';
        
         
            
            cols += '<td class="d-none">'+select_discount+'</td>';
            cols += '<td class="d-none">'+input_uom+'</td>';
            cols += '<td class="">'+taxable_value+'</td>';
            
           
            // cols += '<td class="d-none">'+tax_type+'</td>';
            // cols += '<td class="d-none"><span name="sub_total"></span></td>';
            cols += '</tr>';

            newRow.append(cols);
            $("table.product_table").prepend(newRow);
            $('.select2bs4').select2({theme: 'bootstrap4'});
            $('[data-tt="tooltip"]').tooltip({trigger : 'hover'});
            $('.datepicker').datepicker({
                weekStart: 1,
                daysOfWeekHighlighted: "6,0",
                autoclose: true,
                todayHighlight: true,
                format: 'dd-mm-yyyy'
            });

            newRow.find('input[name="quantity"]').trigger('keyup');
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

      $("table.product_table").on('change blur keyup', 'input[name^="selling_price"], input[name^="quantity"]', function (event) {

       
        var tr          = $(this).closest('tr');

        calculateRow($(this).closest("tr"));
        calculateGrandTotal();

       
      });
      
      $('table.product_table').on('click',"span.delete_item", function(e){
        var tr = $(this).closest('tr');
        tr.remove();
        calculateGrandTotal();
      });

   
      function calculateRow(row)
      {
          var product_id  = row.find('input[name^="product_id"]').val();
          var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
          var cost       = parseFloat(row.find('input[name^="cost"]').val());
          var final_discount_value = 0;

          var taxable_value   = parseFloat(quantity * cost);

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
        
          row.find('span[name^="discount_amount"]').text(final_discount_value.toFixed(2));

          row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
        
      }

      function calculateGrandTotal()
      {
        
        var total_taxable_value = 0.0;
       
        var total               = 0.0;
        var total_discount      = 0.0;

        // TDS part
        var tds                 = parseFloat($('#tds').val()); 


        $("#product_table_body").find('tr').each(function () {
          var tr              = $(this).closest("tr");
          total_taxable_value += parseFloat(tr.find('span[name^="taxable_value"]').text());
          total_discount      += parseFloat(tr.find('span[name^="discount_amount"]').text());
        
        });
        // alert(total_taxable_value);

        $('#total_taxable_value').text(parseFloat(total_taxable_value.toFixed(2)));
        $('#t_taxable_value').val(parseFloat(total_taxable_value.toFixed(2)));
        $('.total_taxable_value').text(parseFloat(total_taxable_value.toFixed(2)));

        $('#total_discount').text(total_discount.toFixed(2));
        $('#t_discount').val(total_discount.toFixed(2));


      }

      $('#editScrapentryForm').submit(function(e){
        // e.preventDefault();

        var isError = false;
        $('#ScrapentrySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $('form#editScrapentryForm .field_validation').each(function() {
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editScrapentryForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#editScrapentryForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editScrapentryForm #err_"+id).text("").fadeOut('slow');
            $('form#editScrapentryForm #'+id).removeClass('is-invalid');
            $('form#editScrapentryForm #'+id).addClass('is-valid');
          }
        });

        var productDataArray = [];

        $("#product_table_body").find('tr').each(function () {

          var tr              = $(this).closest("tr");
          var productData     = {};

          productData['product_id']       = tr.find('input[name="product_id"]').val();
          productData['warehouse_product_id'] = tr.find('input[name="warehouse_product_id"]').val();
          productData['pid']                    = tr.find('input[name="pid"]').val();
          productData['product_name']     = tr.find('span[name="product_name"]').text();
          productData['description']      = tr.find('span[name="description"]').text();
          productData['quantity']         = tr.find('input[name="quantity"]').val();
          productData['batch_no']          = tr.find('span[name="batch_no"]').text();
          productData['cost'] = tr.find('input[name^="cost"]').val();

          // Check if cost value is 0
          if (parseFloat(productData['cost']) === 0) {
              tr.find('input[name^="cost"]').addClass('is-green'); // Assuming you have a class for green color
          } else {
              tr.find('input[name^="cost"]').removeClass('is-green');
          }

          productData['selling_price']            = tr.find('input[name="selling_price"]').val();
          productData['price']            = tr.find('input[name="price"]').val();
          productData['taxable_value']    = parseFloat(productData['quantity'])*parseFloat(productData['selling_price']);
          productData['discount_id']      = tr.find('select[name="item_discount"]').val();
          productData['discount_type']    = tr.find('input[name="discount_type"]').val();
          productData['discount_value']   = tr.find('input[name="discount_value"]').val();
          productData['discount_amount']  = tr.find('span[name="discount_amount"]').text();
          productData['uom_id']           = tr.find('input[name="uom_id"]').val();
          productData['uom_name']         = tr.find('input[name="uom_id"]').data('uom_name');
          productData['uom_uom']          = tr.find('input[name="uom_id"]').data('uom_uom');
        

          productDataArray.push(JSON.stringify(productData));
        });

        if(productDataArray.length > 0)
        {
          $('#scrap_entry_items').val(productDataArray.join('|'));
        }
        else
        {
          isError = true;
          $('#emptySaleItemWarningModal').modal('show');
        }

         
        // Check if any cost value is 0
        var hasZeroCost = $("#product_table_body").find('input[name^="cost"].is-green').length > 0;

        if (hasZeroCost) {
            isError = true;
            $('#ScrapentrySubmit').text('<?=$this->lang->line("scrap_entry_add")?>').removeAttr('disabled');
            return false;
            //alert('Cost value cannot be 0. Please correct the values before submitting.');
        }

        if(isError == true)
        {
          $('#ScrapentrySubmit').text('<?=$this->lang->line("proforma_invoice_add")?>').removeAttr('disabled');
          return false;
        }
        else 
        {
          return true;
        }
      });

      $("form#editScrapentryForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editScrapentryForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#editScrapentryForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editScrapentryForm #err_"+id).text("").fadeOut('slow');
          $('form#editScrapentryForm #'+id).removeClass('is-invalid');
          $('form#editScrapentryForm #'+id).addClass('is-valid');
        }
      });

      const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 10000
                          });

      // function autosaveForm()
      // {
      //   var supplier_id =  $("#supplier_id").val();
      //   var isError = false;

      //   if((supplier_id != '') && ($('#product_table_body').find('tr').length > 0))
      //   {
      //     var productDataArray = [];

      //     $("#product_table_body").find('tr').each(function () {

      //       var tr              = $(this).closest("tr");
      //       var productData     = {};

      //       productData['warehouse_product_id']   = tr.find('input[name^="warehouse_product_id"]').val();
      //       productData['product_id']             = tr.find('input[name^="product_id"]').val();
      //       productData['pid']                    = tr.find('input[name^="pid"]').val();
      //       productData['product_name']           = tr.find('span[name^="product_name"]').text();

        
      //       productData['batch_no']               = tr.find('span[name="batch_no"]').text();
        
      //       productData['total_quantity']         = tr.find('span[name="total_quantity"]').text();
        

      //       productData['description']      = tr.find('span[name^="description"]').text();
      //       productData['optional']         = tr.find('textarea[name^="optional"]').val();
      //       productData['selling_price']      = tr.find('input[name="selling_price"]').val();
      //       productData['quantity']         = tr.find('input[name^="quantity"]').val();
      //       productData['cost']             = tr.find('input[name^="cost"]').val();
      //       productData['price']            = tr.find('input[name^="price"]').val();
      //       productData['taxable_value']    = tr.find('span[name="taxable_value"]').text();
      //       productData['discount_id']      = tr.find('select[name^="item_discount"]').val();
      //       productData['discount_type']    = tr.find('input[name^="discount_type"]').val();
      //       productData['discount_value']   = tr.find('input[name^="discount_value"]').val();
      //       productData['discount_amount']  = tr.find('span[name^="discount_amount"]').text();
      //       productData['uom_id']           = tr.find('input[name^="uom_id"]').val();
      //       productData['uom_name']         = tr.find('input[name^="uom_id"]').data('uom_name');
      //       productData['uom_uom']          = tr.find('input[name^="uom_id"]').data('uom_uom');
          
        

      //       productDataArray.push(JSON.stringify(productData));
      //     });

      //     if(productDataArray.length > 0)
      //       $('#scrap_entry_items').val(productDataArray.join('|'));


      //     var scrapFormData       = $('#editScrapentryForm').serialize();

      //     isError = check_if_quantity_is_proper_added();

      //     if(!isError)
      //     {
      //       $.ajax({
      //         async:false,
      //         url: "<?php echo base_url('scrap_entry/edit')?>",
      //         type: "POST",
      //         data:scrapFormData,
      //         dataType: "JSON",
      //         success: function(data){
      //           // if(data.code == 1)
      //           // {
      //           //   Toast.fire({
      //           //     type: 'success',
      //           //     title: data.message
      //           //   });
      //           // }
      //           // else
      //           // {
      //           //   Toast.fire({
      //           //     type: 'error',
      //           //     title: data.message
      //           //   }); 
      //           // }
      //         }
      //       });    
      //     }
      //     else{
      //       // alert('there is issue in check_if_quantity_is_proper_added');
      //     }

          
      //   }
      // }


    
  });
</script>

