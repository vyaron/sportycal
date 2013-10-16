mysql -uroot -pyokoono -e "drop database evento"
mysql -uroot -pyokoono -e "create database evento"
mysql -uroot -pyokoono -e "SET FOREIGN_KEY_CHECKS=0"
mysql -uroot -pyokoono --default_character_set utf8 evento < evento.sql
mysql -uroot -pyokoono -e "SET FOREIGN_KEY_CHECKS=1"


pause
