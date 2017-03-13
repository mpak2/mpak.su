<?

if($conf['settings']['theme'] != "vk"){// mpre("Код только для контакта");
}elseif(!array_key_exists('hash', $_GET)){// mpre("Хеш вконтакта пуст");
}elseif(!$_REQUEST = $_GET = mpgt(($_SERVER['REQUEST_URI'] = urldecode($_GET['hash'])), $_GET)){ mpre("Ошибка получения параметров запроса");
}elseif(!$mod = first(array_keys($_REQUEST['m']))){ mpre("Ошибка поиска модуля");
}elseif(!get($_GET, "{$mod}_viewer")){
}elseif(!$viewer = fk("{$mod}-viewer", $w = array("name"=>$_REQUEST['viewer_id']), $w, array("up"=>time()))){ mpre("Ошибка добавления vk посетителя");
}elseif($viewer['id'] == $conf['user']['sess']['viewer']){ mpre("Пользователь равен себе");
	$sess = fk("{$conf['db']['prefix']}sess", array("id"=>$conf['user']['sess']['id']), null, array('viewer'=>$viewer['id']));
	$conf['user']['sess']['viewer'] = $viewer['id'];

	if(!$viewer['viewer_id'] && !$viewer['up'] && ($user = rb("{$conf['db']['prefix']}{$mod}_viewer", "name", "[{$_REQUEST['user_id']}]")) && ($user['name'] != $viewer['name'])){
		$viewer = fk("{$conf['db']['prefix']}{$mod}_viewer", array("id"=>$viewer['id']), null, array("viewer_id"=>$user['id']));
	}
}
