<? die;

/*if($_GET['id']){
	$usr = mpql(mpqw("SELECT name, reg_time, last_time FROM {$conf['db']['prefix']}utree_index WHERE usr=". (int)$conf['user']['uid']));
	if(!$conf['user']['sess']['count'] && !$usr){
		$sql = "INSERT INTO {$conf['db']['prefix']}utree_index SET time=". time(). ", uid=". (int)$_GET['id']. ", usr=". (int)$conf['user']['uid']; mpqw($sql);
	} header("Location: /services/uid:". (int)$_GET['id']); exit;
}else{
//	mpqw("INSERT INTO {$conf['db']['prefix']}utree_index SET time=". time(). ", usr=". (int)$conf['user']['uid']. " ON DUPLICATE KEY UPDATE view=view+1");
}*/

$readtree = function ($id, $readtree, $tree = array()) use($arg){ global $conf;
	if($id > 0){
		$tree[''] = array("uid"=>$id)+mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE id=". (int)$id), 0);
	}else{
		$tree[''] = array('uid'=>$id, 'name'=>"{$conf['settings']['default_usr']}{$id}")+mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}sess WHERE id=". abs($id)), 0);
		$tree['']['count_time'] = str_pad(floor($tree['']['count_time']/3600), 2, 0, STR_PAD_LEFT). ":". str_pad(floor($tree['']['count_time']/60)%60, 2, 0, STR_PAD_LEFT). ":". str_pad($tree['']['count_time']%60, 2, 0, STR_PAD_LEFT);
	}
	foreach(mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$id. " ORDER BY id DESC")) as $k=>$v){
		$tree[$v['usr']] = $readtree($v['usr'], $readtree);
	}
	return $tree;
}; $conf['tpl']['tree'] = $readtree(((array_key_exists("id", $_GET) && ($arg['access'] >= 3)) ? (int)$_GET['id'] : $conf['user']['uid']), $readtree);

$conf['tpl']['index'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_index"));

?>