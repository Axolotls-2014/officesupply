<?php $this->load->view('layout/header');?>
 

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('quotation')?>"><?=$this->lang->line('quotation_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('quotation_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('quotation_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item ml-2">
                    <select class="form-control form-control-sm" id="quotation_status">
                      <option value="">All</option>
                      <option value="<?=strtoupper(QUOTATION_STATUS_PENDING)?>" selected><?=strtoupper(QUOTATION_STATUS_PENDING)?></option>
                      <option value="<?=strtoupper(QUOTATION_STATUS_APPROVED)?>"><?=strtoupper(QUOTATION_STATUS_APPROVED)?></option>
                      <option value="<?=strtoupper(QUOTATION_STATUS_REJECTED)?>"><?=strtoupper(QUOTATION_STATUS_REJECTED)?></option>
                    </select>
                  </li>
                  <li class="nav-item ml-2">
                    <?php 
                      if($this->permission_model->has_permission('add_quotation'))
                      {
                    ?>
                        <a class="nav-link active" href="<?=base_url('quotation/add')?>" data-tt="tooltip" title="Click here to Add Quotation"><i class="far fa-snowflake mr-2"></i><?=$this->lang->line('quotation_add')?></a>
                    <?php 
                      }
                    ?>
                  </li>
                </ul>
              </div>
            
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('quotation_reference_no')?></th>
                    <th><?=$this->lang->line('quotation_date')?></th>
                     <th>Company Name</th>  <!-- New column -->
            <th>Customer Name</th>  <!-- New column -->
                    <!--<th><?=$this->lang->line('quotation_customer')?></th>-->
                    <th><?="Taxable" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <!--<th><?="Discount" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>-->
                    <th><?="GST" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <th><?='Grand Total (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <th><?=$this->lang->line('quotation_status')?></th>
                    <th width="22%"><?=$this->lang->line('quotation_action')?></th>
                  </tr>
                </thead>
                <tbody>
                 
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('quotation_reference_no')?></th>
                    <th><?=$this->lang->line('quotation_date')?></th>
                     <th>Company Name</th>  <!-- New column -->
            <th>Customer Name</th>  <!-- New column -->
                    <!--<th><?=$this->lang->line('quotation_customer')?></th>-->
                    <th><?="Taxable" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <!--<th><?="Discount" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>-->
                    <th><?="GST" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <th><?=$this->lang->line('quotation_total').' ('.$this->session->userdata('currency_symbol').')'?></th>
                    <th><?=$this->lang->line('quotation_status')?></th>
                    <th><?=$this->lang->line('quotation_action')?></th>
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
  <div class="modal fade" id="delete_quotation">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="change_status_quotation">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
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

<script type="text/javascript">
  $(document).ready(function(e){

    const emailToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

       /*************************** Start Dynamic Product List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    {
      var quotation_status = $('#quotation_status').val();

      $('#example').DataTable({ 
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "order": [], //Initial no order.
          "bDestroy":true,
   
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": "<?php echo site_url('quotation/ajax_list')?>",
              "type": "POST",
              "data":  {
                'status':quotation_status,
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              }
          },

          'initComplete':function(settings, json){
            $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
          },  
   
          //Set column definition initialisation properties.
          "columnDefs": [
            { 
              "targets": [ 0,4,5,7 ], //first column / numbering column
              "orderable": false, //set not orderable
            },
          ],
      });
    }

    $(document).on('change','#quotation_status',function(e){
      initialize_datatable();
    });

    $(document).on('show.bs.modal','#delete_quotation', function (e) {
      var quotation_id = $(e.relatedTarget).data('quotation_id');
      $('#delete_quotation').find('#id').val(quotation_id);

      $.ajax({
        url: "<?php echo base_url('quotation/quotation_delete_confirmation')?>",
        type: "POST",
        data:{
          'quotation_id': quotation_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_quotation').find('.modal-content').html(data.quotation_delete_modal_body);
        }
      });
    });

     // Delete record with please wait text
    $(document).on('submit','#deleteQuotationForm',function(e){
      $('#deleteQuotationSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    $(document).on('show.bs.modal','#change_status_quotation', function (e) {
      var quotation_id = $(e.relatedTarget).data('quotation_id');
      $('#change_status_quotation').find('#id').val(quotation_id);

      $.ajax({
        url: "<?php echo base_url('quotation/quotation_change_status_confirmation')?>",
        type: "POST",
        data:{
          'quotation_id': quotation_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#change_status_quotation').find('.modal-content').html(data.quotation_change_status_modal_body);
        }
      });
    });

    // Change quotation status confirmation dialogue

    // $(document).on('click','.change_status_quotation', function (e) {
    //   $('#change_status_quotation').modal('show');
    // });    

    $(document).on('show.bs.modal','#change_status_quotation', function (e) {
      var quotation_id = $(e.relatedTarget).data('quotation_id');
      $('#change_status_quotation').find('#id').val(quotation_id);

      $.ajax({
        url: "<?php echo base_url('quotation/quotation_change_status_confirmation')?>",
        type: "POST",
        data:{
          'quotation_id': quotation_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#change_status_quotation').find('.modal-content').html(data.quotation_change_status_modal_body);
        }
      });
    });

     // Delete record with please wait text
    $(document).on('submit','#changeQuotationStatusForm',function(e){

      if($('#changeQuotationStatusForm').find('input[name="change_status"]').val() == '')
      {
        e.preventDefault();
        $('#quotation_status').text('Please select Status').addClass('text-danger');
      }
      else
      {
        $('#changeQuotationStatusSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');  
      }
    });

    // Change change_status value based on radio button selection.
    $(document).on('change','input[name="quotation_status"]',function(e){
      $('input[name="change_status"]').val($(this).val());
      $('#quotation_status').text('');
    });

    /*************************** End Dynamic Product List with Datatables ****************************/

    $(document).on('click','.email-modal',function(e){
      var quotation_id = $(this).data('quotation_id');
      // alert();

      $.ajax({
        url: "<?php echo base_url('quotation/send_email')?>/"+quotation_id,
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
        url: "<?php echo base_url('quotation/send_email')?>",
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

              emailToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              emailToast.fire({
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