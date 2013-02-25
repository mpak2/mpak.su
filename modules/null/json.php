<? die;

if(!empty($_REQUEST['class']) && $class = explode(" ", $_REQUEST['class'])){ # klesh запросы
	if($arg['access'] >= 1){
		$json = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_". mpquot($class[0]). ($_REQUEST['id'] ? " WHERE id=". (int)$_REQUEST['id'] : " LIMIT 10"));
		exit(json_encode($json));
	}else{ exit("Не достаточно прав доступа"); }
}else{ exit("Параметры запроса заданы не верно"); }