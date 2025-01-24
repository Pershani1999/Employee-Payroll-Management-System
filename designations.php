<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once(dirname(__FILE__) . '/config.php'); 
if ( !isset($_SESSION['Admin_ID']) || !isset($_SESSION['Login_Type']) ) {
   	header('location:' . BASE_URL);
} ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<title>Designations - Payroll</title>

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/jquery.dataTables_themeroller.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/iCheck/all.css">
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
				<h1>Designations</h1>
				<ol class="breadcrumb">
					<li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
					<li class="active">Designations</li>
				</ol>
			</section>

			<section class="content">
				<div class="row">
        			<div class="col-xs-12">
						<div class="box">
							<div class="box-header">
								<h3 class="box-title">List of Designations</h3>
								
									<button type="button" class="btn btn-xs btn-primary pull-right" data-toggle="modal" data-target="#DesignationModal">
										<i class="fa fa-plus"></i> Add Designation
									</button>
								
							</div>
							<div class="box-body">
								<div class="table-responsiove">
									
										<table id="designations" class="table table-bordered table-striped">
											<thead>
												<tr>
													<th class="text-center">Designation #</th>
													<th class="text-center">Designation</th>
													
													<th class="text-center">Normal Rate</th>
													<th class="text-center">OT Rate</th>
													<th class="text-center">ACTION</th>
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

		
			<div class="modal fade in" id="DesignationModal" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-label="Close">
								<span aria-hidden="true">&times;</span>
							</button>
							<h4 class="modal-title">Designations</h4>
						</div>
						<form method="post" role="form" data-toggle="validator" id="designation-form">
							<div class="modal-body">
								<div class="form-group">
									<label for="designation">Designation</label>
									<input type="text" class="form-control" id="designation" name="designation" placeholder="Designation " required />
								</div>
								<div class="form-group">
									<label for="normal_rate">Normal Rate</label>
									<textarea class="form-control" id="normal_rate" name="normal_rate" placeholder="Normal rate" required></textarea>
								</div>
								<div class="form-group">
									<label for="ot_rate">OT Rate</label>
									<textarea class="form-control" id="ot_rate" name="ot_rate" placeholder="OT rate" required></textarea>
								</div>
					        </div>
						
							<div class="modal-footer">
								<input type="hidden" name="designation_id" id="designation_id" />
								<button type="submit" name="submit" class="btn btn-primary">Save Designation</button>
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