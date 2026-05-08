<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_inventory')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_transfer')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('transfer_list')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('transfer_list')?></h3>
              ( <span class="text-xs"><?=$this->lang->line('all_amount_are_in')?> <b><?=$this->session->userdata('currency_symbol')?></b></span> )
              <?php 
                if($this->permission_model->has_permission('add_transfer'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('transfer/add')?>" data-tt="tooltip" title="Click here to Add Transfer"><i class="far fa-snowflake mr-2"></i><?=$this->lang->line('transfer_add')?></a>
                  </li>
                </ul>
              </div>
              <?php 
                }
              ?>
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    
                    <th><?=$this->lang->line('transfer_date')?></th>
                    <th>transfer_from</th>
                    <th>transfer_to</th>
                    <th><?=$this->lang->line('transfer_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
                    <th width="20%"><?=$this->lang->line('transfer_action')?></th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('transfer_date')?></th>
                    <th>transfer_from</th>
                    <th>transfer_to</th>
                    <th><?=$this->lang->line('transfer_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
                    <th><?=$this->lang->line('transfer_action')?></th>
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



<?php $this->load->view('layout/footer');?>

<div class="example-modal">
  <div class="modal fade" id="delete_transfer">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="transfer_edit">
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

    $('.saleDeliveryDetailCard').css('display','none');
    $('.addDeliveryDetailCard').css('display','none');

    $('#transaction-modal').on('show.bs.modal', function (e) {

      $('.addTransactionCard').css('display','block');

      var transfer_id = $(e.relatedTarget).data('transfer_id');
      $('#transfer_id').val(transfer_id);
      
      $.ajax({
        url: "<?php echo base_url('sale/view')?>/"+transfer_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){

          $(".transfer_detail").html(data.transfer_view);
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

      var transfer_id = $(e.relatedTarget).data('transfer_id');
      $('#transfer_id').val(transfer_id);
      $('#addNewDeliveryDetail').data('transfer_id',transfer_id);

      if($(e.relatedTarget).hasClass('open_delivery_modal'))
      {
        $.ajax({
          url: "<?php echo base_url('sale/view_delivery')?>",
          type: "POST",
          data: {
            'transfer_id':transfer_id,
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
            $(".transfer_delivery_detail").html(data.transfer_delivery_detail);
            
            $('.view_delivery_transaction_detail').css('display','none');
            $('#deliveryEntries').collapse("show");

            $('#addDeliveryDetailForm').find('input[name="transfer_id"]').val(transfer_id);

            
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

      var transfer_id = $('#addDeliveryDetailForm').find('input[name="transfer_id"]').val();

      $.ajax({
        url: "<?php echo base_url('sale/add_transfer_delivery')?>/"+transfer_id,
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

        deliveryData['product_id']  = tr.find('input[name="quantity"]').data('product_id');
        deliveryData['quantity']    = tr.find('input[name="quantity"]').val();

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
        var transfer_id                 = $('form#addDeliveryDetailForm').find('input[name="transfer_id"]').val(); 

        $('#addDeliveryDetailSubmit').text('Please wait while we are saving').addClass('disabled');

        setTimeout(function(){ 

          $.ajax({
            url: "<?php echo base_url('sale/add_transfer_delivery')?>",
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
                    'transfer_id':transfer_id,
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
                    // $(".transfer_delivery_detail").html(data.transfer_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="transfer_id"]').val(transfer_id);

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
      var transfer_delivery_id  = $(this).data('transfer_delivery_id');
      var transfer_id           = $('#addDeliveryDetailForm').find('input[name="transfer_id"]').val();

      $(this).closest('td').find('.delete_delivery_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('sale/delete_transfer_delivery')?>",
          type: "POST",
          data: {
            'transfer_delivery_id':transfer_delivery_id,
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
                    'transfer_id':transfer_id,
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
                    $(".transfer_delivery_detail").html(data.transfer_delivery_detail);
                    
                    $('.view_delivery_transaction_detail').css('display','none');
                    $('#deliveryEntries').collapse("show");

                    $('#addDeliveryDetailForm').find('input[name="transfer_id"]').val(transfer_id);
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
        var transfer_id = $('#transfer_id').val();

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
              url: "<?php echo base_url('sale/view')?>/"+transfer_id,
              type: "GET",
              dataType: "JSON",
              success: function(data){

                $(".transfer_detail").html(data.transfer_view);
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
      var transfer_id         = $(this).data('entry_id');

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
                  url: "<?php echo base_url('sale/view')?>/"+transfer_id,
                  type: "GET",
                  dataType: "JSON",
                  success: function(data){

                    $(".transfer_detail").html(data.transfer_view);
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

          var transfer_id = $(this).data('transfer_id');
          var i_icon = $(this);

          i_icon.find('.email_icon').removeClass('fa-at');
          i_icon.find('.email_icon').addClass('fa-spinner').addClass('fa-spin');
          
          $.ajax({
            url: "<?php echo base_url('sale/email_invoice') ?>",
            type: "POST",
            dataType: "json",
            data:{
              'transfer_id' : transfer_id,
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

           /*************************** Start Dynamic Sale List with Datatables **************************/

    var table = $('#example').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('transfer/ajax_list')?>",
            "type": "POST",
            "data":  {
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 0,4 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
    });


    $(document).on('show.bs.modal','#delete_transfer', function (e) {
      var transfer_id = $(e.relatedTarget).data('transfer_id');
      $('#delete_transfer').find('#id').val(transfer_id);

      // alert(transfer_id);

      $.ajax({
        url: "<?php echo base_url('transfer/transfer_delete_confirmation')?>",
        type: "POST",
        data:{
          'transfer_id': transfer_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_transfer').find('.modal-content').html(data.transfer_delete_modal_body);
        }
      });


    });

     // Delete record with please wait text
    $(document).on('submit','#deleteTransferForm',function(e){
      $('#deleteTransferSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    $(document).on('show.bs.modal','#transfer_edit', function (e) {
      var transfer_id = $(e.relatedTarget).data('transfer_id');
      $('#transfer_edit').find('#id').val(transfer_id);

      // alert(transfer_id);

      $.ajax({
        url: "<?php echo base_url('transfer/transfer_edit_confirmation')?>",
        type: "POST",
        data:{
          'transfer_id': transfer_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#transfer_edit').find('.modal-content').html(data.transfer_edit_modal_body);
        }
      });


    });

    /*************************** End Dynamic Transfer List with Datatables ****************************/

  });
</script>
