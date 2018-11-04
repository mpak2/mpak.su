<?

function get($ar){
	foreach(array_slice(func_get_args(), 1) as $key){
		if(!empty($ar) && is_array($ar) && (is_string($key) || is_numeric($key)) && array_key_exists($key, $ar)){
			$ar = $ar[ $key ];
		}else{ return null; }
	} return $ar;
} function pre(){
	global $conf;
	if(!$debug_backtrace = debug_backtrace()){ mpre("Ошибка получения списка функций");
	}elseif(!$list[] = get($debug_backtrace, 0)){ print_r("Ошибка получения фукнции инициатора pre[{$num}]");
	}else{// echo "<pre><b>"; print_r($func); echo "</b></pre><pre>"; /*print_r($pre);*/ print_r($debug_backtrace); echo "</pre>";
		foreach($list as $pre){
			echo "<fieldset class='pre' style=\"z-index:". ($conf['settings']['themes-z-index'] = ($z_index = get($conf, "settings", 'themes-z-index')) ? --$z_index : 999999). "\"><legend> {$pre['file']}:{$pre['line']} <b>{$pre['function']}</b> ()</legend>";
			foreach(get($pre, 'args') as $n=>$z){
				echo "<pre>\t\n\t"; print_r($z); echo "\n</pre>";
			} if(true) echo "</fieldset>\n";
		}
	} return get(func_get_args(), 0);
} function mpre(){
	global $conf;
	if(!$debug_backtrace = debug_backtrace()){ mpre("Ошибка получения списка функций");
	}elseif(!$list[] = get($debug_backtrace, 0)){ print_r("Ошибка получения фукнции инициатора pre[{$num}]");
	}else{// echo "<pre><b>"; print_r($func); echo "</b></pre><pre>"; /*print_r($pre);*/ print_r($debug_backtrace); echo "</pre>";
		foreach($list as $pre){
			echo "<fieldset class='pre' style=\"z-index:". ($conf['settings']['themes-z-index'] = ($z_index = get($conf, "settings", 'themes-z-index')) ? --$z_index : 999999). "\"><legend> {$pre['file']}:{$pre['line']} <b>{$pre['function']}</b> ()</legend>";
			foreach(get($pre, 'args') as $n=>$z){
				echo "<pre>\t\n\t"; print_r($z); echo "\n</pre>";
			} if(true) echo "</fieldset>\n";
		}
	} return get(func_get_args(), 0);
}

if(!(strpos($doc = get($_GET, 'path'), 'http') === 0) && !($doc = $_SERVER['argv']['1'])){ pre("Не задан адрес");
}elseif(!$bn = call_user_func(function($doc){ # Основная директория
		if(empty($_SERVER['argv'][1])){ return ($_SERVER['argv'][1] ? './' : '/tmp/'). basename($doc);
		}elseif(!$basename = basename($doc)){ mpre("ОШИБКА получения директории из имени файла `{$doc}`");
		}elseif(!file_exists($basename)){ return $basename;
		}elseif(!$file_name = "/tmp/{$basename}_". date('Y.m.d H:i:s')){ mpre("ОШИБКА получения имени файла для переименования");
		}elseif(!rename($basename, $file_name)){ die(mpre("ОШИБКА переименования файла"));
		}else{ mpre("Старая директория переименована в `{$file_name}`");
			return $basename;
		}
	}, $doc)){ mpre("Ошибка установки основной директории");
//}elseif(true){ mpre("bn: ", $bn);
}elseif(!file_exists($bn) && !mkdir($bn)){ mpre("Ошибка создания директории `{$bn}`");
}elseif(!$parse_url = parse_url($doc)){ mpre("Ошибка парсинга адреса страницы");
}elseif(!$realurl = function($doc, $img) use($parse_url){
		/*if($parse_url = parse_url($img)){ return $img;
		}else*/if(!$parse = parse_url($doc)){ mpre("Ошибка парсинга адреса `{$img}`");
		}elseif(get($parse, 'host') && ($parse['host'] != $parse_url['host'])){ return null; mpre("Ссылка на другой хост");
		}elseif(preg_match("#^https?:#", $img, $match)){ $fn = $img;
		}elseif(substr($img, 0, 2) == '//'){ $fn = 'http:'. $img;
		}elseif(substr($img, 0, 1) == '/'){
			preg_match("/^(https?:\/\/)?([^\/]+)/i", $doc, $matches);
			$fn = $matches[0]. $img;
		}else{// mpre($doc, $parse, dirname($parse['path']));
				$fn = $parse_url['scheme']. "://". $parse_url['host']. (get($parse, 'path') ? dirname($parse['path']). "/" : '/'). $img;
		} return $fn;
	}){ mpre("Ошибка установки функции расчета адресов");
}elseif(!$zam = function($name, $from, $to){
		if(!$to){ return  ['name'=>$name, 'from'=>$from, 'to'=>$to, 'ret'=>$name];
		}elseif(false === strpos($name, $from)){ mpre("В исходном тексте строки замены не найдено");
		}elseif(!$ret = str_replace($from, $to, $name)){ mpre("ОШИБКА замены строки");
		}else{// die(!mpre($name, $from, $to, $ret));
			return ['name'=>$name, 'from'=>$from, 'to'=>$to, 'ret'=>$ret];
		}
	}){ mpre("ОШИБКА создания функции составления массива замены");
}elseif(!$html = file_get_contents($doc)){ mpre("Ошибка получения содержимого страницы `{$doc}`");
}elseif(call_user_func(function($name) use($realurl, $zam, $doc, $bn, $argv, &$html){ //preg_match_all("!@import\"?'?([^ \"'>]+)\"?'?!is",$html,$ok2);
		if(!mpre("Загрузка изображений из основного файла страницы `{$name}`")){ mpre("Уведомление");
		}elseif(!is_array($argv_in = array_slice($argv, 2))){ mpre("ОШИБКА выборки из списка входящих параметров управляющих за пропусти");
		}elseif(!empty($argv_in) && (false === array_search($name, $argv_in))){ mpre("Пропускаем загрузку Отсутствует параметр стилей `{$name}`");
		}elseif(!preg_match_all("!<img[^>]+src=\"?'?([^\"\']+)\"?'?[^>]+>!is", $html, $match)){ mpre("Изображений в исходном тексте страницы не найдено");
//		}elseif(true){ return mpre("Список изображений документа", $match);
		}elseif(!file_exists($d = "{$bn}/img") && !mkdir($d)){ mpre("Ошибка создания директории стилей `{$d}`");
		}elseif(!$IMG = array_map(function($img) use($realurl, $bn, $doc){
				if(!$link = $realurl($doc, $img)){// mpre("Ошибка расчета внешней ссылки");
				}elseif(!$base = basename(get(explode("?", $img), 0))){ mpre("Ошибка расчета имени файла изображения `{$img}`");
				}elseif(!$loc = "img/{$base}"){ mpre("Ошибка установки локального файла");
				}elseif(!$local = "{$bn}/{$loc}"){ mpre("ОШИБКА получения полного локального пути");
				}elseif(!$microtime = microtime(true)){ mpre("Засекаем время начала скачивания файла");
				}elseif(!$data = file_get_contents($link)){ mpre("ОШИБКА скачивания файла {$link}");
				}elseif(!$microtime = number_format(microtime(true)-$microtime, 2)){ mpre("Засекаем время начала скачивания файла");
				}elseif(!file_put_contents($local, $data)){ mpre("ОШИБКА сохранения файла {$local}");
				}elseif(!$size = filesize($local)){ mpre("ОШИБКА получения размера файла");
				}elseif(!print_r(__LINE__. " {$microtime}c. {$link} >> {$local} {$size}Б\n")){ mpre("Останов");
				}else{ return $loc; }
			}, $match[1])){ mpre("Ошибка загрузки изображений документа");
		}elseif(!$ZAM = array_filter(array_map(function($txt, $from, $to) use($zam){ return $zam($txt, $from, $to); }, $match[0], $match[1], $IMG))){ mpre("ОШИБКА составления массива замены");
		}elseif(!$_ZAM = array_filter(array_column($ZAM, 'ret', 'name'))){ mpre("ОШИБКА форматирования массива замены");
		}elseif(!$html = strtr($html, $_ZAM)){ mpre("ОШИБКА замены обновленных адресов изображений в документе");
		}else{// mpre("Замена `{$name}`", $ZAM);
		}
	}, $n = "img")){ mpre("Ошибка загрузки массива изображений документа `{$n}`");
}elseif(call_user_func(function($name) use($realurl, $zam, $doc, $bn, $argv, &$html){ //preg_match_all("!@import\"?'?([^ \"'>]+)\"?'?!is",$html,$ok2);
		if(!mpre("Загрузка ресурсов файлов стилей `{$name}`")){ mpre("Уведомление");
		}elseif(!is_array($argv_in = array_slice($argv, 2))){ mpre("ОШИБКА выборки из списка входящих параметров управляющих за пропусти");
		}elseif(!empty($argv_in) && (false === array_search($name, $argv_in))){ mpre("Пропускаем загрузку Отсутствует параметр стилей `{$name}`");
		}elseif(!preg_match_all("!<link[^>]+href=\"?'?([^ \"'>]+)\"?'?[^>]*>?!is", $html, $match)){ mpre("ОШИБКА выборки списка файлов стилей `{$name}`");
		}elseif(!file_exists($d = "{$bn}/img") && !mkdir($d)){ mpre("Ошибка создания директории стилей `{$d}`");
		}elseif(!file_exists($d = "{$bn}/css") && !mkdir($d)){ mpre("Ошибка создания директории стилей `{$d}`");
		}elseif(!$CSS = array_map(function($url) use($realurl, $zam, $bn, $doc, $name){
				if((!$link = $realurl($doc, $url))){// mpre("Ошибка расчета внешней ссылки");
				}elseif(!$base = basename(get(explode("?", $url), 0))){ mpre("Ошибка получения основного имени файла");
				}elseif(!$microtime = microtime(true)){ mpre("Засекаем время начала загрузки файла");
				}elseif(!$data = file_get_contents($link)){ mpre("ОШИБКА получения содержимого файла `{$link}`");
				}elseif(!$time = number_format(microtime(true)-$microtime, 2)){ mpre("Фиксируем время обработки файла");
				}elseif(!$microtime2 = microtime(true)){ mpre("Засекаем время начала загрузки файла");
				}elseif(!call_user_func(function($link) use($realurl, $zam, $bn, $doc, &$data, $url){// mpre($link);
						if(!preg_match_all("!url\(\"?'?([^ \"'\)]+)\"?'?\)!is", $data, $URL)){ return $link; mpre("ОШИБКА выборки фоновых изображений регулярным выражением");
						}elseif(!$LINK = array_map(function($url) use($realurl, $bn, $doc, $link){// mpre($url);
								if(!$css = $realurl($doc, $link)){ mpre("Ошибка расчета адреса стилей `{$doc}` файла стилей `{$link}`");
								}elseif(!$lnk = $realurl($css, $url)){ mpre("Ошибка расчета адреса изображения `{$css}` файла стилей `{$url}`");
//								}elseif(!mpre("Ресурс", $doc, $url, $lnk)){ mpre("Останов");
								}elseif(!$base = trim(basename(get(explode("?", $url), 0)))){ mpre("Ошибка расчета имени файла изображения `{$url}`");
//								}elseif(true){ die(!mpre('Остановка скрипта', 'doc: '. $doc, "url: ". $url, "link: ". $lnk, 'base: '. $base));
								}elseif(!$loc = "../img/{$base}"){ mpre("Ошибка установки локального файла");
								}elseif(!$local = "{$bn}/css/{$loc}"){ mpre("ОШИБКА составления полного локального пути");
								}elseif(!$local = strtr($local, ['/css/../'=>'/'])){ mpre("Замена вышестоящих переходов в пути до локального файла");
//								}elseif(!$lnk = strtr($lnk, ['/css/../'=>'/'])){ mpre("Замена вышестоящих переходов в адресе ссылки");
								}elseif(!$microtime = microtime(true)){ mpre("Засекаем время начала скачивания файла");
								}elseif(!$data = file_get_contents($lnk)){ mpre("ОШИБКА скачивания файла {$lnk}");
								}elseif(!$microtime = number_format(microtime(true)-$microtime, 2)){ mpre("Засекаем время начала скачивания файла");
								}elseif(!file_put_contents($local, $data)){ mpre("ОШИБКА сохранения файла {$local}");
								}elseif(!$size = filesize($local)){ mpre("ОШИБКА получения размера файла");
								}elseif(!print_r(__LINE__. " {$microtime}c. {$lnk} >> {$local} {$size}Б\n")){ mpre("Останов");
								}elseif(!copy($lnk, $local)){ mpre("Ошибка копирования файлов {$lnk} => {$local}");
								}else{ return $loc; }
							}, $URL[1])){ mpre("ОШИБКА получения списка фоновых изображений в файле `{$link}`");
//						}elseif(!mpre("Ресурсы", $link, $URL, $LINK)){ mpre("Останов");
						}elseif(!$ZAM = array_filter(array_map(function($txt, $from, $to) use($zam){ return $zam($txt, $from, $to); }, $URL[0], $URL[1], $LINK))){ mpre("ОШИБКА составления массива замены");
//						}elseif(!mpre("Список замены css", $ZAM)){ mpre("Останов");
						}elseif(!$_ZAM = array_column($ZAM, 'ret', 'name')){ mpre("ОШИБКА пересборки массива замены");
						}elseif(!$data = strtr($data, $_ZAM)){ mpre("ОШИБКА замены ресурсов в файле стилей");
						}else{ return $link; }
					}, $link)){ mpre("ОШИБКА парсинга фоновых изображений в файле стилей `{$link}`");
				}elseif(!$time2 = number_format(microtime(true)-$microtime2, 2)){ mpre("Фиксируем время обработки файла");
				}elseif(!$size = strlen($data)){ mpre("Расчет размера файла стилей");
//				}elseif(!mpre("Список ресурсов файла стилей {$url}", $URL)){ mpre("Останов");
				}elseif(!$link = "css/{$base}"){ mpre("Ошибка установки короткого имени до файла");
				}elseif(!file_put_contents("{$bn}/{$link}", $data)){ die(!mpre("Ошибка загрузки файла стиля `{$f}`"));
				}else{ print_r(__LINE__. " {$time} {$link} >> {$bn}/{$link} {$size}Б\n");
					return $link;
				}
			}, $match[1])){ mpre("ОШИБКА загрузки изображений из списка файлов css");
//		}elseif(!mpre("Список css файлов", $match)){ mpre("Останов");
		}elseif(!$ZAM = array_filter(array_map(function($txt, $from, $to) use($zam){ return $zam($txt, $from, $to); }, $match[0], $match[1], $CSS))){ mpre("ОШИБКА составления массива замены");
//		}elseif(true){ die(!mpre($ZAM));
//		}elseif(true){ die(!mpre($ZAM));
		}elseif(!$_ZAM = array_column($ZAM, 'ret', 'name')){ mpre("ОШИБКА пересборки массива замены");
		}elseif(!$html = strtr($html, $_ZAM)){ mpre("ОШИБКА замены адресов файлов стилей в коде страницы", $_ZAM);
		}else{// die(!mpre($_ZAM));
		}
	}, $n = "css")){ mpre("Ошибка загрузки массива стилей `{$n}`");
}elseif(call_user_func(function($name) use($realurl, $doc, $bn, $argv, &$html){ //preg_match_all("!@import\"?'?([^ \"'>]+)\"?'?!is",$html,$ok2);
		if(!mpre("Загрузка изображений из основного файла страницы `{$name}`")){ mpre("Уведомление");
		}elseif(!is_array($argv_in = array_slice($argv, 2))){ mpre("ОШИБКА выборки из списка входящих параметров управляющих за пропусти");
		}elseif(!empty($argv_in) && (false === array_search($name, $argv_in))){ mpre("Пропускаем загрузку Отсутствует параметр стилей `{$name}`");
		}elseif(!preg_match_all("!url\(\"?'?([^ \"'\)]+)\"?'?\)!is", $html, $img)){ mpre("В документе ссылок на фоновые изображения не найдены");
		}elseif(!is_array($argv_in = array_slice($argv, 2))){ mpre("ОШИБКА выборки из списка входящих параметров управляющих за пропусти");
		}elseif(!empty($argv_in) && is_null(array_search($n = "url"))){ mpre("Пропускаем загрузку Отсутствует параметр стилей `{$n}`"); return [];
		}elseif(!file_exists($d = "{$bn}/img") && !mkdir($d)){ mpre("Ошибка создания директории стилей `{$d}`");
		}elseif(!$IMG = call_user_func(function($img, $IMG = []) use($realurl, $bn, $doc){ # Загрузка элементов списка
				foreach($img as $im){
					if(!$link = $realurl($doc, $im)){// mpre("Ошибка расчета внешней ссылки");
					}elseif(!$base = basename(get(explode("?", $im), 0))){ mpre("Ошибка расчета имени файла изображения `{$im}`");
					}elseif(!$loc = "img/{$base}"){ mpre("Ошибка установки локального файла");
					}elseif(!copy($link, $f = "{$bn}/{$loc}")){ mpre("Ошибка копирования файлов `{$link}`, `{$f}`");
					}else{ $IMG[$im] = $loc; }
				} return $IMG;
			}, $img)){ mpre("Ошибка загрузки изображений документа");
//		}elseif(!$ZAM = array_map(function($txt, $from, $to) use($zam){ return $zam($txt, $from, $to); }, $match[0], $match[1], $IMG)){ mpre("ОШИБКА составления массива замены");
		}elseif(!$ZAM = array_filter(array_map(function($txt, $from, $to) use($zam){ return $zam($txt, $from, $to); }, $img[0], $img[1], $IMG))){ mpre("ОШИБКА составления массива замены");
		}elseif(true){ mpre($ZAM);
		}elseif(!$_ZAM = array_column($ZAM, 'ret', 'name')){ mpre("ОШИБКА пересборки массива замены");
		}elseif(!$html = strtr($html, array_filter($_ZAM))){ mpre("ОШИБКА замены фоновых изображений в тексте документа");
		}else{
		}
	}, $n = "url")){ mpre("Ошибка загрузки массива изображений стилей `{$n}`");
}elseif(array_filter(call_user_func(function($name) use($realurl, $doc, $bn, $argv, &$html){ //preg_match_all("!@import\"?'?([^ \"'>]+)\"?'?!is",$html,$ok2);
		if(!mpre("Загрузка скриптов в теле страницы `{$name}`")){ mpre("Уведомление");
		}elseif(!is_array($argv_in = array_slice($argv, 2))){ mpre("ОШИБКА выборки из списка входящих параметров управляющих за пропусти");
		}elseif(!empty($argv_in) && (false === array_search($name, $argv_in))){ mpre("Пропускаем загрузку Отсутствует параметр стилей `{$name}`"); return [];
		}elseif(preg_match_all("!<script[^>]+src=\"?'?([^ \"'>]+)\"?'?[^>]*></script>!is", $html, $script)){ mpre("Скриптов в теле документа не найдено");
		}elseif(!file_exists($d = "{$bn}/js") && !mkdir($d)){ mpre("Ошибка создания директории скриптов `{$d}`");
		}elseif(!$SRC = call_user_func(function($script, $SRC = []) use($realurl, $bn, $doc){ # Загрузка элементов списка
				foreach($script as $src){
					if((!$link = $realurl($doc, $src))){// mpre("Ошибка расчета внешней ссылки");
					}elseif(!$base = basename(get(explode("?", $src), 0))){ mpre("Ошибка расчета имени файла изображения `{$im}`");
					}elseif(!$loc = "js/{$base}"){ mpre("Ошибка установки локального файла");
					}elseif(!copy($link, $f = "{$bn}/{$loc}")){ mpre("Ошибка копирования файлов `{$link}`, `{$f}`");
					}else{ $SRC[$src] = $loc; }
				} return $SRC;
			}, $script)){ mpre("Ошибка загрузки изображений документа");
		}elseif(!$html = strtr($html, array_filter($SRC))){ mpre("ОШИБКА замены в теле документа ссылки на скрипты");
		}else{ return $SRC; }
	}, "script"))){ mpre("Ошибка загрузки массива скриптов", $s);
}elseif(!file_put_contents("{$bn}/index.html", $html)){ mpre("Ошибка записи данных в index.html");
}elseif(mpre("Тег для установки локальных путей", '<base href="/themes/<!-- [settings:theme] -->/">')){ mpre("Код для установки в шаблон");
}else{

//	file_put_contents("$bn/block.html", "<h3 title='<!-- [block:modpath] -->:<!-- [block:fn] -->:<!-- [block:id] -->'><!-- [block:title] --></h3>\n<div><!-- [block:content] --></div>\n");
//	file_put_contents("$bn/index.html", $html);

	if(empty($_SERVER['argv']['1'])){
		$zip = new ZipArchive();
		if ($zip->open($filename = "$bn.zip", ZIPARCHIVE::CREATE)!==TRUE) {
			exit("Невозможно открыть <$bn.zip>\n");
		}else{
			$tree = function($dir) use(&$tree, &$zip){
				$d = opendir($dir);
				while($file = readdir($d)){
					if($file{0} == ".") continue;
					if(is_dir($dir. "/". $file)){
						$tree($dir. "/". $file);
					}else{
						$zip->addFile($dir. "/". $file, substr($dir. "/". $file, strlen("/tmp/")));
					}
				}
			}; $tree($bn); $zip->close();

			header('Content-type: application/zip');
			header('Content-Disposition: attachment; filename="'. substr($bn, strlen("/tmp/")). '.zip"');
			readfile($filename);
		}
	}
}
