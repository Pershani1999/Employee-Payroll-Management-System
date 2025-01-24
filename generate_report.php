<?php
require_once(dirname(__FILE__) . '/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reportType = $_POST['reportType'];
    $startDate = $_POST['startDate'];
    $endDate = $_POST['endDate'];

    $response = [
        'success' => false,
        'tableHead' => '',
        'tableBody' => ''
    ];

    switch ($reportType) {
        case 'attendance':
            $query = "SELECT * FROM wy_attendance WHERE date BETWEEN '$startDate' AND '$endDate'";
            $tableHead = '<tr><th>#</th><th>Employee Code</th><th>Date</th><th>Status</th></tr>';
            break;
        case 'advance':
            $query = "SELECT * FROM wy_payments WHERE payment_type = 'Advance' AND payment_date BETWEEN '$startDate' AND '$endDate'";
            $tableHead = '<tr><th>#</th><th>Employee Code</th><th>Date</th><th>Amount</th></tr>';
            break;
        case 'loan':
            $query = "SELECT * FROM wy_payments WHERE payment_type = 'Loan' AND payment_date BETWEEN '$startDate' AND '$endDate'";
            $tableHead = '<tr><th>#</th><th>Employee Code</th><th>Date</th><th>Amount</th></tr>';
            break;
        case 'epf':
            $query = "SELECT * FROM wy_payments WHERE payment_type = 'EPF' AND payment_date BETWEEN '$startDate' AND '$endDate'";
            $tableHead = '<tr><th>#</th><th>Employee Code</th><th>Date</th><th>Amount</th></tr>';
            break;
        case 'etf':
            $query = "SELECT * FROM wy_payments WHERE payment_type = 'ETF' AND payment_date BETWEEN '$startDate' AND '$endDate'";
            $tableHead = '<tr><th>#</th><th>Employee Code</th><th>Date</th><th>Amount</th></tr>';
            break;
        case 'salary':
            $query = "SELECT * FROM wy_payments WHERE payment_type = 'Salary' AND payment_date BETWEEN '$startDate' AND '$endDate'";
            $tableHead = '<tr><th>#</th
