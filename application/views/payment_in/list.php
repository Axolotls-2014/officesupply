<?php $this->load->view('layout/header');?>

<div class="wrapper">
  <div class="content-wrapper">
    <section class="content-header">
      <div class="row mb-2">
        <div class="col-sm-12">
          <ol class="breadcrumb breadcrumb-custom float-sm-left">
            <li class="breadcrumb-item"><a href="<?=base_url('auth')?>">Home</a></li>
            <li class="breadcrumb-item">Payments In</li>
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
              <span class="info-box-text">Total Payments Received</span>
              <span class="info-box-number">
                <?=$this->session->userdata('currency_symbol')?><?=number_format($total_payment_in, 2)?>
              </span>
            </div>
          </div>
        </div>
      </div>
      
      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Payments Received</h3>
              <div class="card-tools">
                <?php if($this->permission_model->has_permission('add_payment_in')): ?>
                  <a class="btn btn-sm btn-primary" href="<?=base_url('payment_in/add')?>">
                    <i class="fas fa-plus"></i> Add Payment In
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
                    <th>Company Name</th>
                    <th>Amount</th>
                    <th>Payment Mode</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach($payment_ins as $payment): ?>
                    <tr>
                      <td><?=$payment->reference_no?></td>
                      <td data-order="<?php echo date('Ymd', strtotime($payment->payment_date)); ?>"><?=date('d-m-Y', strtotime($payment->payment_date))?></td>
                      <td><?=$payment->customer_name?></td>
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
                        <a href="<?=base_url('payment_in/generate_pdf/'.base64_encode($payment->id))?>" class="btn btn-sm btn-secondary" title="Download PDF">
                            <i class="fas fa-file-pdf"></i>
                        </a>
                        <a href="<?=base_url('payment_in/view/'.base64_encode($payment->id))?>" class="btn btn-sm btn-info" title="View">
                          <i class="fas fa-eye"></i>
                        </a>
                        <!--<?php if($this->permission_model->has_permission('edit_payment_in')): ?>
                          <a href="<?=base_url('payment_in/edit/'.base64_encode($payment->id))?>" class="btn btn-sm btn-primary" title="Edit">
                            <i class="fas fa-edit"></i>
                          </a>
                        <?php endif; ?>
                        <?php if($this->permission_model->has_permission('delete_payment_in')): ?>
                          <a href="<?=base_url('payment_in/delete/'.base64_encode($payment->id))?>" class="btn btn-sm btn-danger" title="Delete" onclick="return confirm('Are you sure?')">
                            <i class="fas fa-trash"></i>
                          </a>
                        <?php endif; ?> -->
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
        "order": [[1, "desc"]]
    });
});
</script>