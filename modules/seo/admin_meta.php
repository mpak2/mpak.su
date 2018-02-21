<?

if($canonical){// mpre("Каноническая ссылка не установлена"); # Нет мета или обновление категории больше чем у записи
}elseif(!$alias = "{$arg['modpath']}:{$arg['fn']}". (($keys = array_keys(array_diff_key(array_filter($_GET), array_flip(["m", ""])))) ? "/". implode("/", $keys) : "")){ mpre("Алиас сфоримрован ошибочно");
}elseif(!$alias = seo_alias($canonical)){ mpre("ОШИБКА получения алиаса категории адреса");
}elseif(array_search('', $_GET)){// mpre("Пустые значения в адресе");
}elseif(!$fn_name = (get($conf, 'settings', "{$arg['modpath']}_{$arg['fn']}") ?: ($arg['fn'] == "index" ? "Главная" : $arg['fn']))){ mpre("Ошибка формирования имени страницы");
}elseif(!$mod_name = get($conf, 'modules', $arg['modpath'], 'name')){ mpre("ОШИБКА формировния имени модуля");
}elseif(!$cat_name = "{$mod_name} » {$fn_name}"){ mpre("Ошибка формирования имени категории");
}elseif(!$seo_cat = fk("seo-cat", $w = ["alias"=>$alias], $w += ["name"=>$cat_name], $w)){ mpre("Ошибка добавления категория переадресации", $w);
}elseif($seo_cat['hide'] !== "0"){// mpre("Категория скрыта");
}elseif(!$settings = mpzam($conf['settings'], "settings")){ mpre("Ошибка формирования системных переменных");
}elseif(!$lang = ((strpos($_SERVER['HTTP_HOST'], "xn--") === 0) ? "Русские" : "Английские")){ mpre("Определение языка сайта");
}elseif(!$characters_lang = rb("seo-characters_lang", "name", $w = "[{$lang}]")){ mpre("Не найдены данные перекодировки {$w}");
}elseif(!$CHARACTERS = array_column(rb("seo-characters", "characters_lang_id", "id", "[{$characters_lang['id']},0,NULL]"), "to", "from")){ mpre("Не установлена таблица перекодировки <a href='/seo:admin/r:mp_seo_characters'>seo_characters</a>");
}elseif(!$href = $seo_cat['href']){ mpre("Не задан адрес ссылки <a href='/seo:admin/r:{$conf['db']['prefix']}seo_cat?&where[id]={$seo_cat['id']}'>{$seo_cat['name']}</a>");
}elseif("/" != substr($seo_cat['href'], 0, 1)){ mpre("Формат устанавливаемого адреса должен начинаться со слеша <a href='/seo:admin/r:{$conf['db']['prefix']}seo_cat?&where[id]={$seo_cat['id']}'>{$seo_cat['name']}</a>");
}elseif(!list($modpath, $fn) = each($get['m'])){ mpre("Ошибка получения модуля и имени файла");
}elseif(!is_array($self = (get($get, 'id') ? rb("{$modpath}-{$fn}", "id", $get['id']) : []))){ mpre("Ошибка выборки данных страницы");
}elseif($INDEX = []){ mpre("Ошибка добавления значений самой таблицы к общему списку значений");
}elseif(!is_array($links = call_user_func(function($self) use($arg){// mpre($self);
		if(!is_array($fields = array_filter(array_map(function($key, $val){
				if(substr($key, -3) == "_id"){ return $key; mpre("Связанная таблица внутри раздела");
				}elseif(strpos($key, "-")){ return $key; mpre("Ссылка на внешний элемент");
				}else{// mpre("Поле не распознано `{$key}`");
				}
			}, array_keys($self), $self)))){ mpre("Список полей с ключами не найден");
		}elseif(!is_array($values = array_intersect_key($self, array_flip($fields)))){ mpre("Ошибка получения массив ключ=>значение связанных элементов");
		}elseif(!is_array($values = array_filter($values))){ mpre("Ошибка удаление пустых значений в ключах");
		}elseif(!is_array($keys = array_map(function($field) use($arg){
				if(substr($field, -3) != "_id"){ return $field;
				}elseif(!$fd = "{$arg['modpath']}-". substr($field, 0, -3)){
				}else{ return $fd; }
			}, array_keys($values)))){ mpre("Ошибка конвертации полей из _id в полный формат");
		}elseif(!is_array($links = array_combine($keys, $values))){ mpre("Ошибка конвертированных ключей");
		}else{ return $links; }
	}, $self))){ mpre("Ошибка выборки списка вторичных ключей");
}elseif(!is_array($gets = call_user_func(function(){
		if(!is_array($fields = array_filter(array_map(function($key, $val){
				if(strpos($key, "-")){ return $key; mpre("Ссылка на внешний элемент");
				}else{// mpre("Поле не распознано `{$key}`");
				}
			}, array_keys($_GET), $_GET)))){ mpre("Список полей с ключами указанными в заголовке не найден");
		}elseif(!is_array($values = array_intersect_key($_GET, array_flip($fields)))){ mpre("Ошибка получения массив ключ=>значение связанных элементов");
		}else{ return $values; }
	}))){ mpre("Ошибка добавления занчений списка тегов из адреса");
}elseif(!is_array($tables = $links + $gets)){ mpre("Ошибка создания массива для выборки данных из таблиц");
}elseif(!$INDEX += call_user_func(function($links, $INDEX = []){// mpre($links);
		if(!is_array($_INDEX = ($links ? (array)array_map(function($tab, $id){
				if(!$index = rb($tab, 'id', $id)){ mpre("Ошибка выборки элемента связанной таблицы `{$tab}` {$id}");
				}else{ return $index; }
			}, array_keys($links), $links) : []))){ mpre("Ошибка получения значений ссылок");
		}elseif(!is_array($INDEX += ($_INDEX ? array_combine(array_keys($links), $_INDEX) : []))){ mpre("Ошибка установки ключей значений");
		}else{ return $INDEX; }
	}, $tables)){ mpre("Ошибка получения значений ссылок");
//}elseif(!mpre($INDEX)){
}elseif(!is_array($ZAM = mpzam($self))){// mpre("Ошибка формирования массива замены"); //}elseif(mpre($ZAM)){ # Список заменяемых элементов в адреса

}elseif(!$href = strtr($href, $ZAM)){ mpre("Ошибка замены тегов в адресе");
}elseif(!is_array($ZAM_INDEX = mpzam($INDEX))){ mpre("ОШИБКА замены связанных таблиц");
}elseif(!$href = strtr($href, $ZAM_INDEX)){ mpre("Ошибка замены тегов в адресе");
}elseif(!is_array($ZAM_GET = mpzam($_GET))){ mpre("ОШИБКА замены параметров адресной строки");
}elseif(!$href = strtr($href, $ZAM_GET)){ mpre("Ошибка замены тегов в адресе");

}elseif(!$href = htmlspecialchars_decode(mb_strtolower($href, 'UTF-8'))){ mpre("Строчные символы и мнемоники");
}elseif(!$meta = array_intersect_key($seo_cat, array_flip(['title', 'description', 'keywords']))){ mpre("Мета информация в категории не найдена", $seo_cat);
}elseif(!$meta = call_user_func(function($meta, $temp = "") use($ZAM, $ZAM_INDEX, $ZAM_GET){
		do{ # Выполняем замену до тех пор, пока измененое значение не будет равно значению до изменения
			if(!$meta = array_map(function($text) use($ZAM, $ZAM_INDEX, $ZAM_GET){// mpre($text);
					if(!is_string($text = strtr($text, $ZAM))){ mpre("Ошибка замены тегов в мета информации `{$text}`");
					}elseif(!is_string($text = strtr($text, $ZAM_INDEX))){ mpre("Ошибка замены тегов в мета информации `{$text}`");
					}elseif(!is_string($text = strtr($text, $ZAM_GET))){ mpre("Ошибка замены тегов в мета информации `{$text}`");
					}else{ return $text; }
				}, $meta)){
			}else{// mpre($meta);
			}
		}while(($meta != $temp) && ($temp = $meta));
		return $meta;
	}, $meta)){ mpre("Ошибка замены тегов в мета информации", $meta);
}elseif("/" != substr($href, 0, 1)){ mpre("Первым символом в адресе должен быть правый слеш `{$href}`");
}elseif(preg_match_all("#{(.*):?(.*?)}#", $href. implode("", $meta), $match) && !call_user_func(function($seo_cat) use($conf, $href, $meta, $ZAM, $ZAM_INDEX, $ZAM_GET){
		mpre("В адресе категории <a target=blank href='/seo:admin/r:{$conf['db']['prefix']}seo_cat?&where[id]={$seo_cat['id']}'>{$seo_cat['name']}</a> и метаинформации заменены не все теги", $href, $meta);
		mpre("Доступны для использования теги страницы", $ZAM, "Связанных таблиц", $ZAM_INDEX, "Параметры адресной строки", $ZAM_GET);
	}, $seo_cat)){ mpre("Прерывание формирование адреса");
}elseif(!$location = meta(array(urldecode($_SERVER['REQUEST_URI']), $href = preg_replace('|\s+|', '', strtr($href, $CHARACTERS))), $meta += array("cat_id"=>$seo_cat['id']))){ mpre("Ошибка установки мета информации", $seo_cat);
}else{ mpre("Установлен новый адрес <a href='{$location[1]}'>{$location[1]}</a>", $meta);
}
