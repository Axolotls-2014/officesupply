<?php
	$paid_amount = $this->transaction_model->get_total_transaction_amount($expense->id, EXPENSE_MODULE, PAYMENT_TRANSACTION_TYPE);
                        ($paid_amount == '') ? $paid_amount = 0 : $paid_amount = $paid_amount;
?>

<div class="modal-header  <?php 
		if($paid_amount > 0)
	  {
	  	echo 'warning-header';
	  }
	  else
	  {
	  	echo 'failure-header';
	  }
?>">
  <h4 class="modal-title">
     <?php echo $this->lang->line('expense_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
	<?php 
		if($paid_amount > 0)
	  {
	?>
		<p>
	    <?=$this->lang->line('due_to_following_reason')?>
	  </p>
	  <ul style="list-style: disc;">
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
		 	 <?php echo $this->lang->line('expense_delete_message') .$expense->expense_category_name."?";?>
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
			if(!($paid_amount > 0))
		  {
		?>
    		<form action="<?php echo base_url('expense/delete');?>" method="POST" name="deleteExpenseForm" id="deleteExpenseForm">
		      <input type="hidden" name="id" value="<?=$expense->id?>">
		      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		      <button type="submit" name="deleteExpenseSubmit" id="deleteExpenseSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
		    </form>
    <?php 
    	}
   	?>
    
</div>