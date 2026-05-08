<?php
  $paid_amount = $this->transaction_model->get_total_transaction_amount($expense->id, EXPENSE_MODULE, PAYMENT_TRANSACTION_TYPE);
  ($paid_amount == '') ? $paid_amount = 0 : $paid_amount = $paid_amount;
?>


<div class="modal-header  warning-header">
  <h4 class="modal-title">
     <?php echo $this->lang->line('expense_delete');?>
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