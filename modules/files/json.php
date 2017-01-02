<?

if(!empty($_REQUEST['class']) && $class = explode(" ", $_REQUEST['class'])){ # klesh запросы
	if($arg['admin_access'] >= 3){
		if(array_key_exists("debug", $_GET)){
		}else if($class[0] == "del"){
			mpqw($sql = "DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_{$class[1]} WHERE id=". (int)$_REQUEST['id']); die($_REQUEST['id']);
		}else if(array_key_exists("val", $_REQUEST)){
			$klesh_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_{$class[1]}", $w = array("id"=>$_POST["id"]), $w += array("{$class[2]}"=>$_POST['val'])+$_REQUEST, $w); die((string)$klesh_id);
		}else{
			$class_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_{$class[1]}", array("id"=>$_REQUEST['id']), $_POST, $_POST);
			if($_FILES) foreach($_FILES as $f=>$v){
				$file_id = mpfid("{$conf['db']['prefix']}{$arg['modpath']}_{$class[1]}", $f, $class_id);
			} die((string)$class_id);
		}
	}else{
		echo "Недостаточно прав доступа";
	}
}
