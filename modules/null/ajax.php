<?

if(get($_REQUEST, 'class') && $class = "{$conf['db']['prefix']}{$arg['modpath']}_". ($t = first(explode(" ", $_REQUEST['class'])))){
	if($arg['access'] > 1){
		mpevent("ajax://{$arg['modpath']}:ajax/class:{$t}", $conf['user']['uid'], $_REQUEST);
		$where = array_diff_key($_REQUEST, array_flip(array("class", "m", "null")));
		$w = array("time"=>time()) + array_diff_key($_REQUEST, array("id"=>false));
		if($arg['access'] >= 2){
			if(get($_POST, 'id') < 0){
				qw("DELETE FROM {$class} WHERE ". implode(" AND ", array_map(function($k, $v){
					return "`$k`=". (is_numeric($v) ? (int)$v : "\"". mpquot($v). "\"");
				}, array_keys($where), array_values($where))));
				exit("{}");
			}elseif(!$fdk = fdk($class, $where, $w = ($where + ($_POST ? $w : [])), $w)){ exit(mpre("Ошибка запроса к БД", $class, $where, $w));
			}else{
				if(array_key_exists("sort", $fdk) && ($fdk['sort'] == 0)){
					$fdk = fdk($class, array("id"=>$fdk['id']), null, array("sort"=>$fdk['id']));
				} if($_FILES) foreach($_FILES as $f=>$v){
					$file_id = mpfid($class, $f, $fdk['id']);
				}elseif($img = get($_POST, $f = 'img')){
					$file_id = mphid($class, $f, $fdk['id'], $img);
				} exit(json_encode($fdk));
			}
		}else{
			$error = "Прав доступа {$arg['access']} недостаточно для изменения данных";
			mpevent("Аякс запрос /{$arg['modpath']}:{$class[0]}", $conf['user']['uid'], $error, $_REQUEST);
			exit($error);
		}
	}else{
		$error = "Недостаточно прав доступа";
		mpevent("Аякс запрос /{$arg['modpath']}:{$class[0]}", $conf['user']['uid'], $error, $_REQUEST);
		exit($error);
	}
}else{
	$error = "Не указана таблица";
	mpevent("Аякс запрос /{$arg['modpath']}:{$class[0]}", $conf['user']['uid'], $error, $_REQUEST);
	exit($error);
}
