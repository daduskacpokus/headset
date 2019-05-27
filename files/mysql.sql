-- Adminer 4.7.1 MySQL dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP TABLE IF EXISTS `across`;
CREATE TABLE `across` (
  `row_id` int(11) NOT NULL AUTO_INCREMENT,
  `row_date` timestamp NOT NULL,
  `increment_id` varchar(12) DEFAULT NULL,
  `increment_label` varchar(32) DEFAULT NULL,
  `increment_condition` varchar(32) DEFAULT NULL,
  `increment_storage` varchar(32) DEFAULT NULL,
  `decrement_id` varchar(12) DEFAULT NULL,
  `decrement_label` varchar(32) DEFAULT NULL,
  `decrement_condition` varchar(32) DEFAULT NULL,
  `decrement_storage` varchar(32) DEFAULT NULL,
  `reverse_date` timestamp NOT NULL,
  `rotate` binary(1) NOT NULL,
  PRIMARY KEY (`row_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


DROP TABLE IF EXISTS `trace`;
CREATE TABLE `trace` (
  `device` varchar(32) NOT NULL,
  `storage` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `motion` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `state` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;


-- 2019-05-27 20:17:27
