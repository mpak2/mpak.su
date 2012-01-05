<? die;

if($_GET['id']){
	$tn = array(''=>'');
	$sql = "SELECT img FROM {$conf['db']['prefix']}{$arg['modpath']}{$tn[$_GET['tn']]} WHERE id=".(int)$_GET['id'];
	$file_name = mpopendir("include/". ($fn = mpql(mpqw($sql), 0, 'img')));
	header ("Content-type: image/". array_pop(explode('.', $file_name)));
	echo mprs($fn ? $file_name : mpopendir("modules/users/img/unknown.png"), $_GET['w'], $_GET['h'], $_GET['c']);
}elseif(file_exists($fn = mpopendir("modules/{$arg['modpath']}/img/". basename($_GET[''])))){
	header ("Content-type: image/". array_pop(explode('.', $file_name)));
	echo mprs($fn, $_GET['w'], $_GET['h'], $_GET['c']);
}// mpre($_GET);

?>