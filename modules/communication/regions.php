<? die;

$conf['tpl']['region'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_region WHERE 1". ($_GET['id'] ? " AND id=". (int)$_GET['id'] : '')));

?>