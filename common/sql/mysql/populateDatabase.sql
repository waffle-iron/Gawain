USE `gawain`;
-- MySQL dump 10.13  Distrib 5.6.23, for Win64 (x86_64)
--
-- Host: 10.0.0.12    Database: gawain
-- ------------------------------------------------------
-- Server version	5.5.44-0+deb8u1

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
INSERT INTO `activities` VALUES (9,NULL,1,'Prova padre',1,'123456','Prova di inserimento progetto padre modificato da GUI',1,1,'Nome cliente','admin',NULL,1,'2015-05-22',1,40.00,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(10,9,1,'Prova figlio',1,'2245','Prova progetto figlio',1,1,'Pippo','admin',20.00,0,'2015-05-23',1,20.00,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(11,NULL,2,'Prova dell\'altro cliente',1,NULL,'Prova per verificare che le attività vengano filtrate correttamente',1,1,NULL,NULL,0.00,1,NULL,NULL,0.00,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(45,NULL,1,'Attività inserita da dialogo',1,NULL,NULL,1,NULL,NULL,'admin',NULL,1,'2015-09-01',NULL,NULL,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(46,NULL,1,'Prova evolutiva',2,NULL,'Prova di inserimento di un\'evolutiva',1,1,NULL,'admin',NULL,1,'2015-09-03',NULL,NULL,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(47,NULL,1,'Nonno',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-09-08',NULL,NULL,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(48,47,1,'Papà',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,1,'2015-09-08',NULL,NULL,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(49,48,1,'Figlio',1,NULL,NULL,NULL,NULL,NULL,NULL,15.00,0,'2015-09-08',NULL,NULL,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(50,48,1,'Fratello',1,NULL,NULL,NULL,NULL,NULL,NULL,20.00,0,'2015-09-08',NULL,NULL,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(51,48,1,'Sorella',1,NULL,NULL,NULL,NULL,NULL,NULL,33.00,0,'2015-09-08',NULL,NULL,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(52,47,1,'Mamma',1,NULL,NULL,NULL,NULL,NULL,NULL,8.00,0,'2015-09-08',NULL,NULL,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(53,48,1,'Sorellina',1,NULL,NULL,NULL,NULL,NULL,NULL,4.00,0,'2015-09-08',NULL,NULL,1,0,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
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
INSERT INTO `activity_status` VALUES (1,1,'In Carico','Attività  presa in carico',100),(2,1,'In Corso','Attività in corso',200);
/*!40000 ALTER TABLE `activity_status` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `activity_type`
--

LOCK TABLES `activity_type` WRITE;
/*!40000 ALTER TABLE `activity_type` DISABLE KEYS */;
INSERT INTO `activity_type` VALUES (1,1,'Progetti',1,'Progetti nuovi'),(2,1,'Evolutive',1,'Evolutive su progetti esistenti');
/*!40000 ALTER TABLE `activity_type` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `application_log`
--

LOCK TABLES `application_log` WRITE;
/*!40000 ALTER TABLE `application_log` DISABLE KEYS */;
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
INSERT INTO `entities` VALUES ('activity','Activity','activities','activityCustomerID','Attività generica'),('task','Task','tasks','taskDomainID','Task associata ad una determinata attività'),('timeslot','Timeslot','timeslots','timeslotDomainID','Elemento di timeslot inserito dall\'utente');
/*!40000 ALTER TABLE `entities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entities_columns_label`
--

LOCK TABLES `entities_columns_label` WRITE;
/*!40000 ALTER TABLE `entities_columns_label` DISABLE KEYS */;
INSERT INTO `entities_columns_label` VALUES (1,'activity','activityAreaID','Area di competenza',5000,1000,NULL),(1,'activity','activityCode','Codice attività',4500,1000,NULL),(1,'activity','activityColorHexCode','Colore attività',19000,1000,NULL),(1,'activity','activityComment','Commento',100000,1000,NULL),(1,'activity','activityCompletion','Completamento',13000,1000,NULL),(1,'activity','activityCustomerID','Cliente',0,1000,NULL),(1,'activity','activityCustomerReference','Project Manager',7000,1000,NULL),(1,'activity','activityDescription','Descrizione',8000,1000,NULL),(1,'activity','activityEnvironmentID','Ambiente',5500,1000,NULL),(1,'activity','activityEstimatedEffortHours','Effort stimato',11000,1000,NULL),(1,'activity','activityID','ID Attività',1000,1000,NULL),(1,'activity','activityIsCompleted','Completata',15000,1000,NULL),(1,'activity','activityIsCompletionAuto','Calcolo automatico completamento?',14000,1000,NULL),(1,'activity','activityIsEstimatedEffortHoursAuto','Stima automatica effort?',12000,1000,NULL),(1,'activity','activityIsOfficial','Ufficiale',16000,1000,NULL),(1,'activity','activityManagerNick','Test Manager',6000,1000,NULL),(1,'activity','activityName','Nome attività',2000,1000,NULL),(1,'activity','activityParentID','Attività padre',3000,1000,NULL),(1,'activity','activityQcConnectionID','ID connessione QC',17000,1000,NULL),(1,'activity','activityQcIdentificationQuery','Query univoca QC',18000,1000,NULL),(1,'activity','activityStartDate','Data inizio',9000,1000,NULL),(1,'activity','activityStatusID','Stato',10000,1000,NULL),(1,'activity','activityTypeID','Tipo attività',4000,1000,NULL),(1,'task','taskComment','Commento',4000,1000,NULL),(1,'task','taskDomainID','Cliente',0,1000,NULL),(1,'task','taskID','ID Task',1000,1000,NULL),(1,'task','taskName','Nome Task',3000,1000,NULL),(1,'task','taskRelatedActivityID','Attività',2000,1000,NULL),(1,'timeslot','timeslotActivityID','Attività',2000,1000,NULL),(1,'timeslot','timeslotDescription','Dettaglio consuntivazione',7000,1000,NULL),(1,'timeslot','timeslotDomainID','Cliente',0,1000,NULL),(1,'timeslot','timeslotDuration','Durata',6000,1000,NULL),(1,'timeslot','timeslotID','ID Consuntivazione',1000,1000,NULL),(1,'timeslot','timeslotReferenceDate','Data',5000,1000,NULL),(1,'timeslot','timeslotTaskID','Task',3000,1000,NULL),(1,'timeslot','timeslotUserNick','Utente',4000,1000,NULL);
/*!40000 ALTER TABLE `entities_columns_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entities_label`
--

LOCK TABLES `entities_label` WRITE;
/*!40000 ALTER TABLE `entities_label` DISABLE KEYS */;
INSERT INTO `entities_label` VALUES ('activity',1,'Attività','Attività','Attività generali'),('task',1,'Task','Task',NULL),('timeslot',1,'Consuntivazioni','Consuntivazione',NULL);
/*!40000 ALTER TABLE `entities_label` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `entities_reference_fields`
--

LOCK TABLES `entities_reference_fields` WRITE;
/*!40000 ALTER TABLE `entities_reference_fields` DISABLE KEYS */;
INSERT INTO `entities_reference_fields` VALUES ('activity','activityAreaID',0,0,1,'NUM','left','areas','areaID','areaName','areaCustomerID',NULL),('activity','activityCode',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityColorHexCode',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityComment',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityCompletion',0,0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityCustomerID',0,0,0,'NUM','inner','customers','customerID','customerName','customerID',NULL),('activity','activityCustomerReference',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityDescription',0,0,1,'LONGTEXT',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityEnvironmentID',0,0,1,'NUM','left','environment','environmentID','environmentName','environmentCustomerID',NULL),('activity','activityEstimatedEffortHours',0,0,1,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityID',1,1,0,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsCompleted',0,0,0,'BOOL',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsCompletionAuto',0,0,0,'BOOL',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsEstimatedEffortHoursAuto',0,0,0,'BOOL',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityIsOfficial',0,0,0,'BOOL',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityManagerNick',0,0,1,'CHAR','left','users','userNick','userName',NULL,NULL),('activity','activityName',0,0,0,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityParentID',0,0,1,'NUM','left','activities','activityID','activityName','activityCustomerID',NULL),('activity','activityQcConnectionID',0,0,1,'NUM','left','qc_connections','connID','connName','connCustomerID',NULL),('activity','activityQcIdentificationQuery',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityRootID',0,0,1,'NUM','left','activities','activityID','activityName','activityCustomerID',NULL),('activity','activityStartDate',0,0,1,'DATE',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityStatusID',0,0,1,'NUM','left','activity_status','statusID','statusName','statusCustomerID',NULL),('activity','activityTypeID',0,0,0,'NUM','inner','activity_type','activityTypeID','activityTypeName','activityTypeCustomerID',NULL),('activity','activityUserField01',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField02',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField03',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField04',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField05',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField06',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField07',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField08',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField09',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField10',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField11',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField12',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField13',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField14',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField15',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('activity','activityUserField16',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('task','taskComment',0,0,1,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('task','taskDomainID',0,0,0,'NUM','inner','customers','customerID','customerName','customerID',NULL),('task','taskID',1,1,0,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('task','taskName',0,0,0,'CHAR',NULL,NULL,NULL,NULL,NULL,NULL),('task','taskRelatedActivityID',0,0,0,'NUM','inner','activities','activityID','activityName','activityCustomerID',NULL),('timeslot','timeslotActivityID',0,0,0,'NUM','inner','activities','activityID','activityName','activityCustomerID',NULL),('timeslot','timeslotDescription',0,0,1,'LONGTEXT',NULL,NULL,NULL,NULL,NULL,NULL),('timeslot','timeslotDomainID',0,0,0,'NUM','inner','customers','customerID','customerName','customerID',NULL),('timeslot','timeslotDuration',0,0,0,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('timeslot','timeslotID',1,1,0,'NUM',NULL,NULL,NULL,NULL,NULL,NULL),('timeslot','timeslotReferenceDate',0,0,0,'DATE',NULL,NULL,NULL,NULL,NULL,NULL),('timeslot','timeslotTaskID',0,0,1,'NUM','left','tasks','taskID','taskName','taskDomainID',NULL),('timeslot','timeslotUserNick',0,0,1,'CHAR','left','users','userNick','userName',NULL,NULL);
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
INSERT INTO `modules` VALUES ('activities','Activities',NULL),('login','Login',NULL),('timeslots','Timeslots',NULL);
/*!40000 ALTER TABLE `modules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `modules_auths`
--

LOCK TABLES `modules_auths` WRITE;
/*!40000 ALTER TABLE `modules_auths` DISABLE KEYS */;
INSERT INTO `modules_auths` VALUES ('activities',1,'admin',1),('activities',1,'user',0),('activities',2,'user',0),('timeslots',1,'admin',1),('timeslots',1,'user',1);
/*!40000 ALTER TABLE `modules_auths` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `modules_label`
--

LOCK TABLES `modules_label` WRITE;
/*!40000 ALTER TABLE `modules_label` DISABLE KEYS */;
INSERT INTO `modules_label` VALUES ('activities',1,'Attività',1000),('login',1,'Login',1000),('timeslots',1,'Consuntivazioni',1000);
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
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('02a4e3d3f2da598fb6e38ca6a1b26e33dd9b7b1d','admin',NULL,'2015-06-17 22:47:41','10.0.0.11'),('07f8ed4eb383ec730b5081d0b620d344306a4764','admin',NULL,'2015-06-17 22:50:36','10.0.0.11'),('114a7038d25e844f32c27219a7bbcadf75f5e104','admin',NULL,'2015-06-17 22:48:05','10.0.0.11'),('339b933142965f02212493b0f93b8cccba9690be','admin',NULL,'2015-06-17 22:44:16','10.0.0.11'),('4300f04618013dcbea21d2d8fc1fc79074d51b6f','admin',1,'2015-06-30 22:33:23','10.0.0.11'),('7238ce07c6f0eb9f294057ffc7ce00a89c99b09c','admin',1,'2015-06-30 23:16:24','10.0.0.11'),('8d578b09c11a988875d72bbd35fe14748bdda12c','admin',NULL,'2015-07-01 23:46:57','10.0.0.11'),('9caa0e2d23f3435776ad4be51869d2a990296e3e','admin',1,'2015-06-30 23:10:32','10.0.0.11'),('a3064af84ee9d9f1f5c4ef9b828cf6368f2b991b','admin',2,'2015-07-26 23:25:24','10.0.0.11'),('a673c39b471a4c4e4c31773df33439a1a4ffa7f2','admin',1,'2015-07-01 23:06:33','10.0.0.11'),('AAA','admin',1,'2014-12-18 17:41:10','127.0.0.1'),('bf890a11d49c74ff2558d37170262e5e00958e7d','admin',1,'2015-06-30 23:17:00','10.0.0.11'),('bfa5749173b83bef6d8168eae8f12f62c1bfe2f2','admin',NULL,'2015-07-01 23:45:22','10.0.0.11'),('c153448f65ab6e88c0fecebc06da90fdaf005598','admin',1,'2015-06-30 22:49:10','10.0.0.11'),('c61daa5617e9a17bd1afb563f4c2611983d7a2fa','admin',NULL,'2015-06-17 22:45:57','10.0.0.11'),('d5e485be358f644339b2045d75944a6367aefd47','admin',NULL,'2015-06-17 22:50:51','10.0.0.11'),('d7365ed02de6465525024853d9e20aa451b2521c','admin',NULL,'2015-06-17 22:40:14','10.0.0.11'),('e55395eae8170ec67f787bca37ff16bcd2ed5a00','admin',1,'2015-06-17 22:56:39','10.0.0.11');
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `tasks`
--

LOCK TABLES `tasks` WRITE;
/*!40000 ALTER TABLE `tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping data for table `timeslots`
--

LOCK TABLES `timeslots` WRITE;
/*!40000 ALTER TABLE `timeslots` DISABLE KEYS */;
INSERT INTO `timeslots` VALUES (1,1,9,NULL,'admin','2015-09-08',8.00,NULL),(2,1,10,NULL,'admin','2015-09-08',4.00,NULL),(3,1,47,NULL,'test2','2015-09-08',6.00,NULL),(4,1,48,NULL,'admin','2015-09-08',2.00,NULL),(5,1,48,NULL,'test2','2015-09-09',3.00,NULL),(6,1,50,NULL,'admin','2015-09-08',7.00,NULL);
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

-- Dump completed on 2015-09-29 23:25:30
