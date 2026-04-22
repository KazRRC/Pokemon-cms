-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 22, 2026 at 09:20 PM
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
-- Database: `pokemon_cms`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `pokemon_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `pokemon_name` varchar(255) DEFAULT NULL,
  `approved` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `pokemon_id`, `user_id`, `comment_text`, `created_at`, `pokemon_name`, `approved`) VALUES
(13, 9, 1, 'green buge🟢🟢🐛🪲🦗💚💚🟢🟢🟢🌳🌳🥬🍏🥬🥬🥬🟢🟢🟢🟢📗', '2026-03-25 18:56:20', NULL, 1),
(14, 9, 4, 'green buge🟢🟢🟢🟢🥬🥬🍏🪲🐛🐛🐛🦗🦗🦗🦗🌳🌳💚💚💚💚🌳🍏🟢🟢🟢🍏🍏🍏🌳💚💚💚💚📗🟢🟢🟢🟢🟢🦗🪲🪲🐛🐛🐛🐛🐛🐛🐛🪲🪲🪲🐛🐛🐛🐛🐛🐛🐛🐛🐛🐛🥬', '2026-03-25 19:22:46', NULL, 1),
(15, 8, 4, 'green buge..............🥬🥬🍏🍏🍏💚💚💚🐛🐛🐛🐛🐛🪲🦗🟢🟢🟢🟢', '2026-03-25 19:23:24', NULL, 1),
(16, 24, 6, 'boy why you so keys', '2026-03-31 03:04:57', NULL, 1),
(17, 4, 8, 'test', '2026-03-31 15:10:58', NULL, 1),
(18, 28, 6, 'green buge🟢🟢🟢🟢🟢🦗🪲🐛🐛🦗📗📗📗🥬🥬🥬very nice📗🍏🍏🍏🐛🐛🐛🐛🦗🪲🪲🟢🟢🟢🟢🟢🟢🟢🪲🐛🦗🍏💚💚🥬💚💚💚🍏🌳🥬🥬📗so green🟢🟢🪲🪲🪲🥬📗🐛🐛🥬🥬🥬🌳🍏 polite and green💚💚🟢🥬🥬🥬🥬🐛🦗🦗📗🪲🪲🐛🦗💚💚🟢🟢🟢🍏', '2026-03-31 19:44:29', NULL, 1),
(19, 9, 6, 'green buge so green🟢🟢🟢🟢📗🪲🪲🍏🍏💚💚🥬🥬🐛🐛🦗🦗🌳🌳💚💚🟢🍏🥬🥬📗📗🟢🟢🟢💚💚💚🦗🦗🐛🐛🐛🌳💚🟢🟢📗 gre en🟢🟢🍏💚💚🐛🐛🦗🦗🌳📗🥬🟢🟢🌳🍏🪲🥬🥬', '2026-03-31 19:48:55', NULL, 1),
(20, 8, 6, 'GREEN BUGE!!!!!🍏🍏🍏💚💚🐛🦗🪲🪲🦗🥬🥬📗🟢🟢🟢🟢🟢🟢🌳💚💚🐛🐛🪲💚🍏💚💚🪲🥬🥬🦗🐛🐛🦗🦗💚💚🟢🟢💚💚💚🐛🐛🐛🐛🪲', '2026-03-31 19:49:52', NULL, 1),
(21, 29, 6, 'green buge...💚💚🟢🌳🐛🐛🐛🪲🍏🍏🥬🦗🟢🟢🟢🥬🥬💚💚🐛🐛💚🟢🦗🥬🥬💚🐛🍏🍏🐛🐛💚💚🟢🥬🦗 so sharp🟢🟢💚🐛🐛🐛🦗🪲🪲🦗🦗🥬🌳🌳🌳🟢 why are you fighting little bug...🍏🍏🥬🦗🪲🦗🟢🍏💚🌳🪲🪲🐛🦗🥬🟢🟢🟢🟢🟢💚🌳', '2026-03-31 19:51:35', NULL, 1),
(22, 30, 6, 'little green buge...🪲🪲💚🟢🟢🌳🌳🌳🦗🦗🥬🟢🟢💚💚🪲🪲🍏💚🦗🦗🥬🥬🥬🌳🌳🌳🌳💚🟢💚💚💚🍏🦗🦗🦗🐛🥬🦗🦗🦗🦗🦗💚🟢🟢🟢🟢🍏🪲🪲📗spider.e...', '2026-03-31 19:52:37', NULL, 1),
(23, 46, 6, 'hi', '2026-04-14 06:30:30', NULL, 1),
(24, 46, 6, 'testing', '2026-04-14 15:47:51', NULL, 1),
(26, NULL, 6, 'test', '2026-04-21 05:56:31', 'klefki', 1),
(27, NULL, 6, 'crouton', '2026-04-21 10:02:36', 'crouton', 1),
(28, NULL, 6, 'bug...', '2026-04-21 15:06:27', 'caterpie', 1);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

CREATE TABLE `pages` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `page_categories`
--

CREATE TABLE `page_categories` (
  `page_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pokemon`
--

CREATE TABLE `pokemon` (
  `pokemon_id` int(11) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `hitpoints` int(11) DEFAULT NULL,
  `attack` int(11) DEFAULT NULL,
  `defense` int(11) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `type` varchar(50) NOT NULL,
  `is_custom` tinyint(1) DEFAULT 1,
  `description` text DEFAULT NULL,
  `ability` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pokemon`
--

INSERT INTO `pokemon` (`pokemon_id`, `name`, `type_id`, `hitpoints`, `attack`, `defense`, `image`, `created_at`, `type`, `is_custom`, `description`, `ability`) VALUES
(56, 'crouton', NULL, 1, 1, 1, '1776757435_69e72abb364a1.png', '2026-04-21 02:43:55', 'fire', 1, '<p>hi</p>', ''),
(57, 'Dragonite', NULL, NULL, 1, 1, NULL, '2026-04-21 03:39:03', '', 1, NULL, NULL),
(60, 'noimage', NULL, 231, 212, 222, NULL, '2026-04-21 11:50:40', '', 1, '<p>no image</p>', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `types`
--

CREATE TABLE `types` (
  `type_id` int(11) NOT NULL,
  `type_name` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `types`
--

INSERT INTO `types` (`type_id`, `type_name`) VALUES
(1, 'electric'),
(2, 'grass'),
(3, 'fire'),
(4, 'bug'),
(5, 'normal'),
(6, 'fairy'),
(7, 'water'),
(8, 'ground'),
(9, 'dragon'),
(10, 'steel'),
(11, 'ice');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp(),
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `role`, `created_at`, `email`) VALUES
(6, 'test', '$2y$10$xHQjjHGylXyBgmphBRW0Ie70ozXPacjn.rnRnAJHAM2bwt.QmHspW', 'admin', '2026-03-30 21:38:36', 'email@email.com'),
(8, 'test2', '$2y$10$Ez72kLsznUd1bwLzsLuAQO3cRx8JdO28ZKDM7ZDa80OzVbZnYXMYC', 'user', '2026-03-31 10:10:43', 'email2@gmail.com'),
(9, 'bob2', '$2y$10$5LikouHMVvH6eR4KWTtTK.vmc.LiHQz/oxqAS4/cl7gy.PO/dZxsm', 'user', '2026-03-31 10:38:50', 'bob@mail.com'),
(10, 'frank', '$2y$10$ceGohpPs9xkyyQ/.2lW3SuN5DL8RHK7xGZLdn391lyihTxjWkVfDK', 'user', '2026-03-31 10:40:25', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

--
-- Indexes for table `pages`
--
ALTER TABLE `pages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `pokemon`
--
ALTER TABLE `pokemon`
  ADD PRIMARY KEY (`pokemon_id`),
  ADD KEY `type_id` (`type_id`);

--
-- Indexes for table `types`
--
ALTER TABLE `types`
  ADD PRIMARY KEY (`type_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `pages`
--
ALTER TABLE `pages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pokemon`
--
ALTER TABLE `pokemon`
  MODIFY `pokemon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `pokemon`
--
ALTER TABLE `pokemon`
  ADD CONSTRAINT `pokemon_ibfk_1` FOREIGN KEY (`type_id`) REFERENCES `types` (`type_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
