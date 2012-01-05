<? die;

$conf['tpl']['cat'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat"));

$conf['tpl']['index'] = mpqn(mpqw("SELECT id, kid, name FROM {$conf['db']['prefix']}{$arg['modpath']}_index". ($_GET['uid'] ? " WHERE uid=". (int)$_GET['uid'] : "")), 'kid', 'id');

//mpre($conf['tpl']['index']);

/*	$conf['tpl']['cat'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat WHERE id=".(int)$_GET['cid']), 0);
	$conf['tpl']['res'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM  {$conf['db']['prefix']}{$arg['modpath']}_index WHERE kid=".(int)$_GET['cid']." ORDER BY id DESC LIMIT ".((int)$_GET['p']*50). ", 50"));
	$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/50 AS cnt"), 0, 'cnt'));
	$conf['tpl']['all'] = mpql(mpqw("SELECT c.*, COUNT(*) as count FROM {$conf['db']['prefix']}{$arg['modpath']}_cat as c, {$conf['db']['prefix']}{$arg['modpath']}_index as p WHERE c.id=p.kid AND parent=".(int)$_GET['cid']." GROUP BY c.id"));*/

?>