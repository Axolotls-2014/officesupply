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
                  <li class="breadcrumb-item active"><?=$this->lang->line('expense_edit')?></li>
                </ol>
              </div>
            </div>
        </section>

        <section class="content">
          <div class="row">
            <div class="col-md-12">
              <form class="form-horizontal" name="editExpenseForm" id="editExpenseForm" method="post" action="<?=base_url('expense/edit')?>" enctype="multipart/form-data">
                <div class="card card-primary card-outline">
                  <div class="card-header">
                    <h3 class="card-title"><?=$this->lang->line('expense_edit')?></h3>
                      <div class="card-tools">
                    
                        <ul class="nav nav-pills ml-auto">

                          <li class="nav-item ml-2">
                            <a class="nav-link active" href="<?=base_url('expense')?>" data-tt="tooltip" title="Click here to show expense list"><i class="fas fa-arrow-left mr-2"></i>Back</a>
                          </li>

                          <?php 
                            if($this->permission_model->has_permission('add_item'))
                            {
                          ?>

                                              
                          <li class="nav-item  ml-2">
                            <button type="button" class="btn btn-block btn-primary btn-sm add_item_modal" data-toggle="modal" data-target="#add_item_modal" data-tt="tooltip" title="Click here to Add Item">
                              Add Item
                            </button>
                          </li>

                          <?php
                            }
                          ?>
                        </ul>
                      </div>
                  </div>
                  <div class="card-body">
                    <div class="row">
                      <div class="col-md-6">
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('expense_date')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="date" class="form-control form-control-sm field_validation datepicker" id="date" placeholder="<?=$this->lang->line('expense_date')?>" value="<?=set_value('date',date('d-m-Y', strtotime($expense->date)))?>">
                            <span id="err_date" class="error invalid-feedback"><?=form_error('date');?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('expense_expense_category')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                            <div class="input-group">
                              <select class="form-control form-control-sm select2bs4 field_validation" name="expense_category_id" id="expense_category_id" placeholder="<?=$this->lang->line('expense_expense_category')?>" width="100%">
                                <option value=""><?=$this->lang->line('select')?></option>
                                <?php
                                  foreach ($expense_categories as $value) {
                                ?>
                                  <option value="<?=$value->id;?>"
                                    <?php 
                                      if(!isset($expense_category_id))
                                      {
                                        if($value->id == $expense->expense_category_id)
                                          echo ' selected';
                                      }
                                      else
                                      {
                                        if($value->id == $expense_category_id)
                                          echo ' selected';
                                      }
                                    ?>
                                  >
                                    <?= ucfirst($value->name);?>
                                  </option>
                                <?php 
                                  }
                                ?>
                              </select>
                              <span class="input-group-append">
                                <button type="button" class="btn btn-info btn-flat add_expense_category_modal" data-tt="tooltip" title="Add Expense Category (ALT + C)" data-keyboard="true" data-toggle="modal" data-target="#add_expense_category_modal" data-tt="tooltip" accesskey="c"><i class="fas fa-plus"></i>
                                </button>
                              </span>
                              <span id="err_expense_category_id" class="error invalid-feedback"><?=form_error('expense_category_id');?></span>
                            </div>
                            
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('expense_supplier')?>
                          </label>
                          <div class="col-sm-8">
                            <div class="input-group">
                              <select class="form-control form-control-sm select2bs4 field_validation" name="supplier_id" id="supplier_id" placeholder="<?=$this->lang->line('expense_supplier')?>" width="100%">
                                <option value="">Select Supplier</option>
                                <?php
                                  foreach ($suppliers as $value) {
                                ?>
                                  <option value="<?=$value->id;?>" 
                                    <?php 
                                      if(!isset($supplier_id))
                                      {
                                        if($value->id == $expense->supplier_id){
                                          echo ' selected';
                                        }
                                      }
                                      else
                                      {
                                        if($value->id == $supplier_id)
                                          echo ' selected';
                                      }
                                    ?>
                                  >
                                    <?= ucfirst($value->company_name);?>
                                  </option>
                                <?php 
                                  }
                                ?>
                              </select>
                              <span class="input-group-append">
                                <button type="button" class="btn btn-info btn-flat" data-tt="tooltip" title="Add Supplier (ALT + S)" data-toggle="modal" data-target="#add_supplier_modal" data-tt="tooltip" accesskey="s"><i class="fas fa-plus"></i>
                                </button>
                              </span>
                            </div>
                            <span id="info_supplier_id" class="text-info"></span>
                            <input type="hidden" name="supplier_state_id" id="supplier_state_id" value="<?=$supplier->state_id?>">
                            <span id="err_supplier_id" class="error invalid-feedback"><?=form_error('supplier_id');?></span>
                          </div>
                        </div>
                        <div class="form-group row d-none">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('expense_supplier_name')?>
                          </label>
                          <div class="col-sm-8">
                            <input type="text" class="form-control form-control-sm" name="supplier_name" id="supplier_name" placeholder="<?=$this->lang->line('expense_supplier_name')?>" value="<?=set_value('supplier_name',$expense->supplier_name)?>" width="100%">
                            <span id="err_supplier_name" class="text-danger"></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('expense_amount')?><span class="text-danger">*</span></label>
                          <div class="col-sm-8">
                            <input type="number" name="amount" value="<?=set_value('amount',$expense->amount)?>" class="form-control form-control-sm field_validation" id="amount" placeholder="<?=$this->lang->line('expense_amount')?>" readonly="readonly">
                            <span id="err_amount" class="error invalid-feedback"><?=form_error('amount');?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('expense_cgst')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                            <input type="number" name="cgst" value="<?=set_value('igst',$expense->cgst_tax)?>" class="form-control form-control-sm field_validation" id="cgst" placeholder="<?=$this->lang->line('expense_cgst')?>" step="0.01"  readonly="readonly">
                            <span id="err_cgst" class="error invalid-feedback"><?=form_error('cgst');?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('expense_sgst')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                            <input type="number" name="sgst" value="<?=set_value('sgst',$expense->sgst_tax)?>" class="form-control form-control-sm field_validation" id="sgst" placeholder="<?=$this->lang->line('expense_sgst')?>"  step="0.01"  readonly="readonly">
                            <span id="err_sgst" class="error invalid-feedback"><?=form_error('sgst');?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('expense_igst')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                            <input type="number" name="igst" value="<?=set_value('igst',$expense->igst_tax)?>" class="form-control form-control-sm field_validation" id="igst" placeholder="<?=$this->lang->line('expense_igst')?>" step="0.01"  readonly="readonly">
                            <span id="err_igst" class="error invalid-feedback"><?=form_error('igst');?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('expense_total_amount')?>
                            <span class="text-danger">*</span>
                          </label>
                          <div class="col-sm-8">
                            <input type="number" name="total_amount" value="<?=set_value('total_amount',$expense->total_amount) ?>" class="form-control form-control-sm field_validation" id="total_amount" placeholder="<?=$this->lang->line('expense_total_amount')?>"  step="0.01" readonly>
                            <span id="err_total_amount" class="error invalid-feedback"><?=form_error('total_amount');?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('expense_remarks')?>
                          </label>
                          <div class="col-sm-8">
                            <input type="text" name="remarks" value="<?=set_value('remarks',$expense->remarks) ?>" class="form-control form-control-sm" id="remarks" placeholder="<?=$this->lang->line('expense_remarks')?>">
                            <span id="err_remarks" class="error invalid-feedback"><?=form_error('remarks');?></span>
                          </div>
                        </div>

                        <div class="form-group row itc_row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('expense_itc')?></label>
                          <div class="col-sm-8">
                            <div class="custom-control custom-checkbox">
                              <input class="custom-control-input" name="itc" type="checkbox" id="itc" value="1" disabled="disabled" 
                                <?php 
                                  if($expense->itc != 0 && $expense->itc != null)
                                    echo ' checked';
                                ?>
                              >
                              <label for="itc" class="custom-control-label"></label>
                            </div>
                            <span id="err_itc" class="error invalid-feedback"><?=form_error('itc');?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('expense_expense_share')?>
                          </label>
                          <div class="col-sm-8">
                              <select class="form-control form-control-sm select2bs4 mb-2"  name="expense_share" id="expense_share" width="100%">
                                <option value="<?=EXPENSE_SHARE_ALL_INDIA?>"><?=clean_e_val(EXPENSE_SHARE_ALL_INDIA)?></option>
                                <option value="<?=EXPENSE_SHARE_NO_SHARING?>"><?=clean_e_val(EXPENSE_SHARE_NO_SHARING)?></option>
                                <option value="<?=EXPENSE_SHARE_STATE_WISE?>"><?=clean_e_val(EXPENSE_SHARE_STATE_WISE)?></option>
                              </select>
                            <span id="err_expense_share" class="error invalid-feedback"><?=form_error('expense_share');?></span>
                          </div>
                        </div>
                        <div class="form-group row d-none">
                          <label for="inputEmail3" class="col-sm-4 col-form-label">
                            <?=$this->lang->line('expense_expense_share_state')?>
                          </label>
                          <div class="col-sm-8">
                            <select class="form-control form-control-sm select2bs4"  name="expense_share_state_id[]" id="expense_share_state_id" width="100%" multiple="multiple">
                              <?php
                                if(isset($states))
                                {
                                  foreach ($states as $value) 
                                  {
                              ?>
                                    <option value="<?=$value->id;?>"
                                      <?php
                                        if(isset($expense_share_state_id))
                                        {
                                          if(in_array($value->id, $expense_share_state_id))
                                            echo ' selected';
                                        } 
                                      ?>
                                    >
                                      <?= $value->name;?>
                                    </option>
                              <?php 
                                  }
                                }
                              ?>
                            </select>
                            <span id="err_expense_share_state_id" class="error invalid-feedback"><?=form_error('expense_share_state_id');?></span>
                          </div>
                        </div>
                        <div class="form-group row">
                          <label for="inputEmail3" class="col-sm-4 col-form-label"><?=$this->lang->line('expense_document')?></label>
                          <div class="col-sm-8">
                            <div class="input-group">
                              <div class="custom-file">
                                <button type="button" class="btn btn-primary" data-tt="tooltip" title="Click here to upload the files" data-toggle="modal" data-target="#attachment_modal"><?=$this->lang->line('upload_file')?></button>&nbsp;&nbsp;
                                <button type="button" class="btn btn-success" data-tt="tooltip" title="Click here to select the files" data-toggle="modal" data-target="#upload-files"><?=$this->lang->line('attach_file')?></button>
                                
                              </div>
                            </div>
                            <span id="err_document" class="error invalid-feedback"><?=form_error('document');?></span>
                          </div>
                        </div>
                        
                        <div class="form-group row">
                          <div class="col-sm-4">
                            
                          </div>
                          <div class="col-sm-10">
                            <div id="selected_attachment" class="selected_attachment"> 
                            </div>
                          </div>
                        </div>
                        <br/>
                      </div>
                      <div class="col-md-6">
                        <div class="row">
                          <div class="col-md-12">
                            <table class="table table-bordered item_table" width="100%">
                              <thead>
                                <tr>
                                  <th width="25%">Item</th>
                                  <th>Taxable Amount</th>
                                  <th>IGST</th>
                                  <th>CGST</th>
                                  <th>SGST</th>
                                  <th>#</th>
                                </tr>
                                <tr>
                                  <td>
                                    <select class="form-control select2bs4 is_required" name="expense_item_id" id="expense_item_id">
                                      <option value="">Select Item</option>
                                      <?php 
                                        foreach($items as $item)
                                        {
                                      ?>
                                          <option value="<?=$item->id?>"
                                            data-igst="<?=$item->igst?>"
                                            data-cgst="<?=$item->cgst?>"
                                            data-sgst="<?=$item->sgst?>"
                                          >
                                            <?=$item->item_name.'-'.$item->igst?>
                                          </option>
                                      <?php
                                        }
                                      ?>
                                    </select>
                                  </td>
                                  <td>
                                    <input type="number" class="form-control" name="item_taxable_amount" value="0" min="0" step="0.01">
                                  </td>
                                  <td>
                                    <input type="number" class="form-control" name="item_igst_amount" value="0" min="0" step="0.01" readonly="readonly">
                                  </td>
                                  <td>
                                    <input type="number" class="form-control" name="item_cgst_amount" value="0" min="0" step="0.01" readonly="readonly">
                                  </td>
                                  <td>
                                    <input type="number" class="form-control" name="item_sgst_amount" value="0" min="0" step="0.01" readonly="readonly">
                                  </td>
                                  <td>
                                    <button type="button" class="btn btn-sm btn-success" id="addrow"><i class="fas fa-plus"></i></button>
                                  </td>
                                </tr>
                              </thead>
                              <tbody id="item_table_body">
                                <?php foreach($expense_items as $ei){?>
                                <tr>  
                                  <td>  
                                    <?=$ei->item_name?>
                                    <input type="hidden" name="item_id" value="<?php echo $ei->item_id?>">  
                                  </td> 
                                  <td>  
                                    <span name="taxable_amount"><?php echo $ei->taxable_amount;?></span>  
                                  </td> 
                                  <td>  
                                    <span name="igst_amount">   <?php echo $ei->igst_tax;?></span>  
                                    <input type="hidden" name="igst_rate" value="<?=$ei->igst?>">    
                                  </td> 
                                  <td>  
                                    <span name="cgst_amount">  <?php echo $ei->cgst_tax;?></span>  
                                    <input type="hidden" name="cgst_rate" value="<?=$ei->cgst?>">   
                                  </td> 
                                  <td>  
                                    <span name="sgst_amount">  <?php echo $ei->sgst_tax;?> </span>  
                                    <input type="hidden" name="sgst_rate" value="<?=$ei->sgst?>">   
                                  </td> 
                                  <td> 
                                   <i class="fas fa-trash delete_item" style="cursor:pointer"></i> 
                                  </td>  
                               </tr>
                             <?php }?>
                              </tbody>
                            </table>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="card-footer">
                    <input type="hidden" name="id" id="id" value="<?=$expense->id?>">
                    <input type="hidden" name="expense_items" id="expense_items" value="">
                    <input type="hidden" name="attached_files" value="<?=$expense->document?>" id="attached_files">
                    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                    <button type="submit" name="submit" id="expenseSubmit" data-tt="tooltip" title="Click here to Save Expense" class="btn btn-info">
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
                      <h5 class="modal-title" id="exampleModalLabel"><?=$this->lang->line('expense_attach_modal_label')?><</h5>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                    </div>
                    <div class="modal-body">
                      <form action="<?php echo base_url('attachment')?>/file_upload" enctype="multipart/form-data" class="dropzone" id="image-upload"  method="POST" style="border-style: dotted;border-color: #bababa; min-height: 275px !important">
                        <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                      </form>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-secondary" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>   
        </section>
      </div>
      <aside class="control-sidebar control-sidebar-dark"></aside>
      <!-- <div style="position: absolute;top: 15%; right: 20px; width: 300px;" >
        <div class="row">
          <div class="col-md-12">
            <div class="card card-outline card-primary">
              <div class="card-header">
                <h3 class="card-title">Shortcuts for adding Records</h3>
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
              </div>
              <div class="card-body" style="font-size: 14px;">
                <div class="row">
                  <div class="col-md-9">
                    <?=$this->lang->line('expense_category_add')?>
                  </div>
                  <div class="col-md-3">
                    ALT + C
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-9">
                    <?=$this->lang->line('merchant_add')?>
                  </div>
                  <div class="col-md-3">
                    ALT + M
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div> -->
    </div>
    <div class="upload-files">
      <div class="modal fade" id="upload-files" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel"><?=$this->lang->line('expense_upload_file')?></h5>
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body" style="max-height: 400px;overflow-y: auto">

              <style type="text/css">
                .cursor-pointer {
                  cursor: pointer;
                }

                .list-group-item{
                  margin-bottom: -2px !important;
                }
              </style>

              <div class="row">
                <div class="col-md-12">
                  <ul class="list-group list_attachment">
                  </ul>
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger attach_files"><?=$this->lang->line('expense_attach_file')?></button>
              <button type="button" class="btn btn-default refresh_attachments"><i class="fa fa-refresh"></i><?=$this->lang->line('refresh')?></button>
              <button type="button" class="btn btn-default" data-dismiss="modal"><?=$this->lang->line('close')?></button>
            </div>
          </div>
        </div>
      </div>
    </div>

    
<?php $this->load->view('layout/footer');?>
<?php $this->load->view('supplier/add_supplier_modal');?>

<div class="example-modal">
  <div class="modal fade" id="add_item_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>


<div class="example-modal">
  <div class="modal fade" id="add_expense_category_modal" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
      <div class="modal-content">
      </div>
      <!-- /.modal-content -->
    </div>
      <!-- /.modal-dialog -->
  </div>
</div>


<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/4.2.0/min/dropzone.min.js"></script>

<script type="text/javascript">
  Dropzone.options.imageUpload =  {
                  maxFilesize:100,
                  parallelUploads:2,
                  acceptedFiles: ".jpeg,.jpg,.png,.gif,.doc,.docx,.ppt,.pptx,.xls,.xlsx,.txt,.html,.zip,.rar,.csv,.pdf"
                };
</script>

<script type="text/javascript">
  $(document).ready(function(e){
    $('.dz-message span').html("Click or Drag & Drop files here to upload");
    $('.dz-message').css('padding-top',"7%");
    $('.dz-message').css('font-size',"20px");
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    const expense_categoryToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });
    const itemToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    // Item add start //

    $(document).on('click', ".add_item_modal" ,function(){
   
      $.ajax({
      url: "<?php echo base_url('item/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_item_modal').find('.modal-content').html(data.add_item_modal_body);
          $('#add_item_modal').modal('show');
          $('.select2bs4').select2({
            theme: 'bootstrap4'
          });
        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','#addItemForm',function(e){
      
      e.preventDefault();

      $('#addItemSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addItemForm').serialize();

      var isError = false;

      $('form#addItemForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addItemForm  #err_"+id).text(field+ " field is required.");
            $('form#addItemForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addItemForm #err_"+id).text("");
            $('form#addItemForm #'+id).removeClass('is-invalid');
            $('form#addItemForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addItemSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
          url: "<?php echo base_url('item/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
              $('#add_item_modal').modal('hide');
              $('form#addItemForm #addItemSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');

              if($('form#editExpenseForm #expense_item_id').length)
              {
                $('form#editExpenseForm #expense_item_id').html('');
                $('form#editExpenseForm #expense_item_id').append('<option value="">Select</option>');
                
                for(i=0;i<response['items'].length;i++)
                { 
                  $('form#editExpenseForm #expense_item_id').append('<option value="' + response['items'][i].id + '" data-igst="'+response['items'][i].igst+'" data-cgst="'+response['items'][i].cgst+'" data-sgst="'+response['items'][i].sgst+'">' + response['items'][i].item_name +'</option>');
                }

                $('form#editExpenseForm #expense_item_id').val(response['id']).attr("selected","selected");

                itemToast.fire({
                  type: 'success',
                  title: response.message
                });
              }
              else
              {
                // show_message('success-header',response.message);
                itemToast.fire({
                  type: 'success',
                  title: response.message
                });  

                /*location.reload(true);*/
              }
            }
            else
            {
              itemToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addItemSubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
    });

    $(document).on("blur change keyup", "form#addItemForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addItemForm #err_"+id).text(field+ " field is required.");
          $('form#addItemForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addItemForm #err_"+id).text("");
          $('form#addItemForm #'+id).removeClass('is-invalid');
          $('form#addItemForm #'+id).addClass('is-valid');
        }
    });

    // Item add end //

    $(document).on('click', ".add_expense_category_modal" ,function(){

     $.ajax({
        url: "<?php echo base_url('expense_category/add')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){
          $('#add_expense_category_modal').find('.modal-content').html(data.add_expense_category_modal_body);
          $('#add_expense_category_modal').modal('show');

          $('.select2bs4').select2({theme: 'bootstrap4'});

        },
        error: function (xhr, ajaxOptions, thrownError) {
          alert(xhr.status);
          alert(thrownError);
          alert(ajaxOptions);
        }
      });
    });

    $(document).on('submit','#addExpense_categoryForm',function(e){
      
      e.preventDefault();

      $('#addExpense_categorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');
      var formData = $('#addExpense_categoryForm').serialize();

      var isError = false;

      $('form#addExpense_categoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addExpense_categoryForm  #err_"+id).text(field+ " field is required.");
            $('form#addExpense_categoryForm  #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addExpense_categoryForm #err_"+id).text("");
            $('form#addExpense_categoryForm #'+id).removeClass('is-invalid');
            $('form#addExpense_categoryForm #'+id).addClass('is-valid');
          }

      });

      if(isError == true)
      {
        $('#addExpense_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
        return false;
      }
      else
      {
        $.ajax({
        url: "<?php echo base_url('expense_category/add')?>",
          type: "POST",
          data: formData,
          dataType: "JSON",
          success: function(response){
            if(response.code==1)
            { 
            $('#add_expense_category_modal').modal('hide');
              $('form#addExpense_categoryForm #addExpense_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');

              if($('form#editExpenseForm #expense_category_id').length)
              {
                $('form#editExpenseForm #expense_category_id').html('');
                $('form#editExpenseForm #expense_category_id').append('<option value="">Select</option>');
                
                for(i=0;i<response['expense_categories'].length;i++)
                { 
                  $('form#editExpenseForm #expense_category_id').append('<option value="' + response['expense_categories'][i].id + '">' + response['expense_categories'][i].name +'</option>');
                }

                $('form#editExpenseForm #expense_category_id').val(response['id']).attr("selected","selected");


                expense_categoryToast.fire({
                  type: 'success',
                  title: response.message
                });
              }
              else
              {
                // show_message('success-header',response.message);
                expense_categoryToast.fire({
                  type: 'success',
                  title: response.message
                });  

                location.reload(true);
              }
            }
            else
            {
              expense_categoryToast.fire({
                type: 'error',
                title: response.message
              });
              $('#addExpense_categorySubmit').text('<?=$this->lang->line("submit")?>').removeAttr('disabled');
            }
          }
        });
      }
      
    });

    $(document).on("blur change keyup", "form#addExpense_categoryForm  .field_validation", function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addExpense_categoryForm #err_"+id).text(field+ " field is required.");
          $('form#addExpense_categoryForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addExpense_categoryForm #err_"+id).text("");
          $('form#addExpense_categoryForm #'+id).removeClass('is-invalid');
          $('form#addExpense_categoryForm #'+id).addClass('is-valid');
        }
    });

    // $('.tax_row').css('display','none');

    // <?php 
    //   if($expense->gst_registration_type == 1 && $company_setting->state_id == $expense->supplier_state_id)
    //   {
    // ?>
    //     $('.cgst_row').fadeIn(10);
    //     $('.sgst_row').fadeIn(10);
    // <?php     
    //   }
    //   else if($expense->gst_registration_type == 1 && $company_setting->state_id != $expense->supplier_state_id)
    //   {
    // ?>
    //     $('.igst_row').fadeIn(10);
    // <?php
    //   }
    // ?>

    $('#upload-files').on('shown.bs.modal',function(e){
      $.ajax({
        url: '<?php echo base_url('attachment');?>/get_attachment_from_session',
        type: 'GET',
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function(response){

          var files               = response.data.split(',');
          
          var selectedFilesId     = new Array();
          var attached_files_id   = $('#attached_files').val();

          if(attached_files_id != '')
          {
            selectedFilesId = attached_files_id.split(",");
          }

          for(var i = 0 ; i < files.length ; i++)
          {
            var attachment_name_array  = files[i].split(".");
            var attachment_name        = attachment_name_array[0];
            var ext                    = attachment_name_array[1];
            var actual_attachment_name = attachment_name;
            var actual_file            = actual_attachment_name+"."+ext;

            if(selectedFilesId.indexOf(files[i]) == -1 && files[i] != '')
            {
              $('.list_attachment').append('<li class="list-group-item">'
                                            +'<div class="custom-control custom-checkbox">'
                                              +'<input class="custom-control-input" id="'+files[i]+'" type="checkbox" value="'+files[i]+'">'
                                              +'<label class="cursor-pointer d-block custom-control-label" for="'+files[i]+'">'
                                                +actual_file
                                              +'</label>'
                                            +'</div>'
                                          +'</li>');
            }
          }
        }
      });
    });

    $('#upload-files').on('hidden.bs.modal',function(e){
      $('.list_attachment').html('');
    });

    $('#refresh_attachments').click(function(e){
      $.ajax({
        url: '<?php echo base_url('attachment');?>/get_attachment_from_session',
        type: 'GET',
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function(response){

          var files               = response.data.split(',');
          
          var selectedFilesId     = new Array();
          var attached_files_id   = $('#attached_files').val();

          if(attached_files_id != '')
          {
            selectedFilesId = attached_files_id.split(",");
          }

          for(var i = 0 ; i < files.length ; i++)
          {
            var attachment_name_array  = files[i].split(".");
            var attachment_name        = attachment_name_array[0];
            var ext                    = attachment_name_array[1];
            var actual_attachment_name = attachment_name;
            var actual_file            = actual_attachment_name+"."+ext;

            if(selectedFilesId.indexOf(files[i]) == -1 && files[i] != '')
            {
              selectedFilesId.push(files[i]);

              $('.list_attachment').append('<li class="list-group-item">'
                                            +'<div class="custom-control custom-checkbox">'
                                              +'<input class="custom-control-input" id="'+files[i]+'" type="checkbox" value="'+files[i]+'">'
                                              +'<label class="cursor-pointer d-block custom-control-label" for="'+files[i]+'">'
                                                +actual_file
                                              +'</label>'
                                            +'</div>'
                                          +'</li>');
            }
          }

          if(selectedFilesId.length > 0)
          {
            $('#attached_files').val(selectedFilesId.join(','));
          }
        }
      });
    });

    $('.attach_files').click(function(e){

      var attached_files        = $('#attached_files').val();
      var attached_files_array  = new Array();

      if(attached_files != '')
      {
        attached_files_array = attached_files.split(',');
      }

      var selected        = new Array();

      $('.list_attachment input[type=checkbox]').each(function() {
         if ($(this).is(":checked")) {
             selected.push($(this).attr('id'));
         }
      });

      for (var i = selected.length - 1; i >= 0; i--) {

        attached_files_array.push(selected[i]);
 
        var attachment_name_array   = selected[i].split(".");
        var attachment_name         = attachment_name_array[0];
        var ext                     = attachment_name_array[1];
        var actual_attachment_name  = attachment_name;
        var actual_file             = actual_attachment_name+"."+ext;

        $('#selected_attachment').append('<div class="btn-group" style="margin-top:5px; margin-left:5px;margin-right:5px;">'
                                                +'<a href="#" class="btn btn-default">'
                                                +'<i class="fas fa-paperclip"></i> ' 
                                                +actual_file
                                                +'</a>'
                                                // +'<a href="#" target="_blank" class="btn btn-default">'
                                                // +   '<i class="fa fa-download"></i>'
                                                // +'</a>'
                                                +'<button type="button" class="btn btn-danger delete_attached_file"  id="'+selected[i]+'">X</button'
                                                +'</div> ');
      }

      $('#attached_files').val(attached_files_array.join(','));

      $('#upload-files').modal('hide');
    });

    // list out all the select
    $('#attachment_modal').on('hide.bs.modal', function () {
      $.ajax({
        url: '<?php echo base_url('attachment');?>/get_attachment_from_session',
        type: 'GET',
        dataType: 'JSON',
        contentType: false,
        processData: false,
        success: function(response){

          var files               = response.data.split(',');
          
          var selectedFilesId     = new Array();
          var attached_files_id   = $('#attached_files').val();

          if(attached_files_id != '')
          {
            selectedFilesId = attached_files_id.split(",");
          }

          for(var i = 0 ; i < files.length ; i++)
          {
            var attachment_name_array  = files[i].split(".");
            var attachment_name        = attachment_name_array[0];
            var ext                    = attachment_name_array[1];
            var actual_attachment_name = attachment_name;
            var actual_file            = actual_attachment_name+"."+ext;

            if(selectedFilesId.indexOf(files[i]) == -1 && files[i] != '')
            {
              selectedFilesId.push(files[i]);

              $('#selected_attachment').append('<div class="btn-group" style="margin-top:5px; margin-left:5px;margin-right:5px;">'
                                                +'<a href="#" class="btn btn-default">'
                                                +'<i class="fas fa-paperclip"></i> ' 
                                                +actual_file
                                                +'</a>'
                                                // +'<a href="#" target="_blank" class="btn btn-default">'
                                                // +   '<i class="fa fa-download"></i>'
                                                // +'</a>'
                                                +'<button type="button" class="btn btn-danger delete_attached_file"  id="'+files[i]+'">X</button'
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

    // remove image button pill from selected attachment
    $("#selected_attachment").delegate("button", "click", function(e){
      var attached_files_array = $('#attached_files').val().split(",");
      attached_files_array.splice($.inArray($(this).attr('id'), attached_files_array),1);
      $('#attached_files').val(attached_files_array.join(','));
      // remove file pill
      $(this).parent().remove();
    });  

    $("#supplier_id").change(function(e){

      var company_state_id = <?=$this->company_settings_model->get_company_records()->state_id?>;
      var supplier_id      = $(this).val();
      var supplier_name      = $.trim($(this).find('option:selected').text());

      if(supplier_id != '')
      {
        $('#supplier_name').val(supplier_name);

        if($('.item_table').hasClass('d-none'))
          $('.item_table').removeClass('d-none');

        $.ajax({
          url: '<?php echo base_url('supplier/get_record_detail');?>',
          type: 'POST',
          data:{
              'supplier_id' : supplier_id,
              '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: 'JSON',
          success: function(response){

            var supplier_type = '';

            if(response.gst_registration_type == 1)
              supplier_type = 'Registered';
            else if(response.gst_registration_type == 0)
              supplier_type = 'Not Registered';
            else if(response.gst_registration_type == 2)
              supplier_type = 'Composite';

            $('form#addExpenseForm #info_supplier_id').text(supplier_type);
            $('#supplier_state_id').val(response.state_id);
            $('#item_table_body').empty();

            $('#cgst').val(0.0);
            $('#sgst').val(0.0);              
            $('#igst').val(0.0);              

            $('input[name="item_igst_amount"]').val(0);
            $('input[name="item_cgst_amount"]').val(0);
            $('input[name="item_sgst_amount"]').val(0);

            $('input[name="total_amount"]').val(0);
            $('input[name="amount"]').val(0);

            if(response.gst_registration_type == 1)
            {
              $('#itc').removeAttr('disabled','disabled');

              // if(response.state_id == company_state_id)
              // {
              //   if(!$(".cgst_row").is(":visible"))
              //   {
              //     $('input[name="item_igst_amount"]').val(0);
              //     $('input[name="item_cgst_amount"]').val(0);
              //     $('input[name="item_sgst_amount"]').val(0);
              //   }
              // }
              // else
              // {
              //   if(!$(".igst_row").is(":visible"))
              //   { 

              //   }
              // }
            }
            else
            {
              if($('#itc').is(':checked'))
              {
                $('#itc').prop('checked',false);
              }

              $('#itc').attr('disabled','disabled'); 
            }

            $('select[name="expense_item_id"]').val('');
            $('.select2bs4').select2({
              theme: 'bootstrap4'
            });
          }
        });        
      }
      else
      {
        if(!$('.item_table').hasClass('d-none'))
          $('.item_table').addClass('d-none');

        $('#item_table_body').empty();

        $('form#addExpenseForm #info_supplier_id').text('');
        $('#supplier_name').val('');
        $('#supplier_state_id').val('');
        $('#cgst').val(0.0);
        $('#sgst').val(0.0);              
        $('#igst').val(0.0);

        $('select[name="expense_item_id"]').val('');
        $('.select2bs4').select2({
          theme: 'bootstrap4'
        });              
      }
    }); 

    $("#cgst, #sgst, #igst, #amount").on("keydown keyup change", function(e){

      var amount        = Number($("#amount").val());

      var cgst          = $("#cgst").val();
      var cgst_tax      = ($("#cgst").val()*amount)/100;

      var sgst          = $("#sgst").val();
      var sgst_tax      = ($("#sgst").val()*amount)/100;

      var igst          = $("#igst").val();
      var igst_tax      = ($("#igst").val()*amount)/100;
       
      var total_amount  = amount + cgst_tax + sgst_tax + igst_tax;

      $('#total_amount').val(total_amount.toFixed(2));
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){

    $('form#editExpenseForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#expenseSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#editExpenseForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editExpenseForm #err_"+id).text(field+ " field is required.");
            $('form#editExpenseForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editExpenseForm #err_"+id).text("");
            $('form#editExpenseForm #'+id).removeClass('is-invalid');
            $('form#editExpenseForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#expenseSubmit').text('<?=$this->lang->line("expense_save")?>').removeAttr('disabled');

        return false;
      }  
      else 
      {
        var itemDataArray = [];

        $("#item_table_body").find('tr').each(function () {

            var tr              = $(this).closest("tr");
            var itemData        = {};

            itemData['item_id']                = tr.find('input[name="item_id"]').val();
            itemData['taxable_amount']         = tr.find('span[name="taxable_amount"]').text();
            itemData['igst']                   = tr.find('input[name="igst"]').val();
            itemData['igst_tax']               = tr.find('span[name="igst_amount"]').text();
            itemData['sgst']                   = tr.find('input[name="sgst"]').val();
            itemData['sgst_tax']               = tr.find('span[name="sgst_amount"]').text();
            itemData['cgst']                   = tr.find('input[name="cgst"]').val();
            itemData['cgst_tax']               = tr.find('span[name="cgst_amount"]').text();

            //alert(itemData['taxable_amount']);
            //alert(itemData['igst_tax']);
            //alert(itemData['cgst_tax']);
            //alert(itemData['sgst_tax']);
            
            itemDataArray.push(JSON.stringify(itemData));
        });

        if(itemDataArray.length > 0)
        {
          $('#expense_items').val(itemDataArray.join('|'));
          //alert($('#expense_items').val());
        }
        else
        {
          isError = true;
          Swal.fire({
            text: 'Please add 1 atleast one expense item.',
            icon: "warning",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            timer: 2000,
            customClass: {
                confirmButton: "btn btn-primary"
            }
          });

          $('#expenseSubmit').text('Save Expense').removeAttr('disabled');
          return false;
        }
      }    
    });

    $("form#editExpenseForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editExpenseForm #err_"+id).text(field+ " field is required.");
          $('form#editExpenseForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editExpenseForm #err_"+id).text("");
          $('form#editExpenseForm #'+id).removeClass('is-invalid');
          $('form#editExpenseForm #'+id).addClass('is-valid');
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

    $('#expense_share').change(function(){

      var expense_share = $(this).val();

      if(expense_share != '<?=EXPENSE_SHARE_STATE_WISE?>')
      {
        $('#expense_share_state_id').val('');
        $('#expense_share_state_id').trigger('change');
        $('#expense_share_state_id').closest('.row').addClass('d-none');
      }
      else
      {
        $('#expense_share_state_id').val('');
        $('#expense_share_state_id').trigger('change');
        $('#expense_share_state_id').closest('.row').removeClass('d-none');
      }
      
    });


    $(document).on('click',"#addrow",function(ev){
      // ev.preventDefault();
      var isError = false;

      $('.is_required').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("#err_"+id).text(field+ " field is required.");
            isError = true;
          }
          else{
            $("#err_"+id).text("");
          }

      });

      if(isError == false)
      {
        var expense_item_id           = $('select[name="expense_item_id"] option:selected').val();
        var expense_item_id_text      = $.trim($('select[name="expense_item_id"] option:selected').text());

        var igst           = ($('input[name="item_igst_amount"]').prop('readonly')) ? 0 : parseFloat($('select[name="expense_item_id"] option:selected').data('igst'));
        var cgst           = ($('input[name="item_cgst_amount"]').prop('readonly')) ? 0 : parseFloat($('select[name="expense_item_id"] option:selected').data('cgst'));
        var sgst           = ($('input[name="item_sgst_amount"]').prop('readonly')) ? 0 : parseFloat($('select[name="expense_item_id"] option:selected').data('sgst'));

        var taxable_amount = parseFloat($('input[name="item_taxable_amount"]').val());
        
        var igst_amount    = parseFloat((igst*taxable_amount)/100);
        var cgst_amount    = parseFloat((cgst*taxable_amount)/100);
        var sgst_amount    = parseFloat((sgst*taxable_amount)/100);


        
        var delete_row  =  '<i class="fas fa-trash delete_item" style="cursor:pointer"></i>';

        var rows        = '<tr>'
                        +  '<td>'
                        +     expense_item_id_text
                        +     '<input type="hidden" name="item_id" value="'+expense_item_id+'">' 
                        +  '</td>'
                        +  '<td>'
                        +     '<span name="taxable_amount">'+taxable_amount+'</span>'
                        +  '</td>'
                        +  '<td>'
                        +     '<span name="igst_amount">'+igst_amount+'</span>'
                        +     '<input type="hidden" name="igst_rate" value="'+igst+'">'   
                        +  '</td>'
                        +  '<td>'
                        +     '<span name="cgst_amount">'+cgst_amount+'</span>'
                        +     '<input type="hidden" name="cgst_rate" value="'+cgst+'">'  
                        +  '</td>'
                        +  '<td>'
                        +     '<span name="sgst_amount">'+sgst_amount+'</span>'
                        +     '<input type="hidden" name="sgst_rate" value="'+sgst+'">'  
                        +  '</td>'
                        +   '<td>'
                        +     delete_row 
                        +   '</td>'
                        + '</tr>';

        $(rows).prependTo("#item_table_body");
        $('select[name="expense_item_id"]').focus();

        calculateTotal();        
        calculateTotalAndTax();     

        $('input[name="item_taxable_amount"]').val(0);   
        $('input[name="item_igst_amount"]').val(0);   
        $('input[name="item_cgst_amount"]').val(0);   
        $('input[name="item_sgst_amount"]').val(0);   
        $('select[name="expense_item_id"]').val('');
        $('.select2bs4').select2({
          theme: 'bootstrap4'
        });

        return false;
      }
      else
      {
        return false;
      }
    });

    $(document).on('change keyup','select[name="expense_item_id"],input[name="item_taxable_amount"],input[name="item_igst_amount"],input[name="item_cgst_amount"],input[name="item_sgst_amount"]', function(e){

      var company_state_id = <?=$this->company_settings_model->get_company_records()->state_id?>;
      var supplier_state_id = $('#supplier_state_id').val();

      var tr = $(this).closest('tr');
      
      var taxable_amount = parseFloat(tr.find('input[name="item_taxable_amount"]').val());

      var igst           = parseFloat($('select[name="expense_item_id"] option:selected').data('igst'));
      var cgst           = parseFloat($('select[name="expense_item_id"] option:selected').data('cgst'));
      var sgst           = parseFloat($('select[name="expense_item_id"] option:selected').data('sgst'));

      var igst_amount       = (igst*taxable_amount)/100;
      var cgst_amount       = (cgst*taxable_amount)/100;
      var sgst_amount       = (sgst*taxable_amount)/100;

      if(company_state_id == supplier_state_id)
      {
        tr.find('input[name="item_igst_amount"]').val(0);  
        tr.find('input[name="item_igst_amount"]').attr('readonly','readonly');
        tr.find('input[name="item_sgst_amount"]').val(sgst_amount.toFixed(2));
        tr.find('input[name="item_sgst_amount"]').removeAttr('readonly');
        tr.find('input[name="item_cgst_amount"]').val(cgst_amount.toFixed(2));
        tr.find('input[name="item_cgst_amount"]').removeAttr('readonly');
        
      }
      else
      {
        tr.find('input[name="item_igst_amount"]').val(igst_amount.toFixed(2));  
        tr.find('input[name="item_igst_amount"]').removeAttr('readonly');
        tr.find('input[name="item_sgst_amount"]').val(0);
        tr.find('input[name="item_sgst_amount"]').attr('readonly','readonly');
        tr.find('input[name="item_cgst_amount"]').val(0);
        tr.find('input[name="item_cgst_amount"]').attr('readonly','readonly');
      }
    });

    $(document).on('click',".delete_item", function(e){
      var tr = $(this).closest('tr');
      tr.remove();
      calculateTotal();
      calculateTotalAndTax();
      return false;
    });

    function calculateTotal()
    {
      var taxable_amount = 0;
      var igst           = 0;
      var cgst           = 0;
      var sgst           = 0;


      $("#item_table_body").find('tr').each(function () {
        var tr  = $(this).closest("tr");

        taxable_amount += parseFloat($(this).find('span[name="taxable_amount"]').text());
        igst           += parseFloat($(this).find('span[name="igst_amount"]').text());
        cgst           += parseFloat($(this).find('span[name="cgst_amount"]').text());
        sgst           += parseFloat($(this).find('span[name="sgst_amount"]').text());

      });        

      $('#igst').val(igst);
      $('#cgst').val(cgst);
      $('#sgst').val(sgst);
      $('#total_amount').val(taxable_amount + igst + cgst + sgst);
    }

    function calculateTotalAndTax()
    {
      var net_total = 0;
      var igst = 0;
      var cgst = 0;
      var sgst = 0;
      $("#item_table_body tr").each(function(){
          var total_value       = parseFloat($(this).find('span[name="taxable_amount"]').text());
          var total_igst_value  = parseFloat($(this).find('span[name="igst_amount"]').text());
          var total_cgst_value  = parseFloat($(this).find('span[name="cgst_amount"]').text());
          var total_sgst_value  = parseFloat($(this).find('span[name="sgst_amount"]').text());


          net_total+=total_value;
          igst+=total_igst_value;
          cgst+=total_cgst_value;
          sgst+=total_sgst_value;
      });

      $("#amount").val(net_total.toFixed(2));
      $("#igst").val(igst.toFixed(2));
      $("#cgst").val(cgst.toFixed(2));
      $("#sgst").val(sgst.toFixed(2));


      var total_amount = net_total + igst + cgst + sgst;
      $("#total_amount").val(total_amount.toFixed(2));
    }
  });
</script>