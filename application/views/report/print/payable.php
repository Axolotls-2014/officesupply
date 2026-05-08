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
			<p><?=$company_setting->address_line1.', '.$company_setting->address_line2.', '.$company_setting->city_name.', '.$company_setting->state_name.', state_name'.$company_setting->country_name.'. - '.$company_setting->pincode ?></p>
		</td>
	</tr>
	<tr>
		<td>
			<?= $this->lang->line('print_email').': '.$company_setting->email.', '.$this->lang->line('print_mobile').'.: '.$company_setting->mobile.'. '?>
		</td>
	</tr>
	<tr>
		<td>
			<?=$this->lang->line('header_payable')?>
		</td>
	</tr>
	
</table>
<table border="1" style="text-align: center;font-size: 14px;" width="100%" cellpadding="0" cellspacing="0">
	<thead>
    <tr>
      <th><?=$this->lang->line('supplier_company_name')?></th>
      
      <th><?=$this->lang->line('supplier_gstin')?></th>
      <th><?=$this->lang->line('supplier_email')?></th>
      <th><?=$this->lang->line('supplier_phone')?></th>
      <th><?=$this->lang->line('supplier_contact_person_name')?></th>
      <th><?=$this->lang->line('customer_invoice_amount')?></th>
      <th><?=$this->lang->line('customer_paid_amount')?></th>
      <th><?=$this->lang->line('supplier_payable')?></th>
    
    </tr>
  </thead>
  <tbody>
  	<?php

    	$total_total_amount = 0;
  		$total_paid_amount  = 0;
  		$total_payable_amount  = 0;

  		foreach ($suppliers as $item) {

  				$total_amount = $this->transaction_model->get_total_transaction_amount_by_supplier($item->id, PURCHASE_MODULE, PURCHASE_TRANSACTION_TYPE);
					$paid_amount = $this->transaction_model->get_total_transaction_amount_by_supplier($item->id, PURCHASE_MODULE, PAYMENT_TRANSACTION_TYPE);

					$total_total_amount += $total_amount;
					$total_paid_amount += $paid_amount;

					$total_payable_amount += (($total_amount-$paid_amount));

  	?>
  		<tr>
  			<td><?=$item->company_name;?></td>
	      
	      <td><?=$item->gstin;?></td>
	      <td><?=$item->email;?></td>
	      <td><?=$item->phone;?></td>
	      <td><?=$item->contact_person_name;?></td>
	      
	      <td><?=($total_amount) ? number_format_i($total_amount) : "0.00"?></td>
        <td><?=($paid_amount) ? number_format_i($paid_amount) : "0.00"?></td>
	      <td><?=number_format_i($total_amount-$paid_amount);?></td>
  		</tr>
  	<?php
  		}
  	?>
  </tbody>
  <tfoot>
  	<tr>
  		<th colspan="5">Total</th>
  		<th><?=number_format_i($total_total_amount)?></th>
      <th><?=number_format_i($total_paid_amount)?></th>
      <th><?=number_format_i($total_payable_amount)?></th>
  	</tr>
  </tfoot>
</table>
<script>window.print();</script>