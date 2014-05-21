-- MySQL dump 10.13  Distrib 5.5.35, for debian-linux-gnu (i686)
--
-- Host: localhost    Database: zf2_tutorial
-- ------------------------------------------------------
-- Server version	5.5.35-0ubuntu0.12.04.2

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
-- Table structure for table `album`
--

DROP TABLE IF EXISTS `album`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `artist` varchar(100) NOT NULL,
  `title` varchar(100) NOT NULL,
  `price` smallint(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `album`
--

LOCK TABLES `album` WRITE;
/*!40000 ALTER TABLE `album` DISABLE KEYS */;
INSERT INTO `album` VALUES (1,'The  Military  Wives','In  My  Dreams tarun',10),(2,'Adele','21',23),(3,'Bruce  Springsteen','Wrecking Ball (Deluxe)',33),(5,'Gotye','Making  Mirrors',22),(6,'Zend','comply site',63);
/*!40000 ALTER TABLE `album` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobqueue_details`
--

DROP TABLE IF EXISTS `jobqueue_details`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobqueue_details` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `module` varchar(45) NOT NULL,
  `module_id` int(11) NOT NULL,
  `jobqueue_id` int(11) NOT NULL,
  `created_on` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobqueue_details`
--

LOCK TABLES `jobqueue_details` WRITE;
/*!40000 ALTER TABLE `jobqueue_details` DISABLE KEYS */;
INSERT INTO `jobqueue_details` VALUES (1,'action_items',1,46272,'0000-00-00 00:00:00'),(2,'action_items',1,46279,'0000-00-00 00:00:00'),(3,'action_items',1,46296,'0000-00-00 00:00:00'),(4,'test-module',1,46723,'0000-00-00 00:00:00'),(5,'test-module',1,46724,'0000-00-00 00:00:00'),(6,'test-module',1,46841,'0000-00-00 00:00:00'),(7,'Application',1,46994,'0000-00-00 00:00:00'),(8,'Application',1,46997,'0000-00-00 00:00:00'),(9,'Application',1,47114,'0000-00-00 00:00:00'),(10,'Application',1,47137,'0000-00-00 00:00:00'),(11,'Application',1,47140,'0000-00-00 00:00:00'),(12,'Application',1,48409,'0000-00-00 00:00:00'),(13,'Application',1,48410,'0000-00-00 00:00:00'),(14,'Application',1,48411,'0000-00-00 00:00:00'),(15,'Application',1,48412,'0000-00-00 00:00:00'),(16,'Application',1,48413,'0000-00-00 00:00:00'),(17,'Application',1,48414,'0000-00-00 00:00:00'),(18,'Application',1,48415,'0000-00-00 00:00:00'),(19,'Application',1,48416,'0000-00-00 00:00:00'),(20,'Application',1,48417,'0000-00-00 00:00:00');
/*!40000 ALTER TABLE `jobqueue_details` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2014-04-23 12:12:46
