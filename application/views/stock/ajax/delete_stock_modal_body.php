<?php
  
  $warehouse_product = $this->warehouse_products_model->get_single_product_by_warehouse_id_product_id_batch_no($stock->warehouse_id, $stock->product_id, $stock->batch_no);

?>
<div class="modal-header  <?php 
		if($stock->entry_type == 'in')
    {
      if($stock->quantity == $warehouse_product->quantity)
      {
        echo 'failure-header';
       
      }
      else
      {
        echo 'warning-header';
      }
    }
    else
    {
      echo 'failure-header';
    }
	  
?>">
  <h4 class="modal-title">
     <?php echo $this->lang->line('stock_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">

<?php
  if($stock->entry_type == 'in')
  {
    if($stock->quantity == $warehouse_product->quantity)
    {
?>

      <p> <?php echo "Are you sure want to delete this Stock ?";?> </p>
<?php
    }
  
    else
    {
?>  
      <p> <?php echo "Enough quantity is not available. You can not delete this stock in entry" ?></p>
<?php
    }
  }
  else
  {
?>
    <p> <?php echo "Are you sure want to delete this Stock ?";?> </p>
<?php
  }
?>
</div>
<div class="modal-footer">

  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>

  <?php 
    if($stock->entry_type == 'in')
    {
      if($stock->quantity == $warehouse_product->quantity)
      {
  ?>
  <form method="POST" name="deleteStockForm" id="deleteStockForm">
    <input type="hidden" name="id" id="id" value="<?=$stock->id?>">
    <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
    <button type="submit" name="deleteStockSubmit" id="deleteStockSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
  </form>

  <?php
      }
    }
    else
    {
  ?>
      <form method="POST" name="deleteStockForm" id="deleteStockForm">
      <input type="hidden" name="id" id="id" value="<?=$stock->id?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit" name="deleteStockSubmit" id="deleteStockSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
    </form>
  <?php
    }
  ?>
</div>