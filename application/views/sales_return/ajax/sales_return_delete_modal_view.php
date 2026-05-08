<?php
	 $paid_amount  = $this->transaction_model->get_total_transaction_amount($sales_return->id, SALE_RETURN_MODULE, PAYMENT_TRANSACTION_TYPE);
  $delivered_product_qty = $this->sales_return_delivery_model->get_total_no_of_quantity_delivered($sales_return->id);
?>

<div class="modal-header  <?php 
		if($delivered_product_qty > 0 || $paid_amount > 0)
	  {
	  	echo 'warning-header';
	  }
	  else
	  {
	  	echo 'failure-header';
	  }
?>">
  <h4 class="modal-title">
     <?php echo $this->lang->line('sales_return_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
	<?php 
		if($delivered_product_qty > 0 || $paid_amount > 0)
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
        <li><?=$this->lang->line('due_to_following_reason_payment_transaction')?></li>
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
		 	 <?php echo "Are you sure want to delete this sales return with Reference No : ".$sales_return->reference_no."?";?>
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
			if(!($delivered_product_qty > 0 || $paid_amount > 0))
		  {
		?>
    		<form action="<?php echo base_url('sales_return/delete');?>" method="POST" name="deletesalesReturnForm" id="deletesalesReturnForm">
		      <input type="hidden" name="id" value="<?=$sales_return->id?>">
		      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		      <button type="submit" name="submit" name="deletesalesReturnSubmit" id="deletesalesReturnSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
		    </form>
    <?php 
    	}
   	?>
    
</div>