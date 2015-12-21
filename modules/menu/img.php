<?

if($_GET['tn']){
	if(($i = $conf['settings'][$s = "{$arg['modpath']}=>img"]) && ($img = explode(",", $i))){ $tn = array_combine($img, $img); }else{
		$tn = array($_GET['tn']=>preg_replace('/[^0-9a-z_]/', '', $_GET['tn']));
	} /*mpre($tn); exit;*/ $sql = "SELECT `". mpquot($_GET['fn'] ?: "img"). "` FROM {$conf['db']['prefix']}{$arg['modpath']}_{$tn[$_GET['tn']]} WHERE id=".(int)$_GET['id'];
	$file_name = mpopendir("include")."/".($fn = mpql(mpqw($sql), 0, ($_GET['fn'] ?: "img")));
//	if(empty($fn)){ $file_name = mpopendir("modules/{$arg['modpath']}/img/no.png"); }
}else{
//	$file_name = mpopendir("modules/{$arg['modpath']}/img/". basename($_GET['']));
}
header ("Content-type: image/". array_pop(explode('.', $file_name)));
echo mprs($file_name, $_GET['w'], $_GET['h'], $_GET['c']);

?>
