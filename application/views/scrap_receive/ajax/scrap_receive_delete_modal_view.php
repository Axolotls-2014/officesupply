<div class="modal-header failure-header">
  <h4 class="modal-title">
     <?php echo $this->lang->line('scrap_receive_delete');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
		<p>
		 	 <?php echo "Are you sure want to delete this Scrap receive with Reference No : ".$scrap_receive->reference_no."?";?>
		</p>
</div>
<div class="modal-footer">
    
    <button type="button" class="btn btn-default" data-dismiss="modal">
      <?php echo $this->lang->line('btn_modal_close');?>
    </button>
		<form action="<?php echo base_url('scrap_receive/delete');?>" method="POST" name="deleteScrapreceiveForm" id="deleteScrapreceiveForm">
      <input type="hidden" name="id" value="<?=$scrap_receive->id?>">
      <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
      <button type="submit"  name="deleteScrapreceiveButton" id="deleteScrapreceiveButton" class="btn btn-danger" value=""><?php echo $this->lang->line('btn_modal_delete');?></button>
    </form>
    
</div>