-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 29, 2025 at 09:11 PM
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
-- Database: `safesupport`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `counselor_id` int(11) NOT NULL,
  `appointment_time` datetime NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `counselor_id`, `appointment_time`, `reason`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(12, 1, 1, '2025-03-20 10:22:00', 'dfsadsadas', 'accepted', 'Scheduled appointment', '2025-03-08 14:18:55', '2025-03-08 14:42:49'),
(13, 1, 1, '2025-03-03 10:22:00', 'dasdasdsadas', 'accepted', 'Scheduled appointment', '2025-03-08 14:19:02', '2025-03-08 14:42:50'),
(15, 1, 2, '2025-03-18 09:09:00', 'dbhjsadvhjsavdhgsavd', 'accepted', 'Scheduled appointment', '2025-03-11 12:18:40', '2025-03-11 20:51:42'),
(16, 1, 3, '2025-03-18 12:00:00', 'dsadasdsadsa', 'rejected', 'Scheduled appointment', '2025-03-11 20:51:34', '2025-03-11 20:51:46'),
(17, 1, 2, '2025-03-19 12:00:00', 'dsahdsajgdsa', 'accepted', 'Scheduled appointment', '2025-03-11 22:19:15', '2025-03-11 22:19:22'),
(18, 1, 2, '2025-03-18 12:00:00', 'dadadsad', 'accepted', 'Scheduled appointment', '2025-03-13 01:23:05', '2025-03-13 01:23:26'),
(19, 1, 2, '2025-03-18 12:00:00', 'dsadsadsa', 'rejected', 'Scheduled appointment', '2025-03-14 01:06:22', '2025-03-18 08:58:08'),
(20, 1, 1, '2025-03-18 12:00:00', 'ftytcytytcy', 'accepted', 'Scheduled appointment', '2025-03-18 18:00:18', '2025-03-18 18:00:53'),
(21, 1, 1, '2025-03-18 12:00:00', 'ftctt', 'accepted', 'Scheduled appointment', '2025-03-18 18:10:03', '2025-03-18 18:10:47');

-- --------------------------------------------------------

--
-- Table structure for table `counselors`
--

CREATE TABLE `counselors` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `bio` text DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `availability` varchar(255) NOT NULL,
  `experience` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `counselors`
--

INSERT INTO `counselors` (`id`, `name`, `email`, `password`, `specialization`, `bio`, `active`, `created_at`, `updated_at`, `availability`, `experience`) VALUES
(1, 'Dr. Sarah Johnson', 'sarah@example.com', '$2y$10$1KBhEM.ClqMh.PEEqRtaPudh4oGudDGp/okx5zaN/GKXDZGJXDJXW', 'Anxiety & Depression', 'Experienced in treating anxiety and depression, using CBT and mindfulness techniques.', 1, '2025-02-27 10:16:02', '2025-03-08 08:37:29', 'Mon-Fri 9 AM - 5 PM', 10),
(2, 'Dr. Michael Chen', 'michael@example.com', '', 'Career Counseling', 'Specializes in career counseling, helping clients navigate professional growth.', 1, '2025-02-27 10:16:02', '2025-03-08 01:50:03', 'Tue-Thu 10 AM - 6 PM', 7),
(3, 'Dr. Emily Rodriguez', 'emily@example.com', '', 'Relationship Therapy', 'Focuses on relationship therapy, using evidence-based strategies for couples.', 1, '2025-02-27 10:16:02', '2025-03-08 01:50:03', 'Mon, Wed, Fri 1 PM - 7 PM', 5),
(4, 'Dr. James Wilson', 'james@example.com', '', 'Stress Management', 'Expert in stress management, guiding clients through relaxation techniques and resilience training.', 1, '2025-02-27 10:16:02', '2025-03-08 01:50:03', 'Mon-Sat 8 AM - 4 PM', 12);

-- --------------------------------------------------------

--
-- Table structure for table `counselor_hours`
--

CREATE TABLE `counselor_hours` (
  `id` int(11) NOT NULL,
  `counselor_id` int(11) NOT NULL,
  `day_of_week` tinyint(4) NOT NULL COMMENT '1=Sunday, 2=Monday, ..., 7=Saturday',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resources`
--

CREATE TABLE `resources` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `type` enum('article','video','tool') NOT NULL,
  `description` text NOT NULL,
  `link` varchar(500) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `resources`
--

INSERT INTO `resources` (`id`, `title`, `type`, `description`, `link`, `created_at`) VALUES
(1, '6 Tips To Overcome Social Anxiety (Affects Our Everyday Life)', 'video', 'Are you always worried that other people are judging you when you’re out and about? It’s normal to feel slightly anxious or uncomfortable when you’re hanging out with co-workers or people you’ve never met before. However, when your anxiety from being in a social setting becomes so overwhelming or even debilitating that it starts affecting your daily life, it may be a sign of social anxiety. The symptoms can vary from sweating and trembling to experience nausea and dizziness. Severe social anxiety can even cause some to avoid social situations or going out altogether. So to help you out, here are 6 ways to overcome social anxiety. ', 'https://www.youtube.com/watch?v=X_ZKkvhXNJk', '2025-03-08 07:58:26'),
(2, 'What is depression?', 'article', 'Depression (major depressive disorder) is a common and serious mental disorder that negatively affects how you feel, think, act, and perceive the world.', 'https://www.psychiatry.org/patients-families/depression/what-is-depression', '2025-03-08 08:03:10'),
(3, 'So, You\'re Having an Anxiety Attack (The Calm-Down Method for Stopping Anxiety Attacks)', 'video', 'Do you want to learn How to Process Emotions and improve your Mental Health? Sign up for a Therapy in a Nutshell Membership, you\'ll get access to all of Emma’s courses, workbooks, and a Live Q and A with 100’s of exclusive videos:', 'https://www.youtube.com/watch?v=WGG7MGgptxE', '2025-03-08 14:27:38'),
(4, '6 Therapy Skills to Stop Overthinking Everything', 'video', 'Do you want to learn How to Process Emotions and improve your Mental Health? Sign up for a Therapy in a Nutshell Membership, you\'ll get access to all of Emma’s courses, workbooks, and a Live Q and A with 100’s of exclusive videos:', 'https://www.youtube.com/watch?v=tK2LaefZcy8', '2025-03-08 14:29:34'),
(5, 'How to become 37.78 times better at anything | Atomic Habits summary (by James Clear)', 'video', 'Atomic Habits can help you improve every day, no matter what your goals are. As one of the world\'s leading experts on habit formation, James Clear reveals practical strategies that will help you form good habits, break bad ones, and master tiny behaviors that lead to big changes.', 'https://www.youtube.com/watch?v=PZ7lDrwYdZc', '2025-03-08 14:31:11'),
(6, 'What is mental health?', 'article', 'Mental health is about how people think, feel, and behave. Mental health care professionals can help people manage conditions such as depression, anxiety, bipolar disorder, addiction, and other disorders that affect their thoughts, feelings, and behaviors.', 'https://www.medicalnewstoday.com/articles/154543', '2025-03-08 14:39:27'),
(7, '10 Things You Can Do To Stop Overthinking', 'video', 'Everyone has suffered bouts of overthinking. The only difference is that some eventually come up with a decision. While chronic overthinkers are plagued with questions and doubt. You rehash previous conversations, relive past events, and imagine catastrophic outcomes. If you are an overthinker, your mind can feel like a never-ending movie of horrible possibilities. This is debilitating in more ways than one. Not only does it exhaust you mentally and produce anxiety, along with many other health issues. It also prevents you from moving forward. So here are a few things you could do to stop overthinking. ', 'https://www.youtube.com/watch?v=oGqu_U0EI8c', '2025-03-11 20:54:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `role` enum('user','counselor','admin') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone_number`, `role`, `created_at`) VALUES
(1, 'Mark Ian D. Dela Cruz', 'delacruzmarkian404@gmail.com', '$2y$10$cPHyPPoj8NkBaI0zthvXEOB8GDt1YAJNTQf4il5Z4R7cqZFG0RlKq', '09673315747', 'user', '2025-02-27 09:48:33'),
(3, 'Dr. Sarah Johnson', 'sarah@example.com', '$2y$10$48ZwtFVp3xcp0QsvhCoD/OUmkurvNt.JgblxroSh.NVArpfYOiRC6', NULL, 'counselor', '2025-03-11 10:46:51');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `counselor_id` (`counselor_id`);

--
-- Indexes for table `counselors`
--
ALTER TABLE `counselors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `counselor_hours`
--
ALTER TABLE `counselor_hours`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_counselor_day` (`counselor_id`,`day_of_week`),
  ADD KEY `idx_counselor_hours_counselor` (`counselor_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `resources`
--
ALTER TABLE `resources`
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
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `counselors`
--
ALTER TABLE `counselors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `counselor_hours`
--
ALTER TABLE `counselor_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resources`
--
ALTER TABLE `resources`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`counselor_id`) REFERENCES `counselors` (`id`);

--
-- Constraints for table `counselor_hours`
--
ALTER TABLE `counselor_hours`
  ADD CONSTRAINT `counselor_hours_ibfk_1` FOREIGN KEY (`counselor_id`) REFERENCES `counselors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
