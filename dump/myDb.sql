-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 23 jan 2021 om 23:42
-- Serverversie: 10.4.17-MariaDB
-- PHP-versie: 7.4.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `muziek_project`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `comment`
--

CREATE TABLE `comment` (
  `comment_ID` int(10) NOT NULL,
  `conversation_ID` int(10) NOT NULL,
  `comment` varchar(100) NOT NULL,
  `date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `user_ID` int(10) NOT NULL,
  `uploaded_file` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `comment`
--

INSERT INTO `comment` (`comment_ID`, `conversation_ID`, `comment`, `date`, `user_ID`, `uploaded_file`) VALUES
(1, 1, 'Dit is het begin en een test.', '2021-01-23 02:20:12', 1, 'Dire Straits - Sultans Of Swing.mp3'),
(2, 1, 'Dit is een reactie', '2021-01-23 02:11:02', 18, ''),
(3, 2, 'Dit is een tweede gesprek.', '2021-01-06 23:46:41', 2, ''),
(5, 1, 'Dank je voor de remix klinkt goed.', '2021-01-07 00:38:33', 1, ''),
(6, 1, 'Geen probleem ben blij om dat te horen!', '2021-01-07 00:42:06', 18, ''),
(14, 1, 'Hoop snel weer samen te werken.', '2021-01-07 03:21:50', 1, ''),
(120, 2, 'Reactie alweer', '2021-01-21 01:39:05', 2, 'Nik Kershaw - The Riddle Lyrics.mp3'),
(164, 1, 'Nieuwe test', '2021-01-23 02:54:20', 1, 'stuck_at_home.mp3'),
(187, 2, 'Test', '2021-01-23 22:27:28', 18, 'The Sound of Silence Original Version from 1964.mp3');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `conversation`
--

CREATE TABLE `conversation` (
  `conversation_ID` int(11) NOT NULL,
  `user_ID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `conversation`
--

INSERT INTO `conversation` (`conversation_ID`, `user_ID`) VALUES
(1, 1),
(2, 2);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user`
--

CREATE TABLE `user` (
  `user_ID` int(10) NOT NULL,
  `user_name` varchar(30) NOT NULL,
  `user_password` varchar(30) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Gegevens worden geëxporteerd voor tabel `user`
--

INSERT INTO `user` (`user_ID`, `user_name`, `user_password`, `admin`) VALUES
(1, 'jacob', 'jacob', 0),
(2, 'karel', 'karel', 0),
(17, 'Peter', 'Pater', 0),
(18, 'admin', 'admin', 1),
(20, 'rob', 'rob', 1);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `comment`
--
ALTER TABLE `comment`
  ADD PRIMARY KEY (`comment_ID`),
  ADD KEY `user_ID` (`user_ID`),
  ADD KEY `conversation_ID` (`conversation_ID`);

--
-- Indexen voor tabel `conversation`
--
ALTER TABLE `conversation`
  ADD PRIMARY KEY (`conversation_ID`),
  ADD KEY `user_ID` (`user_ID`);

--
-- Indexen voor tabel `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_ID`),
  ADD KEY `user_name` (`user_name`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `comment`
--
ALTER TABLE `comment`
  MODIFY `comment_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT voor een tabel `conversation`
--
ALTER TABLE `conversation`
  MODIFY `conversation_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT voor een tabel `user`
--
ALTER TABLE `user`
  MODIFY `user_ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`user_ID`) REFERENCES `user` (`user_ID`),
  ADD CONSTRAINT `comment_ibfk_3` FOREIGN KEY (`conversation_ID`) REFERENCES `conversation` (`conversation_ID`);

--
-- Beperkingen voor tabel `conversation`
--
ALTER TABLE `conversation`
  ADD CONSTRAINT `conversation_ibfk_1` FOREIGN KEY (`user_ID`) REFERENCES `user` (`user_ID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
