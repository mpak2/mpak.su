<?

if(get($_GET, 'tn')){
	$_GET['tn'] = urldecode($_GET['tn']);
	if(($i = get($conf, 'settings', "{$arg['modpath']}=>img")) && ($img = explode(",", $i))){ $tn = array_combine($img, $img); }else{
		$tn = array($_GET['tn']=>preg_replace('/[^\d\w-_]/u', '', $_GET['tn']));
	} $sql = "SELECT `". mpquot($_GET['fn'] ? $_GET['fn'] : "img"). "` FROM {$conf['db']['prefix']}{$arg['modpath']}_{$tn[$_GET['tn']]} WHERE id=".(int)$_GET['id'];
	$file_name = mpopendir("include")."/".($fn = mpql(mpqw($sql) , 0, ($_GET['fn'] ? $_GET['fn'] : "img")));
	if(!array_search($exp = strtolower(last(explode('.', $file_name))), array(1=>"jpg", "jpeg", "png", "gif"))){
		$file_name = mpopendir($f = "img/no.". ($exp = "png"));
	} if(!ob_get_length()){
		header ("Content-type: image/{$exp}");
	} echo mprs($file_name, (get($_GET, "w") ?: 0), (get($_GET, "h") ?: 0), (get($_GET, "c") ?: 0));
}elseif(array_key_exists('', $_GET) && ($file_name = mpopendir("modules/{$arg['modpath']}/img/". basename($_GET[''])))){
	if(!ob_get_length()){
		header ("Content-type: image/". last(explode('.', $file_name)));
	} echo mprs($file_name, (get($_GET, "w") ?: 0), (get($_GET, "h") ?: 0), (get($_GET, "c") ?: 0));
}else{
	echo "<ul>";
	foreach(mpreaddir("modules/{$arg['modpath']}/img", true) as $n=>$img){
		echo "<li style=\"display:inline-block; width:80px; overflow:hidden; text-align:center;\"><img src=\"/{$arg["modpath"]}:{$arg['fn']}/w:50/h:50/null/{$img}\"><div><a href=\"/{$arg["modpath"]}:{$arg['fn']}/null/{$img}\" title=\"{$img}\">{$img}</a></div></li>";
	} echo "</ul>";
}
