<?

if(($conf['settings']['theme'] != "zhiraf")){
	global $conf;
	$http_host = idn_to_utf8($host = strpos($_SERVER['HTTP_HOST'], "www.") === 0 ? substr($_SERVER['HTTP_HOST'], 4) : $_SERVER['HTTP_HOST']);
	$http_host = strpos($http_host, ":") ? array_shift(explode(":", $http_host)) : $http_host;

	if(/*empty($_GET['theme']) &&*/ !($themes_index = rb("{$conf['db']['prefix']}themes_index", "name", "[$http_host]"))){
		if(gethostbyname($host) == $_SERVER['SERVER_ADDR']){
			$themes_index = fk("{$conf['db']['prefix']}themes_index", $w = array("name"=>$http_host), $w/*, $w*/);
		}else{ $http_host = $_SERVER['SERVER_ADDR']; }
	} if($themes_index){
		if($themes_index['theme']){
			$conf['settings']['theme'] = $themes_index['theme'];
		}elseif($themes_index_theme = rb("{$conf['db']['prefix']}themes_index_theme", "id", $themes_index['index_theme_id'])){
			$conf['settings']['theme'] = $themes_index_theme['path'];
		} $conf['settings']['title'] = $themes_index['title'];
		$keys = array_keys($ar = explode(":", $conf['db']['open_basedir']));
		$conf['db']['open_basedir'] = $ar[min($keys)]. "/themes/{$conf['settings']['theme']}:{$conf['db']['open_basedir']}";

		if(($url = preg_replace("#\/(стр|p)\:[0-9]+#", "", first(explode("?", urldecode($_SERVER['REQUEST_URI']))))) && (strlen($url) > 1) && (substr($url, -1) == "/")){
			exit(header("Location: ". substr($url, 0, -1)));
		} if(array_key_exists("null", $_GET)){
			# Ресурсы не трогаем
		}else if(strpos($_SERVER['REQUEST_URI'], "//") === 0){
			header("HTTP/1.0 404 Not Found");
			exit(header("Location: /themes:404{$_SERVER['REQUEST_URI']}"));
		}else if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !array_search("Зарегистрированные", $conf['user']['gid'])){
			exit(header('HTTP/1.0 304 Not Modified'));
		}else if(($seo_location = rb("{$conf['db']['prefix']}seo_location", "name", "[{$url}]")) && ($seo_location_themes = rb("{$conf['db']['prefix']}seo_location_themes", "location_id", "themes_index", $seo_location['id'], $themes_index['id']))){
			if($seo_index = rb("{$conf['db']['prefix']}seo_index", "id", $seo_location_themes['index_id'])){
				if($seo_location_status = rb("{$conf['db']['prefix']}seo_location_status", "id", $seo_location['location_status_id'])){
					header("HTTP/1.1 {$seo_location_status['id']} {$seo_location_status['name']}");
				} exit(header("Location: {$seo_index['name']}"));
			}
		}else if(
			($seo_location = rb("{$conf['db']['prefix']}seo_location", "id", get($seo_index_themes = rb("{$conf['db']['prefix']}seo_index_themes", "index_id", "themes_index", get($seo_index = rb("{$conf['db']['prefix']}seo_index", "name", "[{$url}]"), "id"), $themes_index['id']), 'location_id'))) ||
			($seo_index_themes = rb("{$conf['db']['prefix']}seo_index_themes", "themes_index", "location_id", $themes_index['id'], get($seo_location = rb("{$conf['db']['prefix']}seo_location", "name", "[{$url}]"), "id")))
		){// pre($seo_index, $seo_index_themes, $seo_location);
				$_REQUEST += $_GET = mpgt($conf['settings']['canonical'] = $seo_location['name'], $_GET);
				$conf['settings']['modpath'] = $conf['modules'][ first(array_keys($_GET['m'])) ]['folder'];
				$conf['settings']['fn'] = first(array_values($_GET['m'])) ?: "index";

				$conf['settings']['title'] = htmlspecialchars($seo_index_themes['title']);
				$conf['settings']['description'] = htmlspecialchars($seo_index_themes['description']);
				$conf['settings']['keywords'] = htmlspecialchars($seo_index_themes['keywords']);
		}else if(($_SERVER['REQUEST_URI'] == "/") && $themes_index['href']){
			$_REQUEST += $_GET = mpgt($conf['settings']['canonical'] = $themes_index['href'], $_GET);
		}else if(($keys = array_keys($ar = array_keys($_GET['m']))) && !rb($conf['modules'], "modname", "[". $ar[max($keys)]. "]") && !$conf['modules'][ $ar[max($keys)] ]){
			exit(header("Location: /themes:404{$_SERVER['REQUEST_URI']}"));
		}

		$conf['user']['sess']['themes_index'] = $conf['themes']['index'] = $themes_index;
		$conf['themes']['index_cat'] = rb("{$conf['db']['prefix']}themes_index_cat", "id", $themes_index['index_cat_id']);
	}else if($conf['settings']['theme'] != "zhiraf"){
		$conf['settings']['theme'] = "bootstrap3";
	}
}
