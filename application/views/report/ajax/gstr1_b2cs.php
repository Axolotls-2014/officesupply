<table class="table table-bordered table-striped">
  <thead>
    <tr>
      <th><?=$this->lang->line('gstr1_invoice_type')?></th>
      <th><?=$this->lang->line('place_of_supply')?></th>
      <th><?=$this->lang->line('tax_rate')?></th>
	    <th><?=$this->lang->line('taxable_value')?></th>
      <th><?=$this->lang->line('ecommerce_gstin')?></th>
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
		      <td>OE</td>
          <td><?=$value->PlaceOfSupply?></td>
		      <td><?=$value->TaxRate?></td>
			    <td><?=$value->TaxableValue?></td>
          <td><?=$value->EcommerceGSTIN?></td>
		    </tr>
    <?php  
        }
      }
      else
      {
    ?>
    <tr>
      <td colspan="5"><?=$this->lang->line('no_records_available')?></td>
    </tr>
    <?php
      }
    ?>
  </tbody>
</table>