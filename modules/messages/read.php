<?

$tpl['mess'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id=".(int)$_GET['id']), 0);
$tpl['user'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE id={$mess['uid']}"), 0);
if (!$mess['open']) mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']} SET open=1 WHERE uid={$conf['user']['uid']} AND id=".(int)$_GET['id']);

?>
