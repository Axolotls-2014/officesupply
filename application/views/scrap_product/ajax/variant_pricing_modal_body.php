  
<form role="form" method="POST" name="variantPricingForm" id="variantPricingForm">
  <div class="modal-header text-left">
    <h4 class="modal-title"><?php echo $this->lang->line('product_variant_pricing');?></h4>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    <span aria-hidden="true">&times;</span></button>
  </div>
  <div class="modal-body" style="overflow-x: auto;">
    <table class="table table-bordered" id="variant_pricing_table">
      <thead>
        <tr>
          <th>Product Name</th>
          <th>Batch</th>
          <th>Mfg Date</th>
          <th>Expiry Date</th>
          <th width="10%">Purchase Rate</th>
          <th width="10%">MRP</th>
          <th width="10%">PTD</th>
          <th width="10%">PTR</th>
        </tr>
      </thead>
      <tbody>
        <?php 
          foreach ($warehouse_products as $value) {
        ?>
            <tr>
              <td>
                <?=$value->product_name?>
                <input type="hidden" name="warehouse_product_id" value="<?=$value->id?>">
              </td>
              <td>
                <input type="text" class="form-control" name="batch_no" value="<?=$value->batch_no?>" autocomplete="off" <?=($value->batch_no == '') ? ' disabled="disabled"' : ''?>>
              </td>
              <td><input type="text" class="form-control datepicker" name="mfg_date" value="<?=system_date_format($value->mfg_date)?>" autocomplete="off" <?=($value->batch_no == '') ? ' disabled="disabled"' : ''?>></td>
              <td><input type="text" class="form-control datepicker" name="expiry_date" value="<?=system_date_format($value->expiry_date)?>" autocomplete="off" <?=($value->batch_no == '') ? ' disabled="disabled"' : ''?>></td>
              <td><input type="number" class="form-control" name="cost" value="<?=$value->cost?>" step="any" readonly></td>
              <td><input type="number" class="form-control" name="price" value="<?=$value->price?>" step="any"></td>
              <td><input type="number" class="form-control" name="ptd" value="<?=$value->ptd?>" step="any"></td>
              <td><input type="number" class="form-control" name="ptr" value="<?=$value->ptr?>" step="any"></td>
            </tr>
        <?php
          }
        ?>  
      </tbody>
    </table>
  </div>
  <div class="modal-footer">
    <input type="hidden" name="warehouse_products" id="warehouse_products" value="">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="submit" id="variantPricingSubmit" class="btn btn-primary"><?=$this->lang->line('submit')?></button>
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
  </div>
</form>
  
