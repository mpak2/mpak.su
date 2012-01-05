<? die;

$conf['tpl']['glass'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}"));
$conf['tpl']['count'] = spisok("SELECT g.id, COUNT(*) as count FROM {$conf['db']['prefix']}{$arg['modpath']} as g, {$conf['db']['prefix']}{$arg['modpath']}_desc as d WHERE g.id=d.gid GROUP BY g.id");
$conf['tpl']['desc'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_desc  WHERE 1=1".($_GET['id'] ? " AND gid=".(int)$_GET['id'] : '').($_GET['did'] ? " AND id=".(int)$_GET['did'] : '')." LIMIT ".((int)$_GET['p']*10).",10"));
$conf['tpl']['pcount'] = mpql(mpqw("SELECT FOUND_ROWS()/10 AS pcount"), 0, 'pcount');

?>