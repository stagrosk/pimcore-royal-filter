-- MySQL dump 10.13  Distrib 8.0.32, for Linux (x86_64)
--
-- Host: 0.0.0.0    Database: pimcore
-- ------------------------------------------------------
-- Server version	8.0.32

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `assets`
--

DROP TABLE IF EXISTS `assets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parentId` int unsigned DEFAULT NULL,
  `type` varchar(20) DEFAULT NULL,
  `filename` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT '',
  `path` varchar(765) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `mimetype` varchar(190) DEFAULT NULL,
  `creationDate` int unsigned DEFAULT NULL,
  `modificationDate` int unsigned DEFAULT NULL,
  `dataModificationDate` int unsigned DEFAULT NULL,
  `userOwner` int unsigned DEFAULT NULL,
  `userModification` int unsigned DEFAULT NULL,
  `customSettings` longtext,
  `hasMetaData` tinyint(1) NOT NULL DEFAULT '0',
  `versionCount` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fullpath` (`path`,`filename`),
  KEY `parentId` (`parentId`),
  KEY `filename` (`filename`),
  KEY `modificationDate` (`modificationDate`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assets`
--

LOCK TABLES `assets` WRITE;
/*!40000 ALTER TABLE `assets` DISABLE KEYS */;
INSERT INTO `assets` VALUES (1,0,'folder','','/',NULL,1719596425,1719596425,NULL,1,1,NULL,0,0);
/*!40000 ALTER TABLE `assets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assets_image_thumbnail_cache`
--

DROP TABLE IF EXISTS `assets_image_thumbnail_cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assets_image_thumbnail_cache` (
  `cid` int unsigned NOT NULL,
  `name` varchar(190) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL,
  `filename` varchar(190) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin NOT NULL,
  `modificationDate` int unsigned DEFAULT NULL,
  `filesize` int unsigned DEFAULT NULL,
  `width` smallint unsigned DEFAULT NULL,
  `height` smallint unsigned DEFAULT NULL,
  PRIMARY KEY (`cid`,`name`,`filename`),
  CONSTRAINT `FK_assets_image_thumbnail_cache_assets` FOREIGN KEY (`cid`) REFERENCES `assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assets_image_thumbnail_cache`
--

LOCK TABLES `assets_image_thumbnail_cache` WRITE;
/*!40000 ALTER TABLE `assets_image_thumbnail_cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `assets_image_thumbnail_cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `assets_metadata`
--

DROP TABLE IF EXISTS `assets_metadata`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `assets_metadata` (
  `cid` int unsigned NOT NULL,
  `name` varchar(190) NOT NULL,
  `language` varchar(10) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT '',
  `type` enum('input','textarea','asset','document','object','date','select','checkbox') DEFAULT NULL,
  `data` longtext,
  PRIMARY KEY (`cid`,`name`,`language`),
  KEY `name` (`name`),
  CONSTRAINT `FK_assets_metadata_assets` FOREIGN KEY (`cid`) REFERENCES `assets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `assets_metadata`
--

LOCK TABLES `assets_metadata` WRITE;
/*!40000 ALTER TABLE `assets_metadata` DISABLE KEYS */;
/*!40000 ALTER TABLE `assets_metadata` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_items`
--

DROP TABLE IF EXISTS `cache_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_items` (
  `item_id` varbinary(255) NOT NULL,
  `item_data` mediumblob NOT NULL,
  `item_lifetime` int unsigned DEFAULT NULL,
  `item_time` int unsigned NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_items`
--

LOCK TABLES `cache_items` WRITE;
/*!40000 ALTER TABLE `cache_items` DISABLE KEYS */;
INSERT INTO `cache_items` VALUES (_binary 'asset_1',_binary 'O:1:\"©\":2:{s:6:\"ÿÿÿÀ\";O:26:\"Pimcore\\Model\\Asset\\Folder\":22:{s:25:\"\0*\0__dataVersionTimestamp\";i:1719596425;s:7:\"\0*\0path\";s:1:\"/\";s:5:\"\0*\0id\";i:1;s:15:\"\0*\0creationDate\";i:1719596425;s:19:\"\0*\0modificationDate\";i:1719596425;s:15:\"\0*\0versionCount\";i:0;s:12:\"\0*\0userOwner\";i:1;s:9:\"\0*\0locked\";N;s:19:\"\0*\0userModification\";i:1;s:11:\"\0*\0parentId\";i:0;s:12:\"\0*\0_fulldump\";b:0;s:7:\"\0*\0type\";s:6:\"folder\";s:11:\"\0*\0filename\";s:0:\"\";s:11:\"\0*\0mimetype\";N;s:11:\"\0*\0metadata\";a:0:{}s:17:\"\0*\0customSettings\";a:0:{}s:28:\"\0*\0customSettingsCanBeCached\";b:1;s:28:\"\0*\0customSettingsNeedRefresh\";b:1;s:14:\"\0*\0hasMetaData\";b:0;s:11:\"\0*\0siblings\";N;s:14:\"\0*\0dataChanged\";b:0;s:23:\"\0*\0dataModificationDate\";N;}s:7:\"asset_1\";s:6:\"ˆLj=Ti\";}',31536000,1719596608),(_binary 'asset_1tags',_binary 's:6:\"ˆLj=Ti\";',NULL,1719596608),(_binary 'class_companytags',_binary 's:6:\">½\â)\";',NULL,1719599005),(_binary 'document_1',_binary 'O:1:\"©\":2:{s:6:\"ÿÿÿÀ\";O:27:\"Pimcore\\Model\\Document\\Page\":28:{s:25:\"\0*\0__dataVersionTimestamp\";i:1719596425;s:7:\"\0*\0path\";s:1:\"/\";s:5:\"\0*\0id\";i:1;s:15:\"\0*\0creationDate\";i:1719596425;s:19:\"\0*\0modificationDate\";i:1719596425;s:15:\"\0*\0versionCount\";i:0;s:12:\"\0*\0userOwner\";i:1;s:9:\"\0*\0locked\";N;s:19:\"\0*\0userModification\";i:1;s:11:\"\0*\0parentId\";i:0;s:12:\"\0*\0_fulldump\";b:0;s:7:\"\0*\0type\";s:4:\"page\";s:6:\"\0*\0key\";s:0:\"\";s:8:\"\0*\0index\";i:999999;s:12:\"\0*\0published\";b:1;s:11:\"\0*\0siblings\";a:0:{}s:13:\"\0*\0controller\";s:47:\"App\\Controller\\DefaultController::defaultAction\";s:11:\"\0*\0template\";s:0:\"\";s:12:\"\0*\0editables\";N;s:24:\"\0*\0contentMainDocumentId\";N;s:26:\"\0*\0contentMasterDocumentId\";R:22;s:22:\"\0*\0supportsContentMain\";b:1;s:26:\"\0*\0missingRequiredEditable\";N;s:25:\"\0*\0staticGeneratorEnabled\";N;s:26:\"\0*\0staticGeneratorLifetime\";N;s:8:\"\0*\0title\";s:0:\"\";s:14:\"\0*\0description\";s:0:\"\";s:12:\"\0*\0prettyUrl\";N;}s:10:\"document_1\";s:6:\"\îO…§^\";}',31536000,1719596607),(_binary 'document_1tags',_binary 's:6:\"\îO…§^\";',NULL,1719596607),(_binary 'object_1',_binary 'O:1:\"©\":2:{s:6:\"ÿÿÿÀ\";O:31:\"Pimcore\\Model\\DataObject\\Folder\":17:{s:25:\"\0*\0__dataVersionTimestamp\";i:1719596425;s:7:\"\0*\0path\";s:1:\"/\";s:5:\"\0*\0id\";i:1;s:15:\"\0*\0creationDate\";i:1719596425;s:19:\"\0*\0modificationDate\";i:1719596425;s:15:\"\0*\0versionCount\";i:0;s:12:\"\0*\0userOwner\";i:1;s:9:\"\0*\0locked\";N;s:19:\"\0*\0userModification\";i:1;s:11:\"\0*\0parentId\";i:0;s:12:\"\0*\0_fulldump\";b:0;s:7:\"\0*\0type\";s:6:\"folder\";s:6:\"\0*\0key\";s:0:\"\";s:8:\"\0*\0index\";i:999999;s:11:\"\0*\0siblings\";a:0:{}s:17:\"\0*\0childrenSortBy\";N;s:20:\"\0*\0childrenSortOrder\";N;}s:8:\"object_1\";s:6:\"ý‹N1À\";}',31536000,1719596608),(_binary 'object_1tags',_binary 's:6:\"ý‹N1À\";',NULL,1719596608),(_binary 'object_2',_binary 'O:1:\"©\":2:{s:6:\"ÿÿÿÀ\";O:31:\"Pimcore\\Model\\DataObject\\Folder\":17:{s:25:\"\0*\0__dataVersionTimestamp\";i:1719597111;s:7:\"\0*\0path\";s:1:\"/\";s:5:\"\0*\0id\";i:2;s:15:\"\0*\0creationDate\";i:1719597101;s:19:\"\0*\0modificationDate\";i:1719597111;s:15:\"\0*\0versionCount\";i:3;s:12:\"\0*\0userOwner\";i:2;s:9:\"\0*\0locked\";N;s:19:\"\0*\0userModification\";i:2;s:11:\"\0*\0parentId\";i:1;s:12:\"\0*\0_fulldump\";b:0;s:7:\"\0*\0type\";s:6:\"folder\";s:6:\"\0*\0key\";s:16:\"Paper cartridges\";s:8:\"\0*\0index\";i:0;s:11:\"\0*\0siblings\";a:0:{}s:17:\"\0*\0childrenSortBy\";N;s:20:\"\0*\0childrenSortOrder\";N;}s:8:\"object_2\";s:6:\"|ýŒbº\";}',31536000,1719597111),(_binary 'object_2tags',_binary 's:6:\"|ýŒbº\";',NULL,1719597111),(_binary 'object_3',_binary 'O:1:\"©\":3:{s:6:\"ÿÿÿÀ\";O:39:\"Pimcore\\Model\\DataObject\\PaperCartridge\":32:{s:25:\"\0*\0__dataVersionTimestamp\";i:1719599220;s:7:\"\0*\0path\";s:18:\"/Paper cartridges/\";s:5:\"\0*\0id\";i:3;s:15:\"\0*\0creationDate\";i:1719597137;s:19:\"\0*\0modificationDate\";i:1719599220;s:15:\"\0*\0versionCount\";i:12;s:12:\"\0*\0userOwner\";i:2;s:9:\"\0*\0locked\";N;s:19:\"\0*\0userModification\";i:2;s:11:\"\0*\0parentId\";i:2;s:12:\"\0*\0_fulldump\";b:0;s:7:\"\0*\0type\";s:6:\"object\";s:6:\"\0*\0key\";s:5:\"SC757\";s:8:\"\0*\0index\";i:0;s:11:\"\0*\0siblings\";a:0:{}s:17:\"\0*\0childrenSortBy\";N;s:20:\"\0*\0childrenSortOrder\";N;s:19:\"__objectAwareFields\";a:0:{}s:12:\"\0*\0published\";b:1;s:10:\"\0*\0classId\";s:15:\"paper_cartridge\";s:12:\"\0*\0className\";s:14:\"PaperCartridge\";s:7:\"\0*\0name\";s:5:\"SC757\";s:9:\"\0*\0bottom\";N;s:6:\"\0*\0top\";N;s:9:\"\0*\0length\";d:330;s:11:\"\0*\0diameter\";d:130;s:17:\"\0*\0threadPosition\";N;s:21:\"\0*\0diameterWithThread\";N;s:24:\"\0*\0diameterWithoutThread\";N;s:8:\"\0*\0pitch\";N;s:15:\"\0*\0threadImages\";O:42:\"Pimcore\\Model\\DataObject\\Data\\ImageGallery\":4:{s:8:\"\0*\0items\";a:0:{}s:9:\"\0*\0_owner\";N;s:13:\"\0*\0_fieldname\";N;s:12:\"\0*\0_language\";N;}s:9:\"\0*\0images\";O:42:\"Pimcore\\Model\\DataObject\\Data\\ImageGallery\":4:{s:8:\"\0*\0items\";a:0:{}s:9:\"\0*\0_owner\";N;s:13:\"\0*\0_fieldname\";N;s:12:\"\0*\0_language\";N;}}s:8:\"object_3\";s:6:\"pÁ.=‘\";s:21:\"class_paper_cartridge\";s:6:\"\Öu{À\í\Ô\";}',31536000,1719599435),(_binary 'object_3tags',_binary 's:6:\"pÁ.=‘\";',NULL,1719599220),(_binary 'object_4',_binary 'O:1:\"©\":2:{s:6:\"ÿÿÿÀ\";O:31:\"Pimcore\\Model\\DataObject\\Folder\":17:{s:25:\"\0*\0__dataVersionTimestamp\";i:1719598997;s:7:\"\0*\0path\";s:1:\"/\";s:5:\"\0*\0id\";i:4;s:15:\"\0*\0creationDate\";i:1719598997;s:19:\"\0*\0modificationDate\";i:1719598997;s:15:\"\0*\0versionCount\";i:2;s:12:\"\0*\0userOwner\";i:2;s:9:\"\0*\0locked\";N;s:19:\"\0*\0userModification\";i:2;s:11:\"\0*\0parentId\";i:1;s:12:\"\0*\0_fulldump\";b:0;s:7:\"\0*\0type\";s:6:\"folder\";s:6:\"\0*\0key\";s:9:\"Companies\";s:8:\"\0*\0index\";i:0;s:11:\"\0*\0siblings\";a:0:{}s:17:\"\0*\0childrenSortBy\";N;s:20:\"\0*\0childrenSortOrder\";N;}s:8:\"object_4\";s:6:\"—}ƒ\òY/\";}',31536000,1719598997),(_binary 'object_4tags',_binary 's:6:\"—}ƒ\òY/\";',NULL,1719598997),(_binary 'object_5',_binary 'O:1:\"©\":3:{s:6:\"ÿÿÿÀ\";O:32:\"Pimcore\\Model\\DataObject\\Company\":22:{s:25:\"\0*\0__dataVersionTimestamp\";i:1719599009;s:7:\"\0*\0path\";s:11:\"/Companies/\";s:5:\"\0*\0id\";i:5;s:15:\"\0*\0creationDate\";i:1719599005;s:19:\"\0*\0modificationDate\";i:1719599009;s:15:\"\0*\0versionCount\";i:3;s:12:\"\0*\0userOwner\";i:2;s:9:\"\0*\0locked\";N;s:19:\"\0*\0userModification\";i:2;s:11:\"\0*\0parentId\";i:4;s:12:\"\0*\0_fulldump\";b:0;s:7:\"\0*\0type\";s:6:\"object\";s:6:\"\0*\0key\";s:12:\"Allseas Spas\";s:8:\"\0*\0index\";i:0;s:11:\"\0*\0siblings\";a:0:{}s:17:\"\0*\0childrenSortBy\";N;s:20:\"\0*\0childrenSortOrder\";N;s:19:\"__objectAwareFields\";a:0:{}s:12:\"\0*\0published\";b:1;s:10:\"\0*\0classId\";s:7:\"company\";s:12:\"\0*\0className\";s:7:\"Company\";s:7:\"\0*\0name\";s:12:\"Allseas Spas\";}s:8:\"object_5\";s:6:\"#$\";s:13:\"class_company\";s:6:\">½\â)\";}',31536000,1719599009),(_binary 'object_5tags',_binary 's:6:\"#$\";',NULL,1719599009),(_binary 'object_6',_binary 'O:1:\"©\":3:{s:6:\"ÿÿÿÀ\";O:32:\"Pimcore\\Model\\DataObject\\Company\":22:{s:25:\"\0*\0__dataVersionTimestamp\";i:1719599047;s:7:\"\0*\0path\";s:11:\"/Companies/\";s:5:\"\0*\0id\";i:6;s:15:\"\0*\0creationDate\";i:1719599043;s:19:\"\0*\0modificationDate\";i:1719599047;s:15:\"\0*\0versionCount\";i:3;s:12:\"\0*\0userOwner\";i:2;s:9:\"\0*\0locked\";N;s:19:\"\0*\0userModification\";i:2;s:11:\"\0*\0parentId\";i:4;s:12:\"\0*\0_fulldump\";b:0;s:7:\"\0*\0type\";s:6:\"object\";s:6:\"\0*\0key\";s:11:\"Arctic Spas\";s:8:\"\0*\0index\";i:0;s:11:\"\0*\0siblings\";a:0:{}s:17:\"\0*\0childrenSortBy\";N;s:20:\"\0*\0childrenSortOrder\";N;s:19:\"__objectAwareFields\";a:0:{}s:12:\"\0*\0published\";b:1;s:10:\"\0*\0classId\";s:7:\"company\";s:12:\"\0*\0className\";s:7:\"Company\";s:7:\"\0*\0name\";s:11:\"Arctic Spas\";}s:8:\"object_6\";s:6:\"3>j\ã>\";s:13:\"class_company\";s:6:\">½\â)\";}',31536000,1719599047),(_binary 'object_6tags',_binary 's:6:\"3>j\ã>\";',NULL,1719599047),(_binary 'sitetags',_binary 's:6:\"\Z¿\â…\";',NULL,1719596607),(_binary 'site_domain_e27ef24c20235be1aa97cd2f6d28bae0',_binary 'O:1:\"©\":3:{s:6:\"ÿÿÿÀ\";s:6:\"failed\";s:6:\"system\";s:6:\"ø&YX7\";s:4:\"site\";s:6:\"\Z¿\â…\";}',31536000,1719653840),(_binary 'systemtags',_binary 's:6:\"ø&YX7\";',NULL,1719653840),(_binary 'system_resource_columns_edit_lock',_binary 'O:1:\"©\":3:{s:6:\"ÿÿÿÀ\";a:2:{s:7:\"columns\";a:6:{i:0;s:2:\"id\";i:1;s:3:\"cid\";i:2;s:5:\"ctype\";i:3;s:6:\"userId\";i:4;s:9:\"sessionId\";i:5;s:4:\"date\";}s:17:\"primaryKeyColumns\";a:1:{i:0;s:2:\"id\";}}s:6:\"system\";s:6:\"h3thÖ‘\";s:8:\"resource\";s:6:\"h3thÖ‘\";}',31536000,1719599326),(_binary 'system_resource_columns_versions',_binary 'O:1:\"©\":3:{s:6:\"ÿÿÿÀ\";a:2:{s:7:\"columns\";a:14:{i:0;s:2:\"id\";i:1;s:3:\"cid\";i:2;s:5:\"ctype\";i:3;s:6:\"userId\";i:4;s:4:\"note\";i:5;s:10:\"stackTrace\";i:6;s:4:\"date\";i:7;s:6:\"public\";i:8;s:10:\"serialized\";i:9;s:12:\"versionCount\";i:10;s:14:\"binaryFileHash\";i:11;s:12:\"binaryFileId\";i:12;s:8:\"autoSave\";i:13;s:11:\"storageType\";}s:17:\"primaryKeyColumns\";a:1:{i:0;s:2:\"id\";}}s:6:\"system\";s:6:\"YÿaB\àx\";s:8:\"resource\";s:6:\"YÿaB\àx\";}',31536000,1719599231),(_binary 'system_supported_locales_en',_binary 'O:1:\"©\":2:{s:6:\"ÿÿÿÀ\";a:805:{s:2:\"af\";s:9:\"Afrikaans\";s:5:\"af_NA\";s:19:\"Afrikaans (Namibia)\";s:5:\"af_ZA\";s:24:\"Afrikaans (South Africa)\";s:3:\"agq\";s:5:\"Aghem\";s:6:\"agq_CM\";s:16:\"Aghem (Cameroon)\";s:2:\"ak\";s:4:\"Akan\";s:5:\"ak_GH\";s:12:\"Akan (Ghana)\";s:2:\"sq\";s:8:\"Albanian\";s:5:\"sq_AL\";s:18:\"Albanian (Albania)\";s:5:\"sq_XK\";s:17:\"Albanian (Kosovo)\";s:5:\"sq_MK\";s:26:\"Albanian (North Macedonia)\";s:2:\"am\";s:7:\"Amharic\";s:5:\"am_ET\";s:18:\"Amharic (Ethiopia)\";s:2:\"ar\";s:6:\"Arabic\";s:5:\"ar_DZ\";s:16:\"Arabic (Algeria)\";s:5:\"ar_BH\";s:16:\"Arabic (Bahrain)\";s:5:\"ar_TD\";s:13:\"Arabic (Chad)\";s:5:\"ar_KM\";s:16:\"Arabic (Comoros)\";s:5:\"ar_DJ\";s:17:\"Arabic (Djibouti)\";s:5:\"ar_EG\";s:14:\"Arabic (Egypt)\";s:5:\"ar_ER\";s:16:\"Arabic (Eritrea)\";s:5:\"ar_IQ\";s:13:\"Arabic (Iraq)\";s:5:\"ar_IL\";s:15:\"Arabic (Israel)\";s:5:\"ar_JO\";s:15:\"Arabic (Jordan)\";s:5:\"ar_KW\";s:15:\"Arabic (Kuwait)\";s:5:\"ar_LB\";s:16:\"Arabic (Lebanon)\";s:5:\"ar_LY\";s:14:\"Arabic (Libya)\";s:5:\"ar_MR\";s:19:\"Arabic (Mauritania)\";s:5:\"ar_MA\";s:16:\"Arabic (Morocco)\";s:5:\"ar_OM\";s:13:\"Arabic (Oman)\";s:5:\"ar_PS\";s:32:\"Arabic (Palestinian Territories)\";s:5:\"ar_QA\";s:14:\"Arabic (Qatar)\";s:5:\"ar_SA\";s:21:\"Arabic (Saudi Arabia)\";s:5:\"ar_SO\";s:16:\"Arabic (Somalia)\";s:5:\"ar_SS\";s:20:\"Arabic (South Sudan)\";s:5:\"ar_SD\";s:14:\"Arabic (Sudan)\";s:5:\"ar_SY\";s:14:\"Arabic (Syria)\";s:5:\"ar_TN\";s:16:\"Arabic (Tunisia)\";s:5:\"ar_AE\";s:29:\"Arabic (United Arab Emirates)\";s:5:\"ar_EH\";s:23:\"Arabic (Western Sahara)\";s:5:\"ar_YE\";s:14:\"Arabic (Yemen)\";s:6:\"ar_001\";s:14:\"Arabic (world)\";s:2:\"hy\";s:8:\"Armenian\";s:5:\"hy_AM\";s:18:\"Armenian (Armenia)\";s:2:\"as\";s:8:\"Assamese\";s:5:\"as_IN\";s:16:\"Assamese (India)\";s:3:\"ast\";s:8:\"Asturian\";s:6:\"ast_ES\";s:16:\"Asturian (Spain)\";s:3:\"asa\";s:3:\"Asu\";s:6:\"asa_TZ\";s:14:\"Asu (Tanzania)\";s:2:\"az\";s:11:\"Azerbaijani\";s:7:\"az_Cyrl\";s:11:\"Azerbaijani\";s:7:\"az_Latn\";s:11:\"Azerbaijani\";s:10:\"az_Cyrl_AZ\";s:24:\"Azerbaijani (Azerbaijan)\";s:10:\"az_Latn_AZ\";s:24:\"Azerbaijani (Azerbaijan)\";s:3:\"ksf\";s:5:\"Bafia\";s:6:\"ksf_CM\";s:16:\"Bafia (Cameroon)\";s:2:\"bm\";s:7:\"Bambara\";s:5:\"bm_ML\";s:14:\"Bambara (Mali)\";s:2:\"bn\";s:6:\"Bangla\";s:5:\"bn_BD\";s:19:\"Bangla (Bangladesh)\";s:5:\"bn_IN\";s:14:\"Bangla (India)\";s:3:\"bas\";s:5:\"Basaa\";s:6:\"bas_CM\";s:16:\"Basaa (Cameroon)\";s:2:\"eu\";s:6:\"Basque\";s:5:\"eu_ES\";s:14:\"Basque (Spain)\";s:2:\"be\";s:10:\"Belarusian\";s:5:\"be_BY\";s:20:\"Belarusian (Belarus)\";s:3:\"bem\";s:5:\"Bemba\";s:6:\"bem_ZM\";s:14:\"Bemba (Zambia)\";s:3:\"bez\";s:4:\"Bena\";s:6:\"bez_TZ\";s:15:\"Bena (Tanzania)\";s:3:\"bho\";s:8:\"Bhojpuri\";s:6:\"bho_IN\";s:16:\"Bhojpuri (India)\";s:3:\"brx\";s:4:\"Bodo\";s:6:\"brx_IN\";s:12:\"Bodo (India)\";s:2:\"bs\";s:7:\"Bosnian\";s:7:\"bs_Cyrl\";s:7:\"Bosnian\";s:7:\"bs_Latn\";s:7:\"Bosnian\";s:10:\"bs_Cyrl_BA\";s:30:\"Bosnian (Bosnia & Herzegovina)\";s:10:\"bs_Latn_BA\";s:30:\"Bosnian (Bosnia & Herzegovina)\";s:2:\"br\";s:6:\"Breton\";s:5:\"br_FR\";s:15:\"Breton (France)\";s:2:\"bg\";s:9:\"Bulgarian\";s:5:\"bg_BG\";s:20:\"Bulgarian (Bulgaria)\";s:2:\"my\";s:7:\"Burmese\";s:5:\"my_MM\";s:25:\"Burmese (Myanmar (Burma))\";s:3:\"yue\";s:9:\"Cantonese\";s:8:\"yue_Hans\";s:9:\"Cantonese\";s:8:\"yue_Hant\";s:9:\"Cantonese\";s:11:\"yue_Hans_CN\";s:17:\"Cantonese (China)\";s:11:\"yue_Hant_HK\";s:31:\"Cantonese (Hong Kong SAR China)\";s:2:\"ca\";s:7:\"Catalan\";s:5:\"ca_AD\";s:17:\"Catalan (Andorra)\";s:5:\"ca_FR\";s:16:\"Catalan (France)\";s:5:\"ca_IT\";s:15:\"Catalan (Italy)\";s:5:\"ca_ES\";s:15:\"Catalan (Spain)\";s:3:\"ceb\";s:7:\"Cebuano\";s:6:\"ceb_PH\";s:21:\"Cebuano (Philippines)\";s:3:\"tzm\";s:23:\"Central Atlas Tamazight\";s:6:\"tzm_MA\";s:33:\"Central Atlas Tamazight (Morocco)\";s:3:\"ckb\";s:15:\"Central Kurdish\";s:6:\"ckb_IR\";s:22:\"Central Kurdish (Iran)\";s:6:\"ckb_IQ\";s:22:\"Central Kurdish (Iraq)\";s:3:\"ccp\";s:6:\"Chakma\";s:6:\"ccp_BD\";s:19:\"Chakma (Bangladesh)\";s:6:\"ccp_IN\";s:14:\"Chakma (India)\";s:2:\"ce\";s:7:\"Chechen\";s:5:\"ce_RU\";s:16:\"Chechen (Russia)\";s:3:\"chr\";s:8:\"Cherokee\";s:6:\"chr_US\";s:24:\"Cherokee (United States)\";s:3:\"cgg\";s:5:\"Chiga\";s:6:\"cgg_UG\";s:14:\"Chiga (Uganda)\";s:2:\"zh\";s:7:\"Chinese\";s:7:\"zh_Hans\";s:7:\"Chinese\";s:7:\"zh_Hant\";s:7:\"Chinese\";s:10:\"zh_Hans_CN\";s:15:\"Chinese (China)\";s:10:\"zh_Hans_HK\";s:29:\"Chinese (Hong Kong SAR China)\";s:10:\"zh_Hant_HK\";s:29:\"Chinese (Hong Kong SAR China)\";s:10:\"zh_Hans_MO\";s:25:\"Chinese (Macao SAR China)\";s:10:\"zh_Hant_MO\";s:25:\"Chinese (Macao SAR China)\";s:10:\"zh_Hans_SG\";s:19:\"Chinese (Singapore)\";s:10:\"zh_Hant_TW\";s:16:\"Chinese (Taiwan)\";s:2:\"cv\";s:7:\"Chuvash\";s:5:\"cv_RU\";s:16:\"Chuvash (Russia)\";s:3:\"ksh\";s:9:\"Colognian\";s:6:\"ksh_DE\";s:19:\"Colognian (Germany)\";s:2:\"kw\";s:7:\"Cornish\";s:5:\"kw_GB\";s:24:\"Cornish (United Kingdom)\";s:2:\"hr\";s:8:\"Croatian\";s:5:\"hr_BA\";s:31:\"Croatian (Bosnia & Herzegovina)\";s:5:\"hr_HR\";s:18:\"Croatian (Croatia)\";s:2:\"cs\";s:5:\"Czech\";s:5:\"cs_CZ\";s:15:\"Czech (Czechia)\";s:2:\"da\";s:6:\"Danish\";s:5:\"da_DK\";s:16:\"Danish (Denmark)\";s:5:\"da_GL\";s:18:\"Danish (Greenland)\";s:3:\"doi\";s:5:\"Dogri\";s:6:\"doi_IN\";s:13:\"Dogri (India)\";s:3:\"dua\";s:5:\"Duala\";s:6:\"dua_CM\";s:16:\"Duala (Cameroon)\";s:2:\"nl\";s:5:\"Dutch\";s:5:\"nl_AW\";s:13:\"Dutch (Aruba)\";s:5:\"nl_BE\";s:15:\"Dutch (Belgium)\";s:5:\"nl_BQ\";s:29:\"Dutch (Caribbean Netherlands)\";s:5:\"nl_CW\";s:16:\"Dutch (CuraÃ§ao)\";s:5:\"nl_NL\";s:19:\"Dutch (Netherlands)\";s:5:\"nl_SX\";s:20:\"Dutch (Sint Maarten)\";s:5:\"nl_SR\";s:16:\"Dutch (Suriname)\";s:2:\"dz\";s:8:\"Dzongkha\";s:5:\"dz_BT\";s:17:\"Dzongkha (Bhutan)\";s:3:\"ebu\";s:4:\"Embu\";s:6:\"ebu_KE\";s:12:\"Embu (Kenya)\";s:2:\"en\";s:7:\"English\";s:5:\"en_AS\";s:24:\"English (American Samoa)\";s:5:\"en_AI\";s:18:\"English (Anguilla)\";s:5:\"en_AG\";s:27:\"English (Antigua & Barbuda)\";s:5:\"en_AU\";s:19:\"English (Australia)\";s:5:\"en_AT\";s:17:\"English (Austria)\";s:5:\"en_BS\";s:17:\"English (Bahamas)\";s:5:\"en_BB\";s:18:\"English (Barbados)\";s:5:\"en_BE\";s:17:\"English (Belgium)\";s:5:\"en_BZ\";s:16:\"English (Belize)\";s:5:\"en_BM\";s:17:\"English (Bermuda)\";s:5:\"en_BW\";s:18:\"English (Botswana)\";s:5:\"en_IO\";s:40:\"English (British Indian Ocean Territory)\";s:5:\"en_VG\";s:32:\"English (British Virgin Islands)\";s:5:\"en_BI\";s:17:\"English (Burundi)\";s:5:\"en_CM\";s:18:\"English (Cameroon)\";s:5:\"en_CA\";s:16:\"English (Canada)\";s:5:\"en_KY\";s:24:\"English (Cayman Islands)\";s:5:\"en_CX\";s:26:\"English (Christmas Island)\";s:5:\"en_CC\";s:33:\"English (Cocos (Keeling) Islands)\";s:5:\"en_CK\";s:22:\"English (Cook Islands)\";s:5:\"en_CY\";s:16:\"English (Cyprus)\";s:5:\"en_DK\";s:17:\"English (Denmark)\";s:5:\"en_DG\";s:22:\"English (Diego Garcia)\";s:5:\"en_DM\";s:18:\"English (Dominica)\";s:5:\"en_ER\";s:17:\"English (Eritrea)\";s:5:\"en_SZ\";s:18:\"English (Eswatini)\";s:6:\"en_150\";s:16:\"English (Europe)\";s:5:\"en_FK\";s:26:\"English (Falkland Islands)\";s:5:\"en_FJ\";s:14:\"English (Fiji)\";s:5:\"en_FI\";s:17:\"English (Finland)\";s:5:\"en_GM\";s:16:\"English (Gambia)\";s:5:\"en_DE\";s:17:\"English (Germany)\";s:5:\"en_GH\";s:15:\"English (Ghana)\";s:5:\"en_GI\";s:19:\"English (Gibraltar)\";s:5:\"en_GD\";s:17:\"English (Grenada)\";s:5:\"en_GU\";s:14:\"English (Guam)\";s:5:\"en_GG\";s:18:\"English (Guernsey)\";s:5:\"en_GY\";s:16:\"English (Guyana)\";s:5:\"en_HK\";s:29:\"English (Hong Kong SAR China)\";s:5:\"en_IN\";s:15:\"English (India)\";s:5:\"en_IE\";s:17:\"English (Ireland)\";s:5:\"en_IM\";s:21:\"English (Isle of Man)\";s:5:\"en_IL\";s:16:\"English (Israel)\";s:5:\"en_JM\";s:17:\"English (Jamaica)\";s:5:\"en_JE\";s:16:\"English (Jersey)\";s:5:\"en_KE\";s:15:\"English (Kenya)\";s:5:\"en_KI\";s:18:\"English (Kiribati)\";s:5:\"en_LS\";s:17:\"English (Lesotho)\";s:5:\"en_LR\";s:17:\"English (Liberia)\";s:5:\"en_MO\";s:25:\"English (Macao SAR China)\";s:5:\"en_MG\";s:20:\"English (Madagascar)\";s:5:\"en_MW\";s:16:\"English (Malawi)\";s:5:\"en_MY\";s:18:\"English (Malaysia)\";s:5:\"en_MV\";s:18:\"English (Maldives)\";s:5:\"en_MT\";s:15:\"English (Malta)\";s:5:\"en_MH\";s:26:\"English (Marshall Islands)\";s:5:\"en_MU\";s:19:\"English (Mauritius)\";s:5:\"en_FM\";s:20:\"English (Micronesia)\";s:5:\"en_MS\";s:20:\"English (Montserrat)\";s:5:\"en_NA\";s:17:\"English (Namibia)\";s:5:\"en_NR\";s:15:\"English (Nauru)\";s:5:\"en_NL\";s:21:\"English (Netherlands)\";s:5:\"en_NZ\";s:21:\"English (New Zealand)\";s:5:\"en_NG\";s:17:\"English (Nigeria)\";s:5:\"en_NU\";s:14:\"English (Niue)\";s:5:\"en_NF\";s:24:\"English (Norfolk Island)\";s:5:\"en_MP\";s:34:\"English (Northern Mariana Islands)\";s:5:\"en_PK\";s:18:\"English (Pakistan)\";s:5:\"en_PW\";s:15:\"English (Palau)\";s:5:\"en_PG\";s:26:\"English (Papua New Guinea)\";s:5:\"en_PH\";s:21:\"English (Philippines)\";s:5:\"en_PN\";s:26:\"English (Pitcairn Islands)\";s:5:\"en_PR\";s:21:\"English (Puerto Rico)\";s:5:\"en_RW\";s:16:\"English (Rwanda)\";s:5:\"en_WS\";s:15:\"English (Samoa)\";s:5:\"en_SC\";s:20:\"English (Seychelles)\";s:5:\"en_SL\";s:22:\"English (Sierra Leone)\";s:5:\"en_SG\";s:19:\"English (Singapore)\";s:5:\"en_SX\";s:22:\"English (Sint Maarten)\";s:5:\"en_SI\";s:18:\"English (Slovenia)\";s:5:\"en_SB\";s:25:\"English (Solomon Islands)\";s:5:\"en_ZA\";s:22:\"English (South Africa)\";s:5:\"en_SS\";s:21:\"English (South Sudan)\";s:5:\"en_SH\";s:20:\"English (St. Helena)\";s:5:\"en_KN\";s:27:\"English (St. Kitts & Nevis)\";s:5:\"en_LC\";s:19:\"English (St. Lucia)\";s:5:\"en_VC\";s:34:\"English (St. Vincent & Grenadines)\";s:5:\"en_SD\";s:15:\"English (Sudan)\";s:5:\"en_SE\";s:16:\"English (Sweden)\";s:5:\"en_CH\";s:21:\"English (Switzerland)\";s:5:\"en_TZ\";s:18:\"English (Tanzania)\";s:5:\"en_TK\";s:17:\"English (Tokelau)\";s:5:\"en_TO\";s:15:\"English (Tonga)\";s:5:\"en_TT\";s:27:\"English (Trinidad & Tobago)\";s:5:\"en_TC\";s:32:\"English (Turks & Caicos Islands)\";s:5:\"en_TV\";s:16:\"English (Tuvalu)\";s:5:\"en_UM\";s:31:\"English (U.S. Outlying Islands)\";s:5:\"en_VI\";s:29:\"English (U.S. Virgin Islands)\";s:5:\"en_UG\";s:16:\"English (Uganda)\";s:5:\"en_AE\";s:30:\"English (United Arab Emirates)\";s:5:\"en_GB\";s:24:\"English (United Kingdom)\";s:5:\"en_US\";s:23:\"English (United States)\";s:11:\"en_US_POSIX\";s:23:\"English (United States)\";s:5:\"en_VU\";s:17:\"English (Vanuatu)\";s:5:\"en_ZM\";s:16:\"English (Zambia)\";s:5:\"en_ZW\";s:18:\"English (Zimbabwe)\";s:6:\"en_001\";s:15:\"English (world)\";s:2:\"eo\";s:9:\"Esperanto\";s:6:\"eo_001\";s:17:\"Esperanto (world)\";s:2:\"et\";s:8:\"Estonian\";s:5:\"et_EE\";s:18:\"Estonian (Estonia)\";s:2:\"ee\";s:3:\"Ewe\";s:5:\"ee_GH\";s:11:\"Ewe (Ghana)\";s:5:\"ee_TG\";s:10:\"Ewe (Togo)\";s:3:\"ewo\";s:6:\"Ewondo\";s:6:\"ewo_CM\";s:17:\"Ewondo (Cameroon)\";s:2:\"fo\";s:7:\"Faroese\";s:5:\"fo_DK\";s:17:\"Faroese (Denmark)\";s:5:\"fo_FO\";s:23:\"Faroese (Faroe Islands)\";s:3:\"fil\";s:8:\"Filipino\";s:6:\"fil_PH\";s:22:\"Filipino (Philippines)\";s:2:\"fi\";s:7:\"Finnish\";s:5:\"fi_FI\";s:17:\"Finnish (Finland)\";s:2:\"fr\";s:6:\"French\";s:5:\"fr_DZ\";s:16:\"French (Algeria)\";s:5:\"fr_BE\";s:16:\"French (Belgium)\";s:5:\"fr_BJ\";s:14:\"French (Benin)\";s:5:\"fr_BF\";s:21:\"French (Burkina Faso)\";s:5:\"fr_BI\";s:16:\"French (Burundi)\";s:5:\"fr_CM\";s:17:\"French (Cameroon)\";s:5:\"fr_CA\";s:15:\"French (Canada)\";s:5:\"fr_CF\";s:33:\"French (Central African Republic)\";s:5:\"fr_TD\";s:13:\"French (Chad)\";s:5:\"fr_KM\";s:16:\"French (Comoros)\";s:5:\"fr_CG\";s:28:\"French (Congo - Brazzaville)\";s:5:\"fr_CD\";s:25:\"French (Congo - Kinshasa)\";s:5:\"fr_CI\";s:25:\"French (CÃ´te dâ€™Ivoire)\";s:5:\"fr_DJ\";s:17:\"French (Djibouti)\";s:5:\"fr_GQ\";s:26:\"French (Equatorial Guinea)\";s:5:\"fr_FR\";s:15:\"French (France)\";s:5:\"fr_GF\";s:22:\"French (French Guiana)\";s:5:\"fr_PF\";s:25:\"French (French Polynesia)\";s:5:\"fr_GA\";s:14:\"French (Gabon)\";s:5:\"fr_GP\";s:19:\"French (Guadeloupe)\";s:5:\"fr_GN\";s:15:\"French (Guinea)\";s:5:\"fr_HT\";s:14:\"French (Haiti)\";s:5:\"fr_LU\";s:19:\"French (Luxembourg)\";s:5:\"fr_MG\";s:19:\"French (Madagascar)\";s:5:\"fr_ML\";s:13:\"French (Mali)\";s:5:\"fr_MQ\";s:19:\"French (Martinique)\";s:5:\"fr_MR\";s:19:\"French (Mauritania)\";s:5:\"fr_MU\";s:18:\"French (Mauritius)\";s:5:\"fr_YT\";s:16:\"French (Mayotte)\";s:5:\"fr_MC\";s:15:\"French (Monaco)\";s:5:\"fr_MA\";s:16:\"French (Morocco)\";s:5:\"fr_NC\";s:22:\"French (New Caledonia)\";s:5:\"fr_NE\";s:14:\"French (Niger)\";s:5:\"fr_RW\";s:15:\"French (Rwanda)\";s:5:\"fr_RE\";s:17:\"French (RÃ©union)\";s:5:\"fr_SN\";s:16:\"French (Senegal)\";s:5:\"fr_SC\";s:19:\"French (Seychelles)\";s:5:\"fr_BL\";s:24:\"French (St. BarthÃ©lemy)\";s:5:\"fr_MF\";s:19:\"French (St. Martin)\";s:5:\"fr_PM\";s:30:\"French (St. Pierre & Miquelon)\";s:5:\"fr_CH\";s:20:\"French (Switzerland)\";s:5:\"fr_SY\";s:14:\"French (Syria)\";s:5:\"fr_TG\";s:13:\"French (Togo)\";s:5:\"fr_TN\";s:16:\"French (Tunisia)\";s:5:\"fr_VU\";s:16:\"French (Vanuatu)\";s:5:\"fr_WF\";s:24:\"French (Wallis & Futuna)\";s:3:\"fur\";s:8:\"Friulian\";s:6:\"fur_IT\";s:16:\"Friulian (Italy)\";s:2:\"ff\";s:4:\"Fula\";s:7:\"ff_Adlm\";s:4:\"Fula\";s:7:\"ff_Latn\";s:4:\"Fula\";s:10:\"ff_Adlm_BF\";s:19:\"Fula (Burkina Faso)\";s:10:\"ff_Latn_BF\";s:19:\"Fula (Burkina Faso)\";s:10:\"ff_Adlm_CM\";s:15:\"Fula (Cameroon)\";s:10:\"ff_Latn_CM\";s:15:\"Fula (Cameroon)\";s:10:\"ff_Adlm_GM\";s:13:\"Fula (Gambia)\";s:10:\"ff_Latn_GM\";s:13:\"Fula (Gambia)\";s:10:\"ff_Adlm_GH\";s:12:\"Fula (Ghana)\";s:10:\"ff_Latn_GH\";s:12:\"Fula (Ghana)\";s:10:\"ff_Adlm_GN\";s:13:\"Fula (Guinea)\";s:10:\"ff_Latn_GN\";s:13:\"Fula (Guinea)\";s:10:\"ff_Adlm_GW\";s:20:\"Fula (Guinea-Bissau)\";s:10:\"ff_Latn_GW\";s:20:\"Fula (Guinea-Bissau)\";s:10:\"ff_Adlm_LR\";s:14:\"Fula (Liberia)\";s:10:\"ff_Latn_LR\";s:14:\"Fula (Liberia)\";s:10:\"ff_Adlm_MR\";s:17:\"Fula (Mauritania)\";s:10:\"ff_Latn_MR\";s:17:\"Fula (Mauritania)\";s:10:\"ff_Adlm_NE\";s:12:\"Fula (Niger)\";s:10:\"ff_Latn_NE\";s:12:\"Fula (Niger)\";s:10:\"ff_Adlm_NG\";s:14:\"Fula (Nigeria)\";s:10:\"ff_Latn_NG\";s:14:\"Fula (Nigeria)\";s:10:\"ff_Adlm_SN\";s:14:\"Fula (Senegal)\";s:10:\"ff_Latn_SN\";s:14:\"Fula (Senegal)\";s:10:\"ff_Adlm_SL\";s:19:\"Fula (Sierra Leone)\";s:10:\"ff_Latn_SL\";s:19:\"Fula (Sierra Leone)\";s:2:\"gl\";s:8:\"Galician\";s:5:\"gl_ES\";s:16:\"Galician (Spain)\";s:2:\"lg\";s:5:\"Ganda\";s:5:\"lg_UG\";s:14:\"Ganda (Uganda)\";s:2:\"ka\";s:8:\"Georgian\";s:5:\"ka_GE\";s:18:\"Georgian (Georgia)\";s:2:\"de\";s:6:\"German\";s:5:\"de_AT\";s:16:\"German (Austria)\";s:5:\"de_BE\";s:16:\"German (Belgium)\";s:5:\"de_DE\";s:16:\"German (Germany)\";s:5:\"de_IT\";s:14:\"German (Italy)\";s:5:\"de_LI\";s:22:\"German (Liechtenstein)\";s:5:\"de_LU\";s:19:\"German (Luxembourg)\";s:5:\"de_CH\";s:20:\"German (Switzerland)\";s:2:\"el\";s:5:\"Greek\";s:5:\"el_CY\";s:14:\"Greek (Cyprus)\";s:5:\"el_GR\";s:14:\"Greek (Greece)\";s:2:\"gu\";s:8:\"Gujarati\";s:5:\"gu_IN\";s:16:\"Gujarati (India)\";s:3:\"guz\";s:5:\"Gusii\";s:6:\"guz_KE\";s:13:\"Gusii (Kenya)\";s:3:\"bgc\";s:8:\"Haryanvi\";s:6:\"bgc_IN\";s:16:\"Haryanvi (India)\";s:2:\"ha\";s:5:\"Hausa\";s:5:\"ha_GH\";s:13:\"Hausa (Ghana)\";s:5:\"ha_NE\";s:13:\"Hausa (Niger)\";s:5:\"ha_NG\";s:15:\"Hausa (Nigeria)\";s:3:\"haw\";s:8:\"Hawaiian\";s:6:\"haw_US\";s:24:\"Hawaiian (United States)\";s:2:\"he\";s:6:\"Hebrew\";s:5:\"he_IL\";s:15:\"Hebrew (Israel)\";s:2:\"hi\";s:5:\"Hindi\";s:7:\"hi_Latn\";s:5:\"Hindi\";s:5:\"hi_IN\";s:13:\"Hindi (India)\";s:10:\"hi_Latn_IN\";s:13:\"Hindi (India)\";s:2:\"hu\";s:9:\"Hungarian\";s:5:\"hu_HU\";s:19:\"Hungarian (Hungary)\";s:2:\"is\";s:9:\"Icelandic\";s:5:\"is_IS\";s:19:\"Icelandic (Iceland)\";s:2:\"ig\";s:4:\"Igbo\";s:5:\"ig_NG\";s:14:\"Igbo (Nigeria)\";s:3:\"smn\";s:10:\"Inari Sami\";s:6:\"smn_FI\";s:20:\"Inari Sami (Finland)\";s:2:\"id\";s:10:\"Indonesian\";s:5:\"id_ID\";s:22:\"Indonesian (Indonesia)\";s:2:\"ia\";s:11:\"Interlingua\";s:6:\"ia_001\";s:19:\"Interlingua (world)\";s:2:\"ga\";s:5:\"Irish\";s:5:\"ga_IE\";s:15:\"Irish (Ireland)\";s:5:\"ga_GB\";s:22:\"Irish (United Kingdom)\";s:2:\"it\";s:7:\"Italian\";s:5:\"it_IT\";s:15:\"Italian (Italy)\";s:5:\"it_SM\";s:20:\"Italian (San Marino)\";s:5:\"it_CH\";s:21:\"Italian (Switzerland)\";s:5:\"it_VA\";s:22:\"Italian (Vatican City)\";s:2:\"ja\";s:8:\"Japanese\";s:5:\"ja_JP\";s:16:\"Japanese (Japan)\";s:2:\"jv\";s:8:\"Javanese\";s:5:\"jv_ID\";s:20:\"Javanese (Indonesia)\";s:3:\"dyo\";s:10:\"Jola-Fonyi\";s:6:\"dyo_SN\";s:20:\"Jola-Fonyi (Senegal)\";s:3:\"kea\";s:12:\"Kabuverdianu\";s:6:\"kea_CV\";s:25:\"Kabuverdianu (Cape Verde)\";s:3:\"kab\";s:6:\"Kabyle\";s:6:\"kab_DZ\";s:16:\"Kabyle (Algeria)\";s:3:\"kgp\";s:8:\"Kaingang\";s:6:\"kgp_BR\";s:17:\"Kaingang (Brazil)\";s:3:\"kkj\";s:4:\"Kako\";s:6:\"kkj_CM\";s:15:\"Kako (Cameroon)\";s:2:\"kl\";s:11:\"Kalaallisut\";s:5:\"kl_GL\";s:23:\"Kalaallisut (Greenland)\";s:3:\"kln\";s:8:\"Kalenjin\";s:6:\"kln_KE\";s:16:\"Kalenjin (Kenya)\";s:3:\"kam\";s:5:\"Kamba\";s:6:\"kam_KE\";s:13:\"Kamba (Kenya)\";s:2:\"kn\";s:7:\"Kannada\";s:5:\"kn_IN\";s:15:\"Kannada (India)\";s:2:\"ks\";s:8:\"Kashmiri\";s:7:\"ks_Arab\";s:8:\"Kashmiri\";s:7:\"ks_Deva\";s:8:\"Kashmiri\";s:10:\"ks_Arab_IN\";s:16:\"Kashmiri (India)\";s:10:\"ks_Deva_IN\";s:16:\"Kashmiri (India)\";s:2:\"kk\";s:6:\"Kazakh\";s:5:\"kk_KZ\";s:19:\"Kazakh (Kazakhstan)\";s:2:\"km\";s:5:\"Khmer\";s:5:\"km_KH\";s:16:\"Khmer (Cambodia)\";s:2:\"ki\";s:6:\"Kikuyu\";s:5:\"ki_KE\";s:14:\"Kikuyu (Kenya)\";s:2:\"rw\";s:11:\"Kinyarwanda\";s:5:\"rw_RW\";s:20:\"Kinyarwanda (Rwanda)\";s:3:\"kok\";s:7:\"Konkani\";s:6:\"kok_IN\";s:15:\"Konkani (India)\";s:2:\"ko\";s:6:\"Korean\";s:5:\"ko_KP\";s:20:\"Korean (North Korea)\";s:5:\"ko_KR\";s:20:\"Korean (South Korea)\";s:3:\"khq\";s:12:\"Koyra Chiini\";s:6:\"khq_ML\";s:19:\"Koyra Chiini (Mali)\";s:3:\"ses\";s:15:\"Koyraboro Senni\";s:6:\"ses_ML\";s:22:\"Koyraboro Senni (Mali)\";s:2:\"ku\";s:7:\"Kurdish\";s:5:\"ku_TR\";s:16:\"Kurdish (Turkey)\";s:3:\"nmg\";s:6:\"Kwasio\";s:6:\"nmg_CM\";s:17:\"Kwasio (Cameroon)\";s:2:\"ky\";s:6:\"Kyrgyz\";s:5:\"ky_KG\";s:19:\"Kyrgyz (Kyrgyzstan)\";s:3:\"lkt\";s:6:\"Lakota\";s:6:\"lkt_US\";s:22:\"Lakota (United States)\";s:3:\"lag\";s:5:\"Langi\";s:6:\"lag_TZ\";s:16:\"Langi (Tanzania)\";s:2:\"lo\";s:3:\"Lao\";s:5:\"lo_LA\";s:10:\"Lao (Laos)\";s:2:\"lv\";s:7:\"Latvian\";s:5:\"lv_LV\";s:16:\"Latvian (Latvia)\";s:2:\"ln\";s:7:\"Lingala\";s:5:\"ln_AO\";s:16:\"Lingala (Angola)\";s:5:\"ln_CF\";s:34:\"Lingala (Central African Republic)\";s:5:\"ln_CG\";s:29:\"Lingala (Congo - Brazzaville)\";s:5:\"ln_CD\";s:26:\"Lingala (Congo - Kinshasa)\";s:2:\"lt\";s:10:\"Lithuanian\";s:5:\"lt_LT\";s:22:\"Lithuanian (Lithuania)\";s:3:\"dsb\";s:13:\"Lower Sorbian\";s:6:\"dsb_DE\";s:23:\"Lower Sorbian (Germany)\";s:2:\"lu\";s:12:\"Luba-Katanga\";s:5:\"lu_CD\";s:31:\"Luba-Katanga (Congo - Kinshasa)\";s:3:\"luo\";s:3:\"Luo\";s:6:\"luo_KE\";s:11:\"Luo (Kenya)\";s:2:\"lb\";s:13:\"Luxembourgish\";s:5:\"lb_LU\";s:26:\"Luxembourgish (Luxembourg)\";s:3:\"luy\";s:5:\"Luyia\";s:6:\"luy_KE\";s:13:\"Luyia (Kenya)\";s:2:\"mk\";s:10:\"Macedonian\";s:5:\"mk_MK\";s:28:\"Macedonian (North Macedonia)\";s:3:\"jmc\";s:7:\"Machame\";s:6:\"jmc_TZ\";s:18:\"Machame (Tanzania)\";s:3:\"mai\";s:8:\"Maithili\";s:6:\"mai_IN\";s:16:\"Maithili (India)\";s:3:\"mgh\";s:14:\"Makhuwa-Meetto\";s:6:\"mgh_MZ\";s:27:\"Makhuwa-Meetto (Mozambique)\";s:3:\"kde\";s:7:\"Makonde\";s:6:\"kde_TZ\";s:18:\"Makonde (Tanzania)\";s:2:\"mg\";s:8:\"Malagasy\";s:5:\"mg_MG\";s:21:\"Malagasy (Madagascar)\";s:2:\"ms\";s:5:\"Malay\";s:5:\"ms_BN\";s:14:\"Malay (Brunei)\";s:5:\"ms_ID\";s:17:\"Malay (Indonesia)\";s:5:\"ms_MY\";s:16:\"Malay (Malaysia)\";s:5:\"ms_SG\";s:17:\"Malay (Singapore)\";s:2:\"ml\";s:9:\"Malayalam\";s:5:\"ml_IN\";s:17:\"Malayalam (India)\";s:2:\"mt\";s:7:\"Maltese\";s:5:\"mt_MT\";s:15:\"Maltese (Malta)\";s:3:\"mni\";s:8:\"Manipuri\";s:8:\"mni_Beng\";s:8:\"Manipuri\";s:11:\"mni_Beng_IN\";s:16:\"Manipuri (India)\";s:2:\"gv\";s:4:\"Manx\";s:5:\"gv_IM\";s:18:\"Manx (Isle of Man)\";s:2:\"mr\";s:7:\"Marathi\";s:5:\"mr_IN\";s:15:\"Marathi (India)\";s:3:\"mas\";s:5:\"Masai\";s:6:\"mas_KE\";s:13:\"Masai (Kenya)\";s:6:\"mas_TZ\";s:16:\"Masai (Tanzania)\";s:3:\"mzn\";s:11:\"Mazanderani\";s:6:\"mzn_IR\";s:18:\"Mazanderani (Iran)\";s:3:\"mer\";s:4:\"Meru\";s:6:\"mer_KE\";s:12:\"Meru (Kenya)\";s:3:\"mgo\";s:6:\"MetaÊ¼\";s:6:\"mgo_CM\";s:17:\"MetaÊ¼ (Cameroon)\";s:2:\"mn\";s:9:\"Mongolian\";s:5:\"mn_MN\";s:20:\"Mongolian (Mongolia)\";s:3:\"mfe\";s:8:\"Morisyen\";s:6:\"mfe_MU\";s:20:\"Morisyen (Mauritius)\";s:3:\"mua\";s:7:\"Mundang\";s:6:\"mua_CM\";s:18:\"Mundang (Cameroon)\";s:2:\"mi\";s:6:\"MÄori\";s:5:\"mi_NZ\";s:20:\"MÄori (New Zealand)\";s:3:\"naq\";s:4:\"Nama\";s:6:\"naq_NA\";s:14:\"Nama (Namibia)\";s:2:\"ne\";s:6:\"Nepali\";s:5:\"ne_IN\";s:14:\"Nepali (India)\";s:5:\"ne_NP\";s:14:\"Nepali (Nepal)\";s:3:\"nnh\";s:9:\"Ngiemboon\";s:6:\"nnh_CM\";s:20:\"Ngiemboon (Cameroon)\";s:3:\"jgo\";s:6:\"Ngomba\";s:6:\"jgo_CM\";s:17:\"Ngomba (Cameroon)\";s:3:\"yrl\";s:9:\"Nheengatu\";s:6:\"yrl_BR\";s:18:\"Nheengatu (Brazil)\";s:6:\"yrl_CO\";s:20:\"Nheengatu (Colombia)\";s:6:\"yrl_VE\";s:21:\"Nheengatu (Venezuela)\";s:3:\"pcm\";s:15:\"Nigerian Pidgin\";s:6:\"pcm_NG\";s:25:\"Nigerian Pidgin (Nigeria)\";s:2:\"nd\";s:13:\"North Ndebele\";s:5:\"nd_ZW\";s:24:\"North Ndebele (Zimbabwe)\";s:3:\"lrc\";s:13:\"Northern Luri\";s:6:\"lrc_IR\";s:20:\"Northern Luri (Iran)\";s:6:\"lrc_IQ\";s:20:\"Northern Luri (Iraq)\";s:2:\"se\";s:13:\"Northern Sami\";s:5:\"se_FI\";s:23:\"Northern Sami (Finland)\";s:5:\"se_NO\";s:22:\"Northern Sami (Norway)\";s:5:\"se_SE\";s:22:\"Northern Sami (Sweden)\";s:2:\"no\";s:9:\"Norwegian\";s:2:\"nb\";s:17:\"Norwegian BokmÃ¥l\";s:5:\"nb_NO\";s:26:\"Norwegian BokmÃ¥l (Norway)\";s:5:\"nb_SJ\";s:40:\"Norwegian BokmÃ¥l (Svalbard & Jan Mayen)\";s:2:\"nn\";s:17:\"Norwegian Nynorsk\";s:5:\"nn_NO\";s:26:\"Norwegian Nynorsk (Norway)\";s:3:\"nus\";s:4:\"Nuer\";s:6:\"nus_SS\";s:18:\"Nuer (South Sudan)\";s:3:\"nyn\";s:8:\"Nyankole\";s:6:\"nyn_UG\";s:17:\"Nyankole (Uganda)\";s:2:\"or\";s:4:\"Odia\";s:5:\"or_IN\";s:12:\"Odia (India)\";s:2:\"om\";s:5:\"Oromo\";s:5:\"om_ET\";s:16:\"Oromo (Ethiopia)\";s:5:\"om_KE\";s:13:\"Oromo (Kenya)\";s:2:\"os\";s:7:\"Ossetic\";s:5:\"os_GE\";s:17:\"Ossetic (Georgia)\";s:5:\"os_RU\";s:16:\"Ossetic (Russia)\";s:2:\"ps\";s:6:\"Pashto\";s:5:\"ps_AF\";s:20:\"Pashto (Afghanistan)\";s:5:\"ps_PK\";s:17:\"Pashto (Pakistan)\";s:2:\"fa\";s:7:\"Persian\";s:5:\"fa_AF\";s:21:\"Persian (Afghanistan)\";s:5:\"fa_IR\";s:14:\"Persian (Iran)\";s:2:\"pl\";s:6:\"Polish\";s:5:\"pl_PL\";s:15:\"Polish (Poland)\";s:2:\"pt\";s:10:\"Portuguese\";s:5:\"pt_AO\";s:19:\"Portuguese (Angola)\";s:5:\"pt_BR\";s:19:\"Portuguese (Brazil)\";s:5:\"pt_CV\";s:23:\"Portuguese (Cape Verde)\";s:5:\"pt_GQ\";s:30:\"Portuguese (Equatorial Guinea)\";s:5:\"pt_GW\";s:26:\"Portuguese (Guinea-Bissau)\";s:5:\"pt_LU\";s:23:\"Portuguese (Luxembourg)\";s:5:\"pt_MO\";s:28:\"Portuguese (Macao SAR China)\";s:5:\"pt_MZ\";s:23:\"Portuguese (Mozambique)\";s:5:\"pt_PT\";s:21:\"Portuguese (Portugal)\";s:5:\"pt_CH\";s:24:\"Portuguese (Switzerland)\";s:5:\"pt_ST\";s:35:\"Portuguese (SÃ£o TomÃ© & PrÃ­ncipe)\";s:5:\"pt_TL\";s:24:\"Portuguese (Timor-Leste)\";s:2:\"pa\";s:7:\"Punjabi\";s:7:\"pa_Arab\";s:7:\"Punjabi\";s:7:\"pa_Guru\";s:7:\"Punjabi\";s:10:\"pa_Guru_IN\";s:15:\"Punjabi (India)\";s:10:\"pa_Arab_PK\";s:18:\"Punjabi (Pakistan)\";s:2:\"qu\";s:7:\"Quechua\";s:5:\"qu_BO\";s:17:\"Quechua (Bolivia)\";s:5:\"qu_EC\";s:17:\"Quechua (Ecuador)\";s:5:\"qu_PE\";s:14:\"Quechua (Peru)\";s:3:\"raj\";s:10:\"Rajasthani\";s:6:\"raj_IN\";s:18:\"Rajasthani (India)\";s:2:\"ro\";s:8:\"Romanian\";s:5:\"ro_MD\";s:18:\"Romanian (Moldova)\";s:5:\"ro_RO\";s:18:\"Romanian (Romania)\";s:2:\"rm\";s:7:\"Romansh\";s:5:\"rm_CH\";s:21:\"Romansh (Switzerland)\";s:3:\"rof\";s:5:\"Rombo\";s:6:\"rof_TZ\";s:16:\"Rombo (Tanzania)\";s:2:\"rn\";s:5:\"Rundi\";s:5:\"rn_BI\";s:15:\"Rundi (Burundi)\";s:2:\"ru\";s:7:\"Russian\";s:5:\"ru_BY\";s:17:\"Russian (Belarus)\";s:5:\"ru_KZ\";s:20:\"Russian (Kazakhstan)\";s:5:\"ru_KG\";s:20:\"Russian (Kyrgyzstan)\";s:5:\"ru_MD\";s:17:\"Russian (Moldova)\";s:5:\"ru_RU\";s:16:\"Russian (Russia)\";s:5:\"ru_UA\";s:17:\"Russian (Ukraine)\";s:3:\"rwk\";s:3:\"Rwa\";s:6:\"rwk_TZ\";s:14:\"Rwa (Tanzania)\";s:3:\"saq\";s:7:\"Samburu\";s:6:\"saq_KE\";s:15:\"Samburu (Kenya)\";s:2:\"sg\";s:5:\"Sango\";s:5:\"sg_CF\";s:32:\"Sango (Central African Republic)\";s:3:\"sbp\";s:5:\"Sangu\";s:6:\"sbp_TZ\";s:16:\"Sangu (Tanzania)\";s:2:\"sa\";s:8:\"Sanskrit\";s:5:\"sa_IN\";s:16:\"Sanskrit (India)\";s:3:\"sat\";s:7:\"Santali\";s:8:\"sat_Olck\";s:7:\"Santali\";s:11:\"sat_Olck_IN\";s:15:\"Santali (India)\";s:2:\"sc\";s:9:\"Sardinian\";s:5:\"sc_IT\";s:17:\"Sardinian (Italy)\";s:2:\"gd\";s:15:\"Scottish Gaelic\";s:5:\"gd_GB\";s:32:\"Scottish Gaelic (United Kingdom)\";s:3:\"seh\";s:4:\"Sena\";s:6:\"seh_MZ\";s:17:\"Sena (Mozambique)\";s:2:\"sr\";s:7:\"Serbian\";s:7:\"sr_Cyrl\";s:7:\"Serbian\";s:7:\"sr_Latn\";s:7:\"Serbian\";s:10:\"sr_Cyrl_BA\";s:30:\"Serbian (Bosnia & Herzegovina)\";s:10:\"sr_Latn_BA\";s:30:\"Serbian (Bosnia & Herzegovina)\";s:10:\"sr_Cyrl_XK\";s:16:\"Serbian (Kosovo)\";s:10:\"sr_Latn_XK\";s:16:\"Serbian (Kosovo)\";s:10:\"sr_Cyrl_ME\";s:20:\"Serbian (Montenegro)\";s:10:\"sr_Latn_ME\";s:20:\"Serbian (Montenegro)\";s:10:\"sr_Cyrl_RS\";s:16:\"Serbian (Serbia)\";s:10:\"sr_Latn_RS\";s:16:\"Serbian (Serbia)\";s:3:\"ksb\";s:8:\"Shambala\";s:6:\"ksb_TZ\";s:19:\"Shambala (Tanzania)\";s:2:\"sn\";s:5:\"Shona\";s:5:\"sn_ZW\";s:16:\"Shona (Zimbabwe)\";s:2:\"ii\";s:10:\"Sichuan Yi\";s:5:\"ii_CN\";s:18:\"Sichuan Yi (China)\";s:2:\"sd\";s:6:\"Sindhi\";s:7:\"sd_Arab\";s:6:\"Sindhi\";s:7:\"sd_Deva\";s:6:\"Sindhi\";s:10:\"sd_Deva_IN\";s:14:\"Sindhi (India)\";s:10:\"sd_Arab_PK\";s:17:\"Sindhi (Pakistan)\";s:2:\"si\";s:7:\"Sinhala\";s:5:\"si_LK\";s:19:\"Sinhala (Sri Lanka)\";s:2:\"sk\";s:6:\"Slovak\";s:5:\"sk_SK\";s:17:\"Slovak (Slovakia)\";s:2:\"sl\";s:9:\"Slovenian\";s:5:\"sl_SI\";s:20:\"Slovenian (Slovenia)\";s:3:\"xog\";s:4:\"Soga\";s:6:\"xog_UG\";s:13:\"Soga (Uganda)\";s:2:\"so\";s:6:\"Somali\";s:5:\"so_DJ\";s:17:\"Somali (Djibouti)\";s:5:\"so_ET\";s:17:\"Somali (Ethiopia)\";s:5:\"so_KE\";s:14:\"Somali (Kenya)\";s:5:\"so_SO\";s:16:\"Somali (Somalia)\";s:2:\"es\";s:7:\"Spanish\";s:5:\"es_AR\";s:19:\"Spanish (Argentina)\";s:5:\"es_BZ\";s:16:\"Spanish (Belize)\";s:5:\"es_BO\";s:17:\"Spanish (Bolivia)\";s:5:\"es_BR\";s:16:\"Spanish (Brazil)\";s:5:\"es_IC\";s:24:\"Spanish (Canary Islands)\";s:5:\"es_EA\";s:25:\"Spanish (Ceuta & Melilla)\";s:5:\"es_CL\";s:15:\"Spanish (Chile)\";s:5:\"es_CO\";s:18:\"Spanish (Colombia)\";s:5:\"es_CR\";s:20:\"Spanish (Costa Rica)\";s:5:\"es_CU\";s:14:\"Spanish (Cuba)\";s:5:\"es_DO\";s:28:\"Spanish (Dominican Republic)\";s:5:\"es_EC\";s:17:\"Spanish (Ecuador)\";s:5:\"es_SV\";s:21:\"Spanish (El Salvador)\";s:5:\"es_GQ\";s:27:\"Spanish (Equatorial Guinea)\";s:5:\"es_GT\";s:19:\"Spanish (Guatemala)\";s:5:\"es_HN\";s:18:\"Spanish (Honduras)\";s:6:\"es_419\";s:23:\"Spanish (Latin America)\";s:5:\"es_MX\";s:16:\"Spanish (Mexico)\";s:5:\"es_NI\";s:19:\"Spanish (Nicaragua)\";s:5:\"es_PA\";s:16:\"Spanish (Panama)\";s:5:\"es_PY\";s:18:\"Spanish (Paraguay)\";s:5:\"es_PE\";s:14:\"Spanish (Peru)\";s:5:\"es_PH\";s:21:\"Spanish (Philippines)\";s:5:\"es_PR\";s:21:\"Spanish (Puerto Rico)\";s:5:\"es_ES\";s:15:\"Spanish (Spain)\";s:5:\"es_US\";s:23:\"Spanish (United States)\";s:5:\"es_UY\";s:17:\"Spanish (Uruguay)\";s:5:\"es_VE\";s:19:\"Spanish (Venezuela)\";s:3:\"zgh\";s:27:\"Standard Moroccan Tamazight\";s:6:\"zgh_MA\";s:37:\"Standard Moroccan Tamazight (Morocco)\";s:2:\"su\";s:9:\"Sundanese\";s:7:\"su_Latn\";s:9:\"Sundanese\";s:10:\"su_Latn_ID\";s:21:\"Sundanese (Indonesia)\";s:2:\"sw\";s:7:\"Swahili\";s:5:\"sw_CD\";s:26:\"Swahili (Congo - Kinshasa)\";s:5:\"sw_KE\";s:15:\"Swahili (Kenya)\";s:5:\"sw_TZ\";s:18:\"Swahili (Tanzania)\";s:5:\"sw_UG\";s:16:\"Swahili (Uganda)\";s:2:\"sv\";s:7:\"Swedish\";s:5:\"sv_FI\";s:17:\"Swedish (Finland)\";s:5:\"sv_SE\";s:16:\"Swedish (Sweden)\";s:5:\"sv_AX\";s:24:\"Swedish (Ã…land Islands)\";s:3:\"gsw\";s:12:\"Swiss German\";s:6:\"gsw_FR\";s:21:\"Swiss German (France)\";s:6:\"gsw_LI\";s:28:\"Swiss German (Liechtenstein)\";s:6:\"gsw_CH\";s:26:\"Swiss German (Switzerland)\";s:3:\"shi\";s:9:\"Tachelhit\";s:8:\"shi_Latn\";s:9:\"Tachelhit\";s:8:\"shi_Tfng\";s:9:\"Tachelhit\";s:11:\"shi_Latn_MA\";s:19:\"Tachelhit (Morocco)\";s:11:\"shi_Tfng_MA\";s:19:\"Tachelhit (Morocco)\";s:3:\"dav\";s:5:\"Taita\";s:6:\"dav_KE\";s:13:\"Taita (Kenya)\";s:2:\"tg\";s:5:\"Tajik\";s:5:\"tg_TJ\";s:18:\"Tajik (Tajikistan)\";s:2:\"ta\";s:5:\"Tamil\";s:5:\"ta_IN\";s:13:\"Tamil (India)\";s:5:\"ta_MY\";s:16:\"Tamil (Malaysia)\";s:5:\"ta_SG\";s:17:\"Tamil (Singapore)\";s:5:\"ta_LK\";s:17:\"Tamil (Sri Lanka)\";s:3:\"twq\";s:7:\"Tasawaq\";s:6:\"twq_NE\";s:15:\"Tasawaq (Niger)\";s:2:\"tt\";s:5:\"Tatar\";s:5:\"tt_RU\";s:14:\"Tatar (Russia)\";s:2:\"te\";s:6:\"Telugu\";s:5:\"te_IN\";s:14:\"Telugu (India)\";s:3:\"teo\";s:4:\"Teso\";s:6:\"teo_KE\";s:12:\"Teso (Kenya)\";s:6:\"teo_UG\";s:13:\"Teso (Uganda)\";s:2:\"th\";s:4:\"Thai\";s:5:\"th_TH\";s:15:\"Thai (Thailand)\";s:2:\"bo\";s:7:\"Tibetan\";s:5:\"bo_CN\";s:15:\"Tibetan (China)\";s:5:\"bo_IN\";s:15:\"Tibetan (India)\";s:2:\"ti\";s:8:\"Tigrinya\";s:5:\"ti_ER\";s:18:\"Tigrinya (Eritrea)\";s:5:\"ti_ET\";s:19:\"Tigrinya (Ethiopia)\";s:2:\"to\";s:6:\"Tongan\";s:5:\"to_TO\";s:14:\"Tongan (Tonga)\";s:2:\"tr\";s:7:\"Turkish\";s:5:\"tr_CY\";s:16:\"Turkish (Cyprus)\";s:5:\"tr_TR\";s:16:\"Turkish (Turkey)\";s:2:\"tk\";s:7:\"Turkmen\";s:5:\"tk_TM\";s:22:\"Turkmen (Turkmenistan)\";s:2:\"uk\";s:9:\"Ukrainian\";s:5:\"uk_UA\";s:19:\"Ukrainian (Ukraine)\";s:3:\"hsb\";s:13:\"Upper Sorbian\";s:6:\"hsb_DE\";s:23:\"Upper Sorbian (Germany)\";s:2:\"ur\";s:4:\"Urdu\";s:5:\"ur_IN\";s:12:\"Urdu (India)\";s:5:\"ur_PK\";s:15:\"Urdu (Pakistan)\";s:2:\"ug\";s:6:\"Uyghur\";s:5:\"ug_CN\";s:14:\"Uyghur (China)\";s:2:\"uz\";s:5:\"Uzbek\";s:7:\"uz_Arab\";s:5:\"Uzbek\";s:7:\"uz_Cyrl\";s:5:\"Uzbek\";s:7:\"uz_Latn\";s:5:\"Uzbek\";s:10:\"uz_Arab_AF\";s:19:\"Uzbek (Afghanistan)\";s:10:\"uz_Cyrl_UZ\";s:18:\"Uzbek (Uzbekistan)\";s:10:\"uz_Latn_UZ\";s:18:\"Uzbek (Uzbekistan)\";s:3:\"vai\";s:3:\"Vai\";s:8:\"vai_Latn\";s:3:\"Vai\";s:8:\"vai_Vaii\";s:3:\"Vai\";s:11:\"vai_Latn_LR\";s:13:\"Vai (Liberia)\";s:11:\"vai_Vaii_LR\";s:13:\"Vai (Liberia)\";s:2:\"vi\";s:10:\"Vietnamese\";s:5:\"vi_VN\";s:20:\"Vietnamese (Vietnam)\";s:3:\"vun\";s:5:\"Vunjo\";s:6:\"vun_TZ\";s:16:\"Vunjo (Tanzania)\";s:3:\"wae\";s:6:\"Walser\";s:6:\"wae_CH\";s:20:\"Walser (Switzerland)\";s:2:\"cy\";s:5:\"Welsh\";s:5:\"cy_GB\";s:22:\"Welsh (United Kingdom)\";s:2:\"fy\";s:15:\"Western Frisian\";s:5:\"fy_NL\";s:29:\"Western Frisian (Netherlands)\";s:2:\"wo\";s:5:\"Wolof\";s:5:\"wo_SN\";s:15:\"Wolof (Senegal)\";s:2:\"xh\";s:5:\"Xhosa\";s:5:\"xh_ZA\";s:20:\"Xhosa (South Africa)\";s:3:\"sah\";s:5:\"Yakut\";s:6:\"sah_RU\";s:14:\"Yakut (Russia)\";s:3:\"yav\";s:7:\"Yangben\";s:6:\"yav_CM\";s:18:\"Yangben (Cameroon)\";s:2:\"yi\";s:7:\"Yiddish\";s:6:\"yi_001\";s:15:\"Yiddish (world)\";s:2:\"yo\";s:6:\"Yoruba\";s:5:\"yo_BJ\";s:14:\"Yoruba (Benin)\";s:5:\"yo_NG\";s:16:\"Yoruba (Nigeria)\";s:3:\"dje\";s:5:\"Zarma\";s:6:\"dje_NE\";s:13:\"Zarma (Niger)\";s:2:\"zu\";s:4:\"Zulu\";s:5:\"zu_ZA\";s:19:\"Zulu (South Africa)\";}s:6:\"system\";s:6:\"ø&YX7\";}',31536000,1719653858),(_binary 'translatetags',_binary 's:6:\"9Fûv§\";',NULL,1719653840),(_binary 'translation_data_2e364d315ad5ad984609e7f4beedf0f7',_binary 'O:1:\"©\":4:{s:6:\"ÿÿÿÀ\";O:46:\"Symfony\\Component\\Translation\\MessageCatalogue\":7:{s:56:\"\0Symfony\\Component\\Translation\\MessageCatalogue\0messages\";a:2:{s:5:\"admin\";a:48:{s:15:\"__pimcore_dummy\";s:12:\"only_a_dummy\";s:4:\"Base\";s:0:\"\";s:6:\"Bottom\";s:0:\"\";s:4:\"Code\";s:0:\"\";s:5:\"Codes\";s:0:\"\";s:9:\"Companies\";s:0:\"\";s:7:\"Company\";s:7:\"Company\";s:12:\"Company Name\";s:0:\"\";s:8:\"Diameter\";s:0:\"\";s:20:\"Diameter With Thread\";s:0:\"\";s:23:\"Diameter Without Thread\";s:0:\"\";s:10:\"Dimensions\";s:0:\"\";s:7:\"English\";s:0:\"\";s:6:\"French\";s:0:\"\";s:6:\"German\";s:0:\"\";s:6:\"Images\";s:0:\"\";s:6:\"Inside\";s:0:\"\";s:6:\"Length\";s:0:\"\";s:4:\"Main\";s:0:\"\";s:17:\"Main (Admin Mode)\";s:0:\"\";s:7:\"Outside\";s:0:\"\";s:5:\"Paper\";s:5:\"Paper\";s:14:\"PaperCartridge\";s:0:\"\";s:18:\"Pimcore\'s logotype\";s:0:\"\";s:5:\"Pitch\";s:0:\"\";s:18:\"Priemer bez zavitu\";s:0:\"\";s:18:\"Priemer so zavitom\";s:0:\"\";s:5:\"Royal\";s:5:\"Royal\";s:11:\"RoyalFilter\";s:0:\"\";s:15:\"Stupanie zavitu\";s:0:\"\";s:6:\"Thread\";s:0:\"\";s:15:\"Thread Position\";s:0:\"\";s:13:\"Thread images\";s:0:\"\";s:3:\"Top\";s:0:\"\";s:6:\"bottom\";s:0:\"\";s:4:\"down\";s:0:\"\";s:6:\"global\";s:0:\"\";s:10:\"ignoreCase\";s:0:\"\";s:5:\"login\";s:0:\"\";s:9:\"multiline\";s:0:\"\";s:37:\"object_add_dialog_custom_text.Company\";s:0:\"\";s:44:\"object_add_dialog_custom_text.PaperCartridge\";s:0:\"\";s:38:\"object_add_dialog_custom_title.Company\";s:0:\"\";s:45:\"object_add_dialog_custom_title.PaperCartridge\";s:0:\"\";s:19:\"paperCartridgeCodes\";s:0:\"\";s:6:\"sticky\";s:0:\"\";s:7:\"unicode\";s:0:\"\";s:2:\"up\";s:0:\"\";}s:14:\"admin+intl-icu\";a:1:{s:15:\"__pimcore_dummy\";s:12:\"only_a_dummy\";}}s:56:\"\0Symfony\\Component\\Translation\\MessageCatalogue\0metadata\";a:0:{}s:65:\"\0Symfony\\Component\\Translation\\MessageCatalogue\0catalogueMetadata\";a:0:{}s:57:\"\0Symfony\\Component\\Translation\\MessageCatalogue\0resources\";a:0:{}s:54:\"\0Symfony\\Component\\Translation\\MessageCatalogue\0locale\";s:2:\"en\";s:65:\"\0Symfony\\Component\\Translation\\MessageCatalogue\0fallbackCatalogue\";N;s:54:\"\0Symfony\\Component\\Translation\\MessageCatalogue\0parent\";N;}s:10:\"translator\";s:6:\"9Fûv§\";s:18:\"translator_website\";s:6:\"¸¤\õge\õ\";s:9:\"translate\";s:6:\"9Fûv§\";}',31536000,1719653840),(_binary 'translatortags',_binary 's:6:\"9Fûv§\";',NULL,1719653840),(_binary 'translator_websitetags',_binary 's:6:\"¸¤\õge\õ\";',NULL,1719596607);
/*!40000 ALTER TABLE `cache_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classes` (
  `id` varchar(50) NOT NULL,
  `name` varchar(190) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES ('company','Company'),('paper_cartridge','PaperCartridge'),('royal_filter','RoyalFilter');
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classificationstore_collectionrelations`
--

DROP TABLE IF EXISTS `classificationstore_collectionrelations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classificationstore_collectionrelations` (
  `colId` int unsigned NOT NULL,
  `groupId` int unsigned NOT NULL,
  `sorter` int DEFAULT '0',
  PRIMARY KEY (`colId`,`groupId`),
  KEY `FK_classificationstore_collectionrelations_groups` (`groupId`),
  CONSTRAINT `FK_classificationstore_collectionrelations_groups` FOREIGN KEY (`groupId`) REFERENCES `classificationstore_groups` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classificationstore_collectionrelations`
--

LOCK TABLES `classificationstore_collectionrelations` WRITE;
/*!40000 ALTER TABLE `classificationstore_collectionrelations` DISABLE KEYS */;
/*!40000 ALTER TABLE `classificationstore_collectionrelations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classificationstore_collections`
--

DROP TABLE IF EXISTS `classificationstore_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classificationstore_collections` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `storeId` int DEFAULT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `creationDate` int unsigned DEFAULT '0',
  `modificationDate` int unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `storeId` (`storeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classificationstore_collections`
--

LOCK TABLES `classificationstore_collections` WRITE;
/*!40000 ALTER TABLE `classificationstore_collections` DISABLE KEYS */;
/*!40000 ALTER TABLE `classificationstore_collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classificationstore_groups`
--

DROP TABLE IF EXISTS `classificationstore_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classificationstore_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `storeId` int DEFAULT NULL,
  `parentId` int unsigned NOT NULL DEFAULT '0',
  `name` varchar(190) NOT NULL DEFAULT '',
  `description` varchar(255) DEFAULT NULL,
  `creationDate` int unsigned DEFAULT '0',
  `modificationDate` int unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `storeId` (`storeId`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classificationstore_groups`
--

LOCK TABLES `classificationstore_groups` WRITE;
/*!40000 ALTER TABLE `classificationstore_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `classificationstore_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classificationstore_keys`
--

DROP TABLE IF EXISTS `classificationstore_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classificationstore_keys` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `storeId` int DEFAULT NULL,
  `name` varchar(190) NOT NULL DEFAULT '',
  `title` varchar(255) NOT NULL DEFAULT '',
  `description` text,
  `type` varchar(190) DEFAULT NULL,
  `creationDate` int unsigned DEFAULT '0',
  `modificationDate` int unsigned DEFAULT '0',
  `definition` json DEFAULT NULL,
  `enabled` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `enabled` (`enabled`),
  KEY `type` (`type`),
  KEY `storeId` (`storeId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classificationstore_keys`
--

LOCK TABLES `classificationstore_keys` WRITE;
/*!40000 ALTER TABLE `classificationstore_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `classificationstore_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classificationstore_relations`
--

DROP TABLE IF EXISTS `classificationstore_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classificationstore_relations` (
  `groupId` int unsigned NOT NULL,
  `keyId` int unsigned NOT NULL,
  `sorter` int DEFAULT NULL,
  `mandatory` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`groupId`,`keyId`),
  KEY `FK_classificationstore_relations_classificationstore_keys` (`keyId`),
  KEY `mandatory` (`mandatory`),
  CONSTRAINT `FK_classificationstore_relations_classificationstore_groups` FOREIGN KEY (`groupId`) REFERENCES `classificationstore_groups` (`id`) ON DELETE CASCADE,
  CONSTRAINT `FK_classificationstore_relations_classificationstore_keys` FOREIGN KEY (`keyId`) REFERENCES `classificationstore_keys` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classificationstore_relations`
--

LOCK TABLES `classificationstore_relations` WRITE;
/*!40000 ALTER TABLE `classificationstore_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `classificationstore_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classificationstore_stores`
--

DROP TABLE IF EXISTS `classificationstore_stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `classificationstore_stores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(190) DEFAULT NULL,
  `description` longtext,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classificationstore_stores`
--

LOCK TABLES `classificationstore_stores` WRITE;
/*!40000 ALTER TABLE `classificationstore_stores` DISABLE KEYS */;
/*!40000 ALTER TABLE `classificationstore_stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dependencies`
--

DROP TABLE IF EXISTS `dependencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dependencies` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `sourcetype` enum('document','asset','object') NOT NULL DEFAULT 'document',
  `sourceid` int unsigned NOT NULL DEFAULT '0',
  `targettype` enum('document','asset','object') NOT NULL DEFAULT 'document',
  `targetid` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `combi` (`sourcetype`,`sourceid`,`targettype`,`targetid`),
  KEY `targettype_targetid` (`targettype`,`targetid`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dependencies`
--

LOCK TABLES `dependencies` WRITE;
/*!40000 ALTER TABLE `dependencies` DISABLE KEYS */;
INSERT INTO `dependencies` VALUES (1,'object',3,'object',5),(2,'object',3,'object',6);
/*!40000 ALTER TABLE `dependencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parentId` int unsigned DEFAULT NULL,
  `type` enum('page','link','snippet','folder','hardlink','email') DEFAULT NULL,
  `key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT '',
  `path` varchar(765) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `index` int unsigned DEFAULT '0',
  `published` tinyint unsigned DEFAULT '1',
  `creationDate` int unsigned DEFAULT NULL,
  `modificationDate` int unsigned DEFAULT NULL,
  `userOwner` int unsigned DEFAULT NULL,
  `userModification` int unsigned DEFAULT NULL,
  `versionCount` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fullpath` (`path`,`key`),
  KEY `parentId` (`parentId`),
  KEY `key` (`key`),
  KEY `published` (`published`),
  KEY `modificationDate` (`modificationDate`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents`
--

LOCK TABLES `documents` WRITE;
/*!40000 ALTER TABLE `documents` DISABLE KEYS */;
INSERT INTO `documents` VALUES (1,0,'page','','/',999999,1,1719596425,1719596425,1,1,0);
/*!40000 ALTER TABLE `documents` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents_editables`
--

DROP TABLE IF EXISTS `documents_editables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents_editables` (
  `documentId` int unsigned NOT NULL DEFAULT '0',
  `name` varchar(750) CHARACTER SET ascii COLLATE ascii_general_ci NOT NULL DEFAULT '',
  `type` varchar(50) DEFAULT NULL,
  `data` longtext,
  PRIMARY KEY (`documentId`,`name`),
  CONSTRAINT `fk_documents_editables_documents` FOREIGN KEY (`documentId`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents_editables`
--

LOCK TABLES `documents_editables` WRITE;
/*!40000 ALTER TABLE `documents_editables` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents_editables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents_email`
--

DROP TABLE IF EXISTS `documents_email`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents_email` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `controller` varchar(500) DEFAULT NULL,
  `template` varchar(255) DEFAULT NULL,
  `to` varchar(255) DEFAULT NULL,
  `from` varchar(255) DEFAULT NULL,
  `replyTo` varchar(255) DEFAULT NULL,
  `cc` varchar(255) DEFAULT NULL,
  `bcc` varchar(255) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `missingRequiredEditable` tinyint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_documents_email_documents` FOREIGN KEY (`id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents_email`
--

LOCK TABLES `documents_email` WRITE;
/*!40000 ALTER TABLE `documents_email` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents_email` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents_hardlink`
--

DROP TABLE IF EXISTS `documents_hardlink`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents_hardlink` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `sourceId` int DEFAULT NULL,
  `propertiesFromSource` tinyint(1) DEFAULT NULL,
  `childrenFromSource` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `sourceId` (`sourceId`),
  CONSTRAINT `fk_documents_hardlink_documents` FOREIGN KEY (`id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents_hardlink`
--

LOCK TABLES `documents_hardlink` WRITE;
/*!40000 ALTER TABLE `documents_hardlink` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents_hardlink` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents_link`
--

DROP TABLE IF EXISTS `documents_link`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents_link` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `internalType` enum('document','asset','object') DEFAULT NULL,
  `internal` int unsigned DEFAULT NULL,
  `direct` varchar(1000) DEFAULT NULL,
  `linktype` enum('direct','internal') DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_documents_link_documents` FOREIGN KEY (`id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents_link`
--

LOCK TABLES `documents_link` WRITE;
/*!40000 ALTER TABLE `documents_link` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents_link` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents_page`
--

DROP TABLE IF EXISTS `documents_page`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents_page` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `controller` varchar(500) DEFAULT NULL,
  `template` varchar(255) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` varchar(383) DEFAULT NULL,
  `prettyUrl` varchar(255) DEFAULT NULL,
  `contentMainDocumentId` int DEFAULT NULL,
  `targetGroupIds` varchar(255) NOT NULL DEFAULT '',
  `missingRequiredEditable` tinyint unsigned DEFAULT NULL,
  `staticGeneratorEnabled` tinyint unsigned DEFAULT NULL,
  `staticGeneratorLifetime` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `prettyUrl` (`prettyUrl`),
  CONSTRAINT `fk_documents_page_documents` FOREIGN KEY (`id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents_page`
--

LOCK TABLES `documents_page` WRITE;
/*!40000 ALTER TABLE `documents_page` DISABLE KEYS */;
INSERT INTO `documents_page` VALUES (1,'App\\Controller\\DefaultController::defaultAction','','','',NULL,NULL,'',NULL,NULL,NULL);
/*!40000 ALTER TABLE `documents_page` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents_snippet`
--

DROP TABLE IF EXISTS `documents_snippet`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents_snippet` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `controller` varchar(500) DEFAULT NULL,
  `template` varchar(255) DEFAULT NULL,
  `contentMainDocumentId` int DEFAULT NULL,
  `missingRequiredEditable` tinyint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_documents_snippet_documents` FOREIGN KEY (`id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents_snippet`
--

LOCK TABLES `documents_snippet` WRITE;
/*!40000 ALTER TABLE `documents_snippet` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents_snippet` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `documents_translations`
--

DROP TABLE IF EXISTS `documents_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `documents_translations` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `sourceId` int unsigned NOT NULL DEFAULT '0',
  `language` varchar(10) NOT NULL DEFAULT '',
  PRIMARY KEY (`sourceId`,`language`),
  KEY `id` (`id`),
  KEY `language` (`language`),
  CONSTRAINT `fk_documents_translations_documents` FOREIGN KEY (`id`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `documents_translations`
--

LOCK TABLES `documents_translations` WRITE;
/*!40000 ALTER TABLE `documents_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `documents_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `edit_lock`
--

DROP TABLE IF EXISTS `edit_lock`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `edit_lock` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cid` int unsigned NOT NULL DEFAULT '0',
  `ctype` enum('document','asset','object') DEFAULT NULL,
  `userId` int unsigned NOT NULL DEFAULT '0',
  `sessionId` varchar(255) DEFAULT NULL,
  `date` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ctype` (`ctype`),
  KEY `cidtype` (`cid`,`ctype`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `edit_lock`
--

LOCK TABLES `edit_lock` WRITE;
/*!40000 ALTER TABLE `edit_lock` DISABLE KEYS */;
INSERT INTO `edit_lock` VALUES (12,3,'object',2,'01bbca4be15f149b0be9700411a855df',1719599436);
/*!40000 ALTER TABLE `edit_lock` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `element_workflow_state`
--

DROP TABLE IF EXISTS `element_workflow_state`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `element_workflow_state` (
  `cid` int NOT NULL DEFAULT '0',
  `ctype` enum('document','asset','object') NOT NULL,
  `place` text,
  `workflow` varchar(100) NOT NULL,
  PRIMARY KEY (`cid`,`ctype`,`workflow`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `element_workflow_state`
--

LOCK TABLES `element_workflow_state` WRITE;
/*!40000 ALTER TABLE `element_workflow_state` DISABLE KEYS */;
/*!40000 ALTER TABLE `element_workflow_state` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_blocklist`
--

DROP TABLE IF EXISTS `email_blocklist`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_blocklist` (
  `address` varchar(190) NOT NULL DEFAULT '',
  `creationDate` int unsigned DEFAULT NULL,
  `modificationDate` int unsigned DEFAULT NULL,
  PRIMARY KEY (`address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_blocklist`
--

LOCK TABLES `email_blocklist` WRITE;
/*!40000 ALTER TABLE `email_blocklist` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_blocklist` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `email_log`
--

DROP TABLE IF EXISTS `email_log`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `email_log` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `documentId` int unsigned DEFAULT NULL,
  `requestUri` varchar(500) DEFAULT NULL,
  `params` text,
  `from` varchar(500) DEFAULT NULL,
  `replyTo` varchar(255) DEFAULT NULL,
  `to` longtext,
  `cc` longtext,
  `bcc` longtext,
  `sentDate` int unsigned DEFAULT NULL,
  `subject` varchar(500) DEFAULT NULL,
  `error` text,
  PRIMARY KEY (`id`),
  KEY `sentDate` (`sentDate`,`id`),
  KEY `document_id` (`documentId`),
  FULLTEXT KEY `fulltext` (`from`,`to`,`cc`,`bcc`,`subject`,`params`),
  CONSTRAINT `fk_email_log_documents` FOREIGN KEY (`documentId`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `email_log`
--

LOCK TABLES `email_log` WRITE;
/*!40000 ALTER TABLE `email_log` DISABLE KEYS */;
/*!40000 ALTER TABLE `email_log` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gridconfig_favourites`
--

DROP TABLE IF EXISTS `gridconfig_favourites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gridconfig_favourites` (
  `ownerId` int NOT NULL,
  `classId` varchar(50) NOT NULL,
  `objectId` int NOT NULL DEFAULT '0',
  `gridConfigId` int NOT NULL,
  `searchType` varchar(50) NOT NULL DEFAULT '',
  `type` enum('asset','object') NOT NULL DEFAULT 'object',
  PRIMARY KEY (`ownerId`,`classId`,`searchType`,`objectId`),
  KEY `classId` (`classId`),
  KEY `searchType` (`searchType`),
  KEY `grid_config_id` (`gridConfigId`),
  CONSTRAINT `fk_gridconfig_favourites_gridconfigs` FOREIGN KEY (`gridConfigId`) REFERENCES `gridconfigs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gridconfig_favourites`
--

LOCK TABLES `gridconfig_favourites` WRITE;
/*!40000 ALTER TABLE `gridconfig_favourites` DISABLE KEYS */;
/*!40000 ALTER TABLE `gridconfig_favourites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gridconfig_shares`
--

DROP TABLE IF EXISTS `gridconfig_shares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gridconfig_shares` (
  `gridConfigId` int NOT NULL,
  `sharedWithUserId` int NOT NULL,
  PRIMARY KEY (`gridConfigId`,`sharedWithUserId`),
  KEY `sharedWithUserId` (`sharedWithUserId`),
  KEY `grid_config_id` (`gridConfigId`),
  CONSTRAINT `fk_gridconfig_shares_gridconfigs` FOREIGN KEY (`gridConfigId`) REFERENCES `gridconfigs` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gridconfig_shares`
--

LOCK TABLES `gridconfig_shares` WRITE;
/*!40000 ALTER TABLE `gridconfig_shares` DISABLE KEYS */;
/*!40000 ALTER TABLE `gridconfig_shares` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `gridconfigs`
--

DROP TABLE IF EXISTS `gridconfigs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `gridconfigs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ownerId` int DEFAULT NULL,
  `classId` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `searchType` varchar(50) DEFAULT NULL,
  `type` enum('asset','object') NOT NULL DEFAULT 'object',
  `config` json DEFAULT NULL,
  `description` longtext,
  `creationDate` int DEFAULT NULL,
  `modificationDate` int DEFAULT NULL,
  `shareGlobally` tinyint(1) DEFAULT NULL,
  `setAsFavourite` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ownerId` (`ownerId`),
  KEY `classId` (`classId`),
  KEY `searchType` (`searchType`),
  KEY `shareGlobally` (`shareGlobally`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `gridconfigs`
--

LOCK TABLES `gridconfigs` WRITE;
/*!40000 ALTER TABLE `gridconfigs` DISABLE KEYS */;
/*!40000 ALTER TABLE `gridconfigs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `importconfig_shares`
--

DROP TABLE IF EXISTS `importconfig_shares`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `importconfig_shares` (
  `importConfigId` int NOT NULL,
  `sharedWithUserId` int NOT NULL,
  PRIMARY KEY (`importConfigId`,`sharedWithUserId`),
  KEY `sharedWithUserId` (`sharedWithUserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `importconfig_shares`
--

LOCK TABLES `importconfig_shares` WRITE;
/*!40000 ALTER TABLE `importconfig_shares` DISABLE KEYS */;
/*!40000 ALTER TABLE `importconfig_shares` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `importconfigs`
--

DROP TABLE IF EXISTS `importconfigs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `importconfigs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ownerId` int DEFAULT NULL,
  `classId` varchar(50) DEFAULT NULL,
  `name` varchar(50) DEFAULT NULL,
  `config` json DEFAULT NULL,
  `description` longtext,
  `creationDate` int DEFAULT NULL,
  `modificationDate` int DEFAULT NULL,
  `shareGlobally` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ownerId` (`ownerId`),
  KEY `classId` (`classId`),
  KEY `shareGlobally` (`shareGlobally`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `importconfigs`
--

LOCK TABLES `importconfigs` WRITE;
/*!40000 ALTER TABLE `importconfigs` DISABLE KEYS */;
/*!40000 ALTER TABLE `importconfigs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lock_keys`
--

DROP TABLE IF EXISTS `lock_keys`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `lock_keys` (
  `key_id` varchar(64) NOT NULL,
  `key_token` varchar(44) NOT NULL,
  `key_expiration` int unsigned NOT NULL,
  PRIMARY KEY (`key_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lock_keys`
--

LOCK TABLES `lock_keys` WRITE;
/*!40000 ALTER TABLE `lock_keys` DISABLE KEYS */;
/*!40000 ALTER TABLE `lock_keys` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messenger_messages`
--

DROP TABLE IF EXISTS `messenger_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `messenger_messages` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `body` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb3_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb3_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `available_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `delivered_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  PRIMARY KEY (`id`),
  KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  KEY `IDX_75EA56E016BA31DB` (`delivered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messenger_messages`
--

LOCK TABLES `messenger_messages` WRITE;
/*!40000 ALTER TABLE `messenger_messages` DISABLE KEYS */;
INSERT INTO `messenger_messages` VALUES (1,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:2;}}','[]','pimcore_search_backend_message','2024-06-28 17:51:41','2024-06-28 17:51:41',NULL),(2,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:2;}}','[]','pimcore_search_backend_message','2024-06-28 17:51:41','2024-06-28 17:51:41',NULL),(3,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:2;}}','[]','pimcore_search_backend_message','2024-06-28 17:51:51','2024-06-28 17:51:51',NULL),(4,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:3;}}','[]','pimcore_search_backend_message','2024-06-28 17:52:17','2024-06-28 17:52:17',NULL),(5,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:3;}}','[]','pimcore_search_backend_message','2024-06-28 17:52:40','2024-06-28 17:52:40',NULL),(6,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:3;}}','[]','pimcore_search_backend_message','2024-06-28 17:52:43','2024-06-28 17:52:43',NULL),(7,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:3;}}','[]','pimcore_search_backend_message','2024-06-28 18:19:31','2024-06-28 18:19:31',NULL),(8,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:3;}}','[]','pimcore_search_backend_message','2024-06-28 18:20:52','2024-06-28 18:20:52',NULL),(9,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:4;}}','[]','pimcore_search_backend_message','2024-06-28 18:23:17','2024-06-28 18:23:17',NULL),(10,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:4;}}','[]','pimcore_search_backend_message','2024-06-28 18:23:17','2024-06-28 18:23:17',NULL),(11,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:5;}}','[]','pimcore_search_backend_message','2024-06-28 18:23:25','2024-06-28 18:23:25',NULL),(12,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:5;}}','[]','pimcore_search_backend_message','2024-06-28 18:23:27','2024-06-28 18:23:27',NULL),(13,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:5;}}','[]','pimcore_search_backend_message','2024-06-28 18:23:29','2024-06-28 18:23:29',NULL),(14,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:3;}}','[]','pimcore_search_backend_message','2024-06-28 18:23:34','2024-06-28 18:23:34',NULL),(15,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:6;}}','[]','pimcore_search_backend_message','2024-06-28 18:24:03','2024-06-28 18:24:03',NULL),(16,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:6;}}','[]','pimcore_search_backend_message','2024-06-28 18:24:07','2024-06-28 18:24:07',NULL),(17,'O:36:\\\"Symfony\\\\Component\\\\Messenger\\\\Envelope\\\":2:{s:44:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0stamps\\\";a:1:{s:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\";a:1:{i:0;O:46:\\\"Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\\":1:{s:55:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Stamp\\\\BusNameStamp\\0busName\\\";s:26:\\\"messenger.bus.pimcore-core\\\";}}}s:45:\\\"\\0Symfony\\\\Component\\\\Messenger\\\\Envelope\\0message\\\";O:69:\\\"Pimcore\\\\Bundle\\\\SimpleBackendSearchBundle\\\\Message\\\\SearchBackendMessage\\\":2:{s:7:\\\"\\0*\\0type\\\";s:6:\\\"object\\\";s:5:\\\"\\0*\\0id\\\";i:3;}}','[]','pimcore_search_backend_message','2024-06-28 18:27:00','2024-06-28 18:27:00',NULL);
/*!40000 ALTER TABLE `messenger_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migration_versions`
--

DROP TABLE IF EXISTS `migration_versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migration_versions` (
  `version` varchar(1024) COLLATE utf8mb3_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migration_versions`
--

LOCK TABLES `migration_versions` WRITE;
/*!40000 ALTER TABLE `migration_versions` DISABLE KEYS */;
INSERT INTO `migration_versions` VALUES ('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20201007000000',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20201008082752',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20201008091131',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20201008101817',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20201008132324',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20201009095924',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20201012154224',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20201014101437',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20201113143914',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20201201084201',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210107103923',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210218142556',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210323082921',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210323110055',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210324152821',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210324152822',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210330132354',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210408153226',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210412112812',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210428145320',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210505093841',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210531125102',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210608094532',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210616114744',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210624085031',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210630062445',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210702102100',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210706090823',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210901130000',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20210928135248',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20211016084043',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20211018104331',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20211028134037',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20211028155535',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20211103055110',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20211117173000',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20211209131141',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20211221152344',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220119082511',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220120121803',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220120162621',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220201132131',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220214110000',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220317125711',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220318101020',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220402104849',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220411172543',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220419120333',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220425082914',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220502104200',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220506103100',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220511085800',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220614115124',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220617145524',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220718162200',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220725154615',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220809154926',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220809164000',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220816120101',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220829132224',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220830105212',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220906111031',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20220908113752',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221003115124',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221005090000',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221019042134',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221020195451',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221025165133',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221028115803',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221107084519',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221116115427',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221129084031',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221215071650',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221216140012',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221220152444',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221222134837',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221222181745',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20221228101109',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230107224432',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230110130748',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230111074323',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230113165612',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230120111111',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230124120641',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230125164101',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230202152342',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230221073317',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230222075502',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230222174636',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230223101848',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230320110429',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230320131322',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230321133700',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230322114936',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230405162853',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230406113010',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230412105530',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230424084415',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230428112302',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230508121105',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230516161000',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230517115427',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230525131748',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230606112233',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230616085142',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20230913173812',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20231127124738',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20240131080600',NULL,NULL),('Pimcore\\Bundle\\CoreBundle\\Migrations\\Version20240222143211',NULL,NULL);
/*!40000 ALTER TABLE `migration_versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes`
--

DROP TABLE IF EXISTS `notes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(255) DEFAULT NULL,
  `cid` int DEFAULT NULL,
  `ctype` enum('asset','document','object') DEFAULT NULL,
  `date` int DEFAULT NULL,
  `user` int DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` longtext,
  `locked` tinyint unsigned DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `cid_ctype` (`cid`,`ctype`),
  KEY `date` (`date`),
  KEY `user` (`user`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes`
--

LOCK TABLES `notes` WRITE;
/*!40000 ALTER TABLE `notes` DISABLE KEYS */;
/*!40000 ALTER TABLE `notes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notes_data`
--

DROP TABLE IF EXISTS `notes_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notes_data` (
  `auto_id` int NOT NULL AUTO_INCREMENT,
  `id` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` enum('text','date','document','asset','object','bool') DEFAULT NULL,
  `data` text,
  PRIMARY KEY (`auto_id`),
  UNIQUE KEY `UNIQ_E5A8E5E2BF3967505E237E06` (`id`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notes_data`
--

LOCK TABLES `notes_data` WRITE;
/*!40000 ALTER TABLE `notes_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `notes_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notifications` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL DEFAULT 'info',
  `title` varchar(250) NOT NULL DEFAULT '',
  `message` text NOT NULL,
  `sender` int DEFAULT NULL,
  `recipient` int NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `creationDate` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `modificationDate` timestamp NULL DEFAULT NULL,
  `linkedElementType` enum('document','asset','object') DEFAULT NULL,
  `linkedElement` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recipient` (`recipient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `object_collection_paperCartridgeCodes_paper_cartridge`
--

DROP TABLE IF EXISTS `object_collection_paperCartridgeCodes_paper_cartridge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `object_collection_paperCartridgeCodes_paper_cartridge` (
  `id` int unsigned NOT NULL DEFAULT '0',
  `index` int NOT NULL DEFAULT '0',
  `fieldname` varchar(190) NOT NULL DEFAULT '',
  `companyName` varchar(190) DEFAULT NULL,
  `code` double DEFAULT NULL,
  PRIMARY KEY (`id`,`index`,`fieldname`),
  KEY `index` (`index`),
  KEY `fieldname` (`fieldname`),
  CONSTRAINT `fk_object_collection_paperCartridgeCodes_paper_cartridge__id` FOREIGN KEY (`id`) REFERENCES `objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object_collection_paperCartridgeCodes_paper_cartridge`
--

LOCK TABLES `object_collection_paperCartridgeCodes_paper_cartridge` WRITE;
/*!40000 ALTER TABLE `object_collection_paperCartridgeCodes_paper_cartridge` DISABLE KEYS */;
INSERT INTO `object_collection_paperCartridgeCodes_paper_cartridge` VALUES (3,0,'codes','PLEATCO',NULL),(3,1,'codes','MAGNUM',NULL),(3,2,'codes','Dailrly',40508),(3,3,'codes','UNICEL',4);
/*!40000 ALTER TABLE `object_collection_paperCartridgeCodes_paper_cartridge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `object_company`
--

DROP TABLE IF EXISTS `object_company`;
/*!50001 DROP VIEW IF EXISTS `object_company`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `object_company` AS SELECT 
 1 AS `oo_id`,
 1 AS `oo_classId`,
 1 AS `oo_className`,
 1 AS `name`,
 1 AS `id`,
 1 AS `parentId`,
 1 AS `type`,
 1 AS `key`,
 1 AS `path`,
 1 AS `index`,
 1 AS `published`,
 1 AS `creationDate`,
 1 AS `modificationDate`,
 1 AS `userOwner`,
 1 AS `userModification`,
 1 AS `classId`,
 1 AS `className`,
 1 AS `childrenSortBy`,
 1 AS `childrenSortOrder`,
 1 AS `versionCount`*/;
SET character_set_client = @saved_cs_client;

--
-- Temporary view structure for view `object_paper_cartridge`
--

DROP TABLE IF EXISTS `object_paper_cartridge`;
/*!50001 DROP VIEW IF EXISTS `object_paper_cartridge`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `object_paper_cartridge` AS SELECT 
 1 AS `oo_id`,
 1 AS `oo_classId`,
 1 AS `oo_className`,
 1 AS `name`,
 1 AS `images__images`,
 1 AS `images__hotspots`,
 1 AS `diameter`,
 1 AS `length`,
 1 AS `companies`,
 1 AS `bottom`,
 1 AS `diameterWithThread`,
 1 AS `diameterWithoutThread`,
 1 AS `pitch`,
 1 AS `threadImages__images`,
 1 AS `threadImages__hotspots`,
 1 AS `threadPosition`,
 1 AS `top`,
 1 AS `id`,
 1 AS `parentId`,
 1 AS `type`,
 1 AS `key`,
 1 AS `path`,
 1 AS `index`,
 1 AS `published`,
 1 AS `creationDate`,
 1 AS `modificationDate`,
 1 AS `userOwner`,
 1 AS `userModification`,
 1 AS `classId`,
 1 AS `className`,
 1 AS `childrenSortBy`,
 1 AS `childrenSortOrder`,
 1 AS `versionCount`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `object_query_company`
--

DROP TABLE IF EXISTS `object_query_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `object_query_company` (
  `oo_id` int unsigned NOT NULL DEFAULT '0',
  `oo_classId` varchar(50) DEFAULT 'company',
  `oo_className` varchar(255) DEFAULT 'Company',
  `name` varchar(190) DEFAULT NULL,
  PRIMARY KEY (`oo_id`),
  CONSTRAINT `fk_object_query_company__oo_id` FOREIGN KEY (`oo_id`) REFERENCES `objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object_query_company`
--

LOCK TABLES `object_query_company` WRITE;
/*!40000 ALTER TABLE `object_query_company` DISABLE KEYS */;
INSERT INTO `object_query_company` VALUES (5,'company','Company','Allseas Spas'),(6,'company','Company','Arctic Spas');
/*!40000 ALTER TABLE `object_query_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `object_query_paper_cartridge`
--

DROP TABLE IF EXISTS `object_query_paper_cartridge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `object_query_paper_cartridge` (
  `oo_id` int unsigned NOT NULL DEFAULT '0',
  `oo_classId` varchar(50) DEFAULT 'paper_cartridge',
  `oo_className` varchar(255) DEFAULT 'PaperCartridge',
  `name` varchar(190) DEFAULT NULL,
  `images__images` text,
  `images__hotspots` longtext,
  `diameter` double DEFAULT NULL,
  `length` double DEFAULT NULL,
  `companies` text,
  `bottom` varchar(190) DEFAULT NULL,
  `diameterWithThread` double DEFAULT NULL,
  `diameterWithoutThread` double DEFAULT NULL,
  `pitch` double DEFAULT NULL,
  `threadImages__images` text,
  `threadImages__hotspots` longtext,
  `threadPosition` varchar(190) DEFAULT NULL,
  `top` varchar(190) DEFAULT NULL,
  PRIMARY KEY (`oo_id`),
  CONSTRAINT `fk_object_query_paper_cartridge__oo_id` FOREIGN KEY (`oo_id`) REFERENCES `objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object_query_paper_cartridge`
--

LOCK TABLES `object_query_paper_cartridge` WRITE;
/*!40000 ALTER TABLE `object_query_paper_cartridge` DISABLE KEYS */;
INSERT INTO `object_query_paper_cartridge` VALUES (3,'paper_cartridge','PaperCartridge','SC757','','a:0:{}',130,330,',object|5,object|6,',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `object_query_paper_cartridge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `object_query_royal_filter`
--

DROP TABLE IF EXISTS `object_query_royal_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `object_query_royal_filter` (
  `oo_id` int unsigned NOT NULL DEFAULT '0',
  `oo_classId` varchar(50) DEFAULT 'royal_filter',
  `oo_className` varchar(255) DEFAULT 'RoyalFilter',
  PRIMARY KEY (`oo_id`),
  CONSTRAINT `fk_object_query_royal_filter__oo_id` FOREIGN KEY (`oo_id`) REFERENCES `objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object_query_royal_filter`
--

LOCK TABLES `object_query_royal_filter` WRITE;
/*!40000 ALTER TABLE `object_query_royal_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `object_query_royal_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `object_relations_company`
--

DROP TABLE IF EXISTS `object_relations_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `object_relations_company` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `src_id` int unsigned NOT NULL DEFAULT '0',
  `dest_id` int unsigned NOT NULL DEFAULT '0',
  `type` enum('object','asset','document') NOT NULL,
  `fieldname` varchar(70) NOT NULL DEFAULT '0',
  `index` int unsigned NOT NULL DEFAULT '0',
  `ownertype` enum('object','fieldcollection','localizedfield','objectbrick') NOT NULL DEFAULT 'object',
  `ownername` varchar(70) NOT NULL DEFAULT '',
  `position` varchar(70) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `forward_lookup` (`src_id`,`ownertype`,`ownername`,`position`),
  KEY `reverse_lookup` (`dest_id`,`type`),
  KEY `fieldname` (`fieldname`),
  CONSTRAINT `fk_object_relations_company__src_id` FOREIGN KEY (`src_id`) REFERENCES `objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object_relations_company`
--

LOCK TABLES `object_relations_company` WRITE;
/*!40000 ALTER TABLE `object_relations_company` DISABLE KEYS */;
/*!40000 ALTER TABLE `object_relations_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `object_relations_paper_cartridge`
--

DROP TABLE IF EXISTS `object_relations_paper_cartridge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `object_relations_paper_cartridge` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `src_id` int unsigned NOT NULL DEFAULT '0',
  `dest_id` int unsigned NOT NULL DEFAULT '0',
  `type` enum('object','asset','document') NOT NULL,
  `fieldname` varchar(70) NOT NULL DEFAULT '0',
  `index` int unsigned NOT NULL DEFAULT '0',
  `ownertype` enum('object','fieldcollection','localizedfield','objectbrick') NOT NULL DEFAULT 'object',
  `ownername` varchar(70) NOT NULL DEFAULT '',
  `position` varchar(70) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `forward_lookup` (`src_id`,`ownertype`,`ownername`,`position`),
  KEY `reverse_lookup` (`dest_id`,`type`),
  KEY `fieldname` (`fieldname`),
  CONSTRAINT `fk_object_relations_paper_cartridge__src_id` FOREIGN KEY (`src_id`) REFERENCES `objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object_relations_paper_cartridge`
--

LOCK TABLES `object_relations_paper_cartridge` WRITE;
/*!40000 ALTER TABLE `object_relations_paper_cartridge` DISABLE KEYS */;
INSERT INTO `object_relations_paper_cartridge` VALUES (2,3,5,'object','companies',1,'object','','0'),(3,3,6,'object','companies',2,'object','','0');
/*!40000 ALTER TABLE `object_relations_paper_cartridge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `object_relations_royal_filter`
--

DROP TABLE IF EXISTS `object_relations_royal_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `object_relations_royal_filter` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `src_id` int unsigned NOT NULL DEFAULT '0',
  `dest_id` int unsigned NOT NULL DEFAULT '0',
  `type` enum('object','asset','document') NOT NULL,
  `fieldname` varchar(70) NOT NULL DEFAULT '0',
  `index` int unsigned NOT NULL DEFAULT '0',
  `ownertype` enum('object','fieldcollection','localizedfield','objectbrick') NOT NULL DEFAULT 'object',
  `ownername` varchar(70) NOT NULL DEFAULT '',
  `position` varchar(70) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `forward_lookup` (`src_id`,`ownertype`,`ownername`,`position`),
  KEY `reverse_lookup` (`dest_id`,`type`),
  KEY `fieldname` (`fieldname`),
  CONSTRAINT `fk_object_relations_royal_filter__src_id` FOREIGN KEY (`src_id`) REFERENCES `objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object_relations_royal_filter`
--

LOCK TABLES `object_relations_royal_filter` WRITE;
/*!40000 ALTER TABLE `object_relations_royal_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `object_relations_royal_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `object_royal_filter`
--

DROP TABLE IF EXISTS `object_royal_filter`;
/*!50001 DROP VIEW IF EXISTS `object_royal_filter`*/;
SET @saved_cs_client     = @@character_set_client;
/*!50503 SET character_set_client = utf8mb4 */;
/*!50001 CREATE VIEW `object_royal_filter` AS SELECT 
 1 AS `oo_id`,
 1 AS `oo_classId`,
 1 AS `oo_className`,
 1 AS `id`,
 1 AS `parentId`,
 1 AS `type`,
 1 AS `key`,
 1 AS `path`,
 1 AS `index`,
 1 AS `published`,
 1 AS `creationDate`,
 1 AS `modificationDate`,
 1 AS `userOwner`,
 1 AS `userModification`,
 1 AS `classId`,
 1 AS `className`,
 1 AS `childrenSortBy`,
 1 AS `childrenSortOrder`,
 1 AS `versionCount`*/;
SET character_set_client = @saved_cs_client;

--
-- Table structure for table `object_store_company`
--

DROP TABLE IF EXISTS `object_store_company`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `object_store_company` (
  `oo_id` int unsigned NOT NULL DEFAULT '0',
  `name` varchar(190) DEFAULT NULL,
  PRIMARY KEY (`oo_id`),
  CONSTRAINT `fk_object_store_company__oo_id` FOREIGN KEY (`oo_id`) REFERENCES `objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object_store_company`
--

LOCK TABLES `object_store_company` WRITE;
/*!40000 ALTER TABLE `object_store_company` DISABLE KEYS */;
INSERT INTO `object_store_company` VALUES (5,'Allseas Spas'),(6,'Arctic Spas');
/*!40000 ALTER TABLE `object_store_company` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `object_store_paper_cartridge`
--

DROP TABLE IF EXISTS `object_store_paper_cartridge`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `object_store_paper_cartridge` (
  `oo_id` int unsigned NOT NULL DEFAULT '0',
  `name` varchar(190) DEFAULT NULL,
  `images__images` text,
  `images__hotspots` longtext,
  `diameter` double DEFAULT NULL,
  `length` double DEFAULT NULL,
  `bottom` varchar(190) DEFAULT NULL,
  `diameterWithThread` double DEFAULT NULL,
  `diameterWithoutThread` double DEFAULT NULL,
  `pitch` double DEFAULT NULL,
  `threadImages__images` text,
  `threadImages__hotspots` longtext,
  `threadPosition` varchar(190) DEFAULT NULL,
  `top` varchar(190) DEFAULT NULL,
  PRIMARY KEY (`oo_id`),
  CONSTRAINT `fk_object_store_paper_cartridge__oo_id` FOREIGN KEY (`oo_id`) REFERENCES `objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object_store_paper_cartridge`
--

LOCK TABLES `object_store_paper_cartridge` WRITE;
/*!40000 ALTER TABLE `object_store_paper_cartridge` DISABLE KEYS */;
INSERT INTO `object_store_paper_cartridge` VALUES (3,'SC757','','a:0:{}',130,330,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `object_store_paper_cartridge` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `object_store_royal_filter`
--

DROP TABLE IF EXISTS `object_store_royal_filter`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `object_store_royal_filter` (
  `oo_id` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`oo_id`),
  CONSTRAINT `fk_object_store_royal_filter__oo_id` FOREIGN KEY (`oo_id`) REFERENCES `objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object_store_royal_filter`
--

LOCK TABLES `object_store_royal_filter` WRITE;
/*!40000 ALTER TABLE `object_store_royal_filter` DISABLE KEYS */;
/*!40000 ALTER TABLE `object_store_royal_filter` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `object_url_slugs`
--

DROP TABLE IF EXISTS `object_url_slugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `object_url_slugs` (
  `objectId` int unsigned NOT NULL DEFAULT '0',
  `classId` varchar(50) NOT NULL DEFAULT '0',
  `fieldname` varchar(70) NOT NULL DEFAULT '0',
  `ownertype` enum('object','fieldcollection','localizedfield','objectbrick') NOT NULL DEFAULT 'object',
  `ownername` varchar(70) NOT NULL DEFAULT '',
  `position` varchar(70) NOT NULL DEFAULT '0',
  `slug` varchar(765) NOT NULL,
  `siteId` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`slug`,`siteId`),
  KEY `objectId` (`objectId`),
  KEY `classId` (`classId`),
  KEY `fieldname` (`fieldname`),
  KEY `position` (`position`),
  KEY `ownertype` (`ownertype`),
  KEY `ownername` (`ownername`),
  KEY `slug` (`slug`),
  KEY `siteId` (`siteId`),
  KEY `fieldname_ownertype_position_objectId` (`fieldname`,`ownertype`,`position`,`objectId`),
  CONSTRAINT `fk_object_url_slugs__objectId` FOREIGN KEY (`objectId`) REFERENCES `objects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `object_url_slugs`
--

LOCK TABLES `object_url_slugs` WRITE;
/*!40000 ALTER TABLE `object_url_slugs` DISABLE KEYS */;
/*!40000 ALTER TABLE `object_url_slugs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `objects`
--

DROP TABLE IF EXISTS `objects`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `objects` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parentId` int unsigned DEFAULT NULL,
  `type` enum('object','folder','variant') DEFAULT NULL,
  `key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT '',
  `path` varchar(765) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `index` int unsigned DEFAULT '0',
  `published` tinyint unsigned DEFAULT '1',
  `creationDate` int unsigned DEFAULT NULL,
  `modificationDate` int unsigned DEFAULT NULL,
  `userOwner` int unsigned DEFAULT NULL,
  `userModification` int unsigned DEFAULT NULL,
  `classId` varchar(50) DEFAULT NULL,
  `className` varchar(255) DEFAULT NULL,
  `childrenSortBy` enum('key','index') DEFAULT NULL,
  `childrenSortOrder` enum('ASC','DESC') DEFAULT NULL,
  `versionCount` int unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `fullpath` (`path`,`key`),
  KEY `key` (`key`),
  KEY `index` (`index`),
  KEY `published` (`published`),
  KEY `parentId` (`parentId`),
  KEY `type_path_classId` (`type`,`path`,`classId`),
  KEY `modificationDate` (`modificationDate`),
  KEY `classId` (`classId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `objects`
--

LOCK TABLES `objects` WRITE;
/*!40000 ALTER TABLE `objects` DISABLE KEYS */;
INSERT INTO `objects` VALUES (1,0,'folder','','/',999999,1,1719596425,1719596425,1,1,NULL,NULL,NULL,NULL,0),(2,1,'folder','Paper cartridges','/',0,1,1719597101,1719597111,2,2,NULL,NULL,NULL,NULL,3),(3,2,'object','SC757','/Paper cartridges/',0,1,1719597137,1719599220,2,2,'paper_cartridge','PaperCartridge',NULL,NULL,12),(4,1,'folder','Companies','/',0,1,1719598997,1719598997,2,2,NULL,NULL,NULL,NULL,2),(5,4,'object','Allseas Spas','/Companies/',0,1,1719599005,1719599009,2,2,'company','Company',NULL,NULL,3),(6,4,'object','Arctic Spas','/Companies/',0,1,1719599043,1719599047,2,2,'company','Company',NULL,NULL,3);
/*!40000 ALTER TABLE `objects` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `properties`
--

DROP TABLE IF EXISTS `properties`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `properties` (
  `cid` int unsigned NOT NULL DEFAULT '0',
  `ctype` enum('document','asset','object') NOT NULL DEFAULT 'document',
  `cpath` varchar(765) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `name` varchar(190) NOT NULL DEFAULT '',
  `type` enum('text','document','asset','object','bool','select') DEFAULT NULL,
  `data` text,
  `inheritable` tinyint unsigned DEFAULT '1',
  PRIMARY KEY (`cid`,`ctype`,`name`),
  KEY `getall` (`cpath`,`ctype`,`inheritable`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `properties`
--

LOCK TABLES `properties` WRITE;
/*!40000 ALTER TABLE `properties` DISABLE KEYS */;
/*!40000 ALTER TABLE `properties` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `quantityvalue_units`
--

DROP TABLE IF EXISTS `quantityvalue_units`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `quantityvalue_units` (
  `id` varchar(50) NOT NULL,
  `group` varchar(50) DEFAULT NULL,
  `abbreviation` varchar(20) DEFAULT NULL,
  `longname` varchar(250) DEFAULT NULL,
  `baseunit` varchar(50) DEFAULT NULL,
  `factor` double DEFAULT NULL,
  `conversionOffset` double DEFAULT NULL,
  `reference` varchar(50) DEFAULT NULL,
  `converter` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_baseunit` (`baseunit`),
  CONSTRAINT `fk_baseunit` FOREIGN KEY (`baseunit`) REFERENCES `quantityvalue_units` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `quantityvalue_units`
--

LOCK TABLES `quantityvalue_units` WRITE;
/*!40000 ALTER TABLE `quantityvalue_units` DISABLE KEYS */;
/*!40000 ALTER TABLE `quantityvalue_units` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `recyclebin`
--

DROP TABLE IF EXISTS `recyclebin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `recyclebin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type` varchar(20) DEFAULT NULL,
  `subtype` varchar(20) DEFAULT NULL,
  `path` varchar(765) DEFAULT NULL,
  `amount` int DEFAULT NULL,
  `date` int unsigned DEFAULT NULL,
  `deletedby` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `recyclebin_date` (`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `recyclebin`
--

LOCK TABLES `recyclebin` WRITE;
/*!40000 ALTER TABLE `recyclebin` DISABLE KEYS */;
/*!40000 ALTER TABLE `recyclebin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `schedule_tasks`
--

DROP TABLE IF EXISTS `schedule_tasks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `schedule_tasks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `cid` int unsigned DEFAULT NULL,
  `ctype` enum('document','asset','object') DEFAULT NULL,
  `date` int unsigned DEFAULT NULL,
  `action` enum('publish','unpublish','delete','publish-version') DEFAULT NULL,
  `version` bigint unsigned DEFAULT NULL,
  `active` tinyint unsigned DEFAULT '0',
  `userId` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `ctype` (`ctype`),
  KEY `active` (`active`),
  KEY `version` (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `schedule_tasks`
--

LOCK TABLES `schedule_tasks` WRITE;
/*!40000 ALTER TABLE `schedule_tasks` DISABLE KEYS */;
/*!40000 ALTER TABLE `schedule_tasks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `search_backend_data`
--

DROP TABLE IF EXISTS `search_backend_data`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `search_backend_data` (
  `id` int NOT NULL,
  `key` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT '',
  `index` int unsigned DEFAULT '0',
  `fullpath` varchar(765) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci DEFAULT NULL,
  `maintype` varchar(8) NOT NULL DEFAULT '',
  `type` varchar(20) DEFAULT NULL,
  `subtype` varchar(190) DEFAULT NULL,
  `published` tinyint unsigned DEFAULT NULL,
  `creationDate` int unsigned DEFAULT NULL,
  `modificationDate` int unsigned DEFAULT NULL,
  `userOwner` int DEFAULT NULL,
  `userModification` int DEFAULT NULL,
  `data` longtext,
  `properties` text,
  PRIMARY KEY (`id`,`maintype`),
  KEY `key` (`key`),
  KEY `index` (`index`),
  KEY `fullpath` (`fullpath`),
  KEY `maintype` (`maintype`),
  KEY `type` (`type`),
  KEY `subtype` (`subtype`),
  KEY `published` (`published`),
  FULLTEXT KEY `fulltext` (`data`,`properties`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `search_backend_data`
--

LOCK TABLES `search_backend_data` WRITE;
/*!40000 ALTER TABLE `search_backend_data` DISABLE KEYS */;
/*!40000 ALTER TABLE `search_backend_data` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings_store`
--

DROP TABLE IF EXISTS `settings_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings_store` (
  `id` varchar(190) NOT NULL DEFAULT '',
  `scope` varchar(190) NOT NULL DEFAULT '',
  `data` longtext,
  `type` enum('bool','int','float','string') NOT NULL DEFAULT 'string',
  PRIMARY KEY (`id`,`scope`),
  KEY `scope` (`scope`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings_store`
--

LOCK TABLES `settings_store` WRITE;
/*!40000 ALTER TABLE `settings_store` DISABLE KEYS */;
INSERT INTO `settings_store` VALUES ('BUNDLE_INSTALLED__Pimcore\\Bundle\\AdminBundle\\PimcoreAdminBundle','pimcore','1','bool'),('BUNDLE_INSTALLED__Pimcore\\Bundle\\SimpleBackendSearchBundle\\PimcoreSimpleBackendSearchBundle','pimcore','1','bool'),('BUNDLE_INSTALLED__Pimcore\\Bundle\\StaticRoutesBundle\\PimcoreStaticRoutesBundle','pimcore','1','bool'),('BUNDLE_INSTALLED__Pimcore\\Bundle\\TinymceBundle\\PimcoreTinymceBundle','pimcore','1','bool'),('BUNDLE_INSTALLED__Pimcore\\Bundle\\UuidBundle\\PimcoreUuidBundle','pimcore','1','bool');
/*!40000 ALTER TABLE `settings_store` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sites`
--

DROP TABLE IF EXISTS `sites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sites` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `mainDomain` varchar(255) DEFAULT NULL,
  `domains` text,
  `rootId` int unsigned DEFAULT NULL,
  `errorDocument` varchar(255) DEFAULT NULL,
  `localizedErrorDocuments` text,
  `redirectToMainDomain` tinyint(1) DEFAULT NULL,
  `creationDate` int unsigned DEFAULT '0',
  `modificationDate` int unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `rootId` (`rootId`),
  CONSTRAINT `fk_sites_documents` FOREIGN KEY (`rootId`) REFERENCES `documents` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sites`
--

LOCK TABLES `sites` WRITE;
/*!40000 ALTER TABLE `sites` DISABLE KEYS */;
/*!40000 ALTER TABLE `sites` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parentId` int unsigned DEFAULT NULL,
  `idPath` varchar(190) DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `idPath_name` (`idPath`,`name`),
  KEY `idpath` (`idPath`),
  KEY `parentid` (`parentId`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags_assignment`
--

DROP TABLE IF EXISTS `tags_assignment`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags_assignment` (
  `tagid` int unsigned NOT NULL DEFAULT '0',
  `cid` int NOT NULL DEFAULT '0',
  `ctype` enum('document','asset','object') NOT NULL,
  PRIMARY KEY (`tagid`,`cid`,`ctype`),
  KEY `ctype` (`ctype`),
  KEY `ctype_cid` (`cid`,`ctype`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags_assignment`
--

LOCK TABLES `tags_assignment` WRITE;
/*!40000 ALTER TABLE `tags_assignment` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags_assignment` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tmp_store`
--

DROP TABLE IF EXISTS `tmp_store`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tmp_store` (
  `id` varchar(190) NOT NULL DEFAULT '',
  `tag` varchar(190) DEFAULT NULL,
  `data` longtext,
  `serialized` tinyint NOT NULL DEFAULT '0',
  `date` int unsigned DEFAULT NULL,
  `expiryDate` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tag` (`tag`),
  KEY `date` (`date`),
  KEY `expiryDate` (`expiryDate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tmp_store`
--

LOCK TABLES `tmp_store` WRITE;
/*!40000 ALTER TABLE `tmp_store` DISABLE KEYS */;
/*!40000 ALTER TABLE `tmp_store` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translations_admin`
--

DROP TABLE IF EXISTS `translations_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `translations_admin` (
  `key` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `type` varchar(10) DEFAULT NULL,
  `language` varchar(10) NOT NULL DEFAULT '',
  `text` text,
  `creationDate` int unsigned DEFAULT NULL,
  `modificationDate` int unsigned DEFAULT NULL,
  `userOwner` int unsigned DEFAULT NULL,
  `userModification` int unsigned DEFAULT NULL,
  PRIMARY KEY (`key`,`language`),
  KEY `language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations_admin`
--

LOCK TABLES `translations_admin` WRITE;
/*!40000 ALTER TABLE `translations_admin` DISABLE KEYS */;
INSERT INTO `translations_admin` VALUES ('Base','simple','ca','',1719598732,1719598732,2,2),('Base','simple','cs','',1719598732,1719598732,2,2),('Base','simple','de','',1719598732,1719598732,2,2),('Base','simple','en','',1719598732,1719598732,2,2),('Base','simple','es','',1719598732,1719598732,2,2),('Base','simple','fr','',1719598732,1719598732,2,2),('Base','simple','hu','',1719598732,1719598732,2,2),('Base','simple','it','',1719598732,1719598732,2,2),('Base','simple','ja','',1719598732,1719598732,2,2),('Base','simple','nl','',1719598732,1719598732,2,2),('Base','simple','pl','',1719598732,1719598732,2,2),('Base','simple','pt','',1719598732,1719598732,2,2),('Base','simple','ru','',1719598732,1719598732,2,2),('Base','simple','sk','',1719598732,1719598732,2,2),('Base','simple','sv','',1719598732,1719598732,2,2),('Base','simple','th','',1719598732,1719598732,2,2),('Base','simple','tr','',1719598732,1719598732,2,2),('Base','simple','uk','',1719598732,1719598732,2,2),('Base','simple','zh_Hans','',1719598732,1719598732,2,2),('Bottom','simple','ca','',1719599223,1719599223,2,2),('Bottom','simple','cs','',1719599223,1719599223,2,2),('Bottom','simple','de','',1719599223,1719599223,2,2),('Bottom','simple','en','',1719599223,1719599223,2,2),('Bottom','simple','es','',1719599223,1719599223,2,2),('Bottom','simple','fr','',1719599223,1719599223,2,2),('Bottom','simple','hu','',1719599223,1719599223,2,2),('Bottom','simple','it','',1719599223,1719599223,2,2),('Bottom','simple','ja','',1719599223,1719599223,2,2),('Bottom','simple','nl','',1719599223,1719599223,2,2),('Bottom','simple','pl','',1719599223,1719599223,2,2),('Bottom','simple','pt','',1719599223,1719599223,2,2),('Bottom','simple','ru','',1719599223,1719599223,2,2),('Bottom','simple','sk','',1719599223,1719599223,2,2),('Bottom','simple','sv','',1719599223,1719599223,2,2),('Bottom','simple','th','',1719599223,1719599223,2,2),('Bottom','simple','tr','',1719599223,1719599223,2,2),('Bottom','simple','uk','',1719599223,1719599223,2,2),('Bottom','simple','zh_Hans','',1719599223,1719599223,2,2),('Code','simple','ca','',1719597142,1719597142,2,2),('Code','simple','cs','',1719597142,1719597142,2,2),('Code','simple','de','',1719597142,1719597142,2,2),('Code','simple','en','',1719597142,1719597142,2,2),('Code','simple','es','',1719597142,1719597142,2,2),('Code','simple','fr','',1719597142,1719597142,2,2),('Code','simple','hu','',1719597142,1719597142,2,2),('Code','simple','it','',1719597142,1719597142,2,2),('Code','simple','ja','',1719597142,1719597142,2,2),('Code','simple','nl','',1719597142,1719597142,2,2),('Code','simple','pl','',1719597142,1719597142,2,2),('Code','simple','pt','',1719597142,1719597142,2,2),('Code','simple','ru','',1719597142,1719597142,2,2),('Code','simple','sk','',1719597142,1719597142,2,2),('Code','simple','sv','',1719597142,1719597142,2,2),('Code','simple','th','',1719597142,1719597142,2,2),('Code','simple','tr','',1719597142,1719597142,2,2),('Code','simple','uk','',1719597142,1719597142,2,2),('Code','simple','zh_Hans','',1719597142,1719597142,2,2),('Codes','simple','ca','',1719598732,1719598732,2,2),('Codes','simple','cs','',1719598732,1719598732,2,2),('Codes','simple','de','',1719598732,1719598732,2,2),('Codes','simple','en','',1719598732,1719598732,2,2),('Codes','simple','es','',1719598732,1719598732,2,2),('Codes','simple','fr','',1719598732,1719598732,2,2),('Codes','simple','hu','',1719598732,1719598732,2,2),('Codes','simple','it','',1719598732,1719598732,2,2),('Codes','simple','ja','',1719598732,1719598732,2,2),('Codes','simple','nl','',1719598732,1719598732,2,2),('Codes','simple','pl','',1719598732,1719598732,2,2),('Codes','simple','pt','',1719598732,1719598732,2,2),('Codes','simple','ru','',1719598732,1719598732,2,2),('Codes','simple','sk','',1719598732,1719598732,2,2),('Codes','simple','sv','',1719598732,1719598732,2,2),('Codes','simple','th','',1719598732,1719598732,2,2),('Codes','simple','tr','',1719598732,1719598732,2,2),('Codes','simple','uk','',1719598732,1719598732,2,2),('Codes','simple','zh_Hans','',1719598732,1719598732,2,2),('Companies','simple','ca','',1719598983,1719598983,2,2),('Companies','simple','cs','',1719598983,1719598983,2,2),('Companies','simple','de','',1719598983,1719598983,2,2),('Companies','simple','en','',1719598983,1719598983,2,2),('Companies','simple','es','',1719598983,1719598983,2,2),('Companies','simple','fr','',1719598983,1719598983,2,2),('Companies','simple','hu','',1719598983,1719598983,2,2),('Companies','simple','it','',1719598983,1719598983,2,2),('Companies','simple','ja','',1719598983,1719598983,2,2),('Companies','simple','nl','',1719598983,1719598983,2,2),('Companies','simple','pl','',1719598983,1719598983,2,2),('Companies','simple','pt','',1719598983,1719598983,2,2),('Companies','simple','ru','',1719598983,1719598983,2,2),('Companies','simple','sk','',1719598983,1719598983,2,2),('Companies','simple','sv','',1719598983,1719598983,2,2),('Companies','simple','th','',1719598983,1719598983,2,2),('Companies','simple','tr','',1719598983,1719598983,2,2),('Companies','simple','uk','',1719598983,1719598983,2,2),('Companies','simple','zh_Hans','',1719598983,1719598983,2,2),('Company','simple','ca','Company',1719598938,1719598938,2,2),('Company','simple','cs','Company',1719598938,1719598938,2,2),('Company','simple','de','Company',1719598938,1719598938,2,2),('Company','simple','en','Company',1719598938,1719598938,2,2),('Company','simple','es','Company',1719598938,1719598938,2,2),('Company','simple','fr','Company',1719598938,1719598938,2,2),('Company','simple','hu','Company',1719598938,1719598938,2,2),('Company','simple','it','Company',1719598938,1719598938,2,2),('Company','simple','ja','Company',1719598938,1719598938,2,2),('Company','simple','nl','Company',1719598938,1719598938,2,2),('Company','simple','pl','Company',1719598938,1719598938,2,2),('Company','simple','pt','Company',1719598938,1719598938,2,2),('Company','simple','ru','Company',1719598938,1719598938,2,2),('Company','simple','sk','Company',1719598938,1719598938,2,2),('Company','simple','sv','Company',1719598938,1719598938,2,2),('Company','simple','th','Company',1719598938,1719598938,2,2),('Company','simple','tr','Company',1719598938,1719598938,2,2),('Company','simple','uk','Company',1719598938,1719598938,2,2),('Company','simple','zh_Hans','Company',1719598938,1719598938,2,2),('Company Name','simple','ca','',1719598762,1719598762,2,2),('Company Name','simple','cs','',1719598762,1719598762,2,2),('Company Name','simple','de','',1719598762,1719598762,2,2),('Company Name','simple','en','',1719598762,1719598762,2,2),('Company Name','simple','es','',1719598762,1719598762,2,2),('Company Name','simple','fr','',1719598762,1719598762,2,2),('Company Name','simple','hu','',1719598762,1719598762,2,2),('Company Name','simple','it','',1719598762,1719598762,2,2),('Company Name','simple','ja','',1719598762,1719598762,2,2),('Company Name','simple','nl','',1719598762,1719598762,2,2),('Company Name','simple','pl','',1719598762,1719598762,2,2),('Company Name','simple','pt','',1719598762,1719598762,2,2),('Company Name','simple','ru','',1719598762,1719598762,2,2),('Company Name','simple','sk','',1719598762,1719598762,2,2),('Company Name','simple','sv','',1719598762,1719598762,2,2),('Company Name','simple','th','',1719598762,1719598762,2,2),('Company Name','simple','tr','',1719598762,1719598762,2,2),('Company Name','simple','uk','',1719598762,1719598762,2,2),('Company Name','simple','zh_Hans','',1719598762,1719598762,2,2),('Diameter','simple','ca','',1719598732,1719598732,2,2),('Diameter','simple','cs','',1719598732,1719598732,2,2),('Diameter','simple','de','',1719598732,1719598732,2,2),('Diameter','simple','en','',1719598732,1719598732,2,2),('Diameter','simple','es','',1719598732,1719598732,2,2),('Diameter','simple','fr','',1719598732,1719598732,2,2),('Diameter','simple','hu','',1719598732,1719598732,2,2),('Diameter','simple','it','',1719598732,1719598732,2,2),('Diameter','simple','ja','',1719598732,1719598732,2,2),('Diameter','simple','nl','',1719598732,1719598732,2,2),('Diameter','simple','pl','',1719598732,1719598732,2,2),('Diameter','simple','pt','',1719598732,1719598732,2,2),('Diameter','simple','ru','',1719598732,1719598732,2,2),('Diameter','simple','sk','',1719598732,1719598732,2,2),('Diameter','simple','sv','',1719598732,1719598732,2,2),('Diameter','simple','th','',1719598732,1719598732,2,2),('Diameter','simple','tr','',1719598732,1719598732,2,2),('Diameter','simple','uk','',1719598732,1719598732,2,2),('Diameter','simple','zh_Hans','',1719598732,1719598732,2,2),('Diameter With Thread','simple','ca','',1719599223,1719599223,2,2),('Diameter With Thread','simple','cs','',1719599223,1719599223,2,2),('Diameter With Thread','simple','de','',1719599223,1719599223,2,2),('Diameter With Thread','simple','en','',1719599223,1719599223,2,2),('Diameter With Thread','simple','es','',1719599223,1719599223,2,2),('Diameter With Thread','simple','fr','',1719599223,1719599223,2,2),('Diameter With Thread','simple','hu','',1719599223,1719599223,2,2),('Diameter With Thread','simple','it','',1719599223,1719599223,2,2),('Diameter With Thread','simple','ja','',1719599223,1719599223,2,2),('Diameter With Thread','simple','nl','',1719599223,1719599223,2,2),('Diameter With Thread','simple','pl','',1719599223,1719599223,2,2),('Diameter With Thread','simple','pt','',1719599223,1719599223,2,2),('Diameter With Thread','simple','ru','',1719599223,1719599223,2,2),('Diameter With Thread','simple','sk','',1719599223,1719599223,2,2),('Diameter With Thread','simple','sv','',1719599223,1719599223,2,2),('Diameter With Thread','simple','th','',1719599223,1719599223,2,2),('Diameter With Thread','simple','tr','',1719599223,1719599223,2,2),('Diameter With Thread','simple','uk','',1719599223,1719599223,2,2),('Diameter With Thread','simple','zh_Hans','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','ca','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','cs','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','de','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','en','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','es','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','fr','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','hu','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','it','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','ja','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','nl','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','pl','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','pt','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','ru','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','sk','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','sv','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','th','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','tr','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','uk','',1719599223,1719599223,2,2),('Diameter Without Thread','simple','zh_Hans','',1719599223,1719599223,2,2),('Dimensions','simple','ca','',1719598732,1719598732,2,2),('Dimensions','simple','cs','',1719598732,1719598732,2,2),('Dimensions','simple','de','',1719598732,1719598732,2,2),('Dimensions','simple','en','',1719598732,1719598732,2,2),('Dimensions','simple','es','',1719598732,1719598732,2,2),('Dimensions','simple','fr','',1719598732,1719598732,2,2),('Dimensions','simple','hu','',1719598732,1719598732,2,2),('Dimensions','simple','it','',1719598732,1719598732,2,2),('Dimensions','simple','ja','',1719598732,1719598732,2,2),('Dimensions','simple','nl','',1719598732,1719598732,2,2),('Dimensions','simple','pl','',1719598732,1719598732,2,2),('Dimensions','simple','pt','',1719598732,1719598732,2,2),('Dimensions','simple','ru','',1719598732,1719598732,2,2),('Dimensions','simple','sk','',1719598732,1719598732,2,2),('Dimensions','simple','sv','',1719598732,1719598732,2,2),('Dimensions','simple','th','',1719598732,1719598732,2,2),('Dimensions','simple','tr','',1719598732,1719598732,2,2),('Dimensions','simple','uk','',1719598732,1719598732,2,2),('Dimensions','simple','zh_Hans','',1719598732,1719598732,2,2),('English','simple','ca','',1719597142,1719597142,2,2),('English','simple','cs','',1719597142,1719597142,2,2),('English','simple','de','',1719597142,1719597142,2,2),('English','simple','en','',1719597142,1719597142,2,2),('English','simple','es','',1719597142,1719597142,2,2),('English','simple','fr','',1719597142,1719597142,2,2),('English','simple','hu','',1719597142,1719597142,2,2),('English','simple','it','',1719597142,1719597142,2,2),('English','simple','ja','',1719597142,1719597142,2,2),('English','simple','nl','',1719597142,1719597142,2,2),('English','simple','pl','',1719597142,1719597142,2,2),('English','simple','pt','',1719597142,1719597142,2,2),('English','simple','ru','',1719597142,1719597142,2,2),('English','simple','sk','',1719597142,1719597142,2,2),('English','simple','sv','',1719597142,1719597142,2,2),('English','simple','th','',1719597142,1719597142,2,2),('English','simple','tr','',1719597142,1719597142,2,2),('English','simple','uk','',1719597142,1719597142,2,2),('English','simple','zh_Hans','',1719597142,1719597142,2,2),('French','simple','ca','',1719597142,1719597142,2,2),('French','simple','cs','',1719597142,1719597142,2,2),('French','simple','de','',1719597142,1719597142,2,2),('French','simple','en','',1719597142,1719597142,2,2),('French','simple','es','',1719597142,1719597142,2,2),('French','simple','fr','',1719597142,1719597142,2,2),('French','simple','hu','',1719597142,1719597142,2,2),('French','simple','it','',1719597142,1719597142,2,2),('French','simple','ja','',1719597142,1719597142,2,2),('French','simple','nl','',1719597142,1719597142,2,2),('French','simple','pl','',1719597142,1719597142,2,2),('French','simple','pt','',1719597142,1719597142,2,2),('French','simple','ru','',1719597142,1719597142,2,2),('French','simple','sk','',1719597142,1719597142,2,2),('French','simple','sv','',1719597142,1719597142,2,2),('French','simple','th','',1719597142,1719597142,2,2),('French','simple','tr','',1719597142,1719597142,2,2),('French','simple','uk','',1719597142,1719597142,2,2),('French','simple','zh_Hans','',1719597142,1719597142,2,2),('German','simple','ca','',1719597142,1719597142,2,2),('German','simple','cs','',1719597142,1719597142,2,2),('German','simple','de','',1719597142,1719597142,2,2),('German','simple','en','',1719597142,1719597142,2,2),('German','simple','es','',1719597142,1719597142,2,2),('German','simple','fr','',1719597142,1719597142,2,2),('German','simple','hu','',1719597142,1719597142,2,2),('German','simple','it','',1719597142,1719597142,2,2),('German','simple','ja','',1719597142,1719597142,2,2),('German','simple','nl','',1719597142,1719597142,2,2),('German','simple','pl','',1719597142,1719597142,2,2),('German','simple','pt','',1719597142,1719597142,2,2),('German','simple','ru','',1719597142,1719597142,2,2),('German','simple','sk','',1719597142,1719597142,2,2),('German','simple','sv','',1719597142,1719597142,2,2),('German','simple','th','',1719597142,1719597142,2,2),('German','simple','tr','',1719597142,1719597142,2,2),('German','simple','uk','',1719597142,1719597142,2,2),('German','simple','zh_Hans','',1719597142,1719597142,2,2),('Images','simple','ca','',1719597142,1719597142,2,2),('Images','simple','cs','',1719597142,1719597142,2,2),('Images','simple','de','',1719597142,1719597142,2,2),('Images','simple','en','',1719597142,1719597142,2,2),('Images','simple','es','',1719597142,1719597142,2,2),('Images','simple','fr','',1719597142,1719597142,2,2),('Images','simple','hu','',1719597142,1719597142,2,2),('Images','simple','it','',1719597142,1719597142,2,2),('Images','simple','ja','',1719597142,1719597142,2,2),('Images','simple','nl','',1719597142,1719597142,2,2),('Images','simple','pl','',1719597142,1719597142,2,2),('Images','simple','pt','',1719597142,1719597142,2,2),('Images','simple','ru','',1719597142,1719597142,2,2),('Images','simple','sk','',1719597142,1719597142,2,2),('Images','simple','sv','',1719597142,1719597142,2,2),('Images','simple','th','',1719597142,1719597142,2,2),('Images','simple','tr','',1719597142,1719597142,2,2),('Images','simple','uk','',1719597142,1719597142,2,2),('Images','simple','zh_Hans','',1719597142,1719597142,2,2),('Inside','simple','ca','',1719599343,1719599343,2,2),('Inside','simple','cs','',1719599343,1719599343,2,2),('Inside','simple','de','',1719599343,1719599343,2,2),('Inside','simple','en','',1719599343,1719599343,2,2),('Inside','simple','es','',1719599343,1719599343,2,2),('Inside','simple','fr','',1719599343,1719599343,2,2),('Inside','simple','hu','',1719599343,1719599343,2,2),('Inside','simple','it','',1719599343,1719599343,2,2),('Inside','simple','ja','',1719599343,1719599343,2,2),('Inside','simple','nl','',1719599343,1719599343,2,2),('Inside','simple','pl','',1719599343,1719599343,2,2),('Inside','simple','pt','',1719599343,1719599343,2,2),('Inside','simple','ru','',1719599343,1719599343,2,2),('Inside','simple','sk','',1719599343,1719599343,2,2),('Inside','simple','sv','',1719599343,1719599343,2,2),('Inside','simple','th','',1719599343,1719599343,2,2),('Inside','simple','tr','',1719599343,1719599343,2,2),('Inside','simple','uk','',1719599343,1719599343,2,2),('Inside','simple','zh_Hans','',1719599343,1719599343,2,2),('Length','simple','ca','',1719598732,1719598732,2,2),('Length','simple','cs','',1719598732,1719598732,2,2),('Length','simple','de','',1719598732,1719598732,2,2),('Length','simple','en','',1719598732,1719598732,2,2),('Length','simple','es','',1719598732,1719598732,2,2),('Length','simple','fr','',1719598732,1719598732,2,2),('Length','simple','hu','',1719598732,1719598732,2,2),('Length','simple','it','',1719598732,1719598732,2,2),('Length','simple','ja','',1719598732,1719598732,2,2),('Length','simple','nl','',1719598732,1719598732,2,2),('Length','simple','pl','',1719598732,1719598732,2,2),('Length','simple','pt','',1719598732,1719598732,2,2),('Length','simple','ru','',1719598732,1719598732,2,2),('Length','simple','sk','',1719598732,1719598732,2,2),('Length','simple','sv','',1719598732,1719598732,2,2),('Length','simple','th','',1719598732,1719598732,2,2),('Length','simple','tr','',1719598732,1719598732,2,2),('Length','simple','uk','',1719598732,1719598732,2,2),('Length','simple','zh_Hans','',1719598732,1719598732,2,2),('Main','simple','ca','',1719597142,1719597142,2,2),('Main','simple','cs','',1719597142,1719597142,2,2),('Main','simple','de','',1719597142,1719597142,2,2),('Main','simple','en','',1719597142,1719597142,2,2),('Main','simple','es','',1719597142,1719597142,2,2),('Main','simple','fr','',1719597142,1719597142,2,2),('Main','simple','hu','',1719597142,1719597142,2,2),('Main','simple','it','',1719597142,1719597142,2,2),('Main','simple','ja','',1719597142,1719597142,2,2),('Main','simple','nl','',1719597142,1719597142,2,2),('Main','simple','pl','',1719597142,1719597142,2,2),('Main','simple','pt','',1719597142,1719597142,2,2),('Main','simple','ru','',1719597142,1719597142,2,2),('Main','simple','sk','',1719597142,1719597142,2,2),('Main','simple','sv','',1719597142,1719597142,2,2),('Main','simple','th','',1719597142,1719597142,2,2),('Main','simple','tr','',1719597142,1719597142,2,2),('Main','simple','uk','',1719597142,1719597142,2,2),('Main','simple','zh_Hans','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','ca','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','cs','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','de','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','en','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','es','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','fr','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','hu','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','it','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','ja','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','nl','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','pl','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','pt','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','ru','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','sk','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','sv','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','th','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','tr','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','uk','',1719597142,1719597142,2,2),('Main (Admin Mode)','simple','zh_Hans','',1719597142,1719597142,2,2),('Outside','simple','ca','',1719599343,1719599343,2,2),('Outside','simple','cs','',1719599343,1719599343,2,2),('Outside','simple','de','',1719599343,1719599343,2,2),('Outside','simple','en','',1719599343,1719599343,2,2),('Outside','simple','es','',1719599343,1719599343,2,2),('Outside','simple','fr','',1719599343,1719599343,2,2),('Outside','simple','hu','',1719599343,1719599343,2,2),('Outside','simple','it','',1719599343,1719599343,2,2),('Outside','simple','ja','',1719599343,1719599343,2,2),('Outside','simple','nl','',1719599343,1719599343,2,2),('Outside','simple','pl','',1719599343,1719599343,2,2),('Outside','simple','pt','',1719599343,1719599343,2,2),('Outside','simple','ru','',1719599343,1719599343,2,2),('Outside','simple','sk','',1719599343,1719599343,2,2),('Outside','simple','sv','',1719599343,1719599343,2,2),('Outside','simple','th','',1719599343,1719599343,2,2),('Outside','simple','tr','',1719599343,1719599343,2,2),('Outside','simple','uk','',1719599343,1719599343,2,2),('Outside','simple','zh_Hans','',1719599343,1719599343,2,2),('Paper','simple','ca','Paper',1719597028,1719597028,2,2),('Paper','simple','cs','Paper',1719597028,1719597028,2,2),('Paper','simple','de','Paper',1719597028,1719597028,2,2),('Paper','simple','en','Paper',1719597028,1719597028,2,2),('Paper','simple','es','Paper',1719597028,1719597028,2,2),('Paper','simple','fr','Paper',1719597028,1719597028,2,2),('Paper','simple','hu','Paper',1719597028,1719597028,2,2),('Paper','simple','it','Paper',1719597028,1719597028,2,2),('Paper','simple','ja','Paper',1719597028,1719597028,2,2),('Paper','simple','nl','Paper',1719597028,1719597028,2,2),('Paper','simple','pl','Paper',1719597028,1719597028,2,2),('Paper','simple','pt','Paper',1719597028,1719597028,2,2),('Paper','simple','ru','Paper',1719597028,1719597028,2,2),('Paper','simple','sk','Paper',1719597028,1719597028,2,2),('Paper','simple','sv','Paper',1719597028,1719597028,2,2),('Paper','simple','th','Paper',1719597028,1719597028,2,2),('Paper','simple','tr','Paper',1719597028,1719597028,2,2),('Paper','simple','uk','Paper',1719597028,1719597028,2,2),('Paper','simple','zh_Hans','Paper',1719597028,1719597028,2,2),('PaperCartridge','simple','ca','',1719597052,1719597052,2,2),('PaperCartridge','simple','cs','',1719597052,1719597052,2,2),('PaperCartridge','simple','de','',1719597052,1719597052,2,2),('PaperCartridge','simple','en','',1719597052,1719597052,2,2),('PaperCartridge','simple','es','',1719597052,1719597052,2,2),('PaperCartridge','simple','fr','',1719597052,1719597052,2,2),('PaperCartridge','simple','hu','',1719597052,1719597052,2,2),('PaperCartridge','simple','it','',1719597052,1719597052,2,2),('PaperCartridge','simple','ja','',1719597052,1719597052,2,2),('PaperCartridge','simple','nl','',1719597052,1719597052,2,2),('PaperCartridge','simple','pl','',1719597052,1719597052,2,2),('PaperCartridge','simple','pt','',1719597052,1719597052,2,2),('PaperCartridge','simple','ru','',1719597052,1719597052,2,2),('PaperCartridge','simple','sk','',1719597052,1719597052,2,2),('PaperCartridge','simple','sv','',1719597052,1719597052,2,2),('PaperCartridge','simple','th','',1719597052,1719597052,2,2),('PaperCartridge','simple','tr','',1719597052,1719597052,2,2),('PaperCartridge','simple','uk','',1719597052,1719597052,2,2),('PaperCartridge','simple','zh_Hans','',1719597052,1719597052,2,2),('Pimcore\'s logotype','simple','de','',1719596593,1719596593,0,0),('Pimcore\'s logotype','simple','en','',1719596593,1719596593,0,0),('Pimcore\'s logotype','simple','fr','',1719596593,1719596593,0,0),('Pitch','simple','ca','',1719599223,1719599223,2,2),('Pitch','simple','cs','',1719599223,1719599223,2,2),('Pitch','simple','de','',1719599223,1719599223,2,2),('Pitch','simple','en','',1719599223,1719599223,2,2),('Pitch','simple','es','',1719599223,1719599223,2,2),('Pitch','simple','fr','',1719599223,1719599223,2,2),('Pitch','simple','hu','',1719599223,1719599223,2,2),('Pitch','simple','it','',1719599223,1719599223,2,2),('Pitch','simple','ja','',1719599223,1719599223,2,2),('Pitch','simple','nl','',1719599223,1719599223,2,2),('Pitch','simple','pl','',1719599223,1719599223,2,2),('Pitch','simple','pt','',1719599223,1719599223,2,2),('Pitch','simple','ru','',1719599223,1719599223,2,2),('Pitch','simple','sk','',1719599223,1719599223,2,2),('Pitch','simple','sv','',1719599223,1719599223,2,2),('Pitch','simple','th','',1719599223,1719599223,2,2),('Pitch','simple','tr','',1719599223,1719599223,2,2),('Pitch','simple','uk','',1719599223,1719599223,2,2),('Pitch','simple','zh_Hans','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','ca','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','cs','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','de','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','en','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','es','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','fr','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','hu','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','it','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','ja','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','nl','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','pl','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','pt','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','ru','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','sk','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','sv','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','th','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','tr','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','uk','',1719599223,1719599223,2,2),('Priemer bez zavitu','simple','zh_Hans','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','ca','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','cs','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','de','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','en','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','es','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','fr','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','hu','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','it','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','ja','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','nl','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','pl','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','pt','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','ru','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','sk','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','sv','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','th','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','tr','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','uk','',1719599223,1719599223,2,2),('Priemer so zavitom','simple','zh_Hans','',1719599223,1719599223,2,2),('Royal','simple','ca','Royal',1719599482,1719599482,2,2),('Royal','simple','cs','Royal',1719599482,1719599482,2,2),('Royal','simple','de','Royal',1719599482,1719599482,2,2),('Royal','simple','en','Royal',1719599482,1719599482,2,2),('Royal','simple','es','Royal',1719599482,1719599482,2,2),('Royal','simple','fr','Royal',1719599482,1719599482,2,2),('Royal','simple','hu','Royal',1719599482,1719599482,2,2),('Royal','simple','it','Royal',1719599482,1719599482,2,2),('Royal','simple','ja','Royal',1719599482,1719599482,2,2),('Royal','simple','nl','Royal',1719599482,1719599482,2,2),('Royal','simple','pl','Royal',1719599482,1719599482,2,2),('Royal','simple','pt','Royal',1719599482,1719599482,2,2),('Royal','simple','ru','Royal',1719599482,1719599482,2,2),('Royal','simple','sk','Royal',1719599482,1719599482,2,2),('Royal','simple','sv','Royal',1719599482,1719599482,2,2),('Royal','simple','th','Royal',1719599482,1719599482,2,2),('Royal','simple','tr','Royal',1719599482,1719599482,2,2),('Royal','simple','uk','Royal',1719599482,1719599482,2,2),('Royal','simple','zh_Hans','Royal',1719599482,1719599482,2,2),('RoyalFilter','simple','ca','',1719599488,1719599488,2,2),('RoyalFilter','simple','cs','',1719599488,1719599488,2,2),('RoyalFilter','simple','de','',1719599488,1719599488,2,2),('RoyalFilter','simple','en','',1719599488,1719599488,2,2),('RoyalFilter','simple','es','',1719599488,1719599488,2,2),('RoyalFilter','simple','fr','',1719599488,1719599488,2,2),('RoyalFilter','simple','hu','',1719599488,1719599488,2,2),('RoyalFilter','simple','it','',1719599488,1719599488,2,2),('RoyalFilter','simple','ja','',1719599488,1719599488,2,2),('RoyalFilter','simple','nl','',1719599488,1719599488,2,2),('RoyalFilter','simple','pl','',1719599488,1719599488,2,2),('RoyalFilter','simple','pt','',1719599488,1719599488,2,2),('RoyalFilter','simple','ru','',1719599488,1719599488,2,2),('RoyalFilter','simple','sk','',1719599488,1719599488,2,2),('RoyalFilter','simple','sv','',1719599488,1719599488,2,2),('RoyalFilter','simple','th','',1719599488,1719599488,2,2),('RoyalFilter','simple','tr','',1719599488,1719599488,2,2),('RoyalFilter','simple','uk','',1719599488,1719599488,2,2),('RoyalFilter','simple','zh_Hans','',1719599488,1719599488,2,2),('Stupanie zavitu','simple','ca','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','cs','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','de','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','en','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','es','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','fr','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','hu','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','it','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','ja','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','nl','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','pl','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','pt','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','ru','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','sk','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','sv','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','th','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','tr','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','uk','',1719599223,1719599223,2,2),('Stupanie zavitu','simple','zh_Hans','',1719599223,1719599223,2,2),('Thread','simple','ca','',1719599223,1719599223,2,2),('Thread','simple','cs','',1719599223,1719599223,2,2),('Thread','simple','de','',1719599223,1719599223,2,2),('Thread','simple','en','',1719599223,1719599223,2,2),('Thread','simple','es','',1719599223,1719599223,2,2),('Thread','simple','fr','',1719599223,1719599223,2,2),('Thread','simple','hu','',1719599223,1719599223,2,2),('Thread','simple','it','',1719599223,1719599223,2,2),('Thread','simple','ja','',1719599223,1719599223,2,2),('Thread','simple','nl','',1719599223,1719599223,2,2),('Thread','simple','pl','',1719599223,1719599223,2,2),('Thread','simple','pt','',1719599223,1719599223,2,2),('Thread','simple','ru','',1719599223,1719599223,2,2),('Thread','simple','sk','',1719599223,1719599223,2,2),('Thread','simple','sv','',1719599223,1719599223,2,2),('Thread','simple','th','',1719599223,1719599223,2,2),('Thread','simple','tr','',1719599223,1719599223,2,2),('Thread','simple','uk','',1719599223,1719599223,2,2),('Thread','simple','zh_Hans','',1719599223,1719599223,2,2),('Thread Position','simple','ca','',1719599343,1719599343,2,2),('Thread Position','simple','cs','',1719599343,1719599343,2,2),('Thread Position','simple','de','',1719599343,1719599343,2,2),('Thread Position','simple','en','',1719599343,1719599343,2,2),('Thread Position','simple','es','',1719599343,1719599343,2,2),('Thread Position','simple','fr','',1719599343,1719599343,2,2),('Thread Position','simple','hu','',1719599343,1719599343,2,2),('Thread Position','simple','it','',1719599343,1719599343,2,2),('Thread Position','simple','ja','',1719599343,1719599343,2,2),('Thread Position','simple','nl','',1719599343,1719599343,2,2),('Thread Position','simple','pl','',1719599343,1719599343,2,2),('Thread Position','simple','pt','',1719599343,1719599343,2,2),('Thread Position','simple','ru','',1719599343,1719599343,2,2),('Thread Position','simple','sk','',1719599343,1719599343,2,2),('Thread Position','simple','sv','',1719599343,1719599343,2,2),('Thread Position','simple','th','',1719599343,1719599343,2,2),('Thread Position','simple','tr','',1719599343,1719599343,2,2),('Thread Position','simple','uk','',1719599343,1719599343,2,2),('Thread Position','simple','zh_Hans','',1719599343,1719599343,2,2),('Thread images','simple','ca','',1719599343,1719599343,2,2),('Thread images','simple','cs','',1719599343,1719599343,2,2),('Thread images','simple','de','',1719599343,1719599343,2,2),('Thread images','simple','en','',1719599343,1719599343,2,2),('Thread images','simple','es','',1719599343,1719599343,2,2),('Thread images','simple','fr','',1719599343,1719599343,2,2),('Thread images','simple','hu','',1719599343,1719599343,2,2),('Thread images','simple','it','',1719599343,1719599343,2,2),('Thread images','simple','ja','',1719599343,1719599343,2,2),('Thread images','simple','nl','',1719599343,1719599343,2,2),('Thread images','simple','pl','',1719599343,1719599343,2,2),('Thread images','simple','pt','',1719599343,1719599343,2,2),('Thread images','simple','ru','',1719599343,1719599343,2,2),('Thread images','simple','sk','',1719599343,1719599343,2,2),('Thread images','simple','sv','',1719599343,1719599343,2,2),('Thread images','simple','th','',1719599343,1719599343,2,2),('Thread images','simple','tr','',1719599343,1719599343,2,2),('Thread images','simple','uk','',1719599343,1719599343,2,2),('Thread images','simple','zh_Hans','',1719599343,1719599343,2,2),('Top','simple','ca','',1719599223,1719599223,2,2),('Top','simple','cs','',1719599223,1719599223,2,2),('Top','simple','de','',1719599223,1719599223,2,2),('Top','simple','en','',1719599223,1719599223,2,2),('Top','simple','es','',1719599223,1719599223,2,2),('Top','simple','fr','',1719599223,1719599223,2,2),('Top','simple','hu','',1719599223,1719599223,2,2),('Top','simple','it','',1719599223,1719599223,2,2),('Top','simple','ja','',1719599223,1719599223,2,2),('Top','simple','nl','',1719599223,1719599223,2,2),('Top','simple','pl','',1719599223,1719599223,2,2),('Top','simple','pt','',1719599223,1719599223,2,2),('Top','simple','ru','',1719599223,1719599223,2,2),('Top','simple','sk','',1719599223,1719599223,2,2),('Top','simple','sv','',1719599223,1719599223,2,2),('Top','simple','th','',1719599223,1719599223,2,2),('Top','simple','tr','',1719599223,1719599223,2,2),('Top','simple','uk','',1719599223,1719599223,2,2),('Top','simple','zh_Hans','',1719599223,1719599223,2,2),('bottom','simple','ca','',1719598792,1719598792,2,2),('bottom','simple','cs','',1719598792,1719598792,2,2),('bottom','simple','de','',1719598792,1719598792,2,2),('bottom','simple','en','',1719598792,1719598792,2,2),('bottom','simple','es','',1719598792,1719598792,2,2),('bottom','simple','fr','',1719598792,1719598792,2,2),('bottom','simple','hu','',1719598792,1719598792,2,2),('bottom','simple','it','',1719598792,1719598792,2,2),('bottom','simple','ja','',1719598792,1719598792,2,2),('bottom','simple','nl','',1719598792,1719598792,2,2),('bottom','simple','pl','',1719598792,1719598792,2,2),('bottom','simple','pt','',1719598792,1719598792,2,2),('bottom','simple','ru','',1719598792,1719598792,2,2),('bottom','simple','sk','',1719598792,1719598792,2,2),('bottom','simple','sv','',1719598792,1719598792,2,2),('bottom','simple','th','',1719598792,1719598792,2,2),('bottom','simple','tr','',1719598792,1719598792,2,2),('bottom','simple','uk','',1719598792,1719598792,2,2),('bottom','simple','zh_Hans','',1719598792,1719598792,2,2),('down','simple','ca','',1719598983,1719598983,2,2),('down','simple','cs','',1719598983,1719598983,2,2),('down','simple','de','',1719598983,1719598983,2,2),('down','simple','en','',1719598983,1719598983,2,2),('down','simple','es','',1719598983,1719598983,2,2),('down','simple','fr','',1719598983,1719598983,2,2),('down','simple','hu','',1719598983,1719598983,2,2),('down','simple','it','',1719598983,1719598983,2,2),('down','simple','ja','',1719598983,1719598983,2,2),('down','simple','nl','',1719598983,1719598983,2,2),('down','simple','pl','',1719598983,1719598983,2,2),('down','simple','pt','',1719598983,1719598983,2,2),('down','simple','ru','',1719598983,1719598983,2,2),('down','simple','sk','',1719598983,1719598983,2,2),('down','simple','sv','',1719598983,1719598983,2,2),('down','simple','th','',1719598983,1719598983,2,2),('down','simple','tr','',1719598983,1719598983,2,2),('down','simple','uk','',1719598983,1719598983,2,2),('down','simple','zh_Hans','',1719598983,1719598983,2,2),('global','simple','ca','',1719597052,1719597052,2,2),('global','simple','cs','',1719597052,1719597052,2,2),('global','simple','de','',1719597052,1719597052,2,2),('global','simple','en','',1719597052,1719597052,2,2),('global','simple','es','',1719597052,1719597052,2,2),('global','simple','fr','',1719597052,1719597052,2,2),('global','simple','hu','',1719597052,1719597052,2,2),('global','simple','it','',1719597052,1719597052,2,2),('global','simple','ja','',1719597052,1719597052,2,2),('global','simple','nl','',1719597052,1719597052,2,2),('global','simple','pl','',1719597052,1719597052,2,2),('global','simple','pt','',1719597052,1719597052,2,2),('global','simple','ru','',1719597052,1719597052,2,2),('global','simple','sk','',1719597052,1719597052,2,2),('global','simple','sv','',1719597052,1719597052,2,2),('global','simple','th','',1719597052,1719597052,2,2),('global','simple','tr','',1719597052,1719597052,2,2),('global','simple','uk','',1719597052,1719597052,2,2),('global','simple','zh_Hans','',1719597052,1719597052,2,2),('ignoreCase','simple','ca','',1719597052,1719597052,2,2),('ignoreCase','simple','cs','',1719597052,1719597052,2,2),('ignoreCase','simple','de','',1719597052,1719597052,2,2),('ignoreCase','simple','en','',1719597052,1719597052,2,2),('ignoreCase','simple','es','',1719597052,1719597052,2,2),('ignoreCase','simple','fr','',1719597052,1719597052,2,2),('ignoreCase','simple','hu','',1719597052,1719597052,2,2),('ignoreCase','simple','it','',1719597052,1719597052,2,2),('ignoreCase','simple','ja','',1719597052,1719597052,2,2),('ignoreCase','simple','nl','',1719597052,1719597052,2,2),('ignoreCase','simple','pl','',1719597052,1719597052,2,2),('ignoreCase','simple','pt','',1719597052,1719597052,2,2),('ignoreCase','simple','ru','',1719597052,1719597052,2,2),('ignoreCase','simple','sk','',1719597052,1719597052,2,2),('ignoreCase','simple','sv','',1719597052,1719597052,2,2),('ignoreCase','simple','th','',1719597052,1719597052,2,2),('ignoreCase','simple','tr','',1719597052,1719597052,2,2),('ignoreCase','simple','uk','',1719597052,1719597052,2,2),('ignoreCase','simple','zh_Hans','',1719597052,1719597052,2,2),('login','simple','de','',1719596593,1719596593,0,0),('login','simple','en','',1719596593,1719596593,0,0),('login','simple','fr','',1719596593,1719596593,0,0),('multiline','simple','ca','',1719597052,1719597052,2,2),('multiline','simple','cs','',1719597052,1719597052,2,2),('multiline','simple','de','',1719597052,1719597052,2,2),('multiline','simple','en','',1719597052,1719597052,2,2),('multiline','simple','es','',1719597052,1719597052,2,2),('multiline','simple','fr','',1719597052,1719597052,2,2),('multiline','simple','hu','',1719597052,1719597052,2,2),('multiline','simple','it','',1719597052,1719597052,2,2),('multiline','simple','ja','',1719597052,1719597052,2,2),('multiline','simple','nl','',1719597052,1719597052,2,2),('multiline','simple','pl','',1719597052,1719597052,2,2),('multiline','simple','pt','',1719597052,1719597052,2,2),('multiline','simple','ru','',1719597052,1719597052,2,2),('multiline','simple','sk','',1719597052,1719597052,2,2),('multiline','simple','sv','',1719597052,1719597052,2,2),('multiline','simple','th','',1719597052,1719597052,2,2),('multiline','simple','tr','',1719597052,1719597052,2,2),('multiline','simple','uk','',1719597052,1719597052,2,2),('multiline','simple','zh_Hans','',1719597052,1719597052,2,2),('object_add_dialog_custom_text.Company','simple','ca','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','cs','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','de','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','en','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','es','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','fr','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','hu','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','it','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','ja','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','nl','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','pl','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','pt','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','ru','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','sk','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','sv','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','th','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','tr','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','uk','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.Company','simple','zh_Hans','',1719599013,1719599013,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','ca','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','cs','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','de','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','en','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','es','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','fr','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','hu','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','it','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','ja','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','nl','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','pl','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','pt','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','ru','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','sk','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','sv','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','th','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','tr','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','uk','',1719597142,1719597142,2,2),('object_add_dialog_custom_text.PaperCartridge','simple','zh_Hans','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.Company','simple','ca','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','cs','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','de','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','en','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','es','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','fr','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','hu','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','it','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','ja','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','nl','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','pl','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','pt','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','ru','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','sk','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','sv','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','th','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','tr','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','uk','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.Company','simple','zh_Hans','',1719599013,1719599013,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','ca','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','cs','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','de','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','en','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','es','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','fr','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','hu','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','it','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','ja','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','nl','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','pl','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','pt','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','ru','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','sk','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','sv','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','th','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','tr','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','uk','',1719597142,1719597142,2,2),('object_add_dialog_custom_title.PaperCartridge','simple','zh_Hans','',1719597142,1719597142,2,2),('paperCartridgeCodes','simple','ca','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','cs','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','de','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','en','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','es','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','fr','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','hu','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','it','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','ja','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','nl','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','pl','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','pt','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','ru','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','sk','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','sv','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','th','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','tr','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','uk','',1719598732,1719598732,2,2),('paperCartridgeCodes','simple','zh_Hans','',1719598732,1719598732,2,2),('sticky','simple','ca','',1719597052,1719597052,2,2),('sticky','simple','cs','',1719597052,1719597052,2,2),('sticky','simple','de','',1719597052,1719597052,2,2),('sticky','simple','en','',1719597052,1719597052,2,2),('sticky','simple','es','',1719597052,1719597052,2,2),('sticky','simple','fr','',1719597052,1719597052,2,2),('sticky','simple','hu','',1719597052,1719597052,2,2),('sticky','simple','it','',1719597052,1719597052,2,2),('sticky','simple','ja','',1719597052,1719597052,2,2),('sticky','simple','nl','',1719597052,1719597052,2,2),('sticky','simple','pl','',1719597052,1719597052,2,2),('sticky','simple','pt','',1719597052,1719597052,2,2),('sticky','simple','ru','',1719597052,1719597052,2,2),('sticky','simple','sk','',1719597052,1719597052,2,2),('sticky','simple','sv','',1719597052,1719597052,2,2),('sticky','simple','th','',1719597052,1719597052,2,2),('sticky','simple','tr','',1719597052,1719597052,2,2),('sticky','simple','uk','',1719597052,1719597052,2,2),('sticky','simple','zh_Hans','',1719597052,1719597052,2,2),('unicode','simple','ca','',1719597052,1719597052,2,2),('unicode','simple','cs','',1719597052,1719597052,2,2),('unicode','simple','de','',1719597052,1719597052,2,2),('unicode','simple','en','',1719597052,1719597052,2,2),('unicode','simple','es','',1719597052,1719597052,2,2),('unicode','simple','fr','',1719597052,1719597052,2,2),('unicode','simple','hu','',1719597052,1719597052,2,2),('unicode','simple','it','',1719597052,1719597052,2,2),('unicode','simple','ja','',1719597052,1719597052,2,2),('unicode','simple','nl','',1719597052,1719597052,2,2),('unicode','simple','pl','',1719597052,1719597052,2,2),('unicode','simple','pt','',1719597052,1719597052,2,2),('unicode','simple','ru','',1719597052,1719597052,2,2),('unicode','simple','sk','',1719597052,1719597052,2,2),('unicode','simple','sv','',1719597052,1719597052,2,2),('unicode','simple','th','',1719597052,1719597052,2,2),('unicode','simple','tr','',1719597052,1719597052,2,2),('unicode','simple','uk','',1719597052,1719597052,2,2),('unicode','simple','zh_Hans','',1719597052,1719597052,2,2),('up','simple','ca','',1719598983,1719598983,2,2),('up','simple','cs','',1719598983,1719598983,2,2),('up','simple','de','',1719598983,1719598983,2,2),('up','simple','en','',1719598983,1719598983,2,2),('up','simple','es','',1719598983,1719598983,2,2),('up','simple','fr','',1719598983,1719598983,2,2),('up','simple','hu','',1719598983,1719598983,2,2),('up','simple','it','',1719598983,1719598983,2,2),('up','simple','ja','',1719598983,1719598983,2,2),('up','simple','nl','',1719598983,1719598983,2,2),('up','simple','pl','',1719598983,1719598983,2,2),('up','simple','pt','',1719598983,1719598983,2,2),('up','simple','ru','',1719598983,1719598983,2,2),('up','simple','sk','',1719598983,1719598983,2,2),('up','simple','sv','',1719598983,1719598983,2,2),('up','simple','th','',1719598983,1719598983,2,2),('up','simple','tr','',1719598983,1719598983,2,2),('up','simple','uk','',1719598983,1719598983,2,2),('up','simple','zh_Hans','',1719598983,1719598983,2,2);
/*!40000 ALTER TABLE `translations_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `translations_messages`
--

DROP TABLE IF EXISTS `translations_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `translations_messages` (
  `key` varchar(190) CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL DEFAULT '',
  `type` varchar(10) DEFAULT NULL,
  `language` varchar(10) NOT NULL DEFAULT '',
  `text` text,
  `creationDate` int unsigned DEFAULT NULL,
  `modificationDate` int unsigned DEFAULT NULL,
  `userOwner` int unsigned DEFAULT NULL,
  `userModification` int unsigned DEFAULT NULL,
  PRIMARY KEY (`key`,`language`),
  KEY `language` (`language`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `translations_messages`
--

LOCK TABLES `translations_messages` WRITE;
/*!40000 ALTER TABLE `translations_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `translations_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tree_locks`
--

DROP TABLE IF EXISTS `tree_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tree_locks` (
  `id` int NOT NULL DEFAULT '0',
  `type` enum('asset','document','object') NOT NULL DEFAULT 'asset',
  `locked` enum('self','propagate') DEFAULT NULL,
  PRIMARY KEY (`id`,`type`),
  KEY `type` (`type`),
  KEY `locked` (`locked`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tree_locks`
--

LOCK TABLES `tree_locks` WRITE;
/*!40000 ALTER TABLE `tree_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `tree_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `parentId` int unsigned DEFAULT NULL,
  `type` enum('user','userfolder','role','rolefolder') NOT NULL DEFAULT 'user',
  `name` varchar(50) DEFAULT NULL,
  `password` varchar(190) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL,
  `lastname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `language` varchar(10) DEFAULT NULL,
  `contentLanguages` longtext,
  `admin` tinyint unsigned DEFAULT '0',
  `active` tinyint unsigned DEFAULT '1',
  `permissions` text,
  `roles` varchar(1000) DEFAULT NULL,
  `welcomescreen` tinyint(1) DEFAULT NULL,
  `closeWarning` tinyint(1) DEFAULT NULL,
  `memorizeTabs` tinyint(1) DEFAULT NULL,
  `allowDirtyClose` tinyint unsigned DEFAULT '1',
  `docTypes` text,
  `classes` text,
  `twoFactorAuthentication` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `activePerspective` varchar(255) DEFAULT NULL,
  `perspectives` longtext,
  `websiteTranslationLanguagesEdit` longtext,
  `websiteTranslationLanguagesView` longtext,
  `lastLogin` int unsigned DEFAULT NULL,
  `keyBindings` json DEFAULT NULL,
  `passwordRecoveryToken` varchar(290) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `type_name` (`type`,`name`),
  KEY `parentId` (`parentId`),
  KEY `name` (`name`),
  KEY `password` (`password`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (0,0,'user','system',NULL,NULL,NULL,NULL,NULL,NULL,1,1,NULL,NULL,NULL,NULL,NULL,1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,0,'user','admin','$2y$10$2no7zwO8rwZ1Wl63ibH4tOEO6YGyeiwIUf.vhlXIzJ3EylLqWquN2',NULL,NULL,NULL,'en','',1,1,'','',0,0,0,1,'','','null',NULL,NULL,'','','',NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_permission_definitions`
--

DROP TABLE IF EXISTS `users_permission_definitions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_permission_definitions` (
  `key` varchar(50) NOT NULL DEFAULT '',
  `category` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_permission_definitions`
--

LOCK TABLES `users_permission_definitions` WRITE;
/*!40000 ALTER TABLE `users_permission_definitions` DISABLE KEYS */;
INSERT INTO `users_permission_definitions` VALUES ('admin_translations','Pimcore Admin Bundle'),('asset_metadata',''),('assets',''),('classes',''),('classificationstore',''),('clear_cache',''),('clear_fullpage_cache',''),('clear_temp_files',''),('dashboards',''),('document_types',''),('documents',''),('emails',''),('fieldcollections',''),('gdpr_data_extractor','Pimcore Admin Bundle'),('notes_events',''),('notifications',''),('notifications_send',''),('objectbricks',''),('objects',''),('objects_sort_method',''),('predefined_properties',''),('quantityValueUnits',''),('recyclebin',''),('redirects',''),('routes','Pimcore Static Routes Bundle'),('seemode',''),('selectoptions',''),('share_configurations',''),('sites',''),('system_appearance_settings','Pimcore Admin Bundle'),('system_settings',''),('tags_assignment',''),('tags_configuration',''),('tags_search',''),('thumbnails',''),('translations',''),('users',''),('website_settings',''),('workflow_details','');
/*!40000 ALTER TABLE `users_permission_definitions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_workspaces_asset`
--

DROP TABLE IF EXISTS `users_workspaces_asset`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_workspaces_asset` (
  `cid` int unsigned NOT NULL DEFAULT '0',
  `cpath` varchar(765) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `userId` int unsigned NOT NULL DEFAULT '0',
  `list` tinyint(1) DEFAULT '0',
  `view` tinyint(1) DEFAULT '0',
  `publish` tinyint(1) DEFAULT '0',
  `delete` tinyint(1) DEFAULT '0',
  `rename` tinyint(1) DEFAULT '0',
  `create` tinyint(1) DEFAULT '0',
  `settings` tinyint(1) DEFAULT '0',
  `versions` tinyint(1) DEFAULT '0',
  `properties` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`cid`,`userId`),
  UNIQUE KEY `cpath_userId` (`cpath`,`userId`),
  KEY `userId` (`userId`),
  CONSTRAINT `fk_users_workspaces_asset_assets` FOREIGN KEY (`cid`) REFERENCES `assets` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_users_workspaces_asset_users` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_workspaces_asset`
--

LOCK TABLES `users_workspaces_asset` WRITE;
/*!40000 ALTER TABLE `users_workspaces_asset` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_workspaces_asset` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_workspaces_document`
--

DROP TABLE IF EXISTS `users_workspaces_document`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_workspaces_document` (
  `cid` int unsigned NOT NULL DEFAULT '0',
  `cpath` varchar(765) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `userId` int unsigned NOT NULL DEFAULT '0',
  `list` tinyint unsigned DEFAULT '0',
  `view` tinyint unsigned DEFAULT '0',
  `save` tinyint unsigned DEFAULT '0',
  `publish` tinyint unsigned DEFAULT '0',
  `unpublish` tinyint unsigned DEFAULT '0',
  `delete` tinyint unsigned DEFAULT '0',
  `rename` tinyint unsigned DEFAULT '0',
  `create` tinyint unsigned DEFAULT '0',
  `settings` tinyint unsigned DEFAULT '0',
  `versions` tinyint unsigned DEFAULT '0',
  `properties` tinyint unsigned DEFAULT '0',
  PRIMARY KEY (`cid`,`userId`),
  UNIQUE KEY `cpath_userId` (`cpath`,`userId`),
  KEY `userId` (`userId`),
  CONSTRAINT `fk_users_workspaces_document_documents` FOREIGN KEY (`cid`) REFERENCES `documents` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_users_workspaces_document_users` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_workspaces_document`
--

LOCK TABLES `users_workspaces_document` WRITE;
/*!40000 ALTER TABLE `users_workspaces_document` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_workspaces_document` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users_workspaces_object`
--

DROP TABLE IF EXISTS `users_workspaces_object`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users_workspaces_object` (
  `cid` int unsigned NOT NULL DEFAULT '0',
  `cpath` varchar(765) CHARACTER SET utf8mb3 COLLATE utf8mb3_bin DEFAULT NULL,
  `userId` int unsigned NOT NULL DEFAULT '0',
  `list` tinyint unsigned DEFAULT '0',
  `view` tinyint unsigned DEFAULT '0',
  `save` tinyint unsigned DEFAULT '0',
  `publish` tinyint unsigned DEFAULT '0',
  `unpublish` tinyint unsigned DEFAULT '0',
  `delete` tinyint unsigned DEFAULT '0',
  `rename` tinyint unsigned DEFAULT '0',
  `create` tinyint unsigned DEFAULT '0',
  `settings` tinyint unsigned DEFAULT '0',
  `versions` tinyint unsigned DEFAULT '0',
  `properties` tinyint unsigned DEFAULT '0',
  `lEdit` text,
  `lView` text,
  `layouts` text,
  PRIMARY KEY (`cid`,`userId`),
  UNIQUE KEY `cpath_userId` (`cpath`,`userId`),
  KEY `userId` (`userId`),
  CONSTRAINT `fk_users_workspaces_object_objects` FOREIGN KEY (`cid`) REFERENCES `objects` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_users_workspaces_object_users` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci ROW_FORMAT=DYNAMIC;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users_workspaces_object`
--

LOCK TABLES `users_workspaces_object` WRITE;
/*!40000 ALTER TABLE `users_workspaces_object` DISABLE KEYS */;
/*!40000 ALTER TABLE `users_workspaces_object` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `uuids`
--

DROP TABLE IF EXISTS `uuids`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `uuids` (
  `uuid` char(36) NOT NULL,
  `itemId` varchar(50) NOT NULL,
  `type` varchar(25) NOT NULL,
  `instanceIdentifier` varchar(50) NOT NULL,
  PRIMARY KEY (`uuid`,`itemId`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `uuids`
--

LOCK TABLES `uuids` WRITE;
/*!40000 ALTER TABLE `uuids` DISABLE KEYS */;
/*!40000 ALTER TABLE `uuids` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `versions`
--

DROP TABLE IF EXISTS `versions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `versions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `cid` int unsigned DEFAULT NULL,
  `ctype` enum('document','asset','object') DEFAULT NULL,
  `userId` int unsigned DEFAULT NULL,
  `note` text,
  `stackTrace` text,
  `date` int unsigned DEFAULT NULL,
  `public` tinyint unsigned NOT NULL DEFAULT '0',
  `serialized` tinyint unsigned DEFAULT '0',
  `versionCount` int unsigned NOT NULL DEFAULT '0',
  `binaryFileHash` varchar(128) CHARACTER SET ascii COLLATE ascii_general_ci DEFAULT NULL,
  `binaryFileId` bigint unsigned DEFAULT NULL,
  `autoSave` tinyint NOT NULL DEFAULT '0',
  `storageType` varchar(5) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cid` (`cid`),
  KEY `ctype_cid` (`ctype`,`cid`),
  KEY `date` (`date`),
  KEY `binaryFileHash` (`binaryFileHash`),
  KEY `autoSave` (`autoSave`),
  KEY `stackTrace` (`stackTrace`(1))
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `versions`
--

LOCK TABLES `versions` WRITE;
/*!40000 ALTER TABLE `versions` DISABLE KEYS */;
INSERT INTO `versions` VALUES (1,3,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(false, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(867): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->addAction(Object(Symfony\\Component\\HttpFoundation\\Request), Object(Pimcore\\Model\\Factory))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719597137,0,1,1,NULL,NULL,0,'fs'),(3,3,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(true, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(1402): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->saveAction(Object(Symfony\\Component\\HttpFoundation\\Request))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719597160,0,1,3,NULL,NULL,0,'fs'),(4,3,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(true, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(1363): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->saveAction(Object(Symfony\\Component\\HttpFoundation\\Request))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719597163,0,1,4,NULL,NULL,0,'fs'),(6,3,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(true, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(1363): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->saveAction(Object(Symfony\\Component\\HttpFoundation\\Request))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719598771,0,1,6,NULL,NULL,0,'fs'),(8,3,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(true, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(1363): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->saveAction(Object(Symfony\\Component\\HttpFoundation\\Request))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719598852,0,1,8,NULL,NULL,0,'fs'),(9,5,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(false, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(867): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->addAction(Object(Symfony\\Component\\HttpFoundation\\Request), Object(Pimcore\\Model\\Factory))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719599005,0,1,1,NULL,NULL,0,'fs'),(10,5,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(true, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(1402): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->saveAction(Object(Symfony\\Component\\HttpFoundation\\Request))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719599007,0,1,2,NULL,NULL,0,'fs'),(11,5,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(true, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(1363): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->saveAction(Object(Symfony\\Component\\HttpFoundation\\Request))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719599009,0,1,3,NULL,NULL,0,'fs'),(13,3,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(true, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(1363): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->saveAction(Object(Symfony\\Component\\HttpFoundation\\Request))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719599014,0,1,10,NULL,NULL,0,'fs'),(14,6,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(false, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(867): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->addAction(Object(Symfony\\Component\\HttpFoundation\\Request), Object(Pimcore\\Model\\Factory))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719599043,0,1,1,NULL,NULL,0,'fs'),(16,6,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(true, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(1363): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->saveAction(Object(Symfony\\Component\\HttpFoundation\\Request))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719599047,0,1,3,NULL,NULL,0,'fs'),(18,3,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, false, true, false)\n#2 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(217): Pimcore\\Model\\DataObject\\Concrete->saveVersion(false, false, NULL)\n#3 /var/www/html/vendor/pimcore/pimcore/models/DataObject/AbstractObject.php(562): Pimcore\\Model\\DataObject\\Concrete->update(true, Array)\n#4 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(667): Pimcore\\Model\\DataObject\\AbstractObject->save(Array)\n#5 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(1363): Pimcore\\Model\\DataObject\\Concrete->save()\n#6 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->saveAction(Object(Symfony\\Component\\HttpFoundation\\Request))\n#7 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#8 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#9 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#10 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#11 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#12 {main}',1719599220,0,1,12,NULL,NULL,0,'fs'),(19,3,'object',2,'','#0 /var/www/html/vendor/pimcore/pimcore/models/Element/AbstractElement.php(596): Pimcore\\Model\\Version->save()\n#1 /var/www/html/vendor/pimcore/pimcore/models/DataObject/Concrete.php(279): Pimcore\\Model\\Element\\AbstractElement->doSaveVersion(NULL, true, true, true)\n#2 /var/www/html/vendor/pimcore/admin-ui-classic-bundle/src/Controller/Admin/DataObject/DataObjectController.php(1395): Pimcore\\Model\\DataObject\\Concrete->saveVersion(true, true, NULL, true)\n#3 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(181): Pimcore\\Bundle\\AdminBundle\\Controller\\Admin\\DataObject\\DataObjectController->saveAction(Object(Symfony\\Component\\HttpFoundation\\Request))\n#4 /var/www/html/vendor/symfony/http-kernel/HttpKernel.php(76): Symfony\\Component\\HttpKernel\\HttpKernel->handleRaw(Object(Symfony\\Component\\HttpFoundation\\Request), 1)\n#5 /var/www/html/vendor/symfony/http-kernel/Kernel.php(197): Symfony\\Component\\HttpKernel\\HttpKernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request), 1, true)\n#6 /var/www/html/vendor/symfony/runtime/Runner/Symfony/HttpKernelRunner.php(35): Symfony\\Component\\HttpKernel\\Kernel->handle(Object(Symfony\\Component\\HttpFoundation\\Request))\n#7 /var/www/html/vendor/autoload_runtime.php(29): Symfony\\Component\\Runtime\\Runner\\Symfony\\HttpKernelRunner->run()\n#8 /var/www/html/public/index.php(19): require_once(\'/var/www/html/v...\')\n#9 {main}',1719599242,0,1,14,NULL,NULL,1,'fs');
/*!40000 ALTER TABLE `versions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `webdav_locks`
--

DROP TABLE IF EXISTS `webdav_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `webdav_locks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `owner` varchar(100) DEFAULT NULL,
  `timeout` int unsigned DEFAULT NULL,
  `created` int DEFAULT NULL,
  `token` varbinary(100) DEFAULT NULL,
  `scope` tinyint DEFAULT NULL,
  `depth` tinyint DEFAULT NULL,
  `uri` varbinary(1000) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `token` (`token`),
  KEY `uri` (`uri`(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `webdav_locks`
--

LOCK TABLES `webdav_locks` WRITE;
/*!40000 ALTER TABLE `webdav_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `webdav_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `website_settings`
--

DROP TABLE IF EXISTS `website_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `website_settings` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(190) NOT NULL DEFAULT '',
  `type` enum('text','document','asset','object','bool') DEFAULT NULL,
  `data` text,
  `language` varchar(15) NOT NULL DEFAULT '',
  `siteId` int unsigned DEFAULT NULL,
  `creationDate` int unsigned DEFAULT '0',
  `modificationDate` int unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `name` (`name`),
  KEY `siteId` (`siteId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `website_settings`
--

LOCK TABLES `website_settings` WRITE;
/*!40000 ALTER TABLE `website_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `website_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Final view structure for view `object_company`
--

/*!50001 DROP VIEW IF EXISTS `object_company`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`pimcore`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `object_company` AS select `object_query_company`.`oo_id` AS `oo_id`,`object_query_company`.`oo_classId` AS `oo_classId`,`object_query_company`.`oo_className` AS `oo_className`,`object_query_company`.`name` AS `name`,`objects`.`id` AS `id`,`objects`.`parentId` AS `parentId`,`objects`.`type` AS `type`,`objects`.`key` AS `key`,`objects`.`path` AS `path`,`objects`.`index` AS `index`,`objects`.`published` AS `published`,`objects`.`creationDate` AS `creationDate`,`objects`.`modificationDate` AS `modificationDate`,`objects`.`userOwner` AS `userOwner`,`objects`.`userModification` AS `userModification`,`objects`.`classId` AS `classId`,`objects`.`className` AS `className`,`objects`.`childrenSortBy` AS `childrenSortBy`,`objects`.`childrenSortOrder` AS `childrenSortOrder`,`objects`.`versionCount` AS `versionCount` from (`object_query_company` join `objects` on((`objects`.`id` = `object_query_company`.`oo_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `object_paper_cartridge`
--

/*!50001 DROP VIEW IF EXISTS `object_paper_cartridge`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`pimcore`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `object_paper_cartridge` AS select `object_query_paper_cartridge`.`oo_id` AS `oo_id`,`object_query_paper_cartridge`.`oo_classId` AS `oo_classId`,`object_query_paper_cartridge`.`oo_className` AS `oo_className`,`object_query_paper_cartridge`.`name` AS `name`,`object_query_paper_cartridge`.`images__images` AS `images__images`,`object_query_paper_cartridge`.`images__hotspots` AS `images__hotspots`,`object_query_paper_cartridge`.`diameter` AS `diameter`,`object_query_paper_cartridge`.`length` AS `length`,`object_query_paper_cartridge`.`companies` AS `companies`,`object_query_paper_cartridge`.`bottom` AS `bottom`,`object_query_paper_cartridge`.`diameterWithThread` AS `diameterWithThread`,`object_query_paper_cartridge`.`diameterWithoutThread` AS `diameterWithoutThread`,`object_query_paper_cartridge`.`pitch` AS `pitch`,`object_query_paper_cartridge`.`threadImages__images` AS `threadImages__images`,`object_query_paper_cartridge`.`threadImages__hotspots` AS `threadImages__hotspots`,`object_query_paper_cartridge`.`threadPosition` AS `threadPosition`,`object_query_paper_cartridge`.`top` AS `top`,`objects`.`id` AS `id`,`objects`.`parentId` AS `parentId`,`objects`.`type` AS `type`,`objects`.`key` AS `key`,`objects`.`path` AS `path`,`objects`.`index` AS `index`,`objects`.`published` AS `published`,`objects`.`creationDate` AS `creationDate`,`objects`.`modificationDate` AS `modificationDate`,`objects`.`userOwner` AS `userOwner`,`objects`.`userModification` AS `userModification`,`objects`.`classId` AS `classId`,`objects`.`className` AS `className`,`objects`.`childrenSortBy` AS `childrenSortBy`,`objects`.`childrenSortOrder` AS `childrenSortOrder`,`objects`.`versionCount` AS `versionCount` from (`object_query_paper_cartridge` join `objects` on((`objects`.`id` = `object_query_paper_cartridge`.`oo_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;

--
-- Final view structure for view `object_royal_filter`
--

/*!50001 DROP VIEW IF EXISTS `object_royal_filter`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`pimcore`@`%` SQL SECURITY DEFINER */
/*!50001 VIEW `object_royal_filter` AS select `object_query_royal_filter`.`oo_id` AS `oo_id`,`object_query_royal_filter`.`oo_classId` AS `oo_classId`,`object_query_royal_filter`.`oo_className` AS `oo_className`,`objects`.`id` AS `id`,`objects`.`parentId` AS `parentId`,`objects`.`type` AS `type`,`objects`.`key` AS `key`,`objects`.`path` AS `path`,`objects`.`index` AS `index`,`objects`.`published` AS `published`,`objects`.`creationDate` AS `creationDate`,`objects`.`modificationDate` AS `modificationDate`,`objects`.`userOwner` AS `userOwner`,`objects`.`userModification` AS `userModification`,`objects`.`classId` AS `classId`,`objects`.`className` AS `className`,`objects`.`childrenSortBy` AS `childrenSortBy`,`objects`.`childrenSortOrder` AS `childrenSortOrder`,`objects`.`versionCount` AS `versionCount` from (`object_query_royal_filter` join `objects` on((`objects`.`id` = `object_query_royal_filter`.`oo_id`))) */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-06-29  9:57:21
