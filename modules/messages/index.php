<? die;

if ((int)$_GET['del']){
	mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE addr={$conf['user']['uid']} AND id=".(int)$_GET['del']);
}

if($_GET['read'] == 1){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET open=1");
}elseif ($_GET['id'] && !$conf['tpl']['mess']['0']['open']){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET open=1 WHERE id=".(int)$_GET['id']);
}

$conf['tpl']['mess'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS m.*, u.name, u2.name AS adname FROM {$conf['db']['prefix']}{$arg['modpath']} as m LEFT JOIN {$conf['db']['prefix']}users as u ON m.uid=u.id LEFT JOIN {$conf['db']['prefix']}users as u2 ON m.addr=u2.id WHERE (m.addr=". (int)$conf['user']['uid']." OR m.uid=". (int)$conf['user']['uid']. ")".($_GET['id'] ? " AND m.id<=".(int)$_GET['id']. " ORDER BY time DESC LIMIT 2" : " ORDER BY time DESC LIMIT ". ((int)$_GET['p']*15).", 15")));

$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/15 AS cnt"), 0, 'cnt'));

?>
