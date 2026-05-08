<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
          <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_account')?></a></li>
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('header_bank_statement')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('bank_statement_view')?></li>
          </ol>
        </div>
      </div>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title"><?=$this->lang->line('bank_statement_view')?></h3>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">

                  <li class="nav-item  ml-2">
                    <a class="nav-link btn btn-secondary active" href="<?=base_url('bank_statement')?>" data-tt="tooltip" title="Click here to Go Back">
                      <i class="fas fa-arrow-left mr-2"></i>Back
                    </a>
                  </li>
                </ul>

              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-12">
                  <table class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th class="span2">Sr</th>
                        <!-- <th class="span2"><?=$this->lang->line('bank_statement')?></th> -->
                        <th class="span2"><?=$this->lang->line('bank_transaction_date')?></th>
                        <th class="span2"><?=$this->lang->line('bank_cheque_no')?></th>
                        <th class="span2"><?=$this->lang->line('bank_particulars')?></th>
                        <th class="span2"><?=$this->lang->line('bank_withdraw')?></th>
                        <th class="span2"><?=$this->lang->line('bank_deposit')?></th>
                        <th class="span2"><?=$this->lang->line('bank_reconcile_status')?></th>
                        
                      </tr>
                    </thead>
                    <tbody>
                      <?php 
                        if(sizeof($bank_statement_entries) > 0)
                        {
                          $i = 1;
                          foreach ($bank_statement_entries as $row) 
                          {
                          
                            $reconcile_status = '';
                        
                            if($row->reconcile_status == 'pending')
                            {
                              $reconcile_status .= '<span class="badge badge-danger"> Pending </span>';
                            }
                            elseif($row->reconcile_status == 'completed')
                            {
                              $reconcile_status .= '<span class="badge badge-primary"> Completed </span>'; 
                            }
                      ?>
                        <tr>
                          <td><?=$i++?></td>
                          <!-- <td><?=$row->bank_statement_id?></td> -->
                          <td><?=($row->transaction_date != '' && $row->transaction_date != '0000-00-00') ? date('d-m-Y',strtotime($row->transaction_date)) : '' ?></td>
                          <td><?=$row->cheque_no?></td>
                          <td><?=$row->particulars?></td>
                          <td><?=$row->withdraw?></td>
                          <td><?=$row->deposit?></td>
                          <td><?=$reconcile_status?></td>
                        </tr>
                      <?php 
                          }
                        }
                        else
                        {
                      ?>
                          <tr>
                            <td colspan="7">No record(s) are available.</td>
                          </tr>
                      <?php
                        }
                      ?>
                    </tbody>
                  </table>
                </div>
              </div>
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
