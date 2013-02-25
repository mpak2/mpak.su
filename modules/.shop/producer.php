<? die;

//if($_GET['id']) $conf['settings']['title'] = $conf['tpl'][$arg['fn']]['0']['name'];

$conf['tpl'][$arg['fn']] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}".($_GET['id'] ? " WHERE id=".(int)$_GET['id'] : '')." ORDER BY id DESC LIMIT ".($_GET['p']*10).",10"));
$conf['tpl']['sity'] = spisok("SELECT id, name FROM {$conf['db']['prefix']}{$arg['modpath']}_sity");
$conf['tpl']['cnt'] = mpql(mpqw("SELECT FOUND_ROWS()/10 AS cnt"), 0, 'cnt');
$conf['tpl']['comments'] = spisok("SELECT u.num, COUNT(*) AS cnt FROM {$conf['db']['prefix']}comments_url AS u, {$conf['db']['prefix']}comments_txt AS t WHERE u.id=t.uid AND u.url LIKE '%/{$arg['modpath']}/%' GROUP BY u.id");

?>