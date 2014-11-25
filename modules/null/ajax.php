<? die;

if(!empty($_REQUEST['class']) && $class = "{$conf['db']['prefix']}{$arg['modpath']}_". array_shift(explode(" ", $_REQUEST['class']))){
	if($arg['access'] > 1){
		mpevent("Аякс запрос /{$arg['modpath']}:{$class[0]}", $conf['user']['uid'], $_REQUEST);
		$where = array_diff_key($_GET, array_flip(array("class", "m")));
		$w = array("time"=>time(), "uid"=>$conf['user']['uid']) + array_diff_key($_REQUEST, array("id"=>false));
		if($arg['access'] >= 2){ # Добавление
			if($_POST['id'] < 0){
				qw("DELETE FROM {$class} WHERE ". implode(" AND ", array_map(function($k, $v){
					return "`$k`=". (is_numeric($v) ? (int)$v : "\"". mpquot($v). "\"");
				}, array_keys($where), array_values($where))));
				exit($_POST['id']);
			}else{
				$fdk = fdk($class, $where, $w, $w);
				if($_FILES) foreach($_FILES as $f=>$v){
					$file_id = mpfid($class, $f, $fdk['id']);
				}elseif($_POST[$f = 'img']){
					$file_id = mphid($class, $f, $fdk['id'], $_POST['img']);
				} exit($fdk['id']);
			}
		}else{
			exit("Прав доступа {$arg['access']} недостаточно для изменения данных");
		}
	}else{
		exit("Недостаточно прав доступа");
	}
}
