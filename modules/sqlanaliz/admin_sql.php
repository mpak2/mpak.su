<?

if(array_key_exists("null", $_GET) && $_POST){
	if(($table = $_POST['del']) && ($fields = fields($table))){
		exit(qw("DROP TABLE `{$table}`"));
	}elseif($table = $_POST['table']){
		qw("CREATE TABLE `$table` (
			id INT(8) AUTO_INCREMENT PRIMARY KEY,
			time INT(8) NOT NULL,
			uid INT(8) NOT NULL,
			name VARCHAR(255) NOT NULL
		)"); exit(json_encode(array("table"=>$table)));
	} exit(mpre("Ошибочный запрос", $_POST));
}elseif(($table = $_GET['r']) && ($fields = fields($table)) && is_array($fil = $_POST['f'])){
	foreach($fil as $f=>$fld){
		if(!$fld['name']){
			qw($sql = "ALTER TABLE `". mpquot($table). "` DROP COLUMN `". mpquot($f). "`"); mpre($sql);
		}elseif($fld['after']){
			qw($sql = "ALTER TABLE `". mpquot($table). "` CHANGE `". mpquot($f). "` `". mpquot($f). "` ". ($fld['type'] ?: $fields[$f]['Type']). " ". ($fields[$f]['Null'] == "NO" ? " NOT NULL" : " NULL"). " AFTER `". mpquot($fld['after']). "`"); mpre($sql);
		}elseif($fields[$f]['Type'] != $fld['type']){
			qw($sql = "ALTER TABLE `". mpquot($table). "` CHANGE `". mpquot($f). "` `". mpquot($f). "` ". mpquot($fld['type']). " ". ($fields[$f]['Null'] == "NO" ? " NOT NULL" : " NULL")); mpre($sql);
		}
	} if($new = $_POST['$']){
		if($f = $new['name']){
			qw($sql = "ALTER TABLE `". mpquot($table). "` ADD `". mpquot($f). "` ". mpquot($new['type']). " NOT NULL AFTER `". mpquot($new['after']). "`"); mpre($sql);
		}
	}
}

foreach($tpl['tables'] = tables() as $table=>&$info){
	$info['id'] = ++$nn;
	$info['modpath'] = $conf['modules'][ array_shift(array_slice(explode("_", $table), 1, 1)) ]['folder'];
} if($table = $_GET['r']){
	$tpl['fields'] = fields($table);
}

