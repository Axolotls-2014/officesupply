<?php
	 $paid_amount = $this->transaction_model->get_total_transaction_amount($purchase_return->id, PURCHASE_RETURN_MODULE, RECEIPT_TRANSACTION_TYPE);
   $delivered_product_qty = $this->purchase_return_delivery_model->get_total_no_of_quantity_delivered($purchase_return->id);
?>

<div class="modal-header  <?php 
		  if($paid_amount > 0 || $delivered_product_qty > 0)
	  {
	  	echo 'warning-header';
	  }
	  else
	  {
	  	echo 'failure-header';
	  }
?>">
  <h4 class="modal-title">
     <?php echo $this->lang->line('purchase_return_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
	<?php 
		if($paid_amount > 0 || $delivered_product_qty > 0)
	  {
	?>
		<p>
	    <?=$this->lang->line('due_to_following_reason')?>
	  </p>
	  <ul style="list-style: disc;">
	    <?php 
	       if($delivered_product_qty > 0)
	      {
	    ?>
	      <li><?=$this->lang->line('due_to_following_reason_product_delivered')?></li>
	    <?php
	      }
	    ?>

	     <?php 
	       if($paid_amount > 0)
	      {
	    ?>
	      <li><?=$this->lang->line('due_to_following_reason_payment_transaction')?>/li>
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
		 	 <?php echo "Are you sure want to delete this purchase return with Reference No : ".$purchase_return->reference_no."?";?>
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
			if(!($paid_amount > 0 || $delivered_product_qty > 0))
		  {
		?>
    		<form action="<?php echo base_url('purchase_return/delete');?>" method="POST" name="deletepurchaseReturnForm" id="deletepurchaseReturnForm">
		      <input type="hidden" name="id" value="<?=$purchase_return->id?>">
		      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		      <button type="submit" name="deletepurchaseReturnSubmit" id="deletepurchaseReturnSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
		    </form>
    <?php 
    	}
   	?>
    
</div>