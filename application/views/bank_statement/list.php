<?php $this->load->view('layout/header');?>

<style>
  /* Custom CSS for larger modal */
  .custom-modal-lg {
      max-width: 80%; /* Adjust the percentage to your desired width */
  }
  .table-container {
      max-height: 400px !important;
      overflow-y: auto !important;
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
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_bank_statement')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('bank_statement_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('bank_statement_list')?></h3>
              <?php 
                if($this->permission_model->has_permission('add_bank_statement'))
                {
              ?>
              <div class="card-tools">
                <button type="button" class="btn btn-block btn-primary btn-sm add_bank_statement_modal" data-toggle="modal" data-target="#add_bank_statement_modal" data-tt="tooltip" title="Click here to Add Bank statement" data-bank_statement_id="">Add Bank statement</button>
                <!-- <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('bank_statement/add')?>" data-tt="tooltip" title="Click here to Add Cash bank entry">
                      <i class="fas fa-cash-register mr-2"></i><?=$this->lang->line('bank_statement_add')?>
                    </a>
                  </li>
                </ul> -->
              </div>
              <?php 
                }
              ?>
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('bank_account_name')?></th>
                    <th><?=$this->lang->line('bank_account_number')?></th>
                    <th><?=$this->lang->line('bank_account_account_type')?></th>
                    <th><?=$this->lang->line('bank_account_bank_name')?></th>
                    <th><?=$this->lang->line('bank_statement')?></th>
                    <th><?=$this->lang->line('bank_reconcile_status')?></th>
                    <th width="20%"><?=$this->lang->line('bank_statement_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
              
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('bank_account_name')?></th>
                    <th><?=$this->lang->line('bank_account_number')?></th>
                    <th><?=$this->lang->line('bank_account_account_type')?></th>
                    <th><?=$this->lang->line('bank_account_bank_name')?></th>
                    <th><?=$this->lang->line('bank_statement')?></th>
                    <th><?=$this->lang->line('bank_reconcile_status')?></th>
                    <th width="20%"><?=$this->lang->line('bank_statement_action')?></th>
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
  <div class="modal fade" id="add_bank_statement_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="edit_bank_statement_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="delete_bank_statement_modal">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="reconcile_bank_statement_modal">
    <div class="modal-dialog custom-modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function(e){

    const bankStatementToast = Swal.mixin({
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
          "url": "<?php echo site_url('bank_statement/ajax_list')?>",
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
            "targets": [4,6], //first column / numbering column
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

    // start to add cash bank entry modal

    $(document).on('click', ".add_bank_statement_modal" ,function(){
      var bank_statement_id = $(this).data('bank_statement_id');

      $.ajax({
        url: "<?php echo base_url('bank_statement/add')?>/"+bank_statement_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_bank_statement_modal').find('.modal-content').html(data.add_bank_statement_modal_body);
          $('#add_bank_statement_modal').modal('show');
          reinitialise();  
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // alert(xhr.status);
          // alert(thrownError);
          // alert(ajaxOptions);
        }
      });
    });

    $(document).on('hidden.bs.modal','#add_bank_statement_modal',function(e){
      $('#add_bank_statement_modal').find('.modal-content').html('');
    });

    $(document).on('submit','#addbankStatementForm',function(e){
      
      e.preventDefault();

      $('#addbankStatementSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      var isError = false;

      $('form#addbankStatementForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          // alert(id);

          if(value==null || value==""){
            $("form#addbankStatementForm  #err_"+id).text(field+ " field is required.");
            $('form#addbankStatementForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addbankStatementForm #err_"+id).text("");
            $('form#addbankStatementForm #'+id).removeClass('is-invalid');
            $('form#addbankStatementForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addbankStatementSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        var formData = $('#addbankStatementForm').serialize();
        $.ajax({
          url: "<?php echo base_url('bank_statement/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_bank_statement_modal').modal('hide');
              $('form#addbankStatementForm #addbankStatementSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              bankStatementToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              bankStatementToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addbankStatementSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("change keyup", "form#addbankStatementForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          alert('id '+ id + ' placeholder '+value);
          $("form#addbankStatementForm #err_"+id).text(field+ " field is required.");
          $('form#addbankStatementForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addbankStatementForm #err_"+id).text("");
          $('form#addbankStatementForm #'+id).removeClass('is-invalid');
          $('form#addbankStatementForm #'+id).addClass('is-valid');
        }
    });

    $(document).on("change", "form#addbankStatementForm #upload_statement",function(e) {

      var allowedExtensions = ["xlsx"];
      var file = this.files[0];

      var fileName = file.name;
  
      // Regular expression to match only alphanumeric characters and underscores
      var regex = /^[a-zA-Z0-9_]+(\.[a-zA-Z0-9]+)?$/;

      if (!regex.test(fileName)) {
        Swal.fire({
          title: "Message",
          text: "File name should only contain alphanumeric (a-z0-9) and underscore ( _ ).",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          timer: 5000,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        });

        $(this).val(""); // Clear the file input

        return;
      }

      var fileExtension = file.name.split(".").pop().toLowerCase();

      if ($.inArray(fileExtension, allowedExtensions) == -1) {

        Swal.fire({
            title: "Message",
            text: "Only xlsx files are allowed.",
            // type: "warning",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            timer: 5000,
            customClass: {
              confirmButton: "btn btn-primary"
            }
          }); 
        
          $(this).val(""); // Clear the file input
          
          return;
      }
   
      // Get the file input element and the selected file
      var inputFile = this;
      var file = inputFile.files[0];

      // Create a new FormData object to send the file to the server
      var formData = new FormData();
      formData.append("upload_statement", file);

      // Get the bank_account_id from the hidden input field
      var bankAccountId = $('form#addbankStatementForm #bank_account_id').val();
      formData.append("bank_account_id", bankAccountId); // Add bank_account_id to FormData

      var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
      var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

      // Add the CSRF token to the form data
      formData.append(csrfTokenName, csrfTokenValue);
      

      // Perform the AJAX request to the server
      $.ajax({
        url: "<?php echo base_url('bank_statement/upload_statement')?>", // Replace with your server-side script URL
        type: "POST",
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        success: function(response) {
          if(response.code == 1)
          {
            $('form#addbankStatementForm #statement').val(response.statement);  
          }
          else
          {
            Swal.fire({
              title: "Message",
              html: response.message,
              icon: "error",
              confirmButtonText: "Ok, got it!",
              timer: 15000,
              customClass: {
                  confirmButton: "btn btn-primary"
              }
            });
          
            $('form#addbankStatementForm #upload_statement').val('');
            
            $("form#addbankStatementForm #err_upload_statement").text('Please select statement first');
            $('form#addbankStatementForm #upload_statement').addClass('is-invalid');
            $('form#addbankStatementForm #upload_statement').removeClass('is-valid');
          }
          
        },
        error: function(jqXHR, textStatus, errorThrown) {
          Swal.fire({
            title: "Message",
            html: errorThrown,
            icon: "error",
            confirmButtonText: "Ok, got it!",
            timer: 15000,
            customClass: {
                confirmButton: "btn btn-primary"
            }
          });

        }
      });
    });
    
    /*************************** End Dynamic Cash bank entry List with Datatables ****************************/

    $(document).on('shown.bs.modal','#delete_bank_statement_modal', function (e) {
      var bank_statement_id = $(e.relatedTarget).data('bank_statement_id');
      $('#delete_bank_statement_modal').find('#id').val(bank_statement_id);

      $.ajax({
        url: "<?php echo base_url('bank_statement/bank_statement_delete_confirmation')?>",
        type: "POST",
        data:{
        'bank_statement_id': bank_statement_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_bank_statement_modal').find('.modal-content').html(data.bank_statement_delete_modal_body);
        }
      });

    });

    $(document).on('submit', '#deleteBankStatementForm' ,function (e) {
      e.preventDefault();

      $('#deleteBankStatementSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteBankStatementForm').serialize();

      $.ajax({
        url: "<?php echo base_url('bank_statement/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
            $('#delete_bank_statement_modal').modal('hide');
            $('form#deleteBankStatementForm #deleteBankStatementSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            initialize_datatable();

            bankStatementToast.fire({
              type: 'success',
              title: response.message
            });
            
          }
          else
          {
            bankStatementToast.fire({
              type: 'error',
              title: response.message
            });            
          }

          $('#deleteBankStatementSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        }
      });
    });

    $(document).on('shown.bs.modal','#reconcile_bank_statement_modal', function (e) {
      var bank_statement_id = $(e.relatedTarget).data('bank_statement_id');

      $.ajax({
        url: "<?php echo base_url('bank_statement/reconcile')?>/"+bank_statement_id,
        type: "GET",
        // data:{
        
        //   '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        // },
        dataType: "JSON",
        success: function(data){
          $('#reconcile_bank_statement_modal').find('.modal-content').html(data.reconcile_bank_statement_modal_body);
        }
      });

    });

    $(document).on('click','.reconcil_entry',function(e){
      var bank_statement_entry_id = $(this).data('bank_statement_entry_id');     
      
      // Remove existing tr
      $('#reconcile_bank_statement_modal').find('.transaction_entries').remove();

      // Find the closest tr
      var closestTr = $(this).closest('tr');

      // Create a new tr with a single td and colspan 7
      var newTr = $('<tr>').append($('<td colspan="8" class="transaction_entries" style="margin:5px;"><div class="table-container">Please wait...</div></td>'));
      
      // Apply the sticky style to the <thead>
      $('#reconcile_bank_statement_modal').find('.table thead').css({
          'position': 'sticky',
          'top': 0,
          'background-color': '#fff', // Set background color if needed
          // Add any other styling as needed
      });

      // Insert the new tr after the closest tr
      closestTr.after(newTr);  

      $.ajax({
        url: "<?php echo base_url('bank_statement/reconcile_entry')?>/"+bank_statement_entry_id,
        type: "GET",
        // data:{
        
        //   '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        // },
        dataType: "JSON",
        success: function(data){
          $('#reconcile_bank_statement_modal').find('.table-container').html(data.transaction_entries_table);
          $('.datepicker').datepicker({
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
        });
        }
      });

      $(this).addClass('d-none');
      $(this).closest('tr').find('.save_reconcil_entry').removeClass('d-none');
      $(this).closest('tr').find('.cancel_reconcil_entry').removeClass('d-none');
    });
    $(document).on('click','.cancel_reconcil_entry',function(e){
      var bank_statement_entry_id = $(e.relatedTarget).data('bank_statement_entry_id');

      // Remove existing tr
      $('#reconcile_bank_statement_modal').find('.transaction_entries').remove();

      $(this).closest('tr').find('.reconcil_entry').removeClass('d-none');
      $(this).closest('tr').find('.save_reconcil_entry').addClass('d-none');
      $(this).closest('tr').find('.cancel_reconcil_entry').addClass('d-none');
    });

    var selectedTransactions = [];

    $(document).on('click', '.save_reconcil_entry', function (e) {
      var current_row = $(this).closest('tr');

      var remarks = $('#reconcile_bank_statement_modal').find('input[name="remarks"]').val();
      var reconcileDate = $('#reconcile_bank_statement_modal').find('input[name="reconcile_datetime"]').val();
      var bank_statement_entry_id = $(this).data('bank_statement_entry_id'); 

      var anyCheckboxChecked = false;

      $("#trancation_entries").find('tr').each(function () {
        var tr = $(this);
        if (tr.find('input[type="checkbox"]').is(':checked')) {
          anyCheckboxChecked = true;
          var transactionId = tr.find('input[type="checkbox"]').data('transaction_id');

          // Check if transactionId already exists in selectedTransactions array
          if (selectedTransactions.indexOf(transactionId) === -1) {
              selectedTransactions.push(transactionId);
          }

        }
      });


      if (!anyCheckboxChecked) {

        Swal.fire({
            title: 'No Transactions Selected',
            text: 'Please select at least one transaction before saving.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
      }
      else
      {
        
        $.ajax({
            url: "<?php echo base_url('bank_statement/update_reconcile')?>/"+bank_statement_entry_id, 
            type: "POST",
            data: {
                  remarks: remarks,
                  reconcileDate: reconcileDate,
                  selectedTransactions: selectedTransactions,
                  '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              },
            dataType: "json",            
            success: function(response) {
                // Handle success response
                console.log(response);

                if (response.code === 1) {

               
                  var reconcileStatusElement = current_row.find('.reconcile-status'); 

                  reconcileStatusElement.text(response.reconcile_status);

                  // Determine the class based on the reconcile status
                  var badgeClass = (response.reconcile_status == 'pending') ? 'badge badge-warning' : 'badge badge-success';
                  
                  // Remove existing badge classes and apply the new one
                  reconcileStatusElement.removeClass('badge badge-warning badge-success').addClass(badgeClass);


                  bankStatementToast.fire({
                    type: 'success',
                    title: response.message
                  });

                  $('.reconcil_entry').removeClass('d-none');
                  $('.save_reconcil_entry, .cancel_reconcil_entry').addClass('d-none');
                  current_row.next().remove();
                
                
              } else {
                  // Show error message using Swal (SweetAlert) popup
                  bankStatementToast.fire({
                  type: 'error',
                  title: response.message
                });
              }
              
            },
            error: function(xhr, status, error) {
                // Handle error
                console.error(xhr.responseText);
            }
        });
      }
    
    });

    $(document).on('click', '.single_transaction_entry', function (e) {
      var tr = $(this).closest('tr');

      if ($(this).is(':checked')) {
          anyCheckboxChecked = true;
          var transactionId = tr.find('input[type="checkbox"]').data('transaction_id');
          selectedTransactions.push(transactionId);
      }
      else
      {
        // remove transaction from array
        var index = $.inArray(transactionId, selectedTransactions);

        if (index !== -1) {
            selectedTransactions.splice(index, 1);
        }
      }
    });

    $(document).on('keyup', '#searchInput',function(){
        var searchText = $(this).val().toLowerCase(); // Get the value entered in the search input
        $('#trancation_entries_table tbody tr').each(function(){ // Loop through each table row
            var rowText = $(this).text().toLowerCase(); // Get all text within the row, converted to lowercase
            // Check if the row's text contains the search input
            if (rowText.indexOf(searchText) !== -1) {
                $(this).show(); // Show the row if it contains the search input
            } else {
                $(this).hide(); // Hide the row if it doesn't contain the search input
            }
        });
    });


  });
</script>