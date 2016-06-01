<?

if(get($conf, "settings", "themes_index_tags")){ # Добавляем теги к сайту
	if($tpl["themes-index_tags"] = rb("themes-index_tags", "name", "id", array_flip(explode(".", $themes_index['name'])))){
		foreach($tpl["themes-index_tags"] as $themes_index_tags){
			$themes_index_tags_index = fk("{$conf['db']['prefix']}themes_index_tags_index", $w = array("index_tags_id"=>$themes_index_tags['id'], "index_id"=>$themes_index['id']), $w, $w);
		} if(count($tpl["themes_index_cat"] = rb("themes-index_cat", "id", "id", rb($tpl["themes-index_tags"], "index_cat_id"))) == 1){
			if($themes_index_cat = first($tpl["themes_index_cat"])){
				$themes_index = fk("{$conf['db']['prefix']}themes_index", array("id"=>$themes_index['id']), null, array("index_cat_id"=>$themes_index_cat['id']));
			}else{ pre("Ошибка определения категории хоста"); }
		}else{ pre("С тегами связано более одной категории", $tpl["themes_index_cat"]); }
	}else{ pre("Теги хоста не найдены"); }
} if(array_key_exists("sort", $themes_index) && !$themes_index['sort']){ # Заполним сортировку
	$themes_index = fk("{$conf['db']['prefix']}themes_index", array("id"=>$themes_index['id']), null, array("sort"=>$themes_index['id']));
} if(array_key_exists("prime", $themes_index)){ # Добавление простого числа к сайту;
	inc("modules/themes/admin_index_prime.tpl");
}
