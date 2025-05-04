-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: May 04, 2025 at 03:28 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `glitch_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `ADMIN`
--

CREATE TABLE `ADMIN` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ADMIN`
--

INSERT INTO `ADMIN` (`admin_id`, `username`, `email`, `password`) VALUES
(1, 'admin1', 'admin1@gmail.com', '$2y$10$EIKfzwjQJBDv92m.KjOC2uRvGr0o2fJVbXpaNNxDLoKfB/q5jGHzu'),
(2, 'admin2', 'admin2@gmail.com', '$2y$10$tXOQE2m11Lc0SQLyVJytOuzBvHtPIba5SV5oe.c.XN3xv/taV55z6'),
(3, 'admin3', 'admin3@gmail.com', '$2y$10$XKqPbTJTRF.VcnQIPwEO/.xmS.s1DlWGdW/jNudo2JKu1Nng9efA.'),
(4, 'admin4', 'admin4@gmail.com', '$2y$10$Pm4EHrnsRPAIS9UlEkLX9umn.8wcoUYWOhHCp6OviBTsnc5A50pgS');

-- --------------------------------------------------------

--
-- Table structure for table `CART`
--

CREATE TABLE `CART` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `CART`
--

INSERT INTO `CART` (`cart_id`, `user_id`, `total`) VALUES
(1, 1, 19.99),
(2, 2, 39.99),
(3, 3, 19.99),
(4, 4, 29.99);

-- --------------------------------------------------------

--
-- Table structure for table `CartItem`
--

CREATE TABLE `CartItem` (
  `cart_item_id` int(11) NOT NULL,
  `cart_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `unit_price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `CartItem`
--

INSERT INTO `CartItem` (`cart_item_id`, `cart_id`, `game_id`, `unit_price`, `quantity`) VALUES
(1, 1, 1, 19.99, 1),
(2, 2, 2, 39.99, 1),
(3, 3, 3, 19.99, 1),
(4, 4, 4, 29.99, 1);

-- --------------------------------------------------------

--
-- Table structure for table `GAME`
--

CREATE TABLE `GAME` (
  `game_id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `release_date` date DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `GAME`
--

INSERT INTO `GAME` (`game_id`, `title`, `category`, `description`, `image_url`, `release_date`, `price`) VALUES
(1, 'Minecraft', 'Sandbox, Survival', 'Minecraft is a game made up of blocks, creatures, and community. You can survive the night or build a work of art — the choice is all yours.', 'Minecraft_poster.jpeg', '2011-11-18', 19.99),
(2, 'Overwatch 2', 'First-Person Shooter', 'Overwatch is a team-based shooter that blends fast-paced gameplay with strategic hero abilities. Players choose from a roster of diverse heroes, each with unique skills, and compete in various game modes like Escort and Control.', 'ow2_poster.jpeg', '2016-10-04', 39.99),
(3, 'STARDEW VALLEY', 'Farming Simulation, RPG', 'You\'ve inherited your grandfather\'s old farm plot in Stardew Valley.\r\nArmed with hand-me-down tools and a few coins, you set out to begin your new life. Can you learn to live off the land and turn these overgrown fields into a thriving home?', 'StardewValley_poster.jpeg', '2016-10-29', 19.99),
(4, 'Marvel Rivals', 'Hero Shooter', 'Marvel Rivals is a Super Hero Team-Based PVP Shooter! Assemble an all-star Marvel squad, devise countless strategies by combining powers to form unique Team-Up skills and fight in destructible, ever-changing battlefields across the continually evolving Marvel universe!', 'marvel_poster.jpeg', '2024-05-21', 29.99);

-- --------------------------------------------------------

--
-- Table structure for table `GameOwnership`
--

CREATE TABLE `GameOwnership` (
  `ownership_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `purchase_date` date DEFAULT NULL,
  `achievements` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `GameOwnership`
--

INSERT INTO `GameOwnership` (`ownership_id`, `user_id`, `game_id`, `purchase_date`, `achievements`) VALUES
(1, 1, 1, '2025-04-25', 'Built a house, Defeated the Ender Dragon'),
(2, 2, 2, '2025-04-25', 'Reached level 50, Won a match'),
(3, 3, 3, '2025-04-25', 'Achieved 100 kills, Completed all missions'),
(4, 4, 4, '2025-04-25', 'First Win: Win your first match in Marvel Rivals.');

-- --------------------------------------------------------

--
-- Table structure for table `ORDER`
--

CREATE TABLE `ORDER` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `order_date` date DEFAULT NULL,
  `tax` decimal(5,2) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ORDER`
--

INSERT INTO `ORDER` (`order_id`, `user_id`, `total_price`, `order_date`, `tax`, `payment_status`) VALUES
(1, 1, 19.99, '2025-04-25', 5.45, 'Paid'),
(2, 2, 39.99, '2025-04-25', 2.00, 'Pending'),
(3, 3, 19.99, '2025-04-25', 3.00, 'Paid'),
(4, 4, 29.99, '2025-04-25', 2.50, 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `OrderItem`
--

CREATE TABLE `OrderItem` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `price_at_purchase` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `OrderItem`
--

INSERT INTO `OrderItem` (`order_item_id`, `order_id`, `game_id`, `price_at_purchase`, `quantity`) VALUES
(1, 1, 1, 19.99, 1),
(2, 2, 2, 39.99, 1),
(3, 3, 3, 19.99, 1),
(4, 4, 4, 29.99, 1);

-- --------------------------------------------------------

--
-- Table structure for table `Payment`
--

CREATE TABLE `Payment` (
  `payment_id` int(11) NOT NULL,
  `SaveInfo` tinyint(1) DEFAULT NULL,
  `PhoneNum` varchar(20) DEFAULT NULL,
  `Name` varchar(100) DEFAULT NULL,
  `First` varchar(50) DEFAULT NULL,
  `Last` varchar(50) DEFAULT NULL,
  `City` varchar(50) DEFAULT NULL,
  `Country` varchar(50) DEFAULT NULL,
  `ZIP` varchar(10) DEFAULT NULL,
  `cardNum` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Payment`
--

INSERT INTO `Payment` (`payment_id`, `SaveInfo`, `PhoneNum`, `Name`, `First`, `Last`, `City`, `Country`, `ZIP`, `cardNum`) VALUES
(1, 1, '123-456-7890', 'John Doe', 'John', 'Doe', 'New York', 'USA', '10001', '1234567812345678'),
(2, 0, '987-654-3210', 'Jane Smith', 'Jane', 'Smith', 'Los Angeles', 'USA', '90001', '8765432187654321');

-- --------------------------------------------------------

--
-- Table structure for table `PaymentMethod`
--

CREATE TABLE `PaymentMethod` (
  `cardNum` varchar(20) NOT NULL,
  `First` varchar(50) DEFAULT NULL,
  `Last` varchar(50) DEFAULT NULL,
  `cvv` int(11) DEFAULT NULL,
  `Expiry_Date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `PaymentMethod`
--

INSERT INTO `PaymentMethod` (`cardNum`, `First`, `Last`, `cvv`, `Expiry_Date`) VALUES
('1234567812345678', 'John', 'Doe', 123, '2025-05-10'),
('8765432187654321', 'Jane', 'Smith', 456, '2026-06-15');

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int(11) NOT NULL,
  `game_id` int(11) NOT NULL,
  `stock_quant` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `game_id`, `stock_quant`) VALUES
(1, 1, 10),
(2, 2, 10),
(3, 3, 10),
(4, 4, 10);

-- --------------------------------------------------------

--
-- Table structure for table `SupportTicket`
--

CREATE TABLE `SupportTicket` (
  `ticket_id` int(11) NOT NULL,
  `user_email` varchar(100) DEFAULT NULL,
  `user_name` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `response` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `SupportTicket`
--

INSERT INTO `SupportTicket` (`ticket_id`, `user_email`, `user_name`, `message`, `response`) VALUES
(1, 'noor1@gmail.com', 'noor', 'I have an issue with my payment.', 'We are investigating the issue.'),
(2, 'dhays@gmail.com', 'didi', 'Can I get a refund for the game?', 'Refund request is being processed.');

-- --------------------------------------------------------

--
-- Table structure for table `USER`
--

CREATE TABLE `USER` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `profile_pic` varchar(255) DEFAULT NULL,
  `promo` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `USER`
--

INSERT INTO `USER` (`user_id`, `username`, `email`, `password`, `birthdate`, `bio`, `profile_pic`, `promo`) VALUES
(1, 'inoor', 'noor1@gmail.com', '$2y$10$RxrcEUD22wo5CN5tRtW42u3gLhldyJiuXNGyG7tToeGVX5fwQ6Q0m', '2000-05-10', 'Gamer & trophy hunter', 'noor.png', 0),
(2, 'didi', 'dhays@gmail.com', '$2y$10$kRGAsCnUKikE66h.Jg/SSOZs47e8vrxguxS728xNszkOnDUF/wPLq', '2003-04-10', 'Collector & speedrunner', 'dhay.png', 1),
(3, 'roro', 'r11@gmail.com', '$2y$10$AOv7KL0f.baKvEObm5e37ObW1zqE7NL7vvwunDvvlrcP0oUhqzz/u', '2001-06-22', 'Gamer at heart | Always chasing the next high score', 're.png', 0),
(4, 'njnj', 'njj21@gmail.com', '$2y$10$P6mFDntiBVedlTFHCcKqZOLyz8eCr1L4/HEeX0MZW5T1zgMyiuaF.', '2000-11-01', 'Leveling up in life one game at a time 🍓🎮', 'nj.png', 0);

-- --------------------------------------------------------

--
-- Table structure for table `Wishlist`
--

CREATE TABLE `Wishlist` (
  `wishlist_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `game_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `Wishlist`
--

INSERT INTO `Wishlist` (`wishlist_id`, `user_id`, `game_id`, `price`) VALUES
(1, 1, 2, 39.99),
(2, 2, 1, 19.99),
(3, 3, 4, 29.99),
(4, 4, 3, 19.99);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `ADMIN`
--
ALTER TABLE `ADMIN`
  ADD PRIMARY KEY (`admin_id`);

--
-- Indexes for table `CART`
--
ALTER TABLE `CART`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `CartItem`
--
ALTER TABLE `CartItem`
  ADD PRIMARY KEY (`cart_item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `GAME`
--
ALTER TABLE `GAME`
  ADD PRIMARY KEY (`game_id`);

--
-- Indexes for table `GameOwnership`
--
ALTER TABLE `GameOwnership`
  ADD PRIMARY KEY (`ownership_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `ORDER`
--
ALTER TABLE `ORDER`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `OrderItem`
--
ALTER TABLE `OrderItem`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `Payment`
--
ALTER TABLE `Payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `cardNum` (`cardNum`);

--
-- Indexes for table `PaymentMethod`
--
ALTER TABLE `PaymentMethod`
  ADD PRIMARY KEY (`cardNum`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `game_id` (`game_id`);

--
-- Indexes for table `SupportTicket`
--
ALTER TABLE `SupportTicket`
  ADD PRIMARY KEY (`ticket_id`);

--
-- Indexes for table `USER`
--
ALTER TABLE `USER`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `Wishlist`
--
ALTER TABLE `Wishlist`
  ADD PRIMARY KEY (`wishlist_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `game_id` (`game_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `ADMIN`
--
ALTER TABLE `ADMIN`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `CART`
--
ALTER TABLE `CART`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `CartItem`
--
ALTER TABLE `CartItem`
  MODIFY `cart_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `GAME`
--
ALTER TABLE `GAME`
  MODIFY `game_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `GameOwnership`
--
ALTER TABLE `GameOwnership`
  MODIFY `ownership_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `ORDER`
--
ALTER TABLE `ORDER`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `OrderItem`
--
ALTER TABLE `OrderItem`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Payment`
--
ALTER TABLE `Payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `SupportTicket`
--
ALTER TABLE `SupportTicket`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `USER`
--
ALTER TABLE `USER`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `Wishlist`
--
ALTER TABLE `Wishlist`
  MODIFY `wishlist_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `CART`
--
ALTER TABLE `CART`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`);

--
-- Constraints for table `CartItem`
--
ALTER TABLE `CartItem`
  ADD CONSTRAINT `cartitem_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `CART` (`cart_id`),
  ADD CONSTRAINT `cartitem_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `GAME` (`game_id`);

--
-- Constraints for table `GameOwnership`
--
ALTER TABLE `GameOwnership`
  ADD CONSTRAINT `gameownership_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`),
  ADD CONSTRAINT `gameownership_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `GAME` (`game_id`);

--
-- Constraints for table `ORDER`
--
ALTER TABLE `ORDER`
  ADD CONSTRAINT `order_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`);

--
-- Constraints for table `OrderItem`
--
ALTER TABLE `OrderItem`
  ADD CONSTRAINT `orderitem_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `ORDER` (`order_id`),
  ADD CONSTRAINT `orderitem_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `GAME` (`game_id`);

--
-- Constraints for table `Payment`
--
ALTER TABLE `Payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`cardNum`) REFERENCES `PaymentMethod` (`cardNum`);

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`game_id`) REFERENCES `Game` (`game_id`);

--
-- Constraints for table `Wishlist`
--
ALTER TABLE `Wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `USER` (`user_id`),
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`game_id`) REFERENCES `GAME` (`game_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
