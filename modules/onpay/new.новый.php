<? die;

if($_POST['sum']){
	$mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_operations", $_POST);
	mpqw($sql = "INSERT INTO $tn SET $mpdbf, date=NOW(), type=\"". ($_GET['type'] ? $_GET['type'] : $arg['modpath'])."\", uid=".(int)($_GET['uid'] ?: $conf['user']['uid']));// echo $sql; exit;
	if($operations_id = mysql_insert_id()){
		mpevent("Создание нового платежа", $operations_id, $conf['user']['uid']);
	} header("Location: /{$arg['modpath']}:new/". (int)$operations_id);
}elseif($_GET['id']){
	$conf['tpl']['operation'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_operations".($_GET['id'] ? " WHERE id=".(int)$_GET['id'] : '')." ORDER BY id DESC LIMIT 1"), 0);
}

?>