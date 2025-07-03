-- Create daily_passwords table
CREATE TABLE IF NOT EXISTS `daily_passwords` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `password_code` varchar(4) NOT NULL,
  `password_date` date NOT NULL,
  `generated_at` timestamp NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `daily_passwords_password_date_unique` (`password_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert today's password
INSERT INTO `daily_passwords` (`password_code`, `password_date`, `generated_at`, `created_at`, `updated_at`) VALUES
('1234', CURDATE(), NOW(), NOW(), NOW())
ON DUPLICATE KEY UPDATE 
`password_code` = '1234', 
`generated_at` = NOW(), 
`updated_at` = NOW();
