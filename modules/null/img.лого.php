<? die;

if($_GET['tn']){
	$tn = array(
		($f = 'index')=>"_{$f}",
	);
	$sql = "SELECT `". mpquot($_GET['fn'] ?: "img"). "` FROM {$conf['db']['prefix']}{$arg['modpath']}{$tn[$_GET['tn']]} WHERE id=".(int)$_GET['id'];
	$file_name = mpopendir("include")."/".($fn = mpql(mpqw($sql), 0, ($_GET['fn'] ?: "img")));
//	if(empty($fn)){ $file_name = mpopendir("modules/{$arg['modpath']}/img/no.png"); }
}else{
//	$file_name = mpopendir("modules/{$arg['modpath']}/img/". basename($_GET['']));
}
header ("Content-type: image/". array_pop(explode('.', $file_name)));
echo mprs($file_name, $_GET['w'], $_GET['h'], $_GET['c']);

?>