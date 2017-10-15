-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1:3306
-- Vytvořeno: Ned 15. říj 2017, 18:29
-- Verze serveru: 5.7.19
-- Verze PHP: 7.1.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databáze: `zcu_kiv_web_seminar`
--

-- --------------------------------------------------------

--
-- Struktura tabulky `web_sessions`
--

DROP TABLE IF EXISTS `web_sessions`;
CREATE TABLE IF NOT EXISTS `web_sessions` (
  `id` varchar(64) COLLATE utf8_czech_ci NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `ip` varchar(40) COLLATE utf8_czech_ci NOT NULL,
  `browser` varchar(150) COLLATE utf8_czech_ci NOT NULL,
  `session_start` int(11) UNSIGNED NOT NULL,
  `remember` tinyint(1) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `web_users`
--

DROP TABLE IF EXISTS `web_users`;
CREATE TABLE IF NOT EXISTS `web_users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `username_clean` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `lastvisit` int(11) UNSIGNED NOT NULL,
  `verified` tinyint(1) UNSIGNED NOT NULL,
  `regdate` int(11) UNSIGNED NOT NULL,
  `registered` tinyint(1) UNSIGNED NOT NULL,
  `actkey` varchar(23) COLLATE utf8_czech_ci NOT NULL,
  `form_salt` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `web_users`
--

INSERT INTO `web_users` (`id`, `username`, `username_clean`, `email`, `password`, `lastvisit`, `verified`, `regdate`, `registered`, `actkey`, `form_salt`) VALUES
(1, 'Anonymous', 'anonymous', '', '', 0, 0, 0, 0, '', '');

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `web_sessions`
--
ALTER TABLE `web_sessions`
  ADD CONSTRAINT `web_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `web_users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
