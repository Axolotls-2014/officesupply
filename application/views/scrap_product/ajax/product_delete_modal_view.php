<?php 

	$product 			= $this->product_model->get_single_record($product_id);
  $sales        = $this->sale_model->get_sale_item_records_by_product_id($product_id);
  $quotations   = $this->quotation_model->get_quotation_item_records_by_product_id($product_id);
  $purchases    = $this->purchase_model->get_purchase_item_records_by_product_id($product_id);
?>



<div class="modal-header  <?php 
		if(sizeof($sales) > 0 || sizeof($quotations) > 0 || sizeof($purchases) > 0)
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
		if(sizeof($sales) > 0 || sizeof($quotations) > 0 || sizeof($purchases) > 0)
	  {
	?>
		<p>
	    <?=$this->lang->line('due_to_following_reason')?>
	  </p>
	  <ul style="list-style: disc;">
	    <?php 
	      if($sales != null)
	      {
	    ?>
	      <li><?=$this->lang->line('due_to_following_reason_sale_items_exist')?></li>
	    <?php
	      }
	    ?>

	    <?php 
	      if($purchases != null)
	      {
	    ?>
	      <li><?=$this->lang->line('due_to_following_reason_purchase_items_exist')?></li>
	    <?php
	      }
	    ?>

	    <?php 
	      if($quotations != null)
	      {
	    ?>
	      <li><?=$this->lang->line('due_to_following_reason_quotation_items_exist')?></li>
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
			if(!(sizeof($sales) > 0 || sizeof($quotations) > 0 || sizeof($purchases) > 0))
		  {
		?>
    		<form action="<?php echo base_url('product/delete');?>" method="POST" name="deleteProductForm" id="deleteProductForm">
		      <input type="hidden" name="id" value="<?=$product->id?>">
		      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		      <button type="submit" name="deleteProductSubmit" id="deleteProductSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
		    </form>
    <?php 
    	}
   	?>
    
</div>