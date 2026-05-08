<?php $this->load->view('layout/header');?>

<style>
  .custom-swal-content {
    display: none; /* Hide the content */
}

.hidden-span {
  display: none;
}
</style>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_account')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_cash_bank_entry')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('cash_bank_entry_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('cash_bank_entry_list')?></h3>
             
              <div class="card-tools">
                <!-- <button type="button" class="btn btn-block btn-primary btn-sm add_cash_bank_entry_modal" data-toggle="modal" data-target="#add_cash_bank_entry_modal" data-tt="tooltip" title="Click here to Add Cash Bank Entry" data-cash_bank_entry_id="">Add Cash Bank Entry</button> -->
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item mr-2">
                    <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Cash bank entry">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>
                  
                  <li class="nav-item mr-2">
                    <button type="button" class="btn btn-block btn-info btn-sm bulk_cash_bank_entry_modal" data-toggle="modal" data-target="#bulk_cash_bank_entry_modal" data-tt="tooltip" title="Click here to Bulk Cash Bank Entry" data-cash_bank_entry_id="">Bulk Entry</button>
                  </li>

                <?php 
                  if($this->permission_model->has_permission('add_cash_bank_entry'))
                  {
                ?>
                  <li class="nav-item">
                    <button type="button" class="btn btn-block btn-primary btn-sm add_cash_bank_entry_modal" data-toggle="modal" data-target="#add_cash_bank_entry_modal" data-tt="tooltip" title="Click here to Add Cash Bank Entry" data-cash_bank_entry_id="">Add Cash Bank Entry</button>
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
                    <th width="2%"><input type="checkbox" class="all_cash_bank_entry"></th>
                    <th><?=$this->lang->line('cash_bank_entry_reference_no')?></th>
                    <th><?=$this->lang->line('cash_bank_entry_voucher_type')?></th>
                    <th><?=$this->lang->line('cash_bank_entry_from_account_id')?></th>
                    <th><?=$this->lang->line('cash_bank_entry_to_account_id')?></th>
                    <th><?=$this->lang->line('cash_bank_entry_amount')?></th>
                    <th width="20%"><?=$this->lang->line('cash_bank_entry_action')?></th>
                  </tr>
                </thead>
                
                <tbody id="cash_bank_entry_list">
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('cash_bank_entry_reference_no')?></th>
                    <th><?=$this->lang->line('cash_bank_entry_voucher_type')?></th>
                    <th><?=$this->lang->line('cash_bank_entry_from_account_id')?></th>
                    <th><?=$this->lang->line('cash_bank_entry_to_account_id')?></th>
                    <th><?=$this->lang->line('cash_bank_entry_amount')?></th>
                    <th><?=$this->lang->line('cash_bank_entry_action')?></th>
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
  <div class="modal fade" id="add_cash_bank_entry_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="bulk_cash_bank_entry_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="edit_cash_bank_entry_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="delete_cash_bank_entry">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<!-- Parent Modal -->
<div class="modal fade" id="parentModal" tabindex="-1" aria-labelledby="parentModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="parentModalLabel">Parent Modal</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Button to open nested modal -->
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#nestedModal">
          Open Nested Modal
        </button>
      </div>
    </div>
  </div>
</div>



<script type="text/javascript">
  $(document).ready(function(e){

    const cashBankEntryToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    /*************************** Start Dynamic Cash bank entry List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    {
     $('#example').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('cash_bank_entry/ajax_list')?>",
            "type": "POST",
            "data":  {
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          reinitialise();
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [0,6], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
     });
    }

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

    $(document).on('change', '.all_cash_bank_entry', function() {
      if(this.checked == true)
        $('.single_cash_bank_entry').prop('checked',true);
      else
        $('.single_cash_bank_entry').prop('checked',false);
    });

    $(document).on('change', '.single_cash_bank_entry', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#cash_bank_entry_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_cash_bank_entry          = $('.single_cash_bank_entry').length;
      var total_checked_single_cash_bank_entry  = $('.single_cash_bank_entry:checked').length;
      
      if(total_checked_single_cash_bank_entry < total_single_cash_bank_entry && total_checked_single_cash_bank_entry > 0){
        $('.all_cash_bank_entry').prop('indeterminate',true); 
      }
      else if(total_checked_single_cash_bank_entry == total_single_cash_bank_entry){
        $('.all_cash_bank_entry').prop('indeterminate',false);
        $('.all_cash_bank_entry').prop('checked',true);
      }
      else if(total_checked_single_cash_bank_entry == 0){
        $('.all_cash_bank_entry').prop('indeterminate',false);
        $('.all_cash_bank_entry').prop('checked',false);
      }
    }

    $(document).on('click','#cash_bank_entry_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_cash_bank_entry:checked'); // Select only checked checkboxes
     
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); // Assuming the value holds the employee_id
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
              title:'Warning',
              text: "Please select at least one cash bank entry record to export.",
              icon: "warning",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                  confirmButton: "btn btn-primary"
              }
          });
          return; // Stop further execution
      }

      // Redirect to export URL with selected parameters
      var exportUrl = '<?= base_url('cash_bank_entry/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",");

      window.location.href = exportUrl;
    });

    $(document).on('click', ".bulk_cash_bank_entry_modal" ,function(){
      var cash_bank_entry_id = $(this).data('cash_bank_entry_id');

      $.ajax({
        url: "<?php echo base_url('cash_bank_entry/bulk_entry')?>/"+cash_bank_entry_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#bulk_cash_bank_entry_modal').find('.modal-content').html(data.bulk_cash_bank_entry_modal_body);
          $('#bulk_cash_bank_entry_modal').modal('show');
          reinitialise();  
          $('.datepicker').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
          }); 
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on('hidden.bs.modal','#bulk_cash_bank_entry_modal',function(e){
      $('#bulk_cash_bank_entry_modal').find('.modal-content').html('');
    });

    // start to add cash bank entry modal

    $(document).on('click', ".add_cash_bank_entry_modal" ,function(){
      var cash_bank_entry_id = $(this).data('cash_bank_entry_id');

      $.ajax({
        url: "<?php echo base_url('cash_bank_entry/add')?>/"+cash_bank_entry_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_cash_bank_entry_modal').find('.modal-content').html(data.add_cash_bank_entry_modal_body);
          $('#add_cash_bank_entry_modal').modal('show');
          reinitialise();  
          $('.datepicker').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
          }); 
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on('hidden.bs.modal','#add_cash_bank_entry_modal',function(e){
      $('#add_cash_bank_entry_modal').find('.modal-content').html('');
    });

    $(document).on('submit','#addcashBankEntryForm',function(e){
      
      e.preventDefault();

      $('#addcashBankEntrySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addcashBankEntryForm').serialize();

      var isError = false;

      $('form#addcashBankEntryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addcashBankEntryForm  #err_"+id).text(field+ " field is required.");
            $('form#addcashBankEntryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addcashBankEntryForm #err_"+id).text("");
            $('form#addcashBankEntryForm #'+id).removeClass('is-invalid');
            $('form#addcashBankEntryForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addcashBankEntrySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('cash_bank_entry/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_cash_bank_entry_modal').modal('hide');
              $('form#addcashBankEntryForm #addcashBankEntrySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              cashBankEntryToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                $("form#addcashBankEntryForm  #err_"+key).text(value);
              });
              $('form#addcashBankEntryForm #addcashBankEntrySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
            else
            {
              cashBankEntryToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addcashBankEntrySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addcashBankEntryForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addcashBankEntryForm #err_"+id).text(field+ " field is required.");
          $('form#addcashBankEntryForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addcashBankEntryForm #err_"+id).text("");
          $('form#addcashBankEntryForm #'+id).removeClass('is-invalid');
          $('form#addcashBankEntryForm #'+id).addClass('is-valid');
        }
    });

    // end to add cash bank entry

    //start to edit cash bank entry

    $(document).on('click','.edit_cash_bank_entry_modal',function(e){
      var cash_bank_entry_id = $(this).data('cash_bank_entry_id');
      // alert(cash_bank_entry_id);
      $.ajax({
        url: "<?php echo base_url('cash_bank_entry/edit')?>/"+cash_bank_entry_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#edit_cash_bank_entry_modal').find('.modal-content').html(data.edit_cash_bank_entry_modal_body);
          $('#edit_cash_bank_entry_modal').modal('show');
          reinitialise();   
          $('.datepicker').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
          });
        }
      });
    });

    $(document).on('hidden.bs.modal','#edit_cash_bank_entry_modal', function (e) {
      $('#edit_cash_bank_entry_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
    $(document).on('submit','#editcashBankEntryForm',function(e){
      e.preventDefault();

      $('#editcashBankEntrySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#editcashBankEntryForm').serialize();

      var isError = false;

      $('form#editcashBankEntryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editcashBankEntryForm  #err_"+id).text(field+ " field is required.");
            $('form#editcashBankEntryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editcashBankEntryForm #err_"+id).text("");
            $('form#editcashBankEntryForm #'+id).removeClass('is-invalid');
            $('form#editcashBankEntryForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#editcashBankEntrySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('cash_bank_entry/edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#edit_cash_bank_entry_modal').modal('hide');
              $('form#editcashBankEntryForm #editcashBankEntrySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              cashBankEntryToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#editcashBankEntrySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

    // end to start cash bank entry


    $(document).on('show.bs.modal','#delete_cash_bank_entry', function (e) {
      var cash_bank_entry_id = $(e.relatedTarget).data('cash_bank_entry_id');
      $('#delete_cash_bank_entry').find('#id').val(cash_bank_entry_id);

      // alert(cash_bank_entry_id);

      $.ajax({
        url: "<?php echo base_url('cash_bank_entry/cash_bank_entry_delete_confirmation')?>",
        type: "POST",
        data:{
          'cash_bank_entry_id': cash_bank_entry_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_cash_bank_entry').find('.modal-content').html(data.cash_bank_entry_delete_modal_body);
        }
      });


    });

      // Delete record with please wait text
    $(document).on('submit','#deletecashBankEntryForm',function(e){
      $('#deletecashBankEntrySubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    /*************************** End Dynamic Cash bank entry List with Datatables ****************************/

    $(document).on('change', '#voucher_type', function (e) {
      var voucher_type = $(this).val();

     
      $('#from_account_id').html('<option value="">Select</option>');
      $('#to_account_id').html('<option value="">Select</option>'); // Clear existing options in to_account_id

      $.ajax({
          async: true,
          url: "<?php echo base_url('cash_bank_entry/get_ledger_accounts') ?>/" + voucher_type,
          type: "GET",
          dataType: "JSON",
          success: function (data) {
              var from_account_records = data.from_account_records;
              var to_account_records = data.to_account_records;
              var from_account_record_type = data.from_account_record_type;
              var to_account_record_type = data.to_account_record_type;

              function populateAccountOptions(records, accountType, targetElement) {
                  for (var i = 0; i < records.length; i++) {
                      if (accountType === '<?= BANK_ACCOUNT_GROUP ?>' ||
                          accountType === '<?= CASH_GROUP ?>' ||
                          accountType === '<?= SUNDRY_CREDITORS_GROUP ?>' ||
                          accountType === '<?= SUNDRY_DEBTORS_GROUP ?>') {
                          $(targetElement).append('<option value="' + records[i].id + '">' + records[i].title + '</option>');
                      }
                  }
              }

              // Populate options based on voucher type
              if (voucher_type === '<?= VOUCHER_TYPE_BANK_RECEIPT ?>') {
                  populateAccountOptions(from_account_records, '<?= SUNDRY_DEBTORS_GROUP ?>', '#from_account_id');
                  populateAccountOptions(to_account_records, '<?= BANK_ACCOUNT_GROUP ?>', '#to_account_id');
              } else if (voucher_type === '<?= VOUCHER_TYPE_CASH_RECEIPT ?>') {
                  populateAccountOptions(from_account_records, '<?= SUNDRY_DEBTORS_GROUP ?>', '#from_account_id');
                  populateAccountOptions(to_account_records, '<?= CASH_GROUP ?>', '#to_account_id');
              } else if (voucher_type === '<?= VOUCHER_TYPE_BANK_PAYMENT ?>') {
                  populateAccountOptions(from_account_records, '<?= BANK_ACCOUNT_GROUP ?>', '#from_account_id');
                  populateAccountOptions(to_account_records, '<?= SUNDRY_CREDITORS_GROUP ?>', '#to_account_id');
              } else if (voucher_type === '<?= VOUCHER_TYPE_CASH_PAYMENT ?>') {
                  populateAccountOptions(from_account_records, '<?= CASH_GROUP ?>', '#from_account_id');
                  populateAccountOptions(to_account_records, '<?= SUNDRY_CREDITORS_GROUP ?>', '#to_account_id');
              } else if (voucher_type === '<?= VOUCHER_TYPE_CONTRA ?>') {
                  populateAccountOptions(from_account_records, '<?= BANK_ACCOUNT_GROUP ?>', '#from_account_id');
                  populateAccountOptions(to_account_records, '<?= BANK_ACCOUNT_GROUP ?>', '#to_account_id');
              }

             
          }
      });
    });



    $(document).on('change', 'form#bulkcashBankEntryForm #voucher_type', function (e) {
      var voucher_type = $(this).val();

      $('#from_account_id').html('<option value="">Select</option>');
      $('#to_account_id').html('<option value="">Select</option>'); // Clear existing options in to_account_id

      // Show Bootstrap parent modal
      // $('#parentModal').modal('show');

      Swal.fire({
        title: "Please Wait",
        showConfirmButton: false, // Do not show any buttons
        timer: 5000,
        timerProgressBar: true, // Show progress bar
        customClass: {
            popup: 'swal2-noanimation', // Disable animations for smoother loading
            content: 'custom-swal-content' // Define custom CSS class for content
        },
        allowOutsideClick: false // Prevent users from closing the modal by clicking outside
      });


      $.ajax({
          async: true,
          url: "<?php echo base_url('cash_bank_entry/get_ledger_accounts') ?>/" + voucher_type,
          type: "GET",
          dataType: "JSON",
          success: function (data) {
              var from_account_records = data.from_account_records;
              var to_account_records = data.to_account_records;
              var from_account_record_type = data.from_account_record_type;
              var to_account_record_type = data.to_account_record_type;

              function populateAccountOptions(records, accountType, targetElement) {
                  for (var i = 0; i < records.length; i++) {
                      if (accountType === '<?= BANK_ACCOUNT_GROUP ?>' ||
                          accountType === '<?= CASH_GROUP ?>' ||
                          accountType === '<?= SUNDRY_CREDITORS_GROUP ?>' ||
                          accountType === '<?= SUNDRY_DEBTORS_GROUP ?>') {
                          $(targetElement).append('<option value="' + records[i].id + '">' + records[i].title + '</option>');
                      }
                  }
              }

              if (voucher_type === '<?= VOUCHER_TYPE_BANK_RECEIPT ?>') {
                  populateAccountOptions(from_account_records, '<?= SUNDRY_DEBTORS_GROUP ?>', '#from_account_id');
                  populateAccountOptions(to_account_records, '<?= BANK_ACCOUNT_GROUP ?>', '#to_account_id');
              } else if (voucher_type === '<?= VOUCHER_TYPE_CASH_RECEIPT ?>') {
                  populateAccountOptions(from_account_records, '<?= SUNDRY_DEBTORS_GROUP ?>', '#from_account_id');
                  populateAccountOptions(to_account_records, '<?= CASH_GROUP ?>', '#to_account_id');
              } else if (voucher_type === '<?= VOUCHER_TYPE_BANK_PAYMENT ?>') {
                  populateAccountOptions(from_account_records, '<?= BANK_ACCOUNT_GROUP ?>', '#from_account_id');
                  populateAccountOptions(to_account_records, '<?= SUNDRY_CREDITORS_GROUP ?>', '#to_account_id');
              } else if (voucher_type === '<?= VOUCHER_TYPE_CASH_PAYMENT ?>') {
                  populateAccountOptions(from_account_records, '<?= CASH_GROUP ?>', '#from_account_id');
                  populateAccountOptions(to_account_records, '<?= SUNDRY_CREDITORS_GROUP ?>', '#to_account_id');
              } else if (voucher_type === '<?= VOUCHER_TYPE_CONTRA ?>') {
                  populateAccountOptions(from_account_records, '<?= BANK_ACCOUNT_GROUP ?>', '#from_account_id');
                  populateAccountOptions(to_account_records, '<?= BANK_ACCOUNT_GROUP ?>', '#to_account_id');
              }
              swal.close();

              // $('#parentModal').modal('hide');
          }
      });
    });


    $(document).on('click', ".add_cash_bank_entry" ,function(event){
      event.preventDefault();    

      if($('.no_records_row').length > 0)
      {
        $('.no_records_row').remove();
        
      }

      var cashbankrow = $('#bulk_cash_bank_entry_modal');    

      var voucher_type_text  = cashbankrow.find('select[name="voucher_type"] option:selected').text();
      var voucher_type       = cashbankrow.find('select[name="voucher_type"]').val();

      var voucher_date       = cashbankrow.find('input[name="voucher_date"]').val();

      // Get the text value of the selected "from" account
      var from_account_text  = cashbankrow.find('select[name="from_account_id"] option:selected').text();
      var from_account_id    = cashbankrow.find('select[name="from_account_id"]').val();

      var to_account_text    = cashbankrow.find('select[name="to_account_id"] option:selected').text();
      var to_account_id      = cashbankrow.find('select[name="to_account_id"]').val();

      var amount             = cashbankrow.find('input[name="amount"]').val();
      var narration          = cashbankrow.find('input[name="narration"]').val();

      if(voucher_type != '' && from_account_id != '' && to_account_id != '' && amount > 0)
      {
        var cash_bank_entry  = '<tr>'
                          +   '<td>' 
                          +      voucher_type_text
                          +      '<span class="hidden-span" name="voucher_type">'+voucher_type+'</span>'
                          +     '<input type="hidden" name="voucher_type" value="'+voucher_type+'">'
                          +   '</td>'
                          +   '<td>'
                          +     voucher_date
                          +      '<span class="hidden-span" name="voucher_date" >'+voucher_date+'</span>'
                          +     '<input type="hidden" name="voucher_date" value="'+voucher_date+'">'
                          +   '</td>'
                          +   '<td>'
                          +     from_account_text
                          +      '<span type="hidden" name="from_account_id" value="'+from_account_id+'"></span>'
                          +     '<input type="hidden" name="from_account_id" value="'+from_account_id+'">'
                          +   '</td>'
                          +   '<td>'
                          +     to_account_text
                          +      '<span type="hidden" name="to_account_id" value="'+to_account_id+'"></span>'
                          +     '<input type="hidden" name="to_account_id" value="'+to_account_id+'">'
                          +   '</td>'
                          +   '<td>'
                          +     amount
                           +      '<span class="hidden-span" name="amount">'+amount+'</span>'
                          +     '<input type="hidden" name="amount" value="'+amount+'">'
                          +   '</td>'
                          +   '<td>'
                          +     narration
                           +      '<span class="hidden-span" name="narration" >'+narration+'</span>'
                          +     '<input type="hidden" name="narration" value="'+narration+'">'
                          +   '</td>'
                          +   '<td align="center">'
                          // +     '<input type="hidden" name="raw_material_id" value="'+raw_material_id+'">'
                          // +     '<input type="hidden" name="raw_material_name" value="'+raw_material_name+'">'                          
                          // +     '<input type="hidden" name="raw_material_uom" value="'+raw_material_uom+'">'
                          +     '<a href="#" class="delete_cash_bank_entry text-danger">'
                          +       '<i class="fas fa-trash"></i>'
                          +     '</a>' 
                          +   '</td>'
                          +  '</tr>';

        $("#cash_bank_entry_table_body").append(cash_bank_entry);  

        //Clear the form fields after adding the entry
       // cashbankrow.find('select[name="voucher_type"]').val('').trigger('change');
        // cashbankrow.find('input[name="voucher_date"]').val('');
        cashbankrow.find('select[name="from_account_id"]').val('').trigger('change');
        cashbankrow.find('select[name="to_account_id"]').val('').trigger('change');
        cashbankrow.find('input[name="amount"]').val(0);
        cashbankrow.find('input[name="narration"]').val('');



        $('form#bulkcashBankEntryForm .error').text('');


      }
      else
      {
        Swal.fire({
          title: "Message",
          text: "Please select voucher type, from account, to account and amount should be greater than 0",
          // type: "warning",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          timer: 5000,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        }); 

        $('form#bulkcashBankEntryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#bulkcashBankEntryForm  #err_"+id).text(field+ " field is required.");
            $('form#bulkcashBankEntryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#bulkcashBankEntryForm #err_"+id).text("");
            $('form#bulkcashBankEntryForm #'+id).removeClass('is-invalid');
            $('form#bulkcashBankEntryForm #'+id).addClass('is-valid');
          }

      });

      }
    });

    // Find and remove table rows
    $(document).on('click', "form#bulkcashBankEntryForm .delete_cash_bank_entry" ,function(){
      $(this).parents("tr").remove();
    });

    $(document).on('submit','form#bulkcashBankEntryForm',function(e){
      e.preventDefault();

      $('#bulkcashBankEntrySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      // var isError = false;

      $('form#bulkcashBankEntryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#bulkcashBankEntryForm  #err_"+id).text(field+ " field is required.");
            $('form#bulkcashBankEntryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#bulkcashBankEntryForm #err_"+id).text("");
            $('form#bulkcashBankEntryForm #'+id).removeClass('is-invalid');
            $('form#bulkcashBankEntryForm #'+id).addClass('is-valid');
          }

      });

      var cashbankentryDataArray = [];

      $("#cash_bank_entry_table_body").find('tr').each(function () {

          var tr                         = $(this).closest("tr");
          var cashbankentryData     = {};
          
          cashbankentryData['voucher_type']     = tr.find('input[name="voucher_type"]').val();
          cashbankentryData['voucher_date']     = tr.find('span[name="voucher_date"]').text();
          cashbankentryData['from_account_id']  = tr.find('input[name="from_account_id"]').val();
          cashbankentryData['to_account_id']    = tr.find('input[name="to_account_id"]').val();
          cashbankentryData['amount']           = tr.find('span[name="amount"]').text();
          cashbankentryData['narration']        = tr.find('span[name="narration"]').text();

         
      
          cashbankentryDataArray.push(JSON.stringify(cashbankentryData));
      });

      if(cashbankentryDataArray.length > 0)
      {
        $('form#bulkcashBankEntryForm .error').text('');
        $('#bulk_cash_bank_entry_modal').find('#cash_bank_entries').val(cashbankentryDataArray.join('|'));
      }
      else
      {
        Swal.fire({
          title: "Message",
          text: "Please add atlest 1 record.",
          // type: "warning",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          timer: 5000,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        }); 
       
        $('#bulkcashBankEntrySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }

      

        var formData = $('#bulkcashBankEntryForm').serialize();
        // alert(formData);

        $.ajax({
          url: "<?php echo base_url('cash_bank_entry/bulk_entry')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#bulk_cash_bank_entry_modal').modal('hide');
              $('form#bulkcashBankEntryForm #bulkcashBankEntrySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              cashBankEntryToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#bulkcashBankEntrySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
     
    });

    $(document).on("blur change keyup", "form#bulkcashBankEntryForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#bulkcashBankEntryForm #err_"+id).text(field+ " field is required.");
          $('form#bulkcashBankEntryForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#bulkcashBankEntryForm #err_"+id).text("");
          $('form#bulkcashBankEntryForm #'+id).removeClass('is-invalid');
          $('form#bulkcashBankEntryForm #'+id).addClass('is-valid');
        }
    });



  });
</script>