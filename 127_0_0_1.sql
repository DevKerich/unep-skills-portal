-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2026 at 05:02 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `unep_skills`
--
CREATE DATABASE IF NOT EXISTS `unep_skills` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `unep_skills`;

-- --------------------------------------------------------

--
-- Table structure for table `duty_stations`
--

CREATE TABLE `duty_stations` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `duty_stations`
--

INSERT INTO `duty_stations` (`id`, `name`) VALUES
(2, 'Geneva'),
(1, 'Nairobi'),
(3, 'New York'),
(4, 'Remote');

-- --------------------------------------------------------

--
-- Table structure for table `education_levels`
--

CREATE TABLE `education_levels` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `education_levels`
--

INSERT INTO `education_levels` (`id`, `name`) VALUES
(3, 'Bachelor'),
(2, 'Diploma'),
(1, 'High School'),
(4, 'Master'),
(5, 'PhD');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`id`, `name`) VALUES
(4, 'Arabic'),
(1, 'English'),
(2, 'French'),
(3, 'Spanish');

-- --------------------------------------------------------

--
-- Table structure for table `software_expertise`
--

CREATE TABLE `software_expertise` (
  `id` int(11) NOT NULL,
  `name` varchar(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `software_expertise`
--

INSERT INTO `software_expertise` (`id`, `name`) VALUES
(3, 'C#'),
(2, 'Java'),
(5, 'JavaScript'),
(4, 'PHP'),
(1, 'Python'),
(6, 'SQL');

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `id` int(11) NOT NULL,
  `index_number` varchar(50) NOT NULL,
  `full_names` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `current_location` varchar(150) NOT NULL,
  `highest_education_id` int(11) DEFAULT NULL,
  `duty_station_id` int(11) DEFAULT NULL,
  `availability_remote_work` enum('Yes','No') NOT NULL DEFAULT 'No',
  `software_expertise_id` int(11) DEFAULT NULL,
  `software_expertise_level` enum('Beginner','Intermediate','Advanced','Expert') DEFAULT NULL,
  `language_id` int(11) DEFAULT NULL,
  `level_of_responsibility` enum('Junior','Mid','Senior','Manager','Director') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `staff`
--

INSERT INTO `staff` (`id`, `index_number`, `full_names`, `email`, `current_location`, `highest_education_id`, `duty_station_id`, `availability_remote_work`, `software_expertise_id`, `software_expertise_level`, `language_id`, `level_of_responsibility`, `created_at`, `updated_at`) VALUES
(1, '1122222', 'Obadia Kerich', 'kerichobadia@gmail.com', 'Nakuru', 3, 1, 'Yes', 4, 'Advanced', 1, 'Senior', '2026-02-14 08:47:54', '2026-02-14 08:47:54'),
(2, 'ds', 'dssd', 'ssd@gmail.com', 'dssd', 3, 2, 'No', 3, 'Intermediate', 4, 'Senior', '2026-04-14 15:00:34', '2026-04-14 15:00:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','staff') NOT NULL DEFAULT 'staff',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(2, 'System Admin', 'admin@unep.org', '$2y$10$NTA4dHJsRK8kwufTDE/beew3gMUE2o.38QkNndo/lWEAlrsOXJJwq', 'admin', '2026-02-14 08:20:22');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `duty_stations`
--
ALTER TABLE `duty_stations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `education_levels`
--
ALTER TABLE `education_levels`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `software_expertise`
--
ALTER TABLE `software_expertise`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `index_number` (`index_number`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_staff_education` (`highest_education_id`),
  ADD KEY `fk_staff_duty` (`duty_station_id`),
  ADD KEY `fk_staff_language` (`language_id`),
  ADD KEY `fk_staff_software` (`software_expertise_id`);

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
-- AUTO_INCREMENT for table `duty_stations`
--
ALTER TABLE `duty_stations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `education_levels`
--
ALTER TABLE `education_levels`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `software_expertise`
--
ALTER TABLE `software_expertise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `staff`
--
ALTER TABLE `staff`
  ADD CONSTRAINT `fk_staff_duty` FOREIGN KEY (`duty_station_id`) REFERENCES `duty_stations` (`id`),
  ADD CONSTRAINT `fk_staff_education` FOREIGN KEY (`highest_education_id`) REFERENCES `education_levels` (`id`),
  ADD CONSTRAINT `fk_staff_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  ADD CONSTRAINT `fk_staff_software` FOREIGN KEY (`software_expertise_id`) REFERENCES `software_expertise` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
