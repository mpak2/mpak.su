<? die;

$_REQUEST += $_GET;
if($_REQUEST['sum']){
	$mpdbf = mpdbf($tn = "{$conf['db']['prefix']}{$arg['modpath']}_operations", $_REQUEST);
	mpqw($sql = "INSERT INTO $tn SET $mpdbf, date=NOW(), type=\"". ($_GET['type'] ? $_GET['type'] : $arg['modpath'])."\", uid=".(int)$conf['user']['uid']);
	header("Location: /{$arg['modpath']}:new/". mysql_insert_id());
}elseif($_GET['id']){
	$conf['tpl']['operation'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_operations".($_GET['id'] ? " WHERE id=".(int)$_GET['id'] : '')." ORDER BY id DESC LIMIT 1"), 0);
}

?>