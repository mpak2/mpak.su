<? die;

$tpl['index'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY id DESC"));
