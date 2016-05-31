-- MySQL dump 10.13  Distrib 5.7.12, for Linux (x86_64)
--
-- Host: 127.0.0.1    Database: BancoDeImagens
-- ------------------------------------------------------
-- Server version	5.7.12-0ubuntu1

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
-- Table structure for table `eventos`
--

DROP TABLE IF EXISTS `eventos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `eventos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(50) DEFAULT NULL,
  `descricao` varchar(200) DEFAULT NULL,
  `fotografo` int(11) NOT NULL,
  `usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `eventos`
--

LOCK TABLES `eventos` WRITE;
/*!40000 ALTER TABLE `eventos` DISABLE KEYS */;
INSERT INTO `eventos` VALUES (1,'EventoTeste','EventoTeste123',5,7),(2,'234','234',5,8);
/*!40000 ALTER TABLE `eventos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fotos`
--

DROP TABLE IF EXISTS `fotos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fotos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` text,
  `selecionada` tinyint(1) DEFAULT NULL,
  `url` text NOT NULL,
  `evento_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fotos`
--

LOCK TABLES `fotos` WRITE;
/*!40000 ALTER TABLE `fotos` DISABLE KEYS */;
INSERT INTO `fotos` VALUES (7,'1',1,'images/dog.png',1),(8,'2',1,'images/dog.png',1),(9,'3',1,'images/user.jpg',1),(10,'4',0,'images/user.jpg',1),(11,'5',1,'images/user.jpg',1),(12,'senai1.png',1,'http://banco.imagens/eventosFotos/1/tmp_senai1.png',1),(13,'senai2.png',1,'http://banco.imagens/eventosFotos/1/tmp_senai2.png',1),(14,'senai1.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_senai1.png',2),(15,'senai2.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_senai2.png',2),(16,'Captura de tela de 2016-05-21 11-53-36.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-53-36.png',2),(17,'Captura de tela de 2016-05-21 11-53-42.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-53-42.png',2),(18,'Captura de tela de 2016-05-21 11-53-50.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-53-50.png',2),(19,'Captura de tela de 2016-05-21 11-54-00.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-54-00.png',2),(20,'Captura de tela de 2016-05-21 11-54-05.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-54-05.png',2),(21,'Captura de tela de 2016-05-21 11-54-00.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-54-00.png',2),(22,'Captura de tela de 2016-05-21 11-54-00.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-54-00.png',2),(23,'Captura de tela de 2016-05-21 11-53-36.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-53-36.png',2),(24,'Captura de tela de 2016-05-21 11-54-00.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-54-00.png',2),(25,'Captura de tela de 2016-05-21 11-54-05.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-54-05.png',2),(26,'senai2.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_senai2.png',2),(27,'senai1.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_senai1.png',2),(28,'Captura de tela de 2016-05-21 11-53-42.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-53-42.png',2),(29,'Captura de tela de 2016-05-21 11-53-50.png',NULL,'http://banco.imagens/eventosFotos/2/tmp_captura_de_tela_de_2016-05-21_11-53-50.png',2);
/*!40000 ALTER TABLE `fotos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `usuarios`
--

DROP TABLE IF EXISTS `usuarios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login` varchar(50) NOT NULL,
  `senha` varchar(50) NOT NULL,
  `nome` varchar(50) DEFAULT NULL,
  `tipo` varchar(20) NOT NULL,
  `status` tinyint(4) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `usuarios`
--

LOCK TABLES `usuarios` WRITE;
/*!40000 ALTER TABLE `usuarios` DISABLE KEYS */;
INSERT INTO `usuarios` VALUES (1,'admin','admin','Administrador','admin',1),(2,'teste1','teste','Fotografo1','fotografo',1),(3,'teste2','teste','Fotografo2','fotografo',1),(5,'1','1','123213231','fotografo',1),(7,'1234','1234','EventoTeste','evento',1),(8,'234','234','234','evento',1);
/*!40000 ALTER TABLE `usuarios` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary view structure for view `vw_fotografosEventosFotos`
--

DROP TABLE IF EXISTS `vw_fotografosEventosFotos`;
/*!50001 DROP VIEW IF EXISTS `vw_fotografosEventosFotos`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8;
/*!50001 CREATE VIEW `vw_fotografosEventosFotos` AS SELECT 
 1 AS `id`,
 1 AS `nome`,
 1 AS `tipo`,
 1 AS `eventos`,
 1 AS `fotos`*/;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vw_fotografosEventosFotos`
--

/*!50001 DROP VIEW IF EXISTS `vw_fotografosEventosFotos`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8 */;
/*!50001 SET character_set_results     = utf8 */;
/*!50001 SET collation_connection      = utf8_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`skip-grants user`@`skip-grants host` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_fotografosEventosFotos` AS select `u`.`id` AS `id`,`u`.`nome` AS `nome`,`u`.`tipo` AS `tipo`,(select count(1) from `eventos` where (`eventos`.`fotografo` = `u`.`id`)) AS `eventos`,(select count(1) from (`fotos` `f` join `eventos` `e` on((`f`.`evento_id` = `e`.`id`))) where (`e`.`fotografo` = `u`.`id`)) AS `fotos` from `usuarios` `u` where (`u`.`tipo` = 'fotografo') */;
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

-- Dump completed on 2016-05-31  0:19:53
