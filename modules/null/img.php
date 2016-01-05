<?

if(array_key_exists('tn', $_GET)){
	$_GET['tn'] = urldecode($_GET['tn']);
	if(array_key_exists($s = "{$arg['modpath']}=>img", $conf['settings']) && ($i = $conf['settings'][$s]) && ($img = explode(",", $i))){ $tn = array_combine($img, $img); }else{
		$tn = array($_GET['tn']=>preg_replace('/[^\d\w-_]/u', '', $_GET['tn']));
	} $sql = "SELECT `". mpquot($_GET['fn'] ? $_GET['fn'] : "img"). "` FROM {$conf['db']['prefix']}{$arg['modpath']}_{$tn[$_GET['tn']]} WHERE id=".(int)$_GET['id'];
	$file_name = mpopendir("include")."/".($fn = mpql(mpqw($sql) , 0, ($_GET['fn'] ? $_GET['fn'] : "img")));
	$keys = array_keys($ar = explode('.', $file_name));
	if(!array_search($exp = strtolower($ar[max($keys)]), array(1=>"jpg", "jpeg", "png", "gif"))){
		$file_name = mpopendir("img/ext/". array_pop(explode('.', $file_name)). ".png");
	} if(!ob_get_length()){
		header ("Content-type: image/{$exp}");
	} echo mprs($file_name, (array_key_exists("w", $_GET) ? $_GET['w'] : 0), (array_key_exists("h", $_GET) ? $_GET['h'] : 0), (array_key_exists("c", $_GET) ? $_GET['c'] : 0));
}elseif(array_key_exists('', $_GET) && ($file_name = mpopendir("modules/{$arg['modpath']}/img/". basename($_GET[''])))){
	$keys = array_keys($ar = explode('.', $file_name));
	if(!ob_get_length()){
		header ("Content-type: image/". $ar[max($keys)]);
	} echo mprs($file_name, (array_key_exists("w", $_GET) ? $_GET['w'] : 0), (array_key_exists("h", $_GET) ? $_GET['h'] : 0), (array_key_exists("c", $_GET) ? $_GET['c'] : 0));
}else{
	echo "<ul>";
	foreach(mpreaddir("modules/{$arg['modpath']}/img", true) as $n=>$img){
		echo "<li style=\"display:inline-block; width:80px; overflow:hidden; text-align:center;\"><img src=\"/{$arg["modname"]}:{$arg['fn']}/w:50/h:50/null/{$img}\"><div><a href=\"/{$arg["modname"]}:{$arg['fn']}/null/{$img}\" title=\"{$img}\">{$img}</a></div></li>";
	} echo "</ul>";
}
