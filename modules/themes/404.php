<? die;

$tpl['location'] = strtr("/". implode("/", array_slice(explode("/", urldecode($_SERVER['REQUEST_URI'])), 2)), array("/katalog/"=>"/catalog/"));

if(array_key_exists("null", $_GET) && $_POST['uri']){
	$from = strtr("/". implode("/", array_slice(explode("/", urldecode($_POST['uri'])), 2)), array("/katalog/"=>"/catalog/"));
	$pages_index_id = mpfdk("{$conf['db']['prefix']}pages_index", null, array("name"=>"Новая страница", "text"=>"Текст"));
	$seo_redirect_id = mpfdk("{$conf['db']['prefix']}seo_redirect", $w = array("to"=>"/pages/". (int)$pages_index_id), $w+=array("from"=>$from) , $w);
	exit((string)$pages_index_id);
} header("HTTP/1.0 404 Not Found");
