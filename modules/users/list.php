<?

$tpl['list'] = mpqn(mpqw("SELECT u.* FROM {$conf['db']['prefix']}{$arg['modpath']} AS u INNER JOIN {$conf['db']['prefix']}sess AS s ON (u.id=s.uid) ORDER BY s.last_time DESC LIMIT 30"));

?>
