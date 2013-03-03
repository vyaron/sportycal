delete from category where id = 2600;
insert into category
  (id, name, image_path, approved_at, parent_id, partner_id, is_public)
values
  (2600, '888', '/images/partner/888.gif', '2012-04-01', null, 10, false);
