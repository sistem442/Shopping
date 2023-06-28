-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: db9atodf.mariadb.hosting.zone
-- Erstellungszeit: 28. Jun 2023 um 14:34
-- Server-Version: 10.3.28-MariaDB-deb10-keen
-- PHP-Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `db9atodf`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `product`
--

INSERT INTO `product` (`id`, `name`, `description`, `price`, `date`) VALUES
(1, 'Spulmittel', '1l', 300, NULL),
(2, 'Flussige Seife', '500 ml', 240, NULL),
(9, 'Mullbeutel', '20l', 130, NULL),
(10, 'Brot', 'Dunkel, 500g', 130, NULL),
(11, 'Kuchenrollenpapier', '2 st', 150, NULL),
(12, 'Oregano', '1 st', 120, NULL),
(13, 'Pfeffer', '1 st', 122, NULL),
(14, 'Salz', '1 st', 125, NULL),
(15, 'Paprika', '1 st', 155, NULL),
(16, 'Curry', '1 st', 166, NULL),
(17, 'Vegeta', '250g', 250, NULL),
(18, 'Kuchenschwam', '10 st', 130, NULL),
(19, 'Topfkratzer aus Edelstahl', '4st', 120, '2017-01-01'),
(20, 'Klopapier', '10 st', 320, NULL),
(21, 'Glassreiniger', '1l', 200, NULL),
(22, 'Backoffen und Grillreiniger', '400ml', 300, NULL),
(23, 'Scheuermittel', '600ml', 350, NULL),
(108, 'SCHOKOLADE', '100g', 265, '2023-05-16');

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
