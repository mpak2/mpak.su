<?

mb_internal_encoding("UTF-8");
date_default_timezone_set('Europe/Moscow');

if(!array_key_exists('open_basedir', (array)$conf['db'])){
	$conf['db']['open_basedir'] = ini_get("open_basedir");
//	$conf['db']['open_basedir'] = dirname(dirname(__FILE__)). ":". ini_get('upload_tmp_dir');
}

$conf['db']['conn'] = null;
$conf['db']['type'] = 'mysql';
$conf['db']['prefix'] = 'mp_';
$conf['db']['host'] = 'localhost';
$conf['db']['login'] = 'mpak.cms';
$conf['db']['name'] = 'mpak.cms';
$conf['db']['pass'] = 'password';
