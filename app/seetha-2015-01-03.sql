/*
SQLyog Ultimate v9.63 
MySQL - 5.5.16 : Database - seetha
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`seetha` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `seetha`;

/*Table structure for table `t_internal_transfer_det` */

DROP TABLE IF EXISTS `t_internal_transfer_det`;

CREATE TABLE `t_internal_transfer_det` (
  `auto_no` int(11) NOT NULL AUTO_INCREMENT,
  `cl` varchar(2) NOT NULL,
  `bc` varchar(3) NOT NULL,
  `nno` int(11) NOT NULL,
  `item_code` varchar(30) NOT NULL,
  `batch_no` varchar(10) NOT NULL,
  `item_cost` decimal(10,0) NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`cl`,`bc`,`nno`,`item_code`),
  UNIQUE KEY `auto_no` (`auto_no`),
  KEY `item_code` (`item_code`)
) ENGINE=InnoDB AUTO_INCREMENT=212 DEFAULT CHARSET=latin1;

/*Data for the table `t_internal_transfer_det` */

/*Table structure for table `t_internal_transfer_order_det` */

DROP TABLE IF EXISTS `t_internal_transfer_order_det`;

CREATE TABLE `t_internal_transfer_order_det` (
  `auto_no` int(11) NOT NULL AUTO_INCREMENT,
  `cl` varchar(2) NOT NULL,
  `bc` varchar(3) NOT NULL,
  `nno` decimal(10,0) NOT NULL,
  `item_code` varchar(30) NOT NULL,
  `item_cost` decimal(10,0) NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`cl`,`bc`,`nno`,`item_code`),
  UNIQUE KEY `auto_no` (`auto_no`),
  KEY `item_code` (`item_code`),
  CONSTRAINT `t_internal_transfer_order_det_ibfk_2` FOREIGN KEY (`item_code`) REFERENCES `m_item` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=91 DEFAULT CHARSET=latin1;

/*Data for the table `t_internal_transfer_order_det` */

/*Table structure for table `t_internal_transfer_order_sum` */

DROP TABLE IF EXISTS `t_internal_transfer_order_sum`;

CREATE TABLE `t_internal_transfer_order_sum` (
  `auto_no` int(11) NOT NULL AUTO_INCREMENT,
  `cl` varchar(2) NOT NULL,
  `bc` varchar(3) NOT NULL,
  `nno` decimal(10,0) NOT NULL,
  `ddate` date NOT NULL,
  `ref_no` varchar(10) NOT NULL,
  `to_bc` varchar(3) NOT NULL,
  `note` varchar(500) NOT NULL,
  `oc` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_approved` tinyint(4) NOT NULL DEFAULT '0',
  `is_cancel` tinyint(4) NOT NULL DEFAULT '0',
  `status` varchar(5) NOT NULL DEFAULT '1' COMMENT '1=pending,2=issued,3=rejected',
  PRIMARY KEY (`cl`,`bc`,`nno`),
  KEY `auto_no` (`auto_no`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=latin1;

/*Data for the table `t_internal_transfer_order_sum` */

/*Table structure for table `t_internal_transfer_sum` */

DROP TABLE IF EXISTS `t_internal_transfer_sum`;

CREATE TABLE `t_internal_transfer_sum` (
  `auto_no` int(11) NOT NULL AUTO_INCREMENT,
  `cl` varchar(2) NOT NULL,
  `bc` varchar(3) NOT NULL,
  `nno` int(11) NOT NULL,
  `ddate` date NOT NULL,
  `store` varchar(15) NOT NULL,
  `ref_no` varchar(10) NOT NULL,
  `order_no` varchar(10) NOT NULL,
  `to_bc` varchar(3) NOT NULL,
  `note` varchar(500) NOT NULL,
  `oc` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_approved` tinyint(4) NOT NULL DEFAULT '0',
  `is_cancel` tinyint(4) NOT NULL DEFAULT '0',
  `status` varchar(2) NOT NULL DEFAULT 'P',
  `trans_code` varchar(10) NOT NULL,
  `trans_no` varchar(10) NOT NULL,
  `ref_trans_code` varchar(10) NOT NULL DEFAULT '0',
  `ref_trans_no` varchar(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cl`,`bc`,`nno`),
  KEY `auto_no` (`auto_no`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=latin1;

/*Data for the table `t_internal_transfer_sum` */

/*Table structure for table `t_po_det` */

DROP TABLE IF EXISTS `t_po_det`;

CREATE TABLE `t_po_det` (
  `cl` varchar(2) NOT NULL,
  `bc` varchar(3) NOT NULL,
  `nno` decimal(10,0) NOT NULL,
  `sub_cl` varchar(2) NOT NULL,
  `sub_bc` varchar(3) NOT NULL,
  `sub_nno` decimal(10,0) NOT NULL,
  `qty` decimal(10,0) NOT NULL,
  `current_qty` decimal(10,0) NOT NULL DEFAULT '0',
  `cost` decimal(12,2) NOT NULL DEFAULT '0.00',
  `amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `item` varchar(15) NOT NULL,
  KEY `FK_t_po_det` (`cl`,`bc`,`nno`),
  KEY `Item` (`item`),
  CONSTRAINT `FK_t_po_det` FOREIGN KEY (`cl`, `bc`, `nno`) REFERENCES `t_po_sum` (`cl`, `bc`, `nno`) ON UPDATE CASCADE,
  CONSTRAINT `t_po_det_ibfk_1` FOREIGN KEY (`item`) REFERENCES `m_item` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_po_det` */

/*Table structure for table `t_po_sum` */

DROP TABLE IF EXISTS `t_po_sum`;

CREATE TABLE `t_po_sum` (
  `cl` varchar(2) NOT NULL,
  `bc` varchar(3) NOT NULL,
  `nno` decimal(10,0) NOT NULL,
  `approve_no` decimal(10,0) NOT NULL,
  `ddate` date NOT NULL,
  `deliver_date` date NOT NULL,
  `ref_no` varchar(20) NOT NULL DEFAULT '',
  `ship_to_bc` varchar(3) NOT NULL DEFAULT '',
  `supplier` varchar(10) NOT NULL DEFAULT '',
  `comment` varchar(100) NOT NULL DEFAULT '',
  `total_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `oc` varchar(20) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `approved` tinyint(4) NOT NULL DEFAULT '0',
  `approved_by` varchar(50) NOT NULL DEFAULT '',
  `approved_date` datetime NOT NULL,
  `is_cancel` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cl`,`bc`,`nno`),
  KEY `supplier` (`supplier`),
  CONSTRAINT `t_po_sum_ibfk_1` FOREIGN KEY (`cl`, `bc`) REFERENCES `m_branch` (`cl`, `bc`) ON UPDATE CASCADE,
  CONSTRAINT `t_po_sum_ibfk_2` FOREIGN KEY (`supplier`) REFERENCES `m_supplier` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_po_sum` */

/*Table structure for table `t_req_approve_additional_det` */

DROP TABLE IF EXISTS `t_req_approve_additional_det`;

CREATE TABLE `t_req_approve_additional_det` (
  `cl` varchar(2) NOT NULL,
  `bc` varchar(3) NOT NULL,
  `nno` decimal(10,0) NOT NULL,
  `sub_cl` varchar(2) NOT NULL,
  `sub_bc` varchar(3) NOT NULL,
  `sub_nno` decimal(10,0) NOT NULL,
  `item` varchar(15) NOT NULL,
  `roq` decimal(10,0) NOT NULL,
  `rol` decimal(10,0) NOT NULL,
  `current_qty` decimal(10,0) NOT NULL,
  `request` decimal(10,0) NOT NULL,
  `approve` decimal(10,0) NOT NULL,
  KEY `cl` (`cl`,`bc`,`nno`),
  KEY `cl_2` (`cl`,`bc`,`sub_nno`),
  KEY `sub_cl` (`sub_cl`,`sub_bc`,`sub_nno`),
  KEY `item` (`item`),
  CONSTRAINT `t_req_approve_additional_det_ibfk_1` FOREIGN KEY (`cl`, `bc`, `nno`) REFERENCES `t_req_approve_sum` (`cl`, `bc`, `nno`) ON UPDATE CASCADE,
  CONSTRAINT `t_req_approve_additional_det_ibfk_2` FOREIGN KEY (`sub_cl`, `sub_bc`, `sub_nno`) REFERENCES `t_req_det` (`cl`, `bc`, `nno`) ON UPDATE CASCADE,
  CONSTRAINT `t_req_approve_additional_det_ibfk_3` FOREIGN KEY (`item`) REFERENCES `m_item` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_req_approve_additional_det` */

insert  into `t_req_approve_additional_det`(`cl`,`bc`,`nno`,`sub_cl`,`sub_bc`,`sub_nno`,`item`,`roq`,`rol`,`current_qty`,`request`,`approve`) values ('SH','AK','1','SH','AK','1','BT001','0','0','0','40','50');

/*Table structure for table `t_req_approve_det` */

DROP TABLE IF EXISTS `t_req_approve_det`;

CREATE TABLE `t_req_approve_det` (
  `cl` varchar(2) NOT NULL,
  `bc` varchar(3) NOT NULL,
  `nno` decimal(10,0) NOT NULL,
  `code` varchar(15) NOT NULL,
  `request` decimal(10,0) NOT NULL,
  `roq` decimal(10,0) NOT NULL,
  `current_qty` decimal(10,0) NOT NULL,
  `approve_qty` decimal(10,0) NOT NULL,
  `cost` decimal(12,2) NOT NULL,
  `last_price` decimal(12,2) NOT NULL,
  `max_price` decimal(12,2) NOT NULL,
  `last_pre` decimal(12,2) NOT NULL,
  `max_pre` decimal(12,2) NOT NULL,
  KEY `cl` (`cl`,`bc`,`nno`),
  KEY `code` (`code`),
  CONSTRAINT `t_req_approve_det_ibfk_1` FOREIGN KEY (`cl`, `bc`, `nno`) REFERENCES `t_req_approve_sum` (`cl`, `bc`, `nno`) ON UPDATE CASCADE,
  CONSTRAINT `t_req_approve_det_ibfk_2` FOREIGN KEY (`code`) REFERENCES `m_item` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_req_approve_det` */

insert  into `t_req_approve_det`(`cl`,`bc`,`nno`,`code`,`request`,`roq`,`current_qty`,`approve_qty`,`cost`,`last_price`,`max_price`,`last_pre`,`max_pre`) values ('SH','AK','1','BT001','40','11','0','50','0.00','4000.00','4500.00','6500.00','11.11');

/*Table structure for table `t_req_approve_sum` */

DROP TABLE IF EXISTS `t_req_approve_sum`;

CREATE TABLE `t_req_approve_sum` (
  `cl` varchar(2) NOT NULL,
  `bc` varchar(3) NOT NULL,
  `nno` decimal(10,0) NOT NULL,
  `ddate` date NOT NULL,
  `supplier` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `oc` varchar(10) NOT NULL,
  `is_cancel` tinyint(4) NOT NULL DEFAULT '0',
  `is_level_3_approved` tinyint(4) NOT NULL DEFAULT '0',
  `is_ordered` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cl`,`bc`,`nno`),
  KEY `supplier` (`supplier`),
  CONSTRAINT `t_req_approve_sum_ibfk_1` FOREIGN KEY (`cl`, `bc`) REFERENCES `m_branch` (`cl`, `bc`) ON UPDATE CASCADE,
  CONSTRAINT `t_req_approve_sum_ibfk_2` FOREIGN KEY (`supplier`) REFERENCES `m_supplier` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_req_approve_sum` */

insert  into `t_req_approve_sum`(`cl`,`bc`,`nno`,`ddate`,`supplier`,`action_date`,`oc`,`is_cancel`,`is_level_3_approved`,`is_ordered`) values ('SH','AK','1','2015-01-04','2191c','2015-01-04 01:06:05','1',0,1,1);

/*Table structure for table `t_req_det` */

DROP TABLE IF EXISTS `t_req_det`;

CREATE TABLE `t_req_det` (
  `cl` varchar(2) NOT NULL,
  `bc` varchar(3) NOT NULL,
  `nno` decimal(10,0) NOT NULL,
  `item` varchar(15) NOT NULL,
  `cur_qty` decimal(10,0) NOT NULL DEFAULT '0',
  `rol` decimal(10,0) NOT NULL DEFAULT '0',
  `roq` decimal(10,0) NOT NULL DEFAULT '0',
  `week1` decimal(10,0) NOT NULL DEFAULT '0',
  `week2` decimal(10,0) NOT NULL DEFAULT '0',
  `week3` decimal(10,0) NOT NULL DEFAULT '0',
  `week4` decimal(10,0) NOT NULL DEFAULT '0',
  `total_qty` decimal(10,0) NOT NULL DEFAULT '0',
  `level_0_approve_qty` decimal(10,0) NOT NULL COMMENT 'Manager Approval',
  `level_1_approve_qty` decimal(10,0) NOT NULL DEFAULT '0' COMMENT 'Superwiser Approval',
  `level_2_approve_qty` decimal(10,0) DEFAULT NULL COMMENT 'MD/Stores Approval',
  `supplier` varchar(10) NOT NULL DEFAULT '',
  `comment` varchar(100) NOT NULL DEFAULT '',
  `orderd` tinyint(4) NOT NULL DEFAULT '0',
  `orderd_no` decimal(10,0) NOT NULL DEFAULT '0',
  KEY `FK_t_req_det` (`cl`,`bc`,`nno`),
  KEY `Item` (`item`),
  KEY `supplier` (`supplier`),
  CONSTRAINT `FK_t_req_det` FOREIGN KEY (`cl`, `bc`, `nno`) REFERENCES `t_req_sum` (`cl`, `bc`, `nno`) ON UPDATE CASCADE,
  CONSTRAINT `t_req_det_ibfk_1` FOREIGN KEY (`item`) REFERENCES `m_item` (`code`) ON UPDATE CASCADE,
  CONSTRAINT `t_req_det_ibfk_2` FOREIGN KEY (`supplier`) REFERENCES `m_supplier` (`code`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_req_det` */

insert  into `t_req_det`(`cl`,`bc`,`nno`,`item`,`cur_qty`,`rol`,`roq`,`week1`,`week2`,`week3`,`week4`,`total_qty`,`level_0_approve_qty`,`level_1_approve_qty`,`level_2_approve_qty`,`supplier`,`comment`,`orderd`,`orderd_no`) values ('SH','AK','1','BT001','0','0','0','25','5','5','5','40','40','40','50','2191c','abc',0,'0'),('SH','AK','1','0220800400003','0','0','0','50','5','0','5','60','60','60',NULL,'2192C','gtr',0,'0');

/*Table structure for table `t_req_sum` */

DROP TABLE IF EXISTS `t_req_sum`;

CREATE TABLE `t_req_sum` (
  `cl` varchar(2) NOT NULL,
  `bc` varchar(3) NOT NULL,
  `nno` decimal(10,0) NOT NULL,
  `ddate` date NOT NULL,
  `ref_no` varchar(20) NOT NULL DEFAULT '',
  `supplier` varchar(10) NOT NULL DEFAULT '',
  `comment` varchar(100) NOT NULL DEFAULT '',
  `oc` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `is_level_1_approved` tinyint(4) NOT NULL DEFAULT '0',
  `is_level_2_approved` tinyint(4) NOT NULL DEFAULT '0',
  `approved_date` datetime NOT NULL,
  `orderd` tinyint(4) NOT NULL DEFAULT '0',
  `orderd_no` decimal(10,0) NOT NULL DEFAULT '0',
  `is_cancel` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`cl`,`bc`,`nno`),
  KEY `FK_t_reg_sum` (`supplier`),
  CONSTRAINT `t_req_sum_ibfk_1` FOREIGN KEY (`cl`, `bc`) REFERENCES `m_branch` (`cl`, `bc`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_req_sum` */

insert  into `t_req_sum`(`cl`,`bc`,`nno`,`ddate`,`ref_no`,`supplier`,`comment`,`oc`,`action_date`,`is_level_1_approved`,`is_level_2_approved`,`approved_date`,`orderd`,`orderd_no`,`is_cancel`) values ('SH','AK','1','2015-01-04','','','','1','2015-01-04 01:05:49',1,0,'0000-00-00 00:00:00',0,'0',0);

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
