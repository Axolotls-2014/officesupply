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

    .single_product{
      cursor: pointer;
    }
    .product_additional_info{
      font-size: 10px;
    }
  </style>
  <div class="wrapper">
    <div class="content-wrapper">
      <div class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col-md-4">
              <div class="card p-3 mt-3"  >
                <div class="row d-none"> 
                  <div class="col-md-12">
                    <div class="form-group">
                      <select class="form-control form-control-sm form-control-sm select2bs4">
                        <option value="">All Product Categery</option>
                        <?php
                          foreach ($product_categories as $value) {
                        ?>
                            <option value="<?=$value->id?>"><?=$value->name?></option>
                        <?php
                           } 
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <div class="form-group">
                      <input type="search" name="search-input" id="search-input" class="form-control form-control-sm" placeholder="Type here to seach product">
                    </div>
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-12" style="max-height: 600px; overflow-y: scroll;">
                    <table width="100%" class="table table-bordered" id="product_list">
                      <?php 
                        foreach ($warehouse_products as $value) {
                          if($value->quantity > 0)
                          {
                            $product = $this->product_model->get_single_record($value->product_id);
                            $product_category = $this->product_category_model->get_single_record($product->product_category_id);
                          
                      ?>
                            <tr class="single_product" data-id="<?=$value->product_id?>" 
                                                       data-warehouse_product_id="<?=$value->id?>"
                                                       data-product_category_id="<?=$product->product_category_id?>"
                                                     
                                                       data-batch_no="<?=$value->batch_no?>"
                                                    
                                                       data-product_name="<?=$value->name?>"
                                                       data-product_category_name="<?=$product_category->name?>"
                            >
                              <td>
                                <?=$value->name?> <!-- <strong>[<?=$product_category->name?>]</strong> --><br/>
                                <span class="product_additional_info"><strong>Batch No: </strong><?=$value->batch_no?></span> |
                                <span class="product_additional_info"><strong>Available Qty: </strong><?=$value->quantity?></span> 
                               
                              </td>
                            </tr>  
                      <?php
                          }
                        }
                      ?>
                    </table>  
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-8">
              <form class="form-horizontal" name="addPosForm" id="addPosForm" method="POST" action="#">
                <div class="card p-3 mt-3">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <select class="form-control select2bs4" name="customer_id" id="customer_id">
                          <?php 
                            foreach ($customers as $value) {
                          ?>
                              <option value="<?=$value->id?>"
                                      data-customer_country_id="<?=$value->country_id?>"
                                      data-customer_state_id="<?=$value->state_id?>"
                                <?php 
                                  if($value->customer_name == 'Walkin Customer')
                                    echo ' selected';
                                ?>
                              ><?=$value->customer_name.'-'.$value->phone?></option>
                          <?php
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-4 d-none">
                      <div class="form-group">
                        <input list="doctor_name_datalist" class="form-control" name="doctor_name" id="doctor_name" value="<?=set_value("doctor_name") ?>" placeholder="Doctor Name">
                        <datalist id="doctor_name_datalist">
                          <?php 
                            $doctor_name_array = column_unique_value('sale','doctor_name');
                            for ($i=0; $i < sizeof($doctor_name_array); $i++) { 
                          ?>    
                              <option value="<?=$doctor_name_array[$i]?>">
                          <?php 
                            }
                          ?>
                        </datalist>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <button class="btn btn-info btn-block customer_modal" data-toggle="modal" data-target="#add_customer_modal"><i class="fas fa-plus-circle"></i> Customers</button>
                      </div>
                    </div>
                    <div class="col-md-2 d-none">
                      <div class="form-group">
                        <button class="btn btn-info btn-block new_order"><i class="fas fa-plus-circle"></i> New order</button>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <table class="table table-bordered product_table">
                        <thead>
                          <tr>
                            <th style="width:10px;padding-left: 6px;padding-right: 6px;"><i class="fas fa-trash-alt"></i></th>
                            <th>Item</th>
                            <!-- <th>Sale Type</th> -->
                            <th>MRP</th>
                            <th>Selling Price</th>
                            <th>Qty</th>
                            <th>Discount</th>
                            
                            <th>S.Total</th>
                          </tr>  
                        </thead>
                        <tbody id="product_table_body">
                        </tbody>
                        <tfoot>
                          <tr>
                            <td colspan="6"><?=$this->lang->line('sale_total_discount')?>(<?=$currency?>)</td>
                            <td>
                              -<span id="total_discount">0.00</span>
                              <input type="hidden" name="total_discount" id="t_discount" value="0">
                              <input type="number" class="form-control d-none" name="tds" id="tds" value="0.00" min="0.00" style="text-align: right">
                            </td>
                          </tr>  
                          <tr>
                            <td colspan="6"><?=$this->lang->line('sale_total_taxable_value')?>(<?=$currency?>)</td>
                            <td>
                              +<span id="total_taxable_value">0.00</span>
                              <input type="hidden" name="total_taxable_value" id="t_taxable_value" value="0">
                            </td>
                          </tr>  
                          <tr>
                            <td colspan="6"><?=$this->lang->line('sale_total_tax')?>(<?=$currency?>)</td>
                            <td>
                              +<span id="total_tax">0.00</span>
                              <input type="hidden" name="total_tax" id="t_tax" value="0">
                            </td>
                          </tr>  
                          <tr>
                            <td colspan="6"><?=$this->lang->line('sale_total')?>(<?=$currency?>)</td>
                            <td>
                              <span id="total">0.00</span>
                              <input type="hidden" name="total" id="t" value="0">
                            </td>
                          </tr>
                          <tr>
                            <td colspan="6">Available Credit(<?=$currency?>)</td>
                            <td>
                              <span id="available_credit">0.00</span>
                              <input type="hidden" name="available_credit" value="0">
                            </td>
                          </tr>
                          
                        </tfoot>
                      </table>    
                    </div>
                  </div>

                  <div class="row">
                    <!-- <div class="col-md-3">
                      <div class="form-group">
                        <button class="form-control btn btn-sm btn-secondary">Cancel</button>
                      </div>
                    </div> -->
                    <div class="col-md-3">
                      <div class="form-group">
                        <input type="hidden" name="sale_items" id="sale_items" value="">
                        <input type="hidden" name="rcm" id="rcm" value="<?=$company_setting->default_rcm?>">
                        <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                        <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                        <button type="submit" id="posSubmit" class="form-control btn btn-sm btn-primary">Generate Bill</button>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <button class="form-control btn btn-sm btn-warning print d-none" data-print_url="">Print</button>
                      </div>
                    </div>
                    <div class="col-md-3">
                      <div class="form-group">
                        <a href="#" class="form-control btn btn-sm btn-warning payment d-none" data-target="#transaction-modal" data-toggle="modal"  data-sale_id="">Collect Payment</a>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
            </div>
          </div>
          <!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

  <div class="transaction-modal">
    <div class="modal fade" id="transaction-modal" data-backdrop="static" data-keyboard="false">
      <div class="modal-dialog modal-xl">
        <div class="modal-content">
          <div class="modal-header light-purple-header">
            <h4 class="modal-title">
              <?php echo $this->lang->line('sale_transaction');?>
            </h4>
            <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button> -->
          </div>
          <div class="modal-body">
            <div id="accordion">
              <div class="card card-secondary">
                <div class="card-header saleDetail light-secondary-header">
                  <h4 class="card-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#saleDetail" class="collapsed" aria-expanded="false">
                      <?=$this->lang->line('sale_view')?>
                      <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                    </a>
                  </h4>
                </div>
                <div id="saleDetail" class="panel-collapse in collapse" style="">
                  <div class="card-body sale_detail  m-0 p-0">
                  </div>
                </div>
              </div>
              <div class="card card-danger addTransactionCard">
                <div class="card-header addTransaction light-failure-header">
                  <h4 class="card-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#addTransaction" class="" aria-expanded="true">
                      <?=$this->lang->line('sale_payment')?>
                      <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                    </a>
                  </h4>
                </div>
                <div id="addTransaction" class="panel-collapse collapse show" style="">
                  <form id="addTransactionForm" name="addTransactionForm" method="POST">
                    <div class="card-body add_transaction">
                    </div>
                    <div class="card-footer">
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                      <input type="hidden" name="transaction_type" value="<?=RECEIPT_TRANSACTION_TYPE?>">
                      <input type="hidden" name="entry_id" id="sale_id" value="">
                      <input type="hidden" name="module" value="<?=SALE_MODULE?>">
                      <input type="hidden" name="from_account" id="from_account" value="">
                      <button type="submit" class="btn btn-info" name="addTransactionSubmit" id="addTransactionSubmit">
                        <?php echo $this->lang->line('submit');?>
                      </button>
                      <button type="reset" class="btn btn-default">
                        <?php echo $this->lang->line('reset');?>
                      </button>
                    </div>
                  </form>
                </div>
              </div>
              <div class="card card-success">
                <div class="card-header transactionEntries light-success-header">
                  <h4 class="card-title">
                    <a data-toggle="collapse" data-parent="#accordion" href="#transactionEntries" class="collapsed" aria-expanded="false">
                      <?=$this->lang->line('sale_previous_transaction')?>
                      <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                    </a>
                  </h4>
                </div>
                <div id="transactionEntries" class="panel-collapse collapse">
                  <div class="card-body transaction_entries m-0 p-0">
                    
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="is_there_change_in_transaction" id="is_there_change_in_transaction" value="false">
            <form action="<?php echo base_url('sale/delete');?>" method="POST" name="deleteSaleForm" id="deleteSaleForm">
              <input type="hidden" name="id" value="">
              <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
              <button type="submit"  name="deleteSaleSubmit" id="deleteSaleSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('cancel');?> Invoice</button>
            </form>
            <!-- <button type="button" class="btn btn-default" data-dismiss="modal">
              <?php echo $this->lang->line('cancel');?>
            </button> -->
          </div>
        </div>
      </div>
    </div>
  </div>

  
<?php 
  $this->load->view('layout/footer');
  $this->load->view('customer/ajax_add_customer_modal');
?>

<script type="text/javascript">

  $(document).ready(function(e){

    // $('.customer_modal').on('click' , function(){

    //   $('.payment').prop('disabled', true);
    // });
    

      $('.print').on('click',function(ev){
        ev.preventDefault();
        var url = $(this).data('print_url');
        var myWindow=window.open(url,'','width=600,height=800');
        myWindow.focus();
      });

      $('.new_order').on('click',function(ev){
        ev.preventDefault();
        // $('#product_table_body tr').remove();
        // $('#sale_items').val('');
        // $('.print').addClass('d-none');
        // $('.payment').addClass('d-none');



        // $('#total_taxable_value').text('0');
        // $('#t_taxable_value').val(0);

        // $('#total_discount').text('0');
        // $('#t_discount').val(0);

        // $('#total_tax').text('0');
        // $('#t_tax').val(0);

        // $('#total').text('0');
        // $('#t').val(0);

        // calculateRow();
        // calculateGrandTotal();

        window.location.reload(true);
      });

      $("#search-input").on('keyup',function() {
        var searchValue = $(this).val().toLowerCase();

        $("table#product_list tr.single_product").each(function() {
          var row = $(this);
          // var productName = row.find("td:first-child").text().toLowerCase();
          var batchNo = row.data("batch_no").toLowerCase();
          var expiryDate = row.data("expiry_date").toLowerCase();
          var productName = row.data("product_name").toLowerCase();
          var productCategoryName = row.data("product_category_name").toLowerCase();
        
          
          // Search in td text as well
          
          if (searchValue === "" || 
              productName.indexOf(searchValue) !== -1 ||
              productCategoryName.indexOf(searchValue) !== -1 ||
              batchNo.indexOf(searchValue) !== -1 ||
              expiryDate.indexOf(searchValue) !== -1 ||
              salt.indexOf(searchValue) !== -1) {
            row.show();
          } else {
            row.hide();
          }
        });
      });

      $('.single_product').on('click',function(ev){

        var warehouse_product_id = $(this).closest('tr').data('warehouse_product_id');
        $.ajax({
          url: "<?php echo base_url('product/get_record_detail') ?>",
          type: "POST",
          dataType: "json",
          data:{
            'product_id': warehouse_product_id, // here product is warehouse_products_id 
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
            // $('#search_product').val('');
          }
        });
      });

      // Add keyup event handler to search box to update results on input change
      $("#search-input").on("keyup", function() {
        $("#search-button").trigger('click');
      });

      // $("#customer_id").closest('.row').siblings().css('display','none');

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

      // $('#customer_id').change(function(e){

      //   var customer_id = $(this).val();
      //   if(customer_id != '')
      //   {
      //     if($('#warehouse_id').val() != '')
      //     {
      //       $("#customer_id").closest('.row').siblings().fadeIn(10);  
      //     }

      //     $.ajax({
      //         url: "<?php echo base_url('customer/get_record_details') ?>",
      //         type: "POST",
      //         dataType: "json",
      //         data: {
      //             'customer_id' : customer_id,
      //             '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
      //         },
      //         success: function(data){
      //           var customer = data.customer;
      //           $('#customer_state_id').val(customer.state_id);
      //           $('#customer_country_id').val(customer.country_id);

      //           $("#product_table_body tr").remove();
      //           calculateGrandTotal();
      //         }
      //     });
      //   }
      // }); 

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
        var customer_country_id = $('#customer_id').find(":selected").data('customer_country_id');
        var customer_state_id   = $('#customer_id').find(":selected").data('customer_state_id');

        var company_country_id  = $('#company_country_id').val();
        var company_state_id    = $('#company_state_id').val();
        var company_gstin       = '<?=$company_setting->gstin?>';
        var gst_registration_type = <?=$company_setting->gst_registration_type?>;

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
            select_discount += '<span name="span_discount_type" class="discount_type d-none">Discount Value :  <?=$this->session->userdata('ccurrency_symbol');?> </span><input type="hidden" name="discount_type" value="0">';
            select_discount += '<span name="discount_amount" class="discount_amount d-none">0.0</span><input type="hidden" name="discount_value" value="'+0+'">';

    
            

        var input_uom = '<input type="hidden" name="uom_id" value="'+product.uom_id+'" data-uom_name="'+product.uom_name+'" data-uom_uom="'+product.uom_uom+'">'+product.uom_uom;

        // var ordered_qty =  '<input type="number" class="form-control" name="ordered_qty" value="1" step="0.01" min="0.01">';

        var input_quantity = "";

        input_quantity  +=  '<input type="number" class="form-control" name="quantity" value="1" step="0.01" max="'+(product.quantity-hold_quantity)+'"  data-price="'+(product.price)+'">';
        input_quantity  +=  '<div class="d-none">QTY: <span name="available_quantity">'+(product.quantity-hold_quantity)+'</span> <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/> Hold QTY: <span name="hold_quantity">'+hold_quantity+'</span></div>';
        
        // input_quantity  +=  '<input type="number" class="form-control" name="quantity" value="'+product.no_of_strips_in_box+'" step="0.01" max="'+(product.quantity*product.no_of_strips_in_box)+'" min="0.01" data-no_of_strips_in_box="'+product.no_of_strips_in_box+'">';
        // input_quantity  +=  'QTY: '+ (product.quantity*product.no_of_strips_in_box)+' <span data-tt="tooltip" title="Available qty in warehouse"><i class="far fa-question-circle" style="float:right;font-size:11px;padding-top:5px"></i></span><br/>';

        // var free_quantity =  '<input type="number" class="form-control" name="free_quantity" value="0" step="0.01">';


        input_quantity  +=  '<span name="quantity_update_message" class="quantity_update_message"></span>';

        input_optional  = "";

        input_optional += '<textarea class="form-control d-none" id="textarea" name="optional" rows="4" placeholder="" maxlength="50"></textarea>';


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
                      + '<input type="hidden" name="warehouse_product_id" value="'+product.warehouse_products_id+'">'  
                      // + '<input type="hidden" name="pid" value="'+product.pid+'">'  
                      // + '<input type="hidden" name="purchase_cost" value="'+product.purchase_cost+'">'  
                    +'</td>';
            cols += '<td><span name="product_name">'+product.name+'</span><span name="description" class="d-none">'+product.description+'</span>'+input_optional+'</td>';
          
            cols += '<td>' 
                      +'<input type="number" class="form-control text-right" name="price" value="'+product.price+'" min="0.01" step="any" readonly="readonly">'
                    +'</td>';
            cols += '<td>' 
                      +'<input type="number" class="form-control text-right" name="selling_price" value="'+((product.selling_price))+'" min="0.01" step="any">'
                      +'<input type="hidden" class="form-control text-right" name="cost" value="'+((product.cost))+'" min="0.01">'
                    +'</td>';
            cols += '<td>'+input_quantity+'</td>';
            // cols += '<td class="d-none">'+free_quantity+'</td>';
            cols += '<td class="">'+select_discount+'</td>';
            cols += '<td class="d-none"><span name="total_quantity"></span></td>';
          
            // cols += '<td class="d-none"><span name="no_of_case"></span></td>';
            // cols += '<td class="d-none"><span name="no_of_box"></span></td>';
            cols += '<td class="d-none">'+taxable_value+'</td>';
            cols += '<td class="d-none">'+tax+'</td>';
          
        
            cols += '<td class="d-none"><span name="batch_no">'+product.batch_no+'</span></td>';
            
            
            cols += '<td class="d-none">'+input_uom+'</td>';
            
            
           
            cols += '<td class="d-none">'+tax_type+'</td>';
            cols += '<td class=""><span name="sub_total"></span></td>';
            cols += '</tr>';

            newRow.append(cols);
            $('#product_table_body').prepend(newRow);
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

      // function change_date_format(date)
      // {
      //   if(date != '' && dateString !== null)
      //   {
      //     // Get the date string in Y-m-d format
      //     var dateString = date;

      //     // Split the date string into year, month, and day components
      //     var dateParts = dateString.split("-");

      //     // Create a new date string in d-m-Y format using the day, month, and year components
      //     var newDateString = dateParts[2] + "-" + dateParts[1] + "-" + dateParts[0];

      //     // Output the new date string
      //     return newDateString;
      //   }
      //   else{
      //     return '';
      //   }
        
        
      // }

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

            var existing_quantity = +tr.find('input[name="quantity"]').val();
            // alert(existing_quantity + 1);
            // tr.find('input[name="quantity"]').val(existing_quantity + 1).trigger('change');
            // tr.find('span[name="quantity_update_message"]').text('+1');

            setTimeout(function(){
              tr.removeClass('highlight_row');
              tr.find('span[name="quantity_update_message"]').text('');
            },3000);
          }
        });        
      }

      $("table.product_table").on('change keyup', 'input[name="cost"], input[name="quantity"],  select[name="item_discount"]', function (event) {

        var tr          = $(this).closest('tr');

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

                tr.find('input[name="discount_type"]').val(discount.type);
                tr.find('input[name="discount_value"]').val(discount.value);
                
                calculateRow(tr);
                calculateGrandTotal();
              }
            });    
          }
          else
          {
              tr.find('input[name="discount_type"]').val(0);
              tr.find('input[name="discount_value"]').val(0);

              calculateRow(tr);
              calculateGrandTotal();
          }
        }
        else if($(this).attr('name') == 'quantity' )
        {
          calculateRow($(this).closest("tr"));
          calculateGrandTotal();

          var total_quantity  = parseFloat(tr.find('span[name="total_quantity"]').text());
          var max_quantity    = (parseFloat(tr.find('input[name="quantity"]').attr('max'))).toFixed(2);
          var quantity        = parseFloat(tr.find('input[name="quantity"]').val());
          

          if((quantity < 0) )
          { 
            Swal.fire({
              title: 'WARNING !!',
              text: "quantity should be greater than 0",
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
              title: 'WARNING !!',
              text: 'quantity should be less than '+max_quantity,
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

      // set sptr value based on ptd 
      // $(document).on('change keyup','input[name="ptd"]',function(event){
      //   // alert();
      //   var tr  = $(this).closest('tr');
      //   var ptd = parseFloat($(this).val());
      //   var no_of_strips_in_box = parseFloat(tr.find('input[name="quantity"]').data('no_of_strips_in_box'));

      //   var sptr = parseFloat(ptd/no_of_strips_in_box).toFixed(2);
      //   tr.find('input[name="sptr"]').val(sptr);

      //   calculateRow(tr);
      //   calculateGrandTotal();
      // });

      // $(document).on('change',input[name="quantity"]',function(event){
      //   // alert();
      //   var tr                      = $(this).closest('tr');
    

      //   var price                   = parseFloat(tr.find('input[name="quantity"]').data('price'));

   
      //   var product_id              = tr.find('input[name="product_id"]').val();
      //   var selling_price           = tr.find('input[name="selling_price').val();
        
      //   if(selling_type == 'box')
      //   {
      //     var cost = strip_cost*no_of_strips_in_box;

      //     tr.find('input[name="cost"]').val(cost);
      //     tr.find('input[name="selling_price"]').val(price);
      //     tr.find('input[name="price"]').val(price);
      //     tr.find('input[name="available_quantity"]').val((available_strip_quantity/no_of_strips_in_box).toFixed(2));
      //     tr.find('input[name="quantity"]').attr('max',(available_strip_quantity/no_of_strips_in_box).toFixed(2));
      //   }
      //   else if(selling_type == 'strip')
      //   {
      //     var cost = strip_cost;
          
      //     tr.find('input[name="cost"]').val(cost);
      //     tr.find('input[name="selling_price"]').val((price/no_of_strips_in_box).toFixed(2));
      //     tr.find('input[name="price"]').val(price/no_of_strips_in_box);
      //     tr.find('input[name="available_quantity"]').val((available_strip_quantity).toFixed(2));
      //     tr.find('input[name="quantity"]').attr('max',(available_strip_quantity).toFixed(2));
      //   }
      //   else if(selling_type == 'tablet')
      //   {
      //     var cost = (strip_cost/no_of_tablets_per_strip).toFixed(2);

      //     tr.find('input[name="cost"]').val(cost);
      //     tr.find('input[name="price"]').val(price/no_of_strips_in_box);
      //     tr.find('input[name="selling_price"]').val(((price/no_of_strips_in_box)/(no_of_tablets_per_strip)).toFixed(2));
      //     tr.find('input[name="available_quantity"]').val((available_strip_quantity*no_of_tablets_per_strip).toFixed(2)); 
      //     tr.find('input[name="quantity"]').attr('max',(available_strip_quantity*no_of_tablets_per_strip).toFixed(2));
      //   }

      //   calculateRow(tr);
      //   calculateGrandTotal();
      // });


      function calculateRow(row)
      {
        var tax_type    = row.find('input[name="tax_type"]').val();
        // var free_quantity = parseFloat(row.find('input[name="free_quantity"]').val());
        // var case_size = parseFloat(row.find('span[name="case_size"]').text());

        // alert(free_quantity);

        // if(tax_type == 0)
        // {
        //   var product_id  = row.find('input[name="product_id"]').val();
        //   var quantity    = parseFloat(row.find('input[name="quantity"]').val());

        //   row.find('span[name="total_quantity"]').text(quantity+free_quantity);
        //   // set box and case
        //   row.find('span[name="no_of_case"]').text( Math.floor((quantity+free_quantity) / case_size));
        //   row.find('span[name="no_of_box"]').text( (quantity+free_quantity) % case_size);

        //   var price       = parseFloat(row.find('input[name="selling_price"]').val());
        //   var final_discount_value = 0;

        //   var taxable_value   = parseFloat(quantity * price);

        //   // alert('price'+price);
        //   // alert('q'+quantity);
        //   // alert('t'+taxable_value);


        //   var discount_type   = row.find('input[name="discount_type"]').val();
        //   var discount_value  = parseFloat(row.find('input[name="discount_value"]').val());  

        //   var final_discount_value = 0;

        //   if(discount_type == 0)
        //   {
        //     final_discount_value = discount_value;
        //   }
        //   else
        //   {
        //     final_discount_value = (taxable_value * discount_value)/100;
        //   }

        //   taxable_value   = taxable_value - final_discount_value;

        //   // alert(taxable_value);
          
        //   var igst        = parseFloat(row.find('span[name="igst"]').text());
        //   var cgst        = parseFloat(row.find('span[name="cgst"]').text());
        //   var sgst        = parseFloat(row.find('span[name="sgst"]').text());

        //   var igst_tax    = parseFloat((taxable_value * igst)/100) ;
        //   var cgst_tax    = parseFloat((taxable_value * cgst)/100) ;
        //   var sgst_tax    = parseFloat((taxable_value * sgst)/100) ;

        //   // alert(igst);
        //   // alert(cgst);
        //   // alert(sgst);

        //   var sub_total       = parseFloat(taxable_value + igst_tax + cgst_tax + sgst_tax);

        //   // alert(sub_total);

        //   row.find('span[name="i_tax"]').text(igst_tax.toFixed(2));
        //   row.find('span[name="c_tax"]').text(cgst_tax.toFixed(2));
        //   row.find('span[name="s_tax"]').text(sgst_tax.toFixed(2));
        //   row.find('span[name="discount_amount"]').text(final_discount_value.toFixed(2));

        //   row.find('span[name="taxable_value"]').text(taxable_value.toFixed(2));
        //   row.find('span[name="sub_total"]').text(sub_total.toFixed(2));  
        // }
        // else
        // {
          var final_discount_value = 0;
          var quantity    = parseFloat(row.find('input[name="quantity"]').val());

          row.find('span[name="total_quantity"]').text(parseFloat(quantity));
          // set box and case
          // row.find('span[name="no_of_case"]').text( Math.floor((quantity) / case_size));
          // row.find('span[name="no_of_box"]').text( (quantity+free_quantity) % case_size);

          var price       = parseFloat(row.find('input[name="selling_price"]').val());

          var discount_type   = row.find('input[name="discount_type"]').val();
          var discount_value  = parseFloat(row.find('input[name="discount_value"]').val()); 

          var sub_total       = (quantity * price);

          // alert(price);
          // alert(discount_type);
          // alert(discount_value);
          // alert(sub_total);

          if(discount_type == 0)
          {
            final_discount_value = discount_value;
          }
          else
          {
            final_discount_value = (sub_total * discount_value)/100;
          }

          sub_total = sub_total - final_discount_value;

          // alert(sub_total);

          // alert(final_discount_value);

          var igst        = parseFloat(row.find('span[name="igst"]').text());
          var cgst        = parseFloat(row.find('span[name="cgst"]').text());
          var sgst        = parseFloat(row.find('span[name="sgst"]').text());

          var igst_tax    = parseFloat(sub_total * igst / (100 + igst));
          var cgst_tax    = parseFloat(sub_total * cgst / (100 + cgst));
          var sgst_tax    = parseFloat(sub_total * sgst / (100 + sgst));

          var taxable_value = sub_total - igst_tax - cgst_tax - sgst_tax;

          row.find('span[name="i_tax"]').text(igst_tax.toFixed(2));
          row.find('span[name="c_tax"]').text(cgst_tax.toFixed(2));
          row.find('span[name="s_tax"]').text(sgst_tax.toFixed(2));
          row.find('span[name="discount_amount"]').text(final_discount_value.toFixed(2));

          row.find('span[name="taxable_value"]').text(taxable_value.toFixed(2));
          row.find('span[name="sub_total"]').text(sub_total.toFixed(2));   

        // }
        
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
            total_taxable_value += parseFloat(tr.find('span[name="taxable_value"]').text());
            total_discount      += parseFloat(tr.find('span[name="discount_amount"]').text());
            total_cgst          += parseFloat(tr.find('span[name="c_tax"]').text());
            total_sgst          += parseFloat(tr.find('span[name="s_tax"]').text());
            total_igst          += parseFloat(tr.find('span[name="i_tax"]').text());
            total               += parseFloat(tr.find('span[name="sub_total"]').text()); 
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

      $('#addPosForm').submit(function(e){
        e.preventDefault();

        var isError = false;
        $('#posSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $('form#addPosForm .field_validation').each(function() {
            var id    = $(this).attr('id');
            var value = $(this).val();
            var field = $(this).attr('placeholder');

            if(value==null || value==""){
              $("form#addPosForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
              $('form#addPosForm #'+id).addClass('is-invalid');
              $('form#addPosForm #'+id).removeClass('is-invalid');
              isError = true;
            }
            else
            {
              $("form#addPosForm #err_"+id).text("").fadeOut('slow');
              $('form#addPosForm #'+id).removeClass('is-invalid');
              $('form#addPosForm #'+id).addClass('is-valid');
            }
        });

        var productDataArray = [];

        $("#product_table_body").find('tr').each(function () {

            var tr              = $(this).closest("tr");
            var productData     = {};

            productData['warehouse_product_id']   = tr.find('input[name="warehouse_product_id"]').val();
            productData['product_id']             = tr.find('input[name="product_id"]').val();
            // productData['pid']                    = tr.find('input[name="pid"]').val();
            // productData['purchase_cost']          = tr.find('input[name="purchase_cost"]').val();
            productData['product_name']           = tr.find('span[name="product_name"]').text();
              
            // productData['selling_type']           = tr.find('select[name="selling_type"]').val();

            // productData['pack']                   = tr.find('span[name="pack"]').text();
            // productData['packing_type']           = tr.find('span[name="packing_type"]').text();
            // productData['batch_no']               = tr.find('span[name="batch_no"]').text();
            // productData['expiry_date']            = tr.find('input[name="expiry_date"]').val();
            // productData['case_size']              = tr.find('span[name="case_size"]').text();            
            // productData['free_quantity']          = tr.find('input[name="free_quantity"]').val();
            // productData['total_quantity']         = tr.find('span[name="total_quantity"]').text();
            // productData['no_of_box']              = tr.find('span[name="no_of_box"]').text();
            // productData['no_of_case']             = tr.find('span[name="no_of_case"]').text();

            productData['description']            = tr.find('span[name="description"]').text();
            // productData['optional']               = tr.find('textarea[name="optional"]').val();
            productData['quantity']               = tr.find('input[name="quantity"]').val();
            productData['selling_price']          = tr.find('input[name="selling_price"]').val();
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

            // alert(productData['ptd']);

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

        isError = check_if_quantity_is_proper_added();

        // return false;

        if(isError == true)
        {
          $('#posSubmit').text('Generate Bill').removeAttr('disabled');
          return false;
        }
        else 
        {
          var formData = $('#addPosForm').serialize();

          $.ajax({
            url: "<?php echo base_url('pos/index')?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(response){
              if(response.code==1)
              { 
                // Swal.fire({
                //   text: response.message,
                //   icon: "success",
                //   buttonsStyling: !1,
                //   confirmButtonText: "Ok, got it!",
                //   timer: 3000,
                //   customClass: {
                //       confirmButton: "btn btn-primary"
                //   }
                // });

                $('.print').data('print_url','<?=base_url("pos/print")?>/'+response.sale_id);
                $('.payment').data('sale_id',response.d_sale_id);
                // $('.print').removeClass('d-none');
                // $('.payment').removeClass('d-none');
                $('.payment').trigger('click');

              }
              else if(response.code == 2)
              {
                // $.each(response.errors, function(key, value) {
                //   $("form#addProductForm  #err_"+key).text(value);
                // });
                // $('form#addProductForm #addProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              }
              else
              {
                Swal.fire({
                  title: 'SUCCESS !!',
                  text: response.message,
                  icon: "success",
                  buttonsStyling: !1,
                  confirmButtonText: "Ok, got it!",
                  timer: 3000,
                  customClass: {
                      confirmButton: "btn btn-primary"
                  }
                });
                $('#posSubmit').text('Generate Bill').removeAttr('disabled');
              }

              $('#posSubmit').text('Generate Bill').removeAttr('disabled');
            }
          });
        }
      });

    

      $("form#addPosForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addPosForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addPosForm #'+id).addClass('is-invalid');
          $('form#addPosForm #'+id).removeClass('is-valid');
          return false;
        }
        else{
          $("form#addPosForm #err_"+id).text("").fadeOut('slow');
          $('form#addPosForm #'+id).removeClass('is-invalid');
          $('form#addPosForm #'+id).addClass('is-valid');
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


<script type="text/javascript">
  $(document).ready(function(e){

    $('#transaction-modal').on('show.bs.modal', function (e) {

      $('.addTransactionCard').css('display','block');

      var sale_id = $(e.relatedTarget).data('sale_id');
      $('#sale_id').val(sale_id);
      
      $.ajax({
        url: "<?php echo base_url('sale/view')?>/"+sale_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){

          $(".sale_detail").html(data.sale_view);
          $(".add_transaction").html(data.add_transaction);
          $("#from_account").val(data.from_account);
          $(".transaction_entries").html(data.previous_transaction_view);

          if(data.due_amount == 0)
          {
            $('.addTransactionCard').css('display','none');
            // collapse all accordian
            $('#transactionEntries').collapse("hide");
            $('#saleDetail').collapse("show");  
          }
          else
          {
            // collapse all accordian
            $('#transactionEntries').collapse("hide");
            $('#addTransaction').collapse("show");
            $('#saleDetail').collapse("hide");  
          }
        }
      });
    });

    $(document).on('hide.bs.modal','#transaction-modal' ,function (e) {

      // alert($('#is_there_change_in_transaction').val());
      
      if($('#is_there_change_in_transaction').val() == "true")
      {
        // location.reload();
      }
      
    });

    $('body').on('click', '.transactionEntries', function() {
      $('#transactionEntries').collapse("show");
      $('#addTransaction').collapse("hide");
      $('#saleDetail').collapse("hide");
    });

    $('body').on('click', '.addTransaction', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("show");
      $('#saleDetail').collapse("hide");
    });

    $('body').on('click', '.saleDetail', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("hide");
      $('#saleDetail').collapse("show");
    });

    $(document).on('click', '.make_payment', function (event) {
      var transaction_amount = $(this).data('transaction_amount');
      $('#transaction_amount').val(transaction_amount);
      $('.addTransaction').trigger('click');
    });

    $('body').on('change', '#payment_mode', function() {
      var payment_mode = $(this).val();

      if(payment_mode == '')
      {
        $('.transaction_mode_row').fadeOut(10);
      }
      else if(payment_mode == 0)
      {
        $('.transaction_mode_row').fadeOut(10);
      }
      else if(payment_mode == 1)
      {
        $('.transaction_mode_row').fadeOut(10);
        $('.credit_card_row').fadeIn();
      }
      else if(payment_mode == 2)
      {
        $('.transaction_mode_row').fadeOut(10);
        $('.cheque_row').fadeIn();
      }

      $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
      $('form#addTransactionForm #credit_card_no').removeClass('is-valid');

      $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
      $('form#addTransactionForm #cheque_no').removeClass('is-valid');
    });    

    $('body').on('click', '#addTransactionSubmit', function(e) {
      e.preventDefault();
      var isError = false;

      $('form#addTransactionForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addTransactionForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addTransactionForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addTransactionForm #err_"+id).text("").fadeOut('slow');
            $('form#addTransactionForm #'+id).removeClass('is-invalid');
            $('form#addTransactionForm #'+id).addClass('is-valid');
          }
      });

      if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
      {
        var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
        $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
        $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
        isError = true;
      }
      else
      {
        $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
        $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
        $('form#addTransactionForm #credit_card_no').addClass('is-valid');
      }

      if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
      {
        var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
        $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
        $('form#addTransactionForm #cheque_no').addClass('is-invalid');
        isError = true;
      }
      else
      {
        $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
        $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
        $('form#addTransactionForm #cheque_no').addClass('is-valid');
      }

      if(isError == true)
      {
        return false;
      }  
      else 
      {
        var transactionFormData = $('#addTransactionForm').serialize();
        var sale_id = $('#sale_id').val();

        $('#addTransactionSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled')

        $.ajax({
          url: "<?php echo base_url('transaction/add')?>",
          type: "POST",
          data: transactionFormData,
          dataType: "JSON",
          saleId: sale_id,
          success: function(data){

            $(".transaction_entries").html(data.previous_transaction);

            $('#addTransactionSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            $('#is_there_change_in_transaction').val('true');

            $.ajax({
              url: "<?php echo base_url('sale/view')?>/"+sale_id,
              type: "GET",
              dataType: "JSON",
              success: function(data){

                $(".sale_detail").html(data.sale_view);
                $(".add_transaction").html(data.add_transaction);
                $("#to_account").val(data.to_account);
                $(".transaction_entries").html(data.previous_transaction_view);

                $('#transaction-modal').modal('hide');

                $('.print').trigger('click');
                $('.new_order').trigger('click');

                // if(data.due_amount == 0)
                // {
                //   $('.addTransactionCard').css('display','none');
                //   // collapse all accordian
                //   $('#transactionEntries').collapse("show");
                //   $('#saleDetail').collapse("hide");  
                // }
                // else
                // {
                //   // collapse all accordian
                //   $('#transactionEntries').collapse("show");
                //   $('#addTransaction').collapse("hide");
                //   $('#saleDetail').collapse("hide");  
                // }
              }
            });
          }
        });
      } 
    });

    $(document).on("blur change keyup", "form#addTransactionForm .field_validation" , function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addTransactionForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addTransactionForm #err_"+id).text("").fadeOut('slow');
          $('form#addTransactionForm #'+id).removeClass('is-invalid');
          $('form#addTransactionForm #'+id).addClass('is-valid');
        }

        if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
        {
          var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
          $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
          $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
          $('form#addTransactionForm #credit_card_no').addClass('is-valid');
        }

        if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
        {
          var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
          $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #cheque_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
          $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
          $('form#addTransactionForm #cheque_no').addClass('is-valid');
        }
    });

    $(document).on("blur change keyup","form#addTransactionForm #credit_card_no",  function (event){
              
        if($('form#addTransactionForm #credit_card_no').val() == "" && $('form#addTransactionForm #payment_mode').val() == 1)
        {
          var placeholder = $('form#addTransactionForm #credit_card_no').attr('placeholder');
          $("form#addTransactionForm #err_credit_card_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #credit_card_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_credit_card_no").text("").fadeOut('slow');
          $('form#addTransactionForm #credit_card_no').removeClass('is-invalid');
          $('form#addTransactionForm #credit_card_no').addClass('is-valid');
        }
        
    });

    $(document).on("blur change keyup","form#addTransactionForm #cheque_no",  function (event){              
        
        if($('form#addTransactionForm #cheque_no').val()=="" && $('form#addTransactionForm #payment_mode').val() == 2)
        {
          var placeholder = $('form#addTransactionForm #cheque_no').attr('placeholder');
          $("form#addTransactionForm #err_cheque_no").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #cheque_no').addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addTransactionForm #err_cheque_no").text("").fadeOut('slow');
          $('form#addTransactionForm #cheque_no').removeClass('is-invalid');
          $('form#addTransactionForm #cheque_no').addClass('is-valid');
        }
    });

    $(document).on('click', '.delete_transaction', function (event) {
      $(this).fadeOut(10);
      $(this).closest('td').find('.delete_transaction_confirmation').fadeIn();
    });

    $(document).on('click', '.delete_transaction_no', function (event) {
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_transaction').fadeIn(20);
    });

    $(document).on('click', '.delete_transaction_yes', function (event) {
      // $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(1);
      

      var $this           = $(this);
      var transaction_id  = $(this).data('transaction_id');
      var sale_id         = $(this).data('entry_id');

      $(this).closest('td').find('.delete_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('transaction/delete')?>",
          type: "POST",
          data: {
            'transaction_id':transaction_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $('#is_there_change_in_transaction').val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                $.ajax({
                  url: "<?php echo base_url('sale/view')?>/"+sale_id,
                  type: "GET",
                  dataType: "JSON",
                  success: function(data){

                    $(".sale_detail").html(data.sale_view);
                    $(".add_transaction").html(data.add_transaction);
                    $("#to_account").val(data.to_account);
                    $(".transaction_entries").html(data.previous_transaction_view);

                    if(data.due_amount == 0)
                    {
                      $('.addTransactionCard').css('display','none');
                      // collapse all accordian
                      $('#transactionEntries').collapse("show");
                      $('#saleDetail').collapse("hide");  
                    }
                    else
                    {
                      // collapse all accordian
                      $('.addTransactionCard').css('display','block');
                      $('#transactionEntries').collapse("hide");
                      $('#addTransaction').collapse("hide");
                      $('#saleDetail').collapse("show");  
                    }
                  }
                });  
              }

            },500);
          }
        });
      }, 500);
    });

    $(document).on('show.bs.modal','#sale_edit', function (e) {
      var sale_id = $(e.relatedTarget).data('sale_id');
      $('#sale_edit').find('#id').val(sale_id);

      // alert(sale_id);

      $.ajax({
        url: "<?php echo base_url('sale/sale_edit_confirmation')?>",
        type: "POST",
        data:{
          'sale_id': sale_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#sale_edit').find('.modal-content').html(data.sale_edit_modal_body);
        }
      });
    });

    const messageToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    // Copy to clipboard
    $(document).on('click','.copy-to-clickboard',function(e){
      e.preventDefault();
      var $temp = $("<input>");
      $("body").append($temp);
      $temp.val($(this).data('url')).select();
      document.execCommand("copy");
      $temp.remove();

      messageToast.fire({
        type: 'success',
        title: "Link is copied into your clipboard. CTRL + V to paste the URL."
      });  

    })


    $(document).on('click','#deleteSaleSubmit',function(ev){
      var sale_id = $('#addTransactionForm').find('input[name="entry_id"]').val();
      $('#deleteSaleForm').find('input[name="id"]').val(sale_id);
      $('#deleteSaleForm').submit();
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

                  if($('addPosForm #customer_id').length)
                  {
                    $('addPosForm #customer_id').html('');
                    $('addPosForm #customer_id').append('<option value="">Select</option>');
                    
                    for(i=0;i<response['customers'].length;i++)
                    { 
                      $('addPosForm #customer_id').append('<option value="' + response['customers'][i].id + '">' + response['customers'][i].customer_name+'-'+response['customers'][i].phone+'</option>');
                    }

                    $('addPosForm #customer_id').val(response['id']).attr("selected","selected");  

                    // if($("#warehouse_id").length)
                    // { 
                      // display shipping fields
                      // $('.shipping_detail').css('display','block');
                      
                      // if($("#warehouse_id").val() != '')
                      // {
                      //   $("#customer_id").closest('.row').siblings().fadeIn(10);
                      // }
                      $('form#addCustomerForm #customer_state_id').val(customer.state_id);
                      $('form#addCustomerForm #customer_country_id').val(customer.country_id);

                      $('input[name="shipping_country_id"]').val(customer.shipping_country_id).trigger('change');

                      // $("#product_table_body tr").remove();
                      // calculateGrandTotal();
                      // refresh_tax_td();
                    // }

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

        $("form#addCustomerForm #customer_name").focus();
        
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

  })
</script>

