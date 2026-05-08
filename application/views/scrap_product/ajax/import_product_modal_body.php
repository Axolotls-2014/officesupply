  
<form role="form" method="POST" name="importProductForm" id="importProductForm" enctype="multipart/form-data" action="<?=base_url('product/import_product')?>">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_import_product');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body">

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_product_category_name")?>
        </label>
        <div class="col-sm-8">
          <?php
            if($this->permission_model->has_permission('add_product_category'))
            { 
          ?>
          <div class="input-group input-group-sm">
            <select class="form-control form-control-sm select2bs4 field_validation" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_product_category_name')?>" width="100%">
              <option value=""><?=$this->lang->line('select')?></option>
              <?php
                foreach ($product_categories as $value) {
              ?>
                <option value="<?=$value->id;?>">
                  <?= $value->name;?>
                </option>
              <?php 
                }
              ?>
            </select>
            <span class="input-group-append">
              <button type="button" class="btn btn-info btn-flat add_product_category_modal" data-toggle="modal" data-target="#add_product_category_modal" data-tt="tooltip" title="<?=$this->lang->line('product_category_add')?>"><i class="fas fa-plus"></i></button>
            </span>
          </div>
          <?php
            }
            else
            {
          ?>
          <select class="form-control form-control-sm select2bs4" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_product_category_name')?>" width="100%">
            <?php
              foreach ($product_categories as $value) {
            ?>
              <option value="<?=$value->id;?>">
                <?= $value->name;?>
              </option>
            <?php 
              }
            ?>
          </select>
          <?php
            } 
          ?>
          <span id="err_product_category_id" class="error invalid-feedback"></span>
        </div>
      </div>

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_uom")?>
        </label>
        <div class="col-sm-8">
          <select class="form-control form-control-sm select2bs4" name="uom_id" id="uom_id" placeholder="<?=$this->lang->line('product_uom')?>" width="100%">
            <?php
              foreach ($uoms as $value) {
            ?>
              <option value="<?=$value->id;?>">
                <?= $value->uom;?>
              </option>
            <?php 
              }
            ?>
          </select>
          
          <span id="err_uom_id" class="error invalid-feedback"></span>
        </div>
      </div> 

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_csv_file")?>
        </label>
        <div class="col-sm-8">
          <div class="input-group input-group-sm">
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="csvfile" name="csvfile" accept=".csv">
              <label class="custom-file-label" for="exampleInputFile">Choose file</label>
            </div>
          </div>
          <span id="err_csvfile" class="error invalid-feedback"></span>
        </div>
      </div> 

      <div class="row">
        <div class="col-md-12" style="opacity: 0.7; border-radius: 5px; background-color: #F8E4A4; font-size: 16px;">
          <div class="m-4">
            Download sample file  <a href="<?=base_url('assets/sample_files/sample_product.csv')?>" target="_blank" class="btn btn-info" style="float:right">Click here to Download</a>  
          </div>
          
        </div>
      </div>
  </div>

  <div class="modal-footer">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="importProductSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
