<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('employee')?>"><?=$this->lang->line('header_hrmodule')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_position')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('position_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('position_list')?></h3>
             
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item  ml-2">
                    <a class="nav-link export btn-sm btn-secondary text-white" href="#" data-tt="tooltip" title="Click here to Export Position">
                      <i class="fas fa-share"></i> Export
                    </a>
                  </li>

                  <?php 
                    if($this->permission_model->has_permission('add_position'))
                    {
                  ?>
                  <li class="nav-item ml-2">
                    
                    <button type="button" class="btn btn-primary add_position_modal">
                      <i class="fas fa-user mr-2"></i><?=$this->lang->line('position_add')?>
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
                    <th width="2%"><input type="checkbox" class="all_position"></th>
                    <th><?=$this->lang->line('position_code')?></th>
                    <th><?=$this->lang->line('position_title')?></th>
                    <th width="15%"><?=$this->lang->line('position_action')?></th>
                  </tr>
                </thead>
                
                <tbody id="position_list">
              
                </tbody>
                <tfoot>
                  <tr>
                    <th></th>
                    <th><?=$this->lang->line('position_code')?></th>
                    <th><?=$this->lang->line('position_title')?></th>
                    <th><?=$this->lang->line('position_action')?></th>
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

<div class="modal fade" id="add_position_modal" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content ">
      
    </div>
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="delete_position" data-backdrop="static" data-keyboard="false">
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

    /*************************** Start Dynamic position List with Datatables **************************/

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
          "url": "<?php echo site_url('position/ajax_list')?>",
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

    $(document).on('click', ".add_position_modal" ,function(event){
      event.preventDefault();
      
      var position_id = $(this).data('position_id');
      position_id = (position_id === undefined) ? "" : "/"+position_id;

      $.ajax({
        url: "<?=base_url('position/add')?>"+position_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_position_modal').find('.modal-content').html(data.add_position_modal_body);
          $('#add_position_modal').modal('show');
          reinitialize();
        
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

    $(document).on('submit','#addPositionForm',function(e){
      
      e.preventDefault();

      $('#addPositionSubmit').text('Please wait...').attr('disabled','disabled');
      

      var isError = false;

      $('form#addPositionForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $('.'+$(this).closest('div.tab-pane').attr('id')+' a').tab('show');
            $("form#addPositionForm  #err_"+id).text(field+ " field is required.");
            isError = true;
          }
          else
          {
            $("form#addPositionForm #err_"+id).text("");
          }
      });
      
      if(isError == true)
      {
        $('#addPositionSubmit').text('Save').removeAttr('disabled');
        return false;
      }
      else
      {
        var formData = $('#addPositionForm').serialize();
        
        var position_id = $('#add_position_modal').find('input[name="position_id"]').val();
        position_id = (position_id == '') ? "" : "/"+position_id;

        $.ajax({
          url: "<?php echo base_url('position/add')?>"+position_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_position_modal').modal('hide');
              $('form#addPositionForm #addPositionSubmit').text('Save').removeAttr('disabled');

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
                $("form#addPositionForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#addPositionForm #addPositionSubmit').text('Save').removeAttr('disabled');
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
              $('#addPositionSubmit').text('Save').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addPositionForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addPositionForm #err_"+id).text(field+ " field is required.");
          $('form#addPositionForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addPositionForm #err_"+id).text("");
          $('form#addPositionForm #'+id).removeClass('is-invalid');
          $('form#addPositionForm #'+id).addClass('is-valid');
        }
    });


    $(document).on('show.bs.modal','#delete_position', function (e) {
      var position_id = $(e.relatedTarget).data('position_id');
      $('#delete_position').find('#id').val(position_id);

      // alert(position_id);

      $.ajax({
        url: "<?php echo base_url('position/position_delete_confirmation')?>",
        type: "POST",
        data:{
          'position_id': position_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_position').find('.modal-content').html(data.position_delete_modal_body);
        }
      });


    });

    $(document).on('submit', '#deletePositionForm' ,function (e) {
      e.preventDefault();

      $('#deletePositionSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#deletePositionForm').serialize();

      $.ajax({
      url: "<?php echo base_url('position/delete')?>",
        type: "POST",
        data: formData,
        dataType: "JSON",
        success: function(response){
          if(response.code==1)
          { 
           
            $('#delete_position').modal('hide');
            $('form#deletePositionForm #deletePositionSubmit').text('Delete').removeAttr('disabled');
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

          $('#deletePositionSubmit').text('Delete').removeAttr('disabled');
        }
      });
    });

   
    /*************************** End Dynamic Employyee List with Datatables ****************************/

    $(document).on('change', '.all_position', function() {
      if(this.checked == true)
        $('.single_position').prop('checked',true);
      else
        $('.single_position').prop('checked',false);
    });

    $(document).on('change', '.single_position', function() {

      // Set select all checkbox status
      set_select_all_checkbox_status();
    });

    $('#position_list').on('click', ':checkbox', function(event) {
      // Stop the event from propagating up to the row
      event.stopPropagation();
    });

    function set_select_all_checkbox_status()
    {
      var total_single_position          = $('.single_position').length;
      var total_checked_single_position  = $('.single_position:checked').length;
      
      if(total_checked_single_position < total_single_position && total_checked_single_position > 0){
        $('.all_position').prop('indeterminate',true); 
      }
      else if(total_checked_single_position == total_single_position){
        $('.all_position').prop('indeterminate',false);
        $('.all_position').prop('checked',true);
      }
      else if(total_checked_single_position == 0){
        $('.all_position').prop('indeterminate',false);
        $('.all_position').prop('checked',false);
      }
    }

    $(document).on('click','#position_list tr',function(event){

      var tr = $(this).closest('tr');

      tr.find(':checkbox').prop('checked', !tr.find(':checkbox').prop('checked'));
      set_select_all_checkbox_status();
    })

    $(document).on('click', '.export', function(event) {
      event.preventDefault();

      var checkboxes = $('.single_position:checked'); // Select only checked checkboxes
     
      
      var checkedIds = [];

      checkboxes.each(function() {
          checkedIds.push($(this).val()); // Assuming the value holds the position_id
      });

      // Check if no checkboxes are checked
      if (checkedIds.length === 0) {
          // Show SweetAlert message
          Swal.fire({
              title: 'FAILURE !!',
              text: "Please select at least one position record to export.",
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
      var exportUrl = '<?= base_url('position/export'); ?>';
      exportUrl += "?data=" + checkedIds.join(",");

      window.location.href = exportUrl;
    });

    
  });
</script>