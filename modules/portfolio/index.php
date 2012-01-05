<? die;

$conf['tpl']['list'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']} ORDER BY id DESC LIMIT ".(int)($_GET['p']*5).", 5"));
$conf['tpl']['pcount'] = mpql(mpqw("SELECT FOUND_ROWS()/5 as pcount"), 0, 'pcount');

?>