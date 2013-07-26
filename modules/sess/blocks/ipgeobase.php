<? die;

################################# php код #################################

require_once mpopendir("modules/{$arg['modpath']}/class"). "/geo.php";
$geo = new Geo(array('charset'=>'utf-8'));
$city = "";

if($g = $geo->get_value()){// mpre($conf['user']['sess']);
	if(!$conf['user']['sess']['city_id']){
		$g['region_id'] = mpfdk("{$conf['db']['prefix']}users_region", $w = array("name"=>$reegion = $g['region']), $w);
		$conf['user']['sess']['city_id'] = mpfdk("{$conf['db']['prefix']}users_city", $w = array("name"=>$city = $g['city']), $w += $g, $w);
		$conf['user']['sess']['geo_id'] = mpfdk("{$conf['db']['prefix']}users_geo", $w = array("lat"=>number_format($g['lat'], 1), "lng"=>number_format($g['lng'], 1)), $w);

		mpqw("UPDATE {$conf['db']['prefix']}sess SET city_id=". (int)$conf['user']['sess']['city_id']. ", geo_id=". (int)$conf['user']['sess']['geo_id']. " WHERE id=". (int)$conf['user']['sess']['id']);

		if(!array_key_exists("city_id", $conf['user']['sess'])){ # ДОбавление поля к таблице сессии
			mpqw("ALTER TABLE `{$conf['db']['prefix']}sess` ADD `city_id` int(11) NOT NULL AFTER `uid`");
		} $sess_id = mpfdk("{$conf['db']['prefix']}sess", array("id"=>$conf['user']['sess']['id']), null, array("city_id"=>$conf['user']['sess']['city_id']));
	} if(($conf['user']['uid'] > 0) && array_key_exists("city_id", $conf['user']) && !$conf['user']['city_id']){ # Обновление данных
		$conf['user']['city_id'] = mpfdk("{$conf['db']['prefix']}users_city", $w = array("name"=>$city = $g['city']), $w += $g, $w);
		$user_city = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_city WHERE id=". (int)$conf['user']['city_id']);

		$conf['user']['geo_id'] = mpfdk("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_geo", $w = array("lat"=>(float)number_format($user_city[ $conf['user']['city_id'] ]['lat'], 1), "lng"=>(float)number_format($user_city[ $conf['user']['city_id'] ]['lng'], 1)), $w);

		$uid = mpfdk("{$conf['db']['prefix']}users", array("id"=>$conf['user']['uid']), null, array("city_id"=>$conf['user']['city_id'], "geo_id"=>$conf['user']['geo_id']));

	}
} if(!empty($conf['user']['geo']) && ($geo = explode(",", $conf['user']['geo']))){
	$conf['user']['geo_id'] = mpfdk("{$conf['db']['prefix']}users_geo", $w = array("lat"=>(float)number_format($geo[1], 1), "lng"=>(float)number_format($geo[0], 1)), $w);
	$uid = mpfdk("{$conf['db']['prefix']}users", array("id"=>$conf['user']['uid']), null, array("geo_id"=>$conf['user']['geo_id']));
} if($city){ echo "<b>". $city. "</b> ($reegion)"; }

//mpre($conf['user']['sess']);

################################# верстка #################################?>