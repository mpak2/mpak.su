<? die;

//$tpl[ $arg['fe'] ] = qn("SELECT SQL_CALC_FOUND_ROWS * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fe']}". ($_GET['id'] ? " WHERE id=". (int)$_GET['id'] : " LIMIT ". ($_GET['p']*20). ",20"));
//$tpl['mpager'] = mpager(ql("SELECT FOUND_ROWS()/20 AS cnt", 0, 'cnt'));

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}\_%\"")) as $k=>$v){
	$t = implode("_", array_slice(explode("_", $v["Tables_in_{$conf['db']['name']}"]), 2));
	if((empty($conf['settings']["{$arg['modpath']}_tmp_exceptions"]) && !array_key_exists($t, (array)$tpl)) ||
		((!empty($conf['settings']["{$arg['modpath']}_tmp_exceptions"]) && (array_search($t, explode(",", $conf['settings']["{$arg['modpath']}_tmp_exceptions"])) === false)) && !array_key_exists($t, (array)$tpl))){
		$tpl[ $t ] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$t}"));
	}
} // mpre(array_keys($tpl));

//$tpl['uid'] = qn("SELECT * FROM {$conf['db']['prefix']}users WHERE id IN (". implode(",", array_keys(rb($tpl['index'], "uid")) ?: array(0)). ")");
