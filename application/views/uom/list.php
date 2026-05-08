<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('uom')?>"><?=$this->lang->line('header_uom')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_uom')?></li>
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
                <h3 class="card-title">Uom</h3>
                <?php 
                if($this->permission_model->has_permission('add_uom'))
                  {
                ?>
                <div class="card-tools">
                <button type="button" class="btn btn-block btn-primary btn-sm add_uom_modal" data-toggle="modal" data-target="#add_uom_modal" data-tt="tooltip" title="Click here to Add Uom">Add Uom</button>
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
                      <th><?=$this->lang->line("uom_uom")?></th>
                      <th><?=$this->lang->line("uom_name")?></th>
                      <th width="15%"><?=$this->lang->line("uom_action")?></th>   
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
<div class="modal fade" id="add_uom_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
<div class="modal fade" id="edit_uom_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
<div class="modal fade" id="delete_uom_modal" data-backdrop="static" data-keyboard="false">
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

     const uomToast = Swal.mixin({
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
          "url": "<?php echo site_url('uom/ajax_list')?>",
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
            "targets": [ 0 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
     });
    }

  $(document).on('click', ".add_uom_modal" ,function(){
    var uom_id = $(this).data('uom_id');

      $.ajax({
      url: "<?php echo base_url('uom/add')?>/"+uom_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_uom_modal').find('.modal-content').html(data.add_uom_modal_body);
          $('#add_uom_modal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });


    $(document).on('submit','#addUomForm',function(e){
      
      e.preventDefault();

      $('#addUomSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addUomForm').serialize();

      var isError = false;

      $('form#addUomForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addUomForm  #err_"+id).text(field+ " field is required.");
            $('form#addUomForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addUomForm #err_"+id).text("");
            $('form#addUomForm #'+id).removeClass('is-invalid');
            $('form#addUomForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addUomSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('uom/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
            $('#add_uom_modal').modal('hide');
              $('form#addUomForm #addUomSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              uomToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              uomToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addUomSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addUomForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addUomForm #err_"+id).text(field+ " field is required.");
          $('form#addUomForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addUomForm #err_"+id).text("");
          $('form#addUomForm #'+id).removeClass('is-invalid');
          $('form#addUomForm #'+id).addClass('is-valid');
        }
    });

  $(document).on('show.bs.modal','#edit_uom_modal', function (e) {
      
      var uom_id = $(e.relatedTarget).data('uom_id');
      $('#edit_uom_modal').find('#id').val(uom_id);

      $.ajax({
        url: "<?php echo base_url('uom/edit')?>/"+uom_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#edit_uom_modal').find('.modal-content').html(data.edit_uom_modal_body);
        }
      });

    });

    $(document).on('hidden.bs.modal','#edit_uom_modal', function (e) {
      $('#edit_uom_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
     $(document).on('submit','#editUomForm',function(e){
      e.preventDefault();

      $('#editUomSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#editUomForm').serialize();

      var isError = false;

      $('form#editUomForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editUomForm  #err_"+id).text(field+ " field is required.");
            $('form#editUomForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editUomForm #err_"+id).text("");
            $('form#editUomForm #'+id).removeClass('is-invalid');
            $('form#editUomForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#editUomSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('uom/edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#edit_uom_modal').modal('hide');
              $('form#editUomForm #editUomSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              uomToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#editUomSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

  $(document).on('shown.bs.modal','#delete_uom_modal', function (e) {
    var uom_id = $(e.relatedTarget).data('uom_id');
    $('#delete_uom_modal').find('#id').val(uom_id);

      $.ajax({
      url: "<?php echo base_url('uom/uom_delete_confirmation')?>",
        type: "POST",
        data:{
        'uom_id': uom_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
        $('#delete_uom_modal').find('.modal-content').html(data.delete_uom_modal_body);
        }
      });

    });

    $(document).on('submit', '#deleteUomForm' ,function (e) {
      e.preventDefault();

      $('#deleteUomSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteUomForm').serialize();

      $.ajax({
      url: "<?php echo base_url('uom/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
            $('#delete_uom_modal').modal('hide');
            $('form#deleteUomForm #deleteUomSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            initialize_datatable();

            uomToast.fire({
              type: 'success',
              title: response.message
            });
            
          }
          else
          {
            uomToast.fire({
              type: 'error',
              title: response.message
            });            
          }

          $('#deleteUomSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        }
      });
    });
  });
</script>


<!--  -->