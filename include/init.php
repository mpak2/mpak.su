<?

if($conf['settings']['theme'] != "vk"){// mpre("Код только для контакта");
}elseif(!array_key_exists('hash', $_GET)){// mpre("Хеш вконтакта пуст");
}elseif(!$_REQUEST = $_GET = mpgt(($_SERVER['REQUEST_URI'] = urldecode($_GET['hash'])), $_GET)){ mpre("Ошибка получения параметров запроса");
//}elseif(mpre($_REQUEST)){
}elseif(!$mod = first(array_keys($_REQUEST['m']))){ mpre("Ошибка поиска модуля");
}elseif(!$viewer_id = get($_REQUEST, "viewer_id")){
}elseif(!$viewer = fk($t = "mp-viewer", $w = array("name"=>$viewer_id), $w, $w += array("up"=>time()))){ mpre("Ошибка добавления vk посетителя", $t);
}elseif(!$user_id = get($_REQUEST, "user_id")){ mpre("Рефер не известен");
}elseif($viewer['up']){// mpre("Не первый вход");
}elseif(!$_viewer = rb("mp-viewer", "name", "[{$user_id}]")){ mpre("Ошибка выборки реферера");
}elseif(!$viewer = fk("mp-viewer", array("id"=>$viewer['id']), null, ['viewer_id'=>$_viewer['id']])){ mpre("Ошибка добавления реферера");
}else{
	
//}elseif($viewer['id'] == $conf['user']['sess']['viewer']){ mpre("Пользователь равен себе");
//	$sess = fk("{$conf['db']['prefix']}sess", array("id"=>$conf['user']['sess']['id']), null, array('viewer'=>$viewer['id']));
//	$conf['user']['sess']['viewer'] = $viewer['id'];

//	if(!$viewer['viewer_id'] && !$viewer['up'] && ($user = rb("{$conf['db']['prefix']}{$mod}_viewer", "name", "[{$_REQUEST['user_id']}]")) && ($user['name'] != $viewer['name'])){
//		$viewer = fk("{$conf['db']['prefix']}mp_viewer", array("id"=>$viewer['id']), null, array("viewer_id"=>$user['id']));
//	}
}
