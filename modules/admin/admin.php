<?

if(array_key_exists("null", $_GET)){// mpre("Таблица для записи не указана");$_RETURN = 556;
	if(!$_POST){ mpre("", "Данных для сохарения не задано"); $_RETURN = 556;
	}elseif(!get($_GET, 'r')){ mpre("Таблица сохранения не указана");
	}elseif(!$order = (get($conf, "settings", substr($_GET['r'], strlen($conf['db']['prefix'])). "=>order") ?: "sort")){ mpre("Ошибка формирования сортировки таблицы");
	}elseif(get($_GET, "id") && array_key_exists("id", $_POST) && ($_POST['id'] < 0)){// mpre($_POST); # Удаление элемента
		if(get($conf, 'settings', 'admin_history_log') && !call_user_func(function() use($conf, $arg){
				if(!$admin_history_type = fk("admin-history_type", $w = array("name"=>"Удаление"), $w)){ mpre("Тип записи не найден {$w}");
				}elseif(!$data = rb($_GET['r'], "id", $_GET['id'])){ mpre("Данные для сохранения не определены");
				}elseif(!$admin_history_tables = fk("admin-history_tables", $w = array("name"=>$_GET['r']), $w += array("modpath"=>$arg['modpath'], "fn"=>$arg['fn'], "description"=>get($conf, 'settings', substr($_GET['r'], strlen($conf['db']['prefix'])))), $w)){ mpre("Информация об изменяемой таблице не найдена");
				}elseif(!$admin_history = fk("admin-history", null, array("history_type_id"=>$admin_history_type['id'], "name"=>$_GET['id'], "history_tables_id"=>$admin_history_tables['id'], "data"=>json_encode($data)))){ die(mpre("Ошибка сохранения истории действий в админстранице"));
				}else{ return $data; }
			})){ mpre("Лог файл отключен");
		}elseif(!qw($sql = "DELETE FROM {$_GET['r']} WHERE id=". (int)abs($_GET['id']))){ mpre("Ошибка удаления записи `{$sql}`");$_RETURN = 556;
		}else{ echo(json_encode([]));$_RETURN = 556; }
	}elseif(get($_POST, "inc") && ($inc = rb($_GET['r'], "id", $_POST['inc']))){ # Изменение сортировки вверх
		if(!$list = qn($sql = "SELECT * FROM {$_GET['r']} WHERE ". (mpdbf($_GET['r'], get($_GET, 'where'), true) ?: 1). " ORDER BY ". (get($_GET, 'order') ?: $order). "")){ mpre("Элементы в списке не найдены");
		}elseif(!$keys = array_keys($list)){ mpre("Ошибка формирования ключей массива");
		}elseif(($nn = array_search($_POST['inc'], $keys)) === false){ mpre("Элемент в списке не найден");
		}elseif(!$dec = get($list, $keys[$nn-1])){ mpre("Ошибка запроса заменяемого элемента");
		}else{// mpre($dec, $keys);
			$_inc = fk($_GET['r'], array("id"=>$inc['id']), null, array("sort"=>$dec['sort']));
			$_dec = fk($_GET['r'], array("id"=>$dec['id']), null, array("sort"=>$inc['sort']));
			echo(json_encode(array($_inc['id']=>$_inc, $_dec['id']=>$_dec)));
			$_RETURN = 556;
		}// die();
	}elseif(get($_POST, "dec") && ($dec = rb($_GET['r'], "id", $_POST['dec']))){ # Изменение сортировки вниз
		if(!$list = qn($sql = "SELECT * FROM {$_GET['r']} WHERE ". (($where = get($_GET, 'where')) ? mpdbf($_GET['r'], $where, true) : 1). " ORDER BY ". (get($_GET, 'order') ?: $order). "")){ mpre("Элементы в списке не найдены");
		}elseif(!$keys = array_keys($list)){ mpre("Ошибка формирования ключей массива");
		}elseif(($nn = array_search($_POST['dec'], $keys)) === false){ mpre("Элемент в списке не найден", $_POST['dec'], $keys);
		}elseif(!$inc = get($list, $keys[$nn+1])){ mpre("Ошибка запроса заменяемого элемента");
		}else{// mpre($nn, $inc);
			$_inc = fk($_GET['r'], array("id"=>$inc['id']), null, array("sort"=>$dec['sort']));
			$_dec = fk($_GET['r'], array("id"=>$dec['id']), null, array("sort"=>$inc['sort']));
			echo(json_encode(array($_inc['id']=>$_inc, $_dec['id']=>$_dec)));$_RETURN = 556;
		} die;
	}elseif(get($_POST, "first") && ($inc = rb($_GET['r'], "id", $_POST["first"]))){ # Сортировка списка элементов
		if($dec = ql($sql = "SELECT * FROM {$_GET['r']} WHERE ". (mpdbf($_GET['r'], get($_GET, 'where'), true) ?: 1). " ORDER BY ". (get($_GET, 'order') ?: $order). " LIMIT 1", 0)){
			$_inc = fk($_GET['r'], array("id"=>$inc['id']), null, array("sort"=>$dec['sort']));
			$_dec = fk($_GET['r'], array("id"=>$dec['id']), null, array("sort"=>$inc['sort']));
		}else{ mpre($sql); }/* mpre($_inc, $_dec);*/ echo(json_encode(array($_inc['id']=>$_inc, $_dec['id']=>$_dec)));$_RETURN = 556;
	}elseif(get($_POST, "last") && ($inc = rb($_GET['r'], "id", $_POST["last"]))){ # Сортировка списка элементов
		if($dec = ql($sql = "SELECT * FROM {$_GET['r']} WHERE ". (mpdbf($_GET['r'], get($_GET, 'where'), true) ?: 1). " ORDER BY ". (get($_GET, 'order') ?: $order). " DESC LIMIT 1", 0)){
			$_inc = fk($_GET['r'], array("id"=>$inc['id']), null, array("sort"=>$dec['sort']));
			$_dec = fk($_GET['r'], array("id"=>$dec['id']), null, array("sort"=>$inc['sort']));
		}else{ mpre($sql); }/* mpre($_inc, $_dec);*/ echo(json_encode(array($_inc['id']=>$_inc, $_dec['id']=>$_dec)));$_RETURN = 556;
	}elseif(!array_walk($_POST, function(&$post, $field) use($conf){
			if(!preg_match("#_id$#ui",$field) AND preg_match("#(^|.+_)(time|last_time|reg_time|up|down)(\d+|_.+|$)#ui",$field)){ $post = strtotime($post);
			}elseif(($_GET['r'] == "{$conf['db']['prefix']}users") && ($field == "pass") && $_POST['pass'] && (strlen($_POST['pass']) != 32) && (substr($_POST['pass'], 0, 1) != "!")){ $post = mphash($_POST['name'], $_POST['pass']);
			}elseif("_id" != substr($field, -3)){ return $post; // return $post;
			}elseif(empty($post) && !strlen($post)){ return $post = "NULL";
			}elseif($post === "NULL"){// mpre("Пустое значение от формы");
			}elseif(!$tab = substr($field, 0, -3)){ mpre("ОШИБКА определения связанной таблицы таблицы");
			}elseif(!get($conf, 'settings', 'admin_datalist')){ return $post; # Если не включен режим редактируемых списков
			}elseif(!$TAB = explode("_", $_GET['r'])){ mpre("ОШИБКА парсинга полного адреса текущей таблицы");
			}elseif(!$table = "{$TAB[1]}-$tab"){ mpre("ОШИБКА получения имени связанной таблицы");
			}elseif($index = call_user_func(function($post) use($table, $field){ # Получения записи по значению поля
					if(preg_match("#^(\d+)\.(.*)#", $post, $match)){
						if(!$index = rb($table, "id", "name", $match[1], "[{$match[2]}]")){ mpre("Не уникальная запись не найдена");
						}else{ return $index; }
					}elseif(!$fields = fields($table)){ mpre("ОШИБКА получения списка полей таблицы");
					}elseif(!get($fields, "name")){// mpre("Поле name в таблице не найдено");
					}elseif(!$INDEX = rb($table, "name", "id", "[". mpquot($post). "]")){// mpre("Запись в таблице не найдена");
					}elseif(!is_numeric($count = count($INDEX))){ mpre("ОШИБКА расчета количества элементов");
					}elseif($count > 1){ die("Элементов `{$field}` несколько `{$post}` ({$count}). Укажите номер.");
					}elseif(!$index = last($INDEX)){ mpre("ОШИБКА выборки элемента из списка");
					}else{ return $index; }
				}, $post)){ $post = $index['id'];
			}elseif(is_numeric($post)){// mpre("Ключ связанной таблицы");
			}elseif(!$index = fk($table, $w = ['name'=>$post], $w)){ mpre("ОШИБКА добавления занчения в связанную таблицу");
			}elseif(!array_key_exists('sort', $index)){ $post = $index['id']; // return $index; mpre("Нет поля сортировки у записи");
			}else{// mpre($field, $post);
				$post = $index['id'];
			}
		})){ mpre("ОШИБКА предобработки занчений связанных таблиц");
//	}elseif(mpre($_POST)){
	}else{// die(!mpre($_SERVER['REQUEST_URI'], $_POST)); # Правка записи и добавление новой
		/*if(is_numeric(get($_POST, '_id'))){ //mpre("Идентификатор число");
		}else if(!isset($_POST['_id'])){ //mpre("Идентификатор не найден");
			$_GET['id'] = get($_POST, '_id');
			unset($_POST['_id']);
		}*/
		if(get($_GET, 'id') && !get($_POST, '_id')){// mpre("Редактирование", $_POST);
			if(!get($conf, 'settings', 'admin_history_log')){// mpre("История не включена");
			}elseif(!$admin_history_type = fk("admin-history_type", $w = array("name"=>"Редактирование"), $w)){ mpre("Тип записи не найден {$w}");
			}elseif(!$admin_history_tables = fk("admin-history_tables", $w = array("name"=>$_GET['r']), $w += array("modpath"=>$arg['modpath'], "fn"=>$arg['fn'], "description"=>get($conf, 'settings', substr($_GET['r'], strlen($conf['db']['prefix'])))), $w)){ mpre("Ошибка сохранения названия таблицы");
			}elseif(!$data = rb($_GET['r'], "id", $_GET['id'])){ mpre("Ошибка выборки изменяемой записи");
			}elseif(!$admin_history = fk("admin-history", null, array("history_type_id"=>$admin_history_type['id'], "name"=>$_GET['id'], "history_tables_id"=>$admin_history_tables['id'], "diff"=>json_encode(array_diff_key(array_diff_assoc($_POST, $data), array_flip(["id"]))), "data"=>json_encode($data)))){ mpre("Ошибка сохранения истории");
			}else{ //mpre("История сохранена", $admin_history);
			}

			/*
				https://webhamster.ru/mytetrashare/index/mtb0/14670332485rAaNEteTA
				Я хочу написать в редакторе <script> и чтобы в БД попала запись &lt;script&gt;
				А иначе потом данный скрипт выведется мне на страницу.
			*/
			//array_walk_recursive($_POST, function($val, $key){ $_POST[$key] = "`$key`=". ($val == "NULL" ? "NULL" : "\"". mpquot(htmlspecialchars_decode($val)). "\""); });

			array_walk_recursive($_POST, function($val, $key){ $_POST[$key] = "`$key`=". ($val === "NULL" ? "NULL" : "\"". mpquot($val). "\""); });
			qw($sql = "UPDATE {$_GET['r']} SET ". implode(", ", array_values($_POST)). " WHERE id=". (int)$_GET['id']);
			$el = rb($_GET['r'], "id", $_GET['id']);
		}else{// die(!mpre($_POST));// mpre("Добавление", $_POST);
			if($ar = array_filter($_POST, function($e){ return is_array($e); })){
				array_walk($_POST, function($val, $key){
					if(is_array($val)){
						$_POST[$key] = null;
					}else{						
						/*
							https://webhamster.ru/mytetrashare/index/mtb0/14670332485rAaNEteTA
							Я хочу написать в редакторе <script> и чтобы в БД попала запись &lt;script&gt;
							А иначе потом данный скрипт выведется мне на страницу.
						*/
						//$_POST[$key] = ($val == "NULL" ? "NULL" : "\"". mpquot(htmlspecialchars_decode($val)). "\"");
						$_POST[$key] = ($val == "NULL" ? "NULL" : "\"". mpquot($val). "\"");
					}
				}); $_POST = array_filter($_POST);
				foreach($ar as $a=>$r){
					foreach($r as $v){
						if($post = $_POST + array($a=>$v)){


							qw($sql = "INSERT INTO {$_GET['r']} (`". implode("`, `", array_keys($post)). "`) VALUES (". implode(", ", array_values($post)). ")");
							$_GET['id'] = $conf['db']['conn']->lastInsertId();


						}
					}
				} $el = rb($_GET['r'], "id", $_GET['id']);
			}else{// mpre("Добавление", $_POST);
				$_POST = array_diff_key($_POST, array_flip(['_id']));
				/*
					https://webhamster.ru/mytetrashare/index/mtb0/14670332485rAaNEteTA
					Я хочу написать в редакторе <script> и чтобы в БД попала запись &lt;script&gt;
					А иначе потом данный скрипт выведется мне на страницу.
				*/
				//array_walk_recursive($_POST, function($val, $key){ $_POST[$key] = ($val == "NULL" ? "NULL" : "\"". mpquot(htmlspecialchars_decode($val)). "\""); });
				array_walk_recursive($_POST, function($val, $key){ $_POST[$key] = ($val == "NULL" ? "NULL" : "\"". mpquot($val). "\""); });
				mpqw($sql = "INSERT INTO {$_GET['r']} (`". implode("`, `", array_keys($_POST)). "`) VALUES (". implode(", ", array_values($_POST)). ")", "Добавление записи в таблицу из админстраницы", function($error) use($conf){
					if(($fields = fields($_GET['r'])) && preg_match("#Column '([\w-_]+)' cannot be null#", $error, $match)){
					}elseif($type = $fields[$match[1]]['Type']){ mpre("Тип данных для правки структуры БД не определен", $_GET['r'], $match);
					}else{
							mpre("Правка структуры таблицы", $sql = "ALTER TABLE {$_GET['r']} CHANGE `{$match[1]}` `{$match[1]}` {$type} DEFAULT NULL COMMENT '". mpquot(get($fields, $match[1], 'Comment')). "'", $error, $match, get($fields, $match[1]));
							qw($sql);
					}
				});// mpre($sql);
				$_GET['id'] = $conf['db']['conn']->lastInsertId();
				$el = rb($_GET['r'], "id", $_GET['id']);
			}

			if(!get($conf, 'settings', 'admin_history_log')){// mpre("Логирование записи выключено");
			}elseif(!$admin_history_type = fk("admin-history_type", $w = array("name"=>"Добавление"), $w)){ mpre("Тип записи не найден {$w}");
			}elseif(!$admin_history_tables = fk("admin-history_tables", $w = array("name"=>$_GET['r']), $w += array("modpath"=>$arg['modpath'], "fn"=>$arg['fn'], "description"=>get($conf, 'settings', substr($_GET['r'], strlen($conf['db']['prefix'])))), $w)){ mpre("Ошибка сохранения названия таблицы");
			}elseif(!$admin_history = fk("admin-history", null, array("history_type_id"=>$admin_history_type['id'], "name"=>$el['id'], "history_tables_id"=>$admin_history_tables['id'], "data"=>json_encode($_POST)))){ mpre("Ошибка добавления записи лога");
			}else{ /*mpre("Логирование отработало корректно");*/ }
		}

		//поиск изображений и файлов
		preg_match_all("#(img|file)(\d+|_[^,]+)?#iu", implode(",", array_keys($_FILES)), $matchFiles); //маска
		$exts = array(
			"img"=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp'),
			"file"=>array("*"=>"*")
		);
		

		foreach($matchFiles[0] as $mKey=>$param_name){
			if(!$f = $matchFiles[1][$mKey]){ mpre("Ошибка выборки поля данных");
			}elseif(!$ext = $exts[$f]){ mpre("Ошибка выборки расшерения");
			}elseif(!$file = get($_FILES, $param_name)){ mpre("Ошибка выборки параметров файла");
			}else{// mpre($file);
				foreach($file['error'] as $key=>$error){
					if(!$name = $file['name'][$key]){// exit(!mpre("Имя файла не установлено"));
					}elseif($error = $file['error'][$key]){ mpre("Код ошибки `{$error}` для файла `{$name}` поле `{$param_name}`");
					}elseif(($key > 0) && (!$el = fk($_GET['r'], null, $w = array_diff_key($el, array_flip(array("id", "sort"))), $w))){ mpre("Ошибка добавления новой записи с файла");
					}elseif((array_key_exists("sort", $el) && ($el['sort'] == 0)) && (!$el = fk($_GET['r'], array("id"=>$el['id']), null, array("sort"=>$el['id'])))){ mpre("Ошибка установки значения сортировки");
//					}elseif(!$file_id = mpfid($_GET['r'], $param_name, $el['id'], $key, $ext)){ mpre("Ошибка установки изображения");
					}elseif(!$file_id = fid($_GET['r'], $param_name, $el['id'], $key, $ext)){ mpre("Ошибка установки изображения");
					}else{ //mpre($file);
					}
				}
			}
		}
		
		
		if(array_key_exists("sort", $el) && !$el['sort']){ # Если у нас есть поле сортировки и оно пустое, то назначаем его равным id
			$el = fk($_GET['r'], array("id"=>$el['id']), null, array("sort"=>$el['id']));
		} echo(htmlspecialchars(json_encode($el)));$_RETURN = 556;
	} /*echo("Аварийный выход");*/$_RETURN = 556;
}elseif(!$tpl['href'] = get($_GET, 'go_to_save') ?: call_user_func(function() use($arg){
		if(!$r = get($_GET, 'r')){ return "/"; mpre("Имя таблицы не установлено");
		}elseif(!$base = "/{$arg["modpath"]}:admin/r:{$_GET["r"]}"){ mpre("Основной адрес страницы");
		}elseif(!is_string($pager = (get($_GET, "p") ? "/p:{$_GET["p"]}" : ""))){ mpre("Учитываем страницу на которой находимся в пагинаторе");
		}elseif(!is_array($where = call_user_func(function(){
				if(!$where = get($_GET, 'where')){ return [];
				}elseif(!$WHERE = array_map(function($key, $val){
						return "where[{$key}]={$val}";
					}, array_keys($where), $where)){ mpre("ОШИБКА выборки форматирования условий выборки");
				}else{ return $WHERE; }
			}))){ mpre("Ошибка установки условий выборки таблицы");
		}elseif(!is_string($limit = ($l = get($_GET, "limit")) ? "/limit:{$l}" : "")){ mpre("Условие указания лимитов");
		}elseif(!is_string($split = ($where ? "?" : ""))){ mpre("Расчет разделителя параметров");
		}elseif(!$href = "{$base}{$pager}{$limit}{$split}". implode('&', $where)){ mpre("ОШИБКА составления ссылки");
		}else{// mpre($href);
			return $href;
		}
	})){ mpre("ОШИБКА составления ссылки");
}else if(!$tpl['tables'] = call_user_func(function($tables = []) use($conf, $arg){ // Список табли модуля
		if($conf['db']['type'] == "mysql"){ $tables = array_column(ql("SHOW TABLES WHERE `Tables_in_{$conf['db']['name']}` LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\""), "Tables_in_{$conf['db']['name']}");
		}elseif(!$database_list = mpql(mpqw("PRAGMA database_list"))){ mpre("ОШИБКА получения списка баз данных");
		}elseif(!$TABLES = array_map(function($database, $TABLES = []) use($conf, $arg){
				if(!$sql = "SELECT * FROM {$database['name']}.sqlite_master WHERE type='table' AND name LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\""){ mpre("Запрос на выборку списка баз данных раздела");
				}elseif(!is_array($_TABLES = (qn($sql, "name") ?: []))){ mpre("ОШИБКА формирования массив имен таблиц");
				}elseif(!is_array($TABLES = array_column($_TABLES, "name"))){ mpre("ОШИБКА получения списка таблиц БД `{$database['name']}`");
				}elseif("main" == $database["name"]){ //mpre("Основная база данных");
				}elseif(!is_array($TABLES = array_map(function($table) use($database){ return "{$database["name"]}.{$table}"; }, $TABLES))){ mpre("ОШИБКА добавления имени базы данных к имени таблицы", $database);
				}else{ //mpre("Список таблиц БД", $database, $TABLES);
				} return $TABLES;
			}, $database_list)){ mpre("ОШИБКА получения списка табли баз данных");
		}elseif(!is_array($tables = call_user_func_array('array_merge', $TABLES))){ mpre("ОШИБКА совмещения списка таблиц");
		}elseif(!sort($tables)){ mpre("ОШИБКА сортировки таблиц");
		}else{// mpre($TABLES);
		} return $tables;
	})){ mpre("ОШИБКА получения списка таблиц модуля");
}else if(!$_GET['r'] = call_user_func(function($table) use($conf, $tpl){ // Формирование имени таблицы
		if(!$table){ $table = first($tpl["tables"]);// mpre("Имя таблицы не задано");
		}else if(!strpos($table, "-")){ //mpre("Полный адрес таблицы");
		}else if(!$ex = explode("-", $table)){ mpre("ОШИБКА разбора имени таблицы на части");
		}else{ //mpre("Короткое имя таблицы", $ex);
			$table = $conf['db']['prefix']. first($ex). (($l = last($ex)) ? "_{$l}" : "");
		} return $table;
	}, get($_GET, 'r'))){ mpre("ОШИБКА получения имени таблицы");
}else if(!$tpl['menu'] = call_user_func(function($tables, $menu = []){ // Список меню
		foreach($tables as $key=>$table){
			if(!$short = implode("_", array_slice(explode("_", $table), 0, -1))){ mpre("ОШИБКА получения короткого имени таблицы");
			}else if(($top = array_search($short, $tables)) !== false){ //mpre("Подтаблица", $short);
				$menu[$top][] = $key;
			}else{ $menu[$key] = array(); }
		} return $menu;
	}, $tpl["tables"])){ mpre("ОШИБКА формирования меню");
}else if(array_search($_GET['r'], $tpl['tables']) === false){ mpre("ОШИБКА имя таблицы не найдено в списке таблиц");
}else if(!$tpl['fields'] = fields($_GET['r'])){ mpre("ОШИБКА выборки полей таблицы");
}else{ # Выборка таблицы
		if(get($_GET, 'order')){ # Установка временной сортировки
			$conf['settings'][substr($_GET['r'], strlen($conf['db']['prefix'])). "=>order"] = $_GET['order'];
		}
		$where = array_map(function($v){ return "[{$v}]"; }, get($_GET, 'where') ?: array());
		$tpl['lines'] = call_user_func_array("rb", ($where ? array_merge(array($_GET['r'], (get($_GET, 'limit') ?: 20)), array_keys($where), array("id"), (array)array_values($where)) : array($_GET['r'], (get($_GET, 'limit') ?: 20))));
		$tpl['spisok'] = array(
			'hide' => array(0=>"Видим", 1=>"Скрыт"),
		);
		if(get($_GET, 'edit')){
			$tpl['edit'] = rb($_GET['r'], "id", $_GET['edit']);
		}elseif($settings = (get($conf, 'settings', substr($_GET['r'], strlen($conf['db']['prefix'])). "=>ecounter") /*?: get($conf, 'settings', "{$arg['modpath']}=>ecounter")*/)){
			foreach(explode(",", $settings) as $ecounter){
				if(!$TN = call_user_func(function($ecounter){ # Получение массива раздела и имени таблицы
						if(!$TN = explode("-", $ecounter)){ mpre("Ошибка парсинга имени таблицы");
						}elseif((count($TN) == 1) && (!$TN = explode("_", $ecounter, 2))){ mpre("Ошибка парсинга имени таблицы без разделителя");
						}else{ return $TN; }
					}, $ecounter)){ mpre("Ошибка разбора имени таблицы");
				}elseif(count($TN) > 3){ mpre("Некорректный формат таблицы внешнего счетчика");
				}elseif(!$table = ((count($TN) > 1) ? "{$conf['db']['prefix']}{$TN[0]}_{$TN[1]}" : "{$conf['db']['prefix']}{$ecounter}")){ mpre("Ошибка состалвения имени таблицы внешнего счетчика");
				}elseif(!$FIELDS = fields($table)){ mpre("Ошибка получения полей таблицы внешнего счетчика `{$table}`");
				}elseif(!$fl = call_user_func(function($FIELDS) {
						if(!$current_table = $_GET["r"]){ mpre("Ошибка разбиения теккущей таблицы");
						}elseif(!$sp = explode('_', $current_table, 3)){ mpre ("Ошибка получения слов");
						}elseif(!$fl1 = ($sp[1] . "-" . $sp[2])){ mpre("Ошибка формирования стандартного поля связи");
						}elseif(array_key_exists($fl1, $FIELDS)){ return $fl1; mpre("Поле найдено");
						}elseif(!$fl2 = ($sp[1] . "_" . $sp[2])){ mpre("Ошибка формирования стандартного поля связи");
						}elseif(!array_key_exists($fl2, $FIELDS)){ mpre("Поле для связи в таблице `{$fl1}`, `{$fl2}` не найдено");
						}else{// mpre($sp, $current_table); mpre($_GET, $FIELDS);
							return $fl2;
						}
					}, $FIELDS )){ mpre("");
				}elseif(!$sql = "SELECT `id`, `{$fl}`, COUNT(id) AS cnt FROM `{$table}` WHERE `{$fl}` IN (". in($tpl['lines']). ") GROUP BY `{$fl}`"){ mpre("Ошибка составления запроса расчета счетчика");
				}elseif(!$tpl['ecounter']["__". $ecounter] = qn($sql, $fl)){// mpre("Ошибка выполнения запроса");
				}else{// mpre("Запрос счетчика", $sql, $tpl['ecounter']["__". $ecounter]);
				}
			}
		}
		foreach($tpl['fields'] as $f=>$field){
			if((count($fd = explode("-", $f)) >= 2) && get($conf, 'modules', $fd[0])){
				if(array_search($f, explode(',', get($conf, 'settings', "{$arg['modpath']}_tpl_exceptions"))) === false){
					$tpl['espisok'][$f] = rb($f);
				}
			}else{ /*mpre("Что-то пошло не так...");*/ }
		}
		if($tab = substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))){
			if(($settings_espisok = get($conf, 'settings', "{$arg['modpath']}=>espisok")) || ($settings_espisok = get($conf, 'settings', "{$arg['modpath']}_{$tab}=>espisok"))){
				foreach(explode(",", $settings_espisok) as $espisok){
					$tpl['espisok'][$espisok] = rb("{$conf['db']['prefix']}{$espisok}");
				}
			}
			foreach($tpl['tables'] as $table){
				if(!$table = strpos($table, ".") ? last(explode(".", $table)) : $table){ mpre("ОШИБКА получения имени таблицы");
				}else if(!$ft = substr($table, strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))){ //mpre("ОШИБКА получения короткого имени таблицы");
				}else if($fields = fields($table)){
					if(get($fields, ($t = "{$tab}_id"))){
						$tpl['counter']["_{$ft}"] = array_column(ql("SELECT `{$t}`, COUNT(*) AS cnt FROM `{$conf['db']['prefix']}{$arg['modpath']}". ($ft ? "_{$ft}" : ""). "` WHERE `{$t}` IN (". in($tpl['lines']). ") GROUP BY `{$t}`"), "cnt", $t);
					}
				}else{ mpre("Поля таблицы $table не определены"); }
			}

			$tpl['etitle'] = array("id"=>"Номер", 'time'=>'Время', 'up'=>'Обновление', 'down'=>'Окончание', 'uid'=>'Пользователь', 'count'=>'Количество', 'level'=>'Уровень', 'ref'=>'Источник', 'cat_id'=>'Категория', 'img'=>'Изображение', 'img2'=>'Изображение2', 'img3'=>'Изображение3', 'file'=>'Файл', 'hide'=>'Видим', 'sum'=>'Сумма', 'fam'=>'Фамилия', 'imy'=>'Имя', 'otv'=>'Отвество', 'sort'=>'Сорт', 'name'=>'Название', 'duration'=>'Длительность', 'pass'=>'Пароль', 'reg_time'=>'Время регистрации', 'last_time'=>'Последний вход', 'email'=>'Почта', 'skype'=>'Скайп', 'site'=>'Сайт', 'title'=>'Заголовок', 'sity_id'=>'Город', 'country_id'=>'Страна', 'value'=>'Значение', 'status'=>'Статус', 'addr'=>'Адрес', 'tel'=>'Телефон', 'code'=>'Код', "article"=>"Артикул", 'price'=>'Цена', 'captcha'=>'Защита', 'href'=>'Ссылка', 'keywords'=>'Ключевики', "users_sity"=>'Город', 'log'=>'Лог', 'min'=>'Мин', 'max'=>'Макс', 'own'=>'Владелец', 'period'=>'Период', "from"=>"Откуда", "to"=>"Куда", "percentage"=>"Процент", 'description'=>'Описание', 'text'=>'Текст', 'result'=>"Результат", "place"=>"Место", "num"=>"Номер");
			if($title = get($conf, 'settings', "{$arg['modpath']}_{$tab}=>title")){
				$tpl['title'] = array_merge(array("id"), explode(",", $title));
			}elseif(get($tpl, 'fields', "text")){
				$tpl['title'] = array_keys(array_diff_key($tpl['fields'], array("text"=>false)));
			}
		}elseif($_GET['r'] == "{$conf['db']['prefix']}users"){ # Количество групп в которых состоит человек
			$tpl['counter']["_mem"] = array_column(ql("SELECT `uid`, COUNT(*) AS cnt FROM `{$conf['db']['prefix']}{$arg['modpath']}_mem` WHERE `uid` IN (". in($tpl['lines']). ") GROUP BY `uid`"), "cnt", "uid");
		}
}
