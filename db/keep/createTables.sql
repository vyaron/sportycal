-- This is Create tables vor V1.0
-- Need to run dbChanges after that to create an updated DB

drop database if exists `evento`;
create database `evento`;

use `evento`;

set FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `id`              bigint(20) unsigned NOT NULL auto_increment,
  `email`           varchar(255) NOT NULL,
  `pass`            varchar(50) NOT NULL,
  `full_name`       varchar(255) NOT NULL,
  `birthdate`       date default NULL,
  `country_id`      bigint(20) unsigned default NULL,
  `state_id`        bigint(20) unsigned default NULL,
  `city`            varchar(255) NULL,
  `address`         varchar(255)NULL,
  `zip_code`        varchar(255) NULL,
  `created_at`      timestamp NOT NULL default '0000-00-00 00:00:00',
  `updated_at`      timestamp NOT NULL default '0000-00-00 00:00:00',
  `activation_key`  varchar(255) default NULL,
  `activation_date` date default NULL,
  `ref_user_id`     bigint(20) unsigned NULL,
  `balance`         bigint(20) default '0',
  `type`            enum('SIMPLE','MASTER') default 'SIMPLE',
  `gender`          enum('M','F') NOT NULL,
  `last_login_date` date default NULL,
  `fb_code`         bigint(20) unsigned NULL,

  PRIMARY KEY  (`id`),
  KEY `keyUSER_email` (`email`),
  CONSTRAINT `fkUSER_country`     FOREIGN KEY (`country_id`) REFERENCES `country` (`id`),
  CONSTRAINT `fkUSER_state`       FOREIGN KEY (`state_id`) REFERENCES `state` (`id`),
  CONSTRAINT `fkUSER_user`        FOREIGN KEY (`ref_user_id`) REFERENCES `user` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user_cal`;
CREATE TABLE `user_cal` (
  `id`        bigint(20) unsigned NOT NULL auto_increment,
  `user_id`   bigint(20) unsigned NULL,
  `cal_id`    bigint(20) unsigned NULL,
  `cal_type`  varchar(255)NULL,

  `reminder1` int unsigned NULL,
  `reminder2` int unsigned NULL,
  
  `taken_at` timestamp null  default null,
  `updated_at` timestamp null  default null,

  PRIMARY KEY  (`id`),
  CONSTRAINT `fkUSERCAL_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `fkUSERCAL_cal` FOREIGN KEY (`cal_id`) REFERENCES `cal` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `category`;
CREATE TABLE `category` (
  `id`          bigint(20) unsigned NOT NULL auto_increment,
  `name`        varchar(255) NOT NULL,
  `image_path`  text,
  `by_user_id`  bigint(20) unsigned NULL,
  `approved_at` timestamp null  default null,
  `parent_id`  bigint(20) unsigned NULL,
  `rate`       int unsigned NULL,
  `cals_count` int unsigned NULL,

  PRIMARY KEY  (`id`),
  CONSTRAINT `fkCATEGORY_user` FOREIGN KEY (`by_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `fkCATEGORY_cat` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `cal`;
CREATE TABLE `cal` (
  `id`                    bigint(20) unsigned NOT NULL auto_increment,
  `by_user_id`            bigint(20) unsigned default NULL,
  `category_id`           bigint(20) unsigned NULL,
  `name`                  varchar(255) default NULL,
  `primary_slogan`        varchar(255) default NULL,
  `description`           varchar(500) default NULL,  
  `location`              varchar(500) default NULL,
  `image_path`            varchar(500) default NULL,
  `access_key`            varchar(255) default NULL,
  `created_at`            timestamp NOT NULL default '0000-00-00 00:00:00',
  `updated_at`            timestamp NOT NULL default '0000-00-00 00:00:00',

  PRIMARY KEY  (`id`),
  KEY `keyCAL_user` (`by_user_id`),
  CONSTRAINT `fkCAL_user`           FOREIGN KEY (`by_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `fkCAL_category`       FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `event`;
CREATE TABLE `event` (
  `id`          bigint(20) unsigned NOT NULL auto_increment,
  `cal_id`      bigint(20) unsigned default NULL,
  `name`        varchar(255) default NULL,
  `description` varchar(500) default NULL,  
  `image_path`  varchar(500) default NULL,
  `location`    varchar(500) default NULL,
  `tz`          varchar(80) default NULL,
  `starts_at`   timestamp NOT NULL default '0000-00-00 00:00:00',
  `ends_at`     timestamp NOT NULL default '0000-00-00 00:00:00',
  `created_at`  timestamp NOT NULL default '0000-00-00 00:00:00',
  `updated_at`  timestamp NOT NULL default '0000-00-00 00:00:00',

  PRIMARY KEY  (`id`),
  KEY `keyEVENT_cal` (`cal_id`),
  CONSTRAINT `fkEVENT_cal` FOREIGN KEY (`cal_id`) REFERENCES `cal` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `contact`;
CREATE TABLE `contact` (
  `id`              bigint(20) unsigned NOT NULL auto_increment,
  `subject`         varchar(255) NOT NULL,
  `message`         text,
  `created_at`      timestamp NOT NULL default '0000-00-00 00:00:00',
  `by_user_id`      bigint(20) unsigned default NULL,
  `sender_name`     varchar(255) default NULL,
  `sender_email`    varchar(255) default NULL,
  PRIMARY KEY  (`id`),
  KEY `keyCONTACT_user` (`by_user_id`),
  CONSTRAINT `fkCONTACT_user` FOREIGN KEY (`by_user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



DROP TABLE IF EXISTS `invitation`;
CREATE TABLE `invitation` (
  `id`          bigint(20) unsigned NOT NULL auto_increment,
  `by_user_id`      bigint(20) unsigned default NULL,
  `cal_id`      bigint(20) unsigned NOT NULL,
  `event_id`    bigint(20) unsigned NOT NULL,  
  `email`       varchar(255) default NULL,

  `created_at`  timestamp NOT NULL default '0000-00-00 00:00:00',

  PRIMARY KEY  (`id`),
  KEY `keyINV_user` (`by_user_id`),
  KEY `keyINV_email` (`email`),
  KEY `keyINV_cal` (`cal_id`),
  KEY `keyEVENT` (`event_id`),
  CONSTRAINT `fkINV_user`  FOREIGN KEY (`by_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `fkINV_cal`    FOREIGN KEY (`cal_id`) REFERENCES `cal` (`id`),
  CONSTRAINT `fkINV_event`  FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `country`;
CREATE TABLE `country` (
  `id`        bigint(20) unsigned NOT NULL auto_increment,
  `name`      varchar(255) NOT NULL,
  `iso`       char(2) default NULL,
  `iso3`      char(3) default NULL,
  `numcode`   smallint(6) default NULL,
  PRIMARY KEY  (`id`),
  KEY `iso` (`iso`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `state`;
CREATE TABLE `state` (
  `id`          bigint(20) unsigned NOT NULL auto_increment,
  `country_id`  bigint(20) unsigned NOT NULL,
  `name`        varchar(255) NOT NULL,
  `regionCode`  varchar(50) null ,
  PRIMARY KEY  (`id`),
  KEY `country_id` (`country_id`),
  CONSTRAINT `state_country_fk` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `city`;
CREATE TABLE `city` (
  `id` bigint(20) unsigned NOT NULL auto_increment,
  `country_id` bigint(20) unsigned NOT NULL,
  `state_id` bigint(20) unsigned NULL,
  `name` varchar(255) NOT NULL,
  `nameascii` varchar(255)  NULL,
  `by_user_id` bigint(20) unsigned default NULL,
  `approved_at` timestamp null  default null,
  `latitude` float  default NULL,
  `longitude` float  default NULL,
  `zoom` smallint(6) default NULL,
  PRIMARY KEY  (`id`),
  KEY `keyCountry` (`country_id`),
  KEY `keyState` (`state_id`),
  KEY `keyByUser` (`by_user_id`),

  CONSTRAINT `fkCityUser` FOREIGN KEY (`by_user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `fkCityCountry` FOREIGN KEY (`country_id`) REFERENCES `country` (`id`),
  CONSTRAINT `fkCityState` FOREIGN KEY (`state_id`) REFERENCES `state` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user_search`;
CREATE TABLE `user_search` (
  `id`        bigint(20) unsigned NOT NULL auto_increment,
  `user_id`   bigint(20) unsigned NULL,
  `str`      varchar(255) NOT NULL,
  
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',

  PRIMARY KEY  (`id`),
  CONSTRAINT `fkUSERSEARCH_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



set FOREIGN_KEY_CHECKS = 1;


