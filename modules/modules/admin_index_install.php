<?

if(array_key_exists("null", $_GET) && ($folder = $_POST['install'])){
	if($f = mpopendir("modules/{$folder}/info.php")){
		include $f;
		$modules_index = fk("index", $w = array("folder"=>$folder, "hide"=>0), $w += $conf['modversion'], $w);
		$arg['modpath'] = $folder;
		inc("modules/{$folder}/init.php", array('arg'=>array('modpath'=>$folder)));
		inc("modules/{$folder}/sql.php", array('arg'=>array('modpath'=>$folder)));
		exit($modules_index);
	}else{ exit("Установочный файл не найден"); }
}
