<? die;

//if($viewer = rb("viewer", "id", $conf['user']['sess']['vk_viewer'])){
	if(!empty($_REQUEST['class']) && $class = "{$conf['db']['prefix']}{$arg['modpath']}_". array_shift(explode(" ", $_REQUEST['class']))){
		if($arg['access'] > 1){
			mpevent("Аякс запрос /{$arg['modpath']}:{$class[0]}", $conf['user']['uid'], $_REQUEST, $viewer);
			$where = array_diff_key($_GET, array_flip(array("class", "m")));
			$w = array("viewer_id"=>($_POST['viewer_id'] ?: $viewer['id'])) + array_diff_key($_REQUEST, array("id"=>false, "time"=>false));
			if($arg['access'] >= 2){
				if($_POST['id'] < 0){
					qw("DELETE FROM {$class} WHERE ". implode(" AND ", array_map(function($k, $v){
						return "`$k`=". (is_numeric($v) ? (int)$v : "\"". mpquot($v). "\"");
					}, array_keys($where), array_values($where))));
					exit("{}");
				}else{
					if($fdk = fdk($class, $where, $w = ($_POST ? $w : null), $w)){
						if(array_key_exists("sort", $fdk) && ($fdk['sort'] == 0)){
							$fdk = fdk($class, array("id"=>$fdk['id']), null, array("sort"=>$fdk['id']));
						}
					} if($_FILES) foreach($_FILES as $f=>$v){
						$file_id = mpfid($class, $f, $fdk['id']);
					}elseif($_POST[$f = 'img']){
						$file_id = mphid($class, $f, $fdk['id'], $_POST['img']);
					} exit(json_encode($fdk));
				}
			}else{ exit("Прав доступа {$arg['access']} недостаточно для изменения данных"); }
		}else{ exit("Недостаточно прав доступа"); }
	}else{ exit("Не указана таблица"); }
//}else{ exit(pre("Посетитель не найден")); }
