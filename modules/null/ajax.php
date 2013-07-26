<? die;

if(!empty($_REQUEST['class']) && $class = explode(" ", $_REQUEST['class'])){ # klesh запросы
//mpre($_REQUEST);
//mpre($_REQUEST['id']);
	if($arg['access'] > 1){
		mpevent("Аякс запрос /{$arg['modpath']}:{$class[0]}", $conf['user']['uid'], $_REQUEST);
		if($class[1] == "debug") mpre($_REQUEST);
		if(($_REQUEST['id'] < 0) && ($arg['access'] > 2)){
			mpqw($sql = "DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_{$class[0]} WHERE 1". ($arg['access'] > 3 ? "" : " AND uid=". $conf['user']['uid']). " AND id=". (int)($_REQUEST['id']*-1)); exit($_REQUEST['id']);
		}else if(array_key_exists("val", $_REQUEST) && ($arg['access'] > 2)){
			$klesh_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_{$class[0]}", $w = array("id"=>$_REQUEST["id"]), $w += array("{$class[1]}"=>$_REQUEST['val'])+$_REQUEST, $w); die((string)$klesh_id);
		}else if(!array_key_exists("id", $_REQUEST)){
			if($arg['access'] <= 3){ # Права доступа ниже модератора проверяем поле юзера
				$fileds = qn("SHOW COLUMNS FROM `{$conf['db']['prefix']}{$arg['modpath']}_{$class[0]}`", "Field");
				if(!array_key_exists("uid", $fileds)){ exit("Прав доступа не достаточно для добавления"); }
			} $class_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_{$class[0]}", null, $_REQUEST);
			if($_FILES) foreach($_FILES as $f=>$v){
				$file_id = mpfid("{$conf['db']['prefix']}{$arg['modpath']}_{$class[0]}", $f, $class_id);
			} die((string)$class_id);
		}else if(($arg['access'] >= 2)){
			$where = ($arg['access'] <= 3 ? array("uid"=>$conf['user']['uid']) : array()) + (array_key_exists("id", $_REQUEST) ? array("id"=>$_REQUEST['id']) : $_REQUEST);
			$class_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_{$class[0]}", $where, $_REQUEST, $_REQUEST);
			if($_FILES) foreach($_FILES as $f=>$v){
				$file_id = mpfid("{$conf['db']['prefix']}{$arg['modpath']}_{$class[0]}", $f, $class_id);
			} die((string)$class_id);
		} exit("Прав доступа не достаточно для правки");
	}else{
		exit("Недостаточно прав доступа");
	}
}
