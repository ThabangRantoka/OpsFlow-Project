-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 13, 2026 at 09:20 PM
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
-- Database: `opsflow`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(150) NOT NULL,
  `action` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`id`, `user_id`, `fullname`, `action`, `created_at`) VALUES
(1, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-07-27 13:23:31'),
(2, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-07-27 13:23:40'),
(3, 1, 'Moyahabo Thabang Rantoka', 'Added employee: cjindnijfiejfiur', '2026-07-27 13:24:38'),
(4, 1, 'Moyahabo Thabang Rantoka', 'Added department: Science', '2026-07-27 13:25:06'),
(5, 1, 'Moyahabo Thabang Rantoka', 'Created project: iejfdkemf m cn dcwkdnkemfkowmdkoemfk', '2026-07-27 13:25:39'),
(6, 1, 'Moyahabo Thabang Rantoka', 'Created project: vmcjidnve  rc je cjj n vejcokemfkemd', '2026-07-27 13:26:23'),
(7, 1, 'Moyahabo Thabang Rantoka', 'Created project: NNNN', '2026-07-27 13:27:11'),
(8, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-07-27 13:27:27'),
(9, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-07-27 13:27:37'),
(10, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-07-27 20:18:13'),
(11, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-07-30 15:07:01'),
(12, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-07-30 15:09:02'),
(13, 6, 'stoes', 'Logged into the system', '2026-07-30 15:10:25'),
(14, 6, 'stoes', 'Logged out of the system', '2026-07-30 15:11:31'),
(15, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-07-30 15:11:54'),
(16, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-07-30 15:12:21'),
(17, 6, 'stoes', 'Logged into the system', '2026-07-31 05:56:30'),
(18, 6, 'stoes', 'Logged out of the system', '2026-08-01 17:18:58'),
(19, 6, 'stoes', 'Logged into the system', '2026-08-01 17:19:01'),
(20, 6, 'stoes', 'Logged out of the system', '2026-08-01 22:53:55'),
(21, 6, 'stoes', 'Logged into the system', '2026-08-01 22:54:33'),
(22, 6, 'stoes', 'Logged out of the system', '2026-08-01 22:54:42'),
(23, 7, 'kat', 'Logged into the system', '2026-08-01 22:54:47'),
(24, 7, 'kat', 'Logged out of the system', '2026-08-02 13:15:25'),
(25, 8, 'lebo', 'Logged into the system', '2026-08-02 13:17:05'),
(26, 8, 'lebo', 'Logged out of the system', '2026-08-02 13:27:10'),
(27, 9, 'kamo', 'Logged into the system', '2026-08-02 13:28:10'),
(28, 9, 'kamo', 'Logged out of the system', '2026-08-03 21:47:42'),
(29, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-08-03 21:48:21'),
(30, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-08-03 21:48:32'),
(31, 6, 'stoes', 'Logged into the system', '2026-08-03 21:49:03'),
(32, 6, 'stoes', 'Logged out of the system', '2026-08-04 04:29:59'),
(33, 10, 'Nozipho', 'Logged into the system', '2026-08-04 04:36:05'),
(34, 10, 'Nozipho', 'Logged out of the system', '2026-08-04 04:37:45'),
(35, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-08-04 04:42:22'),
(36, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-08-04 17:48:06'),
(37, 6, 'stoes', 'Logged into the system', '2026-08-04 19:18:11'),
(38, 6, 'stoes', 'Logged out of the system', '2026-08-04 19:18:33'),
(39, 6, 'stoes', 'Logged into the system', '2026-08-04 19:18:35'),
(40, 6, 'stoes', 'Logged out of the system', '2026-08-04 19:18:42'),
(41, 6, 'stoes', 'Logged into the system', '2026-08-04 19:19:00'),
(42, 6, 'stoes', 'Logged out of the system', '2026-08-04 19:33:36'),
(43, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-08-04 19:33:49'),
(44, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-08-04 19:33:59'),
(45, 11, 'Leto', 'Logged into the system', '2026-08-04 19:34:45'),
(46, 11, 'Leto', 'Added employee: Moyahabo Thabang Rantoka', '2026-08-04 19:36:22'),
(47, 11, 'Leto', 'Logged out of the system', '2026-08-04 19:36:44'),
(48, 6, 'stoes', 'Logged into the system', '2026-08-04 19:36:50'),
(49, 6, 'stoes', 'Logged out of the system', '2026-08-04 19:36:58'),
(50, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-08-04 19:37:02'),
(51, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-08-04 19:37:44'),
(52, 11, 'Leto', 'Logged into the system', '2026-08-04 19:37:49'),
(53, 11, 'Leto', 'Logged out of the system', '2026-08-04 19:54:51'),
(54, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-08-04 19:54:56'),
(55, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-08-04 19:55:39'),
(56, 6, 'stoes', 'Logged into the system', '2026-08-04 19:55:44'),
(57, 6, 'stoes', 'Logged out of the system', '2026-08-04 20:05:15'),
(58, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-08-04 20:05:20'),
(59, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-08-04 20:20:30'),
(60, 11, 'Leto', 'Logged into the system', '2026-08-04 20:20:40'),
(61, 11, 'Leto', 'Logged out of the system', '2026-08-04 20:21:08'),
(62, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-08-04 20:21:15'),
(63, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-08-04 20:31:14'),
(64, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-08-04 20:31:17'),
(65, 1, 'Moyahabo Thabang Rantoka', 'Logged out of the system', '2026-08-04 20:31:26'),
(66, 11, 'Leto', 'Logged into the system', '2026-08-04 20:31:30'),
(67, 11, 'Leto', 'Logged out of the system', '2026-08-04 20:31:52'),
(68, 11, 'Leto', 'Logged into the system', '2026-08-04 20:38:56'),
(69, 11, 'Leto', 'Logged into the system', '2026-08-04 20:41:09'),
(70, 11, 'Leto', 'Logged out of the system', '2026-08-04 20:41:31'),
(71, 11, 'Leto', 'Logged into the system', '2026-08-04 20:41:35'),
(72, 11, 'Leto', 'Logged out of the system', '2026-08-04 20:41:45'),
(73, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-08-04 20:41:49'),
(74, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-08-13 06:38:24'),
(75, 1, 'Moyahabo Thabang Rantoka', 'Logged into the system', '2026-08-13 06:38:26');

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `status` enum('Present','Absent','Late','Leave') NOT NULL,
  `check_in` time DEFAULT NULL,
  `check_out` time DEFAULT NULL,
  `remarks` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `employee_id`, `attendance_date`, `status`, `check_in`, `check_out`, `remarks`, `created_at`) VALUES
(1, 3, '2026-07-01', 'Present', '07:00:00', '17:00:00', 'good', '2026-07-26 17:37:31');

-- --------------------------------------------------------

--
-- Table structure for table `clients`
--

CREATE TABLE `clients` (
  `id` int(11) NOT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `contact_person` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `department_name` varchar(100) NOT NULL,
  `department_code` varchar(20) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `department_name`, `department_code`, `description`, `created_at`) VALUES
(1, 'Information Technology', 'IT', 'NBBEFBEHBCHB B BHR R', '2026-07-26 15:11:42'),
(2, 'Computer systems eng', 'DPYE21', 'jbhbhbrhfbv', '2026-07-26 15:15:25'),
(3, 'Science', 'SCE', 'Health', '2026-07-27 13:25:06');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `employee_number` varchar(20) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `gender` enum('Male','Female') NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `department` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `status` enum('Active','Inactive','Leave') DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `employee_number`, `fullname`, `gender`, `email`, `phone`, `department`, `position`, `salary`, `hire_date`, `status`, `created_at`) VALUES
(2, 'EMP001', 'Thabang Dev', 'Male', 'thabangrantyyoka@gmail.com', '0763649531', 'IT', 'Software Developer', 160000.00, '2026-07-18', 'Active', '2026-07-26 10:27:32'),
(3, 'EMP002', 'KABELO', 'Male', 'thabangrAantoka@gmail.com', '0763649533', 'Information Technology', 'IT', 200000.00, '2026-07-01', 'Inactive', '2026-07-26 15:14:28'),
(4, 'cse', 'kkay', 'Female', 'thabangrantokaa@gmail.com', '0763649526', 'Computer systems eng', 'Software Developer', 100000.00, '2026-06-04', 'Leave', '2026-07-26 15:16:10'),
(6, 'EMP003', 'Moyahabo Thabang Rantoka', 'Male', 'thabangrantoka@gmail.com', '0763649535', 'Computer systems eng', 'Software Developer', 40000.00, '2002-06-18', 'Active', '2026-08-04 19:36:22');

-- --------------------------------------------------------

--
-- Table structure for table `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `leave_type` enum('Annual','Sick','Maternity','Paternity','Study','Unpaid','Other') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `reason` text DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected') DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `leave_requests`
--

INSERT INTO `leave_requests` (`id`, `employee_id`, `leave_type`, `start_date`, `end_date`, `reason`, `status`, `created_at`) VALUES
(1, 6, 'Sick', '2026-08-04', '2026-08-20', 'break nyana', 'Approved', '2026-08-04 19:37:37'),
(2, 6, 'Sick', '2026-08-04', '2026-08-20', 'sick', 'Pending', '2026-08-04 20:20:22'),
(3, 6, 'Maternity', '2026-08-05', '2026-08-14', 'bihjij', 'Approved', '2026-08-04 20:21:34'),
(4, 6, 'Annual', '2026-08-04', '2026-08-12', 'jnh', 'Pending', '2026-08-04 20:42:12'),
(5, 6, 'Annual', '2026-08-05', '2026-08-07', 'dfbb', 'Pending', '2026-08-05 06:57:35');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(150) NOT NULL,
  `message` text NOT NULL,
  `icon` varchar(50) DEFAULT '?',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `projects`
--

CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `project_code` varchar(30) NOT NULL,
  `project_name` varchar(150) NOT NULL,
  `department` varchar(100) NOT NULL,
  `project_manager` varchar(150) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `budget` decimal(12,2) DEFAULT NULL,
  `progress` int(11) NOT NULL DEFAULT 0,
  `priority` enum('Low','Medium','High','Critical') NOT NULL DEFAULT 'Medium',
  `status` enum('Planning','In Progress','Completed','On Hold') DEFAULT 'Planning',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `projects`
--

INSERT INTO `projects` (`id`, `project_code`, `project_name`, `department`, `project_manager`, `start_date`, `end_date`, `budget`, `progress`, `priority`, `status`, `created_at`) VALUES
(1, 'PROHEAA', 'DATA PROJECT', 'Computer systems eng', 'THABANG', '2026-06-01', '2026-08-01', 50000.00, 0, 'Medium', 'In Progress', '2026-07-26 15:57:56'),
(2, 'PROHEAU', 'DATA PROJECTs', 'Information Technology', 'huyt', '2026-07-02', '2026-08-13', 223444.00, 50, 'Medium', 'In Progress', '2026-07-26 17:25:39'),
(3, 'NJFKWDKEWNFKEJF', 'iejfdkemf m cn dcwkdnkemfkowmdkoemfk', 'Science', 'nfkrenjedkndjnieur', '2026-06-01', '2026-08-01', 47.00, 0, 'Medium', 'Planning', '2026-07-27 13:25:39'),
(4, 'NWJBEJNDHEBDNSANXN WE DNJWNDC', 'vmcjidnve  rc je cjj n vejcokemfkemd', 'Science', 'nnnscned', '2026-05-01', '2026-07-25', 8888.00, 30, 'Medium', 'In Progress', '2026-07-27 13:26:23'),
(5, 'PROJOO1', 'NNNN', 'Computer systems eng', 'THABANG', '2026-07-03', '2026-07-24', 2000.00, 30, 'Medium', 'Planning', '2026-07-27 13:27:10');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(150) NOT NULL,
  `company_email` varchar(150) DEFAULT NULL,
  `company_phone` varchar(50) DEFAULT NULL,
  `company_address` text DEFAULT NULL,
  `company_website` varchar(150) DEFAULT NULL,
  `timezone` varchar(100) DEFAULT 'Africa/Maseru',
  `currency` varchar(20) DEFAULT 'LSL',
  `theme` enum('Light','Dark') DEFAULT 'Light',
  `company_logo` varchar(255) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `company_name`, `company_email`, `company_phone`, `company_address`, `company_website`, `timezone`, `currency`, `theme`, `company_logo`, `updated_at`) VALUES
(1, 'OpsFlow Enterprise', 'admibn@opsflow.com', '+266 50000000', 'Maseru, Lesotho', 'www.opsflow.com', 'Africa/Maseru', 'LSL', 'Light', '1785089666_bible-verse-desktop-wallpaper-i-can-do-vector-28603073.jpg', '2026-07-26 18:14:26');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Manager','Employee') DEFAULT 'Employee',
  `status` enum('Active','Inactive') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `employee_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `username`, `email`, `phone`, `photo`, `password`, `role`, `status`, `created_at`, `employee_id`) VALUES
(1, 'Moyahabo Thabang Rantoka', 'moyahabo', 'thabangrantoka@gmail.com', NULL, NULL, '$2y$10$hJqw/fYH1M9RYmnVG8z.MOXsubkGe5.AzE0bbkMITIcBZ7Oh6aReK', 'Employee', 'Active', '2026-07-25 14:58:57', 6),
(2, 'tebogo', 'tebogo', 'thabangrantloka@gmail.com', NULL, NULL, '$2y$10$rMYxKJjPd5Wh3ne1BEn4uObV1gKLUPwg.ZPtUNW6cVU9W6odeK3Hu', 'Admin', 'Active', '2026-07-25 15:00:19', NULL),
(3, 'karabo', 'karabo', 'thabangrantokaa@gmail.com', NULL, NULL, '$2y$10$d9EGiWnfDpuRgJ1Nm0mLM.RapxtCTCX4li1shMOS9tCUI6DmJM0s6', 'Admin', 'Active', '2026-07-25 15:07:17', 4),
(4, 'tebogo', 'tebogo2', 'kabelorantoka@gmail.com', NULL, NULL, '$2y$10$7D7G/okT0.BO3yniU7dtDuRkDZq.s0qhB.Mbp1CrhFSBhDQS5ew12', 'Admin', 'Active', '2026-07-26 09:36:34', NULL),
(5, 'Kay Bee', '', 'thabangrantoka123@gmail.com', NULL, NULL, '$2y$10$OeGkkS67KEpjNi0aoOBvOe./N7x8KqGVFdvJtiSUDm//uT.O5tCBW', 'Admin', 'Active', '2026-07-27 08:51:15', NULL),
(6, 'stoes', '', 'thabangranto9ka@gmail.com', NULL, NULL, '$2y$10$KpvnlO2kVnDkYNPIotVLaO19ZZ4ByzuVG/nrTEYUMBtlMpkZ7ZWgO', 'Employee', 'Active', '2026-07-30 15:10:19', NULL),
(7, 'kat', '', 'thabangranto19ka@gmail.com', NULL, NULL, '$2y$10$MJ1L.hBVEa9VpEIb/H/kTeRfGmmT3QB..hr/6GnFeDXOK5JJinc2S', 'Admin', 'Active', '2026-08-01 22:54:30', NULL),
(8, 'lebo', '', 'thabangrantoka012@gmail.com', NULL, NULL, '$2y$10$/F0dlW9O6QjrnCRtkFhYDOxB.axgaboo7re048l9GnPIRJSNh3QCG', 'Manager', 'Active', '2026-08-02 13:16:50', NULL),
(9, 'kamo', '', 'thabangranto9ka012@gmail.com', NULL, NULL, '$2y$10$934mO3jBXzK.CXpS4IJDaeowdOF.Gfz1riM5enKHao/NJbBP1p2QK', 'Admin', 'Active', '2026-08-02 13:27:42', NULL),
(10, 'Nozipho', '', 'thabangrantloka012@gmail.com', NULL, NULL, '$2y$10$PFlI/hDlhZiWrWiwuNdmJODh1wO2cZkl687jezGpuoJlOrIeowMWG', 'Admin', 'Active', '2026-08-04 04:35:25', NULL),
(11, 'Leto', '', 'aa@gmail.com', NULL, NULL, '$2y$10$GwNr7rL4r3CDSnIn.y8OeOTkr7V2fQBOLOxp/Q6.Yo9HiuknoWzia', 'Admin', 'Active', '2026-08-04 19:34:35', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `clients`
--
ALTER TABLE `clients`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `department_name` (`department_name`),
  ADD UNIQUE KEY `department_code` (`department_code`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_number` (`employee_number`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `project_code` (`project_code`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `clients`
--
ALTER TABLE `clients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
