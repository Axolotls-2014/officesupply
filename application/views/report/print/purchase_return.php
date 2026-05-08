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
			<?='Purchase Return Report'?>
		</td>
	</tr>
	<tr>
		<td>
			<?=$this->lang->line('print_from_date').': '.$from_date.' - '.$this->lang->line('print_to_date').': '.$to_date?>
		</td>
	</tr>
</table>
<table border="1" style="text-align: center;font-size: 8px" width="100%" cellpadding="0" cellspacing="0">
	<thead>
	  <tr>
	    <th><?=$this->lang->line('purchase_return_reference_no')?></th>
      <th><?=$this->lang->line('purchase_return_invoice_no')?></th>
      <th><?=$this->lang->line('purchase_return_date')?></th>
			<th><?=$this->lang->line('warehouse')?></th>
      <th><?=$this->lang->line('purchase_return_supplier')?></th>
      <th><?=$this->lang->line('purchase_return_total_discount')?></th>
      <th><?=$this->lang->line('purchase_return_total_taxable_value')?></th>
      <th><?=$this->lang->line('purchase_return_igst_tax')?></th>
	    <th><?=$this->lang->line('purchase_return_cgst_tax')?></th>
	    <th><?=$this->lang->line('purchase_return_sgst_tax')?></th>
      <th><?=$this->lang->line('purchase_return_total')?></th>

	  </tr>
	</thead>
	<tbody>
    <?php

    	$total_taxable_value 	= 0.0;
    	$total_discount 			= 0.0;
    	$total_tax 						= 0.0;
    	$total 								= 0.0;
    	$total_igst 					= 0.0;
	  	$total_cgst 					= 0.0;
	  	$total_sgst 					= 0.0;
    	

      if(sizeof($purchase_return) > 0)
      {
        foreach ($purchase_return as $value) 
        {
        	$total_taxable_value 	+= $value->total_taxable_value;
        	$total_discount 			+= $value->total_discount;
        	$total_tax 						+= $value->total_tax;
        	$total 								+= $value->total;

        	$purchase_return = $this->purchase_return_model->get_purchase_return_tax_individual($value->id);

	      	$total_igst 					+= $purchase_return->igst_tax;
	      	$total_cgst 					+= $purchase_return->cgst_tax;
	      	$total_sgst 					+= $purchase_return->sgst_tax;
    ?>
    <tr>                        
      <td><?=$value->reference_no?></td>
      <td><?=$value->invoice_no?></td>
      <td><?=date('d-m-Y', strtotime($value->purchase_return_date));?></td>
			<td><?=$value->name;?></td>
      <td><?=$value->company_name;?></td>
      <td><?=number_format_i($value->total_discount)?></td>
      <td><?=number_format_i($value->total_taxable_value)?></td>
      <td><?=number_format_i($purchase_return->igst_tax)?></td>
	    <td><?=number_format_i($purchase_return->cgst_tax)?></td>
	    <td><?=number_format_i($purchase_return->sgst_tax)?></td>
      <td><?=number_format_i($value->total)?></td>
    </tr>
    <?php  
        }
      }
      else
      {
    ?>
    <tr>
      <td colspan="9"><?=$this->lang->line('no_records_available')?></td>
    </tr>
    <?php
      }
    ?>
  </tbody>
	<tfoot class="bg-gray disabled footer_data">
		<tr>
	    <th colspan="5"></th>
	    <th><?=number_format_i($total_discount)?></th>
	    <th><?=number_format_i($total_taxable_value)?></th>
	    <th><?=number_format_i($total_igst)?></th>
	    <th><?=number_format_i($total_cgst)?></th>
	    <th><?=number_format_i($total_sgst)?></th>
	    <th><?=number_format_i($total)?></th>
	  </tr>
	</tfoot>
</table>
<script>window.print();</script>