<? die;

$conf['tpl']['index'] = mpqn(mpqw("SELECT * FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index"). mpwr($tn)));

if($v = $conf['tpl']['index'][ $_GET['id'] ]){
	$conf['tpl']['href'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_href WHERE index_id=". (int)$v['id']));
}

?>