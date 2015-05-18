USE `gawain`;
-- MySQL dump 10.13  Distrib 5.6.23, for Win64 (x86_64)
--
-- Host: 10.0.0.12    Database: gawain
-- ------------------------------------------------------
-- Server version	5.5.43-0+deb7u1

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
-- Dumping data for table `activities`
--

LOCK TABLES `activities` WRITE;
/*!40000 ALTER TABLE `activities` DISABLE KEYS */;
INSERT INTO `activities` VALUES (9,NULL,NULL,1,'Prova padre',1,'123456','Prova di inserimento progetto padre',1,1,'Nome cliente','admin',50.00,1,'2015-03-26',1,40.00,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(10,9,9,1,'Prova figlio',1,'2245','Prova progetto figlio',1,1,'Pippo','admin',20.00,1,'2015-03-26',1,20.00,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `activities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activities_assigned_users`
--

LOCK TABLES `activities_assigned_users` WRITE;
/*!40000 ALTER TABLE `activities_assigned_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `activities_assigned_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activities_associated_qc_bugreport_types`
--

LOCK TABLES `activities_associated_qc_bugreport_types` WRITE;
/*!40000 ALTER TABLE `activities_associated_qc_bugreport_types` DISABLE KEYS */;
/*!40000 ALTER TABLE `activities_associated_qc_bugreport_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_event_type`
--

LOCK TABLES `activity_event_type` WRITE;
/*!40000 ALTER TABLE `activity_event_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_event_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_events`
--

LOCK TABLES `activity_events` WRITE;
/*!40000 ALTER TABLE `activity_events` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_events` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_posts`
--

LOCK TABLES `activity_posts` WRITE;
/*!40000 ALTER TABLE `activity_posts` DISABLE KEYS */;
/*!40000 ALTER TABLE `activity_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_status`
--

LOCK TABLES `activity_status` WRITE;
/*!40000 ALTER TABLE `activity_status` DISABLE KEYS */;
INSERT INTO `activity_status` VALUES (1,1,'In Carico','AttivitÃƒÆ’Ã‚Â  presa in carico',100);
/*!40000 ALTER TABLE `activity_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_type`
--

LOCK TABLES `activity_type` WRITE;
/*!40000 ALTER TABLE `activity_type` DISABLE KEYS */;
INSERT INTO `activity_type` VALUES (1,1,'Progetti',1,'Progetti nuovi',100);
/*!40000 ALTER TABLE `activity_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_log`
--

LOCK TABLES `application_log` WRITE;
/*!40000 ALTER TABLE `application_log` DISABLE KEYS */;
INSERT INTO `application_log` VALUES ('2015-03-30 19:33:46.405849','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:33:55.424221','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:33:55.847820','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:33:56.127533','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:33:56.337381','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:33:56.551921','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:33:56.721674','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:33:56.959757','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:33:57.210617','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:34:28.250713','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:34:28.417287','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:34:28.603863','INFO','localhost','admin','activity','','Testo di prova'),('2015-03-30 19:34:28.772254','INFO','localhost','admin','activity','','Testo di prova');
/*!40000 ALTER TABLE `application_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `areas`
--

LOCK TABLES `areas` WRITE;
/*!40000 ALTER TABLE `areas` DISABLE KEYS */;
INSERT INTO `areas` VALUES (1,1,'Cliente','Progetti che impattano direttamente col cliente',100);
/*!40000 ALTER TABLE `areas` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `components_linked_rendering_report_elements`
--

LOCK TABLES `components_linked_rendering_report_elements` WRITE;
/*!40000 ALTER TABLE `components_linked_rendering_report_elements` DISABLE KEYS */;
/*!40000 ALTER TABLE `components_linked_rendering_report_elements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `customers`
--

LOCK TABLES `customers` WRITE;
/*!40000 ALTER TABLE `customers` DISABLE KEYS */;
INSERT INTO `customers` VALUES (1,'Prova 1','Commento di Prova 1'),(2,'Prova 2','Secondo cliente');
/*!40000 ALTER TABLE `customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `document_repository`
--

LOCK TABLES `document_repository` WRITE;
/*!40000 ALTER TABLE `document_repository` DISABLE KEYS */;
/*!40000 ALTER TABLE `document_repository` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entities`
--

LOCK TABLES `entities` WRITE;
/*!40000 ALTER TABLE `entities` DISABLE KEYS */;
INSERT INTO `entities` VALUES ('activity','Activity','activities','Attività generica'),('timeslot','Timeslot','','Elemento di timeslot inserito dall\'utente');
/*!40000 ALTER TABLE `entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entities_columns_label`
--

LOCK TABLES `entities_columns_label` WRITE;
/*!40000 ALTER TABLE `entities_columns_label` DISABLE KEYS */;
INSERT INTO `entities_columns_label` VALUES (1,'activity','activityAreaID','Area di competenza',NULL,800),(1,'activity','activityCode','Codice attività',NULL,600),(1,'activity','activityColorHexCode','Colore attività',NULL,2600),(1,'activity','activityComment','Commento',NULL,2700),(1,'activity','activityCompletion','Completamento',NULL,2000),(1,'activity','activityCustomerID','Cliente',NULL,350),(1,'activity','activityCustomerReference','Project Manager',NULL,1000),(1,'activity','activityDescription','Descrizione',NULL,700),(1,'activity','activityEnvironmentID','Ambiente',NULL,900),(1,'activity','activityID','ID Attività',NULL,100),(1,'activity','activityIsCompleted','Completata',NULL,2200),(1,'activity','activityIsCompletionAuto','Calcolo automatico completamento?',NULL,2100),(1,'activity','activityIsEstimatedEffortHoursAuto','Stima automatica effort?',NULL,1300),(1,'activity','activityIsOfficial','Ufficiale',NULL,2300),(1,'activity','activityManagerNick','Test Manager',NULL,1100),(1,'activity','activityName','Nome attività',NULL,400),(1,'activity','activityParentID','Attività padre',NULL,300),(1,'activity','activityQcConnectionID','ID connessione QC',NULL,2400),(1,'activity','activityQcIdentificationQuery','Query univoca QC',NULL,2500),(1,'activity','activityRootID','Attività radice',NULL,200),(1,'activity','activityStartDate','Data inizio',NULL,1500),(1,'activity','activityStatusID','Stato',NULL,1800),(1,'activity','activityTypeID','Tipo attività',NULL,500);
/*!40000 ALTER TABLE `entities_columns_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entities_label`
--

LOCK TABLES `entities_label` WRITE;
/*!40000 ALTER TABLE `entities_label` DISABLE KEYS */;
INSERT INTO `entities_label` VALUES ('activity',1,'Attività','Attività generali');
/*!40000 ALTER TABLE `entities_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entities_reference_fields`
--

LOCK TABLES `entities_reference_fields` WRITE;
/*!40000 ALTER TABLE `entities_reference_fields` DISABLE KEYS */;
INSERT INTO `entities_reference_fields` VALUES ('activity','activityAreaID',0,0,1,'NUM','inner','areas','areaID','areaName','areaCustomerID',NULL),('activity','activityCode',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityColorHexCode',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityComment',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityCompletion',0,0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityCustomerID',0,0,1,'NUM','inner','customers','customerID','customerName','customerID',NULL),('activity','activityCustomerReference',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityDescription',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityEnvironmentID',0,0,1,'NUM','inner','environment','environmentID','environmentName','environmentCustomerID',NULL),('activity','activityEstimatedEffortHours',0,0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityID',1,1,0,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsCompleted',0,0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsCompletionAuto',0,0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsEstimatedEffortHoursAuto',0,0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsOfficial',0,0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityManagerNick',0,0,1,'CHAR','inner','users','userNick','userName',NULL,NULL),('activity','activityName',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityParentID',0,0,1,'NUM','left','activities','activityID','activityName','activityCustomerID',NULL),('activity','activityQcConnectionID',0,0,1,'NUM','left','qc_connections','connID','connName','connCustomerID',NULL),('activity','activityQcIdentificationQuery',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityRootID',0,0,1,'NUM','left','activities','activityID','activityName','activityCustomerID',NULL),('activity','activityStartDate',0,0,1,'DATE',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityStatusID',0,0,1,'NUM','inner','activity_status','statusID','statusName','statusCustomerID',NULL),('activity','activityTypeID',0,0,1,'NUM','inner','activity_type','activityTypeID','activityTypeName','activityTypeCustomerID',NULL),('activity','activityUserField01',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField02',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField03',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField04',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField05',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField06',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField07',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField08',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField09',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField10',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField11',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField12',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField13',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField14',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField15',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField16',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `entities_reference_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `environment`
--

LOCK TABLES `environment` WRITE;
/*!40000 ALTER TABLE `environment` DISABLE KEYS */;
INSERT INTO `environment` VALUES (1,1,'System Test','AttivitÃƒÆ’Ã‚Â  in corso in ambiente System Test',100);
/*!40000 ALTER TABLE `environment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `meeting_logs`
--

LOCK TABLES `meeting_logs` WRITE;
/*!40000 ALTER TABLE `meeting_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `meeting_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `modules`
--

LOCK TABLES `modules` WRITE;
/*!40000 ALTER TABLE `modules` DISABLE KEYS */;
INSERT INTO `modules` VALUES ('activities','Activities',NULL),('login','Login',NULL);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `modules_auths`
--

LOCK TABLES `modules_auths` WRITE;
/*!40000 ALTER TABLE `modules_auths` DISABLE KEYS */;
INSERT INTO `modules_auths` VALUES ('activities',1,'admin',1),('activities',1,'user',0);
/*!40000 ALTER TABLE `modules_auths` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `modules_label`
--

LOCK TABLES `modules_label` WRITE;
/*!40000 ALTER TABLE `modules_label` DISABLE KEYS */;
INSERT INTO `modules_label` VALUES ('activities',1,'Attività',1000),('login',1,'Login',1000);
/*!40000 ALTER TABLE `modules_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `qc_bugreport`
--

LOCK TABLES `qc_bugreport` WRITE;
/*!40000 ALTER TABLE `qc_bugreport` DISABLE KEYS */;
/*!40000 ALTER TABLE `qc_bugreport` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `qc_bugreport_type`
--

LOCK TABLES `qc_bugreport_type` WRITE;
/*!40000 ALTER TABLE `qc_bugreport_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `qc_bugreport_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `qc_connections`
--

LOCK TABLES `qc_connections` WRITE;
/*!40000 ALTER TABLE `qc_connections` DISABLE KEYS */;
INSERT INTO `qc_connections` VALUES (1,1,'qc_test','localhost','test','pwd','DEFAULT','prova');
/*!40000 ALTER TABLE `qc_connections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `rendering_report_elements`
--

LOCK TABLES `rendering_report_elements` WRITE;
/*!40000 ALTER TABLE `rendering_report_elements` DISABLE KEYS */;
/*!40000 ALTER TABLE `rendering_report_elements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `report_repository`
--

LOCK TABLES `report_repository` WRITE;
/*!40000 ALTER TABLE `report_repository` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_repository` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `report_type`
--

LOCK TABLES `report_type` WRITE;
/*!40000 ALTER TABLE `report_type` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `report_type_linked_components`
--

LOCK TABLES `report_type_linked_components` WRITE;
/*!40000 ALTER TABLE `report_type_linked_components` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_type_linked_components` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `report_type_specs`
--

LOCK TABLES `report_type_specs` WRITE;
/*!40000 ALTER TABLE `report_type_specs` DISABLE KEYS */;
/*!40000 ALTER TABLE `report_type_specs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('AAA','admin',1,'2014-12-18 17:41:10','127.0.0.1');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `timeslots`
--

LOCK TABLES `timeslots` WRITE;
/*!40000 ALTER TABLE `timeslots` DISABLE KEYS */;
/*!40000 ALTER TABLE `timeslots` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_enabled_customers`
--

LOCK TABLES `user_enabled_customers` WRITE;
/*!40000 ALTER TABLE `user_enabled_customers` DISABLE KEYS */;
INSERT INTO `user_enabled_customers` VALUES ('admin',1,'admin'),('admin',2,'user'),('test2',1,'user');
/*!40000 ALTER TABLE `user_enabled_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `user_groups`
--

LOCK TABLES `user_groups` WRITE;
/*!40000 ALTER TABLE `user_groups` DISABLE KEYS */;
INSERT INTO `user_groups` VALUES ('admin','Administrator','Autorizzazione di amministratore'),('user','User','Autorizzazioni di utente normale');
/*!40000 ALTER TABLE `user_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES ('admin','7407946a7a90b037ba5e825040f184a142161e4c61d81feb83ec8c7f011a99b0d77f39c9170c3231e1003c5cf859c69bd93043b095feff5cce6f6d45ec513764','Admin',8.00,1),('test2','0cd1637c9bf218414ab5b734f21a946d6131f5510b8ae2525538053f8d9d96384025ed1377bc9d606e695598c3504b4f3cfe84517a7b6c5f4ca0ae0abcf4f10a','Test2',8.00,1);
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

-- Dump completed on 2015-05-19  0:23:38
