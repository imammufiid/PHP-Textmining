/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100408
 Source Host           : localhost:3306
 Source Schema         : project_pengaduan

 Target Server Type    : MySQL
 Target Server Version : 100408
 File Encoding         : 65001

 Date: 22/10/2020 11:31:49
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for documents
-- ----------------------------
DROP TABLE IF EXISTS `documents`;
CREATE TABLE `documents`  (
  `doc_id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `complaint` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `code_disposition` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`doc_id`) USING BTREE,
  INDEX `code_disposition`(`code_disposition`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 19 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for case_folding
-- ----------------------------
DROP TABLE IF EXISTS `case_folding`;
CREATE TABLE `case_folding`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `case_folding` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `doc_id` int(11) NULL DEFAULT NULL,
  `code_disposition` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `code_disposition`(`code_disposition`) USING BTREE
);
-- ----------------------------
-- Table structure for token
-- ----------------------------
DROP TABLE IF EXISTS `token`;
CREATE TABLE `token`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `term` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `doc_id` int(11) NULL DEFAULT NULL,
  `code_disposition` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for filtering
-- ----------------------------
DROP TABLE IF EXISTS `filtering`;
CREATE TABLE `filtering`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `term` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `doc_id` int(11) NULL DEFAULT NULL,
  `code_disposition` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for stemming
-- ----------------------------
DROP TABLE IF EXISTS `stemming`;
CREATE TABLE `stemming`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `term` text CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL,
  `doc_id` int(11) NULL DEFAULT NULL,
  `code_disposition` varchar(3) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for tbindex
-- ----------------------------
DROP TABLE IF EXISTS `tbindex`;
CREATE TABLE `tbindex`  (
  `Id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `Term` varchar(50) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `DocId` int(11) NOT NULL,
  `Count` int(11) NOT NULL,
  `Weight` float NOT NULL,
  PRIMARY KEY (`Id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 27 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Table structure for basic_word
-- ----------------------------
DROP TABLE IF EXISTS `basic_word`;
CREATE TABLE `basic_word`  (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `basic_word` varchar(70) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `type_basic_word` varchar(25) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 28527 CHARACTER SET = latin1 COLLATE = latin1_swedish_ci ROW_FORMAT = Dynamic;

SET FOREIGN_KEY_CHECKS = 1;