<?php 
	$discount 			= $this->discount_model->get_single_record($discount_id);
	$sale_items 		= $this->sale_model->get_sale_item_records_by_discount_id($discount->id);
?>

<div class="modal-header  <?php 
		if($sale_items != null)
	  {
	  	echo 'warning-header';
	  }
	  else
	  {
	  	echo 'failure-header';
	  }
?>">
  <h4 class="modal-title">
     <?php echo $this->lang->line('discount_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
	<?php 
	  if($sale_items != null)
	  {
	?>
		<p>
	    <h6><?=$this->lang->line('can_not_delete_the_discount')?></h6>
	  </p>
		<ul>
      <li><?=$this->lang->line('discount_used_in_sale')?></li>
    </ul>
	<?php 
		}
		else
		{
	?>
		<p>
	    <?php echo $this->lang->line('discount_delete_message') .$discount->name."?";?>
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
			if($sale_items == null)
		  {
		?>
    		<form action="<?php echo base_url('discount/delete');?>" method="POST" name="deleteDiscountForm" id="deleteDiscountForm">
          <input type="hidden" name="id" value="<?=$discount->id?>">
          <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
          <button type="submit" name="deleteDiscountSubmit" id="deleteDiscountSubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
        </form>
    <?php 
    	}
   	?>
    
</div>