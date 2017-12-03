-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1:3306
-- Vytvořeno: Ned 03. pro 2017, 18:13
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
-- Struktura tabulky `web_calendar_events`
--

DROP TABLE IF EXISTS `web_calendar_events`;
CREATE TABLE IF NOT EXISTS `web_calendar_events` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `server_id` bigint(20) UNSIGNED NOT NULL,
  `type` tinyint(1) UNSIGNED NOT NULL,
  `title` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `description` text COLLATE utf8_czech_ci NOT NULL,
  `time_from` int(11) UNSIGNED NOT NULL,
  `time_to` int(11) UNSIGNED NOT NULL,
  `time_estimated_hours` int(11) UNSIGNED NOT NULL,
  `rating` text COLLATE utf8_czech_ci NOT NULL,
  `recorded` tinyint(1) UNSIGNED NOT NULL,
  `edited` tinyint(1) UNSIGNED NOT NULL,
  `edited_time` int(11) UNSIGNED NOT NULL,
  `founder_user_id` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `server_id` (`server_id`),
  KEY `founder_user_id` (`founder_user_id`),
  KEY `recorded` (`recorded`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `web_calendar_event_sections`
--

DROP TABLE IF EXISTS `web_calendar_event_sections`;
CREATE TABLE IF NOT EXISTS `web_calendar_event_sections` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `calendar_event_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(15) COLLATE utf8_czech_ci NOT NULL,
  `is_limited` tinyint(1) UNSIGNED NOT NULL,
  `limit_max` tinyint(4) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `calendar_event_id` (`calendar_event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `web_calendar_event_users`
--

DROP TABLE IF EXISTS `web_calendar_event_users`;
CREATE TABLE IF NOT EXISTS `web_calendar_event_users` (
  `calendar_event_section_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notice` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `participation` tinyint(1) UNSIGNED NOT NULL,
  `joined_time` int(11) UNSIGNED NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `calendar_event_section_id` (`calendar_event_section_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

-- --------------------------------------------------------

--
-- Struktura tabulky `web_groups`
--

DROP TABLE IF EXISTS `web_groups`;
CREATE TABLE IF NOT EXISTS `web_groups` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_czech_ci NOT NULL,
  `server_group` tinyint(1) UNSIGNED NOT NULL,
  `priority` tinyint(3) UNSIGNED NOT NULL,
  `color` varchar(6) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `server_group` (`server_group`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `web_groups`
--

INSERT INTO `web_groups` (`id`, `name`, `server_group`, `priority`, `color`) VALUES
(1, 'Administrator', 0, 100, 'FF0000'),
(2, 'Moderator', 0, 75, '00FF00'),
(3, 'Master', 1, 50, 'F98D00'),
(4, 'Member', 1, 25, '00C12B'),
(5, 'Recruit', 1, 10, 'D8005F'),
(6, 'Recruiter', 1, 40, '4286F4'),
(7, 'Organizer', 1, 30, 'DAE023');

-- --------------------------------------------------------

--
-- Struktura tabulky `web_servers`
--

DROP TABLE IF EXISTS `web_servers`;
CREATE TABLE IF NOT EXISTS `web_servers` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8_czech_ci NOT NULL,
  `access_type` tinyint(1) UNSIGNED NOT NULL COMMENT '0=public;1=protected;2=private',
  `has_background_placeholder` tinyint(1) UNSIGNED NOT NULL,
  `edited_at` int(11) UNSIGNED NOT NULL,
  `created_at` int(11) UNSIGNED NOT NULL,
  `owner` bigint(20) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `owner` (`owner`)
) ENGINE=InnoDB AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `web_servers`
--

INSERT INTO `web_servers` (`id`, `name`, `access_type`, `has_background_placeholder`, `edited_at`, `created_at`, `owner`) VALUES
(1, 'Small Community', 1, 0, 0, 0, 0),
(2, 'ZGame', 1, 0, 0, 0, 0),
(3, 'Overwatch', 0, 0, 0, 0, 0),
(4, 'GGCommunity', 2, 0, 0, 0, 0),
(25, 'Dota2 Room', 0, 0, 1511723310, 1511624618, 4);

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
  KEY `users_id` (`user_id`),
  KEY `ip` (`ip`),
  KEY `remember` (`remember`),
  KEY `browser` (`browser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `web_sessions`
--

INSERT INTO `web_sessions` (`id`, `user_id`, `ip`, `browser`, `session_start`, `remember`) VALUES
('300cf1e5c5649881a5d1408994fae5d5d5acf8094fd746165ab6f68a4af22f17', 4, '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36', 1511191905, 1),
('800fba8ac1afe1b5f45d2ff8dc918b677c7e3f7e7a9bfd9ec756d40674037d0e', 4, '::1', 'Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Mobile Safari/537.36', 1511255844, 1);

-- --------------------------------------------------------

--
-- Struktura tabulky `web_users`
--

DROP TABLE IF EXISTS `web_users`;
CREATE TABLE IF NOT EXISTS `web_users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `username` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `username_clean` varchar(20) COLLATE utf8_czech_ci NOT NULL,
  `email` varchar(150) COLLATE utf8_czech_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `lastvisit` int(11) UNSIGNED NOT NULL,
  `registered` tinyint(1) UNSIGNED NOT NULL,
  `register_time` int(11) UNSIGNED NOT NULL,
  `verified` tinyint(1) UNSIGNED NOT NULL,
  `actkey` varchar(23) COLLATE utf8_czech_ci NOT NULL,
  `form_salt` varchar(32) COLLATE utf8_czech_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `username_clean` (`username_clean`),
  KEY `verified` (`verified`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `web_users`
--

INSERT INTO `web_users` (`id`, `username`, `username_clean`, `email`, `password`, `lastvisit`, `registered`, `register_time`, `verified`, `actkey`, `form_salt`) VALUES
(1, 'Anonymous', 'anonymous', '', '', 0, 0, 0, 0, '', ''),
(4, 'Frixs', 'frixs', 'frixs.dotlog@gmail.com', '$2y$12$568qvZ2pgEW06FZUC9f38OXPmwUkqX5J2PLrt6WN1XbyFcJ1FVfOW', 1509993601, 1, 1509993601, 0, '', '3ef205b0117f9d6df28908f1c0524260'),
(5, 'TestFrixs', 'testfrixs', 'testfrixs@testfrixs.com', '$2y$12$568qvZ2pgEW06FZUC9f38OXPmwUkqX5J2PLrt6WN1XbyFcJ1FVfOW', 1507893603, 1, 1507793602, 0, '', '3ef205b0117f9d6df28908f1c0524260');

-- --------------------------------------------------------

--
-- Struktura tabulky `web_user_groups`
--

DROP TABLE IF EXISTS `web_user_groups`;
CREATE TABLE IF NOT EXISTS `web_user_groups` (
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `group_id` bigint(20) UNSIGNED NOT NULL,
  `server_id` bigint(20) UNSIGNED DEFAULT NULL,
  KEY `user_id` (`user_id`),
  KEY `group_id` (`group_id`),
  KEY `server_id` (`server_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `web_user_groups`
--

INSERT INTO `web_user_groups` (`user_id`, `group_id`, `server_id`) VALUES
(4, 4, 1),
(4, 3, 1),
(4, 5, 3),
(4, 3, 25),
(4, 4, 25),
(4, 1, NULL),
(5, 4, 25);

--
-- Omezení pro exportované tabulky
--

--
-- Omezení pro tabulku `web_calendar_events`
--
ALTER TABLE `web_calendar_events`
  ADD CONSTRAINT `web_calendar_events_ibfk_1` FOREIGN KEY (`server_id`) REFERENCES `web_servers` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `web_calendar_event_sections`
--
ALTER TABLE `web_calendar_event_sections`
  ADD CONSTRAINT `web_calendar_event_sections_ibfk_1` FOREIGN KEY (`calendar_event_id`) REFERENCES `web_calendar_events` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `web_calendar_event_users`
--
ALTER TABLE `web_calendar_event_users`
  ADD CONSTRAINT `web_calendar_event_users_ibfk_1` FOREIGN KEY (`calendar_event_section_id`) REFERENCES `web_calendar_event_sections` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `web_calendar_event_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `web_users` (`id`) ON DELETE SET NULL;

--
-- Omezení pro tabulku `web_sessions`
--
ALTER TABLE `web_sessions`
  ADD CONSTRAINT `web_sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `web_users` (`id`) ON DELETE CASCADE;

--
-- Omezení pro tabulku `web_user_groups`
--
ALTER TABLE `web_user_groups`
  ADD CONSTRAINT `web_user_groups_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `web_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `web_user_groups_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `web_groups` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `web_user_groups_ibfk_3` FOREIGN KEY (`server_id`) REFERENCES `web_servers` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
