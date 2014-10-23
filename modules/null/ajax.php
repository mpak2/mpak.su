<? die;

if(!empty($_REQUEST['class']) && $class = "{$conf['db']['prefix']}{$arg['modpath']}_". array_shift(explode(" ", $_REQUEST['class']))){
	if($arg['access'] > 1){
		mpevent("Аякс запрос /{$arg['modpath']}:{$class[0]}", $conf['user']['uid'], $_REQUEST);
		$where = array_diff_key($_GET, array_flip(array("class", "m")));
		$w = array("time"=>time(), "uid"=>$conf['user']['uid']) + $_REQUEST;
		if($arg['access'] >= 2){ # Добавление
			$fdk = fdk($class, $where, ($arg['access'] >= 3 ? $w : null), $w);
			if($f = array_shift($fdk)){
				if($_FILES && (count($fdk) == 1)) foreach($_FILES as $f=>$v){
					$file_id = mpfid("{$conf['db']['prefix']}{$arg['modpath']}_{$class[0]}", $f, $class_id);
				} exit($f['id']);
			}
		}else {
			exit("Прав доступа {$arg['access']} недостаточно для изменения данных");
		}
	}else{
		exit("Недостаточно прав доступа");
	}
}
