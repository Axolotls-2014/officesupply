<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                  <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><a href="https://ossdemo.tastytap.in/product_core"><?=$this->lang->line('header_inventory')?></a></li>
         
                <li class="breadcrumb-item "><a href="<?=base_url('product/list')?>"><?=$this->lang->line('header_product')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('product_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editProductForm" id="editProductForm" method="post" action="<?php echo base_url('product/edit');?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('product_edit')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_name')?>
                      <span class="text-danger">*</span> 
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="name" value="<?=set_value('name',$product->name) ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('product_name')?>"><?=form_error('name', '<div class="text-danger">', '</div>');?>
                      <span id="err_name" class="error invalid-feedback"><!-- <?=form_error('name');?> --></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_description')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="description" value="<?=set_value('description',$product->description) ?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line('product_description')?>"><?=form_error('description', '<div class="text-danger">', '</div>');?>
                      <span id="err_description" class="error invalid-feedback"><!-- <?=form_error('symbol');?> --></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('product_category_name')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <?php
                        if($this->permission_model->has_permission('add_product_category'))
                        { 
                      ?>
                      <div class="input-group input-group-sm">
                        <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_category_name')?>" width="100%">
                          <?php
                            foreach ($product_categories as $value) {
                          ?>
                            <option value="<?=$value->id;?>"
                              <?php 
                                if($value->id == $product->product_category_id)
                                  echo ' selected';
                              ?>
                            >
                              <?= $value->name;?>
                            </option>
                          <?php 
                            }
                          ?>
                        </select>
                        <span class="input-group-append">
                          <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_product_category_modal" data-tt="tooltip" title="<?=$this->lang->line('product_category_add')?>"><i class="fas fa-plus"></i></button>
                        </span>
                      </div>
                      <?php
                        }
                        else
                        {
                      ?>
                      <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_category_name')?>" width="100%">
                        <?php
                          foreach ($product_categories as $value) {
                        ?>
                          <option value="<?=$value->id;?>"
                            <?php 
                              if($value->id == $product->product_category_id)
                                echo ' selected';
                            ?>
                          >
                            <?= $value->name;?>
                          </option>
                        <?php 
                          }
                        ?>
                      </select>
                      <?php
                        } 
                      ?>
                      
                      <span id="err_product_category_id" class="error invalid-feedback"><?=form_error('product_category_id');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_hsn')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="hsn" value="<?=set_value('hsn',$product->hsn) ?>" class="form-control form-control-sm field_validation" id="hsn" placeholder="<?=$this->lang->line('product_hsn')?>">
                      <span id="err_hsn" class="error invalid-feedback"><?=form_error('hsn');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_markup')?>
                       <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="number" name="markup" value="<?=set_value('markup',$product->markup) ?>" class="form-control form-control-sm field_validation" id="markup" placeholder="<?=$this->lang->line('product_markup')?>" step="0.1">
                      <span id="err_markup" class="error invalid-feedback"><?=form_error('markup');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_alert_quantity')?>
                       <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="number" name="alert_quantity" value="<?=set_value('alert_quantity',$product->alert_quantity)?>" class="form-control form-control-sm field_validation" id="alert_quantity" placeholder="<?=$this->lang->line('product_alert_quantity')?>" step="0.1">
                      <span id="err_alert_quantity" class="error invalid-feedback"><?=form_error('alert_quantity');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_uom')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4" name="uom_id" id="uom_id" placeholder="<?=$this->lang->line('product_uom')?>" width="100%">
                        <option value=""><?=$this->lang->line('select')?></option>
                        <?php
                          foreach ($uoms as $value) {
                        ?>
                          <option value="<?=$value->id;?>" 
                            <?php 
                              if(isset($uom_id))
                              {
                                if($uom_id == $value->id)
                                {
                                  echo ' selected';
                                }
                              }
                              else
                              {
                                if($value->id == $product->uom_id)
                                {
                                  echo ' selected';
                                }
                              }
                            ?>
                          >
                            <?= $value->name.' ('.$value->uom.')';?>
                          </option>
                        <?php 
                          }
                        ?>
                      </select>
                      <span id="err_uom_id" class="error invalid-feedback"><?=form_error('uom_id');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_status')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <select class="form-control form-control-sm select2bs4 field_validation" name="status" id="status" placeholder="<?=$this->lang->line('product_status')?>" width="100%">
                        <option value="<?=PRODUCT_STATUS_ACTIVE?>"
                          <?php 
                            if($product->status == PRODUCT_STATUS_ACTIVE)
                              echo ' selected';
                          ?>
                        >
                          <?=ucfirst(PRODUCT_STATUS_ACTIVE)?>
                        </option>
                        <option value="<?=PRODUCT_STATUS_INACTIVE?>"
                          <?php 
                            if($product->status == PRODUCT_STATUS_INACTIVE)
                              echo ' selected';
                          ?>
                        >
                          <?=ucfirst(PRODUCT_STATUS_INACTIVE)?>
                        </option>
                        
                      </select>
                      <span id="err_status" class="error invalid-feedback"><?=form_error('status')?></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="id" value="<?=$product->id?>">
                  <button type="submit" name="submit" id="productSubmit" class="btn btn-info"><?=$this->lang->line('product_edit')?></button>
                   <a href="<?=base_url('product')?>" class="btn btn-default float-right"><?=$this->lang->line('product_cancel')?></a>
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
  $this->load->view('product_category/add_product_category_modal');
?>

<script type="text/javascript">
 
  $(document).ready(function(e){

    $('form#editProductForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#productSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#editProductForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editProductForm #err_"+id).text(field+ " field is required.");
            $('form#editProductForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editProductForm #err_"+id).text("");
            $('form#editProductForm #'+id).removeClass('is-invalid');
            $('form#editProductForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#productSubmit').text('<?=$this->lang->line("product_save")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#editProductForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editProductForm #err_"+id).text(field+ " field is required.");
          $('form#editProductForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editProductForm #err_"+id).text("");
          $('form#editProductForm #'+id).removeClass('is-invalid');
          $('form#editProductForm #'+id).addClass('is-valid');
        }
    });

  });
</script>

