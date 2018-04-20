<?

if(!get($_REQUEST, 'class')){ exit(mpre("Класс не задан"));
}elseif(!$class = "{$conf['db']['prefix']}{$arg['modpath']}_". ($t = first(explode(" ", $_REQUEST['class'])))){ exit(mpre("Таблица не указана"));
}elseif($arg['admin_access'] <= 1){ exit(mpre("Недостаточно прав доступа к разделу"));
}elseif((!$where = array_diff_key($_GET, array_flip(array("class", "m", "null")))) &0){ exit(mpre("Ошибка установки условий"));
}elseif(!$where += ['uid'=>$conf['user']['uid'], 'sid'=>get($conf, 'user', 'sess', 'id')]){ mpre("ОШИБКА добавления параметров выборки");
}elseif((!$w = array_diff_key($_REQUEST, array("id"=>false))) &0){ mpre("Ошибка определения массива изменений");
}elseif(get($_POST, 'id') < 0){// die(mpre("Удаление"));
	if($arg['admin_access'] < 2){ die("Прав недостаточно для изменений");
	}elseif(!$sql = "DELETE FROM {$class} WHERE ". implode(" AND ", array_map(function($k, $v){
			return "`$k`=". (is_numeric($v) ? (int)$v : "\"". mpquot($v). "\"");
		}, array_keys($where), array_values($where)))){ die("Ошибка составления запроса к БД");
	}elseif(!$response = rb($class, "id", $_GET['id'])){ die("Ошибка выборки удаляемого элемента");
	}elseif(qw($sql)){ die("Запрос к БД вернул ошибку");
	}else{ exit(json_encode($response)); }
}elseif(!call_user_func(function($class){// mpre($fdk);
		if($class != "mp_pages_voting_voice"){ return true;
		}elseif(!$email = get($_REQUEST, 'email')){ pre("Адрес почты для отправки подтверждения не указан", $_REQUEST);
		}elseif(!$pages_voting = rb($class, 'email', "[{$email}]")){ return true; pre("ОШИБКА выборки голоса `{$email}`");
		}else{// mpre($pages_voting);
			die("Мы рады что вы снова с нами, но вы уже голосовали на нашем сайте.");
		}
	}, $class)){ die(pre("ОШИБКА отправки сообщения для проверки электронной почты"));
}elseif(!$fdk = fdk($class, $where, ['time'=>time()] + ($w = ($where + ($_POST ? $w : []))), $w)){ exit(!pre("Ошибка запроса к БД", $class, $where, $w));
}elseif(array_key_exists("sort", $fdk) && ($fdk['sort'] == 0) && (!$fdk = fdk($class, array("id"=>$fdk['id']), null, array("sort"=>$fdk['id'])))){ mpre("Ошибка установки значения сортировки");
}elseif($_FILES && !call_user_func(function($FILES){
		foreach($FILES as $f=>$v){
			if(!$file_id = mpfid($class, $f, $fdk['id'])){ mpre("Ошибка загрузки изображения");
				return false;
			}
		} return true;
	}, $_FILES)){ mpre("Ошибка загрузки изображений");
}elseif(($src = get($_POST, 'img')) && !$file_id = mphid($class, $f, $fdk['id'], $img)){ mpre("Ошибка загрузки внешнего изображений");
}else{ exit(json_encode($fdk)); }
