<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('employee')?>"><?=$this->lang->line('header_hrmodule')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_leave')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('leave_list')?></li>
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
            
            <div class="card-body">
              <div class="row">
                 
                  
                  <div class="col-md-2">
                    <label>Employee</label>
                    <select class="form-control form-control-sm select2bs4" id="employee_id" name="employee_id">
                        <option value="">All</option>
                        <?php 
                          foreach ($employees as $value) {
                        ?>
                            <option value="<?=$value->employee_id?>"><?=$value->first_name.' '.$value->last_name?></option>
                        <?php
                          }
                        ?>
                      </select>
                  </div>

                  <div class="col-md-2">
                    <label><?=$this->lang->line('from_date')?></label>
                    <input type="text" class="form-control datepicker" name="from_date" id="from_date" style="z-index:999 !important;cursor:pointer;" autocomplete="off">
                  </div>

                  <div class="col-md-2">
                    <label><?=$this->lang->line('to_date')?></label>
                    <input type="text" class="form-control datepicker" name="to_date" id="to_date" style="z-index:999 !important;cursor:pointer;" value="<?=date('d-m-Y')?>"autocomplete="off">
                  </div>
                  
                  <div class="col-md-3">
                    <label>Leave Type</label>
                    <select class="form-control form-control-sm select2bs4" id="leave_type">
                      <option value="">All</option>
                      <option value="<?=LEAVE_TYPE_FULL?>">Full</option>
                      <option value="<?=LEAVE_TYPE_HALF?>">Half</option>
                      <option value="<?=LEAVE_TYPE_QUARTER?>">Quarter</option>
                    </select>
                  </div>

                  <div class="col-md-3">
                    <label>Status</label>
                    <select class="form-control form-control-sm select2bs4" id="status">
                      <option value="">All</option>
                      <option value="<?=STATUS_PENDING?>">Pending</option>
                      <option value="<?=STATUS_APPROVED?>">Approved</option>
                      <option value="<?=STATUS_REJECTED?>">Rejected</option>
                    </select>
                  </div>

                </div>
            </div>
            <!-- /.card-body -->
          </div>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('leave_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item  ml-2">
                    <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Leave">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>
                  <?php 
                    if($this->permission_model->has_permission('add_leave'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    
                    <button type="button" class="btn btn-primary add_leave_modal">
                      <i class="fas fa-comment-dollar mr-2"></i><?=$this->lang->line('leave_add')?>
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
                    <th width="2%"><input type="checkbox" class="all_leave"></th>
                    <th><?=$this->lang->line('leave_employee_id')?></th>
                    <th><?=$this->lang->line('leave_date')?></th>
                    <th><?=$this->lang->line('leave_type')?></th>
                    <th><?=$this->lang->line('leave_status')?></th>
                    <th><?=$this->lang->line('leave_action')?></th>
                  </tr>
                </thead>
                
                <tbody id="leave_list">
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('leave_employee_id')?></th>
                    <th><?=$this->lang->line('leave_date')?></th>
                    <th><?=$this->lang->line('leave_type')?></th>
                    <th><?=$this->lang->line('leave_status')?></th>
                    <th><?=$this->lang->line('leave_action')?></th>
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

<div class="modal fade" id="add_leave_modal" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      
    </div>
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="delete_leave" data-backdrop="static">
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

      // Get the first date of the current month
      var date = new Date();
      var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);

      // Format the date as mm/dd/yyyy
      var day = ("0" + firstDay.getDate()).slice(-2);
      var month = ("0" + (firstDay.getMonth() + 1)).slice(-2);
      var formattedDate = day + "/" + month + "/" + firstDay.getFullYear();

       // Get the last date of the current month
       var lastDay = new Date(date.getFullYear(), date.getMonth() + 1, 0);
        var day = ("0" + lastDay.getDate()).slice(-2);
        var month = ("0" + (lastDay.getMonth() + 1)).slice(-2);
        var formattedLastDate = day + "/" + month + "/" + lastDay.getFullYear();

      // Set the datepicker value
      $('#from_date').datepicker({
          dateFormat: 'dd/mm/yy'
      }).datepicker("setDate", formattedDate);

       // Set the datepicker value for to_date
       $('#to_date').datepicker({
            dateFormat: 'dd/mm/yy'
        }).datepicker("setDate", formattedLastDate);

    /*************************** Start Dynamic salary List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    { 

      var employee_id   = $('#employee_id').val();
      var status        = $('#status').val();
      var leave_type    = $('#leave_type').val();
      var from_date     = $('#from_date').val();
      var to_date       = $('#to_date').val();

      $('#example').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [], //Initial no order.
        "pageLength": 50,
 
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('leave/ajax_list')?>",
            "type": "POST",
            "data":  {
              'employee_id' : employee_id,
              'status' : status,
              'leave_type' : leave_type,
              'from_date' : from_date,
              'to_date' : to_date,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
            }
        },

        'initComplete':function(settings, json){
          
        },  
 
        //Set column definition initialisation properties.
        "columnDefs": [
          { 
            "targets": [ 0,5 ], //first column / numbering column
            "orderable": false, //set not orderable
          },
        ],
      });
    }

    function reinitialize(){
      $(".select2bs4").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }

    $(document).on('change','#employee_id, #status, #from_date, #to_date, #leave_type',function(e){
      initialize_datatable();
    })

    $(document).on('click', ".add_leave_modal" ,function(event){
      event.preventDefault();
      
      var leave_id  = $(this).data('leave_id');
      leave_id      = (leave_id === undefined) ? "" : "/"+leave_id;
      

      $.ajax({
        url: "<?=base_url('leave/add')?>"+leave_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_leave_modal').find('.modal-content').html(data.add_leave_modal_body);
          $('#add_leave_modal').modal('show');
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

    $(document).on('submit','#addLeaveForm',function(e){
      
      e.preventDefault();

      $('#addLeaveSubmit').text('Please wait...').attr('disabled','disabled');
      

      var isError = false;

      $('form#addLeaveForm .field_validation').each(function() {
          
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#addLeaveForm  #err_"+id).text(field+ " field is required.");
          $('form#addLeaveForm  #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addLeaveForm #err_"+id).text("");
          $('form#addLeaveForm #'+id).removeClass('is-invalid');
          $('form#addLeaveForm #'+id).addClass('is-valid');
        }
      });
      
      if(isError == true)
      {
        $('#addLeaveSubmit').text('Save').removeAttr('disabled');
        return false;
      }
      else
      {
        var formData = $('#addLeaveForm').serialize();
        
        var leave_id = $('#add_leave_modal').find('input[name="leave_id"]').val();
        leave_id = (leave_id == '') ? "" : "/"+leave_id;

        $.ajax({
          url: "<?php echo base_url('leave/add')?>"+leave_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_leave_modal').modal('hide');
              $('form#addLeaveForm #addLeaveSubmit').text('Save').removeAttr('disabled');

              Swal.fire({
                title:'SUCCESS !!',
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
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                  $("#err_" + key).text(value).addClass('invalid-feedback').show();
                  $('#' + key).addClass('is-invalid');
              });
              $('#addLeaveSubmit').text('Save').removeAttr('disabled');
            }
            else
            {
              Swal.fire({
                title:'FAILURE !!',
                text: response.message,
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              $('#addLeaveSubmit').text('Save').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addLeaveForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addLeaveForm #err_"+id).text(field+ " field is required.");
          $('form#addLeaveForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addLeaveForm #err_"+id).text("");
          $('form#addLeaveForm #'+id).removeClass('is-invalid');
          $('form#addLeaveForm #'+id).addClass('is-valid');
        }
    });


    $(document).on('show.bs.modal','#delete_leave', function (e) {
      var leave_id = $(e.relatedTarget).data('leave_id');
      $('#delete_leave').find('#id').val(leave_id);

      // alert(leave_id);

      $.ajax({
        url: "<?php echo base_url('leave/leave_delete_confirmation')?>",
        type: "POST",
        data:{
          'leave_id': leave_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_leave').find('.modal-content').html(data.leave_delete_modal_body);
        }
      });


    });

    $(document).on('submit', '#deleteLeaveForm' ,function (e) {
      e.preventDefault();

      $('#deleteLeaveSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteLeaveForm').serialize();

      $.ajax({
      url: "<?php echo base_url('leave/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#delete_leave').modal('hide');
            $('form#deleteLeaveForm #deleteLeaveSubmit').text('Delete').removeAttr('disabled');
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

          $('#deleteLeaveSubmit').text('Delete').removeAttr('disabled');
        }
      });
    });

    /*************************** End Dynamic Employyee List with Datatables ****************************/

    $(document).on('change', '.all_leave', function() {
      if(this.checked == true)
        $('.single_leave').prop('checked',true);
      else
        $('.single_leave').prop('checked',false);
    });

    $(document).on('change', '.single_leave', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#leave_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_leave          = $('.single_leave').length;
      var total_checked_single_leave  = $('.single_leave:checked').length;
      
      if(total_checked_single_leave < total_single_leave && total_checked_single_leave > 0){
        $('.all_leave').prop('indeterminate',true); 
      }
      else if(total_checked_single_leave == total_single_leave){
        $('.all_leave').prop('indeterminate',false);
        $('.all_leave').prop('checked',true);
      }
      else if(total_checked_single_leave == 0){
        $('.all_leave').prop('indeterminate',false);
        $('.all_leave').prop('checked',false);
      }
    }

    $(document).on('click','#leave_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_leave:checked'); // Select only checked checkboxes
      var employee_id = $('#employee_id').val();
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      var leave_type = $('#leave_type').val();
      var status = $('#status').val();
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); // Assuming the value holds the leave_id
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
            title: 'FAILURE !!',
              text: "Please select at least one leave record to export.",
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
      var exportUrl = '<?= base_url('leave/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",") +
                  "&employee_id=" + employee_id +
                  "&from_date=" + from_date +
                  "&to_date=" + to_date +
                  "&leave_type=" + leave_type +
                  "&status=" + status;

      window.location.href = exportUrl;
    });

    
  });
</script>