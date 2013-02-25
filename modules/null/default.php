<? die;

mpre($arg);

$tpl['imgs'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}_imgs WHERE {$arg['fn']}_id=". (int)$_GET['id']);

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}\_%\"")) as $k=>$v){
	$t = implode("_", array_slice(explode("_", $v["Tables_in_{$conf['db']['name']}"]), 2));
	if(!array_key_exists($t, (array)$tpl)){
		$tpl[ $t ] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$t}"));
	}
}// mpre(array_keys($tpl));

if($e = $tpl[ $arg['fn'] ][ $_GET['id'] ]){
	$conf['settings']['title'] = $e['name'];
}

?>