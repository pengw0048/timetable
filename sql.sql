/*
SQLyog Ultimate v11.11 (64 bit)
MySQL - 5.6.16 : Database - timetable
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`timetable` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `timetable`;

/*Table structure for table `personinfo` */

DROP TABLE IF EXISTS `personinfo`;

CREATE TABLE `personinfo` (
  `name` varchar(30) DEFAULT NULL,
  `tid` varchar(16) DEFAULT NULL,
  `content` varchar(300) DEFAULT NULL,
  `pass` varchar(40) DEFAULT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `CreateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Table structure for table `tableinfo` */

DROP TABLE IF EXISTS `tableinfo`;

CREATE TABLE `tableinfo` (
  `desc` varchar(100) DEFAULT NULL,
  `pass` varchar(40) DEFAULT NULL,
  `tid` varchar(16) NOT NULL,
  `ip` varchar(20) DEFAULT NULL,
  `content` varchar(400) NOT NULL,
  `CreateTime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
