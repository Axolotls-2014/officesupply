<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_setting')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('tax')?>"><?=$this->lang->line('tax_header')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('tax_add')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="addTaxForm" id="addTaxForm" method="post" action="">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('tax_add')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('tax_name')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="tax_name" value="<?=set_value('tax_name') ?>" class="form-control form-control-sm field_validation" id="tax_name" placeholder="<?=$this->lang->line('tax_name')?>">
                      <span id="err_tax_name" class="error invalid-feedback"><?=form_error('tax_name');?></span>
                    </div>
                  </div>
                  
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('tax_sgst')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="sgst" value="<?=set_value('sgst') ?>" class="form-control form-control-sm field_validation" id="sgst" placeholder="<?=$this->lang->line('tax_sgst')?>">
                      <span id="err_sgst" class="error invalid-feedback"><?=form_error('sgst');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('tax_cgst')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="cgst" value="<?=set_value('cgst') ?>" class="form-control form-control-sm field_validation" id="cgst" placeholder="<?=$this->lang->line('tax_cgst')?>">
                      <span id="err_cgst" class="error invalid-feedback"><?=form_error('cgst');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('tax_igst')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="igst" value="<?=set_value('igst') ?>" class="form-control form-control-sm field_validation" id="igst" placeholder="<?=$this->lang->line('tax_igst')?>">
                      <span id="err_igst" class="error invalid-feedback"><?=form_error('igst');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('tax_status')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4 field_validation" name="status" id="status" width="100%">
                        <option value="1"><?=$this->lang->line('tax_status_active')?></option>
                        <option value="0"><?=$this->lang->line('tax_status_inactive')?></option>
                      
                      </select>
                      <span id="err_status" class="error invalid-feedback"><?=form_error('status');?></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <button type="submit" name="submit" id="taxSubmit" data-tt="tooltip" title="Click here to Save Tax" class="btn btn-info"><?=$this->lang->line('tax_save')?></button>
                  <a href="<?=base_url('tax')?>" class="btn btn-default float-right"><?=$this->lang->line('tax_cancel')?></a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php $this->load->view('layout/footer');?>


<script type="text/javascript">

  $(document).ready(function(e){

    

    $('form#addTaxForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#taxSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#addTaxForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addTaxForm #err_"+id).text(field+ " field is required.");
            $('form#addTaxForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addTaxForm #err_"+id).text("");
            $('form#addTaxForm #'+id).removeClass('is-invalid');
            $('form#addTaxForm #'+id).addClass('is-valid');
          }
      });

      if(isError == true)
      {
        $('#taxSubmit').text('<?=$this->lang->line("tax_save")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }   
    });

    $("form#addTaxForm .field_validation").on("blur keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addTaxForm #err_"+id).text(field+ " field is required.");
          $('form#addTaxForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addTaxForm #err_"+id).text("");
          $('form#addTaxForm #'+id).removeClass('is-invalid');
          $('form#addTaxForm #'+id).addClass('is-valid');
        }
    });

  });
</script>
