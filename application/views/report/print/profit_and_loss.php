		<style type="text/css">
			td{
				padding:4px;
			}
		  .footer_data{
		    font-size: 10px;
		    background-color: #dee2e6;
		  }
		</style>
		<table width="100%" style="text-align: center;font-size: 10px;" cellspacing="0" cellpadding="0">
			<tr>
				<td>
					<?=$company_setting->company_name?>
				</td>
			</tr>
			<tr>
				<td>
					<?=$company_setting->address_line1.', '.$company_setting->address_line2.', '.$company_setting->city_name.', '.$company_setting->state_name.', state_name'.$company_setting->country_name.'. - '.$company_setting->pincode ?>
				</td>
			</tr>
			<tr>
				<td>
					<?= $this->lang->line('print_email').': '.$company_setting->email.', '.$this->lang->line('print_mobile').'.: '.$company_setting->mobile.'. '?>
				</td>
			</tr>
			<tr>
				<td>
					<?=$this->lang->line('profit_and_loss_report')?>
				</td>
			</tr>
			<tr>
				<td>
					<?=($year_ending-1).' - '.$year_ending?>
				</td>
			</tr>
		</table>
		<br/>
		<table width="100%" border="1" style="font-size: 12px" cellspacing="0" cellpadding="0">
			<tr>
				<td width="90%" align="right">Year Ending</td>
				<td width="10%" align="right"><strong><?=$year_ending?></strong></td>
			</tr>
		</table>
		<br/>
<?php 
	$sale[$year_ending] = 0;
	$sale[$year_ending-1] = 0;

	foreach ($transactions as $value) 
	{
		if($value->module == SALE_MODULE && $value->type == SALE_TRANSACTION_TYPE && $value->year == $year_ending)
		{
			$sale[$year_ending] += $value->amount; 
		}
		else if($value->module == SALE_MODULE && $value->type == SALE_TRANSACTION_TYPE && $value->year == $year_ending-1)
		{
			$sale[$year_ending-1] += $value->amount; 	
		}

	}
?>

		<table width="100%" border="1" style="font-size: 12px;" cellspacing="0" cellpadding="0"> 
			<tr style="font-weight: bolder">
				<td width="60%" colspan="2">Revenue</td>
				<td width="20%"><?=$year_ending-1?></td>
				<td width="20%"><?=$year_ending?></td>
			</tr>
			<tr>
				<td width="20%"></td>
				<td width="40%">
					<?php 
						$sale_ledger = $this->ledger_model->get_single_record(SALE_LEDGER);
						echo $sale_ledger->title;
					?>
				</td>
				<td width="20%"><?=number_format_i($sale[$year_ending-1])?></td>
				<td width="20%"><?=number_format_i($sale[$year_ending])?></td>
			</tr>
			<tr style="font-size: 12px;font-weight: bold">
				<td width="60%" colspan="2">Total Revenues</td>
				<td width="20%"><?=number_format_i($sale[$year_ending-1])?></td>
				<td width="20%"><?=number_format_i($sale[$year_ending])?></td>
			</tr>
		</table>
		<br/>

<?php 

	$expense_arr_curr 			= array();
	$expense_arr_prev 			= array();

	$expense_ledgers 	= $this->ledger_model->get_records_by_account_group_category(array('Expense'));

	foreach ($expense_ledgers as $value) {
		$expense_arr_curr[$value->id] = 0;
	}

	foreach ($expense_ledgers as $value) {
		$expense_arr_prev[$value->id] = 0;
	}


	foreach ($transactions as $value) 
	{
		if($value->module == EXPENSE_MODULE && $value->type == EXPENSE_TRANSACTION_TYPE && $value->year == $year_ending)
		{
			$expense 					= $this->expense_model->get_single_record($value->entry_id);
			$expense_category = $this->expense_category_model->get_single_record($expense->expense_category_id);

			$expense_arr_curr[$expense_category->ledger_id] += $value->amount; 

		}
		else if($value->module == EXPENSE_MODULE && $value->type == EXPENSE_TRANSACTION_TYPE && $value->year == $year_ending-1)
		{
			$expense 					= $this->expense_model->get_single_record($value->entry_id);
			$expense_category = $this->expense_category_model->get_single_record($expense->expense_category_id);

			$expense_arr_prev[$expense_category->ledger_id] += $value->amount; 			
		}
		else if($value->module == PURCHASE_MODULE && $value->type == PURCHASE_TRANSACTION_TYPE && (int)$value->year == (int)$year_ending)
		{
			$expense_arr_curr[PURCHASE_LEDGER] += $value->amount;
		}
		else if($value->module == PURCHASE_MODULE && $value->type == PURCHASE_TRANSACTION_TYPE  && (int)$value->year == (int)$year_ending-1)
		{
			$expense_arr_prev[PURCHASE_LEDGER] += $value->amount;
		}
	}
?>
		<table width="100%" border="1" style="font-size: 12px;" cellspacing="0" cellpadding="0">
			<tr style="font-weight: bolder">
				<td width="60%" colspan="2">Expenses</td>
				<td width="20%"><?=$year_ending-1?></td>
				<td width="20%"><?=$year_ending?></td>
			</tr>
			<?php 
				$total_expense_curr = 0;
				$total_expense_prev = 0;
				foreach ($expense_arr_curr as $key => $value) {
					$total_expense_curr += $expense_arr_curr[$key];
					$total_expense_prev += $expense_arr_prev[$key];
			?>
			<tr>
				<td width="20%"></td>
				<td width="40%">
					<?php
						$ledger = $this->ledger_model->get_single_record($key);
						echo $ledger->title;
					?>
				</td>
				<td width="20%"><?=number_format_i($expense_arr_prev[$key])?></td>
				<td width="20%"><?=number_format_i($expense_arr_curr[$key])?></td>
			</tr>
			<?php 
				}
			?>
			<tr style="font-size: 12px;font-weight: bold">
				<td width="60%" colspan="2">Total Expenses</td>
				<td width="20%"><?=number_format_i($total_expense_prev)?></td>
				<td width="20%"><?=number_format_i($total_expense_curr)?></td>
			</tr>
		</table>
		<br/>
		<table width="100%" border="1" cellspacing="0" cellpadding="0" style="font-size: 12px">
			<tr style="font-weight: bolder" class="footer_data">
				<td width="70%" colspan="2">Net Profit / Loss</td>
				<td width="20%"><?=number_format_i($sale[$year_ending-1]-$total_expense_prev)?></td>
				<td width="20%"><?=number_format_i($sale[$year_ending]-$total_expense_curr)?></td>
			</tr>
		</table>
	
	<script>window.print();</script>