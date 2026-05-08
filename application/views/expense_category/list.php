<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('expense_category')?>"><?=$this->lang->line('header_expense_category')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_expense_category')?></li>
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
                <h3 class="card-title">Expense Category</h3>
                <?php 
                if($this->permission_model->has_permission('add_expense_category'))
                  {
                ?>
                <div class="card-tools">
                <button type="button" class="btn btn-block btn-primary btn-sm add_expense_category_modal" data-toggle="modal" data-target="#add_expense_category_modal" data-tt="tooltip" title="Click here to Add Expense Category">Add Expense Category</button>
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
                      <th><?=$this->lang->line("expense_category_name")?></th>
                      <th><?=$this->lang->line("expense_category_description")?></th>
                      <th><?=$this->lang->line('expense_category_type')?></th>
                      <th width="15%"><?=$this->lang->line("expense_category_action")?></th>   
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
<div class="modal fade" id="add_expense_category_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
<div class="modal fade" id="edit_expense_category_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
<div class="modal fade" id="delete_expense_category_modal" data-backdrop="static" data-keyboard="false">
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

     const expense_categoryToast = Swal.mixin({
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
          "url": "<?php echo site_url('expense_category/ajax_list')?>",
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

  $(document).on('click', ".add_expense_category_modal" ,function(){
   
      $.ajax({
      url: "<?php echo base_url('expense_category/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_expense_category_modal').find('.modal-content').html(data.add_expense_category_modal_body);
          $('#add_expense_category_modal').modal('show');
          $('.select2bs4').select2({theme: 'bootstrap4'});

        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });


    $(document).on('submit','#addExpense_categoryForm',function(e){
      
      e.preventDefault();

      $('#addExpense_categorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addExpense_categoryForm').serialize();

      var isError = false;

      $('form#addExpense_categoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addExpense_categoryForm  #err_"+id).text(field+ " field is required.");
            $('form#addExpense_categoryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addExpense_categoryForm #err_"+id).text("");
            $('form#addExpense_categoryForm #'+id).removeClass('is-invalid');
            $('form#addExpense_categoryForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addExpense_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('expense_category/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
            $('#add_expense_category_modal').modal('hide');
              $('form#addExpense_categoryForm #addExpense_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              expense_categoryToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              expense_categoryToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addExpense_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addExpense_categoryForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addExpense_categoryForm #err_"+id).text(field+ " field is required.");
          $('form#addExpense_categoryForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addExpense_categoryForm #err_"+id).text("");
          $('form#addExpense_categoryForm #'+id).removeClass('is-invalid');
          $('form#addExpense_categoryForm #'+id).addClass('is-valid');
        }
    });

  $(document).on('show.bs.modal','#edit_expense_category_modal', function (e) {
      
      var expense_category_id = $(e.relatedTarget).data('expense_category_id');
      $('#edit_expense_category_modal').find('#id').val(expense_category_id);

      $.ajax({
        url: "<?php echo base_url('expense_category/edit')?>/"+expense_category_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#edit_expense_category_modal').find('.modal-content').html(data.edit_expense_category_modal_body);
          $('.select2bs4').select2({theme: 'bootstrap4'});

        }
      });

    });

    $(document).on('hidden.bs.modal','#edit_expense_category_modal', function (e) {
      $('#edit_expense_category_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
     $(document).on('submit','#editExpense_categoryForm',function(e){
      e.preventDefault();

      $('#editExpense_categorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#editExpense_categoryForm').serialize();

      var isError = false;

      $('form#editExpense_categoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editExpense_categoryForm  #err_"+id).text(field+ " field is required.");
            $('form#editExpense_categoryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editExpense_categoryForm #err_"+id).text("");
            $('form#editExpense_categoryForm #'+id).removeClass('is-invalid');
            $('form#editExpense_categoryForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#editExpense_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('expense_category/edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#edit_expense_category_modal').modal('hide');
              $('form#editExpense_categoryForm #editExpense_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              expense_categoryToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#editExpense_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

  $(document).on('shown.bs.modal','#delete_expense_category_modal', function (e) {
    var expense_category_id = $(e.relatedTarget).data('expense_category_id');
    $('#delete_expense_category_modal').find('#id').val(expense_category_id);

      $.ajax({
      url: "<?php echo base_url('expense_category/expense_category_delete_confirmation')?>",
        type: "POST",
        data:{
        'expense_category_id': expense_category_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
        $('#delete_expense_category_modal').find('.modal-content').html(data.delete_expense_category_modal_body);
        }
      });

    });

    $(document).on('submit', '#deleteExpense_categoryForm' ,function (e) {
      e.preventDefault();

      $('#deleteExpense_categorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteExpense_categoryForm').serialize();

      $.ajax({
      url: "<?php echo base_url('expense_category/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
            $('#delete_expense_category_modal').modal('hide');
            $('form#deleteExpense_categoryForm #deleteExpense_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            initialize_datatable();

            expense_categoryToast.fire({
              type: 'success',
              title: response.message
            });
            
          }
          else
          {
            expense_categoryToast.fire({
              type: 'error',
              title: response.message
            });            
          }

          $('#deleteExpense_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        }
      });
    });
  });
</script>


<!--  -->