-- Add missing columns to users table for user management functionality

-- Add phone column
ALTER TABLE `users` ADD COLUMN `phone` varchar(255) NULL AFTER `name`;

-- Add plain_password column
ALTER TABLE `users` ADD COLUMN `plain_password` varchar(255) NULL AFTER `password`;

-- Add approval fields
ALTER TABLE `users` ADD COLUMN `approved_at` timestamp NULL AFTER `approval_status`;
ALTER TABLE `users` ADD COLUMN `approved_by` bigint unsigned NULL AFTER `approved_at`;
ALTER TABLE `users` ADD COLUMN `approval_notes` text NULL AFTER `approved_by`;

-- Add profile and avatar fields
ALTER TABLE `users` ADD COLUMN `profile_picture` varchar(255) NULL AFTER `approval_notes`;
ALTER TABLE `users` ADD COLUMN `avatar_style` varchar(255) NULL AFTER `profile_picture`;
ALTER TABLE `users` ADD COLUMN `avatar_seed` varchar(255) NULL AFTER `avatar_style`;

-- Add foreign key constraint for approved_by (if not exists)
-- Note: This might fail if there are existing records with invalid approved_by values
-- ALTER TABLE `users` ADD CONSTRAINT `users_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

-- Update existing users to have valid data
UPDATE `users` SET `phone` = '0000000000' WHERE `phone` IS NULL;
UPDATE `users` SET `plain_password` = 'temp123' WHERE `plain_password` IS NULL;
