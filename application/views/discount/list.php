<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('discount')?>"><?=$this->lang->line('header_discount')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_discount')?></li>
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
                <h3 class="card-title">Discount</h3>

                <div class="card-tools">

                  <ul class="nav nav-pills ml-auto">

                    <?php 
                      if($this->permission_model->has_permission('import_discount'))
                      {
                    ?>
                    <li class="nav-item  ml-2">
                      <a class="nav-link import_discount_modal btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Import Discount in Bulk using CSV file">
                        <i class="fas fa-file-import"></i> Import Discount
                      </a>
                    </li>
                    <?php 
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_permission('add_discount'))
                      {
                    ?>
                        <li class="nav-item ml-2">
                        <button type="button" class="btn btn-block btn-primary btn-sm add_discount_modal" data-toggle="modal" data-target="#add_discount_modal" data-tt="tooltip" title="Click here to Add Discount">Add Discount</button>
                        </li>
                    <?php
                      }
                    ?>
                  </ul>
                </div>

                
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><?=$this->lang->line("discount_name")?></th>
                      <th><?=$this->lang->line("discount_type")?></th>
                      <th><?=$this->lang->line("discount_value")?></th>
                      <th><?=$this->lang->line("discount_valid_from")?></th>
                      <th><?=$this->lang->line("discount_valid_to")?></th>
                      <th><?=$this->lang->line("discount_description")?></th>
                      <th><?=$this->lang->line('discount_status')?></th>
                      <th width="15%"><?=$this->lang->line("discount_action")?></th>   
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
<div class="modal fade" id="add_discount_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
<div class="modal fade" id="edit_discount_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="delete_discount_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="import_discount_modal" data-backdrop="static" data-keyboard="false">
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

     const discountToast = Swal.mixin({
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
          "url": "<?php echo site_url('discount/ajax_list')?>",
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
            "targets": [ 7 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
     });
    }

  $(document).on('click', ".add_discount_modal" ,function(){
    var discount_id = $(this).data('discount_id');

    $.ajax({
    url: "<?php echo base_url('discount/add')?>/"+discount_id,
      type: "GET",
      dataType: "JSON",
      success: function(data){
        $('#add_discount_modal').find('.modal-content').html(data.add_discount_modal_body);
        $('#add_discount_modal').modal('show');
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


    $(document).on('submit','#addDiscountForm',function(e){
      
      e.preventDefault();

      $('#addDiscountSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addDiscountForm').serialize();

      var isError = false;

      $('form#addDiscountForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addDiscountForm  #err_"+id).text(field+ " field is required.");
            $('form#addDiscountForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addDiscountForm #err_"+id).text("");
            $('form#addDiscountForm #'+id).removeClass('is-invalid');
            $('form#addDiscountForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addDiscountSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('discount/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
            $('#add_discount_modal').modal('hide');
              $('form#addDiscountForm #addDiscountSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              discountToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              discountToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addDiscountSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addDiscountForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addDiscountForm #err_"+id).text(field+ " field is required.");
          $('form#addDiscountForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addDiscountForm #err_"+id).text("");
          $('form#addDiscountForm #'+id).removeClass('is-invalid');
          $('form#addDiscountForm #'+id).addClass('is-valid');
        }
    });

    $(document).on('click','.edit_discount_modal', function (e) {
      
      var discount_id = $(this).data('discount_id');
      $('#edit_discount_modal').find('#id').val(discount_id);

      $.ajax({
        url: "<?php echo base_url('discount/edit')?>/"+discount_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#edit_discount_modal').find('.modal-content').html(data.edit_discount_modal_body);

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

    $(document).on('hidden.bs.modal','#edit_discount_modal', function (e) {
      $('#edit_discount_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
    $(document).on('submit','#editDiscountForm',function(e){
      e.preventDefault();

      $('#editDiscountSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#editDiscountForm').serialize();

      var isError = false;

      $('form#editDiscountForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editDiscountForm  #err_"+id).text(field+ " field is required.");
            $('form#editDiscountForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editDiscountForm #err_"+id).text("");
            $('form#editDiscountForm #'+id).removeClass('is-invalid');
            $('form#editDiscountForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#editDiscountSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('discount/edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#edit_discount_modal').modal('hide');
              $('form#editDiscountForm #editDiscountSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              discountToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#editDiscountSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

  $(document).on('shown.bs.modal','#delete_discount_modal', function (e) {
    var discount_id = $(e.relatedTarget).data('discount_id');
    $('#delete_discount_modal').find('#id').val(discount_id);

      $.ajax({
      url: "<?php echo base_url('discount/discount_delete_confirmation')?>",
        type: "POST",
        data:{
        'discount_id': discount_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
        $('#delete_discount_modal').find('.modal-content').html(data.delete_discount_modal_body);
        }
      });

    });

    $(document).on('submit', '#deleteDiscountForm' ,function (e) {
      e.preventDefault();

      $('#deleteDiscountSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteDiscountForm').serialize();

      $.ajax({
      url: "<?php echo base_url('discount/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
            $('#delete_discount_modal').modal('hide');
            $('form#deleteDiscountForm #deleteDiscountSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            initialize_datatable();

            discountToast.fire({
              type: 'success',
              title: response.message
            });
            
          }
          else
          {
            discountToast.fire({
              type: 'error',
              title: response.message
            });            
          }

          $('#deleteDiscountSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        }
      });
    });
  });

      /* Import discount using CSV function Begin */

    $(document).on('click', ".import_discount_modal" ,function(event){
      event.preventDefault();

      $.ajax({
        url: "<?php echo base_url('discount/import_discount')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#import_discount_modal').find('.modal-content').html(data.import_discount_modal_body);
          $('#import_discount_modal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // alert(xhr.status);
          show_message('failure-header',thrownError);
          // alert(thrownError);
          // alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','form#importDiscountForm',function(event){
      // event.preventDefault();

      $('form#importDiscountForm #importDiscountSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('form#importDiscountForm').serialize();

      var isError = false;

      $('form#importDiscountForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#importDiscountForm  #err_"+id).text(field+ " field is required.");
            $('form#importDiscountForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#importDiscountForm #err_"+id).text("");
            $('form#importDiscountForm #'+id).removeClass('is-invalid');
            $('form#importDiscountForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('form#importDiscountForm #importDiscountSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        return true;
      }
    });

    /* Import discount using CSV function End */

</script>


<!--  -->