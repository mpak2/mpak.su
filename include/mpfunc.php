<?
//функция скачки файла (чтение файла идет по 5метров)
function file_download ($file,$filename,$mimetype='application/octet-stream') {
   if(!$filename) $filename = preg_replace("#.*\/([^\/]+)$#iu",'$1',$file);
   if (file_exists ($file)) {
     header ($_SERVER["SERVER_PROTOCOL"] . ' 200 OK');
     header ('Content-Type: ' . $mimetype);
     header ('Last-Modified: ' . gmdate ('r', filemtime ($file)));
     header ('ETag: ' . sprintf ('%x-%x-%x', fileinode ($file), filesize ($file), filemtime ($file)));
     header ('Content-Length: ' . (filesize ($file)));
     header ('Connection: close');
     header ('Content-Disposition: attachment; filename="'.$filename.'";');
 // Открываем искомый файл
     $f=fopen ($file, 'r');
     while (!feof ($f)) {
 // Читаем килобайтный блок, отдаем его в вывод и сбрасываем в буфер
       echo fread ($f, 5120);
       flush ();
     }
 // Закрываем файл
     fclose ($f);
   } else {
     header ($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
     header ('Status: 404 Not Found');
   }
exit();
}//функция скачки файла (чтение файла идет по 5метров)
 
# Проверка вхождения тегов в коде и их корректная вложенность друг в друга
# Если вложенность тегов верная возвращается false иначе список незакрытых тегов в форме массива
# Если тегов не найдено, возвращается null
function nesting($text, $tags = array("\? if", "\? endif", "\? foreach", "\? endforeach", "html", "div", "span", "table", "ul", "li", "tr", "td", "form", "label", "button", "script", "noscript", "p", "a")){
	if(preg_match_all($str = "#<(\/?)(". implode("|", $tags). ")(\s.*?)?>#si", $text, $match)){
		$nesting = $tags = array();// mpre($str, array_slice($match, 1));
		foreach($match[2] as $n=>$tag_name){
			$tn = last($nesting);
			if(($sl = get($match, 1, $n)) && ($tag_name == $tn)){
				$tn = array_pop($nesting);
			}elseif(($tn == "? if") && ($tag_name == "? endif")){
				$tn = array_pop($nesting);
			}elseif(($tn == "? foreach") && ($tag_name == "? endforeach")){
				$tn = array_pop($nesting);
			}else{
				$nesting[$n] = $tag_name; //array_push($nesting, $tag_name);
				$tags[$n] = "&lt;". ($sl ? "/" : ""). $match[2][$n]. $match[3][$n]. "&gt;"; //array_push($tags, "&lt;". $match[2][$n]. $match[3][$n]. "&gt;");
			}
		} return empty($nesting) ? false : array_intersect_key($tags, $nesting);
		// return empty($nesting) ? false : $nesting;
	}else{ return null; }
}

function get($ar){
	foreach(array_slice(func_get_args(), 1) as $key){
		if(!empty($ar) && is_array($ar) && strlen($key) && array_key_exists($key, $ar)){
			$ar = $ar[ $key ];
		}else{ return false; }
	} return $ar;
} function first($ar){
	if(!empty($ar) && is_array($ar)){
		return get($ar, min(array_keys($ar)));
	}else{ return false; }
} function last($ar){
	if(!empty($ar) && is_array($ar)){
		return get($ar, max(array_keys($ar)));
	}else{ return false; }
}

function tables($table = null){
	global $conf;
	if($conf['db']['type'] == "sqlite"){
		$tpl['tables'] = qn("SELECT * FROM sqlite_master WHERE type='table'", "name");
	}else{
		$tpl['tables'] = qn("SHOW TABLES", "Tables_in_{$conf['db']['name']}");
	} if($table){
		return $tpl['tables'][$table];
	} return $tpl['tables'];
} function fields($table, $type = false){
	global $conf;
	if($conf['db']['type'] == "sqlite"){
		$tpl['fields'] = qn("pragma table_info ('". $table. "')", "name");
		if($type){
			$tpl['fields'] = array_column($tpl['fields'], "type", "name");
		}
	}else{
		$tpl['fields'] = qn("SHOW FULL COLUMNS FROM ". $table, "Field");
	} return $tpl['fields'];
}

# Подключение страницы
function inc($file_name, $variables = array(), $req = false){
	global $tpl, $arg, $conf;
	$_arg = $arg; extract($variables);
	if(preg_match("#(.*)(\.php|\.tpl|\.html)$#", $file_name, $match)){
		if($f = mpopendir($file_name)){
			if(!array_key_exists('arg', $variables)){ # Если не переопределяем список аргументов
				if(($path = explode("/", $file_name)) && ($path[0] == "modules")){
					$arg = array("modpath"=>$path[1], "fn"=>first(explode(".", $path[2])));
				}
			} if(array_search("Администратор", get($conf, 'user', 'gid'))){
/*				if($block = rb($info = get($conf, 'blocks', 'info'), 'alias', "[{$arg['fn']}]")){ # Составление структуры всех подключаемых шаблонов
					if($backtrace = rb(debug_backtrace(), "function", "[inc]")){
						if($inc = fk("{$conf['db']['prefix']}themes_inc", $w = array("name"=>$backtrace['file']), $w += array("up"=>time(), "index_id"=>get($conf, 'user', 'sess', 'themes_index', 'id')), $w)){
							if($themes_inc = fk("{$conf['db']['prefix']}themes_inc", $w = array("name"=>$f), $w += array("up"=>time(), "index_id"=>get($conf, 'user', 'sess', 'themes_index', 'id')), $w)){
								if($themes_inc_entry = fk("{$conf['db']['prefix']}themes_inc_entry", $w = array("line"=>$backtrace['line'], "inc_id"=>$inc['id'], "themes_inc"=>$themes_inc['id']), $w += array("up"=>time()))){
									$conf['themes']['inc'][ $inc['id'] ] = $inc;
									$conf['themes']['inc'][ $themes_inc['id'] ] = $themes_inc;
									$conf['themes']['inc_entry'][ $themes_inc_entry['id'] ] = $themes_inc_entry;
								}
							}
						}
					}
				}*/
				ob_start();
					if($req){ require $f; }else{ include $f; }
				$content = ob_get_contents(); ob_end_clean();
				if((".tpl" == get($match, 2))){
					echo strtr(get($conf, 'settings', 'modules_start'), array('{path}'=>$f));
					if($nesting = nesting($content)){
						mpre("Ошибка верстки. Нарушена структура вложенности тегов.", $f, $nesting);
					}
				} echo $content;
				if((".tpl" == get($match, 2))){
					echo strtr(get($conf, 'settings', 'modules_stop'), array('{path}'=>$f));
				}
			}else{
				if($req){ require $f; }else{ include $f; }
			} $arg = $_arg; return true;
		} return false;
	}else{
		$p = inc("{$file_name}.php", $variables, $req);
		$t = inc("{$file_name}.tpl", $variables, $req);
		return($p || $t);
	} return false;
}

# Функция определения seo вдреса страницы. Если адрес не определен в таблице seo_redirect то false
# Параметр return определяет возвращать ли ссылку обратно если переадресация не найдена
function seo($href, $return = true){
	global $conf;
	if($themes_index = $conf['user']['sess']['themes_index']){
		if($tpl['seo_location'] = rb("{$conf['db']['prefix']}seo_location", "name", "id", "[{$href}]")){
			if($tpl['seo_index'] = rb("{$conf['db']['prefix']}seo_index", "location_id", "id", $tpl['seo_location'])){
				if($tpl['seo_index_themes'] = rb("{$conf['db']['prefix']}seo_index_themes", "index_id", "themes_index", "id", $tpl['seo_index'], $themes_index['id'])){
					return rb($tpl['seo_index'], "id", rb($tpl['seo_index_themes'], "index_id"), "name");
				}else{ return ($return ? $href : false); }
			}else{ return ($return ? $href : false); }
		}else{ return ($return ? $href : false); }
	}else{
		if(($seo_location = rb("{$conf['db']['prefix']}seo_location", "name", "[{$href}]")) && ($seo_index = rb("{$conf['db']['prefix']}seo_index", "location_id", $seo_location['id']))){
			return $seo_index['name'];
		}else{ return ($return ? $href : false); }
	}
}

if (!function_exists('mcont')){
	function mcont($content){ # Загрузка содержимого модуля
		global $conf, $arg, $tpl;
		foreach($_GET['m'] as $k=>$v){ $k = urldecode($k);
			$mod = get($conf, 'modules', $k);
			$mod['link'] = (is_link($f = mpopendir("modules/{$mod['folder']}")) ? readlink($f) : $mod['folder']);
			ini_set("include_path" ,mpopendir("modules/{$mod['link']}"). ":./modules/{$mod['link']}:". ini_get("include_path"));
			if(get($conf, 'settings', 'modules_title')){
				$conf['settings']['title'] = $conf['modules'][ $k ]['name']. ' : '. $conf['settings']['title'];
			}

			$v = $v != 'del' && $v != 'init' && $v != 'sql' && strlen($v) ? $v : 'index';
			if(get($conf, 'modules', $k, 'access') >= ((strpos($v, 'admin') === 0) ? 4 : 1)){
				$conf['db']['info'] = "Модуль '". ($name = $mod['name']). "'";
				if(preg_match("/[a-z]/", $v)){ $g = "/{$v}.*.php"; }else{ $g = "/*.{$v}.php"; }// pre($g);
				if(($glob = glob($gb = (mpopendir("modules/{$mod['link']}"). $g)))
					|| ($glob = glob($gb = ("modules/{$mod['link']}". $g)))){
					$glob = basename(array_pop($glob));
					$g = explode(".", $glob);
					$v = array_shift($g);
				}

				$fe = ((strpos($_SERVER['HTTP_HOST'], "xn--") !== false) && (count($g) > 1)) ? array_shift($g) : $v;
				$arg = array('modpath'=>$mod['folder'], 'modname'=>$mod['modname'], 'fn'=>$v, "fe"=>$fe, 'access'=>$mod['access']);
				ob_start();
					if($glob){
//						$content .= mpct("modules/{$mod['link']}/{$glob}", $arg);
					}elseif(($v == "admin")){
						if(!inc("modules/{$mod['link']}/admin")){
							inc("modules/admin/admin", array('arg'=>array('modpath'=>$mod['link'], 'fn'=>'admin')));
						}
					}elseif(!inc("modules/{$mod['link']}/{$v}", array('arg'=>$arg))){
						inc("modules/{$mod['link']}/default", array('arg'=>array('modpath'=>$mod['link'], 'fn'=>$v)));
					}
				$content .= ob_get_contents(); ob_end_clean();
			}else if(get($conf, 'modules', $k) && ($_SERVER['REQUEST_URI'] != "/admin")){
				header("HTTP/1.0 403 Access Denied");
				header("Location: /users:login");
			}else{
				if (file_exists(mpopendir("modules/{$mod['link']}/deny.php"))){
//					$content = mpct("modules/{$mod['link']}/deny.php", $conf['arg'] = array('modpath'=>$mod['folder']));
					ob_start();
						inc("modules/{$mod['link']}/deny.php", array('arg'=>array('modpath'=>$mod['folder'])));
					$content .= ob_get_contents(); ob_end_clean();
				}else if(!array_key_exists("themes", $_GET)){
					if(!array_key_exists('null', $_GET) && ($_SERVER['REQUEST_URI'] != "/admin")){
//						header("HTTP/1.0 404 Not Found");
//						header("Location: /themes:404{$_SERVER['REQUEST_URI']}");// header("Location: /admin");
					}
				}
			}
		}
		return $content;
	}
}

if (!function_exists('bcont')){
	function bcont($bid = null){# Загружаем список блоков и прав доступа
		global $theme, $conf, $arg;
		$conf['db']['info'] = "Выборка шаблонов блоков";
		$blocks = mpqn(mpqw("SELECT *, `src` AS src, `reg_id` AS reg_id, `sort` AS sort FROM {$conf['db']['prefix']}blocks_index WHERE enabled=1". ($bid ? " AND id=". (int)$bid : " ORDER BY sort"), "Запрос списка блоков", function($error, $conf){
			if(strpos($error, ".{$conf['db']['prefix']}blocks_index' doesn't exist")){
				qw("ALTER TABLE {$conf['db']['prefix']}blocks RENAME {$conf['db']['prefix']}blocks_index");
			}elseif(strpos($error, "Unknown column 'src' in 'field list'")){
				qw("ALTER TABLE {$conf['db']['prefix']}blocks_index CHANGE `file` `src` varchar(255)");
			}elseif(strpos($error, "Unknown column 'reg_id' in 'field list'")){
				qw("ALTER TABLE {$conf['db']['prefix']}blocks_index CHANGE `rid` `reg_id` int(11) NOT NULL");
			}elseif(strpos($error, "Unknown column 'sort' in 'field list'")){
				qw("ALTER TABLE {$conf['db']['prefix']}blocks_index CHANGE `orderby` `sort` int(11) NOT NULL");
			}else{ mpre("Ошибка не определена", $error); }
		}));
		$blocks_reg = mpqn(mpqw("SELECT *, `name` as name FROM {$conf['db']['prefix']}blocks_reg", "Запрос списка регионов", function($error, $conf){
			if(strpos($error, "Unknown column 'name' in 'field list'")){
				qw("ALTER TABLE {$conf['db']['prefix']}blocks_reg CHANGE `description` `name` varchar(255)");
			}else{ mpre("Ошибка не определена", $error); }
		}));
		$blocks_reg_modules = qn("SELECT * FROM {$conf['db']['prefix']}blocks_reg_modules ORDER BY sort");

		foreach($_GET['m'] as $k=>$v){
			$md[ $k ] = $v ? $v : "index";
		}

		foreach($blocks_reg as $r){
			if($r["term"] == 0){ # Условия выключены
				$reg[ $r['id'] ] = $r;
			}else{
				$brm = rb($blocks_reg_modules, "reg_id", "id", $r['id']);
				if(($array_column = array_column($brm, 'name')) && max($array_column)){ # Если стоит страница
					$br = first($brm = rb($brm, "name", "id", array_flip($md)));
				} if(($array_column = array_column($brm, 'modules_index')) && max($array_column)){
					$brm = rb($brm, "modules_index", "id", array("all")+rb($conf['modules'], "folder", "id", $md));
				} if(($array_column = array_column($brm, 'theme')) && max($array_column)){ # Условие на тему
					$brm = rb($brm, "theme", "id", array_flip(array("", $conf['settings']['theme'])));
				} if(($array_column = array_column($brm, 'uri')) && max($array_column)){ # Адрес страницы в системе. Всегда не главная. (может быть не равен $_SERVER['REDIRECT_URL'])
					$brm = rb($brm, "uri", "id", array_flip(array("", $_SERVER['REQUEST_URI'])));
				} if(($array_column = array_column($brm, 'url')) && max($array_column)){ # Адрес страницы из адресной строки браузера работает если нужно поставил условием главную страницу
					$brm = rb($brm, "url", "id", array_flip(array("", $_SERVER['REDIRECT_URL'])));
				}// mpre(array_flip($md)); mpre($br);

				if(!empty($brm) && ($r["term"] > 0)){ # Условие только
					$reg[ $r['id'] ] = $r;
				}elseif(empty($brm) && ($r["term"] < 0)){ # Исключающее условие
					$reg[ $r['id'] ] = $r;
				}
			} # Условие исключая не срабатывает
		}

		if(get($_SERVER, 'HTTP_REFERER')){
			$gt = mpgt(urldecode(last(explode($_SERVER['HTTP_HOST'], $_SERVER['HTTP_REFERER']))));
		}else{ $gt = mpgt("/"); }

		foreach(rb($blocks, "reg_id", "id", $reg+array(0=>array("id"=>0))) as $k=>$v){
			$conf['blocks']['info'][$v['id']] = $v;
			if($v['access'] < 0){
				$conf['blocks']['info'][ $v['id'] ]['access'] = get($conf, 'modules', first(explode('/', $v['src'])), 'access');
			}
		}

		foreach(mpql(mpqw("SELECT *, index_id AS index_id FROM {$conf['db']['prefix']}blocks_index_gaccess ORDER BY id", 'Права доступа группы к блоку', function($error, $conf){
			if(strpos($error, ".{$conf['db']['prefix']}blocks_index_gaccess' doesn't exist")){
				qw("ALTER TABLE {$conf['db']['prefix']}blocks_gaccess RENAME {$conf['db']['prefix']}blocks_index_gaccess");
			}elseif(strpos($error, "Unknown column 'index_id' in 'field list'")){
				qw("ALTER TABLE {$conf['db']['prefix']}blocks_index_gaccess CHANGE `bid` `index_id` int(11) NOT NULL");
			}else{ mpre("Ошибка не определена", $error); }
			mpre($error);
		})) as $k=>$v){
			if(get($conf, 'user', 'gid', $v['gid'])){
				if(get($conf, 'blocks', 'info', $v['index_id'])){
					$conf['blocks']['info'][ $v['index_id'] ]['access'] = $v['access'];
				}
			}
		} foreach(mpql(mpqw("SELECT *, index_id AS index_id FROM {$conf['db']['prefix']}blocks_index_uaccess ORDER BY id", 'Права доступа пользователя к блоку', function($error, $conf){
			if(strpos($error, ".{$conf['db']['prefix']}blocks_index_uaccess' doesn't exist")){
				qw("ALTER TABLE {$conf['db']['prefix']}blocks_uaccess RENAME {$conf['db']['prefix']}blocks_index_uaccess");
			}elseif(strpos($error, "Unknown column 'index_id' in 'field list'")){
				qw("ALTER TABLE {$conf['db']['prefix']}blocks_index_uaccess CHANGE `bid` `index_id` int(11) NOT NULL");
			}else{ mpre("Ошибка не определена", $error); }
			mpre($error);
		})) as $k=>$v)
			if(/*get($conf, 'blocks', 'info', $v['index_id']) &&*/ ($conf['user']['uid'] == $v['uid'] || (!$v['uid'] && ($conf['user']['uid'] == 0)))){
				$conf['blocks']['info'][ $v['index_id'] ]['access'] = $v['access'];
			}
		foreach(rb($blocks, "reg_id", "id", $reg+array(0=>array("id"=>0))) as $k=>$v){
			if(($conf['settings']['theme'] == $v['theme']) || ((substr($v['theme'], 0, 1) == "!") && ($conf['settings']['theme'] != substr($v['theme'], 1)))){
				$conf['db']['info'] = "Блок '{$conf['blocks']['info'][ $v['id'] ]['name']}'";
				$mod = get($conf, 'modules', basename(dirname(dirname($v['src'])))) ?: array("folder"=>'');
				$arg = array('blocknum'=>$v['id'], 'modpath'=>$mod['folder'], 'modname'=>(get($mod, 'modname') ?: ""), 'fn'=>basename(first(explode('.', $v['src']))), 'uid'=>0, 'access'=>$conf['blocks']['info'][ $v['id'] ]['access']);
				if ($conf['blocks']['info'][ $v['id'] ]['access'] /*&& strlen($cb = mpeval("modules/{$v['src']}", $arg))*/){
					ob_start();
						inc("modules/{$v['src']}", array('arg'=>$arg));
					$cb = ob_get_contents(); ob_end_clean();

					if($conf["settings"]["bid"] = $bid){ $result = $cb; }else{
						if(!is_numeric($v['shablon']) && file_exists($file_name = mpopendir("themes/{$conf['settings']['theme']}/". ($v['shablon'] ?: "block.html")))){
							$shablon[ $v['shablon'] ] = file_get_contents($file_name);
						}else{ $shablon[ $v['shablon'] ] = "<!-- [block:content] -->"; }
						$cb = strtr($shablon[ $v['shablon'] ], $w = array(
							'<!-- [block:content] -->'=>$cb,
							'<!-- [block:id] -->'=>$v['id'],
							'<!-- [block:name] -->'=>$v['name'],
							'<!-- [block:modpath] -->'=>$arg['modpath'],
							'<!-- [block:fn] -->'=>$arg['fn'],
							'<!-- [block:title] -->'=>$v['name']
						));
						$section = array("{modpath}"=>$arg['modpath'],"{modname}"=>$arg['modname'], "{name}"=>$v['name'], "{fn}"=>$arg['fn'], "{id}"=>$v['id']);
						$result["<!-- [block:{$v['id']}] -->"] = strtr(get($conf, 'settings', 'blocks_start'), $section). $cb. strtr(get($conf, 'settings', 'blocks_stop'), $section);
						if(array_key_exists('alias', $v) && ($alias = get($v, 'alias')) && ($n = "<!-- [block:{$alias}] -->")){ # Подключение блока по его алиасу
							$result[$n] = get($result, $n). $result["<!-- [block:{$v['id']}] -->"];
						} if($n = "<!-- [blocks:". $v['reg_id'] . "] -->"){ # Все блоки региона
							$result[$n] = get($result, $n). $result["<!-- [block:{$v['id']}] -->"];
						} if(array_key_exists($v['reg_id'], $reg) && array_key_exists($n = "<!-- [blocks:". $reg[ $v['reg_id'] ]['reg_id']. "] -->", $result)){ # Блоки вышестоящего региона
							$result[$n] .= strtr(get($conf, 'settings', 'blocks_start'), $section). $cb. strtr(get($conf, 'settings', 'blocks_stop'), $section);
						}else{
							$result[$n] = strtr(get($conf, 'settings', 'blocks_start'), $section). $cb. strtr(get($conf, 'settings', 'blocks_stop'), $section);
						}
					}
				}
			}
		} return $result;
	}
}

function gvk($array = array(), $field=false){ 
	return isset($array[$field]) ? $array[$field] : FALSE;
}
//проверка на ассоциативность массива
function mp_is_assoc($array){	
	if(key($array)===0){		
		$keys = array_keys($array);
		return array_keys(array_keys($array)) !== array_keys($array);
	}else{
		return true;
	}
}
//проверка на одномерность массива
function mp_array_is_simple($array){
	return count($array, COUNT_NORMAL)===count($array, COUNT_RECURSIVE);
}
//форматирование массива - приведение двухмерного массива к нужному формату
function mp_array_format($array,$array_format){
	$buf = array();
	if(is_array($array) AND (is_array($array_format) OR is_string($array_format))){
		foreach($array as $key => $value){
			if(is_array($array_format)){
				if(!isset($buf[$key])) $buf[$key] = array();
				foreach($array_format as $key_from => $key_to){						
					if(is_string($key_from)){	
						if(isset($value[(string)$key_from]))
							$buf[$key][(string)$key_to] = $value[(string)$key_from];
					}else{
						if(isset($value[(string)$key_to]))
							$buf[$key][(string)$key_to] = $value[(string)$key_to];
					}					
				}
			}else if(is_string($array_format)){				
				if(!isset($buf[$key])) $buf[$key] = array();					
				if(isset($value[$array_format])) 
					$buf[$key][(string)$array_format] = $value[(string)$array_format];				
			}
		}
	}
	return $buf?:$array;
}

set_error_handler(function ($errno, $errmsg, $filename, $linenum, $vars){
	global $conf;
    $errortype = array (
		1   =>  "Ошибка",
		2   =>  "Предупреждение",
		4   =>  "Ошибка синтаксического анализа",
		8   =>  "Замечание",
		16  =>  "Ошибка ядра",
		32  =>  "Предупреждение ядра",
		64  =>  "Ошибка компиляции",
		128 =>  "Предупреждение компиляции",
		256 =>  "Ошибка пользователя",
		512 =>  "Предупреждение пользователя",
		1024=>  "Замечание пользователя",
		2048=> "Обратная совместимость",
	); mpre(get($errortype, $errno). " ($errno)", $errmsg, $filename/*, get($conf, 'settings', 'data-file')*/, $linenum/*, debug_backtrace()*/);
});
function mpzam($ar, $name = null, $prefix = "{", $postfix = "}", $separator = ":"){ # Создание из много мерного массиива - одномерного. Применяется для подставки в текстах отправляемых писем данных из массивов
	$f = function($ar, $prx = "") use(&$f, $prefix, $postfix, $separator, $name){
		$r = array();
		foreach($ar as $k=>$v){
			$pr = ($prx ? $prx.":".$k : $k);
			if(is_array($v)){
				$r += $f($v, $pr);
			}else{
				$r[$prefix. ($name ? "{$name}". ($separator ?: ":") : ""). $pr. $postfix] = $v;
			}
		} return $r;
	}; return $f($ar);
}
function in($ar, $flip = false){ # Формирует из массива строку с перечисляемыми ключами для подставки в запрос
	if(!is_array($ar) || empty($ar)){
		$ar = array(0);
	}else if($flip){
		 $ar = array_flip($ar);
	} return implode(",", array_map(function($key){
		return (is_numeric($key) || ($key == "NULL")) ? $key : "\"". mpquot($key). "\"";
	}, array_keys($ar)));
}
function aedit($href, $echo = true, $title = null){ # Установка на пользовательскую старницу ссылки в административные разделы. В качестве аргумента передается ссылка, выводится исходя из прав пользователя на сайте
	global $arg, $conf;
	$link = "<div class=\"aedit\" style=\"position:relative; left:-20px; z-index:999; float:right;\"><span style=\"float:right; margin-left:5px; position:absolute;\"><a href=\"{$href}\" title=\"". $title. "\" ><img src=\"/img/aedit.png\" style='max-width:10px; max-height:10px; width:10px; height:10px;'></a></span></div>";
	if(array_search("Администратор", $conf['user']['gid'])){if((bool)$echo) echo $link; else return $link;}	
}



function mptс($time = null, $format = 0){ # Приведение временных данных у удобочитаемую человеческую форму. Обычно для вывода на пользовательские страницы
	if($time === null) $time = time();
	$time = time()-$time;
	$month = explode(",", $conf['settings']['themes_month']);
	$days = floor($time/86400);
	$hours = floor($time/3600)%60;
	$minutes = floor($time/60);
	if($format == 1){
		return ($time > 86400 ? str_pad($days, 2, '0', STR_PAD_LEFT). ":" : "")
				. str_pad($hours%24, 2, '0', STR_PAD_LEFT). ":"
				. str_pad($minutes%60, 2, '0', STR_PAD_LEFT). ":"
				. str_pad($time%60, 2, '0', STR_PAD_LEFT);
	}else{
		return ($days ? " {$days} ". mpfm($days, "день", "дня", "дней") : "").
				($hours ? " ". ($hours%24). " ". mpfm($hours, "час", "часа", "часов") : "").
				($minutes ? " ". ($minutes%60). " ". mpfm($minutes, "минута", "минуты", "минут")  : "");
	}
}
function mb_ord($char){
		list(, $ord) = unpack('N', mb_convert_encoding($char, 'UCS-4BE', 'UTF-8'));
		return $ord;
} function mb_chr($string){
    return html_entity_decode('&#' . intval($string) . ';', ENT_COMPAT, 'UTF-8');
}
# Вызов библиотеки curl Для хранения файла кукисов используется текущая директория. Первым параметром передается адрес запрос, вторым пост если требуется
function mpcurl($href, $post = null, $temp = "cookie.txt", $referer = null, $headers = array(), $proxy = null){
	$ch = curl_init();
	if($proxy){
		curl_setopt($ch, CURLOPT_PROXY, $proxy); //если нужен прокси
	}
	curl_setopt ($ch , CURLOPT_FOLLOWLOCATION , 1);
	curl_setopt($ch, CURLOPT_COOKIEFILE, $temp);//tempnam(ini_get('upload_tmp_dir'), "curl_cookie_")
	curl_setopt($ch, CURLOPT_COOKIEJAR, $temp); //В какой файл записывать
	curl_setopt($ch, CURLOPT_URL, $href); //куда шлем
	if($post){
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, iconv('utf-8', 'cp1251', $post));
	}
	if ($referer) curl_setopt($ch, CURLOPT_REFERER, $referer);
	curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.0; MyIE2; .NET CLR 1.1.4322)");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers); 
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_NOBODY, 0);
	$result=curl_exec ($ch);
	curl_close ($ch);
	return $result;
}
# единственные двойственные и множественные числительные. Пример использования mpfm($n, 'письмо', 'письма', 'писем');
function mpfm($n, $form1, $form2, $form5){
    $n = abs($n) % 100;
    $n1 = $n % 10;
    if ($n > 10 && $n < 20) return $form5;
    if ($n1 > 1 && $n1 < 5) return $form2;
    if ($n1 == 1) return $form1;
    return $form5;
}
# Кеширование данных в memcache
function mc($key, $function, $force = false){
	if($force !== false) mpre($key);
	if(!($tmp = mpmc($key)) || $force){
		mpmc($key, $tmp = $function($key));
		if($force !== false) mpre($tmp);
	} return $tmp;
}
function mpsmtp($to, $obj, $text, $login = null){ # Отправка письмо по SMTP протоколу
	global $conf;
	ini_set("include_path", ini_get("include_path"). ":". "./include/mail/");
	include_once("PEAR.php");
	include_once("Mail.php");
	$param = explode("@", $login ? $login : $conf['settings']['smtp']);
	$host = explode(":", array_pop($param));
	$auth = explode(":", implode("@", $param));
	$mail = Mail::factory(
		'smtp',
		(!empty($smtp) ? $smtp : array (
			'host' => $host[0],
			'port' => $host[1],
			'auth' => true,
			'password' => $auth[1],
			'username' => $auth[0],
			'timeout'=>10,
		))
	);
	if (PEAR::isError($mail)){
		$return = $mail->getMessage();
	} $m = $mail->send( $to,
			(!empty($smtp) ? $smtp : array (
				'From' => $auth[0],
				'To' => $to,
				'Subject' => '=?UTF-8?B?'.base64_encode($obj).'?=',
				'Content-Type' =>'text/html;charset=utf-8',
			)), $text);
	if (PEAR::isError($m)){
		$return = $m->getMessage();
	}else{
		$return = 0;
	} mpevent("SMTP электронное сообщение", $to, $conf['user']['uid'], func_get_args(), $return);
	return $return;
}
function mpue($name){
	return str_replace('%', '%25', trim($name));
}
function mpmc($key, $data = null, $compress = 1, $limit = 1000, $event = true){
	global $conf;
	if(get($conf, 'settings', 'sql_memcache_disable') || !function_exists('memcache_connect')) return false;
	if($memcache = memcache_connect("localhost", 11211)){
		if($data){
			memcache_set($memcache, $key, $data, $compress, $limit);
	//		if($event) mpevent($conf['settings']['users_event_memcache_set'], $key, $conf['user']['uid']);
		}else{
			$mc = memcache_get($memcache, $key);
	//		if($event) mpevent($conf['settings']['users_event_memcache_get'], $key, $conf['user']['uid']);
		} return $mc;
	} return false;
}
function rb($src, $key = 'id'){
	global $conf, $arg, $tpl;
	$func_get_args = func_get_args();
//	echo "<pre>"; print_r($func_get_args); echo "</pre>"; exit;
	if(is_string($src)){
		//проверка полное или коротное название таблицы
		if(!preg_match("#^{$conf['db']['prefix']}.*#iu",$func_get_args[0]))
			$func_get_args[0] = "{$conf['db']['prefix']}{$arg['modpath']}_{$func_get_args[0]}";
	} return call_user_func_array('erb', $func_get_args);
}

# Пересборка данных массива. Исходный массив должен находится в первой форме
#	[0]  = (array)|(string)			массив|название тавлицы
#   	[1] ?= (int) \d+				пагинатор
#	[2] ?= (string) 'id|name_id'	другой id
#	[.] ?= (mixed)					параметры выборки
#
#	erb('table',10,'id|virtuemart_category_id',........ПАРАМЕТРЫ ВЫБОКИ..........);
#	erb('table','id|virtuemart_category_id',........ПАРАМЕТРЫ ВЫБОКИ..........);
#	erb('table',10,........ПАРАМЕТРЫ ВЫБОКИ..........);
#	erb('table',........ПАРАМЕТРЫ ВЫБОКИ..........);
#####################################################################################
function erb($src, $key = 'id'){
	global $arg, $conf, $tpl;
	$purpose = $keys = $return = array();
	$ArrayPositions = array(array(1,2),array(2,3));
	$func_get_args = func_get_args();
/*	$FixID = is_string($func_get_args[$ArrayPositions[is_numeric($key)][0]])?intval(preg_match("#^id\|.*$#",$func_get_args[$ArrayPositions[is_numeric($key)][0]])):0;
	$StartForeach = $ArrayPositions[intval(is_numeric($key))][$FixID];
	$IdName = $FixID?preg_replace("#^id\|(.*)$#","$1",$func_get_args[$StartForeach-1]):'id';*/
	$IdName = "id"; ($StartForeach = (is_numeric(get($func_get_args, 1)) ? 2 : 1));

	foreach(array_slice($func_get_args, $StartForeach) as $a){
		if(is_string($a)){
			if(preg_match('#^\[.*\]$#',trim($a))){
				$a = array_flip(preg_split('#\s*,\s*#', preg_replace('#^\[|\]$#','',trim($a))));
			}
		}
		if(is_numeric($a) || is_array($a) || is_bool($a) || empty($a)){
			if($a === true){ # Удаляем условие на выборку (любые условия)
				array_splice($keys, count($purpose), 1);
			}else if(is_array($a)){
				$purpose[] = (!mp_is_assoc($a) && mp_array_is_simple($a)) ? array_flip($a) : $a;
			}else{
				$purpose[] = $a;
			}
		}else{
			if(!empty($purpose)){
				$field = $a;
			}else{
				$keys[] = $a;
			}
		}
	} if(is_numeric($key)){ # Второй параметр число строим пагинатор
		if(is_array($tab = $src)){
			$src = array_slice($src, 0, $key);
		}else{
			$where = array_map(function($key, $val){
				return "`{$key}`". (is_array($val) ? " IN (". in($val). ")" : "=". (int)$val);
			}, array_intersect_key($keys, $purpose), array_intersect_key($purpose, $keys));
			$src = qn($sql = "SELECT * FROM `{$tab}`". ($where ? " WHERE ". implode(" AND ", $where) : ""). (($order = array_key_exists($n = substr($src, strlen($conf['db']['prefix'])). "=>order", $conf['settings']) ? $conf['settings'][$n] : "") ? " ORDER BY ". mpquot($order) : ""). " LIMIT ". (int)(array_key_exists('p', $_GET) ? $_GET['p']*$key : 0). ",". (int)$key,$IdName);
			$tpl['pager'] = $conf['pager'] = mpager($cnt = ql($sql = "SELECT COUNT(*) AS cnt FROM `{$tab}`". ($where ? " WHERE ". implode(" AND ", $where) : ""), 0, "cnt")/$key);
		}
	}else if(is_string($src)){
		$where = array_map(function($key, $val){
			if(is_null($val)){
				return "`{$key}` IS NULL";
			}elseif(is_array($val)){
				return "`{$key}` IN (". in($val). ")";
			}else{
				return "`{$key}`=". intval($val);
			}
		}, array_intersect_key($keys, $purpose), array_intersect_key($purpose, $keys));
		$src = qn($sql = "SELECT * FROM {$src}". ($where ? " WHERE ". implode(" AND ", $where) : ""). (array_key_exists($n = substr($src, strlen($conf['db']['prefix'])). "=>order", $conf['settings']) && ($order = $conf['settings'][$n]) ? " ORDER BY ". mpquot($order) : ""),$IdName);
	} if($keys){
		if(!empty($src)){
			foreach($src as $v){
				$n = &$return;
				foreach($keys as $s){
					if(empty($n[ $v[$s] ])){
						$n[ $v[$s] ] = array();
					} $n = &$n[ $v[$s] ];
				} $n = $v;
			}
		}else{ $n = array(); }
	}else{ $return = $src; }
	foreach($purpose as $v){
		$r = array();
		if(is_numeric($v) || empty($v)){ # Выборка по целочисленному ключу
			$return = get($return, $v) ? $return[ $v ] : array();
		}else if(is_array($v)){ # Сортировка по ключям массива
			foreach($return as $key=>$val){
				if(array_key_exists($key, $v)){
					$r = array_replace_recursive($r, $val);
				}
			} $return = $r;
		}else if($v === true){ # Выстраивание ключей по порядку
			$inc = 0;
			foreach($return as $k){
				$r[ $inc++ ] = $k;
			} $return = $r;
		}
	} return !empty($field) ? (array_key_exists($field, $return) ? $return[ $field ] : null) : $return;
} function arb($index,$params,$return=null){
	$buff = array($index);
	foreach($params as $key => $param){
		if(!is_int($key)){array_push($buff,$key);}
		else{array_push($buff,$param);}
	}
	foreach($params as $key => $param){
		if(!is_int($key)){array_push($buff,$param);}
	}
	if(is_string($return)){array_push($buff,$return);}
	return call_user_func_array('rb',$buff);
}
# Автоматическое определение кодировки строки и приведение ее в нужную форму.
function mpde($string) { 
	static $list = array('utf-8', 'windows-1251');
	foreach ($list as $item) {
		$sample = @iconv($item, $item, $string);
		if (md5($sample) == md5($string))
			return iconv($item, "utf-8", $string);
	} return null;
}

function mpfdk($tn, $find, $insert = array(), $update = array(), $log = false){
	global $conf, $arg;
	if($find && ($fnd = mpdbf($tn, $find, 1)) &&
		($sel = qn($sql = "SELECT `id` FROM `". mpquot($tn). "` WHERE ". $fnd))
	){
		if((count($sel) == 1) && ($s = array_shift($sel))){
			if($update && ($upd = mpdbf($tn, $update))){
				qw($sql = "UPDATE `". mpquot($tn). "` SET {$upd} WHERE `id`=". (int)$s['id']);
			} return $s['id'];
		}else{ # Множественное обновление. Если в качестве условия используется несколько элементов
			if($update && ($upd = mpdbf($tn, $update))){
				qw($sql = "UPDATE `". mpquot($tn). "` SET {$upd} WHERE `id` IN (". implode(",", array_keys($sel)). ")");
			} return array_keys($sel);
		}
	}elseif($insert){
		qw($sql = "INSERT INTO `". mpquot($tn). "` SET ". mpdbf($tn, $insert+array("time"=>time(), "uid"=>(!empty($conf['user']['uid']) ? $conf['user']['uid'] : 0))));
		return $sel['id'] = $conf['db']['conn']->lastInsertId();
	}
} function fdk($tn, $find, $insert = array(), $update = array(), $log = false){
	if($index_id = mpfdk($tn, $find, $insert, $update, $log)){
		if($line = ql("SELECT * FROM `$tn` WHERE id=". (int)$index_id, 0)){
			return $line;
		}else{ return false; }
	}
} function fk($t, $find, $insert = array(), $update = array(), $key = false, $log = false){
	global $conf, $arg;
	//проверка полное или коротное название таблицы
	if(!preg_match("#^{$conf['db']['prefix']}.*#iu",$t))
		$t = "{$conf['db']['prefix']}{$arg['modpath']}_{$t}";	
	if($index = fdk($t, $find, $insert, $update, $log))
		return $key ? $index[$key] : $index;
}
function mpdk($tn, $insert, $update = array()){
	global $conf, $arg;
	if($ins = mpdbf($tn, $insert)){
		$upd = mpdbf($tn, $update);
		foreach(mpql(mpqw("SHOW COLUMNS FROM $tn")) as $k=>$v){
			$fields[$v['Field']] = $v['Type'];
		}
		if("SELECT id FROM `". mpquot($tn). "` WHERE ")
		mpqw("INSERT INTO `". mpquot($tn). "` SET $ins ON DUPLICATE KEY UPDATE id=LAST_INSERT_ID(id)". ($update ? ", $upd" : ""));
		return mysql_insert_id();
	}
}
function mpevent($name, $description = null, $own = null){
	global $conf, $argv;
	$debug_backtrace = debug_backtrace();
	if(empty($name)){
		if($args = $debug_backtrace[1]['args'][0]){
			$src = "/{$args['modpath']}:{$args['fn']}". ($args['blocknum'] ? "/blocknum:{$args['blocknum']}" : "");
//			mpevent("Неизвестное событие", $src, $conf['user']['uid'], $debug_backtrace);
		} return false;
	}
	if(empty($argv) && empty($conf['settings']['users_log'])) return;
	$func_get_args = func_get_args();
	$keys = array_keys($ar = explode("/", (!empty($debug_backtrace[1]['file']) ? $debug_backtrace[1]['file'] : "")));
	$desc = $ar[max($keys)];

	if(is_numeric($own)){
		$func_get_args[2] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE id=". (int)$own), 0);
	}
	if(!empty($func_get_args[0]) && function_exists("event")){
		$return = event($func_get_args);
	}
//	if((!empty($conf['settings']['users_log']) && $conf['settings']['users_log']) || !empty($argv)){
		if(!empty($conf['event'][$name])) $event = $conf['event'][$name];
		mpqw($sql = "INSERT DELAYED INTO {$conf['db']['prefix']}users_event SET time=". time(). ", uid=". (int)(!empty($conf['user']['uid']) ? $conf['user']['uid'] : 0). ", name=\"". mpquot($name). "\", description=\"". mpquot($desc). "\", count=1 ON DUPLICATE KEY UPDATE time=". time(). ", uid=". (int)(!empty($conf['user']['uid']) ? $conf['user']['uid'] : 0). ", count=count+1, last=". (int)$func_get_args[1]. ", max=IF(". (int)$func_get_args[1]. ">max, ". (int)$func_get_args[1]. ", max), min=IF(". (int)$func_get_args[1]. "<min, ". (int)$func_get_args[1]. ", min), description=\"". mpquot($desc). "\", log_last=". (!empty($event['log']) && $event['log'] ? "(SELECT id FROM {$conf['db']['prefix']}users_event_logs WHERE event_id=". (int)$event['id']. " ORDER BY id DESC limit 1)" : 0));
		if(!empty($argv)){
			$event = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users_event WHERE id=". (int)mysql_insert_id()), 0);
		} $notice = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}users_event_notice WHERE event_id=". (int)$event['id']));
		if((!empty($event['log']) && ($event['log'] > 1)) || $notice){
			if(!is_numeric(get($func_get_args, 2)) && get($func_get_args, 2, "pass")){
				unset($func_get_args[2]['pass']);
			} $zam = mpzam($func_get_args);
		}
		if(!empty($event['log']) && $event['log']){
			mpqw($sql = "INSERT DELAYED INTO {$conf['db']['prefix']}users_event_logs SET time=". time(). ", event_id=". (int)$event['id']. ", uid=". (int)(!empty($conf['user']['uid']) ? $conf['user']['uid'] : 0). ", description=\"". mpquot($description). "\", own=". /*mpquot(is_array($own) ? var_export($own, true) : $own)*/ (int)get($func_get_args, 2, 'id'). ", `return`=\"". (!empty($return) ? mpquot($return) : ""). "\", zam=\"". mpquot(!empty($zam) ? var_export($zam, true) : ""). "\"");
			if(array_key_exists('limit', $event)){
				$remove = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}users_event_logs WHERE event_id=". (int)$event['id']. " AND uid". ($conf['user']['uid'] > 0 ? "=". (int)$conf['user']['uid'] : "<0"). " ORDER BY id DESC LIMIT ". (int)$event['limit']. ",2"));
				if($remove){ # Удаляем все с журнала пользователя
					mpqw($sql = "DELETE FROM {$conf['db']['prefix']}users_event_logs WHERE id IN (". implode(",", array_keys($remove)). ")");
				}
			}
		}
		if($notice){
			foreach($notice as $v){
				if(!empty($v['log']) && $v['log']){ # Сохраняем замещаемые значения
					mpqw($sql = "UPDATE {$conf['db']['prefix']}users_event_notice SET zam=\"". mpquot(var_export($zam, true)). "\" WHERE id=". (int)$v['id']);
				} if($v['grp_id'] > 0){ # Рассылка на группу
					$grp = mpqn(mpqw($sql = "SELECT u.* FROM {$conf['db']['prefix']}users_mem AS m LEFT JOIN {$conf['db']['prefix']}users AS u ON (m.uid=u.id) WHERE 1 AND grp_id=". (int)$v['grp_id']));
				}else{ # Если рассылка владельцу то он нам уже известен
					$grp = array($func_get_args[2]['id']=>$func_get_args[2]);
				} /*mpre($grp);*/ foreach($grp as $m){
					mpqw($sql = "UPDATE {$conf['db']['prefix']}users_event_notice SET count=count+1 WHERE id=". (int)$v['id']);
					$name = strtr(($v['name'] ? $v['name'] : $event['name']), $zam);
					require_once(mpopendir('include/idna_convert.class.inc')); $IDN = new idna_convert();
					$text = (strip_tags($v['text']) ? strtr($v['text'], $zam) : $event['name']);
					switch($v['type']){
						case "email":# Сообщение на электронную почту
							if(preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $m['email'])){
								$response = mpmail($m['email'], $name, $text);
								if($v['log']){ # Сохраняем если включен лог
									mpfdk("{$conf['db']['prefix']}users_event_mess", null, array("event_notice_id"=>$v['id'], "dst"=>$m['email'], "name"=>$name, "text"=>$text, "response"=>$response));
								}
							}
						break;
						case "smtp":# Сообщение по smtp протоколу
							if(preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $m['email'])){
								$response = mpsmtp($m['email'], $name, $text, $v['login']);
								if($v['log']){ # Сохраняем если включен лог
									$event_notice_id = mpfdk("{$conf['db']['prefix']}users_event_mess", null, array("event_notice_id"=>$v['id'], "dst"=>$m['email'], "name"=>$name, "text"=>$text, "response"=>$response));
								}
							}
						break;
						case "sms.ru":# Сервис смс уведомление sms.ru
							include_once mpopendir("include/class/smsru.php");
							$smsru = new \Zelenin\smsru($v['login']);
							$response = $smsru->sms_send($m['tel'], $text);
						break;
						case "skype":# Скайп уведомление
						break;
						case "xmpp":# Уведомление по джаббер протоколу
							if(preg_match("/^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$/", $m['xmpp'])){
								ini_set("include_path", ini_get("include_path"). ":". "/srv/www/vhosts/mpak.cms/include");
								$param = explode("@", $v['login']);// mpre($param);
								$host = explode(":", array_pop($param));// mpre($host);
								$auth = explode(":", implode("@", $param));// mpre($auth);
								include_once(mpopendir("include/webi/xmpp.class.php"));
								$webi = new XMPP(array('user'=>$auth[0], 'pass'=>$auth[1], 'host'=>$host[0], 'port'=>($host[1] ? $host[1] : 5222), 'domain'=>"ya.ru", 'logtxt'=>false,'tls_off'=>0,));
								if($webi->connect()){// установка соединения...
									$webi->sendStatus('text status','chat',3); // установка статуса
									$response = $webi->sendMessage($m['xmpp'], $text); // отправка сообщения
								} if($v['log']){ # Сохраняем если включен лог
									$event_notice_id = mpfdk("{$conf['db']['prefix']}users_event_mess", null, array("uid"=>$m['id'], "event_notice_id"=>$v['id'], "dst"=>$m['xmpp'], "name"=>$name, "text"=>$text, "response"=>$response));
								}
							}
						break;
					}// mpre($v['type']);
				}
			}
		}
//	}
	if(isset($return)) return $return;
}
function mpidn($value, $enc = 0){
	if(!class_exists('idna_convert')){
		require_once(mpopendir('include/idna_convert.class.inc'));
	} $IDN = new idna_convert();
	if($enc){
		return $IDN->encode($value);
	}else{
		return $IDN->decode($value);
	}
}
function mpsettings($name, $value = null, $aid = null){
	global $conf, $arg;
	if($value === null){
		return mpql(mpqw($sql = "SELECT value FROM {$conf['db']['prefix']}settings WHERE name=\"". mpquot($name). "\""), 0, "value");
	}elseif(!empty($value) && ($conf['settings'][$name] != $value)){
		if(mpql(mpqw($sql = "SELECT value FROM {$conf['db']['prefix']}settings WHERE name=\"". mpquot($name). "\""), 0)){
			mpqw($sql = "UPDATE {$conf['db']['prefix']}settings SET value=\"". mpquot($value). "\" WHERE name=\"". mpquot($name). "\"");
		}else{
			mpqw($sql = "INSERT INTO {$conf['db']['prefix']}settings SET modpath=\"". mpquot(array_shift(explode("_", $name))). "\", aid=". (int)($aid ?: 4). ", name=\"". mpquot($name). "\", value=\"". mpquot($value). "\"");
		} return $value;
	} return !empty($name) && !empty($conf['settings'][$name]) ? $conf['settings'][$name] : null;
}

# Разбор адресной строки на параметры для использования в $_GET массиве
function mpgt($REQUEST_URI, $get = array()){
	if(strpos($REQUEST_URI, "?")){
		$keys = array_keys($ar = explode('?', $REQUEST_URI));
		$part = explode('//', str_replace("/null/", "//", $ar[min($keys)]), 2);// mpre($part); exit;
	}else{
		$part = explode('//', str_replace("/null/", "//", $REQUEST_URI), 2);
	} if(!empty($part[1])){
		$param = explode(':', $part[1], 2);// mpre($param);
		$val = array_pop($param);// mpre($val); exit;
		$get += array(urldecode(array_shift($param))=>urldecode($val));
		$get['null'] = '';
	}
	$part = explode('/', $part[0], 3);
	$mod = array_key_exists(1, $part) ? explode(':', $part[1]) : array();
	if(!empty($mod[0])){
		$get['m'] = array(urldecode(array_key_exists(0, $mod) ? $mod[0] : "")=>urldecode(array_key_exists(1, $mod) ? $mod[1] : ""));
		if($mod[0] == 'include' || urldecode($mod[0]) == 'img') $get['null'] = '';
	}
	if(!empty($part[2]) && $part[2] != ''){
		foreach($tpl = explode('/', $part[2]) as $k=>$v){
			if($param = explode(':', $v, 2)){
				if(!empty($param[0]) && !is_numeric($param[0])){
					$get = $get + array(urldecode(array_key_exists(0, $param) ? $param[0] : "")=>urldecode(array_key_exists(1, $param) ? $param[1] : ""));
				}elseif(is_numeric($param[0])){
					$get = array('id'=>$param[0]) + $get;
				}
			}
		}
	} if(!empty($get['стр']) && $get['стр']) $get['p'] = $get['стр'];
	return $get;
}
function mpwr($tn, $get = null, $prefix = null){
	global $conf;
	if(empty($prefix)) $where = ' WHERE 1=1';
	$f = mpqn(mpqw("DESC {$tn}"), 'Field');
	foreach($get !== null ? $get : $_GET as $k=>$v){
		$n = array_pop(explode('.', $k));
		if((substr($k, 0, 1) == '!') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND {$prefix}`". mpquot(substr($k, 1)). "`<>\"". mpquot($v). "\"";
		}elseif(is_numeric($v) && (substr($k, 0, 1) == '+') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND {$prefix}`". mpquot(substr($k, 1)). "`>". (int)$v;
		}elseif(is_numeric($v) && (substr($k, 0, 1) == '-') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND {$prefix}`". mpquot(substr($k, 1)). "`<". (int)$v;
		}elseif(($v !== "") && $f[$n] && gettype($v) == "string"){
			$where .= " AND {$prefix}`". mpquot($k). "`=\"". mpquot($v). "\"";
		}
	} return $where;
}
/*function mpwr($tn, $get = array()){
	global $conf;
	$where = ' WHERE 1=1';
	$f = mpqn(mpqw("DESC {$tn}"), 'Field');
	foreach((array)$get ?: $_GET as $k=>$v){
		$n = array_pop(explode('.', $k));
		if((substr($k, 0, 2) == '!') && ($f[substr($k, 2)] || $f[$n])){
			$where .= " AND ". mpquot(substr($k, 2)). "<>\"". mpquot($v). "\"";
		}elseif(is_numeric($v) && (substr($k, 0, 1) == '+') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND ". mpquot(substr($k, 1)). ">". (int)$v;
		}elseif(is_numeric($v) && (substr($k, 0, 1) == '-') && ($f[substr($k, 1)] || $f[$n])){
			$where .= " AND ". mpquot(substr($k, 1)). "<". (int)$v;
		}elseif(is_numeric($v) && $f[$n]){
			$where .= " AND ". mpquot($k). "=". (int)$v;
		}
	} return $where;
}*/
function mpmail($to = '', $subj='Проверка', $text = 'Проверка', $from = ''){
	global $conf;
	if($conf['settings']['smtp']){
		return mpsmtp($to, $subj, $text);
	} mpevent("Отправка сообщения", $to, $conf['user']['uid'], debug_backtrace());
	if(empty($to)){ return false; }else{
		$header = "Content-type:text/html; charset=UTF-8;". ($from ? " From: {$from};" : "");
		mail($to, $subj, $text, $header/*, ($from ? "-f$from" : null)*/);
		mpevent($conf['settings']['users_event_mail'], $to, $conf['user']['uid'], $subj, $text);
		return true;
	}
}
function spisok($sql, $str_len = null, $left_pos = 0){
	global $conf;
	$spisok = array();
	if($result = mpqw($sql)){
		while($line = $result->fetch()){
	//		list($id, $name) = $line;
			$name = array_pop($line);
			$id = array_pop($line);
	//		pre($line); pre($id); pre($name);
			if ($str_len) $name = substr($name, $left_pos , $str_len).(strlen($name) > $str_len ? '...' : '');
			$spisok[$id] = $name;
		}
	} return (array)$spisok;
}
function mpfid($tn, $fn, $id = 0, $prefix = null, $exts = array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')){
	global $conf;
	if($prefix === null){
		$file = $_FILES[$fn];
	}else{
		$file = array(
			'name'=>$_FILES[$fn]['name'][$prefix],
			'type'=>$_FILES[$fn]['type'][$prefix],
			'tmp_name'=>$_FILES[$fn]['tmp_name'][$prefix],
			'error'=>$_FILES[$fn]['error'][$prefix],
			'size'=>$_FILES[$fn]['size'][$prefix],
		);
	}// mpre($file);
	if($file['error'] === 0){
		if ($exts[ $file['type'] ] || isset($exts['*'])){
			if(!($ext = $exts[ $file['type'] ])){
				$ext = '.'. array_pop(explode('.', $file['name']));
			} $f = "{$tn}_{$fn}_". (int)($img_id = mpfdk($tn, $w = array("id"=>$id), $w += array("time"=>time(), "uid"=>$conf['user']['uid']), $w)). $ext;
			if(($ufn = mpopendir('include/images')) && move_uploaded_file($file['tmp_name'], "$ufn/$f")){
				/*if($img_id != $id)*/ mpqw($sql = "UPDATE {$tn} SET `". mpquot($fn). "`=\"". mpquot($return = "images/$f"). "\" WHERE id=". (int)$img_id);
				mpevent("Загрузка файла", $_SERVER['REQUEST_URI'], $conf['user']['uid'], $file);
			}else{
				if($img_id != $id){
					mpqw("DELETE FROM {$tn} WHERE id=". (int)$img_id);
				} mpevent("Ошибка копирования файла", $_SERVER['REQUEST_URI'], $conf['user']['uid'], $file);
			} return $img_id;
		}else{
			echo $file['type'];
			mpevent("Ошибка расширения загружаемого файла", $_SERVER['REQUEST_URI'], $conf['user']['uid'], $file);
			return 0;
		}
	}elseif(empty($file)){
		echo "file error {$file['error']}";
		mpevent("Ошибка загрузки файла", $_SERVER['REQUEST_URI'], $conf['user']['uid'], $file);
	} return null;
}
function mphid($tn, $fn, $id = 0, $href, $exts = array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')){
	global $conf;
	if($data = file_get_contents($href)){
		if (($ext = '.'. preg_replace("/[\W]+.*/", '', preg_replace("/.*?\./", '', $href))) && (array_search(strtolower($ext), $exts) || isset($exts['*']))){
			$f = "{$tn}_{$fn}_". (int)($img_id = mpfdk($tn, $w = array("id"=>$id), $w += array("time"=>time()), $w)). $ext;
			if(($ufn = mpopendir('include/images')) && file_put_contents("$ufn/$f", $data)){
//				mpqw($sql = "UPDATE {$tn} SET `". mpquot($fn). "`=\"". mpquot("images/$f"). "\" WHERE id=". (int)$img_id);
//				chmod(0777, "$ufn/$f"); chown("www-data", "$ufn/$f");
				fk($tn, array("id"=>$img_id), null, array($fn=>"images/$f"));
				mpevent("Загрузка внешнего файла", $href, (!empty($conf['user']['uid']) ? $conf['user']['uid'] : 0), func_get_args());
			}else{
				if($img_id != $id){
					mpqw("DELETE FROM {$tn} WHERE id=". (int)$img_id);
				} mpevent("Ошибка копирования удаленного файла", $href, $conf['user']['uid'], func_get_args());
			} return $img_id;
		}else{
			echo $ext;
			mpevent("Ошибка расширения при загрузке удаленного файла", $href, $conf['user']['uid'], func_get_args());
			return null;
		}
	}else{
		mpevent("Ошибка загрузки внешнего файла", $href, $conf['user']['uid'], func_get_args());
		echo "file href error {$error}";
	} return null;
}
function mpfn($tn, $fn, $id = 0, $prefix = null, $exts = array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp')){
	global $conf;
	mpevent("Устаревшая функция", "mpfn", $conf['users']['uid']);
	if($prefix === null){
		$file = $_FILES[$fn];
	}else{
		$file = array(
			'name'=>$_FILES[$fn]['name'][$prefix],
			'type'=>$_FILES[$fn]['type'][$prefix],
			'tmp_name'=>$_FILES[$fn]['tmp_name'][$prefix],
			'error'=>$_FILES[$fn]['error'][$prefix],
			'size'=>$_FILES[$fn]['size'][$prefix],
		);
	}// mpre($_FILES[$fn]); mpre($file);
	if($file['error'] === 0){
		if ($exts[ $file['type'] ] || isset($exts['*'])){
			if(!($ext = $exts[ $file['type'] ])){
				$ext = '.'. array_pop(explode('.', $file['name']));
			} $f = "{$tn}_{$fn}_". (int)($img_id = mpfdk($tn, array("id"=>$id), array("time"=>time(), "uid"=>$conf['user']['uid']))). $ext;
			if(($ufn = mpopendir('include/images')) && move_uploaded_file($file['tmp_name'], "$ufn/$f")){
				/*if($img_id != $id) */mpqw($sql = "UPDATE {$tn} SET `". mpquot($fn). "`=\"". mpquot($return = "images/$f"). "\" WHERE id=". (int)$img_id);
			}else{
				if($img_id != $id){
					mpqw("DELETE FROM {$tn} WHERE id=". (int)$img_id);
				}
			} return "images/$f";
		}else{
			echo " <span style='color:red;'>{$file['type']}</span>";
		} mpevent("Загрузка файла", $_SERVER['REQUEST_URI'], $conf['user']['uid'], $file);
		return $return ? $return : false;
	}elseif(empty($file)){
		return "error not null";
	} return null;
}

function mpdbf($tn, $post = null, $and = false){
	global $conf;
	$fields = $f = array();
	if(!isset($post)) $post = $_POST;
	foreach(mpql(mpqw("SHOW COLUMNS FROM `$tn`")) as $k=>$v){
		$fields[$v['Field']] = $v['Type'];
	} foreach($post AS $k=>$v){
		if(!empty($conf['settings']['analizsql_autofields']) && $conf['settings']['analizsql_autofields'] && !array_key_exists($k, $fields) && array_search($conf['user']['uname'], explode(',', $conf['settings']['admin_usr'])) !== false){
			mpqw($sql = "ALTER TABLE `$tn` ADD `$k` ". (is_numeric($v) ? "INT" : "varchar(255)"). " NOT NULL"); echo "\n<br>". $sql;
			$f[] = "`$k`=\"". mpquot(strtr($v, array("<"=>"&lt;", ">"=>"&gt;"))). "\"";
		}elseif(array_key_exists($k, $fields)){
			if(is_array($v)){
				if(mp_is_assoc($v)){
					$f[] = "`$k` IN (". mpquot(strtr(implode(",", $v), array("<"=>"&lt;", ">"=>"&gt;"))). ")";
				}else{
					$f[] = "`$k`=\"". mpquot(strtr(implode(",", $v), array("<"=>"&lt;", ">"=>"&gt;"))). "\"";
				}
			}else{
				if($v === null){
					$f[] = ($and ? "`$k` IS NULL" : "`$k`=NULL");
				}elseif(is_int($v)){
					$f[] = "`$k`=". $v;
				}else{
					$f[] = "`$k`=\"". mpquot(strtr($v, array("<"=>"&lt;", ">"=>"&gt;"))). "\"";
				}
			}
		}
	} /*mpre($post, implode(($and ? " AND " : ', '), (array)$f));*/ return implode(($and ? " AND " : ', '), (array)$f);
}
function mpager($count, $null=null, $cur=null, $url=null){
	global $conf;
	$p = (strpos($_SERVER['HTTP_HOST'], "xn--") === 0) ? "стр" : "p";
	if ($cur === null) $cur = (array_key_exists($p, $_GET) ? $_GET[$p] : 0);
	if ($url === null){
		if(array_key_exists($p, $_GET)){
			$url = strtr($u = urldecode($_SERVER['REQUEST_URI']), array("/{$p}:{$_GET[$p]}"=>'', "&{$p}={$_GET[$p]}"=>''));
		}else{
			$url = $u = urldecode($_SERVER['REQUEST_URI']);
		}
	}
	if ($null){
		$url = str_replace($u, $u. (strpos($url, '&') || strpos($url, '?') ? "&null" : "/null"), $url);
	}else if($null === false){
		$url = strtr($url, array("/null"=>"", "&null"=>"", "?null"=>""));
	}
	if(2 > $count = ceil($count)) return;
	$return = "<script>$(function(){ $(\".pager\").find(\"a[href='". urldecode($_SERVER['REQUEST_URI']). "']\").addClass(\"active\").css(\"font-weight\", \"bold\"); })</script>";
	$return .=  "<div class=\"pager\">";
	$mpager['first'] = $url;
	$return .= "<a rel=\"prev\" href=\"$url".($cur > 1 ? "/{$p}:".($cur-1) : '')."\">&#8592; назад</a>";
	$mpager['prev'] = $url. ($cur > 1 ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=".($cur-1) : "/{$p}:".($cur-1)) : '');
	for($i = max(0, min($cur-5, $count-10)); $i < ($max = min($count, max($cur+5, 10))); $i++){
		$mpager[ $i+1 ] = $url. ($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=$i" : (substr($url, -1, 1) == "/" ? "" : "/"). "{$p}:$i") : '');
		$return .=  '&nbsp;'. ("<a href=\"$url".($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=$i" : (substr($url, -1, 1) == "/" ? "" : "/"). "{$p}:$i") : ''). "\">".($i+1)."</a>");
	}
	$return .=  '&nbsp;';
	$return .=  "<a rel=\"next\" href=\"$url".($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=".($cur+1) : "/{$p}:".($cur+1)) : '')."\">вперед &#8594;</a>";
	$mpager['next'] = $url. ($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=".($cur+1) : (substr($url, -1, 1) == "/" ? "" : "/"). "{$p}:". min($max-1, $cur+1)) : '');
	$mpager['last'] = $url. ($i ? (strpos($url, '&') || strpos($url, '?') ? "&{$p}=".($count-1) : (substr($url, -1, 1) == "/" ? "" : "/"). "{$p}:". ($count-1)) : '');
	$return .= "</div>";
	if(($fn = mpopendir("themes/{$conf['settings']['theme']}/mpager.tpl")) || ($fn = mpopendir("themes/zhiraf/mpager.tpl"))){
		ob_start();
		include($fn);
		$return = ob_get_contents();
		ob_end_clean();
		return $return;
	}else{ return $return; }
}
function mphash($user, $pass){
	return md5("$user:".md5($pass));
}
function mpget($name, $value = null){
	$param = "$name".(strlen($value) ? "=$value" : '');
	if (isset($_GET[$name])){
		return str_replace("$name={$_GET[$name]}", $param, $_SERVER['REQUEST_URI']);
	}else{
		return $_SERVER['REQUEST_URI'].(strpos($_SERVER['REQUEST_URI'], '?') ? '&' : '?').$param;
	}
}
function mpct($file_name, $arg = array(), $vr = 1){
	global $conf, $tpl;
	foreach(explode('::', strtr(strtr($conf["db"]["open_basedir"], array(":"=>"::")), array("phar:://"=>"phar://"))) as $k=>$v)
		if (file_exists($file = "$v/$file_name")) break;
	if (!file_exists($file = "$v/$file_name")) return false;
	$conf['settings']['data-file'] = $file;
	$func_name = create_function('$arg', "global \$conf, \$tpl;\n". strtr(file_get_contents($file), $vr ? array('<? die;'=>'', '<?'=>'', '?>'=>'') : array()));
	ob_start(); $func_name($arg);
	$content = ob_get_contents(); ob_end_clean();
	return $content;
}
function mpeval($file_name, $arg = array(), $vr = 1){
	global $conf;
	foreach(explode('::', strtr(strtr($conf["db"]["open_basedir"], array(":"=>"::")), array("phar:://"=>"phar://"))) as $k=>$v)
		if (file_exists($file = "$v/$file_name")) break;
	if (!file_exists($file = "$v/$file_name")) return "<div style=\"margin-top:100px; text-align:center;\"><span style=color:red;>Ошибка доступа к файлу</span> $v/$file_name</div>";
	ob_start();
	$conf['settings']['data-file'] = $file;
	eval('?>'. strtr(file_get_contents($file), array('<? die;'=>'<?', '<?php die;'=>'<?php')));
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}
function mpreaddir($file_name, $merge=0){
	global $conf;
	$itog = array();
	$prefix = $merge ? explode('::', strtr(strtr($conf["db"]["open_basedir"], array(":"=>"::")), array("phar:://"=>"phar://"))) : array('./');
	if ($merge < 0) krsort($prefix);
	foreach($prefix as $k=>$v){
		if (!is_dir("$v/$file_name")) continue;
		$dir = opendir("$v/$file_name");
		$files = array();
		while($file = readdir($dir)){
			if (substr($file, 0, 1) == '.') continue;
			$files[] = $file;
		}
		$itog = array_merge_recursive($itog, $files);
	}
	return $itog;
}
function mpopendir($file_name, $merge=1){
	global $conf;
	$prefix = $merge ? explode('::', strtr(strtr($conf["db"]["open_basedir"], array(":"=>"::")), array("phar:://"=>"phar://"))) : array('./');
	if ($merge < 0) krsort($prefix);
	foreach($prefix as $k=>$v){
		$file = strtr("$v/$file_name", array('/modules/..'=>''));
		if (file_exists($file)){
			return $file; break;
		}
	}
}
function mpql($dbres, $ln = null, $fd = null){
	$result = array();
	if($dbres){
		try{
			$result = $dbres->fetchAll();
			if($ln !== null && $result){
				$result = $result[$ln];
				if($fd){
					$result = $result[$fd];
				}
			}
		} catch(Exception $e){
			mpre($e->getMessage());
		}
	} return $result;
} function ql($sql, $ln = null, $fd = null){ # Выполнение запроса к базе данных. В случае превышения лимита времени кеширование результата
	$microtime = microtime(true);
	if(!($r = mpmc($key = "ql:". md5($sql)))){
		if($mpqw = mpqw($sql)){
			$r = mpql($mpqw, $ln, $fd);
			if(($mt = (microtime(true) - $microtime)) > .3){
				mpevent("Кеширование списка", $sql);
				mpmc($key, $r);
			}
		} return $r;
	}
}

function mpqn($dbres, $x = "id", $y = null, $n = null, $z = null){
	$result = array();
	if($dbres){
		while($line = $dbres->fetch(PDO::FETCH_ASSOC)){
//		while(($line = $dbres->fetch(PDO::FETCH_COLUMN)) !== false){
			if($z){
				$result[ $line[$x] ][ $line[$y] ][ $line[$n] ][ $line[$z] ] = $line;
			}elseif($n){
				$result[ $line[$x] ][ $line[$y] ][ $line[$n] ] = $line;
			}elseif($y){
				$result[ $line[$x] ][ $line[$y] ] = $line;
			}else{
				$result[ $line[$x] ] = $line;
			}
		}
	}
	return $result;
} function qn($sql){ # Выполнение запроса к базе данных. В случае превышения лимита времени кеширование результата. Возвращается список записей в нормальной форме
	$microtime = microtime(true);
	if(!($r = mpmc($key = "qn:". md5($sql)))){
		$func_get_args = func_get_args();
		$func_get_args[0] = mpqw($sql);
		$r = call_user_func_array('mpqn', $func_get_args);
		if(($mt = microtime(true) - $microtime) > .3){
			mpevent("Кеширование нумерованного списка", $sql);
			mpmc($key, $r);
		}
	} return $r;
}

function mpqw($sql, $info = null, $callback = null, $conn = null){
	global $conf;
	$mt = microtime(true);
	try{
		$result = $conf['db']['conn']->query($sql);
	}catch(Exception $e){
		mpre($sql, $error = $e->getMessage());
		if(is_callable($callback)){
			$callback($error, $conf);
		}
	} if(!empty($conf['settings']['analizsql_log'])){
		$conf['db']['sql'][] = $q = array(
			'info' => $info ? $info : $conf['db']['info'],
			'time' => microtime(true)-$mt,
			'sql' => $sql,
		);
		if(!empty($conf['settings']['sqlanaliz_time_log']) && $q['time'] > $conf['settings']['sqlanaliz_time_log']){
			mpevent("Долгий запрос к базе данных", $sql. " {$q['time']}c.", $conf['user']['uid'], $q);
		}
	} return($result);
} function qw($sql, $info = null, $conn = null){
	global $conf;
	$mt = microtime(true);
	$stm = $conf['db']['conn']->prepare($sql);
	try{
		$return = $stm->execute();
	}catch(Exception $e){
		mpre($sql, $e->getMessage());
	} if(!empty($conf['settings']['analizsql_log'])){
		$conf['db']['sql'][] = $q = array(
			'info' => $info ? $info : $conf['db']['info'],
			'time' => microtime(true)-$mt,
			'sql' => $sql,
		);
		if(!empty($conf['settings']['sqlanaliz_time_log']) && $q['time'] > $conf['settings']['sqlanaliz_time_log']){
			mpevent("Долгий запрос к базе данных", $sql. " {$q['time']}c.", $conf['user']['uid'], $q);
		}
	}// return($result);
}
function mpfile($filename, $description = null){
//	$file_name = strtr($file_name, array('../'=>'', '/./'=>'/', '//'=>'/'));
	$file_name = mpopendir("include/$filename");
	if (file_exists($file_name)){
		$ext = explode('.', $file_name); $ext = $ext[count($ext) - 1];
//		header("Content-Type:	 text/html; charset=windows-1251");
//		header("Content-Type: application/force-download; name=\"".($description ? "/$description/". (substr($description, strlen($ext)*-1)) : basename($file_name))."\"");
		header("Content-Transfer-Encoding: binary");
		header("Content-Length: ".filesize("$file_name"));
		header("Content-Disposition: attachment; filename=\"".($description ? "$description". (substr($description, strlen($ext)*-1) == $ext ? "" : ".". $ext) : basename($file_name))."\"");
		header("Expires: ".gmdate("r", time() + 86400*10));
		header('Cache-Control: max-age=28800');
//		header("Cache-Control: max-age=3600, must-revalidate");
//		header("Pragma: no-cache");
//		readfile($file_name); exit;
		$handle = fopen($file_name, 'rb');
		while (!feof($handle)){
			echo fread($handle, 4096);
			ob_flush();
			flush();
		} fclose($handle); exit;
	}else{
		return '';
	}
}
function mpgc($value, $param = null){
	if ($param) unset($value[$param]);
	ob_start();
	var_dump($value);
	$str = ob_get_contents();
	ob_end_clean();
	return $str;
}
function mpwysiwyg($name, $content = null, $tpl = ""){
	global $conf;
	if(!empty($conf['modules']['redactor']['access'])){
		$conf['settings']['redactor_name'] = $name;
		$conf['settings']['redactor_text'] = $content;
		if($tpl && $fn = mpopendir("modules/redactor/". basename($tpl))){
			include $fn;
		}else{
			include mpopendir("modules/redactor/wysiwyg.tpl");
		}
	}elseif(!empty($conf['modules']['tinymce']['access'])){
		$conf['settings']['tinymce_name'] = $name;
		$conf['settings']['tinymce_text'] = $content;
		if($tpl && $fn = mpopendir("modules/tinymce/". basename($tpl))){
			include $fn;
		}else{
			include mpopendir("modules/tinymce/wysiwyg.tpl");
		}
	}elseif(true){
		include_once("include/spaw2/spaw.inc.php");
		ob_start();
		$spaw1 = new SpawEditor($name, $content);
		$spaw1->show();
		$spaw2 = ob_get_contents();
		ob_end_clean();
		return $spaw2;
	}elseif(!empty($conf['modules']['rte']['access'])){
		$conf['settings']['rte_name'] = $name;
		$conf['settings']['rte_text'] = $content;
		include mpopendir("modules/rte/wysiwyg.tpl");
	}else{
		return "<textarea name='$name' style='width:100%; height:200px;'>$content</textarea>";
	}
}
function mpmenu($m = array()){
	global $conf, $arg;
	# Скрываем меню в админке для администраторов
	if($conf['settings']['admin_mpmenu_hide'] && $arg['access'] < 5) return;
	if(array_key_exists("null", $_GET)) return false;
	$tab = (int)$_GET['r'];
	if($_GET['r']){
		echo <<<EOF
			<script>
				$(function(){
					$('.tabs li.{$tab}').add('.tabs li.{$_GET['r']}').addClass('act');
				});
			</script>
EOF;
	}
	if(empty($conf['settings']['admin_help_hide'])){
		echo '<div style="float:right; margin:5px;"><a target=blank href="//mpak.su/help/modpath:'. $arg['modpath']. "/fn:". $arg['fn']. '/r:'. $_GET['r']. '">Помощь</a></div>';
	}
	if($modname = array_search('admin', $_GET['m'])){
		$modname_id = mpfdk("{$conf['db']['prefix']}modules",
			array("folder"=>$modname), null, array("priority"=>time())
		);
	}
	echo '<ul class="nl tabs">';
	foreach($m as $k=>$v){
		if (($v[0] == '.') && ($_GET['r'] != $k)) continue;
		echo "<li class=\"$k\"><a href=\"/{$modname}:admin". ($k ? "/r:$k" : ''). "\">$v</a></li>";
	}
	echo '</ul>';
	if(!empty($m) && empty($_GET['r'])){
		if(!is_numeric($r = array_shift(array_keys($m))) && (strpos($_SERVER['REQUEST_URI'], "?") !== false)){
			header("Location: /admin:{$arg['modname']}/r:". array_shift(array_keys($m)));
		}
	}
}

function pre(){
	$lines = false;
	$keys = array_keys($ar = array_slice($func_get_args = func_get_args(), -1, 1));
	if(is_bool($bool = $ar[max($keys)]) && ($lines = $bool)){
		$func_get_args = array_slice(func_get_args(), -1, 1);
	}else{ /*exit(var_dump($lines));*/ }

	$debug_backtrace = debug_backtrace();

	if($lines){
		$list = $debug_backtrace;
	}else{
		if($l = rb($debug_backtrace, "function", "[mpre]")){
			$list = array($l);
		}else{
			$list = array(rb($debug_backtrace, "function", "[pre]"));
		}
	} foreach($list as $k=>$v){
		if(true){ # Комментарии выводим для javascript шаблонов. Чтобы они игнорировались как код
			echo "/*<fieldset><legend>[$k] {$v['file']}:{$v['line']} <b>{$v['function']}</b> ()</legend>*/";
		}else{
			echo "/*\n[$k] {$v['file']}:{$v['line']} <b>{$v['function']}</b> ()<br>\n*/";
		}
		foreach($v['args'] as $n=>$z){
			echo "/*<pre>\n\t"; print_r($z); echo "\n</pre>*/";
		}
		if(true) echo "/*</fieldset>*/\n";
	}
} function mpre(){
	global $conf, $arg, $argv;
	if(array_key_exists('user', $conf) && array_key_exists('gid', $conf['user']) && array_search("Администратор", $conf['user']['gid'])){
		return call_user_func_array('pre', func_get_args());
	}
//	if(empty($argv) && ($arg['access'] < $access)) return;
}
function mpqwt($result){
	echo "<table style='background-color:#888;' cellspacing=0 cellpadding=3 border=1><tr>";
	foreach($result[0] as $k=>$v){
		echo "<td align=center style='background-color: #888; color:white;'><b>$k</b></td>";
	} echo "</tr>";
	foreach($result as $k=>$l){
		echo "<tr valign='top' style='background-color: #eee;'>";
		foreach($l as $null=>$v){
			echo "<td>".(strlen($v) ? $v : '&nbsp;')."</td>";
		}
		echo "</tr>";
	}
	echo "</table>";
}
/*function mptree($ar, $func, $top = array("id"=>0), $level = 0, $line = 0){
	global $arg, $conf;
	$tree = function($p, $tree, $func, $level, $line) use($ar, $conf, $arg){
 		if($level) $func($p, $ar, $line);
		if($ar[ $p['id'] ]){
			foreach($ar[ $p['id'] ] as $v){
				$tree($v, $tree, $func, $level, $line+1);
			}
		} if(!$level) $func($p, $ar, $line);
	}; $tree($top, $tree, $func, $level, $line);
}*/
function mpquot($data){	
	if(ini_get('magic_quotes_gpc')){
		$data = stripslashes($data); //; Волшебные кавычки для входных данных GET/POST/Cookie. magic_quotes_gpc = On
	}
	$data = str_replace("\\", "\\\\", $data); 
	$data = str_replace("'", "\'", $data); 
	$data = str_replace('"', '\"', $data); 
	$data = str_replace("\x00", "\\x00", $data); 
	$data = str_replace("\x1a", "\\x1a", $data); 
	$data = str_replace("\r", "\\r", $data); 
	$data = str_replace("\n", "\\n", $data); 
	return $data;
}

# Изменение размеров изображения. ($max_width и $max_height) высота и ширина. Параметр $crop это способ обработки. Обрезать или вписать в размер
function mprs($file_name, $max_width=0, $max_height=0, $crop=0){
	global $conf;
	$func = array(
		'jpg' => 'imagejpeg',
		'jpeg' => 'imagejpeg',
		'png' => 'imagepng',
		'gif' => 'imagegif',
	);
	$keys = array_keys($ar = explode('.', $file_name));
	$ext = $ar[max($keys)];
	$cache_name = (ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : "/tmp"). "/images";
	$host_name = strpos('www.', $_SERVER['SERVER_NAME']) === 0 ? substr($_SERVER['SERVER_NAME'], 4) : $_SERVER['SERVER_NAME'];
	$fl_name = (int)$max_width. "x". (int)$max_height. "x". (int)$crop. "_" .basename($file_name);
	$prx = basename(dirname($file_name));
	if(!array_key_exists('nologo', $_GET) && isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= filectime($file_name))){
		header('HTTP/1.0 304 Not Modified');
	}else if(file_exists("$cache_name/$host_name/$prx/$fl_name") && (($filectime = filectime("$cache_name/$host_name/$prx/$fl_name")) > ($sfilectime = filectime($file_name)))){
		header('Last-Modified: '. date("r", $filectime));
		header("Expires: ".gmdate("r", time() + 86400*10));
		return file_get_contents("$cache_name/$host_name/$prx/$fl_name");
	}else if($src = imagecreatefromstring(file_get_contents($file_name))){
		header('Last-Modified: '. date("r", filectime($file_name)));
		header("Expires: ".gmdate("r", time() + 86400*10));
		$width = imagesx($src);
		$height = imagesy($src);
		if(!array_key_exists('water', $_GET) && (empty($max_width) || empty($max_height) || (($width <= $max_width) && ($height <= $max_height)))){
			$content = file_get_contents($file_name);
		}else{
			if($crop){
				$cdst = array($max_width, $max_height);
				$max = max($max_width/$width, $max_height/$height);
				$irs = array(4=>($width-$max_width/$max)/2, ($height-$max_height/$max)/2, $max_width, $max_height, ($max_width/$max), ($max_height/$max),);
			}else{
				$x_ratio = $max_width / $width;
				$y_ratio = $max_height / $height;
				if ( ($width <= $max_width) && ($height <= $max_height) ){
					$tn_width = $width;
					$tn_height = $height;
				}elseif (($x_ratio * $height) < $max_height){
					$tn_height = ceil($x_ratio * $height);
					$tn_width = $max_width;
				}else{
					$tn_width = ceil($y_ratio * $width);
					$tn_height = $max_height;
				}
				$irs = array(4=>0, 5=>0, $tn_width, $tn_height, $width, $height,);
				$cdst = array($tn_width, $tn_height);
			}
			$dst = imagecreatetruecolor($cdst[0], $cdst[1]);
			imagealphablending($dst, false);
			imagesavealpha($dst, true);
			imagecopyresampled($dst, $src, 0, 0, $irs[4], $irs[5], $irs[6], $irs[7], $irs[8], $irs[9]);
			if (
				!array_key_exists('nowater', $_GET) &&
				!empty($conf['settings']['theme_logo']) &&
				(imagesx($dst) >= 200) &&
				(imagesy($dst) >= 200) &&
				!isset($_GET['m']['themes']) &&
				($lg = explode(':', $conf['settings']['theme_logo'])) &&
				($f = mpopendir("themes/{$conf['settings']['theme']}/". array_shift($lg))) &&
				$logo = imagecreatefromstring(file_get_contents($f))
			){
				imagealphablending($dst, true);
				$w = array_shift($lg); $h = array_shift($lg);
				imagecopyresampled($dst, $logo, ($w < 0 ? imagesx($dst)-imagesx($logo)+$w : $w), ($h < 0 ? imagesy($dst)-imagesy($logo)+$h : $h), 0, 0, imagesx($logo), imagesy($logo), imagesx($logo), imagesy($logo));
			}
			ob_start();
				$keys = array_keys($ar = explode('.', $file_name));
				$func[ strtolower($ar[max($keys)]) ]($dst, null, -1);
				$content = ob_get_contents();
			ob_end_clean();
			ImageDestroy($src); ImageDestroy($dst);
		}
		if(!file_exists("$cache_name/$host_name/$prx")){
			if($idna = mpopendir('include/idna_convert.class.inc')){
				require_once($idna);
			}
			$IDN = new idna_convert();
			mkdir("$cache_name/$host_name/$prx", 0755, 1);
			if($host_name != $IDN->decode($host_name) && !file_exists("$cache_name/". $IDN->decode($host_name))){
				symlink("$cache_name/$host_name", "$cache_name/". $IDN->decode($host_name));
			}
		}/* mpre("$cache_name/$host_name/$prx/$fl_name");*/ file_put_contents("$cache_name/$host_name/$prx/$fl_name", $content);
		if(function_exists("mpevent")){
			mpevent("Формирование изображения", $fl_name, $conf['user']['uid']);
		} return $content;
	}else{
		$src = imagecreate (65, 65);
		$bgc = imagecolorallocate ($src, 255, 255, 255);
		$tc = imagecolorallocate ($src, 0, 0, 0);
		imagefilledrectangle ($src, 0, 0, 150, 30, $bgc);
		header("Content-type: image/jpeg");
		header('Last-Modified: '. date("r"));
		mpevent("Ошибка открытия изображения", $file_name, $conf['user']['uid']);
		imagestring($src, 1, 5, 30, (file_exists($file_name) ? "GD Error" : "HeTKapmuHku"), $tc);
		return ImageJpeg($src);
	}
}

if(!function_exists("array_column")){
	function array_column(array $input, $columnKey, $indexKey = null) {
		$result = array();
		if(null === $indexKey){
			if(null === $columnKey){
				$result = array_values($input);
			}else{
				foreach($input as $row){
					$result[] = get($row, $columnKey);
				}
			}
		}else{
			if(null === $columnKey){
				foreach($input as $row){
					$result[$row[$indexKey]] = $row;
				}
			}else{
				foreach($input as $row){
					$result[$row[$indexKey]] = $row[$columnKey];
				}
			}
		} return $result;
	}
}
