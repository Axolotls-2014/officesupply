<?php 
	$item 			= $this->item_model->get_single_record($item_id);
?>



<div class="modal-header  <?php 
		if($this->expense_model->get_records_by_item_id($item->id) != null)
	  {
	  	echo 'warning-header';
	  }
	  else
	  {
	  	echo 'failure-header';
	  }
?>">
  <h4 class="modal-title">
    <?php echo $this->lang->line('item_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
	<?php 
		if($this->expense_model->get_records_by_item_id($item->id) != null)
	  {
	?>
		<p>
	    <h6><?=$this->lang->line('can_not_delete_the_item')?></h6>
	  </p>
		<ul>
      <li><?=$this->lang->line('item_used_in_expense')?></li>
    </ul>

	<?php 
		}
		else
		{
	?>
		<p>
		    <?php echo $this->lang->line('expense_delete_message') .$item->name."?";?>
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
			if(!($this->expense_model->get_records_by_item_id($item->id) != null))
		  {
		?>
    		<form action="<?php echo base_url('item/delete');?>" method="POST" name="deleteItemForm" id="deleteItemForm">
          <input type="hidden" name="id" value="<?=$item->id?>">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <button type="submit" name="deleteItemSubmit" id="deleteItemSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
        </form>
    <?php 
    	}
   	?>
    
</div>