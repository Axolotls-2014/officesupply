<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_inventory')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_warehouse')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('warehouse_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('warehouse_list')?></h3>
              
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('warehouse/add')?>" data-tt="tooltip" title="Click here to Add warehouse">
                      <i class="fas fa-warehouse mr-2"></i><?=$this->lang->line('warehouse_add')?>
                    </a>
                  </li>
                </ul>
              </div>
              
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('warehouse_name')?></th>
                    <th><?=$this->lang->line('warehouse_code')?></th>
                    <th><?=$this->lang->line('warehouse_description')?></th>
                    <th><?=$this->lang->line('quantity')?></th>
                    <th width="5%"><?=$this->lang->line('warehouse_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
               
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('warehouse_name')?></th>
                    <th><?=$this->lang->line('warehouse_code')?></th>
                    <th><?=$this->lang->line('warehouse_description')?></th>
                    <th><?=$this->lang->line('quantity')?></th>
                    <th><?=$this->lang->line('warehouse_action')?></th>
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
  <div class="modal fade" id="delete_warehouse">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="product_quantity_warehouse_wise">
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
        <div class="modal-body product_quantity_warehouse_wise_data">
          
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
  $(document).ready(function(e){
    $('#product_quantity_warehouse_wise').on('show.bs.modal', function (e) {
      
      var warehouse_id = $(e.relatedTarget).data('warehouse_id');
      
      $.ajax({
        url: "<?php echo base_url('product/product_quantity_warehouse_wise')?>",
        type: "POST",
        data: {
            'warehouse_id':warehouse_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
        dataType: "JSON",
        success: function(data){
          $('.product_quantity_warehouse_wise_data').html(data.product_quantity_data);
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
            "targets": [ 3,4 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
    });


    $(document).on('show.bs.modal','#delete_warehouse', function (e) {
      var warehouse_id = $(e.relatedTarget).data('warehouse_id');
      $('#delete_warehouse').find('#id').val(warehouse_id);

      // alert(warehouse_id);

      $.ajax({
        url: "<?php echo base_url('warehouse/warehouse_delete_confirmation')?>",
        type: "POST",
        data:{
          'warehouse_id': warehouse_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_warehouse').find('.modal-content').html(data.warehouse_delete_modal_body);
        }
      });


    });

      // Delete record with please wait text
    $(document).on('submit','#deleteWarehouseForm',function(e){
      $('#deleteWarehouseSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    /*************************** End Dynamic Product List with Datatables ****************************/

  });
</script>