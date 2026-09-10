-- MySQL dump 10.13  Distrib 8.0.46, for Linux (x86_64)
--
-- Host: localhost    Database: wordpress
-- ------------------------------------------------------
-- Server version	8.0.46

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
-- Table structure for table `wp_commentmeta`
--

DROP TABLE IF EXISTS `wp_commentmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `comment_id` bigint unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `comment_id` (`comment_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_commentmeta`
--

LOCK TABLES `wp_commentmeta` WRITE;
/*!40000 ALTER TABLE `wp_commentmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_commentmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_comments`
--

DROP TABLE IF EXISTS `wp_comments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_comments` (
  `comment_ID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `comment_post_ID` bigint unsigned NOT NULL DEFAULT '0',
  `comment_author` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_author_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_karma` int NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'comment',
  `comment_parent` bigint unsigned NOT NULL DEFAULT '0',
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`comment_ID`),
  KEY `comment_post_ID` (`comment_post_ID`),
  KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  KEY `comment_date_gmt` (`comment_date_gmt`),
  KEY `comment_parent` (`comment_parent`),
  KEY `comment_author_email` (`comment_author_email`(10))
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_comments`
--

LOCK TABLES `wp_comments` WRITE;
/*!40000 ALTER TABLE `wp_comments` DISABLE KEYS */;
INSERT INTO `wp_comments` VALUES (1,1,'A WordPress Commenter','wapuu@wordpress.example','https://wordpress.org/','','2026-09-07 05:21:21','2026-09-07 05:21:21','Hi, this is a comment.\nTo get started with moderating, editing, and deleting comments, please visit the Comments screen in the dashboard.\nCommenter avatars come from <a href=\"https://gravatar.com/\">Gravatar</a>.',0,'1','','comment',0,0);
/*!40000 ALTER TABLE `wp_comments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_links`
--

DROP TABLE IF EXISTS `wp_links`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_links` (
  `link_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `link_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint unsigned NOT NULL DEFAULT '1',
  `link_rating` int NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `link_rss` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`link_id`),
  KEY `link_visible` (`link_visible`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_links`
--

LOCK TABLES `wp_links` WRITE;
/*!40000 ALTER TABLE `wp_links` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_links` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_options`
--

DROP TABLE IF EXISTS `wp_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_options` (
  `option_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `option_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `option_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `autoload` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'yes',
  PRIMARY KEY (`option_id`),
  UNIQUE KEY `option_name` (`option_name`),
  KEY `autoload` (`autoload`)
) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_options`
--

LOCK TABLES `wp_options` WRITE;
/*!40000 ALTER TABLE `wp_options` DISABLE KEYS */;
INSERT INTO `wp_options` VALUES (1,'cron','a:12:{i:1788758482;a:3:{s:32:\"recovery_mode_clean_expired_keys\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:34:\"wp_privacy_delete_old_export_files\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}s:41:\"wp_privacy_personal_data_cleanup_requests\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1788758485;a:1:{s:30:\"wp_delete_temp_updater_backups\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}i:1788758651;a:1:{s:26:\"rediscache_discard_metrics\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"hourly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:3600;}}}i:1788758671;a:3:{s:19:\"wp_scheduled_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:25:\"delete_expired_transients\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}s:21:\"wp_update_user_counts\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1788758677;a:1:{s:30:\"wp_scheduled_auto_draft_delete\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:5:\"daily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:86400;}}}i:1788758731;a:1:{s:28:\"wp_update_comment_type_batch\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}i:1788759122;a:1:{s:8:\"do_pings\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:2:{s:8:\"schedule\";b:0;s:4:\"args\";a:0:{}}}}i:1788762081;a:1:{s:16:\"wp_version_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1788763881;a:1:{s:17:\"wp_update_plugins\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1788765681;a:1:{s:16:\"wp_update_themes\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:10:\"twicedaily\";s:4:\"args\";a:0:{}s:8:\"interval\";i:43200;}}}i:1788844882;a:1:{s:30:\"wp_site_health_scheduled_check\";a:1:{s:32:\"40cd750bba9870f18aada2478b24840a\";a:3:{s:8:\"schedule\";s:6:\"weekly\";s:4:\"args\";a:0:{}s:8:\"interval\";i:604800;}}}s:7:\"version\";i:2;}','on'),(2,'siteurl','http://localhost:8000','on'),(3,'home','http://localhost:8000','on'),(4,'blogname','GS Sanyal School of Technology','on'),(5,'blogdescription','','on'),(6,'users_can_register','0','on'),(7,'admin_email','admin@example.com','on'),(8,'start_of_week','1','on'),(9,'use_balanceTags','0','on'),(10,'use_smilies','1','on'),(11,'require_name_email','1','on'),(12,'comments_notify','1','on'),(13,'posts_per_rss','10','on'),(14,'rss_use_excerpt','0','on'),(15,'mailserver_url','mail.example.com','on'),(16,'mailserver_login','login@example.com','on'),(17,'mailserver_pass','','on'),(18,'mailserver_port','110','on'),(19,'default_category','1','on'),(20,'default_comment_status','open','on'),(21,'default_ping_status','open','on'),(22,'default_pingback_flag','1','on'),(23,'posts_per_page','10','on'),(24,'date_format','F j, Y','on'),(25,'time_format','g:i a','on'),(26,'links_updated_date_format','F j, Y g:i a','on'),(27,'comment_moderation','0','on'),(28,'moderation_notify','1','on'),(29,'permalink_structure','','on'),(30,'rewrite_rules','','on'),(31,'hack_file','0','on'),(32,'blog_charset','UTF-8','on'),(33,'moderation_keys','','off'),(34,'active_plugins','a:1:{i:0;s:27:\"redis-cache/redis-cache.php\";}','on'),(35,'category_base','','on'),(36,'ping_sites','https://rpc.pingomatic.com/','on'),(37,'comment_max_links','2','on'),(38,'gmt_offset','0','on'),(39,'default_email_category','1','on'),(40,'recently_edited','','off'),(41,'template','cic-theme','on'),(42,'stylesheet','cic-theme','on'),(43,'comment_registration','0','on'),(44,'html_type','text/html','on'),(45,'use_trackback','0','on'),(46,'default_role','subscriber','on'),(47,'db_version','61833','on'),(48,'uploads_use_yearmonth_folders','1','on'),(49,'upload_path','','on'),(50,'blog_public','1','on'),(51,'default_link_category','2','on'),(52,'show_on_front','posts','on'),(53,'tag_base','','on'),(54,'show_avatars','1','on'),(55,'avatar_rating','G','on'),(56,'upload_url_path','','on'),(57,'thumbnail_size_w','150','on'),(58,'thumbnail_size_h','150','on'),(59,'thumbnail_crop','1','on'),(60,'medium_size_w','300','on'),(61,'medium_size_h','300','on'),(62,'avatar_default','mystery','on'),(63,'large_size_w','1024','on'),(64,'large_size_h','1024','on'),(65,'image_default_link_type','none','on'),(66,'image_default_size','','on'),(67,'image_default_align','','on'),(68,'close_comments_for_old_posts','0','on'),(69,'close_comments_days_old','14','on'),(70,'thread_comments','1','on'),(71,'thread_comments_depth','5','on'),(72,'page_comments','0','on'),(73,'comments_per_page','50','on'),(74,'default_comments_page','newest','on'),(75,'comment_order','asc','on'),(76,'sticky_posts','a:0:{}','on'),(77,'widget_categories','a:0:{}','on'),(78,'widget_text','a:0:{}','on'),(79,'widget_rss','a:0:{}','on'),(80,'uninstall_plugins','a:0:{}','off'),(81,'timezone_string','','on'),(82,'page_for_posts','0','on'),(83,'page_on_front','0','on'),(84,'default_post_format','0','on'),(85,'link_manager_enabled','0','on'),(86,'finished_splitting_shared_terms','1','on'),(87,'site_icon','0','on'),(88,'medium_large_size_w','768','on'),(89,'medium_large_size_h','0','on'),(90,'wp_page_for_privacy_policy','3','on'),(91,'show_comments_cookies_opt_in','1','on'),(92,'admin_email_lifespan','1804310481','on'),(93,'disallowed_keys','','off'),(94,'comment_previously_approved','1','on'),(95,'auto_plugin_theme_update_emails','a:0:{}','off'),(96,'auto_update_core_dev','enabled','on'),(97,'auto_update_core_minor','enabled','on'),(98,'auto_update_core_major','enabled','on'),(99,'wp_force_deactivated_plugins','a:0:{}','on'),(100,'wp_attachment_pages_enabled','0','on'),(101,'wp_notes_notify','1','on'),(102,'initial_db_version','61833','on'),(103,'wp_user_roles','a:5:{s:13:\"administrator\";a:2:{s:4:\"name\";s:13:\"Administrator\";s:12:\"capabilities\";a:61:{s:13:\"switch_themes\";b:1;s:11:\"edit_themes\";b:1;s:16:\"activate_plugins\";b:1;s:12:\"edit_plugins\";b:1;s:10:\"edit_users\";b:1;s:10:\"edit_files\";b:1;s:14:\"manage_options\";b:1;s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:6:\"import\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:8:\"level_10\";b:1;s:7:\"level_9\";b:1;s:7:\"level_8\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;s:12:\"delete_users\";b:1;s:12:\"create_users\";b:1;s:17:\"unfiltered_upload\";b:1;s:14:\"edit_dashboard\";b:1;s:14:\"update_plugins\";b:1;s:14:\"delete_plugins\";b:1;s:15:\"install_plugins\";b:1;s:13:\"update_themes\";b:1;s:14:\"install_themes\";b:1;s:11:\"update_core\";b:1;s:10:\"list_users\";b:1;s:12:\"remove_users\";b:1;s:13:\"promote_users\";b:1;s:18:\"edit_theme_options\";b:1;s:13:\"delete_themes\";b:1;s:6:\"export\";b:1;}}s:6:\"editor\";a:2:{s:4:\"name\";s:6:\"Editor\";s:12:\"capabilities\";a:34:{s:17:\"moderate_comments\";b:1;s:17:\"manage_categories\";b:1;s:12:\"manage_links\";b:1;s:12:\"upload_files\";b:1;s:15:\"unfiltered_html\";b:1;s:10:\"edit_posts\";b:1;s:17:\"edit_others_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:10:\"edit_pages\";b:1;s:4:\"read\";b:1;s:7:\"level_7\";b:1;s:7:\"level_6\";b:1;s:7:\"level_5\";b:1;s:7:\"level_4\";b:1;s:7:\"level_3\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:17:\"edit_others_pages\";b:1;s:20:\"edit_published_pages\";b:1;s:13:\"publish_pages\";b:1;s:12:\"delete_pages\";b:1;s:19:\"delete_others_pages\";b:1;s:22:\"delete_published_pages\";b:1;s:12:\"delete_posts\";b:1;s:19:\"delete_others_posts\";b:1;s:22:\"delete_published_posts\";b:1;s:20:\"delete_private_posts\";b:1;s:18:\"edit_private_posts\";b:1;s:18:\"read_private_posts\";b:1;s:20:\"delete_private_pages\";b:1;s:18:\"edit_private_pages\";b:1;s:18:\"read_private_pages\";b:1;}}s:6:\"author\";a:2:{s:4:\"name\";s:6:\"Author\";s:12:\"capabilities\";a:10:{s:12:\"upload_files\";b:1;s:10:\"edit_posts\";b:1;s:20:\"edit_published_posts\";b:1;s:13:\"publish_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_2\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;s:22:\"delete_published_posts\";b:1;}}s:11:\"contributor\";a:2:{s:4:\"name\";s:11:\"Contributor\";s:12:\"capabilities\";a:5:{s:10:\"edit_posts\";b:1;s:4:\"read\";b:1;s:7:\"level_1\";b:1;s:7:\"level_0\";b:1;s:12:\"delete_posts\";b:1;}}s:10:\"subscriber\";a:2:{s:4:\"name\";s:10:\"Subscriber\";s:12:\"capabilities\";a:2:{s:4:\"read\";b:1;s:7:\"level_0\";b:1;}}}','on'),(104,'fresh_site','0','off'),(105,'user_count','1','off'),(106,'widget_block','a:6:{i:2;a:1:{s:7:\"content\";s:19:\"<!-- wp:search /-->\";}i:3;a:1:{s:7:\"content\";s:154:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Posts</h2><!-- /wp:heading --><!-- wp:latest-posts /--></div><!-- /wp:group -->\";}i:4;a:1:{s:7:\"content\";s:227:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Recent Comments</h2><!-- /wp:heading --><!-- wp:latest-comments {\"displayAvatar\":false,\"displayDate\":false,\"displayExcerpt\":false} /--></div><!-- /wp:group -->\";}i:5;a:1:{s:7:\"content\";s:146:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Archives</h2><!-- /wp:heading --><!-- wp:archives /--></div><!-- /wp:group -->\";}i:6;a:1:{s:7:\"content\";s:150:\"<!-- wp:group --><div class=\"wp-block-group\"><!-- wp:heading --><h2>Categories</h2><!-- /wp:heading --><!-- wp:categories /--></div><!-- /wp:group -->\";}s:12:\"_multiwidget\";i:1;}','auto'),(107,'sidebars_widgets','a:2:{s:19:\"wp_inactive_widgets\";a:5:{i:0;s:7:\"block-2\";i:1;s:7:\"block-3\";i:2;s:7:\"block-4\";i:3;s:7:\"block-5\";i:4;s:7:\"block-6\";}s:13:\"array_version\";i:3;}','auto'),(108,'widget_pages','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(109,'widget_calendar','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(110,'widget_archives','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(111,'widget_media_audio','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(112,'widget_media_image','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(113,'widget_media_gallery','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(114,'widget_media_video','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(115,'widget_meta','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(116,'widget_search','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(117,'widget_recent-posts','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(118,'widget_recent-comments','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(119,'widget_tag_cloud','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(120,'widget_nav_menu','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(121,'widget_custom_html','a:1:{s:12:\"_multiwidget\";i:1;}','auto'),(127,'theme_mods_twentytwentyfive','a:1:{s:16:\"sidebars_widgets\";a:2:{s:4:\"time\";i:1788758482;s:4:\"data\";a:3:{s:19:\"wp_inactive_widgets\";a:0:{}s:9:\"sidebar-1\";a:3:{i:0;s:7:\"block-2\";i:1;s:7:\"block-3\";i:2;s:7:\"block-4\";}s:9:\"sidebar-2\";a:2:{i:0;s:7:\"block-5\";i:1;s:7:\"block-6\";}}}}','off'),(128,'current_theme','CIC Theme','auto'),(129,'theme_switched','','auto'),(133,'theme_mods_cic-theme','a:2:{s:18:\"nav_menu_locations\";a:4:{s:8:\"top_menu\";i:3;s:12:\"primary_menu\";i:4;s:12:\"footer_links\";i:5;s:16:\"footer_academics\";i:6;}s:18:\"custom_css_post_id\";i:-1;}','auto'),(141,'can_compress_scripts','0','on'),(142,'recently_activated','a:0:{}','off'),(143,'wp_calendar_block_has_published_posts','1','auto'),(144,'category_children','a:0:{}','auto'),(147,'recovery_mode_email_last_sent','1788776770','auto'),(148,'recovery_keys','a:1:{s:22:\"lvc2pUlIz5RTOK4vQiEQ6b\";a:2:{s:10:\"hashed_key\";s:49:\"$generic$BS8lPyXqCUHagrrclefEwssQrSUMHBa6GeEJgzpD\";s:10:\"created_at\";i:1788776770;}}','off'),(149,'cic_dept_settings','a:2:{s:13:\"section_title\";s:22:\"Explore Our Department\";s:5:\"cards\";a:4:{i:0;a:5:{s:5:\"title\";s:21:\"Research & Innovation\";s:11:\"description\";s:67:\"Engage with cutting-edge research that addresses global challenges.\";s:8:\"btn_text\";s:7:\"Explore\";s:7:\"btn_url\";s:1:\"#\";s:9:\"icon_type\";s:4:\"chip\";}i:1;a:5:{s:5:\"title\";s:15:\"Faculty & Staff\";s:11:\"description\";s:68:\"Meet our distinguished faculty members committed to student success.\";s:8:\"btn_text\";s:9:\"Directory\";s:7:\"btn_url\";s:1:\"#\";s:9:\"icon_type\";s:5:\"users\";}i:2;a:5:{s:5:\"title\";s:6:\"Awards\";s:11:\"description\";s:69:\"Celebrating the outstanding achievements of our students and faculty.\";s:8:\"btn_text\";s:8:\"View All\";s:7:\"btn_url\";s:1:\"#\";s:9:\"icon_type\";s:5:\"award\";}i:3;a:5:{s:5:\"title\";s:6:\"Carrer\";s:11:\"description\";s:33:\"Apply for faculty / staff vacancy\";s:8:\"btn_text\";s:5:\"Apply\";s:7:\"btn_url\";s:1:\"#\";s:9:\"icon_type\";s:4:\"book\";}}}','auto');
/*!40000 ALTER TABLE `wp_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_postmeta`
--

DROP TABLE IF EXISTS `wp_postmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_postmeta` (
  `meta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `post_id` (`post_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=179 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_postmeta`
--

LOCK TABLES `wp_postmeta` WRITE;
/*!40000 ALTER TABLE `wp_postmeta` DISABLE KEYS */;
INSERT INTO `wp_postmeta` VALUES (1,2,'_wp_page_template','default'),(2,3,'_wp_page_template','default'),(3,2,'_edit_lock','1788758692:1'),(4,6,'_wp_attached_file','2026/09/1.jpeg'),(5,6,'_wp_attachment_metadata','a:6:{s:5:\"width\";i:733;s:6:\"height\";i:1024;s:4:\"file\";s:14:\"2026/09/1.jpeg\";s:8:\"filesize\";i:159546;s:5:\"sizes\";a:2:{s:6:\"medium\";a:5:{s:4:\"file\";s:14:\"1-215x300.jpeg\";s:5:\"width\";i:215;s:6:\"height\";i:300;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:18856;}s:9:\"thumbnail\";a:5:{s:4:\"file\";s:14:\"1-150x150.jpeg\";s:5:\"width\";i:150;s:6:\"height\";i:150;s:9:\"mime-type\";s:10:\"image/jpeg\";s:8:\"filesize\";i:7774;}}s:10:\"image_meta\";a:13:{s:8:\"aperture\";s:1:\"0\";s:6:\"credit\";s:0:\"\";s:6:\"camera\";s:0:\"\";s:7:\"caption\";s:0:\"\";s:17:\"created_timestamp\";s:1:\"0\";s:9:\"copyright\";s:0:\"\";s:12:\"focal_length\";s:1:\"0\";s:3:\"iso\";s:1:\"0\";s:13:\"shutter_speed\";s:1:\"0\";s:5:\"title\";s:0:\"\";s:11:\"orientation\";s:1:\"0\";s:8:\"keywords\";a:0:{}s:3:\"alt\";s:0:\"\";}}'),(6,1,'_edit_lock','1788758766:1'),(7,7,'_edit_lock','1788759068:1'),(8,8,'_edit_lock','1788759163:1'),(9,8,'_pingme','1'),(10,8,'_encloseme','1'),(11,10,'_menu_item_type','custom'),(12,10,'_menu_item_menu_item_parent','0'),(13,10,'_menu_item_object_id','10'),(14,10,'_menu_item_object','custom'),(15,10,'_menu_item_target',''),(16,10,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(17,10,'_menu_item_xfn',''),(18,10,'_menu_item_url','http://localhost:8000/'),(19,11,'_menu_item_type','custom'),(20,11,'_menu_item_menu_item_parent','0'),(21,11,'_menu_item_object_id','11'),(22,11,'_menu_item_object','custom'),(23,11,'_menu_item_target',''),(24,11,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(25,11,'_menu_item_xfn',''),(26,11,'_menu_item_url','#'),(27,12,'_menu_item_type','custom'),(28,12,'_menu_item_menu_item_parent','0'),(29,12,'_menu_item_object_id','12'),(30,12,'_menu_item_object','custom'),(31,12,'_menu_item_target',''),(32,12,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(33,12,'_menu_item_xfn',''),(34,12,'_menu_item_url','#'),(35,13,'_menu_item_type','custom'),(36,13,'_menu_item_menu_item_parent','0'),(37,13,'_menu_item_object_id','13'),(38,13,'_menu_item_object','custom'),(39,13,'_menu_item_target',''),(40,13,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(41,13,'_menu_item_xfn',''),(42,13,'_menu_item_url','#'),(43,14,'_menu_item_type','custom'),(44,14,'_menu_item_menu_item_parent','0'),(45,14,'_menu_item_object_id','14'),(46,14,'_menu_item_object','custom'),(47,14,'_menu_item_target',''),(48,14,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(49,14,'_menu_item_xfn',''),(50,14,'_menu_item_url','https://www.iitkgp.ac.in'),(51,15,'_menu_item_type','custom'),(52,15,'_menu_item_menu_item_parent','0'),(53,15,'_menu_item_object_id','15'),(54,15,'_menu_item_object','custom'),(55,15,'_menu_item_target',''),(56,15,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(57,15,'_menu_item_xfn',''),(58,15,'_menu_item_url','#'),(59,16,'_menu_item_type','custom'),(60,16,'_menu_item_menu_item_parent','0'),(61,16,'_menu_item_object_id','16'),(62,16,'_menu_item_object','custom'),(63,16,'_menu_item_target',''),(64,16,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(65,16,'_menu_item_xfn',''),(66,16,'_menu_item_url','#'),(67,17,'_menu_item_type','custom'),(68,17,'_menu_item_menu_item_parent','0'),(69,17,'_menu_item_object_id','17'),(70,17,'_menu_item_object','custom'),(71,17,'_menu_item_target',''),(72,17,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(73,17,'_menu_item_xfn',''),(74,17,'_menu_item_url','#'),(75,18,'_menu_item_type','custom'),(76,18,'_menu_item_menu_item_parent','0'),(77,18,'_menu_item_object_id','18'),(78,18,'_menu_item_object','custom'),(79,18,'_menu_item_target',''),(80,18,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(81,18,'_menu_item_xfn',''),(82,18,'_menu_item_url','#'),(83,19,'_menu_item_type','custom'),(84,19,'_menu_item_menu_item_parent','0'),(85,19,'_menu_item_object_id','19'),(86,19,'_menu_item_object','custom'),(87,19,'_menu_item_target',''),(88,19,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(89,19,'_menu_item_xfn',''),(90,19,'_menu_item_url','#'),(91,20,'_menu_item_type','custom'),(92,20,'_menu_item_menu_item_parent','0'),(93,20,'_menu_item_object_id','20'),(94,20,'_menu_item_object','custom'),(95,20,'_menu_item_target',''),(96,20,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(97,20,'_menu_item_xfn',''),(98,20,'_menu_item_url','#'),(99,21,'_menu_item_type','custom'),(100,21,'_menu_item_menu_item_parent','0'),(101,21,'_menu_item_object_id','21'),(102,21,'_menu_item_object','custom'),(103,21,'_menu_item_target',''),(104,21,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(105,21,'_menu_item_xfn',''),(106,21,'_menu_item_url','#'),(107,22,'_menu_item_type','custom'),(108,22,'_menu_item_menu_item_parent','0'),(109,22,'_menu_item_object_id','22'),(110,22,'_menu_item_object','custom'),(111,22,'_menu_item_target',''),(112,22,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(113,22,'_menu_item_xfn',''),(114,22,'_menu_item_url','#'),(115,23,'_menu_item_type','custom'),(116,23,'_menu_item_menu_item_parent','0'),(117,23,'_menu_item_object_id','23'),(118,23,'_menu_item_object','custom'),(119,23,'_menu_item_target',''),(120,23,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(121,23,'_menu_item_xfn',''),(122,23,'_menu_item_url','#'),(123,24,'_menu_item_type','custom'),(124,24,'_menu_item_menu_item_parent','0'),(125,24,'_menu_item_object_id','24'),(126,24,'_menu_item_object','custom'),(127,24,'_menu_item_target',''),(128,24,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(129,24,'_menu_item_xfn',''),(130,24,'_menu_item_url','#'),(131,25,'_menu_item_type','custom'),(132,25,'_menu_item_menu_item_parent','0'),(133,25,'_menu_item_object_id','25'),(134,25,'_menu_item_object','custom'),(135,25,'_menu_item_target',''),(136,25,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(137,25,'_menu_item_xfn',''),(138,25,'_menu_item_url','https://www.iitkgp.ac.in'),(139,26,'_menu_item_type','custom'),(140,26,'_menu_item_menu_item_parent','0'),(141,26,'_menu_item_object_id','26'),(142,26,'_menu_item_object','custom'),(143,26,'_menu_item_target',''),(144,26,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(145,26,'_menu_item_xfn',''),(146,26,'_menu_item_url','#'),(147,27,'_menu_item_type','custom'),(148,27,'_menu_item_menu_item_parent','0'),(149,27,'_menu_item_object_id','27'),(150,27,'_menu_item_object','custom'),(151,27,'_menu_item_target',''),(152,27,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(153,27,'_menu_item_xfn',''),(154,27,'_menu_item_url','#'),(155,28,'_menu_item_type','custom'),(156,28,'_menu_item_menu_item_parent','0'),(157,28,'_menu_item_object_id','28'),(158,28,'_menu_item_object','custom'),(159,28,'_menu_item_target',''),(160,28,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(161,28,'_menu_item_xfn',''),(162,28,'_menu_item_url','#'),(163,29,'_menu_item_type','custom'),(164,29,'_menu_item_menu_item_parent','0'),(165,29,'_menu_item_object_id','29'),(166,29,'_menu_item_object','custom'),(167,29,'_menu_item_target',''),(168,29,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(169,29,'_menu_item_xfn',''),(170,29,'_menu_item_url','#'),(171,30,'_menu_item_type','custom'),(172,30,'_menu_item_menu_item_parent','0'),(173,30,'_menu_item_object_id','30'),(174,30,'_menu_item_object','custom'),(175,30,'_menu_item_target',''),(176,30,'_menu_item_classes','a:1:{i:0;s:0:\"\";}'),(177,30,'_menu_item_xfn',''),(178,30,'_menu_item_url','#');
/*!40000 ALTER TABLE `wp_postmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_posts`
--

DROP TABLE IF EXISTS `wp_posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_posts` (
  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `post_author` bigint unsigned NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `to_ping` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `pinged` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_parent` bigint unsigned NOT NULL DEFAULT '0',
  `guid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `menu_order` int NOT NULL DEFAULT '0',
  `post_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_count` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`ID`),
  KEY `post_name` (`post_name`(191)),
  KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  KEY `post_parent` (`post_parent`),
  KEY `post_author` (`post_author`),
  KEY `type_status_author` (`post_type`,`post_status`,`post_author`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_posts`
--

LOCK TABLES `wp_posts` WRITE;
/*!40000 ALTER TABLE `wp_posts` DISABLE KEYS */;
INSERT INTO `wp_posts` VALUES (1,1,'2026-09-07 05:21:21','2026-09-07 05:21:21','<!-- wp:paragraph -->\n<p>Welcome to WordPress. This is your first post. Edit or delete it, then start writing!</p>\n<!-- /wp:paragraph -->','Hello world!','','publish','open','open','','hello-world','','','2026-09-07 05:21:21','2026-09-07 05:21:21','',0,'http://localhost:8000/?p=1',0,'post','',1),(2,1,'2026-09-07 05:21:21','2026-09-07 05:21:21','<!-- wp:paragraph -->\n<p>This is an example page. It\'s different from a blog post because it will stay in one place and will show up in your site navigation (in most themes). Most people start with an About page that introduces them to potential site visitors. It might say something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\">\n<!-- wp:paragraph -->\n<p>Hi there! I\'m a bike messenger by day, aspiring actor by night, and this is my website. I live in Los Angeles, have a great dog named Jack, and I like pi&#241;a coladas. (And gettin\' caught in the rain.)</p>\n<!-- /wp:paragraph -->\n</blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>...or something like this:</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:quote -->\n<blockquote class=\"wp-block-quote\">\n<!-- wp:paragraph -->\n<p>The XYZ Doohickey Company was founded in 1971, and has been providing quality doohickeys to the public ever since. Located in Gotham City, XYZ employs over 2,000 people and does all kinds of awesome things for the Gotham community.</p>\n<!-- /wp:paragraph -->\n</blockquote>\n<!-- /wp:quote -->\n\n<!-- wp:paragraph -->\n<p>As a new WordPress user, you should go to <a href=\"http://localhost:8000/wp-admin/\">your dashboard</a> to delete this page and create new pages for your content. Have fun!</p>\n<!-- /wp:paragraph -->','Sample Page','','publish','closed','open','','sample-page','','','2026-09-07 05:21:21','2026-09-07 05:21:21','',0,'http://localhost:8000/?page_id=2',0,'page','',0),(3,1,'2026-09-07 05:21:21','2026-09-07 05:21:21','<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Who we are</h2>\n<!-- /wp:heading -->\n<!-- wp:paragraph -->\n<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Our website address is: http://localhost:8000.</p>\n<!-- /wp:paragraph -->\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Comments</h2>\n<!-- /wp:heading -->\n<!-- wp:paragraph -->\n<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>When visitors leave comments on the site we collect the data shown in the comments form, and also the visitor&#8217;s IP address and browser user agent string to help spam detection.</p>\n<!-- /wp:paragraph -->\n<!-- wp:paragraph -->\n<p>An anonymized string created from your email address (also called a hash) may be provided to the Gravatar service to see if you are using it. The Gravatar service privacy policy is available here: https://automattic.com/privacy/. After approval of your comment, your profile picture is visible to the public in the context of your comment.</p>\n<!-- /wp:paragraph -->\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Media</h2>\n<!-- /wp:heading -->\n<!-- wp:paragraph -->\n<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you upload images to the website, you should avoid uploading images with embedded location data (EXIF GPS) included. Visitors to the website can download and extract any location data from images on the website.</p>\n<!-- /wp:paragraph -->\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Cookies</h2>\n<!-- /wp:heading -->\n<!-- wp:paragraph -->\n<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you leave a comment on our site you may opt-in to saving your name, email address and website in cookies. These are for your convenience so that you do not have to fill in your details again when you leave another comment. These cookies will last for one year.</p>\n<!-- /wp:paragraph -->\n<!-- wp:paragraph -->\n<p>If you visit our login page, we will set a temporary cookie to determine if your browser accepts cookies. This cookie contains no personal data and is discarded when you close your browser.</p>\n<!-- /wp:paragraph -->\n<!-- wp:paragraph -->\n<p>When you log in, we will also set up several cookies to save your login information and your screen display choices. Login cookies last for two days, and screen options cookies last for a year. If you select &quot;Remember Me&quot;, your login will persist for two weeks. If you log out of your account, the login cookies will be removed.</p>\n<!-- /wp:paragraph -->\n<!-- wp:paragraph -->\n<p>If you edit or publish an article, an additional cookie will be saved in your browser. This cookie includes no personal data and simply indicates the post ID of the article you just edited. It expires after 1 day.</p>\n<!-- /wp:paragraph -->\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Embedded content from other websites</h2>\n<!-- /wp:heading -->\n<!-- wp:paragraph -->\n<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Articles on this site may include embedded content (e.g. videos, images, articles, etc.). Embedded content from other websites behaves in the exact same way as if the visitor has visited the other website.</p>\n<!-- /wp:paragraph -->\n<!-- wp:paragraph -->\n<p>These websites may collect data about you, use cookies, embed additional third-party tracking, and monitor your interaction with that embedded content, including tracking your interaction with the embedded content if you have an account and are logged in to that website.</p>\n<!-- /wp:paragraph -->\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Who we share your data with</h2>\n<!-- /wp:heading -->\n<!-- wp:paragraph -->\n<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you request a password reset, your IP address will be included in the reset email.</p>\n<!-- /wp:paragraph -->\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">How long we retain your data</h2>\n<!-- /wp:heading -->\n<!-- wp:paragraph -->\n<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you leave a comment, the comment and its metadata are retained indefinitely. This is so we can recognize and approve any follow-up comments automatically instead of holding them in a moderation queue.</p>\n<!-- /wp:paragraph -->\n<!-- wp:paragraph -->\n<p>For users that register on our website (if any), we also store the personal information they provide in their user profile. All users can see, edit, or delete their personal information at any time (except they cannot change their username). Website administrators can also see and edit that information.</p>\n<!-- /wp:paragraph -->\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">What rights you have over your data</h2>\n<!-- /wp:heading -->\n<!-- wp:paragraph -->\n<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>If you have an account on this site, or have left comments, you can request to receive an exported file of the personal data we hold about you, including any data you have provided to us. You can also request that we erase any personal data we hold about you. This does not include any data we are obliged to keep for administrative, legal, or security purposes.</p>\n<!-- /wp:paragraph -->\n<!-- wp:heading -->\n<h2 class=\"wp-block-heading\">Where your data is sent</h2>\n<!-- /wp:heading -->\n<!-- wp:paragraph -->\n<p><strong class=\"privacy-policy-tutorial\">Suggested text: </strong>Visitor comments may be checked through an automated spam detection service.</p>\n<!-- /wp:paragraph -->\n','Privacy Policy','','draft','closed','open','','privacy-policy','','','2026-09-07 05:21:21','2026-09-07 05:21:21','',0,'http://localhost:8000/?page_id=3',0,'page','',0),(4,1,'2026-09-07 05:24:37','0000-00-00 00:00:00','','Auto Draft','','auto-draft','open','open','','','','','2026-09-07 05:24:37','0000-00-00 00:00:00','',0,'http://localhost:8000/?p=4',0,'post','',0),(5,1,'2026-09-07 05:24:52','2026-09-07 05:24:52','{\"version\": 3, \"isGlobalStylesUserThemeJSON\": true }','Custom Styles','','publish','closed','closed','','wp-global-styles-cic-theme','','','2026-09-07 05:24:52','2026-09-07 05:24:52','',0,'http://localhost:8000/?p=5',0,'wp_global_styles','',0),(6,1,'2026-09-07 05:25:39','2026-09-07 05:25:39','','1','','inherit','open','closed','','1','','','2026-09-07 05:25:39','2026-09-07 05:25:39','',0,'http://localhost:8000/wp-content/uploads/2026/09/1.jpeg',0,'attachment','image/jpeg',0),(7,1,'2026-09-07 05:31:08','0000-00-00 00:00:00','','Auto Draft','','auto-draft','open','open','','','','','2026-09-07 05:31:08','0000-00-00 00:00:00','',0,'http://localhost:8000/?p=7',0,'post','',0),(8,1,'2026-09-07 05:32:02','2026-09-07 05:32:02','<!-- wp:paragraph -->\n<p>This is the image.</p>\n<!-- /wp:paragraph -->','This is me','','publish','open','open','','this-is-me','','','2026-09-07 05:32:02','2026-09-07 05:32:02','',0,'http://localhost:8000/?p=8',0,'post','',0),(9,1,'2026-09-07 05:32:02','2026-09-07 05:32:02','<!-- wp:paragraph -->\n<p>This is the image.</p>\n<!-- /wp:paragraph -->','This is me','','inherit','closed','closed','','8-revision-v1','','','2026-09-07 05:32:02','2026-09-07 05:32:02','',8,'http://localhost:8000/?p=9',0,'revision','',0),(10,0,'2026-09-07 05:58:29','2026-09-07 05:58:29','','HOME','','publish','closed','closed','','home','','','2026-09-07 05:58:29','2026-09-07 05:58:29','',0,'http://localhost:8000/?p=10',0,'nav_menu_item','',0),(11,0,'2026-09-07 05:58:30','2026-09-07 05:58:30','','RESEARCH','','publish','closed','closed','','research','','','2026-09-07 05:58:30','2026-09-07 05:58:30','',0,'http://localhost:8000/?p=11',2,'nav_menu_item','',0),(12,0,'2026-09-07 05:58:30','2026-09-07 05:58:30','','STAFF','','publish','closed','closed','','staff','','','2026-09-07 05:58:30','2026-09-07 05:58:30','',0,'http://localhost:8000/?p=12',3,'nav_menu_item','',0),(13,0,'2026-09-07 05:58:30','2026-09-07 05:58:30','','FACULTY','','publish','closed','closed','','faculty','','','2026-09-07 05:58:30','2026-09-07 05:58:30','',0,'http://localhost:8000/?p=13',4,'nav_menu_item','',0),(14,0,'2026-09-07 05:58:31','2026-09-07 05:58:31','','IIT KGP','','publish','closed','closed','','iit-kgp','','','2026-09-07 05:58:31','2026-09-07 05:58:31','',0,'http://localhost:8000/?p=14',5,'nav_menu_item','',0),(15,0,'2026-09-07 05:58:32','2026-09-07 05:58:32','','HEAD','','publish','closed','closed','','head','','','2026-09-07 05:58:32','2026-09-07 05:58:32','',0,'http://localhost:8000/?p=15',0,'nav_menu_item','',0),(16,0,'2026-09-07 05:58:32','2026-09-07 05:58:32','','FACULTY','','publish','closed','closed','','faculty-2','','','2026-09-07 05:58:32','2026-09-07 05:58:32','',0,'http://localhost:8000/?p=16',2,'nav_menu_item','',0),(17,0,'2026-09-07 05:58:32','2026-09-07 05:58:32','','STAFF','','publish','closed','closed','','staff-2','','','2026-09-07 05:58:32','2026-09-07 05:58:32','',0,'http://localhost:8000/?p=17',3,'nav_menu_item','',0),(18,0,'2026-09-07 05:58:33','2026-09-07 05:58:33','','ACADEMICS','','publish','closed','closed','','academics','','','2026-09-07 05:58:33','2026-09-07 05:58:33','',0,'http://localhost:8000/?p=18',4,'nav_menu_item','',0),(19,0,'2026-09-07 05:58:33','2026-09-07 05:58:33','','RESEARCH','','publish','closed','closed','','research-2','','','2026-09-07 05:58:33','2026-09-07 05:58:33','',0,'http://localhost:8000/?p=19',5,'nav_menu_item','',0),(20,0,'2026-09-07 05:58:33','2026-09-07 05:58:33','','PUBLICATION','','publish','closed','closed','','publication','','','2026-09-07 05:58:33','2026-09-07 05:58:33','',0,'http://localhost:8000/?p=20',6,'nav_menu_item','',0),(21,0,'2026-09-07 05:58:34','2026-09-07 05:58:34','','NOTABLE ALUMNI','','publish','closed','closed','','notable-alumni','','','2026-09-07 05:58:34','2026-09-07 05:58:34','',0,'http://localhost:8000/?p=21',7,'nav_menu_item','',0),(22,0,'2026-09-07 05:58:34','2026-09-07 05:58:34','','FACULTY AWARDS','','publish','closed','closed','','faculty-awards','','','2026-09-07 05:58:34','2026-09-07 05:58:34','',0,'http://localhost:8000/?p=22',8,'nav_menu_item','',0),(23,0,'2026-09-07 05:58:34','2026-09-07 05:58:34','','STUDENT AWARDS','','publish','closed','closed','','student-awards','','','2026-09-07 05:58:34','2026-09-07 05:58:34','',0,'http://localhost:8000/?p=23',9,'nav_menu_item','',0),(24,0,'2026-09-07 05:58:35','2026-09-07 05:58:35','','PHOTO GALLERY','','publish','closed','closed','','photo-gallery','','','2026-09-07 05:58:35','2026-09-07 05:58:35','',0,'http://localhost:8000/?p=24',10,'nav_menu_item','',0),(25,0,'2026-09-07 05:58:36','2026-09-07 05:58:36','','Institute Home','','publish','closed','closed','','institute-home','','','2026-09-07 05:58:36','2026-09-07 05:58:36','',0,'http://localhost:8000/?p=25',0,'nav_menu_item','',0),(26,0,'2026-09-07 05:58:36','2026-09-07 05:58:36','','ERP Portal','','publish','closed','closed','','erp-portal','','','2026-09-07 05:58:36','2026-09-07 05:58:36','',0,'http://localhost:8000/?p=26',2,'nav_menu_item','',0),(27,0,'2026-09-07 05:58:36','2026-09-07 05:58:36','','Central Library','','publish','closed','closed','','central-library','','','2026-09-07 05:58:36','2026-09-07 05:58:36','',0,'http://localhost:8000/?p=27',3,'nav_menu_item','',0),(28,0,'2026-09-07 05:58:37','2026-09-07 05:58:37','','Programmes','','publish','closed','closed','','programmes','','','2026-09-07 05:58:37','2026-09-07 05:58:37','',0,'http://localhost:8000/?p=28',0,'nav_menu_item','',0),(29,0,'2026-09-07 05:58:38','2026-09-07 05:58:38','','Admissions','','publish','closed','closed','','admissions','','','2026-09-07 05:58:38','2026-09-07 05:58:38','',0,'http://localhost:8000/?p=29',2,'nav_menu_item','',0),(30,0,'2026-09-07 05:58:38','2026-09-07 05:58:38','','Academic Calendar','','publish','closed','closed','','academic-calendar','','','2026-09-07 05:58:38','2026-09-07 05:58:38','',0,'http://localhost:8000/?p=30',3,'nav_menu_item','',0),(31,0,'2026-03-06 00:00:00','2026-03-06 00:00:00','','M.Tech. Admission 2026-27 Applications open','','publish','closed','closed','','m-tech-admission-2026-27-applications-open','','','2026-03-06 00:00:00','2026-03-06 00:00:00','',0,'http://localhost:8000/?notice=m-tech-admission-2026-27-applications-open',0,'notice','',0),(32,0,'2026-02-28 00:00:00','2026-02-28 00:00:00','','PhD Admission Schedule 2026-27 (Autumn Session)','','publish','closed','closed','','phd-admission-schedule-2026-27-autumn-session','','','2026-02-28 00:00:00','2026-02-28 00:00:00','',0,'http://localhost:8000/?notice=phd-admission-schedule-2026-27-autumn-session',0,'notice','',0),(33,0,'2026-02-15 00:00:00','2026-02-15 00:00:00','','Convocation 2026 — Official Date, Schedule and Guidelines','','publish','closed','closed','','convocation-2026-official-date-schedule-and-guidelines','','','2026-02-15 00:00:00','2026-02-15 00:00:00','',0,'http://localhost:8000/?notice=convocation-2026-official-date-schedule-and-guidelines',0,'notice','',0),(34,0,'2026-09-07 09:45:19','2026-09-07 09:45:19','GSSST faculty member Prof. Arundhati Sharma has joined the prestigious editorial board.','Prof. Arundhati Sharma Appointed to IEEE Editorial Board on Wireless Communications','','publish','closed','closed','','prof-arundhati-sharma-appointed-to-ieee-editorial-board-on-wireless-communications','','','2026-09-07 09:45:19','2026-09-07 09:45:19','',0,'http://localhost:8000/?news=prof-arundhati-sharma-appointed-to-ieee-editorial-board-on-wireless-communications',0,'news','',0),(35,0,'2026-09-07 09:45:20','2026-09-07 09:45:20','A significant milestone in wireless communication research at IIT Kharagpur.','GSSST Research Team Secures ₹4.5 Crore National Grant for Next-Gen 6G Testbed','','publish','closed','closed','','gssst-research-team-secures-%e2%82%b94-5-crore-national-grant-for-next-gen-6g-testbed','','','2026-09-07 09:45:20','2026-09-07 09:45:20','',0,'http://localhost:8000/?news=gssst-research-team-secures-%e2%82%b94-5-crore-national-grant-for-next-gen-6g-testbed',0,'news','',0),(36,0,'2026-09-07 09:45:20','2026-09-07 09:45:20','Interdisciplinary team recognized for high-performance edge computing solutions.','School of Technology Students Win First Prize at National Smart Infrastructure Hackathon','','publish','closed','closed','','school-of-technology-students-win-first-prize-at-national-smart-infrastructure-hackathon','','','2026-09-07 09:45:20','2026-09-07 09:45:20','',0,'http://localhost:8000/?news=school-of-technology-students-win-first-prize-at-national-smart-infrastructure-hackathon',0,'news','',0),(37,0,'2026-09-07 09:45:20','2026-09-07 09:45:20','Join leading minds across industry and academia at the upcoming GSSST Symposium.','Annual Technology Symposium 2026: Call for Papers &amp; Registrations','','publish','closed','closed','','annual-technology-symposium-2026-call-for-papers-registrations','','','2026-09-07 09:45:20','2026-09-07 09:45:20','',0,'http://localhost:8000/?event=annual-technology-symposium-2026-call-for-papers-registrations',0,'event','',0),(38,0,'2026-09-07 09:45:20','2026-09-07 09:45:20','Three-day hands-on workshop on quantum simulation and photonics.','International Workshop on Quantum Computing and High-Speed Optical Networks','','publish','closed','closed','','international-workshop-on-quantum-computing-and-high-speed-optical-networks','','','2026-09-07 09:45:20','2026-09-07 09:45:20','',0,'http://localhost:8000/?event=international-workshop-on-quantum-computing-and-high-speed-optical-networks',0,'event','',0),(39,0,'2026-09-07 09:45:20','2026-09-07 09:45:20','Special guest seminar hosted at GSSST auditorium.','Distinguished Guest Lecture: Next-Gen Edge AI Architecture by Dr. Rajesh Patel','','publish','closed','closed','','distinguished-guest-lecture-next-gen-edge-ai-architecture-by-dr-rajesh-patel','','','2026-09-07 09:45:20','2026-09-07 09:45:20','',0,'http://localhost:8000/?event=distinguished-guest-lecture-next-gen-edge-ai-architecture-by-dr-rajesh-patel',0,'event','',0),(40,1,'2026-09-07 10:52:01','0000-00-00 00:00:00','','Auto Draft','','auto-draft','closed','closed','','','','','2026-09-07 10:52:01','0000-00-00 00:00:00','',0,'http://localhost:8000/?post_type=notice&p=40',0,'notice','',0),(41,1,'2026-09-07 10:52:10','0000-00-00 00:00:00','','Auto Draft','','auto-draft','closed','closed','','','','','2026-09-07 10:52:10','0000-00-00 00:00:00','',0,'http://localhost:8000/?post_type=news&p=41',0,'news','',0),(42,1,'2026-09-07 10:52:13','0000-00-00 00:00:00','','Auto Draft','','auto-draft','closed','closed','','','','','2026-09-07 10:52:13','0000-00-00 00:00:00','',0,'http://localhost:8000/?post_type=event&p=42',0,'event','',0);
/*!40000 ALTER TABLE `wp_posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_term_relationships`
--

DROP TABLE IF EXISTS `wp_term_relationships`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_term_relationships` (
  `object_id` bigint unsigned NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint unsigned NOT NULL DEFAULT '0',
  `term_order` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  KEY `term_taxonomy_id` (`term_taxonomy_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_relationships`
--

LOCK TABLES `wp_term_relationships` WRITE;
/*!40000 ALTER TABLE `wp_term_relationships` DISABLE KEYS */;
INSERT INTO `wp_term_relationships` VALUES (1,1,0),(5,2,0),(8,1,0),(10,3,0),(11,3,0),(12,3,0),(13,3,0),(14,3,0),(15,4,0),(16,4,0),(17,4,0),(18,4,0),(19,4,0),(20,4,0),(21,4,0),(22,4,0),(23,4,0),(24,4,0),(25,5,0),(26,5,0),(27,5,0),(28,6,0),(29,6,0),(30,6,0);
/*!40000 ALTER TABLE `wp_term_relationships` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_term_taxonomy`
--

DROP TABLE IF EXISTS `wp_term_taxonomy`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint unsigned NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `parent` bigint unsigned NOT NULL DEFAULT '0',
  `count` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_taxonomy_id`),
  UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  KEY `taxonomy` (`taxonomy`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_term_taxonomy`
--

LOCK TABLES `wp_term_taxonomy` WRITE;
/*!40000 ALTER TABLE `wp_term_taxonomy` DISABLE KEYS */;
INSERT INTO `wp_term_taxonomy` VALUES (1,1,'category','',0,2),(2,2,'wp_theme','',0,1),(3,3,'nav_menu','',0,5),(4,4,'nav_menu','',0,10),(5,5,'nav_menu','',0,3),(6,6,'nav_menu','',0,3);
/*!40000 ALTER TABLE `wp_term_taxonomy` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_termmeta`
--

DROP TABLE IF EXISTS `wp_termmeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_termmeta` (
  `meta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `term_id` bigint unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`meta_id`),
  KEY `term_id` (`term_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_termmeta`
--

LOCK TABLES `wp_termmeta` WRITE;
/*!40000 ALTER TABLE `wp_termmeta` DISABLE KEYS */;
/*!40000 ALTER TABLE `wp_termmeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_terms`
--

DROP TABLE IF EXISTS `wp_terms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_terms` (
  `term_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `term_group` bigint NOT NULL DEFAULT '0',
  PRIMARY KEY (`term_id`),
  KEY `slug` (`slug`(191)),
  KEY `name` (`name`(191))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_terms`
--

LOCK TABLES `wp_terms` WRITE;
/*!40000 ALTER TABLE `wp_terms` DISABLE KEYS */;
INSERT INTO `wp_terms` VALUES (1,'Uncategorized','uncategorized',0),(2,'cic-theme','cic-theme',0),(3,'Top Menu','top-menu',0),(4,'Primary Menu','primary-menu',0),(5,'Footer Links','footer-links',0),(6,'Footer Academics','footer-academics',0);
/*!40000 ALTER TABLE `wp_terms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_usermeta`
--

DROP TABLE IF EXISTS `wp_usermeta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci,
  PRIMARY KEY (`umeta_id`),
  KEY `user_id` (`user_id`),
  KEY `meta_key` (`meta_key`(191))
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_usermeta`
--

LOCK TABLES `wp_usermeta` WRITE;
/*!40000 ALTER TABLE `wp_usermeta` DISABLE KEYS */;
INSERT INTO `wp_usermeta` VALUES (1,1,'nickname','admin'),(2,1,'first_name',''),(3,1,'last_name',''),(4,1,'description',''),(5,1,'rich_editing','true'),(6,1,'syntax_highlighting','true'),(7,1,'infinite_scrolling','true'),(8,1,'comment_shortcuts','false'),(9,1,'admin_color','light'),(10,1,'use_ssl','0'),(11,1,'show_admin_bar_front','true'),(12,1,'locale',''),(13,1,'wp_capabilities','a:1:{s:13:\"administrator\";b:1;}'),(14,1,'wp_user_level','10'),(15,1,'dismissed_wp_pointers',''),(16,1,'show_welcome_panel','1'),(18,1,'wp_dashboard_quick_press_last_post_id','4'),(19,1,'community-events-location','a:1:{s:2:\"ip\";s:10:\"172.19.0.0\";}'),(20,1,'wp_persisted_preferences','a:3:{s:4:\"core\";a:1:{s:26:\"isComplementaryAreaVisible\";b:1;}s:9:\"_modified\";s:24:\"2026-09-07T05:31:33.833Z\";s:14:\"core/edit-post\";a:1:{s:12:\"welcomeGuide\";b:0;}}'),(24,1,'session_tokens','a:1:{s:64:\"a198788744570b013967bae9b64386aa82e3f8c1312ac8475fa23b43602b9544\";a:4:{s:10:\"expiration\";i:1789142088;s:2:\"ip\";s:10:\"172.19.0.1\";s:2:\"ua\";s:101:\"Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/152.0.0.0 Safari/537.36\";s:5:\"login\";i:1788969288;}}'),(25,1,'wp_user-settings','mfold=o&libraryContent=browse'),(26,1,'wp_user-settings-time','1788791833');
/*!40000 ALTER TABLE `wp_usermeta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `wp_users`
--

DROP TABLE IF EXISTS `wp_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `wp_users` (
  `ID` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_login` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_status` int NOT NULL DEFAULT '0',
  `display_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`ID`),
  KEY `user_login_key` (`user_login`),
  KEY `user_nicename` (`user_nicename`),
  KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `wp_users`
--

LOCK TABLES `wp_users` WRITE;
/*!40000 ALTER TABLE `wp_users` DISABLE KEYS */;
INSERT INTO `wp_users` VALUES (1,'admin','$wp$2y$10$saVYcC0W.40icgH0HgaFLe0xnKN/E25hbqvk4EdnsTJsMyPO3HDVi','admin','admin@example.com','http://localhost:8000','2026-09-07 05:21:21','',0,'admin');
/*!40000 ALTER TABLE `wp_users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-09-10  9:43:17
