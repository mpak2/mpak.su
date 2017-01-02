<?

if(get($_POST,'text')){
	mpevent("Сообщение в чате", $_POST['text'], $conf['user']['uid']);
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_index SET time=". time(). ", uid=". intval($conf,'user','uid'). ", text=\"". mpquot(get($_POST,'text')). "\"");
}

if(array_key_exists('null', $_GET) && array_key_exists('chat', $_GET)){
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_usr SET time=". time(). ", uid=". (int)$conf['user']['uid']. " ON DUPLICATE KEY UPDATE time=". time());

	$tpl['usr'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_usr WHERE time>". (time()-$conf['settings']['chat_user_online']));
	$tpl['index'] = qn("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index ORDER BY id DESC LIMIT ". (intval(get($_GET,'cnt')) ?: 20));	
	$tpl['uid'] = qn("SELECT * FROM {$conf['db']['prefix']}users WHERE id IN (". in(rb(get($tpl,'usr'), "uid")). ") OR id IN (". in(rb($tpl['index'], "uid")). ")");
	ksort($tpl['index']);
}

?>
