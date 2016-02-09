<?

if($_POST){
	if(($name = get($_POST, 'name')) && ($pass = get($_POST, 'pass')) && (get($_POST, 'pass') == get($_POST, 'pass2'))){
		if(count(ql("SELECT name FROM {$conf['db']['prefix']}users WHERE type_id=1 AND name='". mpquot($name). "'"))){
			exit("Пользователь уже зарегистрирован");
		}else{
			if(($users = fk($tn = "{$conf['db']['prefix']}users", $w = array("name"=>$name), $w += array("type_id"=>1, "pass"=>mphash($name, $pass), "reg_time"=>time(), "last_time"=>time(), "email"=>get($_POST, 'email'), "ref"=>get($conf, 'user', 'sess', 'ref'), "refer"=>get($conf, 'user', 'sess', 'refer'))))){
				qw("UPDATE {$conf['db']['prefix']}sess SET uid=". $users['id']. " WHERE id=". get($conf, 'user', 'sess', 'id'));
				mpevent("Регистрация нового пользователя", $name, $users['id'], $_POST);

				if($grp = rb("grp", "name", "[". get($conf, 'settings', 'user_grp'). "]")){
					$mem = fk("mem", $w = array("uid"=>$users['id'], "grp_id"=>$grp['id']), $w);
				} exit(json_encode($users));
			}
		}
	}else{ exit("Не указан логи доступа или пароли не совпадают"); }
}
