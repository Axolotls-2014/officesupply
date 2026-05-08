<!-- <div class="modal-header failure-header">
  <h4 class="modal-title">
     <?php echo $this->lang->line('product_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <?php echo "Are you sure want to delete this Product ?";?>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>
  <form method="POST" name="deleteProductForm" id="deleteProductForm">
    <input type="hidden" name="id" id="id" value="<?=$warehouse_product->id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="deleteProductSubmit" id="deleteProductSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
  </form>
</div> -->

<?php 
  $purchase_item        = $this->purchase_model->get_purchase_item_records_by_product_id($warehouse_product->product_id);
?>

<div class="modal-header <?php 
    if($purchase_item != null)
    {
      echo 'warning-header';
    }
    else
    {
      echo 'failure-header';
    }
?>">
  <h4 class="modal-title">
     <?php echo $this->lang->line('product_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <?php 
    if($purchase_item != null)
    {
  ?>
    <p>
      <?=$this->lang->line('due_to_following_reason')?>
    </p>
    <ul style="list-style: disc;">
      <li>This product is already purchased. You can not delete this product</li>
    </ul>

  <?php 
    }
    else
    {
  ?>
    <p>
      <?php echo $this->lang->line('product_delete_message') .$product->name."?";?>
    </p>
  <?php
    }
  ?>
</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>
  <?php 
    if($purchase_item == null)
    {
  ?>
  <form method="POST" name="deleteProductForm" id="deleteProductForm">
    <!-- warehouse product id -->
    <input type="hidden" name="id" id="id" value="<?=$warehouse_product->id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="deleteProductSubmit" id="deleteProductSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
  </form>
  <?php 
    }
  ?>
</div>