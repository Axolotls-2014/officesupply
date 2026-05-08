<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><?=$this->lang->line('header_account')?></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_scrap_receive')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('scrap_receive_list')?></li>
          </ol>
        </div>
      </div>
    </section>


    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <div class="col-sm-3">
                  <div class="form-group">
                    <label for="customer_id"><?=$this->lang->line('sale_customer')?></label>
                    <select class="form-control form-control-sm select2bs4" name="customer_id" id="customer_id" width="100%" class="add-row">
                      <option value="">All</option>
                      <?php 
                        foreach ($customers as $value) {
                      ?>
                        <option value="<?=$value->id?>"><?=$value->customer_name?></option>
                      <?php
                        }
                      ?>
                      
                    </select>
                    <span id="err_customer_id" class="error invalid-feedback"><?=form_error('customer_id');?></span>
                  </div>
                </div>
               
               
              </div>  
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('scrap_receive_list')?></h3>
              <?php 
                if($this->permission_model->has_permission('add_scrap_receive'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item ml-2">
                    <a class="nav-link active" href="<?=base_url('scrap_receive/add')?>" data-tt="tooltip" title="Click here to Add Scrap receive"><i class="far fa-snowflake mr-2"></i><?=$this->lang->line('scrap_receive_add')?></a>
                  </li>
                </ul>
              </div>
              <?php 
                }
              ?>
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <!-- <th><input type="checkbox" class="all_scrap_receive"></th> -->
                    <th><?=$this->lang->line('scrap_receive_reference_no')?></th>
                    
                    <th><?=$this->lang->line('scrap_receive_total_amount')?></th>
                    <th><?=$this->lang->line('scrap_receive_total_product')?></th>
                    <th><?=$this->lang->line('scrap_receive_total_quantity')?></th>
                    
                    <th><?=$this->lang->line('scrap_receive_date')?></th>
                    <th><?=$this->lang->line('scrap_receive_customer')?></th>
                    
                    <th><?=$this->lang->line('scrap_receive_action')?></th>
                  </tr>
                </thead>
                <tbody>
                  
                </tbody>
                <tfoot>
                  <tr>
                    <!-- <th></th> -->
                    <th><?=$this->lang->line('scrap_receive_reference_no')?></th>
                 
                    <th><?=$this->lang->line('scrap_receive_total_amount')?></th>
                    <th><?=$this->lang->line('scrap_receive_total_product')?></th>
                    <th><?=$this->lang->line('scrap_receive_total_quantity')?></th>
                  
                    <th><?=$this->lang->line('scrap_receive_date')?></th>
                    <th><?=$this->lang->line('scrap_receive_customer')?></th>
                    
                    <th><?=$this->lang->line('scrap_receive_action')?></th>
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
  <div class="modal fade" id="delete_scrap_receive">
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

           /*************************** Start Dynamic Sale List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    {
      // var sales_payment = $('input[name="sales_payment"]:checked').val();

      var supplier_id       = $('#supplier_id').val();
      

      $('#example').DataTable({ 
   
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "order": [], //Initial no order.
          "bDestroy":true,
   
          // Load data for the table's content from an Ajax source
          "ajax": {
              "url": "<?php echo site_url('scrap_receive/ajax_list')?>",
              "type": "POST",
              "data":  {
                'supplier_id':supplier_id,
                
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              }
          },

          'initComplete':function(settings, json){
            $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
          },  
   
          //Set column definition initialisation properties.
          "columnDefs": [
            { 
              "targets": [ 6], //first column / numbering column
              "orderable": false, //set not orderable
            },
          ],
      });
    }

    $(document).on('change','select[name="supplier_id"]',function(e){
      initialize_datatable();
    })

    $(document).on('show.bs.modal','#delete_scrap_receive', function (e) {
      var scrap_receive_id = $(e.relatedTarget).data('scrap_receive_id');
      $('#delete_scrap_receive').find('#id').val(scrap_receive_id);

      // alert(scrap_receive_id);

      $.ajax({
        url: "<?php echo base_url('scrap_receive/scrap_receive_delete_confirmation')?>",
        type: "POST",
        data:{
          'scrap_receive_id': scrap_receive_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_scrap_receive').find('.modal-content').html(data.scrap_receive_delete_modal_body);
        }
      });


    });

     // Delete record with please wait text
     $(document).on('submit','#deleteScrapreceiveForm',function(e){
      $('#deleteScrapreceiveButton').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

  

    /*************************** End Dynamic Sale List with Datatables ****************************/

  });
</script>
