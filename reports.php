<?php 
require_once(dirname(__FILE__) . '/config.php'); 
if (!isset($_SESSION['Admin_ID']) || !isset($_SESSION['Login_Type'])) {
    header('location:' . BASE_URL);
}
function fetchReportData($db, $query) {
    $result = mysqli_query($db, $query);
    if ($result) {
        return mysqli_fetch_all($result, MYSQLI_ASSOC);
    }
    return [];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Payroll</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>plugins/datatables/jquery.dataTables_themeroller.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/AdminLTE.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>dist/css/skins/_all-skins.min.css">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .report-container, .report-container * {
                visibility: visible;
            }
            .report-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                margin: 0;
            }
        }
        .form-container, .report-container {
            display: inline-block;
            vertical-align: top;
        }
        .form-container {
            width: 30%;
        }
        .report-container {
            width: 65%;
            margin-left: 5%;
        }
    </style>
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <?php require_once(dirname(__FILE__) . '/partials/topnav.php'); ?>
        <?php require_once(dirname(__FILE__) . '/partials/sidenav.php'); ?>
        
        <div class="content-wrapper">
            <section class="content-header">
                <h1>Reports</h1>
                <ol class="breadcrumb">
                    <li><a href="<?php echo BASE_URL; ?>"><i class="fa fa-dashboard"></i> Home</a></li>
                    <li class="active">Reports</li>
                </ol>
            </section>
            
            <section class="content">
                <div class="form-container no-print">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Generate Report</h3>
                        </div>
                        <div class="box-body">
                            <form method="GET" id="reportForm">
                                <div class="form-group">
                                    <label for="reportType">Select Report Type</label>
                                    <select class="form-control" id="reportType" name="reportType" required>
                                        <option value="">Select</option>
                                        <option value="Salary">Salary Report</option>
                                        <option value="Advance">Advance Payment Report</option>
                                        <option value="Loan">Loan Payment Report</option>
                                        <option value="EPF">EPF Payment Report</option>
                                        <option value="ETF">ETF Payment Report</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="startDate">Start Date</label>
                                    <input type="date" class="form-control" id="startDate" name="startDate" required>
                                </div>
                                <div class="form-group">
                                    <label for="endDate">End Date</label>
                                    <input type="date" class="form-control" id="endDate" name="endDate" required>
                                </div>
                                <button type="submit" class="btn btn-primary">Generate Report</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="report-container">
                    <div id="reportContent" class="box box-primary" style="display: none;">
                        <div class="box-header with-border">
                            <h3 class="box-title" id="reportTitle"></h3>
                        </div>
                        <div class="box-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped" id="reportTable">
                                    <thead id="reportTableHeader"></thead>
                                    <tbody id="reportTableBody"></tbody>
                                    <tfoot id="reportTableFooter"></tfoot>
                                </table>
                            </div>
                        </div>
                        <div class="box-footer">
                            <button class="btn btn-default no-print" onclick="window.print();">Print</button>
                        </div>
                    </div>
                </div>
            </section>
        </div>
        
        <footer class="main-footer no-print">
            <strong> Employee Payroll Management System</strong>
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
    <script>
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            e.preventDefault();
            generateReport();
        });

        function generateReport() {
            var reportType = document.getElementById('reportType').value;
            var startDate = document.getElementById('startDate').value;
            var endDate = document.getElementById('endDate').value;

            var query = `SELECT employee_code, employee_name, payment_date, payment_amount FROM wy_payments WHERE payment_type = '${reportType}' AND payment_date BETWEEN '${startDate}' AND '${endDate}'`;
            var columns = ["Employee Code", "Employee Name", "Payment Date", "Amount"];

            fetchReport(query, columns, reportType);
        }

        function fetchReport(query, columns, reportType) {
            $.ajax({
                url: baseurl + 'fetch_report.php',
                type: 'POST',
                data: { query: query },
                success: function(data) {
                    var result = JSON.parse(data);
                    displayReport(result, columns, reportType);
                }
            });
        }

        function displayReport(data, columns, reportType) {
    var reportTitle = document.getElementById('reportType').selectedOptions[0].text;
    document.getElementById('reportTitle').innerText = reportTitle;

    var header = document.getElementById('reportTableHeader');
    var body = document.getElementById('reportTableBody');
    var footer = document.getElementById('reportTableFooter');
    
    header.innerHTML = "";
    body.innerHTML = "";
    footer.innerHTML = "";

    // Create header row
    var headerRow = document.createElement('tr');
    columns.forEach(column => {
        var th = document.createElement('th');
        th.innerText = column;
        headerRow.appendChild(th);
    });
    header.appendChild(headerRow);

    var totalAmount = 0;
    data.forEach(row => {
        var tr = document.createElement('tr');
        columns.forEach(col => {
            var td = document.createElement('td');
            var key = col.toLowerCase().replace(" ", "_");
            td.innerText = row[key] !== undefined ? row[key] : '';
            tr.appendChild(td);
        });
        body.appendChild(tr);
        totalAmount += parseFloat(row['payment_amount']) || 0;
    });

    // Create footer row with total amount
    var footerRow = document.createElement('tr');
    footerRow.innerHTML = `<td colspan="${columns.length - 1}" class="text-right"><strong>Total</strong></td><td>${totalAmount.toFixed(2)}</td>`;
    footer.appendChild(footerRow);

    document.getElementById('reportContent').style.display = 'block';
}

    </script>
</body>
</html>
