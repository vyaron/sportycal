-- ------------------------------------ Start Working --------------------------------------



-- Importing : C:/dev1/sportYcal/Categories/import/Basketball/Europe Championship/Macabi TA.csv
DELETE FROM `event` where cal_id=12;
UPDATE `cal` set updated_at = '2010-08-30 3:20' WHERE id=12;


-- Full line
-- Line: 'Macabi TA, Pantanaikos, 28/08/2010,17:00,Asia/Jerusalem,Tel Aviv'
insert into `event`(`id`,`cal_id`,`name`,`description`, `location`, `tz`, `starts_at`,`ends_at`,`created_at`, `updated_at`) values (23,12,'Macabi TA vs. Pantanaikos',null,'Tel Aviv','Asia/Jerusalem','2010-08-28 17:00','2010-08-28 17:00', '2010-08-30 3:20', '2010-08-30 3:20');

-- No location, should be HOME
-- Line: 'Macabi TA, Olimpiakos, 07/09/2010,23:00,Asia/Jerusalem,'
insert into `event`(`id`,`cal_id`,`name`,`description`, `location`, `tz`, `starts_at`,`ends_at`,`created_at`, `updated_at`) values (24,12,'Macabi TA vs. Olimpiakos',null,'Home','Asia/Jerusalem','2010-09-07 23:00','2010-09-07 23:00', '2010-08-30 3:20', '2010-08-30 3:20');

-- No location, should be AWAY
-- Line: 'Gaza, Macabi TA, 05/09/2010,21:00,Asia/Jerusalem,'
insert into `event`(`id`,`cal_id`,`name`,`description`, `location`, `tz`, `starts_at`,`ends_at`,`created_at`, `updated_at`) values (25,12,'Gaza vs. Macabi TA',null,'Away','Asia/Jerusalem','2010-09-05 21:00','2010-09-05 21:00', '2010-08-30 3:20', '2010-08-30 3:20');

-- Home, No hour
-- Line: 'Macabi TA, Beirut, 02/09/2010,,,'
insert into `event`(`id`,`cal_id`,`name`,`description`, `location`, `tz`, `starts_at`,`ends_at`,`created_at`, `updated_at`) values (26,12,'Macabi TA vs. Beirut',null,'Home','','2010-09-02','2010-09-02', '2010-08-30 3:20', '2010-08-30 3:20');

-- No player2, with location
-- Line: 'Tournamento1,, 02/09/2010,,,Natanya'
insert into `event`(`id`,`cal_id`,`name`,`description`, `location`, `tz`, `starts_at`,`ends_at`,`created_at`, `updated_at`) values (27,12,'Tournamento1',null,'Natanya','','2010-09-02','2010-09-02', '2010-08-30 3:20', '2010-08-30 3:20');

-- No player2, no location
-- Line: 'Tournamento3,, 03/09/2010,,,'
insert into `event`(`id`,`cal_id`,`name`,`description`, `location`, `tz`, `starts_at`,`ends_at`,`created_at`, `updated_at`) values (28,12,'Tournamento3',null,'','','2010-09-03','2010-09-03', '2010-08-30 3:20', '2010-08-30 3:20');

-- error

-- lala,




-- DONE
