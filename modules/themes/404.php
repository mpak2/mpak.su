<?

$tpl['location'] = strtr("/". implode("/", array_slice(explode("/", urldecode($_SERVER['REQUEST_URI'])), 2)), array("/katalog/"=>"/catalog/"));

if(array_key_exists("null", $_GET) && $_POST['uri']){
	if($pages_index = fk("{$conf['db']['prefix']}pages_index", null, array("name"=>"Новая страница", "text"=>"Текст"))){
		if($index = implode("/", array_slice(explode("/", urldecode($_POST['uri'])), 2))){
			if($meta = meta(array("/{$index}", "/pages/{$pages_index['id']}"), array('title'=>''))){
				exit($pages_index['id']);
			}else{ mpre("Ошибка установки переадресации страницы /pages/{$pages_index['id']}"); }
		}else{ mpre("Не адрес не установлен"); }
	}else{ mpre("Ошибка создания страницы"); }
} header("HTTP/1.0 404 Not Found");
