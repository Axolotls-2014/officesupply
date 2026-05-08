<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
  <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>

                <li class="breadcrumb-item "><a href="<?=base_url('stock')?>"><?=$this->lang->line('header_stock')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_stock')?></li>
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
                <h3 class="card-title">Stock Entry</h3>
                <?php 
                if($this->permission_model->has_permission('add_stock'))
                  {
                ?>

                <div class="card-tools">
                  <button type="button" class="btn btn-primary btn-sm add_stock_modal" data-toggle="modal" data-entry_type="in" data-target="#add_stock_modal" data-tt="tooltip" title="Click here to Stock In">    Stock In
                  </button>
                  <button type="button" class="btn btn-danger btn-sm add_stock_modal" data-toggle="modal" data-entry_type="out" data-target="#add_stock_modal" data-tt="tooltip" title="Click here to Stock Out">   Stock Out
                  </button>
                </div>
                <?php
                  }
                ?>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-md-12">
                    <div class="row">
                      <div class="col-md-2">
                        <label>From Date</label>
                        <input type="date" class="form-control" id="from_date" name="from_date">
                      </div>
                      <div class="col-md-2">
                        <label>To Date</label>
                        <input type="date" class="form-control" id="to_date" name="to_date">
                      </div>
                      <!--<div class="col-md-2">-->
                      <!--  <label>&nbsp;</label>-->
            
                      <!--  <button type="button" class="btn btn-primary btn-block" id="filter_date">Filter</button>-->
                      <!--</div>-->
                      <div class="col-md-2">
                        <label>&nbsp;</label>
                        <button type="button" class="btn btn-secondary btn-block" id="reset_date">Reset</button>
                      </div>
                    </div>
                  </div>
                </div>
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th>Branch Name</th>
                      <th><?=$this->lang->line("stock_product_name")?></th>
                      <th>HSN</th>
                      <th>Batch NO.</th>
                      <th>QTY</th>
                      <th>UOM</th>
                      <th>Purchase Price<?=' ('.$this->session->userdata('currency_symbol').')'?></th>
                      <th><?=$this->lang->line("stock_product_price").' ('.$this->session->userdata('currency_symbol').')'?></th>
                      <th><?=$this->lang->line("stock_product_entry_type")?></th>
                      <th>Entry Date</th>
                      <th width="8%"><?=$this->lang->line("stock_action")?></th>   
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
<div class="modal fade" id="edit_stock_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
<div class="modal fade" id="delete_stock_modal" data-backdrop="static" data-keyboard="false">
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

    const stockToast = Swal.mixin({
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
          "url": "<?php echo site_url('stock/ajax_list')?>",
            "type": "POST",
            "data":  {
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>',
              'from_date': function() { return $('#from_date').val(); },
              'to_date': function() { return $('#to_date').val(); }
            }
        },

        'initComplete':function(settings, json){
          reinitialize();
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 8 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
     });
    }

    function reinitialize()
    {
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });
    }

    // Date Filter
    $(document).on('click', '#filter_date', function(){
      $('#example').DataTable().ajax.reload();
    });

    $(document).on('change', '#from_date, #to_date', function(){
      $('#example').DataTable().ajax.reload();
    });

    $(document).on('click', '#reset_date', function(){
      $('#from_date').val('');
      $('#to_date').val('');
      $('#example').DataTable().ajax.reload();
    });

    $(document).on('click', ".add_stock_modal" ,function(){

      var entry_type = $(this).data("entry_type");

      $.ajax({
        url: "<?php echo base_url('stock/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_stock_modal').find('.modal-content').html(data.add_stock_modal_body);
          $('#add_stock_modal').find('#entry_type').val(entry_type);

          if(entry_type == '<?=WAREHOUSE_STOCK_OUT?>'){
            $('#add_stock_modal').find('#product_cost').attr('readonly','readonly')
            $('#add_stock_modal').find('#product_price').attr('readonly','readonly')
          }

          $('#add_stock_modal').modal('show');
          
          reinitialize();
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });
    
    $(document).on('show.bs.modal','#edit_stock_modal', function (e) {
  var stock_id = $(e.relatedTarget).data('stock_id');
  $('#edit_stock_modal').find('#id').val(stock_id);

  $.ajax({
    url: "<?php echo base_url('stock/edit')?>/"+stock_id,
    type: "GET",
    dataType: "JSON",
    success: function(data){
      $('#edit_stock_modal').find('.modal-content').html(data.edit_stock_modal_body);
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });
    }
  });
});

$(document).on('hidden.bs.modal','#edit_stock_modal', function (e) {
  $('#edit_stock_modal').find('.modal-content').html('');
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
              initialize_datatable();

              stockToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              stockToast.fire({
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

    $(document).on('show.bs.modal','#edit_stock_modal', function (e) {
      
      var stock_id = $(e.relatedTarget).data('stock_id');
      $('#edit_stock_modal').find('#id').val(stock_id);

      $.ajax({
        url: "<?php echo base_url('stock/edit')?>/"+stock_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#edit_stock_modal').find('.modal-content').html(data.edit_stock_modal_body);
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
        }
      });
    });

    $(document).on('hidden.bs.modal','#edit_stock_modal', function (e) {
      $('#edit_stock_modal').find('.modal-content').html('');
    });

    // Edit record with please wait text
    $(document).on('submit','#editStockForm',function(e){
      e.preventDefault();

      $('#editStockSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#editStockForm').serialize();

      var isError = false;

      $('form#editStockForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editStockForm  #err_"+id).text(field+ " field is required.");
            $('form#editStockForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editStockForm #err_"+id).text("");
            $('form#editStockForm #'+id).removeClass('is-invalid');
            $('form#editStockForm #'+id).addClass('is-valid');
          }
      });

      if(isError == true)
      {
        $('#editStockSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
          url: "<?php echo base_url('stock/edit')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            {  
              //alert(data);
              //$('.rig_category').html(response.rig_categories);
              $('#edit_stock_modal').modal('hide');
              $('form#editStockForm #editStockSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
              initialize_datatable();

              stockToast.fire({
                type: 'success',
                title: response.message
              });
            }
            else
            {
              $('#editStockSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on('shown.bs.modal','#delete_stock_modal', function (e) {
      var stock_id = $(e.relatedTarget).data('stock_id');
      $('#delete_stock_modal').find('#id').val(stock_id);

      $.ajax({
        url: "<?php echo base_url('stock/stock_delete_confirmation')?>",
        type: "POST",
        data:{
        'stock_id': stock_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
        $('#delete_stock_modal').find('.modal-content').html(data.delete_stock_modal_body);
        }
      });
    });

    $(document).on('submit', '#deleteStockForm' ,function (e) {
      e.preventDefault();

      $('#deleteStockSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteStockForm').serialize();

      $.ajax({
        url: "<?php echo base_url('stock/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
            $('#delete_stock_modal').modal('hide');
            $('form#deleteStockForm #deleteStockSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            initialize_datatable();

            stockToast.fire({
              type: 'success',
              title: response.message
            });
            
          }
          else
          {
            stockToast.fire({
              type: 'error',
              title: response.message
            });            
          }

          $('#deleteStockSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        }
      });
    });

    $(document).on('change', '#warehouse_id' ,function (e) {
      //var warehouse_id = $('#warehouse_id').val();
      $('#warehouse_name').val($(this).find(':selected').data('warehouse_name'));

      // if($('#entry_type').val()=='<?=WAREHOUSE_STOCK_OUT?>')
      // {
        // $('#product_id').html('<option value="">Select (Product - Product Category - Price)</option>');
        // $.ajax({
        //   url: "<?php echo base_url('warehouse/warehouse_products_by_warehouse_id') ?>/"+warehouse_id,
        //   type: "GET",
        //   dataType: "JSON",
        //   success: function(data){
        //     if($('#entry_type').val()=='<?=WAREHOUSE_STOCK_OUT?>')
        //     {
        //       for(i=0;i<data.length;i++){
        //          $('#product_id').append(
        //           '<option value="'+ data[i].product_id + '" data-product_name="'
        //                            + data[i].product_name +'" data-product_price="'
        //                            + data[i].price +'" data-product_cost="'
        //                            + data[i].cost +'" data-product_uom="'
        //                            + data[i].product_uom +'" data-max_quantity="'
        //                            + data[i].quantity 
        //           +'">' 
        //               + data[i].product_name + ' - ' 
        //               + data[i].product_category_name + ' - ' 
        //               + data[i].price 
        //           + '</option>');
        //        }
        //     }
        //     else
        //     {
        //       for(i=0;i<data.length;i++){
        //          $('#product_id').append(
        //           '<option value="'+ data[i].product_id + '" data-product_name="'
        //                            + data[i].product_name +'" data-product_price="'
        //                            + data[i].price +'" data-product_cost="'
        //                            + data[i].cost +'" data-product_uom="'
        //                            + data[i].product_uom +'" data-max_quantity="'
        //                            + data[i].quantity 
        //           +'">' 
        //               + data[i].product_name + ' - ' 
        //               + data[i].product_category_name + ' - ' 
        //               + data[i].cost 
        //           + '</option>');
        //        } 
        //     }
        //   }
        // });
      // }
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

      $('#product_name').val($(this).find(':selected').data('product_name'));
     
      $('#product_uom').val($(this).find(':selected').data('product_uom'));
      $('#product_price').val($(this).find(':selected').data('product_price'));
      $('#product_cost').val($(this).find(':selected').data('product_cost'));
      $('#selling_price').val($(this).find(':selected').data('selling_price'));

    
      if($('#entry_type').val()=='<?=WAREHOUSE_STOCK_OUT?>')
        $('#product_quantity').attr('max',$(this).find(':selected').data('max_quantity'));
      
    });
  });
</script>


<!--  -->