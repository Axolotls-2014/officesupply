<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('employee')?>"><?=$this->lang->line('header_hrmodule')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_holidays')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('holiday_list')?></li>
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
                 
                 <div class="col-md-3">
                  <label>Month</label>
                  <select class="form-control form-control-sm select2bs4" id="month">
                    <option value="">All</option>
                    <option value="1">January</option>
                    <option value="2">February</option>
                    <option value="3">March</option>
                    <option value="4">April</option>
                    <option value="5">May</option>
                    <option value="6">June</option>
                    <option value="7">July</option>
                    <option value="8">August</option>
                    <option value="9">September</option>
                    <option value="10">October</option>
                    <option value="11">November</option>
                    <option value="12">December</option>
                  </select>
                </div>

                <div class="col-md-3">
                  <label>Year</label>
                  <select class="form-control form-control-sm select2bs4" id="year">
                    
                  </select>
                </div>
              </div>
            </div>
            <!-- /.card-body -->
          </div>

          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('holiday_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item  ml-2">
                    <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Holiday">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>
                  <?php 
                    if($this->permission_model->has_permission('add_holiday'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    
                    <button type="button" class="btn btn-primary add_holiday_modal">
                      <i class="fas fa-holly-berry mr-2"></i><?=$this->lang->line('holiday_add')?>
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
                    <th width="2%"><input type="checkbox" class="all_holiday"></th>
                    <th><?=$this->lang->line('holiday_date')?></th>
                    <th><?=$this->lang->line('holiday_name')?></th>
                    <th><?=$this->lang->line('holiday_action')?></th>
                  </tr>
                </thead>
                
                <tbody id="holiday_list">
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('holiday_date')?></th>
                    <th><?=$this->lang->line('holiday_name')?></th>
                    <th><?=$this->lang->line('holiday_action')?></th>
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

<div class="modal fade" id="add_holiday_modal" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      
    </div>
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="delete_holiday" data-backdrop="static">
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

    // Populate year options
    var currentYear = new Date().getFullYear();
    var startYear = 1991;
    
    for (var year = currentYear; year >= startYear; year--) {
        $('#year').append(new Option(year, year));
    }

    /*************************** Start Dynamic holiday List with Datatables **************************/

    initialize_datatable();
    function initialize_datatable()
    { 
      var month               = $('#month').val();
      var year                = $('#year').val();

      $('#example').DataTable({ 
        
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "bDestroy": true, //Destroy before reinitialise
        "order": [], //Initial no order.
        "pageLength": 50,
 
        // Load data for the table's content from an Ajax source
        "ajax": {
          "url": "<?php echo site_url('holiday/ajax_list')?>",
            "type": "POST",
            "data":  {
              'month': month,
              'year': year,
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

    $(document).on('change','#month, #year',function(e){
      initialize_datatable();
    })

    function reinitialize(){
      $(".select2bs4").select2({theme:'bootstrap4'});
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
    }

    $(document).on('click', ".add_holiday_modal" ,function(event){
      event.preventDefault();
      
      var holiday_id = $(this).data('holiday_id');
      holiday_id = (holiday_id === undefined) ? "" : "/"+holiday_id;

      $.ajax({
        url: "<?=base_url('holiday/add')?>"+holiday_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_holiday_modal').find('.modal-content').html(data.add_holiday_modal_body);
          $('#add_holiday_modal').modal('show');
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

    $(document).on('submit','#addHolidayForm',function(e){
      
      e.preventDefault();

      $('#addHolidaySubmit').text('Please wait...').attr('disabled','disabled');
      

      var isError = false;

      $('form#addHolidayForm .field_validation').each(function() {
          
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(value==null || value==""){
          $("form#addHolidayForm  #err_"+id).text(field+ " field is required.");
          $('form#addHolidayForm  #'+id).addClass('is-invalid');
          isError = true;
        }
        else
        {
          $("form#addHolidayForm #err_"+id).text("");
          $('form#addHolidayForm #'+id).removeClass('is-invalid');
          $('form#addHolidayForm #'+id).addClass('is-valid');
        }
      });
      
      if(isError == true)
      {
        $('#addHolidaySubmit').text('Save').removeAttr('disabled');
        return false;
      }
      else
      {
        var formData = $('#addHolidayForm').serialize();
        
        var holiday_id = $('#add_holiday_modal').find('input[name="holiday_id"]').val();
        holiday_id = (holiday_id == '') ? "" : "/"+holiday_id;

        $.ajax({
          url: "<?php echo base_url('holiday/add')?>"+holiday_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_holiday_modal').modal('hide');
              $('form#addHolidayForm #addHolidaySubmit').text('Save').removeAttr('disabled');

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
                    $("#err_" + key).text(value).addClass('invalid-feedback').show();
                    $('#' + key).addClass('is-invalid');
                });
                $('#addHolidaySubmit').text('Save').removeAttr('disabled');

             
            }
            else
            {
              Swal.fire({
                text: response.message,
                title: 'FAILURE !!',
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                    confirmButton: "btn btn-primary"
                },
                timer:1000
              });
              $('#addHolidaySubmit').text('Save').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addHolidayForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addHolidayForm #err_"+id).text(field+ " field is required.");
          $('form#addHolidayForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addHolidayForm #err_"+id).text("");
          $('form#addHolidayForm #'+id).removeClass('is-invalid');
          $('form#addHolidayForm #'+id).addClass('is-valid');
        }
    });


    $(document).on('show.bs.modal','#delete_holiday', function (e) {
      var holiday_id = $(e.relatedTarget).data('holiday_id');
      $('#delete_holiday').find('#id').val(holiday_id);

      // alert(holiday_id);

      $.ajax({
        url: "<?php echo base_url('holiday/holiday_delete_confirmation')?>",
        type: "POST",
        data:{
          'holiday_id': holiday_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_holiday').find('.modal-content').html(data.holiday_delete_modal_body);
        }
      });


    });

    $(document).on('submit', '#deleteHolidayForm' ,function (e) {
      e.preventDefault();

      $('#deleteHolidaySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deleteHolidayForm').serialize();

      $.ajax({
      url: "<?php echo base_url('holiday/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#delete_holiday').modal('hide');
            $('form#deleteHolidayForm #deleteHolidaySubmit').text('Delete').removeAttr('disabled');
            initialize_datatable();

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

          $('#deleteHolidaySubmit').text('Delete').removeAttr('disabled');
        }
      });
    });

    /*************************** End Dynamic Employyee List with Datatables ****************************/

    $(document).on('change', '.all_holiday', function() {
      if(this.checked == true)
        $('.single_holiday').prop('checked',true);
      else
        $('.single_holiday').prop('checked',false);
    });

    $(document).on('change', '.single_holiday', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#holiday_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_holiday          = $('.single_holiday').length;
      var total_checked_single_holiday  = $('.single_holiday:checked').length;
      
      if(total_checked_single_holiday < total_single_holiday && total_checked_single_holiday > 0){
        $('.all_holiday').prop('indeterminate',true); 
      }
      else if(total_checked_single_holiday == total_single_holiday){
        $('.all_holiday').prop('indeterminate',false);
        $('.all_holiday').prop('checked',true);
      }
      else if(total_checked_single_holiday == 0){
        $('.all_holiday').prop('indeterminate',false);
        $('.all_holiday').prop('checked',false);
      }
    }

    $(document).on('click','#holiday_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_holiday:checked'); // Select only checked checkboxes
      
      var month = $('#month').val();
      var year = $('#year').val();
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); // Assuming the value holds the holiday_id
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
              title: 'FAILURE !!',
              text: "Please select at least one holiday record to export.",
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
      var exportUrl = '<?= base_url('holiday/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",") +
                  
                  "&month=" + month +
                  "&year=" + year;

      window.location.href = exportUrl;
    });

    
  });
</script>