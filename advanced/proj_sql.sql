/*
SQLyog Ultimate v12.09 (64 bit)
MySQL - 5.5.53 : Database - db_edj_cbank
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`db_edj_cbank` /*!40100 DEFAULT CHARACTER SET utf8 */;

USE `db_edj_cbank`;

/*Table structure for table `bonuslist` */

DROP TABLE IF EXISTS `bonuslist`;

CREATE TABLE `bonuslist` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `cid` int(11) NOT NULL COMMENT '用户id',
  `type` varchar(20) NOT NULL COMMENT '券种',
  `code` varchar(30) DEFAULT NULL COMMENT '券码',
  `pic` varchar(100) DEFAULT NULL COMMENT '二维码',
  `bindtime` datetime DEFAULT NULL COMMENT '绑定时间',
  `deadline` datetime DEFAULT NULL COMMENT '使用有效期',
  `usetime` datetime DEFAULT NULL COMMENT '使用时间',
  `lasttime` datetime DEFAULT NULL COMMENT '最后绑定时间',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 本月未绑定 1 本月已绑定',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8;

/*Data for the table `bonuslist` */

insert  into `bonuslist`(`id`,`cid`,`type`,`code`,`pic`,`bindtime`,`deadline`,`usetime`,`lasttime`,`status`) values (1,1,'代驾','11','','2018-05-24 11:26:24','2018-06-30 11:26:27','2018-05-24 11:26:32','2018-05-24 11:26:36',0),(2,1,'打蜡','11','','2018-05-24 11:26:36','2018-06-30 11:26:27','2018-05-24 11:26:36','2018-05-24 11:26:36',0),(3,1,'空调清洗','11','','2018-05-24 11:26:36','2018-06-30 11:26:27','0000-00-00 00:00:00','0000-00-00 00:00:00',0),(4,1,'洗车','11','11','2018-05-24 11:26:36','2018-06-30 11:26:27','0000-00-00 00:00:00','0000-00-00 00:00:00',0),(5,1,'洗车',NULL,NULL,'2018-05-24 11:26:36','2018-06-30 11:26:27','0000-00-00 00:00:00','0000-00-00 00:00:00',0),(6,1,'洗车',NULL,NULL,'2018-05-24 11:26:36','2018-06-30 11:26:27','0000-00-00 00:00:00','0000-00-00 00:00:00',0),(7,2,'代驾','22',NULL,'2018-05-24 11:26:36','2018-06-30 11:26:27','0000-00-00 00:00:00','0000-00-00 00:00:00',0),(8,2,'打蜡','22',NULL,'2018-05-24 11:26:36','2018-06-30 11:26:27','0000-00-00 00:00:00','0000-00-00 00:00:00',0),(9,2,'空调清洗','22',NULL,'2018-05-24 11:26:36','2018-06-30 11:26:27','0000-00-00 00:00:00','0000-00-00 00:00:00',0),(10,2,'洗车','22','22','2018-05-24 11:26:36','2018-06-30 11:26:27','0000-00-00 00:00:00','0000-00-00 00:00:00',0),(11,2,'洗车',NULL,NULL,'0000-00-00 00:00:00','2018-06-30 11:26:27','0000-00-00 00:00:00','0000-00-00 00:00:00',0),(12,2,'洗车',NULL,NULL,'0000-00-00 00:00:00','2018-06-30 11:26:27','0000-00-00 00:00:00','0000-00-00 00:00:00',0),(13,2,'洗车',NULL,NULL,'0000-00-00 00:00:00','2018-06-30 11:26:27','0000-00-00 00:00:00','0000-00-00 00:00:00',0);

/*Table structure for table `customers` */

DROP TABLE IF EXISTS `customers`;

CREATE TABLE `customers` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `phone` varchar(11) NOT NULL COMMENT '手机号',
  `package` varchar(30) DEFAULT NULL COMMENT '权益包',
  `creater` varchar(32) NOT NULL COMMENT '录入管理员',
  `createtime` datetime NOT NULL COMMENT '录入时间',
  `bak` varchar(200) DEFAULT NULL COMMENT '备注',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0 已绑定 1 未绑定',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8;

/*Data for the table `customers` */

insert  into `customers`(`id`,`phone`,`package`,`creater`,`createtime`,`bak`,`status`) values (1,'13333333333','A','admin','2018-05-23 14:43:33','bak',0),(2,'12222222222','B','admin','2018-05-15 14:43:33','',0);

/*Table structure for table `user` */

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) NOT NULL COMMENT '用户名',
  `pwd` varchar(64) NOT NULL COMMENT '密码',
  `level` tinyint(1) NOT NULL DEFAULT '0' COMMENT '级别',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0 正常 1 禁用',
  `createtime` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

/*Data for the table `user` */

insert  into `user`(`id`,`name`,`pwd`,`level`,`status`,`createtime`) values (1,'admin','admin',0,0,NULL),(2,'user','user',0,0,NULL);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
