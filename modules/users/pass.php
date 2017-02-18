<?

if($conf['user']['uid'] > 0){
	if((mphash($conf['user']['uname'], $_POST['old']) == $conf['user']['pass']) || ($arg['admin_access'] >= 5) || $conf['user']['flush']){
		$uid = ($_GET['id'] && ($arg['admin_access'] >= 5) ? $_GET['id'] : $conf['user']['uid']);
		if($_POST['new'] && $_POST['new'] == $_POST['ret']){
			if(strlen($_POST['new'])){
				$user = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id=". (int)$uid), 0);
				mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET pass=\"". mpquot(mphash($user['name'], $_POST['new'])). "\", flush=0 WHERE id=". (int)$user['id']);
				if(mysql_affected_rows() == 1){
					echo "Пароль изменен";
				}else{
					echo "Ошибка сохранения";
				}
			}
		}elseif(!$_POST['flush']){
			echo "Пароли не совпадают";
		}
		if($_POST['flush']){
			mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET flush=1 WHERE id=". (int)$uid);
			echo "<br />Просьба сохранена";
		}
	}else{
		echo "Не верный пароль";
	}
}else{
	echo "Ошибка доступа";
}

?>
