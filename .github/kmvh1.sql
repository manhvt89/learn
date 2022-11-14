-- MariaDB dump 10.19  Distrib 10.5.17-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: ospos
-- ------------------------------------------------------
-- Server version	10.5.17-MariaDB-1:10.5.17+maria~ubu2004

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ospos_app_config`
--

DROP TABLE IF EXISTS `ospos_app_config`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_app_config` (
  `key` varchar(50) NOT NULL,
  `value` varchar(500) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_app_config`
--

LOCK TABLES `ospos_app_config` WRITE;
/*!40000 ALTER TABLE `ospos_app_config` DISABLE KEYS */;
INSERT INTO `ospos_app_config` VALUES ('address','157 CHÙA LÁNG'),('barcode_content','number'),('barcode_first_row','name'),('barcode_font','Arial.ttf'),('barcode_font_size','10'),('barcode_generate_if_empty','1'),('barcode_height','20'),('barcode_num_in_row','1'),('barcode_page_cellspacing','20'),('barcode_page_width','100'),('barcode_quality','100'),('barcode_second_row','item_code'),('barcode_third_row','unit_price'),('barcode_type','Code128'),('barcode_width','220'),('client_id','675e673a-1518-4bc1-93e1-8eff41ebdade'),('company','KÍNH THUỐC KÍNH MẮT PHÁT TRIỂN'),('company_logo','company_logo1.png'),('country_codes','vn'),('currency_decimals','0'),('currency_symbol','₫'),('custom10_name',''),('custom1_name',''),('custom2_name',''),('custom3_name',''),('custom4_name',''),('custom5_name',''),('custom6_name',''),('custom7_name',''),('custom8_name',''),('custom9_name',''),('dateformat','d/m/Y'),('default_sales_discount','0'),('default_tax_1_name','VAT'),('default_tax_1_rate','10'),('default_tax_2_name',''),('default_tax_2_rate',''),('default_tax_rate','8'),('email',''),('fax',''),('invoice_default_comments',''),('invoice_email_message',''),('invoice_enable','0'),('language','english'),('language_code','en'),('lines_per_page','25'),('mailpath','/usr/sbin/sendmail'),('msg_msg',''),('msg_pwd',''),('msg_src',''),('msg_uid',''),('notify_horizontal_position','center'),('notify_vertical_position','top'),('number_locale','vi_VN'),('payment_options_order','cashdebitcredit'),('phone','320 157 57'),('print_bottom_margin','0'),('print_footer','0'),('print_header','0'),('print_left_margin','0'),('print_right_margin','0'),('print_silently','0'),('print_top_margin','0'),('protocol','mail'),('quantity_decimals','0'),('receipt_printer','HP LaserJet Professional P1102'),('receipt_show_description','0'),('receipt_show_serialnumber','0'),('receipt_show_taxes','0'),('receipt_show_total_discount','1'),('receipt_template','receipt_default'),('receiving_calculate_average_price','1'),('recv_invoice_format','$CO'),('return_policy','Hệ thống truy cập vào tài nguyên'),('sales_invoice_format','$CO'),('smtp_crypto','ssl'),('smtp_port','465'),('smtp_timeout','5'),('statistics','1'),('takings_printer','HP LaserJet Professional P1102'),('tax_decimals','2'),('tax_included','1'),('theme','cerulean'),('thousands_separator','thousands_separator'),('timeformat','H:i:s'),('timezone','Asia/Bangkok'),('website','');
/*!40000 ALTER TABLE `ospos_app_config` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ospos_customers`
--

DROP TABLE IF EXISTS `ospos_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_customers` (
  `person_id` int(10) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `taxable` int(1) NOT NULL DEFAULT 1,
  `discount_percent` decimal(15,2) NOT NULL DEFAULT 0.00,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `username` varchar(50) DEFAULT NULL,
  `password` varchar(250) DEFAULT NULL,
  `points` decimal(10,2) NOT NULL DEFAULT 0.00,
  `customer_uuid` varchar(250) NOT NULL DEFAULT uuid(),
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_customers`
--

--
-- Table structure for table `ospos_daily_total`
--

DROP TABLE IF EXISTS `ospos_daily_total`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_daily_total` (
  `daily_total_id` int(10) NOT NULL AUTO_INCREMENT,
  `created_time` int(11) DEFAULT NULL,
  `begining_amount` decimal(15,2) NOT NULL,
  `ending_amount` decimal(15,2) NOT NULL,
  `increase_amount` decimal(15,2) NOT NULL,
  `decrease_amount` decimal(15,2) DEFAULT NULL,
  PRIMARY KEY (`daily_total_id`),
  KEY `sale_id` (`daily_total_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_daily_total`
--



--
-- Table structure for table `ospos_employees`
--

DROP TABLE IF EXISTS `ospos_employees`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_employees` (
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `person_id` int(10) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `hash_version` int(1) NOT NULL DEFAULT 2,
  `type` tinyint(1) DEFAULT 1 COMMENT '1:staff;2:CTV',
  UNIQUE KEY `username` (`username`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_employees`
--

LOCK TABLES `ospos_employees` WRITE;
/*!40000 ALTER TABLE `ospos_employees` DISABLE KEYS */;
INSERT INTO `ospos_employees` VALUES (('admin','$2y$10$2MwikhUEMMiyWHfLx9oQ3.cqGG.P/XLFIh8g7C4BL1.TiWnmR8sLS',1,0,2,1));
/*!40000 ALTER TABLE `ospos_employees` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ospos_fields`
--

DROP TABLE IF EXISTS `ospos_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_fields` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `field_key` varchar(250) DEFAULT NULL,
  `permission_id` int(10) NOT NULL DEFAULT 0,
  `permission` tinyint(1) NOT NULL DEFAULT 2,
  `field_name` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_fields`
--

--
-- Table structure for table `ospos_grants`
--

DROP TABLE IF EXISTS `ospos_grants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_grants` (
  `permission_id` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_grants`
--

LOCK TABLES `ospos_grants` WRITE;
/*!40000 ALTER TABLE `ospos_grants` DISABLE KEYS */;
INSERT INTO `ospos_grants` VALUES ('100',2),('100',3),('100',5),('101',2),('101',3),('101',5),('102',1),('106',1),('106',2),('106',3),('106',5),('108',1),('109',1),('111',1),('112',1),('112',2),('112',3),('112',5),('113',1),('114',1),('115',1),('115',5),('116',1),('116',5),('117',1),('117',2),('117',3),('117',5),('118',1),('118',2),('118',3),('118',5),('12',1),('12',2),('12',3),('12',4),('12',5),('17',1),('17',2),('17',3),('17',4),('17',5),('18',1),('18',2),('18',3),('18',4),('18',5),('19',1),('21',1),('21',4),('23',1),('23',2),('23',3),('23',4),('23',5),('24',1),('26',1),('26',2),('26',3),('26',4),('26',5),('26',8714),('27',1),('27',2),('27',3),('27',4),('27',5),('27',8714),('28',1),('28',4),('28',5),('28',8714),('29',1),('29',4),('29',5),('29',8714),('30',1),('30',2),('30',4),('30',5),('30',8714),('31',1),('31',2),('31',4),('31',5),('31',8714),('32',1),('32',4),('32',5),('32',8714),('33',1),('33',4),('33',5),('33',8714),('34',1),('34',4),('34',5),('34',8714),('35',1),('35',4),('35',5),('35',6),('35',8714),('36',1),('36',4),('36',5),('36',8714),('37',1),('37',4),('37',5),('37',8714),('4',1),('4',2),('4',3),('4',5),('47',1),('47',5),('49',1),('49',5),('5',1),('5',2),('5',3),('5',5),('51',1),('52',1),('52',5),('53',1),('53',5),('54',1),('54',2),('54',3),('54',5),('55',1),('55',5),('56',1),('57',1),('58',1),('60',1),('60',5),('61',1),('62',1),('62',5),('65',1),('65',5),('67',1),('67',5),('68',1),('68',5),('68',6),('69',1),('69',5),('70',1),('70',5),('71',1),('71',5),('77',1),('77',2),('77',3),('77',5),('78',1),('78',5),('80',1),('81',1),('82',1),('83',1),('84',1),('85',1),('86',1),('87',1),('88',1),('89',1),('90',1),('91',1),('92',1),('93',1),('94',1),('95',1),('97',1),('97',5),('99',1),('99',5);
/*!40000 ALTER TABLE `ospos_grants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ospos_history_points`
--

DROP TABLE IF EXISTS `ospos_history_points`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_history_points` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) NOT NULL DEFAULT 0,
  `sale_id` int(10) NOT NULL DEFAULT 0,
  `sale_uuid` varchar(250) NOT NULL DEFAULT '0',
  `created_date` int(11) NOT NULL DEFAULT 0,
  `point` decimal(10,2) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0,
  `note` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_history_points`
--
--
-- Table structure for table `ospos_inventory`
--

DROP TABLE IF EXISTS `ospos_inventory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_inventory` (
  `trans_id` int(11) NOT NULL AUTO_INCREMENT,
  `trans_items` int(11) NOT NULL DEFAULT 0,
  `trans_user` int(11) NOT NULL DEFAULT 0,
  `trans_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `trans_comment` text NOT NULL,
  `trans_location` int(11) NOT NULL,
  `trans_inventory` decimal(15,3) NOT NULL DEFAULT 0.000,
  PRIMARY KEY (`trans_id`),
  KEY `trans_items` (`trans_items`),
  KEY `trans_user` (`trans_user`),
  KEY `trans_location` (`trans_location`)
) ENGINE=InnoDB AUTO_INCREMENT=29268 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_inventory`
--

--
-- Table structure for table `ospos_item_kit_items`
--

DROP TABLE IF EXISTS `ospos_item_kit_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_item_kit_items` (
  `item_kit_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `quantity` decimal(15,3) NOT NULL,
  PRIMARY KEY (`item_kit_id`,`item_id`,`quantity`),
  KEY `ospos_item_kit_items_ibfk_2` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_item_kit_items`
--

--
-- Table structure for table `ospos_item_kits`
--

DROP TABLE IF EXISTS `ospos_item_kits`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_item_kits` (
  `item_kit_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  PRIMARY KEY (`item_kit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;


--
-- Table structure for table `ospos_item_quantities`
--

DROP TABLE IF EXISTS `ospos_item_quantities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_item_quantities` (
  `item_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `quantity` decimal(15,3) NOT NULL DEFAULT 0.000,
  PRIMARY KEY (`item_id`,`location_id`),
  KEY `item_id` (`item_id`),
  KEY `location_id` (`location_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `ospos_items`
--

DROP TABLE IF EXISTS `ospos_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_items` (
  `name` varchar(255) NOT NULL,
  `category` varchar(255) NOT NULL,
  `supplier_id` int(11) DEFAULT NULL,
  `item_number` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `cost_price` decimal(15,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `reorder_level` decimal(15,3) NOT NULL DEFAULT 0.000,
  `receiving_quantity` decimal(15,3) NOT NULL DEFAULT 1.000,
  `item_id` int(10) NOT NULL AUTO_INCREMENT,
  `pic_id` int(10) DEFAULT NULL,
  `allow_alt_description` tinyint(1) NOT NULL,
  `is_serialized` tinyint(1) NOT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `custom1` varchar(25) NOT NULL,
  `custom2` varchar(25) NOT NULL,
  `custom3` varchar(25) NOT NULL,
  `custom4` varchar(25) NOT NULL,
  `custom5` varchar(25) NOT NULL,
  `custom6` varchar(25) NOT NULL,
  `custom7` varchar(25) NOT NULL,
  `custom8` varchar(25) NOT NULL,
  `custom9` varchar(25) NOT NULL,
  `custom10` varchar(25) NOT NULL,
  `standard_amount` decimal(15,3) NOT NULL DEFAULT 0.000,
  `item_number_new` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: mới tạo; 1 đã đồng bộ; 3 edited; ',
  PRIMARY KEY (`item_id`),
  UNIQUE KEY `item_number` (`item_number`),
  KEY `supplier_id` (`supplier_id`),
  KEY `unit_cost` (`unit_price`),
  FULLTEXT KEY `name` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=26155 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_items`
--

--
-- Table structure for table `ospos_items_taxes`
--

DROP TABLE IF EXISTS `ospos_items_taxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_items_taxes` (
  `item_id` int(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  PRIMARY KEY (`item_id`,`name`,`percent`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_items_taxes`
--

--
-- Table structure for table `ospos_messages`
--

DROP TABLE IF EXISTS `ospos_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_messages` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `to` varchar(25) DEFAULT NULL,
  `content` varchar(255) DEFAULT NULL,
  `type` tinyint(1) DEFAULT 0 COMMENT '0: gửi cảm ơn; 1: gửi nhắc khám;2 gửi sinh nhật;3 gửi giảm giá; 4 gửi sự kiện',
  `employee_id` int(11) DEFAULT NULL,
  `name` varchar(25) DEFAULT '',
  `created_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`message_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_messages`
--
--
-- Table structure for table `ospos_modules`
--

DROP TABLE IF EXISTS `ospos_modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_modules` (
  `name_lang_key` varchar(255) NOT NULL,
  `desc_lang_key` varchar(255) NOT NULL,
  `sort` int(10) NOT NULL,
  `module_key` varchar(255) NOT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(250) DEFAULT NULL,
  `name` varchar(250) DEFAULT NULL,
  `created_at` int(11) NOT NULL DEFAULT 0,
  `updated_at` int(11) NOT NULL DEFAULT 0,
  `deleted_at` int(11) NOT NULL DEFAULT 0,
  `module_uuid` varchar(250) NOT NULL DEFAULT uuid(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `desc_lang_key` (`desc_lang_key`),
  UNIQUE KEY `name_lang_key` (`name_lang_key`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_modules`
--

LOCK TABLES `ospos_modules` WRITE;
/*!40000 ALTER TABLE `ospos_modules` DISABLE KEYS */;
INSERT INTO `ospos_modules` VALUES ('module_account','module_account_desc',120,'account',1,NULL,'Kế toán',0,0,0,'aa3922b7-5819-11ed-b65f-040300000000'),('module_config','module_config_desc',130,'config',2,NULL,'Thiết lập',0,0,0,'aa392523-5819-11ed-b65f-040300000000'),('module_customers','module_customers_desc',10,'customers',3,NULL,'Khách hàng',0,0,0,'aa3926ef-5819-11ed-b65f-040300000000'),('module_customer_info','module_customer_info',121,'customer_info',4,NULL,'Bảo hành',0,0,0,'aa3927cc-5819-11ed-b65f-040300000000'),('module_employees','module_employees_desc',80,'employees',5,NULL,'Nhân viên',0,0,0,'aa3928f4-5819-11ed-b65f-040300000000'),('module_giftcards','module_giftcards_desc',90,'giftcards',6,NULL,'Quà tặng',0,0,0,'aa392a99-5819-11ed-b65f-040300000000'),('module_items','module_items_desc',20,'items',7,NULL,'Sản phẩm',0,0,0,'aa392b4c-5819-11ed-b65f-040300000000'),('module_item_kits','module_item_kits_desc',30,'item_kits',8,NULL,'Nhóm sản phẩm',0,0,0,'aa392bf1-5819-11ed-b65f-040300000000'),('module_messages','module_messages_desc',100,'messages',9,NULL,'Tin nhắn',0,0,0,'aa392ca6-5819-11ed-b65f-040300000000'),('module_order','module_order_desc',150,'order',10,NULL,NULL,0,0,0,'aa392d4a-5819-11ed-b65f-040300000000'),('module_receivings','module_receivings_desc',60,'receivings',11,NULL,'Nhập hàng',0,0,0,'aa392df2-5819-11ed-b65f-040300000000'),('module_reminders','module_reminders_desc',140,'reminders',12,NULL,NULL,0,0,0,'aa392ea1-5819-11ed-b65f-040300000000'),('module_reports','module_reports_desc',50,'reports',13,NULL,'Báo cáo',0,0,0,'aa392f58-5819-11ed-b65f-040300000000'),('module_sales','module_sales_desc',70,'sales',14,NULL,'Bán hàng',0,0,0,'aa393000-5819-11ed-b65f-040300000000'),('module_suppliers','module_suppliers_desc',40,'suppliers',15,NULL,'Nhà cung cấp',0,0,0,'aa3930d4-5819-11ed-b65f-040300000000'),('module_test','module_test_desc',110,'test',16,NULL,'Đo mắt',0,0,0,'aa393173-5819-11ed-b65f-040300000000'),('roles','roles',10,'roles',17,'roles','Phân quyền',1667225850,0,0,'c14fb1fe-5926-11ed-b3d8-040300000000');
/*!40000 ALTER TABLE `ospos_modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ospos_people`
--

DROP TABLE IF EXISTS `ospos_people`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_people` (
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `gender` int(1) DEFAULT NULL,
  `phone_number` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `address_1` varchar(255) NOT NULL,
  `address_2` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `zip` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `comments` text NOT NULL,
  `person_id` int(10) NOT NULL AUTO_INCREMENT,
  `age` char(2) NOT NULL DEFAULT '0',
  `facebook` varchar(250) DEFAULT '',
  PRIMARY KEY (`person_id`),
  KEY `first_name` (`first_name`),
  KEY `phone_number` (`phone_number`),
  FULLTEXT KEY `last_name` (`last_name`)
) ENGINE=InnoDB AUTO_INCREMENT=104580 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_people`
--

LOCK TABLES `ospos_people` WRITE;
/*!40000 ALTER TABLE `ospos_people` DISABLE KEYS */;
INSERT INTO `ospos_people` VALUES ('admin','admin',1,'091301933','manhvt89@gmail.com','Address 1','HN','Hà Nội','HN','10001','Việt Nam','',1,'0',''));
/*!40000 ALTER TABLE `ospos_people` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ospos_permissions`
--

DROP TABLE IF EXISTS `ospos_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_permissions` (
  `permission_key` varchar(255) NOT NULL,
  `module_id` varchar(255) NOT NULL,
  `location_id` int(10) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module_key` varchar(250) NOT NULL DEFAULT '''''',
  `permissions_uuid` varchar(250) NOT NULL DEFAULT uuid(),
  `module_uuid` varchar(250) NOT NULL DEFAULT uuid(),
  `name` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `module_id` (`module_id`),
  KEY `ospos_permissions_ibfk_2` (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=119 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_permissions`
--

LOCK TABLES `ospos_permissions` WRITE;
/*!40000 ALTER TABLE `ospos_permissions` DISABLE KEYS */;
INSERT INTO `ospos_permissions` VALUES ('account_manage','account',NULL,4,'account','a5664fbb-5cd9-11ed-b8c5-040300000000','0a63f505-5cd9-11ed-b8c5-040300000000',NULL),('account_view','account',NULL,5,'account','a5665085-5cd9-11ed-b8c5-040300000000','0a63f5c9-5cd9-11ed-b8c5-040300000000',NULL),('customers_view','customers',NULL,12,'customers','a5665202-5cd9-11ed-b8c5-040300000000','0a63f745-5cd9-11ed-b8c5-040300000000','Tạo mới khách hàng'),('items_accounting','items',NULL,17,'items','a5665b0b-5cd9-11ed-b8c5-040300000000','0a63f9fc-5cd9-11ed-b8c5-040300000000','Kế toán'),('items_stock','items',1,18,'items','a5665bc5-5cd9-11ed-b8c5-040300000000','0a63fb3f-5cd9-11ed-b8c5-040300000000','Kho'),('item_kits','item_kits',NULL,19,'item_kits','a5665c8f-5cd9-11ed-b8c5-040300000000','0a63fc32-5cd9-11ed-b8c5-040300000000',NULL),('order','order',NULL,21,'order','a5665ee5-5cd9-11ed-b8c5-040300000000','0a63fda6-5cd9-11ed-b8c5-040300000000',NULL),('receivings_stock','receivings',1,23,'receivings','a5665fa0-5cd9-11ed-b8c5-040300000000','0a63fe5c-5cd9-11ed-b8c5-040300000000',NULL),('reminders','reminders',1,24,'reminders','a566606e-5cd9-11ed-b8c5-040300000000','0a63ff2e-5cd9-11ed-b8c5-040300000000',NULL),('reports_categories','reports',NULL,26,'reports','a5666128-5cd9-11ed-b8c5-040300000000','0a63ffe6-5cd9-11ed-b8c5-040300000000',NULL),('reports_customers','reports',NULL,27,'reports','a56661d8-5cd9-11ed-b8c5-040300000000','0a64008e-5cd9-11ed-b8c5-040300000000',NULL),('reports_discounts','reports',NULL,28,'reports','a5666286-5cd9-11ed-b8c5-040300000000','0a64013c-5cd9-11ed-b8c5-040300000000',NULL),('reports_employees','reports',NULL,29,'reports','a5666334-5cd9-11ed-b8c5-040300000000','0a6401ec-5cd9-11ed-b8c5-040300000000',NULL),('reports_inventory','reports',NULL,30,'reports','a56663dc-5cd9-11ed-b8c5-040300000000','0a640293-5cd9-11ed-b8c5-040300000000',NULL),('reports_items','reports',NULL,31,'reports','a5666481-5cd9-11ed-b8c5-040300000000','0a64033c-5cd9-11ed-b8c5-040300000000',NULL),('reports_lens','reports',NULL,32,'reports','a5666523-5cd9-11ed-b8c5-040300000000','0a6403ea-5cd9-11ed-b8c5-040300000000',NULL),('reports_payments','reports',NULL,33,'reports','a56665ce-5cd9-11ed-b8c5-040300000000','0a640495-5cd9-11ed-b8c5-040300000000',NULL),('reports_receivings','reports',NULL,34,'reports','a566666e-5cd9-11ed-b8c5-040300000000','0a64056a-5cd9-11ed-b8c5-040300000000',NULL),('reports_sales','reports',NULL,35,'reports','a5666709-5cd9-11ed-b8c5-040300000000','0a640619-5cd9-11ed-b8c5-040300000000',NULL),('reports_suppliers','reports',NULL,36,'reports','a56667a8-5cd9-11ed-b8c5-040300000000','0a6406b5-5cd9-11ed-b8c5-040300000000',NULL),('reports_taxes','reports',NULL,37,'reports','a5666849-5cd9-11ed-b8c5-040300000000','0a640756-5cd9-11ed-b8c5-040300000000',NULL),('test_manage','test',NULL,47,'test','a5666d8a-5cd9-11ed-b8c5-040300000000','0a640c85-5cd9-11ed-b8c5-040300000000',NULL),('customers_index','customers',NULL,49,'customers','a5666ed7-5cd9-11ed-b8c5-040300000000','0a640dcb-5cd9-11ed-b8c5-040300000000','Danh sách khách hàng'),('customers_delete','customers',NULL,51,'customers','a5666f9e-5cd9-11ed-b8c5-040300000000','0a640e88-5cd9-11ed-b8c5-040300000000','Xóa'),('customers_excel_import','customers',NULL,52,'customers','a5667065-5cd9-11ed-b8c5-040300000000','0a640f42-5cd9-11ed-b8c5-040300000000','Nhập excel'),('customers_excel_import','customers',NULL,53,'customers','a5667124-5cd9-11ed-b8c5-040300000000','0a640ff6-5cd9-11ed-b8c5-040300000000','Nhập excel'),('items_index','items',NULL,54,'items','a56671e1-5cd9-11ed-b8c5-040300000000','0a6410ab-5cd9-11ed-b8c5-040300000000','Danh sách sản phẩm'),('items_excel_import','items',NULL,55,'items','a5667293-5cd9-11ed-b8c5-040300000000','0a64115a-5cd9-11ed-b8c5-040300000000','Nhập excel'),('items_delete','items',NULL,56,'items','a5667349-5cd9-11ed-b8c5-040300000000','0a64120a-5cd9-11ed-b8c5-040300000000','Xóa sản phẩm'),('items_bulk_update','items',NULL,57,'items','a56673fb-5cd9-11ed-b8c5-040300000000','0a6412bb-5cd9-11ed-b8c5-040300000000','Cập nhật nhiều sản phẩm'),('items_save_inventory','items',NULL,58,'items','a56674b2-5cd9-11ed-b8c5-040300000000','0a641368-5cd9-11ed-b8c5-040300000000','Lưu vào kho'),('items_bulk_edit','items',NULL,60,'items','a566756e-5cd9-11ed-b8c5-040300000000','0a64141d-5cd9-11ed-b8c5-040300000000','Chỉnh sửa hàng loạt'),('items_inventory','items',NULL,61,'items','a5667623-5cd9-11ed-b8c5-040300000000','0a6414c7-5cd9-11ed-b8c5-040300000000','Kho'),('items_view','items',NULL,62,'items','a56676ca-5cd9-11ed-b8c5-040300000000','0a641566-5cd9-11ed-b8c5-040300000000','Tạo mới'),('suppliers_view','suppliers',NULL,65,'suppliers','a5667770-5cd9-11ed-b8c5-040300000000','0a641606-5cd9-11ed-b8c5-040300000000','Tạo mới'),('suppliers_delete','suppliers',NULL,67,'suppliers','a5667834-5cd9-11ed-b8c5-040300000000','0a6416b2-5cd9-11ed-b8c5-040300000000','Xóa'),('reports_index','reports',NULL,68,'reports','a56678f0-5cd9-11ed-b8c5-040300000000','0a64176a-5cd9-11ed-b8c5-040300000000','Danh sách báo cáo'),('receivings_index','receivings',NULL,69,'receivings','a56679b0-5cd9-11ed-b8c5-040300000000','0a64181c-5cd9-11ed-b8c5-040300000000','Danh sách '),('receivings_lens','receivings',NULL,70,'receivings','a5667a61-5cd9-11ed-b8c5-040300000000','0a6418ca-5cd9-11ed-b8c5-040300000000','Nhập tròng kính'),('receivings_view','receivings',NULL,71,'receivings','a5667b14-5cd9-11ed-b8c5-040300000000','0a641979-5cd9-11ed-b8c5-040300000000','Nhập hàng'),('sales_index','sales',NULL,77,'sales','a5667f5c-5cd9-11ed-b8c5-040300000000','0a641d91-5cd9-11ed-b8c5-040300000000','Bán hàng (tạo mới)'),('test_index','test',NULL,78,'test','a566804a-5cd9-11ed-b8c5-040300000000','0a641e2f-5cd9-11ed-b8c5-040300000000','Danh sách đơn kính'),('config_index','config',NULL,80,'config','a56681c7-5cd9-11ed-b8c5-040300000000','0a641f9c-5cd9-11ed-b8c5-040300000000','Danh sách'),('roles_index','roles',NULL,81,'roles','a5668293-5cd9-11ed-b8c5-040300000000','0a64204b-5cd9-11ed-b8c5-040300000000','Nhóm quyền'),('roles_create','roles',NULL,82,'roles','a5668351-5cd9-11ed-b8c5-040300000000','0a6420f6-5cd9-11ed-b8c5-040300000000','Tạo nhóm quyền'),('roles_view','roles',NULL,83,'roles','a5668403-5cd9-11ed-b8c5-040300000000','0a6421a5-5cd9-11ed-b8c5-040300000000','Xem nhóm quyền'),('roles_edit','roles',NULL,84,'roles','a56684c1-5cd9-11ed-b8c5-040300000000','0a642255-5cd9-11ed-b8c5-040300000000','Sửa nhóm quyền'),('roles_per_index','roles',NULL,85,'roles','a5668583-5cd9-11ed-b8c5-040300000000','0a642306-5cd9-11ed-b8c5-040300000000','Quyền'),('roles_per_add','roles',NULL,86,'roles','a5668636-5cd9-11ed-b8c5-040300000000','0a6423ae-5cd9-11ed-b8c5-040300000000','Thêm quyền'),('roles_per_view','roles',NULL,87,'roles','a56686d4-5cd9-11ed-b8c5-040300000000','0a642449-5cd9-11ed-b8c5-040300000000','Xem quyền'),('roles_per_edit','roles',NULL,88,'roles','a566877f-5cd9-11ed-b8c5-040300000000','0a6424ea-5cd9-11ed-b8c5-040300000000','Sửa quyền'),('roles_mod_index','roles',NULL,89,'roles','a566882e-5cd9-11ed-b8c5-040300000000','0a642589-5cd9-11ed-b8c5-040300000000','Danh sách mô đun'),('roles_mod_add','roles',NULL,90,'roles','a56688d4-5cd9-11ed-b8c5-040300000000','0a642624-5cd9-11ed-b8c5-040300000000','Thêm mô đun'),('roles_mod_view','roles',NULL,91,'roles','a566896f-5cd9-11ed-b8c5-040300000000','0a6426be-5cd9-11ed-b8c5-040300000000','Xem mô đun'),('roles_mod_edit','roles',NULL,92,'roles','a5668a19-5cd9-11ed-b8c5-040300000000','0a64275c-5cd9-11ed-b8c5-040300000000','Sửa mô đun'),('employees_index','employees',NULL,93,'employees','a5668ac8-5cd9-11ed-b8c5-040300000000','0a6427fe-5cd9-11ed-b8c5-040300000000','Danh sách nhân viên'),('employees_view','employees',NULL,94,'employees','a5668b7c-5cd9-11ed-b8c5-040300000000','0a6428ab-5cd9-11ed-b8c5-040300000000','Tạo mới'),('employees_delete','employees',NULL,95,'employees','a5668c2d-5cd9-11ed-b8c5-040300000000','0a64295a-5cd9-11ed-b8c5-040300000000','Xóa'),('items_count_details','items',NULL,97,'items','a5668db5-5cd9-11ed-b8c5-040300000000','0a642abe-5cd9-11ed-b8c5-040300000000','Xem chi tiết sản phẩm trong kho'),('suppliers_index','suppliers',NULL,99,'suppliers','a5668e77-5cd9-11ed-b8c5-040300000000','0a642b7c-5cd9-11ed-b8c5-040300000000','Danh sách nhà cung cấp'),('items_unitprice_hide','items',NULL,100,'items','6b284764-5cde-11ed-b8c5-040300000000','6b28476f-5cde-11ed-b8c5-040300000000','Ẩn giá nhập'),('customers_phonenumber_hide','customers',1,101,'customers','fe2fc7fc-5ce9-11ed-b8c5-040300000000','fe2fc804-5ce9-11ed-b8c5-040300000000',''),('sales_price_edit','sales',1,102,'sales','a974fae3-5d00-11ed-b8c5-040300000000','a974faeb-5d00-11ed-b8c5-040300000000','Cho phép thay đổi giá'),('sales_manage','sales',NULL,106,'sales','2f9353b8-5d0f-11ed-b8c5-040300000000','2f9353c3-5d0f-11ed-b8c5-040300000000','Danh sách đơn hàng'),('sales_delete','sales',1,108,'sales','9422bf13-5d0f-11ed-b8c5-040300000000','9422bf1d-5d0f-11ed-b8c5-040300000000',''),('giftcards_index','giftcards',1,109,'giftcards','dedb2c8f-5d0f-11ed-b8c5-040300000000','dedb2c9c-5d0f-11ed-b8c5-040300000000',''),('giftcards_view','giftcards',1,111,'giftcards','e50e23f4-5d0f-11ed-b8c5-040300000000','e50e23fd-5d0f-11ed-b8c5-040300000000',''),('test_detail_test','test',1,112,'test','25460ad1-5d10-11ed-b8c5-040300000000','25460ada-5d10-11ed-b8c5-040300000000',''),('test_delete','test',1,113,'test','314c3e14-5d10-11ed-b8c5-040300000000','314c3e1c-5d10-11ed-b8c5-040300000000',''),('test_edit','test',1,114,'test','31dca20f-5d10-11ed-b8c5-040300000000','31dca218-5d10-11ed-b8c5-040300000000',''),('test_view','test',1,115,'test','37a58d56-5d10-11ed-b8c5-040300000000','37a58d63-5d10-11ed-b8c5-040300000000',''),('test_view_test','test',1,116,'test','3f3976cd-5d10-11ed-b8c5-040300000000','3f3976d4-5d10-11ed-b8c5-040300000000',''),('account_index','account',1,117,'account','5776d5d3-5d10-11ed-b8c5-040300000000','5776d5dd-5d10-11ed-b8c5-040300000000',''),('customer_info_index','customer_info',1,118,'customer_info','6a9e1d60-5d10-11ed-b8c5-040300000000','6a9e1d69-5d10-11ed-b8c5-040300000000','');
/*!40000 ALTER TABLE `ospos_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ospos_receivings`
--

DROP TABLE IF EXISTS `ospos_receivings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_receivings` (
  `receiving_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `supplier_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT 0,
  `comment` text NOT NULL,
  `receiving_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(20) DEFAULT NULL,
  `reference` varchar(32) DEFAULT NULL,
  PRIMARY KEY (`receiving_id`),
  KEY `supplier_id` (`supplier_id`),
  KEY `employee_id` (`employee_id`),
  KEY `reference` (`reference`)
) ENGINE=InnoDB AUTO_INCREMENT=110 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_receivings`
--

--
-- Table structure for table `ospos_receivings_items`
--

DROP TABLE IF EXISTS `ospos_receivings_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_receivings_items` (
  `receiving_id` int(10) NOT NULL DEFAULT 0,
  `item_id` int(10) NOT NULL DEFAULT 0,
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL,
  `quantity_purchased` decimal(15,3) NOT NULL DEFAULT 0.000,
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` decimal(15,2) NOT NULL,
  `discount_percent` decimal(15,2) NOT NULL DEFAULT 0.00,
  `item_location` int(11) NOT NULL,
  `receiving_quantity` decimal(15,3) NOT NULL DEFAULT 1.000,
  PRIMARY KEY (`receiving_id`,`item_id`,`line`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_receivings_items`
--

--
-- Table structure for table `ospos_reminders`
--

DROP TABLE IF EXISTS `ospos_reminders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_reminders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `tested_date` int(11) DEFAULT NULL,
  `duration` int(1) DEFAULT NULL,
  `status` tinyint(1) DEFAULT 0 COMMENT '0 chưa remind; 1: đã remind; 2 remind lần 2; 3 remind lần 3',
  `remain` int(1) DEFAULT NULL COMMENT 'thời gian còn lại',
  `des` varchar(255) DEFAULT '',
  `action` varchar(10) DEFAULT NULL COMMENT '{sms:done;call:done;retest:done}',
  `expired_date` int(11) DEFAULT NULL,
  `created_date` int(11) DEFAULT NULL,
  `phone` varchar(25) DEFAULT '0',
  `customer_id` int(11) DEFAULT 0,
  `deleted` tinyint(11) DEFAULT 0,
  `is_sms` tinyint(1) DEFAULT 0 COMMENT '0 chưa gửi; 1 đã gửi thành công',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_reminders`
--



--
-- Table structure for table `ospos_reports_detail_sales`
--

DROP TABLE IF EXISTS `ospos_reports_detail_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_reports_detail_sales` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(25) DEFAULT NULL,
  `sale_time` timestamp NULL DEFAULT NULL,
  `amount` int(11) DEFAULT NULL,
  `saler` varchar(50) DEFAULT NULL,
  `buyer` varchar(50) DEFAULT NULL,
  `subtotal` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) DEFAULT NULL,
  `tax` decimal(10,2) DEFAULT NULL,
  `cost` decimal(10,2) DEFAULT NULL,
  `profit` decimal(10,2) DEFAULT NULL,
  `paid_customer` varchar(250) DEFAULT NULL,
  `comment` varchar(250) DEFAULT NULL,
  `kind` tinyint(1) DEFAULT 0 COMMENT '0: offline; 1: online',
  `items` text DEFAULT NULL,
  `sale_type` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_reports_detail_sales`
--
--
-- Table structure for table `ospos_role_permissions`
--

DROP TABLE IF EXISTS `ospos_role_permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_role_permissions` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `role_id` int(10) NOT NULL DEFAULT 0,
  `permission_id` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_role_permissions`
--

LOCK TABLES `ospos_role_permissions` WRITE;
/*!40000 ALTER TABLE `ospos_role_permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `ospos_role_permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ospos_roles`
--

DROP TABLE IF EXISTS `ospos_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `display_name` varchar(250) DEFAULT NULL,
  `code` varchar(20) DEFAULT NULL,
  `role_uuid` varchar(250) NOT NULL DEFAULT '0',
  `created_at` int(11) NOT NULL DEFAULT 0,
  `updated_at` int(11) NOT NULL DEFAULT 0,
  `deleted_at` int(11) NOT NULL DEFAULT 0,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_roles`
--

LOCK TABLES `ospos_roles` WRITE;
/*!40000 ALTER TABLE `ospos_roles` DISABLE KEYS */;
INSERT INTO `ospos_roles` VALUES (1,'admin','admin','ADM','7b498149-5877-11ed-a953-040300000000',0,0,0,0,'1'),(2,'Bán hàng','Bán hàng','SALE','7b4984a5-5877-11ed-a953-040300000000',0,0,0,0,'1'),(3,'Thu ngân','Thu ngân','thungan','7b4985c8-5877-11ed-a953-040300000000',0,0,0,0,'1'),(4,'Thủ kho','Thủ kho','thukho','7b498693-5877-11ed-a953-040300000000',0,0,0,0,'1'),(5,'Quản lý','Quản lý','MGR','7b49875d-5877-11ed-a953-040300000000',0,0,0,0,'1'),(6,'Nhà đầu tư','Nhà đầu tư','NDT','7b498829-5877-11ed-a953-040300000000',0,0,0,0,'1');
/*!40000 ALTER TABLE `ospos_roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ospos_sales`
--

DROP TABLE IF EXISTS `ospos_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_sales` (
  `sale_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT 0,
  `comment` text NOT NULL,
  `invoice_number` varchar(32) DEFAULT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `test_id` int(11) DEFAULT 0 COMMENT '{0: mua hang ko qua don; > 0 mua hang qua đơn khám}',
  `kxv_id` int(11) DEFAULT 0 COMMENT '{0: mua hang ko kxv; > 0 mua hang co kxv}',
  `doctor_id` int(11) DEFAULT 0 COMMENT '{0: mua hang ko doctor; > 0 mua hang co doctor}',
  `paid_points` decimal(10,2) NOT NULL DEFAULT 0.00 COMMENT 'Điểm dùng để thanh toán',
  `status` tinyint(1) DEFAULT 0 COMMENT '{1: dat coc;0: thanh toan đủ - hoàn thành}',
  `code` varchar(14) DEFAULT '0',
  `kind` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0: offline; 1: online',
  `shipping_address` varchar(250) DEFAULT '' COMMENT 'khác null khi kind=1',
  `shipping_city` varchar(100) DEFAULT '' COMMENT 'khac null kind = 1',
  `shipping_method` varchar(250) DEFAULT '' COMMENT 'VNPOST,VIETEL,....',
  `shipping_phone` varchar(11) DEFAULT '',
  `source` varchar(25) DEFAULT '',
  `completed` tinyint(1) DEFAULT 0 COMMENT '0 thông tin; 1 đặt hàng;2 chuyển đến nhà vận chuyển;3 nhận hàng;4 hoàn thành',
  `shipping_address_type` tinyint(1) DEFAULT 1,
  `shipping_fee` decimal(10,2) DEFAULT 0.00,
  `shipping_code` varchar(50) DEFAULT '',
  `ctv_id` int(11) DEFAULT 0,
  `confirm` tinyint(1) DEFAULT 0 COMMENT '0: chưa confirm\r\n1: đã confirm',
  `sale_uuid` varchar(250) NOT NULL DEFAULT uuid(),
  PRIMARY KEY (`sale_id`),
  UNIQUE KEY `invoice_number` (`invoice_number`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`),
  KEY `sale_time` (`sale_time`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_sales`
--

--
-- Table structure for table `ospos_sales_items`
--

DROP TABLE IF EXISTS `ospos_sales_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_sales_items` (
  `sale_id` int(10) NOT NULL DEFAULT 0,
  `item_id` int(10) NOT NULL DEFAULT 0,
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT 0,
  `quantity_purchased` decimal(15,3) NOT NULL DEFAULT 0.000,
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` decimal(15,2) NOT NULL,
  `discount_percent` decimal(15,2) NOT NULL DEFAULT 0.00,
  `item_location` int(11) NOT NULL,
  `item_name` varchar(250) DEFAULT NULL,
  `item_description` varchar(12) DEFAULT NULL,
  `item_number` varchar(12) DEFAULT NULL,
  `item_supplier_id` varchar(12) DEFAULT NULL,
  `item_category` varchar(250) DEFAULT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `sale_id` (`sale_id`),
  KEY `item_id` (`item_id`),
  KEY `item_location` (`item_location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_sales_items`
--

--
-- Table structure for table `ospos_sales_items_taxes`
--

DROP TABLE IF EXISTS `ospos_sales_items_taxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_sales_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `sale_id` (`sale_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_sales_items_taxes`
--


--
-- Table structure for table `ospos_sales_payments`
--

DROP TABLE IF EXISTS `ospos_sales_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_sales_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  `payment_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_kind` varchar(40) NOT NULL DEFAULT '''''' COMMENT '{Thanh Toán='''';Đặt Trước}',
  PRIMARY KEY (`payment_id`),
  KEY `sale_id` (`sale_id`)
) ENGINE=InnoDB AUTO_INCREMENT=74 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_sales_payments`
--


--
-- Table structure for table `ospos_sales_suspended`
--

DROP TABLE IF EXISTS `ospos_sales_suspended`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_sales_suspended` (
  `sale_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `customer_id` int(10) DEFAULT NULL,
  `employee_id` int(10) NOT NULL DEFAULT 0,
  `comment` text NOT NULL,
  `invoice_number` varchar(32) DEFAULT NULL,
  `sale_id` int(10) NOT NULL AUTO_INCREMENT,
  `lock` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`sale_id`),
  KEY `customer_id` (`customer_id`),
  KEY `employee_id` (`employee_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_sales_suspended`
--

--
-- Table structure for table `ospos_sales_suspended_items`
--

DROP TABLE IF EXISTS `ospos_sales_suspended_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_sales_suspended_items` (
  `sale_id` int(10) NOT NULL DEFAULT 0,
  `item_id` int(10) NOT NULL DEFAULT 0,
  `description` varchar(30) DEFAULT NULL,
  `serialnumber` varchar(30) DEFAULT NULL,
  `line` int(3) NOT NULL DEFAULT 0,
  `quantity_purchased` decimal(15,3) NOT NULL DEFAULT 0.000,
  `item_cost_price` decimal(15,2) NOT NULL,
  `item_unit_price` decimal(15,2) NOT NULL,
  `discount_percent` decimal(15,2) NOT NULL DEFAULT 0.00,
  `item_location` int(11) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`),
  KEY `sale_id` (`sale_id`),
  KEY `item_id` (`item_id`),
  KEY `ospos_sales_suspended_items_ibfk_3` (`item_location`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_sales_suspended_items`
--
--
-- Table structure for table `ospos_sales_suspended_items_taxes`
--

DROP TABLE IF EXISTS `ospos_sales_suspended_items_taxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_sales_suspended_items_taxes` (
  `sale_id` int(10) NOT NULL,
  `item_id` int(10) NOT NULL,
  `line` int(3) NOT NULL DEFAULT 0,
  `name` varchar(255) NOT NULL,
  `percent` decimal(15,3) NOT NULL,
  PRIMARY KEY (`sale_id`,`item_id`,`line`,`name`,`percent`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_sales_suspended_items_taxes`
--
--
-- Table structure for table `ospos_sales_suspended_payments`
--

DROP TABLE IF EXISTS `ospos_sales_suspended_payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_sales_suspended_payments` (
  `sale_id` int(10) NOT NULL,
  `payment_type` varchar(40) NOT NULL,
  `payment_amount` decimal(15,2) NOT NULL,
  PRIMARY KEY (`sale_id`,`payment_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_sales_suspended_payments`
--


--
-- Table structure for table `ospos_sessions`
--

DROP TABLE IF EXISTS `ospos_sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_sessions` (
  `id` varchar(40) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` longblob NOT NULL,
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_sessions`
--

--
-- Table structure for table `ospos_short_survey`
--

DROP TABLE IF EXISTS `ospos_short_survey`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_short_survey` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `customer_id` int(10) DEFAULT NULL,
  `sale_id` int(10) DEFAULT NULL,
  `sale_uuid` varchar(255) DEFAULT NULL,
  `nvbh_id` int(10) NOT NULL DEFAULT 0,
  `kxv_id` int(10) NOT NULL DEFAULT 0,
  `created_date` int(11) NOT NULL DEFAULT 0,
  `q1` int(1) NOT NULL DEFAULT 1,
  `q2` int(1) NOT NULL DEFAULT 1,
  `q3` int(1) NOT NULL DEFAULT 1,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_short_survey`
--

--
-- Table structure for table `ospos_sms_sale`
--

DROP TABLE IF EXISTS `ospos_sms_sale`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_sms_sale` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sale_id` int(11) DEFAULT NULL,
  `is_sms` tinyint(1) DEFAULT 0 COMMENT '0: chưa gửi sms;1 đã gửi sms',
  `name` varchar(250) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `customer_id` int(11) DEFAULT NULL,
  `saled_date` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_sms_sale`
--

--
-- Table structure for table `ospos_stock_locations`
--

DROP TABLE IF EXISTS `ospos_stock_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_stock_locations` (
  `location_id` int(11) NOT NULL AUTO_INCREMENT,
  `location_name` varchar(255) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `location_code` varchar(5) NOT NULL,
  `location_phone` varchar(12) NOT NULL,
  `location_address` varchar(255) NOT NULL,
  `location_owner_name` varchar(255) NOT NULL,
  `location_parent_id` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`location_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_stock_locations`
--
--
-- Table structure for table `ospos_suppliers`
--

DROP TABLE IF EXISTS `ospos_suppliers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_suppliers` (
  `person_id` int(10) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `agency_name` varchar(255) NOT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `deleted` int(1) NOT NULL DEFAULT 0,
  `company_phone` varchar(12) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `company_code` varchar(5) NOT NULL,
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`supplier_id`),
  UNIQUE KEY `account_number` (`account_number`),
  KEY `person_id` (`person_id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_suppliers`
--
--
-- Table structure for table `ospos_test`
--

DROP TABLE IF EXISTS `ospos_test`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_test` (
  `test_id` int(11) NOT NULL AUTO_INCREMENT,
  `employeer_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `code` varchar(12) DEFAULT NULL,
  `right_e` varchar(255) DEFAULT NULL,
  `left_e` varchar(255) DEFAULT NULL,
  `toltal` varchar(255) DEFAULT '''''',
  `lens_type` varchar(255) DEFAULT NULL,
  `contact_lens_type` varchar(255) DEFAULT NULL,
  `note` varchar(255) DEFAULT '''''',
  `test_time` int(11) DEFAULT NULL,
  `type` tinyint(1) DEFAULT NULL,
  `location_id` int(11) DEFAULT NULL,
  `duration` int(1) DEFAULT 6,
  `reminder` tinyint(1) DEFAULT 1 COMMENT 'nhắc tái khám 1; không nhắc 0',
  `expired_date` int(11) DEFAULT 0,
  `test_uuid` varchar(250) NOT NULL DEFAULT uuid(),
  PRIMARY KEY (`test_id`)
) ENGINE=InnoDB AUTO_INCREMENT=50483 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_test`
--

--
-- Table structure for table `ospos_total`
--

DROP TABLE IF EXISTS `ospos_total`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_total` (
  `total_id` int(10) NOT NULL AUTO_INCREMENT,
  `payment_type` varchar(40) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `payment_id` int(10) NOT NULL,
  `code` varchar(50) DEFAULT NULL,
  `created_time` int(11) DEFAULT NULL,
  `type` tinyint(1) NOT NULL DEFAULT 0 COMMENT '{ 0: Thu; 1: Chi}',
  `creator_personal_id` int(10) DEFAULT NULL,
  `personal_id` int(10) DEFAULT NULL,
  `sale_id` int(10) DEFAULT NULL,
  `kind` tinyint(1) NOT NULL DEFAULT 0 COMMENT '{0: Thanh toan; 1: Dat truoc; 2: return money}',
  `daily_total_id` int(10) NOT NULL,
  `note` varchar(250) NOT NULL DEFAULT '''''',
  PRIMARY KEY (`total_id`),
  KEY `ospos_total_ibfk_1` (`sale_id`),
  KEY `total_id` (`total_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_total`
--


--
-- Table structure for table `ospos_user_roles`
--

DROP TABLE IF EXISTS `ospos_user_roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `ospos_user_roles` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `role_id` int(10) NOT NULL DEFAULT 0,
  `user_id` int(10) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ospos_user_roles`
--

LOCK TABLES `ospos_user_roles` WRITE;
/*!40000 ALTER TABLE `ospos_user_roles` DISABLE KEYS */;
INSERT INTO `ospos_user_roles` VALUES (2,2,7713),(4,4,8811),(6,6,8826),(14,1,1),(26,2,8794),(28,6,8714),(29,3,8877),(32,1,8856),(41,3,8878),(42,6,8878),(43,5,8817);
/*!40000 ALTER TABLE `ospos_user_roles` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-11-14 14:45:36
