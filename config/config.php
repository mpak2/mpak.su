<?

mb_internal_encoding("UTF-8");
date_default_timezone_set('Europe/Moscow');

$conf['db']['open_basedir'] = ini_get("open_basedir");
$conf['db']['conn'] = null;
$conf['db']['type'] = 'mysql';
$conf['db']['prefix'] = 'mp_';
$conf['db']['host'] = 'localhost';
$conf['db']['login'] = 'mpak.cms';
$conf['db']['name'] = 'mpak.cms';
$conf['db']['pass'] = 'password';
