<? die;

$tpl['cat'] = mpqn(mpqw("SELECT c.*, COUNT(DISTINCT i.id) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_cat AS c LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_img AS i ON (c.id=i.kid)"));

$tpl['img'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_img"), 'kid', 'id');

?>