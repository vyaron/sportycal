delete from `category`; 

insert into `category`(`id`,`name`,`image_path`,`by_user_id`,`approved_at`,`parent_id`, `rate`, `cals_count`) values
(1,'American Football','/images/categories/football1.gif',101,'2010-08-25',null, 170, 0),
(2,'Baseball','/images/categories/baseball1.gif',101,'2010-08-25',null,180, 0),
(3,'Football (Soccer)','/images/categories/soccer1.gif',101,'2010-08-25',null,200, 0),
(4,'Basketball','/images/categories/basketball1.gif',101,'2010-08-25',null,190, 0),
(5,'Tennis','/images/categories/tennis1.gif',101,'2010-08-25',null,160, 0),
(6,'Cricket','/images/categories/cricket1.gif',101,'2010-08-25',null,130, 0),
(7,'Badminton','/images/categories/badminton1.gif',101,'2010-08-25',null,10, 0),
(8,'Golf','/images/categories/golf1.gif',101,'2010-08-25',null,120, 0),
(9,'Ice Hockey','/images/categories/hockey1.gif',101,'2010-08-25',null,150, 0),
(10,'Motor Sport','/images/categories/motor_sport1.gif',101,'2010-08-25',null,140, 0),
(11,'Table Tennis','/images/categories/table_tennis1.gif',101,'2010-08-25',null,10, 0),
(12,'Surfing','/images/categories/surfing1.gif',101,'2010-08-25',null,10, 0),
(13,'Swimming','/images/categories/swimming1.gif',101,'2010-08-25',null,10, 0),
(14,'Cycling','/images/categories/cycling1.gif',101,'2010-08-25',null,10, 0),
(15,'Kayaking & Canoeing','/images/categories/kayaking1.gif',101,'2010-08-25',null,10, 0),
(16,'Sailing','/images/categories/sailing1.gif',101,'2010-08-25',null,10, 0),
(17,'Athletics','/images/categories/athletics1.gif',101,'2010-08-25',null,10, 0),
(18,'Olympic Games','/images/categories/olympic_games1.gif',101,'2010-08-25',null,10, 0),
(19,'Volleyball','/images/categories/volleyball1.gif',101,'2010-08-25',null,10, 0),
(20,'Martial Art','/images/categories/martial_art1.gif',101,'2010-08-25',null,10, 0),
(21,'Boxing','/images/categories/boxing1.gif',101,'2010-08-25',null,10, 0);