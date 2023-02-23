/*
SQLyog Community v13.1.7 (64 bit)
MySQL - 10.4.17-MariaDB : Database - palio_lite
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*Table structure for table `user_notification` */

CREATE TABLE `user_notification` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOTIF_ID` varchar(20) NOT NULL,
  `TYPE` varchar(20) DEFAULT NULL COMMENT '1=follow, etc',
  `F_PIN` varchar(10) DEFAULT NULL COMMENT 'receiver f_pin',
  `ENTITY_ID` varchar(64) DEFAULT NULL COMMENT 'correlate with type, if type=follow then entity=follower f_pin, etc',
  `READ_STATUS` tinyint(4) DEFAULT 0 COMMENT '0=unread,1=read',
  `TIME` bigint(20) DEFAULT NULL COMMENT 'millisecond',
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
