<? die;

if($_POST['name'] && $_GET['id']){
	mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_vetki SET vetki_id=". (int)abs($_GET['id']). ", name=\"". mpquot($_POST['name']). "\", aid=". ($_POST['aid'] ?: 3). ", description=''");// echo $sql;

	if(($vetki_id = mysql_insert_id()) && ($_POST['aid'] == 3)){
		mpql(mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mess SET time=". time(). ", uid=". (int)$conf['user']['uid']. ", vetki_id=". (int)$vetki_id. ", text=\"". mpquot($_POST['text']). "\""));// echo $sql;
	} header("Location: /{$arg['modpath']}/vetki_id:". (int)$vetki_id); exit;
}

?>