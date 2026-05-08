<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th><?=$this->lang->line('sale_customer')?></th>
      <th><?=$this->lang->line('sale_reference_no')?></th>
      <th><?=$this->lang->line('sale_invoice_date')?></th>
      <th><?=$this->lang->line('invoice_value')?></th>
      <th><?=$this->lang->line('place_of_supply')?></th>
      <th><?=$this->lang->line('tax_rate')?></th>
	    <th><?=$this->lang->line('taxable_value')?></th>
	    <!-- <th><?=$this->lang->line('cess_amount')?></th> -->
      
      
    </tr>
  </thead>
  <tbody>
    <?php

    	$total_taxable_value 	= 0.0;
    	$total_discount 			= 0.0;
    	$total_tax 						= 0.0;
    	$total_profit 				= 0.0;
    	$total 								= 0.0;
    	$total_igst 					= 0.0;
	  	$total_cgst 					= 0.0;
	  	$total_sgst 					= 0.0;
	  	$total_tds 						= 0.0;									  	

      if(sizeof($sales) > 0)
      {
        foreach ($sales as $value) 
        {
    ?>
		    <tr> 
		    	<td><?=$value->CustomerName;?></td>
          <td><?=$value->InvoiceNumber?></td>           
          <td><?=$value->InvoiceDate?></td>
          <td><?=$value->InvoiceValue?></td>
          <td><?=$value->PlaceOfSupply?></td>
		      <td><?=$value->TaxRate?></td>
			    <td><?=$value->TaxableValue?></td>
		    </tr>
    <?php  
        }
      }
      else
      {
    ?>
    <tr>
      <td colspan="7"><?=$this->lang->line('no_records_available')?></td>
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