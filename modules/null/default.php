<?

if(!get($conf, "settings", "canonical") && !array_key_exists("null", $_GET) && array_key_exists('id', $_GET)){ # Нет перезагрузки страницы адреса
	if($default = rb($arg['fn'], "id", $_GET['id'])){
		if($seo_cat = fk("{$conf['db']['prefix']}seo_cat", $w = array("alias"=>"{$arg['modpath']}:{$arg['fn']}"), $w += array("name"=>$conf['modules'][$arg['modpath']]['name']. " » ". (get($conf, 'settings', "{$arg['modpath']}_{$arg['fn']}") ?: $arg['fn']))/*, $w*/)){
			if($seo_cat['href'] && ("/" == substr($seo_cat['href'], -1, 1)) && ("/" == substr($seo_cat['href'], 0, 1))){
				if($settings = mpzam($conf['settings'], "settings")){
					foreach(array_intersect_key($seo_cat, array_flip(array('title', 'description', 'keywords'))) as $k=>$m){
						if($m){ $meta[$k] = strtr(strtr($m, $settings), mpzam($default)); }
					} if($characters_lang = rb("{$conf['db']['prefix']}seo_characters_lang", "name", $w = "[". ((strpos($_SERVER['HTTP_HOST'], "xn--") === 0) ? "Русские" : "Английские"). "]")){
						if($characters = array_column(rb("{$conf['db']['prefix']}seo_characters", "characters_lang_id", "id", array_flip([$characters_lang['id'],0])), "to", "from")){
								if($src = strtr(strtr($seo_cat['href'], $settings), $characters). strtr(htmlspecialchars_decode(strtr($default['name'], $settings)), $characters)){
									if($meta && ($meta = meta(array(urldecode($_SERVER['REQUEST_URI']), $src), $meta += array("cat_id"=>$seo_cat['id'])))){
										exit(header("Location: {$meta[0]}"));
									}else{ mpre("Мета информация не установлена"); }
								}else{ mpre("Ошибка формирования адреса страницы"); }
						}else{ mpre("Не установлена таблица перекодировки seo_characters"); }
					}else{ mpre("Таблица языка перекодировки не найдена <a href='/seo:admin/r:mp_seo_characters_lang'>{$w}</a>"); }
				}else{ mpre("Ошибка формирования системных переменных"); }
			}else{ mpre("Не верный формат seo адреса <a href='/seo:admin/r:{$conf['db']['prefix']}seo_cat?&where[id]={$seo_cat['id']}'>{$seo_cat['name']}</a>"); }
		}else{ mpre("Не найдена категория переадресации"); }
	}else{ mpre("Не найдена информация об объекте"); }
}else{ /*mpre(get($conf, "settings", "canonical"));*/ }
