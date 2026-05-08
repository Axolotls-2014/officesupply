<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
           <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>

                <li class="breadcrumb-item "><a href="<?=base_url('warehouse')?>">Branch</a></li>
                <li class="breadcrumb-item active">Branch</li>
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
                <h3 class="card-title">Branch</h3>
                <?php 
                if($this->permission_model->has_permission('add_warehouse'))
                  {
                ?>
                <div class="card-tools">
                <button type="button" class="btn btn-block btn-primary btn-sm add_warehouse_modal" data-toggle="modal" data-target="#add_warehouse_modal" data-tt="tooltip" title="Click here to Add Warehouse">Add Branch</button>
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
                      <th><?=$this->lang->line("warehouse_name")?></th>
                      <th><?=$this->lang->line("warehouse_code")?></th>
                      <th><?=$this->lang->line("warehouse_description")?></th>
                      <th><?=$this->lang->line('quantity')?></th>
                      <th>Is Head Office</th>
                      <th width="15%"><?=$this->lang->line("warehouse_action")?></th>   
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
 <div class="modal fade" id="add_warehouse_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
<div class="modal fade" id="edit_warehouse_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
<div class="modal fade" id="delete_warehouse_modal" data-backdrop="static" data-keyboard="false">
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

     const warehouseToast = Swal.mixin({
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
          "url": "<?php echo site_url('warehouse/ajax_list')?>",
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
            "targets": [ 4 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
     });
    }

  $(document).on('click', ".add_warehouse_modal" ,function(){
    var warehouse_id = $(this).data('warehouse_id');

      $.ajax({
      url: "<?php echo base_url('warehouse/add')?>/"+warehouse_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_warehouse_modal').find('.modal-content').html(data.add_warehouse_modal_body);
          $('#add_warehouse_modal').modal('show');

          $('.select2bs4').select2({theme: 'bootstrap4'});
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });


    $(document).on('submit','#addWarehouseForm',function(e){
      
      e.preventDefault();

      $('#addWarehouseSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addWarehouseForm').serialize();

      var isError = false;

      $('form#addWarehouseForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addWarehouseForm  #err_"+id).text(field+ " field is required.");
            $('form#addWarehouseForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addWarehouseForm #err_"+id).text("");
            $('form#addWarehouseForm #'+id).removeClass('is-invalid');
            $('form#addWarehouseForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addWarehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('warehouse/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
            $('#add_warehouse_modal').modal('hide');
              $('form#addWarehouseForm #addWarehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              warehouseToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                $("form#addWarehouseForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#addWarehouseForm #addWarehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
            else
            {
              warehouseToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addWarehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addWarehouseForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addWarehouseForm #err_"+id).text(field+ " field is required.");
          $('form#addWarehouseForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addWarehouseForm #err_"+id).text("");
          $('form#addWarehouseForm #'+id).removeClass('is-invalid');
          $('form#addWarehouseForm #'+id).addClass('is-valid');
        }
    });

  $(document).on('show.bs.modal','#edit_warehouse_modal', function (e) {
      
      var warehouse_id = $(e.relatedTarget).data('warehouse_id');
      $('#edit_warehouse_modal').find('#id').val(warehouse_id);

      //alert(warehouse_id);

      $.ajax({
        url: "<?php echo base_url('warehouse/edit')?>/"+warehouse_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#edit_warehouse_modal').find('.modal-content').html(data.edit_warehouse_modal_body);
          $('.select2bs4').select2({theme: 'bootstrap4'});
        }
      });

    });

    $(document).on('hidden.bs.modal','#edit_warehouse_modal', function (e) {
      $('#edit_warehouse_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
     $(document).on('submit','#editWarehouseForm',function(e){
      e.preventDefault();

      $('#editWarehouseSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#editWarehouseForm').serialize();

      var isError = false;

      $('form#editWarehouseForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editWarehouseForm  #err_"+id).text(field+ " field is required.");
            $('form#editWarehouseForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editWarehouseForm #err_"+id).text("");
            $('form#editWarehouseForm #'+id).removeClass('is-invalid');
            $('form#editWarehouseForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#editWarehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('warehouse/edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#edit_warehouse_modal').modal('hide');
              $('form#editWarehouseForm #editWarehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              warehouseToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                $("form#editWarehouseForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#editWarehouseForm #editWarehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
            else
            {
              $('#editWarehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

  $(document).on('shown.bs.modal','#delete_warehouse_modal', function (e) {
    var warehouse_id = $(e.relatedTarget).data('warehouse_id');
    $('#delete_warehouse_modal').find('#id').val(warehouse_id);

      $.ajax({
      url: "<?php echo base_url('warehouse/warehouse_delete_confirmation')?>",
        type: "POST",
        data:{
        'warehouse_id': warehouse_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
        $('#delete_warehouse_modal').find('.modal-content').html(data.delete_warehouse_modal_body);
        }
      });

    });

    $(document).on('submit', '#deleteWarehouseForm' ,function (e) {
      e.preventDefault();

      $('#deleteWarehouseSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteWarehouseForm').serialize();

      $.ajax({
      url: "<?php echo base_url('warehouse/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
            $('#delete_warehouse_modal').modal('hide');
            $('form#deleteWarehouseForm #deleteWarehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            initialize_datatable();

            warehouseToast.fire({
              type: 'success',
              title: response.message
            });
            
          }
          else
          {
            warehouseToast.fire({
              type: 'error',
              title: response.message
            });            
          }

          $('#deleteWarehouseSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        }
      });
    });

    $(document).on('change', 'form#addWarehouseForm #country_id',function(){
      var id = $(this).val();
     
      $('form#addWarehouseForm #state_id').html('<option value="">Select</option>');
      $('form#addWarehouseForm #city_id').html('<option value="">Select</option>');
      $.ajax({
        url: "<?php echo base_url('utility/get_states') ?>/"+id,
        type: "GET",
        async:false,
        dataType: "JSON",
        success: function(data){
         for(i=0;i<data.length;i++){
           $('form#addWarehouseForm #state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
          }
        
        }
      });
    });

    $(document).on('change','form#addWarehouseForm #state_id',function(){
      var id = $(this).val();
     
      $('form#addWarehouseForm #city_id').html('<option value="">Select</option>');
      $.ajax({
         url: "<?php echo base_url('utility/get_cities') ?>/"+id,
         type: "GET",
         async:false,
         dataType: "JSON",
         success: function(data){
           for(i=0;i<data.length;i++){
             $('form#addWarehouseForm #city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
           }
         
         }
      });
    });

    $(document).on('change', 'form#editWarehouseForm #country_id',function(){
      var id = $(this).val();
     
      $('form#editWarehouseForm #state_id').html('<option value="">Select</option>');
      $('form#editWarehouseForm #city_id').html('<option value="">Select</option>');
      $.ajax({
        url: "<?php echo base_url('utility/get_states') ?>/"+id,
        type: "GET",
        async: false,
        dataType: "JSON",
        success: function(data){
         for(i=0;i<data.length;i++){
           $('form#editWarehouseForm #state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
          }
    
        
        }
      });
    });

    $(document).on('change','form#editWarehouseForm #state_id',function(){
      var id = $(this).val();
      
      $('form#editWarehouseForm #city_id').html('<option value="">Select</option>');
      $.ajax({
         url: "<?php echo base_url('utility/get_cities') ?>/"+id,
         type: "GET",
         async: false,
         dataType: "JSON",
         success: function(data){
           for(i=0;i<data.length;i++){
             $('form#editWarehouseForm #city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
           }
         
         }
      });
    });

  });
</script>


<!--  -->