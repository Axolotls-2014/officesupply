<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payslip</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
        }
        .payslip-box {
            border: 1px solid #ddd;
            padding: 20px;
            border-radius: 10px;
            max-width: 800px;
            margin: auto;
        }
        .payslip-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .payslip-header h1 {
            font-size: 20px; /* Decreased font size */
            margin: 0;
        }
        .payslip-header h2 {
            font-size: 16px; /* Decreased font size */
            margin: 0;
        }
        .payslip-table {
            width: 100%;
            margin-bottom: 20px;
            border-collapse: collapse;
            font-size: 10px; /* Decreased font size */
        }
        .payslip-table th, .payslip-table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }
        .payslip-footer {
            text-align: right;
            margin-top: 20px;
            font-size: 14px; /* Decreased font size */
        }
        .payslip-footer h3 {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="payslip-box">
        <div class="payslip-header">
            <h1><?= $company_setting->company_name ?></h1>
            <h2>Payslip for the month of <?= date('F Y', strtotime($payroll->payroll_month)) ?></h2>
        </div>
        <div>
            <table class="payslip-table">
                <tr>
                    <th>Emp No.</th>
                    <td><?= $payroll->employee_id ?></td>
                    <th>Bank A/c No.</th>
                    <td><?= $payroll->account_number ?></td>
                </tr>
                <tr>
                    <th>Name</th>
                    <td><?= $payroll->employee_name ?></td>
                    <th>Department</th>
                    <td>ACCOUNTS</td> <!-- Add department dynamically if available -->
                </tr>
            </table>
        </div>
        <div>
            <table class="payslip-table">
                <tr>
                    <th>EARNINGS</th>
                    <th>PER MONTH</th>
                    <th>DEDUCTIONS</th>
                    <th>PER MONTH</th>
                </tr>
                <tr>
                    <td>Basic</td>
                    <td><?= number_format($payroll->base_salary, 2) ?></td>
                    <td>Total Deduction</td>
                    <td><?= number_format($payroll->total_deductions, 2) ?></td>
                </tr>
                <tr>
                    <td>Total Bonus</td>
                    <td><?= number_format($payroll->total_bonuses, 2) ?></td> 
                    <td>Total Tax</td>
                    <td><?= number_format($payroll->total_tax, 2) ?></td>
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
        <div>
            <table class="payslip-table">
                <tr>
                    <th>Earnings (A)</th>
                    <td><?= number_format($payroll->base_salary + $payroll->total_bonuses, 2) ?></td> <!-- Sum of all earnings -->
                    <th>Gross Deductions (B)</th>
                    <td><?= number_format($payroll->total_deductions + $payroll->total_tax + $payroll->total_advance, 2) ?></td> <!-- Sum of all deductions -->
                </tr>
                <tr>
                    <th>Net Salary Payable (A-B)</th>
                    <td colspan="3"><?= number_format($payroll->net_salary, 2) ?></td>
                </tr>
            </table>
        </div>
        <div class="payslip-footer">
            <h3>Net Salary Payable: <?= number_format($payroll->net_salary, 2) ?></h3>
        </div>
    </div>
</body>
</html>
