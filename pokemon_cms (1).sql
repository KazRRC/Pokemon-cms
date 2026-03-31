-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 31, 2026 at 03:24 AM
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
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `comment_id` int(11) NOT NULL,
  `pokemon_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `comment_text` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`comment_id`, `pokemon_id`, `user_id`, `comment_text`, `created_at`) VALUES
(9, 3, 3, 'test', '2026-03-25 03:29:42'),
(10, 5, 3, 'test', '2026-03-25 03:46:55'),
(11, 4, 1, 'test', '2026-03-25 04:24:07'),
(13, 9, 1, 'green buge🟢🟢🐛🪲🦗💚💚🟢🟢🟢🌳🌳🥬🍏🥬🥬🥬🟢🟢🟢🟢📗', '2026-03-25 18:56:20'),
(14, 9, 4, 'green buge🟢🟢🟢🟢🥬🥬🍏🪲🐛🐛🐛🦗🦗🦗🦗🌳🌳💚💚💚💚🌳🍏🟢🟢🟢🍏🍏🍏🌳💚💚💚💚📗🟢🟢🟢🟢🟢🦗🪲🪲🐛🐛🐛🐛🐛🐛🐛🪲🪲🪲🐛🐛🐛🐛🐛🐛🐛🐛🐛🐛🥬', '2026-03-25 19:22:46'),
(15, 8, 4, 'green buge..............🥬🥬🍏🍏🍏💚💚💚🐛🐛🐛🐛🐛🪲🦗🟢🟢🟢🟢', '2026-03-25 19:23:24');

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
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pokemon`
--

INSERT INTO `pokemon` (`pokemon_id`, `name`, `type_id`, `hitpoints`, `attack`, `defense`, `image`, `created_at`) VALUES
(1, 'pikachu', NULL, 0, 0, 0, NULL, '2026-03-24 22:07:36'),
(2, 'pichu', NULL, 0, 0, 0, NULL, '2026-03-24 22:07:36'),
(3, 'Pichu', 1, 20, 40, 15, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/172.png', '2026-03-24 22:07:43'),
(4, 'Bulbasaur', 2, 45, 49, 49, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/1.png', '2026-03-24 22:46:42'),
(5, 'Ivysaur', 2, 60, 62, 63, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/2.png', '2026-03-24 22:46:49'),
(6, 'Pikachu', 1, 35, 55, 40, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/25.png', '2026-03-24 22:48:52'),
(7, 'Vulpix', 3, 38, 41, 40, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/37.png', '2026-03-24 23:40:30'),
(8, 'Metapod', 4, 50, 20, 55, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/11.png', '2026-03-24 23:41:18'),
(9, 'Caterpie', 4, 45, 30, 35, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/10.png', '2026-03-25 13:55:23'),
(10, 'Butterfree', 4, 60, 45, 50, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/12.png', '2026-03-25 14:06:14'),
(11, 'Jigglypuff', 5, 115, 45, 20, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/39.png', '2026-03-25 14:06:22'),
(12, 'Sandshrew', 8, 50, 75, 85, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/27.png', '2026-03-25 14:13:10'),
(13, 'Venusaur', 2, 80, 82, 83, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/3.png', '2026-03-25 14:21:01'),
(14, 'Weedle', 4, 40, 35, 30, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/13.png', '2026-03-25 14:21:18'),
(15, 'Magikarp', 7, 20, 10, 55, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/129.png', '2026-03-25 14:21:43'),
(16, 'Ditto', 5, 48, 48, 48, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/132.png', '2026-03-25 14:24:05'),
(17, 'Dragonite', NULL, 400, 120, 250, NULL, '2026-03-25 14:39:48'),
(19, 'Dragonite', 9, 91, 134, 95, 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/149.png', '2026-03-30 15:23:31');

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
(9, 'dragon');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `password_hash` varchar(255) DEFAULT NULL,
  `role` varchar(20) DEFAULT 'user',
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password_hash`, `role`, `created_at`) VALUES
(4, 'test', '$2y$10$OMfNhf9o9LladCMLWAEgReyWasTHQZBXvUG5Yd.jAUmADgGOLo69i', 'admin', '2026-03-25 14:21:55'),
(5, 'test2', '$2y$10$xoL2AQ41WB0Q9fBVCA/AG.f03leQ.scOrQCTd2ps2mHbXHmlNDGSm', 'user', '2026-03-30 15:20:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`comment_id`);

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
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pokemon`
--
ALTER TABLE `pokemon`
  MODIFY `pokemon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `types`
--
ALTER TABLE `types`
  MODIFY `type_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

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
