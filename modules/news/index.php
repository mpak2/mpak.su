<?

$tpl['cat'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat");

$tpl['index'] = qn("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE 1". (get($_GET, 'id') ? " AND id=". (int)$_GET['id'] : " ORDER BY time DESC"). " LIMIT ". (get($_GET, 'p')*10). ",10");
$tpl['mpager'] = mpager(ql("SELECT FOUND_ROWS()/10 AS cnt", 0, 'cnt'));

if($n = get($tpl, 'index', get($_GET, 'id')) && !empty($n['count'])){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET count=count+1 WHERE id=". (int)$n['id']);
}

if($index = rb($arg['fn'], "id", get($_GET, 'id'))){ # Загрузка мета информации о странице
	$conf['settings']['title'] = $index['name'];
	if(get($index, 'description')){
		$conf['settings']['description'] = $index['description'];
	} if(get($index, 'keywords')){
		$conf['settings']['keywords'] = $index['keywords'];
	} if(get($index, 'title')){
		$conf['settings']['title'] = $index['title'];
	}
}
