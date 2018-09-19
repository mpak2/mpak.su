<?

//pre($conf);

if(($conf['settings']['theme'] == "zhiraf") || array_filter(get($_GET['m']), function($v){ return (strpos($v, "admin") === 0); })){
	$conf['settings']['theme'] = "zhiraf"; # Имя файла начинается на admin
}elseif(!$http_host = mb_strtolower($_SERVER['HTTP_HOST'], 'UTF-8')){ mpre("Ошибка определения хоста");
}elseif(!$http_host = (strpos($http_host, "www.") === 0 ? substr($http_host, 4) : $http_host)){ mpre("Удаление www из начала страницы");
}elseif(!$http_host = idn_to_utf8($http_host, IDNA_NONTRANSITIONAL_TO_ASCII, INTL_IDNA_VARIANT_UTS46)){ mpre("Руссификация хоста");
}elseif(!$http_host = preg_replace("/[.]+$/", "", $http_host)){ mpre("Ошибка выризания точек в конце хоста. По стандартам можно ставить точку в конце адреса и это будет работать");
}elseif(!is_array($themes_index = call_user_func(function($http_host, $themes_index = []) use($conf){ # Выборка хоста и добавление в случае необходимости
		if($themes_index = rb("themes-index", "name", "[$http_host]")){// mpre("Хост найден в списке хостов");
			if(!$_themes_index = (get($themes_index, 'index_id') ? rb("themes-index", "id", $themes_index['index_id']) : [])){// mpre("Зеркало сайта не найдено");
			}elseif($_SERVER['REQUEST_URI'] == "/robots.txt"){// mpre("Обработка страницы робота");
			}else{ $themes_index = $_themes_index; }
		}elseif((gethostbyname($_SERVER['HTTP_HOST']) == $_SERVER['SERVER_ADDR']) || (get($conf, "settings", 'admin_gethostbyname') == gethostbyname($_SERVER['HTTP_HOST']))){ # Хост настроен на сервер или совпадает с указанным в админке ip
			if(!trim($http_host)){ mpre("Хост пустышка");
			}else if(!$themes_index = fk("themes-index", $w = array("name"=>$http_host), $w, $w)){ mpre("Ошибка добавления нового хоста");
			}else{ header('HTTP/1.0 403 Forbidden');
				inc("modules/{$arg['modpath']}/{$arg['fn']}_init", array("themes_index"=>$themes_index));
			}
		}else{
		} return $themes_index;
	}, $http_host))){ mpre("ОШИБКА выборки хоста сайта");
}elseif(empty($themes_index)){ mpre("ОШИБКА хост сайта не установлен");
}elseif(!$conf['user']['sess']['themes_index'] = $conf['themes']['index'] = (get($conf, 'themes', 'index') ? $conf['themes']['index'] + $themes_index : $themes_index)){ mpre("Таблица хостов не задана");
	$conf['settings']['theme'] = "bootstrap3";
//}elseif(!get($conf, 'user', 'sess', 'id')){// mpre("Сессия не задана, возможно выключена в настройках сайта");
//}elseif(!array_key_exists("themes-index", $_sess = get($conf, 'user', 'sess'))){// mpre("Поле для записи хоста в таблице сессии не задано");
//}elseif($_sess["themes-index"] == $themes_index['id']){// mpre("Информация о хосте уже обновлена");
//}elseif(!$_sess = fk("{$conf['db']['prefix']}sess", ['id'=>$_sess['id']], null, ["themes-index"=>$themes_index['id']])){ mpre("Ошибка обновления хоста в сессиях");
}elseif(!$conf['settings']['theme'] = (get($themes_index, 'theme') ?: $conf['settings']['theme'])){ mpre("ОШИБКА установки темы из свойств хоста");
}elseif(!$conf['settings']['theme'] = (get($conf, 'user', 'theme') ? $conf['user']['theme'] : $conf['settings']['theme'])){ mpre("ОШИБКА установки темы из свойств пользователя");
}elseif(!$conf['settings']['theme'] = (get($_GET, "theme") ?: $conf['settings']['theme'])){ mpre("ОШИБКА установки темы из адресной строки");
//}elseif(true){ pre($conf['settings']);
//}elseif(get($conf, 'settings', 'themes_index_theme') && ($themes_index_theme = rb("themes-index_theme", "id", $themes_index['index_theme_id']))){ $conf['settings']['theme'] = $themes_index_theme['theme'];
}elseif(array_key_exists("null", $_GET)){ # Ресурсы не трогаем
//}elseif(!$conf['settings']['title'] = get($themes_index, 'title') ?: $conf['settings']['title']){ mpre("ОШИБКА установки заголовка страницы из свойств сатйа");
//}elseif(!$conf['settings'][$s = 'description'] = (get($themes_index, $s) ?: get($conf, 'settings', $s))){ mpre("ОШИБКА установки <a href='/settings:admin/r:settings-?edit&where[name]=title'>описания сайта</a>", get($conf, 'settings'));
//}elseif(($get = mpgt($_SERVER['REQUEST_URI'])) && preg_match("#^webdav.(.*)#", $_SERVER['HTTP_HOST']) && ($_SERVER['REQUEST_URI'] = "/webdav". $_SERVER['REQUEST_URI'])){// mpre("Вебдав", $_SERVER['REQUEST_URI']);
//	$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>$_SERVER['REQUEST_URI']), 'name'), $_GET);
}elseif(!$url = first(explode("?", urldecode($_SERVER['REQUEST_URI'])))){ mpre("Ошибка формирования исходного адреса страницы");
}elseif(!$url = preg_replace("#\/(стр|p)\:[0-9]+#", "", $url)){ mpre("Ошибка избавления от номера страниц в адресе");
}elseif(call_user_func(function(){
		if(strpos($_SERVER['REQUEST_URI'], "//") !== 0){// mpre("Не переходим");
		}else{ header("HTTP/1.0 404 Not Found");
			$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
		}
	})){ mpre("ОШИБКА перехода на страницу ошибки");
}elseif(!is_array($seo_index = rb("seo-index", "name", array_flip([$url])))){ mpre("ОШИБКА получения внешнего адреса страницы");
}elseif(!is_array($seo_index_themes = ($seo_index && $themes_index ? rb("seo-index_themes", "themes_index", "index_id", $themes_index['id'], $seo_index["id"]) : []))){ mpre("ОШИБКА выборки адресации");
}elseif(!is_array($seo_location = ($seo_index_themes ? rb("seo-location", "id", $seo_index_themes["location_id"]) : rb("seo-location", "name", "[{$url}]")))){ mpre("ОШИБКА выборки внутреннего адреса страницы");
}elseif(!is_array($seo_location_themes = ($seo_location && $themes_index ? rb("seo-location_themes", "location_id", "themes_index", $seo_location['id'], $themes_index['id']) : []))){ mpre("ОШИБКА получения перенаправления страницы");
//}elseif(!is_array($SEO_INDEX_THEMES = ($seo_index && $themes_index ? rb("seo-index_themes", "index_id", "themes_index", "id", $seo_index['id'], $themes_index['id']) : []))){ mpre("ОШИБКА выборки списк возможных адресаций");
//}elseif(!is_array($seo_location = ($SEO_INDEX_THEMES ? first(rb($SEO_INDEX_THEMES, "location_id")) : $seo_location))){  mpre("Не найдены адреса для переходной ссылки <a href='/seo:admin/r:mp_seo_location?&where[id]=". get($seo_location, 'id'). "'>". get($seo_location, 'name'). "</a>", $seo_location);
//}elseif(true){ mpre($seo_index_themes, $seo_location);
}elseif(call_user_func(function() use($conf, $seo_index, $seo_location_themes, $seo_location){ # Отображаем администратору или переходим по перенаправлению
		if($seo_index){// mpre("Для перенаправления не найден внешний адрес");
		}elseif(empty($seo_location_themes)){// mpre("Для перенправления не установлена адресация");
		}elseif(empty($seo_location)){// mpre("Для перенправления не утсановлен внутренний адрес");
		}elseif(!$_seo_index = rb("seo-index", "id", $seo_location_themes["index_id"])){ mpre("ОШИБКА выборки страницы перенаправления");
//		}elseif(true){ pre($_seo_index);
		}elseif(array_search("Администратор", $conf['user']['gid'])){ mpre("<a href='/seo:admin/r:seo-location_themes?&where[id]={$seo_location_themes['id']}'>Перенаправление</a> с внутреннего на внешний адрес <a href='{$_seo_index['name']}'>{$_seo_index['name']}</a>");
		}else{ header("HTTP/1.1 301 Moved Permanently");
			exit(header("Location: {$_seo_index['name']}"));
		}
	})){ mpre("ОШИБКА расчета перенаправления");
//}else if(($_SERVER['REQUEST_URI'] == "/") && get($themes_index, 'href')){
//	$_REQUEST += $_GET = mpgt($conf['settings']['canonical'] = $themes_index['href']);
}elseif(!$start_mod = get($themes_index, 'href') ?: $conf["settings"]["start_mod"]){// mpre("Каноникл у хоста не указан");
}elseif(call_user_func(function() use($themes_index, $start_mod, $conf){ # По свойствам выставляем настройку
		if($_SERVER['REQUEST_URI'] != "/"){// mpre("Только для главной страницы");
		}elseif(!$conf['settings']['canonical'] = $start_mod){ mpre("ОШИБКА установки каноникала в свойство");
		}else{ $_REQUEST += $_GET = mpgt($start_mod); }
	})){ mpre("ОШИБКА установки адреса главной страницы");
/*}elseif($_seo_index = rb("seo-index", "id", get($_seo_index_themes = rb("seo-index_themes", "index_id", "themes_index", get($seo_index = rb("seo-index", "name", "[{$url}]"), "id"), $themes_index['id']), 'seo-index'))){
	if($_seo_location = rb("seo-location", "id", $_seo_index_themes['location_id'])){
		$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = $_seo_location + $_seo_index_themes + (array_key_exists('up', $_seo_index_themes) ? array("index_themes_id"=>$seo_index_themes['id'], "up"=>get($_seo_index_themes, 'up')) : ["index_themes_id"=>$seo_index_themes['id']]), 'name'), $_GET);
		if(!$modpath = get($conf, 'modules', first(array_keys($_GET['m'])))){ mpre("Модуль не определен");
		}else{
			$conf['settings']['modpath'] = $modpath['folder'];
			$conf['settings']['modname'] = $modpath['name'];
		} if(!$conf['settings']['fn'] = first(array_values($_GET['m'])) ?: "index"){// mpre("Имя файла не определено");
		}else{ $conf['settings']['filename'] = get($conf, 'settings', "{$modpath['folder']}_{$conf['settings']['fn']}"); }


		$conf['settings']['title'] = htmlspecialchars($_seo_index_themes['title']);
		$conf['settings']['description'] = htmlspecialchars($_seo_index_themes['description']);
		$conf['settings']['keywords'] = htmlspecialchars($_seo_index_themes['keywords']);
	}
	if($seo_location_themes = rb("seo-location_themes", "location_id", "themes_index", $_seo_location['id'], $conf['themes']['index']['id'])){
		// mpre("Переадресация", $seo_location_themes);
	} if(array_search("Администратор", $conf['user']['gid'])){
			mpre("<a href='/seo:admin/r:seo-index_themes?&where[id]={$_seo_index_themes['id']}'>Адресация</a> на внешний адрес <a href='{$_seo_index['name']}'>{$_seo_index['name']}</a>", ($seo_location_themes ? "Редактировать <a href='/seo:admin/r:seo-location_themes?&where[id]={$seo_location_themes['id']}'>переадресацию</a>" : "Переадресация не установлена"));
	}elseif(get($seo_location_themes, 'location_status_id') && ($seo_location_status = rb("seo-location_status", "id", $seo_location_themes['location_status_id']))){
		header("HTTP/1.1 {$seo_location_status['id']} {$seo_location_status['name']}");
		exit(header("Location: {$_seo_index['name']}"));
	}else{
		header("HTTP/1.1 301 Moved Permanently");
		exit(header("Location: {$_seo_index['name']}"));
	}
}else if(// mpre($_GET) &&
	($seo_location = rb("seo-location", "id", get($seo_index_themes = rb("seo-index_themes", "index_id", "themes_index", get($seo_index = rb("seo-index", "name", "[{$url}]"), "id"), $themes_index['id']), 'location_id'))) ||
	($seo_index_themes = rb("seo-index_themes", "themes_index", "location_id", $themes_index['id'], get($seo_location = rb("seo-location", "name", "[{$url}]"), "id")))
){
	if(substr($seo_location['name'], 0, 2) == "//"){
		if(!$parse_url = parse_url($seo_location['name'])){
		}elseif(get($conf, 'settings', 'seo_index_exceptions') && ($seo_index_exceptions = rb("seo-index_exceptions", 'index_themes_id', 'uid', $seo_index_themes['id'], $conf['user']['uid']))){
			if(!$modpath = get($conf, 'modules', first(array_keys($_GET['m'])))){ mpre("Модуль не определен");
			}else{// mpre($modpath);
				$conf['settings']['modpath'] = $modpath['folder'];
				$conf['settings']['modname'] = $modpath['name'];
			} if(!$conf['settings']['fn'] = first(array_values($_GET['m'])) ?: "index"){// mpre("Имя файла не определено");
			}else{ $conf['settings']['filename'] = get($conf, 'settings', "{$modpath['folder']}_{$conf['settings']['fn']}"); }

			$conf['settings']['title'] = htmlspecialchars($seo_index_themes['title']);
			$conf['settings']['description'] = htmlspecialchars($seo_index_themes['description']);
			$conf['settings']['keywords'] = htmlspecialchars($seo_index_themes['keywords']);
		}elseif(!get($parse_url, 'path')){// mpre("Заголовок на сайте не задан");
			if(array_search("Администратор", $conf['user']['gid'])){
				mpre("Перенаправление на внешний адрес <a href='{$seo_location['name']}'>{$seo_location['name']}</a> правило <a href='/seo:admin/r:seo-index_themes?&where[id]={$seo_index_themes['id']}'>{$seo_index_themes['id']}</a>");
			}else{ header("HTTP/1.1 302 Moved Permanently"); exit(header("Location: {$seo_location['name']}")); }
		}elseif(!$index = rb("themes-index", "name", "[{$parse_url['host']}]")){ mpre("Сайт перенаправления не найден");
			if(array_search("Администратор", $conf['user']['gid'])){
				mpre("Перенаправление на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
			}else{ header("HTTP/1.1 302 Moved Permanently"); exit(header("Location: {$seo_location['name']}")); }
		}elseif(!$_seo_location = rb("seo-location", "name", "[{$parse_url['path']}]")){ mpre("Внутренний адрес сайта перенаправления не определен", $parse_url);
			if(array_search("Администратор", $conf['user']['gid'])){
				mpre("Перенаправление на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
			}else{ header("HTTP/1.1 302 Moved Permanently"); exit(header("Location: {$seo_location['name']}")); }
		}elseif(!$seo_index_themes = rb("seo-index_themes", "themes_index", "location_id", $index['id'], $_seo_location['id'])){ mpre("Не найдена запись страницы хоста");
			if(array_search("Администратор", $conf['user']['gid'])){
				mpre("Перенаправление на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
			}else{ header("HTTP/1.1 302 Moved Permanently"); exit(header("Location: {$_seo_location['name']}")); }
		}elseif(!$seo_index = rb("seo-index", "id", $seo_index_themes['index_id'])){ mpre("Не найден внешний адрес перенаправляемого хоста");
			if(array_search("Администратор", $conf['user']['gid'])){
				mpre("Перенаправление на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
			}else{ header("HTTP/1.1 302 Moved Permanently"); exit(header("Location: {$_seo_location['name']}")); }
		}else{
			header("HTTP/1.1 302 Moved Permanently");
			if(array_search("Администратор", $conf['user']['gid'])){
				mpre("<a href='/seo:admin/r:seo-index_themes?&where[id]={$seo_index_themes['id']}'>Перенаправление</a> на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
			}else{ exit(header("Location: //{$index['name']}{$seo_index['name']}")); }
		}
	}elseif(get($seo_index, 'seo-index') && ($_seo_index = rb("seo-index", "id", $seo_index['seo-index']))){
//				mpre($_seo_index);
	}else{
		$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = $seo_location + $seo_index_themes + (array_key_exists('up', $seo_index_themes) ? array("index_themes_id"=>$seo_index_themes['id'], "up"=>get($seo_index_themes, 'up')) : ["index_themes_id"=>$seo_index_themes['id']]), 'name'), $_GET);
		if(!$modpath = get($conf, 'modules', first(array_keys($_GET['m'])))){ mpre("Модуль не определен");
		}else{// mpre($modpath);
			$conf['settings']['modpath'] = $modpath['folder'];
			$conf['settings']['modname'] = $modpath['name'];
		} if(!$conf['settings']['fn'] = first(array_values($_GET['m'])) ?: "index"){// mpre("Имя файла не определено");
		}else{ $conf['settings']['filename'] = get($conf, 'settings', "{$modpath['folder']}_{$conf['settings']['fn']}"); }

		$conf['settings']['title'] = htmlspecialchars($seo_index_themes['title']);
		$conf['settings']['description'] = htmlspecialchars($seo_index_themes['description']);
		$conf['settings']['keywords'] = htmlspecialchars($seo_index_themes['keywords']);
	}*/
}elseif(call_user_func(function() use($conf, $seo_index, $seo_index_themes){ # Установка мета информации сайта
		if(empty($seo_index_themes)){// mpre("Адресация не задана");
		}elseif(!$conf['settings']['title'] = (get($seo_index_themes, 'title') ? htmlspecialchars($seo_index_themes['title']) : $conf['settings']['title'])){ mpre("ОШИБКА установки мета заголовка");
		}elseif(!$conf['settings']['description'] = (get($seo_index_themes, 'description') ? htmlspecialchars($seo_index_themes['description']) : $conf['settings']['description'])){ mpre("ОШИБКА установки мета описания");
		}elseif(!$conf['settings']['keywords'] = (get($seo_index_themes, 'keywords') ? htmlspecialchars($seo_index_themes['keywords']) : $conf['settings']['keywords'])){ mpre("ОШИБКА установки <a href='/settings:admin/r:settings-?edit&where[name]=keywords'>мета ключевиков</a>");
		}else{// mpre("Адресация страницы", $seo_index_themes);
		}
	})){ mpre("ОШИБКА перенаправления сайта");
//}elseif(true){ mpre($seo_index);
}elseif(call_user_func(function() use($url){
		if(strlen($url) <= 1){// mpre("Похоже на главную страницу");
		}elseif(substr($url, -1) != "/"){// mpre("Страница оканчивается не на слеш");
		}elseif(first(array_keys(get($get, 'm'))) == "webdav"){// mpre("Раздел исключений адресации со слешами");
		}elseif(array_search("Администратор", $conf['user']['gid'])){ mpre("Адрес заканчивается на правый слеш - перенаправляем без слеша <a href='". substr($url, 0, -1). "'>". substr($url, 0, -1). "</a>");
		}else{ exit(header("Location: ". substr($url, 0, -1)));
		}
	})){ mpre("ОШИБКА обработки адресов заканчивающихся на правый слеш");
/*}elseif(($keys = array_keys($ar = array_keys($_GET['m']))) && !rb($conf['modules'], "modname", "[". $ar[max($keys)]. "]") && !get($conf, 'modules', $ar[max($keys)])){
	header("HTTP/1.0 404 Not Found");
	$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
}elseif(call_user_func(function(){
		if(!$modpath = $conf['settings']['modpath']){ mpre("Модуль не определен");
		}elseif(get($conf, 'modules', $modpath)){ mpre("Модуль обнаружен")
		}else{
		}
	})){ mpre("ОШИБКА установки 404 страницы");
}elseif(($_SERVER['REQUEST_URI'] == "/") && get($themes_index, 'index_cat_id') && ($themes_index_cat = rb("themes-index_cat", "id", $themes_index['index_cat_id'])) && ($href = get($themes_index_cat, 'href'))){
	if($log){
		pre("Адрес страницы из настроек категории хоста", $themes_index_cat);
	}  $_REQUEST += $_GET = mpgt($conf['settings']['canonical'] = $href, $_GET);*/
//}else if((($uri = get($canonical = get($conf, 'settings', 'canonical'), 'name') ? $canonical['name'] : $_SERVER['REQUEST_URI'])) && ($get = mpgt($uri))){
}elseif(!$canonical = (($seo_location + $seo_index_themes + ["index_themes_id"=>get($seo_index_themes, 'id')]) ?: $_SERVER["REQUEST_URI"])){// mpre("ОШИБКА формирования каноникала");
}elseif(!is_string($href = get($seo_location, "name") ?: $canonical)){// mpre("ОШИБКА расчета адреса");
}elseif(!$get = mpgt($href, $_GET)){ mpre("ОШИБКА получения параметров адресной строки");
}elseif(!$mod = get($conf, 'modules', first(array_keys($get['m'])))){ mpre("Модуль не определен");
}elseif(!$_GET = ($get + $_GET)){ mpre("ОШИБКА добавления параметров адресной строки");
}else{// pre($canonical, $href, $get, $_GET, $mod, $seo_location, $url);
	$conf['settings']['modpath'] = $mod['folder'];
	$conf['settings']['modname'] = $mod['name'];
/*		if(!$modpath = get($conf, 'modules', first(array_keys($get['m'])))){ mpre("Модуль не определен");
		}else{// mpre($modpath);
			$conf['settings']['modpath'] = $modpath['folder'];
			$conf['settings']['modname'] = $modpath['name'];
		} if(!$conf['settings']['fn'] = first(array_values($get['m'])) ?: "index"){// mpre("Имя файла не определено");
		}else{ $conf['settings']['filename'] = get($conf, 'settings', "{$modpath['folder']}_{$conf['settings']['fn']}"); }

		if($alias = "{$modpath['folder']}:{$conf['settings']['fn']}". (($keys = array_keys(array_diff_key($get, array_flip(["m", "id"])))) ? "/". implode("/", $keys) : "")){
			if($seo_cat = rb("seo-cat", "alias", "[{$alias}]")){
				if(!get($conf, 'settings', 'seo_cat_themes') || !($SEO_CAT_THEMES = rb("seo-cat_themes", "cat_id", "id", $seo_cat['id'])) || ($seo_cat_themes = rb($SEO_CAT_THEMES, 'themes_index', 'id', get($conf, 'themes', 'index', 'id')))){
//							mpre("Страница доступна для адресации", $seo_cat);
				}else{ mpre("Страница закрыта для адресации <a href='/seo:admin/r:seo-cat_themes?&where[cat_id]={$seo_cat['id']}&where[themes_index]=". get($conf, 'themes', 'index', 'id'). "'>Добавить</a>");
					header("HTTP/1.0 404 Not Found");
					$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
				}
			}
		}*/
	/*mpevent("Внутренний адрес", $url);*/
}

/*if(get($_GET, "theme")){ # Шаблон задан в адресной строке
	$conf['settings']['theme'] = $_GET['theme'];
}

if(get($themes_index, 'index_cat_id')){
	$conf['themes']['index_cat'] = rb("themes-index_cat", "id", $themes_index['index_cat_id']);
}*/
