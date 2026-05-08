  
<form role="form" method="POST" name="bulkEditForm" id="bulkEditForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_bulk_edit');?></h4>
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
            <select class="form-control form-control-sm select2bs4" name="product_category_id" id="product_category_id" placeholder="<?=$this->lang->line('product_product_category_name')?>" width="100%" >
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
          <?=$this->lang->line("product_hsn")?>
        </label>
        <div class="col-sm-8">
          <input type="text" name="hsn" value="<?=set_value("hsn") ?>" class="form-control form-control-sm" id="hsn" placeholder="<?=$this->lang->line("product_hsn")?>">
          <span id="err_hsn" class="error invalid-feedback"></span>
        </div>
      </div>

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_cost")?>
        </label>
        <div class="col-sm-8">
          <input type="number" name="product_cost" value="<?=set_value("product_cost") ?>" class="form-control form-control-sm" id="product_cost" placeholder="<?=$this->lang->line("product_cost")?>">
          <span id="err_product_cost" class="error invalid-feedback"></span>
        </div>
      </div> 

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_price")?>
        </label>
        <div class="col-sm-8">
          <input type="text" name="product_price" value="<?=set_value("product_price") ?>" class="form-control form-control-sm" id="product_price" placeholder="<?=$this->lang->line("product_price")?>">
          <span id="err_product_price" class="error invalid-feedback"></span>
        </div>
      </div> 

      <!-- <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_markup")?>
        </label>
        <div class="col-sm-8">
          <input type="text" name="markup" value="<?=set_value("markup") ?>" class="form-control form-control-sm" id="markup" placeholder="<?=$this->lang->line("product_markup")?>">
          <span id="err_markup" class="error invalid-feedback"></span>
        </div>
      </div>  -->

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_alert_quantity")?>
        </label>
        <div class="col-sm-8">
          <input type="text" name="alert_quantity" value="<?=set_value("alert_quantity") ?>" class="form-control form-control-sm" id="alert_quantity" placeholder="<?=$this->lang->line("product_alert_quantity")?>">
          <span id="err_alert_quantity" class="error invalid-feedback"></span>
        </div>
      </div> 

      <div class="form-group row">
        <label for="inputEmail3" class="col-sm-4 col-form-label">
          <?=$this->lang->line("product_uom")?>
        </label>
        <div class="col-sm-8">
          <select class="form-control form-control-sm select2bs4" name="uom_id" id="uom_id" placeholder="<?=$this->lang->line('product_uom')?>" width="100%">
            <option value=""><?=$this->lang->line('select')?></option>
            <?php
              foreach ($uoms as $value) {
            ?>
              <option value="<?=$value->id;?>" <?php echo set_select('uom_id', $value->id); ?>>
                <?= $value->name.' ('.$value->uom.')';?>
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
          <?=$this->lang->line("product_status")?>
        </label>
        <div class="col-sm-8">
          <select class="form-control form-control-sm select2bs4" name="status" id="status" placeholder="<?=$this->lang->line('product_status')?>" width="100%">
            <option value="<?=PRODUCT_STATUS_ACTIVE?>">
              <?=ucfirst(PRODUCT_STATUS_ACTIVE)?>
            </option>
            <option value="<?=PRODUCT_STATUS_INACTIVE?>">
              <?=ucfirst(PRODUCT_STATUS_INACTIVE)?>
            </option>
          </select>
          <span id="err_status" class="error invalid-feedback"></span>
        </div>
      </div> 
    </div>
  <div class="modal-footer">
    <input type="hidden" name="markup" id="markup" value="0" id="markup">
    <input type="hidden" name="warehouse_product_ids" id="warehouse_product_ids" value="">
    <input type="hidden" name="product_ids" id="product_ids" value="">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="bulkEditSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
