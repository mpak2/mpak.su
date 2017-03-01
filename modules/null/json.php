<?

if($arg['admin_access'] < 4){ pre("Не достаточно прав доступа");
}elseif(empty($_REQUEST['class'])){ pre("Пустой параметр класса");
}elseif(!$class = explode(" ", $_REQUEST['class'])){ pre("Ошибка формирования имени таблицы");
}elseif(!$w = array_diff_key($_REQUEST, array("m"=>false, "class"=>false, "limit"=>false))){ pre("Ошибка составления массива условий выборки");
}elseif(!$t = "{$conf['db']['prefix']}{$arg['modpath']}_". mpquot($class[0])){ pre("Ошибка составления имени таблицы");
}elseif(!$sql = "SELECT * FROM $t WHERE 1". (mpdbf($t, $w, true) ? " AND ". mpdbf($t, $w, true) : ""). ($_REQUEST['limit'] ? " LIMIT ". (int)$_REQUEST['limit'] : "")){ pre("Ошибка составления запроса к базе данных");
}elseif(!$data = qn($sql)){ pre("Данные в таблице не найдены");
}else{ exit(json_encode($data)); }
