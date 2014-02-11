<? die;

if(!empty($_REQUEST['class']) && $class = explode(" ", $_REQUEST['class'])){ # klesh запросы
	if($arg['access'] >= 4){
		$w = array_diff_key($_REQUEST, array("m"=>false, "class"=>false, "limit"=>false));
		$t = "{$conf['db']['prefix']}{$arg['modpath']}_". mpquot($class[0]);
		$json = qn($sql = "SELECT * FROM $t WHERE 1".
			(mpdbf($t, $w, true) ? " AND ". mpdbf($t, $w, true) : "").
			($_REQUEST['limit'] ? " LIMIT ". (int)$_REQUEST['limit'] : "")
		); exit(json_encode($json));
	}else{ exit("Не достаточно прав доступа"); }
}else{ exit("Параметры запроса заданы не верно"); }