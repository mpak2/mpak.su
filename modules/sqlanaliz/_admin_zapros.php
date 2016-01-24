<?

function geton($one, $two){
	global $_POST, $conf, $modules;
	$modules = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules WHERE id=". (int)$_POST['modules_id']), 0);
	if($two == "{$conf['db']['prefix']}users"){ $tp = "uid"; }else{
		$tp = str_replace("{$conf['db']['prefix']}{$modules['folder']}_", "", $two);
	} $op = str_replace("{$conf['db']['prefix']}{$modules['folder']}_", "", $one);
	$o = mpqn(mpqw($sql = "SHOW COLUMNS FROM `". mpquot($one). "`"), 'Field');
	$t = mpqn(mpqw($sql = "SHOW COLUMNS FROM `". mpquot($two). "`"), 'Field');
	if(array_key_exists("{$op}_id", $t)){
		$on = " ON `{$op}`.id=`{$tp}`.{$op}_id";
	}else if(array_key_exists("{$tp}_id", $o)){
		$on = " ON `{$op}`.{$tp}_id=`{$tp}`.id";
	}else if($two == "{$conf['db']['prefix']}users"){
		$on = " ON uid=`{$tp}`.id";
	} /*echo "<br />$on";*/ return $on;
}

$conf['tpl']['modules'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}modules ORDER BY name"));

if($_POST['tn'] && $_POST['join']){
	$modules = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}modules WHERE id=". (int)$_POST['modules_id']), 0);
	$sql = "SELECT". ($_GET['p'] ? " SQL_CALC_FOUND_ROWS" : ""). " * FROM";
	foreach($_POST['tn'] as $k=>$v){
		if($k) $on = geton($prev, $v);
		if($v == "{$conf['db']['prefix']}users"){
			$prx = "uid";
			$tn = str_replace("{$conf['db']['prefix']}", "{\$conf['db']['prefix']}", $v);
		}else{
			$prx = str_replace("{$conf['db']['prefix']}{$modules['folder']}_", "", $v);
			$tn = str_replace("{$conf['db']['prefix']}{$modules['folder']}", "{\$conf['db']['prefix']}{\$arg['modpath']}", $v);
		} $prev = $v; if(!$k) $tpl = $prx;
		$sql .= " `$tn`  AS `{$prx}` ". ($on ?: ""). ($k+1 < count($_POST['tn']) ? " ". $_POST['join'][ $k ] : "");
	}
	if($_POST['where']) $sql .= " WHERE {$_POST['where']}";
	$limit = ($_POST['p'] ? " LIMIT \". (\$_GET['p']*". (int)$_POST['p']. "). \"". ",". (int)$_POST['p'] : "");
	$conf['tpl']['tpl'] =  ($_POST['tpl'] ? "\$conf['tpl']['{$tpl}'] = mpqn(mpqw(\"{$sql}{$limit}\"));" : $sql);

	$sql = strtr($sql, array("{\$conf['db']['prefix']}"=>$conf['db']['prefix'], "{\$arg['modpath']}"=>$modules['folder']));
	$conf['tpl']['zapros'] = mpqn(mpqw(strtr("$sql LIMIT 10", array("SELECT"=>"SELECT SQL_CALC_FOUND_ROWS"))));

	$conf['tpl']['count'] = mpql(mpqw("SELECT FOUND_ROWS() AS cnt"), 0, 'cnt');

}elseif($_POST['modules_id']){
	$conf['tpl']['mod'] = mpqn(mpqw($sql = "SHOW tables FROM `{$conf['db']['name']}` WHERE `Tables_in_{$conf['db']['name']}` LIKE \"". mpquot($conf['db']['prefix']. $conf['tpl']['modules'][ $_POST['modules_id'] ]['folder']). "%\" OR Tables_in_{$conf['db']['name']}=\"". mpquot($conf['db']['prefix']. "users"). "\""), "Tables_in_{$conf['db']['name']}");

	foreach($conf['tpl']['mod'] as $k=>$v){
		$conf['tpl']['mod'][$k]['Fields'] = mpqn(mpqw($sql = "SHOW COLUMNS FROM `". mpquot($k). "`"), 'Field');
	}
}

?>
