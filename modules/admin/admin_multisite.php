<?

if(($conf['settings']['theme'] == "zhiraf") || array_filter(get($_GET['m']), function($v){ return (strpos($v, "admin") === 0); })){ $conf['settings']['theme'] = "zhiraf"; # Админстраница
}elseif(!get($conf, "settings", "themes_index")){// mpre("Резим мультисайта выключен");
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
}elseif(!$conf['settings']['theme'] = (get($themes_index, 'theme') ?: $conf['settings']['theme'])){ mpre("ОШИБКА установки темы из свойств хоста");
}elseif(!$conf['settings']['theme'] = (get($conf, 'user', 'theme') ? $conf['user']['theme'] : $conf['settings']['theme'])){ mpre("ОШИБКА установки темы из свойств пользователя");
}elseif(!$conf['settings']['theme'] = (get($_GET, "theme") ?: $conf['settings']['theme'])){ mpre("ОШИБКА установки темы из адресной строки");
}elseif(array_key_exists("null", $_GET)){ # Ресурсы не трогаем
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
}elseif(call_user_func(function() use($conf, $seo_index, $seo_location_themes, $seo_location){ # Отображаем администратору или переходим по перенаправлению
		if($seo_index){// mpre("Для перенаправления не найден внешний адрес");
		}elseif(empty($seo_location_themes)){// mpre("Для перенправления не установлена адресация");
		}elseif(empty($seo_location)){// mpre("Для перенправления не утсановлен внутренний адрес");
		}elseif(!$_seo_index = rb("seo-index", "id", $seo_location_themes["index_id"])){ mpre("ОШИБКА выборки страницы перенаправления");
		}elseif(array_search("Администратор", $conf['user']['gid'])){ mpre("<a href='/seo:admin/r:seo-location_themes?&where[id]={$seo_location_themes['id']}'>Перенаправление</a> с внутреннего на внешний адрес <a href='{$_seo_index['name']}'>{$_seo_index['name']}</a>");
		}else{ header("HTTP/1.1 301 Moved Permanently");
			exit(header("Location: {$_seo_index['name']}"));
		}
	})){ mpre("ОШИБКА расчета перенаправления");
}elseif(!$start_mod = get($themes_index, 'href') ?: $conf["settings"]["start_mod"]){// mpre("Каноникл у хоста не указан");
}elseif(call_user_func(function() use($themes_index, $start_mod, $conf){ # По свойствам выставляем настройку
		if($_SERVER['REQUEST_URI'] != "/"){// mpre("Только для главной страницы");
		}elseif(!$conf['settings']['canonical'] = $start_mod){ mpre("ОШИБКА установки каноникала в свойство");
		}else{ $_REQUEST += $_GET = mpgt($start_mod); }
	})){ mpre("ОШИБКА установки адреса главной страницы");
}elseif(call_user_func(function() use($conf, $seo_index, $seo_index_themes){ # Установка мета информации сайта
		if(empty($seo_index_themes)){// mpre("Адресация не задана");
		}elseif(!$conf['settings']['title'] = (get($seo_index_themes, 'title') ? htmlspecialchars($seo_index_themes['title']) : $conf['settings']['title'])){ mpre("ОШИБКА установки мета заголовка");
		}elseif(!$conf['settings']['description'] = (get($seo_index_themes, 'description') ? htmlspecialchars($seo_index_themes['description']) : $conf['settings']['description'])){ mpre("ОШИБКА установки мета описания");
		}elseif(!$conf['settings']['keywords'] = (get($seo_index_themes, 'keywords') ? htmlspecialchars($seo_index_themes['keywords']) : $conf['settings']['keywords'])){ mpre("ОШИБКА установки <a href='/settings:admin/r:settings-?edit&where[name]=keywords'>мета ключевиков</a>");
		}else{// mpre("Адресация страницы", $seo_index_themes);
		}
	})){ mpre("ОШИБКА перенаправления сайта");
}elseif(call_user_func(function() use($url){
		if(strlen($url) <= 1){// mpre("Похоже на главную страницу");
		}elseif(substr($url, -1) != "/"){// mpre("Страница оканчивается не на слеш");
		}elseif(first(array_keys(get($get, 'm'))) == "webdav"){// mpre("Раздел исключений адресации со слешами");
		}elseif(array_search("Администратор", $conf['user']['gid'])){ mpre("Адрес заканчивается на правый слеш - перенаправляем без слеша <a href='". substr($url, 0, -1). "'>". substr($url, 0, -1). "</a>");
		}else{ exit(header("Location: ". substr($url, 0, -1)));
		}
	})){ mpre("ОШИБКА обработки адресов заканчивающихся на правый слеш");
}elseif(!$conf["settings"]["canonical"] = call_user_func(function($canonical = null) use($conf, $seo_location, $seo_index_themes){ # Расчет и установка каноникала
		if(!is_array($canonical = ($seo_location + $seo_index_themes))){ mpre("ОШИБКА формирования массива с каноникалам");
		}elseif(!is_array($canonical += ($seo_index_themes ? ["index_themes_id"=>$seo_index_themes['id']] : []))){ mpre("ОШИБКА добавления в мета информацию ссылки на заголовки");
		}elseif(!$uri = ("/" == $_SERVER["REQUEST_URI"] ? $conf["settings"]["start_mod"] : $_SERVER["REQUEST_URI"])){ mpre("ОШИБКА нахождения адреса текущей страницы");
		}elseif(!$canonical = ($canonical ?: $uri)){ mpre("ОШИБКА формирования каноникала");
		}elseif(!is_string($href = get($seo_location, "name") ?: $canonical)){ mpre("ОШИБКА расчета адреса");
		}elseif(!$get = mpgt($href, $_GET)){ mpre("ОШИБКА получения параметров адресной строки");
		}elseif(!$mod = get($conf, 'modules', first(array_keys($get['m'])))){ mpre("Модуль не определен");
		}elseif(!$_GET = ($get + $_GET)){ mpre("ОШИБКА добавления параметров адресной строки");
		}else{// pre($canonical, $href, $get, $_GET, $mod, $seo_location, $url);
		} return $canonical;
	})){ mpre("ОШИБКА установки канонической мета информации");
}else{// mpre("Каноническая информация", $conf["settings"]["canonical"]);
}
