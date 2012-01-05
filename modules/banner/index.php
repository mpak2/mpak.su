<? die;

if(array_key_exists('add', $_GET)){
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} SET uid=". (int)$conf['user']['uid']);
	header("Location: /{$arg['modpath']}". ($arg['fn'] != 'index' ? ":{$arg['fn']}/" : '/'). mysql_insert_id());
}elseif(array_key_exists('del', $_GET) && (int)$_GET['id']){
	$index = mpql(mpqw("SELECT * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"). " WHERE id=".(int)$_GET['id']), 0);
	if(($index['uid'] == $conf['user']['uid']) || ($arg['access'] >= 5)){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE id=". (int)$_GET['id']. " AND uid=". (int)$conf['user']['uid']);
		header("Location: /{$arg['modpath']}");
	}
}

if($_GET['id'] && array_key_exists('value', $_POST) && array_key_exists('id', $_POST) && $_POST){
	$index = mpql(mpqw("SELECT * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"). " WHERE id=".(int)$_GET['id']), 0);
	if(array_key_exists($_POST['id'], $index) && ((($index['uid'] == $conf['user']['uid']) || ($index['uid'] == -$conf['user']['sess']['id']) || ($arg['access'] >= 4)))){
		mpqw("UPDATE {$tn} SET ". mpquot($_POST['id'])."=\"". mpquot($_POST['value']). "\" WHERE id=".(int)$_GET['id']);
		if(mysql_affected_rows() == 1){
			if(substr($_POST['id'], -3) == '_id'){
				echo mpql(mpqw("SELECT name FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($_POST['id'], 0, strlen($_POST['id'])-3). " WHERE id=". (int)$_POST['value']), 0, 'name');
			}else{
				echo $_POST['value'];
			}
		}else{
			echo 'save error';
		}
	}else{
		echo 'access error';
	} exit;
}


if($_GET['id']){
	$conf['tpl'][$arg['fn']] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE id=".(int)$_GET['id']), 0);
	foreach($conf['tpl'][$arg['fn']] as $k=>$v){
		if(substr($k, -3) == '_id'){
			$conf['tpl'][$k] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($k, 0, strlen($k)-3));
		}elseif(substr($k, -4) == '_mid'){
			$conf['tpl'][$k] = spisok("SELECT mid.id, mid.name FROM {$conf['db']['prefix']}{$arg['modpath']}_index_". ($mid = substr($k, 0, strlen($k)-4)). " AS im LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_$mid AS mid ON im.{$mid}_id=mid.id WHERE im.index_id=". (int)$_GET['id']);
		}
	}
	$sql = "SELECT * FROM information_schema.KEY_COLUMN_USAGE WHERE `TABLE_SCHEMA`=\"{$conf['db']['name']}\" AND `POSITION_IN_UNIQUE_CONSTRAINT`=1 AND `REFERENCED_TABLE_NAME`=\"{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}\"";
	foreach(mpqn(mpqw($sql), 'COLUMN_NAME') as $k=>$v){
		$conf['tpl']['fk'][ substr($v['TABLE_NAME'], strlen("{$conf['db']['prefix']}{$arg['modpath']}_")) ] = mpql(mpqw("SELECT * FROM {$v['TABLE_NAME']} WHERE {$v['COLUMN_NAME']}=". (int)$_GET['id']));
	}// mpre($conf['tpl']['fk']);
}else{
	$conf['tpl'][$arg['fn']] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"). mpwr($tn)));
	$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/20 AS cnt"), 0, 'cnt'));

//	foreach(array('punkt_zagruzki_id', 'punkt_razgruzki_id') as $k=>$v){
//		$conf['tpl'][$v] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($v, 0, strlen($v)-3));
//	}
}

?>