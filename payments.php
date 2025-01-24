<?php 
require_once(dirname(__FILE__) . '/config.php'); 
if (!isset($_SESSION['Admin_ID']) || !isset($_SESSION['Login_Type'])) {
    header('location:' . BASE_URL);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment - Payroll</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/jquery.dataTables_themeroller.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/skins/_all-skins.min.css">
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
        .content-wrapper {
            padding: 20px;
        }
        .form-container, .slip-container {
            display: inline-block;
            vertical-align: top;
            width: 45%;
            margin-right: 5%;
        }
        .slip-container {
            width: 40%;
        }
        label {
            display: block;
            margin-top: 5px;
        }
        input, select {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            margin-top: 20px;
            padding: 10px 20px;
        }
        .payment-slip {
            padding: 20px;
            border: 1px solid #ccc;
            margin-top: 20px;
            background-color: #f9f9f9;
        }
        .hidden {
            display: none;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            #paymentSlip, #paymentSlip * {
                visibility: visible;
            }
            #paymentSlip {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
                padding: 0;
                border: none;
                background-color: white;
            }
            #paymentSlip button {
                display: none;
            }
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php require_once(dirname(__FILE__) . '/partials/topnav.php'); ?>
        <?php require_once(dirname(__FILE__) . '/partials/sidenav.php'); ?>
        
        <div class="content-wrapper">
            <section class="content-header">
                <h1>Payments</h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Payments</li>
                </ol>
            </section>
            <section class="content">
                <div class="form-container">
                    <form id="paymentForm">
                        
                        <label for="employeeCode">Employee Code:</label>
                        <input type="text" id="employeeCode" name="employee_code" required>

                        <label for="employeeName">Employee Name:</label>
                        <input type="text" id="employeeName" name="employee_name" required>

                        <label for="paymentType">Payment Type:</label>
                        <select id="paymentType" name="payment_type" required>
                            <option value="Salary">Salary</option>
                            <option value="Advance">Advance</option>
                            <option value="Loan">Loan</option>
                            <option value="EPF">EPF</option>
                            <option value="ETF">ETF</option>
                        </select>

                        <label for="paymentDate">Payment Date:</label>
                        <input type="date" id="paymentDate" name="payment_date" required>

                        <label for="payAmount">Pay Amount:</label>
                        <input type="number" id="payAmount" name="payment_amount" required>

                        <button type="submit" class="btn btn-primary">Generate Slip</button>
                    </form>
                </div>
                <div class="slip-container">
                    <div id="paymentSlip" class="payment-slip hidden">
                        <h2>Payment Slip</h2>
                        <p id="slipEmployeeCode"></p>
                        <p id="slipEmployeeName"></p>
                        <p id="slipPaymentType"></p>
                        <p id="slipPaymentDate"></p>
                        <p id="slipPayAmount"></p>
                        <button onclick="printSlip()" class="btn btn-primary">Print Slip</button>
                    </div>
                </div>
            </section>

            <section class="content">
				<div class="row">
        			<div class="col-xs-12">
						<div class="box">
							<div class="box-header">
								<h3 class="box-title">List of Payments</h3>
							</div>
							<div class="box-body">
								<div class="table-responsive">
									<table id="payments" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th class="text-center">#</th>
												<th class="text-center">Emp Code</th>
												<th class="text-center">Name</th>
												<th class="text-center">Payment Type</th>
                                                <th class="text-center">Payment Date</th>
												<th class="text-center">Amount</th>
											</tr>
										</thead>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>
        </div>
        <footer class="main-footer">
            <strong>&copy; <?php echo date("Y"); ?> Employee Payroll Management System</strong>
        </footer>
    </div>
    
    <script>
        document.getElementById('paymentForm').addEventListener('submit', function(event) {
            event.preventDefault();
            
            const employeeCode = document.getElementById('employeeCode').value;
            const employeeName = document.getElementById('employeeName').value;
            const paymentType = document.getElementById('paymentType').value;
            const paymentDate = document.getElementById('paymentDate').value;
            const payAmount = document.getElementById('payAmount').value;

            document.getElementById('slipEmployeeCode').innerText = `Employee Code: ${employeeCode}`;
            document.getElementById('slipEmployeeName').innerText = `Employee Name: ${employeeName}`;
            document.getElementById('slipPaymentType').innerText = `Payment Type: ${paymentType}`;
            document.getElementById('slipPaymentDate').innerText = `Payment Date: ${paymentDate}`;
            document.getElementById('slipPayAmount').innerText = `Pay Amount: ${payAmount}`;
            
            document.getElementById('paymentSlip').classList.remove('hidden');
        });

        function printSlip() {
            window.print();
        }
    </script>
    <script src="<?php echo BASE_URL; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/jquery-validator/validator.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datepicker/bootstrap-datepicker.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/iCheck/icheck.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
	<script src="<?php echo BASE_URL; ?>dist/js/app.min.js"></script>
	<script type="text/javascript">var baseurl = '<?php echo BASE_URL; ?>';</script>
	<script src="<?php echo BASE_URL; ?>dist/js/script.js?rand=<?php echo rand(); ?>"></script>
</body>
</html>
