-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 06, 2026 at 07:42 AM
-- Server version: 8.0.31
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pharmacy v2`
--

-- --------------------------------------------------------

--
-- Table structure for table `disposalrecord`
--

CREATE TABLE `disposalrecord` (
  `disposal_ID` varchar(10) NOT NULL,
  `batchNumber` varchar(50) DEFAULT NULL,
  `disposalDate` date NOT NULL,
  `method` varchar(100) DEFAULT NULL,
  `disposalStatus` enum('PENDING','COMPLETED') DEFAULT 'PENDING',
  `managedBy` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `disposalrecord`
--

INSERT INTO `disposalrecord` (`disposal_ID`, `batchNumber`, `disposalDate`, `method`, `disposalStatus`, `managedBy`) VALUES
('10', 'B048', '2024-03-10', 'Disposal according to SOP', 'COMPLETED', 'U003'),
('11', 'B002', '2025-02-01', 'Return to supplier', 'PENDING', 'U002'),
('12', 'B009', '2025-02-01', 'Disposal according to SOP', 'PENDING', 'U003'),
('13', 'B013', '2025-02-01', 'Disposal according to SOP', 'PENDING', 'U002'),
('14', 'B017', '2025-02-01', 'Return to supplier', 'PENDING', 'U003'),
('15', 'B024', '2025-02-01', 'Disposal according to SOP', 'PENDING', 'U002'),
('16', 'B029', '2025-02-01', 'Return to supplier', 'PENDING', 'U003'),
('17', 'B032', '2025-02-01', 'Disposal according to SOP', 'PENDING', 'U002'),
('18', 'B038', '2025-02-01', 'Return to supplier', 'PENDING', 'U003'),
('19', 'B044', '2025-02-01', 'Disposal according to SOP', 'PENDING', 'U002'),
('20', 'B047', '2025-02-01', 'Return to supplier', 'PENDING', 'U003'),
('3', 'B014', '2024-02-20', 'Disposal according to SOP', 'COMPLETED', 'U002'),
('4', 'B019', '2024-01-05', 'Return to supplier', 'COMPLETED', 'U003'),
('5', 'B022', '2024-03-05', 'Disposal according to SOP', 'COMPLETED', 'U002'),
('7', 'B034', '2024-04-10', 'Return to supplier', 'COMPLETED', 'U002'),
('8', 'B039', '2024-01-25', 'Disposal according to SOP', 'COMPLETED', 'U003'),
('9', 'B042', '2023-11-20', 'Return to supplier', 'COMPLETED', 'U002'),
('DISP-001', 'B003', '2024-01-15', 'Disposal according to SOP', 'COMPLETED', 'U002');

-- --------------------------------------------------------

--
-- Table structure for table `expiryalert`
--

CREATE TABLE `expiryalert` (
  `alertID` int NOT NULL,
  `batchNumber` varchar(50) NOT NULL,
  `alertDate` date NOT NULL,
  `alertStatus` enum('NORMAL','EXPIRING SOON','EXPIRED') DEFAULT 'NORMAL',
  `isRead` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `productID` int NOT NULL,
  `productName` varchar(100) NOT NULL,
  `brandName` varchar(100) DEFAULT NULL,
  `supplierID` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`productID`, `productName`, `brandName`, `supplierID`) VALUES
(1, 'Paracetamol 500mg', 'Panadol', 1),
(2, 'Amoxicillin 250mg', 'Amoxil', 2),
(3, 'Cetirizine 10mg', 'Zyrtec', 3),
(4, 'Metformin 500mg', 'Glucophage', 4),
(5, 'Ibuprofen 400mg', 'Advil', 5),
(6, 'Loratadine 10mg', 'Claritin', 1),
(7, 'Atorvastatin 20mg', 'Lipitor', 2),
(8, 'Omeprazole 20mg', 'Prilosec', 3),
(9, 'Amlodipine 5mg', 'Norvasc', 4),
(10, 'Salbutamol Inhaler', 'Ventolin', 5),
(12, 'Amoxicillin 500mg', 'Moxatag', 3),
(13, 'paracetamol 500mg', 'Panadol', 1);

-- --------------------------------------------------------

--
-- Table structure for table `productitem`
--

CREATE TABLE `productitem` (
  `batchNumber` varchar(50) NOT NULL,
  `productID` int NOT NULL,
  `quantity` int DEFAULT '0',
  `expiryDate` date NOT NULL
) ;

--
-- Dumping data for table `productitem`
--

INSERT INTO `productitem` (`batchNumber`, `productID`, `quantity`, `expiryDate`) VALUES
('B001', 1, 500, '2026-12-01'),
('B002', 1, 200, '2025-06-15'),
('B003', 1, 50, '2024-01-10'),
('B004', 1, 300, '2027-02-20'),
('B005', 1, 150, '2026-08-30'),
('B006', 2, 100, '2026-05-12'),
('B008', 2, 80, '2027-01-15'),
('B009', 2, 45, '2025-05-20'),
('B010', 2, 200, '2026-10-10'),
('B011', 3, 400, '2026-11-22'),
('B012', 3, 350, '2027-03-05'),
('B013', 3, 100, '2025-07-01'),
('B014', 3, 10, '2024-02-14'),
('B015', 3, 500, '2026-09-18'),
('B016', 4, 250, '2026-04-30'),
('B017', 4, 120, '2025-08-15'),
('B018', 4, 300, '2027-05-01'),
('B019', 4, 60, '2023-12-25'),
('B020', 4, 180, '2026-07-09'),
('B021', 5, 600, '2026-12-10'),
('B022', 5, 30, '2024-03-01'),
('B023', 5, 450, '2027-08-20'),
('B024', 5, 90, '2025-04-30'),
('B025', 5, 220, '2026-11-05'),
('B026', 6, 140, '2026-10-15'),
('B027', 6, 160, '2027-01-10'),
('B029', 6, 55, '2025-06-05'),
('B030', 6, 300, '2026-09-30'),
('B031', 7, 200, '2026-08-20'),
('B032', 7, 80, '2025-05-15'),
('B033', 7, 150, '2027-02-12'),
('B034', 7, 40, '2024-04-05'),
('B035', 7, 250, '2026-12-01'),
('B036', 8, 500, '2026-07-14'),
('B037', 8, 400, '2027-03-30'),
('B038', 8, 120, '2025-08-01'),
('B039', 8, 15, '2024-01-20'),
('B040', 8, 300, '2026-11-15'),
('B041', 9, 220, '2026-05-25'),
('B042', 9, 50, '2023-11-15'),
('B043', 9, 180, '2027-04-10'),
('B044', 9, 70, '2025-07-20'),
('B045', 9, 350, '2026-10-05'),
('B046', 10, 100, '2026-09-10'),
('B047', 10, 30, '2025-06-30'),
('B048', 10, 12, '2024-02-28'),
('B049', 10, 85, '2027-05-15'),
('B050', 10, 120, '2026-12-20');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplierID` int NOT NULL,
  `supplierName` varchar(100) NOT NULL,
  `supplierEmail` varchar(100) DEFAULT NULL,
  `picName` varchar(100) DEFAULT NULL,
  `picPhoneNum` varchar(15) DEFAULT NULL,
  `picEmail` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplierID`, `supplierName`, `supplierEmail`, `picName`, `picPhoneNum`, `picEmail`) VALUES
(1, 'PharmaDistribute Sdn Bhd', 'sales@pharmadist.com', 'Mr. Robert', '012-1112222', 'robert@pharmadist.com'),
(2, 'MediSupply Global', 'info@medisupply.com', 'Ms. Wong', '012-3334444', 'wong@medisupply.com'),
(3, 'HealthFirst Pharma', 'order@healthfirst.com', 'Encik Ahmad', '019-5556666', 'ahmad@healthfirst.com'),
(4, 'Apex Pharmacy Wholesaler', 'contact@apex.com', 'Mr. Lee', '011-7778888', 'lee@apex.com'),
(5, 'BioMed Suppliers', 'support@biomed.com', 'Ms. Sarah', '013-9990000', 'sarah@biomed.com');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` varchar(10) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phoneNum` varchar(15) DEFAULT NULL,
  `role` enum('MANAGER','PHARMACIST','PENDING') DEFAULT 'PENDING',
  `accountStatus` enum('ACTIVE','INACTIVE') DEFAULT 'INACTIVE'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `username`, `password`, `name`, `email`, `phoneNum`, `role`, `accountStatus`) VALUES
('U001', 'manager_Eli', '$2y$10$O90Lmj.03tIcHLfWvD/uaO2A/BJUajC1mmQIx4FHy3RyzKS3kNY9m', 'Elizabeth', 'Eli@pharmacy.com', '012-2348907', 'MANAGER', 'ACTIVE'),
('U002', 'pharm_siti', '$2y$10$FnZI/fOTq8YUpn6GvuUx2.71ZW03b.QxBaDQBYCFt0FxyaaXH3XN.', 'Siti Aminah', 'siti@pharmacy.com', '011-2233445', 'PHARMACIST', 'ACTIVE'),
('U003', 'pharm_raj', '$2y$10$FnZI/fOTq8YUpn6GvuUx2.71ZW03b.QxBaDQBYCFt0FxyaaXH3XN.', 'Raj Kumar', 'raj@pharmacy.com', '017-7788990', 'MANAGER', 'ACTIVE'),
('U004', 'pending_low', '$2y$10$FnZI/fOTq8YUpn6GvuUx2.71ZW03b.QxBaDQBYCFt0FxyaaXH3XN.', 'Low Wei Kit', 'low@test.com', '019-9988776', 'PENDING', 'INACTIVE'),
('U005', 'pending_lim', '$2y$10$FnZI/fOTq8YUpn6GvuUx2.71ZW03b.QxBaDQBYCFt0FxyaaXH3XN.', 'Lim Mei Xin', 'lim@test.com', '014-4455667', 'PENDING', 'INACTIVE'),
('U006', 'test', '$2y$10$rgIezx3dbKPf2RTyjkH6zOa3vHSNUcDrdqHYFWoqq6zSHkt37IXpi', 'test', 'test@123', NULL, 'PENDING', 'INACTIVE');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `disposalrecord`
--
ALTER TABLE `disposalrecord`
  ADD PRIMARY KEY (`disposal_ID`),
  ADD KEY `fk_disposal_user` (`managedBy`),
  ADD KEY `disposalrecord_ibfk_1` (`batchNumber`);

--
-- Indexes for table `expiryalert`
--
ALTER TABLE `expiryalert`
  ADD PRIMARY KEY (`alertID`),
  ADD KEY `batchNumber` (`batchNumber`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`productID`),
  ADD KEY `supplierID` (`supplierID`);

--
-- Indexes for table `productitem`
--
ALTER TABLE `productitem`
  ADD PRIMARY KEY (`batchNumber`),
  ADD KEY `productID` (`productID`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplierID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `expiryalert`
--
ALTER TABLE `expiryalert`
  MODIFY `alertID` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `productID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplierID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `disposalrecord`
--
ALTER TABLE `disposalrecord`
  ADD CONSTRAINT `disposalrecord_ibfk_1` FOREIGN KEY (`batchNumber`) REFERENCES `productitem` (`batchNumber`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_disposal_user` FOREIGN KEY (`managedBy`) REFERENCES `user` (`userID`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `expiryalert`
--
ALTER TABLE `expiryalert`
  ADD CONSTRAINT `expiryalert_ibfk_1` FOREIGN KEY (`batchNumber`) REFERENCES `productitem` (`batchNumber`) ON DELETE CASCADE;

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`supplierID`) REFERENCES `supplier` (`supplierID`) ON DELETE SET NULL;

--
-- Constraints for table `productitem`
--
ALTER TABLE `productitem`
  ADD CONSTRAINT `productitem_ibfk_1` FOREIGN KEY (`productID`) REFERENCES `product` (`productID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
