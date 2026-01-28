-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Jan 22. 14:15
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
  `selejt` enum('igen','nem') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `items`
--

INSERT INTO `items` (`ItemsID`, `R/U`, `tipus`, `uzemanyag`, `marka`, `modell`, `kivitel`, `sz_szem`, `suly`, `ajtokszama`, `ar/nap`, `loero`, `nyomatek`, `selejt`) VALUES
(1, 'FOS-671', 'szemelygepauto', 'Benzin', 'Lada', '2105', 'Sedan', 5, 1060, 4, 1000, 73, 110, 'nem'),
(3, 'RKI-906', 'haszonauto', 'Benzin', 'GMC', 'SYCLONE', 'Pickup', 2, 1600, 2, 5000, 280, 475, 'nem'),
(4, 'Nincs', 'munkagep', 'Dízel', 'John Deere', '7R 350', 'Egyéb', 1, 11400, 2, 8000, 350, 1580, 'nem'),
(5, 'XYZ-999', 'motorkerekpar', 'Benzin', 'Ducati', 'Panigale V4', 'Motor', 1, 190, 0, 3500, 230, 124, 'nem');

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
  `password` varchar(50) NOT NULL,
  `szig` int(11) NOT NULL,
  `lakc` varchar(255) NOT NULL,
  `jogosultsag` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`UserID`, `username`, `name`, `email`, `password`, `szig`, `lakc`, `jogosultsag`) VALUES
(1, 'mc doktorúr', 'mc istvániusz', 'mcpityu@gmail.com', 'darko', 55555, 'miskolc', 1),
(2, 'kili_boss', 'kili', 'kili@gmail.com', '$2y$10$zmATMjHzdVI/9ujfvqcEyeM14iP1qIpbyBYoYw7VD2W', 4444, 'kili haz', 0);

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
  ADD PRIMARY KEY (`ItemsID`);

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
  MODIFY `ItemsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Megkötések a táblához `kolcsonzes`
--
ALTER TABLE `kolcsonzes`
  ADD CONSTRAINT `kolcsonzes_ibfk_1` FOREIGN KEY (`ItemsID`) REFERENCES `items` (`ItemsID`),
  ADD CONSTRAINT `kolcsonzes_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
