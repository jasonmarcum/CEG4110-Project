CREATE DATABASE `ProcessedImages`;
USE `ProcessedImages`;
CREATE TABLE `ProcessedImages`.`Image_Info` 
            ( `id` INT(1) NOT NULL AUTO_INCREMENT ,
              `url` VARCHAR(256) NOT NULL , 
              `food_score` FLOAT(1) NOT NULL , 
              `not_food_score` FLOAT(1) NOT NULL , 
              `composite_score` FLOAT(1) NOT NULL , 
              `is_food` BOOLEAN NOT NULL , 
              `ip` VARCHAR(15) NOT NULL , 
              `date_time` TIMESTAMP(1) NOT NULL DEFAULT CURRENT_TIMESTAMP(1) , 
              PRIMARY KEY (`id`)) ENGINE = InnoDB;
SHOW DATABASES;


