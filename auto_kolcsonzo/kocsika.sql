-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Jan 30. 13:55
-- Kiszolgáló verziója: 10.4.32-MariaDB
-- PHP verzió: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Adatbázis: `kocsika`
--

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `foglalas`
--

CREATE TABLE `foglalas` (
  `UserID` int(11) NOT NULL,
  `ItemsID` int(11) NOT NULL,
  `mikortol` date NOT NULL,
  `meddig` date NOT NULL,
  `elvitte` enum('igen','nem') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `items`
--

CREATE TABLE `items` (
  `ItemsID` int(11) NOT NULL,
  `R/U` varchar(7) NOT NULL,
  `tipus` enum('szemelygepauto','haszonauto','munkagep','motorkerekpar','egyeb') NOT NULL,
  `uzemanyag` enum('Üres','Benzin','Dízel','Benzingaz','Hybrid','Elektromos') NOT NULL,
  `marka` varchar(255) NOT NULL,
  `modell` varchar(255) NOT NULL,
  `kivitel` enum('Cabrio','Sedan','Hatchback','Kombi','Pickup','Coupe','Van','Buggy','Sport','SUV','Terepjáró','Egyéb','Motor') NOT NULL,
  `sz_szem` int(11) NOT NULL,
  `suly` int(11) NOT NULL,
  `ajtokszama` int(11) NOT NULL,
  `ar/nap` int(11) NOT NULL,
  `loero` int(11) NOT NULL,
  `nyomatek` int(11) NOT NULL,
  `selejt` enum('igen','nem') NOT NULL,
  `UserID` int(11) NOT NULL,
  `kep` varchar(255) DEFAULT NULL,
  `leiras` varchar(2000) NOT NULL,
  `telefon` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `items`
--

INSERT INTO `items` (`ItemsID`, `R/U`, `tipus`, `uzemanyag`, `marka`, `modell`, `kivitel`, `sz_szem`, `suly`, `ajtokszama`, `ar/nap`, `loero`, `nyomatek`, `selejt`, `UserID`, `kep`, `leiras`, `telefon`) VALUES
(11, 'abc-123', 'szemelygepauto', 'Benzin', 'lada', '2000', 'Sedan', 5, 100, 4, 1000, 80, 70, 'nem', 5, 'uploads/1769609599_lada.jpg', 'A Lada az orosz AvtoVAZ járműgyártó vállalat gépkocsimárkája. A szovjet időszakban ezt a márkanevet csak az exportra szánt járműveknél használták, ugyanazokat a modelleket a belső szovjet piacon Zsiguli márkanéven forgalmazták, de az autók a keleti blokkbeli szocialista országokba is cirill betűs Zsiguli felirattal érkeztek a forgalmazás első éveiben.[1]\r\n\r\n1993-ban a Vjacseszlav Zubarev által 1992-ben alapított TTS szerződést írt alá az AvtoVAZ-zal. 1995-ben Naberezsnije Cselni városában megnyílt az első teljes értékű LADA autóközpont, amely közvetlen szállítást indított az autógyárból. 1995-ben irodát nyitottak Kazanyban. 1997-ig az autókat Naberezsnije Cselniből hajtották. Után-vasúti szállítás[', 22222);

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `kolcsonzes`
--

CREATE TABLE `kolcsonzes` (
  `UserID` int(11) NOT NULL,
  `ItemsID` int(11) NOT NULL,
  `mikortol` date NOT NULL,
  `meddig` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `szig` int(11) NOT NULL,
  `lakc` varchar(255) NOT NULL,
  `jogosultsag` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`UserID`, `username`, `name`, `email`, `password`, `szig`, `lakc`, `jogosultsag`) VALUES
(5, 'test', 'test', 'test@gmail.com', '$2y$10$4TuXDsp4JiHbjhksRitV..35Qg4GAhii/7MnRqVINdn6k0b8W5ZyO', 1234, '1234', 0),
(6, 'kili_boss', 'kilike', 'kili@gmail.com', '$2y$10$RW7y4GVImaIs7XNIXW1oV.TIaeKuiMnl.TrXmkCo6ZXXcrUebYlzK', 4444, 'kili haz', 1),
(7, 'test2', 'test2', 'test2@gmail.com', '$2y$10$gl1UXleEFfaleUcEKzdtF.Dp5WDAFZ.tSiX8zEXWvcZ9IW8c0zrPe', 4444, 'test2', 0);

--
-- Indexek a kiírt táblákhoz
--

--
-- A tábla indexei `foglalas`
--
ALTER TABLE `foglalas`
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ItemsID` (`ItemsID`);

--
-- A tábla indexei `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`ItemsID`),
  ADD KEY `fk_items_user` (`UserID`);

--
-- A tábla indexei `kolcsonzes`
--
ALTER TABLE `kolcsonzes`
  ADD KEY `UserID` (`UserID`),
  ADD KEY `ItemsID` (`ItemsID`);

--
-- A tábla indexei `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`UserID`);

--
-- A kiírt táblák AUTO_INCREMENT értéke
--

--
-- AUTO_INCREMENT a táblához `items`
--
ALTER TABLE `items`
  MODIFY `ItemsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Megkötések a kiírt táblákhoz
--

--
-- Megkötések a táblához `foglalas`
--
ALTER TABLE `foglalas`
  ADD CONSTRAINT `foglalas_ibfk_1` FOREIGN KEY (`ItemsID`) REFERENCES `items` (`ItemsID`),
  ADD CONSTRAINT `foglalas_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Megkötések a táblához `items`
--
ALTER TABLE `items`
  ADD CONSTRAINT `fk_items_user` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`) ON DELETE CASCADE;

--
-- Megkötések a táblához `kolcsonzes`
--
ALTER TABLE `kolcsonzes`
  ADD CONSTRAINT `kolcsonzes_ibfk_1` FOREIGN KEY (`ItemsID`) REFERENCES `items` (`ItemsID`),
  ADD CONSTRAINT `kolcsonzes_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
