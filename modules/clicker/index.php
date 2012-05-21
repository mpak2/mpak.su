<? die;

$tpl['lesson'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_lesson"));

$tpl['index'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE lesson_id=". (int)$_GET['lesson_id']));

?>