<? die;

if($_GET['id']){
	$conf['tpl']['obj'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj WHERE id=".(int)$_GET['id']), 0);
	$conf['tpl']['desc'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_desc WHERE oid=".(int)$_GET['id']));
	$conf['tpl']['img'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_img WHERE oid=".(int)$_GET['id']));

	$conf['settings']['title'] = "{$conf['tpl']['obj']['name']} : {$conf['settings']['title']}";
}else{
//	$conf['tpl']['obj'] = mpql(mpqw("SELECT *, SUBSTR(description, 1, 150) AS description FROM {$conf['db']['prefix']}{$arg['modpath']}_obj"));
}

?>