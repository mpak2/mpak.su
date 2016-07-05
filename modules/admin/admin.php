<?

if(array_key_exists("null", $_GET) && get($_GET, 'r') && $_POST){ # Управляющие данные
	if(get($_GET, "id") && array_key_exists("id", $_POST) && !$_POST['id']){ # Удаление элемента

		if(get($conf, 'settings', 'admin_history_log')){
//			if($admin_history_type = rb("{$conf['db']['prefix']}admin_history_type", "name", $w = "[Удаление]")){
			if($admin_history_type = fk("{$conf['db']['prefix']}admin_history_type", $w = array("name"=>"Удаление"), $w)){
				if($data = rb($_GET['r'], "id", $_GET['id'])){
					if($admin_history_tables = fk("{$conf['db']['prefix']}admin_history_tables", $w = array("name"=>$_GET['r']), $w += array("modpath"=>$arg['modpath'], "fn"=>$arg['fn'], "description"=>get($conf, 'settings', substr($_GET['r'], strlen($conf['db']['prefix'])))), $w)){
						$admin_history = fk("{$conf['db']['prefix']}admin_history", null, array("history_type_id"=>$admin_history_type['id'], "name"=>$_GET['id'], "history_tables_id"=>$admin_history_tables['id'], "data"=>json_encode($data)));
					}else{ mpre("Информация об изменяемой таблице не найдена"); }
				}else{ mpre("Данные для сохранения не определены"); }
			}else{ mpre("Тип записи не найден {$w}"); }
		} exit(qw("DELETE FROM {$_GET['r']} WHERE id=". (int)$_GET['id']));

	}elseif(get($_POST, "inc") && ($inc = rb($_GET['r'], "id", $_POST['inc']))){ # Правка записи и добавление новой
		if($dec = ql($sql = "SELECT * FROM {$_GET['r']} WHERE sort<". (int)$inc['sort']. " AND ". (mpdbf($_GET['r'], get($_GET, 'where'), true) ?: 1). " ORDER BY ". (get($_GET, 'order') ?: "sort"). " DESC LIMIT 1", 0)){
			$_inc = fk($_GET['r'], array("id"=>$inc['id']), null, array("sort"=>$dec['sort']));
			$_dec = fk($_GET['r'], array("id"=>$dec['id']), null, array("sort"=>$inc['sort']));
		}else{ mpre($sql); }/* mpre($_inc, $_dec);*/ exit(json_encode(array($_inc['id']=>$_inc, $_dec['id']=>$_dec)));
	}elseif(get($_POST, "dec") && ($dec = rb($_GET['r'], "id", $_POST['dec']))){ # Правка записи и добавление новой
		if($inc = ql($sql = "SELECT * FROM {$_GET['r']} WHERE `sort`>". (int)$dec['sort']. " AND ". (($where = get($_GET, 'where')) ? mpdbf($_GET['r'], $where, true) : 1). " ORDER BY ". (get($_GET, 'order') ?: "sort"). " LIMIT 1", 0)){
			$_inc = fk($_GET['r'], array("id"=>$inc['id']), null, array("sort"=>$dec['sort']));
			$_dec = fk($_GET['r'], array("id"=>$dec['id']), null, array("sort"=>$inc['sort']));
		}else{ mpre($sql); } exit(json_encode(array($_inc['id']=>$_inc, $_dec['id']=>$_dec)));
	}else{ # Правка записи и добавление новой
		foreach($_POST as $field=>$post){
			if(array_search($field, array(1=>"time", "last_time", "reg_time", "up"))){
				$_POST[$field] = strtotime($post);
			} if(($_GET['r'] == "{$conf['db']['prefix']}users") && ($field == "pass") && (strlen($_POST['pass']) != 32)){
				$_POST[$field] = mphash($_POST['name'], $_POST['pass']);
			}
		} if(get($_GET, 'id')){

			if(get($conf, 'settings', 'admin_history_log')){
//				if($admin_history_type = rb("{$conf['db']['prefix']}admin_history_type", "name", $w = "[Редактирование]")){
				if($admin_history_type = fk("{$conf['db']['prefix']}admin_history_type", $w = array("name"=>"Редактирование"), $w)){
					if($admin_history_tables = fk("{$conf['db']['prefix']}admin_history_tables", $w = array("name"=>$_GET['r']), $w += array("modpath"=>$arg['modpath'], "fn"=>$arg['fn'], "description"=>get($conf, 'settings', substr($_GET['r'], strlen($conf['db']['prefix'])))), $w)){
						if($data = rb($_GET['r'], "id", $_GET['id'])){
							$admin_history = fk("{$conf['db']['prefix']}admin_history", null, array("history_type_id"=>$admin_history_type['id'], "name"=>$_GET['id'], "history_tables_id"=>$admin_history_tables['id'], "diff"=>json_encode(array_diff_key(array_diff_assoc($_POST, $data), array_flip(["id"]))), "data"=>json_encode($data)));
						}else{ mpre("Ошибка выборки изменяемой записи"); }
					}else{ mpre("Ошибка сохранения названия таблицы"); }
				}else{ mpre("Тип записи не найден {$w}"); }
			}else{ /*mpre("Логирование запросов выключено");*/ }

			array_walk_recursive($_POST, function($val, $key){ $_POST[$key] = "`$key`=". ($val == "NULL" ? "NULL" : "\"". mpquot(htmlspecialchars_decode($val)). "\""); });
			qw($sql = "UPDATE `{$_GET['r']}` SET ". implode(", ", array_values($_POST)). " WHERE id=". (int)$_GET['id']);
			$el = rb($_GET['r'], "id", $_GET['id']);
		}else{
			if($ar = array_filter($_POST, function($e){ return is_array($e); })){
				array_walk($_POST, function($val, $key){
					if(is_array($val)){
						$_POST[$key] = null;
					}else{
						$_POST[$key] = ($val == "NULL" ? "NULL" : "\"". mpquot(htmlspecialchars_decode($val)). "\"");
					}
				}); $_POST = array_filter($_POST);
				foreach($ar as $a=>$r){
					foreach($r as $v){
						if($post = $_POST + array($a=>$v)){


							qw($sql = "INSERT INTO `{$_GET['r']}` (`". implode("`, `", array_keys($post)). "`) VALUES (". implode(", ", array_values($post)). ")");
							$_GET['id'] = $conf['db']['conn']->lastInsertId();


						}
					}
				} $el = rb($_GET['r'], "id", $_GET['id']);
			}else{
				array_walk_recursive($_POST, function($val, $key){ $_POST[$key] = ($val == "NULL" ? "NULL" : "\"". mpquot(htmlspecialchars_decode($val)). "\""); });
				mpqw($sql = "INSERT INTO `{$_GET['r']}` (`". implode("`, `", array_keys($_POST)). "`) VALUES (". implode(", ", array_values($_POST)). ")", "Добавление записи в таблицу из админстраницы", function($error) use($conf){
					if(($fields = fields($_GET['r'])) && preg_match("#Column '([\w-_]+)' cannot be null#", $error, $match)){
						if($type = $fields[$match[1]]['Type']){
							mpre("Правка структуры таблицы", $sql = "ALTER TABLE {$_GET['r']} CHANGE `{$match[1]}` `{$match[1]}` {$type} DEFAULT NULL COMMENT '". mpquot(get($fields, $match[1], 'Comment')). "'", $error, $match, get($fields, $match[1]));
							qw($sql);
						}else{ mpre("Тип данных для правки структуры БД не определен", $_GET['r'], $match); }
					}else{ /*mpre("Ошибка определения структуры запроса");*/ }
				});
				$_GET['id'] = $conf['db']['conn']->lastInsertId();
				$el = rb($_GET['r'], "id", $_GET['id']);
			}
			if(get($conf, 'settings', 'admin_history_log')){
//				if($admin_history_type = rb("{$conf['db']['prefix']}admin_history_type", "name", $w = "[Добавление]")){
				if($admin_history_type = fk("{$conf['db']['prefix']}admin_history_type", $w = array("name"=>"Добавление"), $w)){
					if($admin_history_tables = fk("{$conf['db']['prefix']}admin_history_tables", $w = array("name"=>$_GET['r']), $w += array("modpath"=>$arg['modpath'], "fn"=>$arg['fn'], "description"=>get($conf, 'settings', substr($_GET['r'], strlen($conf['db']['prefix'])))), $w)){
//						if($data = rb($_GET['r'], "id", $_POST['id'])){
							$admin_history = fk("{$conf['db']['prefix']}admin_history", null, array("history_type_id"=>$admin_history_type['id'], "name"=>$el['id'], "history_tables_id"=>$admin_history_tables['id'], "data"=>json_encode($_POST)));
//						}else{ mpre("Ошибка выборки изменяемой записи"); }
					}else{ mpre("Ошибка сохранения названия таблицы"); }
				}else{ mpre("Тип записи не найден {$w}"); }
			}else{ /*mpre("Логирование запросов выключено");*/ }
		}

		foreach(array(
			"img"=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp'),
			"img2"=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp'),
			"img3"=>array('image/png'=>'.png', 'image/pjpeg'=>'.jpg', 'image/jpeg'=>'.jpg', 'image/gif'=>'.gif', 'image/bmp'=>'.bmp'),
			"file"=>array("*"=>"*")
		) as $f=>$ext){
			if($file = get($_FILES, $f)){ # POST содержащий  файл
				if(is_array($file['error'])){ # Множественная загрузка
					foreach($file['error'] as $key=>$error){
						if($file['name'][$key]){
							if($error){
								exit("Ошибка загрузки файла {$file['name'][$key]}");
							}else{
								if($key > 0){
									$el = fk($_GET['r'], null, $w = array_diff_key($el, array_flip(array("id", "sort"))), $w);
								} if(array_key_exists("sort", $el) && ($el['sort'] == 0)){
									$el = fk($_GET['r'], array("id"=>$el['id']), null, array("sort"=>$el['id']));
								} $file_id = mpfid($_GET['r'], $f, $el['id'], $key, $ext);
							}
						}
					}
				}else if($file_id = mpfid($_GET['r'], $f, $el['id'], null, $ext)){
					# Файл загружен
				}else{ exit("Ошибка загрузки файла {$file['name']}"); }
			}elseif(get($_POST, $f)){ # Адрес внешнего изображения
				$file_id = mphid($class, $f, $el['id'], $_POST[$f], $ext);
			}
		}
		
		if(array_key_exists("sort", $el) && !$el['sort']){ # Если у нас есть поле сортировки и оно пустое, то назначаем его равным id
			$el = fk($_GET['r'], array("id"=>$el['id']), null, array("sort"=>$el['id']));
		} exit(htmlspecialchars(json_encode($el)));
	}
}else{ # Выборка таблицы
	if(strpos($_GET['r'], "-") && ($r = explode("-", $_GET['r']))){
		$_GET['r'] = $conf['db']['prefix']. first($r). "_". last($r);
	} if($conf['db']['type'] == "sqlite"){
		$tpl['tables'] = array_column(qn("SELECT * FROM sqlite_master WHERE type='table' AND name LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\"", "name"), "name");
	}else{
		$tpl['tables'] = array_column(ql("SHOW TABLES WHERE `Tables_in_{$conf['db']['name']}` LIKE \"{$conf['db']['prefix']}{$arg['modpath']}%\""), "Tables_in_{$conf['db']['name']}");
	}
	foreach($tpl['tables'] as $key=>$tables){
		$short = implode("_", array_slice(explode("_", $tables), 0, -1));
		if(($top = array_search($short, $tpl['tables'])) !== false){
			$tpl['menu'][$top][] = $key;
		}else{ $tpl['menu'][$key] = array(); }
	} if(empty($_GET['r'])){
		$modules_index = fk("modules-index", array("folder"=>$arg['modpath']), null, array("priority"=>time()));
		if($tpl['tables'] && ($table = array_shift($tables = $tpl['tables']))){
			exit(header("Location:/{$arg['modpath']}:admin/r:{$table}"));
		}elseif($table = "{$conf['db']['prefix']}{$arg['modpath']}_index"){
			exit(header("Location:/{$arg['modpath']}:admin/r:{$table}"));
		}
	}elseif(array_search($_GET['r'], $tpl['tables']) !== false){
/*		if($conf['db']['type'] == "sqlite"){
			$tpl['fields'] = qn("pragma table_info ('". $_GET['r']. "')", "name");
		}else{
			$tpl['fields'] = qn("SHOW FULL COLUMNS FROM {$_GET['r']}", "Field");
		}*/ $tpl['fields'] = fields($_GET['r']);
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
		}elseif($settings = get($conf, 'settings', "{$arg['modpath']}=>ecounter")){
			foreach(explode(",", $settings) as $ecounter){
//				if($fields = qn("SHOW FULL COLUMNS FROM {$conf['db']['prefix']}{$ecounter}", "Field")){
				if($fields = fields("{$conf['db']['prefix']}{$ecounter}")){
					if(get($fields, substr($_GET['r'], strlen($conf['db']['prefix']))) || ($_GET['r'] == "{$conf['db']['prefix']}{$arg['modpath']}")){
						if($fl = ($_GET['r'] != "{$conf['db']['prefix']}{$arg['modpath']}" ? substr($_GET['r'], strlen($conf['db']['prefix'])) : "uid")){
							$tpl['ecounter']["__". $ecounter] = qn($sql = "SELECT `id`, `{$fl}`, COUNT(id) AS cnt FROM `{$conf['db']['prefix']}{$ecounter}` WHERE `{$fl}` IN (". in($tpl['lines']). ") GROUP BY `{$fl}`", $fl);
						}else{ mpre("Поле не определено ". substr($_GET['r'], strlen($conf['db']['prefix']))); }
					}
				}else{ mpre("Не удалось получить список полей таблицы {$conf['db']['prefix']}{$ecounter}"); }
			}
		}
		if($settings_espisok = get($conf, 'settings', "{$arg['modpath']}=>espisok")){
			foreach(explode(",", $settings_espisok) as $espisok){
				$tpl['espisok'][$espisok] = rb("{$conf['db']['prefix']}{$espisok}");
			}
		} foreach($tpl['fields'] as $field){
			if((count($fd = explode("-", $field['Field'])) >= 2) && get($conf, 'modules', $fd[0])){
				$tpl['espisok'][$field['Field']] = rb($field['Field']);
			}else{ /*mpre("Что-то пошло не так...");*/ }
		}
		if($tab = substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))){
			foreach($tpl['tables'] as $tables){
				$ft = substr($tables, strlen("{$conf['db']['prefix']}{$arg['modpath']}_"));
				if($fields = fields($tables)){
					if(get($fields, ($t = "{$tab}_id"))){
						$tpl['counter']["_{$ft}"] = array_column(ql("SELECT `{$t}`, COUNT(*) AS cnt FROM `{$conf['db']['prefix']}{$arg['modpath']}". ($ft ? "_{$ft}" : ""). "` WHERE `{$t}` IN (". in($tpl['lines']). ") GROUP BY `{$t}`"), "cnt", $t);
					}
				}else{ mpre("Поля таблицы $tables не определены"); }
			}

			$tpl['etitle'] = array("id"=>"Номер", 'time'=>'Время', 'up'=>'Обновление', 'uid'=>'Пользователь', 'count'=>'Количество', 'level'=>'Уровень', 'ref'=>'Источник', 'cat_id'=>'Категория', 'img'=>'Изображение', 'img2'=>'Изображение2', 'img3'=>'Изображение3', 'file'=>'Файл', 'hide'=>'Видим', 'sum'=>'Сумма', 'fm'=>'Фамилия', 'im'=>'Имя', 'ot'=>'Отвество', 'sort'=>'Сорт', 'name'=>'Название', 'duration'=>'Длительность', 'pass'=>'Пароль', 'reg_time'=>'Время регистрации', 'last_time'=>'Последний вход', 'email'=>'Почта', 'skype'=>'Скайп', 'site'=>'Сайт', 'title'=>'Заголовок', 'sity_id'=>'Город', 'country_id'=>'Страна', 'status'=>'Статус', 'addr'=>'Адрес', 'tel'=>'Телефон', 'code'=>'Код', "article"=>"Артикул", 'price'=>'Цена', 'captcha'=>'Защита', 'href'=>'Ссылка', 'keywords'=>'Ключевики', "users_sity"=>'Город', 'log'=>'Лог', 'min'=>'Мин', 'max'=>'Макс', 'own'=>'Владелец', 'period'=>'Период', "from"=>"Откуда", "to"=>"Куда", "percentage"=>"Процент", 'description'=>'Описание', 'text'=>'Текст');
			if($title = get($conf, 'settings', "{$arg['modpath']}_{$tab}=>title")){
				$tpl['title'] = array_merge(array("id"), explode(",", $title));
			}elseif(get($tpl, 'fields', "text")){
				$tpl['title'] = array_keys(array_diff_key($tpl['fields'], array("text"=>false)));
			}
		}elseif($_GET['r'] == "{$conf['db']['prefix']}users"){ # Количество групп в которых состоит человек
			$tpl['counter']["_mem"] = array_column(ql("SELECT `uid`, COUNT(*) AS cnt FROM `{$conf['db']['prefix']}{$arg['modpath']}_mem` WHERE `uid` IN (". in($tpl['lines']). ") GROUP BY `uid`"), "cnt", "uid");
		}
	}
}
