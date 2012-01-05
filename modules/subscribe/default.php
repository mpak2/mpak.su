<? die;

if($_GET['unsubscribe']){
	$subscribe = mpql(mpqw("SELECT u.index_id, id.id, id.time FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_unsubscribe AS u ON id.id=u.index_id AND u.fn=\"". mpquot($arg['fn']). "\" WHERE id.id=". (int)$_GET['id']), 0);
	if($subscribe["index_id"]){
		$conf['tpl']['unsubscribe'] = 2;
	}elseif(substr(md5("{$subscribe['id']}:{$subscribe['time']}"), 0, 12) == $_GET['unsubscribe']){
		$conf['tpl']['unsubscribe'] = 1;
		mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_unsubscribe SET time=". time(). ", fn=\"". mpquot($arg['fn']). "\", index_id=". (int)$subscribe['id']);
	}// echo $conf['tpl']['unsubscribe'];
}elseif($arg['access'] >= 5){
//	echo "test ". mpsettings("subscribe_send_{$arg['fn']}_test", "Бла бла бла");
	if($_POST['uid']){
		if($_POST['subject'] && $_POST['text']){
			$subscribe = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} WHERE id=". (int)$_POST['uid']), 0);

			mpsettings("subscribe_send_{$arg['fn']}_text", $_POST['text']);
			mpsettings("subscribe_send_{$arg['fn']}_subject", $_POST['subject']);
			require_once(mpopendir('include/idna_convert.class.inc')); $IDN = new idna_convert();

			foreach($subscribe as $k=>$v){
				$_POST['subject'] = str_replace("{". $k. "}", $v, $_POST['subject']);
				$_POST['text'] = str_replace("{". $k. "}", $v, $_POST['text']);
			}
			$_POST['text'] = str_replace("{http_host}", $_SERVER['HTTP_HOST'], $_POST['text']);
			$_POST['subject'] = str_replace("{http_host}", $_SERVER['HTTP_HOST'], $_POST['subject']);

			$_POST['text'] = str_replace("{idn_host}", $IDN->decode($_SERVER['HTTP_HOST']), $_POST['text']);
			$_POST['subject'] = str_replace("{idn_host}", $IDN->decode($_SERVER['HTTP_HOST']), $_POST['subject']);
//			if($conf['settings']["{$arg['modpath']}_title"]){
//				$_POST['text'] .= "\n\n{$conf['settings']["{$arg['modpath']}_title"]}";
//			}
//			$_POST['text'] .= "\n\nОтписаться от рассылки: ". $unsubscribe = "http://". $IDN->decode($_SERVER['HTTP_HOST']). "/{$arg['modpath']}:{$arg['fn']}/{$subscribe['id']}/unsubscribe:". substr(md5("{$subscribe['id']}:{$subscribe['time']}"), 0, 12);
			if($subscribe['mail'] && $conf['settings']['subscribe_send']){
				mpmail($subscribe['mail'], $_POST['subject'], nl2br($_POST['text']), $conf['settings']['subscribe_send_from']);
				mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_mail SET time=". time(). ", fn=\"". mpquot($arg['fn']). "\", index_id=". (int)$subscribe['id']);
			}
			echo $user['id'];
		} exit;
	}
	if(!$_GET['to']){
		header("Location: ". $_SERVER['REQUEST_URI']. "/to:10000");
		exit;
	}
/*	$mail = mpqn(mpqw("SHOW COLUMNS FROM {$conf['db']['prefix']}{$arg['modpath']}_mail"), 'Field'); 
	$unsubscribe = mpqn(mpqw("SHOW COLUMNS FROM {$conf['db']['prefix']}{$arg['modpath']}_unsubscribe"), 'Field'); 
	if(mpql(mpqw("SHOW COLUMNS FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}")) && !$mail["{$arg['fn']}_id"]){
		mpqw("ALTER TABLE {$conf['db']['prefix']}{$arg['modpath']}_mail ADD `{$arg['fn']}_id` int(11) NOT NULL");
	}
	if(mpql(mpqw("SHOW COLUMNS FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']}")) && !$unsubscribe["{$arg['fn']}_id"]){
		mpqw("ALTER TABLE {$conf['db']['prefix']}{$arg['modpath']}_unsubscribe ADD `{$arg['fn']}_id` int(11) NOT NULL");
	}*/
	$conf['tpl']['subscribe'] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS id.*, u.id AS disable, COUNT(DISTINCT m.id) AS count FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} AS id LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_mail AS m ON id.id=m.index_id AND m.fn=\"". mpquot($arg['fn']). "\" LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_unsubscribe AS u ON id.id=u.index_id AND u.fn=\"". mpquot($arg['fn']). "\" WHERE mail<>''". ($_GET['to'] ? " AND id.id<". (int)$_GET['to'] : ""). " GROUP BY id.id LIMIT ". (int)$_GET['filter']. ", 100"));// echo $sql;// mpre($conf['tpl']['subscribe']);

	$conf['tpl']['count'] = mpql(mpqw($sql = "SELECT COUNT(*) AS cnt FROM {$conf['db']['prefix']}{$arg['modpath']}_{$arg['fn']} AS id WHERE mail<>''". ($_GET['to'] ? " AND id.id<". (int)$_GET['to'] : "")), 0, 'cnt');// echo $sql;// mpre($conf['tpl']['subscribe']);

//	echo "<br />". $conf['tpl']['count'] = mpql(mpqw("SELECT FOUND_ROWS() AS cnt"), 0, 'cnt');
}else{
	mpre($arg);
	exit;
}

?>