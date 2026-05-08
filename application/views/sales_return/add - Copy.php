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
  .search_service{
    background-color: #F8F8F8;
    height: 35px;
    padding-left: 25px;
    font-size: 18px;
  }

  .search_service::-webkit-input-placeholder { /* Chrome/Opera/Safari */
    padding-left: 5px;
    font-size: 18px;
  }
  .search_service::-moz-placeholder { /* Firefox 19+ */
    font-size: 18px;
  }
  .search_service:-ms-input-placeholder { /* IE 10+ */
    font-size: 18px;
  }
  .search_service:-moz-placeholder { /* Firefox 18- */
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
  .discount_value{
    padding-right: 10px !important;
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
                <li class="breadcrumb-item "><a href="<?=base_url('service')?>"><?=$this->lang->line('header_service')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('service_add')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="addSaleForm" id="addSaleForm" method="post" action="<?=base_url('service/add')?>">
              <div class="card">
                <div class="card-header">
                  <h6 class="card-title">Add Sale</h6>
                </div>
                <div class="card-body">
                  <div class="col-sm-12">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label>Reference No:</label>
                          <input type="text" class="form-control" id="Reference_no" name="Reference_no" disabled="">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label>Invoice Date</label>
                          <input type="text" class="form-control datepicker" id="date" name="date">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <div class="form-group">
                          <label for="customer"><?=$this->lang->line('sale_customer')?><span class="validation-color">*</span>
                          </label>
                          <div class="input-group input-group-sm">
                            <select class="form-control form-control-sm select2bs4 field_validation" name="customer_id" id="customer_id" width="100%" class="add-row">
                              <option value=""><?=$this->lang->line('select')?></option>
                              <?php
                                foreach ($customer as $value) {
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
                          <span id="err_customer_id" class="error invalid-feedback"></span>
                        </div>
                      </div>
                    </div>
                    
                    <div class="row">
                      <div class="col-sm-12 ">
                        <div class="well">
                          <div class="row">
                            <div class="col-sm-12">
                              <a href="" data-toggle="modal" data-tt="tooltip" data-target="#add_service_modal" class="float-right">+ Add New Service</a>
                            </div>
                            <div class="col-sm-12 search">
                              <span class="fa fa-search"></span>
                              <input id="search_service" class="form-control search_service"  type="text" name="search_service"  placeholder="Search Service name here ...">
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
                          <label>Items</label>
                          <table class="table items table-striped table-bordered table-condensed table-hover product_table" name="product_data" id="product_data">
                            <thead>
                              <tr>
                                <th width="2%">
                                  <img src="<?php  echo base_url(); ?>assets/images/bin1.png" />
                                </th>
                                <th class="span2" width="15%">Service Description</th>
                                <th class="span2" width="8%">Qty</th>
                                <th class="span2" width="10%">Price</th>
                                <th class="span2" width="17%">Discount</th>
                                <th class="span2" width="10%">Taxable Value</th>
                                <th class="span2" width="10%">Tax</th>
                                <th class="span2" width="5%">Inclusive ?</th>
                                <th class="span2" width="10%">Total</th>
                              </tr>
                            </thead>
                            <tbody id="product_table_body">
                            </tbody>
                          </table>
                          <table class="table table-striped table-bordered table-condensed table-hover total_data" style="font-size: 18px;">
                            <tr>
                              <td align="right" colspan="7">Total Taxable Value(<?=$currency?>)</td>
                              <td align='right'><span id="total_taxable_value">0.00</span></td>
                            </tr>
                            <tr>
                              <td align="right" colspan="7"> Total Discount(<?=$currency?>)</td>
                              <td align='right'>
                                <span id="total_discount">0.00</span>
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="7">Total Tax(<?=$currency?>)</td>
                              <td align='right'>
                                <span id="total_tax">0.00</span>
                              </td>
                            </tr>
                            <tr>
                              <td align="right" colspan="7">Total(<?=$currency?>)</td>
                              <td align='right'><span id="total">0.00</span></td>
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
                                  <a class="nav-link active" href="#note_to_customer_tab" data-toggle="pill"><?php echo $this->lang->line('sale_note_to_customer'); ?></a>
                                </li>
                                <li class="nav-item">
                                  <a class="nav-link" href="#internal_note_tab" data-toggle="pill">Internal Note</a>
                                </li>
                              </ul>                           
                              <br>
                              <div class="tab-content">
                                <div class="tab-pane" id="note_to_customer">
                                  <textarea class="col-sm-12 form-control" id="note_to_customer_tab" name="note_to_customer"></textarea>
                                  <span style="color:red;" id="err_note"></span>
                                </div>
                                <div class="tab-pane active" id="internal_note_tab">
                                  <textarea class="col-sm-12 form-control" id="internal_note" name="internal_note"></textarea>
                                  <span style="color:red;" id="err_note"></span>
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
                  <input type="hidden" name="company_state_id" id="company_state_id" value="<?=$company_setting->state_id?>">
                  <input type="hidden" name="company_country_id" id="company_country_id" value="<?=$company_setting->country_id?>">
                  <button type="submit" name="submit" id="submit" class="btn btn-info">&nbsp;&nbsp;&nbsp;Add&nbsp;&nbsp;&nbsp;</button>
                  <button type="submit" name="submit" id="submitPayNow" value="pay" name="pay" class="btn btn-info" style="margin-left: 2%">Add And Pay Now</button>                     
                  <span class="btn btn-default" id="cancel" style="margin-left: 2%" onclick="cancel('sales')">Cancel</span>
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

<script src="https://cdn.jsdelivr.net/npm/disableautofill/src/jquery.disableAutoFill.min.js"></script>

<script type="text/javascript">

  $(document).ready(function(e){

      $("#customer_id").closest('.row').siblings().css('display','none');

      $('#customer_id').change(function(e){

        var customer_id = $(this).val();
        if(customer_id != '')
        {
          $("#customer_id").closest('.row').siblings().fadeIn(10);

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
              }
          });
        }
      }); 

      // Service search code begin

      var c_mapping = { };
      var row_count = 1;

      $(function(){
        $('#search_service').autoComplete({
          minChars: 1,
          source: function(term, suggest){
            term = term.toLowerCase();

            $.ajax({
              url: "<?php echo base_url('service/search') ?>/"+term,
              type: "GET",
              dataType: "json",
              success: function(data){
                var services = data;
                var suggestions = [];
                for(var i = 0; i < services.length; ++i) {
                    suggestions.push(services[i].id+' - '+services[i].name);
                    c_mapping[services[i].id] = services[i].name;
                }
                suggest(suggestions);
              }
            });
          },
          onSelect: function(event, ui) {
            var str = ui.split(' - ');
            
            var service_id = str[0];

            $.ajax({
              url: "<?php echo base_url('service/get_record_detail') ?>/"+service_id,
              type: "GET",
              dataType: "json",
              success: function(data){
                var service = data.service;

                if(!is_service_exist_in_row(service.id))
                {
                  add_row(data);
                }
                else
                {
                  highlight_row(service.id);
                }
                $('#search_service').val('');
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

        var service   = data.service;
        var discounts = data.discount;

        var select_discount = "";
            select_discount += '<select class="form-control select2bs4" name="item_discount" style="width: 100%;">';
            select_discount += '<option value="">Select</option>';
              for(a=0;a<discounts.length;a++)
              {
                var type_symbol;
                if(discounts[a].type == 0)
                {
                  type_symbol = "$";
                }
                else
                {
                  type_symbol = "%"; 
                }
                select_discount += '<option value="' + discounts[a].id + '">' + discounts[a].name+'('+discounts[a].value  + type_symbol +')'+ '</option>';
              }
            select_discount += '</select>'
            select_discount += '<span name="span_discount_type" class="discount_type">Discount Value :  <?=$this->session->userdata('currency_symbol');?> </span><input type="hidden" name="discount_type" value="0">';
            select_discount += '<span name="span_discount_value" class="discount_value">0.0</span><input type="hidden" name="discount_value" value="'+0+'">';

        var input_quantity =  '<input type="number" class="form-control" name="quantity" value="1" step="1"><span name="quantity_update_message" class="quantity_update_message"></span>';
        var taxable_value  =  '<span name="taxable_value">'+service.price+'</span>';

        /************    tax begin   *****************/
        var tax = '';

        if(company_country_id == customer_country_id)
        {
          if(company_state_id == customer_state_id)
          {
            tax += 'CGST : <span name="c_tax">'+0.0+'</span>(<span name="cgst">'+service.cgst+'</span>)<br/>';
            tax += 'SGST : <span name="s_tax">'+0.0+'</span>(<span name="sgst">'+service.sgst+'</span>)';
            tax += '<span name="i_tax"style="display:none">0</span><span name="igst" style="display:none">0</span>';
          }
          else
          {
            tax += 'IGST : <span name="i_tax">'+0.0+'</span>(<span name="igst">'+service.igst+'</span>)<br/>';
            tax += '<span name="c_tax" style="display:none">0</span><span name="cgst" style="display:none">0</span>';
            tax += '<span name="s_tax" style="display:none">0</span><span name="sgst" style="display:none">0</span>';
          }
        }

        /************    tax end   *****************/

        /************    tax type begin   *****************/

        var tax_type  = "";
        if(service.tax_type == 0)
        {
          tax_type = "No";
        }
        else
        {
          tax_type = "Yes";
        }

        /************    tax type end   *****************/



        var newRow = $('<tr class="service_row">');
            var cols = "";

            cols += '<td><input type="hidden" name="service_id" value="'+service.id+'">'+ row_count++ +'</td>';
            cols += '<td>'+service.name+'<br/>'+service.description+'</td>';
            cols += '<td>'+input_quantity+'</td>';
            cols += '<td>' 
                      +'<span id="price_span">'
                        +'<input type="number" class="form-control text-right" name="price" step="0.01" value="'+service.price+'">'
                      +'</span>'
                    +'</td>';
            cols += '<td>'+select_discount+'</td>';
            cols += '<td>'+taxable_value+'</td>';
            cols += '<td>'+tax+'</td>';
           
            cols += '<td>'+tax_type+'</td>';
            cols += '<td><span name="sub_total"></span></td>';
            cols += '</tr>';

            newRow.append(cols);
            $("table.product_table").append(newRow);
            $('.select2bs4').select2({theme: 'bootstrap4'});

            calculateRow(newRow);
      }

      function is_service_exist_in_row(service_id)
      {
        var isServiceExist = false;
        $("#product_table_body").find('tr').each(function () {

          var tr = $(this).closest("tr");

          if(tr.find('input[name^="service_id"]').val() == service_id)
          {
            isServiceExist = true;
          }
        });

        return isServiceExist;
      }

      function highlight_row(service_id)
      {
        $("#product_table_body").find('tr').each(function () {
          var tr  = $(this).closest("tr");

          if(tr.find('input[name^="service_id"]').val() == service_id)
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

      $("table.product_table").on("change", 'select[name^="item_discount"]', function (event) {

        // alert($(this).val());

        
        
      });

      $("table.product_table").on('change', 'input[name^="price"], input[name^="quantity"], select[name^="item_discount"]', function (event) {

        if($(this).attr('name') == 'item_discount')
        {
          var discount_id = $(this).val();
          var tr          = $(this).closest('tr');

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
              var discount_type = '';

              tr.find('input[name^="discount_type"]').val(discount.type);
              tr.find('input[name^="discount_value"]').val(discount.value);
              
              calculateRow(tr);
              calculateGrandTotal();
            }
          });  
        }
        else
        {
          calculateRow($(this).closest("tr"));
          calculateGrandTotal();
        }
       });
      
      function calculateRow(row)
      {
        var service_id  = row.find('input[name^="service_id"]').val();
        var quantity    = parseFloat(row.find('input[name^="quantity"]').val());
        var price       = parseFloat(row.find('input[name^="price"]').val());

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
        row.find('span[name^="span_discount_value"]').text(final_discount_value.toFixed(2));

        row.find('span[name^="taxable_value"]').text(taxable_value.toFixed(2));
        row.find('span[name^="sub_total"]').text(sub_total.toFixed(2));
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
            total_discount      += parseFloat(tr.find('span[name^="span_discount_value"]').text());
            total_cgst          += parseFloat(tr.find('span[name^="c_tax"]').text());
            total_sgst          += parseFloat(tr.find('span[name^="s_tax"]').text());
            total_igst          += parseFloat(tr.find('span[name^="i_tax"]').text());
            total               += parseFloat(tr.find('span[name^="sub_total"]').text()); 
        });

        $('#total_taxable_value').text(total_taxable_value.toFixed(2));
        $('#total_discount').text(total_discount.toFixed(2));
        $('#total_tax').text((total_cgst+total_sgst+total_igst).toFixed(2));
        $('#total').text(total.toFixed(2));
      }
  });
</script>