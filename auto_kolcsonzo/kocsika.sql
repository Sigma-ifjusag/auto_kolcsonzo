-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Gép: 127.0.0.1
-- Létrehozás ideje: 2026. Feb 12. 11:14
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

--
-- A tábla adatainak kiíratása `foglalas`
--

INSERT INTO `foglalas` (`UserID`, `ItemsID`, `mikortol`, `meddig`, `elvitte`) VALUES
(6, 17, '2026-04-30', '2026-07-22', 'nem');

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
  `telefon` int(11) NOT NULL,
  `kiemelt` enum('nem','igen') NOT NULL DEFAULT 'nem',
  `kiadott` enum('nem','igen') NOT NULL DEFAULT 'nem'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `items`
--

INSERT INTO `items` (`ItemsID`, `R/U`, `tipus`, `uzemanyag`, `marka`, `modell`, `kivitel`, `sz_szem`, `suly`, `ajtokszama`, `ar/nap`, `loero`, `nyomatek`, `selejt`, `UserID`, `kep`, `leiras`, `telefon`, `kiemelt`, `kiadott`) VALUES
(17, 'AB EN-2', 'szemelygepauto', 'Benzin', 'Mercedes-AMG', 'GT R', 'Sport', 2, 1890, 2, 36000, 585, 700, 'nem', 8, NULL, 'Egy igazi modern sportautó.', 32431151, 'nem', 'nem'),
(21, 'TSO-571', 'szemelygepauto', 'Benzin', 'Lexus', 'LFA', 'Sport', 2, 1614, 2, 50000, 560, 480, 'nem', 8, NULL, 'Ez egy ritka sportautó amiből összesn csak 500 darab készült. ', 32431151, 'nem', 'nem'),
(22, 'ZNL-173', 'szemelygepauto', 'Benzin', 'Audi', 'RS6 Avant', 'Kombi', 5, 2150, 5, 32000, 600, 850, 'nem', 9, NULL, 'Egy erős kombi.', 183141, 'igen', 'nem'),
(25, 'GJS-273', 'haszonauto', 'Benzin', 'GMC', 'Syclone', 'Pickup', 2, 1600, 2, 19000, 280, 475, 'nem', 10, NULL, 'Ez egy nagy teljesítményű pickup.', 3224121, 'nem', 'nem'),
(26, 'Nincs', 'munkagep', 'Benzin', 'John Deere', '6250R', 'Egyéb', 2, 10540, 2, 45000, 250, 1167, 'nem', 10, NULL, 'Egy traktor amivel bármilyen munkát el lehet végezni.', 3224121, 'nem', 'nem'),
(27, 'SAJ-934', 'szemelygepauto', 'Benzin', 'BMW', 'X5 M50i', 'SUV', 5, 2995, 5, 29000, 530, 750, 'nem', 8, NULL, 'Egy városi terepjáró.', 32431151, 'nem', 'nem');

-- --------------------------------------------------------

--
-- Tábla szerkezet ehhez a táblához `item_images`
--

CREATE TABLE `item_images` (
  `ImageID` int(11) NOT NULL,
  `ItemsID` int(11) NOT NULL,
  `kep` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `item_images`
--

INSERT INTO `item_images` (`ImageID`, `ItemsID`, `kep`) VALUES
(13, 17, 'uploads/1770377586_565441.jpg'),
(14, 17, 'uploads/1770377586_565442.jpg'),
(15, 17, 'uploads/1770377586_565453.jpg'),
(16, 17, 'uploads/1770377586_565454.jpg'),
(35, 21, 'uploads/1770378162_6c32054cc176912c18d63d7a02627efc.webp'),
(36, 21, 'uploads/1770378162_9f62dbce4d6375743ab6ecdc87aa279f.webp'),
(37, 21, 'uploads/1770378162_eaa2ebd6a25ebbea3433ce2f20f7c8d1.webp'),
(38, 21, 'uploads/1770378162_f71099283fee9486b5960f3bfeae2b1a.webp'),
(39, 21, 'uploads/1770378162_fb1497e1992d73df5206c55a3c4c82e2.webp'),
(40, 22, 'uploads/1770378592_13592524.jpg'),
(41, 22, 'uploads/1770378592_13592525.jpg'),
(42, 22, 'uploads/1770378592_13592530.jpg'),
(43, 22, 'uploads/1770378592_13592532.jpg'),
(60, 25, 'uploads/1770380412_865ab249ed4820d1f1a11b0b0425a735.webp'),
(61, 25, 'uploads/1770380412_gmc.webp'),
(62, 25, 'uploads/1770380412_gmc2.webp'),
(63, 25, 'uploads/1770380412_gmc3.webp'),
(64, 25, 'uploads/1770380412_gmc5.webp'),
(65, 26, 'uploads/1770381124_20321594.jpg'),
(66, 26, 'uploads/1770381124_20321600.jpg'),
(67, 26, 'uploads/1770381124_20321605.jpg'),
(68, 26, 'uploads/1770381124_20321618.jpg'),
(69, 27, 'uploads/1770882678_3568784.jpg'),
(70, 27, 'uploads/1770882678_3568833.jpg'),
(71, 27, 'uploads/1770882678_3568934.jpg'),
(72, 27, 'uploads/1770882678_3569024.jpg');

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
  `jogosultsag` int(1) NOT NULL,
  `profile_pic` varchar(255) DEFAULT 'images/defavatar.webp'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- A tábla adatainak kiíratása `users`
--

INSERT INTO `users` (`UserID`, `username`, `name`, `email`, `password`, `szig`, `lakc`, `jogosultsag`, `profile_pic`) VALUES
(5, 'test', 'test', 'test@gmail.com', '$2y$10$4TuXDsp4JiHbjhksRitV..35Qg4GAhii/7MnRqVINdn6k0b8W5ZyO', 1234, '1234', 0, 'uploads/profile_5_1770816722.png'),
(6, 'kili_boss', 'kilike', 'kili@gmail.com', '$2y$10$RW7y4GVImaIs7XNIXW1oV.TIaeKuiMnl.TrXmkCo6ZXXcrUebYlzK', 4444, 'kili haz', 1, 'images/defavatar.webp'),
(7, 'test2', 'test2', 'test2@gmail.com', '$2y$10$gl1UXleEFfaleUcEKzdtF.Dp5WDAFZ.tSiX8zEXWvcZ9IW8c0zrPe', 4444, 'test2', 0, 'images/defavatar.webp'),
(8, '0RespectHun0', 'Bence Zwick', 'zwickbence23@gmail.com', '$2y$10$a9JqQn2qrbh8CB5p52HGHOi3TgezsItMKLAQs2TzG.eHrlYUH0B/a', 23414, 'Dunaharaszti', 0, 'images/defavatar.webp'),
(9, 'Kiliboss', 'Mézner Kilián Pál', 'mezner01@gmail.com', '$2y$10$SDMJAzGxDwT.uTnLaKuO5uPL/0UuftG3NDg3REWAzGWUeokc61zBe', 142402, 'Csepel', 0, 'images/defavatar.webp'),
(10, 'MC Isti', 'Köröskényi István', 'isti69@gmail.com', '$2y$10$PAeXwU5HZRQDXegb8Ejzb.JFJ7AGZR66a00D831NrmrPaCcc30M/W', 124144, 'Miskolc', 0, 'images/defavatar.webp');

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
-- A tábla indexei `item_images`
--
ALTER TABLE `item_images`
  ADD PRIMARY KEY (`ImageID`),
  ADD KEY `fk_item_images_items` (`ItemsID`);

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
  MODIFY `ItemsID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT a táblához `item_images`
--
ALTER TABLE `item_images`
  MODIFY `ImageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT a táblához `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
-- Megkötések a táblához `item_images`
--
ALTER TABLE `item_images`
  ADD CONSTRAINT `fk_item_images_items` FOREIGN KEY (`ItemsID`) REFERENCES `items` (`ItemsID`) ON DELETE CASCADE;

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
