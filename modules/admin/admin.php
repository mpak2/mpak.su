<?

if(array_key_exists("null", $_GET) && $_GET['r'] && $_POST){
	if($_GET['id'] && !$_POST['id'] && array_key_exists("id", $_POST)){ # Удаление элемента
		exit(qw("DELETE FROM {$_GET['r']} WHERE id=". (int)$_GET['id']));
	}else{ # Правка записи и добавление новой
		$el = fk($_GET['r'], ($_GET['id'] ? array("id"=>$_GET['id']) : null), $_POST, $_POST);
		if($_FILES['img']){ # POST содержащий  файл
			$file_id = mpfid($_GET['r'], "img", $el['id']);
		}elseif($_POST[$f = 'img']){ # Адрес внешнего изображения
			$file_id = mphid($class, $f, $el['id'], $_POST['img']);
		} exit(json_encode($el));
	}
}else{ # Выборка таблицы
	$tpl['tables'] = array_column(ql("SHOW TABLES WHERE `Tables_in_{$conf['db']['name']}` LIKE \"{$conf['db']['prefix']}{$arg['modname']}%\""), "Tables_in_{$conf['db']['name']}");
	if(empty($_GET['r'])){
		if($table = array_shift($tables = $tpl['tables'])){
			exit(header("Location:/{$arg['modname']}:admin/r:{$table}"));
		}
	}elseif(array_search($_GET['r'], $tpl['tables']) !== false){
		$tpl['fields'] = qn("SHOW FULL COLUMNS FROM {$_GET['r']}", "Field");

		if($_GET['order']){ # Установка временной сортировки
			$conf['settings'][substr($_GET['r'], strlen($conf['db']['prefix'])). "=>order"] = $_GET['order'];
		} $tpl['lines'] = call_user_func_array("rb", ($_GET['where'] ? array_merge(array($_GET['r'], 20), array_keys($_GET['where']), array("id"), (array)array_values($_GET['where'])) : array($_GET['r'], 20)));

		$tpl['spisok'] = array(
			'hide' => array(0=>"Видим", 1=>"Скрыт"),
		);
		if($_GET['edit']){
			$tpl['edit'] = rb($_GET['r'], "id", $_GET['edit']);
		}else{
			foreach(array_intersect_key($tpl['fields'], array_flip(explode(",", $conf['settings']["{$arg['modname']}=>espisok"]))) as $fl=>$espisok){
				$tpl['espisok'][$fl] = rb("{$conf['db']['prefix']}{$fl}", "id", "id", rb($tpl['lines'], $fl));
			}
			if($settings = $conf['settings']["{$arg['modname']}=>ecounter"]){
				foreach(explode(",", $settings) as $ecounter){
					if($fields = qn("SHOW FULL COLUMNS FROM {$conf['db']['prefix']}{$ecounter}", "Field")){
						if(array_key_exists(substr($_GET['r'], strlen($conf['db']['prefix'])), $fields)){
							if($fl = substr($_GET['r'], strlen($conf['db']['prefix']))){
								$tpl['ecounter']["__". $ecounter] = qn($sql = "SELECT `id`, `{$fl}`, COUNT(id) AS cnt FROM `{$conf['db']['prefix']}{$ecounter}` WHERE `{$fl}` IN (". in($tpl['lines']). ") GROUP BY `{$fl}`", $fl);
							}else{ mpre("Поле не определено ". substr($_GET['r'], strlen($conf['db']['prefix']))); }
						}
					}else{ mpre("Не удалось получить список полей таблицы {$conf['db']['prefix']}{$ecounter}"); }
				}
			}
		}// mpre($tpl['ecounter']);
		if($tab = substr($_GET['r'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_"))){
			foreach($tpl['tables'] as $tables){
				$ft = substr($tables, strlen("{$conf['db']['prefix']}{$arg['modpath']}_"));
				$fields = qn("SHOW FULL COLUMNS FROM {$tables}", "Field");
				if(array_key_exists(($t = "{$tab}_id"), $fields)){
					$tpl['counter']["_{$ft}"] = array_column(ql("SELECT `{$t}`, COUNT(*) AS cnt FROM `{$conf['db']['prefix']}{$arg['modpath']}_{$ft}` WHERE `{$t}` IN (". in($tpl['lines']). ") GROUP BY `{$t}`"), "cnt", $t);
				}
			}

			$tpl['etitle'] = array("id"=>"Номер", 'time'=>'Время', 'up'=>'Обновление', 'uid'=>'Пользователь', 'count'=>'Количество', 'level'=>'Уровень', 'ref'=>'Источник', 'cat_id'=>'Категория', 'img'=>'Изображение', 'img2'=>'Изображение2', 'file'=>'Файл', 'hide'=>'Видим', 'sum'=>'Сумма', 'fm'=>'Фамилия', 'im'=>'Имя', 'ot'=>'Отвество', 'sort'=>'Сорт', 'name'=>'Название', 'duration'=>'Длительность', 'pass'=>'Пароль', 'reg_time'=>'Время регистрации', 'last_time'=>'Последний вход', 'email'=>'Почта', 'skype'=>'Скайп', 'site'=>'Сайт', 'title'=>'Заголовок', 'sity_id'=>'Город', 'country_id'=>'Страна', 'status'=>'Статус', 'addr'=>'Адрес', 'tel'=>'Телефон', 'code'=>'Код', "article"=>"Артикул", 'price'=>'Цена', 'captcha'=>'Защита', 'href'=>'Ссылка', 'keywords'=>'Ключевики', "users_sity"=>'Город', 'log'=>'Лог', 'min'=>'Мин', 'max'=>'Макс', 'own'=>'Владелец', 'period'=>'Период', "from"=>"С", "to"=>"До", "percentage"=>"Процент", 'description'=>'Описание', 'text'=>'Текст');
			if($title = $conf['settings']["{$arg['modpath']}_{$tab}=>title"]){
				$tpl['title'] = array_merge(array("id"), explode(",", $title));
			}elseif(array_key_exists("text", $tpl['fields'])){
				$tpl['title'] = array_keys(array_diff_key($tpl['fields'], array("text"=>false)));
			}
		}
	}
}