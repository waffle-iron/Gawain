CREATE DATABASE  IF NOT EXISTS `gawain` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `gawain`;
-- MySQL dump 10.13  Distrib 5.6.23, for Win64 (x86_64)
--
-- Host: 192.168.1.11    Database: gawain
-- ------------------------------------------------------
-- Server version	5.5.46-0+deb8u1

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
  `activityEstimatedEffortHours` decimal(7,2) unsigned DEFAULT '0.00',
  `activityIsEstimatedEffortHoursAuto` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `activityStartDate` date DEFAULT NULL,
  `activityStatusID` int(10) unsigned DEFAULT NULL,
  `activityCompletion` decimal(5,2) unsigned DEFAULT '0.00',
  `activityIsCompletionAuto` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `activityIsCompleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `activityIsOfficial` tinyint(1) unsigned NOT NULL DEFAULT '0',
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
  KEY `FK_activities_qc_connections_connID` (`activityQcConnectionID`),
  KEY `projectParentProjectID` (`activityParentID`),
  CONSTRAINT `FK_activities_qc_connections_connID` FOREIGN KEY (`activityQcConnectionID`) REFERENCES `qc_connections` (`connID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_activities_users_userNick` FOREIGN KEY (`activityManagerNick`) REFERENCES `users` (`userNick`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `projectAreaID` FOREIGN KEY (`activityAreaID`) REFERENCES `areas` (`areaID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `projectCustomerID` FOREIGN KEY (`activityCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectParentProjectID` FOREIGN KEY (`activityParentID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `projectReleaseStateID` FOREIGN KEY (`activityEnvironmentID`) REFERENCES `environment` (`environmentID`) ON UPDATE CASCADE,
  CONSTRAINT `projectStatusID` FOREIGN KEY (`activityStatusID`) REFERENCES `activity_status` (`statusID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `projectTypeID` FOREIGN KEY (`activityTypeID`) REFERENCES `activity_type` (`activityTypeID`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=4096;
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  PRIMARY KEY (`activityTypeID`),
  UNIQUE KEY `projectTypeID_UNIQUE` (`activityTypeID`),
  KEY `ProjectTypeCustomerID_idx` (`activityTypeCustomerID`),
  CONSTRAINT `ProjectTypeCustomerID` FOREIGN KEY (`activityTypeCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `application_log`
--

DROP TABLE IF EXISTS `application_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `application_log` (
  `logID` int(30) unsigned NOT NULL AUTO_INCREMENT,
  `logTimestamp` varchar(32) NOT NULL,
  `logLevel` enum('FATAL ERROR','ERROR','WARNING','INFO','DEBUG') NOT NULL DEFAULT 'INFO',
  `hostname` varchar(255) DEFAULT NULL,
  `userNick` varchar(64) DEFAULT NULL,
  `entity` varchar(64) DEFAULT NULL,
  `module` varchar(64) DEFAULT NULL,
  `message` mediumtext NOT NULL,
  PRIMARY KEY (`logID`),
  KEY `FK_log_users_userNick` (`userNick`),
  KEY `IDX_log_logLevel` (`logLevel`),
  KEY `IDX_log` (`entity`,`module`),
  KEY `UK_log_hostname` (`hostname`),
  KEY `UK_log_timestamp` (`logTimestamp`),
  CONSTRAINT `FK_log_users_userNick` FOREIGN KEY (`userNick`) REFERENCES `users` (`userNick`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Table containing all the application logs';
/*!40101 SET character_set_client = @saved_cs_client */;

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
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `entities`
--

DROP TABLE IF EXISTS `entities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entities` (
  `entityCode` varchar(64) NOT NULL,
  `entityName` varchar(64) NOT NULL,
  `entityReferenceTable` varchar(128) NOT NULL,
  `entityDomainDependencyColumnName` varchar(128) DEFAULT NULL,
  `entityComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`entityCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `entities_columns_label`
--

DROP TABLE IF EXISTS `entities_columns_label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entities_columns_label` (
  `customerID` int(10) unsigned NOT NULL,
  `entityCode` varchar(64) NOT NULL,
  `columnName` varchar(64) NOT NULL,
  `fieldLabel` varchar(128) NOT NULL,
  `fieldOrderingIndex` int(11) NOT NULL DEFAULT '1000',
  `fieldOrderingGroupIndex` int(11) NOT NULL DEFAULT '1000',
  `fieldComment` varchar(256) DEFAULT NULL,
  PRIMARY KEY (`customerID`,`entityCode`,`columnName`),
  KEY `FK_entities_linked_rendering_e` (`entityCode`,`columnName`),
  CONSTRAINT `FK_entities_linked_rendering_e` FOREIGN KEY (`entityCode`, `columnName`) REFERENCES `entities_reference_fields` (`entityCode`, `columnName`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_rendering_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
  `entityItemLabel` varchar(64) DEFAULT NULL,
  `entityComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`entityCode`,`customerID`),
  KEY `FK_entities_label_customers_customerID` (`customerID`),
  CONSTRAINT `FK_entities_label_customers_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_entities_label_entities_entityCode` FOREIGN KEY (`entityCode`) REFERENCES `entities` (`entityCode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `entities_reference_fields`
--

DROP TABLE IF EXISTS `entities_reference_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `entities_reference_fields` (
  `entityCode` varchar(64) NOT NULL,
  `columnName` varchar(128) NOT NULL,
  `fieldIsMainID` tinyint(1) NOT NULL DEFAULT '0',
  `fieldIsAutoIncrement` tinyint(1) NOT NULL DEFAULT '0',
  `fieldIsNillable` tinyint(1) NOT NULL DEFAULT '1',
  `fieldType` enum('NUM','CHAR','DATE','BOOL','LONGTEXT') NOT NULL DEFAULT 'CHAR',
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
-- Table structure for table `modules`
--

DROP TABLE IF EXISTS `modules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules` (
  `moduleCode` varchar(64) NOT NULL,
  `moduleName` varchar(128) NOT NULL,
  `moduleComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`moduleCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modules_auths`
--

DROP TABLE IF EXISTS `modules_auths`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules_auths` (
  `moduleCode` varchar(64) NOT NULL,
  `customerID` int(10) unsigned NOT NULL,
  `groupCode` varchar(64) NOT NULL,
  `writePermission` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`moduleCode`,`customerID`,`groupCode`),
  KEY `FK_pages_auths_auths_authCode` (`groupCode`),
  KEY `FK_pages_auths_customers_customerID` (`customerID`),
  CONSTRAINT `FK_modules_auths_customers_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_modules_auths_modules_moduleCode` FOREIGN KEY (`moduleCode`) REFERENCES `modules` (`moduleCode`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_modules_auths_user_groups_groupCode` FOREIGN KEY (`groupCode`) REFERENCES `user_groups` (`groupCode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `modules_label`
--

DROP TABLE IF EXISTS `modules_label`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `modules_label` (
  `moduleCode` varchar(64) NOT NULL,
  `customerID` int(10) unsigned NOT NULL,
  `moduleLabel` varchar(128) NOT NULL,
  `moduleDisplayOrder` int(10) NOT NULL DEFAULT '1000',
  PRIMARY KEY (`customerID`,`moduleCode`),
  KEY `FK_pages_label_pages_pageCode` (`moduleCode`),
  CONSTRAINT `FK_modules_label_customers_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_modules_label_modules_moduleCode` FOREIGN KEY (`moduleCode`) REFERENCES `modules` (`moduleCode`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

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
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `sessionID` char(40) NOT NULL,
  `userNick` varchar(64) NOT NULL,
  `customerID` int(10) unsigned DEFAULT NULL,
  `sessionStartDate` datetime NOT NULL,
  `sessionHostName` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`sessionID`),
  KEY `FK_sessions_customers_customerID` (`customerID`),
  KEY `FK_sessions_users_userID` (`userNick`),
  CONSTRAINT `FK_sessions_customers_customerID` FOREIGN KEY (`customerID`) REFERENCES `customers` (`customerID`) ON UPDATE CASCADE,
  CONSTRAINT `FK_sessions_users_userNick` FOREIGN KEY (`userNick`) REFERENCES `users` (`userNick`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tasks` (
  `taskID` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `taskDomainID` int(10) unsigned NOT NULL,
  `taskRelatedActivityID` int(10) unsigned NOT NULL,
  `taskName` varchar(128) NOT NULL,
  `taskComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`taskID`),
  KEY `FK_tasks_customers_customerID` (`taskDomainID`),
  KEY `FK_tasks_activities_activityID` (`taskRelatedActivityID`),
  CONSTRAINT `FK_tasks_activities_activityID` FOREIGN KEY (`taskRelatedActivityID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_tasks_customers_customerID` FOREIGN KEY (`taskDomainID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Contains the tasks related to specified activity';
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `timeslots`
--

DROP TABLE IF EXISTS `timeslots`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timeslots` (
  `timeslotID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `timeslotDomainID` int(10) unsigned NOT NULL,
  `timeslotActivityID` int(10) unsigned NOT NULL,
  `timeslotTaskID` int(11) unsigned DEFAULT NULL,
  `timeslotUserNick` varchar(64) DEFAULT NULL,
  `timeslotReferenceDate` date NOT NULL,
  `timeslotDuration` decimal(5,2) unsigned NOT NULL,
  `timeslotDescription` mediumtext,
  PRIMARY KEY (`timeslotID`),
  UNIQUE KEY `timeslotID_UNIQUE` (`timeslotID`),
  KEY `timeslot_StartTime` (`timeslotReferenceDate`),
  KEY `timeslotProjectID_idx` (`timeslotActivityID`),
  KEY `timeslotTagID_idx` (`timeslotTaskID`),
  KEY `timeslotUserID_idx` (`timeslotUserNick`),
  KEY `FK_timeslots_customers_customerID` (`timeslotDomainID`),
  CONSTRAINT `FK_timeslots_customers_customerID` FOREIGN KEY (`timeslotDomainID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_timeslots_tasks_taskID` FOREIGN KEY (`timeslotTaskID`) REFERENCES `tasks` (`taskID`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `FK_timeslots_users_userNick` FOREIGN KEY (`timeslotUserNick`) REFERENCES `users` (`userNick`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `timeslotProjectID` FOREIGN KEY (`timeslotActivityID`) REFERENCES `activities` (`activityID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_enabled_customers`
--

DROP TABLE IF EXISTS `user_enabled_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_enabled_customers` (
  `userNick` varchar(64) NOT NULL,
  `authorizedCustomerID` int(10) unsigned NOT NULL,
  `groupCode` varchar(64) NOT NULL,
  PRIMARY KEY (`userNick`,`authorizedCustomerID`),
  KEY `FK_user_enabled_customers_auths_authCode` (`groupCode`),
  KEY `FK_user_enabled_customers_customers_customerID` (`authorizedCustomerID`),
  CONSTRAINT `FK_user_enabled_customers_customers_customerID` FOREIGN KEY (`authorizedCustomerID`) REFERENCES `customers` (`customerID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_user_enabled_customers_users_userNick` FOREIGN KEY (`userNick`) REFERENCES `users` (`userNick`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_user_enabled_customers_user_groups_groupCode` FOREIGN KEY (`groupCode`) REFERENCES `user_groups` (`groupCode`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `user_groups`
--

DROP TABLE IF EXISTS `user_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_groups` (
  `groupCode` varchar(64) NOT NULL,
  `groupName` varchar(64) NOT NULL,
  `groupComment` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`groupCode`),
  UNIQUE KEY `authID_UNIQUE` (`groupCode`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=16384;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `userNick` varchar(64) NOT NULL,
  `userPassword` char(128) DEFAULT NULL,
  `userName` varchar(128) DEFAULT NULL,
  `userDailyWorkingHours` decimal(4,2) DEFAULT '8.00',
  `userIsActive` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`userNick`),
  UNIQUE KEY `userNick_UNIQUE` (`userNick`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AVG_ROW_LENGTH=8192;
/*!40101 SET character_set_client = @saved_cs_client */;

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

-- Dump completed on 2016-04-13 22:15:58
