<?

$conf['db']['conn'] = null;
$conf['db']['type'] = 'sqlite';# mysql/sqlite
$conf['db']['prefix'] = 'mp_';
//$conf['db']['unix_socket'] = "/var/run/mysqld/mysqld.sock";
//$conf['db']['host'] = 'localhost';
$conf['db']['login'] = '';
$conf['db']['pass'] = '';
$conf['db']['name'] = '.htdb';

$conf['themes_cache'] = 86400; # Время кеширования страниц для гостей
//$conf["db"]["open_basedir"] = '.::core::/tmp'; # Установка директории с файлами системы
