<? die;

$_GET['id.id'] = $_GET['id']; unset($_GET['id']);
$conf['tpl']['taxi'] = mpql(mpqw("SELECT id.*, s.name AS sname, SUM(COALESCE(d.ves,0)*COALESCE(d.count,0)) AS sm FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index"). " AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON id.id=d.index_id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_type AS s ON id.type_id=s.id". mpwr($tn). " GROUP BY id.id ORDER BY sm DESC, id.type_id, id.name"));

$conf['tpl']['rating'] = mpql(mpqw("SELECT id.*, SUM(id.view) AS vw, SUM(d.count) AS cnt, SUM(COALESCE(d.ves,0)*COALESCE(d.count,0)) AS sm FROM ". ($tn = "{$conf['db']['prefix']}{$arg['modpath']}_index"). " AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON id.id=d.index_id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_type AS s ON id.type_id=s.id". mpwr($tn)), 0);
//mpre($conf['tpl']['rating']);

if($_GET['id'] = $_GET['id.id']){
	if(!array_key_exists('null', $_GET)) mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET view=view+1 WHERE id=". (int)$_GET['id']);
	$conf['settings']['title'] = "Рейтинг : ". $conf['tpl']['taxi'][0]['sname']. " : ". $conf['tpl']['taxi'][0]['name'];
	if($_POST['desc']){
		mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']}_desc SET count=count+1 WHERE id=". (int)$_POST['desc']);
		mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sess SET time=". time(). ", desc_id=". (int)$_POST['desc']. ", sess_id=". (int)$conf['user']['sess']['id']);
		exit;
	}elseif($_POST['ves'] && $_POST['name']){
		mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_desc SET index_id=". (int)$_GET['id']. ", count=1, ves=". ($_POST['ves'] == 'true' ? 1 : -1). ", name=\"". mpquot($_POST['name']). "\"");
		mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_sess SET time=". time(). ", desc_id=". (int)mysql_insert_id(). ", sess_id=". (int)$conf['user']['sess']['id']);
		echo $_POST['name']; exit;
	}
	$conf['tpl']['ves'] = mpql(mpqw("SELECT d.*, s.sess_id FROM {$conf['db']['prefix']}{$arg['modpath']}_desc AS d LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_sess AS s ON d.id=s.desc_id AND s.sess_id=". (int)$conf['user']['sess']['id']. " WHERE d.index_id=". (int)$_GET['id']. " ORDER BY d.count DESC, d.id"));
}else{
	if($_POST['index']){
		$sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_index SET uid=". (int)$conf['user']['uid']. ", type_id=". (int)$_POST['type_id']. ", name=\"". mpquot($_POST['index']). "\"";
		mpqw($sql); echo mysql_insert_id(); exit;
	}
	$conf['tpl']['type'] = mpqn(mpqw("SELECT s.*, COUNT(*) AS cnt, id.id AS iid FROM {$conf['db']['prefix']}{$arg['modpath']}_type AS s LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_index AS id ON s.id=id.type_id GROUP BY s.id ORDER BY cnt DESC, iid DESC, name"));
	$conf['tpl']['ves'] = mpqn(mpqw("SELECT d.index_id, d.ves, d.id FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id INNER JOIN {$conf['db']['prefix']}{$arg['modpath']}_desc AS d ON id.id=d.index_id"), 'index_id', 'ves', 'id');//	mpre($conf['tpl']['ves']);
}

?>