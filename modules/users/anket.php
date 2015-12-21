<? # Нуль

if($conf['user']['uid'] > 0){
	if(array_key_exists('null', $_GET) && $_POST){
		if($_POST['level']){
			$level_id = mpfdk("{$conf['db']['prefix']}pay_level", $w = array("name"=>$_POST['level']), $w += array("value"=>$_POST['val']), $w);
			exit($level_id);
		}else{
			$anket_data_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_anket_data", null, $w = array("time"=>time(), "uid"=>$arg['uid'], "anket_id"=>$_POST['anket_id'], "name"=>$_POST['val']));
			exit($anket_data_id);
		}
	};

	$tpl['anket'] = mpqn(mpqw("SELECT a.* FROM {$conf['db']['prefix']}{$arg['modpath']}_anket AS a LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_anket_type AS at ON (a.anket_type_id=at.id) WHERE reg<>1 ORDER BY at.sort, a.sort"), "anket_type_id", "id");// mpre($anket);
	$tables = mpqn(mpqw("SHOW TABLES"), "Tables_in_{$conf['db']['name']}");// mpre($tables);
	foreach($tpl['anket'] as $anket){
		foreach($anket as $a){
			if((substr($a['alias'], -3, 3) == "_id") && array_key_exists("{$conf['db']['prefix']}{$arg['modpath']}_". ($alias = substr($a['alias'], 0, -3)), $tables)){
				$select[ $a['alias'] ] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$alias}"));
			}
		}
	}// mpre($select);

	$tpl['anket_type'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_anket_type ORDER BY sort"));

	$tpl['anket_data'] = mpqn(mpqw("SELECT d.*
		FROM {$conf['db']['prefix']}{$arg['modpath']}_anket_data AS d
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_anket AS a ON (a.id=d.anket_id)
		LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_anket_type AS t ON (a.anket_type_id=t.id) 
		INNER JOIN (SELECT MAX(`id`) AS max, anket_id FROM {$conf['db']['prefix']}{$arg['modpath']}_anket_data WHERE uid=". (int)$arg['uid']. " GROUP BY `anket_id`) AS m ON (m.max=d.id)
		ORDER BY t.sort, a.sort DESC"
	), 'anket_id');// mpre($anket_data);

}else{
	$tpl['anket_type'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_anket_type ORDER BY sort"));

	$tpl['anket'] = mpqn(mpqw("SELECT a.* FROM {$conf['db']['prefix']}{$arg['modpath']}_anket AS a LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_anket_type AS at ON (a.anket_type_id=at.id) WHERE reg<2 ORDER BY at.sort, a.sort"), 'anket_type_id', 'id');

	$tpl['alias'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_anket WHERE alias<>''"), 'id');

	if(array_key_exists("null", $_GET) && $_POST["anket"]){
		foreach($_POST["anket"] as $anket_id=>$name){
			if(!empty($name)){
				$anket_data_id = mpfdk("{$conf['db']['prefix']}{$arg['modpath']}_anket_data", null, $w = array("time"=>time(), "uid"=>$conf['user']['uid'], "anket_id"=>$anket_id, "name"=>$name));
				if(!empty($tpl['alias'][ $anket_id ]['alias'])){
					$user[ $tpl['alias'][ $anket_id ]['alias'] ] = $name;
				}
			}
		}// mpre($user); exit;
		if($mpdbf = mpdbf($tn = "{$conf['db']['prefix']}users", array("type_id"=>1, "pass"=>mphash($user['name'], $user['pass']))+$user)){
			mpqw("INSERT INTO {$tn} SET {$mpdbf}");
			if($mysql_insert_id = mysql_insert_id()){
				mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_anket_data SET uid=". (int)$mysql_insert_id. " WHERE uid=". $conf['user']['uid']);

				if($refer = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE name=\"". mpquot($user['refer']). "\""), 0)){
					$user['refer'] = $refer['id'];
					mpqw("INSERT INTO {$conf['db']['prefix']}utree_index SET time=". time(). ", uid=". (int)$refer['id']. ", usr=". (int)$mysql_insert_id);
				}

				$user_grp = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_grp WHERE name=\"". mpquot($conf['settings']['user_grp']). "\""), 0);
				mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mem SET grp_id=". (int)$user_grp['id']. ", uid=\"". (int)$mysql_insert_id. "\"");
				mpqw("UPDATE {$conf['db']['prefix']}sess SET uid=". (int)$mysql_insert_id. " WHERE id=". (int)$conf['user']['sess']['id']);

				exit(''. $mysql_insert_id);
			}
		}
	}else if($_COOKIE["{$conf['db']['prefix']}refer"] && ($_COOKIE["{$conf['db']['prefix']}refer"] != $conf['user']['uid'])){
		$tpl['refer'] = mpql(mpqw("SELECT id, name, fm, im FROM {$conf['db']['prefix']}users WHERE id=". (int)$_COOKIE["{$conf['db']['prefix']}refer"]), 0);
	}
}

?>
