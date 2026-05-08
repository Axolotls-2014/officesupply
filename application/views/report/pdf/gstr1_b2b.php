<style type="text/css">
	th,td{
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
			<?=$this->lang->line('gstr1_report')?>
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
      <th><?=$this->lang->line('customer_gstin')?></th>
      <th><?=$this->lang->line('sale_customer')?></th>
      <th><?=$this->lang->line('sale_reference_no')?></th>
      <th><?=$this->lang->line('sale_invoice_date')?></th>
      <th><?=$this->lang->line('invoice_value')?></th>
      <th><?=$this->lang->line('place_of_supply')?></th>
      <th><?=$this->lang->line('reverse_charge')?></th>
      <th><?=$this->lang->line('gstr1_invoice_type')?></th>
      <th><?=$this->lang->line('tax_rate')?></th>
      <th><?=$this->lang->line('taxable_value')?></th>
      <!-- <th><?=$this->lang->line('cess_amount')?></th> -->
      
      
    </tr>
  </thead>
  <tbody>
    <?php

      $total_taxable_value  = 0.0;
      $total_discount       = 0.0;
      $total_tax            = 0.0;
      $total_profit         = 0.0;
      $total                = 0.0;
      $total_igst           = 0.0;
      $total_cgst           = 0.0;
      $total_sgst           = 0.0;
      $total_tds            = 0.0;                      

      if(sizeof($sales) > 0)
      {
        foreach ($sales as $value) 
        {
    ?>
        <tr> 
          <td><?=$value->CustomerGSTIN?></td> 
          <td><?=$value->CustomerName;?></td>
          <td><?=$value->InvoiceNumber?></td>           
          <td><?=$value->InvoiceDate?></td>
          <td><?=$value->InvoiceValue?></td>
          <td><?=$value->PlaceOfSupply?></td>
          <td><?=$value->RCM?></td>
          <td>Regular B2B</td>
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
      <td colspan="10"><?=$this->lang->line('no_records_available')?></td>
    </tr>
    <?php
      }
    ?>
  </tbody>
</table>
