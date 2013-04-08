<? die;

if(!empty($_REQUEST['class']) && $class = explode(" ", $_REQUEST['class'])){ # klesh запросы
	if($arg['access'] >= 1){
		$w = array_diff_key($_REQUEST, array("m"=>false, "class"=>false));
		$t = "{$conf['db']['prefix']}{$arg['modpath']}_". mpquot($class[0]);
		$json = qn("SELECT * FROM $t WHERE 1". ($w ? " AND ". mpdbf($t, $w, true) : ""). ($_REQUEST['id'] ? " id=". (int)$_REQUEST['id'] : " LIMIT 10"));
		exit(json_encode($json));
	}else{ exit("Не достаточно прав доступа"); }
}else{ exit("Параметры запроса заданы не верно"); }