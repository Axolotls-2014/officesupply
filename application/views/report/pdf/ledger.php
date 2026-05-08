<style type="text/css">
	td{
		padding: 4px;
	}
    .footer_data{
        font-size: 10px;
        background-color: #dee2e6;
    }
</style>

<!-- Company Details -->
<table width="100%" style="text-align: center;font-size: 10px;">
	<tr>
		<td><?=$company_setting->company_name?></td>
	</tr>
	<tr>
		<td><?=$company_setting->address_line1.', '.$company_setting->address_line2.', '.$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name.'. - '.$company_setting->pincode ?></td>
	</tr>
	<tr>
		<td><?= $this->lang->line('print_email').': '.$company_setting->email.', '.$this->lang->line('print_mobile').'.: '.$company_setting->mobile.'. '?></td>
	</tr>
	<tr>
		<td><?=$this->lang->line('ledger_report')?></td>
	</tr>
	<tr>
        <td>
            <?php
                if (!empty($from_date)) {
                    echo 'From: ' . $from_date;
                    if (!empty($to_date)) {
                        echo ' - To: ' . $to_date;
                    }
                } else {
                    echo 'From: <span style="width:' . strlen('From') . 'ch">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>';
                    if (!empty($to_date)) {
                        echo ' - To: ' . $to_date;
                    }
                }
            ?>
        </td>
	</tr>
</table>

<!-- PARTY DETAILS SECTION - Shows COMPANY NAME prominently for both Customers and Suppliers -->
<?php
if(isset($ledger_detail->party_type) && $ledger_detail->party_data):
    $party = $ledger_detail->party_data;
?>

<?php if($ledger_detail->party_type == 'Customer'): ?>
<!-- CUSTOMER DETAILS WITH COMPANY NAME -->
<table width="100%" style="text-align: left;font-size: 10px; margin: 10px 0;" cellspacing="0" cellpadding="5" border="1">
    <tr>
        <td colspan="4" style="background-color: #f0f0f0; text-align: center; font-weight: bold;">CUSTOMER DETAILS</td>
    </tr>
    <tr>
        <td width="15%"><b>Company Name:</b></td>
        <td width="35%" style="font-weight: bold;"><?= isset($party->customer_company_name) && !empty($party->customer_company_name) ? $party->customer_company_name : 'N/A' ?></td>
        <td width="15%"><b>Contact Person:</b></td>
        <td width="35%"><?= $party->customer_name ?></td>
    </tr>
    <tr>
        <td><b>Address:</b></td>
        <td colspan="3"><?= $party->address . ', ' . $party->city_name . ', ' . $party->state_name . ' - ' . $party->pincode ?></td>
    </tr>
    <tr>
        <td><b>GSTIN:</b></td>
        <td><?= $party->gstin ?: 'N/A' ?></td>
        <td><b>Phone:</b></td>
        <td><?= $party->phone ?: 'N/A' ?></td>
    </tr>
</table>

<?php elseif($ledger_detail->party_type == 'Supplier'): ?>
<!-- SUPPLIER DETAILS WITH COMPANY NAME -->
<table width="100%" style="text-align: left;font-size: 10px; margin: 10px 0;" cellspacing="0" cellpadding="5" border="1">
    <tr>
        <td colspan="4" style="background-color: #f0f0f0; text-align: center; font-weight: bold;">SUPPLIER DETAILS</td>
    </tr>
    <tr>
        <td width="15%"><b>Company Name:</b></td>
        <td width="35%" style="font-weight: bold;"><?= isset($party->company_name) && !empty($party->company_name) ? $party->company_name : 'N/A' ?></td>
        <td width="15%"><b>Contact Person:</b></td>
        <td width="35%">
            <?php 
                // Use contact_person_name (from your database)
                echo isset($party->contact_person_name) && !empty($party->contact_person_name) ? $party->contact_person_name : 'N/A';
            ?>
        </td>
    </tr>
    <tr>
        <td><b>Address:</b></td>
        <td colspan="3">
            <?php 
                $address = isset($party->address) ? $party->address : '';
                $city = isset($party->city_name) ? $party->city_name : '';
                $state = isset($party->state_name) ? $party->state_name : '';
                $pincode = isset($party->pincode) ? $party->pincode : '';
                
                $address_parts = array_filter([$address, $city, $state, $pincode]);
                echo !empty($address_parts) ? implode(', ', $address_parts) : 'N/A';
            ?>
        </td>
    </tr>
    <tr>
        <td><b>GSTIN:</b></td>
        <td><?= isset($party->gstin) && !empty($party->gstin) ? $party->gstin : 'N/A' ?></td>
        <td><b>Phone:</b></td>
        <td><?= isset($party->phone) && !empty($party->phone) ? $party->phone : 'N/A' ?></td>
    </tr>
</table>
<?php endif; ?>
<?php endif; ?>

<br/>

<!-- Ledger Header -->
<table width="100%" border="1" style="text-align: center;font-size: 8px;" cellspacing="0" cellpadding="0">
	<tr class="bg-light">
		<td width="40%"><b><?=$ledger_detail->title?></b></td>
		<td width="15%">Opening Bal.</td>
		<td width="15%">
			<b>
				<?php
					$o_balance = 0.000;
					$c_balance = 0.000;
					$opening_balance = 0.000;

					$from_date 	= ($from_date == '') ? null : date('Y-m-d',strtotime($from_date));
					$to_date 		= ($to_date == '') ? null : date('Y-m-d',strtotime($to_date));

					if($from_date == null)
					{
						$opening_balance = $ledger_detail->opening_balance;
					}
					else
					{
						$opening_balance = $ledger_detail->opening_balance;

						$transactions = $this->transaction_model->get_transaction_by_ledger_id_for_closing_balance($ledger_detail->id,$from_date);

						if($transactions != null)
						{
							foreach ($transactions as $trans) 
							{
								if($trans->voucher_type == 'D')
								{
									$opening_balance += $trans->cr_amount;
								}
								else
								{
									$opening_balance -= $trans->dr_amount;
								}
							}	
						}
					}

					$o_balance = $opening_balance;

					echo number_format_i($opening_balance);
					
				?>
			</b>
		</td>
		<td width="15%">Closing Bal.</td>
		<td width="15%">
			<b>
				<?php 

					$from_date 	= ($from_date == '') ? null : date('Y-m-d',strtotime($from_date));
					$to_date 		= ($to_date == '') ? null : date('Y-m-d',strtotime($to_date));

					$transactions 		= $this->transaction_model->get_transaction_by_ledger_id($ledger_detail->id,$from_date, $to_date);

					if($transactions != null)
					{
						foreach ($transactions as $trans) 
						{	
							if($trans->voucher_type == 'D')
							{
								$opening_balance += $trans->cr_amount;
							}
							else
							{
								$opening_balance -= $trans->dr_amount;
							}
						}	
					}

					$c_balance = $opening_balance;
					echo number_format_i(abs($c_balance));
				?>
			</b>
		</td>
	</tr>
</table>

<br/>

<!-- Transaction Table with Cheque No Column -->
<table width="100%" border="1" style="text-align: center;font-size: 11px;" cellspacing="0" cellpadding="0">
	<tr style="font-size:11px;font-weight: bolder">
		<td width="15%">Date</td>
		<td width="35%">Particulars</td>
		<td width="10%">Cheque No.</td>
		<td width="20%"><?=$this->lang->line('dr_amount')?></td>
		<td width="20%"><?=$this->lang->line('cr_amount')?></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<u><?=strtoupper("Opening Balance")?></u>
		</td>
		<td></td>
		<td>
			<?=number_format_i($o_balance)?>
		</td>
		<td>0.000</td>
	</tr>
	
	<?php 
		$total_cr_amount = $o_balance;
		$total_dr_amount = 0.000;

		foreach ($transaction_details as $value) 
		{
			$transaction 	= $this->transaction_model->get_single_record($value->transaction_id);
			$from_account = $transaction->from_account;
			$to_account 	= $transaction->to_account;
			
			// Get cheque number from transaction header
			$cheque_no = !empty($transaction->cheque_no) ? $transaction->cheque_no : '-';
	?>	
			
			<tr>
				<td><?=date('d-m-Y',strtotime($value->transaction_date))?></td>
				<td>
					<?php
					
					  
						// YOUR EXISTING PARTICULARS CODE - KEEP IT EXACTLY AS IS
						if($transaction->module == SALE_MODULE)
						{
							$sale = $this->sale_model->get_sale_single_record($transaction->entry_id);

							if($transaction->module == SALE_MODULE && $from_account == $ledger_detail->id)
							{
								$ledger 					= $this->ledger_model->get_single_record($to_account);
								echo $ledger->title.' - '.$ledger->group_title." ";

							}
							else
							{
								$ledger = $this->ledger_model->get_single_record($from_account);
								echo $ledger->title.' - '.$ledger->group_title.' ';		
								echo ' [<a href="'.base_url('sale/view/'.$sale->id).'" target="_blank">'.$sale->reference_no.'</a>]';
							}
						}
						else if($transaction->module == PURCHASE_RETURN_MODULE)
						{
							$purchase_return = $this->purchase_return_model->get_purchase_return_single_record($transaction->entry_id);

							if($transaction->module == PURCHASE_RETURN_MODULE && $from_account == $ledger_detail->id)
							{
								$ledger 					= $this->ledger_model->get_single_record($to_account);
								echo $ledger->title.' - '.$ledger->group_title." ";

							}
							else
							{
								$ledger = $this->ledger_model->get_single_record($from_account);
								echo $ledger->title.' - '.$ledger->group_title.' ';		
								echo ' [<a href="'.base_url('purchase_return/view/'.base64_encode($purchase_return->id)).'" target="_blank">'.$purchase_return->reference_no.'</a>]';
							}
						}
						else if($transaction->module == EXPENSE_MODULE)
						{
							if($to_account != '')
							{
								if($transaction->module == EXPENSE_MODULE && $to_account == $ledger_detail->id)
								{
									if($from_account != '')
									{
										$ledger 					= $this->ledger_model->get_single_record($from_account);
										echo $ledger->title.' - '.$ledger->group_title;		
									}
									else
									{
										echo strtoupper($transaction->reference_no);
									}
								}
								else
								{
									$ledger = $this->ledger_model->get_single_record($to_account);
									echo $ledger->title.' - '.$ledger->group_title;		
								}
							}
							else
							{
								$expense 					= $this->expense_model->get_single_record($transaction->entry_id);
								$expense_category = $this->expense_category_model->get_single_record($expense->expense_category_id);
								$ledger 					= $this->ledger_model->get_single_record($expense_category->ledger_id);
								echo $ledger->title.' - '.$ledger->group_title;	
							}
						}
						else if($transaction->module == PURCHASE_MODULE)
						{
							if($transaction->module == PURCHASE_MODULE && $to_account == $ledger_detail->id)
							{
								
									$ledger 					= $this->ledger_model->get_single_record($from_account);
									echo $ledger->title.' - '.$ledger->group_title;		
								
							}
							else
							{
								$ledger = $this->ledger_model->get_single_record($to_account);
								echo $ledger->title.' - '.$ledger->group_title;		
							}
						}
						else if($transaction->module == SALE_RETURN_MODULE)
						{
							if($transaction->module == SALE_RETURN_MODULE && $to_account == $ledger_detail->id)
							{
								
									$ledger 					= $this->ledger_model->get_single_record($from_account);
									echo $ledger->title.' - '.$ledger->group_title;		
								
							}
							else
							{
								$ledger = $this->ledger_model->get_single_record($to_account);
								echo $ledger->title.' - '.$ledger->group_title;		
							}
						}
				// 		else if($transaction->module == BANK_MODULE)
				// 		{
				// 			if($transaction->module == BANK_MODULE && $to_account == $ledger_detail->id)
				// 			{
				// 				if($from_account == '')
				// 				{
				// 					echo $transaction->reference_no;
				// 				}
				// 				else
				// 				{
				// 					$ledger = $this->ledger_model->get_single_record($from_account);
				// 					echo $ledger->title.' - '.$ledger->group_title;				
				// 				}
								
				// 			}
				// 			else
				// 			{
				// 				if($to_account == '')
				// 				{
				// 					echo $transaction->reference_no;
				// 				}
				// 				else
				// 				{
				// 					$ledger = $this->ledger_model->get_single_record($to_account);
				// 					echo $ledger->title.' - '.$ledger->group_title;			
				// 				}
								
				// 			}	
				// 		}
				else if($transaction->module == 'PAYMENT_OUT' || $transaction->module == 'PAYMENT_IN' || $transaction->module == 'BANK_MODULE')
{
    // Bank is receiving money (PAYMENT_IN)
    if($to_account == $ledger_detail->id)
    {
        if($from_account != '')
        {
            $ledger = $this->ledger_model->get_single_record($from_account);
            if($ledger) {
                echo $ledger->title . ' - ' . $ledger->group_title;
            } else {
                echo 'Bank Receipt';
            }
        }
        else
        {
            echo $transaction->reference_no ?: 'Bank Receipt';
        }
    }
    // Bank is paying money (PAYMENT_OUT)
    else if($from_account == $ledger_detail->id)
    {
        if($to_account != '')
        {
            $ledger = $this->ledger_model->get_single_record($to_account);
            if($ledger) {
                echo $ledger->title . ' - ' . $ledger->group_title;
            } else {
                echo 'Bank Payment';
            }
        }
        else
        {
            echo $transaction->reference_no ?: 'Bank Payment';
        }
    }
    else
    {
        echo $transaction->reference_no ?: 'Bank Transaction';
    }
}
					?>
				</td>
				<td>
					<?= $cheque_no ?>
				</td>
				<td>
					<?php
						if($ledger_detail->group_title == BANK_ACCOUNT_GROUP || $ledger_detail->group_title == CASH_GROUP)
						{
							$total_cr_amount += $value->cr_amount;
							echo number_format_i($value->cr_amount);	
						}
						else
						{
							$total_dr_amount += $value->dr_amount;
							echo number_format_i($value->dr_amount);
							
						}
					?>
				</td>
				<td>
					<?php 
						if($ledger_detail->group_title == BANK_ACCOUNT_GROUP || $ledger_detail->group_title == CASH_GROUP)
						{
							$total_dr_amount += $value->dr_amount;
							echo number_format_i($value->dr_amount);	
						}
						else
						{
							$total_cr_amount += $value->cr_amount;
							echo number_format_i($value->cr_amount);
						}
					?>
				</td>
			</tr>
	<?php
		}
	?>				
	<tr>
		<td></td>
		<td>
			<u><?=strtoupper("Closing Balance")?></u>
		</td>
		<td></td>
		<td>
			<?php 
				if($ledger_detail->group_title == BANK_ACCOUNT_GROUP || $ledger_detail->group_title == CASH_GROUP)
				{
					if($c_balance < 0)
					{
						echo number_format_i(abs($c_balance));
					}
					else
					{
						echo '0.000';
					}
				}
				else
				{
					if($c_balance > 0)
					{
						echo number_format_i($c_balance);
					}
					else
					{
						echo '0.000';
					}
				}
			?>
		</td>
		<td>
			<?php 
				if($ledger_detail->group_title == BANK_ACCOUNT_GROUP || $ledger_detail->group_title == CASH_GROUP)
				{
					if($c_balance < 0)
					{
						echo '0.000';
					}
					else
					{
						echo number_format_i(abs($c_balance));
					}
				}
				else
				{
					if($c_balance > 0)
					{
						echo '0.000';
					}
					else
					{
						echo number_format_i(abs($c_balance));
					}
				}
			?>
		</td>
	</tr>
	<tr style="font-size: 12px;font-weight: bold" class="bg-light">
		<td></td>
		<td></td>
		<td></td>
		<td>
			<?php 
				if($ledger_detail->group_title == BANK_ACCOUNT_GROUP || $ledger_detail->group_title == CASH_GROUP)
				{
					if($c_balance < 0)
					{
						echo number_format_i($total_cr_amount+$c_balance);
					}
					else
					{
						echo number_format_i($total_cr_amount);
					}
				}
				else
				{
					if($c_balance > 0)
					{
						echo number_format_i($total_dr_amount+$c_balance);
					}
					else
					{
						echo number_format_i($total_dr_amount);
					}
				}
			?>
		</td>
		<td>
			<?php 
				if($ledger_detail->group_title == BANK_ACCOUNT_GROUP || $ledger_detail->group_title == CASH_GROUP)
				{
					if($c_balance < 0)
					{
						echo number_format_i($total_dr_amount);	
					}
					else
					{
						echo number_format_i($total_dr_amount+abs($c_balance));
					}
				}
				else
				{
					if($c_balance > 0)
					{
						echo number_format_i($total_cr_amount);
					}
					else
					{
						echo number_format_i($total_cr_amount+abs($c_balance));
					}
				}
			?>
		</td>
	</tr>
 
</table> <!-- End of transaction table -->

<!-- PARTY STATEMENT SUMMARY SECTION -->
<?php if(isset($ledger_detail->party_type) && $ledger_detail->party_data): 
    // Calculate totals from transaction_details
    $total_sale = 0;
    $total_purchase = 0;
    $total_expense = 0;
    $total_payment_in = 0;
    $total_payment_out = 0;
    $total_expense_payment = 0;
    $total_received = 0;
    $total_paid = 0;
    
    foreach($transaction_details as $value) {
        $transaction = $this->transaction_model->get_single_record($value->transaction_id);
        
        switch($transaction->module) {
            case 'S':
            case 'SALE_MODULE':
                $total_sale += $transaction->amount;
                break;
            case 'P':
            case 'PURCHASE_MODULE':
                $total_purchase += $transaction->amount;
                break;
            case 'E':
            case 'EXPENSE_MODULE':
                $total_expense += $transaction->amount;
                break;
            case 'PAYMENT_IN':
                $total_payment_in += $transaction->amount;
                $total_received += $transaction->amount;
                break;
            case 'PAYMENT_OUT':
                $total_payment_out += $transaction->amount;
                $total_paid += $transaction->amount;
                break;
        }
    }
?>

<!-- Summary Header -->
<table width="100%" style="margin-top: 20px; font-size: 10px;" cellspacing="0" cellpadding="5">
    <tr>
        <td colspan="4" style="background-color: #f0f0f0; text-align: center; font-weight: bold; font-size: 13px; padding: 8px; border: 1px solid #000;">
            PARTY STATEMENT SUMMARY
        </td>
    </tr>
</table>

<!-- Summary Cards - No Colors -->
<table width="100%" style="margin-top: 5px; font-size: 10px; border-collapse: collapse;" cellspacing="0" cellpadding="5">
    <!-- First Row Headers - Sales, Purchases, Expenses -->
    <tr>
        <td width="33%" style="border: 1px solid #000; background-color: #f0f0f0; text-align: center; font-weight: bold; padding: 8px;">
            TOTAL SALES
        </td>
        <td width="34%" style="border: 1px solid #000; background-color: #f0f0f0; text-align: center; font-weight: bold; padding: 8px;">
            TOTAL PURCHASES
        </td>
        <td width="33%" style="border: 1px solid #000; background-color: #f0f0f0; text-align: center; font-weight: bold; padding: 8px;">
            TOTAL EXPENSES
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid #000; text-align: center; font-size: 12px; font-weight: bold; padding: 8px;">
            <?= number_format($total_sale, 2) ?>
        </td>
        <td style="border: 1px solid #000; text-align: center; font-size: 12px; font-weight: bold; padding: 8px;">
            <?= number_format($total_purchase, 2) ?>
        </td>
        <td style="border: 1px solid #000; text-align: center; font-size: 12px; font-weight: bold; padding: 8px;">
            <?= number_format($total_expense, 2) ?>
        </td>
    </tr>
    
    <!-- Second Row Headers - Money In, Money Out, Receivable -->
    <tr>
        <td width="33%" style="border: 1px solid #000; background-color: #f0f0f0; text-align: center; font-weight: bold; padding: 8px;">
            TOTAL MONEY-IN
        </td>
        <td width="34%" style="border: 1px solid #000; background-color: #f0f0f0; text-align: center; font-weight: bold; padding: 8px;">
            TOTAL MONEY-OUT
        </td>
        <td width="33%" style="border: 1px solid #000; background-color: #f0f0f0; text-align: center; font-weight: bold; padding: 8px;">
            RECEIVABLE BALANCE
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid #000; text-align: center; font-size: 12px; font-weight: bold; padding: 8px;">
            <?= number_format($total_payment_in, 2) ?>
        </td>
        <td style="border: 1px solid #000; text-align: center; font-size: 12px; font-weight: bold; padding: 8px;">
            <?= number_format($total_payment_out, 2) ?>
        </td>
        <td style="border: 1px solid #000; text-align: center; font-size: 12px; font-weight: bold; padding: 8px;">
            <?= number_format($c_balance, 2) ?>
        </td>
    </tr>
</table>

<!-- WALLET TRANSACTIONS SECTION - Only for Customers -->
<?php if($ledger_detail->party_type == 'Customer'): ?>

<?php 
echo "<!-- DEBUG: Wallet Balance = " . ($wallet_balance ?? 'NOT SET') . " -->";
echo "<!-- DEBUG: Total Wallet Deposits = " . ($total_wallet_deposits ?? 'NOT SET') . " -->";
echo "<!-- DEBUG: Total Wallet Payments = " . ($total_wallet_payments ?? 'NOT SET') . " -->";
echo "<!-- DEBUG: Wallet Transactions Count = " . (isset($wallet_transactions) ? count($wallet_transactions) : 'NOT SET') . " -->";

if(isset($wallet_transactions) && !empty($wallet_transactions)):
    echo "<!-- DEBUG: First transaction type = " . $wallet_transactions[0]->type . " -->";
endif;
?>


<!-- Wallet Header -->
<table width="100%" style="margin-top: 20px; font-size: 10px;" cellspacing="0" cellpadding="5">
    <tr>
        <td colspan="5" style="background-color: #f0f0f0; text-align: center; font-weight: bold; font-size: 13px; padding: 8px; border: 1px solid #000;">
            WALLET TRANSACTIONS
        </td>
    </tr>
</table>

<!-- Wallet Balance -->
<table width="100%" style="margin-top: 5px; font-size: 10px; border-collapse: collapse;" cellspacing="0" cellpadding="5">
    <tr>
        <td width="100%" style="border: 1px solid #000; background-color: #f0f0f0; text-align: center; font-weight: bold; padding: 8px;">
            CURRENT WALLET BALANCE: <?= number_format($wallet_balance, 2) ?>
        </td>
    </tr>
</table>

<!-- Wallet Summary -->
<table width="100%" style="margin-top: 10px; font-size: 10px; border-collapse: collapse;" cellspacing="0" cellpadding="5">
    <tr>
        <td width="50%" style="border: 1px solid #000; background-color: #f0f0f0; text-align: center; font-weight: bold; padding: 8px;">
            TOTAL WALLET DEPOSITS
        </td>
        <td width="50%" style="border: 1px solid #000; background-color: #f0f0f0; text-align: center; font-weight: bold; padding: 8px;">
            TOTAL WALLET USAGE
        </td>
    </tr>
    <tr>
        <td style="border: 1px solid #000; text-align: center; font-size: 12px; font-weight: bold; padding: 8px;">
            <?= number_format($total_wallet_deposits, 2) ?>
        </td>
        <td style="border: 1px solid #000; text-align: center; font-size: 12px; font-weight: bold; padding: 8px;">
            <?= number_format($total_wallet_payments, 2) ?>
        </td>
    </tr>
</table>

<!-- Wallet Transaction History -->
<?php if(!empty($wallet_transactions)): ?>
<table width="100%" style="margin-top: 15px; font-size: 9px; border-collapse: collapse;" cellspacing="0" cellpadding="4" border="1">
    <tr style="background-color: #f0f0f0; font-weight: bold;">
        <td colspan="5" style="text-align: center; padding: 5px; border: 1px solid #000;">WALLET TRANSACTION HISTORY</td>
    </tr>
    <tr style="background-color: #e0e0e0; font-weight: bold;">
        <td width="15%" style="border: 1px solid #000; text-align: center;">Date</td>
        <td width="25%" style="border: 1px solid #000; text-align: center;">Type</td>
        <td width="25%" style="border: 1px solid #000; text-align: center;">Reference No</td>
        <td width="20%" style="border: 1px solid #000; text-align: center;">Amount</td>
        <td width="15%" style="border: 1px solid #000; text-align: center;">Balance</td>
    </tr>
    <?php 
    $running_balance = 0;
    foreach($wallet_transactions as $txn): 
        if($txn->type == 'WALLET_ADD') {
            $running_balance += $txn->amount;
            $amount_prefix = '+';
        } else {
            $running_balance -= $txn->amount;
            $amount_prefix = '-';
        }
    ?>
    <tr>
        <td style="border: 1px solid #000; text-align: center;"><?= date('d-m-Y', strtotime($txn->voucher_date)) ?></td>
        <td style="border: 1px solid #000; text-align: center;">
            <?php 
                if($txn->type == 'WALLET_ADD') echo 'Wallet Deposit';
                elseif($txn->type == 'WALLET_PAYMENT') echo 'Wallet Payment';
                elseif($txn->type == 'WALLET_DEDUCT') echo 'Wallet Deduction';
            ?>
        </td>
        <td style="border: 1px solid #000; text-align: center;"><?= $txn->reference_no ?: 'N/A' ?></td>
        <td style="border: 1px solid #000; text-align: right; padding-right: 10px; font-weight: bold;">
            <?= $amount_prefix ?> <?= number_format($txn->amount, 2) ?>
        </td>
        <td style="border: 1px solid #000; text-align: right; padding-right: 10px; font-weight: bold;">
            <?= number_format($running_balance, 2) ?>
        </td>
    </tr>
    <?php endforeach; ?>
    <tr style="background-color: #f0f0f0; font-weight: bold;">
        <td colspan="4" style="border: 1px solid #000; text-align: right; padding-right: 10px;">Current Wallet Balance:</td>
        <td style="border: 1px solid #000; text-align: right; padding-right: 10px;"><?= number_format($wallet_balance, 2) ?></td>
    </tr>
</table>
<?php else: ?>
<!-- No Wallet Transactions -->
<table width="100%" style="margin-top: 10px; font-size: 9px; border-collapse: collapse;" cellspacing="0" cellpadding="4" border="1">
    <tr>
        <td style="border: 1px solid #000; text-align: center; padding: 10px;">
            No wallet transactions found for the selected period.
        </td>
    </tr>
</table>
<?php endif; ?>
<?php endif; /* End of wallet section */ ?>

<?php endif; /* End of party statement section */ ?>
