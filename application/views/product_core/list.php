<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
  <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>

                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_add_product')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('products')?></li>
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
                <h3 class="card-title">Product</h3>
                <div class="card-tools">

                  <ul class="nav nav-pills ml-auto">

                    <?php 
                      if($this->permission_model->has_permission('edit_custom_field'))
                      {
                    ?>
                    <li class="nav-item  ml-2">
                      <a class="nav-link custom_field_modal btn-sm btn-success text-white" href="#" data-tt="tooltip" title="Click here to Edit Custom Field" data-toggle="modal" data-target="#custom_field_modal" data-module="product">
                        <i class="fas fa-cogs"></i> Custom Field
                      </a>
                    </li>
                    <?php 
                      }
                    ?>

                          <li class="nav-item ml-2">
                         <a href="<?= base_url('product/export_csv') ?>" class="nav-link btn-sm btn-warning text-white" data-tt="tooltip" title="Export All Products">
                       <i class="fas fa-file-excel"></i> Export to Excel
                         </a>
                         </li>

                    <?php 
                      if($this->permission_model->has_permission('import_product'))
                      {
                    ?>
                    <li class="nav-item  ml-2">
                      <a class="nav-link import_product_modal btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Import Product in Bulk using CSV file">
                        <i class="fas fa-file-import"></i> Import Products
                      </a>
                    </li>
                    <?php 
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_permission('edit_product'))
                      {
                    ?>
                    <li class="nav-item ml-2">
                      <a class="nav-link bulk_edit_modal btn-sm btn-info text-white" href="#" data-tt="tooltip" title="Click here to Edit Product in Bulk">
                        <i class="fas fa-pencil-alt"></i> Bulk Edit
                      </a>
                    </li>
                    <?php 
                      }
                    ?>

                    <?php 
                      if($this->permission_model->has_permission('add_product'))
                      {
                    ?>
                        <li class="nav-item ml-2">
                          <a class="nav-link add_product_modal btn-sm btn-primary text-white" href="#" data-toggle="modal" data-target="#add_product_modal" data-tt="tooltip" title="Click here to Add Product">
                            Add Product
                          </a>
                        </li>
                    <?php
                      }
                    ?>
                  </ul>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th width="2%"><input type="checkbox" class="all_product"></th>
                      <th><?=$this->lang->line('product_image')?></th>
                      <th><?=$this->lang->line('product_category_name')?></th>
                      <th>Product Code</th> 
                      <th><?=$this->lang->line('product_uom')?></th>
                      <th><?=$this->lang->line('product_manage_inventory')?></th>
                      <th><?=$this->lang->line('product_hsn')?></th>
                    <th><?=$this->lang->line('product_price').' ('.$this->session->userdata('currency_symbol').')'?></th>

                      <th><?=$this->lang->line('product_cost').' ('.$this->session->userdata('currency_symbol').')'?></th>                      
                      <th><?=$this->lang->line('product_selling_price').' ('.$this->session->userdata('currency_symbol').')'?></th>
                      <th><?=$this->lang->line('product_status').' ('.$this->session->userdata('currency_symbol').')'?></th>
                      <th width="15%"><?=$this->lang->line('product_action')?></th>   
                    </tr>
                  </thead>
                  <tbody id="product_list">
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
  <div class="modal fade" id="import_product_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="add_product_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="edit_product_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="copy_product_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="bulk_edit_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="delete_product_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

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
  <div class="modal fade" id="custom_field_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>



<div class="example-modal">
  <div class="modal fade" id="warehouse_wise_product">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header  info-header">
          <h4 class="modal-title">
            <?php echo $this->lang->line('product_quantity_warehouse_wise');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body warehouse_wise_product_data">
          
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
  function reinitialize()
  {
    $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    $('.select2bs4').select2({
      theme: 'bootstrap4'
    });
  }
</script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
document.getElementById('export_excel').addEventListener('click', function () {
  // Get the table
  var table = document.getElementById('example'); // Your table ID
  var wb = XLSX.utils.table_to_book(table, {sheet: "Products"});

  // Export it
  XLSX.writeFile(wb, 'product_list.xlsx');
});
<script type="text/javascript">
  $(document).ready(function(){

    const product_categoryToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    $(document).on("change", "form#addProductForm #upload_image",function() {

      var allowedExtensions = ["jpg", "jpeg", "png", "gif"];
      var file = this.files[0];
      var fileExtension = file.name.split(".").pop().toLowerCase();

      if ($.inArray(fileExtension, allowedExtensions) === -1) {
        // Invalid file type, show alert message and clear the file input

        Swal.fire({
          title: "Message",
          text: "Only JPG, JPEG, PNG, and GIF files are allowed.",
          // type: "warning",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          timer: 5000,
          customClass: {
            confirmButton: "btn btn-primary"
          }
        }); 

        //alert("Only JPG, JPEG, PNG, and GIF files are allowed.");
      /* $("#message").text("Only JPG, JPEG, PNG, and GIF files are allowed.");*/
        $("form#addProductForm #displayImage").hide();
        $("form#addProductForm #deleteImageButton").hide();
        $(this).val(""); // Clear the file input
        return;
      }
      // Get the file input element and the selected file
      var inputFile = this;
      var file = inputFile.files[0];

      // Create a new FormData object to send the file to the server
      var formData = new FormData();
      formData.append("upload_image", file);

      var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
      var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

      // Add the CSRF token to the form data
      formData.append(csrfTokenName, csrfTokenValue);

      // Perform the AJAX request to the server
      $.ajax({
        url: "<?php echo base_url('product_core/upload_image')?>", // Replace with your server-side script URL
        type: "POST",
        data: formData,
        dataType: "JSON",
        contentType: false,
        processData: false,
        success: function(response) {

        
          $('form#addProductForm #product_image').val(response.filename);

          var image_path = '<?=base_url('assets/product_images')?>/' + response.cid + '/product/' + response.filename;

          //var image_path = '<?=base_url('assets/product_images')?>/'+ response.filename;
          $("form#addProductForm #displayImage").show();
          $("form#addProductForm #deleteImageButton").show();
          $('form#addProductForm #displayImage').attr('src',image_path);
        
          // Handle the server response (if needed)
        },
        error: function(jqXHR, textStatus, errorThrown) {
          // Handle the error (if needed)
          alert("Error uploading image: " + errorThrown);
          //$("#displayImage").hide();
        }
      });
    });

    $(document).on("click", "form#addProductForm #deleteImageButton", function(e) {
      e.preventDefault();
      // Get the filename from the src attribute of the image
      var filename = $("form#addProductForm #displayImage").attr("src").split('/').pop();

      // Call the deleteImage function to delete the image and update the UI
      deleteImage1(filename);
    });

    function deleteImage1(filename) {
        // Send an AJAX request to delete the image file and the hidden field value
        $.ajax({
          url: "<?php echo base_url('product_core/delete_uploaded_image')?>", // Replace with your server-side script URL for deleting the image
          type: "POST",
          data: { 
              filename: filename, 
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              $('form#addProductForm #product_image').val('');
              $("form#addProductForm #displayImage").hide();
              $("form#addProductForm #deleteImageButton").hide();
              /* $("#message").text("Image deleted successfully.");*/
            } /*else {
              $("#message").text("Error deleting image.");
            }*/
          },
          error: function(jqXHR, textStatus, errorThrown) {
            /* $("#message").text("Error deleting image: " + errorThrown);*/
          }
        });
      }

      $(document).on("change", "form#editProductForm #upload_image" , function() {

        var allowedExtensions = ["jpg", "jpeg", "png", "gif"];
        var file = this.files[0];
        var fileExtension = file.name.split(".").pop().toLowerCase();

        if ($.inArray(fileExtension, allowedExtensions) === -1) {
            // Invalid file type, show alert message and clear the file input
            Swal.fire({
              title: "Message",
              text: "Only JPG, JPEG, PNG, and GIF files are allowed.",
              // type: "warning",
              buttonsStyling: !1,
              confirmButtonText: "Ok, got it!",
              timer: 5000,
              customClass: {
                confirmButton: "btn btn-primary"
              }
            }); 
          /* $("#message").text("Only JPG, JPEG, PNG, and GIF files are allowed.");*/
            $("form#editProductForm #displayImage").hide();
            $("form#editProductForm #deleteImageButton").hide();
            $(this).val(""); // Clear the file input
            return;
        }

         var inputFile = this;
        var file = inputFile.files[0];

        // Create a new FormData object to send the file to the server
        var formData = new FormData();
        formData.append("upload_image", file);

        var csrfTokenName = "<?php echo $this->security->get_csrf_token_name(); ?>"; // Change to your desired token name
        var csrfTokenValue = "<?php echo $this->security->get_csrf_hash(); ?>"; // Change to your actual CSRF token value

        // Add the CSRF token to the form data
        formData.append(csrfTokenName, csrfTokenValue);

        // Perform the AJAX request to the server
        $.ajax({
          url: "<?php echo base_url('product_core/upload_image')?>", // Replace with your server-side script URL
          type: "POST",
          data: formData,
          dataType: "JSON",
          contentType: false,
          processData: false,
          success: function(response) {

            $('form#editProductForm #product_image1').val(response.filename);
            $('form#editProductForm #product_image').val(response.filename);

            var image_path = '<?=base_url('assets/product_images')?>/' + response.cid + '/product/' + response.filename;

           // var image_path = '<?=base_url('assets/product_images')?>/'+ response.filename;
          
            $("form#editProductForm #deleteImage").show();
            $('form#editProductForm #imagePreview').attr('src',image_path);
            $("form#editProductForm #imagePreview").show();
            
          
            // Handle the server response (if needed)
          },
          error: function(jqXHR, textStatus, errorThrown) {
            // Handle the error (if needed)
            alert("Error uploading image: " + errorThrown);
            //$("#displayImage").hide();
          }
        });
      });

      $(document).on("click", "form#editProductForm #deleteImage", function(event) {
        event.preventDefault();
        // Get the filename from the src attribute of the image
        var filename = $("form#editProductForm #imagePreview").attr("src").split('/').pop();

        // Call the deleteImage function to delete the image and update the UI
        deleteImage(filename);
      });

      function deleteImage(filename) {
      // Send an AJAX request to delete the image file and the hidden field value
        $.ajax({
          url: "<?php echo base_url('product_core/delete_uploaded_image')?>", // Replace with your server-side script URL for deleting the image
          type: "POST",
          data: { 
              filename: filename, 
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            },
          dataType: "json",
          success: function(response) {
            if (response.success) {
              
              $("form#editProductForm #product_image").val("");
              $("form#editProductForm #imagePreview").attr("src", "");
              $("form#editProductForm #imagePreview").hide();
              $("form#editProductForm #deleteImage").hide();

            } 
          },
          error: function(jqXHR, textStatus, errorThrown) {
          /* $("#message").text("Error deleting image: " + errorThrown);*/
          }
        });
      }


    $('#warehouse_wise_product').on('show.bs.modal', function (e) {
      
      var product_id = $(e.relatedTarget).data('product_id');
      
      $.ajax({
        url: "<?php echo base_url('product_core/warehouse_wise_product_quantity')?>",
        type: "POST",
        data: {
            'product_id':product_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
        dataType: "JSON",
        success: function(data){

          $('.warehouse_wise_product_data').html(data.product_quantity_data);

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
          reinitialize();
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
              /* initialize_datatable();*/
              if($('form#addProductForm #product_category_id').length)
              {
                $('form#addProductForm #product_category_id').html('');
                $('form#addProductForm #product_category_id').append('<option value="">Select</option>');
                
                for(i=0;i<response['product_categories'].length;i++)
                { 
                  $('form#addProductForm #product_category_id').append('<option value="' + response['product_categories'][i].id + '">' + response['product_categories'][i].name +'</option>');
                }

                $('form#addProductForm #product_category_id').val(response['id']).attr("selected","selected");


                product_categoryToast.fire({
                  type: 'success',
                  title: response.message
                });
              }
              else
              {
                // show_message('success-header',response.message);
                product_categoryToast.fire({
                  type: 'success',
                  title: response.message
                });  

                location.reload(true);
              }
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

  });
</script>

<script type="text/javascript">

  const productToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 10000
  });

  function initialize_datatable()
  {

    // alert();
    $('#example').DataTable({ 
      
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "bDestroy": true, //Destroy before reinitialise
      "order": [],
      "pageLength": 100,//Initial no order.

      // Load data for the table's content from an Ajax source
      "ajax": {
        "url": "<?php echo site_url('product_core/ajax_list')?>",
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
          "targets": [0,10], //first column / numbering column
          "orderable": false, //set not orderable
        },
      ] 
    });
  }

  $(document).ready(function(e){

    initialize_datatable();

    $(document).on('click', ".custom_field_modal", function() {
      var module_name = $(this).data('module'); // Assuming you have a data-module attribute in your button

      $.ajax({
          url: "<?php echo base_url('custom_field/edit')?>",
          type: "GET",
          dataType: "JSON",
          data: { module_name: module_name }, // Pass the module name to the server
          success: function(data) {
              $('#custom_field_modal').find('.modal-content').html(data.custom_field_modal_body);
              $('#custom_field_modal').modal('show');

              reinitialize();
          },
          error: function(xhr, ajaxOptions, thrownError) {
              alert(xhr.status);
              alert(thrownError);
              alert(ajaxOptions);
          }
      });
    });

    $(document).on('submit', '#customfieldForm', function(e) {
      e.preventDefault();

    $('#customfieldSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled', 'disabled');
      var formData = $('#customfieldForm').serialize();
      // Check formData in console for debugging
      //console.log(formData);

      var csrfName = '<?php echo $this->security->get_csrf_token_name(); ?>';
      var csrfHash = '<?php echo $this->security->get_csrf_hash(); ?>';

      $.ajax({
          url: "<?php echo base_url('custom_field/edit')?>",
          type: "POST",
          data: formData + '&' + csrfName + '=' + csrfHash,
          dataType: "JSON",
          success: function(response) {
              if (response.code == 1) {
                  // Success response handling
                
                  $('#custom_field_modal').modal('hide');
                  $('#customfieldSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
                  // initialize_datatable();
                  productToast.fire({
                      type: 'success',
                      title: response.message
                  });
              } else {
                  // Error response handling
                  $('#customfieldSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              }
          }
      });
    });




    $(document).on('click', ".add_product_modal" ,function(){
      var product_id = $(this).data('product_id');

      $.ajax({
        url: "<?php echo base_url('product_core/add')?>/"+product_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_product_modal').find('.modal-content').html(data.add_product_modal_body);
          $('#add_product_modal').modal('show');

          reinitialize();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });


    $(document).on('submit','#addProductForm',function(e){
      
      e.preventDefault();

      $('#addProductSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addProductForm').serialize();

      var isError = false;

      $('form#addProductForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addProductForm  #err_"+id).text(field+ " field is required.");
            $('form#addProductForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addProductForm #err_"+id).text("");
            $('form#addProductForm #'+id).removeClass('is-invalid');
            $('form#addProductForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('product_core/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_product_modal').modal('hide');
              $('form#addProductForm #addProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              productToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              productToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addProductForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addProductForm #err_"+id).text(field+ " field is required.");
          $('form#addProductForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addProductForm #err_"+id).text("");
          $('form#addProductForm #'+id).removeClass('is-invalid');
          $('form#addProductForm #'+id).addClass('is-valid');
        }
    });

    $(document).on('show.bs.modal','#edit_product_modal', function (e) {
        
        var product_id = $(e.relatedTarget).data('product_id');
        $('#edit_product_modal').find('#id').val(product_id);

        $.ajax({
          url: "<?php echo base_url('product_core/edit')?>/"+product_id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            $('#edit_product_modal').find('.modal-content').html(data.edit_product_modal_body);
            $('.select2bs4').select2({theme: 'bootstrap4'});
            reinitialize();
          }
        });

    });

    $(document).on('hidden.bs.modal','#edit_product_modal', function (e) {
      $('#edit_product_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
    $(document).on('submit','#editProductForm',function(e){
      e.preventDefault();

      $('#editProductSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#editProductForm').serialize();

      var isError = false;

      $('form#editProductForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editProductForm  #err_"+id).text(field+ " field is required.");
            $('form#editProductForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editProductForm #err_"+id).text("");
            $('form#editProductForm #'+id).removeClass('is-invalid');
            $('form#editProductForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#editProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('product_core/edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#edit_product_modal').modal('hide');
              $('form#editProductForm #editProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              productToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#editProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

    //copy product

    $(document).on('show.bs.modal','#copy_product_modal', function (e) {

     
        
        var product_id = $(e.relatedTarget).data('product_id');
        $('#copy_product_modal').find('#id').val(product_id);

        $.ajax({
          url: "<?php echo base_url('product_core/copy')?>/"+product_id,
          type: "GET",
          dataType: "JSON",
          success: function(data){
            $('#copy_product_modal').find('.modal-content').html(data.copy_product_modal_body);
            $('.select2bs4').select2({theme: 'bootstrap4'});
            reinitialize();
          }
        });

    });

    $(document).on('hidden.bs.modal','#copy_product_modal', function (e) {
      $('#copy_product_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
    $(document).on('submit','#copyProductForm',function(e){
      e.preventDefault();

      $('#copyProductSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#copyProductForm').serialize();

      var isError = false;

      $('form#copyProductForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#copyProductForm  #err_"+id).text(field+ " field is required.");
            $('form#copyProductForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#copyProductForm #err_"+id).text("");
            $('form#copyProductForm #'+id).removeClass('is-invalid');
            $('form#copyProductForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#copyProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('product_core/copy')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#copy_product_modal').modal('hide');
              $('form#copyProductForm #copyProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              productToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#copyProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });


    $(document).on('shown.bs.modal','#delete_product_modal', function (e) {
      var product_id = $(e.relatedTarget).data('product_id');
      $('#delete_product_modal').find('#id').val(product_id);

        $.ajax({
        url: "<?php echo base_url('product_core/product_delete_confirmation')?>",
          type: "POST",
          data:{
          'product_id': product_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){
          $('#delete_product_modal').find('.modal-content').html(data.delete_product_modal_body);
          }
        });

      });

      $(document).on('submit', '#deleteProductForm' ,function (e) {
        e.preventDefault();

        $('#deleteProductSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
        var formData = $('#deleteProductForm').serialize();

        $.ajax({
        url: "<?php echo base_url('product_core/delete')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#delete_product_modal').modal('hide');
              $('form#deleteProductForm #deleteProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              productToast.fire({
                type: 'success',
                title: response.message
              });
              
            }
            else
            {
              productToast.fire({
                type: 'error',
                title: response.message
              });            
            }

            $('#deleteProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
          }
        });
      });
    });

    /* Bulk Edit function Begin */

    $(document).on('change', '.all_product', function() {
      if(this.checked == true)
        $('.single_product').prop('checked',true);
      else
        $('.single_product').prop('checked',false);
    });

    $(document).on('change', '.single_product', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#product_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_product          = $('.single_product').length;
      var total_checked_single_product  = $('.single_product:checked').length;
      
      if(total_checked_single_product < total_single_product && total_checked_single_product > 0){
        $('.all_product').prop('indeterminate',true); 
      }
      else if(total_checked_single_product == total_single_product){
        $('.all_product').prop('indeterminate',false);
        $('.all_product').prop('checked',true);
      }
      else if(total_checked_single_product == 0){
        $('.all_product').prop('indeterminate',false);
        $('.all_product').prop('checked',false);
      }
    }

    $(document).on('click','#product_list tr',function(event){

      var tr = $(this).closest('tr');

      if(tr.find('.single_product').is(':checked'))
      {
        tr.find('.single_product').prop('checked',false); 
      }
      else
      {
        tr.find('.single_product').prop('checked',true);  
      }

      set_select_all_checkbox_status();
    })

    $(document).on('click', ".bulk_edit_modal" ,function(event){
      event.preventDefault();

      var total_checked_single_product  = $('.single_product:checked').length;

      if(total_checked_single_product > 0){

        var product_id_array = new Array();

        // get all checked product
        $(".single_product").each(function() {
          if($(this).is(':checked')){
            var closestTr = $(this).closest('tr');
            product_id_array.push(closestTr.find('#product_id').val());
          }
        });

        $.ajax({
          url: "<?php echo base_url('product_core/bulk_edit')?>",
          type: "GET",
          dataType: "JSON",
          success: function(data){
            $('#bulk_edit_modal').find('.modal-content').html(data.bulk_edit_modal_body);
            $('#bulk_edit_modal').find('#product_ids').val(product_id_array.join(","));
            $('#bulk_edit_modal').modal('show');

            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
          },
          error: function (xhr, ajaxOptions, thrownError) {
            // alert(xhr.status);
            show_message('failure-header',thrownError);
            // alert(thrownError);
            // alert(ajaxOptions);
          }
        });
      }
      else
      {
        show_message('failure-header','Please select at least one Product.');
      }
    });

    $(document).on('submit','form#bulkEditForm',function(event){
      event.preventDefault();

      $('form#bulkEditForm #bulkEditSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('form#bulkEditForm').serialize();

      var isError = false;

      $('form#bulkEditForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#bulkEditForm  #err_"+id).text(field+ " field is required.");
            $('form#bulkEditForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#bulkEditForm #err_"+id).text("");
            $('form#bulkEditForm #'+id).removeClass('is-invalid');
            $('form#bulkEditForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#bulkEditSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
          url: "<?php echo base_url('product_core/bulk_edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#bulk_edit_modal').modal('hide');
              $('form#bulkEditForm #bulkEditSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');

              initialize_datatable();
              
              productToast.fire({
                type: 'success',
                title: response.message
              });

             
            }
            else
            {
              productToast.fire({
                type: 'error',
                title: response.message
              });
              $('form#bulkEditForm #bulkEditSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

    /* Bulk Edit function End */




    /* Import product using CSV function Begin */

    $(document).on('click', ".import_product_modal" ,function(event){
      event.preventDefault();

      $.ajax({
        url: "<?php echo base_url('product_core/import_product')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#import_product_modal').find('.modal-content').html(data.import_product_modal_body);
          $('#import_product_modal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // alert(xhr.status);
          show_message('failure-header',thrownError);
          // alert(thrownError);
          // alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','form#importProductForm',function(event){
      // event.preventDefault();

      $('form#importProductForm #importProductSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('form#importProductForm').serialize();

      var isError = false;

      $('form#importProductForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#importProductForm  #err_"+id).text(field+ " field is required.");
            $('form#importProductForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#importProductForm #err_"+id).text("");
            $('form#importProductForm #'+id).removeClass('is-invalid');
            $('form#importProductForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('form#importProductForm #importProductSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        return true;
      }
    });

    /* Import product using CSV function End */
</script>