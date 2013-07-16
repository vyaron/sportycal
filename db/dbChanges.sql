ALTER TABLE `partner` DROP `external_user_id`;
ALTER TABLE `partner` ADD `licence_start_at` timestamp NULL DEFAULT NULL;


----------------------------DONE ON PROD-------------------------------------------------------
-- ALTER TABLE user DROP INDEX fkUSER_email;
-- ALTER IGNORE TABLE user ADD UNIQUE INDEX(email);

ALTER TABLE `partner` ADD `external_user_id` VARCHAR( 64 ) NULL DEFAULT NULL AFTER `tz` , ADD INDEX ( `external_user_id` );
ALTER TABLE `partner` ADD `licence_code` VARCHAR( 512 ) NULL DEFAULT NULL AFTER `external_user_id` , ADD INDEX ( `licence_code` );
ALTER TABLE `partner` DROP `max_subscribers`;

----------------------------DONE ON PROD-------------------------------------------------------
ALTER TABLE `partner` ADD `max_subscribers` INT UNSIGNED NULL DEFAULT '100';

----------------------------DONE ON PROD-------------------------------------------------------
ALTER TABLE `user` CHANGE `type` `type` ENUM( 'SIMPLE', 'MASTER', 'PARTNER', 'MAILINGLIST' ) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT 'SIMPLE';

ALTER TABLE `user` 
ADD `tz` VARCHAR( 80 ) NULL DEFAULT NULL ,
ADD `is_subscribe` BOOLEAN NOT NULL DEFAULT FALSE;

CREATE TABLE IF NOT EXISTS `mailinglist` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `send_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
   
ALTER TABLE `mailinglist`
  ADD CONSTRAINT `mailinglist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

----------------------------DONE ON PROD-------------------------------------------------------

INSERT INTO `evento`.`category` (`id` ,`name` ,`image_path` ,`by_user_id` ,`approved_at` ,`parent_id` ,`rate` ,`cals_count` ,`category_ids_path` ,`deleted_at` ,`address_id` ,`partner_id` ,`is_public`)
VALUES ('7777', 'Never Miss', NULL , '2', NOW( ) , NULL , NULL , NULL , '7777' , NULL , NULL , NULL , '0');

ALTER TABLE `event` ADD `rec_type` VARCHAR( 128 ) NULL DEFAULT NULL AFTER `tags`;
ALTER TABLE `event` ADD `length` BIGINT UNSIGNED NULL AFTER `rec_pattern`;
ALTER TABLE `partner_desc` ADD `website` VARCHAR( 512 ) NULL DEFAULT NULL AFTER `cal_id`;
 
ALTER TABLE `event` ADD `pid` BIGINT(20) UNSIGNED NULL DEFAULT NULL AFTER `id` ,ADD INDEX ( `pid` );
ALTER TABLE `event` DROP FOREIGN KEY `event_ibfk_1` ,
ADD FOREIGN KEY ( `pid` ) REFERENCES `evento`.`event` (`id`) ON DELETE SET NULL ON UPDATE SET NULL ;

ALTER TABLE `event` ADD `tags` TEXT NULL AFTER `tz`; 

-- ---------------------------- CAMPUS TLV ----------------------------------
UPDATE `evento`.`cal` SET `name` = 'Campus Tel Aviv' WHERE `cal`.`id` =8699;
-- ---------------------------- CAMPUS TLV ----------------------------------

INSERT into `partner` values (null, 'CampusTLV', 'CampusTLV', null);

insert into user
  (email, pass, full_name, created_at, updated_at, type, gender)
values
  ('michal@campustelaviv.com', 'CampusTLV', 'Michal', '2013-04-05', '2013-04-05', 'PARTNER', 'F');
INSERT into `partner_user` values (null, 1982, 705);


insert into category
  (id, name, image_path, approved_at, parent_id, partner_id, is_public)
values
  (2003, 'Campus TLV', '/images/partner/campusTLV.jpg', '2013-04-05', null, 1982, false);

  -- ------------------------- END CAMPUS TLV ----------------------------------


INSERT into `partner` values (null, 'FTBpro', 'FTBpro', null);

INSERT INTO `evento`.`short_url` (`partner_id`, `url`, `hash`, `comment`) VALUES (10, 'http://www.888poker.com/poker-promotions/super-xl.htm', '888SuperXL', 'SuperXL Nov 2012');

-- remove cal, events - winner football
set foreign_key_checks = 0;
delete from event where cal_id IN (select id from cal where category_id=3479 AND partner_id = 1979);
delete from cal where category_id=3479 AND partner_id = 1979;
set foreign_key_checks = 1;
----------------------------------------------------

UPDATE `user` set type = 'SIMPLE' WHERE id = 443;

insert into `partner`  values (null, 'ifa', 'Israeli Football Association', 'Asia/Jerusalem');


-- Check for CalReq with no user-cal but matching hash (found ~4800)
SELECT count(*)
FROM cal_request cr, user_cal uc
WHERE   cr.user_cal_id is null AND cr.hash is not null AND  cr.hash = uc.hash;

-- Set the CalRequest user_cal_id accordingly:
UPDATE  cal_request cr, user_cal uc
SET     cr.user_cal_id = uc.id 
WHERE   cr.user_cal_id is null AND cr.hash is not null AND  cr.hash = uc.hash;


insert into user
  (email, pass, full_name, created_at, updated_at, type, gender)
values
  ('yossi@winner.co.il', 'Winner12x', 'Yossi Massri', '2012-09-09', '2012-09-09', 'PARTNER', 'M');



INSERT INTO `evento`.`short_url` (`partner_id`, `url`, `hash`, `comment`) VALUES ('1979', 'http://www.winner.co.il/?referer=sportYcal.com', 'winner', 'Winner homepage');
INSERT INTO `evento`.`short_url` (`partner_id`, `url`, `hash`, `comment`) VALUES ('1979', 'http://www.winner.co.il/?referer=sportYcal.com', 'winner-from-share', 'Winner homepage from facebook share action link');


----------------
set foreign_key_checks = 0;
delete from event where cal_id in (select id from cal where partner_id = 1979);
delete from cal where partner_id = 1979;
delete from category where partner_id = 1979 and id != 2100;
set foreign_key_checks = 1;
----------------
UPDATE `cal` set partner_id = null WHERE partner_id = 10 and category_ids_path not like '2600,%' and id != 2482


insert into `partner`  values (778, 'thepeople', 'thepeople', 'Asia/Jerusalem');


ALTER TABLE `event_stat` DROP FOREIGN KEY `fkEVSTAT_event` ;
ALTER TABLE `event_stat` ADD FOREIGN KEY ( `event_id` ) REFERENCES `evento`.`event` (`id`) ON DELETE CASCADE ;


insert into user
  (email, pass, full_name, created_at, updated_at, type, gender)
values
  ('Roni.Landau@888holdings.com', 'Random888', 'Roni Landau', '2012-05-27', '2012-05-27', 'PARTNER', 'F');
insert into partner_user values(null, 10, 538);




-- Need to create the CTG on local env
update cal set category_id=3001 where id = 4005; 


-- --------------------------DONE ON PROD-------------------------------------------------------

insert into `partner`  values (777, 'pokerstars', 'pokerstars', 'Asia/Jerusalem');

INSERT INTO `evento`.`category` (`id`, `name`, `image_path`, `by_user_id`, `approved_at`, `parent_id`, `rate`, `cals_count`, `category_ids_path`, `deleted_at`, `address_id`, `partner_id`, `is_public`) VALUES 
                                 (3000, 'Poker Stars', '/images/partner/777_1.jpg', NULL, NOW(), NULL, NULL, 4, '3000', NULL, NULL, 777, 0);

INSERT INTO `evento`.`cal` (`id`, `by_user_id`, `category_id`, `name`, `primary_slogan`, `description`, `location`, `image_path`, `access_key`, `created_at`, `updated_at`, `category_ids_path`, `deleted_at`, `rate`, `partner_id`, `is_public`) 
VALUES (4000, NULL, 3000, 'Regular', NULL, NULL, NULL, NULL, NULL, '2012-05-01 00:00:00', '2012-05-01 00:00:00', '3000', NULL, '0', 777, 0);
INSERT INTO `evento`.`cal` (`id`, `by_user_id`, `category_id`, `name`, `primary_slogan`, `description`, `location`, `image_path`, `access_key`, `created_at`, `updated_at`, `category_ids_path`, `deleted_at`, `rate`, `partner_id`, `is_public`) VALUES 
(4001, NULL, 3000, 'Satellite', NULL, NULL, NULL, NULL, NULL, '2012-05-01 00:00:00', '2012-05-01 00:00:00', '3000', NULL, '0', 777, 0);
INSERT INTO `evento`.`cal` (`id`, `by_user_id`, `category_id`, `name`, `primary_slogan`, `description`, `location`, `image_path`, `access_key`, `created_at`, `updated_at`, `category_ids_path`, `deleted_at`, `rate`, `partner_id`, `is_public`) VALUES 
(4002, NULL, 3000, 'Special', NULL, NULL, NULL, NULL, NULL, '2012-05-01 00:00:00', '2012-05-01 00:00:00', '3000', NULL, '0', 777, 0);
INSERT INTO `evento`.`cal` (`id`, `by_user_id`, `category_id`, `name`, `primary_slogan`, `description`, `location`, `image_path`, `access_key`, `created_at`, `updated_at`, `category_ids_path`, `deleted_at`, `rate`, `partner_id`, `is_public`) VALUES
 (4003, null, 3000, 'Freeroll', NULL, NULL, NULL, NULL, NULL, '2012-05-01 00:00:00', '2012-05-01 00:00:00', '3000', NULL, '0', 777, 0);
-- --------------------------DONE ON IDO-------------------------------------------------------
insert into category
  (id, name, image_path, approved_at, parent_id, partner_id, is_public)
values
  (2600, '888', '/images/partner/888.png', '2012-04-01', null, 10, false);



insert into partner  values (10, '888holdings', '888 Holdings', 'Asia/Jerusalem');
insert into partner  values (9, 'ringtonepartner', 'ringtonepartner', 'Asia/Jerusalem');


  


ALTER TABLE `user_cal` ADD `reminder`  int(2) unsigned NOT NULL DEFAULT '1';

insert into short_url  (url, hash, comment, created_at)
values ('http://www.sportycal.com/main/friendsBirthday/src/cal-event-desc', 'birthday', 'link to birthday-cal from event-desc', '2011-02-12');



insert into partner  values (8, 'sportwiser', 'sportwiser', 'Asia/Jerusalem');

insert into short_url  (partner_id, url, hash, comment, created_at)
values (8, 'http://www.sportwiser.com', 'sportwiser', 'sportwiser homepage link from event-desc', '2011-02-11');







insert into short_url  (partner_id, url, hash, comment, created_at)
values (6, 'http://www.sportsevents365.com?a_aid=4f26c59635296&amp;a_bid=204845fd', 'tickets', 'sportsevents365 homepage link from event-desc', '2011-02-11');


UPDATE `evento`.`category` SET `image_path` = '/images/partner/1979_ctg1.gif' WHERE `category`.`id` =2100;
-- --------------------------DONE ON PROD-------------------------------------------------------
insert into partner  values (7, 'sport1', 'Sport1', 'Asia/Jerusalem');

insert into partner  values (6, 'sportsevents365-9123', 'SportsEvents365', 'Asia/Jerusalem');


insert into partner  values (5, 'cw-9855', 'ClickWise', 'Asia/Jerusalem');

DROP TABLE IF EXISTS `event_stat`;
CREATE TABLE `event_stat` (
  `id`                    bigint(20) unsigned NOT NULL auto_increment,
  `event_id`              bigint(20) unsigned NOT NULL,
  `type`                  varchar(50) NOT NULL,
  `text`                  text NOT NULL,
  `created_at`            timestamp NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  CONSTRAINT `fkEVSTAT_event`        FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ---------------------------------------------------------------------------------



insert into category
  (id, name, image_path, approved_at, parent_id, partner_id, is_public, category_ids_path, rate)
values
  (2500, 'Rugby', '/images/categories/rugby1.gif', '2012-01-01', null, null, true, '2500', 110);

-- ---------------------------------------------------------------------------------



update `category` set is_public = 0 where parent_id=2000;
update `cal` set is_public = 0 where id in (1802, 1812, 1813)

insert into partner  values (1, 'sportYcal', 'sportYcal', 'Asia/Jerusalem');
------------------------------------------------------------------------------



DROP TABLE IF EXISTS `user_birthday`;
CREATE TABLE `user_birthday` (
  `id`            bigint(20) unsigned NOT NULL auto_increment,
  `user_id`       bigint(20) unsigned NOT NULL,
  `full_name`             varchar(255) NOT NULL,
  `birthdate`             varchar(50) NOT NULL,
  `created_at`            timestamp NOT NULL default '0000-00-00 00:00:00',

  PRIMARY KEY  (`id`),
  KEY `fkUSERBIRTHDAY_user` (`user_id`),
  CONSTRAINT `fkUSERBIRTHDAY_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- DONE --
ALTER TABLE `user_fb_user` MODIFY `in_birthday_cal` tinyint(1) DEFAULT '0';

-- DONE --
update user set balance = 5;



-- DONE ON PROD:

insert into category
  (id, name, image_path, approved_at, parent_id, partner_id, is_public, category_ids_path)
values
  (2200, 'Friends Birthday', '/images/categories/friends1.gif', '2011-11-01', null, null, false, '2200');


ALTER TABLE `user_cal` ADD `birthday_cal_for_user_id`  bigint(20) unsigned NULL;
ALTER TABLE `user_cal` ADD FOREIGN KEY `fkUCAL_bcuid`  (`birthday_cal_for_user_id`) REFERENCES `user` (`id`);



ALTER TABLE `user_fb_user`    ADD `in_birthday_cal`  boolean default true;


ALTER TABLE `user` MODIFY `fb_code`  varchar(255) default NULL;
ALTER TABLE `user` ADD UNIQUE KEY `keyUSER_fbcode`  (`fb_code`);

DROP TABLE IF EXISTS `fb_user`;
CREATE TABLE `fb_user` (
  `id`                    bigint(20) unsigned NOT NULL auto_increment,
  `full_name`             varchar(255) NOT NULL,
  `birthdate`             varchar(50) NOT NULL,
  `fb_code`               varchar(255) NOT NULL,
  `created_at`            timestamp NOT NULL default '0000-00-00 00:00:00',

  PRIMARY KEY  (`id`),
  UNIQUE KEY `keyFBUSER_code` (`fb_code`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `user_fb_user`;
CREATE TABLE `user_fb_user` (
  `id`            bigint(20) unsigned NOT NULL auto_increment,
  `user_id`       bigint(20) unsigned NOT NULL,
  `fb_user_id`    bigint(20) unsigned NOT NULL,
  `created_at`    timestamp NOT NULL  default '0000-00-00 00:00:00',

  PRIMARY KEY  (`id`),
  CONSTRAINT `fkUFBUSER_user`        FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `fkUFBUSER_fbuser`      FOREIGN KEY (`fb_user_id`) REFERENCES `fb_user` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;




-- Version 2.2
update `partner` set `tz` = "Asia/Jerusalem";

ALTER TABLE `partner`    ADD `tz`  varchar(100) default NULL;
ALTER TABLE `user_cal`    ADD `label`  varchar(100) default NULL;

ALTER TABLE `user_cal`    ADD `hash`  varchar(100) default NULL;
ALTER TABLE `cal_request` ADD `hash`  varchar(100) default NULL;


-- Version 2.1

delete from category_link where type='bet';

insert into partner_user values(null, 1979, 433);

DROP TABLE IF EXISTS `partner_user`;
CREATE TABLE `partner_user` (
  `id`                bigint(20) unsigned NOT NULL auto_increment,
  `partner_id`        bigint(20) unsigned NULL,
  `user_id`           bigint(20) unsigned NULL,
  
  PRIMARY KEY  (`id`),
  KEY `keyPU_user` (`user_id`),  
  KEY `keyPU_partner` (`partner_id`),

  CONSTRAINT `fkPU_partner`  FOREIGN KEY (`partner_id`) REFERENCES `partner` (`id`),
  CONSTRAINT `fkPU_user`  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


update user set pass='kntyellowmouse' where id in (1, 2, 3, 4);

DROP TABLE IF EXISTS `short_url`;
CREATE TABLE `short_url` (
  `id`                    bigint(20) unsigned NOT NULL auto_increment,
  `cal_id`                bigint(20) unsigned NULL,
  `category_id`           bigint(20) unsigned NULL,
  `event_id`              bigint(20) unsigned NULL,
  `user_id`               bigint(20) unsigned NULL,
  `partner_id`            bigint(20) unsigned NULL,

  `url`                   text NOT NULL,
  `hash`                  varchar(250) NOT NULL,

  `label`                 varchar(255) NOT NULL,
  `comment`               varchar(255) NOT NULL,
  `created_at`            timestamp NOT NULL default '0000-00-00 00:00:00',
  `used_at`               timestamp NOT NULL default '0000-00-00 00:00:00',
  `count_used`            mediumint(8) unsigned DEFAULT 0,
  
  PRIMARY KEY  (`id`),
  KEY `keyURL_user` (`user_id`),  
  KEY `keyURL_partner` (`partner_id`),
  KEY `keyURL_ctg` (`category_id`),
  KEY `keyURL_cal` (`cal_id`),
  KEY `keyURL_event` (`event_id`),
  KEY `keyURL_hash` (`hash`),

  CONSTRAINT `fkURL_partner`  FOREIGN KEY (`partner_id`) REFERENCES `partner` (`id`),
  CONSTRAINT `fkURL_ctg`  FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `fkURL_cal`  FOREIGN KEY (`cal_id`) REFERENCES `cal` (`id`),
  CONSTRAINT `fkURL_event`  FOREIGN KEY (`event_id`) REFERENCES `event` (`id`),
  CONSTRAINT `fkURL_user`  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;



ALTER TABLE `intel` ADD `ip_address`  varchar(50) default NULL;
ALTER TABLE `contact` ADD `ip_address`  varchar(50) default NULL;
ALTER TABLE `user_cal` ADD `ip_address`  varchar(50) default NULL;
ALTER TABLE `user_search` ADD `ip_address`  varchar(50) default NULL;

ALTER TABLE `intel` ADD `user_cal_id`  bigint(20) unsigned default NULL;
ALTER TABLE `intel` ADD FOREIGN KEY `fkINTEL_ucid`  (`user_cal_id`) REFERENCES `user_cal` (`id`);






-- update category set is_public=1 where id =2100;



-- -----------------------------------------------------------------------------------------------


--------------------------- DONE ON LOCAL DESKTOP  ------------------------------------------



--------------------------- DONE ON LOCAL LAPTOP  ------------------------------------------




--------------------------- DONE ON PROD ------------------------------------------
DROP TABLE IF EXISTS `intel`;
CREATE TABLE `intel` (
  `id`                    bigint(20) unsigned NOT NULL auto_increment,
  `cal_id`                bigint(20) unsigned NULL,
  `category_id`           bigint(20) unsigned NULL,
  `event_id`              bigint(20) unsigned NULL,
  `user_id`               bigint(20) unsigned NULL,
  `partner_id`            bigint(20) unsigned NULL,
  `session_code`          varchar(255) NULL,
  `section`               varchar(255) NOT NULL,
  `action`                varchar(255) NULL,
  `label`                 varchar(255) NULL,
  `value`                 int(11) NULL,
  `created_at`            timestamp NOT NULL default '0000-00-00 00:00:00',
  
  PRIMARY KEY  (`id`),
  KEY `keyINTEL_partner` (`partner_id`),
  KEY `keyINTEL_ctg` (`category_id`),
  KEY `keyINTEL_cal` (`cal_id`),
  KEY `keyINTEL_event` (`event_id`),
  KEY `keyINTEL_user` (`user_id`),  

  CONSTRAINT `fkINTEL_partner`  FOREIGN KEY (`partner_id`) REFERENCES `partner` (`id`),
  CONSTRAINT `fkINTEL_ctg`  FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `fkINTEL_cal`  FOREIGN KEY (`cal_id`) REFERENCES `cal` (`id`),
  CONSTRAINT `fkINTEL_user`  FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  CONSTRAINT `fkINTEL_event`  FOREIGN KEY (`event_id`) REFERENCES `event` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;



select * from category where parent_id=777 and name in ('AEK Athens', 'Atletico Madrid', 'AZ Alkmaar', 'Club Brugge', 'Red Bull Salzburg');
update category set deleted_at = null where parent_id=777 and name in ('AEK Athens', 'Atletico Madrid', 'AZ Alkmaar', 'Club Brugge', 'Red Bull Salzburg');


insert into user
  (email, pass, full_name, created_at, updated_at, type, gender)
values
  ('eitan.golani@gmail.com', '7boom', 'Eitan Golani', '2011-08-15', '2011-08-15', 'MASTER', 'M'),
  ('rami.vachner@sportycal.com', '7boom', 'Rami Vachner', '2011-08-15', '2011-08-15', 'MASTER', 'M');

update category set rate = 10 where id in (2101, 2102);

ALTER TABLE `user_cal` ADD `category_id`  bigint(20) unsigned default NULL;
ALTER TABLE `user_cal` ADD FOREIGN KEY `fkUCAL_ctg`  (`category_id`) REFERENCES `category` (`id`);

ALTER TABLE `cal_request` MODIFY `cal_id`  bigint(20) unsigned default NULL;
ALTER TABLE `cal_request` ADD `category_id`  bigint(20) unsigned default NULL;
ALTER TABLE `cal_request` ADD FOREIGN KEY `fkCALREQ_ctg`  (`category_id`) REFERENCES `category` (`id`);


update category set image_path='/images/partner/1979_ctg1.gif' where id =2100; 


-- V1.8 Extras ??-07-2011
insert into category
  (id, name, image_path, approved_at, parent_id, partner_id, is_public)
values
  (2101, 'Snooker', '/images/categories/snooker1.gif', '2011-07-14', null, null, true);

insert into category
  (id, name, image_path, approved_at, parent_id, partner_id, is_public)
values
  (2102, 'Darts', '/images/categories/darts1.gif', '2011-07-14', null, null, true);


insert into partner 
values (1979, 'Winner-5712', 'Winner');

ALTER TABLE `cal` ADD `partner_id`  bigint(20) unsigned default NULL;
ALTER TABLE `cal` ADD `is_public`   tinyint unsigned default 1;

ALTER TABLE `cal` ADD FOREIGN KEY `fkCAL_partner`  (`partner_id`) REFERENCES `partner` (`id`);

insert into category
  (id, name, image_path, approved_at, parent_id, partner_id, is_public)
values
  (2100, 'θεθε - Winner', '/images/partner/1979_ctg1.png', '2011-07-14', null, 1979, false);


update category set image_path = '/images/partner/1979_ctg1.png' where id=2100; 


ALTER TABLE `user` CHANGE `type` `type` enum('SIMPLE', 'MASTER', 'PARTNER') default 'SIMPLE';
insert into user
  (email, pass, full_name, created_at, updated_at, type, gender)
values
  ('Mottiy@winner.co.il', 'winner12x', 'Motti Yochpaz', '2011-07-14', '2011-07-14', 'PARTNER', 'M');


-- V1.8 12-07-2011
ALTER TABLE `cal_request` ADD `partner_id`  bigint(20) unsigned default NULL;
ALTER TABLE `cal_request` ADD FOREIGN KEY `fkCALR_partner`  (`partner_id`) REFERENCES `partner` (`id`);

-- ??? ALTER TABLE `cal` ADD `partner_id`  bigint(20) unsigned default NULL;

insert into `category`(`id`,`name`,`image_path`,`by_user_id`,`approved_at`,`parent_id`, `rate`, `cals_count`, `is_public`) values
(2000,'TEST','/images/categories/test1.gif',101,'2011-07-10',null, 0, 0, 0);

insert into `category`(`id`,`name`,`image_path`,`by_user_id`,`approved_at`,`parent_id`, `rate`, `cals_count`, `is_public`) values
(2001,'TEST1',null,101,'2011-07-10',2000, 0, 0, 0);

insert into `category`(`id`,`name`,`image_path`,`by_user_id`,`approved_at`,`parent_id`, `rate`, `cals_count`, `is_public`) values
(2002,'TEST2',null,101,'2011-07-10',2000, 0, 0, 0);

insert into cal
  (id, by_user_id, category_id, name, primary_slogan, description, location, 
   image_path, access_key, created_at, updated_at, category_ids_path, deleted_at, 
   rate)
values
  (null, null, 2001, "sportYcal Test Calendar", null, null, 
   null, null, null, "2011-07-10", "2011-07-10", "2000,2001", null, 0); 


-- V1.7 08-07-2011
TRUNCATE TABLE cal_request;
delete from category_link_usage where user_cal_id is null;


ALTER TABLE `category` ADD `partner_id`  bigint(20) unsigned default NULL;
ALTER TABLE `category` ADD `is_public`   tinyint unsigned default 1;
ALTER TABLE `user_cal` ADD `partner_id`  bigint(20) unsigned default NULL;

ALTER TABLE `category` ADD FOREIGN KEY `fkCTG_partner`  (`partner_id`) REFERENCES `partner` (`id`);
ALTER TABLE `user_cal` ADD FOREIGN KEY `fkUCAL_partner`  (`partner_id`) REFERENCES `partner` (`id`);

DROP TABLE IF EXISTS `partner_desc`;
CREATE TABLE `partner_desc` (
  `id`                    bigint(20) unsigned NOT NULL auto_increment,
  `partner_id`            bigint(20) unsigned NULL,
  `description`           text NOT NULL,
  `category_id`           bigint(20) unsigned NULL,
  `cal_id`                bigint(20) unsigned NULL,
  `updated_at`            timestamp NOT NULL default '0000-00-00 00:00:00',
  
  PRIMARY KEY  (`id`),
  KEY `keyPDESC_partner` (`partner_id`),
  KEY `keyPDESC_ctg` (`category_id`),
  KEY `keyPDESC_cal` (`cal_id`),
  KEY `keyPDESC_partner_cal` (`partner_id`, `cal_id`),

  CONSTRAINT `fkPDESC_partner`  FOREIGN KEY (`partner_id`) REFERENCES `partner` (`id`),
  CONSTRAINT `fkPDESC_ctg`  FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `fkPDESC_cal`  FOREIGN KEY (`cal_id`) REFERENCES `cal` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- V1.6 20-06-2011
ALTER TABLE `cal` ADD `rate`  int(10) unsigned default 0;
update cal set rate = 10 where id = 1097;
update cal set image_path = 'cals.png' where id = 1097;


DROP TABLE IF EXISTS `partner`;
CREATE TABLE `partner` (
  `id` bigint(20) unsigned  NOT NULL AUTO_INCREMENT,
  `hash` varchar(100) NOT NULL DEFAULT '',
  `name` varchar(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ukPARTNERHash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into partner 
values (1976, '888Sport-0796', '888Sport'),
       (1977, 'GoPlanIt-4217', 'GoPlanIt'),
       (1978, 'andru', 'sportYcal Android');


DROP TABLE IF EXISTS `alias`;
CREATE TABLE `alias` (
  `id`                    bigint(20) unsigned NOT NULL auto_increment,
  `alias`                 varchar(255) NOT NULL,
  `category_id`           bigint(20) unsigned NULL,
  `partner_id`            bigint(20) unsigned NULL,
  
  PRIMARY KEY  (`id`),
  KEY `keyALIAS_ctg` (`category_id`),
  KEY `keyALIAS_partner` (`partner_id`),
  CONSTRAINT `fkALIAS_ctg`  FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  CONSTRAINT `fkALIAS_partner`  FOREIGN KEY (`partner_id`) REFERENCES `partner` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

insert into alias 
values (null, 'English Premier League 2010-2011', 753, 1976);




-- V1.5 23-01-2011
CREATE INDEX `keyEVENT_starts` ON event (starts_at);
CREATE INDEX `keyEVENT_ends` ON event (ends_at);
CREATE INDEX `keyCAL_ctgPath` ON cal (category_ids_path);
CREATE INDEX `keyCAL_deleted` ON cal (deleted_at);
CREATE INDEX `keyCTG_deleted` ON category (deleted_at);
CREATE INDEX `keyLOC_name` ON location (`name`);

-- V1.4 05-01-2011

DELETE FROM category_link_usage WHERE category_link_id in (select id from category_link WHERE type='website'); 
delete from category_link WHERE type='website'; 
-- DO: Run file: insertCategoryURLs.sql



-- DO: RUN file loadLocations.sql
 
DROP TABLE IF EXISTS `address`;
CREATE TABLE `address` (
  `id`                    bigint(20) unsigned NOT NULL auto_increment,
  `country_code`          varchar(50) NOT NULL,
  `state`                 varchar(255) NOT NULL,
  `city`                  varchar(100) NOT NULL,
  `addr`                  varchar(100) NULL,
  `zip`                   varchar(50) NOT NULL,
  `location_id`           bigint(20) unsigned NULL,

  PRIMARY KEY  (`id`),
  KEY `keyADDR_loc` (`location_id`),
  CONSTRAINT `fkADDR_loc`  FOREIGN KEY (`location_id`) REFERENCES `location` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `category` ADD `address_id`  bigint(20) unsigned default NULL;
ALTER TABLE `event` ADD `address_id`  bigint(20) unsigned default NULL;

ALTER TABLE `category` ADD CONSTRAINT `fkCTG_Addr` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);
ALTER TABLE `event` ADD CONSTRAINT `fkEVENT_Addr` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);


update category set name = 'Arsenal de Sarandi' where name = 'Arsenal de Sarand';
update category set name = "FC Utrecht" where name = 'Utrecht';

update event set name = 'Paris Saint-Germain vs. Borussia Dortmund'  where id = 13924;
update event set name = 'Paris Saint-Germain vs. Sevilla'  where id = 13925;
update event set name = 'Levski Sofia vs. LOSC Lille Metropole'  where id = 13842;
update event set name = 'Feyenoord vs. FC Utrecht'  where id = 53442;
 

-- DO: run file: updateEventsAddress.sql




-- V1.3 14-12-2010
ALTER TABLE `cal` ADD `deleted_at`  timestamp NULL default NULL;
ALTER TABLE `category` ADD `deleted_at`  timestamp NULL default NULL;


-- V1.2 04-12-2010
ALTER TABLE `user_search` ADD `from_date`  timestamp NULL default '0000-00-00 00:00:00';
ALTER TABLE `user_search` ADD `to_date`  timestamp NULL default '0000-00-00 00:00:00';



-- V1.1 18-11-2010

ALTER TABLE `cal` ADD `category_ids_path`      varchar(255) default NULL;
ALTER TABLE `category` ADD `category_ids_path`      varchar(255) default NULL;

DROP TABLE IF EXISTS `category_link`;
CREATE TABLE `category_link` (
  `id`                    bigint(20) unsigned NOT NULL auto_increment,
  `category_id`           bigint(20) unsigned NULL,
  `type`                  varchar(100) NOT NULL,
  `txt`                   varchar(255) NOT NULL,
  `url`                   text NOT NULL,
  `target_url`            text NOT NULL,

  PRIMARY KEY  (`id`),
  KEY `keyLINK_category` (`category_id`),
  CONSTRAINT `fkLINK_category`  FOREIGN KEY (`category_id`) REFERENCES `category` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `category_link_usage`;
CREATE TABLE `category_link_usage` (
  `id`                    bigint(20) unsigned NOT NULL auto_increment,
  `category_link_id`      bigint(20) unsigned NULL,
  `user_cal_id`           bigint(20) unsigned NULL,
  `created_at`            timestamp NOT NULL default '0000-00-00 00:00:00',

  PRIMARY KEY  (`id`),
  KEY `keyLINK_USAGE_link` (`category_link_id`),
  CONSTRAINT `fkLINK_USAGE_link`  FOREIGN KEY (`category_link_id`) REFERENCES `category_link` (`id`),
  KEY `keyLINK_USAGE_usercal` (`user_cal_id`),
  CONSTRAINT `fkLINK_USAGE_usercal`  FOREIGN KEY (`user_cal_id`) REFERENCES `user_cal` (`id`)

) ENGINE=InnoDB DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `cal_request`;
CREATE TABLE `cal_request` (
  `id`                bigint(20) unsigned NOT NULL auto_increment,
  `user_cal_id`       bigint(20) unsigned NULL,  
  `cal_id`            bigint(20) unsigned NOT NULL,
  `cal_type`          varchar(255)NULL,
  `created_at`        timestamp NOT NULL default '0000-00-00 00:00:00',

  PRIMARY KEY  (`id`),
  KEY `keyREQ_cal` (`cal_id`),
  CONSTRAINT `fkREQ_cal`    FOREIGN KEY (`cal_id`) REFERENCES `cal` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

