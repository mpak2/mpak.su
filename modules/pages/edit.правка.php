<? die;

if($_POST['name'] && $_POST['text']){
	if($_POST['id']){
		mpqw($sql = "UPDATE {$conf['db']['prefix']}{$arg['modpath']}_index SET name=\"". mpquot($_POST['name']). "\", text=\"". mpquot($_POST['text']). "\" WHERE uid=". $conf['user']['uid']. " AND id=". (int)$_POST['id']);
	}else if($mpdbf = mpdbf("{$conf['db']['prefix']}{$arg['modpath']}_index", array('time'=>time(), 'uid'=>$conf['user']['uid'])+array_diff_key($_POST, array('text'=>'')))){
		mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_index SET text=\"". mpquot($_POST['text']). "\", $mpdbf");
		$_GET['id'] = mysql_insert_id();
	} $conf['tpl']['mysql_fetch_rows'] = mysql_affected_rows();
}elseif($_GET['del']){
	mpqw("DELETE FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_GET['del']);
	header("Location: {$_SERVER['HTTP_REFERER']}"); exit;
}

$conf['tpl']['page'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$conf['user']['uid']. " AND id=". (int)$_GET['id']), 0);
$conf['tpl']['cat'] = mpqn(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_cat ORDER BY name"));

?>