<?php $this->load->view('layout/header');?>

  <div class="wrapper">
    <div class="content-wrapper">
      <section class="content-header">
          <div class="row mb-2">
            <div class="col-sm-12">
              <ol class="breadcrumb breadcrumb-custom float-sm-left">
                <li class="breadcrumb-item"><a href="<?php echo base_url('auth');?>"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item "><a href="https://ossdemo.tastytap.in/product_core"><?=$this->lang->line('header_inventory')?></a></li>
         
                <li class="breadcrumb-item "><a href="<?=base_url('product_category/list')?>"><?=$this->lang->line('header_product_category')?></a></li>
                <li class="breadcrumb-item active"><?=$this->lang->line('product_category_edit')?></li>
              </ol>
            </div>
          </div>
      </section>

      <section class="content">
        <div class="row">
          <div class="col-md-12">
            <form class="form-horizontal" name="editproductCategoryForm" id="editproductCategoryForm" method="post" action="<?php echo base_url('product_category/edit');?>">
              <div class="card card-info">
                <div class="card-header">
                  <h3 class="card-title"><?=$this->lang->line('product_category_edit')?></h3>
                </div>
                <div class="card-body">
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_category_name')?>
                      <span class="text-danger">*</span> 
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="name" value="<?=set_value('name',$product_category->name) ?>" class="form-control form-control-sm field_validation" id="name" placeholder="<?=$this->lang->line('product_category_name')?>">
                      <span id="err_name" class="error invalid-feedback"><?=form_error('name');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label">
                      <?=$this->lang->line('product_category_description')?>
                      <span class="text-danger">*</span>
                    </label>
                    <div class="col-sm-4">
                      <input type="text" name="description" value="<?=set_value('description',$product_category->description) ?>" class="form-control form-control-sm field_validation" id="description" placeholder="<?=$this->lang->line('product_category_description')?>">
                      <span id="err_description" class="error invalid-feedback"><?=form_error('description');?></span>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('product_category_tax_name')?><span class="text-danger">*</span></label>
                    <div class="col-sm-4">
                      <?php
                        if($this->permission_model->has_permission('add_tax'))
                        { 
                      ?>
                      <div class="input-group input-group-sm">
                        <select class="form-control form-control-sm select2bs4 field_validation" value="<?php echo $product_category->tax_id;?>" name="tax_id" id="tax_id" width="100%">
                          <?php
                            foreach ($tax as $value) {
                          ?>
                            <option value="<?=$value->id;?>"
                              <?php 
                                if($value->id == $product_category->tax_id)
                                  echo ' selected';
                              ?>
                            >
                              <?= $value->tax_name;?>
                            </option>
                          <?php 
                            }
                          ?>
                        </select>
                        <span class="input-group-append">
                          <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#add_tax_modal" data-tt="tooltip" title="<?=$this->lang->line('tax_add')?>"><i class="fas fa-plus"></i></button>
                        </span>
                      </div>
                      <?php
                        }
                        else
                        {
                      ?>
                      <select class="form-control form-control-sm select2bs4 field_validation" value="<?php echo $product_category->tax_id;?>" name="tax_id" id="tax_id" width="100%">
                        <?php
                          foreach ($tax as $value) {
                        ?>
                          <option value="<?=$value->id;?>"
                            <?php 
                              if($value->id == $product_category->tax_id)
                                echo ' selected';
                            ?>
                          >
                            <?= $value->tax_name;?>
                          </option>
                        <?php 
                          }
                        ?>
                      </select>
                      <?php
                        } 
                      ?>
                      
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="inputEmail3" class="col-sm-2 col-form-label"><?=$this->lang->line('product_category_tax_type')?></label>
                    <div class="col-sm-4">
                      <div class="custom-control custom-checkbox">
                          <input class="custom-control-input" name="tax_type" type="checkbox" id="tax_type" value="1"
                            <?php
                              if($product_category->tax_type == 1)
                              {
                                echo " checked";
                              } 
                            ?>
                          >
                          <label for="tax_type" class="custom-control-label"></label>
                      </div>
                      <span id="err_tax_type" class="error invalid-feedback"><?=form_error('tax_type');?></span>
                    </div>
                  </div>
                </div>
                <div class="card-footer">
                  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
                  <input type="hidden" name="id" value="<?=$product_category->id?>">
                  <button type="submit" name="submit" id="productCategorySubmit" class="btn btn-info"><?=$this->lang->line('product_category_edit')?></button>
                   <a href="<?=base_url('product_category')?>" class="btn btn-default float-right"><?=$this->lang->line('product_category_cancel')?></a>
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

    $('form#editproductCategoryForm').submit(function(e){
      // e.preventDefault();

      var isError = false;
      $('#productCategorySubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

      $('form#editproductCategoryForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#editproductCategoryForm #err_"+id).text(field+ " field is required.");
            $('form#editproductCategoryForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#editproductCategoryForm #err_"+id).text("");
            $('form#editproductCategoryForm #'+id).removeClass('is-invalid');
            $('form#editproductCategoryForm #'+id).addClass('is-valid');
          }
      });


      if(isError == true)
      {
        $('#productCategorySubmit').text('<?=$this->lang->line("product_category_edit")?>').removeAttr('disabled');
        return false;
      }  
      else 
      {
        return true;
      }    


    });

    $("form#editproductCategoryForm .field_validation").on("blur change keyup",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#editproductCategoryForm #err_"+id).text(field+ " field is required.");
          $('form#editproductCategoryForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#editproductCategoryForm #err_"+id).text("");
          $('form#editproductCategoryForm #'+id).removeClass('is-invalid');
          $('form#editproductCategoryForm #'+id).addClass('is-valid');
        }
    });

  });
</script>

