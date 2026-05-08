<table class="table table-striped">
  <tr>
    <td width="20%"><label><?=$this->lang->line('sale_invoice_date')?></label></td>
    <td width="5%"> : </td>
    <td><?=date('d-m-Y', strtotime($sale->invoice_date)) ?></td>
  
    <td width="20%"><label><?=$this->lang->line('sale_reference_no')?></label></td>
    <td width="5%"> : </td>
    <td><?=$sale->reference_no ?></td>
  </tr>
  <tr>
    <!--<td><label><?=$this->lang->line('sale_total_discount')?></label></td>-->
    <!--<td> : </td>-->
    <!--<td><?=$sale->total_discount ?></td>-->
    <td><label><?=$this->lang->line('sale_customer')?></label></td>
    <td> : </td>
    <td><?=$sale->customer_name ?></td>
    
    
  </tr>
  <tr>
    <td><label><?=$this->lang->line('sale_total_taxable_value')?></label></td>
    <td> : </td>
    <td><?=$sale->total_taxable_value ?></td>
    <td><label><?=$this->lang->line('sale_rcm')?></label></td>
    <td> : </td>
    <td><?=($sale->rcm == "Y") ? "Yes" : "No"?></td>

  </tr>
  <tr>
    <td><label><?=$this->lang->line('sale_tds')?></label></td>
    <td> : </td>
    <td><?=$sale->tds ?></td>
    <td></td>
    <td> </td>
    <td></td>

  </tr>
  <tr>
    <td><label><?=$this->lang->line('sale_total_tax')?></label></td>
    <td> : </td>
    <td><?=$sale->total_tax ?></td>
    <td><label><?=$this->lang->line('sale_paid')?></label></td>
    <td> : </td>
    <td><?=$paid_amount-$sale->tds ?></td>
    
  </tr>
  <tr>
    <td><label><?=$this->lang->line('sale_total')?></label></td>
    <td> : </td>
    <td><?=$sale->total ?></td>
    <td><label><?=$this->lang->line('sale_due')?></label></td>
    <td> : </td>
    <td>
      <?=$sale->total-$paid_amount ?>
      <?php 
        if($sale->total-$sale->tds > $paid_amount)
        { 
      ?>
      <button type="button" id="make_payment" class="btn btn-warning make_payment float-right" data-transaction_amount="<?=$sale->total-$paid_amount?>">
        Make Payment
      </button>
      <?php 
        }
      ?>
    </td>
  </tr>
</table>            