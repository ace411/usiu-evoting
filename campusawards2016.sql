-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Jul 21, 2016 at 06:37 AM
-- Server version: 10.1.13-MariaDB
-- PHP Version: 7.0.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

-- Database: `campusawards2016`
--

-- --------------------------------------------------------

--
-- Table structure for tables categories, nominees, students and sub-categories
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(200) NOT NULL,
  `category_description` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `nominees` (
  `nominee_id` int(11) NOT NULL,
  `nominee_name` varchar(200) NOT NULL,
  `nominee_desc` text,
  `nominee_img` varchar(250) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `sub_cat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_id` int(6) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `password` varchar(250) NOT NULL,
  `salt` varchar(20) NOT NULL,
  `has_voted` tinyint(1) NOT NULL,
  `combined_name` varchar(250) AS (CONCAT(lastname, ' ', firstname)) VIRTUAL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `sub_category` (
  `sub_cat_id` int(11) NOT NULL,
  `sub_category` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

CREATE TABLE `votes` (
  `vote_id` int(11) NOT NULL,
  `std_id` int(11) NOT NULL,
  `cat_id` int(11) NOT NULL,
  `nom_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `category_name` (`category_name`);

--
-- Indexes for table `nominees`
--
ALTER TABLE `nominees`
  ADD PRIMARY KEY (`nominee_id`),
  ADD KEY `fk_cat` (`cat_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_identifier` (`student_id`);

--
-- Indexes for table `sub_category`
--
ALTER TABLE `sub_category`
  ADD PRIMARY KEY (`sub_cat_id`);

--
-- Indexes for table `votes`
--
ALTER TABLE `votes`
  ADD PRIMARY KEY (`vote_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT for table `nominees`
--
ALTER TABLE `nominees`
  MODIFY `nominee_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=96;
--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT for table `sub_category`
--
ALTER TABLE `sub_category`
  MODIFY `sub_cat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `votes`
--
ALTER TABLE `votes`
  MODIFY `vote_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `nominees`
--
ALTER TABLE `nominees`
  ADD CONSTRAINT `fk_cat` FOREIGN KEY (`cat_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

