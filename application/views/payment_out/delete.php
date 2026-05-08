<?php $this->load->view('layout/header'); ?>

<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Delete Payment Out</h1>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="alert alert-danger" role="alert">
                        <h4 class="alert-heading">Warning!</h4>
                        <p>Are you sure you want to delete this payment? This action cannot be undone and will also remove all associated transaction records.</p>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header bg-light">
                            <h3 class="card-title">Payment Details</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Reference No:</strong></label>
                                        <p><?=$payment_out->reference_no?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Payment Date:</strong></label>
                                        <p><?=date('d-m-Y', strtotime($payment_out->payment_date))?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Supplier:</strong></label>
                                        <p><?=$payment_out->supplier_company_name?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Amount:</strong></label>
                                        <p><?=$this->session->userdata('currency_symbol')?><?=number_format($payment_out->amount, 2)?></p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Payment Mode:</strong></label>
                                        <p>
                                            <?php 
                                                switch($payment_out->payment_mode) {
                                                    case 0: echo 'Cash'; break;
                                                    case 1: echo 'Credit Card'; break;
                                                    case 2: echo 'Cheque'; break;
                                                    case 3: echo 'NEFT/RTGS'; break;
                                                    default: echo 'Unknown';
                                                }
                                            ?>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Notes:</strong></label>
                                        <p><?=$payment_out->notes ? $payment_out->notes : 'N/A'?></p>
                                    </div>
                                </div>
                            </div>

                            <?php if($payment_out->payment_mode == 3): ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>UTR Number:</strong></label>
                                        <p><?=$payment_out->utr_number?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label><strong>Bank Name:</strong></label>
                                        <p><?=$payment_out->bank_name?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <?php if(!empty($distributions)): ?>
                    <div class="card mt-3">
                        <div class="card-header bg-light">
                            <h3 class="card-title">Linked Invoices</h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>PO Number</th>
                                        <th>Invoice No</th>
                                        <th>Date</th>
                                        <th>Invoice Amount</th>
                                        <th>Amount Paid</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($distributions as $dist): ?>
                                    <tr>
                                        <td><?=$dist->po_number?></td>
                                        <td><?=$dist->invoice_no?></td>
                                        <td><?=date('d-m-Y', strtotime($dist->purchase_date))?></td>
                                        <td><?=$this->session->userdata('currency_symbol')?><?=number_format($dist->invoice_amount, 2)?></td>
                                        <td><?=$this->session->userdata('currency_symbol')?><?=number_format($dist->amount, 2)?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <div class="col-md-4">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Delete Confirmation</h3>
                        </div>
                        <div class="card-body">
                            <p><strong>Reference No:</strong> <?=$payment_out->reference_no?></p>
                            <p><strong>Amount:</strong> <?=$this->session->userdata('currency_symbol')?><?=number_format($payment_out->amount, 2)?></p>
                            <p class="text-muted">This action will:</p>
                            <ul class="text-muted">
                                <li>Delete the payment record</li>
                                <li>Reverse all ledger transactions</li>
                                <li>Remove payment distributions</li>
                            </ul>
                        </div>
                        <div class="card-footer bg-transparent">
                            <form method="POST" action="<?=base_url('payment_out/delete/'.base64_encode($payment_out->id))?>">
                                <input type="hidden" name="<?=$this->security->get_csrf_token_name()?>" value="<?=$this->security->get_csrf_hash()?>">
                                <input type="hidden" name="action" value="confirm_delete">
                                <input type="hidden" name="payment_id" value="<?=base64_encode($payment_out->id)?>">
                                <button type="submit" class="btn btn-danger btn-block">
                                    <i class="fas fa-trash"></i> Delete Payment
                                </button>
                                <a href="<?=base_url('payment_out')?>" class="btn btn-secondary btn-block mt-2">
                                    <i class="fas fa-times"></i> Cancel
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>

<?php $this->load->view('layout/footer'); ?>
