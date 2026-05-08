<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="#"><?=$this->lang->line('home')?></a></li>
            <li class="breadcrumb-item"><a href="<?=base_url('bank_account')?>"><?=$this->lang->line('header_bank_account')?></a></li>
            <li class="breadcrumb-item active"><?=$this->lang->line('bank_account_list')?></li>
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
              <h3 class="card-title"><?=$this->lang->line('bank_account_list')?></h3>
              <?php 
                if($this->permission_model->has_permission('add_bank_account'))
                {
              ?>
              <div class="card-tools">
                <ul class="nav nav-pills ml-auto">
                  <li class="nav-item">
                    <a class="nav-link active" href="<?=base_url('bank_account/add')?>" data-tt="tooltip" title="Click here to Add Bank Account">
                      <i class="fas fa-piggy-bank mr-2"></i><?=$this->lang->line('bank_account_add')?>
                    </a>
                  </li>
                </ul>
              </div>
              <?php 
                }
              ?>
            </div>
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th><?=$this->lang->line('bank_account_name')?></th>
                    <th><?=$this->lang->line('bank_account_number')?></th>
                    <th><?=$this->lang->line('bank_account_bank_name')?></th>
                    <th><?=$this->lang->line('bank_account_ifsc')?></th>
                    <th><?=$this->lang->line('bank_account_description')?></th>
                    <th><?=$this->lang->line('bank_account_opening_balance')?></th>
                    <th><?=$this->lang->line('bank_account_closing_balance')?></th>
                    <th><?=$this->lang->line('bank_account_action')?></th>
                  </tr>
                </thead>
                
                <tbody>
                  <?php 
                    foreach ($bank_account as $value) 
                    {
                  ?>
                  <tr>                        
                    
                    <td>
                      <?php 
                        echo $value->account_name."<br/>";
                        if($value->account_type == 0)
                        {
                          echo '['.$this->lang->line('bank_account_account_type_saving').']';
                        }
                        else 
                        {
                          echo '['.$this->lang->line('bank_account_account_type_current').']';
                        }
                      ?>
                      
                    </td>
                    <td><?php echo $value->account_number;?></td>
                    <td><?php echo $value->bank_name;?></td>
                    <td><?php echo $value->ifsc;?></td>
                    <td><?php echo $value->description;?></td>
                    <td><?php echo $value->opening_balance;?></td>
                    <td><span class="closing_balance_<?=$value->id?>"><?php echo $value->closing_balance;?></span></td>
                    <td>
                      <table>
                        <tr>
                          
                          <?php 
                            if($this->permission_model->has_permission('view_bank_account'))
                            {
                              $from_ledger = $this->ledger_model->get_single_record($value->ledger_id);
                          ?>
                          <td class="p-2 m-2">
                            <a href="#" data-toggle="modal" data-target="#view_bank_account_modal" class="btn bg-maroon btn-xs" data-tt="tooltip" title="<?=$this->lang->line('bank_account_view')?>" data-ledger_id="<?=$value->ledger_id?>" data-account_name="<?=$value->account_name?>" data-bank_name="<?=$value->bank_name?>" data-closing_balance="<?=$from_ledger->closing_balance?>" data-opening_balance="<?=$from_ledger->opening_balance?>" data-id="<?=$value->id?>">
                              <i class="fas fa-eye"></i>
                            </a>
                          </td>
                          <?php 
                            }
                          ?>

                          <?php 
                            if($this->permission_model->has_permission('transfer_bank_account'))
                            {
                              $from_ledger = $this->ledger_model->get_single_record($value->ledger_id);
                          ?>
                          <td class="p-2 m-2">
                            <a href="#" data-toggle="modal" data-target="#transfer_modal" class="btn bg-teal btn-xs" data-tt="tooltip" title="<?=$this->lang->line('bank_account_transfer')?>" data-ledger_id="<?=$value->ledger_id?>" data-account_name="<?=$value->account_name?>" data-bank_name="<?=$value->bank_name?>" data-closing_balance="<?=$from_ledger->closing_balance?>" data-id="<?=$value->id?>">
                              <i class="fas fa-exchange-alt"></i>
                            </a>
                          </td>
                          <?php 
                            }
                          ?>
                          <?php 
                            if($this->permission_model->has_permission('deposit_bank_account'))
                            {
                          ?>
                            <td class="p-2 m-2">
                            <a href="#" data-toggle="modal" data-target="#deposit_modal" class="btn bg-purple btn-xs" data-tt="tooltip" title="<?=$this->lang->line('bank_account_deposit')?>" data-id="<?=$value->id?>" data-account_name="<?=$value->ledger_title?>" data-bank_name="<?=$value->bank_name?>" data-ledger_id="<?=$value->ledger_id?>">
                              <i class="fas fa-coins"></i>
                            </a>
                          </td>
                          <?php 
                            }
                          ?>  

                          <?php 
                            if($this->permission_model->has_permission('withdraw_bank_account'))
                            {
                              $from_ledger = $this->ledger_model->get_single_record($value->ledger_id);
                          ?>
                          <td class="p-2 m-2">
                            <a href="#" data-toggle="modal" data-target="#withdraw_modal" class="btn bg-orange btn-xs" data-tt="tooltip" title="<?=$this->lang->line('bank_account_withdraw')?>" data-id="<?=$value->id?>" data-account_name="<?=$value->ledger_title?>" data-bank_name="<?=$value->bank_name?>" data-ledger_id="<?=$value->ledger_id?>" data-closing_balance="<?=$from_ledger->closing_balance?>">
                              <b>W</b>
                            </a>  
                          </td>
                          <?php 
                            }
                          ?>

                          <?php 
                            if($this->permission_model->has_permission('increase_bank_account'))
                            {
                              $from_ledger = $this->ledger_model->get_single_record($value->ledger_id);
                          ?>
                          <td class="p-2 m-2">
                            <a href="#" data-toggle="modal" data-target="#increase_reduce_modal" class="btn bg-navy btn-xs" data-tt="tooltip" title="<?=$this->lang->line('ledger_increase_capital_amount')?>" data-account_name="<?=$value->ledger_title?>" data-modal_title="<?=$this->lang->line('ledger_increase_capital_amount')?>" data-ledger_id="<?=$value->ledger_id?>" data-closing_balance="<?=$from_ledger->closing_balance?>" data-transaction_type="<?=INCREASE_ADJUSTMENT_TRANSACTION_TYPE?>" data-reference_no="INCREASE CAPITAL">
                              <i class="fas fa-arrow-up"></i>
                            </a>  
                          </td>
                          <?php 
                            }
                          ?>

                          <?php 
                            if($this->permission_model->has_permission('reduce_bank_account'))
                            {
                              $from_ledger = $this->ledger_model->get_single_record($value->ledger_id);
                          ?>
                          <td class="p-2 m-2">
                            <a href="#" data-toggle="modal" data-target="#increase_reduce_modal" class="btn bg-pink btn-xs" data-tt="tooltip" title="<?=$this->lang->line('ledger_reduce_capital_amount')?>" data-account_name="<?=$value->ledger_title?>" data-ledger_id="<?=$value->ledger_id?>" data-modal_title="<?=$this->lang->line('ledger_reduce_capital_amount')?>" data-closing_balance="<?=$from_ledger->closing_balance?>" data-transaction_type="<?=REDUCE_ADJUSTMENT_TRANSACTION_TYPE?>" data-reference_no="REDUCE CAPITAL">
                              <i class="fas fa-arrow-down"></i>
                            </a>  
                          </td>
                          <?php 
                            }
                          ?>
                          
                          <?php 
                            if($this->permission_model->has_permission('edit_bank_account'))
                            {
                              $from_ledger = $this->ledger_model->get_single_record($value->ledger_id);
                          ?>
                          <td class="p-2 m-2">
                            <a href="<?php echo base_url('bank_account/edit/'.$value->id);?>" class="btn btn-info btn-xs" data-tt="tooltip" title="<?=$this->lang->line('bank_account_edit')?>" data-id="<?=$value->id?>"  data-account_name="<?=$value->ledger_title?>" data-bank_name="<?=$value->bank_name?>" data-ledger_id="<?=$value->ledger_id?>" data-closing_balance="<?=$from_ledger->closing_balance?>">
                              <i class="fas fa-edit"></i>
                            </a>
                          </td>
                          <?php 
                            }
                          ?>
                          <!-- <td class="p-2 m-2"> -->
                            <?php 
                              // if($this->permission_model->has_permission('delete_bank_account'))
                              // {
                            ?>
                            <!-- <a href="#"  data-toggle="modal" data-target="#mymodal_<?php echo $value->id;?>" data-tt="tooltip" title="<?=$this->lang->line('bank_account_delete')?>" class="btn btn-danger btn-xs delete_product">
                              <i class="fas fa-trash"></i>
                            </a> -->

                            <!-- <div class="example-modal">
                              <div class="modal fade" id="mymodal_<?php echo $value->id;?>">
                                <div class="modal-dialog">
                                  <div class="modal-content">
                                    <div class="modal-header  failure-header">
                                      <h4 class="modal-title">
                                        <?php echo $this->lang->line('bank_account_delete');?>
                                      </h4>
                                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                      </button>
                                    </div>
                                    <div class="modal-body">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal">
                                          <?php echo $this->lang->line('btn_modal_close');?>
                                        </button>
                                        <a href="<?php echo base_url('bank_account/delete/'.$value->id);?>" class="btn btn-danger">
                                          <?php echo $this->lang->line('btn_modal_delete');?>
                                        </a>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div> -->
                            <?php 
                              // }
                            ?>
                          <!-- </td> -->
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <?php  
                    }
                  ?>
                </tbody>
                <tfoot>
                  <tr>
                    <th><?=$this->lang->line('bank_account_name')?></th>
                    <th><?=$this->lang->line('bank_account_number')?></th>
                    <th><?=$this->lang->line('bank_account_bank_name')?></th>
                    <th><?=$this->lang->line('bank_account_ifsc')?></th>
                    <th><?=$this->lang->line('bank_account_description')?></th>
                    <th><?=$this->lang->line('bank_account_opening_balance')?></th>
                    <th><?=$this->lang->line('bank_account_closing_balance')?></th>
                    <th><?=$this->lang->line('bank_account_action')?></th>
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

<div class="deposit-modal">
  <div class="modal fade" id="deposit_modal">
    <div class="modal-dialog">
      <form method="POST" name="addDepositForm" id="addDepositForm">
        <div class="modal-content">
          <div class="modal-header  purple-header">
            <h4 class="modal-title">
              <?php echo $this->lang->line('bank_account_deposit');?>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('ledger_transfer_from')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <?php
                  $cash_ledger    = $this->ledger_model->get_single_record(CASH_GROUP_LEDGER);
                ?>
                <input type="hidden" name="from_account" id="from_account" value="<?=$cash_ledger->id?>" data-closing_balance="<?=$cash_ledger->closing_balance?>" >
                <?=$cash_ledger->title.' ( CB : '.$cash_ledger->closing_balance.')'?>
                <span id="err_from_account" class="error invalid-feedback"><?=form_error('from_account');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('ledger_deposit_into')?>
              </label>
              <label for="inputEmail3" class="col-sm-6 col-form-label" style="font-weight: normal !important">
                <span name="account_name"></span> (<span name="bank_name"></span>)
              </label>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('transaction_amount')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="number" name="transaction_amount" id="transaction_amount" value="" class="form-control form-control-sm field_validation" placeholder="<?=$this->lang->line('transaction_amount')?>" required="required" max="<?=$cash_ledger->closing_balance?>">
                <span id="err_transaction_amount" class="error invalid-feedback"></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('transaction_reference_no')?>
              </label>
              <div class="col-sm-6">
                <input type="text" name="reference_no" value="" class="form-control form-control-sm" placeholder="<?=$this->lang->line('transaction_reference_no')?>">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="to_account" value="">
            <input type="hidden" name="entry_id" value="0">
            <input type="hidden" name="module" value="<?=BANK_MODULE?>">
            <input type="hidden" name="transaction_type" value="<?=CONTRA_TRANSACTION_TYPE?>">

            <input type="hidden" name="payment_mode" value="0">
            <input type="hidden" name="credit_card_no" value="">
            <input type="hidden" name="cheque_no" value="">
            <input type="hidden" name="cheque_date" value="">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <button type="submit" name="addBankDepositSubmit" id="addBankDepositSubmit" class="btn btn-info">
              <?php echo $this->lang->line('bank_account_deposit_label');?>  
            </button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
              <?php echo $this->lang->line('btn_modal_close');?>
            </button>
          </div>
        </div>
      </form>
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="transfer-modal">
  <div class="modal fade" id="transfer_modal">
    <div class="modal-dialog">
      <form name="addTransferForm" id="addTransferForm">
        <div class="modal-content">
          <div class="modal-header teal-header">
            <h4 class="modal-title">
              <?php echo $this->lang->line('bank_account_transfer');?>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('ledger_transfer_from').' <br/>('.$this->lang->line('ledger_closing_balance').')'?>
              </label>
              <label for="inputEmail3" class="col-sm-6 col-form-label" style="font-weight: normal !important">
                <span name="account_name"></span> (CB: <span name="closing_balance"></span>)
              </label>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('ledger_transfer_to')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <select class="form-control form-control-sm select2bs4 field_validation" name="to_account" id="to_account" placeholder="<?=$this->lang->line('ledger_transfer_to')?>" width="100%"  required="required">
                  <option value=""><?=$this->lang->line('select')?></option>
                </select>
                <span id="err_to_account" class="error invalid-feedback"><?=form_error('to_account');?></span>
              </div>
            </div>
            
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('transaction_amount')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="number" name="transaction_amount" id="transaction_amount" value="" class="form-control form-control-sm field_validation" placeholder="<?=$this->lang->line('transaction_amount')?>" max="" required="required">
                <span id="err_transaction_amount" class="error invalid-feedback"><?=form_error('transaction_amount');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('transaction_reference_no')?>
              </label>
              <div class="col-sm-6">
                <input type="text" name="reference_no" value="" class="form-control form-control-sm" placeholder="<?=$this->lang->line('transaction_reference_no')?>">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="from_account" value="">
            <input type="hidden" name="entry_id" value="0">
            <input type="hidden" name="module" value="<?=BANK_MODULE?>">

            <input type="hidden" name="payment_mode" value="0">
            <input type="hidden" name="transaction_type" value="<?=CONTRA_TRANSACTION_TYPE?>">
            <input type="hidden" name="credit_card_no" value="">
            <input type="hidden" name="cheque_no" value="">
            <input type="hidden" name="cheque_date" value="">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <button type="submit" name="addBankTransferSubmit" id="addBankTransferSubmit" class="btn btn-info transferSubmit">
              <?php echo $this->lang->line('bank_account_transfer_label');?>  
            </button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
              <?php echo $this->lang->line('btn_modal_close');?>
            </button>
          </div>
        </div>
      </form>
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="withdraw-modal">
  <div class="modal fade" id="withdraw_modal">
    <div class="modal-dialog">
      <form name="addWithdrawForm" id="addWithdrawForm">
        <div class="modal-content">
          <div class="modal-header teal-header">
            <h4 class="modal-title">
              <?php echo $this->lang->line('bank_account_withdraw');?>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('ledger_transfer_from').' <br/>('.$this->lang->line('ledger_closing_balance').')'?>
              </label>
              <label for="inputEmail3" class="col-sm-6 col-form-label" style="font-weight: normal !important">
                <span name="account_name"></span> (CB: <span name="closing_balance"></span>)
              </label>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('transaction_amount')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="number" name="transaction_amount" id="transaction_amount" value="" class="form-control form-control-sm field_validation" placeholder="<?=$this->lang->line('transaction_amount')?>" max="" required="required" >
                <span id="err_transaction_amount" class="error invalid-feedback"><?=form_error('transaction_amount');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('transaction_reference_no')?>
              </label>
              <div class="col-sm-6">
                <input type="text" name="reference_no" value="" class="form-control form-control-sm" placeholder="<?=$this->lang->line('transaction_reference_no')?>">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="from_account" value="">
            <input type="hidden" name="entry_id" value="0"> 
            <input type="hidden" name="module" value="<?=BANK_MODULE?>">
            <input type="hidden" name="to_account" value="<?=CASH_GROUP_LEDGER?>">
            <input type="hidden" name="payment_mode" value="0">
            <input type="hidden" name="transaction_type" value="<?=CONTRA_TRANSACTION_TYPE?>">
            <input type="hidden" name="credit_card_no" value="">
            <input type="hidden" name="cheque_no" value="">
            <input type="hidden" name="cheque_date" value="">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <button type="submit" name="addBankWithdrawSubmit" id="addBankWithdrawSubmit" class="btn btn-info withdrawSubmit">
              <?php echo $this->lang->line('bank_account_withdraw_label');?>  
            </button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
              <?php echo $this->lang->line('btn_modal_close');?>
            </button>
          </div>
        </div>
      </form>
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="increase-reduce-modal">
  <div class="modal fade" id="increase_reduce_modal">
    <div class="modal-dialog">
      <form name="addIncreaseReduceForm" id="addIncreaseReduceForm">
        <div class="modal-content">
          <div class="modal-header teal-header">
            <h4 class="modal-title">
              <?php echo $this->lang->line('bank_account_withdraw');?>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('ledger_current_balance')?>
              </label>
              <label for="inputEmail3" class="col-sm-6 col-form-label" style="font-weight: normal !important">
                <span name="account_name"></span> (CB: <span name="closing_balance"></span>)
              </label>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('transaction_amount')?>
                <span class="text-danger">*</span>
              </label>
              <div class="col-sm-6">
                <input type="number" name="transaction_amount" id="transaction_amount" value="" class="form-control form-control-sm field_validation" placeholder="<?=$this->lang->line('transaction_amount')?>" required="required">
                <span id="err_transaction_amount" class="error invalid-feedback"><?=form_error('transaction_amount');?></span>
              </div>
            </div>
            <div class="form-group row">
              <label for="inputEmail3" class="col-sm-6 col-form-label">
                <?=$this->lang->line('transaction_reference_no')?>
              </label>
              <div class="col-sm-6">
                <input type="text" name="reference_no" value="" class="form-control form-control-sm" placeholder="<?=$this->lang->line('transaction_reference_no')?>">
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <input type="hidden" name="from_account" value="">
            <input type="hidden" name="entry_id" value="0"> 
            <input type="hidden" name="module" value="<?=BANK_MODULE?>">
            <input type="hidden" name="to_account" value="">
            <input type="hidden" name="payment_mode" value="3">
            <input type="hidden" name="transaction_type" value="">
            <input type="hidden" name="credit_card_no" value="">
            <input type="hidden" name="cheque_no" value="">
            <input type="hidden" name="cheque_date" value="">
            <input type="hidden" name="reference_no" value="">
            <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">

            <button type="submit" name="addIncreaseReduceSubmit" id="addIncreaseReduceSubmit" class="btn btn-info increaseReduceSubmit">
              <?php echo $this->lang->line('save');?>  
            </button>
            <button type="button" class="btn btn-default" data-dismiss="modal">
              <?php echo $this->lang->line('btn_modal_close');?>
            </button>
          </div>
        </div>
      </form>
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<div class="view-bank-account-modal">
  <div class="modal fade" id="view_bank_account_modal">
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header teal-header">
          <h4 class="modal-title">
            <?php echo $this->lang->line('bank_account_view');?>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="card card-danger addTransactionCard">
            <div class="card-header bank_details light-failure-header">
              <h4 class="card-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#bank_details" class="" aria-expanded="true">
                  <?=$this->lang->line('bank_account_details')?>
                  <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                </a>
              </h4>
            </div>
            <div id="bank_details" class="panel-collapse collapse show" style="">
              <div class="card-body bank_account_details">
                
              </div>
            </div>
          </div>
          <div class="card card-success">
            <div class="card-header transactionEntries light-success-header">
              <h4 class="card-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#transactionEntries" class="collapsed" aria-expanded="false">
                  <?=$this->lang->line('sale_previous_transaction')?>
                  <span class="text-xs click_here_to_view_details">(<?=$this->lang->line('click_here_to_view_details')?>)</span>
                </a>
              </h4>
            </div>
            <div id="transactionEntries" class="panel-collapse collapse show">
              <div class="card-body transaction_entries">
                
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <input type="hidden" name="bank_id" id="bank_id" value="">
          <button type="button" class="btn btn-default" data-dismiss="modal">
            <?php echo $this->lang->line('btn_modal_close');?>
          </button>
        </div>
      </div>
    </div>
    <!-- /.modal-dialog -->
  </div>
</div>

<?php $this->load->view('layout/footer');?>

<script type="text/javascript">
  $(document).ready(function(e){

    const BankAccountToast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 10000
    });

    /*******************************************  Money deposit ****************************************/

    $('#deposit_modal').on('shown.bs.modal', function (e) {
      
      var bank_id       = $(e.relatedTarget).data('id');
      var account_name  = $(e.relatedTarget).data('account_name');
      var bank_name     = $(e.relatedTarget).data('bank_name');
      var to_account    = $(e.relatedTarget).data('ledger_id');

      var deposit_modal = $('#deposit_modal');

      deposit_modal.find('span[name="account_name"]').html(account_name);
      deposit_modal.find('span[name="bank_name"]').html(bank_name);
      deposit_modal.find('input[name="to_account"]').val(to_account);
      deposit_modal.find('input[name="transaction_amount"]').focus();
    });

    $('#deposit_modal').on('hide.bs.modal', function (e) {

      var deposit_modal = $('#deposit_modal');

      deposit_modal.find('span[name="account_name"]').html("");
      deposit_modal.find('span[name="bank_name"]').html("");
      deposit_modal.find('input[name="to_account"]').val("");

      $('form#addDepositForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if($('form#addDepositForm #'+id).hasClass('is-invalid'))
            $('form#addDepositForm #'+id).removeClass('is-invalid');

          if($('form#addDepositForm #'+id).hasClass('is-valid'))
            $('form#addDepositForm #'+id).removeClass('is-valid');
      });
    });

    $('#addDepositForm').submit(function(e){
      e.preventDefault();

      var isError = false;

      // var bank_id         = $(this).data('id');

      $('form#addDepositForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addDepositForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addDepositForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addDepositForm #err_"+id).text("").fadeOut('slow');
            $('form#addDepositForm #'+id).removeClass('is-invalid');
            $('form#addDepositForm #'+id).addClass('is-valid');
          }
      });

      if(isError == true)
      {
        return false;
      }
      else
      {
        var depositForm           = $(this).closest('form');        
        var depositFormData       = depositForm.serialize();
        var cash_closing_balance  = +$('.cash_closing_balance').text();
        var transaction_amount    = +depositForm.find('#transaction_amount').val();
        $('#addBankDepositSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $.ajax({
          url: "<?php echo base_url('transaction/add')?>",
          type: "POST",
          data: depositFormData,
          dataType: "JSON",
          success: function(data){

            $('#deposit_modal').modal('hide');

            // Update cash ledger balance
            $('.cash_closing_balance').text(cash_closing_balance-transaction_amount);
            depositForm.find('input[name="transaction_amount"]').val('');

            $('#addBankDepositSubmit').text('<?=$this->lang->line("bank_account_deposit_label")?>').removeAttr('disabled');

            if(data.code == 1)
            {
              BankAccountToast.fire({
                type: 'success',
                title: data.message
              });
              location.reload();

            }
            else
            {
              BankAccountToast.fire({
                type: 'error',
                title: data.message
              }); 
            }          
          }
        });   
        
      }
    });

    $("form#addDepositForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');

        if(id == 'from_account' && value != '')
        {
          var closing_balance = $(this).find(':selected').data('closing_balance');
          $('form#addDepositForm #transaction_amount').attr('max',closing_balance);
        }
        
        if(value==null || value=="")
        {
          $("form#addDepositForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addDepositForm #'+id).addClass('is-invalid');
          return false;
        }
        else
        {

          $("form#addDepositForm #err_"+id).text("").fadeOut('slow');
          $('form#addDepositForm #'+id).removeClass('is-invalid');
          $('form#addDepositForm #'+id).addClass('is-valid');
        }
    });

    /*******************************************  Money transfer ****************************************/

    $('#transfer_modal').on('shown.bs.modal', function (e) {
      
      var bank_id         = $(e.relatedTarget).data('id');
      var account_name    = $(e.relatedTarget).data('account_name');
      var closing_balance = $(e.relatedTarget).data('closing_balance');
      var from_account    = $(e.relatedTarget).data('ledger_id');

      var to_account      = $('#transfer_modal').find('#to_account');

      to_account.html('<option value="">Select</option>');

      $.ajax({
        url: "<?php echo base_url('ledger/index')?>",
        type: "GET",
        dataType: "JSON",
        success: function(data){

          var bank_accounts = data.bank_accounts;
          for(i=0;i<bank_accounts.length;i++)
          {
            if(bank_accounts[i].id != bank_id)
            {
              to_account.append('<option value="' + bank_accounts[i].ledger_id + '">' + bank_accounts[i].account_name + '</option>');  
            }
          }  
          to_account.select2('open');                            
        }
      });


      

      var transfer_modal = $('#transfer_modal');

      transfer_modal.find('span[name="account_name"]').html(account_name);
      transfer_modal.find('span[name="closing_balance"]').html(closing_balance);
      transfer_modal.find('input[name="transaction_amount"]').attr('max',closing_balance);
      transfer_modal.find('input[name="from_account"]').val(from_account);
    });

    $('#transfer_modal').on('hide.bs.modal', function (e) {

      var transfer_modal = $('#transfer_modal');

      transfer_modal.find('span[name="account_name"]').html("");
      transfer_modal.find('span[name="closing_balance"]').html("");
      transfer_modal.find('input[name="to_account"]').val("");

      var to_account      = $('#transfer_modal').find('#to_account');
      to_account.html('<option value="">Select</option>');

      $('form#addTransferForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if($('form#addTransferForm #'+id).hasClass('is-invalid'))
            $('form#addTransferForm #'+id).removeClass('is-invalid');

          if($('form#addTransferForm #'+id).hasClass('is-valid'))
            $('form#addTransferForm #'+id).removeClass('is-valid');
      });
    });

    $('#addTransferForm').submit(function(e){
      e.preventDefault();
      var isError = false;

      $('form#addTransferForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addTransferForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addTransferForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addTransferForm #err_"+id).text("").fadeOut('slow');
            $('form#addTransferForm #'+id).removeClass('is-invalid');
            $('form#addTransferForm #'+id).addClass('is-valid');
          }
      });

      if(isError == true)
      {
        return false;
      }
      else
      {
        var transferFormData = $(this).closest('form').serialize();
        $('#addBankTransferSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $.ajax({
          url: "<?php echo base_url('transaction/add')?>",
          type: "POST",
          data: transferFormData,
          dataType: "JSON",
          success: function(data){

            $('#transfer_modal').modal('hide');
            $('#addBankTransferSubmit').text('<?=$this->lang->line("bank_account_transfer_label")?>').removeAttr('disabled');

            if(data.code == 1)
            {
              BankAccountToast.fire({
                type: 'success',
                title: data.message
              });
              location.reload();

            }
            else
            {
              BankAccountToast.fire({
                type: 'error',
                title: data.message
              }); 
            } 

            location.reload(true);         
          }
        });  
      }
    });

    $("form#addTransferForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value==""){
          $("form#addTransferForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addTransferForm #'+id).addClass('is-invalid');
          return false;
        }
        else{
          $("form#addTransferForm #err_"+id).text("").fadeOut('slow');
          $('form#addTransferForm #'+id).removeClass('is-invalid');
          $('form#addTransferForm #'+id).addClass('is-valid');
        }
    });

    /*******************************************  Withdrawal ****************************************/

    $('#withdraw_modal').on('shown.bs.modal', function (e) {
      
      var bank_id         = $(e.relatedTarget).data('id');
      var account_name    = $(e.relatedTarget).data('account_name');
      var closing_balance = $(e.relatedTarget).data('closing_balance');
      var from_account    = $(e.relatedTarget).data('ledger_id');
      


      var withdraw_modal = $('#withdraw_modal');

      withdraw_modal.find('span[name="account_name"]').html(account_name);
      withdraw_modal.find('span[name="closing_balance"]').html(closing_balance);
      withdraw_modal.find('input[name="from_account"]').val(from_account);
      withdraw_modal.find('input[name="transaction_amount"]').attr('max',closing_balance).focus();
      // withdraw_modal.find('input[name="transaction_amount"]');
    });

    $('#withdraw_modal').on('hide.bs.modal', function (e) {

      var withdraw_modal = $('#withdraw_modal');

      withdraw_modal.find('span[name="account_name"]').html("");
      withdraw_modal.find('span[name="closing_balance"]').html("");

      $('form#addWithdrawForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if($('form#addWithdrawForm #'+id).hasClass('is-invalid'))
            $('form#addWithdrawForm #'+id).removeClass('is-invalid');

          if($('form#addWithdrawForm #'+id).hasClass('is-valid'))
            $('form#addWithdrawForm #'+id).removeClass('is-valid');
      });
    });

    $('#addWithdrawForm').submit(function(e){
      e.preventDefault();
      var isError = false;

      $('form#addWithdrawForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addWithdrawForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addWithdrawForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addWithdrawForm #err_"+id).text("").fadeOut('slow');
            $('form#addWithdrawForm #'+id).removeClass('is-invalid');
            $('form#addWithdrawForm #'+id).addClass('is-valid');
          }
      });

      if(isError == true)
      {
        return false;
      }
      else
      {
        var withdrawFormData = $(this).closest('form').serialize();
        $('#addBankWithdrawSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $.ajax({
          url: "<?php echo base_url('transaction/add')?>",
          type: "POST",
          data: withdrawFormData,
          dataType: "JSON",
          success: function(data){

            $('#withdraw_modal').modal('hide');
            $('#addBankWithdrawSubmit').text('<?=$this->lang->line("bank_account_transfer_label")?>').removeAttr('disabled');

            if(data.code == 1)
            {
              
              BankAccountToast.fire({
                type: 'success',
                title: data.message
              });

              location.reload();
            }
            else
            {
              BankAccountToast.fire({
                type: 'error',
                title: data.message
              }); 
            }          
          }
        });  
      }
    });

    $("form#addWithdrawForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value=="")
        {
          $("form#addWithdrawForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addWithdrawForm #'+id).addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addWithdrawForm #err_"+id).text("").fadeOut('slow');
          $('form#addWithdrawForm #'+id).removeClass('is-invalid');
          $('form#addWithdrawForm #'+id).addClass('is-valid');
        }
    });

    /************************************* Increase OR Reduce ****************************************/

    $('#increase_reduce_modal').on('shown.bs.modal', function (e) {
      
      var modal_title         = $(e.relatedTarget).data('modal_title');
      // var bank_id          = $(e.relatedTarget).data('id');
      var account_name        = $(e.relatedTarget).data('account_name');
      var closing_balance     = $(e.relatedTarget).data('closing_balance');
      var ledger_account      = $(e.relatedTarget).data('ledger_id');
      var transaction_type    = $(e.relatedTarget).data('transaction_type');
      var reference_no        = $(e.relatedTarget).data('reference_no');

      var increase_reduce_modal = $('#increase_reduce_modal');

      increase_reduce_modal.find('h4[class="modal-title"]').html(modal_title);
      increase_reduce_modal.find('span[name="closing_balance"]').html(closing_balance);

      
      increase_reduce_modal.find('span[name="account_name"]').html(account_name);
      increase_reduce_modal.find('input[name="transaction_amount"]').attr('min',1).focus();
      increase_reduce_modal.find('input[name="transaction_type"]').val(transaction_type);
      increase_reduce_modal.find('input[name="reference_no"]').val(reference_no);

      // alert(transaction_type);

      if(transaction_type == "<?=INCREASE_ADJUSTMENT_TRANSACTION_TYPE?>")
      {
        // alert('I'+ledger_acount);
        increase_reduce_modal.find('input[name="to_account"]').val(ledger_account);  
      }
      else
      {
        // alert('R'+ledger_acount);
        increase_reduce_modal.find('input[name="from_account"]').val(ledger_account);  
      }
    });

    $('#increase_reduce_modal').on('hide.bs.modal', function (e) {

      var increase_reduce_modal = $('#increase_reduce_modal');

      increase_reduce_modal.find('span[name="account_name"]').html("");
      increase_reduce_modal.find('span[name="closing_balance"]').html("");

      $('form#addIncreaseReduceForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if($('form#addIncreaseReduceForm #'+id).hasClass('is-invalid'))
            $('form#addIncreaseReduceForm #'+id).removeClass('is-invalid');

          if($('form#addIncreaseReduceForm #'+id).hasClass('is-valid'))
            $('form#addIncreaseReduceForm #'+id).removeClass('is-valid');
      });
    });

    $('#addIncreaseReduceForm').submit(function(e){
      e.preventDefault();
      var isError = false;

      $('form#addIncreaseReduceForm .field_validation').each(function() {
          
          var id    = $(this).attr('id');
          var value = $(this).val();
          var field = $(this).attr('placeholder');

          if(value==null || value==""){
            $("form#addIncreaseReduceForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
            $('form#addIncreaseReduceForm #'+id).addClass('is-invalid');
            isError = true;
          }
          else
          {
            $("form#addIncreaseReduceForm #err_"+id).text("").fadeOut('slow');
            $('form#addIncreaseReduceForm #'+id).removeClass('is-invalid');
            $('form#addIncreaseReduceForm #'+id).addClass('is-valid');
          }
      });

      if(isError == true)
      {
        return false;
      }
      else
      {
        var increaseReduceFormData = $(this).closest('form').serialize();
        $('#addIncreaseReduceSubmit').text('<?=$this->lang->line("please_wait")?>').attr('disabled','disabled');

        $.ajax({
          url: "<?php echo base_url('transaction/add')?>",
          type: "POST",
          data: increaseReduceFormData,
          dataType: "JSON",
          success: function(data){

            $('#increase_reduce_modal').modal('hide');
            $('#addIncreaseReduceSubmit').text('<?=$this->lang->line("save")?>').removeAttr('disabled');

            if(data.code == 1)
            {
              
              BankAccountToast.fire({
                type: 'success',
                title: data.message
              });

              location.reload();
            }
            else
            {
              BankAccountToast.fire({
                type: 'error',
                title: data.message
              }); 
            }          
          }
        });  
      }
    });

    $("form#addIncreaseReduceForm .field_validation").on("blur keyup change",  function (event){
        var id    = $(this).attr('id');
        var value = $(this).val();
        var field = $(this).attr('placeholder');
        
        if(value==null || value=="")
        {
          $("form#addIncreaseReduceForm #err_"+id).text(field+ " field is required.").fadeIn('slow');
          $('form#addIncreaseReduceForm #'+id).addClass('is-invalid');
          return false;
        }
        else
        {
          $("form#addIncreaseReduceForm #err_"+id).text("").fadeOut('slow');
          $('form#addIncreaseReduceForm #'+id).removeClass('is-invalid');
          $('form#addIncreaseReduceForm #'+id).addClass('is-valid');
        }
    });

    /************************************* Increase OR Reduce ****************************************/

    $('#view_bank_account_modal').on('shown.bs.modal', function (e) {
      
      var modal_title         = $(e.relatedTarget).data('modal_title');
      var bank_id             = $(e.relatedTarget).data('id');
      var account_name        = $(e.relatedTarget).data('account_name');
      var closing_balance     = $(e.relatedTarget).data('closing_balance');
      var opening_balance     = $(e.relatedTarget).data('opening_balance');

      $('#bank_id').val(bank_id);

      $.ajax({
        url: "<?php echo base_url('bank_account/view')?>",
        type: "POST",
        data: {
            "id" : bank_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
        },
        dataType: "JSON",
        success: function(data){
            
            $('.bank_account_details').html(data.bank_account_detail);
            $('.transaction_entries').html(data.transaction_entries);

            $('#example2').DataTable({
              "paging": true,
              "lengthChange": true,
              "searching": true,
              "ordering": true,
              "info": true,
              "autoWidth": true,
              "iDisplayLength": 10,
              "aLengthMenu": [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]]
            });

        }
      }); 
    });

    $(document).on('click', '.delete_transaction', function (event) {
      $(this).fadeOut(10);
      $(this).closest('td').find('.delete_transaction_confirmation').fadeIn();
    });

    $(document).on('click', '.delete_transaction_no', function (event) {
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_transaction').fadeIn(20);
    });

    $(document).on('click', '.delete_transaction_yes', function (event) {
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(10);
      $(this).closest('td').find('.delete_transaction_confirmation').fadeOut(1);
      

      var $this           = $(this);
      var transaction_id  = $(this).data('transaction_id');

      $(this).closest('td').find('.delete_transaction_yes').text('<?=$this->lang->line('please_wait')?>').addClass('disabled').fadeIn(1500);

      setTimeout(function(){ 

        $.ajax({
          url: "<?php echo base_url('transaction/delete')?>",
          type: "POST",
          data: {
            'transaction_id':transaction_id,
            '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
          },
          dataType: "JSON",
          success: function(data){

            $this.removeClass('btn-info').addClass('btn-success').text(data.message);
            $('#is_there_change_in_transaction').val('true');

            setTimeout(function(){ 

              if(data.code == 1)
              {
                var bank_id = $('#bank_id').val();

                $.ajax({
                  url: "<?php echo base_url('bank_account/view')?>",
                  type: "POST",
                  data: {
                      "id" : bank_id,
                      '<?php echo $this->security->get_csrf_token_name(); ?>' : '<?php echo $this->security->get_csrf_hash(); ?>'
                  },
                  dataType: "JSON",
                  success: function(data){
                      
                      $('.bank_account_details').html(data.bank_account_detail);
                      $('.transaction_entries').html(data.transaction_entries);

                      $('#example2').DataTable({
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": true,
                        "iDisplayLength": 10,
                        "aLengthMenu": [[10, 15, 25, 35, 50, 100, -1], [10, 15, 25, 35, 50, 100, "All"]]
                      });

                  }
                }); 
              }

            },500);
          }
        });
      }, 500);
    });
  });
</script>

<script type="text/javascript">
  $(document).ready(function(e){
      
    // $('#example1').children('tr:first').focus();

    document.addEventListener ("keydown", function (zEvent) {
      if (zEvent.altKey  &&  zEvent.key === "a") {  
        window.location.href="<?=base_url('bank_account/add')?>";
      }
    });
  });
</script>