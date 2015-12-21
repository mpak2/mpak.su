<?

$readtree = function ($id, $readtree, $tree = array()) use($arg) { global $conf;
	if($id > 0){
		$tree[''] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}users WHERE id=". (int)$id), 0);
	}else{
		$tree[''] = array('uid'=>$id, 'name'=>"{$conf['settings']['default_usr']}{$id}")+mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}sess WHERE id=". abs($id)), 0);
		$tree['']['count_time'] = str_pad(floor($tree['']['count_time']/3600), 2, 0, STR_PAD_LEFT). ":". str_pad(floor($tree['']['count_time']/60)%60, 2, 0, STR_PAD_LEFT). ":". str_pad($tree['']['count_time']%60, 2, 0, STR_PAD_LEFT);
	}
	$list = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE refer=". (int)$id. " AND name<>\"". mpquot($conf['settings']['default_usr']). "\" ORDER BY id DESC"));
	foreach($list as $k=>$v){
		$tree[$v['id']] = $readtree($v['id'], $readtree);
	}
	return $tree;
}; $conf['tpl']['tree'] = $readtree(isset($_GET['uid']) ? (int)$_GET['uid'] : $conf['user']['uid'], $readtree);
