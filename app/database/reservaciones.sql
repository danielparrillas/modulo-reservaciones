--
-- Table structure for table `grupos_disponibilidades`
--
DROP TABLE IF EXISTS `grupos_disponibilidades`;
CREATE TABLE `grupos_disponibilidades` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(45) NOT NULL,
  `creado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `nombre_UNIQUE` (`nombre`)
) ENGINE=InnoDB AUTO_INCREMENT=3;
INSERT INTO `grupos_disponibilidades` VALUES (1,'Entradas','2023-04-24 14:36:08','2023-04-27 11:54:21',0),(2,'Parqueos','2023-04-24 14:36:41','2023-04-27 11:54:21',0);
--
-- Table structure for table `clientes_api`
--
DROP TABLE IF EXISTS `clientes_api`;
CREATE TABLE `clientes_api` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) NOT NULL,
  `api_key` varchar(500) NOT NULL,
  `activo` tinyint NOT NULL DEFAULT '1',
  `creado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3;
INSERT INTO `clientes_api` VALUES (1,'consumidor para pruebas','marn_bdps-2023?_3j--_0sdf20J09J988hj9',1,'2023-04-24 19:50:40','2023-04-26 10:40:58',0),(2,'mantenimiento local para pruebas','marn_bdps-2023jJ99u89h87T6UjiIU87hUI',1,'2023-04-26 10:23:19','2023-04-26 10:40:40',0);
--
-- Table structure for table `lugares_turisticos`
--
DROP TABLE IF EXISTS `lugares_turisticos`;
CREATE TABLE `lugares_turisticos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `anp_id` int NOT NULL,
  `municipio_id` int NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `permite_acampar` tinyint NOT NULL DEFAULT '1',
  `activo` tinyint NOT NULL DEFAULT '1',
  `creado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14;

INSERT INTO `lugares_turisticos` VALUES (1,0,0,'Parque Nacional Montecristo',1,0,'2023-04-24 17:59:52','2023-05-11 10:42:12',0),(2,0,0,'Parque Nacional Los Volcanes Sector San Blas',0,1,'2023-04-24 17:59:52','2023-04-24 17:59:52',0),(3,0,0,'La Magdalena',1,1,'2023-04-24 17:59:52','2023-04-24 17:59:52',0),(4,0,0,'Complejo El Taquillo',0,1,'2023-04-24 17:59:52','2023-04-24 17:59:52',0),(5,0,0,'Parque Nacional San Diego y San Felipe Las Barras',0,1,'2023-04-24 17:59:52','2023-04-24 17:59:52',0),(6,0,0,'Barra de Santiago, Canal El Zapatero',1,1,'2023-04-24 17:59:52','2023-04-24 17:59:52',0),(7,0,0,'Complejo Los Volcanes Los Andes',1,0,'2023-04-24 17:59:52','2023-05-11 10:42:12',0),(8,0,0,'Complejo Conchagua',1,1,'2023-04-24 17:59:52','2023-04-24 17:59:52',0),(9,0,0,'Laguna de las ranas',1,1,'2023-04-24 17:59:52','2023-04-24 17:59:52',0),(10,0,0,'Cerro el Aguila',1,1,'2023-04-24 17:59:52','2023-04-24 17:59:52',0),(11,0,0,'El imposible Sector San Benito',1,1,'2023-04-24 17:59:52','2023-04-24 17:59:52',0),(12,0,0,'El Imposible Sector San Francisco',0,1,'2023-04-24 17:59:52','2023-04-24 17:59:52',0),(13,0,0,'El Imposible Sector La Fincona',1,1,'2023-04-24 17:59:52','2023-04-24 17:59:52',0);
--
-- Table structure for table `periodos_deshabilitados`
--
DROP TABLE IF EXISTS `periodos_deshabilitados`;
CREATE TABLE `periodos_deshabilitados` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lugar_id` int NOT NULL,
  `inicio` date NOT NULL,
  `fin` date NOT NULL,
  `creado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_deshabilitados_lugar_idx` (`lugar_id`),
  CONSTRAINT `fk_periododes_lugar` FOREIGN KEY (`lugar_id`) REFERENCES `lugares_turisticos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2;
INSERT INTO `periodos_deshabilitados` VALUES (1,1,'1970-01-01','2023-01-01','2023-04-24 19:48:34','2023-04-24 19:48:34',0);
--
-- Table structure for table `servicios`
--
DROP TABLE IF EXISTS `servicios`;
CREATE TABLE `servicios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `grupo_disponibilidad_id` int NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `creado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint NOT NULL DEFAULT '0',
  `descripcion` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_servicio_tipo_idx` (`grupo_disponibilidad_id`),
  CONSTRAINT `fk_servicio_grupodisponibilidad` FOREIGN KEY (`grupo_disponibilidad_id`) REFERENCES `grupos_disponibilidades` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9;

INSERT INTO `servicios` VALUES (1,1,'Persona nacional (13 - 59 años)',3.00,'2023-04-24 17:20:09','2023-04-24 11:48:54',0,'Entrada a personas salvadoreñas con una edad entre 13 y 59 años'),(2,1,'Persona extranjera (13 - 59 años)',6.00,'2023-04-24 17:21:58','2023-04-24 11:48:54',0,'Entrada a personas que no tengan DUI con una edad entre 13 y 59 años'),(3,1,'Estudiante privado (13 años en adelante)',1.00,'2023-04-24 17:24:19','2023-04-24 11:48:54',0,'Entrada para estudiantes de institución privada con una edad de 13 en adelante'),(4,1,'Estudiante publico (13 años en adelante)',0.50,'2023-04-24 17:24:19','2023-04-24 11:48:54',0,'Entrada para estudiantes de institución pública con una edad de 13 en adelante'),(5,1,'Tercera edad (60 años en adelante)',0.00,'2023-04-24 17:30:13','2023-04-24 11:48:54',0,'Entrada para personas de la tercera edad (60 años en adelante)'),(6,1,'Niño (hasta los 12 años)',0.00,'2023-04-24 17:33:40','2023-04-24 11:48:54',0,'Entrada para niños de 12 años o menos'),(7,2,'Vehículo pesado',2.00,'2023-04-24 17:37:45','2023-04-24 11:48:54',0,'Espacio de estacionamiento para vehículo pesado'),(8,2,'Vehículo liviano',1.00,'2023-04-24 17:37:45','2023-04-24 11:48:54',0,'Espacio de estacionamiento para vehículo liviano');
--
-- Table structure for table `solicitudes`
--
DROP TABLE IF EXISTS `solicitudes`;
CREATE TABLE `solicitudes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `body` json NOT NULL,
  `metodo` varchar(45) NOT NULL,
  `creado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `fk_solicitud_cliente_idx` (`cliente_id`),
  CONSTRAINT `fk_solicitud_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes_api` (`id`)
) ENGINE=InnoDB;
--
-- Table structure for table `disponibilidades_lugares_gruposservicios`
--
DROP TABLE IF EXISTS `disponibilidades_lugares_gruposservicios`;
CREATE TABLE `disponibilidades_lugares_gruposservicios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `lugar_id` int NOT NULL,
  `grupo_id` int NOT NULL,
  `cantidad_maxima` int NOT NULL,
  `creado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_lugar_gruposervicio` (`lugar_id`,`grupo_id`) COMMENT 'No puede repetirse un lugar con el mismo grupo',
  KEY `fk_disponibilidad_lugar_idx` (`lugar_id`),
  KEY `fk_disponibilidad_servicio_idx` (`grupo_id`),
  CONSTRAINT `fk_disponibilidad_gruposervicio` FOREIGN KEY (`grupo_id`) REFERENCES `grupos_disponibilidades` (`id`),
  CONSTRAINT `fk_disponibilidad_lugar` FOREIGN KEY (`lugar_id`) REFERENCES `lugares_turisticos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29;
INSERT INTO `disponibilidades_lugares_gruposservicios` VALUES (1,1,1,250,'2023-04-24 18:14:22','2023-04-24 18:14:22',0),(2,1,2,50,'2023-04-24 18:14:22','2023-04-24 18:14:22',0),(3,2,1,600,'2023-04-24 18:16:26','2023-04-24 18:16:26',0),(4,2,2,0,'2023-04-24 18:16:26','2023-04-24 18:16:26',0),(5,3,1,40,'2023-04-24 18:16:26','2023-04-24 18:16:26',0),(6,3,2,5,'2023-04-24 18:16:26','2023-04-24 18:16:26',0),(7,4,1,100,'2023-04-24 18:17:11','2023-04-24 18:17:11',0),(8,4,2,5,'2023-04-24 18:17:11','2023-04-24 18:17:11',0),(9,5,1,75,'2023-04-24 18:19:22','2023-04-24 18:19:22',0),(10,5,2,8,'2023-04-24 18:19:22','2023-04-24 18:19:22',0),(11,6,1,30,'2023-04-24 18:19:22','2023-04-24 18:19:22',0),(12,6,2,0,'2023-04-24 18:19:22','2023-04-24 18:19:22',0),(13,7,1,300,'2023-04-24 18:53:36','2023-04-24 18:53:36',0),(14,7,2,10,'2023-04-24 18:53:36','2023-04-24 18:53:36',0),(15,8,1,150,'2023-04-24 18:53:36','2023-04-24 18:53:36',0),(16,8,2,5,'2023-04-24 18:53:36','2023-04-24 18:53:36',0),(17,9,1,200,'2023-04-24 18:55:28','2023-04-24 18:55:28',0),(18,9,2,20,'2023-04-24 18:55:28','2023-04-24 18:55:28',0),(19,10,1,150,'2023-04-24 18:55:28','2023-04-24 18:55:28',0),(20,10,2,5,'2023-04-24 18:55:28','2023-04-24 18:55:28',0),(21,11,1,150,'2023-04-24 18:57:30','2023-04-24 18:57:30',0),(22,11,2,10,'2023-04-24 18:57:30','2023-04-24 18:57:30',0),(23,12,1,150,'2023-04-24 18:57:30','2023-04-24 18:57:30',0),(24,12,2,10,'2023-04-24 18:57:30','2023-04-24 18:57:30',0),(25,13,1,50,'2023-04-24 18:57:30','2023-04-24 18:57:30',0),(26,13,2,10,'2023-04-24 18:57:30','2023-04-24 18:57:30',0);
--
-- Table structure for table `reservaciones`
--
DROP TABLE IF EXISTS `reservaciones`;
CREATE TABLE `reservaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cliente_id` int NOT NULL,
  `lugar_id` int NOT NULL,
  `nombres` varchar(45) NOT NULL,
  `apellidos` varchar(45) NOT NULL,
  `dui` varchar(45) NOT NULL,
  `pagada` tinyint NOT NULL,
  `inicio` date NOT NULL,
  `fin` date NOT NULL,
  `creado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint NOT NULL DEFAULT '0',
  `clave_acceso` varchar(45) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_reservacion_cliente_idx` (`cliente_id`),
  KEY `fk_reservacion_lugar_idx` (`lugar_id`),
  CONSTRAINT `fk_reservacion_cliente` FOREIGN KEY (`cliente_id`) REFERENCES `clientes_api` (`id`),
  CONSTRAINT `fk_reservacion_lugar` FOREIGN KEY (`lugar_id`) REFERENCES `lugares_turisticos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19;

INSERT INTO `reservaciones` VALUES (1,1,1,'Daniel','Parrillas','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 08:59:42','2023-05-05 08:59:42',0,'72d342b52640'),(2,1,1,'Daniel','Parrillas','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 09:01:56','2023-05-05 09:01:56',0,'54cbd526ca15'),(3,1,1,'Daniel','Parrillas','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 09:02:19','2023-05-05 09:02:19',0,'45f4e0ee0e03'),(4,1,1,'Daniel','Parrillas','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 09:14:56','2023-05-05 09:14:56',0,'479241270f9a'),(5,1,1,'Daniel','Parrillas','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 09:15:16','2023-05-05 09:15:16',0,'2cc902308757'),(6,1,1,'Daniel','Parrillas','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 09:15:31','2023-05-05 09:15:31',0,'b90908ae5312'),(7,1,1,'Daniel','Parrillas','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 09:16:12','2023-05-05 09:16:12',0,'5402cd38695d'),(8,1,1,'Daniel','Parrillas','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 09:17:59','2023-05-05 09:17:59',0,'84ad40655096'),(9,1,1,'Pedro','Ortiz','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 10:39:40','2023-05-05 10:39:40',0,'a4105650e6f3'),(10,1,1,'Pedro','Ortiz','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 10:43:39','2023-05-05 10:43:39',0,'8f6eec4fabcd'),(11,1,1,'Pedro','Ortiz','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 10:44:22','2023-05-05 10:44:22',0,'f64aaf77a2e6'),(12,1,1,'Pedro','Ortiz','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 10:54:11','2023-05-05 10:54:11',0,'67c199afa85f'),(13,1,1,'Pedro','Ortiz','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 10:55:00','2023-05-05 10:55:00',0,'290a0eb3e57d'),(14,1,1,'Pedro','Ortiz','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 10:55:35','2023-05-05 10:55:35',0,'7e3fb0c77a8f'),(15,1,1,'Pedro','Ortiz','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 11:00:05','2023-05-05 11:00:05',0,'92225e1f4d52'),(16,1,1,'Pedro','Ortiz','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 11:00:21','2023-05-05 11:00:21',0,'bee491cfc124'),(17,1,1,'Pedro','Ortiz','06308333-4',1,'2023-05-05','2023-05-05','2023-05-05 11:01:05','2023-05-05 11:01:05',0,'41be3a5d468f'),(18,1,4,'Juan','Gonzales','06308333-4',1,'2023-05-08','2023-05-09','2023-05-05 11:01:40','2023-05-08 12:23:37',0,'a4ace13c56e2');
--
-- Table structure for table `detalles_reservaciones`
--
DROP TABLE IF EXISTS `detalles_reservaciones`;
CREATE TABLE `detalles_reservaciones` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reservacion_id` int NOT NULL,
  `servicio_id` int NOT NULL,
  `cantidad` int NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `creado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actualizado` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `eliminado` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_reservacionid_servicioid` (`reservacion_id`,`servicio_id`),
  KEY `fk_detalle_reservacion_idx` (`reservacion_id`),
  KEY `fk_detalle_servicio_idx` (`servicio_id`),
  CONSTRAINT `fk_detalle_reservacion` FOREIGN KEY (`reservacion_id`) REFERENCES `reservaciones` (`id`),
  CONSTRAINT `fk_detalle_servicio` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=44;
INSERT INTO `detalles_reservaciones` VALUES (1,4,1,5,3.00,'2023-05-05 09:14:56','2023-05-05 09:14:56',0),(2,4,2,0,6.00,'2023-05-05 09:14:56','2023-05-05 09:14:56',0),(3,4,3,1,1.00,'2023-05-05 09:14:56','2023-05-05 09:14:56',0),(4,4,4,1,0.50,'2023-05-05 09:14:56','2023-05-05 09:14:56',0),(5,5,1,5,3.00,'2023-05-05 09:15:16','2023-05-05 09:15:16',0),(6,5,2,0,6.00,'2023-05-05 09:15:16','2023-05-05 09:15:16',0),(7,5,3,1,1.00,'2023-05-05 09:15:16','2023-05-05 09:15:16',0),(8,5,4,1,0.50,'2023-05-05 09:15:16','2023-05-05 09:15:16',0),(9,7,1,5,3.00,'2023-05-05 09:16:12','2023-05-05 09:16:12',0),(10,7,2,0,6.00,'2023-05-05 09:16:12','2023-05-05 09:16:12',0),(11,7,3,1,1.00,'2023-05-05 09:16:12','2023-05-05 09:16:12',0),(12,7,4,1,0.50,'2023-05-05 09:16:12','2023-05-05 09:16:12',0),(13,8,1,5,3.00,'2023-05-05 09:18:00','2023-05-05 09:18:00',0),(14,8,2,0,6.00,'2023-05-05 09:18:00','2023-05-05 09:18:00',0),(15,8,3,1,1.00,'2023-05-05 09:18:00','2023-05-05 09:18:00',0),(16,8,4,1,0.50,'2023-05-05 09:18:00','2023-05-05 09:18:00',0),(17,10,1,5,3.00,'2023-05-05 10:43:39','2023-05-05 10:43:39',0),(18,10,2,0,6.00,'2023-05-05 10:43:39','2023-05-05 10:43:39',0),(19,10,3,1,1.00,'2023-05-05 10:43:39','2023-05-05 10:43:39',0),(20,10,4,1,0.50,'2023-05-05 10:43:39','2023-05-05 10:43:39',0),(21,10,6,1,0.00,'2023-05-05 10:43:39','2023-05-05 10:43:39',0),(22,14,1,5,3.00,'2023-05-05 10:55:35','2023-05-05 10:55:35',0),(23,14,2,0,6.00,'2023-05-05 10:55:35','2023-05-05 10:55:35',0),(24,14,3,1,1.00,'2023-05-05 10:55:35','2023-05-05 10:55:35',0),(25,14,4,1,0.50,'2023-05-05 10:55:35','2023-05-05 10:55:35',0),(26,14,6,1,0.00,'2023-05-05 10:55:35','2023-05-05 10:55:35',0),(27,15,1,5,3.00,'2023-05-05 11:00:05','2023-05-05 11:00:05',0),(28,15,2,0,6.00,'2023-05-05 11:00:05','2023-05-05 11:00:05',0),(29,15,3,1,1.00,'2023-05-05 11:00:05','2023-05-05 11:00:05',0),(30,15,4,1,0.50,'2023-05-05 11:00:05','2023-05-05 11:00:05',0),(31,15,6,1,0.00,'2023-05-05 11:00:05','2023-05-05 11:00:05',0),(32,16,1,5,3.00,'2023-05-05 11:00:21','2023-05-05 11:00:21',0),(33,16,2,0,6.00,'2023-05-05 11:00:21','2023-05-05 11:00:21',0),(34,16,3,1,1.00,'2023-05-05 11:00:21','2023-05-05 11:00:21',0),(35,16,4,1,0.50,'2023-05-05 11:00:21','2023-05-05 11:00:21',0),(36,16,6,1,0.00,'2023-05-05 11:00:21','2023-05-05 11:00:21',0),(37,18,1,0,3.00,'2023-05-05 11:01:40','2023-05-08 12:11:17',0),(38,18,2,0,6.00,'2023-05-05 11:01:40','2023-05-08 12:11:17',0),(39,18,3,0,1.00,'2023-05-05 11:01:40','2023-05-08 11:26:28',0),(40,18,4,0,0.50,'2023-05-05 11:01:40','2023-05-08 11:26:28',0),(41,18,6,0,0.00,'2023-05-05 11:01:40','2023-05-08 11:26:28',0),(42,18,7,0,2.00,'2023-05-08 12:10:40','2023-05-08 12:10:59',0),(43,18,8,0,1.00,'2023-05-08 12:10:59','2023-05-08 12:11:17',0);