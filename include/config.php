<?

mb_internal_encoding("UTF-8");
date_default_timezone_set('Europe/Moscow');

$conf['db']['conn'] = null;
$conf['db']['type'] = 'sqlite';
$conf['db']['prefix'] = 'mp_';
$conf['db']['host'] = 'localhost';
$conf['db']['login'] = '';
$conf['db']['pass'] = '';
$conf['db']['name'] = '.htdb';

//$conf["db"]["open_basedir"] = '.:core:/tmp';
