<? die;

//$_GET['id.id'] = $_GET['id']; unset($_GET['id']);
//$_GET['id.cat_id'] = $_GET['cat_id']; unset($_GET['cat_id']);

$conf['tpl']['vacancy'] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS id.*, u.name AS uname FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index"). " AS id LEFT JOIN {$conf['db']['prefix']}users AS u ON id.uid=u.id WHERE 1". ($_GET['cat_id'] ? " AND id.cat_id=". (int)$_GET['cat_id'] : ""). ($_GET['id'] ? " AND id.id=". (int)$_GET['id'] : ""). " LIMIT ".($_GET['p']*10).", 10"));

$conf['tpl']['cat'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat WHERE id=". ($_GET['cat_id'] ?: $conf['tpl']['vacancy'][0]["cat_id"]). " ORDER BY name"), 0);

$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/10 AS pcount"), 0, 'pcount'));

?>