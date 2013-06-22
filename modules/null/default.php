<? die;

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}\_%\"")) as $k=>$v){
	$t = implode("_", array_slice(explode("_", $v["Tables_in_{$conf['db']['name']}"]), 2));
	if((empty($conf['settings']["{$arg['modpath']}_tmp_exceptions"]) && !array_key_exists($t, (array)$tpl)) || (!empty($conf['settings']["{$arg['modpath']}_tmp_exceptions"]) && (array_search($t, explode(",", $conf['settings']["{$arg['modpath']}_tmp_exceptions"])) === false))){
		$tpl[ $t ] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$t}"));
	}
}// mpre(array_keys($tpl));
