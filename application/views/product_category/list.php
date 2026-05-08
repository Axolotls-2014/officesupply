<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
  <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>

                <li class="breadcrumb-item "><a href="<?=base_url('product_category')?>"><?=$this->lang->line('header_product_category')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_product_category')?></li>
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
                <h3 class="card-title">Product Category</h3>
                <div class="card-tools">
                  <ul class="nav nav-pills ml-auto">

                    <?php 
                      if($this->permission_model->has_permission('import_product_category'))
                      {
                    ?>
                    <li class="nav-item">
                      <a class="nav-link import_product_category_modal btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Import Product Category in Bulk using CSV file">
                        <i class="fas fa-file-import"></i> Import Product Category
                      </a>
                    </li>
                    <?php 
                      }
                    ?>
                    <?php 
                      if($this->permission_model->has_permission('add_product_category'))
                      {
                    ?>
                      <li class="nav-item ml-2">
                        <a href="#" class="nav-link btn-primary btn-sm add_product_category_modal text-white" data-toggle="modal" data-target="#add_product_category_modal" data-tt="tooltip" title="Click here to Add Product Category"><i class="fas fa-user-friends mr-2"></i><?=$this->lang->line('product_category_add')?></a>
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
                      <th><?=$this->lang->line("product_category_name")?></th>
                      <th><?=$this->lang->line("product_category_description")?></th>
                      <th><?=$this->lang->line('product_category_tax_name')?></th>
                      <th width="15%"><?=$this->lang->line("product_category_action")?></th>   
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

<?php 
  $this->load->view('layout/footer');
?>


<div class="example-modal">
  <div class="modal fade" id="add_product_category_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="edit_product_category_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="add_tax_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="delete_product_category_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="import_product_category_modal" data-backdrop="static" data-keyboard="false">
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

    const product_categoryToast = Swal.mixin({
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
          "url": "<?php echo site_url('product_category/ajax_list')?>",
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

    $(document).on('click', ".add_tax_modal" ,function(){

     $.ajax({
        url: "<?php echo base_url('tax/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_tax_modal').find('.modal-content').html(data.add_tax_modal_body);
          $('#add_tax_modal').modal('show');
          $('.select2bs4').select2({theme: 'bootstrap4'});
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });


    $(document).on('submit','#addTaxForm',function(e){
      
      e.preventDefault();

      $('#taxSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addTaxForm').serialize();

      var isError = false;

      $('form#addTaxForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addTaxForm  #err_"+id).text(field+ " field is required.");
            $('form#addTaxForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addTaxForm #err_"+id).text("");
            $('form#addTaxForm #'+id).removeClass('is-invalid');
            $('form#addTaxForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        var formData = $('#addTaxForm').serialize();
        $('#taxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('tax/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
              {
                $('#add_tax_modal').modal('hide');
                /*$('#add_product_category_modal').modal('hide');*/
                $('#taxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
                
                if($('form#addProduct_categoryForm #tax_id').length)
                {
                  $('form#addProduct_categoryForm #tax_id').html('');
                  $('form#addProduct_categoryForm #tax_id').append('<option value="">Select</option>');
                  
                  for(i=0;i<response['taxes'].length;i++)
                  { 
                    $('form#addProduct_categoryForm #tax_id').append('<option value="' + response['taxes'][i].id + '">' + response['taxes'][i].tax_name + ' ( I : ' + response['taxes'][i].igst + ' | C : ' +response['taxes'][i].cgst + ' | S : ' + response['taxes'][i].sgst + ' ) ' +'</option>');

                    /*<?=$value->tax_name.' ( I : '.$value->igst.' | C : '.$value->cgst.' | S : '.$value->sgst.' )'?>*/
                  }

                  $('form#addProduct_categoryForm #tax_id').val(response['id']).attr("selected","selected");  

                  // show_message('success-header',response.message);
                  TaxToast.fire({
                    type: 'success',
                    title: response.message
                  });  

                }
                else
                {
                  // show_message('success-header',response.message);
                  TaxToast.fire({
                    type: 'success',
                    title: response.message
                  });  

                  location.reload(true);
                }
              }
            else
            {
              TaxToast.fire({
                type: 'error',
                title: response.message
              });
              $('#taxSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addTaxForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addTaxForm #err_"+id).text(field+ " field is required.");
          $('form#addTaxForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addTaxForm #err_"+id).text("");
          $('form#addTaxForm #'+id).removeClass('is-invalid');
          $('form#addTaxForm #'+id).addClass('is-valid');
        }
    });

    $(document).on('show.bs.modal','#edit_product_category_modal', function (e) {
        
        var product_category_id = $(e.relatedTarget).data('product_category_id');
        $('#edit_product_category_modal').find('#id').val(product_category_id);

        $.ajax({
          url: "<?php echo base_url('product_category/edit')?>/"+product_category_id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            $('#edit_product_category_modal').find('.modal-content').html(data.edit_product_category_modal_body);
          }
        });

    });

    $(document).on('click', ".add_product_category_modal" ,function(){
      
      $.ajax({
        url: "<?php echo base_url('product_category/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_product_category_modal').find('.modal-content').html(data.add_product_category_modal_body);
          $('#add_product_category_modal').modal('show');
          $('.select2bs4').select2({theme: 'bootstrap4'});
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });


    $(document).on('submit','#addProduct_categoryForm',function(e){
      
      e.preventDefault();

      $('#addProduct_categorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addProduct_categoryForm').serialize();

      var isError = false;

      $('form#addProduct_categoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addProduct_categoryForm  #err_"+id).text(field+ " field is required.");
            $('form#addProduct_categoryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addProduct_categoryForm #err_"+id).text("");
            $('form#addProduct_categoryForm #'+id).removeClass('is-invalid');
            $('form#addProduct_categoryForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('product_category/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
            $('#add_product_category_modal').modal('hide');
              $('form#addProduct_categoryForm #addProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              product_categoryToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              product_categoryToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addProduct_categoryForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addProduct_categoryForm #err_"+id).text(field+ " field is required.");
          $('form#addProduct_categoryForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addProduct_categoryForm #err_"+id).text("");
          $('form#addProduct_categoryForm #'+id).removeClass('is-invalid');
          $('form#addProduct_categoryForm #'+id).addClass('is-valid');
        }
    });

    $(document).on('show.bs.modal','#edit_product_category_modal', function (e) {
        
        var product_category_id = $(e.relatedTarget).data('product_category_id');
        $('#edit_product_category_modal').find('#id').val(product_category_id);

        $.ajax({
          url: "<?php echo base_url('product_category/edit')?>/"+product_category_id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            $('#edit_product_category_modal').find('.modal-content').html(data.edit_product_category_modal_body);
            $('.select2bs4').select2({theme: 'bootstrap4'});
          }
        });

    });

    $(document).on('hidden.bs.modal','#edit_product_category_modal', function (e) {
      $('#edit_product_category_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
    $(document).on('submit','#editProduct_categoryForm',function(e){
      e.preventDefault();

      $('#editProduct_categorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#editProduct_categoryForm').serialize();

      var isError = false;

      $('form#editProduct_categoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editProduct_categoryForm  #err_"+id).text(field+ " field is required.");
            $('form#editProduct_categoryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editProduct_categoryForm #err_"+id).text("");
            $('form#editProduct_categoryForm #'+id).removeClass('is-invalid');
            $('form#editProduct_categoryForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#editProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('product_category/edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#edit_product_category_modal').modal('hide');
              $('form#editProduct_categoryForm #editProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              product_categoryToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#editProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on('shown.bs.modal','#delete_product_category_modal', function (e) {
      var product_category_id = $(e.relatedTarget).data('product_category_id');
      $('#delete_product_category_modal').find('#id').val(product_category_id);

        $.ajax({
        url: "<?php echo base_url('product_category/product_category_delete_confirmation')?>",
          type: "POST",
          data:{
          'product_category_id': product_category_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){
          $('#delete_product_category_modal').find('.modal-content').html(data.delete_product_category_modal_body);
          }
        });

      });

      $(document).on('submit', '#deleteProduct_categoryForm' ,function (e) {
        e.preventDefault();

        $('#deleteProduct_categorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
        var formData = $('#deleteProduct_categoryForm').serialize();

        $.ajax({
        url: "<?php echo base_url('product_category/delete')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#delete_product_category_modal').modal('hide');
              $('form#deleteProduct_categoryForm #deleteProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              product_categoryToast.fire({
                type: 'success',
                title: response.message
              });
              
            }
            else
            {
              product_categoryToast.fire({
                type: 'error',
                title: response.message
              });            
            }

            $('#deleteProduct_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
          }
        });
      });
    });


    /* Import product using CSV function Begin */

    $(document).on('click', ".import_product_category_modal" ,function(event){
      event.preventDefault();

      $.ajax({
        url: "<?php echo base_url('product_category/import_product_category')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#import_product_category_modal').find('.modal-content').html(data.import_product_category_modal_body);
          $('#import_product_category_modal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // alert(xhr.status);
          show_message('failure-header',thrownError);
          // alert(thrownError);
          // alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','form#importProductCategoryForm',function(event){
      // event.preventDefault();

      $('form#importProductCategoryForm #importProductCategorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('form#importProductCategoryForm').serialize();

      var isError = false;

      $('form#importProductCategoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#importProductCategoryForm  #err_"+id).text(field+ " field is required.");
            $('form#importProductCategoryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#importProductCategoryForm #err_"+id).text("");
            $('form#importProductCategoryForm #'+id).removeClass('is-invalid');
            $('form#importProductCategoryForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('form#importProductCategoryForm #importProductCategorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        return true;
      }
    });

    /* Import product using CSV function End */
</script>


<!--  -->