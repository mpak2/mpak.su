<? die;

$conf['tpl']['budgets'] = mpql(mpqw("SELECT l.* FROM {$conf['db']['prefix']}{$arg['modpath']}_list AS l LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_list_cat AS lc ON l.id=lc.lid WHERE 1=1". ($_GET['cid'] ? " AND lc.cid=".(int)$_GET['cid'] : ''). ($_GET['id'] ? " AND l.id=".(int)$_GET['id'] : ''). " ORDER BY l.id DESC"));
foreach($conf['tpl']['budgets'] as $k=>$v){
	$conf['tpl']['img'][$v['id']] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_img WHERE lid=".(int)$v['id']));
}

?>