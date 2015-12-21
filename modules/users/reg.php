<?

if($_POST){
	if(strlen($_POST['name']) && strlen($_POST['pass']) && ($_POST['pass'] == $_POST['pass2'])){
		if(count(ql("SELECT name FROM {$conf['db']['prefix']}users WHERE type_id=1 AND name='{$_POST['name']}'"))){
			exit("Пользователь уже зарегистрирован");
		}else{
			if(($users = fk($tn = "{$conf['db']['prefix']}users", $w = array("name"=>$_POST['name']), $w += array("type_id"=>"1", "pass"=>$pass, "reg_time"=>time(), "last_time"=>time(), "email"=>$_POST['email'], "ref"=>$conf['user']['sess']['ref'], "refer"=>$conf['user']['sess']['refer'])))){
				qw("UPDATE {$conf['db']['prefix']}sess SET uid=". $users['id']. " WHERE id=". (int)$conf['user']['sess']['id']);
				mpevent("Регистрация нового пользователя", $_POST['name'], $users['id'], $_POST);

				$gg = mpql(mpqw($sql = "SELECT id FROM {$conf['db']['prefix']}users_grp WHERE name = \"". $conf['settings']['user_grp']. "\""), 0);
				mpqw($sql = "INSERT INTO {$conf['db']['prefix']}users_mem SET uid=".(int)$users['id'].", grp_id=".(int)$gg['id']);
				exit(json_encode($users));
			}
		}
	}else{ exit("Не указан логи доступа или пароли не совпадают"); }
}
