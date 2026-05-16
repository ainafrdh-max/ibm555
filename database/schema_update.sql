-- Run once in phpMyAdmin on database `blank_perfume`

ALTER TABLE `orders`
  ADD COLUMN IF NOT EXISTS `delivery_name` VARCHAR(150) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `delivery_phone` VARCHAR(30) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `delivery_email` VARCHAR(150) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `delivery_address` TEXT DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `payment_type` ENUM('card','fpx') NOT NULL DEFAULT 'card',
  ADD COLUMN IF NOT EXISTS `card_holder` VARCHAR(150) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `card_number_masked` VARCHAR(30) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `card_expiry` VARCHAR(7) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `card_brand` VARCHAR(30) DEFAULT NULL,
  ADD COLUMN IF NOT EXISTS `fpx_bank` VARCHAR(100) DEFAULT NULL;

-- Fix product image filenames to match files in /img (MariaDB 10.4 may not support IF NOT EXISTS on ADD COLUMN — use setup_database.php instead if this fails)

UPDATE `products` SET `image` = 'blank-black-rose.png' WHERE `id` = 1;
UPDATE `products` SET `image` = 'blank-rose.png' WHERE `id` = 2;
UPDATE `products` SET `image` = 'blank-black.png' WHERE `id` = 3;
UPDATE `products` SET `image` = 'blank-lemon.png' WHERE `id` = 4;
UPDATE `products` SET `image` = 'blank-rose.png' WHERE `id` = 5;
UPDATE `products` SET `image` = 'blank-summer.png' WHERE `id` = 6;
