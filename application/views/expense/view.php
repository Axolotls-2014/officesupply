<?php $this->load->view('layout/header');?>
  <style type="text/css">
    .footer_data{
      font-size: 20px;
    }
  </style>
<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_people')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('expense_header')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('expense_view')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header secondary-header">
              <h3 class="card-title"><?=$this->lang->line('expense_view')?></h3>
              <!-- <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-tt="tooltip" title="<?=$this->lang->line('hide_details')?>">
                  <i class="fas fa-minus"></i>
                </button>
              </div> -->
            </div>
            <div class="card-body p-0">

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
                  <td><?=number_format_i($expense->amount)?></td>
                </tr>
                <tr>
                  <td><label><?=$this->lang->line('expense_cgst')?></label></td>
                  <td> : </td>
                  <td><?=number_format_i($expense->cgst_tax) ?></td>
                </tr>
                <tr>
                  <td><label><?=$this->lang->line('expense_sgst')?></label></td>
                  <td> : </td>
                  <td><?=number_format_i($expense->sgst_tax) ?></td>
                </tr>
                <tr>
                  <td><label><?=$this->lang->line('expense_igst')?></label></td>
                  <td> : </td>
                  <td><?=number_format_i($expense->igst_tax) ?></td>
                </tr>
                <tr>
                  <td><label><?=$this->lang->line('expense_total_amount')?></label></td>
                  <td> : </td>
                  <td><?=number_format_i($expense->total_amount)?></td>
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
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header purple-header">
              <h3 class="card-title"><?=$this->lang->line('expense_items')?></h3>
            </div>
            <div class="card-body m-0 p-0">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('sr_no')?></th>  
                    <th><?=$this->lang->line('expense_item_name')?></th>
                    <th><?=$this->lang->line('expense_item_taxable_amount')?></th>
                    <th><?=$this->lang->line('expense_item_igst')?></th>
                    <th><?=$this->lang->line('expense_item_cgst')?></th>
                    <th><?=$this->lang->line('expense_item_sgst')?></th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                  $i = 1;
                  foreach ($expense_items as $value) 
                  {
                ?>
                    <tr>
                      <td><?=$i++?></td>
                      <td><?=$value->item_name?></td>
                      <td><?=$value->taxable_amount?></td>
                      <td><?=$value->igst_tax.'('.$value->igst.' %)'?></td>
                      <td><?=$value->cgst_tax.'('.$value->cgst.' %)'?></td>
                      <td><?=$value->sgst_tax.'('.$value->sgst.' %)'?></td>
                    </tr>
                <?php                    
                  }
                ?>
                </tbody>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header purple-header">
              <h3 class="card-title"><?=$this->lang->line('expense_transaction')?></h3>
            </div>
            <div class="card-body m-0 p-0">
              <table class="table table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('sr_no')?></th>  
                    <th><?=$this->lang->line('header_expense_category')?></th>
                    <th><?=$this->lang->line('from_account')?></th>
                    <th><?=$this->lang->line('to_account')?></th>
                    <th><?=$this->lang->line('transaction_date')?></th>
                    <th><?=$this->lang->line('transaction_amount').' ('.$this->session->userdata('currency_symbol').')'?></th>
                  </tr>
                </thead>
                <tbody>
                <?php 
                  $total_paid_amount = 0;
                  if($transactions != null)
                  {
                    $i = 1;
                    foreach ($transactions as $value) {
                      $total_paid_amount += $value->amount;
                ?>
                <tr>
                  <td><?=$i++?></td>
                  <td>
                    <?php 
                      $expense          = $this->expense_model->get_single_record($value->entry_id);
                      $expense_category = $this->expense_category_model->get_single_record($expense->expense_category_id);

                      echo $expense_category->name;
                    ?>
                  </td>
                  <td><?=$value->from_ledger_title?></td>
                  <td><?=$value->to_ledger_title?></td>
                  <td><?=date('d-M-Y H:i:s', strtotime($value->created_date))?></td>
                  <td><?=number_format_i($value->amount)?></td>
                  
                </tr>
                <?php 
                    }
                  }
                  else
                  {
                ?>
                <tr>
                  <td colspan="6">No Record(s) are available.</td>
                </tr>
                <?php
                  }
                ?>
                </tbody>
                <tfoot class="bg-gray disabled footer_data">
                  <tr>
                    <th colspan="5"></th>  
                    <th><?=$this->session->userdata('currency_symbol').' '.number_format_i($total_paid_amount);?></th>
                  </tr>
                </tfoot>
              </table>
            </div>
            <!-- /.card-body -->
          </div>
          <!-- /.card -->
        </div>
        <!-- /.col -->
      </div>
      
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <aside class="control-sidebar control-sidebar-dark"></aside>
</div>

<?php $this->load->view('layout/footer');?>