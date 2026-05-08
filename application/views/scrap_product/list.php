<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('product')?>"><?=$this->lang->line('header_scrap_product')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_scrap_product')?></li>
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
                      <option value="<?=QUANTITY_GREATER_THEN_ZERO?>">Greater then Zero</option>
                      <option value="<?=QUANTITY_ZERO?>">Zero</option>
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
                <h3 class="card-title">Scrap Product</h3>
                <div class="card-tools">

                  <ul class="nav nav-pills ml-auto">
                    <?php
                      $warehouse = $this->warehouse_model->get_record_by_is_default();
                    ?>
                    <input type="hidden" name="warehouse_id" id="warehouse_id" value="<?=($warehouse != '') ? $warehouse->id : ''?>">

                    <?php 
                      if($this->permission_model->has_permission('export_scrap_product'))
                      {
                    ?>
                        <li class="nav-item  ml-2">
                          <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Scrap Product">
                            <i class="fas fa-share"></i> Export
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
                      <th width="2%"><input type="checkbox" class="all_scrap_product"></th>
                      
                      <th><?=$this->lang->line('product_name')?></th>
                     
                      <th><?=$this->lang->line('product_batch_no')?></th>
                      <th>QTY</th>
                      <th><?=$this->lang->line('product_cost').' ('.$this->session->userdata('currency_symbol').')'?></th>
                      <th><?=$this->lang->line('product_price').' ('.$this->session->userdata('currency_symbol').')'?></th>
                      <th><?=$this->lang->line('product_selling_price').' ('.$this->session->userdata('currency_symbol').')'?></th>
                     
                      <th>Branch Name</th>
                         
                    </tr>
                  </thead>
                  <tbody id="scrap_product_list">
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

<script type="text/javascript">

  const productToast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 10000
  });

  $(document).ready(function(e){

    initialize_datatable();
    function initialize_datatable()
    {
      var warehouse_product_id = $('#warehouse_product_id').val();
     
      var quantity            = $('#quantity').val();

      $('#example').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [], //Initial no order.
        "pageLength": 100,
        "lengthMenu": [ [100, 250, 500, 1000, -1], [100, 250, 500, 1000, "All"] ],
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('scrap_product/ajax_list')?>",
            "type": "POST",
            "data":  {
              
              // 'warehouse_product_id' : warehouse_product_id,
              'quantity' : quantity,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 0,7 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],

      
      });
    }

    function reinitialize(){
      $('.select2bs4').select2({
        theme: 'bootstrap4'
      });

      $('.datepicker').datepicker({
          weekStart: 1,
          daysOfWeekHighlighted: "6,0",
          autoclose: true,
          todayHighlight: true,
          format: 'dd-mm-yyyy'
      });
    }

    $(document).on('change','#warehouse_product_id,#quantity',function(e){
      initialize_datatable();
    })

    /* Bulk Edit function Begin */

    $(document).on('change', '.all_scrap_product', function() {
      if(this.checked == true)
        $('.single_scrap_product').prop('checked',true);
      else
        $('.single_scrap_product').prop('checked',false);
    });

    $(document).on('change', '.single_scrap_product', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_scrap_product          = $('.single_scrap_product').length;
      var total_checked_single_scrap_product  = $('.single_scrap_product:checked').length;
      
      if(total_checked_single_scrap_product < total_single_scrap_product && total_checked_single_scrap_product > 0){
        $('.all_scrap_product').prop('indeterminate',true); 
      }
      else if(total_checked_single_scrap_product == total_single_scrap_product){
        $('.all_scrap_product').prop('indeterminate',false);
        $('.all_scrap_product').prop('checked',true);
      }
      else if(total_checked_single_scrap_product == 0){
        $('.all_scrap_product').prop('indeterminate',false);
        $('.all_scrap_product').prop('checked',false);
      }
    }

    $(document).on('click','#scrap_product_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));


      set_select_all_checkbox_status();
    });

    $('#scrap_product_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    /* export product */

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      // Get all checkboxes on the page
      var checkboxes = $('.single_scrap_product');
      var quantity        = $('#quantity').val();
      
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
        window.location.href = '<?= base_url('scrap_product/export'); ?>'+
                                  "/?data=" + checkedNames.join(",") +
                                 
                                  "&quantity=" + quantity;
      }
      else
      {
        window.location.href = '<?= base_url('scrap_product/export'); ?>'+
                                  "/?data=" + checkedNames.join(",") +
                                  
                                  "&quantity=" + quantity;

        Swal.fire({
          text: "All Scrap Products are exported",
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


  });
</script>
