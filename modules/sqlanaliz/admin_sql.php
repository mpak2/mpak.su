<?

if($table = get($_GET, 'r')){
	$tpl['fields'] = fields($table);
	$tpl['indexes'] = indexes($table);// mpre($tpl['indexes']);
}

if($dump = get($_REQUEST, 'dump')){
	if(get($file = get($_FILES, 'file'), 'name')){
		if($file['error'] == 0){
//			if(move_uploaded_file($file['tmp_name'], $tmpfile = tempnam(sys_get_temp_dir(), "dump_"))){
//				$tpl['file'] = `mysql -v -u{$conf['db']['name']} -p{$conf['db']['pass']} {$conf['db']['name']} < {$tmpfile}`;
				exit(mpre(false, $file['tmp_name'], qw(file_get_contents($file['tmp_name']))));
//			}else{ mpre("Ошибка создания временного файла"); }
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
	if($_POST){
		if($foreign = get($_POST, 'foreign')){
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
		}else if($sql = get($_POST, 'sql')){
			if($query = fk("query", null, array("query"=>$sql))){
				if(($mpqw = mpqw($sql)) && ($data = mpql($mpqw))){
					exit(mpre("Результат вывода запроса", ((count($data) == 1) ? first($data) : $data)));
				}else{ exit(mpre("Запрос не предполагает вывода", $query['query'])); }
			}
		}else if(($table = get($_POST, 'del')) && ($fields = fields($table))){
			exit(qw("DROP TABLE `{$table}`"));
		}elseif($table = $_POST['table']){
			if($conf['db']['type'] == 'sqlite'){
				qw("CREATE TABLE `{$table}` (id INTEGER PRIMARY KEY ASC, time INTEGER, name TEXT)");
			}else{
				qw("CREATE TABLE `$table` (
					id INT(11) AUTO_INCREMENT PRIMARY KEY,
					time INT(11),
					uid INT(11),
					name VARCHAR(255)
				) DEFAULT CHARSET=utf8");
			} exit(json_encode(array("table"=>$table)));
		} exit(mpre("Ошибочный запрос", $_POST));
	}
}elseif(($table = get($_GET, 'r')) && ($fields = fields($table)) && array_key_exists('f', $_POST) && is_array($fil = $_POST['f'])){
	foreach($fil as $f=>$fld){ # Поля базы данных
		if($conf['db']['type'] == 'sqlite'){
			if(get($fld, 'index')){// mpre("Ключ не отмечен на удаление");
			}elseif(!$index_name = substr($_GET['r'], strlen($conf['db']['prefix'])). "-{$f}"){ mpre("Ошибка формирования имени ключа");
			}elseif(!get($tpl, 'indexes', $index_name)){// mpre("Ключ не создан");
			}else{// mpre($index_name, $fld);
				qw(mpre("DROP INDEX `{$index_name}`"));
				$tpl['indexes'] = indexes($table);
			}

			/*if(!$fld['name']){ # Удаление ключа
				qw($sql = "ALTER TABLE `". mpquot($table). "` DROP COLUMN `". mpquot($f). "`"); mpre($sql);
			}*/ if(!$fld['name']){
				$FIELDS = array_diff_key($tpl['fields'], array_flip(array($f)));
				mpre("База данных sqlite не поддерживает удаление полей, поэтому делаем через промежуточную таблицу", $transaction = array(
					"BEGIN TRANSACTION;",
					"CREATE TEMPORARY TABLE backup(". implode(",", array_map(function($f){ return ($f['name'] == "id" ? "{$f['name']} {$f['type']} PRIMARY KEY" : "{$f['name']} {$f['type']}"); }, $tpl['fields'])). ");",
					"INSERT INTO backup SELECT * FROM ". mpquot($table). ";",
					"DROP TABLE ". mpquot($table). ";",
					"CREATE TABLE ". mpquot($table). "(". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "{$f['name']} INTEGER PRIMARY KEY" : "{$f['name']} {$f['type']}". ($f['dflt_value'] ? " DEFAULT {$f['dflt_value']}" : "")); }, $FIELDS))). ");",
					"INSERT INTO ". mpquot($table). " SELECT ". implode(",", array_column($FIELDS, "name")). " FROM backup;",
					"DROP TABLE backup;",
					"COMMIT;",
				)); foreach($transaction as $sql){ qw($sql); }
			}elseif(get($fields, $f, 'name') && ((get($fields, $f, 'type') != get($fld, 'type')) || ($fields[$f]['name'] != $fld['name']) || (get($fields, $f, 'dflt_value') != get($fld, 'default')))){
				if(!$nn = array_search($f, array_keys($tpl['fields']))){ mpre("Номер старого элемента не найден");
				}elseif(!$NF = array_slice($tpl['fields'], 0, $nn) + [$fld['name']=>($fld+ ['dflt_value'=>$fld['default']])] + array_slice($tpl['fields'], $nn+1)){ mpre("Ошибка формирования нового списка значений");
				}elseif(!$FF = array_diff_key($tpl['fields'], [$f=>false])){ mpre("Ошибка формирования списка без старого элемента");
//				}elseif(!($fields[$f]['dflt_value'] = $fld['default']) &0){ mpre("Установка значения по умолчанию");
				}else{// mpre($nn, $FF, $NF);
					mpre("База данных sqlite не поддерживает изменение полей, поэтому делаем через промежуточную таблицу", $transaction = array(
						"BEGIN TRANSACTION;",
						"CREATE TEMPORARY TABLE backup(". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "{$f['name']} INTEGER PRIMARY KEY" : "{$f['name']} {$f['type']}"); }, $tpl['fields']))). ");",
	//					"INSERT INTO backup SELECT ". implode(", ", array_keys(array_diff_key($tpl['fields'], array_flip(array($f))))). ", {$f} FROM ". mpquot($table). ";",
						"INSERT INTO backup SELECT * FROM ". mpquot($table). ";",
						"DROP TABLE ". mpquot($table). ";",
						"CREATE TABLE ". mpquot($table). "(". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "{$f['name']} INTEGER PRIMARY KEY" : "{$f['name']} {$f['type']}". ($f['dflt_value'] ? " DEFAULT {$f['dflt_value']}" : "")); }, $NF))). ");",
						"INSERT INTO ". mpquot($table). " (". implode(",", array_keys($NF)). ") SELECT ". implode(",", array_keys($tpl['fields'])). " FROM backup;",
						"DROP TABLE backup;",
						"COMMIT;",
					)); foreach($transaction as $sql){ qw($sql); }
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
						"BEGIN TRANSACTION;",
						"CREATE TEMPORARY TABLE backup(". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "{$f['name']} INTEGER PRIMARY KEY" : "{$f['name']} {$f['type']}"); }, $tpl['fields']))). ");",
	//					"INSERT INTO backup SELECT ". implode(", ", array_keys(array_diff_key($tpl['fields'], array_flip(array($f))))). ", {$f} FROM ". mpquot($table). ";",
						"INSERT INTO backup SELECT * FROM ". mpquot($table). ";",
						"DROP TABLE ". mpquot($table). ";",
						"CREATE TABLE ". mpquot($table). "(". implode(",", (array_map(function($f){ return ($f['name'] == "id" ? "{$f['name']} INTEGER PRIMARY KEY" : "{$f['name']} {$f['type']}"); }, $NF))). ");",
						"INSERT INTO ". mpquot($table). " (". implode(",", array_keys($NF)). ") SELECT ". implode(",", array_keys($NF)). " FROM backup;",
						"DROP TABLE backup;",
						"COMMIT;",
					)); foreach($transaction as $sql){ qw($sql); }
				}
			}

			if(empty($tpl['indexes'][$index_name = substr($_GET['r'], strlen($conf['db']['prefix'])). "-{$fld['name']}"]) && array_key_exists($f, $tpl['fields']) && array_key_exists('index', $fld) && $fld['index']){
				qw(mpre("CREATE INDEX `{$index_name}` ON `{$_GET['r']}` (`{$fld['name']}`);"));
				$tpl['indexes'] = indexes($table);
			} $tpl['fields'] = fields($table);
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

	if($new = $_POST['$']){ # Новый элемент
		if($f = $new['name']){
			if($conf['db']['type'] == 'sqlite'){
				qw($sql = "ALTER TABLE ". mpquot($table). " ADD COLUMN {$f} {$new['type']}");
				if(($after = get($new, 'after')) && get($tpl['fields'] = fields($table), $after)){
					$fields = array_map(function($field) use($f){
						return "{$field['name']} {$field['type']}";
					}, $tpl['fields'] = fields($table));

					if(!$nn = array_search($after, array_keys($fields))){ mpre("Не найдено поле за которым устанавливаем новое");
					}elseif(!$NF = array_slice($fields, 0, $nn+1) + [$f=>"{$f} {$new['type']}"] + array_slice($fields, $nn+1)){ mpre("Ошибка формирования списка новых полей");
					}else{// mpre($fields, $NF);
						mpre("База данных sqlite не поддерживает изменение полей, поэтому делаем через промежуточную таблицу", $transaction = array(
							"BEGIN TRANSACTION;",
							"CREATE TEMPORARY TABLE backup(". implode(",", (array_map(function($f){ return (first(explode(" ", $f)) == "id" ? "{$f} PRIMARY KEY" : $f); }, $fields))). ")",
							"INSERT INTO backup SELECT ". implode(", ", array_keys(array_diff_key($tpl['fields'], array_flip(array($f))))). ", {$f} FROM ". mpquot($table). ";",
							"DROP TABLE ". mpquot($table). ";",
							"CREATE TABLE ". mpquot($table). "(". implode(",", (array_map(function($f){ return (first(explode(" ", $f)) == "id" ? "{$f} PRIMARY KEY" : $f); }, $NF))). ")",
							"INSERT INTO ". mpquot($table). " (". implode(", ", array_keys($NF)). ") SELECT ". implode(", ", array_keys($NF)). " FROM backup;",
							"DROP TABLE backup;",
							"COMMIT;",
						)); foreach($transaction as $sql){ qw($sql); }
					}
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
}

foreach($tpl['tables'] = tables() as $table=>$info){
	$info['id'] = (empty($nn) ? ($nn = 1) : ++$nn);
	$info['modpath'] = get($conf, 'modules', first(array_slice(explode("_", $table), 1, 1)), 'folder');
} if($conf['db']['type'] == 'sqlite'){
	$tpl['types'] = array("INTEGER", "REAL", "TEXT");
}else{
	$tpl['types'] = array("int(11)", "smallint(6)", "bigint(20)", "float", "varchar(255)", "text", "longtext");
}

