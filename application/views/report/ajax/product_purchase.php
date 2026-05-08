<table class="sticky-header-table table table-bordered table-striped" id="product-purchase-data">
	<thead>
	  <tr>
	  	<th>SL</th>
    	<th>Product Name</th>
      <th>Purchase Date</th>
    	<th>PO Number</th>
    	<th>Batch No</th>
    	
    	<th>Billed Qty</th>
     
    	<th>Cost</th>
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

	    if(sizeof($purchase_items) > 0)
	    {
	    	$i = 1;
	      foreach ($purchase_items as $value) 
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

	      	$delivered_quantity = $this->purchase_delivery_model->get_total_quantity_of_purchase_item_delivered($value->purchase_id,$value->product_id);
	  ?>
				  <tr>                        
				    <td><?=$i++?></td>
			    	<td><?=$value->product_name?></td>
            <td><?=date('d-m-Y',strtotime($value->purchase_date))?></td>
			    	<td><?=$value->reference_no?></td>
			    	<td><?=$value->batch_no?></td>
			    	
			    	<td><?=($value->quantity)?></td>
           
			    	<td><?=number_format_i($value->cost)?></td>
			    	<td><?=number_format_i($value->subtotal)?></td>
			    	<td><?=($value->igst+$value->sgst+$value->cgst)?></td>
            <td><?=(number_format_i($value->igst_tax+$value->cgst_tax+$value->sgst_tax))?></td>
			    	<td><?=$value->company_name?></td>
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
	    <td colspan="12"><?=$this->lang->line('no_records_available')?></td>
	  </tr>
	  <?php
	    }
	  ?>
	</tbody>
	<!-- <tfoot class="bg-gray disabled footer_data">
		<tr>
	    <th colspan="3"></th>
	    <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_discount)?></th>
	    <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_taxable_value)?></th>
	    <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_tds)?></th>
	    <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_igst)?></th>
	    <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_cgst)?></th>
	    <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_sgst)?></th>
	    <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total)?></th>
	    <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_profit)?></th>
	  </tr>
	</tfoot> -->
</table>