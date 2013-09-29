<? die;

$tpl['cat'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat");

$tpl['index'] = qn("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE 1". ($_GET['id'] ? " AND id=". (int)$_GET['id'] : " ORDER BY time DESC"). " LIMIT ". ($_GET['p']*10). ",10");
$tpl['mpager'] = mpager(ql("SELECT FOUND_ROWS()/10 AS cnt", 0, 'cnt'));

if($n = $tpl['index'][ $_GET['id'] ] && !empty($n['count'])){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET count=count+1 WHERE id=". (int)$n['id']);
}

?>