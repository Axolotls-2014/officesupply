<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_inventory')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_product')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('product_list')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('product_list')?></h3>
              
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('product/add')?>" data-tt="tooltip" title="Click here to Add product">
                      <i class="fas fa-dice-d20 mr-2"></i><?=$this->lang->line('product_add')?>
                    </a>
                  </li>
                </ul>
              </div>
              
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th width="2%"><input type="checkbox" class="all_product"></th>
                    <th><?=$this->lang->line('product_name')?></th>
                    <th><?=$this->lang->line('product_description')?></th>
                    <th><?=$this->lang->line('product_category_name')?></th>
                    <th><?=$this->lang->line('product_hsn')?></th>
                    <th><?=$this->lang->line('product_markup')?></th>
                    <th><?=$this->lang->line('product_uom')?></th>
                    <th><?=$this->lang->line('quantity')?></th>
                    <th><?=$this->lang->line('product_status')?></th>
                    <th width="5%"><?=$this->lang->line('product_action')?></th>
                  </tr>
                </thead>
                <tbody>
                
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('product_name')?></th>
                    <th><?=$this->lang->line('product_description')?></th>
                    <th><?=$this->lang->line('product_category_name')?></th>
                    <th><?=$this->lang->line('product_hsn')?></th>
                    <th><?=$this->lang->line('product_markup')?></th>
                    <th><?=$this->lang->line('product_uom')?></th>
                    <th><?=$this->lang->line('quantity')?></th>
                    <th><?=$this->lang->line('product_status')?></th>
                    <th><?=$this->lang->line('product_action')?></th>
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

<div class="example-modal">
  <div class="modal fade" id="delete_product">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<!-- Checkbox management -->
<script type="text/javascript">
  $(document).ready(function(e){
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
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

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


    /*************************** Start Dynamic Product List with Datatables **************************/

    var table = $('#example').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('product/ajax_list')?>",
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
            "targets": [0,9], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
    });


    $(document).on('show.bs.modal','#delete_product', function (e) {
      var product_id = $(e.relatedTarget).data('product_id');
      $('#delete_product').find('#id').val(product_id);

      // alert(product_id);

      $.ajax({
        url: "<?php echo base_url('product/product_delete_confirmation')?>",
        type: "POST",
        data:{
          'product_id': product_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_product').find('.modal-content').html(data.product_delete_modal_body);
        }
      });


    });

      // Delete record with please wait text
    $(document).on('submit','#deleteProductForm',function(e){
      $('#deleteProductSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    /*************************** End Dynamic Product List with Datatables ****************************/

  });
</script>