<?

if(array_key_exists("id", $_GET) && ($tn = $_GET['tn']) && ($img = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}"/*. mpquot($tn)*/. " WHERE img<>'' AND id=".(int)$_GET['id']), 0))){
	$file_name = mpopendir("include/". ($fn = $img['img']));
	if(!ob_get_length()){
		header ("Content-type: image/". last(explode('.', $file_name)));
	} echo mprs($file_name, (array_key_exists("w", $_GET) ? $_GET['w'] : 0), (array_key_exists("h", $_GET) ? $_GET['h'] : 0), (array_key_exists("c", $_GET) ? $_GET['c'] : 0));
}elseif(file_exists($fn = mpopendir("modules/{$arg['modpath']}/img/". $fn = basename($_GET[''])))){
	if(!ob_get_length()){
		header ("Content-type: image/". last(explode('.', $fn)));
	} echo mprs($fn, (array_key_exists("w", $_GET) ? $_GET['w'] : 0), (array_key_exists("h", $_GET) ? $_GET['h'] : 0), (array_key_exists("c", $_GET) ? $_GET['c'] : 0));
}elseif(($guest = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE name=\"". mpquot($conf['settings']['default_usr']). "\""), 0)) && $guest['img']){
	$file_name = mpopendir("include/". $guest['img']);
	if(!ob_get_length()){
		header ("Content-type: image/". last(explode('.', $file_name)));
	} echo mprs($file_name, (array_key_exists("w", $_GET) ? $_GET['w'] : 0), (array_key_exists("h", $_GET) ? $_GET['h'] : 0), (array_key_exists("c", $_GET) ? $_GET['c'] : 0));
}elseif($fn = mpopendir("modules/{$arg['modpath']}/img/unknown.png")){
	if(!ob_get_length()){
		header ("Content-type: image/". last(explode('.', $fn)));
	} echo mprs($fn, (array_key_exists("w", $_GET) ? $_GET['w'] : 0), (array_key_exists("h", $_GET) ? $_GET['h'] : 0), (array_key_exists("c", $_GET) ? $_GET['c'] : 0));
}
