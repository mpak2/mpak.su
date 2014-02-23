<? die;

if($_POST){
	if ($user = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}users WHERE name='".mpquot($_POST['uname']). "' OR id=". (int)$_POST['addr']), 0)){
		require_once(mpopendir('include/idna_convert.class.inc')); $IDN = new idna_convert();
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']} SET time=". time(). ", uid=".(int)$conf['user']['uid']. ", addr=". (int)$user['id']. ", title='". mpquot($_POST['title'])."', text='". mpquot(htmlspecialchars($_POST['text']))."'");
		mpevent("Новое личное сообщение", mysql_insert_id(), $user['id']);
		$conf['tpl']['error'] = "Сообщение отправлено.";
	}else{
		$conf['tpl']['error'] = "Адресат неизвестен.";
	}
}

if ($_GET['id']){
	$conf['tpl']['mess'] = mpql(mpqw("SELECT m.*, u.name FROM {$conf['db']['prefix']}{$arg['modpath']} as m LEFT JOIN {$conf['db']['prefix']}users as u ON m.uid=u.id WHERE m.addr=". (int)$conf['user']['uid']. " AND m.id=".(int)$_GET['id']), 0);
}elseif($_GET['uid']){
	$conf['tpl']['mess'] = mpql(mpqw($sql = "SELECT id AS uid, name FROM {$conf['db']['prefix']}users WHERE id=".(int)$_GET['uid']), 0);
	$conf['settings']['title'] = "Новое сообщение для ". $conf['tpl']['mess']['name'];
	$conf['settings']['description'] = "Создение нового сообщения для пользователя ". $conf['tpl']['mess']['name'];
}

?>