<? die;

if($user = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id=". (int)$_GET['uid']), 0)){
	if(strpos($user['pass'], $conf['settings']['users_activation']. $_GET['activation']) === 0){
		mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET pass=\"". mb_substr($user['pass'], strlen($conf['settings']['users_activation']), 999). "\" WHERE id=". (int)$user['id']);
		mpevent("Активация учетной записи", $_SERVER['REQUEST_URI'], $user);
		header("Location: /users/". (int)$user['id']);
	}else{
		mpevent("Не верный код активации", $_SERVER['REQUEST_URI'], $conf['user']['uid']);
		echo "Код активации не правильный";
	}
}else{
	mpevent("При активации учетной записи пользователь не найден", $_SERVER['REQUEST_URI'], $conf['user']['uid']);
	echo "Пользователь не найден";
}

?>