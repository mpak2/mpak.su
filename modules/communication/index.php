<? die;

$conf['tpl'][$arg['fn']] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE 1=1". ($_GET['region_id'] ? " AND region_id=". (int)$_GET['region_id'] : '').($_GET['id'] ? " AND id=".(int)$_GET['id'] : " ORDER BY id DESC LIMIT ".($_GET['p']*10).",10")));
//$conf['tpl']['type_id'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_type");
$conf['tpl']['cnt'] = mpql(mpqw("SELECT FOUND_ROWS()/10 AS cnt"), 0, 'cnt');
$conf['tpl']['region'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_region"));
//$conf['tpl']['comments'] = spisok("SELECT u.num, COUNT(*) AS cnt FROM {$conf['db']['prefix']}comments_url AS u, {$conf['db']['prefix']}comments_txt AS t WHERE u.id=t.uid AND u.url LIKE '%/{$arg['modpath']}/%' GROUP BY u.id");

?>