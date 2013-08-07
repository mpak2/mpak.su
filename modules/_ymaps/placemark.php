<? die;

if(array_key_exists('add', $_GET)){
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} SET uid=". (int)$conf['user']['uid']);
	header("Location: /{$arg['modpath']}/". mysql_insert_id());
}elseif(array_key_exists('del', $_GET) && (int)$_GET['id']){
	$index = mpql(mpqw("SELECT * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"). " WHERE id=".(int)$_GET['id']), 0);
	if(($index['uid'] == $conf['user']['uid']) || ($arg['access'] >= 5)){
		mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE id=". (int)$_GET['id']. " AND uid=". (int)$conf['user']['uid']);
		header("Location: /{$arg['modpath']}");
	}
}


if(array_key_exists('value', $_POST)){
	$index = mpql(mpqw($sql = "SELECT * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"). " WHERE id=".(int)$_POST['pid']), 0);
	if(array_key_exists($_POST['id'], $index) && ($index['uid'] == $conf['user']['uid'])){
		mpqw($sql = "UPDATE {$tn} SET ". mpquot($_POST['id'])."=\"". mpquot($_POST['value']). "\" WHERE id=".(int)$_POST['pid']);
		if(mysql_affected_rows() == 1){
			if(substr($_POST['id'], -3) == '_id'){
				echo mpql(mpqw("SELECT name FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($_POST['id'], 0, strlen($_POST['id'])-3). " WHERE id=". (int)$_POST['value']), 0, 'name');
			}elseif($_POST['id'] == 'status'){
				$status = array('Включен', 'Выключен');
				echo $status[$_POST['value']];
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
//	mpre($conf['tpl'][$arg['fn']]);
	foreach($conf['tpl'][$arg['fn']] as $k=>$v){
		if(substr($k, -3) == '_id'){
			$conf['tpl'][$k] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($k, 0, strlen($k)-3));
		}
	}
	if($arg['fn'] != 'index'){ # Выборка всех ключей и мультиключей
		if(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}_index_{$arg['fn']}%\""))){
			$conf['tpl']['index'] = mpql(mpqw("SELECT id.* FROM {$conf['db']['prefix']}{$arg['modpath']}_index_{$arg['fn']} AS mid LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON id.id=mid.index_id AND mid.{$arg['fn']}_id=". (int)$_GET['id']));
		}else{
//			$conf['tpl']['index'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE {$arg['fn']}_id=". (int)$_GET['id']));
		}
	}
}else{
	$conf['tpl'][$arg['fn']] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS fn.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} AS fn LEFT JOIN {$conf['db']['prefix']}users AS u ON fn.uid=u.id WHERE 1=1".
		(array_key_exists('uid', $_GET) ? " AND uid=". (int)($_GET['uid'] ?: $conf['user']['uid']) : '').
		($_GET['id'] ? " AND id=".(int)$_GET['id'] : " ORDER BY id DESC LIMIT ".($_GET['p']*20).",20")
	));// mpre($conf['tpl'][$arg['fn']]);
//mpre($conf['tpl'][$arg['fn']]);
	$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/20 AS cnt"), 0, 'cnt'));

//	$conf['tpl']['kuzov_type_id'] = spisok("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_kuzov_type");
//	$conf['tpl']['index_cnt'] = spisok("SELECT iu.uslug_id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_index_uslug AS iu, {$conf['db']['prefix']}{$arg['modpath']}_index AS id WHERE iu.index_id=id.id GROUP BY iu.uslug_id");
}

?>