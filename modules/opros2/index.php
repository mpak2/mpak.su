<? die;

if($_GET['anket_id']){
	$conf['tpl']['anket'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_anket WHERE id=". (int)$_GET['anket_id']), 0);
	$conf['index'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$conf['tpl']['anket']['index_id']), 0);
	if($conf['index']['uid'] == $conf['user']['uid']){
		$conf['tpl']['result'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_result WHERE anket_id=". (int)$conf['tpl']['anket']['id']), 'vopros_id', 'variant_id');//	mpre($conf['tpl']['result']);
	}else{
		header("Location: /{$arg['modpath']}/{$conf['index']['id']}");
	} $_GET['id'] = $conf['tpl']['anket']['index_id'];
}

if($_GET['id']){

	$conf['tpl']['index'] = mpqn(mpqw("SELECT id.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}users AS u ON id.uid=u.id WHERE id.id=". (int)$_GET['id']));

	$conf['tpl']['type'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_type"));
	$conf['tpl']['vopros'] = mpqn(mpqw("SELECT v.id AS vid, v.* FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_vopros AS v ON id.id=v.index_id WHERE id.id=". (int)$_GET['id']. " ORDER BY v.type_id, v.sort, v.id"), 'type_id', 'vid');
	$conf['tpl']['variant'] = mpqn(mpqw("SELECT vt.* FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_vopros AS v ON id.id=v.index_id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_variant AS vt ON v.id=vt.vopros_id WHERE id.id=". (int)$_GET['id']), 'vopros_id', 'id');

	if($_POST && $conf['tpl']['index'][ $_GET['id'] ]){

		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_anket SET time=". time(). ", index_id=". (int)$_GET['id']. ", uid=". (int)$conf['user']['uid']);
		if($conf['tpl']['anket_id'] = mysql_insert_id()){
			foreach($conf['tpl']['vopros'] as $type_id=>$vopros){
				foreach($vopros as $vopros_id=>$v){
					if(($v['type'] == 'check') && !empty($_POST[ $vopros_id ])){
						foreach($_POST[ $vopros_id ] as $variant_id=>$val){
							$sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_result SET anket_id=". (int)$conf['tpl']['anket_id']. ", vopros_id=". (int)$vopros_id. ", variant_id=". (int)$variant_id;
							mpqw($sql);
						}
					}elseif($_POST[$vopros_id] && (($v['type'] == 'text') || ($v['type'] == 'textarea'))){
						$sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_result SET anket_id=". (int)$conf['tpl']['anket_id']. ", vopros_id=". (int)$vopros_id. ", val=\"". mpquot($_POST[$vopros_id]). "\"";
						mpqw($sql);
					}elseif(($v['type'] == 'file') && ($_FILES[$vopros_id]['error']['file'] === 0)){
						$sql = "INSERT INTO ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_result"). " SET anket_id=". (int)$conf['tpl']['anket_id']. ", vopros_id=". (int)$vopros_id;
						if(mpqw($sql) && ($insert_id = mysql_insert_id()) && ($fn = mpfn($tn, 'file', $insert_id, $vopros_id, array("*"=>"*")))){
							mpqw($sql = "UPDATE $tn SET file=\"". mpquot($fn). "\" WHERE id=". (int)$insert_id);
						}
					}elseif((int)$_POST[ $vopros_id ]){
						$sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_result SET anket_id=". (int)$conf['tpl']['anket_id']. ", vopros_id=". (int)$vopros_id. ", variant_id=". (int)$_POST[$vopros_id];
						mpqw($sql);
					}
					$res[] = "\n#{$v['id']} {$v['name']} : ". $_POST[$vopros_id];
				}
			} mpevent("Заполнение заявки", $_SERVER['REQUERT_URI'], $conf['tpl']['index'][ $_GET['id'] ]['uid'], "/opros2/anket_id:{$conf['tpl']['anket_id']}", implode("<br />", $res));
		}
	}else{
		mpevent("Просмотр заявки", $_SERVER['REQUERT_URI'], $conf['tpl']['index'][ $_GET['id'] ]['uid']);
	}
}else{
	$conf['tpl']['index'] = mpql(mpqw("SELECT o.*, u.name AS uname FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS o LEFT JOIN {$conf['db']['prefix']}users AS u ON o.uid=u.id ORDER BY o.id DESC"));
}

?>