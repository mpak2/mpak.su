<?

if(!$_POST){//mpre("Не аякс запрос");
}elseif(!$name = mpquot(get($_POST, 'name'))){ die(!pre("Регистрационное имя не указано"));
}elseif(!$pass = get($_POST, 'pass')){ die(!pre("Пароль для регистрации не указан"));
}elseif(get($_POST, 'pass') != get($_POST, 'pass2')){ die(!pre("Пароли не совпадают"));
}elseif($users = rb("{$conf['db']['prefix']}users","name","[{$name}]")){ die(!pre("Пользователь уже зарегистрирован"));
}elseif(!$sess = get($conf, 'user', 'sess')){ die(!pre("Ошибка полученя сессии текущего пользователя"));
}elseif(!$mphash = mphash($name, $pass)){ die(!pre("Ошибка генерации пароля"));
}elseif(!$users = fk("{$conf['db']['prefix']}users", $w = array("name"=>$name), $w += array("type_id"=>1, "pass"=>$mphash, "reg_time"=>time(), "last_time"=>time(), "email"=>get($_POST, 'email'), "ref"=>get($conf, 'user', 'sess', 'ref'), "refer"=>get($conf, 'user', 'sess', 'refer')))){ die(!pre("Ошибка регистрации пользователя"));
}elseif(!$grp = get($conf, 'settings', 'user_grp')){ die(!pre("Ошибка определения пользовательской группы"));
}elseif(!$users_grp = rb("users-grp", "name",$w = "[{$grp}]")){ die(!pre("Ошибка выборки группы {$w}"));
}elseif(!$users_mem = fk("users-mem", $w = ["uid"=>$users['id'], "grp_id"=>$users_grp['id']], $w)){ die(!pre("Ошибка добавления пользователя `{$users["name"]}` в группу '{$users_grp["name"]}'"));
//}elseif(!$sess = fk("{$conf['db']['prefix']}sess", ["id"=>$sess["id"]], null, ['uid'=>$users["id"]])){ die(!pre("Ошибка обновления сессии пользователя", $sess));
}else{// pre($sess);
	mpevent("Регистрация нового пользователя", $name, $users['id'], $_POST);
	exit(json_encode($users));
}
