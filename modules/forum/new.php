<? die;

if($_POST['name'] && $_GET['id']){
	mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_vetki SET vetki_id=". (int)abs($_GET['id']). ", name=\"". mpquot($_POST['name']). "\", aid=". ($aid = $_POST['aid'] ?: 3). ", description=''");// echo $sql;

	if(($vetki_id = mysql_insert_id()) && ($aid == 3)){

		$_POST['text'] = preg_replace( '/(?<!S)((http(s?):\/\/)|(www\.[A-Za-zА-Яа-яЁё0-9-_]+\.))+([A-Za-zА-Яа-яЁё0-9\/*+-_?&;:%=.,#]+)/u', '<a href="http$3://$4$5" target="_blank" rel="nofollow">http$3://$4$5</a>', htmlspecialchars($_POST['text']));
		$_POST['text'] = preg_replace ( '/(?<!S)([A-Za-zА-Яа-яЁё0-9_.\-]+\@{1}[A-Za-zА-Яа-яЁё0-9\.|-|_]*[.]{1}[a-z-а-я]{2,5})/u', '<a href="mailto:$1">$1</a>', $_POST['text'] );

		mpql(mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mess SET time=". time(). ", uid=". (int)$conf['user']['uid']. ", vetki_id=". (int)$vetki_id. ", text=\"". mpquot($_POST['text']). "\""));// echo $sql;
	} header("Location: /{$arg['modpath']}/vetki_id:". (int)$vetki_id); exit;
}

?>