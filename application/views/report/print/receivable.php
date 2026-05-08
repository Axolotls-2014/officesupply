<style type="text/css">
	th,td{
		padding: 3px !important;
	}
	.footer_data{
		font-size: 14px;
		background-color: #dee2e6;
	}
</style>
<table width="100%" style="text-align: center;font-size: 14px;">
	<tr>
		<td style="line-height: 3px;padding-top: 10px;">
			<h3><?=$company_setting->company_name?></h3>
			<p><?=$company_setting->address_line1.', '.$company_setting->address_line2.', '.$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name.'. - '.$company_setting->pincode ?></p>
		</td>
	</tr>
	<tr>
		<td>
			<?= $this->lang->line('print_email').': '.$company_setting->email.', '.$this->lang->line('print_mobile').'.: '.$company_setting->mobile.'. '?>
		</td>
	</tr>
	<tr>
		<td>
			<?=$this->lang->line('header_receivable')?>
		</td>
	</tr>
	
</table>
<table border="1" style="text-align: center;font-size: 14px" width="100%" cellpadding="0" cellspacing="0">
		<thead>
    <tr>
      <th><?=$this->lang->line('customer_name')?></th>
      <th><?=$this->lang->line('customer_gstin')?></th>
      <th><?=$this->lang->line('customer_email')?></th>
      <th><?=$this->lang->line('customer_phone')?></th>
      <th><?=$this->lang->line('customer_country_id')?></th>
      <th><?=$this->lang->line('customer_state_id')?></th>
      <th><?=$this->lang->line('customer_invoice_amount')?></th>
      <th><?=$this->lang->line('customer_paid_amount')?></th>
      <th><?=$this->lang->line('customer_receivable')?></th>
    </tr>
  </thead>
  
  <tbody>
  	<?php 

  		$total_total_amount = 0;
  		$total_paid_amount  = 0;
  		$total_receivable_amount  = 0;

  		foreach ($customers as $row) {

  			$total_amount = $this->transaction_model->get_total_transaction_amount_by_customer($row->id, SALE_MODULE, SALE_TRANSACTION_TYPE);
				$paid_amount = $this->transaction_model->get_total_transaction_amount_by_customer($row->id, SALE_MODULE, RECEIPT_TRANSACTION_TYPE)+ $this->transaction_model->get_total_transaction_amount($row->id,SALE_MODULE,CREDIT_TRANSACTION_TYPE);

				$total_total_amount += $total_amount;
				$total_paid_amount += $paid_amount;

				$total_receivable_amount += (($total_amount-$paid_amount));
				$receivable_amount = $total_amount-$paid_amount;
				if($receivable_amount > 0)
				{

  	?>
  			<tr>
  				<td><?=$row->customer_name?></td>
	        <td><?=$row->gstin?></td>
	        <td><?=$row->email?></td>
	        <td><?=$row->phone?></td>
	        <td><?=$row->country_name?></td>
	        <td><?=$row->state_name?></td>
	        <td><?=($total_amount) ? number_format_i($total_amount) : "0.00"?></td>
	        <td><?=($paid_amount) ? number_format_i($paid_amount) : "0.00"?></td>
	        <td><?=number_format_i(($total_amount-$paid_amount))?></td>
  			</tr>
  	<?php
				}
  		}
  	?>
  </tbody>
  <tfoot>
    <tr>
      <th colspan="6"><?=$this->lang->line('total')?></th>
      <th><?=number_format_i($total_total_amount)?></th>
      <th><?=number_format_i($total_paid_amount)?></th>
      <th><?=number_format_i($total_receivable_amount)?></th>
    </tr>
  </tfoot>
</table>
<script>window.print();</script>