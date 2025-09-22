-- Script para agregar las tablas del módulo Tipos de Cobro
-- Ejecutar después de tener las tablas base del sistema

-- ===================================================================
-- Tabla: tipos_cobro
-- Descripción: Configuración de tipos de cobro entre empresas
-- ===================================================================

CREATE TABLE `tipos_cobro` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `empresa_principal_id` bigint unsigned NOT NULL,
  `empresa_contratista_id` bigint unsigned NOT NULL,
  `tipo_cobro` enum('uf','pesos') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pesos',
  `tipo_pago` enum('webpay','factura') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'factura',
  `activo` tinyint(1) NOT NULL DEFAULT '1',
  `observaciones` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_empresa_principal_contratista` (`empresa_principal_id`,`empresa_contratista_id`),
  KEY `tipos_cobro_empresa_contratista_id_foreign` (`empresa_contratista_id`),
  CONSTRAINT `tipos_cobro_empresa_contratista_id_foreign` FOREIGN KEY (`empresa_contratista_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `tipos_cobro_empresa_principal_id_foreign` FOREIGN KEY (`empresa_principal_id`) REFERENCES `empresas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- Tabla: tipo_cobro_rangos
-- Descripción: Rangos de cobro por cantidad de trabajadores
-- ===================================================================

CREATE TABLE `tipo_cobro_rangos` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tipo_cobro_id` bigint unsigned NOT NULL,
  `trabajadores_desde` int NOT NULL,
  `trabajadores_hasta` int NOT NULL,
  `monto` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `tipo_cobro_rangos_tipo_cobro_id_foreign` (`tipo_cobro_id`),
  KEY `tipo_cobro_rangos_trabajadores_desde_trabajadores_hasta_index` (`tipo_cobro_id`,`trabajadores_desde`,`trabajadores_hasta`),
  CONSTRAINT `tipo_cobro_rangos_tipo_cobro_id_foreign` FOREIGN KEY (`tipo_cobro_id`) REFERENCES `tipos_cobro` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ===================================================================
-- Datos de ejemplo (opcional - eliminar en producción)
-- ===================================================================

-- Ejemplo de configuración entre dos empresas
-- NOTA: Reemplazar los IDs de empresa por los correctos de tu base de datos

/*
INSERT INTO `tipos_cobro` VALUES
(1, 1, 2, 'pesos', 'factura', 1, 'Configuración inicial para empresa contratista', NOW(), NOW());

INSERT INTO `tipo_cobro_rangos` VALUES
(1, 1, 1, 10, 50000.00, NOW(), NOW()),
(2, 1, 11, 20, 75000.00, NOW(), NOW()),
(3, 1, 21, 50, 100000.00, NOW(), NOW()),
(4, 1, 51, 100, 150000.00, NOW(), NOW());
*/

-- ===================================================================
-- Verificación de integridad
-- ===================================================================

-- Verificar que las tablas se crearon correctamente
SELECT
    TABLE_NAME,
    TABLE_ROWS,
    DATA_LENGTH,
    INDEX_LENGTH
FROM information_schema.TABLES
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('tipos_cobro', 'tipo_cobro_rangos');

-- Verificar las foreign keys
SELECT
    TABLE_NAME,
    COLUMN_NAME,
    CONSTRAINT_NAME,
    REFERENCED_TABLE_NAME,
    REFERENCED_COLUMN_NAME
FROM information_schema.KEY_COLUMN_USAGE
WHERE TABLE_SCHEMA = DATABASE()
  AND TABLE_NAME IN ('tipos_cobro', 'tipo_cobro_rangos')
  AND REFERENCED_TABLE_NAME IS NOT NULL;