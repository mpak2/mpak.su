<?

if(!function_exists("strpos_array")){
	function strpos_array($haystack, $needles) {
		if ( is_array($needles) ) {
			foreach ($needles as $img=>$str) {
				if ( is_array($str) ) {
					$pos = strpos_array($haystack, $str);
				} else {
					$pos = strpos($haystack, $str);
				}
				if ($pos !== FALSE) {
					return $img;
				}
			}
		} else {
			return strpos($haystack, $needles);
		}
	}
}

if($uid = $_GET['id']){
	if($uid > 0){
		$user = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE id=". (int)$uid), 0);
		if(file_exists($fn = mpopendir("include/{$user['img']}"))){
			# Изображение загруженное пользователем
		}else if(file_exists($fn = mpopendir("modules/{$arg['modpath']}/img/unknown.png"))){
			# Стандартное изображение гостя
		}
	}else{
		$v = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}sess WHERE id=". abs($uid)), 0);
		$logo = array(
			"Mediapartners-Google.png"=>"Mediapartners-Google",
			"statdom.png"=>"statdom",
			"ezooms.png"=>"ezooms",
			"ahrefs.png"=>"ahrefs",
			"yahoo.png"=>"Yahoo",
			"google.png"=>"Googlebot",
			"rambler.png"=>"StackRambler",
			"yandex.png"=>"Yandex",
			"msnbot.png"=>"msnbot",
			"bing.png"=>"bing",
			"cctld.ru.png"=>"cctld.ru/bot",
			"adsbot.png"=>"adsbot",
			"archive.org.png"=>"archive.org_bot",
			"begun.png"=>"Begun",
			"majestic12.png"=>"majestic12",
			"mail.ru.png"=>"Mail.RU",
			"bot.png"=>"bot",
		);// mpre($v); mpre($logo); exit;
		if($img = strpos_array($v['agent'], $logo)){
			if(file_exists($fn = mpopendir("modules/{$arg['modpath']}/img/{$img}"))){
				# Изображение бота
			}
		}else if(file_exists($fn = mpopendir("modules/{$arg['modpath']}/img/unknown.png"))){
			# Незарегистрированный пользователь
		}
	}
	header ("Content-type: image/". array_pop(explode('.', $fn)));
	echo mprs($fn, $_GET['w'], $_GET['h'], $_GET['c']);

}

?>
