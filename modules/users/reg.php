<?

if(!$_POST){//mpre("Не аякс запрос");
}elseif(!$name = mpquot(get($_POST, 'name'))){ mpre("Регистрационное имя не указано");
}elseif(!$pass = get($_POST, 'pass')){ mpre("Пароль для регистрации не указан");
}elseif(get($_POST, 'pass') != get($_POST, 'pass2')){ mpre("Пароли не совпадают");
}elseif($users = rb("{$conf['db']['prefix']}users","name","[{$name}]")){ mpre("Пользователь уже зарегистрирован");
}elseif(!$sess = get($conf, 'user', 'sess')){ mpre("Ошибка полученя сессии текущего пользователя");
}elseif(!$mphash = mphash($name, $pass)){ mpre("Ошибка генерации пароля");
}elseif(!$users = fk("{$conf['db']['prefix']}users", $w = array("name"=>$name), $w += array("type_id"=>1, "pass"=>$mphash, "reg_time"=>time(), "last_time"=>time(), "email"=>get($_POST, 'email'), "ref"=>get($conf, 'user', 'sess', 'ref'), "refer"=>get($conf, 'user', 'sess', 'refer')))){ mpre("Ошибка регистрации пользователя");
}elseif(!$grp = get($conf, 'settings', 'user_grp')){ mpre("Ошибка определения пользовательской группы");
}elseif(!$users_grp = rb("users-grp", "name",$w = "[{$grp}]")){ mpre("Ошибка выборки группы {$w}");
}elseif(!$users_mem = fk("users-mem", $w = ["uid"=>$users['id'], "grp_id"=>$users_grp['id']], $w)){ mpre("Ошибка добавления пользователя `{$users["name"]}` в группу '{$users_grp["name"]}'");
}elseif(!$sess = fk("{$conf['db']['prefix']}sess", ["id"=>$sess["id"]], null, ['uid'=>$users["id"]])){ mpre("Ошибка обновления сессии пользователя");
}else{ mpevent("Регистрация нового пользователя", $name, $users['id'], $_POST);
	exit(json_encode($users));
}
