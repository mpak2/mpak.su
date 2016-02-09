<?

if($conf['settings']['canonical'] == $_SERVER['REQUEST_URI']){
	sleep(1); mpre($conf['settings']['canonical']);
	if($themes_index = $conf['user']['sess']['themes_index']){
//		if($themes_pages_cat = rb("{$conf['db']['prefix']}themes_pages_cat", "id", get($_GET, 'id'))){
			if($zam = mpzam(array(
				'hh_blocks'=>($hh_blocks = rb("{$conf['db']['prefix']}hh_blocks", "id", $themes_index['hh_blocks'])),
				'hh_builders'=>($hh_builders = rb("{$conf['db']['prefix']}hh_builders", "id", $hh_blocks['builders_id'])),
			))){
				if($meta = meta(array($themes_pages_cat['href'], $_SERVER['REQUEST_URI']), $w = array(
					"title"=>strtr($themes_pages_cat['title'], $zam),
					'description'=>strtr($themes_pages_cat['description'], $zam),
					'keywords'=>strtr($themes_pages_cat['keywords'], $zam)
				))){ exit(header("Location: {$meta[0]}")); }
			}
//		}
	}
}
