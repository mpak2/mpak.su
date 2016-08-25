<?

if(($conf['settings']['theme'] == "zhiraf") || array_filter(get($_GET['m']), function($v){ return (strpos($v, "admin") === 0); })){ # Имя файла начинается на admin
	$conf['settings']['theme'] = "zhiraf";
}else{ global $conf; # Пользовательская страница
	$http_host = first(explode(":", idn_to_utf8(($host = strpos($http_host = mb_strtolower($_SERVER['HTTP_HOST'], 'UTF-8'), "www.")) === 0 ? substr($http_host, 4) : $http_host)));
	if(!($themes_index = rb("{$conf['db']['prefix']}themes_index", "name", "[$http_host]"))){
		if(get($conf, "settings", "themes_index_ignore") && ($themes_index_ignore = rb("themes-index_ignore", "name", "[{$http_host}]"))){
			if(array_key_exists("count", $themes_index_ignore)){
				$themes_index_ignore = fk("{$conf['db']['prefix']}themes_index_ignore", array("id"=>$themes_index_ignore['id']), null, array("count"=>$themes_index_ignore['count']+1));
			} exit("Сайт {$http_host} в списке игнорированных");
		}else if(get($conf, 'settings', 'themes_index_ignore_host') && ($_hosts = explode(".", $_SERVER['HTTP_HOST'])) && ($themes_index_ignore_host = rb("themes-index_ignore_host", "name", "[". implode(",", $_hosts). "]"))){
			$themes_index_ignore = fk("themes-index_ignore", $w = ['name'=>$http_host], $w += ['index_ignore_host_id'=>$themes_index_ignore_host['id']], $w);
		}else if((trim($http_host) && gethostbyname($_SERVER['HTTP_HOST']) == $_SERVER['SERVER_ADDR']) || (get($conf, "settings", 'admin_gethostbyname') == gethostbyname($http_host))){ # Хост настроен на сервер или совпадает с указанным в админке ip
			if($themes_index = fk("{$conf['db']['prefix']}themes_index", $w = array("name"=>$http_host), $w, $w)){
				inc("modules/{$arg['modpath']}/{$arg['fn']}_init", array("themes_index"=>$themes_index)); # Скрипт создания сайта
			}else{ mpre("Ошибка добавления нового хоста"); }
		}else{ $http_host = $_SERVER['SERVER_ADDR']; }
	}
	if($conf['user']['sess']['themes_index'] = $conf['themes']['index'] = $themes_index){
		if(!array_search("Администраторы", $conf['user']['gid'])){
//			inc("modules/{$arg['modpath']}/{$arg['fn']}_init.php", array("themes_index"=>$themes_index)); # Скрипт создания сайта
		}
		
		if(get($themes_index, 'index_id') && ($_SERVER['REQUEST_URI'] != "/robots.txt")){
			$conf['user']['sess']['themes_index'] = $conf['themes']['index'] = $themes_index = rb("{$conf['db']['prefix']}themes_index", "id", $themes_index['index_id']);
		}
		if(get($_GET, "theme")){ # Шаблон задан в адресной строке
			$conf['settings']['theme'] = $_GET['theme'];
		}elseif(get($conf, 'user', 'theme')){ # Изменяем тему, если для пользователя установлен другой шаблон
			$conf['settings']['theme'] = $conf['user']['theme'];
		}elseif($themes_index['theme']){ # Тема задана в списке сайтов
			$conf['settings']['theme'] = $themes_index['theme'];
		}elseif($themes_index_theme = rb("{$conf['db']['prefix']}themes_index_theme", "id", $themes_index['index_theme_id'])){
			$conf['settings']['theme'] = $themes_index_theme['path'];
		} $conf['settings']['title'] = $themes_index['title'];

		if(/*mpre*/($url = preg_replace("#\/(стр|p)\:[0-9]+#", "", first(explode("?", urldecode($_SERVER['REQUEST_URI']))))) && (strlen($url) > 1) && (substr($url, -1) == "/")){
			header("HTTP/1.1 301 Moved Permanently");
			if(array_search("Администратор", $conf['user']['gid'])){
				mpre("Адрес заканчивается на правый слеш - перенаправляем без слеша <a href='". substr($url, 0, -1). "'>". substr($url, 0, -1). "</a>");
			}else{ exit(header("Location: ". substr($url, 0, -1))); }
		} if($log = FALSE){ mpre("Адрес страницы" , $url, $_GET); }

		if(array_key_exists("null", $_GET)){
			# Ресурсы не трогаем
		}else if(strpos($_SERVER['REQUEST_URI'], "//") === 0){
			 if($log = true){ mpre("Перенаправление" , $_SERVER['REQUEST_URI']); }
			header("HTTP/1.0 404 Not Found");
			$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
		}else if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !array_search("Зарегистрированные", $conf['user']['gid'])){
			exit(header('HTTP/1.0 304 Not Modified'));
		}else if(($seo_location = rb("seo-location", "name", "[{$url}]")) && ($seo_location_themes = rb("seo-location_themes", "location_id", "themes_index", $seo_location['id'], $themes_index['id']))){
			if(!$seo_index = rb("{$conf['db']['prefix']}seo_index", "id", $seo_location_themes['index_id'])){ mpre("Ссылка для перехода не найдена", $seo_location_themes);
			}elseif(!$tpl['seo_index_themes'] = rb("seo-index_themes", "index_id", "themes_index", "id", $seo_index['id'], $themes_index['id'])){ mpre("Не найден внутренний адрес");
			}elseif(!$seo_location = rb("seo-location", "id", rb($tpl['seo_index_themes'], "location_id"))){ mpre("Не найдены адреса для переходной ссылки <a href='/seo:admin/r:mp_seo_location?&where[id]={$seo_location['id']}'>{$seo_location['name']}</a>", $seo_location);
			}elseif(!$seo_location_status = rb("{$conf['db']['prefix']}seo_location_status", "id", $seo_location['location_status_id'])){ mpre("Не найдена внутренняя страница на переходной ссылке");
			}else{// exit(mpre("301 Перенарпавление", $seo_index));
				header("HTTP/1.1 {$seo_location_status['id']} {$seo_location_status['name']}");
				if(array_search("Администратор", $conf['user']['gid'])){
					mpre("<a href='/seo:admin/r:seo-location_themes?&where[id]={$seo_location_themes['id']}'>Перенаправление</a> с внутреннего на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
				}else{ exit(header("Location: {$seo_index['name']}")); }
			}
		}else if(($_SERVER['REQUEST_URI'] == "/") && get($themes_index, 'href')){
			$_REQUEST += $_GET = mpgt($conf['settings']['canonical'] = $themes_index['href']);
		}elseif($_seo_index = rb("seo-index", "id", get($_seo_index_themes = rb("seo-index_themes", "index_id", "themes_index", get($seo_index = rb("seo-index", "name", "[{$url}]"), "id"), $themes_index['id']), 'seo-index'))){
			if($_seo_location = rb("seo-location", "id", $_seo_index_themes['location_id'])){
				$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = $_seo_location += (array_key_exists('up', $_seo_index_themes) ? array("up"=>get($_seo_index_themes, 'up')) : []), 'name'), $_GET);
				$conf['settings']['modpath'] = $conf['modules'][ first(array_keys($_GET['m'])) ]['folder'];
				$conf['settings']['fn'] = first(array_values($_GET['m'])) ?: "index";

				$conf['settings']['title'] = htmlspecialchars($_seo_index_themes['title']);
				$conf['settings']['description'] = htmlspecialchars($_seo_index_themes['description']);
				$conf['settings']['keywords'] = htmlspecialchars($_seo_index_themes['keywords']);
			}
			if(array_search("Администратор", $conf['user']['gid'])){
				mpre("<a href='/seo:admin/r:seo-index_themes?&where[id]={$_seo_index_themes['id']}'>Перенаправление</a> на внешний адрес <a href='{$_seo_index['name']}'>{$_seo_index['name']}</a>");
			}else{ exit(header("Location: {$_seo_index['name']}")); }
		}else if(// mpre($_GET) &&
			($seo_location = rb("seo-location", "id", get($seo_index_themes = rb("seo-index_themes", "index_id", "themes_index", get($seo_index = rb("seo-index", "name", "[{$url}]"), "id"), $themes_index['id']), 'location_id'))) ||
			($seo_index_themes = rb("seo-index_themes", "themes_index", "location_id", $themes_index['id'], get($seo_location = rb("seo-location", "name", "[{$url}]"), "id")))
		){
			if(substr($seo_location['name'], 0, 2) == "//"){
				if(!$parse_url = parse_url($seo_location['name'])){
				}elseif(get($conf, 'settings', 'seo_index_exceptions') && ($seo_index_exceptions = rb("seo-index_exceptions", 'index_themes_id', 'uid', $seo_index_themes['id'], $conf['user']['uid']))){
					$conf['settings']['modpath'] = $conf['modules'][ first(array_keys($_GET['m'])) ]['folder'];
					$conf['settings']['fn'] = first(array_values($_GET['m'])) ?: "index";

					$conf['settings']['title'] = htmlspecialchars($seo_index_themes['title']);
					$conf['settings']['description'] = htmlspecialchars($seo_index_themes['description']);
					$conf['settings']['keywords'] = htmlspecialchars($seo_index_themes['keywords']);
				}elseif(!get($parse_url, 'path')){ mpre("Заголовок на сайте не задан");
					if(array_search("Администратор", $conf['user']['gid'])){
						mpre("Перенаправление на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
					}else{ header("HTTP/1.1 302 Moved Permanently"); exit(header("Location: {$seo_location['name']}")); }
				}elseif(!$index = rb("themes-index", "name", "[{$parse_url['host']}]")){ mpre("Сайт перенаправления не найден");
					if(array_search("Администратор", $conf['user']['gid'])){
						mpre("Перенаправление на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
					}else{ header("HTTP/1.1 302 Moved Permanently"); exit(header("Location: {$seo_location['name']}")); }
				}elseif(!$seo_location = rb("seo-location", "name", "[{$parse_url['path']}]")){ mpre("Внутренний адрес сайта перенаправления не определен");
					if(array_search("Администратор", $conf['user']['gid'])){
						mpre("Перенаправление на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
					}else{ header("HTTP/1.1 302 Moved Permanently"); exit(header("Location: {$seo_location['name']}")); }
				}elseif(!$seo_index_themes = rb("seo-index_themes", "themes_index", "location_id", $index['id'], $seo_location['id'])){ mpre("Не найдена запись страницы хоста");
					if(array_search("Администратор", $conf['user']['gid'])){
						mpre("Перенаправление на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
					}else{ header("HTTP/1.1 302 Moved Permanently"); exit(header("Location: {$seo_location['name']}")); }
				}elseif(!$seo_index = rb("seo-index", "id", $seo_index_themes['index_id'])){ mpre("Не найден внешний адрес перенаправляемого хоста");
					if(array_search("Администратор", $conf['user']['gid'])){
						mpre("Перенаправление на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
					}else{ header("HTTP/1.1 302 Moved Permanently"); exit(header("Location: {$seo_location['name']}")); }
				}else{
					header("HTTP/1.1 302 Moved Permanently");
					if(array_search("Администратор", $conf['user']['gid'])){
						mpre("<a href='/seo:admin/r:seo-index_themes?&where[id]={$seo_index_themes['id']}'>Перенаправление</a> на внешний адрес <a href='{$seo_index['name']}'>{$seo_index['name']}</a>");
					}else{ exit(header("Location: //{$index['name']}{$seo_index['name']}")); }
				}
			}elseif(get($seo_index, 'seo-index') && ($_seo_index = rb("seo-index", "id", $seo_index['seo-index']))){
//				mpre($_seo_index);
			}else{
				$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = $seo_location += (array_key_exists('up', $seo_index_themes) ? array("up"=>get($seo_index_themes, 'up')) : []), 'name'), $_GET);
				$conf['settings']['modpath'] = $conf['modules'][ first(array_keys($_GET['m'])) ]['folder'];
				$conf['settings']['fn'] = first(array_values($_GET['m'])) ?: "index";

				$conf['settings']['title'] = htmlspecialchars($seo_index_themes['title']);
				$conf['settings']['description'] = htmlspecialchars($seo_index_themes['description']);
				$conf['settings']['keywords'] = htmlspecialchars($seo_index_themes['keywords']);
			}
		}else if(($keys = array_keys($ar = array_keys($_GET['m']))) && !rb($conf['modules'], "modname", "[". $ar[max($keys)]. "]") && !get($conf, 'modules', $ar[max($keys)])){
			mpevent("Страница не найдена 404", $url);

			if(("/robots.txt" == $url) && ($meta = meta(array("/seo:robots/null", $url)))){
				exit(header("Location: {$meta[1]}"));
			} if(("/sitemap.xml" == $url) && ($meta = meta(array("/seo:sitemap/null", $url)))){
				exit(header("Location: {$meta[1]}"));
			} header("HTTP/1.0 404 Not Found");
			$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
		}elseif(($_SERVER['REQUEST_URI'] == "/") && get($themes_index, 'index_cat_id') && ($themes_index_cat = rb("themes-index_cat", "id", $themes_index['index_cat_id'])) && ($href = get($themes_index_cat, 'href'))){
			if($log){
				pre("Адрес страницы из настроек категории хоста", $themes_index_cat);
			}  $_REQUEST += $_GET = mpgt($conf['settings']['canonical'] = $href, $_GET);
		}else if((($uri = get($canonical = get($conf, 'settings', 'canonical'), 'name') ? $canonical['name'] : $_SERVER['REQUEST_URI'])) && ($get = mpgt($uri))){
				$conf['settings']['modpath'] = $modpath = get($conf, 'modules', first(array_keys($get['m'])), 'folder');
				$conf['settings']['fn'] = $fn = first(array_values($get['m'])) ?: "index";

				if($alias = "{$modpath}:{$fn}". (($keys = array_keys(array_diff_key($get, array_flip(["m", "id"])))) ? "/". implode("/", $keys) : "")){
					if($seo_cat = rb("seo-cat", "alias", "[{$alias}]")){
						if(!get($conf, 'settings', 'seo_cat_themes') || !($SEO_CAT_THEMES = rb("seo-cat_themes", "cat_id", "id", $seo_cat['id'])) || ($seo_cat_themes = rb($SEO_CAT_THEMES, 'themes_index', 'id', get($conf, 'themes', 'index', 'id')))){
//							mpre("Страница доступна для адресации", $seo_cat);
						}else{ mpre("Страница закрыта для адресации <a href='/seo:admin/r:{$conf['db']['prefix']}seo_cat_themes?&where[cat_id]={$seo_cat['id']}&where[themes_index]=". get($conf, 'themes', 'index', 'id'). "'>Добавить</a>");
							header("HTTP/1.0 404 Not Found");
							$_REQUEST += $_GET = mpgt(get($conf['settings']['canonical'] = array("id"=>0, "name"=>"/themes:404"), 'name'), $_GET);
						}
					}
				}
		}else{ mpevent("Внутренний адрес", $url); }

		if(get($_GET, "theme")){ # Шаблон задан в адресной строке
			$conf['settings']['theme'] = $_GET['theme'];
		} if($log){ mpre($_GET); }

		if(get($themes_index, 'index_cat_id')){
			$conf['themes']['index_cat'] = rb("{$conf['db']['prefix']}themes_index_cat", "id", $themes_index['index_cat_id']);
		}
	}else{ $conf['settings']['theme'] = "bootstrap3"; }
} $conf['db']['open_basedir'] = first(explode(":", $conf['db']['open_basedir'])). "/themes/{$conf['settings']['theme']}:{$conf['db']['open_basedir']}";
