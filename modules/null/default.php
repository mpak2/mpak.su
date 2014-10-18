<? die;

//$tpl['index:mpager'] = qn($sql = "SELECT SQL_CALC_FOUND_ROWS id,name FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE 1". ($_GET['id'] ? " AND id=". (int)$_GET['id'] : " ORDER BY id DESC LIMIT ". ($_GET['p']*10). ",10"));
//$tpl['mpager:index'] = mpager(ql("SELECT FOUND_ROWS()/10 AS cnt", 0, 'cnt'));

//$tpl[$i = "index"] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$i} WHERE id IN (". in($tpl['index:mpager']).")");

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}\_%\"")) as $k=>$v){
	$t = implode("_", array_slice(explode("_", $v["Tables_in_{$conf['db']['name']}"]), 2));
	if((empty($conf['settings']["{$arg['modpath']}_tpl_exceptions"]) && !array_key_exists($t, (array)$tpl)) ||
		((!empty($conf['settings']["{$arg['modpath']}_tpl_exceptions"]) && (array_search($t, explode(",", $conf['settings']["{$arg['modpath']}_tpl_exceptions"])) === false)) && !array_key_exists($t, (array)$tpl))){
		$tpl[ $t ] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$t}"));
	}
}// mpre(array_keys($tpl));

//$tpl['uid'] = qn("SELECT * FROM {$conf['db']['prefix']}users WHERE id IN (". in(rb($tpl['index'], "uid")). ")");
