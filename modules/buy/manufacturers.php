<? die;

$tpl['manufacturers'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_manufacturers ORDER BY sort"));

?>