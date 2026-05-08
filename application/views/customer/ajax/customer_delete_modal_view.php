<?php 
	$sales        = $this->sale_model->get_sale_records_by_customer_id($customer->id);
  $quotations   = $this->quotation_model->get_quotation_records_by_customer_id($customer->id);
?>



<div class="modal-header  <?php 
		if(sizeof($sales) > 0 || sizeof($quotations) > 0)
	  {
	  	echo 'warning-header';
	  }
	  else
	  {
	  	echo 'failure-header';
	  }
?>">
  <h4 class="modal-title">
    <?php echo $this->lang->line('customer_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
	<?php 
		if(sizeof($sales) > 0 || sizeof($quotations) > 0)
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
	      <li><?=$this->lang->line('due_to_following_reason_sale_exist')?></li>
	    <?php
	      }
	    ?>

	    <?php 
	      if($quotations != null)
	      {
	    ?>
	      <li><?=$this->lang->line('due_to_following_reason_quotation_exist')?></li>
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
		    <?php echo $this->lang->line('customer_delete_message') .$customer->customer_name."?";?>
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
			if(!(sizeof($sales) > 0 || sizeof($quotations) > 0))
		  {
		?>
    		<form action="<?php echo base_url('customer/delete');?>" method="POST" name="deleteCustomerForm" id="deleteCustomerForm">
		      <input type="hidden" name="id" value="<?=$customer->id?>">
		      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		      <button type="submit" name="deleteCustomerSubmit" id="deleteCustomerSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
		    </form>
    <?php 
    	}
   	?>
    
</div>