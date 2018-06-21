<?

if($table = get($_GET, 'r')){
	$tpl['fields'] = fields($table);
	$tpl['indexes'] = indexes($table);// mpre($tpl['indexes']);
}

if($dump = get($_REQUEST, 'dump')){
	if(get($file = get($_FILES, 'file'), 'name')){
		if($file['error'] == 0){
			exit(mpre(false, $file['tmp_name'], qw(file_get_contents($file['tmp_name']))));
		}else{ mpre("Ошибка загрузки файла", $file); }
	}else if(get($_REQUEST, 'upload')){		
		if($cmd = "mysqldump {$conf['db']['name']} ".implode(" ",array_keys($dump))." -u {$conf['db']['login']} -p{$conf['db']['pass']}"){			
			header("content-disposition: attachment; filename={$conf['db']['name']}".((count($dump)==1)?".".first(array_keys($dump)):"").".sql");			
			exit(passthru($cmd));
		}
	}else{
		$tpl['dump'] = "mysqldump {$conf['db']['name']} ".implode(" ",array_keys($dump))." -u {$conf['db']['login']} -p{$conf['db']['pass']}";
	}
}else if(array_key_exists("null", $_GET)){
	if(!$_POST){ mpre("Пост запрос не задан");
	}else{
		if($foreign = get($_POST, 'foreign')){
			if($conf['db']['type'] == "sqlite"){// die(!mpre($_GET, $_POST));
				if(!$table = $_GET['r']){ die(!mpre("Имя таблицы не установлено"));
				}elseif(!$FIELDS = fields($table)){ die(!mpre("Ошибка установки свойств таблицы"));
				}elseif(!$field = $_POST['foreign']){ die(!mpre("Ошибка установки поля вторичного ключа"));
				}elseif(!$sql = "PRAGMA foreign_key_list({$_GET['r']});"){ mpre("Ошибка получения информации о вторичных ключах");
				}elseif(!is_array($FOREIGN_KEYS = mpqn(mpqw($sql), "from"))){ mpre("Ошибка выполнения выборки вторичных ключей");
				}elseif(!$tab = explode("_", substr($table, strlen($conf['db']['prefix'])))){ die(!mpre("Ошибка парса таблицы"));
				}elseif(!$fntab = "{$conf['db']['prefix']}{$tab[0]}_". substr($field, 0, -3)){ die(!mpre("Ошибка установки связанной таблицы"));
				}elseif($foreign_keys = get($FOREIGN_KEYS, $field)){// mpre("Удаление ключа");
//					"База данных sqlite не поддерживает изменение полей, поэтому делаем через промежуточную таблицу",
					$transaction = array(
						"PRAGMA foreign_keys=OFF;",
						"BEGIN TRANSACTION;",
						"DROP TABLE IF EXISTS `backup`;",
						"CREATE TEMPORARY TABLE `backup` (". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "`{$f['name']}` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}"); }, $FIELDS))). ");",
						"INSERT INTO `backup` SELECT * FROM `". mpquot($table). "`;",
						"DROP TABLE `". mpquot($table). "`;",
						"CREATE TABLE `". mpquot($table). "` (". implode(",", (array_map(function($f) use($fntab, $field){ return ($f['name'] == "id" ? " `{$f['name']}` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}". ($f['dflt_value'] ? " DEFAULT {$f['dflt_value']}" : "")); }, $FIELDS))). ");",
						"INSERT INTO `". mpquot($table). "` (`". implode("`, `", array_keys($FIELDS)). "`) SELECT `". implode("`, `", array_keys($FIELDS)). "` FROM backup;",
						"DROP TABLE backup;",
						"COMMIT;",
						"PRAGMA foreign_keys=ON;",
					);// die(!mpre(get($transaction, 5))); // foreach($transaction as $sql){ qw($sql); }
//					while(list($key, $sql) = each($transaction)){ qw($sql); } # IS DEPRICATE
					foreach($transaction as $key=>$sql){ qw($sql); }
					exit(json_encode($FIELDS));
				}elseif(!$on_update = "UPDATE ". $_POST[$w = 'on_update']){ die(!mpre("Не задан `{$w}` контроля вторичного ключа"));
				}elseif(!$on_delete = "DELETE ". $_POST[$w = 'on_delete']){ die(!mpre("Не задан `{$w}` контроля вторичного ключа"));
				}else{// die(!mpre($fntab, $field));
//					"База данных sqlite не поддерживает изменение полей, поэтому делаем через промежуточную таблицу",
					$transaction = array(
						"PRAGMA foreign_keys=OFF;",
						"BEGIN TRANSACTION;",
						"DROP TABLE IF EXISTS `backup`;",
						"CREATE TEMPORARY TABLE `backup` (". implode(", ", (array_map(function($f){ return ($f['name'] == "id" ? "`{$f['name']}` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}"); }, $FIELDS))). ");",
						"INSERT INTO `backup` SELECT * FROM `". mpquot($table). "`;",
						"DROP TABLE `". mpquot($table). "`;",
						"CREATE TABLE `". mpquot($table). "` (". implode(", ", (array_map(function($f) use($fntab, $field, $on_update, $on_delete){ return ($f['name'] == "id" ? " `{$f['name']}` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}". ($f['dflt_value'] ? " DEFAULT {$f['dflt_value']}" : ""). (($field == $f['name']) ? " REFERENCES {$fntab}(id) ON {$on_update} ON {$on_delete}" : "")); }, $FIELDS))). ");",
						"INSERT INTO `". mpquot($table). "` (`". implode("`, `", array_keys($FIELDS)). "`) SELECT `". implode("`, `", array_keys($FIELDS)). "` FROM `backup`;",
						"DROP TABLE `backup`;",
						"COMMIT;",
						"PRAGMA foreign_keys=ON;",
					);// die(!mpre($transaction)); // foreach($transaction as $sql){ qw($sql); }
//					while(list($key, $sql) = each($transaction)){ qw($sql); }
					foreach($transaction as $key=>$sql){ qw($sql); }
					exit(json_encode($FIELDS));
				} die(!mpre("Неустановленная ошибка"));
			}else{// die(!mpre("mysql"));
				$tpl['key_column_usage'] = ql($sql = "SELECT * FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE WHERE (TABLE_NAME='{$_GET['r']}' AND REFERENCED_TABLE_NAME != '') OR REFERENCED_TABLE_NAME = '{$_GET['r']}'");
				if($key_column_usage = rb($tpl['key_column_usage'], "REFERENCED_TABLE_NAME", "REFERENCED_COLUMN_NAME", "[{$_GET['r']}]", $foreign)){ mpre("Внешний ключ", $key_column_usage);
					exit(mpre("ALTER TABLE {$key_column_usage['REFERENCED_TABLE_NAME']} DROP KEY "));
				}elseif($key_column_usage = rb($tpl['key_column_usage'], "TABLE_NAME", "COLUMN_NAME", "[{$_GET['r']}]", $foreign)){// mpre("Внутренний ключ", $key_column_usage);
					qw("ALTER TABLE `{$key_column_usage['TABLE_NAME']}` DROP FOREIGN KEY `{$key_column_usage['CONSTRAINT_NAME']}`");
					exit(json_encode($key_column_usage));
				}elseif("_id" == substr($foreign, -3)){// mpre("Создание ключа", $foreign);
					if($fields = fields($_GET['r'])){
						if(get($fields, $foreign, "Null") == "NO"){
							qw("ALTER TABLE `". mpquot($_GET['r']). "` CHANGE `{$foreign}` `{$foreign}` {$fields[$foreign]['Type']} NULL COMMENT '{$fields[$foreign]['Comment']}'");
							qw("UPDATE `". mpquot($_GET['r']). "` SET `{$foreign}`=NULL WHERE `{$foreign}`=0");
						} qw("ALTER TABLE `{$_GET['r']}` ADD FOREIGN KEY (`". mpquot($foreign). "`) REFERENCES `{$conf['db']['prefix']}". first(explode("_", substr($_GET['r'], strlen($conf['db']['prefix'])))). "_". substr($foreign, 0, -3). "` (`id`) ON DELETE ". mpquot($_POST['reference']));
					}else{ exit(mpre("Ошибка определения структуры таблицы", $_GET['r'])); }
					exit(json_encode($tpl['key_column_usage']));
				}else{ exit(mpre("Ошибка подключения вторичного ключа", $_GET['r'], $_POST)); }
				exit(mpre(false, $_GET['r'], $tpl['key_column_usage']));
			}
		}else if($sql = get($_POST, 'sql')){
			if(!$query = fk("query", null, $w = array("query"=>$sql), $w)){ mpre("ОШИБКА добавления запроса в таблицу истории");
//			}elseif(true){ mpre("Запрос", $sql);
			}elseif(!$result = qw($sql)){ mpre("Ошибка выполнения запроса");
			}elseif(!is_array($data = mpql($result))){ mpre("ОШИБКА получения списка результатов");
			}elseif(($name = get($data, "name")) && (!$data = $name)){ mpre("Удобный для вывода формат");
			}else{ exit(!mpre("Результат вывода запроса", ((count($data) == 1) ? first($data) : $data)));
			} exit(mpre("ОШИБКА выполнения запроса"));
		}else if(($table = get($_POST, 'del')) && ($fields = fields($table))){
			if(!qw($sql = "DROP TABLE `{$table}`")){ mpre("ОШИБКА удаления таблицы", $sql);
			}else{ exit(json_encode($fields)); }
		}else if($table = $_POST['table']){
			if($conf['db']['type'] == 'sqlite'){ # Новая таблица в sqlite
				qw("CREATE TABLE `{$table}` (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE, time INTEGER, uid INTEGER, name TEXT)");
			}else{ # Новая таблица в mysql
				qw("CREATE TABLE `$table` (
					id INT(11) AUTO_INCREMENT PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE,
					time INT(11),
					uid INT(11),
					name VARCHAR(255)
				) DEFAULT CHARSET=utf8");
			} exit(json_encode(array("table"=>$table)));
		} exit(mpre("Ошибочный запрос", $_POST));
	}
}elseif(!$table = get($_GET, 'r')){ mpre("ОШИБКА получения имени таблицы");
}elseif(!$fields = fields($table)){ mpre("ОШИБКА определения полей таблицы");
}elseif(!$fil = get($_POST, 'f')){// mpre("ОШИБКА получения списка полей");
}else{// mpre($tpl['indexes']);
	foreach($fil as $f=>$fld){ # Поля базы данных
		if($conf['db']['type'] == 'sqlite'){
			if(get($fld, 'index')){// mpre("Ключ не отмечен на удаление");
			}elseif(!$index_name = substr($_GET['r'], strlen($conf['db']['prefix'])). "-{$f}"){ mpre("Ошибка формирования имени ключа");
			}elseif(!get($tpl, 'indexes', $index_name)){// mpre("Ключ не создан");
			}else{// mpre($index_name, $fld);
				qw(mpre("DROP INDEX `{$index_name}`"));
				$tpl['indexes'] = indexes($table);
			}

			if(!$fld['name']){ # Удаление поля
				$FIELDS = array_diff_key($tpl['fields'], array_flip(array($f)));
				mpre("База данных sqlite не поддерживает удаление полей, поэтому делаем через промежуточную таблицу", $transaction = array(
					"PRAGMA foreign_keys=OFF;",
					"BEGIN TRANSACTION;",
					"CREATE TEMPORARY TABLE `backup`(". implode(",", array_map(function($f){ return ($f['name'] == "id" ? "`{$f['name']}` {$f['type']} PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}"); }, $tpl['fields'])). ");",
					"INSERT INTO `backup` SELECT * FROM `". mpquot($table). "`;",
					"DROP TABLE `". mpquot($table). "`;",
					"CREATE TABLE `". mpquot($table). "`(". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "`{$f['name']}` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}". ($f['dflt_value'] ? " DEFAULT {$f['dflt_value']}" : "")); }, $FIELDS))). ");",
					"INSERT INTO `". mpquot($table). "` SELECT `". implode("`,`", array_column($FIELDS, "name")). "` FROM `backup`;",
					"DROP TABLE `backup`;",
					"COMMIT;",
					"PRAGMA foreign_keys=ON;",
				)); foreach($transaction as $sql){ qw($sql); }


				if(!$tab = substr($table, strlen($conf['db']['prefix']))){ mpre("ОШИБКА получения короткого имени таблицы (без префикса)");
				}elseif(!$keyname = "{$tab}-{$f}"){ mpre("ОШИБКА получение имени ключа удаляемого поля");
				}elseif(!$tpl['indexes'] = array_diff_key($tpl['indexes'], array_flip([$keyname]))){ mpre("ОШИБКА удаления из списка ключа удаляемого поля");
				}else{// mpre($tab, $keyname, $tpl['indexes']);
					mpre("Восстановление индексов", array_column($tpl['indexes'], 'sql', 'name'));
					foreach($tpl['indexes'] as $indexes){ qw($indexes['sql']); }
				}

			}elseif(get($fields, $f, 'name') && ((get($fields, $f, 'type') != get($fld, 'type')) || ($fields[$f]['name'] != $fld['name']) || (get($fields, $f, 'dflt_value') != get($fld, 'default')))){
				if(!$nn = array_search($f, array_keys($tpl['fields']))){ mpre("Номер старого элемента не найден");
				}elseif(!$NF = array_slice($tpl['fields'], 0, $nn) + [$fld['name']=>($fld+ ['dflt_value'=>$fld['default']])] + array_slice($tpl['fields'], $nn+1)){ mpre("Ошибка формирования нового списка значений");
				}elseif(!$FF = array_diff_key($tpl['fields'], [$f=>false])){ mpre("Ошибка формирования списка без старого элемента");
//				}elseif(!($fields[$f]['dflt_value'] = $fld['default']) &0){ mpre("Установка значения по умолчанию");
				}else{// mpre($nn, $FF, $NF);
					mpre("База данных sqlite не поддерживает изменение полей, поэтому изменения порядка полей делаем через промежуточную таблицу", $transaction = array(
						"PRAGMA foreign_keys=OFF;",
						"BEGIN TRANSACTION;",
						"CREATE TEMPORARY TABLE backup(". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "{$f['name']} INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}"); }, $tpl['fields']))). ");",
						"INSERT INTO backup SELECT * FROM ". mpquot($table). ";",
						"DROP TABLE ". mpquot($table). ";",
						"CREATE TABLE ". mpquot($table). "(". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "{$f['name']} INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}". ($f['dflt_value'] ? " DEFAULT {$f['dflt_value']}" : "")); }, $NF))). ");",
						"INSERT INTO ". mpquot($table). " (`". implode("`,`", array_keys($NF)). "`) SELECT `". implode("`,`", array_keys($tpl['fields'])). "` FROM backup;",
						"DROP TABLE backup;",
						"COMMIT;",
						"PRAGMA foreign_keys=ON;",
					)); foreach($transaction as $sql){ qw($sql); }

					if(!$tab = substr($table, strlen($conf['db']['prefix']))){ mpre("ОШИБКА получения короткого имени таблицы (без префикса)");
					}elseif(!$keyname = "{$tab}-{$f}"){ mpre("ОШИБКА получение имени ключа удаляемого поля");
					}elseif(!$tpl['indexes'] = call_user_func(function($indexes) use($keyname, $table, $tab, $fld, $f){ # Удаляем ключ со старым полем и добавляем запрос на создание ключа на новом поле
							if(!get($indexes, $keyname)){ mpre("Ключ на поле у старой таблицы не был установлен (на измененное поле тоже не стаим)");
							}elseif(!is_array($indexes = array_diff_key($indexes, array_flip([$keyname])))){ mpre("Удаляем запись о старом ключе");
							}elseif(!$field = get($fld, 'name')){ mpre("ОШИБКА имя нового поля не указано");
							}elseif(!$_keyname = "{$tab}-{$field}"){ mpre("ОШИБКА формирования имени нового ключа");
							}elseif(!$sql = "CREATE INDEX `{$_keyname}` ON `{$table}` (`{$field}`);"){ mpre("ОШИБКА формирования запроса на создание люча нового поля");
							}elseif(!$indexes[$_keyname] = ['type'=>'index', 'name'=>$_keyname, 'tbl_name'=>$table, 'sql'=>$sql]){ mpre("ОШИБКА формирования всех полей нового ключа");
							} return $indexes;
						}, $tpl['indexes'])){ mpre("ОШИБКА модификации списка ключей таблицы");
					}else{// mpre($tab, $f, $tpl['indexes']);
						mpre("Восстановление индексов", array_column($tpl['indexes'], 'sql', 'name'));
						foreach($tpl['indexes'] as $indexes){ qw($indexes['sql']); }
					}
				}
			}elseif($after = get($fld, 'after')){ # Изменение порядка сортировки полей
				if(!$afld = get($tpl, 'fields', $after)){ mpre("Ошибка определения свойст поля после которого ставим <b>{$after}</b>");
				}elseif(!$nfld = get($tpl, 'fields', $fld['name'])){ mpre("Ошибка определения свойст поля котороге ставим <b>{$after}</b>");
				}elseif(($nn = array_search($after, array_keys($tpl['fields']))) === false){ mpre("Номер старого элемента не найден");
				}elseif(!$F = array_diff_key($tpl['fields'], [$fld['name']=>$fld])){ mpre("Ошибка исключения элемента из списка полей");
				}elseif(!$NF = array_slice($F, 0, $nn+1) + [$nfld['name']=>$nfld] + array_slice($F, $nn+1)){ mpre("Ошибка формирования нового списка значений");
				}elseif(!$OF = $tpl['fields']){ mpre("Ошибка формирования списка без старого элемента");
				}else{// mpre($nn, $nfld, $OF, $F, $NF);
					mpre("База данных sqlite не поддерживает изменение полей, поэтому делаем через промежуточную таблицу", $transaction = array(
						"PRAGMA foreign_keys=OFF;",
						"BEGIN TRANSACTION;",
						"CREATE TEMPORARY TABLE `backup`(". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "`{$f['name']}` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}"); }, $tpl['fields']))). ");",
						"INSERT INTO `backup` SELECT * FROM `". mpquot($table). "`;",
						"DROP TABLE `". mpquot($table). "`;",
						"CREATE TABLE `". mpquot($table). "`(". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "`{$f['name']}` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : "`{$f['name']}` {$f['type']}"); }, $NF))). ");",
						"INSERT INTO `". mpquot($table). "`(`". implode("`, `", array_keys($NF)). "`) SELECT `". implode("`, `", array_keys($NF)). "` FROM `backup`;",
						"DROP TABLE `backup`;",
						"COMMIT;",
						"PRAGMA foreign_keys=ON;",
					)); foreach($transaction as $sql){ qw($sql); }
					
					mpre("Восстановление индексов", array_column($tpl['indexes'], 'sql', 'name'));
					foreach($tpl['indexes'] as $indexes){ qw($indexes['sql']); }

				}
			}elseif(empty($tpl['indexes'][$index_name = substr($_GET['r'], strlen($conf['db']['prefix'])). "-{$fld['name']}"]) && array_key_exists($f, $tpl['fields']) && array_key_exists('index', $fld) && $fld['index']){
				qw(mpre("CREATE INDEX `{$index_name}` ON `{$_GET['r']}` (`{$fld['name']}`);"));
				$tpl['indexes'] = indexes($table);
			}else{ $tpl['fields'] = fields($table);
			}
		}else{ // $conf['db']['type'] == 'mysql'
			
			if($fld['after'] || ($fields[$f]['Type'] != $fld['type']) || ($fields[$f]['Comment'] != $fld['comment']) || ($fields[$f]['Default'] != $fld['default']) || ($fld['name'] && ($fld['name'] != $f))){
				qw(mpre($sql = ("ALTER TABLE `". mpquot($table). "` CHANGE `". mpquot($f). "` `". mpquot($fld['name'] ?: $f). "` ". mpquot($fld['type'] ?: $fields[$f]['Type'])/*. ($fields[$f]['Null'] == "NO" ? " NOT NULL" : " NULL")*/. (($fld['default'] !== "") ? " DEFAULT ". ($fld['default'] == "NULL" ? mpquot($fld['default']) : "'". mpquot($fld['default']). "'") : ""). " COMMENT '". mpquot($fld['comment']). "'") . ($fld['after'] ? " AFTER `". mpquot($fld['after'])."`": "") ));                           
			}

			if(!get($fld, 'index')){// mpre("Ключ не отмечен для создания");
			}elseif(!$index_name = $fld['name']){// mpre("Ошибка формирования имени ключа");
			}elseif(get($tpl, 'indexes', $index_name)){// mpre("Ключ уже создан");
			}else{
				qw(mpre($sql = "ALTER TABLE `{$_GET['r']}` ADD INDEX (`{$fld['name']}`)"));
				$tpl['indexes'] = indexes($table);
			}

			if(get($fld, 'index')){// mpre("Ключ не отмечен на удаление");
			}elseif(!$index_name = $fld['name']){// mpre("Ошибка формирования имени ключа");
			}elseif(!get($tpl, 'indexes', $index_name)){// mpre("Ключ не создан");
			}else{// mpre($fld);
//			qw(mpre("DROP INDEX `{$index_name}`"));
				qw(mpre($sql = "DROP INDEX `{$index_name}` ON `{$_GET['r']}`"));
				$tpl['indexes'] = indexes($table);
			}

			if(!get($fld, 'name')){ # Удаление поля
				qw(mpre("ALTER TABLE `". mpquot($table). "` DROP `{$f}`"));
			} $tpl['fields'] = fields($table);

		}
	}

	if(!$new = $_POST['$']){ # mpre("Не создаем новое поле");
	}elseif(!$f = $new['name']){// mpre("ОШИБКА получения имени нового поля");
	}else{
			if($conf['db']['type'] == 'sqlite'){
				if(!$sql = "ALTER TABLE `". mpquot($table). "` ADD COLUMN `{$f}` {$new['type']}"){ mpre("Ошибка составления запроса");
				}elseif(!qw($sql)){ mpre("Ошибка запроса к БД");
				}elseif(($after = get($new, 'after')) && (!get($tpl['fields'] = fields($table), $after))){ mpre("Ошибочное поле после");
				}elseif(!$fields = array_map(function($field) use($f){
						return "`{$field['name']}` {$field['type']}";
					}, $tpl['fields'] = fields($table))){
				}elseif(!$nn = array_search($after, array_keys($fields))){ mpre("Не найдено поле за которым устанавливаем новое");
				}elseif(!$NF = array_slice($fields, 0, $nn+1) + [$f=>"`{$f}` {$new['type']}"] + array_slice($fields, $nn+1)){ mpre("Ошибка формирования списка новых полей");
				}elseif(!$_fields = array_column($tpl['fields'], 'type', 'name')){ mpre("ОШИБКА получения списка старых полей");
				}else{
					mpre("Создание поля", $sql, "База данных sqlite не поддерживает изменение полей, поэтому делаем через промежуточную таблицу", $transaction = array(
						"BEGIN TRANSACTION;",
						"CREATE TEMPORARY TABLE backup(". implode(",", (array_map(function($f){ return (first(explode(" ", $f)) == "id" ? "{$f} PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : $f); }, $fields))). ")",
						"INSERT INTO backup SELECT * FROM ". mpquot($table). ";",
						"DROP TABLE ". mpquot($table). ";",
						"CREATE TABLE ". mpquot($table). "(". implode(",", (array_map(function($f){ return (first(explode(" ", $f)) == "`id`" ? "{$f} PRIMARY KEY AUTOINCREMENT NOT NULL UNIQUE" : $f); }, $NF))). ")",
						"INSERT INTO ". mpquot($table). " (`". implode("`, `", array_keys($_fields)). "`) SELECT `". implode("`, `", array_keys($_fields)). "` FROM backup;",
						"DROP TABLE backup;",
						"COMMIT;",
					));

					foreach($transaction as $sql){ qw($sql); }
					mpre("Восстановление индексов", array_column($tpl['indexes'], 'sql', 'name'));
					foreach($tpl['indexes'] as $indexes){ qw($indexes['sql']); }
				}
			}else{
				qw($sql = "ALTER TABLE `". mpquot($table). "` ADD `". mpquot($f). "` ". mpquot($new['type']). " ". ($new['default'] ? " DEFAULT '". mpquot($new['default']). "'" : ""). " COMMENT '". mpquot($new['comment']). "' AFTER `". mpquot($new['after']). "`"); mpre($sql);
				$tpl['fields'] = fields($table);
				if(array_key_exists("index", $new) && $new['index']){
					qw($sql = "ALTER TABLE `". mpquot($table). "` ADD INDEX (`". mpquot($f). "`)"); mpre($sql);
				}
				if($f == "sort"){
					qw("UPDATE `". mpquot($table). "` SET `{$f}`=`id`");
					qw("ALTER TABLE `". mpquot($table). "` ADD INDEX (`{$f}`)");
				}
			} $tpl['indexes'] = indexes($table);
	}
}

foreach($tpl['tables'] = tables() as $table=>$info){
	$info['id'] = (empty($nn) ? ($nn = 1) : ++$nn);
	$info['modpath'] = get($conf, 'modules', first(array_slice(explode("_", $table), 1, 1)), 'folder');
} if($conf['db']['type'] == 'sqlite'){
	$tpl['types'] = array("INTEGER", "REAL", "TEXT");
}else{
	$tpl['types'] = array("int(11)", "smallint(6)", "bigint(20)", "float", "varchar(255)", "text", "longtext");
}

