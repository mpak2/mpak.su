<?

if(get($conf, "settings", "themes_index_tags")){ # Добавляем теги к сайту
	if($THEMES_INDEX_TAGS = rb("themes-index_tags", "name", "id", array_flip(explode(".", $themes_index['name'])))){
		foreach($THEMES_INDEX_TAGS as $themes_index_tags){
			mpevent("Установка тега сайта", $themes_index_tags['name']);
			$themes_index_tags_index = fk("themes-index_tags_index", $w = array("index_tags_id"=>$themes_index_tags['id'], "index_id"=>$themes_index['id']), $w, $w);
		}

		if(count($THEMES_INDEX_CAT = rb("themes-index_cat", "id", "id", rb($THEMES_INDEX_TAGS, "index_cat_id"))) == 1){
			if($themes_index_cat = first($THEMES_INDEX_CAT)){
				mpevent("Установка категории сайта", $themes_index_cat['name']);
				$themes_index = fk("themes-index", array("id"=>$themes_index['id']), null, array("index_cat_id"=>$themes_index_cat['id']));
			}else{ pre("Ошибка определения категории хоста"); }
		}else{ pre("С тегами связано более одной категории", $THEMES_INDEX_CAT); }

		if((count($tag = array_filter(array_column($THEMES_INDEX_TAGS, 'href'))) == 1) && ($href = first($tag))){
			mpevent("Добавления адреса главной страницы сайта", $href);
			$themes_index = fk("themes-index", array("id"=>$themes_index['id']), null, array("href"=>$href));
		}

		if((count($tag = array_filter(array_column($THEMES_INDEX_TAGS, 'theme'))) == 1) && ($theme = first($tag))){
			mpevent("Устновка темы", $theme);
			$themes_index = fk("themes-index", array("id"=>$themes_index['id']), null, array("theme"=>$theme));
		}
	}else{ pre("Теги хоста не найдены"); }
} if(array_key_exists("sort", $themes_index) && !$themes_index['sort']){ # Заполним сортировку
	$themes_index = fk("themes-index", array("id"=>$themes_index['id']), null, array("sort"=>$themes_index['id']));
} if(array_key_exists("prime", $themes_index)){ # Добавление простого числа к сайту;
	inc("modules/themes/admin_index_prime.tpl");
} if(array_key_exists("index_theme_id", $themes_index)){ # Устанавливаем тему;
	inc("modules/themes/admin_index_themes.tpl");
}
