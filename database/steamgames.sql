-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 19, 2024 at 03:50 PM
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
-- Database: `games`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `cupon_code` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`id`, `user_id`, `product_id`, `cupon_code`) VALUES
(38, 23, 11, '');

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `category_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`) VALUES
(6, 'racing'),
(9, 'Action'),
(10, 'Future Generation'),
(11, 'creativity');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_codes`
--

CREATE TABLE `coupon_codes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `value` int(11) NOT NULL,
  `min` int(11) NOT NULL,
  `max` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupon_codes`
--

INSERT INTO `coupon_codes` (`id`, `name`, `value`, `min`, `max`) VALUES
(2, 'Devil124', 23, 400, 5000),
(3, 'FIRST20', 20, 400, 5000),
(4, 'NEW48', 8, 400, 5000);

-- --------------------------------------------------------

--
-- Table structure for table `multiple_images`
--

CREATE TABLE `multiple_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `multiple_images`
--

INSERT INTO `multiple_images` (`id`, `product_id`, `image`) VALUES
(28, 10, '20240615130415_0.png'),
(29, 10, '20240615130415_1.png'),
(30, 10, '20240615130415_2.png'),
(31, 10, '20240615130415_3.png'),
(32, 10, '20240615130415_4.png'),
(33, 10, '20240615130415_5.png'),
(34, 10, '20240615130420_0.png'),
(35, 11, '20240615134228_0.png'),
(36, 11, '20240615134228_1.png'),
(37, 11, '20240615134228_2.png'),
(38, 11, '20240615134228_3.png'),
(39, 11, '20240615134228_4.png'),
(40, 12, '20240615141348_0.jpg'),
(41, 12, '20240615141348_1.jpg'),
(42, 12, '20240615141348_2.png'),
(43, 12, '20240615141348_3.png'),
(44, 13, '20240616141052_0.jpg'),
(45, 13, '20240616141052_1.jpg'),
(46, 13, '20240616141052_2.jpg'),
(47, 13, '20240616141052_3.jpg'),
(48, 13, '20240616141052_4.jpg'),
(49, 14, '20240616141447_0.jpg'),
(50, 14, '20240616141447_1.jpg'),
(51, 14, '20240616141447_2.jpg'),
(52, 14, '20240616141447_3.jpg'),
(53, 15, '20240616141755_0.jpg'),
(54, 15, '20240616141820_0.jpg'),
(55, 15, '20240616141820_1.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `price` int(200) NOT NULL,
  `discount` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `thumbnail` varchar(100) NOT NULL,
  `category_id` int(11) NOT NULL,
  `keywords` varchar(200) NOT NULL,
  `twofactor` tinyint(1) NOT NULL,
  `date` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `discount`, `description`, `thumbnail`, `category_id`, `keywords`, `twofactor`, `date`) VALUES
(10, 'Grand Theft Auto 5', 900, 70, 'Grand Theft Auto V for PC offers players the option to explore the award-winning world of Los Santos and Blaine County in resolutions of up to 4k and beyond ', '20240615130337.png', 9, 'Grand Theft Auto V, gta 5, rock star ', 0, '2024-06-15'),
(11, 'Forza Horizon 5 ', 2000, 91, 'Forza Horizon 5 is a 2021 racing video game developed by Playground Games and published by Xbox Game Studios. It is the fifth Forza Horizon title and twelfth main instalment in the Forza series. The game is set in a fictionalised', '20240615130609.png', 6, 'Racing Video Game, Adventure game', 0, '2024-06-15'),
(12, 'Marvel’s Spider-Man Remastered ', 700, 65, 'Initial release date: 12 August 2022\r\nEngine: Proprietary Engine\r\nDevelopers: Insomniac Games, Nixxes Software, Marvel Entertainment\r\nGenres: Adventure game, Fighting game, Platform game, Casual game, Adventure\r\nPlatforms: PlayStation 5, Microsoft Windows', '20240615141340.png', 9, 'Initial release date: 12 August 2022\nEngine: Proprietary Engine\nDevelopers: Insomniac Games, Nixxes Software, Marvel Entertainment\nGenres: Adventure game, Fighting game, Platform game, Casual game,', 0, '2024-06-15'),
(13, 'counter strick', 655, 65, 'cs is free game on steam', '20240616141044.jpg', 9, 'cs', 1, '2024-06-16'),
(14, 'cyberpunk 2077 ', 233, 23, 'cyberpunk here', '20240616141438.jpg', 10, 'ayush', 0, '2024-06-16'),
(15, 'Minecraft ', 338, 24, 'Minecraft ', '20240616141944.jpg', 11, 'build', 0, '2024-06-16');

-- --------------------------------------------------------

--
-- Table structure for table `rememberme_tokens`
--

CREATE TABLE `rememberme_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expiration` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rememberme_tokens`
--

INSERT INTO `rememberme_tokens` (`id`, `user_id`, `token`, `expiration`) VALUES
(3, 8, '3dead9f64424ca7deaf7f3bef87d27be764628d73821d2e4faf4c0731b7d5846', '2024-05-20'),
(4, 8, '0ffac8ca58ddf349cb118a2258d84dfb88d4db7791ce3328b1630bb8df55fb7f', '2024-06-19'),
(5, 8, '7d1b175e61fc85f00a0ad9c230a453d1ff2cc35f943e26279c1c566e79947f5d', '2024-06-20'),
(6, 8, '84912a5922cd6b068e206b65ebc4053cbe04651b624b9150679c743332f80344', '2024-06-20'),
(7, 8, 'aa123d8a301b1025976d6eb2c2411385609693becca1251371af2281f9f8ae2a', '2024-06-20'),
(8, 8, '1fd8984ca58804b1fe81b75d1ef3b5545e7c8146b1252dc89970f46b3b23e6cd', '2024-06-20'),
(9, 8, 'ef4fc17142eb7f0d8fcfe984c553f5826be2168726378347e4a54b6a3d268c41', '2024-06-20'),
(10, 8, '2f14b44ea3341b896e38c9e01f15901fe9ecddc550a76b2fbb79e6dc4e4682a4', '2024-06-20'),
(11, 8, '736c0964f6db6b38b9550b4e72c2f7c5ab91bb1f61b83f85a57a191ee24b29b8', '2024-06-20'),
(12, 9, '999c4be2d14e97bd83bc0b7bab9d9c9a0b059d6f581fb4cfdce1c15d16f1c685', '2024-06-22'),
(13, 9, 'b37daf332387749fb7a8a232fa7e0c430a1dff2288df8e2f2be328b8a3a3b816', '2024-07-04'),
(14, 8, 'b0a45dc4a74333aaf7ef599c418a18207d8b978e8fc243f2fe2fb5d95494f0f2', '2024-07-04'),
(15, 8, 'b086155cc253ade8650c57304394ebb19e11123f6ea4f65d72b1ad736b7510f9', '2024-07-13'),
(16, 19, '9528a0d1a1214e8c8da9e5f7e65227a6e807a6573afdacdca89a39b56ad064cc', '2024-07-13'),
(17, 21, '9d10666a965a1c769feb97e73dc1ec007fcb3af66d31230b2d9ca0d1fa380bad', '2024-07-13'),
(18, 21, '75a8327aae2de2b39dac66b241cf0a254b1b2b77552767007ef7c6bf85a4e1e0', '2024-07-13'),
(19, 23, 'caf546bfc5172fe80b521ff095ab5698ecc300580f9681096ff95fb43ac107cc', '2024-07-13'),
(20, 23, '2b80eca5c32b5f1e6f7ecdfa9a55ceee5e79ad680dea2901fb8a4ce71871608a', '2024-07-13'),
(21, 23, 'fad407cb022f7fcd7583ed0f69ac0931bc682007ee6dfc46cce7883a39655f17', '2024-07-13'),
(22, 8, '072971900fe970980c009d84029f883d8fe4d4525897d71d307cf5469b8a1846', '2024-07-13'),
(23, 23, 'f45abcbf14bfd596c3a03d0aa1dd19ff29cdf60df8f01525426299bb7133aa9a', '2024-07-14'),
(24, 23, 'f33cd96d86cad001303a252060f0be0f0b1d6597fb48c8e4adb0e36ba97df22c', '2024-07-18'),
(25, 23, '8aa264e1730dd5e39e236d1b3734d99b804259ae8ba25ff0ab65943ee0b33163', '2024-07-18'),
(26, 9, 'bf377f88b2716c20ce51cb8becc2f69fefc5ac1526d8e14d88c26d89167a8f0a', '2024-07-18'),
(27, 23, 'd0f7c4fcf525d6ec77d6c9782ba9ed05880cfa70d924fb45b3b6bf0b9fa46411', '2024-07-18'),
(28, 23, '7c08507c78ae8c923b5267b0231792d07e2579e38bd4e12980579e2ca35a431e', '2024-07-18');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `data` varchar(200) NOT NULL,
  `data1` varchar(200) NOT NULL,
  `data2` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `data`, `data1`, `data2`) VALUES
(1, 'promo', 'dective', 'ind23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(200) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `profile` varchar(100) NOT NULL DEFAULT 'default.png',
  `code` varchar(255) NOT NULL,
  `ac_status` varchar(11) NOT NULL DEFAULT 'false',
  `type` varchar(100) NOT NULL DEFAULT 'email',
  `created_at` date NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `profile`, `code`, `ac_status`, `type`, `created_at`) VALUES
(8, 'ayush.exe', 'ayushsolanki2901@gmail.com', '$2y$10$HU0jUHYpdz0g4byR7db4/eYGxQ2oRrWcr1yatwJUM.J1PjdGYkgJK', 'default.png', '', 'true', 'email', '2024-05-20'),
(9, 'ayush.exeaa', 'kehovis114@huleos.com', '$2y$10$oazOZWPGFDmQzvTNkO7jLexclTk/66ZfCBWRS/ZiAHMEv1opoNkQO', 'default.png', '', 'true', 'email', '2024-05-23'),
(10, '123', 'todiha4958@adrais.com', '$2y$10$YU7Sa1xPSiFWh8aNou5a8OnML61THrPt19Du0CbYMu4SacFFDxtYm', 'default.png', 'qU9ZxAhBe52Pt0c68WWokdzUA2Ob-dP7cS-CsCZdpX7T2bHqVB7O', 'false', 'email', '2024-05-30'),
(23, 'Aimgod', 'aimgodmanagement@gmail.com', '', 'https://lh3.googleusercontent.com/a/ACg8ocJzQkixCcKcDEpBEZtvZNY-WmwBDU0TtmKuZvFsSKxTU6GAqCg=s96-c', '', 'true', 'google', '2024-06-13'),
(24, 'admin_march', 'yalate1111@fna6.com', '$2y$10$fYZ4rghBT0bXwlxISkC7QePe4Wbkbzv9ZIbT93vMbz2f8RKSNMdqu', 'default.png', '', 'true', 'email', '2024-06-13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupon_codes`
--
ALTER TABLE `coupon_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `multiple_images`
--
ALTER TABLE `multiple_images`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rememberme_tokens`
--
ALTER TABLE `rememberme_tokens`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `coupon_codes`
--
ALTER TABLE `coupon_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `multiple_images`
--
ALTER TABLE `multiple_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `rememberme_tokens`
--
ALTER TABLE `rememberme_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
