-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema gohealthdb
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema gohealthdb
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `gohealthdb` DEFAULT CHARACTER SET utf8 ;
USE `gohealthdb` ;

-- -----------------------------------------------------
-- Table `gohealthdb`.`users`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gohealthdb`.`users` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `age` INT NULL,
  `weight` DECIMAL(4,1) NULL,
  `height` DECIMAL(3,2) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_usuarios_UNIQUE` (`id` ASC))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gohealthdb`.`groups`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gohealthdb`.`groups` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NULL,
  `description` VARCHAR(255) NULL,
  `group_photo` VARCHAR(255) NULL,
  `group_banner` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `owner_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_groups_users1_idx` (`owner_id` ASC),
  CONSTRAINT `fk_groups_users1`
    FOREIGN KEY (`owner_id`)
    REFERENCES `gohealthdb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gohealthdb`.`checkins`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gohealthdb`.`checkins` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `date` DATE NOT NULL,
  `type` VARCHAR(45) NULL,
  `media_path` VARCHAR(255) NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `group_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_checkins_usuarios_idx` (`user_id` ASC),
  INDEX `fk_checkins_groups1_idx` (`group_id` ASC),
  CONSTRAINT `fk_checkins_usuarios`
    FOREIGN KEY (`user_id`)
    REFERENCES `gohealthdb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_checkins_groups1`
    FOREIGN KEY (`group_id`)
    REFERENCES `gohealthdb`.`groups` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gohealthdb`.`streaks`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gohealthdb`.`streaks` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `current_streak` INT UNSIGNED NULL,
  `longest_streak` INT UNSIGNED NULL,
  `last_checkin_date` DATE NULL,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `user_id` INT UNSIGNED NOT NULL,
  `group_id` INT UNSIGNED NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `id_UNIQUE` (`id` ASC),
  INDEX `fk_streaks_users1_idx` (`user_id` ASC),
  INDEX `fk_streaks_groups1_idx` (`group_id` ASC),
  CONSTRAINT `fk_streaks_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `gohealthdb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_streaks_groups1`
    FOREIGN KEY (`group_id`)
    REFERENCES `gohealthdb`.`groups` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `gohealthdb`.`group_user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `gohealthdb`.`group_user` (
  `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `created_at` TIMESTAMP NULL,
  `updated_at` TIMESTAMP NULL,
  `group_id` INT UNSIGNED NOT NULL,
  `user_id` INT UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_table1_groups1_idx` (`group_id` ASC),
  INDEX `fk_table1_users1_idx` (`user_id` ASC),
  CONSTRAINT `fk_table1_groups1`
    FOREIGN KEY (`group_id`)
    REFERENCES `gohealthdb`.`groups` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_table1_users1`
    FOREIGN KEY (`user_id`)
    REFERENCES `gohealthdb`.`users` (`id`)
    ON DELETE CASCADE
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
