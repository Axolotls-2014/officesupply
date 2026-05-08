<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?=base_url('auth')?>"><?=$this->lang->line('home')?></a></li>
           
            <li class="breadcrumb-item"><a href="<?=base_url('delivery_challan')?>"><?=$this->lang->line('delivery_challan_header')?></a></li>
            
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
			          	
        <div class="col-12">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-store"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><?=$this->lang->line('delivery_challan_total_total')?></span>
              <span class="info-box-number" style="font-size: 19px">
                <!-- <small><?=$this->session->userdata('currency_symbol')?></small> -->
              
                <?=($total_delivery_challan == '') ? '0' : $total_delivery_challan?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- ./col -->
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('delivery_challan_list')?></h3>
              ( <span class="text-xs"><?=$this->lang->line('all_amount_are_in')?> <b><?=$this->session->userdata('currency_symbol')?></b></span> )
              
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                 <!-- <?php 
                    if($this->permission_model->has_permission('add_sale'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    <a class="nav-link generate_delivery_challan active" href="" data-tt="tooltip" title="Click here convert to sale invoice."><i class="fas fa-store mr-2"></i>Convert to Sale Invoice</a>
                  </li>
                  <?php 
                    }
                  ?> -->
                 
                  <?php 
                    if($this->permission_model->has_permission('add_delivery_challan'))
                    {
                  ?>
                      <li class="nav-item ml-2">
                        <a class="nav-link active" href="<?=base_url('delivery_challan/add')?>" data-tt="tooltip" title="Click here to Add delivery challan"><i class="far fa-snowflake mr-2"></i><?=$this->lang->line('delivery_challan_add')?></a>
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
                    <th width="2%"><input type="checkbox" class="all_delivery_challan"></th>
                    <th><?=$this->lang->line('delivery_challan_reference_no')?></th>
                    <th><?=$this->lang->line('delivery_challan_invoice_date')?></th>
                    <th><?=$this->lang->line('delivery_challan_customer')?></th>
                    <!--<th><?=$this->lang->line('delivery_challan_total_discount')?></th>-->
                    <th><?=$this->lang->line('delivery_challan_total_taxable_value')?></th>
                    <!--<th><?=$this->lang->line('delivery_challan_tds')?></th>-->
                    <th><?=$this->lang->line('delivery_challan_total_tax')?></th>
                    <th><?=$this->lang->line('delivery_challan_total')?></th>
                  
                    <th width="15%"><?=$this->lang->line('delivery_challan_action')?></th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('delivery_challan_reference_no')?></th>
                    <th><?=$this->lang->line('delivery_challan_invoice_date')?></th>
                    <th><?=$this->lang->line('delivery_challan_customer')?></th>
                    <!--<th><?=$this->lang->line('delivery_challan_total_discount')?></th>-->
                    <th><?=$this->lang->line('delivery_challan_total_taxable_value')?></th>
                    <!--<th><?=$this->lang->line('delivery_challan_tds')?></th>-->
                    <th><?=$this->lang->line('delivery_challan_total_tax')?></th>
                    <th><?=$this->lang->line('delivery_challan_total')?></th>
                    <th><?=$this->lang->line('delivery_challan_action')?></th>
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
  <div class="modal fade" id="delete_delivery_challan">
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

<div class="example-modal">
  <div class="modal fade" id="generate_delivery_challan_modal">
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

    const EmailToast = Swal.mixin({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 10000
                            });

           /*************************** Start Dynamic delivery challan List with Datatables **************************/

    initialize_datatable();
function initialize_datatable() {
    $('#example').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 100,
        "order": [],
        "bDestroy": true,
        "ajax": {
            "url": "<?php echo site_url('delivery_challan/ajax_list')?>",
            "type": "POST",
            "data": function(d) {
                // Include CSRF token with every request
                d.<?php echo $this->security->get_csrf_token_name(); ?> = 
                    '<?php echo $this->security->get_csrf_hash(); ?>';
            },
            "error": function(xhr, error, thrown) {
                console.error("AJAX Error:", xhr.responseText);
                // Show more detailed error message
                alert("Error loading data. Status: " + xhr.status + " - " + thrown);
            }
        },
        "initComplete": function(settings, json) {
            $('[data-tt="tooltip"]').tooltip({trigger: 'hover'});
        },
        "columnDefs": [
            { 
                "targets": [0,6,7],
                "orderable": false
            }
        ]
    });
}
  

    $(document).on('show.bs.modal','#delete_delivery_challan', function (e) {
      var delivery_challan_id = $(e.relatedTarget).data('delivery_challan_id');
      $('#delete_delivery_challan').find('#id').val(delivery_challan_id);

      // alert(delivery_challan_id);

      $.ajax({
        url: "<?php echo base_url('delivery_challan/delivery_challan_delete_confirmation')?>",
        type: "POST",
        data:{
          'delivery_challan_id': delivery_challan_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_delivery_challan').find('.modal-content').html(data.delivery_challan_delete_modal_body);
        }
      });


    });

     // Delete record with please wait text
    $(document).on('submit','#deleteProformaInvoiceForm',function(e){
      $('#deleteProformaInvoiceSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

   
    /*************************** End Dynamic delivery challan List with Datatables ****************************/

    $(document).on('click','.email-modal',function(e){
      var delivery_challan_id = $(this).data('delivery_challan_id');
      // alert();

      $.ajax({
        url: "<?php echo base_url('delivery_challan/send_email')?>/"+delivery_challan_id,
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
        url: "<?php echo base_url('delivery_challan/send_email')?>",
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

    $(document).on('change', '.all_delivery_challan', function() {
      if (this.checked == true)
          $('.single_delivery_challan:not(:disabled)').prop('checked', true);
      else
          $('.single_delivery_challan:not(:disabled)').prop('checked', false);
    });

    $(document).on('change', '.single_delivery_challan', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    function set_select_all_checkbox_status() {
      var total_single_delivery_challan = $('.single_delivery_challan').length;
      var total_checked_single_delivery_challan = $('.single_delivery_challan:checked').length;
      var total_enabled_single_delivery_challan = $('.single_delivery_challan:not(:disabled)').length;
      var total_checked_enabled_single_delivery_challan = $('.single_delivery_challan:checked:not(:disabled)').length;
      
      if (total_checked_enabled_single_delivery_challan < total_enabled_single_delivery_challan && total_checked_enabled_single_delivery_challan > 0) {
          $('.all_delivery_challan').prop('indeterminate', true); 
      } else if (total_checked_enabled_single_delivery_challan == total_enabled_single_delivery_challan) {
          $('.all_delivery_challan').prop('indeterminate', false);
          $('.all_delivery_challan').prop('checked', true);
      } else if (total_checked_enabled_single_delivery_challan == 0) {
          $('.all_delivery_challan').prop('indeterminate', false);
          $('.all_delivery_challan').prop('checked', false);
      }
    }

    $(document).on('click', ".generate_delivery_challan", function(event){
      event.preventDefault();
      
      var checkboxes = $('.single_delivery_challan');
      var checkedNames = [];

      checkboxes.each(function() {
          if ($(this).is(':checked')) {
              checkedNames.push($(this).data('delivery_challan_id'));
          }
      });

      if(checkedNames.length)
      {
          $.ajax({
              url: '<?= base_url('sale/convert_from_delivery_challan_list'); ?>'+"/"+checkedNames.join("-")+"/true",
              type: "GET",
              dataType: "JSON",
              data: {
                  // delivery_challan_ids: checkedNames.join("-"),
                  '<?= $this->security->get_csrf_token_name(); ?>': '<?= $this->security->get_csrf_hash(); ?>'
              },
              success: function(data){
                  $('#generate_delivery_challan_modal').find('.modal-content').html(data.generate_delivery_challan_modal_body);
                  $('#generate_delivery_challan_modal').modal('show');
                  $('#generateInvoiceForm').find('input[name="delivery_challan_ids"]').val(checkedNames.join("-"));
              },
              error: function (xhr, ajaxOptions, thrownError) {
                  Swal.fire({
                      title: 'FAILURE !!',
                      icon: "error",
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                          confirmButton: "btn btn-primary"
                      }
                  });
              }
          });
      }
      else
      {
          Swal.fire({
              title: 'FAILURE !!',
              text: "Please select at least one delivery challan",
              icon: "warning",
              confirmButtonText: "Ok, got it!",
              timer: 3000,
              customClass: {
                  confirmButton: "btn btn-primary"
              }
          });
      }
    });

    $(document).on('submit', '#generateInvoiceForm', function(e) {
        e.preventDefault();

        $('#generateInvoiceSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled', 'disabled');
        var formData = $('#generateInvoiceForm').serialize();

        $.ajax({
            url: "<?php echo base_url('sale/convert_from_delivery_challan_list')?>",
            type: "POST",
            data: formData,
            dataType: "JSON",
            success: function(response) {
                if (response.code == 1) {
                  // alert('in');
                    $('#generate_delivery_challan_modal').modal('hide');
                    $('form#generateInvoiceForm #generateInvoiceSubmit').text('Submit').removeAttr('disabled');

                    // Remove modal backdrop
                    //$('.modal-backdrop').remove();

                    Swal.fire({
                        text: response.message,
                        title: 'SUCCESS !!',
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                        timer: 1000
                    });

                    // Call any additional functions you need to run on success
                    // e.g., refreshing a datatable or reloading a section of the page
                    initialize_datatable();
                } else {
                    Swal.fire({
                        text: response.message,
                        title: 'FAILURE !!',
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        },
                        timer: 1000
                    });

                    // Call any additional functions you need to run on failure
                    initialize_datatable();
                }

                $('#generateInvoiceSubmit').text('Submit').removeAttr('disabled');
            }
        });
    });




     

   

    // $(document).on('click', '.generate_delivery_challan', function(event) {
    //   event.preventDefault();

    //   var checkboxes = $('.single_delivery_challan');
      
    //   var checkedNames = [];
      
    //   checkboxes.each(function() {
    //     // Add the checkbox name to the array if it's checked
    //     if ($(this).is(':checked')) {
    //       // alert($(this).data('warehouse_product_id'));
    //       checkedNames.push($(this).data('delivery_challan_id'));
    //     }
    //   });

    //   if(checkedNames.length){
    //     window.location.href = '<?= base_url('sale/convert_from_delivery_challan_list'); ?>'+"/"+checkedNames.join("-")+"/true";
    //   }
    //   else
    //   {
    //     Swal.fire({
    //       text: "Please select alteast one delivery challan",
    //       icon: "warning",
    //       buttonsStyling: !1,
    //       confirmButtonText: "Ok, got it!",
    //       timer: 3000,
    //       customClass: {
    //           confirmButton: "btn btn-primary"
    //       }
    //     });
    //   }
      
    // });


  });
</script>
