mysql -uroot -pyokoono < createTables.sql
mysql -uroot -pyokoono --default_character_set utf8 evento < insertBasics.sql
mysql -uroot -pyokoono --default_character_set utf8 evento < insertCategories.sql
mysql -uroot -pyokoono --default_character_set utf8 evento < insertCategoriesMore.sql
mysql -uroot -pyokoono --default_character_set utf8 evento < insertCalendars.sql
mysql -uroot -pyokoono --default_character_set utf8 evento < insertEvents.sql

pause