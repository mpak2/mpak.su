<?

header("Content-Type: text/txt");

foreach(array("Yandex", "*") as $n=>$r){
	echo "User-agent:{$r}\n";
	if(($themes_index = $conf['user']['sess']['themes_index']) && get($themes_index, 'hide')){
		echo "Disallow: /\n\n";
	}
	echo "Disallow: /*?*\n\n";
	if($r == "Yandex"){
		if(get($themes_index, 'index_id') && ($index = rb("{$conf['db']['prefix']}themes_index", "id", $themes_index['index_id']))){
			echo "Host: {$index['name']}\n\n";
		}else{
			echo "Host: {$_SERVER['HTTP_HOST']}\n\n";
		}
	}
}

echo "Sitemap: http://{$_SERVER['HTTP_HOST']}/sitemap.xml\n";
