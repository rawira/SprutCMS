1. Импортировать структуру БД:
/_read_me/sql/install.sql

2.Настраиваем сайт:

- Файл настроек и доступа к БД:
config.php

- Файлы, которые необходимо изменить под свой сайт:
config.php
/templates/*.php
/templates/assets/img/logo.png (там же лежит PSD файл, который можно переделать)
/templates/assets/img/favicon.png

При необходимости поправить стили:
/templates/assets/css

4. Настраиваем ЧПУ через mod_rewrite.
Примеры,
 - nginx:
 /_read_me/nginx

 - Apache/httpd2:
 /_read_me/httpd

3. Загружаем товар на сайт по API.
Примеры тут:
/_read_me/api