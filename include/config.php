<?

if(true){
//if(array_key_exists("REMOTE_ADDR" ,$_SERVER) &&("10.144.230.40" ==$_SERVER["REMOTE_ADDR"])){
	$conf['db']['conn'] = null;
	$conf['db']['type'] = 'pgsql';# mysql/sqlite
	$conf['db']['prefix'] = 'mp_';
	//$conf['db']['unix_socket'] = "/var/run/mysqld/mysqld.sock";
	//$conf['db']['host'] = 'localhost';
	$conf['db']['login'] = 'postgres';
	$conf['db']['pass'] = 'postgres';
	$conf['db']['name'] = 'perelink';

	$conf['themes_cache'] = 86400; # Время кеширования страниц для гостей
	//$conf["db"]["open_basedir"] = '.::core::/tmp'; # Установка директории с файлами системы
}else{
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
}
