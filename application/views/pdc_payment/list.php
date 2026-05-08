<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><?=$this->lang->line('header_account')?></li>
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_pdc_payment')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('pdc_payment_list')?></li>
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
                <h3 class="card-title"><?=$this->lang->line('header_pdc_payment')?></h3>
                <?php 
                if($this->permission_model->has_permission('add_pdc_payment'))
                  {
                ?>
                <div class="card-tools">
                  <button type="button" class="btn btn-block btn-primary btn-sm add_pdc_payment_modal" data-toggle="modal" data-target="#add_pdc_payment_modal" data-tt="tooltip" title="Click here to Add PDC payment" data-pdc_payment_id="">Add PDC Payment</button>
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
                     
                      <th><?=$this->lang->line("pdc_payment_ledger_id")?></th>
                      <th><?=$this->lang->line("pdc_payment_pdc_cheque_no")?></th>
                      <th><?=$this->lang->line("pdc_payment_pdc_cheque_date")?></th>
                      <th><?=$this->lang->line("pdc_payment_pdc_amount")?></th>
                      <th><?=$this->lang->line("pdc_payment_pdc_deposit_to")?></th>
                      <th><?=$this->lang->line("pdc_payment_pdc_deposit_date")?></th>
                      <th width="15%"><?=$this->lang->line("action")?></th>   
                    </tr>
                  </thead>
                  <tbody>
                  </tbody>
                  <tfoot>
                  <tr>
                     
                      <th><?=$this->lang->line("pdc_payment_ledger_id")?></th>
                      <th><?=$this->lang->line("pdc_payment_pdc_cheque_no")?></th>
                      <th><?=$this->lang->line("pdc_payment_pdc_cheque_date")?></th>
                      <th><?=$this->lang->line("pdc_payment_pdc_amount")?></th>
                      <th><?=$this->lang->line("pdc_payment_pdc_deposit_to")?></th>
                      <th><?=$this->lang->line("pdc_payment_pdc_deposit_date")?></th>
                      <th width="15%"><?=$this->lang->line("action")?></th>   
                  </tr>
                </tfoot>
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
  <div class="modal fade" id="add_pdc_payment_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="add_deposit_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>



<div class="example-modal">
  <div class="modal fade" id="delete_pdc_payment" data-backdrop="static" data-keyboard="false">
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
            "url": "<?php echo site_url('pdc_payment/ajax_list')?>",
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
              "targets": [6], //first column / numbering column
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
       //Date picker
        // var datepickerInput = $('.datetimepicker-input');

        // datepickerInput.datepicker({
        //     weekStart: 1,
        //     daysOfWeekHighlighted: "6,0",
        //     autoclose: true,
        //     todayHighlight: true,
        //     format: 'dd-mm-yyyy'
        // });

        // // Manually trigger datepicker when clicking on the input field
        // datepickerInput.on('click', function () {
        //     datepickerInput.datepicker('show');
        // });
    }

    $(document).on('input', '#pdc_cheque_no' , function () {
     
        var input = $(this).val();
        
        if (input.length > 10) {
            $(this).val(input.slice(0, 10)); // Truncate input to 10 characters
        }
    });

    /* add pdc payment start */

    $(document).on('click', ".add_pdc_payment_modal" ,function(event){
      event.preventDefault();
      
      var pdc_payment_id = $(this).data('pdc_payment_id');
      pdc_payment_id = (pdc_payment_id === undefined) ? "" : "/"+pdc_payment_id;

      $.ajax({
        url: "<?=base_url('pdc_payment/add')?>"+pdc_payment_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_pdc_payment_modal').find('.modal-content').html(data.add_pdc_payment_modal_body);
          $('#add_pdc_payment_modal').modal('show');

          $('.datepicker').datepicker({
            weekStart: 1,
            daysOfWeekHighlighted: "6,0",
            autoclose: true,
            todayHighlight: true,
            format: 'dd-mm-yyyy'
          });
          initialize_datatable();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          Swal.fire({
            title: 'FAILURE !!',
            // text: xhr.status + thrownError + ajaxOptions,
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
          });
        }
      });
    });

    
    $(document).on('submit','#addPDCpaymentForm',function(e){
    
      
      e.preventDefault();

      $('#addPDCpaymentSubmit').text('Please wait...').attr('disabled','disabled');

      var formData = $('#addPDCpaymentForm').serialize();
      // alert(formData);
            
      var isError = false;

      $('form#addPDCpaymentForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addPDCpaymentForm  #err_"+id).text(field+ " field is required.");
            $('form#addPDCpaymentForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addPDCpaymentForm #err_"+id).text("");
            $('form#addPDCpaymentForm #'+id).removeClass('is-invalid');
            $('form#addPDCpaymentForm #'+id).addClass('is-valid');
          }


       
      });
      
      if(isError == true)
      {
        
        $('#addPDCpaymentSubmit').text('Submit').removeAttr('disabled');
        return false;
      }
      else
      {
      
        var pdc_payment_id = $('#add_pdc_payment_modal').find('input[name="id"]').val();
        pdc_payment_id = (pdc_payment_id == '') ? "" : "/"+pdc_payment_id;

        $.ajax({
          url: "<?php echo base_url('pdc_payment/add')?>"+pdc_payment_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_pdc_payment_modal').modal('hide');
              $('form#addPDCpaymentForm #addPDCpaymentSubmit').text('Submit').removeAttr('disabled');

              Swal.fire({
                title: "SUCCESS !!",
                text: response.message,
                icon: "success",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              initialize_datatable();
            }
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                $("form#addPDCpaymentForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#addPDCpaymentForm #addPDCpaymentSubmit').text('Submit').removeAttr('disabled');
            }
            else
            {
              Swal.fire({
                title: "FAILURE !!",
                text: response.message,
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              $('#addPDCpaymentSubmit').text('Submit').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addPDCpaymentForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addPDCpaymentForm #err_"+id).text(field+ " field is required.");
          return false;
        }
        else{
          $("form#addPDCpaymentForm #err_"+id).text("");
        }
    });

    $(document).on('click', ".add_deposit_modal" ,function(event){
      event.preventDefault();
      
      var pdc_payment_id = $(this).data('pdc_payment_id');
      pdc_payment_id = (pdc_payment_id === undefined) ? "" : "/"+pdc_payment_id;

     // alert(pdc_payment_id);

      $.ajax({
        url: "<?=base_url('pdc_payment/add_deposit')?>"+pdc_payment_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_deposit_modal').find('.modal-content').html(data.add_deposit_modal_body);
          $('#add_deposit_modal').modal('show');

          initialize_datatable();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          Swal.fire({
            title: 'FAILURE !!',
            // text: xhr.status + thrownError + ajaxOptions,
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
          });
        }
      });
    });

    $(document).on('submit','#adddepositForm',function(e){
    
      
      e.preventDefault();

      $('#adddepositSubmit').text('Please wait...').attr('disabled','disabled');

      var formData = $('#adddepositForm').serialize();
      // alert(formData);
            
      var isError = false;

      $('form#adddepositForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#adddepositForm  #err_"+id).text(field+ " field is required.");
            $('form#adddepositForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#adddepositForm #err_"+id).text("");
            $('form#adddepositForm #'+id).removeClass('is-invalid');
            $('form#adddepositForm #'+id).addClass('is-valid');
          }


      
      });
      
      if(isError == true)
      {
        
        $('#adddepositSubmit').text('Submit').removeAttr('disabled');
        return false;
      }
      else
      {
      
        var pdc_payment_id = $('#add_deposit_modal').find('input[name="id"]').val();
        pdc_payment_id = (pdc_payment_id == '') ? "" : "/"+pdc_payment_id;

        $.ajax({
          url: "<?php echo base_url('pdc_payment/add_deposit')?>"+pdc_payment_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_deposit_modal').modal('hide');
              $('form#adddepositForm #adddepositSubmit').text('Submit').removeAttr('disabled');

              Swal.fire({
                title: 'SUCCESS !!',
                text: response.message,
                icon: "success",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              initialize_datatable();
            }
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                $("form#adddepositForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#adddepositForm #adddepositSubmit').text('Submit').removeAttr('disabled');
            }
            else
            {
              Swal.fire({
                title: "FAILURE !!",
                text: response.message,
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              $('#adddepositSubmit').text('Submit').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on('change','#ledger_id',function(e){
      var ledger_id = $('#ledger_id').val();

      var purchase_id_el     = $('#purchase_id');
      
      purchase_id_el.html('<option value="">Select</option>');

      if(ledger_id != '')
      {
        $.ajax({

          async:false,
          url: "<?php echo base_url('pdc_payment/get_purchase_records_by_ledger') ?>",
          type: "POST",
          data: {
            'ledger_id' : ledger_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){
            
            var records   = null;
            records = data.purchases;

            for(i=0;i<records.length;i++)
            {
              purchase_id_el.append('<option value="' + records[i].id + '">' + records[i].reference_no + '</option>');  
            }

            $('#purchase_id').trigger('change');
           
                
          }
        });
      }

      
    });


    
    $(document).on('show.bs.modal','#delete_pdc_payment', function (e) {
      // alert();
      var pdc_payment_id = $(e.relatedTarget).data('pdc_payment_id');
      // $('#delete_pdc_payment').find('#id').val(pdc_payment_id);

      // alert(pdc_payment_id);

      $.ajax({
        url: "<?php echo base_url('pdc_payment/pdc_payment_delete_confirmation')?>",
        type: "POST",
        data:{
          'pdc_payment_id': pdc_payment_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_pdc_payment').find('.modal-content').html(data.pdc_payment_delete_modal_body);
        }
      });


    });

      // Delete record with please wait text
    $(document).on('submit','#deletePDCpaymentForm',function(e){
      $('#deletePDCpaymentSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    

    
  });
</script>
