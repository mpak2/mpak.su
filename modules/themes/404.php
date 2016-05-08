<?


$tpl['link'] = "pages:index";
$tpl['location'] = strtr("/". implode("/", array_slice(explode("/", urldecode($_SERVER['REQUEST_URI'])), 2)), array("/katalog/"=>"/catalog/"));

if(array_key_exists("null", $_GET) && get($_POST, 'uri')){
//	pre($_POST, $_GET, "{$conf['db']['prefix']}". strtr($tpl['link'], array(":"=>"_")));
	if($pages = fk("{$conf['db']['prefix']}". strtr($tpl['link'], array(":"=>"_")), null, array("name"=>"Новая страница", "text"=>"Текст"))){
		if($index = urldecode($_POST['uri'])){
			if($meta = meta(array("/{$tpl['link']}/{$pages['id']}", $index), array('title'=>''))){
				exit(json_encode($pages));
			}else{ exit("Ошибка установки переадресации страницы /{$tpl['link']}/{$pages['id']}"); }
		}else{ exit("Не адрес не установлен"); }
	}else{ exit("Ошибка создания страницы"); }
}

$conf['settings']['title'] = "404. Страница не найдена";
header("HTTP/1.0 404 Not Found");
