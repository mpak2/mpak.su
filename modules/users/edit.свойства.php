<?

$conf['tpl']['uid'] = (($arg['admin_access'] >= 5) && ((int)$_GET['id'] ? (int)$_GET['id'] : $conf['user']['uid']));

/*if ($conf['user']['uname'] == $conf['settings']['default_usr']){
	header("Location: /users:deny"); die;
}*/

foreach(mpql(mpqw("DESC {$conf['db']['prefix']}{$arg['modpath']}")) as $k=>$v){
	$f[$v['Field']] = $v['Type'];
}// mpre($f);

$conf['tpl']['user'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id=". (int)$conf['user']['uid']), 0);
$conf['tpl']['fields'] = array_diff_key($conf['tpl']['user'], array_flip(array('id', 'tid', 'img', 'login', 'name', 'pass', 'reg_time', 'last_time', 'param', 'refer', 'flush', 'ref', 'refer_tel', 'http_host')));

foreach($conf['tpl']['fields'] as $k=>$v){
	if(substr($k, -3) == '_id'){
		$conf['tpl'][$k] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($k, 0, strlen($k)-3). " ORDER BY name");
	}
}// mpre($conf['tpl']['fields']);

if($_FILES || $_POST){
	if($_FILES['img']){
		if($fn = mpfn("{$conf['db']['prefix']}{$arg['modpath']}", "img", $conf['user']['uid'])){
			mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET img='". mpquot($fn). "' WHERE id=". (int)$conf['user']['uid']);
			header("Location: /users/0"); exit;
		}
	}elseif($_POST['id'] && array_key_exists('value', $_POST) && array_search('Зарегистрированные', $conf['user']['gid'])){
		if((($conf['user']['uid'] == $conf['tpl']['uid']) || ($arg['admin_access'] >= 5)) && array_key_exists($_POST['id'], $conf['tpl']['fields'])){
			mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET ". mpquot($_POST['id'])."=\"". mpquot($_POST['value']). "\" WHERE id=".(int)$conf['tpl']['uid']);

			if(mysql_affected_rows()){
				if(substr($_POST['id'], -3) == '_id'){
					echo mpql(mpqw("SELECT name FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($_POST['id'], 0, strlen($_POST['id'])-3). " WHERE id=". (int)$_POST['value']), 0, 'name');
				}else{
					echo $_POST['value'];
				}
			}else{
				echo 'save error';
			}
		}
	}else{
		echo "Ошибка доступа";
	}
	exit;
}

?>
