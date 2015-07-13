<? die;

if($_POST['email']){
	if($conf['tpl']['user'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE email=\"". mpquot($_POST['email']). "\""), 0)){
		$cod = substr($conf['tpl']['user']['pass'], 0, 12);
		$uri = "/{$arg['modpath']}:{$arg['fn']}/{$conf['tpl']['user']['id']}/resque:$cod";
		mpmail($conf['tpl']['user']['email'], "Восстановление пароля", "Для продолжения необходимо пройти по ссылке<br /><br /><a href=\"http://{$_SERVER['HTTP_HOST']}{$uri}\">". mpidn($_SERVER['HTTP_HOST']). "{$uri}</a>");
		mpevent("Запрос сообщения для восстановления пароля", $conf['user']['uid'], $uri);
	}
}elseif($_GET['resque'] && $_GET['id']){
	if($user = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id=". (int)$_GET['id']), 0)){
		if($cod = substr($user['pass'], 0, 12)){
			if($cod == $_GET['resque']){
				mpevent("Восстановление пароля", $conf['user']['uid']);
				mpqw("UPDATE {$conf['db']['prefix']}sess SET uid=". (int)$user['id']. " WHERE id=". $conf['user']['sess']['id']);
				$conf['tpl']['resque'] = $cod;
				if($_POST['pass']){
					mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET pass=\"". mpquot(mphash($user['name'], $_POST['pass'])). "\" WHERE id=". (int)$user['id']);
				}
			}
		}
	}
}

?>