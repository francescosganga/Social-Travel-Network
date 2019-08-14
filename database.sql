-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Host: db5000146972.hosting-data.io
-- Creato il: Ago 12, 2019 alle 20:37
-- Versione del server: 5.7.27-log
-- Versione PHP: 7.0.33-0+deb9u3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbs142231`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `av_options`
--

CREATE TABLE `av_options` (
  `id` int(5) NOT NULL,
  `option_name` varchar(255) NOT NULL,
  `option_value` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dump dei dati per la tabella `av_options`
--

INSERT INTO `av_options` (`id`, `option_name`, `option_value`) VALUES
('site_name', 'Social Travel Network'),
('site_url', 'https://www.amiciviaggiando.it'),
('site_path', '/homepages/45/d785015712/htdocs/amiciviaggiando.it'),
('site_language', 'italian');
('mail_host', 'mail_host'),
('mail_username', 'mail_username'),
('mail_port', 'mail_port'),
('mail_password', 'mail_password');

-- --------------------------------------------------------

--
-- Struttura della tabella `av_users`
--

CREATE TABLE `av_users` (
  `id` int(5) NOT NULL,
  `name` varchar(255) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `avatar` varchar(255) NOT NULL,
  `last_login` varchar(255) DEFAULT NULL,
  `login_hash` varchar(255) NOT NULL,
  `verified` varchar(255) NOT NULL,
  `verify_hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Struttura della tabella `av_trips`
--

CREATE TABLE `av_trips` (
  `id` int(5) NOT NULL,
  `user_id` int(5) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `from_city` varchar(255) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) NOT NULL,
  `address` varchar(255) DEFAULT NULL,
  `date` int(15) NOT NULL,
  `partecipants` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `av_options`
--
ALTER TABLE `av_options`
  ADD PRIMARY KEY (`id`);

--
-- Indici per le tabelle `av_users`
--
ALTER TABLE `av_users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `av_options`
--
ALTER TABLE `av_options`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT per la tabella `av_users`
--
ALTER TABLE `av_users`
  MODIFY `id` int(5) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
