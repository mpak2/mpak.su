<? die;

if($_POST && array_key_exists('null', $_GET)){
	$href = "/". implode('/', array_slice((explode('/', urldecode($_SERVER['HTTP_REFERER']))), 3));
	mpevent("Задан вопрос на пользовательской странице", $_POST['text'], $_POST['uid'], $_POST);
	mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_index SET time=". time(). ", uid=". (int)$_POST['uid']. ", usr=". (int)$conf['user']['uid']. ", qw=\"". mpquot($_POST['text']). "\", href=\"". mpquot($href). "\"");
	echo mysql_insert_id(); exit;
}elseif($_GET['uid']){
	$conf['tpl']['cnt'] = mpql(mpqw("SELECT COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE uid=". (int)$_GET['uid']), 0, 'cnt');
}

?>