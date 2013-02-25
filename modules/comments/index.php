<? die;

if(array_key_exists("null", $_GET)){
	$referer = urldecode("/". array_pop(explode("/", $_SERVER['HTTP_REFERER'], 4)));
	$ref = mpgt($referer);
	$modpath = array_shift(array_keys((array)$ref['m']));
	$fn = $ref['m'][ $modpath ] ?: "index";
	$wr = "name=\"". mpquot(str_replace("/". $conf['modules'][ $modpath ]['modname']. "/", "/". $conf['modules'][ $modpath ]['folder']. "/", $referer)). "\" OR name=\"". mpquot(str_replace("/". $conf['modules'][ $modpath ]['folder']. "/", "/". $conf['modules'][ $modpath ]['modname']. "/", $referer)). "\"";

	$conf['tpl']['deny'] = mpql(mpqw("SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']}_deny WHERE url=\"". mpquot($referer). "\""));

	if ($_POST || $_GET['post']){
		if (!($uid = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_url WHERE ". $wr), 0))){
			preg_match_all("/([0-9]+)/", $referer, $out);

			mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_url SET num=".(int)$out[1][0].", modpath=\"". mpquot($modpath). "\", fn=\"". mpquot($fn). "\", name=\"".htmlspecialchars(mpquot($referer))."\"");
			$uid = array('id'=>mysql_insert_id(), 'name'=>$referer, 'modpath'=>$modpath, 'fn'=>$fn, 'num'=>$out[1][0]);
		} $uid['name'] = urldecode($uid['name']);
		$text = preg_replace( '/(?<!S)((http(s?):\/\/)|(www\.[A-Za-zА-Яа-яЁё0-9-_]+\.))+([A-Za-zА-Яа-яЁё0-9\/*+-_?&;:%=.,#]+)/u', '<a href="http$3://$4$5" target="_blank" rel="nofollow">http$3://$4$5</a>', htmlspecialchars($_POST['text']));
		$text = preg_replace ( '/(?<!S)([A-Za-zА-Яа-яЁё0-9_.\-]+\@{1}[A-Za-zА-Яа-яЁё0-9\.|-|_]*[.]{1}[a-z-а-я]{2,5})/u', '<a href="mailto:$1">$1</a>', $text );

		mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_txt SET url_id=". (int)$uid['id']. ", time=".time().", uid=". (int)$conf['user']['uid']. ", uname=\"".htmlspecialchars(mpquot($_POST['uname']))."\", text='".mpquot($text)."'");

		$conf['tpl']['comments'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_txt WHERE id=". (int)mysql_insert_id()));
		if($tab = mpql(mpqw("SHOW TABLES WHERE `Tables_in_{$conf['db']['name']}`=\"{$conf['db']['prefix']}". mpquot($uid['modpath']). "_index\" OR `Tables_in_{$conf['db']['name']}`=\"{$conf['db']['prefix']}". mpquot($uid['modpath']). "\""), 0, "Tables_in_{$conf['db']['name']}")){
			$desc = mpql(mpqw($sql = "SELECT * FROM $tab WHERE id=". (int)$uid['num']), 0);
		} $mail = ($desc['mail'] ?: $desc['email']);
		mpevent("Добавление комментария", $referer, $desc['uid'] ?: $mail, $_POST);
	}else{
		$conf['tpl']['comments'] = mpql(mpqw($sql = "SELECT txt.* FROM {$conf['db']['prefix']}{$arg['modpath']}_txt AS txt, {$conf['db']['prefix']}{$arg['modpath']}_url AS url WHERE txt.url_id=url.id AND (". $wr. ") ORDER BY id DESC LIMIT 10"));
	}
}else{
	$tpl['comments'] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS txt.*, url.name AS url FROM {$conf['db']['prefix']}{$arg['modpath']}_txt AS txt LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_url AS url ON txt.url_id=url.id ORDER BY txt.id DESC LIMIT ". ($_GET['p']*20). ",20"));
	$tpl['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/20 AS cnt"), 0, 'cnt'));
}

?>