<?php 

  $product_categories = $this->product_category_model->get_records_by_tax_id($tax->id);
  $sale_items         = $this->sale_model->get_sale_item_records_by_tax_id($tax->id);
  $purchase_items     = $this->purchase_model->get_purchase_item_records_by_tax_id($tax->id);
?>



<div class="modal-header  <?php 
		if($product_categories != null || $sale_items != null || $purchase_items != null)
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
		if($product_categories != null || $sale_items != null || $purchase_items != null)
  	{
	?>
		<p>
	    <?=$this->lang->line('due_to_following_reason')?>
	  </p>
	  <ul style="list-style: disc;">
	  	<?php 
        if($product_categories != null)
        {
      ?>
        <li><?=$this->lang->line('due_to_following_reason_product_category_exist')?></li>
      <?php
        }
      ?>
	    <?php 
	      if($sale_items != null)
	      {
	    ?>
	      <li><?=$this->lang->line('due_to_following_reason_sale_item')?></li>
	    <?php
	      }
	    ?>

	    <?php 
	       if($purchase_items != null)
	      {
	    ?>
	      <li><?=$this->lang->line('due_to_following_reason_purchase_item')?></li>
	    <?php
	      }
	    ?>
	  </ul>
	<?php 
		}
		else
		{
	?>
		<p>
		   <?php echo $this->lang->line('tax_delete_message') .$tax->tax_name."?";?>
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
			if($product_categories == null && $sale_items == null && $purchase_items == null)
		  {
		?>
    		<form action="<?php echo base_url('tax/delete');?>" method="POST" name="deleteTaxForm" id="deleteTaxForm">
		      <input type="hidden" name="id" value="<?=$tax->id?>">
		      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		      <button type="submit" name="deleteTaxSubmit" id="deleteTaxSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
		    </form>
    <?php 
    	}
   	?>
    
</div>