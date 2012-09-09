<? die;

foreach(mpql(mpqw("DESC {$conf['db']['prefix']}{$arg['modpath']}")) as $k=>$v){
	$f[$v['Field']] = $v['Type'];
}// mpre($f);

$conf['tpl']['fields'] = array_intersect_key($f, array_flip(explode(',', $conf['settings']['user_reg_fields'])));// mpre($conf['tpl']['fields']);
foreach($conf['tpl']['fields'] as $k=>$v){
	if(substr($k, -3) == '_id'){
		$conf['tpl'][$k] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($k, 0, strlen($k)-3). " ORDER BY name"));
//		mpre($conf['tpl'][$k]);
	}
}// mpre($conf['tpl']['fields']);

if($conf['settings']['users_reg_page']){
	$conf['tpl']['users_reg_page'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}pages_index WHERE id=". (int)$conf['settings']['users_reg_page']), 0);
}

if (isset($_POST['add']) && strlen($_POST['name']) && strlen($_POST['pass']) && ($_POST['pass'] == $_POST['pass2'])){
	$sql = "SELECT name FROM {$conf['db']['prefix']}users WHERE tid=1 AND name='{$_POST['name']}'";
	if (count(mpql(mpqw($sql)))){
		echo "<p><p><center><font color=red>Такое имя уже зарегистрировано. Выбирите другое</font>";
		echo " <a href='/{$arg['modname']}:{$arg['fe']}'>Вернуться</a></center>";
	}else{
		mpqw($sql = "INSERT INTO ". ($tn = "{$conf['db']['prefix']}users"). " SET tid=1, name=\"". mpquot($_POST['name']). "\", pass=\"". ($pass = mphash($_POST['name'], $_POST['pass'])). "\", reg_time=". time(). ", last_time=". time(). ", email=\"".mpquot($_POST['email'])."\", ref=\"". mpquot($conf['user']['sess']['ref']). "\"". ($conf['user']['sess']['refer'] ? ", refer=". (int)$conf['user']['sess']['refer'] : ''));
		if($tpl['mysql_insert_id'] = mysql_insert_id()){
			if($tpl['users_activation'] = $conf['settings']['users_activation']){ # Для регистрации нужна активация аккаунта
				mpqw("UPDATE {$conf['db']['prefix']}users SET pass=\"". mpquot($conf['settings']['users_activation']. $pass). "\" WHERE id=". (int)$tpl['mysql_insert_id']);
			}else{ # Автоподключение а аккаунту
				mpqw("UPDATE {$conf['db']['prefix']}sess SET uid=". (int)$tpl['mysql_insert_id']. " WHERE id=". (int)$conf['user']['sess']['id']);
			}
			if($mpdbf = mpdbf($tn, array_intersect_key($_POST, $conf['tpl']['fields']))){
				mpqw($sql = "UPDATE $tn SET $mpdbf WHERE id=".(int)$tpl['mysql_insert_id']);
			} mpevent("Регистрация нового пользователя", $_POST['name'], $tpl['mysql_insert_id'], array("activation"=>"/users:activation/uid:{$tpl['mysql_insert_id']}/activation:". mb_substr($pass, 0, 7))+$_POST);
//			mpevent("Регистрация нового пользователя", $_POST['name'], $tpl['mysql_insert_id']);

			$gg = mpql(mpqw($sql = "SELECT id FROM {$conf['db']['prefix']}users_grp WHERE name = \"". $conf['settings']['user_grp']. "\""), 0);// echo $sql;
			mpqw($sql = "INSERT INTO {$conf['db']['prefix']}users_mem SET uid=".(int)$tpl['mysql_insert_id'].", gid=".(int)$gg['id']);

			if($conf['settings']['users_reg_redirect']){ # Перенаправление на страницу с настроек
				header("Location: {$conf['settings']['users_reg_redirect']}");
			}else{
				header("Location: /");
			}$conf['tpl']['reg'] = 1;
		}
	}
}elseif($_POST['add']){
	echo "<div style=\"margin:100px; text-align:center;\">Не указан логин доступа или не совпадают пароли <a href=\"/{$arg['modname']}:{$arg['fe']}\">Вернуться</a></div>";
}

?>