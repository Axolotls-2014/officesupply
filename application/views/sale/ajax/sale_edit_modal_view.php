<?php
 $paid_amount = $this->transaction_model->get_total_transaction_amount($sale->id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE);
   $delivered_product_qty = $this->sale_delivery_model->get_total_no_of_quantity_delivered($sale->id);
?>


<div class="modal-header  warning-header">
  <h4 class="modal-title">
     <?php echo $this->lang->line('sale_edit');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
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
</div>
<div class="modal-footer">
    
 	<button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>
    
</div>