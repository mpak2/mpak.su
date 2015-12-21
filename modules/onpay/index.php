<?

if($_GET['id']){
	$conf['tpl']['operation'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_operations".($_GET['id'] ? " WHERE id=".(int)$_GET['id'] : '')." ORDER BY id DESC LIMIT 1"), 0);
} $conf['tpl']['money'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_balances WHERE uid=".(int)$conf['user']['uid']), 0);

//$conf['tpl']['operations'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_operations WHERE uid=".(int)$conf['user']['uid']." ORDER BY id DESC LIMIT ".((int)$_GET['p']*10).",10"));
//$conf['tpl']['pcount'] = mpql(mpqw("SELECT FOUND_ROWS() AS count"), 0, 'count');

//mpre($conf['tpl']['money']);

?>
