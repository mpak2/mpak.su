<? die;

if(!empty($_REQUEST['class']) && $class = explode(" ", $_REQUEST['class'])){ # klesh запросы
	if($arg['access'] > 1){
		if($class[1] == "debug") mpre($_REQUEST);
		if(($_REQUEST['id'] < 0) && ($arg['access'] > 2)){
			mpqw($sql = "DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_{$class[0]} WHERE 1". ($arg['access'] > 3 ? " AND uid=". $conf['user']['uid'] : ""). " AND id=". (int)($_REQUEST['id']*-1)); die;
		}else if(array_key_exists("val", $_REQUEST) && ($arg['access'] > 2)){
			$klesh_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_{$class[0]}", $w = array("id"=>$_POST["id"]), $w += array("{$class[1]}"=>$_POST['val'])+$_REQUEST, $w); die((string)$klesh_id);
		}else if(($arg['access'] > 2) || empty($_REQUEST['id'])){
			$class_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_{$class[0]}", array("id"=>$_REQUEST['id']), $_POST, $_POST);
			if($_FILES) foreach($_FILES as $f=>$v){
				$file_id = mpfid("{$conf['db']['prefix']}{$arg['modpath']}_{$class[0]}", $f, $class_id);
			} die((string)$class_id);
		} echo "Прав данных не хватает для правки";
	}else{
		echo "Недостаточно прав доступа";
	}
}
