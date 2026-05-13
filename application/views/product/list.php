<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
  <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>

                <li class="breadcrumb-item "><a href="<?=base_url('product')?>"><?=$this->lang->line('header_all_product')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_all_product')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="card card-warning card-outline">
              <div class="card-body">
                <div class="row">
<div class="col-md-2">
    <label>Quantity</label>
    <select class="form-control form-control-sm select2bs4" id="quantity">
        <option value="<?=QUANTITY_ALL?>">ALL</option>
        <option value="<?=QUANTITY_GREATER_THEN_ZERO?>">Greater than Zero</option>
        <option value="<?=QUANTITY_ZERO?>">Zero</option>
      <!--  <option value="<?=QUANTITY_BELOW_ZERO?>">Below 0</option>-->
        <option value="<?=QUANTITY_NEGATIVE?>">Negative</option>
    </select>
</div>
                <?php 
                    $user_id = $this->session->userdata('user_id');
                    $user = $this->db->get_where('users', ['id' => $user_id])->row();
                
                    $user_branch_id = isset($user->branch_id) ? $user->branch_id : null;
                    $warehouse_name = '';
                
                    if ($user_branch_id) {
                        foreach ($warehouses as $value) {
                            if ($value->id == $user_branch_id) {
                                $warehouse_name = $value->name;
                                break;
                            }
                        }
                    }
                ?>
                
                <div class="col-md-2">
                    <label>Branch</label>
                    <select class="form-control form-control-sm select2bs4" id="warehouse_id" name="w_id" <?= $user_branch_id ? 'disabled' : '' ?>>
                            <option value="">All Branches</option>  <!-- Add this option -->
   
                    <?php if ($user_branch_id && $warehouse_name): ?>
                            
                            <option value="<?= $user_branch_id ?>" selected><?= $warehouse_name ?></option>
                        <?php else: ?>
                         
                            <?php 
                                $first_warehouse = true;
                                foreach ($warehouses as $value): 
                            ?>
                                <option value="<?= $value->id ?>" 
                                    <?= ($first_warehouse) ? 'selected' : '' ?>
                                    <?= ($value->is_default == WAREHOUSE_IS_DEFAULT_YES && !$first_warehouse) ? 'selected' : '' ?>>
                                    <?= $value->name ?>
                                </option>
                                <?php 
                                    $first_warehouse = false;
                                endforeach; 
                            ?>
                        <?php endif; ?>
                    </select>
                </div>
                  <div class="col-md-2">
                    <label>Products</label>
                    <select class="form-control form-control-sm select2bs4" id="product_id" name="p_id"> 
                        <option option value="">All Product</option>
                        <?php 
                          foreach ($products as $value) {
                        ?>
                            <option value="<?=$value->id?>"><?=$value->name?></option>
                        <?php
                          }
                        ?>
                      </select>
                  </div>

                  <div class="col-md-2">
                    <label>Status</label>
                    <select class="form-control form-control-sm select2bs4" id="product_status">
                      <option value="">All</option>
                      <option value="<?=PRODUCT_STATUS_ACTIVE?>">Active</option>
                      <option value="<?=PRODUCT_STATUS_INACTIVE?>">Inactive</option>
                    </select>
                  </div>

                  <div class="col-md-2">
                    <label>Manage Inventory</label>
                    <select class="form-control form-control-sm select2bs4" name="manage_inventory" id="manage_inventory">
                      <option value="">ALL</option>
                      <option value="<?=MANAGE_INVENTORY_YES?>"><?=clean_e_val(MANAGE_INVENTORY_YES)?></option>
                      <option value="<?=MANAGE_INVENTORY_NO?>"><?=clean_e_val(MANAGE_INVENTORY_NO)?></option>
                    </select>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <!-- /.col -->
        </div>
      <!-- /.row -->
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
                      $warehouse = $this->warehouse_model->get_record_by_is_default();
                    ?>
                    <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=($warehouse != '') ? $warehouse->id : ''?>">
                    <li class="nav-item  ml-2">
                      <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Product">
                        <i class="fas fa-share"></i> Export
                      </a>
                    </li>
                    <li class="nav-item ml-2">
                        <!-- Back Button - Hidden by default -->
                        <a class="nav-link btn-sm btn-info text-white" href="javascript:void(0)" id="btn_back_to_list" style="display:none;">
                            <i class="fas fa-arrow-left"></i> Back to Product List
                        </a>
                    </li>
                    <!-- <?php 
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
                    ?> -->

                    <!-- <?php 
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
                    ?> -->

                    <!-- <?php 
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
                    ?> -->
                  </ul>
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                 <thead>
    <tr>
        <th width="2%"><input type="checkbox" class="all_product"></th>
        <th>Branch</th>
        <th>Product Name</th>
        <th>HSN</th>
        <th>Available Qty</th>
        <th>Batch No</th>
        <th>UOM</th>
        <th>Alert Qty</th>
        <th>MRP (<?=$this->session->userdata('currency_symbol')?>)</th>
        <th>Purchase Price (<?=$this->session->userdata('currency_symbol')?>)</th>
        <th>Taxable Value (<?=$this->session->userdata('currency_symbol')?>)</th>
        <th>Total Amount (<?=$this->session->userdata('currency_symbol')?>)</th>
        <th>Status</th>
        <th width="15%">Action</th>   
    </tr>
</thead>
                  <tbody id="product_list">
                  </tbody>
<tfoot align="right">
    <tr>
        <th style="text-align: left" colspan="4"></th>
        <th style="text-align: left"></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th></th>
        <th style="text-align: left !important"></th>
        <th style="text-align: left !important"></th>
        <th></th>
        <th></th>
    </tr>
</tfoot>
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

<div class="modal fade" id="add_stock_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


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


<script type="text/javascript">
var current_view = 'list'; // Global flag: 'list' or 'history'
var selected_product_id = '';

$(document).ready(function() {
    // Initial Load
    initialize_datatable();

    // 1. Click on Product Name to show History
   $(document).on('click', '.view_history', function(e) {
        e.preventDefault();
        selected_product_id = $(this).data('id');
        current_view = 'history';
    
        $('#btn_back_to_list').show();
        $('.export').hide();
    
        // 1. KILL the old table completely
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
        }
    
        // 2. WIPE the HTML and put new History headers
        update_table_headers('history');
    
        // 3. NOW load the data
        initialize_datatable();
    });

    // 2. Click Back Button
  $(document).on('click', '#btn_back_to_list', function() {
        current_view = 'list';
        selected_product_id = '';
        
        $('#btn_back_to_list').hide();
        $('.export').show();
    
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
        }
    
        update_table_headers('list'); // Switch back to 14 columns
        initialize_datatable();
    });

});

function update_table_headers(mode) {
    var headContent = "";
    var footContent = "";

    if (mode === 'history') {
        headContent = `
            <tr>
                <th>Date</th><th>Type</th><th>Ref No</th><th>PID</th>
                <th>Product Name</th><th>HSN</th><th>UOM</th><th>Alert</th>
                <th>MRP</th><th>Pur. Price</th><th>Sale Price</th><th>In Qty</th>
                <th>Out Qty</th><th>Closing</th><th>Value</th><th>Status</th>
            </tr>`;
        // footContent = ""; // No footer for history
        footContent = `
            <tr>
                <th colspan="13" style="text-align:right">Total Closing Value:</th>
                <th id="hist_total_stock"></th>
                <th id="hist_total_value"></th>
                <th></th>
            </tr>`;
    } else {
        headContent = `
            <tr>
                <th width="2%"><input type="checkbox" class="all_product"></th>
                <th>Branch</th>
                <th>Product Name</th>
                <th>HSN</th>
                <th>Available Qty</th>
                <th>Batch No</th>
                <th>UOM</th>
                <th>Alert Qty</th>
                <th>MRP</th>
                <th>Purchase Price</th>
                <th>Taxable Value</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th width="15%">Action</th>   
            </tr>`;
        footContent = `
            <tr><th colspan="4" style="text-align: left">Total</th><th style="text-align: left"></th><th></th><th></th><th></th><th></th><th></th><th style="text-align: left !important"></th><th style="text-align: left !important"></th><th></th><th></th></tr>`;
    }

    // UPDATE ONLY THE INNER HTML OF THEAD AND TFOOT
    $('#example thead').html(headContent);
    $('#example tfoot').html(footContent);
    $('#example tbody').html(''); // Clear rows so it looks clean while loading
}

function initialize_datatable() {
    var ajax_url = (current_view === 'list') ? "<?php echo site_url('product/ajax_list')?>" : "<?php echo site_url('product/ajax_list_stock')?>";
    
    var table = $('#example').DataTable({ 
        "processing": true,
        "serverSide": true,
        "bDestroy": true,
        "autoWidth": false, 
        "scrollX": true, 
        "order": [],
        "ajax": {
            "url": ajax_url,
            "type": "POST",
            "data": function(d) {
                d.warehouse_id = $('#warehouse_id').val();
                d.product_id = (current_view === 'history') ? selected_product_id : $('#product_id').val();
                d.product_status = $('#product_status').val();
                d.quantity = $('#quantity').val();
                d.manage_inventory = $('#manage_inventory').val();
                d.<?php echo $this->security->get_csrf_token_name(); ?> = '<?php echo $this->security->get_csrf_hash(); ?>';
            }
        },
        "initComplete": function(settings, json) {
            // THIS LINE FIXES THE ALIGNMENT AND HEADER VISIBILITY
            this.api().columns.adjust();
            $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
        },
        "footerCallback": function (row, data, start, end, display) {
            var api = this.api();
            var intVal = function (i) { 
                return typeof i === 'string' ? i.replace(/[\$,]|<b>|<\/b>/g, '')*1 : typeof i === 'number' ? i : 0; 
            };

            if (current_view === 'list') {
                // Calculation for All Products List
                var qty = api.column(4).data().reduce(function (a, b) { return intVal(a) + intVal(b); }, 0);
                var taxable = api.column(10).data().reduce(function (a, b) { return intVal(a) + intVal(b); }, 0);
                var total = api.column(11).data().reduce(function (a, b) { return intVal(a) + intVal(b); }, 0);

                $(api.column(4).footer()).html(qty.toLocaleString("en-US"));
                $(api.column(10).footer()).html(taxable.toLocaleString("en-US"));
                $(api.column(11).footer()).html(total.toLocaleString("en-US"));
            } 
            else {
                // Calculation for History Mode
                // Column 13: Closing Stock, Column 14: Closing Value
                // var totalValue = api.column(14).data().reduce(function (a, b) { return intVal(a) + intVal(b); }, 0);
                
                // // Since we reversed the data for display, the "Closing Stock" total 
                // // in the footer is usually just the latest stock (top row)
                // var latestStock = intVal(data[0][13]); 

                // $(api.column(13).footer()).html("<b>" + latestStock.toLocaleString("en-US") + "</b>");
                // $(api.column(14).footer()).html("<b>" + totalValue.toLocaleString("en-US", {minimumFractionDigits: 2}) + "</b>");
                var latestStock = (data.length > 0) ? intVal(data[0][13]) : 0; 
    var latestValue = (data.length > 0) ? intVal(data[0][14]) : 0; 

    // Update the footer with the LATEST balance, not the sum of all rows
    $(api.column(13).footer()).html("<b>" + latestStock.toLocaleString("en-US") + "</b>");
    $(api.column(14).footer()).html("<b>" + latestValue.toLocaleString("en-US", {minimumFractionDigits: 2}) + "</b>");
            }
        }
    });
}

  $(document).ready(function(){

    const product_categoryToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    $('#warehouse_wise_product').on('show.bs.modal', function (e) {
      
      var product_id = $(e.relatedTarget).data('product_id');
      
      $.ajax({
        url: "<?php echo base_url('product/warehouse_wise_product_quantity')?>",
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

  $(document).ready(function(e){

    initialize_datatable();
    /*function initialize_datatable()
    {

      var product_id          = $('#product_id').val();
      var warehouse_id        = $('#warehouse_id').val();
      var product_status      = $('#product_status').val();
      var quantity            = $('#quantity').val();
      var manage_inventory    = $('#manage_inventory').val();

      

      $('#example').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [],
        "pageLength": 100, //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('product/ajax_list')?>",
            "type": "POST",
            "data":  {
              'warehouse_id' : warehouse_id,
              'product_id' : product_id,
              'product_status' : product_status,
              'quantity' : quantity,
              'manage_inventory' : manage_inventory,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 0,11 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],

        "footerCallback": function ( row, data, start, end, display ) {
            var api = this.api(), data;
 
            // converting to interger to find total
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
 
            // computing column Total of the complete result 
            var quantityTotal = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
            // var costTotal = api
            //     .column( 7 )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
        
            var totalcostTotal = api
                .column( 9 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
            // var priceTotal = api
            //     .column( 9 )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );

            var totalpriceTotal = api
                .column( 8 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        
            // var friTotal = api
            //     .column( 5 )
            //     .data()
            //     .reduce( function (a, b) {
            //         return intVal(a) + intVal(b);
            //     }, 0 );
      
        
            // Update footer by showing the total with the reference of the column index 
            $( api.column( 0 ).footer() ).html('Total');
            $( api.column( 4 ).footer() ).html(quantityTotal.toLocaleString("en-US"));
            // $( api.column( 7 ).footer() ).html(costTotal.toLocaleString("en-US"));
            $( api.column( 9 ).footer() ).html(totalcostTotal.toLocaleString("en-US"));
            // $( api.column( 9 ).footer() ).html(priceTotal.toLocaleString("en-US"));
            $( api.column( 8 ).footer() ).html(totalpriceTotal.toLocaleString("en-US"));
            // $( api.column( 5 ).footer() ).html(friTotal);
        },
      });
    }*/
// function initialize_datatable()
// {
//     var product_id          = $('#product_id').val();
//     var warehouse_id        = $('#warehouse_id').val();  // This will be empty for "All Branches"
//     var product_status      = $('#product_status').val();
//     var quantity            = $('#quantity').val();
//     var manage_inventory    = $('#manage_inventory').val();

//     $('#example').DataTable({ 
//         "processing": true,
//         "serverSide": true,
//         "bDestroy": true,
//         "order": [],
//         "pageLength": 100,
 
//         "ajax": {
//             "url": "<?php echo site_url('product/ajax_list')?>",
//             "type": "POST",
//             "data": {
//                 'warehouse_id' : warehouse_id,  // Empty value = All Branches
//                 'product_id' : product_id,
//                 'product_status' : product_status,
//                 'quantity' : quantity,
//                 'manage_inventory' : manage_inventory,
//                 '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
//             }
//         },

//         'initComplete':function(settings, json){
//             $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
//         },  
 
//         "columnDefs": [
//             { 
//                 "targets": [0, 11],
//                 "orderable": false,
//             },
//         ],

// "footerCallback": function (row, data, start, end, display) {
//     var api = this.api(), data;
 
//     var intVal = function (i) {
//         return typeof i === 'string' ?
//             i.replace(/[\$,]/g, '')*1 :
//             typeof i === 'number' ?
//                 i : 0;
//     };
 
//     // Computing totals
//     var availableQtyTotal = api
//         .column(4)
//         .data()
//         .reduce(function (a, b) {
//             return intVal(a) + intVal(b);
//         }, 0);
    
//     var mrpTotal = api
//         .column(8)
//         .data()
//         .reduce(function (a, b) {
//             return intVal(a) + intVal(b);
//         }, 0);
    
//     var purchasePriceTotal = api
//         .column(9)
//         .data()
//         .reduce(function (a, b) {
//             return intVal(a) + intVal(b);
//         }, 0);
    
//     var taxableValueTotal = api
//         .column(10)
//         .data()
//         .reduce(function (a, b) {
//             return intVal(a) + intVal(b);
//         }, 0);
    
//     var totalAmountTotal = api
//         .column(11)
//         .data()
//         .reduce(function (a, b) {
//             return intVal(a) + intVal(b);
//         }, 0);
    
//     // Update footer
//     $(api.column(0).footer()).html('Total');
//     $(api.column(4).footer()).html(availableQtyTotal.toLocaleString("en-US"));
//     $(api.column(10).footer()).html(taxableValueTotal.toLocaleString("en-US"));
//     $(api.column(11).footer()).html(totalAmountTotal.toLocaleString("en-US"));
// },
//     });
// }



    $(document).on('change','#product_id, #warehouse_id, #quantity,#product_status,#manage_inventory',function(e){
      initialize_datatable();
    })

   


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

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      // Get all checkboxes on the page
      var checkboxes = $('.single_product');

      var warehouse_id    = $('#warehouse_id').val();
      var product_id      = $('#product_id').val();
      var quantity        = $('#quantity').val();
      var product_status   = $('#product_status').val();
      
      // Create an array to store the checked checkbox names
      var checkedNames = [];
      
      // Loop through each checkbox and check it if it's not already checked
      checkboxes.each(function() {
        // Add the checkbox name to the array if it's checked
        if ($(this).is(':checked')) {
          // alert($(this).data('warehouse_product_id'));
          checkedNames.push($(this).data('warehouse_product_id'));
        }
      });

      // alert(checkedNames.length);

      if(checkedNames.length){
        window.location.href = '<?= base_url('product/export'); ?>'+
                                  "/?data=" + checkedNames.join(",") +
                                  "&warehouse_id=" + warehouse_id +
                                  "&product_id=" + product_id +
                                  "&product_status=" + product_status +
                                  "&quantity=" + quantity;
      }
      else
      {
        window.location.href = '<?= base_url('product/export'); ?>'+
                                  "/?data=" + checkedNames.join(",") +
                                  "&warehouse_id=" + warehouse_id +
                                  "&product_id=" + product_id +
                                  "&product_status=" + product_status +
                                  "&quantity=" + quantity;

        Swal.fire({
          text: "All Products are exported",
          icon: "warning",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          timer: 3000,
          customClass: {
              confirmButton: "btn btn-primary"
          }
        });
      }
    });

    $(document).on('click', ".add_stock_modal" ,function(){

      $('#add_stock_modal').find('.modal-content').html('');

      var entry_type = $(this).data("entry_type");
      var warehouse_product_id = $(this).data("warehouse_product_id");
      var product_id = $(this).data("product_id");
      var warehouse_id = $(this).data("warehouse_id");

      // alert(warehouse_product_id);

      $.ajax({
        url: "<?php echo base_url('stock/add')?>/"+warehouse_product_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_stock_modal').find('.modal-content').html(data.add_stock_modal_body);
          $('#add_stock_modal').find('#entry_type').val(entry_type);
          $('#add_stock_modal').find('#warehouse_product_id').val(warehouse_product_id);

          // alert(product_id);
          // alert(warehouse_id);
        
          var desiredProductId = product_id; // Assuming you have the desired warehouse_product_id

          $('#add_stock_modal').find('#product_id option').each(function() {
              if ($(this).val() != desiredProductId) {
                  $(this).remove();
              }
          });

          var product_name = $('#add_stock_modal').find('form#addStockForm #product_id option:selected').text();

          $('#add_stock_modal').find('#product_name').val(product_name.trim());

       
         

          var desiredWarehouseId = warehouse_id; // Assuming you have the desired warehouse_product_id

          $('#add_stock_modal').find('form#addStockForm #warehouse_id option').each(function() {
              if ($(this).val() != desiredWarehouseId) {
                  $(this).remove();
              }
          });

          var warehouse_name = $('#add_stock_modal').find('form#addStockForm #warehouse_id option:selected').text();

          $('#add_stock_modal').find('#warehouse_name').val(warehouse_name.trim());



          $('#add_stock_modal').find('form#addStockForm #warehouse_product_id').trigger('change');


          $('#add_stock_modal').find('form#addStockForm #product_id').trigger('change');




          


          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });

          $('#add_stock_modal').modal('show');
          // reinitialize();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','#addStockForm',function(e){

      e.preventDefault();

      $('#addStockSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addStockForm').serialize();

      var isError = false;

      $('form#addStockForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addStockForm  #err_"+id).text(field+ " field is required.");
            $('form#addStockForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addStockForm #err_"+id).text("");
            $('form#addStockForm #'+id).removeClass('is-invalid');
            $('form#addStockForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addStockSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
          url: "<?php echo base_url('stock/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_stock_modal').modal('hide');
              $('form#addStockForm #addStockSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              productToast.fire({
                type: 'success',
                title: response.message
              });

              initialize_datatable();
            }
            else
            {
              productToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addStockSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addStockForm  .field_validation", function (event){
      var id    = $(this).attr('id');
      var value = $(this).val();
      var field = $(this).attr('placeholder');
      
      if(value==null || value==""){
        $("form#addStockForm #err_"+id).text(field+ " field is required.");
        $('form#addStockForm #'+id).addClass('is-invalid');
        return false;
      }
      else{
        $("form#addStockForm #err_"+id).text("");
        $('form#addStockForm #'+id).removeClass('is-invalid');
        $('form#addStockForm #'+id).addClass('is-valid');
      }
    });

    $(document).on('change', 'form#addStockForm #product_id' ,function (e) {

      var selectedOption = $(this).find(':selected');

      var batchNumbersString = selectedOption.data('batch_no');
    
      if (batchNumbersString && batchNumbersString.length > 1) {
          // Split batch numbers if not null and more than one
          var batchNumbersArray = batchNumbersString.split(',');

          // Rest of your code to populate datalist, set default value, and update other fields
          var datalist = $('#batchNumbersDatalist');
          datalist.empty();
          batchNumbersArray.forEach(function (batch) {
              datalist.append('<option value="' + batch.trim() + '">');
          });

          var batchNoInput = $('form#addStockForm #batch_no');
          batchNoInput.val(batchNumbersArray[0].trim()); // Set the first batch number as default
          batchNoInput.trigger('change');
      }

      // Set batch number input value
      //$('form#addStockForm #batch_no').val(batchNumbersArray[0].trim()); // Set the first batch number as default
     
      // $('form#addStockForm #product_name').val($(this).find(':selected').data('product_name'));
      // $('form#addStockForm #warehouse_name').val($(this).find(':selected').data('warehouse_name'));
      // $('form#addStockForm #batch_no').val($(this).find(':selected').data('batch_no'));
      $('form#addStockForm #product_uom').val($(this).find(':selected').data('product_uom'));
      $('form#addStockForm #product_price').val($(this).find(':selected').data('product_price'));
      $('form#addStockForm #product_cost').val($(this).find(':selected').data('product_cost'));
      $('form#addStockForm #selling_price').val($(this).find(':selected').data('selling_price'));
      if($('#entry_type').val()=='<?=WAREHOUSE_STOCK_OUT?>')
        $('#product_quantity').attr('max',$(this).find(':selected').data('max_quantity'));
    });


    $(document).on('show.bs.modal', '#edit_product_modal', function (e) {
        //var product_id = $(e.relatedTarget).data('product_id');
        var warehouse_product_id = $(e.relatedTarget).data('warehouse_product_id');
        $('#edit_product_modal').find('#id').val(product_id);

        $.ajax({
            url: "<?php echo base_url('product/edit')?>/" + warehouse_product_id,
            type: "GET",
            // data: {
            //     warehouse_product_id: warehouse_product_id
            // },
            dataType: "JSON",
            success: function(data) {
                $('#edit_product_modal').find('.modal-content').html(data.edit_product_modal_body);
                $('.select2bs4').select2({ theme: 'bootstrap4' });
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
        url: "<?php echo base_url('product/edit')?>",
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
  
  });
   
</script>