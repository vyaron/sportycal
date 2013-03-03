delete from `user`; 

-- Master Users
INSERT INTO `user`
(id, email, pass, full_name, birthdate, country_id, state_id, city, address, zip_code, created_at, updated_at, activation_key, activation_date, ref_user_id, balance, type, gender, last_login_date, fb_code) 
VALUES 
(1, 'davido.cohen@gmail.com', 'yoko', 'David Cohen', '', null, null, 'city', 'address', 'zip_code', null, null, 'activation_key', null, null, 0, 'master', 'M', null, '525562356'),
(2, 'vyaron@gmail.com', 'yoko', 'Yaron Biton', '', null, null, 'city', 'address', 'zip_code', null, null, 'activation_key', null, null, 0, 'master', 'M', null, '677870954'),
(3, 'ronenya@gmail.com', 'yoko', 'Ronen', '', null, null, 'city', 'address', 'zip_code', null, null, 'activation_key', null, null, 0, 'master', 'M', null, '789528270');



-- Simple Users
INSERT INTO `user`
(id, email, pass, full_name, birthdate, country_id, state_id, city, address, zip_code, created_at, updated_at, activation_key, activation_date, ref_user_id, balance, type, gender, last_login_date) 
VALUES (101, 'momo@gmail.com', 'momo', 'Momo', '', null, null, 'city', 'address', 'zip_code', null, null, 'activation_key', null, null, 0, 'simple', 'M', null);
