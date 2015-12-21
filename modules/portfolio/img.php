<?

if ($_GET['id']){
	$fn = mpql(mpqw("SELECT img FROM {$conf['db']['prefix']}{$arg['modpath']} WHERE id=".(int)$_GET['id']), 0, 'img');
	header("Content-type: image/". array_pop(explode('.', $fn)));
	echo mprs(mpopendir("include/$fn"), $_GET['w'], $_GET['h'], $_GET['c']);
}

?>
