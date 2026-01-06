-- MySQL dump 10.13  Distrib 8.0.44, for Linux (x86_64)
--
-- Host: localhost    Database: portal_paciente
-- ------------------------------------------------------
-- Server version	8.0.44-0ubuntu0.24.04.2

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
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `appointments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `medico` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `especialidade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data_agendamento` datetime NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'agendado',
  `observacoes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_tenant_id_foreign` (`tenant_id`),
  KEY `appointments_user_id_foreign` (`user_id`),
  CONSTRAINT `appointments_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (1,2,11,'Roberto Carlos','Cardiologista','2026-01-05 08:30:00','cancelado','Teste','2026-01-03 16:56:27','2026-01-03 22:06:17'),(2,2,6,'Roberto Carlos','Cardiologista','2026-01-05 10:00:00','agendado','teste e-mail','2026-01-03 17:21:24','2026-01-03 17:21:24'),(3,3,14,'Pedro','Cardiologista','2026-01-06 10:00:00','agendado','asdasdas','2026-01-03 22:21:42','2026-01-03 22:21:42');
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Cache:all:1_hour','a:3:{i:0;O:8:\"stdClass\":2:{s:4:\"hits\";i:0;s:6:\"misses\";i:0;}i:1;d:1.733681;i:2;s:19:\"2026-01-05 20:10:06\";}',1767643811),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Cache:all:6_hours','a:3:{i:0;O:8:\"stdClass\":2:{s:4:\"hits\";i:0;s:6:\"misses\";i:0;}i:1;d:1.304125;i:2;s:19:\"2026-01-05 19:13:31\";}',1767640416),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Cache:all:7_days','a:3:{i:0;O:8:\"stdClass\":2:{s:4:\"hits\";i:0;s:6:\"misses\";i:0;}i:1;d:1.198723;i:2;s:19:\"2026-01-05 19:13:34\";}',1767640419),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Cache:keys:1_hour','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.824367;i:2;s:19:\"2026-01-05 20:10:06\";}',1767643811),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Cache:keys:6_hours','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.880523;i:2;s:19:\"2026-01-05 19:13:31\";}',1767640416),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Cache:keys:7_days','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.707676;i:2;s:19:\"2026-01-05 19:13:34\";}',1767640419),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Exceptions:count:1_hour','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.607399;i:2;s:19:\"2026-01-05 20:10:06\";}',1767643811),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Exceptions:count:6_hours','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:2.06852;i:2;s:19:\"2026-01-05 19:13:31\";}',1767640416),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Exceptions:count:7_days','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.768651;i:2;s:19:\"2026-01-05 19:13:34\";}',1767640419),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Queues::1_hour','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.399978;i:2;s:19:\"2026-01-05 20:10:06\";}',1767643811),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Queues::6_hours','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.3153;i:2;s:19:\"2026-01-05 19:13:31\";}',1767640416),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Queues::7_days','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.497277;i:2;s:19:\"2026-01-05 19:13:34\";}',1767640419),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Servers::1_hour','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{s:15:\"portal-paciente\";O:8:\"stdClass\":9:{s:4:\"name\";s:15:\"portal-paciente\";s:11:\"cpu_current\";i:2;s:3:\"cpu\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:60:{s:19:\"2026-01-05 19:11:00\";N;s:19:\"2026-01-05 19:12:00\";N;s:19:\"2026-01-05 19:13:00\";s:4:\"1.50\";s:19:\"2026-01-05 19:14:00\";s:4:\"5.00\";s:19:\"2026-01-05 19:15:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:16:00\";s:4:\"2.00\";s:19:\"2026-01-05 19:17:00\";s:4:\"1.50\";s:19:\"2026-01-05 19:18:00\";s:4:\"8.50\";s:19:\"2026-01-05 19:19:00\";s:4:\"1.50\";s:19:\"2026-01-05 19:20:00\";s:4:\"1.50\";s:19:\"2026-01-05 19:21:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:22:00\";s:4:\"2.50\";s:19:\"2026-01-05 19:23:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:24:00\";s:4:\"2.50\";s:19:\"2026-01-05 19:25:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:26:00\";s:4:\"1.50\";s:19:\"2026-01-05 19:27:00\";s:4:\"0.50\";s:19:\"2026-01-05 19:28:00\";s:4:\"1.50\";s:19:\"2026-01-05 19:29:00\";s:4:\"2.00\";s:19:\"2026-01-05 19:30:00\";s:4:\"2.00\";s:19:\"2026-01-05 19:31:00\";s:4:\"1.50\";s:19:\"2026-01-05 19:32:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:33:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:34:00\";s:4:\"0.50\";s:19:\"2026-01-05 19:35:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:36:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:37:00\";s:4:\"0.50\";s:19:\"2026-01-05 19:38:00\";s:4:\"1.50\";s:19:\"2026-01-05 19:39:00\";s:4:\"0.50\";s:19:\"2026-01-05 19:40:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:41:00\";s:4:\"0.50\";s:19:\"2026-01-05 19:42:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:43:00\";s:4:\"2.50\";s:19:\"2026-01-05 19:44:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:45:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:46:00\";s:4:\"3.00\";s:19:\"2026-01-05 19:47:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:48:00\";s:4:\"0.50\";s:19:\"2026-01-05 19:49:00\";s:4:\"2.50\";s:19:\"2026-01-05 19:50:00\";s:4:\"0.00\";s:19:\"2026-01-05 19:51:00\";s:4:\"2.50\";s:19:\"2026-01-05 19:52:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:53:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:54:00\";s:4:\"1.00\";s:19:\"2026-01-05 19:55:00\";s:4:\"1.50\";s:19:\"2026-01-05 19:56:00\";s:4:\"1.50\";s:19:\"2026-01-05 19:57:00\";s:4:\"2.50\";s:19:\"2026-01-05 19:58:00\";s:4:\"1.50\";s:19:\"2026-01-05 19:59:00\";s:4:\"1.50\";s:19:\"2026-01-05 20:00:00\";s:4:\"1.50\";s:19:\"2026-01-05 20:01:00\";s:4:\"1.00\";s:19:\"2026-01-05 20:02:00\";s:4:\"1.00\";s:19:\"2026-01-05 20:03:00\";s:4:\"2.00\";s:19:\"2026-01-05 20:04:00\";s:4:\"0.50\";s:19:\"2026-01-05 20:05:00\";s:4:\"3.00\";s:19:\"2026-01-05 20:06:00\";s:4:\"2.00\";s:19:\"2026-01-05 20:07:00\";s:4:\"2.00\";s:19:\"2026-01-05 20:08:00\";s:4:\"0.50\";s:19:\"2026-01-05 20:09:00\";s:4:\"1.00\";s:19:\"2026-01-05 20:10:00\";s:4:\"2.00\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:14:\"memory_current\";i:1303;s:12:\"memory_total\";i:7941;s:6:\"memory\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:60:{s:19:\"2026-01-05 19:11:00\";N;s:19:\"2026-01-05 19:12:00\";N;s:19:\"2026-01-05 19:13:00\";s:7:\"1891.50\";s:19:\"2026-01-05 19:14:00\";s:7:\"1939.50\";s:19:\"2026-01-05 19:15:00\";s:7:\"1985.00\";s:19:\"2026-01-05 19:16:00\";s:7:\"1871.25\";s:19:\"2026-01-05 19:17:00\";s:7:\"1871.00\";s:19:\"2026-01-05 19:18:00\";s:7:\"1876.25\";s:19:\"2026-01-05 19:19:00\";s:7:\"1870.00\";s:19:\"2026-01-05 19:20:00\";s:7:\"1880.75\";s:19:\"2026-01-05 19:21:00\";s:7:\"1883.00\";s:19:\"2026-01-05 19:22:00\";s:7:\"1882.50\";s:19:\"2026-01-05 19:23:00\";s:7:\"1887.25\";s:19:\"2026-01-05 19:24:00\";s:7:\"1889.75\";s:19:\"2026-01-05 19:25:00\";s:7:\"1889.75\";s:19:\"2026-01-05 19:26:00\";s:7:\"1890.00\";s:19:\"2026-01-05 19:27:00\";s:7:\"1892.00\";s:19:\"2026-01-05 19:28:00\";s:7:\"1886.50\";s:19:\"2026-01-05 19:29:00\";s:7:\"1888.50\";s:19:\"2026-01-05 19:30:00\";s:7:\"1893.75\";s:19:\"2026-01-05 19:31:00\";s:7:\"1894.50\";s:19:\"2026-01-05 19:32:00\";s:7:\"1893.75\";s:19:\"2026-01-05 19:33:00\";s:7:\"1894.50\";s:19:\"2026-01-05 19:34:00\";s:7:\"1893.50\";s:19:\"2026-01-05 19:35:00\";s:7:\"1894.75\";s:19:\"2026-01-05 19:36:00\";s:7:\"1889.25\";s:19:\"2026-01-05 19:37:00\";s:7:\"1893.00\";s:19:\"2026-01-05 19:38:00\";s:7:\"1893.00\";s:19:\"2026-01-05 19:39:00\";s:7:\"1899.75\";s:19:\"2026-01-05 19:40:00\";s:7:\"1899.00\";s:19:\"2026-01-05 19:41:00\";s:7:\"1896.75\";s:19:\"2026-01-05 19:42:00\";s:7:\"1897.00\";s:19:\"2026-01-05 19:43:00\";s:7:\"1896.50\";s:19:\"2026-01-05 19:44:00\";s:7:\"1895.75\";s:19:\"2026-01-05 19:45:00\";s:7:\"1894.25\";s:19:\"2026-01-05 19:46:00\";s:7:\"1895.50\";s:19:\"2026-01-05 19:47:00\";s:7:\"1897.75\";s:19:\"2026-01-05 19:48:00\";s:7:\"1900.25\";s:19:\"2026-01-05 19:49:00\";s:7:\"1922.25\";s:19:\"2026-01-05 19:50:00\";s:7:\"1795.25\";s:19:\"2026-01-05 19:51:00\";s:7:\"1389.00\";s:19:\"2026-01-05 19:52:00\";s:7:\"1388.50\";s:19:\"2026-01-05 19:53:00\";s:7:\"1389.00\";s:19:\"2026-01-05 19:54:00\";s:7:\"1394.00\";s:19:\"2026-01-05 19:55:00\";s:7:\"1372.50\";s:19:\"2026-01-05 19:56:00\";s:7:\"1273.50\";s:19:\"2026-01-05 19:57:00\";s:7:\"1273.25\";s:19:\"2026-01-05 19:58:00\";s:7:\"1277.50\";s:19:\"2026-01-05 19:59:00\";s:7:\"1288.25\";s:19:\"2026-01-05 20:00:00\";s:7:\"1286.50\";s:19:\"2026-01-05 20:01:00\";s:7:\"1287.50\";s:19:\"2026-01-05 20:02:00\";s:7:\"1288.50\";s:19:\"2026-01-05 20:03:00\";s:7:\"1288.00\";s:19:\"2026-01-05 20:04:00\";s:7:\"1285.75\";s:19:\"2026-01-05 20:05:00\";s:7:\"1295.75\";s:19:\"2026-01-05 20:06:00\";s:7:\"1286.25\";s:19:\"2026-01-05 20:07:00\";s:7:\"1286.00\";s:19:\"2026-01-05 20:08:00\";s:7:\"1281.00\";s:19:\"2026-01-05 20:09:00\";s:7:\"1287.75\";s:19:\"2026-01-05 20:10:00\";s:7:\"1303.00\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:7:\"storage\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:8:\"stdClass\":3:{s:9:\"directory\";s:1:\"/\";s:5:\"total\";i:31066;s:4:\"used\";i:11325;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:10:\"updated_at\";O:22:\"Carbon\\CarbonImmutable\":3:{s:4:\"date\";s:26:\"2026-01-05 20:10:04.000000\";s:13:\"timezone_type\";i:1;s:8:\"timezone\";s:6:\"+00:00\";}s:17:\"recently_reported\";b:1;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:3.784298;i:2;s:19:\"2026-01-05 20:10:06\";}',1767643811),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Servers::6_hours','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{s:15:\"portal-paciente\";O:8:\"stdClass\":9:{s:4:\"name\";s:15:\"portal-paciente\";s:11:\"cpu_current\";i:0;s:3:\"cpu\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:60:{s:19:\"2026-01-05 13:18:00\";N;s:19:\"2026-01-05 13:24:00\";N;s:19:\"2026-01-05 13:30:00\";N;s:19:\"2026-01-05 13:36:00\";N;s:19:\"2026-01-05 13:42:00\";N;s:19:\"2026-01-05 13:48:00\";N;s:19:\"2026-01-05 13:54:00\";N;s:19:\"2026-01-05 14:00:00\";N;s:19:\"2026-01-05 14:06:00\";N;s:19:\"2026-01-05 14:12:00\";N;s:19:\"2026-01-05 14:18:00\";N;s:19:\"2026-01-05 14:24:00\";N;s:19:\"2026-01-05 14:30:00\";N;s:19:\"2026-01-05 14:36:00\";N;s:19:\"2026-01-05 14:42:00\";N;s:19:\"2026-01-05 14:48:00\";N;s:19:\"2026-01-05 14:54:00\";N;s:19:\"2026-01-05 15:00:00\";N;s:19:\"2026-01-05 15:06:00\";N;s:19:\"2026-01-05 15:12:00\";N;s:19:\"2026-01-05 15:18:00\";N;s:19:\"2026-01-05 15:24:00\";N;s:19:\"2026-01-05 15:30:00\";N;s:19:\"2026-01-05 15:36:00\";N;s:19:\"2026-01-05 15:42:00\";N;s:19:\"2026-01-05 15:48:00\";N;s:19:\"2026-01-05 15:54:00\";N;s:19:\"2026-01-05 16:00:00\";N;s:19:\"2026-01-05 16:06:00\";N;s:19:\"2026-01-05 16:12:00\";N;s:19:\"2026-01-05 16:18:00\";N;s:19:\"2026-01-05 16:24:00\";N;s:19:\"2026-01-05 16:30:00\";N;s:19:\"2026-01-05 16:36:00\";N;s:19:\"2026-01-05 16:42:00\";N;s:19:\"2026-01-05 16:48:00\";N;s:19:\"2026-01-05 16:54:00\";N;s:19:\"2026-01-05 17:00:00\";N;s:19:\"2026-01-05 17:06:00\";N;s:19:\"2026-01-05 17:12:00\";N;s:19:\"2026-01-05 17:18:00\";N;s:19:\"2026-01-05 17:24:00\";N;s:19:\"2026-01-05 17:30:00\";N;s:19:\"2026-01-05 17:36:00\";N;s:19:\"2026-01-05 17:42:00\";N;s:19:\"2026-01-05 17:48:00\";N;s:19:\"2026-01-05 17:54:00\";N;s:19:\"2026-01-05 18:00:00\";N;s:19:\"2026-01-05 18:06:00\";N;s:19:\"2026-01-05 18:12:00\";N;s:19:\"2026-01-05 18:18:00\";N;s:19:\"2026-01-05 18:24:00\";N;s:19:\"2026-01-05 18:30:00\";N;s:19:\"2026-01-05 18:36:00\";N;s:19:\"2026-01-05 18:42:00\";N;s:19:\"2026-01-05 18:48:00\";N;s:19:\"2026-01-05 18:54:00\";N;s:19:\"2026-01-05 19:00:00\";N;s:19:\"2026-01-05 19:06:00\";N;s:19:\"2026-01-05 19:12:00\";s:4:\"1.00\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:14:\"memory_current\";i:1890;s:12:\"memory_total\";i:7941;s:6:\"memory\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:60:{s:19:\"2026-01-05 13:18:00\";N;s:19:\"2026-01-05 13:24:00\";N;s:19:\"2026-01-05 13:30:00\";N;s:19:\"2026-01-05 13:36:00\";N;s:19:\"2026-01-05 13:42:00\";N;s:19:\"2026-01-05 13:48:00\";N;s:19:\"2026-01-05 13:54:00\";N;s:19:\"2026-01-05 14:00:00\";N;s:19:\"2026-01-05 14:06:00\";N;s:19:\"2026-01-05 14:12:00\";N;s:19:\"2026-01-05 14:18:00\";N;s:19:\"2026-01-05 14:24:00\";N;s:19:\"2026-01-05 14:30:00\";N;s:19:\"2026-01-05 14:36:00\";N;s:19:\"2026-01-05 14:42:00\";N;s:19:\"2026-01-05 14:48:00\";N;s:19:\"2026-01-05 14:54:00\";N;s:19:\"2026-01-05 15:00:00\";N;s:19:\"2026-01-05 15:06:00\";N;s:19:\"2026-01-05 15:12:00\";N;s:19:\"2026-01-05 15:18:00\";N;s:19:\"2026-01-05 15:24:00\";N;s:19:\"2026-01-05 15:30:00\";N;s:19:\"2026-01-05 15:36:00\";N;s:19:\"2026-01-05 15:42:00\";N;s:19:\"2026-01-05 15:48:00\";N;s:19:\"2026-01-05 15:54:00\";N;s:19:\"2026-01-05 16:00:00\";N;s:19:\"2026-01-05 16:06:00\";N;s:19:\"2026-01-05 16:12:00\";N;s:19:\"2026-01-05 16:18:00\";N;s:19:\"2026-01-05 16:24:00\";N;s:19:\"2026-01-05 16:30:00\";N;s:19:\"2026-01-05 16:36:00\";N;s:19:\"2026-01-05 16:42:00\";N;s:19:\"2026-01-05 16:48:00\";N;s:19:\"2026-01-05 16:54:00\";N;s:19:\"2026-01-05 17:00:00\";N;s:19:\"2026-01-05 17:06:00\";N;s:19:\"2026-01-05 17:12:00\";N;s:19:\"2026-01-05 17:18:00\";N;s:19:\"2026-01-05 17:24:00\";N;s:19:\"2026-01-05 17:30:00\";N;s:19:\"2026-01-05 17:36:00\";N;s:19:\"2026-01-05 17:42:00\";N;s:19:\"2026-01-05 17:48:00\";N;s:19:\"2026-01-05 17:54:00\";N;s:19:\"2026-01-05 18:00:00\";N;s:19:\"2026-01-05 18:06:00\";N;s:19:\"2026-01-05 18:12:00\";N;s:19:\"2026-01-05 18:18:00\";N;s:19:\"2026-01-05 18:24:00\";N;s:19:\"2026-01-05 18:30:00\";N;s:19:\"2026-01-05 18:36:00\";N;s:19:\"2026-01-05 18:42:00\";N;s:19:\"2026-01-05 18:48:00\";N;s:19:\"2026-01-05 18:54:00\";N;s:19:\"2026-01-05 19:00:00\";N;s:19:\"2026-01-05 19:06:00\";N;s:19:\"2026-01-05 19:12:00\";s:7:\"1892.00\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:7:\"storage\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:8:\"stdClass\":3:{s:9:\"directory\";s:1:\"/\";s:5:\"total\";i:31066;s:4:\"used\";i:11284;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:10:\"updated_at\";O:22:\"Carbon\\CarbonImmutable\":3:{s:4:\"date\";s:26:\"2026-01-05 19:13:20.000000\";s:13:\"timezone_type\";i:1;s:8:\"timezone\";s:6:\"+00:00\";}s:17:\"recently_reported\";b:1;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:2.572378;i:2;s:19:\"2026-01-05 19:13:31\";}',1767640416),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Servers::7_days','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{s:15:\"portal-paciente\";O:8:\"stdClass\":9:{s:4:\"name\";s:15:\"portal-paciente\";s:11:\"cpu_current\";i:0;s:3:\"cpu\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:60:{s:19:\"2025-12-29 21:36:00\";N;s:19:\"2025-12-30 00:24:00\";N;s:19:\"2025-12-30 03:12:00\";N;s:19:\"2025-12-30 06:00:00\";N;s:19:\"2025-12-30 08:48:00\";N;s:19:\"2025-12-30 11:36:00\";N;s:19:\"2025-12-30 14:24:00\";N;s:19:\"2025-12-30 17:12:00\";N;s:19:\"2025-12-30 20:00:00\";N;s:19:\"2025-12-30 22:48:00\";N;s:19:\"2025-12-31 01:36:00\";N;s:19:\"2025-12-31 04:24:00\";N;s:19:\"2025-12-31 07:12:00\";N;s:19:\"2025-12-31 10:00:00\";N;s:19:\"2025-12-31 12:48:00\";N;s:19:\"2025-12-31 15:36:00\";N;s:19:\"2025-12-31 18:24:00\";N;s:19:\"2025-12-31 21:12:00\";N;s:19:\"2026-01-01 00:00:00\";N;s:19:\"2026-01-01 02:48:00\";N;s:19:\"2026-01-01 05:36:00\";N;s:19:\"2026-01-01 08:24:00\";N;s:19:\"2026-01-01 11:12:00\";N;s:19:\"2026-01-01 14:00:00\";N;s:19:\"2026-01-01 16:48:00\";N;s:19:\"2026-01-01 19:36:00\";N;s:19:\"2026-01-01 22:24:00\";N;s:19:\"2026-01-02 01:12:00\";N;s:19:\"2026-01-02 04:00:00\";N;s:19:\"2026-01-02 06:48:00\";N;s:19:\"2026-01-02 09:36:00\";N;s:19:\"2026-01-02 12:24:00\";N;s:19:\"2026-01-02 15:12:00\";N;s:19:\"2026-01-02 18:00:00\";N;s:19:\"2026-01-02 20:48:00\";N;s:19:\"2026-01-02 23:36:00\";N;s:19:\"2026-01-03 02:24:00\";N;s:19:\"2026-01-03 05:12:00\";N;s:19:\"2026-01-03 08:00:00\";N;s:19:\"2026-01-03 10:48:00\";N;s:19:\"2026-01-03 13:36:00\";N;s:19:\"2026-01-03 16:24:00\";N;s:19:\"2026-01-03 19:12:00\";N;s:19:\"2026-01-03 22:00:00\";N;s:19:\"2026-01-04 00:48:00\";N;s:19:\"2026-01-04 03:36:00\";N;s:19:\"2026-01-04 06:24:00\";N;s:19:\"2026-01-04 09:12:00\";N;s:19:\"2026-01-04 12:00:00\";N;s:19:\"2026-01-04 14:48:00\";N;s:19:\"2026-01-04 17:36:00\";N;s:19:\"2026-01-04 20:24:00\";N;s:19:\"2026-01-04 23:12:00\";N;s:19:\"2026-01-05 02:00:00\";N;s:19:\"2026-01-05 04:48:00\";N;s:19:\"2026-01-05 07:36:00\";N;s:19:\"2026-01-05 10:24:00\";N;s:19:\"2026-01-05 13:12:00\";N;s:19:\"2026-01-05 16:00:00\";N;s:19:\"2026-01-05 18:48:00\";s:4:\"1.00\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:14:\"memory_current\";i:1890;s:12:\"memory_total\";i:7941;s:6:\"memory\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:60:{s:19:\"2025-12-29 21:36:00\";N;s:19:\"2025-12-30 00:24:00\";N;s:19:\"2025-12-30 03:12:00\";N;s:19:\"2025-12-30 06:00:00\";N;s:19:\"2025-12-30 08:48:00\";N;s:19:\"2025-12-30 11:36:00\";N;s:19:\"2025-12-30 14:24:00\";N;s:19:\"2025-12-30 17:12:00\";N;s:19:\"2025-12-30 20:00:00\";N;s:19:\"2025-12-30 22:48:00\";N;s:19:\"2025-12-31 01:36:00\";N;s:19:\"2025-12-31 04:24:00\";N;s:19:\"2025-12-31 07:12:00\";N;s:19:\"2025-12-31 10:00:00\";N;s:19:\"2025-12-31 12:48:00\";N;s:19:\"2025-12-31 15:36:00\";N;s:19:\"2025-12-31 18:24:00\";N;s:19:\"2025-12-31 21:12:00\";N;s:19:\"2026-01-01 00:00:00\";N;s:19:\"2026-01-01 02:48:00\";N;s:19:\"2026-01-01 05:36:00\";N;s:19:\"2026-01-01 08:24:00\";N;s:19:\"2026-01-01 11:12:00\";N;s:19:\"2026-01-01 14:00:00\";N;s:19:\"2026-01-01 16:48:00\";N;s:19:\"2026-01-01 19:36:00\";N;s:19:\"2026-01-01 22:24:00\";N;s:19:\"2026-01-02 01:12:00\";N;s:19:\"2026-01-02 04:00:00\";N;s:19:\"2026-01-02 06:48:00\";N;s:19:\"2026-01-02 09:36:00\";N;s:19:\"2026-01-02 12:24:00\";N;s:19:\"2026-01-02 15:12:00\";N;s:19:\"2026-01-02 18:00:00\";N;s:19:\"2026-01-02 20:48:00\";N;s:19:\"2026-01-02 23:36:00\";N;s:19:\"2026-01-03 02:24:00\";N;s:19:\"2026-01-03 05:12:00\";N;s:19:\"2026-01-03 08:00:00\";N;s:19:\"2026-01-03 10:48:00\";N;s:19:\"2026-01-03 13:36:00\";N;s:19:\"2026-01-03 16:24:00\";N;s:19:\"2026-01-03 19:12:00\";N;s:19:\"2026-01-03 22:00:00\";N;s:19:\"2026-01-04 00:48:00\";N;s:19:\"2026-01-04 03:36:00\";N;s:19:\"2026-01-04 06:24:00\";N;s:19:\"2026-01-04 09:12:00\";N;s:19:\"2026-01-04 12:00:00\";N;s:19:\"2026-01-04 14:48:00\";N;s:19:\"2026-01-04 17:36:00\";N;s:19:\"2026-01-04 20:24:00\";N;s:19:\"2026-01-04 23:12:00\";N;s:19:\"2026-01-05 02:00:00\";N;s:19:\"2026-01-05 04:48:00\";N;s:19:\"2026-01-05 07:36:00\";N;s:19:\"2026-01-05 10:24:00\";N;s:19:\"2026-01-05 13:12:00\";N;s:19:\"2026-01-05 16:00:00\";N;s:19:\"2026-01-05 18:48:00\";s:7:\"1892.00\";}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:7:\"storage\";O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:8:\"stdClass\":3:{s:9:\"directory\";s:1:\"/\";s:5:\"total\";i:31066;s:4:\"used\";i:11284;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}s:10:\"updated_at\";O:22:\"Carbon\\CarbonImmutable\":3:{s:4:\"date\";s:26:\"2026-01-05 19:13:20.000000\";s:13:\"timezone_type\";i:1;s:8:\"timezone\";s:6:\"+00:00\";}s:17:\"recently_reported\";b:1;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:2.159263;i:2;s:19:\"2026-01-05 19:13:34\";}',1767640419),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\SlowJobs:slowest:1_hour','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.900718;i:2;s:19:\"2026-01-05 20:10:04\";}',1767643809),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\SlowOutgoingRequests:slowest:1_hour','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:2.476625;i:2;s:19:\"2026-01-05 20:10:04\";}',1767643809),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\SlowQueries:slowest:1_hour','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.721039;i:2;s:19:\"2026-01-05 20:10:06\";}',1767643811),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\SlowQueries:slowest:6_hours','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.987604;i:2;s:19:\"2026-01-05 19:13:31\";}',1767640416),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\SlowQueries:slowest:7_days','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.663296;i:2;s:19:\"2026-01-05 19:13:34\";}',1767640419),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\SlowRequests:slowest:1_hour','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:8:\"stdClass\":6:{s:3:\"uri\";s:22:\"/hospital-exemplo-tasy\";s:6:\"method\";s:4:\"POST\";s:6:\"action\";s:20:\"via /livewire/update\";s:5:\"count\";s:4:\"2.00\";s:7:\"slowest\";s:7:\"2387.00\";s:9:\"threshold\";i:1000;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:2.115869;i:2;s:19:\"2026-01-05 20:10:06\";}',1767643811),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\SlowRequests:slowest:6_hours','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.965198;i:2;s:19:\"2026-01-05 19:13:31\";}',1767640416),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\SlowRequests:slowest:7_days','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:0:{}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:1.759588;i:2;s:19:\"2026-01-05 19:13:34\";}',1767640419),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Usage:requests:1_hour','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:2:{i:0;O:8:\"stdClass\":3:{s:3:\"key\";s:2:\"18\";s:4:\"user\";O:8:\"stdClass\":3:{s:4:\"name\";s:28:\"PRUEBA FABIANA INTERCONSULTA\";s:5:\"extra\";s:30:\"pedro@vivereconsultoria.com.br\";s:6:\"avatar\";s:97:\"https://gravatar.com/avatar/4eb2ddf2b788646fc620acbec09fd632182c7959ca47d42224b8b289e585799c?d=mp\";}s:5:\"count\";i:4;}i:1;O:8:\"stdClass\":3:{s:3:\"key\";s:1:\"6\";s:4:\"user\";O:8:\"stdClass\":3:{s:4:\"name\";s:34:\"Pedro Arcangelo Dias Lopes Polezel\";s:5:\"extra\";s:28:\"pedroarcangelo1997@gmail.com\";s:6:\"avatar\";s:97:\"https://gravatar.com/avatar/cc06666e41b423fd93db989521fad646d28bda44cee2fd035fd783f36f85d679?d=mp\";}s:5:\"count\";i:3;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:2.735804;i:2;s:19:\"2026-01-05 20:10:06\";}',1767643811),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Usage:requests:6_hours','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:8:\"stdClass\":3:{s:3:\"key\";s:1:\"6\";s:4:\"user\";O:8:\"stdClass\":3:{s:4:\"name\";s:34:\"Pedro Arcangelo Dias Lopes Polezel\";s:5:\"extra\";s:28:\"pedroarcangelo1997@gmail.com\";s:6:\"avatar\";s:97:\"https://gravatar.com/avatar/cc06666e41b423fd93db989521fad646d28bda44cee2fd035fd783f36f85d679?d=mp\";}s:5:\"count\";i:1;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:2.532609;i:2;s:19:\"2026-01-05 19:13:32\";}',1767640417),('laravel-cache-laravel:pulse:Laravel\\Pulse\\Livewire\\Usage:requests:7_days','a:3:{i:0;O:29:\"Illuminate\\Support\\Collection\":2:{s:8:\"\0*\0items\";a:1:{i:0;O:8:\"stdClass\":3:{s:3:\"key\";s:1:\"6\";s:4:\"user\";O:8:\"stdClass\":3:{s:4:\"name\";s:34:\"Pedro Arcangelo Dias Lopes Polezel\";s:5:\"extra\";s:28:\"pedroarcangelo1997@gmail.com\";s:6:\"avatar\";s:97:\"https://gravatar.com/avatar/cc06666e41b423fd93db989521fad646d28bda44cee2fd035fd783f36f85d679?d=mp\";}s:5:\"count\";i:1;}}s:28:\"\0*\0escapeWhenCastingToString\";b:0;}i:1;d:2.868983;i:2;s:19:\"2026-01-05 19:13:34\";}',1767640419),('laravel-cache-laravel:pulse:throttle:225TMJhu1aAoWdJs:Laravel\\Pulse\\Recorders\\Servers','i:1767644314;',1767644329),('laravel-cache-livewire-rate-limiter:bb12d1f4ed9d0c85adb60eb4a13cb2940f6e0d54','i:1;',1767638944),('laravel-cache-livewire-rate-limiter:bb12d1f4ed9d0c85adb60eb4a13cb2940f6e0d54:timer','i:1767638944;',1767638944);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
INSERT INTO `cache_locks` VALUES ('laravel-cache-laravel:pulse:check','fi1bbbBWx80Xh6ul',1767644325);
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exams`
--

DROP TABLE IF EXISTS `exams`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `exams` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tenant_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'analise',
  `file_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exams_tenant_id_foreign` (`tenant_id`),
  KEY `exams_user_id_foreign` (`user_id`),
  CONSTRAINT `exams_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exams_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exams`
--

LOCK TABLES `exams` WRITE;
/*!40000 ALTER TABLE `exams` DISABLE KEYS */;
INSERT INTO `exams` VALUES (1,2,11,'Raio-X Teste Local','2026-01-03','liberado','exames_locais/Termo de Indeferimento da Opção pelo Simples Nacional 5168919.pdf','2026-01-03 14:38:23','2026-01-03 14:38:23'),(2,3,14,'Raio-X Teste Local','2026-01-03','liberado','exames_locais/RelatorioConsultaDevedorPDF.pdf','2026-01-03 15:54:24','2026-01-03 15:54:24'),(3,3,12,'Raio-X Teste Local','2026-01-03','analise',NULL,'2026-01-03 15:54:39','2026-01-03 15:54:39'),(4,2,11,'Raio-X Teste Em Análise','2026-01-03','analise',NULL,'2026-01-03 15:55:17','2026-01-03 15:55:17'),(5,2,6,'Raio-X Teste Local','2026-01-04','liberado','exames_locais/Termo de Indeferimento da Opção pelo Simples Nacional 5168919.pdf','2026-01-04 21:46:38','2026-01-04 21:46:46');
/*!40000 ALTER TABLE `exams` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_02_025521_create_personal_access_tokens_table',1),(5,'2026_01_02_032133_add_tasy_fields_to_users_table',1),(6,'2026_01_02_234237_create_tenants_table',2),(7,'2026_01_03_033020_add_details_to_tenants_table',3),(8,'2026_01_03_002741_add_dates_to_tenants',4),(9,'2026_01_03_012259_add_deleted_at_to_tenants_table',5),(10,'2026_01_03_142233_create_exams_table',6),(11,'2026_01_03_162527_create_appointments_table',7),(12,'2026_01_03_173047_add_whatsapp_to_tenants_table',8),(13,'2026_01_04_190739_create_term_acceptances_table',9),(14,'2026_01_05_001844_add_profile_fields_to_users_table',10),(15,'2026_01_05_002647_add_complemento_to_users_table',11),(16,'2026_01_05_151042_create_pulse_tables',12);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
INSERT INTO `password_reset_tokens` VALUES ('pedroarcangelo1997@gmail.com','$2y$12$pl3x/5SdERIIKdlqGW7jQOC0YQ6UlKHe6BVIEIJk0PppCLN9V2V7S','2026-01-05 10:24:50');
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  KEY `personal_access_tokens_expires_at_index` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pulse_aggregates`
--

DROP TABLE IF EXISTS `pulse_aggregates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pulse_aggregates` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `bucket` int unsigned NOT NULL,
  `period` mediumint unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `aggregate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` decimal(20,2) NOT NULL,
  `count` int unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pulse_aggregates_bucket_period_type_aggregate_key_hash_unique` (`bucket`,`period`,`type`,`aggregate`,`key_hash`),
  KEY `pulse_aggregates_period_bucket_index` (`period`,`bucket`),
  KEY `pulse_aggregates_type_index` (`type`),
  KEY `pulse_aggregates_period_type_aggregate_bucket_index` (`period`,`type`,`aggregate`,`bucket`)
) ENGINE=InnoDB AUTO_INCREMENT=2165 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pulse_aggregates`
--

LOCK TABLES `pulse_aggregates` WRITE;
/*!40000 ALTER TABLE `pulse_aggregates` DISABLE KEYS */;
INSERT INTO `pulse_aggregates` (`id`, `bucket`, `period`, `type`, `key`, `aggregate`, `value`, `count`) VALUES (1,1767640380,60,'cpu','portal-paciente','avg',1.50,4),(2,1767640320,360,'cpu','portal-paciente','avg',2.21,20),(3,1767640320,1440,'cpu','portal-paciente','avg',1.90,92),(4,1767638880,10080,'cpu','portal-paciente','avg',1.32,263),(5,1767640380,60,'memory','portal-paciente','avg',1891.50,4),(6,1767640320,360,'memory','portal-paciente','avg',1911.66,20),(7,1767640320,1440,'memory','portal-paciente','avg',1893.01,92),(8,1767638880,10080,'memory','portal-paciente','avg',1644.58,263),(9,1767640380,60,'user_request','6','count',1.00,NULL),(10,1767640320,360,'user_request','6','count',1.00,NULL),(11,1767640320,1440,'user_request','6','count',1.00,NULL),(12,1767638880,10080,'user_request','6','count',5.00,NULL),(37,1767640440,60,'cpu','portal-paciente','avg',5.00,4),(38,1767640440,60,'memory','portal-paciente','avg',1939.50,4),(69,1767640500,60,'cpu','portal-paciente','avg',1.00,4),(70,1767640500,60,'memory','portal-paciente','avg',1985.00,4),(101,1767640560,60,'cpu','portal-paciente','avg',2.00,4),(102,1767640560,60,'memory','portal-paciente','avg',1871.25,4),(133,1767640620,60,'cpu','portal-paciente','avg',1.50,4),(134,1767640620,60,'memory','portal-paciente','avg',1871.00,4),(165,1767640680,60,'cpu','portal-paciente','avg',8.50,4),(166,1767640680,360,'cpu','portal-paciente','avg',2.66,24),(167,1767640680,60,'memory','portal-paciente','avg',1876.25,4),(168,1767640680,360,'memory','portal-paciente','avg',1879.95,24),(197,1767640740,60,'cpu','portal-paciente','avg',1.50,4),(198,1767640740,60,'memory','portal-paciente','avg',1870.00,4),(229,1767640800,60,'cpu','portal-paciente','avg',1.50,4),(230,1767640800,60,'memory','portal-paciente','avg',1880.75,4),(261,1767640860,60,'cpu','portal-paciente','avg',1.00,4),(262,1767640860,60,'memory','portal-paciente','avg',1883.00,4),(293,1767640920,60,'cpu','portal-paciente','avg',2.50,4),(294,1767640920,60,'memory','portal-paciente','avg',1882.50,4),(325,1767640980,60,'cpu','portal-paciente','avg',1.00,4),(326,1767640980,60,'memory','portal-paciente','avg',1887.25,4),(357,1767641040,60,'cpu','portal-paciente','avg',2.50,4),(358,1767641040,360,'cpu','portal-paciente','avg',1.50,24),(359,1767641040,60,'memory','portal-paciente','avg',1889.75,4),(360,1767641040,360,'memory','portal-paciente','avg',1889.42,24),(389,1767641100,60,'cpu','portal-paciente','avg',1.00,4),(390,1767641100,60,'memory','portal-paciente','avg',1889.75,4),(421,1767641160,60,'cpu','portal-paciente','avg',1.50,4),(422,1767641160,60,'memory','portal-paciente','avg',1890.00,4),(453,1767641220,60,'cpu','portal-paciente','avg',0.50,4),(454,1767641220,60,'memory','portal-paciente','avg',1892.00,4),(485,1767641280,60,'cpu','portal-paciente','avg',1.50,4),(486,1767641280,60,'memory','portal-paciente','avg',1886.50,4),(517,1767641340,60,'cpu','portal-paciente','avg',2.00,4),(518,1767641340,60,'memory','portal-paciente','avg',1888.50,4),(549,1767641400,60,'cpu','portal-paciente','avg',2.00,4),(550,1767641400,360,'cpu','portal-paciente','avg',1.17,24),(551,1767641400,60,'memory','portal-paciente','avg',1893.75,4),(552,1767641400,360,'memory','portal-paciente','avg',1894.12,24),(581,1767641460,60,'cpu','portal-paciente','avg',1.50,4),(582,1767641460,60,'memory','portal-paciente','avg',1894.50,4),(613,1767641520,60,'cpu','portal-paciente','avg',1.00,4),(614,1767641520,60,'memory','portal-paciente','avg',1893.75,4),(645,1767641580,60,'cpu','portal-paciente','avg',1.00,4),(646,1767641580,60,'memory','portal-paciente','avg',1894.50,4),(677,1767641640,60,'cpu','portal-paciente','avg',0.50,4),(678,1767641640,60,'memory','portal-paciente','avg',1893.50,4),(709,1767641700,60,'cpu','portal-paciente','avg',1.00,4),(710,1767641700,60,'memory','portal-paciente','avg',1894.75,4),(741,1767641760,60,'cpu','portal-paciente','avg',1.00,4),(742,1767641760,360,'cpu','portal-paciente','avg',0.83,24),(743,1767641760,1440,'cpu','portal-paciente','avg',1.32,96),(744,1767641760,60,'memory','portal-paciente','avg',1889.25,4),(745,1767641760,360,'memory','portal-paciente','avg',1895.12,24),(746,1767641760,1440,'memory','portal-paciente','avg',1683.78,96),(773,1767641820,60,'cpu','portal-paciente','avg',0.50,4),(774,1767641820,60,'memory','portal-paciente','avg',1893.00,4),(805,1767641880,60,'cpu','portal-paciente','avg',1.50,4),(806,1767641880,60,'memory','portal-paciente','avg',1893.00,4),(837,1767641940,60,'cpu','portal-paciente','avg',0.50,4),(838,1767641940,60,'memory','portal-paciente','avg',1899.75,4),(869,1767642000,60,'cpu','portal-paciente','avg',1.00,4),(870,1767642000,60,'memory','portal-paciente','avg',1899.00,4),(901,1767642060,60,'cpu','portal-paciente','avg',0.50,4),(902,1767642060,60,'memory','portal-paciente','avg',1896.75,4),(933,1767642120,60,'cpu','portal-paciente','avg',1.00,4),(934,1767642120,360,'cpu','portal-paciente','avg',1.58,24),(935,1767642120,60,'memory','portal-paciente','avg',1897.00,4),(936,1767642120,360,'memory','portal-paciente','avg',1896.12,24),(965,1767642180,60,'cpu','portal-paciente','avg',2.50,4),(966,1767642180,60,'memory','portal-paciente','avg',1896.50,4),(997,1767642180,60,'user_request','6','count',1.00,NULL),(998,1767642120,360,'user_request','6','count',1.00,NULL),(999,1767641760,1440,'user_request','6','count',1.00,NULL),(1001,1767642240,60,'cpu','portal-paciente','avg',1.00,4),(1002,1767642240,60,'memory','portal-paciente','avg',1895.75,4),(1009,1767642240,60,'user_request','18','count',2.00,NULL),(1010,1767642120,360,'user_request','18','count',2.00,NULL),(1011,1767641760,1440,'user_request','18','count',2.00,NULL),(1012,1767638880,10080,'user_request','18','count',4.00,NULL),(1013,1767642240,60,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','count',1.00,NULL),(1014,1767642120,360,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','count',1.00,NULL),(1015,1767641760,1440,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','count',1.00,NULL),(1016,1767638880,10080,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','count',2.00,NULL),(1017,1767642240,60,'slow_user_request','18','count',1.00,NULL),(1018,1767642120,360,'slow_user_request','18','count',1.00,NULL),(1019,1767641760,1440,'slow_user_request','18','count',1.00,NULL),(1020,1767638880,10080,'slow_user_request','18','count',2.00,NULL),(1025,1767642240,60,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','max',2387.00,NULL),(1026,1767642120,360,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','max',2387.00,NULL),(1027,1767641760,1440,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','max',2387.00,NULL),(1028,1767638880,10080,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','max',2387.00,NULL),(1053,1767642300,60,'cpu','portal-paciente','avg',1.00,4),(1054,1767642300,60,'memory','portal-paciente','avg',1894.25,4),(1085,1767642360,60,'cpu','portal-paciente','avg',3.00,4),(1086,1767642360,60,'memory','portal-paciente','avg',1895.50,4),(1117,1767642420,60,'cpu','portal-paciente','avg',1.00,4),(1118,1767642420,60,'memory','portal-paciente','avg',1897.75,4),(1149,1767642480,60,'cpu','portal-paciente','avg',0.50,4),(1150,1767642480,360,'cpu','portal-paciente','avg',1.26,24),(1151,1767642480,60,'memory','portal-paciente','avg',1900.25,4),(1152,1767642480,360,'memory','portal-paciente','avg',1630.70,24),(1181,1767642540,60,'cpu','portal-paciente','avg',2.50,4),(1182,1767642540,60,'memory','portal-paciente','avg',1922.25,4),(1213,1767642600,60,'cpu','portal-paciente','avg',0.00,4),(1214,1767642600,60,'memory','portal-paciente','avg',1795.25,4),(1245,1767642660,60,'cpu','portal-paciente','avg',2.50,4),(1246,1767642660,60,'memory','portal-paciente','avg',1389.00,4),(1277,1767642720,60,'cpu','portal-paciente','avg',1.00,4),(1278,1767642720,60,'memory','portal-paciente','avg',1388.50,4),(1309,1767642780,60,'cpu','portal-paciente','avg',1.00,4),(1310,1767642780,60,'memory','portal-paciente','avg',1389.00,4),(1341,1767642840,60,'cpu','portal-paciente','avg',1.00,4),(1342,1767642840,360,'cpu','portal-paciente','avg',1.57,24),(1343,1767642840,60,'memory','portal-paciente','avg',1394.00,4),(1344,1767642840,360,'memory','portal-paciente','avg',1313.17,24),(1373,1767642900,60,'cpu','portal-paciente','avg',1.50,4),(1374,1767642900,60,'memory','portal-paciente','avg',1372.50,4),(1405,1767642960,60,'cpu','portal-paciente','avg',1.50,4),(1406,1767642960,60,'memory','portal-paciente','avg',1273.50,4),(1437,1767643020,60,'cpu','portal-paciente','avg',2.50,4),(1438,1767643020,60,'memory','portal-paciente','avg',1273.25,4),(1469,1767643080,60,'cpu','portal-paciente','avg',1.50,4),(1470,1767643080,60,'memory','portal-paciente','avg',1277.50,4),(1501,1767643140,60,'cpu','portal-paciente','avg',1.50,4),(1502,1767643140,60,'memory','portal-paciente','avg',1288.25,4),(1533,1767643200,60,'cpu','portal-paciente','avg',1.50,4),(1534,1767643200,360,'cpu','portal-paciente','avg',1.51,24),(1535,1767643200,1440,'cpu','portal-paciente','avg',1.49,75),(1536,1767643200,60,'memory','portal-paciente','avg',1286.50,4),(1537,1767643200,360,'memory','portal-paciente','avg',1288.66,24),(1538,1767643200,1440,'memory','portal-paciente','avg',1289.70,75),(1565,1767643260,60,'cpu','portal-paciente','avg',1.00,4),(1566,1767643260,60,'memory','portal-paciente','avg',1287.50,4),(1597,1767643320,60,'cpu','portal-paciente','avg',1.00,4),(1598,1767643320,60,'memory','portal-paciente','avg',1288.50,4),(1629,1767643380,60,'cpu','portal-paciente','avg',2.00,4),(1630,1767643380,60,'memory','portal-paciente','avg',1288.00,4),(1661,1767643440,60,'cpu','portal-paciente','avg',0.50,4),(1662,1767643440,60,'memory','portal-paciente','avg',1285.75,4),(1693,1767643440,60,'user_request','6','count',1.00,NULL),(1694,1767643200,360,'user_request','6','count',1.00,NULL),(1695,1767643200,1440,'user_request','6','count',3.00,NULL),(1697,1767643500,60,'cpu','portal-paciente','avg',3.00,4),(1698,1767643500,60,'memory','portal-paciente','avg',1295.75,4),(1729,1767643560,60,'cpu','portal-paciente','avg',2.00,4),(1730,1767643560,360,'cpu','portal-paciente','avg',1.34,24),(1731,1767643560,60,'memory','portal-paciente','avg',1286.25,4),(1732,1767643560,360,'memory','portal-paciente','avg',1286.04,24),(1761,1767643620,60,'cpu','portal-paciente','avg',2.00,4),(1762,1767643620,60,'memory','portal-paciente','avg',1286.00,4),(1793,1767643680,60,'cpu','portal-paciente','avg',0.50,4),(1794,1767643680,60,'memory','portal-paciente','avg',1281.00,4),(1801,1767643680,60,'user_request','18','count',2.00,NULL),(1802,1767643560,360,'user_request','18','count',2.00,NULL),(1803,1767643200,1440,'user_request','18','count',2.00,NULL),(1805,1767643680,60,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','count',1.00,NULL),(1806,1767643560,360,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','count',1.00,NULL),(1807,1767643200,1440,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','count',1.00,NULL),(1808,1767643680,60,'slow_user_request','18','count',1.00,NULL),(1809,1767643560,360,'slow_user_request','18','count',1.00,NULL),(1810,1767643200,1440,'slow_user_request','18','count',1.00,NULL),(1817,1767643680,60,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','max',2290.00,NULL),(1818,1767643560,360,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','max',2290.00,NULL),(1819,1767643200,1440,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]','max',2290.00,NULL),(1845,1767643740,60,'cpu','portal-paciente','avg',1.00,4),(1846,1767643740,60,'memory','portal-paciente','avg',1287.75,4),(1877,1767643800,60,'cpu','portal-paciente','avg',2.00,4),(1878,1767643800,60,'memory','portal-paciente','avg',1281.75,4),(1909,1767643860,60,'cpu','portal-paciente','avg',0.50,4),(1910,1767643860,60,'memory','portal-paciente','avg',1293.50,4),(1941,1767643920,60,'cpu','portal-paciente','avg',1.50,4),(1942,1767643920,360,'cpu','portal-paciente','avg',1.59,24),(1943,1767643920,60,'memory','portal-paciente','avg',1291.50,4),(1944,1767643920,360,'memory','portal-paciente','avg',1294.28,24),(1973,1767643980,60,'cpu','portal-paciente','avg',2.50,4),(1974,1767643980,60,'memory','portal-paciente','avg',1296.00,4),(2005,1767644040,60,'cpu','portal-paciente','avg',2.00,4),(2006,1767644040,60,'memory','portal-paciente','avg',1295.50,4),(2037,1767644100,60,'cpu','portal-paciente','avg',1.50,4),(2038,1767644100,60,'memory','portal-paciente','avg',1293.50,4),(2069,1767644160,60,'cpu','portal-paciente','avg',1.50,4),(2070,1767644160,60,'memory','portal-paciente','avg',1294.00,4),(2101,1767644220,60,'cpu','portal-paciente','avg',0.50,4),(2102,1767644220,60,'memory','portal-paciente','avg',1295.25,4),(2133,1767644280,60,'cpu','portal-paciente','avg',1.33,3),(2134,1767644280,360,'cpu','portal-paciente','avg',1.33,3),(2135,1767644280,60,'memory','portal-paciente','avg',1291.00,3),(2136,1767644280,360,'memory','portal-paciente','avg',1291.00,3),(2141,1767644280,60,'user_request','6','count',2.00,NULL),(2142,1767644280,360,'user_request','6','count',2.00,NULL);
/*!40000 ALTER TABLE `pulse_aggregates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pulse_entries`
--

DROP TABLE IF EXISTS `pulse_entries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pulse_entries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `value` bigint DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pulse_entries_timestamp_index` (`timestamp`),
  KEY `pulse_entries_type_index` (`type`),
  KEY `pulse_entries_key_hash_index` (`key_hash`),
  KEY `pulse_entries_timestamp_type_key_hash_value_index` (`timestamp`,`type`,`key_hash`,`value`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pulse_entries`
--

LOCK TABLES `pulse_entries` WRITE;
/*!40000 ALTER TABLE `pulse_entries` DISABLE KEYS */;
INSERT INTO `pulse_entries` (`id`, `timestamp`, `type`, `key`, `value`) VALUES (1,1767640394,'user_request','6',NULL),(2,1767642236,'user_request','6',NULL),(3,1767642245,'user_request','18',NULL),(4,1767642245,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]',2387),(5,1767642245,'slow_user_request','18',NULL),(6,1767642245,'user_request','18',NULL),(7,1767643496,'user_request','6',NULL),(8,1767643689,'user_request','18',NULL),(9,1767643689,'slow_request','[\"POST\",\"\\/hospital-exemplo-tasy\",\"via \\/livewire\\/update\"]',2290),(10,1767643689,'slow_user_request','18',NULL),(11,1767643689,'user_request','18',NULL),(12,1767644289,'user_request','6',NULL),(13,1767644289,'user_request','6',NULL);
/*!40000 ALTER TABLE `pulse_entries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pulse_values`
--

DROP TABLE IF EXISTS `pulse_values`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pulse_values` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `timestamp` int unsigned NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `key_hash` binary(16) GENERATED ALWAYS AS (unhex(md5(`key`))) VIRTUAL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pulse_values_type_key_hash_unique` (`type`,`key_hash`),
  KEY `pulse_values_timestamp_index` (`timestamp`),
  KEY `pulse_values_type_index` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=264 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pulse_values`
--

LOCK TABLES `pulse_values` WRITE;
/*!40000 ALTER TABLE `pulse_values` DISABLE KEYS */;
INSERT INTO `pulse_values` (`id`, `timestamp`, `type`, `key`, `value`) VALUES (1,1767644314,'system','portal-paciente','{\"name\":\"portal-paciente\",\"cpu\":2,\"memory_used\":1276,\"memory_total\":7941,\"storage\":[{\"directory\":\"\\/\",\"total\":31066,\"used\":11325}]}');
/*!40000 ALTER TABLE `pulse_values` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('5ISv8vXcVdHwNPR9HoXLMMghy12vwxZ2VqHGkjPH',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoickIzSWo2YnU0T2syWTIxODBNQVJXTWpYNWVTdVNPajlPWkhIRkEwRSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xOTIuMTY4LjEwMC43Njo4MDAwL3BhaW5lbCI7czo1OiJyb3V0ZSI7czoxOToiZmlsYW1lbnQuYXBwLnRlbmFudCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O30=',1767421295),('6ZDRDiqTVtZggTss55o2ZP28SwClaVGP3zrl6a4p',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiNGhMaW1Pc0dWVThJRTdNVUUzY2NNVXVuMVhoSHg0aFBlVnhvdWpCeSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xOTIuMTY4LjEwMC43Njo4MDAwL3BhaW5lbCI7czo1OiJyb3V0ZSI7czoxOToiZmlsYW1lbnQuYXBwLnRlbmFudCI7fXM6MzoidXJsIjthOjA6e31zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkUHEwbVQ1WTF2dHhFLkN4ZWlZVEpiT3F4R3pCNldDM2hSa2ZIUnFoekloSlM2bEpmQkpxSnkiO30=',1767419185),('aStLiGAemGnGqE4RspEDasXSCvVC2hfXg4IxjPZx',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiMWd6SmRrbG9LUHgzSDhjV0hLYTl0MkpiNUtEb3VVd1h4QnlFR3BJTiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMC9wYWluZWwiO3M6NToicm91dGUiO3M6MTk6ImZpbGFtZW50LmFwcC50ZW5hbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkUHEwbVQ1WTF2dHhFLkN4ZWlZVEpiT3F4R3pCNldDM2hSa2ZIUnFoekloSlM2bEpmQkpxSnkiO30=',1767419844),('ByBeh9NoOQsJgtQ4woec9YQUIoTWId46rQCupY71',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo2OntzOjY6Il90b2tlbiI7czo0MDoibHVMVXBmaWUwVmdkYnlNNXVqY0tmT2pVcUV0OUNGdEFrQVNUWUpCdSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMC9wYWluZWwiO3M6NToicm91dGUiO3M6MTk6ImZpbGFtZW50LmFwcC50ZW5hbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkUHEwbVQ1WTF2dHhFLkN4ZWlZVEpiT3F4R3pCNldDM2hSa2ZIUnFoekloSlM2bEpmQkpxSnkiO30=',1767419780),('CfKIZ4pRiK2L0apyTpHlkOlSAqWdp1RsAvDweBLU',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiWnRWdjB0UkFXbnlrbkp1RU1uUUhNamhvclVIR1IzOEdFYm0yamFVciI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMC9wYWluZWwiO3M6NToicm91dGUiO3M6MTk6ImZpbGFtZW50LmFwcC50ZW5hbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkUHEwbVQ1WTF2dHhFLkN4ZWlZVEpiT3F4R3pCNldDM2hSa2ZIUnFoekloSlM2bEpmQkpxSnkiO30=',1767420226),('F2tLSgqQeBv7JHvd1HMEchmntpFIcqFxWumO1ePZ',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZjRuOEt0QXRpeXN6SXVUSDdUVlNqakpiMGdsUU9hNkMxRnRPbkdhdSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xOTIuMTY4LjEwMC43Njo4MDAwL2FkbWluIjtzOjU6InJvdXRlIjtzOjMwOiJmaWxhbWVudC5hZG1pbi5wYWdlcy5kYXNoYm9hcmQiO31zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkUHEwbVQ1WTF2dHhFLkN4ZWlZVEpiT3F4R3pCNldDM2hSa2ZIUnFoekloSlM2bEpmQkpxSnkiO30=',1767421232),('G5QXxTAwRKQpYcUE7SxKkoTUUt4gXYzXacI8nEfE',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiSDhSeEcxQVF4bzZNY3c3eGpwMlhrcjd0dk1RU3RLUXJKdkwxWDNoMiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMC9wYWluZWwiO3M6NToicm91dGUiO3M6MTk6ImZpbGFtZW50LmFwcC50ZW5hbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkUHEwbVQ1WTF2dHhFLkN4ZWlZVEpiT3F4R3pCNldDM2hSa2ZIUnFoekloSlM2bEpmQkpxSnkiO30=',1767420461),('GFa9ow5tvDKGA4WJSG6AY4JLnXByxtzm80dBd5Db',11,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoicFlCT0FrOGczdVNOSzR4TzhQR0hjaXhmZkg1NnhjTkZma2U4bm1PSiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMC9wYWluZWwiO3M6NToicm91dGUiO3M6MTk6ImZpbGFtZW50LmFwcC50ZW5hbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aToxMTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJEJXZTdha2hKSWx4RFNERW1YcHRtS09LMlg1dTZwN3Q4MVprR1JNQlQ5Q3AyRFRQV2pEQlU2Ijt9',1767419900),('KmO2VzSBTB9RRAp93Gl2o6v24Fkk2sjGxFtcBveG',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiZjZxMDdVM3dpdlo4SnhieDdnbVhXdkZiVVNxZ0pnTllVTEIzWExGMyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMC9wYWluZWwiO3M6NToicm91dGUiO3M6MTk6ImZpbGFtZW50LmFwcC50ZW5hbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O30=',1767421043),('LwdIBxAKhkg0NijNR551ZJutkOklAqaW6WM0v7Hs',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiOGhUc1cwMWtNd3E4bkduZExTbDlTODMyamd3QnZNUkpMRXRFbEJIMiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMC9wYWluZWwiO3M6NToicm91dGUiO3M6MTk6ImZpbGFtZW50LmFwcC50ZW5hbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O30=',1767421033),('O6iIL11km1yP5FLe66IhyoIsxOXww0HptaBN6vif',11,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiVEx2c3RiQ1BCa2U1YWxNQUdXZmt5bnFiMDMxWnB3dDBkRkg1NkxNVSI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMC9wYWluZWwiO3M6NToicm91dGUiO3M6MTk6ImZpbGFtZW50LmFwcC50ZW5hbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aToxMTtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJEJXZTdha2hKSWx4RFNERW1YcHRtS09LMlg1dTZwN3Q4MVprR1JNQlQ5Q3AyRFRQV2pEQlU2Ijt9',1767420281),('qfPs6cKZjR9tkvJ4oAA5UvIOqm7NZUWOaQyg7wmS',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiR0pnMWs3eFJBMm9Wc05CemJtZ0VZNmVzeHBhb0dnakU3VnBlVWtZRiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMC9wYWluZWwiO3M6NToicm91dGUiO3M6MTk6ImZpbGFtZW50LmFwcC50ZW5hbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkUHEwbVQ1WTF2dHhFLkN4ZWlZVEpiT3F4R3pCNldDM2hSa2ZIUnFoekloSlM2bEpmQkpxSnkiO30=',1767419716),('QLR5CQwgTM7mqVS9MnNz3912pYkdXnTCBMO0A2kR',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoidUZ5YWFBU1VqNlZkbk9Cc0N2UE9jQlVWUzJpNWk3VTcxWDdQN3ZKeCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMC9wYWluZWwiO3M6NToicm91dGUiO3M6MTk6ImZpbGFtZW50LmFwcC50ZW5hbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkUHEwbVQ1WTF2dHhFLkN4ZWlZVEpiT3F4R3pCNldDM2hSa2ZIUnFoekloSlM2bEpmQkpxSnkiO30=',1767419514),('TJqU2vTaqBmr5CSp4dRsDzyVoWroMCTO5qn6d8sz',NULL,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVHJhb1FIaUJ2Y1ZtYlFlUzYyOVd6MllYOVBDVTk5bU1pbzZSZDFVWSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czo0NDoiaHR0cDovLzE5Mi4xNjguMTAwLjc2OjgwMDAvY29uc3VsdG9yaW8tcGVkcm8iO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czo1NDoiaHR0cDovLzE5Mi4xNjguMTAwLjc2OjgwMDAvaG9zcGl0YWwtZXhlbXBsby10YXN5L2xvZ2luIjtzOjU6InJvdXRlIjtzOjU6ImxvZ2luIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1767418474),('vLs03ECVLODinR11NisIQNO0Ywn5eAyXd621VvYD',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoibmlTYXl5a3R2VFVIbDJncDJTa1RVY3FWcXJIYUw0WTNkTlEwV1N4USI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjI2OiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMCI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzNkYzdhOTEzZWY1ZmQ0Yjg5MGVjYWJlMzQ4NzA4NTU3M2UxNmNmODIiO2k6NjtzOjE3OiJwYXNzd29yZF9oYXNoX3dlYiI7czo2MDoiJDJ5JDEyJFBxMG1UNVkxdnR4RS5DeGVpWVRKYk9xeEd6QjZXQzNoUmtmSFJxaHpJaEpTNmxKZkJKcUp5Ijt9',1767418766),('xxvm2ob8O8W0xsrBSn0moL6YU1KXTgTBODXELCs2',6,'192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','YTo2OntzOjY6Il90b2tlbiI7czo0MDoiY2tkaFBUZ1FBREN5THhtbkVneTZUc3QwTnRQeWh3M1RUZVROMFpMSiI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjMzOiJodHRwOi8vMTkyLjE2OC4xMDAuNzY6ODAwMC9wYWluZWwiO3M6NToicm91dGUiO3M6MTk6ImZpbGFtZW50LmFwcC50ZW5hbnQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfM2RjN2E5MTNlZjVmZDRiODkwZWNhYmUzNDg3MDg1NTczZTE2Y2Y4MiI7aTo2O3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjYwOiIkMnkkMTIkUHEwbVQ1WTF2dHhFLkN4ZWlZVEpiT3F4R3pCNldDM2hSa2ZIUnFoekloSlM2bEpmQkpxSnkiO30=',1767420632);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tenants`
--

DROP TABLE IF EXISTS `tenants`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tenants` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `whatsapp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domain` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `primary_color` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#0ea5e9',
  `mode` enum('standalone','integrated') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'standalone',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `erp_driver` enum('tasy','mv','protheus') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `db_connection_data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `subscription_start` date DEFAULT NULL,
  `subscription_end` date DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tenants_slug_unique` (`slug`),
  UNIQUE KEY `tenants_domain_unique` (`domain`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tenants`
--

LOCK TABLES `tenants` WRITE;
/*!40000 ALTER TABLE `tenants` DISABLE KEYS */;
INSERT INTO `tenants` VALUES (1,'Hospital Exemplo Tasy','5565981536448','hospital-exemplo-tasy','portal.amecor.com.br','tenants/logos/01KE5QQ7VP6NTZD8FAAB4QRRFT.png','#185700','integrated',1,'tasy','eyJpdiI6IjdKWDFvMGNnVkhGM0ZGcjIwcXdRcWc9PSIsInZhbHVlIjoiYWFiYmIxeERqMlRUcU9LeFI4VUhjWVpyczZQNlk5eVpCUkpBTDhmV1d4UFFKSEJHQ3NrVUpvSjZRdHUzaDh5YUdRM3RRbU9kQWYrbXdYeUpVU3B2QTZHUklIYzdZTERLUVV2Y2t2RXNzUWJzSlhqRG1SekZ6azhkYWV5M2d0eDFKY2dJMlpqMW1TZVlwSzE5Tk5sMyt6ODhYZytpdVdqOERlQVBUWmtveEFlaXhlOEs0UGxrTWdMWWQ2VUoxTkpZUnE4WTQwQTJyeitTd2hSTGhDaG5UUT09IiwibWFjIjoiNWEyZmFmMTU0NThmMGFlYjUzYzRiZGFiMDBjZGQ3NGEyZTg5MmUyM2QyN2E0MmFjNTAyZjY5Mzg0MjI3YjAzYyIsInRhZyI6IiJ9','2026-01-03 00:08:55','2026-01-04 20:11:17','2026-01-03','2026-01-11',NULL),(2,'Consultório Dr. Pedro','5565981536448','consultorio-pedro',NULL,'tenants/logos/01KE2P9FH0QJRB7QATVQKCBJ00.png','#00868f','standalone',1,NULL,NULL,'2026-01-03 00:14:26','2026-01-05 10:29:56','2026-01-03','2032-12-31',NULL),(3,'Teste',NULL,'teste',NULL,'tenants/logos/01KE2Q869BBYJQ1PC8J55ERZCN.png','#8aff4b','standalone',1,NULL,NULL,'2026-01-03 15:47:50','2026-01-03 16:02:17','2026-01-03','2026-01-10',NULL);
/*!40000 ALTER TABLE `tenants` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `term_acceptances`
--

DROP TABLE IF EXISTS `term_acceptances`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `term_acceptances` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `term_version` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'v1.0',
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `term_acceptances_user_id_foreign` (`user_id`),
  CONSTRAINT `term_acceptances_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `term_acceptances`
--

LOCK TABLES `term_acceptances` WRITE;
/*!40000 ALTER TABLE `term_acceptances` DISABLE KEYS */;
INSERT INTO `term_acceptances` VALUES (1,18,'v1.0','192.168.100.12','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/141.0.0.0 Safari/537.36 OPR/125.0.0.0','2026-01-05 10:17:43','2026-01-05 10:17:43');
/*!40000 ALTER TABLE `term_acceptances` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tasy_cd_pessoa_fisica` bigint unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('super_admin','admin_clinica','paciente') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'paciente',
  `cpf` varchar(14) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tenant_id` bigint unsigned DEFAULT NULL,
  `cns` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nome_mae` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nascimento` date DEFAULT NULL,
  `celular` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cep` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `endereco` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `numero` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `complemento` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bairro` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cidade` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uf` varchar(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_cpf_unique` (`cpf`),
  KEY `users_tenant_id_foreign` (`tenant_id`),
  CONSTRAINT `users_tenant_id_foreign` FOREIGN KEY (`tenant_id`) REFERENCES `tenants` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (6,100,'Pedro Arcangelo Dias Lopes Polezel','pedroarcangelo1997@gmail.com','super_admin','063.962.385-94',NULL,'$2y$12$Pq0mT5Y1vtxE.CxeiYTJbOqxGzB6WC3hRkfHRqhzIhJS6lJfBJqJy','GbFCTSZqeJT3yJD7W3t8P9ZbBtV3Tx3KITm62AoCIEzysfr1TshGyxMK6zdX','2026-01-02 23:47:51','2026-01-05 00:29:04',2,'1234567890','Renata Arcangelo Dias Lopes Polezel','1997-02-03','(65) 98153-6448','78050-170','Rua Professor Rafael Rueda','37','Ed Cannes, apto 24','Bosque da Saude','Cuiabá','MT'),(10,286457,'PEDRO LOPES','06396238594@1.portal','paciente',NULL,NULL,'$2y$12$rXdL37fRrbcy7H1f2NkGHeJ0KV02exHzV.lRsneBr2GN5Y4dn/xH.','Ili9k142Q2Asgt0MUAt5LMdWwy4E5dI7mz4bOdJ4SMBMTeJgO0db2i3DnYSK','2026-01-03 00:00:12','2026-01-04 18:13:09',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(11,NULL,'Pedro Cliente','pedro@cliente.com.br','admin_clinica',NULL,NULL,'$2y$12$/gSIAr4NDf7kYfMe5tXYh.zrf3jsb6xH6o3XBxZUEOuKFwGuJXI3.',NULL,'2026-01-03 01:57:48','2026-01-03 15:00:01',2,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(12,NULL,'teste','teste@teste.com.br','admin_clinica',NULL,NULL,'$2y$12$LiJ4GC7KyKgrT0kjUIH1gOqf3PMDp4EiAFSHnDdy6bRdkgvGrR8FO',NULL,'2026-01-03 15:49:22','2026-01-03 15:49:22',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(14,NULL,'Teste','teste1@teste.com.br','paciente',NULL,NULL,'$2y$12$No59JD3DWlWSelwIFRfDsuTx5llFlXUfzDI40LAs0eo.Db70ZB.vK',NULL,'2026-01-03 15:53:06','2026-01-03 15:53:06',3,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(17,329197,'JOSE EMILIO PACHECO BERNY','pedro@wcic.com.br','paciente',NULL,NULL,'$2y$12$B.yWr74A4H6bKxzSw.9oWOsVlHNuyxRHB1ih6tSyIoxKpmc9z/g7q','QerAfHk1QSnPIJvp1t639ZwnvZ9Bes0jqYQEq271WXEzRW40pQS8i4G9PG3z','2026-01-04 17:26:47','2026-01-05 10:24:20',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(18,329174,'PRUEBA FABIANA INTERCONSULTA','pedro@vivereconsultoria.com.br','paciente','68970957022',NULL,'$2y$12$C2PfZGtC2xUPWF6OR.460uqGEcIiUG2n2lVXBTyRhu3BdIStzMpMG',NULL,'2026-01-05 10:17:43','2026-01-05 10:17:43',1,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-01-06  1:53:59
