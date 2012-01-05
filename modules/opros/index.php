<? die;

if($_GET['aid'] && $arg['access'] >=4){
	$conf['tpl']['anket'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_anket WHERE id=".(int)$_GET['aid']), 0);
}// mpre($conf['tpl']['anket']);

if($_GET['id'] && empty($_GET['oid']) && $_POST){
	if(!$conf['tpl']['anket']['id']){
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_anket SET time=".time().", oid=".(int)$_GET['id'].", sid=".(int)$conf['user']['sess']['id']. ", uid=". (int)$conf['user']['uid']);
		$conf['tpl']['anket'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_anket WHERE id=".(int)mysql_insert_id()), 0);
		$conf['tpl']['order_id'] = mysql_insert_id();
	}// mpre($conf['tpl']['anket']);

	if($conf['tpl']['order_id']){
		$conf['tpl']['vopros'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_vopros WHERE oid=".(int)$_GET['id']));
		foreach($conf['tpl']['vopros'] as $k=>$v){
			mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_result SET aid=".(int)$conf['tpl']['anket']['id'].", vid=".(int)$v['id'].", value=\"".mpquot($_POST[$v['id']])."\" ON DUPLICATE KEY UPDATE value=\"".mpquot($_POST[$v['id']])."\"");
			mpevent("Заполнение заявки", $conf['tpl']['anket']['id'], $conf['tpl']['anket']['uid']);
		}
	}
}

if($_GET['id']){
	$conf['tpl']['opros'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id=".(int)$_GET['id']), 0);
	$conf['tpl']['vopros'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_vopros WHERE oid=".(int)$_GET['id']. " ORDER BY sort"));
	$conf['tpl']['select'] = mpql(mpqw("SELECT t.* FROM {$conf['db']['prefix']}{$arg['modpath']}_vopros AS v, {$conf['db']['prefix']}{$arg['modpath']}_variant AS t WHERE v.id=t.vid AND v.oid=".(int)$_GET['id']." ORDER BY t.sort"));
	$conf['tpl']['result'] = spisok("SELECT vid, value FROM {$conf['db']['prefix']}{$arg['modpath']}_result WHERE aid=".(int)$conf['tpl']['anket']['id']);
}else{
	$conf['tpl']['list'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}"));
}

?>