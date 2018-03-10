<?

if(!get($_REQUEST, 'class')){ exit(!pre("Класс не задан"));
}elseif(!$class = "{$conf['db']['prefix']}{$arg['modpath']}_". ($t = first(explode(" ", $_REQUEST['class'])))){ exit(!pre("Таблица не указана"));
}elseif($arg['admin_access'] <= 1){ exit(!pre("Недостаточно прав доступа к разделу"));
}elseif((!$where = array_diff_key($_GET, array_flip(array("class", "m", "null")))) &0){ exit(!pre("Ошибка установки условий"));
}elseif((!$w = array_diff_key($_REQUEST, array("id"=>false))) &0){ mpre("Ошибка определения массива изменений");
}elseif(get($_POST, 'id') < 0){ die(mpre("Удаление"));
	if($arg['admin_access'] < 2){ die("Прав недостаточно для изменений");
	}elseif(!$sql = "DELETE FROM {$class} WHERE ". implode(" AND ", array_map(function($k, $v){
			return "`$k`=". (is_numeric($v) ? (int)$v : "\"". mpquot($v). "\"");
		}, array_keys($where), array_values($where)))){ die("Ошибка составления запроса к БД");
	}elseif(!$response = rb($class, "id", $_GET['id'])){ die("Ошибка выборки удаляемого элемента");
	}elseif(qw($sql)){ die("Запрос к БД вернул ошибку");
	}else{ exit(json_encode($response)); }
}elseif(!$fdk = fdk($class, $where, ['time'=>time()] + ($w = ($where + ($_POST ? $w : []))), $w)){ exit(pre("Ошибка запроса к БД", $class, $where, $w));
}elseif(array_key_exists("sort", $fdk) && ($fdk['sort'] == 0) && (!$fdk = fdk($class, array("id"=>$fdk['id']), null, array("sort"=>$fdk['id'])))){ pre("Ошибка установки значения сортировки");
}elseif($_FILES && !call_user_func(function($FILES) use($class, $fdk){// mpre($FILES);
		foreach($FILES as $f=>$v){// mpre($f, $v);
			if(!$file_id = fid($class, $f, $fdk['id'])){ pre("Ошибка загрузки изображения");
				return false;
			}
		} return true;
	}, $_FILES)){ mpre("Ошибка загрузки изображений");
}elseif(($src = get($_POST, 'img')) && !$fdk = fid($class, "img", $fdk['id'], $img)){ mpre("Ошибка загрузки внешнего изображений");
}else{// mpre($_FILES);
	exit(json_encode($fdk));
}
