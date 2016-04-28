<?

$tpl['link'] = "pages:index";
$tpl['location'] = strtr("/". implode("/", array_slice(explode("/", urldecode($_SERVER['REQUEST_URI'])), 2)), array("/katalog/"=>"/catalog/"));

if(array_key_exists("null", $_GET) && get($_POST, 'uri')){
	if($pages = fk("{$conf['db']['prefix']}". strtr($tpl['link'], array(":"=>"_")), null, array("name"=>"Новая страница", "text"=>"Текст"))){
		if($index = urldecode($_POST['uri'])){
			if($meta = meta(array("/{$tpl['link']}/{$pages['id']}", $index), array('title'=>''))){
				exit($pages['id']);
			}else{ mpre("Ошибка установки переадресации страницы /{$tpl['link']}/{$pages_index['id']}"); }
		}else{ mpre("Не адрес не установлен"); }
	}else{ mpre("Ошибка создания страницы"); }
}

$conf['settings']['title'] = "404. Страница не найдена";
header("HTTP/1.0 404 Not Found");
