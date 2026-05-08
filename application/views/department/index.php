<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('employee')?>"><?=$this->lang->line('header_hrmodule')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('department')?>"><?=$this->lang->line('header_department')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('department_list')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <?php $this->load->view('layout/common/hr_menu');?>
        </div>
        <div class="col-md-9">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('department_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item  ml-2">
                    <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Department">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>
                  <?php 
                    if($this->permission_model->has_permission('add_department'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    
                    <button type="button" class="btn btn-primary add_department_modal">
                      <i class="fas fa-user mr-2"></i><?=$this->lang->line('department_add')?>
                    </button>
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
                    <th width="2%"><input type="checkbox" class="all_department"></th>
                    <th><?=$this->lang->line('department_code')?></th>
                    <th><?=$this->lang->line('department_name')?></th>
                    <th width="15%"><?=$this->lang->line('department_action')?></th>
                  </tr>
                </thead>
                
                <tbody id="department_list">
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('department_code')?></th>
                    <th><?=$this->lang->line('department_name')?></th>
                    <th><?=$this->lang->line('department_action')?></th>
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

<div class="modal fade" id="add_department_modal" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      
    </div>
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="delete_department" data-backdrop="static">
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

    /*************************** Start Dynamic department List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    { 
      $('#example').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [], //Initial no order.
        "pageLength": 50,
 
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('department/ajax_list')?>",
            "type": "POST",
            "data":  {
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 0,3 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
      });
    }

    function reinitialize(){
      $(".select2").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }

    $(document).on('click', ".add_department_modal" ,function(event){
      event.preventDefault();
      
      var department_id = $(this).data('department_id');
      department_id = (department_id === undefined) ? "" : "/"+department_id;

      $.ajax({
        url: "<?=base_url('department/add')?>"+department_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_department_modal').find('.modal-content').html(data.add_department_modal_body);
          $('#add_department_modal').modal('show');
          reinitialize();
          $('.datepicker').datepicker({
              weekStart: 1,
              daysOfWeekHighlighted: "6,0",
              autoclose: true,
              todayHighlight: true,
              format: 'dd-mm-yyyy'
          });
        },
        error: function (xhr, ajaxOptions, thrownError) {
          Swal.fire({
            title: 'FAILURE !!',
            // text: xhr.status + thrownError + ajaxOptions,
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary"
            }
          });
        }
      });

      
    });

    $(document).on('submit','#addDepartmentForm',function(e){
      
      e.preventDefault();

      $('#addDepartmentSubmit').text('Please wait...').attr('disabled','disabled');
      var formData = $('#addDepartmentForm').serialize();

      var isError = false;

      $('form#addDepartmentForm .field_validation').each(function() {
          
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#addDepartmentForm  #err_"+id).text(field+ " field is required.");
          $('form#addDepartmentForm  #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addDepartmentForm #err_"+id).text("");
          $('form#addDepartmentForm #'+id).removeClass('is-invalid');
          $('form#addDepartmentForm #'+id).addClass('is-valid');
        }
        
      });
      
      if(isError == true)
      {
        $('#addDepartmentSubmit').text('Submit').removeAttr('disabled');
        return false;
      }
      else
      {
       
        
        var department_id = $('#add_department_modal').find('input[name="department_id"]').val();
        department_id = (department_id == '') ? "" : "/"+department_id;

        $.ajax({
          url: "<?php echo base_url('department/add')?>"+department_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_department_modal').modal('hide');
              $('form#addDepartmentForm #addDepartmentSubmit').text('Submit').removeAttr('disabled');

              Swal.fire({
                text: response.message,
                title: 'SUCCESS !!',
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              initialize_datatable();
            }
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                $("form#addDepartmentForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#addDepartmentForm #addDepartmentSubmit').text('Submit').removeAttr('disabled');
            }
            else
            {
              Swal.fire({
                text: response.message,
                title: "FAILURE !!",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              $('#addDepartmentSubmit').text('Submit').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addDepartmentForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addDepartmentForm #err_"+id).text(field+ " field is required.");
          $('form#addDepartmentForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addDepartmentForm #err_"+id).text("");
          $('form#addDepartmentForm #'+id).removeClass('is-invalid');
          $('form#addDepartmentForm #'+id).addClass('is-valid');
        }
    });


    $(document).on('show.bs.modal','#delete_department', function (e) {
      var department_id = $(e.relatedTarget).data('department_id');
      $('#delete_department').find('#id').val(department_id);

      // alert(department_id);

      $.ajax({
        url: "<?php echo base_url('department/department_delete_confirmation')?>",
        type: "POST",
        data:{
          'department_id': department_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_department').find('.modal-content').html(data.department_delete_modal_body);
        }
      });


    });

    $(document).on('submit', '#deleteDepartmentForm' ,function (e) {
      e.preventDefault();

      $('#deleteDepartmentSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteDepartmentForm').serialize();

      $.ajax({
      url: "<?php echo base_url('department/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#delete_department').modal('hide');
            $('form#deleteDepartmentForm #deleteDepartmentSubmit').text('Delete').removeAttr('disabled');
            initialize_datatable();

            Swal.fire({
                title: 'SUCCESS !!',
                text: response.message,
                icon: "success",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              initialize_datatable();
            
          }
          else
          {
            
            Swal.fire({
                title: 'FAILURE !!',
                text: response.message,
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              initialize_datatable();        
          }

          $('#deleteDepartmentSubmit').text('Delete').removeAttr('disabled');
        }
      });
    });

    /*************************** End Dynamic Employyee List with Datatables ****************************/

    $(document).on('change', '.all_department', function() {
      if(this.checked == true)
        $('.single_department').prop('checked',true);
      else
        $('.single_department').prop('checked',false);
    });

    $(document).on('change', '.single_department', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#department_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_department          = $('.single_department').length;
      var total_checked_single_department  = $('.single_department:checked').length;
      
      if(total_checked_single_department < total_single_department && total_checked_single_department > 0){
        $('.all_department').prop('indeterminate',true); 
      }
      else if(total_checked_single_department == total_single_department){
        $('.all_department').prop('indeterminate',false);
        $('.all_department').prop('checked',true);
      }
      else if(total_checked_single_department == 0){
        $('.all_department').prop('indeterminate',false);
        $('.all_department').prop('checked',false);
      }
    }

    $(document).on('click','#department_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_department:checked'); // Select only checked checkboxes
     
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); // Assuming the value holds the department_id
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
            title: 'FAILURE !!',
              text: "Please select at least one department record to export.",
              icon: "warning",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                  confirmButton: "btn btn-primary"
              }
          });
          return; // Stop further execution
      }

      // Redirect to export URL with selected parameters
      var exportUrl = '<?= base_url('department/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",");

      window.location.href = exportUrl;
    });

    
  });
</script>