-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1
-- Время создания: Сен 30 2024 г., 14:12
-- Версия сервера: 10.4.32-MariaDB
-- Версия PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `ecommerce`
--

-- --------------------------------------------------------

--
-- Структура таблицы `addresses`
--

CREATE TABLE `addresses` (
  `address_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `street_address` varchar(255) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `blog_authors`
--

CREATE TABLE `blog_authors` (
  `author_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `blog_authors`
--

INSERT INTO `blog_authors` (`author_id`, `first_name`, `last_name`) VALUES
(1, 'Kristin', 'Watson'),
(2, 'Robert', 'Fox');

-- --------------------------------------------------------

--
-- Структура таблицы `blog_posts`
--

CREATE TABLE `blog_posts` (
  `post_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `author_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `published_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `blog_posts`
--

INSERT INTO `blog_posts` (`post_id`, `title`, `content`, `author_id`, `image_url`, `published_date`) VALUES
(1, 'First Time Home Owner Ideas', 'Content for the blog post goes here...', 1, 'images/blog-post-1.png', '2021-12-19'),
(2, 'How To Keep Your Furniture Clean', 'Content for the blog post goes here...', 2, 'images/blog-post-2.png', '2021-12-15'),
(3, 'Small Space Furniture Apartment Ideas', 'Content for the blog post goes here...', 1, 'images/blog-post-3.png', '2021-12-12');

-- --------------------------------------------------------

--
-- Структура таблицы `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Chairs'),
(2, 'Tables'),
(3, 'Sofas'),
(4, 'Beds'),
(5, 'Desks'),
(6, 'Wardrobes'),
(7, 'Bookshelves'),
(8, 'Cabinets'),
(9, 'Dressers'),
(10, 'Coffee Tables'),
(11, 'Stools'),
(12, 'Clothes');

-- --------------------------------------------------------

--
-- Структура таблицы `contact_messages`
--

CREATE TABLE `contact_messages` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Структура таблицы `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `owner_id` int(11) NOT NULL DEFAULT 1,
  `product_id` int(11) NOT NULL,
  `country` varchar(200) NOT NULL,
  `address` varchar(200) NOT NULL,
  `state` varchar(200) NOT NULL,
  `zip` varchar(50) NOT NULL,
  `phone` varchar(40) NOT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `message` text NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` varchar(50) NOT NULL DEFAULT 'Pending',
  `quantity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `owner_id`, `product_id`, `country`, `address`, `state`, `zip`, `phone`, `order_date`, `message`, `total_amount`, `status`, `quantity`) VALUES
(31, 1, 2, 0, '', '', '', '', '', '2024-09-27 07:34:56', '', 100.50, 'delivered', 0),
(32, 2, 3, 0, '', '', '', '', '', '2024-09-28 08:45:00', '', 200.75, 'pending', 0),
(35, 40, 45, 0, '2', 'Tashkent, Uzbekistan', 'Sergeli', '100012', '8913892033', '2024-09-30 07:54:44', 'Hi', 50.00, 'pending', 0),
(36, 40, 45, 0, '2', 'Tashkent, Uzbekistan', 'Sergeli', '100012', '8913892033', '2024-09-30 07:54:44', 'Hi', 50.00, 'pending', 0),
(37, 40, 45, 0, 'Russia', 'Tashkent, Uzbekistan', 'Sergeli', '100012', '8913892033', '2024-09-30 08:01:23', 'Hi', 50.00, 'cancelled', 0),
(38, 40, 45, 0, 'Russia', 'Tashkent, Uzbekistan', 'Sergeli', '100012', '8913892033', '2024-09-30 08:01:23', 'Hi', 50.00, 'delivered', 0),
(55, 40, 1, 0, 'Uzbekistan', '', '', '', '', '2024-09-30 08:57:14', '', 900.00, 'pending', 0),
(57, 40, 1, 0, 'Uzbekistan', '', '', '', '', '2024-09-30 08:59:34', '', 1200.00, 'pending', 0),
(58, 40, 1, 0, 'Uzbekistan', '', '', '', '', '2024-09-30 09:00:05', '', 1500.00, 'cancelled', 0),
(59, 40, 1, 0, 'Uzbekistan', '', '', '', '', '2024-09-30 09:03:39', '', 1500.00, 'pending', 0),
(60, 40, 1, 0, 'Uzbekistan', '', '', '', '', '2024-09-30 09:08:49', '', 800.00, 'pending', 0);

-- --------------------------------------------------------

--
-- Структура таблицы `order_details`
--

CREATE TABLE `order_details` (
  `order_detail_id` int(11) NOT NULL,
  `order_id` int(11) DEFAULT NULL,
  `product_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL,
  `total_amount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `order_details`
--

INSERT INTO `order_details` (`order_detail_id`, `order_id`, `product_id`, `quantity`, `total_amount`) VALUES
(1, 55, 14, 30, 900),
(2, 57, 14, 40, 1200),
(3, 58, 14, 50, 1500),
(4, 59, 14, 50, 1500),
(5, 60, 13, 40, 800);

-- --------------------------------------------------------

--
-- Структура таблицы `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `owner_id` int(11) NOT NULL DEFAULT 1,
  `quantity` int(11) NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `category_id`, `price`, `image_url`, `owner_id`, `quantity`, `status`) VALUES
(1, 'Nordic Chair', 1, 50.00, 'images/product-3.png', 0, 0, 0),
(3, 'Kruzo Aero Chair', 1, 78.00, 'images/product-2.png', 0, 0, 1),
(4, 'Ergonomic Chair', 1, 43.00, 'images/product-3.png', 0, 0, 1),
(13, 'Sofa', 3, 20.00, 'images/red_sofa.png', 45, 40, 1),
(14, 'Sofa', 3, 30.00, 'images/—Pngtree—sofa_5641036.png', 45, 50, 1),
(15, 'Switzerland table', 2, 40.00, 'images/istockphoto-175499936-612x612.jpg', 45, 40, 0);

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `role` varchar(30) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `password_hash`, `created_at`, `role`) VALUES
(1, 'Salimbay', 'Elimuratov', 'admin123@gmail.com', 'd41d8cd98f00b204e9800998ecf8427e', '2024-09-26 11:18:43', 'user'),
(2, 'Salimbay', 'Elimuratov', 'yelimuratovsalimbay@gmail.com', '$2y$10$eimzMVwA8YX2GVLMmI6P1.0k/0GM2eeVuDCXWpIxNWxUAkRsF8M7u', '2024-09-26 12:59:19', 'user'),
(6, 'newusertest', 'newusersurname', 'test@gmail.com', '$2y$10$G5pgIJfS4JkOrlpHOFrmnudZPIprqRCC0Njc1LU3M23ctW0otuY8q', '2024-09-27 03:14:13', 'user'),
(7, 'User0', 'User0', 'user0@example.com', '$2y$10$uB.NB/sY0b/Klzr8DNYun.q0UY5j9p.yo/9rhrp6JLHi9tu8ofTJ2', '2024-09-27 03:21:31', 'user'),
(8, 'User1', 'User1', 'user1@example.com', '$2y$10$mrRkCTnrCgJIO5YH2.SEOuVlvpkWnS.PgWeXRtz4VLwhd4/kDJp5y', '2024-09-27 03:21:31', 'user'),
(9, 'User2', 'User2', 'user2@example.com', '$2y$10$YYzLdxMvsJTQMzXoH.IvweRdse6iLepdgSRrF4/MTr0cMENzYgrji', '2024-09-27 03:21:31', 'user'),
(10, 'User3', 'User3', 'user3@example.com', '$2y$10$aXvbpZeA3brJdzvLlybXb.uDqPRljm0LVL4iRZxQi/W31CwEumf2O', '2024-09-27 03:21:31', 'user'),
(11, 'User4', 'User4', 'user4@example.com', '$2y$10$vzG4SSznXlmXb7rOuek9/eVGPZYJdC/nndGBytqh4HEJDnaEU.yyW', '2024-09-27 03:21:31', 'user'),
(14, 'User7', 'User7', 'user7@example.com', '$2y$10$sVSXnjNElVDMAc/Yw8GdSuM0Rk5cY1Vb2zA9kn7WfXDAs9nhwx4T6', '2024-09-27 03:21:31', 'user'),
(15, 'User8', 'User8', 'user8@example.com', '$2y$10$d.SHG52vZ9Uoj7g.EZnOgOF11sFl2QLXCmY4No26cjd6lTPaiUR8u', '2024-09-27 03:21:31', 'user'),
(16, 'User9', 'User9', 'user9@example.com', '$2y$10$3SfKMgDqDHocRs6nB2RHsuwtaX676XVMNoSYZ.2Dh2JH4kNa9qRiS', '2024-09-27 03:21:31', 'user'),
(17, 'User10', 'User10', 'user10@example.com', '$2y$10$s2YxHytKmDy0SA87tbNGNuAOqQCT3E8WGHBlu1y7Ju.1eVYGBa.9C', '2024-09-27 03:21:31', 'user'),
(18, 'User11', 'User11', 'user11@example.com', '$2y$10$2I4XdAqyalDNkW7pZxQXouTfMduTyiTjJiAd4eA3ykWZg9EHWZ4PC', '2024-09-27 03:21:31', 'user'),
(19, 'User12', 'User12', 'user12@example.com', '$2y$10$43CHYogoASXVFC5mbP7y7eqgGrrW8WC8lGB7Cij.8BFVJj09TZIia', '2024-09-27 03:21:31', 'user'),
(20, 'User13', 'User13', 'user13@example.com', '$2y$10$obDk4I/Q6SqIMiRJuRz/oOeaCq7iP40nHDn2T5sWGZy5aba3AtWb.', '2024-09-27 03:21:31', 'user'),
(21, 'User14', 'User14', 'user14@example.com', '$2y$10$I62YYIZEQsamSsJhpCxCUu5IJFODaKcGxGk9dWTsGMNibf8GX4id6', '2024-09-27 03:21:31', 'user'),
(22, 'User15', 'User15', 'user15@example.com', '$2y$10$0dQt7tdrpW/fx2.aBSRILu.pjwrmy78qbo/GT9u9lxdgndsmLfLm6', '2024-09-27 03:21:31', 'user'),
(23, 'User16', 'User16', 'user16@example.com', '$2y$10$IE1Nb4mOWgHHRVrYVfKc7uZKqZ9Ews1ajl3hpLQWyUSnQuKNiOJeC', '2024-09-27 03:21:32', 'user'),
(24, 'User17', 'User17', 'user17@example.com', '$2y$10$H4ADke.9qRe3qoNt/KrXXe99.LfHOuPGMbfXf7mAa7BNNEEH0BcRC', '2024-09-27 03:21:32', 'user'),
(25, 'User18', 'User18', 'user18@example.com', '$2y$10$DT76QxM5EhW47UQWXyXTI.AOGs/g6W4JiJgNv/85VIkfzySurxJyO', '2024-09-27 03:21:32', 'user'),
(26, 'User19', 'User19', 'user19@example.com', '$2y$10$t0HFvuqj22JK8NDUUbkrRe2l5UGWErun2KtdmneJ601UaMfaGHmHG', '2024-09-27 03:21:32', 'user'),
(27, 'User20', 'User20', 'user20@example.com', '$2y$10$VNL9FLforQXavxJ4R2RUVur/pHgoY1s5z.c0OfabjnnbkDGIMY68O', '2024-09-27 03:21:32', 'user'),
(28, 'User21', 'User21', 'user21@example.com', '$2y$10$.agOeJk7xjb9bqHcOTYAR.GxPwZMa8fozTtB8BDZuqXBjRtyuuGLi', '2024-09-27 03:21:32', 'user'),
(29, 'User22', 'User22', 'user22@example.com', '$2y$10$EjXZavHmuwwyDj1jApD1juV/AUC08wJbLgNrAb.CoF9TfdnaLwZ8e', '2024-09-27 03:21:32', 'user'),
(30, 'User23', 'User23', 'user23@example.com', '$2y$10$uA.A/SOZcQOhydgJPciasOMGS0uUykIrg.IHcer2/EpYYeaCr6L7.', '2024-09-27 03:21:32', 'user'),
(31, 'User24', 'User24', 'user24@example.com', '$2y$10$fZKaNOq14xGS4TQHGnrKEONTlt5wDod/TY.wvBrRjZ0CkW3unyRjS', '2024-09-27 03:21:32', 'user'),
(32, 'User25', 'User25', 'user25@example.com', '$2y$10$iNamZXI66RnvjqM.0pKZduWXm/hy0XFWiE/u9e.I.MvvSyfpx8nU2', '2024-09-27 03:21:32', 'user'),
(33, 'User26', 'User26', 'user26@example.com', '$2y$10$t2eJIyofx3gHKoHIhZZuC.1h/ifxD1iTBhs8jaFzD66eRqD5YWBE.', '2024-09-27 03:21:32', 'user'),
(34, 'User27', 'User27', 'user27@example.com', '$2y$10$KgJrBwhtINyv85NrgjXTUuBlMXKF51185EQp.Z2R4.GqwCZLSGJDK', '2024-09-27 03:21:32', 'user'),
(35, 'User28', 'User28', 'user28@example.com', '$2y$10$qFFrU9eUzU.pkN0CWs/Ux.lHkCjkArbivOPKAkW/8mqnTpmtP8GbW', '2024-09-27 03:21:32', 'user'),
(36, 'User29', 'User29', 'user29@example.com', '$2y$10$hu/iVIKETem6ABZAOhEO5.JgJWrTnR7yrILYCN9ri.oGaovnzkb4C', '2024-09-27 03:21:32', 'user'),
(38, 'Salimbay', 'Elimuratov', 'Superadmin@gmail.com', '$2y$10$39LE15FYaHxxwplipEfAo.FHMpspY5WcRhY19nK15W7BNDAdDTNhW', '2024-09-27 03:54:02', 'admin'),
(39, 'Salimbay', 'Elimuratov', 'user@gmail.com', '4297f44b13955235245b2497399d7a93', '2024-09-27 06:23:24', 'user'),
(40, 'user', 'user', 'user2@gmail.com', '4297f44b13955235245b2497399d7a93', '2024-09-27 06:40:45', 'user'),
(45, 'Salimbay', 'Elimuratov', 'admin@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-09-27 12:33:11', 'admin'),
(46, 'Salimbay', 'Elimuratov', 'testus@gmail.com', '4297f44b13955235245b2497399d7a93', '2024-09-27 12:55:08', 'user');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`address_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `blog_authors`
--
ALTER TABLE `blog_authors`
  ADD PRIMARY KEY (`author_id`);

--
-- Индексы таблицы `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Индексы таблицы `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Индексы таблицы `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Индексы таблицы `order_details`
--
ALTER TABLE `order_details`
  ADD PRIMARY KEY (`order_detail_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Индексы таблицы `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `addresses`
--
ALTER TABLE `addresses`
  MODIFY `address_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `blog_authors`
--
ALTER TABLE `blog_authors`
  MODIFY `author_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `blog_posts`
--
ALTER TABLE `blog_posts`
  MODIFY `post_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT для таблицы `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT для таблицы `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT для таблицы `order_details`
--
ALTER TABLE `order_details`
  MODIFY `order_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблицы `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `blog_posts`
--
ALTER TABLE `blog_posts`
  ADD CONSTRAINT `blog_posts_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `blog_authors` (`author_id`);

--
-- Ограничения внешнего ключа таблицы `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Ограничения внешнего ключа таблицы `order_details`
--
ALTER TABLE `order_details`
  ADD CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Ограничения внешнего ключа таблицы `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
