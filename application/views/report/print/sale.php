<style type="text/css">
	th, td{
		padding: 4px;
	}
	.footer_data{
		font-size: 10px;
		background-color: #dee2e6;
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
			<?=$company_setting->address_line1.', '.$company_setting->address_line2.', '.$company_setting->city_name.', '.$company_setting->state_name.', '.$company_setting->country_name.' - '.$company_setting->pincode ?>
		</td>
	</tr>
	<tr>
		<td>
			<?= $this->lang->line('print_email').': '.$company_setting->email.', '.$this->lang->line('print_mobile').': '.$company_setting->mobile ?>
		</td>
	</tr>
	<tr>
		<td>
			<?=$this->lang->line('sales_report')?>
		</td>
	</tr>
	<tr>
		<td>
			<?=$this->lang->line('print_from_date').': '.$from_date.' - '.$this->lang->line('print_to_date').': '.$to_date?>
		</td>
	</tr>
</table>

<table border="1" style="text-align: center;font-size: 8px" width="100%" cellpadding="2" cellspacing="0">
	<thead>
		<tr>
			<th>Expense Month</th>
			<th>Branch Name</th>
			<th><?=$this->lang->line('sale_invoice_date')?></th>
			<th><?=$this->lang->line('sale_reference_no')?></th>
			<th>User ID</th>
			<th>Customer Name</th>
			<th>Phone Number</th>
			<th>Department</th>
			<th>GSTIN</th>
			<th>State</th>
			<th>Category</th>
			<th>Item Name</th>
			<th>Item Code</th>
			<th>UOM</th>
			<th>HSN</th>
			<th>QTY</th>
			<th>Unit Price</th>
			<th>Taxable</th>
			<th><?=$this->lang->line('igst')?></th>
			<th><?=$this->lang->line('cgst')?></th>
			<th><?=$this->lang->line('sgst')?></th>
			<th><?=$this->lang->line('sale_total')?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$total_taxable_value = 0.0;
		$total_discount = 0.0;
		$total_tax = 0.0;
		$total = 0.0;
		$total_igst = 0.0;
		$total_cgst = 0.0;
		$total_sgst = 0.0;
		$total_tds = 0.0;
		$total_cost = 0.0;
		$total_price = 0.0;

		if(!empty($sales))
		{
			foreach ($sales as $value) 
			{
				$total_taxable_value += $value->total_taxable_value;
				$total_discount += $value->total_discount;
				$total_tax += $value->total_tax;
				$total += $value->total;
				$total_tds += $value->tds;

				$sale = $this->sale_model->get_sale_tax_individual_and_tot_cost_and_total_price($value->id);

				if($sale != '')
				{
					$total_igst += $sale->igst_tax;
					$total_cgst += $sale->cgst_tax;
					$total_sgst += $sale->sgst_tax;
				}
				$total_cost += $sale->total_cost;
				$total_price += $sale->total_price;
				
				// Get category name
				$product_category_name = $this->db->select('name')
												 ->get_where('product_category', ['id' => $value->product_category_id])
												 ->row()
												 ->name;
				
				// Get UOM name
				$uom_name = $this->db->select('name')
									->get_where('uom', ['id' => $value->uom_id])
									->row()
									->name;
		?>
		<tr style="padding:2px;">                        
			<td><?= date('M Y', strtotime($value->invoice_date)) ?></td>
			<td><?= $value->warehouse_name ?></td>
			<td><?= date('d-m-Y', strtotime($value->invoice_date)) ?></td>
			<td><?= $value->reference_no ?></td>
			<td><?= isset($value->email) ? $value->email : 'N/A' ?></td>
			<td><?= $value->customer_name ?></td>
			<td><?= isset($value->phone) ? $value->phone : 'N/A' ?></td>
			<td><?= isset($value->customer_department) ? $value->customer_department : 'N/A' ?></td>
			<td><?= $value->gstin ?></td>
			<td><?= isset($value->state_name) ? $value->state_name : 'N/A' ?></td>
			<td><?= $product_category_name ?></td>
			<td><?= isset($value->product_name) ? $value->product_name : '' ?></td>
			<td><?= isset($value->product_code) ? $value->product_code : '' ?></td>
			<td><?= $uom_name ?></td>
			<td><?= $value->hsn ?></td>
			<td><?= $value->quantity ?></td>
			<td><?= number_format($value->selling_price, 2) ?></td>
			<td><?= number_format($value->total_taxable_value, 2) ?></td>
			<td><?= number_format($sale->igst_tax, 2) ?></td>
			<td><?= number_format($sale->cgst_tax, 2) ?></td>
			<td><?= number_format($sale->sgst_tax, 2) ?></td>
			<td><?= number_format($value->total, 2) ?></td>
		</tr>
		<?php  
			}
		}
		else
		{
		?>
		<tr>
			<td colspan="22"><?=$this->lang->line('no_records_available')?></td>
		</tr>
		<?php
		}
		?>
	</tbody>
	<tfoot class="bg-gray disabled footer_data">
		<tr>
			<th colspan="17" style="text-align: right;">Totals:</th>
			<th><?=number_format($total_taxable_value, 2)?></th>
			<th><?=number_format($total_igst, 2)?></th>
			<th><?=number_format($total_cgst, 2)?></th>
			<th><?=number_format($total_sgst, 2)?></th>
			<th><?=number_format($total, 2)?></th>
		</tr>
	</tfoot>
</table>

<script>
	window.onload = function() {
		window.print();
	}
</script>