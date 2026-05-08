<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('header_whatsapp_template')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        
        <div class="col-md-12">
          <div class="card card-primary card-outline">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('whatsapp_template_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <?php 
                    if($this->permission_model->has_permission('list_whatsapp_message'))
                    {
                  ?>
                  <li class="nav-item  ml-2">
                    <a class="nav-link active text-white btn btn-success" href="<?=base_url('whatsapp_message')?>" data-tt="tooltip" title="Click here to show whatsapp messages list">Whatsapp messages</a>
                  </li>
                  <?php 
                    }
                  ?>
                  
                  <?php 
                    if($this->permission_model->has_permission('add_whatsapp_template'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    
                    <button type="button" class="btn btn-primary add_whatsapp_template_modal">
                      <i class="fas fa-user mr-2"></i><?=$this->lang->line('whatsapp_template_add')?>
                    </button>
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
                   
                    <th><?=$this->lang->line('whatsapp_template_type')?></th>
                    <th><?=$this->lang->line('whatsapp_template_action')?></th>
                  </tr>
                </thead>
                
                <tbody id="whatsapp_template_list">
              
                </tbody>
                <tfoot>
                  <tr>
                   <th><?=$this->lang->line('whatsapp_template_type')?></th>
                    <th><?=$this->lang->line('whatsapp_template_action')?></th>
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

<div class="modal fade" id="add_whatsapp_template_modal" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      
    </div>
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="delete_whatsapp_template" data-backdrop="static">
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

    

    /*************************** Start Dynamic whatsapp_template List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    { 
      $('#example').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [], //Initial no order.
        "pageLength": 50,
 
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('whatsapp_template/ajax_list')?>",
            "type": "POST",
            "data":  {
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 1 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
      });
    }

    function reinitialize(){
      $(".select2").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }

    $(document).on('click', ".add_whatsapp_template_modal" ,function(event){
      event.preventDefault();
      
      var wm_id = $(this).data('wm_id');
      wm_id = (wm_id === undefined) ? "" : "/"+wm_id;

      $.ajax({
        url: "<?=base_url('whatsapp_template/add')?>"+wm_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $(".select2").select2({theme:'bootstrap4'});
          $('#add_whatsapp_template_modal').find('.modal-content').html(data.add_whatsapp_template_modal_body);
          $('#add_whatsapp_template_modal').modal('show');
          reinitialize();
          $('.datepicker').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
          });
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

    $(document).on('submit','#addwhatsappTemplateForm',function(e){
      
      e.preventDefault();

      $('#addwhatsappTemplateSubmit').text('Please wait...').attr('disabled','disabled');
      var formData = $('#addwhatsappTemplateForm').serialize();

      var isError = false;

      $('form#addwhatsappTemplateForm .field_validation').each(function() {
          
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#addwhatsappTemplateForm  #err_"+id).text(field+ " field is required.");
          $('form#addwhatsappTemplateForm  #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addwhatsappTemplateForm #err_"+id).text("");
          $('form#addwhatsappTemplateForm #'+id).removeClass('is-invalid');
          $('form#addwhatsappTemplateForm #'+id).addClass('is-valid');
        }
        
      });
      
      if(isError == true)
      {
        $('#addwhatsappTemplateSubmit').text('Submit').removeAttr('disabled');
        return false;
      }
      else
      {
       
        
        var wm_id = $('#add_whatsapp_template_modal').find('input[name="wm_id"]').val();
        wm_id = (wm_id == '') ? "" : "/"+wm_id;

        $.ajax({
          url: "<?php echo base_url('whatsapp_template/add')?>"+wm_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_whatsapp_template_modal').modal('hide');
              $('form#addwhatsappTemplateForm #addwhatsappTemplateSubmit').text('Submit').removeAttr('disabled');

              Swal.fire({
                text: response.message,
                title: 'SUCCESS !!',
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
                $("form#addwhatsappTemplateForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#addwhatsappTemplateForm #addwhatsappTemplateSubmit').text('Submit').removeAttr('disabled');
            }
            else
            {
              Swal.fire({
                text: response.message,
                title: "FAILURE !!",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              $('#addwhatsappTemplateSubmit').text('Submit').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addwhatsappTemplateForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addwhatsappTemplateForm #err_"+id).text(field+ " field is required.");
          $('form#addwhatsappTemplateForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addwhatsappTemplateForm #err_"+id).text("");
          $('form#addwhatsappTemplateForm #'+id).removeClass('is-invalid');
          $('form#addwhatsappTemplateForm #'+id).addClass('is-valid');
        }
    });


    $(document).on('show.bs.modal','#delete_whatsapp_template', function (e) {
      var wm_id = $(e.relatedTarget).data('wm_id');
      $('#delete_whatsapp_template').find('#id').val(wm_id);

      // alert(wm_id);

      $.ajax({
        url: "<?php echo base_url('whatsapp_template/whatsapp_template_delete_confirmation')?>",
        type: "POST",
        data:{
          'wm_id': wm_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_whatsapp_template').find('.modal-content').html(data.whatsapp_template_delete_modal_body);
        }
      });


    });

    $(document).on('submit', '#deletewhatsappTemplateForm' ,function (e) {
      e.preventDefault();

      $('#deletewhatsappTemplateSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deletewhatsappTemplateForm').serialize();

      $.ajax({
      url: "<?php echo base_url('whatsapp_template/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#delete_whatsapp_template').modal('hide');
            $('form#deletewhatsappTemplateForm #deletewhatsappTemplateSubmit').text('Delete').removeAttr('disabled');
            initialize_datatable();

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
          else
          {
            
            Swal.fire({
                title: 'FAILURE !!',
                text: response.message,
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              initialize_datatable();        
          }

          $('#deletewhatsappTemplateSubmit').text('Delete').removeAttr('disabled');
        }
      });
    });

    /*************************** End Dynamic Employyee List with Datatables ****************************/

   
    
  });
</script>