<table width="100%" style="background-color: 	#fdf8ed;" border="1">
  <thead>
    <tr>
    	<th style="width: 10px">#</th>
    	<th>Invoice No.</th>
    	<th>Due Date</th>
      <th>Due days</th>
      <th>Total Invoice Amount</th>
    	<th>Paid Amount</th>
    	<th>Pending Amount</th>
    </tr>
	</thead>
	<tbody>
		<?php
			$i = 1;

      $total_invoice_amount = 0;
			$total_pending_amount = 0;
      $total_paid_amount    = 0;

      $today = new DateTime(); // Current date
			
			foreach ($sales as $value) 
      {	

      	$paid_amount = $this->transaction_model->get_total_transaction_amount($value->id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE)+ $this->transaction_model->get_total_transaction_amount($value->id,SALE_MODULE,CREDIT_TRANSACTION_TYPE);
        $pending_amount = $value->total-$paid_amount;

        
        $dueDate 			= new DateTime($value->due_date);
        
        $interval 		= $today->diff($dueDate);
        $no_of_days 	= $interval->days;

        if ($dueDate < $today) 
          $no_of_days *= -1;

        if($pending_amount >= 0.99 && $no_of_days < 0)
        { 

          $total_invoice_amount   += $value->total;
          $total_paid_amount      += $paid_amount;
          $total_pending_amount   += $pending_amount;

		?>
		<tr>
			<td><?=$i++?></td>
      <td>
        <a href="<?=base_url('sale/view/'.base64_encode($value->id))?>" target="_blank">
          <?=$value->reference_no?>  
        </a>
      </td>
      <td><?=date('d-m-Y',strtotime($value->due_date))?></td>
      <td><?=$no_of_days?></td>
			<td><?=number_format_i($value->total);?></td>
			<td><?=number_format_i($paid_amount);?></td>
			<td><?=number_format_i($pending_amount);?></td>
		</tr>
		<?php
        }
			}
		?>

    <tr style="font-size: 16px;font-weight: bolder;">
      <td colspan="4"></td>
      <td>
        <?php 
          echo number_format_i($total_invoice_amount);
        ?>
      </td>

      <td>
        <?php 
          echo number_format_i($total_paid_amount);
        ?>
      </td>

      <td>
        <?php 
          echo number_format_i($total_pending_amount);
        ?>
      </td>

    </tr>


	</tbody>
</table>
