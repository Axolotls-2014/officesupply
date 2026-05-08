<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<table width="100%"  style="font-size: 12px">
		<tr>
			<td align="center" style="border-bottom: dashed; border-width: 1px;">
				<address>
          <strong><h2><?=$company_setting->company_name?></strong></h2>
          <?= ($company_setting->address_line1 != '') ? ($company_setting->address_line1.'<br/>') : '' ?>
          <?= ($company_setting->address_line2 != '') ? ($company_setting->address_line2.'<br/>') : '' ?>
          <?=$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name?><br>
          <?= ($company_setting->pincode != '') ? ($company_setting->pincode.'<br/>') : '' ?>
          <?=$this->lang->line('phone')?>: <?=$company_setting->mobile?><br>
          <?=$this->lang->line('email')?>: <?=$company_setting->email?><br>
          <?=$this->lang->line('gstin_of_supplier')?>: <?=$company_setting->gstin?><br>
        </address>
			</td>
		</tr>
		<tr>
			<td style="border-bottom: dashed; border-width: 1px;">
				<table width="100%">
					<tr>
						<td>Date: <?=date('d-m-Y',strtotime($sale->invoice_date))?></td>
					</tr>
					<tr>
						<td>Invoice No: <?=$sale->reference_no?></td>
					</tr>
					<tr>
						<td>Customer Name: <?=$customer_detail->customer_name?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td>
				<table width="100%" style="font-size: 10px;text-align: left;border-bottom: dashed; border-width: 1px;" cellspacing="1px">
					<thead>
						<tr>
							<th>Sl.</th>
							<th>Name</th>
							<th>Qty</th>
							<th>Price</th>
							<th>Tax</th>
							<th>Subtotal</th>
						</tr>	
					</thead>
					<tbody>
						<?php 
							$i = 1;
							$total_discount = 0;
							$total_sub_total = 0;
							$total_taxable_value = 0;
							$total_tax = 0;


							foreach ($sale_items as $value) 
							{	
								$total_taxable_value 	+= $value->taxable_value;
								$total_sub_total 			+= $value->sub_total;
								$total_discount  			+= $value->discount_amount;
								$total_tax 						+= $value->igst_tax+$value->cgst_tax+$value->sgst_tax;
						?>
								<tr>
									<td><?=$i++?></td>
									<td><?=$value->product_name?></td>
									<td><?=$value->quantity?></td>
									<td><?=$value->taxable_value?></td>
									<td><?=($value->igst_tax+$value->cgst_tax+$value->sgst_tax)?></td>
									<td><?=$value->sub_total?></td>
								</tr>
						<?php 
							}	
						?>
					</tbody>
				</table>
			</td>
		</tr>
		<tr>
			<td style="border-bottom: dashed; border-width: 1px;">
				<table width="100%">
					<tr>
						<td>Discount</td>
						<td style="text-align: right"><?=$total_discount?></td>
					</tr>

					<tr>
						<td>Subtotal</td>
						<td style="text-align: right"><?=$total_taxable_value?></td>
					</tr>
					<tr>
						<td>Tax</td>
						<td style="text-align: right"><?=$total_tax?></td>
					</tr>
					<tr>
						<td>Total</td>
						<td style="text-align: right"><?=$total_sub_total?></td>
					</tr>
					<tr>
						<td>Amount Paid</td>
						<td style="text-align: right"><?=$total_sub_total?></td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td style="text-align: center;">
				Thank you for choosing us!
			</td>
		</tr>
		<tr>
			<td style="text-align: center">
				
        <center>
          <img src="<?=base_url('assets/login-page/images/pos_print.png')?>" style="width: 100px;">
        </center>
        
        <br/>      
				Powered by Sarva Aushadhi Store
			</td>
		</tr>
		<tr>
			<td>
				<!-- Print Button -->
				<button onclick="window.print()">Print</button>

				<!-- Close Button -->
				<button onclick="window.close()">Close</button>

			</td>
		</tr>
	</table>
</body>
</html>