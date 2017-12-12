-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Počítač: 127.0.0.1:3306
-- Vytvořeno: Úte 12. pro 2017, 23:50
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
  `title` varchar(30) COLLATE utf8_czech_ci NOT NULL,
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
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `web_calendar_events`
--

INSERT INTO `web_calendar_events` (`id`, `server_id`, `type`, `title`, `description`, `time_from`, `time_to`, `time_estimated_hours`, `rating`, `recorded`, `edited`, `edited_time`, `founder_user_id`) VALUES
(1, 25, 0, 'První akce', '', 1512684488, 0, 1, '', 0, 0, 0, 4),
(3, 25, 0, 'Testovací akce 01', 'Něco drobného, ale nic velkého.', 1513180800, 0, 4, '', 0, 1, 1513118378, 4);

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
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `web_calendar_event_sections`
--

INSERT INTO `web_calendar_event_sections` (`id`, `calendar_event_id`, `name`, `is_limited`, `limit_max`) VALUES
(1, 1, 'Boss', 1, 10),
(2, 1, 'Others', 0, 0),
(3, 3, 'Nováčci', 0, 0),
(4, 3, 'Borci', 1, 3);

-- --------------------------------------------------------

--
-- Struktura tabulky `web_calendar_event_users`
--

DROP TABLE IF EXISTS `web_calendar_event_users`;
CREATE TABLE IF NOT EXISTS `web_calendar_event_users` (
  `calendar_event_section_id` bigint(20) UNSIGNED NOT NULL,
  `calendar_event_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `notice` varchar(255) COLLATE utf8_czech_ci NOT NULL,
  `participation` tinyint(1) UNSIGNED NOT NULL,
  `joined_time` int(11) UNSIGNED NOT NULL,
  KEY `user_id` (`user_id`),
  KEY `calendar_event_section_id` (`calendar_event_section_id`),
  KEY `calendar_event_id` (`calendar_event_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `web_calendar_event_users`
--

INSERT INTO `web_calendar_event_users` (`calendar_event_section_id`, `calendar_event_id`, `user_id`, `notice`, `participation`, `joined_time`) VALUES
(1, 1, 4, '', 1, 0),
(4, 3, 9, 'Byl jsem tady!', 1, 1513117818),
(4, 3, 5, '', 1, 1513117982),
(4, 3, 4, 'Uf.... sthil jsem to!', 1, 1513118006),
(4, 3, 7, 'No, tak sem se nevejdu :/', 1, 1513118041);

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
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `web_servers`
--

INSERT INTO `web_servers` (`id`, `name`, `access_type`, `has_background_placeholder`, `edited_at`, `created_at`, `owner`) VALUES
(1, 'Small Community', 1, 0, 0, 0, 0),
(2, 'ZGame', 1, 0, 0, 0, 0),
(3, 'Overwatch', 0, 0, 0, 0, 0),
(4, 'GGCommunity', 2, 0, 0, 0, 0),
(25, 'Dota2 Room', 0, 1, 1513116361, 1511624618, 4),
(35, 'PUBG HUB', 0, 1, 1513117203, 1513115950, 9);

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
('29123574bdc12d691cdd84560fbab9ff555cb77a61c73474e9b4632f048ef8d0', 7, '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1513118470, 0),
('32f5b8e03cb5254939764492d9aa1f22f08d3c444ca36de4375e8522ec3243a8', 4, '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.94 Safari/537.36', 1512757722, 1),
('5a705903fb0a017112f31d7a8fe2d83d36225e9cdadded4c69737867f3ea431a', 4, '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1512863522, 0),
('a279ef62aa76a249d9433a084c329c964a76a3151cd8003daab78cf03cfb3969', 4, '::1', 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko', 1512863557, 0),
('e0b373db66f9fc17ba02450eb1a6ec705ce05e8ba9cf04f37e4d66a76dbc818e', 7, '::1', 'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:57.0) Gecko/20100101 Firefox/57.0', 1513118022, 0);

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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COLLATE=utf8_czech_ci;

--
-- Vypisuji data pro tabulku `web_users`
--

INSERT INTO `web_users` (`id`, `username`, `username_clean`, `email`, `password`, `lastvisit`, `registered`, `register_time`, `verified`, `actkey`, `form_salt`) VALUES
(1, 'Anonymous', 'anonymous', '', '', 0, 0, 0, 0, '', ''),
(4, 'Frixs', 'frixs', 'frixs.dotlog@gmail.com', '$2y$12$568qvZ2pgEW06FZUC9f38OXPmwUkqX5J2PLrt6WN1XbyFcJ1FVfOW', 1509993601, 1, 1509993601, 0, '', '3ef205b0117f9d6df28908f1c0524260'),
(5, 'TestFrixs', 'testfrixs', 'testfrixs@testfrixs.com', '$2y$12$568qvZ2pgEW06FZUC9f38OXPmwUkqX5J2PLrt6WN1XbyFcJ1FVfOW', 1507893603, 1, 1507793602, 0, '', '3ef205b0117f9d6df28908f1c0524260'),
(6, 'TestObject1', 'testobject1', 'testobject1@testobject.xyz', '$2y$12$XlDcYbAk9aNfTly3WeSVRe9jh3yKM5TvyvyTs85josMIvhOzLUrde', 1513099745, 1, 1513099745, 0, '', '3339c1060f4bd01e7a0cacb192170b24'),
(7, 'TestObject2', 'testobject2', 'testobject2@testobject.xyz', '$2y$12$iGjjqUnBJ.M8VgBV3ctBKeNcyRQy9sMW7nGLqXfxDGWQAnTcIFLmy', 1513099807, 1, 1513099807, 0, '', '52c3a3846dd7554c22a9b5fb7f102766'),
(8, 'JenJa', 'jenja', 'testobject3@testobject.xyz', '$2y$12$SnXEbYHQYMgK26cXiZjOt.0VANPYUjldJvfl8/j92z1V6jjyvIrtK', 1513099901, 1, 1513099901, 0, '', 'dfe19606c769eda62faeaf638a86c2fe'),
(9, 'NickTester', 'nicktester', 'testobject4@testobject.xyz', '$2y$12$GzNDzHlEhSupm6dsmwKtFOPcQBzgU.17qq77gKdeZKG7.pK.uG666', 1513099949, 1, 1513099949, 0, '', '28dfa02d3658bb402e78d7f45a290f44');

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
(5, 4, 25),
(9, 4, 25),
(9, 7, 25),
(9, 4, 35),
(9, 3, 35),
(7, 5, 35),
(7, 4, 25),
(7, 3, 25),
(4, 1, NULL);

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
  ADD CONSTRAINT `web_calendar_event_users_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `web_users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `web_calendar_event_users_ibfk_3` FOREIGN KEY (`calendar_event_id`) REFERENCES `web_calendar_events` (`id`) ON DELETE CASCADE;

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
