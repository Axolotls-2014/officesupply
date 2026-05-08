<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('purchase_order')?>"><?=$this->lang->line('header_purchase_order')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('purchase_order_list')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row d-none">
        <div class="col-12 col-sm-12 col-md-12">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-store"></i></span>

            <div class="info-box-content">
              <span class="info-box-text"><?=$this->lang->line('total_purchase_order')?></span>
              <span class="info-box-number" style="font-size: 19px">
                <!-- <small><?=$this->session->userdata('currency_symbol')?></small> -->
                <?=$this->session->userdata('currency_symbol')?>
                <?=($total_purchase_order == 0) ? '0.000' : $total_purchase_order?>
              </span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
       

        <!-- /.col -->
      </div>
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">
                <?=$this->lang->line('purchase_order_list')?> 
              </h3> ( <span class="text-xs"><?=$this->lang->line('all_amount_are_in')?> <b><?=$this->session->userdata('currency_symbol')?></b></span> )
              
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <?php 
                    if($this->permission_model->has_permission('add_purchase_order'))
                    {
                  ?>
                      <li class="nav-item ml-2">
                        <a class="nav-link active" href="<?=base_url('purchase_order/add')?>" data-tt="tooltip" title="Click here to Add Purchase Order"><i class="far fa-snowflake mr-2"></i><?=$this->lang->line('purchase_order_add')?></a>
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
                    <th><?=$this->lang->line('purchase_order_reference_no')?></th>
                   
                    <th><?=$this->lang->line('purchase_order_date')?></th>
                    <th>Branch</th>
                    <th><?=$this->lang->line('purchase_order_supplier')?></th>
                    <th><?="Discount" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                     <th><?="Taxable" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <th><?="GST" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <th><?=$this->lang->line('purchase_order_total')?></th>
                    <th width="15%"><?=$this->lang->line('purchase_order_action')?></th>
                  </tr>
                </thead>
                <tbody>
                 
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('purchase_order_reference_no')?></th>
                   
                    <th><?=$this->lang->line('purchase_order_date')?></th>
                    <th>Branch</th>
                    <th><?=$this->lang->line('purchase_order_supplier')?></th>
                    <th><?="Discount" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                     <th><?="Taxable" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <th><?="GST" . ' (' . $this->session->userdata('currency_symbol') . ')'?></th>
                    <th><?=$this->lang->line('purchase_order_total')?></th>
                    <th><?=$this->lang->line('purchase_order_action')?></th>
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
  <div class="modal fade" id="delete_purchase_order">
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

    const EmailToast = Swal.mixin({
                              toast: true,
                              position: 'top-end',
                              showConfirmButton: false,
                              timer: 10000
                            });


           /*************************** Start Dynamic Sale List with Datatables **************************/
    initialize_datatable();
    function initialize_datatable()
    {
      
      $('#example').DataTable({ 
   
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "order": [], //Initial no order.
          "bDestroy": true, //Destroy before reinitialise
   
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": "<?php echo site_url('purchase_order/ajax_list')?>",
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
              "targets": [ 0,8], //first column / numbering column
              "orderable": false, //set not orderable
            },
          ],
      });
    }

  

    $(document).on('show.bs.modal','#delete_purchase_order', function (e) {
      var purchase_order_id = $(e.relatedTarget).data('purchase_order_id');
      $('#delete_purchase_order').find('#id').val(purchase_order_id);

      // alert(purchase_id);

      $.ajax({
        url: "<?php echo base_url('purchase_order/purchase_order_delete_confirmation')?>",
        type: "POST",
        data:{
          'purchase_order_id': purchase_order_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_purchase_order').find('.modal-content').html(data.purchase_order_delete_modal_body);
        }
      });
    });

    // Delete record with please wait text
    $(document).on('submit','#deletePurchaseOrderForm',function(e){
      $('#deletePurchaseOrderSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

 

    /*************************** End Dynamic Sale List with Datatables ****************************/

    $(document).on('click','.email-modal',function(e){
      var purchase_order_id = $(this).data('purchase_order_id');
      // alert();

      $.ajax({
        url: "<?php echo base_url('purchase_order/send_email')?>/"+purchase_order_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
           
          $('#email-modal').find('.modal-content').html(data.email_modal_body);
          $('#email-modal').modal('show');
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
        url: "<?php echo base_url('purchase_order/send_email')?>",
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
