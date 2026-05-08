<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
                <li class="breadcrumb-item "><a href="#"><?=$this->lang->line('header_sales')?></a></li>
                <li class="breadcrumb-item "><a href="<?=base_url('service')?>"><?=$this->lang->line('header_service')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('service_add')?></li>
              </ol>
            </div>
          </div>
      </section>
      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="addServiceForm" id="addServiceForm" method="post" action="<?=base_url('service/add')?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('service_add')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('service_name')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="text" name="name" value="<?=set_value('name') ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('service_name')?>"><?=form_error('name');?>
                      <span id="err_name" class="error invalid-feedback"><?=form_error('name');?></span>
                    </div>
                  </div>
                 
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('service_description')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="description" value="<?=set_value('description')?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line('service_description')?>"><?=form_error('description');?>
                      <span id="err_description" class="error invalid-feedback"><?=form_error('description');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('service_sac_code')?> <br/>(<?=$this->lang->line('service_sac_code_full')?>)
                      <!-- <span class="text-danger">*</span> -->
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="sac_code" value="<?=set_value('sac_code')?>" class="form-control form-control-sm" id="sac_code" placeholder="<?=$this->lang->line('service_sac_code')?>"><?=form_error('sac_code');?>
                      <span id="err_sac_code" class="error invalid-feedback"><?=form_error('sac_code');?></span>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('service_price')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <input type="number" name="price" value="<?=set_value('price',0.0)?>" class="form-control form-control-sm field_validation" id="price" placeholder="<?=$this->lang->line('service_price')?>"><?=form_error('price');?>
                      <span id="err_price" class="error invalid-feedback"><?=form_error('price');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputPassword3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('service_tax')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <div class="input-group">
                        <select class="form-control form-control-sm select2bs4 field_validation" placeholder="<?=$this->lang->line('service_tax')?>" name="tax_id" id="tax_id" width="100%">
                          <option value=""><?=$this->lang->line('service_select_tax')?></option>
                          <?php
                            foreach ($taxes as $value) {
                          ?>
                            <option value="<?=$value->id;?>"
                              <?php 
                                if(isset($tax_id))
                                {
                                  if($tax_id == $value->id)  
                                    echo ' selected';
                                }
                              ?>
                            >
                              <?= $value->tax_name.' (IGST='.$value->igst.' SGST='.$value->sgst.' CGST='.$value->cgst.')';?>
                            </option>
                            
                          <?php 
                            }
                          ?>
                        </select>
                        <span class="input-group-append">
                          <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_tax_modal" data-tt="tooltip" title="Click here to Add Tax" accesskey="t"><i class="fas fa-plus"></i>
                          </button>
                        </span>
                      </div>
                      <span id="err_tax_id" class="error invalid-feedback"><?=form_error('tax_id');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('service_tax_type')?></label>
                    <div class="col-sm-4">
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" name="tax_type" type="checkbox" id="tax_type" value="1">
                          <label for="tax_type" class="custom-control-label"></label>
                        </div>
                      <span id="err_tax_type" class="error invalid-feedback"><?=form_error('tax_type');?></span>
                    </div>
                  </div>

                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <button type="submit" name="submit" id="serviceSubmit" class="btn btn-info"><?=$this->lang->line('service_save')?></button>
                  <a href="<?=base_url('service')?>" class="btn btn-default float-right"><?=$this->lang->line('service_cancel')?></a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </section>
  
    </div>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>

<?php 
  $this->load->view('layout/footer');
  $this->load->view('tax/add_tax_modal');
?>


<script type="text/javascript">

 
  $(document).ready(function(e){

    $('#serviceSubmit').click(function(e){
      // e.preventDefault();

      var isError = false;

      $('form#addServiceForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addServiceForm #err_"+id).text(field+ " field is required.");
            $('form#addServiceForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addServiceForm #err_"+id).text("");
            $('form#addServiceForm #'+id).removeClass('is-invalid');
            $('form#addServiceForm #'+id).addClass('is-valid');
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

    $("form#addServiceForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addServiceForm #err_"+id).text(field+ " field is required.");
          $('form#addServiceForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addServiceForm #err_"+id).text("");
          $('form#addServiceForm #'+id).removeClass('is-invalid');
          $('form#addServiceForm #'+id).addClass('is-valid');
        }
    });

  });
</script>
