<?php $this->load->view('layout/header');?>
<style>
/* Remove horizontal scroll and force fit */
.table-responsive {
    overflow-x: hidden !important;
}

#example {
    table-layout: fixed !important; /* Forces columns to stay within 100% width */
    width: 100% !important;
    font-size: 11px; /* Slightly smaller font to save space */
}

#example th, 
#example td {
    padding: 4px 2px !important; /* Minimal padding */
    vertical-align: middle;
    word-wrap: break-word; /* Allows long text like emails to wrap to next line */
    white-space: normal !important; /* Overrides DataTables default nowrap */
    overflow: hidden;
    line-height: 1.2;
}

/* Optional: Hide less important columns on very small screens if needed, 
   but for now, we force all to fit. */
</style>
<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_expense')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('supplier_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('supplier_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('supplier_list')?></h3>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <?php 
                    if($this->permission_model->has_permission('import_supplier'))
                    {
                  ?>
                  <!-- <li class="nav-item">
                    <a class="nav-link import_supplier_modal btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Import Supplier in Bulk using CSV file">
                      <i class="fas fa-file-import"></i> Import Supplier
                    </a>
                  </li> -->
                  <?php 
                    }
                  ?>
                  <li class="nav-item ml-2">
                    <a class="nav-link btn-sm active" href="<?=base_url('supplier/add')?>" data-tt="tooltip" title="Click here to Add Supplier"><i class="fas fa-user-friends mr-2"></i><?=$this->lang->line('supplier_add')?></a>
                  </li>
                </ul>
              </div>
            </div>
            <div class="card-body">
              <table id="example" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('supplier_company_name')?></th>
                    <th><?=$this->lang->line('supplier_gst_registration_type')?></th>
                    <th><?=$this->lang->line('supplier_gstin')?></th>
                    <th><?=$this->lang->line('supplier_email')?></th>
                    <th><?=$this->lang->line('supplier_phone')?></th>
                    <th><?=$this->lang->line('supplier_contact_person_name')?></th>
                    <th><?=$this->lang->line('supplier_contact_person_designation')?></th>
                    <!-- <th><?=$this->lang->line('supplier_address')?></th> -->
                    <!--<th><?=$this->lang->line('supplier_website')?></th>-->
                    <th>Bank Name</th>
                    <th>Bank IFSC</th>
                    <th>Account Number</th>
                    <th><?=$this->lang->line('supplier_state_id')?></th>
                    <th><?=$this->lang->line('supplier_country_id')?></th>
                    <th width="18%"><?=$this->lang->line('supplier_action')?></th>
                  </tr>
                </thead>
                <tbody>
                </tbody>
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
  <div class="modal fade" id="delete_supplier">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="import_supplier_modal" data-backdrop="static" data-keyboard="false">
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

       /*************************** Start Dynamic Product List with Datatables **************************/

    var table = $('#example').DataTable({ 
 
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "order": [], //Initial no order.
 
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": "<?php echo site_url('supplier/ajax_list')?>",
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
            "targets": [ 12 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
    });


    $(document).on('show.bs.modal','#delete_supplier', function (e) {
      var supplier_id = $(e.relatedTarget).data('supplier_id');
      $('#delete_supplier').find('#id').val(supplier_id);

      // alert(supplier_id);

      $.ajax({
        url: "<?php echo base_url('supplier/supplier_delete_confirmation')?>",
        type: "POST",
        data:{
          'supplier_id': supplier_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_supplier').find('.modal-content').html(data.supplier_delete_modal_body);
        }
      });


    });

       // Delete record with please wait text
    $(document).on('submit','#deleteSupplierForm',function(e){
      $('#deleteSupplierSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    /*************************** End Dynamic Product List with Datatables ****************************/

    /* Import product using CSV function Begin */

    $(document).on('click', ".import_supplier_modal" ,function(e){
      e.preventDefault();

      $.ajax({
        url: "<?php echo base_url('supplier/import_supplier')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#import_supplier_modal').find('.modal-content').html(data.import_supplier_modal_body);
          $('#import_supplier_modal').modal('show');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          // alert(xhr.status);
          show_message('failure-header',thrownError);
          // alert(thrownError);
          // alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','form#importSupplierForm',function(event){
      // event.preventDefault();

      $('form#importSupplierForm #importProductSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('form#importSupplierForm').serialize();

      var isError = false;

      $('form#importSupplierForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#importSupplierForm  #err_"+id).text(field+ " field is required.");
            $('form#importSupplierForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#importSupplierForm #err_"+id).text("");
            $('form#importSupplierForm #'+id).removeClass('is-invalid');
            $('form#importSupplierForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('form#importSupplierForm #importSupplierSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        return true;
      }
    });

    /* Import product using CSV function End */


  });
</script>