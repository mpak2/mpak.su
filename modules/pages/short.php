<? die;

	$conf['tpl']['res'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM  {$GLOBALS['conf']['db']['prefix']}{$arg['modpath']}_post WHERE kid=".(int)$_GET['cid']." ORDER BY id DESC LIMIT ".((int)$_GET['p']*50). ", 50"));
	$conf['tpl']['pcount'] = mpql(mpqw("SELECT FOUND_ROWS()/50 as pcount"), 0, 'pcount');
	$conf['tpl']['all'] = mpql(mpqw("SELECT c.*, COUNT(*) as count FROM {$conf['db']['prefix']}{$arg['modpath']}_cat as c, {$conf['db']['prefix']}{$arg['modpath']}_post as p WHERE c.id=p.kid AND parent=".(int)$_GET['cid']." GROUP BY c.id"));

?>