-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 20, 2026 alle 15:17
-- Versione del server: 10.4.32-MariaDB
-- Versione PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `auto_manutenzione`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `manutenzioni`
--

CREATE TABLE `manutenzioni` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `data` date DEFAULT NULL,
  `km` int(11) DEFAULT NULL,
  `descrizione` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `manutenzioni`
--

INSERT INTO `manutenzioni` (`id`, `user_id`, `data`, `km`, `descrizione`) VALUES
(1, 1, '2007-05-12', 15000, 'cambio freni'),
(2, 1, '2008-03-23', 25000, 'manutenzione e freni'),
(3, 2, '2006-05-12', 5000, 'manutenzione'),
(4, 2, '2007-06-12', 12333, 'cambio freni'),
(8, 5, '2024-08-15', 104000, 'Cambio gomme e riparazione giunto ,u giunttttt'),
(9, 5, '2026-02-12', 208000, 'cambio pasticche freni e dischi');

-- --------------------------------------------------------

--
-- Struttura della tabella `utenti`
--

CREATE TABLE `utenti` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `cognome` varchar(50) DEFAULT NULL,
  `foto` varchar(255) DEFAULT 'default.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dump dei dati per la tabella `utenti`
--

INSERT INTO `utenti` (`id`, `email`, `password`, `nome`, `cognome`, `foto`) VALUES
(1, 'rocco@gmai.com', '$2y$10$RU4RTO5XQhBcQ6.IIePbB.SAvF4Ch7LQQmJOrgDWICs/Lsdx8oIH6', 'Rocco', 'Mancini', 'uploads/1776447988_Immagine WhatsApp 2024-06-29 ore 15.46.54_2ba2135d.jpg'),
(2, 'mastinolavico@gmail.com', '$2y$10$yvbK8oTS6IwpF5XXXH6qfeSTviZzRs0eEimVdiXOi1UPcuiUQvENG', 'Rocco', 'Mancini', 'uploads/1776431869eclipse_moon_light_120001_3840x2400.jpg'),
(3, 'gino@gmail.com', '$2y$10$95OC3gKhx8ee7oqNncuKo.RUqVjC9978qPuxh7.UZFWaBeMUU8EQC', 'pino', 'pascali', 'uploads/1776436004Immagine WhatsApp 2024-06-29 ore 15.46.54_2ba2135d.jpg'),
(4, 'ricci@pollo.com', '$2y$10$Jg5WoJg.zAXORejhs90v2e/xQHXp1Fa1UY4LJ879X0Mm.jm4lAH36', 'luigi', 'mancini', 'uploads/1776436349Immagine WhatsApp 2024-06-29 ore 15.46.54_2ba2135d.jpg'),
(5, 'lollopollo@gmail.com', '$2y$10$5gO3DZmuEdL7m9S3KB6M7uKThTICZc6TBIA4bWAeeXiWmlkgiXH26', 'Luigi', 'Mancini', 'uploads/1776448335_Immagine WhatsApp 2024-06-29 ore 15.46.54_2ba2135d.jpg'),
(6, 'paolo12@gmail.com', '$2y$10$QphFk0Vu6abnfqm1MB9O7eCrFtWBxOOhnQeuulN/YUJfWaGAT36EK', 'Paolo', 'Messa', 'uploads/1776450640_WIN_20251008_18_59_31_Pro.jpg');

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `manutenzioni`
--
ALTER TABLE `manutenzioni`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indici per le tabelle `utenti`
--
ALTER TABLE `utenti`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `manutenzioni`
--
ALTER TABLE `manutenzioni`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT per la tabella `utenti`
--
ALTER TABLE `utenti`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `manutenzioni`
--
ALTER TABLE `manutenzioni`
  ADD CONSTRAINT `manutenzioni_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `utenti` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
