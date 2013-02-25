<? die;

//if($_GET['id']) $conf['settings']['title'] = $conf['tpl'][$arg['fn']]['0']['name'];

$conf['tpl'][$arg['fn']] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}".($_GET['id'] ? " WHERE id=".(int)$_GET['id'] : '')." ORDER BY name LIMIT ".($_GET['p']*10).",10"));

$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/10 AS cnt"), 0, 'cnt'));
$conf['tpl']['count'] = spisok("SELECT s.id, COUNT(*) FROM {$conf['db']['prefix']}{$arg['modpath']}_sity AS s, {$conf['db']['prefix']}{$arg['modpath']}_desc AS d WHERE s.id=d.sity_id GROUP BY s.id");
//$conf['tpl']['comments'] = spisok("SELECT u.num, COUNT(*) AS cnt FROM {$conf['db']['prefix']}comments_url AS u, {$conf['db']['prefix']}comments_txt AS t WHERE u.id=t.uid AND u.url LIKE '%/{$arg['modpath']}/%' GROUP BY u.id");

?>