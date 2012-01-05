<? die;

$referer = array_pop(explode($_SERVER['HTTP_HOST'], $_SERVER['HTTP_REFERER'], 2));

$conf['tpl']['deny'] = mpql(mpqw("SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']}_deny WHERE url=\"". mpquot($referer). "\""));

if ($_POST || $_GET['post']){
	if (!($uid = mpql(mpqw("SELECT id FROM {$conf['db']['prefix']}{$arg['modpath']}_url WHERE name=\"".htmlspecialchars(mpquot($_SERVER['HTTP_REFERER']))."\" OR name=\"". mpquot($referer). "\""), 0, 'id'))){

		$ref = mpgt($referer);
		$modpath = array_shift(array_keys($ref['m']));
		$fn = $ref['m'][ $modpath ] ?: "index";

		preg_match_all("/([0-9]+)$/", $referer, $out);
		mpqw("INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_url SET num=".(int)$out[1][0].", modpath=\"". mpquot($modpath). "\", fn=\"". mpquot($fn). "\", name=\"".htmlspecialchars(mpquot($referer))."\"");
		$uid = mysql_insert_id();
	}
	$text = preg_replace( '/(?<!S)((http(s?):\/\/)|(www\.[A-Za-zА-Яа-яЁё0-9-_]+\.))+([A-Za-zА-Яа-яЁё0-9\/*+-_?&;:%=.,#]+)/u', '<a href="http$3://$4$5" target="_blank" rel="nofollow">http$3://$4$5</a>', htmlspecialchars($_POST['text']));
	$text = preg_replace ( '/(?<!S)([A-Za-zА-Яа-яЁё0-9_.\-]+\@{1}[A-Za-zА-Яа-яЁё0-9\.|-|_]*[.]{1}[a-z-а-я]{2,5})/u', '<a href="mailto:$1">$1</a>', $text );

	mpqw($sql = "INSERT INTO {$conf['db']['prefix']}{$arg['modpath']}_txt SET url_id=". (int)$uid. ", time=".time().", uid=". (int)$conf['user']['uid']. ", uname=\"".htmlspecialchars(mpquot($_POST['uname']))."\", text='".mpquot($text)."'");

	$conf['tpl']['comments'] = mpql(mpqw($sql = "SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_txt WHERE id=". (int)mysql_insert_id()));
}else{
	$conf['tpl']['comments'] = mpql(mpqw($sql = "SELECT txt.* FROM {$conf['db']['prefix']}{$arg['modpath']}_txt AS txt, {$conf['db']['prefix']}{$arg['modpath']}_url AS url WHERE txt.url_id=url.id AND (url.name=\"". mpquot($_SERVER['HTTP_REFERER']). "\" OR url.name=\"". mpquot($referer). "\") ORDER BY id DESC"));
}

?>