-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1
-- Üretim Zamanı: 09 Ağu 2022, 23:06:30
-- Sunucu sürümü: 10.4.24-MariaDB
-- PHP Sürümü: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `buybox`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `parentid` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `category`
--

INSERT INTO `category` (`id`, `parentid`, `title`, `keywords`, `description`, `image`, `status`, `created_at`, `updated_at`) VALUES
(1, 0, 'Teknoloji', 'teknoloji,telefon,tablet,bilgisayar', 'Tüm teknolojik aletler', '1e7bed16f5fd91658076d1d8f60f727b.jpg', 'True', NULL, '0000-00-00 00:00:00'),
(3, 0, 'Erkek', 'erkek,erkek giyim,tshirt,pantolon', 'Erkeklerin aradığı herşey', 'bb35c3850eeba92a9db6b43ea9c914dc.jpg', 'True', NULL, '0000-00-00 00:00:00'),
(12, 3, 'Erkek Giyim', 'erkek,giyim,şort,ayakkabı,tshirt,gözlük', 'Erkek giyimdeki tüm ürünler', '5c3d4bfd98526d8502befe63d959736f.jpg', 'True', NULL, NULL),
(13, 12, 'Erkek Ayakkabı', 'erkek,ayakkabı,spor', 'Erkeklerin aradığı tüm ayakkabılar', 'cd3549d4b68265b27b2671eff2fdb1ec.jpg', 'True', NULL, NULL),
(14, 0, 'Kadın', 'kadın,giyim,makyaj,bakım', 'Kadınların aradığı herşey', 'c038c5d7de5178104d7e29a5a3660ed0.jpg', 'True', NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `discount`
--

CREATE TABLE `discount` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Tablo döküm verisi `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220729190339', '2022-07-29 21:03:53', 212),
('DoctrineMigrations\\Version20220730094100', '2022-07-30 11:41:55', 338),
('DoctrineMigrations\\Version20220730222920', '2022-07-31 00:42:44', 281),
('DoctrineMigrations\\Version20220731070742', '2022-07-31 09:19:51', 199),
('DoctrineMigrations\\Version20220731090739', '2022-07-31 11:07:50', 50),
('DoctrineMigrations\\Version20220731111048', '2022-07-31 13:10:58', 33),
('DoctrineMigrations\\Version20220802110521', '2022-08-02 13:10:10', 236),
('DoctrineMigrations\\Version20220802113531', '2022-08-02 13:35:40', 44),
('DoctrineMigrations\\Version20220803093746', '2022-08-03 11:38:33', 380),
('DoctrineMigrations\\Version20220803161312', '2022-08-03 18:14:54', 55),
('DoctrineMigrations\\Version20220804200705', '2022-08-04 22:07:22', 193),
('DoctrineMigrations\\Version20220804200900', '2022-08-04 22:09:03', 32),
('DoctrineMigrations\\Version20220804205905', '2022-08-04 22:59:08', 34),
('DoctrineMigrations\\Version20220805095954', '2022-08-05 11:59:58', 536),
('DoctrineMigrations\\Version20220806072646', '2022-08-06 09:26:59', 232),
('DoctrineMigrations\\Version20220808100320', '2022-08-08 12:03:27', 53),
('DoctrineMigrations\\Version20220809091339', '2022-08-09 11:13:56', 600);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `image`
--

INSERT INTO `image` (`id`, `product_id`, `title`, `image`) VALUES
(7, 2, 'telefon1', '2c7492f051c86914aaf4f652afe4d7d6.jpg'),
(8, 2, 'telefon2', 'd121254a0eb2cd9710558312ef56f4b3.jpg'),
(14, 4, 'kundura1', '8d9c72f3530813b833646cd0781155c3.webp'),
(15, 4, 'kundura2', '3e165115e204dde7b7b3f10e943a9291.webp'),
(16, 4, 'kundura3', '56fcd43a18454fbe0f55e741293e675f.webp'),
(17, 6, 'tshirt1', '5de11d6b9921c1b009f9ef23a64e79a9.webp'),
(18, 6, 'tshirt2', 'bc237a0cbc105f089f71a1cd53a659e7.webp'),
(19, 6, 'tshirt3', 'e0870c51311a0a33810a936c467116fd.webp');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `message`
--

INSERT INTO `message` (`id`, `name`, `subject`, `message`, `status`, `ip`, `created_at`, `updated_at`, `email`) VALUES
(5, 'Test', 'Test Konu', 'Test mesajı', 'New', '127.0.0.1', '2022-08-09 22:02:23', NULL, 'test@gmail.com');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `userid` int(11) DEFAULT NULL,
  `productid` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `surname` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ip` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `price` int(11) DEFAULT NULL,
  `shipping` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `orders`
--

INSERT INTO `orders` (`id`, `userid`, `productid`, `name`, `surname`, `email`, `address`, `phone`, `ip`, `created_at`, `price`, `shipping`) VALUES
(20, 4, '2', 'Admin', 'Test', 'admintest@gmail.com', 'Admin Test Adress', '0123 456 7890', '127.0.0.1', '2022-08-09 21:57:43', 42000, 'Kapıda ödeme'),
(21, 4, '3,4', 'Admin', 'Test', 'admintest@gmail.com', 'Admin Test Adress', '0123 456 7890', '127.0.0.1', '2022-08-09 22:01:12', 1500, 'Kapıda ödeme');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `detail` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` double NOT NULL,
  `stock` int(11) NOT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product`
--

INSERT INTO `product` (`id`, `title`, `description`, `keywords`, `image`, `detail`, `price`, `stock`, `status`, `created_at`, `updated_at`) VALUES
(2, 'Telefon', 'Akıllı Telefon', 'android,telefon,ram', 'f28168f879d2c213bd9268e8cf8f4e91.jpg', '8 gb ram,256 gb hafızalı telefon', 7000, 100, 'True', NULL, NULL),
(3, 'Erkek Koşu Ayakkabısı', 'Erkek Koşu Ayakkabısı', 'erkek,koşu,ayakkabı', 'cb5b7598c4f754c0e27a8a4aba444af8.jpg', 'Günlük kullanım için ideal erkek koşu ayakkabısı', 100, 1, 'True', NULL, NULL),
(4, 'Erkek Kundura', 'Erkek Kundura', 'erkek,kundura,şık,siyah', '95867a8637b5abfcc44e1de42b707a6d.webp', 'asd', 300, 1, 'True', NULL, NULL),
(6, 'Kadın Tshirt', 'Siyah tshirt', 'kadın,siyah,tshirt', 'c3202a48ceb57ec0d8c89a35b6bddad1.webp', 'Kadın siyah tshirt', 80, 1, 'True', NULL, NULL);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product_category`
--

CREATE TABLE `product_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
(2, 1),
(3, 3),
(3, 13),
(4, 3),
(4, 12),
(4, 13),
(6, 14);

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `setting`
--

CREATE TABLE `setting` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `keywords` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `companyname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fax` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `instagram` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `twitter` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `about` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `contact` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reference` longtext COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(6) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `setting`
--

INSERT INTO `setting` (`id`, `title`, `keywords`, `description`, `companyname`, `address`, `phone`, `fax`, `email`, `facebook`, `instagram`, `twitter`, `about`, `contact`, `reference`, `status`) VALUES
(1, 'BuyBox', 'buybox,alışveriş,kadın,erkek', 'Türkiyenin en iyi alışveriş sitesi Buybox', 'BuyBox', 'Küçükbakkalköy Mah. Kayışdağı Cd. Allianz Tower 1/16 34750 Ataşehir İstanbul', '+850 000 0000', '+232 987 6543', 'ornek@gmail.com', 'https://www.facebook.com/', 'https://www.instagram.com', 'https://www.twitter.com', '<h1>BuyBox: T&uuml;rkiye&rsquo;nin Alışveriş Sitesi!</h1>\r\n\r\n<p><strong>Lorem Ipsum</strong>&nbsp;is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#39;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p>\r\n\r\n<div id=\"gtx-trans\" style=\"left:-56px; position:absolute; top:86.5938px\">\r\n<div class=\"gtx-trans-icon\">&nbsp;</div>\r\n</div>', '<h2>Harita</h2>\r\n\r\n<p><iframe frameborder=\"0\" height=\"400\" scrolling=\"no\" src=\"https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d7200.391109189414!2d29.100484804490744!3d40.981440008555765!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x4729ef41834c4f79!2sEnuygun!5e1!3m2!1str!2str!4v1642488325571!5m2!1str!2str\" title=\"Map\" width=\"100%\"></iframe></p>', '<p>asd</p>', 'True');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `shopcart`
--

CREATE TABLE `shopcart` (
  `id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `productid` int(11) DEFAULT NULL,
  `quantity` int(11) DEFAULT NULL,
  `totalprice` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surname` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime DEFAULT NULL,
  `updated_at` datetime DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Tablo döküm verisi `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `surname`, `image`, `status`, `created_at`, `updated_at`, `is_verified`, `address`, `phone`) VALUES
(4, 'admintest@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$7R.U7voq7lc.3RUCdG.O5.SXh20rfnFMFXsHU3Jwj7o3kLfG.ELBO', 'Admin', 'Test', 'ccf14dd0534082357c057c4687ff6b29.png', 'True', NULL, '2022-08-09 21:56:55', 0, 'Admin Test Adress', '0123 456 7890'),
(11, 'test@gmail.com', '[]', '$2y$13$n0VzI/.nmUYDDyGWcinOxeneeVo6CWS/bbjdM07g0NklFGHQ4W7XS', 'Test', 'User', '3c4daf9c6b5862ea1fde6e4ae2a58f68.png', 'True', '2022-08-09 11:18:59', '2022-08-09 11:21:45', 0, '4000/1 sokak no:5 Bornova/İzmir', '0123 456 7890');

--
-- Dökümü yapılmış tablolar için indeksler
--

--
-- Tablo için indeksler `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `discount`
--
ALTER TABLE `discount`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Tablo için indeksler `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C53D045F4584665A` (`product_id`);

--
-- Tablo için indeksler `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Tablo için indeksler `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `IDX_CDFC73564584665A` (`product_id`),
  ADD KEY `IDX_CDFC735612469DE2` (`category_id`);

--
-- Tablo için indeksler `setting`
--
ALTER TABLE `setting`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `shopcart`
--
ALTER TABLE `shopcart`
  ADD PRIMARY KEY (`id`);

--
-- Tablo için indeksler `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Dökümü yapılmış tablolar için AUTO_INCREMENT değeri
--

--
-- Tablo için AUTO_INCREMENT değeri `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Tablo için AUTO_INCREMENT değeri `discount`
--
ALTER TABLE `discount`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Tablo için AUTO_INCREMENT değeri `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- Tablo için AUTO_INCREMENT değeri `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Tablo için AUTO_INCREMENT değeri `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Tablo için AUTO_INCREMENT değeri `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- Tablo için AUTO_INCREMENT değeri `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Tablo için AUTO_INCREMENT değeri `setting`
--
ALTER TABLE `setting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Tablo için AUTO_INCREMENT değeri `shopcart`
--
ALTER TABLE `shopcart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=130;

--
-- Tablo için AUTO_INCREMENT değeri `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Dökümü yapılmış tablolar için kısıtlamalar
--

--
-- Tablo kısıtlamaları `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F4584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Tablo kısıtlamaları `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `FK_CDFC735612469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CDFC73564584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
