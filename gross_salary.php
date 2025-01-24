<?php 
require(dirname(__FILE__) . '/config.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <title>Gross Salary List - Payroll</title>

    <link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">

    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
    </style>

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition register-page">
    <div class="container">
        <div class="register-box">
        </div>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Gross Salary List</h3>
            </div>
            <div class="box-body">
                <div class="table-responsive">
                    <table id="gross_salary" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">#</th>
                                <th class="text-center">EMP CODE</th>
                                <th class="text-center">MONTH</th>
                                <th class="text-center">WORK DAYS</th>
                                <th class="text-center">GROSS SALARY</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            $sql = "SELECT * FROM `wy_gross_salaries` ORDER BY `emp_code`, `month` DESC";
                            $query = mysqli_query($db, $sql);
                            if ($query && mysqli_num_rows($query) > 0) {
                                $i = 1;
                                while ($row = mysqli_fetch_assoc($query)) {
                                    echo "<tr>";
                                    echo "<td class='text-center'>{$i}</td>";
                                    echo "<td class='text-center'>{$row['emp_code']}</td>";
                                    echo "<td class='text-center'>{$row['month']}</td>";
                                    echo "<td class='text-center'>{$row['work_days']}</td>";
                                    echo "<td class='text-center'>Rs. " . number_format($row['gross_salary'], 2) . "</td>";
                                    echo "</tr>";
                                    $i++;
                                }
                            } else {
                                echo "<tr><td colspan='5' class='text-center'>No records found</td></tr>";
                            }
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo BASE_URL; ?>plugins/jQuery/jquery-2.2.3.min.js"></script>
    <script src="<?php echo BASE_URL; ?>bootstrap/js/bootstrap.min.js"></script>
</body>
</html>
