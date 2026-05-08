<style type="text/css">
	th,td{
		padding: 4px;
 
	}
	.footer_data{
		font-size: 10px;
		background-color: #dee2e6;
	}

  table#balance_sheet th, table#balance_sheet td {
      text-align: left; /* Left-align text for balance sheet table */
  }

</style>
<table width="100%" style="text-align: center;font-size: 10px;">

	<tr>
		<td>
			<?=$company_setting->company_name?>
		</td>
	</tr>
	<tr>
		<td>
			<?=$company_setting->address_line1.', '.$company_setting->address_line2.', '.$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name.'. - '.$company_setting->pincode ?>
		</td>
	</tr>
	<tr>
		<td>
			<?= $this->lang->line('print_email').': '.$company_setting->email.', '.$this->lang->line('print_mobile').'.: '.$company_setting->mobile.'. '?>
		</td>
	</tr>
	<tr>
		<td>
			<?=$this->lang->line('header_balance_sheet')?>
		</td>
	</tr>

</table>
<!-- <table border="1" style="text-align: center;font-size: 8px" width="100%" cellpadding="0" cellspacing="0">
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
</table> -->

<table border="1"  id="balance_sheet"  style="text-align: center;font-size: 8px" width="100%" cellpadding="0" cellspacing="0">
  <thead>
    <tr>
      <th colspan="2">Current Assets</th>
      <th colspan="2">Non-Current Assets</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td width="30%">Trade Payables</td>
      <td width="20%">₹<?php echo number_format($trade_payables, 2); ?></td>
      <td width="30%">Property, Plant, and Equipment</td>
      <td width="20%">₹<?php echo number_format($property_plant_equipment, 2); ?></td>
    </tr>
    <tr>
      <td width="30%">Other Payables</td>
      <td width="20%">₹<?php echo number_format($other_payables, 2); ?></td>
      <td width="30%">Intangible Assets</td>
      <td width="20%">₹<?php echo number_format($intangible_assets, 2); ?></td>
    </tr>
    <tr>
      <td width="30%">PDC Payable</td>
      <td width="20%">₹<?php echo number_format($pdc_payable, 2); ?></td>
      <td width="30%">Investments</td>
      <td width="20%">₹<?php echo number_format($investments, 2); ?></td>
    </tr>
    <tr>
      <td width="30%">Accrued Expenses</td>
      <td width="20%">₹<?php echo number_format($accrued_expenses, 2); ?></td>
      <td width="30%">Other Long-term Assets</td>
      <td width="20%">₹<?php echo number_format($other_long_term_assets, 2); ?></td>
    </tr>
    <tr>
      <td width="30%">Short-term Loans and Borrowings</td>
      <td width="20%">₹<?php echo number_format($short_term_loans, 2); ?></td>
      <td width="30%">Total Non-Current Assets</td>
      <td width="20%">₹<?php echo number_format($total_non_current_assets, 2); ?></td>
    </tr>
    <tr>
      <td width="30%">Total Current Liabilities</td>
      <td width="20%">₹<?php echo number_format($total_current_liabilities, 2); ?></td>
      <td width="30%"></td>
      <td width="20%"></td>
    </tr>
    <tr>
      <td width="30%">Total Assets</td>
      <td width="20%">₹<?php echo number_format($total_assets, 2); ?></td>
      <td width="30%"></td>
      <td width="20%"></td>
      
    </tr>
    <tr>
      <th colspan="2">Current Liabilities</th>
      <th colspan="2">Non-Current Liabilities</th>
    </tr>
    <tr>
      <td width="30%">Trade Payables</td>
      <td width="20%">₹<?php echo number_format($trade_payables, 2); ?></td>
      <td width="30%">Long-term Loans and Borrowings</td>
      <td width="20%">₹<?php echo number_format($long_term_loans, 2); ?></td>
      
    </tr>

    <tr>
      <td width="30%">Other Payables</td>
      <td width="20%">₹<?php echo number_format($other_payables, 2); ?></td>
      <td width="30%">Deferred Tax Liabilities</td>
      <td width="20%">₹<?php echo number_format($deferred_tax_liabilities, 2); ?></td>
      
    </tr>

    <tr>
      <td width="30%">PDC Payable</td>
      <td width="20%">₹<?php echo number_format($pdc_payable, 2); ?></td>
      <td width="30%">Other Long-term Liabilities</td>
      <td width="20%">₹<?php echo number_format($other_long_term_liabilities, 2); ?></td>
      
    </tr>

    <tr>
      <td width="30%">Accrued Expenses</td>
      <td width="20%">₹<?php echo number_format($accrued_expenses, 2); ?></td>
      <td width="30%">Total Non-Current Liabilities</td>
      <td width="20%">₹<?php echo number_format($total_non_current_liabilities, 2); ?></td>
      
    </tr>

    <tr>
      <td width="30%">Short-term Loans and Borrowings</td>
      <td width="20%">₹<?php echo number_format($short_term_loans, 2); ?></td>
      <td width="30%">Total Liabilities</td>
      <td width="20%">₹<?php echo number_format($total_liabilities, 2); ?></td>
      
    </tr>

    <tr>
      <td width="30%">Total Current Liabilities</td>
      <td width="20%">₹<?php echo number_format($total_current_liabilities, 2); ?></td>
      <td width="30%"></td>
      <td width="20%"></td>
      
    </tr>

    <tr>
      <th colspan="2">Shareholder's Equity</th>
      <th colspan="2"></th>
    </tr>
    <tr>
      <td width="30%">Share Capital</td>
      <td width="20%">₹<?php echo number_format($share_capital, 2); ?></td>
      <td></td>
      <td></td>
    </tr>

    <tr>
      <td width="30%">Retained Earnings</td>
      <td width="20%">₹<?php echo number_format($retained_earnings, 2); ?></td>
      <td></td>
      <td></td>
    </tr>

    <tr>
      <td width="30%">Additional Paid-in Capital</td>
      <td width="20%">₹<?php echo number_format($additional_paid_in_capital, 2); ?></td>
      <td></td>
      <td></td>
    </tr>

    <tr>
      <td width="30%">Other Reserves</td>
      <td width="20%">₹<?php echo number_format($other_reserves, 2); ?></td>
      <td></td>
      <td></td>
    </tr>

    <tr>
      <td width="30%">Total Shareholder's Equity</td>
      <td width="20%">₹<?php echo number_format($total_equity, 2); ?></td>
      <td></td>
      <td></td>
    </tr>

    <tr>
      <td width="30%">Total Liabilities and Shareholder's Equity</td>
      <td width="20%">₹<?php echo number_format($total_liabilities_equity, 2); ?></td>
      <td></td>
      <td></td>
    </tr>

    <tr>
      <th colspan="4">The acoompanying notes are an integral part of this statement.</th>
    </tr>
    


  </tbody>
  
</table>
