ALTER TABLE `user_alumni`
	ADD COLUMN `province_id` INT(10) NOT NULL AFTER `negara_other`,
	ADD COLUMN `city_id` INT(10) NOT NULL AFTER `province_id`,
	ADD COLUMN `city_other` VARCHAR(200) NOT NULL DEFAULT '' AFTER `city_id`,
	ADD COLUMN `company` VARCHAR(200) NULL DEFAULT NULL AFTER `nik`,
	ADD COLUMN `last_education` VARCHAR(20) NULL DEFAULT NULL AFTER `company`;