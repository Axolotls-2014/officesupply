<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_expense_category')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('expense_category_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('expense_category_list')?></h3>
              <?php 
                if($this->permission_model->has_permission('add_expense_category'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('expense_category/add')?>" data-tt="tooltip" title="Click here to Add Expense Category">
                      <i class="fas fa-money-check-alt mr-2"></i><?=$this->lang->line('expense_category_add')?>
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
                    <th><?=$this->lang->line('expense_category_name')?></th>
                    <th><?=$this->lang->line('expense_category_description')?></th>
                    <th><?=$this->lang->line('expense_category_type')?></th>
                    <th><?=$this->lang->line('expense_category_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                 
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('expense_category_name')?></th>
                    <th><?=$this->lang->line('expense_category_description')?></th>
                    <th><?=$this->lang->line('expense_category_type')?></th>
                    <th><?=$this->lang->line('expense_category_action')?></th>
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
  <div class="modal fade" id="delete_expense_category">
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
            "url": "<?php echo site_url('expense_category/ajax_list')?>",
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


    $(document).on('show.bs.modal','#delete_expense_category', function (e) {
      var expense_category_id = $(e.relatedTarget).data('expense_category_id');
      $('#delete_expense_category').find('#id').val(expense_category_id);

      // alert(expense_category_id);

      $.ajax({
        url: "<?php echo base_url('expense_category/expense_category_delete_confirmation')?>",
        type: "POST",
        data:{
          'expense_category_id': expense_category_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_expense_category').find('.modal-content').html(data.expense_category_delete_modal_body);
        }
      });


    });

      // Delete record with please wait text
    $(document).on('submit','#deleteexpenseCategoryForm',function(e){
      $('#deleteexpenseCategorySubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

      /*************************** End Dynamic Product List with Datatables **************************/

  });
</script>

