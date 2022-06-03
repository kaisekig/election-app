CREATE DATABASE  IF NOT EXISTS `election` /*!40100 DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `election`;
-- MySQL dump 10.13  Distrib 8.0.26, for Win64 (x86_64)
--
-- Host: localhost    Database: election
-- ------------------------------------------------------
-- Server version	8.0.26

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


--
-- Password for test users is identical to user's username
-- 

--
-- Table structure for table `candidate`
--

DROP TABLE IF EXISTS `candidate`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `candidate` (
  `candidate_id` int unsigned NOT NULL AUTO_INCREMENT,
  `forename` varchar(64) NOT NULL DEFAULT '0',
  `surname` varchar(64) NOT NULL DEFAULT '0',
  `party` varchar(16) NOT NULL DEFAULT '0',
  `number` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`candidate_id`),
  UNIQUE KEY `uq_candidate_forename_surname` (`forename`,`surname`),
  UNIQUE KEY `uq_candidate_number` (`number`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `candidate`
--

LOCK TABLES `candidate` WRITE;
/*!40000 ALTER TABLE `candidate` DISABLE KEYS */;
INSERT INTO `candidate` VALUES (1,'John Junior','Doe','JRD',1),(2,'Jane','Doe','JND',2),(3,'Lost','Hope','LH',3);
/*!40000 ALTER TABLE `candidate` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user` (
  `user_id` int unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(64) NOT NULL DEFAULT '0',
  `password` varchar(128) NOT NULL DEFAULT '0',
  `identifier` varchar(16) NOT NULL DEFAULT '0',
  `is_voted` tinyint(1) NOT NULL DEFAULT '0',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `role` enum('user','admin') NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `uq_user_username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'user','$2y$10$ssIUwp5N6BMQn9JYxbDwNePYtmRF6cOa79rFYr3aZsUTzEQJ9g4B6','1000000000000000',0,1,'user','2022-04-29 10:01:16'),(2,'admin','$2y$10$F0oOBBIMD/FHLsRjArEaG.C88IBH6a73kjZKoorIJFJb0fYcfUOkS','2000000000000000',0,1,'admin','2022-04-29 10:04:29');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `vote`
--

DROP TABLE IF EXISTS `vote`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `vote` (
  `vote_id` int unsigned NOT NULL AUTO_INCREMENT,
  `candidate_id` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`vote_id`),
  KEY `fk_vote_candidate_id` (`candidate_id`),
  CONSTRAINT `fk_vote_candidate_id` FOREIGN KEY (`candidate_id`) REFERENCES `candidate` (`candidate_id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `vote`
--

LOCK TABLES `vote` WRITE;
/*!40000 ALTER TABLE `vote` DISABLE KEYS */;
/*!40000 ALTER TABLE `vote` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-04-29 12:09:27
