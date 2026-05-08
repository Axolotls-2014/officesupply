<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><?=$this->lang->line('header_setting')?></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('header_email_template')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <!-- /.col -->
          <div class="col-md-12">
            <div class="card card-primary card-outline">
              <div class="card-header">
                <h3 class="card-title"><?=$this->lang->line('header_email_template')?></h3>
                <?php 
                if($this->permission_model->has_permission('add_email_template'))
                  {
                ?>
                <div class="card-tools">
                  <button type="button" class="btn btn-block btn-primary btn-sm add_email_template_modal" data-toggle="modal" data-target="#add_email_template_modal" data-tt="tooltip" title="Click here to Add Email Template" data-email_template_id="">Add Email Template</button>
                </div>
                <?php
                  }
                ?>
                <!-- /.card-tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <table id="example" class="table table-bordered table-striped">
                  <thead>
                    <tr>
                      <th><?=$this->lang->line("email_template_template_name")?></th>
                      <th><?=$this->lang->line("email_template_from_name")?></th>
                      <th><?=$this->lang->line("email_template_from_email")?></th>
                      <th><?=$this->lang->line("email_template_module")?></th>
                      <th><?=$this->lang->line("email_template_created_date")?></th>
                      <th width="15%"><?=$this->lang->line("action")?></th>   
                    </tr>
                  </thead>
                  <tbody>
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


<div class="example-modal">
  <div class="modal fade" id="add_email_template_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="example-modal">
  <div class="modal fade" id="copy_email_template_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>



<div class="example-modal">
  <div class="modal fade" id="delete_email_template" data-backdrop="static" data-keyboard="false">
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

     $('[data-toggle="tooltip"]').tooltip();

     function insertAtCursor(input, textToInsert) {
        // Get the current position of the cursor
        var startPos = input.selectionStart;
        var endPos = input.selectionEnd;
        
        // Insert the text at the cursor position
        input.value = input.value.substring(0, startPos) + textToInsert + input.value.substring(endPos, input.value.length);
        
        // Move the cursor to the end of the inserted text
        input.selectionStart = input.selectionEnd = startPos + textToInsert.length;
      }

      $(document).on('click', ' .clipboard-text', function() {
        var textToCopy = $(this).attr('data-clipboard-text');
        
        // Find the subject input field
        var subjectInput = document.getElementById('subject');
        
        // Insert the text at the cursor position in the subject input field
        insertAtCursor(subjectInput, textToCopy);

        // Copy text to clipboard
      
      });

      $(document).on('click', '.clipboard-icon', function() {
        var textToCopy = $(this).attr('data-clipboard-text');
        
        // Copy text to clipboard
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val(textToCopy).select();
        document.execCommand("copy");
        $temp.remove();

        // Show the alert message in the top-right corner
        var $copyAlert = $('#copy-alert');
        $copyAlert.fadeIn().delay(1000).fadeOut();
      });




    const emailTemplateToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    initialize_datatable();

    function initialize_datatable()
    {
      $('#example').DataTable({ 
  
          "processing": true, //Feature control the processing indicator.
          "serverSide": true, //Feature control DataTables' server-side processing mode.
          "bDestroy": true, //Destroy before reinitialise
          "order": [], //Initial no order.
  
          // Load data for the table's content from an Ajax source
          "ajax": {
            "url": "<?php echo site_url('email_template/ajax_list')?>",
              "type": "POST",
              "data":  {
                '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
              }
          },

          'initComplete':function(settings, json){
            reinitialise();
          },  
  
          //Set column definition initialisation properties.
          "columnDefs": [
            { 
              "targets": [5], //first column / numbering column
              "orderable": false, //set not orderable
            },
          ],
      });
    }

    function reinitialise()
    {
      $('[data-tt="tooltip"]').tooltip({trigger : 'hover'}); 
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

    /* add email template start */

    $(document).on('click', ".copy_email_template_modal" ,function(event){
      event.preventDefault();
      
      var email_template_id = $(this).data('email_template_id');
      email_template_id = (email_template_id === undefined) ? "" : "/"+email_template_id;

      $.ajax({
        url: "<?=base_url('email_template/copy')?>"+email_template_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#copy_email_template_modal').find('.modal-content').html(data.copy_email_template_modal_body);
          $('#copy_email_template_modal').modal('show');

          // Initialize Summernote instances with different heights
          $('#mail_header').summernote({
              height: 80 // Set the height for the header textarea
          });
          $('#mail_body').summernote({
              height: 200 // Set the height for the body textarea
          });
          $('#mail_footer').summernote({
              height: 150 // Set the height for the footer textarea
          });
          initialize_datatable();
        },
        // error: function (xhr, ajaxOptions, thrownError) {
        //   Swal.fire({
        //     // text: xhr.status + thrownError + ajaxOptions,
        //     icon: "error",
        //     buttonsStyling: !1,
        //     confirmButtonText: "Ok, got it!",
        //     customClass: {
        //         confirmButton: "btn btn-primary"
        //     }
        //   });
        // }
      });
    });

    $(document).on('submit','#copyemailTemplateForm',function(e){
    
      
      e.preventDefault();

      $('#copyEmailTemplateSubmit').text('Please wait...').attr('disabled','disabled');

      var formData = $('#copyemailTemplateForm').serialize();
      // alert(formData);
            
      var isError = false;

      $('form#copyemailTemplateForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#copyemailTemplateForm  #err_"+id).text(field+ " field is required.");
            $('form#copyemailTemplateForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#copyemailTemplateForm #err_"+id).text("");
            $('form#copyemailTemplateForm #'+id).removeClass('is-invalid');
            $('form#copyemailTemplateForm #'+id).addClass('is-valid');
          }

      });
      
      if(isError == true)
      {
        
        $('#copyEmailTemplateSubmit').text('Submit').removeAttr('disabled');
        return false;
      }
      else
      {
      
        var email_template_id = $('#copy_email_template_modal').find('input[name="id"]').val();
        email_template_id = (email_template_id == '') ? "" : "/"+email_template_id;

        $.ajax({
          url: "<?php echo base_url('email_template/copy')?>"+email_template_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#copy_email_template_modal').modal('hide');
              $('form#copyemailTemplateForm #copyEmailTemplateSubmit').text('Submit').removeAttr('disabled');

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
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                $("form#copyemailTemplateForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#copyemailTemplateForm #copyEmailTemplateSubmit').text('Submit').removeAttr('disabled');
            }
            else if (response.code == 3) 
            {

              
              // Module already exists error
              Swal.fire({
                  title: 'FAILURE !!',
                  text: response.message,
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                      confirmButton: "btn btn-primary"
                  }
              });

              $('form#copyemailTemplateForm #copyEmailTemplateSubmit').text('Submit').removeAttr('disabled');
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
              $('#copyEmailTemplateSubmit').text('Submit').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#copyemailTemplateForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#copyemailTemplateForm #err_"+id).text(field+ " field is required.");
          return false;
        }
        else{
          $("form#copyemailTemplateForm #err_"+id).text("");
        }
    });

    $(document).on('click', ".add_email_template_modal" ,function(event){
      event.preventDefault();
      
      var email_template_id = $(this).data('email_template_id');
      email_template_id = (email_template_id === undefined) ? "" : "/"+email_template_id;

      $.ajax({
        url: "<?=base_url('email_template/add')?>"+email_template_id,
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_email_template_modal').find('.modal-content').html(data.add_email_template_modal_body);
          $('#add_email_template_modal').modal('show');

          // Initialize Summernote instances with different heights
          $('#mail_header').summernote({
              height: 80 // Set the height for the header textarea
          });
          $('#mail_body').summernote({
              height: 200 // Set the height for the body textarea
          });
          $('#mail_footer').summernote({
              height: 150 // Set the height for the footer textarea
          });
          initialize_datatable();
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

    
    $(document).on('submit','#addemailTemplateForm',function(e){
    
      
      e.preventDefault();

      $('#addEmailTemplateSubmit').text('Please wait...').attr('disabled','disabled');

      var formData = $('#addemailTemplateForm').serialize();
      // alert(formData);
            
      var isError = false;

      $('form#addemailTemplateForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addemailTemplateForm  #err_"+id).text(field+ " field is required.");
            $('form#addemailTemplateForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addemailTemplateForm #err_"+id).text("");
            $('form#addemailTemplateForm #'+id).removeClass('is-invalid');
            $('form#addemailTemplateForm #'+id).addClass('is-valid');
          }


       
      });
      
      if(isError == true)
      {
        
        $('#addEmailTemplateSubmit').text('Submit').removeAttr('disabled');
        return false;
      }
      else
      {
      
        var email_template_id = $('#add_email_template_modal').find('input[name="id"]').val();
        email_template_id = (email_template_id == '') ? "" : "/"+email_template_id;

        $.ajax({
          url: "<?php echo base_url('email_template/add')?>"+email_template_id,
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_email_template_modal').modal('hide');
              $('form#addemailTemplateForm #addEmailTemplateSubmit').text('Submit').removeAttr('disabled');

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
            else if(response.code == 2)
            {
              $.each(response.errors, function(key, value) {
                $("form#addemailTemplateForm  #err_"+key).text(value);
                $('.'+$("#"+key).closest('div.tab-pane').attr('id')+' a').tab('show');
              });
              $('form#addemailTemplateForm #addEmailTemplateSubmit').text('Save').removeAttr('disabled');
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
              $('#addEmailTemplateSubmit').text('Save').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addemailTemplateForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addemailTemplateForm #err_"+id).text(field+ " field is required.");
          return false;
        }
        else{
          $("form#addemailTemplateForm #err_"+id).text("");
        }
    });

    
    $(document).on('show.bs.modal','#delete_email_template', function (e) {
      // alert();
      var email_template_id = $(e.relatedTarget).data('email_template_id');
      // $('#delete_email_template').find('#id').val(email_template_id);

      // alert(email_template_id);

      $.ajax({
        url: "<?php echo base_url('email_template/email_template_delete_confirmation')?>",
        type: "POST",
        data:{
          'email_template_id': email_template_id,
          '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
          $('#delete_email_template').find('.modal-content').html(data.email_template_delete_modal_body);
        }
      });


    });

      // Delete record with please wait text
    $(document).on('submit','#deleteEmailTemplateForm',function(e){
      $('#deleteEmailTemplateSubmit').text('<?=$this->lang->line('please_wait')?>').attr('disabled','disabled');
    });

    

    
  });
</script>
