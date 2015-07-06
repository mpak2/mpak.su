<? die;

$tpl['cat'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat");

$tpl['index'] = qn("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE 1". ($_GET['id'] ? " AND id=". (int)$_GET['id'] : " ORDER BY time DESC"). " LIMIT ". ($_GET['p']*10). ",10");
$tpl['mpager'] = mpager(ql("SELECT FOUND_ROWS()/10 AS cnt", 0, 'cnt'));

if($n = $tpl['index'][ $_GET['id'] ] && !empty($n['count'])){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET count=count+1 WHERE id=". (int)$n['id']);
}

if(${$arg['fn']} = rb($arg['fn'], "id", $_GET['id'])){ # Загрузка мета информации о странице
	$conf['settings']['title'] = ${$arg['fn']}['name'];
	if(${$arg['fn']}['description']){
		$conf['settings']['description'] = ${$arg['fn']}['description'];
	} if(${$arg['fn']}['keywords']){
		$conf['settings']['keywords'] = ${$arg['fn']}['keywords'];
	} if(${$arg['fn']}['title']){
		$conf['settings']['title'] = ${$arg['fn']}['title'];
	}
}