<? die;


$conf['tpl']['index'] = mpqn(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}"). mpwr($tn, $_GET). " ORDER BY id DESC LIMIT ". ($_GET['p']*8). ", 8"));
$conf['tpl']['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/8 AS cnt"), 0, 'cnt'));

if($_GET['id']){
	$conf['settings']['title'] = $conf['tpl'][$arg['fn']][ $_GET['id'] ]['name'];
	$conf['settings']['description'] = $conf['tpl'][$arg['fn']][ $_GET['id'] ]['description'];
	foreach($conf['tpl'][$arg['fn']][ $_GET['id'] ] as $k=>$v){
		if(substr($k, -3) == '_id'){
			$conf['tpl'][ $k ] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_". substr($k, 0, strlen($k)-3)));
		}
	}
}else{
	$conf['tpl']['prof'] = mpqn(mpqw("SELECT p.*, COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_prof AS p, {$conf['db']['prefix']}{$arg['modpath']}_index AS id WHERE p.id=id.prof_id GROUP BY p.id")); //mpre($conf['tpl']['prof']);

	foreach($conf['tpl']['index'] as $k=>$v){
		$uid[ $v['uid'] ] = $v['id'];
	}// mpre($uid);

	$conf['tpl']['users'] = mpqn(mpqw("SELECT id, name FROM {$conf['db']['prefix']}users WHERE id IN (". implode(',', array_keys($uid)). ")"));// mpre($conf['tpl']['users']);

//	$conf['tpl']['sity_id'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_sity"));
}

?>