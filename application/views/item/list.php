<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('item')?>"><?=$this->lang->line('header_item')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_item')?></li>
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
                <h3 class="card-title">Item</h3>
                <?php 
                if($this->permission_model->has_permission('add_item'))
                  {
                ?>
                <div class="card-tools">
                <button type="button" class="btn btn-block btn-primary btn-sm add_item_modal" data-toggle="modal" data-target="#add_item_modal" data-tt="tooltip" title="Click here to Add Item">Add Item</button>
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
                      <th><?=$this->lang->line("item_name")?></th>
                      <th><?=$this->lang->line("item_description")?></th>
                      <th><?=$this->lang->line('item_tax')?></th>
                      <th width="15%"><?=$this->lang->line("item_action")?></th>   
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
  <div class="modal fade" id="add_item_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
<div class="modal fade" id="edit_item_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
<div class="modal fade" id="delete_item_modal" data-backdrop="static" data-keyboard="false">
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

     const itemToast = Swal.mixin({
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
          "url": "<?php echo site_url('item/ajax_list')?>",
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
            "targets": [ 3 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
     });
    }

  $(document).on('click', ".add_item_modal" ,function(){
   
      $.ajax({
      url: "<?php echo base_url('item/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_item_modal').find('.modal-content').html(data.add_item_modal_body);
          $('#add_item_modal').modal('show');
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });


    $(document).on('submit','#addItemForm',function(e){
      
      e.preventDefault();

      $('#addItemSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addItemForm').serialize();

      var isError = false;

      $('form#addItemForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addItemForm  #err_"+id).text(field+ " field is required.");
            $('form#addItemForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addItemForm #err_"+id).text("");
            $('form#addItemForm #'+id).removeClass('is-invalid');
            $('form#addItemForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addItemSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('item/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
            $('#add_item_modal').modal('hide');
              $('form#addItemForm #addItemSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              itemToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              itemToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addItemSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addItemForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addItemForm #err_"+id).text(field+ " field is required.");
          $('form#addItemForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addItemForm #err_"+id).text("");
          $('form#addItemForm #'+id).removeClass('is-invalid');
          $('form#addItemForm #'+id).addClass('is-valid');
        }
    });

  $(document).on('show.bs.modal','#edit_item_modal', function (e) {
      
      var item_id = $(e.relatedTarget).data('item_id');
      $('#edit_item_modal').find('#id').val(item_id);

      $.ajax({
        url: "<?php echo base_url('item/edit')?>/"+item_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#edit_item_modal').find('.modal-content').html(data.edit_item_modal_body);
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
        }
      });

    });

    $(document).on('hidden.bs.modal','#edit_item_modal', function (e) {
      $('#edit_item_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
     $(document).on('submit','#editItemForm',function(e){
      e.preventDefault();

      $('#editItemSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#editItemForm').serialize();

      var isError = false;

      $('form#editItemForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editItemForm  #err_"+id).text(field+ " field is required.");
            $('form#editItemForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editItemForm #err_"+id).text("");
            $('form#editItemForm #'+id).removeClass('is-invalid');
            $('form#editItemForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#editItemSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('item/edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#edit_item_modal').modal('hide');
              $('form#editItemForm #editItemSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              itemToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#editItemSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

  $(document).on('shown.bs.modal','#delete_item_modal', function (e) {
    var item_id = $(e.relatedTarget).data('item_id');
    $('#delete_item_modal').find('#id').val(item_id);

      $.ajax({
      url: "<?php echo base_url('item/item_delete_confirmation')?>",
        type: "POST",
        data:{
        'item_id': item_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
        $('#delete_item_modal').find('.modal-content').html(data.delete_item_modal_body);
        }
      });

    });

    $(document).on('submit', '#deleteItemForm' ,function (e) {
      e.preventDefault();

      $('#deleteItemSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteItemForm').serialize();

      $.ajax({
      url: "<?php echo base_url('item/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
            $('#delete_item_modal').modal('hide');
            $('form#deleteItemForm #deleteItemSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            initialize_datatable();

            itemToast.fire({
              type: 'success',
              title: response.message
            });
            
          }
          else
          {
            itemToast.fire({
              type: 'error',
              title: response.message
            });            
          }

          $('#deleteItemSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        }
      });
    });
  });
</script>


<!--  -->