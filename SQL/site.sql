-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3306
-- Üretim Zamanı: 01 Haz 2021, 18:22:46
-- Sunucu sürümü: 5.7.31
-- PHP Sürümü: 7.3.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `site`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `ord`
--

DROP TABLE IF EXISTS `ord`;
CREATE TABLE IF NOT EXISTS `ord` (
  `ord_id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) NOT NULL,
  `pr_id` int(11) NOT NULL,
  `ord_type` enum('0','1') COLLATE utf8_turkish_ci NOT NULL,
  `ord_damat` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `ord_gelin` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `ord_aile` varchar(250) COLLATE utf8_turkish_ci NOT NULL,
  `ord_mani` varchar(500) COLLATE utf8_turkish_ci NOT NULL,
  `ord_tarih` date NOT NULL,
  `ord_saat` time NOT NULL,
  `ord_adres` varchar(250) COLLATE utf8_turkish_ci DEFAULT NULL,
  `ord_file` varchar(250) COLLATE utf8_turkish_ci DEFAULT NULL,
  PRIMARY KEY (`ord_id`)
) ENGINE=MyISAM AUTO_INCREMENT=20 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `product`
--

DROP TABLE IF EXISTS `product`;
CREATE TABLE IF NOT EXISTS `product` (
  `pr_id` int(11) NOT NULL AUTO_INCREMENT,
  `pr_name` varchar(150) COLLATE utf8_turkish_ci NOT NULL,
  `pr_img` varchar(250) COLLATE utf8_turkish_ci NOT NULL,
  `pr_status` enum('0','1') COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`pr_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `product`
--

INSERT INTO `product` (`pr_id`, `pr_name`, `pr_img`, `pr_status`) VALUES
(1, 'Çizgili Kalın Kapaklı Yaldızlı Davetiye', 'img/1.jpg', '1'),
(2, 'Eflatun Çiçek Deseli, Craft Zarflı Davetiye', 'img/2.jpg', '1'),
(3, 'Yaldızlı Başak Kurdelalı Craft Zarflı Davetiye', 'img/3.jpg', '1'),
(4, 'Nergis Motifli Kare Davetiye', 'img/4.jpg', '1'),
(5, 'Şeffaf Yaldızlı Baskılı Mühürlü Siyah Zarf Davetiye', 'img/5.jpg', '1'),
(6, 'Siyah İsim Mühürlü Kağıt Davetiye', 'img/6.jpg', '1'),
(7, 'Siyah Yatay Davetiye', 'img/7.jpg', '1'),
(8, 'Gri Karton Kabartma Desenli Davetiye', 'img/8.jpg', '1'),
(9, 'Basic Kırmızı Davetiye', 'img/9.jpg', '1');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_mail` varchar(150) COLLATE utf8_turkish_ci NOT NULL,
  `user_pass` varchar(250) COLLATE utf8_turkish_ci NOT NULL,
  `user_surname` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `user_lastname` varchar(100) COLLATE utf8_turkish_ci NOT NULL,
  `user_status` enum('0','1') COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
