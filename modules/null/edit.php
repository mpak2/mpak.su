<? die;

if($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$_GET['tn']}"){
	if($_POST && $mpdbf = mpdbf($tn, $_POST)){
		if(($arg['access'] >= 3) && $_POST['id']){
			mpqw($sql = "UPDATE $tn SET $mpdbf WHERE id=". (int)$_POST['id']. " AND uid=". (int)$conf['user']['uid']);
		}elseif($arg['access'] >= 2){
			mpqw($sql = "INSERT INTO $tn SET $mpdbf, uid=". (int)$conf['user']['uid']);
		}else{
			echo "Доступ запрещен";
		}
//echo $sql;
//		if(mysql_affected_rows() == 1){
			header("Location: /{$arg['modpath']}:{$_GET['tn']}");
//		}
	}

	if((int)$_GET['del'] && $arg['access'] >= 3){
		mpqw("DELETE FROM $tn WHERE id=". (int)$_GET['del']. " AND uid = ". (int)$conf['user']['uid']);
		if(mysql_affected_rows() == 1){
			header("Location: /{$arg['modpath']}:{$_GET['tn']}");
		}
	}

	if($arg['access'] > 0){
		$conf['tpl']['field'] = spisok("SELECT COLUMN_NAME, COLUMN_TYPE FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = \"{$tn}\"");
		if($_GET['id']){
			$conf['tpl']['edit'] = mpql(mpqw($sql = "SELECT * FROM $tn WHERE id=". (int)$_GET['id']), 0);
		}else{
			$conf['tpl']['edit'] = spisok("SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.Columns WHERE TABLE_NAME = \"{$tn}\"");
		}// mpre($conf['tpl']['gruz']);

		foreach($conf['tpl']['edit'] as $k=>$v){
			if(substr($k, -3) == '_id'){
				$fn = substr($k, 0, strlen($k)-3);
				$conf['tpl'][$k] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_{$fn}". ($fn == 'firms' ? " WHERE uid=". (int)$conf['user']['uid'] : ''));
			}
		}
	}
}

?>