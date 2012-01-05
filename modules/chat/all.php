<? die;

$conf['db']['back'] = $conf['db']['conn'];
$conf['db']['conn'] = @mysql_connect('localhost', $conf['settings']['chat_all_login'], $conf['settings']['chat_all_pass']); # Соединение с базой данных
mysql_select_db('fobs_su', $conf['db']['conn']); mpqw("SET NAMES UTF8");

if($_POST['text'] && ($arg['access'] > 1)){
	require_once(mpopendir('include/idna_convert.class.inc')); $IDN = new idna_convert();
	$text = preg_replace( '/(?<!S)((http(s?):\/\/)|(www\.[A-Za-zА-Яа-яЁё0-9-_]+\.))+([A-Za-zА-Яа-яЁё0-9\/*+-_?&;:%=.,#]+)/u', '<a href="http$3://$4$5" target="_blank" rel="nofollow">http$3://$4$5</a>', htmlspecialchars($_POST['text']));
	$text = preg_replace ( '/(?<!S)([A-Za-zА-Яа-яЁё0-9_.\-]+\@{1}[A-Za-zА-Яа-яЁё0-9\.|-|_]*[.]{1}[a-z-а-я]{2,5})/u', '<a href="mailto:$1">$1</a>', $text );
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_index SET time=". time(). ", uid=". (int)$conf['user']['uid']. ", usr=". (int)$_POST['usr']. ", uname=\"". mpquot($_POST['uname']). "\", host=\"". mpquot($IDN->decode($_POST['host'])). "\", text=\"". mpquot($text). "\"");
}

if(array_key_exists('null', $_GET) && array_key_exists('chat', $_GET)){
	mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_usr SET time=". time(). ", uid=". (int)$conf['user']['uid']. ", usr=". (int)$_GET['usr']. ", uname=\"". mpquot($_GET['usr'] < 0 ? "{$conf['settings']['default_usr']}_". abs($_GET['usr']) : $_GET['uname']). "\", host=\"". mpquot($_GET['host']). "\" ON DUPLICATE KEY UPDATE uid=". (int)$conf['user']['uid']. ", time=". time(). ", usr=". (int)$_GET['usr']. ", uname=\"". mpquot($_GET['usr'] < 0 ? "{$conf['settings']['default_usr']}_". abs($_GET['usr']) : $_GET['uname']). "\", host=\"". mpquot($_GET['host']). "\"");

	$conf['tpl']['data'] = mpql(mpqw("SELECT id.*, u.name FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}users AS u ON id.uid=u.id ORDER BY id DESC LIMIT ". ((int)$_GET['cnt'] ?: 20)));
	$conf['tpl']['users'] = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_usr WHERE time>". (time()-$conf['settings']['chat_user_online']. " ORDER BY id")));
	krsort($conf['tpl']['data']);
}
$conf['db']['conn'] = $conf['db']['back'];

?>