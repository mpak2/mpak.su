<? die;

$conf['tpl']['index'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE LENGTH(name) < 5"));

?>