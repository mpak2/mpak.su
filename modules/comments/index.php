<?

if(array_key_exists("null", $_GET)){
	$referer=explode("/", get($_SERVER,'HTTP_REFERER'), 4);
	$referer = urldecode("/". array_pop($referer));
	$ref = mpgt($referer);
	$modpath = array_keys((array)get($ref,'m'));
	$modpath = array_shift($modpath);
	$fn = get($ref,'m',$modpath) ?: "index";
	$wr = "name=\"". mpquot(str_replace("/". get($conf,'modules',$modpath,'modname'). "/", "/". get($conf,'modules',$modpath,'folder'). "/", $referer)). "\" OR name=\"". mpquot(str_replace("/". get($conf,'modules',$modpath,'folder'). "/", "/". get($conf,'modules',$modpath,'modname'). "/", $referer)). "\"";

	$conf['tpl']['deny'] = mpql(mpqw("SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']}_deny WHERE url=\"". mpquot($referer). "\""));

	if ($_POST || get($_GET,'post')){
		if (!($uid = mpql(mpqw("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_url WHERE ". $wr), 0))){
			preg_match_all("/([0-9]+)/", $referer, $out);

			//mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_url SET num=".(int)$out[1][0].", modpath=\"". mpquot($modpath). "\", fn=\"". mpquot($fn). "\", name=\"".htmlspecialchars(mpquot($referer))."\"");
			$result = fk(
				"{$conf['db']['prefix']}{$arg['modpath']}_url",
				null,
				[
					"num"=>intval(get($out,1,0)),
					"modpath"=> mpquot($modpath), 
					"fn"=>mpquot($fn), 
					"name"=>htmlspecialchars(mpquot($referer))
				]
			);

			$uid = array('id'=>$result['id'], 'name'=>$referer, 'modpath'=>$modpath, 'fn'=>$fn, 'num'=>get($out,1,0));
		} $uid['name'] = urldecode($uid['name']);

		$text = preg_replace( '/(?<!S)((http(s?):\/\/)|(www\.[A-Za-zА-Яа-яЁё0-9-_]+\.))+([A-Za-zА-Яа-яЁё0-9\/*+-_?&;:%=.,#]+)/u', '<a href="http$3://$4$5" target="_blank" rel="nofollow">http$3://$4$5</a>', htmlspecialchars($_POST['text']));
		$text = preg_replace ( '/(?<!S)([A-Za-zА-Яа-яЁё0-9_.\-]+\@{1}[A-Za-zА-Яа-яЁё0-9\.|-|_]*[.]{1}[a-z-а-я]{2,5})/u', '<a href="mailto:$1">$1</a>', $text );

		$sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_txt SET url_id=". (int)$uid['id']. ", time=".time().", uid=". (int)$conf['user']['uid']. ", uname=\"".htmlspecialchars(mpquot($_POST['uname']))."\", text='".mpquot($text)."'";
		
		$result = fk(
			"{$conf['db']['prefix']}{$arg['modpath']}_txt",
			null,
			[
				"url_id"=>intval($uid['id']),
				"time"=>time(),
				"uid"=>intval($conf['user']['uid']), 
				"uname"=>htmlspecialchars(mpquot($_POST['uname'])), 
				"text"=>mpquot($text)
			]
		);

		$conf['tpl']['comments'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_txt WHERE id=". $result['id']));
		if($desc = mpql(mpqw("SHOW TABLES WHERE `Tables_in_{$conf['db']['name']}`=\"{$conf['db']['prefix']}". mpquot($uid['modpath']). "_index\" OR `Tables_in_{$conf['db']['name']}`=\"{$conf['db']['prefix']}". mpquot($uid['modpath']). "\""), 0, "Tables_in_{$conf['db']['name']}")){
			$desc = mpql(mpqw($sql = "SELECT * FROM $tab WHERE id=". (int)$uid['num']), 0);
		} 
		$mail = (get($desc,'mail') ?: get($desc,'email'));
		mpevent("Добавление комментария", $referer, $uid['id'] ?: $mail, $_POST);
	}else{
		$conf['tpl']['comments'] = mpql(mpqw($sql = "SELECT txt.* FROM {$conf['db']['prefix']}{$arg['modpath']}_txt AS txt, {$conf['db']['prefix']}{$arg['modpath']}_url AS url WHERE txt.url_id=url.id AND (". $wr. ") ORDER BY id DESC LIMIT 10"));
	}
}else{
	$tpl['comments'] = mpql(mpqw($sql = "SELECT SQL_CALC_FOUND_ROWS txt.*, url.name AS url FROM {$conf['db']['prefix']}{$arg['modpath']}_txt AS txt LEFT JOIN {$conf['db']['prefix']}{$arg['modpath']}_url AS url ON txt.url_id=url.id ORDER BY txt.id DESC LIMIT ". ($_GET['p']*20). ",20"));
	$tpl['mpager'] = mpager(mpql(mpqw("SELECT FOUND_ROWS()/20 AS cnt"), 0, 'cnt'));
}
