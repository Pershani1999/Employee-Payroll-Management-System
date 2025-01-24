<?php
require_once(dirname(__FILE__) . '/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $emp_code = $_POST['emp_code'];
    $pay_month = $_POST['pay_month'];

    $empData = GetEmployeeDataByEmpCode($emp_code);
    $empSalary = GetEmployeeSalaryByEmpCodeAndMonth($emp_code, $pay_month);

    if ($empData && $empSalary) {
        $totalEarnings = 0;
        $totalDeductions = 0;
        ?>

        <div class="table-responsive">
            <table class="table table-bordered">
                <tr>
                    <td width="20%">Employee Code</td>
                    <td width="30%"><?php echo strtoupper($empData['emp_code']); ?></td>
                    <td width="20%">Bank Name</td>
                    <td width="30%"><?php echo ucwords($empData['bank_name']); ?></td>
                </tr>
                <tr>
                    <td>Employee Name</td>
                    <td><?php echo ucwords($empData['first_name'] . ' ' . $empData['last_name']); ?></td>
                    <td>Branch Name</td>
                    <td><?php echo strtoupper($empData['branch_name']); ?></td>
                </tr>
                <tr>
                    <td>Designation</td>
                    <td><?php echo ucwords($empData['designation']); ?></td>
                    <td>Bank Account</td>
                    <td><?php echo $empData['account_no']; ?></td>
                </tr>
                <tr>
                    <td>Gender</td>
                    <td><?php echo ucwords($empData['gender']); ?></td>
                    <td>ETF Account</td>
                    <td><?php echo strtoupper($empData['etf_account']); ?></td>
                </tr>
                <tr>
                    <td>Location</td>
                    <td><?php echo ucwords($empData['address']); ?></td>
                    <td>EPF Account</td>
                    <td><?php echo strtoupper($empData['epf_account']); ?></td>
                </tr>

                <tr>
                    <td>Date of Joining</td>
                    <td><?php echo date('d-m-Y', strtotime($empData['joining_date'])); ?></td>
                </tr>
            </table>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th width="35%">Earnings</th>
                        <th width="15%" class="text-right">Amount (Rs.)</th>
                        <th width="35%">Deductions</th>
                        <th width="15%" class="text-right">Amount (Rs.)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($empSalary['earnings'] as $earning) { ?>
                        <?php $totalEarnings += $earning['payhead_amount']; ?>
                        <tr>
                            <td width="70%"><?php echo $earning['payhead_name']; ?></td>
                            <td width="30%" class="text-right"><?php echo number_format($earning['payhead_amount'], 2, '.', ''); ?></td>
                        </tr>
                    <?php } ?>
                    <?php foreach ($empSalary['deductions'] as $deduction) { ?>
                        <?php $totalDeductions += $deduction['payhead_amount']; ?>
                        <tr>
                            <td width="70%"><?php echo $deduction['payhead_name']; ?></td>
                            <td width="30%" class="text-right"><?php echo number_format($deduction['payhead_amount'], 2, '.', ''); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td><strong>Total Earnings</strong></td>
                        <td class="text-right">
                            <strong id="totalEarnings"><?php echo number_format($totalEarnings, 2, '.', ''); ?></strong>
                        </td>
                        <td><strong>Total Deductions</strong></td>
                        <td class="text-right">
                            <strong id="totalDeductions"><?php echo number_format($totalDeductions, 2, '.', ''); ?></strong>
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                        <td><strong>Net Salary</strong></td>
                        <td class="text-right">
                            <strong id="netSalary"><?php echo number_format($totalEarnings - $totalDeductions, 2, '.', ''); ?></strong>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <?php
    } else {
        echo '<p>No salary records found for this month</p>';
    }
}
?>
