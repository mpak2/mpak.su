<?

mb_internal_encoding("UTF-8");
date_default_timezone_set('Europe/Moscow');

$conf['db']['conn'] = null;
$conf['db']['type'] = 'mysql';
$conf['db']['prefix'] = 'mp_';
$conf['db']['host'] = 'localhost';
$conf['db']['login'] = 'username';
$conf['db']['name'] = 'basename';
$conf['db']['pass'] = 'password';

//$conf["db"]["open_basedir"] = '.:core:/tmp';