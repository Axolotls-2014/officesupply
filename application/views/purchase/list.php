<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
             <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
          
            <li class="breadcrumb-item active"><?=$this->lang->line('purchase_list')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12 col-sm-12 col-md-4">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-store"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><?=$this->lang->line('total_purchases')?></span>
              <span class="info-box-number" style="font-size: 19px">
                <!-- <small><?=$this->session->userdata('currency_symbol')?></small> -->
                <?=$this->session->userdata('currency_symbol')?>
                <?=($total_purchase == 0) ? '0.000' : number_format_i($total_purchase)?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-12 col-sm-12 col-md-4">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-success elevation-1"><i class="fas fa-check"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><?=$this->lang->line('total_amount_paid')?></span>
              <span class="info-box-number" style="font-size: 19px">
                <?=$this->session->userdata('currency_symbol')?>
                <?=($total_paid_amount == 0) ? '0.000' : number_format_i($total_paid_amount)?>                  
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        <div class="col-12 col-sm-12 col-md-4">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-piggy-bank"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><?=$this->lang->line('total_amount_payable')?></span>
              <span class="info-box-number" style="font-size: 19px">
                <?=$this->session->userdata('currency_symbol')?>
                <?=(($total_purchase-$total_paid_amount) == 0 ) ? '0.000' : number_format_i(($total_purchase-$total_paid_amount))?>                  
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <?=$this->lang->line('purchase_list')?> 
              </h3> ( <span class="text-xs"><?=$this->lang->line('all_amount_are_in')?> <b><?=$this->session->userdata('currency_symbol')?></b></span> )
              
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item ml-8">
                    <select class="form-control form-control-sm select2bs4 " name="purchase_payment" id="purchase_payment" width="100%" placeholder="" >
                        <option value="">Show All Purchases</option>
                        <option value="<?=INVOICE_UNPAID?>">Unpaid</option>
                        <option value="<?=INVOICE_PARTIALLY_PAID?>">Partial Paid</option>
                        <option value="<?=INVOICE_PAID?>">Paid</option>
                      </select>
                  </li>
                  <?php 
                    if($this->permission_model->has_permission('add_purchase'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    <a class="nav-link active" href="<?=base_url('purchase/add')?>" data-tt="tooltip" title="Click here to Add Purchase"><i class="far fa-snowflake mr-2"></i><?=$this->lang->line('purchase_add')?></a>
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
                    <th>Sr.No</th>
                    <th><?=$this->lang->line('purchase_invoice_no')?></th>
                    <th><?=$this->lang->line('purchase_date')?></th>
                    <th><?=$this->lang->line('purchase_warehouse')?></th>
                    <th>Supplier Name</th>
                   <th><?="Discount" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                     <th><?="Taxable" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <th><?="Tax" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <th>Total Price</th>
                    <th><?=$this->lang->line('paid')?></th>
                    <th><?=$this->lang->line('due')?></th>
                    <th>Recevied Status</th>
                    <th width="15%"><?=$this->lang->line('purchase_action')?></th>
                  </tr>
                </thead>
                <tbody>
                 
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('purchase_reference_no')?></th>
                    <th><?=$this->lang->line('purchase_invoice_no')?></th>
                    <th><?=$this->lang->line('purchase_date')?></th>
                    <th><?=$this->lang->line('purchase_warehouse')?></th>
                    <th><?=$this->lang->line('purchase_supplier')?></th>
                     <th><?="Discount" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                     <th><?="Taxable" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <th><?="GST" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>

                    <th><?=$this->lang->line('purchase_total')?></th>
                    <th><?=$this->lang->line('paid')?></th>
                    <th><?=$this->lang->line('due')?></th>
                    <th>Recevied Status</th>
                    <th><?=$this->lang->line('purchase_action')?></th>
                  </tr>
                </tfoot>
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
            <?php echo $this->lang->line('purchase_transaction');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="accordion">
            <div class="card card-secondary">
              <div class="card-header purchaseDetail light-secondary-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#purchaseDetail" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('purchase_view')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="purchaseDetail" class="panel-collapse in collapse" style="">
                <div class="card-body purchase_detail  m-0 p-0">
                </div>
              </div>
            </div>
            <div class="card card-danger addTransactionCard">
              <div class="card-header addTransaction light-failure-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#addTransaction" class="" aria-expanded="true">
                    <?=$this->lang->line('purchase_payment')?>
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
                    <input type="hidden" name="transaction_type" value="<?=PAYMENT_TRANSACTION_TYPE?>">
                    <input type="hidden" name="entry_id" id="purchase_id" value="">
                    <input type="hidden" name="module" value="<?=PURCHASE_MODULE?>">
                    <input type="hidden" name="to_account" id="to_account" value="">
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
                    <?=$this->lang->line('purchase_previous_transaction')?>
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
            <?php echo $this->lang->line('purchase_delivery');?>
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
                        <?=$this->lang->line('purchase_delivery_add')?>
                        <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                      </a>
                    </h5>
                  </div>
                  <div id="addDeliveryDetail" class="panel-collapse in collapse" style="">
                    <div class="card-body add_delivery_detail">
                    
                    </div>
                    <div class="card-footer">
                      <input type="hidden" name="delivery_items" value="">
                      <input type="hidden" name="purchase_id" value="">
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
                    <?=$this->lang->line('previous_purchase_delivery')?>
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

<?php $this->load->view('layout/footer');?>

<div class="example-modal">
  <div class="modal fade" id="delete_purchase">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="restore_purchase">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="purchase_edit">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<script type="text/javascript">
  $(document).ready(function(e){
    $('#transaction-modal').on('show.bs.modal', function (e) {

      $('.addTransactionCard').css('display','block');

      var purchase_id = $(e.relatedTarget).data('purchase_id');
      $('#purchase_id').val(purchase_id);
      
      $.ajax({
        url: "<?php echo base_url('purchase/view')?>/"+purchase_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){

          $(".purchase_detail").html(data.purchase_view);
          $(".add_transaction").html(data.add_transaction);
          $("#to_account").val(data.to_account);
          $(".transaction_entries").html(data.previous_transaction_view);

          if(data.due_amount == 0)
          {
            $('.addTransactionCard').css('display','none');
            // collapse all accordian
            $('#transactionEntries').collapse("hide");
            $('#purchaseDetail').collapse("show");  
          }
          else
          {
            // collapse all accordian
            $('#transactionEntries').collapse("hide");
            $('#addTransaction').collapse("hide");
            $('#purchaseDetail').collapse("show");  
          }
        }
      });
    });

    $(document).on('hide.bs.modal', '#transaction-modal', function (e) {

        if($('#is_there_change_in_transaction').val() == "true")
        {
          location.reload();
        }

    });

    $('#delivery-modal').on('show.bs.modal', function (e) {

      var purchase_id = $(e.relatedTarget).data('purchase_id');
      $('#purchase_id').val(purchase_id);
      $('#addNewDeliveryDetail').data('purchase_id',purchase_id);

      if($(e.relatedTarget).hasClass('open_delivery_modal'))
      {
        $.ajax({
          url: "<?php echo base_url('purchase/view_delivery')?>",
          type: "POST",
          data: {
            'purchase_id':purchase_id,
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
            $(".purchase_delivery_detail").html(data.purchase_delivery_detail);
            
            $('.view_delivery_transaction_detail').css('display','none');
            $('#deliveryEntries').collapse("show");

            $('#addDeliveryDetailForm').find('input[name="purchase_id"]').val(purchase_id);
          }
        });  
      }      
    });


    $(document).on('hide.bs.modal', '#delivery-modal', function (e) {
   
      if($('#is_there_change_in_delivery_transaction').val() == "true")
      {
        location.reload();
      }
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('.purchaseDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

    // View delivery transaction
    $(document).on('click', '.view_delivery_transaction', function (event) {

      $('.view_delivery_transaction_detail').css('display','none');
      $(this).parents('tr').next('tr').toggle(200);
      
    });

    // Add new delivery of purchase
    $(document).on('click','#addNewDeliveryDetail',function(e){

      $('.addDeliveryDetailCard').toggle(200);

      var purchase_id = $('#addDeliveryDetailForm').find('input[name="purchase_id"]').val();

      $.ajax({
        url: "<?php echo base_url('purchase/add_purchase_delivery')?>/"+purchase_id,
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

    // Populate delivery data of purchase
    $(document).on('click','#addDeliveryDetailSubmit',function(e){
      e.preventDefault();

      var deliveryDataArray = [];

      $("#product_delivery_item_body").find('tr').each(function () {

        var tr                      = $(this).closest("tr");
        var deliveryData            = {};

        deliveryData['product_id']    = tr.find('input[name="quantity"]').data('product_id');
        deliveryData['warehouse_id']  = tr.find('input[name="quantity"]').data('warehouse_id');
        deliveryData['quantity']      = tr.find('input[name="quantity"]').val();
        deliveryData['batch_no']              = tr.find('input[name="quantity"]').data('batch_no');
        deliveryData['cost']                  = tr.find('input[name="quantity"]').data('cost');
        deliveryData['price']                 = tr.find('input[name="quantity"]').data('price');
        deliveryData['selling_price']                 = tr.find('input[name="quantity"]').data('selling_price');
        deliveryDataArray.push(JSON.stringify(deliveryData));
      });

       
      $('#addDeliveryDetailForm').find('input[name="delivery_items"]').val(deliveryDataArray.join('|'));

      $('#addDeliveryDetailForm').trigger('submit');
    });

    // Submit delivery data of purchase
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
        var purchase_id             = $('form#addDeliveryDetailForm').find('input[name="purchase_id"]').val(); 

        $('#addDeliveryDetailSubmit').text('Please wait while we are saving').addClass('disabled');

        setTimeout(function(){ 

          $.ajax({
            url: "<?php echo base_url('purchase/add_purchase_delivery')?>",
            type: "POST",
            data: DeliveryDetailFormData,
            dataType: "JSON",
            success: function(data){


              if(data.code == 1)
              {
                $('#addDeliveryDetailSubmit').text('Submit').removeClass('disabled');
                $.ajax({
                  url: "<?php echo base_url('purchase/view_delivery')?>",
                  type: "POST",
                  data: {
                    'purchase_id':purchase_id,
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
                    // $(".purchase_delivery_detail").html(data.purchase_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="purchase_id"]').val(purchase_id);

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
      var purchase_delivery_id  = $(this).data('purchase_delivery_id');
      var purchase_id           = $('#addDeliveryDetailForm').find('input[name="purchase_id"]').val();

      $(this).closest('td').find('.delete_delivery_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('purchase/delete_purchase_delivery')?>",
          type: "POST",
          data: {
            'purchase_delivery_id':purchase_delivery_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $("#is_there_change_in_delivery_transaction").val("true");

            setTimeout(function(){ 

              if(data.code == 1)
              {
                

                $.ajax({
                  url: "<?php echo base_url('purchase/view_delivery')?>",
                  type: "POST",
                  data: {
                    'purchase_id':purchase_id,
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
                    $(".purchase_delivery_detail").html(data.purchase_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="purchase_id"]').val(purchase_id);
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
      $('#purchaseDetail').collapse("hide");
    });

    $('body').on('click', '.addTransaction', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("show");
      $('#purchaseDetail').collapse("hide");
    });

    $('body').on('click', '.purchaseDetail', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("hide");
      $('#purchaseDetail').collapse("show");
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

    $('body').on('submit', '#addTransactionForm', function(e) {
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

      var closing_balance     = parseFloat($('form#addTransactionForm #from_account').find(':selected').data('closing_balance'));
      var transaction_amount  = parseFloat($('form#addTransactionForm #transaction_amount').val()); 


      if(closing_balance < transaction_amount)
      {
        var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');
        var text        = $('form#addTransactionForm #from_account').find(':selected').text();
        
        $("form#addTransactionForm #err_from_account").text("There is not enough balance in "+text).fadeIn('slow');
        $('form#addTransactionForm #from_account').addClass('is-invalid');
        isError = true;
      }
      else if($('form#addTransactionForm #from_account').find(':selected').val() == '' || $('form#addTransactionForm #from_account').find(':selected').val() == null)
      {
        var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');

        $("form#addTransactionForm #err_from_account").text(placeholder+ " field is required.").fadeIn('slow');
        $('form#addTransactionForm #from_account').addClass('is-invalid');
        isError = true;
      }
      else
      {
        $("form#addTransactionForm #err_from_account").text("").fadeOut('slow');
        $('form#addTransactionForm #from_account').removeClass('is-invalid');
        $('form#addTransactionForm #from_account').addClass('is-valid');
      }

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
        var purchase_id = $('#purchase_id').val();

        $('#addTransactionSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled')
        

        $.ajax({
        //   url: "<?php echo base_url('transaction/add')?>",
        url: "<?php echo base_url('transaction/add_purchase_payment')?>"
          type: "POST",
          data: transactionFormData,
          dataType: "JSON",
          success: function(data){

            $(".transaction_entries").html(data.previous_transaction);
            $("#is_there_change_in_transaction").val('true');

            $.ajax({
              url: "<?php echo base_url('purchase/view')?>/"+purchase_id,
              type: "GET",
              dataType: "JSON",
              success: function(data){

                $('#addTransactionSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        
                $(".purchase_detail").html(data.purchase_view);
                $(".add_transaction").html(data.add_transaction);
                $("#from_account").val(data.from_account);
                $(".transaction_entries").html(data.previous_transaction_view);

                if(data.due_amount == 0)
                {
                  $('.addTransactionCard').css('display','none');
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#purchaseDetail').collapse("hide");  
                }
                else
                {
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#addTransaction').collapse("hide");
                  $('#purchaseDetail').collapse("hide");  
                }
              }
            });
          }
        });
      } 

    });

    $(document).on("blur change keyup","form#addTransactionForm .field_validation",  function (event){
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

        var closing_balance     = parseFloat($('form#addTransactionForm #from_account').find(':selected').data('closing_balance'));
        var transaction_amount  = parseFloat($('form#addTransactionForm #transaction_amount').val()); 

        if(closing_balance < transaction_amount)
        {
          var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');
          var text        = $('form#addTransactionForm #from_account').find(':selected').text();

          $("form#addTransactionForm #err_from_account").text("There is not enough balance in "+text).fadeIn('slow');
          $('form#addTransactionForm #from_account').addClass('is-invalid');
          return false;
        }
        else if($('form#addTransactionForm #from_account').find(':selected').val() == '' || $('form#addTransactionForm #from_account').find(':selected').val() == null)
        {
          var placeholder = $('form#addTransactionForm #from_account').attr('placeholder');
          
          $("form#addTransactionForm #err_from_account").text(placeholder+ " field is required.").fadeIn('slow');
          $('form#addTransactionForm #from_account').addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addTransactionForm #err_from_account").text("").fadeOut('slow');
          $('form#addTransactionForm #from_account').removeClass('is-invalid');
          $('form#addTransactionForm #from_account').addClass('is-valid');
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
      var purchase_id         = $(this).data('entry_id');

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
            $("#is_there_change_in_transaction").val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                $.ajax({
                  url: "<?php echo base_url('purchase/view')?>/"+purchase_id,
                  type: "GET",
                  dataType: "JSON",
                  success: function(data){

                    $(".purchase_detail").html(data.purchase_view);
                    $(".add_transaction").html(data.add_transaction);
                    $("#from_account").val(data.from_account);
                    $(".transaction_entries").html(data.previous_transaction_view);

                    if(data.due_amount == 0)
                    {
                      $('.addTransactionCard').css('display','none');
                      // collapse all accordian
                      $('#transactionEntries').collapse("show");
                      $('#purchaseDetail').collapse("hide");  
                    }
                    else
                    {
                      // collapse all accordian
                      $('.addTransactionCard').css('display','block');
                      $('#transactionEntries').collapse("hide");
                      $('#addTransaction').collapse("hide");
                      $('#purchaseDetail').collapse("show");  
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


    $('.email_invoice').click(function(e){

      var purchase_id = $(this).data('purchase_id');
      var i_icon = $(this);

      i_icon.find('.email_icon').removeClass('fa-at');
      i_icon.find('.email_icon').addClass('fa-spinner').addClass('fa-spin');
      
      $.ajax({
        url: "<?php echo base_url('purchase/email_invoice') ?>",
        type: "POST",
        dataType: "json",
        data:{
          'purchase_id' : purchase_id,
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
   /* function initialize_datatable()
    {
      var purchase_payment = $('select[name="purchase_payment"] option:selected').val();

      $('#example').DataTable({ 
   
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "order": [], //Initial no order.
          "bDestroy": true, //Destroy before reinitialise
          "pageLength": 100,
   
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": "<?php echo site_url('purchase/ajax_list')?>",
              "type": "POST",
              "data":  {
                'purchase_payment': purchase_payment,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              }
          },

          'initComplete':function(settings, json){
            $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
          },  
   
          //Set column definition initialisation properties.
          "columnDefs": [
            { 
              "targets": [ 0,9,10,11,12 ], //first column / numbering column
              "orderable": false, //set not orderable
            },
          ],
      });
    }
*/

function initialize_datatable()
{
    var purchase_payment = $('select[name="purchase_payment"] option:selected').val();

    $('#example').DataTable({ 
        "processing": true,
        "serverSide": true,
        "order": [],
        "bDestroy": true,
        "pageLength": 100,
        
        "ajax": {
            "url": "<?php echo site_url('purchase/ajax_list')?>",
            "type": "POST",
            "data": {
                'purchase_payment': purchase_payment,
                '<?php echo $this->security->get_csrf_token_name(); ?>': '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete': function(settings, json) {
            $('[data-tt="tooltip"]').tooltip({trigger: 'hover'}); 
        },

        // IMPORTANT: Define columns to match your HTML
        "columns": [
            { "data": 0, "name": "sr_no" },           // Sr.No
            { "data": 1, "name": "invoice_no" },      // Invoice No
            { "data": 2, "name": "purchase_date" },   // Purchase Date
            { "data": 3, "name": "warehouse" },       // Warehouse
            { "data": 4, "name": "supplier" },        // Supplier
            { "data": 5, "name": "discount" },        // DISCOUNT - This must exist
            { "data": 6, "name": "taxable" },         // Taxable
            { "data": 7, "name": "tax" },             // Tax
            { "data": 8, "name": "total" },           // Total Price
            { "data": 9, "name": "paid" },            // Paid
            { "data": 10, "name": "due" },            // Due
            { "data": 11, "name": "delivery_status" }, // Delivery Status
            { "data": 12, "name": "action" }          // Action
        ],

        "columnDefs": [
            { "targets": [0, 9, 10, 11, 12], "orderable": false }
        ],
    });
}
    $(document).on('change','select[name="purchase_payment"]',function(e){
      initialize_datatable();
    })


    $(document).on('show.bs.modal','#delete_purchase', function (e) {
      var purchase_id = $(e.relatedTarget).data('purchase_id');
      $('#delete_purchase').find('#id').val(purchase_id);

      // alert(purchase_id);

      $.ajax({
        url: "<?php echo base_url('purchase/purchase_delete_confirmation')?>",
        type: "POST",
        data:{
          'purchase_id': purchase_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_purchase').find('.modal-content').html(data.purchase_delete_modal_body);
        }
      });
    });

    // Delete record with please wait text
    $(document).on('submit','#deletePurchaseForm',function(e){
      $('#deletePurchaseSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    
    $(document).on('show.bs.modal','#restore_purchase', function (e) {
      var purchase_id = $(e.relatedTarget).data('purchase_id');
      $('#restore_purchase').find('#id').val(purchase_id);

      // alert(purchase_id);

      $.ajax({
        url: "<?php echo base_url('purchase/purchase_restore_confirmation')?>",
        type: "POST",
        data:{
          'purchase_id': purchase_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#restore_purchase').find('.modal-content').html(data.purchase_restore_modal_body);
        }
      });
    });

    // Restore record with please wait text
    $(document).on('submit','#restorePurchaseForm',function(e){
      $('#restorePurchaseSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    $(document).on('show.bs.modal','#purchase_edit', function (e) {
      var purchase_id = $(e.relatedTarget).data('purchase_id');
      $('#purchase_edit').find('#id').val(purchase_id);

      // alert(purchase_id);

      $.ajax({
        url: "<?php echo base_url('purchase/purchase_edit_confirmation')?>",
        type: "POST",
        data:{
          'purchase_id': purchase_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#purchase_edit').find('.modal-content').html(data.purchase_edit_modal_body);
        }
      });


    });

    /*************************** End Dynamic Sale List with Datatables ****************************/

    
    $(document).on('click','.email-modal',function(e){
      var purchase_id = $(this).data('purchase_id');
      // alert();

      $.ajax({
        url: "<?php echo base_url('purchase/send_email')?>/"+purchase_id,
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
        url: "<?php echo base_url('purchase/send_email')?>",
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
