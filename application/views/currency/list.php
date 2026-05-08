<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_setting')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_currency')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('currency_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('currency_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                    <?php 
                      if($this->permission_model->has_permission('import_currency'))
                      {
                    ?>
                    <li class="nav-item  ml-2">
                      <a class="nav-link import_currency_modal btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Import Currency in Bulk using CSV file">
                        <i class="fas fa-file-import"></i> Import Currency
                      </a>
                    </li>
                    <?php 
                      }
                    ?>


                  <?php 
                    if($this->permission_model->has_permission('add_currency'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    <a class="nav-link active" href="<?=base_url('currency/add')?>" data-tt="tooltip" title="Click here to Add Currency">
                      <i class="fas fa-money-check-alt mr-2"></i><?=$this->lang->line('currency_add')?>
                    </a>
                  </li>
                  <?php 
                    }
                  ?>
                </ul>
              </div>
             
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('currency_name')?></th>
                    <th><?=$this->lang->line('currency_symbol')?></th>
                    <th width="10%"><?=$this->lang->line('currency_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
              
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('currency_name')?></th>
                    <th><?=$this->lang->line('currency_symbol')?></th>
                    <th><?=$this->lang->line('currency_action')?></th>
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
  <div class="modal fade" id="delete_currency">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="import_currency_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function(e){

    /*************************** Start Dynamic Currency List with Datatables **************************/

    var table = $('#example').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('currency/ajax_list')?>",
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
            "targets": [ 0,2 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
    });


    $(document).on('show.bs.modal','#delete_currency', function (e) {
      var currency_id = $(e.relatedTarget).data('currency_id');
      $('#delete_currency').find('#id').val(currency_id);

      // alert(currency_id);

      $.ajax({
        url: "<?php echo base_url('currency/currency_delete_confirmation')?>",
        type: "POST",
        data:{
          'currency_id': currency_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_currency').find('.modal-content').html(data.currency_delete_modal_body);
        }
      });


    });

      // Delete record with please wait text
    $(document).on('submit','#deleteCurrencyForm',function(e){
      $('#deleteCurrencySubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    /*************************** End Dynamic currency List with Datatables ****************************/

    /* Import currency using CSV function Begin */

    $(document).on('click', ".import_currency_modal" ,function(event){
      event.preventDefault();

      $.ajax({
        url: "<?php echo base_url('currency/import_currency')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#import_currency_modal').find('.modal-content').html(data.import_currency_modal_body);
          $('#import_currency_modal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // alert(xhr.status);
          show_message('failure-header',thrownError);
          // alert(thrownError);
          // alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','form#importCurrencyForm',function(event){
      // event.preventDefault();

      $('form#importCurrencyForm #importCurrencySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('form#importCurrencyForm').serialize();

      var isError = false;

      $('form#importCurrencyForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#importCurrencyForm  #err_"+id).text(field+ " field is required.");
            $('form#importCurrencyForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#importCurrencyForm #err_"+id).text("");
            $('form#importCurrencyForm #'+id).removeClass('is-invalid');
            $('form#importCurrencyForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('form#importCurrencyForm #importCurrencySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        return true;
      }
    });

    /* Import currency using CSV function End */


  });
</script>