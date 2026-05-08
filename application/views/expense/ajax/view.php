<table class="table table-striped">
  <tr>
    <td width="20%"><label><?=$this->lang->line('expense_date')?></label></td>
    <td width="5%"> : </td>
    <td><?=date('d-m-Y', strtotime($expense->date)) ?></td>
  
    <td width="20%"><label><?=$this->lang->line('expense_expense_category')?></label></td>
    <td width="5%"> : </td>
    <td><?=$expense->expense_category_name ?></td>  
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_supplier')?></label></td>
    <td> : </td>
    <td><?=$expense->company_name ?></td>
  
    <td><label><?=$this->lang->line('expense_remarks')?></label></td>
    <td> : </td>
    <td><?=$expense->remarks ?></td>
    
  </tr>
  
    
    <td><label><?=$this->lang->line('expense_amount')?></label></td>
    <td> : </td>
    <td><?=$expense->amount ?></td>
    <td><label><?=$this->lang->line('expense_itc')?></label></td>
    <td> : </td>
    <td><?=($expense->itc == 0) ? 'No' : 'Yes' ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_cgst')?></label></td>
    <td> : </td>
    <td><?=$expense->cgst_tax ?></td>
    <td><label><?=$this->lang->line('expense_document')?></label></td>
    <td> : </td>
    <td>
      <?php 
        if($expense->document != '')
        {
          $document_array = explode(",", $expense->document);
          for ($i=0; $i < sizeof($document_array); $i++) 
          {
            $file_name_without_ext  = explode(".", $document_array[$i])[0];
            $file_ext               = explode(".", $document_array[$i])[1];

            $actual_file_name       = $file_name_without_ext.'.'.$file_ext;
      ?>
            <div class="btn-group" style="margin-top:5px; margin-left:5px;margin-right:5px;">
              <a href="<?=base_url('attachment/download/'.$document_array[$i])?>" class="btn btn-default" data-tt="tooltip" title="<?php echo $actual_file_name?>">
                <i class="fas fa-paperclip"></i> 
                <?php 
                  $file_name_without_ext  = explode(".", $document_array[$i])[0];
                  $file_ext         = explode(".", $document_array[$i])[1];

                  $actual_file_name     = $file_name_without_ext.'.'.$file_ext;

                  echo $actual_file_name;
                ?>
              </a>
            </div>
      <?php 
          }
        }
      ?>
    </td>
    
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_igst')?></label></td>
    <td> : </td>
    <td><?=$expense->igst_tax ?></td>
  
    <td><label><?=$this->lang->line('expense_total_amount')?></label></td>
    <td> : </td>
    <td><?=$expense->total_amount ?></td>
  </tr>
  <tr>
  <tr>
    <td><label><?=$this->lang->line('expense_sgst')?></label></td>
    <td> : </td>
    <td><?=$expense->sgst_tax ?></td>
    <td><label><?=$this->lang->line('expense_due')?></label></td>
    <td> : </td>
    <td>
      <?=$expense->total_amount-$paid_amount ?>

      <?php 
        if($expense->total_amount > $paid_amount)
        { 
      ?>
      <button type="button" id="make_payment" class="btn btn-warning make_payment float-right" data-transaction_amount="<?=$expense->total_amount-$paid_amount?>">
        Make Payment
      </button>
      <?php 
        }
      ?>

    </td>
  </tr>
</table>            