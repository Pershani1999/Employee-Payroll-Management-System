<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(dirname(__FILE__) . '/config.php'); 
if ( !isset($_SESSION['Admin_ID']) || $_SESSION['Login_Type'] != 'admin' ) {
   	header('location:' . BASE_URL);
} ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<title>Employees - Payroll</title>

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/jquery.dataTables_themeroller.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datepicker/datepicker3.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/skins/_all-skins.min.css">

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="hold-transition skin-blue sidebar-mini">
	<div class="wrapper">
		
		<?php require_once(dirname(__FILE__) . '/partials/topnav.php'); ?>

		<?php require_once(dirname(__FILE__) . '/partials/sidenav.php'); ?>

		<div class="content-wrapper">
			<section class="content-header">
				<h1>
					Employees
				</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Employees</li>
				</ol>
			</section>

			<section class="content">
				<div class="row">
        			<div class="col-xs-12">
						<div class="box">
							<div class="box-header">
								<h3 class="box-title">All Employee List</h3>
								<a href="<?php echo BASE_URL; ?>registration/new/" class="btn btn-xs btn-primary pull-right">
									<i class="fa fa-plus"></i> Add New Employee
								</a>
								 
							</div>
							<div class="box-body">
								<div class="table-responsiove">
									<table id="employees" class="table table-bordered table-striped">
										<thead>
											<tr>
												<th>ID</th>
												<th>IMAGE</th>
												<th>NAME</th>
												<th>EMAIL</th>
												<th>CONTACT</th>
												<th>NIC</th>
												<th>DOB</th>
												<th>JOINING</th>
												<th>DESIGNATION</th>
												
												
												<th width="6%">ACTION</th>
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

		<div class="modal fade in" id="SalaryMonthModal" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title">Select Month for Salary</h4>
					</div>
					<div class="modal-body">
						<div class="row">
							<?php 
							$months = array(); $years = array();
							$before2Month = (int)date('m') - 2;
							for ( $i = $before2Month; $i < $before2Month + 16; $i++ ) {
							    $months[$i] = date('F', mktime(0, 0, 0, $i, 1));
							    $years[$i] = date('Y', mktime(0, 0, 0, $i, 1));
							}
							foreach ( $months as $key => $month ) { ?>
								<div class="col-sm-3 <?php echo ($month == date('F') && $years[$key] == date('Y')) ? 'bg-danger' : ''; ?>">
									<a data-month="<?php echo $month; ?>" data-year="<?php echo $years[$key]; ?>" href="#">
										<?php echo strtoupper($month); ?><br /><?php echo $years[$key]; ?>
									</a>
								</div>
							<?php 
							} ?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="modal fade in" id="ManageModal" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title text-center">Add Payheads to Employee</h4>
					</div>
					<form method="post" role="form" data-toggle="validator" id="assign-payhead-form">
						<div class="modal-body">
							<div class="row">
								<div class="col-sm-4">
									<label for="all_payheads">List of Pay Heads</label>
									<button type="button" id="selectHeads" class="btn btn-success btn-xs pull-right"><i class="fa fa-arrow-circle-right"></i></button>
									<select class="form-control" id="all_payheads" name="all_payheads[]" multiple size="10"></select>
								</div>
								<div class="col-sm-4">
									<label for="selected_payheads">Selected Pay Heads</label>
									<button type="button" id="removeHeads" class="btn btn-danger btn-xs pull-right"><i class="fa fa-arrow-circle-left"></i></button>
									<select class="form-control" id="selected_payheads" name="selected_payheads[]" data-error="Pay Heads is required" multiple size="10" required>

									

									</select>
								</div>
								<div class="col-sm-4">
									<label for="selected_payamount">Enter Payhead Amount</label>
									<div id="selected_payamount">

									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<input type="hidden" name="empcode" id="empcode" />
							<button type="submit" name="submit" class="btn btn-primary">Add Pay Heads to Employee</button>
						</div>
					</form>
				</div>
			</div>
		</div>


		<div class="modal fade in" id="EditEmpModal" tabindex="-1">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
						<h4 class="modal-title">Update Employee Details</h4>
					</div>
					<form method="post" role="form" data-toggle="validator" id="edit-emp-form">
						<div class="modal-body">
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label for="emp_code">Emp. Code</label>
										<div class="form-control" id="emp_code" id="emp_code"></div>
										<input type="hidden" name="emp_id" id="emp_id" />
									</div>
									<div class="col-sm-4">
										<label for="first_name">First Name</label>
										<input type="text" class="form-control" name="first_name" id="first_name" required />
									</div>
									<div class="col-sm-4">
										<label for="last_name">Last Name</label>
										<input type="text" class="form-control" name="last_name" id="last_name" required />
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label for="dob">Emp. DOB (MM/DD/YYYY)</label>
										<input type="text" class="form-control datepicker" name="dob" id="dob" required />
									</div>
									<div class="col-sm-4">
										<label for="gender">Gender</label>
										<select class="form-control" name="gender" id="gender" required>
											<option value="">Please make a choice</option>
											<option value="male">Male</option>
											<option value="female">Female</option>
										</select>
									</div>
									<div class="col-sm-4">
										<label for="identity_no">Identity No</label>
										<input type="text" class="form-control" name="identity_no" id="identity_no" required />
									</div>
								</div>
							</div>
							
							<div class="form-group">
								<div class="row">
									<div class="col-sm-12">
										<label for="address">Address</label>
										<textarea class="form-control" name="address" id="address" required></textarea>
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									
									<div class="col-sm-4">
										<label for="email">Email</label>
										<input type="email" class="form-control" name="email" id="email" required />
									</div>
								
									<div class="col-sm-4">
										<label for="mobile">Mobile</label>
										<input type="text" class="form-control" name="mobile" id="mobile" required />
									</div>
									
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									
									<div class="col-sm-4">
										<label for="joining_date">DOJ (MM/DD/YYYY)</label>
										<input type="text" class="form-control datepicker" name="joining_date" id="joining_date" required />
									</div>
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label for="designation">Designation</label>
										<input type="text" class="form-control" name="designation" id="designation" required />
									</div>
									
									
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label for="bank_name">Bank Name</label>
										<input type="text" class="form-control" name="bank_name" id="bank_name" required />
									</div>
									<div class="col-sm-4">
										<label for="branch_name">Branch Name</label>
										<input type="text" class="form-control" name="branch_name" id="branch_name" required />
									</div>
									<div class="col-sm-4">
										<label for="account_no">Bank A/C No.</label>
										<input type="text" class="form-control" name="account_no" id="account_no" required />
									</div>
									
								</div>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-sm-4">
										<label for="epf_account">EPF A/C No.</label>
										<input type="text" class="form-control" name="epf_account" id="epf_account" required />
									</div>
									<div class="col-sm-4">
										<label for="etf_account">ETF A/C No.</label>
										<input type="text" class="form-control" name="etf_account" id="etf_account" required />
									</div>
								</div>
							</div>
						</div>
						<div class="modal-footer">
							<button type="submit" name="submit" class="btn btn-primary">Update Employee</button>
						</div>
					</form>
				</div>
			</div>
		</div>

		<footer class="main-footer">
		<strong> Employee Payroll Management System of CEW</strong>
		</footer>
	</div>

	<script src="<?php echo BASE_URL; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/jquery-validator/validator.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datatables/jquery.dataTables.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/bootstrap-notify/bootstrap-notify.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datepicker/bootstrap-datepicker.js"></script>
	<script src="<?php echo BASE_URL; ?>dist/js/app.min.js"></script>
	<script type="text/javascript">var baseurl = '<?php echo BASE_URL; ?>';</script>
	<script src="<?php echo BASE_URL; ?>dist/js/script.js?rand=<?php echo rand(); ?>"></script>
</body>
</html>