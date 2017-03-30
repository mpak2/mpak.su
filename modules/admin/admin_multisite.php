<?

if(($conf['settings']['theme'] == "zhiraf") || array_filter(get($_GET['m']), function($v){ return (strpos($v, "admin") === 0); })){ # Имя файла начинается на admin
	$conf['settings']['theme'] = "zhiraf";
}elseif(!$http_host = mb_strtolower($_SERVER['HTTP_HOST'], 'UTF-8')){ mpre("Ошибка определения хоста");
}elseif(!$http_host = (strpos($http_host, "www.") === 0 ? substr($http_host, 4) : $http_host)){ mpre("Удаление www из начала страницы");
}elseif(!$http_host = idn_to_utf8($http_host)){ mpre("Руссификация хоста");
}elseif(!$http_host = preg_replace("/[.]+$/", "", $http_host)){ mpre("Ошибка выризания точек в конце хоста. По стандартам можно ставить точку в конце адреса и это будет работать");
}else{ global $conf; # Пользовательская страница
	if($themes_index = rb("themes-index", "name", "[$http_host]")){// mpre("Хост найден в списке хостов");
	}else if(get($conf, "settings", "themes_index_ignore") && ($themes_index_ignore = rb("themes-index_ignore", "name", "[{$http_host}]"))){ mpre("Хост в списке игнорированных");
		if(array_key_exists("count", $themes_index_ignore)){
			$themes_index_ignore = fk("themes-index_ignore", array("id"=>$themes_index_ignore['id']), null, array("count"=>$themes_index_ignore['count']+1));
		} exit("Сайт {$http_host} в списке игнорированных");
	}else if(get($conf, 'settings', 'themes_index_ignore_host') && ($_hosts = explode(".", $_SERVER['HTTP_HOST'])) && ($themes_index_ignore_host = rb("themes-index_ignore_host", "name", "[". implode(",", $_hosts). "]"))){
		$themes_index_ignore = fk("themes-index_ignore", $w = ['name'=>$http_host], $w += ['index_ignore_host_id'=>$themes_index_ignore_host['id']], $w);
	}else if((gethostbyname($_SERVER['HTTP_HOST']) == $_SERVER['SERVER_ADDR']) || (get($conf, "settings", 'admin_gethostbyname') == gethostbyname($_SERVER['HTTP_HOST']))){ # Хост настроен на сервер или совпадает с указанным в админке ip
		if(!trim($http_host)){ mpre("Хост пустышка");
			$http_host = $_SERVER['SERVER_ADDR'];
		}else if(!$themes_index = fk("themes-index", $w = array("name"=>$http_host), $w, $w)){ mpre("Ошибка добавления нового хоста");
		}else{ header('HTTP/1.0 403 Forbidden');
			inc("modules/{$arg['modpath']}/{$arg['fn']}_init", array("themes_index"=>$themes_index));
		}
	}else{ $http_host = $_SERVER['SERVER_ADDR']; }


	if($conf['user']['sess']['themes_index'] = $conf['themes']['index'] = $themes_index){

		if(!get($conf, 'user', 'sess', 'id')){// mpre("Сессия не задана, возможно выключена в настройках сайта");
		}elseif(!array_key_exists("themes-index", $_sess = get($conf, 'user', 'sess'))){// mpre("Поле для записи хоста в таблице сессии не задано");
		}elseif($_sess["themes-index"] == $themes_index['id']){// mpre("Информация о хосте уже обновлена");
		}elseif(!$_sess = fk("{$conf['db']['prefix']}sess", ['id'=>$_sess['id']], null, ["themes-index"=>$themes_index['id']])){ mpre("Ошибка обновления хоста в сессиях");
		}else{ /*mpre("Обновление хоста", $themes_index, $_sess);*/ }

		if(get($themes_index, 'index_id') && ($_SERVER['REQUEST_URI'] != "/robots.txt")){
			$conf['user']['sess']['themes_index'] = $conf['themes']['index'] = $themes_index = rb("themes-index", "id", $themes_index['index_id']);
		}
		if(get($_GET, "theme")){ # Шаблон задан в адресной строке
			$conf['settings']['theme'] = $_GET['theme'];
		}elseif(get($conf, 'user', 'theme')){ # Изменяем тему, если для пользователя установлен другой шаблон
			$conf['settings']['theme'] = $conf['user']['theme'];
		}elseif($themes_index['theme']){ # Тема задана в списке сайтов
			$conf['settings']['theme'] = $themes_index['theme'];
		}elseif(get($conf, 'settings', 'themes_index_theme') && ($themes_index_theme = rb("themes-index_theme", "id", $themes_index['index_theme_id']))){
			$conf['settings']['theme'] = $themes_index_theme['path'];
		}
		$conf['settings']['title'] = get($themes_index, 'title');
		$conf['settings']['description'] = get($themes_index, 'description');

		if(array_key_exists("null", $_GET)){ # Ресурсы не трогаем
		}elseif(($get = mpgt($_SERVER['REQUEST_URI'])) && preg_match("#^webdav.(.*)#", $_SERVER['HTTP_HOST']) && ($_SERVER['REQUEST_URI'] = "/webdav". $_SERVER['REQUEST_URI'])){// mpre("Вебдав", $_SERVER['REQUEST_URI']);
			$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>$_SERVER['REQUEST_URI']), 'name'), $_GET);
		}elseif(!$url = first(explode("?", urldecode($_SERVER['REQUEST_URI'])))){ mpre("Ошибка формирования исходного адреса страницы");
		}elseif(!$url = preg_replace("#\/(стр|p)\:[0-9]+#", "", $url)){ mpre("Ошибка избавления от номера страниц в адресе");
		}elseif(strpos($_SERVER['REQUEST_URI'], "//") === 0){
			 if($log = true){ mpre("Перенаправление" , $_SERVER['REQUEST_URI']); }
			header("HTTP/1.0 404 Not Found");
			$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
		}else if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !array_search("Зарегистрированные", $conf['user']['gid'])){
			exit(header('HTTP/1.0 304 Not Modified'));
		}else if(($seo_location = rb("seo-location", "name", "[{$url}]")) && ($seo_location_themes = rb("seo-location_themes", "location_id", "themes_index", $seo_location['id'], $themes_index['id'])) && ($seo_index = rb("seo-index", "id", $seo_location_themes['index_id'])) && ($SEO_INDEX_THEMES = rb("seo-index_themes", "index_id", "themes_index", "id", $seo_index['id'], $themes_index['id']))){
			if(!$seo_location = rb("seo-location", "id", rb($SEO_INDEX_THEMES, "location_id"))){ mpre("Не найдены адреса для переходной ссылки <a href='/seo:admin/r:mp_seo_location?&where[id]={$seo_location['id']}'>{$seo_location['name']}</a>", $seo_location);
			}elseif(!$seo_location_status = rb("seo-location_status", "id", $seo_location['location_status_id'])){ mpre("Не найдена внутренняя страница на переходной ссылке");
			}else{// exit(mpre("301 Перенарпавление", $seo_index));
				header("HTTP/1.1 {$seo_location_status['id']} {$seo_location_status['name']}");
				if(array_search("Администратор", $conf['user']['gid'])){
					mpre("<a href='/seo:admin/r:seo-location_themes?&where[id]={$seo_location_themes['id']}'>Перенаправление</a> с внутреннего на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
				}else{
					header("HTTP/1.1 301 Moved Permanently");
					exit(header("Location: {$seo_index['name']}"));
				}
			}
		}else if(($_SERVER['REQUEST_URI'] == "/") && get($themes_index, 'href')){
			$_REQUEST += $_GET = mpgt($conf['settings']['canonical'] = $themes_index['href']);
		}elseif($_seo_index = rb("seo-index", "id", get($_seo_index_themes = rb("seo-index_themes", "index_id", "themes_index", get($seo_index = rb("seo-index", "name", "[{$url}]"), "id"), $themes_index['id']), 'seo-index'))){
			if($_seo_location = rb("seo-location", "id", $_seo_index_themes['location_id'])){
				$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = $_seo_location + $_seo_index_themes + (array_key_exists('up', $_seo_index_themes) ? array("up"=>get($_seo_index_themes, 'up')) : []), 'name'), $_GET);
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
						mpre("Перенаправление на внешний адрес <a href='{$seo_location['name']}'>{$seo_location['name']}</a>");
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
				$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = $seo_location + $seo_index_themes + (array_key_exists('up', $seo_index_themes) ? array("up"=>get($seo_index_themes, 'up')) : []), 'name'), $_GET);
				if(!$modpath = get($conf, 'modules', first(array_keys($_GET['m'])))){ mpre("Модуль не определен");
				}else{// mpre($modpath);
					$conf['settings']['modpath'] = $modpath['folder'];
					$conf['settings']['modname'] = $modpath['name'];
				} if(!$conf['settings']['fn'] = first(array_values($_GET['m'])) ?: "index"){// mpre("Имя файла не определено");
				}else{ $conf['settings']['filename'] = get($conf, 'settings', "{$modpath['folder']}_{$conf['settings']['fn']}"); }

				$conf['settings']['title'] = htmlspecialchars($seo_index_themes['title']);
				$conf['settings']['description'] = htmlspecialchars($seo_index_themes['description']);
				$conf['settings']['keywords'] = htmlspecialchars($seo_index_themes['keywords']);
			}
		}elseif((strlen($url) > 1) && (substr($url, -1) == "/") && (first(array_keys(get($get, 'm'))) == "webdav")){
			header("HTTP/1.1 301 Moved Permanently");
			if(array_search("Администратор", $conf['user']['gid'])){
				mpre("Адрес заканчивается на правый слеш - перенаправляем без слеша <a href='". substr($url, 0, -1). "'>". substr($url, 0, -1). "</a>");
			}else{ exit(header("Location: ". substr($url, 0, -1))); }
		}elseif(($keys = array_keys($ar = array_keys($_GET['m']))) && !rb($conf['modules'], "modname", "[". $ar[max($keys)]. "]") && !get($conf, 'modules', $ar[max($keys)])){
			mpevent("Страница не найдена 404", $url);
			header("HTTP/1.0 404 Not Found");
			$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
		}elseif(($_SERVER['REQUEST_URI'] == "/") && get($themes_index, 'index_cat_id') && ($themes_index_cat = rb("themes-index_cat", "id", $themes_index['index_cat_id'])) && ($href = get($themes_index_cat, 'href'))){
			if($log){
				pre("Адрес страницы из настроек категории хоста", $themes_index_cat);
			}  $_REQUEST += $_GET = mpgt($conf['settings']['canonical'] = $href, $_GET);
		}else if((($uri = get($canonical = get($conf, 'settings', 'canonical'), 'name') ? $canonical['name'] : $_SERVER['REQUEST_URI'])) && ($get = mpgt($uri))){
				if(!$modpath = get($conf, 'modules', first(array_keys($get['m'])))){ mpre("Модуль не определен");
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
				}
		}else{ /*mpevent("Внутренний адрес", $url);*/ }

		if(get($_GET, "theme")){ # Шаблон задан в адресной строке
			$conf['settings']['theme'] = $_GET['theme'];
		}

		if(get($themes_index, 'index_cat_id')){
			$conf['themes']['index_cat'] = rb("themes-index_cat", "id", $themes_index['index_cat_id']);
		}

	}else{ $conf['settings']['theme'] = "bootstrap3"; }
} $conf['db']['open_basedir'] = first(explode(":", $conf['db']['open_basedir'])). "/themes/{$conf['settings']['theme']}::{$conf['db']['open_basedir']}";
