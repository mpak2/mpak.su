<? die;

$tn = array(($f = 'files')=>"_{$f}",);

mpqw("UPDATE {$conf['db']['prefix']}{$arg['modpath']}{$tn[$_GET['tn']]} SET count=count+1 WHERE id=". (int)$_GET['id']);

$sql = "SELECT {$arg['fn']} FROM {$conf['db']['prefix']}{$arg['modpath']}{$tn[$_GET['tn']]} WHERE id=".(int)$_GET['id'];
$file_name = mpopendir("include")."/".mpql(mpqw($sql), 0, $arg['fn']);

//header("Content-Type: application/octet-stream");
header("Content-Type: video");
header("Content-Disposition: attachment; filename=\"" . basename($_GET['']) . "\"");
header("Content-Length: ".filesize($file_name));
header("Content-Transfer-Encoding: binary");
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Cache-Control: private', false);
header('Pragma: public');
header('Expires: 0');

$handle = fopen($file_name, 'rb');
while (!feof($handle)){
	echo fread($handle, 4096);
	ob_flush();
	flush();
}
fclose($handle); exit;

?>