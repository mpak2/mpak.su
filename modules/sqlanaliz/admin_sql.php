<?

if(array_key_exists("null", $_GET) && $_POST){
	if($sql = $_POST['sql']){
		exit(mpre($sql, qn($sql)));
	}elseif(($table = $_POST['del']) && ($fields = fields($table))){
		exit(qw("DROP TABLE `{$table}`"));
	}elseif($table = $_POST['table']){
		qw("CREATE TABLE `$table` (
			id INT(11) AUTO_INCREMENT PRIMARY KEY,
			time INT(11) NOT NULL,
			uid INT(11) NOT NULL,
			name VARCHAR(255) NOT NULL
		)"); exit(json_encode(array("table"=>$table)));
	} exit(mpre("Ошибочный запрос", $_POST));
}elseif(($table = $_GET['r']) && ($fields = fields($table)) && is_array($fil = $_POST['f'])){
	foreach($fil as $f=>$fld){
		if(!$fld['name']){
			qw($sql = "ALTER TABLE `". mpquot($table). "` DROP COLUMN `". mpquot($f). "`"); mpre($sql);
		}elseif($fld['after'] || ($fields[$f]['Type'] != $fld['type']) || ($fields[$f]['Comment'] != $fld['comment']) || ($fields[$f]['Default'] != $fld['default']) || ($fld['name'] && ($fld['name'] != $f))){
			qw($sql = "ALTER TABLE `". mpquot($table). "` CHANGE `". mpquot($f). "` `". mpquot($fld['name'] ?: $f). "` ". mpquot($fld['type'] ?: $fields[$f]['Type']). ($fields[$f]['Null'] == "NO" ? " NOT NULL" : " NULL"). ($fld['default'] ? " DEFAULT '". mpquot($fld['default']). "'" : ""). " COMMENT '". mpquot($fld['comment']). "'"); mpre($sql);
		}
	} if($new = $_POST['$']){
		if($f = $new['name']){
			qw($sql = "ALTER TABLE `". mpquot($table). "` ADD `". mpquot($f). "` ". mpquot($new['type']). " NOT NULL ". ($new['default'] ? " DEFAULT '". mpquot($new['default']). "'" : ""). " COMMENT '". mpquot($new['comment']). "' AFTER `". mpquot($new['after']). "`"); mpre($sql);
		}
	}
}

foreach($tpl['tables'] = tables() as $table=>&$info){
	$info['id'] = ++$nn;
	$info['modpath'] = $conf['modules'][ array_shift(array_slice(explode("_", $table), 1, 1)) ]['folder'];
} if($table = $_GET['r']){
	$tpl['fields'] = fields($table);
}

