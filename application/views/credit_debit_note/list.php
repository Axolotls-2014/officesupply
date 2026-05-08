<?php $this->load->view('layout/header');?>

    <style>
        
    
      /* Custom CSS for larger modal */
      .custom-modal-lg {
          max-width: 80%; /* Adjust the percentage to your desired width */
      }
    
      /* Add your styling for the modal and table here */
      .table-container{
          max-height: 500px; /* Set a fixed height for the table container */
          overflow-y: auto; /* Enable vertical scrollbar for the container */
          position: relative;
      }

      table {
          width: 100%;
          border-collapse: collapse;          
      }

      th, td {
          border: 1px solid #ddd;
          padding: 8px;
          text-align: left;
      }

      thead {
          position: sticky;
          top: 0;
          background-color: #f5f5f5;
      }
      tfoot {
          position: sticky;
          bottom: 0;
          background-color: #f5f5f5;
      }
      /* Additional style for sticky tfoot */
      .sticky-footer {
          position: absolute;
          width: 100%;
          bottom: 0;
      }
    </style>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                 <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('credit_debit_note')?>"><?=$this->lang->line('header_credit_debit_note')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('cdn_add')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title"><?=$this->lang->line('header_credit_debit_note')?></h3>
                <?php 
                if($this->permission_model->has_permission('add_credit_debit_note'))
                  {
                ?>
                <div class="card-tools">
                  <button type="button" class="btn btn-block btn-primary btn-sm add_credit_debit_note_modal" data-toggle="modal" data-target="#add_credit_debit_note_modal" data-tt="tooltip" title="Click here to Add Credit Debit Note" data-cdn_id="">Add Credit Debit Note</button>
                </div>
                <?php
                  }
                ?>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><?=$this->lang->line("cdn_date")?></th>
                      <th><?=$this->lang->line("cdn_reference_no")?></th>
                      <th><?=$this->lang->line("cdn_note_type")?></th>
                      <th><?=$this->lang->line("cdn_ledger")?></th>
                      <th><?=$this->lang->line("cdn_description")?></th>
                      <th><?=$this->lang->line("cdn_taxable_amount")?></th>
                      <th><?=$this->lang->line("cdn_tax_type")?></th>
                      <th><?=$this->lang->line("cdn_igst")?></th>
                      <th><?=$this->lang->line('cdn_igst_tax')?></th>
                      <th><?=$this->lang->line("cdn_cgst")?></th>
                      <th><?=$this->lang->line('cdn_cgst_tax')?></th>
                      <th><?=$this->lang->line("cdn_sgst")?></th>
                      <th><?=$this->lang->line('cdn_sgst_tax')?></th>
                      <th><?=$this->lang->line('cdn_amount')?></th>
                      <th width="15%"><?=$this->lang->line("cdn_action")?></th>   
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
      <!-- /.row -->
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php $this->load->view('layout/footer');?>


<div class="example-modal">
  <div class="modal fade" id="add_credit_debit_note_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog custom-modal-lg" >
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
<div class="modal fade" id="edit_credit_debit_note_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog custom-modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="delete_credit_debit_note_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div>
  <div class="modal fade" id="whatsapp-modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<script type="text/javascript">

  $(document).ready(function(e){

    const creditDebitNoteToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

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
          "url": "<?php echo site_url('credit_debit_note/ajax_list')?>",
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
            "targets": [14], //first column / numbering column
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

    const whatsappToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });


    $(document).on('click','.whatsapp-modal',function(e){
      var cdn_id = $(this).data('cdn_id');
      // alert();

      $.ajax({
        url: "<?php echo base_url('credit_debit_note/whatsapp_pdf')?>/"+cdn_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
           
          $('#whatsapp-modal').find('.modal-content').html(data.whatsapp_modal_body);
          $('#whatsapp-modal').modal('show');
        },
       error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on("click","#submitPDF", function (e) {

    $('form#submitCdnPDF #submitPDF').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      var credit_note_id = $("#credit_noteId").val();

      // Get the customer's phone number
      var customerPhone = $("#phone").val().trim();

      // Get the other mobile numbers from the textarea and split them into an array
      var otherMobileNumbers = $("#other_mobile_numbers").val().trim();
      var mobileNumbersArray = otherMobileNumbers.split(',');

      // Add the customer's phone number to the array if it's not empty
      if (customerPhone !== '') {
        mobileNumbersArray.push(customerPhone);
      }

      // Prepare the mobile numbers as a comma-separated string
      var mobileNumbers = mobileNumbersArray.join(',');


      $.ajax({
        url: "<?php echo base_url('credit_debit_note/whatsapp_pdf'); ?>" ,
        type: "POST",
        data: {
          'id':credit_note_id,
          'mobile_numbers': mobileNumbers,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "json",
        success: function(response) {

          $('#submitPDF').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');

          // Close the popup
          $('#whatsapp-modal').modal('hide');
          // Show an alert on successful message delivery
          if (response.code==1) {

            whatsappToast.fire({
                type: 'success',
                title: response.message
              });
          } else {

          whatsappToast.fire({
          type: 'error',
          title: response.message
          });

          }
        },
        error: function(xhr, textStatus, errorThrown) {
        
          Swal.fire({
            title: 'FAILURE !!',
            text: "AJAX request failed. Error: " + errorThrown,
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn btn-primary"
            }
          });
        }
      });

    });

    $(document).on('click', ".add_credit_debit_note_modal" ,function(){
      var cdn_id = $(this).data('cdn_id');

      $.ajax({
        url: "<?php echo base_url('credit_debit_note/add')?>/"+cdn_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_credit_debit_note_modal').find('.modal-content').html(data.add_credit_debit_note_modal_body);
          $('#add_credit_debit_note_modal').modal('show');
          reinitialise();   
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on('hidden.bs.modal','#add_credit_debit_note_modal',function(e){
      $('#add_credit_debit_note_modal').find('.modal-content').html('');
    });


    $(document).on('submit','#addCreditDebitNoteForm',function(e){
      
      e.preventDefault();

      $('#addCreditDebitNoteSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      

      var isError = false;

      $('form#addCreditDebitNoteForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value=="" || value == 0){
            $("form#addCreditDebitNoteForm  #err_"+id).text(field+ " field is required.");
            $('form#addCreditDebitNoteForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addCreditDebitNoteForm #err_"+id).text("");
            $('form#addCreditDebitNoteForm #'+id).removeClass('is-invalid');
            $('form#addCreditDebitNoteForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addCreditDebitNoteSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {

        var creditDebitNoteItems = '';

        var populatedCreditDebitNoteItems = get_credit_note_items();

        if (populatedCreditDebitNoteItems.length > 0) {
          creditDebitNoteItems = JSON.stringify(populatedCreditDebitNoteItems);
          console.log('JSON String:', creditDebitNoteItems);

          $('form#addCreditDebitNoteForm input[name="credit_debit_note_items"]').val(creditDebitNoteItems);
        }


        var formData = $('#addCreditDebitNoteForm').serialize();
        
        $.ajax({
          url: "<?php echo base_url('credit_debit_note/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_credit_debit_note_modal').modal('hide');
              $('form#addCreditDebitNoteForm #addCreditDebitNoteSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              creditDebitNoteToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              creditDebitNoteToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addCreditDebitNoteSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addCreditDebitNoteForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addCreditDebitNoteForm #err_"+id).text(field+ " field is required.");
          $('form#addCreditDebitNoteForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addCreditDebitNoteForm #err_"+id).text("");
          $('form#addCreditDebitNoteForm #'+id).removeClass('is-invalid');
          $('form#addCreditDebitNoteForm #'+id).addClass('is-valid');
        }
    });

    $(document).on('click','.edit_credit_debit_note_modal',function(e){
      var cdn_id = $(this).data('cdn_id');
      // alert(cdn_id);
      $.ajax({
        url: "<?php echo base_url('credit_debit_note/edit')?>/"+cdn_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#edit_credit_debit_note_modal').find('.modal-content').html(data.edit_credit_debit_note_modal_body);
          $('#edit_credit_debit_note_modal').modal('show');
          set_max_attribute_to_new_price();
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

    $(document).on('hidden.bs.modal','#edit_credit_debit_note_modal', function (e) {
      $('#edit_credit_debit_note_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
    $(document).on('submit','#editCreditDebitNoteForm',function(e){
      e.preventDefault();

      $('#editCreditDebitNoteSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      

      var isError = false;

      $('form#editCreditDebitNoteForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value=="" || value == 0){
            $("form#editCreditDebitNoteForm  #err_"+id).text(field+ " field is required.");
            $('form#editCreditDebitNoteForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editCreditDebitNoteForm #err_"+id).text("");
            $('form#editCreditDebitNoteForm #'+id).removeClass('is-invalid');
            $('form#editCreditDebitNoteForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#editCreditDebitNoteSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        var creditDebitNoteItems = '';

        var populatedCreditDebitNoteItems = get_credit_note_items();

        if (populatedCreditDebitNoteItems.length > 0) {
          creditDebitNoteItems = JSON.stringify(populatedCreditDebitNoteItems);
          console.log('JSON String:', creditDebitNoteItems);

          $('form#editCreditDebitNoteForm input[name="credit_debit_note_items"]').val(creditDebitNoteItems);
        }

        var formData = $('#editCreditDebitNoteForm').serialize();

        $.ajax({
          url: "<?php echo base_url('credit_debit_note/edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#edit_credit_debit_note_modal').modal('hide');
              $('form#editCreditDebitNoteForm #editCreditDebitNoteSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              creditDebitNoteToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#editCreditDebitNoteSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on('shown.bs.modal','#delete_credit_debit_note_modal', function (e) {
      var cdn_id = $(e.relatedTarget).data('cdn_id');
      $('#delete_credit_debit_note_modal').find('#id').val(cdn_id);

      $.ajax({
        url: "<?php echo base_url('credit_debit_note/credit_debit_note_delete_confirmation')?>",
        type: "POST",
        data:{
          'cdn_id': cdn_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_credit_debit_note_modal').find('.modal-content').html(data.delete_credit_debit_note_modal_body);
        }
      });
    });

    $(document).on('hidden.bs.modal','#delete_credit_debit_note_modal', function (e) {
      $('#delete_credit_debit_note_modal').find('.modal-content').html("");
    });

    $(document).on('submit', '#deleteCreditDebitNoteForm' ,function (e) {
      e.preventDefault();

      $('#deleteCreditDebitNoteSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteCreditDebitNoteForm').serialize();

      $.ajax({
      url: "<?php echo base_url('credit_debit_note/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
            $('#delete_credit_debit_note_modal').modal('hide');
            $('form#deleteCreditDebitNoteForm #deleteCreditDebitNoteSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            initialize_datatable();

            creditDebitNoteToast.fire({
              type: 'success',
              title: response.message
            });
          }
          else
          {
            creditDebitNoteToast.fire({
              type: 'error',
              title: response.message
            });            
          }

          $('#deleteCreditDebitNoteSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        }
      });
    });

    $(document).on('change keyup', '#cdn_tax_id, #cdn_taxable_amount, #cdn_ledger_id, #cdn_tax_type' ,function (e) {

      var taxable_amount   = parseFloat($('#cdn_taxable_amount').val());
      var tax_id           = $('#cdn_tax_id').find('option:selected').val();
      var company_state_id = $('#company_state_id').val();
      var ledger_state_id  = $('#cdn_ledger_id').find('option:selected').data('state_id');
      var tax_type         = $('#cdn_tax_type').val();
      var total_amount     = 0;

      if(tax_id != '')
      {
        var igst    = parseFloat($('#cdn_tax_id').find('option:selected').data('igst'));
        var cgst    = parseFloat($('#cdn_tax_id').find('option:selected').data('cgst'));
        var sgst    = parseFloat($('#cdn_tax_id').find('option:selected').data('sgst'));

        var igst_tax    = 0;
        var cgst_tax    = 0;
        var sgst_tax    = 0;

        if(tax_type == '<?=CREDIT_NOTE_TAX_EXCLUSIVE?>')
        {
          var igst_tax    = parseFloat((taxable_amount * igst)/100);
          var cgst_tax    = parseFloat((taxable_amount * cgst)/100);
          var sgst_tax    = parseFloat((taxable_amount * sgst)/100);  
        }
        else
        {
          var tax_rate    = igst + cgst + sgst;

          var tax_amount    = parseFloat((taxable_amount * tax_rate) / (100 + tax_rate));

          if(igst > 0)
          {
            igst_tax = tax_amount;
          }
          else
          {
            cgst_tax = tax_amount/2;
            sgst_tax = tax_amount/2;
          }
        }

        if(ledger_state_id == company_state_id)
        {
          $('#cdn_cgst_tax').parent().find('#cdn_cgst').val(cgst);
          $('#cdn_cgst_tax').parent().find('button').text(cgst);
          $('#cdn_cgst_tax').val(cgst_tax.toFixed(2));
          
          $('#cdn_sgst_tax').parent().find('#cdn_sgst').val(sgst);  
          $('#cdn_sgst_tax').parent().find('button').text(sgst);
          $('#cdn_sgst_tax').val(sgst_tax.toFixed(2));

          $('#cdn_igst_tax').parent().find('#cdn_igst').val(0);
          $('#cdn_igst_tax').parent().find('button').text(0);
          $('#cdn_igst_tax').val(0);

          total_amount += taxable_amount+cgst_tax+sgst_tax;
        }
        else
        {
          $('#cdn_cgst_tax').parent().find('#cdn_cgst').val(0);
          $('#cdn_cgst_tax').parent().find('button').text(0);
          $('#cdn_cgst_tax').val(0);
          
          $('#cdn_sgst_tax').parent().find('#cdn_sgst').val(0);  
          $('#cdn_sgst_tax').parent().find('button').text(0);
          $('#cdn_sgst_tax').val(0);

          $('#cdn_igst_tax').parent().find('#cdn_igst').val(igst);
          $('#cdn_igst_tax').parent().find('button').text(igst);
          $('#cdn_igst_tax').val(igst_tax.toFixed(2));

          total_amount += taxable_amount+igst_tax;
        }  

        var total_amount = (tax_type == '<?=CREDIT_NOTE_TAX_EXCLUSIVE?>') ? total_amount : taxable_amount;
        $('#cdn_amount').val(total_amount.toFixed(2));
      }
      else
      {
        $('#add_credit_debit_note_modal').find('.table-container tbody').html('<tr><td colspan="13">No records are not selected.</td></tr>')

        $('#cdn_tax_id').closest('label').removeClass('required');
        $('#cdn_tax_id').removeClass('field_validation');

        var show_sale_product_entries     = $('#show_sale_product_entries');
        var show_purchase_product_entries = $('#show_purchase_product_entries');

        if(!(!(show_sale_product_entries.checked) && !(show_purchase_product_entries.checked)))
        {
          $('#cdn_tax_id').closest('.row').addClass('d-none');
        }
        
        $('#cdn_taxable_amount').removeAttr('readonly');

        $('#cdn_igst').closest('.input-group-append').removeClass('d-none');
        $('#cdn_sgst').closest('.input-group-append').removeClass('d-none');
        $('#cdn_cgst').closest('.input-group-append').removeClass('d-none');
      }
    });

    $(document).on('change','#cdn_note_type',function(e){
      var cdn_note_type = $(this).val();

      $('#cdn_ledger_id').html('<option value="">Select</option>');
      
      $.ajax({
        async:false,
        url: "<?php echo base_url('credit_debit_note/get_ledger_accounts') ?>/"+cdn_note_type,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          var records     = data.records;
          var record_type = data.record_type;

          for(i=0;i<records.length;i++)
          {
            if(record_type === '<?=SUNDRY_CREDITORS_GROUP?>')
            {
              $('#cdn_ledger_id').append('<option value="' + records[i].ledger_id + '" data-country_id="'+records[i].country_id+'" data-state_id="'+records[i].state_id+'">' + records[i].company_name + ' - ' + records[i].state_name +  '</option>');  
            }
            else
            {
              $('#cdn_ledger_id').append('<option value="' + records[i].ledger_id + '" data-country_id="'+records[i].shipping_country_id+'" data-state_id="'+records[i].shipping_state_id+'">' + records[i].customer_name + ' - ' + records[i].state_name + '</option>');   
            }
          }
        }
      });

      if(cdn_note_type == '<?=CREDIT_NOTE_TYPE_ADVANCE_REFUND_VOUCHER?>')
      {
        $('#cdn_tax_id option').filter(function() {
          return $(this).data('igst') == '18.00';
        }).prop('selected', true); 

        $('#cdn_tax_id').trigger('change');
        var show_sale_product_entries     = $('#show_sale_product_entries');
        var show_purchase_product_entries = $('#show_purchase_product_entries');

        if(!(!(show_sale_product_entries.checked) && !(show_purchase_product_entries.checked)))
        {
          $('#cdn_tax_id').closest('.row').addClass('d-none');
        }
      }
      else
      {
        $('#cdn_tax_id').closest('.row').removeClass('d-none');
      }

      $('#cdn_ledger_id').trigger('change');
    });

    $(document).on('change','#cdn_ledger_id',function(e){
      var cdn_note_type = $('#cdn_note_type').val();
      var cdn_ledger_id = $('#cdn_ledger_id').val();

      var cdn_purchase_ids_el = $('#cdn_purchase_ids');
      var cdn_sale_ids_el     = $('#cdn_sale_ids');

      var cdn_purchase_return_ids_el = $('#cdn_purchase_return_ids');
      var cdn_sale_return_ids_el     = $('#cdn_sale_return_ids');
      
      cdn_purchase_ids_el.closest('.row').not('.d-none').addClass('d-none');
      cdn_purchase_ids_el.html('<option value="">Select</option>');

      cdn_purchase_return_ids_el.closest('.row').not('.d-none').addClass('d-none');
      cdn_purchase_return_ids_el.html('<option value="">Select</option>');

      cdn_sale_ids_el.closest('.row').not('.d-none').addClass('d-none');
      cdn_sale_ids_el.html('<option value="">Select</option>');

      cdn_sale_return_ids_el.closest('.row').not('.d-none').addClass('d-none');
      cdn_sale_return_ids_el.html('<option value="">Select</option>');

      if(cdn_ledger_id != '')
      {
        $.ajax({

          async:false,
          url: "<?php echo base_url('credit_debit_note/get_sale_purchase_records') ?>/"+cdn_note_type,
          type: "POST",
          data: {
            'cdn_ledger_id' : cdn_ledger_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){
            
            var note_type = data.note_type;
            var records   = null;

            if(note_type === '<?=CREDIT_NOTE_TYPE_DEBIT?>')
            {
              records = data.purchases;
              cdn_purchase_ids_el.closest('.row').toggleClass('d-none', false);
              for(i=0;i<records.length;i++)
              {
                cdn_purchase_ids_el.append('<option value="' + records[i].id + '">' + records[i].reference_no + '</option>');  
              }

            }

            else if(note_type === '<?=CREDIT_NOTE_TYPE_CREDIT_SUPPLIER?>')
            {
              records = data.purchases;
              purchase_return_records = data.purchase_return;

              cdn_purchase_ids_el.closest('.row').toggleClass('d-none', false);
              for(i=0;i<records.length;i++)
              {
                cdn_purchase_ids_el.append('<option value="' + records[i].id + '">' + records[i].reference_no + '</option>');  
              }

              cdn_purchase_return_ids_el.closest('.row').toggleClass('d-none', false);
              for(i=0;i<purchase_return_records.length;i++)
              {
                cdn_purchase_return_ids_el.append('<option value="' + purchase_return_records[i].id + '">' + purchase_return_records[i].reference_no + '</option>');  
              }

            }

            else if(note_type === '<?=CREDIT_NOTE_TYPE_DEBIT_CUSTOMER?>')
            {
              records = data.sales;
              sales_return_records = data.sales_return;

              cdn_sale_ids_el.closest('.row').toggleClass('d-none', false);
              for(i=0;i<records.length;i++)
              {
                cdn_sale_ids_el.append('<option value="' + records[i].id + '">' + records[i].reference_no + '</option>');  
              }

              cdn_sale_return_ids_el.closest('.row').toggleClass('d-none', false);
              for(i=0;i<sales_return_records.length;i++)
              {
                cdn_sale_return_ids_el.append('<option value="' + sales_return_records[i].id + '">' + sales_return_records[i].reference_no + '</option>');  
              }
             
            }


            

            else
            {
              records = data.sales;
              cdn_sale_ids_el.closest('.row').toggleClass('d-none', false);
              for(i=0;i<records.length;i++)
              {
                cdn_sale_ids_el.append('<option value="' + records[i].id + '">' + records[i].reference_no + '</option>');  
              }
             
            }
            
                
          }
        });
      }

      
    });

    $(document).on('change', 'form#addCreditDebitNoteForm #cdn_document_upload', function(e){
     
      var allowedExtensions = ["pdf"];
      var file = this.files[0];
      var fileExtension = file.name.split(".").pop().toLowerCase();

      var errorSpan = $('#fileTypeError');

      if ($.inArray(fileExtension, allowedExtensions) === -1) {
          errorSpan.text("Only PDF file are allowed.");
         $(this).val(""); // Clear the file input
         return;
       }
        // Get the file input element and the selected file
        var inputFile = this;
        var file = inputFile.files[0];

        // Create a new FormData object to send the file to the server
        var formData = new FormData();
        formData.append("cdn_document_upload", file);

        var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
        var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

        // Add the CSRF token to the form data
        formData.append(csrfTokenName, csrfTokenValue);

        // Perform the AJAX request to the server
        $.ajax({
          url: "<?php echo base_url('credit_debit_note/upload_document')?>", // Replace with your server-side script URL
          type: "POST",
          data: formData,
          dataType: "JSON",
          contentType: false,
          processData: false,
          success: function(response) {

          
            $('form#addCreditDebitNoteForm #cdn_document').val(response.filename);

         
            // Handle the server response (if needed)
          },
          error: function(jqXHR, textStatus, errorThrown) {
            // Handle the error (if needed)
            alert("Error uploading image: " + errorThrown);
            //$("#displayImage").hide();
          }
        });

    });

    
    $(document).on('change', 'form#editCreditDebitNoteForm #cdn_document_upload', function(e){
     
     var allowedExtensions = ["pdf"];
     var file = this.files[0];
     var fileExtension = file.name.split(".").pop().toLowerCase();
     var errorSpan = $('#fileTypeError');

     if ($.inArray(fileExtension, allowedExtensions) === -1) {
          errorSpan.text("Only PDF file are allowed.");
         $(this).val(""); // Clear the file input
         return;
       }
       // Get the file input element and the selected file
       var inputFile = this;
       var file = inputFile.files[0];

       // Create a new FormData object to send the file to the server
       var formData = new FormData();
       formData.append("cdn_document_upload", file);

       var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
       var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

       // Add the CSRF token to the form data
       formData.append(csrfTokenName, csrfTokenValue);

       // Perform the AJAX request to the server
       $.ajax({
         url: "<?php echo base_url('credit_debit_note/upload_document')?>", // Replace with your server-side script URL
         type: "POST",
         data: formData,
         dataType: "JSON",
         contentType: false,
         processData: false,
         success: function(response) {

         
           $('form#editCreditDebitNoteForm #cdn_document').val(response.filename);

        
           // Handle the server response (if needed)
         },
         error: function(jqXHR, textStatus, errorThrown) {
           // Handle the error (if needed)
           alert("Error uploading image: " + errorThrown);
           //$("#displayImage").hide();
         }
       });

    });

    
   // For addCreditDebitNoteForm
    $(document).off('input', 'form#addCreditDebitNoteForm #cdn_taxable_amount').on('input', 'form#addCreditDebitNoteForm #cdn_taxable_amount', function() {
        var input = $(this).val();
        if (input.length > 9) {
            $(this).val(input.slice(0, 9)); // Truncate input to 9 characters
        }
    });

    // For editCreditDebitNoteForm
    $(document).off('input', 'form#editCreditDebitNoteForm #cdn_taxable_amount').on('input', 'form#editCreditDebitNoteForm #cdn_taxable_amount', function() {
        var input = $(this).val();
        if (input.length > 9) {
            $(this).val(input.slice(0, 9)); // Truncate input to 9 characters
        }
    });


    $(document).on('change','#show_sale_product_entries',function () {
      // Check if the checkbox is checked
      if (this.checked) 
      {
        if ($('#cdn_sale_ids option:selected').length > 0) 
        {
          var sale_ids_arr = $('#cdn_sale_ids').val();
          var sale_ids_str = sale_ids_arr.join(',');

          $('#cdn_tax_id').closest('label').removeClass('required');
          $('#cdn_tax_id').removeClass('field_validation');
          $('#cdn_tax_id').closest('.row').addClass('d-none');

          $('#cdn_tax_type').val('<?=CREDIT_NOTE_TAX_EXCLUSIVE?>').trigger('change');

          $('#cdn_taxable_amount').attr('readonly','readonly');

          $('#cdn_igst').closest('.input-group-append').addClass('d-none');
          $('#cdn_sgst').closest('.input-group-append').addClass('d-none');
          $('#cdn_cgst').closest('.input-group-append').addClass('d-none');

          $.ajax({
            url: "<?php echo base_url('credit_debit_note/get_sale_items')?>", // Replace with your server-side script URL
            type: "POST",
            data:{
              'sale_ids' : sale_ids_str,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            dataType: "JSON",
            success: function(data) {
              $('.table-container').html(data.credit_debit_note_items);
              set_max_attribute_to_new_price();
            },
            error: function(jqXHR, textStatus, errorThrown) {
              // alert("Error uploading image: " + errorThrown);
              console.log(errorThrown);
            }
          });
          
        }
        else
        {

          $('#cdn_tax_id').closest('label').addClass('required');
          $('#cdn_tax_id').addClass('field_validation');
          $('#cdn_tax_id').closest('.row').removeClass('d-none');

          $('#cdn_tax_type').val('').trigger('change');
          $('#cdn_taxable_amount').removeAttr('readonly');
          $('#cdn_taxable_amount').val(0);
          
          $('#cdn_igst').closest('.input-group-append').removeClass('d-none');
          $('#cdn_sgst').closest('.input-group-append').removeClass('d-none');
          $('#cdn_cgst').closest('.input-group-append').removeClass('d-none');

          $('#cdn_igst').val(0);
          $('#cdn_sgst').val(0);
          $('#cdn_cgst').val(0);

          $('#cdn_igst_tax').val(0);
          $('#cdn_sgst_tax').val(0);
          $('#cdn_cgst_tax').val(0);

          $('#cdn_amount').val(0);


          $(this).prop('checked', false);

          Swal.fire({
            title: 'Warning',
            text: 'Please select at least one option.',
            icon: "warning",
            buttonsStyling: false,
            confirmButtonText: "Ok, Got it!",
            timer: 3000,
            customClass: {
              confirmButton: "btn btn-primary"
            }
          });
        }
      }
      else
      {
        $('.table-container tbody').html('<tr><td colspan="13">No records are not selected.</td></tr>')

        $('#cdn_tax_id').closest('label').addClass('required');
        $('#cdn_tax_id').addClass('field_validation');
        $('#cdn_tax_id').closest('.row').removeClass('d-none');

        $('#cdn_tax_type').val('').trigger('change');
        $('#cdn_taxable_amount').removeAttr('readonly');
        $('#cdn_taxable_amount').val(0);

        $('#cdn_igst').closest('.input-group-append').removeClass('d-none');
        $('#cdn_sgst').closest('.input-group-append').removeClass('d-none');
        $('#cdn_cgst').closest('.input-group-append').removeClass('d-none');

        $('#cdn_igst').val(0);
        $('#cdn_sgst').val(0);
        $('#cdn_cgst').val(0);

        $('#cdn_igst_tax').val(0);
        $('#cdn_sgst_tax').val(0);
        $('#cdn_cgst_tax').val(0);

        $('#cdn_amount').val(0);
      }
    });

    $(document).on('change','#show_purchase_product_entries',function () {
      // Check if the checkbox is checked
      if (this.checked) 
      {
        if ($('#cdn_purchase_ids option:selected').length > 0) 
        {
          var purchase_ids_arr = $('#cdn_purchase_ids').val();
          var purchase_ids_str = purchase_ids_arr.join(',');

          $('#cdn_tax_id').closest('label').removeClass('required');
          $('#cdn_tax_id').removeClass('field_validation');
          $('#cdn_tax_id').closest('.row').addClass('d-none');

          $('#cdn_tax_type').val('<?=CREDIT_NOTE_TAX_EXCLUSIVE?>').trigger('change');

          $('#cdn_taxable_amount').attr('readonly','readonly');

          $('#cdn_igst').closest('.input-group-append').addClass('d-none');
          $('#cdn_sgst').closest('.input-group-append').addClass('d-none');
          $('#cdn_cgst').closest('.input-group-append').addClass('d-none');

          $.ajax({
            url: "<?php echo base_url('credit_debit_note/get_purchase_items')?>", // Replace with your server-side script URL
            type: "POST",
            data:{
              'purchase_ids' : purchase_ids_str,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
            dataType: "JSON",
            success: function(data) {
              $('.table-container').html(data.credit_debit_note_items);
              set_max_attribute_to_new_price();
            },
            error: function(jqXHR, textStatus, errorThrown) {
              
              alert("Error uploading image: " + errorThrown);
              //$("#displayImage").hide();
            }
          });
        }
        else
        {

          $('#cdn_tax_id').closest('label').addClass('required');
          $('#cdn_tax_id').addClass('field_validation');
          $('#cdn_tax_id').closest('.row').removeClass('d-none');

          $('#cdn_tax_type').val('').trigger('change');
          $('#cdn_taxable_amount').removeAttr('readonly');
          $('#cdn_taxable_amount').val(0);
          
          $('#cdn_igst').closest('.input-group-append').removeClass('d-none');
          $('#cdn_sgst').closest('.input-group-append').removeClass('d-none');
          $('#cdn_cgst').closest('.input-group-append').removeClass('d-none');

          $('#cdn_igst').val(0);
          $('#cdn_sgst').val(0);
          $('#cdn_cgst').val(0);

          $('#cdn_igst_tax').val(0);
          $('#cdn_sgst_tax').val(0);
          $('#cdn_cgst_tax').val(0);

          $('#cdn_amount').val(0);


          $(this).prop('checked', false);

          Swal.fire({
            title: 'Warning',
            text: 'Please select at least one option.',
            icon: "warning",
            buttonsStyling: false,
            confirmButtonText: "Ok, Got it!",
            timer: 3000,
            customClass: {
              confirmButton: "btn btn-primary"
            }
          });
        }
      }
      else
      {
        $('.table-container tbody').html('<tr><td colspan="13">No records are not selected.</td></tr>')

        $('#cdn_tax_id').closest('label').addClass('required');
        $('#cdn_tax_id').addClass('field_validation');
        $('#cdn_tax_id').closest('.row').removeClass('d-none');

        $('#cdn_tax_type').val('').trigger('change');
        $('#cdn_taxable_amount').removeAttr('readonly');
        $('#cdn_taxable_amount').val(0);

        $('#cdn_igst').closest('.input-group-append').removeClass('d-none');
        $('#cdn_sgst').closest('.input-group-append').removeClass('d-none');
        $('#cdn_cgst').closest('.input-group-append').removeClass('d-none');

        $('#cdn_igst').val(0);
        $('#cdn_sgst').val(0);
        $('#cdn_cgst').val(0);

        $('#cdn_igst_tax').val(0);
        $('#cdn_sgst_tax').val(0);
        $('#cdn_cgst_tax').val(0);

        $('#cdn_amount').val(0);
      }
    });

    function set_max_attribute_to_new_price()
    {
      $(".new_price").each(function() {

        var cdn_note_type = $('#cdn_note_type').val();
        var old_price     = parseFloat($(this).closest('tr').find('span[name="old_price"]').text());

        if(cdn_note_type == '<?=CREDIT_NOTE_TYPE_CREDIT?>')
          $(this).attr("max", old_price);
        else if(cdn_note_type == '<?=CREDIT_NOTE_TYPE_DEBIT?>')
          $(this).attr("max", old_price);
        else if(cdn_note_type == '<?=CREDIT_NOTE_TYPE_CREDIT_SUPPLIER?>')
          $(this).attr("min", old_price);
        else if(cdn_note_type == '<?=CREDIT_NOTE_TYPE_DEBIT_CUSTOMER?>')
          $(this).attr("min", old_price);
        
      });
    }

    $(document).on("change keyup", ".new_price", function() {
      updateTotals();

      var cdn_note_type = $('#cdn_note_type').val();

      var tr = $(this).closest('tr');
      var old_price = parseFloat(tr.find('span[name="old_price"]').text());

      var total_new_taxable_value = parseFloat($('#credit_debit_note_items_table').find("span[name='total_new_taxable_value']").text());
      var total_old_taxable_value = parseFloat($('#credit_debit_note_items_table').find("span[name='total_old_taxable_value']").text());
      var updated_cdn_taxable_amount = 0;

      
      if(cdn_note_type == "<?=CREDIT_NOTE_TYPE_CREDIT?>"){
        $(this).attr("max", old_price);
        updated_cdn_taxable_amount = total_old_taxable_value-total_new_taxable_value;
      }
      else if(cdn_note_type == "<?=CREDIT_NOTE_TYPE_DEBIT?>"){
        $(this).attr("max", old_price);
        updated_cdn_taxable_amount = total_old_taxable_value-total_new_taxable_value;
      }
      else if(cdn_note_type == "<?=CREDIT_NOTE_TYPE_CREDIT_SUPPLIER?>"){
        $(this).attr("min", old_price);
        updated_cdn_taxable_amount = total_new_taxable_value-total_old_taxable_value;
        
      }
      else if(cdn_note_type == "<?=CREDIT_NOTE_TYPE_DEBIT_CUSTOMER?>"){
        $(this).attr("min", old_price);
        updated_cdn_taxable_amount = total_new_taxable_value-total_old_taxable_value;
      }

      // update cdn taxable amount
      $('#cdn_taxable_amount').val(updated_cdn_taxable_amount.toFixed(2));

      var company_state_id = $('#company_state_id').val();
      var ledger_state_id  = $('#cdn_ledger_id').find('option:selected').data('state_id');
      var new_total_tax    = parseFloat($('span[name="total_new_tax"]').text());
      var old_total_tax    = parseFloat($('span[name="total_old_tax"]').text());

      var updated_tax = 0;

      if(cdn_note_type == "<?=CREDIT_NOTE_TYPE_CREDIT?>")
      {
        updated_tax = old_total_tax-new_total_tax;
      }
      else if(cdn_note_type == "<?=CREDIT_NOTE_TYPE_DEBIT?>")
      {
        updated_tax = old_total_tax-new_total_tax;
      }
      else if(cdn_note_type == "<?=CREDIT_NOTE_TYPE_CREDIT_SUPPLIER?>")
      {
        updated_tax = new_total_tax-old_total_tax;
      }
      else if(cdn_note_type == "<?=CREDIT_NOTE_TYPE_DEBIT_CUSTOMER?>")
      {
        updated_tax = new_total_tax-old_total_tax;        
      }

      var igst_tax = 0;
      var cgst_tax = 0;
      var sgst_tax = 0;

      if(company_state_id == ledger_state_id)
      {
        cgst_tax = (updated_tax/2).toFixed(2);
        sgst_tax = (updated_tax/2).toFixed(2);
      }
      else
      {
        igst_tax = (updated_tax).toFixed(2);
      }

      $('#cdn_igst_tax').val(igst_tax);
      $('#cdn_cgst_tax').val(cgst_tax);
      $('#cdn_sgst_tax').val(sgst_tax);


      $('#cdn_amount').val((updated_cdn_taxable_amount+updated_tax).toFixed(2));

      validate_price(this);

    });

    function updateTotals()
    {
      var total_new_taxable_value = 0;
      var total_new_tax = 0;
      var total_new_subtotal = 0;

      var total_old_taxable_value = 0;
      var total_old_tax = 0;
      var total_old_subtotal = 0;

      // Loop through each new_price input field
      $(".new_price").each(function() {
          var new_price = parseFloat($(this).val());
          var tr        = $(this).closest('tr');
          var igst      = tr.find('input[name="r_igst"]').val();
          var sgst      = tr.find('input[name="r_sgst"]').val();
          var cgst      = tr.find('input[name="r_cgst"]').val();
          var quantity  = tr.find('input[name="quantity"]').val();

          var t_new_taxable_value = (new_price * quantity);
          total_new_taxable_value += t_new_taxable_value; // You may need to adjust this based on your actual logic

          var igst_tax = parseFloat((t_new_taxable_value * igst)/100);
          var cgst_tax = parseFloat((t_new_taxable_value * cgst)/100);
          var sgst_tax = parseFloat((t_new_taxable_value * sgst)/100);

          var t_new_tax = igst_tax + cgst_tax + sgst_tax;
          var t_new_sub_total = t_new_tax + t_new_taxable_value;

          total_new_tax       += t_new_tax;
          total_new_subtotal  += t_new_sub_total;

          

          tr.find("span[name='new_taxable_value']").text(t_new_taxable_value.toFixed(2));
          tr.find("span[name='new_tax']").text(t_new_tax.toFixed(2));
          tr.find("span[name='new_sub_total']").text(t_new_sub_total.toFixed(2));

          total_old_taxable_value += parseFloat(tr.find('span[name="old_taxable_value"]').text());
          total_old_tax           += parseFloat(tr.find('span[name="old_tax"]').text());
          total_old_subtotal      += parseFloat(tr.find('span[name="old_sub_total"]').text());
          
      });

      // Update the corresponding total spans in the footer
      $("span[name='total_new_taxable_value']").text(total_new_taxable_value.toFixed(2));
      $("span[name='total_new_tax']").text(total_new_tax.toFixed(2));
      $("span[name='total_new_subtotal']").text(total_new_subtotal.toFixed(2));

      $("span[name='total_old_taxable_value']").text(total_old_taxable_value.toFixed(2));
      $("span[name='total_old_tax']").text(total_old_tax.toFixed(2));
      $("span[name='total_old_subtotal']").text(total_old_subtotal.toFixed(2));
    }

    function validate_price(new_price_el)
    {
      var inputValue  = parseFloat($(new_price_el).val());
      var hasMin      = $(new_price_el).attr("min");
      var hasMax      = $(new_price_el).attr("max");

      // Reset border color to normal
      $(new_price_el).css("border-color", "");

      if (hasMin !== undefined) {
          var minVal = parseFloat(hasMin);
          if (inputValue < minVal) {
              // If entered value is not greater than min, set border color to red
              $(new_price_el).css("border-color", "red");
              return; // Exit function to avoid checking max condition if min condition is violated
          }
      }

      if (hasMax !== undefined) {
          var maxVal = parseFloat(hasMax);
          if (inputValue > maxVal) {
              // If entered value is not less than max, set border color to red
              $(new_price_el).css("border-color", "red");
              return; // Exit function if max condition is violated
          }
      }
    }

    function get_credit_note_items() 
    {
      var creditDebitNoteItems = [];

      $("#credit_debit_note_items_tbody tr").each(function(index, row) {
          // Check if any td within the tr has colspan greater than 1
          if ($(row).find('td[colspan]').length > 0) {
              // If found, return an empty array
              creditDebitNoteItems = [];
              return false; // Exit the loop
          }

          var creditDebitNoteItem = {
              entry_id: $(row).find('input[name="entry_id"]').val(),
              entry_item_id: $(row).find('input[name="entry_item_id"]').val(),
              entry_type: $(row).find('input[name="entry_type"]').val(), // You need to set this value based on the entry type (sale/purchase)
              reference_no: $(row).find('span[name="reference_no"]').text(),
              product_name: $(row).find('span[name="product_name"]').text(),
              tax_rate: parseFloat($(row).find('span[name="tax_rate"]').text()),
              //quantity: parseFloat($(row).find('span[name="quantity"]').text()),
              quantity: parseFloat($(row).find('input[name="quantity"]').val()),
              old_price: parseFloat($(row).find('span[name="old_price"]').text()),
              old_taxable_value: parseFloat($(row).find('span[name="old_taxable_value"]').text()),
              r_igst: parseFloat($(row).find('input[name="r_igst"]').val()),
              r_cgst: parseFloat($(row).find('input[name="r_cgst"]').val()),
              r_sgst: parseFloat($(row).find('input[name="r_sgst"]').val()),
              old_tax: parseFloat($(row).find('span[name="old_tax"]').text()),
              old_sub_total: parseFloat($(row).find('span[name="old_sub_total"]').text()),
              new_price: parseFloat($(row).find('input[name="new_price"]').val()),
              new_taxable_value: parseFloat($(row).find('span[name="new_taxable_value"]').text()),
              new_tax: parseFloat($(row).find('span[name="new_tax"]').text()),
              new_sub_total: parseFloat($(row).find('span[name="new_sub_total"]').text())
          };

          creditDebitNoteItems.push(creditDebitNoteItem);
      });

      return creditDebitNoteItems;
    }

    var prevSelectedValues = [];

    $(document).on('change','#cdn_sale_ids', function () {
        var currentSelectedValues = $(this).val();

        // remove item entries from list whichever option is removed from selection
        var removedValue = getRemovedValues(prevSelectedValues, currentSelectedValues);

        if(removedValue != '')
        {
          removeItemsEntries(removedValue);
        }
        
        // Update previous selected values for the next change event
        prevSelectedValues = currentSelectedValues;
    });

    function getRemovedValues(prevValues, currentValues) 
    {
      return prevValues.filter(value => !currentValues.includes(value));
    }

    function removeItemsEntries(removedValue)
    {
      // Check if the last row is being removed
      var isLastRow = false;

      $("#credit_debit_note_items_tbody tr").each(function () {
          var currentEntryId = $(this).find("input[name='entry_id']").val();

          if (currentEntryId == removedValue) {
              // Check if it's the last row
              isLastRow = $(this).is(':last-child');
              
              $(this).remove();
          }
      });

      // If the last row was removed, create a new row
      if (isLastRow) {
          $("#credit_debit_note_items_tbody").append('<tr><td colspan="13">No records are not selected.</td></tr>');
          
          var visibleCheckbox = $(".form-group.row:not(.d-none) .custom-switch :checkbox");
          visibleCheckbox.prop("checked", false);

          $('span[name="total_old_taxable_value"]').text(0);
          $('span[name="total_old_tax"]').text(0);
          $('span[name="total_old_subtotal"]').text(0);

          $('span[name="total_new_taxable_value"]').text(0);
          $('span[name="total_new_tax"]').text(0);
          $('span[name="total_new_subtotal"]').text(0);
      }

      $(".new_price").trigger('change');
    }

  });
</script>
