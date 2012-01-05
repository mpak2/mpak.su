<? die;

if ($_GET['id']){
	$tn = array('obj', 'desc', 'img', 'producer', 'sity');
	$fn = mpql(mpqw("SELECT img FROM {$conf['db']['prefix']}{$arg['modpath']}_".$tn[(int)$_GET['tn']]." WHERE id=".(int)$_GET['id']), 0, 'img');
	header("Content-type: image/". array_pop(explode('.', $fn)));
	echo mprs(mpopendir("include/$fn"), $_GET['w'], $_GET['h'], $_GET['c']);
}

?>