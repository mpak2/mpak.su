<?

$_GET['null'] = header("Content-Type: text/plain; charset=utf-8");

if(!($SEO_ROBOTS = call_user_func(function() use($conf, $arg){
	if(get($conf, 'settings', 'seo_robots')){
		return rb('robots');
	}else{ return []; }})) &0){ mpre("Список сайтов для установки правил не найден");
}elseif(!($SEO_ROBOTS_AGENT = call_user_func(function() use($conf, $arg){
	if(get($conf, 'settings', 'seo_robots_agent')){
		return rb('robots_agent');
	}else{ return []; }})) &0){ mpre("Список агентов не задан в админке");
}elseif(!($SEO_ROBOTS_DISALLOW = call_user_func(function() use($conf, $arg){
	if(get($conf, 'settings', 'seo_robots_disallow')){
		return rb('robots_disallow');
	}else{ return []; }})) &0){ mpre("Список запрещенных адресов не задан");
}else{ /*mpre($SEO_ROBOTS, $SEO_ROBOTS_AGENT, $SEO_ROBOTS_DISALLOW);*/ }

foreach(['*'=>['name'=>'*'], 'Yandex'=>['name'=>'Yandex']] + rb($SEO_ROBOTS_AGENT, "name") as $seo_robots_agent){// mpre($seo_robots_agent);
	echo "User-agent: {$seo_robots_agent['name']}\n";
	if(get($conf, 'themes', 'index', 'hide')){
		echo "\nDisallow: /";
	}else{
		if(($themes_index = $conf['user']['sess']['themes_index']) && get($themes_index, 'hide')){
			echo "\nDisallow: /";
		}
		foreach(rb($SEO_ROBOTS_DISALLOW, "robots_id", "robots_agent_id", "id", "[0,NULL,". in(rb($SEO_ROBOTS, 'themes_index', 'id', get($conf, 'themes', 'index', 'id'))). "]", "[0,NULL,". get($seo_robots_agent, 'id'). "]") as $seo_robots_disallow){
			echo "\nDisallow: {$seo_robots_disallow['name']}";
		}
		if($seo_robots_agent['name'] == "Yandex"){
			if(get($themes_index, 'index_id') && ($index = rb("themes-index", "id", $themes_index['index_id']))){
				echo "\nHost: {$index['name']}";
			}else{
				echo "\n\nHost: {$_SERVER['HTTP_HOST']}";
			}
		} echo "\n\nSitemap: http://{$_SERVER['HTTP_HOST']}/sitemap.xml\n\n";
	}
}

