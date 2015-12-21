<?

if(array_key_exists("null", $_GET) && ($folder = $_POST['install'])){
	if($f = mpopendir("modules/{$folder}/info.php")){
		include $f;
		$modules_index = fk("{$conf['db']['prefix']}modules", $w = array("folder"=>$folder), $w += $conf['modversion'], $w);
		$arg['modpath'] = $folder;
		include mpopendir("modules/{$folder}/init.php");
		include mpopendir("modules/{$folder}/sql.php");
		exit($modules_index);
	}else{ exit("Установочный файл не найден"); }
}
