<? die;

if($_POST['text']){
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_index SET time=". time(). ", uid=". (int)$conf['user']['uid']. ", text=\"". mpquot($_POST['text']). "\"");
}

if(array_key_exists('null', $_GET) && array_key_exists('chat', $_GET)){
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_usr SET time=". time(). ", uid=". (int)$conf['user']['uid']. " ON DUPLICATE KEY UPDATE time=". time());

	$conf['tpl']['data'] = mpql(mpqw("SELECT id.*, u.name FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}users AS u ON id.uid=u.id ORDER BY id DESC LIMIT ". ((int)$_GET['cnt'] ?: 20)));
	$conf['tpl']['users'] = spisok("SELECT cu.uid, u.name FROM {$conf['db']['prefix']}{$arg['modpath']}_usr AS cu LEFT JOIN {$conf['db']['prefix']}users AS u ON cu.uid=u.id WHERE time>". (time()-$conf['settings']['chat_user_online']. " ORDER BY u.id"));
	krsort($conf['tpl']['data']);
}

?>