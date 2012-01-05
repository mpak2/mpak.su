<? die;


if($_POST['x'] && $_POST['y'] && $_GET['id']){
	mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_placemark SET x=". mpquot(substr($_POST['x'], 0, 8)). ", y=". mpquot(substr($_POST['y'], 0, 8)). " WHERE id=". (int)$_GET['id']. ($arg['access'] >= 5 ? '' : " AND uid=". (int)$conf['user']['uid']));
	echo (mysql_affected_rows() == 1 ? "accept" : "error"); exit;
}elseif(array_key_exists('add', $_GET)){
	$conf['tpl']['sity'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_sity WHERE id=". (int)($conf['user']['sess']['sity_id'] ?: $conf['user']['sity_id'])), 0);
	mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_placemark SET name=\"". mpquot($_POST['name']). "\", type_id=". (int)$_POST['type_id']. ", description=\"". mpquot($_POST['description']). "\", price=\"". mpquot($_POST['price']). "\", period=". (time()+max(86400, (int)$_POST['period'])). ", uid=". (int)($conf['settings']['default_usr'] == $conf['user']['uname'] ? -$conf['user']['sess']['id'] : $conf['user']['uid']). ", x=". (int)$conf['tpl']['sity']['x']. ", y=". (int)$conf['tpl']['sity']['y']);
	header("Location: /{$arg['modpath']}/drive:". mysql_insert_id());
	$cnt = mpql(mpqw("SELECT COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_placemark"), 0, 'cnt');
	mpqw("UPDATE {$conf['db']['prefix']}settings SET value=". (int)$cnt. " WHERE name=\"ymaps_cnt\"");
}

$conf['tpl']['placemark'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_placemark". ($_GET['drive'] ? " WHERE id=". (int)$_GET['drive'] : '')));
$conf['tpl']['sity'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_sity WHERE id=". (int)($conf['user']['sess']['sity_id'] ?: $conf['user']['sity_id'])), 0);

?>