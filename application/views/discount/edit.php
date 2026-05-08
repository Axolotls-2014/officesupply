<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_setting')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('discount')?>"><?=$this->lang->line('header_discount')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('discount_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editDiscountForm" id="editDiscountForm" method="post" action="">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('discount_edit')?></h3>
                </div>
                 <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('discount_name')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="name" value="<?=set_value('name',$discount->name) ?>" class="form-control form-control-sm field_validation" id="discount_name" placeholder="<?=$this->lang->line('discount_name')?>">
                      <span id="err_currency_name" class="error invalid-feedback"><?=form_error('name');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('discount_type')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <select class="form-control field_validation form-control-sm select2bs4" style="width: 100%;" placeholder="<?=$this->lang->line('discount_type')?>" id="type" name="type">
                        <option value="">Select</option>
                        <option value="0" <?php echo ($discount->type == 0) ? ' selected' : ''; ?>><?=$this->lang->line('discount_type_fixed')?></option>
                        <option value="1" <?php echo ($discount->type == 1) ? ' selected' : ''; ?>><?=$this->lang->line('discount_type_percentage')?></option>
                      </select>
                      <span id="err_type" class="error invalid-feedback"><?=form_error('type');?></span>
                    </div>
                  </div>
                   <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('discount_value')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="value" value="<?=set_value('value',$discount->value) ?>" class="form-control form-control-sm field_validation" id="value" placeholder="<?=$this->lang->line('discount_value')?>">
                      <span id="err_value" class="error invalid-feedback"><?=form_error('value');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('discount_valid_from')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" value="<?=set_value('valid_from',date('d-m-Y', strtotime($discount->valid_from))) ?>" class="form-control form-control-sm float-right field_validation datepicker" name="valid_from" id="valid_from" placeholder="<?=$this->lang->line('discount_valid_from')?>" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                      <span id="err_valid_from" class="error invalid-feedback"><?=form_error('valid_from');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('discount_valid_to')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control form-control-sm float-right field_validation datepicker" name="valid_to" value="<?=set_value('valid_to',date('d-m-Y', strtotime($discount->valid_to))) ?>" id="valid_to" placeholder="<?=$this->lang->line('discount_valid_to')?>" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" data-mask>
                      <span id="err_valid_to" class="error invalid-feedback"><?=form_error('valid_to');?></span>
                    </div>
                  </div>
                   <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('discount_description')?></label>
                    <div class="col-sm-4">
                      <input type="text" name="description" value="<?=set_value('description',$discount->description) ?>" class="form-control form-control-sm" id="description" placeholder="<?=$this->lang->line('discount_description')?>">
                      <span id="" class="error invalid-feedback"><?=form_error();?></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="id" value="<?=$discount->id?>">
                  <button type="submit" name="submit" id="discountSubmit" class="btn btn-info"><?=$this->lang->line('discount_edit')?></button>
                  <a href="<?=base_url('discount')?>" class="btn btn-default float-right"><?=$this->lang->line('discount_cancel')?></a>
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

    $('form#editDiscountForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#discountSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#editDiscountForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editDiscountForm #err_"+id).text(field+ " field is required.");
            $('form#editDiscountForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editDiscountForm #err_"+id).text("");
            $('form#editDiscountForm #'+id).removeClass('is-invalid');
            $('form#editDiscountForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#discountSubmit').text('<?=$this->lang->line("discount_edit")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#editDiscountForm .field_validation").on("blur keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editDiscountForm #err_"+id).text(field+ " field is required.");
          $('form#editDiscountForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editDiscountForm #err_"+id).text("");
          $('form#editDiscountForm #'+id).removeClass('is-invalid');
          $('form#editDiscountForm #'+id).addClass('is-valid');
        }
    });

  });
</script>



