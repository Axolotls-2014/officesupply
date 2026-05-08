<style type="text/css">
	th, td {
		padding: 4px;
	}
	.footer_data {
		font-size: 10px;
		background-color: #dee2e6;
	}
	.clickable-link {
		color: #007bff;
		text-decoration: underline;
	}
	.sale-link {
		color: #28a745;
		text-decoration: underline;
	}
</style>

<table width="100%" style="text-align: center; font-size: 10px;">
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
			<?=$this->lang->line('sales_report')?>
		</td>
	</tr>
	<tr>
		<td>
			<?=$this->lang->line('print_from_date').': '.$from_date.' - '.$this->lang->line('print_to_date').': '.$to_date?>
		</td>
	</tr>
</table>

<table border="1" style="text-align: center; font-size: 8px" width="100%" cellpadding="0" cellspacing="0">
	<thead>
		<tr>
			<th>Branch Name</th>
			<th><?=$this->lang->line('sale_invoice_date')?></th>
			<th><?=$this->lang->line('sale_reference_no')?></th>
			<th>Customer Name</th>
			<th>GSTIN</th>
			<th>Category</th>
			<th>Product_desc</th>
			<th>UOM</th>
			<th>HSN</th>
			<th>QTY</th>
			<th>Unit Price</th>
			<th>Taxable</th>
			<th><?=$this->lang->line('igst')?></th>
			<th><?=$this->lang->line('cgst')?></th>
			<th><?=$this->lang->line('sgst')?></th>
			<th>Discount</th>
			<th><?=$this->lang->line('sale_total')?></th>
		</tr>
	</thead>
	<tbody>
		<?php
		$total_taxable_value 	= 0.0;
		$total_discount 		= 0.0;
		$total_tax 				= 0.0;
		$total 					= 0.0;
		$total_profit			= 0.0;
		$total_igst 			= 0.0;
		$total_cgst 			= 0.0;
		$total_sgst 			= 0.0;
		$total_tds 				= 0.0;
		$total_cost 			= 0.0;
		$total_price 			= 0.0;			
		
		if(sizeof($sales) > 0)
		{
			foreach ($sales as $value) 
			{
				$total_taxable_value 	+= $value->total_taxable_value;
				$total_discount 		+= $value->total_discount;
				$total_tax 				+= $value->total_tax;
				$total 					+= $value->total;
				$total_tds 				+= $value->tds;

				$sale = $this->sale_model->get_sale_tax_individual_and_tot_cost_and_total_price($value->id);

				$total_igst 			+= $sale->igst_tax;
				$total_cgst 			+= $sale->cgst_tax;
				$total_sgst 			+= $sale->sgst_tax;
				$total_profit 			+= $sale->total_price - $sale->total_cost;
				$total_cost 			+= $sale->total_cost;
				$total_price 			+= $sale->total_price;
				
				// Create Sale URL (for "View Sale" link)
				//$sale_url = base_url('sale/view/' . base64_encode($value->id));
				
				// Get the proforma_invoice_id (Purchase Request ID)
				$purchase_request_id = isset($value->proforma_invoice_id) ? $value->proforma_invoice_id : '';
				$sale_url = base_url('Purchase_request/view_admin_request/' . base64_encode($purchase_request_id));
			?>
				<tr>
					<td><?=$value->warehouse_name;?></td>
					<td><?=date('d-m-Y', strtotime($value->invoice_date));?></td>
					<td>
						<?php if(!empty($purchase_request_id) && $purchase_request_id > 0): ?>
							<!-- MAIN LINK: Purchase Request (PO) -->
							<a href="<?=base_url('Purchase_request/view_admin_request/' . base64_encode($purchase_request_id))?>" target="_blank" class="clickable-link" title="Click to view Purchase Request (PO)">
								<?=$value->reference_no?>
							</a>
							<br>
							<small>
								<a href="<?=$sale_url?>" target="_blank" class="sale-link" title="Click to view Sale Invoice">
									View Sale
								</a>
							</small>
						<?php else: ?>
							<!-- Fallback: Show only Sale link -->
							<a href="<?=$sale_url?>" target="_blank" class="clickable-link" title="Click to view Sale Invoice">
								<?=$value->reference_no?>
							</a>
						<?php endif; ?>
					</td
					<td><?=$value->customer_name;?></td>
					<td><?=$value->gstin;?></td>
					<?php 
						$product_category_name = $this->db->select('name')
														  ->get_where('product_category', ['id' => $value->product_category_id])
														  ->row()
														  ->name; 
					?>
					<td><?=$product_category_name;?></td>
					<td><?=$value->description;?></td>
					<td><?=$this->db->select('name')->get_where('uom', ['id' => $value->uom_id])->row()->name;?></td>
					<td><?=$value->hsn;?></td>
					<td><?=$value->quantity;?></td>
					<td><?=$value->selling_price;?></td>
					<td><?=number_format_i($value->total_taxable_value)?></td>
					<td><?=number_format_i($sale->igst_tax)?></td>
					<td><?=number_format_i($sale->cgst_tax)?></td>
					<td><?=number_format_i($sale->sgst_tax)?></td>
					<td><?=number_format_i($value->total_discount)?></td>
					<td><?=number_format_i($value->total)?></td>
				</tr>
			<?php  
			}
		}
		else
		{
			?>
				<tr>
					<td colspan="17"><?=$this->lang->line('no_records_available')?></td>
				</tr>
			<?php
		}
		?>
	</tbody>
	<tfoot class="bg-gray disabled footer_data">
		<tr>
			<th colspan="11" style="text-align:right">Total:</th>
			<th><?=number_format_i($total_taxable_value)?></th>
			<th><?=number_format_i($total_igst)?></th>
			<th><?=number_format_i($total_cgst)?></th>
			<th><?=number_format_i($total_sgst)?></th>
			<th><?=number_format_i($total_discount)?></th>
			<th><?=number_format_i($total)?></th>
		</tr>
	</tfoot>
</table>