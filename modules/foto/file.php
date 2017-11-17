<?
if($_GET['id']){
	//$conf['defaultmimes'];
	$file = ql("SELECT * FROM {$conf['db']['prefix']}{$arg['modpath']}_". mpquot($_GET['tn']). " WHERE id=". (int)$_GET['id'], 0);
	header("Content-Disposition: inline; filename='". $file['name']. ".". array_pop(explode(".", $file['file'])). "'");
	header("Content-type: application/". array_pop(explode(".", $file['file'])));
	mpfile($file[ $_GET['fn'] ], $file['name']); //	mpre($file);
}
?>
