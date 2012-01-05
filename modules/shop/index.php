<? die;

$conf['tpl']['sity'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_sity ORDER BY name");
$conf['tpl']['objs'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_obj ORDER BY name");

if($_GET['id']){
	$conf['tpl']['desc'] = mpql(mpqw("SELECT d.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_desc AS d LEFT JOIN {$conf['db']['prefix']}users AS u ON u.id=d.uid WHERE d.disable=0 AND d.id=".(int)$_GET['id']));
	$conf['settings']['title'] = $conf['tpl']['desc'][0]['name'];
	$conf['tpl']['obj'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj WHERE id=".(int)$conf['tpl']['desc'][0]['oid']), 0);
	$conf['tpl']['img'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_img WHERE desc_id=".(int)$_GET['id']));
}elseif(($max = max($_GET['pid'], $_GET['oid'])) || $_GET['sity_id'] || $_GET['uid']){
	$conf['tpl']['producer'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_producer WHERE id=".(int)$_GET['pid']), 0);
	$conf['tpl']['desc'] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS d.*, u.name AS uname, COUNT(i.id) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_desc AS d LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_img AS i ON d.id=i.desc_id LEFT JOIN {$conf['db']['prefix']}users AS u ON u.id=d.uid WHERE d.disable=0".($max ? " AND d.obj_id=". (int)$max : ''). ($_GET['sity_id'] ? " AND d.sity_id=".(int)$_GET['sity_id'] : ''). ($_GET['uid'] ? " AND d.uid=". (int)$_GET['uid'] : ''). " GROUP BY d.id ORDER BY id DESC LIMIT ".($_GET['p']*5).",5"));
	$conf['tpl']['pcount'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/5 AS cnt"), 0, 'cnt'));
}else{
	$conf['tpl']['obj'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj WHERE obj_id=0 ORDER BY sort"));
	$conf['tpl']['objs'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_obj WHERE obj_id<>0 ORDER BY sort"));
	$conf['tpl']['dcount'] = spisok("SELECT o.id, COUNT(*) AS dcount FROM {$conf['db']['prefix']}{$arg['modpath']}_obj AS o, {$conf['db']['prefix']}{$arg['modpath']}_desc AS d WHERE o.id=d.obj_id AND d.disable=0 GROUP BY o.id");
}

?>