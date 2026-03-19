-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             12.11.0.7065
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for restaurante_db
CREATE DATABASE IF NOT EXISTS `restaurante_db` /*!40100 DEFAULT CHARACTER SET latin1 */;
USE `restaurante_db`;

-- Dumping structure for table restaurante_db.configuracion
CREATE TABLE IF NOT EXISTS `configuracion` (
  `clave` varchar(50) NOT NULL,
  `valor` varchar(255) NOT NULL,
  PRIMARY KEY (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table restaurante_db.configuracion: ~1 rows (approximately)
INSERT INTO `configuracion` (`clave`, `valor`) VALUES
	('logo', '1772736421_logo.jpg');

-- Dumping structure for table restaurante_db.detalle_ventas
CREATE TABLE IF NOT EXISTS `detalle_ventas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `venta_id` int(11) DEFAULT NULL,
  `producto_id` int(11) DEFAULT NULL,
  `cantidad` int(11) NOT NULL,
  `precio_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `venta_id` (`venta_id`),
  KEY `producto_id` (`producto_id`),
  CONSTRAINT `detalle_ventas_ibfk_1` FOREIGN KEY (`venta_id`) REFERENCES `ventas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `detalle_ventas_ibfk_2` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table restaurante_db.detalle_ventas: ~9 rows (approximately)
INSERT INTO `detalle_ventas` (`id`, `venta_id`, `producto_id`, `cantidad`, `precio_unitario`) VALUES
	(1, 1, 1, 2, 85.00),
	(2, 2, 1, 1, 85.00),
	(3, 3, 1, 1, 85.00),
	(4, 4, 1, 1, 85.00),
	(5, 5, 1, 1, 85.00),
	(6, 6, 2, 2, 95.00),
	(7, 7, 1, 20, 85.00),
	(8, 8, 1, 1, 85.00),
	(9, 9, 1, 1, 85.00);

-- Dumping structure for table restaurante_db.ingredientes
CREATE TABLE IF NOT EXISTS `ingredientes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `unidad_medida` varchar(20) NOT NULL,
  `stock_actual` decimal(10,2) DEFAULT '0.00',
  `par_stock` decimal(10,2) NOT NULL,
  `costo_unitario` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table restaurante_db.ingredientes: ~4 rows (approximately)
INSERT INTO `ingredientes` (`id`, `nombre`, `unidad_medida`, `stock_actual`, `par_stock`, `costo_unitario`) VALUES
	(1, 'Carne de Res', 'kg', 27.50, 10.00, 150.00),
	(2, 'Pan de Hamburguesa', 'unidades', 75.00, 20.00, 5.00),
	(3, 'Queso Cheddar', 'kg', 20.00, 3.00, 120.00),
	(4, 'Tomate', 'kg', 13.75, 5.00, 30.00);

-- Dumping structure for table restaurante_db.productos
CREATE TABLE IF NOT EXISTS `productos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `precio_venta` decimal(10,2) NOT NULL,
  `imagen` varchar(255) DEFAULT 'default.png',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table restaurante_db.productos: ~2 rows (approximately)
INSERT INTO `productos` (`id`, `nombre`, `precio_venta`, `imagen`) VALUES
	(1, 'Hamburguesa Clásica', 85.00, '1772736474_coffee.png'),
	(2, 'Hamburguesa con Queso', 95.00, '1772736490_FK_Bodo_Glimt_logo.svg.png');

-- Dumping structure for table restaurante_db.recetas
CREATE TABLE IF NOT EXISTS `recetas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `producto_id` int(11) DEFAULT NULL,
  `ingrediente_id` int(11) DEFAULT NULL,
  `cantidad_requerida` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `producto_id` (`producto_id`),
  KEY `ingrediente_id` (`ingrediente_id`),
  CONSTRAINT `recetas_ibfk_1` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE,
  CONSTRAINT `recetas_ibfk_2` FOREIGN KEY (`ingrediente_id`) REFERENCES `ingredientes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- Dumping data for table restaurante_db.recetas: ~3 rows (approximately)
INSERT INTO `recetas` (`id`, `producto_id`, `ingrediente_id`, `cantidad_requerida`) VALUES
	(2, 1, 2, 1.00),
	(3, 1, 4, 0.05),
	(4, 1, 1, 0.90);

-- Dumping structure for table restaurante_db.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `rol` enum('admin','cliente') NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- Dumping data for table restaurante_db.usuarios: ~3 rows (approximately)
INSERT INTO `usuarios` (`id`, `username`, `password`, `rol`) VALUES
	(1, 'admin', '123', 'admin'),
	(2, 'cliente', '123', 'cliente'),
	(3, 'jeff', '123', 'admin');

-- Dumping structure for table restaurante_db.ventas
CREATE TABLE IF NOT EXISTS `ventas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `fecha` datetime DEFAULT CURRENT_TIMESTAMP,
  `canal` enum('local','delivery','takeaway') NOT NULL,
  `total` decimal(10,2) DEFAULT '0.00',
  `usuario_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

-- Dumping data for table restaurante_db.ventas: ~9 rows (approximately)
INSERT INTO `ventas` (`id`, `fecha`, `canal`, `total`, `usuario_id`) VALUES
	(1, '2026-03-05 14:03:44', 'local', 170.00, NULL),
	(2, '2026-03-05 14:03:44', 'delivery', 85.00, NULL),
	(3, '2026-03-05 14:13:25', 'local', 85.00, 1),
	(4, '2026-03-05 14:13:29', 'local', 85.00, 1),
	(5, '2026-03-05 14:13:29', 'local', 85.00, 1),
	(6, '2026-03-05 14:20:26', 'local', 190.00, 2),
	(7, '2026-03-05 14:48:39', 'local', 1700.00, 3),
	(8, '2026-03-05 14:54:49', 'local', 85.00, 3),
	(9, '2026-03-06 10:08:41', 'local', 85.00, 3);

-- Dumping structure for table restaurante_db.visitas
CREATE TABLE IF NOT EXISTS `visitas` (
  `fecha` date NOT NULL,
  `contador` int(11) DEFAULT '1',
  PRIMARY KEY (`fecha`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table restaurante_db.visitas: ~0 rows (approximately)
INSERT INTO `visitas` (`fecha`, `contador`) VALUES
	('2026-03-05', 10),
	('2026-03-06', 4);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
