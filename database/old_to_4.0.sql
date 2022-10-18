-- MySQL dump 10.16  Distrib 10.1.44-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: 157c_sys
-- ------------------------------------------------------
-- Server version	10.1.44-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- ADD fields to ospos_sales
-- sale_uuid, confirm: 0,1,2; 0: don't confirm; 1: OK; 2: not OK
ALTER TABLE `ospos_sales` ADD `confirm` TINYINT(1) NOT NULL DEFAULT 0 AFTER `ctv_id`; 
    ALTER TABLE `ospos_sales` ADD `sale_uuid` VARCHAR(250) NOT NULL DEFAULT UUID() AFTER `confirm`; 

--
-- ADD fields to ospos_customers
    ALTER TABLE `ospos_customers` ADD `points` DECIMAL(10,2) NOT NULL DEFAULT '0' AFTER `password`; 
    ALTER TABLE `ospos_customers` ADD `customer_uuid` VARCHAR(250) NOT NULL DEFAULT UUID() AFTER `points`; 
--
-- Table structure for table `ospos_customers`
--

DROP TABLE IF EXISTS `ospos_short_survey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_short_survey` (
  `id` int(10) NOT NULL,
  `customer_id` int(10),
  `sale_id` int(10),
  `sale_uuid` varchar(255) DEFAULT NULL,
  `nvbh_id` int(10) NOT NULL DEFAULT '0',
  `kxv_id` int(10) NOT NULL DEFAULT '0',
  `created_date` int(11) NOT NULL DEFAULT '0',
  `q1` int(1) NOT NULL DEFAULT '1',
  `q2` int(1) NOT NULL DEFAULT '1',
  `q3` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `ospos_short_survey` ADD PRIMARY KEY( `id`); 
ALTER TABLE `ospos_short_survey` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT; 
/*!40101 SET character_set_client = @saved_cs_client */;


DROP TABLE IF EXISTS `ospos_history_points`;

CREATE TABLE `ospos_history_points` (
  `id` int(10) NOT NULL,
  `customer_id` int(10) NOT NULL DEFAULT 0,
  `sale_id` int(10) NOT NULL DEFAULT 0,
  `sale_uuid` varchar(250) CHARACTER SET utf8 NOT NULL DEFAULT '0',
  `created_date` int(11) NOT NULL DEFAULT 0,
  `point` decimal(10,2) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `note` text CHARACTER SET utf8 NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `ospos_history_points` ADD PRIMARY KEY( `id`); 
ALTER TABLE `ospos_history_points` CHANGE `id` `id` INT(10) NOT NULL AUTO_INCREMENT; 
