<? die;

$tn = array(''=>'_img', 'img'=>'_img');
$sql = "SELECT img FROM {$conf['db']['prefix']}{$arg['modpath']}{$tn[$_GET['tn']]} WHERE id=".(int)$_GET['id'];
$file_name = mpopendir("include")."/".mpql(mpqw($sql), 0, 'img');
header ("Content-type: image/". array_pop(explode('.', $file_name)));
echo mprs($file_name, $_GET['w'], $_GET['h'], $_GET['c']);

?>