<?

$conf['tpl'][$arg['fn']] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE 1=1".($_GET['id'] ? " AND id=".(int)$_GET['id'] : ''). ($arg['admin_access'] < 5 ? " AND uid=".(int)$conf['user']['uid'] : ''). " ORDER BY id DESC LIMIT ".($_GET['p']*10).",10"));
$conf['tpl']['cnt'] = mpql(mpqw("SELECT FOUND_ROWS()/10 AS cnt"), 0, 'cnt');

?>
