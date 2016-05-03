<?
	if(/*!get($conf, 'settings', 'canonical') &&*/ !array_key_exists("null", $_GET) && !array_key_exists("p", $_GET) && ($conf['settings']['theme/*:admin'] != $conf['settings']['theme']) && !array_search($arg['fn'], ['', 'ajax', 'json', '404', 'img'])){ # Нет перезагрузки страницы адреса
		if(!($diff = array_diff_key($_GET, array_filter($_GET)))){
			if($alias = "{$arg['modpath']}:{$arg['fn']}". (($keys = array_keys(array_diff_key($_GET, array_flip(["m", "id"])))) ? "/". implode("/", $keys) : "")){
				if($seo_cat = fk("{$conf['db']['prefix']}seo_cat", $w = array("alias"=>$alias), $w += array("name"=>$conf['modules'][$arg['modpath']]['name']. " » ". (get($conf, 'settings', "{$arg['modpath']}_{$arg['fn']}") ?: $arg['fn']))/*, $w*/)){
					if(empty($seo_cat['hide'])){
						if($settings = mpzam($conf['settings'], "settings")){
							if($characters_lang = rb("{$conf['db']['prefix']}seo_characters_lang", "name", $w = "[". ((strpos($_SERVER['HTTP_HOST'], "xn--") === 0) ? "Русские" : "Английские"). "]")){
								if($characters = array_column(rb("{$conf['db']['prefix']}seo_characters", "characters_lang_id", "id", array_flip([$characters_lang['id'],0])), "to", "from")){
									if($seo_cat['href'] && ("/" == substr($seo_cat['href'], 0, 1)) /*&& ("/" == substr($seo_cat['href'], -1, 1))*/){
										if(get($_GET, 'id')){ # Проверка и формирование методанных объекта
											if(($default = rb($arg['fn'], "id", $_GET['id']))){
												foreach(array_intersect_key($seo_cat, array_flip(array('title', 'description', 'keywords', "href"))) as $n){
													if(preg_match_all("#{([\w-_]+):(\w+)}#", $n, $match)){ mpre($n, $match);
														foreach($match[0] as $n=>$m){
															if(empty($e) || !rb($e, "table", "field", "[{$match[1][$n]}]", "[{$match[2][$n]}]")){
																$e[] = array("id"=>(empty($e) ? 0 : count($e)), "table"=>$match[1][$n], "field"=>$match[2][$n]);
															}
														}
													}//else{ mpre($n, $match); }
												} if(!empty($e)){
													foreach($e as $t){
														if(strpos($t['table'], "-")){
															if($id = get($_GET, $t['table'])){
																$data[$t['table']] = rb($t['table'], "id", (int)$id);
															}else{ mpre("Ключ не найден"); }
														}
													} while(($tabs = array_intersect_key((empty($d) ? ($d = $default) : $d), array_flip(array_map(function($v){ return "{$v}_id"; }, array_column($e, "table"))))) && (($loop = /*mpre*/(empty($loop) ? 1 : $loop+1)) < 10 /* Максимальное количество итераций */)){ # Если есть ключи от требующихся тегов
														foreach($tabs as $k=>$id){
															$data[$t = substr($k, 0, -3)] = rb($t, "id", $id);
															$d += $data[$t = substr($k, 0, -3)];
															$e = array_diff_key($e, rb($e, "table", "id", "[{$t}]"));
														}
													}
												} if($mpzam = mpzam(empty($data) ? $default : array(""=>$default)+$data)){// exit(mpre($mpzam));
													foreach(array_intersect_key($seo_cat, array_flip(array('title', 'description', 'keywords'))) as $k=>$m){
														if($m){ $meta[$k] = strtr(strtr($m, $settings), $mpzam); }
													} if($src = htmlspecialchars_decode(mb_strtolower(strtr($seo_cat['href'], $mpzam+$settings), 'UTF-8'))){
														if(!preg_match_all("#{(.*):?(.*?)}#", $src. implode("", $meta), $match) && (substr($src, -1) != "/")){
															if($meta && ($meta = meta(array(urldecode($_SERVER['REQUEST_URI']), strtr($src, $characters)), $meta += array("cat_id"=>$seo_cat['id'])))){
																exit(header("Location: {$meta[0]}"));
															}else{ mpre("Мета информация не установлена"); }
														}else{ mpre("В адресе категории <a href='/seo:admin/r:{$conf['db']['prefix']}seo_cat?&where[id]={$seo_cat['id']}'>{$seo_cat['name']}</a> и метаинформации заменены не все теги", $src, $meta); }
													}else{ mpre("Ошибка формирования адреса страницы"); }
												}else{ mpre("Таблица языка перекодировки не найдена <a href='/seo:admin/r:mp_seo_characters_lang'>{$w}</a>"); }
											}else{ mpre("Элемент с указанных номером не найден", $_GET['id']); }
										}else if($src = htmlspecialchars_decode(mb_strtolower(strtr(implode("/", array_slice(explode("/", $seo_cat['href']), 0, 2)), $settings), 'UTF-8'))){ // mpre($src); # Список элементов
											if(!preg_match_all("#{(.*):?(.*?)}#", $src. implode("", $seo_cat), $match) && (substr($src, -1) != "/")){// exit(mpre($src, $match));
												if($meta = meta(array(urldecode($_SERVER['REQUEST_URI']), strtr($src, $characters)), $seo_cat + array("cat_id"=>$seo_cat['id']))){
													exit(header("Location: {$meta[0]}"));
												}else{ mpre("Мета информация не установлена"); }
											}else{ mpre("В адресе и метаинформации заменены не все теги <a href='/seo:admin/r:{$conf['db']['prefix']}seo_cat?&where[id]={$seo_cat['id']}'>{$seo_cat['name']}</a>", $src, $seo_cat); }
										}else{ mpre("Элемент не найден и адрес списка не верный"); }
									}else{ mpre("Не верный формат seo адреса <a href='/seo:admin/r:{$conf['db']['prefix']}seo_cat?&where[id]={$seo_cat['id']}'>{$seo_cat['name']}</a>"); }
								}else{ mpre("Не установлена таблица перекодировки <a href='/seo:admin/r:mp_seo_characters'>seo_characters</a>"); }
							}else{ mpre("Не найдены данные перекодировки"); }
						}else{ mpre("Ошибка формирования системных переменных"); }
					}else{ /*mpre("Категория отмечена как скрытая");*/ }
				}else{ mpre("Не найдена категория переадресации"); }
			}else{ mpre("Алиас сфоримрован ошибочно"); }
		}else{ mpre("Входящие параметры содержат пустые значения", $diff); }
	}else{ /*mpre(get($conf, "settings", "canonical"));*/ }
