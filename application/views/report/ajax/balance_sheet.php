<div class="row">
	<div class="col-md-12">
		<table class="table table-bordered" width="100%">
			<tr>
				<td width="90%" align="right">Financial Year</td>
				<td width="10%" align="right"><strong><?=($year_ending-1).' - '.$year_ending?></strong></td>
			</tr>
		</table>

		<table class="table table-bordered" width="100%">
			<tr>
				<th width="35%">Liabilities</th>
				<th width="15%" align="center">Amount (<?=$this->session->userdata('currency_symbol')?>)</th>
				<th width="35%">Assets</th>
				<th width="15%" align="center">Amount (<?=$this->session->userdata('currency_symbol')?>)</th>
			</tr>
			<tr>
				<td>
					<strong><u>Capital Account / Equity</u></strong>
				</td>
				<td>
					<?=number_format($bl_sheet['capital_account_opening_balance']-$bl_sheet['total_decrement']+$bl_sheet['total_increment']+$bl_sheet['total_profit_loss'],3)?>
				</td>
				<td>
					<strong><u>Current Assets, Loans & Advances</u></strong>
				</td>
				<td>
					<?=number_format($bl_sheet['current_assets'],3)?>
				</td>
			</tr>
			<tr>
				<td>
					Opening Balance 
					<table width="100%" class="mt-2">
						<?php 
							$ob_bank_ledgers = $this->ledger_model->get_records_by_account_group_id(BANK_ACCOUNT_GROUP_ID);

							foreach ($ob_bank_ledgers as $value) 
							{
						?>
						<tr>
							<td width="70%" align="left" class="p-1 m-1 pl-4">
								<?=$value->title?>								
							</td>
							<td width="30%" align="right" class="p-1 m-1">
								<?=number_format($value->opening_balance,3)?>
							</td>	
						</tr>
						<?php
							} 
						?>

						<tr>
							<td width="70%" align="left" class="p-1 m-1 pl-4">
								<?php
									if($bl_sheet['total_profit_loss'] >= 0)
										echo 'PROFIT';
									else
										echo 'LOSS';
								?>		
							</td>
							<td width="30%" align="right" class="p-1 m-1">
								<?php
									echo $bl_sheet['total_profit_loss'];
								?>			
							</td>
						</tr>
					</table>
				</td>
				<td>
				</td>
				<td>
					Closing Stock / inventrory on hand 

					<table width="100%" class="mt-2">
						
						<tr>
							<td width="70%" align="left" class="p-1 m-1 pl-4">
								<?="INVENTORY ON HAND"?>								
							</td>
							<td width="30%" align="right" class="p-1 m-1">
								<?=number_format($bl_sheet['closing_stock'],3)?>
							</td>	
						</tr>
					</table>

				</td>
				<td>
					
				</td>
			</tr>
			<tr>
				<td>
					Owner's Equity
					<table width="100%" class="mt-2">
						<?php 
							$ob_bank_ledgers = $this->ledger_model->get_records_by_account_group_id(BANK_ACCOUNT_GROUP_ID);
							$total_increment = 0;
							$total_decrement = 0;

							foreach ($ob_bank_ledgers as $value) 
							{
								$temp_balance = $value->opening_balance;
						?>
						<tr>
							<td width="70%" align="left" class="p-1 m-1 pl-4">
								<?=$value->title?>
								<table width="100%" class="mt-2" border="0">	
									<?php
										$ob_bank_ledgers_transaction = $this->transaction_model->get_transaction_by_ledger_id_and_transaction_type($value->id,array(REDUCE_ADJUSTMENT_TRANSACTION_TYPE,INCREASE_ADJUSTMENT_TRANSACTION_TYPE));

										foreach ($ob_bank_ledgers_transaction as $ledger_trans) 
										{	
											if($ledger_trans->transaction_type == REDUCE_ADJUSTMENT_TRANSACTION_TYPE)
											{
												$temp_balance -= $ledger_trans->transaction_amount;
												$total_decrement += $ledger_trans->transaction_amount;
											}
											else if ($ledger_trans->transaction_type == INCREASE_ADJUSTMENT_TRANSACTION_TYPE) 
											{
												$temp_balance += $ledger_trans->transaction_amount;
												$total_increment += $ledger_trans->transaction_amount;
											}
									?>								
									<tr>
										<td width="70%" align="left" class="p-1 m-1 pl-4 <?=($ledger_trans->transaction_type == REDUCE_ADJUSTMENT_TRANSACTION_TYPE) ? 'text-danger' : 'text-success'?>">
											<?=($ledger_trans->transaction_type == REDUCE_ADJUSTMENT_TRANSACTION_TYPE) ? ' - REDUCE' : ' + INCREASE'?>
										</td>
										<td width="30%" align="right" class="p-1 m-1">
											<?=number_format($ledger_trans->transaction_amount,3)?>
										</td>
									</tr>
									<?php
										} 
									?>
								</table>
							</td>
							<td width="30%" align="right" class="p-1 m-1">
								<?=number_format($temp_balance,3)?>
							</td>	
						</tr>
						<?php
							} 
						?>
					</table>
				</td>
				<td></td>
				<td>
					Sundry Debtors / Accounts Receivable
					<table width="100%" class="mt-2">
						<?php 
							$customer_ledgers = $this->ledger_model->get_records_by_account_group_id(CUSTOMER_ACCOUNT_GROUP_ID);

							foreach ($customer_ledgers as $value) 
							{
						?>
						<tr>
							<td width="70%" align="left" class="p-1 m-1 pl-4">
								<?=$value->title?>								
							</td>
							<td width="30%" align="right" class="p-1 m-1">
								<?=number_format($value->closing_balance,3)?>
							</td>	
						</tr>
						<?php
							} 
						?>
					</table>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<strong><u> Current Liabilities & Provisions</u></strong>
				</td>
				<td>
					<?=number_format($bl_sheet['current_liability_and_provision'],3)?>
				</td>
				<td>
					<strong><u> Other Current Assets </u></strong>
				</td>
				<td>
					<?=number_format($bl_sheet['other_current_assets'],3)?>
				</td>
			</tr>
			<tr>
				<td>
					Sundry Creditors / Accounts Payable 
				</td>
				<td></td>
				<td>
					<table width="100%">
						<tr>
							<td width="70%" align="left" class="p-1 m-1 pl-4">
								Tax Receivable
							</td>
							<td width="30%" align="right" class="p-1 m-1">
								<?=number_format($bl_sheet['tax_receivable'],3)?>
							</td>	
						</tr>
						<tr>
							<td width="70%" align="left" class="p-1 m-1 pl-4">
								TDS Receivable 			
							</td>
							<td width="30%" align="right" class="p-1 m-1">
								<?=number_format($bl_sheet['tds_receivable'],3)?>
							</td>	
						</tr>
					</table>
					
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
					<strong><u>  Tax Payable </u></strong>
				</td>
				<td>
					<?=number_format($bl_sheet['tax_payable'],3)?>
				</td>
				<td>
					<strong><u> Cash & Bank Balances </u></strong>
				</td>
				<td>
					<?=number_format($bl_sheet['cash_bank_closing_balance'],3)?>
				</td>
			</tr>
			<tr>
				<td>
				 	Tax on sale 
				</td>
				<td></td>
				<td>
					Cash in Hand 
					<table width="100%" class="mt-2">
						<tr>
							<td width="70%" align="left" class="p-1 m-1 pl-4">
								<?=$this->ledger_model->get_single_record(CASH_GROUP_LEDGER)->title?>								
							</td>
							<td width="30%" align="right" class="p-1 m-1">
								<?=number_format($bl_sheet['cash_closing_balance'],3)?>
							</td>	
						</tr>
					</table>
				</td>
				<td></td>
			</tr>
			<tr>
				<td>
				 	
				</td>
				<td></td>
				<td>
					Cash at Bank  
					<table width="100%" class="mt-2">
						<?php 
							$ob_bank_ledgers = $this->ledger_model->get_records_by_account_group_id(BANK_ACCOUNT_GROUP_ID);

							foreach ($ob_bank_ledgers as $value) 
							{
						?>
						<tr>
							<td width="70%" align="left" class="p-1 m-1 pl-4">
								<?=$value->title?>								
							</td>
							<td width="30%" align="right" class="p-1 m-1">
								<?=number_format($value->closing_balance,3)?>
							</td>	
						</tr>
						<?php
							} 
						?>
					</table>
				</td>
				<td></td>
			</tr>
			<tr>
				<td align="right">
				 	<strong>Total</strong>
				</td>
				<td>
					<?=number_format(($bl_sheet['total_liability']-$total_decrement+$total_increment),3)?>
				</td>
				<td align="right">
					<strong>Total</strong>  
				</td>
				<td>
					<?=number_format($bl_sheet['total_assets'],3)?>
				</td>
			</tr>
		</table>
	</div>
</div>

