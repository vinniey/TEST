/*
SQLyog Trial v11.11 (64 bit)
MySQL - 5.6.12 : Database - sampledb
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`sampledb` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `sampledb`;

/*Table structure for table `adlist` */

DROP TABLE IF EXISTS `adlist`;

CREATE TABLE `adlist` (
  `ad_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ad_name` varchar(50) NOT NULL,
  `ad_desc` text NOT NULL,
  `ad_url` varchar(500) NOT NULL,
  `ad_time` int(11) NOT NULL,
  `ad_tactics` varchar(50) NOT NULL,
  `ad_stat` enum('0','1','2','3','4','5') NOT NULL,
  `ad_add_time` int(11) NOT NULL,
  `ad_last_edit_time` int(11) NOT NULL,
  PRIMARY KEY (`ad_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1107 DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
