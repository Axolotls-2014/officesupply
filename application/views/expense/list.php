<?php $this->load->view('layout/header');?>

<style type="text/css">
  .text-xs{
    font-size: 11px !important;
  }
</style>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('expense')?>"><?=$this->lang->line('expense_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('expense_list')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12 col-sm-12 col-md-6">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fab fa-codepen"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><?=$this->lang->line('expense_total_expense')?></span>
              <span class="info-box-number" style="font-size: 19px">
                <!-- <small><?=$this->session->userdata('currency_symbol')?></small> -->
                <?=$this->session->userdata('currency_symbol')?>
                <?=($total_expense == '') ? "0.0" : number_format_i($total_expense);?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        
        <div class="col-12 col-sm-12 col-md-6">
          <div class="info-box mb-3">
            <span class="info-box-icon bg-danger elevation-1"><i class="fas fa-clipboard-check"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><?=$this->lang->line('expense_amount_payable')?></span>
              <span class="info-box-number" style="font-size: 19px">
                <?=$this->session->userdata('currency_symbol')?>
                <?=($total_amount_paid == '') ? '0.000' : number_format_i(($total_expense -$total_amount_paid))?>                  
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>

        
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('expense_list')?></h3>
              ( <span class="text-xs"><?=$this->lang->line('all_amount_are_in')?> <b><?=$this->session->userdata('currency_symbol')?></b></span> )
              <?php 
                if($this->permission_model->has_permission('add_expense'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('expense/add')?>" data-tt="tooltip" title="Click here to Add Expense">
                      <i class="fas fa-money-check-alt mr-2"></i><?=$this->lang->line('expense_add')?>
                    </a>
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
                    <th><?=$this->lang->line('expense_date')?></th>
                    <th><?=$this->lang->line('expense_expense_category')?></th>
                    <th><?=$this->lang->line('expense_supplier')?></th>
                    <!-- <th><?=$this->lang->line('expense_amount')?></th>
                    <th><?=$this->lang->line('expense_cgst')?></th>
                    <th><?=$this->lang->line('expense_sgst')?></th>
                    <th><?=$this->lang->line('expense_igst')?></th> -->
                    <th><?=$this->lang->line('expense_document')?></th>
                    <th><?=$this->lang->line('expense_remarks')?></th>
                    <th><?=$this->lang->line('expense_total_amount')?></th>
                    <th><?=$this->lang->line('expense_paid_amount')?></th>
                    <th><?=$this->lang->line('expense_due_amount')?></th>
                    <th><?=$this->lang->line('expense_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('expense_date')?></th>
                    <th><?=$this->lang->line('expense_expense_category')?></th>
                    <th><?=$this->lang->line('expense_supplier')?></th>
                    <!-- <th><?=$this->lang->line('expense_amount')?></th> -->
                    <!-- <th><?=$this->lang->line('expense_cgst')?></th>
                    <th><?=$this->lang->line('expense_sgst')?></th>
                    <th><?=$this->lang->line('expense_igst')?></th> -->
                    <th><?=$this->lang->line('expense_document')?></th>
                    <th><?=$this->lang->line('expense_remarks')?></th>
                    <th><?=$this->lang->line('expense_total_amount')?></th>
                    <th><?=$this->lang->line('expense_paid_amount')?></th>
                    <th><?=$this->lang->line('expense_due_amount')?></th>
                    
                    <th><?=$this->lang->line('expense_action')?></th>
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
            <?php echo $this->lang->line('expense_transaction');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div id="accordion">
            <div class="card card-secondary">
              <div class="card-header expenseDetail light-secondary-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#expenseDetail" class="collapsed" aria-expanded="false">
                    <?=$this->lang->line('expense_view')?>
                    <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                  </a>
                </h4>
              </div>
              <div id="expenseDetail" class="panel-collapse in collapse" style="">
                <div class="card-body expense_detail  m-0 p-0">
                </div>
              </div>
            </div>
            <div class="card card-danger addTransactionCard">
              <div class="card-header addTransaction light-failure-header">
                <h4 class="card-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#addTransaction" class="" aria-expanded="true">
                    <?=$this->lang->line('expense_payment')?>
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
                    <input type="hidden" name="entry_id" id="expense_id" value="">
                    <input type="hidden" name="module" value="<?=EXPENSE_MODULE?>">
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
                    <?=$this->lang->line('expense_previous_transaction')?>
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
          <input type="hidden" name="is_there_change_in_transaction" value="false" id="is_there_change_in_transaction">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<?php $this->load->view('layout/footer');?>

<div class="example-modal">
  <div class="modal fade" id="delete_expense">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="expense_edit">
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

      var expense_id = $(e.relatedTarget).data('expense_id');

      if (!(typeof expense_id === 'undefined')) {

        $('#expense_id').val(expense_id);
      
        $.ajax({
          url: "<?php echo base_url('expense/view')?>/"+expense_id,
          type: "GET",
          dataType: "JSON",
          success: function(data){

            $(".expense_detail").html(data.expense_view);
            $(".add_transaction").html(data.add_transaction);
            $("#to_account").val(data.to_account);
            $(".transaction_entries").html(data.previous_transaction_view);

            $('#transaction-modal').find('input[name="voucher_date"]').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
            });

            if(data.due_amount == 0)
            {
              $('.addTransactionCard').css('display','none');
              // collapse all accordian
              $('#transactionEntries').collapse("hide");
              $('#expenseDetail').collapse("show");  
            }
            else
            {
              // collapse all accordian
              $('#transactionEntries').collapse("hide");
              $('#addTransaction').collapse("hide");
              $('#expenseDetail').collapse("show");  
            }

            
          }
        });
      }
      
    });

    $('#transaction-modal').on('hide.bs.modal', function (e) {
      
      if($('#is_there_change_in_transaction').val() == "true")
      {
        location.reload();
      }
    
    });

      /*************************** Start Dynamic Product List with Datatables **************************/

    var table = $('#example').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('expense/ajax_list')?>",
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
            "targets": [5,6,7, 8 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
    });


    $(document).on('show.bs.modal','#delete_expense', function (e) {
      var expense_id = $(e.relatedTarget).data('expense_id');
      $('#delete_expense').find('#id').val(expense_id);

      // alert(expense_id);

      $.ajax({
        url: "<?php echo base_url('expense/expense_delete_confirmation')?>",
        type: "POST",
        data:{
          'expense_id': expense_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_expense').find('.modal-content').html(data.expense_delete_modal_body);
        }
      });


    });


     // Delete record with please wait text
    $(document).on('submit','#deleteExpenseForm',function(e){
      $('#deleteExpenseSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });


     $(document).on('show.bs.modal','#expense_edit', function (e) {
      var expense_id = $(e.relatedTarget).data('expense_id');
      $('#expense_edit').find('#id').val(expense_id);

      // alert(expense_id);

      $.ajax({
        url: "<?php echo base_url('expense/expense_edit_confirmation')?>",
        type: "POST",
        data:{
          'expense_id': expense_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#expense_edit').find('.modal-content').html(data.expense_edit_modal_body);
        }
      });


    });

    /*************************** End Dynamic Product List with Datatables ****************************/

  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    

    $('body').on('click', '.transactionEntries', function() {
      $('#transactionEntries').collapse("show");
      $('#addTransaction').collapse("hide");
      $('#expenseDetail').collapse("hide");
    });

    $('body').on('click', '.addTransaction', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("show");
      $('#expenseDetail').collapse("hide");
    });

    $('body').on('click', '.expenseDetail', function() {
      $('#transactionEntries').collapse("hide");
      $('#addTransaction').collapse("hide");
      $('#expenseDetail').collapse("show");
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
        var expense_id = $('#expense_id').val();

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
              url: "<?php echo base_url('expense/view')?>/"+expense_id,
              type: "GET",
              dataType: "JSON",
              success: function(data){

                $(".expense_detail").html(data.expense_view);
                $(".add_transaction").html(data.add_transaction);
                $("#to_account").val(data.to_account);
                $(".transaction_entries").html(data.previous_transaction_view);

                $('#transaction-modal').find('input[name="voucher_date"]').datepicker({
                  weekStart: 1,
                  daysOfWeekHighlighted: "6,0",
                  autoclose: true,
                  todayHighlight: true,
                  format: 'dd-mm-yyyy'
                });

                if(data.due_amount == 0)
                {
                  $('.addTransactionCard').css('display','none');
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#expenseDetail').collapse("hide");  
                }
                else
                {
                  // collapse all accordian
                  $('#transactionEntries').collapse("show");
                  $('#addTransaction').collapse("hide");
                  $('#expenseDetail').collapse("hide");  
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
          return false;
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
      var expense_id      = $(this).data('entry_id');

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
                  url: "<?php echo base_url('expense/view')?>/"+expense_id,
                  type: "GET",
                  dataType: "JSON",
                  success: function(data){

                    $(".expense_detail").html(data.expense_view);
                    $(".add_transaction").html(data.add_transaction);
                    $("#to_account").val(data.to_account);
                    $(".transaction_entries").html(data.previous_transaction_view);

                    $('#transaction-modal').find('input[name="voucher_date"]').datepicker({
                      weekStart: 1,
                      daysOfWeekHighlighted: "6,0",
                      autoclose: true,
                      todayHighlight: true,
                      format: 'dd-mm-yyyy'
                    });

                    if(data.due_amount == 0)
                    {
                      $('.addTransactionCard').css('display','none');
                      // collapse all accordian
                      $('#transactionEntries').collapse("show");
                      $('#expenseDetail').collapse("hide");  
                    }
                    else
                    {
                      // collapse all accordian
                      $('.addTransactionCard').css('display','block');
                      $('#transactionEntries').collapse("hide");
                      $('#addTransaction').collapse("hide");
                      $('#expenseDetail').collapse("show");  
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



