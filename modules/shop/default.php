<? die;

//$tpl['query:mpager'] = qn($sql = "SELECT SQL_CALC_FOUND_ROWS id,name FROM {$conf['db']['prefix']}{$arg['modpath']}_query WHERE index_id=". (int)$_GET['id']. " LIMIT ". ($_GET['p']*($mpager = 10). ",{$mpager}")); $tpl['mpager:query'] = mpager(ql("SELECT FOUND_ROWS()/{$mpager} AS cnt", 0, 'cnt'));
//$tpl['query'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_query WHERE id IN (". in($tpl['query:mpager']).")");

foreach(mpql(mpqw("SHOW TABLES WHERE Tables_in_{$conf['db']['name']} LIKE \"{$conf['db']['prefix']}{$arg['modpath']}\_%\"")) as $k=>$v){
	$t = implode("_", array_slice(explode("_", $v["Tables_in_{$conf['db']['name']}"]), 2));
	if((empty($conf['settings']["{$arg['modpath']}_tpl_exceptions"]) && !array_key_exists($t, (array)$tpl)) ||
		((!empty($conf['settings']["{$arg['modpath']}_tpl_exceptions"]) && (array_search($t, explode(",", $conf['settings']["{$arg['modpath']}_tpl_exceptions"])) === false)) && !array_key_exists($t, (array)$tpl))){
		$tpl[ $t ] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$t}"));
	}
}// mpre(array_keys($tpl));

//$tpl['uid'] = qn("SELECT * FROM {$conf['db']['prefix']}users WHERE id IN (". in(rb($tpl['index'], "uid")). ")");
