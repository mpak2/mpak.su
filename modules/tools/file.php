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
		}else{ return basename($doc); }
	}, $doc)){ mpre("Ошибка установки основной директории");
}elseif(!file_exists($bn) && !mkdir($bn)){ mpre("Ошибка создания директории `{$bn}`");
}elseif(!$parse_url = parse_url($doc)){ mpre("Ошибка парсинга адреса страницы");
}elseif(!$realurl = function($url, $link) use($parse_url){
		if(!$parse = parse_url($url)){ mpre("Ошибка парсинга адреса `{$link}`");
		}elseif(get($parse, 'host') && ($parse['host'] != $parse_url['host'])){ return null; mpre("Ссылка на другой хост");
		}elseif(preg_match("#^https?:#", $link, $match)){ $fn = $link;
		}elseif(substr($link, 0, 2) == '//'){ $fn = 'http:'. $link;
		}elseif(substr($link, 0, 1) == '/'){
			preg_match("/^(https?:\/\/)?([^\/]+)/i", $url, $matches);
			$fn = $matches[0]. $link;
		}else{// mpre($url, $parse, dirname($parse['path']));
				$fn = $parse_url['scheme']. "://". $parse_url['host']. (get($parse, 'path') ? dirname($parse['path']). "/" : '/'). $link;
		} return $fn;
	}){ mpre("Ошибка установки функции расчета адресов");
}elseif(!$html = file_get_contents($doc)){ mpre("Ошибка получения содержимого страницы `{$doc}`");
}elseif(!is_array($CSS = (preg_match_all("!<link[^>]+href=\"?'?([^ \"'>]+)\"?'?[^>]*>?!is", $html, $css) ? array_filter(call_user_func(function($css) use($realurl, $doc, $bn){ //preg_match_all("!@import\"?'?([^ \"'>]+)\"?'?!is",$html,$ok2);
		if(!file_exists($d = "{$bn}/css") && !mkdir($d)){ mpre("Ошибка создания директории стилей `{$d}`");
		}elseif(!file_exists($d = "{$bn}/img") && !mkdir($d)){ mpre("Ошибка создания директории стилей `{$d}`");
		}elseif(!$CSS = call_user_func(function($css, $CSS = []) use($realurl, $bn, $doc){ # Загрузка элементов списка
				foreach($css as $url){
					if((!$link = $realurl($doc, $url))){// mpre("Ошибка расчета внешней ссылки");
					}elseif(!$base = basename(get(explode("?", $url), 0))){ mpre("Ошибка получения основного имени файла");
					}elseif(!$data = file_get_contents($link)){ mpre("Ошибка получения содержимого файла `{$link}`");
					}elseif((!$IMG = (preg_match_all("!url\(\"?'?([^ \"'\)]+)\"?'?\)!is", $data, $img) ? array_filter(call_user_func(function($img, $url, $IMG = []) use($realurl, $bn, $doc, $data){
							foreach($img as $im){
								if(!$link = $realurl($url, $im)){// mpre("Ошибка расчета адреса изображения `{$im}` файла стилей `{$url}`");
								}elseif(!$base = basename(get(explode("?", $im), 0))){ mpre("Ошибка расчета имени файла изображения `{$im}`");
								}elseif(!$loc = "img/{$base}"){ mpre("Ошибка установки локального файла");
								}elseif(!copy($link, $f = "{$bn}/{$loc}")){ mpre("Ошибка копирования файлов `{$link}`, `{$f}`");
								}else{// die(!mpre($im, $base));
									$IMG[$im] = $loc;
								}
							} return $IMG;
						}, get($img, 1), $link)) : []))){ mpre("Изображений в файле стилей не найдено `{$link}`");
					}elseif(mpre("Дизайн", $IMG) && (!$data = strtr($data, $IMG))){ mpre("Ошибка замены изображений стилей реальными путями", $IMG);
					}elseif(!file_put_contents($f = "{$bn}/css/{$base}", $data)){ mpre("Ошибка загрузки данных в файл `{$f}`");
					}else{ $CSS[$url] = $link; }
				} return $CSS;
			}, $css)){ mpre("Ошибка загрузки файлов стилей");
		}else{ return $CSS; }
	}, $s = get($css, 1))) : []))){ mpre("Ошибка загрузки массива стилей", $s);
}elseif(mpre("Стили", $CSS) && (!$html = strtr($html, $CSS))){ mpre("Ошибка замены стилей в документе");

}elseif(!is_array($IMG = (preg_match_all("!<img[^>]+src=\"?'?([^ \"'>]+)\"?'?[^>]+>!is", $html, $img) ? array_filter(call_user_func(function($img) use($realurl, $doc, $bn){ //preg_match_all("!@import\"?'?([^ \"'>]+)\"?'?!is",$html,$ok2);
		if(!file_exists($d = "{$bn}/img") && !mkdir($d)){ mpre("Ошибка создания директории стилей `{$d}`");
		}elseif(!$IMG = call_user_func(function($img, $IMG = []) use($realurl, $bn, $doc){ # Загрузка элементов списка
				foreach($img as $im){
					if((!$link = $realurl($doc, $im))){// mpre("Ошибка расчета внешней ссылки");
					}elseif(!$base = basename(get(explode("?", $im), 0))){ mpre("Ошибка расчета имени файла изображения `{$im}`");
					}elseif(!$loc = "img/{$base}"){ mpre("Ошибка установки локального файла");
					}elseif(!copy($link, $f = "{$bn}/{$loc}")){ mpre("Ошибка копирования файлов `{$link}`, `{$f}`");
					}else{ $IMG[$im] = $loc; }
				} return $IMG;
			}, $img)){ mpre("Ошибка загрузки изображений документа");
		}else{ return $IMG; }
	}, $s = get($img, 1))) : []))){ mpre("Ошибка загрузки массива изображений документа", $s);
}elseif(mpre("Изображения", $IMG) && (!$html = strtr($html, $IMG))){ mpre("Ошибка замены стилей в документе");

}elseif(!is_array($IMG = (preg_match_all("!url\(\"?'?([^ \"'\)]+)\"?'?\)!is", $html, $img) ? array_filter(call_user_func(function($img) use($realurl, $doc, $bn){ //preg_match_all("!@import\"?'?([^ \"'>]+)\"?'?!is",$html,$ok2);
		if(!file_exists($d = "{$bn}/img") && !mkdir($d)){ mpre("Ошибка создания директории стилей `{$d}`");
		}elseif(!$IMG = call_user_func(function($img, $IMG = []) use($realurl, $bn, $doc){ # Загрузка элементов списка
				foreach($img as $im){
					if((!$link = $realurl($doc, $im))){// mpre("Ошибка расчета внешней ссылки");
					}elseif(!$base = basename(get(explode("?", $im), 0))){ mpre("Ошибка расчета имени файла изображения `{$im}`");
					}elseif(!$loc = "img/{$base}"){ mpre("Ошибка установки локального файла");
					}elseif(!copy($link, $f = "{$bn}/{$loc}")){ mpre("Ошибка копирования файлов `{$link}`, `{$f}`");
					}else{ $IMG[$im] = $loc; }
				} return $IMG;
			}, $img)){ mpre("Ошибка загрузки изображений документа");
		}else{ return $IMG; }
	}, $s = get($img, 1))) : []))){ mpre("Ошибка загрузки массива изображений стилей", $s);
}elseif(mpre("Графика", $IMG) && (!$html = strtr($html, $IMG))){ mpre("Ошибка замены стилей в документе");

}elseif(!is_array($SRC = (preg_match_all("!<script[^>]+src=\"?'?([^ \"'>]+)\"?'?[^>]*></script>!is", $html, $script) ? array_filter(call_user_func(function($script) use($realurl, $doc, $bn){ //preg_match_all("!@import\"?'?([^ \"'>]+)\"?'?!is",$html,$ok2);
		if(!file_exists($d = "{$bn}/js") && !mkdir($d)){ mpre("Ошибка создания директории скриптов `{$d}`");
		}elseif(!$SRC = call_user_func(function($script, $SRC = []) use($realurl, $bn, $doc){ # Загрузка элементов списка
				foreach($script as $src){
					if((!$link = $realurl($doc, $src))){// mpre("Ошибка расчета внешней ссылки");
					}elseif(!$base = basename(get(explode("?", $src), 0))){ mpre("Ошибка расчета имени файла изображения `{$im}`");
					}elseif(!$loc = "js/{$base}"){ mpre("Ошибка установки локального файла");
					}elseif(!copy($link, $f = "{$bn}/{$loc}")){ mpre("Ошибка копирования файлов `{$link}`, `{$f}`");
					}else{ $SRC[$src] = $loc; }
				} return $SRC;
			}, $script)){ mpre("Ошибка загрузки изображений документа");
		}else{ return $SRC; }
	}, $s = get($script, 1))) : []))){ mpre("Ошибка загрузки массива скриптов", $s);
}elseif(mpre("Скрипты", $SRC) && (!$html = strtr($html, $SRC))){ mpre("Ошибка замены стилей в документе");

}elseif(!file_put_contents("{$bn}/index.html", $html)){ mpre("Ошибка записи данных в index.html");
}elseif(!file_put_contents("$bn/block.html", "<h3 title='<!-- [block:modpath] -->:<!-- [block:fn] -->:<!-- [block:id] -->'><!-- [block:title] --></h3>\n<div><!-- [block:content] --></div>\n")){ mpre("Ошибка загрузки блока");
}else{ # Упаковка шаблона в архив
	if(empty($_SERVER['argv']['1'])){
		$zip = new ZipArchive();
		if ($zip->open($filename = "$bn.zip", ZIPARCHIVE::CREATE)!==TRUE) {
			exit("Невозможно открыть `$bn.zip`\n");
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