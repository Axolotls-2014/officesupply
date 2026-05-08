<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('tax_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('tax_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('tax_list')?></h3>
              <?php 
                if($this->permission_model->has_permission('add_tax'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('tax/add')?>" data-tt="tooltip" title="Click here to Add Tax">
                      <i class="fas fa-money-check-alt mr-2"></i><?=$this->lang->line('tax_add')?>
                    </a>
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
                    <th><?=$this->lang->line('tax_name')?></th>
                    <th><?=$this->lang->line('tax_sgst')?></th>
                    <th><?=$this->lang->line('tax_cgst')?></th>
                    <th><?=$this->lang->line('tax_igst')?></th>
                    <th><?=$this->lang->line('tax_status')?></th>
                    <th width="5%"><?=$this->lang->line('tax_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                 
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('tax_name')?></th>
                    <th><?=$this->lang->line('tax_sgst')?></th>
                    <th><?=$this->lang->line('tax_cgst')?></th>
                    <th><?=$this->lang->line('tax_igst')?></th>
                    <th><?=$this->lang->line('tax_status')?></th>
                    <th><?=$this->lang->line('tax_action')?></th>
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
  <div class="modal fade" id="delete_tax">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="tax_edit">
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
            "url": "<?php echo site_url('tax/ajax_list')?>",
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
            "targets": [ 5 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
    });


    $(document).on('show.bs.modal','#delete_tax', function (e) {
      var tax_id = $(e.relatedTarget).data('tax_id');
      $('#delete_tax').find('#id').val(tax_id);

      // alert(tax_id);

      $.ajax({
        url: "<?php echo base_url('tax/tax_delete_confirmation')?>",
        type: "POST",
        data:{
          'tax_id': tax_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_tax').find('.modal-content').html(data.tax_delete_modal_body);
        }
      });


    });

       // Delete record with please wait text
    $(document).on('submit','#deleteTaxForm',function(e){
      $('#deleteTaxSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    $(document).on('show.bs.modal','#tax_edit', function (e) {
      var tax_id = $(e.relatedTarget).data('tax_id');
      $('#tax_edit').find('#id').val(tax_id);

      // alert(tax_id);

      $.ajax({
        url: "<?php echo base_url('tax/tax_edit_confirmation')?>",
        type: "POST",
        data:{
          'tax_id': tax_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#tax_edit').find('.modal-content').html(data.tax_edit_modal_body);
        }
      });


    });

    /*************************** End Dynamic Product List with Datatables ****************************/
  });
</script>
