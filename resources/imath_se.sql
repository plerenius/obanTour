-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Värd: 127.0.0.1
-- Tid vid skapande: 03 aug 2016 kl 19:51
-- Serverversion: 10.1.13-MariaDB
-- PHP-version: 5.6.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Databas: `imath_se`
--

-- --------------------------------------------------------

--
-- Tabellstruktur `competitions`
--

CREATE TABLE `competitions` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `date` date DEFAULT NULL,
  `yearsId` int(11) NOT NULL,
  `course` tinytext,
  `type` tinytext,
  `weather` tinytext,
  `nf` int(11) DEFAULT NULL,
  `ld` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `competitions`
--

INSERT INTO `competitions` (`id`, `name`, `date`, `yearsId`, `course`, `type`, `weather`, `nf`, `ld`) VALUES
(1, '2016-1', '2016-05-13', 2016, 'Linköpings GK', NULL, 'Regnskurar', 2, 0),
(2, '2016-2 Vårresan', '2016-05-21', 2016, 'Vårresan', NULL, NULL, 37, 17),
(3, '2016-3', '2016-06-12', 2016, 'Landeryd Södra', '0', '0', 37, 37),
(6, '2016-4', '2016-06-29', 2016, 'Landeryd Norra', 'Poängbogey', 'moln', 2, 32),
(7, '2015-1', NULL, 2015, 'Ljunghusen,Falsterbo,PGA Links', 'Eclectic', 'moln', 3, NULL);

-- --------------------------------------------------------

--
-- Ersättningsstruktur för vy `comp_result`
--
CREATE TABLE `comp_result` (
`year` int(11)
,`fname` varchar(30)
,`lname` varchar(30)
,`result` double
);

-- --------------------------------------------------------

--
-- Tabellstruktur `players`
--

CREATE TABLE `players` (
  `id` int(11) NOT NULL,
  `fname` varchar(30) NOT NULL,
  `lname` varchar(30) NOT NULL,
  `email` tinytext,
  `golfId` varchar(10) DEFAULT NULL,
  `hcp` float DEFAULT NULL,
  `mobil` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `players`
--

INSERT INTO `players` (`id`, `fname`, `lname`, `email`, `golfId`, `hcp`, `mobil`) VALUES
(0, 'Alex', 'Sjöberg', '', '', 0, ''),
(1, 'Anders', 'Forslund', '', '', 0, ''),
(2, 'Axel', 'Flink', 'axel.flink@gmail.com', '790917-006', 3, '070-9143509'),
(3, 'Claes', 'Lundström', '', '', 0, ''),
(4, 'Dan', 'Eding', '', '', 0, ''),
(5, 'Daniel', '?', '', '', 0, ''),
(6, 'Daniel', 'Wressle', '', '', 0, ''),
(7, 'Hans', 'Rosander', '', '', 0, ''),
(8, 'Henric', 'Granberg', '', '', 0, ''),
(9, 'Håkan', 'Ritzén', 'hakan@ritzen.se', '710906-024', 9, '070-5235256'),
(10, 'Jens', '?', '', '', 0, ''),
(11, 'Jens', 'Bergendorff', 'jensbergendorff@gmail.com', '810301-028', 18, '070-5384813'),
(12, 'Jesper', 'Broberg', '', '', 0, ''),
(13, 'Jesper', 'Jansson', 'jansson.jesper@gmail.com', '810108-015', 0, '073-9238989'),
(14, 'Joakim', 'Jerndal', 'jo-jer@sectra.com', '660117-030', 0, '070-6995990'),
(15, 'Jocke', 'Kättström', 'jockekattstrom@gmail.com', '800123-012', 6, '070-2382948'),
(16, 'Johan', 'Bernspång', '', '', 0, ''),
(17, 'Johan', 'Nilsson', '', '', 0, ''),
(18, 'Johan', 'Nordkvist', 'johan@nordkvist.se', '750911-016', 4, '070-3197272'),
(19, 'Johan', 'Sjöberg', 'johans@isy.liu.se', '780711-009', 0, '073-1504310'),
(20, 'Johan', 'Wiberg', 'wibergjohan@yahoo.se', '770418-031', 0, '076-7729132'),
(21, 'Jonas', 'Melin', '', '', 0, ''),
(22, 'Kajsa', 'Glimåker', '', '', 0, ''),
(23, 'Karl', 'Andersson', 'andersson.karl@gmail.com', '820114-008', 22, '070-9130601'),
(24, 'Lena', 'Hallgren', '', '', 0, ''),
(25, 'Lotta', 'Jerndal', 'lotta.jerndal@linkoping.se', '700923-035', 0, '072-2226814'),
(26, 'Magnus', 'Björklund', '', '', 0, ''),
(27, 'Malin', 'Björklund', '', '', 0, ''),
(28, 'Mattias', 'Hallgren', '', '', 0, ''),
(29, 'Mårten', 'Nygren', 'marten.nygren@gmail.com', '790211-016', 8, '073-3982688'),
(30, 'Mårten', 'Nygren', '', '', 0, ''),
(31, 'Naureen', 'Ghafoor', '', '', 0, ''),
(32, 'Niklas', 'Svenzén', '', '', 0, ''),
(33, 'Nina', 'Wollinger', '', '', 0, ''),
(34, 'Patrik', 'Johansson', '', '', 0, ''),
(35, 'Patrik', 'Preimer', 'pa-pre@sectra.se', '', 17, ''),
(36, 'Pedro', 'Botella', 'pbotella@gmail.com', '810913-014', 24, '070-2769760'),
(37, 'Per', 'Pettersson', 'perpettersson74@gmail.com', '741203-021', 5, '070-9652372'),
(38, 'Peter', '?', '', '', 0, ''),
(39, 'Peter', 'Högfeldt', '', '', 0, ''),
(40, 'Peter', 'Melin', '', '', 0, ''),
(41, 'Petter', 'Knutsson', '', '', 0, ''),
(42, 'Petter', 'Lerenius', 'plerenius@gmail.com', '790703-015', 17, '070-4820990'),
(43, 'Richard', '?', '', '', 0, ''),
(44, 'Stefan', 'Johansson', 'gstefanjohansson@gmail.com', '780616-003', 7, '070-8340315'),
(45, 'Stefan', 'Lilliehjort', '', '', 0, ''),
(46, 'Stefan', 'Melin', 'stefan.melin@gmail.com', '770404-030', 16, '070-3256121'),
(47, 'Thomas', 'Schön', 'thomas@imath.se', '771225-006', 18, '073-5933887'),
(48, 'Tommy', 'Dahlgren', '', '', 0, ''),
(49, 'Ulf', 'Kättström', '', '', 0, ''),
(50, 'Ulrica', 'Melin', 'ulrica.melin@gmail.com', '770422-005', 25, '070-5246130');

-- --------------------------------------------------------

--
-- Tabellstruktur `results`
--

CREATE TABLE `results` (
  `id` int(11) NOT NULL,
  `players_id` int(11) NOT NULL,
  `competitions_id` int(11) NOT NULL,
  `result` float NOT NULL,
  `rank` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `results`
--

INSERT INTO `results` (`id`, `players_id`, `competitions_id`, `result`, `rank`) VALUES
(1, 2, 1, 11, 1),
(3, 15, 1, 4.33, 5),
(5, 9, 1, 2.67, 6),
(6, 0, 1, 9.33, 2),
(7, 17, 1, 6, 4),
(9, 32, 1, 7.67, 3),
(11, 3, 1, 1, 7),
(12, 2, 2, 1, 8),
(13, 37, 2, 11, 1),
(14, 15, 2, 5.29, 5),
(15, 34, 2, 9.57, 2),
(16, 9, 2, 6.71, 4),
(18, 17, 2, 2.43, 7),
(19, 46, 2, 8.14, 3),
(21, 11, 2, 3.86, 6),
(22, 15, 3, 11, 1),
(23, 46, 3, 9, 2),
(24, 17, 3, 7, 3),
(25, 2, 3, 5, 4),
(26, 37, 3, 3, 5),
(27, 11, 3, 1, 6),
(28, 2, 6, 11, 1),
(29, 17, 6, 9.33, 2),
(30, 9, 6, 6, 4),
(31, 11, 6, 1, 7),
(32, 46, 6, 7.67, 3),
(33, 32, 6, 4.33, 5),
(34, 23, 6, 2.67, 6),
(35, 2, 7, 6, 10),
(36, 17, 7, -7, 2),
(37, 9, 7, -8, 1),
(38, 11, 7, -1, 6),
(39, 15, 7, 0, 7),
(40, 37, 7, -6, 4),
(41, 3, 7, 5, 9),
(42, 29, 7, 4, 8),
(43, 35, 7, -7, 3),
(44, 42, 7, -5, 5);

-- --------------------------------------------------------

--
-- Tabellstruktur `season`
--

CREATE TABLE `season` (
  `year` int(11) NOT NULL,
  `type` tinytext NOT NULL,
  `noOfRounds` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumpning av Data i tabell `season`
--

INSERT INTO `season` (`year`, `type`, `noOfRounds`) VALUES
(2007, '', 0),
(2008, '', 0),
(2009, '', 0),
(2010, '', 0),
(2011, '', 0),
(2012, 'rank', 3),
(2013, 'rank', 3),
(2014, 'rank', 3),
(2015, 'rank', 4),
(2016, 'rank', 4);

-- --------------------------------------------------------

--
-- Ersättningsstruktur för vy `vinlista`
--
CREATE TABLE `vinlista` (
`year` int(11)
,`fname` varchar(30)
,`lname` varchar(30)
,`vinpavor` decimal(25,0)
);

-- --------------------------------------------------------

--
-- Struktur för vy `comp_result`
--
DROP TABLE IF EXISTS `comp_result`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `comp_result`  AS  select `c`.`yearsId` AS `year`,`p`.`fname` AS `fname`,`p`.`lname` AS `lname`,sum((`r`.`result` + if((`c`.`nf` = `p`.`id`),2,0))) AS `result` from ((`competitions` `c` join `results` `r` on((`c`.`id` = `r`.`competitions_id`))) join `players` `p` on((`r`.`players_id` = `p`.`id`))) group by `c`.`yearsId`,`p`.`id` order by sum((`r`.`result` + if((`c`.`nf` = `p`.`id`),2,0))) desc ;

-- --------------------------------------------------------

--
-- Struktur för vy `vinlista`
--
DROP TABLE IF EXISTS `vinlista`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vinlista`  AS  select `c`.`yearsId` AS `year`,`p`.`fname` AS `fname`,`p`.`lname` AS `lname`,sum(((if((`r`.`rank` = 1),1,0) + if((`c`.`nf` = `p`.`id`),1,0)) + if((`c`.`ld` = `p`.`id`),1,0))) AS `vinpavor` from ((`competitions` `c` left join `results` `r` on((`c`.`id` = `r`.`competitions_id`))) join `players` `p` on((`r`.`players_id` = `p`.`id`))) group by `c`.`yearsId`,`p`.`id` order by sum(((if((`r`.`rank` = 1),1,0) + if((`c`.`nf` = `p`.`id`),1,0)) + if((`c`.`ld` = `p`.`id`),1,0))) desc,`p`.`fname` ;

--
-- Index för dumpade tabeller
--

--
-- Index för tabell `competitions`
--
ALTER TABLE `competitions`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `results`
--
ALTER TABLE `results`
  ADD PRIMARY KEY (`id`);

--
-- Index för tabell `season`
--
ALTER TABLE `season`
  ADD PRIMARY KEY (`year`);

--
-- AUTO_INCREMENT för dumpade tabeller
--

--
-- AUTO_INCREMENT för tabell `competitions`
--
ALTER TABLE `competitions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT för tabell `results`
--
ALTER TABLE `results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
