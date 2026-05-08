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
                <?=($total_expense == '') ? "0.0" : $total_expense;?>
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
                <?=($total_amount_paid == '') ? '0.000' : ($total_expense -$total_amount_paid)?>                  
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
              
              <table id="example1" class="table table-bordered table-striped">
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
                  <?php 
                    if(sizeof($expense) > 0)
                    {
                      foreach ($expense as $value) 
                      {
                  ?>
                  <tr>                        
                    
                    <td><?php echo date('d-m-Y', strtotime($value->date));?></td>
                    <td><?php echo $value->expense_category_name;?></td>
                    <td><?php echo $value->company_name;?></td>
                    <!--<td><?php echo $value->amount;?></td>
                    <td><?php echo $value->cgst;?></td>
                    <td><?php echo $value->sgst;?></td>
                    <td><?php echo $value->igst;?></td> -->
                    <td>
                      <div id="document_list">
                        <?php 
                          

                          if($value->document != '' || $value->document != null)
                          {
                            $document_array = explode(",", $value->document);
                            for ($i=0; $i < sizeof($document_array); $i++) 
                            {
                              $file_name_without_ext  = explode(".", $document_array[$i])[0];
                              $file_ext         = explode(".", $document_array[$i])[1];

                              $actual_file_name     = substr($file_name_without_ext, 0, -14).'.'.$file_ext;
                        ?>
                        <div class="btn-group" style="padding-bottom: 10px;">
                          <a target="_blank" class="btn btn-warning btn-xs" id="<?=$document_array[$i]?>" href="<?=base_url('attachment/download_attachment/'.$document_array[$i])?>" data-tt="tooltip" title="<?php echo $actual_file_name?>">
                            <i class="fa fa-paperclip"></i>
                          </a>
                        </div>
                        <?php 
                            }
                          }
                        ?>
                      </div>
                    </td>
                    <td>
                      <?=$value->remarks?>
                    </td>
                    <td><?php echo $value->total_amount;?></td>
                    <td>
                      <?php
                        $paid_amount = $this->transaction_model->get_total_transaction_amount($value->id, EXPENSE_MODULE, PAYMENT_TRANSACTION_TYPE);
                        ($paid_amount == '') ? $paid_amount = 0 : $paid_amount = $paid_amount;
                      ?>
                      <span class="text-success"><?=($paid_amount == '') ? '0.000' : $paid_amount;?></span>
                    </td>
                    <td>
                      <span class="text-danger"><?php echo $value->total_amount-$paid_amount;?></span>
                    </td>
                    <td>
                      <table>
                        <tr>
                          <?php 
                            if($this->permission_model->has_permission('view_expense'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="<?php echo base_url('expense/view/'.$value->id);?>" class="btn btn-default btn-xs" data-tt="tooltip" title="View Expense">
                              <i class="fas fa-eye"></i>
                            </a>
                          </td>
                          <?php 
                            }
                          ?>    
                          <?php 
                            if($this->permission_model->has_permission('manage_transaction'))
                            {
                          ?>
                          <td class="p-2">
                            <a href="#transaction-modal" data-toggle="modal" class="btn bg-purple btn-xs" data-tt="tooltip" title="Make Payment" data-expense_id="<?=$value->id?>"> 
                              <i class="fas fa-rupee-sign"></i>
                            </a>
                          </td>
                          <?php 
                            }
                          ?>
                          <?php 
                            if($this->permission_model->has_permission('edit_expense'))
                            {
                          ?>
                          <td class="p-2">
                            
                            <?php  
                              if($paid_amount > 0)
                              {
                            ?>
                              <a href="#" data-toggle="modal" data-target="#edit_expense_<?php echo $value->id;?>" class="btn btn-info btn-xs" data-tt="tooltip" title="Edit Expense">
                                <i class="fas fa-edit"></i>
                              </a>
                              <div class="example-modal">
                                <div class="modal fade" id="edit_expense_<?php echo $value->id;?>">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header  warning-header">
                                        <h4 class="modal-title">
                                          <?php echo $this->lang->line('expense_delete');?>
                                        </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                        
                                      </div>
                                      <div class="modal-body">
                                        <p>
                                          <?=$this->lang->line('due_to_following_reason')?>
                                        </p>
                                        <ul style="list-style: disc;">
                                          <?php 
                                            if($paid_amount > 0)
                                            {
                                          ?>
                                            <li><?=$this->lang->line('due_to_following_reason_payment_transaction')?></li>
                                          <?php
                                            }
                                          ?>
                                        </ul>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                          <?php echo $this->lang->line('btn_modal_close');?>
                                        </button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php 
                              }
                              else
                              {
                            ?>
                              <a href="<?php echo base_url('expense/edit/'.$value->id);?>" class="btn btn-info btn-xs" data-tt="tooltip" title="Edit Expense">
                                <i class="fas fa-edit"></i>
                              </a>
                            <?php
                              }
                            ?>
                            
                          </td>
                          <?php 
                            }
                          ?>

                          <?php 
                            if($this->permission_model->has_permission('delete_expense'))
                            {
                          ?>
                          <td class="p-2">                            
                            <a href="#"  data-toggle="modal" data-target="#delete_expense_<?php echo $value->id;?>" data-tt="tooltip" title="Delete Expense" class="btn btn-danger btn-xs delete_product">
                              <i class="fas fa-trash"></i>
                            </a>

                            <?php  
                              if($paid_amount > 0)
                              {
                            ?>
                              <div class="example-modal">
                                <div class="modal fade" id="delete_expense_<?php echo $value->id;?>">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header  warning-header">
                                        <h4 class="modal-title">
                                          <?php echo $this->lang->line('expense_delete');?>
                                        </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                        
                                      </div>
                                      <div class="modal-body">
                                        <p>
                                          <?=$this->lang->line('due_to_following_reason')?>
                                        </p>
                                        <ul style="list-style: disc;">
                                          <?php 
                                            if($paid_amount > 0)
                                            {
                                          ?>
                                            <li><?=$this->lang->line('due_to_following_reason_payment_transaction')?></li>
                                          <?php
                                            }
                                          ?>
                                        </ul>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                          <?php echo $this->lang->line('btn_modal_close');?>
                                        </button>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php 
                              }
                              else
                              {
                            ?>
                              <div class="example-modal">
                                <div class="modal fade" id="delete_expense_<?php echo $value->id;?>">
                                  <div class="modal-dialog">
                                    <div class="modal-content">
                                      <div class="modal-header  failure-header">
                                        <h4 class="modal-title">
                                          <?php echo $this->lang->line('expense_delete');?>
                                        </h4>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                          <span aria-hidden="true">&times;</span>
                                        </button>
                                        
                                      </div>
                                      <div class="modal-body">
                                        <p>
                                          <?php echo $this->lang->line('expense_delete_message') .$value->expense_category_name."?";?>
                                        </p>
                                      </div>
                                      <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                          <?php echo $this->lang->line('btn_modal_close');?>
                                        </button>
                                        <form action="<?php echo base_url('expense/delete');?>" method="POST">
                                          <input type="hidden" name="id" value="<?=$value->id?>">
                                          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                                          <button type="submit" name="submit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
                                        </form>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            <?php
                              }
                            ?>  
                          </td>
                          <?php 
                            }
                          ?>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <?php  
                      }
                    }
                    else
                    {
                  ?>
                  <tr>
                    <td colspan="11">No Record(s) are available.</td>
                  </tr>
                  <?php
                    }
                  ?>
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

<script type="text/javascript">
  $(document).ready(function(e){
    $('#transaction-modal').on('show.bs.modal', function (e) {

      $('.addTransactionCard').css('display','block');

      var expense_id = $(e.relatedTarget).data('expense_id');
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
    });

    $('#transaction-modal').on('hide.bs.modal', function (e) {
      
      if($('#is_there_change_in_transaction').val() == "true")
      {
        location.reload();
      }
    
    });
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



