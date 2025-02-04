-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 04, 2025 at 01:46 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `Echipe_de_fotbal`
--

-- --------------------------------------------------------

--
-- Table structure for table `antrenor`
--

CREATE TABLE `antrenor` (
  `id_antrenor` int(11) NOT NULL,
  `nume` varchar(100) DEFAULT NULL,
  `prenume` varchar(100) DEFAULT NULL,
  `CNP` double DEFAULT NULL,
  `varsta` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_echipa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `antrenor`
--

INSERT INTO `antrenor` (`id_antrenor`, `nume`, `prenume`, `CNP`, `varsta`, `email`, `id_echipa`) VALUES
(1, 'Criste', 'Mircea', 1760523123456, 45, 'mircea.popescu@example.com', 1),
(2, 'Ion', 'Daniel', 1750711123456, 46, 'daniel.ionescu@example.com', 2),
(3, 'Georgescu', 'Ana', 1791213123456, 41, 'ana.georgescu@example.com', 3),
(4, 'Marin', 'Gabriel', 1720415123456, 48, 'gabriel.marin@example.com', 4),
(5, 'Dumitrescu', 'Ioana', 1800312123456, 43, 'ioana.dumitrescu@example.com', 5),
(6, 'Stan', 'Radu', 1770920123456, 44, 'radu.stan@example.com', 6),
(7, 'Radu', 'Cristina', 1730816123456, 47, 'cristina.radu@example.com', 7),
(8, 'Vasilescu', 'Adrian', 1780614123456, 42, 'adrian.vasilescu@example.com', 8);

-- --------------------------------------------------------

--
-- Table structure for table `echipa`
--

CREATE TABLE `echipa` (
  `id_echipa` int(11) NOT NULL,
  `nume` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `echipa`
--

INSERT INTO `echipa` (`id_echipa`, `nume`) VALUES
(1, 'Echipa Stelelor'),
(2, 'Invincibilii'),
(3, 'Cavalerii Albastri'),
(4, 'Gladiatorii'),
(5, 'Vulturii'),
(6, 'Dragonii Rosii'),
(7, 'Torpedo'),
(8, 'Fulgerul Galben'),
(9, 'Cainele flamand');

-- --------------------------------------------------------

--
-- Table structure for table `echipa_meci`
--

CREATE TABLE `echipa_meci` (
  `id_echipa_meci` int(11) NOT NULL,
  `id_meci` int(11) NOT NULL,
  `id_echipa_1` int(11) NOT NULL,
  `id_echipa_2` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `echipa_meci`
--

INSERT INTO `echipa_meci` (`id_echipa_meci`, `id_meci`, `id_echipa_1`, `id_echipa_2`) VALUES
(1, 1, 3, 1),
(2, 2, 5, 4),
(3, 3, 7, 4),
(4, 4, 6, 3),
(5, 5, 1, 8),
(6, 6, 2, 7),
(7, 7, 6, 4),
(8, 8, 1, 2),
(9, 9, 8, 7),
(10, 10, 2, 6);

-- --------------------------------------------------------

--
-- Table structure for table `facultate`
--

CREATE TABLE `facultate` (
  `id_facultate` int(11) NOT NULL,
  `nume` varchar(100) DEFAULT NULL,
  `locatie` varchar(100) DEFAULT NULL,
  `specializare` varchar(100) DEFAULT NULL,
  `id_echipa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `facultate`
--

INSERT INTO `facultate` (`id_facultate`, `nume`, `locatie`, `specializare`, `id_echipa`) VALUES
(1, 'Facultatea de Inginer', 'București', 'Inginerie Electrică', 1),
(2, 'Facultatea de Medicină', 'Cluj-Napoca', 'Medicină Generală', 2),
(3, 'Facultatea de Științe Economice', 'Timișoara', 'Economie', 3),
(4, 'Facultatea de Informatică', 'Iași', 'Informatică', 4),
(5, 'Facultatea de Drept', 'București', 'Drept', 5);

-- --------------------------------------------------------

--
-- Table structure for table `meci`
--

CREATE TABLE `meci` (
  `id_meci` int(11) NOT NULL,
  `loc` varchar(100) DEFAULT NULL,
  `ora` varchar(100) DEFAULT NULL,
  `data` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `meci`
--

INSERT INTO `meci` (`id_meci`, `loc`, `ora`, `data`) VALUES
(1, 'Stadion National Arena', '14:00', '2025-01-01'),
(2, 'Stadion Cluj Arena', '16:00', '2025-01-02'),
(3, 'Stadion Ion Oblemenco', '18:00', '2025-01-03'),
(4, 'Stadionul Steaua', '20:00', '2025-01-04'),
(5, 'Stadionul Dinamo', '15:00', '2025-01-05'),
(6, 'Stadionul Giulesti', '17:00', '2025-01-06'),
(7, 'Stadionul Dr. Constantin Radulescu', '19:00', '2025-01-07'),
(8, 'Stadionul Gaz Metan', '13:00', '2025-01-08'),
(9, 'Stadionul Municipal Sibiu', '18:30', '2025-01-09'),
(10, 'Stadionul Farul', '20:30', '2025-01-10');

-- --------------------------------------------------------

--
-- Table structure for table `student`
--

CREATE TABLE `student` (
  `id_student` int(11) NOT NULL,
  `nume` varchar(100) DEFAULT NULL,
  `prenume` varchar(100) DEFAULT NULL,
  `CNP` double DEFAULT NULL,
  `varsta` int(11) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `id_echipa` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `student`
--

INSERT INTO `student` (`id_student`, `nume`, `prenume`, `CNP`, `varsta`, `email`, `id_echipa`) VALUES
(1, 'Popescu', 'Andrei', 1990101012345, 20, 'andrei.popescu1@example.com', 1),
(2, 'Radulesc', 'Oana', 1993090912346, 23, 'oana.radulescu9@example.com', 1),
(3, 'Mihailesc', 'Stefan', 1994081512345, 20, 'stefan.mihailescu3@example.com', 1),
(4, 'Ionescu', 'Marian', 1995022012345, 19, 'marian.ionescu4@example.com', 1),
(5, 'Dumitru', 'Florin', 1996121112345, 18, 'florin.dumitru5@example.com', 1),
(6, 'Preda', 'Valentina', 1997040812345, 21, 'valentina.preda6@example.com', 1),
(7, 'Diaconu', 'Cristina', 1993093012345, 22, 'cristina.diaconu7@example.com', 1),
(8, 'Georgescu', 'Andrei', 1992080912345, 22, 'andrei.georgescu8@example.com', 1),
(9, 'Stoica', 'Mihaela', 1996120512345, 20, 'mihaela.stoica9@example.com', 1),
(10, 'Radu', 'Florentina', 1993090712345, 23, 'florentina.radu10@example.com', 1),
(11, 'Ionescu', 'Maria', 1991121512345, 21, 'maria.ionescu11@example.com', 2),
(12, 'Mocanu', 'Elena', 1991121212346, 21, 'elena.mocanu12@example.com', 2),
(13, 'Stan', 'Ioana', 1995120512345, 21, 'ioana.stan13@example.com', 2),
(14, 'Popa', 'Gheorghe', 1993071012345, 23, 'gheorghe.popa14@example.com', 2),
(15, 'Marin', 'Catalin', 1997012012345, 19, 'catalin.marin15@example.com', 2),
(16, 'Alexandru', 'Gabriel', 1998050312345, 18, 'gabriel.alexandru16@example.com', 2),
(17, 'Dinu', 'Adriana', 1994110112345, 22, 'adriana.dinu17@example.com', 2),
(18, 'Vasilescu', 'Daniela', 1995121212345, 20, 'daniela.vasilescu18@example.com', 2),
(19, 'Petrescu', 'Lucian', 1994091412345, 21, 'lucian.petrescu19@example.com', 2),
(20, 'Lungu', 'Bianca', 1996122212345, 20, 'bianca.lungu20@example.com', 2),
(21, 'Dumitrescu', 'Ion', 1989072012345, 22, 'ion.dumitrescu21@example.com', 3),
(22, 'Radu', 'Constantin', 1993061712345, 23, 'constantin.radu22@example.com', 3),
(23, 'Ciobanu', 'Florin', 1991121312345, 21, 'florin.ciobanu23@example.com', 3),
(24, 'Badea', 'Ana', 1995020812345, 19, 'ana.badea24@example.com', 3),
(25, 'Pavel', 'Eugen', 1997110912345, 18, 'eugen.pavel25@example.com', 3),
(26, 'Vasile', 'Roxana', 1995091812345, 20, 'roxana.vasile26@example.com', 3),
(27, 'Pop', 'Cosmin', 1997121512345, 18, 'cosmin.pop27@example.com', 3),
(28, 'Iacob', 'Alina', 1998092012345, 18, 'alina.iacob28@example.com', 3),
(29, 'Fodor', 'Sorin', 1994121112345, 21, 'sorin.fodor29@example.com', 3),
(30, 'Voicu', 'Monica', 1993093012345, 22, 'monica.voicu30@example.com', 3),
(31, 'Stan', 'Cristina', 1993090912345, 23, 'cristina.stan31@example.com', 4),
(32, 'Popescu', 'Daniel', 1997050512345, 19, 'daniel.popescu32@example.com', 4),
(33, 'Petre', 'Elena', 1991121012345, 21, 'elena.petre33@example.com', 4),
(34, 'Marinescu', 'Alexandru', 1996092512345, 20, 'alexandru.marinescu34@example.com', 4),
(35, 'Vlad', 'Mihaela', 1995090612345, 21, 'mihaela.vlad35@example.com', 4),
(36, 'Voinea', 'Sergiu', 1994091712345, 21, 'sergiu.voinea36@example.com', 4),
(37, 'Vasilescu', 'Cristian', 1992110812345, 22, 'cristian.vasilescu37@example.com', 4),
(38, 'Nistor', 'Bogdan', 1997031412345, 19, 'bogdan.nistor38@example.com', 4),
(39, 'Iliescu', 'Oana', 1995081912345, 20, 'oana.iliescu39@example.com', 4),
(40, 'Cristea', 'Roxana', 1995100912345, 20, 'roxana.cristea40@example.com', 4),
(41, 'Radu', 'Mihai', 1991121212345, 21, 'mihai.radu41@example.com', 5),
(42, 'Ilie', 'Florin', 1995050312345, 19, 'florin.ilie42@example.com', 5),
(43, 'Dumitru', 'Catalina', 1998022012345, 18, 'catalina.dumitru43@example.com', 5),
(44, 'Avram', 'Dan', 1992091912345, 22, 'dan.avram44@example.com', 5),
(45, 'Moraru', 'Elena', 1994123012345, 21, 'elena.moraru45@example.com', 5),
(46, 'Balan', 'Adrian', 1996031812345, 20, 'adrian.balan46@example.com', 5),
(47, 'Serban', 'Cosmina', 1997092012345, 18, 'cosmina.serban47@example.com', 5),
(48, 'Anghel', 'Irina', 1998121012345, 18, 'irina.anghel48@example.com', 5),
(49, 'Chirila', 'Alin', 1995100512345, 21, 'alin.chirila49@example.com', 5),
(50, 'Maxim', 'Anca', 1996110912345, 20, 'anca.maxim50@example.com', 5),
(51, 'Tudor', 'Ana', 1992080812345, 20, 'ana.tudor51@example.com', 6),
(52, 'Marcu', 'Radu', 1996010212345, 22, 'radu.marcu52@example.com', 6),
(53, 'Ionita', 'Adrian', 1993032012345, 23, 'adrian.ionita53@example.com', 6),
(54, 'Stroe', 'Diana', 1995101012345, 20, 'diana.stroe54@example.com', 6),
(55, 'Badea', 'Razvan', 1997121512345, 18, 'razvan.badea55@example.com', 6),
(56, 'Lazar', 'Simona', 1994101212345, 21, 'simona.lazar56@example.com', 6),
(57, 'Voinea', 'Ionut', 1996021512345, 19, 'ionut.voinea57@example.com', 6),
(58, 'Ardelean', 'Denisa', 1998110812345, 18, 'denisa.ardelean58@example.com', 6),
(59, 'Petre', 'Silviu', 1995051212345, 19, 'silviu.petre59@example.com', 6),
(60, 'Draghici', 'Oana', 1996111312345, 20, 'oana.draghici60@example.com', 6),
(61, 'Georgescu', 'Vasile', 1990051512345, 24, 'vasile.georgescu61@example.com', 7),
(62, 'Marian', 'Florin', 1998021512345, 18, 'florin.marian62@example.com', 7),
(63, 'Neagu', 'Alina', 1996020812345, 22, 'alina.neagu63@example.com', 7),
(64, 'Lupu', 'Cezar', 1997120112345, 18, 'cezar.lupu64@example.com', 7),
(65, 'Stoian', 'Georgeta', 1996101012345, 20, 'georgeta.stoian65@example.com', 7),
(66, 'Grigore', 'Sonia', 1998030212345, 18, 'sonia.grigore66@example.com', 7),
(67, 'Vlad', 'Cristian', 1993071012345, 23, 'cristian.vlad67@example.com', 7),
(68, 'Ilie', 'Larisa', 1994122312345, 21, 'larisa.ilie68@example.com', 7),
(69, 'Barbu', 'Ovidiu', 1995011812345, 19, 'ovidiu.barbu69@example.com', 7),
(70, 'Sima', 'Raluca', 1994090912345, 22, 'raluca.sima70@example.com', 7),
(71, 'Nistor', 'Laura', 1993020212345, 23, 'laura.nistor71@example.com', 8),
(72, 'Avram', 'Claudiu', 1995123012345, 21, 'claudiu.avram72@example.com', 8),
(73, 'Dragomir', 'Gabriela', 1996110712345, 20, 'gabriela.dragomir73@example.com', 8),
(74, 'Neacsu', 'Dan', 1997091412345, 18, 'dan.neacsu74@example.com', 8),
(75, 'Stanciu', 'Ioana', 1998101812345, 18, 'ioana.stanciu75@example.com', 8),
(76, 'Gheorghe', 'Razvan', 1997120312345, 18, 'razvan.gheorghe76@example.com', 8),
(77, 'Petre', 'Luciana', 1995090212345, 20, 'luciana.petre77@example.com', 8),
(78, 'Munteanu', 'Victor', 1996062512345, 20, 'victor.munteanu78@example.com', 8),
(79, 'Voicu', 'Marius', 1993071112345, 23, 'marius.voicu79@example.com', 8),
(80, 'Constantin', 'George', 1991111012345, 20, 'george.constantin80@example.com', 8);

-- --------------------------------------------------------

--
-- Table structure for table `utilizatori`
--

CREATE TABLE `utilizatori` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `utilizatori`
--

INSERT INTO `utilizatori` (`id`, `username`, `password`) VALUES
(1, 'admin', 'parola123'),
(2, 'user1', 'parola'),
(3, 'andreea', 'parola'),
(4, 'david', 'david');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `antrenor`
--
ALTER TABLE `antrenor`
  ADD PRIMARY KEY (`id_antrenor`),
  ADD KEY `id_echipa` (`id_echipa`);

--
-- Indexes for table `echipa`
--
ALTER TABLE `echipa`
  ADD PRIMARY KEY (`id_echipa`);

--
-- Indexes for table `echipa_meci`
--
ALTER TABLE `echipa_meci`
  ADD PRIMARY KEY (`id_echipa_meci`),
  ADD KEY `id_meci` (`id_meci`),
  ADD KEY `id_echipa_1` (`id_echipa_1`),
  ADD KEY `id_echipa_2` (`id_echipa_2`);

--
-- Indexes for table `facultate`
--
ALTER TABLE `facultate`
  ADD PRIMARY KEY (`id_facultate`),
  ADD KEY `id_echipa` (`id_echipa`);

--
-- Indexes for table `meci`
--
ALTER TABLE `meci`
  ADD PRIMARY KEY (`id_meci`);

--
-- Indexes for table `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id_student`),
  ADD KEY `id_echipa` (`id_echipa`);

--
-- Indexes for table `utilizatori`
--
ALTER TABLE `utilizatori`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `utilizatori`
--
ALTER TABLE `utilizatori`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `antrenor`
--
ALTER TABLE `antrenor`
  ADD CONSTRAINT `antrenor_ibfk_1` FOREIGN KEY (`id_echipa`) REFERENCES `echipa` (`id_echipa`);

--
-- Constraints for table `echipa_meci`
--
ALTER TABLE `echipa_meci`
  ADD CONSTRAINT `echipa_meci_ibfk_1` FOREIGN KEY (`id_meci`) REFERENCES `meci` (`id_meci`),
  ADD CONSTRAINT `echipa_meci_ibfk_2` FOREIGN KEY (`id_echipa_1`) REFERENCES `echipa` (`id_echipa`),
  ADD CONSTRAINT `echipa_meci_ibfk_3` FOREIGN KEY (`id_echipa_2`) REFERENCES `echipa` (`id_echipa`);

--
-- Constraints for table `facultate`
--
ALTER TABLE `facultate`
  ADD CONSTRAINT `facultate_ibfk_1` FOREIGN KEY (`id_echipa`) REFERENCES `echipa` (`id_echipa`);

--
-- Constraints for table `student`
--
ALTER TABLE `student`
  ADD CONSTRAINT `student_ibfk_1` FOREIGN KEY (`id_echipa`) REFERENCES `echipa` (`id_echipa`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
