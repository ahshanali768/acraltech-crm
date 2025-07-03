-- Create allowed_locations table
CREATE TABLE IF NOT EXISTS `allowed_locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `radius_meters` int NOT NULL DEFAULT '1000',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `location_type` varchar(255) NOT NULL DEFAULT 'office',
  `notes` text,
  `created_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `allowed_locations_name_index` (`name`),
  KEY `allowed_locations_created_by_foreign` (`created_by`),
  CONSTRAINT `allowed_locations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert sample data (using admin user ID = 1)
INSERT INTO `allowed_locations` (`name`, `address`, `latitude`, `longitude`, `radius_meters`, `is_active`, `location_type`, `notes`, `created_by`, `created_at`, `updated_at`) VALUES
('Main Office', '123 Business Street, City, State 12345', 40.7589000, -73.9851000, 100, 1, 'office', 'Primary office location', 1, NOW(), NOW()),
('Remote Hub A', '456 Tech Avenue, City, State 12346', 40.7614000, -73.9776000, 150, 1, 'remote', 'Remote work hub for team A', 1, NOW(), NOW()),
('Branch Office', '789 Corporate Blvd, City, State 12347', 40.7505000, -73.9934000, 200, 1, 'branch', 'Secondary branch office', 1, NOW(), NOW());
