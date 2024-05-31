CREATE DATABASE IF NOT EXISTS `fitsystem`;
USE `fitsystem`;

DROP TABLE IF EXISTS `admin`;
CREATE TABLE `admin` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `username` VARCHAR(255) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
);

DROP TABLE IF EXISTS `client`;
CREATE TABLE `client` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `email` VARCHAR(255) NOT NULL,
  `date_of_birth` DATE NOT NULL,
  `gender` ENUM('Male','Female','Other') NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `photo` MEDIUMTEXT NULL,
  `address` TEXT NOT NULL,
  PRIMARY KEY (`id`)
);

DROP TABLE IF EXISTS `class`;
CREATE TABLE `class` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `icon` VARCHAR(255) NOT NULL,
  `description` TEXT NOT NULL,
  `status` BOOL NOT NULL,
  PRIMARY KEY (`id`)
);


DROP TABLE IF EXISTS `client_health_metrics`;
CREATE TABLE `client_health_metrics` (
  `id` int NOT NULL AUTO_INCREMENT,
  `client_id` int DEFAULT NULL,
  `weight` decimal(5,2) NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `imc_value` decimal(5,2) NOT NULL,
  `date_recorded` date NOT NULL DEFAULT (curdate()),
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_health_metrics_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
