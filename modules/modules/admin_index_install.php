<?

if(!array_key_exists("null", $_GET)){// mpre("Директория установки не найдена");
}elseif(!$folder = $_POST['install']){ mpre("Раздел не указан");
}elseif(!$f = mpopendir("modules/{$folder}/info.php")){ mpre("Установочная директория не найдена");
	$modules_index = fk("index", $w = array("folder"=>$folder), $w += ["name"=>$folder, 'author'=>$conf['user']['uname'], "access"=>1, "admin"=>3, "hide"=>0]);
	exit($modules_index);
}else{
	include $f;
	$modules_index = fk("index", $w = array("folder"=>$folder), $w += ["hide"=>0, 'modversion'=>$conf['modversion']], $w);
	$arg['modpath'] = $folder;
	inc("modules/{$folder}/init.php", array('arg'=>array('modpath'=>$folder)));
	inc("modules/{$folder}/sql.php", array('arg'=>array('modpath'=>$folder)));
	exit($modules_index);
}
