<?

$conf['db']['conn'] = null;
$conf['db']['type'] = 'sqlite';//mysql:sqlite
$conf['db']['prefix'] = 'mp_';
//$conf['db']['unix_socket'] = "/var/run/mysqld/mysqld.sock";
//$conf['db']['host'] = 'localhost';
$conf['db']['login'] = '';
$conf['db']['pass'] = '';
$conf['db']['name'] = '.htdb';

if(is_dir('./core')){
	$conf["db"]["open_basedir"] = '.::core::/tmp';
}
