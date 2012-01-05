<? die;

if($_GET['id']){
	$conf['tpl']['faq'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE `hide`=0 AND cat_id=".(int)$_GET['id']. " ORDER BY `sort`"));
}elseif($_GET['uid']){
	$conf['tpl']['user'] = mpql(mpqw("SELECT id, name FROM {$conf['db']['prefix']}users WHERE id=". (int)$_GET['uid']), 0);
	$conf['tpl']['faq'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE `hide`=0 AND uid=". (int)$_GET['uid']. " ORDER BY `sort`"));
}else{
	$conf['tpl']['cat'] = mpqn(mpqw("SELECT cat.*, COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_cat AS cat INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS faq ON cat.id=faq.cat_id GROUP BY cat.id"));
}

?>