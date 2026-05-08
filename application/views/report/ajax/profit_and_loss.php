<div class="row">
	<div class="col-md-12">
		<table width="100%" class="table table-bordered">
			<tr>
				<td width="90%" align="right">Year Ending</td>
				<td width="10%" align="right"><strong><?=$year_ending?></strong></td>
			</tr>
		</table>
	</div>
</div>
<?php 
	$sale[$year_ending] = 0;
	$sale[$year_ending-1] = 0;

	$purchase_return[$year_ending] = 0;
	$purchase_return[$year_ending-1] = 0;

	foreach ($transactions as $value) 
	{
		if($value->module == SALE_MODULE && $value->type == SALE_TRANSACTION_TYPE && (int)$value->year == (int)$year_ending)
		{
			$sale[$year_ending] += $value->amount; 
		}
		else if($value->module == SALE_MODULE && $value->type == SALE_TRANSACTION_TYPE && (int)$value->year == (int)$year_ending-1)
		{
			$sale[$year_ending-1] += $value->amount; 	
		}

		if($value->module == PURCHASE_RETURN_MODULE && $value->type == PURCHASE_RETURN_TRANSACTION_TYPE && (int)$value->year == (int)$year_ending)
		{
			$purchase_return[$year_ending] += $value->amount; 
		}
		else if($value->module == PURCHASE_RETURN_MODULE && $value->type == PURCHASE_RETURN_TRANSACTION_TYPE && (int)$value->year == (int)$year_ending-1)
		{
			$purchase_return[$year_ending-1] += $value->amount; 	
		}
	}


?>
<div class="row">
	<div class="col-md-12">
		<table width="100%" class="table table-bordered">
			<tr style="font-size:18px;font-weight: bolder" class="bg-secondary disabled">
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
			<tr>
				<td width="20%"></td>
				<td width="40%">
					<?php 
						$purchase_return_ledger = $this->ledger_model->get_single_record(PURCHASE_RETURN_LEDGER);
						echo $purchase_return_ledger->title;
					?>
				</td>
				<td width="20%"><?=number_format_i($purchase_return[$year_ending-1])?></td>
				<td width="20%"><?=number_format_i($purchase_return[$year_ending])?></td>
			</tr>
			<tr style="font-size: 16px;font-weight: bold" class="bg-light">
				<td width="60%" colspan="2">Total Revenues</td>
				<td width="20%"><?=number_format_i($sale[$year_ending-1] + $purchase_return[$year_ending-1])?></td>
				<td width="20%"><?=number_format_i($sale[$year_ending] + $purchase_return[$year_ending])?></td>
			</tr>
		</table>
	</div>
</div>

<?php 

	$expense_arr_curr 			= array();
	$expense_arr_prev 			= array();

	$expense_ledgers 	= $this->ledger_model->get_records_by_account_group_category(array("Expense"));

	foreach ($expense_ledgers as $value) {
		$expense_arr_curr[$value->id] = 0;
	}

	foreach ($expense_ledgers as $value) {
		$expense_arr_prev[$value->id] = 0;
	}

	foreach ($transactions as $value) 
	{
		if($value->module == EXPENSE_MODULE && $value->type == EXPENSE_TRANSACTION_TYPE && (int)$value->year == (int)$year_ending)
		{
			$expense 					= $this->expense_model->get_single_record($value->entry_id);
			$expense_category = $this->expense_category_model->get_single_record($expense->expense_category_id);

			$expense_arr_curr[$expense_category->ledger_id] += $value->amount; 

		}
		else if($value->module == EXPENSE_MODULE && $value->type == EXPENSE_TRANSACTION_TYPE && (int)$value->year == (int)$year_ending-1)
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
<div class="row">
	<div class="col-md-12">
		<table width="100%" class="table table-bordered">
			<tr style="font-size:18px;font-weight: bolder" class="bg-secondary disabled">
				<td width="60%" colspan="2">Expenses & Purchases</td>
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
			<tr style="font-size: 16px;font-weight: bold" class="bg-light">
				<td width="60%" colspan="2">Total Expenses</td>
				<td width="20%"><?=number_format_i($total_expense_prev)?></td>
				<td width="20%"><?=number_format_i($total_expense_curr)?></td>
			</tr>
		</table>
		<table width="100%" class="table table-bordered">
			<tr style="font-size: 18px;font-weight: bolder" class="bg-secondary">
				<td width="60%" colspan="2">Net Profit / Loss</td>
				<td width="20%"><?=number_format_i($sale[$year_ending-1]+$purchase_return[$year_ending-1]-$total_expense_prev)?></td>
				<td width="20%"><?=number_format_i($sale[$year_ending]+$purchase_return[$year_ending]-$total_expense_curr)?></td>
			</tr>
		</table>
	</div>
</div>