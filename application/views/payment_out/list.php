<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">

      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?=base_url('auth')?>">Home</a></li>
            <li class="breadcrumb-item">Payments Out</li>
          </ol>
        </div>
      </div>
    </section>

    <section class="content">
  
      <div class="row">
        <div class="col-12">
          <div class="info-box">
            <span class="info-box-icon bg-info elevation-1"><i class="fas fa-money-bill-wave"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Payments Paid</span>
              <span class="info-box-number">
                <?=$this->session->userdata('currency_symbol')?><?=number_format($total_payment_out, 2)?>
              </span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Payments Paid</h3>
              <div class="card-tools">
                <?php if($this->permission_model->has_permission('add_payment_out')): ?>
                  <a class="btn btn-sm btn-primary" href="<?=base_url('payment_out/add')?>">
                    <i class="fas fa-plus"></i> Add Payment Out
                  </a>
                <?php endif; ?>
              </div>
            </div>
            <div class="card-body">
              <table id="paymentTable" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>Reference No</th>
                    <th>Date</th>
                    <th>Supplier</th>
                    <th>Amount</th>
                    <th>Payment Mode</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($payment_outs as $payment): ?>
                    <tr>
                      <td><?=$payment->reference_no?></td>
                      <td><?=date('d-m-Y', strtotime($payment->payment_date))?></td>
                      <td><?=$payment->supplier_name?></td>
                      <td><?=$this->session->userdata('currency_symbol')?><?=number_format($payment->amount, 2)?></td>
                      <td>
                        <?php 
                          switch($payment->payment_mode) {
                            case 0: echo 'Cash'; break;
                            case 1: echo 'Credit Card'; break;
                            case 2: echo 'Cheque'; break;
                            case 3: echo 'NEFT/RTGS'; break;
                            default: echo 'Unknown';
                          }
                        ?>
                      </td>
                      <td>
                        <a href="<?=base_url('payment_out/view/'.base64_encode($payment->id))?>" class="btn btn-sm btn-info" title="View">
                          <i class="fas fa-eye"></i>
                        </a>
                        <?php if($this->permission_model->has_permission('edit_payment_out')): ?>
                          <a href="<?=base_url('payment_out/edit/'.base64_encode($payment->id))?>" class="btn btn-sm btn-primary" title="Edit">
                            <i class="fas fa-edit"></i>
                          </a>
                        <?php endif; ?>
                        <?php if($this->permission_model->has_permission('delete_payment_out')): ?>
                          <a href="<?=base_url('payment_out/delete/'.base64_encode($payment->id))?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i>
                          </a>
                        <?php endif; ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
</div>

<?php $this->load->view('layout/footer');?>

<script>
$(document).ready(function() {
    $('#paymentTable').DataTable({
        "responsive": true,
        "autoWidth": false,
        "pageLength": 100,
         "order": []
        // "order": [[1, "desc"]]
    });
});
</script>