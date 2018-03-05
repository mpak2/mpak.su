-- MySQL dump 10.13  Distrib 5.5.47, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: 12004a0c5ff3527
-- ------------------------------------------------------
-- Server version	5.5.47-0ubuntu0.14.04.1

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
-- Table structure for table `mp_seo_characters`
--

DROP TABLE IF EXISTS `mp_seo_characters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mp_seo_characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `sort` int(11) NOT NULL,
  `characters_lang_id` int(11) NOT NULL,
  `from` varchar(255) CHARACTER SET cp1251 NOT NULL,
  `to` varchar(255) CHARACTER SET cp1251 NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mp_seo_characters`
--

LOCK TABLES `mp_seo_characters` WRITE;
/*!40000 ALTER TABLE `mp_seo_characters` DISABLE KEYS */;
INSERT INTO `mp_seo_characters` VALUES (3,3,2,'А','a'),(4,4,2,'а','a'),(5,5,2,'Б','b'),(6,6,2,'б','b'),(7,7,2,'В','v'),(8,8,2,'в','v'),(9,9,2,'Г','g'),(10,10,2,'г','g'),(11,11,2,'Д','d'),(12,12,2,'д','d'),(13,13,2,'Е','e'),(14,14,2,'е','e'),(15,15,2,'Ё','jo'),(16,16,2,'ё','jo'),(17,17,2,'Ж','zh'),(18,18,2,'ж','zh'),(19,19,2,'З','z'),(20,20,2,'з','z'),(21,21,2,'И','i'),(22,22,2,'и','i'),(23,23,2,'Й','j'),(24,24,2,'й','j'),(25,25,2,'К','k'),(26,26,2,'к','k'),(27,27,2,'Л','l'),(28,28,2,'л','l'),(29,29,2,'М','m'),(30,30,2,'м','m'),(31,31,2,'Н','n'),(32,32,2,'н','n'),(33,33,2,'О','o'),(34,34,2,'о','o'),(35,35,2,'П','p'),(36,36,2,'п','p'),(37,37,2,'Р','r'),(38,38,2,'р','r'),(39,39,2,'С','s'),(40,40,2,'с','s'),(41,41,2,'Т','t'),(42,42,2,'т','t'),(43,43,2,'У','u'),(44,44,2,'у','u'),(45,45,2,'Ф','f'),(46,46,2,'ф','f'),(47,47,2,'Х','h'),(48,48,2,'х','h'),(49,49,2,'Ц','c'),(50,50,2,'ц','c'),(51,51,2,'Ч','ch'),(52,52,2,'ч','ch'),(53,53,2,'Ш','sh'),(54,54,2,'ш','sh'),(55,55,2,'Щ','sch'),(56,56,2,'щ','sch'),(57,57,1,'Ъ',''),(58,58,1,'ъ',''),(59,59,2,'Э','e'),(60,60,2,'э','e'),(61,61,2,'Ю','ju'),(62,62,2,'ю','ju'),(63,63,2,'Я','ja'),(64,64,2,'я','ja'),(65,65,2,'Ы','y'),(66,66,2,'ы','y'),(67,67,2,'Ь',''),(68,68,2,'ь',''),(70,70,1,'A','a'),(71,71,1,'B','b'),(72,72,1,'C','c'),(73,73,1,'D','d'),(74,74,1,'E','e'),(75,75,1,'F','f'),(76,76,1,'G','g'),(77,77,1,'H','h'),(78,78,1,'I','i'),(79,79,1,'J','j'),(80,80,1,'K','k'),(81,81,1,'L','l'),(82,82,1,'M','m'),(83,83,1,'N','n'),(84,84,1,'O','o'),(85,85,1,'P','p'),(86,86,1,'Q','q'),(87,87,1,'R','r'),(88,88,1,'S','s'),(89,89,1,'T','t'),(90,90,1,'U','u'),(91,91,1,'V','v'),(92,92,1,'W','w'),(93,93,1,'X','x'),(94,94,1,'Y','y'),(95,95,1,'Z','z'),(96,96,0,',',''),(98,98,1,'А','а'),(99,99,1,'Б','б'),(100,100,1,'В','в'),(101,101,1,'Г','г'),(102,102,1,'Д','д'),(103,103,1,'Е','е'),(104,104,1,'Ё','ё'),(105,105,1,'Ж','ж'),(106,106,1,'З','з'),(107,107,1,'И','и'),(108,108,1,'Й','й'),(109,109,1,'К','к'),(110,110,1,'Л','л'),(111,111,1,'М','м'),(112,112,1,'Н','н'),(113,113,1,'О','о'),(114,114,1,'П','п'),(115,115,1,'Р','р'),(116,116,1,'С','с'),(117,117,1,'Т','т'),(118,118,1,'У','у'),(119,119,1,'Ф','ф'),(120,120,1,'Х','х'),(121,121,1,'Ц','ц'),(122,122,1,'Ч','ч'),(123,123,1,'Ш','ш'),(124,124,1,'Щ','щ'),(125,125,1,'Ь','ь'),(126,126,1,'Ы','ы'),(127,127,1,'Ъ','ъ'),(128,128,1,'Э','э'),(129,129,1,'Ю','ю'),(130,130,1,'Я','я'),(131,131,0,';',''),(134,134,0,'\'',''),(136,136,0,'&',''),(137,137,0,'=',''),(138,138,0,' ','_'),(139,139,0,'?',''),(140,140,0,'+',''),(141,141,0,'\"',''),(143,143,0,'*',''),(146,146,0,':',''),(147,147,0,'@',''),(149,149,0,'!',''),(150,150,0,'$',''),(151,151,0,',',''),(153,153,0,'%',''),(154,154,0,'#',''),(155,155,0,'[',''),(156,156,0,']',''),(157,156,0,'«',''),(158,156,0,'»','');
/*!40000 ALTER TABLE `mp_seo_characters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mp_seo_characters_lang`
--

DROP TABLE IF EXISTS `mp_seo_characters_lang`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `mp_seo_characters_lang` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `time` int(11) NOT NULL,
  `uid` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mp_seo_characters_lang`
--

LOCK TABLES `mp_seo_characters_lang` WRITE;
/*!40000 ALTER TABLE `mp_seo_characters_lang` DISABLE KEYS */;
INSERT INTO `mp_seo_characters_lang` VALUES (1,1456598997,1,'Русские'),(2,1456599013,1,'Английские');
/*!40000 ALTER TABLE `mp_seo_characters_lang` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-06 15:18:35
