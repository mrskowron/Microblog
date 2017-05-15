-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Czas generowania: 12 Cze 2016, 16:42
-- Wersja serwera: 10.1.10-MariaDB
-- Wersja PHP: 5.6.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `microblog`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `comments`
--

CREATE TABLE `comments` (
  `id` int(11) NOT NULL,
  `post_id` int(11) NOT NULL,
  `author` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `comment` text COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `comments`
--

INSERT INTO `comments` (`id`, `post_id`, `author`, `comment`, `date`) VALUES
(7, 8, 'Bożena Dykiel', 'Prosta lecz smaczna :)', '2016-04-10 11:21:18'),
(8, 11, 'imie nazwisko', 'komentarz', '2016-04-11 15:34:15'),
(9, 12, 'Krzysztof Ibisz', 'dobry przepis', '2016-05-14 13:50:21'),
(10, 12, 'Bartek Leszczyk', 'komentarz', '2016-05-16 16:52:28');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `posts`
--

CREATE TABLE `posts` (
  `id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `content` text COLLATE utf8_polish_ci NOT NULL,
  `author` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `posts`
--

INSERT INTO `posts` (`id`, `title`, `content`, `author`, `date`) VALUES
(8, 'Kolorowa sałatka z jajkami', 'Jajka gotujemy, studzimy w zimnej wodzie i obieramy. Jajka, cebulę, ogórki, szynkę i paprykę kroimy w kostkę. Kukurydzę odsączamy z zalewy. Mieszamy ze sobą wszystkie składniki, dodajemy sos tatarski oraz majonez wedle uznania, doprawiamy do smaku pieprzem i solą. Delikatnie mieszamy sałatkę i schładzamy w lodówce.\r\n\r\nSkładniki\r\n\r\n5 szt. - jajka\r\n5 szt. - ogórki konserwowe\r\n30 dkg - szynka\r\n2 szt. - cebula\r\n1/2 szt. - papryka czerwona\r\n1 szt. - kukurydza w puszce\r\n2 łyżka - sos tatarski\r\nsól', 'Elżbieta Sobejko', '2016-04-01 13:00:00'),
(9, 'Roladki jajeczne', 'W misce rozbełtaj jajko, dodaj mąkę, mleko i wymieszaj. Dodaj sól i pieprz.\r\n\r\nNa patelni rozgrzej masło, wylej masę jajeczną. Obróć na drugą stronę. Idealna byłaby kwadratowa patelnia, jeśli jej nie macie (tak jak my nie miałyśmy) to można okroić.\r\n\r\nOmlet studzimy, kroimy długi prostokąt. Kładziemy na niego ser, suszony pomidor, kapary, kiełki i rolujemy. Spinamy wykałaczką.\r\n\r\nSkładniki\r\n\r\n1 szt. - Jajko\r\n2 łyżka - Mąka\r\n2 łyżka - Mleko\r\n1 szczypta - Sól\r\n1 szczypta - Pieprz\r\n4 szt. - Pomidory suszone\r\n20 szt. - Kiełki (u mnie kapusty)\r\n10 szt. - Kapary\r\n5 dkg - Żółty ser\r\n1 łyżeczka - Masło klarowane', 'Izabela Lewandowska', '2016-04-05 17:24:07'),
(10, 'Sałatka jajeczna', 'Składniki\r\n\r\n6 szt. - jajka przepiórki\r\n1/2 szklanka - kwas z buraków\r\n4 łyżka - kasza gryczana sucha\r\n1/2 szklanka - olej\r\n4 łyżka - zioła do przybrania\r\n2 szczypta - sól\r\n2 szczypta - pieprz\r\n400 ml - bulion drobiowy\r\n80 ml - kwas z buraków\r\n2 i 1/2 łyżeczka - agar-agar\r\n1 szczypta - sól i pieprz', 'Malgorzata Kwiatkowska', '2016-04-03 23:26:35'),
(11, 'Jak ubić białko na sztywno?', 'Ubicie piany\r\nBiałka należy ubić trzepaczką albo mikserem. Oczywiście wybór miksera jest zdecydowanie bardziej popularny z bardzo prostego względu – zdecydowanie szybciej pozwala na ubicie białek. Należy również pamiętać o tym, aby nie przesadzić z czasem ubijania białek, żeby piana nie opadła. Pamiętajcie, że nie do każdego przepisu wymagana jest sztywno ubita piana, dlatego warto kontrolować ten proces, aby w efekcie cieszyć się z udanego dania. ', 'Piotr Ogiński', '2016-04-10 11:00:00'),
(12, 'sos', 'śmietana, pieczarki, rodzynki', 'Anna Dymna', '2016-05-14 13:49:59'),
(13, 'ciastko', '200 g mąki, czekolada', 'Rafał Gryz', '2016-05-16 16:54:01');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `login` varchar(10) COLLATE utf8_polish_ci NOT NULL,
  `password` varchar(20) COLLATE utf8_polish_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Indeksy dla zrzutów tabel
--

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT dla tabeli `comments`
--
ALTER TABLE `comments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT dla tabeli `posts`
--
ALTER TABLE `posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT dla tabeli `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
