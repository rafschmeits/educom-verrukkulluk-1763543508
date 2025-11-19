-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 19 nov 2025 om 11:36
-- Serverversie: 10.4.32-MariaDB
-- PHP-versie: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `recepten_db`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `artikel`
--

CREATE TABLE `artikel` (
  `id` int(11) NOT NULL,
  `naam` varchar(150) NOT NULL,
  `omschrijving` varchar(255) DEFAULT NULL,
  `prijs` decimal(10,2) NOT NULL DEFAULT 0.00,
  `eenheid` varchar(50) NOT NULL,
  `verpakking` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `artikel`
--

INSERT INTO `artikel` (`id`, `naam`, `omschrijving`, `prijs`, `eenheid`, `verpakking`) VALUES
(1, 'Spaghetti', 'Durum tarwe pasta', 1.50, 'g', 'pak'),
(2, 'Tomaat', 'Verse tomaten', 0.40, 'stuk', 'zak'),
(3, 'Knoflook', 'Knoflooktenen', 0.10, 'teen', 'net'),
(4, 'Olijfolie', 'Extra vierge', 5.99, 'ml', 'fles'),
(5, 'Parmezaan', 'Geraspte kaas', 2.50, 'g', 'zak'),
(6, 'Rundergehakt', 'Vers gehakt', 6.50, 'g', 'pak'),
(7, 'Tortilla', 'Maïs tortilla', 2.99, 'stuk', 'pak'),
(8, 'Avocado', 'Rijp', 1.20, 'stuk', 'los'),
(9, 'Sojasaus', 'Zoute saus', 3.20, 'ml', 'fles'),
(10, 'Sushi rijst', 'Kleefrijst', 2.80, 'g', 'zak');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `boodschappenlijst`
--

CREATE TABLE `boodschappenlijst` (
  `id` int(11) NOT NULL,
  `session_id` varchar(100) NOT NULL,
  `artikel_id` int(11) NOT NULL,
  `aantal` decimal(10,2) NOT NULL,
  `eenheid` varchar(50) DEFAULT NULL,
  `verpakking` varchar(50) DEFAULT NULL,
  `prijs` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gerecht`
--

CREATE TABLE `gerecht` (
  `id` int(11) NOT NULL,
  `keuken_id` int(11) NOT NULL,
  `type_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `datum_toegevoegd` date NOT NULL,
  `titel` varchar(150) NOT NULL,
  `korte_omschrijving` varchar(255) DEFAULT NULL,
  `lange_omschrijving` text DEFAULT NULL,
  `afbeelding` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `gerecht`
--

INSERT INTO `gerecht` (`id`, `keuken_id`, `type_id`, `user_id`, `datum_toegevoegd`, `titel`, `korte_omschrijving`, `lange_omschrijving`, `afbeelding`) VALUES
(1, 1, 5, 1, '2025-11-01', 'Spaghetti al Pomodoro', 'Klassieke tomatensaus', 'Eenvoudig Italiaans gerecht met tomaat, knoflook en olijfolie.', NULL),
(2, 2, 5, 1, '2025-11-02', 'Taco met avocado', 'Frisse taco', 'Tortilla met gehakt, tomaat en avocado.', NULL),
(3, 3, 5, 2, '2025-11-03', 'Sushi bowl', 'Snelle sushi zonder rollen', 'Rijst met soja, avocado en toppings.', NULL),
(4, 4, 8, 2, '2025-11-04', 'Belgische tomatensoep', 'Rijke tomatensoep', 'Geconcentreerde tomatensoep met kruiden.', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `gerecht_info`
--

CREATE TABLE `gerecht_info` (
  `id` int(11) NOT NULL,
  `record_type` enum('O','B','W','F') NOT NULL,
  `gerecht_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `datum` date DEFAULT NULL,
  `stap` int(11) DEFAULT NULL,
  `tekstveld` text DEFAULT NULL,
  `aantal` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `gerecht_info`
--

INSERT INTO `gerecht_info` (`id`, `record_type`, `gerecht_id`, `user_id`, `datum`, `stap`, `tekstveld`, `aantal`) VALUES
(1, 'B', 1, 1, '2025-11-01', 1, 'Kook de spaghetti al dente.', NULL),
(2, 'B', 1, 1, '2025-11-01', 2, 'Fruit knoflook in olijfolie, voeg tomaat toe.', NULL),
(3, 'B', 1, 1, '2025-11-01', 3, 'Meng saus met pasta, serveer met parmezaan.', NULL),
(4, 'O', 1, 2, '2025-11-02', NULL, 'Lekker met basilicum.', NULL),
(5, 'O', 1, 1, '2025-11-02', NULL, 'Gebruik rijpe tomaten.', NULL),
(6, 'W', 1, 2, '2025-11-03', NULL, NULL, 5),
(7, 'F', 1, 1, '2025-11-04', NULL, NULL, NULL),
(8, 'B', 2, 1, '2025-11-02', 1, 'Bak gehakt, breng op smaak.', NULL),
(9, 'B', 2, 1, '2025-11-02', 2, 'Vul tortilla met tomaat en avocado.', NULL),
(10, 'O', 2, 2, '2025-11-02', NULL, 'Voeg limoen toe.', NULL),
(11, 'W', 2, 1, '2025-11-03', NULL, NULL, 4),
(12, 'B', 3, 2, '2025-11-03', 1, 'Kook rijst, laat rusten.', NULL),
(13, 'B', 3, 2, '2025-11-03', 2, 'Voeg toppings en sojasaus toe.', NULL),
(14, 'W', 3, 1, '2025-11-04', NULL, NULL, 4),
(15, 'B', 4, 2, '2025-11-04', 1, 'Fruit knoflook, voeg tomaat toe en laat sudderen.', NULL),
(16, 'B', 4, 2, '2025-11-04', 2, 'Mix en kruid naar smaak.', NULL),
(17, 'O', 4, 1, '2025-11-05', NULL, 'Serveer met een scheut room.', NULL),
(18, 'W', 4, 2, '2025-11-05', NULL, NULL, 5);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `ingredient`
--

CREATE TABLE `ingredient` (
  `id` int(11) NOT NULL,
  `gerecht_id` int(11) NOT NULL,
  `artikel_id` int(11) NOT NULL,
  `aantal` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `ingredient`
--

INSERT INTO `ingredient` (`id`, `gerecht_id`, `artikel_id`, `aantal`) VALUES
(1, 1, 1, 250.00),
(2, 1, 2, 2.00),
(3, 1, 3, 2.00),
(4, 1, 4, 20.00),
(5, 1, 5, 30.00),
(6, 2, 7, 3.00),
(7, 2, 6, 150.00),
(8, 2, 2, 1.00),
(9, 2, 8, 1.00),
(10, 3, 10, 200.00),
(11, 3, 9, 20.00),
(12, 3, 8, 1.00),
(13, 4, 2, 5.00),
(14, 4, 3, 2.00),
(15, 4, 4, 15.00);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `keuken_type`
--

CREATE TABLE `keuken_type` (
  `id` int(11) NOT NULL,
  `record_type` enum('K','T') NOT NULL,
  `omschrijving` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `keuken_type`
--

INSERT INTO `keuken_type` (`id`, `record_type`, `omschrijving`) VALUES
(1, 'K', 'Italiaans'),
(2, 'K', 'Mexicaans'),
(3, 'K', 'Japans'),
(4, 'K', 'Belgisch'),
(5, 'T', 'Hoofdgerecht'),
(6, 'T', 'Bijgerecht'),
(7, 'T', 'Dessert'),
(8, 'T', 'Soep');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(150) NOT NULL,
  `afbeelding` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`id`, `user_name`, `password`, `email`, `afbeelding`) VALUES
(1, 'raf', 'hashed_pw_raf', 'raf@example.com', NULL),
(2, 'lisa', 'hashed_pw_lisa', 'lisa@example.com', NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `artikel`
--
ALTER TABLE `artikel`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `boodschappenlijst`
--
ALTER TABLE `boodschappenlijst`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_bl_artikel` (`artikel_id`);

--
-- Indexen voor tabel `gerecht`
--
ALTER TABLE `gerecht`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gerecht_keuken` (`keuken_id`),
  ADD KEY `fk_gerecht_type` (`type_id`),
  ADD KEY `fk_gerecht_user` (`user_id`);

--
-- Indexen voor tabel `gerecht_info`
--
ALTER TABLE `gerecht_info`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_gi_gerecht` (`gerecht_id`),
  ADD KEY `fk_gi_user` (`user_id`);

--
-- Indexen voor tabel `ingredient`
--
ALTER TABLE `ingredient`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ing_gerecht` (`gerecht_id`),
  ADD KEY `fk_ing_artikel` (`artikel_id`);

--
-- Indexen voor tabel `keuken_type`
--
ALTER TABLE `keuken_type`
  ADD PRIMARY KEY (`id`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `artikel`
--
ALTER TABLE `artikel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT voor een tabel `boodschappenlijst`
--
ALTER TABLE `boodschappenlijst`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT voor een tabel `gerecht`
--
ALTER TABLE `gerecht`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT voor een tabel `gerecht_info`
--
ALTER TABLE `gerecht_info`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT voor een tabel `ingredient`
--
ALTER TABLE `ingredient`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT voor een tabel `keuken_type`
--
ALTER TABLE `keuken_type`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `boodschappenlijst`
--
ALTER TABLE `boodschappenlijst`
  ADD CONSTRAINT `fk_bl_artikel` FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`);

--
-- Beperkingen voor tabel `gerecht`
--
ALTER TABLE `gerecht`
  ADD CONSTRAINT `fk_gerecht_keuken` FOREIGN KEY (`keuken_id`) REFERENCES `keuken_type` (`id`),
  ADD CONSTRAINT `fk_gerecht_type` FOREIGN KEY (`type_id`) REFERENCES `keuken_type` (`id`),
  ADD CONSTRAINT `fk_gerecht_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Beperkingen voor tabel `gerecht_info`
--
ALTER TABLE `gerecht_info`
  ADD CONSTRAINT `fk_gi_gerecht` FOREIGN KEY (`gerecht_id`) REFERENCES `gerecht` (`id`),
  ADD CONSTRAINT `fk_gi_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Beperkingen voor tabel `ingredient`
--
ALTER TABLE `ingredient`
  ADD CONSTRAINT `fk_ing_artikel` FOREIGN KEY (`artikel_id`) REFERENCES `artikel` (`id`),
  ADD CONSTRAINT `fk_ing_gerecht` FOREIGN KEY (`gerecht_id`) REFERENCES `gerecht` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
