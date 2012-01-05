<? die;

if($_GET['unsubscribe']){
	$subscribe = mpql(mpqw("SELECT u.index_id, id.id, id.time FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_unsubscribe AS u ON id.id=u.index_id WHERE id.id=". (int)$_GET['id']), 0);
	if($subscribe['index_id']){
		$conf['tpl']['unsubscribe'] = 2;
	}elseif(substr(md5("{$subscribe['id']}:{$subscribe['time']}"), 0, 12) == $_GET['unsubscribe']){
		$conf['tpl']['unsubscribe'] = 1;
		mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_unsubscribe SET time=". time(). ", index_id=". (int)$subscribe['id']);
	}// echo $conf['tpl']['unsubscribe'];
}elseif($arg['access'] >= 5){
	if($_POST['uid']){
		if($_POST['subject'] && $_POST['subject']){
			$subscribe = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_index WHERE id=". (int)$_POST['uid']), 0);
			mpsettings("subscribe_send_text", $_POST['text']);
			mpsettings("subscribe_send_subject", $_POST['subject']);
			foreach($subscribe as $k=>$v){
				$_POST['subject'] = str_replace("{". $k. "}", $v, $_POST['subject']);
				$_POST['text'] = str_replace("{". $k. "}", $v, $_POST['text']);
			}
			require_once(mpopendir('include/idna_convert.class.inc')); $IDN = new idna_convert();
//			if($conf['settings']["{$arg['modpath']}_title"]){
//				$_POST['text'] .= "\n\n{$conf['settings']["{$arg['modpath']}_title"]}";
//			}
			$_POST['text'] .= "\n\nОтписаться от рассылки можно пройдя по ссылке: ". $unsubscribe = "http://". $IDN->decode($_SERVER['HTTP_HOST']). "/{$arg['modpath']}/{$subscribe['id']}/unsubscribe:". substr(md5("{$subscribe['id']}:{$subscribe['time']}"), 0, 12);
			if($subscribe['mail'] && $conf['settings']['subscribe_send']){
				mpmail($subscribe['mail'], $_POST['subject'], nl2br($_POST['text']), $conf['settings']['subscribe_send_from']);
				mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mail SET time=". time(). ", index_id=". (int)$subscribe['id']);
			}
			echo $user['id'];
		} exit;
	}
//	$conf['tpl']['subscribe'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS DISTINCT id.id, id.*, u.id AS disable, m.time AS send FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_mail AS m ON id.id=m.index_id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_unsubscribe AS u ON id.id=u.index_id WHERE mail<>'' ORDER BY m.time LIMIT ". (int)$_GET['filter']. ", 100"));
	if(!$_GET['to']){
		header("Location: ". $_SERVER['REQUEST_URI']. "/to:10000");
		exit;
	}
	$conf['tpl']['subscribe'] = mpql(mpqw("SELECT SQL_CALC_FOUND_ROWS id.*, u.id AS disable, COUNT(DISTINCT m.id) AS count FROM {$conf['db']['prefix']}{$arg['modpath']}_index AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_mail AS m ON id.id=m.index_id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_unsubscribe AS u ON id.id=u.index_id WHERE mail<>''". ($_GET['to'] ? " AND id.id<". (int)$_GET['to'] : ""). " GROUP BY id.id LIMIT ". (int)$_GET['filter']. ", 100"));
	$conf['tpl']['count'] = mpql(mpqw("SELECT FOUND_ROWS() AS cnt"), 0, 'cnt');
}else{
	mpre($arg);
	exit;
}

?>