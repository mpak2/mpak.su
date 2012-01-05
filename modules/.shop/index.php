<? die;

//$conf['tpl']['sity'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_sity ORDER BY name");
$conf['tpl']['objs'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_obj ORDER BY name");
if($_GET['id']){
	$conf['tpl']['desc'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_desc WHERE disable=0 AND id=".(int)$_GET['id']));
	$conf['tpl']['obj'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj WHERE id=".(int)$conf['tpl']['desc'][0]['oid']), 0);
	$conf['tpl']['img'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_img WHERE did=".(int)$_GET['id']));
}elseif($_GET['pid']){
	$conf['tpl']['producer'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_producer WHERE id=".(int)$_GET['pid']), 0);
	$conf['tpl']['desc'] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_desc WHERE disable=0 AND pid=".(int)$_GET['pid']." LIMIT ".($_GET['p']*4).",5"));
	$conf['tpl']['pcount'] = mpql(mpqw("SELECT FOUND_ROWS()/5 AS pcount"), 0, 'pcount');
}elseif($_GET['oid']){
	$conf['tpl']['obj'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj WHERE id=".(int)$_GET['oid']), 0);
	$conf['tpl']['desc'] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_desc WHERE disable=0 AND oid=".(int)$_GET['oid']." LIMIT ".($_GET['p']*4).",5"));
	$conf['tpl']['pcount'] = mpql(mpqw("SELECT FOUND_ROWS()/5 AS pcount"), 0, 'pcount');
}elseif($_GET['sity_id']){
	$conf['tpl']['desc'] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_desc WHERE disable=0 AND sity_id=".(int)$_GET['sity_id']." LIMIT ".($_GET['p']*4).",5"));
	$conf['tpl']['pcount'] = mpql(mpqw("SELECT FOUND_ROWS()/5 AS pcount"), 0, 'pcount');
}else{
	$conf['tpl']['obj'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj WHERE pid=0 ORDER BY sort"));
	$conf['tpl']['objs'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj WHERE pid<>0 ORDER BY sort"));
	$conf['tpl']['dcount'] = spisok("SELECT o.id, COUNT(*) AS dcount FROM {$conf['db']['prefix']}{$arg['modpath']}_obj AS o, {$conf['db']['prefix']}{$arg['modpath']}_desc AS d WHERE o.id=d.oid AND d.disable=0 GROUP BY o.id");
}

?>