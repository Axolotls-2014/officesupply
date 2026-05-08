<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_inventory')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_product_category')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('product_category_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('product_category_list')?></h3>
              
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('product_category/add')?>" data-tt="tooltip" title="Click here to Add Product Category">
                      <i class="fas fa-shapes mr-2"></i><?=$this->lang->line('product_category_add')?>
                    </a>
                  </li>
                </ul>
              </div>
              
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('product_category_name')?></th>
                    <th><?=$this->lang->line('product_category_description')?></th>
                    <th><?=$this->lang->line('product_category_tax_name')?></th>
                    <th width="5%"><?=$this->lang->line('product_category_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
               
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('product_category_name')?></th>
                    <th><?=$this->lang->line('product_category_description')?></th>
                    <th><?=$this->lang->line('product_category_tax_name')?></th>
                    <th><?=$this->lang->line('product_category_action')?></th>
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
  <div class="modal fade" id="delete_product_category">
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

     /*************************** Start Dynamic Product List with Datatables **************************/

    var table = $('#example').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
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


    $(document).on('show.bs.modal','#delete_product_category', function (e) {
      var product_category_id = $(e.relatedTarget).data('product_category_id');
      $('#delete_product_category').find('#id').val(product_category_id);

      // alert(product_category_id);

      $.ajax({
        url: "<?php echo base_url('product_category/product_category_delete_confirmation')?>",
        type: "POST",
        data:{
          'product_category_id': product_category_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_product_category').find('.modal-content').html(data.product_category_delete_modal_body);
        }
      });


    });

    // Delete record with please wait text
    $(document).on('submit','#deleteproductCategoryForm',function(e){
      $('#deleteproductCategorySubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    /*************************** End Dynamic Product List with Datatables ****************************/


  });
</script>