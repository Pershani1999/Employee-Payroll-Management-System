<?php require(dirname(__FILE__) . '/config.php');

$errors 	= array();
$expensions = array("jpeg", "jpg", "png");
$target_dir = dirname(__FILE__) . "/photos/";

$designations = [];
$designationSQL = mysqli_query($db, "SELECT * FROM `wy_designations`");
if ($designationSQL) {
    while ($row = mysqli_fetch_assoc($designationSQL)) {
        $designations[] = $row;
    }
}

if ( isset($_POST['submit']) ) {

	$selectSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "employees` ORDER BY `emp_id` DESC LIMIT 0, 100");
	if ( $selectSQL ) {
		if ( mysqli_num_rows($selectSQL) > 0 ) {
			$LastEMP = mysqli_num_rows($selectSQL);
			$curEmpID = 'WY' . ($LastEMP < 10 ? sprintf("%02d", $LastEMP + 1) : $LastEMP + 1);
		} else {
			$curEmpID = 'WY01';
		}
	} else {
		$errors['database'] = '<span class="text-danger">Something went wrong, please contact to support team!</span>';
	}

	if ( empty($_POST['first_name']) ) {
		$errors['first_name'] = '<span class="text-danger">Please enter your first name!</span>';
	}
	if ( empty($_POST['last_name']) ) {
		$errors['last_name'] = '<span class="text-danger">Please enter your last name!</span>';
	}
	if ( empty($_POST['dob']) ) {
		$errors['dob'] = '<span class="text-danger">Please enter your date of birth!</span>';
	}
	if ( empty($_POST['gender']) ) {
		$errors['gender'] = '<span class="text-danger">Please select your gender!</span>';
	}
	if ( empty($_POST['id_no']) ) {
		$errors['id_no'] = '<span class="text-danger">Please enter your identification number!</span>';
	}
	
	if ( empty($_POST['address']) ) {
		$errors['address'] = '<span class="text-danger">Please enter your address!</span>';
	}
	
	if ( empty($_POST['email']) ) {
		$errors['email'] = '<span class="text-danger">Please enter your email id!</span>';
	}
	if ( empty($_POST['mobile']) ) {
		$errors['mobile'] = '<span class="text-danger">Please enter your mobile number!</span>';
	}
	

	if ( empty($_POST['joining_date']) ) {
		$errors['joining_date'] = '<span class="text-danger">Please enter your joining date!</span>';
	}
	if ( empty($_POST['designation']) ) {
		$errors['designation'] = '<span class="text-danger">Please enter your designation!</span>';
	}
	
	
	if ( empty($_POST['bank_name']) ) {
		$errors['bank_name'] = '<span class="text-danger">Please enter your bank_name!</span>';
	}
	if ( empty($_POST['branch_name']) ) {
		$errors['branch_name'] = '<span class="text-danger">Please enter your branch name!</span>';
	}
	if ( empty($_POST['account_no']) ) {
		$errors['account_no'] = '<span class="text-danger">Please enter your account no!</span>';
	}
	if ( empty($_POST['epf_account']) ) {
		$errors['epf_account'] = '<span class="text-danger">Please enter your epf account!</span>';
	}
	if ( empty($_POST['etf_account']) ) {
		$errors['etf_account'] = '<span class="text-danger">Please enter your etf account!</span>';
	}

	if ( empty($_POST['emp_password']) ) {
		$errors['emp_password'] = '<span class="text-danger">Please set employee password!</span>';
	} 
	else {
		$emp_password = addslashes($_POST['emp_password']);
	}
	
	if ( empty($_FILES['photo']['name']) ) {
		$errors['photo'] = '<span class="text-danger">Please upload your recent photograph!</span>';
	} else {
		$file_tmp 	= $_FILES['photo']['tmp_name'];
		$file_type 	= $_FILES['photo']['type'];
		$file_ext 	= strtolower(end(explode('.', $_FILES['photo']['name'])));

		$photocopy 	= $curEmpID . '.' . $file_ext;
		if ( in_array($file_ext, $expensions) === false ) {
		 	$errors['photo'] = '<span class="text-danger">Extension not allowed, please choose a JPEG or PNG file!</span>';
		}
	}

	if ( empty($errors) == true ) {
	 	if ( move_uploaded_file($file_tmp, $target_dir . $photocopy) ) {
			
	 		extract($_POST);
	 		$insertSQL = mysqli_query($db, "INSERT INTO " . DB_PREFIX . "employees(emp_code, first_name, last_name, dob, gender, address, email, mobile, identity_no,joining_date,designation,bank_name,branch_name,account_no,epf_account,etf_account,emp_password , photo, created) VALUES ('$curEmpID', '$first_name', '$last_name', '$dob', '$gender', '$address','$email', '$mobile','$id_no', '$joining_date','$designation','$bank_name','$branch_name','$account_no','$epf_account','$etf_account', '" . sha1($emp_password) . "' , '$photocopy', NOW())");    
	 		$_SESSION['success'] = '<p class="text-center"><span class="text-success">Employee registration successfully!</span></p>';
	 		header('location: http://localhost/payroll/employees/');
	 	} else {
	 		$errors['photo'] = '<span class="text-danger">Photo is not uploaded, please try again!</span>';
	 	}
	}
} ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
  	<meta http-equiv="X-UA-Compatible" content="IE=edge">
  	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

	<title>Employee Registration - Payroll</title>

	<link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
  	<link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
	<link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datepicker/datepicker3.css">

	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>

<body class="hold-transition register-page">
	<div class="container">
		<div class="register-box">
		  	<div class="register-logo">
		    	<a href="<?php echo BASE_URL; ?>"><b>Employee Payroll</b> Management</a>
		    	<small>Employee Registration Form</small>
		  	</div>
		</div>
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title">Fill the below form</h3>
				<div class="box-tools pull-right">
					<span class="text-red">All fields are mandatory</span>
				</div>
			</div>
			<form class="form-horizontal" method="post" enctype="multipart/form-data" novalidate="">
				<div class="box-body">
					<div class="form-group">
						<label for="first_name" class="col-sm-2 control-label">Full Name</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name" value="<?php echo $_POST['first_name']; ?>" required />
							<?php echo $errors['first_name']; ?>
						</div>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name" value="<?php echo $_POST['last_name']; ?>" required />
							<?php echo $errors['last_name']; ?>
						</div>
					</div>
					<div class="form-group">
						<label for="dob" class="col-sm-2 control-label">DOB</label>
						<div class="col-sm-5">
							<div class="input-group">
								<input type="text" class="form-control" id="dob" name="dob" placeholder="MM/DD/YYYY" value="<?php echo $_POST['dob']; ?>" required />
								<span class="input-group-addon">
									<i class="glyphicon glyphicon-calendar"></i>
								</span>
							</div>
							<?php echo $errors['dob']; ?>
						</div>
					</div>
			        <div class="form-group">
				        <label class="col-xs-2 control-label">Gender</label>
				        <div class="col-xs-10">
				            <div class="btn-group" data-toggle="buttons">
				                <label class="btn btn-default <?php echo $_POST['gender']=='male' ? 'active' : ''; ?>">
				                    <input type="radio" name="gender" value="male" <?php echo $_POST['gender']=='male' ? 'checked' : ''; ?> required /> Male
				                </label>
				                <label class="btn btn-default <?php echo $_POST['gender']=='female' ? 'active' : ''; ?>">
				                    <input type="radio" name="gender" value="female" <?php echo $_POST['gender']=='female' ? 'checked' : ''; ?> required /> Female
				                </label>
				            </div><br />
				            <?php echo $errors['gender']; ?>
				        </div>
				    </div>
					<div class="form-group">
						<label for="id_no" class="col-sm-2 control-label">Id Number</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="id_no" name="id_no" placeholder="Identification No" value="<?php echo $_POST['id_no']; ?>" required />
							<?php echo $errors['id_no']; ?>
						</div>
					</div>
					
					<hr />
					<div class="form-group">
						<label for="address" class="col-sm-2 control-label">Address</label>
						<div class="col-sm-10">
							<textarea class="form-control" id="address" name="address" placeholder="Address" required><?php echo $_POST['address']; ?></textarea>
							<?php echo $errors['address']; ?>
						</div>
					</div>
					
					<div class="form-group">
						<label for="email" class="col-sm-2 control-label">Email Id</label>
						<div class="col-sm-10">
							<input type="email" class="form-control" id="email" name="email" placeholder="Email Id" value="<?php echo $_POST['email']; ?>" required />
							<?php echo $errors['email']; ?>
						</div>
					</div>
					<div class="form-group">
						<label for="mobile" class="col-sm-2 control-label">Contact No</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="mobile" name="mobile" placeholder="Mobile No" value="<?php echo $_POST['mobile']; ?>" required />
							<?php echo $errors['mobile']; ?>
						</div>
						
					</div>
					
					
					<hr />
					<div class="form-group">
						<label for="joining_date" class="col-sm-2 control-label">Joining Date</label>
						<div class="col-sm-5">
							<div class="input-group">
								<input type="text" class="form-control" id="joining_date" name="joining_date" placeholder="MM/DD/YYYY" value="<?php echo $_POST['joining_date']; ?>" required />
								<span class="input-group-addon">
									<i class="glyphicon glyphicon-calendar"></i>
								</span>
							</div>
							<?php echo $errors['joining_date']; ?>
						</div>
					</div>
					<div class="form-group">
                        <label for="designation" class="col-sm-2 control-label">Designation</label>
                        <div class="col-sm-5">
                            <select class="form-control" name="designation" id="designation" required>
                                <option value="">Select Designation</option>
                                <?php foreach ($designations as $designation) { ?>
                                    <option value="<?php echo $designation['designation']; ?>"><?php echo $designation['designation']; ?></option>
                                <?php } ?>
                            </select>
                            <?php echo $errors['designation']; ?>
                        </div>
                    </div>
					
					<hr />

					<div class="form-group">
						<label for="bank_name" class="col-sm-2 control-label">Bank Name</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Name" value="<?php echo $_POST['bank_name']; ?>" required />
							<?php echo $errors['bank_name']; ?>
						</div>
						
					</div>
					<div class="form-group">
						<label for="branch_name" class="col-sm-2 control-label">Branch Name</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="branch_name" name="branch_name" placeholder="Branch Name" value="<?php echo $_POST['branch_name']; ?>" required />
							<?php echo $errors['branch_name']; ?>
						</div>
						
					</div>
					<div class="form-group">
						<label for="account_no" class="col-sm-2 control-label">Account no</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="account_no" name="account_no" placeholder="Account no" value="<?php echo $_POST['account_no']; ?>" required />
							<?php echo $errors['account_no']; ?>
						</div>
						
					</div>
					<div class="form-group">
						<label for="epf_account" class="col-sm-2 control-label">EPF A/C No.</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="epf_account" name="epf_account" placeholder="EPF A/C No." value="<?php echo $_POST['epf_account']; ?>" required />
							<?php echo $errors['epf_account']; ?>
						</div>
						
					</div>
					<div class="form-group">
						<label for="etf_account" class="col-sm-2 control-label">ETF A/C No.</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="etf_account" name="etf_account" placeholder="ETF A/C No." value="<?php echo $_POST['etf_account']; ?>" required />
							<?php echo $errors['etf_account']; ?>
						</div>
						
					</div>

					<div class="form-group">
						<label for="photo" class="col-sm-2 control-label">Photograph</label>
						<div class="col-sm-10">
							<input type="file" class="form-control" id="photo" name="photo" accept="image/*" placeholder="Photograph" required style="height:auto" />
							<?php echo $errors['photo']; ?>
						</div>
					</div>
					<div class="form-group">
						<label for="password" class="col-sm-2 control-label">Password</label>
						<div class="col-sm-4">
							<input type="password" class="form-control" id="emp_password" name="emp_password" placeholder="Password" value="<?php echo $_POST['emp_password']; ?>" required />
							<?php echo $errors['emp_password']; ?>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<div class="col-sm-offset-2 col-sm-10">
						<button type="submit" class="btn btn-primary" name="submit">Submit</button>
						<a href="http://localhost/payroll/employees.php" class="btn btn-primary pull-right">Cancel</a>
					</div>
				</div>
			</form>
		</div>
	</div>

	<script src="<?php echo BASE_URL; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
	<script src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo BASE_URL; ?>plugins/datepicker/bootstrap-datepicker.js"></script>
	<script type="text/javascript">
	$('#dob, #joining_date').datepicker();
	</script>
</body>
</html>
