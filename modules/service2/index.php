<? die;


if($_GET['id']){
	$conf['tpl']['index'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$_GET['id']));
	$conf['tpl']['img'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_img WHERE index_id=". (int)$conf['tpl']['index'][ $_GET['id']]['id']));
}elseif($_GET['cat_id']){
	$conf['tpl']['index'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE cat_id=". (int)$_GET['cat_id']));
}else{
	$readtree = function ($id, $readtree, $tree = array()) use($arg) { global $conf;
		if($id) $tree[''] = mpql(mpqw("SELECT c.*, COUNT(DISTINCT id.id) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_cat AS c LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON c.id=id.cat_id WHERE c.id=". (int)$id. " GROUP BY c.id"), 0);
		foreach(mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat WHERE cat_id=". (int)$id. " ORDER BY id DESC")) as $k=>$v){
			$tree[$v['id']] = $readtree($v['id'], $readtree);
		}
		return $tree;
	}; $conf['tpl']['tree'] = $readtree(0, $readtree);
	$conf['tpl']['tree'][''] = array('name'=>mpidn($_SERVER['HTTP_HOST']));
}

?>