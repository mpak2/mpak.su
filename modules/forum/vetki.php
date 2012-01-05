<? die;

$readtree = function ($id, $readtree, $tree = array()) use($arg) { global $conf;
	foreach(mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE vetki_id=". (int)$id)) as $k=>$v){
		$tree[$v['id']] = $readtree($v['id'], $readtree);
		$tree[$v['id']][''] = $v;
	}
	return $tree;
}; $conf['tpl']['tree'] = $readtree($_GET['id'], $readtree);
$conf['tpl']['tree'][''] = array('fm'=>'/', 'name'=>$_SERVER['HTTP_HOST'], 'site'=>'На главную', 'url'=>'/');
//$conf['tpl']['index'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_index"));

//mpre($conf['tpl']['tree']);

?>