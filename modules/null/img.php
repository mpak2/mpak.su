<? die;

if($_GET['tn']){
	$_GET['tn'] = urldecode($_GET['tn']);
	if(($i = $conf['settings'][$s = "{$arg['modpath']}=>img"]) && ($img = explode(",", $i))){ $tn = array_combine($img, $img); }else{
		$tn = array($_GET['tn']=>preg_replace('/[^\d\w-_]/u', '', $_GET['tn']));
	} $sql = "SELECT `". mpquot($_GET['fn'] ? $_GET['fn'] : "img"). "` FROM {$conf['db']['prefix']}{$arg['modpath']}_{$tn[$_GET['tn']]} WHERE id=".(int)$_GET['id'];
	$file_name = mpopendir("include")."/".($fn = mpql(mpqw($sql), 0, ($_GET['fn'] ? $_GET['fn'] : "img")));
	if(!array_search(array_pop(explode('.', $file_name)), array(1=>"jpg", "jpeg", "png", "gif"))){
		$file_name = mpopendir("img/ext/". array_pop(explode('.', $file_name)). ".png");
	}
	header ("Content-type: image/". array_pop(explode('.', $file_name)));
	echo mprs($file_name, $_GET['w'], $_GET['h'], $_GET['c']);
}elseif($_GET['']){
	$file_name = mpopendir("modules/{$arg['modpath']}/img/". basename($_GET['']));
	header ("Content-type: image/". array_pop(explode('.', $file_name)));
	echo mprs($file_name, $_GET['w'], $_GET['h'], $_GET['c']);
}else{
	echo "<ul>";
	foreach(mpreaddir("modules/{$arg['modpath']}/img", true) as $n=>$img){
		echo "<li style=\"display:inline-block; width:80px; overflow:hidden; text-align:center;\"><img src=\"/{$arg["modname"]}:{$arg['fn']}/w:50/h:50/null/{$img}\"><div><a href=\"/{$arg["modname"]}:{$arg['fn']}/null/{$img}\" title=\"{$img}\">{$img}</a></div></li>";
	}
	echo "</ul>";
}
