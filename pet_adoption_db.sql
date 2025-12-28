-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 18, 2025 at 03:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pet_adoption_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `adoption_applications`
--

CREATE TABLE `adoption_applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `pet_id` int(11) DEFAULT NULL,
  `application_text` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `application_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `adoption_applications`
--

INSERT INTO `adoption_applications` (`id`, `user_id`, `pet_id`, `application_text`, `status`, `application_date`) VALUES
(1, 2, 1, 'sss', 'rejected', '2025-12-08 12:00:39'),
(2, 2, 3, 's', 'approved', '2025-12-08 12:47:43');

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `breed` varchar(50) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `city` varchar(50) NOT NULL DEFAULT 'İstanbul',
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `status` enum('available','pending','adopted') DEFAULT 'available',
  `owner_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pets`
--

INSERT INTO `pets` (`id`, `name`, `breed`, `age`, `city`, `description`, `image_path`, `status`, `owner_id`) VALUES
(1, 'Pamuk', 'Golden Retriever', 2, 'İstanbul', 'Çok oyuncu, çocuklarla arası iyi.', 'dog1.jpg', 'available', NULL),
(2, 'Duman', 'British Shorthair', 1, 'İstanbul', 'Sakin, ev ortamına alışkın.', 'cat1.jpg', 'available', NULL),
(3, 'Karabaş', 'Anadolu çoban köpeği', 1, 'İstanbul', 'Uslu,sakin.', '1765197351_dog2.jpg', 'adopted', NULL),
(6, 'Zeytin', 'Kara Kedi', 1, 'İstanbul', 'Uğursuz değil uğurludur :) Çok oyuncu.', 'cat2.jpg', 'available', NULL),
(7, 'Limon', 'Kanarya', 1, 'Bursa', 'Ötüşüyle evi şenlendirir, çok neşeli.', 'bird1.jpg', 'available', NULL),
(8, 'Baron', 'Rotweiler', 3, 'İzmir', 'Görünüşü sert ama kalbi pamuk gibi. Bahçeli ev arıyor.', 'dog3.jpg', 'available', NULL),
(11, 'Asil', 'Pitbull', 3, 'Ankara', 'Çok tatlıdır, akıllıdır, ödül maması çok sever, bacakları kısa.', '1765287005_24E608F7-750F-425D-A14D-36A0B4A18D4E.JPG', 'available', 3),
(12, 'Samet', 'Tekir Kedi', 2, 'Bolu', 'Çok sakin, sürekli aç ve sevilmeyi sever. ', '1765287377_20be7e82-d059-4ce2-89af-ce5980ea7524.JPG', 'available', 2);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `role`, `created_at`) VALUES
(2, 'berraers', 'berra-ersoy@hotmail.com', '$2y$10$Uruq8oeX8uyNBcsxplVZ1eEg5LUvK6.lRVoFed.LepemtuqNAFMOS', 'admin', '2025-12-08 11:58:08'),
(3, 'ali', 'ali@gmail.com', '$2y$10$ZqKGu.JhRAv7HJxb7rBEoOroxau31JlenOJm7Y3NWaYgPgbE2JBxi', 'user', '2025-12-09 13:24:09');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `adoption_applications`
--
ALTER TABLE `adoption_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `pet_id` (`pet_id`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
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
-- AUTO_INCREMENT for table `adoption_applications`
--
ALTER TABLE `adoption_applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `adoption_applications`
--
ALTER TABLE `adoption_applications`
  ADD CONSTRAINT `adoption_applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `adoption_applications_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;