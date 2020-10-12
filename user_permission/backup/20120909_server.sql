/*
SQLyog Ultimate v8.55 
MySQL - 5.1.65-community-log : Database - softmast_janasiri_distribution
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
/*CREATE DATABASE !32312 IF NOT EXISTS`softmast_janasiri_distribution` /*!40100 DEFAULT CHARACTER SET latin1 */;*/

/*USE `softmast_janasiri_distribution`;*/

/*Table structure for table `a_com_reg` */

DROP TABLE IF EXISTS `a_com_reg`;

CREATE TABLE `a_com_reg` (
  `id` int(11) DEFAULT NULL,
  `com_id` varchar(40) NOT NULL,
  `current_id` varchar(40) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '0',
  `req_on` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `req_by` varchar(10) DEFAULT NULL,
  `app_on` timestamp NULL DEFAULT NULL,
  `app_by` varchar(10) DEFAULT NULL,
  `des` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`com_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `a_com_reg` */

/*Table structure for table `a_users` */

DROP TABLE IF EXISTS `a_users`;

CREATE TABLE `a_users` (
  `cCode` varchar(10) NOT NULL DEFAULT '',
  `loginName` varchar(50) NOT NULL,
  `discription` varchar(50) DEFAULT NULL,
  `userPassword` varchar(50) DEFAULT NULL,
  `isAdmin` tinyint(1) unsigned DEFAULT NULL,
  `bc` varchar(5) DEFAULT NULL,
  PRIMARY KEY (`cCode`,`loginName`),
  KEY `bc` (`bc`),
  CONSTRAINT `a_users_ibfk_1` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `a_users` */

insert  into `a_users`(`cCode`,`loginName`,`discription`,`userPassword`,`isAdmin`,`bc`) values ('1','developer','Soft Master Developer','e8912bf8c7ef369038190ef88cefbce4',1,'001'),('2','user','user','202cb962ac59075b964b07152d234b70',NULL,'001');

/*Table structure for table `m_agency` */

DROP TABLE IF EXISTS `m_agency`;

CREATE TABLE `m_agency` (
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address01` varchar(255) NOT NULL,
  `address02` varchar(255) NOT NULL,
  `address03` varchar(255) NOT NULL,
  `phone01` varchar(10) NOT NULL,
  `phone02` varchar(10) NOT NULL,
  `phone03` varchar(10) NOT NULL,
  `agent` varchar(10) NOT NULL,
  `area` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`),
  KEY `agent_code` (`agent`),
  KEY `sales_man_code` (`area`),
  CONSTRAINT `m_agency_ibfk_1` FOREIGN KEY (`agent`) REFERENCES `m_agent` (`code`),
  CONSTRAINT `m_agency_ibfk_2` FOREIGN KEY (`area`) REFERENCES `m_area` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_agency` */

/*Table structure for table `m_agent` */

DROP TABLE IF EXISTS `m_agent`;

CREATE TABLE `m_agent` (
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address01` varchar(255) NOT NULL,
  `address02` varchar(255) NOT NULL,
  `address03` varchar(255) NOT NULL,
  `phone01` varchar(10) NOT NULL,
  `phone02` varchar(10) NOT NULL,
  `phone03` varchar(10) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `credit_limit` decimal(10,2) NOT NULL,
  `credit_days` int(11) NOT NULL,
  `br_no` varchar(10) NOT NULL,
  `bank_guarantee_code` varchar(20) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_agent` */

/*Table structure for table `m_area` */

DROP TABLE IF EXISTS `m_area`;

CREATE TABLE `m_area` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `region` varchar(10) NOT NULL,
  `sales_ref` varchar(10) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`),
  KEY `region` (`region`),
  KEY `sales_ref` (`sales_ref`),
  CONSTRAINT `m_area_ibfk_1` FOREIGN KEY (`region`) REFERENCES `m_sub_regon` (`code`),
  CONSTRAINT `m_area_ibfk_2` FOREIGN KEY (`sales_ref`) REFERENCES `m_sales_ref` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_area` */

insert  into `m_area`(`code`,`description`,`region`,`sales_ref`,`action_date`) values ('KDY01','KANDY TOWN','KDY','PG','2012-08-29 04:02:49');

/*Table structure for table `m_bank_branch` */

DROP TABLE IF EXISTS `m_bank_branch`;

CREATE TABLE `m_bank_branch` (
  `bank_code` varchar(10) DEFAULT NULL,
  `code` varchar(10) NOT NULL DEFAULT '',
  `description` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`code`),
  KEY `bank_code` (`bank_code`),
  CONSTRAINT `m_bank_branch_ibfk_1` FOREIGN KEY (`bank_code`) REFERENCES `m_banks` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_bank_branch` */

insert  into `m_bank_branch`(`bank_code`,`code`,`description`) values ('7010','7010-001','Bank Of Ceylon - City Office'),('7010','7010-002','Bank Of Ceylon - Kandy'),('7010','7010-003','Bank Of Ceylon - Galle'),('7010','7010-004','Bank Of Ceylon - Pettah'),('7010','7010-005','Bank Of Ceylon - Jaffna'),('7010','7010-006','Bank Of Ceylon - Trincomalee'),('7010','7010-007','Bank Of Ceylon - Panadaura'),('7010','7010-009','Bank Of Ceylon - Kurunegala'),('7010','7010-010','Bank Of Ceylon - Savings Department'),('7010','7010-011','Bank Of Ceylon - Badulla'),('7010','7010-012','Bank Of Ceylon - Batticalioa'),('7010','7010-015','Bank Of Ceylon - Central Office'),('7010','7010-016','Bank Of Ceylon - Kalutara'),('7010','7010-018','Bank Of Ceylon - Negambo'),('7010','7010-020','Bank Of Ceylon - Chilaw'),('7010','7010-021','Bank Of Ceylon - Ampara'),('7010','7010-022','Bank Of Ceylon - Anuradhapura'),('7010','7010-023','Bank Of Ceylon - Wellawatte'),('7010','7010-024','Bank Of Ceylon - Matara'),('7010','7010-025','Bank Of Ceylon - Prince Street'),('7010','7010-026','Bank Of Ceylon - Main Street'),('7010','7010-027','Bank Of Ceylon - Kegalle'),('7010','7010-028','Bank Of Ceylon - Point Pedro'),('7010','7010-029','Bank Of Ceylon - Nuwara Eliya'),('7010','7010-030','Bank Of Ceylon - Katubedde'),('7010','7010-031','Bank Of Ceylon - Ratnapura'),('7010','7010-032','Bank Of Ceylon - Hulfsdrop'),('7010','7010-034','Bank Of Ceylon - Kollupitiya'),('7010','7010-035','Bank Of Ceylon - Haputale'),('7010','7010-037','Bank Of Ceylon - Bambalapitiya'),('7010','7010-038','Bank Of Ceylon - Borella'),('7010','7010-039','Bank Of Ceylon - Ja-Ela'),('7010','7010-040','Bank Of Ceylon - Hatton'),('7010','7010-041','Bank Of Ceylon - Maradana'),('7010','7010-042','Bank Of Ceylon - Peliyagoda'),('7010','7010-043','Bank Of Ceylon - Union Place'),('7010','7010-044','Bank Of Ceylon - Vavuniya'),('7010','7010-045','Bank Of Ceylon - Gampaha'),('7010','7010-046','Bank Of Ceylon - Mannar'),('7010','7010-047','Bank Of Ceylon - Ambalangoda'),('7010','7010-048','Bank Of Ceylon - Puttalam'),('7010','7010-049','Bank Of Ceylon - Nugegoda'),('7010','7010-050','Bank Of Ceylon - Nattandiya'),('7010','7010-051','Bank Of Ceylon - Dehiwela'),('7010','7010-052','Bank Of Ceylon - Kuliyapitiya'),('7010','7010-053','Bank Of Ceylon - Chunnakam'),('7010','7010-054','Bank Of Ceylon - Horana'),('7010','7010-055','Bank Of Ceylon - Maharagama'),('7010','7010-056','Bank Of Ceylon - Tangalle'),('7010','7010-057','Bank Of Ceylon - Eheliyagoda'),('7010','7010-058','Bank Of Ceylon - Beruwela'),('7010','7010-059','Bank Of Ceylon - Kadawatha'),('7010','7010-060','Bank Of Ceylon - Fifth City'),('7010','7010-061','Bank Of Ceylon - Idama - Moratuwa'),('7010','7010-063','Bank Of Ceylon - Kayts'),('7010','7010-068','Bank Of Ceylon - Matale'),('7010','7010-082','Bank Of Ceylon - Moneragala'),('7010','7010-083','Bank Of Ceylon - Polonnaruwa, New Town'),('7010','7010-085','Bank Of Ceylon - Hambantota'),('7010','7010-087','Bank Of Ceylon - International Division'),('7010','7010-088','Bank Of Ceylon - Mirigama'),('7010','7010-089','Bank Of Ceylon - Galle Bazzar'),('7010','7010-092','Bank Of Ceylon - Naula'),('7010','7010-093','Bank Of Ceylon - Kilinochchi'),('7010','7010-097','Bank Of Ceylon - Rajanganaya'),('7010','7010-098','Bank Of Ceylon - New Town Anuradhapura'),('7010','7010-099','Bank Of Ceylon - Primary Dealer Unit'),('7010','7010-100','Bank Of Ceylon - Oruwala'),('7010','7010-101','Bank Of Ceylon - Galaha'),('7010','7010-102','Bank Of Ceylon - Bentota'),('7010','7010-104','Bank Of Ceylon - Welpalla'),('7010','7010-118','Bank Of Ceylon - Mutur'),('7010','7010-122','Bank Of Ceylon - Galenbindunuwewa'),('7010','7010-127','Bank Of Ceylon - Padavi Parakramapura'),('7010','7010-135','Bank Of Ceylon - Imaduwa'),('7010','7010-139','Bank Of Ceylon - Weeraketiya'),('7010','7010-144','Bank Of Ceylon - Yatawatte'),('7010','7010-146','Bank Of Ceylon -'),('7010','7010-152','Bank Of Ceylon - Pemaduwa'),('7010','7010-157','Bank Of Ceylon - Tirappane'),('7010','7010-162','Bank Of Ceylon - Medawachchiya'),('7010','7010-167','Bank Of Ceylon - Rikillagaskada'),('7010','7010-172','Bank Of Ceylon - Kobeigane'),('7010','7010-183','Bank Of Ceylon - Sewagama'),('7010','7010-217','Bank Of Ceylon - Horowpothana'),('7010','7010-236','Bank Of Ceylon - Ipalogama'),('7010','7010-238','Bank Of Ceylon - Medagama'),('7010','7010-250','Bank Of Ceylon - Tawalama'),('7010','7010-257','Bank Of Ceylon - Mawathagama'),('7010','7010-260','Bank Of Ceylon - Diyatalawa'),('7010','7010-273','Bank Of Ceylon - Digana'),('7010','7010-281','Bank Of Ceylon - Manipay'),('7010','7010-293','Bank Of Ceylon - Dodangoda'),('7010','7010-298','Bank Of Ceylon - Urubokka'),('7010','7010-318','Bank Of Ceylon - Potuvil'),('7010','7010-320','Bank Of Ceylon - Ballakatuwa'),('7010','7010-322','Bank Of Ceylon - Tanamalwila'),('7010','7010-325','Bank Of Ceylon - Kuruwita'),('7010','7010-335','Bank Of Ceylon - Mihintale'),('7010','7010-337','Bank Of Ceylon - Pussellawa'),('7010','7010-340','Bank Of Ceylon - Wattegama'),('7010','7010-342','Bank Of Ceylon - Pambahinna'),('7010','7010-343','Bank Of Ceylon - Uva Paranagama'),('7010','7010-348','Bank Of Ceylon - Padiyatalawa'),('7010','7010-365','Bank Of Ceylon - Hasalaka'),('7010','7010-379','Bank Of Ceylon - Wariyapola'),('7010','7010-384','Bank Of Ceylon - Karametiya'),('7010','7010-401','Bank Of Ceylon - Ayagama'),('7010','7010-416','Bank Of Ceylon - Siyambalanduwa'),('7010','7010-421','Bank Of Ceylon - Seeduwa'),('7010','7010-425','Bank Of Ceylon - Pundaluoya'),('7010','7010-432','Bank Of Ceylon - Galewela'),('7010','7010-433','Bank Of Ceylon - Divulapitiya'),('7010','7010-434','Bank Of Ceylon - Wellawaya'),('7010','7010-440','Bank Of Ceylon - Sammanthurai'),('7010','7010-453','Bank Of Ceylon - Torrington Square'),('7010','7010-463','Bank Of Ceylon - Haldummulla'),('7010','7010-476','Bank Of Ceylon - Ettampitiya'),('7010','7010-477','Bank Of Ceylon - Yatiyantota'),('7010','7010-492','Bank Of Ceylon - Padiyapelella'),('7010','7010-494','Bank Of Ceylon - Andiambalama'),('7010','7010-497','Bank Of Ceylon - Dankotuwa'),('7010','7010-498','Bank Of Ceylon - Alawwa'),('7010','7010-500','Bank Of Ceylon - Jaffna Second Branch'),('7010','7010-501','Bank Of Ceylon - Chavakachcheri'),('7010','7010-502','Bank Of Ceylon - Kaduruwela'),('7010','7010-503','Bank Of Ceylon - Passara'),('7010','7010-504','Bank Of Ceylon - Devinuwara'),('7010','7010-505','Bank Of Ceylon - Wattala'),('7010','7010-506','Bank Of Ceylon - Maskeliya'),('7010','7010-507','Bank Of Ceylon - Kahawatte'),('7010','7010-508','Bank Of Ceylon - Wennappuwa'),('7010','7010-509','Bank Of Ceylon - Hingurana'),('7010','7010-510','Bank Of Ceylon - Kalmunai'),('7010','7010-511','Bank Of Ceylon - Mullaitivu'),('7010','7010-512','Bank Of Ceylon - Thimbirigasyaya'),('7010','7010-513','Bank Of Ceylon - Kurunegala Bazzar'),('7010','7010-514','Bank Of Ceylon - Galnewa'),('7010','7010-515','Bank Of Ceylon - Bandarawela'),('7010','7010-517','Bank Of Ceylon - Walasmulla'),('7010','7010-518','Bank Of Ceylon - Middeniya'),('7010','7010-521','Bank Of Ceylon - Hyde Park'),('7010','7010-522','Bank Of Ceylon - Batapola'),('7010','7010-524','Bank Of Ceylon - Geli Oya'),('7010','7010-525','Bank Of Ceylon - Baddegama'),('7010','7010-526','Bank Of Ceylon - Polgahawela'),('7010','7010-527','Bank Of Ceylon - Welisara'),('7010','7010-528','Bank Of Ceylon - Deniyaya'),('7010','7010-529','Bank Of Ceylon - Kamburupitiya'),('7010','7010-530','Bank Of Ceylon - Avissawella'),('7010','7010-531','Bank Of Ceylon - Talawakelle'),('7010','7010-532','Bank Of Ceylon - Ridigama'),('7010','7010-534','Bank Of Ceylon - Narammala'),('7010','7010-535','Bank Of Ceylon - Embilipitiya'),('7010','7010-536','Bank Of Ceylon - Kegalle Bazzar'),('7010','7010-537','Bank Of Ceylon - Ambalantota'),('7010','7010-538','Bank Of Ceylon - Tissamaharama'),('7010','7010-539','Bank Of Ceylon - Beliatta'),('7010','7010-540','Bank Of Ceylon - Badalkumbura'),('7010','7010-542','Bank Of Ceylon - Mahiyangana'),('7010','7010-543','Bank Of Ceylon - Kiribathgoda'),('7010','7010-544','Bank Of Ceylon - Madampe'),('7010','7010-545','Bank Of Ceylon - Minuwangoda'),('7010','7010-546','Bank Of Ceylon - Pannala'),('7010','7010-547','Bank Of Ceylon - Nikaweratiya'),('7010','7010-548','Bank Of Ceylon - Anamaduwa'),('7010','7010-549','Bank Of Ceylon - Galgamuwa'),('7010','7010-550','Bank Of Ceylon - Weligama'),('7010','7010-551','Bank Of Ceylon - Anuradhapura Bazaar'),('7010','7010-553','Bank Of Ceylon - Giriulla'),('7010','7010-554','Bank Of Ceylon - Bingiriya'),('7010','7010-555','Bank Of Ceylon - Melsiripura'),('7010','7010-556','Bank Of Ceylon - Matugama'),('7010','7010-558','Bank Of Ceylon - Waikkal'),('7010','7010-559','Bank Of Ceylon - Mawanella'),('7010','7010-560','Bank Of Ceylon - Buttala'),('7010','7010-561','Bank Of Ceylon - Dematagoda'),('7010','7010-562','Bank Of Ceylon - Warakapola'),('7010','7010-563','Bank Of Ceylon - Dharga Town'),('7010','7010-564','Bank Of Ceylon - Maho'),('7010','7010-565','Bank Of Ceylon - Madurankuliya'),('7010','7010-566','Bank Of Ceylon - Aranayake'),('7010','7010-568','Bank Of Ceylon - Homagama'),('7010','7010-569','Bank Of Ceylon - Hiripitiya'),('7010','7010-570','Bank Of Ceylon - Hettipola'),('7010','7010-571','Bank Of Ceylon - Kirindiwela'),('7010','7010-572','Bank Of Ceylon - Negambo Bazzar'),('7010','7010-573','Bank Of Ceylon - Central Bus Stand'),('7010','7010-574','Bank Of Ceylon - Mankulam'),('7010','7010-575','Bank Of Ceylon - Gampola'),('7010','7010-576','Bank Of Ceylon - Dambulla'),('7010','7010-577','Bank Of Ceylon - Lunugala'),('7010','7010-578','Bank Of Ceylon - Yakkalamulla'),('7010','7010-579','Bank Of Ceylon - Bibila'),('7010','7010-580','Bank Of Ceylon - Dummalasuriya'),('7010','7010-581','Bank Of Ceylon - Madawala'),('7010','7010-582','Bank Of Ceylon - Rambukkana'),('7010','7010-583','Bank Of Ceylon - Pelmadulla'),('7010','7010-584','Bank Of Ceylon - Wadduwa'),('7010','7010-585','Bank Of Ceylon - Ruwanwella'),('7010','7010-587','Bank Of Ceylon - Pilimatalawa'),('7010','7010-588','Bank Of Ceylon - Peradeniya'),('7010','7010-589','Bank Of Ceylon - Kalpitiya'),('7010','7010-590','Bank Of Ceylon - Akkaraipattu'),('7010','7010-591','Bank Of Ceylon - Nintavur'),('7010','7010-592','Bank Of Ceylon - Dickwella'),('7010','7010-593','Bank Of Ceylon - Milagiriya'),('7010','7010-594','Bank Of Ceylon - Rakwana'),('7010','7010-595','Bank Of Ceylon - Kolonnawa'),('7010','7010-596','Bank Of Ceylon - Talgaswela'),('7010','7010-597','Bank Of Ceylon - Nivitigala'),('7010','7010-598','Bank Of Ceylon - Nawalapitiya'),('7010','7010-599','Bank Of Ceylon - Aralaganwila'),('7010','7010-600','Bank Of Ceylon - Jayanthipura'),('7010','7010-601','Bank Of Ceylon - Hingurakgoda'),('7010','7010-604','Bank Of Ceylon - Ingiriya'),('7010','7010-605','Bank Of Ceylon - Kankasanthurai'),('7010','7010-606','Bank Of Ceylon - Udu Dumbara'),('7010','7010-607','Bank Of Ceylon - Panadura Bazzar'),('7010','7010-608','Bank Of Ceylon - Kaduwela'),('7010','7010-609','Bank Of Ceylon - Hikkaduwa'),('7010','7010-610','Bank Of Ceylon - Pitigala'),('7010','7010-611','Bank Of Ceylon - Kaluwanchikudy'),('7010','7010-612','Bank Of Ceylon - Lake View Branch'),('7010','7010-613','Bank Of Ceylon - Akuressa'),('7010','7010-614','Bank Of Ceylon - Matara Bazzar'),('7010','7010-615','Bank Of Ceylon - Galagedera'),('7010','7010-616','Bank Of Ceylon - Kataragama'),('7010','7010-617','Bank Of Ceylon - Metropolitan Br. (York St.)'),('7010','7010-618','Bank Of Ceylon - Metropolitan Br. (York St.)'),('7010','7010-619','Bank Of Ceylon - Elpitiya'),('7010','7010-621','Bank Of Ceylon - Kebithigollawa'),('7010','7010-622','Bank Of Ceylon - Khatagasdigiliya'),('7010','7010-623','Bank Of Ceylon - Kantalai Bazzar'),('7010','7010-624','Bank Of Ceylon - Trincomalee Bazzar'),('7010','7010-626','Bank Of Ceylon - Valachchenai'),('7010','7010-627','Bank Of Ceylon - Regent Street'),('7010','7010-628','Bank Of Ceylon - Grandpass'),('7010','7010-629','Bank Of Ceylon - Koslanda'),('7010','7010-630','Bank Of Ceylon - Chenkalady'),('7010','7010-633','Bank Of Ceylon - Kandapola'),('7010','7010-634','Bank Of Ceylon - Dehiowita'),('7010','7010-636','Bank Of Ceylon - Lake House Branch'),('7010','7010-638','Bank Of Ceylon - Nelliady'),('7010','7010-639','Bank Of Ceylon - Rattota'),('7010','7010-640','Bank Of Ceylon - Palepola'),('7010','7010-641','Bank Of Ceylon - Medirigiriya'),('7010','7010-642','Bank Of Ceylon - Deraniyagala'),('7010','7010-644','Bank Of Ceylon - Parliamentary Complex'),('7010','7010-645','Bank Of Ceylon - Kalawana'),('7010','7010-646','Bank Of Ceylon - Ginigathhena'),('7010','7010-647','Bank Of Ceylon - Lunuwatte'),('7010','7010-648','Bank Of Ceylon - Kattankudy'),('7010','7010-649','Bank Of Ceylon - Kandy 2nd City'),('7010','7010-650','Bank Of Ceylon - Talatuoya'),('7010','7010-652','Bank Of Ceylon - Bakamuna'),('7010','7010-653','Bank Of Ceylon - Galkiriyagama'),('7010','7010-654','Bank Of Ceylon - Madatugama'),('7010','7010-655','Bank Of Ceylon - Tambuttegama'),('7010','7010-656','Bank Of Ceylon - Nochchiyagama'),('7010','7010-657','Bank Of Ceylon - Agalawatta'),('7010','7010-658','Bank Of Ceylon - Katunayake Investment Br.'),('7010','7010-660','Bank Of Ceylon - Corporate Branch'),('7010','7010-663','Bank Of Ceylon - Kotahena'),('7010','7010-664','Bank Of Ceylon - Pothuhera'),('7010','7010-665','Bank Of Ceylon - Bandaragama'),('7010','7010-666','Bank Of Ceylon - Katugastota'),('7010','7010-667','Bank Of Ceylon - Neluwa'),('7010','7010-668','Bank Of Ceylon - Borella 2nd Branch'),('7010','7010-669','Bank Of Ceylon - Girandurukotte'),('7010','7010-670','Bank Of Ceylon - Kollupitiya 2nd Branch'),('7010','7010-672','Bank Of Ceylon - Central Super Market Branch'),('7010','7010-673','Bank Of Ceylon - Bulathsinhala'),('7010','7010-675','Bank Of Ceylon - Nittambuwa'),('7010','7010-676','Bank Of Ceylon - Kekirawa'),('7010','7010-678','Bank Of Ceylon - Padukka'),('7010','7010-679','Bank Of Ceylon - Battaramulla'),('7010','7010-680','Bank Of Ceylon - Aluthgama'),('7010','7010-681','Bank Of Ceylon - Personal Br., New HQ Bldg.'),('7010','7010-682','Bank Of Ceylon - Veyangoda'),('7010','7010-683','Bank Of Ceylon - Pelmadulla'),('7010','7010-684','Bank Of Ceylon - Ratnapura Bazzar'),('7010','7010-686','Bank Of Ceylon - Dehiattakandiya'),('7010','7010-688','Bank Of Ceylon - Balangoda'),('7010','7010-689','Bank Of Ceylon - Ratmalana'),('7010','7010-690','Bank Of Ceylon - Pelawatta'),('7010','7010-691','Bank Of Ceylon - Hakmana'),('7010','7010-692','Bank Of Ceylon - Eppawala'),('7010','7010-693','Bank Of Ceylon - Ruhunu Campus Branch'),('7010','7010-728','Bank Of Ceylon - Meegallewa'),('7010','7010-730','Bank Of Ceylon - Welimada'),('7010','7010-731','Bank Of Ceylon - CEYBANK Credit Card Centre'),('7010','7010-732','Bank Of Ceylon - Biyagama'),('7010','7010-735','Bank Of Ceylon - Kinniya'),('7010','7010-736','Bank Of Ceylon - Piliyandala'),('7010','7010-741','Bank Of Ceylon - Hanwella'),('7010','7010-743','Bank Of Ceylon - Walapone'),('7010','7010-745','Bank Of Ceylon - Kotiyakumbura'),('7010','7010-746','Bank Of Ceylon - Rajagiriya'),('7010','7010-747','Bank Of Ceylon - Taprobane Branch'),('7010','7010-749','Bank Of Ceylon - Karainagar'),('7010','7010-750','Bank Of Ceylon - Koggala EPZ'),('7010','7010-754','Bank Of Ceylon - Ahungalla'),('7010','7010-757','Bank Of Ceylon - Athurugiriya'),('7010','7010-760','Bank Of Ceylon - Treasury Division'),('7010','7010-761','Bank Of Ceylon - Thirunelvely'),('7010','7010-762','Bank Of Ceylon - Narahenpita'),('7010','7010-763','Bank Of Ceylon - Malabe'),('7010','7010-764','Bank Of Ceylon - Ragama'),('7010','7010-765','Bank Of Ceylon - Pugoda'),('7010','7010-766','Bank Of Ceylon - Mt.Lavinia'),('7010','7010-768','Bank Of Ceylon - Alawattugoda'),('7010','7010-769','Bank Of Ceylon - Yakkala'),('7010','7010-770','Bank Of Ceylon - Ibbagamuwa'),('7010','7010-771','Bank Of Ceylon - Kandana'),('7010','7010-772','Bank Of Ceylon - Hemmathagama'),('7010','7010-773','Bank Of Ceylon - Kottawa Branch'),('7010','7010-774','Bank Of Ceylon - Angunaklapelessa'),('7010','7010-776','Bank Of Ceylon - Islamic Banking'),('7010','7010-779','Bank Of Ceylon - Nuraicholai'),('7010','7010-822','Bank Of Ceylon - Corporate 2nd Branch'),('7038','7038-001','Standard Chartered Bank - Head Office'),('7038','7038-002','Standard Chartered Bank - Bambalapitiya'),('7038','7038-003','Standard Chartered Bank - Wellawatte'),('7038','7038-004','Standard Chartered Bank - Kiribathgoda'),('7038','7038-005','Standard Chartered Bank - Kirullapone'),('7038','7038-006','Standard Chartered Bank - Moratuwa'),('7038','7038-007','Standard Chartered Bank - Rajagiriya'),('7038','7038-008','Standard Chartered Bank - Kollupitiya'),('7038','7038-010','Standard Chartered Bank - Petta'),('7038','7038-011','Standard Chartered Bank - Union Place'),('7038','7038-012','Standard Chartered Bank - Negombo'),('7047','7047-001','City Bank - Colombo 7'),('7056','7056-','Commercial Bank - Puttalam'),('7056','7056-001','Commercial Bank - Head Office'),('7056','7056-002','Commercial Bank - City Office'),('7056','7056-003','Commercial Bank - Foreign Branch'),('7056','7056-004','Commercial Bank - Kandy'),('7056','7056-005','Commercial Bank - Galle'),('7056','7056-006','Commercial Bank - Juffna'),('7056','7056-007','Commercial Bank - Matara'),('7056','7056-008','Commercial Bank - Matale'),('7056','7056-009','Commercial Bank - Galewela'),('7056','7056-010','Commercial Bank - Wellawatte'),('7056','7056-011','Commercial Bank - Kollupitiya'),('7056','7056-012','Commercial Bank - Kotahena'),('7056','7056-013','Commercial Bank - Negombo'),('7056','7056-014','Commercial Bank - Hikkaduwa'),('7056','7056-015','Commercial Bank - Hingurakgoda'),('7056','7056-016','Commercial Bank - Kurunegala'),('7056','7056-017','Commercial Bank - Old Moor Street'),('7056','7056-018','Commercial Bank - Maharagama'),('7056','7056-019','Commercial Bank - Borella'),('7056','7056-020','Commercial Bank - Nugegoda'),('7056','7056-021','Commercial Bank - Kegalle'),('7056','7056-022','Commercial Bank - Narahenpita'),('7056','7056-023','Commercial Bank - Mutuwal'),('7056','7056-024','Commercial Bank - Pettah'),('7056','7056-025','Commercial Bank - Katunayake FTZ'),('7056','7056-026','Commercial Bank - Wennappuwa'),('7056','7056-027','Commercial Bank - Galle Sub Branch'),('7056','7056-028','Commercial Bank - Koggala'),('7056','7056-029','Commercial Bank - Battaramulla'),('7056','7056-030','Commercial Bank - Embilipitiya'),('7056','7056-031','Commercial Bank - Kandana'),('7056','7056-032','Commercial Bank - Maradana'),('7056','7056-033','Commercial Bank - Minuwangoda'),('7056','7056-034','Commercial Bank - Nuwara Eliya'),('7056','7056-035','Commercial Bank - Akuressa'),('7056','7056-036','Commercial Bank - Kalutara'),('7056','7056-037','Commercial Bank - Trincomalee'),('7056','7056-038','Commercial Bank - Panchikawatte'),('7056','7056-039','Commercial Bank - Keyzer Street'),('7056','7056-040','Commercial Bank - Aluthgama'),('7056','7056-041','Commercial Bank - Panadura'),('7056','7056-042','Commercial Bank - Kaduwela'),('7056','7056-043','Commercial Bank - Chilaw'),('7056','7056-044','Commercial Bank - Gampaha'),('7056','7056-045','Commercial Bank - Katugastota'),('7056','7056-046','Commercial Bank - Ratmalana'),('7056','7056-047','Commercial Bank - Kirulapana'),('7056','7056-048','Commercial Bank - Union Place'),('7056','7056-049','Commercial Bank - Ratnapura'),('7056','7056-050','Commercial Bank - Colombo 07'),('7056','7056-051','Commercial Bank - Kuliyapitiya'),('7056','7056-052','Commercial Bank - Badulla'),('7056','7056-053','Commercial Bank - Anuradhapura'),('7056','7056-054','Commercial Bank - Dambulla'),('7056','7056-055','Commercial Bank - Nattandiya'),('7056','7056-056','Commercial Bank - Wattala'),('7056','7056-057','Commercial Bank - Grandpass'),('7056','7056-058','Commercial Bank - Dehiwala'),('7056','7056-059','Commercial Bank - Moratuwa'),('7056','7056-060','Commercial Bank - Narammala'),('7056','7056-061','Commercial Bank - Vavuniya'),('7056','7056-062','Commercial Bank - Rajagiriya'),('7056','7056-063','Commercial Bank - Ambalanthota'),('7056','7056-064','Commercial Bank - Seeduwa'),('7056','7056-065','Commercial Bank - Nittambuwa'),('7056','7056-066','Commercial Bank - Mirigama'),('7056','7056-067','Commercial Bank - Kadawatha'),('7056','7056-068','Commercial Bank - Duplication Road'),('7056','7056-069','Commercial Bank - Kiribathgoda'),('7056','7056-070','Commercial Bank - Avissawella'),('7056','7056-071','Commercial Bank - Ekala'),('7056','7056-072','Commercial Bank - Pettah Main Street'),('7056','7056-073','Commercial Bank - Peradeniya'),('7056','7056-074','Commercial Bank - Kochchikade (C.S.P)'),('7056','7056-075','Commercial Bank - Homagama'),('7056','7056-076','Commercial Bank - Horana'),('7056','7056-077','Commercial Bank - Piliyandala'),('7056','7056-078','Commercial Bank - Thalawathugoda'),('7056','7056-079','Commercial Bank - Mawanella'),('7056','7056-080','Commercial Bank - Bandarawela'),('7056','7056-081','Commercial Bank - Ja-Ela'),('7056','7056-082','Commercial Bank - Balangoda'),('7056','7056-083','Commercial Bank - Nikaweratiya'),('7056','7056-084','Commercial Bank - Bandaragama'),('7056','7056-085','Commercial Bank - Yakkala'),('7056','7056-086','Commercial Bank - Malabe'),('7056','7056-087','Commercial Bank - Kohuwala'),('7056','7056-088','Commercial Bank - Kaduruwela'),('7056','7056-089','Commercial Bank - Nawalapitiya'),('7056','7056-093','Commercial Bank - Mt.Lavinia'),('7056','7056-096','Commercial Bank - Mathugama'),('7056','7056-097','Commercial Bank - Ambalangoda'),('7056','7056-098','Commercial Bank - Baddegama'),('7056','7056-100','Commercial Bank - Ampara'),('7056','7056-101','Commercial Bank - Nawala'),('7056','7056-102','Commercial Bank - Gampola'),('7056','7056-103','Commercial Bank - Elpitiya'),('7056','7056-104','Commercial Bank - Kamburupitiya'),('7056','7056-105','Commercial Bank - Batticaloa'),('7056','7056-106','Commercial Bank - Bambalapitiya'),('7056','7056-107','Commercial Bank - Chunakkam'),('7056','7056-108','Commercial Bank - Nelliady'),('7056','7056-109','Commercial Bank - Pilimathalawa'),('7056','7056-110','Commercial Bank - Kekirawa'),('7056','7056-111','Commercial Bank - Deniyaya'),('7056','7056-112','Commercial Bank - Weligama'),('7056','7056-113','Commercial Bank - Baseline Road (CSP)'),('7056','7056-114','Commercial Bank - Katubedda'),('7056','7056-115','Commercial Bank - Hatton'),('7056','7056-116','Commercial Bank - Reid Avenue'),('7056','7056-117','Commercial Bank - Pitakotte (CSP)'),('7056','7056-118','Commercial Bank - Negombo'),('7056','7056-119','Commercial Bank - Kotikawatta'),('7056','7056-120','Commercial Bank - Monaragala'),('7056','7056-121','Commercial Bank - Galle'),('7056','7056-122','Commercial Bank - Kurunagala (C.S.P)'),('7056','7056-123','Commercial Bank - Tangalle'),('7056','7056-124','Commercial Bank - Tissamaharama'),('7056','7056-125','Commercial Bank - Neluwa'),('7056','7056-126','Commercial Bank - Chavakachcheri'),('7056','7056-127','Commercial Bank - Jaffa (C.S.P)'),('7056','7056-128','Commercial Bank - Warakapola'),('7056','7056-129','Commercial Bank - Udugama'),('7056','7056-130','Commercial Bank - Athurugiriya'),('7056','7056-131','Commercial Bank - Raddolugama (C.S.P)'),('7056','7056-132','Commercial Bank - Boralesgamuwa (CSP)'),('7056','7056-133','Commercial Bank - Kahawatte'),('7056','7056-134','Commercial Bank - Delkanda (C.S.P)'),('7056','7056-135','Commercial Bank - Karapitiya'),('7056','7056-136','Commercial Bank - Welimada'),('7056','7056-137','Commercial Bank - Mahiyanganaya'),('7056','7056-138','Commercial Bank - Kalawana'),('7056','7056-140','Commercial Bank - Digana'),('7056','7056-142','Commercial Bank - Boralesgamuwa'),('7056','7056-146','Commercial Bank - Wadduwa'),('7056','7056-147','Commercial Bank - Biyagama'),('7056','7056-148','Commercial Bank - Puttalam'),('7056','7056-149','Commercial Bank - Pelmadulla'),('7056','7056-150','Commercial Bank - Kandy City Office'),('7056','7056-151','Commercial Bank - Matara City'),('7056','7056-152','Commercial Bank - Kalmunai'),('7056','7056-159','Commercial Bank - Valachcheni'),('7056','7056-160','Commercial Bank - Wellawaya'),('7056','7056-162','Commercial Bank - Thambuththegama'),('7056','7056-163','Commercial Bank - Ruwanwella'),('7056','7056-164','Commercial Bank - Colombo'),('7056','7056-172','Commercial Bank - akkaraipattu'),('7056','7056-206','Commercial Bank - Palavi (C.S.P)'),('7056','7056-216','Commercial Bank - Ramanayake Mawatha'),('7056','7056-220','Commercial Bank - Beruwala Minicom'),('7056','7056-254','Commercial Bank - Anniwatte (C.S.P)'),('7056','7056-255','Commercial Bank - Kundasale (C.S.P)'),('7074','7074-003','HBL - Kalmunai'),('7083','7083-','HNB -'),('7083','7083-001','HNB - Aluth Kade'),('7083','7083-002','HNB - City Office'),('7083','7083-003','HNB - Head Office Branch'),('7083','7083-004','HNB - Head Office'),('7083','7083-005','HNB - Main Branch'),('7083','7083-006','HNB - Maligawatte'),('7083','7083-007','HNB - Pettah'),('7083','7083-008','HNB - Suduwella'),('7083','7083-009','HNB - Wellawatte'),('7083','7083-010','HNB - Anuradhapura'),('7083','7083-011','HNB - Badulla'),('7083','7083-012','HNB - Bandarawela'),('7083','7083-013','HNB - Galle'),('7083','7083-014','HNB - Gampola'),('7083','7083-015','HNB - Hatton'),('7083','7083-016','HNB - Jaffna'),('7083','7083-017','HNB - Kahawatte'),('7083','7083-018','HNB - KAndy'),('7083','7083-019','HNB - Kurunegala'),('7083','7083-020','HNB - Mannar'),('7083','7083-021','HNB - Maskeliya'),('7083','7083-022','HNB - Moratuwa'),('7083','7083-023','HNB - Nawalapitiya'),('7083','7083-024','HNB - Negombo'),('7083','7083-025','HNB - Nittambuwa'),('7083','7083-026','HNB - Nochchiyagama'),('7083','7083-027','HNB - Nugegoda'),('7083','7083-028','HNB - Nuwara Eliya'),('7083','7083-029','HNB - Pusellawa'),('7083','7083-030','HNB - Ratnapura'),('7083','7083-031','HNB - Trincomalee'),('7083','7083-032','HNB - Vavuniya'),('7083','7083-033','HNB - Welimada'),('7083','7083-034','HNB - Kalutara'),('7083','7083-035','HNB - Wattala'),('7083','7083-036','HNB - Sri Jayawardanapura'),('7083','7083-038','HNB - Piliyandala'),('7083','7083-039','HNB - Bambalapitiya'),('7083','7083-040','HNB - Chilaw'),('7083','7083-041','HNB - Kegalle'),('7083','7083-042','HNB - Matara'),('7083','7083-043','HNB - Kirulapone'),('7083','7083-044','HNB - Polonnaruwa'),('7083','7083-045','HNB - Ambalantota'),('7083','7083-046','HNB - Grandpass'),('7083','7083-047','HNB - Biyagama'),('7083','7083-048','HNB - Dambulla'),('7083','7083-049','HNB - Air Cargo Village'),('7083','7083-050','HNB - Embilipitiya'),('7083','7083-051','HNB - Gampaha'),('7083','7083-052','HNB - Horana'),('7083','7083-053','HNB - Monaragala'),('7083','7083-055','HNB - Borella'),('7083','7083-056','HNB - Kiribathgoda'),('7083','7083-057','HNB - Batticaloa'),('7083','7083-058','HNB - Ampara'),('7083','7083-059','HNB - Panchikawatta'),('7083','7083-060','HNB - Bogawanthalawa'),('7083','7083-061','HNB - Mt.Lavinia'),('7083','7083-062','HNB - Kollupitiya'),('7083','7083-063','HNB - Hulftsdrop'),('7083','7083-064','HNB - Maharagama'),('7083','7083-065','HNB - MAtale'),('7083','7083-066','HNB - Pinnawala'),('7083','7083-067','HNB - Suriyawewa'),('7083','7083-068','HNB - Hambantota'),('7083','7083-069','HNB - Panadura'),('7083','7083-070','HNB - Dankotuwa'),('7083','7083-071','HNB - Balangoda'),('7083','7083-072','HNB - Sea Street'),('7083','7083-073','HNB - Moratumulla'),('7083','7083-074','HNB - Kuliyapitiya'),('7083','7083-075','HNB - Buttala'),('7083','7083-076','HNB - Cinnamon Gardens'),('7083','7083-077','HNB - Homagama'),('7083','7083-078','HNB - Akkaraipathhtu'),('7083','7083-079','HNB - Maradagahamula'),('7083','7083-080','HNB - Marawila'),('7083','7083-081','HNB - ambalangoda'),('7083','7083-082','HNB - Kaduwela'),('7083','7083-083','HNB - Puttalam'),('7083','7083-084','HNB - Kadawatha'),('7083','7083-085','HNB - Thalangama'),('7083','7083-086','HNB - Thangalle'),('7083','7083-087','HNB - Ja Ela'),('7083','7083-088','HNB - Thambuttegama'),('7083','7083-089','HNB - Mawanella'),('7083','7083-090','HNB - Thissamaharama'),('7083','7083-091','HNB - Kalmunai'),('7083','7083-092','HNB - Thimbirigasyaya'),('7083','7083-093','HNB - Dehiwala'),('7083','7083-094','HNB - Minuwangoda'),('7083','7083-095','HNB - Kanthali'),('7083','7083-096','HNB - Kotahena'),('7083','7083-097','HNB - Mutwal'),('7083','7083-098','HNB - Kottawa'),('7083','7083-099','HNB - Kirindiwela'),('7083','7083-100','HNB - Katugastota'),('7083','7083-101','HNB - Pelmadulla'),('7083','7083-102','HNB - Ragama'),('7083','7083-103','HNB - Dematagoda'),('7083','7083-104','HNB - Narahenpitiya'),('7083','7083-106','HNB - Wellawaya'),('7083','7083-107','HNB - Elpitiya'),('7083','7083-108','HNB - Maradana'),('7083','7083-109','HNB - Aluthgama'),('7083','7083-110','HNB - Wennappuwa'),('7083','7083-111','HNB - Avissawella'),('7083','7083-112','HNB - Boralesgamuwa'),('7083','7083-114','HNB - Central Colombo Branch'),('7083','7083-115','HNB - Kollupitiya'),('7083','7083-116','HNB - Colombo South Branch'),('7083','7083-117','HNB - Chunnakam'),('7083','7083-118','HNB - Nelliady'),('7083','7083-120','HNB - Deniyaya'),('7083','7083-121','HNB - Nikaweratiya'),('7083','7083-122','HNB - Delgoda'),('7083','7083-123','HNB - Alawwa'),('7083','7083-124','HNB - Mahiyangana'),('7083','7083-125','HNB - Mathugama'),('7083','7083-126','HNB - Warakapola'),('7083','7083-127','HNB - Middeniya'),('7083','7083-128','HNB - Galgamuwa'),('7083','7083-130','HNB - Weliweriya'),('7083','7083-135','HNB - Chenkaladi'),('7083','7083-136','HNB - Ganemulla'),('7083','7083-138','HNB - Kelaniya'),('7083','7083-139','HNB - HANWELLA'),('7083','7083-141','HNB - Pilimatalawa'),('7083','7083-143','HNB - Madawachchiya'),('7083','7083-149','HNB - Galewela'),('7083','7083-151','HNB - Akuressa'),('7083','7083-153','HNB - Wariyapola'),('7083','7083-158','HNB - Pottuvil'),('7083','7083-159','HNB - Nintavur'),('7083','7083-162','HNB - Rikilla'),('7083','7083-165','HNB - Kaluwanchikudy'),('7083','7083-167','HNB - Valachenai'),('7083','7083-176','HNB - Digana'),('7083','7083-178','HNB - Dikwella'),('7092','7092-001','HSBC - Colombo'),('7092','7092-002','HSBC - Kandy'),('7135','7135-001','Peoples Bank - Duke Street'),('7135','7135-002','Peoples Bank - Matale'),('7135','7135-003','Peoples Bank - Kandy'),('7135','7135-004','Peoples Bank - International Div.'),('7135','7135-005','Peoples Bank - Polonnaruwa'),('7135','7135-006','Peoples Bank - Hingurakgoda'),('7135','7135-007','Peoples Bank - Hambantota'),('7135','7135-008','Peoples Bank - Anuradhapura'),('7135','7135-009','Peoples Bank - Puttalam'),('7135','7135-011','Peoples Bank - Bibila'),('7135','7135-012','Peoples Bank - Kurunegala'),('7135','7135-013','Peoples Bank - Galle-Fort'),('7135','7135-014','Peoples Bank - Union Place'),('7135','7135-015','Peoples Bank - Ampara'),('7135','7135-016','Peoples Bank - Welimada'),('7135','7135-017','Peoples Bank - Balangoda'),('7135','7135-018','Peoples Bank - Gampola'),('7135','7135-019','Peoples Bank - Dehiwala'),('7135','7135-020','Peoples Bank - Mullaitivu'),('7135','7135-021','Peoples Bank - Minuwangoda'),('7135','7135-022','Peoples Bank - Hanguranketha'),('7135','7135-023','Peoples Bank - Kalmunai'),('7135','7135-024','Peoples Bank - Chalaw'),('7135','7135-025','Peoples Bank - Hyde Park Corner'),('7135','7135-026','Peoples Bank - Gampaha'),('7135','7135-027','Peoples Bank - Kegalle'),('7135','7135-028','Peoples Bank - Kuliyapitiya'),('7135','7135-029','Peoples Bank - Avissawella'),('7135','7135-030','Peoples Bank - Jaffna Stanley Road'),('7135','7135-031','Peoples Bank - Kankasanthurai'),('7135','7135-032','Peoples Bank - Matara Uyanwatte'),('7135','7135-033','Peoples Bank - Queen Street'),('7135','7135-034','Peoples Bank - Negambo'),('7135','7135-035','Peoples Bank - Ambalangoda'),('7135','7135-036','Peoples Bank - Ragala'),('7135','7135-037','Peoples Bank - Bandarawela'),('7135','7135-038','Peoples Bank - Talawakelle'),('7135','7135-039','Peoples Bank - Kalutara'),('7135','7135-040','Peoples Bank - Vavuniya'),('7135','7135-041','Peoples Bank - Horana'),('7135','7135-042','Peoples Bank - Kekirawa'),('7135','7135-043','Peoples Bank - Padaviya'),('7135','7135-044','Peoples Bank - Mannar'),('7135','7135-045','Peoples Bank - Embilipitiya'),('7135','7135-046','Peoples Bank - First City Branch'),('7135','7135-047','Peoples Bank - Yatiyantota'),('7135','7135-048','Peoples Bank - Kilinochci'),('7135','7135-049','Peoples Bank - Homagama'),('7135','7135-050','Peoples Bank - MAin Street,Colombo'),('7135','7135-051','Peoples Bank - Kahatagasdigitiya'),('7135','7135-052','Peoples Bank - Maho'),('7135','7135-053','Peoples Bank - Nawalapitiya'),('7135','7135-054','Peoples Bank - Warakapola'),('7135','7135-055','Peoples Bank - Kelaniya'),('7135','7135-056','Peoples Bank - Sri Sangaraja Mw.'),('7135','7135-057','Peoples Bank - Peradeniya'),('7135','7135-058','Peoples Bank - Mahiyanganaya'),('7135','7135-059','Peoples Bank - Polgahawela'),('7135','7135-060','Peoples Bank - Morawaka'),('7135','7135-061','Peoples Bank - Tissamaharama'),('7135','7135-062','Peoples Bank - Wellawaya'),('7135','7135-063','Peoples Bank - Akkareipattu'),('7135','7135-064','Peoples Bank - Sammanturai'),('7135','7135-065','Peoples Bank - Kattankudy'),('7135','7135-066','Peoples Bank - Trincomalee'),('7135','7135-067','Peoples Bank - Tangalle'),('7135','7135-068','Peoples Bank - Monaragala'),('7135','7135-069','Peoples Bank - Mawanella'),('7135','7135-070','Peoples Bank - Matugama'),('7135','7135-071','Peoples Bank - Dematagoda'),('7135','7135-072','Peoples Bank - Ambalantota'),('7135','7135-073','Peoples Bank - Elpitiya'),('7135','7135-074','Peoples Bank - Wattegama'),('7135','7135-075','Peoples Bank - Batticaloa'),('7135','7135-076','Peoples Bank - Wennappuwa'),('7135','7135-077','Peoples Bank - Weligama'),('7135','7135-078','Peoples Bank - Borella'),('7135','7135-079','Peoples Bank - Veyangoda'),('7135','7135-080','Peoples Bank - Ratmalana'),('7135','7135-081','Peoples Bank - Ruwanwella'),('7135','7135-082','Peoples Bank - Narammala'),('7135','7135-083','Peoples Bank - Nattandiya'),('7135','7135-084','Peoples Bank - Aluthgama'),('7135','7135-085','Peoples Bank - Eheliyagoda'),('7135','7135-086','Peoples Bank - Thimbirigasyaya'),('7135','7135-087','Peoples Bank - Baddegama'),('7135','7135-088','Peoples Bank - Ratnapura'),('7135','7135-089','Peoples Bank - Katugastota'),('7135','7135-090','Peoples Bank - Kantalai'),('7135','7135-091','Peoples Bank - Moratuwa'),('7135','7135-092','Peoples Bank - Giriulla'),('7135','7135-093','Peoples Bank - Pugoda'),('7135','7135-094','Peoples Bank - Kinniya'),('7135','7135-095','Peoples Bank - Mutur'),('7135','7135-096','Peoples Bank - Medawachchiya'),('7135','7135-097','Peoples Bank - Gangodawila'),('7135','7135-098','Peoples Bank - Kotikawatte'),('7135','7135-100','Peoples Bank - Marandagahamula'),('7135','7135-101','Peoples Bank - Rambukkana'),('7135','7135-102','Peoples Bank - Valachchenei'),('7135','7135-103','Peoples Bank - Piliyandala'),('7135','7135-104','Peoples Bank - Jaffna Main Street'),('7135','7135-105','Peoples Bank - Keyts'),('7135','7135-106','Peoples Bank - Nelliady'),('7135','7135-107','Peoples Bank - Atchuvely'),('7135','7135-108','Peoples Bank - Chankanai'),('7135','7135-109','Peoples Bank - Chunnakam'),('7135','7135-110','Peoples Bank - Chavakachcheri'),('7135','7135-111','Peoples Bank - Paranthan'),('7135','7135-112','Peoples Bank - Teldeniya'),('7135','7135-113','Peoples Bank - Batticaloa'),('7135','7135-114','Peoples Bank - Galagedara'),('7135','7135-115','Peoples Bank - Galewela'),('7135','7135-116','Peoples Bank - Passara'),('7135','7135-117','Peoples Bank - Akuressa'),('7135','7135-118','Peoples Bank - delgoda'),('7135','7135-119','Peoples Bank - Narahenpita'),('7135','7135-120','Peoples Bank - Walasmulla'),('7135','7135-121','Peoples Bank - Bandaragama'),('7135','7135-122','Peoples Bank - Wilgamuwa'),('7135','7135-123','Peoples Bank - Eravur'),('7135','7135-124','Peoples Bank - Nikaweratiya'),('7135','7135-125','Peoples Bank - Kalpitiya'),('7135','7135-126','Peoples Bank - Grandpass'),('7135','7135-127','Peoples Bank - Nildandahinna'),('7135','7135-128','Peoples Bank - Rattota'),('7135','7135-129','Peoples Bank - Rakwana'),('7135','7135-130','Peoples Bank - Hakmana'),('7135','7135-131','Peoples Bank - Udugama'),('7135','7135-132','Peoples Bank - Deniyaya'),('7135','7135-133','Peoples Bank - Kamburupitiya'),('7135','7135-134','Peoples Bank - Nuwara Eliya'),('7135','7135-135','Peoples Bank - Dickwella'),('7135','7135-136','Peoples Bank - Hikkaduwa'),('7135','7135-137','Peoples Bank - Makandura'),('7135','7135-138','Peoples Bank - Dambulla'),('7135','7135-139','Peoples Bank - Pettah'),('7135','7135-140','Peoples Bank - HAsalaka'),('7135','7135-141','Peoples Bank - Velivetiturai'),('7135','7135-142','Peoples Bank - Kochchikade'),('7135','7135-143','Peoples Bank - Suduwella'),('7135','7135-144','Peoples Bank - Hettipola'),('7135','7135-145','Peoples Bank - Wellawatte'),('7135','7135-146','Peoples Bank - Naula'),('7135','7135-147','Peoples Bank - Buttala'),('7135','7135-148','Peoples Bank - Panadura'),('7135','7135-149','Peoples Bank - Alawwa'),('7135','7135-150','Peoples Bank - Kebithigollewa'),('7135','7135-151','Peoples Bank - Diyatalawa'),('7135','7135-152','Peoples Bank - Matara Dharmapala Mw'),('7135','7135-153','Peoples Bank - Akurana'),('7135','7135-154','Peoples Bank - Balapitiya'),('7135','7135-155','Peoples Bank - Kahawatte'),('7135','7135-156','Peoples Bank - Uva Paranagama'),('7135','7135-157','Peoples Bank - Manikhinna'),('7135','7135-158','Peoples Bank - Senkadagala'),('7135','7135-159','Peoples Bank - Kadugannawa'),('7135','7135-160','Peoples Bank - Pelmadulla'),('7135','7135-161','Peoples Bank - Bulathsinhala'),('7135','7135-162','Peoples Bank - Jaffna University'),('7135','7135-163','Peoples Bank - Wariyapola'),('7135','7135-164','Peoples Bank - Pottuvil'),('7135','7135-165','Peoples Bank - Mankulam'),('7135','7135-166','Peoples Bank - Murunkan'),('7135','7135-167','Peoples Bank - Town Hall,Colombo'),('7135','7135-168','Peoples Bank - Kataragama'),('7135','7135-169','Peoples Bank - Galle Main Street'),('7135','7135-170','Peoples Bank - Eppawela'),('7135','7135-171','Peoples Bank - Nochchiyagama'),('7135','7135-172','Peoples Bank - Bingiriya'),('7135','7135-173','Peoples Bank - Pundaluoya'),('7135','7135-174','Peoples Bank - Nugegoda'),('7135','7135-175','Peoples Bank - Kandana'),('7135','7135-176','Peoples Bank - Mid City Branch'),('7135','7135-177','Peoples Bank - Galenbindunuwewa'),('7135','7135-178','Peoples Bank - Maskeliya'),('7135','7135-179','Peoples Bank - Galnewa'),('7135','7135-180','Peoples Bank - Deraniyagala'),('7135','7135-181','Peoples Bank - Maha Oya'),('7135','7135-183','Peoples Bank - Ankumbura'),('7135','7135-184','Peoples Bank - Galgamuwa'),('7135','7135-185','Peoples Bank - Galigamuwa/Kegalle'),('7135','7135-186','Peoples Bank - Hatton'),('7135','7135-188','Peoples Bank - Ahangama'),('7135','7135-189','Peoples Bank - Uhana'),('7135','7135-190','Peoples Bank - Kaluwanchikudy'),('7135','7135-191','Peoples Bank - MAlwana'),('7135','7135-192','Peoples Bank - Nivithigala'),('7135','7135-193','Peoples Bank - Ridigama'),('7135','7135-194','Peoples Bank - Kolonnawa'),('7135','7135-195','Peoples Bank - Haldummulla'),('7135','7135-196','Peoples Bank - Kaduwela'),('7135','7135-197','Peoples Bank - Uragasmanhandiya'),('7135','7135-198','Peoples Bank - Mirigama'),('7135','7135-199','Peoples Bank - Mawathagama'),('7135','7135-200','Peoples Bank - Majestic City'),('7135','7135-201','Peoples Bank - Ukuwela'),('7135','7135-202','Peoples Bank - Kirindiwela'),('7135','7135-203','Peoples Bank - Habarana'),('7135','7135-204','Peoples Bank - Head quarters Branch'),('7135','7135-205','Peoples Bank - Angunakolapalessa'),('7135','7135-206','Peoples Bank - Davulagala'),('7135','7135-207','Peoples Bank - Ibbagamuwa'),('7135','7135-208','Peoples Bank - Battaramulla'),('7135','7135-209','Peoples Bank - Boralanda'),('7135','7135-210','Peoples Bank - Kollupi.Co-op House'),('7135','7135-211','Peoples Bank - Pnwila'),('7135','7135-214','Peoples Bank - Mutuwal'),('7135','7135-215','Peoples Bank - Madampe'),('7135','7135-216','Peoples Bank - Haputale'),('7135','7135-217','Peoples Bank - Mahara'),('7135','7135-218','Peoples Bank - Horowpathana'),('7135','7135-219','Peoples Bank - Thambuttegama'),('7135','7135-220','Peoples Bank - Anuradhapura,Nuwarawewa'),('7135','7135-221','Peoples Bank - Hemmathagama'),('7135','7135-222','Peoples Bank - Wattala'),('7135','7135-223','Peoples Bank - Karaitivu'),('7135','7135-224','Peoples Bank - Thirukkovil'),('7135','7135-225','Peoples Bank - Haliela'),('7135','7135-226','Peoples Bank - Kurune.Maliyadeva Br'),('7135','7135-227','Peoples Bank - Chenkalady'),('7135','7135-228','Peoples Bank - Addalachena'),('7135','7135-229','Peoples Bank - Hanwella'),('7135','7135-230','Peoples Bank - Thanamalwila'),('7135','7135-231','Peoples Bank - Medirigiriya'),('7135','7135-232','Peoples Bank - Polonnaruwa Town'),('7135','7135-233','Peoples Bank - Serunuwara'),('7135','7135-234','Peoples Bank - Batapola'),('7135','7135-235','Peoples Bank - kalawana'),('7135','7135-236','Peoples Bank - Maradana'),('7135','7135-237','Peoples Bank - Kiribathgoda'),('7135','7135-238','Peoples Bank - Gonagaldeniya'),('7135','7135-239','Peoples Bank - Ja-Ela'),('7135','7135-240','Peoples Bank - Keppetipola'),('7135','7135-241','Peoples Bank - Pallepola'),('7135','7135-242','Peoples Bank -Bakamuna'),('7135','7135-243','Peoples Bank - Devinueara'),('7135','7135-244','Peoples Bank - Beliatta'),('7135','7135-245','Peoples Bank - Godakawela'),('7135','7135-246','Peoples Bank - Meegalewa'),('7135','7135-247','Peoples Bank - Imaduwa'),('7135','7135-248','Peoples Bank - Aranayaka'),('7135','7135-249','Peoples Bank - Neboda'),('7135','7135-250','Peoples Bank - kandeketiya'),('7135','7135-251','Peoples Bank - Lunugala'),('7135','7135-252','Peoples Bank - Bulathkohupitiya'),('7135','7135-253','Peoples Bank - Aralaganwila'),('7135','7135-254','Peoples Bank - Welikanda'),('7135','7135-255','Peoples Bank - Trincomalee Town'),('7135','7135-256','Peoples Bank - Pilimatalawa'),('7135','7135-257','Peoples Bank - Deltota'),('7135','7135-258','Peoples Bank - Medagama'),('7135','7135-259','Peoples Bank - Kehelwatte'),('7135','7135-260','Peoples Bank - Koslanda'),('7135','7135-261','Peoples Bank - Pelawatte'),('7135','7135-262','Peoples Bank - Wadduwa'),('7135','7135-263','Peoples Bank - Kuruvita'),('7135','7135-264','Peoples Bank - Suriyawewa'),('7135','7135-265','Peoples Bank - Middeniya'),('7135','7135-266','Peoples Bank - Kiriella'),('7135','7135-267','Peoples Bank - Anamaduwa'),('7135','7135-268','Peoples Bank - Girandurukotte'),('7135','7135-269','Peoples Bank - Badulla-Muthiyangana'),('7135','7135-270','Peoples Bank - Thulhiriya'),('7135','7135-271','Peoples Bank - Urubokka'),('7135','7135-272','Peoples Bank - Talgaswala'),('7135','7135-273','Peoples Bank - Kadawata'),('7135','7135-274','Peoples Bank - pussellawa'),('7135','7135-275','Peoples Bank - Olcott Mawatha'),('7135','7135-276','Peoples Bank - Katunayaka'),('7135','7135-277','Peoples Bank - Sea Street'),('7135','7135-278','Peoples Bank - Nittambuwa'),('7135','7135-279','Peoples Bank - Pitakotte'),('7135','7135-280','Peoples Bank - Pothuhera'),('7135','7135-281','Peoples Bank - Kobeigane'),('7135','7135-282','Peoples Bank - Maggona'),('7135','7135-283','Peoples Bank - Badureliya'),('7135','7135-284','Peoples Bank - Jaffna Kannathiddy'),('7135','7135-285','Peoples Bank - Point Pedro'),('7135','7135-286','Peoples Bank - International Div.'),('7135','7135-288','Peoples Bank - Kudawella'),('7135','7135-289','Peoples Bank - Kaltota'),('7135','7135-290','Peoples Bank - Moratumulla'),('7135','7135-291','Peoples Bank - Dankotuwa'),('7135','7135-292','Peoples Bank - Udapussellawa'),('7135','7135-293','Peoples Bank - Dehiovita'),('7135','7135-294','Peoples Bank - Alawatugoda'),('7135','7135-295','Peoples Bank - Udawalawe'),('7135','7135-296','Peoples Bank - Nintavur'),('7135','7135-297','Peoples Bank - Dam Street'),('7135','7135-298','Peoples Bank - Ginthupitiya'),('7135','7135-299','Peoples Bank - Kegalle Bazzar'),('7135','7135-300','Peoples Bank - Ingiriya'),('7135','7135-301','Peoples Bank - Galkiriyagama'),('7135','7135-302','Peoples Bank - Ginigathhena'),('7135','7135-303','Peoples Bank - Mahawewa'),('7135','7135-304','Peoples Bank - Urugamuwa'),('7135','7135-305','Peoples Bank - Trinco Inner Harbour'),('7135','7135-306','Peoples Bank - Maharagama'),('7135','7135-307','Peoples Bank - Gandara'),('7135','7135-308','Peoples Bank - Kotahena'),('7135','7135-309','Peoples Bank - Liberty Plaza'),('7135','7135-310','Peoples Bank - Bambalapitiya'),('7135','7135-311','Peoples Bank - Beruwala'),('7135','7135-312','Peoples Bank - Malwatta Road'),('7135','7135-313','Peoples Bank - Katubedda'),('7135','7135-315','Peoples Bank - Talawa'),('7135','7135-316','Peoples Bank - Ragama'),('7135','7135-317','Peoples Bank - Ratnapura Town'),('7135','7135-318','Peoples Bank - Pamunugama'),('7135','7135-319','Peoples Bank - Kirulapana'),('7135','7135-320','Peoples Bank - Borella Cotta Road'),('7135','7135-321','Peoples Bank - Panadura Town'),('7135','7135-322','Peoples Bank - Marawila'),('7135','7135-323','Peoples Bank - Corporate Branch'),('7135','7135-324','Peoples Bank - Seeduwa'),('7135','7135-325','Peoples Bank - Wandurambe'),('7135','7135-326','Peoples Bank - Capricon Branch'),('7135','7135-327','Peoples Bank - Kesbewa'),('7135','7135-328','Peoples Bank - kottawa'),('7135','7135-329','Peoples Bank - Koggala'),('7135','7135-330','Peoples Bank - Dehiattakandiya'),('7135','7135-331','Peoples Bank - Lucky Plaza'),('7135','7135-332','Peoples Bank - Ganemulla'),('7135','7135-333','Peoples Bank - Ykkala'),('7135','7135-334','Peoples Bank - Kgala-Ethugalpura'),('7135','7135-335','Peoples Bank - Nugegoda City'),('7135','7135-336','Peoples Bank - Mt.Lavinia'),('7135','7135-337','Peoples Bank - Dehiwala'),('7135','7135-338','Peoples Bank - Sainthamaruthu'),('7135','7135-339','Peoples Bank - Kallar'),('7135','7135-340','Peoples Bank - Oddamawady'),('7135','7135-666','Peoples Bank - Poojapitiya'),('7135','7135-796','Peoples Bank - OCU'),('7162','7162-','Nation Trust - Colombo'),('7162','7162-001','Nation Trust - Colombo'),('7162','7162-004','Nation Trust - Colombo 2'),('7162','7162-007','Nation Trust - Colombo2'),('7162','7162-012','Nation Trust - Kurunegala'),('7162','7162-017','Nation Trust - Colombo'),('7162','7162-025','Nation Trust - Union Place'),('7162','7162-026','Nation Trust -'),('7162','7162-028','Nation Trust - Colombo 2'),('7162','7162-029','Nation Trust - Colombo 2'),('7162','7162-031','Nation Trust - Colombo 2'),('7162','7162-034','Nation Trust - Colombo'),('7214','7214-000','NDB - Head Office'),('7214','7214-001','NDB - Nawam Mawatha'),('7214','7214-002','NDB - Kandy'),('7214','7214-003','NDB - Jawatta'),('7214','7214-004','NDB - Nugegoda'),('7214','7214-005','NDB - Rajagiriya'),('7214','7214-006','NDB - Matara'),('7214','7214-007','NDB - Kurunegala'),('7214','7214-008','NDB - Wellawatta'),('7214','7214-009','NDB - Negombo'),('7214','7214-010','NDB - Chilaw'),('7214','7214-011','NDB - Wattala'),('7214','7214-012','NDB - Maharagama'),('7214','7214-013','NDB - Ratnapura'),('7214','7214-014','NDB - Colombo 03'),('7214','7214-015','NDB - Moratuwa'),('7214','7214-016','NDB - Kalutara'),('7214','7214-017','NDB - Kegalle'),('7214','7214-018','NDB - Badulla'),('7214','7214-019','NDB - Anuradhapura'),('7214','7214-020','NDB - Mt.Lavinia'),('7214','7214-021','NDB - Galle'),('7214','7214-022','NDB - Pelawatta'),('7214','7214-023','NDB - Piliyandala'),('7214','7214-024','NDB - Kotahena'),('7214','7214-025','NDB - Kiribathgoda'),('7214','7214-026','NDB - Kadawatha'),('7214','7214-027','NDB - Horana'),('7214','7214-028','NDB - Kandana'),('7214','7214-029','NDB - Gampaha'),('7214','7214-030','NDB - Homagama'),('7214','7214-031','NDB - Malabe'),('7214','7214-033','NDB - Puttalam'),('7214','7214-038','NDB - Trincomalee'),('7214','7214-039','NDB - Colombo'),('7214','7214-040','NDB - Colombo'),('7214','7214-100','NDB - Head Office (Retail)'),('7214','7214-21','NDB - Galle'),('7214','7214-900','NDB - Head Office (CO-op)'),('7269','7269-','Islamic Bank -'),('7269','7269-004','Islamic Bank - Colombo'),('7269','7269-006','Islamic Bank - Colombo'),('7278','7278-','Sampath Bank - Batticoloa'),('7278','7278-001','Sampath Bank - City Office'),('7278','7278-002','Sampath Bank - Pettah'),('7278','7278-003','Sampath Bank - Nugegoda'),('7278','7278-004','Sampath Bank - Borella'),('7278','7278-005','Sampath Bank - Kiribathgoda'),('7278','7278-006','Sampath Bank - Kurunegala'),('7278','7278-007','Sampath Bank - Kandy'),('7278','7278-008','Sampath Bank - Wattala'),('7278','7278-009','Sampath Bank - Navam Mawatha'),('7278','7278-010','Sampath Bank - Matara'),('7278','7278-011','Sampath Bank - Bambalapitiya'),('7278','7278-012','Sampath Bank - Fort'),('7278','7278-013','Sampath Bank - Maharagama'),('7278','7278-014','Sampath Bank - Deniyaya'),('7278','7278-015','Sampath Bank - Morawaka'),('7278','7278-016','Sampath Bank - Gampaha'),('7278','7278-017','Sampath Bank - Dehiwala'),('7278','7278-018','Sampath Bank - Ratmalana'),('7278','7278-019','Sampath Bank - Piliyandala'),('7278','7278-020','Sampath Bank - Eheliyagoda'),('7278','7278-021','Sampath Bank - Anuradhapura'),('7278','7278-022','Sampath Bank - Avissawella'),('7278','7278-023','Sampath Bank - Kuliyapitiya'),('7278','7278-024','Sampath Bank - Negombo'),('7278','7278-025','Sampath Bank - Matale'),('7278','7278-026','Sampath Bank - Panadura'),('7278','7278-027','Sampath Bank - Old Moor Street'),('7278','7278-028','Sampath Bank - Thissamaharama'),('7278','7278-029','Sampath Bank - Head Quarters'),('7278','7278-030','Sampath Bank - Wennappuwa'),('7278','7278-031','Sampath Bank - Moratuwa'),('7278','7278-032','Sampath Bank - Katugasthota'),('7278','7278-033','Sampath Bank - Rathnapura'),('7278','7278-034','Sampath Bank - Thimbirigasyaya'),('7278','7278-035','Sampath Bank - Galle'),('7278','7278-036','Sampath Bank - Wellawatta'),('7278','7278-037','Sampath Bank - Kotahena'),('7278','7278-038','Sampath Bank - Kaduruwela'),('7278','7278-039','Sampath Bank - Malabe'),('7278','7278-040','Sampath Bank - Narahenpita'),('7278','7278-041','Sampath Bank - Lakawana'),('7278','7278-042','Sampath Bank - Main Street'),('7278','7278-043','Sampath Bank - Embilipitiya'),('7278','7278-044','Sampath Bank - Wariyapola'),('7278','7278-045','Sampath Bank - Wellampitiya'),('7278','7278-046','Sampath Bank - Bandarawela'),('7278','7278-047','Sampath Bank - Unichela'),('7278','7278-048','Sampath Bank - Thambuttegama (SPU)'),('7278','7278-049','Sampath Bank - Deraniyagala'),('7278','7278-050','Sampath Bank - Kalutara'),('7278','7278-051','Sampath Bank - Peradeniya'),('7278','7278-052','Sampath Bank - Kottawa'),('7278','7278-053','Sampath Bank - Alawwe'),('7278','7278-054','Sampath Bank - Neluwa'),('7278','7278-055','Sampath Bank - Vavuniya'),('7278','7278-056','Sampath Bank - Mahiyanganaya'),('7278','7278-057','Sampath Bank - Horana'),('7278','7278-058','Sampath Bank - Harbour-View'),('7278','7278-059','Sampath Bank - Bandaragama'),('7278','7278-060','Sampath Bank - Kadawatha'),('7278','7278-061','Sampath Bank - Battaramulla'),('7278','7278-062','Sampath Bank - Ampara'),('7278','7278-063','Sampath Bank - Pelawatta (SPU)'),('7278','7278-064','Sampath Bank - Kegalle'),('7278','7278-065','Sampath Bank - Minuwangoda'),('7278','7278-066','Sampath Bank - Trincomalee'),('7278','7278-067','Sampath Bank - Athurugiriya (SPU)'),('7278','7278-068','Sampath Bank - Yakkala'),('7278','7278-069','Sampath Bank - Homagama'),('7278','7278-070','Sampath Bank - Gregorys Road'),('7278','7278-071','Sampath Bank - Nittambuwa'),('7278','7278-072','Sampath Bank - Ambalangoada'),('7278','7278-073','Sampath Bank - Ragama'),('7278','7278-074','Sampath Bank - Monaragala'),('7278','7278-075','Sampath Bank - Wadduwa'),('7278','7278-076','Sampath Bank - Kandana'),('7278','7278-077','Sampath Bank - Veyangoda'),('7278','7278-078','Sampath Bank - Ganemulla'),('7278','7278-079','Sampath Bank - Aluthgama'),('7278','7278-080','Sampath Bank - Hatton'),('7278','7278-081','Sampath Bank - Welimada'),('7278','7278-082','Sampath Bank - Singer Mega-Nugegoda'),('7278','7278-083','Sampath Bank - Kirindiwela'),('7278','7278-084','Sampath Bank - Nuwara Eliya'),('7278','7278-085','Sampath Bank - Digana'),('7278','7278-086','Sampath Bank - Mirigama'),('7278','7278-087','Sampath Bank - Laugfs Super Market'),('7278','7278-088','Sampath Bank - Singer Mega - Negombo'),('7278','7278-089','Sampath Bank - Attidiya'),('7278','7278-090','Sampath Bank - Dambulla'),('7278','7278-091','Sampath Bank - Pitakotte'),('7278','7278-092','Sampath Bank - Singer Mega-Maharagama'),('7278','7278-093','Sampath Bank - Badulla'),('7278','7278-094','Sampath Bank - Kohuwala'),('7278','7278-095','Sampath Bank - Girulla'),('7278','7278-096','Sampath Bank - Singer Mega-Wattala'),('7278','7278-097','Sampath Bank - Balangoda'),('7278','7278-098','Sampath Bank - JA-Ela'),('7278','7278-099','Sampath Bank - Narammala'),('7278','7278-100','Sampath Bank - Corporate Branch-Kandy'),('7278','7278-102','Sampath Bank -'),('7278','7278-103','Sampath Bank - Pelmadulla'),('7278','7278-104','Sampath Bank - Ambalantota'),('7278','7278-106','Sampath Bank - Mathugama'),('7278','7278-107','Sampath Bank - Batticoloa'),('7278','7278-109','Sampath Bank - Mawathagama'),('7278','7278-110','Sampath Bank - Hingurakgoda'),('7278','7278-111','Sampath Bank - Akkaraipattu'),('7278','7278-112','Sampath Bank - Kalmunai'),('7278','7278-114','Sampath Bank - Embuldeniya'),('7278','7278-115','Sampath Bank - Kattankudy'),('7278','7278-118','Sampath Bank - Baddegama'),('7278','7278-121','Sampath Bank - Chenkalady'),('7278','7278-124','Sampath Bank - Oddamavady'),('7278','7278-125','Sampath Bank - Kattankudy'),('7278','7278-126','Sampath Bank - Sainthamaruthu'),('7278','7278-130','Sampath Bank - Pottuvil'),('7278','7278-135','Sampath Bank - Gangodawila'),('7278','7278-138','Sampath Bank - Nocchiyagama'),('7278','7278-140','Sampath Bank - Ingiriya'),('7278','7278-143','Anamaduwa'),('7278','7278-145','Sampath Bank - buttala'),('7278','7278-149','Sampath Bank - Kekirawa'),('7278','7278-150','Sampath Bank - Pilimatalawa'),('7278','7278-152','Sampath Bank - Pussellawa'),('7278','7278-153','Sampath Bank - Matara Bazar'),('7278','7278-154','Sampath Bank - Aralaganwila'),('7278','7278-156','Sampath Bank - Puttalam'),('7278','7278-157','Sampath Bank - Sooriyawewa'),('7278','7278-159','Sampath Bank - Galle Bazaar'),('7278','7278-160','Sampath Bank -'),('7278','7278-161','Sampath Bank - Bibile'),('7278','7278-167','Sampath Bank - Sandunpura'),('7278','7278-168','Sampath Bank -'),('7278','7278-187','Sampath Bank - Hettipola'),('7278','7278-188','Sampath Bank - Rambukkana'),('7278','7278-920','Sampath Bank - Trade Services Dept.'),('7278','7278-993','Sampath Bank - Central Clearing Dept'),('7278','7278-996','Sampath Bank - Card Centre'),('7287','7287-001','Seylan Bank - City Office'),('7287','7287-002','Seylan Bank - Matara'),('7287','7287-003','Seylan Bank - Mount Lavinia'),('7287','7287-004','Seylan Bank - Maharagama'),('7287','7287-005','Seylan Bank - Panadura'),('7287','7287-006','Seylan Bank - Kiribathgoda'),('7287','7287-007','Seylan Bank - Ratnapura'),('7287','7287-008','Seylan Bank - Kollupitiya'),('7287','7287-009','Seylan Bank - Moratuwa'),('7287','7287-010','Seylan Bank - Kegalle'),('7287','7287-011','Seylan Bank - Gampaha'),('7287','7287-012','Seylan Bank - Nugegoda'),('7287','7287-013','Seylan Bank - Negombo'),('7287','7287-014','Seylan Bank - Dehiwala'),('7287','7287-015','Seylan Bank - Chilaw'),('7287','7287-016','Seylan Bank - Galle'),('7287','7287-017','Seylan Bank - Kandy'),('7287','7287-018','Seylan Bank - Kurunegala'),('7287','7287-019','Seylan Bank - Nuwara Eliya'),('7287','7287-020','Seylan Bank - Balangoda'),('7287','7287-021','Seylan Bank - Anuradhapura'),('7287','7287-022','Seylan Bank - Grandpass'),('7287','7287-023','Seylan Bank - Horana'),('7287','7287-024','Seylan Bank - Ambalangoda'),('7287','7287-025','Seylan Bank - Gampola'),('7287','7287-026','Seylan Bank - Badulla'),('7287','7287-027','Seylan Bank - Ja-Ela'),('7287','7287-028','Seylan Bank - Kadawatha'),('7287','7287-029','Seylan Bank - Dehiattakandiya'),('7287','7287-030','Seylan Bank - (BCCI) U. Chatam St. Br'),('7287','7287-031','Seylan Bank - (BCCI) Free Trade Zone'),('7287','7287-032','Seylan Bank - Cinnamon Gardens'),('7287','7287-033','Seylan Bank - Kottawa'),('7287','7287-034','Seylan Bank - Boralesgamuwa'),('7287','7287-035','Seylan Bank - Yakkala'),('7287','7287-036','Seylan Bank - Kalutara'),('7287','7287-037','Seylan Bank - Thissamaharama'),('7287','7287-038','Seylan Bank - Matale'),('7287','7287-039','Seylan Bank - Hatton'),('7287','7287-040','Seylan Bank - Sarikkamulla'),('7287','7287-041','Seylan Bank - Attidiya'),('7287','7287-042','Seylan Bank - Kalubowila'),('7287','7287-043','Seylan Bank - Homagama'),('7287','7287-044','Seylan Bank - Kuliyapitiya'),('7287','7287-045','Seylan Bank - Embilipitiya'),('7287','7287-046','Seylan Bank - Bandarawela'),('7287','7287-047','Seylan Bank - Maradana'),('7287','7287-048','Seylan Bank - Mawanella'),('7287','7287-049','Seylan Bank - Puttalam'),('7287','7287-050','Seylan Bank - Old Moor Street'),('7287','7287-051','Seylan Bank - Higurakgoda'),('7287','7287-052','Seylan Bank - Nawalla'),('7287','7287-053','Seylan Bank - Manampitiya'),('7287','7287-054','Seylan Bank - Bandaragama'),('7287','7287-055','Seylan Bank - Katuneriya'),('7287','7287-056','Seylan Bank - Kaggala'),('7287','7287-057','Seylan Bank - Welimada'),('7287','7287-058','Seylan Bank - Kochchikade'),('7287','7287-059','Seylan Bank - Bogawanthalawa'),('7287','7287-060','Seylan Bank - Ganemulla'),('7287','7287-061','Seylan Bank - Thalawakele'),('7287','7287-062','Seylan Bank - Raddolugama'),('7287','7287-063','Seylan Bank - Weliveriya'),('7287','7287-064','Seylan Bank - Pettah'),('7287','7287-065','Seylan Bank - Baliatta'),('7287','7287-066','Seylan Bank - Mathugama'),('7287','7287-067','Seylan Bank - Malabe'),('7287','7287-068','Seylan Bank - Colombo South'),('7287','7287-070','Seylan Bank - Warakapola'),('7287','7287-071','Seylan Bank - Wattala'),('7287','7287-072','Seylan Bank - Vavuniya'),('7287','7287-073','Seylan Bank - Batticaloa'),('7287','7287-074','Seylan Bank - Kaththankudy'),('7287','7287-075','Seylan Bank - Avissawella'),('7287','7287-076','Seylan Bank - Nawalapitiya'),('7287','7287-077','Seylan Bank - Kekirawa'),('7287','7287-078','Seylan Bank - Mirigama'),('7287','7287-079','Seylan Bank - Soysapura'),('7287','7287-080','Seylan Bank - Ruwanwella'),('7287','7287-081','Seylan Bank - Hambantota'),('7287','7287-082','Seylan Bank - Borella'),('7287','7287-083','Seylan Bank - Havelock Town'),('7287','7287-084','Seylan Bank - Marandagahamulla'),('7287','7287-085','Seylan Bank - Jaffna'),('7287','7287-086','Seylan Bank - Millenium'),('7287','7287-087','Seylan Bank - Nittambuwa'),('7287','7287-088','Seylan Bank - Trincomalee'),('7287','7287-089','Seylan Bank - Meegoda'),('7287','7287-090','Seylan Bank - Pelmadulla'),('7287','7287-091','Seylan Bank - Ampara'),('7287','7287-999','Seylan Bank - Other Branches'),('7302','7302-001','Union Bank - Colombo'),('7311','7311-001','PABC - Metro'),('7311','7311-002','PABC - Panchikawatta'),('7311','7311-003','PABC - Lollupitiya'),('7311','7311-004','PABC - Pettah'),('7311','7311-005','PABC - Kandy'),('7311','7311-006','PABC - Rajagiriya'),('7311','7311-007','PABC - Ratnapura'),('7311','7311-008','PABC - Nugegoda'),('7311','7311-009','PABC - Bambalapitiya'),('7311','7311-010','PABC - Negombo'),('7311','7311-011','PABC - Gampaha'),('7311','7311-012','PABC - Kurunegala'),('7311','7311-013','PABC - Matara'),('7311','7311-014','PABC - Kotahena'),('7311','7311-015','PABC - Dehiwala'),('7311','7311-016','PABC - Wattala'),('7311','7311-017','PABC - Panadura'),('7311','7311-018','PABC - Old Moor Street'),('7311','7311-019','PABC - Dam Street'),('7311','7311-020','PABC - Katugastota'),('7311','7311-021','PABC - Narahenpita'),('7311','7311-022','PABC - Kirulapone'),('7311','7311-023','PABC - Maharagama'),('7311','7311-024','PABC - Moratuwa'),('7311','7311-025','PABC - Galle'),('7311','7311-027','PABC - Kegalle'),('7311','7311-029','PABC - Wallawatta'),('7311','7311-032','PABC - Anuradhapura'),('7311','7311-033','PABC - Kalutara'),('7311','7311-035','PABC - Malabe'),('7311','7311-036','PABC - Chilaw'),('7311','7311-038','PABC - Embilipitiya'),('7311','7311-039','PABC - Matale'),('7311','7311-040','PABC - Batti'),('7311','7311-041','PABC - Ambalangoda'),('7311','7311-042','PABC - Kalmunai'),('7311','7311-043','PABC - Ambalangoda'),('7311','7311-045','PABC - Badulla'),('7384','7384-001','ICICI Bank - Colombo'),('7454','7454-001','DFCC Bank - Head Office'),('7454','7454-002','DFCC Bank - Gangodawila'),('7454','7454-003','DFCC Bank - Malabe'),('7454','7454-004','DFCC Bank - Matara'),('7454','7454-005','DFCC Bank - Kurunegala'),('7454','7454-006','DFCC Bank - Kandy'),('7454','7454-007','DFCC Bank - City Office'),('7454','7454-008','DFCC Bank - Ratnapura'),('7454','7454-009','DFCC Bank - Anuradhapura'),('7454','7454-010','DFCC Bank - Gampaha'),('7454','7454-011','DFCC Bank - Badulla'),('7454','7454-012','DFCC Bank - Borella'),('7454','7454-014','DFCC Bank - Kaduruwela'),('7454','7454-015','DFCC Bank - Kotahena'),('7454','7454-016','DFCC Bank - Maharagama'),('7454','7454-017','DFCC Bank - Bandarawela'),('7454','7454-018','DFCC Bank - Negombo'),('7454','7454-019','DFCC Bank - Kottawa'),('7454','7454-020','DFCC Bank - Dambulla'),('7454','7454-021','DFCC Bank - Wattala'),('7454','7454-022','DFCC Bank - Kuliyapitiya'),('7454','7454-023','DFCC Bank - Panadura'),('7454','7454-024','DFCC Bank - Piliyandala'),('7454','7454-025','DFCC Bank - Deniyaya'),('7454','7454-026','DFCC Bank - Lalutara'),('7454','7454-028','DFCC Bank - Nawala'),('7454','7454-031','DFCC Bank - Matale'),('7454','7454-032','DFCC Bank - Chilaw'),('7454','7454-034','DFCC Bank - Horana'),('7454','7454-035','DFCC Bank - Galle'),('7454','7454-036','DFCC Bank - Nuwaraeliya'),('7454','7454-037','DFCC Bank - KALAWANA'),('7454','7454-038','DFCC Bank - NAWALA'),('7454','7454-040','DFCC Bank - Batticaloa'),('7454','7454-041','DFCC Bank - Ampara'),('7454','7454-042','DFCC Bank - Jafna'),('7454','7454-044','DFCC Bank - Trinco'),('7454','7454-045','DFCC Bank - Embilipitiya');

/*Table structure for table `m_banks` */

DROP TABLE IF EXISTS `m_banks`;

CREATE TABLE `m_banks` (
  `code` varchar(10) NOT NULL,
  `bank_name` varchar(100) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_banks` */

insert  into `m_banks`(`code`,`bank_name`,`action_date`) values ('7010','Bank Of Ceylon','2012-02-16 19:54:52'),('7038','Standard Chartered Bank','2012-02-16 19:54:52'),('7047','City Bank','2012-02-16 19:54:52'),('7056','Commercial Bank','2012-02-16 19:54:52'),('7074','HBL','2012-02-16 19:54:52'),('7083','HNB','2012-02-16 19:54:52'),('7092','HSBC','2012-02-16 19:54:52'),('7135','Peoples Bank','2012-02-16 19:54:52'),('7162','Nation Trust','2012-02-16 19:54:52'),('7214','NDB','2012-02-16 19:54:52'),('7269','Islamic Bank','2012-02-16 19:54:52'),('7278','Sampath Bank','2012-02-16 19:54:52'),('7287','Seylan Bank','2012-02-16 19:54:52'),('7302','Union Bank','2012-02-16 19:54:52'),('7311','PABC','2012-02-16 19:54:52'),('7384','ICICI Bank','2012-02-16 19:54:52'),('7454','DFCC Bank','2012-02-16 19:54:52');

/*Table structure for table `m_branches` */

DROP TABLE IF EXISTS `m_branches`;

CREATE TABLE `m_branches` (
  `cCode` varchar(5) NOT NULL,
  `branchName` varchar(100) NOT NULL,
  `address01` varchar(200) DEFAULT NULL,
  `address02` varchar(100) DEFAULT NULL,
  `phone01` varchar(12) DEFAULT NULL,
  `phone02` varchar(12) DEFAULT NULL,
  `phone03` varchar(12) DEFAULT NULL,
  `Email` varchar(50) DEFAULT NULL,
  `br_id` varchar(3) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_branches` */

insert  into `m_branches`(`cCode`,`branchName`,`address01`,`address02`,`phone01`,`phone02`,`phone03`,`Email`,`br_id`,`action_date`) values ('001','SUN MATCH COMPANY (Pvt) LTD','BAMUNUPOLAWATTA','KUNDASALE','081-4472555-','081-242022','081-2420435','soorya@slt.lk','001','0000-00-00 00:00:00'),('002','uggg','gggg','hiiiiig','7987979','9779797','79797979','ihihhihihi+','','2012-02-09 15:09:48');

/*Table structure for table `m_company` */

DROP TABLE IF EXISTS `m_company`;

CREATE TABLE `m_company` (
  `name` varchar(200) DEFAULT NULL,
  `address01` varchar(100) DEFAULT NULL,
  `address02` varchar(100) DEFAULT NULL,
  `address03` varchar(100) DEFAULT NULL,
  `phone01` varchar(12) DEFAULT NULL,
  `phone02` varchar(12) DEFAULT NULL,
  `phone03` varchar(12) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_company` */

insert  into `m_company`(`name`,`address01`,`address02`,`address03`,`phone01`,`phone02`,`phone03`,`email`) values ('JANASIRI DISTRIBUTION(PVT)LTD','','','','','','','');

/*Table structure for table `m_competitors` */

DROP TABLE IF EXISTS `m_competitors`;

CREATE TABLE `m_competitors` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_competitors` */

/*Table structure for table `m_customer` */

DROP TABLE IF EXISTS `m_customer`;

CREATE TABLE `m_customer` (
  `code` varchar(15) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address01` varchar(255) NOT NULL,
  `address02` varchar(255) NOT NULL,
  `address03` varchar(255) NOT NULL,
  `dob` date NOT NULL,
  `outlet_name` varchar(255) NOT NULL,
  `phone01` varchar(10) NOT NULL,
  `phone02` varchar(10) NOT NULL,
  `phone03` varchar(10) NOT NULL,
  `root` varchar(10) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `b` int(11) DEFAULT NULL,
  `d` int(11) DEFAULT NULL,
  `g` int(11) DEFAULT NULL,
  `officer` decimal(10,2) DEFAULT '0.00',
  `company` decimal(10,2) DEFAULT '0.00',
  `margin` decimal(10,2) DEFAULT '0.00',
  `tx_r` tinyint(4) DEFAULT '0',
  `tx_no` varchar(25) DEFAULT NULL,
  `period` smallint(6) DEFAULT '0',
  `l1` decimal(10,2) DEFAULT '0.00',
  `l2` decimal(10,2) DEFAULT '0.00',
  `l3` decimal(10,2) DEFAULT '0.00',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`),
  KEY `root` (`root`),
  CONSTRAINT `m_customer_ibfk_1` FOREIGN KEY (`root`) REFERENCES `m_root` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_customer` */

/*Table structure for table `m_department` */

DROP TABLE IF EXISTS `m_department`;

CREATE TABLE `m_department` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_department` */

/*Table structure for table `m_email` */

DROP TABLE IF EXISTS `m_email`;

CREATE TABLE `m_email` (
  `femail` varchar(255) DEFAULT NULL,
  `email` text
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_email` */

/*Table structure for table `m_item_rate` */

DROP TABLE IF EXISTS `m_item_rate`;

CREATE TABLE `m_item_rate` (
  `item_code` varchar(10) NOT NULL,
  `unit_code` varchar(10) NOT NULL,
  `pur_price` decimal(10,2) DEFAULT NULL,
  `min_sale_pri` decimal(10,2) DEFAULT NULL,
  `max_sale_pri` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`item_code`,`unit_code`),
  KEY `unit_code` (`unit_code`),
  CONSTRAINT `m_item_rate_ibfk_1` FOREIGN KEY (`unit_code`) REFERENCES `m_units` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_item_rate` */

/*Table structure for table `m_items` */

DROP TABLE IF EXISTS `m_items`;

CREATE TABLE `m_items` (
  `department` varchar(10) NOT NULL,
  `main_cat` varchar(10) NOT NULL,
  `sub_cat` varchar(10) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `units` varchar(10) NOT NULL,
  `purchase_price` decimal(10,2) NOT NULL,
  `cost_price` decimal(10,2) NOT NULL,
  `is_measure` tinyint(4) DEFAULT '0',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`),
  KEY `units_code` (`units`),
  KEY `main_category_code` (`main_cat`),
  KEY `sub_category_code` (`sub_cat`),
  KEY `department_code` (`department`),
  CONSTRAINT `m_items_ibfk_1` FOREIGN KEY (`department`) REFERENCES `m_department` (`code`),
  CONSTRAINT `m_items_ibfk_2` FOREIGN KEY (`main_cat`) REFERENCES `m_main_category` (`code`),
  CONSTRAINT `m_items_ibfk_3` FOREIGN KEY (`sub_cat`) REFERENCES `m_sub_category` (`code`),
  CONSTRAINT `m_items_ibfk_4` FOREIGN KEY (`units`) REFERENCES `m_units` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_items` */

/*Table structure for table `m_main_category` */

DROP TABLE IF EXISTS `m_main_category`;

CREATE TABLE `m_main_category` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_main_category` */

/*Table structure for table `m_main_region` */

DROP TABLE IF EXISTS `m_main_region`;

CREATE TABLE `m_main_region` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `manager` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`),
  KEY `manager` (`manager`),
  CONSTRAINT `m_main_region_ibfk_1` FOREIGN KEY (`manager`) REFERENCES `m_person` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_main_region` */

/*Table structure for table `m_main_regon` */

DROP TABLE IF EXISTS `m_main_regon`;

CREATE TABLE `m_main_regon` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_main_regon` */

insert  into `m_main_regon`(`code`,`description`,`action_date`) values ('CTL','CENTREL PROVINCE','2012-08-29 03:24:06');

/*Table structure for table `m_person` */

DROP TABLE IF EXISTS `m_person`;

CREATE TABLE `m_person` (
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address01` varchar(255) NOT NULL,
  `address02` varchar(255) NOT NULL,
  `address03` varchar(255) NOT NULL,
  `phone01` varchar(10) NOT NULL,
  `phone02` varchar(10) NOT NULL,
  `phone03` varchar(10) NOT NULL,
  `type` int(11) NOT NULL,
  `dateOfJoin` date DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_person` */

/*Table structure for table `m_reason` */

DROP TABLE IF EXISTS `m_reason`;

CREATE TABLE `m_reason` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_reason` */

/*Table structure for table `m_region` */

DROP TABLE IF EXISTS `m_region`;

CREATE TABLE `m_region` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `main_region` varchar(10) NOT NULL,
  `regional_manager` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`),
  KEY `regional_manager` (`regional_manager`),
  KEY `main_region` (`main_region`),
  CONSTRAINT `m_region_ibfk_1` FOREIGN KEY (`main_region`) REFERENCES `m_main_region` (`code`),
  CONSTRAINT `m_region_ibfk_2` FOREIGN KEY (`regional_manager`) REFERENCES `m_person` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_region` */

/*Table structure for table `m_root` */

DROP TABLE IF EXISTS `m_root`;

CREATE TABLE `m_root` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `area` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`),
  KEY `area` (`area`),
  CONSTRAINT `m_root_ibfk_1` FOREIGN KEY (`area`) REFERENCES `m_area` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_root` */

insert  into `m_root`(`code`,`description`,`area`,`action_date`) values ('KDY01-01','KANDY TOWN -PERADENIYA','KDY01','2012-08-29 04:06:00');

/*Table structure for table `m_sales_ref` */

DROP TABLE IF EXISTS `m_sales_ref`;

CREATE TABLE `m_sales_ref` (
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `address01` varchar(255) NOT NULL,
  `address02` varchar(255) NOT NULL,
  `address03` varchar(255) NOT NULL,
  `phone01` varchar(10) NOT NULL,
  `phone02` varchar(10) NOT NULL,
  `phone03` varchar(10) NOT NULL,
  `type` int(11) NOT NULL,
  `dateOfJoin` date DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_sales_ref` */

insert  into `m_sales_ref`(`code`,`name`,`address01`,`address02`,`address03`,`phone01`,`phone02`,`phone03`,`type`,`dateOfJoin`,`action_date`) values ('PG','PRIYANTHA GAMAGE','No','Street','City','0777660190','Office','Fax',0,'2012-08-29','2012-08-29 03:21:56');

/*Table structure for table `m_stores` */

DROP TABLE IF EXISTS `m_stores`;

CREATE TABLE `m_stores` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `is_sale` tinyint(4) NOT NULL,
  `is_purchase` tinyint(4) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_stores` */

/*Table structure for table `m_sub_category` */

DROP TABLE IF EXISTS `m_sub_category`;

CREATE TABLE `m_sub_category` (
  `main_cat` varchar(10) NOT NULL,
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`),
  KEY `main_category_code` (`main_cat`),
  CONSTRAINT `m_sub_category_ibfk_1` FOREIGN KEY (`main_cat`) REFERENCES `m_main_category` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_sub_category` */

/*Table structure for table `m_sub_regon` */

DROP TABLE IF EXISTS `m_sub_regon`;

CREATE TABLE `m_sub_regon` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `main_region` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`),
  KEY `main_region` (`main_region`),
  CONSTRAINT `m_sub_regon_ibfk_3` FOREIGN KEY (`main_region`) REFERENCES `m_main_regon` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_sub_regon` */

insert  into `m_sub_regon`(`code`,`description`,`main_region`,`action_date`) values ('KDY','KANDY ','CTL','2012-08-29 03:34:34');

/*Table structure for table `m_supplier` */

DROP TABLE IF EXISTS `m_supplier`;

CREATE TABLE `m_supplier` (
  `code` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address01` varchar(255) NOT NULL,
  `address02` varchar(255) NOT NULL,
  `address03` varchar(255) NOT NULL,
  `phone01` varchar(10) NOT NULL,
  `phone02` varchar(10) NOT NULL,
  `phone03` varchar(10) NOT NULL,
  `nic` varchar(20) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_supplier` */

/*Table structure for table `m_units` */

DROP TABLE IF EXISTS `m_units`;

CREATE TABLE `m_units` (
  `code` varchar(10) NOT NULL,
  `description` varchar(255) NOT NULL,
  `base_unit` varchar(10) DEFAULT NULL,
  `base_nos` int(11) DEFAULT NULL,
  `nos` int(11) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `m_units` */

/*Table structure for table `t_cheque` */

DROP TABLE IF EXISTS `t_cheque`;

CREATE TABLE `t_cheque` (
  `no` bigint(20) NOT NULL AUTO_INCREMENT,
  `transe_type` varchar(20) DEFAULT NULL,
  `transe_no` bigint(20) DEFAULT NULL,
  `bank_code` varchar(10) DEFAULT NULL,
  `cheque_no` varchar(50) DEFAULT NULL,
  `rez_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bc` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`no`),
  KEY `bank_code` (`bank_code`),
  KEY `bc` (`bc`),
  CONSTRAINT `t_cheque_ibfk_1` FOREIGN KEY (`bank_code`) REFERENCES `m_banks` (`code`),
  CONSTRAINT `t_cheque_ibfk_2` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_cheque` */

/*Table structure for table `t_cus_cheque` */

DROP TABLE IF EXISTS `t_cus_cheque`;

CREATE TABLE `t_cus_cheque` (
  `no` bigint(20) NOT NULL AUTO_INCREMENT,
  `transe_type` varchar(20) DEFAULT NULL,
  `transe_no` bigint(20) DEFAULT NULL,
  `bank_code` varchar(10) DEFAULT NULL,
  `branch_code` varchar(10) DEFAULT NULL,
  `cheque_no` varchar(50) DEFAULT NULL,
  `acc_no` varchar(50) DEFAULT NULL,
  `rez_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bc` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`no`),
  KEY `bank_code` (`bank_code`),
  KEY `branch_code` (`branch_code`),
  KEY `bc` (`bc`),
  CONSTRAINT `t_cus_cheque_ibfk_1` FOREIGN KEY (`bank_code`) REFERENCES `m_banks` (`code`),
  CONSTRAINT `t_cus_cheque_ibfk_2` FOREIGN KEY (`branch_code`) REFERENCES `m_bank_branch` (`code`),
  CONSTRAINT `t_cus_cheque_ibfk_3` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_cus_cheque` */

/*Table structure for table `t_cus_rcp_det` */

DROP TABLE IF EXISTS `t_cus_rcp_det`;

CREATE TABLE `t_cus_rcp_det` (
  `No` bigint(20) NOT NULL,
  `bc` varchar(10) NOT NULL,
  `inv_no` bigint(20) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `paid` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`No`,`bc`,`inv_no`),
  KEY `inv_no` (`inv_no`),
  KEY `bc` (`bc`),
  CONSTRAINT `t_cus_rcp_det_ibfk_3` FOREIGN KEY (`No`) REFERENCES `t_cus_rcp_sum` (`No`),
  CONSTRAINT `t_cus_rcp_det_ibfk_4` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_cus_rcp_det_ibfk_5` FOREIGN KEY (`inv_no`) REFERENCES `t_cus_sale_sum` (`No`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_cus_rcp_det` */

/*Table structure for table `t_cus_rcp_sum` */

DROP TABLE IF EXISTS `t_cus_rcp_sum`;

CREATE TABLE `t_cus_rcp_sum` (
  `No` bigint(20) NOT NULL,
  `bc` varchar(10) NOT NULL,
  `dDate` date DEFAULT NULL,
  `ref_no` varchar(25) DEFAULT NULL,
  `cash_a` decimal(10,2) DEFAULT NULL,
  `cheque_a` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `customer_id` varchar(20) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `oc` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`No`,`bc`),
  KEY `customer_id` (`customer_id`),
  KEY `bc` (`bc`),
  KEY `oc` (`oc`),
  CONSTRAINT `t_cus_rcp_sum_ibfk_2` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_cus_rcp_sum_ibfk_3` FOREIGN KEY (`customer_id`) REFERENCES `m_customer` (`code`),
  CONSTRAINT `t_cus_rcp_sum_ibfk_4` FOREIGN KEY (`oc`) REFERENCES `a_users` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_cus_rcp_sum` */

/*Table structure for table `t_cus_sale_det` */

DROP TABLE IF EXISTS `t_cus_sale_det`;

CREATE TABLE `t_cus_sale_det` (
  `bc` varchar(10) NOT NULL,
  `No` bigint(20) NOT NULL,
  `itemCode` varchar(10) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quntity` int(11) DEFAULT NULL,
  `units` varchar(10) NOT NULL,
  PRIMARY KEY (`bc`,`No`,`itemCode`,`units`),
  KEY `units` (`units`),
  KEY `No` (`No`),
  KEY `itemCode` (`itemCode`),
  CONSTRAINT `t_cus_sale_det_ibfk_3` FOREIGN KEY (`No`) REFERENCES `t_cus_sale_sum` (`No`),
  CONSTRAINT `t_cus_sale_det_ibfk_4` FOREIGN KEY (`itemCode`) REFERENCES `m_items` (`code`),
  CONSTRAINT `t_cus_sale_det_ibfk_5` FOREIGN KEY (`units`) REFERENCES `m_units` (`code`),
  CONSTRAINT `t_cus_sale_det_ibfk_6` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_cus_sale_det` */

/*Table structure for table `t_cus_sale_sum` */

DROP TABLE IF EXISTS `t_cus_sale_sum`;

CREATE TABLE `t_cus_sale_sum` (
  `No` bigint(20) NOT NULL,
  `storeId` varchar(10) DEFAULT NULL,
  `customerId` varchar(20) DEFAULT NULL,
  `resonId` varchar(10) DEFAULT NULL,
  `dDate` date DEFAULT NULL,
  `refNo` varchar(100) DEFAULT NULL,
  `cash` decimal(10,2) DEFAULT NULL,
  `cheque` decimal(10,2) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `bc` varchar(10) NOT NULL DEFAULT '',
  `oc` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `root` varchar(10) DEFAULT NULL,
  `ret_id` varchar(20) DEFAULT '0',
  `salesman` varchar(10) DEFAULT NULL,
  `cordinator` varchar(10) DEFAULT NULL,
  `manager` varchar(10) DEFAULT NULL,
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`No`,`bc`),
  KEY `storeId` (`storeId`),
  KEY `customerId` (`customerId`),
  KEY `resonId` (`resonId`),
  KEY `sales_manager` (`salesman`),
  KEY `regional_manager` (`cordinator`),
  KEY `manager` (`manager`),
  KEY `bc` (`bc`),
  KEY `oc` (`oc`),
  CONSTRAINT `t_cus_sale_sum_ibfk_3` FOREIGN KEY (`storeId`) REFERENCES `m_stores` (`code`),
  CONSTRAINT `t_cus_sale_sum_ibfk_4` FOREIGN KEY (`customerId`) REFERENCES `m_customer` (`code`),
  CONSTRAINT `t_cus_sale_sum_ibfk_5` FOREIGN KEY (`resonId`) REFERENCES `m_reason` (`code`),
  CONSTRAINT `t_cus_sale_sum_ibfk_6` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_cus_sale_sum_ibfk_7` FOREIGN KEY (`oc`) REFERENCES `a_users` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_cus_sale_sum` */

/*Table structure for table `t_cus_sale_trance` */

DROP TABLE IF EXISTS `t_cus_sale_trance`;

CREATE TABLE `t_cus_sale_trance` (
  `no` bigint(20) NOT NULL AUTO_INCREMENT,
  `customer` varchar(20) NOT NULL,
  `dr_trnce_code` varchar(10) DEFAULT NULL,
  `dr_trnce_no` bigint(20) DEFAULT NULL,
  `cr_trnce_code` varchar(10) DEFAULT NULL,
  `cr_trnce_no` bigint(20) DEFAULT NULL,
  `dr_amount` decimal(10,2) DEFAULT NULL,
  `cr_amount` decimal(10,2) DEFAULT NULL,
  `trance_type` varchar(10) DEFAULT NULL,
  `trance_no` bigint(20) DEFAULT NULL,
  `bc` varchar(10) DEFAULT NULL,
  `oc` varchar(10) DEFAULT NULL,
  `dDate` date DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`),
  KEY `customer` (`customer`),
  KEY `bc` (`bc`),
  KEY `oc` (`oc`),
  CONSTRAINT `t_cus_sale_trance_ibfk_1` FOREIGN KEY (`customer`) REFERENCES `m_customer` (`code`),
  CONSTRAINT `t_cus_sale_trance_ibfk_2` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_cus_sale_trance_ibfk_3` FOREIGN KEY (`oc`) REFERENCES `a_users` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_cus_sale_trance` */

/*Table structure for table `t_cus_salses_ord_detail` */

DROP TABLE IF EXISTS `t_cus_salses_ord_detail`;

CREATE TABLE `t_cus_salses_ord_detail` (
  `no` varchar(20) DEFAULT NULL,
  `cus_code` varchar(20) DEFAULT NULL,
  `item_code` varchar(20) DEFAULT NULL,
  `qty` int(10) DEFAULT NULL,
  `rate` decimal(10,0) DEFAULT '0',
  KEY `NewIndex1` (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_cus_salses_ord_detail` */

/*Table structure for table `t_cus_salses_ord_sum` */

DROP TABLE IF EXISTS `t_cus_salses_ord_sum`;

CREATE TABLE `t_cus_salses_ord_sum` (
  `no` varchar(20) NOT NULL,
  `customer_code` varchar(20) NOT NULL,
  `ref_no` varchar(20) DEFAULT NULL,
  `dDate` date DEFAULT NULL,
  `discription` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`no`,`customer_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_cus_salses_ord_sum` */

/*Table structure for table `t_cus_salses_ord_trance` */

DROP TABLE IF EXISTS `t_cus_salses_ord_trance`;

CREATE TABLE `t_cus_salses_ord_trance` (
  `cus_code` varchar(20) DEFAULT NULL,
  `dDate` date DEFAULT NULL,
  `ord_trans_code` varchar(20) DEFAULT NULL,
  `ord_trans_no` varchar(20) DEFAULT NULL,
  `ord_qty` int(10) DEFAULT NULL,
  `item_code` varchar(20) DEFAULT NULL,
  `del_trans_code` varchar(20) DEFAULT NULL,
  `del_trans_no` varchar(20) DEFAULT NULL,
  `del_qty` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_cus_salses_ord_trance` */

/*Table structure for table `t_del_cheque` */

DROP TABLE IF EXISTS `t_del_cheque`;

CREATE TABLE `t_del_cheque` (
  `no` bigint(20) NOT NULL AUTO_INCREMENT,
  `transe_type` varchar(20) DEFAULT NULL,
  `transe_no` bigint(20) DEFAULT NULL,
  `bank_code` varchar(10) DEFAULT NULL,
  `branch_code` varchar(10) DEFAULT NULL,
  `cheque_no` varchar(50) DEFAULT NULL,
  `acc_no` varchar(50) DEFAULT NULL,
  `rez_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `bc` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`no`),
  KEY `bank_code` (`bank_code`),
  KEY `branch_code` (`branch_code`),
  KEY `bc` (`bc`),
  CONSTRAINT `t_del_cheque_ibfk_3` FOREIGN KEY (`bank_code`) REFERENCES `m_banks` (`code`),
  CONSTRAINT `t_del_cheque_ibfk_4` FOREIGN KEY (`branch_code`) REFERENCES `m_bank_branch` (`code`),
  CONSTRAINT `t_del_cheque_ibfk_5` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_del_cheque` */

/*Table structure for table `t_del_debit_note` */

DROP TABLE IF EXISTS `t_del_debit_note`;

CREATE TABLE `t_del_debit_note` (
  `no` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `ref_no` varbinary(25) DEFAULT NULL,
  `agent` varbinary(10) DEFAULT NULL,
  `discription` varbinary(150) DEFAULT NULL,
  `dispatch_no` bigint(20) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `bc` varbinary(10) NOT NULL,
  `oc` varbinary(10) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`,`bc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_del_debit_note` */

/*Table structure for table `t_del_rcp_det` */

DROP TABLE IF EXISTS `t_del_rcp_det`;

CREATE TABLE `t_del_rcp_det` (
  `No` bigint(20) NOT NULL,
  `bc` varchar(10) NOT NULL,
  `inv_no` bigint(20) NOT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `paid` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`No`,`bc`,`inv_no`),
  KEY `inv_no` (`inv_no`),
  KEY `bc` (`bc`),
  CONSTRAINT `t_del_rcp_det_ibfk_6` FOREIGN KEY (`No`) REFERENCES `t_del_rcp_sum` (`No`),
  CONSTRAINT `t_del_rcp_det_ibfk_7` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_del_rcp_det_ibfk_8` FOREIGN KEY (`inv_no`) REFERENCES `t_dispatch_sum` (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_del_rcp_det` */

/*Table structure for table `t_del_rcp_sum` */

DROP TABLE IF EXISTS `t_del_rcp_sum`;

CREATE TABLE `t_del_rcp_sum` (
  `No` bigint(20) NOT NULL,
  `bc` varchar(10) NOT NULL,
  `dDate` date DEFAULT NULL,
  `ref_no` varchar(25) DEFAULT NULL,
  `cash_a` decimal(10,2) DEFAULT NULL,
  `cheque_a` decimal(10,2) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT NULL,
  `agent_id` varchar(10) NOT NULL,
  `description` varchar(100) DEFAULT NULL,
  `action_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `oc` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`No`,`bc`),
  KEY `agent_id` (`agent_id`),
  KEY `bc` (`bc`),
  KEY `oc` (`oc`),
  CONSTRAINT `t_del_rcp_sum_ibfk_1` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_del_rcp_sum_ibfk_2` FOREIGN KEY (`agent_id`) REFERENCES `m_agent` (`code`),
  CONSTRAINT `t_del_rcp_sum_ibfk_3` FOREIGN KEY (`oc`) REFERENCES `a_users` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_del_rcp_sum` */

/*Table structure for table `t_dispatch_det` */

DROP TABLE IF EXISTS `t_dispatch_det`;

CREATE TABLE `t_dispatch_det` (
  `no` bigint(20) NOT NULL,
  `item_code` varchar(10) NOT NULL,
  `units` varchar(10) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL,
  `bc` varchar(10) NOT NULL,
  PRIMARY KEY (`no`,`item_code`,`units`,`bc`),
  KEY `item_code` (`item_code`),
  KEY `units` (`units`),
  KEY `bc` (`bc`),
  CONSTRAINT `t_dispatch_det_ibfk_1` FOREIGN KEY (`item_code`) REFERENCES `m_items` (`code`),
  CONSTRAINT `t_dispatch_det_ibfk_2` FOREIGN KEY (`units`) REFERENCES `m_units` (`code`),
  CONSTRAINT `t_dispatch_det_ibfk_3` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_dispatch_det_ibfk_4` FOREIGN KEY (`no`) REFERENCES `t_dispatch_sum` (`no`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_dispatch_det` */

/*Table structure for table `t_dispatch_ret_det` */

DROP TABLE IF EXISTS `t_dispatch_ret_det`;

CREATE TABLE `t_dispatch_ret_det` (
  `bc` varchar(10) NOT NULL DEFAULT '',
  `no` bigint(20) NOT NULL DEFAULT '0',
  `item_code` varchar(10) NOT NULL DEFAULT '',
  `units` varchar(10) NOT NULL DEFAULT '',
  `quntity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`bc`,`no`,`item_code`,`units`),
  KEY `item_code` (`item_code`),
  KEY `units` (`units`),
  CONSTRAINT `t_dispatch_ret_det_ibfk_1` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_dispatch_ret_det_ibfk_2` FOREIGN KEY (`item_code`) REFERENCES `m_items` (`code`),
  CONSTRAINT `t_dispatch_ret_det_ibfk_3` FOREIGN KEY (`units`) REFERENCES `m_units` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_dispatch_ret_det` */

/*Table structure for table `t_dispatch_ret_sum` */

DROP TABLE IF EXISTS `t_dispatch_ret_sum`;

CREATE TABLE `t_dispatch_ret_sum` (
  `bc` varchar(10) NOT NULL DEFAULT '',
  `no` bigint(20) NOT NULL DEFAULT '0',
  `agent_code` varchar(10) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `oc` varchar(10) DEFAULT NULL,
  `actionDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `dispatch_no` bigint(20) DEFAULT NULL,
  `dDate` date DEFAULT NULL,
  `ref_no` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`bc`,`no`),
  KEY `agent_code` (`agent_code`),
  KEY `dispatch_no` (`dispatch_no`),
  KEY `oc` (`oc`),
  CONSTRAINT `t_dispatch_ret_sum_ibfk_1` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_dispatch_ret_sum_ibfk_2` FOREIGN KEY (`agent_code`) REFERENCES `m_agent` (`code`),
  CONSTRAINT `t_dispatch_ret_sum_ibfk_3` FOREIGN KEY (`dispatch_no`) REFERENCES `t_dispatch_sum` (`no`),
  CONSTRAINT `t_dispatch_ret_sum_ibfk_4` FOREIGN KEY (`oc`) REFERENCES `a_users` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_dispatch_ret_sum` */

/*Table structure for table `t_dispatch_sum` */

DROP TABLE IF EXISTS `t_dispatch_sum`;

CREATE TABLE `t_dispatch_sum` (
  `no` bigint(20) NOT NULL,
  `dDate` date NOT NULL,
  `ref_no` varchar(25) NOT NULL,
  `agent_id` varchar(10) NOT NULL,
  `discount` decimal(10,2) NOT NULL,
  `bc` varchar(10) NOT NULL,
  `oc` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `sales_manager` varchar(10) NOT NULL,
  `regional_manager` varchar(10) NOT NULL,
  `manager` varchar(10) NOT NULL,
  `po_no` bigint(20) DEFAULT '0',
  `description` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`no`,`bc`),
  KEY `sales_manager` (`sales_manager`),
  KEY `regional_manager` (`regional_manager`),
  KEY `manager` (`manager`),
  KEY `agency_id` (`agent_id`),
  KEY `po_no` (`po_no`),
  KEY `bc` (`bc`),
  KEY `oc` (`oc`),
  CONSTRAINT `t_dispatch_sum_ibfk_19` FOREIGN KEY (`agent_id`) REFERENCES `m_agent` (`code`),
  CONSTRAINT `t_dispatch_sum_ibfk_20` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_dispatch_sum_ibfk_21` FOREIGN KEY (`sales_manager`) REFERENCES `m_person` (`code`),
  CONSTRAINT `t_dispatch_sum_ibfk_22` FOREIGN KEY (`regional_manager`) REFERENCES `m_person` (`code`),
  CONSTRAINT `t_dispatch_sum_ibfk_23` FOREIGN KEY (`manager`) REFERENCES `m_person` (`code`),
  CONSTRAINT `t_dispatch_sum_ibfk_24` FOREIGN KEY (`oc`) REFERENCES `a_users` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_dispatch_sum` */

/*Table structure for table `t_dispatch_trance` */

DROP TABLE IF EXISTS `t_dispatch_trance`;

CREATE TABLE `t_dispatch_trance` (
  `no` bigint(20) NOT NULL AUTO_INCREMENT,
  `agent` varchar(10) NOT NULL,
  `dr_trnce_code` varchar(10) DEFAULT NULL,
  `dr_trnce_no` bigint(20) DEFAULT NULL,
  `cr_trnce_code` varchar(10) DEFAULT NULL,
  `cr_trnce_no` bigint(20) DEFAULT NULL,
  `dr_amount` decimal(10,2) DEFAULT NULL,
  `cr_amount` decimal(10,2) DEFAULT NULL,
  `trance_type` varchar(10) DEFAULT NULL,
  `trance_no` bigint(20) DEFAULT NULL,
  `bc` varchar(10) DEFAULT NULL,
  `oc` varchar(10) DEFAULT NULL,
  `dDate` date DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`),
  KEY `agent` (`agent`),
  KEY `bc` (`bc`),
  KEY `oc` (`oc`),
  CONSTRAINT `t_dispatch_trance_ibfk_1` FOREIGN KEY (`agent`) REFERENCES `m_agent` (`code`),
  CONSTRAINT `t_dispatch_trance_ibfk_2` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_dispatch_trance_ibfk_3` FOREIGN KEY (`oc`) REFERENCES `a_users` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_dispatch_trance` */

/*Table structure for table `t_item_movement` */

DROP TABLE IF EXISTS `t_item_movement`;

CREATE TABLE `t_item_movement` (
  `id` bigint(20) NOT NULL,
  `trance_id` bigint(20) NOT NULL,
  `trance_type` varchar(10) NOT NULL,
  `item_code` varchar(10) NOT NULL,
  `in_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `out_quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date` date NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `bc` varchar(10) NOT NULL,
  `pur_price` decimal(12,2) DEFAULT '0.00',
  `sal_price` decimal(12,2) DEFAULT '0.00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_item_movement` */

/*Table structure for table `t_monthly_target_det` */

DROP TABLE IF EXISTS `t_monthly_target_det`;

CREATE TABLE `t_monthly_target_det` (
  `no` bigint(20) NOT NULL,
  `area` varchar(10) NOT NULL,
  `t_level01` int(11) DEFAULT NULL,
  `t_level02` int(11) DEFAULT NULL,
  `t_level03` int(11) DEFAULT NULL,
  `bc` varchar(10) NOT NULL,
  PRIMARY KEY (`no`,`area`,`bc`),
  KEY `FK_t_monthly_target_det` (`area`),
  KEY `bc` (`bc`),
  CONSTRAINT `FK_t_monthly_target_det` FOREIGN KEY (`area`) REFERENCES `m_area` (`code`),
  CONSTRAINT `t_monthly_target_det_ibfk_1` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_monthly_target_det` */

/*Table structure for table `t_monthly_target_sum` */

DROP TABLE IF EXISTS `t_monthly_target_sum`;

CREATE TABLE `t_monthly_target_sum` (
  `no` bigint(20) NOT NULL,
  `date` date DEFAULT NULL,
  `oc` varchar(10) DEFAULT NULL,
  `bc` varchar(10) NOT NULL,
  `sdate` date DEFAULT NULL,
  `edate` date DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`no`,`bc`),
  KEY `oc` (`oc`),
  KEY `bc` (`bc`),
  CONSTRAINT `t_monthly_target_sum_ibfk_1` FOREIGN KEY (`oc`) REFERENCES `a_users` (`cCode`),
  CONSTRAINT `t_monthly_target_sum_ibfk_2` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_monthly_target_sum` */

/*Table structure for table `t_open_stock_det` */

DROP TABLE IF EXISTS `t_open_stock_det`;

CREATE TABLE `t_open_stock_det` (
  `id` bigint(20) unsigned NOT NULL,
  `item_code` varchar(10) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`,`item_code`),
  KEY `item_code` (`item_code`),
  CONSTRAINT `t_open_stock_det_ibfk_2` FOREIGN KEY (`id`) REFERENCES `t_open_stock_sum` (`id`),
  CONSTRAINT `t_open_stock_det_ibfk_3` FOREIGN KEY (`item_code`) REFERENCES `m_items` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_open_stock_det` */

/*Table structure for table `t_open_stock_sum` */

DROP TABLE IF EXISTS `t_open_stock_sum`;

CREATE TABLE `t_open_stock_sum` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `ref_no` varchar(25) NOT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `bc` varchar(10) NOT NULL,
  `oc` varchar(10) NOT NULL,
  `is_cancel` smallint(6) DEFAULT '0',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`no`,`bc`),
  KEY `agency_id` (`memo`),
  KEY `oc` (`oc`),
  KEY `bc` (`bc`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_open_stock_sum` */

/*Table structure for table `t_po_details` */

DROP TABLE IF EXISTS `t_po_details`;

CREATE TABLE `t_po_details` (
  `no` bigint(20) NOT NULL,
  `item_code` varchar(10) NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `price` float(10,2) DEFAULT NULL,
  `bc` varchar(10) NOT NULL,
  PRIMARY KEY (`no`,`item_code`,`bc`),
  KEY `item_code` (`item_code`),
  KEY `FK_t_po_details` (`no`,`bc`),
  CONSTRAINT `FK_t_po_details` FOREIGN KEY (`no`, `bc`) REFERENCES `t_po_sum` (`no`, `bc`) ON DELETE CASCADE,
  CONSTRAINT `t_po_details_ibfk_1` FOREIGN KEY (`item_code`) REFERENCES `m_items` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_po_details` */

/*Table structure for table `t_po_sum` */

DROP TABLE IF EXISTS `t_po_sum`;

CREATE TABLE `t_po_sum` (
  `no` bigint(20) NOT NULL,
  `dDate` date DEFAULT NULL,
  `ref_no` varchar(10) DEFAULT NULL,
  `supplier_code` varchar(10) DEFAULT NULL,
  `bc` varchar(10) NOT NULL,
  `oc` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`no`,`bc`),
  KEY `supplier_code` (`supplier_code`),
  CONSTRAINT `t_po_sum_ibfk_1` FOREIGN KEY (`supplier_code`) REFERENCES `m_supplier` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_po_sum` */

/*Table structure for table `t_po_trans` */

DROP TABLE IF EXISTS `t_po_trans`;

CREATE TABLE `t_po_trans` (
  `sup_code` varchar(20) DEFAULT NULL,
  `dDate` date DEFAULT NULL,
  `ord_trans_code` varchar(20) DEFAULT NULL,
  `ord_trans_no` varchar(20) DEFAULT NULL,
  `ord_qty` int(10) DEFAULT NULL,
  `item_code` varchar(20) DEFAULT NULL,
  `rec_trans_code` varchar(20) DEFAULT NULL,
  `rec_trans_no` varchar(20) DEFAULT NULL,
  `rec_qty` int(10) DEFAULT NULL,
  `bc` varchar(20) DEFAULT NULL,
  `oc` varchar(20) DEFAULT NULL,
  KEY `item_code` (`item_code`),
  KEY `FK_t_po_trans` (`sup_code`),
  CONSTRAINT `FK_t_po_trans` FOREIGN KEY (`sup_code`) REFERENCES `m_supplier` (`code`),
  CONSTRAINT `t_po_trans_ibfk_2` FOREIGN KEY (`item_code`) REFERENCES `m_items` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_po_trans` */

/*Table structure for table `t_purchase_detail` */

DROP TABLE IF EXISTS `t_purchase_detail`;

CREATE TABLE `t_purchase_detail` (
  `no` int(11) NOT NULL,
  `item_code` varchar(10) NOT NULL,
  `qty` int(11) DEFAULT NULL,
  `po_qty` int(11) DEFAULT NULL,
  `rate` decimal(12,2) DEFAULT NULL,
  `discount_value` decimal(12,2) DEFAULT NULL,
  `discount_rate` decimal(12,2) DEFAULT NULL,
  `bc` varchar(10) NOT NULL,
  PRIMARY KEY (`no`,`item_code`,`bc`),
  KEY `item_code` (`item_code`),
  KEY `FK_t_purchase_detail` (`no`,`bc`),
  CONSTRAINT `t_purchase_detail_ibfk_2` FOREIGN KEY (`item_code`) REFERENCES `m_items` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_purchase_detail` */

/*Table structure for table `t_purchase_sum` */

DROP TABLE IF EXISTS `t_purchase_sum`;

CREATE TABLE `t_purchase_sum` (
  `no` int(11) NOT NULL,
  `dDate` date DEFAULT NULL,
  `ref_no` varchar(10) DEFAULT NULL,
  `po_no` varchar(10) DEFAULT NULL,
  `supplier_code` varchar(10) DEFAULT NULL,
  `invoice_no` int(11) DEFAULT NULL,
  `bill_discount` decimal(12,2) DEFAULT NULL,
  `bc` varchar(10) NOT NULL,
  `oc` varchar(10) DEFAULT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `descr` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`no`,`bc`),
  KEY `supplier_code` (`supplier_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_purchase_sum` */

/*Table structure for table `t_purchse_det` */

DROP TABLE IF EXISTS `t_purchse_det`;

CREATE TABLE `t_purchse_det` (
  `id` bigint(20) unsigned NOT NULL,
  `item_code` varchar(10) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`,`item_code`),
  KEY `item_code` (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_purchse_det` */

/*Table structure for table `t_purchse_order_det` */

DROP TABLE IF EXISTS `t_purchse_order_det`;

CREATE TABLE `t_purchse_order_det` (
  `id` bigint(20) unsigned NOT NULL,
  `item_code` varchar(10) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`,`item_code`),
  KEY `item_code` (`item_code`),
  CONSTRAINT `t_purchse_order_det_ibfk_3` FOREIGN KEY (`id`) REFERENCES `t_purchse_order_sum` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `t_purchse_order_det_ibfk_6` FOREIGN KEY (`item_code`) REFERENCES `m_items` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_purchse_order_det` */

/*Table structure for table `t_purchse_order_sum` */

DROP TABLE IF EXISTS `t_purchse_order_sum`;

CREATE TABLE `t_purchse_order_sum` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `ref_no` varchar(25) NOT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `supplier` varchar(10) DEFAULT NULL,
  `bc` varchar(10) NOT NULL,
  `oc` varchar(10) NOT NULL,
  `is_cancel` smallint(6) DEFAULT '0',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`no`,`bc`),
  KEY `agency_id` (`memo`),
  KEY `oc` (`oc`),
  KEY `bc` (`bc`),
  KEY `supplier` (`supplier`),
  CONSTRAINT `t_purchse_order_sum_ibfk_1` FOREIGN KEY (`supplier`) REFERENCES `m_supplier` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_purchse_order_sum` */

/*Table structure for table `t_purchse_order_trance` */

DROP TABLE IF EXISTS `t_purchse_order_trance`;

CREATE TABLE `t_purchse_order_trance` (
  `trance_id` bigint(20) unsigned DEFAULT NULL,
  `trance_type` varchar(25) DEFAULT NULL,
  `item_code` varchar(10) DEFAULT NULL,
  `in_quantity` decimal(10,2) DEFAULT '0.00',
  `out_quantity` decimal(10,2) DEFAULT '0.00',
  `date` date DEFAULT NULL,
  `ad` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `item_code` (`item_code`),
  CONSTRAINT `t_purchse_order_trance_ibfk_1` FOREIGN KEY (`item_code`) REFERENCES `m_items` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_purchse_order_trance` */

/*Table structure for table `t_purchse_return_det` */

DROP TABLE IF EXISTS `t_purchse_return_det`;

CREATE TABLE `t_purchse_return_det` (
  `id` bigint(20) unsigned NOT NULL,
  `item_code` varchar(10) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`,`item_code`),
  KEY `item_code` (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_purchse_return_det` */

/*Table structure for table `t_purchse_return_sum` */

DROP TABLE IF EXISTS `t_purchse_return_sum`;

CREATE TABLE `t_purchse_return_sum` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `ref_no` varchar(25) NOT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `supplier` varchar(10) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `bc` varchar(10) NOT NULL,
  `oc` varchar(10) NOT NULL,
  `is_cancel` smallint(6) DEFAULT '0',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`no`,`bc`),
  KEY `agency_id` (`memo`),
  KEY `oc` (`oc`),
  KEY `bc` (`bc`),
  KEY `supplier` (`supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_purchse_return_sum` */

/*Table structure for table `t_purchse_sum` */

DROP TABLE IF EXISTS `t_purchse_sum`;

CREATE TABLE `t_purchse_sum` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `ref_no` varchar(25) NOT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `supplier` varchar(10) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `bc` varchar(10) NOT NULL,
  `oc` varchar(10) NOT NULL,
  `is_cancel` smallint(6) DEFAULT '0',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`no`,`bc`),
  KEY `agency_id` (`memo`),
  KEY `oc` (`oc`),
  KEY `bc` (`bc`),
  KEY `supplier` (`supplier`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_purchse_sum` */

/*Table structure for table `t_sale_competitor` */

DROP TABLE IF EXISTS `t_sale_competitor`;

CREATE TABLE `t_sale_competitor` (
  `com_id` varchar(10) NOT NULL,
  `qun` int(11) DEFAULT NULL,
  `saleid` bigint(20) NOT NULL,
  `bc` varchar(10) NOT NULL,
  PRIMARY KEY (`com_id`,`saleid`,`bc`),
  KEY `saleid` (`saleid`),
  KEY `bc` (`bc`),
  CONSTRAINT `t_sale_competitor_ibfk_1` FOREIGN KEY (`saleid`) REFERENCES `t_cus_sale_sum` (`No`),
  CONSTRAINT `t_sale_competitor_ibfk_2` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_sale_competitor` */

/*Table structure for table `t_sale_det` */

DROP TABLE IF EXISTS `t_sale_det`;

CREATE TABLE `t_sale_det` (
  `bc` varchar(10) NOT NULL,
  `No` bigint(20) NOT NULL,
  `itemCode` varchar(10) NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quntity` int(11) DEFAULT NULL,
  `units` varchar(10) NOT NULL,
  PRIMARY KEY (`bc`,`No`,`itemCode`,`units`),
  KEY `units` (`units`),
  KEY `No` (`No`),
  KEY `itemCode` (`itemCode`),
  CONSTRAINT `t_sale_det_ibfk_3` FOREIGN KEY (`No`) REFERENCES `t_sale_sum` (`No`),
  CONSTRAINT `t_sale_det_ibfk_4` FOREIGN KEY (`itemCode`) REFERENCES `m_items` (`code`),
  CONSTRAINT `t_sale_det_ibfk_5` FOREIGN KEY (`units`) REFERENCES `m_units` (`code`),
  CONSTRAINT `t_sale_det_ibfk_6` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_sale_det` */

/*Table structure for table `t_sale_sum` */

DROP TABLE IF EXISTS `t_sale_sum`;

CREATE TABLE `t_sale_sum` (
  `No` bigint(20) NOT NULL,
  `storeId` varchar(10) DEFAULT NULL,
  `customerId` varchar(20) DEFAULT NULL,
  `resonId` varchar(10) DEFAULT NULL,
  `dDate` date DEFAULT NULL,
  `refNo` varchar(100) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `bc` varchar(10) NOT NULL DEFAULT '',
  `oc` varchar(10) NOT NULL,
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `root` varchar(10) DEFAULT NULL,
  `ret_id` varchar(20) DEFAULT '0',
  `sales_manager` varchar(10) DEFAULT NULL,
  `regional_manager` varchar(10) DEFAULT NULL,
  `manager` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`No`,`bc`),
  KEY `storeId` (`storeId`),
  KEY `customerId` (`customerId`),
  KEY `resonId` (`resonId`),
  KEY `sales_manager` (`sales_manager`),
  KEY `regional_manager` (`regional_manager`),
  KEY `manager` (`manager`),
  KEY `bc` (`bc`),
  KEY `oc` (`oc`),
  CONSTRAINT `t_sale_sum_ibfk_1` FOREIGN KEY (`storeId`) REFERENCES `m_stores` (`code`),
  CONSTRAINT `t_sale_sum_ibfk_2` FOREIGN KEY (`bc`) REFERENCES `m_branches` (`cCode`),
  CONSTRAINT `t_sale_sum_ibfk_3` FOREIGN KEY (`oc`) REFERENCES `a_users` (`cCode`),
  CONSTRAINT `t_sale_sum_ibfk_4` FOREIGN KEY (`customerId`) REFERENCES `m_customer` (`code`),
  CONSTRAINT `t_sale_sum_ibfk_5` FOREIGN KEY (`resonId`) REFERENCES `m_reason` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_sale_sum` */

/*Table structure for table `t_sales_det` */

DROP TABLE IF EXISTS `t_sales_det`;

CREATE TABLE `t_sales_det` (
  `id` bigint(20) unsigned NOT NULL,
  `item_code` varchar(10) NOT NULL,
  `cost` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantity` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`,`item_code`),
  KEY `item_code` (`item_code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_sales_det` */

/*Table structure for table `t_sales_ret_det` */

DROP TABLE IF EXISTS `t_sales_ret_det`;

CREATE TABLE `t_sales_ret_det` (
  `bc` varchar(10) NOT NULL DEFAULT '',
  `no` bigint(20) NOT NULL DEFAULT '0',
  `item_code` varchar(10) NOT NULL DEFAULT '',
  `units` varchar(10) NOT NULL DEFAULT '',
  `quntity` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`bc`,`no`,`item_code`,`units`),
  KEY `item_code` (`item_code`),
  KEY `units` (`units`),
  CONSTRAINT `t_sales_ret_det_ibfk_1` FOREIGN KEY (`item_code`) REFERENCES `m_items` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_sales_ret_det` */

/*Table structure for table `t_sales_ret_sum` */

DROP TABLE IF EXISTS `t_sales_ret_sum`;

CREATE TABLE `t_sales_ret_sum` (
  `bc` varchar(10) NOT NULL DEFAULT '',
  `no` bigint(20) NOT NULL DEFAULT '0',
  `customer_code` varchar(15) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `total` decimal(10,2) DEFAULT NULL,
  `oc` varchar(10) DEFAULT NULL,
  `actionDate` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `invoice_no` bigint(20) DEFAULT NULL,
  `dDate` date DEFAULT NULL,
  `ref_no` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`bc`,`no`),
  KEY `agent_code` (`customer_code`),
  KEY `dispatch_no` (`invoice_no`),
  KEY `oc` (`oc`),
  CONSTRAINT `t_sales_ret_sum_ibfk_1` FOREIGN KEY (`customer_code`) REFERENCES `m_customer` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_sales_ret_sum` */

/*Table structure for table `t_sales_sum` */

DROP TABLE IF EXISTS `t_sales_sum`;

CREATE TABLE `t_sales_sum` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `no` bigint(20) NOT NULL,
  `date` date NOT NULL,
  `ref_no` varchar(25) NOT NULL,
  `memo` varchar(255) DEFAULT NULL,
  `customer` varchar(15) DEFAULT NULL,
  `sales_ref` varchar(10) DEFAULT NULL,
  `discount` decimal(10,2) DEFAULT NULL,
  `bc` varchar(10) NOT NULL,
  `oc` varchar(10) NOT NULL,
  `is_cancel` smallint(6) DEFAULT '0',
  `action_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`,`no`,`bc`),
  KEY `agency_id` (`memo`),
  KEY `oc` (`oc`),
  KEY `bc` (`bc`),
  KEY `supplier` (`customer`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

/*Data for the table `t_sales_sum` */

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
