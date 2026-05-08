<style type="text/css">
	th td{
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
			<?=$this->lang->line('product_sale_report')?>
		</td>
	</tr>
	<!-- <tr>
		<td>
			<?=$this->lang->line('print_from_date').': '.$from_date.' - '.$this->lang->line('print_to_date').': '.$to_date?>
		</td>
	</tr> -->
</table>
<table border="1" style="text-align: center;font-size: 8px" width="100%" cellpadding="0" cellspacing="0">
<thead>
    <tr>
	  	<th>SL</th>
    	<th>Product Name</th>
      <th>Sale Date</th>
    	<th>Sale Invoice Number</th>
      <th>Batch No</th>
    	
    	<th>Billed Qty</th>
      
    	<th>Selling Price</th>
    	<th>Total Amount</th>
    	<th>Tax Percentage</th>
      <th>Tax Amount</th>
    	<th>Customer Name</th>
    	<th>Delivered</th>
	  </tr>
	</thead>
	<tbody>
	  <?php

	  	$total_taxable_value 	= 0.0;
	  	$total_discount 			= 0.0;
	  	$total_tax 						= 0.0;
	  	$total 								= 0.0;
	  	$total_profit					= 0.0;
	  	$total_igst 					= 0.0;
	  	$total_cgst 					= 0.0;
	  	$total_sgst 					= 0.0;
	  	$total_tds  					= 0.0;

	    if(sizeof($products) > 0)
	    {
	    	$i = 1;
	      foreach ($products as $value) 
	      {
	      	
	      	// $total_taxable_value 	+= $value->total_taxable_value;
	      	// $total_discount 			+= $value->total_discount;
	      	// $total_tax 						+= $value->total_tax;
	      	// $total 								+= $value->total;
	      	// // $total_profit 				+= $total-$total_tax;
	      	// $total_tds 						+= $value->tds;

	      	// $sale = $this->sale_model->get_sale_tax_individual_and_tot_cost_and_total_price($value->id);


	      	// $total_igst 					+= $sale->igst_tax;
	      	// $total_cgst 					+= $sale->cgst_tax;
	      	// $total_sgst 					+= $sale->sgst_tax;

	      	// $total_profit 				+= $sale->total_price-$sale->total_cost;

	      	$delivered_quantity = $this->sale_delivery_model->get_total_quantity_of_sale_item_delivered($value->sale_id,$value->warehouse_product_id);

	  ?>
				  <tr>                        
				    <td><?=$i++?></td>
			    	<td><?=$value->product_name?></td>
            <td><?=date('d-m-Y',strtotime($value->invoice_date))?></td>
			    	<td><?=$value->reference_no?></td>
            <td><?=$value->batch_no?></td>
			    	
			    	<td><?=($value->quantity)?></td>
         
			    	<td><?=number_format_i($value->selling_price)?></td>
			    	<td><?=number_format_i($value->sub_total)?></td>
			    	<td><?=($value->igst+$value->sgst+$value->cgst)?></td>
            <td><?=(number_format_i($value->igst_tax+$value->cgst_tax+$value->sgst_tax))?></td>
			    	<td><?=$value->customer_name?></td>
			    	<td>
			    		<?php 
			    			// echo $value->quantity.' and '.$delivered_quantity;
			    			if(($value->quantity) == $delivered_quantity)
			    				echo '<span class="badge badge-success">DELIEVERED</span>';
			    			else if(($value->quantity) != $delivered_quantity && $delivered_quantity > 0)
			    				echo '<span class="badge badge-warning">PARTIAL</span>';
			    			else
			    				echo '<span class="badge badge-danger">NOT DELIVERED</span>';
			    		?>
			    	</td>
				  </tr>
	  <?php  
	      }
	    }
	    else
	    {
	  ?>
	  <tr>
	    <td colspan="13"><?=$this->lang->line('no_records_available')?></td>
	  </tr>
	  <?php
	    }
	  ?>
	</tbody>
	
</table>
<script>window.print();</script>