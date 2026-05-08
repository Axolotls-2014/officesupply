<?php
	  $products = $this->product_model->get_records_by_product_category($product_category->id);
?>

<div class="modal-header  <?php 
		if($products != null)
	  {
	  	echo 'warning-header';
	  }
	  else
	  {
	  	echo 'failure-header';
	  }
?>">
  <h4 class="modal-title">
     <?php echo $this->lang->line('product_category_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
	<?php 
		if($products != null)
	  {
	?>
		<p>
	    <?=$this->lang->line('due_to_following_reason')?>
	  </p>
	  <ul style="list-style: disc;">
	    <?php 
	      if($products != null)
	      {
	    ?>
	      <li><?=$this->lang->line('due_to_following_reason_product_exist')?></li>
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
		   <?php echo $this->lang->line('product_category_delete_message') .$product_category->name."?";?>
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
			if(!($products != null))
		  {
		?>
    		<form action="<?php echo base_url('product_category/delete');?>" method="POST" name="deleteproductCategoryForm" id="deleteproductCategoryForm">
		      <input type="hidden" name="id" value="<?=$product_category->id?>">
		      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
		      <button type="submit" name="deleteproductCategorySubmit" id="deleteproductCategorySubmit" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
		    </form>
    <?php 
    	}
   	?>
    
</div>