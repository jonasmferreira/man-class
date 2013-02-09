-- MySQL Administrator dump 1.4
--
-- ------------------------------------------------------
-- Server version	5.1.58-community-log


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;



--
-- Definition of table `tb_configuracao`
--

DROP TABLE IF EXISTS `tb_configuracao`;
CREATE TABLE `tb_configuracao` (
  `configuracao_id` bigint(20) NOT NULL AUTO_INCREMENT,
  `configuracao_baseurl_ckfinder` text,
  PRIMARY KEY (`configuracao_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_configuracao`
--

/*!40000 ALTER TABLE `tb_configuracao` DISABLE KEYS */;
INSERT INTO `tb_configuracao` (`configuracao_id`,`configuracao_baseurl_ckfinder`) VALUES 
 (1,'http://www.movieco.org.br/imgs_rich/');
/*!40000 ALTER TABLE `tb_configuracao` ENABLE KEYS */;


--
-- Definition of table `tb_usuario`
--

DROP TABLE IF EXISTS `tb_usuario`;
CREATE TABLE `tb_usuario` (
  `usuario_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_nome` varchar(255) NOT NULL,
  `usuario_login` varchar(100) NOT NULL,
  `usuario_senha` varchar(150) NOT NULL,
  `usuario_status_id` bigint(20) unsigned NOT NULL,
  `usuario_nivel_id` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`usuario_id`),
  KEY `fk_tb_usuario_tb_usuario_status` (`usuario_status_id`),
  KEY `fk_tb_usuario_tb_usuario_nivel1` (`usuario_nivel_id`),
  CONSTRAINT `fk_tb_usuario_tb_usuario_nivel1` FOREIGN KEY (`usuario_nivel_id`) REFERENCES `tb_usuario_nivel` (`usuario_nivel_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_tb_usuario_tb_usuario_status` FOREIGN KEY (`usuario_status_id`) REFERENCES `tb_usuario_status` (`usuario_status_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_usuario`
--

/*!40000 ALTER TABLE `tb_usuario` DISABLE KEYS */;
INSERT INTO `tb_usuario` (`usuario_id`,`usuario_nome`,`usuario_login`,`usuario_senha`,`usuario_status_id`,`usuario_nivel_id`) VALUES 
 (1,'Jonas Mendes','tchoi','583a3f96476b1c216511d3143747d9d7',1,3)
/*!40000 ALTER TABLE `tb_usuario` ENABLE KEYS */;


--
-- Definition of table `tb_usuario_nivel`
--

DROP TABLE IF EXISTS `tb_usuario_nivel`;
CREATE TABLE `tb_usuario_nivel` (
  `usuario_nivel_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_nivel_titulo` varchar(150) NOT NULL,
  PRIMARY KEY (`usuario_nivel_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_usuario_nivel`
--

/*!40000 ALTER TABLE `tb_usuario_nivel` DISABLE KEYS */;
INSERT INTO `tb_usuario_nivel` (`usuario_nivel_id`,`usuario_nivel_titulo`) VALUES 
 (1,'Administrador'),
 (2,'Usuário'),
 (3,'ADM - Desenvolvedor');
/*!40000 ALTER TABLE `tb_usuario_nivel` ENABLE KEYS */;


--
-- Definition of table `tb_usuario_status`
--

DROP TABLE IF EXISTS `tb_usuario_status`;
CREATE TABLE `tb_usuario_status` (
  `usuario_status_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `usuario_status_titulo` varchar(150) NOT NULL,
  PRIMARY KEY (`usuario_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `tb_usuario_status`
--

/*!40000 ALTER TABLE `tb_usuario_status` DISABLE KEYS */;
INSERT INTO `tb_usuario_status` (`usuario_status_id`,`usuario_status_titulo`) VALUES 
 (1,'Ativo'),
 (2,'Inativo');
/*!40000 ALTER TABLE `tb_usuario_status` ENABLE KEYS */;


/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
