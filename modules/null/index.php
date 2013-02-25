<? die;

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}\_%\"")) as $k=>$v){
	$t = implode("_", array_slice(explode("_", $v["Tables_in_{$conf['db']['name']}"]), 2));
	if(!array_key_exists($t, (array)$tpl)){
		$tpl[ $t ] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$t}"));
	}
}// mpre(array_keys($tpl));

