/*
 Navicat Premium Data Transfer

 Source Server         : rong
 Source Server Type    : MariaDB
 Source Server Version : 100212
 Source Host           : 127.0.0.1:3306
 Source Schema         : vmall

 Target Server Type    : MariaDB
 Target Server Version : 100212
 File Encoding         : 65001

 Date: 09/02/2018 00:43:05
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ci_sessions
-- ----------------------------
DROP TABLE IF EXISTS `ci_sessions`;
CREATE TABLE `ci_sessions` (
  `id` varchar(128) NOT NULL,
  `ip_address` varchar(45) NOT NULL,
  `timestamp` int(10) unsigned NOT NULL DEFAULT 0,
  `data` blob NOT NULL,
  PRIMARY KEY (`id`),
  KEY `ci_sessions_timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of ci_sessions
-- ----------------------------
BEGIN;
INSERT INTO `ci_sessions` VALUES ('hlkmg6s52nq3fgphda8gjhfb2c0hd9p6', '127.0.0.1', 1517932211, 0x5F5F63695F6C6173745F726567656E65726174657C693A313531373933323231313B);
INSERT INTO `ci_sessions` VALUES ('rmt2pugstkmndlukfsc644miccr3b7l5', '127.0.0.1', 1517932270, 0x5F5F63695F6C6173745F726567656E65726174657C693A313531373933323237303B);
COMMIT;

-- ----------------------------
-- Table structure for member
-- ----------------------------
DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `member_id` bigint(11) NOT NULL AUTO_INCREMENT COMMENT 'member id',
  `username` varchar(30) NOT NULL COMMENT 'username',
  `name` varchar(30) DEFAULT NULL COMMENT 'member name',
  `phone` varchar(20) DEFAULT NULL COMMENT 'phone',
  `email` varchar(50) DEFAULT NULL COMMENT 'email',
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `zipcode` int(8) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `address1` varchar(50) DEFAULT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `rb_id` bigint(11) DEFAULT NULL COMMENT 'rbs_id',
  PRIMARY KEY (`member_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='members';

-- ----------------------------
-- Table structure for merchant
-- ----------------------------
DROP TABLE IF EXISTS `merchant`;
CREATE TABLE `merchant` (
  `merchant_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `shop_name` varchar(30) DEFAULT NULL,
  `merchant_type` tinyint(4) DEFAULT 0,
  `status` tinyint(4) DEFAULT 0,
  PRIMARY KEY (`merchant_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='merchant';

-- ----------------------------
-- Table structure for merchant_debtor
-- ----------------------------
DROP TABLE IF EXISTS `merchant_debtor`;
CREATE TABLE `merchant_debtor` (
  `debtor_id` bigint(11) NOT NULL,
  `balance` decimal(12,2) DEFAULT 0.00,
  `credit` decimal(12,2) DEFAULT 0.00,
  `debit` decimal(12,2) DEFAULT 0.00,
  `merchant_id` bigint(11) DEFAULT NULL,
  `debtor_source` varchar(20) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`debtor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='merchant_debtor';

-- ----------------------------
-- Table structure for merchant_deposit
-- ----------------------------
DROP TABLE IF EXISTS `merchant_deposit`;
CREATE TABLE `merchant_deposit` (
  `deposit_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `balance` decimal(12,2) DEFAULT 0.00,
  `credit` decimal(12,2) DEFAULT 0.00,
  `debit` decimal(12,2) DEFAULT 0.00,
  `merchant_id` bigint(11) DEFAULT NULL,
  `reference_id` bigint(11) DEFAULT NULL,
  `reference_source` varchar(20) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`deposit_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='merchant_deposit';

-- ----------------------------
-- Table structure for merchant_track
-- ----------------------------
DROP TABLE IF EXISTS `merchant_track`;
CREATE TABLE `merchant_track` (
  `merchant_track_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `member_id` bigint(11) DEFAULT NULL,
  `merchant_id` bigint(11) DEFAULT NULL,
  PRIMARY KEY (`merchant_track_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='merchant_track';

-- ----------------------------
-- Table structure for rb_track
-- ----------------------------
DROP TABLE IF EXISTS `rb_track`;
CREATE TABLE `rb_track` (
  `rb_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `balance` decimal(12,2) DEFAULT 0.00,
  `credit` decimal(12,2) DEFAULT 0.00,
  `debit` decimal(12,2) DEFAULT 0.00,
  `member_id` bigint(11) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `create_timestamp` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`rb_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='rb_track';

-- ----------------------------
-- Table structure for transaction
-- ----------------------------
DROP TABLE IF EXISTS `transaction`;
CREATE TABLE `transaction` (
  `transaction_id` bigint(11) NOT NULL AUTO_INCREMENT,
  `cash` decimal(12,2) DEFAULT 0.00,
  `epoint` decimal(12,2) DEFAULT 0.00,
  `voucher` decimal(12,2) DEFAULT 0.00,
  `member_id` bigint(11) DEFAULT NULL,
  `merchat_id` bigint(11) DEFAULT NULL,
  `remark` varchar(100) DEFAULT NULL,
  `create_timestamp` timestamp NULL DEFAULT NULL,
  `update_timestamp` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`transaction_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='transaction';

-- ----------------------------
-- Table structure for voucher
-- ----------------------------
DROP TABLE IF EXISTS `voucher`;
CREATE TABLE `voucher` (
  `voucher_id` bigint(11) NOT NULL,
  `transaction_id` bigint(11) DEFAULT NULL,
  PRIMARY KEY (`voucher_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='voucher';

-- ----------------------------
-- Records of voucher
-- ----------------------------
BEGIN;
INSERT INTO `voucher` VALUES (110, 110);
COMMIT;


DROP TABLE IF EXISTS `member`;
CREATE TABLE `member` (
  `member_id` bigint(11) NOT NULL COMMENT 'member id',
  `username` varchar(50) NOT NULL COMMENT 'username',
  `name` varchar(30) DEFAULT NULL COMMENT 'member name',
  `phone` varchar(20) DEFAULT NULL COMMENT 'phone',
  `email` varchar(50) DEFAULT NULL COMMENT 'email',
  `birthday` date DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `zipcode` int(8) DEFAULT NULL,
  `country` varchar(30) DEFAULT NULL,
  `city` varchar(30) DEFAULT NULL,
  `state` varchar(30) DEFAULT NULL,
  `address1` varchar(50) DEFAULT NULL,
  `address2` varchar(50) DEFAULT NULL,
  `status` tinyint(4) DEFAULT NULL,
  `rb_id` bigint(11) DEFAULT NULL COMMENT 'rbs_id',
  `ismerchant` char(2) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`member_id`),
  KEY `member_mid` (`member_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='members';

-- ----------------------------

INSERT INTO `member` VALUES (604148724, '', 'CHUA SIAN DEN', '60122012894', 'sianden@hotmail.com', NULL, 'M', 13455, 'MY', '-', 'B', '-', NULL, NULL, NULL, 'N');
INSERT INTO `member` VALUES (6041487247, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `member` VALUES (6041487248, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);



SET FOREIGN_KEY_CHECKS = 1;
