<?

/*if(($url = preg_replace("#\/p\:[0-9]+#", "", $_SERVER['REQUEST_URI'])) && array_key_exists("null", $_GET)){
	# Ресурсы не трогаем
}else*/if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && !array_search("Зарегистрированные", $conf['user']['gid'])){
	exit(header('HTTP/1.0 304 Not Modified'));
}elseif(($conf['settings']['theme'] == "vk") && array_key_exists('hash', $_GET)){
	$_REQUEST = $_GET = mpgt(($_SERVER['REQUEST_URI'] = urldecode($_GET['hash'])), $_GET);
	if($mod = array_shift(array_keys($_REQUEST['m']))){
		if($viewer = fk("{$conf['db']['prefix']}{$mod}_viewer", $w = array("name"=>$_REQUEST['viewer_id']), $w, array("up"=>time()))){
			if($viewer['id'] != $conf['user']['sess']['viewer']){
				$sess = fk("{$conf['db']['prefix']}sess", array("id"=>$conf['user']['sess']['id']), null, array('viewer'=>$viewer['id']));
				$conf['user']['sess']['viewer'] = $viewer['id'];

				if(!$viewer['viewer_id'] && !$viewer['up'] && ($user = rb("{$conf['db']['prefix']}{$mod}_viewer", "name", "[{$_REQUEST['user_id']}]")) && ($user['name'] != $viewer['name'])){
					$viewer = fk("{$conf['db']['prefix']}{$mod}_viewer", array("id"=>$viewer['id']), null, array("viewer_id"=>$user['id']));
				}// pre($sess);
			}else{
//				pre($conf['user']['sess']);
			}
		}
	}
} if(!array_search("Зарегистрированные", $conf['user']['gid'])){ # Исключаем админстраницу из кеширования
	header('Last-Modified: '. date("r"));
	header("Expires: ".gmdate("r", time() + 86400));
}
