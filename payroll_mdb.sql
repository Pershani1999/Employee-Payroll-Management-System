-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 05, 2024 at 04:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `payroll_mdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `wy_admin`
--

CREATE TABLE `wy_admin` (
  `admin_id` int(11) NOT NULL,
  `admin_code` varchar(255) NOT NULL,
  `admin_name` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL,
  `admin_password` varchar(255) NOT NULL,
  `admin_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wy_admin`
--

INSERT INTO `wy_admin` (`admin_id`, `admin_code`, `admin_name`, `admin_email`, `admin_password`, `admin_time`) VALUES
(1, 'WY00', 'Admin', 'admin@gmail.com', '7c4a8d09ca3762af61e59520943dc26494f8941b', '2019-04-18 02:22:37');

-- --------------------------------------------------------

--
-- Table structure for table `wy_advances`
--

CREATE TABLE `wy_advances` (
  `advance_id` int(11) NOT NULL,
  `emp_code` varchar(255) NOT NULL,
  `advance_subject` varchar(255) NOT NULL,
  `advance_dates` varchar(255) NOT NULL,
  `advance_type` varchar(255) NOT NULL,
  `advance_status` enum('pending','approve','reject') NOT NULL DEFAULT 'pending',
  `apply_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wy_advances`
--

INSERT INTO `wy_advances` (`advance_id`, `emp_code`, `advance_subject`, `advance_dates`, `advance_type`, `advance_status`, `apply_date`) VALUES
(1, 'WY01', 'odc', '08/07/2024', 'Casual advance', 'reject', '2024-07-27 15:43:32'),
(2, 'WY01', 'odc', '08/07/2024', 'Casual advance', 'approve', '2024-07-27 15:43:33');

-- --------------------------------------------------------

--
-- Table structure for table `wy_attendance`
--

CREATE TABLE `wy_attendance` (
  `attendance_id` int(11) NOT NULL,
  `emp_code` varchar(255) NOT NULL,
  `attendance_date` date NOT NULL,
  `action_name` enum('punchin','punchout') NOT NULL,
  `action_time` time NOT NULL,
  `daily_salary` float(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wy_attendance`
--

INSERT INTO `wy_attendance` (`attendance_id`, `emp_code`, `attendance_date`, `action_name`, `action_time`, `daily_salary`) VALUES
(10, 'WY01', '2024-08-01', 'punchin', '16:41:00', 1595.83),
(11, 'WY01', '2024-08-01', 'punchout', '21:40:00', 1346.67),
(13, 'WY01', '2024-08-03', 'punchin', '12:04:00', 11766.67),
(14, 'WY04', '2024-08-03', 'punchin', '12:17:00', 5245.83),
(15, 'WY01', '2024-08-03', 'punchout', '15:25:00', 0.00),
(16, 'WY04', '2024-08-03', 'punchout', '17:30:00', 0.00),
(17, 'WY04', '2024-08-05', 'punchin', '05:12:00', 641.67),
(18, 'WY04', '2024-08-05', 'punchout', '07:46:00', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `wy_designations`
--

CREATE TABLE `wy_designations` (
  `designation_id` int(11) NOT NULL,
  `designation` varchar(20) NOT NULL,
  `normal_rate` float(11,2) NOT NULL,
  `ot_rate` float(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wy_designations`
--

INSERT INTO `wy_designations` (`designation_id`, `designation`, `normal_rate`, `ot_rate`) VALUES
(1, 'CEO', 300.00, 350.00),
(2, 'Manager', 250.00, 300.00),
(3, 'Accountant', 250.00, 300.00),
(4, 'Technician', 220.00, 250.00),
(5, 'Labor', 100.00, 150.00),
(6, 'Director', 200.00, 250.00),
(8, 'Supervisor', 200.00, 230.00),
(9, 'Assistant', 250.00, 300.00);

-- --------------------------------------------------------

--
-- Table structure for table `wy_employees`
--

CREATE TABLE `wy_employees` (
  `emp_id` int(11) NOT NULL,
  `emp_code` varchar(255) NOT NULL,
  `emp_password` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `dob` varchar(255) NOT NULL,
  `gender` enum('male','female') NOT NULL DEFAULT 'male',
  `address` longtext NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `identity_no` varchar(255) NOT NULL,
  `joining_date` varchar(255) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `account_no` varchar(255) NOT NULL,
  `epf_account` varchar(255) NOT NULL,
  `etf_account` varchar(255) NOT NULL,
  `created` datetime NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wy_employees`
--

INSERT INTO `wy_employees` (`emp_id`, `emp_code`, `emp_password`, `first_name`, `last_name`, `dob`, `gender`, `address`, `email`, `mobile`, `identity_no`, `joining_date`, `photo`, `designation`, `bank_name`, `branch_name`, `account_no`, `epf_account`, `etf_account`, `created`) VALUES
(1, 'WY01', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Pershani', 'Sewwandi', '05/20/1999', 'female', 'Kosgahaela, Makulpotha', 'gayan@gmail.com', '0762954899', '996410161V', '07/01/2024', 'WY01.jpg', 'Assistant', 'Bank Of Ceylon', 'Ibbagamuwa', '87241', 'e18867', 't39433', '2024-07-16 10:27:03'),
(2, 'WY02', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Gayan', 'Madusanka', '03/24/1992', 'male', 'Rambe, Maeliya', 'gayan@gmail.com', '0712942846', '921847429v', '07/01/2024', 'WY02.jpeg', 'Assistant', 'Peoples Bank', 'Ibbagamuwa', '20718402644', 'e18867', 't83742', '2024-07-28 10:31:30'),
(3, 'WY03', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Manoj', 'Chathuranga', '05/20/1999', 'male', 'Koswaththa, Wariyapola', 'manoj@gmail.com', '0713891955', '993718341v', '07/14/2024', 'WY03.jpeg', 'Assistant', 'Bank Of Ceylon', 'Wariyapola', '87241', 'e13421', '088666', '2024-07-28 13:23:57'),
(4, 'WY04', '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', 'Tiruni', 'Niwanthika', '03/17/1999', 'female', 'Yatawaththa, Mathale', 'tiruni@gmail.com', '0719923749', '993718341v', '05/07/2024', 'WY04.jpg', 'Accountant', 'Bank Of Ceylon', 'Mathale', '992144', '9283992', '012133', '2024-08-03 12:16:41');

-- --------------------------------------------------------

--
-- Table structure for table `wy_gross_salaries`
--

CREATE TABLE `wy_gross_salaries` (
  `id` int(11) NOT NULL,
  `emp_code` varchar(50) NOT NULL,
  `month` varchar(7) NOT NULL,
  `work_days` int(11) NOT NULL DEFAULT 0,
  `gross_salary` decimal(10,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `wy_gross_salaries`
--

INSERT INTO `wy_gross_salaries` (`id`, `emp_code`, `month`, `work_days`, `gross_salary`) VALUES
(1, 'WY01', '2024-08', 2, 2942.50),
(5, 'WY04', '2024-08', 2, 5245.83);

-- --------------------------------------------------------

--
-- Table structure for table `wy_loans`
--

CREATE TABLE `wy_loans` (
  `loan_id` int(11) NOT NULL,
  `emp_code` varchar(255) NOT NULL,
  `loan_subject` varchar(255) NOT NULL,
  `loan_dates` varchar(255) NOT NULL,
  `loan_type` varchar(255) NOT NULL,
  `loan_status` enum('pending','approve','reject') NOT NULL DEFAULT 'pending',
  `apply_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wy_loans`
--

INSERT INTO `wy_loans` (`loan_id`, `emp_code`, `loan_subject`, `loan_dates`, `loan_type`, `loan_status`, `apply_date`) VALUES
(1, 'WY01', 'Education loan', '07/22/2024', 'Casual loan', 'approve', '2024-07-16 10:52:47'),
(2, 'WY01', 'lkk', '07/27/2024', 'Casual loan', 'approve', '2024-07-27 20:36:05'),
(3, 'WY04', 'emergancy', '08/05/2024', 'Rs:50000', 'reject', '2024-08-05 05:26:00');

-- --------------------------------------------------------

--
-- Table structure for table `wy_payheads`
--

CREATE TABLE `wy_payheads` (
  `payhead_id` int(11) NOT NULL,
  `payhead_name` varchar(255) NOT NULL,
  `payhead_desc` varchar(255) NOT NULL,
  `payhead_type` enum('earnings','deductions') NOT NULL DEFAULT 'earnings'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wy_payheads`
--

INSERT INTO `wy_payheads` (`payhead_id`, `payhead_name`, `payhead_desc`, `payhead_type`) VALUES
(4, 'Gross Salary', 'we', 'earnings'),
(5, 'EPF', 'Monthly rate', 'deductions'),
(6, 'ETF', 'Monthly deduct', 'deductions'),
(7, 'Bonus', 'new year', 'earnings'),
(8, 'loan installment', 'interest free', 'deductions');

-- --------------------------------------------------------

--
-- Table structure for table `wy_payments`
--

CREATE TABLE `wy_payments` (
  `id` int(11) NOT NULL,
  `payment_date` date NOT NULL,
  `employee_code` varchar(50) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `payment_type` varchar(50) NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wy_payments`
--

INSERT INTO `wy_payments` (`id`, `payment_date`, `employee_code`, `employee_name`, `payment_type`, `payment_amount`) VALUES
(1, '2024-08-02', 'WY03', 'Manoj Chathuranga', 'Advance', 290.00),
(2, '2024-08-03', 'WY03', 'Manoj Chathuranga', 'Advance', 500.00),
(3, '2024-07-31', 'WY01', 'Pershani Sewwandi', 'EPF', 43800.00),
(4, '2024-08-05', 'WY04', 'Tiruni Niwanthika', 'Loan', 50000.00);

-- --------------------------------------------------------

--
-- Table structure for table `wy_pay_structure`
--

CREATE TABLE `wy_pay_structure` (
  `salary_id` int(11) NOT NULL,
  `emp_code` varchar(255) NOT NULL,
  `payhead_id` int(11) NOT NULL,
  `default_salary` float(11,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wy_pay_structure`
--

INSERT INTO `wy_pay_structure` (`salary_id`, `emp_code`, `payhead_id`, `default_salary`) VALUES
(3, 'WY01', 5, 2000.00),
(4, 'WY01', 4, 50000.00),
(5, 'WY01', 7, 5000.00),
(8, 'WY02', 4, 150000.00),
(9, 'WY02', 8, 3000.00),
(10, 'WY04', 8, 5000.00);

-- --------------------------------------------------------

--
-- Table structure for table `wy_salaries`
--

CREATE TABLE `wy_salaries` (
  `salary_id` int(11) NOT NULL,
  `emp_code` varchar(255) NOT NULL,
  `payhead_name` varchar(255) NOT NULL,
  `pay_amount` float(11,2) NOT NULL,
  `earning_total` float(11,2) NOT NULL,
  `deduction_total` float(11,2) NOT NULL,
  `net_salary` float(11,2) NOT NULL,
  `pay_type` enum('earnings','deductions') NOT NULL,
  `pay_month` varchar(255) NOT NULL,
  `generate_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `wy_salaries`
--

INSERT INTO `wy_salaries` (`salary_id`, `emp_code`, `payhead_name`, `pay_amount`, `earning_total`, `deduction_total`, `net_salary`, `pay_type`, `pay_month`, `generate_date`) VALUES
(1, 'WY01', 'Gross Salary', 50000.00, 55000.00, 2000.00, 53000.00, 'earnings', 'August, 2024', '2024-08-04 23:20:00'),
(2, 'WY01', 'Bonus', 5000.00, 55000.00, 2000.00, 53000.00, 'earnings', 'August, 2024', '2024-08-04 23:20:00'),
(4, 'WY02', 'Gross Salary', 150000.00, 150000.00, 3000.00, 147000.00, 'earnings', 'August, 2024', '2024-08-04 23:47:00'),
(5, 'WY02', 'loan installment', 3000.00, 150000.00, 3000.00, 147000.00, 'deductions', 'August, 2024', '2024-08-04 23:47:00'),
(6, 'WY01', 'Gross Salary', 50000.00, 55000.00, 2000.00, 53000.00, 'earnings', 'July, 2024', '2024-08-05 05:09:00'),
(7, 'WY01', 'Bonus', 5000.00, 55000.00, 2000.00, 53000.00, 'earnings', 'July, 2024', '2024-08-05 05:09:00'),
(8, 'WY01', 'EPF', 2000.00, 55000.00, 2000.00, 53000.00, 'deductions', 'July, 2024', '2024-08-05 05:09:00'),
(9, 'WY01', 'Gross Salary', 50000.00, 55000.00, 2000.00, 53000.00, 'earnings', 'January, 2025', '2024-08-05 06:12:00'),
(10, 'WY01', 'Bonus', 5000.00, 55000.00, 2000.00, 53000.00, 'earnings', 'January, 2025', '2024-08-05 06:12:00'),
(11, 'WY01', 'EPF', 2000.00, 55000.00, 2000.00, 53000.00, 'deductions', 'January, 2025', '2024-08-05 06:12:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `wy_admin`
--
ALTER TABLE `wy_admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_email` (`admin_email`),
  ADD UNIQUE KEY `admin_code` (`admin_code`);

--
-- Indexes for table `wy_advances`
--
ALTER TABLE `wy_advances`
  ADD PRIMARY KEY (`advance_id`);

--
-- Indexes for table `wy_attendance`
--
ALTER TABLE `wy_attendance`
  ADD PRIMARY KEY (`attendance_id`);

--
-- Indexes for table `wy_designations`
--
ALTER TABLE `wy_designations`
  ADD PRIMARY KEY (`designation_id`);

--
-- Indexes for table `wy_employees`
--
ALTER TABLE `wy_employees`
  ADD PRIMARY KEY (`emp_id`);

--
-- Indexes for table `wy_gross_salaries`
--
ALTER TABLE `wy_gross_salaries`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `emp_code_month` (`emp_code`,`month`);

--
-- Indexes for table `wy_loans`
--
ALTER TABLE `wy_loans`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `wy_payheads`
--
ALTER TABLE `wy_payheads`
  ADD PRIMARY KEY (`payhead_id`);

--
-- Indexes for table `wy_payments`
--
ALTER TABLE `wy_payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wy_pay_structure`
--
ALTER TABLE `wy_pay_structure`
  ADD PRIMARY KEY (`salary_id`);

--
-- Indexes for table `wy_salaries`
--
ALTER TABLE `wy_salaries`
  ADD PRIMARY KEY (`salary_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `wy_admin`
--
ALTER TABLE `wy_admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wy_advances`
--
ALTER TABLE `wy_advances`
  MODIFY `advance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wy_attendance`
--
ALTER TABLE `wy_attendance`
  MODIFY `attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `wy_designations`
--
ALTER TABLE `wy_designations`
  MODIFY `designation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `wy_employees`
--
ALTER TABLE `wy_employees`
  MODIFY `emp_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wy_gross_salaries`
--
ALTER TABLE `wy_gross_salaries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `wy_loans`
--
ALTER TABLE `wy_loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wy_payheads`
--
ALTER TABLE `wy_payheads`
  MODIFY `payhead_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `wy_payments`
--
ALTER TABLE `wy_payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `wy_pay_structure`
--
ALTER TABLE `wy_pay_structure`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `wy_salaries`
--
ALTER TABLE `wy_salaries`
  MODIFY `salary_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
