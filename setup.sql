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

DROP TABLE IF EXISTS `class_schedule`;
CREATE TABLE `class_schedule` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `class_id` INT NOT NULL,
  `weekday` ENUM('Monday','Tuesday','Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday') NOT NULL,
  `hour` ENUM('09:00','10:00','11:00','12:00','13:00','14:00','15:00','16:00','17:00','18:00','19:00','20:00','21:00','22:00') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `class_id` (`class_id`),
  CONSTRAINT `class_schedule_fk_class` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE,
  UNIQUE KEY `unique_schedule` (`class_id`, `weekday`, `hour`)
);

DROP TABLE IF EXISTS `class_subscription`;
CREATE TABLE `class_subscription` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `client_id` INT NOT NULL,
  `class_id` INT NOT NULL,
  `subscription_start` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `subscription_end` DATETIME NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_client_class` (`client_id`, `class_id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `class_subscription_fk_client` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`) ON DELETE CASCADE,
  KEY `class_id` (`class_id`),
  CONSTRAINT `class_subscription_fk_class` FOREIGN KEY (`class_id`) REFERENCES `class` (`id`) ON DELETE CASCADE
);
  PRIMARY KEY (`id`),
  KEY `client_id` (`client_id`),
  CONSTRAINT `client_health_metrics_ibfk_1` FOREIGN KEY (`client_id`) REFERENCES `client` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
