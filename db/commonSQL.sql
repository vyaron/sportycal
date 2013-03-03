
-- HOWTO Delete all current toto events
select count(e.id) from event e, cal c where e.cal_id=c.id and partner_id=2100;
delete from event, cal
USING event inner join cal on (event.cal_id = cal.id) 
where partner_id=2100; 



-- HOWTO completely delete a specific category
delete from intel where cal_id in (select id from cal where category_id=2100);
delete from event where cal_id in (select id from cal where category_id=2100);
delete from cal_request where cal_id in (select id from cal where category_id=2100);
delete from user_cal where cal_id in (select id from cal where category_id=2100);
delete from cal where category_id=2100;
-- delete from category where id=2100;