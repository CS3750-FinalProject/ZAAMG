# ZAAMG Database Creation Script
# October 27, 2016
# Gisela Chodos
# CS 3750
# Final Project

DROP DATABASE IF EXISTS ZAAMG;

CREATE DATABASE IF NOT EXISTS ZAAMG

  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
  
  
USE `zaamg`; 
  
GRANT USAGE ON *.* TO 'zaamg'@'localhost';

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER ON `zaamg`.`zaamg` TO 'zaamg'@'localhost';

CREATE TABLE `Section` 
( `section_id` INT NOT NULL AUTO_INCREMENT , 
`course_id` INT NOT NULL ,
`prof_id` SMALLINT NOT NULL ,
`classroom_id` INT NOT NULL ,
`sem_id` INT NOT NULL,
`section_days` VARCHAR(40) NOT NULL ,
`section_start_time` TIME NOT NULL ,
`section_end_time` TIME NOT NULL,
`section_block` INT NOT NULL, 
`section_capacity` INT NOT NULL,
PRIMARY KEY (`section_id`) );


CREATE TABLE `Semester` 
( `sem_id` INT NOT NULL AUTO_INCREMENT , 
`sem_year` CHAR(4) NOT NULL ,
`sem_season` VARCHAR(6) NOT NULL ,
`sem_num_weeks` SMALLINT ,
`sem_start_date` DATETIME NOT NULL ,
`sem_first_block_start_date` DATETIME ,
`sem_second_block_start_date` DATETIME, 
PRIMARY KEY (`sem_id`) );


CREATE TABLE `Schedule`
( `schedule_id` INT NOT NULL AUTO_INCREMENT,
PRIMARY KEY (`schedule_id`) );


CREATE TABLE `Schedule_Section` 
( `schedule_id` INT NOT NULL,  
`section_id` INT NOT NULL,    
PRIMARY KEY  (`schedule_id`, `section_id`));


CREATE TABLE `Course` 
( `course_id` INT NOT NULL AUTO_INCREMENT , 
`course_code` CHAR(10) NOT NULL ,
`course_title` VARCHAR(50) NOT NULL ,
`course_capacity` SMALLINT ,
`course_credits` SMALLINT NOT NULL,
`dept_id` INT NOT NULL, 
PRIMARY KEY (`course_id`) );


CREATE TABLE `Professor` 
( `prof_id` SMALLINT NOT NULL AUTO_INCREMENT , 
`prof_first` VARCHAR(30) NOT NULL ,
`prof_last` VARCHAR(30) NOT NULL ,
`prof_email` VARCHAR(50) NOT NULL UNIQUE,
`dept_id` INT NOT NULL,
PRIMARY KEY (`prof_id`) );


CREATE TABLE `Classroom` 
( `classroom_id` INT NOT NULL AUTO_INCREMENT , 
`classroom_number` CHAR(10) NOT NULL ,
`classroom_capacity` SMALLINT NOT NULL ,
`classroom_workstations` SMALLINT,
`building_id` INT NOT NULL,
PRIMARY KEY (`classroom_id`) );


CREATE TABLE `Building` 
( `building_id` INT NOT NULL AUTO_INCREMENT , 
`building_code` CHAR(3) NOT NULL ,
`building_name` VARCHAR(30) NOT NULL ,
`campus_id` SMALLINT NOT NULL,
PRIMARY KEY (`building_id`) );


CREATE TABLE `Campus` 
( `campus_id` SMALLINT NOT NULL AUTO_INCREMENT , 
`campus_name` VARCHAR(50) NOT NULL UNIQUE,
PRIMARY KEY (`campus_id`) );


CREATE TABLE `Department` 
( `dept_id` INT NOT NULL AUTO_INCREMENT , 
`dept_name` VARCHAR(20) NOT NULL UNIQUE,
`dept_code` CHAR(4) NOT NULL UNIQUE,
PRIMARY KEY (`dept_id`) );


CREATE TABLE `User` 
( `user_id` INT NOT NULL AUTO_INCREMENT , 
`user_login` VARCHAR(20) NOT NULL UNIQUE,
`user_admin` CHAR(1) NOT NULL ,
`dept_id` INT ,
PRIMARY KEY (`user_id`) );


ALTER TABLE `Section`
ADD CONSTRAINT fk_course_id_1
FOREIGN KEY (`course_id`)
REFERENCES Course(`course_id`)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE `Section`
ADD CONSTRAINT fk_prof_id_1
FOREIGN KEY (`prof_id`)
REFERENCES Professor(`prof_id`)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE `Section`
ADD CONSTRAINT fk_classroom_id_1
FOREIGN KEY (`classroom_id`)
REFERENCES Classroom(`classroom_id`)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE `Section`
ADD CONSTRAINT fk_sem_id_1
FOREIGN KEY (`sem_id`)
REFERENCES Semester(`sem_id`)
ON UPDATE CASCADE
ON DELETE CASCADE;

ALTER TABLE `Schedule_Section`
ADD CONSTRAINT fk_schedule_id_1
FOREIGN KEY (`schedule_id`)
REFERENCES Schedule(`schedule_id`)
ON UPDATE NO ACTION
ON DELETE NO ACTION;

ALTER TABLE `Schedule_Section`
ADD CONSTRAINT fk_section_id_1
FOREIGN KEY (`section_id`)
REFERENCES Section(`section_id`)
ON UPDATE NO ACTION
ON DELETE NO ACTION;


ALTER TABLE `Course`
ADD CONSTRAINT fk_dept_id_1
FOREIGN KEY (`dept_id`)
REFERENCES Department(`dept_id`)
ON UPDATE CASCADE
ON DELETE CASCADE;


ALTER TABLE `Professor`
ADD CONSTRAINT fk_dept_id_2
FOREIGN KEY (`dept_id`)
REFERENCES Department(`dept_id`)
ON UPDATE CASCADE
ON DELETE CASCADE;


ALTER TABLE `Classroom`
ADD CONSTRAINT fk_building_id_1
FOREIGN KEY (`building_id`)
REFERENCES Building(`building_id`)
ON UPDATE CASCADE
ON DELETE CASCADE;


ALTER TABLE `User`
ADD CONSTRAINT fk_dept_id_2
FOREIGN KEY (`dept_id`)
REFERENCES Department(`dept_id`)
ON UPDATE CASCADE
ON DELETE CASCADE;


ALTER TABLE `Building`
ADD CONSTRAINT fk_campus_id_1
FOREIGN KEY (`campus_id`)
REFERENCES Campus(`campus_id`)
ON UPDATE CASCADE
ON DELETE CASCADE;


