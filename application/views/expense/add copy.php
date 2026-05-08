  <?php $this->load->view('layout/header');?>
    <link href="<?php echo base_url();?>assets/js/dropzone.min.css" rel="stylesheet">

    <div class="wrapper">
      <div class="content-wrapper">
        <section class="content-header">

            <div class="row mb-2">
              <div class="col-sm-12">
                <ol class="breadcrumb breadcrumb-custom float-sm-left">
                  <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                  <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_expense')?></a></li>
                  <li class="breadcrumb-item "><a href="<?=base_url('expense')?>"><?=$this->lang->line('expense_header')?></a></li>
                  <li class="breadcrumb-item active"><?=$this->lang->line('expense_add')?></li>
                </ol>
              </div>
            </div>
        </section>

        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" name="addExpenseForm" id="addExpenseForm" method="post" action="" enctype="multipart/form-data">
                <div class="card card-info">
                  <div class="card-header">
                    <h3 class="card-title"><?=$this->lang->line('expense_add')?></h3>
                  </div>
                  <div class="card-body">
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_date')?><span class="text-danger">*</span></label>
                      <div class="col-sm-4">
                        <input type="text" name="date" data-date-format="yyyy-mm-dd" class="form-control form-control-sm field_validation datepicker" id="date" placeholder="<?=$this->lang->line('expense_date')?>"><?=form_error('date', '<div class="text-danger">', '</div>');?>
                        <span id="err_date" class="error invalid-feedback"><?=form_error('date');?></span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">
                        <?=$this->lang->line('expense_expense_category')?>
                      </label>
                      <div class="col-sm-4">
                        <div class="input-group">
                          <select class="form-control form-control-sm select2bs4 field_validation" name="expense_category_id" id="expense_category_id" width="100%">
                            <?php
                              foreach ($expense_categories as $value) {
                            ?>
                              <option value="<?=$value->id;?>" <?php echo set_select('expense_expense_category', $value->id); ?>>
                                <?= ucfirst($value->name);?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span class="input-group-append">
                            <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_expense_category_modal" data-tt="tooltip" accesskey="e"><i class="fas fa-plus"></i>
                            </button>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label">
                        <?=$this->lang->line('expense_merchant')?>
                      </label>
                      <div class="col-sm-4">
                        <div class="input-group">
                          <select class="form-control form-control-sm select2bs4 field_validation" name="merchant_id" id="merchant_id" width="100%">
                            <?php
                              foreach ($merchants as $value) {
                            ?>
                              <option value="<?=$value->id;?>" <?php echo set_select('merchant_id', $value->id); ?>>
                                <?= ucfirst($value->company_name);?>
                              </option>
                            <?php 
                              }
                            ?>
                          </select>
                          <span class="input-group-append">
                            <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_merchant_modal" data-tt="tooltip" accesskey="e"><i class="fas fa-plus"></i>
                            </button>
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_amount')?><span class="text-danger">*</span></label>
                      <div class="col-sm-4">
                        <input type="number" name="amount" value="<?=set_value('amount') ?>" class="form-control form-control-sm field_validation" id="amount" placeholder="<?=$this->lang->line('expense_amount')?>"><?=form_error('amount', '<div class="text-danger">', '</div>');?>
                        <span id="err_amount" class="error invalid-feedback"><?=form_error('amount');?></span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_cgst')?><span class="text-danger">*</span></label>
                      <div class="col-sm-4">
                        <input type="number" name="cgst" value="<?=set_value('igst',0.0)?>" class="form-control form-control-sm field_validation" id="cgst" placeholder="<?=$this->lang->line('expense_cgst')?>">
                        <?=form_error('cgst', '<div class="text-danger">', '</div>');?>
                        <span id="err_cgst" class="error invalid-feedback"><?=form_error('cgst');?></span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_sgst')?><span class="text-danger">*</span></label>
                      <div class="col-sm-4">
                        <input type="number" name="sgst" value="<?=set_value('igst',0.0)?>" class="form-control form-control-sm field_validation" id="sgst" placeholder="<?=$this->lang->line('expense_sgst')?>"><?=form_error('sgst', '<div class="text-danger">', '</div>');?>
                        <span id="err_sgst" class="error invalid-feedback"><?=form_error('sgst');?></span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_igst')?><span class="text-danger">*</span></label>
                      <div class="col-sm-4">
                        <input type="number" name="igst" value="<?=set_value('igst',0.0)?>" class="form-control form-control-sm field_validation" id="igst" placeholder="<?=$this->lang->line('expense_igst')?>"><?=form_error('igst', '<div class="text-danger">', '</div>');?>
                        <span id="err_igst" class="error invalid-feedback"><?=form_error('igst');?></span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_total_amount')?><span class="text-danger">*</span></label>
                      <div class="col-sm-4">
                        <input type="number" name="total_amount" value="<?=set_value('total_amount') ?>" class="form-control form-control-sm field_validation" id="total_amount" placeholder="<?=$this->lang->line('expense_total_amount')?>" readonly><?=form_error('total_amount', '<div class="text-danger">', '</div>');?>
                        <span id="err_total_amount" class="error invalid-feedback"><?=form_error('total_amount');?></span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_remarks')?></label>
                      <div class="col-sm-4">
                        <input type="text" name="remarks" value="" class="form-control form-control-sm field_validation" id="remarks" placeholder="<?=$this->lang->line('expense_remarks')?>"><?=form_error('remarks', '<div class="text-danger">', '</div>');?>
                        <span id="err_remarks" class="error invalid-feedback"><?=form_error('remarks');?></span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_itc')?></label>
                      <div class="col-sm-4">
                        <div class="custom-control custom-checkbox">
                            <input class="custom-control-input" name="itc" type="checkbox" id="itc" value="1">
                            <label for="itc" class="custom-control-label"></label>
                          </div>
                        <span id="err_itc" class="error invalid-feedback"><?=form_error('itc');?></span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('expense_document')?></label>
                      <div class="col-sm-4">
                        <div class="input-group">
                          <div class="custom-file">
                            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#upload-files">Upload File</button>&nbsp;&nbsp;
                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#attachment_modal">Attach File</button>
                          </div>
                        </div>
                        <span id="err_document" class="error invalid-feedback"><?=form_error('document');?></span>
                      </div>
                    </div>
                    <div class="form-group row">
                      <div class="col-sm-4">
                        <div id="selected_attachment" class="selected_attachment"> 
                        </div>
                      </div>
                    </div>
                    <br/>
                  </div>
                  <div class="card-footer">
                    <input type="hidden" name="attached_files" value="" id="attached_files">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" name="submit" id="expenseSubmit" class="btn btn-info">
                      <?=$this->lang->line('expense_save')?>
                    </button>
                    <a href="<?=base_url('expense')?>" class="btn btn-default float-right">
                      <?=$this->lang->line('expense_cancel')?>
                    </a>
                  </div>
                </div>
              </form>
              <div class="modal fade" id="attachment_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="<?php echo base_url()?>index.php/expense/fileUpload" enctype="multipart/form-data" class="dropzone" id="image-upload"  method="POST" style="border-style: dotted;border-color: #ffe100; min-height: 275px !important">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                      <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>   
        </section>
      </div>
      <aside class="control-sidebar control-sidebar-dark"></aside>
    </div>
    
    <div class="upload-files">
      <div class="modal fade" id="upload-files" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">uploaded files</h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">

              <div class="row">
                <div class="col-md-12">
                  <style type="text/css">
                    .state-icon {
                        left: 10px;
                    }
                    .list-group-item-primary {
                        color: rgb(255, 255, 255);
                        background-color: rgb(66, 139, 202);
                    }

                  </style>
                  
                  <ul id="check-list-box" class="list-group checked-list-box list_attachment" style="max-height: 300px;overflow: auto;"> 
                    <li class="list-group-item d-flex justify-content-between align-items-center" data-color="success" data-filename="Picture.jpg" data-fileid="175" style="cursor: pointer;">
                        <span class="state-icon glyphicon glyphicon-unchecked"></span>
                        <span style="font-size: 20px;">picture.jpg</span>
                        <span class="badge badge-default badge-pill" style="background-color: white" download>
                          <a href="picture.jpg" target="_blank" style="text-decoration: none; color: blue;">
                            <i class="fa fa-download"></i>
                          </a>
                        </span>
                        <br/>
                        <span style="font-size: 13px;padding-left: 17px;">Uploaded At: 2020-05-09 10:39:15</span>
                        <input type="checkbox" class="hidden">
                    </li>
                  </ul>
                </div>
              </div>
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              <button type="button" class="btn btn-default refresh_attachments"><i class="fa fa-refresh"></i>Refresh</button>
              <button type="button" class="btn btn-danger">Attach file</button>
            </div>
          </div>
        </div>
      </div>
    </div>
<?php $this->load->view('layout/footer');?>
<?php $this->load->view('expense_category/add_expense_category_modal');?>
<?php $this->load->view('merchant/add_merchant_modal');?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>

<script type="text/javascript">
  Dropzone.options.imageUpload =  {
                        maxFilesize:50,
                        parallelUploads:4,
                        acceptedFiles: ".jpeg,.jpg,.png,.gif,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.html,.zip,.rar,.csv"
                              
                      };
</script>

<script type="text/javascript">
  $(document).ready(function(){
    $("#selected_attachment").delegate("button", "click", function(e){
      var attached_files_array = $('#attached_files').val().split(",");
      attached_files_array.splice($.inArray($(this).attr('id'), attached_files_array),1);
      $('#attached_files').val(attached_files_array.join(','));
      // remove file pill
      $(this).parent().remove();
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){
    $('#attachment_modal').on('hide.bs.modal', function () {
      $.ajax({
        url: '<?php echo base_url();?>index.php/expense/getAttachmentsByAjax',
        type: 'GET',
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function(response){

          var files               = response['data'];
          
          var selectedFilesId     = new Array();
          var attached_files_id   = $('#attached_files').val();

          if(attached_files_id != '')
          {
            selectedFilesId = attached_files_id.val().split(",");
          }

          $('#selected_attachment').html('');

          for(var i = 0 ; i < files.length ; i++)
          {
            

            var attachment_name_array  = files[i].attachment_name.split(".");
            var attachment_name        = attachment_name_array[0];
            var ext                    = attachment_name_array[1];
            var actual_attachment_name = attachment_name.substr(0,attachment_name.length);
            var actual_file            = actual_attachment_name+"."+ext;

            // var selectedFilesId        = $(this).attr('id'); 
             // var attached_files_id = '';


            if(selectedFilesId.indexOf(files[i].id) == -1 && files[i].id != '')
            {
              var result = files[i].id;

              selectedFilesId.push(files[i].id);

              $('#selected_attachment').append('<div class="btn-group" style="padding-top:4px;padding-bottom:4px; margin-left: 260px">'
                                                +'<a href="#" target="_blank" class="btn btn-default">'
                                                +actual_file
                                                +'</a>'
                                                +'<a href="#" target="_blank" class="btn btn-default">'
                                                +   '<i class="fa fa-download"></i>'
                                                +'</a>'
                                                +'<button type="button" class="btn btn-danger delete_attached_file"  id="'+result+'">X</button'
                                                +'</div> ');
            }
          }

          if(selectedFilesId.length > 0)
          {
            $('#attached_files').val(selectedFilesId.join(','));
          }
        }
      });
    });  
  });
  $(document).on('click', '.delete_attached_file', function(){  
    $.ajax({
      url: '<?php echo base_url();?>index.php/expense/remove_attachment_by_session/',
      type: 'GET',
      dataType: 'JSON',
      contentType: false,
      processData: false,
      success: function(response){
      }
    });
  });  
</script>

<script type="text/javascript">
  $(function() {
    
    $("#cgst, #sgst, #igst").on("keydown keyup", calculate_total_expense_amount);

    function calculate_total_expense_amount() 
    {
      var amount        = Number($("#amount").val());

      var cgst          = $("#cgst").val();
      var cgst_tax      = ($("#cgst").val()*amount)/100;

      var sgst          = $("#sgst").val();
      var sgst_tax      = ($("#sgst").val()*amount)/100;

      var igst          = $("#igst").val();
      var igst_tax      = ($("#igst").val()*amount)/100;
       
      var total_amount  = amount + cgst_tax + sgst_tax + igst_tax;

      $('#total_amount').val(total_amount);

      // total_amount += 
    }
  });
</script>

<script type="text/javascript">
  function fileValidation(){
    var fileInput = document.getElementById('document');
    var filePath = fileInput.value;
    var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.doc|\.pdf|\.docx|\.xls)$/i;
    if(!allowedExtensions.exec(filePath)){
        alert('Please upload file having extensions .jpeg/.jpg/.png/.doc/.pdf/.docx/.xls only.');
        fileInput.value = '';
        return false;
    }
  }
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('#expenseSubmit').click(function(e){
      // e.preventDefault();

      var isError = false;

      $('form#addExpenseForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addExpenseForm #err_"+id).text(field+ " field is required.");
            $('form#addExpenseForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addExpenseForm #err_"+id).text("");
            $('form#addExpenseForm #'+id).removeClass('is-invalid');
            $('form#addExpenseForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        return false;
      }  
      else 
      {
        return true;
      }    
    });

    $("form#addExpenseForm .field_validation").on("blur keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addExpenseForm #err_"+id).text(field+ " field is required.");
          $('form#addExpenseForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addExpenseForm #err_"+id).text("");
          $('form#addExpenseForm #'+id).removeClass('is-invalid');
          $('form#addExpenseForm #'+id).addClass('is-valid');
        }
    });

    $('#country_id').change(function(){

      var form = $(this).closest('form');

      var id = $(this).val();

      form.find('#state_id').html('<option value="">Select</option>');
      form.find('#city_id').html('<option value="">Select</option>');

      $.ajax({
        url: "<?php echo base_url('utility/get_states') ?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          for(i=0;i<data.length;i++)
          {
            form.find('#state_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
          }
        }
      });
    });

    $('#state_id').change(function(){
      
      var form = $(this).closest('form');
      
      var id = $(this).val();

      form.find('#city_id').html('<option value="">Select</option>');

      $.ajax({
        url: "<?php echo base_url('utility/get_cities') ?>/"+id,
        type: "GET",
        dataType: "JSON",
        success: function(data)
        {
          for(i=0;i<data.length;i++)
          {
            $('#city_id').append('<option value="' + data[i].id + '">' + data[i].name + '</option>');
          }
        }
      });
    });
  });
</script>