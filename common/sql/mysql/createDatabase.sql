CREATE DATABASE  IF NOT EXISTS `gawain` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `gawain`;
-- MySQL dump 10.13  Distrib 5.6.13, for Win32 (x86)
--
-- Host: 10.0.0.12    Database: gawain
-- ------------------------------------------------------
-- Server version	5.5.41-0+wheezy1

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
-- Table structure for table `activities`
--

DROP TABLE IF EXISTS `activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activities` (
  `activityID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activityRootID` int(10) unsigned DEFAULT NULL,
  `activityParentID` int(10) unsigned DEFAULT NULL,
  `activityCustomerID` int(10) unsigned NOT NULL,
  `activityName` varchar(64) NOT NULL,
  `activityTypeID` int(10) unsigned NOT NULL,
  `activityCode` varchar(32) DEFAULT NULL,
  `activityDescription` mediumtext,
  `activityAreaID` int(10) unsigned DEFAULT NULL,
  `activityEnvironmentID` int(10) unsigned DEFAULT NULL,
  `activityCustomerReference` varchar(256) DEFAULT NULL,
  `activityManagerNick` varchar(64) DEFAULT NULL,
  `activityEstimatedEffortHours` decimal(7,2) unsigned NOT NULL DEFAULT '0.00',
  `activityIsEstimatedEffortHoursAuto` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `activityStartDate` date DEFAULT NULL,
  `activityStatusID` int(10) unsigned DEFAULT NULL,
  `activityCompletion` decimal(5,2) unsigned NOT NULL DEFAULT '0.00',
  `activityIsCompletionAuto` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `activityIsCompleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `activityIsOfficial` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `activityQcConnectionID` int(10) unsigned DEFAULT NULL,
  `activityQcIdentificationQuery` varchar(256) DEFAULT NULL,
  `activityUserField01` varchar(256) DEFAULT NULL,
  `activityUserField02` varchar(256) DEFAULT NULL,
  `activityUserField03` varchar(256) DEFAULT NULL,
  `activityUserField04` varchar(256) DEFAULT NULL,
  `activityUserField05` varchar(256) DEFAULT NULL,
  `activityUserField06` varchar(256) DEFAULT NULL,
  `activityUserField07` varchar(256) DEFAULT NULL,
  `activityUserField08` varchar(256) DEFAULT NULL,
  `activityUserField09` varchar(256) DEFAULT NULL,
  `activityUserField10` varchar(256) DEFAULT NULL,
  `activityUserField11` varchar(256) DEFAULT NULL,
  `activityUserField12` varchar(256) DEFAULT NULL,
  `activityUserField13` varchar(256) DEFAULT NULL,
  `activityUserField14` varchar(256) DEFAULT NULL,
  `activityUserField15` varchar(256) DEFAULT NULL,
  `activityUserField16` varchar(256) DEFAULT NULL,
  `activityColorHexCode` char(7) DEFAULT NULL,
  `activityComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`activityID`),
  UNIQUE KEY `projectID_UNIQUE` (`activityID`),
  KEY `projectAreaID_idx` (`activityAreaID`),
  KEY `projectCustomerID_idx` (`activityCustomerID`),
  KEY `projectIsCompleted_idx` (`activityIsCompleted`),
  KEY `projectIsInternal` (`activityIsOfficial`),
  KEY `projectReleaseStateID_idx` (`activityEnvironmentID`),
  KEY `projectStartDate_idx` (`activityStartDate`),
  KEY `projectStatusID_idx` (`activityStatusID`),
  KEY `projectTestManagerID_idx` (`activityManagerNick`),
  KEY `projectTypeID_idx` (`activityTypeID`),
  KEY `FK_activities_activities_activityID` (`activityRootID`),
  KEY `FK_activities_qc_connections_connID` (`activityQcConnectionID`),
  KEY `projectParentProjectID` (`activityParentID`),
  CONSTRAINT `FK_activities_activities_activityID` FOREIGN KEY (`activityRootID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_activities_qc_connections_connID` FOREIGN KEY (`activityQcConnectionID`) REFERENCES `qc_connections` (`connID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_activities_users_userNick` FOREIGN KEY (`activityManagerNick`) REFERENCES `users` (`userNick`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `projectAreaID` FOREIGN KEY (`activityAreaID`) REFERENCES `areas` (`areaID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `projectCustomerID` FOREIGN KEY (`activityCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectParentProjectID` FOREIGN KEY (`activityParentID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectReleaseStateID` FOREIGN KEY (`activityEnvironmentID`) REFERENCES `environment` (`environmentID`) ON UPDATE CASCADE,
  CONSTRAINT `projectStatusID` FOREIGN KEY (`activityStatusID`) REFERENCES `activity_status` (`statusID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `projectTypeID` FOREIGN KEY (`activityTypeID`) REFERENCES `activity_type` (`activityTypeID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=4096;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (9,NULL,NULL,1,'Prova padre',1,'123456','Prova di inserimento progetto padre',1,1,'Nome cliente','admin',50.00,1,'2015-03-26',1,40.00,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(10,9,9,1,'Prova figlio',1,'2245','Prova progetto figlio',1,1,'Pippo','admin',20.00,1,'2015-03-26',1,20.00,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(11,NULL,NULL,1,'Prova a caso',1,NULL,'Prova a caso',NULL,NULL,NULL,NULL,0.00,1,NULL,NULL,0.00,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activities_assigned_users`
--

DROP TABLE IF EXISTS `activities_assigned_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activities_assigned_users` (
  `activityID` int(10) unsigned NOT NULL,
  `assignedUserNick` varchar(64) NOT NULL,
  `assignedActivityCustomerID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`activityID`,`assignedUserNick`),
  KEY `FK_wp_activities_assigned_users_wp_customers_customerID` (`assignedActivityCustomerID`),
  KEY `FK_wp_activities_assigned_users_wp_users_userID` (`assignedUserNick`),
  CONSTRAINT `FK_activities_assigned_users_users_userNick` FOREIGN KEY (`assignedUserNick`) REFERENCES `users` (`userNick`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_wp_activities_assigned_users_wp_activities_activityID` FOREIGN KEY (`activityID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_wp_activities_assigned_users_wp_customers_customerID` FOREIGN KEY (`assignedActivityCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities_assigned_users`
--

LOCK TABLES `activities_assigned_users` WRITE;
/*!40000 ALTER TABLE `activities_assigned_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `activities_assigned_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activities_associated_qc_bugreport_types`
--

DROP TABLE IF EXISTS `activities_associated_qc_bugreport_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activities_associated_qc_bugreport_types` (
  `activityID` int(10) unsigned NOT NULL,
  `bugreportTypeID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`activityID`,`bugreportTypeID`),
  KEY `FK_activities_bugreport_types_bugreport_type_bugreportTypeID` (`bugreportTypeID`),
  CONSTRAINT `FK_activities_associated_bugreport_types_activities_activityID` FOREIGN KEY (`activityID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_activities_bugreport_types_bugreport_type_bugreportTypeID` FOREIGN KEY (`bugreportTypeID`) REFERENCES `qc_bugreport_type` (`bugreportTypeID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activities_associated_qc_bugreport_types`
--

LOCK TABLES `activities_associated_qc_bugreport_types` WRITE;
/*!40000 ALTER TABLE `activities_associated_qc_bugreport_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `activities_associated_qc_bugreport_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_event_type`
--

DROP TABLE IF EXISTS `activity_event_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_event_type` (
  `eventTypeID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eventTypeCustomerID` int(10) unsigned NOT NULL,
  `eventTypeName` varchar(64) NOT NULL,
  `eventTypeComment` varchar(128) DEFAULT NULL,
  `eventTypeOrderIndex` int(10) NOT NULL DEFAULT '100',
  PRIMARY KEY (`eventTypeID`),
  UNIQUE KEY `typeID_UNIQUE` (`eventTypeID`),
  KEY `prTypeCustomerID_idx` (`eventTypeCustomerID`),
  CONSTRAINT `prTypeCustomerID` FOREIGN KEY (`eventTypeCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_event_type`
--

LOCK TABLES `activity_event_type` WRITE;
/*!40000 ALTER TABLE `activity_event_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_event_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_events`
--

DROP TABLE IF EXISTS `activity_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_events` (
  `eventID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `eventRelatedActivityID` int(10) unsigned NOT NULL,
  `eventTypeID` int(10) unsigned DEFAULT NULL,
  `eventDescription` mediumtext,
  `eventStartDate` datetime NOT NULL,
  `eventEndTime` datetime DEFAULT NULL,
  `eventIsAllDayLong` tinyint(1) NOT NULL,
  PRIMARY KEY (`eventID`),
  UNIQUE KEY `eventID_UNIQUE` (`eventID`),
  KEY `eventRelatedProject_idx` (`eventRelatedActivityID`),
  KEY `eventStartDate_idx` (`eventStartDate`),
  KEY `eventTypeID_idx` (`eventTypeID`),
  CONSTRAINT `eventRelatedProjectID` FOREIGN KEY (`eventRelatedActivityID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectEventTypeID` FOREIGN KEY (`eventTypeID`) REFERENCES `activity_event_type` (`eventTypeID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_events`
--

LOCK TABLES `activity_events` WRITE;
/*!40000 ALTER TABLE `activity_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_posts`
--

DROP TABLE IF EXISTS `activity_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_posts` (
  `postID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `postRelatedActivityID` int(10) unsigned NOT NULL,
  `postAuthorNick` varchar(64) DEFAULT NULL,
  `postContent` mediumtext,
  `postCreation` datetime NOT NULL,
  `postLastUpdate` datetime NOT NULL,
  `postIsOfficial` tinyint(1) unsigned NOT NULL,
  PRIMARY KEY (`postID`),
  UNIQUE KEY `postID_UNIQUE` (`postID`),
  KEY `postAuthorID_idx` (`postAuthorNick`),
  KEY `postLastUpdate_idx` (`postLastUpdate`),
  KEY `postRelatedProjectID_idx` (`postRelatedActivityID`),
  CONSTRAINT `FK_activity_posts_users_userNick` FOREIGN KEY (`postAuthorNick`) REFERENCES `users` (`userNick`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `postRelatedProjectID` FOREIGN KEY (`postRelatedActivityID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_posts`
--

LOCK TABLES `activity_posts` WRITE;
/*!40000 ALTER TABLE `activity_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_status`
--

DROP TABLE IF EXISTS `activity_status`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_status` (
  `statusID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `statusCustomerID` int(10) unsigned NOT NULL,
  `statusName` varchar(64) NOT NULL,
  `statusComment` varchar(128) DEFAULT NULL,
  `statusOrderIndex` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`statusID`),
  UNIQUE KEY `statusID_UNIQUE` (`statusID`),
  KEY `projectStatusCustomerID_idx` (`statusCustomerID`),
  CONSTRAINT `projectStatusCustomerID` FOREIGN KEY (`statusCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_status`
--

LOCK TABLES `activity_status` WRITE;
/*!40000 ALTER TABLE `activity_status` DISABLE KEYS */;
INSERT INTO `activity_status` VALUES (1,1,'In Carico','AttivitÃƒÆ’Ã‚Â  presa in carico',100);
/*!40000 ALTER TABLE `activity_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `activity_type`
--

DROP TABLE IF EXISTS `activity_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_type` (
  `activityTypeID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `activityTypeCustomerID` int(10) unsigned NOT NULL,
  `activityTypeName` varchar(64) NOT NULL,
  `activityTypeIsOfficial` tinyint(1) unsigned NOT NULL DEFAULT '1',
  `activityTypeComment` varchar(128) DEFAULT NULL,
  `activityTypeOrderIndex` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`activityTypeID`),
  UNIQUE KEY `projectTypeID_UNIQUE` (`activityTypeID`),
  KEY `ProjectTypeCustomerID_idx` (`activityTypeCustomerID`),
  CONSTRAINT `ProjectTypeCustomerID` FOREIGN KEY (`activityTypeCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_type`
--

LOCK TABLES `activity_type` WRITE;
/*!40000 ALTER TABLE `activity_type` DISABLE KEYS */;
INSERT INTO `activity_type` VALUES (1,1,'Progetti',1,'Progetti nuovi',100);
/*!40000 ALTER TABLE `activity_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `application_log`
--

DROP TABLE IF EXISTS `application_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_log` (
  `logTimestamp` varchar(32) NOT NULL,
  `logLevel` enum('FATAL ERROR','ERROR','WARNING','INFO','DEBUG') NOT NULL DEFAULT 'INFO',
  `hostname` varchar(255) DEFAULT NULL,
  `userNick` varchar(64) DEFAULT NULL,
  `entity` varchar(64) DEFAULT NULL,
  `module` varchar(64) DEFAULT NULL,
  `message` mediumtext NOT NULL,
  PRIMARY KEY (`logTimestamp`),
  UNIQUE KEY `UK_log_timestamp` (`logTimestamp`),
  KEY `FK_log_users_userNick` (`userNick`),
  KEY `IDX_log_logLevel` (`logLevel`),
  KEY `IDX_log` (`entity`,`module`),
  KEY `UK_log_hostname` (`hostname`),
  CONSTRAINT `FK_log_users_userNick` FOREIGN KEY (`userNick`) REFERENCES `users` (`userNick`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table containing all the application logs';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `application_log`
--

LOCK TABLES `application_log` WRITE;
/*!40000 ALTER TABLE `application_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `application_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `areas`
--

DROP TABLE IF EXISTS `areas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `areas` (
  `areaID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `areaCustomerID` int(10) unsigned NOT NULL,
  `areaName` varchar(64) NOT NULL,
  `areaComment` varchar(128) DEFAULT NULL,
  `areaOrderIndex` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`areaID`),
  UNIQUE KEY `areaID_UNIQUE` (`areaID`),
  KEY `areaCustomerID_idx` (`areaCustomerID`),
  CONSTRAINT `areaCustomerID` FOREIGN KEY (`areaCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `areas`
--

LOCK TABLES `areas` WRITE;
/*!40000 ALTER TABLE `areas` DISABLE KEYS */;
INSERT INTO `areas` VALUES (1,1,'Cliente','Progetti che impattano direttamente col cliente',100);
/*!40000 ALTER TABLE `areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `auths`
--

DROP TABLE IF EXISTS `auths`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `auths` (
  `authCode` varchar(64) NOT NULL,
  `authName` varchar(64) NOT NULL,
  `authComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`authCode`),
  UNIQUE KEY `authID_UNIQUE` (`authCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `auths`
--

LOCK TABLES `auths` WRITE;
/*!40000 ALTER TABLE `auths` DISABLE KEYS */;
INSERT INTO `auths` VALUES ('1','ADMIN','Autorizzazione di amministratore');
/*!40000 ALTER TABLE `auths` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `components_linked_rendering_report_elements`
--

DROP TABLE IF EXISTS `components_linked_rendering_report_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `components_linked_rendering_report_elements` (
  `componentCode` varchar(64) NOT NULL,
  `customerID` int(10) unsigned NOT NULL,
  `reportElementCode` varchar(64) NOT NULL,
  PRIMARY KEY (`componentCode`,`customerID`,`reportElementCode`),
  KEY `FK_components_linked_rendering_report` (`reportElementCode`,`customerID`),
  KEY `FK_components_linked_rendering_report_elements_customerID` (`customerID`),
  CONSTRAINT `FK_components_linked_rendering_report` FOREIGN KEY (`reportElementCode`, `customerID`) REFERENCES `rendering_report_elements` (`elementCode`, `elementCustomerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_components_linked_rendering_report_elements_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `components_linked_rendering_report_elements`
--

LOCK TABLES `components_linked_rendering_report_elements` WRITE;
/*!40000 ALTER TABLE `components_linked_rendering_report_elements` DISABLE KEYS */;
/*!40000 ALTER TABLE `components_linked_rendering_report_elements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `customers`
--

DROP TABLE IF EXISTS `customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `customers` (
  `customerID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `customerName` varchar(64) NOT NULL,
  `customerComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`customerID`),
  UNIQUE KEY `ID_CUST_UNIQUE` (`customerID`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Prova 1','Commento di Prova 1');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `document_repository`
--

DROP TABLE IF EXISTS `document_repository`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `document_repository` (
  `docRepoID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `docRepoActivityID` int(10) unsigned NOT NULL,
  `docRepoDocumentName` varchar(128) NOT NULL,
  `docRepoDescription` mediumtext,
  `docRepoVersion` int(10) unsigned NOT NULL,
  `docRepoFilePath` varchar(256) NOT NULL,
  PRIMARY KEY (`docRepoID`),
  UNIQUE KEY `docRepoID_UNIQUE` (`docRepoID`),
  KEY `docRepoProjectID_idx` (`docRepoActivityID`),
  KEY `docRepoVersion` (`docRepoVersion`),
  CONSTRAINT `docRepoProjectID` FOREIGN KEY (`docRepoActivityID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `document_repository`
--

LOCK TABLES `document_repository` WRITE;
/*!40000 ALTER TABLE `document_repository` DISABLE KEYS */;
/*!40000 ALTER TABLE `document_repository` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dos_event_type`
--

DROP TABLE IF EXISTS `dos_event_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dos_event_type` (
  `dosTypeID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dosTypeCustomerID` int(10) unsigned NOT NULL,
  `dosTypeName` varchar(64) NOT NULL,
  `dosTypeComment` varchar(128) DEFAULT NULL,
  `dosTypeOrderIndex` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`dosTypeID`),
  UNIQUE KEY `dosTypeID_UNIQUE` (`dosTypeID`),
  KEY `dosTypeCustomerID_idx` (`dosTypeCustomerID`),
  CONSTRAINT `dosTypeCustomerID` FOREIGN KEY (`dosTypeCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dos_event_type`
--

LOCK TABLES `dos_event_type` WRITE;
/*!40000 ALTER TABLE `dos_event_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `dos_event_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dos_events`
--

DROP TABLE IF EXISTS `dos_events`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dos_events` (
  `dosID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `dosTypeID` int(10) unsigned NOT NULL,
  `dosDescription` mediumtext,
  `dosStartDate` datetime NOT NULL,
  `dosEndDate` datetime NOT NULL,
  `dosAffectedEnvironments` varchar(256) DEFAULT NULL,
  `dosAffectedAreas` varchar(256) DEFAULT NULL,
  `dosAffectedActivitiesIDs` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`dosID`),
  UNIQUE KEY `dosID_UNIQUE` (`dosID`),
  KEY `dosStartEndDate` (`dosStartDate`,`dosEndDate`),
  KEY `dosTypeID_idx` (`dosTypeID`),
  CONSTRAINT `dosTypeID` FOREIGN KEY (`dosTypeID`) REFERENCES `dos_event_type` (`dosTypeID`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dos_events`
--

LOCK TABLES `dos_events` WRITE;
/*!40000 ALTER TABLE `dos_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `dos_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dos_events_affected_activities`
--

DROP TABLE IF EXISTS `dos_events_affected_activities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dos_events_affected_activities` (
  `dosID` int(10) unsigned NOT NULL,
  `dosAffectedActivityID` int(10) unsigned NOT NULL,
  PRIMARY KEY (`dosID`,`dosAffectedActivityID`),
  KEY `FK_dos_events_affected_activities_wp_activities_activityID` (`dosAffectedActivityID`),
  CONSTRAINT `FK_dos_events_affected_activities_wp_activities_activityID` FOREIGN KEY (`dosAffectedActivityID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_dos_events_affected_activities_wp_dos_events_dosID` FOREIGN KEY (`dosID`) REFERENCES `dos_events` (`dosID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dos_events_affected_activities`
--

LOCK TABLES `dos_events_affected_activities` WRITE;
/*!40000 ALTER TABLE `dos_events_affected_activities` DISABLE KEYS */;
/*!40000 ALTER TABLE `dos_events_affected_activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entities`
--

DROP TABLE IF EXISTS `entities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entities` (
  `entityCode` varchar(64) NOT NULL,
  `entityName` varchar(64) NOT NULL,
  `entityReferenceTable` varchar(128) NOT NULL,
  `entityComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`entityCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entities`
--

LOCK TABLES `entities` WRITE;
/*!40000 ALTER TABLE `entities` DISABLE KEYS */;
INSERT INTO `entities` VALUES ('activity','Activity','activities','Attività generica'),('timeslot_item','Timeslot Item','','Elemento di timeslot inserito dall\'utente');
/*!40000 ALTER TABLE `entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entities_label`
--

DROP TABLE IF EXISTS `entities_label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entities_label` (
  `entityCode` varchar(64) NOT NULL,
  `customerID` int(10) unsigned NOT NULL,
  `entityLabel` varchar(64) NOT NULL,
  `entityComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`entityCode`,`customerID`),
  KEY `FK_entities_label_customers_customerID` (`customerID`),
  CONSTRAINT `FK_entities_label_customers_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_entities_label_entities_entityCode` FOREIGN KEY (`entityCode`) REFERENCES `entities` (`entityCode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entities_label`
--

LOCK TABLES `entities_label` WRITE;
/*!40000 ALTER TABLE `entities_label` DISABLE KEYS */;
INSERT INTO `entities_label` VALUES ('activity',1,'Attività','Attività generali');
/*!40000 ALTER TABLE `entities_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entities_linked_rendering_elements`
--

DROP TABLE IF EXISTS `entities_linked_rendering_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entities_linked_rendering_elements` (
  `customerID` int(10) unsigned NOT NULL,
  `renderingTypeCode` varchar(64) NOT NULL,
  `entityCode` varchar(64) NOT NULL,
  `columnName` varchar(64) NOT NULL,
  `fieldLabel` varchar(128) NOT NULL,
  `fieldTooltip` varchar(256) DEFAULT NULL,
  `fieldOrderingIndex` int(10) unsigned NOT NULL DEFAULT '100',
  `fieldGroupingLevel` int(10) DEFAULT NULL,
  `fieldGroupingFunction` varchar(64) DEFAULT NULL,
  `fieldRenderingElementCode` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`customerID`,`renderingTypeCode`,`entityCode`,`columnName`),
  KEY `FK_renderingElementCode` (`fieldRenderingElementCode`),
  KEY `FK_entities_types_renderingTypeCode` (`renderingTypeCode`),
  KEY `FK_entities_linked_rendering_e` (`entityCode`,`columnName`),
  CONSTRAINT `FK_entities_linked_rendering_e` FOREIGN KEY (`entityCode`, `columnName`) REFERENCES `entities_reference_fields` (`entityCode`, `columnName`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_entities_types_renderingTypeCode` FOREIGN KEY (`renderingTypeCode`) REFERENCES `rendering_types` (`renderingTypeCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_renderingElementCode` FOREIGN KEY (`fieldRenderingElementCode`) REFERENCES `rendering_elements` (`elementCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_rendering_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entities_linked_rendering_elements`
--

LOCK TABLES `entities_linked_rendering_elements` WRITE;
/*!40000 ALTER TABLE `entities_linked_rendering_elements` DISABLE KEYS */;
INSERT INTO `entities_linked_rendering_elements` VALUES (1,'display__block_text','activity','activityAreaID','Area di competenza',NULL,800,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityCode','Codice attività',NULL,600,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityColorHexCode','Colore attività',NULL,2600,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityComment','Commento',NULL,2700,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityCompletion','Completamento',NULL,2000,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityCustomerID','Cliente',NULL,350,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityCustomerReference','Project Manager',NULL,1000,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityDescription','Descrizione',NULL,700,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityEnvironmentID','Ambiente',NULL,900,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityID','ID Attività',NULL,100,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityIsCompleted','Completata',NULL,2200,NULL,NULL,'form-horizontal-disabled-checkbox'),(1,'display__block_text','activity','activityIsCompletionAuto','Calcolo automatico completamento?',NULL,2100,NULL,NULL,'form-horizontal-disabled-checkbox'),(1,'display__block_text','activity','activityIsEstimatedEffortHoursAuto','Stima automatica effort?',NULL,1300,NULL,NULL,'form-horizontal-disabled-checkbox'),(1,'display__block_text','activity','activityIsOfficial','Ufficiale',NULL,2300,NULL,NULL,'form-horizontal-disabled-checkbox'),(1,'display__block_text','activity','activityManagerNick','Test Manager',NULL,1100,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityName','Nome attività',NULL,400,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityParentID','Attività padre',NULL,300,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityQcConnectionID','ID connessione QC',NULL,2400,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityQcIdentificationQuery','Query univoca QC',NULL,2500,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityRootID','Attività radice',NULL,200,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityStartDate','Data inizio',NULL,1500,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityStatusID','Stato',NULL,1800,NULL,NULL,'form-horizontal-text'),(1,'display__block_text','activity','activityTypeID','Tipo attività',NULL,500,NULL,NULL,'form-horizontal-text'),(1,'edit__block_text','activity','activityAreaID','Area di competenza',NULL,800,NULL,NULL,'form-horizontal-select'),(1,'edit__block_text','activity','activityCode','Codice attività',NULL,600,NULL,NULL,'form-horizontal-input-text'),(1,'edit__block_text','activity','activityColorHexCode','Colore attività',NULL,2600,NULL,NULL,'form-horizontal-input-text'),(1,'edit__block_text','activity','activityComment','Commento',NULL,2700,NULL,NULL,'form-horizontal-input-text'),(1,'edit__block_text','activity','activityCompletion','Completamento',NULL,2000,NULL,NULL,'form-horizontal-input-text'),(1,'edit__block_text','activity','activityCustomerID','Cliente',NULL,350,NULL,NULL,'form-horizontal-select'),(1,'edit__block_text','activity','activityCustomerReference','Project Manager',NULL,1000,NULL,NULL,'form-horizontal-input-text'),(1,'edit__block_text','activity','activityDescription','Descrizione',NULL,700,NULL,NULL,'form-horizontal-input-text'),(1,'edit__block_text','activity','activityEnvironmentID','Ambiente',NULL,900,NULL,NULL,'form-horizontal-select'),(1,'edit__block_text','activity','activityID','ID Attività',NULL,100,NULL,NULL,'form-horizontal-input-text'),(1,'edit__block_text','activity','activityIsCompleted','Completata',NULL,2200,NULL,NULL,'form-horizontal-checkbox'),(1,'edit__block_text','activity','activityIsCompletionAuto','Calcolo automatico completamento?',NULL,2100,NULL,NULL,'form-horizontal-checkbox'),(1,'edit__block_text','activity','activityIsEstimatedEffortHoursAuto','Stima automatica effort?',NULL,1300,NULL,NULL,'form-horizontal-checkbox'),(1,'edit__block_text','activity','activityIsOfficial','Ufficiale',NULL,2300,NULL,NULL,'form-horizontal-checkbox'),(1,'edit__block_text','activity','activityManagerNick','Test Manager',NULL,1100,NULL,NULL,'form-horizontal-select'),(1,'edit__block_text','activity','activityName','Nome attività',NULL,400,NULL,NULL,'form-horizontal-input-text'),(1,'edit__block_text','activity','activityParentID','Attività padre',NULL,300,NULL,NULL,'form-horizontal-select'),(1,'edit__block_text','activity','activityQcConnectionID','ID connessione QC',NULL,2400,NULL,NULL,'form-horizontal-select'),(1,'edit__block_text','activity','activityQcIdentificationQuery','Query univoca QC',NULL,2500,NULL,NULL,'form-horizontal-input-text'),(1,'edit__block_text','activity','activityRootID','Attività radice',NULL,200,NULL,NULL,'form-horizontal-select'),(1,'edit__block_text','activity','activityStartDate','Data inizio',NULL,1500,NULL,NULL,'form-horizontal-input-date'),(1,'edit__block_text','activity','activityStatusID','Stato',NULL,1800,NULL,NULL,'form-horizontal-select'),(1,'edit__block_text','activity','activityTypeID','Tipo attività',NULL,500,NULL,NULL,'form-horizontal-select');
/*!40000 ALTER TABLE `entities_linked_rendering_elements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `entities_reference_fields`
--

DROP TABLE IF EXISTS `entities_reference_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entities_reference_fields` (
  `entityCode` varchar(64) NOT NULL,
  `columnName` varchar(128) NOT NULL,
  `fieldIsAutoIncrement` tinyint(1) NOT NULL DEFAULT '0',
  `fieldIsNillable` tinyint(1) NOT NULL DEFAULT '1',
  `fieldType` enum('NUM','CHAR','DATE') NOT NULL DEFAULT 'CHAR',
  `referentialJoinType` enum('inner','left') DEFAULT NULL,
  `referentialTableName` varchar(128) DEFAULT NULL,
  `referentialCodeColumnName` varchar(128) DEFAULT NULL,
  `referentialValueColumnName` varchar(128) DEFAULT NULL,
  `referentialCustomerDependencyColumnName` varchar(128) DEFAULT NULL,
  `fieldComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`entityCode`,`columnName`),
  KEY `FK_entities_reference_fields_entities_entityCode` (`entityCode`),
  KEY `FK_entities_reference_fields` (`columnName`),
  CONSTRAINT `FK_entities_reference_fields_entities_entityCode` FOREIGN KEY (`entityCode`) REFERENCES `entities` (`entityCode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `entities_reference_fields`
--

LOCK TABLES `entities_reference_fields` WRITE;
/*!40000 ALTER TABLE `entities_reference_fields` DISABLE KEYS */;
INSERT INTO `entities_reference_fields` VALUES ('activity','activityAreaID',0,1,'NUM','inner','areas','areaID','areaName','areaCustomerID',NULL),('activity','activityCode',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityColorHexCode',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityComment',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityCompletion',0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityCustomerID',0,1,'NUM','inner','customers','customerID','customerName','customerID',NULL),('activity','activityCustomerReference',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityDescription',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityEnvironmentID',0,1,'NUM','inner','environment','environmentID','environmentName','environmentCustomerID',NULL),('activity','activityEstimatedEffortHours',0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityID',1,0,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsCompleted',0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsCompletionAuto',0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsEstimatedEffortHoursAuto',0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsOfficial',0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityManagerNick',0,1,'CHAR','inner','users','userNick','userName',NULL,NULL),('activity','activityName',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityParentID',0,1,'NUM','left','activities','activityID','activityName','activityCustomerID',NULL),('activity','activityQcConnectionID',0,1,'NUM','left','qc_connections','connID','connName','connCustomerID',NULL),('activity','activityQcIdentificationQuery',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityRootID',0,1,'NUM','left','activities','activityID','activityName','activityCustomerID',NULL),('activity','activityStartDate',0,1,'DATE',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityStatusID',0,1,'NUM','inner','activity_status','statusID','statusName','statusCustomerID',NULL),('activity','activityTypeID',0,1,'NUM','inner','activity_type','activityTypeID','activityTypeName','activityTypeCustomerID',NULL),('activity','activityUserField01',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField02',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField03',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField04',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField05',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField06',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField07',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField08',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField09',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField10',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField11',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField12',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField13',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField14',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField15',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField16',0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `entities_reference_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `environment`
--

DROP TABLE IF EXISTS `environment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `environment` (
  `environmentID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `environmentCustomerID` int(10) unsigned NOT NULL,
  `environmentName` varchar(64) NOT NULL,
  `environmentComment` varchar(128) DEFAULT NULL,
  `environmentOrderIndex` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`environmentID`),
  UNIQUE KEY `relStateID_UNIQUE` (`environmentID`),
  KEY `relStateCustomerID_idx` (`environmentCustomerID`),
  CONSTRAINT `relStateCustomerID` FOREIGN KEY (`environmentCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `environment`
--

LOCK TABLES `environment` WRITE;
/*!40000 ALTER TABLE `environment` DISABLE KEYS */;
INSERT INTO `environment` VALUES (1,1,'System Test','AttivitÃƒÆ’Ã‚Â  in corso in ambiente System Test',100);
/*!40000 ALTER TABLE `environment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meeting_logs`
--

DROP TABLE IF EXISTS `meeting_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `meeting_logs` (
  `meetingLogID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `meetingLogActivityID` int(10) unsigned NOT NULL,
  `meetingLogName` varchar(64) NOT NULL,
  `meetingLogRelatedEventID` int(10) unsigned DEFAULT NULL,
  `meetingLogMeetingDate` datetime NOT NULL,
  `meetingLogAttendeeIDs` varchar(255) NOT NULL,
  `meetingLogCustomerAttendees` varchar(255) DEFAULT NULL,
  `meetingLogReport` mediumtext,
  `meetingLogLastUpdateTime` datetime NOT NULL,
  `meetingLogComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`meetingLogID`),
  KEY `FK_wp_meeting_logs_wp_projectEventID` (`meetingLogRelatedEventID`),
  KEY `FK_wp_meeting_logs_wp_projects_activityID` (`meetingLogActivityID`),
  KEY `IDX_wp_meeting_logs_meetingLogAttendeeIDs` (`meetingLogAttendeeIDs`),
  KEY `IDX_wp_meeting_logs_meetingLogMeetingDate` (`meetingLogMeetingDate`),
  CONSTRAINT `FK_wp_meeting_logs_wp_projects_projectID` FOREIGN KEY (`meetingLogActivityID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_wp_meeting_logs_wp_project_events_projectEventID` FOREIGN KEY (`meetingLogRelatedEventID`) REFERENCES `activity_events` (`eventID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meeting_logs`
--

LOCK TABLES `meeting_logs` WRITE;
/*!40000 ALTER TABLE `meeting_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `meeting_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages` (
  `pageCode` varchar(64) NOT NULL,
  `pageUrl` varchar(128) NOT NULL,
  PRIMARY KEY (`pageCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages_auths`
--

DROP TABLE IF EXISTS `pages_auths`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages_auths` (
  `pageCode` varchar(64) NOT NULL,
  `customerID` int(10) unsigned NOT NULL,
  `authCode` varchar(64) NOT NULL,
  `pageReadWrite` enum('READ','WRITE') NOT NULL DEFAULT 'WRITE',
  `pageFocus` enum('USER','USER_TEAM','ALL') NOT NULL DEFAULT 'ALL',
  PRIMARY KEY (`pageCode`,`customerID`,`authCode`),
  KEY `FK_pages_auths_auths_authCode` (`authCode`),
  KEY `FK_pages_auths_customers_customerID` (`customerID`),
  CONSTRAINT `FK_pages_auths_auths_authCode` FOREIGN KEY (`authCode`) REFERENCES `auths` (`authCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pages_auths_customers_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pages_auths_pages_pageCode` FOREIGN KEY (`pageCode`) REFERENCES `pages` (`pageCode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages_auths`
--

LOCK TABLES `pages_auths` WRITE;
/*!40000 ALTER TABLE `pages_auths` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages_auths` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages_label`
--

DROP TABLE IF EXISTS `pages_label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `pages_label` (
  `pageCode` varchar(64) NOT NULL,
  `customerID` int(10) unsigned NOT NULL,
  `pageLabel` varchar(128) NOT NULL,
  PRIMARY KEY (`customerID`,`pageCode`),
  KEY `FK_pages_label_pages_pageCode` (`pageCode`),
  CONSTRAINT `FK_pages_label_customers_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_pages_label_pages_pageCode` FOREIGN KEY (`pageCode`) REFERENCES `pages` (`pageCode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages_label`
--

LOCK TABLES `pages_label` WRITE;
/*!40000 ALTER TABLE `pages_label` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qc_bugreport`
--

DROP TABLE IF EXISTS `qc_bugreport`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qc_bugreport` (
  `bugreportID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bugreportActivityID` int(10) unsigned NOT NULL,
  `bugreportTypeID` int(10) unsigned NOT NULL,
  `bugreportContent` mediumtext NOT NULL,
  `bugreportReferenceDate` datetime NOT NULL,
  `bugreportComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`bugreportID`),
  UNIQUE KEY `bugreportID_UNIQUE` (`bugreportID`),
  KEY `bugreportProjectID_idx` (`bugreportActivityID`),
  KEY `bugreportReferenceDate` (`bugreportReferenceDate`),
  KEY `bugreportTypeID_idx` (`bugreportTypeID`),
  CONSTRAINT `bugreportProjectID` FOREIGN KEY (`bugreportActivityID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bugreportTypeID` FOREIGN KEY (`bugreportTypeID`) REFERENCES `qc_bugreport_type` (`bugreportTypeID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qc_bugreport`
--

LOCK TABLES `qc_bugreport` WRITE;
/*!40000 ALTER TABLE `qc_bugreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `qc_bugreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qc_bugreport_type`
--

DROP TABLE IF EXISTS `qc_bugreport_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qc_bugreport_type` (
  `bugreportTypeID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `bugreportTypeCustomerID` int(10) unsigned NOT NULL,
  `bugreportTypeName` varchar(64) NOT NULL,
  `bugreportTypeQcConnID` int(10) unsigned NOT NULL,
  `bugreportTypeXAxisQcField` varchar(128) NOT NULL,
  `bugreportTypeYAxisQcField` varchar(128) NOT NULL,
  `bugreportTypeComment` varchar(128) DEFAULT NULL,
  `bugreportTypeOrderIndex` int(10) unsigned NOT NULL DEFAULT '100',
  PRIMARY KEY (`bugreportTypeID`),
  UNIQUE KEY `bugreportTypeID_UNIQUE` (`bugreportTypeID`),
  KEY `bugreportTypeCustomerID_idx` (`bugreportTypeCustomerID`),
  KEY `FK_bugreport_type_qc_connections_connID` (`bugreportTypeQcConnID`),
  CONSTRAINT `bugreportTypeCustomerID` FOREIGN KEY (`bugreportTypeCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_bugreport_type_qc_connections_connID` FOREIGN KEY (`bugreportTypeQcConnID`) REFERENCES `qc_connections` (`connID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qc_bugreport_type`
--

LOCK TABLES `qc_bugreport_type` WRITE;
/*!40000 ALTER TABLE `qc_bugreport_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `qc_bugreport_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `qc_connections`
--

DROP TABLE IF EXISTS `qc_connections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `qc_connections` (
  `connID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `connCustomerID` int(10) unsigned NOT NULL,
  `connName` varchar(128) DEFAULT NULL,
  `connServer` varchar(128) NOT NULL,
  `connUsername` varchar(64) NOT NULL,
  `connPassword` varchar(64) NOT NULL,
  `connDomain` varchar(64) NOT NULL,
  `connProject` varchar(64) NOT NULL,
  PRIMARY KEY (`connID`),
  UNIQUE KEY `connID_UNIQUE` (`connID`),
  KEY `qcCustomerID_idx` (`connCustomerID`),
  CONSTRAINT `qcCustomerID` FOREIGN KEY (`connCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `qc_connections`
--

LOCK TABLES `qc_connections` WRITE;
/*!40000 ALTER TABLE `qc_connections` DISABLE KEYS */;
INSERT INTO `qc_connections` VALUES (1,1,'qc_test','localhost','test','pwd','DEFAULT','prova');
/*!40000 ALTER TABLE `qc_connections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rendering_elements`
--

DROP TABLE IF EXISTS `rendering_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rendering_elements` (
  `elementCode` varchar(64) NOT NULL,
  `elementBaseTag` varchar(64) NOT NULL,
  `elementTemplate` mediumtext NOT NULL,
  `elementComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`elementCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192 COMMENT='Contains the minimal elements used to render entities fields';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rendering_elements`
--

LOCK TABLES `rendering_elements` WRITE;
/*!40000 ALTER TABLE `rendering_elements` DISABLE KEYS */;
INSERT INTO `rendering_elements` VALUES ('form-horizontal-checkbox','checkbox','<div class=\"form-group\" id=\"%ID%-container\">\r\n	<div class=\"col-sm-offset-2 col-sm-10\">\r\n		<div class=\"checkbox\">\r\n			<label id=\"%ID%-label\">\r\n				<input type=\"checkbox\" id=\"%ID%-value\" %IS_CHECKED% data-table=\"%TABLENAME%\" data-field=\"%COLNAME%\"> %LABEL%\r\n			</label>\r\n		</div>\r\n	</div>\r\n</div>','Template per checkbox'),('form-horizontal-disabled-checkbox','checkbox','<div class=\"form-group\" id=\"%ID%-container\">\r\n	<div class=\"col-sm-offset-2 col-sm-10\">\r\n		<div class=\"checkbox\">\r\n			<label id=\"%ID%-label\">\r\n				<input type=\"checkbox\" id=\"%ID%-value\" %IS_CHECKED% data-table=\"%TABLENAME%\" data-field=\"%COLNAME%\" disabled> %LABEL%\r\n			</label>\r\n		</div>\r\n	</div>\r\n</div>','Template per checkbox disabilitata'),('form-horizontal-input-date','input','<div class=\"form-group\" id=\"%ID%-container\">\r\n	<label class=\"col-sm-2 control-label\" id=\"%ID%-label\">%LABEL%</label>\r\n	<div class=\"col-sm-10\">\r\n		<input type=\"date\" class=\"form-control\" id=\"%ID%-value\" placeholder=\"%LABEL%\" data-table=\"%TABLENAME%\" data-field=\"%COLNAME%\" value=\"%VALUE%\">\r\n	</div>\r\n</div>','Template per campo di testo in sola lettura'),('form-horizontal-input-text','input','<div class=\"form-group\" id=\"%ID%-container\">\r\n	<label class=\"col-sm-2 control-label\" id=\"%ID%-label\">%LABEL%</label>\r\n	<div class=\"col-sm-10\">\r\n		<input type=\"text\" class=\"form-control\" id=\"%ID%-value\" placeholder=\"%LABEL%\" data-table=\"%TABLENAME%\" data-field=\"%COLNAME%\" value=\"%VALUE%\">\r\n	</div>\r\n</div>','Template per campo di testo in sola lettura'),('form-horizontal-select','select','<div class=\"form-group\" id=\"%ID%-container\">\r\n	<label class=\"col-sm-2 control-label\" id=\"%ID%-label\">%LABEL%</label>\r\n	<div class=\"col-sm-10\">\r\n		<select class=\"form-control\" id=\"%ID%-value\" data-table=\"%TABLENAME%\" data-field=\"%COLNAME%\">%OPTIONS%</select>\r\n	</div>\r\n</div>','Template per combobox in editing'),('form-horizontal-text','p','<div class=\"form-group\" id=\"%ID%-container\">\r\n	<label class=\"col-sm-2 control-label\" id=\"%ID%-label\">%LABEL%</label>\r\n	<div class=\"col-sm-10\">\r\n		<p class=\"form-control-static\" id=\"%ID%-value\" data-table=\"%TABLENAME%\" data-field=\"%COLNAME%\">%VALUE%</p>\r\n	</div>\r\n</div>','Template per campo di inserimento testo');
/*!40000 ALTER TABLE `rendering_elements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rendering_report_elements`
--

DROP TABLE IF EXISTS `rendering_report_elements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rendering_report_elements` (
  `elementCode` varchar(64) NOT NULL,
  `elementCustomerID` int(10) unsigned NOT NULL,
  `elementReportPreTemplate` mediumtext,
  `elementReportTemplate` mediumtext NOT NULL,
  `elementReportPostTemplate` mediumtext,
  `elementComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`elementCode`,`elementCustomerID`),
  KEY `FK_rendering_report_elements_customers_customerID` (`elementCustomerID`),
  CONSTRAINT `FK_rendering_report_elements_customers_customerID` FOREIGN KEY (`elementCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rendering_report_elements`
--

LOCK TABLES `rendering_report_elements` WRITE;
/*!40000 ALTER TABLE `rendering_report_elements` DISABLE KEYS */;
/*!40000 ALTER TABLE `rendering_report_elements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rendering_types`
--

DROP TABLE IF EXISTS `rendering_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rendering_types` (
  `renderingTypeCode` varchar(64) NOT NULL,
  `renderingTypeName` varchar(64) NOT NULL,
  `renderingTypeBeforeAllSnippet` mediumtext,
  `renderingTypeBeforeEachRecordSnippet` mediumtext,
  `renderingTypeBetweenEachRecordSnippet` mediumtext,
  `renderingTypeAfterEachRecordSnippet` mediumtext,
  `renderingTypeAfterAllSnippet` mediumtext,
  `renderingTypeComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`renderingTypeCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=5461;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rendering_types`
--

LOCK TABLES `rendering_types` WRITE;
/*!40000 ALTER TABLE `rendering_types` DISABLE KEYS */;
INSERT INTO `rendering_types` VALUES ('display__block_text','Block textual (display mode)',NULL,NULL,'<hr>',NULL,NULL,'Il campo viene mostrato testualmente allineato a blocchi (sola lettura)'),('edit__block_text','Block textual (edit mode)',NULL,NULL,'<hr>',NULL,NULL,'Il campo viene mostrato testualmente allineato a blocchi (inserimento)'),('graphical','Graphical',NULL,NULL,NULL,NULL,NULL,'Il campo viene mostrato graficamente'),('inline_text','Inline textual',NULL,NULL,NULL,NULL,NULL,'Il campo viene mostrato testualmente in modo inline');
/*!40000 ALTER TABLE `rendering_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_repository`
--

DROP TABLE IF EXISTS `report_repository`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_repository` (
  `reportID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `reportCustomerID` int(10) unsigned NOT NULL,
  `reportTypeCode` varchar(64) DEFAULT NULL,
  `reportStartDate` datetime NOT NULL,
  `reportEndDate` datetime NOT NULL,
  `reportApprovationDate` datetime NOT NULL,
  `reportDocumentPath` varchar(128) NOT NULL,
  PRIMARY KEY (`reportID`),
  UNIQUE KEY `salID_UNIQUE` (`reportID`),
  KEY `reportTypeID_idx` (`reportTypeCode`),
  KEY `salCustomerID_idx` (`reportCustomerID`),
  KEY `salStartEndDate` (`reportStartDate`,`reportEndDate`),
  CONSTRAINT `FK_report_repository_report_type_reportTypeCode` FOREIGN KEY (`reportTypeCode`) REFERENCES `report_type` (`reportTypeCode`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `reportlCustomerID` FOREIGN KEY (`reportCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_repository`
--

LOCK TABLES `report_repository` WRITE;
/*!40000 ALTER TABLE `report_repository` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_repository` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_type`
--

DROP TABLE IF EXISTS `report_type`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_type` (
  `reportTypeCode` varchar(64) NOT NULL,
  `reportTypeName` varchar(64) NOT NULL,
  `reportTypeComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`reportTypeCode`),
  UNIQUE KEY `reportTypeID_UNIQUE` (`reportTypeCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_type`
--

LOCK TABLES `report_type` WRITE;
/*!40000 ALTER TABLE `report_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_type_linked_components`
--

DROP TABLE IF EXISTS `report_type_linked_components`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_type_linked_components` (
  `reportTypeCode` varchar(64) NOT NULL,
  `customerID` int(10) unsigned NOT NULL,
  `componentCode` varchar(64) NOT NULL,
  `componentNestingLevel` int(10) unsigned NOT NULL,
  `componentLabel` varchar(64) NOT NULL,
  `componentOrderIndex` int(10) unsigned NOT NULL DEFAULT '100',
  `componentGroupingLevel` int(10) DEFAULT NULL,
  PRIMARY KEY (`reportTypeCode`,`customerID`,`componentCode`,`componentNestingLevel`),
  KEY `FK_report_type_components_components_componentCode` (`componentCode`),
  KEY `FK_report_type_components_customers_customerID` (`customerID`),
  CONSTRAINT `FK_report_type_components_customers_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_report_type_components_report_type_reportTypeCode` FOREIGN KEY (`reportTypeCode`) REFERENCES `report_type` (`reportTypeCode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_type_linked_components`
--

LOCK TABLES `report_type_linked_components` WRITE;
/*!40000 ALTER TABLE `report_type_linked_components` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_type_linked_components` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `report_type_specs`
--

DROP TABLE IF EXISTS `report_type_specs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `report_type_specs` (
  `reportTypeCode` varchar(64) NOT NULL,
  `customerID` int(10) unsigned NOT NULL,
  `reportTypeLabel` varchar(128) NOT NULL,
  `reportTypeOutputFormat` varchar(64) NOT NULL,
  `reportTypeFrontMatterTemplate` mediumtext,
  `reportTypeBackMatterTemplate` mediumtext,
  PRIMARY KEY (`reportTypeCode`,`customerID`),
  KEY `FK_report_type_specs_customers_customerID` (`customerID`),
  CONSTRAINT `FK_report_type_specs_customers_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_report_type_specs_report_type_reportTypeCode` FOREIGN KEY (`reportTypeCode`) REFERENCES `report_type` (`reportTypeCode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `report_type_specs`
--

LOCK TABLES `report_type_specs` WRITE;
/*!40000 ALTER TABLE `report_type_specs` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_type_specs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `sessionID` char(40) NOT NULL,
  `userID` int(10) unsigned NOT NULL,
  `customerID` int(10) unsigned NOT NULL,
  `sessionStartDate` datetime NOT NULL,
  `sessionOriginIP` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`sessionID`),
  KEY `FK_sessions_customers_customerID` (`customerID`),
  KEY `FK_sessions_users_userID` (`userID`),
  CONSTRAINT `FK_sessions_customers_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('AAA',1,1,'2014-12-18 17:41:10','127.0.0.1');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `tagID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `tagCustomerID` int(10) unsigned NOT NULL,
  `tagValue` varchar(32) NOT NULL,
  PRIMARY KEY (`tagID`),
  UNIQUE KEY `tagID_UNIQUE` (`tagID`),
  KEY `tagCustomerID_idx` (`tagCustomerID`),
  CONSTRAINT `tagCustomerID` FOREIGN KEY (`tagCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timeslots`
--

DROP TABLE IF EXISTS `timeslots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timeslots` (
  `timeslotID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timeslotActivityID` int(10) unsigned NOT NULL,
  `timeslotUserNick` varchar(64) DEFAULT NULL,
  `timeslotReferenceDate` date NOT NULL,
  `timeslotDuration` decimal(5,2) unsigned NOT NULL,
  `timeslotDescription` mediumtext,
  `timeslotTagID` int(10) unsigned DEFAULT NULL,
  PRIMARY KEY (`timeslotID`),
  UNIQUE KEY `timeslotID_UNIQUE` (`timeslotID`),
  KEY `timeslot_StartTime` (`timeslotReferenceDate`),
  KEY `timeslotProjectID_idx` (`timeslotActivityID`),
  KEY `timeslotTagID_idx` (`timeslotTagID`),
  KEY `timeslotUserID_idx` (`timeslotUserNick`),
  CONSTRAINT `FK_timeslots_users_userNick` FOREIGN KEY (`timeslotUserNick`) REFERENCES `users` (`userNick`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `timeslotProjectID` FOREIGN KEY (`timeslotActivityID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `timeslotTagID` FOREIGN KEY (`timeslotTagID`) REFERENCES `tags` (`tagID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timeslots`
--

LOCK TABLES `timeslots` WRITE;
/*!40000 ALTER TABLE `timeslots` DISABLE KEYS */;
/*!40000 ALTER TABLE `timeslots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_enabled_customers`
--

DROP TABLE IF EXISTS `user_enabled_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_enabled_customers` (
  `userNick` varchar(64) NOT NULL,
  `authorizedCustomerID` int(10) unsigned NOT NULL,
  `userAuthCode` varchar(64) NOT NULL,
  PRIMARY KEY (`userNick`,`authorizedCustomerID`),
  KEY `FK_user_enabled_customers_auths_authCode` (`userAuthCode`),
  KEY `FK_user_enabled_customers_customers_customerID` (`authorizedCustomerID`),
  CONSTRAINT `FK_user_enabled_customers_users_userNick` FOREIGN KEY (`userNick`) REFERENCES `users` (`userNick`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_user_enabled_customers_auths_authCode` FOREIGN KEY (`userAuthCode`) REFERENCES `auths` (`authCode`) ON UPDATE CASCADE,
  CONSTRAINT `FK_user_enabled_customers_customers_customerID` FOREIGN KEY (`authorizedCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_enabled_customers`
--

LOCK TABLES `user_enabled_customers` WRITE;
/*!40000 ALTER TABLE `user_enabled_customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_enabled_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userNick` varchar(64) NOT NULL,
  `userPassword` char(40) DEFAULT NULL,
  `userName` varchar(128) DEFAULT NULL,
  `userDailyWorkingHours` decimal(4,2) DEFAULT '8.00',
  `userIsActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`userNick`),
  UNIQUE KEY `userNick_UNIQUE` (`userNick`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('admin','D033E22AE348AEB5660FC2140AEC35850C4DA997','Admin',8.00,1),('test2','A94A8FE5CCB19BA61C4C0873D391E987982FBBD3','Test2',8.00,1);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping events for database 'gawain'
--

--
-- Dumping routines for database 'gawain'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2015-03-26  0:23:25
