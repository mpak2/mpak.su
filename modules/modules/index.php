<? die;

$conf['tpl']['modules'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}"));

?>