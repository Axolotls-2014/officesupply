<table class="table table-striped">
  <tr>
    <td width="20%"><label><?=$this->lang->line('expense_date')?></label></td>
    <td width="5%"> : </td>
    <td><?=date('d-m-Y', strtotime($expense->date)) ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_expense_category')?></label></td>
    <td> : </td>
    <td><?=$expense->expense_category_name ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_supplier')?></label></td>
    <td> : </td>
    <td><?=$expense->company_name ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_amount')?></label></td>
    <td> : </td>
    <td><?=$expense->amount ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_cgst')?></label></td>
    <td> : </td>
    <td><?=$expense->cgst_tax.'('.$expense->cgst.' %)' ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_sgst')?></label></td>
    <td> : </td>
    <td><?=$expense->sgst_tax.'('.$expense->sgst.' %)' ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_igst')?></label></td>
    <td> : </td>
    <td><?=$expense->igst_tax.'('.$expense->igst.' %)' ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_total_amount')?></label></td>
    <td> : </td>
    <td><?=$expense->total_amount ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_remarks')?></label></td>
    <td> : </td>
    <td><?=$expense->remarks ?></td>
  </tr>
  <tr>
    <td><label><?=$this->lang->line('expense_itc')?></label></td>
    <td> : </td>
    <td><?=($expense->itc == 0) ? 'No' : 'Yes' ?></td>
  </tr>
  <tr>
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

            $actual_file_name       = substr($file_name_without_ext, 0, -14).'.'.$file_ext;
      ?>
            <div class="btn-group" style="margin-top:5px; margin-left:5px;margin-right:5px;">
              <a href="<?=base_url('attachment/download_attachment/'.$document_array[$i])?>" class="btn btn-default" data-tt="tooltip" title="<?php echo $actual_file_name?>">
                <i class="fas fa-paperclip"></i> 
                <?php 
                  $file_name_without_ext  = explode(".", $document_array[$i])[0];
                  $file_ext         = explode(".", $document_array[$i])[1];

                  $actual_file_name     = substr($file_name_without_ext, 0, -14).'.'.$file_ext;

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
</table>            