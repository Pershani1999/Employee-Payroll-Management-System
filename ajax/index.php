<?php
include(dirname(dirname(__FILE__)) . '/config.php');

$case = $_GET['case'];
switch($case) {
	case 'LoginProcessHandler':
		LoginProcessHandler();
		break;
	case 'AttendanceProcessHandler':
		AttendanceProcessHandler();
		break;
	case 'LoadingAttendance':
		LoadingAttendance();
		break;
	
	case 'getAllEmployeeCodes':
		getAllEmployeeCodes();
		break;
		
	
	case 'LoadingSalaries':
		LoadingSalaries();
		break;
	case 'LoadingEmployees':
		LoadingEmployees();
		break;
	case 'AssignPayheadsToEmployee':
		AssignPayheadsToEmployee();
		break;
	case 'InsertUpdateDesignations':
		InsertUpdateDesignations();
		break;
	case 'GetDesignationByID':
		GetDesignationByID();
		break;
	case 'DeleteDesignationByID':
		DeleteDesignationByID();
		break;
	case 'LoadingDesignations':
		LoadingDesignations();
		break;
	case 'InsertUpdatePayheads':
		InsertUpdatePayheads();
		break;
	case 'GetPayheadByID':
		GetPayheadByID();
		break;
	case 'DeletePayheadByID':
		DeletePayheadByID();
		break;
	case 'LoadingPayheads':
		LoadingPayheads();
		break;
	case 'GetAllPayheadsExceptEmployeeHave':
		GetAllPayheadsExceptEmployeeHave();
		break;
	case 'GetEmployeePayheadsByID':
		GetEmployeePayheadsByID();
		break;
	case 'GetEmployeeByID':
		GetEmployeeByID();
		break;
	case 'DeleteEmployeeByID':
		DeleteEmployeeByID();
		break;
	case 'EditEmployeeDetailsByID':
		EditEmployeeDetailsByID();
		break;
	case 'GeneratePaySlip':
		GeneratePaySlip();
		break;
	case 'SendPaySlipByMail':
		SendPaySlipByMail();
		break;
	case 'EditProfileByID':
		EditProfileByID();
		break;
	case 'EditLoginDataByID':
		EditLoginDataByID();
		break;
	case 'LoadingAllloans':
		LoadingAllloans();
		break;
	case 'LoadingMyloans':
		LoadingMyloans();
		break;
	case 'ApplyloanToAdminApproval':
		ApplyloanToAdminApproval();
		break;
	case 'ApproveloanApplication':
		ApproveloanApplication();
		break;
	case 'RejectloanApplication':
		RejectloanApplication();
		break;
	case 'DeleteloanByID':
			DeleteloanByID();
			break;
	case 'LoadingAlladvances':
				LoadingAlladvances();
				break;
	case 'LoadingMyadvances':
				LoadingMyadvances();
				break;
	case 'ApplyadvanceToAdminApproval':
				ApplyadvanceToAdminApproval();
				break;
	case 'ApproveadvanceApplication':
				ApproveadvanceApplication();
				break;
	case 'RejectadvanceApplication':
				RejectadvanceApplication();
				break;
				case 'InsertPayments':
					InsertPayments();
					break;
					case 'LoadingPayments':
						LoadingPayments();
						break;
				
							
	default:
		echo '404! Page Not Found.';
		break;
}

function LoginProcessHandler() {
	$result = array();
	global $db;

	$code = addslashes($_POST['code']);
    $password = addslashes($_POST['password']);
    if ( !empty($code) && !empty($password) ) {
	    $adminCheck = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "admin` WHERE `admin_code` = '$code' AND `admin_password` = '" . sha1($password) . "' LIMIT 0, 1");
	    if ( $adminCheck ) {
	        if ( mysqli_num_rows($adminCheck) == 1 ) {
	            $adminData = mysqli_fetch_assoc($adminCheck);
	            $_SESSION['Admin_ID'] = $adminData['admin_id'];
	            $_SESSION['Login_Type'] = 'admin';
	            $result['result'] = BASE_URL . 'employees/';
			    		$result['code'] = 0;
	        } else {
	        	$empCheck = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "employees` WHERE `emp_code` = '$code' AND `emp_password` = '" . sha1($password) . "' LIMIT 0, 1");
			    if ( $empCheck ) {
			        if ( mysqli_num_rows($empCheck) == 1 ) {
			        	$empData = mysqli_fetch_assoc($empCheck);
			            $_SESSION['Admin_ID'] = $empData['emp_id'];
			            $_SESSION['Login_Type'] = 'emp';
			            $result['result'] = BASE_URL . 'profile/';
			    		$result['code'] = 0;
			        } else {
			        	$result['result'] = 'Invalid Login Details.';
			        	$result['code'] = 1;
			        }
			    } else {
			    	$result['result'] = 'Something went wrong, please try again.';
		    		$result['code'] = 2;
			    }
	        }
	    } else {
	    	$result['result'] = 'Something went wrong, please try again.';
		    $result['code'] = 2;
	    }
	} else {
		$result['result'] = 'Login Details should not be blank.';
		$result['code'] = 3;
	}

    echo json_encode($result);
}

function AttendanceProcessHandler() {
    global $userData, $db;
    $result = array();

    $emp_code = $userData['emp_code'];
    $attendance_date = date('Y-m-d');
    $month = date('Y-m');
    $attendanceSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "attendance` WHERE `emp_code` = '$emp_code' AND `attendance_date` = '$attendance_date'");
    
    if ($attendanceSQL) {
        $attendanceROW = mysqli_num_rows($attendanceSQL);
        if ($attendanceROW == 0) {
            $action_name = 'punchin';
        } else {
            $attendanceDATA = mysqli_fetch_assoc($attendanceSQL);
            if ($attendanceDATA['action_name'] == 'punchin') {
                $action_name = 'punchout';
            } else {
                $action_name = 'punchin';
            }
        }
    } else {
        $attendanceROW = 0;
        $action_name = 'punchin';
    }
    
    $action_time = date('H:i');
    $insertSQL = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "attendance`(`emp_code`, `attendance_date`, `action_name`, `action_time`) VALUES ('$emp_code', '$attendance_date', '$action_name', '$action_time')");
    
    if ($insertSQL) {
        // Update or Insert Gross Salary Information
        updateGrossSalary($emp_code, $month);

        $result['next'] = ($action_name == 'punchin' ? 'Punch Out' : 'Punch In');
        $result['complete'] = $attendanceROW + 1;
        $result['result'] = ($action_name == 'punchin' ? 'You have successfully punched in.' : 'You have successfully punched out.');
        $result['code'] = 0;
    } else {
        $result['result'] = 'Something went wrong, please try again.';
        $result['code'] = 1;
    }

    echo json_encode($result);
}

function updateGrossSalary($emp_code, $month) {
    global $db;

    // Calculate Work Days and Gross Salary for the given month
    $workDaysSQL = "SELECT COUNT(DISTINCT `attendance_date`) AS `work_days`, SUM(`daily_salary`) AS `gross_salary` 
                    FROM `" . DB_PREFIX . "attendance` 
                    WHERE `emp_code` = '$emp_code' AND DATE_FORMAT(`attendance_date`, '%Y-%m') = '$month'";
    $workDaysResult = mysqli_query($db, $workDaysSQL);
    $workDaysData = mysqli_fetch_assoc($workDaysResult);

    $work_days = $workDaysData['work_days'];
    $gross_salary = $workDaysData['gross_salary'];

    // Insert or Update the Gross Salary Table
    $grossSalarySQL = "INSERT INTO `wy_gross_salaries` (`emp_code`, `month`, `work_days`, `gross_salary`) 
                       VALUES ('$emp_code', '$month', '$work_days', '$gross_salary') 
                       ON DUPLICATE KEY UPDATE `work_days` = VALUES(`work_days`), `gross_salary` = VALUES(`gross_salary`)";
    mysqli_query($db, $grossSalarySQL);
}


function LoadingAttendance() {
	global $db;
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'attendance_date',
		1 => 'emp_code',
		2 => 'first_name',
		3 => 'last_name',
		4 => 'action_time',
	);

	$sql  = "SELECT `attendance_id`, `emp_code`, `attendance_date`, GROUP_CONCAT(`action_time`) AS `times` FROM `" . DB_PREFIX . "attendance` GROUP BY `emp_code`, `attendance_date`";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql  = "SELECT `emp`.`emp_code`, `emp`.`first_name`, `emp`.`last_name`, `emp`.`designation`, `att`.`attendance_id`, `att`.`attendance_date`, GROUP_CONCAT(`att`.`action_time`) AS `times`, `att`.`daily_salary`";
	$sql .= " FROM `" . DB_PREFIX . "employees` AS `emp`, `" . DB_PREFIX . "attendance` AS `att` WHERE `emp`.`emp_code` = `att`.`emp_code`";
	if ( !empty($requestData['search']['value']) ) {
		$sql .= " AND (`att`.`attendance_date` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR CONCAT(TRIM(`emp`.`first_name`), ' ', TRIM(`emp`.`last_name`)) LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `att`.`times` LIKE '" . $requestData['search']['value'] . "%'";
	}
	$sql .= " GROUP BY `emp`.`emp_code`, `att`.`attendance_date`";

	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ( $row = mysqli_fetch_assoc($query) ) {
		$nestedData = array();
		$nestedData[] = date('d-m-Y', strtotime($row['attendance_date']));
		$nestedData[] = $row["emp_code"];
		$nestedData[] = '<a target="_blank" href="' . REG_URL . 'reports/' . $row["emp_code"] . '/">' . $row["first_name"] . ' ' . $row["last_name"] . '</a>';
		$times = explode(',', $row["times"]);
		
		$nestedData[] = isset($times[0]) ? date('h:i A', strtotime($times[0])) : '';
		$nestedData[] = isset($times[1]) ? date('h:i A', strtotime($times[1])) : '';

		// Calculate Normal and OT hours
		$punchInTime = isset($times[0]) ? new DateTime($times[0]) : null;
		$punchOutTime = isset($times[1]) ? new DateTime($times[1]) : null;
		$cutoffTime = new DateTime($row['attendance_date'] . ' 16:30:00');

		$normalHours = 0;
		$otHours = 0;

		if ($punchInTime && $punchOutTime) {
			if ($punchOutTime <= $cutoffTime) {
				$interval = $punchInTime->diff($punchOutTime);
				$normalHours = $interval->h + ($interval->i/60);
			} else {
				$normalInterval = $punchInTime->diff($cutoffTime);
				$normalHours = $normalInterval->h + ($normalInterval->i/60);

				$otInterval = $cutoffTime->diff($punchOutTime);
				$otHours = $otInterval->h + ($otInterval->i/60);
			}
		}

		// Fetch hourly rates based on designation
		$designation = $row['designation'];
		$rateSQL = "SELECT `normal_rate`, `ot_rate` FROM `" . DB_PREFIX . "designations` WHERE `designation` = '$designation'";
		$rateResult = mysqli_query($db, $rateSQL);
		$rateRow = mysqli_fetch_assoc($rateResult);

		$normalRate = $rateRow['normal_rate'];
		$otRate = $rateRow['ot_rate'];

		// Calculate daily salary
		$dailySalary = ($normalHours * $normalRate) + ($otHours * $otRate);

		// Update attendance record with daily salary
		$updateSQL = "UPDATE `" . DB_PREFIX . "attendance` SET `daily_salary` = $dailySalary WHERE `attendance_id` = " . $row['attendance_id'];
		mysqli_query($db, $updateSQL);

		$workHours = "<b>Normal:</b> " . round($normalHours, 2) . " Hrs <br> <b>OT:</b> " . round($otHours, 2) . " Hrs";

		$nestedData[] = $workHours;
		$nestedData[] = "<b>Rs. </b>". round($dailySalary, 2); // Display daily salary in a separate column

		$data[] = $nestedData;
		$i++;
	}
	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);

	echo json_encode($json_data);
}


function LoadingSalaries() {
	global $db;
	$requestData = $_REQUEST;
	if ( $_SESSION['Login_Type'] == 'admin' ) {
		$columns = array(
			0 => 'emp_code',
			1 => 'first_name',
			2 => 'last_name',
			3 => 'pay_month',
			4 => 'earning_total',
			5 => 'deduction_total',
			6 => 'net_salary'
		);

		$sql  = "SELECT * FROM `" . DB_PREFIX . "salaries` GROUP BY `emp_code`, `pay_month`";
		$query = mysqli_query($db, $sql);
		$totalData = mysqli_num_rows($query);
		$totalFiltered = $totalData;

		$sql  = "SELECT `emp`.`emp_code`, `emp`.`first_name`, `emp`.`last_name`, `salary`.*";
		$sql .= " FROM `" . DB_PREFIX . "salaries` AS `salary`, `" . DB_PREFIX . "employees` AS `emp` WHERE `emp`.`emp_code` = `salary`.`emp_code`";
		if ( !empty($requestData['search']['value']) ) {
			$sql .= " AND (`salary`.`emp_code` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR CONCAT(TRIM(`emp`.`first_name`), ' ', TRIM(`emp`.`last_name`)) LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`pay_month` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`earning_total` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`otal` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`net_salary` LIKE '" . $requestData['search']['value'] . "%')";
		}
		$sql .= " GROUP BY `salary`.`emp_code`, `salary`.`pay_month`";

		$query = mysqli_query($db, $sql);
		$totalFiltered = mysqli_num_rows($query);

		$data = array();
		$i = 1 + $requestData['start'];
		while ( $row = mysqli_fetch_assoc($query) ) {
			$nestedData = array();
			$nestedData[] = $row['emp_code'];
			$nestedData[] = '<a target="_blank" href="' . REG_URL . 'reports/' . $row["emp_code"] . '/">' . $row["first_name"] . ' ' . $row["last_name"] . '</a>';
			$nestedData[] = $row['pay_month'];
			$nestedData[] = number_format($row['earning_total'], 2, '.', ',');
			$nestedData[] = number_format($row['deduction_total'], 2, '.', ',');
			$nestedData[] = number_format($row['net_salary'], 2, '.', ',');
			$nestedData[] = '<button type="button" class="btn btn-success btn-xs" onclick="openInNewTab(\'' . BASE_URL . 'payslips/' . $row['emp_code'] . '/' . str_replace(', ', '-', $row['pay_month']) . '/' . str_replace(', ', '-', $row['pay_month']) . '.pdf\');"><i class="fa fa-download"></i></button> <button type="button" class="btn btn-info btn-xs" onclick="sendPaySlipByMail(\'' . $row['emp_code'] . '\', \'' . $row['pay_month'] . '\');"><i class="fa fa-envelope"></i></button>';

			$data[] = $nestedData;
			$i++;
		}
	} else {
		$columns = array(
			0 => 'pay_month',
			1 => 'earning_total',
			2 => 'deduction_total',
			3 => 'net_salary'
		);
		$empData = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);
		$sql  = "SELECT * FROM `" . DB_PREFIX . "salaries` GROUP BY `emp_code`, `pay_month` WHERE `emp_code` = '" . $empData['emp_code'] . "'";
		$query = mysqli_query($db, $sql);
		$totalData = mysqli_num_rows($query);
		$totalFiltered = $totalData;

		$sql  = "SELECT `emp`.`emp_code`, `emp`.`first_name`, `emp`.`last_name`, `salary`.*";
		$sql .= " FROM `" . DB_PREFIX . "salaries` AS `salary`, `" . DB_PREFIX . "employees` AS `emp` WHERE `emp`.`emp_code` = `salary`.`emp_code` AND `salary`.`emp_code` = '" . $empData['emp_code'] . "'";
		if ( !empty($requestData['search']['value']) ) {
			$sql .= " AND (`salary`.`emp_code` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR CONCAT(TRIM(`emp`.`first_name`), ' ', TRIM(`emp`.`last_name`)) LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`pay_month` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`earning_total` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`deduction_total` LIKE '" . $requestData['search']['value'] . "%'";
			$sql .= " OR `salary`.`net_salary` LIKE '" . $requestData['search']['value'] . "%')";
		}
		$sql .= " GROUP BY `salary`.`emp_code`, `salary`.`pay_month`";

		$query = mysqli_query($db, $sql);
		$totalFiltered = mysqli_num_rows($query);

		$data = array();
		$i = 1 + $requestData['start'];
		while ( $row = mysqli_fetch_assoc($query) ) {
			$nestedData = array();
			$nestedData[] = $row['pay_month'];
			$nestedData[] = number_format($row['earning_total'], 2, '.', ',');
			$nestedData[] = number_format($row['deduction_total'], 2, '.', ',');
			$nestedData[] = number_format($row['net_salary'], 2, '.', ',');
			$nestedData[] = '<button type="button" class="btn btn-success btn-xs" onclick="openInNewTab(\'' . BASE_URL . 'payslips/' . $empData['emp_code'] . '/' . str_replace(', ', '-', $row['pay_month']) . '/' . str_replace(', ', '-', $row['pay_month']) . '.pdf\');"><i class="fa fa-download"></i></button>';

			$data[] = $nestedData;
			$i++;
		}
	}
	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);

	echo json_encode($json_data);
}

function LoadingEmployees() {
	global $db;
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'emp_code',
		1 => 'photo',
		2 => 'first_name',
		3 => 'last_name',
		4 => 'email',
		5 => 'mobile',
		
		6 => 'identity_no',
		7 => 'dob',
		8 => 'joining_date',
		9 => 'designation',
		
		
		
	);

	$sql  = "SELECT `emp_id` ";
	$sql .= " FROM `" . DB_PREFIX . "employees`";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql  = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "employees` WHERE 1 = 1";
	if ( !empty($requestData['search']['value']) ) {
		$sql .= " AND (`emp_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR CONCAT(TRIM(`first_name`), ' ', TRIM(`last_name`)) LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `email` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `mobile` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `identity_no` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `dob` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `joining_date` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `designation` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `` LIKE '" . $requestData['search']['value'] . "%'";
		
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ( $row = mysqli_fetch_assoc($query) ) {
		$nestedData = array();
		$nestedData[] = $row["emp_code"];
		$nestedData[] = '<img width="50" src="' . REG_URL . 'photos/' . $row["photo"] . '" alt="' . $row["emp_code"] . '" />';
		$nestedData[] = '<a target="_blank" href="' . REG_URL . 'reports/' . $row["emp_code"] . '/">' . $row["first_name"] . ' ' . $row["last_name"] . '</a>';
		$nestedData[] = $row["email"];
		$nestedData[] = $row["mobile"];
		$nestedData[] = $row["identity_no"];
		$nestedData[] = $row["dob"];
		$nestedData[] = $row["joining_date"];
		$nestedData[] = $row["designation"];
		
		
		
		$data[] = $nestedData;
		$i++;
	}
	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);

	echo json_encode($json_data);
}

function AssignPayheadsToEmployee() {
	$result = array();
	global $db;

	$payheads = $_POST['selected_payheads'];
	$default_salary = $_POST['pay_amounts'];
	$emp_code = $_POST['empcode'];
	$checkSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "pay_structure` WHERE `emp_code` = '$emp_code'");
	if ( $checkSQL ) {
		if ( !empty($payheads) && !empty($emp_code) ) {
			if ( mysqli_num_rows($checkSQL) == 0 ) {
				foreach ( $payheads as $payhead ) {
					mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "pay_structure`(`emp_code`, `payhead_id`, `default_salary`) VALUES ('$emp_code', $payhead, " . (!empty($default_salary[$payhead]) ? $default_salary[$payhead] : 0) . ")");
				}
				$result['result'] = 'Payheads are successfully assigned to employee.';
				$result['code'] = 0;
			} else {
				mysqli_query($db, "DELETE FROM `" . DB_PREFIX . "pay_structure` WHERE `emp_code` = '$emp_code'");
				foreach ( $payheads as $payhead ) {
					mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "pay_structure`(`emp_code`, `payhead_id`, `default_salary`) VALUES ('$emp_code', $payhead, " . (!empty($default_salary[$payhead]) ? $default_salary[$payhead] : 0) . ")");
				}
				$result['result'] = 'Payheads are successfully re-assigned to employee.';
				$result['code'] = 0;
			}
		} else {
			$result['result'] = 'Please select payheads and employee to assign.';
			$result['code'] = 2;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 1;
	}

	echo json_encode($result);
}

function InsertUpdateDesignations() {
	$result = array();
	global $db;

	$designation = stripslashes($_POST['designation']);
    $normal_rate = stripslashes($_POST['normal_rate']);
    $ot_rate = stripslashes($_POST['ot_rate']);
    
    if ( !empty($designation) && !empty($normal_rate) && !empty($ot_rate) ) {
	    if ( !empty($_POST['designation_id']) ) {
	    	$designation_id = addslashes($_POST['designation_id']);
	    	$updateDesignation = mysqli_query($db, "UPDATE `" . DB_PREFIX . "designations` SET `designation` = '$designation', `normal_rate` = '$normal_rate', `ot_rate` = '$ot_rate' WHERE `designation_id` = $designation_id");
		    if ( $updateDesignation ) {
		        $result['result'] = 'Designation record has been successfully updated.';
		        $result['code'] = 0;
		    } else {
		    	$result['result'] = 'Something went wrong, please try again.';
		    	$result['code'] = 1;
		    }
	    } else {
	    	$insertDesignation = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "designations`(`designation`, `normal_rate`, `ot_rate`) VALUES ('$designation', '$normal_rate', '$ot_rate')");
		    if ( $insertDesignation ) {
		        $result['result'] = 'Designation record has been successfully inserted.';
		        $result['code'] = 0;
		    } else {
		    	$result['result'] = 'Something went wrong, please try again.';
		    	$result['code'] = 1;
		    }
		}
	} else {
		$result['result'] = 'Designation details should not be blank.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function GetDesignationByID() {
	$result = array();
	global $db;

	$id = $_POST['id'];
	$desiSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "designations` WHERE `designation_id` = $id LIMIT 0, 1");
	if ( $desiSQL ) {
		if ( mysqli_num_rows($desiSQL) == 1 ) {
			$result['result'] = mysqli_fetch_assoc($desiSQL);
			$result['code'] = 0;
		} else {
			$result['result'] = 'Designation record is not found.';
			$result['code'] = 1;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function DeleteDesignationByID() {
    $result = array();
    global $db;

    $id = $_POST['id'];
    $desiSQL = mysqli_query($db, "DELETE FROM `" . DB_PREFIX . "designations` WHERE `designation_id` = $id");
    if ($desiSQL) {
        $result['result'] = 'Designation record is successfully deleted.';
        $result['code'] = 0;
    } else {
        $result['result'] = 'Something went wrong, please try again.';
        $result['code'] = 1;
    }

    echo json_encode($result);
}
     


function LoadingDesignations() {
	global $db;
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'designation_id',
		1 => 'designation',
		2 => 'normal_rate',
		3 => 'ot_rate',
		
	);

	$sql  = "SELECT `designation_id` ";
	$sql .= " FROM `" . DB_PREFIX . "designations`";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql  = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "designations` WHERE 1 = 1";
	if ( !empty($requestData['search']['value']) ) {
		$sql .= " AND (`designation_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `designation` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `normal_rate` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `ot_rate` LIKE '" . $requestData['search']['value'] . "%'";
		
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ( $row = mysqli_fetch_assoc($query) ) {
		$nestedData = array();
		$nestedData[] = $row["designation_id"];
		$nestedData[] = $row["designation"];
		$nestedData[] = $row["normal_rate"];
		$nestedData[] = $row["ot_rate"];
		
		$data[] = $nestedData;
		$i++;
	}
	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);

	echo json_encode($json_data);
}

function InsertUpdatePayheads() {
	$result = array();
	global $db;

	$payhead_name = stripslashes($_POST['payhead_name']);
    $payhead_desc = stripslashes($_POST['payhead_desc']);
    $payhead_type = stripslashes($_POST['payhead_type']);
    if ( !empty($payhead_name) && !empty($payhead_desc) && !empty($payhead_type) ) {
	    if ( !empty($_POST['payhead_id']) ) {
	    	$payhead_id = addslashes($_POST['payhead_id']);
	    	$updatePayhead = mysqli_query($db, "UPDATE `" . DB_PREFIX . "payheads` SET `payhead_name` = '$payhead_name', `payhead_desc` = '$payhead_desc', `payhead_type` = '$payhead_type' WHERE `payhead_id` = $payhead_id");
		    if ( $updatePayhead ) {
		        $result['result'] = 'Payhead record has been successfully updated.';
		        $result['code'] = 0;
		    } else {
		    	$result['result'] = 'Something went wrong, please try again.';
		    	$result['code'] = 1;
		    }
	    } else {
	    	$insertPayhead = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "payheads`(`payhead_name`, `payhead_desc`, `payhead_type`) VALUES ('$payhead_name', '$payhead_desc', '$payhead_type')");
		    if ( $insertPayhead ) {
		        $result['result'] = 'Payhead record has been successfully inserted.';
		        $result['code'] = 0;
		    } else {
		    	$result['result'] = 'Something went wrong, please try again.';
		    	$result['code'] = 1;
		    }
		}
	} else {
		$result['result'] = 'Payhead details should not be blank.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function GetPayheadByID() {
	$result = array();
	global $db;

	$id = $_POST['id'];
	$desiSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "payheads` WHERE `payhead_id` = $id LIMIT 0, 1");
	if ( $desiSQL ) {
		if ( mysqli_num_rows($desiSQL) == 1 ) {
			$result['result'] = mysqli_fetch_assoc($desiSQL);
			$result['code'] = 0;
		} else {
			$result['result'] = 'Payhead record is not found.';
			$result['code'] = 1;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function DeletePayheadByID() {
	$result = array();
	global $db;

	$id = $_POST['id'];
	$desiSQL = mysqli_query($db, "DELETE FROM `" . DB_PREFIX . "payheads` WHERE `payhead_id` = $id");
	if ( $desiSQL ) {
		$result['result'] = 'Payhead record is successfully deleted.';
		$result['code'] = 0;
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 1;
	}

	echo json_encode($result);
}

function LoadingPayheads() {
	global $db;
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'payhead_id',
		1 => 'payhead_name',
		2 => 'payhead_desc',
		3 => 'payhead_type'
	);

	$sql  = "SELECT `payhead_id` ";
	$sql .= " FROM `" . DB_PREFIX . "payheads`";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql  = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "payheads` WHERE 1 = 1";
	if ( !empty($requestData['search']['value']) ) {
		$sql .= " AND (`payhead_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `payhead_name` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `payhead_desc` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `payhead_type` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$arr = 1;
	$i = 1 + $requestData['start'];
	while ( $row = mysqli_fetch_assoc($query) ) {
		$nestedData = array();
		$nestedData[] = $arr;
		$nestedData[] = $row["payhead_name"];
		$nestedData[] = $row["payhead_desc"];
		if ( $row["payhead_type"] == 'earnings' ) {
			$nestedData[] = '<span class="label label-success">' . ucwords($row["payhead_type"]) . '</span>';
		} else {
			$nestedData[] = '<span class="label label-danger">' . ucwords($row["payhead_type"]) . '</span>';
		}
		$data[] = $nestedData;
		$i++;
		$arr++;
	}
	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);

	echo json_encode($json_data);
}

function GetAllPayheadsExceptEmployeeHave() {
	$result = array();
	global $db;

	$emp_code = $_POST['emp_code'];
	$salarySQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "payheads` WHERE `payhead_id` NOT IN (SELECT `payhead_id` FROM `" . DB_PREFIX . "pay_structure` WHERE `emp_code` = '$emp_code')");
	if ( $salarySQL ) {
		if ( mysqli_num_rows($salarySQL) > 0 ) {
			while ( $data = mysqli_fetch_assoc($salarySQL) ) {
				$result['result'][] = $data;
			}
			$result['code'] = 0;
		} else {
			$result['result'] = 'Salary record is not found.';
			$result['code'] = 1;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function GetEmployeePayheadsByID() {
	$result = array();
	global $db;

	$emp_code = $_POST['emp_code'];
	$salarySQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "pay_structure` AS `pay`, `" . DB_PREFIX . "payheads` AS `head` WHERE `head`.`payhead_id` = `pay`.`payhead_id` AND `pay`.`emp_code` = '$emp_code'");
	if ( $salarySQL ) {
		if ( mysqli_num_rows($salarySQL) > 0 ) {
			while ( $data = mysqli_fetch_assoc($salarySQL) ) {
				$result['result'][] = $data;
			}
			$result['code'] = 0;
		} else {
			$result['result'] = 'Salary record is not found.';
			$result['code'] = 1;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function GetEmployeeByID() {
	$result = array();
	global $db;

	$emp_code = $_POST['emp_code'];
	$empSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "employees` WHERE `emp_code` = '$emp_code' LIMIT 0, 1");
	if ( $empSQL ) {
		if ( mysqli_num_rows($empSQL) == 1 ) {
			$result['result'] = mysqli_fetch_assoc($empSQL);
			$result['code'] = 0;
		} else {
			$result['result'] = 'Employee record is not found.';
			$result['code'] = 1;
		}
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

function DeleteEmployeeByID() {
	$result = array();
	global $db;

	$emp_code = $_POST['emp_code'];
	$empSQL = mysqli_query($db, "DELETE FROM `" . DB_PREFIX . "employees` WHERE `emp_code` = '$emp_code'");
	if ( $empSQL ) {
		$result['result'] = 'Employee record is successfully deleted.';
		$result['code'] = 0;
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 1;
	}

	echo json_encode($result);
}

function EditEmployeeDetailsByID() {
	$result = array();
	global $db;

	$emp_id = stripslashes($_POST['emp_id']);
    $first_name = stripslashes($_POST['first_name']);
    $last_name = stripslashes($_POST['last_name']);
    $dob = stripslashes($_POST['dob']);
    $gender = stripslashes($_POST['gender']);
    $identity_no = stripslashes($_POST['identity_no']);
    
    $address = stripslashes($_POST['address']);
    
    $email = stripslashes($_POST['email']);
    $mobile = stripslashes($_POST['mobile']);
    
   
    $joining_date = stripslashes($_POST['joining_date']);
    
    $designation = stripslashes($_POST['designation']);
    
    
    $bank_name = stripslashes($_POST['bank_name']);
	$branch_name = stripslashes($_POST['branch_name']);
    $account_no = stripslashes($_POST['account_no']);
    
    $epf_account = stripslashes($_POST['epf_account']);
	$etf_account = stripslashes($_POST['etf_account']);
    if ( !empty($first_name) && !empty($last_name) && !empty($dob) && !empty($gender) && !empty($address) && !empty($email) && !empty($mobile) && !empty($identity_no) && !empty($joining_date) && !empty($designation) &&  !empty($bank_name) && !empty($branch_name) && !empty($account_no) && !empty($epf_account) && !empty($etf_account) ) {
    	$updateEmp = mysqli_query($db, "UPDATE `" . DB_PREFIX . "employees` SET `first_name` = '$first_name', `last_name` = '$last_name', `dob` = '$dob', `gender` = '$gender', `address` = '$address', `email` = '$email', `mobile` = '$mobile', `identity_no` = '$identity_no', `joining_date` = '$joining_date', `designation` = '$designation', `bank_name` = '$bank_name', `branch_name` = '$branch_name', `account_no` = '$account_no',`epf_account` = '$epf_account', `etf_account` = '$etf_account' WHERE `emp_id` = $emp_id");
	    if ( $updateEmp ) {
	        $result['result'] = 'Employee details has been successfully updated.';
	        $result['code'] = 0;
	    } else {
	    	$result['result'] = 'Something went wrong, please try again.';
	    	$result['code'] = 1;
	    }
	} else {
		$result['result'] = 'All fields are mandatory except telephone.';
		$result['code'] = 2;
	}

	echo json_encode($result);
}

/*function GeneratePaySlip() {
	global $mpdf, $db;
	$result = array();

	$emp_code = $_POST['emp_code'];
    $pay_month = $_POST['pay_month'];
    $earnings_heads = $_POST['earnings_heads'];
    $earnings_amounts = $_POST['earnings_amounts'];
    $deductions_heads = $_POST['deductions_heads'];
    $deductions_amounts = $_POST['deductions_amounts'];
    if ( !empty($emp_code) && !empty($pay_month) ) {
	    for ( $i = 0; $i < count($earnings_heads); $i++ ) {
	    	$checkSalSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "salaries` WHERE `emp_code` = '$emp_code' AND `payhead_name` = '" . $earnings_heads[$i] . "' AND `pay_month` = '$pay_month' AND `pay_type` = 'earnings' LIMIT 0, 1");
	    	if ( $checkSalSQL ) {
	    		if ( mysqli_num_rows($checkSalSQL) == 0 ) {
	    			mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "salaries`(`emp_code`, `payhead_name`, `pay_amount`, `earning_total`, `deduction_total`, `net_salary`, `pay_type`, `pay_month`, `generate_date`) VALUES ('$emp_code', '" . $earnings_heads[$i] . "', " . number_format($earnings_amounts[$i], 2, '.', '') . ", " . number_format(array_sum($earnings_amounts), 2, '.', '') . ", " . number_format(array_sum($deductions_amounts), 2, '.', '') . ", " . number_format((array_sum($earnings_amounts) - array_sum($deductions_amounts)), 2, '.', '') . ", 'earnings', '$pay_month', '" . date('Y-m-d H:i') . "')");
	    		}
	    	}
	    }
	    for ( $i = 0; $i < count($deductions_heads); $i++ ) {
	    	$checkSalSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "salaries` WHERE `emp_code` = '$emp_code' AND `payhead_name` = '" . $deductions_heads[$i] . "' AND `pay_month` = '$pay_month' AND `pay_type` = 'deductions' LIMIT 0, 1");
	    	if ( $checkSalSQL ) {
	    		if ( mysqli_num_rows($checkSalSQL) == 0 ) {
	    			mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "salaries`(`emp_code`, `payhead_name`, `pay_amount`, `earning_total`, `deduction_total`, `net_salary`, `pay_type`, `pay_month`, `generate_date`) VALUES ('$emp_code', '" . $deductions_heads[$i] . "', " . number_format($deductions_amounts[$i], 2, '.', '') . ", " . number_format(array_sum($earnings_amounts), 2, '.', '') . ", " . number_format(array_sum($deductions_amounts), 2, '.', '') . ", " . number_format((array_sum($earnings_amounts) - array_sum($deductions_amounts)), 2, '.', '') . ", 'deductions', '$pay_month', '" . date('Y-m-d H:i') . "')");
	    		}
	    	}
	    }
	    $empData = GetEmployeeDataByEmpCode($emp_code);
	    $empSalary = GetEmployeeSalaryByEmpCodeAndMonth($emp_code, $pay_month);
	   /* $emploan = GetEmployeeLWPDataByEmpCodeAndMonth($emp_code, $pay_month);
	   */
	    /*$totalEarnings = 0;
		$totalDeductions = 0;
		$html = '<style>
		@page{margin:20px 20px;font-family:Arial;font-size:14px;}
    	.div_half{float:left;margin:0 0 30px 0;width:50%;}
    	.logo{width:250px;padding:0;}
    	.com_title{text-align:center;font-size:16px;margin:0;}
    	.reg_no{text-align:center;font-size:12px;margin:5px 0;}
    	.subject{text-align:center;font-size:20px;font-weight:bold;}
    	.emp_info{width:100%;margin:0 0 30px 0;}
    	.table{border:1px solid #ccc;margin:0 0 30px 0;}
    	.salary_info{width:100%;margin:0;}
    	.salary_info th,.salary_info td{border:1px solid #ccc;margin:0;padding:5px;vertical-align:middle;}
    	.net_payable{margin:0;color:#050;}
    	.in_word{text-align:right;font-size:12px;margin:5px 0;}
    	.signature{margin:0 0 30px 0;}
    	.signature strong{font-size:12px;padding:5px 0 0 0;border-top:1px solid #000;}
    	.com_info{font-size:12px;text-align:center;margin:0 0 30px 0;}
    	.noseal{text-align:center;font-size:11px;}
	    </style>';
	    $html .= '<div class="div_half">';
	    $html .= '<img class="logo" src="' . BASE_URL . 'dist/img/cover.jpg" alt="Ceylon Engineering Works Holding Pvt Ltd" />';
	    $html .= '</div>';
	    $html .= '<div class="div_half">';
	    $html .= '<h2 class="com_title">Ceylon Engineering Works Holding Pvt Ltd</h2>';
	    $html .= '<p class="reg_no">Registration Number: 063838</p>';
	    $html .= '</div>';

	    $html .= '<p class="subject">Salary Slip for ' . $pay_month . '</p>';

	    $html .= '<table class="emp_info">';
	    $html .= '<tr>';
	    $html .= '<td width="25%">Employee Code</td>';
	    $html .= '<td width="25%">: ' . strtoupper($emp_code) . '</td>';
	    $html .= '<td width="25%">Bank Name</td>';
	    $html .= '<td width="25%">: ' . ucwords($empData['bank_name']) . '</td>';
	    $html .= '</tr>';

	    $html .= '<tr>';
	    $html .= '<td>Employee Name</td>';
	    $html .= '<td>: ' . ucwords($empData['first_name'] . ' ' . $empData['last_name']) . '</td>';
	    $html .= '<td>Branch Name</td>';
	    $html .= '<td>: ' . strtoupper($empData['branch_name']) . '</td>';
	    $html .= '</tr>';

	    $html .= '<tr>';
	    $html .= '<td>Designation</td>';
	    $html .= '<td>: ' . ucwords($empData['designation']) . '</td>';
	    $html .= '<td>Bank Account</td>';
	    $html .= '<td>: ' . $empData['account_no'] . '</td>';
	    $html .= '</tr>';

	    $html .= '<tr>';
	    $html .= '<td>Gender</td>';
	    $html .= '<td>: ' . ucwords($empData['gender']) . '</td>';
		$html .= '<td>EPF Account</td>';
	    $html .= '<td>: ' . strtoupper($empData['epf_account']) . '</td>';
	    $html .= '</tr>';

	    $html .= '<tr>';
	    
	    $html .= '<td>ETF Account</td>';
	    $html .= '<td>: ' . strtoupper($empData['etf_account']) . '</td>';
	    $html .= '</tr>';

	    $html .= '<tr>';
	    
	    
	   
	    $html .= '</tr>';

	    $html .= '<tr>';
	    $html .= '<td>Date of Joining</td>';
	    $html .= '<td>: ' . date('d-m-Y', strtotime($empData['joining_date'])) . '</td>';
	   
	    $html .= '</tr>';
	    $html .= '</table>';

		$html .= '<table class="table" cellspacing="0" cellpadding="0" width="100%">';
			$html .= '<thead>';
				$html .= '<tr>';
					$html .= '<th width="50%" valign="top">';
						$html .= '<table class="salary_info" cellspacing="0">';
							$html .= '<tr>';
								$html .= '<th align="left">Earnings</th>';
								$html .= '<th width="110" align="right">Amount (Rs.)</th>';
							$html .= '</tr>';
						$html .= '</table>';
					$html .= '</th>';
					$html .= '<th width="50%" valign="top">';
						$html .= '<table class="salary_info" cellspacing="0">';
							$html .= '<tr>';
								$html .= '<th align="left">Deductions</th>';
								$html .= '<th width="110" align="right">Amount (Rs.)</th>';
							$html .= '</tr>';
						$html .= '</table>';
					$html .= '</th>';
				$html .= '</tr>';
			$html .= '</thead>';

			if ( !empty($empSalary) ) {
				$html .= '<tr>';
					$html .= '<td width="50%" valign="top">';
						$html .= '<table class="salary_info" cellspacing="0">';
						foreach ( $empSalary as $salary ) {
							if ( $salary['pay_type'] == 'earnings' ) {
								$totalEarnings += $salary['pay_amount'];
								$html .= '<tr>';
									$html .= '<td align="left">';
										$html .= $salary['payhead_name'];
									$html .= '</td>';
									$html .= '<td width="110" align="right">';
										$html .= number_format($salary['pay_amount'], 2, '.', ',');
									$html .= '</td>';
								$html .= '</tr>';
							}
						}
						$html .= '</table>';
					$html .= '</td>';

					$html .= '<td width="50%" valign="top">';
						$html .= '<table class="salary_info" cellspacing="0">';
						foreach ( $empSalary as $salary ) {
							if ( $salary['pay_type'] == 'deductions' ) {
								$totalDeductions += $salary['pay_amount'];
								$html .= '<tr>';
									$html .= '<td align="left">';
										$html .= $salary['payhead_name'];
									$html .= '</td>';
									$html .= '<td width="110" align="right">';
										$html .= number_format($salary['pay_amount'], 2, '.', ',');
									$html .= '</td>';
								$html .= '</tr>';
							}
						}
						$html .= '</table>';
					$html .= '</td>';
				$html .= '</tr>';
			} else {
				$html .= '<tr>';
					$html .= '<td colspan="2" width="100%">No payheads are assigned for this employee</td>';
				$html .= '</tr>';
			}

			$html .= '<tr>';
				$html .= '<td width="50%" valign="top">';
					$html .= '<table class="salary_info" cellspacing="0">';
						$html .= '<tr>';
							$html .= '<td align="left">';
								$html .= '<strong>Total Earnings</strong>';
							$html .= '</td>';
							$html .= '<td width="110" align="right">';
								$html .= '<strong>' . number_format($totalEarnings, 2, '.', ',') . '</strong>';
							$html .= '</td>';
						$html .= '</tr>';
					$html .= '</table>';
				$html .= '</td>';
				$html .= '<td width="50%" valign="top">';
					$html .= '<table class="salary_info" cellspacing="0">';
						$html .= '<tr>';
							$html .= '<td align="left">';
								$html .= '<strong>Total Deductions</strong>';
							$html .= '</td>';
							$html .= '<td width="110" align="right">';
								$html .= '<strong>' . number_format($totalDeductions, 2, '.', ',') . '</strong>';
							$html .= '</td>';
						$html .= '</tr>';
					$html .= '</table>';
				$html .= '</td>';
			$html .= '</tr>';
		$html .= '</table>';

		$html .= '<div class="div_half">';
			$html .= '<h3 class="net_payable">';
				$html .= 'Net Salary Payable: Rs.' . number_format(($totalEarnings - $totalDeductions), 2, '.', ',');
			$html .= '</h3>';
		$html .= '</div>';
		$html .= '<div class="div_half">';
			$html .= '<h3 class="net_payable">';
				$html .= '<p class="in_word">(In words: ' . ucfirst(ConvertNumberToWords(($totalEarnings - $totalDeductions))) . ')</p>';
			$html .= '</h3>';
		$html .= '</div>';

		$html .= '<div class="signature">';
			$html .= '<table class="emp_info">';
				$html .= '<thead>';
					$html .= '<tr>';
						$html .= '<td>Date: ' . date('d-m-Y') . '</td>';
						$html .= '<th width="200">';
							$html .= '<img width="100" src="' . BASE_URL . 'dist/img/signature.png" alt="" /><br />';
							$html .= '<strong>Director</strong>';
						$html .= '</th>';
					$html .= '</tr>';
				$html .= '</thead>';
			$html .= '</table>';
		$html .= '</div>';

		$html .= '<p class="com_info">';
			$html .= 'No.15,<br/>';
			$html .= 'Yapahuwa Road,<br/>';
			$html .= 'Karambe,<br/>';
			$html .= 'Maho<br/>';
			$html .= 'www.cnc.co';
		$html .= '</p>';
		$html .= '<p class=""><small>Note: This is an electronically generated copy & therefore doesn’t require seal.</small></p>';

	    $mpdf->WriteHTML($html);
	    $pay_month = str_replace(', ', '-', $pay_month);
	    $payslip_path = dirname(dirname(__FILE__)) . '/payslips/';
	    if ( ! file_exists($payslip_path . $emp_code . '/') ) {
	    	mkdir($payslip_path . $emp_code, 0777);
	    }
	    if ( ! file_exists($payslip_path . $emp_code . '/' . $pay_month . '/') ) {
	    	mkdir($payslip_path . $emp_code . '/' . $pay_month, 0777);
	    }
		$mpdf->Output($payslip_path . $emp_code . '/' . $pay_month . '/' . $pay_month . '.pdf', 'F');
    	$result['code'] = 0;
    	$_SESSION['PaySlipMsg'] = $pay_month . ' PaySlip has been successfully generated for ' . $emp_code . '.';
    } else {
    	$result['code'] = 1;
    	$result['result'] = 'Something went wrong, please try again.';
    }

	echo json_encode($result);
}

/*function SendPaySlipByMail() {
	$result = array();
	global $db;

	$emp_code = $_POST['emp_code'];
	$month 	  = $_POST['month'];
	$empData  = GetEmployeeDataByEmpCode($emp_code);
	if ( $empData ) {
		$empName  = $empData['first_name'] . ' ' . $empData['last_name'];
		$empEmail = $empData['email'];
		$subject  = 'PaySlip for ' . $month;
		$message  = '<p>Hi ' . $empData['first_name'] . '</p>';
		$message .= '<p>Here is your attached Salary Slip for the period of ' . $month . '.</p>';
		$message .= '<hr/>';
		$message .= '<p>Thank You,<br/>Wisely Online Services Private Limited</p>';
		$attachment[0]['src'] = dirname(dirname(__FILE__)) . '/payslips/' . $emp_code . '/' . str_replace(', ', '-', $month) . '/' . str_replace(', ', '-', $month) . '.pdf';
		$attachment[0]['name'] = str_replace(', ', '-', $month);
		$send = Send_Mail($subject, $message, $empName, $empEmail, FALSE, FALSE, FALSE, FALSE, $attachment);
		if ( $send == 0 ) {
			$result['code'] = 0;
			$result['result'] = 'PaySlip for ' . $month . ' has been successfully send to ' . $empName;
		} else {
			$result['code'] = 1;
			$result['result'] = 'PaySlip is not send, please try again.';
		}
	} else {
		$result['code'] = 2;
		$result['result'] = 'No such employee found.';
	}

	echo json_encode($result);
}*/

function EditProfileByID() {
	$result = array();
	global $db;

	if ( $_SESSION['Login_Type'] == 'admin' ) {
		$admin_id = $_SESSION['Admin_ID'];
		$admin_name = addslashes($_POST['admin_name']);
		$admin_email = addslashes($_POST['admin_email']);
		if ( !empty($admin_name) && !empty($admin_email) ) {
			$editSQL = mysqli_query($db, "UPDATE `" . DB_PREFIX . "admin` SET `admin_name` = '$admin_name', `admin_email` = '$admin_email' WHERE `admin_id` = $admin_id");
			if ( $editSQL ) {
				$result['code'] = 0;
				$result['result'] = 'Profile data has been successfully updated.';
			} else {
				$result['code'] = 1;
				$result['result'] = 'Something went wrong, please try again.';
			}
		} else {
			$result['code'] = 2;
			$result['result'] = 'All fields are mandatory.';
		}
	} else {
		$emp_id = stripslashes($_SESSION['Admin_ID']);
	    $first_name = stripslashes($_POST['first_name']);
	    $last_name = stripslashes($_POST['last_name']);
	    $dob = stripslashes($_POST['dob']);
	    $gender = stripslashes($_POST['gender']);
	    $identity_no = stripslashes($_POST['identity_no']);

	    $address = stripslashes($_POST['address']);
	    
	    $email = stripslashes($_POST['email']);
	    $mobile = stripslashes($_POST['mobile']);
	    
	    
	  
	    $joining_date = stripslashes($_POST['joining_date']);
	   
	    $designation = stripslashes($_POST['designation']);
	    
	    
	    $bank_name = stripslashes($_POST['bank_name']);
		$branch_name = stripslashes($_POST['branch_name']);
	    $account_no = stripslashes($_POST['account_no']);
	   
	    $epf_account = stripslashes($_POST['epf_account']);
		$etf_account = stripslashes($_POST['etf_account']);
	    if ( !empty($first_name) && !empty($last_name) && !empty($dob) && !empty($gender) && !empty($address) && !empty($email) && !empty($mobile) && !empty($identity_no) && !empty($joining_date) && !empty($designation) && !empty($bank_name) && !empty($branch_name) && !empty($account_no) && !empty($epf_account) && !empty($etf_account) ) {
	    	$updateEmp = mysqli_query($db, "UPDATE `" . DB_PREFIX . "employees` SET `first_name` = '$first_name', `last_name` = '$last_name', `dob` = '$dob', `gender` = '$gender', `address` = '$address', `email` = '$email', `mobile` = '$mobile', `identity_no` = '$identity_no',`joining_date` = '$joining_date',  `designation` = '$designation', `bank_name` = '$bank_name',`branch_name` = '$branch_name', `account_no` = '$account_no', `epf_account` = '$epf_account', `etf_account` = '$etf_account' WHERE `emp_id` = $emp_id");
		    if ( $updateEmp ) {
		        $result['result'] = 'Profile data has been successfully updated.';
		        $result['code'] = 0;
		    } else {
		    	$result['result'] = 'Something went wrong, please try again.';
		    	$result['code'] = 1;
		    }
		} else {
			$result['result'] = 'All fields are mandatory except Telephone.';
			$result['code'] = 2;
		}
	}

	echo json_encode($result);
}

function EditLoginDataByID() {
	$result = array();
	global $db;

	if ( $_SESSION['Login_Type'] == 'admin' ) {
		$admin_id = $_SESSION['Admin_ID'];
		$admin_code = addslashes($_POST['admin_code']);
		$admin_password = addslashes($_POST['admin_password']);
		$admin_password_conf = addslashes($_POST['admin_password_conf']);
		if ( !empty($admin_code) && !empty($admin_password) && !empty($admin_password_conf) ) {
			if ( $admin_password == $admin_password_conf ) {
				$editSQL = mysqli_query($db, "UPDATE `" . DB_PREFIX . "admin` SET `admin_code` = '$admin_code', `admin_password` = '" . sha1($admin_password) . "' WHERE `admin_id` = $admin_id");
				if ( $editSQL ) {
					$result['code'] = 0;
					$result['result'] = 'Login data has been successfully updated.';
				} else {
					$result['code'] = 1;
					$result['result'] = 'Something went wrong, please try again.';
				}
			} else {
				$result['code'] = 2;
				$result['result'] = 'Confirm password does not match.';
			}
		} else {
			$result['code'] = 3;
			$result['result'] = 'All fields are mandatory.';
		}
	} else {
		$emp_id = $_SESSION['Admin_ID'];
		$old_password = addslashes($_POST['old_password']);
		$new_password = addslashes($_POST['new_password']);
		$password_conf = addslashes($_POST['password_conf']);
		if ( !empty($old_password) && !empty($new_password) && !empty($password_conf) ) {
			$checkPassSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "employees` WHERE `emp_id` = $emp_id");
			if ( $checkPassSQL ) {
				if ( mysqli_num_rows($checkPassSQL) == 1 ) {
					$passData = mysqli_fetch_assoc($checkPassSQL);
					if ( sha1($old_password) == $passData['emp_password'] ) {
						if ( $new_password == $password_conf ) {
							$editSQL = mysqli_query($db, "UPDATE `" . DB_PREFIX . "employees` SET `emp_password` = '" . sha1($new_password) . "' WHERE `emp_id` = $emp_id");
							if ( $editSQL ) {
								$result['code'] = 0;
								$result['result'] = 'Password has been successfully updated.';
							} else {
								$result['code'] = 1;
								$result['result'] = 'Something went wrong, please try again.';
							}
						} else {
							$result['code'] = 2;
							$result['result'] = 'Confirm password does not match.';
						}
					} else {
						$result['code'] = 3;
						$result['result'] = 'Entered wrong existing password.';
					}
				} else {
					$result['code'] = 4;
					$result['result'] = 'No such employee found.';
				}
			} else {
				$result['code'] = 5;
				$result['result'] = 'Something went wrong, please try again.';
			}
		} else {
			$result['code'] = 6;
			$result['result'] = 'All fields are mandatory.';
		}
	}

	echo json_encode($result);
}

function LoadingAllloans() {
	global $db;
	$empData = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'loan_id',
		1 => 'emp_code',
		2 => 'loan_subject',
		3 => 'loan_dates',
		
		4 => 'loan_type',
		5 => 'loan_status'
	);

	$sql  = "SELECT `loan_id` ";
	$sql .= " FROM `" . DB_PREFIX . "loans`";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql  = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "loans` WHERE 1=1";
	if ( !empty($requestData['search']['value']) ) {
		$sql .= " AND (`loan_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `emp_code` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `loan_subject` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `loan_dates` LIKE '" . $requestData['search']['value'] . "%'";
		
		$sql .= " OR `loan_type` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `loan_status` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ( $row = mysqli_fetch_assoc($query) ) {
		$nestedData = array();
		$nestedData[] = $row["loan_id"];
		$nestedData[] = '<a target="_blank" href="' . REG_URL . 'reports/' . $row["emp_code"] . '/">' . $row["emp_code"] . '</a>';
		$nestedData[] = $row["loan_subject"];
		$nestedData[] = $row["loan_dates"];
		
		$nestedData[] = $row["loan_type"];
		if ( $row["loan_status"] == 'pending' ) {
			$nestedData[] = '<span class="label label-warning">' . ucwords($row["loan_status"]) . '</span>';
		} elseif ( $row['loan_status'] == 'approve' ) {
			$nestedData[] = '<span class="label label-success">' . ucwords($row["loan_status"]) . 'd</span>';
		} elseif ( $row['loan_status'] == 'reject' ) {
			$nestedData[] = '<span class="label label-danger">' . ucwords($row["loan_status"]) . 'ed</span>';
		}
		$data[] = $nestedData;
		$i++;
	}
	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);

	echo json_encode($json_data);
}

function LoadingMyloans() {
	global $db;
	$empData = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'loan_id',
		1 => 'loan_subject',
		2 => 'loan_dates',
		
		3 => 'loan_type',
		4 => 'loan_status'
	);

	$sql  = "SELECT `loan_id` ";
	$sql .= " FROM `" . DB_PREFIX . "loans` WHERE `emp_code` = '" . $empData['emp_code'] . "'";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql  = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "loans` WHERE `emp_code` = '" . $empData['emp_code'] . "'";
	if ( !empty($requestData['search']['value']) ) {
		$sql .= " AND (`loan_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `loan_subject` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `loan_dates` LIKE '" . $requestData['search']['value'] . "%'";
		
		$sql .= " OR `loan_type` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `loan_status` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ( $row = mysqli_fetch_assoc($query) ) {
		$nestedData = array();
		$nestedData[] = $row["loan_id"];
		$nestedData[] = $row["loan_subject"];
		$nestedData[] = $row["loan_dates"];
		
		$nestedData[] = $row["loan_type"];
		if ( $row["loan_status"] == 'pending' ) {
			$nestedData[] = '<span class="label label-warning">' . ucwords($row["loan_status"]) . '</span>';
		} elseif ( $row['loan_status'] == 'approve' ) {
			$nestedData[] = '<span class="label label-success">' . ucwords($row["loan_status"]) . 'd</span>';
		} elseif ( $row['loan_status'] == 'reject' ) {
			$nestedData[] = '<span class="label label-danger">' . ucwords($row["loan_status"]) . 'ed</span>';
		}
		$data[] = $nestedData;
		$i++;
	}
	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);

	echo json_encode($json_data);
}

function ApplyloanToAdminApproval() {
	$result = array();
	global $db;

	$adminData = GetAdminData(1);
	$empData   = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);

	$loan_subject = addslashes($_POST['loan_subject']);
	$loan_dates   = addslashes($_POST['loan_dates']);
	
	$loan_type    = addslashes($_POST['loan_type']);
	if ( !empty($loan_subject) && !empty($loan_dates) && !empty($loan_type) ) {
		$AppliedDates = '';
		if ( strpos($loan_dates, ',') !== false ) {
			$dates = explode(',', $loan_dates);
			foreach ( $dates as $date ) {
				$checkloanSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "loans` WHERE `loan_dates` LIKE '%$date%' AND `emp_code` = '" . $empData['emp_code'] . "'");
				if ( $checkloanSQL ) {
					if ( mysqli_num_rows($checkloanSQL) > 0 ) {
						$AppliedDates .= $date . ', ';
					}
				}
			}
		}
		if ( empty($AppliedDates) ) {
			$loanSQL = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "loans` (`emp_code`, `loan_subject`, `loan_dates`, `loan_type`, `apply_date`) VALUES('" . $empData['emp_code'] . "', '$loan_subject', '$loan_dates', '$loan_type', '" . date('Y-m-d H:i') . "')");
			if ( $loanSQL ) {
				$empName    = $empData['first_name'] . ' ' . $empData['last_name'];
				$empEmail   = $empData['email'];
				$adminEmail = $adminData['admin_email'];
				$subject 	= 'loan Application: ' . $loan_subject;
				$message    = '<p>Employee: ' . $empName . ' (' . $empData['emp_code'] . ')' . '</p>';
				
				$message   .= '<p>loan Date(s): ' . $loan_dates . '</p>';
				$message   .= '<p>loan Type: ' . $loan_type . '</p>';
				$message   .= '<hr/>';
				$message   .= '<p>Please click on the buttons below or log into the admin area to get an action:</p>';
				$message   .= '<form method="post" action="' . BASE_URL . 'ajax/?case=ApproveloanApplication&id=' . mysqli_insert_id() . '" style="display:inline;">';
				$message   .= '<input type="hidden" name="id" value="' . mysqli_insert_id() . '" />';
				$message   .= '<button type="submit" style="background:green; border:1px solid green; color:white; padding:0 5px 3px; cursor:pointer; margin-right:15px;">Approve</button>';
				$message   .= '</form>';
				$message   .= '<form method="post" action="' . BASE_URL . 'ajax/?case=RejectloanApplication&id=' . mysqli_insert_id() . '" style="display:inline;">';
				$message   .= '<input type="hidden" name="id" value="' . mysqli_insert_id() . '" />';
				$message   .= '<button type="submit" style="background:red; border:1px solid red; color:white; padding:0 5px 3px; cursor:pointer;">Reject</button>';
				$message   .= '</form>';
				$message   .= '<p style="font-size:85%;">After clicking the button, please click on OK and then Continue to make your action complete.</p>';
				$message   .= '<hr/>';
				$message   .= '<p>Thank You<br/>' . $empName . '</p>';
				$adminName 	= $adminData['admin_name'];
				$send = Send_Mail($subject, $message, $adminName, $adminEmail, $empName, $empEmail);
				if ( $send == 0 ) {
					$result['code'] = 0;
					$result['result'] = 'loan Application has been successfully send to your employer through mail.';
				} else {
					$result['code'] = 1;
					$result['result'] = 'Notice: loan Application not send through E-Mail, please try again.';
				}
			} else {
				$result['code'] = 1;
				$result['result'] = 'Something went wrong, please try again.';
			}
		} else {
			$alreadyDates = substr($AppliedDates, 0, -2);
			$result['code'] = 2;
			$result['result'] = 'You have already applied for loan on ' . $alreadyDates . '. Please change the loan dates.';
		}
	} else {
		$result['code'] = 3;
		$result['result'] = 'All fields are mandatory.';
	}

	echo json_encode($result);
}

function ApproveloanApplication() {
	$result = array();
	global $db;

	$loanId = $_REQUEST['id'];
	$loanSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "loans` WHERE `loan_id` = $loanId AND `loan_status` = 'pending' LIMIT 0, 1");
	if ( $loanSQL ) {
		if ( mysqli_num_rows($loanSQL) == 1 ) {
			$loanData = mysqli_fetch_assoc($loanSQL);
			$update = mysqli_query($db, "UPDATE `" . DB_PREFIX . "loans` SET `loan_status` = 'approve' WHERE `loan_id` = $loanId");
			if ( $update ) {
				$empData  = GetEmployeeDataByEmpCode($loanData['emp_code']);
				if ( $empData ) {
					$empName  = $empData['first_name'] . ' ' . $empData['last_name'];
					$empEmail = $empData['email'];
					$subject  = 'loan Application Approved';
					$message  = '<p>Hi ' . $empData['first_name'] . '</p>';
					$message .= '<p>Your loan application is approved.</p>';
					$message .= '<p>Application Details:</p>';
					$message .= '<p>Subject: ' . $loanData['loan_subject'] . '</p>';
					$message .= '<p>loan Date(s): ' . $loanData['loan_dates'] . '</p>';
					
					$message .= '<p>loan Type: ' . $loanData['loan_type'] . '</p>';
					$message .= '<p>Status: ' . ucwords($loanData['loan_status']) . '</p>';
					$message .= '<hr/>';
					$message .= '<p>Thank You,<br/>Wisely Online Services Private Limited</p>';
					$send = Send_Mail($subject, $message, $empName, $empEmail);
					if ( $send == 0 ) {
						$result['code'] = 0;
						$result['result'] = 'loan Application is successfully approved. An email notification will be send to the employee.';
					} else {
						$result['code'] = 1;
						$result['result'] = 'loan Application is not approved, please try again.';
					}
				} else {
					$result['code'] = 2;
					$result['result'] = 'No such employee found.';
				}
			} else {
				$result['code'] = 1;
				$result['result'] = 'Something went wrong, please try again.';
			}
		} else {
			$result['code'] = 2;
			$result['result'] = 'This loan application is already verified.';
		}
	} else {
		$result['code'] = 3;
		$result['result'] = 'Something went wrong, please try again.';
	}

	echo json_encode($result);
}

function RejectloanApplication() {
	$result = array();
	global $db;

	$loanId = $_REQUEST['id'];
	$loanSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "loans` WHERE `loan_id` = $loanId AND `loan_status` = 'pending' LIMIT 0, 1");
	if ( $loanSQL ) {
		if ( mysqli_num_rows($loanSQL) == 1 ) {
			$loanData = mysqli_fetch_assoc($loanSQL);
			$update = mysqli_query($db, "UPDATE `" . DB_PREFIX . "loans` SET `loan_status` = 'reject' WHERE `loan_id` = $loanId");
			if ( $update ) {
				$empData  = GetEmployeeDataByEmpCode($loanData['emp_code']);
				if ( $empData ) {
					$empName  = $empData['first_name'] . ' ' . $empData['last_name'];
					$empEmail = $empData['email'];
					$subject  = 'loan Application Rejected';
					$message  = '<p>Hi ' . $empData['first_name'] . '</p>';
					$message .= '<p>Your loan application is rejected.</p>';
					$message .= '<p>Application Details:</p>';
					$message .= '<p>Subject: ' . $loanData['loan_subject'] . '</p>';
					$message .= '<p>loan Date(s): ' . $loanData['loan_dates'] . '</p>';
					
					$message .= '<p>loan Type: ' . $loanData['loan_type'] . '</p>';
					$message .= '<p>Status: ' . ucwords($loanData['loan_status']) . '</p>';
					$message .= '<hr/>';
					$message .= '<p>Thank You,<br/>Wisely Online Services Private Limited</p>';
					$send = Send_Mail($subject, $message, $empName, $empEmail);
					if ( $send == 0 ) {
						$result['code'] = 0;
						$result['result'] = 'loan Application is rejected. An email notification will be send to the employee.';
					} else {
						$result['code'] = 1;
						$result['result'] = 'loan Application is not rejected, please try again.';
					}
				} else {
					$result['code'] = 2;
					$result['result'] = 'No such employee found.';
				}
			} else {
				$result['code'] = 1;
				$result['result'] = 'Something went wrong, please try again.';
			}
		} else {
			$result['code'] = 2;
			$result['result'] = 'This loan application is already verified.';
		}
	} else {
		$result['code'] = 3;
		$result['result'] = 'Something went wrong, please try again.';
	}

	echo json_encode($result);
}
function DeleteloanByID() {
	$result = array();
	global $db;

	$id = $_POST['id'];
	$loanSQL = mysqli_query($db, "DELETE FROM `" . DB_PREFIX . "loans` WHERE `loan_id` = $id");
	if ( $loanSQL ) {
		$result['result'] = 'Loan record is successfully deleted.';
		$result['code'] = 0;
	} else {
		$result['result'] = 'Something went wrong, please try again.';
		$result['code'] = 1;
	}

	echo json_encode($result);
}



function LoadingAlladvances() {
	global $db;
	$empData = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'advance_id',
		1 => 'emp_code',
		2 => 'advance_subject',
		3 => 'advance_dates',
		
		4 => 'advance_type',
		5 => 'advance_status'
	);

	$sql  = "SELECT `advance_id` ";
	$sql .= " FROM `" . DB_PREFIX . "advances`";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql  = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "advances` WHERE 1=1";
	if ( !empty($requestData['search']['value']) ) {
		$sql .= " AND (`advance_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `emp_code` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `advance_subject` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `advance_dates` LIKE '" . $requestData['search']['value'] . "%'";
		
		$sql .= " OR `advance_type` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `advance_status` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ( $row = mysqli_fetch_assoc($query) ) {
		$nestedData = array();
		$nestedData[] = $row["advance_id"];
		$nestedData[] = '<a target="_blank" href="' . REG_URL . 'reports/' . $row["emp_code"] . '/">' . $row["emp_code"] . '</a>';
		$nestedData[] = $row["advance_subject"];
		$nestedData[] = $row["advance_dates"];
		
		$nestedData[] = $row["advance_type"];
		if ( $row["advance_status"] == 'pending' ) {
			$nestedData[] = '<span class="label label-warning">' . ucwords($row["advance_status"]) . '</span>';
		} elseif ( $row['advance_status'] == 'approve' ) {
			$nestedData[] = '<span class="label label-success">' . ucwords($row["advance_status"]) . 'd</span>';
		} elseif ( $row['advance_status'] == 'reject' ) {
			$nestedData[] = '<span class="label label-danger">' . ucwords($row["advance_status"]) . 'ed</span>';
		}
		$data[] = $nestedData;
		$i++;
	}
	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);

	echo json_encode($json_data);
}

function LoadingMyadvances() {
	global $db;
	$empData = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'advance_id',
		1 => 'advance_subject',
		2 => 'advance_dates',
		
		3 => 'advance_type',
		4 => 'advance_status'
	);

	$sql  = "SELECT `advance_id` ";
	$sql .= " FROM `" . DB_PREFIX . "advances` WHERE `emp_code` = '" . $empData['emp_code'] . "'";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	$sql  = "SELECT *";
	$sql .= " FROM `" . DB_PREFIX . "advances` WHERE `emp_code` = '" . $empData['emp_code'] . "'";
	if ( !empty($requestData['search']['value']) ) {
		$sql .= " AND (`advance_id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `advance_subject` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `advance_dates` LIKE '" . $requestData['search']['value'] . "%'";
		
		$sql .= " OR `advance_type` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `advance_status` LIKE '" . $requestData['search']['value'] . "%')";
	}
	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ( $row = mysqli_fetch_assoc($query) ) {
		$nestedData = array();
		$nestedData[] = $row["advance_id"];
		$nestedData[] = $row["advance_subject"];
		$nestedData[] = $row["advance_dates"];
		
		$nestedData[] = $row["advance_type"];
		if ( $row["advance_status"] == 'pending' ) {
			$nestedData[] = '<span class="label label-warning">' . ucwords($row["advance_status"]) . '</span>';
		} elseif ( $row['advance_status'] == 'approve' ) {
			$nestedData[] = '<span class="label label-success">' . ucwords($row["advance_status"]) . 'd</span>';
		} elseif ( $row['advance_status'] == 'reject' ) {
			$nestedData[] = '<span class="label label-danger">' . ucwords($row["advance_status"]) . 'ed</span>';
		}
		$data[] = $nestedData;
		$i++;
	}
	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);

	echo json_encode($json_data);
}

function ApplyadvanceToAdminApproval() {
	$result = array();
	global $db;

	$adminData = GetAdminData(1);
	$empData   = GetDataByIDAndType($_SESSION['Admin_ID'], $_SESSION['Login_Type']);

	$advance_subject = addslashes($_POST['advance_subject']);
	$advance_dates   = addslashes($_POST['advance_dates']);
	
	$advance_type    = addslashes($_POST['advance_type']);
	if ( !empty($advance_subject) && !empty($advance_dates) && !empty($advance_type) ) {
		$AppliedDates = '';
		if ( strpos($advance_dates, ',') !== false ) {
			$dates = explode(',', $advance_dates);
			foreach ( $dates as $date ) {
				$checkadvanceSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "advances` WHERE `advance_dates` LIKE '%$date%' AND `emp_code` = '" . $empData['emp_code'] . "'");
				if ( $checkadvanceSQL ) {
					if ( mysqli_num_rows($checkadvanceSQL) > 0 ) {
						$AppliedDates .= $date . ', ';
					}
				}
			}
		}
		if ( empty($AppliedDates) ) {
			$advanceSQL = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "advances` (`emp_code`, `advance_subject`, `advance_dates`, `advance_type`, `apply_date`) VALUES('" . $empData['emp_code'] . "', '$advance_subject', '$advance_dates', '$advance_type', '" . date('Y-m-d H:i') . "')");
			if ( $advanceSQL ) {
				$empName    = $empData['first_name'] . ' ' . $empData['last_name'];
				$empEmail   = $empData['email'];
				$adminEmail = $adminData['admin_email'];
				$subject 	= 'advance Application: ' . $advance_subject;
				$message    = '<p>Employee: ' . $empName . ' (' . $empData['emp_code'] . ')' . '</p>';
				
				$message   .= '<p>advance Date(s): ' . $advance_dates . '</p>';
				$message   .= '<p>advance Type: ' . $advance_type . '</p>';
				$message   .= '<hr/>';
				$message   .= '<p>Please click on the buttons below or log into the admin area to get an action:</p>';
				$message   .= '<form method="post" action="' . BASE_URL . 'ajax/?case=ApproveadvanceApplication&id=' . mysqli_insert_id() . '" style="display:inline;">';
				$message   .= '<input type="hidden" name="id" value="' . mysqli_insert_id() . '" />';
				$message   .= '<button type="submit" style="background:green; border:1px solid green; color:white; padding:0 5px 3px; cursor:pointer; margin-right:15px;">Approve</button>';
				$message   .= '</form>';
				$message   .= '<form method="post" action="' . BASE_URL . 'ajax/?case=RejectadvanceApplication&id=' . mysqli_insert_id() . '" style="display:inline;">';
				$message   .= '<input type="hidden" name="id" value="' . mysqli_insert_id() . '" />';
				$message   .= '<button type="submit" style="background:red; border:1px solid red; color:white; padding:0 5px 3px; cursor:pointer;">Reject</button>';
				$message   .= '</form>';
				$message   .= '<p style="font-size:85%;">After clicking the button, please click on OK and then Continue to make your action complete.</p>';
				$message   .= '<hr/>';
				$message   .= '<p>Thank You<br/>' . $empName . '</p>';
				$adminName 	= $adminData['admin_name'];
				$send = Send_Mail($subject, $message, $adminName, $adminEmail, $empName, $empEmail);
				if ( $send == 0 ) {
					$result['code'] = 0;
					$result['result'] = 'advance Application has been successfully send to your employer through mail.';
				} else {
					$result['code'] = 1;
					$result['result'] = 'Notice: advance Application not send through E-Mail, please try again.';
				}
			} else {
				$result['code'] = 1;
				$result['result'] = 'Something went wrong, please try again.';
			}
		} else {
			$alreadyDates = substr($AppliedDates, 0, -2);
			$result['code'] = 2;
			$result['result'] = 'You have already applied for advance on ' . $alreadyDates . '. Please change the advance dates.';
		}
	} else {
		$result['code'] = 3;
		$result['result'] = 'All fields are mandatory.';
	}

	echo json_encode($result);
}

function ApproveadvanceApplication() {
	$result = array();
	global $db;

	$advanceId = $_REQUEST['id'];
	$advanceSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "advances` WHERE `advance_id` = $advanceId AND `advance_status` = 'pending' LIMIT 0, 1");
	if ( $advanceSQL ) {
		if ( mysqli_num_rows($advanceSQL) == 1 ) {
			$advanceData = mysqli_fetch_assoc($advanceSQL);
			$update = mysqli_query($db, "UPDATE `" . DB_PREFIX . "advances` SET `advance_status` = 'approve' WHERE `advance_id` = $advanceId");
			if ( $update ) {
				$empData  = GetEmployeeDataByEmpCode($advanceData['emp_code']);
				if ( $empData ) {
					$empName  = $empData['first_name'] . ' ' . $empData['last_name'];
					$empEmail = $empData['email'];
					$subject  = 'advance Application Approved';
					$message  = '<p>Hi ' . $empData['first_name'] . '</p>';
					$message .= '<p>Your advance application is approved.</p>';
					$message .= '<p>Application Details:</p>';
					$message .= '<p>Subject: ' . $advanceData['advance_subject'] . '</p>';
					$message .= '<p>advance Date(s): ' . $advanceData['advance_dates'] . '</p>';
					
					$message .= '<p>advance Type: ' . $advanceData['advance_type'] . '</p>';
					$message .= '<p>Status: ' . ucwords($advanceData['advance_status']) . '</p>';
					$message .= '<hr/>';
					$message .= '<p>Thank You,<br/>Wisely Online Services Private Limited</p>';
					$send = Send_Mail($subject, $message, $empName, $empEmail);
					if ( $send == 0 ) {
						$result['code'] = 0;
						$result['result'] = 'advance Application is successfully approved. An email notification will be send to the employee.';
					} else {
						$result['code'] = 1;
						$result['result'] = 'advance Application is not approved, please try again.';
					}
				} else {
					$result['code'] = 2;
					$result['result'] = 'No such employee found.';
				}
			} else {
				$result['code'] = 1;
				$result['result'] = 'Something went wrong, please try again.';
			}
		} else {
			$result['code'] = 2;
			$result['result'] = 'This advance application is already verified.';
		}
	} else {
		$result['code'] = 3;
		$result['result'] = 'Something went wrong, please try again.';
	}

	echo json_encode($result);
}

function RejectadvanceApplication() {
	$result = array();
	global $db;

	$advanceId = $_REQUEST['id'];
	$advanceSQL = mysqli_query($db, "SELECT * FROM `" . DB_PREFIX . "advances` WHERE `advance_id` = $advanceId AND `advance_status` = 'pending' LIMIT 0, 1");
	if ( $advanceSQL ) {
		if ( mysqli_num_rows($advanceSQL) == 1 ) {
			$advanceData = mysqli_fetch_assoc($advanceSQL);
			$update = mysqli_query($db, "UPDATE `" . DB_PREFIX . "advances` SET `advance_status` = 'reject' WHERE `advance_id` = $advanceId");
			if ( $update ) {
				$empData  = GetEmployeeDataByEmpCode($advanceData['emp_code']);
				if ( $empData ) {
					$empName  = $empData['first_name'] . ' ' . $empData['last_name'];
					$empEmail = $empData['email'];
					$subject  = 'advance Application Rejected';
					$message  = '<p>Hi ' . $empData['first_name'] . '</p>';
					$message .= '<p>Your advance application is rejected.</p>';
					$message .= '<p>Application Details:</p>';
					$message .= '<p>Subject: ' . $advanceData['advance_subject'] . '</p>';
					$message .= '<p>advance Date(s): ' . $advanceData['advance_dates'] . '</p>';
					
					$message .= '<p>advance Type: ' . $advanceData['advance_type'] . '</p>';
					$message .= '<p>Status: ' . ucwords($advanceData['advance_status']) . '</p>';
					$message .= '<hr/>';
					$message .= '<p>Thank You,<br/>Wisely Online Services Private Limited</p>';
					$send = Send_Mail($subject, $message, $empName, $empEmail);
					if ( $send == 0 ) {
						$result['code'] = 0;
						$result['result'] = 'advance Application is rejected. An email notification will be send to the employee.';
					} else {
						$result['code'] = 1;
						$result['result'] = 'advance Application is not rejected, please try again.';
					}
				} else {
					$result['code'] = 2;
					$result['result'] = 'No such employee found.';
				}
			} else {
				$result['code'] = 1;
				$result['result'] = 'Something went wrong, please try again.';
			}
		} else {
			$result['code'] = 2;
			$result['result'] = 'This advance application is already verified.';
		}
	} else {
		$result['code'] = 3;
		$result['result'] = 'Something went wrong, please try again.';
	}

	echo json_encode($result);
}
function InsertPayments() {
	$result = array();
	global $db;

	$payment_date = stripslashes($_POST['payment_date']);
    $employee_code = stripslashes($_POST['employee_code']);
    $employee_name = stripslashes($_POST['employee_name']);
    $payment_type = stripslashes($_POST['payment_type']);
    $payment_amount = stripslashes($_POST['payment_amount']);

    if (!empty($payment_date) && !empty($employee_code) && !empty($employee_name) && !empty($payment_type) && !empty($payment_amount)) {
        $insertPayment = mysqli_query($db, "INSERT INTO `" . DB_PREFIX . "payments`(`payment_date`, `employee_code`, `employee_name`, `payment_type`, `payment_amount`) VALUES ('$payment_date', '$employee_code', '$employee_name', '$payment_type', '$payment_amount')");
        if ($insertPayment) {
            $result['result'] = 'Payment record has been successfully inserted.';
            $result['code'] = 0;
        } else {
            $result['result'] = 'Something went wrong, please try again.';
            $result['code'] = 1;
        }
    } else {
        $result['result'] = 'Payment details should not be blank.';
        $result['code'] = 2;
    }

    echo json_encode($result);
}

function LoadingPayments() {
	global $db;
	$requestData = $_REQUEST;
	$columns = array(
		0 => 'id',
		1 => 'employee_code',
		2 => 'employee_name',
		3 => 'payment_type',
		4 => 'payment_date',
		5 => 'payment_amount'
	);

	// Initial query to get total number of records without any search
	$sql  = "SELECT `id` ";
	$sql .= " FROM `wy_payments`";
	$query = mysqli_query($db, $sql);
	$totalData = mysqli_num_rows($query);
	$totalFiltered = $totalData;

	// Main query to fetch data
	$sql  = "SELECT *";
	$sql .= " FROM `wy_payments` WHERE 1 = 1";

	// Adding search condition if any
	if ( !empty($requestData['search']['value']) ) {
		$sql .= " AND (`id` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `employee_code` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `employee_name` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `payment_type` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `payment_date` LIKE '" . $requestData['search']['value'] . "%'";
		$sql .= " OR `payment_amount` LIKE '" . $requestData['search']['value'] . "%')";
	}

	$query = mysqli_query($db, $sql);
	$totalFiltered = mysqli_num_rows($query);

	// Adding order and limit condition
	$sql .= " ORDER BY " . $columns[$requestData['order'][0]['column']] . " " . $requestData['order'][0]['dir'] . " LIMIT " . $requestData['start'] . " ," . $requestData['length'] . "";
	$query = mysqli_query($db, $sql);

	$data = array();
	$i = 1 + $requestData['start'];
	while ( $row = mysqli_fetch_assoc($query) ) {
		$nestedData = array();
		$nestedData[] = $row["id"];
		$nestedData[] = $row["employee_code"];
		$nestedData[] = $row["employee_name"];
		$nestedData[] = $row["payment_type"];
		$nestedData[] = $row["payment_date"];
		$nestedData[] = $row["payment_amount"];
		
		$data[] = $nestedData;
		$i++;
	}

	$json_data = array(
		"draw"            => intval($requestData['draw']),
		"recordsTotal"    => intval($totalData),
		"recordsFiltered" => intval($totalFiltered),
		"data"            => $data
	);

	echo json_encode($json_data);
}
