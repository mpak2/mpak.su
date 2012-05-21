<? die;

if(array_key_exists("null", $_GET) && $_POST['lesson_id']){
	$lesson_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_step",
		$w= array("uid"=>$conf['user']['uid'], "hide"=>0),
		$w+= array("time"=>time(), "name"=>$_SERVER['HTTP_HOST'], 'left'=>100, 'top'=>100, "lesson_id"=>$_POST['lesson_id'], "index_id"=>0), $w
	);
}

if($tpl['lesson'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_step WHERE uid=". (int)$conf['user']['uid']. " AND hide=0"), 0)){
	if($_POST['left'] && $_POST['top']){
		mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_step SET `left`=". (int)$_POST['left']. ", `top`=". (int)$_POST['top']. " WHERE id=". (int)$tpl['lesson']['id']);
	}else if($tpl['index'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE lesson_id=". (int)$tpl['lesson']['lesson_id']. " AND id>". (int)$tpl['lesson']['index_id']. " ORDER BY id"), 0)){
		mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_step SET index_id=". (int)$tpl['index']['id']. " WHERE id=". (int)$tpl['lesson']['id']);
		$tpl['cmd'] = mpqn(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cmd WHERE index_id=". (int)$tpl['index']['id']), 'cmd_id', 'id');
	}
}

if($_GET['index_id']){
	$tpl['index'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$_GET['index_id']), 0);
	$tpl['cmd'] = mpqn(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cmd WHERE index_id=". (int)$tpl['index']['id']), 'cmd_id', 'id');
}

?>