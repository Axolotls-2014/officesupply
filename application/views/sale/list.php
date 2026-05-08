<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
           
            <li class="breadcrumb-item active"><?=$this->lang->line('sale_list')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
    <?php 
    // $user_id = $this->session->userdata('user_id');
    // $user = $this->db->get_where('users', ['id' => $user_id])->row();
    // if ($user && (!isset($user->branch_id) || empty($user->branch_id))): 
    ?>
    
  <div class="row">
      
    <div class="col-12 col-sm-12 col-md-3">
      <div class="info-box">
        <span class="info-box-icon bg-info elevation-1"><i class="fas fa-store"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><?=$this->lang->line('sale_total_total')?></span>
          <span class="info-box-number" style="font-size: 19px">
            <?=$this->session->userdata('currency_symbol')?>
            <?=($total_sale == '') ? '0' : number_format_i($total_sale)?>
          </span>
        </div>
      </div>
    </div>
    
    <div class="col-12 col-sm-12 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><?=$this->lang->line('total_amount_received')?></span>
          <span class="info-box-number" style="font-size: 19px">
            <?=$this->session->userdata('currency_symbol')?>
            <?=($total_amount_received == '') ? '0' : number_format_i($total_amount_received)?>
          </span>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-12 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-piggy-bank"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><?=$this->lang->line('total_amount_receivable')?></span>
          <span class="info-box-number" style="font-size: 19px">
            <?=$this->session->userdata('currency_symbol')?>
            <?=number_format_i(($total_sale-$total_amount_received-$total_amount_tds))?>
          </span>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-12 col-md-3">
      <div class="info-box mb-3">
        <span class="info-box-icon bg-default elevation-1"><i class="fas fa-hand-holding-usd"></i></span>

        <div class="info-box-content">
          <span class="info-box-text"><?=$this->lang->line('total_amount_tds')?></span>
          <span class="info-box-number" style="font-size: 19px">
            <?=$this->session->userdata('currency_symbol')?>
            <?=($total_amount_tds == '') ? '0' : number_format_i($total_amount_tds)?>
          </span>
        </div>
      </div>
    </div>
  </div>


      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('sale_list')?></h3>
              ( <span class="text-xs"><?=$this->lang->line('all_amount_are_in')?> <b><?=$this->session->userdata('currency_symbol')?></b></span> )
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item ml-8">
                    <select class="form-control form-control-sm select2bs4 " name="sales_payment" id="sales_payment" width="100%" class="add-row" placeholder="" >
                      <option value="">Show All Invoices</option>
                      <option value="<?=INVOICE_UNPAID?>">Unpaid</option>
                      <option value="<?=INVOICE_PARTIALLY_PAID?>">Partial Paid</option>
                      <option value="<?=INVOICE_PAID?>">Paid</option>
                    </select>
                  </li>

                  <li class="nav-item  ml-2">
                    <a class="nav-link whatsapp_message_modal btn-sm btn-secondary text-white" data-multiple="true" href="#" data-tt="tooltip" title="Click here to send whatsapp message" data-toggle="modal" data-target="#whatsapp_message_modal" >
                      <i class="fab fa-whatsapp"></i> 
                    </a>
                  </li>

                    <?php 
                      if($this->permission_model->has_permission('edit_custom_field'))
                      {
                    ?>
                    <li class="nav-item  ml-2">
                      <a class="nav-link custom_field_modal btn-sm btn-success text-white" href="#" data-tt="tooltip" title="Click here to Edit Custom Field" data-toggle="modal" data-target="#custom_field_modal" data-module="sale">
                        <i class="fas fa-cogs"></i> Custom Field
                      </a>
                    </li>
                    <?php 
                      }
                    ?>

                  <?php 
                    if($this->permission_model->has_permission('add_sale'))
                    {
                  ?>
                      <li class="nav-item ml-2">
                        <a class="nav-link active" href="<?=base_url('sale/add')?>" data-tt="tooltip" title="Click here to Add Sale"><i class="far fa-snowflake mr-2"></i><?=$this->lang->line('sale_add')?></a>
                      </li>

                  <?php
                    }
                  ?>
                  <?php 
                    if($this->permission_model->has_permission('download_ewaybill_json'))
                    {
                  ?>

                      <li class="nav-item ml-2">
                        <a class="nav-link generate_ewaybill active" href="<?=base_url('sale/ewaybill')?>" data-tt="tooltip" title="Click here to Generate Ewabill JSON file."><i class="fas fa-list mr-2"></i>Generate Ewaybill</a>
                      </li>
                      
                  <?php 
                    }
                  ?>
                </ul>
              </div>
             
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="2%"><input type="checkbox" class="all_sale"></th>
                    <th>Inv No.</th>
                    <th><?=$this->lang->line('sale_invoice_date')?></th>
                    <th>Customer Name</th>
                    <!--<th class="d-none"><?="Discount" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>-->
                    <th><?="Taxable Value" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <th><?="GST" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <!--<th><?=$this->lang->line('sale_tds')?></th>-->
                    <th>Total Amount</th>
                     <th>Received</th>

                    <!--<th><?=$this->lang->line('paid')?></th>-->
                    <th><?=$this->lang->line('due')?></th>
                    <th><?=$this->lang->line('delivery_status')?></th>
                    <th width="25%"><?=$this->lang->line('sale_action')?></th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
               
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
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
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
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
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="delivery-modal">
  <div class="modal fade" id="delivery-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header light-purple-header">
          <h4 class="modal-title">
            <?php echo $this->lang->line('sale_delivery');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="accordion">
            <form id="addDeliveryDetailForm" name="addDeliveryDetailForm" method="POST">
              <div class="card card-info addDeliveryDetailCard">

                  <div class="card-header light-secondary-header addDeliveryDetail">
                    <h5 class="card-title">
                      <a data-toggle="collapse" data-parent="#accordion" href="#addDeliveryDetail" class="collapsed" aria-expanded="false">
                        <?=$this->lang->line('sale_delivery_add')?>
                        <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                      </a>
                    </h5>
                  </div>
                  <div id="addDeliveryDetail" class="panel-collapse in collapse" style="">
                    <div class="card-body add_delivery_detail">
                    
                    </div>
                    <div class="card-footer">
                      <input type="hidden" name="delivery_items" value="">
                      <input type="hidden" name="sale_id" value="">
                      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                      <button type="submit" name="addDeliveryDetailSubmit" id="addDeliveryDetailSubmit" class="btn btn-primary float-right">
                        <?=$this->lang->line('submit')?>
                      </button>
                      <button type="button" name="cancelDeliveryDetail" id="cancelDeliveryDetail" class="btn btn-default">
                        <?=$this->lang->line('cancel')?>
                      </button>
                    </div>
                  </div>
                
              </div>
            </form>
            <div class="card card-success deliveryEntriesCard">
              <div class="card-header deliveryEntries light-success-header">
                <h5 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#deliveryEntries" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('previous_sale_delivery')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h5>
                <div class="card-tools">
                  <div class="btn-group">
                    <button type="button" class="btn btn-sm btn-default" id="addNewDeliveryDetail">Add New Delivery</button>
                  </div>
                </div>
              </div>
              <div id="deliveryEntries" class="panel-collapse collapse">
                <div class="card-body delivery_entries m-0 p-0">
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="is_there_change_in_delivery_transaction" id="is_there_change_in_delivery_transaction" value="false">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<div>
  <div class="modal fade" id="email-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="whatsapp_message_modal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>




<?php $this->load->view('layout/footer');?>

<div class="example-modal">
  <div class="modal fade" id="delete_sale">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="upload_docs_modal">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="view_docs_modal">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="sale_edit">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="custom_field_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(e){

    function reinitialise()
    {
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });
      $('.datepicker').datepicker({
          weekStart: 1,
          daysOfWeekHighlighted: "6,0",
          autoclose: true,
          todayHighlight: true,
          format: 'dd-mm-yyyy'
      });
    }

    $(document).on('click', ".whatsapp_message_modal", function(event) {
      event.preventDefault();

      var multiple = $(this).data('multiple');
      var sale_ids = [];

      if(multiple == true){
        $('.single_sale:checked').each(function() {
          sale_ids.push($(this).data('sale_id'));
        });
      }
      else
      {
        sale_ids.push($(this).data('sale_id'));
      }

      if (sale_ids.length === 0) {
        
        Swal.fire({
            title: 'FAILURE !!',
            text: "Please select at least one Sale",
            icon: "warning",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            timer: 3000,
            customClass: {
                confirmButton: "btn btn-primary"
            }
        }).then(() => {
            // Ensure any existing backdrop is removed
            $('.modal-backdrop').remove();
            $('#whatsapp_message_modal').modal('hide');
        });

       // return; // Prevent further execution if no sale is selected
      }
      else
      { 
        
        $.ajax({
            url: "<?= base_url('sale/send_message') ?>",
            type: "GET",
            dataType: "JSON",
            success: function(data) {
                $('#whatsapp_message_modal').find('.modal-content').html(data.whatsapp_message_modal_body);
                $('#whatsapp_message_modal').modal('show');
                $('#whatsapp_message_modal').find('#sale_ids').val(sale_ids.join(','));
                reinitialise();
            },
        });
      }
    });

    $(document).on('submit','#sendMessageForm',function(e){
      
      e.preventDefault();

      $('#sendMessageSubmit').text('Please wait...').attr('disabled','disabled');
      var formData = $('#sendMessageForm').serialize();

      var isError = false;

      $('form#sendMessageForm .field_validation').each(function() {
          
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#sendMessageForm  #err_"+id).text(field+ " field is required.");
          $('form#sendMessageForm  #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#sendMessageForm #err_"+id).text("");
          $('form#sendMessageForm #'+id).removeClass('is-invalid');
          $('form#sendMessageForm #'+id).addClass('is-valid');
        }
        
      });
      
      if(isError == true)
      {
        $('#sendMessageSubmit').text('Submit').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
          url: "<?php echo base_url('sale/send_message')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#whatsapp_message_modal').modal('hide');
              $('form#sendMessageForm #sendMessageSubmit').text('Submit').removeAttr('disabled');

              Swal.fire({
                text: response.message,
                title: 'SUCCESS !!',
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:5000
              });
              //initialize_datatable();
            }
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                $("form#sendMessageForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#sendMessageForm #sendMessageSubmit').text('Submit').removeAttr('disabled');
            }
            else
            {
              Swal.fire({
                text: response.message,
                title: "FAILURE !!",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:10000
              });
              $('#sendMessageSubmit').text('Submit').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#sendMessageForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#sendMessageForm #err_"+id).text(field+ " field is required.");
          $('form#sendMessageForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#sendMessageForm #err_"+id).text("");
          $('form#sendMessageForm #'+id).removeClass('is-invalid');
          $('form#sendMessageForm #'+id).addClass('is-valid');
        }
    });

    $(document).on('change', '#wm_type',function() {
      var selectedType = $(this).val();

      // alert(selectedType);

      if (selectedType) {
        $.ajax({
          url: "<?=base_url('whatsapp_template/get_record_detail_by_type')?>/"+selectedType,
          type: 'GET',
          dataType: 'json',
          success: function(response) {
              if (response.code == <?=RESPONSE_SUCCESS?>) {
                $('#wm_message').val(response.message);
              } else {
                $('#wm_message').val('');
              }
          },
          error: function() {
            show_message('failure-header','An error occurred while fetching the template message.')
          }
        });
      } else {
        $('#wm_message').val('');
      }
    });


    $('.saleDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

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
            $('#addTransaction').collapse("hide");
            $('#saleDetail').collapse("show");  
          }
        }
      });
    });

    $(document).on('hide.bs.modal','#transaction-modal' ,function (e) {

      // alert($('#is_there_change_in_transaction').val());
      
      if($('#is_there_change_in_transaction').val() == "true")
      {
        location.reload();
      }
      
    });

    $('#delivery-modal').on('show.bs.modal', function (e) {

      var sale_id = $(e.relatedTarget).data('sale_id');
      $('#sale_id').val(sale_id);
      $('#addNewDeliveryDetail').data('sale_id',sale_id);

      if($(e.relatedTarget).hasClass('open_delivery_modal'))
      {
        $.ajax({
          url: "<?php echo base_url('sale/view_delivery')?>",
          type: "POST",
          data: {
            'sale_id':sale_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            // if all quantity is delivered, hide the add new delivery button
            if(data.is_all_products_delivered == true)
            {
              $('#addNewDeliveryDetail').css('display','none');
            }
            else
            {
              $('#addNewDeliveryDetail').css('display','block'); 
            }  

            // $(".add_delivery_detail").html(data.add_delivery_detail);
            $(".delivery_entries").html(data.delivery_transaction);
            $(".sale_delivery_detail").html(data.sale_delivery_detail);
            
            $('.view_delivery_transaction_detail').css('display','none');
            $('#deliveryEntries').collapse("show");

            $('#addDeliveryDetailForm').find('input[name="sale_id"]').val(sale_id);

            
          }
        });  
      }      
    });

    $(document).on('hide.bs.modal','#delivery-modal' ,function (e) {
  
      if($('#is_there_change_in_delivery_transaction').val() == "true")
      {
        location.reload();
      }
         
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('.saleDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

    // View delivery transaction
    $(document).on('click', '.view_delivery_transaction', function (event) {

      $('.view_delivery_transaction_detail').css('display','none');
      $(this).parents('tr').next('tr').toggle(200);
      
    });

    // Add new delivery of sale
    $(document).on('click','#addNewDeliveryDetail',function(e){

      $('.addDeliveryDetailCard').toggle(200);

      var sale_id = $('#addDeliveryDetailForm').find('input[name="sale_id"]').val();

      $.ajax({
        url: "<?php echo base_url('sale/add_sale_delivery')?>/"+sale_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){

          $(".add_delivery_detail").html(data.add_delivery_detail);

          // Initialize the dropdown and date
  
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
  
          $('#delivery-modal').find('input[name="delivery_date"]').datepicker({
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
          });
            
        }
      });

      $('#addDeliveryDetail').collapse('show');
    });

    // Populate delivery data of sale
    $(document).on('click','#addDeliveryDetailSubmit',function(e){
      e.preventDefault();

      var deliveryDataArray = [];

      $("#product_delivery_item_body").find('tr').each(function () {

        var tr                      = $(this).closest("tr");
        var deliveryData            = {};

        deliveryData['product_id']            = tr.find('input[name="quantity"]').data('product_id');
        deliveryData['warehouse_product_id']  = tr.find('input[name="warehouse_product_id"]').val();
        deliveryData['quantity']              = tr.find('input[name="quantity"]').val();
        deliveryData['cost']                  = tr.find('input[name="quantity"]').data('cost');
        deliveryData['price']                 = tr.find('input[name="quantity"]').data('price');
        deliveryData['selling_price']         = tr.find('input[name="quantity"]').data('selling_price');

        deliveryDataArray.push(JSON.stringify(deliveryData));
      });

       
      $('#addDeliveryDetailForm').find('input[name="delivery_items"]').val(deliveryDataArray.join('|'));

      $('#addDeliveryDetailForm').trigger('submit');
    });

    // Submit delivery data of sale
    $(document).on('submit','#addDeliveryDetailForm',function(e){
      e.preventDefault();

      var isError = false;

      $('form#addDeliveryDetailForm .field_validation').each(function() {
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');
          var name  = $(this).attr('name');

          // alert(name);
          
          if(value==null || value=="")
          {
            $("form#addDeliveryDetailForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
            $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
            $('form#addDeliveryDetailForm #'+id).addClass('is-valid');
          }

          if(name == 'quantity')
          {
            var max = parseInt($(this).attr('max'));
            if(parseInt(value) > max)
            {
              $("form#addDeliveryDetailForm #err_"+id).text(field+ " should be <= "+ max).fadeIn('slow');
              $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
              isError = true;   
            }
          }


      });

      if(isError == true)
      {
        return false;
      }
      else
      {
        var DeliveryDetailFormData  = $('#addDeliveryDetailForm').serialize();  
        var sale_id                 = $('form#addDeliveryDetailForm').find('input[name="sale_id"]').val(); 

        $('#addDeliveryDetailSubmit').text('Please wait while we are saving').addClass('disabled');

        setTimeout(function(){ 

          $.ajax({
            url: "<?php echo base_url('sale/add_sale_delivery')?>",
            type: "POST",
            data: DeliveryDetailFormData,
            dataType: "JSON",
            success: function(data){


              if(data.code == 1)
              {
                $('#addDeliveryDetailSubmit').text('Submit').removeClass('disabled');
                $.ajax({
                  url: "<?php echo base_url('sale/view_delivery')?>",
                  type: "POST",
                  data: {
                    'sale_id':sale_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                  dataType: "JSON",
                  success: function(data){

                    // if all quantity is delivered, hide the add new delivery button
                    if(data.is_all_products_delivered == true)
                    {
                      $('#addNewDeliveryDetail').css('display','none');
                    }
                    else
                    {
                      $('#addNewDeliveryDetail').css('display','block'); 
                    }  

                    $('.addDeliveryDetailCard').toggle(200);
                    $(".add_delivery_detail").html('');
                    $(".delivery_entries").html(data.delivery_transaction);
                    // $(".sale_delivery_detail").html(data.sale_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="sale_id"]').val(sale_id);

                    $("#is_there_change_in_delivery_transaction").val("true");
                  }
                });  
              }
            }
          });
        }, 500);  
      }
    });

    $("form#addDeliveryDetailForm").on("change", '.field_validation', function (event){

      var id    = $(this).attr('id');
      var value = $(this).val();
      var field = $(this).attr('placeholder');
      var name  = $(this).attr('name');
      
      if(value==null || value==""){
        $("form#addDeliveryDetailForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
        $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
        return false;
      }
      else{
        $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
        $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
        $('form#addDeliveryDetailForm #'+id).addClass('is-valid');
      }

      if(name == 'quantity')
      {
        var max = parseInt($(this).attr('max'));
        if(parseInt(value) > max)
        {
          $("form#addDeliveryDetailForm #err_"+id).text(field+ " should be <= "+ max).fadeIn('slow');
          $('form#addDeliveryDetailForm #'+id).addClass('is-invalid');
          return false;  
        }
        else
        {
          $("form#addDeliveryDetailForm #err_"+id).text("").fadeOut('slow');
          $('form#addDeliveryDetailForm #'+id).removeClass('is-invalid');
          $('form#addDeliveryDetailForm #'+id).addClass('is-valid');   
        }
      }
    });

    $(document).on('click','#cancelDeliveryDetail',function(e){
      $('.addDeliveryDetailCard').toggle(200);
      $('#addDeliveryDetail').collapse('hide');
      
    });

    $(document).on('click','.delete_delivery_transaction',function(e){
      $(this).fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeIn();
    });

    $(document).on('click', '.delete_delivery_transaction_no', function (event) {
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction').fadeIn(20);
    });

    $(document).on('click', '.delete_delivery_transaction_yes', function (event) {
      // $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_delivery_transaction_confirmation').fadeOut(1);
      

      var $this                 = $(this);
      var sale_delivery_id  = $(this).data('sale_delivery_id');
      var sale_id           = $('#addDeliveryDetailForm').find('input[name="sale_id"]').val();

      $(this).closest('td').find('.delete_delivery_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('sale/delete_sale_delivery')?>",
          type: "POST",
          data: {
            'sale_delivery_id':sale_delivery_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $('#is_there_change_in_delivery_transaction').val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                $.ajax({
                  url: "<?php echo base_url('sale/view_delivery')?>",
                  type: "POST",
                  data: {
                    'sale_id':sale_id,
                    '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                  dataType: "JSON",
                  success: function(data){

                    // if all quantity is delivered, hide the add new delivery button
                    if(data.is_all_products_delivered == true)
                    {
                      $('#addNewDeliveryDetail').css('display','none');
                    }
                    else
                    {
                      $('#addNewDeliveryDetail').css('display','block'); 
                    }  

                    // $(".add_delivery_detail").html(data.add_delivery_detail);
                    $(".delivery_entries").html(data.delivery_transaction);
                    $(".sale_delivery_detail").html(data.sale_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="sale_id"]').val(sale_id);
                  }
                });

                
              }

            },500);
          }
        });
      }, 500);
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

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
                  $('#transactionEntries').collapse("show");
                  $('#addTransaction').collapse("hide");
                  $('#saleDetail').collapse("hide");  
                }
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
  })
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    const EmailToast = Swal.mixin({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 10000
                            });

    $(document).on('click','.email_invoice',function(e){

          var sale_id = $(this).data('sale_id');
          var i_icon = $(this);

          i_icon.find('.email_icon').removeClass('fa-at');
          i_icon.find('.email_icon').addClass('fa-spinner').addClass('fa-spin');
          
          $.ajax({
            url: "<?php echo base_url('sale/email_invoice') ?>",
            type: "POST",
            dataType: "json",
            data:{
              'sale_id' : sale_id,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            success: function(data){

              i_icon.find('.email_icon').addClass('fa-at');
              i_icon.find('.email_icon').removeClass('fa-spinner').removeClass('fa-spin');

              if(data.code == 1)
              {
                EmailToast.fire({
                  type: 'success',
                  title: data.message
                });
              }
              else
              {
                EmailToast.fire({
                  type: 'error',
                  title: data.message
                });
              }
              
            }
          });    
        });
   
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

     
    const EmailToast = Swal.mixin({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 10000
                            });

           /*************************** Start Dynamic Sale List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    {
      var sales_payment = $('select[name="sales_payment"] option:selected').val();

      $('#example').DataTable({ 
   
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "order": [], //Initial no order.
           "pageLength": 100,
          "bDestroy":true,
   
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": "<?php echo site_url('sale/ajax_list')?>",
              "type": "POST",
              "data":  {
                'sales_payment':sales_payment,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              }
          },

          'initComplete':function(settings, json){
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
            $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
          },  
   
          //Set column definition initialisation properties.
         "columnDefs": [
    { 
        "targets": [0, 6, 7, 8, 9, 10], // shifted all after removed column
        "orderable": false
    },
]

      });
    }

    $(document).on('change','select[name="sales_payment"]',function(e){
      initialize_datatable();
    })


    $(document).on('click', ".custom_field_modal", function() {
      var module_name = $(this).data('module'); // Assuming you have a data-module attribute in your button

      $.ajax({
          url: "<?php echo base_url('custom_field/edit')?>",
          type: "GET",
          dataType: "JSON",
          data: { module_name: module_name }, // Pass the module name to the server
          success: function(data) {
              $('#custom_field_modal').find('.modal-content').html(data.custom_field_modal_body);
              $('#custom_field_modal').modal('show');

              reinitialize();
          },
          error: function(xhr, ajaxOptions, thrownError) {
            show_message('failure-header',xhr.status+thrownError+ajaxOptions)              
          }
      });
    });

    $(document).on('submit', '#customfieldForm', function(e) {
      e.preventDefault();

      $('#customfieldSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled', 'disabled');
      var formData = $('#customfieldForm').serialize();

      var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
      var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

      $.ajax({
          url: "<?php echo base_url('custom_field/edit')?>",
          type: "POST",
          data: formData + '&' + csrfName + '=' + csrfHash,
          dataType: "JSON",
          success: function(response) {
              if (response.code == 1) {
                  // Success response handling
                
                  $('#custom_field_modal').modal('hide');
                  $('#customfieldSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
                  // initialize_datatable();
                  EmailToast.fire({
                      type: 'success',
                      title: response.message
                  });
              } else {
                  // Error response handling
                  $('#customfieldSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              }
          }
      });
    });



    $(document).on('show.bs.modal','#delete_sale', function (e) {
      var sale_id = $(e.relatedTarget).data('sale_id');
      $('#delete_sale').find('#id').val(sale_id);

      // alert(sale_id);

      $.ajax({
        url: "<?php echo base_url('sale/sale_delete_confirmation')?>",
        type: "POST",
        data:{
          'sale_id': sale_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_sale').find('.modal-content').html(data.sale_delete_modal_body);
        }
      });


    });
    

     // Delete record with please wait text
    $(document).on('submit','#deleteSaleForm',function(e){
      $('#deleteSaleSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
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


    $(document).on('show.bs.modal','#upload_docs_modal', function (e) {
      var sale_id = $(e.relatedTarget).data('sale_id');
      $('#upload_docs_modal').find('#id').val(sale_id);

      // alert(sale_id);

      $.ajax({
        url: "<?php echo base_url('sale/sale_upload_confirmation')?>",
        type: "POST",
        data:{
          'sale_id': sale_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          console.log(data); // check what you get
          if(data.sale_upload_modal_body){
            $('#upload_docs_modal').find('.modal-content').html(data.sale_upload_modal_body);
          } else {
            alert("Empty response from server.");
          }
        },
        error: function(xhr){
          alert("AJAX error: " + xhr.status);
        }
      });


    });
    
    // Handle document upload via AJAX
    $(document).on('click', '#uploadDocsSubmit', function(e) {
        e.preventDefault();
        
        var form = $('#uploadDocsForm')[0];
        var formData = new FormData(form);
        
        if ($('select[name="document_type"]').val() === 'Other Documents' && !$('input[name="other_document_title"]').val()) {
            Swal.fire({
                title: 'Error!',
                text: 'Please provide a document title for "Other Documents"',
                icon: 'error',
                confirmButtonText: 'OK'
            });
            return;
        }        
        
        // Show loading state
        $(this).text('Uploading...').attr('disabled', true);
        
        $.ajax({
            url: "<?= base_url('sale/upload_sale_receipt') ?>",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function(response) {
                if (response.status === 'success') {
                    Swal.fire({
                        title: 'Success!',
                        text: response.message,
                        icon: 'success',
                        confirmButtonText: 'OK'
                    }).then(() => {
                        $('#upload_docs_modal').modal('hide');
                        $('#example').DataTable().ajax.reload();
                    });
                } else {
                    Swal.fire({
                        title: 'Error!',
                        text: response.message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            },
            error: function(xhr, status, error) {
                Swal.fire({
                    title: 'Error!',
                    text: 'An error occurred while uploading the document.',
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            },
            complete: function() {
                $('#uploadDocsSubmit').text('Upload').attr('disabled', false);
            }
        });
    });


$(document).on('show.bs.modal','#view_docs_modal', function (e) {
    var sale_id = $(e.relatedTarget).data('sale_id');
    
    $.ajax({
        url: "<?php echo base_url('sale/sale_receipt_view_confirmation')?>",
        type: "POST",
        data: {
            'sale_id': sale_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "json",
        beforeSend: function() {
            // Show loading indicator if needed
            $('#view_docs_modal .modal-content').html('<div class="text-center py-4"><i class="fas fa-spinner fa-spin fa-3x"></i></div>');
        },
        success: function(response) {
            if(response.status === 'success' && response.sale_view_modal_body) {
                $('#view_docs_modal').find('.modal-content').html(response.sale_view_modal_body);
            } else {
                $('#view_docs_modal').find('.modal-content').html(
                    '<div class="modal-header">' +
                        '<h4 class="modal-title">Error</h4>' +
                        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                            '<span aria-hidden="true">&times;</span>' +
                        '</button>' +
                    '</div>' +
                    '<div class="modal-body">' +
                        '<div class="alert alert-danger">' + (response.message || 'Failed to load documents') + '</div>' +
                    '</div>'
                );
            }
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
            $('#view_docs_modal').find('.modal-content').html(
                '<div class="modal-header">' +
                    '<h4 class="modal-title">Error</h4>' +
                    '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                        '<span aria-hidden="true">&times;</span>' +
                    '</button>' +
                '</div>' +
                '<div class="modal-body">' +
                    '<div class="alert alert-danger">Error loading documents. Please try again.</div>' +
                '</div>'
            );
        }
    });
});

    /*************************** End Dynamic Sale List with Datatables ****************************/

    $(document).on('change', '.all_sale', function() {
      if (this.checked == true)
          $('.single_sale:not(:disabled)').prop('checked', true);
      else
          $('.single_sale:not(:disabled)').prop('checked', false);
    });

    $(document).on('change', '.single_sale', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    function set_select_all_checkbox_status() {
      var total_single_sale = $('.single_sale').length;
      var total_checked_single_sale = $('.single_sale:checked').length;
      var total_enabled_single_sale = $('.single_sale:not(:disabled)').length;
      var total_checked_enabled_single_sale = $('.single_sale:checked:not(:disabled)').length;
      
      if (total_checked_enabled_single_sale < total_enabled_single_sale && total_checked_enabled_single_sale > 0) {
          $('.all_sale').prop('indeterminate', true); 
      } else if (total_checked_enabled_single_sale == total_enabled_single_sale) {
          $('.all_sale').prop('indeterminate', false);
          $('.all_sale').prop('checked', true);
      } else if (total_checked_enabled_single_sale == 0) {
          $('.all_sale').prop('indeterminate', false);
          $('.all_sale').prop('checked', false);
      }
    }

    $(document).on('click', '.generate_ewaybill', function(event) {
      event.preventDefault();

      // Get all checkboxes on the page
      var checkboxes = $('.single_sale');
      
      // Create an array to store the checked checkbox names
      var checkedNames = [];
      
      // Loop through each checkbox and check it if it's not already checked
      checkboxes.each(function() {
        // Add the checkbox name to the array if it's checked
        if ($(this).is(':checked')) {
          // alert($(this).data('warehouse_product_id'));
          checkedNames.push($(this).data('sale_id'));
        }
      });

      // alert(checkedNames.length);

      if(checkedNames.length){
        window.location.href = '<?= base_url('sale/ewaybill'); ?>'+"/"+checkedNames.join("-")+"/true";
      }
      else
      {
        // window.location.href = '<?= base_url('sale/ewaybill'); ?>'+"/"+checkedNames.join("-");
        Swal.fire({
          title: 'FAILURE !!',
          text: "Please select alteast one Sale",
          icon: "warning",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          timer: 3000,
          customClass: {
              confirmButton: "btn btn-primary"
          }
        });
      }
    });

     
    $(document).on('click','.email-modal',function(e){
      var sale_id = $(this).data('sale_id');
      // alert(sale_id);

      $.ajax({
        url: "<?php echo base_url('sale/send_email')?>/"+sale_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
           
          $('#email-modal').find('.modal-content').html(data.email_modal_body);
          $('#email-modal').modal('show');
          $('.summernote').summernote({
              height: 200 
          });
        },
       error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on('hidden.bs.modal','#email-modal',function(e){
      $('#email-modal').find('.modal-content').html('');
    });

    $(document).on('submit','#sendEmailForm',function(e){
      
      e.preventDefault();

      $('#submitEmail').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#sendEmailForm').serialize();

      // alert(formData);

      var isError = false;

      $('form#sendEmailForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value=="" || value == 0){
            $("form#sendEmailForm  #err_"+id).text(field+ " field is required.");
            $('form#sendEmailForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#sendEmailForm #err_"+id).text("");
            $('form#sendEmailForm #'+id).removeClass('is-invalid');
            $('form#sendEmailForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#submitEmail').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('sale/send_email')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if (response.code == 0) {
                // Handle validation errors
                $.each(response.errors, function(key, value) {
                    $("#" + key + "_error").text(value); // Display errors beside respective fields
                });
                $('#submitEmail').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
            else if(response.code==1)
            { 
              $('#email-modal').modal('hide');
              $('form#sendEmailForm #submitEmail').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              //initialize_datatable();

              EmailToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              EmailToast.fire({
                type: 'error',
                title: response.message
              });
              $('#submitEmail').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#sendEmailForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#sendEmailForm #err_"+id).text(field+ " field is required.");
          $('form#sendEmailForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#sendEmailForm #err_"+id).text("");
          $('form#sendEmailForm #'+id).removeClass('is-invalid');
          $('form#sendEmailForm #'+id).addClass('is-valid');
        }
    });


  });
</script>
