<?

if($table = get($_GET, 'r')){
	$tpl['fields'] = fields($table);
	$tpl['indexes'] = indexes($table);
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
		if($cmd = "mysqldump -u{$conf['db']['name']} -p{$conf['db']['pass']} {$conf['db']['name']} ". implode(" ", array_keys($dump))){
			header("Content-Disposition: attachment; filename=". ((count($dump) == 1) ? first(array_keys($dump)) : $conf['db']['name']). ".sql");
			exit(passthru($cmd));
		}
	}else{
		foreach($dump as $t=>$v){
			$tpl['dump'][$t] = `mysqldump -u{$conf['db']['name']} -p{$conf['db']['pass']} {$conf['db']['name']} $t`;
		}
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
				qw("CREATE TABLE `$table` (id PRIMARY KEY, time INTEGER, uid INTEGER, name TEXT)");
			}else{
				qw("CREATE TABLE `$table` (
					id INT(11) AUTO_INCREMENT PRIMARY KEY,
					time INT(11) NOT NULL,
					uid INT(11) NOT NULL,
					name VARCHAR(255) NOT NULL
				) CHARACTER SET utf8 COLLATE utf8_unicode_ci");
			} exit(json_encode(array("table"=>$table)));
		} exit(mpre("Ошибочный запрос", $_POST));
	}
}elseif(($table = get($_GET, 'r')) && ($fields = fields($table)) && array_key_exists('f', $_POST) && is_array($fil = $_POST['f'])){
	foreach($fil as $f=>$fld){
		if(!$fld['name']){
			if($conf['db']['type'] == 'sqlite'){
				$fields = array_map(function($field) use($f){
					return "{$field['name']} {$field['type']}";
				}, array_diff_key($tpl['fields'], array_flip(array($f))));
				mpre("База данных sqlite не поддерживает удаление полей, поэтому делаем через промежуточную таблицу", $transaction = array(
					"BEGIN TRANSACTION;",
					"CREATE TEMPORARY TABLE backup(". implode(",", $fields). ");",
					"INSERT INTO backup SELECT ". implode(", ", array_keys(array_diff_key($tpl['fields'], array_flip(array($f))))). " FROM ". mpquot($table). ";",
					"DROP TABLE ". mpquot($table). ";",
					"CREATE TABLE ". mpquot($table). "(". implode(",", $fields). ");",
					"INSERT INTO ". mpquot($table). " SELECT * FROM backup;",
					"DROP TABLE backup;",
					"COMMIT;",
				)); foreach($transaction as $sql){
					qw($sql);
				}
			}else{
				qw($sql = "ALTER TABLE `". mpquot($table). "` DROP COLUMN `". mpquot($f). "`"); mpre($sql);
			} $tpl['fields'] = fields($table);
		}else if($conf['db']['type'] == 'sqlite'){
			if(($fields[$f]['type'] != $fld['type']) || ($fields[$f]['name'] != $fld['name'])){
				$fields = array_map(function($field) use($f){
					return "{$field['name']} {$field['type']}";
				}, array_diff_key($tpl['fields'], array_flip(array($f))));
				mpre("База данных sqlite не поддерживает изменение полей, поэтому делаем через промежуточную таблицу", $transaction = array(
					"BEGIN TRANSACTION;",
					"CREATE TEMPORARY TABLE backup(". implode(",", $fields). ", {$fld['name']} {$fld['type']});",
					"INSERT INTO backup SELECT ". implode(", ", array_keys(array_diff_key($tpl['fields'], array_flip(array($f))))). ", {$f} FROM ". mpquot($table). ";",
					"DROP TABLE ". mpquot($table). ";",
					"CREATE TABLE ". mpquot($table). "(". implode(",", $fields). ", {$fld['name']} {$fld['type']});",
					"INSERT INTO ". mpquot($table). " SELECT * FROM backup;",
					"DROP TABLE backup;",
					"COMMIT;",
				)); foreach($transaction as $sql){
					qw($sql);
				}
			} $tpl['fields'] = fields($table);
		}else if($fld['after'] || ($fields[$f]['Type'] != $fld['type']) || ($fields[$f]['Comment'] != $fld['comment']) || ($fields[$f]['Default'] != $fld['default']) || ($fld['name'] && ($fld['name'] != $f))){
			qw($sql = ("ALTER TABLE `". mpquot($table). "` CHANGE `". mpquot($f). "` `". mpquot($fld['name'] ?: $f). "` ". mpquot($fld['type'] ?: $fields[$f]['Type']). ($fields[$f]['Null'] == "NO" ? " NOT NULL" : " NULL"). ($fld['default'] ? " DEFAULT ". ($fld['default'] == "NULL" ? mpquot($fld['default']) : "'". mpquot($fld['default']). "'") : ""). " COMMENT '". mpquot($fld['comment']). "'")); mpre($sql);
			$tpl['fields'] = fields($table);
		} if(empty($tpl['indexes'][$fld['name']]) && array_key_exists($f, $tpl['fields']) && array_key_exists('index', $fld) && $fld['index']){
			if($conf['db']['type'] == 'sqlite'){
				qw(mpre("CREATE INDEX {$fld['name']} ON `{$_GET['r']}` ({$fld['name']});"));
			}else{
				qw($sql = "ALTER TABLE `{$_GET['r']}` ADD INDEX (`{$fld['name']}`)"); mpre($sql);
			}
//			$tpl['indexes'] = qn("SHOW INDEXES IN {$_GET['r']}", "Column_name");
			$tpl['indexes'] = indexes($table);
		}elseif(!array_key_exists('index', $fld) && array_key_exists($fld['name'], $tpl['indexes']) && $tpl['indexes'][$fld['name']]){
			qw($sql = "DROP INDEX `{$tpl['indexes'][$fld['name']]['Key_name']}` ON `{$_GET['r']}`"); mpre($sql);
//			$tpl['indexes'] = qn("SHOW INDEXES IN {$_GET['r']}", "Column_name");
			$tpl['indexes'] = indexes($table);
		}
	} if($new = $_POST['$']){
		if($f = $new['name']){
			if($conf['db']['type'] == 'sqlite'){
				qw("ALTER TABLE ". mpquot($table). " ADD COLUMN {$f} {$new['type']}");
			}else{
				qw($sql = "ALTER TABLE `". mpquot($table). "` ADD `". mpquot($f). "` ". mpquot($new['type']). " NOT NULL ". ($new['default'] ? " DEFAULT '". mpquot($new['default']). "'" : ""). " COMMENT '". mpquot($new['comment']). "' AFTER `". mpquot($new['after']). "`"); mpre($sql);
				$tpl['fields'] = fields($table);
				if(array_key_exists("index", $new) && $new['index']){
					qw($sql = "ALTER TABLE `". mpquot($table). "` ADD INDEX (`". mpquot($f). "`)"); mpre($sql);
				}
			} $tpl['indexes'] = indexes($table);
		}
	}
}

foreach($tpl['tables'] = tables() as $table=>$info){
	$info['id'] = (empty($nn) ? ($nn = 1) : ++$nn);
	$info['modpath'] = get($conf, 'modules', first(array_slice(explode("_", $table), 1, 1)), 'folder');
} if($conf['db']['type'] == 'sqlite'){
	$tpl['types'] = array("TEXT", "INTEGER");
}else{
	$tpl['types'] = array("int(11)", "smallint(6)", "bigint(20)", "float", "varchar(255)", "text", "longtext");
}

