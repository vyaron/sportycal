delete from category where id = 2100;
insert into category
  (id, name, image_path, approved_at, parent_id, partner_id, is_public)
values
  (2100, 'טוטו - Winner', '/images/partner/1979_ctg.png', '2011-07-14', null, 1979, true);
