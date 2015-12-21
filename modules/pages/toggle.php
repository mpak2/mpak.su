<?

$conf['tpl']['pages'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE kid=". (int)$_GET['id']));

?>
