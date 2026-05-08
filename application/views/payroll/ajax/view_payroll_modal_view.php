<div class="modal-header primary-header">
  <h4 class="modal-title">
     <?php echo $this->lang->line('payroll_view');?>
  </h4>
  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
  <span aria-hidden="true">&times;</span>
  </button>
</div>
<div class="modal-body">
  <div class="payslip-box">
    <div class="payslip-header">
        <h1><?= $company_setting->company_name ?></h1>
        <h2>Payslip for the month of <?= date('F Y', strtotime($payroll->payroll_month)) ?></h2>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered payslip-table">
                <tr>
                    <th>Emp No.</th>
                    <td><?= $payroll->employee_id ?></td>
                    <th>Department</th>
                    <td>ACCOUNTS</td> <!-- Add department dynamically if available -->
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?= $payroll->employee_name ?></td>
                    <th>Bank A/c No.</th>
                    <td><?= $payroll->account_number ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="row">
      <div class="col-md-12">
          <table class="table table-bordered payslip-table">
              <tr>
                  <th>EARNINGS</th>
                  <th>PER MONTH</th>
                  <th>DEDUCTIONS</th>
                  <th>PER MONTH</th>
              </tr>
              <tr>
                  <td>Base Salary</td>
                  <td><?= number_format($payroll->base_salary, 2) ?></td>
                  <td>Tax Deduction</td>
                  <td><?= number_format($payroll->total_tax, 2) ?></td>
              </tr>
							<tr>
                  <td>Total Bonus</td>
                  <td><?= number_format($payroll->total_bonuses, 2) ?></td> 
                  <td>Total Deduction</td>
                  <td><?= number_format($payroll->total_deductions, 2) ?></td> 
              </tr>
							<tr>
									<td></td>
									<td></td>
                  <td>Total Advances</td>
                  <td><?= number_format($payroll->total_advance, 2) ?></td>
              </tr>
              <tr>
                  <td></td>
                  <td></td>
                  <td>Leave Deduction</td>
                  <td><?= number_format($payroll->leave_deduction_amount, 2) ?></td>
              </tr>
          </table>
      </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered payslip-table">
                <tr>
                    <th>Earnings (A)</th>
                    <td><?= number_format($payroll->base_salary + $payroll->total_bonuses, 2) ?></td> <!-- Sum of all earnings -->
                </tr>
                <tr>
                    <th>Gross Deductions (B)</th>
                    <td><?= number_format($payroll->total_deductions  + $payroll->total_tax + $payroll->leave_deduction_amount  + $payroll->total_advance, 2) ?></td> <!-- Sum of all deductions -->
                </tr>
                <tr>
                    <th>Net Salary Payable (A-B)</th>
                    <td><?= number_format($payroll->net_salary, 2) ?></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="payslip-footer">
        <h3>Net Salary Payable: <?= number_format($payroll->net_salary, 2) ?></h3>
    </div>
  </div>
</div>
<div class="modal-footer">
  <a class="btn btn-primary download-btn" href="<?=base_url('payroll/pdf/'.$payroll->employee_id)?>" target="_blank">
    <i class="fas fa-download"></i> Download Payslip
  </a>
  <button type="button" class="btn btn-default" data-dismiss="modal">
    <?php echo $this->lang->line('btn_modal_close');?>
  </button>
  <input type="hidden" name="<?php echo $this->security->get_csrf_token_name(); ?>" value="<?php echo $this->security->get_csrf_hash(); ?>">
</div>

<style>
  .download-btn {
    float: left;
    margin-right: 5px;
  }
</style>
