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
            <form class="form-horizontal" name="editTransferForm" id="editTransferForm" method="POST" action="<?=base_url('transfer/edit')?>">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('transfer_edit')?></h3>
                </div>
                <div class="card-body">
                  <div class="row">
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label><?=$this->lang->line('transfer_date')?></label>
                        <input type="text" class="form-control  datepicker" id="transfer_date" name="transfer_date" value="<?=date('d-m-Y',strtotime($transfer->transfer_date));?>">
                        <span id="err_transfer_date" class="error invalid-feedback"><?=form_error('transfer_date');?></span>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="warehouse">
                          <?=$this->lang->line('transfer_from_warehouse')?>
                          <span class="text-danger">*</span>
                        </label>
                        <select class="form-control form-control-sm select2bs4 field_validation" name="from_warehouse_id" id="from_warehouse_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('purchase_warehouse')?>">
                          <option value=""><?=$this->lang->line('select')?></option>
                          <?php
                            foreach ($warehouses as $value) {
                          ?>
                            <option value="<?=$value->id;?>" 
                              <?php 
                                if(isset($from_warehouse_id))
                                {
                                  if($from_warehouse_id == $value->id)
                                    echo ' selected';
                                }
                                else if($transfer->from_warehouse_id == $value->id)
                                {
                                  echo ' selected';
                                } 
                              ?>
                            >
                              <?= $value->name;?>
                            </option>
                          <?php
                            }
                          ?>
                        </select>
                        <span id="err_from_warehouse_id" class="error invalid-feedback"><?=form_error('from_warehouse_id');?></span>
                      </div>
                    </div>
                    <div class="col-sm-4">
                      <div class="form-group">
                        <label for="warehouse">
                          <?=$this->lang->line('transfer_to_warehouse')?>
                          <span class="text-danger">*</span>
                        </label>
                        <select class="form-control form-control-sm select2bs4 field_validation" name="to_warehouse_id" id="to_warehouse_id" width="100%" class="add-row" placeholder="<?=$this->lang->line('purchase_warehouse')?>">
                          <option value=""><?=$this->lang->line('select')?></option>
                          <?php
                            foreach ($warehouses as $value) {
                              
                          ?>
                            <option value="<?=$value->id;?>"
                              <?php 
                                if(isset($to_warehouse_id))
                                {
                                  if($to_warehouse_id == $value->id)
                                    echo ' selected';
                                }
                                else if($transfer->to_warehouse_id == $value->id)
                                {
                                  echo ' selected';
                                } 
                              ?>
                            >
                              <?= $value->name;?>
                            </option>
                          <?php
                              
                            }
                          ?>
                        </select>
                        <span id="err_to_warehouse_id" class="error invalid-feedback"><?=form_error('to_warehouse_id');?></span>
                      </div>
                    </div>  
                  </div>
                  <div class="row">
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
                              <th class="span2" width="15%"><?=$this->lang->line('transfer_item_description')?></th>
                              <th class="span2" width="8%"><?=$this->lang->line('transfer_qty')?></th>
                              <th class="span2" width="11%"><?=$this->lang->line('transfer_uom')?></th>
                              <th class="span2" width="14%"><?=$this->lang->line('transfer_product_size')?></th>
                              <th class="span2" width="12%"><?=$this->lang->line('transfer_sqm')?></th>
                              <th class="span2" width="12%"><?=$this->lang->line('transfer_total_sqm')?></th>
                              <th class="span2" width="10%">SQM <?=$this->lang->line('transfer_price')?></th>
                              <th class="span2" width="7%"><?=$this->lang->line('transfer_total')?></th>
                            </tr>
                          </thead>
                          <tbody id="product_table_body">
                            <?php 
                              foreach ($transfer_items as $row) {
                            ?>
                              <tr class="product_row">
                                <td>
                                  <span class="delete_item"><i class="fas fa-minus-circle text-danger"></i>
                                  </span>
                                  <input type="hidden" name="product_id" value="<?=$row->product_id?>">
                                  <?php 
                                    $product_id   = $row->product_id;
                                    $cost         = $row->cost;
                                    $warehouse_id = $transfer->from_warehouse_id;

                                    $warehouse_product = $this->warehouse_products_model->get_records_by_product_id_warehouse_id_cost($warehouse_id,$product_id,$cost);
                                  ?>
                                  <input type="hidden" name="warehouse_products_id" value="<?=$warehouse_product->id?>">
                                </td>

                                <td>
                                  <span name="product_name"><?=$row->product_name?></span><br/>
                                  <span name="description"><?=$row->description?></span>
                                </td>
                                
                                <td>

                                  <input type="number" class="form-control" name="quantity" value="<?=$row->quantity?>"  step="1" min="1" max="'+product.quantity+'">
                                  <span>MAX: <?=$warehouse_product->quantity?></span><span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/>
                                  <span name="quantity_update_message" class="quantity_update_message"></span>
                                  <!-- <input type="number" class="form-control" name="quantity" value="<?=$row->quantity?>" step="1" min="1">
                                  <span name="quantity_update_message" class="quantity_update_message"></span> -->
                                </td>
                                <td>
                                  <input type="hidden" name="uom_id" value="<?=$row->uom_id?>" data-uom_name="<?=$row->uom_name?>" data-uom_uom="<?=$row->uom_uom?>">  
                                  <?=$row->uom_uom?>
                                </td>

                                <td>
                                  <span name="product_size"><?=$row->product_size?></span>
                                </td>

                                <td>
                                  <span name="sqm"><?=$row->sqm?></span>
                                </td>

                                <td>
                                  <span name="total_sqm"><?=$row->total_sqm?></span>
                                </td>
                                
                                <td>
                                  <span id="price_span">
                                    <input type="number" class="form-control text-left" name="price" step="0.01" value="<?=$row->price?>" readonly>
                                    <input type="hidden" name="cost" value="<?=$row->cost?>">
                                  </span>
                                </td>

                                
                                
                                <td>
                                 
                                  <span name="span_discount_type" style="display: none" class="discount_type">Discount Value :  <?=$this->session->userdata('currency_symbol')?> </span>
                                  <input type="hidden" name="discount_type" value="<?=$row->discount_type?>">
                                  <span name="discount_amount" style="display: none" class="discount_amount"><?=$row->discount_amount?></span>
                                  <input type="hidden" name="discount_value" value="<?=$row->discount_value?>">
                                  <input type="hidden" name="tax_id" value="<?=$row->tax_id?>">
                                  <span name="c_tax" style="display:none"><?=$row->cgst_tax?></span>
                                  <span name="cgst" style="display:none"><?=$row->cgst?></span>
                                  <span name="s_tax" style="display:none"><?=$row->sgst_tax?></span>
                                  <span name="sgst" style="display:none"><?=$row->sgst?></span>
                                  <span name="i_tax" style="display:none"><?=$row->igst_tax?></span>
                                  <span name="igst" style="display:none"><?=$row->igst?></span>
                                  <span name="taxable_value" style="display: none"><?=$row->taxable_value-$row->discount_amount?></span>
                                  <input type="hidden" name="tax_type" value="<?=$row->tax_type?>">
                                  <span name="sub_total"><?=$row->sub_total?></span>
                                </td>
                                
                              </tr>
                            <?php 
                              }
                            ?>
                          </tbody>
                        </table>
                        <table class="table table-striped table-bordered table-condensed table-hover total_data" style="font-size: 18px;">
                          <tr style="display: none">
                            <td align="right"  width="66%"> <?=$this->lang->line('sale_total_discount')?>(<?=$currency?>)</td>
                            <td align='right' class="text-danger"  width="34%">
                              -<span id="total_discount"><?=$transfer->total_discount?></span>
                              <input type="hidden" name="total_discount" id="t_discount" value="<?=$transfer->total_discount?>">
                            </td>
                          </tr>
                          <tr style="display: none">
                            <td align="right"  width="66%"><?=$this->lang->line('sale_total_taxable_value')?>(<?=$currency?>)</td>
                            <td align='right' class="text-success" width="34%">
                              +<span id="total_taxable_value"><?=$transfer->total_taxable_value?></span>
                              <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="<?=$transfer->total_taxable_value-$transfer->tds?>">
                            </td>
                          </tr>
                          <tr style="display: none">
                            <td align="right"  width="66%"><?=$this->lang->line('sale_total_tax')?>(<?=$currency?>)</td>
                            <td align='right' class="text-success" width="34%">
                              +<span id="total_tax"><?=$transfer->total_tax?></span>
                              <input type="hidden" name="total_tax" id="t_tax" value="<?=$transfer->total_tax?>">
                            </td>
                          </tr>
                          <tr  style="display: none">
                            <td align="right" width="66%"><?=$this->lang->line('tds')?>(<?=$currency?>)</td>
                            <td align='right' class="text-success" width="34%">
                              <input type="number" class="form-control" name="tds" id="tds" value="<?=$transfer->tds?>" style="text-align: right">
                            </td>
                          </tr>
                          <tr>
                            <td align="right"  width="66%"><?=$this->lang->line('sale_total')?>(<?=$currency?>)</td>
                            <td align='right' width="34%">
                              <span id="total"><?=$transfer->total?></span>
                              <input type="hidden" name="total" id="t" value="<?=$transfer->total?>">
                            </td>
                          </tr>
                        </table>

                      </div>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="external_note" value="">
                  <input type="hidden" name="internal_note" value="">
                  <input type="hidden" name="bank_detail" value="">
                  <input type="hidden" name="terms_and_condition" value="">
                  <input type="hidden" name="rcm" id="rcm" value="<?=$transfer->rcm?>">
                  <input type="hidden" name="id" value="<?=$transfer->id?>">
                  <input type="hidden" name="transfer_items" id="transfer_items" value="">
                  <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  <button type="submit" name="submit" id="transferSubmit" class="btn btn-info"><?=$this->lang->line('sale_edit')?></button>
                  <!-- <button type="submit" name="submit" id="transferSubmitPayNow" value="pay" name="pay" class="btn btn-info">Add Sale & Pay Now</button>                      -->
                  <span class="btn btn-default float-right" id="cancel" onclick="cancel('transfer')"><?=$this->lang->line('transfer_cancel')?></span>
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
  // $this->load->view('customer/add_customer_modal');
  // $this->load->view('service/add_service_modal');
?>

<div class="modal fade" id="emptyTransferItemWarningModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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

      // $("#from_warehouse_id").closest('.row').siblings().css('display','none');

      $('#from_warehouse_id').change(function(e){

        var warehouse_id = $(this).val();
        if(warehouse_id != '')
        {

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

      // Service search code begin

      var c_mapping = { };

      $(function(){
        $('#search_product').autoComplete({
          minChars: 1,
          cache: 0,
          source: function(term, suggest){
            term = term.toLowerCase();
            var warehouse_id = $('#from_warehouse_id').val();
            
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
                    suggestions.push(products[i].warehouse_products_id+' - '+products[i].name+' - '+products[i].product_category_name+' - '+products[i].product_size+' - '+products[i].price);
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

                calculateGrandTotal();
                $('#search_product').val('');
              }
            });
          } 
        });
      });

      function add_row(data)
      {
        var customer_country_id = $('#customer_country_id').val();
        var customer_state_id   = $('#customer_state_id').val();

        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var company_gstin       = '<?=$company_setting->gstin?>';

        var product   = data.product;
        var discounts = data.discount;

        var select_discount = "";
            // select_discount += '<select class="form-control select2bs4" name="item_discount" style="width: 100%;">';
            // select_discount += '<option value="">Select</option>';
            //   for(a=0;a<discounts.length;a++)
            //   {
            //     var type_symbol;
            //     if(discounts[a].type == 0)
            //     {
            //       type_symbol = "<?=$this->session->userdata('currency_symbol')?>";
            //     }
            //     else
            //     {
            //       type_symbol = "%"; 
            //     }
            //     select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name+'('+discounts[a].value  + type_symbol +')'+ '</option>';
            //   }
            // select_discount += '</select>'
            select_discount += '<span name="span_discount_type" class="discount_type" style="display:none">Discount Value :  <?=$this->session->userdata('currency_symbol');?> </span><input type="hidden" name="discount_type" value="0">';
            select_discount += '<span name="discount_amount" class="discount_amount" style="display:none">0.0</span><input type="hidden" name="discount_value" value="'+0+'">';

        var input_uom = '<input type="hidden" name="uom_id" value="'+product.uom_id+'" data-uom_name="'+product.uom_name+'" data-uom_uom="'+product.uom_uom+'">'+product.uom_uom;
        var input_quantity = "";
        /* input_quantity  +=      'MIN: '+product.minimum_order_quantity+' <span data-tt="tooltip" title="Minimum qty must be ordered by customer"><i class="fas fa-question" style="float:right;font-size:11px;padding-top:5px"></i></span>';*/

        input_quantity  +=  '<input type="number" class="form-control" name="quantity" value="1" min="1"  step="1" max="'+product.quantity+'">';
        input_quantity  +=  'MAX: '+product.quantity+' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/>';

        input_quantity  +=  '<span name="quantity_update_message" class="quantity_update_message"></span>';


        /*var input_quantity =  '<input type="number" class="form-control" name="quantity" value="1" step="1" min="1" max="'+product.quantity+'"><span name="quantity_update_message" class="quantity_update_message"></span>';*/
        var taxable_value  =  '<span name="taxable_value" style="display:none">'+product.price+'</span>';

        /************    tax begin   *****************/
        var tax = '<input type="hidden" name="tax_id" value="'+product.tax_id+'">';

        // alert(company_state_id);
        // alert(customer_state_id);

        // if(company_country_id == customer_country_id)
        // {
        //   if($('#rcm').val() == 'N' && company_gstin != '')
        //   {
        //     if(company_state_id == customer_state_id)
        //     {
        //       tax += 'CGST : <span name="c_tax">'+0.0+'</span>(<span name="cgst">'+product.cgst+'</span>)<br/>';
        //       tax += 'SGST : <span name="s_tax">'+0.0+'</span>(<span name="sgst">'+product.sgst+'</span>)';
        //       tax += '<span name="i_tax"style="display:none">0</span><span name="igst" style="display:none">0</span>';
        //     }
        //     else
        //     {
        //       tax += 'IGST : <span name="i_tax">'+0.0+'</span>(<span name="igst">'+product.igst+'</span>)<br/>';
        //       tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
        //       tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
        //     }  
        //   }
        //   else
        //   {
        //     tax += 'N/A';
        //     tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
        //     tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
        //     tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';  
        //   }
          
        // }
        // else if((company_country_id != customer_country_id) || $('#rcm').val() == 'Y')
        // {
            // tax += 'N/A';
            tax += '<span name="i_tax" style="display:none">0</span><span name="igst" style="display:none">0</span>';
            tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
            tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
        // }


        /************    tax end   *****************/

        /************    tax type begin   *****************/

        var tax_type  = '<input type="hidden" name="tax_type" value="'+product.tax_type+'">';
        // tax_type      += (product.tax_type == 0) ? "No" : "Yes";

        var product_size = '<span name="product_size">'+product.size+'</span>';
        var sqm       = '<span name="sqm">'+(get_sqm_value(product.size))+'</span>';
        var total_sqm = '<span name="total_sqm">'+(get_sqm_value(product.size))+'</span>';

        /************    tax type end   *****************/

        var newRow = $('<tr class="service_row">');
            var cols = "";

            cols += '<td>'
                      + '<span class="delete_item"><i class="fas fa-minus-circle text-danger"></i></span>'
                      + '<input type="hidden" name="product_id" value="'+product.id+'">'
                      + '<input type="hidden" name="warehouse_products_id" value="'+product.warehouse_products_id+'">'  
                    +'</td>';
            cols += '<td><span name="product_name">'+product.name+'</span><br/><span name="description">'+product.description+'</span></td>';
            cols += '<td>'+input_quantity+'</td>';
            cols += '<td>'+input_uom+'</td>';
            cols += '<td>'+product_size+'</td>';
            cols += '<td>'+sqm+'</td>';
            cols += '<td>'+total_sqm+'</td>';
            cols += '<td>' 
                      +'<span id="price_span">'
                        +'<input type="number" class="form-control" name="price" step="0.01" value="'+product.price+'" readonly>'
                        +'<input type="hidden" name="cost" value="'+product.cost+'">'
                      +'</span>'
                    +'</td>';
            
            // cols += '<td>'+taxable_value+'</td>';
            // cols += '<td>'+tax+'</td>';
            cols += '<td>'+tax_type+tax+select_discount+taxable_value+'<span name="sub_total"></span></td>';
            cols += '</tr>';

            newRow.append(cols);
            $("table.product_table").append(newRow);
            $('.select2bs4').select2({theme: 'bootstrap4'});
            $('[data-tt="tooltip"]').tooltip({trigger : 'hover'});
            calculateRow(newRow);
      }

      function get_sqm_value(product_size)
      {
        var h = parseFloat(product_size.split("x")[0]);
        var w = parseFloat(product_size.split("x")[1]);
        return (w*h).toFixed(2);
      }

      function is_product_exist_in_row(product_id)
      { 
        var isProductExist = false;
        $("#product_table_body").find('tr').each(function () {

          var tr = $(this).closest("tr");

          if(tr.find('input[name^="warehouse_products_id"]').val() == product_id)
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

          if(tr.find('input[name^="warehouse_products_id"]').val() == product_id)
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

      $("table.product_table").on('change', 'input[name^="price"], input[name^="quantity"], select[name^="item_discount"]', function (event) {

        if($(this).attr('name') == 'item_discount')
        {
          var discount_id = $(this).val();
          var tr          = $(this).closest('tr');

          // if(discount_id != '')
          // {
          //   $.ajax({
          //     url: "<?php echo base_url('discount/get_record_detail') ?>",
          //     type: "POST",
          //     dataType: "json",
          //     data:{
          //       'discount_id' : discount_id,
          //       '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          //     },
          //     success: function(data){

          //       var discount = data.discount;

          //       tr.find('input[name^="discount_type"]').val(discount.type);
          //       tr.find('input[name^="discount_value"]').val(discount.value);
                
          //       calculateRow(tr);
          //       calculateGrandTotal();
          //     }
          //   });    
          // }
          // else
          // {
              tr.find('input[name^="discount_type"]').val(0);
              tr.find('input[name^="discount_value"]').val(0);

              calculateRow(tr);
              calculateGrandTotal();
          // }
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



      function calculateRow(row)
      {
        var tax_type    = row.find('input[name^="tax_type"]').val();
        var sqm         = parseFloat(row.find('span[name="sqm"]').text());
        
        // alert(row.find('input[name^="quantity"]')).val();
        var total_sqm   = sqm*row.find('input[name^="quantity"]').val();

        if(tax_type == 0)
        {
          var product_id  = row.find('input[name^="product_id"]').val();
          var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
          var price       = parseFloat(row.find('input[name^="price"]').val());
          var final_discount_value = 0;

          var taxable_value   = parseFloat(total_sqm * price);

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
          row.find('span[name="sqm"]').text(sqm);
          row.find('span[name="total_sqm"]').text(total_sqm);

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

          var sub_total       = (total_sqm * price);

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
          row.find('span[name="sqm"]').text(sqm);
          row.find('span[name="total_sqm"]').text(total_sqm);

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

      $('#editTransferForm').submit(function(e){
        // e.preventDefault();

        var isError = false;
        $('#transferSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $('form#editTransferForm .field_validation').each(function() {
            var id    = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if(value==null || value==""){
              $("form#editTransferForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
              $('form#editTransferForm #'+id).addClass('is-invalid');
              $('form#editTransferForm #'+id).removeClass('is-invalid');
              isError = true;
            }
            else
            {
              $("form#editTransferForm #err_"+id).text("").fadeOut('slow');
              $('form#editTransferForm #'+id).removeClass('is-invalid');
              $('form#editTransferForm #'+id).addClass('is-valid');
            }
        });

        var productDataArray = [];

        $("#product_table_body").find('tr').each(function () {

            var tr              = $(this).closest("tr");
            var productData     = {};

            productData['product_id']       = tr.find('input[name^="product_id"]').val();
            productData['product_name']     = tr.find('span[name^="product_name"]').text();
            productData['description']      = tr.find('span[name^="description"]').text();
            productData['quantity']         = tr.find('input[name^="quantity"]').val();
            productData['product_size']     = tr.find('span[name="product_size"]').text();
            productData['price']            = tr.find('input[name^="price"]').val();
            productData['cost']             = tr.find('input[name^="cost"]').val();
            productData['taxable_value']    = tr.find('span[name^="taxable_value"]').text();
            productData['discount_id']      = tr.find('select[name^="item_discount"]').val();
            productData['discount_type']    = tr.find('input[name^="discount_type"]').val();
            productData['discount_value']   = tr.find('input[name^="discount_value"]').val();
            productData['discount_amount']  = tr.find('span[name^="discount_amount"]').text();
            productData['uom_id']           = tr.find('input[name^="uom_id"]').val();
            productData['uom_name']         = tr.find('input[name^="uom_id"]').data('uom_name');
            productData['uom_uom']          = tr.find('input[name^="uom_id"]').data('uom_uom');
            productData['sqm']              = tr.find('span[name="sqm"]').text();
            productData['total_sqm']        = tr.find('span[name="total_sqm"]').text();
            productData['tax_id']           = tr.find('input[name^="tax_id"]').val();
            productData['tax_type']         = tr.find('input[name^="tax_type"]').val();
            productData['igst']             = tr.find('span[name^="igst"]').text();
            productData['igst_tax']         = tr.find('span[name^="i_tax"]').text();
            productData['cgst']             = tr.find('span[name^="cgst"]').text();
            productData['cgst_tax']         = tr.find('span[name^="c_tax"]').text();
            productData['sgst']             = tr.find('span[name^="sgst"]').text();
            productData['sgst_tax']         = tr.find('span[name^="s_tax"]').text();
            productData['sub_total']        = tr.find('span[name^="sub_total"]').text();

            productDataArray.push(JSON.stringify(productData));
        });

        if(productDataArray.length > 0)
        {
          $('#transfer_items').val(productDataArray.join('|'));
        }
        else
        {
          isError = true;
          $('#emptyTransferItemWarningModal').modal('show');
        }

        if(isError == true)
        {
          $('#transferSubmit').text('<?=$this->lang->line("transfer_add")?>').removeAttr('disabled');
          return false;
        }
        else 
        {
          return true;
        }
      });

      $("form#editTransferForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editTransferForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#editTransferForm #'+id).addClass('is-invalid');
          $('form#editTransferForm #'+id).removeClass('is-valid');
          return false;
        }
        else{
          $("form#editTransferForm #err_"+id).text("").fadeOut('slow');
          $('form#editTransferForm #'+id).removeClass('is-invalid');
          $('form#editTransferForm #'+id).addClass('is-valid');
        }
      });

      $('.rcm_btn').click(function(e){
        e.preventDefault();

        if($('#product_table_body tr').length > 0)
        {
          $('.rcm-confirmation-body').html('<?=$this->lang->line('transfer_rcm_confirmation_message')?>');

          $('#rcm-confirmation').modal({
              backdrop: 'static',
              keyboard: false
          }).on('click', '#rcm-confirmation-confirm', function(e) {

              var rcm = $('#rcm').val();

              if(rcm == 'N')
              {
                $('#rcm').val('Y');
                $('.rcm_btn').text('<?=$this->lang->line('transfer_disable_rcm')?>');
              }
              else
              {
                $('#rcm').val('N');
                $('.rcm_btn').text('<?=$this->lang->line('transfer_enable_rcm')?>');
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
            $('.rcm_btn').text('<?=$this->lang->line('transfer_disable_rcm')?>');
          }
          else
          {
            $('#rcm').val('N');
            $('.rcm_btn').text('<?=$this->lang->line('transfer_enable_rcm')?>');
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