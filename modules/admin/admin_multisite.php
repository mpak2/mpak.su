<?

if(($conf['settings']['theme'] == "zhiraf") || array_filter(get($_GET['m']), function($v){ return (strpos($v, "admin") === 0); })){ # Имя файла начинается на admin
	$conf['settings']['theme'] = "zhiraf";
}else{ global $conf; # Пользовательская страница
	$http_host = idn_to_utf8($host = strpos($_SERVER['HTTP_HOST'], "www.") === 0 ? substr($_SERVER['HTTP_HOST'], 4) : $_SERVER['HTTP_HOST']);
	$http_host = strpos($http_host, ":") ? array_shift(explode(":", $http_host)) : $http_host;
	if(!($themes_index = rb("{$conf['db']['prefix']}themes_index", "name", "[$http_host]"))){
		if(get($conf, "settings", "admin_multisite_ignore") && ($themes_index_ignore = rb("themes-index_ignore", "name", "[{$http_host}]"))){
			if(array_key_exists("count", $themes_index_ignore)){
				$themes_index_ignore = fk("{$conf['db']['prefix']}themes_index_ignore", array("id"=>$themes_index_ignore['id']), null, array("count"=>$themes_index_ignore['count']+1));
			} exit("Сайт {$http_host} в списке игнорированных");
		}else if(gethostbyname($host) == $_SERVER['SERVER_ADDR']){
			$themes_index = fk("{$conf['db']['prefix']}themes_index", $w = array("name"=>$http_host), $w/*, $w*/);
			if(array_key_exists("sort", $themes_index) && !$themes_index['sort']){
				$themes_index = fk("{$conf['db']['prefix']}themes_index", array("id"=>$themes_index['id']), null, array("sort"=>$themes_index['id']));
			}
		}else{ $http_host = $_SERVER['SERVER_ADDR']; }
	} if($themes_index){

		if(get($themes_index, 'index_id') && ($_SERVER['REQUEST_URI'] != "/robots.txt")){
			$themes_index = rb("{$conf['db']['prefix']}themes_index", "id", $themes_index['index_id']);
		}

		if(get($_GET, "theme")){ # Шаблон задан в адресной строке
			$conf['settings']['theme'] = $_GET['theme'];
		}elseif($themes_index['theme']){ # Тема задана в списке сайтов
			$conf['settings']['theme'] = $themes_index['theme'];
		}elseif($themes_index_theme = rb("{$conf['db']['prefix']}themes_index_theme", "id", $themes_index['index_theme_id'])){
			$conf['settings']['theme'] = $themes_index_theme['path'];
		} $conf['settings']['title'] = $themes_index['title'];

		if(/*mpre*/($url = preg_replace("#\/(стр|p)\:[0-9]+#", "", first(explode("?", urldecode($_SERVER['REQUEST_URI']))))) && (strlen($url) > 1) && (substr($url, -1) == "/")){
			header("HTTP/1.1 301 Moved Permanently");
			exit(header("Location: ". substr($url, 0, -1)));
		} if($log = FALSE){ mpre("Адрес страницы" , $url, $_GET); }

		if(array_key_exists("null", $_GET)){
			# Ресурсы не трогаем
		}else if(strpos($_SERVER['REQUEST_URI'], "//") === 0){
			 if($log = true){ mpre("Перенаправление" , $_SERVER['REQUEST_URI']); }
			header("HTTP/1.0 404 Not Found");
			$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
//			exit(header("Refresh:0; url=/themes:404{$_SERVER['REQUEST_URI']}", true, 404));
		}else if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !array_search("Зарегистрированные", $conf['user']['gid'])){
			exit(header('HTTP/1.0 304 Not Modified'));
		}else if(($seo_location = rb("{$conf['db']['prefix']}seo_location", "name", "[{$url}]")) && ($seo_location_themes = rb("{$conf['db']['prefix']}seo_location_themes", "location_id", "themes_index", $seo_location['id'], $themes_index['id']))){// exit(mpre($url, $seo_location, $seo_location_themes));
			if($log){
				mpre("Перенаправление на внешнюю страницу");
			} if($seo_index = rb("{$conf['db']['prefix']}seo_index", "id", $seo_location_themes['index_id'])){// exit(mpre($seo_location_themes, $seo_index));
				if($tpl['seo_index_themes'] = rb("seo-index_themes", "index_id", "themes_index", "id", $seo_index['id'], $themes_index['id'])){// mpre($seo_index_themes);
					if($seo_location = rb("seo-location", "id", rb($tpl['seo_index_themes'], "location_id"))){
						if($seo_location_status = rb("{$conf['db']['prefix']}seo_location_status", "id", $seo_location['location_status_id'])){
							header("HTTP/1.1 {$seo_location_status['id']} {$seo_location_status['name']}");
						} exit(header("Location: {$seo_index['name']}"));
					}else{ mpre("Не найдена внутренняя страница на переходной ссылке"); }
				}else{ mpre("Не найдены адреса для переходной ссылки <a href='/seo:admin/r:mp_seo_location?&where[id]={$seo_location['id']}'>{$seo_location['name']}</a>", $seo_location); }
			}else{ mpre("Ссылка для перехода не найдена", $seo_location_themes); }
		}else if(($_SERVER['REQUEST_URI'] == "/") && get($themes_index, 'href')){
			if($log){
				mpre("Адрес страницы из настроек хоста", $themes_index);
			} $_REQUEST += $_GET = mpgt($conf['settings']['canonical'] = $themes_index['href'], $_GET);
		}else if(// mpre($_GET) &&
			($seo_location = rb("{$conf['db']['prefix']}seo_location", "id", get($seo_index_themes = rb("{$conf['db']['prefix']}seo_index_themes", "index_id", "themes_index", get($seo_index = rb("{$conf['db']['prefix']}seo_index", "name", "[{$url}]"), "id"), $themes_index['id']), 'location_id'))) ||
			($seo_index_themes = rb("{$conf['db']['prefix']}seo_index_themes", "themes_index", "location_id", $themes_index['id'], get($seo_location = rb("{$conf['db']['prefix']}seo_location", "name", "[{$url}]"), "id")))
		){ if($log){
				pre("Адресация", $seo_index, $seo_index_themes, $seo_location);
			} if(substr($seo_location['name'], 0, 2) == "//"){# pre("Пересылка на другой сайт", $seo_location);
				if($location_status = rb("location_status", "id", get($seo_location, "location_status_id"))){
					header("HTTP/1.1 301 Moved Permanently");
				} exit(header("Location: {$seo_location['name']}"));
			}else{
				$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = $seo_location, 'name'), $_GET);
				$conf['settings']['modpath'] = $conf['modules'][ first(array_keys($_GET['m'])) ]['folder'];
				$conf['settings']['fn'] = first(array_values($_GET['m'])) ?: "index";

				$conf['settings']['title'] = htmlspecialchars($seo_index_themes['title']);
				$conf['settings']['description'] = htmlspecialchars($seo_index_themes['description']);
				$conf['settings']['keywords'] = htmlspecialchars($seo_index_themes['keywords']);
			}
		}else if(($keys = array_keys($ar = array_keys($_GET['m']))) && !rb($conf['modules'], "modname", "[". $ar[max($keys)]. "]") && !get($conf, 'modules', $ar[max($keys)])){
			if($log){
				pre("Ошибочная страница", "/themes:404");
			} header("HTTP/1.0 404 Not Found");
			$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
//			exit(header("Refresh:0; url=/themes:404{$_SERVER['REQUEST_URI']}", true, 404));
		}elseif(($_SERVER['REQUEST_URI'] == "/") && get($themes_index, 'index_cat_id') && ($themes_index_cat = rb("themes-index_cat", "id", $themes_index['index_cat_id'])) && ($href = get($themes_index_cat, 'href'))){
			if($log){
				pre("Адрес страницы из настроек категории хоста", $themes_index_cat);
			}  $_REQUEST += $_GET = mpgt($conf['settings']['canonical'] = $href, $_GET);
		}else{ /*mpre($themes_index);*/ }

		if(get($_GET, "theme")){ # Шаблон задан в адресной строке
			$conf['settings']['theme'] = $_GET['theme'];
		} if($log){
			mpre($_GET);
		}

		if(get($themes_index, 'index_cat_id')){
			$conf['themes']['index_cat'] = rb("{$conf['db']['prefix']}themes_index_cat", "id", $themes_index['index_cat_id']);
		} $conf['user']['sess']['themes_index'] = $conf['themes']['index'] = $themes_index;
	}else{ $conf['settings']['theme'] = "bootstrap3"; }
} $conf['db']['open_basedir'] = first(explode(":", $conf['db']['open_basedir'])). "/themes/{$conf['settings']['theme']}:{$conf['db']['open_basedir']}";
