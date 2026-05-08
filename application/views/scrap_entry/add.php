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

    .table-responsive > table th{
      white-space: nowrap !important;
      min-width:120px;
    }
  </style>
  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_inventory')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('pack_slip')?>"><?=$this->lang->line('pack_slip_header')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('pack_slip_add')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="addPackSlip" id="addPackSlip" method="POST" action="<?=base_url('pack_slip/add')?>">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('pack_slip_add')?></h3>
                  <div class="card-tools">
                    <div class="btn-group">
                      <button type="submit" name="submit" id="saleSubmit" class="btn btn-info"><?=$this->lang->line('pack_slip_save')?></button>
                    </div>
                  </div>
                </div>
                <div class="card-body">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label><?=$this->lang->line('pack_slip_reference_no')?></label>
                          <input type="text" class="form-control" id="reference_no" name="reference_no" disabled="">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label><?=$this->lang->line('pack_slip_date')?></label>
                          <input type="text" class="form-control datepicker" id="pack_slip_date" name="pack_slip_date" value="<?=date('d-m-Y');?>">
                          <span id="err_pack_slip_date" class="error invalid-feedback"><?=form_error('pack_slip_date');?></span>
                        </div>
                      </div>
                      <?php
                        $warehouse = $this->warehouse_model->get_record_by_is_default();
                      ?>
                      <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=$warehouse->id?>">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="customer">
                            <?=$this->lang->line('pack_slip_customer')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="input-group input-group-sm">
                            <select class="form-control form-control-sm select2bs4 field_validation" name="customer_id" id="customer_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('pack_slip_customer')?>">
                              <option value=""><?=$this->lang->line('select')?></option>
                              <?php
                                foreach ($customers as $value) {
                              ?>
                                <option value="<?=$value->id;?>" <?php echo set_select('id', $value->id); ?>>
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
                          <input type="hidden" name="customer_state_id" id="customer_state_id" value="">
                          <input type="hidden" name="customer_country_id" id="customer_country_id" value="">
                          <span id="err_customer_id" class="error invalid-feedback"><?=form_error('customer_id');?></span>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 ">
                        <div class="well">
                          <div class="row">
                            <!-- <div class="col-sm-12">
                              <a href="" data-toggle="modal" data-tt="tooltip" data-target="#add_service_modal" class="float-right"><?=$this->lang->line('pack_slip_add_new_service')?></a>
                            </div> -->
                            <div class="col-sm-12 search">
                              <span class="fa fa-search"></span>
                              <input id="search_product" class="form-control search_product"  type="text" name="search_product"  placeholder="<?=$this->lang->line('pack_slip_item_search')?>">
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
                          <label><?=$this->lang->line('pack_slip_items')?></label>
                          <div class="table-responsive">
                            <table class="table items table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
                              <thead>
                                <tr>
                                  <th>
                                    <img src="<?php  echo base_url(); ?>assets/images/bin1.png" />
                                  </th>
                                  <th class="span2"><?=$this->lang->line('product_name')?></th>
                                  <th class="span2"><?=$this->lang->line('product_price')?></th>
                                  <th class="span2"><?=$this->lang->line('product_ptd')?></th>
                                  <th class="span2"><?=$this->lang->line('pack_slip_selling_type')?></th>
                                  <th class="span2"><?=$this->lang->line('pack_slip_qty')?></th>
                                 
                                  <th class="span2"><?=$this->lang->line('pack_slip_total_qty')?></th>
                                  <th class="span2"><?=$this->lang->line('pack_slip_shipper_quantity')?></th>
                                  <th class="span2"><?=$this->lang->line('pack_slip_no_of_case')?></th>
                                  <th class="span2"><?=$this->lang->line('pack_slip_no_of_box')?></th>
                                  <th class="span2"><?=$this->lang->line('pack_slip_tax')?></th>
                                  <th class="span2"><?=$this->lang->line('product_expiry_date')?></th>
                                  <th class="span2"><?=$this->lang->line('product_pack')?></th>
                                  <th class="span2"><?=$this->lang->line('product_packing_type')?></th>
                                  <th class="span2"><?=$this->lang->line('product_batch_no')?></th>
                                  <th class="span2 d-none"><?=$this->lang->line('pack_slip_discount')?></th>
                                  <th class="span2 d-none"><?=$this->lang->line('pack_slip_uom')?></th>
                                  <th class="span2 d-none"><?=$this->lang->line('pack_slip_taxable_value')?></th>
                                  <th class="span2 d-none"><?=$this->lang->line('pack_slip_inclusive')?></th>
                                  <th class="span2 d-none"><?=$this->lang->line('pack_slip_total')?></th>
                                </tr>
                              </thead>
                              <tbody id="product_table_body">
                              </tbody>
                            </table>
                          </div>
                          <table class="table table-striped table-bordered table-condensed table-hover total_data d-none">
                            <tr>
                              <td align="right" width="66%"> <?=$this->lang->line('pack_slip_total_discount')?>(<?=$currency?>)</td>
                              <td align='right' class="text-danger" width="34%">
                                -<span id="total_discount">0.00</span>
                                <input type="hidden" name="total_discount" id="t_discount" value="0">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('pack_slip_total_taxable_value')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                +<span id="total_taxable_value">0.00</span>
                                <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="0">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('pack_slip_total_tax')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                +<span id="total_tax">0.00</span>
                                <input type="hidden" name="total_tax" id="t_tax" value="0">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('tds')?>(<?=$currency?>)</td>
                              <td align='right' class="text-success" width="34%">
                                <input type="number" class="form-control" name="tds" id="tds" value="0.00" min="0.00" style="text-align: right">
                              </td>
                            </tr>
                            <tr>
                              <td align="right" width="66%"><?=$this->lang->line('pack_slip_total')?>(<?=$currency?>)</td>
                              <td align='right' width="34%">
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
                                  <a class="nav-link active" href="#external_note" data-toggle="pill"><?php echo $this->lang->line('pack_slip_external_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#internal_note" data-toggle="pill"><?php echo $this->lang->line('pack_slip_internal_note'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#bank_detail" data-toggle="pill"><?php echo $this->lang->line('pack_slip_bank_detail'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#terms_and_condition" data-toggle="pill"><?php echo $this->lang->line('pack_slip_terms_and_condition'); ?></a>
                                </li>
                              </ul>                           
                              <br>
                              <div class="tab-content">
                                <div class="tab-pane active" id="external_note">
                                  <textarea class="col-sm-12 form-control" name="external_note" rows="4" placeholder="<?php echo $this->lang->line('pack_slip_external_note'); ?>"></textarea>
                                </div>
                                <div class="tab-pane" id="internal_note">
                                  <textarea class="col-sm-12 form-control" name="internal_note" rows="4" placeholder="<?php echo $this->lang->line('pack_slip_internal_note'); ?>"></textarea>
                                </div>
                                <div class="tab-pane" id="bank_detail">
                                  <textarea class="col-sm-12 form-control" name="bank_detail" rows="4" placeholder="<?php echo $this->lang->line('pack_slip_bank_detail'); ?>"><?=str_replace("<br />","",$company_setting->bank_detail)?></textarea>
                                </div>
                                <div class="tab-pane" id="terms_and_condition">
                                  <textarea class="col-sm-12 form-control" name="terms_and_condition" rows="4" placeholder="<?php echo $this->lang->line('pack_slip_terms_and_condition');?>"><?=str_replace("<br />","",$company_setting->terms_and_condition)?></textarea>
                                </div>
                              </div>
                            </div>   
                          </div>
                        </div>
                      </div>
                    </div>
                    <!-- <div class="row">
                      <div class="col-md-2">
                        <br/>
                        <div class="form-group clearfix">
                          <div class="icheck-primary d-inline">
                            <input type="checkbox" id="mark_as_paid" name="mark_as_paid">
                            <label for="mark_as_paid">
                              Mark as Paid
                            </label>
                          </div>
                        </div>
                      </div>
                      <div class="col-md-2">
                        <br/>
                        <div class="form-group clearfix">
                          <div class="icheck-primary d-inline">
                            <input type="checkbox" id="mark_as_delivered" name="mark_as_delivered">
                            <label for="mark_as_delivered">
                              Mark as Delivered
                            </label>
                          </div>
                        </div>
                      </div>
                    </div> -->
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="promotions" id="promotions" value='<?=json_encode($promotions)?>'>
                  <input type="hidden" name="rcm" id="rcm" value="<?=$company_setting->default_rcm?>">
                  <input type="hidden" name="pack_slip_items" id="pack_slip_items" value="">
                  <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  <!-- <button type="submit" name="submit" id="saleSubmit" class="btn btn-info"><?=$this->lang->line('pack_slip_add')?></button> -->
                  
                  <span class="btn btn-default float-right" id="cancel" onclick="cancel('sale')"><?=$this->lang->line('pack_slip_cancel')?></span>
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
  // $this->load->view('warehouse/add_warehouse_modal');
  
  // $this->load->view('service/add_service_modal');
?>

<div class="modal fade" id="emptySaleItemWarningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header warning-header">
        <h5 class="modal-title" id="exampleModalLabel"><?=$this->lang->line('pack_slip_message_label')?></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <?=$this->lang->line('empty_pack_slip_warning_label')?>
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

      $("#customer_id").closest('.row').siblings().css('display','none');

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

                $("#product_table_body tr").remove();
                calculateGrandTotal();
              }
          });
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
                'module': '<?=SALE_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var products = data;
                var suggestions = [];
                for(var i = 0; i < products.length; ++i) {

                    var variant1 = (products[i].variant1 != '') ? ' - '+products[i].variant1 : '';
                    var variant2 = (products[i].variant2 != '') ? ' - '+products[i].variant2 : '';
                    var variant3 = (products[i].variant3 != '') ? ' - '+products[i].variant3 : '';

                    suggestions.push(products[i].id+' - '+products[i].name+' - '+products[i].product_category_name+" - "+products[i].tax_rate+"% - "+products[i].batch_no+" - "+products[i].quantity+" - "+products[i].expiry_date);
                    // suggestions.push(products[i].warehouse_products_id+' - '+products[i].name+' - '+products[i].product_category_name+' - '+products[i].price);
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
                'warehouse_product_id': warehouse_products_id, // here product is warehouse_products_id 
                'module': '<?=SALE_MODULE?>',
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                var product = data.product;
                var hold_quantity = data.hold_quantity;

                if(!is_product_exist_in_row(product.warehouse_products_id))
                {
                  add_row(data,hold_quantity);
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
      });

      function add_row(data,hold_quantity)
      {
        var customer_country_id = $('#customer_country_id').val();
        var customer_state_id   = $('#customer_state_id').val();

        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var company_gstin       = '<?=$company_setting->gstin?>';

        var product   = data.product;
        var discounts = data.discount;
        var hold_quantity = parseFloat(hold_quantity);

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

        input_quantity  +=  '<input type="number" class="form-control" name="quantity" value="1" step="0.01" max="'+(product.quantity-hold_quantity)+'" min="0.01">';
        input_quantity  +=  'QTY: '+product.quantity+' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/> Hold QTY: <span name="hold_quantity">'+hold_quantity+'</span><br/>';

      
        input_quantity  +=  '<span name="quantity_update_message" class="quantity_update_message"></span>';

        input_optional  = "";

        input_optional += '<textarea class="form-control d-none" id="textarea" name="optional" rows="4" placeholder="" maxlength="50"></textarea><br/>';

        var select_selling_type = "";
            select_selling_type += '<select class="form-control select2bs4" name="selling_type" style="width: 100%;">';
                select_selling_type += '<option value="strip">strip</option>';
                select_selling_type += '<option value="box">box</option>';
            select_selling_type += '</select>';


        /*var input_quantity =  '<input type="number" class="form-control" name="quantity" value="1" step="1" min="1" max="'+product.quantity+'"><span name="quantity_update_message" class="quantity_update_message"></span>';*/
        var taxable_value  =  '<span name="taxable_value">'+product.price+'</span>';

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

        /* Variant start */

        var variant_array = [];

        if(product.variant1 != '')  
          variant_array.push(product.variant1);
        if(product.variant2 != '')  
          variant_array.push(product.variant2);
        if(product.variant3 != '')  
          variant_array.push(product.variant3);

        var variant = (variant_array.length > 0) ? variant_array.join("-") : '';

        /* Variant end */

        var newRow = $('<tr class="service_row">');
            var cols = "";

            cols += '<td>'
                      + '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
                      + '<input type="hidden" name="product_id" value="'+product.id+'">'
                      + '<input type="hidden" name="pid" value="'+product.pid+'">'
                      + '<input type="hidden" name="warehouse_product_id" value="'+product.warehouse_products_id+'">'  
                    +'</td>';
            cols += '<td><span name="product_name">'+product.name+'</span><br/>'+variant+'<br/><span name="description">'+product.description+'</span><br/>'+input_optional+'</td>';
            cols += '<td>' 
                      +'<input type="number" class="form-control text-right" name="price" step="0.01" value="'+product.price+'">'
                    +'</td>';
            cols += '<td>' 
                      +'<input type="number" class="form-control text-right" name="cost" value="'+product.wp_ptd+'" step="0.01">'
                    +'</td>';
            cols += '<td>'+select_selling_type+'</td>';
            cols += '<td>'+input_quantity+'</td>';
          
            cols += '<td><span name="total_quantity"></span></td>';
            cols += '<td><span name="case_size">'+product.shipper_quantity+'</span></td>';
            cols += '<td><span name="no_of_case"></span></td>';
            cols += '<td><span name="no_of_box"></span></td>';
            cols += '<td class="">'+tax+'</td>';
            cols += '<td><input type="text" name="expiry_date" class="form-control form-control-sm" value="'+((product.expiry_date != '' && product.expiry_date != '0000-00-00') ? change_date_format(product.expiry_date) : '')+'" readonly="readonly"></td>';
            cols += '<td><span name="pack">'+ ((product.packing != null) ? product.packing : "")+'</span></td>';
            cols += '<td><span name="packing_type">'+((product.packing_type != null) ? product.packing_type : "")+'</span></td>';
            cols += '<td><span name="batch_no">'+product.batch_no+'</span></td>';
            
            cols += '<td class="d-none">'+select_discount+'</td>';
            cols += '<td class="d-none">'+input_uom+'</td>';
            cols += '<td class="d-none">'+taxable_value+'</td>';
            
           
            cols += '<td class="d-none">'+tax_type+'</td>';
            cols += '<td class="d-none"><span name="sub_total"></span></td>';
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
            calculateRow(newRow);
      }

      function change_date_format(date)
      {
        if(date != '' && date != null )
        {
          // Get the date string in Y-m-d format
          var dateString = date;

          // Split the date string into year, month, and day components
          var dateParts = dateString.split("-");

          // Create a new date string in d-m-Y format using the day, month, and year components
          var newDateString = dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0];

          // Output the new date string
          return newDateString;
        }
        else{
          return '';
        }
      }


      function is_product_exist_in_row(warehouse_product_id)
      { 
        var isProductExist = false;
        $("#product_table_body").find('tr').each(function () {

          var tr = $(this).closest("tr");

          if(tr.find('input[name="warehouse_product_id"]').val() == warehouse_product_id)
          {
            isProductExist = true;
          }
        });

        return isProductExist;
      }

      function highlight_row(warehouse_product_id)
      {
        $("#product_table_body").find('tr').each(function () {
          var tr  = $(this).closest("tr");

          if(tr.find('input[name="warehouse_product_id"]').val() == warehouse_product_id)
          {
            tr.addClass('highlight_row');

            var existing_quantity = +tr.find('input[name^="quantity"]').val();
            // alert(existing_quantity + 1);
            // tr.find('input[name^="quantity"]').val(existing_quantity + 1).trigger('change');
            // tr.find('span[name^="quantity_update_message"]').text('+1');

            setTimeout(function(){
              tr.removeClass('highlight_row');
              tr.find('span[name^="quantity_update_message"]').text('');
            },5000);
          }
        });        
      }

      $("table.product_table").on('change keyup', 'input[name="cost"], input[name="quantity"], select[name^="item_discount"]', function (event) {

        var tr = $(this).closest('tr');

        if($(this).attr('name') == 'item_discount')
        {
          var discount_id = $(this).val();

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
        else if($(this).attr('name') == 'quantity')
        {
          calculateRow($(this).closest("tr"));
          calculateGrandTotal();

          var total_quantity  = parseFloat(tr.find('span[name="total_quantity"]').text());
          var max_quantity    = (parseFloat(tr.find('input[name="quantity"]').attr('max'))).toFixed(2);
          var quantity        = parseFloat(tr.find('input[name="quantity"]').val());
        

          if((quantity < 0))
          { 
            Swal.fire({
              title: 'FAILURE !!',
              text: "Quantity should be greater than 0",
              type: "warning",
              buttonsStyling: !1,
              confirmButtonText: "Ok, got it!",
              timer: 5000,
              customClass: {
                  confirmButton: "btn btn-primary"
              }
            });

            tr.find(this).focus();
            if(!tr.hasClass('highlight_row'))
              tr.addClass('highlight_row');
          }
          else if(total_quantity > max_quantity)
          {
            Swal.fire({
              title: 'FAILURE !!',
              text: 'Quantity should be less than '+max_quantity,
              type: "warning",
              buttonsStyling: !1,
              confirmButtonText: "Ok, got it!",
              timer: 5000,
              customClass: {
                  confirmButton: "btn btn-primary"
              }
            });

            tr.find(this).focus();
            if(!tr.hasClass('highlight_row'))
              tr.addClass('highlight_row');
          }
          else
          {
            if(tr.hasClass('highlight_row'))
              tr.removeClass('highlight_row');

            calculateRow($(this).closest("tr"));
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

      $(document).on('change','input[name="quantity"]',function(event){
        // alert();
        var tr            = $(this).closest('tr');
        
        var ordered_qty   = tr.find('input[name="quantity"]').val();
        var product_id    = tr.find('input[name="product_id"]').val();
       
        // alert('freeQuantity:'+ freeQuantity);
        tr.find('input[name="quantity"]').val(ordered_qty);
      
        tr.find('span[name="total_quantity"]').text(ordered_qty);

        calculateRow(tr);
        calculateGrandTotal();
      });
      
      // Calculate free quantity
   


      function calculateRow(row)
      {
        var tax_type    = row.find('input[name^="tax_type"]').val();
        
        var case_size = parseFloat(row.find('span[name="case_size"]').text());

        if(tax_type == 0)
        {
          var product_id  = row.find('input[name^="product_id"]').val();
          var quantity    = parseFloat(row.find('input[name^="quantity"]').val());

          row.find('span[name="total_quantity"]').text(quantity);
          // set box and case
          row.find('span[name="no_of_case"]').text( Math.floor((quantity) / case_size));
          row.find('span[name="no_of_box"]').text( (quantity) % case_size);

          var price       = parseFloat(row.find('input[name^="cost"]').val());
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
          
          var igst        = parseFloat(row.find('span[name^="igst"]').text());
          var cgst        = parseFloat(row.find('span[name^="cgst"]').text());
          var sgst        = parseFloat(row.find('span[name^="sgst"]').text());

          var igst_tax    = parseFloat((taxable_value * igst)/100) ;
          var cgst_tax    = parseFloat((taxable_value * cgst)/100) ;
          var sgst_tax    = parseFloat((taxable_value * sgst)/100) ;

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

          row.find('span[name="total_quantity"]').text(parseFloat(quantity));
          // set box and case
          row.find('span[name="no_of_case"]').text( Math.floor((quantity) / case_size));
          row.find('span[name="no_of_box"]').text( (quantity) % case_size);

          var price       = parseFloat(row.find('input[name^="cost"]').val());

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

          // alert(final_discount_value);

          var igst        = parseFloat(row.find('span[name^="igst"]').text());
          var cgst        = parseFloat(row.find('span[name^="cgst"]').text());
          var sgst        = parseFloat(row.find('span[name^="sgst"]').text());

          var igst_tax    = parseFloat(sub_total * igst / (100 + igst));
          var cgst_tax    = parseFloat(sub_total * cgst / (100 + cgst));
          var sgst_tax    = parseFloat(sub_total * sgst / (100 + sgst));

          var taxable_value = sub_total - igst_tax - cgst_tax - sgst_tax;

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

      function check_if_quantity_is_proper_added()
      {
        var isError = false;

        if ($('#product_table_body').find('tr').length) {
          $("#product_table_body").find('tr').each(function () {
            var tr                    = $(this).closest("tr");
            var warehouse_product_id  = tr.find('.single_product').data('warehouse_product_id');

            var total_quantity  = parseFloat(tr.find('span[name="total_quantity"]').text());
            var max_quantity    = (parseFloat(tr.find('input[name="quantity"]').attr('max'))).toFixed(2);
            var quantity        = parseFloat(tr.find('input[name="quantity"]').val());
           
            if((quantity < 0))
            { 
              isError = true;
              highlight_row(warehouse_product_id);
            }
            else if(total_quantity > max_quantity)
            {
              isError = true;
              highlight_row(warehouse_product_id);
            }
          });
        }
        else
        {
          isError = true;
        };

        return isError;
      }

      $('#addPackSlip').submit(function(e){
        // e.preventDefault();

        var isError = false;
        $('#packSlipSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $('form#addPackSlip .field_validation').each(function() {
            var id    = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if(value==null || value==""){
              $("form#addPackSlip #err_"+id).text(field+ " field is required.").fadeIn('slow');
              $('form#addPackSlip #'+id).addClass('is-invalid');
              $('form#addPackSlip #'+id).removeClass('is-invalid');
              isError = true;
            }
            else
            {
              $("form#addPackSlip #err_"+id).text("").fadeOut('slow');
              $('form#addPackSlip #'+id).removeClass('is-invalid');
              $('form#addPackSlip #'+id).addClass('is-valid');
            }
        });

        var productDataArray = [];

        $("#product_table_body").find('tr').each(function () {

            var tr              = $(this).closest("tr");
            var productData     = {};

            productData['warehouse_product_id']   = tr.find('input[name^="warehouse_product_id"]').val();
            productData['product_id']             = tr.find('input[name^="product_id"]').val();
            productData['pid']                    = tr.find('input[name="pid"]').val();
            productData['product_name']           = tr.find('span[name="product_name"]').text();
            
            productData['pack']                   = tr.find('span[name="pack"]').text();
            productData['packing_type']           = tr.find('span[name="packing_type"]').text();
            productData['batch_no']               = tr.find('span[name="batch_no"]').text();
            productData['expiry_date']            = tr.find('input[name="expiry_date"]').val();
            productData['case_size']              = tr.find('span[name="case_size"]').text();            
           
            productData['total_quantity']         = tr.find('span[name="total_quantity"]').text();
            productData['no_of_box']              = tr.find('span[name="no_of_box"]').text();
            productData['no_of_case']             = tr.find('span[name="no_of_case"]').text();

            productData['description']            = tr.find('span[name="description"]').text();
            productData['optional']               = tr.find('textarea[name="optional"]').val();
            productData['quantity']               = tr.find('input[name="quantity"]').val();
            productData['price']                  = tr.find('input[name="price"]').val();
            productData['cost']                   = tr.find('input[name="cost"]').val();
            productData['taxable_value']          = tr.find('span[name="taxable_value"]').text();
            productData['discount_id']            = tr.find('select[name="item_discount"]').val();
            productData['discount_type']          = tr.find('input[name="discount_type"]').val();
            productData['discount_value']         = tr.find('input[name="discount_value"]').val();
            productData['discount_amount']        = tr.find('span[name="discount_amount"]').text();
            productData['uom_id']                 = tr.find('input[name="uom_id"]').val();
            productData['uom_name']               = tr.find('input[name="uom_id"]').data('uom_name');
            productData['uom_uom']                = tr.find('input[name="uom_id"]').data('uom_uom');
            productData['tax_id']                 = tr.find('input[name="tax_id"]').val();
            productData['tax_type']               = tr.find('input[name="tax_type"]').val();
            productData['igst']                   = tr.find('span[name="igst"]').text();
            productData['igst_tax']               = tr.find('span[name="i_tax"]').text();
            productData['cgst']                   = tr.find('span[name="cgst"]').text();
            productData['cgst_tax']               = tr.find('span[name="c_tax"]').text();
            productData['sgst']                   = tr.find('span[name="sgst"]').text();
            productData['sgst_tax']               = tr.find('span[name="s_tax"]').text();
            productData['sub_total']              = tr.find('span[name="sub_total"]').text();

            productDataArray.push(JSON.stringify(productData));
        });

        if(productDataArray.length > 0)
        {
          $('#pack_slip_items').val(productDataArray.join('|'));
        }
        else
        {
          isError = true;
          $('#emptySaleItemWarningModal').modal('show');
        }

        isError = check_if_quantity_is_proper_added();

        if(isError == true)
        {
          $('#packSlipSubmit').text('<?=$this->lang->line("pack_slip_add")?>').removeAttr('disabled');
          return false;
        }
        else 
        {
          return true;
        }
      });

      $("form#addPackSlip .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addPackSlip #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addPackSlip #'+id).addClass('is-invalid');
          $('form#addPackSlip #'+id).removeClass('is-valid');
          return false;
        }
        else{
          $("form#addPackSlip #err_"+id).text("").fadeOut('slow');
          $('form#addPackSlip #'+id).removeClass('is-invalid');
          $('form#addPackSlip #'+id).addClass('is-valid');
        }
      });

      $('.rcm_btn').click(function(e){
        e.preventDefault();

        if($('#product_table_body tr').length > 0)
        {
          $('.rcm-confirmation-body').html('<?=$this->lang->line('pack_slip_rcm_confirmation_message')?>');

          $('#rcm-confirmation').modal({
              backdrop: 'static',
              keyboard: false
          }).on('click', '#rcm-confirmation-confirm', function(e) {

              var rcm = $('#rcm').val();

              if(rcm == 'N')
              {
                $('#rcm').val('Y');
                $('.rcm_btn').text('<?=$this->lang->line('pack_slip_disable_rcm')?>');
              }
              else
              {
                $('#rcm').val('N');
                $('.rcm_btn').text('<?=$this->lang->line('pack_slip_enable_rcm')?>');
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
            $('.rcm_btn').text('<?=$this->lang->line('pack_slip_disable_rcm')?>');
          }
          else
          {
            $('#rcm').val('N');
            $('.rcm_btn').text('<?=$this->lang->line('pack_slip_enable_rcm')?>');
          }  
        }
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
