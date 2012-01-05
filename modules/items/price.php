<? die;

$conf['tpl']['price'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_index LIMIT ".($_GET['p']*30). ", 30"));
$conf['tpl']['cnt'] = mpql(mpqw(" SELECT FOUND_ROWS() AS cnt"), 0, 'cnt');

?>