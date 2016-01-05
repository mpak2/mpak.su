<?

if($table = get($_GET, 'r')){
	$tpl['fields'] = fields($table);
	$tpl['indexes'] = qn("SHOW INDEXES IN {$_GET['r']}", "Column_name");
}

if(array_key_exists("null", $_GET) && $_POST){
	if($sql = $_POST['sql']){
		if($mpqw = mpqw($sql)){
			exit(mpre("Результат вывода запроса", mpql($mpqw)));
		}else{ exit(mpre("Запрос не предполагает вывода", $sql)); }
	}elseif(($table = $_POST['del']) && ($fields = fields($table))){
		exit(qw("DROP TABLE `{$table}`"));
	}elseif($table = $_POST['table']){
		qw("CREATE TABLE `$table` (
			id INT(11) AUTO_INCREMENT PRIMARY KEY,
			time INT(11) NOT NULL,
			uid INT(11) NOT NULL,
			name VARCHAR(255) NOT NULL
		) CHARACTER SET utf8 COLLATE utf8_unicode_ci"); exit(json_encode(array("table"=>$table)));
	} exit(mpre("Ошибочный запрос", $_POST));
}elseif(($table = $_GET['r']) && ($fields = fields($table)) && array_key_exists('f', $_POST) && is_array($fil = $_POST['f'])){
	foreach($fil as $f=>$fld){
		if(!$fld['name']){
			qw($sql = "ALTER TABLE `". mpquot($table). "` DROP COLUMN `". mpquot($f). "`"); mpre($sql);
			$tpl['fields'] = fields($table);
		}elseif($fld['after'] || ($fields[$f]['Type'] != $fld['type']) || ($fields[$f]['Comment'] != $fld['comment']) || ($fields[$f]['Default'] != $fld['default']) || ($fld['name'] && ($fld['name'] != $f))){
			qw($sql = "ALTER TABLE `". mpquot($table). "` CHANGE `". mpquot($f). "` `". mpquot($fld['name'] ?: $f). "` ". mpquot($fld['type'] ?: $fields[$f]['Type']). ($fields[$f]['Null'] == "NO" ? " NOT NULL" : " NULL"). ($fld['default'] ? " DEFAULT '". mpquot($fld['default']). "'" : ""). " COMMENT '". mpquot($fld['comment']). "'"); mpre($sql);
			$tpl['fields'] = fields($table);
		} if(empty($tpl['indexes'][$fld['name']]) && array_key_exists($f, $tpl['fields']) && array_key_exists('index', $fld) && $fld['index']){
			qw($sql = "ALTER TABLE `{$_GET['r']}` ADD INDEX (`{$fld['name']}`)"); mpre($sql);
			$tpl['indexes'] = qn("SHOW INDEXES IN {$_GET['r']}", "Column_name");
		}elseif(!array_key_exists('index', $fld) && array_key_exists($fld['name'], $tpl['indexes']) && $tpl['indexes'][$fld['name']]){
			qw($sql = "DROP INDEX `{$tpl['indexes'][$fld['name']]['Key_name']}` ON `{$_GET['r']}`"); mpre($sql);
			$tpl['indexes'] = qn("SHOW INDEXES IN {$_GET['r']}", "Column_name");
		}
	} if($new = $_POST['$']){
		if($f = $new['name']){
			qw($sql = "ALTER TABLE `". mpquot($table). "` ADD `". mpquot($f). "` ". mpquot($new['type']). " NOT NULL ". ($new['default'] ? " DEFAULT '". mpquot($new['default']). "'" : ""). " COMMENT '". mpquot($new['comment']). "' AFTER `". mpquot($new['after']). "`"); mpre($sql);
			$tpl['fields'] = fields($table);
			if(array_key_exists("index", $new) && $new['index']){
				qw($sql = "ALTER TABLE `". mpquot($table). "` ADD INDEX (`". mpquot($f). "`)"); mpre($sql);
				$tpl['indexes'] = qn("SHOW INDEXES IN {$_GET['r']}", "Column_name");
			}
		}
	}
}

foreach($tpl['tables'] = tables() as $table=>&$info){
	$info['id'] = (empty($nn) ? ($nn = 1) : ++$nn);
	$info['modpath'] = get($conf, 'modules', first(array_slice(explode("_", $table), 1, 1)), 'folder');
}

