<?php 
	$purchases = $this->purchase_model->get_purchase_records_by_supplier_id($supplier->id);
  $expenses  = $this->expense_model->get_records_by_supplier_id($supplier->id);
?>



<div class="modal-header  <?php 
		if(sizeof($purchases) > 0 || sizeof($expenses) > 0)
	  {
	  	echo 'warning-header';
	  }
	  else
	  {
	  	echo 'failure-header';
	  }
?>">
  <h4 class="modal-title">
    <?php echo $this->lang->line('supplier_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
	<?php 
		if(sizeof($purchases) > 0 || sizeof($expenses) > 0)
	  {
	?>
		<p>
	    <?=$this->lang->line('due_to_following_reason')?>
	  </p>
	  <ul style="list-style: disc;">
	    <?php 
	      if($purchases != null)
	      {
	    ?>
	      <li><?=$this->lang->line('due_to_following_reason_purchase_exist')?></li>
	    <?php
	      }
	    ?>

	    <?php 
	      if($expenses != null)
	      {
	    ?>
	      <li><?=$this->lang->line('due_to_following_reason_expense_exist')?></li>
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
		 	<?php echo $this->lang->line('expense_delete_message') .$supplier->company_name."?";?>
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
			if(!(sizeof($purchases) > 0 || sizeof($expenses) > 0))
		  {
		?>
    		<form action="<?php echo base_url('supplier/delete');?>" method="POST" name="deleteSupplierForm" id="deleteSupplierForm">
		      <input type="hidden" name="id" value="<?=$supplier->id?>">
		      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		      <button type="submit" name="deleteSupplierSubmit" id="deleteSupplierSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
		    </form>
    <?php 
    	}
   	?>
    
</div>