-- Add missing vertical column to campaigns table
ALTER TABLE `campaigns` ADD COLUMN `vertical` varchar(255) NULL AFTER `campaign_name`;

-- Update existing campaigns with a default vertical value
UPDATE `campaigns` SET `vertical` = 'General' WHERE `vertical` IS NULL;
