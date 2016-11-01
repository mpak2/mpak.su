<?

if(!$alias = "{$arg['modpath']}:{$arg['fn']}". (($keys = array_keys(array_diff_key($get, array_flip(["m", "id"])))) ? "/". implode("/", $keys) : "")){ mpre("Алиас сфоримрован ошибочно");
}elseif($canonical /*|| (array_key_exists("up", $seo_cat) && array_key_exists("up", $canonical) && ($seo_cat['up'] > $canonical['up']))*/){ # Нет мета или обновление категории больше чем у записи
}elseif(!$seo_cat = fk("{$conf['db']['prefix']}seo_cat", $w = ["alias"=>$alias], $w += ["name"=>$conf['modules'][$arg['modpath']]['name']. " » ". (get($conf, 'settings', "{$arg['modpath']}_{$arg['fn']}") ?: $arg['fn'])], $w)){ mpre("Ошибка добавления категория переадресации", $w);
}elseif(!empty($seo_cat['hide'])){// mpre("Категория скрыта");
}elseif(!$settings = mpzam($conf['settings'], "settings")){ mpre("Ошибка формирования системных переменных");
}elseif(!$characters_lang = rb("seo-characters_lang", "name", $w = "[". ((strpos($_SERVER['HTTP_HOST'], "xn--") === 0) ? "Русские" : "Английские"). "]")){ mpre("Не найдены данные перекодировки");
}elseif(!$characters = array_column(rb("seo-characters", "characters_lang_id", "id", "[{$characters_lang['id']},0,NULL]"), "to", "from")){ mpre("Не установлена таблица перекодировки <a href='/seo:admin/r:mp_seo_characters'>seo_characters</a>");
}elseif(!$seo_cat['href'] && ("/" == substr($seo_cat['href'], 0, 1))){ mpre("Не верный формат seo адреса <a href='/seo:admin/r:{$conf['db']['prefix']}seo_cat?&where[id]={$seo_cat['id']}'>{$seo_cat['name']}</a>");
}else{// mpre($seo_cat);
	if(array_diff_key($get, array_flip(['m']))){ $meta = []; $mpzam = mpzam($_GET, 'get'); # Проверка и формирование методанных объекта
		if(($arg['fn'] == "img") && ($tn = get($get, 'tn'))){
			$default = rb($tn, "id", get($get, 'id'));
		}else{
			$default = rb($arg['fn'], "id", get($get, 'id'));
//													mpre("Основной элемент", $default);
		}//else{ mpre("Элемент с указанных номером не найден", get($get['id']); }
		foreach(array_intersect_key($seo_cat, array_flip(array('title', 'description', 'keywords', "href"))) as $n){
			if(preg_match_all("#{([\w-_]+):(\w+)}#", $n, $match)){// mpre($n, $match);
				foreach($match[0] as $n=>$m){
					if(empty($e) || !rb($e, "table", "field", "[{$match[1][$n]}]", "[{$match[2][$n]}]")){
						$e[] = array("id"=>(empty($e) ? 0 : count($e)), "table"=>$match[1][$n], "field"=>$match[2][$n]);
					}
				}
			}//else{ mpre($n, $match); }
		} if(!empty($e)){/*{
					$meta[$k] = strtr(strtr($m, $settings), $mpzam);
				}*/
			foreach($e as $t){
				if(strpos($t['table'], "-")){
					if($id = get($get, $t['table'])){
						$data[$t['table']] = rb($t['table'], "id", (int)$id);
					}else{ mpre("Ключ не найден <a href='/seo:admin/r:{$conf['db']['prefix']}seo_cat?&where[id]={$seo_cat['id']}'>{$seo_cat['name']}</a>"); }
				}
			} while(($tabs = array_intersect_key((empty($d) ? ($d = $default) : $d), array_flip(array_map(function($v){ return "{$v}_id"; }, array_column($e, "table"))))) && (($loop = /*mpre*/(empty($loop) ? 1 : $loop+1)) < 10 /* Максимальное количество итераций */)){ # Если есть ключи от требующихся тегов
				foreach($tabs as $k=>$id){
					$data[$t = substr($k, 0, -3)] = rb($t, "id", $id);
					$d += $data[$t = substr($k, 0, -3)];
					$e = array_diff_key($e, rb($e, "table", "id", "[{$t}]"));
				}
			}
		} if($mpzam += mpzam(empty($data) ? $default : array(""=>$default)+$data)){// exit(mpre($mpzam));
			foreach(array_intersect_key($seo_cat, array_flip(array('title', 'description', 'keywords'))) as $k=>$m){
				if($m){// mpre($k, $m, $mpzam);
					$meta[$k] = strtr(strtr($m, $settings), $mpzam);
				}
			} if($src = htmlspecialchars_decode(mb_strtolower(strtr($seo_cat['href'], $mpzam+$settings), 'UTF-8'))){
				if(!preg_match_all("#{(.*):?(.*?)}#", $src. implode("", $meta), $match) && (substr($src, -1) != "/")){
					/*if(!$meta){ mpre("Не установлена мета информация");
					}else*/if($location = meta(array(urldecode($uri), $src = strtr($src, $characters)), $meta += array("cat_id"=>$seo_cat['id']))){
						mpevent("Мета элемент", $src);
						exit(header("Location: {$location[0]}"));
					}else{ mpre("Мета информация обновлена", $meta); }
				}else{ mpre("В адресе категории <a href='/seo:admin/r:{$conf['db']['prefix']}seo_cat?&where[id]={$seo_cat['id']}'>{$seo_cat['name']}</a> и метаинформации заменены не все теги", $src, $meta, "доступные для замены элементы", $mpzam+$settings); }
			}else{ mpre("Ошибка формирования адреса страницы <a href='/seo:admin/r:seo-cat?&where[alias]={$seo_cat['alias']}'>{$seo_cat['alias']}</a>"); }
		}else{ mpre("Таблица языка перекодировки не найдена <a href='/seo:admin/r:mp_seo_characters_lang'>{$w}</a>"); }
	}else if($src = trim(htmlspecialchars_decode(mb_strtolower(strtr(implode("/", array_slice(explode("/", $seo_cat['href']), 0, 2)), $settings), 'UTF-8')))){ // mpre($src); # Список элементов
		foreach(array_intersect_key($seo_cat, array_flip(array('title', 'description', 'keywords'))) as $k=>$m){
			if($m){
				$meta[$k] = strtr($m, $settings);
			} $meta[$k] = preg_replace("#{(.*?)}#", "", $meta[$k]);
		} if(!preg_match_all("#{(.*):?(.*?)}#", $src. implode("", $meta), $match) && (substr($src, -1) != "/")){// exit(mpre($src, $meta));
			if($meta = meta(array(urldecode($uri), $src = strtr($src, $characters)), $meta + array("cat_id"=>$seo_cat['id']))){
				mpevent("Мета список", $src);
				exit(header("Location: {$meta[0]}"));
			}else{ mpre("Мета информация не установлена"); }
		}else{ mpre("В адресе и метаинформации заменены не все теги <a href='/seo:admin/r:{$conf['db']['prefix']}seo_cat?&where[id]={$seo_cat['id']}'>{$seo_cat['name']}</a>", $src, $meta); }
	}else{ /*mpre("Элемент не найден и адрес списка не верный");*/ }
}
