REM mysql -uroot -pyokoono -e "drop database evento"
REM mysql -uroot -pyokoono -e "create database evento"
mysql -uroot -pyokoono --default_character_set utf8 evento < fromProd.sql

pause