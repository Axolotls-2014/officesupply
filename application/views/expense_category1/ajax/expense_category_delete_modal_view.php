<?php 
	$expense_category 			= $this->expense_category_model->get_single_record($expense_category_id);
?>



<div class="modal-header  <?php 
		if($this->expense_model->get_records_by_expense_category_id($expense_category->id) != null)
	  {
	  	echo 'warning-header';
	  }
	  else
	  {
	  	echo 'failure-header';
	  }
?>">
  <h4 class="modal-title">
    <?php echo $this->lang->line('expense_category_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
	<?php 
		if($this->expense_model->get_records_by_expense_category_id($expense_category->id) != null)
	  {
	?>
		<p>
	    <h6><?=$this->lang->line('can_not_delete_the_expense_category')?></h6>
	  </p>
		<ul>
      <li><?=$this->lang->line('expense_category_used_in_expense')?></li>
    </ul>

	<?php 
		}
		else
		{
	?>
		<p>
		    <?php echo $this->lang->line('expense_delete_message') .$expense_category->name."?";?>
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
			if(!($this->expense_model->get_records_by_expense_category_id($expense_category->id) != null))
		  {
		?>
    		<form action="<?php echo base_url('expense_category/delete');?>" method="POST" name="deleteexpenseCategoryForm" id="deleteexpenseCategoryForm">
          <input type="hidden" name="id" value="<?=$expense_category->id?>">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <button type="submit" name="deleteexpenseCategorySubmit" id="deleteexpenseCategorySubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
        </form>
    <?php 
    	}
   	?>
    
</div>