<div class="row">
	<div class="col-md-12">
		<table width="100%" class="table table-bordered">
			<tr class="bg-light">
				<td width="20%"><b><?=$ledger_detail->title?></b></td>
				<td width="20%">Opening Balance</td>
				<td width="20%"><b><?=$ledger_detail->title?></b></td>
				<td width="10%" align="right"><?=$this->lang->line('from_date')?></td>
				<td width="10%" align="left"><strong><?=$from_date?></strong></td>
				<td width="10%" align="right"><?=$this->lang->line('to_date')?></td>
				<td width="10%" align="left"><strong><?=$to_date?></strong></td>
			</tr>
		</table>
	</div>
</div>
<div class="row">
	<div class="col-md-12">
		<table width="100%" class="table table-bordered">
			<tr style="font-size:18px;font-weight: bolder" class="bg-secondary disabled">
				<td width="20%">Date</td>
				<td width="40%">Particulars</td>
				<td width="20%">Dr. Amount</td>
				<td width="20%">Cr. Amount</td>
			</tr>
			<?php 
				$total_cr_amount = 0;
				$total_dr_amount = 0;

				foreach ($transaction_details as $value) {

					$total_cr_amount += $value->cr_amount;
					$total_dr_amount += $value->dr_amount;

					if($value->transaction_module == EXPENSE_MODULE)
					{
						$total_dr_amount += $value->cr_amount;
						$total_cr_amount += $value->dr_amount;
			?>
						<tr>
							<td><?=date('d-m-Y',strtotime($value->voucher_date))?></td>
							<td>
								<?php 
										$expense 					= $this->expense_model->get_single_record($value->entry_id);
										$expense_category = $this->expense_category_model->get_single_record($expense->expense_category_id);
										$ledger 					= $this->ledger_model->get_single_record($expense_category->ledger_id);

										echo $ledger->title.' - '.$ledger->group_title;	
								?>
							</td>
							<td><?=$value->dr_amount?></td>
							<td><?="0.000"?></td>
						</tr>
			<?php
					}
					else if($value->transaction_module == SALE_MODULE)
					{
						$total_cr_amount += $value->dr_amount;
						$total_dr_amount += $value->cr_amount;
			?>
						<tr>
							<td><?=date('d-m-Y',strtotime($value->voucher_date))?></td>
							<td>
								<?php
									$ledger 					= $this->ledger_model->get_single_record(SALE_LEDGER);
									echo $ledger->title.' - '.$ledger->group_title;	
								?>
							</td>
							<td><?="0.000"?></td>
							<td><?=$value->cr_amount?></td>
						</tr>
			<?php
					}
					else if($value->transaction_module == BANK_MODULE)
					{
			?>
						<tr>
							<td><?=date('d-m-Y',strtotime($value->voucher_date))?></td>
							<td>
								<?php
									$ledger 					= $this->ledger_model->get_single_record(SALE_LEDGER);
									echo $ledger->title.' - '.$ledger->group_title;	
								?>
							</td>
							<td><?="0.000"?></td>
							<td><?=$value->cr_amount?></td>
						</tr>
			<?php			
					}
			?>
			<tr>
				<td><?=date('d-m-Y',strtotime($value->voucher_date))?></td>
				<td>
					<?php 
							if($value->voucher_type == 'D')
							{
								$ledger 	= $this->ledger_model->get_single_record($value->from_account);
								echo $ledger->title.' - '.$ledger->group_title;	
							}
							else
							{	
								if($value->transaction_type == PAYMENT_TRANSACTION_TYPE && $value->transaction_module == EXPENSE_MODULE && $value->to_account == '')
								{
									$expense 					= $this->expense_model->get_single_record($value->entry_id);
									$expense_category = $this->expense_category_model->get_single_record($expense->expense_category_id);
									$ledger 					= $this->ledger_model->get_single_record($expense_category->ledger_id);
									echo $ledger->title.' - '.$ledger->group_title;			
								}
								else
								{
									$ledger 	= $this->ledger_model->get_single_record($value->to_account);
									echo $ledger->title.' - '.$ledger->group_title;			
								}
								
							}
					?>
				</td>
				<td><?=$value->cr_amount?></td>
				<td><?=$value->dr_amount?></td>
			</tr>
			<?php 
				}
			?>
			<tr style="font-size: 16px;font-weight: bold" class="bg-light">
				<td width="20%"></td>
				<td width="40%"></td>
				<td width="20%"><?=$total_cr_amount?></td>
				<td width="20%"><?=$total_dr_amount?></td>
			</tr>
		</table>
	</div>
</div>